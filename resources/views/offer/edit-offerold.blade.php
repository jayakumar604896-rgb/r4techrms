@extends('layouts.app')

@section('title', 'Edit Offer Details')

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

    .form-check-input:checked {
        background-color: #6C63FF;
        border-color: #6C63FF;
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
        border: 1px solid #ddd;
    }

    .btn-light:hover {
        background: #e9e9e9;
    }

    .btn-danger {
        background: linear-gradient(90deg, #dc3545, #e74c3c) !important;
        border: none !important;
        padding: 12px 25px;
        border-radius: 8px;
        font-weight: 600;
        transition: .2s;
    }

    .btn-danger:hover {
        opacity: .9;
        transform: scale(1.03);
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
        Alert styling
    ----------------------------*/
    .alert {
        border-radius: 8px;
        border: none;
        margin-bottom: 20px;
    }
</style>

<div class="content-wrapper">

    <div class="page-header mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h3 class="page-title">Edit Offer Details</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 mt-2">
                    <li class="breadcrumb-item">
                        <a href="{{ route('offer.index') }}">Offers</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('offer.view', $offer->id) }}">View Offer</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Offer</li>
                </ol>
            </nav>
        </div>
        <div>
            <form action="{{ route('offer.delete', $offer->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this offer? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash me-1"></i> Delete Offer
                </button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <h4 class="card-title">Edit Offer & Demand Details</h4>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('offer.update', $offer) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Section 1: Basic Information -->
                <div class="form-section">
                    <h5 class="section-title">Basic Information</h5>
                    <div class="row g-4">
                        <!-- User Type -->
                        <div class="col-md-6">
                            <label>User Type <span class="required-star">*</span></label>
                            <select name="user_type" class="form-select" required>
                                <option value="">Select Type</option>
                                <option value="Domestic" {{ old('user_type', $offer->user_type) == 'Domestic' ? 'selected' : '' }}>Domestic</option>
                                <option value="International" {{ old('user_type', $offer->user_type) == 'International' ? 'selected' : '' }}>International</option>
                            </select>
                        </div>

                        <!-- Demand Ref No. -->
                        <div class="col-md-6">
                            <label>Demand Reference Number</label>
                            <input type="text" name="demand_ref_no" class="form-control" value="{{ old('demand_ref_no', $offer->demand_ref_no) }}">
                        </div>

                        <!-- Demand Open Date -->
                        <div class="col-md-6">
                            <label>Demand Open Date</label>
                            <input type="date" name="demand_open_date" class="form-control" value="{{ old('demand_open_date', $offer->demand_open_date ? $offer->demand_open_date->format('Y-m-d') : '') }}">
                        </div>

                        <!-- Demand Closed Date -->
                        <div class="col-md-6">
                            <label>Demand Closed Date</label>
                            <input type="date" name="demand_closed_date" class="form-control" value="{{ old('demand_closed_date', $offer->demand_closed_date ? $offer->demand_closed_date->format('Y-m-d') : '') }}">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Position Details -->
                <div class="form-section">
                    <h5 class="section-title">Position Details</h5>
                    <div class="row g-4">
                        <!-- Recruiter Name -->
                        <div class="col-md-6">
                            <label>Recruiter Name <span class="required-star">*</span></label>
                            <input type="text" name="recruiter_name" class="form-control" value="{{ old('recruiter_name', $offer->recruiter_name) }}" required>
                        </div>

                        <!-- Post/Designation -->
                        <div class="col-md-6">
                            <label>Designation/Post <span class="required-star">*</span></label>
                            <input type="text" name="designation_offered" class="form-control" value="{{ old('designation_offered', $offer->designation_offered) }}" required>
                        </div>

                        <!-- Client/Agent/Source -->
                        <div class="col-md-6">
                            <label>Client / Agent / Source</label>
                            <input type="text" name="client_agent_source" class="form-control" value="{{ old('client_agent_source', $offer->client_agent_source) }}">
                        </div>

                        <!-- Number of Positions -->
                        <div class="col-md-6">
                            <label>Number of Positions <span class="required-star">*</span></label>
                            <input type="number" name="no_of_positions" class="form-control" value="{{ old('no_of_positions', $offer->no_of_positions) }}" min="1" required>
                        </div>

                        <!-- Qualification -->
                        <div class="col-md-4">
                            <label>Qualification <span class="required-star">*</span></label>
                            <input type="text" name="qualification" class="form-control" value="{{ old('qualification', $offer->qualification) }}" required>
                        </div>

                        <!-- Experience -->
                        <div class="col-md-4">
                            <label>Experience <span class="required-star">*</span></label>
                            <input type="text" name="experience" class="form-control" value="{{ old('experience', $offer->experience) }}" required>
                        </div>

                        <!-- Job Location -->
                        <div class="col-md-4">
                            <label>Job Location <span class="required-star">*</span></label>
                            <input type="text" name="job_location" class="form-control" value="{{ old('job_location', $offer->job_location) }}" required>
                        </div>

                        <!-- Industry/Sector -->
                        <div class="col-md-6">
                            <label>Industry/Sector <span class="required-star">*</span></label>
                            <input type="text" name="industry" class="form-control" value="{{ old('industry', $offer->industry) }}" required>
                        </div>

                        <!-- Candidate Location -->
                        <div class="col-md-6">
                            <label>Preferred Candidate Location</label>
                            <input type="text" name="candidate_location" class="form-control" value="{{ old('candidate_location', $offer->candidate_location) }}" placeholder="e.g., Pune, Gujarat, TN">
                        </div>
                    </div>
                </div>

                <!-- Section 3: Interview & Selection Details -->
                <div class="form-section">
                    <h5 class="section-title">Interview & Selection Details</h5>
                    <div class="row g-4">
                        <!-- Mode of Interview -->
                        <div class="col-md-6">
                            <label>Mode of Interview</label>
                            <select name="mode_of_interview" class="form-select">
                                <option value="">Select Mode</option>
                                <option value="CV selection" {{ old('mode_of_interview', $offer->mode_of_interview) == 'CV selection' ? 'selected' : '' }}>CV Selection</option>
                                <option value="Direct Interview" {{ old('mode_of_interview', $offer->mode_of_interview) == 'Direct Interview' ? 'selected' : '' }}>Direct Interview</option>
                                <option value="Telephone Interview" {{ old('mode_of_interview', $offer->mode_of_interview) == 'Telephone Interview' ? 'selected' : '' }}>Telephone Interview</option>
                                <option value="Video Call" {{ old('mode_of_interview', $offer->mode_of_interview) == 'Video Call' ? 'selected' : '' }}>Video Call</option>
                            </select>
                        </div>

                        <!-- Mode of Selection -->
                        <div class="col-md-6">
                            <label>Mode of Selection</label>
                            <div class="checkbox-group">
                                @php
                                    $modeSelection = is_array($offer->mode_of_selection) ? $offer->mode_of_selection : [];
                                    $oldModeSelection = old('mode_of_selection', []);
                                    $currentModeSelection = !empty($oldModeSelection) ? $oldModeSelection : $modeSelection;
                                @endphp
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="mode_of_selection[]" value="CV selection" id="cvSelection" {{ in_array('CV selection', $currentModeSelection) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="cvSelection">CV Selection</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="mode_of_selection[]" value="Direct Interview" id="directInterview" {{ in_array('Direct Interview', $currentModeSelection) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="directInterview">Direct Interview</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="mode_of_selection[]" value="Telephone Interview" id="telephoneInterview" {{ in_array('Telephone Interview', $currentModeSelection) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="telephoneInterview">Telephone Interview</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="mode_of_selection[]" value="Video Call" id="videoCall" {{ in_array('Video Call', $currentModeSelection) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="videoCall">Video Call</label>
                                </div>
                            </div>
                        </div>

                        <!-- Interview Date -->
                        <div class="col-md-6">
                            <label>Interview Date</label>
                            <input type="date" name="interview_date" class="form-control" value="{{ old('interview_date', $offer->interview_date ? $offer->interview_date->format('Y-m-d') : '') }}">
                        </div>

                        <!-- Interview Location -->
                        <div class="col-md-6">
                            <label>Interview Location</label>
                            <input type="text" name="interview_location" class="form-control" value="{{ old('interview_location', $offer->interview_location) }}">
                        </div>

                        <!-- Type of Hiring -->
                        <div class="col-md-12">
                            <label>Type of Hiring</label>
                            <div class="checkbox-group">
                                @php
                                    $typeHiring = is_array($offer->type_of_hiring) ? $offer->type_of_hiring : [];
                                    $oldTypeHiring = old('type_of_hiring', []);
                                    $currentTypeHiring = !empty($oldTypeHiring) ? $oldTypeHiring : $typeHiring;
                                @endphp
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="type_of_hiring[]" value="Bulk Hiring" id="bulkHiring" {{ in_array('Bulk Hiring', $currentTypeHiring) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="bulkHiring">Bulk Hiring</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="type_of_hiring[]" value="Labour & Technician Cadre" id="labourTech" {{ in_array('Labour & Technician Cadre', $currentTypeHiring) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="labourTech">Labour & Technician Cadre</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="type_of_hiring[]" value="Executive Staffing" id="executiveStaffing" {{ in_array('Executive Staffing', $currentTypeHiring) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="executiveStaffing">Executive Staffing</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="type_of_hiring[]" value="Contract Staffing" id="contractStaffing" {{ in_array('Contract Staffing', $currentTypeHiring) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="contractStaffing">Contract Staffing</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="type_of_hiring[]" value="Global Hiring" id="globalHiring" {{ in_array('Global Hiring', $currentTypeHiring) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="globalHiring">Global Hiring</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 4: Compensation & Benefits -->
                <div class="form-section">
                    <h5 class="section-title">Compensation & Benefits</h5>
                    <div class="row g-4">
                        <!-- Salary Offered -->
                        <div class="col-md-4">
                            <label>Salary Offered <span class="required-star">*</span></label>
                            <input type="text" name="salary_offered" class="form-control" value="{{ old('salary_offered', $offer->salary_offered) }}" required>
                        </div>

                        <!-- Allowance Offered -->
                        <div class="col-md-4">
                            <label>Allowance Offered</label>
                            <input type="text" name="allowance_offered" class="form-control" value="{{ old('allowance_offered', $offer->allowance_offered) }}">
                        </div>

                        <!-- Total Salary -->
                        <div class="col-md-4">
                            <label>Total Salary Per Month <span class="required-star">*</span></label>
                            <input type="text" name="total_salary" class="form-control" value="{{ old('total_salary', $offer->total_salary) }}" required>
                        </div>

                        <!-- Food -->
                        <div class="col-md-4">
                            <label>Food <span class="required-star">*</span></label>
                            <input type="text" name="food" class="form-control" value="{{ old('food', $offer->food) }}" required>
                        </div>

                        <!-- Accommodation & Transport -->
                        <div class="col-md-4">
                            <label>Accommodation & Transport</label>
                            <input type="text" name="accommodation_transport" class="form-control" value="{{ old('accommodation_transport', $offer->accommodation_transport) }}">
                        </div>

                        <!-- Accommodation (separate field) -->
                        <div class="col-md-4">
                            <label>Accommodation <span class="required-star">*</span></label>
                            <input type="text" name="accommodation" class="form-control" value="{{ old('accommodation', $offer->accommodation) }}" required>
                        </div>

                        <!-- Working Hours -->
                        <div class="col-md-4">
                            <label>Working Hours</label>
                            <input type="text" name="working_hours" class="form-control" value="{{ old('working_hours', $offer->working_hours) }}">
                        </div>

                        <!-- Overtime -->
                        <div class="col-md-4">
                            <label>Overtime</label>
                            <input type="text" name="overtime" class="form-control" value="{{ old('overtime', $offer->overtime) }}">
                        </div>

                        <!-- Service Charge -->
                        <div class="col-md-4">
                            <label>Service Charge</label>
                            <input type="text" name="service_charge" class="form-control" value="{{ old('service_charge', $offer->service_charge) }}">
                        </div>
                    </div>
                </div>

                <!-- Section 5: Employment Details -->
                <div class="form-section">
                    <h5 class="section-title">Employment Details</h5>
                    <div class="row g-4">
                        <!-- Employer Name -->
                        <div class="col-md-6">
                            <label>Name of Employer <span class="required-star">*</span></label>
                            <input type="text" name="employer_name" class="form-control" value="{{ old('employer_name', $offer->employer_name) }}" required>
                        </div>

                        <!-- Type of Employment -->
                        <div class="col-md-6">
                            <label>Type of Employment <span class="required-star">*</span></label>
                            <select name="employment_type" class="form-select" required>
                                <option value="">Select Type</option>
                                <option value="Contract" {{ old('employment_type', $offer->employment_type) == 'Contract' ? 'selected' : '' }}>Contract (Minimum 2 Years)</option>
                                <option value="Contract International" {{ old('employment_type', $offer->employment_type) == 'Contract International' ? 'selected' : '' }}>Contract (International)</option>
                                <option value="Regular Domestic" {{ old('employment_type', $offer->employment_type) == 'Regular Domestic' ? 'selected' : '' }}>Regular (Domestic)</option>
                                <option value="Permanent" {{ old('employment_type', $offer->employment_type) == 'Permanent' ? 'selected' : '' }}>Permanent</option>
                                <option value="Temporary" {{ old('employment_type', $offer->employment_type) == 'Temporary' ? 'selected' : '' }}>Temporary</option>
                            </select>
                        </div>

                        <!-- Notice Period -->
                        <div class="col-md-6">
                            <label>Notice Period <span class="required-star">*</span></label>
                            <input type="text" name="notice_period" class="form-control" value="{{ old('notice_period', $offer->notice_period) }}" required>
                        </div>

                        <!-- Air Ticket -->
                        <div class="col-md-6">
                            <label>Onward Air Ticket (International Only)</label>
                            <input type="text" name="air_ticket" class="form-control" value="{{ old('air_ticket', $offer->air_ticket) }}">
                        </div>
                    </div>
                </div>

                <!-- Section 6: Documents & Requirements -->
                <div class="form-section">
                    <h5 class="section-title">Documents & Requirements</h5>
                    <div class="row g-4">
                        <!-- Certificate/Special Requirements -->
                        <div class="col-md-6">
                            <label>Certificate / Special Requirements</label>
                            <input type="text" name="certificate_special_req" class="form-control" value="{{ old('certificate_special_req', $offer->certificate_special_req) }}">
                        </div>

                        <!-- Certificate Attestation -->
                        <div class="col-md-6">
                            <label>Certificate Attestation</label>
                            <input type="text" name="certificate_attestation" class="form-control" value="{{ old('certificate_attestation', $offer->certificate_attestation) }}">
                        </div>

                        <!-- PCC -->
                        <div class="col-md-6">
                            <label>PCC (Police Clearance Certificate)</label>
                            <input type="text" name="pcc" class="form-control" value="{{ old('pcc', $offer->pcc) }}">
                        </div>

                        <!-- Medical Test -->
                        <div class="col-md-6">
                            <label>Medical Test (International Only)</label>
                            <input type="text" name="medical_test" class="form-control" value="{{ old('medical_test', $offer->medical_test) }}">
                        </div>

                        <!-- Passport Status -->
                        <div class="col-md-6">
                            <label>Passport Emigration Status</label>
                            <input type="text" name="passport_status" class="form-control" value="{{ old('passport_status', $offer->passport_status) }}">
                        </div>

                        <!-- Passport Validity -->
                        <div class="col-md-6">
                            <label>Passport Validity</label>
                            <input type="text" name="passport_validity" class="form-control" value="{{ old('passport_validity', $offer->passport_validity) }}">
                        </div>

                        <!-- Willing to Work -->
                        <div class="col-md-6">
                            <label>Willing to Work in the Country</label>
                            <input type="text" name="willing_to_work" class="form-control" value="{{ old('willing_to_work', $offer->willing_to_work) }}">
                        </div>
                    </div>
                </div>

                <!-- Section 7: Advertisement & Media -->
                <div class="form-section">
                    <h5 class="section-title">Advertisement & Media</h5>
                    <div class="row g-4">
                        <!-- Required CVs -->
                        <div class="col-md-6">
                            <label>Required CVs</label>
                            <input type="number" name="required_cvs" class="form-control" value="{{ old('required_cvs', $offer->required_cvs) }}" min="0">
                        </div>

                        <!-- Advertisement Portals -->
                        <div class="col-md-12">
                            <label>Advertisement Portals</label>
                            <div class="checkbox-group">
                                @php
                                    $adPortals = is_array($offer->advertisement_portals) ? $offer->advertisement_portals : [];
                                    $oldAdPortals = old('advertisement_portals', []);
                                    $currentAdPortals = !empty($oldAdPortals) ? $oldAdPortals : $adPortals;
                                @endphp
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="advertisement_portals[]" value="Social Media" id="socialMedia" {{ in_array('Social Media', $currentAdPortals) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="socialMedia">Social Media</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="advertisement_portals[]" value="Print Media" id="printMedia" {{ in_array('Print Media', $currentAdPortals) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="printMedia">Print Media</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="advertisement_portals[]" value="Local Job Portals" id="localPortals" {{ in_array('Local Job Portals', $currentAdPortals) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="localPortals">Local Job Portals</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="advertisement_portals[]" value="Online Portals" id="onlinePortals" {{ in_array('Online Portals', $currentAdPortals) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="onlinePortals">Online Portals</label>
                                </div>
                            </div>
                        </div>

                        <!-- Other Information -->
                        <div class="col-md-12">
                            <label>Any Other Information to Convey</label>
                            <textarea name="other_information" class="form-control" rows="3">{{ old('other_information', $offer->other_information) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="mt-4 text-end">
                    <a href="{{ route('offer.index') }}" class="btn btn-light me-2">Cancel</a>
                    <a href="{{ route('offer.view', $offer->id) }}" class="btn btn-info me-2">
                        <i class="fas fa-eye me-1"></i> View
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Update Offer
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

<script>
    // JavaScript for form handling
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle international-specific fields based on user type
        const userTypeSelect = document.querySelector('select[name="user_type"]');
        const internationalFields = [
            'air_ticket',
            'medical_test',
            'passport_status',
            'passport_validity',
            'willing_to_work'
        ];

        function toggleInternationalFields() {
            const isInternational = userTypeSelect.value === 'International';
            
            internationalFields.forEach(fieldName => {
                const field = document.querySelector(`input[name="${fieldName}"]`);
                if (field) {
                    const parentDiv = field.closest('.col-md-6');
                    if (parentDiv) {
                        parentDiv.style.display = isInternational ? 'block' : 'none';
                    }
                }
            });
        }

        // Initial toggle
        toggleInternationalFields();

        // Add event listener for changes
        if (userTypeSelect) {
            userTypeSelect.addEventListener('change', toggleInternationalFields);
        }

        // Form validation before submit
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        isValid = false;
                        field.classList.add('is-invalid');
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    alert('Please fill in all required fields marked with *.');
                }
            });
        }
    });
</script>

@endsection