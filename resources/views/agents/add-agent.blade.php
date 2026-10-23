@extends('layouts.app')

@section('title', 'Add Agent')

@section('content')

<style>
    .page-header h3 {
        font-weight: 700;
        font-size: 26px;
        color: #3A3A8B;
    }
    
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
    
    .form-control:focus,
    .form-select:focus {
        border-color: #6C63FF !important;
        box-shadow: 0 0 0 0.2rem rgba(108, 99, 255, 0.25) !important;
    }
    
    textarea.form-control {
        height: auto !important;
        min-height: 100px;
    }
    
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
    }
    
    .required-star {
        color: #dc3545;
    }
    
    .is-invalid {
        border-color: #dc3545 !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }
    .international_colid,
    .subdivision-container {
        display: none;
    }
    .dot-border{
        border: 2px dotted;
        padding: 24px;
    }
</style>

<div class="content-wrapper">
    <!-- Page Header -->
    <div class="page-header mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h3 class="page-title">Add Agent</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 mt-2">
                    <li class="breadcrumb-item">
                        <a href="{{ route('agents.index') }}">Agents</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Add Agent</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Form Card -->
    <div class="row justify-content-center">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Agent Details</h4>

                    <!-- Success + Error Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="mdi mdi-check-circle-outline me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="mdi mdi-alert-circle-outline me-2"></i>
                            <strong>Please fix the following errors:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Start Form -->
                    <form action="{{ route('agents.store') }}" method="POST" enctype="multipart/form-data" id="agentForm">
                        @csrf

                        <!-- Row 1: User Type, Sub Division, Company Name, Business Type -->
                        <div class="row g-4 mb-3">
                            <div class="col-md-3">
                                <label>User Type</label>
                                <select name="user_type" class="form-select" id="userType">
                                    <option value="">Select User Type</option>
                                    <option value="Domestic" {{ old('user_type') == 'Domestic' ? 'selected' : '' }}>Domestic</option>
                                    <option value="International" {{ old('user_type') == 'International' ? 'selected' : '' }}>International</option>
                                </select>
                            </div>

                            <!-- Sub Division (hidden initially, shown for International) -->
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
                                <label>Agent Company Name <span class="required-star">*</span></label>
                                <input type="text" name="agent_company_name" class="form-control"
                                       placeholder="Enter company name"
                                       value="{{ old('agent_company_name') }}" required>
                            </div>

                            <div class="col-md-3">
                                <label>Type of Business <span class="required-star">*</span></label>
                                <select name="type_business" class="form-select" id="type_business" required>
                                    <option value="">Select Business Type</option>
                                    @foreach(\App\Models\Bussiness::active()->get() as $industry)
                                        <option value="{{ $industry->name }}" {{ old('type_business') == $industry->id ? 'selected' : '' }}>
                                            {{ $industry->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if(\App\Models\Bussiness::count() == 0)
                                    <small class="text-danger">No industries found. Please run database setup.</small>
                                @endif
                            </div>
                            <div class="col-md-3" id="otherIndustryWrapper" style="display: {{ old('type_business') == 'Other' ? 'block' : 'none' }};">
                                <label for="other_industry">Specify Other Business <span class="required-star">*</span></label>
                                <input type="text" 
                                       name="other_industry" 
                                       id="otherIndustryInput" 
                                       class="form-control" 
                                       value="{{ old('other_industry') }}"
                                       {{ old('type_business') == 'Other' ? 'required' : '' }}>
                            </div>
                        </div>

                        <!-- Row 2: Agent Name and Designation (Contact Person 1) -->
                        <div class="mt-4 mb-3">
                            <div class="dot-border">
                                <p>Contact Person 1</p>
                                <div class="row g-4 mb-3 ">
                                    <div class="col-md-3">
                                        <label>Agent Name <span class="required-star">*</span></label>
                                        <input type="text" name="agent_name" class="form-control"
                                            placeholder="Enter agent's full name"
                                            value="{{ old('agent_name') }}" required>
                                    </div>

                                    <div class="col-md-3">
                                        <label>Designation <span class="required-star">*</span></label>
                                        <input type="text" name="desination" class="form-control"
                                            placeholder="Enter designation"
                                            value="{{ old('desination') }}" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Mobile Number <span class="required-star">*</span></label>
                                        <input type="text" name="mobile_number" class="form-control"
                                            placeholder="Enter  mobile number"
                                            value="{{ old('mobile_number') }}" 
                                            required>
                                        <small class="text-muted">Max:25 digits</small>
                                    </div>

                                    <div class="col-md-3">
                                        <label>Alternate Number</label>
                                        <input type="text" name="alternate_number" class="form-control"
                                            placeholder="Enter alternate number"
                                            value="{{ old('alternate_number') }}">
                                    </div>

                                    <div class="col-md-3">
                                        <label>Email ID <span class="required-star">*</span></label>
                                        <input type="email" name="email_id" class="form-control"
                                            placeholder="Enter email address"
                                            value="{{ old('email_id') }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Row 3: Contact Person 2 -->
                        <div class="mt-4 mb-3">
                            <div class="dot-border">
                                <p>Contact Person 2</p>
                                <div class="row g-4 mb-3">
                                    <div class="col-md-3">
                                        <label>Agent Name <span class="required-star">*</span></label>
                                        <input type="text" name="agent_name_two" class="form-control"
                                            placeholder="Enter agent's full name"
                                            value="{{ old('agent_name_two') }}" required>
                                    </div>

                                    <div class="col-md-3">
                                        <label>Designation <span class="required-star">*</span></label>
                                        <input type="text" name="desination_two" class="form-control"
                                            placeholder="Enter designation"
                                            value="{{ old('desination_two') }}" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Mobile Number <span class="required-star">*</span></label>
                                        <input type="text" name="mobile_number_two" class="form-control"
                                            placeholder="Enter  mobile number"
                                            value="{{ old('mobile_number_two') }}" 
                                            required>
                                        <small class="text-muted">Max:25 digits</small>
                                    </div>

                                    <div class="col-md-3">
                                        <label>Alternate Number</label>
                                        <input type="text" name="alternate_number_two" class="form-control"
                                            placeholder="Enter alternate number"
                                            value="{{ old('alternate_number_two') }}">
                                    </div>

                                    <div class="col-md-3">
                                        <label>Email ID <span class="required-star">*</span></label>
                                        <input type="email" name="email_id_two" class="form-control"
                                            placeholder="Enter email address"
                                            value="{{ old('email_id_two') }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Row 4: Location, GST, MEA, Licence, Status, Attachment -->
                        <div class="row g-4 mb-3">
                            <div class="col-md-3">
                                <label>Company Location <span class="required-star">*</span></label>
                                <input type="text" name="company_location" class="form-control" placeholder="Enter city, state" value="{{ old('company_location') }}" required>
                            </div>
                            <div class="col-md-3" id="gst_colid">
                                <label>GST Number</label>
                                <input type="text" name="gst_number" class="form-control"
                                       placeholder="Enter GST number"
                                       value="{{ old('gst_number') }}">
                            </div>
                            <div class="col-md-3 international_colid" id="international_colid">
                                <label>MEA Number</label>
                                <input type="text" name="mea_number" class="form-control" placeholder="Enter MEA number" value="{{ old('mea_number') }}" >
                            </div>
                            <div class="col-md-3 international_colid" id="licence_colid">
                                <label>Licence Number</label>
                                <input type="text" name="licence_number" class="form-control" placeholder="Enter Licence number" value="{{ old('licence_number') }}" >
                            </div>
                            <div class="col-md-3">
                                <label>Status <span class="required-star">*</span></label>
                                <select name="status" class="form-select" required>
                                    <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="2" {{ old('status') == '2' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Business Profile Attachment</label>
                                <input type="file" name="agent_business_profile_attachment" 
                                       class="form-control" 
                                       id="businessProfile"
                                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <small class="text-muted">Max file size: 10MB | Allowed: PDF, DOC, DOCX, JPG, PNG</small>
                                <div id="filePreview" class="mt-2"></div>
                            </div>
                        </div>

                        <!-- Row 5: Address -->
                        <div class="row g-4 mb-3">
                            <div class="col-md-12">
                                <label>Address <span class="required-star">*</span></label>
                                <textarea name="address" class="form-control" placeholder="Enter complete address" rows="3" required>{{ old('address') }}</textarea>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="mdi mdi-content-save me-2"></i>Save Agent
                                </button>
                                <a href="{{ route('agents.index') }}" class="btn btn-light">
                                    <i class="mdi mdi-arrow-left me-2"></i>Cancel
                                </a>
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

    // Type of Business "Other" handling
    const typeBusinessSelect = document.getElementById('type_business');
    const otherIndustryWrapper = document.getElementById('otherIndustryWrapper');
    const otherIndustryInput = document.getElementById('otherIndustryInput');
    
    if (typeBusinessSelect && otherIndustryWrapper) {
        typeBusinessSelect.addEventListener('change', function() {
            if (this.value === 'Other') {
                otherIndustryWrapper.style.display = 'block';
                otherIndustryInput.required = true;
                this.removeAttribute('required');
            } else {
                otherIndustryWrapper.style.display = 'none';
                otherIndustryInput.required = false;
                this.setAttribute('required', 'required');
            }
        });
        
        if (typeBusinessSelect.value === 'Other') {
            typeBusinessSelect.dispatchEvent(new Event('change'));
        }
    }

    // User Type Change Handler (shows Sub Division, MEA, Licence and hides GST)
    const userTypeSelect = document.getElementById('userType');
    const internationalColid = document.getElementById('international_colid');
    const licenceColid = document.getElementById('licence_colid');
    const gstColid = document.getElementById('gst_colid');
    const subDivisionContainer = document.getElementById('subDivisionContainer');
    const subDivisionSelect = document.getElementById('subDivisionSelect');
    const subDivisionStar = document.getElementById('subDivisionStar');

    if (userTypeSelect) {
        userTypeSelect.addEventListener('change', function() {
            if (this.value === 'International') {
                // Show International-specific fields
                internationalColid.style.display = 'block';
                licenceColid.style.display = 'block';
                gstColid.style.display = 'none';
                subDivisionContainer.style.display = 'block';
                subDivisionSelect.required = true;
                subDivisionStar.style.display = 'inline';
            } else {
                // Hide International fields
                internationalColid.style.display = 'none';
                licenceColid.style.display = 'none';
                gstColid.style.display = 'block';
                subDivisionContainer.style.display = 'none';
                subDivisionSelect.required = false;
                subDivisionStar.style.display = 'none';
                subDivisionSelect.value = ''; // clear selection
            }
        });
        
        // Initialize on page load
        if (userTypeSelect.value === 'International') {
            internationalColid.style.display = 'block';
            licenceColid.style.display = 'block';
            gstColid.style.display = 'none';
            subDivisionContainer.style.display = 'block';
            subDivisionSelect.required = true;
            subDivisionStar.style.display = 'inline';
        } else {
            subDivisionContainer.style.display = 'none';
        }
    }

    const form = document.getElementById('agentForm');
    const mobileInput = document.querySelector('input[name="mobile_number"]');
    const mobileInputTwo = document.querySelector('input[name="mobile_number_two"]');
    const alternateInput = document.querySelector('input[name="alternate_number"]');
    const alternateInputTwo = document.querySelector('input[name="alternate_number_two"]');
    const gstInput = document.querySelector('input[name="gst_number"]');
    const fileInput = document.getElementById('businessProfile');
    const filePreview = document.getElementById('filePreview');

    // Mobile number validation - allow only digits and limit to 25 digits (as per placeholder)
    function enforceMobileValidation(input) {
        if (input) {
            input.addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, '');
                if (this.value.length > 25) {
                    this.value = this.value.slice(0, 25);
                }
            });
        }
    }
    enforceMobileValidation(mobileInput);
    enforceMobileValidation(mobileInputTwo);
    enforceMobileValidation(alternateInput);
    enforceMobileValidation(alternateInputTwo);

    // GST number validation (optional)
    if (gstInput) {
        gstInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
            // Remove special characters except alphanumeric
            this.value = this.value.replace(/[^A-Z0-9]/g, '');
        });
        
        gstInput.addEventListener('blur', function() {
            if (this.value && !isValidGST(this.value)) {
                showError(this, 'Please enter a valid GST number format (15 alphanumeric characters)');
            } else {
                clearError(this);
            }
        });
    }

    // File input preview and validation
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            filePreview.innerHTML = '';
            
            if (file) {
                // File size validation (10MB)
                const maxSize = 10 * 1024 * 1024; // 10MB in bytes
                if (file.size > maxSize) {
                    showError(this, 'File size must be less than 10MB');
                    this.value = '';
                    return;
                }
                
                // File type validation
                const allowedTypes = [
                    'application/pdf',
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'image/jpeg',
                    'image/jpg',
                    'image/png'
                ];
                
                if (!allowedTypes.includes(file.type)) {
                    showError(this, 'Invalid file type. Allowed: PDF, DOC, DOCX, JPG, PNG');
                    this.value = '';
                    return;
                }
                
                clearError(this);
                
                // Show file preview
                const fileInfo = document.createElement('div');
                fileInfo.className = 'alert alert-info py-2';
                fileInfo.innerHTML = `
                    <i class="mdi mdi-file-document me-2"></i>
                    <strong>Selected:</strong> ${file.name} 
                    <small>(${(file.size / 1024 / 1024).toFixed(2)} MB)</small>
                `;
                filePreview.appendChild(fileInfo);
            }
        });
    }

    // Email validation
    const emailInput = document.querySelector('input[name="email_id"]');
    const emailInputTwo = document.querySelector('input[name="email_id_two"]');
    
    function attachEmailValidation(input) {
        if (input) {
            input.addEventListener('blur', function() {
                if (this.value && !isValidEmail(this.value)) {
                    showError(this, 'Please enter a valid email address');
                } else {
                    clearError(this);
                }
            });
        }
    }
    attachEmailValidation(emailInput);
    attachEmailValidation(emailInputTwo);

    // Form submission validation
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        
        let isValid = true;
        const errorMessages = [];
        
        // Check required fields
        const requiredFields = form.querySelectorAll('[required]');
        requiredFields.forEach(field => {
            // Skip hidden fields (Sub Division when not visible)
            if (field.offsetParent === null && field.type !== 'hidden') return;
            
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('is-invalid');
                const fieldName = field.previousElementSibling?.textContent?.replace('*', '').trim() || field.name;
                errorMessages.push(`${fieldName} is required`);
            } else {
                field.classList.remove('is-invalid');
            }
        });

        // Validate mobile number format (optional: we already limit digits, but we can check length)
        if (mobileInput && mobileInput.value && mobileInput.value.length !== 10) {
            isValid = false;
            showError(mobileInput, 'Mobile number should be 10 digits');
            errorMessages.push('Mobile number (Person 1) must be 10 digits');
        }

        if (mobileInputTwo && mobileInputTwo.value && mobileInputTwo.value.length !== 10) {
            isValid = false;
            showError(mobileInputTwo, 'Mobile number should be 10 digits');
            errorMessages.push('Mobile number (Person 2) must be 10 digits');
        }

        // Validate email format
        if (emailInput && emailInput.value && !isValidEmail(emailInput.value)) {
            isValid = false;
            showError(emailInput, 'Invalid email format');
            errorMessages.push('Invalid email address (Person 1)');
        }

        if (emailInputTwo && emailInputTwo.value && !isValidEmail(emailInputTwo.value)) {
            isValid = false;
            showError(emailInputTwo, 'Invalid email format');
            errorMessages.push('Invalid email address (Person 2)');
        }

        // Validate GST if provided
        if (gstInput && gstInput.value && !isValidGST(gstInput.value)) {
            isValid = false;
            showError(gstInput, 'Invalid GST number format (15 alphanumeric characters)');
            errorMessages.push('Invalid GST number format');
        }

        // Validate file if uploaded
        if (fileInput && fileInput.files.length > 0) {
            const file = fileInput.files[0];
            const maxSize = 10 * 1024 * 1024;
            const allowedTypes = [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'image/jpeg',
                'image/jpg',
                'image/png'
            ];
            
            if (file.size > maxSize) {
                isValid = false;
                showError(fileInput, 'File size exceeds 10MB limit');
                errorMessages.push('File size must be less than 10MB');
            }
            
            if (!allowedTypes.includes(file.type)) {
                isValid = false;
                showError(fileInput, 'Invalid file type');
                errorMessages.push('Invalid file type. Allowed: PDF, DOC, DOCX, JPG, PNG');
            }
        }

        if (isValid) {
            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin me-2"></i>Saving...';
            submitBtn.disabled = true;
            
            // Submit the form
            form.submit();
        } else {
            // Show error summary
            Swal.fire({
                icon: 'error',
                title: 'Form Validation Error',
                html: '<div class="text-start"><p>Please fix the following errors:</p><ul class="mb-0"><li>' + 
                      errorMessages.join('</li><li>') + 
                      '</li></ul></div>',
                confirmButtonColor: '#6C63FF'
            });
        }
    });

    // Helper functions
    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    function isValidGST(gst) {
        // Basic GST validation - 15 characters alphanumeric
        return /^[A-Z0-9]{15}$/.test(gst);
    }

    function showError(input, message) {
        input.classList.add('is-invalid');
        let errorDiv = input.parentElement.querySelector('.invalid-feedback');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback';
            input.parentElement.appendChild(errorDiv);
        }
        errorDiv.textContent = message;
    }

    function clearError(input) {
        input.classList.remove('is-invalid');
        const errorDiv = input.parentElement.querySelector('.invalid-feedback');
        if (errorDiv) {
            errorDiv.remove();
        }
    }

    // Real-time validation on input
    form.querySelectorAll('input, select, textarea').forEach(input => {
        input.addEventListener('input', function() {
            if (this.value.trim()) {
                this.classList.remove('is-invalid');
                const errorDiv = this.parentElement.querySelector('.invalid-feedback');
                if (errorDiv) {
                    errorDiv.remove();
                }
            }
        });
    });
});
</script>

<style>
    .invalid-feedback {
        display: block;
        color: #dc3545;
        font-size: 0.875em;
        margin-top: 0.25rem;
    }
    
    input[type="file"] {
        padding: 0.5rem !important;
        height: auto !important;
    }
    
    input[type="file"]::file-selector-button {
        background: #6C63FF;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        margin-right: 1rem;
        cursor: pointer;
        transition: background 0.2s;
    }
    
    input[type="file"]::file-selector-button:hover {
        background: #4B49AC;
    }
</style>

@endsection