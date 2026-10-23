@extends('layouts.app')

@section('title', 'Recruiter Daily Progress')
@section('content')
<style>
    .highlight-row { background-color: #e8f4fd !important; }
    .today-row { background-color: #d1ecf1 !important; font-weight: bold; }
    .past-row { background-color: #f8f9fa !important; }
    .future-row { background-color: #e9ecef !important; }
    .weekend-row { background-color: #f8d7da !important; }
    .input-highlight:focus { 
        background-color: #fff3cd; 
        border-color: #ffc107;
    }
    .progress-bar-custom { height: 20px; border-radius: 3px; }
    .excel-table thead th {
        background-color: #0a0a0b !important;
        color: white !important;
    }
    /*.readonly-input {
        background-color: transparent !important;
        border: none !important;
        box-shadow: none !important;
    }*/
    .calc-btn {
        position: fixed;
        bottom: 20px;
        right: 20px;
        /*
        width: 60px;
        height: 60px;
        border-radius: 50%;*/
        z-index: 1000;
    }
</style>
<div class="content-wrapper">
    <!-- Info Alert -->
    <div class="alert alert-info alert-dismissible fade show mb-3" role="alert">
        <i class="bi bi-info-circle me-2"></i>
        <strong>Note:</strong> You can only enter data for today's date (Day {{ $currentDay }}). Past and future dates are view-only.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>

    <!-- Page Header -->
    <div class="header-section shadow-sm mb-4">
        <div class="row">
            <div class="col-md-8">
                <h4 class="text-primary mb-2">
                    <i class="bi bi-calendar-day me-2"></i>
                    <strong>Recruiter's Daily Progress History Sheet</strong>
                </h4>
                <div class="d-flex flex-wrap gap-3">
                    <div class="bg-light p-2 rounded">
                        <strong>Month:</strong> 
                        <span class="text-primary fw-bold">{{ $selectedMonth }}</span>
                    </div>
                    <div class="bg-light p-2 rounded">
                        <strong>HR Staff:</strong> 
                        <span class="text-success fw-bold">{{ $hrStaff ?? '' }}</span>
                    </div>
                    <div class="bg-light p-2 rounded">
                        <strong>Today:</strong> 
                        <span class="text-danger fw-bold">{{ $currentDate }}</span>
                    </div>
                    <div class="bg-light p-2 rounded">
                        <strong>Editable Day:</strong> 
                        <span class="text-primary fw-bold">{{ $currentDay }}</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-end">
                <div class="btn-group">
                    @if($isCurrentMonth)
                        <button class="btn btn-primary btn-sm" onclick="goToday()">
                            <i class="bi bi-calendar-check"></i> Go to Today (Day {{ $currentDay }})
                        </button>
                    @endif
                    <button class="btn btn-outline-secondary btn-sm" onclick="window.location.href='?month=' + document.querySelector('select[name=\"month\"]').value">
                        <i class="bi bi-arrow-clockwise"></i> Refresh
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Month Selector -->
    <div class="card mb-4 shadow">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <label class="form-label"><strong>Select Month:</strong></label>
                    <select name="month" class="form-select" onchange="window.location.href='?month=' + this.value">
                        @foreach($availableMonths as $month)
                            <option value="{{ $month }}" {{ $month == $selectedMonth ? 'selected' : '' }}>
                                {{ $month }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <div class="alert alert-warning mb-0">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        @if($isCurrentMonth)
                            You are viewing the current month. Day {{ $currentDay }} is editable.
                        @else
                            You are viewing a past month. All days are read-only.
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Section -->
    <div class="card mb-4 shadow">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0">
                <i class="bi bi-bar-chart me-2"></i>SUMMARY OF REPORTS - {{ strtoupper($selectedMonth) }}
            </h6>
            <div>
                <span class="badge bg-light text-dark me-2">Total Days: {{ $daysInMonth }}</span>
                <span class="badge bg-light text-dark">Working Days: {{ floor($daysInMonth * 5/7) }}</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered excel-table mb-0">
                    <thead>
                        <tr>
                            <th colspan="2" class="text-center">Summary of Reports</th>
                            <th class="text-center">Wtg %</th>
                            <th class="text-center">Busi Profile</th>
                            <th class="text-center">Tele marketing</th>
                            <th class="text-center">Client empanelment</th>
                            <th class="text-center">CV submitted</th>
                            <th class="text-center">Interview</th>
                            <th class="text-center">Rejected</th>
                            <th class="text-center">Selected</th>
                            <th class="text-center">Job Reported</th>
                            <th class="text-center">Invoiced</th>
                            <th class="text-center">Payment Recd</th>
                            <th class="text-center">Rec. Overall Prg %</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Wtg % Row -->
                        <tr>
                            <td colspan="2" class="formula-cell">Wtg %</td>
                            <td class="text-center">-</td>
                            <td class="formula-cell text-center" data-wtg="0.02">0.02</td>
                            <td class="formula-cell text-center" data-wtg="0.04">0.04</td>
                            <td class="formula-cell text-center" data-wtg="0.06">0.06</td>
                            <td class="formula-cell text-center" data-wtg="0.15">0.15</td>
                            <td class="formula-cell text-center" data-wtg="0.15">0.15</td>
                            <td class="formula-cell text-center" data-wtg="0">0</td>
                            <td class="formula-cell text-center" data-wtg="0.25">0.25</td>
                            <td class="formula-cell text-center" data-wtg="0.15">0.15</td>
                            <td class="formula-cell text-center" data-wtg="0.12">0.12</td>
                            <td class="formula-cell text-center" data-wtg="0.06">0.06</td>
                            <td id="wtgTotal" class="formula-cell text-center">1.00</td>
                        </tr>
                        
                        <!-- Target Row -->
                        <tr class="summary-row">
                            <td colspan="2" class="text-center">Target</td>
                            <td class="text-center">-</td>
                            <td id="targetBusi" class="formula-cell text-center">520</td>
                            <td id="targetTele" class="formula-cell text-center">260</td>
                            <td id="targetEmpanel" class="formula-cell text-center">10.4</td>
                            <td id="targetCV" class="formula-cell text-center">182</td>
                            <td id="targetInterview" class="formula-cell text-center">45.5</td>
                            <td id="targetRejected" class="formula-cell text-center">0 </td>
                            <td id="targetSelected" class="formula-cell text-center">45.5</td>
                            <td id="targetReported" class="text-center">6</td>
                            <td id="targetInvoiced" class="formula-cell text-center">6</td>
                            <td id="targetPayment" class="formula-cell text-center">3</td>
                            <td id="targetTotal" class="formula-cell text-center">1078.4</td>
                        </tr>
                        
                        <!-- Achieved Row -->
                        <tr>
                            <td colspan="2" class="text-center">Achieved till date</td>
                            <td class="text-center">-</td>
                            <td id="achievedBusi" class="text-center">0</td>
                            <td id="achievedTele" class="text-center">0</td>
                            <td id="achievedEmpanel" class="text-center">0</td>
                            <td id="achievedCV" class="text-center">0</td>
                            <td id="achievedInterview" class="text-center">0</td>
                            <td id="achievedRejected" class="text-center">0</td>
                            <td id="achievedSelected" class="text-center">0</td>
                            <td id="achievedReported" class="text-center">0</td>
                            <td id="achievedInvoiced" class="text-center">0</td>
                            <td id="achievedPayment" class="text-center">0</td>
                            <td id="achievedTotal" class="text-center">0</td>
                        </tr>
                        
                        <!-- % Unit Progress -->
                        <tr>
                            <td colspan="2" class="text-center">% Unit Progress</td>
                            <td class="text-center">-</td>
                            <td id="unitBusi" class="text-center">0%</td>
                            <td id="unitTele" class="text-center">0%</td>
                            <td id="unitEmpanel" class="text-center">0%</td>
                            <td id="unitCV" class="text-center">0%</td>
                            <td id="unitInterview" class="text-center">0%</td>
                            <td id="unitRejected" class="text-center">0%</td>
                            <td id="unitSelected" class="text-center">0%</td>
                            <td id="unitReported" class="text-center">0%</td>
                            <td id="unitInvoiced" class="text-center">0%</td>
                            <td id="unitPayment" class="text-center">0%</td>
                            <td class="text-center">-</td>
                        </tr>
                        
                        <!-- % Overall Progress -->
                        <tr class="summary-row">
                            <td colspan="2" class="text-center">% Overall Progress</td>
                            <td class="text-center">-</td>
                            <td id="overallBusi" class="text-center">0%</td>
                            <td id="overallTele" class="text-center">0%</td>
                            <td id="overallEmpanel" class="text-center">0%</td>
                            <td id="overallCV" class="text-center">0%</td>
                            <td id="overallInterview" class="text-center">0%</td>
                            <td id="overallRejected" class="text-center">0%</td>
                            <td id="overallSelected" class="text-center">0%</td>
                            <td id="overallReported" class="text-center">0%</td>
                            <td id="overallInvoiced" class="text-center">0%</td>
                            <td id="overallPayment" class="text-center">0%</td>
                            <td id="overallTotal" class="text-center">0%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Daily Entry Table -->
    <div class="card shadow">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0">
                <i class="bi bi-calendar-date me-2"></i>
                DAILY PROGRESS ENTRIES - Month: {{ $selectedMonth }}
            </h6>
            <div>
                @if($isCurrentMonth)
                    <button class="btn btn-sm btn-warning me-2" onclick="clearTodayEntry()">
                        <i class="bi bi-trash"></i> Clear Today
                    </button>
                @endif
                <button class="btn btn-sm btn-light" onclick="calculateAll()">
                    <i class="bi bi-calculator"></i> Calculate
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered excel-table mb-0" id="dailyTable">
                    <thead class="table-dark">
                        <tr>
                            <th width="40">Sl.No</th>
                            <th width="150">Client Name</th>
                            <th width="80">Date / day</th>
                            <th width="80">Busi Profile</th>
                            <th width="80">Tele marketing</th>
                            <th width="80">Client empanelment</th>
                            <th width="80">CV submitted</th>
                            <th width="80">Interview</th>
                            <th width="80">Rejected</th>
                            <th width="80">Selected</th>
                            <th width="80">Job Reported</th>
                            <th width="80">Invoiced</th>
                            <th width="80">Payment Recd</th>
                            <th width="70">% selection</th>
                            <th width="70">% rejection</th>
                            <th width="150">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for($i = 1; $i <= $daysInMonth; $i++)
                            @php
                                // Determine if this row should be editable
                                $isEditable = false;
                                $isToday = false;
                                $rowClass = '';
                                $bgColor = '';
                                
                                // Check if this is today in the current month
                                if($isCurrentMonth && $i == $currentDay) {
                                    $isEditable = true;
                                    $isToday = true;
                                    $rowClass = 'today-row';
                                } 
                                // Check if this is a past day in current month
                                elseif($isCurrentMonth && $i < $currentDay) {
                                    $rowClass = 'past-row';
                                }
                                // Check if this is a future day or different month
                                else {
                                    $rowClass = 'future-row';
                                }
                                
                                // Get saved data for this day
                                $dayData = $savedData[$i] ?? null;
                            @endphp
                            
                            <tr data-day="{{ $i }}" class="daily-row {{ $rowClass }}">
                                <td class="text-center">{{ $i }}</td>
                                <td>
                                    @if($isEditable)
                                        <input type="text" class="form-control form-control-sm input-sm client-name input-highlight" 
                                            name="client[]" placeholder="Client name" 
                                            value="{{ $dayData->client_name ?? '' }}"
                                            id="client_{{ $i }}">
                                    @else
                                        <input type="text" class="form-control form-control-sm input-sm readonly-input" 
                                            value="{{ $dayData->client_name ?? '' }}" 
                                            >
                                    @endif
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm input-sm date-day" 
                                        name="date[]" value="{{ $i }}" readonly>
                                </td>
                                
                                <!-- Data Entry Cells -->
                                <!-- Busi Profile -->
                                <td>
                                    @if($isEditable)
                                        <input type="number" class="form-control form-control-sm input-sm busi-entry input-highlight" 
                                            name="busi[]" min="0" 
                                            value="{{ $dayData->busi_profile ?? 0 }}" 
                                            data-col="busi" step="1"
                                            id="busi_{{ $i }}">
                                    @else
                                        <input type="number" class="form-control form-control-sm input-sm readonly-input" 
                                            value="{{ $dayData->busi_profile ?? 0 }}" 
                                            >
                                    @endif
                                </td>
                                
                                <!-- Tele Marketing -->
                                <td>
                                    @if($isEditable)
                                        <input type="number" class="form-control form-control-sm input-sm tele-entry input-highlight" 
                                            name="tele[]" min="0" 
                                            value="{{ $dayData->tele_marketing ?? 0 }}" 
                                            data-col="tele" step="1"
                                            id="tele_{{ $i }}">
                                    @else
                                        <input type="number" class="form-control form-control-sm input-sm readonly-input" 
                                            value="{{ $dayData->tele_marketing ?? 0 }}" 
                                            >
                                    @endif
                                </td>
                                
                                <!-- Client Empanelment -->
                                <td>
                                    @if($isEditable)
                                        <input type="number" class="form-control form-control-sm input-sm empanel-entry input-highlight" 
                                            name="empanel[]" min="0" 
                                            value="{{ $dayData->client_empanelment ?? 0 }}" 
                                            data-col="empanel" step="0.1"
                                            id="empanel_{{ $i }}">
                                    @else
                                        <input type="number" class="form-control form-control-sm input-sm readonly-input" 
                                            value="{{ $dayData->client_empanelment ?? 0 }}" 
                                            >
                                    @endif
                                </td>
                                
                                <!-- CV Submitted -->
                                <td>
                                    @if($isEditable)
                                        <input type="number" class="form-control form-control-sm input-sm cv-entry input-highlight" 
                                            name="cv[]" min="0" 
                                            value="{{ $dayData->cv_submitted ?? 0 }}" 
                                            data-col="cv" step="1"
                                            id="cv_{{ $i }}">
                                    @else
                                        <input type="number" class="form-control form-control-sm input-sm readonly-input" 
                                            value="{{ $dayData->cv_submitted ?? 0 }}" 
                                            >
                                    @endif
                                </td>
                                
                                <!-- Interview -->
                                <td>
                                    @if($isEditable)
                                        <input type="number" class="form-control form-control-sm input-sm interview-entry input-highlight" 
                                            name="interview[]" min="0" 
                                            value="{{ $dayData->interview ?? 0 }}" 
                                            data-col="interview" step="1"
                                            id="interview_{{ $i }}">
                                    @else
                                        <input type="number" class="form-control form-control-sm input-sm readonly-input" 
                                            value="{{ $dayData->interview ?? 0 }}" 
                                            >
                                    @endif
                                </td>
                                
                                <!-- Rejected -->
                                <td>
                                    @if($isEditable)
                                        <input type="number" class="form-control form-control-sm input-sm rejected-entry input-highlight" 
                                            name="rejected[]" min="0" 
                                            value="{{ $dayData->rejected ?? 0 }}" 
                                            data-col="rejected" step="1"
                                            id="rejected_{{ $i }}">
                                    @else
                                        <input type="number" class="form-control form-control-sm input-sm readonly-input" 
                                            value="{{ $dayData->rejected ?? 0 }}" 
                                            >
                                    @endif
                                </td>
                                
                                <!-- Selected -->
                                <td>
                                    @if($isEditable)
                                        <input type="number" class="form-control form-control-sm input-sm selected-entry input-highlight" 
                                            name="selected[]" min="0" 
                                            value="{{ $dayData->selected ?? 0 }}" 
                                            data-col="selected" step="1"
                                            id="selected_{{ $i }}">
                                    @else
                                        <input type="number" class="form-control form-control-sm input-sm readonly-input" 
                                            value="{{ $dayData->selected ?? 0 }}" 
                                            >
                                    @endif
                                </td>
                                
                                <!-- Job Reported -->
                                <td>
                                    @if($isEditable)
                                        <input type="number" class="form-control form-control-sm input-sm reported-entry input-highlight" 
                                            name="reported[]" min="0" 
                                            value="{{ $dayData->job_reported ?? 0 }}" 
                                            data-col="reported" step="1"
                                            id="reported_{{ $i }}">
                                    @else
                                        <input type="number" class="form-control form-control-sm input-sm readonly-input" 
                                            value="{{ $dayData->job_reported ?? 0 }}" 
                                            >
                                    @endif
                                </td>
                                
                                <!-- Invoiced -->
                                <td>
                                    @if($isEditable)
                                        <input type="number" class="form-control form-control-sm input-sm invoiced-entry input-highlight" 
                                            name="invoiced[]" min="0" 
                                            value="{{ $dayData->invoiced ?? 0 }}" 
                                            data-col="invoiced" step="1"
                                            id="invoiced_{{ $i }}">
                                    @else
                                        <input type="number" class="form-control form-control-sm input-sm readonly-input" 
                                            value="{{ $dayData->invoiced ?? 0 }}" 
                                            >
                                    @endif
                                </td>
                                
                                <!-- Payment Received -->
                                <td>
                                    @if($isEditable)
                                        <input type="number" class="form-control form-control-sm input-sm payment-entry input-highlight" 
                                            name="payment[]" min="0" 
                                            value="{{ $dayData->payment_received ?? 0 }}" 
                                            data-col="payment" step="1"
                                            id="payment_{{ $i }}">
                                    @else
                                        <input type="number" class="form-control form-control-sm input-sm readonly-input" 
                                            value="{{ $dayData->payment_received ?? 0 }}" 
                                            >
                                    @endif
                                </td>
                                
                                <!-- Percentage Cells -->
                                <td class="text-center percent-cell percent-select" id="selectPercent{{ $i }}">
                                    @php
                                        $interviewVal = $dayData->interview ?? 0;
                                        $selectedVal = $dayData->selected ?? 0;
                                        $selectPercent = ($interviewVal > 0) ? ($selectedVal / $interviewVal * 100) : 0;
                                    @endphp
                                    <span class="badge {{ $selectPercent >= 50 ? 'bg-success' : 'bg-info' }}">
                                        {{ number_format($selectPercent, 1) }}%
                                    </span>
                                </td>
                                <td class="text-center percent-cell percent-reject" id="rejectPercent{{ $i }}">
                                    @php
                                        $interviewVal = $dayData->interview ?? 0;
                                        $rejectedVal = $dayData->rejected ?? 0;
                                        $rejectPercent = ($interviewVal > 0) ? ($rejectedVal / $interviewVal * 100) : 0;
                                    @endphp
                                    <span class="badge {{ $rejectPercent >= 50 ? 'bg-danger' : 'bg-warning' }}">
                                        {{ number_format($rejectPercent, 1) }}%
                                    </span>
                                </td>
                                
                                <!-- Remarks -->
                                <td>
                                    @if($isEditable)
                                        <input type="text" class="form-control form-control-sm input-sm" 
                                            name="remarks[]" placeholder="Remarks"
                                            value="{{ $dayData->remarks ?? '' }}"
                                            id="remarks_{{ $i }}">
                                    @else
                                        <input type="text" class="form-control form-control-sm input-sm readonly-input" 
                                            value="{{ $dayData->remarks ?? '' }}" 
                                            >
                                    @endif
                                </td>
                            </tr>
                        @endfor
                        
                        <!-- Total Row -->
                        <tr class="total-row">
                            <td colspan="3" class="text-end"><strong>Monthly Total</strong></td>
                            <td id="totalBusi" class="text-center">0</td>
                            <td id="totalTele" class="text-center">0</td>
                            <td id="totalEmpanel" class="text-center">0</td>
                            <td id="totalCV" class="text-center">0</td>
                            <td id="totalInterview" class="text-center">0</td>
                            <td id="totalRejected" class="text-center">0</td>
                            <td id="totalSelected" class="text-center">0</td>
                            <td id="totalReported" class="text-center">0</td>
                            <td id="totalInvoiced" class="text-center">0</td>
                            <td id="totalPayment" class="text-center">0</td>
                            <td colspan="3" class="text-center">
                                <button class="btn btn-sm btn-outline-primary" onclick="calculateAll()">
                                    <i class="bi bi-arrow-clockwise"></i> Update Totals
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-4 text-center">
        @if($isCurrentMonth)
        <form id="saveForm" action="{{ route('recruiter.daily.save') }}" method="POST">
            @csrf
            <input type="hidden" name="month" value="{{ $selectedMonth }}">
            <input type="hidden" name="data" id="formData">
            
            <div class="btn-group" role="group">
                <button type="button" class="btn btn-lg btn-primary me-2" onclick="calculateAll()">
                    <i class="icon-calculator me-1"></i> Calculate All
                </button>
                <button type="button" class="btn btn-lg btn-success me-2" onclick="saveData()">
                    <i class="icon-save me-1"></i> Save Today's Data
                </button>
                <button type="button" class="btn btn-lg btn-warning me-2" onclick="exportToExcel('dailyTable', 'Daily_Progress_{{ $selectedMonth }}')">
                    <i class="icon-file-excel me-1"></i> Export Excel
                </button>
                <button type="button" class="btn btn-lg btn-info" onclick="printReport()">
                    <i class="icon-printer me-1"></i> Print
                </button>
            </div>
        </form>
        @else
        <div class="alert alert-warning">
            <i class="icon-exclamation-triangle me-2"></i>
            <strong>View Mode:</strong> You are viewing a past month. Data entry is not available. 
            You can only view and export data for {{ $selectedMonth }}.
            <div class="mt-2">
                <button type="button" class="btn btn-lg btn-warning me-2" onclick="exportToExcel('dailyTable', 'Daily_Progress_{{ $selectedMonth }}')">
                    <i class="icon-file-excel me-1"></i> Export Excel
                </button>
                <button type="button" class="btn btn-lg btn-info" onclick="printReport()">
                    <i class="icon-printer me-1"></i> Print
                </button>
            </div>
        </div>
        @endif
    </div>

    <!-- Floating Button -->
    <button class="btn btn-primary shadow-md calc-btn" onclick="calculateAll()" title="Calculate All">
        <i class="icon-calculator fs-2"></i>
    </button>
</div>
@endsection

@section('js')
<script>
    // Global variables
    const targets = {
        busi: 520,      // 26*20
        tele: 260,      // 10*26
        empanel: 10.4,  // 26*0.4
        cv: 182,        // 26*7
        interview: 45.5, // 182*0.25
        selected: 45.5,  // 182*0.25
        reported: 6,
        invoiced: 6,
        payment: 3
    };

    const wtgPercent = {
        busi: 0.02,
        tele: 0.04,
        empanel: 0.06,
        cv: 0.15,
        interview: 0.15,
        rejected: 0,
        selected: 0.25,
        reported: 0.15,
        invoiced: 0.12,
        payment: 0.06
    };

    // Main calculation function
    function calculateAll() {
        console.log("Calculating all data...");
        
        let totals = {
            busi: 0, tele: 0, empanel: 0, cv: 0,
            interview: 0, rejected: 0, selected: 0,
            reported: 0, invoiced: 0, payment: 0
        };

        // Calculate row by row
        const totalRows = {{ $daysInMonth }};
        for(let i = 1; i <= totalRows; i++) {
            const row = document.querySelector(`tr[data-day="${i}"]`);
            if(row) {
                // Get input values
                const interview = parseFloat(document.getElementById(`interview_${i}`)?.value) || 0;
                const selected = parseFloat(document.getElementById(`selected_${i}`)?.value) || 0;
                const rejected = parseFloat(document.getElementById(`rejected_${i}`)?.value) || 0;
                
                // Calculate percentages for this row
                let selectPercent = 0;
                let rejectPercent = 0;
                
                if(interview > 0) {
                    selectPercent = (selected / interview * 100).toFixed(1);
                    rejectPercent = (rejected / interview * 100).toFixed(1);
                }
                
                // Update percentage cells with badges
                const selectCell = document.getElementById(`selectPercent${i}`);
                const rejectCell = document.getElementById(`rejectPercent${i}`);
                
                if(selectCell) {
                    selectCell.innerHTML = `<span class="badge ${selectPercent >= 50 ? 'bg-success' : 'bg-info'}">${selectPercent}%</span>`;
                }
                if(rejectCell) {
                    rejectCell.innerHTML = `<span class="badge ${rejectPercent >= 50 ? 'bg-danger' : 'bg-warning'}">${rejectPercent}%</span>`;
                }
                
                // Add to totals
                totals.busi += parseFloat(document.getElementById(`busi_${i}`)?.value) || 0;
                totals.tele += parseFloat(document.getElementById(`tele_${i}`)?.value) || 0;
                totals.empanel += parseFloat(document.getElementById(`empanel_${i}`)?.value) || 0;
                totals.cv += parseFloat(document.getElementById(`cv_${i}`)?.value) || 0;
                totals.interview += interview;
                totals.rejected += rejected;
                totals.selected += selected;
                totals.reported += parseFloat(document.getElementById(`reported_${i}`)?.value) || 0;
                totals.invoiced += parseFloat(document.getElementById(`invoiced_${i}`)?.value) || 0;
                totals.payment += parseFloat(document.getElementById(`payment_${i}`)?.value) || 0;
            }
        }

        // Update total row
        updateTotalRow(totals);
        
        // Update summary section
        updateSummarySection(totals);
        
        // Show notification
        showNotification('Calculation completed!', 'success');
    }

    function updateTotalRow(totals) {
        document.getElementById('totalBusi').innerText = totals.busi;
        document.getElementById('totalTele').innerText = totals.tele;
        document.getElementById('totalEmpanel').innerText = totals.empanel.toFixed(1);
        document.getElementById('totalCV').innerText = totals.cv;
        document.getElementById('totalInterview').innerText = totals.interview;
        document.getElementById('totalRejected').innerText = totals.rejected;
        document.getElementById('totalSelected').innerText = totals.selected;
        document.getElementById('totalReported').innerText = totals.reported;
        document.getElementById('totalInvoiced').innerText = totals.invoiced;
        document.getElementById('totalPayment').innerText = totals.payment;
    }

    function updateSummarySection(totals) {
        // Update Achieved values
        document.getElementById('achievedBusi').innerText = totals.busi;
        document.getElementById('achievedTele').innerText = totals.tele;
        document.getElementById('achievedEmpanel').innerText = totals.empanel.toFixed(1);
        document.getElementById('achievedCV').innerText = totals.cv;
        document.getElementById('achievedInterview').innerText = totals.interview.toFixed(1);
        document.getElementById('achievedRejected').innerText = totals.rejected;
        document.getElementById('achievedSelected').innerText = totals.selected;
        document.getElementById('achievedReported').innerText = totals.reported;
        document.getElementById('achievedInvoiced').innerText = totals.invoiced;
        document.getElementById('achievedPayment').innerText = totals.payment;
        
        // Calculate and update % Unit Progress
        updateUnitProgress(totals);
        
        // Calculate and update % Overall Progress
        updateOverallProgress(totals);
        
        // Update achieved total
        const achievedTotal = Object.values(totals).reduce((a, b) => a + b, 0);
        document.getElementById('achievedTotal').innerText = achievedTotal.toFixed(1);
    }

    function updateUnitProgress(totals) {
        const cols = ['busi', 'tele', 'empanel', 'cv', 'interview', 'rejected', 'selected', 'reported', 'invoiced', 'payment'];
        
        cols.forEach(col => {
            const target = targets[col] || 1;
            const achieved = totals[col] || 0;
            let unitProgress = 0;
            
            if (target > 0) {
                unitProgress = (achieved / target * 100);
            }
            
            const element = document.getElementById(`unit${col.charAt(0).toUpperCase() + col.slice(1)}`);
            if(element) {
                element.innerText = unitProgress.toFixed(1) + '%';
                
                // Color code based on percentage
                if (unitProgress >= 100) {
                    element.className = 'text-center text-success fw-bold';
                } else if (unitProgress >= 75) {
                    element.className = 'text-center text-primary';
                } else if (unitProgress >= 50) {
                    element.className = 'text-center text-warning';
                } else {
                    element.className = 'text-center text-danger';
                }
            }
        });
    }

    function updateOverallProgress(totals) {
        const cols = ['busi', 'tele', 'empanel', 'cv', 'interview', 'rejected', 'selected', 'reported', 'invoiced', 'payment'];
        let overallTotal = 0;
        
        cols.forEach(col => {
            const target = targets[col] || 1;
            const achieved = totals[col] || 0;
            const wtg = wtgPercent[col] || 0;
            const unitProgress = target > 0 ? (achieved / target) : 0;
            const overallProgress = (unitProgress * wtg * 100);
            
            const element = document.getElementById(`overall${col.charAt(0).toUpperCase() + col.slice(1)}`);
            if(element) {
                element.innerText = overallProgress.toFixed(1) + '%';
                
                // Color code
                if (overallProgress >= 100) {
                    element.className = 'text-center text-success fw-bold';
                } else if (overallProgress >= 75) {
                    element.className = 'text-center text-primary';
                } else if (overallProgress >= 50) {
                    element.className = 'text-center text-warning';
                } else {
                    element.className = 'text-center text-danger';
                }
                
                if(!isNaN(overallProgress)) {
                    overallTotal += overallProgress;
                }
            }
        });
        
        const totalElement = document.getElementById('overallTotal');
        if(totalElement) {
            totalElement.innerText = overallTotal.toFixed(1) + '%';
            
            // Color code total
            if (overallTotal >= 100) {
                totalElement.className = 'text-center text-success fw-bold';
            } else if (overallTotal >= 75) {
                totalElement.className = 'text-center text-primary fw-bold';
            } else if (overallTotal >= 50) {
                totalElement.className = 'text-center text-warning fw-bold';
            } else {
                totalElement.className = 'text-center text-danger fw-bold';
            }
        }
    }

    function saveData() {
        const today = {{ $currentDay }};
        const row = document.querySelector(`tr[data-day="${today}"]`);
        
        if(!row || !row.classList.contains('today-row')) {
            showNotification('You can only save data for today!', 'warning');
            return;
        }
        
        // Collect only today's data
        const data = {
            month: document.querySelector('input[name="month"]').value,
            day: today,
            hr_staff: '{{ $hrStaff }}',
            entries: [{
                day: today,
                client: document.getElementById(`client_${today}`)?.value || '',
                busi: document.getElementById(`busi_${today}`)?.value || 0,
                tele: document.getElementById(`tele_${today}`)?.value || 0,
                empanel: document.getElementById(`empanel_${today}`)?.value || 0,
                cv: document.getElementById(`cv_${today}`)?.value || 0,
                interview: document.getElementById(`interview_${today}`)?.value || 0,
                rejected: document.getElementById(`rejected_${today}`)?.value || 0,
                selected: document.getElementById(`selected_${today}`)?.value || 0,
                reported: document.getElementById(`reported_${today}`)?.value || 0,
                invoiced: document.getElementById(`invoiced_${today}`)?.value || 0,
                payment: document.getElementById(`payment_${today}`)?.value || 0,
                remarks: document.getElementById(`remarks_${today}`)?.value || ''
            }]
        };
        
        // Set form data and submit
        document.getElementById('formData').value = JSON.stringify(data);
        document.getElementById('saveForm').submit();
        
        showNotification('Today\'s data saved successfully!', 'success');
    }

    function clearTodayEntry() {
        const today = {{ $currentDay }};
        const row = document.querySelector(`tr[data-day="${today}"]`);
        
        if(row && row.classList.contains('today-row')) {
            if(confirm('Are you sure you want to clear today\'s entry?')) {
                document.getElementById(`client_${today}`).value = '';
                document.getElementById(`busi_${today}`).value = 0;
                document.getElementById(`tele_${today}`).value = 0;
                document.getElementById(`empanel_${today}`).value = 0;
                document.getElementById(`cv_${today}`).value = 0;
                document.getElementById(`interview_${today}`).value = 0;
                document.getElementById(`rejected_${today}`).value = 0;
                document.getElementById(`selected_${today}`).value = 0;
                document.getElementById(`reported_${today}`).value = 0;
                document.getElementById(`invoiced_${today}`).value = 0;
                document.getElementById(`payment_${today}`).value = 0;
                document.getElementById(`remarks_${today}`).value = '';
                
                // Update percentages
                updatePercentages(today);
                // Recalculate totals
                calculateAll();
                
                showNotification('Today\'s entry cleared!', 'warning');
            }
        } else {
            showNotification('You can only clear today\'s entry!', 'warning');
        }
    }

    function clearAllEntries() {
        showNotification('You can only clear today\'s entry. Use "Clear Today" button.', 'warning');
    }

    function updatePercentages(day) {
        const interview = parseFloat(document.getElementById(`interview_${day}`)?.value) || 0;
        const selected = parseFloat(document.getElementById(`selected_${day}`)?.value) || 0;
        const rejected = parseFloat(document.getElementById(`rejected_${day}`)?.value) || 0;
        
        let selectPercent = 0;
        let rejectPercent = 0;
        
        if(interview > 0) {
            selectPercent = (selected / interview * 100).toFixed(1);
            rejectPercent = (rejected / interview * 100).toFixed(1);
        }
        
        const selectCell = document.getElementById(`selectPercent${day}`);
        const rejectCell = document.getElementById(`rejectPercent${day}`);
        
        if(selectCell) {
            selectCell.innerHTML = `<span class="badge ${selectPercent >= 50 ? 'bg-success' : 'bg-info'}">${selectPercent}%</span>`;
        }
        if(rejectCell) {
            rejectCell.innerHTML = `<span class="badge ${rejectPercent >= 50 ? 'bg-danger' : 'bg-warning'}">${rejectPercent}%</span>`;
        }
    }

    function goToday() {
        const today = {{ $currentDay }};
        const element = document.querySelector(`tr[data-day="${today}"]`);
        if(element) {
            element.scrollIntoView({ behavior: 'smooth', block: 'center' });
            element.classList.add('table-active');
            setTimeout(() => element.classList.remove('table-active'), 2000);
            showNotification(`Scrolled to today (Day ${today})`, 'info');
        }
    }

    function prevMonth() {
        showNotification('Loading previous month...', 'info');
        // Add your month navigation logic here
    }

    function nextMonth() {
        showNotification('Loading next month...', 'info');
        // Add your month navigation logic here
    }

    function exportToExcel(tableId, filename) {
        const table = document.getElementById(tableId);
        const rows = table.querySelectorAll('tr');
        
        let csv = [];
        for (let i = 0; i < rows.length; i++) {
            const row = [], cols = rows[i].querySelectorAll('td, th');
            for (let j = 0; j < cols.length; j++) {
                let data = '';
                const cell = cols[j];
                
                // Check if cell has input field
                const input = cell.querySelector('input');
                const select = cell.querySelector('select');
                const textarea = cell.querySelector('textarea');
                const badge = cell.querySelector('.badge');
                
                if (input) {
                    // Get value from input field
                    data = input.value || input.getAttribute('value') || '';
                } else if (select) {
                    // Get selected value from select
                    data = select.value || '';
                } else if (textarea) {
                    // Get value from textarea
                    data = textarea.value || '';
                } else if (badge) {
                    // Get text from badge element (for percentages)
                    data = badge.innerText || badge.textContent || '';
                } else {
                    // Get regular cell text
                    data = cell.innerText || cell.textContent || '';
                }
                
                // Clean the data
                data = data.toString().replace(/(\r\n|\n|\r)/gm, '').replace(/(\s\s)/gm, ' ');
                data = data.replace(/"/g, '""');
                row.push('"' + data + '"');
            }
            csv.push(row.join(','));
        }
        
        const csvString = csv.join('\n');
        const blob = new Blob([csvString], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        
        if (navigator.msSaveBlob) { // IE 10+
            navigator.msSaveBlob(blob, filename + '.csv');
        } else {
            link.href = URL.createObjectURL(blob);
            link.download = filename + '.csv';
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
        
        showNotification('Data exported to CSV file!', 'success');
    }

    function printReport() {
        window.print();
    }

    function showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 1050; min-width: 300px;';
        notification.innerHTML = `
            <strong>${type.charAt(0).toUpperCase() + type.slice(1)}!</strong> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 3000);
    }

    // Auto-calculate when inputs change (only for today's inputs)
    document.addEventListener('DOMContentLoaded', function() {
        const today = {{ $currentDay }};
        
        // Add input event listeners to today's row inputs
        const inputs = [
            `interview_${today}`, `selected_${today}`, `rejected_${today}`,
            `busi_${today}`, `tele_${today}`, `empanel_${today}`, 
            `cv_${today}`, `reported_${today}`, `invoiced_${today}`, 
            `payment_${today}`
        ];
        
        inputs.forEach(inputId => {
            const input = document.getElementById(inputId);
            if(input) {
                input.addEventListener('input', function() {
                    updatePercentages(today);
                    calculateAll();
                });
            }
        });
        
        // Initial calculation
        setTimeout(() => calculateAll(), 500);
    });
</script>
@endsection