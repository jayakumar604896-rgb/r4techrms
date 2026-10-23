@extends('layouts.app')

@section('title', 'Candidate Details')

@section('content')

<style>
    /* ---------------------------
       Global Professional Styling
    ----------------------------*/
    :root {
        --primary-color: #4361ee;
        --primary-light: #eef2ff;
        --secondary-color: #3a0ca3;
        --accent-color: #7209b7;
        --text-dark: #2b2d42;
        --text-light: #8d99ae;
        --border-color: #e9ecef;
        --success-color: #06d6a0;
        --warning-color: #ffd166;
        --danger-color: #ef476f;
        --info-color: #118ab2;
        --card-bg: #ffffff;
    }

    .page-header h3 {
        font-weight: 700;
        font-size: 28px;
        background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 0;
    }

    .breadcrumb-item a {
        color: var(--primary-color) !important;
        font-weight: 500;
        transition: all 0.3s;
    }

    .breadcrumb-item a:hover {
        color: var(--accent-color) !important;
        transform: translateX(3px);
    }

    /* ---------------------------
       Candidate Header
    ----------------------------*/
    .candidate-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .candidate-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 200px;
        height: 200px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
        transform: translate(80px, -80px);
    }

    .candidate-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 150px;
        height: 150px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
        transform: translate(-60px, 60px);
    }

    .candidate-avatar {
        width: 120px;
        height: 120px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
        font-weight: bold;
        margin-right: 25px;
        border: 4px solid rgba(255,255,255,0.3);
        backdrop-filter: blur(10px);
    }

    .candidate-info h2 {
        font-size: 32px;
        font-weight: 800;
        margin-bottom: 5px;
    }

    .candidate-info .badge {
        background: rgba(255,255,255,0.2);
        color: white;
        padding: 8px 20px;
        border-radius: 5px;
        font-size: 14px;
        font-weight: 600;
        backdrop-filter: blur(10px);
    }

    /* ---------------------------
       Status Badges
    ----------------------------*/
    .status-badge {
        padding: 8px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        display: inline-block;
        min-width: 120px;
        text-align: center;
    }
    
    .status-inprocess { 
        background: linear-gradient(135deg, #e3f2fd, #bbdefb); 
        color: #1565c0;
        border: 1px solid #90caf9;
    }
    
    .status-waittinglist
     { 
        background: linear-gradient(135deg, #fff3e0, #ffe0b2); 
        color: #ef6c00;
        border: 1px solid #ffcc80;
    }
    .status-backout
     { 
        background: linear-gradient(135deg, #fff3e0, #ffe0b2); 
        color: #ef6c00;
        border: 1px solid #ffcc80;
    }
    
    .status-rejected { 
        background: linear-gradient(135deg, #ffebee, #ffcdd2); 
        color: #c62828;
        border: 1px solid #ef9a9a;
    }
    
    .status-selected { 
        background: linear-gradient(135deg, #e8f5e9, #c8e6c9); 
        color: #2e7d32;
        border: 1px solid #a5d6a7;
    }
    
    .status-active { 
        background: linear-gradient(135deg, #e8f5e9, #c8e6c9); 
        color: #2e7d32;
        border: 1px solid #a5d6a7;
    }
    
    .status-inactive { 
        background: linear-gradient(135deg, #f5f5f5, #e0e0e0); 
        color: #616161;
        border: 1px solid #bdbdbd;
    }
    
    .status-disable { 
        background: linear-gradient(135deg, #ffebee, #ffcdd2); 
        color: #c62828;
        border: 1px solid #ef9a9a;
    }

    /* ---------------------------
       Cards Layout
    ----------------------------*/
    .detail-card {
        border-radius: 18px;
        border: none;
        background: var(--card-bg);
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        margin-bottom: 25px;
        transition: transform 0.3s, box-shadow 0.3s;
        overflow: hidden;
    }

    .detail-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 50px rgba(0,0,0,0.12);
    }

    .card-header-custom {
        background: linear-gradient(135deg, var(--primary-light), white);
        border-bottom: 3px solid var(--primary-color);
        padding: 20px 25px;
    }

    .card-header-custom h5 {
        font-weight: 700;
        color: var(--text-dark);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .card-header-custom h5 i {
        color: var(--primary-color);
        font-size: 20px;
    }

    .card-body-custom {
        padding: 25px;
    }

    /* ---------------------------
       Update Status Form
    ----------------------------*/
    .status-form-container {
        background: linear-gradient(135deg, #f8f9ff, #ffffff);
        border-radius: 15px;
        padding: 25px;
        border: 2px solid var(--primary-light);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 8px;
        display: block;
    }

    .form-control-custom {
        border: 2px solid var(--border-color);
        border-radius: 10px;
        padding: 12px 15px;
        font-size: 15px;
        transition: all 0.3s;
        width: 100%;
    }

    .form-control-custom:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        outline: none;
    }

    .form-select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23676e7d' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 16px 12px;
    }

    /* ---------------------------
       Status History Timeline
    ----------------------------*/
    .timeline-history {
        position: relative;
        padding-left: 30px;
    }

    .timeline-history::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, var(--primary-color), var(--accent-color));
    }

    .timeline-item-history {
        position: relative;
        margin-bottom: 25px;
        padding: 20px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        border-left: 4px solid var(--primary-color);
    }

    .timeline-item-history::before {
        content: '';
        position: absolute;
        left: -38px;
        top: 25px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: var(--primary-color);
        border: 3px solid white;
        box-shadow: 0 0 0 3px var(--primary-light);
    }

    .timeline-date {
        font-size: 12px;
        color: var(--text-light);
        font-weight: 600;
        margin-bottom: 5px;
    }

    .timeline-status {
        display: inline-block;
        margin-bottom: 10px;
    }

    .timeline-details {
        font-size: 14px;
        color: var(--text-dark);
        line-height: 1.6;
    }

    .timeline-details strong {
        color: var(--text-dark);
        font-weight: 600;
    }

    /* ---------------------------
       Conditional Fields
    ----------------------------*/
    .conditional-field {
        display: none;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ---------------------------
       Buttons
    ----------------------------*/
    .btn-update {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
        width: 100%;
        justify-content: center;
    }

    .btn-update:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(67, 97, 238, 0.3);
        color: white;
    }

    .btn-edit {
        background: linear-gradient(135deg, #06d6a0, #04a97f);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(6, 214, 160, 0.3);
        color: white;
    }

    .btn-back {
        background: white;
        color: var(--text-dark);
        border: 2px solid var(--border-color);
        padding: 12px 30px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-back:hover {
        border-color: var(--primary-color);
        color: var(--primary-color);
        transform: translateY(-2px);
    }

    .action-buttons {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        padding-top: 20px;
        border-top: 1px solid var(--border-color);
        margin-top: 30px;
    }
/* ---------------------------
       Cards Layout
    ----------------------------*/
    .detail-card {
        border-radius: 18px;
        border: none;
        background: var(--card-bg);
        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
        margin-bottom: 25px;
        transition: transform 0.3s, box-shadow 0.3s;
        overflow: hidden;
    }

    .detail-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 50px rgba(0,0,0,0.12);
    }

    .card-header-custom {
        background: linear-gradient(135deg, var(--primary-light), white);
        border-bottom: 3px solid var(--primary-color);
        padding: 20px 25px;
    }

    .card-header-custom h5 {
        font-weight: 700;
        color: var(--text-dark);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .card-header-custom h5 i {
        color: var(--primary-color);
        font-size: 20px;
    }

    .card-body-custom {
        padding: 25px;
    }

    /* ---------------------------
       Info Grid
    ----------------------------*/
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        padding: 15px;
        border-radius: 12px;
        background: var(--primary-light);
        transition: all 0.3s;
        border-left: 4px solid var(--primary-color);
    }

    .info-item:hover {
        background: white;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transform: translateX(5px);
    }

    .info-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-light);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 5px;
    }

    .info-value {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-dark);
        word-break: break-word;
    }
    /* ---------------------------
       Resume Card
    ----------------------------*/
    .resume-card {
        background: linear-gradient(135deg, var(--primary-light), white);
        border: 2px dashed var(--primary-color);
        border-radius: 15px;
        padding: 25px;
        text-align: center;
        transition: all 0.3s;
    }

    .resume-card:hover {
        border-color: var(--accent-color);
        transform: scale(1.02);
    }

    .resume-icon {
        font-size: 48px;
        color: var(--primary-color);
        margin-bottom: 15px;
    }
    /* ---------------------------
       Responsive
    ----------------------------*/
    @media (max-width: 768px) {
        .candidate-header {
            text-align: center;
            padding: 25px 20px;
        }
        
        .candidate-avatar {
            margin: 0 auto 20px;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .action-buttons .btn {
            width: 100%;
            justify-content: center;
        }
        
        .timeline-history::before {
            left: 20px;
        }
        
        .timeline-item-history::before {
            left: -30px;
        }
    }
</style>

<div class="content-wrapper">

    <!-- Page Header -->
    <div class="page-header mb-4">
        <h3 class="page-title">Candidate Profile</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 mt-2">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="mdi mdi-home"></i> Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('candidates.index') }}">Candidates</a></li>
                <li class="breadcrumb-item active" aria-current="page">Profile Details</li>
            </ol>
        </nav>
    </div>

    <!-- Candidate Header -->
    <div class="candidate-header d-flex align-items-center" style="position: relative; z-index: 1;">
        <div class="candidate-avatar">
            {{ strtoupper(substr($candidate->candidate_name, 0, 2)) }}
        </div>
        <div class="candidate-info">
            <h2>{{ $candidate->candidate_name }}</h2>
            <div class="d-flex align-items-center gap-3 mt-2">
                <span class="badge">{{ $candidate->emp_code }}</span>
                <span class="badge">{{ $candidate->designation ?? 'N/A' }}</span>
                <span class="badge">{{ $sectores[$candidate->sector] ?? '' }}</span>
                <span class="status-badge status-{{ strtolower(str_replace(' ', '', $candidate->current_status)) }}">
                    {{ $candidate->current_status ?? 'Active' }}
                </span>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Personal Information -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="detail-card">
                <div class="card-header-custom">
                    <h5><i class="mdi mdi-account-circle"></i> Personal Information</h5>
                </div>
                <div class="card-body-custom">
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Employee Code</span>
                            <span class="info-value">{{ $candidate->emp_code }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Date of Birth</span>
                            <span class="info-value">{{ $candidate->dob ? \Carbon\Carbon::parse($candidate->dob)->format('d M, Y') : '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Age</span>
                            <span class="info-value">{{ $candidate->age }} years</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Mobile</span>
                            <span class="info-value">{{ $candidate->mobile }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Email</span>
                            <span class="info-value">{{ $candidate->email ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Passport</span>
                            <span class="info-value">{{ $candidate->passport_details ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Professional Information -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="detail-card">
                <div class="card-header-custom">
                    <h5><i class="mdi mdi-briefcase"></i> Professional Details</h5>
                </div>
                <div class="card-body-custom">
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Designation</span>
                            <span class="info-value">{{ $candidate->designation ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Total Experience</span>
                            <span class="info-value">{{ $candidate->total_exp ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Sector</span>
                            <span class="info-value">{{ $sectores[$candidate->sector] ?? '' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Industry</span>
                            <span class="info-value">{{ $industries[$candidate->industry] ?? '' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Placement Type</span>
                            <span class="info-value">{{ $placementtypes[$candidate->placement_type] ?? '' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Current CTC</span>
                            <span class="info-value">₹ {{ number_format($candidate->ctc) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Employment Details -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="detail-card">
                <div class="card-header-custom">
                    <h5><i class="mdi mdi-office-building"></i> Employment Details</h5>
                </div>
                <div class="card-body-custom">
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Notice Period</span>
                            <span class="info-value">{{ $candidate->notice_period }} days</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Current Stay</span>
                            <span class="info-value">{{ $candidate->current_stay ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Applied Position</span>
                            <span class="info-value">{{ $candidate->post_location_applied ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Company Serial</span>
                            <span class="info-value">{{ $candidate->company_serial_number }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Code No</span>
                            <span class="info-value">{{ $candidate->code_no }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Candidate Type</span>
                            <span class="info-value">
                                <span class="status-badge badge-{{ $candidate->user_type == 'admin' ? 'primary' : 'success' }}">
                                    {{ $candidate->user_type ?? 'Candidate' }}
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Education & Documents -->
        <div class="col-lg-6 mb-4">
            <div class="detail-card h-100">
                <div class="card-header-custom">
                    <h5><i class="mdi mdi-school"></i> Education & Documents</h5>
                </div>
                <div class="card-body-custom">
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Qualification</span>
                            <span class="info-value">{{ $candidate->qualfn ?? '-' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">DOS</span>
                            <span class="info-value">{{ $candidate->DOS ? \Carbon\Carbon::parse($candidate->DOS)->format('d M, Y') : '-' }}</span>
                        </div>
                    </div>
                    
                    @if(!empty($candidate->resume))
                    <div class="resume-card mt-4">
                        <div class="resume-icon">
                            <i class="mdi mdi-file-document"></i>
                        </div>
                        <h5 class="mb-3">Resume Available</h5>
                        <a href="{{ asset('storage/' . $candidate->resume) }}" target="_blank" class="btn btn-edit">
                            <i class="mdi mdi-download"></i> Download Resume
                        </a>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="mdi mdi-file-document-off" style="font-size: 48px; color: #ddd;"></i>
                        <p class="mt-3 text-muted">No resume uploaded</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Timeline & Remarks -->
       <div class="col-lg-6 mb-4">
            <div class="detail-card">
                <div class="card-header-custom">
                    <h5><i class="mdi mdi-update"></i> Update Status</h5>
                </div>
                <div class="card-body-custom">
                    <div class="status-form-container">
                        <form action="{{ route('candidates.updateStatus', $candidate->id) }}" method="POST" id="statusForm">
                            @csrf
                            @method('PUT')
                            
                            <div class="form-group">
                                <label class="form-label">Select Status</label>
                                <select class="form-control-custom form-select" id="statusSelect" name="status" required>
                                    <option value="">-- Select Status --</option>
                                    <!--<option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                    <option value="Disable">Disable</option>-->
                                    <option value="Inprocess">Inprocess</option>
                                    <option value="Waitting List">Waitting List</option>
                                    <option value="Rejects">Rejects</option>
                                    <option value="Selected">Selected</option>
                                    <option value="Back Out">Back Out</option>
                                </select>
                            </div>

                            <!-- Inprocess Fields -->
                            <div class="conditional-field" id="inprocessFields">
                                <div class="form-group">
                                    <label class="form-label">Select Company</label>
                                    <select class="form-control-custom form-select" name="company_id">
                                        <option value="">-- Select Company --</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}">{{ $client->company_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Interview Date</label>
                                    <input type="datetime-local" class="form-control-custom" name="interview_date">
                                </div>
                            </div>

                            <!-- Remarks Field (for all statuses) -->
                            <div class="form-group">
                                <label class="form-label">Remarks</label>
                                <textarea class="form-control-custom" name="remarks" rows="3" placeholder="Add remarks about this status update..."></textarea>
                            </div>

                            <button type="submit" class="btn btn-update">
                                <i class="mdi mdi-check-circle"></i> Update Status
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- Update Status Section -->
        

        <!-- Status History -->
        <div class="col-lg-12 mb-4">
            <div class="detail-card h-100">
                <div class="card-header-custom">
                    <h5><i class="mdi mdi-history"></i> Status History</h5>
                </div>
                <div class="card-body-custom">
                    @if($statusHistory && count($statusHistory) > 0)
                    <div class="timeline-history">
                        @foreach($statusHistory as $history)
                        <div class="timeline-item-history">
                            <div class="timeline-date">
                                <i class="mdi mdi-clock-outline"></i> 
                                {{ \Carbon\Carbon::parse($history->created_at)->format('d M Y, h:i A') }}
                            </div>
                            <div class="timeline-status">
                                <span class="status-badge status-{{ strtolower(str_replace(' ', '', $history->status)) }}">
                                    {{ $history->status }}
                                </span>
                            </div>
                            <div class="timeline-details">
                                @if($history->company_id && $history->company)
                                    <p><strong>Company:</strong> {{ $history->company->company_name }}</p>
                                @endif
                                @if($history->interview_date)
                                    <p><strong>Interview Date:</strong> {{ \Carbon\Carbon::parse($history->interview_date)->format('d M Y, h:i A') }}</p>
                                @endif
                                @if($history->remarks)
                                    <p><strong>Remarks:</strong> {{ $history->remarks }}</p>
                                @endif
                                <p class="text-muted mb-0"><small>Updated by: {{ $history->updatedByUser->name ?? 'System' }}</small></p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="mdi mdi-history" style="font-size: 48px; color: #ddd;"></i>
                        <p class="mt-3 text-muted">No status history available</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons">
        <a href="{{ route('candidates.edit', $candidate->id) }}" class="btn btn-edit">
            <i class="mdi mdi-pencil"></i> Edit Profile
        </a>
        <a href="{{ route('candidates.index') }}" class="btn btn-back">
            <i class="mdi mdi-arrow-left"></i> Back to List
        </a>
    </div>

</div>

<!-- Material Design Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.getElementById('statusSelect');
        const inprocessFields = document.getElementById('inprocessFields');
        
        // Set current status as selected
        const currentStatus = "{{ $candidate->current_status ?? '' }}";
        if (currentStatus) {
            statusSelect.value = currentStatus;
        }
        
        // Show/hide conditional fields
        function toggleConditionalFields() {
            // Hide all conditional fields first
            document.querySelectorAll('.conditional-field').forEach(field => {
                field.style.display = 'none';
            });
            
            // Show relevant fields based on selected status
            const selectedStatus = statusSelect.value;
            if (selectedStatus === 'Inprocess') {
                inprocessFields.style.display = 'block';
            }
        }
        
        // Initial toggle
        toggleConditionalFields();
        
        // Add event listener
        statusSelect.addEventListener('change', toggleConditionalFields);
        
        // Form submission validation
        document.getElementById('statusForm').addEventListener('submit', function(e) {
            const selectedStatus = statusSelect.value;
            
            if (selectedStatus === 'Inprocess') {
                const companySelect = document.querySelector('select[name="company_id"]');
                const interviewDate = document.querySelector('input[name="interview_date"]');
                
                if (!companySelect.value) {
                    e.preventDefault();
                    alert('Please select a company for Inprocess status');
                    companySelect.focus();
                    return false;
                }
                
                if (!interviewDate.value) {
                    e.preventDefault();
                    alert('Please select an interview date for Inprocess status');
                    interviewDate.focus();
                    return false;
                }
            }
            
            const remarks = document.querySelector('textarea[name="remarks"]');
            if (!remarks.value.trim()) {
                if (!confirm('You haven\'t added any remarks. Continue without remarks?')) {
                    e.preventDefault();
                    return false;
                }
            }
        });
    });
</script>

@endsection