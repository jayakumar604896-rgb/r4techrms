@extends('layouts.app')

@section('title', 'Daily Progress')

@section('content')
<div class="content-wrapper">
    {{-- Success/Error Messages --}}
    <div class="row mb-3">
        <div class="col-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-lg border-0" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="mdi mdi-check-circle-outline me-2" style="font-size: 24px;"></i>
                        <div>
                            <h6 class="mb-0">Success!</h6>
                            <p class="mb-0 mt-1">{{ session('success') }}</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-lg border-0" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="mdi mdi-alert-circle-outline me-2" style="font-size: 24px;"></i>
                        <div>
                            <h6 class="mb-0">Error!</h6>
                            <p class="mb-0 mt-1">{{ session('error') }}</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>
    </div>

    {{-- Page Header --}}
    <div class="page-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div class="mb-3 mb-md-0">
            <h3 class="page-title fw-bold mb-2" style="color: var(--primary-blue);">
                <i class="mdi mdi-chart-line me-2"></i>Daily Progress Report
            </h3>
            <p class="text-muted mb-0">
                <i class="mdi mdi-calendar-check me-1"></i>
                Track and manage your daily recruitment activities
            </p>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('daily-progress.import') }}" class="btn btn-import d-flex align-items-center">
                <i class="mdi mdi-upload me-2"></i> Import Excel
            </a>
            <a href="{{ route('daily-progress.create') }}" class="btn btn-add-progress d-flex align-items-center">
                <i class="mdi mdi-plus-circle-outline me-2"></i> Add Today's Progress
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row mb-4">
        <div class="col-md-3 col-6">
            <div class="card stats-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Entries</h6>
                            <h3 class="count-up mb-0">{{ $summary['total_entries'] }}</h3>
                            <small class="text-muted">Days tracked</small>
                        </div>
                        <div class="stats-icon bg-primary bg-opacity-10">
                            <i class="mdi mdi-calendar-multiple text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-6">
            <div class="card stats-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">CVs Submitted</h6>
                            <h3 class="count-up mb-0">{{ $summary['total_cv_submitted'] }}</h3>
                            <small class="text-muted">Total CVs</small>
                        </div>
                        <div class="stats-icon bg-info bg-opacity-10">
                            <i class="mdi mdi-file-document-multiple text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-6">
            <div class="card stats-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Selected</h6>
                            <h3 class="count-up mb-0">{{ $summary['total_selected'] }}</h3>
                            <small class="text-muted">{{ number_format($summary['avg_selection_rate'], 1) }}% rate</small>
                        </div>
                        <div class="stats-icon bg-success bg-opacity-10">
                            <i class="mdi mdi-checkbox-marked-circle text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-6">
            <div class="card stats-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Payment Received</h6>
                            <h3 class="count-up mb-0">₹{{ number_format($summary['total_payment']) }}</h3>
                            <small class="text-muted">Total revenue</small>
                        </div>
                        <div class="stats-icon bg-warning bg-opacity-10">
                            <i class="mdi mdi-cash-multiple text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Section --}}
    <div class="filter-card mb-4">
        <h6 class="fw-bold mb-3" style="color: var(--primary-blue);">
            <i class="mdi mdi-filter-variant me-2"></i>Filter Records
        </h6>
        <form method="GET" action="{{ route('daily-progress.index') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="filter-label">Date</label>
                    <input type="date" name="date" class="form-control" value="{{ request('date') }}" 
                           placeholder="Select date">
                </div>
                <div class="col-md-3">
                    <label class="filter-label">Month</label>
                    <select name="month" class="form-control">
                        <option value="">All Months</option>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ request('month') == $i ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="filter-label">Year</label>
                    <input type="number" name="year" class="form-control" 
                           value="{{ request('year') ?? date('Y') }}" 
                           min="2023" max="{{ date('Y') + 1 }}">
                </div>
                <div class="col-md-3">
                    <label class="filter-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="mdi mdi-magnify me-1"></i> Apply Filter
                        </button>
                        <a href="{{ route('daily-progress.index') }}" class="btn btn-light">
                            <i class="mdi mdi-refresh"></i>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Main Table Card --}}
    <div class="card daily-progress-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="mdi mdi-chart-bar me-2"></i>Daily Progress Records
            </h5>
            <div class="d-flex align-items-center">
                <div class="input-group input-group-sm me-3" style="width: 250px;">
                    <span class="input-group-text bg-light border-0">
                        <i class="mdi mdi-magnify"></i>
                    </span>
                    <input type="text" id="globalSearch" class="form-control border-0 bg-light" 
                           placeholder="Search records...">
                </div>
                <div class="badge bg-light text-dark">
                    Showing {{ $progress->count() }} of {{ $summary['total_entries'] }} records
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="daily-progress-table" class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th width="100" class="text-center">
                                <span class="text-muted">Date</span>
                            </th>
                            <th width="120">
                                <span class="text-muted">Client</span>
                            </th>
                            <th width="80" class="text-center">
                                <span class="text-muted">CVs</span>
                            </th>
                            <th width="100" class="text-center">
                                <span class="text-muted">Interviews</span>
                            </th>
                            <th width="80" class="text-center">
                                <span class="text-muted">Selected</span>
                            </th>
                            <th width="80" class="text-center">
                                <span class="text-muted">Rejected</span>
                            </th>
                            <th width="80" class="text-center">
                                <span class="text-muted">Invoiced</span>
                            </th>
                            <th width="100" class="text-center">
                                <span class="text-muted">Payment</span>
                            </th>
                            <th width="80" class="text-center">
                                <span class="text-muted">Selection %</span>
                            </th>
                            <th width="100" class="text-center">
                                <span class="text-muted">Actions</span>
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($progress as $record)
                        <tr class="highlight-on-hover" data-record-id="{{ $record->id }}">
                            <td class="text-center">
                                <div class="date-badge">
                                    {{ $record->date->format('d M') }}
                                </div>
                                <small class="text-muted d-block mt-1">
                                    {{ $record->date->format('Y') }}
                                </small>
                            </td>
                            <td>
                                @if($record->client_name)
                                    <div class="client-badge" title="{{ $record->client_name }}">
                                        {{ Str::limit($record->client_name, 20) }}
                                    </div>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="metric-badge badge-cv">
                                    {{ $record->cv_submitted }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="metric-badge badge-interview">
                                    {{ $record->total_interviews }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="metric-badge badge-selected">
                                    {{ $record->selected }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="metric-badge badge-rejected">
                                    {{ $record->rejected }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="metric-badge badge-invoice">
                                    {{ $record->invoiced }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($record->payment_received > 0)
                                    <div class="amount-display">
                                        ₹{{ number_format($record->payment_received) }}
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($record->selection_percentage > 0)
                                    <div class="percentage-circle 
                                        {{ $record->selection_percentage >= 50 ? 'percentage-high' : 
                                          ($record->selection_percentage >= 30 ? 'percentage-medium' : 'percentage-low') }}">
                                        {{ number_format($record->selection_percentage, 0) }}%
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('daily-progress.edit', $record) }}" 
                                       class="btn btn-sm btn-outline-primary" 
                                       title="Edit Record"
                                       data-bs-toggle="tooltip">
                                        <i class="mdi mdi-pencil-outline"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-info view-details"
                                            data-record-id="{{ $record->id }}"
                                            title="View Details"
                                            data-bs-toggle="tooltip">
                                        <i class="mdi mdi-eye-outline"></i>
                                    </button>
                                    <form action="{{ route('daily-progress.destroy', $record) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Delete this record?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-outline-danger"
                                                title="Delete Record"
                                                data-bs-toggle="tooltip">
                                            <i class="mdi mdi-delete-outline"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10">
                                <div class="empty-state">
                                    <i class="mdi mdi-chart-line-variant" style="font-size: 80px;"></i>
                                    <h5 class="mt-3" style="color: var(--primary-blue);">No Records Found</h5>
                                    <p class="text-muted">Start tracking your daily progress to see data here</p>
                                    <a href="{{ route('daily-progress.create') }}" class="btn btn-add-progress mt-2">
                                        <i class="mdi mdi-plus-circle-outline me-2"></i> Add First Entry
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($progress->count() > 0)
        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <small class="text-muted">
                        Showing {{ $progress->firstItem() }} to {{ $progress->lastItem() }} 
                        of {{ $progress->total() }} entries
                    </small>
                </div>
                <div>
                    {{ $progress->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

{{-- View Details Modal --}}
<div class="modal fade" id="viewDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--primary-blue), var(--accent-blue));">
                <h5 class="modal-title text-white">
                    <i class="mdi mdi-chart-box-outline me-2"></i>Progress Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalContent">
                {{-- Content loaded via AJAX --}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <a href="#" id="editRecordBtn" class="btn btn-primary">
                    <i class="mdi mdi-pencil me-1"></i> Edit Record
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        // Initialize tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();

        // Global search
        $('#globalSearch').on('keyup', function() {
            const searchText = $(this).val().toLowerCase();
            let found = false;
            
            $('#daily-progress-table tbody tr').each(function() {
                const rowText = $(this).text().toLowerCase();
                if (rowText.indexOf(searchText) > -1) {
                    $(this).show();
                    found = true;
                } else {
                    $(this).hide();
                }
            });
            
            // Show empty state if no results
            if (!found) {
                $('tbody').append(`
                    <tr id="no-results">
                        <td colspan="10">
                            <div class="empty-state py-4">
                                <i class="mdi mdi-magnify-close" style="font-size: 60px;"></i>
                                <h5 class="mt-3">No Matching Records</h5>
                                <p class="text-muted">Try a different search term</p>
                            </div>
                        </td>
                    </tr>
                `);
            } else {
                $('#no-results').remove();
            }
        });

        // View details modal
        $('.view-details').click(function() {
            const recordId = $(this).data('record-id');
            
            // Show loading
            $('#modalContent').html(`
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading details...</p>
                </div>
            `);
            
            $('#viewDetailsModal').modal('show');
            
            // Load details via AJAX (you'll need to create this endpoint)
            $.ajax({
                url: '/api/daily-progress/' + recordId + '/details',
                method: 'GET',
                success: function(response) {
                    $('#modalContent').html(response.html);
                    $('#editRecordBtn').attr('href', '/daily-progress/' + recordId + '/edit');
                },
                error: function() {
                    $('#modalContent').html(`
                        <div class="alert alert-danger">
                            <i class="mdi mdi-alert-circle"></i>
                            Failed to load details. Please try again.
                        </div>
                    `);
                }
            });
        });

        // Export functionality
        $('#exportBtn').click(function() {
            const filters = {
                date: $('input[name="date"]').val(),
                month: $('select[name="month"]').val(),
                year: $('input[name="year"]').val()
            };
            
            // Create export URL
            let exportUrl = '/daily-progress/export?';
            exportUrl += 'date=' + (filters.date || '');
            exportUrl += '&month=' + (filters.month || '');
            exportUrl += '&year=' + (filters.year || '');
            
            window.location.href = exportUrl;
        });

        // Highlight row on click
        $('#daily-progress-table tbody tr').click(function(e) {
            if (!$(e.target).is('a, button, input')) {
                $(this).toggleClass('selected-progress-row');
            }
        });

        // Clear search on escape
        $(document).keyup(function(e) {
            if (e.key === "Escape") {
                $('#globalSearch').val('').trigger('keyup');
            }
        });

        // Refresh summary cards with animation
        function refreshSummary() {
            $('.count-up').each(function() {
                const $this = $(this);
                const target = parseInt($this.data('target') || $this.text().replace(/[^0-9]/g, ''));
                const current = parseInt($this.text().replace(/[^0-9]/g, '') || 0);
                
                if (current !== target) {
                    $this.prop('Counter', current).animate({
                        Counter: target
                    }, {
                        duration: 1000,
                        easing: 'swing',
                        step: function(now) {
                            $this.text(Math.ceil(now));
                        }
                    });
                }
            });
        }

        // Call on page load
        refreshSummary();
    });

    // SweetAlert for success messages
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false,
        background: 'white',
        color: '#2c3e50',
        backdrop: 'rgba(0,0,0,0.1)'
    });
    @endif

    @if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: '{{ session('error') }}',
        timer: 3000,
        showConfirmButton: true,
        background: 'white',
        color: '#2c3e50'
    });
    @endif
</script>
@endsection