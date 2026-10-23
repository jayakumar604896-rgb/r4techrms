@extends('layouts.app')

@section('title', 'News')
@section('content')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap4.min.css">

<style>
    #sendtoother-listing thead th {
        background: #004085 !important;
        color: #ffffff !important;
        border-color: #003366 !important;
        font-weight: bold !important;
    }

    /* Sticky Header */
    #sendtoother-listing thead th {
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
            <h3 class="page-title fw-bold text-primary mb-1"> News </h3>
            
        </div>

        <a href="{{ route('news.create') }}" class="btn btn-primary btn-sm shadow-sm">
            <i class="mdi mdi-plus"></i> Add news
        </a>
    </div>
    {{-- Card --}}
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">

            <h4 class="card-title mb-3 fw-semibold">News - List</h4>
            
            <div class="table-responsive">
                <table id="sendtoother-listing" class="table table-hover align-middle">

                    <thead class="bg-primary text-white">
                        <tr>
                            <th>S.No</th>
                            <th>Title</th>
                            <th>Sub-Title</th>
                            <th>New Date</th>
                            <th>File</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($news as $key => $new)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td class="fw-semibold">{{ $new->title }}</td>
                                <td class="fw-semibold">{{ $new->sub_title }}</td>

                                <td>{{ $new->news_date ?? '' }}</td>
                                <td>
                                    @if($new->image)
                                        <a href="{{ asset($new->image) }}" target="_blank" title="View Image">
                                            <img src="{{ asset($new->image) }}" alt="News Image" style="max-width: 50px; max-height: 50px; border-radius: 4px;">
                                        </a>
                                        <br>
                                        <a href="{{ asset($new->image) }}" download class="text-primary small">Download</a>
                                    @else
                                        <span class="text-muted">No file</span>
                                    @endif
                                </td>
                                <td>{{ $new->created_at->format('d M, Y') }}</td>
                               <td class="text-center">
                                    <a href="{{ route('news.edit', $new->id) }}" class="btn btn-sm btn-primary" title="Edit">
                                        <i class="mdi mdi-pencil"></i>
                                    </a>
                                    <form action="{{ route('news.destroy', $new->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure?')">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

            {{-- Pagination
            @if($news->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $news->links() }}
                </div>
            @endif --}}

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

        if ($.fn.dataTable.isDataTable('#sendtoother-listing')) {
            $('#sendtoother-listing').DataTable().destroy();
        }

        // Initialize DataTable with Export/Print Buttons
        const table = $('#sendtoother-listing').DataTable({
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
                        columns: [0,1,2,3,4]
                    },
                    title: 'news_List_' + new Date().toLocaleDateString('en-GB')
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-sm',
                    text: '<i class="mdi mdi-file-pdf"></i> PDF',
                    exportOptions: {
                        columns: [0,1,2,3,4]
                    },
                    title: 'news_List_' + new Date().toLocaleDateString('en-GB'),
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
                        columns: [0,1,2,3,4]
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