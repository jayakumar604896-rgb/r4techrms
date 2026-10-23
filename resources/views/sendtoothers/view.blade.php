@extends('layouts.app')

@section('title', 'View Demand Details')

@section('content')
<style>
    /* ---------------------------
       Modern Compact Styling
    ----------------------------*/
    :root {
        --primary-color: #4361ee;
        --secondary-color: #3f37c9;
        --success-color: #4cc9f0;
        --warning-color: #f72585;
        --light-bg: #f8f9fa;
        --border-color: #e9ecef;
        --text-dark: #2d3748;
        --text-light: #718096;
    }

    /* Main Container */
    .demand-detail-container {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        margin-bottom: 30px;
    }

    /* Header */
    .demand-header {
        background: linear-gradient(135deg, #4361ee 0%, #3f37c9 100%);
        color: white;
        padding: 25px 30px;
        position: relative;
    }

    .demand-title {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .demand-meta {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        align-items: center;
    }

    .status-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .badge-open { background: #10b981; }
    .badge-progress { background: #f59e0b; }
    .badge-closed { background: #ef4444; }
    .badge-ref { background: rgba(255,255,255,0.2); }

    /* Compact Info Cards */
    .compact-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 15px;
        border: 1px solid var(--border-color);
        transition: all 0.3s ease;
        display: flex;
        align-items: flex-start;
        gap: 15px;
        height: auto;
        min-height: 90px;
    }

    .compact-card:hover {
        border-color: var(--primary-color);
        box-shadow: 0 5px 15px rgba(67, 97, 238, 0.1);
        transform: translateY(-2px);
    }

    .card-icon {
        width: 45px;
        height: 45px;
        min-width: 45px;
        background: linear-gradient(135deg, #4361ee20 0%, #3f37c920 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        font-size: 20px;
    }

    .card-content {
        flex: 1;
    }

    .card-label {
        font-size: 12px;
        color: var(--text-light);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .card-value {
        font-size: 16px;
        font-weight: 600;
        color: var(--text-dark);
        margin: 0;
        line-height: 1.4;
    }

    .card-value.small {
        font-size: 14px;
        font-weight: 500;
    }

    /* Section Headers */
    .section-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 30px 0 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--border-color);
    }

    .section-icon {
        width: 35px;
        height: 35px;
        background: linear-gradient(135deg, #4361ee 0%, #3f37c9 100%);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-dark);
        margin: 0;
    }

    /* Keyword Tags */
    .keyword-tag {
        display: inline-block;
        background: #f1f5f9;
        color: var(--text-dark);
        padding: 6px 15px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
        margin: 5px;
        border-left: 3px solid var(--primary-color);
        transition: all 0.3s ease;
    }

    .keyword-tag:hover {
        background: var(--primary-color);
        color: white;
        transform: translateY(-2px);
    }

    .keyword-tag.section-a {
        border-left-color: #10b981;
    }

    .keyword-tag.section-b {
        border-left-color: #ef4444;
    }

    /* Portal Tags */
    .portal-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f8f9fa;
        color: var(--text-dark);
        padding: 6px 15px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
        margin: 5px;
        border: 1px solid var(--border-color);
    }

    .portal-tag.social { border-color: #3b82f6; color: #3b82f6; background: #dbeafe; }
    .portal-tag.print { border-color: #8b5cf6; color: #8b5cf6; background: #ede9fe; }
    .portal-tag.local { border-color: #10b981; color: #10b981; background: #d1fae5; }
    .portal-tag.online { border-color: #f59e0b; color: #f59e0b; background: #fef3c7; }

    /* Timeline */
    .compact-timeline {
        position: relative;
        padding-left: 20px;
    }

    .compact-timeline::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, #4361ee, #3f37c9);
    }

    .timeline-item {
        position: relative;
        margin-bottom: 15px;
        padding-left: 20px;
    }

    .timeline-item::before {
        content: '';
        position: absolute;
        left: -24px;
        top: 8px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: var(--primary-color);
        border: 2px solid white;
        box-shadow: 0 0 0 3px var(--primary-color);
    }

    .timeline-date {
        font-size: 12px;
        color: var(--text-light);
        font-weight: 600;
    }

    .timeline-text {
        font-size: 14px;
        color: var(--text-dark);
        font-weight: 500;
    }

    /* Action Buttons */
    .action-buttons {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
        display: flex;
        gap: 10px;
    }

    .btn-action {
        width: 50px;
        height: 50px;
        border-radius: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
        border: none;
        color: white;
    }

    .btn-action:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
    }

    .btn-edit { background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%); }
    .btn-print { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); }
    .btn-share { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }

    /* Additional Info Box */
    .additional-info {
        background: #f8f9fa;
        border-left: 4px solid var(--primary-color);
        padding: 15px;
        border-radius: 8px;
        font-size: 14px;
        line-height: 1.6;
        color: var(--text-dark);
        margin-top: 10px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .demand-title {
            font-size: 20px;
        }
        
        .compact-card {
            padding: 15px;
        }
        
        .card-icon {
            width: 40px;
            height: 40px;
            min-width: 40px;
            font-size: 18px;
        }
        
        .action-buttons {
            bottom: 15px;
            right: 15px;
        }
        
        .btn-action {
            width: 45px;
            height: 45px;
            font-size: 16px;
        }
    }
</style>

<div class="content-wrapper">
    <!-- Floating Action Buttons -->
    <div class="action-buttons">
        <a href="{{ route('demands.edit', $demand->id) }}" class="btn btn-action btn-edit" title="Edit">
            <i class="mdi mdi-pencil"></i>
        </a>
        <a href="{{ route('demands.print', $demand->id) }}" class="btn btn-action btn-print" title="Print" target="_blank"><button class="btn btn-action btn-print" title="Print" >
            <i class="mdi mdi-printer"></i>
        </button></a>
        <button class="btn btn-action btn-share" title="Share" id="shareBtn">
            <i class="mdi mdi-share-variant"></i>
        </button>
    </div>

    <div class="demand-detail-container">
        <!-- Header -->
        <div class="demand-header">
            <div class="d-flex justify-content-between align-items-start flex-wrap">
                <div>
                    <h3 class="page-title mb-2">Demand Details</h3>
                    <h1 class="demand-title">{{ $demand->designation_offered }}</h1>
                    <div class="demand-meta mt-2">
                        <span class="status-badge badge-{{ strtolower(str_replace(' ', '-', $demand->status)) }}">
                            <i class="mdi mdi-circle-small mr-1"></i>{{ $demand->status }}
                        </span>
                        <span class="status-badge badge-ref">
                            <i class="mdi mdi-tag mr-1"></i>{{ $demand->demand_ref_no ?? 'No Ref' }}
                        </span>
                        @if($demand->user_type)
                        <span class="status-badge" style="background: rgba(255,255,255,0.2);">
                            <i class="mdi mdi-account mr-1"></i>{{ $demand->user_type }}
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-3 p-md-4">
            <!-- Quick Stats Row -->
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    <div class="compact-card">
                        <div class="card-icon">
                            <i class="mdi mdi-account-multiple"></i>
                        </div>
                        <div class="card-content">
                            <div class="card-label">Positions</div>
                            <h4 class="card-value">{{ $demand->no_of_positions }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="compact-card">
                        <div class="card-icon">
                            <i class="mdi mdi-map-marker"></i>
                        </div>
                        <div class="card-content">
                            <div class="card-label">Job Location</div>
                            <h4 class="card-value">{{ $demand->job_location }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="compact-card">
                        <div class="card-icon">
                            <i class="mdi mdi-briefcase"></i>
                        </div>
                        <div class="card-content">
                            <div class="card-label">Industry</div>
                            <h4 class="card-value">{{ $demand->industry }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="compact-card">
                        <div class="card-icon">
                            <i class="mdi mdi-cash"></i>
                        </div>
                        <div class="card-content">
                            <div class="card-label">Salary</div>
                            <h4 class="card-value">{{ $demand->salary ?? 'N/A' }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Basic Information -->
            <div class="section-header">
                <div class="section-icon">
                    <i class="mdi mdi-information"></i>
                </div>
                <h4 class="section-title">Basic Information</h4>
            </div>
            
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    <div class="compact-card">
                        <div class="card-icon">
                            <i class="mdi mdi-account-tie"></i>
                        </div>
                        <div class="card-content">
                            <div class="card-label">Recruiter</div>
                            <p class="card-value small">{{ $demand->recruiter_name }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="compact-card">
                        <div class="card-icon">
                            <i class="mdi mdi-office-building"></i>
                        </div>
                        <div class="card-content">
                            <div class="card-label">Client/Source</div>
                            <p class="card-value small">{{ $demand->client_agent_source ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="compact-card">
                        <div class="card-icon">
                            <i class="mdi mdi-school"></i>
                        </div>
                        <div class="card-content d-flex gap-3">
                            <div class="card-label">Qualification</div>
                            <p class="card-value small">{{ $demand->qualification }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="compact-card">
                        <div class="card-icon">
                            <i class="mdi mdi-clock-outline"></i>
                        </div>
                        <div class="card-content">
                            <div class="card-label">Experience</div>
                            <p class="card-value small">{{ $demand->experience }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Benefits Section -->
            @if($demand->food || $demand->accommodation_transport || $demand->working_hours || $demand->overtime)
            <div class="section-header mt-4">
                <div class="section-icon">
                    <i class="mdi mdi-star-circle"></i>
                </div>
                <h4 class="section-title">Benefits & Conditions</h4>
            </div>
            
            <div class="row">
                @if($demand->food)
                <div class="col-md-3 col-sm-6">
                    <div class="compact-card">
                        <div class="card-icon">
                            <i class="mdi mdi-food"></i>
                        </div>
                        <div class="card-content">
                            <div class="card-label">Food</div>
                            <p class="card-value small">{{ $demand->food }}</p>
                        </div>
                    </div>
                </div>
                @endif
                @if($demand->accommodation_transport)
                <div class="col-md-3 col-sm-6">
                    <div class="compact-card">
                        <div class="card-icon">
                            <i class="mdi mdi-home-city"></i>
                        </div>
                        <div class="card-content">
                            <div class="card-label">Accommodation & Transport</div>
                            <p class="card-value small">{{ $demand->accommodation_transport }}</p>
                        </div>
                    </div>
                </div>
                @endif
                @if($demand->working_hours)
                <div class="col-md-3 col-sm-6">
                    <div class="compact-card">
                        <div class="card-icon">
                            <i class="mdi mdi-clock-time-eight"></i>
                        </div>
                        <div class="card-content">
                            <div class="card-label">Working Hours</div>
                            <p class="card-value small">{{ $demand->working_hours }}</p>
                        </div>
                    </div>
                </div>
                @endif
                @if($demand->overtime)
                <div class="col-md-3 col-sm-6">
                    <div class="compact-card">
                        <div class="card-icon">
                            <i class="mdi mdi-timer-sand"></i>
                        </div>
                        <div class="card-content">
                            <div class="card-label">Overtime Policy</div>
                            <p class="card-value small">{{ $demand->overtime }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            @endif

            <!-- Keywords Section -->
            @if($demand->main_keyword_design || $demand->main_keyword_qualification || $demand->main_keyword_experience)
            <div class="section-header mt-4">
                <div class="section-icon">
                    <i class="mdi mdi-keyword"></i>
                </div>
                <h4 class="section-title">Keywords to Check</h4>
            </div>
            
            <!-- Main Keywords -->
            @if($demand->main_keyword_design || $demand->main_keyword_qualification || $demand->main_keyword_experience)
            <div class="mb-3">
                <h6 class="text-primary mb-2"><i class="mdi mdi-magnify mr-2"></i>Main Keywords</h6>
                <div class="d-flex flex-wrap">
                    @if($demand->main_keyword_design)
                    <span class="keyword-tag section-a">{{ $demand->main_keyword_design }}</span>
                    @endif
                    @if($demand->main_keyword_qualification)
                    <span class="keyword-tag section-a">{{ $demand->main_keyword_qualification }}</span>
                    @endif
                    @if($demand->main_keyword_experience)
                    <span class="keyword-tag section-a">{{ $demand->main_keyword_experience }}</span>
                    @endif
                    @if($demand->main_keyword_special_requirements)
                    <span class="keyword-tag section-a">{{ $demand->main_keyword_special_requirements }}</span>
                    @endif
                </div>
            </div>
            @endif

            <!-- Secondary Keywords -->
            @if($demand->secondary_keyword_notice || $demand->secondary_keyword_salary_budget)
            <div class="mb-3">
                <h6 class="text-danger mb-2"><i class="mdi mdi-magnify-plus mr-2"></i>Secondary Keywords</h6>
                <div class="d-flex flex-wrap">
                    @if($demand->secondary_keyword_notice)
                    <span class="keyword-tag section-b">{{ $demand->secondary_keyword_notice }}</span>
                    @endif
                    @if($demand->secondary_keyword_salary_budget)
                    <span class="keyword-tag section-b">{{ $demand->secondary_keyword_salary_budget }}</span>
                    @endif
                    @if($demand->secondary_keyword_additional_3)
                    <span class="keyword-tag section-b">{{ $demand->secondary_keyword_additional_3 }}</span>
                    @endif
                    @if($demand->secondary_keyword_additional_4)
                    <span class="keyword-tag section-b">{{ $demand->secondary_keyword_additional_4 }}</span>
                    @endif
                </div>
            </div>
            @endif
            @endif

            <!-- Interview & Selection -->
            <div class="section-header mt-4">
                <div class="section-icon">
                    <i class="mdi mdi-account-voice"></i>
                </div>
                <h4 class="section-title">Interview & Selection</h4>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="compact-card">
                        <div class="card-icon">
                            <i class="mdi mdi-microphone"></i>
                        </div>
                        <div class="card-content">
                            <div class="card-label">Interview Details</div>
                            <p class="card-value small mb-1"><strong>Mode:</strong> {{ $demand->mode_of_interview ?? 'N/A' }}</p>
                            @if($demand->interview_date)
                            <p class="card-value small mb-1"><strong>Date:</strong> {{ $demand->interview_date->format('d M, Y') }}</p>
                            @endif
                            @if($demand->interview_location)
                            <p class="card-value small"><strong>Location:</strong> {{ $demand->interview_location }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="compact-card">
                        <div class="card-icon">
                            <i class="mdi mdi-cash-check"></i>
                        </div>
                        <div class="card-content">
                            <div class="card-label">Service & Requirements</div>
                            <p class="card-value small mb-2"><strong>Service Charge:</strong> {{ $demand->service_charge ?? 'N/A' }}</p>
                            @if($demand->certificate_special_requirements)
                            <p class="card-value small"><strong>Special Requirements:</strong><br>
                                <span class="text-muted">{{ $demand->certificate_special_requirements }}</span>
                            </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Selection Types -->
            @if(($demand->mode_of_selection && count($demand->mode_of_selection) > 0) || ($demand->type_of_hiring && count($demand->type_of_hiring) > 0))
            <div class="row mt-3">
                @if($demand->mode_of_selection && count($demand->mode_of_selection) > 0)
                <div class="col-md-6">
                    <div class="compact-card">
                        <div class="card-icon">
                            <i class="mdi mdi-format-list-checks"></i>
                        </div>
                        <div class="card-content">
                            <div class="card-label">Mode of Selection</div>
                            <div class="mt-2">
                                @foreach($demand->mode_of_selection as $mode)
                                <span class="keyword-tag">{{ $mode }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                
                @if($demand->type_of_hiring && count($demand->type_of_hiring) > 0)
                <div class="col-md-6">
                    <div class="compact-card">
                        <div class="card-icon">
                            <i class="mdi mdi-briefcase-check"></i>
                        </div>
                        <div class="card-content">
                            <div class="card-label">Type of Hiring</div>
                            <div class="mt-2">
                                @foreach($demand->type_of_hiring as $type)
                                <span class="keyword-tag">{{ $type }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            @endif

            <!-- Advertisement -->
            <div class="section-header mt-4">
                <div class="section-icon">
                    <i class="mdi mdi-bullhorn"></i>
                </div>
                <h4 class="section-title">Advertisement</h4>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="compact-card">
                        <div class="card-icon">
                            <i class="mdi mdi-file-document"></i>
                        </div>
                        <div class="card-content">
                            <div class="card-label">Required CVs</div>
                            <h4 class="card-value">{{ $demand->required_cvs ?? 'N/A' }}</h4>
                        </div>
                    </div>
                </div>
                @if($demand->advertisement_portals && count($demand->advertisement_portals) > 0)
                <div class="col-md-8">
                    <div class="compact-card">
                        <div class="card-icon">
                            <i class="mdi mdi-web"></i>
                        </div>
                        <div class="card-content">
                            <div class="card-label">Advertisement Portals</div>
                            <div class="mt-2">
                                @foreach($demand->advertisement_portals as $portal)
                                @if($portal == 'Social Media')
                                <span class="portal-tag social"><i class="mdi mdi-facebook"></i>{{ $portal }}</span>
                                @elseif($portal == 'Print Media')
                                <span class="portal-tag print"><i class="mdi mdi-newspaper"></i>{{ $portal }}</span>
                                @elseif($portal == 'Local Job Portals')
                                <span class="portal-tag local"><i class="mdi mdi-map-marker"></i>{{ $portal }}</span>
                                @elseif($portal == 'Online Portals')
                                <span class="portal-tag online"><i class="mdi mdi-laptop"></i>{{ $portal }}</span>
                                @else
                                <span class="portal-tag"><i class="mdi mdi-earth"></i>{{ $portal }}</span>
                                @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Additional Information -->
            @if($demand->other_information)
            <div class="section-header mt-4">
                <div class="section-icon">
                    <i class="mdi mdi-note-text"></i>
                </div>
                <h4 class="section-title">Additional Information</h4>
            </div>
            
            <div class="additional-info">
                {{ $demand->other_information }}
            </div>
            @endif

            <!-- Timeline -->
            <div class="section-header mt-4">
                <div class="section-icon">
                    <i class="mdi mdi-timeline-clock"></i>
                </div>
                <h4 class="section-title">Timeline</h4>
            </div>
            
            <div class="compact-timeline">
                @if($demand->demand_open_date)
                <div class="timeline-item">
                    <div class="timeline-date">Demand Opened</div>
                    <div class="timeline-text">{{ $demand->demand_open_date->format('d M, Y') }}</div>
                </div>
                @endif
                <div class="timeline-item">
                    <div class="timeline-date">Created</div>
                    <div class="timeline-text">{{ $demand->created_at->format('d M, Y h:i A') }}</div>
                </div>
                @if($demand->interview_date)
                <div class="timeline-item">
                    <div class="timeline-date">Interview Scheduled</div>
                    <div class="timeline-text">{{ $demand->interview_date->format('d M, Y') }}</div>
                </div>
                @endif
                @if($demand->demand_closed_date)
                <div class="timeline-item">
                    <div class="timeline-date">Demand Closed</div>
                    <div class="timeline-text">{{ $demand->demand_closed_date->format('d M, Y') }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Share functionality
        document.getElementById('shareBtn').addEventListener('click', function() {
            if (navigator.share) {
                navigator.share({
                    title: '{{ $demand->designation_offered }} - Demand Details',
                    text: 'Check out this job demand for {{ $demand->designation_offered }}',
                    url: window.location.href
                });
            } else {
                // Fallback: Copy to clipboard
                navigator.clipboard.writeText(window.location.href).then(() => {
                    alert('Link copied to clipboard!');
                });
            }
        });

        // Animate cards on scroll
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate__animated', 'animate__fadeInUp');
                }
            });
        }, { threshold: 0.1 });

        // Observe all compact cards
        document.querySelectorAll('.compact-card').forEach(card => {
            observer.observe(card);
        });
    });
</script>
@endsection

@endsection