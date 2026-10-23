@extends('layouts.app')

@section('title', 'Receipts')

@section('content')
<style>
    #receipts-table thead th {
        background: #004085 !important;
        color: #ffffff !important;
        border-color: #003366 !important;
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
    .selected-row {
        background-color: rgba(0, 64, 133, 0.1) !important;
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

    {{-- Success / Error Messages --}}
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
            <h3 class="page-title fw-bold mb-2" style="color: var(--primary-blue);">Receipts Management</h3>
            <p class="text-muted mb-0">
                <i class="mdi mdi-receipt me-1"></i>
                Manage and track all cash receipts
            </p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('receipts.create') }}" class="btn btn-primary d-flex align-items-center">
                <i class="mdi mdi-plus-circle-outline me-2"></i> Create New Receipt
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row mb-4">
        <div class="col-md-3 col-6">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Receipts</h6>
                            <h3 class="mb-0">{{ $receipts->count() }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                            <i class="mdi mdi-receipt text-primary" style="font-size: 24px;"></i>
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
                            <h6 class="text-muted mb-1">Total Amount</h6>
                            <h3 class="mb-0">₹ {{ number_format($receipts->sum('amount_received'), 2) }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                            <i class="mdi mdi-currency-inr text-success" style="font-size: 24px;"></i>
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
                            <h6 class="text-muted mb-1">Refundable</h6>
                            <h3 class="mb-0">{{ $receipts->where('is_refundable', true)->count() }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                            <i class="mdi mdi-cash-refund text-warning" style="font-size: 24px;"></i>
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
                            <h6 class="text-muted mb-1">Security Deposits</h6>
                            <h3 class="mb-0">{{ $receipts->where('is_security_deposit', true)->count() }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                            <i class="mdi mdi-shield-lock-outline text-info" style="font-size: 24px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Card with Table --}}
    <div class="card receipts-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">
                <i class="mdi mdi-format-list-bulleted me-2"></i>Receipts List
            </h5>
        </div>

        <div class="card-body p-0">
            @if($receipts->count() > 0)
                <div class="table-responsive">
                    <table id="receipts-table" class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th width="70">#</th>
                                <th>Receipt No.</th>
                                <th>Date</th>
                                <th>Received From</th>
                                <th>Mobile</th>
                                <th>Amount Received</th>
                                <th>Balance Due</th>
                                <th>Mode</th>
                                <th>Status</th>
                                <th width="180" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($receipts as $key => $receipt)
                            <tr>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $key + 1 }}</span>
                                </td>
                                <td>
                                    <strong>{{ $receipt->receipt_no }}</strong>
                                </td>
                                <td>{{ $receipt->date->format('d-m-Y') }}</td>
                                <td>{{ $receipt->received_from }}</td>
                                <td>{{ $receipt->mobile_no }}</td>
                                <td>₹ {{ number_format($receipt->amount_received, 2) }}</td>
                                <td>₹ {{ number_format($receipt->balance_due, 2) }}</td>
                                <td>
                                    @if($receipt->mode_of_payment)
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                            {{ $receipt->mode_of_payment }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($receipt->is_refundable)
                                        <span class="badge bg-warning bg-opacity-10 text-warning me-1">Refundable</span>
                                    @endif
                                    @if($receipt->is_non_refundable)
                                        <span class="badge bg-danger bg-opacity-10 text-danger me-1">Non‑Refundable</span>
                                    @endif
                                    @if($receipt->is_security_deposit)
                                        <span class="badge bg-info bg-opacity-10 text-info">Security</span>
                                    @endif
                                    @if(!$receipt->is_refundable && !$receipt->is_non_refundable && !$receipt->is_security_deposit)
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('receipts.show', $receipt) }}" 
                                       class="btn btn-sm btn-success text-white me-1 shadow-sm"
                                       title="View Receipt">
                                        <i class="mdi mdi-eye"></i> View
                                    </a>
                                    <a href="{{ route('receipts.edit', $receipt) }}" 
                                       class="btn btn-sm btn-primary text-white me-1 shadow-sm"
                                       title="Edit Receipt">
                                        <i class="mdi mdi-pencil"></i> Edit
                                    </a>
                                    <form action="{{ route('receipts.destroy', $receipt) }}" 
                                          method="POST" 
                                          style="display:inline"
                                          onsubmit="return confirm('Are you sure you want to delete this receipt?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-danger text-white shadow-sm"
                                                title="Delete Receipt">
                                            <i class="mdi mdi-delete"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="mdi mdi-receipt-outline"></i>
                    <h5 class="mt-3">No Receipts Found</h5>
                    <p class="text-muted">Create your first receipt to get started</p>
                    <a href="{{ route('receipts.create') }}" class="btn btn-primary mt-2">
                        <i class="mdi mdi-plus"></i> Create Receipt
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
$(document).ready(function() {
    if ($('#receipts-table').length > 0 && $('#receipts-table tbody tr').length > 0) {
        // Destroy any existing instance
        if ($.fn.dataTable.isDataTable('#receipts-table')) {
            $('#receipts-table').DataTable().destroy();
        }

        // Initialize DataTable with export buttons
        $('#receipts-table').DataTable({
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
                        columns: [1, 2, 3, 4, 5, 6, 7, 8] // exclude # and actions
                    },
                    title: 'Receipts_List_' + new Date().toLocaleDateString('en-GB')
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-sm',
                    text: '<i class="mdi mdi-file-pdf"></i> PDF',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8]
                    },
                    title: 'Receipts_List_' + new Date().toLocaleDateString('en-GB'),
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
                        columns: [1, 2, 3, 4, 5, 6, 7, 8]
                    },
                    title: '<h3 class="text-center mb-4">Receipts List</h3>',
                    customize: function(win) {
                        $(win.document.body).find('table').addClass('table table-bordered').css('font-size', '12px');
                        $(win.document.body).find('tr:nth-child(odd) td').css('background-color','#f9f9f9');
                        $(win.document.body).find('h1').css('text-align','center').css('color', '#004085');
                    }
                }
            ],
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search receipts...",
                lengthMenu: "Show _MENU_ entries",
                zeroRecords: "No matching receipts found",
                paginate: {
                    next: '<i class="mdi mdi-chevron-right"></i>',
                    previous: '<i class="mdi mdi-chevron-left"></i>'
                }
            },
            responsive: true,
            autoWidth: false,
            columnDefs: [
                { orderable: false, targets: [0, 9] }, // index and actions columns
                { searchable: false, targets: [0, 9] }
            ]
        });
    }

    // Optional: tooltips
    $('[title]').tooltip({ placement: 'top', trigger: 'hover' });
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