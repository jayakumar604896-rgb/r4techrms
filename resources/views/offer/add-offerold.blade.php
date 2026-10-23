@extends('layouts.app')

@section('title', 'Add Offer Details')

@section('content')

<style>
    /* ---------------------------
       Global Professional Styling
    ----------------------------*/
    .page-header h3 {
        font-weight: 700;
        font-size: 26px;
        color: #3A3A8B;
    }

    .breadcrumb-item a {
        color: #4B49AC !important;
        font-weight: 600;
    }

    .breadcrumb-item.active {
        font-weight: 600;
        color: #6C63FF !important;
    }

    /* ---------------------------
       Card Styling
    ----------------------------*/
    .card {
        border-radius: 14px;
        border: none;
        padding-top: 10px;
        background: #ffffff;
        box-shadow: 0px 8px 30px rgba(0,0,0,0.06);
    }

    .card-title {
        font-weight: 700;
        font-size: 22px;
        padding-left: 15px;
        border-left: 4px solid #6C63FF;
        color: #2b2b2b;
        margin-bottom: 25px;
    }

    .section-title {
        font-weight: 700;
        font-size: 18px;
        color: #4B49AC;
        margin: 25px 0 15px 0;
        padding-bottom: 8px;
        border-bottom: 2px solid #f0f0f0;
    }

    /* ---------------------------
       Labels & Form Elements
    ----------------------------*/
    label {
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
        font-size: 0.9rem;
    }

    .form-control,
    .form-select {
        padding: 0.80rem 1rem !important;
        height: 46px !important;
        border-radius: 8px !important;
        border: 1px solid #d4d4d4 !important;
        font-size: 0.95rem !important;
        background-color: #fff !important;
        transition: all .2s ease-in-out;
    }

    .form-select {
        background-position: right 0.75rem center !important;
        color: #000 !important;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #6C63FF !important;
        box-shadow: 0 0 0 0.2rem rgba(108, 99, 255, 0.25) !important;
    }

    textarea.form-control {
        height: auto !important;
        min-height: 120px;
    }

    /* ---------------------------
       Checkbox & Radio Styling
    ----------------------------*/
    .form-check-label {
        font-weight: 500;
        color: #555;
    }

    .checkbox-group {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .checkbox-group .form-check {
        margin-right: 20px;
        display: inline-block;
    }

    /* ---------------------------
        Buttons
    ----------------------------*/
    .btn-primary {
        background: linear-gradient(90deg, #4B49AC, #6C63FF) !important;
        border: none !important;
        padding: 12px 25px;
        border-radius: 8px;
        font-weight: 600;
        letter-spacing: .3px;
        transition: .2s;
    }

    .btn-primary:hover {
        opacity: .9;
        transform: scale(1.03);
    }

    .btn-light {
        padding: 12px 25px;
        border-radius: 8px;
        font-weight: 600;
        background: #f5f5f5;
    }

    .btn-light:hover {
        background: #e9e9e9;
    }
    .form-check .form-check-label {
         margin-right: 1.75rem; 
         margin-left: 0 !important; 
    }

    /* ---------------------------
        Required field indicator
    ----------------------------*/
    .required-star {
        color: #dc3545;
        font-weight: bold;
    }

    /* ---------------------------
        Form sections
    ----------------------------*/
    .form-section {
        margin-bottom: 30px;
        padding: 20px;
        background: #f9f9ff;
        border-radius: 10px;
        border-left: 4px solid #6C63FF;
    }

    /* ---------------------------
        Keywords Section
    ----------------------------*/
    .keywords-section {
        background: #f0f7ff;
        padding: 20px;
        border-radius: 10px;
        margin-top: 15px;
    }
    
    .keywords-section h6 {
        font-weight: 700;
        color: #4B49AC;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e0e7ff;
    }
    
    .keywords-row {
        margin-bottom: 15px;
    }
    
    .keywords-row:last-child {
        margin-bottom: 0;
    }
</style>

<div class="content-wrapper">

    <div class="page-header mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h3 class="page-title">Add Offer Details</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 mt-2">
                    <li class="breadcrumb-item">
                        <a href="{{ route('offer.index') }}">Offers</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Add Offer</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <h4 class="card-title">Offer & Demand Details</h4>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('offer.store') }}" method="POST">
                @csrf

                <!-- Section 1: Basic Information -->
                <div class="form-section">
                    <h5 class="section-title">Basic Information</h5>
                    <div class="row g-4">
                        <!-- User Type -->
                        <div class="col-md-3">
                            <label>User Type <span class="required-star">*</span></label>
                            <select name="user_type" class="form-select" required>
                                <option value="">Select Type</option>
                                <option value="Domestic" {{ old('user_type')=='Domestic'?'selected':'' }}>Domestic</option>
                                <option value="International" {{ old('user_type')=='International'?'selected':'' }}>International</option>
                            </select>
                        </div>

                        <!-- Demand Ref No. -->
                        <div class="col-md-3">
                            <label>Demand Reference Number</label>
                            <input type="text" name="demand_ref_no" class="form-control" value="{{ old('demand_ref_no') }}">
                        </div>

                        <!-- Demand Open Date -->
                        <div class="col-md-3">
                            <label>Demand Open Date</label>
                            <input type="date" name="demand_open_date" class="form-control" value="{{ old('demand_open_date') }}">
                        </div>

                        <!-- Demand Closed Date -->
                        <div class="col-md-3">
                            <label>Demand Closed Date</label>
                            <input type="date" name="demand_closed_date" class="form-control" value="{{ old('demand_closed_date') }}">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Position Details -->
                <div class="form-section">
                    <h5 class="section-title">Position Details</h5>
                    <div class="row g-4">
                        <!-- Recruiter Name -->
                        <div class="col-md-3">
                            <label>Recruiter Name <span class="required-star">*</span></label>
                            <input type="text" name="recruiter_name" class="form-control" value="{{ old('recruiter_name') }}" required>
                        </div>

                        <!-- Post/Designation -->
                        <div class="col-md-3">
                            <label>Designation/Post <span class="required-star">*</span></label>
                            <input type="text" name="designation_offered" class="form-control" value="{{ old('designation_offered') }}" required>
                        </div>

                        <!-- Client/Agent/Source -->
                        <div class="col-md-3">
                            <label>Client / Agent / Source</label>
                            <input type="text" name="client_agent_source" class="form-control" value="{{ old('client_agent_source') }}">
                        </div>

                        <!-- Number of Positions -->
                        <div class="col-md-3">
                            <label>Number of Positions <span class="required-star">*</span></label>
                            <input type="number" name="no_of_positions" class="form-control" value="{{ old('no_of_positions', 1) }}" min="1" required>
                        </div>

                        <!-- Qualification -->
                        <div class="col-md-4">
                            <label>Qualification <span class="required-star">*</span></label>
                            <input type="text" name="qualification" class="form-control" value="{{ old('qualification') }}" required>
                        </div>

                        <!-- Experience -->
                        <div class="col-md-4">
                            <label>Experience <span class="required-star">*</span></label>
                            <input type="text" name="experience" class="form-control" value="{{ old('experience') }}" required>
                        </div>

                        <!-- Job Location -->
                        <div class="col-md-4">
                            <label>Job Location <span class="required-star">*</span></label>
                            <input type="text" name="job_location" class="form-control" value="{{ old('job_location') }}" required>
                        </div>

                        <!-- Industry/Sector -->
                        <div class="col-md-3">
                            <label>Industry/Sector <span class="required-star">*</span></label>
                            <input type="text" name="industry" class="form-control" value="{{ old('industry') }}" required>
                        </div>

                        <!-- Candidate Location -->
                        <div class="col-md-3">
                            <label>Preferred Candidate Location</label>
                            <input type="text" name="candidate_location" class="form-control" value="{{ old('candidate_location') }}" placeholder="e.g., Pune, Gujarat, TN">
                        </div>
                    </div>
                </div>

                <!-- Section 3: Keywords Section -->
                <div class="form-section">
                    <h5 class="section-title">Keywords to Check</h5>
                    
                    <div class="keywords-section">
                        <h6>A. Main Keywords to Check</h6>
                        <div class="row g-4 keywords-row">
                            <!-- Design -->
                            <div class="col-md-3">
                                <label>Design</label>
                                <input type="text" name="main_keyword_design" class="form-control" 
                                       value="{{ old('main_keyword_design') }}" 
                                       placeholder="e.g., Software Engineer">
                            </div>
                            
                            <!-- Qualification -->
                            <div class="col-md-3">
                                <label>Qualification (Qln)</label>
                                <input type="text" name="main_keyword_qualification" class="form-control" 
                                       value="{{ old('main_keyword_qualification') }}" 
                                       placeholder="e.g., B.Tech, MBA">
                            </div>
                            
                            <!-- Experience -->
                            <div class="col-md-3">
                                <label>Experience (Exp)</label>
                                <input type="text" name="main_keyword_experience" class="form-control" 
                                       value="{{ old('main_keyword_experience') }}" 
                                       placeholder="e.g., 3-5 years">
                            </div>
                            
                            <!-- Special Requirements -->
                            <div class="col-md-3">
                                <label>Special Requirements (Spl. Req)</label>
                                <input type="text" name="main_keyword_special_requirements" class="form-control" 
                                       value="{{ old('main_keyword_special_requirements') }}" 
                                       placeholder="e.g., Certifications, Skills">
                            </div>
                        </div>
                    </div>

                    <div class="keywords-section mt-4">
                        <h6>B. Secondary Keywords to Check</h6>
                        <div class="row g-4 keywords-row">
                            <!-- Notice -->
                            <div class="col-md-3">
                                <label>1. Notice Period</label>
                                <input type="text" name="secondary_keyword_notice" class="form-control" 
                                       value="{{ old('secondary_keyword_notice') }}" 
                                       placeholder="e.g., 30 days">
                            </div>
                            
                            <!-- Salary Budget -->
                            <div class="col-md-3">
                                <label>2. Salary Budget</label>
                                <input type="text" name="secondary_keyword_salary_budget" class="form-control" 
                                       value="{{ old('secondary_keyword_salary_budget') }}" 
                                       placeholder="e.g., 8-12 LPA">
                            </div>
                            
                            <!-- Additional 3 -->
                            <div class="col-md-3">
                                <label>3. Additional Requirement</label>
                                <input type="text" name="secondary_keyword_additional_3" class="form-control" 
                                       value="{{ old('secondary_keyword_additional_3') }}" 
                                       placeholder="Additional requirement">
                            </div>
                            
                            <!-- Additional 4 -->
                            <div class="col-md-3">
                                <label>4. Additional Requirement</label>
                                <input type="text" name="secondary_keyword_additional_4" class="form-control" 
                                       value="{{ old('secondary_keyword_additional_4') }}" 
                                       placeholder="Additional requirement">
                            </div>
                        </div>
                    </div>

                    <!-- Additional Info Row -->
                    <div class="row g-4 mt-4">
                        <div class="col-md-3">
                            <label>Sector / Industry</label>
                            <input type="text" name="sector_industry" class="form-control" 
                                   value="{{ old('sector_industry') }}" 
                                   placeholder="e.g., IT, Manufacturing, Healthcare">
                        </div>
                        
                        <div class="col-md-3">
                            <label>Candidate Location</label>
                            <input type="text" name="candidate_location_requirement" class="form-control" 
                                   value="{{ old('candidate_location_requirement') }}" 
                                   placeholder="e.g., Pune, Gujarat, TN">
                        </div>
                    </div>
                </div>

                <!-- Section 4: Interview & Selection Details -->
                <div class="form-section">
                    <h5 class="section-title">Interview & Selection Details</h5>
                    <div class="row g-4">
                        <!-- Mode of Interview -->
                        <div class="col-md-3">
                            <label>Mode of Interview</label>
                            <select name="mode_of_interview" class="form-select">
                                <option value="">Select Mode</option>
                                <option value="CV selection" {{ old('mode_of_interview')=='CV selection'?'selected':'' }}>CV Selection</option>
                                <option value="Direct Interview" {{ old('mode_of_interview')=='Direct Interview'?'selected':'' }}>Direct Interview</option>
                                <option value="Telephone Interview" {{ old('mode_of_interview')=='Telephone Interview'?'selected':'' }}>Telephone Interview</option>
                                <option value="Video Call" {{ old('mode_of_interview')=='Video Call'?'selected':'' }}>Video Call</option>
                            </select>
                        </div>

                        <!-- Mode of Selection -->
                        <div class="col-md-3">
                            <label>Mode of Selection</label>
                            <div class="checkbox-group">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="mode_of_selection[]" value="CV selection" id="cvSelection">
                                    <label class="form-check-label" for="cvSelection">CV Selection</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="mode_of_selection[]" value="Direct Interview" id="directInterview">
                                    <label class="form-check-label" for="directInterview">Direct Interview</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="mode_of_selection[]" value="Telephone Interview" id="telephoneInterview">
                                    <label class="form-check-label" for="telephoneInterview">Telephone Interview</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="mode_of_selection[]" value="Video Call" id="videoCall">
                                    <label class="form-check-label" for="videoCall">Video Call</label>
                                </div>
                            </div>
                        </div>

                        <!-- Interview Date -->
                        <div class="col-md-3">
                            <label>Interview Date</label>
                            <input type="date" name="interview_date" class="form-control" value="{{ old('interview_date') }}">
                        </div>

                        <!-- Interview Location -->
                        <div class="col-md-3">
                            <label>Interview Location</label>
                            <input type="text" name="interview_location" class="form-control" value="{{ old('interview_location') }}">
                        </div>

                        <!-- Type of Hiring -->
                        <div class="col-md-12">
                            <label>Type of Hiring</label>
                            <div class="checkbox-group">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="type_of_hiring[]" value="Bulk Hiring" id="bulkHiring">
                                    <label class="form-check-label" for="bulkHiring">Bulk Hiring</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="type_of_hiring[]" value="Labour & Technician Cadre" id="labourTech">
                                    <label class="form-check-label" for="labourTech">Labour & Technician Cadre</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="type_of_hiring[]" value="Executive Staffing" id="executiveStaffing">
                                    <label class="form-check-label" for="executiveStaffing">Executive Staffing</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="type_of_hiring[]" value="Contract Staffing" id="contractStaffing">
                                    <label class="form-check-label" for="contractStaffing">Contract Staffing</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="type_of_hiring[]" value="Global Hiring" id="globalHiring">
                                    <label class="form-check-label" for="globalHiring">Global Hiring</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 5: Advertisement & Media -->
                <div class="form-section">
                    <h5 class="section-title">Advertisement & Media</h5>
                    <div class="row g-4">
                        <!-- Required CVs -->
                        <div class="col-md-3">
                            <label>Required CVs</label>
                            <input type="number" name="required_cvs" class="form-control" value="{{ old('required_cvs') }}" min="0">
                        </div>

                        <!-- Advertisement Portals -->
                        <div class="col-md-12">
                            <label>Advertisement Portals</label>
                            <div class="checkbox-group">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="advertisement_portals[]" value="Social Media" id="socialMedia">
                                    <label class="form-check-label" for="socialMedia">Social Media</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="advertisement_portals[]" value="Print Media" id="printMedia">
                                    <label class="form-check-label" for="printMedia">Print Media</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="advertisement_portals[]" value="Local Job Portals" id="localPortals">
                                    <label class="form-check-label" for="localPortals">Local Job Portals</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="advertisement_portals[]" value="Online Portals" id="onlinePortals">
                                    <label class="form-check-label" for="onlinePortals">Online Portals</label>
                                </div>
                            </div>
                        </div>

                        <!-- Other Information -->
                        <div class="col-md-12">
                            <label>Any Other Information to Convey</label>
                            <textarea name="other_information" class="form-control" rows="3">{{ old('other_information') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="mt-4 text-end">
                    <a href="{{ route('offer.index') }}" class="btn btn-light me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Submit Offer Details</button>
                </div>

            </form>

        </div>
    </div>

</div>

@endsection