@extends('layouts.app')

@section('title', 'Candidate Files')

@push('styles')
<style>
    /* Table Header Blue Theme */
    #file-listing thead th {
        background: #004085 !important;
        color: #ffffff !important;
        border-color: #003366 !important;
        font-weight: bold !important;
    }

    /* Sticky Header */
    #file-listing thead th {
        position: sticky;
        top: 0;
        z-index: 5;
    }

    .table thead th,
    .jsgrid .jsgrid-table thead th {
        font-weight: 600 !important;
    }
</style>
@endpush

@section('content')
<div class="content-wrapper">

    {{-- Success Message --}}
    <div class="row mb-3">
        <div class="col-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    <i class="mdi mdi-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>
    </div>

    {{-- Page Header --}}
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="page-title fw-bold text-primary mb-1">Candidate Files</h3>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Candidate Files</li>
            </ol>
        </div>

        <a href="{{ route('candidate-files.create') }}" class="btn btn-primary btn-sm shadow-sm">
            <i class="mdi mdi-plus"></i> Upload New File
        </a>
    </div>

    {{-- Card --}}
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">

            <h4 class="card-title mb-3 fw-semibold">All Uploaded Files</h4>

            <div class="table-responsive">
                <table id="file-listing" class="table table-hover align-middle">

                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Candidate</th>
                            <th>File Title</th>
                            <th>File Name</th>
                            <th>Uploaded Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($files as $key => $file)
                            <tr>
                                <td>{{ $loop->iteration + ($files->currentPage() - 1) * $files->perPage() }}</td>
                                <td class="fw-medium">
                                    {{ $file->candidate->candidate_name ?? 'N/A' }}
                                    <br><small class="text-muted">{{ $file->candidate->mobile ?? '' }}</small>
                                </td>
                                <td>{{ $file->file_title ?? '—' }}</td>
                                <td>
                                    <a href="{{ Storage::url($file->file_path) }}" target="_blank" class="text-primary">
                                        <i class="mdi mdi-file-document-outline"></i> 
                                        {{ basename($file->file_path) }}
                                    </a>
                                </td>
                                <td>{{ $file->created_at->format('d-m-Y') }}</td>

                                {{-- Actions --}}
                                <td class="text-center">
                                    <!--<a href="{{ route('candidate-files.show', $file->id) }}" 
                                       class="btn btn-sm btn-success text-white me-1 shadow-sm">
                                        <i class="mdi mdi-eye"></i> View
                                    </a>-->

                                    <a href="{{ route('candidate-files.edit', $file->id) }}" 
                                       class="btn btn-sm btn-primary text-white me-1 shadow-sm">
                                        <i class="mdi mdi-pencil"></i> Edit
                                    </a>

                                    <form action="{{ route('candidate-files.destroy', $file->id) }}" 
                                          method="POST" 
                                          class="d-inline-block"
                                          onsubmit="return confirm('Are you sure you want to delete this file? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger text-white shadow-sm">
                                            <i class="mdi mdi-delete"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    No files uploaded yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3 d-flex justify-content-end">
                {{ $files->links() }}
            </div>

        </div>
    </div>

</div>
@endsection