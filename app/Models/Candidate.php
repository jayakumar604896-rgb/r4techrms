<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Candidate extends Model
{
    use HasFactory;

    protected $table = 'candidates';
    protected $primaryKey = 'id';
    protected $keyType = 'int';

    protected $fillable = [
        'candidate_name',
        'age',
        'DOS',
        'resume',
        'company_serial_number',
        'code_no',
        'dob',
        'passport_details',
        'qualfn',
        'sector',
        'industry',
        'designation',
        'placement_type',
        'total_exp',
        'post_location_applied',
        'current_stay',
        'ctc',
        'notice_period',
        'mobile',
        'user_type',
        'remarks',
        'email',
        'emp_code',
        'sub_division',
        'passport_number',
        'passport_valid_date',
        'indian_experience',
        'foreign_experience',
        'status_remarks',
        'status',  // Rename to 'current_status' for consistency?
        'company_id',
		'selected_at',
        'interview_date'
    ];

    // Add this casting for interview_date
    protected $casts = [
        'interview_date' => 'datetime',
        'dob' => 'date',
        'DOS' => 'date',
        'passport_valid_date' => 'date',
    ];

    public $timestamps = true;

    /**
     * Get the status history for this candidate
     */
    public function statusHistory()
    {
        return $this->hasMany(StatusHistory::class)->orderBy('created_at', 'desc');
    }

    /**
     * Get the latest status update
     */
    public function latestStatus()
    {
        return $this->hasOne(StatusHistory::class)->latestOfMany();
    }

    /**
     * Get the current company (client) for this candidate
     */
    public function company()
    {
        return $this->belongsTo(Client::class, 'company_id');
    }

    /**
     * Get the resume URL
     */
    public function getResumeUrlAttribute()
    {
        return $this->resume ? asset('storage/' . $this->resume) : null;
    }

    /**
     * Get the resume file name
     */
    public function getResumeFileNameAttribute()
    {
        if ($this->resume) {
            return pathinfo($this->resume, PATHINFO_BASENAME);
        }
        return null;
    }

    /**
     * Check if resume exists
     */
    public function getHasResumeAttribute()
    {
        return $this->resume && Storage::exists('public/' . $this->resume);
    }

    /**
     * Get resume path for storage
     */
    public function getResumePathAttribute()
    {
        return $this->resume ? storage_path('app/public/' . $this->resume) : null;
    }

    public function sector()
    {
        return $this->belongsTo(Sector::class, 'sector', 'name');
    }

    public function industry()
    {
        return $this->belongsTo(Industry::class, 'industry', 'name');
    }

    public function placementType()
    {
        return $this->belongsTo(PlacementType::class, 'placement_type', 'name');
    }
    
    // Helper method to get full name
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public static function generateSimpleEmpCode()
    {
        $prefix = 'EMP';
        
        $lastCandidate = self::orderBy('id', 'desc')->first();
        
        if ($lastCandidate && $lastCandidate->emp_code) {
            // Extract the number from last emp_code
            $lastNumber = (int) substr($lastCandidate->emp_code, 3);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1001; // Start from 1001
        }
        
        return "{$prefix}{$nextNumber}";
    }
    
    /**
     * Alternative: Auto-generate on model creation
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->emp_code)) {
                $model->emp_code = self::generateSimpleEmpCode();
            }
        });

        // When a candidate is created, create the initial status history
        static::created(function ($model) {
            if (!empty($model->status)) {
                StatusHistory::create([
                    'candidate_id' => $model->id,
                    'status' => $model->status,
                    'remarks' => 'Candidate created',
                    'updated_by' => auth()->id() ?? 1,
                ]);
            }
        });
    }

    /**
     * Update candidate status with history tracking
     */
    public function updateStatus(array $data)
    {
        $this->current_status = $data['status'];
        
        if ($data['status'] === 'Inprocess') {
            $this->company_id = $data['company_id'];
            $this->interview_date = $data['interview_date'];
        } elseif ($data['status'] === 'Selected') {
            $this->selected_at = now();
        }
        
        $this->save();

        // Create status history record
        StatusHistory::create([
            'candidate_id' => $this->id,
            'status' => $data['status'],
            'company_id' => $data['company_id'] ?? null,
            'interview_date' => $data['interview_date'] ?? null,
            'remarks' => $data['remarks'] ?? null,
            'updated_by' => auth()->id(),
        ]);
    }

    /**
     * Scope for active candidates
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'Active');
    }

    /**
     * Scope for inprocess candidates
     */
    public function scopeInprocess($query)
    {
        return $query->where('status', 'Inprocess');
    }

    /**
     * Scope for selected candidates
     */
    public function scopeSelected($query)
    {
        return $query->where('status', 'Selected');
    }
    
    public function files()
    {
        return $this->hasMany(CandidateFile::class);
    }
}