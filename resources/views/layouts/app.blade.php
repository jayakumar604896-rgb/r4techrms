<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> RMS - Admin </title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/simple-line-icons/css/simple-line-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/flag-icon-css/css/flag-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/jvectormap/jquery-jvectormap.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/chartist/chartist.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <!-- endinject -->
    <!-- Layout styles -->
    <link rel="stylesheet" href="{{ asset('assets/css/vertical-light-layout/style.css' ) }}?ver={{ rand() }}">
    <!-- End layout styles -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/datatables.net-bs4/dataTables.bootstrap4.css')}}">
    
    <!-- Global Search Styles -->
    <style>
        .global-search-container {
            position: relative;
            margin-right: 15px;
        }
        .global-search-container .search-input {
            width: 250px;
            border-radius: 5px;
            padding: 8px 35px 8px 15px;
            border: 1px solid #e0e0e0;
            font-size: 13px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }
        .global-search-container .search-input:focus {
            width: 350px;
            border-color: #004085;
            box-shadow: 0 0 0 0.2rem rgba(0,64,133,0.1);
            background: #ffffff;
        }
        .global-search-container .search-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            cursor: pointer;
        }
        .global-search-container .search-icon:hover {
            color: #004085;
        }
        
        /* Search Results Dropdown */
        .search-results-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            max-height: 400px;
            overflow-y: auto;
            display: none;
            z-index: 9999;
            margin-top: 5px;
            border: 1px solid #e8edf3;
        }
        .search-results-dropdown.show {
            display: block;
        }
        .search-results-dropdown .search-result-item {
            padding: 10px 15px;
            border-bottom: 1px solid #f0f0f0;
            cursor: pointer;
            transition: background 0.2s ease;
        }
        .search-results-dropdown .search-result-item:hover {
            background: #f0f4ff;
        }
        .search-results-dropdown .search-result-item:last-child {
            border-bottom: none;
        }
        .search-results-dropdown .result-name {
            font-weight: 500;
            color: #333;
        }
        .search-results-dropdown .result-detail {
            font-size: 12px;
            color: #6c757d;
        }
        .search-results-dropdown .result-type {
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 12px;
            background: #e8edf3;
            color: #6c757d;
        }
        .search-results-dropdown .result-empty {
            padding: 20px;
            text-align: center;
            color: #6c757d;
        }
        .search-results-dropdown .result-empty i {
            font-size: 30px;
            display: block;
            margin-bottom: 10px;
            color: #c1c7cd;
        }
        .search-results-dropdown .search-loading {
            padding: 15px;
            text-align: center;
            color: #6c757d;
        }
        .search-results-dropdown .view-all-results {
            padding: 10px;
            text-align: center;
            background: #f8f9fa;
            border-top: 1px solid #e8edf3;
        }
        .search-results-dropdown .view-all-results a {
            color: #004085;
            font-weight: 500;
            text-decoration: none;
        }
        .search-results-dropdown .view-all-results a:hover {
            text-decoration: underline;
        }
        
        .search-shortcut {
            font-size: 10px;
            color: #6c757d;
            background: #e8edf3;
            padding: 2px 8px;
            border-radius: 4px;
            margin-left: 8px;
        }
        
        @media (max-width: 768px) {
            .global-search-container .search-input {
                width: 150px;
                font-size: 12px;
                padding: 6px 30px 6px 12px;
            }
            .global-search-container .search-input:focus {
                width: 200px;
            }
            .search-shortcut {
                display: none;
            }
        }
        
        /* Scrollbar styling for results */
        .search-results-dropdown::-webkit-scrollbar {
            width: 6px;
        }
        .search-results-dropdown::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        .search-results-dropdown::-webkit-scrollbar-thumb {
            background: #c1c7cd;
            border-radius: 10px;
        }
        .search-results-dropdown::-webkit-scrollbar-thumb:hover {
            background: #a8aeb4;
        }

		/* ── FIX DULL FORM INPUTS ── */
		.form-control, 
		.form-select, 
		select, 
		input[type="text"], 
		input[type="email"], 
		input[type="number"], 
		textarea {
		    color: #1a1a1a !important;           /* Dark, crisp text for typed input */
		    background-color: #ffffff !important; /* Pure white background */
		    border: 1px solid #b0b8c0 !important; /* Slightly darker, visible border */
		    font-weight: 500 !important;          /* Make the text slightly bolder */
		    opacity: 1 !important;                /* Ensure no global opacity is applied */
		}
		
		/* ── FIX DULL PLACEHOLDERS ── */
		.form-control::placeholder,
		.form-select::placeholder,
		input::placeholder,
		textarea::placeholder {
		    color: #6c757d !important;            /* Standard readable gray */
		    opacity: 1 !important;                /* Firefox lowers opacity by default */
		}
		
		/* ── ENHANCE FOCUS STATE ── */
		.form-control:focus, 
		.form-select:focus, 
		select:focus, 
		input:focus, 
		textarea:focus {
		    border-color: #6366f1 !important;     /* Purple/Blue focus border */
		    box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25) !important; /* Soft glow */
		    background-color: #ffffff !important;
		    color: #000000 !important;
		}
		
		/* ── FIX DISABLED/READONLY (If they are meant to be disabled, keep them dull but readable) ── */
		.form-control:disabled, 
		.form-control[readonly],
		.form-select:disabled {
		    background-color: #f8f9fa !important;
		    color: #6c757d !important;
		    border-color: #e0e0e0 !important;
		}
    </style>
  </head>
  <body>
    <div class="container-scroller">

      <!-- partial:partials/_navbar.html -->
      <nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
        <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
          <a class="navbar-brand brand-logo" href="{{ route('dashboard') }}">
            <img src="{{ asset('assets/images/rms_logo.png') }}" alt="logo" class="logo-dark" />
            <img src="{{ asset('assets/images/rms_logo.png') }}" alt="logo-light" class="logo-light">
          </a>
          <a class="navbar-brand brand-logo-mini" href="{{ route('dashboard') }}"><img src="{{ asset('assets/images/logo-mini.svg') }}" alt="logo" /></a>
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="icon-menu"></span>
          </button>
        </div>
        <div class="navbar-menu-wrapper d-flex align-items-center">
          <h5 class="mb-0 font-weight-medium d-none d-lg-flex">Welcome {{ Auth::user()->name }} !</h5>
            
          <ul class="navbar-nav navbar-nav-right">
            
            <!-- Global Search -->
            <li class="nav-item global-search-container">
                <input type="text" 
                       class="search-input" 
                       id="globalSearch" 
                       placeholder="Search candidates..." 
                       autocomplete="off">
                <i class="fas fa-search search-icon" onclick="performGlobalSearch()"></i>
                <span class="search-shortcut">Ctrl+K</span>
                
                <!-- Search Results Dropdown -->
                <div class="search-results-dropdown" id="searchResults">
                    <div class="search-loading" style="display: none;">
                        <i class="fas fa-spinner fa-spin"></i> Searching...
                    </div>
                    <div id="searchResultsContent">
                        <!-- Results will be populated here -->
                    </div>
                </div>
            </li>
           
            <li class="nav-item dropdown d-none d-xl-inline-flex user-dropdown">
              <a class="nav-link dropdown-toggle" id="UserDropdown" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                <img class="img-xs rounded-circle ms-2" src="{{ asset('assets/images/faces/admin.png') }}" alt="Profile image"> <span class="font-weight-normal"> {{ Auth::user()->name }} </span></a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                <div class="dropdown-header text-center">
                  <img class="img-md rounded-circle" src="{{ asset('assets/images/faces/admin.png') }}" alt="Profile image">
                  <p class="mb-1 mt-3">{{ Auth::user()->name }}</p>
                  <p class="font-weight-light text-muted mb-0"> Paswi Admin </p>
                </div>
                <a class="dropdown-item"><i class="dropdown-item-icon icon-user text-primary"></i> My Profile <span class="badge badge-pill badge-danger">1</span></a>
                <a class="dropdown-item"><i class="dropdown-item-icon icon-speech text-primary"></i> Messages</a>              
                <a class="dropdown-item" href="{{ route('logout') }}" ><i class="dropdown-item-icon icon-power text-primary"></i>Sign Out</a>
              </div>
            </li>
          </ul>
          
          <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="icon-menu"></span>
          </button>
        </div>
        
      </nav>
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_sidebar.html -->
        <nav class="sidebar sidebar-offcanvas" id="sidebar">
          <ul class="nav">
            <li class="nav-item navbar-brand-mini-wrapper">
              <a class="nav-link navbar-brand brand-logo-mini" href="{{ route('dashboard') }}"><img src="{{ asset('assets/images/logo-mini.svg') }}" alt="logo" /></a>
            </li>
            <li class="nav-item nav-profile">
              <a href="#" class="nav-link">
                <div class="profile-image">
                  <img class="img-xs rounded-circle" src="{{ asset('assets/images/faces/face8.jpg') }}" alt="profile image">
                 <div class="dot-indicator bg-success"></div>
                </div>
                <div class="text-wrapper">
                  <p class="profile-name"> RMS - Admin </p>
                  <p class="designation text-capitalize">{{ Auth::user()->role }}</p>
                </div>
                <div class="icon-container">
                  <i class="icon-bubbles"></i>
                  <div class="dot-indicator bg-danger"></div>
                </div>
              </a>
            </li>
            
           @auth
          
                @if(Auth::user()->role === 'admin')
			  		<li class="nav-item">
	                    <a class="nav-link" href="{{ route('dashboard-menu') }}">
	                        <span class="menu-title">Main Menu</span>
	                        <i class="icon-menu menu-icon"></i>
	                    </a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard') }}">
                        <span class="menu-title">Dashboard</span>
                        <i class="icon-screen-desktop menu-icon"></i>
                    </a>
                    </li>

					 <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#tables" aria-expanded="false" aria-controls="tables">
                <span class="nav-link"> <u><font color=#1BF54E> RESUME MANAGEMENT</font> </u></span>
                <i class="icon-grid menu-icon"></i>
              </a>
              <div class="collapse" id="tables">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link" href="{{route('candidates.index', ['source' => 'database'])}}"> Data Base CVs </a></li> 
                   <li class="nav-item"> <a class="nav-link" href="{{route('candidates.index', ['source' => 'website'])}}"> Website CVs </a></li> 
                   <li class="nav-item"> <a class="nav-link" href="{{route('candidates.index', ['source' => 'other'])}}"> Other CVs </a></li> 
					
                    </ul>
              </div>
            </li>


			  
			  



			  
                     <!-- <li class="nav-item nav-category"><span class="nav-link"> <u> 1. Enrollment </u></span></li> -->
                    <li class="nav-item">
                    <a class="nav-link" href="{{ route('recruiters') }}">
                        <span class="menu-title"> Employees </span>
                        <i class="icon-user menu-icon"></i>
                    </a>
                    </li>
                  @endif
				<li class="nav-item">
	                <a class="nav-link" href="{{ route('clients.index') }}">
	                    <span class="menu-title"> Client Details </span>
	                    <i class="icon-briefcase menu-icon"></i>
	                </a>
	            </li>
				<li class="nav-item">
	                <a class="nav-link" href="{{ route('agents.index') }}">
	                    <span class="menu-title"> Business Agents </span>
	                    <i class="icon-briefcase menu-icon"></i>
	                </a>
	            </li>
				<li class="nav-item">
	                <a class="nav-link" href="{{ route('agents.index') }}">
	                    <span class="menu-title"> Sub Agents </span>
	                    <i class="icon-briefcase menu-icon"></i>
	                </a>
	            </li>
				<li class="nav-item">
		                <a class="nav-link" href="{{ route('service-provider-details.index') }}">
		                    <span class="menu-title"> Service Providers </span>
		                    <i class="icon-briefcase menu-icon"></i>
		                </a>
		            </li>
                     <!-- <li class="nav-item nav-category"><span class="nav-link"> <u> 2. Marketting </u></span></li> -->
                   
                     <li class="nav-item">
                        <a class="nav-link" href="{{ route('sendtoothers.index') }}">
                            <span class="menu-title"> Marketing </span>
                            <i class="icon-briefcase menu-icon"></i>
                        </a>
                    </li>

                    

          <!-- <li class="nav-item nav-category"><span class="nav-link"> <u> 3. Demands Register </u></span></li> -->
              <li class="nav-item {{ Route::is('demands.*') ? 'active' : '' }}">
             <a class="nav-link" href="{{ route('demands.index') }}">
                 <span class="menu-title"> Demand Registers </span>
                 <i class="icon-layers menu-icon"></i>
             </a>
           </li>

          <!-- <li class="nav-item nav-category"><span class="nav-link"> <u> 4. Candidates </u></span></li> -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('candidates.index') }}">
                    <span class="menu-title"> Candidates </span>
                    <i class="icon-briefcase menu-icon"></i>
                </a>
            </li>
            
            
            
            
            
@if(Auth::user()->role === 'admin')

 <!-- <li class="nav-item nav-category"><span class="nav-link"> <u> 5. Mail Managements </u></span></li> -->
                   
                     <li class="nav-item">
                        <a class="nav-link" href="{{ route('candidates.index') }}">
                            <span class="menu-title"> Mail Send to Clients </span>
                            <i class="icon-briefcase menu-icon"></i>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('sendtocandidates.index') }}">
                            <span class="menu-title"> Mail Send to Candidates </span>
                            <i class="icon-briefcase menu-icon"></i>
                        </a>
                    </li>
                    
                   <!-- <li class="nav-item">
                        <a class="nav-link" href="{{ route('sendtoothers.index') }}">
                            <span class="menu-title"> Mail Send to Others </span>
                            <i class="icon-briefcase menu-icon"></i>
                        </a>
                    </li>-->




 <!-- <li class="nav-item nav-category"><span class="nav-link"> <u> 6.Invoice/Quote </u></span></li> -->
  <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#layouts" aria-expanded="false" aria-controls="layouts">
                <span class="menu-title"> Invoice/Quote  </span>
                <i class="menu-icon mdi mdi-page-layout-body"></i>
              </a>
              <div class="collapse" id="layouts">
                <ul class="nav flex-column sub-menu">

                  <li class="nav-item">
                    <a href="/domestic-invoice-reports" class="nav-link"> Proforma - Invoices  </a>
                  </li>

                   <li class="nav-item">
                    <a href="/tax-invoice-reports" class="nav-link"> Tax-Invoices  </a>
                  </li>

                  <li class="nav-item">
                    <a href="/tax-international-invoice-reports" class="nav-link"> International Tax-Invoices  </a>
                  </li>
 
                  <li class="nav-item">
                    <a href="/receipts" class="nav-link"> E-Receipts </a>
                  </li>

                   <!--<li class="nav-item">
                    <a href="#" class="nav-link"> Business Agreements/MoU </a>
                  </li>-->
                                   
                </ul>
              </div>
  </li>
@endif
 
 <!-- <li class="nav-item nav-category"><span class="nav-link"> <u> 7.Progress Reports </u></span></li> -->

 <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#tables" aria-expanded="false" aria-controls="tables">
                <span class="nav-link"> <u> Progress Reports </u></span>
                <i class="icon-grid menu-icon"></i>
              </a>
              <div class="collapse" id="tables">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link" href="{{route('admin.recruiters.all-daily')}}"> Daily Progress </a></li> 
                   <li class="nav-item"> <a class="nav-link" href="{{route('recruiter.monthly')}}"> Monthly Progress </a></li> 
                   <li class="nav-item"> <a class="nav-link" href="{{route('recruiter.monthwise')}}"> Monthwise Progress </a></li> 
                    </ul>
              </div>
            </li>
@if(Auth::user()->role === 'admin')


 <!-- <li class="nav-item nav-category"><span class="nav-link"> 8. Documentation </span></li> -->
    <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#maps" aria-expanded="false" aria-controls="maps">
                <span class="menu-title">Documentation</span>
                <i class="icon-location-pin menu-icon"></i>
              </a>
              <div class="collapse" id="maps">
                <ul class="nav flex-column sub-menu">
                  <li class="nav-item"> <a class="nav-link" href="{{route('candidate-files.index')}}">Candidate File Upload </a> </li>
                  <li class="nav-item"> <a class="nav-link" href="{{route('offer.index')}}">Offer Letters / Pre Offers </a> </li>
                  <li class="nav-item"> <a class="nav-link" href="#">Travel Scheduler  </a> </li>
                  <li class="nav-item"> <a class="nav-link" href="#"> Visa Activities  </a> </li>
                  </ul>
              </div>
            </li>

 <!-- <li class="nav-item {{ Route::is('offer.*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('offer.index') }}">
        <span class="menu-title"> Interview Callers/Letters </span>
        <i class="icon-layers menu-icon"></i>
    </a>
</li>
     
     <li class="nav-item {{ Route::is('offer.*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('offer.index') }}">
        <span class="menu-title"> News Portals </span>
        <i class="icon-layers menu-icon"></i>
    </a>
</li>-->

 
  <!-- <li class="nav-item nav-category"><span class="nav-link">9. Templates/Format</span></li> -->
            <li class="nav-item">
              <a class="nav-link" data-bs-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
                <span class="menu-title">Company Documents</span>
                <i class="icon-disc menu-icon"></i>
              </a>
              <div class="collapse" id="auth">
                <ul class="nav flex-column sub-menu"> 
                   <li class="nav-item"> <a class="nav-link" href="{{route('newbrochures.index')}}"> Company Brochure </a></li>
                    </ul>
              </div>
            </li>

              <!-- <li class="nav-item nav-category"><span class="nav-link">10. News</span></li> -->
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('news.index') }}">
                      <span class="menu-title"> News </span>
                      <i class="icon-briefcase menu-icon"></i>
                  </a>
                </li>
              </li>
              @if(Auth::user()->role === 'admin')
              <!-- <li class="nav-item nav-category"><span class="nav-link">11. Company Setting</span></li> -->
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('company-settings.index') }}">
                      <span class="menu-title"> Image Upload </span>
                      <i class="icon-briefcase menu-icon"></i>
                  </a>
                </li>
              </li>
              @endif
 
 


                @endif
            @endauth

          </ul>
        </nav>
         
        <div class="main-panel">
          
         @yield('content')
          <!-- content-wrapper ends -->
          <!-- partial:partials/_footer.html -->
          <footer class="footer">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
              <span class="text-muted text-center text-sm-left d-block d-sm-inline-block"> Copyright © 2025  All rights reserved.  </span>
              
            </div>
          </footer>
          <!-- partial -->
        </div>
        <!-- main-panel ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
        <script src="{{ asset('assets/vendors/datatables.net/jquery.dataTables.js')}}"></script>
    <script src="{{ asset('assets/vendors/datatables.net-bs4/dataTables.bootstrap4.js')}}"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <script src="{{ asset('assets/vendors/chart.js/chart.umd.js')}}"></script>
    <script src="{{ asset('assets/vendors/jvectormap/jquery-jvectormap.min.js')}}"></script>
    <script src="{{ asset('assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
    <script src="{{ asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{ asset('assets/vendors/moment/moment.min.js')}}"></script>
    <script src="{{ asset('assets/vendors/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{ asset('assets/vendors/chartist/chartist.min.js')}}"></script>
    <script src="{{ asset('assets/vendors/progressbar.js/progressbar.min.js')}}"></script>
    <script src="{{ asset('assets/js/jquery.cookie.js')}}"></script>
    <!-- End plugin js for this page -->
    <!-- inject:js -->

    <script src="{{ asset('assets/js/off-canvas.js')}}"></script>
    <script src="{{ asset('assets/js/hoverable-collapse.js')}}"></script>
    <script src="{{ asset('assets/js/misc.js')}}"></script>
    <script src="{{ asset('assets/js/settings.js')}}"></script>
    <script src="{{ asset('assets/js/todolist.js')}}"></script>
    <!-- endinject -->
    <!-- Custom js for this page -->
    <script src="{{ asset('assets/js/dashboard.js')}}?ver={{ rand() }}"></script>
    <script src="{{ asset('assets/js/data-table.js')}}?ver={{ rand() }}"></script>
    
    <!-- Global Search JavaScript -->
    <script>
        $(document).ready(function() {
            const searchInput = $('#globalSearch');
            const searchResults = $('#searchResults');
            const resultsContent = $('#searchResultsContent');
            const loadingIndicator = $('.search-loading');
            
            let searchTimeout = null;
            
            // Function to perform search
            window.performGlobalSearch = function() {
                const query = searchInput.val().trim();
                
                if (query.length < 2) {
                    searchResults.removeClass('show');
                    return;
                }
                
                // Show loading
                loadingIndicator.show();
                resultsContent.html('');
                searchResults.addClass('show');
                
                // Clear previous timeout
                if (searchTimeout) {
                    clearTimeout(searchTimeout);
                }
                
                // Debounce search
                searchTimeout = setTimeout(function() {
                    $.ajax({
                        url: '{{ route("global.search") }}',
                        type: 'GET',
                        data: {
                            query: query
                        },
                        success: function(response) {
                            loadingIndicator.hide();
                            displayResults(response);
                        },
                        error: function() {
                            loadingIndicator.hide();
                            resultsContent.html(`
                                <div class="result-empty">
                                    <i class="fas fa-exclamation-circle"></i>
                                    Error searching. Please try again.
                                </div>
                            `);
                        }
                    });
                }, 300);
            };
            
            // Function to display results
            function displayResults(data) {
                let html = '';
                
                if (data.candidates && data.candidates.length > 0) {
                    data.candidates.forEach(function(candidate) {
                        html += `
                            <div class="search-result-item" onclick="window.location.href='${candidate.url}'">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="result-name">
                                            <i class="fas fa-user-circle" style="color: #004085;"></i>
                                            ${candidate.candidate_name}
                                        </div>
                                        <div class="result-detail">
                                            <i class="fas fa-envelope"></i> ${candidate.email || 'N/A'} &nbsp;|&nbsp;
                                            <i class="fas fa-phone"></i> ${candidate.mobile || 'N/A'}
                                            ${candidate.designation ? '&nbsp;|&nbsp;<i class="fas fa-briefcase"></i> '+candidate.designation : ''}
                                        </div>
                                    </div>
                                    <span class="result-type">Candidate</span>
                                </div>
                            </div>
                        `;
                    });
                    
                    // Add View All button if there are more results
                    if (data.total > data.candidates.length) {
                        html += `
                            <div class="view-all-results">
                                <a href="{{ route('candidates.index') }}?search=${encodeURIComponent(searchInput.val().trim())}">
                                    View all ${data.total} results <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        `;
                    }
                } else {
                    html = `
                        <div class="result-empty">
                            <i class="fas fa-search"></i>
                            No candidates found matching "${searchInput.val().trim()}"
                            <br>
                            <small class="text-muted">Try searching by name, email, or mobile</small>
                        </div>
                    `;
                }
                
                resultsContent.html(html);
            }
            
            // Input event
            searchInput.on('input', function() {
                window.performGlobalSearch();
            });
            
            // Focus event - show results if there's a query
            searchInput.on('focus', function() {
                if ($(this).val().trim().length >= 2) {
                    window.performGlobalSearch();
                }
            });
            
            // Click outside to close
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.global-search-container').length) {
                    searchResults.removeClass('show');
                }
            });
            
            // Keyboard shortcut: Ctrl+K
            $(document).on('keydown', function(e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                    e.preventDefault();
                    searchInput.focus();
                }
                // Escape key to close results
                if (e.key === 'Escape') {
                    searchResults.removeClass('show');
                    searchInput.blur();
                }
            });
            
            // Prevent form submission on enter
            searchInput.on('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const query = $(this).val().trim();
                    if (query.length >= 2) {
                        window.location.href = '{{ route("candidates.index") }}?search=' + encodeURIComponent(query);
                    }
                }
            });
        });
    </script>
    
    <script>
        console.log('Hello');
        let idleTimer = null;
        let idleState = false;
        const idleLimit = 15 * 60 * 1000; // 15 minutes in ms

        function resetIdleTimer() {
        console.log('reset timer');
            if (idleState) {
                setUserStatus('active');
                idleState = false;
            }
            clearTimeout(idleTimer);
            idleTimer = setTimeout(() => {
                idleState = true;
                setUserStatus('idle');
            }, idleLimit);
        }

        // Track user interaction
        ['mousemove', 'keydown', 'scroll', 'click', 'touchstart'].forEach(event =>
            window.addEventListener(event, resetIdleTimer)
        );

        // Send "active" heartbeat every 2 minutes
        setInterval(() => {
            if (!idleState) {
            console.log('Heart beat');
                fetch('/user/heartbeat', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
            }
        }, 120000); // 2 minutes

        function setUserStatus(status) {
        console.log('set');
            fetch(`/user/status/${status}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
        }

        resetIdleTimer();
    </script>
    @yield('js')
    
</body>

</html>