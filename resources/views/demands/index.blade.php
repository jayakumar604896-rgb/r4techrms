@extends('layouts.app')

@section('title', 'Demands')
@section('content')
<style>
.btn-group .btn {
        border-radius: 4px !important;
        margin-right: 2px;
    }
    .btn-group .btn.active {
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }
    .btn-group .badge {
        font-size: 10px;
        padding: 3px 6px;
        margin-left: 3px;
    }
    .table td {
        vertical-align: middle;
    }
    .flex-wrap {
        display: flex;
        flex-wrap: wrap;
    }
    #demand-listing thead th {
        background: #004085 !important;
        color: #ffffff !important;
        border-color: #003366 !important;
        font-weight: bold !important;
    }
	.btn:hover{
		color:#000 !important;
	}
    /* Sticky Header */
    #demand-listing thead th {
        position: sticky;
        top: 0;
        z-index: 5;
    }
    .table thead th,
    .jsgrid .jsgrid-table thead th {
        font-weight: 600 !important;
    }

    /* Status Badges */
    .badge-open {
        background-color: #28a745 !important;
        color: white !important;
    }

    .badge-in-progress {
        background-color: #ffc107 !important;
        color: #000 !important;
    }

    .badge-closed {
        background-color: #dc3545 !important;
        color: white !important;
    }
    .badge-closed  { background-color: #dc3545; color: white; }
    .badge-working { background-color: #28a745; color: white; }
    .badge-hold    { background-color: #ffc107; color: black; }
    .badge-rework  { background-color: #fd7e14; color: white; }
    .badge-reject  { background-color: #6c757d; color: white; }
    .badge-other   { background-color: #17a2b8; color: white; }
    .btn-group > .btn.active
    {
    color:black;
    }
    .table > :not(caption)  {
    padding: 0 !important;
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
	.badge-light {
	    border: 1px solid #f8f9fa;
	    background: #0975e0;
	    color: white;
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
        </div>
    </div>

    {{-- Page Header --}}
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="page-title fw-bold text-primary mb-1"> Demand Registers </h3>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active"> Demand Registers </li>
            </ol>
        </div>

        <a href="{{ route('demands.create') }}" class="btn btn-primary btn-sm shadow-sm">
            <i class="mdi mdi-plus"></i> Add Demand Registers
        </a>
    </div>

    {{-- Card --}}
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">

            <h4 class="card-title mb-3 fw-semibold">Demand Registers - List</h4>

            {{-- Optional: Status Filter Tabs --}}
            <div class="row mb-3">
        <div class="col-md-12">
            <!-- Status Filter Buttons -->
            <div class="btn-group btn-group-sm flex-wrap" role="group" style="gap: 5px;">
                <a href="{{ route('demands.index', request()->except('status')) }}" 
                   class="btn btn-outline-primary {{ !request('status') ? 'active' : '' }}">
                    All Demands 
                    <span class="badge badge-light">{{ $totalCount ?? 0 }}</span>
                </a>
                
                <a href="{{ route('demands.index', array_merge(request()->except('status'), ['status' => 'Working'])) }}" 
                   class="btn btn-outline-success {{ request('status') == 'Working' ? 'active' : '' }}">
                    Working Demands 
                    <span class="badge badge-light">{{ $statusCounts['Working'] ?? 0 }}</span>
                </a>
                
                <a href="{{ route('demands.index', array_merge(request()->except('status'), ['status' => 'Hold'])) }}" 
                   class="btn btn-outline-warning {{ request('status') == 'Hold' ? 'active' : '' }}">
                    Demands On Hold 
                    <span class="badge badge-light">{{ $statusCounts['Hold'] ?? 0 }}</span>
                </a>
                
                <a href="{{ route('demands.index', array_merge(request()->except('status'), ['status' => 'Rework'])) }}" 
                   class="btn btn-outline-info {{ request('status') == 'Rework' ? 'active' : '' }}">
                    Demands - Rework 
                    <span class="badge badge-light">{{ $statusCounts['Rework'] ?? 0 }}</span>
                </a>
                
                <a href="{{ route('demands.index', array_merge(request()->except('status'), ['status' => 'Reject'])) }}" 
                   class="btn btn-outline-secondary {{ request('status') == 'Reject' ? 'active' : '' }}">
                    Rejected Demands 
                    <span class="badge badge-light">{{ $statusCounts['Reject'] ?? 0 }}</span>
                </a>
                
                <a href="{{ route('demands.index', array_merge(request()->except('status'), ['status' => 'Other'])) }}" 
                   class="btn btn-outline-primary {{ request('status') == 'Other' ? 'active' : '' }}">
                    Other Demands 
                    <span class="badge badge-light">{{ $statusCounts['Other'] ?? 0 }}</span>
                </a>
                
                <a href="{{ route('demands.index', array_merge(request()->except('status'), ['status' => 'Closed'])) }}" 
                   class="btn btn-outline-danger {{ request('status') == 'Closed' ? 'active' : '' }}">
                    Closed Demands 
                    <span class="badge badge-light">{{ $statusCounts['Closed'] ?? 0 }}</span>
                </a>
            </div>
        </div>
    </div>

            <div class="table-responsive">
                <table id="demand-listing" class="table table-hover align-middle">

                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Demand Ref</th>
                            <th>Recruiter</th>
                            <th>Designation</th>
                            <th>Positions</th>
                            <th>Job Location</th>
                            <th>Industry</th>
                            <th>Status</th>
                            <th>Created Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($demands as $key => $demand)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td class="fw-medium">
                                    {{ $demand->demand_ref_no ?? 'N/A' }}
                                    @if($demand->user_type == 'International')
                                        <span class="badge bg-info ms-1">Intl</span>
                                    @endif
                                </td>
                                <td>{{ $demand->recruiter_name }}</td>
                                <td>{{ $demand->designation_offered }}</td>
                                <td>
                                    <span class="badge bg-primary rounded-pill">
                                        {{ $demand->no_of_positions }}
                                    </span>
                                </td>
                                <td>{{ $demand->job_location }}</td>
                                <td>{{ $demand->industry }}</td>
                                <td>
                                    <span class="badge
                                        @switch($demand->status)
                                            @case('Closed') badge-closed @break
                                            @case('Working') badge-working @break
                                            @case('Hold') badge-hold @break
                                            @case('Rework') badge-rework @break
                                            @case('Reject') badge-reject @break
                                            @default badge-other
                                        @endswitch
                                    ">
                                        {{ $demand->status }}
                                    </span>
                                </td>
                                <td>{{ $demand->created_at->format('d M, Y') }}</td>

                                {{-- Actions --}}
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('demands.show', $demand->id) }}" 
                                           class="btn btn-success text-white shadow-sm"
                                           title="View Details">
                                            <i class="mdi mdi-eye"></i>
                                        </a>
                                        
                                        <a href="{{ route('demands.edit', $demand->id) }}" 
                                           class="btn btn-primary text-white shadow-sm"
                                           title="Edit">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>
                                        
                                        {{-- Quick Status Change Dropdown --}}
                                       {{-- <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-info dropdown-toggle shadow-sm" 
                                                    data-bs-toggle="dropdown" aria-expanded="false"
                                                    title="Change Status">
                                                <i class="mdi mdi-cog"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <form action="{{ route('demands.status', $demand->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" name="status" value="Open" 
                                                                class="dropdown-item {{ $demand->status == 'Open' ? 'active' : '' }}">
                                                            <i class="mdi mdi-circle text-success"></i> Mark as Open
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form action="{{ route('demands.status', $demand->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" name="status" value="In Progress" 
                                                                class="dropdown-item {{ $demand->status == 'In Progress' ? 'active' : '' }}">
                                                            <i class="mdi mdi-circle text-warning"></i> Mark as In Progress
                                                        </button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form action="{{ route('demands.status', $demand->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" name="status" value="Closed" 
                                                                class="dropdown-item {{ $demand->status == 'Closed' ? 'active' : '' }}">
                                                            <i class="mdi mdi-circle text-danger"></i> Mark as Closed
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>

                                    </div>--}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>

</div>

<!-- Share List Modal -->
<div class="modal fade" id="shareListModal" tabindex="-1" aria-labelledby="shareListModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shareListModalLabel">Share Demand Registers</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">Share the demand registers via:</p>
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
                        <!-- <button type="button" class="btn btn-outline-danger" id="downloadPDFBtn">
                            <i class="mdi mdi-file-pdf"></i> PDF
                        </button> -->
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

        if ($.fn.dataTable.isDataTable('#demand-listing')) {
            $('#demand-listing').DataTable().destroy();
        }

        // Initialize DataTable with Export/Print Buttons
        const table = $('#demand-listing').DataTable({
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
                        columns: [1, 2, 3, 4, 5, 6, 7, 8]
                    },
                    title: 'RMS_Demand_Registers_List_' + new Date().toLocaleDateString('en-GB')
                },
                // {
                //     extend: 'pdf',
                //     className: 'btn btn-sm',
                //     text: '<i class="mdi mdi-file-pdf"></i> PDF',
                //     exportOptions: {
                //         columns: [1, 2, 3, 4, 5, 6, 7, 8]
                //     },
                //     title: 'RMS_Demand_Registers_List_' + new Date().toLocaleDateString('en-GB'),
                //     customize: function(doc) {
                //         doc.content[1].table.widths = 
                //             Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                //         doc.styles.tableHeader = {
                //             fillColor: '#004085',
                //             color: '#ffffff',
                //             bold: true
                //         };
                //     }
                // },
                {
                    extend: 'print',
                    className: 'btn btn-sm',
                    text: '<i class="mdi mdi-printer"></i> Print',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8]
                    },
                    title: '<h3 class="text-center mb-4">Demand Registers List</h3>',
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
                searchPlaceholder: "Search demand registers...",
                lengthMenu: "Show _MENU_ entries",
                paginate: {
                    next: '<i class="mdi mdi-chevron-right"></i>',
                    previous: '<i class="mdi mdi-chevron-left"></i>'
                }
            },
            responsive: true,
            autoWidth: false
        });

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

        // SHARE FUNCTIONALITY
        // Generate CSV content
        function generateCSVContent() {
            const data = table.rows({ filter: 'applied' }).data();
            
            // Create CSV header
            let csvContent = "S.No,Demand Ref,Recruiter,Designation,Positions,Job Location,Industry,Status,Created Date\n";
            
            // Add rows to CSV
            data.each(function (row, index) {
                // Extract text from HTML for each column
                const demandRef = extractTextFromHTML(row[1]);
                const positions = extractTextFromHTML(row[4]);
                const status = extractTextFromHTML(row[7]);
                
                // Clean data for CSV
                const cleanRow = [
                    index + 1,
                    demandRef,      // Demand Ref (with HTML stripped)
                    row[2],         // Recruiter
                    row[3],         // Designation
                    positions,      // Positions (with HTML stripped)
                    row[5],         // Job Location
                    row[6],         // Industry
                    status,         // Status (with HTML stripped)
                    row[8]          // Created Date
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
            const totalDemands = data.count();
            
            // Get status counts
            let openCount = 0, progressCount = 0, closedCount = 0;
            data.each(function (row) {
                const statusText = extractTextFromHTML(row[7]);
                if (statusText.includes('Open')) openCount++;
                else if (statusText.includes('Progress')) progressCount++;
                else if (statusText.includes('Closed')) closedCount++;
            });
            
            let text = `📋 *DEMAND REGISTERS REPORT*\n\n`;
            text += `📅 Date: ${currentDate}\n`;
            text += `📊 Total Demands: ${totalDemands}\n`;
            text += `✅ Open: ${openCount} | ⚡ In Progress: ${progressCount} | ❌ Closed: ${closedCount}\n\n`;
            text += `================================\n\n`;
            
            // Add limited rows (max 15 for WhatsApp)
            let rowCount = 0;
            const maxRows = 15;
            
            data.each(function (row, index) {
                if (rowCount < maxRows) {
                    const demandRef = extractTextFromHTML(row[1]);
                    const positions = extractTextFromHTML(row[4]);
                    const status = extractTextFromHTML(row[7]);
                    
                    text += `*${index + 1}. ${demandRef}*\n`;
                    text += `Recruiter: ${row[2]}\n`;
                    text += `Designation: ${row[3]}\n`;
                    text += `Positions: ${positions}\n`;
                    text += `Location: ${row[5]}\n`;
                    text += `Status: ${status}\n`;
                    text += `--------------------------------\n`;
                    rowCount++;
                }
            });
            
            if (totalDemands > maxRows) {
                text += `\n...and ${totalDemands - maxRows} more demand registers.\n`;
            }
            
            text += `\n_Generated from Recruitment Demand System_`;
            
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
            const totalDemands = table.rows({ filter: 'applied' }).count();
            
            // Create CSV blob
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const dataUrl = URL.createObjectURL(blob);
            
            // Create download link
            const link = document.createElement('a');
            const fileName = `Demand_Registers_${currentDate.replace(/\//g, '-')}.csv`;
            link.href = dataUrl;
            link.download = fileName;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            // Prepare email
            setTimeout(() => {
                const subject = `Demand Registers Report - ${currentDate}`;
                let body = `Dear Team,\n\n`;
                body += `Please find attached the demand registers report.\n\n`;
                body += `Report Summary:\n`;
                body += `- Total Demands: ${totalDemands}\n`;
                body += `- Report Date: ${currentDate}\n`;
                body += `- Generated By: {{ auth()->user()->name }}\n\n`;
                
                // Add first 5 rows as text preview
                const data = table.rows({ filter: 'applied' }).data();
                let preview = '';
                let previewCount = 0;
                data.each(function (row) {
                    if (previewCount < 5) {
                        const demandRef = extractTextFromHTML(row[1]);
                        const status = extractTextFromHTML(row[7]);
                        preview += `• ${demandRef} - ${row[3]} (${status})\n`;
                        previewCount++;
                    }
                });
                
                if (previewCount > 0) {
                    body += `Recent Demand Registers (${previewCount} of ${totalDemands}):\n`;
                    body += preview + '\n';
                }
                
                body += `Full details are available in the attached CSV file.\n\n`;
                body += `Best regards,\n`;
                body += `Demand Management Team`;
                
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
    });
</script>
@endsection