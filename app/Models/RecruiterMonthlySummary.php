<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecruiterMonthlySummary extends Model
{
    use HasFactory;

    protected $table = 'recruiter_monthly_summary';
    
    protected $fillable = [
        'month',
        'hr_staff',
        'totals',
        'unit_progress',
        'overall_progress',
        'overall_total'
    ];

    protected $casts = [
        'totals' => 'array',
        'unit_progress' => 'array',
        'overall_progress' => 'array',
        'overall_total' => 'float'
    ];

    /**
     * Get daily entries for this month
     */
    public function dailyEntries()
    {
        return $this->hasMany(RecruiterDailyProgress::class, 'month', 'month')
                    ->where('hr_staff', $this->hr_staff)
                    ->orderBy('entry_day');
    }

    /**
     * Get target values for this month
     */
    public function getTargetsAttribute()
    {
        return [
            'busi_profile' => 520,          // 26*20
            'tele_marketing' => 260,        // 10*26
            'client_empanelment' => 10.4,   // 26*0.4
            'cv_submitted' => 182,          // 26*7
            'interview_attended' => 45.5,   // =J14*0.25
            'selected' => 45.5,             // =K14*0.25
            'job_reported' => 6,
            'invoiced' => 6,                // =+N14
            'payment_received' => 3         // =+O14*0.5
        ];
    }

    /**
     * Get weight percentages
     */
    public function getWtgPercentagesAttribute()
    {
        return [
            'busi_profile' => 0.02,
            'tele_marketing' => 0.04,
            'client_empanelment' => 0.06,
            'cv_submitted' => 0.15,
            'interview_attended' => 0.15,
            'rejected' => 0,
            'selected' => 0.25,
            'job_reported' => 0.15,
            'invoiced' => 0.12,
            'payment_received' => 0.06
        ];
    }

    /**
     * Calculate and update summary from daily entries
     */
    public function calculateSummary()
    {
        $dailyEntries = $this->dailyEntries;
        
        $totals = [
            'busi_profile' => 0,
            'tele_marketing' => 0,
            'client_empanelment' => 0,
            'cv_submitted' => 0,
            'interview_attended' => 0,
            'rejected' => 0,
            'selected' => 0,
            'job_reported' => 0,
            'invoiced' => 0,
            'payment_received' => 0,
        ];

        // Calculate totals from daily entries
        foreach ($dailyEntries as $entry) {
            $totals['busi_profile'] += $entry->busi_profile;
            $totals['tele_marketing'] += $entry->tele_marketing;
            $totals['client_empanelment'] += $entry->client_empanelment;
            $totals['cv_submitted'] += $entry->cv_submitted;
            $totals['interview_attended'] += $entry->interview_attended;
            $totals['rejected'] += $entry->rejected;
            $totals['selected'] += $entry->selected;
            $totals['job_reported'] += $entry->job_reported;
            $totals['invoiced'] += $entry->invoiced;
            $totals['payment_received'] += $entry->payment_received;
        }

        // Calculate unit progress
        $unitProgress = [];
        foreach ($totals as $key => $achieved) {
            $target = $this->targets[$key] ?? 1; // Avoid division by zero
            $unitProgress[$key] = $target > 0 ? round(($achieved / $target) * 100, 1) : 0;
        }

        // Calculate overall progress
        $overallProgress = [];
        $overallTotal = 0;
        
        foreach ($unitProgress as $key => $unit) {
            $wtg = $this->wtg_percentages[$key] ?? 0;
            $overall = ($unit / 100) * $wtg * 100;
            $overallProgress[$key] = round($overall, 1);
            
            if (!is_nan($overall)) {
                $overallTotal += $overall;
            }
        }

        // Update model
        $this->update([
            'totals' => $totals,
            'unit_progress' => $unitProgress,
            'overall_progress' => $overallProgress,
            'overall_total' => round($overallTotal, 1)
        ]);

        return $this;
    }

    /**
     * Get performance rating based on overall total
     */
    public function getPerformanceRatingAttribute()
    {
        $total = $this->overall_total;
        
        if ($total >= 90) {
            return 'Excellent';
        } elseif ($total >= 75) {
            return 'Good';
        } elseif ($total >= 60) {
            return 'Average';
        } elseif ($total >= 40) {
            return 'Below Average';
        } else {
            return 'Poor';
        }
    }

    /**
     * Get month-year for display
     */
    public function getDisplayMonthAttribute()
    {
        return date('F Y', strtotime($this->month . '-01'));
    }
}