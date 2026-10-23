@extends('layouts.app')

@section('title', 'Upload Candidate File')

@section('content')

<style>
    /* Reuse styles from demand forms */
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
        font-size: 0.9rem;
    }

    .form-control,
    .form-select {
        padding: 0.80rem 1rem !important;
        height: 46px !important;
        border-radius: 8px !important;
        border: 1px solid #d4d4d4 !important;
        font-size: 0.95rem !important;
        transition: all .2s ease-in-out;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #6C63FF !important;
        box-shadow: 0 0 0 0.2rem rgba(108, 99, 255, 0.25) !important;
    }

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

    .required-star {
        color: #dc3545;
        font-weight: bold;
    }
</style>

<div class="content-wrapper">

    <div class="page-header mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h3 class="page-title">Upload Candidate File</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 mt-2">
                    <li class="breadcrumb-item">
                        <a href="{{ route('candidate-files.index') }}">Candidate Files</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Upload</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <h4 class="card-title">File Upload Details</h4>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('candidate-files.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-4">
                    {{-- Candidate Selection --}}
                    <div class="col-md-4">
                        <label>Candidate <span class="required-star">*</span></label>
                        <select name="candidate_id" class="form-select" required>
                            <option value="">Select Candidate</option>
                            @foreach($candidates as $candidate)
                                <option value="{{ $candidate->id }}" {{ old('candidate_id') == $candidate->id ? 'selected' : '' }}>
                                    {{ $candidate->candidate_name }} ({{ $candidate->mobile }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- File Title --}}
                    <div class="col-md-4">
                        <label>File Title (Optional)</label>
                        <input type="text" name="file_title" class="form-control" 
                               value="{{ old('file_title') }}" placeholder="e.g., Resume, ID Proof">
                    </div>

                    {{-- File Upload --}}
                    <div class="col-md-4">
                        <label>Choose File <span class="required-star">*</span></label>
                        <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.jpg,.png" required>
                        <small class="text-muted">Max 5MB. Allowed: PDF, DOC, DOCX, JPG, PNG</small>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <a href="{{ route('candidate-files.index') }}" class="btn btn-light me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">Upload File</button>
                </div>

            </form>

        </div>
    </div>

</div>

@endsection