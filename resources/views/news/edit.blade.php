@extends('layouts.app')

@section('title', 'Edit News')

@section('content')

<style>
    /* Reuse your Demand Create styles */
    .page-header h3 { font-weight:700; font-size:26px; color:#3A3A8B; }
    .card { border-radius:14px; border:none; background:#fff; box-shadow:0px 8px 30px rgba(0,0,0,0.06); }
    .card-title { font-weight:700; font-size:22px; padding-left:15px; border-left:4px solid #6C63FF; margin-bottom:25px; }
    label { font-weight:600; font-size:.9rem; }
    .form-control, .form-select {
        height:46px; border-radius:8px; padding:.75rem 1rem;
        border:1px solid #d4d4d4;
    }
    .form-control:focus, .form-select:focus {
        border-color:#6C63FF;
        box-shadow:0 0 0 .2rem rgba(108,99,255,.25);
    }
    .form-section {
        background:#f9f9ff;
        padding:20px;
        border-radius:10px;
        border-left:4px solid #6C63FF;
        margin-bottom:25px;
    }
    .required-star { color:#dc3545; }
    .btn-primary {
        background:linear-gradient(90deg,#4B49AC,#6C63FF);
        border:none; padding:12px 25px; border-radius:8px;
        font-weight:600;
    }
</style>

<div class="content-wrapper">

    {{-- Page Header --}}
    <div class="page-header mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h3 class="page-title">Edit News</h3>
            <ol class="breadcrumb mb-0 mt-2">
                <li class="breadcrumb-item">
                    <a href="{{ route('news.index') }}">News</a>
                </li>
                <li class="breadcrumb-item active">Edit News</li>
            </ol>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <h4 class="card-title">New Details</h4>

            {{-- Validation Errors --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('news.update', $news->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-section">
                    <h5 class="section-title">New Details</h5>
                    <div class="row g-4">

                        <!-- New Name -->
                        <div class="col-md-3">
                            <label>News Title <span class="required-star">*</span></label>
                            <input type="text" name="title" class="form-control"
                                value="{{ old('title',$news->title) }}" placeholder="Enter News Title" required>
                        </div>
                        <div class="col-md-3">
                            <label>News Sub-Title</label>
                            <input type="text" name="sub_title" class="form-control"
                                value="{{ old('sub_title',$news->sub_title) }}" placeholder="Enter News Sub-Title">
                        </div>
                        <div class="col-md-3">
                            <label>Date <span class="required-star">*</span></label>
                            <input type="date" name="news_date" class="form-control"
                                value="{{ old('news_date',$news->news_date) }}" required>
                        </div>
                        <!-- Status -->
                        <div class="col-md-3">
                            <label>Status</label>
                            <select name="is_active" class="form-select">
                                <option value="1" @if($news->is_active==1) 'selected' @endif>Active</option>
                                <option value="0" @if($news->is_active==0) 'selected' @endif>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label>News Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">

                            @if($news->image)
                                <div class="mt-2">
                                    <img src="{{ asset($news->image) }}" alt="Current Image" style="max-height: 100px; border-radius: 4px;">
                                    <p class="text-muted small mt-1">Current image. Upload a new one to replace it.</p>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <a href="{{ route('news.index') }}" class="btn btn-light me-2">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-content-save"></i> Update News
                    </button>
                </div>

                </form>


        </div>
    </div>
</div>

@endsection
