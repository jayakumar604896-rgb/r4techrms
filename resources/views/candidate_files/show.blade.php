@extends('layouts.app')

@section('title', 'View File')

@section('content')
<div class="content-wrapper">
    <div class="page-header mb-4">
        <h3>File Details</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('candidate-files.index') }}">Files</a></li>
                <li class="breadcrumb-item active">View</li>
            </ol>
        </nav>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th style="width: 200px;">Candidate</th>
                    <td>{{ $candidateFile->candidate->candidate_name ?? 'N/A' }} ({{ $candidateFile->candidate->mobile ?? '' }})</td>
                </tr>
                <tr>
                    <th>File Title</th>
                    <td>{{ $candidateFile->file_title ?? '—' }}</td>
                </tr>
                <tr>
                    <th>File</th>
                    <td>
                        <a href="{{ Storage::url($candidateFile->file_path) }}" target="_blank" class="btn btn-sm btn-primary">
                            <i class="mdi mdi-download"></i> Download
                        </a>
                        <span class="ms-2">{{ basename($candidateFile->file_path) }}</span>
                    </td>
                </tr>
                <tr>
                    <th>Uploaded At</th>
                    <td>{{ $candidateFile->created_at->format('d-m-Y H:i') }}</td>
                </tr>
            </table>

            <a href="{{ route('candidate-files.index') }}" class="btn btn-light">Back</a>
            <a href="{{ route('candidate-files.edit', $candidateFile->id) }}" class="btn btn-primary">Edit</a>
        </div>
    </div>
</div>
@endsection