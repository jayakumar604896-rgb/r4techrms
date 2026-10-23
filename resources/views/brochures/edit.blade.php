@extends('layouts.app')Brochure

@section('title', 'Edit Brochure Details')

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
</style>

<div class="content-wrapper">

    <div class="page-header mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h3 class="page-title">Edit  Brochure Registers Details</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 mt-2">
                    <li class="breadcrumb-item">
                        <a href="{{ route('newbrochures.index') }}">  Brochure Registers </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <h4 class="card-title">Edit  Brochure Registers Details</h4>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('newbrochures.update', $brochure->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-4">

                    <!-- Brochure Name -->
                    <div class="col-md-4">
                        <label>Brochure Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control"
                            value="{{ old('name', $brochure->name) }}">
                    </div>

                    <!-- Status -->
                    <div class="col-md-4">
                        <label>Status</label>
                        <select name="is_active" class="form-select">
                            <option value="1" {{ $brochure->is_active == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ $brochure->is_active == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <!-- File -->
                    <div class="col-md-4">
                        <label>Brochure File</label>
                        <input type="file" name="file" class="form-control" accept=".pdf">
                    </div>

                </div>

                @if($brochure->file_path)
                <div class="mt-3">
                    <a href="{{ asset($brochure->file_path) }}" target="_blank"
                    class="btn btn-sm btn-outline-info">
                        <i class="mdi mdi-file-pdf"></i> View Current File
                    </a>
                </div>
                @endif

                <div class="text-end mt-4">
                    <a href="{{ route('newbrochures.index') }}" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-content-save"></i> Update Brochure
                    </button>
                </div>

                </form>


        </div>
    </div>

</div>

@endsection