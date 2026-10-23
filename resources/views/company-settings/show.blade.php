@extends('layouts.app')

@section('title', 'View Company Setting')
@section('content')

<div class="content-wrapper">
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="page-title fw-bold text-primary mb-1">View Company Setting</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('company-settings.index') }}">Company Settings</a></li>
                    <li class="breadcrumb-item active" aria-current="page">View</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">ID:</div>
                        <div class="col-md-8">{{ $companySetting->id }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Image:</div>
                        <div class="col-md-8">
                            @if($companySetting->image)
                                <img src="{{ asset('uploads/company-settings/' . $companySetting->image) }}" 
                                     alt="Setting Image" 
                                     style="max-width: 400px; max-height: 300px; border-radius: 4px; border: 1px solid #ddd;">
                            @else
                                <span class="text-muted">No Image</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Created At:</div>
                        <div class="col-md-8">{{ $companySetting->created_at->format('d M, Y H:i') }}</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Updated At:</div>
                        <div class="col-md-8">{{ $companySetting->updated_at->format('d M, Y H:i') }}</div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('company-settings.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left"></i> Back to List
                        </a>
                        <div>
                            <a href="{{ route('company-settings.edit', $companySetting->id) }}" 
                               class="btn btn-primary">
                                <i class="mdi mdi-pencil"></i> Edit
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection