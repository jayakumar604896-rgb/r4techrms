@extends('layouts.app')

@section('title', 'Add Client')

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
    }
    
    .subdivision-container {
        display: none;
    }
    .international_colid {
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
            <h3 class="page-title">Add Client</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 mt-2">
                    <li class="breadcrumb-item">
                        <a href="{{ route('clients.index') }}">Clients</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Add Client</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Form Card -->
    <div class="row justify-content-center">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Client Details</h4>

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
                    <form action="{{ route('clients.store') }}" method="POST" enctype="multipart/form-data" id="clientForm">
                        @csrf

                        <!-- Row 1: User Type and Sub Division -->
                        <div class="row g-4 mb-3">
                            <div class="col-md-3" id="user_type_colid">
                                <label>User Type</label>
                                <select name="user_type" class="form-select" id="userType">
                                    <option value="">Select User Type</option>
                                    <option value="Domestic" {{ old('user_type') == 'Domestic' ? 'selected' : '' }}>Domestic</option>
                                    <option value="International" {{ old('user_type') == 'International' ? 'selected' : '' }}>International</option>
                                </select>
                            </div>

                            <div class="col-md-3 subdivision-container" id="subDivisionContainer">
                                <label>Sub Division</label>
                                <select name="sub_division" class="form-select" id="subDivision">
                                    <option value="">Select Sub Division</option>
                                    <option value="GuLF" {{ old('sub_division') == 'GuLF' ? 'selected' : '' }}>GuLF</option>
                                    <option value="SEA" {{ old('sub_division') == 'SEA' ? 'selected' : '' }}>SEA</option>
                                    <option value="EUROPE" {{ old('sub_division') == 'EUROPE' ? 'selected' : '' }}>EUROPE</option>
                                    <option value="OTHERS" {{ old('sub_division') == 'OTHERS' ? 'selected' : '' }}>OTHERS</option>
                                </select>
                            </div>
                            <div class="col-md-3" id="company_name_colid">
                                <label>Company Name</label>
                                <input type="text" name="company_name" class="form-control"
                                       placeholder="Enter company name"
                                       value="{{ old('company_name') }}">
                            </div>
                        
                            <div class="col-md-3" id="industryCol">
                                <label>Industry <span class="required-star">*</span></label>
                                <select name="industry_id" id="industrySelect" class="form-select" required>
                                    <option value="">Select Industry</option>
                                    @foreach(\App\Models\Industry::active()->get() as $industry)
                                        <option value="{{ $industry->name }}" {{ old('industry_name') == $industry->id ? 'selected' : '' }}>
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
                                <label>Country</label>
                                <input type="text" name="country" class="form-control"
                                       placeholder="Enter country"
                                       value="{{ old('country') }}">
                            </div>
                            <div class="col-md-3">
                                <label>State</label>
                                <input type="text" name="state" class="form-control"
                                       placeholder="Enter state"
                                       value="{{ old('state') }}">
                            </div>
                            <div class="col-md-3">
                                <label>Address</label>
                                <input type="text" name="address" class="form-control"
                                       placeholder="Enter address"
                                       value="{{ old('address') }}">
                            </div>
                            <div class="col-md-3">
                                <label>Company Location</label>
                                <input type="text" name="company_location" class="form-control"
                                       placeholder="Enter company location"
                                       value="{{ old('company_location') }}">
                            </div>

                            <div class="col-md-3" id="gst_colid">
                                <label>GST Number</label>
                                <input type="text" name="gst_number" class="form-control"
                                       placeholder="Enter GST number"
                                       value="{{ old('gst_number') }}">
                            </div>
                            <div class="col-md-3 international_colid" id="international_colid">
                                <label>MEA Number</label>
                                <input type="text" name="mea_number" class="form-control" placeholder="Enter MEA number" value="{{ old('mea_number') }}">
                            </div>
                            <div class="col-md-3 international_colid" id="licence_colid">
                                <label>Licence Number</label>
                                <input type="text" name="licence_number" class="form-control" placeholder="Enter Licence number" value="{{ old('licence_number') }}" >
                            </div>
                            <div class="col-md-3">
                                <label>Status</label>
                                <select name="status" class="form-select">
                                    <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="2" {{ old('status') == '2' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <!-- Row 4: Contact Person and Designation -->
                        <div class="mt-4 mb-3">
                            <div class="dot-border">
                                <p>Contact Person 1</p>
                                <div class="row g-4 mb-3">
                                    <div class="col-md-6">
                                        <label>Contact Person</label>
                                        <input type="text" name="contact_person" class="form-control"
                                            placeholder="Enter contact person name"
                                            value="{{ old('contact_person') }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label>Designation</label>
                                        <input type="text" name="desination" class="form-control"
                                            placeholder="Enter designation"
                                            value="{{ old('desination') }}">
                                    </div>
                                </div>

                                <!-- Row 5: Contact Details -->
                                <div class="row g-4">
                                    <div class="col-md-4">
                                        <label>Mobile Number</label>
                                        <input type="text" name="mobile_number" class="form-control"
                                            placeholder="Enter 25-digit mobile number"
                                            value="{{ old('mobile_number') }}" 
                                            pattern="[0-9]{10}" 
                                            maxlength="25">
                                        <small class="text-muted">Max: 25 digits </small>
                                    </div>

                                    <div class="col-md-4">
                                        <label>Alternate Number</label>
                                        <input type="text" name="alternate_number" class="form-control"
                                            placeholder="Enter alternate number"
                                            value="{{ old('alternate_number') }}"
                                            pattern="[0-9]{10}"
                                            maxlength="25">
                                    </div>

                                    <div class="col-md-4">
                                        <label>Email ID</label>
                                        <input type="email" name="email_id" class="form-control"
                                            placeholder="Enter email address"
                                            value="{{ old('email_id') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 mb-3">
                            <div class="dot-border">
                                <p>Contact Person 2</p>
                                <div class="row g-4 mb-3">
                                    <div class="col-md-6">
                                        <label>Contact Person</label>
                                        <input type="text" name="contact_person_two" class="form-control"
                                            placeholder="Enter contact person name"
                                            value="{{ old('contact_person_two') }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label>Designation</label>
                                        <input type="text" name="desination_two" class="form-control"
                                            placeholder="Enter designation"
                                            value="{{ old('desination_two') }}">
                                    </div>
                                </div>

                                <!-- Row 5: Contact Details -->
                                <div class="row g-4">
                                    <div class="col-md-4">
                                        <label>Mobile Number</label>
                                        <input type="text" name="mobile_number_two" class="form-control"
                                            placeholder="Enter 25-digit mobile number"
                                            value="{{ old('mobile_number_two') }}" 
                                            maxlength="25">
                                        <small class="text-muted">Max: 25 digits </small>
                                    </div>

                                    <div class="col-md-4">
                                        <label>Alternate Number</label>
                                        <input type="text" name="alternate_number_two" class="form-control"
                                            placeholder="Enter alternate number"
                                            value="{{ old('alternate_number_two') }}"
                                            
                                            maxlength="25">
                                    </div>

                                    <div class="col-md-4">
                                        <label>Email ID</label>
                                        <input type="email" name="email_id_two" class="form-control"
                                            placeholder="Enter email address"
                                            value="{{ old('email_id_two') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Row 6: File Attachments -->
                        <div class="row g-4 mb-3">
                            <div class="col-md-6">
                                <label>Job Description Attachment</label>
                                <input type="file" name="attachment_of_jd" 
                                       class="form-control" 
                                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <small class="text-muted">Max file size: 10MB | Allowed: PDF, DOC, DOCX, JPG, PNG</small>
                                <div id="jdPreview" class="mt-2"></div>
                            </div>

                            <div class="col-md-6">
                                <label>Other Documents Attachment</label>
                                <input type="file" name="attachment_of_other_docs" 
                                       class="form-control" 
                                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <small class="text-muted">Max file size: 10MB | Allowed: PDF, DOC, DOCX, JPG, PNG</small>
                                <div id="otherDocsPreview" class="mt-2"></div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="mdi mdi-content-save me-2"></i>Save Client
                                </button>
                                <a href="{{ route('clients.index') }}" class="btn btn-light">
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

    const industrySelect = document.getElementById('industrySelect');
    const industryCol = document.getElementById('industryCol');
    const otherIndustryWrapper = document.getElementById('otherIndustryWrapper');
    const otherIndustryInput = document.getElementById('otherIndustryInput');
    
    if (industrySelect && otherIndustryWrapper) {
        industrySelect.addEventListener('change', function() {
            if (this.value === 'Others') {
                otherIndustryWrapper.style.display = 'block';
                otherIndustryInput.required = true;
                this.removeAttribute('required');
                //industryCol.classList.remove('col-md-6');
                //industryCol.classList.add('col-md-3');
            } else {
                otherIndustryWrapper.style.display = 'none';
                otherIndustryInput.required = false;
                this.setAttribute('required', 'required');
               // industryCol.classList.remove('col-md-6');
                //industryCol.classList.add('col-md-3');
            }
        });
        
        if (industrySelect.value === 'Others') {
            industrySelect.dispatchEvent(new Event('change'));
        }
    }

    const form = document.getElementById('clientForm');
    const userTypeSelect = document.getElementById('userType');
    const userTypeColidSelect = document.getElementById('user_type_colid');
    const companyNameColidSelect = document.getElementById('company_name_colid');

    const subDivisionContainer = document.getElementById('subDivisionContainer');
    const internationalColid = document.getElementById('international_colid');
    const licenceColid = document.getElementById('licence_colid');
    const gstColid = document.getElementById('gst_colid');
    const subDivisionSelect = document.getElementById('subDivision');
    const mobileInput = document.querySelector('input[name="mobile_number"]');
    const alternateInput = document.querySelector('input[name="alternate_number"]');
    const gstInput = document.querySelector('input[name="gst_number"]');
    const jdFileInput = document.querySelector('input[name="attachment_of_jd"]');
    const otherDocsFileInput = document.querySelector('input[name="attachment_of_other_docs"]');
    
    // User Type Change Handler
    if (userTypeSelect) {
        userTypeSelect.addEventListener('change', function() {
            if (this.value === 'International') {
                subDivisionContainer.style.display = 'block';
                subDivisionSelect.required = true;
                internationalColid.style.display = 'block';
                licenceColid.style.display = 'block';
                gstColid.style.display = 'none';
                //userTypeColidSelect.classList.remove('col-md-6');
                //userTypeColidSelect.classList.add('col-md-4');
                //companyNameColidSelect.classList.remove('col-md-6');
                //companyNameColidSelect.classList.add('col-md-4');
            } else {
                subDivisionContainer.style.display = 'none';
                subDivisionSelect.required = false;
                internationalColid.style.display = 'none';
                licenceColid.style.display = 'none';
                gstColid.style.display = 'block';
                subDivisionSelect.value = '';
                //userTypeColidSelect.classList.remove('col-md-4');
                //userTypeColidSelect.classList.add('col-md-6');
                //companyNameColidSelect.classList.remove('col-md-4');
                //companyNameColidSelect.classList.add('col-md-6');
            }
        });
        
        // Initialize on page load
        if (userTypeSelect.value === 'International') {
            subDivisionContainer.style.display = 'block';
            subDivisionSelect.required = true;
            internationalColid.style.display = 'block';
            licenceColid.style.display = 'block';
            gstColid.style.display = 'none';

        }
    }

    // Mobile number validation
    if (mobileInput) {
        mobileInput.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '');
            if (this.value.length > 10) {
                this.value = this.value.slice(0, 10);
            }
        });
    }

    // Alternate number validation
    if (alternateInput) {
        alternateInput.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '');
            if (this.value.length > 10) {
                this.value = this.value.slice(0, 10);
            }
        });
    }

    // GST number validation
    if (gstInput) {
        gstInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
            this.value = this.value.replace(/[^A-Z0-9]/g, '');
        });
    }

    // File validation for JD attachment
    if (jdFileInput) {
        jdFileInput.addEventListener('change', function() {
            validateFile(this, 'jdPreview');
        });
    }

    // File validation for other docs attachment
    if (otherDocsFileInput) {
        otherDocsFileInput.addEventListener('change', function() {
            validateFile(this, 'otherDocsPreview');
        });
    }

    // Form submission
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        
        let isValid = true;
        const errorMessages = [];
        
        // Validate mobile number if provided
        //if (mobileInput && mobileInput.value && !/^\d{10}$/.test(mobileInput.value)) {
        //    isValid = false;
        //    showError(mobileInput, 'Mobile number must be exactly 10 digits');
        //    errorMessages.push('Invalid mobile number format');
        //}

        // Validate alternate number if provided
        //if (alternateInput && alternateInput.value && !/^\d{10}$/.test(alternateInput.value)) {
        //    isValid = false;
        //    showError(alternateInput, 'Alternate number must be exactly 10 digits');
        //    errorMessages.push('Invalid alternate number format');
        //}

        // Validate email if provided
        const emailInput = document.querySelector('input[name="email_id"]');
        if (emailInput && emailInput.value && !isValidEmail(emailInput.value)) {
            isValid = false;
            showError(emailInput, 'Please enter a valid email address');
            errorMessages.push('Invalid email address');
        }

        // Validate files if uploaded
        if (jdFileInput && jdFileInput.files.length > 0) {
            if (!validateFileSizeAndType(jdFileInput.files[0])) {
                isValid = false;
                errorMessages.push('Invalid Job Description file (max 10MB, allowed: PDF, DOC, DOCX, JPG, PNG)');
            }
        }

        if (otherDocsFileInput && otherDocsFileInput.files.length > 0) {
            if (!validateFileSizeAndType(otherDocsFileInput.files[0])) {
                isValid = false;
                errorMessages.push('Invalid Other Documents file (max 10MB, allowed: PDF, DOC, DOCX, JPG, PNG)');
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

    function validateFile(input, previewId) {
        const file = input.files[0];
        const preview = document.getElementById(previewId);
        preview.innerHTML = '';
        
        if (file) {
            if (!validateFileSizeAndType(file)) {
                showError(input, 'Invalid file. Max 10MB, allowed: PDF, DOC, DOCX, JPG, PNG');
                input.value = '';
                return;
            }
            
            clearError(input);
            
            // Show file preview
            const fileInfo = document.createElement('div');
            fileInfo.className = 'alert alert-info py-2';
            fileInfo.innerHTML = `
                <i class="mdi mdi-file-document me-2"></i>
                <strong>Selected:</strong> ${file.name} 
                <small>(${(file.size / 1024 / 1024).toFixed(2)} MB)</small>
            `;
            preview.appendChild(fileInfo);
        }
    }

    function validateFileSizeAndType(file) {
        const maxSize = 10 * 1024 * 1024; // 10MB
        const allowedTypes = [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'image/jpeg',
            'image/jpg',
            'image/png'
        ];
        
        return file.size <= maxSize && allowedTypes.includes(file.type);
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