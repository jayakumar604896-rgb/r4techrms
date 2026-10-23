@extends('layouts.app')

@section('title', 'Candidates')

@section('content')
<style>
    #candidates-table thead th {
        background: #004085 !important;
        color: #ffffff !important;
        border-color: #003366 !important;
    }
    #candidates-table thead th {
        position: sticky;
        top: 0;
        z-index: 5;
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
    .dt-buttons .btn:hover {
        background: #002752 !important;
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
    .dt-button span.dt-button-down-arrow,
    .dt-button:after,
    .dt-button::after {
        display: none !important;
        content: none !important;
    }
    .buttons-excel span.dt-button-down-arrow {
        display: none !important;
    }
    .status-inprocess { 
        background: linear-gradient(135deg, #e3f2fd, #bbdefb); 
        color: #1565c0;
        border: 1px solid #90caf9;
    }
    
    .status-waittinglist
     { 
        background: linear-gradient(135deg, #fff3e0, #ffe0b2); 
        color: #ef6c00;
        border: 1px solid #ffcc80;
    }
    .status-backout
     { 
        background: linear-gradient(135deg, #fff3e0, #ffe0b2); 
        color: #ef6c00;
        border: 1px solid #ffcc80;
    }
    
    .status-rejected { 
        background: linear-gradient(135deg, #ffebee, #ffcdd2); 
        color: #c62828;
        border: 1px solid #ef9a9a;
    }
    
    .status-selected { 
        background: linear-gradient(135deg, #e8f5e9, #c8e6c9); 
        color: #2e7d32;
        border: 1px solid #a5d6a7;
    }
    .status-badge {
        padding: 4px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
        min-width: 120px;
        text-align: center;
    }
    .content-wrapper
    {
        padding: 0.75rem 1.5rem 0 !important;
    }
</style>
<div class="content-wrapper">

    {{-- Success Message --}}
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

    {{-- Enhanced Page Header --}}
    <div class="page-header d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div class="mb-3 mb-md-0">
            <h3 class="page-title fw-bold mb-2" style="color: var(--primary-blue);">Candidates Management</h3>
            <p class="text-muted mb-0">
                <i class="mdi mdi-account-multiple me-1"></i>
                Manage and organize candidate profiles
            </p>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <div class="position-relative">
                <!--<button type="button" id="chooseRecruiterBtn" class="btn btn-warning d-flex align-items-center" disabled>
                    <i class="mdi mdi-account-arrow-right me-2"></i> 
                    Assign to Recruiter
                    <span id="selectionCount" class="selection-count ms-2">0</span>
                </button>-->
                <!-- <small class="text-muted d-block mt-1">Select candidates first</small> -->
            </div>
            <a href="{{ route('add-candidates') }}" class="btn btn-primary d-flex align-items-center">
                <i class="mdi mdi-plus-circle-outline me-2"></i> Add New Candidate
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row mb-4">
        <p>Domatic / International Count</p>
        <!-- Total Candidates -->
        <div class="col-md-2 col-6 mb-3">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Candidates</h6>
                            <h3 class="mb-0">{{ $candidateCounts['total'] ?? 0 }} / {{ $internationalcandidateCounts['total'] ?? 0 }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                            <i class="mdi mdi-account-multiple text-primary" style="font-size: 24px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inprogress -->
        <div class="col-md-2 col-6 mb-3">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Inprogress</h6>
                            <h3 class="mb-0">{{ $candidateCounts['inprogress'] ?? 0 }} / {{ $internationalcandidateCounts['inprogress'] ?? 0 }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                            <i class="mdi mdi-progress-clock text-warning" style="font-size: 24px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Selected -->
        <div class="col-md-2 col-6 mb-3">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Selected</h6>
                            <h3 class="mb-0">{{ $candidateCounts['selected'] ?? 0 }} / {{ $internationalcandidateCounts['selected'] ?? 0 }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                            <i class="mdi mdi-check-circle text-success" style="font-size: 24px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Waiting List -->
        <div class="col-md-2 col-6 mb-3">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Waiting List</h6>
                            <h3 class="mb-0">{{ $candidateCounts['waitinglist'] ?? 0 }} / {{ $internationalcandidateCounts['waitinglist'] ?? 0 }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                            <i class="mdi mdi-clock-alert text-info" style="font-size: 24px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rejected -->
        <div class="col-md-2 col-6 mb-3">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Rejected</h6>
                            <h3 class="mb-0">{{ $candidateCounts['rejected'] ?? 0 }} / {{ $internationalcandidateCounts['rejected'] ?? 0 }}</h3>
                        </div>
                        <div class="bg-danger bg-opacity-10 p-3 rounded-circle">
                            <i class="mdi mdi-close-circle text-danger" style="font-size: 24px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back Out -->
        <div class="col-md-2 col-6 mb-3">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Back Out</h6>
                            <h3 class="mb-0">{{ $candidateCounts['backout'] ?? 0 }} / {{ $internationalcandidateCounts['backout'] ?? 0 }}</h3>
                        </div>
                        <div class="bg-secondary bg-opacity-10 p-3 rounded-circle">
                            <i class="mdi mdi-account-arrow-left text-secondary" style="font-size: 24px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @if(request()->has('source') || request()->has('status') || request()->has('filter') || request()->has('type'))
        @php
            $filterName = request('source') ?? request('status') ?? request('filter') ?? request('type');
            $filterLabels = [
                'database' => 'Data Base CVs',
                'website' => 'Website CVs',
                'other' => 'Other CVs',
                'shortlisted' => 'CVs Shortlisted',
                'submitted' => 'CVs Submitted / Re-Awage',
                'domestic' => 'Pan India (Domestic) CVs',
                'international' => 'International CVs',
            ];
            $displayLabel = $filterLabels[strtolower($filterName)] ?? ucwords(str_replace(['_', '-'], ' ', $filterName));
        @endphp
        <div class="alert alert-info border-0 shadow-sm d-flex justify-content-between align-items-center mb-3 py-2 px-3 rounded-3" style="background-color: #e7f3ff; color: #004085;">
            <div>
                <i class="mdi mdi-filter-check me-2" style="font-size: 18px;"></i>
                Filtered by: <strong>{{ $displayLabel }}</strong> 
                <span class="badge bg-primary text-white ms-2">{{ $candidates->count() }} records found</span>
            </div>
            <a href="{{ route('candidates.index') }}" class="btn btn-sm btn-outline-primary" style="font-size: 12px;">
                <i class="mdi mdi-close-circle-outline me-1"></i> Clear Filter
            </a>
        </div>
    @endif

    {{-- Enhanced Card --}}
    <div class="card candidates-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="mdi mdi-account-group me-2"></i>Candidates List
            </h5>
            <!-- <div class="d-flex align-items-center">
                <div class="input-group input-group-sm me-3" style="width: 250px;">
                    <span class="input-group-text bg-light border-0">
                        <i class="mdi mdi-magnify"></i>
                    </span>
                    <input type="text" id="globalSearch" class="form-control border-0 bg-light" 
                           placeholder="Search all columns...">
                </div>
                <div class="dropdown">
                    <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="mdi mdi-filter-variant"></i> Filter
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" data-filter="all">All Candidates</a></li>
                        <li><a class="dropdown-item" href="#" data-filter="with-resume">With Resume</a></li>
                        <li><a class="dropdown-item" href="#" data-filter="without-resume">Without Resume</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" data-filter="clear">Clear Filters</a></li>
                    </ul>
                </div>
            </div> -->
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="candidates-table" class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th width="50">
                                <input type="checkbox" id="select-all">
                            </th>
                            <th width="70">#</th>
                            <th>User Type</th>
                            <th>Name/Experience/Mobile</th>
                            <th>Qualification</th>
                            <th>Industry</th>
                            <th>Designation</th> 
                           <!-- <th>Mobile</th>-->
                            <!--<th>CV Code</th>-->
                            <th>Resume</th>
                            <th>Status</th>
                            <th width="160" class="text-center">Actions</th>
                        </tr>

                        {{-- Search Row --}}
                        {{-- <tr class="search-row">
                            <th></th>
                            <th></th>
                            <th><input type="text" class="form-control form-control-sm" placeholder="Search Name/Experience"></th>
                            <th><input type="text" class="form-control form-control-sm" placeholder="Search Industry"></th>
                            <th><input type="text" class="form-control form-control-sm" placeholder="Search Designation"></th> 
                            <th><input type="text" class="form-control form-control-sm" placeholder="Search Qualification"></th>
                            <th><input type="text" class="form-control form-control-sm" placeholder="Search Mobile"></th>
                            <th><input type="text" class="form-control form-control-sm" placeholder="Search CV Code"></th>
                            <th></th>
                        </tr>--}}
                    </thead>

                    <tbody>
                        @forelse($candidates as $key => $candidate)
                        <tr data-candidate-id="{{ $candidate->id }}" 
                            data-has-resume="{{ $candidate->has_resume ? 'yes' : 'no' }}">
                            <td>
                                <input type="checkbox" name="candidate_ids[]" value="{{ $candidate->id }}" 
                                       class="candidate-checkbox"
                                       data-candidate-id="{{ $candidate->id }}"
                                       data-candidate-name="{{ $candidate->candidate_name }}"
                                       data-candidate-industry="{{ $industries[$candidate->industry] ?? $candidate->industry }}"
                                       data-candidate-sector="{{ $sectores[$candidate->sector] ?? $candidate->sector }}"
                                       data-candidate-designation="{{ $candidate->designation }}" 
                                       data-candidate-qualification="{{ $candidate->qualfn }}"
                                       data-candidate-mobile="{{ $candidate->mobile }}"
                                       data-candidate-cvcode="{{ $candidate->code_no }}"
                                       data-candidate-age="{{ $candidate->age }}"
                                       data-candidate-location="{{ $candidate->post_location_applied }}"
                                       data-candidate-current-stay="{{ $candidate->current_stay }}"
                                       data-candidate-ctc="{{ $candidate->ctc }}"
                                       data-candidate-notice-period="{{ $candidate->notice_period }}"
                                       data-candidate-resume="{{ $candidate->resume }}"
                                       data-candidate-has-resume="{{ $candidate->resume ? '1' : '0' }}"
                                       data-candidate-resume-filename="{{ $candidate->resume_file_name }}">
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">{{ $key + 1 }}</span>
                            </td>
                            <td>{{ $candidate->user_type ?? '' }}</td>

                            <td>
                                <div class="d-flex align-items-center">
                                    
                                    <div>
                                        <strong>{{ $candidate->candidate_name }}</strong>
                                        <br>
                                        <small class="text-muted">Exp:{{ $candidate->total_exp ?? 'No' }}</small>
                                        <br>
                                        <small class="text-muted">Mob:{{ $candidate->mobile ?? '' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $candidate->qualfn }}</td>
                            <td>
                                <span class="badge bg-info bg-opacity-10 text-info">
                                    {{ $industries[$candidate->industry] ?? $candidate->industry }}
                                </span>
                            </td>
                            <td>
                                <span class="fw-medium">{{ $candidate->designation }}</span>
                            </td>
                            
                            <!--<td>
                                 {{ $candidate->mobile }}
                            </td>-->
                            <!--<td> {{ $candidate->code_no }} </td>-->
                            <td>
                                @if($candidate->resume)
                                    <a href="{{ asset($candidate->resume) }}" 
                                    class="badge bg-primary bg-opacity-10 text-primary"
                                    download>
                                        Resume
                                    </a>
                                @else
                                    <span class="text-muted">No File</span>
                                @endif
                            </td>
                            <td>
                                <span class="status-badge status-{{ strtolower(str_replace(' ', '', $candidate->status)) }}">
                                    {{ $candidate->status }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('candidates.show', $candidate->id) }}" 
                                   class="btn btn-sm btn-success text-white me-1 shadow-sm">
                                    <i class="mdi mdi-eye"></i> View
                                </a>
                                <a href="{{ route('candidates.edit', $candidate->id) }}" 
                                   class="btn btn-sm btn-primary text-white shadow-sm">
                                    <i class="mdi mdi-pencil"></i> Edit
                                </a>
                                @if($candidate->has_resume)
                                    <small class="text-success d-block mt-1">
                                        <i class="mdi mdi-file-check"></i> Resume Available
                                    </small>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10">
                                <div class="empty-state">
                                    <i class="mdi mdi-account-off-outline"></i>
                                    <h5 class="mt-3">No Candidates Found</h5>
                                    <p class="text-muted">Add your first candidate to get started</p>
                                    <a href="{{ route('add-candidates') }}" class="btn btn-primary mt-2">
                                        <i class="mdi mdi-plus"></i> Add Candidate
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- Send Candidates Modal -->
<div class="modal fade" id="sendCandidatesModal" tabindex="-1" aria-labelledby="sendCandidatesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sendCandidatesModalLabel">Send Candidates to Recruiter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="sendCandidatesForm" action="{{ route('candidates.send-to-recruiter') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Recruiter Type</label>
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
                        <div class="col-md-6">
                            <label class="form-label">Attachment Format</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="attachment_format" id="format_pdf" value="pdf" checked>
                                <label class="form-check-label" for="format_pdf">
                                    <i class="mdi mdi-file-pdf text-danger"></i> PDF
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="attachment_format" id="format_excel" value="excel">
                                <label class="form-check-label" for="format_excel">
                                    <i class="mdi mdi-file-excel text-success"></i> Excel
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="modal_message" class="form-label">Email Content *</label>
                        <textarea class="form-control textarea" id="modal_message" name="message" rows="6"></textarea>
                        <small class="text-muted">
                            <i class="mdi mdi-information"></i> 
                            Candidate details will be attached as a PDF file. You can customize the email message above.
                        </small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Selected Candidates</label>
                        <div id="modal_candidates_list" class="border p-3 bg-light rounded" style="max-height: 300px; overflow-y: auto;">
                            <div class="text-center text-muted">
                                <i class="mdi mdi-loading mdi-spin"></i> Loading candidates...
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="candidate_ids" id="modal_candidate_ids">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="sendEmailBtn">
                        <i class="mdi mdi-send"></i> Send Email with PDF Attachment
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
                <h5 class="modal-title" id="shareListModalLabel">Share Candidates List</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">Share the candidates list via:</p>
                
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
                        <button type="button" class="btn btn-outline-danger" id="downloadPDFBtn">
                            <i class="mdi mdi-file-pdf"></i> PDF
                        </button>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
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
        
        generatePDFBlob(function(blob) {
            const fileName = `Candidates_List_${new Date().toLocaleDateString('en-GB').replace(/\//g, '-')}.pdf`;
            const totalCandidates = $('#candidates-table').DataTable().rows({ filter: 'applied' }).count();
            
            // Create FormData
            const formData = new FormData();
            formData.append('file', blob, fileName);
            formData.append('email', email);
            formData.append('total_candidates', totalCandidates);
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

            // Upload to server and send email
            $.ajax({
                url: '{{ route("candidates.send-pdf-email") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Email Sent!',
                            text: `Candidates list PDF has been sent to ${email}`,
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
    // Define helper function at the VERY TOP (outside any document.ready)
    function extractTextFromHTML(htmlString) {
        if (!htmlString) return '';
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = htmlString;
        return (tempDiv.textContent || tempDiv.innerText || '').trim().replace(/\s+/g, ' ');
    }

    // Define PDF generation function at top level
    // Define PDF generation function at top level
    function generatePDFBlob(callback) {
        try {
            const table = $('#candidates-table').DataTable();
            const data = table.rows({ filter: 'applied' }).data();
            const totalRows = data.count();
            
            // Prepare table headers
            const headers = ['#', 'Name/Experience', 'Industry', 'Designation', 'Qualification', 'Mobile', 'CV Code', 'Status'];
            const tableBody = [headers];
            
            // Add data rows
            data.each(function(row, index) {
                // Extract status from the row (column 9 is Status)
                let status = '';
                if (row[9]) {
                    status = extractTextFromHTML(row[9]);
                }
                
                // Clean up mobile number (column 6 is Mobile)
                let mobile = row[6] || '';
                mobile = extractTextFromHTML(mobile);
                
                // Extract name/experience (column 2)
                const nameExp = extractTextFromHTML(row[2]) || '';
                
                // Extract industry (column 3)
                const industry = extractTextFromHTML(row[3]) || '';
                
                // Extract designation (column 4)
                const designation = extractTextFromHTML(row[4]) || '';
                
                // Extract qualification (column 5)
                const qualification = extractTextFromHTML(row[5]) || '';
                
                // Extract CV Code (column 7)
                const cvCode = extractTextFromHTML(row[7]) || '';
                
                tableBody.push([
                    (index + 1).toString(),
                    nameExp,
                    industry,
                    designation,
                    qualification,
                    mobile.trim(),
                    cvCode,
                    status.trim()
                ]);
            });

            // Create PDF document definition
            const docDefinition = {
                pageSize: 'A4',
                pageOrientation: 'landscape',
                pageMargins: [20, 20, 20, 20],
                header: {
                    text: 'Candidates List',
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
                        text: `Total Candidates: ${totalRows}`,
                        margin: [0, 0, 0, 20],
                        fontSize: 12
                    },
                    {
                        table: {
                            headerRows: 1,
                            widths: ['auto', 'auto', 'auto', 'auto', 'auto', 'auto', 'auto', 'auto'],
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

    // Email share button click handler - MOVED OUTSIDE document.ready
    $('#shareEmailBtn').click(function() {
        const btn = $(this);
        const originalHtml = btn.html();
        btn.html('<i class="mdi mdi-loading mdi-spin"></i> Generating PDF...').prop('disabled', true);
        
        generatePDFBlob(function(blob) {
            const fileName = `Candidates_List_${new Date().toLocaleDateString('en-GB').replace(/\//g, '-')}.pdf`;
            const totalCandidates = $('#candidates-table').DataTable().rows({ filter: 'applied' }).count();
            
            // Create FormData
            const formData = new FormData();
            formData.append('file', blob, fileName);
            formData.append('total_candidates', totalCandidates);
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

            // Upload to server and send email
            $.ajax({
                url: '{{ route("candidates.send-pdf-email") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Email Sent!',
                            text: 'Candidates list PDF has been sent successfully.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        
                        // Open email client as fallback
                        const subject = `Candidates List - ${new Date().toLocaleDateString('en-GB')}`;
                        const body = encodeURIComponent(
                            `Dear Team,\n\n` +
                            `Please find attached the candidates list PDF.\n\n` +
                            `Summary:\n` +
                            `- Total Candidates: ${totalCandidates}\n` +
                            `- Date: ${new Date().toLocaleDateString('en-GB')}\n\n` +
                            `Best regards,\n` +
                            `{{ auth()->user()->name }}`
                        );
                        window.location.href = `mailto:?subject=${subject}&body=${body}`;
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
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to upload PDF. Please try again.'
                    });
                },
                complete: function() {
                    btn.html(originalHtml).prop('disabled', false);
                    $('#shareListModal').modal('hide');
                }
            });
        });
    });

    $(document).ready(function() {
        if ($.fn.dataTable.isDataTable('#candidates-table')) {
            $('#candidates-table').DataTable().destroy();
        }

        // Initialize DataTable with Export/Print Buttons
        const table = $('#candidates-table').DataTable({
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
                    title: 'RMS_Candidate_List_' + new Date().toLocaleDateString('en-GB').replace(/\//g, '-')
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-sm',
                    text: '<i class="mdi mdi-file-pdf"></i> PDF',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9]
                    },
                    title: 'RMS_Candidate_List_' + new Date().toLocaleDateString('en-GB').replace(/\//g, '-'),
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
                // {
                //     extend: 'print',
                //     className: 'btn btn-sm',
                //     text: '<i class="mdi mdi-printer"></i> Print',
                //     exportOptions: {
                //         columns: [1, 2, 3, 4, 5, 6, 7, 8, 9],
                //         stripHtml: false
                //     },
                //     title: '<h3 class="text-center mb-4">Candidates List</h3>',
                //     customize: function(win) {
                //         $(win.document.body).find('table').addClass('table table-bordered').css('font-size', '12px');
                //         $(win.document.body).find('tr:nth-child(odd) td').css('background-color','#f9f9f9');
                //         $(win.document.body).find('h1').css('text-align','center');
                //         $(win.document.body).find('h1').css('color', '#004085');
                //     }
                // },
                {
                    extend: 'print',
                    className: 'btn btn-sm',
                    text: '<i class="mdi mdi-printer"></i> Print',
                    exportOptions: {
                        columns: [2, 3, 4, 5, 6, 7, 8, 9],  // includes status column (index 8)
                        stripHtml: false                         // keep HTML formatting (like <br>)
                    },
                    title: '<h3 class="text-center mb-4">RMS Candidates List</h3>',
                    customize: function(win) {
                        // Basic table styling
                        $(win.document.body).find('table')
                            .addClass('table table-bordered')
                            .css('font-size', '12px')
                            .css('width', '100%');

                        // Zebra striping
                        $(win.document.body).find('tr:nth-child(odd) td')
                            .css('background-color', '#f9f9f9');

                        // Title styling
                        $(win.document.body).find('h1')
                            .css('text-align', 'center')
                            .css('color', '#004085');

                        // --- Add status badge styles to the print document ---
                        const style = `
                            <style>
                                .status-badge {
                                    padding: 4px 12px;
                                    border-radius: 8px;
                                    font-size: 12px;
                                    font-weight: 600;
                                    display: inline-block;
                                    min-width: 120px;          /* ensures enough space */
                                    text-align: center;
                                    white-space: nowrap;        /* prevent wrapping inside badge */
                                }
                                .status-inprocess {
                                    background: linear-gradient(135deg, #e3f2fd, #bbdefb);
                                    color: #1565c0;
                                    border: 1px solid #90caf9;
                                }
                                .status-waittinglist {
                                    background: linear-gradient(135deg, #fff3e0, #ffe0b2);
                                    color: #ef6c00;
                                    border: 1px solid #ffcc80;
                                }
                                .status-backout {
                                    background: linear-gradient(135deg, #fff3e0, #ffe0b2);
                                    color: #ef6c00;
                                    border: 1px solid #ffcc80;
                                }
                                .status-rejected {
                                    background: linear-gradient(135deg, #ffebee, #ffcdd2);
                                    color: #c62828;
                                    border: 1px solid #ef9a9a;
                                }
                                .status-selected {
                                    background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
                                    color: #2e7d32;
                                    border: 1px solid #a5d6a7;
                                }
                                /* Ensure the status column (8th column) doesn't get squeezed */
                                th:nth-child(8), td:nth-child(8) {
                                    min-width: 140px;
                                }
                            </style>
                        `;
                        $(win.document.head).append(style);
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
                searchPlaceholder: "Search candidates...",
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

        // Select All functionality
        $('#select-all').change(function() {
            $('.candidate-checkbox').prop('checked', this.checked);
            updateSelectedCandidates();
            updateChooseRecruiterButton();
            updateSelectedCount();
        });

        // Individual checkbox change
        $('.candidate-checkbox').change(function() {
            updateSelectedCandidates();
            updateChooseRecruiterButton();
            updateSelectedCount();
            
            // Update select all checkbox
            if (!this.checked) {
                $('#select-all').prop('checked', false);
            } else {
                const allChecked = $('.candidate-checkbox:checked').length === $('.candidate-checkbox').length;
                $('#select-all').prop('checked', allChecked);
            }
            
            // Add/remove selected-row class
            $(this).closest('tr').toggleClass('selected-row', this.checked);
        });

        // Update selected candidates
        function updateSelectedCandidates() {
            selectedCandidates = [];
            candidateDetails = {};
            
            $('.candidate-checkbox:checked').each(function() {
                const candidateId = $(this).val();
                selectedCandidates.push(candidateId);
                
                // Store ALL candidate details in the object
                candidateDetails[candidateId] = {
                    id: candidateId,
                    name: $(this).data('candidate-name'),
                    industry: $(this).data('candidate-industry'),
                    sector: $(this).data('candidate-sector'),
                    designation: $(this).data('candidate-designation'),
                    qualification: $(this).data('candidate-qualification'),
                    mobile: $(this).data('candidate-mobile'),
                    cvcode: $(this).data('candidate-cvcode'),
                    age: $(this).data('candidate-age'),
                    location: $(this).data('candidate-location'),
                    current_stay: $(this).data('candidate-current-stay'),
                    ctc: $(this).data('candidate-ctc'),
                    notice_period: $(this).data('candidate-notice-period'),
                    resume: $(this).data('candidate-resume'),
                    has_resume: $(this).data('candidate-has-resume'),
                    resume_filename: $(this).data('candidate-resume-filename'),
                    experience: $(this).closest('tr').find('td').eq(2).find('small').text().replace('Exp:', '').trim()
                };
            });
        }

        // Update UI elements
        function updateChooseRecruiterButton() {
            const hasSelection = selectedCandidates.length > 0;
            $('#chooseRecruiterBtn').prop('disabled', !hasSelection);
            $('#selectionCount').text(selectedCandidates.length);
        }

        function updateSelectedCount() {
            $('#selectedCount').text(selectedCandidates.length);
        }

        // Choose Recruiter button click
        $('#chooseRecruiterBtn').click(function() {
            if (selectedCandidates.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Selection',
                    text: 'Please select at least one candidate.',
                    confirmButtonColor: '#f39c12'
                });
                return;
            }

            // Store in session storage with ALL details
            sessionStorage.setItem('selectedCandidates', JSON.stringify(selectedCandidates));
            sessionStorage.setItem('candidateDetails', JSON.stringify(candidateDetails));
            
            // Show loading
            $(this).html('<i class="mdi mdi-loading mdi-spin me-2"></i> Processing...');
            
            setTimeout(() => {
                window.location.href = "{{ route('recruiters') }}?from_candidates=true&selected=" + selectedCandidates.join(',');
            }, 500);
        });

        // Global search
        $('#globalSearch').on('keyup', function() {
            const searchText = $(this).val().toLowerCase();
            
            $('#candidates-table tbody tr').filter(function() {
                const rowText = $(this).text().toLowerCase();
                $(this).toggle(rowText.indexOf(searchText) > -1);
            });
        });

        // Column search
        $('.search-row input').on('keyup', function() {
            const columnIndex = $(this).closest('th').index();
            const searchText = $(this).val().toLowerCase();
            
            $('#candidates-table tbody tr').filter(function() {
                const cellText = $(this).find('td').eq(columnIndex).text().toLowerCase();
                $(this).toggle(cellText.indexOf(searchText) > -1);
            });
        });

        // Filter functionality
        $('[data-filter]').click(function(e) {
            e.preventDefault();
            const filter = $(this).data('filter');
            
            $('#candidates-table tbody tr').show();
            
            if (filter === 'with-resume') {
                $('#candidates-table tbody tr').filter(function() {
                    $(this).toggle($(this).data('has-resume') === 'yes');
                });
            } else if (filter === 'without-resume') {
                $('#candidates-table tbody tr').filter(function() {
                    $(this).toggle($(this).data('has-resume') === 'no');
                });
            } else if (filter === 'clear') {
                $('#globalSearch').val('');
                $('.search-row input').val('');
            }
        });

        // Add tooltips
        $('[title]').tooltip({
            placement: 'top',
            trigger: 'hover'
        });

        // SHARE FUNCTIONALITY
        // Generate CSV content
        function generateCSVContent() {
            const data = table.rows({ filter: 'applied' }).data();
            
            // Create CSV header
            let csvContent = "S.No,Name,Experience,Industry,Designation,Qualification,Mobile,CV Code\n";
            
            // Add rows to CSV
            data.each(function (row, index) {
                // Extract text from HTML for each column
                const nameExperience = extractTextFromHTML(row[2]);
                const industry = extractTextFromHTML(row[3]);
                const designation = row[4] || '';
                const qualification = row[5] || '';
                const mobile = row[6] ? row[6].replace(/\<.*?\>/g, '').trim() : ''; // Remove phone link HTML
                const cvCode = row[7] || '';
                
                // Clean data for CSV
                const cleanRow = [
                    index + 1,
                    nameExperience,
                    industry,
                    designation,
                    qualification,
                    mobile,
                    cvCode
                ].map(cell => {
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

        // Generate text content for WhatsApp
        function generateTextContent() {
            const data = table.rows({ filter: 'applied' }).data();
            const currentDate = new Date().toLocaleDateString('en-GB');
            const totalCandidates = data.count();
            
            let text = `👥 *CANDIDATES LIST*\n\n`;
            text += `📅 Date: ${currentDate}\n`;
            text += `📊 Total Candidates: ${totalCandidates}\n\n`;
            text += `================================\n\n`;
            
            // Add limited rows (max 12 for WhatsApp)
            let rowCount = 0;
            const maxRows = 12;
            
            data.each(function (row, index) {
                if (rowCount < maxRows) {
                    const nameExperience = extractTextFromHTML(row[2]);
                    const industry = extractTextFromHTML(row[3]);
                    const designation = row[4] || '';
                    const mobile = row[6] ? row[6].replace(/\<.*?\>/g, '').trim() : '';
                    const cvCode = row[7] || '';
                    
                    text += `*${index + 1}. ${nameExperience}*\n`;
                    text += `Industry: ${industry}\n`;
                    text += `Designation: ${designation}\n`;
                    text += `Mobile: ${mobile}\n`;
                    text += `CV Code: ${cvCode}\n`;
                    text += `--------------------------------\n`;
                    rowCount++;
                }
            });
            
            if (totalCandidates > maxRows) {
                text += `\n...and ${totalCandidates - maxRows} more candidates.\n`;
            }
            
            text += `\n_Generated from Recruitment System_`;
            
            return text;
        }
        
        $('#shareWhatsAppBtn').click(function() {
            generatePDFBlob(function(blob) {
                // 1. Upload the PDF to your server
                const formData = new FormData();
                formData.append('pdf', blob, `RMS_Candidate_List_${new Date().toLocaleDateString('en-GB').replace(/\//g, '-')}.pdf`);

                $.ajax({
                    url: '{{ route("temp.pdf.upload") }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            // 2. Open WhatsApp with the link
                            const message = encodeURIComponent(
                                `Here is the Candidate list PDF:\n${response.url}\n\nThe file will be available for 24 hours.`
                            );
                            const whatsappUrl = `https://wa.me/?text=${message}`;
                            window.open(whatsappUrl, '_blank');
                        } else {
                            alert('Failed to upload PDF. Please try again.');
                        }
                    },
                    error: function() {
                        alert('Error uploading PDF. Please try again.');
                    }
                });
            });
            $('#shareListModal').modal('hide');
        });

        // WhatsApp Share with Data
        $('#shareWhatsAppBtnOld').click(function() {
            const message = generateTextContent();
            const encodedMessage = encodeURIComponent(message);
            const whatsappUrl = `https://wa.me/?text=${encodedMessage}`;
            
            window.open(whatsappUrl, '_blank');
            $('#shareListModal').modal('hide');
        });

        // Email Share with Data
        $('#shareEmailBtnOld').click(function() {
            const csvContent = generateCSVContent();
            const currentDate = new Date().toLocaleDateString('en-GB');
            const totalCandidates = table.rows({ filter: 'applied' }).count();
            
            // Create CSV blob
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const dataUrl = URL.createObjectURL(blob);
            
            // Create download link
            const link = document.createElement('a');
            const fileName = `Candidates_List_${currentDate.replace(/\//g, '-')}.csv`;
            link.href = dataUrl;
            link.download = fileName;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            // Prepare email
            setTimeout(() => {
                const subject = `Candidates List - ${currentDate}`;
                let body = `Dear Team,\n\n`;
                body += `Please find attached the candidates list report.\n\n`;
                body += `Report Summary:\n`;
                body += `- Total Candidates: ${totalCandidates}\n`;
                body += `- Report Date: ${currentDate}\n`;
                body += `- Generated By: {{ auth()->user()->name }}\n\n`;
                
                // Add first 5 rows as text preview
                const data = table.rows({ filter: 'applied' }).data();
                let preview = '';
                let previewCount = 0;
                data.each(function (row) {
                    if (previewCount < 5) {
                        const nameExperience = extractTextFromHTML(row[2]);
                        const industry = extractTextFromHTML(row[3]);
                        preview += `• ${nameExperience} - ${industry}\n`;
                        previewCount++;
                    }
                });
                
                if (previewCount > 0) {
                    body += `Recent Candidates (${previewCount} of ${totalCandidates}):\n`;
                    body += preview + '\n';
                }
                
                body += `Full details are available in the attached CSV file.\n\n`;
                body += `Best regards,\n`;
                body += `Recruitment Team`;
                
                const mailtoLink = `mailto:?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
                window.location.href = mailtoLink;
            }, 500);
            
            $('#shareListModal').modal('hide');
        });

        // Download Excel
        $('#downloadExcelBtn').click(function() {
            // Trigger Excel export
            $('.buttons-excel').click();
            $('#shareListModal').modal('hide');
        });

        // Download CSV
        $('#downloadCSVBtn').click(function() {
            // Trigger CSV export
            $('.buttons-csv').click();
            $('#shareListModal').modal('hide');
        });

        // Download PDF
        $('#downloadPDFBtn').click(function() {
            // Trigger PDF export
            $('.buttons-pdf').click();
            $('#shareListModal').modal('hide');
        });

        // Initialize
        updateSelectedCount();
    });
    </script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false
    });
</script>
@endif
@endsection