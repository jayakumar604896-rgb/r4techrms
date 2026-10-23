@extends('layouts.app')

@section('title', 'Add Demand Details')

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
    
    /* Sub Division container – hidden by default */
    .subdivision-container,
    .other-sector-container {
        display: none;
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
            <h3 class="page-title">Add Demand Register Details</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 mt-2">
                    <li class="breadcrumb-item">
                        <a href="{{ route('demands.index') }}"> Demand Register </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Add Demand Register </li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <h4 class="card-title">Demand Register Details</h4>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('demands.store') }}" method="POST">
                @csrf
                @method('post')
                <!-- Section 1: Basic Information -->
                <div class="form-section">
                    <h5 class="section-title">Basic Information</h5>
                    <div class="row g-4">
                        <!-- User Type -->
                        <div class="col-md-3">
                            <label>User Type <span class="required-star">*</span></label>
                            @if(Auth::user()->role === 'admin')
                            <select name="user_type" id="userType" class="form-select">
                                <option value="">Select Type</option>
                                <option value="Domestic" {{ old('user_type')=='Domestic'?'selected':'' }}>Domestic</option>
                                <option value="International" {{ old('user_type')=='International'?'selected':'' }}>International</option>
                            </select>
                            @else
                            <input type="text" name="user_type" id="userType" class="form-control" value="{{ Auth::user()->user_type }}" readonly>
                            @endif
                        </div>

                        <div class="col-md-3 subdivision-container" id="subDivisionContainer">
                            <label>Sub Division <span class="required-star" id="subDivisionStar" style="display: none;">*</span></label>
                            <select name="sub_division" id="subDivisionSelect" class="form-select">
                                <option value="">Select Sub Division</option>
                                <option value="GULF" {{ old('sub_division') == 'GULF' ? 'selected' : '' }}>GULF</option>
                                <option value="SEA" {{ old('sub_division') == 'SEA' ? 'selected' : '' }}>SEA</option>
                                <option value="EUROPE" {{ old('sub_division') == 'EUROPE' ? 'selected' : '' }}>EUROPE</option>
                                <option value="OTHERS" {{ old('sub_division') == 'OTHERS' ? 'selected' : '' }}>OTHERS</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Demand Reference Number</label>
                            <input type="text" name="demand_ref_no" class="form-control" value="{{ old('demand_ref_no') }}">
                        </div>

                        <div class="col-md-3">
                            <label>Demand Open Date</label>
                            <input type="date" name="demand_open_date" class="form-control" value="{{ old('demand_open_date') }}">
                        </div>

                        <div class="col-md-3">
                            <label>Recruiter Name <span class="required-star">*</span></label>
                            <select name="recruiter_id" class="form-select" >
                                <option value="">Select Recruiter</option>
                                @foreach(\App\Models\User::get() as $recruiter)
                                    <option value="{{ $recruiter->id }}" {{ old('recruiter_id') == $recruiter->id ? 'selected' : '' }}>
                                        {{ $recruiter->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h5 class="section-title">Position Details</h5>
                    <div class="row g-4">
                        <div class="col-md-3">
                            <label>Designation/Post <span class="required-star">*</span></label>
                            <input type="text" name="designation_offered" class="form-control" value="{{ old('designation_offered') }}">
                        </div>

                        <div class="col-md-3">
                            <label>Client / Agent / Source</label>
                            <input type="text" name="client_agent_source" class="form-control" value="{{ old('client_agent_source') }}">
                        </div>

                        <div class="col-md-3">
                            <label>Number of Positions <span class="required-star">*</span></label>
                            <input type="number" name="no_of_positions" class="form-control" value="{{ old('no_of_positions', 1) }}" min="1">
                        </div>
						<div class="col-md-3">
                            <label>Required CVs</label>
                            <input type="number" name="required_cvs" class="form-control" value="{{ old('required_cvs') }}" min="0">
                         </div>
                        <div class="col-md-3">
                            <label>Qualification <span class="required-star">*</span></label>
                            <input type="text" name="qualification" class="form-control" value="{{ old('qualification') }}">
                        </div>

                        <div class="col-md-3">
                            <label>Total Experience <span class="required-star">*</span></label>
                            <input type="text" name="experience" class="form-control" value="{{ old('experience') }}">
                        </div>

                        <div class="col-md-3">
                            <label>Job Location <span class="required-star">*</span></label>
                            <input type="text" name="job_location" class="form-control" value="{{ old('job_location') }}">
                        </div>

                        <div class="col-md-3">
                            <label>Industry/Sector <span class="required-star">*</span></label>
                            <select name="sector_id" id="sectorSelect" class="form-select">
                                <option value="">Select Sector/Industry</option>
                                @foreach(\App\Models\Sector::active()->get() as $sector)
                                    <option value="{{ $sector->id }}" {{ old('sector_id') == $sector->id ? 'selected' : '' }}>
                                        {{ $sector->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 other-sector-container" id="otherSectorContainer">
                            <label>Other Sector <span class="required-star">*</span></label>
                            <input type="text" name="other_industry" id="otherSectorInput" class="form-control" 
                                   value="{{ old('other_industry') }}" placeholder="Specify sector">
                        </div>

                        <div class="col-md-3">
                            <label>Preferred Candidate Location</label>
                            <input type="text" name="candidate_location" class="form-control" value="{{ old('candidate_location') }}" placeholder="e.g., Pune, Gujarat, TN">
                        </div>
                    </div>
                    
                    <div class="row g-4 mt-3">
                        <div class="col-md-12">
                            <h6 class="border-bottom pb-2 mb-3 text-primary">Additional Benefits</h6>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="input-group">
                                <span class="input-group-text bg-light">Food</span>
                                <input type="text" name="food" class="form-control" value="{{ old('food') }}" placeholder="e.g., Provided">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="input-group">
                                <span class="input-group-text bg-light">Accom & Transport</span>
                                <input type="text" name="accommodation_transport" class="form-control" value="{{ old('accommodation_transport') }}" placeholder="e.g., Provided">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="input-group">
                                <span class="input-group-text bg-light">Salary</span>
                                <input type="text" name="salary" class="form-control" value="{{ old('salary') }}" placeholder="e.g., ₹30,000">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="input-group">
                                <span class="input-group-text bg-light">Workg Hrs.</span>
                                <input type="text" name="working_hours" class="form-control" value="{{ old('working_hours') }}" placeholder="e.g., 9-6">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="input-group">
                                <span class="input-group-text bg-light">Overtime</span>
                                <input type="text" name="overtime" class="form-control" value="{{ old('overtime') }}" placeholder="e.g., 1.5x">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h5 class="section-title">Keywords to Check</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="keywords-section">
                                <h6>A. Main Keywords to Check</h6>
                                <div class="row g-4 keywords-row">
                                    <div class="col-md-4">
                                        <label>Design</label>
                                        <input type="text" name="main_keyword_design" class="form-control" 
                                            value="{{ old('main_keyword_design') }}" 
                                            placeholder="e.g., Software Engineer">
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label>Qualification (Qln)</label>
                                        <input type="text" name="main_keyword_qualification" class="form-control" 
                                            value="{{ old('main_keyword_qualification') }}" 
                                            placeholder="e.g., B.Tech, MBA">
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label>Experience (Exp)</label>
                                        <input type="text" name="main_keyword_experience" class="form-control" 
                                            value="{{ old('main_keyword_experience') }}" 
                                            placeholder="e.g., 3-5 years">
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label>Special Requirements (Spl. Req)</label>
                                        <input type="text" name="main_keyword_special_requirements" class="form-control" 
                                            value="{{ old('main_keyword_special_requirements') }}" 
                                            placeholder="e.g., Certifications, Skills">
                                    </div>
                                    <div class="col-md-4">
                                        <label>Sector / Industry (Additional)</label>
                                        <input type="text" name="a_sector_industry" class="form-control" 
                                            value="{{ old('a_sector_industry') }}" 
                                            placeholder="e.g., IT, Manufacturing, Healthcare">
                                    </div>
                                
                                    <div class="col-md-4">
                                        <label>Candidate Location (Additional)</label>
                                        <input type="text" name="a_candidate_location_requirement" class="form-control" 
                                            value="{{ old('a_candidate_location_requirement') }}" 
                                            placeholder="e.g., chennai, mylapore">
                                    </div>
                                </div>
                            </div>
                        </div>
                    <div class="col-md-6">
                        <div class="keywords-section mt-4">
                            <h6>B. Secondary Keywords to Check</h6>
                            <div class="row g-4 keywords-row">
                                <div class="col-md-4">
                                    <label>1. Notice Period</label>
                                    <input type="text" name="secondary_keyword_notice" class="form-control" 
                                        value="{{ old('secondary_keyword_notice') }}" 
                                        placeholder="e.g., 30 days">
                                </div>
                                
                                <div class="col-md-4">
                                    <label>2. Salary Budget</label>
                                    <input type="text" name="secondary_keyword_salary_budget" class="form-control" 
                                        value="{{ old('secondary_keyword_salary_budget') }}" 
                                        placeholder="e.g., 8-12 LPA">
                                </div>
                                
                                <div class="col-md-4">
                                    <label>3. Additional Requirement</label>
                                    <input type="text" name="secondary_keyword_additional_3" class="form-control" 
                                        value="{{ old('secondary_keyword_additional_3') }}" 
                                        placeholder="Additional requirement">
                                </div>
                                
                                <div class="col-md-4">
                                    <label>4. Additional Requirement</label>
                                    <input type="text" name="secondary_keyword_additional_4" class="form-control" 
                                        value="{{ old('secondary_keyword_additional_4') }}" 
                                        placeholder="Additional requirement">
                                </div>
                                <div class="col-md-4">
                                    <label>Sector / Industry (Additional)</label>
                                    <input type="text" name="sector_industry" class="form-control" 
                                        value="{{ old('sector_industry') }}" 
                                        placeholder="e.g., IT, Manufacturing, Healthcare">
                                </div>
                                
                                <div class="col-md-4">
                                    <label>Candidate Location (Additional)</label>
                                    <input type="text" name="candidate_location_requirement" class="form-control" 
                                        value="{{ old('candidate_location_requirement') }}" 
                                        placeholder="e.g., chennai, mylapore">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-section">
                            <h5 class="section-title">Interview & Selection Details</h5>
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <label>Mode of Interview</label>
                                    <select name="mode_of_interview" class="form-select">
                                        <option value="">Select Mode</option>
                                        <option value="CV selection" {{ old('mode_of_interview')=='CV selection'?'selected':'' }}>CV Selection</option>
                                        <option value="Direct Interview" {{ old('mode_of_interview')=='Direct Interview'?'selected':'' }}>Direct Interview</option>
                                        <option value="Telephone Interview" {{ old('mode_of_interview')=='Telephone Interview'?'selected':'' }}>Telephone Interview</option>
                                        <option value="Zoom / Video Call" {{ old('mode_of_interview')=='Zoom / Video Call'?'selected':'' }}>Zoom / Video Call</option>
                                        <option value="Return / Practical" {{ old('mode_of_interview')=='Return / Practical'?'selected':'' }}>Return / Practical</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label>Interview Date</label>
                                    <input type="date" name="interview_date" class="form-control" value="{{ old('interview_date') }}">
                                </div>

                                <div class="col-md-4">
                                    <label>Interview Location</label>
                                    <input type="text" name="interview_location" class="form-control" value="{{ old('interview_location') }}">
                                </div>

                                <div class="col-md-4">
                                    <label>Service Charge</label>
                                    <div class="input-group">
                                        <input type="text" name="service_charge" class="form-control" 
                                            value="{{ old('service_charge') }}" 
                                            placeholder="e.g., 8.33%">
                                    </div>
                                </div>

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

                                <div class="col-md-12">
                                    <label>Certificate / Special Requirements</label>
                                    <textarea name="certificate_special_requirements" class="form-control" rows="3" 
                                            placeholder="e.g., Valid passport, Police clearance certificate, specific certifications">{{ old('certificate_special_requirements') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-section">
                            <h5 class="section-title">Advertisement & Media</h5>
                            <div class="row g-4">
                                

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

                                <div class="col-md-12">
                                    <label>Any Other Information to Convey</label>
                                    <textarea name="other_information" class="form-control" rows="3">{{ old('other_information') }}</textarea>
                                </div>

                                <div class="col-md-4">
                                    <label>Demand Closed Date</label>
                                    <input type="date" name="demand_closed_date" class="form-control" value="{{ old('demand_closed_date') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <a href="{{ route('demands.index') }}" class="btn btn-light me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Submit Demand Details</button>
                </div>

            </form>

        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get elements once
    const userTypeSelect = document.getElementById('userType');
    const subDivisionContainer = document.getElementById('subDivisionContainer');
    const subDivisionSelect = document.getElementById('subDivisionSelect');
    const subDivisionStar = document.getElementById('subDivisionStar');
    const demandRefInput = document.querySelector('input[name="demand_ref_no"]');

    // Function to toggle Sub Division visibility
    function toggleSubDivision() {
        let userTypeValue = userTypeSelect ? userTypeSelect.value : null;
        if (!userTypeValue && userTypeSelect && userTypeSelect.tagName === 'INPUT') {
            userTypeValue = userTypeSelect.value;
        }
        if (userTypeValue === 'International') {
            subDivisionContainer.style.display = 'block';
            subDivisionSelect.required = true;
            subDivisionStar.style.display = 'inline';
        } else {
            subDivisionContainer.style.display = 'none';
            subDivisionSelect.required = false;
            subDivisionStar.style.display = 'none';
            subDivisionSelect.value = '';
        }
    }

    // Function to generate Demand Reference Number
    function generateDemandRefNo() {
        let userType = userTypeSelect ? userTypeSelect.value : null;
        let subDivision = subDivisionSelect ? subDivisionSelect.value : null;
        
        if (!userType) {
            if (demandRefInput) demandRefInput.value = '';
            return;
        }
        
        if (demandRefInput) {
            demandRefInput.value = 'Generating...';
        }
        
        fetch('{{ route("demands.generate-ref-no") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                user_type: userType,
                sub_division: subDivision
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (demandRefInput) demandRefInput.value = data.ref_no;
            } else {
                if (demandRefInput) demandRefInput.value = '';
                console.error('Error:', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (demandRefInput) demandRefInput.value = '';
        });
    }

    // Function to toggle Other Sector
    const sectorSelect = document.getElementById('sectorSelect');
    const otherSectorContainer = document.getElementById('otherSectorContainer');
    const otherSectorInput = document.getElementById('otherSectorInput');

    function toggleOtherSector() {
        if (sectorSelect.value === '13') {
            otherSectorContainer.style.display = 'block';
            otherSectorInput.required = true;
        } else {
            otherSectorContainer.style.display = 'none';
            otherSectorInput.required = false;
            otherSectorInput.value = '';
        }
    }

    // Make demand_ref_no field readonly
    if (demandRefInput) {
        demandRefInput.readOnly = true;
        demandRefInput.style.backgroundColor = '#f5f5f5';
    }

    // Add event listeners
    toggleSubDivision();
    toggleOtherSector();

    if (userTypeSelect && userTypeSelect.tagName === 'SELECT') {
        userTypeSelect.addEventListener('change', toggleSubDivision);
        userTypeSelect.addEventListener('change', generateDemandRefNo);
    }

    if (subDivisionSelect) {
        subDivisionSelect.addEventListener('change', generateDemandRefNo);
    }

    if (sectorSelect) {
        sectorSelect.addEventListener('change', toggleOtherSector);
    }

    // Initial generation if values exist
    if (userTypeSelect && userTypeSelect.value) {
        generateDemandRefNo();
    }
});
</script>

@endsection