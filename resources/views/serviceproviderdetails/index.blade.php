@extends('layouts.app')

@section('title', 'Service Providers')

@section('content')
<style>
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
    #service-provider-details-table thead th {
        background: #004085 !important;
        color: #ffffff !important;
        border-color: #003366 !important;
    }
    #service-provider-details-table thead th {
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
            <h3 class="page-title fw-bold mb-2" style="color: var(--primary-blue);">Service Providers</h3>
            <p class="text-muted mb-0">
                <i class="mdi mdi-account-multiple me-1"></i>
                Manage and organize agent profiles
            </p>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <!--<div class="position-relative">
                <button type="button" id="chooseRecruiterBtn" class="btn btn-warning d-flex align-items-center" disabled>
                    <i class="mdi mdi-account-arrow-right me-2"></i> 
                    Assign to Recruiter
                    <span id="selectionCount" class="selection-count ms-2">0</span>
                </button>
            </div>-->
            <a href="{{ route('service-provider-details.create') }}" class="btn btn-primary d-flex align-items-center">
                <i class="mdi mdi-plus-circle-outline me-2"></i> Add New Service Provider
            </a>
        </div>
    </div>
	<div class="row mb-3">
	    <div class="col-md-12">
	        <div class="d-flex flex-wrap gap-2 align-items-center p-2 bg-light rounded-3">
	            <span class="fw-semibold me-2 text-muted small">
	                <i class="mdi mdi-filter-variant me-1"></i>Filter by Type:
	            </span>
	            <a href="{{ route('service-provider-details.index', ['type' => 'all']) }}" 
	               class="btn btn-sm btn-outline-secondary {{ (!request()->has('type') || request('type') == 'all') ? 'active' : '' }}">
	                All Types
	            </a>
	            <a href="{{ route('service-provider-details.index', ['type' => 'international']) }}" 
	               class="btn btn-sm btn-outline-info {{ request('type') == 'international' ? 'active' : '' }}">
	                🌍 International ({{ $counts['International'] ?? 0 }})
	            </a>
	            <a href="{{ route('service-provider-details.index', ['type' => 'domestic']) }}" 
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
                            <h6 class="text-muted mb-1">Total Service Providers</h6>
                            <h3 class="mb-0">{{ $serviceproviderdetails->count() }}</h3>
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
                            <h3 class="mb-0">{{ $serviceproviderdetails->where('status', 1)->count() }}</h3>
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
                            <h3 class="mb-0">{{ $serviceproviderdetails->where('status', 2)->count() }}</h3>
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
    <div class="card service-provider-details-card">
        <!-- <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="mdi mdi-account-group me-2"></i>ServiceProviders List
            </h5>
        </div> -->

        <div class="card-body p-0">
            @if($serviceproviderdetails->count() > 0)
            <div class="table-responsive">
                <table id="service-provider-details-table" class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th width="50">
                                <input type="checkbox" id="select-all">
                            </th>
                            <th width="70">#</th>
                            <th>User Type</th>
                            <th>Service Provider Name</th>
                            <th>Service Provider Name</th>
                            <th>Business Type</th>
                            <th>Designation</th>
                            <th>Mobile</th>
                            <th>Email</th>
                            <th>GST Number</th>
                            <th>Status</th>
                            <th width="160" class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($serviceproviderdetails as $key => $agent)
                        <tr data-agent-id="{{ $agent->id }}" 
                            data-status="{{ $agent->status }}">
                            <td>
                                <input type="checkbox" name="agent_ids[]" value="{{ $agent->id }}" 
                                       class="agent-checkbox"
                                       data-agent-id="{{ $agent->id }}"
                                       data-agent-company-name="{{ $agent->service_company_name }}"
                                       data-agent-name="{{ $agent->agent_name }}"
                                       data-type-business="{{ $agent->type_business }}"
                                       data-designation="{{ $agent->desination }}"
                                       data-mobile="{{ $agent->mobile_number }}"
                                       data-email="{{ $agent->email_id }}"
                                       data-gst="{{ $agent->gst_number }}"
                                       data-status="{{ $agent->status }}"
                                       data-address="{{ $agent->address }}"
                                       data-company-location="{{ $agent->company_location }}"
                                       data-alternate-number="{{ $agent->alternate_number }}">
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">{{ $key + 1 }}</span>
                            </td>
 							<td>
                                <strong>{{ $agent->user_type ?? 'N/A' }}</strong>
                            </td>
                            <td>
                                <strong>{{ $agent->service_company_name ?? 'N/A' }}</strong>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-placeholder bg-primary bg-opacity-10 text-primary rounded-circle 
                                                d-flex align-items-center justify-content-center me-3" 
                                         style="width: 36px; height: 36px; font-weight: bold;">
                                        {{ strtoupper(substr($agent->agent_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <strong>{{ $agent->agent_name }}</strong>
                                        @if($agent->alternate_number)
                                        <br>
                                        <small class="text-muted">Alt: {{ $agent->alternate_number }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info bg-opacity-10 text-info">
                                    {{ $agent->type_business ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <span class="fw-medium">{{ $agent->desination ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <a href="tel:{{ $agent->mobile_number }}" class="text-decoration-none">
                                    <i class="mdi mdi-phone me-1"></i> {{ $agent->mobile_number }}
                                </a>
                            </td>
                            <td>
                                @if($agent->email_id)
                                <a href="mailto:{{ $agent->email_id }}" class="text-decoration-none">
                                    <i class="mdi mdi-email me-1"></i> {{ $agent->email_id }}
                                </a>
                                @else
                                <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($agent->gst_number)
                                <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                    {{ $agent->gst_number }}
                                </span>
                                @else
                                <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($agent->status == 1)
                                <span class="badge bg-success bg-opacity-10 text-success">
                                    Active
                                </span>
                                @elseif($agent->status == 2)
                                <span class="badge bg-danger bg-opacity-10 text-danger">
                                    Inactive
                                </span>
                                @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                    Unknown
                                </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('service-provider-details.show', $agent->id) }}" 
                                   class="btn btn-sm btn-success text-white me-1 shadow-sm">
                                    <i class="mdi mdi-eye"></i> View
                                </a>
                                <a href="{{ route('service-provider-details.edit', $agent->id) }}" 
                                   class="btn btn-sm btn-primary text-white shadow-sm">
                                    <i class="mdi mdi-pencil"></i> Edit
                                </a>
                                <a href="{{ url('service-provider/prints/'.$agent->id) }}" target="_blank" 
                                   class="btn btn-sm btn-secondary text-white shadow-sm"
                                   title="Print Client">
                                    <i class="fa fa-print"></i>Print
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state">
                <i class="mdi mdi-account-off-outline"></i>
                <h5 class="mt-3">No Service Providers Found</h5>
                <p class="text-muted">Add your first agent to get started</p>
                <a href="{{ route('service-provider-details.create') }}" class="btn btn-primary mt-2">
                    <i class="mdi mdi-plus"></i> Add Service Provider
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
                <h5 class="modal-title" id="shareListModalLabel">Share Service Providers List</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">Share the service-provider-details list via:</p>
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
        // Only initialize DataTable if there are service-provider-details
        if ($('#service-provider-details-table').length > 0) {
            if ($.fn.dataTable.isDataTable('#service-provider-details-table')) {
                $('#service-provider-details-table').DataTable().destroy();
            }

            // Initialize DataTable with Export/Print Buttons
            const table = $('#service-provider-details-table').DataTable({
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
                        title: 'RMS_ServiceProviders_List_' + new Date().toLocaleDateString('en-GB')
                    },
                    {
                        extend: 'pdf',
                        className: 'btn btn-sm',
                        text: '<i class="mdi mdi-file-pdf"></i> PDF',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7, 8, 9] // Export columns except checkbox and actions
                        },
                        title: 'RMS_ServiceProviders_List_' + new Date().toLocaleDateString('en-GB'),
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
                        title: '<h3 class="text-center mb-4">Service Providers List</h3>',
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
                    searchPlaceholder: "Search service-provider-details...",
                    lengthMenu: "Show _MENU_ entries",
                    zeroRecords: "No matching service-provider-details found",
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

            let selectedServiceProviders = [];
            let agentDetails = {};

            // Select All functionality
            $('#select-all').change(function() {
                $('.agent-checkbox').prop('checked', this.checked);
                updateSelectedServiceProviders();
                updateChooseRecruiterButton();
                updateSelectedCount();
            });

            // Individual checkbox change
            $('.agent-checkbox').change(function() {
                updateSelectedServiceProviders();
                updateChooseRecruiterButton();
                updateSelectedCount();
                
                // Update select all checkbox
                if (!this.checked) {
                    $('#select-all').prop('checked', false);
                } else {
                    const allChecked = $('.agent-checkbox:checked').length === $('.agent-checkbox').length;
                    $('#select-all').prop('checked', allChecked);
                }
                
                // Add/remove selected-row class
                $(this).closest('tr').toggleClass('selected-row', this.checked);
            });

            // Update selected service-provider-details
            function updateSelectedServiceProviders() {
                selectedServiceProviders = [];
                agentDetails = {};
                
                $('.agent-checkbox:checked').each(function() {
                    const agentId = $(this).val();
                    selectedServiceProviders.push(agentId);
                    
                    // Store agent details
                    agentDetails[agentId] = {
                        service_company_name: $(this).data('agent-company-name'),
                        agent_name: $(this).data('agent-name'),
                        type_business: $(this).data('type-business'),
                        desination: $(this).data('designation'),
                        mobile_number: $(this).data('mobile'),
                        email_id: $(this).data('email'),
                        gst_number: $(this).data('gst'),
                        status: $(this).data('status'),
                        address: $(this).data('address'),
                        company_location: $(this).data('company-location'),
                        alternate_number: $(this).data('alternate-number')
                    };
                });
            }

            // Update UI elements
            function updateChooseRecruiterButton() {
                const hasSelection = selectedServiceProviders.length > 0;
                $('#chooseRecruiterBtn').prop('disabled', !hasSelection);
                $('#selectionCount').text(selectedServiceProviders.length);
            }

            function updateSelectedCount() {
                $('#selectedCount').text(selectedServiceProviders.length);
            }

            // Choose Recruiter button click
            $('#chooseRecruiterBtn').click(function() {
                if (selectedServiceProviders.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Selection',
                        text: 'Please select at least one agent.',
                        confirmButtonColor: '#f39c12'
                    });
                    return;
                }

                // Store in session storage
                sessionStorage.setItem('selectedService Providers', JSON.stringify(selectedServiceProviders));
                sessionStorage.setItem('agentDetails', JSON.stringify(agentDetails));
                
                // Show loading
                $(this).html('<i class="mdi mdi-loading mdi-spin me-2"></i> Processing...');
                
                setTimeout(() => {
                    window.location.href = "{{ route('recruiters') }}?from_service-provider-details=true&selected=" + selectedServiceProviders.join(',');
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

            // Generate CSV content from DataTable
            function generateCSVContent() {
                const data = table.rows({ filter: 'applied' }).data();
                
                // Create CSV header - matching your database columns
                let csvContent = "S.No,Company Name,Service Provider Name,Type of Business,Designation,Mobile Number,Email,GST Number,Status,Address,Company Location,Alternate Number\n";
                
                // Add rows to CSV
                data.each(function (row, index) {
                    // Extract data from table cells
                    const companyName = extractTextFromHTML(row[2]);
                    const agentName = extractTextFromHTML(row[3]);
                    const typeBusiness = extractTextFromHTML(row[4]);
                    const designation = extractTextFromHTML(row[5]) || '';
                    const mobile = row[6] ? row[6].replace(/\<.*?\>/g, '').trim() : '';
                    const email = row[7] ? row[7].replace(/\<.*?\>/g, '').trim() : '';
                    const gstNumber = extractTextFromHTML(row[8]) || '';
                    const status = extractTextFromHTML(row[9]) || '';
                    
                    // Get additional data from checkbox data attributes
                    const rowElement = table.row(index).node();
                    const checkbox = $(rowElement).find('.agent-checkbox');
                    const address = checkbox.data('address') || '';
                    const companyLocation = checkbox.data('company-location') || '';
                    const alternateNumber = checkbox.data('alternate-number') || '';
                    
                    // Clean data for CSV
                    const cleanRow = [
                        index + 1,
                        companyName,
                        agentName,
                        typeBusiness,
                        designation,
                        mobile,
                        email,
                        gstNumber,
                        status,
                        address,
                        companyLocation,
                        alternateNumber
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
                const totalServiceProviders = data.count();
                
                let text = `🏢 *AGENTS LIST*\n\n`;
                text += `📅 Date: ${currentDate}\n`;
                text += `📊 Total Service Providers: ${totalServiceProviders}\n\n`;
                text += `================================\n\n`;
                
                // Add limited rows (max 10 for WhatsApp)
                let rowCount = 0;
                const maxRows = 10;
                
                data.each(function (row, index) {
                    if (rowCount < maxRows) {
                        const companyName = extractTextFromHTML(row[2]);
                        const agentName = extractTextFromHTML(row[3]);
                        const typeBusiness = extractTextFromHTML(row[4]);
                        const designation = extractTextFromHTML(row[5]) || '';
                        const mobile = row[6] ? row[6].replace(/\<.*?\>/g, '').trim() : '';
                        const email = row[7] ? row[7].replace(/\<.*?\>/g, '').trim() : '';
                        const status = extractTextFromHTML(row[9]) || '';
                        
                        text += `*${index + 1}. ${companyName}*\n`;
                        text += `Service Provider: ${agentName}\n`;
                        text += `Business: ${typeBusiness}\n`;
                        text += `Designation: ${designation}\n`;
                        text += `Mobile: ${mobile}\n`;
                        text += `Email: ${email}\n`;
                        text += `Status: ${status}\n`;
                        text += `--------------------------------\n`;
                        rowCount++;
                    }
                });
                
                if (totalServiceProviders > maxRows) {
                    text += `\n...and ${totalServiceProviders - maxRows} more service-provider-details.\n`;
                }
                
                text += `\n_Generated from ServiceProviders Management System_`;
                
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
                const totalServiceProviders = table.rows({ filter: 'applied' }).count();
                
                // Create CSV blob
                const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                const dataUrl = URL.createObjectURL(blob);
                
                // Create download link
                const link = document.createElement('a');
                const fileName = `RMS_ServiceProviders_List_${currentDate.replace(/\//g, '-')}.csv`;
                link.href = dataUrl;
                link.download = fileName;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                
                // Prepare email
                setTimeout(() => {
                    const subject = `ServiceProviders List - ${currentDate}`;
                    let body = `Dear Team,\n\n`;
                    body += `Please find attached the service-provider-details list report.\n\n`;
                    body += `Report Summary:\n`;
                    body += `- Total ServiceProviders: ${totalServiceProviders}\n`;
                    body += `- Report Date: ${currentDate}\n`;
                    body += `- Generated By: {{ auth()->user()->name }}\n\n`;
                    
                    // Add first 5 rows as text preview
                    const data = table.rows({ filter: 'applied' }).data();
                    let preview = '';
                    let previewCount = 0;
                    data.each(function (row) {
                        if (previewCount < 5) {
                            const companyName = extractTextFromHTML(row[2]);
                            const agentName = extractTextFromHTML(row[3]);
                            preview += `• ${companyName} - ${agentName}\n`;
                            previewCount++;
                        }
                    });
                    
                    if (previewCount > 0) {
                        body += `Recent ServiceProviders (${previewCount} of ${totalServiceProviders}):\n`;
                        body += preview + '\n';
                    }
                    
                    body += `Full details are available in the attached CSV file.\n\n`;
                    body += `Best regards,\n`;
                    body += `ServiceProviders Management Team`;
                    
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