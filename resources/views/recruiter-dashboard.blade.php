@extends('layouts.app')

@section('title', 'Dashboard')

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
  .dataTables_length{
    display:none !important;
  }
  .dataTables_filter{
    display:none !important;
  }
</style>
 <div class="content-wrapper">
		<div class="mt-5">
        	<img class=" ms-2" src="{{ asset('uploads/company-settings/' . $companySetting->image) }}" alt="Profile image" style="width:100%">
          </div>
            <div class="row">
                <div class="col-xl-4 grid-margin stretch-card">
                    <div class="card quick-status-card">
                        <div class="card-body">
                            <h4 class="card-title">Monthly Performance Summary</h4>
                            <div class="row">
                                <div class="col-md-6 text-center mb-3">
                                    <div class="circle-progress-wrapper" style="width: 120px; height: 120px; margin: 0 auto; position: relative;">
                                        <div id="circle-progress-current"></div>
                                        <div class="circle-progress-text" 
                                            style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-weight: bold; font-size: 20px;">
                                            {{ $currentMonthProgress['overall'] ?? 0 }}%
                                        </div>
                                    </div>
                                    <p class="mt-2 small text-muted">Current Month</p>
                                    <h6 class="mb-0">{{ $currentMonth }}</h6>
                                    <p class="mb-0">{{ $currentMonthProgress['overall'] ?? 0 }}% Overall</p>
                                </div>
                                
                                <div class="col-md-6 text-center mb-3">
                                    <div class="circle-progress-wrapper" style="width: 120px; height: 120px; margin: 0 auto; position: relative;">
                                        <div id="circle-progress-previous"></div>
                                        <div class="circle-progress-text" 
                                            style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-weight: bold; font-size: 20px;">
                                            {{ $previousMonthProgress['overall'] ?? 0 }}%
                                        </div>
                                    </div>
                                    <p class="mt-2 small text-muted">Previous Month</p>
                                    <h6 class="mb-0">{{ $previousMonth }}</h6>
                                    <p class="mb-0">{{ $previousMonthProgress['overall'] ?? 0 }}% Overall</p>
                                </div>
                            </div>
                      
                            <!-- Comparison Row -->
                            <div class="row mt-4 border-top pt-3">
                                <div class="col-12">
                                    @php
                                    $overallChange = ($currentMonthProgress['overall'] ?? 0) - ($previousMonthProgress['overall'] ?? 0);
                                    $changeClass = $overallChange >= 0 ? 'text-success' : 'text-danger';
                                    $changeIcon = $overallChange >= 0 ? 'icon-arrow-up-circle' : 'icon-arrow-down-circle';
                                    @endphp
                                
                                    <div class="wrapper py-3 d-flex border-bottom">
                                    <span class="icon-holder">
                                        <i class="icon-chart"></i>
                                    </span>
                                    <div class="ms-3">
                                        <p class="mb-1">Overall Progress Change</p>
                                        <h6 class="mb-0 {{ $changeClass }}">
                                            {{ $overallChange >= 0 ? '+' : '' }}{{ number_format($overallChange, 1) }}%
                                        </h6>
                                    </div>
                                    <div class="ms-auto">
                                        <i class="{{ $changeIcon }} {{ $changeClass }}"></i>
                                        <span class="{{ $changeClass }} ms-2">
                                            {{ number_format(abs($overallChange), 1) }}%
                                        </span>
                                    </div>
                                    </div>
                                
                                <!-- Selection Comparison -->
                                    @php
                                    $selectionChange = $currentSelectionPercent - $previousSelectionPercent;
                                    $selectionClass = $selectionChange >= 0 ? 'text-success' : 'text-danger';
                                    @endphp
                                
                                    <div class="wrapper py-3 d-flex">
                                    <span class="icon-holder">
                                        <i class="icon-check"></i>
                                    </span>
                                    <div class="ms-3">
                                        <p class="mb-1">Selection Rate Change</p>
                                        <h6 class="mb-0 {{ $selectionClass }}">
                                            {{ $selectionChange >= 0 ? '+' : '' }}{{ number_format($selectionChange, 1) }}%
                                        </h6>
                                        <small class="text-muted">
                                            {{ $currentSelectionPercent }}% vs {{ $previousSelectionPercent }}%
                                        </small>
                                    </div>
                                    <div class="ms-auto">
                                        <span class="badge {{ $selectionChange >= 0 ? 'bg-success' : 'bg-danger' }}">
                                            {{ $selectionChange >= 0 ? 'Improved' : 'Declined' }}
                                        </span>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-4 grid-margin stretch-card">
                    <div class="card sessions-progress-bar-card">
                    <div class="card-header">
                        <h4> Latest News </h4>
                    </div>
                        <div class="card-body">
                            <div class="marquee-container">
                                <div class="marquee-content">
                                    <ul class="marquee-inner">
                                        @foreach($news as $index => $new)
                                            <li>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h6>{{ $index + 1 }} {{ $new->sub_title }}   <span class="text-muted font-weight-normal text-capitalize"></span></h6>
                                                    <p class="font-weight-semibold">
                                                        <span class="text-muted font-weight-normal text-capitalize">
                                                            {{ $new->news_date }}
                                                        </span>
                                                    </p>
                                                </div>
                                                
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

              <div class="col-md-4 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title"> Resume Data base Volume  </h4>
                    <div class="aligner-wrapper py-3">
                      <div class="doughnut-chart-height">
                        <canvas id="sessionsDoughnutChart" height="210"></canvas>
                      </div>
                      <div class="wrapper d-flex flex-column justify-content-center absolute absolute-center">
                        <h2 class="text-center mb-0 font-weight-bold">8.234</h2>
                        <small class="d-block text-center text-muted  font-weight-semibold mb-0">Total Leads</small>
                      </div>
                    </div>
                    <div class="wrapper mt-4 d-flex flex-wrap align-items-cente">
                      <div class="d-flex">
                        <span class="square-indicator bg-danger ms-2"></span>
                        <p class="mb-0 ms-2"> Domestic Jobs </p>
                      </div>
                      <div class="d-flex">
                        <span class="square-indicator bg-success ms-2"></span>
                        <p class="mb-0 ms-2"> International Jobs </p>
                      </div>
                      <div class="d-flex">
                        <span class="square-indicator bg-warning ms-2"></span>
                        <p class="mb-0 ms-2"> Website Resumes </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="d-sm-flex align-items-baseline report-summary-header">
                          <h5 class="font-weight-semibold"> Progress Report Summary</h5> <span class="ms-auto">Updated Report</span> <button class="btn btn-icons border-0 p-2"><i class="icon-refresh"></i></button>
                        </div>
                      </div>
                    </div>
                    <div class="row report-inner-cards-wrapper">
                      <div class=" col-md -6 col-xl report-inner-card">
                        <div class="inner-card-text">
                          <span class="report-title">TotalCandidate</span>
                          <h4>{{ $candidatecounts ?? '' }}</h4>
                         <!-- <span class="report-count"> 2 Reports</span>-->
                        </div>
                        <div class="inner-card-icon bg-success">
                          <i class="icon-rocket"></i>
                        </div>
                      </div>
                      <div class="col-md-6 col-xl report-inner-card">
                        <div class="inner-card-text">
                          <span class="report-title">Onboarded </span>
                         <!-- <h4>58</h4>
                          <span class="report-count"> 3 Reports</span>-->
                        </div>
                        <div class="inner-card-icon bg-danger">
                          <i class="icon-briefcase"></i>
                        </div>
                      </div>
                      <div class="col-md-6 col-xl report-inner-card">
                        <div class="inner-card-text">
                          <span class="report-title">On Process</span>
                          <!--<h4>50</h4>
                          <span class="report-count"> 5 Reports</span>-->
                        </div>
                        <div class="inner-card-icon bg-warning">
                          <i class="icon-globe-alt"></i>
                        </div>
                      </div>
                      <div class="col-md-6 col-xl report-inner-card">
                        <div class="inner-card-text">
                          <span class="report-title">Back Out</span>
                         <!-- <h4>42</h4>
                          <span class="report-count"> 9 Reports</span>-->
                        </div>
                        <div class="inner-card-icon bg-primary">
                          <i class="icon-diamond"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                      <div class="d-sm-flex align-items-center mb-4">
                        <h4 class="card-title mb-sm-0"> Resume Progress Status </h4>
                        <a href="{{ route('candidates.index') }}" class="text-dark ms-auto mb-3 mb-sm-0 btn btn-primary btn-sm"> View all</a>
                      </div>
                      <div class="table-responsive">
                        <table id="candidates-table" class="table table-hover align-middle resume-table w-100">
                            <thead>
                                <tr style="background-color: #004085; color: #fff;">
                                    <th class="fw-bold">Name</th>
                                    <th class="fw-bold">Industry / Sector</th>
                                    <th class="fw-bold">Designation</th>
                                    <th class="fw-bold">Experience</th>
                                    <th class="fw-bold">Qualification</th>
                                    <th class="fw-bold">Mobile</th>
                                    <th class="fw-bold">CV Code</th>
                                    
                                </tr>

                                {{-- Search Inputs 
                                <tr>
                                    <th><input type="text" class="form-control form-control-sm" placeholder="Search Name"></th>
                                    <th><input type="text" class="form-control form-control-sm" placeholder="Search Industry"></th>
                                    <th><input type="text" class="form-control form-control-sm" placeholder="Search Designation"></th>
                                    <th><input type="text" class="form-control form-control-sm" placeholder="Search Experience"></th>
                                    <th><input type="text" class="form-control form-control-sm" placeholder="Search Qualification"></th>
                                    <th><input type="text" class="form-control form-control-sm" placeholder="Search Mobile"></th>
                                    <th><input type="text" class="form-control form-control-sm" placeholder="Search CV Code"></th>
                                </tr>--}}
                            </thead>
                            <tbody>
                                @foreach($candidates as $index => $user)
                                    <tr>
                                        <td>{{ $user->candidate_name }}</td>
                                        <td>{{ $user->industry }}</td>
                                        <td>{{ $user->designation }}</td>
                                        <td>{{ $user->total_exp }}</td>
                                        <td>{{ $user->qualfn }}</td>
                                        <td>{{ $user->mobile }}</td>
                                        <td>{{ $user->code_no }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
            <div class="row">
            <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <div class="popover-static-demo">
                      <div class="popover bs-popover-bottom bs-popover-bottom-demo popover-success">
                        <div class="arrow"></div>
                        <h3 class="popover-header"> <center> Demand Register </center> </h3>
                        <div class="popover-body">
                          <p>Recruiter: Sed posuere consectetur est at lobortis. Aenean eu leo quam.</p>
                        </div>
                      </div>                      
                      <div class="popover bs-popover-bottom bs-popover-bottom-demo popover-warning">
                        <div class="arrow"></div>
                        <h3 class="popover-header"> <center> Progress Report </center></h3>
                        <div class="popover-body">
                          <p>Recruiter: Sed posuere consectetur est at lobortis. Aenean eu leo quam.</p>
                        </div>
                      </div>
                      <div class="popover bs-popover-bottom bs-popover-bottom-demo popover-danger">
                        <div class="arrow"></div>
                        <h3 class="popover-header"> <center> Offer Letters / Pre Offers </h3>
                        <div class="popover-body">
                          <p>Recruiter: Sed posuere consectetur est at lobortis. Aenean eu leo quam.</p>
                        </div>
                      </div>
                      <div class="popover bs-popover-bottom bs-popover-bottom-demo popover-info">
                        <div class="arrow"></div>
                        <h3 class="popover-header"> Invoice  / Quotations  </center>   </h3>
                        <div class="popover-body">
                          <p>Recruiter: Sed posuere consectetur est at lobortis. Aenean eu leo quam.</p>
                        </div>
                      </div>
                      <div class="popover bs-popover-bottom bs-popover-bottom-demo popover-primary">
                        <div class="arrow"></div>
                        <h3 class="popover-header"> <center> JOB Reporting / Profile </center> </h3>
                        <div class="popover-body">
                          <p>Recruiter: Sed posuere consectetur est at lobortis. Aenean eu leo quam.</p>
                        </div>
                      </div>
                      <div class="clearfix"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
          </div>

@endsection
@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-circle-progress@1.2.2/dist/circle-progress.min.js"></script>
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
                        columns: [1, 2, 3, 4, 5, 6, 7] // Same column selection as CSV
                    },
                    title: 'Candidate_List_' + new Date().toLocaleDateString('en-GB')
                },
                {
                    extend: 'excel',
                    className: 'btn btn-sm',
                    text: '<i class="mdi mdi-file-excel"></i> Excel',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7] // Same column selection as CSV
                    },
                    title: 'Candidate_List_' + new Date().toLocaleDateString('en-GB')
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-sm',
                    text: '<i class="mdi mdi-file-pdf"></i> PDF',
                    exportOptions: {
                        columns: [1, 2, 3, 4, 5, 6, 7] // Same column selection as CSV
                    },
                    title: 'Candidate_List_' + new Date().toLocaleDateString('en-GB'),
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
                        columns: [1, 2, 3, 4, 5, 6, 7] // Same column selection as CSV
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
$(document).ready(function() {
    console.log('Document ready');
    console.log('Current month progress:', {{ $currentMonthProgress['overall'] ?? 0 }});
    console.log('Previous month progress:', {{ $previousMonthProgress['overall'] ?? 0 }});
    
    // Check if circleProgress function exists
    if (typeof $.fn.circleProgress === 'function') {
        console.log('Circle Progress plugin is loaded');
        
        // Initialize current month circle progress
        $('#circle-progress-current').circleProgress({
            value: {{ ($currentMonthProgress['overall'] ?? 0) / 100 }},
            size: 120,
            fill: {
                color: '#007bff'
            },
            emptyFill: 'rgba(0, 0, 0, 0.1)',
            thickness: 10,
            animation: {
                duration: 1000
            }
        });
        
        // Initialize previous month circle progress
        $('#circle-progress-previous').circleProgress({
            value: {{ ($previousMonthProgress['overall'] ?? 0) / 100 }},
            size: 120,
            fill: {
                color: '#6c757d'
            },
            emptyFill: 'rgba(0, 0, 0, 0.1)',
            thickness: 10,
            animation: {
                duration: 1000
            }
        });
    } else {
        console.error('Circle Progress plugin is NOT loaded');
        // Fallback: Show regular progress bars
        $('#circle-progress-current').html('<div class="progress" style="height: 120px; width: 120px; border-radius: 50%; background: conic-gradient(#007bff {{ ($currentMonthProgress["overall"] ?? 0) * 3.6 }}deg, rgba(0,0,0,0.1) 0deg);"></div>');
        $('#circle-progress-previous').html('<div class="progress" style="height: 120px; width: 120px; border-radius: 50%; background: conic-gradient(#6c757d {{ ($previousMonthProgress["overall"] ?? 0) * 3.6 }}deg, rgba(0,0,0,0.1) 0deg);"></div>');
    }
});
</script>
@endsection