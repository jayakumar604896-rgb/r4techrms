<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'client_name',
        'busi_profile_submission',
        'tele_marketing',
        'client_empanelment',
        'cv_submitted',
        'interview_organized',
        'interview_attended',
        'rejected',
        'selected',
        'job_reported',
        'invoiced',
        'payment_received',
        'selection_percentage',
        'rejection_percentage',
        'remarks'
    ];

    protected $casts = [
        'date' => 'date',
    ];

    // Relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Calculate percentages automatically
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($progress) {
            // Calculate selection percentage
            if ($progress->cv_submitted > 0) {
                $progress->selection_percentage = ($progress->selected / $progress->cv_submitted) * 100;
            } else {
                $progress->selection_percentage = 0;
            }

            // Calculate rejection percentage
            if ($progress->cv_submitted > 0) {
                $progress->rejection_percentage = ($progress->rejected / $progress->cv_submitted) * 100;
            } else {
                $progress->rejection_percentage = 0;
            }
        });
    }

    // Scopes for filtering
    public function scopeFilterByDate($query, $date)
    {
        return $query->whereDate('date', $date);
    }

    public function scopeFilterByMonth($query, $year, $month)
    {
        return $query->whereYear('date', $year)
                    ->whereMonth('date', $month);
    }

    // Get total interviews (organized + attended)
    public function getTotalInterviewsAttribute()
    {
        return $this->interview_organized + $this->interview_attended;
    }

    // Get all metric names for the form
    public static function getMetricColumns()
    {
        return [
            'busi_profile_submission' => 'Business Profile Submission',
            'tele_marketing' => 'Tele Marketing',
            'client_empanelment' => 'Client Empanelment',
            'cv_submitted' => 'CV Submitted',
            'interview_organized' => 'Interview Organized',
            'interview_attended' => 'Interview Attended',
            'rejected' => 'Rejected / Position Closed',
            'selected' => 'Selected / Position Closed',
            'job_reported' => 'Job Reported',
            'invoiced' => 'Invoiced',
            'payment_received' => 'Payment Received',
        ];
    }
}