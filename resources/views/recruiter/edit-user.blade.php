@extends('layouts.app')

@section('title', 'Edit User')

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
        margin-bottom: 6px;
    }
    ::placeholder {
        color: rgb(19, 18, 18) !important;
        opacity: 1 !important;
    }
    .form-control,
    .form-select {
        padding: 0.80rem 1rem !important;
        height: 46px !important;
        border-radius: 8px !important;
        border: 1px solid #262424 !important;
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

    /* ---------------------------
        Conditional Field
    ----------------------------*/
    .conditional-field {
        display: none;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-5px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Current photo styling */
    .current-photo {
        margin-bottom: 10px;
        border-radius: 6px;
        border: 2px solid #e0e0e0;
        padding: 4px;
    }
</style>

<div class="content-wrapper">

    <!-- Page Header -->
    <div class="page-header mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h3 class="page-title">Edit User</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 mt-2">
                    <li class="breadcrumb-item"><a href="{{ route('recruiters') }}">Recruiters</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit User</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Form Card -->
    <div class="row justify-content-center">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">

                    <h4 class="card-title">User Details</h4>

                    {{-- SUCCESS MESSAGE --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- ERROR MESSAGE --}}
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- EDIT FORM -->
                    <form action="{{ route('recruiters.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Row 1 -->
                        <div class="row g-4">
                            <div class="col-md-4">
                                <label>Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control"
                                       value="{{ old('name', $user->name) }}" required>
                            </div>

                            <div class="col-md-4">
                                <label>Email </label>
                                <input type="email" name="email" class="form-control"
                                       value="{{ old('email', $user->email) }}">
                            </div>
                            <div class="col-md-4">
                                <label>Company Serial Number <span class="text-danger">*</span></label>
                                <input type="text" name="company_serial_number" class="form-control"
                                       value="{{ old('company_serial_number', $user->company_serial_number) }}" required>
                            </div>

                            <div class="col-md-4">
                                <label>Designation</label>
                                <input type="text" name="designation" class="form-control"
                                       value="{{ old('designation', $user->designation) }}">
                            </div>
                            <div class="col-md-4">
                                <label>Staff Contact Number <span class="text-danger">*</span></label>
                                <input type="text" name="staff_contact_no" class="form-control"
                                       value="{{ old('staff_contact_no', $user->staff_contact_no) }}" required>
                            </div>

                            <div class="col-md-4">
                                <label>Alternate Contact Number</label>
                                <input type="text" name="alternate_contact_no" class="form-control"
                                       value="{{ old('alternate_contact_no', $user->alternate_contact_no) }}">
                            </div>
                            <div class="col-md-12">
                                <label>Address <span class="text-danger">*</span></label>
                                <textarea name="address" class="form-control" required >{{ old('address', $user->address) }}</textarea>
                            </div>
                            <div class="col-md-12">
                                <label>Location / District</label>
                                <input type="text" name="city" class="form-control" required value="{{ old('city', $user->city) }}" >
                            </div>
                            <div class="col-md-4">
                                <label>Aadhar Card Number <span class="text-danger">*</span></label>
                                <input type="text" name="aadhar_card_number" class="form-control" required value="{{ old('aadhar_card_number', $user->aadhar_card_number) }}" >
                            </div>
                            
                        
                            <div class="col-md-4">
                                <label>Date of Joining <span class="text-danger">*</span></label>
                                <input type="date" name="date_of_joining" class="form-control"
                                       value="{{ old('date_of_joining', $user->date_of_joining) }}" required>
                            </div>

                            <div class="col-md-4">
                                <label>Date of Birth</label>
                                <input type="date" name="dob" class="form-control"
                                       value="{{ old('dob', $user->dob) }}" >
                            </div>
                        
                            <div class="col-md-4">
                                <label>Photo</label><br>
                                @if($user->photo)
                                    <img src="{{ asset($user->photo) }}" width="100" class="current-photo mb-2">
                                @endif
                                <input type="file" name="photo" class="form-control" accept="image/*">
                            </div>
                            <div class="col-md-4">
                                <label>Work Mode <span class="text-danger">*</span></label>
                                <select name="work_mode" class="form-select" required>
                                    <option value="">Select Type</option>
                                    <option value="work from home" {{ old('work_mode', $user->work_mode) == 'work from home' ? 'selected' : '' }}>work from home</option>
                                    <option value="work from office" {{ old('work_mode', $user->work_mode) == 'work from office' ? 'selected' : '' }}>work from office</option>
                                    <option value="Hybrid" {{ old('work_mode', $user->work_mode) == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>User Type <span class="text-danger">*</span></label>
                                <select name="user_type" class="form-select" id="userTypeSelect" required>
                                    <option value="">Select Type</option>
                                    <option value="Domestic" {{ old('user_type', $user->user_type) == 'Domestic' ? 'selected' : '' }}>Domestic</option>
                                    <option value="International" {{ old('user_type', $user->user_type) == 'International' ? 'selected' : '' }}>International</option>
                                    <option value="Guest" {{ old('user_type', $user->user_type) == 'Guest' ? 'selected' : '' }}>Guest</option>
                                </select>
                            </div>
                        </div>

                        <div class="row g-4 mt-1 conditional-field" id="subDivisionField">
                            <div class="col-md-4">
                                <label>Sub-division <span class="text-danger">*</span></label>
                                <select name="sub_division" class="form-select" id="subDivisionSelect">
                                    <option value="">Select Sub-division</option>
                                    <option value="GULF" {{ old('sub_division', $user->sub_division) == 'GULF' ? 'selected' : '' }}>GULF</option>
                                    <option value="SEA" {{ old('sub_division', $user->sub_division) == 'SEA' ? 'selected' : '' }}>SEA</option>
                                    <option value="EUROPE" {{ old('sub_division', $user->sub_division) == 'EUROPE' ? 'selected' : '' }}>EUROPE</option>
                                    <option value="OTHERS" {{ old('sub_division', $user->sub_division) == 'OTHERS' ? 'selected' : '' }}>OTHERS</option>
                                </select>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="{{ route('recruiters') }}" class="btn btn-light">Cancel</a>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const userTypeSelect = document.getElementById('userTypeSelect');
        const subDivisionField = document.getElementById('subDivisionField');
        const subDivisionSelect = document.getElementById('subDivisionSelect');
        
        // Function to toggle sub-division field
        function toggleSubDivisionField() {
            if (userTypeSelect.value === 'International') {
                subDivisionField.style.display = 'flex';
                subDivisionSelect.required = true;
            } else {
                subDivisionField.style.display = 'none';
                subDivisionSelect.required = false;
            }
        }
        
        // Initial check on page load (for edit page, show if already International)
        toggleSubDivisionField();
        
        // Add event listener for change on user type select
        userTypeSelect.addEventListener('change', toggleSubDivisionField);
        
        // Also handle form submission to ensure validation
        document.querySelector('form').addEventListener('submit', function(e) {
            if (userTypeSelect.value === 'International' && !subDivisionSelect.value) {
                e.preventDefault();
                alert('Please select a sub-division for International user type.');
                subDivisionSelect.focus();
            }
        });
    });
</script>

@endsection