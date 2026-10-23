@extends('layouts.app')

@section('title', 'Edit Candidate')

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

    /* ---------------------------
       Labels & Form Elements
    ----------------------------*/
    label {
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
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

    .btn-secondary {
        padding: 12px 25px;
        border-radius: 8px;
        font-weight: 600;
        background: #6c757d;
        color: white;
        border: none;
    }

    .btn-secondary:hover {
        background: #5a6268;
        color: white;
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
    
    /* Current Resume Display */
    .current-resume {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 10px 15px;
        margin-top: 10px;
    }
    
    .resume-badge {
        background-color: #e9ecef;
        color: #495057;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    
    /* Emp ID Display */
    .emp-id-display {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 10px 15px;
        font-weight: 600;
        color: #495057;
        margin-bottom: 1rem;
    }
    
    /* Required field indicator */
    .required-star {
        color: #dc3545;
    }
    
    /* Optional field indicator */
    .optional-text {
        color: #6c757d;
        font-size: 0.875em;
    }
    
    /* Experience section styling */
    .experience-section {
        border-left: 3px solid #6C63FF;
        padding-left: 15px;
        margin-top: 25px;
        margin-bottom: 15px;
    }

    .experience-section h6 {
        color: #4B49AC;
        font-weight: 600;
    }
    
    /* International passport section */
    .international-section {
        border-left: 3px solid #FF6B6B;
        padding-left: 15px;
        margin-top: 25px;
        margin-bottom: 15px;
    }

    .international-section h5 {
        color: #FF6B6B;
        font-weight: 600;
    }
    
    /* Auto-calculated fields */
    .auto-calculated {
        background-color: #f8f9fa !important;
        color: #495057 !important;
        cursor: not-allowed;
    }
    
    /* Form validation */
    .is-invalid {
        border-color: #dc3545 !important;
    }
    
    .input-group-text {
        background-color: #f8f9fa;
        border: 1px solid #d4d4d4;
    }
</style>

<div class="content-wrapper">

    <!-- Page Header -->
    <div class="page-header mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h3 class="page-title">Edit Candidate</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 mt-2">
                    <li class="breadcrumb-item">
                        <a href="{{ route('candidates.index') }}">Candidates</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('candidates.show', $candidate->id) }}">{{ $candidate->candidate_name }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Form Card -->
    <div class="row justify-content-center">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">

                    <h4 class="card-title">
                        Edit Candidate: <span class="text-primary">{{ $candidate->candidate_name }}</span>
                        <small class="text-muted">(Fields marked with * are required)</small>
                    </h4>

                    <!-- Success + Error Messages -->
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Start Form -->
                    <form action="{{ route('candidates.update', $candidate->id) }}" method="POST" enctype="multipart/form-data" id="candidateForm">
                        @csrf
                        @method('PUT')

                        <!-- Emp ID Display -->
                        <div class="row g-4 mb-3">
                            <div class="col-md-12">
                                <div class="emp-id-display">
                                    Emp-id: <span class="text-primary">{{ $candidate->emp_code ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Row 1: Basic Info -->
                        <div class="row g-4">
                            <div class="col-md-3 col-12">
                                <label>Candidate Name <span class="required-star">*</span></label>
                                <input type="text" name="candidate_name" class="form-control"
                                       placeholder="Enter Candidate Full Name"
                                       value="{{ old('candidate_name', $candidate->candidate_name) }}" required>
                            </div>
                            <div class="col-md-3 col-12">
                                <label>Code No</label>
                                <input type="text" name="code_no" class="form-control"
                                       placeholder="Enter Code No"
                                       value="{{ old('code_no', $candidate->code_no) }}" >
                            </div>
                            <div class="col-md-3 col-12">
                                <label>Date of Submission <span class="required-star">*</span></label>
                                <input type="date" name="DOS" class="form-control"
                                       value="{{ old('DOS', $candidate->DOS) }}" required>
                            </div>
                            <div class="col-md-3 col-12">
                                <label>Date of Birth</label>
                                <input type="date" name="dob" id="dob" class="form-control"
                                       value="{{ old('dob', $candidate->dob) }}" onchange="calculateAge()">
                            </div>
                        </div>

                        <!-- Row 2: Age, Email, Mobile, Designation -->
                        <div class="row g-4 mt-3">
                            <div class="col-md-3 col-12">
                                <label>Age</label>
                                <input type="number" name="age" id="age" class="form-control"
                                       placeholder="Auto calculated"
                                       value="{{ old('age', $candidate->age) }}" readonly>
                            </div>
                            <div class="col-md-3 col-12">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control"
                                       placeholder="Enter Email"
                                       value="{{ old('email', $candidate->email) }}" >
                            </div>
                            <div class="col-md-3 col-12">
                                <label>Mobile <span class="required-star">*</span></label>
                                <input type="text" name="mobile" class="form-control"
                                       placeholder="Enter Mobile Number"
                                       value="{{ old('mobile', $candidate->mobile) }}" required>
                            </div>
                            <div class="col-md-3 col-12">
                                <label>Designation</label>
                                <input type="text" name="designation" class="form-control"
                                       placeholder="Enter Designation"
                                       value="{{ old('designation', $candidate->designation) }}" >
                            </div>

                        <!-- Row 3: Sector, Other Sector, Qualification, Industry -->
                            <div class="col-md-3 col-12">
                                <label>Sector <span class="required-star">*</span></label>
                                <select name="sector_id" id="sectorSelect" class="form-select" required>
                                    <option value="">Select Sector</option>
                                    @foreach(\App\Models\Sector::active()->get() as $sector)
                                        <option value="{{ $sector->id }}" {{ (old('sector_id', $candidate->sector_id) == $sector->id) ? 'selected' : '' }}>
                                            {{ $sector->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if(\App\Models\Sector::count() == 0)
                                    <small class="text-danger">No sectors found. Please run database setup.</small>
                                @endif
                            </div>
                            
                            <div class="col-md-3 col-12" id="otherSectorWrapper" style="display: {{ (old('sector_id') == 'other' || ($candidate->other_sector && !\App\Models\Sector::find($candidate->sector_id))) ? 'block' : 'none' }};">
                                <label for="other_sector">Specify Other Sector <span class="required-star">*</span></label>
                                <input type="text" 
                                    name="other_sector" 
                                    id="otherSectorInput" 
                                    class="form-control" 
                                    value="{{ old('other_sector', $candidate->other_sector) }}"
                                    {{ (old('sector_id') == 'other' || ($candidate->other_sector && !\App\Models\Sector::find($candidate->sector_id))) ? 'required' : '' }}>
                            </div>
                            
                            <div class="col-md-3 col-12">
                                <label>Qualification</label>
                                <input type="text" name="qualfn" class="form-control"
                                       placeholder="Enter Qualification"
                                       value="{{ old('qualfn', $candidate->qualfn) }}" >
                            </div>
                            
                            <div class="col-md-3 col-12">
                                <label>Industry <span class="required-star">*</span></label>
                                <select name="industry_id" id="industrySelect" class="form-select" required>
                                    <option value="">Select Industry</option>
                                    @foreach(\App\Models\Industry::active()->get() as $industry)
                                        <option value="{{ $industry->id }}" {{ (old('industry_id', $candidate->industry_id) == $industry->id) ? 'selected' : '' }}>
                                            {{ $industry->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if(\App\Models\Industry::count() == 0)
                                    <small class="text-danger">No industries found. Please run database setup.</small>
                                @endif
                            </div>

                        <!-- Row 4: Other Industry, Total Experience, Placement Type -->
                            <div class="col-md-3 col-12" id="otherIndustryWrapper" style="display: {{ (old('industry_id') == 'other' || ($candidate->other_industry && !\App\Models\Industry::find($candidate->industry_id))) ? 'block' : 'none' }};">
                                <label for="other_industry">Specify Other Industry <span class="required-star">*</span></label>
                                <input type="text" 
                                    name="other_industry" 
                                    id="otherIndustryInput" 
                                    class="form-control" 
                                    value="{{ old('other_industry', $candidate->other_industry) }}"
                                    {{ (old('industry_id') == 'other' || ($candidate->other_industry && !\App\Models\Industry::find($candidate->industry_id))) ? 'required' : '' }}>
                            </div>
                            
                            <!--<div class="col-md-3 col-12">
                                <label>Total Experience <span class="required-star">*</span></label>
                                <input type="text" name="total_exp" id="total_exp" class="form-control {{ $candidate->user_type == 'International' ? 'auto-calculated' : '' }}"
                                       placeholder="{{ $candidate->user_type == 'International' ? 'Auto calculated for International' : 'Enter Total Experience' }}"
                                       value="{{ old('total_exp', $candidate->total_exp) }}" 
                                       {{ $candidate->user_type == 'International' ? 'readonly' : '' }}
                                       required>
                            </div>-->
                            
                            <div class="col-md-3 col-12">
                                <label>Placement Type <span class="required-star">*</span></label>
                                <select name="placement_type" class="form-select" required>
                                    <option value="">Select Placement Type</option>
                                    @php
                                        $placementTypes = \App\Models\PlacementType::all();
                                    @endphp
                                    @foreach($placementTypes as $type)
                                        <option value="{{ $type->id }}" {{ (old('placement_type', $candidate->placement_type) == $type->id) ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if($placementTypes->count() == 0)
                                    <small class="text-danger">No placement types found.</small>
                                @endif
                            </div>
                            
                            <div class="col-md-3 col-12">
                                <label>Post & Location Applied</label>
                                <input type="text" name="post_location_applied" class="form-control"
                                       placeholder="Enter Post & Location Applied"
                                       value="{{ old('post_location_applied', $candidate->post_location_applied) }}" >
                            </div>

                        <!-- Row 5: Current Stay, CTC, Notice Period, User Type -->
                            <div class="col-md-3 col-12">
                                <label>Current Stay</label>
                                <input type="text" name="current_stay" class="form-control"
                                       placeholder="Enter Current Stay"
                                       value="{{ old('current_stay', $candidate->current_stay) }}" >
                            </div>
                            
                            <div class="col-md-3 col-12">
                                <label>CTC (Annual)</label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="text" name="ctc" class="form-control"
                                           placeholder="Enter CTC"
                                           value="{{ old('ctc', $candidate->ctc) }}" >
                                    <span class="input-group-text">LPA</span>
                                </div>
                                <small class="text-muted">In Lakhs per annum</small>
                            </div>
                            
                            <div class="col-md-3 col-12">
                                <label>Notice Period</label>
                                <div class="input-group">
                                    <input type="text" name="notice_period" class="form-control"
                                           placeholder="Enter Notice Period"
                                           value="{{ old('notice_period', $candidate->notice_period) }}" >
                                    <span class="input-group-text">days</span>
                                </div>
                                <small class="text-muted">In days</small>
                            </div>
                            
                            @if(Auth::user()->user_type == 'Domestic')
                                <input type="hidden" value="Domestic" name="user_type" id="user_type_hidden">
                            @elseif(Auth::user()->user_type == 'International')
                                <input type="hidden" value="International" name="user_type" id="user_type_hidden">
                            @else
                                <div class="col-md-3 col-12">
                                    <label>User Type <span class="required-star">*</span></label>
                                    <select name="user_type" id="user_type" class="form-select" required onchange="togglePassportFields()">
                                        <option value="">Select Type</option>
                                        <option value="Domestic" {{ (old('user_type', $candidate->user_type) == 'Domestic') ? 'selected' : '' }}>Domestic</option>
                                        <option value="International" {{ (old('user_type', $candidate->user_type) == 'International') ? 'selected' : '' }}>International</option>
                                    </select>
                                </div>
                            @endif
                        </div>

                        <!-- EXPERIENCE FIELDS SECTION (Always Visible for all users) -->
                        <div class="experience-section mt-4 mb-3">
                            <h6 class="mb-3" style="color: #4B49AC; font-weight: 600;">
                                <i class="fas fa-chart-line me-2"></i>Experience Breakdown (Optional)
                            </h6>
                        </div>

                        <!-- Row 6: Indian Experience & Foreign Experience -->
                        <div class="row g-4 mt-2">
                            <div class="col-md-3 col-12">
                                <label>Indian Experience (Years)</label>
                                <input type="number" step="0.1" name="indian_experience" id="indian_experience" class="form-control"
                                       placeholder="Enter Indian Experience"
                                       value="{{ old('indian_experience', $candidate->indian_experience) }}" min="0"
                                       oninput="calculateTotalExperience()">
                                <small class="text-muted">Experience in India</small>
                            </div>

                            <div class="col-md-3 col-12">
                                <label>Foreign Experience (Years)</label>
                                <input type="number" step="0.1" name="foreign_experience" id="foreign_experience" class="form-control"
                                       placeholder="Enter Foreign Experience"
                                       value="{{ old('foreign_experience', $candidate->foreign_experience) }}" min="0"
                                       oninput="calculateTotalExperience()">
                                <small class="text-muted">Experience outside India</small>
                            </div>

                            <div class="col-md-3 col-12">
                                <label>Total (Auto Calculated)</label>
                                <div class="input-group">
                                    <input type="number" step="0.1" name="total_exp" id="auto_total_experience" class="form-control auto-calculated"
                                           value="{{ old('indian_experience', $candidate->indian_experience ?? 0) + old('foreign_experience', $candidate->foreign_experience ?? 0) }}" readonly>
                                    <span class="input-group-text">years</span>
                                </div>
                                <small class="text-muted">Indian + Foreign = Total</small>
                            </div>
                        </div>

                        <!-- INTERNATIONAL PASSPORT FIELDS SECTION (Conditional - only for International) -->
                        <div id="passportFields" style="display: {{ $candidate->user_type == 'International' ? 'block' : 'none' }};">
                            <div class="international-section mt-4 mb-3">
                                <h5 class="mb-3" style="color: #4B49AC; font-weight: 600;">
                                    <i class="fas fa-passport me-2"></i>Passport Details (For International Candidates)
                                </h5>
                            </div>

                            <!-- Row 7: Passport Number & Passport Valid Date -->
                            <div class="row g-4 mt-2">
                                <div class="col-md-3 col-12">
                                    <label>Passport Number <span class="required-star" id="passportNoStar" style="display: none;">*</span></label>
                                    <input type="text" name="passport_number" id="passport_number" class="form-control"
                                           placeholder="Enter Passport Number"
                                           value="{{ old('passport_number', $candidate->passport_number) }}"
                                           {{ $candidate->user_type == 'International' ? 'required' : '' }}>
                                </div>

                                <div class="col-md-3 col-12">
                                    <label>Passport Valid Date <span class="required-star" id="passportDateStar" style="display: none;">*</span></label>
                                    <input type="date" name="passport_valid_date" id="passport_valid_date" class="form-control"
                                           value="{{ old('passport_valid_date', $candidate->passport_valid_date) }}"
                                           onchange="checkPassportExpiry()"
                                           {{ $candidate->user_type == 'International' ? 'required' : '' }}>
                                </div>

                                <div class="col-md-3 col-12">
                                    <label>Passport Expiry Alert</label>
                                    <div id="passportExpiryAlert" class="form-control-plaintext">
                                        <small class="text-muted">
                                            @if($candidate->passport_valid_date)
                                                @php
                                                    $expiryDate = new DateTime($candidate->passport_valid_date);
                                                    $today = new DateTime();
                                                    $daysDiff = $today->diff($expiryDate)->days;
                                                    $isPast = $expiryDate < $today;
                                                @endphp
                                                @if($isPast)
                                                    <span class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i>Expired {{ abs($daysDiff) }} days ago</span>
                                                @elseif($daysDiff <= 30)
                                                    <span class="text-warning"><i class="fas fa-exclamation-circle me-1"></i>Expires in {{ $daysDiff }} days</span>
                                                @elseif($daysDiff <= 180)
                                                    <span class="text-info"><i class="fas fa-info-circle me-1"></i>Expires in {{ $daysDiff }} days</span>
                                                @else
                                                    <span class="text-success"><i class="fas fa-check-circle me-1"></i>Valid for {{ $daysDiff }} days</span>
                                                @endif
                                            @else
                                                Will show days remaining
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- NEW: Sub-division Row (for International) -->
                            <div class="row g-4 mt-2">
                                <div class="col-md-3 col-12">
                                    <label>Sub-division <span class="required-star" id="subDivisionStar" style="display: none;">*</span></label>
                                    <select name="sub_division" id="subDivisionSelect" class="form-select">
                                        <option value="">Select Sub-division</option>
                                        <option value="GULF" {{ old('sub_division', $candidate->sub_division) == 'GULF' ? 'selected' : '' }}>GULF</option>
                                        <option value="SEA" {{ old('sub_division', $candidate->sub_division) == 'SEA' ? 'selected' : '' }}>SEA</option>
                                        <option value="EUROPE" {{ old('sub_division', $candidate->sub_division) == 'EUROPE' ? 'selected' : '' }}>EUROPE</option>
                                        <option value="OTHERS" {{ old('sub_division', $candidate->sub_division) == 'OTHERS' ? 'selected' : '' }}>OTHERS</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Row 8: Resume -->
                        <div class="row g-4 mt-3">
                            <div class="col-md-12 col-12">
                                <label>Resume</label>
                                <!-- Current Resume -->
                                @if($candidate->resume)
                                    <div class="current-resume mb-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="resume-badge">
                                                    <i class="mdi mdi-file-document"></i>
                                                    Current Resume: 
                                                    @if($candidate->resume)
                                                        <a href="{{ asset('storage/' . $candidate->resume) }}" 
                                                           target="_blank" class="text-primary ms-2">
                                                            View Current Resume
                                                        </a>
                                                    @else
                                                        <span class="text-muted ms-2">File not found</span>
                                                    @endif
                                                </span>
                                            </div>
                                            <div>
                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        onclick="confirmRemoveResume()">
                                                    <i class="mdi mdi-delete"></i> Remove
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="remove_resume" id="removeResumeFlag" value="0">
                                @endif
                                
                                <!-- Upload New Resume -->
                                <div class="mt-2">
                                    <input type="file" name="resume" class="form-control" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                    <small class="text-muted">
                                        Upload new resume to replace current one. Accepted formats: .pdf, .doc, .docx, .jpg, .jpeg, .png (Max: 10MB)
                                    </small>
                                    <small class="optional-text">Leave empty to keep current resume</small>
                                </div>
                            </div>
                        </div>

                        <!-- Row 9: Remarks -->
                        <div class="row g-4 mt-3">
                            <div class="col-md-12 col-12">
                                <label>Remarks</label>
                                <textarea name="remarks" class="form-control"
                                          placeholder="Enter Remarks"
                                          rows="3">{{ old('remarks', $candidate->remarks) }}</textarea>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="row mt-4">
                            <div class="col-12 d-flex justify-content-between">
                                <div>
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="mdi mdi-content-save"></i> Update Candidate
                                    </button>
                                    <a href="{{ route('candidates.show', $candidate->id) }}" class="btn btn-light">
                                        <i class="mdi mdi-close"></i> Cancel
                                    </a>
                                </div>
                                
                                <div>
                                    <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                        <i class="mdi mdi-refresh"></i> Reset Form
                                    </button>
                                    <a href="{{ route('candidates.index') }}" class="btn btn-light ms-2">
                                        <i class="mdi mdi-arrow-left"></i> Back to List
                                    </a>
                                </div>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize functions
        calculateAge();
        togglePassportFields();
        calculateTotalExperience();
        
        // Check passport expiry on load
        const passportDate = document.getElementById('passport_valid_date');
        if (passportDate && passportDate.value) {
            checkPassportExpiry();
        }
        
        // Event listeners for passport date
        if (passportDate) {
            passportDate.addEventListener('change', checkPassportExpiry);
        }
        
        // Sector "Other" option handling
        const sectorSelect = document.getElementById('sectorSelect');
        const otherSectorWrapper = document.getElementById('otherSectorWrapper');
        const otherSectorInput = document.getElementById('otherSectorInput');
        
        if (sectorSelect && otherSectorWrapper) {
            sectorSelect.addEventListener('change', function() {
                if (this.value === '13') {
                    otherSectorWrapper.style.display = 'block';
                    otherSectorInput.required = true;
                    this.removeAttribute('required');
                } else {
                    otherSectorWrapper.style.display = 'none';
                    otherSectorInput.required = false;
                    this.setAttribute('required', 'required');
                }
            });
            
            // Trigger change event on page load if "other" is already selected
            if (sectorSelect.value === '13' || (sectorSelect.selectedIndex === 0 && otherSectorInput.value)) {
                sectorSelect.dispatchEvent(new Event('change'));
            }
        }
        
        // Industry "Other" option handling
        const industrySelect = document.getElementById('industrySelect');
        const otherIndustryWrapper = document.getElementById('otherIndustryWrapper');
        const otherIndustryInput = document.getElementById('otherIndustryInput');
        
        if (industrySelect && otherIndustryWrapper) {
            industrySelect.addEventListener('change', function() {
                if (this.value === '8') {
                    otherIndustryWrapper.style.display = 'block';
                    otherIndustryInput.required = true;
                    this.removeAttribute('required');
                } else {
                    otherIndustryWrapper.style.display = 'none';
                    otherIndustryInput.required = false;
                    this.setAttribute('required', 'required');
                }
            });
            
            // Trigger change event on page load if "other" is already selected
            if (industrySelect.value === '8' || (industrySelect.selectedIndex === 0 && otherIndustryInput.value)) {
                industrySelect.dispatchEvent(new Event('change'));
            }
        }
        
        // Auto-fill total experience when Indian/Foreign experience is entered
        const indianExpInput = document.getElementById('indian_experience');
        const foreignExpInput = document.getElementById('foreign_experience');
        const totalExpInput = document.getElementById('total_exp');
        
        if (totalExpInput && (indianExpInput || foreignExpInput)) {
            const checkAndFillTotal = function() {
                const indianExp = parseFloat(indianExpInput?.value) || 0;
                const foreignExp = parseFloat(foreignExpInput?.value) || 0;
                const totalExp = indianExp + foreignExp;
                
                if ((indianExp > 0 || foreignExp > 0) && totalExp > 0 && !totalExpInput.value) {
                    totalExpInput.value = totalExp.toFixed(1);
                }
            };
            
            if (indianExpInput) indianExpInput.addEventListener('blur', checkAndFillTotal);
            if (foreignExpInput) foreignExpInput.addEventListener('blur', checkAndFillTotal);
        }
        
        // Form validation
        const form = document.getElementById('candidateForm');
        if (form) {
            form.addEventListener('submit', function(event) {
                let isValid = true;
                const requiredFields = form.querySelectorAll('[required]');
                
                requiredFields.forEach(field => {
                    if (!field.value.trim() && field.style.display !== 'none') {
                        isValid = false;
                        field.classList.add('is-invalid');
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });
                
                // Additional validation for International candidates
                const userTypeElement = document.getElementById('user_type') || document.getElementById('user_type_hidden');
                const userType = userTypeElement ? userTypeElement.value : '{{ $candidate->user_type }}';
                
                if (userType === 'International') {
                    const passportNumber = document.getElementById('passport_number');
                    const passportDate = document.getElementById('passport_valid_date');
                    const subDivision = document.getElementById('subDivisionSelect');
                    
                    if (passportNumber && !passportNumber.value.trim()) {
                        isValid = false;
                        passportNumber.classList.add('is-invalid');
                    }
                    
                    if (passportDate && !passportDate.value) {
                        isValid = false;
                        passportDate.classList.add('is-invalid');
                    }
                    
                    if (subDivision && !subDivision.value.trim()) {
                        isValid = false;
                        subDivision.classList.add('is-invalid');
                    }
                }
                
                if (!isValid) {
                    event.preventDefault();
                    alert('Please fill all required fields correctly.');
                }
            });
        }
    });

    function calculateAge() {
        const dobInput = document.getElementById('dob');
        const ageInput = document.getElementById('age');
        
        if (dobInput && dobInput.value) {
            const dob = new Date(dobInput.value);
            const today = new Date();
            
            let age = today.getFullYear() - dob.getFullYear();
            const monthDiff = today.getMonth() - dob.getMonth();
            
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                age--;
            }
            
            if (ageInput) {
                ageInput.value = age;
            }
        }
    }

    function togglePassportFields() {
        const userTypeElement = document.getElementById('user_type') || document.getElementById('user_type_hidden');
        const userType = userTypeElement ? userTypeElement.value : '{{ $candidate->user_type }}';
        const passportFields = document.getElementById('passportFields');
        
        // Get conditional elements and stars
        const passportNumber = document.getElementById('passport_number');
        const passportValidDate = document.getElementById('passport_valid_date');
        const subDivisionSelect = document.getElementById('subDivisionSelect');
        const passportNoStar = document.getElementById('passportNoStar');
        const passportDateStar = document.getElementById('passportDateStar');
        const subDivisionStar = document.getElementById('subDivisionStar');
        
        if (userType === 'International') {
            // Show passport fields
            if (passportFields) {
                passportFields.style.display = 'block';
            }
            
            // Set required attributes
            if (passportNumber) passportNumber.required = true;
            if (passportValidDate) passportValidDate.required = true;
            if (subDivisionSelect) subDivisionSelect.required = true;
            
            // Show required stars
            if (passportNoStar) passportNoStar.style.display = 'inline';
            if (passportDateStar) passportDateStar.style.display = 'inline';
            if (subDivisionStar) subDivisionStar.style.display = 'inline';
            
        } else {
            // Hide passport fields
            if (passportFields) {
                passportFields.style.display = 'none';
            }
            
            // Remove required attributes
            if (passportNumber) passportNumber.required = false;
            if (passportValidDate) passportValidDate.required = false;
            if (subDivisionSelect) subDivisionSelect.required = false;
            
            // Hide required stars
            if (passportNoStar) passportNoStar.style.display = 'none';
            if (passportDateStar) passportDateStar.style.display = 'none';
            if (subDivisionStar) subDivisionStar.style.display = 'none';
        }
    }

    function calculateTotalExperience() {
        const indianExp = parseFloat(document.getElementById('indian_experience')?.value) || 0;
        const foreignExp = parseFloat(document.getElementById('foreign_experience')?.value) || 0;
        const totalExp = indianExp + foreignExp;
        
        // Update auto-calculated display
        const autoTotalExp = document.getElementById('auto_total_experience');
        if (autoTotalExp) {
            autoTotalExp.value = totalExp.toFixed(1);
        }
    }

    function checkPassportExpiry() {
        const passportDateInput = document.getElementById('passport_valid_date');
        const expiryAlert = document.getElementById('passportExpiryAlert');
        
        if (!passportDateInput || !passportDateInput.value || !expiryAlert) return;
        
        const expiryDate = new Date(passportDateInput.value);
        const today = new Date();
        const timeDiff = expiryDate.getTime() - today.getTime();
        const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));
        
        if (daysDiff < 0) {
            expiryAlert.innerHTML = `<span class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i>Expired ${Math.abs(daysDiff)} days ago</span>`;
        } else if (daysDiff <= 30) {
            expiryAlert.innerHTML = `<span class="text-warning"><i class="fas fa-exclamation-circle me-1"></i>Expires in ${daysDiff} days</span>`;
        } else if (daysDiff <= 180) {
            expiryAlert.innerHTML = `<span class="text-info"><i class="fas fa-info-circle me-1"></i>Expires in ${daysDiff} days</span>`;
        } else {
            expiryAlert.innerHTML = `<span class="text-success"><i class="fas fa-check-circle me-1"></i>Valid for ${daysDiff} days</span>`;
        }
    }

    function confirmRemoveResume() {
        if (confirm('Are you sure you want to remove the current resume? This action cannot be undone.')) {
            document.getElementById('removeResumeFlag').value = '1';
            document.querySelector('.current-resume').style.display = 'none';
            alert('Resume will be removed when you save changes.');
        }
    }

    function resetForm() {
        if (confirm('Are you sure you want to reset all form fields? This will discard all changes.')) {
            document.getElementById('candidateForm').reset();
            calculateAge(); // Recalculate age after reset
            document.getElementById('removeResumeFlag').value = '0';
            
            // Reset to original values
            togglePassportFields();
            calculateTotalExperience();
            
            // Show resume section again if it was hidden
            const currentResume = document.querySelector('.current-resume');
            if (currentResume) {
                currentResume.style.display = 'block';
            }
            
            // Reset "Other" sector/industry visibility
            const sectorSelect = document.getElementById('sectorSelect');
            const industrySelect = document.getElementById('industrySelect');
            
            if (sectorSelect && sectorSelect.value !== '13') {
                document.getElementById('otherSectorWrapper').style.display = 'none';
            }
            
            if (industrySelect && industrySelect.value !== '8') {
                document.getElementById('otherIndustryWrapper').style.display = 'none';
            }
            
            alert('Form has been reset to original values.');
        }
    }
</script>

<style>
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .col-md-3 {
            margin-bottom: 15px;
        }
        
        .btn {
            width: 100%;
            margin-bottom: 10px;
        }
        
        .d-flex {
            flex-direction: column;
        }
        
        .d-flex > * {
            margin-bottom: 10px;
        }
    }
</style>

@endsection