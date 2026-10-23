{{-- resources/views/recruiter/daily-all.blade.php --}}
@extends('layouts.app')

@section('title', 'Recruiter Performance Dashboard')



@section('content')

<style>
    /* Modern Card Design */
    .metric-card {
        border-radius: 15px;
        border: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    }
    .metric-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }
    
    /* Recruiter Profile Card */
    .recruiter-profile-card {
        border-radius: 20px;
        border: none;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.4s ease;
        overflow: hidden;
        position: relative;
    }
    .recruiter-profile-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
    }
    .recruiter-profile-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
    }
    
    /* Progress Circles */
    .progress-circle {
        width: 100px;
        height: 100px;
        position: relative;
        margin: 0 auto;
    }
    .progress-circle svg {
        width: 100px;
        height: 100px;
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
        stroke-dasharray: 283;
        stroke-dashoffset: 283;
        transition: stroke-dashoffset 1s ease;
    }
    .progress-circle-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-weight: bold;
        font-size: 1.2rem;
    }
    
    /* Day Grid */
    .day-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 8px;
        margin: 15px 0;
    }
    .day-cell {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
    }
    .day-cell.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        transform: scale(1.1);
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }
    .day-cell.has-data {
        background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
    }
    .day-cell.has-data:hover {
        transform: scale(1.1);
    }
    .day-cell.no-data {
        background-color: #f8f9fa;
        color: #adb5bd;
        border: 2px solid #e9ecef;
    }
    .day-cell.today {
        border: 2px solid #ff6b6b;
        color: #ff6b6b;
        font-weight: bold;
    }
    
    /* Stats Badges */
    .stat-badge {
        border-radius: 20px;
        padding: 8px 15px;
        font-weight: 500;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    /* Modern Table */
    .modern-table {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }
    .modern-table thead {
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    .modern-table th {
        border: none;
        font-weight: 500;
        padding: 15px;
    }
    .modern-table tbody tr {
        transition: all 0.3s ease;
    }
    .modern-table tbody tr:hover {
        background-color: #f8f9ff;
        transform: scale(1.002);
    }
    .modern-table td {
        padding: 15px;
        border-bottom: 1px solid #e9ecef;
        vertical-align: middle;
    }
    
    /* Action Buttons */
    .action-btn {
        border-radius: 25px;
        padding: 8px 20px;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.15);
    }
    
    /* Header */
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 30px;
        border-radius: 20px;
        margin-bottom: 30px;
        color: white;
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    }
    
    /* Empty State */
    .empty-state {
        padding: 60px 30px;
        text-align: center;
        background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
        border-radius: 20px;
        border: 2px dashed #dee2e6;
    }
    .empty-state-icon {
        font-size: 5rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 20px;
    }
    
    /* Performance Indicator */
    .performance-indicator {
        display: inline-flex;
        align-items: center;
        padding: 5px 15px;
        border-radius: 20px;
        font-weight: 500;
        font-size: 0.85rem;
    }
    
    /* Metric Icon */
    .metric-icon {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
</style>
<div class="container-fluid px-4 py-3">
    <!-- Modern Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="display-5 fw-bold mb-3">
                    <i class="bi bi-graph-up-arrow me-3"></i>
                    Recruiter Performance Dashboard
                </h1>
                <p class="lead mb-0 opacity-75">
                    Monitor and compare all recruiter activities and progress in real-time
                </p>
            </div>
            <div class="col-md-4 text-end">
                <div class="dropdown">
                    <button class="btn btn-light btn-lg rounded-pill px-4 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-calendar-month me-2"></i>
                        {{ $selectedMonth }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-end shadow">
                        <h6 class="dropdown-header">Select Month</h6>
                        @foreach($availableMonths as $month)
                        <a class="dropdown-item" href="?month={{ urlencode($month) }}">
                            <i class="bi bi-calendar-check me-2"></i>
                            {{ $month }}
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(empty($allRecruiters))
    <!-- Empty State -->
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="empty-state">
                <i class="bi bi-people empty-state-icon"></i>
                <h3 class="mb-3">No Recruiters Found</h3>
                <p class="text-muted mb-4">There are no recruiters with data in the system yet.</p>
                <a href="{{ route('recruiter.daily') }}" class="btn btn-primary btn-lg px-4">
                    <i class="bi bi-plus-circle me-2"></i> Start Adding Progress
                </a>
            </div>
        </div>
    </div>
    @else
    <!-- Quick Stats -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="metric-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="metric-icon bg-primary bg-gradient text-white me-3">
                            <i class="bi bi-people"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Total Recruiters</h6>
                            <h3 class="mb-0 text-primary">{{ count($allRecruiters) }}</h3>
                            <small class="text-muted">Currently active</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="metric-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="metric-icon bg-success bg-gradient text-white me-3">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Active This Month</h6>
                            <h3 class="mb-0 text-success">{{ $activeCount ?? 0 }}</h3>
                            <small class="text-muted">With recorded progress</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="metric-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="metric-icon bg-danger bg-gradient text-white me-3">
                            <i class="bi bi-speedometer2"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Average Progress</h6>
                            <h3 class="mb-0 text-danger">{{ $avgProgress ?? 0 }}%</h3>
                            <small class="text-muted">Overall team average</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="metric-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="metric-icon bg-info bg-gradient text-white me-3">
                            <i class="bi bi-file-text"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Total CVs Submitted</h6>
                            <h3 class="mb-0 text-info">{{ $totalCVs ?? 0 }}</h3>
                            <small class="text-muted">This month</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Toolbar -->
    <div class="card mb-4 border-0 shadow">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-bar-chart-line me-2"></i>
                        Performance Overview
                    </h5>
                </div>
                <div class="d-flex gap-2">
                    <!--<button class="btn btn-outline-primary rounded-pill px-4" onclick="exportAllData()">
                        <i class="bi bi-file-earmark-excel me-2"></i> Export Report
                    </button>
                    <button class="btn btn-primary rounded-pill px-4" onclick="openCompareModal()">
                        <i class="bi bi-graph-up me-2"></i> Compare Recruiters
                    </button>-->
                </div>
            </div>
        </div>
    </div>

    <!-- Recruiters Grid -->
    <div class="row g-4 mb-5">
        @foreach($allRecruiters as $recruiter)
        @php
            $summary = $recruiterSummaries[$recruiter] ?? null;
            $rating = $summary['performance_rating'] ?? ['label' => 'No Data', 'class' => 'bg-secondary'];
            $progress = $summary['overall_progress'] ?? 0;
            $progressOffset = 283 - (283 * $progress / 100);
            
            // Determine progress color
            if ($progress >= 75) {
                $progressColor = '#4CAF50';
                $ratingClass = 'bg-success';
            } elseif ($progress >= 50) {
                $progressColor = '#FFC107';
                $ratingClass = 'bg-warning';
            } elseif ($progress >= 25) {
                $progressColor = '#FF9800';
                $ratingClass = 'bg-orange';
            } else {
                $progressColor = '#F44336';
                $ratingClass = 'bg-danger';
            }
        @endphp
        
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="recruiter-profile-card">
                <div class="card-body p-4">
                    <!-- Header with Profile -->
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-gradient rounded-circle p-3 me-3">
                                <i class="bi bi-person text-white fs-4"></i>
                            </div>
                            <div>
                                <h4 class="mb-1 fw-bold">{{ $recruiter }}</h4>
                                <span class="badge {{ $ratingClass }} performance-indicator">
                                    {{ $rating['label'] }}
                                </span>
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="progress-circle">
                                <svg>
                                    <circle class="progress-circle-bg" cx="50" cy="50" r="45"></circle>
                                    <circle class="progress-circle-fill" cx="50" cy="50" r="45" 
                                            stroke="{{ $progressColor }}"
                                            stroke-dashoffset="{{ $progressOffset }}"></circle>
                                </svg>
                                <div class="progress-circle-text">
                                    <strong>{{ $progress }}%</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Key Metrics -->
                    <div class="row g-3 mb-4">
                        <div class="col-4 text-center">
                            <div class="p-3 border rounded-3">
                                <div class="text-primary fw-bold fs-4">
                                    {{ $summary['totals']['cv_submitted'] ?? 0 }}
                                </div>
                                <small class="text-muted">CVs Submitted</small>
                            </div>
                        </div>
                        <div class="col-4 text-center">
                            <div class="p-3 border rounded-3">
                                <div class="text-success fw-bold fs-4">
                                    {{ $summary['totals']['selected'] ?? 0 }}
                                </div>
                                <small class="text-muted">Selected</small>
                            </div>
                        </div>
                        <div class="col-4 text-center">
                            <div class="p-3 border rounded-3">
                                <div class="text-warning fw-bold fs-4">
                                    {{ $summary['totals']['payment_received'] ?? 0 }}
                                </div>
                                <small class="text-muted">Payments</small>
                            </div>
                        </div>
                    </div>

                    <!-- Daily Progress Grid -->
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">
                            <i class="bi bi-calendar3 me-2"></i>
                            Daily Activity - {{ $selectedMonth }}
                        </h6>
                        <div class="day-grid">
                            @for($day = 1; $day <= 31; $day++)
                                @php
                                    $today = date('j');
                                    $currentMonthNum = date('n');
                                    $selectedMonthNum = date('n', strtotime($selectedMonth));
                                    $isToday = ($day == $today && $selectedMonthNum == $currentMonthNum);
                                    
                                    $hasData = isset($allData[$recruiter]) && 
                                               $allData[$recruiter]->where('entry_day', $day)->first() &&
                                               (($allData[$recruiter]->where('entry_day', $day)->first()->cv_submitted ?? 0) > 0 ||
                                                ($allData[$recruiter]->where('entry_day', $day)->first()->busi_profile ?? 0) > 0);
                                    
                                    $dayClass = $isToday ? 'today' : ($hasData ? 'has-data' : 'no-data');
                                @endphp
                                <div class="day-cell {{ $dayClass }}" 
                                     title="Day {{ $day }}{{ $isToday ? ' (Today)' : '' }}"
                                     onclick="viewRecruiterDay('{{ $recruiter }}', {{ $day }})">
                                    {{ $day }}
                                    @if($hasData)
                                    <div class="position-absolute" style="top: -2px; right: -2px;">
                                        <div class="bg-success rounded-circle" style="width: 8px; height: 8px;"></div>
                                    </div>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <div>
                            <span class="text-muted me-3">
                                <i class="bi bi-clock-history me-1"></i>
                                Last updated: {{ now()->format('M d, Y') }}
                            </span>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('recruiter.daily') }}?hr_staff={{ urlencode($recruiter) }}&month={{ urlencode($selectedMonth) }}" 
                               class="btn btn-outline-primary action-btn">
                                <i class="bi bi-eye me-2"></i> View Details
                            </a>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Performance Comparison Table -->
    <div class="card modern-table mb-4">
        <div class="card-header bg-transparent border-0 p-4 d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-0 fw-bold">
                    <i class="bi bi-table me-2"></i>
                    Performance Comparison
                </h4>
                <p class="text-muted mb-0">Detailed metrics for {{ $selectedMonth }}</p>
            </div>
            @if($user->role !== 'admin')
            <a href="{{route('recruiter.daily')}}"><button class="btn btn-primary">Add New</button> </a>
            @endif
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Recruiter</th>
                            <th class="text-center">CVs</th>
                            <th class="text-center">Interviews</th>
                            <th class="text-center">Selected</th>
                            <th class="text-center">Conversion</th>
                            <th class="text-center">Payments</th>
                            <th class="text-center">Progress</th>
                            <th class="text-center pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allRecruiters as $recruiter)
                        @php
                            $summary = $recruiterSummaries[$recruiter] ?? null;
                            $rating = $summary['performance_rating'] ?? ['label' => 'No Data', 'class' => 'bg-secondary'];
                            $cvs = $summary['totals']['cv_submitted'] ?? 0;
                            $selected = $summary['totals']['selected'] ?? 0;
                            $conversion = $cvs > 0 ? round(($selected / $cvs) * 100, 1) : 0;
                        @endphp
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="bi bi-person text-primary"></i>
                                    </div>
                                    <div>
                                        <strong>{{ $recruiter }}</strong>
                                        <div>
                                            <span class="badge {{ $rating['class'] }}">{{ $rating['label'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="fw-bold text-primary">{{ $cvs }}</span>
                            </td>
                            <td class="text-center">
                                <span class="fw-bold text-info">{{ $summary['totals']['interview_attended'] ?? 0 }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success rounded-pill px-3 py-2 fw-bold">
                                    {{ $selected }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="progress" style="height: 25px; width: 100px; margin: 0 auto;">
                                    <div class="progress-bar bg-info" style="width: {{ min($conversion, 100) }}%">
                                        <span class="fw-bold text-white">{{ $conversion }}%</span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-warning rounded-pill px-3 py-2 fw-bold">
                                    {{ $summary['totals']['payment_received'] ?? 0 }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="me-3 fw-bold {{ $summary['overall_progress'] >= 75 ? 'text-success' : ($summary['overall_progress'] >= 50 ? 'text-warning' : 'text-danger') }}">
                                        {{ $summary['overall_progress'] ?? 0 }}%
                                    </div>
                                    <div class="progress" style="height: 10px; width: 100px;">
                                        <div class="progress-bar {{ $summary['overall_progress'] >= 75 ? 'bg-success' : ($summary['overall_progress'] >= 50 ? 'bg-warning' : 'bg-danger') }}" 
                                             style="width: {{ $summary['overall_progress'] ?? 0 }}%">
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="pe-4">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('recruiter.daily.view') }}?hr_staff={{ urlencode($recruiter) }}&month={{ urlencode($selectedMonth) }}" 
                                    class="btn btn-outline-primary action-btn">
                                        <i class="icon-eye menu-icon"></i>
                                    </a>
                                    <a href="{{ route('recruiter.monthly') }}?hr_staff={{ urlencode($recruiter) }}&month={{ urlencode($selectedMonth) }}" 
                                       class="btn btn-outline-info btn-sm px-3" title="Monthly View">
                                        <i class="icon-calendar menu-icon"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Compare Modal -->
@if(!empty($allRecruiters))
<div class="modal fade" id="compareModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-graph-up me-2"></i>
                    Compare Recruiters
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted mb-4">Select 2 or more recruiters to compare their performance:</p>
                <div class="row" id="compareRecruitersList">
                    @foreach($allRecruiters as $recruiter)
                    <div class="col-md-6 mb-3">
                        <div class="form-check">
                            <input class="form-check-input compare-checkbox" type="checkbox" 
                                   value="{{ $recruiter }}" id="compare_{{ $loop->index }}">
                            <label class="form-check-label d-flex align-items-center" for="compare_{{ $loop->index }}">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                    <i class="bi bi-person text-primary"></i>
                                </div>
                                <div>
                                    <strong>{{ $recruiter }}</strong>
                                    @if(isset($recruiterSummaries[$recruiter]))
                                    <div class="text-muted small">
                                        Progress: {{ $recruiterSummaries[$recruiter]['overall_progress'] }}%
                                    </div>
                                    @endif
                                </div>
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary rounded-pill px-4" onclick="startComparison()">
                    <i class="bi bi-graph-up me-2"></i> Compare Selected
                </button>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
    function exportAllData() {
        window.location.href = `/admin/export/all?month={{ urlencode($selectedMonth) }}`;
    }
    
    function openCompareModal() {
        const modal = new bootstrap.Modal(document.getElementById('compareModal'));
        modal.show();
    }
    
    function startComparison() {
        const selected = [];
        document.querySelectorAll('.compare-checkbox:checked').forEach(checkbox => {
            selected.push(checkbox.value);
        });
        
        if(selected.length < 2) {
            Swal.fire({
                icon: 'warning',
                title: 'Select Recruiters',
                text: 'Please select at least 2 recruiters to compare',
                confirmButtonColor: '#667eea'
            });
            return;
        }
        
        window.open(`/admin/recruiters/compare?recruiters=${selected.join(',')}&month={{ urlencode($selectedMonth) }}`, '_blank');
    }
    
    function viewRecruiterDay(recruiter, day) {
        window.location.href = `/recruiter/daily?hr_staff=${encodeURIComponent(recruiter)}&month={{ urlencode($selectedMonth) }}#day${day}`;
    }
    
    function quickEdit(recruiter) {
        Swal.fire({
            title: 'Edit Recruiter Data',
            text: `Do you want to edit data for ${recruiter}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#667eea',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Edit',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `/recruiter/daily?hr_staff=${encodeURIComponent(recruiter)}&month={{ urlencode($selectedMonth) }}&edit=true`;
            }
        });
    }
    
    function editRecruiterData(recruiter) {
        quickEdit(recruiter);
    }
    
    // Animate progress circles on scroll
    document.addEventListener('DOMContentLoaded', function() {
        // Animate progress circles
        const circles = document.querySelectorAll('.progress-circle-fill');
        circles.forEach(circle => {
            const progress = parseInt(circle.parentElement.parentElement.querySelector('.progress-circle-text strong').textContent);
            const offset = 283 - (283 * progress / 100);
            circle.style.strokeDashoffset = offset;
        });
        
        // Add search functionality
        const searchInput = document.createElement('input');
        searchInput.type = 'text';
        searchInput.className = 'form-control form-control-lg rounded-pill mb-4 shadow-sm';
        searchInput.placeholder = '🔍 Search recruiters by name...';
        searchInput.style.padding = '15px 25px';
        searchInput.id = 'recruiterSearch';
        
        const recruitersGrid = document.querySelector('#recruitersGrid');
        if (recruitersGrid) {
            recruitersGrid.parentNode.insertBefore(searchInput, recruitersGrid);
            
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                document.querySelectorAll('.recruiter-profile-card').forEach(card => {
                    const recruiterName = card.querySelector('h4').textContent.toLowerCase();
                    if(searchTerm === '' || recruiterName.includes(searchTerm)) {
                        card.parentElement.style.display = 'block';
                        card.style.opacity = '1';
                    } else {
                        card.parentElement.style.display = 'none';
                    }
                });
                
                // Update table rows
                document.querySelectorAll('.modern-table tbody tr').forEach(row => {
                    const recruiterName = row.querySelector('strong').textContent.toLowerCase();
                    if(searchTerm === '' || recruiterName.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
        
        // Add today highlight
        const today = new Date().getDate();
        document.querySelectorAll('.day-cell.today').forEach(cell => {
            cell.style.animation = 'pulse 2s infinite';
        });
    });
</script>

<style>
    @keyframes pulse {
        0% { transform: scale(1); box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3); }
        50% { transform: scale(1.1); box-shadow: 0 6px 20px rgba(255, 107, 107, 0.5); }
        100% { transform: scale(1); box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3); }
    }
    
    .bg-orange {
        background-color: #FF9800 !important;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    }
    
    .rounded-start-pill {
        border-top-left-radius: 50rem !important;
        border-bottom-left-radius: 50rem !important;
    }
    
    .rounded-end-pill {
        border-top-right-radius: 50rem !important;
        border-bottom-right-radius: 50rem !important;
    }
</style>
@endpush