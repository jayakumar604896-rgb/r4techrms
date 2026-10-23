@extends('layouts.app')

@section('title', 'Domestic Invoice Reports')
@section('content')
<style>
    #invoice-listing thead th {
        background: #004085 !important;
        color: #ffffff !important;
        border-color: #003366 !important;
        font-weight: bold !important;
    }

    /* Sticky Header */
    #invoice-listing thead th {
        position: sticky;
        top: 0;
        z-index: 5;
    }
    .table thead th,
    .jsgrid .jsgrid-table thead th {
        font-weight: 600 !important;
    }

    /* Amount styling */
    .amount {
        font-weight: 600;
        color: #2e7d32;
    }
    .gst-badge {
        font-size: 11px;
        padding: 2px 6px;
    }
    .table > :not(caption)  {
        padding: 0.75rem !important;
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
    /* Action buttons */
    .btn-action {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0;
    }
    /* Highlight overdue invoices */
    .overdue {
        background-color: #fff3f3 !important;
        border-left: 4px solid #dc3545;
    }
    .overdue td {
        font-weight: 600;
    }
    /* Status colors */
    .status-paid {
        background-color: #28a745 !important;
        color: white !important;
    }
    .status-pending {
        background-color: #ffc107 !important;
        color: #000 !important;
    }
    .status-overdue {
        background-color: #dc3545 !important;
        color: white !important;
    }
</style>

<div class="content-wrapper">

    {{-- Success/Error Messages --}}
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

    {{-- Page Header --}}
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="page-title fw-bold text-primary mb-1"> Tax Invoice Reports </h3>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active"> Tax Invoice Reports </li>
            </ol>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('tax-invoice-reports.create') }}" class="btn btn-primary btn-sm shadow-sm">
                <i class="mdi mdi-plus"></i> Create Invoice
            </a>
            <!--<a href="{{ route('tax-invoice-reports.statistics') }}" class="btn btn-info btn-sm shadow-sm">
                <i class="mdi mdi-chart-bar"></i> Statistics
            </a>-->
            <button type="button" class="btn btn-success btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#filterModal">
                <i class="mdi mdi-filter"></i> Filter
            </button>
        </div>
    </div>

    {{-- Summary Cards --}}
    <!--<div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Invoices</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalInvoices ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="mdi mdi-file-document text-primary fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Revenue</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₹{{ number_format($totalRevenue ?? 0, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="mdi mdi-cash text-success fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Avg. Invoice Value</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₹{{ number_format($avgInvoice ?? 0, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="mdi mdi-chart-line text-info fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow-sm h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total GST</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">₹{{ number_format($totalGST ?? 0, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="mdi mdi-percent text-warning fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>-->

    {{-- Filter Modal --}}
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="GET" action="{{ route('tax-invoice-reports.index') }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="filterModalLabel">Filter Invoices</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Invoice Date From</label>
                                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Invoice Date To</label>
                                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Client ID</label>
                                <input type="number" name="client_id" class="form-control" value="{{ request('client_id') }}" placeholder="Enter Client ID">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Invoice Number</label>
                                <input type="text" name="invoice_no" class="form-control" value="{{ request('invoice_no') }}" placeholder="Search by invoice number">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">PAN Number</label>
                                <input type="text" name="pan_no" class="form-control" value="{{ request('pan_no') }}" placeholder="Search by PAN">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Minimum Amount</label>
                                <input type="number" name="min_amount" class="form-control" value="{{ request('min_amount') }}" placeholder="Minimum net amount">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('tax-invoice-reports.index') }}" class="btn btn-secondary">Clear Filters</a>
                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Card --}}
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">
            <h4 class="card-title mb-3 fw-semibold">Tax Invoice Reports - List</h4>

            {{-- Quick Search --}}
            <div class="row mb-3">
                <div class="col-md-12">
                    <form method="GET" action="{{ route('tax-invoice-reports.index') }}" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Search by Invoice No, PAN, LIC, Particular..." 
                                   value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-magnify"></i> Search
                            </button>
                            @if(request()->hasAny(['search', 'start_date', 'end_date', 'client_id', 'invoice_no', 'pan_no', 'min_amount']))
                                <a href="{{ route('tax-invoice-reports.index') }}" class="btn btn-outline-secondary">
                                    <i class="mdi mdi-close"></i> Clear
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table id="invoice-listing" class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Invoice No</th>
                            <th>Date</th>
                            <th>Client ID</th>
                            <th>PAN No</th>
                            <th>LIC No</th>
                            <th>Particular</th>
                            <th>Quantity</th>
                            <th>Unit Rate</th>
                            <th>Total Amount</th>
                            <th>CGST</th>
                            <th>SGST</th>
                            <th>Net Amount</th>
                            <th>Created By</th>
                            <th>Created Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($invoices as $key => $invoice)
                            @php
                                $isOverdue = \Carbon\Carbon::parse($invoice->invoice_date)->addDays(30)->lt(now()) && $invoice->status != 'paid';
                            @endphp
                            <tr class="{{ $isOverdue ? 'overdue' : '' }}">
                                <td>{{ $key + 1 + (($invoices->currentPage() - 1) * $invoices->perPage()) }}</td>
                                <td class="fw-bold">
                                    <a href="{{ route('tax-invoice-reports.show', $invoice->id) }}" class="text-primary">
                                        {{ $invoice->invoice_no }}
                                    </a>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M, Y') }}</td>
                                <td>
                                    <span class="badge bg-secondary rounded-pill">
                                        {{ $invoice->client_id }}
                                    </span>
                                </td>
                                <td>
                                    @if($invoice->pan_no)
                                        <span class="badge bg-dark">{{ $invoice->pan_no }}</span>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($invoice->lic_no)
                                        <span class="badge bg-info">{{ $invoice->lic_no }}</span>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-truncate d-block" style="max-width: 200px;" title="{{ $invoice->particular }}">
                                        {{ Str::limit($invoice->particular, 30) }}
                                    </small>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary rounded-pill">
                                        {{ $invoice->nos }}
                                    </span>
                                </td>
                                <td class="text-end">₹{{ number_format($invoice->unit_rate, 2) }}</td>
                                <td class="text-end amount">₹{{ number_format($invoice->total_amount, 2) }}</td>
                                <td class="text-center">
                                    @if($invoice->cgst)
                                        <span class="badge gst-badge bg-light text-dark">{{ $invoice->cgst }}%</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($invoice->sgst)
                                        <span class="badge gst-badge bg-light text-dark">{{ $invoice->sgst }}%</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-end fw-bold text-success">₹{{ number_format($invoice->net_amount, 2) }}</td>
                                <td>
                                    <small class="text-muted">{{ $users[$invoice->created_by] ?? '' }}</small>
                                </td>
                                <td>{{ \Carbon\Carbon::parse($invoice->created_at)->format('d M, Y') }}</td>

                                {{-- Actions --}}
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('tax-invoice-reports.show', $invoice->id) }}" 
                                           class="btn btn-success btn-action shadow-sm"
                                           title="View Details"  target="_blank">
                                            <i class="mdi mdi-eye"></i>
                                        </a>
                                        
                                        <a href="{{ route('tax-invoice-reports.edit', $invoice->id) }}" 
                                           class="btn btn-primary btn-action shadow-sm"
                                           title="Edit">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>

                                        <a href="{{ route('tax-invoice-reports.show', $invoice->id) }}" 
                                           class="btn btn-info btn-action shadow-sm"
                                           title="Print" target="_blank">
                                            <i class="mdi mdi-printer"></i>
                                        </a>

                                        <form action="{{ route('tax-invoice-reports.destroy', $invoice->id) }}" 
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this invoice?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-action shadow-sm"
                                                    title="Delete">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($invoices->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Showing {{ $invoices->firstItem() }} to {{ $invoices->lastItem() }} of {{ $invoices->total() }} entries
                    </div>
                    <div>
                        {{ $invoices->links() }}
                    </div>
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
                <h5 class="modal-title" id="shareListModalLabel">Share domestic Invoice Reports</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">Share the invoice reports via:</p>
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
                    <div class="d-flex gap-2 flex-wrap">
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

        if ($.fn.dataTable.isDataTable('#invoice-listing')) {
            $('#invoice-listing').DataTable().destroy();
        }

        // Initialize DataTable with Export/Print Buttons
        const table = $('#invoice-listing').DataTable({
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>" +
                "<'row'<'col-sm-12'B>>",
            buttons: [
                {
                    extend: 'csv',
                    className: 'btn btn-sm',
                    text: '<i class="mdi mdi-file-delimited"></i> CSV',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14]
                    },
                    title: 'Invoice_Reports_' + new Date().toLocaleDateString('en-GB')
                },
                {
                    extend: 'excel',
                    className: 'btn btn-sm',
                    text: '<i class="mdi mdi-file-excel"></i> Excel',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14]
                    },
                    title: 'Invoice_Reports_' + new Date().toLocaleDateString('en-GB')
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-sm',
                    text: '<i class="mdi mdi-file-pdf"></i> PDF',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14]
                    },
                    title: 'Invoice_Reports_' + new Date().toLocaleDateString('en-GB'),
                    customize: function(doc) {
                        doc.content[1].table.widths = 
                            ['auto', 'auto', 'auto', 'auto', 'auto', 'auto', 'auto', 
                             'auto', 'auto', 'auto', 'auto', 'auto', 'auto', 'auto', 'auto'];
                        doc.styles.tableHeader = {
                            fillColor: '#004085',
                            color: '#ffffff',
                            bold: true
                        };
                    }
                },
                //{
                //    extend: 'print',
                //    className: 'btn btn-sm',
                //    text: '<i class="mdi mdi-printer"></i> Print',
                //    exportOptions: {
                //        columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14]
                //    },
                //    title: '<h3 class="text-center mb-4">domestic Invoice Reports List</h3>',
                //    customize: function(win) {
                //        $(win.document.body).find('table').addClass('table table-bordered').css('font-size', '11px');
                //        $(win.document.body).find('tr:nth-child(odd) td').css('background-color','#f9f9f9');
                //        $(win.document.body).find('h1').css('text-align','center');
                //        $(win.document.body).find('h1').css('color', '#004085');
                //    }
                //},
                {
                    text: '<i class="mdi mdi-share-variant"></i> Share',
                    className: 'btn btn-sm btn-share',
                    action: function (e, dt, node, config) {
                        $('#shareListModal').modal('show');
                    }
                }
            ],
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search invoices...",
                lengthMenu: "Show _MENU_ entries",
                paginate: {
                    next: '<i class="mdi mdi-chevron-right"></i>',
                    previous: '<i class="mdi mdi-chevron-left"></i>'
                }
            },
            responsive: true,
            autoWidth: false,
            columnDefs: [
                { orderable: false, targets: [15] }, // Actions column
                { className: "text-center", targets: [7, 10, 11, 15] }, // Center align
                { className: "text-end", targets: [8, 9, 12] }, // Right align amounts
                { width: "80px", targets: [0, 15] } // Fixed width for S.No and Actions
            ]
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
            let csvContent = "S.No,Invoice No,Date,Client ID,PAN No,LIC No,Particular,Quantity,Unit Rate,Total Amount,CGST,SGST,Net Amount,Created By,Created Date\n";
            
            // Add rows to CSV
            data.each(function (row, index) {
                // Extract text from HTML for each column
                const particular = extractTextFromHTML(row[6]);
                const panNo = extractTextFromHTML(row[4]);
                const licNo = extractTextFromHTML(row[5]);
                const netAmount = extractTextFromHTML(row[12]);
                
                // Clean data for CSV
                const cleanRow = [
                    index + 1,
                    extractTextFromHTML(row[1]),  // Invoice No
                    row[2],                        // Date
                    extractTextFromHTML(row[3]),  // Client ID
                    panNo,                        // PAN No
                    licNo,                        // LIC No
                    particular,                   // Particular
                    extractTextFromHTML(row[7]),  // Quantity
                    row[8]?.replace('₹', '') || '0',  // Unit Rate
                    row[9]?.replace('₹', '') || '0',  // Total Amount
                    row[10] || '0',               // CGST
                    row[11] || '0',               // SGST
                    netAmount?.replace('₹', '') || '0', // Net Amount
                    row[13],                      // Created By
                    row[14]                       // Created Date
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
            const totalInvoices = data.count();
            
            // Calculate totals
            let totalAmount = 0;
            let totalNetAmount = 0;
            data.each(function (row) {
                const total = parseFloat(row[9]?.replace('₹', '').replace(/,/g, '') || 0);
                const net = parseFloat(row[12]?.replace('₹', '').replace(/,/g, '') || 0);
                totalAmount += total;
                totalNetAmount += net;
            });
            
            let text = `📋 *INVOICE REPORTS*\n\n`;
            text += `📅 Date: ${currentDate}\n`;
            text += `📊 Total Invoices: ${totalInvoices}\n`;
            text += `💰 Total Amount: ₹${totalAmount.toFixed(2)}\n`;
            text += `💵 Net Amount: ₹${totalNetAmount.toFixed(2)}\n\n`;
            text += `================================\n\n`;
            
            // Add limited rows (max 10 for WhatsApp)
            let rowCount = 0;
            const maxRows = 10;
            
            data.each(function (row, index) {
                if (rowCount < maxRows) {
                    const invoiceNo = extractTextFromHTML(row[1]);
                    const particular = extractTextFromHTML(row[6]).substring(0, 20) + '...';
                    const netAmount = extractTextFromHTML(row[12]);
                    
                    text += `*${index + 1}. ${invoiceNo}*\n`;
                    text += `Date: ${row[2]}\n`;
                    text += `Client: ${extractTextFromHTML(row[3])}\n`;
                    text += `Particular: ${particular}\n`;
                    text += `Amount: ${netAmount}\n`;
                    text += `--------------------------------\n`;
                    rowCount++;
                }
            });
            
            if (totalInvoices > maxRows) {
                text += `\n...and ${totalInvoices - maxRows} more invoices.\n`;
            }
            
            text += `\n_Generated from Invoice Management System_`;
            
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
            const totalInvoices = table.rows({ filter: 'applied' }).count();
            
            // Create CSV blob
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const dataUrl = URL.createObjectURL(blob);
            
            // Create download link
            const link = document.createElement('a');
            const fileName = `Invoice_Reports_${currentDate.replace(/\//g, '-')}.csv`;
            link.href = dataUrl;
            link.download = fileName;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            // Prepare email
            setTimeout(() => {
                const subject = `domestic Invoice Reports - ${currentDate}`;
                let body = `Dear Team,\n\n`;
                body += `Please find attached the invoice reports.\n\n`;
                body += `Report Summary:\n`;
                body += `- Total Invoices: ${totalInvoices}\n`;
                body += `- Report Date: ${currentDate}\n`;
                body += `- Generated By: {{ auth()->user()->name ?? 'System' }}\n\n`;
                
                // Add first 5 rows as text preview
                const data = table.rows({ filter: 'applied' }).data();
                let preview = '';
                let previewCount = 0;
                data.each(function (row) {
                    if (previewCount < 5) {
                        const invoiceNo = extractTextFromHTML(row[1]);
                        const clientId = extractTextFromHTML(row[3]);
                        const netAmount = extractTextFromHTML(row[12]);
                        preview += `• ${invoiceNo} - Client ${clientId} - ${netAmount}\n`;
                        previewCount++;
                    }
                });
                
                if (previewCount > 0) {
                    body += `Recent Invoices (${previewCount} of ${totalInvoices}):\n`;
                    body += preview + '\n';
                }
                
                body += `Full details are available in the attached CSV file.\n\n`;
                body += `Best regards,\n`;
                body += `Accounts Team`;
                
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

        // Filter Modal
        $('.btn-filter').click(function() {
            $('#filterModal').modal('show');
        });
    });
</script>
@endsection