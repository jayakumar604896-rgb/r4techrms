@extends('layouts.app')

@section('title', 'Edit Company Setting')
@section('content')

<div class="content-wrapper">
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="page-title fw-bold text-primary mb-1">Edit Company Setting</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('company-settings.index') }}">Company Settings</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body">
                    <form action="{{ route('company-settings.update', $companySetting->id) }}" 
                          method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="currentImage" class="form-label">Current Image</label>
                            <div>
                                @if($companySetting->image)
                                    <img src="{{ asset('uploads/company-settings/' . $companySetting->image) }}" 
                                         alt="Current Image" 
                                         style="max-width: 300px; max-height: 200px; border-radius: 4px; border: 1px solid #ddd;">
                                @else
                                    <span class="text-muted">No image uploaded</span>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">New Image (Leave empty to keep current)</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Allowed: jpeg, png, jpg, gif | Max: 2MB</small>
                        </div>

                        <div class="mb-3">
                            <div id="imagePreview" class="mt-2" style="display: none;">
                                <img id="previewImage" src="#" alt="Preview" 
                                     style="max-width: 300px; max-height: 200px; border-radius: 4px;">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Created At</label>
                            <p class="form-control-plaintext">{{ $companySetting->created_at->format('d M, Y H:i') }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Updated At</label>
                            <p class="form-control-plaintext">{{ $companySetting->updated_at->format('d M, Y H:i') }}</p>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('company-settings.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Setting</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
    // Image preview functionality
    document.getElementById('image').addEventListener('change', function(e) {
        const previewContainer = document.getElementById('imagePreview');
        const previewImage = document.getElementById('previewImage');
        const file = e.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewContainer.style.display = 'block';
            }
            
            reader.readAsDataURL(file);
        } else {
            previewContainer.style.display = 'none';
        }
    });
</script>
@endsection