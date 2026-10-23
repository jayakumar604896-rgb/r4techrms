@extends('layouts.app')

@section('title', 'Offers')

@push('styles')
<style>
    /* Table Header Blue Theme */
    #offer-listing thead th {
        background: #004085 !important;
        color: #ffffff !important;
        border-color: #003366 !important;
        font-weight: bold !important;
    }

    /* Sticky Header */
    #offer-listing thead th {
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
            <h3 class="page-title fw-bold text-primary mb-1">Offers</h3>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Offers</li>
            </ol>
        </div>

        <a href="{{ route('offer.create') }}" class="btn btn-primary btn-sm shadow-sm">
            <i class="mdi mdi-plus"></i> Add Offer
        </a>
    </div>

    {{-- Type Filter Tabs --}}
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="d-flex flex-wrap gap-2 align-items-center p-2 bg-light rounded-3">
                <span class="fw-semibold me-2 text-muted small">
                    <i class="mdi mdi-filter-variant me-1"></i>Filter by Type:
                </span>
                <a href="{{ route('offer.index', ['type' => 'all']) }}" 
                   class="btn btn-sm btn-outline-secondary {{ (!request()->has('type') || request('type') == 'all') ? 'active' : '' }}">
                    All Types ({{ $counts['all'] ?? 0 }})
                </a>
                <a href="{{ route('offer.index', ['type' => 'domestic']) }}" 
                   class="btn btn-sm btn-outline-warning {{ request('type') == 'domestic' ? 'active' : '' }}">
                    🏠 Domestic ({{ $counts['Domestic'] ?? 0 }})
                </a>
                <a href="{{ route('offer.index', ['type' => 'international']) }}" 
                   class="btn btn-sm btn-outline-info {{ request('type') == 'international' ? 'active' : '' }}">
                    🌍 International ({{ $counts['International'] ?? 0 }})
                </a>
            </div>
        </div>
    </div>

    @if(request()->has('type') && request('type') !== 'all')
        @php
            $displayType = ucfirst(request('type'));
            $categoryName = request('category') ? ' (' . ucwords(str_replace('_', ' ', request('category'))) . ')' : '';
        @endphp
        <div class="alert alert-info border-0 shadow-sm d-flex justify-content-between align-items-center mb-3 py-2 px-3 rounded-3" style="background-color: #e7f3ff; color: #004085;">
            <div>
                <i class="mdi mdi-filter-check me-2" style="font-size: 18px;"></i>
                Filtered by: <strong>{{ $displayType }}{{ $categoryName }} Offers</strong>
                <span class="badge bg-primary text-white ms-2">{{ $offers->total() }} records found</span>
            </div>
            <a href="{{ route('offer.index') }}" class="btn btn-sm btn-outline-primary" style="font-size: 12px;">
                <i class="mdi mdi-close-circle-outline me-1"></i> Clear Filter
            </a>
        </div>
    @endif

    {{-- Card --}}
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">

            <h4 class="card-title mb-3 fw-semibold">Offers List</h4>

            <div class="table-responsive">
                <table id="offer-listing" class="table table-hover align-middle">

                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Employer Name</th>
                            <th>Job Location</th>
                            <th>Industry</th>
                            <th>Designation</th>
                            <th>Salary</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($offers as $key => $offer)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td class="fw-medium">{{ $offer->employer_name }}</td>
                                <td>{{ $offer->job_location }}</td>
                                <td>{{ $offer->industry }}</td>
                                <td>{{ $offer->designation_offered }}</td>
                                <td>{{ $offer->total_salary }}</td>

                                {{-- Actions --}}
                                <td class="text-center">
                                    <a href="{{ route('offer.view', $offer->id) }}" 
                                       class="btn btn-sm btn-success text-white me-1 shadow-sm">
                                        <i class="mdi mdi-eye"></i> View
                                    </a>

                                    <a href="{{ route('offer.edit', $offer->id) }}" 
                                       class="btn btn-sm btn-primary text-white shadow-sm">
                                        <i class="mdi mdi-pencil"></i> Edit
                                    </a>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>
@endsection
