@extends('layouts.app')

@section('title', 'Add Candidate')

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
        background: #e9e9e9;
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
        background: #162734;
        color: white;
    }

    .btn-light:hover {
        background: #e9e9e9;
        color: #000;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #6C63FF !important;
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
    
    /* Alert if no data */
    .no-data-alert {
        background-color: #fff3cd;
        border: 1px solid #ffecb5;
        color: #856404;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 15px;
    }
    
    /* Required field indicator */
    .required-star {
        color: #dc3545;
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
    
    /* Auto-calculated field styling */
    .auto-calculated {
        background-color: #f8f9fa !important;
        color: #495057 !important;
        cursor: not-allowed;
    }
    
    /* Experience input styling */
    .experience-input:focus {
        border-color: #4B49AC !important;
        box-shadow: 0 0 0 0.2rem rgba(75, 73, 172, 0.25) !important;
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

    /* Form validation */
    .is-invalid {
        border-color: #dc3545 !important;
    }
    
    .input-group-text {
        background-color: #f8f9fa;
        border: 1px solid #d4d4d4;
    }
    
    /* Sub-division row styling */
    .subdivision-row {
        transition: all 0.3s ease;
    }
</style>

<div class="content-wrapper">

    <!-- Page Header -->
    <div class="page-header mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h3 class="page-title">Add Candidate</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 mt-2">
                    <li class="breadcrumb-item">
                        <a href="{{ route('candidates.index') }}">Candidates</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Add Candidate</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Form Card -->
    <div class="row justify-content-center">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">

                    <h4 class="card-title">Candidate Details <small class="text-muted">(All fields are required)</small></h4>

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

                    <!-- Database Setup Alert -->
                    @if(!\App\Models\Sector::exists() || !\App\Models\Industry::exists() || !\App\Models\PlacementType::exists())
                        <div class="no-data-alert mb-4">
                            <strong>⚠️ Notice:</strong> Some dropdown data is missing. 
                            <a href="{{ route('setup.page') }}" class="alert-link">Run database setup</a> 
                            to populate Sector, Industry, and Placement Type options.
                        </div>
                    @endif

                    <!-- Start Form -->
                    <form action="{{ route('candidates.store') }}" method="POST" enctype="multipart/form-data" id="candidateForm">
                        @csrf
                        
                        <!-- Emp ID Display (Auto Generated) -->
                        <div class="row g-4 mb-3">
                            <div class="col-md-12">
                                <div class="emp-id-display">
                                    Emp-id: <span class="text-primary">Auto Generated</span>
                                </div>
                            </div>
                        </div>

                        <!-- Row 1: First Name & Last Name -->
                        <div class="row g-4">
                            @if(Auth::user()->user_type == 'Domestic')
                                <input type="hidden" value="Domestic" name="user_type" id="user_type_hidden">
                            @elseif(Auth::user()->user_type == 'International')
                                <input type="hidden" value="International" name="user_type" id="user_type_hidden">
                            @else
                                <div class="col-md-3">
                                    <label>User Type <span class="required-star">*</span></label>
                                    <select name="user_type" id="user_type" class="form-select" required onchange="togglePassportFields()">
                                        <option value="">Select Type</option>
                                        <option value="Domestic" {{ old('user_type') == 'Domestic' ? 'selected' : '' }}>Domestic</option>
                                        <option value="International" {{ old('user_type') == 'International' ? 'selected' : '' }}>International</option>
                                    </select>
                                </div>
                            @endif
                            <div class="col-md-3 subdivision-row" id="subDivisionRow">
                                    <label>Sub-division <span class="required-star" id="subDivisionStar" style="display: none;">*</span></label>
                                    <select name="sub_division" id="subDivisionSelect" class="form-select">
                                        <option value="">Select Sub-division</option>
                                        <option value="GULF" {{ old('sub_division') == 'GULF' ? 'selected' : '' }}>GULF</option>
                                        <option value="SEA" {{ old('sub_division') == 'SEA' ? 'selected' : '' }}>SEA</option>
                                        <option value="EUROPE" {{ old('sub_division') == 'EUROPE' ? 'selected' : '' }}>EUROPE</option>
                                        <option value="OTHERS" {{ old('sub_division') == 'OTHERS' ? 'selected' : '' }}>OTHERS</option>
                                    </select>
                            </div>
                            <div class="col-md-3">
                                <label>First Name <span class="required-star">*</span></label>
                                <input type="text" name="first_name" class="form-control"
                                       placeholder="Enter First Name"
                                       value="{{ old('first_name') }}" required>
                            </div>

                            <div class="col-md-3">
                                <label>Last Name </label>
                                <input type="text" name="last_name" class="form-control"
                                       placeholder="Enter Last Name"
                                       value="{{ old('last_name') }}">
                            </div>

                            <div class="col-md-3">
                                <label>Code No </label>
                                <input type="text" name="code_no" class="form-control"
                                       placeholder="Enter Code No"
                                       value="{{ old('code_no') }}">
                            </div>

                        <!-- Row 2: Date of Submission, DOB, Age -->
                            <div class="col-md-3">
                                <label>Date of Submission <span class="required-star">*</span></label>
                                <input type="date" name="date_of_submission" class="form-control"
                                       value="{{ old('date_of_submission') }}" required>
                            </div>

                            <div class="col-md-3">
                                <label>Date of Birth </label>
                                <input type="date" name="dob" id="dob" class="form-control"
                                       value="{{ old('dob') }}" onchange="calculateAge()">
                            </div>

                            <div class="col-md-3">
                                <label>Age </label>
                                <input type="number" name="age" id="age" class="form-control"
                                       placeholder="Auto calculated"
                                       value="{{ old('age') }}" readonly>
                            </div>

                        <!-- Row 3: Email, Contact, Designation -->
                            <div class="col-md-3">
                                <label>Email </label>
                                <input type="email" name="email" class="form-control"
                                       placeholder="Enter Email"
                                       value="{{ old('email') }}">
                            </div>
                            
                            <div class="col-md-3">
                                <label>Contact Number <span class="required-star">*</span></label>
                                <input type="text" name="contact_number" class="form-control"
                                       placeholder="Enter Contact Number"
                                       value="{{ old('contact_number') }}" required>
                            </div>

                            <div class="col-md-3">
                                <label>Designation</label>
                                <input type="text" name="designation" class="form-control"
                                       placeholder="Enter Designation"
                                       value="{{ old('designation') }}">
                            </div>

                        <!-- Row 4: Sector, Other Sector, Qualification -->
                            <div class="col-md-3">
                                <label>Sector <span class="required-star">*</span></label>
                                <select name="sector_id" id="sectorSelect" class="form-select" required>
                                    <option value="">Select Sector</option>
                                    @foreach(\App\Models\Sector::active()->get() as $sector)
                                        <option value="{{ $sector->id }}" {{ old('sector_id') == $sector->id ? 'selected' : '' }}>
                                            {{ $sector->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if(\App\Models\Sector::count() == 0)
                                    <small class="text-danger">No sectors found. Please run database setup.</small>
                                @endif
                            </div>
                            
                            <div class="col-md-3" id="otherSectorWrapper" style="display: {{ old('sector_id') == 'other' ? 'block' : 'none' }};">
                                <label for="other_sector">Specify Other Sector <span class="required-star">*</span></label>
                                <input type="text" 
                                       name="other_sector" 
                                       id="otherSectorInput" 
                                       class="form-control" 
                                       value="{{ old('other_sector') }}"
                                       {{ old('sector_id') == 'other' ? 'required' : '' }}>
                            </div>
                            
                            <div class="col-md-3">
                                <label>Qualification </label>
                                <input type="text" name="qualification" class="form-control"
                                       placeholder="Enter Qualification"
                                       value="{{ old('qualification') }}">
                            </div>
                            <div class="col-md-3">
                                <label>Industry <span class="required-star">*</span></label>
                                <select name="industry_id" id="industrySelect" class="form-select" required>
                                    <option value="">Select Industry</option>
                                    @foreach(\App\Models\Industry::active()->get() as $industry)
                                        <option value="{{ $industry->id }}" {{ old('industry_id') == $industry->id ? 'selected' : '' }}>
                                            {{ $industry->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if(\App\Models\Industry::count() == 0)
                                    <small class="text-danger">No industries found. Please run database setup.</small>
                                @endif
                            </div>
                            
                            <div class="col-md-3" id="otherIndustryWrapper" style="display: {{ old('industry_id') == 'other' ? 'block' : 'none' }};">
                                <label for="other_industry">Specify Other Industry <span class="required-star">*</span></label>
                                <input type="text" 
                                       name="other_industry" 
                                       id="otherIndustryInput" 
                                       class="form-control" 
                                       value="{{ old('other_industry') }}"
                                       {{ old('industry_id') == 'other' ? 'required' : '' }}>
                            </div>
                            
                            <div class="col-md-3">
                                <label>Placement Type <span class="required-star">*</span></label>
                                <select name="placement_type_id" class="form-select" required>
                                    <option value="">Select Placement Type</option>
                                    @foreach(\App\Models\PlacementType::active()->get() as $type)
                                        <option value="{{ $type->id }}" {{ old('placement_type_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if(\App\Models\PlacementType::count() == 0)
                                    <small class="text-danger">No placement types found. Please run database setup.</small>
                                @endif
                            </div>

                            <div class="col-md-3">
                                <label>Post & Location Applied </label>
                                <input type="text" name="post_location_applied" class="form-control"
                                       placeholder="Enter Post & Location Applied"
                                       value="{{ old('post_location_applied') }}">
                            </div>

                            <div class="col-md-3">
                                <label>Current Stay</label>
                                <input type="text" name="current_stay" class="form-control"
                                       placeholder="Enter Current Stay"
                                       value="{{ old('current_stay') }}">
                            </div>

                        <!-- Row 7: Indian Experience & Foreign Experience -->
                            <div class="col-md-3">
                                <label>Indian Experience (Years)</label>
                                <input type="number" step="0.1" name="indian_experience" id="indian_experience" class="form-control"
                                       placeholder="Enter Indian Experience"
                                       value="{{ old('indian_experience') }}" min="0"
                                       oninput="calculateTotalExperience()">
                                <small class="text-muted">Experience in India</small>
                            </div>

                            <div class="col-md-3">
                                <label>Foreign Experience (Years)</label>
                                <input type="number" step="0.1" name="foreign_experience" id="foreign_experience" class="form-control"
                                       placeholder="Enter Foreign Experience"
                                       value="{{ old('foreign_experience') }}" min="0"
                                       oninput="calculateTotalExperience()">
                                <small class="text-muted">Experience outside India</small>
                            </div>

                            <div class="col-md-3">
                                <label>Total (Auto Calculated)</label>
                                <div class="input-group">
                                    <input type="number" step="0.1" name="total_experience" id="auto_total_experience" class="form-control auto-calculated"
                                           value="{{ old('indian_experience', 0) + old('foreign_experience', 0) }}" readonly>
                                    <span class="input-group-text">years</span>
                                </div>
                                <small class="text-muted">Indian + Foreign = Total</small>
                            </div>

                        <!-- Row 8: CTC, Notice Period -->
                            <div class="col-md-3">
                                <label>CTC (Annual) </label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" name="ctc" class="form-control"
                                           placeholder="Enter CTC"
                                           value="{{ old('ctc') }}" min="0" step="0.01">
                                    <span class="input-group-text">LPA</span>
                                </div>
                                <small class="text-muted">In Lakhs per annum</small>
                            </div>

                            <div class="col-md-3">
                                <label>Notice Period</label>
                                <div class="input-group">
                                    <input type="number" name="notice_period" class="form-control"
                                           placeholder="Enter Notice Period"
                                           value="{{ old('notice_period') }}" min="0">
                                    <span class="input-group-text">days</span>
                                </div>
                                <small class="text-muted">In days</small>
                            </div>
                        </div>

                        <!-- INTERNATIONAL PASSPORT FIELDS SECTION (Conditional - only for International) -->
                        <div id="passportFields" style="display: {{ old('user_type') == 'International' ? 'block' : 'none' }};">
                            <div class="international-section mt-4 mb-3">
                                <h5 class="mb-3" style="color: #4B49AC; font-weight: 600;">
                                    <i class="fas fa-passport me-2"></i>Passport Details (For International Candidates)
                                </h5>
                            </div>

                            <!-- Row 9: Passport Number & Passport Valid Date -->
                            <div class="row g-4 mt-2">
                                <div class="col-md-4">
                                    <label>Passport Number <span class="required-star" id="passportNoStar" style="display: none;">*</span></label>
                                    <input type="text" name="passport_number" id="passport_number" class="form-control"
                                           placeholder="Enter Passport Number"
                                           value="{{ old('passport_number') }}"
                                           {{ old('user_type') == 'International' ? 'required' : '' }}>
                                </div>

                                <div class="col-md-4">
                                    <label>Passport Valid Date <span class="required-star" id="passportDateStar" style="display: none;">*</span></label>
                                    <input type="date" name="passport_valid_date" id="passport_valid_date" class="form-control"
                                           value="{{ old('passport_valid_date') }}"
                                           onchange="checkPassportExpiry()"
                                           {{ old('user_type') == 'International' ? 'required' : '' }}>
                                </div>

                                <div class="col-md-4">
                                    <label>Passport Expiry Alert</label>
                                    <div id="passportExpiryAlert" class="form-control-plaintext">
                                        <small class="text-muted">Will show days remaining</small>
                                    </div>
                                </div>
                            </div>

                           
                        </div>

                        <!-- Row 10: Resume & Remarks -->
                        <div class="row g-4 mt-4">
                            <div class="col-md-4">
                                <label>Resume <span class="required-star">*</span></label>
                                <input type="file" name="resume" class="form-control" accept=".pdf,.doc,.docx" required>
                                <small class="text-muted">Accepted formats: .pdf, .doc, .docx (Max: 5MB)</small>
                            </div>

                            <div class="col-md-8">
                                <label>Remarks</label>
                                <textarea name="remarks" class="form-control"
                                          placeholder="Enter Remarks"
                                          rows="3">{{ old('remarks') }}</textarea>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary me-2">Submit</button>
                                <a href="{{ route('candidates.index') }}" class="btn btn-light">Cancel</a>
                                @if(!\App\Models\Sector::exists() || !\App\Models\Industry::exists() || !\App\Models\PlacementType::exists())
                                    <a href="{{ route('setup.page') }}" class="btn btn-warning">Setup Database First</a>
                                @endif
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
        calculateTotalExperience();
        togglePassportFields(); // Initialize passport fields visibility (this handles sub-division too)
        
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
            
            if (sectorSelect.value === '13') {
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
            
            if (industrySelect.value === '8') {
                industrySelect.dispatchEvent(new Event('change'));
            }
        }
        
        // Auto-calculate total experience when either Indian or Foreign experience changes
        const indianExpInput = document.getElementById('indian_experience');
        const foreignExpInput = document.getElementById('foreign_experience');
        
        if (indianExpInput) {
            indianExpInput.addEventListener('input', calculateTotalExperience);
        }
        
        if (foreignExpInput) {
            foreignExpInput.addEventListener('input', calculateTotalExperience);
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
        // Get user type from either select or hidden input
        const userTypeElement = document.getElementById('user_type') || document.getElementById('user_type_hidden');
        const userType = userTypeElement ? userTypeElement.value : '';
        const passportFields = document.getElementById('passportFields');
        
        // Get conditional elements and stars
        const passportNumber = document.getElementById('passport_number');
        const passportValidDate = document.getElementById('passport_valid_date');
        const subDivisionSelect = document.getElementById('subDivisionSelect');
        const subDivisionRow = document.getElementById('subDivisionRow');
        const passportNoStar = document.getElementById('passportNoStar');
        const passportDateStar = document.getElementById('passportDateStar');
        const subDivisionStar = document.getElementById('subDivisionStar');
        
        if (userType === 'International') {
            // Show passport fields container
            if (passportFields) {
                passportFields.style.display = 'block';
            }
            
            // Show sub-division row
            if (subDivisionRow) {
                subDivisionRow.style.display = '';
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
            // Hide passport fields container
            if (passportFields) {
                passportFields.style.display = 'none';
            }
            
            // Hide sub-division row
            if (subDivisionRow) {
                subDivisionRow.style.display = 'none';
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

    // Form validation
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('candidateForm');
        if (form) {
            form.addEventListener('submit', function(event) {
                let isValid = true;
                const requiredFields = form.querySelectorAll('[required]');
                
                requiredFields.forEach(field => {
                    if (!field.value.trim() && field.style.display !== 'none' && field.offsetParent !== null) {
                        isValid = false;
                        field.classList.add('is-invalid');
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });
                
                // Additional validation for International candidates
                const userTypeElement = document.getElementById('user_type') || document.getElementById('user_type_hidden');
                const userType = userTypeElement ? userTypeElement.value : '';
                
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
        
        // Real-time validation for numeric fields
        const numericFields = document.querySelectorAll('input[type="number"]');
        numericFields.forEach(field => {
            field.addEventListener('input', function() {
                if (this.hasAttribute('min') && parseFloat(this.value) < parseFloat(this.getAttribute('min'))) {
                    this.value = this.getAttribute('min');
                }
            });
        });
    });
</script>

@endsection