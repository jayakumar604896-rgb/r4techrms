@extends('layouts.app')
@section('title', 'Add Daily Reports')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="bi bi-plus-circle"></i> Add Daily Progress</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('daily-progress.store') }}">
                    @csrf
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Date *</label>
                            <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" 
                                   value="{{ old('date', $today) }}" required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Client Name (Optional)</label>
                            <input type="text" name="client_name" class="form-control" 
                                   value="{{ old('client_name') }}" placeholder="Enter client name">
                        </div>
                    </div>

                    <h5 class="mb-3 border-bottom pb-2">Daily Metrics</h5>
                    
                    <div class="row">
                        @foreach($metricColumns as $key => $label)
                        <div class="col-md-4 mb-3">
                            <label class="form-label">{{ $label }}</label>
                            <input type="number" name="{{ $key }}" class="form-control" 
                                   value="{{ old($key, 0) }}" min="0" placeholder="0">
                        </div>
                        @endforeach
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Remarks</label>
                        <textarea name="remarks" class="form-control" rows="3" 
                                  placeholder="Any additional notes...">{{ old('remarks') }}</textarea>
                    </div>

                    <div class="text-end">
                        <a href="{{ route('daily-progress.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Save Daily Progress
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection