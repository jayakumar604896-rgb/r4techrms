@extends('layouts.app')

@section('title', 'Employees')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css" rel="stylesheet" />
<style>
    #order-listing thead th {
        background: #004085 !important;
        color: #ffffff !important;
        border-color: #003366 !important;
    }
    #order-listing thead th {
        position: sticky;
        top: 0;
        z-index: 5;
    }
    .selected-candidates-info {
        background: #e7f3ff;
        border-left: 4px solid #004085;
        border-radius: 8px;
    }
    .send-candidates-btn {
        background: linear-gradient(45deg, #ff9800, #ff5722);
        border: none;
        color: white;
    }
    .send-candidates-btn:hover {
        background: linear-gradient(45deg, #f57c00, #e64a19);
        color: white;
    }
    .candidate-detail-item {
        padding: 8px;
        border-bottom: 1px solid #e0e0e0;
        border-radius: 5px;
        margin-bottom: 5px;
        background: #f9f9f9;
    }
    .candidate-detail-item:last-child {
        border-bottom: none;
    }
    .resume-badge {
        font-size: 11px;
        padding: 3px 8px;
    }
    .dt-buttons .btn {
        background: #004085 !important;
        color: white !important;
        border-radius: 4px;
        padding: 5px 15px;
        font-size: 13px;
        margin-right: 5px;
        border: 1px solid #003366;
    }
    .active{
        color:#000 !important;
        text:bold !important;
    }
    .dt-buttons .btn:hover {
        background: #002752 !important;
    }
    /* Status filter tabs */
    .status-filter-tabs {
        border-bottom: 2px solid #dee2e6;
        margin-bottom: 20px;
    }
    .status-filter-tabs .nav-link {
        border: none;
        font-weight: 500;
        color: #6c757d;
        padding: 10px 20px;
        border-radius: 8px 8px 0 0;
        margin-right: 5px;
        transition: all 0.3s;
    }
    .status-filter-tabs .nav-link:hover {
        color: #004085;
        background-color: rgba(0, 64, 133, 0.05);
    }
    .status-filter-tabs .nav-link.active {
        color: #004085;
        background-color: #fff;
        border-bottom: 3px solid #004085;
        font-weight: 600;
    }
    /* Status badges */
    .badge-active {
        background-color: #28a745;
        color: white;
    }
    .badge-inactive {
        background-color: #dc3545;
        color: white;
    }
    .badge-disable {
        background-color: #ffc107;
        color: #212529;
    }
    /* Share button styling */
    .btn-share {
        background: linear-gradient(45deg, #25D366, #128C7E) !important;
        border: none !important;
        color: white !important;
    }
    .btn-share:hover {
        background: linear-gradient(45deg, #1da851, #0c6d5f) !important;
    }
    .tox-notifications-container{
        display:none !important;
    }
    .tox-tinymce-aux{
        display:none !important;
    }
    /* Search box styling */
    .dataTables_filter {
        float: right !important;
        margin-bottom: 15px;
    }
    .dataTables_filter label {
        font-weight: normal;
        white-space: nowrap;
        text-align: left;
    }
    .dataTables_filter input {
        margin-left: 0.5em;
        display: inline-block;
        width: auto;
    }
    .dataTables_info{
        display:none !important;
    }
    .paging_simple_numbers{
        display:none !important;
    }
    .status-filter-tabs {
        background: #ffffff;
        border-radius: 8px;
        padding: 0;
        margin-bottom: 20px;
        display: flex;
        flex-wrap: wrap;
        border-bottom: 1px solid #e0e0e0;
    }
    
    .status-filter-tabs .nav-item {
        margin: 0;
        padding: 0;
    }
    
    .status-filter-tabs .nav-link {
        color: #495057;
        font-weight: 500;
        font-size: 14px;
        padding: 12px 20px;
        border: none;
        border-radius: 0;
        background: transparent;
        position: relative;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .status-filter-tabs .nav-link:hover {
        color: #004085;
        background-color: #f8f9fa;
    }
    
    .status-filter-tabs .nav-link.active {
        color: #004085;
        font-weight: 600;
        background-color: transparent;
    }
    
    .status-filter-tabs .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        right: 0;
        height: 3px;
        background-color: #004085;
        border-radius: 2px 2px 0 0;
    }
    
    .status-filter-tabs .badge {
        font-size: 11px;
        font-weight: 600;
        padding: 3px 8px;
        min-width: 24px;
        text-align: center;
        border-radius: 12px;
    }
    
    .status-filter-tabs .nav-link.active .badge {
        background-color: #004085 !important;
        color: white !important;
    }

    /* Stats Cards */
    .stats-card {
        border-radius: 10px;
        padding: 15px 20px;
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
        background: white;
        height: 100%;
    }
    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .stats-card .stats-number {
        font-size: 28px;
        font-weight: 700;
        line-height: 1.2;
    }
    .stats-card .stats-label {
        font-size: 13px;
        color: #6c757d;
        font-weight: 500;
    }
    .stats-card .stats-icon {
        font-size: 32px;
        opacity: 0.7;
    }
    .stats-card-primary {
        border-left: 4px solid #004085;
    }
    .stats-card-success {
        border-left: 4px solid #28a745;
    }
    .stats-card-danger {
        border-left: 4px solid #dc3545;
    }
    .stats-card-info {
        border-left: 4px solid #17a2b8;
    }
    .stats-card-warning {
        border-left: 4px solid #ffc107;
    }
    .stats-card .stats-number.text-primary {
        color: #004085 !important;
    }
    
    @media (max-width: 768px) {
        .status-filter-tabs {
            flex-direction: column;
            border: 1px solid #e0e0e0;
        }
        
        .status-filter-tabs .nav-link {
            border-bottom: 1px solid #e0e0e0;
            padding: 12px 15px;
        }
        
        .status-filter-tabs .nav-link:last-child {
            border-bottom: none;
        }
        
        .status-filter-tabs .nav-link.active::after {
            display: none;
        }
        
        .status-filter-tabs .nav-link.active {
            background-color: #e7f3ff;
            border-left: 3px solid #004085;
        }
        
        .stats-card .stats-number {
            font-size: 22px;
        }
    }
</style>

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
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    <i class="mdi mdi-alert-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>
    </div>

    {{-- Selected Candidates Info --}}
    @if(request('from_candidates'))
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-info selected-candidates-info">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <i class="mdi mdi-account-multiple-check"></i>
                        <strong>Selected Candidates Ready to Send</strong>
                        <span id="selectedCandidatesCount" class="badge bg-primary ms-2">0</span>
                        <div class="mt-2">
                            <small class="text-muted">
                                <i class="mdi mdi-information-outline"></i>
                                Select a recruiter below to send the candidate profiles
                            </small>
                        </div>
                    </div>
                    <div>
                        <button type="button" class="btn btn-sm send-candidates-btn shadow-sm send-to-recruiter-btn me-2" >
                            <i class="mdi mdi-send"></i> Send Candidates
                        </button>
                        <a href="{{ route('candidates.index') }}" class="btn btn-sm btn-outline-primary me-2">
                            <i class="mdi mdi-arrow-left"></i> Change Selection
                        </a>
                        <button type="button" id="clearSelectionBtn" class="btn btn-sm btn-outline-danger">
                            <i class="mdi mdi-close"></i> Clear All
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Page Header --}}
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="page-title fw-bold text-primary mb-1">Employees</h3>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
                @if(request('from_candidates'))
                <li class="breadcrumb-item"><a href="{{ route('candidates.index') }}">Candidates</a></li>
                @endif
                <li class="breadcrumb-item active">Employees</li>
            </ol>
        </div>

        <div>
            @if(request('from_candidates'))
            <a href="{{ route('candidates.index') }}" class="btn btn-info btn-sm shadow-sm me-2">
                <i class="mdi mdi-arrow-left"></i> Back to Candidates
            </a>
            @endif
            <a href="{{ route('add-user') }}" class="btn btn-primary btn-sm shadow-sm">
                <i class="mdi mdi-plus"></i> Add Recruiter
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row mb-4">
        <div class="col-md-2 col-6 mb-2">
            <div class="stats-card stats-card-primary">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stats-number text-primary">{{ $counts['all'] ?? 0 }}</div>
                        <div class="stats-label">Total Recruiters</div>
                    </div>
                    <div class="stats-icon text-primary">👥</div>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6 mb-2">
            <div class="stats-card stats-card-success">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stats-number text-success">{{ $counts['Active'] ?? 0 }}</div>
                        <div class="stats-label">Active</div>
                    </div>
                    <div class="stats-icon text-success">✅</div>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6 mb-2">
            <div class="stats-card stats-card-danger">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stats-number text-danger">{{ $counts['Inactive'] ?? 0 }}</div>
                        <div class="stats-label">Inactive</div>
                    </div>
                    <div class="stats-icon text-danger">❌</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-2">
            <div class="stats-card stats-card-info">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stats-number text-info">{{ $counts['International'] ?? 0 }}</div>
                        <div class="stats-label">🌍 International</div>
                    </div>
                    <div class="stats-icon text-info">✈️</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-2">
            <div class="stats-card stats-card-warning">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="stats-number text-warning">{{ $counts['Domestic'] ?? 0 }}</div>
                        <div class="stats-label">🏠 Domestic</div>
                    </div>
                    <div class="stats-icon text-warning">🏡</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Card --}}
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">
            <h4 class="card-title mb-3 fw-semibold">Employees List</h4>
            
            {{-- Status & Type Filter Tabs --}}
            <div class="row mb-3">
                <div class="col-md-12">
                    {{-- Status Filters --}}
                    <div class="d-flex flex-wrap gap-2 mb-2">
                        <span class="fw-semibold me-2 text-muted small align-self-center">Status:</span>
                        <a href="{{ route('recruiters', ['status' => 'all', 'type' => request('type')]) }}" 
                           class="btn btn-sm btn-outline-primary {{ (!request()->has('status') || request('status') == 'all') && !request('type') ? 'active' : '' }}">
                            All ({{ $counts['all'] ?? 0 }})
                        </a>
                        <a href="{{ route('recruiters', ['status' => 'Active', 'type' => request('type')]) }}" 
                           class="btn btn-sm btn-outline-success {{ request('status') == 'Active' && !request('type') ? 'active' : '' }}">
                            Active ({{ $counts['Active'] ?? 0 }})
                        </a>
                        <a href="{{ route('recruiters', ['status' => 'Inactive', 'type' => request('type')]) }}" 
                           class="btn btn-sm btn-outline-danger {{ request('status') == 'Inactive' && !request('type') ? 'active' : '' }}">
                            Inactive ({{ $counts['Inactive'] ?? 0 }})
                        </a>
                    </div>
                    
                    {{-- Type Filters --}}
                    <div class="d-flex flex-wrap gap-2">
                        <span class="fw-semibold me-2 text-muted small align-self-center">Type:</span>
                        <a href="{{ route('recruiters', ['type' => 'all', 'status' => request('status')]) }}" 
                           class="btn btn-sm btn-outline-secondary {{ (!request()->has('type') || request('type') == 'all') && !request('status') ? 'active' : '' }}">
                            All Types
                        </a>
                        <a href="{{ route('recruiters', ['type' => 'international', 'status' => request('status')]) }}" 
                           class="btn btn-sm btn-outline-info {{ request('type') == 'international' ? 'active' : '' }}">
                            🌍 International ({{ $counts['International'] ?? 0 }})
                        </a>
                        <a href="{{ route('recruiters', ['type' => 'domestic', 'status' => request('status')]) }}" 
                           class="btn btn-sm btn-outline-warning {{ request('type') == 'domestic' ? 'active' : '' }}">
                            🏠 Domestic ({{ $counts['Domestic'] ?? 0 }})
                        </a>
                    </div>
                </div>
            </div>
            
            @if(request('from_candidates'))
            <p class="text-muted mb-3">
                <i class="mdi mdi-send-circle text-warning"></i>
                Click "Send Candidates" to send selected candidate profiles to any recruiter
            </p>
            @endif

            <div class="table-responsive">
                <table id="order-listing" class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Emp Code</th>
                            <th>Name</th>
                            <th>Designation</th>
                            <th>Contact</th>
                            <th>DOJ</th>
                            <th>DOL</th>
                            <th>User Type</th>
                            <th>Status</th>
                            <th>City</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $key => $record)
                            <tr>
                                <td>{{ ($users->currentPage() - 1) * $users->perPage() + $key + 1 }}</td>
                                <td>{{ $record->company_serial_number }}</td>
                                <td class="fw-medium">{{ $record->name }}</td>
                                <td>{{ $record->designation }}</td>
                                <td>{{ $record->staff_contact_no }}</td>
                                <td>{{ \Carbon\Carbon::parse($record->date_of_joining)->format('d-m-Y') }}</td>
                                <td>{{ $record->date_of_living }}</td>
                                <td>{{ $record->user_type }}</td>
                                <td>
                                    @php
                                        $statusClass = [
                                            'Active' => 'success',
                                            'Inactive' => 'danger',
                                            'Disable' => 'warning'
                                        ][$record->status] ?? 'secondary';
                                    @endphp

                                    <span class="badge bg-{{ $statusClass }}">
                                        {{ $record->status }}
                                    </span>
                                </td>
                                <td>{{ $record->city ?? '' }}</td>
                                <td class="text-center">
                                    @if(request('from_candidates'))
                                    <button type="button" class="btn btn-sm send-candidates-btn shadow-sm send-to-recruiter-btn me-2" 
                                            data-recruiter-id="{{ $record->id }}"
                                            data-recruiter-name="{{ $record->name }}"
                                            data-recruiter-email="{{ $record->email }}">
                                        <i class="mdi mdi-send"></i> Send Candidates
                                    </button>
                                    @endif

                                    <a href="{{ route('recruiter.show', $record->id) }}" 
                                       class="btn btn-sm btn-success text-white me-1 shadow-sm">
                                        <i class="mdi mdi-eye"></i> View
                                    </a>

                                    <a href="{{ route('recruiters.edit', $record->id) }}" 
                                       class="btn btn-sm btn-primary text-white shadow-sm">
                                        <i class="mdi mdi-pencil"></i> Edit
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                {{-- Pagination --}}
                @if($users->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm mb-0">
                            {{-- Previous Page Link --}}
                            @if ($users->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link">&laquo;</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $users->previousPageUrl() }}" rel="prev">&laquo;</a>
                                </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                                @if ($page == $users->currentPage())
                                    <li class="page-item active">
                                        <span class="page-link">{{ $page }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            @if ($users->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $users->nextPageUrl() }}" rel="next">&raquo;</a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link">&raquo;</span>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
                @endif
            </div>

        </div>
    </div>

</div>

<!-- Send Candidates Modal -->
<div class="modal fade" id="sendCandidatesModal" tabindex="-1" aria-labelledby="sendCandidatesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sendCandidatesModalLabel">Candidate Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="sendCandidatesForm" action="{{ route('candidates.send-to-recruiter') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Category</label>
                            <select name="rec_type" id="rec_type" class="form-select select2-basic">
                            <option value="">Select the type</option>
                            <option value="candidate">Candidate</option>
                            <option value="client">Client</option>
                            <option value="agent">Agent</option>
                            <option value="service provider">Service Provider</option>
                            </select>
                            <input type="hidden" name="recruiter_id" id="modal_recruiter_id">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Send to *</label>
                            <select name="send_to" id="send_to" class="form-select select2-basic" required disabled>
                                <option value="">Select the type first</option>
                            </select>
                            <div id="send_to_loading" class="mt-2 d-none">
                                <small class="text-muted">
                                    <i class="mdi mdi-loading mdi-spin"></i> Loading...
                                </small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="modal_subject" class="form-label">Email Subject *</label>
                            <input type="text" class="form-control" id="modal_subject" name="subject" 
                                   value="Candidate Profiles for Review - [count] Candidates" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="modal_message" class="form-label">Email Content *</label>
                        <textarea class="form-control textarea" id="modal_message" name="message" rows="10"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Selected Candidates</label>
                        <div id="modal_candidates_list" class="border p-3 bg-light rounded" style="max-height: 300px; overflow-y: auto;">
                            <div class="text-center text-muted">
                                <i class="mdi mdi-loading mdi-spin"></i> Loading candidates...
                            </div>
                        </div>
                        <div id="resume_summary" class="mt-2 p-2 bg-info text-white rounded d-none">
                            <i class="mdi mdi-file-document"></i>
                            <span id="resume_count">0</span> resumes will be attached automatically
                        </div>
                    </div>

                    <input type="hidden" name="candidate_ids" id="modal_candidate_ids">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-send"></i> Send Email with Resumes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Share List Modal -->
<div class="modal fade" id="shareListModal" tabindex="-1" aria-labelledby="shareListModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shareListModalLabel">Share Employees List</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">Share the recruiters list via:</p>
                
                {{-- Email Share with Input --}}
                <div class="mb-4">
                    <label class="form-label fw-bold">Email</label>
                    <div class="input-group mb-2">
                        <span class="input-group-text bg-light">
                            <i class="mdi mdi-email text-danger"></i>
                        </span>
                        <input type="email" class="form-control" id="shareEmailInput" 
                               placeholder="Enter recipient email address" value="">
                    </div>
                    <div class="d-grid">
                        <button type="button" class="btn btn-danger" id="shareEmailWithInputBtn">
                            <i class="mdi mdi-send me-2"></i> Send Email with PDF
                        </button>
                    </div>
                    <small class="text-muted">
                        <i class="mdi mdi-information-outline"></i> 
                        PDF will be generated and sent as attachment
                    </small>
                </div>

                <div class="dropdown-divider my-3"></div>
                
                <div class="mt-3">
                    <label class="form-label fw-bold">Or download data in:</label>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-primary" id="downloadExcelBtn">
                            <i class="mdi mdi-file-excel"></i> Excel
                        </button>
                       <!-- <button type="button" class="btn btn-outline-danger" id="downloadPDFBtn">
                            <i class="mdi mdi-file-pdf"></i> PDF
                        </button>-->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

<!-- Export Libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    
    $(document).ready(function() {
        function initializeSelect2() {
            $('.select2-basic').select2({
                theme: 'bootstrap4',
                width: '100%',
                placeholder: function() {
                    return $(this).data('placeholder') || 'Select an option';
                },
                allowClear: true,
                dropdownParent: $('#sendCandidatesModal') // Important for modal
            });
        }

        // Initialize Select2 on page load
        initializeSelect2();
        $('#sendCandidatesModal').on('shown.bs.modal', function() {
            // Destroy existing Select2 instances
            $('#rec_type').select2('destroy');
            $('#send_to').select2('destroy');
            
            // Re-initialize with proper modal context
            $('#rec_type').select2({
                theme: 'bootstrap4',
                width: '100%',
                placeholder: 'Select recipient type',
                allowClear: true,
                dropdownParent: $('#sendCandidatesModal')
            });
            
            $('#send_to').select2({
                theme: 'bootstrap4',
                width: '100%',
                placeholder: 'Select recipient',
                allowClear: true,
                disabled: true,
                dropdownParent: $('#sendCandidatesModal')
            });
            
            // Update email subject with count
            const count = selectedCandidates.length;
            $('#modal_subject').val(`Candidate Profiles for Review - ${count} Candidates`);
        });

        $('#sendCandidatesModal').on('hidden.bs.modal', function() {
            // Reset the form
            $('#rec_type').val('').trigger('change');
            $('#send_to').val('').trigger('change');
            $('#send_to').prop('disabled', true);
            $('#send_to_loading').addClass('d-none');
        });
        if ($.fn.dataTable.isDataTable('#order-listing')) {
            $('#order-listing').DataTable().destroy();
        }

        // Initialize DataTable with Export/Print Buttons
        const table = $('#order-listing').DataTable({
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>" +
                 "<'row'<'col-sm-12'B>>",
            buttons: [
                {
                    extend: 'excel',
                    className: 'btn btn-sm',
                    text: '<i class="mdi mdi-file-excel"></i> Excel',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9]
                    },
                    title: 'RMS_Employees_List_' + new Date().toLocaleDateString('en-GB')
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-sm',
                    text: '<i class="mdi mdi-file-pdf"></i> PDF',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9]
                    },
                    title: 'RMS_Employees_List_' + new Date().toLocaleDateString('en-GB'),
                    customize: function(doc) {
                        doc.content[1].table.widths = 
                            Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                        doc.styles.tableHeader = {
                            fillColor: '#004085',
                            color: '#ffffff',
                            bold: true
                        };
                    }
                },
                {
                    extend: 'print',
                    className: 'btn btn-sm',
                    text: '<i class="mdi mdi-printer"></i> Print',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9]
                    },
                    title: '<h3 class="text-center mb-4">Employees List</h3>',
                    customize: function(win) {
                        $(win.document.body).find('table').addClass('table table-bordered').css('font-size', '12px');
                        $(win.document.body).find('tr:nth-child(odd) td').css('background-color','#f9f9f9');
                        $(win.document.body).find('h1').css('text-align','center');
                        $(win.document.body).find('h1').css('color', '#004085');
                    }
                },
                {
                    text: '<i class="mdi mdi-share-variant"></i> Share',
                    className: 'btn btn-sm btn-share',
                    action: function (e, dt, node, config) {
                        $('#shareListModal').modal('show');
                    }
                }
            ],
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search recruiters...",
                lengthMenu: "Show _MENU_ entries",
                paginate: {
                    next: '<i class="mdi mdi-chevron-right"></i>',
                    previous: '<i class="mdi mdi-chevron-left"></i>'
                }
            },
            responsive: true,
            autoWidth: false
        });

        let selectedCandidates = [];
        let candidateDetails = {};

         // Load selected candidates from session storage
        function loadSelectedCandidates() {
            const storedCandidates = sessionStorage.getItem('selectedCandidates');
            const storedDetails = sessionStorage.getItem('candidateDetails');
            
            console.log("Stored Candidates:", storedCandidates);
            console.log("Stored Details:", storedDetails);
            
            if (storedCandidates) {
                selectedCandidates = JSON.parse(storedCandidates);
                if (storedDetails) {
                    candidateDetails = JSON.parse(storedDetails);
                }
                updateSelectedCandidatesDisplay();
                
                // Debug: Log candidate details
                console.log("Loaded Candidate Details:", candidateDetails);
                console.log("Total Candidates:", selectedCandidates.length);
            } else {
                @if(request('from_candidates'))
                alert('No candidates selected. Redirecting to candidates page.');
                window.location.href = "{{ route('candidates.index') }}";
                @endif
            }
        }

        // Update selected candidates display
        function updateSelectedCandidatesDisplay() {
            $('#selectedCandidatesCount').text(selectedCandidates.length);
        }

        // Send to recruiter button click
        $('.send-to-recruiter-btn').click(function() {
            const recruiterId = $(this).data('recruiter-id');
            const recruiterName = $(this).data('recruiter-name');
            const recruiterEmail = $(this).data('recruiter-email');

            if (selectedCandidates.length === 0) {
                alert('No candidates selected. Please go back to candidates page and select candidates.');
                return;
            }

            // Set modal values
            $('#modal_recruiter_id').val(recruiterId);
            $('#modal_recruiter_name').val(recruiterName + ' - ' + recruiterEmail);
            $('#modal_candidate_ids').val(selectedCandidates.join(','));
            
            // Update email subject with count
            $('#modal_subject').val(`Candidate Profiles for Review - ${selectedCandidates.length} Candidates`);

            // Generate email content with candidate details
            generateEmailContent(recruiterName);

            // Show selected candidates details in modal
            displayCandidateDetails();

            $('#sendCandidatesModal').modal('show');
        });

        function generateEmailContent(recruiterName) {
            let emailContent = `Greetings From Paswi!<br><br>Dear Sir / Madam,<br><br>`;
            emailContent += `Please find attached ${selectedCandidates.length} candidates profile for position.Kindly review and update status.<br><br>`;

            if (selectedCandidates.length > 0) {
                emailContent += `<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse; width: 100%; font-family: Arial, sans-serif; font-size: 12px;">
                    <thead style="background-color: #f2f2f2;">
                        <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Designation</th>
                            <th>Industry/Sector</th>
                            <th>Experience</th>
                            <th>Qualification</th>
                            <th>Location</th>
                            <th>CTC</th>
                            <th>Mobile</th>
                            <th>Resume</th>
                        </tr>
                    </thead>
                    <tbody>`;
                
                selectedCandidates.forEach((candidateId, index) => {
                    const candidate = candidateDetails[candidateId];
                    if (candidate) {
                        emailContent += `<tr>
                            <td>${index + 1}</td>
                            <td>${candidate.name || 'N/A'}</td>
                            <td>${candidate.designation || 'N/A'}</td>
                            <td>${candidate.industry || ''} / ${candidate.sector || 'N/A'}</td>
                            <td>${candidate.experience || 'N/A'}</td>
                            <td>${candidate.qualification || 'N/A'}</td>
                            <td>${candidate.location || 'N/A'}</td>
                            <td>${candidate.ctc || 'N/A'}</td>
                            <td>${candidate.mobile || 'N/A'}</td>
                            <td style="text-align: center;">${candidate.has_resume == '1' ? '✓' : '✗'}</td>
                        </tr>`;
                    }
                });
                
                emailContent += `</tbody></table><br>`;
            }

            emailContent += `With Best Regards<br><br><br><br><br>

                Paswi Manpower Consultant<br>
                (Govt. Of India - MEA's Licensed Recruitment Agent)<br>
                #20 A 9th cross street , Mangala Nagar, Porur, Chennai -600116, Tamil Nadu , India .<br>
                Web : paswigroup.com, E mail : muthiah@paswigroup.com <br>
                Mob & Whats app  : +91-9176956007,  +91-44- 46326895`;

            tinymce.get('modal_message').setContent(emailContent);
        }
        tinymce.init({
            selector: '#modal_message',  // Fixed selector
            height: 300,
            menubar: false,
            plugins: 'lists link image table code',
            toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist | table | link',
            content_style: 'body { font-family:Arial,sans-serif; font-size:14px }',
            
            // Add these options to handle validation
            setup: function (editor) {
                editor.on('init', function () {
                    // Make sure original textarea is properly configured
                    editor.getElement().setAttribute('required', 'false');
                });
            }
        });
        // Display ALL candidate details in modal
        function displayCandidateDetails() {
            let html = '';
            let resumeCount = 0;
            
            if (Object.keys(candidateDetails).length > 0) {
                html += '<div class="candidate-details">';
                selectedCandidates.forEach((candidateId, index) => {
                    const candidate = candidateDetails[candidateId];
                    if (candidate) {
                        const hasResume = candidate.has_resume == '1';
                        if (hasResume) resumeCount++;
                        
                        html += `
                            <div class="candidate-detail-item mb-3 p-3 border rounded">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <strong class="text-primary fs-5">${index + 1}. ${candidate.name || 'N/A'}</strong>
                                        <div class="small text-muted mt-1">CV Code: ${candidate.cvcode || 'N/A'}</div>
                                    </div>
                                    <div>
                                        ${hasResume ? 
                                            `<div class="btn-group btn-group-sm">
                                                <a href="${candidate.resume}" 
                                                target="_blank" 
                                                class="btn btn-success"
                                                title="Download Resume">
                                                    <i class="mdi mdi-download"></i> Download
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-info view-resume-btn"
                                                        data-resume-url="${candidate.resume}"
                                                        data-candidate-name="${candidate.name}"
                                                        data-toggle="tooltip"
                                                        title="View Resume">
                                                    <i class="mdi mdi-eye"></i> View
                                                </button>
                                            </div>` 
                                            : 
                                            `<span class="badge bg-warning">
                                                <i class="mdi mdi-file-remove"></i> No Resume
                                            </span>`
                                        }
                                    </div>
                                </div>
                                <div class="row small text-muted mt-2">
                                    <div class="col-md-6">
                                        <div class="mb-1">
                                            <i class="mdi mdi-briefcase me-1"></i>
                                            <strong>Designation:</strong> ${candidate.designation}
                                        </div>
                                        <div class="mb-1">
                                            <i class="mdi mdi-factory me-1"></i>
                                            <strong>Industry:</strong> ${candidate.industry}
                                        </div>
                                        <div class="mb-1">
                                            <i class="mdi mdi-chart-timeline me-1"></i>
                                            <strong>Experience:</strong> ${candidate.experience}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-1">
                                            <i class="mdi mdi-school me-1"></i>
                                            <strong>Qualification:</strong> ${candidate.qualification}
                                        </div>
                                        <div class="mb-1">
                                            <i class="mdi mdi-account me-1"></i>
                                            <strong>Age:</strong> ${candidate.age}
                                        </div>
                                        <div class="mb-1">
                                            <i class="mdi mdi-map-marker me-1"></i>
                                            <strong>Location:</strong> ${candidate.location}
                                        </div>
                                    </div>
                                </div>
                                <div class="row small text-muted mt-2 border-top pt-2">
                                    <div class="col-md-4">
                                        <div class="mb-1">
                                            <i class="mdi mdi-cash me-1"></i>
                                            <strong>Current CTC:</strong> ${candidate.ctc}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-1">
                                            <i class="mdi mdi-calendar-clock me-1"></i>
                                            <strong>Notice Period:</strong> ${candidate.notice_period}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-1">
                                            <i class="mdi mdi-phone me-1"></i>
                                            <strong>Mobile:</strong> 
                                            <a href="tel:${candidate.mobile}" class="text-decoration-none">${candidate.mobile}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    } else {
                        html += `
                            <div class="candidate-detail-item mb-3 p-3 border rounded bg-light">
                                <strong>${index + 1}. Candidate ID: ${candidateId}</strong>
                                <div class="text-danger small">
                                    <i class="mdi mdi-alert"></i> Details not available
                                </div>
                            </div>
                        `;
                    }
                });
                html += '</div>';
                
                // Show resume summary
                if (resumeCount > 0) {
                    $('#resume_summary').removeClass('d-none');
                    $('#resume_count').text(resumeCount);
                } else {
                    $('#resume_summary').addClass('d-none');
                }
            } else {
                html = `
                    <div class="text-center py-4">
                        <i class="mdi mdi-account-multiple text-primary" style="font-size: 48px;"></i>
                        <h5 class="mt-2">${selectedCandidates.length} Candidates Selected</h5>
                        <p class="text-muted">Candidate details could not be loaded.</p>
                        <small class="text-danger">
                            <i class="mdi mdi-alert-circle"></i> 
                            Please go back to candidates page and reselect candidates.
                        </small>
                    </div>
                `;
            }

            $('#modal_candidates_list').html(html);
            
            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();
            
            // Add event listeners for view resume buttons
            $('.view-resume-btn').click(function() {
                const resumeUrl = $(this).data('resume-url');
                const candidateName = $(this).data('candidate-name');
                viewResumeModal(resumeUrl, candidateName);
            });
        }

        // Function to open resume in modal
        function viewResumeModal(resumeUrl, candidateName) {
            // Create modal HTML
            const modalHTML = `
                <div class="modal fade" id="viewResumeModal" tabindex="-1" aria-labelledby="viewResumeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="mdi mdi-file-document me-2"></i>
                                    Resume: ${candidateName}
                                </h5>
                                <div class="d-flex gap-2">
                                    <a href="${resumeUrl}" 
                                    class="btn btn-sm btn-success"
                                    download>
                                        <i class="mdi mdi-download"></i> Download
                                    </a>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                            </div>
                            <div class="modal-body p-0">
                                <div class="resume-viewer">
                                    <!-- PDF Viewer -->
                                    <iframe src="${resumeUrl}" 
                                            style="width: 100%; height: 75vh; border: none;"
                                            title="${candidateName}'s Resume">
                                        Your browser does not support PDFs. 
                                        <a href="${resumeUrl}" download>Download the resume</a> instead.
                                    </iframe>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div class="d-flex justify-content-between w-100">
                                    <div>
                                        <small class="text-muted">
                                            <i class="mdi mdi-information"></i>
                                            PDF Viewer - Use controls to navigate
                                        </small>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Remove existing modal if any
            $('#viewResumeModal').remove();
            
            // Append modal to body
            $('body').append(modalHTML);
            
            // Show modal
            $('#viewResumeModal').modal('show');
            
            // Handle modal cleanup
            $('#viewResumeModal').on('hidden.bs.modal', function() {
                $(this).remove();
            });
        }

        // Clear selection - remove BOTH items
        $('#clearSelectionBtn').click(function() {
            sessionStorage.removeItem('selectedCandidates');
            sessionStorage.removeItem('candidateDetails');
            window.location.href = "{{ route('candidates.index') }}";
        });

        // Load selected candidates when page loads
        @if(request('from_candidates'))
            loadSelectedCandidates();
            
            // Debug info
            setTimeout(() => {
                console.log('Total candidates to send:', selectedCandidates.length);
                console.log('Candidate details loaded:', candidateDetails);
            }, 1000);
        @endif

        // SHARE FUNCTIONALITY
        // Generate CSV content
        function generateCSVContent() {
            const data = table.rows({ filter: 'applied' }).data();
            
            // Create CSV header
            let csvContent = "Emp Code,Name,Designation,Contact,DOJ,DOL,Status\n";
            
            // Add rows to CSV
            data.each(function (row) {
                // Get status from the 8th column (index 7)
                const status = $(row[7]).find('.badge').text() || $(row[7]).text() || '';
                
                // Clean data for CSV (remove commas, quotes, etc.)
                const cleanRow = [
                    row[1], // Emp Code
                    row[2], // Name
                    row[3], // Designation
                    row[4], // Contact
                    row[5], // DOJ
                    row[6], // DOL
                    status  // Status
                ].map(cell => {
                    // Escape quotes and wrap in quotes if contains comma
                    const cellStr = String(cell || '').trim();
                    if (cellStr.includes(',') || cellStr.includes('"') || cellStr.includes('\n')) {
                        return '"' + cellStr.replace(/"/g, '""') + '"';
                    }
                    return cellStr;
                });
                csvContent += cleanRow.join(',') + '\n';
            });
            
            return csvContent;
        }

        // Generate Excel content (base64)
        function generateExcelContent() {
            const csvContent = generateCSVContent();
            return btoa(unescape(encodeURIComponent(csvContent)));
        }

        // Generate text content for WhatsApp
        function generateTextContent() {
            const data = table.rows({ filter: 'applied' }).data();
            const currentDate = new Date().toLocaleDateString('en-GB');
            const totalEmployees = data.count();
            
            let text = `📋 *RECRUITERS LIST*\n\n`;
            text += `📅 Date: ${currentDate}\n`;
            text += `👥 Total Employees: ${totalEmployees}\n\n`;
            text += `================================\n\n`;
            
            // Add limited rows (max 20 for WhatsApp)
            let rowCount = 0;
            const maxRows = 20;
            
            data.each(function (row, index) {
                if (rowCount < maxRows) {
                    const status = $(row[7]).find('.badge').text() || $(row[7]).text() || '';
                    text += `*${index + 1}. ${row[2]}*\n`;
                    text += `Emp Code: ${row[1]}\n`;
                    text += `Designation: ${row[3]}\n`;
                    text += `Contact: ${row[4]}\n`;
                    text += `DOJ: ${row[5]} | DOL: ${row[6]}\n`;
                    text += `Status: ${status}\n`;
                    text += `--------------------------------\n`;
                    rowCount++;
                }
            });
            
            text += `\n_Generated from Recruitment System_`;
            
            return text;
        }

        function generateEmployeesPDFBlob(callback) {
            try {
                const table = $('#order-listing').DataTable();
                const data = table.rows({ filter: 'applied' }).data();
                const totalRows = data.count();
                
                // Prepare table headers
                const headers = ['S.No', 'Emp Code', 'Name', 'Designation', 'Contact', 'DOJ', 'DOL', 'User Type', 'Status', 'City'];
                const tableBody = [headers];
                
                // Add data rows
                data.each(function(row, index) {
                    // Extract status
                    let status = '';
                    if (row[8]) {
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = row[8];
                        status = tempDiv.textContent || tempDiv.innerText || '';
                    }
                    
                    // Extract DOJ (remove any HTML)
                    let doj = extractTextFromHTML(row[5]);
                    
                    // Extract DOL (remove any HTML)
                    let dol = extractTextFromHTML(row[6]);
                    
                    tableBody.push([
                        (index + 1).toString(),
                        extractTextFromHTML(row[1]) || '', // Emp Code
                        extractTextFromHTML(row[2]) || '', // Name
                        extractTextFromHTML(row[3]) || '', // Designation
                        extractTextFromHTML(row[4]) || '', // Contact
                        doj,
                        dol,
                        extractTextFromHTML(row[7]) || '', // User Type
                        status.trim(),
                        extractTextFromHTML(row[9]) || ''  // City
                    ]);
                });

                // Create PDF document definition
                const docDefinition = {
                    pageSize: 'A4',
                    pageOrientation: 'landscape',
                    pageMargins: [20, 20, 20, 20],
                    header: {
                        text: 'Employees List',
                        alignment: 'center',
                        margin: [0, 10, 0, 10],
                        fontSize: 16,
                        bold: true,
                        color: '#004085'
                    },
                    footer: function(currentPage, pageCount) {
                        return {
                            text: `Page ${currentPage} of ${pageCount} | Generated on ${new Date().toLocaleDateString()}`,
                            alignment: 'center',
                            margin: [0, 10, 0, 10],
                            fontSize: 8,
                            color: '#666'
                        };
                    },
                    content: [
                        {
                            text: `Total Employees: ${totalRows}`,
                            margin: [0, 0, 0, 20],
                            fontSize: 12
                        },
                        {
                            table: {
                                headerRows: 1,
                                widths: ['auto', 'auto', 'auto', 'auto', 'auto', 'auto', 'auto', 'auto', 'auto', 'auto'],
                                body: tableBody
                            },
                            layout: {
                                fillColor: function(rowIndex, node, columnIndex) {
                                    return (rowIndex === 0) ? '#004085' : null;
                                },
                                hLineWidth: function(i, node) {
                                    return (i === 0 || i === node.table.body.length) ? 1 : 0.5;
                                },
                                vLineWidth: function(i, node) {
                                    return 0.5;
                                },
                                hLineColor: function(i, node) {
                                    return '#aaa';
                                },
                                vLineColor: function(i, node) {
                                    return '#aaa';
                                },
                                paddingLeft: function(i, node) {
                                    return 5;
                                },
                                paddingRight: function(i, node) {
                                    return 5;
                                },
                                paddingTop: function(i, node) {
                                    return 3;
                                },
                                paddingBottom: function(i, node) {
                                    return 3;
                                }
                            }
                        }
                    ],
                    styles: {
                        tableHeader: {
                            bold: true,
                            fontSize: 9,
                            color: '#ffffff'
                        }
                    }
                };

                // Generate PDF
                pdfMake.createPdf(docDefinition).getBlob(callback);
                
            } catch (error) {
                console.error('PDF Generation Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to generate PDF: ' + error.message
                });
            }
        }

        function extractTextFromHTML(htmlString) {
            if (!htmlString) return '';
            
            if (typeof htmlString === 'string') {
                // Remove HTML tags
                let text = htmlString.replace(/<[^>]*>/g, ' ');
                text = text.replace(/&nbsp;/g, ' ');
                text = text.replace(/&amp;/g, '&');
                text = text.replace(/&lt;/g, '<');
                text = text.replace(/&gt;/g, '>');
                text = text.replace(/&quot;/g, '"');
                text = text.replace(/&#039;/g, "'");
                text = text.replace(/\s+/g, ' ').trim();
                return text;
            }
            
            return String(htmlString || '').trim();
        }

        $('#shareEmailWithInputBtn').click(function() {
            const btn = $(this);
            const emailInput = $('#shareEmailInput');
            const email = emailInput.val().trim();
            
            // Validate email
            if (!email) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Email Required',
                    text: 'Please enter a recipient email address.'
                });
                return;
            }
            
            // Basic email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Invalid Email',
                    text: 'Please enter a valid email address.'
                });
                return;
            }
            
            const originalHtml = btn.html();
            btn.html('<i class="mdi mdi-loading mdi-spin"></i> Generating PDF...').prop('disabled', true);
            
            generateEmployeesPDFBlob(function(blob) {
                const fileName = `RMS_Employees_List_${new Date().toLocaleDateString('en-GB').replace(/\//g, '-')}.pdf`;
                const totalEmployees = $('#order-listing').DataTable().rows({ filter: 'applied' }).count();
                
                // Create FormData
                const formData = new FormData();
                formData.append('file', blob, fileName);
                formData.append('email', email);
                formData.append('total_records', totalEmployees);
                formData.append('record_type', 'recruiters');
                formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                // Upload to server and send email
                $.ajax({
                    url: '{{ route("recruiters.send-pdf-email") }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Email Sent!',
                                text: `Employees list PDF has been sent to ${email}`,
                                timer: 3000,
                                showConfirmButton: false
                            });
                            
                            // Clear the input
                            emailInput.val('');
                            
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message || 'Failed to send email'
                            });
                        }
                    },
                    error: function(xhr) {
                        console.error('Upload error:', xhr);
                        let errorMessage = 'Failed to send email. Please try again.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMessage
                        });
                    },
                    complete: function() {
                        btn.html(originalHtml).prop('disabled', false);
                        $('#shareListModal').modal('hide');
                    }
                });
            });
        });

        // Download Excel
        $('#downloadExcelBtn').click(function() {
            $('.buttons-excel').click();
            $('#shareListModal').modal('hide');
        });

        // Download PDF
        $('#downloadPDFBtn').click(function() {
            $('.buttons-pdf').click();
            $('#shareListModal').modal('hide');
        });

        $('#rec_type').change(function() {
                const recType = $(this).val();
                const sendToSelect = $('#send_to');
                const loadingDiv = $('#send_to_loading');
                const sendEmailBtn = $('#sendEmailBtn');
                const additionalFields = $('#additional_fields');

                if (!recType) {
                    sendToSelect.empty().append('<option value="">Select the type first</option>').prop('disabled', true);
                    sendEmailBtn.prop('disabled', true);
                    additionalFields.addClass('d-none');
                    return;
                }

                // Show loading
                loadingDiv.removeClass('d-none');
                sendToSelect.prop('disabled', true);
                sendEmailBtn.prop('disabled', true);
                sendToSelect.empty().append('<option value="">Loading...</option>');

                // AJAX request to get recipients
                $.ajax({
                    url: "{{ route('get.recipients.by.type') }}",
                    type: "GET",
                    data: {
                        type: recType
                    },
                    success: function(response) {
                        sendToSelect.empty();
                        
                        if (response.success && response.data.length > 0) {
                            // Add default option
                            sendToSelect.append('<option value="">Select recipient</option>');
                            
                            // Add options
                            $.each(response.data, function(index, recipient) {
                                sendToSelect.append(
                                    $('<option></option>')
                                        .attr('value', recipient.id)
                                        .attr('data-email', recipient.email)
                                        .attr('data-name', recipient.name)
                                        .text(recipient.name + ' (' + recipient.email + ')')
                                );
                            });
                            
                            // Enable select and show additional fields
                            sendToSelect.prop('disabled', false);
                            additionalFields.removeClass('d-none');
                        } else {
                            sendToSelect.append('<option value="">No recipients found</option>');
                            sendEmailBtn.prop('disabled', true);
                        }
                    },
                    error: function(xhr) {
                        console.error('Error loading recipients:', xhr);
                        sendToSelect.empty().append('<option value="">Error loading recipients</option>');
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to load recipients. Please try again.',
                        });
                    },
                    complete: function() {
                        loadingDiv.addClass('d-none');
                    }
                });
            });
        // Load selected candidates when page loads
        @if(request('from_candidates'))
            loadSelectedCandidates();
            
            // Show welcome message
            setTimeout(() => {
                console.log('Ready to send ' + selectedCandidates.length + ' candidates to recruiters');
            }, 1000);
        @endif
    });
</script>
@include('shared.tinyMCEFront') 
@endsection