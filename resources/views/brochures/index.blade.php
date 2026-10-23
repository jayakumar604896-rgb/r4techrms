@extends('layouts.app')

@section('title', 'Brochure')
@section('content')
<style>
    #brochure-listing thead th {
        background: #004085 !important;
        color: #ffffff !important;
        border-color: #003366 !important;
        font-weight: bold !important;
    }

    /* Sticky Header */
    #brochure-listing thead th {
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
            <h3 class="page-title fw-bold text-primary mb-1"> Brochure Registers </h3>
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active"> Brochure Registers </li>
            </ol>
        </div>

        <a href="{{ route('newbrochures.create') }}" class="btn btn-primary btn-sm shadow-sm">
            <i class="mdi mdi-plus"></i> Add Brochure Registers
        </a>
    </div>

    {{-- Card --}}
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">

            <h4 class="card-title mb-3 fw-semibold">Brochure Registers - List</h4>
            <div class="table-responsive">
                <table id="brochure-listing" class="table table-hover align-middle">

                    <thead class="bg-primary text-white">
                        <tr>
                            <th>S.No</th>
                            <th>Brochure Title</th>
                            <th>File</th>
                            <th>Status</th>
                            <th>Created Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($brochures as $key => $brochure)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td class="fw-semibold">{{ $brochure->name }}</td>
                                <td>
                                    <a href="{{ asset($brochure->file_path) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                        <i class="mdi mdi-file-pdf"></i> View
                                    </a>
                                </td>
                                <td>
                                    <span class="badge {{ $brochure->is_active == '1' ? 'bg-success' : 'bg-danger' }}">
                                        {{ $brochure->is_active ==1 ? 'Active' : Inactive }}
                                    </span>
                                </td>
                                <td>{{ $brochure->created_at->format('d M, Y') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('newbrochures.edit', $brochure->id) }}" 
                                       class="btn btn-sm btn-primary">
                                        <i class="mdi mdi-pencil"></i>
                                    </a>
                                    <form action="{{ route('newbrochures.destroy', $brochure->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this brochure?')">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

            {{-- Pagination --}}
            @if($brochures->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $brochures->links() }}
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

<!-- Export Libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script>
    $(document).ready(function() {

        if ($.fn.dataTable.isDataTable('#brochure-listing')) {
            $('#brochure-listing').DataTable().destroy();
        }

        // Initialize DataTable with Export/Print Buttons
        const table = $('#brochure-listing').DataTable({
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>" +
                 "<'row'<'col-sm-12'B>>",
            buttons: [
                // {
                //     extend: 'copy',
                //     className: 'btn btn-sm',
                //     text: '<i class="mdi mdi-content-copy"></i> Copy',
                //     exportOptions: {
                //         columns: ':visible'
                //     }
                // },
                {
                    extend: 'csv',
                    className: 'btn btn-sm',
                    text: '<i class="mdi mdi-file-delimited"></i> CSV',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8]
                    },
                    title: 'Brochure_List_' + new Date().toLocaleDateString('en-GB')
                },
                {
                    extend: 'excel',
                    className: 'btn btn-sm',
                    text: '<i class="mdi mdi-file-excel"></i> Excel',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8]
                    },
                    title: 'Brochure_List_' + new Date().toLocaleDateString('en-GB')
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-sm',
                    text: '<i class="mdi mdi-file-pdf"></i> PDF',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7, 8]
                    },
                    title: 'Brochure_List_' + new Date().toLocaleDateString('en-GB'),
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
                    title: '<h3 class="text-center mb-4">Recruiters List</h3>',
                    customize: function(win) {
                        $(win.document.body).find('table').addClass('table table-bordered').css('font-size', '12px');
                        $(win.document.body).find('tr:nth-child(odd) td').css('background-color','#f9f9f9');
                        $(win.document.body).find('h1').css('text-align','center');
                        $(win.document.body).find('h1').css('color', '#004085');
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
    });
</script>
@endsection