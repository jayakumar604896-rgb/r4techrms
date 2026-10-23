@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
 <div class="content-wrapper">
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
	.dashboard-banner {
        width: 100%;
        height: 150px;
        border-radius: 10px;
        margin-bottom: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        border: 1px solid #e8edf3;
        object-fit: cover;
        display: block;
    }
  .dataTables_wrapper .dataTable thead .sorting:before, .dataTables_wrapper .dataTable thead .sorting_asc:before, .dataTables_wrapper .dataTable thead .sorting_desc:before, .dataTables_wrapper .dataTable thead .sorting_asc_disabled:before, .dataTables_wrapper .dataTable thead .sorting_desc_disabled:before
  {
      content: "\e078";
      right: 0.5rem !important;
      top: 0px !important;
  }
  .dataTables_wrapper .dataTable thead .sorting:after, .dataTables_wrapper .dataTable thead .sorting_asc:after, .dataTables_wrapper .dataTable thead .sorting_desc:after, .dataTables_wrapper .dataTable thead .sorting_asc_disabled:after, .dataTables_wrapper .dataTable thead .sorting_desc_disabled:after
  {
      content: "\e07b";
      right: 0.5rem !important;
  }
  .form-control, .typeahead, .tt-query, .tt-hint, .select2-container--default .select2-selection--single .select2-search__field, .select2-container--default .select2-selection--single, .jsgrid .jsgrid-table .jsgrid-filter-row input[type=number], .jsgrid .jsgrid-table .jsgrid-filter-row input[type=text], .jsgrid .jsgrid-table .jsgrid-filter-row select, .dataTables_wrapper select, .asColorPicker-input
  {
  padding: 8px !important;
  }
  .form-control-sm
  {
  min-height: 1.575rem !important;
  }
  .marquee-container {
      height: 380px; /* Adjust as needed */
      overflow: hidden;
      position: relative;
  }

  .marquee-content {
      display: block;
      animation: scrollUp 12s linear infinite;
      animation-delay: 3s; /* Optional delay */
  }

  .marquee-inner {
      display: flex;
      flex-direction: column;
      gap: 0.5rem; /* Space between list items */
      padding: 0;
      margin: 0;
  }

  .marquee-inner li {
      padding: 0.5rem 0; /* Vertical spacing */
      list-style: none; /* Remove default bullets */
  }

  .marquee-inner h6 {
      margin: 0;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 0.5rem; /* Space between serial number and name */
  }

  .marquee-inner p {
      margin: 0;
      display: flex;
      align-items: center;
  }

  .progress.progress-md {
      height: 6px; /* Slim progress bar */
      margin-top: 4px;
  }
  .marquee-container:hover .marquee-content {
    animation-play-state: paused;
}
.news-item {
    cursor: pointer;
    transition: all 0.3s ease;
    padding: 10px 15px;
    border-radius: 5px;
    margin-bottom: 5px;
}

.news-item:hover {
    background-color: rgba(0, 123, 255, 0.1);
    transform: translateX(5px);
}

.news-item .news-title {
    color: #004085;
    font-weight: 500;
}

.news-item:hover .news-title {
    color: #0056b3;
    text-decoration: underline;
}

.cursor-pointer {
    cursor: pointer !important;
}

.news-description-content {
    line-height: 1.6;
    color: #333;
}

.news-description-content p {
    margin-bottom: 1rem;
}

.news-description-content ul,
.news-description-content ol {
    padding-left: 1.5rem;
    margin-bottom: 1rem;
}
.btn-secondary{
    background: #8e32e9 !important;
}

.news-meta .badge {
    padding: 0.35rem 0.75rem;
    font-size: 0.875rem;
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
    .dashboard-card{
        padding: 0.50rem 0.50rem !important;
    }
  @keyframes scrollUp {
      0% { transform: translateY(0); }
      100% { transform: translateY(-50%); }
  }
</style>
            <div class="row">
				@if($companySetting && $companySetting->image)
    				<img src="{{ asset('uploads/company-settings/' . $companySetting->image) }}" alt="We Are Hiring Banner" class="dashboard-banner">
					<!-- <img class=" ms-2" src="{{ asset('uploads/company-settings/' . $companySetting->image) }}" alt="Profile image" style="width:100%"> -->
				@endif
                <div class="col-md-6 col-xl-4 grid-margin stretch-card">
                    <div class="card sessions-progress-bar-card">
                    <div class="card-header">
                        <h4> Users Statistics </h4>
                    </div>
                        <div class="card-body">
                            <div class="marquee-container">
                                <div class="marquee-content">
                                    <ul class="marquee-inner">
                                        @foreach($adminUsers as $index => $user)
                                            <li>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <h6>{{ $index + 1 }}. {{ $user->name }}   <span class="text-muted font-weight-normal text-capitalize">({{ $user->role }})</span></h6>
                                                    <p class="font-weight-semibold">
                                                        <span class="text-muted font-weight-normal text-capitalize">
                                                            ({{ $user->user_status }})
                                                        </span>
                                                    </p>
                                                </div>
                                                <div class="progress progress-md">
                                                    <div class="progress-bar 
                                                        @if($user->user_status == 'active') bg-success
                                                        @elseif($user->user_status == 'idle') bg-warning
                                                        @elseif($user->user_status == 'offline') bg-danger
                                                        @else bg-secondary
                                                        @endif"
                                                        role="progressbar"
                                                        style="width: 90%"
                                                        aria-valuenow="90"
                                                        aria-valuemin="0"
                                                        aria-valuemax="100">
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
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
                                          <li class="news-item" 
                                              data-news-id="{{ $new->id }}"
                                              data-title="{{ $new->title }}"
                                              data-subtitle="{{ $new->sub_title }}"
                                              data-date="{{ $new->news_date ? \Carbon\Carbon::parse($new->news_date)->format('d M, Y') : 'N/A' }}"
                                              data-time="{{ $new->created_at->format('h:i A') }}"
                                              data-description="{{ $new->description ?? '' }}"
                                              data-image="{{ $new->image ? asset($new->image) : asset('assets/images/default-news.jpg') }}">
                                              <div class="d-flex justify-content-between align-items-center">
                                                  <h6 class="cursor-pointer" style="cursor: pointer;">
                                                      <span class="news-serial">{{ $index + 1 }}.</span> 
                                                      <span class="news-title">{{ $new->sub_title }}</span>
                                                  </h6>
                                                  <p class="font-weight-semibold">
                                                      <span class="text-muted font-weight-normal text-capitalize">
                                                          {{ $new->news_date ? \Carbon\Carbon::parse($new->news_date)->format('d M, Y') : 'N/A' }}
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
                
               <!--<div class="col-xl-4 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <div class="d-sm-flex align-items-center">
                      <h4 class="card-title mb-0"> Latest News </h4>
                      <a href="#" class="btn btn-outline-info border-0 font-weight-semibold ms-auto p-0 btn-no-hover-bg">View more</a>
                    </div>
                    <div class="d-flex mt-4 py-3 border-bottom">
                      <img class="img-sm rounded-circle" src="../../../assets/images/faces/face3.jpg" alt="profile image">
                      <div class="wrapper ms-2">
                        <p class="mb-1 font-weight-medium">Mobile Apps Redesign</p>
                        <small class="text-muted">+23 since last year</small>
                      </div>
                      <small class="text-muted ms-auto">10:07PM</small>
                    </div>
                    <div class="d-flex py-3 border-bottom">
                      <img class="img-sm rounded-circle" src="../../../assets/images/faces/face2.jpg">
                      <div class="wrapper ms-2">
                        <p class="mb-1 font-weight-medium">Inviting Join Apps Cont...</p>
                        <small class="text-muted">+23 since last year</small>
                      </div>
                      <small class="text-muted ms-auto">01:07AM</small>
                    </div>
                    <div class="d-flex py-3 border-bottom">
                      <img class="img-sm rounded-circle" src="../../../assets/images/faces/face4.jpg" alt="profile image">
                      <div class="wrapper ms-2">
                        <p class="mb-1 font-weight-medium">Website Redesign</p>
                        <small class="text-muted">+23 since last year</small>
                      </div>
                      <small class="text-muted ms-auto">04:42AM</small>
                    </div>
                    <div class="d-flex py-3  border-bottom">
                      <img class="img-sm rounded-circle" src="../../../assets/images/faces/face8.jpg">
                      <div class="wrapper ms-2">
                        <p class="mb-1 font-weight-medium">Analytics Dashboard</p>
                        <small class="text-muted">+23 since last year</small>
                      </div>
                      <small class="text-muted ms-auto">07:44PM</small>
                    </div>
                    <div class="d-flex pt-3">
                      <img class="img-sm rounded-circle" src="../../../assets/images/faces/face7.jpg">
                      <div class="wrapper ms-2">
                        <p class="mb-1 font-weight-medium">Great Logo Design</p>
                        <small class="text-muted">+23 since last year</small>
                      </div>
                      <small class="text-muted ms-auto">10:49AM</small>
                    </div>
                  </div>
                </div>
              </div>-->

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
                        <div class="d-sm-flex align-items-baseline ">
                          <h5 class="font-weight-semibold"> Domestic Progress Report Summary</h5> <span class="ms-auto">Updated Report</span> <button class="btn btn-icons border-0 p-2"><i class="icon-refresh"></i></button>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <!-- Total Candidates -->
                      <div class="col-md-2 col-6 mb-3">
                          <div class="card border-0 shadow-sm rounded-3">
                              <div class="card-body dashboard-card">
                                  <div class="d-flex justify-content-between align-items-center">
                                      <div>
                                          <h6 class="text-muted mb-1">Total Candidates</h6>
                                          <h3 class="mb-0">{{ $candidateCounts['total'] ?? 0 }}</h3>
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
                              <div class="card-body dashboard-card">
                                  <div class="d-flex justify-content-between align-items-center">
                                      <div>
                                          <h6 class="text-muted mb-1">Inprogress</h6>
                                          <h3 class="mb-0">{{ $candidateCounts['inprogress'] ?? 0 }}</h3>
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
                              <div class="card-body dashboard-card">
                                  <div class="d-flex justify-content-between align-items-center">
                                      <div>
                                          <h6 class="text-muted mb-1">Selected</h6>
                                          <h3 class="mb-0">{{ $candidateCounts['selected'] ?? 0 }}</h3>
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
                              <div class="card-body dashboard-card">
                                  <div class="d-flex justify-content-between align-items-center">
                                      <div>
                                          <h6 class="text-muted mb-1">Waiting List</h6>
                                          <h3 class="mb-0">{{ $candidateCounts['waitinglist'] ?? 0 }}</h3>
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
                              <div class="card-body dashboard-card">
                                  <div class="d-flex justify-content-between align-items-center">
                                      <div>
                                          <h6 class="text-muted mb-1">Rejected</h6>
                                          <h3 class="mb-0">{{ $candidateCounts['rejected'] ?? 0 }}</h3>
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
                              <div class="card-body dashboard-card">
                                  <div class="d-flex justify-content-between align-items-center">
                                      <div>
                                          <h6 class="text-muted mb-1">Back Out</h6>
                                          <h3 class="mb-0">{{ $candidateCounts['backout'] ?? 0 }}</h3>
                                      </div>
                                      <div class="bg-secondary bg-opacity-10 p-3 rounded-circle">
                                          <i class="mdi mdi-account-arrow-left text-secondary" style="font-size: 24px;"></i>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="d-sm-flex align-items-baseline ">
                          <h5 class="font-weight-semibold">International Progress Report Summary</h5> <span class="ms-auto">Updated Report</span> <button class="btn btn-icons border-0 p-2"><i class="icon-refresh"></i></button>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <!-- Total Candidates -->
                      <div class="col-md-2 col-6 mb-3">
                          <div class="card border-0 shadow-sm rounded-3">
                              <div class="card-body dashboard-card">
                                  <div class="d-flex justify-content-between align-items-center">
                                      <div>
                                          <h6 class="text-muted mb-1">Total Candidates</h6>
                                          <h3 class="mb-0">{{ $internationalcandidateCounts['total'] ?? 0 }}</h3>
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
                              <div class="card-body dashboard-card">
                                  <div class="d-flex justify-content-between align-items-center">
                                      <div>
                                          <h6 class="text-muted mb-1">Inprogress</h6>
                                          <h3 class="mb-0">{{ $internationalcandidateCounts['inprogress'] ?? 0 }}</h3>
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
                              <div class="card-body dashboard-card">
                                  <div class="d-flex justify-content-between align-items-center">
                                      <div>
                                          <h6 class="text-muted mb-1">Selected</h6>
                                          <h3 class="mb-0">{{ $internationalcandidateCounts['selected'] ?? 0 }}</h3>
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
                              <div class="card-body dashboard-card">
                                  <div class="d-flex justify-content-between align-items-center">
                                      <div>
                                          <h6 class="text-muted mb-1">Waiting List</h6>
                                          <h3 class="mb-0">{{ $internationalcandidateCounts['waitinglist'] ?? 0 }}</h3>
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
                              <div class="card-body dashboard-card">
                                  <div class="d-flex justify-content-between align-items-center">
                                      <div>
                                          <h6 class="text-muted mb-1">Rejected</h6>
                                          <h3 class="mb-0">{{ $internationalcandidateCounts['rejected'] ?? 0 }}</h3>
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
                              <div class="card-body dashboard-card">
                                  <div class="d-flex justify-content-between align-items-center">
                                      <div>
                                          <h6 class="text-muted mb-1">Back Out</h6>
                                          <h3 class="mb-0">{{ $internationalcandidateCounts['backout'] ?? 0 }}</h3>
                                      </div>
                                      <div class="bg-secondary bg-opacity-10 p-3 rounded-circle">
                                          <i class="mdi mdi-account-arrow-left text-secondary" style="font-size: 24px;"></i>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-sm-flex align-items-baseline">
                                <h5 class="font-weight-semibold">
                                    International Progress Report Summary - GULF
                                </h5>

                                <span class="ms-auto">Updated Report</span>

                                <button class="btn btn-icons border-0 p-2">
                                    <i class="icon-refresh"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Total Candidates -->
                        <div class="col-md-2 col-6 mb-3">
                            <div class="card border-0 shadow-sm rounded-3">
                                <div class="card-body dashboard-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        
                                        <div>
                                            <h6 class="text-muted mb-1">Total Candidates</h6>
                                            <h3 class="mb-0">
                                                {{ $internationalgulfcandidateCounts['total'] ?? 0 }}
                                            </h3>
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
                                <div class="card-body dashboard-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        
                                        <div>
                                            <h6 class="text-muted mb-1">Inprogress</h6>
                                            <h3 class="mb-0">
                                                {{ $internationalgulfcandidateCounts['inprogress'] ?? 0 }}
                                            </h3>
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
                                <div class="card-body dashboard-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        
                                        <div>
                                            <h6 class="text-muted mb-1">Selected</h6>
                                            <h3 class="mb-0">
                                                {{ $internationalgulfcandidateCounts['selected'] ?? 0 }}
                                            </h3>
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
                                <div class="card-body dashboard-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        
                                        <div>
                                            <h6 class="text-muted mb-1">Waiting List</h6>
                                            <h3 class="mb-0">
                                                {{ $internationalgulfcandidateCounts['waitinglist'] ?? 0 }}
                                            </h3>
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
                                <div class="card-body dashboard-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        
                                        <div>
                                            <h6 class="text-muted mb-1">Rejected</h6>
                                            <h3 class="mb-0">
                                                {{ $internationalgulfcandidateCounts['rejected'] ?? 0 }}
                                            </h3>
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
                                <div class="card-body dashboard-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        
                                        <div>
                                            <h6 class="text-muted mb-1">Back Out</h6>
                                            <h3 class="mb-0">
                                                {{ $internationalgulfcandidateCounts['backout'] ?? 0 }}
                                            </h3>
                                        </div>

                                        <div class="bg-secondary bg-opacity-10 p-3 rounded-circle">
                                            <i class="mdi mdi-account-arrow-left text-secondary" style="font-size: 24px;"></i>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-sm-flex align-items-baseline">
                                <h5 class="font-weight-semibold">
                                    International Progress Report Summary - SEA
                                </h5>

                                <span class="ms-auto">Updated Report</span>

                                <button class="btn btn-icons border-0 p-2">
                                    <i class="icon-refresh"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <!-- Total Candidates -->
                        <div class="col-md-2 col-6 mb-3">
                            <div class="card border-0 shadow-sm rounded-3">
                                <div class="card-body dashboard-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        
                                        <div>
                                            <h6 class="text-muted mb-1">Total Candidates</h6>
                                            <h3 class="mb-0">
                                                {{ $internationalseacandidateCounts['total'] ?? 0 }}
                                            </h3>
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
                                <div class="card-body dashboard-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        
                                        <div>
                                            <h6 class="text-muted mb-1">Inprogress</h6>
                                            <h3 class="mb-0">
                                                {{ $internationalseacandidateCounts['inprogress'] ?? 0 }}
                                            </h3>
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
                                <div class="card-body dashboard-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        
                                        <div>
                                            <h6 class="text-muted mb-1">Selected</h6>
                                            <h3 class="mb-0">
                                                {{ $internationalseacandidateCounts['selected'] ?? 0 }}
                                            </h3>
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
                                <div class="card-body dashboard-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        
                                        <div>
                                            <h6 class="text-muted mb-1">Waiting List</h6>
                                            <h3 class="mb-0">
                                                {{ $internationalseacandidateCounts['waitinglist'] ?? 0 }}
                                            </h3>
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
                                <div class="card-body dashboard-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        
                                        <div>
                                            <h6 class="text-muted mb-1">Rejected</h6>
                                            <h3 class="mb-0">
                                                {{ $internationalseacandidateCounts['rejected'] ?? 0 }}
                                            </h3>
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
                                <div class="card-body dashboard-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        
                                        <div>
                                            <h6 class="text-muted mb-1">Back Out</h6>
                                            <h3 class="mb-0">
                                                {{ $internationalseacandidateCounts['backout'] ?? 0 }}
                                            </h3>
                                        </div>

                                        <div class="bg-secondary bg-opacity-10 p-3 rounded-circle">
                                            <i class="mdi mdi-account-arrow-left text-secondary" style="font-size: 24px;"></i>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-sm-flex align-items-baseline">
                                <h5 class="font-weight-semibold">
                                    International Progress Report Summary - EROPE
                                </h5>

                                <span class="ms-auto">Updated Report</span>

                                <button class="btn btn-icons border-0 p-2">
                                    <i class="icon-refresh"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <!-- Total Candidates -->
                        <div class="col-md-2 col-6 mb-3">
                            <div class="card border-0 shadow-sm rounded-3">
                                <div class="card-body dashboard-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        
                                        <div>
                                            <h6 class="text-muted mb-1">Total Candidates</h6>
                                            <h3 class="mb-0">
                                                {{ $internationaleropecandidateCounts['total'] ?? 0 }}
                                            </h3>
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
                                <div class="card-body dashboard-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        
                                        <div>
                                            <h6 class="text-muted mb-1">Inprogress</h6>
                                            <h3 class="mb-0">
                                                {{ $internationaleropecandidateCounts['inprogress'] ?? 0 }}
                                            </h3>
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
                                <div class="card-body dashboard-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        
                                        <div>
                                            <h6 class="text-muted mb-1">Selected</h6>
                                            <h3 class="mb-0">
                                                {{ $internationaleropecandidateCounts['selected'] ?? 0 }}
                                            </h3>
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
                                <div class="card-body dashboard-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        
                                        <div>
                                            <h6 class="text-muted mb-1">Waiting List</h6>
                                            <h3 class="mb-0">
                                                {{ $internationaleropecandidateCounts['waitinglist'] ?? 0 }}
                                            </h3>
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
                                <div class="card-body dashboard-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        
                                        <div>
                                            <h6 class="text-muted mb-1">Rejected</h6>
                                            <h3 class="mb-0">
                                                {{ $internationaleropecandidateCounts['rejected'] ?? 0 }}
                                            </h3>
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
                                <div class="card-body dashboard-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        
                                        <div>
                                            <h6 class="text-muted mb-1">Back Out</h6>
                                            <h3 class="mb-0">
                                                {{ $internationaleropecandidateCounts['backout'] ?? 0 }}
                                            </h3>
                                        </div>

                                        <div class="bg-secondary bg-opacity-10 p-3 rounded-circle">
                                            <i class="mdi mdi-account-arrow-left text-secondary" style="font-size: 24px;"></i>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>


                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-sm-flex align-items-baseline">
                                <h5 class="font-weight-semibold">
                                    International Progress Report Summary - OTHERS
                                </h5>

                                <span class="ms-auto">Updated Report</span>

                                <button class="btn btn-icons border-0 p-2">
                                    <i class="icon-refresh"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <!-- Total Candidates -->
                        <div class="col-md-2 col-6 mb-3">
                            <div class="card border-0 shadow-sm rounded-3">
                                <div class="card-body dashboard-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        
                                        <div>
                                            <h6 class="text-muted mb-1">Total Candidates</h6>
                                            <h3 class="mb-0">
                                                {{ $internationalothercandidateCounts['total'] ?? 0 }}
                                            </h3>
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
                                <div class="card-body dashboard-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        
                                        <div>
                                            <h6 class="text-muted mb-1">Inprogress</h6>
                                            <h3 class="mb-0">
                                                {{ $internationalothercandidateCounts['inprogress'] ?? 0 }}
                                            </h3>
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
                                <div class="card-body dashboard-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        
                                        <div>
                                            <h6 class="text-muted mb-1">Selected</h6>
                                            <h3 class="mb-0">
                                                {{ $internationalothercandidateCounts['selected'] ?? 0 }}
                                            </h3>
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
                                <div class="card-body dashboard-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        
                                        <div>
                                            <h6 class="text-muted mb-1">Waiting List</h6>
                                            <h3 class="mb-0">
                                                {{ $internationalothercandidateCounts['waitinglist'] ?? 0 }}
                                            </h3>
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
                                <div class="card-body dashboard-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        
                                        <div>
                                            <h6 class="text-muted mb-1">Rejected</h6>
                                            <h3 class="mb-0">
                                                {{ $internationalothercandidateCounts['rejected'] ?? 0 }}
                                            </h3>
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
                                <div class="card-body dashboard-card">
                                    <div class="d-flex justify-content-between align-items-center">
                                        
                                        <div>
                                            <h6 class="text-muted mb-1">Back Out</h6>
                                            <h3 class="mb-0">
                                                {{ $internationalothercandidateCounts['backout'] ?? 0 }}
                                            </h3>
                                        </div>

                                        <div class="bg-secondary bg-opacity-10 p-3 rounded-circle">
                                            <i class="mdi mdi-account-arrow-left text-secondary" style="font-size: 24px;"></i>
                                        </div>

                                    </div>
                                </div>
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
                      <h4 class="card-title mb-sm-0"> Caniddate Details </h4>
                      <a href="{{ route('candidates.index') }}" class="text-dark ms-auto mb-3 mb-sm-0 btn btn-primary btn-sm"> View all</a>
                    </div>
                    <div class="table-responsive">
    <table id="candidates-table" class="table table-hover align-middle resume-table w-100">
        <thead>
            <tr style="background-color: #004085; color: #fff;">
                <th>User Type</th>
                <th>Sub Division</th>
                <th>Name/Experience</th>
                <th>Industry</th>
                <th>Designation</th> 
                <th>Qualification</th>
                <th>Mobile</th>
                <th>CV Code</th>
                <th>Resume</th>
                <th>Status</th>
                
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
            @foreach($candidates as $index => $candidate)
                <tr>
                    <td>
                        <span class="fw-medium">{{ ucfirst($candidate->user_type) ?? '' }}</span>
                    </td>
                    <td>
                        <span class="fw-medium">{{ ucfirst($candidate->sub_division) ?? '' }}</span>
                    </td>
                    <td>
                                <div class="d-flex align-items-center">
                                    
                                    <div>
                                        <strong>{{ $candidate->candidate_name }}</strong>
                                        <br>
                                        <small class="text-muted">Exp:{{ $candidate->total_exp ?? 'No' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info bg-opacity-10 text-info">
                                    {{ $industries[$candidate->industry] ?? $candidate->industry }}
                                </span>
                            </td>
                            <td>
                                <span class="fw-medium">{{ $candidate->designation }}</span>
                            </td>
                            
                            <td>{{ $candidate->qualfn }}</td>
                            <td>
                                 {{ $candidate->mobile }}
                            </td>
                            <td> {{ $candidate->code_no }} </td>
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
                     <a href="{{ route('demands.index') }}"><div class="popover bs-popover-bottom bs-popover-bottom-demo popover-success">
                        <div class="arrow"></div>
                        <h3 class="popover-header"> <center> Demand Register </center> </h3>
                        <div class="popover-body">
                         
                          <font size=4 color="blue"> 
                         <center> Demand Register for  
                          Domestic & International    </center</font>
                         
                        </div> 
                      </div>  </a>    


                        <a href="{{ route('domestic-invoice-reports.index') }}"><div class="popover bs-popover-bottom bs-popover-bottom-demo popover-info">
                        <div class="arrow"></div>
                        <h3 class="popover-header"> Invoice  / Quotations  </center>   </h3>
                        <div class="popover-body">
                        
                           <font size=4 color="blue"> 
                        
                                Proforma/Tax Invoice <br> 
                                Quotes Reports<br>
                                E-Receipts Reports 
                        </div>
                      </div></a>  


                     <a href="{{ route('admin.recruiters.all-daily') }}"> <div class="popover bs-popover-bottom bs-popover-bottom-demo popover-warning">
                        <div class="arrow"></div>
                        <h3 class="popover-header"> <center>  <font color="black"> Progress Report </center></h3>
                        <div class="popover-body">
                     
                           <font size=4 color="blue"> 

                                Daily Progress <br>
                                Monthly Progress <br>
                                Monthwise Progress <br>


                          </center</font>
                         
                        </div>
                      </div></a>

                     


                      <a href="{{ route('offer.index') }}"><div class="popover bs-popover-bottom bs-popover-bottom-demo popover-danger">
                        <div class="arrow"></div>
                        <h3 class="popover-header"> <center> Offer Letters / Pre Offers </h3>
                        <div class="popover-body">
                      
                         <font size=4 color="blue">  
    
                            Offer/Pre Offers  <br>
                            Travel Scheduler  <br>
                            Visa Activities  <br> 
                          </center</font> 
                       
                        </div>
                      </div></a>


                   



                      <a href="{{ route('offer.index') }}"><div class="popover bs-popover-bottom bs-popover-bottom-demo popover-primary">
                        <div class="arrow"></div>
                        <h3 class="popover-header"> <center> Documentation </center> </h3>
                        <div class="popover-body">
                         
                          <font size=4 color="blue">   
                                Interview Callers<br> 
                               Interview Letters<br> 
                                News Portals<br>  
                           </font>                         
                         
                        </div>
                      </div></a>

 

              


                
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
          </div>
            <div class="modal fade" id="newsModal" tabindex="-1" aria-labelledby="newsModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                      <div class="modal-header bg-primary text-white">
                          <h5 class="modal-title" id="newsModalLabel">News Details</h5>
                          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                            <div class="modal-body">
                                <div class="news-content">
                                    <!-- Image preview -->
                                    <div class="text-center mb-4" id="newsImageContainer" style="display: none;">
                                        <img id="newsModalImage" src="" alt="News Image" class="img-fluid rounded" style="max-height: 300px;">
                                        <div class="mt-2">
                                            <a id="newsDownloadLink" href="#" class="btn btn-sm btn-primary" download>
                                                <i class="mdi mdi-download"></i> Download Image
                                            </a>
                                        </div>
                                    </div>
                                    
                                    <!-- News details -->
                                    <div class="news-details">
                                        <h3 id="newsModalTitle" class="mb-3"></h3>
                                        <h5 id="newsModalSubtitle" class="text-muted mb-4"></h5>
                                        
                                        <div class="news-meta mb-4">
                                            <div class="d-flex align-items-center gap-3">
                                                <span class="badge bg-primary">
                                                    <i class="mdi mdi-calendar"></i> 
                                                    <span id="newsModalDate"></span>
                                                </span>
                                                <span class="text-muted">
                                                    <i class="mdi mdi-clock"></i> 
                                                    <span id="newsModalTime"></span>
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <!-- Description -->
                                        <div id="newsModalDescription" class="news-description-content"></div>
                                    </div>
                                </div>
                            </div>
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          <button type="button" class="btn btn-primary" onclick="window.location.href='{{ route('news.index') }}'">View All News</button>
                      </div>
                  </div>
              </div>
          </div>
@endsection
@section('js')
<!-- Bootstrap Bundle JS (required for modals)
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>-->

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
    // Debug: Check if news items exist
    console.log('News items found:', $('.news-item').length);
    
    // Handle news item click
    $('.news-item').on('click', function(e) {
        e.preventDefault();
        
        console.log('News item clicked');
        
        const newsData = {
            title: $(this).data('title') || 'No Title',
            subtitle: $(this).data('subtitle') || 'No Subtitle',
            date: $(this).data('date') || 'N/A',
            time: $(this).data('time') || 'N/A',
            description: $(this).data('description') || '',
            image: $(this).data('image') || null
        };
        
        console.log('News data:', newsData);

        // Populate modal with news data
        $('#newsModalLabel').text(newsData.title);
        $('#newsModalTitle').text(newsData.title);
        $('#newsModalSubtitle').text(newsData.subtitle);
        $('#newsModalDate').text(newsData.date);
        $('#newsModalTime').text(newsData.time);
        
        // Handle description
        const descriptionElement = $('#newsModalDescription');
        if (newsData.description && newsData.description.trim() !== '') {
            const formattedDescription = newsData.description.replace(/\n/g, '<br>');
            descriptionElement.html(formattedDescription);
        } else {
            descriptionElement.html('<p class="text-muted">No description available.</p>');
        }

        // Handle image
        if (newsData.image) {
            $('#newsModalImage').attr('src', newsData.image);
            $('#newsDownloadLink').attr('href', newsData.image);
            $('#newsImageContainer').show(); // show the image container
        } else {
            $('#newsImageContainer').hide(); // hide if no image
        }

        // Show modal using Bootstrap 5
        const newsModal = new bootstrap.Modal(document.getElementById('newsModal'));
        newsModal.show();
    });

    // Pause marquee on hover
    $('.marquee-container').hover(
        function() {
            $(this).find('.marquee-content').css('animation-play-state', 'paused');
        },
        function() {
            $(this).find('.marquee-content').css('animation-play-state', 'running');
        }
    );
    
    // DataTable initialization
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
                    columns: [0, 1, 2, 3, 4, 5, 6]
                },
                title: 'Candidate_List_' + new Date().toLocaleDateString('en-GB')
            },
            {
                extend: 'pdf',
                className: 'btn btn-sm',
                text: '<i class="mdi mdi-file-pdf"></i> PDF',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
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
                    columns: [0, 1, 2, 3, 4, 5, 6]
                },
                title: '<h3 class="text-center mb-4">Candidate List</h3>',
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
});
</script>
@endsection