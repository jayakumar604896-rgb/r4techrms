@extends('layouts.app')

@section('title', 'Edit Demand Details')

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

    /* ---------------------------
        Conditional fields (Sub Division & Other Sector)
    ----------------------------*/
    .subdivision-container,
    .other-sector-container {
        display: none;
        animation: fadeIn 0.3s ease;
    }

    .other-sector-container.visible {
        display: block !important;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-5px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="content-wrapper">

    <div class="page-header mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h3 class="page-title">Edit Demand Registers Details</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 mt-2">
                    <li class="breadcrumb-item">
                        <a href="{{ route('demands.index') }}">Demand Registers</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('demands.show', $demand->id) }}">View</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <h4 class="card-title">Edit Demand Registers Details</h4>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('demands.update', $demand->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Section 0: Status & Remarks (unchanged) -->
                <div class="form-section">
                    <h5 class="section-title">Status & Remarks</h5>
                    <div class="row g-4">
                        <div class="col-md-3">
                            <label>Status <span class="required-star">*</span></label>
                            <select name="status" id="statusSelect" class="form-select" required>
                                <option value="">Select Status</option>
                                <option value="Closed" {{ old('status', $demand->status) == 'Closed' ? 'selected' : '' }}>Closed</option>
                                <option value="Working" {{ old('status', $demand->status) == 'Working' ? 'selected' : '' }}>Working</option>
                                <option value="Hold" {{ old('status', $demand->status) == 'Hold' ? 'selected' : '' }}>Hold</option>
                                <option value="Rework" {{ old('status', $demand->status) == 'Rework' ? 'selected' : '' }}>Rework</option>
                                <option value="Reject" {{ old('status', $demand->status) == 'Reject' ? 'selected' : '' }}>Reject</option>
                                <option value="Other" {{ old('status', $demand->status) == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Demand Closed Date</label>
                            <input type="date" name="demand_closed_date" class="form-control" value="{{ old('demand_closed_date', $demand->demand_closed_date ? $demand->demand_closed_date->format('Y-m-d') : '') }}">
                        </div>
                        <div class="col-md-6" id="remarksField">
                            <label>Remarks </label>
                            <textarea name="remarks" class="form-control" rows="2">{{ old('remarks', $demand->remarks) }}</textarea>
                        </div>
                    </div>
                </div>

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
                                    <option value="Domestic" {{ old('user_type', $demand->user_type)=='Domestic'?'selected':'' }}>Domestic</option>
                                    <option value="International" {{ old('user_type', $demand->user_type)=='International'?'selected':'' }}>International</option>
                                </select>
                            @else
                                <input type="text" name="user_type" id="userType" class="form-control" value="{{ Auth::user()->user_type }}" readonly>
                            @endif
                        </div>

                        <!-- Sub Division (conditional) -->
                        <div class="col-md-3 subdivision-container" id="subDivisionContainer">
                            <label>Sub Division <span class="required-star" id="subDivisionStar" style="display: none;">*</span></label>
                            <select name="sub_division" id="subDivisionSelect" class="form-select">
                                <option value="">Select Sub Division</option>
                                <option value="GULF" {{ old('sub_division', $demand->sub_division) == 'GULF' ? 'selected' : '' }}>GULF</option>
                                <option value="SEA" {{ old('sub_division', $demand->sub_division) == 'SEA' ? 'selected' : '' }}>SEA</option>
                                <option value="EUROPE" {{ old('sub_division', $demand->sub_division) == 'EUROPE' ? 'selected' : '' }}>EUROPE</option>
                                <option value="OTHERS" {{ old('sub_division', $demand->sub_division) == 'OTHERS' ? 'selected' : '' }}>OTHERS</option>
                            </select>
                        </div>

                        <!-- Demand Ref No. -->
                        <div class="col-md-3">
                            <label>Demand Reference Number</label>
                            <input type="text" name="demand_ref_no" class="form-control" value="{{ old('demand_ref_no', $demand->demand_ref_no) }}" readonly>
                        </div>

                        <!-- Demand Open Date -->
                        <div class="col-md-3">
                            <label>Demand Open Date</label>
                            <input type="date" name="demand_open_date" class="form-control" value="{{ old('demand_open_date', $demand->demand_open_date ? $demand->demand_open_date->format('Y-m-d') : '') }}">
                        </div>

                        <!-- Recruiter Name -->
                        <div class="col-md-3">
                            <label>Recruiter Name <span class="required-star">*</span></label>
                            <select name="recruiter_id" class="form-select">
                                <option value="">Select Recruiter</option>
                                @foreach(\App\Models\User::get() as $recruiter)
                                    <option value="{{ $recruiter->id }}" {{ old('recruiter_id', $demand->recruiter_id) == $recruiter->id ? 'selected' : '' }}>
                                        {{ $recruiter->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Position Details -->
                <div class="form-section">
                    <h5 class="section-title">Position Details</h5>
                    <div class="row g-4">
                        <div class="col-md-3">
                            <label>Designation/Post <span class="required-star">*</span></label>
                            <input type="text" name="designation_offered" class="form-control" value="{{ old('designation_offered', $demand->designation_offered) }}">
                        </div>
                        <div class="col-md-3">
                            <label>Client / Agent / Source</label>
                            <input type="text" name="client_agent_source" class="form-control" value="{{ old('client_agent_source', $demand->client_agent_source) }}">
                        </div>
                        <div class="col-md-3">
                            <label>Number of Positions <span class="required-star">*</span></label>
                            <input type="number" name="no_of_positions" class="form-control" value="{{ old('no_of_positions', $demand->no_of_positions) }}" min="1">
                        </div>
                        <div class="col-md-3">
                            <label>Qualification <span class="required-star">*</span></label>
                            <input type="text" name="qualification" class="form-control" value="{{ old('qualification', $demand->qualification) }}">
                        </div>
                        <div class="col-md-3">
                            <label>Experience <span class="required-star">*</span></label>
                            <input type="text" name="experience" class="form-control" value="{{ old('experience', $demand->experience) }}">
                        </div>
                        <div class="col-md-3">
                            <label>Job Location <span class="required-star">*</span></label>
                            <input type="text" name="job_location" class="form-control" value="{{ old('job_location', $demand->job_location) }}">
                        </div>

                        <!-- Industry/Sector with Other option -->
                        <div class="col-md-3">
                            <label>Industry/Sector <span class="required-star">*</span></label>
                            <select name="sector_id" id="sectorSelect" class="form-select">
                                <option value="">Select Sector/Industry</option>
                                @foreach(\App\Models\Sector::active()->get() as $sector)
                                    <option value="{{ $sector->id }}" {{ old('sector_id', $demand->sector_id) == $sector->id ? 'selected' : '' }}>
                                        {{ $sector->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Other Sector (conditional) -->
                        <div class="col-md-3 other-sector-container" id="otherSectorContainer">
                            <label>Other Sector <span class="required-star">*</span></label>
                            <input type="text" name="other_industry" id="otherSectorInput" class="form-control" 
                                   value="{{ old('other_industry', $demand->other_industry) }}" placeholder="Specify sector">
                        </div>

                        <div class="col-md-3">
                            <label>Preferred Candidate Location</label>
                            <input type="text" name="candidate_location" class="form-control" value="{{ old('candidate_location', $demand->candidate_location) }}" placeholder="e.g., Pune, Gujarat, TN">
                        </div>
                    </div>

                    <!-- Additional Benefits (unchanged) -->
                    <div class="row g-4 mt-3">
                        <div class="col-md-12">
                            <h6 class="border-bottom pb-2 mb-3 text-primary">Additional Benefits</h6>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <span class="input-group-text bg-light">Food</span>
                                <input type="text" name="food" class="form-control" value="{{ old('food', $demand->food) }}" placeholder="e.g., Provided">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group">
                                <span class="input-group-text bg-light">Accom & Transport</span>
                                <input type="text" name="accommodation_transport" class="form-control" value="{{ old('accommodation_transport', $demand->accommodation_transport) }}" placeholder="e.g., Provided">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group">
                                <span class="input-group-text bg-light">Salary</span>
                                <input type="text" name="salary" class="form-control" value="{{ old('salary', $demand->salary) }}" placeholder="e.g., ₹30,000">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group">
                                <span class="input-group-text bg-light">Workg Hrs.</span>
                                <input type="text" name="working_hours" class="form-control" value="{{ old('working_hours', $demand->working_hours) }}" placeholder="e.g., 9-6">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="input-group">
                                <span class="input-group-text bg-light">Overtime</span>
                                <input type="text" name="overtime" class="form-control" value="{{ old('overtime', $demand->overtime) }}" placeholder="e.g., 1.5x">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sections 3, 4, 5 (Keywords, Interview, Advertisement) remain exactly as in your original code – I'm not repeating them here for brevity, but they are unchanged -->
                <!-- ... (all other sections unchanged) ... -->

                <!-- Buttons -->
                <div class="mt-4 text-end">
                    <a href="{{ route('demands.index') }}" class="btn btn-light me-2">Cancel</a>
                    <a href="{{ route('demands.show', $demand->id) }}" class="btn btn-info me-2">View</a>
                    <button type="submit" class="btn btn-primary">Update Demand Details</button>
                </div>

            </form>

        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sub Division toggle
    const userTypeSelect = document.getElementById('userType');
    const subDivisionContainer = document.getElementById('subDivisionContainer');
    const subDivisionSelect = document.getElementById('subDivisionSelect');
    const subDivisionStar = document.getElementById('subDivisionStar');
    const demandRefInput = document.querySelector('input[name="demand_ref_no"]');

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
        }
        // Regenerate reference number when user type changes
        generateDemandRefNo();
    }

    // Function to generate Demand Reference Number
    function generateDemandRefNo() {
        // Only generate if it's a new demand (not existing one)
        // For edit page, we only generate if user type or sub division changes
        let userType = userTypeSelect ? userTypeSelect.value : null;
        let subDivision = subDivisionSelect ? subDivisionSelect.value : null;
        
        // Don't generate if no user type selected
        if (!userType) {
            return;
        }
        
        // Get the current reference number (original value)
        const originalRefNo = '{{ $demand->demand_ref_no }}';
        
        // Show loading indicator
        if (demandRefInput) {
            demandRefInput.value = 'Generating...';
        }
        
        // AJAX call to get generated number
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
                if (demandRefInput) {
                    // For edit page, show the generated number but don't save until form submit
                    // Mark it as generated so we know to save it
                    demandRefInput.value = data.ref_no;
                    demandRefInput.setAttribute('data-generated', 'true');
                }
            } else {
                if (demandRefInput) {
                    // Revert to original value if generation fails
                    demandRefInput.value = originalRefNo;
                    demandRefInput.setAttribute('data-generated', 'false');
                }
                console.error('Error:', data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (demandRefInput) {
                demandRefInput.value = originalRefNo;
            }
        });
    }

    // Industry/Sector Other toggle
    const sectorSelect = document.getElementById('sectorSelect');
    const otherSectorContainer = document.getElementById('otherSectorContainer');
    const otherSectorInput = document.getElementById('otherSectorInput');

    if (sectorSelect && otherSectorContainer && otherSectorInput) {
        function toggleOtherSector() {
            if (sectorSelect.value === '13') {
                otherSectorContainer.classList.add('visible');
                otherSectorInput.required = true;
            } else {
                otherSectorContainer.classList.remove('visible');
                otherSectorInput.required = false;
                otherSectorInput.value = ''; // Clear when hidden
            }
        }

        // Initial check (for old input)
        toggleOtherSector();

        // Listen for changes
        sectorSelect.addEventListener('change', toggleOtherSector);
    }

    // Make demand_ref_no field readonly
    if (demandRefInput) {
        demandRefInput.readOnly = true;
        demandRefInput.style.backgroundColor = '#f5f5f5';
    }

    // Add event listeners for reference number generation
    if (userTypeSelect && userTypeSelect.tagName === 'SELECT') {
        userTypeSelect.addEventListener('change', function() {
            toggleSubDivision();
            generateDemandRefNo();
        });
    }

    if (subDivisionSelect) {
        subDivisionSelect.addEventListener('change', generateDemandRefNo);
    }

    // Initial setup
    toggleSubDivision();
});
</script>

@endsection