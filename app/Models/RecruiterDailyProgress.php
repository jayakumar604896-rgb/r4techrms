<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecruiterDailyProgress extends Model
{
    use HasFactory;

    protected $table = 'recruiter_daily_progress';
    
    protected $fillable = [
        'month',
        'hr_staff',
        'entry_day',
        'client_name',
        'busi_profile',
        'tele_marketing',
        'client_empanelment',
        'cv_submitted',
        'interview_attended',
        'rejected',
        'selected',
        'job_reported',
        'invoiced',
        'payment_received',
        'remarks',
        'entry_date',
        'interview',
        'selection',
        'rejection'
    ];

    protected $casts = [
        'busi_profile' => 'integer',
        'tele_marketing' => 'integer',
        'client_empanelment' => 'integer',
        'cv_submitted' => 'integer',
        'interview_attended' => 'integer',
        'rejected' => 'integer',
        'selected' => 'integer',
        'job_reported' => 'integer',
        'invoiced' => 'integer',
        'payment_received' => 'integer',
        'entry_date' => 'date'
    ];

    /**
     * Get the monthly summary for this entry
     */
    public function monthlySummary()
    {
        return $this->belongsTo(RecruiterMonthlySummary::class, 'month', 'month')
                    ->where('hr_staff', $this->hr_staff);
    }

    /**
     * Scope for specific month and staff
     */
    public function scopeForMonth($query, $month, $staff = 'Ms. Padmavathi')
    {
        return $query->where('month', $month)
                     ->where('hr_staff', $staff);
    }

    /**
     * Calculate percentage selection for this day
     */
    public function getSelectionPercentageAttribute()
    {
        if ($this->interview_attended > 0) {
            return round(($this->selected / $this->interview_attended) * 100, 1);
        }
        return 0;
    }

    /**
     * Calculate percentage rejection for this day
     */
    public function getRejectionPercentageAttribute()
    {
        if ($this->interview_attended > 0) {
            return round(($this->rejected / $this->interview_attended) * 100, 1);
        }
        return 0;
    }

    /**
     * Get formatted date
     */
    public function getFormattedDateAttribute()
    {
        return $this->entry_date->format('d-m-Y');
    }

    public static function calculateOverallProgress($month, $hrStaff)
    {
        // Get all records for the month and staff
        $records = self::where('month', $month)
            ->where('hr_staff', $hrStaff)
            ->get();

        // Calculate totals
        $totals = [
            'busi_profile' => $records->sum('busi_profile'),
            'tele_marketing' => $records->sum('tele_marketing'),
            'client_empanelment' => $records->sum('client_empanelment'),
            'cv_submitted' => $records->sum('cv_submitted'),
            'interview' => $records->sum('interview'),
            'interview_attended' => $records->sum('interview_attended'),
            'rejected' => $records->sum('rejected'),
            'selected' => $records->sum('selected'),
            'job_reported' => $records->sum('job_reported'),
            'invoiced' => $records->sum('invoiced'),
            'payment_received' => $records->sum('payment_received'),
        ];

        // Define targets (you can adjust these as needed)
        $targets = [
            'busi_profile' => 520,
            'tele_marketing' => 260,
            'cv_submitted' => 182,
            'selected' => 45.5,
            'job_reported' => 6,
            'payment_received' => 3
        ];

        // Calculate progress for each category
        $progress = [];
        foreach ($targets as $key => $target) {
            if ($target > 0) {
                $achieved = $totals[$key] ?? 0;
                $progress[$key] = min(100, round(($achieved / $target) * 100, 1));
            } else {
                $progress[$key] = 0;
            }
        }

        // Calculate overall progress (weighted average)
        $weights = [
            'busi_profile' => 0.02,
            'tele_marketing' => 0.04,
            'cv_submitted' => 0.15,
            'selected' => 0.25,
            'job_reported' => 0.15,
            'payment_received' => 0.06
        ];

        $overall = 0;
        foreach ($weights as $key => $weight) {
            if (isset($progress[$key])) {
                $overall += ($progress[$key] / 100) * $weight * 100;
            }
        }

        // Calculate selection and rejection percentages
        $interviewAttended = $totals['interview_attended'];
        $selected = $totals['selected'];
        $rejected = $totals['rejected'];
        
        $selectionPercent = $interviewAttended > 0 
            ? round(($selected / $interviewAttended) * 100, 1)
            : 0;
            
        $rejectionPercent = $interviewAttended > 0 
            ? round(($rejected / $interviewAttended) * 100, 1)
            : 0;

        return [
            'overall_percentage' => round($overall, 1),
            'selection_percentage' => $selectionPercent,
            'rejection_percentage' => $rejectionPercent,
            'totals' => $totals,
            'progress' => $progress
        ];
    }
}