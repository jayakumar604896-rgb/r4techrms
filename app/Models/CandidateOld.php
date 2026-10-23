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
        'field1',
        'field2',
        'field3',
        'field4',
    ];

    public $timestamps = true;

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
}