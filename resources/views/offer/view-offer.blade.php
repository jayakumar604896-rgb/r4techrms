@extends('layouts.app')

@section('title', 'View Job Offer')

@section('content')

<style>
    /* Page Header */
    .page-header h3 {
        font-weight: 700;
        font-size: 26px;
        color: #3A3A8B;
    }

    .breadcrumb-item a {
        color: #4B49AC !important;
        font-weight: 600;
    }

    .breadcrumb-item.active {
        font-weight: 600;
        color: #6C63FF !important;
    }

    /* Card */
    .card {
        border-radius: 14px;
        border: none;
        padding-top: 10px;
        background: #ffffff;
        box-shadow: 0px 8px 30px rgba(0,0,0,0.06);
    }

    .card-title {
        font-weight: 700;
        font-size: 22px;
        padding-left: 15px;
        border-left: 4px solid #6C63FF;
        color: #2b2b2b;
        margin-bottom: 25px;
    }

    /* Table */
    .table {
        margin-top: 10px;
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
    }

    .table th {
        background: #f4f4ff;
        font-weight: 700;
        font-size: 15px;
        color: #2b2b2b;
        width: 30%;
        padding: 14px !important;
    }

    .table td {
        font-size: 15px;
        padding: 14px !important;
        color: #444;
    }

    .table-bordered > :not(caption) > * > * {
        border-color: #dcdcdc;
    }

    /* Buttons */
    .btn-primary {
        background: linear-gradient(90deg, #4B49AC, #6C63FF);
        border: none;
        padding: 12px 25px;
        border-radius: 8px;
        font-weight: 600;
    }

    .btn-light {
        padding: 12px 25px;
        border-radius: 8px;
        font-weight: 600;
        background: #f5f5f5;
    }

    .btn-outline-info {
        padding: 12px 25px;
        border-radius: 8px;
        border: 2px solid #4B49AC;
        font-weight: 600;
        color: #4B49AC;
    }

    .btn-outline-info:hover {
        background: #4B49AC;
        color: #fff;
    }
</style>


<div class="content-wrapper">

    <!-- Page Header -->
    <div class="page-header mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h3 class="page-title">Job Offer Details</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 mt-2">
                    <li class="breadcrumb-item">
                        <a href="{{ route('offer.index') }}">Offers</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">View Offer</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- View Card -->
    <div class="row justify-content-center">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-body">

                    <h4 class="card-title">Offer Details</h4>

                    <table class="table table-bordered">

                        <tr><th>NAME OF THE EMPLOYER</th><td>{{ $offer->employer_name }}</td></tr>

                        <tr><th>JOB LOCATION (COUNTRY)</th><td>{{ $offer->job_location }}</td></tr>

                        <tr><th>NATURE OF INDUSTRY</th><td>{{ $offer->industry }}</td></tr>

                        <tr><th>DESIGNATION OFFERED</th><td>{{ $offer->designation_offered }}</td></tr>

                        <tr><th>TYPE OF EMPLOYMENT</th><td>{{ $offer->employment_type }}</td></tr>

                        <tr><th>SALARY OFFERED</th><td>{{ $offer->salary_offered }}</td></tr>

                        <tr><th>ALLOWANCE OFFERED</th><td>{{ $offer->allowance_offered ?? '-' }}</td></tr>

                        <tr><th>TOTAL SALARY OFFER PER MONTH</th><td>{{ $offer->total_salary }}</td></tr>

                        <tr><th>FOOD</th><td>{{ $offer->food }}</td></tr>

                        <tr><th>ACCOMMODATION</th><td>{{ $offer->accommodation }}</td></tr>

                        <tr><th>ONWARD AIR TICKET</th><td>{{ $offer->air_ticket ?? '-' }}</td></tr>

                        <tr><th>MEDICAL TEST</th><td>{{ $offer->medical_test ?? '-' }}</td></tr>

                        <tr><th>CERTIFICATE ATTESTATION</th><td>{{ $offer->certificate_attestation ?? '-' }}</td></tr>

                        <tr><th>PCC (Police Clearance Certificate)</th><td>{{ $offer->pcc ?? '-' }}</td></tr>

                        <tr><th>PASSPORT EMIGRATION STATUS</th><td>{{ $offer->passport_status ?? '-' }}</td></tr>

                        <tr><th>PASSPORT VALIDITY</th><td>{{ $offer->passport_validity ?? '-' }}</td></tr>

                        <tr><th>WILLING TO WORK IN THE COUNTRY</th><td>{{ $offer->willing_to_work ?? '-' }}</td></tr>

                        <tr><th>NOTICE PERIOD</th><td>{{ $offer->notice_period }}</td></tr>

                        <tr><th>ANY OTHER INFORMATION</th><td>{{ $offer->other_information ?? '-' }}</td></tr>

                        <tr><th>CANDIDATE SIGNATURE & DATE</th><td>{{ $offer->signature ?? '-' }}</td></tr>

                    </table>

                    <!-- Buttons -->
                    <div class="mt-4">

                        <a href="{{ route('offer.edit', $offer->id) }}" class="btn btn-outline-info">
                            Edit Offer
                        </a>

                        <a href="{{ route('offer.index') }}" class="btn btn-light">
                            Back to List
                        </a>

                    </div>

                </div>
            </div>
        </div>
    </div>

</div>

@endsection
