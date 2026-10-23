@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<!-- Content -->
<div class="container-xxl flex-grow-1 container-p-y">

  <div class="row">
    <div class="col-xxl-6 mb-6 order-0">
      <div class="card">
        <div class="d-flex align-items-start row">
          <div class="col-sm-8">
            <div class="card-body">
              <h5 class="card-title text-primary mb-3">
                Congratulations {{ Auth::user()->name }}! 🎉
              </h5>
              <p class="mb-6">
                You have done 72% more sales today.<br>
                Check your new badge in your profile.
              </p>

              <!-- ✅ Stats row (fixed layout) -->
              <div class="d-flex justify-content-between align-items-center flex-nowrap gap-3 w-100">
                <!-- Hours Spent -->
                <div class="d-flex align-items-center gap-2 flex-shrink-1" style="min-width:100px;">
                  <div class="avatar avatar-lg">
                    <div class="avatar-initial bg-label-primary rounded">
                      <!-- SVG icon -->
                      <div class="text-primary">
                        <i class="bx bx-laptop" style="font-size: 1.75rem;"></i>
                      </div>
                    </div>
                  </div>
                  <div>
                    <p class="mb-0 fw-medium">Today's Data</p>
                    <h4 class="text-primary mb-0">34h</h4>
                  </div>
                </div>

                <!-- Test Results -->
                <div class="d-flex align-items-center gap-2 flex-shrink-1" style="min-width:100px;">
                  <div class="avatar avatar-lg">
                    <div class="avatar-initial bg-label-info rounded">
                      <!-- SVG icon -->
                      <div class="text-info">
                        <i class="bx bx-bulb" style="font-size: 1.75rem;"></i>
                      </div>
                    </div>
                  </div>
                  <div>
                    <p class="mb-0 fw-medium">Monthly Data</p>
                    <h4 class="text-info mb-0">82%</h4>
                  </div>
                </div>

                <!-- Course Completed -->
                <div class="d-flex align-items-center gap-2 flex-shrink-1" style="min-width:100px;">
                  <div class="avatar avatar-lg">
                    <div class="avatar-initial bg-label-warning rounded">
                      <!-- SVG icon -->
                      <div class="text-warning">
                        <i class="bx bx-check-circle" style="font-size: 1.75rem;"></i>
                      </div>
                    </div>
                  </div>
                  <div>
                    <p class="mb-0 fw-medium">Data</p>
                    <h4 class="text-warning mb-0">14</h4>
                  </div>
                </div>
              </div>

              <!-- /Stats row -->
            </div>
            
          </div>

          <!-- Image on the right -->
          <div class="col-sm-4 text-center text-sm-left">
            <div class="card-body pb-0 px-0 px-md-6">
              <img src="{{ asset('assets/img/illustrations/man-with-laptop.png') }}" height="175" class="scaleX-n1-rtl" alt="View Badge User">
            </div>
          </div>
        </div>
      </div>

      
      <div class="card text-center mt-5">
        <div class="card-header nav-align-top">
          <ul class="nav nav-pills flex-wrap row-gap-2" role="tablist">
            <li class="nav-item">
              <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-browser" aria-controls="navs-pills-browser" aria-selected="true">Admin</button>
            </li>
            <li class="nav-item">
              <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-os" aria-controls="navs-pills-os" aria-selected="false">International</button>
            </li>
            <li class="nav-item">
              <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-country" aria-controls="navs-pills-country" aria-selected="false">Domestic</button>
            </li>
          </ul>
        </div>
        <div class="tab-content pt-0 pb-4">
          <div class="tab-pane fade show active" id="navs-pills-browser" role="tabpanel">
            <div class="table-responsive text-start text-nowrap">
              <table class="table table-borderless">
                <thead>
                  <tr>
                    <th>Emp. No</th>
                    <th> Name </th>
                    <th class="w-50">Status</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($adminUsers as $user)
                  <tr>
                    <td>1</td>
                    <td>
                      <div class="d-flex align-items-center">
                        <img src="{{ asset('assets/img/illustrations/chrome.png') }}" alt="Chrome" height="24" class="me-3">
                        <span class="text-heading">{{ $user->name }}</span>
                      </div>
                    </td>
                    <td>
                     <div class="d-flex justify-content-between align-items-center gap-4">
                        <div class="progress w-100" style="height:10px;">
                            <div class="progress-bar 
                                @if($user->user_status == 'active') bg-success
                                @elseif($user->user_status == 'idle') bg-warning
                                @elseif($user->user_status == 'offline') bg-danger
                                @else bg-secondary
                                @endif" 
                                role="progressbar" 
                                style="width: 100%" 
                                aria-valuenow="100" 
                                aria-valuemin="0" 
                                aria-valuemax="100">
                            </div>
                        </div>
                        <small class="fw-medium text-capitalize">{{ $user->user_status }}</small>
                    </div>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <div class="tab-pane fade" id="navs-pills-os" role="tabpanel">
            <div class="table-responsive text-start text-nowrap">
              <table class="table table-borderless">
                <thead>
                  <tr>
                      <th>Emp. No</th>
                    <th> Name </th>
                    <th class="w-50">Status</th>
                  </tr>
                </thead>
                 <tbody>
                  @foreach($interUsers as $user)
                  <tr>
                    <td>1</td>
                    <td>
                      <div class="d-flex align-items-center">
                        <img src="{{ asset('assets/img/illustrations/chrome.png') }}" alt="Chrome" height="24" class="me-3">
                        <span class="text-heading">{{ $user->name }}</span>
                      </div>
                    </td>
                    <td>
                     <div class="d-flex justify-content-between align-items-center gap-4">
                        <div class="progress w-100" style="height:10px;">
                            <div class="progress-bar 
                                @if($user->user_status == 'active') bg-success
                                @elseif($user->user_status == 'idle') bg-warning
                                @elseif($user->user_status == 'offline') bg-danger
                                @else bg-secondary
                                @endif" 
                                role="progressbar" 
                                style="width: 100%" 
                                aria-valuenow="100" 
                                aria-valuemin="0" 
                                aria-valuemax="100">
                            </div>
                        </div>
                        <small class="fw-medium text-capitalize">{{ $user->user_status }}</small>
                    </div>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <div class="tab-pane fade" id="navs-pills-country" role="tabpanel">
            <div class="table-responsive text-start text-nowrap">
              <table class="table table-borderless">
                <thead>
                  <tr>
                      <th>Emp. No</th>
                    <th> Name </th>
                    <th class="w-50">Status</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($domesticUsers as $user)
                  <tr>
                    <td>1</td>
                    <td>
                      <div class="d-flex align-items-center">
                        <img src="{{ asset('assets/img/illustrations/chrome.png') }}" alt="Chrome" height="24" class="me-3">
                        <span class="text-heading">{{ $user->name }}</span>
                      </div>
                    </td>
                    <td>
                     <div class="d-flex justify-content-between align-items-center gap-4">
                        <div class="progress w-100" style="height:10px;">
                            <div class="progress-bar 
                                @if($user->user_status == 'active') bg-success
                                @elseif($user->user_status == 'idle') bg-warning
                                @elseif($user->user_status == 'offline') bg-danger
                                @else bg-secondary
                                @endif" 
                                role="progressbar" 
                                style="width: 100%" 
                                aria-valuenow="100" 
                                aria-valuemin="0" 
                                aria-valuemax="100">
                            </div>
                        </div>
                        <small class="fw-medium text-capitalize">{{ $user->user_status }}</small>
                    </div>
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


    <div class="col-xxl-6 col-lg-12 col-md-4 order-1">
          <div class="row">
        <!-- Donut Chart -->
   
      <div class="card h-100">
        <div class="card-header d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-0">Expense Ratio</h5>
                        <p class="card-subtitle my-0">Spending on various categories</p>
                    </div>
                    <div class="dropdown d-none d-sm-flex">
                        <button type="button" class="btn dropdown-toggle px-0" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="icon-base bx bx-calendar"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                        <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Today</a></li>
                        <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Yesterday</a></li>
                        <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Last 7 Days</a></li>
                        <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Last 30 Days</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Current Month</a></li>
                        <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Last Month</a></li>
                        </ul>
                    </div>
        </div>
        <div class="card-body">
          <div id="donutChart"></div>
           <div class="row g-6">
                <!-- Card Border Shadow -->
                <div class="col-lg-3 col-sm-6">
                <div class="card card-border-shadow-primary h-100">
                    <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                        <span class="avatar-initial rounded bg-label-primary"><i class="icon-base bx bxs-truck icon-lg"></i></span>
                        </div>
                        <h4 class="mb-0">42</h4>
                    </div>
                    <p class="mb-2">On route vehicles</p>
                    <p class="mb-0">
                        <span class="text-heading fw-medium me-2">+18.2%</span>
                        <span class="text-body-secondary">than last week</span>
                    </p>
                    </div>
                </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                <div class="card card-border-shadow-warning h-100">
                    <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                        <span class="avatar-initial rounded bg-label-warning"><i class="icon-base bx bx-error icon-lg"></i></span>
                        </div>
                        <h4 class="mb-0">8</h4>
                    </div>
                    <p class="mb-2">Vehicles with errors</p>
                    <p class="mb-0">
                        <span class="text-heading fw-medium me-2">-8.7%</span>
                        <span class="text-body-secondary">than last week</span>
                    </p>
                    </div>
                </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                <div class="card card-border-shadow-danger h-100">
                    <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                        <span class="avatar-initial rounded bg-label-danger"><i class="icon-base bx bx-git-repo-forked icon-lg"></i></span>
                        </div>
                        <h4 class="mb-0">27</h4>
                    </div>
                    <p class="mb-2">Deviated from route</p>
                    <p class="mb-0">
                        <span class="text-heading fw-medium me-2">+4.3%</span>
                        <span class="text-body-secondary">than last week</span>
                    </p>
                    </div>
                </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                <div class="card card-border-shadow-info h-100">
                    <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                        <span class="avatar-initial rounded bg-label-info"><i class="icon-base bx bx-time-five icon-lg"></i></span>
                        </div>
                        <h4 class="mb-0">13</h4>
                    </div>
                    <p class="mb-2">Late vehicles</p>
                    <p class="mb-0">
                        <span class="text-heading fw-medium me-2">-2.5%</span>
                        <span class="text-body-secondary">than last week</span>
                    </p>
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

  <div class="col-md-6">
  <!-- Product List Widget -->
  <div class="card mb-6">
    <div class="card-widget-separator-wrapper">
      <div class="card-body card-widget-separator">
        <div class="row gy-4 gy-sm-1">
          <div class="col-sm-6 col-lg-3">
            <div class="d-flex justify-content-between align-items-start card-widget-1 border-end pb-4 pb-sm-0">
              <div>
                <p class="mb-1">In-store Sales</p>
                <h4 class="mb-1">$5,345.43</h4>
                <p class="mb-0"><span class="me-2">5k orders</span><span class="badge bg-label-success">+5.7%</span></p>
              </div>
              <span class="avatar me-sm-6">
                <span class="avatar-initial rounded w-px-44 h-px-44">
                  <i class="icon-base bx bx-store-alt icon-lg text-heading"></i>
                </span>
              </span>
            </div>
            <hr class="d-none d-sm-block d-lg-none me-6">
          </div>
          <div class="col-sm-6 col-lg-3">
            <div class="d-flex justify-content-between align-items-start card-widget-2 border-end pb-4 pb-sm-0">
              <div>
                <p class="mb-1">Website Sales</p>
                <h4 class="mb-1">$674,347.12</h4>
                <p class="mb-0"><span class="me-2">21k orders</span><span class="badge bg-label-success">+12.4%</span></p>
              </div>
              <span class="avatar p-2 me-lg-6">
                <span class="avatar-initial rounded w-px-44 h-px-44">
                  <i class="icon-base bx bx-laptop icon-lg text-heading"></i>
                </span>
              </span>
            </div>
            <hr class="d-none d-sm-block d-lg-none">
          </div>
          <div class="col-sm-6 col-lg-3">
            <div class="d-flex justify-content-between align-items-start border-end pb-4 pb-sm-0 card-widget-3">
              <div>
                <p class="mb-1">Discount</p>
                <h4 class="mb-1">$14,235.12</h4>
                <p class="mb-0">6k orders</p>
              </div>
              <span class="avatar p-2 me-sm-6">
                <span class="avatar-initial rounded w-px-44 h-px-44">
                  <i class="icon-base bx bx-gift icon-lg text-heading"></i>
                </span>
              </span>
            </div>
          </div>
          <div class="col-sm-6 col-lg-3">
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <p class="mb-1">Affiliate</p>
                <h4 class="mb-1">$8,345.23</h4>
                <p class="mb-0"><span class="me-2">150 orders</span><span class="badge bg-label-danger">-3.5%</span></p>
              </div>
              <span class="avatar p-2">
                <span class="avatar-initial rounded w-px-44 h-px-44">
                  <i class="icon-base bx bx-wallet icon-lg text-heading"></i>
                </span>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Product List Table -->
  <div class="card">
    <div class="card-header border-bottom">
      <h5 class="card-title">Filter</h5>
      <div class="d-flex justify-content-between align-items-center row pt-4 gap-6 gap-md-0 g-md-6">
        <div class="col-md-4 product_status"></div>
        <div class="col-md-4 product_category"></div>
        <div class="col-md-4 product_stock"></div>
      </div>
    </div>
    <div class="card-datatable">
      <table class="datatables-products table">
        <thead class="border-top">
          <tr>
            <th></th>
            <th></th>
            <th>product</th>
            <th>category</th>
            <th>stock</th>
            <th>sku</th>
            <th>price</th>
            <th>qty</th>
            <th>status</th>
            <th>actions</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
  </div>
  <div class="col-12 col-xl-6">
      <div class="card h-100">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="card-title m-0 me-2">Topic you are interested in</h5>
          <div class="dropdown">
            <button class="btn p-0" type="button" id="topic" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="icon-base bx bx-dots-vertical-rounded icon-lg text-body-secondary"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="topic">
              <a class="dropdown-item" href="javascript:void(0);">Highest Views</a>
              <a class="dropdown-item" href="javascript:void(0);">See All</a>
            </div>
          </div>
        </div>
        <div class="card-body row g-3">
          <div class="col-md-8">
            <div id="horizontalBarChart"></div>
          </div>
          <div class="col-md-4 d-flex justify-content-around align-items-center">
            <div>
              <div class="d-flex align-items-baseline">
                <span class="text-primary me-2"><i class="icon-base bx bxs-circle icon-12px"></i></span>
                <div>
                  <p class="mb-0">UI Design</p>
                  <h5>35%</h5>
                </div>
              </div>
              <div class="d-flex align-items-baseline my-12">
                <span class="text-success me-2"><i class="icon-base bx bxs-circle icon-12px"></i></span>
                <div>
                  <p class="mb-0">Music</p>
                  <h5>14%</h5>
                </div>
              </div>
              <div class="d-flex align-items-baseline">
                <span class="text-danger me-2"><i class="icon-base bx bxs-circle icon-12px"></i></span>
                <div>
                  <p class="mb-0">React</p>
                  <h5>10%</h5>
                </div>
              </div>
            </div>

            <div>
              <div class="d-flex align-items-baseline">
                <span class="text-info me-2"><i class="icon-base bx bxs-circle icon-12px"></i></span>
                <div>
                  <p class="mb-0">UX Design</p>
                  <h5>20%</h5>
                </div>
              </div>
              <div class="d-flex align-items-baseline my-12">
                <span class="text-secondary me-2"><i class="icon-base bx bxs-circle icon-12px"></i></span>
                <div>
                  <p class="mb-0">Animation</p>
                  <h5>12%</h5>
                </div>
              </div>
              <div class="d-flex align-items-baseline">
                <span class="text-warning me-2"><i class="icon-base bx bxs-circle icon-12px"></i></span>
                <div>
                  <p class="mb-0">SEO</p>
                  <h5>9%</h5>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row mt-6">
     <!-- pill table -->
    <div class="col-md-5 mb-6 mb-lg-0">
      <div class="card text-center h-100">
        <div class="card-header nav-align-top">
          <ul class="nav nav-pills flex-wrap row-gap-2" role="tablist">
            <li class="nav-item">
              <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-browser" aria-controls="navs-pills-browser" aria-selected="true">Admin</button>
            </li>
            <li class="nav-item">
              <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-os" aria-controls="navs-pills-os" aria-selected="false">International</button>
            </li>
            <li class="nav-item">
              <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-country" aria-controls="navs-pills-country" aria-selected="false">Domestic</button>
            </li>
          </ul>
        </div>
        <div class="tab-content pt-0 pb-4">
          <div class="tab-pane fade show active" id="navs-pills-browser" role="tabpanel">
            <div class="table-responsive text-start text-nowrap">
              <table class="table table-borderless">
                <thead>
                  <tr>
                    <th>Emp. No</th>
                    <th> Name </th>
                    <th class="w-50">Status</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($adminUsers as $user)
                  <tr>
                    <td>1</td>
                    <td>
                      <div class="d-flex align-items-center">
                        <img src="{{ asset('assets/img/illustrations/chrome.png') }}" alt="Chrome" height="24" class="me-3">
                        <span class="text-heading">{{ $user->name }}</span>
                      </div>
                    </td>
                    <td>
                     <div class="d-flex justify-content-between align-items-center gap-4">
                        <div class="progress w-100" style="height:10px;">
                            <div class="progress-bar 
                                @if($user->user_status == 'active') bg-success
                                @elseif($user->user_status == 'idle') bg-warning
                                @elseif($user->user_status == 'offline') bg-danger
                                @else bg-secondary
                                @endif" 
                                role="progressbar" 
                                style="width: 100%" 
                                aria-valuenow="100" 
                                aria-valuemin="0" 
                                aria-valuemax="100">
                            </div>
                        </div>
                        <small class="fw-medium text-capitalize">{{ $user->user_status }}</small>
                    </div>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <div class="tab-pane fade" id="navs-pills-os" role="tabpanel">
            <div class="table-responsive text-start text-nowrap">
              <table class="table table-borderless">
                <thead>
                  <tr>
                      <th>Emp. No</th>
                    <th> Name </th>
                    <th class="w-50">Status</th>
                  </tr>
                </thead>
                 <tbody>
                  @foreach($interUsers as $user)
                  <tr>
                    <td>1</td>
                    <td>
                      <div class="d-flex align-items-center">
                        <img src="{{ asset('assets/img/illustrations/chrome.png') }}" alt="Chrome" height="24" class="me-3">
                        <span class="text-heading">{{ $user->name }}</span>
                      </div>
                    </td>
                    <td>
                     <div class="d-flex justify-content-between align-items-center gap-4">
                        <div class="progress w-100" style="height:10px;">
                            <div class="progress-bar 
                                @if($user->user_status == 'active') bg-success
                                @elseif($user->user_status == 'idle') bg-warning
                                @elseif($user->user_status == 'offline') bg-danger
                                @else bg-secondary
                                @endif" 
                                role="progressbar" 
                                style="width: 100%" 
                                aria-valuenow="100" 
                                aria-valuemin="0" 
                                aria-valuemax="100">
                            </div>
                        </div>
                        <small class="fw-medium text-capitalize">{{ $user->user_status }}</small>
                    </div>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <div class="tab-pane fade" id="navs-pills-country" role="tabpanel">
            <div class="table-responsive text-start text-nowrap">
              <table class="table table-borderless">
                <thead>
                  <tr>
                      <th>Emp. No</th>
                    <th> Name </th>
                    <th class="w-50">Status</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($domesticUsers as $user)
                  <tr>
                    <td>1</td>
                    <td>
                      <div class="d-flex align-items-center">
                        <img src="{{ asset('assets/img/illustrations/chrome.png') }}" alt="Chrome" height="24" class="me-3">
                        <span class="text-heading">{{ $user->name }}</span>
                      </div>
                    </td>
                    <td>
                     <div class="d-flex justify-content-between align-items-center gap-4">
                        <div class="progress w-100" style="height:10px;">
                            <div class="progress-bar 
                                @if($user->user_status == 'active') bg-success
                                @elseif($user->user_status == 'idle') bg-warning
                                @elseif($user->user_status == 'offline') bg-danger
                                @else bg-secondary
                                @endif" 
                                role="progressbar" 
                                style="width: 100%" 
                                aria-valuenow="100" 
                                aria-valuemin="0" 
                                aria-valuemax="100">
                            </div>
                        </div>
                        <small class="fw-medium text-capitalize">{{ $user->user_status }}</small>
                    </div>
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
    <!--/ pill table -->  


    <!-- Activity Timeline -->
    <div class="col-md-12 col-lg-7">
        <!-- Donut Chart -->
   
      <div class="card h-100">
        <div class="card-header d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-0">Expense Ratio</h5>
                        <p class="card-subtitle my-0">Spending on various categories</p>
                    </div>
                    <div class="dropdown d-none d-sm-flex">
                        <button type="button" class="btn dropdown-toggle px-0" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="icon-base bx bx-calendar"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                        <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Today</a></li>
                        <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Yesterday</a></li>
                        <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Last 7 Days</a></li>
                        <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Last 30 Days</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Current Month</a></li>
                        <li><a href="javascript:void(0);" class="dropdown-item d-flex align-items-center">Last Month</a></li>
                        </ul>
                    </div>
        </div>
        <div class="card-body">
          <div id="donutChart"></div>
           <div class="row g-6">
                <!-- Card Border Shadow -->
                <div class="col-lg-3 col-sm-6">
                <div class="card card-border-shadow-primary h-100">
                    <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                        <span class="avatar-initial rounded bg-label-primary"><i class="icon-base bx bxs-truck icon-lg"></i></span>
                        </div>
                        <h4 class="mb-0">42</h4>
                    </div>
                    <p class="mb-2">On route vehicles</p>
                    <p class="mb-0">
                        <span class="text-heading fw-medium me-2">+18.2%</span>
                        <span class="text-body-secondary">than last week</span>
                    </p>
                    </div>
                </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                <div class="card card-border-shadow-warning h-100">
                    <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                        <span class="avatar-initial rounded bg-label-warning"><i class="icon-base bx bx-error icon-lg"></i></span>
                        </div>
                        <h4 class="mb-0">8</h4>
                    </div>
                    <p class="mb-2">Vehicles with errors</p>
                    <p class="mb-0">
                        <span class="text-heading fw-medium me-2">-8.7%</span>
                        <span class="text-body-secondary">than last week</span>
                    </p>
                    </div>
                </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                <div class="card card-border-shadow-danger h-100">
                    <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                        <span class="avatar-initial rounded bg-label-danger"><i class="icon-base bx bx-git-repo-forked icon-lg"></i></span>
                        </div>
                        <h4 class="mb-0">27</h4>
                    </div>
                    <p class="mb-2">Deviated from route</p>
                    <p class="mb-0">
                        <span class="text-heading fw-medium me-2">+4.3%</span>
                        <span class="text-body-secondary">than last week</span>
                    </p>
                    </div>
                </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                <div class="card card-border-shadow-info h-100">
                    <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                        <span class="avatar-initial rounded bg-label-info"><i class="icon-base bx bx-time-five icon-lg"></i></span>
                        </div>
                        <h4 class="mb-0">13</h4>
                    </div>
                    <p class="mb-2">Late vehicles</p>
                    <p class="mb-0">
                        <span class="text-heading fw-medium me-2">-2.5%</span>
                        <span class="text-body-secondary">than last week</span>
                    </p>
                    </div>
                </div>
            
                </div>
            </div>
        </div>
      </div>
    </div>
    <!--/ Activity Timeline -->

  </div>

</div>
<!-- / Content -->


@endsection