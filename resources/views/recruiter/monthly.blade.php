{{-- resources/views/recruiters/monthly.blade.php --}}
@extends('layouts.app')

@section('title', 'Monthly Performance Dashboard')
@section('content')

<style>
    /* Modern Design System */
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --success-gradient: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
        --warning-gradient: linear-gradient(135deg, #FFC107 0%, #FF9800 100%);
        --danger-gradient: linear-gradient(135deg, #F44336 0%, #D32F2F 100%);
        --info-gradient: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
    }

    /* Modern Cards */
    .modern-card {
        border: none;
        border-radius: 20px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
        overflow: hidden;
    }
    
    .modern-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }
    
    .modern-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: var(--primary-gradient);
    }
    
    /* KPI Metric Cards */
    .kpi-card {
        border-radius: 20px;
        border: none;
        position: relative;
        overflow: hidden;
        background: white;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
    }
    
    .kpi-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
    }
    
    .kpi-card.success::before { background: var(--success-gradient); }
    .kpi-card.primary::before { background: var(--primary-gradient); }
    .kpi-card.warning::before { background: var(--warning-gradient); }
    .kpi-card.danger::before { background: var(--danger-gradient); }
    
    .kpi-icon {
        width: 70px;
        height: 70px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
    }
    
    .kpi-card:hover .kpi-icon {
        transform: scale(1.1) rotate(5deg);
    }
    
    /* Progress Bars */
    .modern-progress {
        height: 12px;
        border-radius: 10px;
        background-color: #e9ecef;
        overflow: hidden;
        box-shadow: inset 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .modern-progress-bar {
        border-radius: 10px;
        position: relative;
        overflow: hidden;
    }
    
    .modern-progress-bar::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(90deg, 
            rgba(255,255,255,0.1) 0%, 
            rgba(255,255,255,0.3) 50%, 
            rgba(255,255,255,0.1) 100%);
        animation: shimmer 2s infinite;
    }
    
    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
    
    /* Header Design */
    .modern-header {
        background: var(--primary-gradient);
        padding: 30px;
        border-radius: 20px;
        margin-bottom: 30px;
        color: white;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        position: relative;
        overflow: hidden;
    }
    
    .modern-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" preserveAspectRatio="none"><path d="M0,100 L1000,0 L1000,100 Z" fill="rgba(255,255,255,0.05)"/></svg>');
        background-size: cover;
    }
    
    /* Table Design */
    .modern-table {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
    }
    
    .modern-table thead {
        background: var(--primary-gradient);
        color: white;
    }
    
    .modern-table th {
        border: none;
        padding: 18px 20px;
        font-weight: 500;
        letter-spacing: 0.5px;
        position: relative;
    }
    
    .modern-table th::after {
        content: '';
        position: absolute;
        right: 0;
        top: 20%;
        height: 60%;
        width: 1px;
        background: rgba(255,255,255,0.3);
    }
    
    .modern-table th:last-child::after {
        display: none;
    }
    
    .modern-table tbody tr {
        transition: all 0.3s ease;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .modern-table tbody tr:hover {
        background: linear-gradient(90deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
        transform: translateX(5px);
    }
    
    .modern-table td {
        padding: 18px 20px;
        vertical-align: middle;
    }
    
    /* Trend Indicators */
    .trend-indicator {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 500;
        font-size: 0.85rem;
    }
    
    .trend-up {
        background: rgba(76, 175, 80, 0.1);
        color: #2E7D32;
    }
    
    .trend-down {
        background: rgba(244, 67, 54, 0.1);
        color: #C62828;
    }
    
    .trend-neutral {
        background: rgba(255, 152, 0, 0.1);
        color: #EF6C00;
    }
    
    /* Status Badges */
    .status-badge {
        padding: 8px 20px;
        border-radius: 25px;
        font-weight: 500;
        font-size: 0.85rem;
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    }
    
    /* Chart Containers */
    .chart-container {
        background: white;
        border-radius: 20px;
        padding: 25px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        height: 100%;
        position: relative;
        overflow: hidden;
    }
    
    .chart-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: var(--primary-gradient);
    }
    
    /* Weekly Cards */
    .week-card {
        border-radius: 20px;
        border: none;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .week-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
    }
    
    .week-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
    }
    
    .week-card:nth-child(1)::before { background: var(--primary-gradient); }
    .week-card:nth-child(2)::before { background: var(--success-gradient); }
    .week-card:nth-child(3)::before { background: var(--info-gradient); }
    .week-card:nth-child(4)::before { background: var(--warning-gradient); }
    
    /* Action Buttons */
    .action-btn {
        border-radius: 25px;
        padding: 12px 30px;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        position: relative;
        overflow: hidden;
    }
    
    .action-btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }
    
    .action-btn:hover::before {
        width: 300px;
        height: 300px;
    }
    
    .action-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
    
    /* Empty States */
    .empty-state {
        padding: 60px 30px;
        text-align: center;
        background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
        border-radius: 20px;
        border: 2px dashed #dee2e6;
    }
    
    .empty-state-icon {
        font-size: 4rem;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 20px;
    }
    
    /* Animations */
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    
    .float-animation {
        animation: float 3s ease-in-out infinite;
    }
</style>

<div class="container-fluid px-4 py-4">
    <!-- Modern Header -->
    <div class="modern-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="display-5 fw-bold mb-3">
                    <i class="bi bi-bar-chart-line me-3 float-animation"></i>
                    Monthly Performance Dashboard
                </h1>
                <p class="lead mb-0 opacity-90">
                    @if($user->role === 'admin')
                    Analysis of <strong>{{ $hrStaff }}</strong>'s performance for {{ $selectedMonth }}
                    @else
                    Your Performance Analysis for {{ $selectedMonth }}
                    @endif
                </p>
            </div>
            <div class="col-md-4 text-end">
                <!-- Admin Recruiter Selector -->
                @if($user->role === 'admin' && !empty($allRecruiters))
                <div class="dropdown mb-3">
                    <button class="btn btn-light btn-lg rounded-pill px-4 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-2"></i>
                        {{ $hrStaff }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg">
                        <li><h6 class="dropdown-header">Select Recruiter</h6></li>
                        @foreach($allRecruiters as $recruiter)
                        <li>
                            <a class="dropdown-item" href="?hr_staff={{ urlencode($recruiter) }}&month={{ urlencode($selectedMonth) }}">
                                <i class="bi bi-person me-2"></i>{{ $recruiter }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <!-- Month Selector -->
                <div class="dropdown">
                    <button class="btn btn-light btn-lg rounded-pill px-4 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-calendar-month me-2"></i>
                        {{ $selectedMonth }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg">
                        <li><h6 class="dropdown-header">Select Month</h6></li>
                        @foreach($availableMonths as $month)
                        <li>
                            <a class="dropdown-item" href="?month={{ urlencode($month) }}@if($user->role === 'admin')&hr_staff={{ urlencode($hrStaff) }}@endif">
                                <i class="bi bi-calendar-check me-2"></i>{{ $month }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @if(!$monthlySummary)
    <!-- Empty State -->
    <div class="modern-card">
        <div class="card-body">
            <div class="empty-state">
                <i class="bi bi-bar-chart empty-state-icon"></i>
                <h3 class="mb-3">No Data Available</h3>
                <p class="text-muted mb-4">
                    @if($user->role === 'admin')
                    No monthly data found for {{ $hrStaff }} in {{ $selectedMonth }}
                    @else
                    You haven't added any progress data for {{ $selectedMonth }} yet
                    @endif
                </p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('recruiter.daily') }}?month={{ urlencode($selectedMonth) }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i> Add Daily Progress
                    </a>
                    @if($user->role === 'admin')
                    <a href="{{ route('admin.recruiters.all-daily') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i> Back to All
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @else
    @php
        // Calculate total values from monthly summary
        $totals = json_decode($monthlySummary->totals, true);
        $unitProgress = json_decode($monthlySummary->unit_progress, true);
        $overallProgress = json_decode($monthlySummary->overall_progress, true);
        
        // Calculate activities array dynamically
        $activities = [
            [
                'name' => 'Business Profile', 
                'target' => 520, 
                'achieved' => $totals['busi_profile'] ?? 0,
                'percentage' => $unitProgress['busi_profile'] ?? 0,
                'trend' => $trends['busi_profile'] ?? 'neutral',
                'icon' => 'bi-building'
            ],
            [
                'name' => 'Tele Marketing', 
                'target' => 260, 
                'achieved' => $totals['tele_marketing'] ?? 0,
                'percentage' => $unitProgress['tele_marketing'] ?? 0,
                'trend' => $trends['tele_marketing'] ?? 'neutral',
                'icon' => 'bi-telephone'
            ],
            [
                'name' => 'Client Emp.', 
                'target' => 10.4, 
                'achieved' => $totals['client_empanelment'] ?? 0,
                'percentage' => $unitProgress['client_empanelment'] ?? 0,
                'trend' => $trends['client_empanelment'] ?? 'neutral',
                'icon' => 'bi-person-badge'
            ],
            [
                'name' => 'CV Submitted', 
                'target' => 182, 
                'achieved' => $totals['cv_submitted'] ?? 0,
                'percentage' => $unitProgress['cv_submitted'] ?? 0,
                'trend' => $trends['cv_submitted'] ?? 'neutral',
                'icon' => 'bi-file-text'
            ],
            [
                'name' => 'Interviews', 
                'target' => 45.5, 
                'achieved' => $totals['interview_attended'] ?? 0,
                'percentage' => $unitProgress['interview_attended'] ?? 0,
                'trend' => $trends['interview_attended'] ?? 'neutral',
                'icon' => 'bi-people'
            ],
            [
                'name' => 'Selections', 
                'target' => 45.5, 
                'achieved' => $totals['selected'] ?? 0,
                'percentage' => $unitProgress['selected'] ?? 0,
                'trend' => $trends['selected'] ?? 'neutral',
                'icon' => 'bi-check-circle'
            ],
            [
                'name' => 'Jobs Reported', 
                'target' => 6, 
                'achieved' => $totals['job_reported'] ?? 0,
                'percentage' => $unitProgress['job_reported'] ?? 0,
                'trend' => $trends['job_reported'] ?? 'neutral',
                'icon' => 'bi-briefcase'
            ],
            [
                'name' => 'Invoiced', 
                'target' => 6, 
                'achieved' => $totals['invoiced'] ?? 0,
                'percentage' => $unitProgress['invoiced'] ?? 0,
                'trend' => $trends['invoiced'] ?? 'neutral',
                'icon' => 'bi-receipt'
            ],
            [
                'name' => 'Payment Received', 
                'target' => 3, 
                'achieved' => $totals['payment_received'] ?? 0,
                'percentage' => $unitProgress['payment_received'] ?? 0,
                'trend' => $trends['payment_received'] ?? 'neutral',
                'icon' => 'bi-cash-coin'
            ],
        ];
        
        // Calculate overall totals
        $overallTarget = array_sum(array_column($activities, 'target'));
        $overallAchieved = array_sum(array_column($activities, 'achieved'));
        $overallPercentage = $overallTarget > 0 ? round(($overallAchieved / $overallTarget) * 100, 1) : 0;
    @endphp

    <!-- KPI Metrics -->
    <div class="row g-4 mb-4">
        @php
            $kpiCards = [
                [
                    'title' => 'CVs Submitted',
                    'value' => $kpiMetrics['cvs']['value'],
                    'target' => $kpiMetrics['cvs']['target'],
                    'percentage' => $kpiMetrics['cvs']['percentage'],
                    'trend' => $kpiMetrics['cvs']['trend'],
                    'color' => 'success',
                    'icon' => 'bi-file-text'
                ],
                [
                    'title' => 'Interviews',
                    'value' => $kpiMetrics['interviews']['value'],
                    'target' => $kpiMetrics['interviews']['target'],
                    'percentage' => $kpiMetrics['interviews']['percentage'],
                    'trend' => $kpiMetrics['interviews']['trend'],
                    'color' => 'primary',
                    'icon' => 'bi-people'
                ],
                [
                    'title' => 'Selections',
                    'value' => $kpiMetrics['selections']['value'],
                    'target' => $kpiMetrics['selections']['target'],
                    'percentage' => $kpiMetrics['selections']['percentage'],
                    'trend' => $kpiMetrics['selections']['trend'],
                    'color' => 'warning',
                    'icon' => 'bi-check-circle'
                ],
                [
                    'title' => 'Payments Rec.',
                    'value' => $kpiMetrics['payments']['value'],
                    'target' => $kpiMetrics['payments']['target'],
                    'percentage' => $kpiMetrics['payments']['percentage'],
                    'trend' => $kpiMetrics['payments']['trend'],
                    'color' => 'danger',
                    'icon' => 'bi-cash-coin'
                ],
            ];
        @endphp
        
        @foreach($kpiCards as $kpi)
        @php
            $trendClass = $kpi['trend'] > 0 ? 'trend-up' : ($kpi['trend'] < 0 ? 'trend-down' : 'trend-neutral');
            $trendIcon = $kpi['trend'] > 0 ? 'arrow-up' : ($kpi['trend'] < 0 ? 'arrow-down' : 'dash');
            $trendText = $kpi['trend'] > 0 ? abs($kpi['trend']) : ($kpi['trend'] < 0 ? abs($kpi['trend']) : 0);
        @endphp
        <div class="col-xl-3 col-lg-6">
            <div class="kpi-card {{ $kpi['color'] }}">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="kpi-icon bg-{{ $kpi['color'] }} bg-gradient text-white me-3">
                            <i class="bi {{ $kpi['icon'] }}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">{{ $kpi['title'] }}</h6>
                            <h2 class="mb-0 text-{{ $kpi['color'] }}">{{ $kpi['value'] }}</h2>
                            <div class="d-flex align-items-center mt-2">
                                <span class="{{ $trendClass }} me-2">
                                    <i class="bi bi-{{ $trendIcon }} me-1"></i>{{ number_format($trendText, 1) }}%
                                </span>
                                <small class="text-muted">vs last month</small>
                            </div>
                        </div>
                    </div>
                    <div class="modern-progress mt-3">
                        <div class="modern-progress-bar bg-{{ $kpi['color'] }}" style="width: {{ $kpi['percentage'] }}%">
                            <span class="progress-text">{{ number_format($kpi['percentage'], 1) }}% of target</span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <small class="text-muted">Target: {{ $kpi['target'] }}</small>
                        <small class="text-{{ $kpi['color'] }} fw-bold">
                            Achieved: {{ number_format($kpi['percentage'], 1) }}%
                        </small>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Charts Section -->
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="chart-container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0">
                        <i class="bi bi-bar-chart-line me-2 text-primary"></i>
                        Performance Trend Analysis
                    </h4>
                    <div class="btn-group">
                        <button class="btn btn-outline-primary btn-sm active" onclick="changeChartView('daily')">Daily</button>
                        <button class="btn btn-outline-primary btn-sm" onclick="changeChartView('weekly')">Weekly</button>
                    </div>
                </div>
                <div id="performanceChart" style="height: 350px;"></div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="chart-container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0">
                        <i class="bi bi-pie-chart me-2 text-success"></i>
                        Selection Distribution
                    </h4>
                    @php
                        $totalCVs = $totals['cv_submitted'] ?? 0;
                        $totalSelected = $totals['selected'] ?? 0;
                        $totalRejected = ($totals['cv_submitted'] ?? 0) - ($totals['selected'] ?? 0);
                        $conversionRate = $totalCVs > 0 ? round(($totalSelected / $totalCVs) * 100, 1) : 0;
                    @endphp
                    <span class="badge bg-success">Conversion: {{ $conversionRate }}%</span>
                </div>
                <div id="selectionChart" style="height: 350px;"></div>
                <div class="row text-center mt-4">
                    <div class="col-6">
                        <div class="p-3 border rounded-3">
                            <h4 class="text-success mb-1">{{ $totalSelected }}</h4>
                            <small class="text-muted">Selected</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 border rounded-3">
                            <h4 class="text-danger mb-1">{{ $totalRejected > 0 ? $totalRejected : 0 }}</h4>
                            <small class="text-muted">Not Selected</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Activity Summary -->
    <div class="modern-card mb-4">
        <div class="card-body p-0">
            <div class="d-flex justify-content-between align-items-center p-4 border-bottom">
                <div>
                    <h4 class="mb-0">
                        <i class="bi bi-speedometer2 me-2 text-primary"></i>
                        Monthly Activity Dashboard
                    </h4>
                    <p class="text-muted mb-0">Detailed breakdown of all key metrics</p>
                </div>
                <div>
                    <span class="badge bg-primary px-3 py-2 me-2">
                        <i class="bi bi-calendar-week me-1"></i>Week {{ date('W', strtotime($selectedMonth . ' 01')) }}
                    </span>
                    <button class="btn btn-outline-primary" onclick="refreshMonthlyData()">
                        <i class="bi bi-arrow-clockwise"></i> Refresh
                    </button>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table modern-table mb-0">
                    <thead>
                        <tr>
                            <th width="200">Key Metric</th>
                            <th class="text-center">Target</th>
                            <th class="text-center">Achieved</th>
                            <th class="text-center">Remaining</th>
                            <th class="text-center">Progress</th>
                            <th class="text-center">Daily Average</th>
                            <th class="text-center">Trend</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activities as $activity)
                        @php
                            $remaining = $activity['target'] - $activity['achieved'];
                            $dailyAvg = $activity['achieved'] / date('t', strtotime($selectedMonth . ' 01'));
                            $statusClass = $activity['percentage'] >= 100 ? 'success' : 
                                         ($activity['percentage'] >= 75 ? 'primary' : 
                                         ($activity['percentage'] >= 50 ? 'warning' : 'danger'));
                            $statusText = $activity['percentage'] >= 100 ? 'Achieved' : 
                                        ($activity['percentage'] >= 75 ? 'Excellent' : 
                                        ($activity['percentage'] >= 50 ? 'On Track' : 'Needs Focus'));
                            $trendClass = $activity['trend'] > 0 ? 'trend-up' : 
                                        ($activity['trend'] < 0 ? 'trend-down' : 'trend-neutral');
                            $trendIcon = $activity['trend'] > 0 ? 'arrow-up' : 
                                       ($activity['trend'] < 0 ? 'arrow-down' : 'dash');
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="bi {{ $activity['icon'] }} text-primary"></i>
                                    </div>
                                    <strong>{{ $activity['name'] }}</strong>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="fw-bold text-dark">{{ $activity['target'] }}</span>
                            </td>
                            <td class="text-center">
                                <span class="fw-bold text-{{ $statusClass }}">{{ $activity['achieved'] }}</span>
                            </td>
                            <td class="text-center">
                                <span class="text-muted">{{ number_format($remaining, 1) }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="modern-progress flex-grow-1 me-3">
                                        <div class="modern-progress-bar bg-{{ $statusClass }}" style="width: {{ $activity['percentage'] }}%"></div>
                                    </div>
                                    <span class="fw-bold text-{{ $statusClass }}">{{ number_format($activity['percentage'], 1) }}%</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-light text-dark">{{ number_format($dailyAvg, 1) }}/day</span>
                            </td>
                            <td class="text-center">
                                <span class="{{ $trendClass }}">
                                    <i class="bi bi-{{ $trendIcon }} me-1"></i>
                                    @php
                                            $trend = (float) ($activity['trend'] ?? 0);
                                        @endphp

                                        {{ $trend > 0 ? '+' : '' }}{{ number_format($trend, 1) }}%

                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-{{ $statusClass }} status-badge">
                                    {{ $statusText }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                        
                        <!-- Overall Summary -->
                        <tr class="table-active">
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary rounded-circle p-2 me-3">
                                        <i class="bi bi-trophy text-white"></i>
                                    </div>
                                    <strong>OVERALL PERFORMANCE</strong>
                                </div>
                            </td>
                            <td class="text-center">
                                <strong>{{ number_format($overallTarget, 1) }}</strong>
                            </td>
                            <td class="text-center">
                                <strong class="text-primary">{{ number_format($overallAchieved, 1) }}</strong>
                            </td>
                            <td class="text-center">
                                <strong>{{ number_format($overallTarget - $overallAchieved, 1) }}</strong>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="modern-progress flex-grow-1 me-3">
                                        <div class="modern-progress-bar bg-primary" style="width: {{ $overallPercentage }}%"></div>
                                    </div>
                                    <span class="fw-bold text-primary">{{ $overallPercentage }}%</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary">{{ number_format($overallAchieved / date('t', strtotime($selectedMonth . ' 01')), 1) }}/day</span>
                            </td>
                            <td class="text-center">
                                <span class="trend-up">
                                    <i class="bi bi-arrow-up me-1"></i>{{ number_format($monthlySummary->overall_total ?? 0, 1) }}%
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary status-badge">
                                    @php
                                        $rating = $monthlySummary->overall_total >= 90 ? 'Excellent' : 
                                                ($monthlySummary->overall_total >= 75 ? 'Good' : 
                                                ($monthlySummary->overall_total >= 60 ? 'Average' : 
                                                ($monthlySummary->overall_total >= 40 ? 'Below Average' : 'Poor')));
                                    @endphp
                                    {{ $rating }} ({{ $monthlySummary->overall_total ?? 0 }}%)
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Weekly Performance -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="modern-card">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="mb-0">
                                <i class="bi bi-calendar-week me-2 text-primary"></i>
                                Weekly Performance Breakdown
                            </h4>
                            <p class="text-muted mb-0">Detailed week-by-week progress analysis</p>
                        </div>
                        <!--<button class="btn btn-outline-primary" onclick="exportWeeklyData()">
                            <i class="bi bi-download me-2"></i>Export Weekly Data
                        </button>-->
                    </div>
                    
                    <div class="row g-4">
                        @for($week = 1; $week <= 5; $week++)
                        @php
                            $weekData = $weeklyBreakdown['Week ' . $week] ?? ['cv' => 0, 'interview' => 0, 'selected' => 0, 'payment' => 0];
                            $weekProgress = isset($weekData['cv']) && $weekData['cv'] > 0 ? 
                                round(($weekData['cv'] / (182/5)) * 100, 1) : 0;
                            $statusColors = ['primary', 'success', 'info', 'warning', 'danger'];
                            $statusClass = $statusColors[$week-1] ?? 'primary';
                        @endphp
                        <div class="col-xl-2-4 col-md-6 col-lg-4">
                            <div class="week-card h-100">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <h5 class="mb-0">Week {{ $week }}</h5>
                                            <small class="text-muted">
                                                {{ ($week-1)*7+1 }} - {{ min($week*7, date('t', strtotime($selectedMonth . ' 01'))) }} 
                                                {{ date('M', strtotime($selectedMonth . ' 01')) }}
                                            </small>
                                        </div>
                                        <span class="badge bg-{{ $statusClass }} px-3 py-2">
                                            {{ $weekProgress }}%
                                        </span>
                                    </div>
                                    
                                    <!-- Metrics -->
                                    <div class="row g-2 mb-3">
                                        <div class="col-6">
                                            <div class="border rounded p-2 text-center">
                                                <div class="text-primary fw-bold">{{ $weekData['cv'] ?? 0 }}</div>
                                                <small class="text-muted">CVs</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="border rounded p-2 text-center">
                                                <div class="text-success fw-bold">{{ $weekData['selected'] ?? 0 }}</div>
                                                <small class="text-muted">Selected</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="border rounded p-2 text-center">
                                                <div class="text-info fw-bold">{{ $weekData['interview'] ?? 0 }}</div>
                                                <small class="text-muted">Interviews</small>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="border rounded p-2 text-center">
                                                <div class="text-warning fw-bold">{{ $weekData['payment'] ?? 0 }}</div>
                                                <small class="text-muted">Payments</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Progress -->
                                    <div class="modern-progress mb-2">
                                        <div class="modern-progress-bar bg-{{ $statusClass }}" 
                                             style="width: {{ $weekProgress }}%"></div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <small class="text-muted">Weekly Progress</small>
                                        <small class="fw-bold">{{ $weekProgress }}%</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
   <!-- <div class="row g-4">
        <div class="col-12">
            <div class="modern-card">
                <div class="card-body p-4 text-center">
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <button class="btn btn-primary action-btn w-100" onclick="printReport()">
                                <i class="bi bi-printer me-2"></i>Print Report
                            </button>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('recruiter.export.monthly', $selectedMonth) }}?hr_staff={{ urlencode($hrStaff) }}" 
                               class="btn btn-success action-btn w-100">
                                <i class="bi bi-file-excel me-2"></i>Export Excel
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <button class="btn btn-info action-btn w-100" onclick="generateInsights()">
                                <i class="bi bi-robot me-2"></i>AI Insights
                            </button>
                        </div>
                        @if($user->role === 'admin')
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('admin.recruiters.all-daily') }}" class="btn btn-warning action-btn w-100">
                                <i class="bi bi-people me-2"></i>All Recruiters
                            </a>
                        </div>
                        @else
                        <div class="col-lg-3 col-md-6">
                            <button class="btn btn-warning action-btn w-100" onclick="shareReport()">
                                <i class="bi bi-share me-2"></i>Share Report
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>-->
    @endif
</div>

<!-- Insights Modal -->
<div class="modal fade" id="insightsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-robot me-2"></i>
                    AI Performance Insights
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Insights content will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if($monthlySummary)
        // Dynamic chart data from PHP
        const chartData = {
            cv_data: @json($chartData['cv_data'] ?? []),
            interview_data: @json($chartData['interview_data'] ?? []),
            selection_data: @json($chartData['selection_data'] ?? [])
        };
        
        // Performance Trend Chart
        const performanceChart = new ApexCharts(document.querySelector("#performanceChart"), {
            series: [{
                name: 'CV Submitted',
                data: chartData.cv_data
            }, {
                name: 'Interviews',
                data: chartData.interview_data
            }, {
                name: 'Selections',
                data: chartData.selection_data
            }],
            chart: {
                type: 'area',
                height: '100%',
                fontFamily: 'inherit',
                toolbar: {
                    show: true,
                    tools: {
                        download: true,
                        selection: true,
                        zoom: true,
                        zoomin: true,
                        zoomout: true,
                        pan: true,
                        reset: true
                    }
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                }
            },
            colors: ['#667eea', '#4CAF50', '#FFC107'],
            stroke: {
                curve: 'smooth',
                width: 3,
                lineCap: 'round'
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.6,
                    opacityTo: 0.1,
                    stops: [0, 90, 100]
                }
            },
            grid: {
                borderColor: '#e0e0e0',
                strokeDashArray: 5,
                padding: {
                    top: 20,
                    right: 20,
                    bottom: 20,
                    left: 20
                }
            },
            xaxis: {
                categories: Array.from({length: 31}, (_, i) => i + 1),
                labels: {
                    style: {
                        colors: '#6c757d',
                        fontSize: '12px'
                    }
                },
                axisBorder: {
                    show: true,
                    color: '#e0e0e0'
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#6c757d',
                        fontSize: '12px'
                    },
                    formatter: function(val) {
                        return Math.round(val);
                    }
                }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                fontSize: '14px',
                itemMargin: {
                    horizontal: 10,
                    vertical: 5
                }
            },
            tooltip: {
                theme: 'light',
                x: {
                    show: true,
                    formatter: function(val) {
                        return `Day ${val}`;
                    }
                }
            }
        });
        
        performanceChart.render();
        
        // Selection Distribution Chart
        const selectionChart = new ApexCharts(document.querySelector("#selectionChart"), {
            series: [{{ $totalSelected ?? 0 }}, {{ $totalRejected > 0 ? $totalRejected : 0 }}],
            chart: {
                type: 'donut',
                height: '100%',
                fontFamily: 'inherit',
                animations: {
                    enabled: true,
                    speed: 800,
                    animateGradually: {
                        enabled: true,
                        delay: 150
                    }
                }
            },
            labels: ['Selected', 'Not Selected'],
            colors: ['#4CAF50', '#F44336'],
            legend: {
                position: 'bottom',
                horizontalAlign: 'center',
                fontSize: '14px',
                markers: {
                    width: 12,
                    height: 12,
                    radius: 6
                }
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '70%',
                        labels: {
                            show: true,
                            name: {
                                show: true,
                                fontSize: '16px',
                                fontFamily: 'inherit',
                                color: '#6c757d'
                            },
                            value: {
                                show: true,
                                fontSize: '28px',
                                fontFamily: 'inherit',
                                fontWeight: 'bold',
                                color: '#333',
                                formatter: function(val) {
                                    return val;
                                }
                            },
                            total: {
                                show: true,
                                label: 'Total CVs',
                                color: '#6c757d',
                                fontSize: '14px',
                                formatter: function(w) {
                                    return '{{ $totalCVs ?? 0 }}';
                                }
                            }
                        }
                    }
                }
            },
            dataLabels: {
                enabled: true,
                style: {
                    fontSize: '14px',
                    fontFamily: 'inherit',
                    colors: ['#fff']
                },
                dropShadow: {
                    enabled: true,
                    top: 1,
                    left: 1,
                    blur: 2,
                    opacity: 0.8
                }
            },
            tooltip: {
                y: {
                    formatter: function(value) {
                        const total = {{ $totalCVs ?? 1 }};
                        const percentage = total > 0 ? (value / total * 100).toFixed(1) : 0;
                        return `${value} candidates (${percentage}%)`;
                    }
                }
            }
        });
        
        selectionChart.render();
        @endif
    });
    
    function changeChartView(view) {
        const dailyBtn = document.querySelector('button[onclick*="daily"]');
        const weeklyBtn = document.querySelector('button[onclick*="weekly"]');
        
        if (view === 'daily') {
            dailyBtn.classList.add('active');
            weeklyBtn.classList.remove('active');
            // Load daily chart data
        } else {
            weeklyBtn.classList.add('active');
            dailyBtn.classList.remove('active');
            // Load weekly chart data
        }
    }
    
    function refreshMonthlyData() {
        const month = '{{ $selectedMonth }}';
        const hrStaff = '{{ $hrStaff }}';
        
        fetch(`/recruiter/monthly-progress/calculate`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ month: month, hr_staff: hrStaff })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            showNotification('Error refreshing data', 'error');
        });
    }
    
    function exportWeeklyData() {
        const month = '{{ $selectedMonth }}';
        const hrStaff = '{{ $hrStaff }}';
        window.location.href = `/admin/export/weekly?month=${encodeURIComponent(month)}&hr_staff=${encodeURIComponent(hrStaff)}`;
    }
    
    function generateInsights() {
        const insights = [
            `📊 Overall Performance: {{ $monthlySummary->overall_total ?? 0 }}% ({{ $rating ?? 'No Rating' }})`,
            `📈 CV Submission Rate: {{ $kpiMetrics['cvs']['percentage'] ?? 0 }}% of target`,
            `🎯 Selection Conversion: {{ $conversionRate ?? 0 }}% from CVs to Selections`,
            @if(($kpiMetrics['selections']['percentage'] ?? 0) < 70)
            `⚠️ Selection rate needs improvement ({{ $kpiMetrics['selections']['percentage'] ?? 0 }}% of target)`,
            @endif
            @if(($kpiMetrics['payments']['percentage'] ?? 0) < 70)
            `💰 Payment collection is below target ({{ $kpiMetrics['payments']['percentage'] ?? 0 }}%)`,
            @endif
            `📅 {{ $selectedMonth }}: {{ $totalSelected ?? 0 }} selections from {{ $totalCVs ?? 0 }} CVs`
        ];
        
        const modalBody = document.querySelector('#insightsModal .modal-body');
        modalBody.innerHTML = `
            <div class="alert alert-info">
                <h5><i class="bi bi-lightbulb"></i> AI Performance Insights</h5>
                <ul class="mb-0">
                    ${insights.map(insight => `<li class="mb-2">${insight}</li>`).join('')}
                </ul>
            </div>
            <div class="mt-3">
                <h6>Recommendations:</h6>
                <ul>
                    <li>Focus on improving interview-to-selection conversion rate</li>
                    <li>Increase follow-up frequency for pending payments</li>
                    <li>Maintain current CV submission rate to meet monthly targets</li>
                </ul>
            </div>
        `;
        
        const modal = new bootstrap.Modal(document.getElementById('insightsModal'));
        modal.show();
    }
    
    function showNotification(message, type = 'info') {
        // Your existing notification function
    }
</script>

<style>
    .col-xl-2-4 {
        flex: 0 0 auto;
        width: 20%;
    }
    
    @media (max-width: 1400px) {
        .col-xl-2-4 {
            width: 25%;
        }
    }
    
    @media (max-width: 1200px) {
        .col-xl-2-4 {
            width: 33.333%;
        }
    }
    
    @media (max-width: 768px) {
        .col-xl-2-4 {
            width: 50%;
        }
    }
    
    @media (max-width: 576px) {
        .col-xl-2-4 {
            width: 100%;
        }
    }
</style>
@endsection