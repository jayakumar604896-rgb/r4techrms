@extends('layouts.app')

@section('title', 'Recruiter Profile')

@section('content')

<style>
    /* ---------------------------
       Global Creative Styling (screen only)
    ----------------------------*/
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --dark-bg: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
        --glass-bg: rgba(255, 255, 255, 0.08);
        --glass-border: rgba(255, 255, 255, 0.2);
        --shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        --radius: 20px;
    }

    body {
        background: #f8faff;
        background-image: 
            radial-gradient(circle at 10% 20%, rgba(108, 99, 255, 0.05) 0%, transparent 20%),
            radial-gradient(circle at 90% 80%, rgba(76, 0, 255, 0.05) 0%, transparent 20%);
    }

    /* Animated Header */
    .page-header-wrapper {
        position: relative;
        overflow: hidden;
        border-radius: var(--radius);
        margin-bottom: 40px;
        background: var(--dark-bg);
        padding: 40px;
        box-shadow: var(--shadow);
    }

    .page-header-wrapper::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--secondary-gradient);
        animation: shimmer 3s infinite;
    }

    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }

    /* Hero Profile Section (screen) */
    .hero-profile {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-radius: var(--radius);
        padding: 14px;
        margin-bottom: 40px;
        box-shadow: var(--shadow);
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .hero-profile::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle, rgba(108, 99, 255, 0.1) 0%, transparent 70%);
        transform: rotate(45deg);
    }

    .avatar-container {
        position: relative;
        width: 120px;
        height: 120px;
        margin: 0 auto 0px;
    }

    .profile-avatar {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid white;
        box-shadow: 0 10px 30px rgba(108, 99, 255, 0.3);
        transition: all 0.5s ease;
    }

    .profile-avatar:hover {
        transform: scale(1.05) rotate(5deg);
    }

    .avatar-ring {
        position: absolute;
        top: -1px;
        left: -1px;
        right: -1px;
        bottom: -1px;
        border: 3px dashed #667eea;
        border-radius: 50%;
        animation: spin 20s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .profile-info h1 {
        font-size: 36px;
        font-weight: 800;
        background: var(--dark-bg);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 10px;
    }

    .profile-tag {
        display: inline-block;
        padding: 8px 20px;
        background: var(--primary-gradient);
        color: white;
        border-radius: 50px;
        font-weight: 600;
        margin: 10px 5px;
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .profile-tag:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
    }

    /* Stats Cards (if used) */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }

    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 25px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        transition: all 0.4s ease;
        border: 1px solid rgba(108, 99, 255, 0.1);
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--primary-gradient);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        background: var(--primary-gradient);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        color: white;
        font-size: 24px;
    }

    .stat-value {
        font-size: 32px;
        font-weight: 800;
        background: var(--dark-bg);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 5px;
    }

    /* Details Glass Cards (screen) */
    .details-section {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border-radius: var(--radius);
        padding: 6px;
        box-shadow: var(--shadow);
        margin-bottom: 40px;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .section-title {
        font-size: 24px;
        font-weight: 700;
        background: var(--primary-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 30px;
        padding-left: 20px;
        position: relative;
    }

    .section-title::before {
        content: '';
        position: absolute;
        left: 0;
        top: 5px;
        bottom: 5px;
        width: 6px;
        background: var(--secondary-gradient);
        border-radius: 3px;
    }

    .detail-row {
        display: flex;
        align-items: center;
        padding: 20px;
        margin-bottom: 15px;
        background: rgba(255, 255, 255, 0.6);
        border-radius: 12px;
        border-left: 4px solid #667eea;
        transition: all 0.3s ease;
    }

    .detail-row:hover {
        transform: translateX(10px);
        background: white;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .detail-icon {
        width: 50px;
        height: 50px;
        background: rgba(102, 126, 234, 0.1);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 20px;
        color: #667eea;
        font-size: 20px;
    }

    .detail-label {
        font-weight: 600;
        color: #333;
        min-width: 160px;
        margin-right: -15px;
    }

    .detail-value {
        color: #666;
        flex: 1;
        font-weight: 500;
    }

    /* Status Toggle Switch */
    .status-toggle-container {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        margin-bottom: 30px;
        border: 1px solid rgba(108, 99, 255, 0.1);
    }

    .toggle-header {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
    }

    .status-indicator {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-right: 10px;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.5; }
    }

    .status-indicator.active { background: #10b981; box-shadow: 0 0 20px #10b981; }
    .status-indicator.inactive { background: #f59e0b; box-shadow: 0 0 20px #f59e0b; }
    .status-indicator.disable { background: #ef4444; box-shadow: 0 0 20px #ef4444; }

    .toggle-buttons {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .toggle-btn {
        flex: 1;
        min-width: 120px;
        padding: 15px 25px;
        text-align: center;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        background: rgba(108, 99, 255, 0.05);
    }

    .toggle-btn.active {
        background: var(--primary-gradient);
        color: white;
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        border-color: #667eea;
    }

    .toggle-btn:hover:not(.active) {
        transform: translateY(-3px);
        border-color: rgba(108, 99, 255, 0.3);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .date-picker-wrapper {
        margin-top: 20px;
        padding: 20px;
        background: rgba(245, 158, 11, 0.05);
        border-radius: 12px;
        border-left: 4px solid #f59e0b;
        display: none;
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        justify-content: center;
        padding: 30px;
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(10px);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .action-btn {
        padding: 15px 35px;
        border-radius: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        transition: all 0.4s ease;
        position: relative;
        overflow: hidden;
        border: none;
        cursor: pointer;
    }

    .action-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: 0.5s;
    }

    .action-btn:hover::before {
        left: 100%;
    }

    .btn-edit {
        background: var(--primary-gradient);
        color: white;
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
    }

    .btn-back {
        background: white;
        color: #333;
        border: 2px solid #e0e0e0;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .btn-update {
        background: var(--secondary-gradient);
        color: white;
        box-shadow: 0 10px 25px rgba(245, 87, 108, 0.4);
        display: none;
    }

    .action-btn:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(102, 126, 234, 0.5);
    }

    .btn-back:hover {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    /* Floating Elements */
    .floating-element {
        position: fixed;
        z-index: -1;
        opacity: 0.3;
        animation: float 20s infinite linear;
    }

    .float-1 {
        top: 10%;
        left: 5%;
        width: 100px;
        height: 100px;
        background: var(--primary-gradient);
        border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
        animation-delay: 0s;
    }

    .float-2 {
        top: 60%;
        right: 5%;
        width: 150px;
        height: 150px;
        background: var(--secondary-gradient);
        border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
        animation-delay: -5s;
    }

    .float-3 {
        bottom: 20%;
        left: 10%;
        width: 80px;
        height: 80px;
        background: #10b981;
        border-radius: 50% 20% 50% 30% / 50% 40% 60% 50%;
        animation-delay: -10s;
    }

    @keyframes float {
        0% { transform: translateY(0) rotate(0deg); }
        25% { transform: translateY(-20px) rotate(90deg); }
        50% { transform: translateY(0) rotate(180deg); }
        75% { transform: translateY(20px) rotate(270deg); }
        100% { transform: translateY(0) rotate(360deg); }
    }

    /* ---------------------------
       PRINT STYLES – CLEAN B&W LAYOUT
    ----------------------------*/
    @media print {
        /* Hide all screen content */
        body * {
            visibility: hidden;
        }

        /* Show only the print container and its children */
        #print, #print * {
            visibility: visible;
        }

        #print {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            background: white !important;
            color: black !important;
            display: block !important;  /* Override inline style */
        }

        /* Remove any background, shadows, gradients */
        #print, #print * {
            background: white !important;
            background-image: none !important;
            color: black !important;
            box-shadow: none !important;
            text-shadow: none !important;
            border-color: #333 !important;
        }

        /* Ensure A4 size */
        @page {
            size: A4;
            margin: 0.4in;
        }

        /* Typography scale */
        body, p, div, span, h1, h2, h3, h4 {
            font-size: 11pt;
            line-height: 1.3;
        }
    }
</style>

<!-- Floating Background Elements (screen only) -->
<div class="floating-element float-1"></div>
<div class="floating-element float-2"></div>
<div class="floating-element float-3"></div>

<div class="content-wrapper">

    <!-- Animated Header (screen only) -->
    <div class="page-header mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h3 class="page-title">View User</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 mt-2">
                    <li class="breadcrumb-item"><a href="{{ route('recruiters') }}">Recruiters</a></li>
                    <li class="breadcrumb-item active" aria-current="page">View User</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- ========== SCREEN PROFILE (COLORFUL, CREATIVE) ========== -->
    <div class="screen-profile">
        <!-- Hero Profile Section -->
        <div class="hero-profile">
            <div class="row align-items-center">
                <div class="col-md-4 text-center">
                    <div class="avatar-container">
                        @if($user->photo)
                            <img src="{{ asset($user->photo) }}" class="profile-avatar" alt="{{ $user->name }}">
                        @else
                            <div class="profile-avatar d-flex align-items-center justify-content-center" 
                                style="background: var(--primary-gradient); color: white; font-size: 48px; font-weight: bold;">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                        @endif
                        <div class="avatar-ring"></div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="profile-info">
                        <h1>{{ $user->name }}</h1>
                        <div class="profile-tag">
                            <i class="mdi mdi-briefcase me-1"></i>{{ $user->designation }}
                        </div>
                        <div class="profile-tag">
                            <i class="mdi mdi-identifier me-1"></i>{{ $user->company_serial_number }}
                        </div>
                        <div class="profile-tag">
                            <i class="mdi mdi-email me-1"></i>{{ $user->email }}
                        </div>
                        <div class="profile-tag">
                            <i class="mdi mdi-phone me-1"></i>{{ $user->staff_contact_no }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <h3 class="section-title">Personal Information</h3>
        <div class="details-section">
            <div class="detail-row">
                <div class="detail-icon"><i class="mdi mdi-account"></i></div>
                <div class="detail-label">Full Name</div>
                <div class="detail-value">{{ $user->name }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-icon"><i class="mdi mdi-email"></i></div>
                <div class="detail-label">Email Address</div>
                <div class="detail-value">{{ $user->email }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-icon"><i class="mdi mdi-phone"></i></div>
                <div class="detail-label">Contact Number</div>
                <div class="detail-value">{{ $user->staff_contact_no }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-icon"><i class="mdi mdi-phone-in-talk"></i></div>
                <div class="detail-label">Alternate Contact</div>
                <div class="detail-value">{{ $user->alternate_contact_no ?? '-' }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-icon"><i class="mdi mdi-map-marker"></i></div>
                <div class="detail-label">Address</div>
                <div class="detail-value">{{ $user->address }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-icon"><i class="mdi mdi-card-account-details"></i></div>
                <div class="detail-label">Aadhar Number</div>
                <div class="detail-value">{{ $user->aadhar_card_number }}</div>
            </div>
        </div>

        <h3 class="section-title">Employment Details</h3>
        <div class="details-section">
            <div class="detail-row">
                <div class="detail-icon"><i class="mdi mdi-briefcase-account"></i></div>
                <div class="detail-label">Designation</div>
                <div class="detail-value">{{ $user->designation }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-icon"><i class="mdi mdi-identifier"></i></div>
                <div class="detail-label">Employee Code</div>
                <div class="detail-value">{{ $user->company_serial_number }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-icon"><i class="mdi mdi-calendar-plus"></i></div>
                <div class="detail-label">Date of Joining</div>
                <div class="detail-value">
                    {{ $user->date_of_joining ? \Carbon\Carbon::parse($user->date_of_joining)->format('d M, Y') : '-' }}
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-icon"><i class="mdi mdi-laptop"></i></div>
                <div class="detail-label">Work Mode</div>
                <div class="detail-value">
                    {{ $user->work_mode ? $user->work_mode : '-' }}
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-icon"><i class="mdi mdi-cake-variant"></i></div>
                <div class="detail-label">Date of Birth</div>
                <div class="detail-value">
                    {{ $user->dob ? \Carbon\Carbon::parse($user->dob)->format('d M, Y') : '-' }}
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-icon"><i class="mdi mdi-calendar-remove"></i></div>
                <div class="detail-label">Date of Leaving</div>
                <div class="detail-value" id="dol-display">
                    @if($user->status == 'Inactive' && $user->date_of_living)
                        {{ \Carbon\Carbon::parse($user->date_of_living)->format('d M, Y') }}
                    @else
                        -
                    @endif
                </div>
            </div>
        </div>
    </div> <!-- end screen-profile -->

    <!-- Status Toggle (screen only) -->
    <div class="status-toggle-container">
        <div class="toggle-header">
            <div class="status-indicator {{ strtolower($user->status) }}"></div>
            <h4 style="margin: 0; color: #333; font-weight: 600;">Employment Status</h4>
        </div>
        
        <div class="toggle-buttons">
            <div class="toggle-btn {{ $user->status == 'Active' ? 'active' : '' }}" data-status="Active">
                <i class="mdi mdi-check-circle me-2"></i> Active
            </div>
            <div class="toggle-btn {{ $user->status == 'Inactive' ? 'active' : '' }}" data-status="Inactive">
                <i class="mdi mdi-pause-circle me-2"></i> Inactive
            </div>
            <div class="toggle-btn {{ $user->status == 'Disable' ? 'active' : '' }}" data-status="Disable">
                <i class="mdi mdi-close-circle me-2"></i> Disable
            </div>
        </div>
        
        <div id="date-picker-wrapper" class="date-picker-wrapper">
            <label for="dol-input" class="form-label">
                <i class="mdi mdi-calendar-alert me-1"></i> Select Date of Leaving
            </label>
            <input type="date" class="form-control" id="dol-input"
                   value="{{ $user->date_of_living ? \Carbon\Carbon::parse($user->date_of_living)->format('Y-m-d') : '' }}">
            <small class="text-muted mt-2 d-block">
                <i class="mdi mdi-information-outline"></i>
                Required when setting status to Inactive
            </small>
        </div>
        
        <div id="status-message" class="mt-3"></div>
    </div>

    <!-- Action Buttons (screen only) -->
    <div class="action-buttons">
        <button type="button" id="update-status-btn" class="action-btn btn-update" style="display: none;">
            <i class="mdi mdi-check-all"></i> Update Status
        </button>
        
        <a href="{{ route('recruiters.edit', $user->id) }}" class="action-btn btn-edit">
            <i class="mdi mdi-pencil"></i> Edit Profile
        </a>
        <button class="action-btn btn-print" onclick="window.print()">
            <i class="mdi mdi-printer"></i> Print Profile
        </button>
        
        <a href="{{ route('recruiters') }}" class="action-btn btn-back">
            <i class="mdi mdi-arrow-left"></i> Back to List
        </a>
    </div>

    <!-- ========== PRINT VERSION – CLEAN BLACK & WHITE ========== -->
    <div id="print" style="display: none;">
        <div style="max-width:1000px; margin:0 auto; background:white; border:1px solid #222; padding:2rem;">
            <!-- HEADER -->
            <div style="display:flex; justify-content:space-between; align-items:flex-start; border-bottom:2px solid #000; padding-bottom:1.5rem; margin-bottom:2rem; flex-wrap:wrap; gap:15px;">
                <div>
                    <h1 style="font-size:2.5rem; font-weight:700; letter-spacing:-0.5px; margin-bottom:0.3rem; color:#000;">{{ $user->name }}</h1>
                    <div style="display:flex; flex-wrap:wrap; gap:15px; margin-top:5px;">
                        <span style="border:1px solid #333; padding:4px 12px; font-size:0.9rem; background:white; color:black; font-weight:500;">{{ $user->company_serial_number ?? 'EMP1008' }}</span>
                        <span style="border:1px solid #333; padding:4px 12px; font-size:0.9rem; background:white; color:black; font-weight:500;">{{ $user->designation }}</span>
                        <span style="border:1px solid #333; padding:4px 12px; font-size:0.9rem; background:white; color:black; font-weight:500;">{{ $user->sector ?? 'Oil gas / PCM' }}</span>
                        <span style="border:2px solid #000; padding:4px 12px; font-size:0.9rem; background:white; color:black; font-weight:600;">{{ $user->status }}</span>
                    </div>
                </div>
                <div style="width:70px; height:70px; border:2px solid black; display:flex; align-items:center; justify-content:center; font-weight:bold; background:white;">
                    {{ substr($user->name, 0, 2) }}
                </div>
            </div>

            <!-- TWO-COLUMN GRID -->
            <div style="display:grid; grid-template-columns:repeat(2,1fr); gap:2rem 2.5rem; margin-bottom:2rem;">
                <!-- PERSONAL INFORMATION -->
                <div>
                    <h2 style="font-size:1.3rem; font-weight:600; border-bottom:1px solid #888; padding-bottom:0.3rem; margin-bottom:1rem; text-transform:uppercase; letter-spacing:0.5px; color:#000;">Personal Information</h2>
                    <div style="display:flex; align-items:baseline; margin-bottom:0.8rem; line-height:1.4;">
                        <span style="width:140px; font-weight:600; color:#000; flex-shrink:0;">EMPLOYEE CODE</span>
                        <span style="color:#222;">{{ $user->company_serial_number ?? 'EMP1008' }}</span>
                    </div>
                    <div style="display:flex; align-items:baseline; margin-bottom:0.8rem;">
                        <span style="width:140px; font-weight:600; color:#000;">DATE OF BIRTH</span>
                        <span>{{ $user->dob ? \Carbon\Carbon::parse($user->dob)->format('d M, Y') : '27 Mar, 1964' }}</span>
                    </div>
                    <div style="display:flex; align-items:baseline; margin-bottom:0.8rem;">
                        <span style="width:140px; font-weight:600; color:#000;">AGE</span>
                        <span>{{ $user->dob ? \Carbon\Carbon::parse($user->dob)->age : '61' }} years</span>
                    </div>
                    <div style="display:flex; align-items:baseline; margin-bottom:0.8rem;">
                        <span style="width:140px; font-weight:600; color:#000;">MOBILE</span>
                        <span>{{ $user->staff_contact_no ?? '8939757668' }}</span>
                    </div>
                    <div style="display:flex; align-items:baseline; margin-bottom:0.8rem;">
                        <span style="width:140px; font-weight:600; color:#000;">EMAIL</span>
                        <span>{{ $user->email ?? 'teamhr3@paswigroup.com' }}</span>
                    </div>
                    <div style="display:flex; align-items:baseline; margin-bottom:0.8rem;">
                        <span style="width:140px; font-weight:600; color:#000;">PASSPORT</span>
                        <span>{{ $user->passport_number ?? '—' }}</span>
                    </div>
                </div>

                <!-- PROFESSIONAL DETAILS -->
                <div>
                    <h2 style="font-size:1.3rem; font-weight:600; border-bottom:1px solid #888; padding-bottom:0.3rem; margin-bottom:1rem; text-transform:uppercase; letter-spacing:0.5px; color:#000;">Professional Details</h2>
                    <div style="display:flex; align-items:baseline; margin-bottom:0.8rem;">
                        <span style="width:140px; font-weight:600; color:#000;">DESIGNATION</span>
                        <span>{{ $user->designation ?? 'Sr Engineer Mechanical' }}</span>
                    </div>
                    <div style="display:flex; align-items:baseline; margin-bottom:0.8rem;">
                        <span style="width:140px; font-weight:600; color:#000;">TOTAL EXPERIENCE</span>
                        <span>{{ $user->total_experience ?? '18.0' }} years</span>
                    </div>
                    <div style="display:flex; align-items:baseline; margin-bottom:0.8rem;">
                        <span style="width:140px; font-weight:600; color:#000;">SECTOR</span>
                        <span>{{ $user->sector ?? 'Oil gas / PCM' }}</span>
                    </div>
                    <div style="display:flex; align-items:baseline; margin-bottom:0.8rem;">
                        <span style="width:140px; font-weight:600; color:#000;">INDUSTRY</span>
                        <span>{{ $user->industry ?? 'Operation, Testing & commissioning' }}</span>
                    </div>
                    <div style="display:flex; align-items:baseline; margin-bottom:0.8rem;">
                        <span style="width:140px; font-weight:600; color:#000;">PLACEMENT TYPE</span>
                        <span>{{ $user->placement_type ?? 'Executive Staff Placements' }}</span>
                    </div>
                    <div style="display:flex; align-items:baseline; margin-bottom:0.8rem;">
                        <span style="width:140px; font-weight:600; color:#000;">CURRENT CTC</span>
                        <span>₹ {{ $user->current_ctc ?? '15' }} Lacs</span>
                    </div>
                </div>

                <!-- EMPLOYMENT DETAILS -->
                <div>
                    <h2 style="font-size:1.3rem; font-weight:600; border-bottom:1px solid #888; padding-bottom:0.3rem; margin-bottom:1rem; text-transform:uppercase; letter-spacing:0.5px; color:#000;">Employment Details</h2>
                    <div style="display:flex; align-items:baseline; margin-bottom:0.8rem;">
                        <span style="width:140px; font-weight:600; color:#000;">NOTICE PERIOD</span>
                        <span>{{ $user->notice_period ?? '30' }} days</span>
                    </div>
                    <div style="display:flex; align-items:baseline; margin-bottom:0.8rem;">
                        <span style="width:140px; font-weight:600; color:#000;">CURRENT STAY</span>
                        <span>{{ $user->current_location ?? 'Chennai' }}</span>
                    </div>
                    <div style="display:flex; align-items:baseline; margin-bottom:0.8rem;">
                        <span style="width:140px; font-weight:600; color:#000;">APPLIED POSITION</span>
                        <span>{{ $user->applied_position ?? 'Sr Project Manager - Kuwait' }}</span>
                    </div>
                    <div style="display:flex; align-items:baseline; margin-bottom:0.8rem;">
                        <span style="width:140px; font-weight:600; color:#000;">COMPANY SERIAL</span>
                        <span>{{ $user->company_serial_number ?? 'CODE NO' }}</span>
                    </div>
                    <div style="display:flex; align-items:baseline; margin-bottom:0.8rem;">
                        <span style="width:140px; font-weight:600; color:#000;">CANDIDATE TYPE</span>
                        <span>{{ $user->candidate_type ?? 'International' }}</span>
                    </div>
                </div>

                <!-- EDUCATION & DOCUMENTS -->
                <div>
                    <h2 style="font-size:1.3rem; font-weight:600; border-bottom:1px solid #888; padding-bottom:0.3rem; margin-bottom:1rem; text-transform:uppercase; letter-spacing:0.5px; color:#000;">Education & Documents</h2>
                    <div style="display:flex; align-items:baseline; margin-bottom:0.8rem;">
                        <span style="width:140px; font-weight:600; color:#000;">QUALIFICATION</span>
                        <span>{{ $user->qualification ?? 'B.E MECHANICAL & MBA Projects' }}</span>
                    </div>
                    <div style="display:flex; align-items:baseline; margin-bottom:0.8rem;">
                        <span style="width:140px; font-weight:600; color:#000;">DOS</span>
                        <span>{{ $user->dos ? \Carbon\Carbon::parse($user->dos)->format('d M, Y') : '06 Feb, 2026' }}</span>
                    </div>
                </div>
            </div>

            <!-- RESUME SECTION -->
            <div style="margin-top:1rem; border:1px dashed #333; padding:1rem; background:#fafafa;">
                <p style="margin:0; font-family:monospace; font-size:0.95rem;">
                    <strong>Resume Available</strong> · {{ $user->resume_file ?? '1770362056_Ranjith Fire fighting (1).docx' }}
                </p>
            </div>

            <hr style="border:none; border-top:1px solid #aaa; margin:1rem 0;">
            <div style="font-size:0.8rem; color:#444; text-align:right;">
                Profile generated · no colours, maximum clarity
            </div>
        </div>
    </div>

</div>

@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        let currentStatus = '{{ $user->status }}';
        let selectedStatus = currentStatus;
        let statusChanged = false;

        // Toggle button click handler
        $('.toggle-btn').click(function() {
            $('.toggle-btn').removeClass('active');
            $(this).addClass('active');
            
            selectedStatus = $(this).data('status');
            statusChanged = selectedStatus !== currentStatus;
            
            $('.status-indicator').removeClass('active inactive disable');
            $('.status-indicator').addClass(selectedStatus.toLowerCase());
            
            if (selectedStatus === 'Inactive') {
                $('#date-picker-wrapper').slideDown(300);
            } else {
                $('#date-picker-wrapper').slideUp(300);
            }
            
            if (statusChanged) {
                $('#update-status-btn').slideDown(300);
            } else {
                $('#update-status-btn').slideUp(300);
            }
        });

        // Date input change handler
        $('#dol-input').change(function() {
            if (selectedStatus === 'Inactive' && $(this).val()) {
                $('#update-status-btn').show();
            }
        });

        // Update status button click
        $('#update-status-btn').click(function() {
            const btn = $(this);
            const recruiterId = {{ $user->id }};
            const newStatus = selectedStatus;
            const dol = selectedStatus === 'Inactive' ? $('#dol-input').val() : null;

            if (newStatus === 'Inactive' && !dol) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Date Required',
                    text: 'Please select Date of Leaving for Inactive status.',
                    confirmButtonColor: '#667eea',
                    confirmButtonText: 'OK',
                    backdrop: 'rgba(0,0,0,0.4)'
                });
                return;
            }

            Swal.fire({
                title: 'Confirm Status Update',
                html: `
                    <div class="text-center">
                        <div class="mb-4">
                            <div class="status-indicator ${newStatus.toLowerCase()}" 
                                 style="width: 40px; height: 40px; margin: 0 auto; box-shadow: 0 0 30px ${getStatusColor(newStatus)};"></div>
                        </div>
                        <h4>Change status to <strong>${newStatus}</strong>?</h4>
                        ${newStatus === 'Inactive' ? `<p>Date of Leaving: ${new Date(dol).toLocaleDateString('en-GB')}</p>` : ''}
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Confirm Update',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#667eea',
                cancelButtonColor: '#6c757d',
                reverseButtons: true,
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                },
                customClass: {
                    confirmButton: 'btn btn-lg',
                    cancelButton: 'btn btn-lg'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    updateStatus(recruiterId, newStatus, dol, btn);
                }
            });
        });

        function getStatusColor(status) {
            switch(status) {
                case 'Active': return '#10b981';
                case 'Inactive': return '#f59e0b';
                case 'Disable': return '#ef4444';
                default: return '#667eea';
            }
        }

        function updateStatus(recruiterId, status, dol, btn) {
            const csrfToken = $('meta[name="csrf-token"]').attr('content');
            const statusMessage = $('#status-message');
            const originalText = btn.html();

            btn.html('<i class="mdi mdi-loading mdi-spin"></i> Updating...');
            btn.prop('disabled', true);

            $.ajax({
                url: '{{ route("recruiters.update.status") }}',
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken },
                data: { recruiter_id: recruiterId, status: status, date_of_living: dol },
                beforeSend: function() { statusMessage.html(''); },
                success: function(response) {
                    if (response.success) {
                        currentStatus = status;
                        statusChanged = false;
                        
                        if (status === 'Inactive' && dol) {
                            const formattedDate = new Date(dol).toLocaleDateString('en-GB', {
                                day: '2-digit', month: 'short', year: 'numeric'
                            });
                            $('#dol-display').html(formattedDate);
                        } else {
                            $('#dol-display').html('-');
                        }

                        statusMessage.html(`
                            <div class="alert alert-success alert-dismissible fade show shadow-lg" role="alert" 
                                 style="background: linear-gradient(135deg, #10b981, #059669); color: white; border: none;">
                                <i class="mdi mdi-check-circle"></i> 
                                <strong>Success!</strong> ${response.message}
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
                            </div>
                        `);

                        $('#update-status-btn').slideUp(300);

                        Swal.fire({
                            icon: 'success',
                            title: 'Updated!',
                            text: 'Status has been updated successfully.',
                            confirmButtonColor: '#667eea',
                            timer: 2000,
                            timerProgressBar: true,
                            showConfirmButton: false,
                            backdrop: 'rgba(102, 126, 234, 0.3)'
                        });
                    } else {
                        statusMessage.html(`
                            <div class="alert alert-danger alert-dismissible fade show shadow-lg" role="alert">
                                <i class="mdi mdi-alert-circle"></i> 
                                <strong>Error!</strong> ${response.message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        `);
                        $('.toggle-btn').removeClass('active');
                        $(`.toggle-btn[data-status="${currentStatus}"]`).addClass('active');
                        selectedStatus = currentStatus;
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'An error occurred while updating status.',
                        confirmButtonColor: '#667eea'
                    });
                    $('.toggle-btn').removeClass('active');
                    $(`.toggle-btn[data-status="${currentStatus}"]`).addClass('active');
                    selectedStatus = currentStatus;
                },
                complete: function() {
                    btn.html(originalText);
                    btn.prop('disabled', false);
                }
            });
        }

        @if($user->status == 'Inactive')
            $('#date-picker-wrapper').show();
        @endif
    });
</script>
@endsection