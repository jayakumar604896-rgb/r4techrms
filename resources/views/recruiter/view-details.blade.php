{{-- resources/views/recruiter/view-details.blade.php --}}
@extends('layouts.app')

@section('title', 'Recruiter Performance Details')

@push('styles')

@endpush

@section('content')
<style>
    .performance-card {
        border-radius: 20px;
        border: none;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
        transition: all 0.3s ease;
    }
    .performance-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
    }
    .stat-card {
        border-radius: 15px;
        padding: 20px;
        text-align: center;
        background: white;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    }
    .stat-value {
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 5px;
    }
    .stat-label {
        color: #6c757d;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .daily-table {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }
    .daily-table thead {
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    .daily-table th {
        border: none;
        padding: 15px;
        font-weight: 500;
    }
    .daily-table tbody tr {
        transition: all 0.2s ease;
    }
    .daily-table tbody tr:hover {
        background-color: #f8f9ff;
    }
    .day-highlight {
        background-color: #e3f2fd;
        border-left: 4px solid #2196f3;
    }
    .metric-badge {
        padding: 5px 15px;
        border-radius: 20px;
        font-weight: 500;
    }
    .trend-up {
        color: #4CAF50;
    }
    .trend-down {
        color: #F44336;
    }
    .trend-neutral {
        color: #FFC107;
    }
</style>
<div class="container-fluid px-4 py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">
                <i class="bi bi-person-badge text-primary me-2"></i>
                {{ $hrStaff }} - Performance Details
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        @if($user->role === 'admin')
                        <a href="{{ route('admin.recruiters.all-daily') }}">All Recruiters</a>
                        @else
                        <a href="{{ route('recruiter.daily') }}">My Dashboard</a>
                        @endif
                    </li>
                    <li class="breadcrumb-item active">Performance Details</li>
                </ol>
            </nav>
        </div>
        
        <div class="d-flex gap-3 align-items-center">
            <!-- Month Selector -->
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-calendar-month me-2"></i>
                    {{ $selectedMonth }}
                </button>
                <div class="dropdown-menu">
                    @foreach($availableMonths as $month)
                    <a class="dropdown-item" href="?hr_staff={{ urlencode($hrStaff) }}&month={{ urlencode($month) }}">
                        {{ $month }}
                    </a>
                    @endforeach
                </div>
            </div>
            
            <!-- Action Buttons -->
            <!-- <a href="{{ route('recruiter.daily') }}?hr_staff={{ urlencode($hrStaff) }}&month={{ urlencode($selectedMonth) }}&edit=true" 
               class="btn btn-primary">
                <i class="bi bi-pencil-square me-2"></i> Edit Data
            </a>
             -->
            <a href="{{ route('admin.recruiters.all-daily') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i> Back to All
            </a>
        </div>
    </div>

    <!-- Performance Summary -->
    <div class="row mb-4">
        <!-- Main Performance Card -->
        <div class="col-xl-8 mb-4">
            <div class="card performance-card">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-4 text-center">
                            <div class="mb-3">
                                <div class="position-relative d-inline-block">
                                    <div class="progress-circle">
                                        <svg width="120" height="120">
                                            <circle class="progress-circle-bg" cx="60" cy="60" r="54"></circle>
                                            <circle class="progress-circle-fill" cx="60" cy="60" r="54" 
                                                    stroke="#4CAF50"
                                                    stroke-dashoffset="{{ 340 - (340 * ($monthlySummary->overall_total ?? 0) / 100) }}"></circle>
                                        </svg>
                                        <div class="position-absolute top-50 start-50 translate-middle">
                                            <h2 class="mb-0 fw-bold">{{ $monthlySummary->overall_total ?? 0 }}%</h2>
                                            <small class="text-muted">Overall</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <span class="badge {{ $stats['performance_class'] ?? 'bg-secondary' }} metric-badge">
                                    {{ $stats['performance_rating'] }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="col-md-8">
                            <h4 class="mb-3">Performance Summary</h4>
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="stat-card">
                                        <div class="stat-value text-primary">{{ $stats['total_cvs'] }}</div>
                                        <div class="stat-label">CVs Submitted</div>
                                        <small class="text-muted">{{ $stats['avg_cvs_per_day'] }} per day</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-card">
                                        <div class="stat-value text-success">{{ $stats['total_selected'] }}</div>
                                        <div class="stat-label">Selected Candidates</div>
                                        <small class="text-muted">{{ $stats['conversion_rate'] }}% conversion</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-card">
                                        <div class="stat-value text-info">{{ $stats['total_interviews'] }}</div>
                                        <div class="stat-label">Interviews Attended</div>
                                        <small class="text-muted">{{ $stats['selection_rate'] }}% selection rate</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-card">
                                        <div class="stat-value text-warning">{{ $stats['total_payments'] }}</div>
                                        <div class="stat-label">Payments Received</div>
                                        <small class="text-muted">{{ $stats['days_worked'] }} days worked</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Trend Analysis -->
        <div class="col-xl-4 mb-4">
            <div class="card performance-card h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="bi bi-graph-up me-2"></i>
                        Trend Analysis
                    </h5>
                    
                    @if(count($previousMonthsData) > 0)
                    <div class="trend-list">
                        @foreach($previousMonthsData as $month => $data)
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                            <div>
                                <small class="text-muted d-block">{{ $month }}</small>
                                <div class="d-flex align-items-center">
                                    <div class="progress" style="width: 100px; height: 8px; margin-right: 10px;">
                                        <div class="progress-bar bg-{{ $data['overall_progress'] >= 75 ? 'success' : ($data['overall_progress'] >= 50 ? 'warning' : 'danger') }}" 
                                             style="width: {{ $data['overall_progress'] }}%"></div>
                                    </div>
                                    <span class="fw-bold">{{ $data['overall_progress'] }}%</span>
                                </div>
                            </div>
                            <div class="text-end">
                                <small class="d-block">{{ $data['totals']['cv_submitted'] ?? 0 }} CVs</small>
                                <small class="d-block">{{ $data['totals']['selected'] ?? 0 }} Selected</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="bi bi-bar-chart text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">No previous data available for trend analysis</p>
                    </div>
                    @endif
                    
                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Comparison with previous {{ count($previousMonthsData) }} months
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Daily Activity Table -->
    <div class="card performance-card mb-4">
        <div class="card-header bg-transparent border-0">
            <h5 class="mb-0">
                <i class="bi bi-calendar-date me-2"></i>
                Daily Activity Details - {{ $selectedMonth }}
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table daily-table mb-0">
                    <thead>
                        <tr>
                            <th width="60">Day</th>
                            <th>Client Name</th>
                            <th class="text-center">Business Profile</th>
                            <th class="text-center">CV Submitted</th>
                            <th class="text-center">Interview</th>
                            <th class="text-center">Selected</th>
                            <th class="text-center">Rejected</th>
                            <th class="text-center">Job Reported</th>
                            <th class="text-center">Payment</th>
                            <th class="text-center">Selection %</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dailyData as $entry)
                        @php
                            $isToday = $entry->entry_day == date('j') && $selectedMonth == date('F Y');
                            $selectionPercent = $entry->interview_attended > 0 
                                ? round(($entry->selected / $entry->interview_attended) * 100, 1)
                                : 0;
                            $rejectionPercent = $entry->interview_attended > 0
                                ? round(($entry->rejected / $entry->interview_attended) * 100, 1)
                                : 0;
                        @endphp
                        <tr class="{{ $isToday ? 'day-highlight' : '' }}">
                            <td class="text-center fw-bold">
                                {{ $entry->entry_day }}
                                @if($isToday)
                                <span class="badge bg-danger ms-1">Today</span>
                                @endif
                            </td>
                            <td>{{ $entry->client_name ?? '-' }}</td>
                            <td class="text-center">{{ $entry->busi_profile }}</td>
                            <td class="text-center">
                                <span class="badge bg-primary rounded-pill px-3">{{ $entry->cv_submitted }}</span>
                            </td>
                            <td class="text-center">{{ $entry->interview_attended }}</td>
                            <td class="text-center">
                                <span class="badge bg-success rounded-pill px-3">{{ $entry->selected }}</span>
                            </td>
                            <td class="text-center">{{ $entry->rejected }}</td>
                            <td class="text-center">{{ $entry->job_reported }}</td>
                            <td class="text-center">
                                @if($entry->payment_received > 0)
                                <span class="badge bg-warning rounded-pill px-3">{{ $entry->payment_received }}</span>
                                @else
                                -
                                @endif
                            </td>
                            <td class="text-center">
                                @if($entry->interview_attended > 0)
                                <div class="progress" style="height: 20px; width: 80px; margin: 0 auto;">
                                    <div class="progress-bar bg-success" style="width: {{ $selectionPercent }}%">
                                        <span class="fw-bold text-white">{{ $selectionPercent }}%</span>
                                    </div>
                                </div>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ $entry->remarks ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center py-4">
                                <i class="bi bi-calendar-x text-muted" style="font-size: 2rem;"></i>
                                <p class="text-muted mt-2">No daily entries found for this month</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($dailyData->isNotEmpty())
                    <tfoot>
                        <tr class="table-secondary">
                            <td colspan="2" class="text-end fw-bold">Monthly Total:</td>
                            <td class="text-center fw-bold">{{ $dailyData->sum('busi_profile') }}</td>
                            <td class="text-center fw-bold">{{ $dailyData->sum('cv_submitted') }}</td>
                            <td class="text-center fw-bold">{{ $dailyData->sum('interview_attended') }}</td>
                            <td class="text-center fw-bold">{{ $dailyData->sum('selected') }}</td>
                            <td class="text-center fw-bold">{{ $dailyData->sum('rejected') }}</td>
                            <td class="text-center fw-bold">{{ $dailyData->sum('job_reported') }}</td>
                            <td class="text-center fw-bold">{{ $dailyData->sum('payment_received') }}</td>
                            <td class="text-center fw-bold">
                                @php
                                    $totalInterview = $dailyData->sum('interview_attended');
                                    $totalSelected = $dailyData->sum('selected');
                                    $overallSelection = $totalInterview > 0 ? round(($totalSelected / $totalInterview) * 100, 1) : 0;
                                @endphp
                                {{ $overallSelection }}%
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

    <!-- Export Options -->
    <div class="card performance-card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1">Export Options</h5>
                    <p class="text-muted mb-0">Download detailed reports for analysis</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('recruiter.export.daily', ['month' => $selectedMonth, 'hr_staff' => $hrStaff]) }}" 
                       class="btn btn-outline-success">
                        <i class="bi bi-file-excel me-2"></i> Export Excel
                    </a>
                    <button class="btn btn-outline-primary" onclick="window.print()">
                        <i class="bi bi-printer me-2"></i> Print Report
                    </button>
                    @if($user->role === 'admin')
                    <button class="btn btn-outline-info" onclick="generatePerformanceReport('{{ $hrStaff }}')">
                        <i class="bi bi-file-text me-2"></i> Generate PDF
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Animate progress circles
    document.addEventListener('DOMContentLoaded', function() {
        const circle = document.querySelector('.progress-circle-fill');
        if (circle) {
            const progress = {{ $monthlySummary->overall_total ?? 0 }};
            const offset = 340 - (340 * progress / 100);
            circle.style.strokeDashoffset = offset;
        }
        
        // Add today highlight animation
        const todayCells = document.querySelectorAll('.day-highlight');
        todayCells.forEach(cell => {
            cell.style.animation = 'pulse 2s infinite';
        });
    });
    
    function generatePerformanceReport(recruiter) {
        Swal.fire({
            title: 'Generate Performance Report',
            text: `Generate PDF report for ${recruiter}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Generate',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `/admin/report/${encodeURIComponent(recruiter)}?month={{ urlencode($selectedMonth) }}`;
            }
        });
    }
</script>

<style>
    @keyframes pulse {
        0% { background-color: #e3f2fd; }
        50% { background-color: #bbdefb; }
        100% { background-color: #e3f2fd; }
    }
    
    .progress-circle {
        position: relative;
        width: 120px;
        height: 120px;
        margin: 0 auto;
    }
    
    .progress-circle svg {
        width: 120px;
        height: 120px;
        transform: rotate(-90deg);
    }
    
    .progress-circle-bg {
        fill: none;
        stroke: #e9ecef;
        stroke-width: 8;
    }
    
    .progress-circle-fill {
        fill: none;
        stroke-width: 8;
        stroke-linecap: round;
        stroke-dasharray: 340;
        stroke-dashoffset: 340;
        transition: stroke-dashoffset 1.5s ease;
    }
</style>
@endpush