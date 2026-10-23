{{-- resources/views/recruiters/monthwise.blade.php --}}
@extends('layouts.app')

@section('title', 'Annual Performance Dashboard')
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
        background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .modern-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
    }
    
    .modern-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: var(--primary-gradient);
        border-radius: 20px 20px 0 0;
    }
    
    /* Month Selector Cards */
    .month-card {
        border-radius: 15px;
        border: 2px solid transparent;
        background: white;
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    
    .month-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }
    
    .month-card.active {
        border-color: #667eea;
        background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.15);
    }
    
    .month-card.past::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--success-gradient);
    }
    
    .month-card.current::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--primary-gradient);
    }
    
    .month-card.future::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: #e9ecef;
    }
    
    /* Comparison Table */
    .comparison-table {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
    }
    
    .comparison-table thead {
        background: var(--primary-gradient);
        color: white;
    }
    
    .comparison-table th {
        border: none;
        padding: 15px;
        font-weight: 500;
        letter-spacing: 0.5px;
        position: relative;
    }
    
    .comparison-table th::after {
        content: '';
        position: absolute;
        right: 0;
        top: 20%;
        height: 60%;
        width: 1px;
        background: rgba(255,255,255,0.3);
    }
    
    .comparison-table th:last-child::after {
        display: none;
    }
    
    .comparison-table tbody tr {
        transition: all 0.3s ease;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .comparison-table tbody tr:hover {
        background: linear-gradient(90deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
    }
    
    .comparison-table td {
        padding: 12px 15px;
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
    
    /* Quarter Cards */
    .quarter-card {
        border-radius: 15px;
        border: none;
        background: white;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .quarter-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
    }
    
    .quarter-card.q1::before { background: var(--primary-gradient); }
    .quarter-card.q2::before { background: var(--success-gradient); }
    .quarter-card.q3::before { background: var(--info-gradient); }
    .quarter-card.q4::before { background: var(--warning-gradient); }
    
    /* Progress Circles */
    .progress-circle {
        width: 80px;
        height: 80px;
        position: relative;
    }
    
    .progress-circle svg {
        width: 80px;
        height: 80px;
        transform: rotate(-90deg);
    }
    
    .progress-circle-bg {
        fill: none;
        stroke: #e9ecef;
        stroke-width: 6;
    }
    
    .progress-circle-fill {
        fill: none;
        stroke-width: 6;
        stroke-linecap: round;
        stroke-dasharray: 226;
        stroke-dashoffset: 226;
        transition: stroke-dashoffset 1s ease;
    }
    
    .progress-circle-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-weight: bold;
        font-size: 1rem;
    }
    
    /* Insight Cards */
    .insight-card {
        border-radius: 15px;
        border: none;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }
    
    .insight-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
    }
    
    /* Header */
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
    
    /* Action Buttons */
    .action-btn {
        border-radius: 25px;
        padding: 12px 30px;
        font-weight: 500;
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    }
    
    .action-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
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
        font-size: 4rem;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 20px;
    }
</style>
<div class="container-fluid px-4 py-4">
    <!-- Modern Header -->
    <div class="modern-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="display-5 fw-bold mb-3">
                    <i class="bi bi-calendar-range me-3"></i>
                    Annual Performance Dashboard
                </h1>
                <p class="lead mb-0 opacity-90">
                    Year-to-date analysis and quarter-wise comparison for {{ $selectedYear }}
                </p>
                <p class="mb-0 opacity-90">
                    <small>HR Staff: <strong>{{ $hrStaff }}</strong></small>
                </p>
            </div>
            <div class="col-md-4 text-end">
                @if($user->role==='admin')
                <!-- HR Staff Selector -->
                <div class="dropdown d-inline-block me-3">
                    <button class="btn btn-light btn-lg rounded-pill px-4 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person me-2"></i>
                        {{ $hrStaff }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg" style="z-index: 1050;">
                        <li><h6 class="dropdown-header">Select HR Staff</h6></li>
                        @foreach($allHrStaff as $staff)
                        <li>
                            <a class="dropdown-item" href="?year={{ $selectedYear }}&hr_staff={{ $staff }}">
                                <i class="bi bi-person-circle me-2"></i>{{ $staff }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                <!-- Year Selector -->
                <div class="dropdown d-inline-block">
                    <button class="btn btn-light btn-lg rounded-pill px-4 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-calendar-year me-2"></i>
                        {{ $selectedYear }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg" style="z-index: 1050;">
                        <li><h6 class="dropdown-header">Select Year</h6></li>
                        @foreach($availableYears as $year)
                        <li>
                            <a class="dropdown-item" href="?year={{ $year }}&hr_staff={{ $hrStaff }}">
                                <i class="bi bi-calendar-check me-2"></i>{{ $year }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="modern-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-gradient rounded-circle p-3 me-3 text-white">
                            <i class="bi bi-file-text fs-4"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">YTD CVs Submitted</h6>
                            <h3 class="mb-0 text-primary">{{ $ytdData['totals']['cv_submitted'] ?? 0 }}</h3>
                            @if($previousYearData && isset($previousYearData['comparison']['cv_submitted']))
                            <small class="{{ $previousYearData['comparison']['cv_submitted'] >= 0 ? 'text-success' : 'text-danger' }}">
                                <i class="bi bi-arrow-{{ $previousYearData['comparison']['cv_submitted'] >= 0 ? 'up' : 'down' }} me-1"></i>
                                {{ abs($previousYearData['comparison']['cv_submitted']) }}% vs last year
                            </small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="modern-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-gradient rounded-circle p-3 me-3 text-white">
                            <i class="bi bi-check-circle fs-4"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">YTD Selections</h6>
                            <h3 class="mb-0 text-success">{{ $ytdData['totals']['selected'] ?? 0 }}</h3>
                            @if($previousYearData && isset($previousYearData['comparison']['selected']))
                            <small class="{{ $previousYearData['comparison']['selected'] >= 0 ? 'text-success' : 'text-danger' }}">
                                <i class="bi bi-arrow-{{ $previousYearData['comparison']['selected'] >= 0 ? 'up' : 'down' }} me-1"></i>
                                {{ abs($previousYearData['comparison']['selected']) }}% vs last year
                            </small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="modern-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning bg-gradient rounded-circle p-3 me-3 text-white">
                            <i class="bi bi-speedometer2 fs-4"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Avg. Conversion</h6>
                            @php
                                $totalCv = $ytdData['totals']['cv_submitted'] ?? 1;
                                $totalSelected = $ytdData['totals']['selected'] ?? 0;
                                $conversionRate = $totalCv > 0 ? ($totalSelected / $totalCv * 100) : 0;
                            @endphp
                            <h3 class="mb-0 text-warning">{{ number_format($conversionRate, 1) }}%</h3>
                            <small class="text-muted">
                                Based on {{ $ytdData['months_count'] ?? 0 }} months
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="modern-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-info bg-gradient rounded-circle p-3 me-3 text-white">
                            <i class="bi bi-trophy fs-4"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Avg Monthly Progress</h6>
                            <h3 class="mb-0 text-info">{{ number_format($ytdData['avg_monthly_progress'] ?? 0, 1) }}%</h3>
                            <small class="text-muted">
                                {{ $ytdData['months_count'] ?? 0 }} months analyzed
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="chart-container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0">
                        <i class="bi bi-bar-chart-line me-2 text-primary"></i>
                        Monthly Performance Trends
                    </h4>
                    <div class="btn-group">
                        <button class="btn btn-outline-primary btn-sm active" onclick="changeChartView('cv')">
                            CV Trends
                        </button>
                        <button class="btn btn-outline-primary btn-sm" onclick="changeChartView('selection')">
                            Selection Trends
                        </button>
                        <button class="btn btn-outline-primary btn-sm" onclick="changeChartView('revenue')">
                            Revenue Trends
                        </button>
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
                        Quarter Performance Distribution
                    </h4>
                    <span class="badge bg-success">{{ $selectedYear }}</span>
                </div>
                <div id="quarterChart" style="height: 350px;"></div>
                <div class="row text-center mt-4">
                    @foreach(['Q1', 'Q2', 'Q3', 'Q4'] as $quarter)
                    <div class="col-6 mb-3">
                        <div class="p-3 border rounded-3">
                            <h5 class="text-{{ $quarter == 'Q1' ? 'primary' : ($quarter == 'Q2' ? 'success' : ($quarter == 'Q3' ? 'info' : 'warning')) }} mb-1">
                                {{ $quarter }}
                            </h5>
                            <small class="text-muted">
                                @if(isset($quarterData[$quarter]['quarter_totals']))
                                CV: {{ $quarterData[$quarter]['quarter_totals']['cv_submitted'] ?? 0 }}
                                @else
                                No data
                                @endif
                            </small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Month Selector -->
    <div class="modern-card mb-4">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-0">
                        <i class="bi bi-calendar3 me-2 text-primary"></i>
                        Monthly Performance
                    </h4>
                    <p class="text-muted mb-0">Select months to compare (max 3)</p>
                </div>
                <div class="d-flex gap-2">
                    <!--<button class="btn btn-outline-primary" onclick="compareSelected()">
                        <i class="bi bi-arrow-left-right me-2"></i>Compare Selected
                    </button>-->
                    <button class="btn btn-outline-danger" onclick="clearSelection()">
                        <i class="bi bi-x-circle me-2"></i>Clear
                    </button>
                </div>
            </div>
            
            <div class="row g-3" id="monthGrid">
                @php
                    $monthsData = [];
                    $currentMonth = now()->format('M');
                    $currentYear = now()->year;
                    
                    for ($i = 1; $i <= 12; $i++) {
                        $monthDate = Carbon\Carbon::createFromDate($selectedYear, $i, 1);
                        $monthName = $monthDate->format('M');
                        $quarter = 'Q' . ceil($i / 3);
                        
                        $monthRecord = $monthlyData->firstWhere('month', $monthDate->format('F Y'));
                        $totals = $monthRecord ? json_decode($monthRecord->totals, true) : [];
                        
                        if ($selectedYear < $currentYear) {
                            $status = 'completed';
                        } elseif ($selectedYear == $currentYear) {
                            $status = $monthDate->month < now()->month ? 'completed' : 
                                     ($monthDate->month == now()->month ? 'current' : 'upcoming');
                        } else {
                            $status = 'upcoming';
                        }
                        
                        $monthsData[] = [
                            'name' => $monthName,
                            'full_name' => $monthDate->format('F Y'),
                            'quarter' => $quarter,
                            'cvs' => $totals['cv_submitted'] ?? 0,
                            'selections' => $totals['selected'] ?? 0,
                            'progress' => $monthRecord ? $monthRecord->overall_total : 0,
                            'status' => $status,
                            'has_data' => !is_null($monthRecord)
                        ];
                    }
                @endphp
                
                @foreach($monthsData as $month)
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                    <div class="month-card {{ $month['status'] }}"
                         data-month="{{ $month['name'] }}"
                         data-full-month="{{ $month['full_name'] }}"
                         onclick="toggleMonthSelection('{{ $month['name'] }}', '{{ $month['full_name'] }}')">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-{{ $month['quarter'] == 'Q1' ? 'primary' : ($month['quarter'] == 'Q2' ? 'success' : ($month['quarter'] == 'Q3' ? 'info' : 'warning')) }}">
                                    {{ $month['quarter'] }}
                                </span>
                                <span class="badge bg-{{ $month['status'] == 'completed' ? 'success' : ($month['status'] == 'current' ? 'primary' : 'secondary') }}">
                                    {{ $month['status'] }}
                                </span>
                            </div>
                            
                            <h5 class="text-center mb-3">{{ $month['name'] }}</h5>
                            
                            <div class="text-center mb-3">
                                <div class="progress-circle mx-auto">
                                    <svg>
                                        <circle class="progress-circle-bg" cx="40" cy="40" r="35"></circle>
                                        <circle class="progress-circle-fill" cx="40" cy="40" r="35"
                                                data-progress="{{ $month['progress'] }}"
                                                stroke="{{ $month['progress'] >= 90 ? '#4CAF50' : ($month['progress'] >= 75 ? '#2196F3' : ($month['progress'] >= 50 ? '#FFC107' : '#F44336')) }}"></circle>
                                    </svg>
                                    <div class="progress-circle-text">
                                        {{ $month['has_data'] ? number_format($month['progress'], 1) : '0' }}%
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="small text-muted">CVs</div>
                                    <div class="fw-bold">{{ $month['cvs'] }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="small text-muted">Selected</div>
                                    <div class="fw-bold">{{ $month['selections'] }}</div>
                                </div>
                            </div>
                            
                            <div class="text-center mt-3">
                                @php
                                    $conversion = ($month['cvs'] > 0 && $month['selections'] > 0) 
                                        ? round(($month['selections'] / $month['cvs']) * 100, 1) 
                                        : 0;
                                @endphp
                                <span class="badge bg-light text-dark">
                                    {{ $conversion }}%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Quarter Performance -->
    <div class="modern-card mb-4 p-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-0">
                        <i class="bi bi-grid-3x3-gap me-2 text-primary"></i>
                        Quarter-wise Performance Analysis
                    </h4>
                    <p class="text-muted mb-0">Detailed quarter performance metrics</p>
                </div>
                <!--<button class="btn btn-outline-primary" onclick="exportQuarterData()">
                    <i class="bi bi-download me-2"></i>Export Quarter Data
                </button>-->
            </div>
            
            <div class="row g-4">
                @foreach(['Q1', 'Q2', 'Q3', 'Q4'] as $quarter)
                @php
                    $quarterTotals = $quarterData[$quarter]['quarter_totals'] ?? [];
                    $cvCount = $quarterTotals['cv_submitted'] ?? 0;
                    $selectionCount = $quarterTotals['selected'] ?? 0;
                    $progress = $quarterData[$quarter]['avg_progress'] ?? 0;
                    
                    if ($progress > 90) {
                        $trend = 'up';
                        $trendClass = 'trend-up';
                    } elseif ($progress < 60) {
                        $trend = 'down';
                        $trendClass = 'trend-down';
                    } else {
                        $trend = 'neutral';
                        $trendClass = 'trend-neutral';
                    }
                    
                    $conversion = $cvCount > 0 ? round(($selectionCount / $cvCount) * 100, 1) : 0;
                @endphp
                <div class="col-xl-3 col-md-6">
                    <div class="quarter-card p-2 {{ strtolower($quarter) }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h5 class="mb-0">{{ $quarter }}</h5>
                                    <small class="text-muted">
                                        @switch($quarter)
                                            @case('Q1') Jan - Mar @break
                                            @case('Q2') Apr - Jun @break
                                            @case('Q3') Jul - Sep @break
                                            @case('Q4') Oct - Dec @break
                                        @endswitch
                                    </small>
                                </div>
                                <span class="trend-indicator {{ $trendClass }}">
                                    <i class="bi bi-arrow-{{ $trend == 'up' ? 'up' : ($trend == 'down' ? 'down' : 'dash') }} me-1"></i>
                                    {{ $trend }}
                                </span>
                            </div>
                            
                            <div class="row text-center mb-3">
                                <div class="col-4">
                                    <div class="border rounded p-2">
                                        <div class="text-primary fw-bold">{{ $cvCount }}</div>
                                        <small class="text-muted">CVs</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border rounded p-2">
                                        <div class="text-success fw-bold">{{ $selectionCount }}</div>
                                        <small class="text-muted">Selected</small>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="border rounded p-2">
                                        <div class="text-info fw-bold">{{ $conversion }}%</div>
                                        <small class="text-muted">Conversion</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="progress mb-2" style="height: 8px;">
                                <div class="progress-bar bg-{{ $quarter == 'Q1' ? 'primary' : ($quarter == 'Q2' ? 'success' : ($quarter == 'Q3' ? 'info' : 'warning')) }}" 
                                     style="width: {{ min($progress, 100) }}%"></div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <small class="text-muted">Progress</small>
                                <small class="fw-bold">{{ number_format($progress, 1) }}%</small>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Performance Insights -->
    <div class="row g-4 mb-4">
        <div class="col-lg-4">
            <div class="insight-card h-100">
                <div class="card-body p-2">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-success bg-gradient rounded-circle p-3 me-3">
                            <i class="bi bi-trophy text-white fs-4"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">Best Performance</h5>
                            <p class="text-muted mb-0">
                                @if($insights['best_month'])
                                {{ \Carbon\Carbon::createFromFormat('F Y', $insights['best_month']['month'])->format('M') }} - {{ $insights['best_month']['progress'] }}%
                                @else
                                No data available
                                @endif
                            </p>
                        </div>
                    </div>
                    @if($insights['best_month'])
                    <p class="text-muted">
                        Achieved the highest overall performance in {{ \Carbon\Carbon::createFromFormat('F Y', $insights['best_month']['month'])->format('F') }} with {{ $insights['best_month']['cv_submitted'] }} CVs submitted.
                    </p>
                    <div class="text-center">
                        <div class="display-4 text-success">{{ $insights['best_month']['progress'] }}%</div>
                        <small class="text-muted">Overall Achievement</small>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="insight-card h-100">
                <div class="card-body  p-2">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-gradient rounded-circle p-3 me-3">
                            <i class="bi bi-graph-up-arrow text-white fs-4"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">Performance Trend</h5>
                            <p class="text-muted mb-0">Overall Progress</p>
                        </div>
                    </div>
                    <p class="text-muted">
                        @if($insights['trend'] == 'improving')
                        Performance is showing improvement compared to earlier months.
                        @elseif($insights['trend'] == 'declining')
                        Performance has declined recently. Needs attention.
                        @else
                        Performance remains stable across months.
                        @endif
                    </p>
                    <div class="text-center">
                        <div class="display-4 text-primary">{{ ucfirst($insights['trend']) }}</div>
                        <small class="text-muted">Current Trend</small>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="insight-card h-100">
                <div class="card-body  p-2">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-warning bg-gradient rounded-circle p-3 me-3">
                            <i class="bi bi-exclamation-triangle text-white fs-4"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">Recommendations</h5>
                            <p class="text-muted mb-0">Based on Analysis</p>
                        </div>
                    </div>
                    @if(!empty($insights['recommendations']))
                    <ul class="list-unstyled">
                        @foreach($insights['recommendations'] as $recommendation)
                        <li class="mb-2">
                            <i class="bi bi-arrow-right-short text-warning me-2"></i>
                            <small>{{ $recommendation }}</small>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <p class="text-muted">No specific recommendations at this time.</p>
                    @endif
                    <div class="text-center mt-3">
                        <div class="display-4 text-warning">{{ count($insights['recommendations'] ?? []) }}</div>
                        <small class="text-muted">Action Items</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <!--<div class="modern-card">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-lg-3 col-md-6">
                    <button class="btn btn-primary action-btn w-100" onclick="printDashboard()">
                        <i class="bi bi-printer me-2"></i>Print Dashboard
                    </button>
                </div>
                <div class="col-lg-3 col-md-6">
                    <button class="btn btn-success action-btn w-100" onclick="generateYearlyReport()">
                        <i class="bi bi-file-earmark-text me-2"></i>Annual Report
                    </button>
                </div>
                <div class="col-lg-3 col-md-6">
                    <button class="btn btn-info action-btn w-100" onclick="showForecast()">
                        <i class="bi bi-graph-up me-2"></i>Forecast
                    </button>
                </div>
                <div class="col-lg-3 col-md-6">
                    <button class="btn btn-warning action-btn w-100" onclick="compareWithLastYear()">
                        <i class="bi bi-calendar2-check me-2"></i>Year Comparison
                    </button>
                </div>
            </div>
        </div>
    </div> -->
</div>

<!-- Comparison Modal -->
<div class="modal fade" id="comparisonModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-arrow-left-right me-2"></i>
                    Month Comparison
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="comparisonModalBody">
                <!-- Comparison content will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    let selectedMonths = [];
    
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize charts with dynamic data
        initializeCharts();
        
        // Animate progress circles
        setTimeout(() => {
            document.querySelectorAll('.progress-circle-fill').forEach(circle => {
                const progress = parseFloat(circle.getAttribute('data-progress')) || 0;
                const offset = 226 - (226 * progress / 100);
                circle.style.strokeDashoffset = offset;
            });
        }, 500);
    });
    
    function initializeCharts() {
        // Prepare chart data from PHP
        const months = @json($monthsData);
        const monthlyData = @json($monthlyData);
        
        // Extract data for charts
        const monthNames = months.map(m => m.name);
        const cvData = months.map(m => m.cvs);
        const selectionData = months.map(m => m.selections);
        
        // Performance Trend Chart
        const performanceChart = new ApexCharts(document.querySelector("#performanceChart"), {
            series: [{
                name: 'CV Submitted',
                data: cvData
            }, {
                name: 'Selections',
                data: selectionData
            }],
            chart: {
                type: 'line',
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
                }
            },
            colors: ['#667eea', '#4CAF50'],
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
                strokeDashArray: 5
            },
            xaxis: {
                categories: monthNames,
                labels: {
                    style: {
                        colors: '#6c757d',
                        fontSize: '12px'
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#6c757d',
                        fontSize: '12px'
                    }
                }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                fontSize: '14px'
            }
        });
        
        performanceChart.render();
        
        // Quarter Performance Chart
        const quarterProgress = [
            {{ $quarterData['Q1']['avg_progress'] ?? 0 }},
            {{ $quarterData['Q2']['avg_progress'] ?? 0 }},
            {{ $quarterData['Q3']['avg_progress'] ?? 0 }},
            {{ $quarterData['Q4']['avg_progress'] ?? 0 }}
        ];
        
        const quarterChart = new ApexCharts(document.querySelector("#quarterChart"), {
            series: quarterProgress,
            chart: {
                type: 'donut',
                height: '100%',
                fontFamily: 'inherit'
            },
            labels: [
                `Q1: ${quarterProgress[0].toFixed(1)}%`,
                `Q2: ${quarterProgress[1].toFixed(1)}%`,
                `Q3: ${quarterProgress[2].toFixed(1)}%`,
                `Q4: ${quarterProgress[3].toFixed(1)}%`
            ],
            colors: ['#667eea', '#4CAF50', '#2196F3', '#FFC107'],
            legend: {
                position: 'bottom',
                horizontalAlign: 'center',
                fontSize: '14px'
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%',
                        labels: {
                            show: true,
                            name: {
                                show: true,
                                fontSize: '14px',
                                color: '#6c757d'
                            },
                            value: {
                                show: true,
                                fontSize: '20px',
                                fontWeight: 'bold',
                                color: '#333'
                            },
                            total: {
                                show: true,
                                label: 'Average',
                                color: '#6c757d',
                                formatter: function(w) {
                                    const avg = quarterProgress.reduce((a, b) => a + b, 0) / 4;
                                    return avg.toFixed(1) + '%';
                                }
                            }
                        }
                    }
                }
            }
        });
        
        quarterChart.render();
        
        // Store chart instances
        window.performanceChart = performanceChart;
        window.quarterChart = quarterChart;
    }
    
    function toggleMonthSelection(month, fullMonth) {
        const card = document.querySelector(`.month-card[data-month="${month}"]`);
        
        if (card.classList.contains('future')) {
            showNotification('Future months cannot be selected', 'warning');
            return;
        }
        
        if (selectedMonths.includes(fullMonth)) {
            selectedMonths = selectedMonths.filter(m => m !== fullMonth);
            card.classList.remove('active');
        } else {
            if (selectedMonths.length >= 3) {
                showNotification('Maximum 3 months can be selected', 'warning');
                return;
            }
            selectedMonths.push(fullMonth);
            card.classList.add('active');
        }
        
        updateSelectionStatus();
    }
    
    function compareSelected() {
        if (selectedMonths.length < 2) {
            showNotification('Please select at least 2 months to compare', 'warning');
            return;
        }
        
        // AJAX call to compare months
        $.ajax({
            url: '{{ route("recruiter.monthwise.compare") }}',
            method: 'POST',
            data: {
                months: selectedMonths,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    showComparisonModal(response.data, response.metrics);
                } else {
                    showNotification(response.message, 'error');
                }
            },
            error: function(xhr) {
                showNotification('Error comparing months', 'error');
            }
        });
    }
    
    function showComparisonModal(data, metrics) {
        const modalBody = document.getElementById('comparisonModalBody');
        let html = `
            <div class="table-responsive">
                <table class="table comparison-table">
                    <thead>
                        <tr>
                            <th>Metric</th>
                            ${Object.keys(data).map(month => `<th>${month}</th>`).join('')}
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>CV Submitted</strong></td>
                            ${Object.values(data).map(month => `<td>${month.summary.cv_submitted || month.daily_totals.cv_submitted || 0}</td>`).join('')}
                        </tr>
                        <tr>
                            <td><strong>Selections</strong></td>
                            ${Object.values(data).map(month => `<td>${month.summary.selected || month.daily_totals.selected || 0}</td>`).join('')}
                        </tr>
                        <tr>
                            <td><strong>Conversion Rate</strong></td>
                            ${Object.values(data).map(month => {
                                const cv = month.summary.cv_submitted || month.daily_totals.cv_submitted || 0;
                                const selected = month.summary.selected || month.daily_totals.selected || 0;
                                const rate = cv > 0 ? ((selected / cv) * 100).toFixed(1) : 0;
                                return `<td>${rate}%</td>`;
                            }).join('')}
                        </tr>
                        <tr>
                            <td><strong>Overall Progress</strong></td>
                            ${Object.values(data).map(month => `<td>${month.summary.overall_total || month.overall_total || 0}%</td>`).join('')}
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6>Comparison Insights</h6>
                            <ul>
                                <li>Highest CV Month: ${metrics.highest_cv_month || 'N/A'}</li>
                                <li>Highest Selection Month: ${metrics.highest_selection_month || 'N/A'}</li>
                                <li>Variation: ${metrics.variation ? metrics.variation.toFixed(2) : '0'}</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <h6>Recommendations</h6>
                            <p class="small">Based on the comparison, focus on replicating strategies from the best-performing months.</p>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        modalBody.innerHTML = html;
        const modal = new bootstrap.Modal(document.getElementById('comparisonModal'));
        modal.show();
    }
    
    function clearSelection() {
        document.querySelectorAll('.month-card.active').forEach(card => {
            card.classList.remove('active');
        });
        selectedMonths = [];
        showNotification('Selection cleared', 'info');
    }
    
    function updateSelectionStatus() {
        const statusElement = document.getElementById('selectionStatus') || createStatusElement();
        statusElement.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <strong>Selected Months:</strong> 
                    ${selectedMonths.length > 0 ? selectedMonths.join(', ') : 'None'}
                </div>
                <div>
                    <button class="btn btn-sm btn-primary" onclick="compareSelected()">
                        <i class="bi bi-arrow-left-right me-1"></i> Compare
                    </button>
                    <button class="btn btn-sm btn-outline-danger ms-2" onclick="clearSelection()">
                        <i class="bi bi-x-circle me-1"></i> Clear
                    </button>
                </div>
            </div>
        `;
    }
    
    function createStatusElement() {
        const element = document.createElement('div');
        element.id = 'selectionStatus';
        element.className = 'alert alert-info mt-3';
        document.querySelector('.modern-card .card-body').appendChild(element);
        return element;
    }
    
    function changeChartView(type) {
        // Update chart based on type
        const months = @json($monthsData);
        
        switch(type) {
            case 'cv':
                window.performanceChart.updateSeries([{
                    name: 'CV Submitted',
                    data: months.map(m => m.cvs)
                }]);
                break;
            case 'selection':
                window.performanceChart.updateSeries([{
                    name: 'Selections',
                    data: months.map(m => m.selections)
                }]);
                break;
            case 'revenue':
                // You'll need to add revenue data to your model
                window.performanceChart.updateSeries([{
                    name: 'Revenue',
                    data: months.map(() => Math.random() * 100)
                }]);
                break;
        }
    }
    
    function exportQuarterData() {
        window.location.href = `/admin/export/quarterly?year={{ $selectedYear }}&hr_staff={{ $hrStaff }}`;
    }
    
    function generateYearlyReport() {
        showNotification('Generating annual report...', 'info');
        window.location.href = `/admin/report/annual?year={{ $selectedYear }}&hr_staff={{ $hrStaff }}`;
    }
    
    function showForecast() {
        const modalBody = document.querySelector('#forecastModal .modal-body');
        modalBody.innerHTML = `
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="card border-primary">
                        <div class="card-body">
                            <h6 class="text-primary mb-3">Next Month Forecast</h6>
                            <div class="text-center">
                                <div class="display-4 text-primary mb-2">
                                    {{ round(($ytdData['totals']['cv_submitted'] ?? 0) / max($ytdData['months_count'], 1) * 1.1) }}
                                </div>
                                <small class="text-muted">Expected CVs (10% growth)</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card border-success">
                        <div class="card-body">
                            <h6 class="text-success mb-3">Expected Selections</h6>
                            <div class="text-center">
                                <div class="display-4 text-success mb-2">
                                    {{ round(($ytdData['totals']['selected'] ?? 0) / max($ytdData['months_count'], 1) * 1.05) }}
                                </div>
                                <small class="text-muted">Based on trends (5% growth)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <h6>Recommendations for Improvement:</h6>
                <ul>
                    @foreach($insights['recommendations'] ?? [] as $recommendation)
                    <li>{{ $recommendation }}</li>
                    @endforeach
                    <li>Focus on high-conversion client segments</li>
                    <li>Increase follow-up frequency by 25%</li>
                    <li>Implement targeted skill training sessions</li>
                    <li>Optimize telemarketing scripts for better conversion</li>
                </ul>
            </div>
        `;
        
        const modal = new bootstrap.Modal(document.getElementById('forecastModal'));
        modal.show();
    }
    
    function compareWithLastYear() {
        showNotification('Loading previous year data for comparison...', 'info');
        window.location.href = '?year={{ $selectedYear - 1 }}&hr_staff={{ $hrStaff }}';
    }
    
    function printDashboard() {
        window.print();
    }
    
    function showNotification(message, type = 'info') {
        // Use Toastr or similar for notifications
        if (typeof toastr !== 'undefined') {
            toastr[type](message);
        } else {
            alert(message);
        }
    }
</script>
@endsection