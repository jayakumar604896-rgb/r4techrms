@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
 <div class="content-wrapper">
            <div class="row">
        <div class="col-xl-4 grid-margin stretch-card">
                <div class="card quick-status-card">
                  <div class="card-body">
                    <h4 class="card-title"> My Summary Timeline </h4>
                    <div class="row">
                      <div class="col-md-6">
                        <div id="circle-progress-1"></div>
                      </div>
                      <div class="col-md-6">
                        <div id="circle-progress-2"></div>
                      </div>
                    </div>
                    <div class="row mt-5">
                      <div class="col-12">
                        <div class="wrapper py-4 d-flex border-bottom">
                          <span class="icon-holder">
                            <i class="icon-wallet"></i>
                          </span>
                          <div class="ms-3">
                            <p class="mb-1">My Balance</p>
                            <h6 class="mb-0">$5021.00</h6>
                          </div>
                          <div class="ms-auto"><i class="icon-arrow-down-circle"></i><span class="text-muted ms-2">2.87 %</span></div>
                        </div>
                        <div class="wrapper py-4 d-flex">
                          <span class="icon-holder">
                            <i class="icon-basket-loaded"></i>
                          </span>
                          <div class="ms-3">
                            <p class="mb-1">Sales Revenue</p>
                            <h6 class="mb-0"></h6>24,301.00</h6>
                          </div>
                          <div class="ms-auto"><i class="icon-arrow-down-circle"></i><span class="text-muted ms-2">2.87 %</span></div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
               <div class="col-xl-4 grid-margin stretch-card">
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
                          <span class="report-title">Selected</span>
                          <h4>123</h4>
                          <span class="report-count"> 2 Reports</span>
                        </div>
                        <div class="inner-card-icon bg-success">
                          <i class="icon-rocket"></i>
                        </div>
                      </div>
                      <div class="col-md-6 col-xl report-inner-card">
                        <div class="inner-card-text">
                          <span class="report-title">Onboarded </span>
                          <h4>58</h4>
                          <span class="report-count"> 3 Reports</span>
                        </div>
                        <div class="inner-card-icon bg-danger">
                          <i class="icon-briefcase"></i>
                        </div>
                      </div>
                      <div class="col-md-6 col-xl report-inner-card">
                        <div class="inner-card-text">
                          <span class="report-title">On Process</span>
                          <h4>50</h4>
                          <span class="report-count"> 5 Reports</span>
                        </div>
                        <div class="inner-card-icon bg-warning">
                          <i class="icon-globe-alt"></i>
                        </div>
                      </div>
                      <div class="col-md-6 col-xl report-inner-card">
                        <div class="inner-card-text">
                          <span class="report-title">Back Out</span>
                          <h4>42</h4>
                          <span class="report-count"> 9 Reports</span>
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
                      <a href="#" class="text-dark ms-auto mb-3 mb-sm-0"> View all Products</a>
                    </div>
                    <div class="table-responsive border rounded p-1">
                      <table class="table">
                        <thead>
                          <tr>
                            <th class="font-weight-bold">Store ID</th>
                            <th class="font-weight-bold">Amount</th>
                            <th class="font-weight-bold">Gateway</th>
                            <th class="font-weight-bold">Created at</th>
                            <th class="font-weight-bold">Paid at</th>
                            <th class="font-weight-bold">Status</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>
                              <img class="img-sm rounded-circle" src="assets/images/faces/face1.jpg" alt="profile image"> Katie Holmes
                            </td>
                            <td>$3621</td>
                            <td><img src="assets/images/dashboard/alipay.png" alt="alipay" class="gateway-icon me-2"> alipay</td>
                            <td>04 Jun 2019</td>
                            <td>18 Jul 2019</td>
                            <td>
                              <div class="badge badge-success p-2">Paid</div>
                            </td>
                          </tr>
                          <tr>
                            <td>
                              <img class="img-sm rounded-circle" src="assets/images/faces/face2.jpg" alt="profile image"> Minnie Copeland
                            </td>
                            <td>$6245</td>
                            <td><img src="assets/images/dashboard/paypal.png" alt="alipay" class="gateway-icon me-2"> Paypal</td>
                            <td>25 Sep 2019</td>
                            <td>07 Oct 2019</td>
                            <td>
                              <div class="badge badge-danger p-2">Pending</div>
                            </td>
                          </tr>
                          <tr>
                            <td>
                              <img class="img-sm rounded-circle" src="assets/images/faces/face3.jpg" alt="profile image"> Rodney Sims
                            </td>
                            <td>$9265</td>
                            <td><img src="assets/images/dashboard/alipay.png" alt="alipay" class="gateway-icon me-2"> alipay</td>
                            <td>12 dec 2019</td>
                            <td>26 Aug 2019</td>
                            <td>
                              <div class="badge badge-warning p-2">Failed</div>
                            </td>
                          </tr>
                          <tr>
                            <td>
                              <img class="img-sm rounded-circle" src="assets/images/faces/face4.jpg" alt="profile image"> Carolyn Barker
                            </td>
                            <td>$2263</td>
                            <td><img src="assets/images/dashboard/alipay.png" alt="alipay" class="gateway-icon me-2"> alipay</td>
                            <td>30 Sep 2019</td>
                            <td>20 Oct 2019</td>
                            <td>
                              <div class="badge badge-success p-2">Paid</div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <div class="d-flex mt-4 flex-wrap align-items-center">
                      <p class="text-muted mb-sm-0">Showing 1 to 10 of 57 entries</p>
                      <nav class="ms-auto">
                        <ul class="pagination separated pagination-info mb-sm-0">
                          <li class="page-item"><a href="#" class="page-link"><i class="icon-arrow-left"></i></a></li>
                          <li class="page-item active"><a href="#" class="page-link">1</a></li>
                          <li class="page-item"><a href="#" class="page-link">2</a></li>
                          <li class="page-item"><a href="#" class="page-link">3</a></li>
                          <li class="page-item"><a href="#" class="page-link">4</a></li>
                          <li class="page-item"><a href="#" class="page-link"><i class="icon-arrow-right"></i></a></li>
                        </ul>
                      </nav>
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