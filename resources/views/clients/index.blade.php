@extends('layouts.app')

@section('title', 'Clients')

@section('content')
<style>
	/* Type filter button active states */
	.btn-outline-info.active {
	    background-color: #17a2b8;
	    color: white !important;
	    border-color: #17a2b8;
	}
	
	.btn-outline-warning.active {
	    background-color: #ffc107;
	    color: #212529 !important;
	    border-color: #ffc107;
	}
	
	.btn-outline-secondary.active {
	    background-color: #6c757d;
	    color: white !important;
	    border-color: #6c757d;
	}
	
	/* Filter container */
	.d-flex.flex-wrap.gap-2 .btn-sm {
	    font-size: 13px;
	    padding: 5px 15px;
	    border-radius: 5px;
	}
	
	.d-flex.flex-wrap.gap-2 .btn-sm.active {
	    font-weight: 600;
	    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
	}
	
	@media (max-width: 768px) {
	    .d-flex.flex-wrap.gap-2 .btn-sm {
	        font-size: 12px;
	        padding: 4px 12px;
	    }
	}
    #clients-table thead th {
        background: #004085 !important;
        color: #ffffff !important;
        border-color: #003366 !important;
    }
    #clients-table thead th {
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
    .selected-row {
        background-color: rgba(0, 64, 133, 0.1) !important;
    }
    .selection-count {
        background: #ffc107;
        color: #000;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: bold;
    }
    .empty-state {
        text-align: center;
        padding: 50px 20px;
        background: #f8f9fa;
        border-radius: 10px;
    }
    .empty-state i {
        font-size: 60px;
        color: #6c757d;
        margin-bottom: 20px;
    }
    .empty-state h5 {
        color: #343a40;
        margin-bottom: 10px;
    }
    .empty-state p {
        color: #6c757d;
        margin-bottom: 20px;
    }
    .badge-domestic {
        background-color: #e3f2fd !important;
        color: #1976d2 !important;
    }
    .badge-international {
        background-color: #f3e5f5 !important;
        color: #7b1fa2 !important;
    }
    .file-badge {
        background-color: #e8f5e9 !important;
        color: #2e7d32 !important;
        cursor: pointer;
    }
    .file-badge:hover {
        background-color: #c8e6c9 !important;
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
            <h3 class="page-title fw-bold mb-2" style="color: var(--primary-blue);">Clients Management</h3>
            <!-- <p class="text-muted mb-0">
                <i class="mdi mdi-account-multiple me-1"></i>
                Manage and organize client profiles
            </p> -->
        </div>

        <div class="d-flex flex-wrap gap-2">
            <!--<div class="position-relative">
                <button type="button" id="chooseRecruiterBtn" class="btn btn-warning d-flex align-items-center" disabled>
                    <i class="mdi mdi-account-arrow-right me-2"></i> 
                    Assign to Recruiter
                    <span id="selectionCount" class="selection-count ms-2">0</span>
                </button>
            </div>-->
            <a href="{{ route('clients.create') }}" class="btn btn-primary d-flex align-items-center">
                <i class="mdi mdi-plus-circle-outline me-2"></i> Add New Client
            </a>
        </div>
    </div>
{{-- Stats Cards with Type Filters --}}
<!-- <div class="row mb-4">
    <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Clients</h6>
                        <h3 class="mb-0">{{ $clients->count() }}</h3>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                        <i class="mdi mdi-account-multiple text-primary" style="font-size: 24px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Active</h6>
                        <h3 class="mb-0">{{ $clients->where('status', 1)->count() }}</h3>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                        <i class="mdi mdi-account-check text-success" style="font-size: 24px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-6">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Inactive</h6>
                        <h3 class="mb-0">{{ $clients->where('status', 2)->count() }}</h3>
                    </div>
                    <div class="bg-danger bg-opacity-10 p-3 rounded-circle">
                        <i class="mdi mdi-account-off text-danger" style="font-size: 24px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->

{{-- Type Filter Tabs --}}
<div class="row mb-3">
    <div class="col-md-12">
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <span class="fw-semibold me-2 text-muted small">Type:</span>
            <a href="{{ route('clients.index', ['type' => 'all']) }}" 
               class="btn btn-sm btn-outline-secondary {{ (!request()->has('type') || request('type') == 'all') ? 'active' : '' }}">
                All Types
            </a>
            <a href="{{ route('clients.index', ['type' => 'international']) }}" 
               class="btn btn-sm btn-outline-info {{ request('type') == 'international' ? 'active' : '' }}">
                🌍 International ({{ $counts['International'] ?? 0 }})
            </a>
            <a href="{{ route('clients.index', ['type' => 'domestic']) }}" 
               class="btn btn-sm btn-outline-warning {{ request('type') == 'domestic' ? 'active' : '' }}">
                🏠 Domestic ({{ $counts['Domestic'] ?? 0 }})
            </a>
        </div>
    </div>
</div>
    {{-- Stats Cards --}}
    <div class="row mb-4">
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Clients</h6>
                            <h3 class="mb-0">{{ $clients->count() }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                            <i class="mdi mdi-account-multiple text-primary" style="font-size: 24px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

     

        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Active</h6>
                            <h3 class="mb-0">{{ $clients->where('status', 1)->count() }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                            <i class="mdi mdi-account-check text-success" style="font-size: 24px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Inactive</h6>
                            <h3 class="mb-0">{{ $clients->where('status', 2)->count() }}</h3>
                        </div>
                        <div class="bg-danger bg-opacity-10 p-3 rounded-circle">
                            <i class="mdi mdi-account-off text-danger" style="font-size: 24px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Enhanced Card --}}
    <div class="card clients-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="mdi mdi-account-group me-2"></i>Clients List
            </h5>
        </div>

        <div class="card-body p-0">
            @if($clients->count() > 0)
            <div class="table-responsive">
                <table id="clients-table" class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th width="50">
                                <input type="checkbox" id="select-all">
                            </th>
                            <th width="70">#</th>
                            <th>User Type</th>
                            <th>Sub Division</th>
                            <th>Contact Person</th>
                            <th>Industry</th>
                            <th>Company Location</th>
                            <th>Mobile</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th width="160" class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($clients as $key => $client)
                        <tr data-client-id="{{ $client->id }}" 
                            data-status="{{ $client->status }}">
                            <td>
                                <input type="checkbox" name="client_ids[]" value="{{ $client->id }}" 
                                       class="client-checkbox"
                                       data-client-id="{{ $client->id }}"
                                       data-user-type="{{ $client->user_type }}"
                                       data-sub-division="{{ $client->sub_division }}"
                                       data-industry-type="{{ $client->industry_type }}"
                                       data-company-location="{{ $client->company_location }}"
                                       data-gst-number="{{ $client->gst_number }}"
                                       data-contact-person="{{ $client->contact_person }}"
                                       data-desination="{{ $client->desination }}"
                                       data-mobile-number="{{ $client->mobile_number }}"
                                       data-alternate-number="{{ $client->alternate_number }}"
                                       data-email-id="{{ $client->email_id }}"
                                       data-attachment-jd="{{ $client->attachment_of_jd }}"
                                       data-attachment-other-docs="{{ $client->attachment_of_other_docs }}"
                                       data-status="{{ $client->status }}">
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">{{ $key + 1 }}</span>
                            </td>
                            <td>
                                @if($client->user_type)
                                <span class="badge {{ $client->user_type == 'Domestic' ? 'badge-domestic' : 'badge-international' }}">
                                    {{ $client->user_type }}
                                </span>
                                @else
                                <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($client->sub_division)
                                <span class="badge bg-info bg-opacity-10 text-info">
                                    {{ $client->sub_division }}
                                </span>
                                @else
                                <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-placeholder bg-primary bg-opacity-10 text-primary rounded-circle 
                                                d-flex align-items-center justify-content-center me-3" 
                                         style="width: 36px; height: 36px; font-weight: bold;">
                                        {{ strtoupper(substr($client->contact_person, 0, 1)) }}
                                    </div>
                                    <div>
                                        <strong>{{ $client->contact_person ?? 'N/A' }}</strong>
                                        @if($client->desination)
                                        <br>
                                        <small class="text-muted">{{ $client->desination }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($client->industry_type)
                                <span class="fw-medium">{{ $client->industry_type }}</span>
                                @else
                                <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($client->company_location)
                                <span class="text-muted">{{ $client->company_location }}</span>
                                @else
                                <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <div>
                                    <a href="tel:{{ $client->mobile_number }}" class="text-decoration-none">
                                        <i class="mdi mdi-phone me-1"></i> {{ $client->mobile_number ?? 'N/A' }}
                                    </a>
                                    @if($client->alternate_number)
                                    <br>
                                    <small class="text-muted">Alt: {{ $client->alternate_number }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($client->email_id)
                                <a href="mailto:{{ $client->email_id }}" class="text-decoration-none">
                                    <i class="mdi mdi-email me-1"></i> {{ $client->email_id }}
                                </a>
                                @else
                                <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $client->status == 1 ? 'success' : 'danger' }} bg-opacity-10 text-{{ $client->status == 1 ? 'success' : 'danger' }}">
                                    {{ $client->status == 1 ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('clients.show', $client->id) }}" 
                                       class="btn btn-sm btn-success text-white me-1 shadow-sm"
                                       title="View Client">
                                        <i class="mdi mdi-eye"></i>
                                    </a>
                                    <a href="{{ route('clients.edit', $client->id) }}" 
                                       class="btn btn-sm btn-primary text-white shadow-sm"
                                       title="Edit Client">
                                        <i class="mdi mdi-pencil"></i>
                                    </a>
                                    <a href="{{ url('client/prints/'.$client->id) }}" target="_blank" 
                                       class="btn btn-sm btn-secondary text-white shadow-sm"
                                       title="Print Client">
                                        <i class="fa fa-print"></i>
                                    </a>
                                    @if($client->attachment_of_jd || $client->attachment_of_other_docs)
                                    <button type="button" 
                                            class="btn btn-sm btn-info text-white ms-1 shadow-sm dropdown-toggle"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false"
                                            title="Attachments">
                                        <i class="mdi mdi-paperclip"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        @if($client->attachment_of_jd)
                                        <li>
                                            <a class="dropdown-item" href="{{ asset($client->attachment_of_jd) }}" target="_blank">
                                                <i class="mdi mdi-file-document me-2"></i> Job Description
                                            </a>
                                        </li>
                                        @endif
                                        @if($client->attachment_of_other_docs)
                                        <li>
                                            <a class="dropdown-item" href="{{ asset($client->attachment_of_other_docs) }}" target="_blank">
                                                <i class="mdi mdi-file-multiple me-2"></i> Other Documents
                                            </a>
                                        </li>
                                        @endif
                                    </ul>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state">
                <i class="mdi mdi-account-off-outline"></i>
                <h5 class="mt-3">No Clients Found</h5>
                <p class="text-muted">Add your first client to get started</p>
                <a href="{{ route('clients.create') }}" class="btn btn-primary mt-2">
                    <i class="mdi mdi-plus"></i> Add Client
                </a>
            </div>
            @endif
        </div>
    </div>

</div>

<!-- Share List Modal -->
<div class="modal fade" id="shareListModal" tabindex="-1" aria-labelledby="shareListModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shareListModalLabel">Share Clients List</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">Share the clients list via:</p>
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-success btn-lg" id="shareWhatsAppBtn">
                        <i class="mdi mdi-whatsapp me-2"></i> WhatsApp
                    </button>
                    <button type="button" class="btn btn-danger btn-lg" id="shareEmailBtn">
                        <i class="mdi mdi-email me-2"></i> Email
                    </button>
                </div>
                
                <div class="mt-4">
                    <label class="form-label">Or download data in:</label>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-primary" id="downloadExcelBtn">
                            <i class="mdi mdi-file-excel"></i> Excel
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="downloadCSVBtn">
                            <i class="mdi mdi-file-delimited"></i> CSV
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
<script>
    $(document).ready(function() {
        // Only initialize DataTable if there are clients
        if ($('#clients-table').length > 0) {
            if ($.fn.dataTable.isDataTable('#clients-table')) {
                $('#clients-table').DataTable().destroy();
            }

            // Initialize DataTable with Export/Print Buttons
            const table = $('#clients-table').DataTable({
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
                            columns: [1, 2, 3, 4, 5, 6, 7, 8, 9] // Export columns except checkbox and actions
                        },
                        title: 'RMS_Clients_List_' + new Date().toLocaleDateString('en-GB')
                    },
                    {
                        extend: 'pdf',
                        className: 'btn btn-sm',
                        text: '<i class="mdi mdi-file-pdf"></i> PDF',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7, 8, 9] // Export columns except checkbox and actions
                        },
                        title: 'RMS_Clients_List_' + new Date().toLocaleDateString('en-GB'),
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
                            columns: [1, 2, 3, 4, 5, 6, 7, 8, 9] // Export columns except checkbox and actions
                        },
                        title: '<h3 class="text-center mb-4">Clients List</h3>',
                        customize: function(win) {
                            $(win.document.body).find('table').addClass('table table-bordered').css('font-size', '12px');
                            $(win.document.body).find('tr:nth-child(odd) td').css('background-color','#f9f9f9');
                            $(win.document.body).find('h1').css('text-align','center');
                            $(win.document.body).find('h1').css('color', '#004085');
                        }
                    },
                    // {
                    //     text: '<i class="mdi mdi-share-variant"></i> Share',
                    //     className: 'btn btn-sm btn-share',
                    //     action: function (e, dt, node, config) {
                    //         $('#shareListModal').modal('show');
                    //     }
                    // }
                ],
                pageLength: 10,
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search clients...",
                    lengthMenu: "Show _MENU_ entries",
                    zeroRecords: "No matching clients found",
                    paginate: {
                        next: '<i class="mdi mdi-chevron-right"></i>',
                        previous: '<i class="mdi mdi-chevron-left"></i>'
                    }
                },
                responsive: true,
                autoWidth: false,
                columnDefs: [
                    { orderable: false, targets: [0, 10] }, // Make checkbox and actions columns not sortable
                    { searchable: false, targets: [0, 10] } // Make checkbox and actions columns not searchable
                ]
            });

            let selectedClients = [];
            let clientDetails = {};

            // Select All functionality
            $('#select-all').change(function() {
                $('.client-checkbox').prop('checked', this.checked);
                updateSelectedClients();
                updateChooseRecruiterButton();
                updateSelectedCount();
                
                // Add/remove selected-row class for all rows
                $('#clients-table tbody tr').toggleClass('selected-row', this.checked);
            });

            // Individual checkbox change
            $(document).on('change', '.client-checkbox', function() {
                updateSelectedClients();
                updateChooseRecruiterButton();
                updateSelectedCount();
                
                // Update select all checkbox
                if (!this.checked) {
                    $('#select-all').prop('checked', false);
                } else {
                    const allChecked = $('.client-checkbox:checked').length === $('.client-checkbox').length;
                    $('#select-all').prop('checked', allChecked);
                }
                
                // Add/remove selected-row class
                $(this).closest('tr').toggleClass('selected-row', this.checked);
            });

            // Update selected clients
            function updateSelectedClients() {
                selectedClients = [];
                clientDetails = {};
                
                $('.client-checkbox:checked').each(function() {
                    const clientId = $(this).val();
                    selectedClients.push(clientId);
                    
                    // Store client details
                    clientDetails[clientId] = {
                        user_type: $(this).data('user-type'),
                        sub_division: $(this).data('sub-division'),
                        industry_type: $(this).data('industry-type'),
                        company_location: $(this).data('company-location'),
                        gst_number: $(this).data('gst-number'),
                        contact_person: $(this).data('contact-person'),
                        desination: $(this).data('desination'),
                        mobile_number: $(this).data('mobile-number'),
                        alternate_number: $(this).data('alternate-number'),
                        email_id: $(this).data('email-id'),
                        attachment_of_jd: $(this).data('attachment-jd'),
                        attachment_of_other_docs: $(this).data('attachment-other-docs'),
                        status: $(this).data('status')
                    };
                });
            }

            // Update UI elements
            function updateChooseRecruiterButton() {
                const hasSelection = selectedClients.length > 0;
                $('#chooseRecruiterBtn').prop('disabled', !hasSelection);
                $('#selectionCount').text(selectedClients.length);
            }

            function updateSelectedCount() {
                $('#selectedCount').text(selectedClients.length);
            }

            // Choose Recruiter button click
            $('#chooseRecruiterBtn').click(function() {
                if (selectedClients.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Selection',
                        text: 'Please select at least one client.',
                        confirmButtonColor: '#f39c12'
                    });
                    return;
                }

                // Store in session storage
                sessionStorage.setItem('selectedClients', JSON.stringify(selectedClients));
                sessionStorage.setItem('clientDetails', JSON.stringify(clientDetails));
                
                // Show loading
                $(this).html('<i class="mdi mdi-loading mdi-spin me-2"></i> Processing...');
                
                setTimeout(() => {
                    window.location.href = "{{ route('recruiters') }}?from_clients=true&selected=" + selectedClients.join(',');
                }, 500);
            });

            // Add tooltips
            $('[title]').tooltip({
                placement: 'top',
                trigger: 'hover'
            });

            // SHARE FUNCTIONALITY
            // Helper function to extract text from HTML
            function extractTextFromHTML(htmlString) {
                if (!htmlString) return '';
                
                // Create a temporary div element
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = htmlString;
                
                // Get the text content and remove extra whitespace
                const text = tempDiv.textContent || tempDiv.innerText || '';
                return text.trim().replace(/\s+/g, ' ');
            }

            // Generate CSV content from DataTable with all your fields
            function generateCSVContent() {
                const data = table.rows({ filter: 'applied' }).data();
                
                // Create CSV header - matching your database columns
                let csvContent = "S.No,User Type,Sub Division,Contact Person,Designation,Industry Type,Company Location,Mobile Number,Alternate Number,Email ID,GST Number,Status,JD Attachment,Other Docs Attachment\n";
                
                // Add rows to CSV
                data.each(function (row, index) {
                    // Extract data from table cells
                    const userType = extractTextFromHTML(row[2]);
                    const subDivision = extractTextFromHTML(row[3]);
                    const contactPerson = extractTextFromHTML(row[4]);
                    const industryType = extractTextFromHTML(row[5]);
                    const companyLocation = extractTextFromHTML(row[6]);
                    const mobile = row[7] ? row[7].replace(/\<.*?\>/g, '').trim() : '';
                    const email = row[8] ? row[8].replace(/\<.*?\>/g, '').trim() : '';
                    const status = extractTextFromHTML(row[9]) || '';
                    
                    // Get additional data from checkbox data attributes
                    const rowElement = table.row(index).node();
                    const checkbox = $(rowElement).find('.client-checkbox');
                    const designation = checkbox.data('desination') || '';
                    const gstNumber = checkbox.data('gst-number') || '';
                    const alternateNumber = checkbox.data('alternate-number') || '';
                    const jdAttachment = checkbox.data('attachment-jd') || '';
                    const otherDocsAttachment = checkbox.data('attachment-other-docs') || '';
                    
                    // Clean data for CSV
                    const cleanRow = [
                        index + 1,
                        userType,
                        subDivision,
                        contactPerson,
                        designation,
                        industryType,
                        companyLocation,
                        mobile,
                        alternateNumber,
                        email,
                        gstNumber,
                        status,
                        jdAttachment,
                        otherDocsAttachment
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
                const totalClients = data.count();
                
                let text = `🏢 *CLIENTS LIST*\n\n`;
                text += `📅 Date: ${currentDate}\n`;
                text += `📊 Total Clients: ${totalClients}\n\n`;
                text += `================================\n\n`;
                
                // Add limited rows (max 10 for WhatsApp)
                let rowCount = 0;
                const maxRows = 10;
                
                data.each(function (row, index) {
                    if (rowCount < maxRows) {
                        const userType = extractTextFromHTML(row[2]);
                        const subDivision = extractTextFromHTML(row[3]);
                        const contactPerson = extractTextFromHTML(row[4]);
                        const industryType = extractTextFromHTML(row[5]);
                        const companyLocation = extractTextFromHTML(row[6]);
                        const mobile = row[7] ? row[7].replace(/\<.*?\>/g, '').trim() : '';
                        const email = row[8] ? row[8].replace(/\<.*?\>/g, '').trim() : '';
                        const status = extractTextFromHTML(row[9]) || '';
                        
                        text += `*${index + 1}. ${contactPerson}*\n`;
                        text += `User Type: ${userType}\n`;
                        if (subDivision && subDivision !== 'N/A') {
                            text += `Sub Division: ${subDivision}\n`;
                        }
                        text += `Industry: ${industryType}\n`;
                        text += `Location: ${companyLocation}\n`;
                        text += `Mobile: ${mobile}\n`;
                        text += `Email: ${email}\n`;
                        text += `Status: ${status}\n`;
                        text += `--------------------------------\n`;
                        rowCount++;
                    }
                });
                
                if (totalClients > maxRows) {
                    text += `\n...and ${totalClients - maxRows} more clients.\n`;
                }
                
                text += `\n_Generated from Clients Management System_`;
                
                return text;
            }

            // WhatsApp Share with Data
            $('#shareWhatsAppBtn').click(function() {
                const message = generateTextContent();
                const encodedMessage = encodeURIComponent(message);
                const whatsappUrl = `https://wa.me/?text=${encodedMessage}`;
                
                window.open(whatsappUrl, '_blank');
                $('#shareListModal').modal('hide');
            });

            // Email Share with Data
            $('#shareEmailBtn').click(function() {
                const csvContent = generateCSVContent();
                const currentDate = new Date().toLocaleDateString('en-GB');
                const totalClients = table.rows({ filter: 'applied' }).count();
                
                // Create CSV blob
                const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                const dataUrl = URL.createObjectURL(blob);
                
                // Create download link
                const link = document.createElement('a');
                const fileName = `RMS_Clients_List_${currentDate.replace(/\//g, '-')}.csv`;
                link.href = dataUrl;
                link.download = fileName;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                
                // Prepare email
                setTimeout(() => {
                    const subject = `Clients List - ${currentDate}`;
                    let body = `Dear Team,\n\n`;
                    body += `Please find attached the clients list report.\n\n`;
                    body += `Report Summary:\n`;
                    body += `- Total Clients: ${totalClients}\n`;
                    body += `- Report Date: ${currentDate}\n`;
                    body += `- Generated By: {{ auth()->user()->name }}\n\n`;
                    
                    // Add first 5 rows as text preview
                    const data = table.rows({ filter: 'applied' }).data();
                    let preview = '';
                    let previewCount = 0;
                    data.each(function (row) {
                        if (previewCount < 5) {
                            const contactPerson = extractTextFromHTML(row[4]);
                            const companyLocation = extractTextFromHTML(row[6]);
                            preview += `• ${contactPerson} - ${companyLocation}\n`;
                            previewCount++;
                        }
                    });
                    
                    if (previewCount > 0) {
                        body += `Recent Clients (${previewCount} of ${totalClients}):\n`;
                        body += preview + '\n';
                    }
                    
                    body += `Full details are available in the attached CSV file.\n\n`;
                    body += `Best regards,\n`;
                    body += `Clients Management Team`;
                    
                    const mailtoLink = `mailto:?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
                    window.location.href = mailtoLink;
                }, 500);
                
                $('#shareListModal').modal('hide');
            });

            // Download Excel
            $('#downloadExcelBtn').click(function() {
                $('.buttons-excel').click();
                $('#shareListModal').modal('hide');
            });

            // Download CSV
            $('#downloadCSVBtn').click(function() {
                $('.buttons-csv').click();
                $('#shareListModal').modal('hide');
            });

            // Download PDF
            $('#downloadPDFBtn').click(function() {
                $('.buttons-pdf').click();
                $('#shareListModal').modal('hide');
            });

            // Initialize
            updateSelectedCount();
        }
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