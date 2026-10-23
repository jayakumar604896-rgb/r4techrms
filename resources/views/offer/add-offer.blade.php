@extends('layouts.app')

@section('title', 'Create Offer')
@section('content')
<div class="content-wrapper">
    <div class="page-header d-flex justify-content-between align-items-center">
        <h3 class="page-title">Create New Offer</h3>
        <a href="{{ route('offer.index') }}" class="btn btn-secondary">
            <i class="mdi mdi-arrow-left"></i> Back
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('offer.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <!-- User Type -->
                    <div class="col-md-4 mb-3">
                        <label for="user_type" class="form-label">User Type</label>
                        <select name="user_type" id="user_type" class="form-select @error('user_type') is-invalid @enderror">
                            <option value="">Select User Type</option>
                            <option value="Domestic" {{ old('user_type') == 'Domestic' ? 'selected' : '' }}>Domestic</option>
                            <option value="International" {{ old('user_type') == 'International' ? 'selected' : '' }}>International</option>
                        </select>
                        @error('user_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Sub Division (visible only for International) -->
                    <div class="col-md-4 mb-3 subdivision-container" id="subDivisionContainer" style="{{ old('user_type') == 'International' ? '' : 'display: none;' }}">
                        <label for="sub_division" class="form-label">Sub Division</label>
                        <select name="sub_division" id="sub_division" class="form-select @error('sub_division') is-invalid @enderror">
                            <option value="">Select Sub Division</option>
                            <option value="GuLF" {{ old('sub_division') == 'GuLF' ? 'selected' : '' }}>GuLF</option>
                            <option value="SEA" {{ old('sub_division') == 'SEA' ? 'selected' : '' }}>SEA</option>
                            <option value="EUROPE" {{ old('sub_division') == 'EUROPE' ? 'selected' : '' }}>EUROPE</option>
                            <option value="OTHERS" {{ old('sub_division') == 'OTHERS' ? 'selected' : '' }}>OTHERS</option>
                        </select>
                        @error('sub_division')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Employer Name -->
                    <div class="col-md-4 mb-3">
                        <label for="employer_name" class="form-label">Employer Name</label>
                        <input type="text" name="employer_name" id="employer_name" class="form-control @error('employer_name') is-invalid @enderror" value="{{ old('employer_name') }}">
                        @error('employer_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Job Location -->
                    <div class="col-md-4 mb-3">
                        <label for="job_location" class="form-label">Job Location</label>
                        <input type="text" name="job_location" id="job_location" class="form-control @error('job_location') is-invalid @enderror" value="{{ old('job_location') }}">
                        @error('job_location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Industry -->
                    <div class="col-md-4 mb-3">
                        <label for="industry" class="form-label">Industry</label>
                        <input type="text" name="industry" id="industry" class="form-control @error('industry') is-invalid @enderror" value="{{ old('industry') }}">
                        @error('industry')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Designation Offered -->
                    <div class="col-md-4 mb-3">
                        <label for="designation_offered" class="form-label">Designation Offered</label>
                        <input type="text" name="designation_offered" id="designation_offered" class="form-control @error('designation_offered') is-invalid @enderror" value="{{ old('designation_offered') }}">
                        @error('designation_offered')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Employment Type -->
                    <div class="col-md-4 mb-3">
                        <label for="employment_type" class="form-label">Employment Type</label>
                        <select name="employment_type" id="employment_type" class="form-select @error('employment_type') is-invalid @enderror">
                            <option value="">Select Type</option>
                            <option value="full-time" {{ old('employment_type') == 'full-time' ? 'selected' : '' }}>Full Time</option>
                            <option value="part-time" {{ old('employment_type') == 'part-time' ? 'selected' : '' }}>Part Time</option>
                            <option value="contract" {{ old('employment_type') == 'contract' ? 'selected' : '' }}>Contract</option>
                        </select>
                        @error('employment_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Salary Offered -->
                    <div class="col-md-4 mb-3">
                        <label for="salary_offered" class="form-label">Salary Offered</label>
                        <input type="number" step="0.01" name="salary_offered" id="salary_offered" class="form-control @error('salary_offered') is-invalid @enderror" value="{{ old('salary_offered') }}">
                        @error('salary_offered')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Allowance Offered -->
                    <div class="col-md-4 mb-3">
                        <label for="allowance_offered" class="form-label">Allowance Offered</label>
                        <input type="number" step="0.01" name="allowance_offered" id="allowance_offered" class="form-control @error('allowance_offered') is-invalid @enderror" value="{{ old('allowance_offered') }}">
                        @error('allowance_offered')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Total Salary -->
                    <div class="col-md-4 mb-3">
                        <label for="total_salary" class="form-label">Total Salary</label>
                        <input type="number" step="0.01" name="total_salary" id="total_salary" class="form-control @error('total_salary') is-invalid @enderror" value="{{ old('total_salary') }}">
                        @error('total_salary')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Benefits Section (checkboxes) -->
                <div class="row">
                    <div class="col-12">
                        <h5 class="mb-3">Benefits Provided</h5>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="food" id="food" class="form-check-input" value="1" {{ old('food') ? 'checked' : '' }}>
                            <label for="food" class="form-check-label">Food</label>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="accommodation" id="accommodation" class="form-check-input" value="1" {{ old('accommodation') ? 'checked' : '' }}>
                            <label for="accommodation" class="form-check-label">Accommodation</label>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="air_ticket" id="air_ticket" class="form-check-input" value="1" {{ old('air_ticket') ? 'checked' : '' }}>
                            <label for="air_ticket" class="form-check-label">Air Ticket</label>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="medical_test" id="medical_test" class="form-check-input" value="1" {{ old('medical_test') ? 'checked' : '' }}>
                            <label for="medical_test" class="form-check-label">Medical Test</label>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="certificate_attestation" id="certificate_attestation" class="form-check-input" value="1" {{ old('certificate_attestation') ? 'checked' : '' }}>
                            <label for="certificate_attestation" class="form-check-label">Certificate Attestation</label>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="pcc" id="pcc" class="form-check-input" value="1" {{ old('pcc') ? 'checked' : '' }}>
                            <label for="pcc" class="form-check-label">PCC (Police Clearance)</label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Passport Status -->
                    <div class="col-md-4 mb-3">
                        <label for="passport_status" class="form-label">Passport Status</label>
                        <input type="text" name="passport_status" id="passport_status" class="form-control @error('passport_status') is-invalid @enderror" value="{{ old('passport_status') }}" placeholder="e.g., Valid, Expired, Applied">
                        @error('passport_status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Passport Validity -->
                    <div class="col-md-4 mb-3">
                        <label for="passport_validity" class="form-label">Passport Validity</label>
                        <input type="date" name="passport_validity" id="passport_validity" class="form-control @error('passport_validity') is-invalid @enderror" value="{{ old('passport_validity') }}">
                        @error('passport_validity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Willing to Work -->
                    <div class="col-md-4 mb-3">
                        <label for="willing_to_work" class="form-label">Willing to Work</label>
                        <select name="willing_to_work" id="willing_to_work" class="form-select @error('willing_to_work') is-invalid @enderror">
                            <option value="">Select</option>
                            <option value="Yes" {{ old('willing_to_work') == 'Yes' ? 'selected' : '' }}>Yes</option>
                            <option value="No" {{ old('willing_to_work') == 'No' ? 'selected' : '' }}>No</option>
                        </select>
                        @error('willing_to_work')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Notice Period -->
                    <div class="col-md-4 mb-3">
                        <label for="notice_period" class="form-label">Notice Period (days)</label>
                        <input type="number" name="notice_period" id="notice_period" class="form-control @error('notice_period') is-invalid @enderror" value="{{ old('notice_period') }}">
                        @error('notice_period')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Other Information -->
                    <div class="col-md-8 mb-3">
                        <label for="other_information" class="form-label">Other Information</label>
                        <textarea name="other_information" id="other_information" rows="4" class="form-control @error('other_information') is-invalid @enderror">{{ old('other_information') }}</textarea>
                        @error('other_information')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Signature -->
                    <div class="col-md-4 mb-3">
                        <label for="signature" class="form-label">Signature</label>
                        <input type="text" name="signature" id="signature" class="form-control @error('signature') is-invalid @enderror" value="{{ old('signature') }}" placeholder="Name or digital signature">
                        @error('signature')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="reset" class="btn btn-secondary">Reset</button>
                    <button type="submit" class="btn btn-primary">Create Offer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    (function() {
        const userTypeSelect = document.getElementById('user_type');
        const subDivContainer = document.getElementById('subDivisionContainer');

        function toggleSubDivision() {
            if (userTypeSelect.value === 'International') {
                subDivContainer.style.display = '';
            } else {
                subDivContainer.style.display = 'none';
            }
        }

        if (userTypeSelect && subDivContainer) {
            userTypeSelect.addEventListener('change', toggleSubDivision);
            // Initial call to set correct state on page load (for validation errors)
            toggleSubDivision();
        }
    })();
</script>
@endsection