@extends('layouts.app')

@section('title', 'Agent Details')

@section('content')

<style>
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

    .agent-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .agent-header::before {
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

    .agent-avatar {
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

    .agent-info h2 {
        font-size: 32px;
        font-weight: 800;
        margin-bottom: 5px;
    }

    .agent-info .badge {
        background: rgba(255,255,255,0.2);
        color: white;
        padding: 8px 20px;
        border-radius: 5px;
        font-size: 14px;
        font-weight: 600;
        backdrop-filter: blur(10px);
    }

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

    .status-badge {
        padding: 8px 16px;
        border-radius: 5px;
        font-size: 14px;
        font-weight: 600;
        display: inline-block;
    }

    .badge-success { background: #e8f5e9; color: #2e7d32; }
    .badge-danger { background: #ffebee; color: #c62828; }
    .badge-info { background: #e0f7fa; color: #006064; }
    .badge-secondary { background: #f5f5f5; color: #616161; }

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

    .action-buttons {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        padding-top: 20px;
        border-top: 1px solid var(--border-color);
        margin-top: 30px;
    }

    .btn-edit {
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
    }

    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(67, 97, 238, 0.3);
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

    @media (max-width: 768px) {
        .agent-header {
            text-align: center;
            padding: 25px 20px;
        }
        
        .agent-avatar {
            margin: 0 auto 20px;
        }
        
        .info-grid {
            grid-template-columns: 1fr;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .action-buttons .btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="content-wrapper">

    <!-- Page Header -->
    <div class="page-header mb-4">
        <h3 class="page-title">Agent Profile</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 mt-2">
                <li class="breadcrumb-item"><a href="{{ route('agents.index') }}">Agents</a></li>
                <li class="breadcrumb-item active" aria-current="page">Profile Details</li>
            </ol>
        </nav>
    </div>

    <!-- Agent Header -->
    <div class="agent-header d-flex align-items-center" style="position: relative; z-index: 1;">
        <div class="agent-avatar">
            {{ strtoupper(substr($agent->agent_name, 0, 2)) }}
        </div>
        <div class="agent-info">
            <h2>{{ $agent->agent_name }}</h2>
            <div class="d-flex align-items-center gap-3 mt-2">
                <span class="badge">{{ $agent->agent_company_name ?? 'No Company' }}</span>
                <span class="badge">{{ $agent->desination ?? 'N/A' }}</span>
                <span class="status-badge badge-{{ $agent->status == 'active' ? 'success' : 'danger' }}">
                    {{ ucfirst($agent->status) }}
                </span>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="row">
        <!-- Company Information -->
        <div class="col-lg-6 col-md-6 mb-4">
            <div class="detail-card">
                <div class="card-header-custom">
                    <h5><i class="mdi mdi-office-building"></i> Agent Information</h5>
                </div>
                <div class="card-body-custom">
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Agent Company Name</span>
                            <span class="info-value">{{ $agent->agent_company_name ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Type of Business</span>
                            <span class="info-value">{{ $agent->type_business ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Agent Company Location</span>
                            <span class="info-value">{{ $agent->company_location ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">GST Number</span>
                            <span class="info-value">
                                @if($agent->gst_number)
                                <span class="badge badge-secondary">{{ $agent->gst_number }}</span>
                                @else
                                <span class="text-muted">N/A</span>
                                @endif
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Status</span>
                            <span class="info-value">
                                <span class="status-badge badge-{{ $agent->status == 1 ? 'success' : 'danger' }}">
								    {{ $agent->status == 1 ? 'Active' : 'Inactive' }}
								</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Personal Information -->
        <div class="col-lg-6 col-md-6 mb-4">
            <div class="detail-card">
                <div class="card-header-custom">
                    <h5><i class="mdi mdi-account-circle"></i> Personal Information</h5>
                </div>
                <div class="card-body-custom">
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Agent Name</span>
                            <span class="info-value">{{ $agent->agent_name }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Designation</span>
                            <span class="info-value">{{ $agent->desination ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Mobile Number</span>
                            <span class="info-value">
                                <a href="tel:{{ $agent->mobile_number }}" class="text-decoration-none">
                                    <i class="mdi mdi-phone me-1"></i> {{ $agent->mobile_number }}
                                </a>
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Alternate Number</span>
                            <span class="info-value">
                                @if($agent->alternate_number)
                                <a href="tel:{{ $agent->alternate_number }}" class="text-decoration-none">
                                    <i class="mdi mdi-phone me-1"></i> {{ $agent->alternate_number }}
                                </a>
                                @else
                                <span class="text-muted">N/A</span>
                                @endif
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Email ID</span>
                            <span class="info-value">
                                @if($agent->email_id)
                                <a href="mailto:{{ $agent->email_id }}" class="text-decoration-none">
                                    <i class="mdi mdi-email me-1"></i> {{ $agent->email_id }}
                                </a>
                                @else
                                <span class="text-muted">N/A</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Address Information -->
        <div class="col-lg-12 mb-4">
            <div class="detail-card">
                <div class="card-header-custom">
                    <h5><i class="mdi mdi-map-marker"></i> Address Information</h5>
                </div>
                <div class="card-body-custom">
                    @if($agent->address)
                    <div class="p-4 bg-light rounded" style="border-left: 4px solid var(--primary-color);">
                        <h6 class="mb-3">Complete Address</h6>
                        <p class="mb-0" style="white-space: pre-line; line-height: 1.8;">{{ $agent->address }}</p>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="mdi mdi-map-marker-off" style="font-size: 48px; color: #ddd;"></i>
                        <p class="mt-3 text-muted">No address information available</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Business Profile Attachment -->
        <div class="col-lg-6 mb-4">
            <div class="detail-card h-100">
                <div class="card-header-custom">
                    <h5><i class="mdi mdi-file-document"></i> Business Profile Attachment</h5>
                </div>
                <div class="card-body-custom">
                    @if($agent->agent_business_profile_attachment && file_exists(public_path($agent->agent_business_profile_attachment)))
                    <div class="resume-card">
                        <div class="resume-icon">
                            <i class="mdi mdi-file-document"></i>
                        </div>
                        <h5 class="mb-3">Business Profile Available</h5>
                        <p class="text-muted mb-3">Click below to download the business profile</p>
                        <a href="{{ asset($agent->agent_business_profile_attachment) }}" 
                           target="_blank" 
                           class="btn btn-edit mb-2">
                            <i class="mdi mdi-eye"></i> View File
                        </a>
                        <a href="{{ asset($agent->agent_business_profile_attachment) }}" 
                           download 
                           class="btn btn-outline-primary">
                            <i class="mdi mdi-download"></i> Download
                        </a>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="mdi mdi-file-document-off" style="font-size: 48px; color: #ddd;"></i>
                        <p class="mt-3 text-muted">No business profile uploaded</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- System Information -->
        <div class="col-lg-6 mb-4">
            <div class="detail-card h-100">
                <div class="card-header-custom">
                    <h5><i class="mdi mdi-information-outline"></i> System Information</h5>
                </div>
                <div class="card-body-custom">
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Created At</span>
                            <span class="info-value">
                                @if($agent->created_at)
                                {{ \Carbon\Carbon::parse($agent->created_at)->format('d M Y, h:i A') }}
                                @else
                                <span class="text-muted">N/A</span>
                                @endif
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Last Updated</span>
                            <span class="info-value">
                                @if($agent->updated_at)
                                {{ \Carbon\Carbon::parse($agent->updated_at)->format('d M Y, h:i A') }}
                                @else
                                <span class="text-muted">N/A</span>
                                @endif
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Record ID</span>
                            <span class="info-value">{{ $agent->id }}</span>
                        </div>
                    </div>
                    
                    <!-- Quick Stats (if you want to add some) -->
                    <div class="mt-4 pt-4 border-top">
                        <h6 class="mb-3">Quick Information</h6>
                        <div class="row">
                            <div class="col-6">
                                <div class="text-center p-3 bg-light rounded">
                                    <i class="mdi mdi-phone mb-2" style="font-size: 24px; color: var(--primary-color);"></i>
                                    <p class="mb-1">Primary Contact</p>
                                    <h5 class="mb-0">{{ $agent->mobile_number }}</h5>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-3 bg-light rounded">
                                    <i class="mdi mdi-email mb-2" style="font-size: 24px; color: var(--accent-color);"></i>
                                    <p class="mb-1">Email</p>
                                    <h6 class="mb-0 text-truncate">{{ $agent->email_id ?? 'N/A' }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="action-buttons">
        <a href="{{ route('agents.edit', $agent->id) }}" class="btn btn-edit">
            <i class="mdi mdi-pencil"></i> Edit Agent
        </a>
        <a href="{{ route('agents.index') }}" class="btn btn-back">
            <i class="mdi mdi-arrow-left"></i> Back to Agents
        </a>
    </div>

</div>

<!-- Material Design Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">

<script>
    // Add subtle animations
    document.addEventListener('DOMContentLoaded', function() {
        const infoItems = document.querySelectorAll('.info-item');
        infoItems.forEach((item, index) => {
            item.style.animationDelay = `${index * 0.1}s`;
            item.classList.add('animate__animated', 'animate__fadeInUp');
        });
    });
</script>

@endsection