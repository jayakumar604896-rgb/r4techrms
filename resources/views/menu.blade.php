@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    /* Dashboard Banner */
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

    /* Dashboard Styles */
    .dashboard-card {
        position: relative;
        background: #ffffff;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        margin-bottom: 20px;
        border: 1px solid #e8edf3;
        border-top: 3px solid var(--accent, #004085);
        overflow: hidden;
    }
    
    .dashboard-card:hover {
        box-shadow: 0 8px 24px rgba(0,0,0,0.14);
        transform: translateY(-2px);
        border-color: var(--accent, #004085);
        border-top-color: var(--accent, #004085);
    }

    /* Accent color variants per card */
    .dashboard-card.accent-teal    { --accent: #20c997; }
    .dashboard-card.accent-orange  { --accent: #fd7e14; }
    .dashboard-card.accent-green   { --accent: #28a745; }
    .dashboard-card.accent-indigo  { --accent: #6610f2; }
    .dashboard-card.accent-red     { --accent: #dc3545; }
    .dashboard-card.accent-cyan    { --accent: #17a2b8; }
    .dashboard-card.accent-brown   { --accent: #795548; }
    .dashboard-card.accent-pink    { --accent: #e83e8c; }
    .dashboard-card.accent-amber   { --accent: #ffc107; }
    .dashboard-card.accent-dark    { --accent: #343a40; }
    .dashboard-card.accent-blue    { --accent: #004085; }
    
    /* UPDATED: Dark header background with white text */
    .dashboard-card .card-header {
        background: color-mix(in srgb, var(--accent, #004085) 80%, #000000);
        padding: 15px 20px;
        border-bottom: none;
        font-weight: 700;
        color: #ffffff;
        font-size: 15px;
        letter-spacing: 0.3px;
    }

    /* Special case for light accents like Amber to ensure text is readable */
    .dashboard-card.accent-amber .card-header {
        background: #b38600;
        color: #ffffff;
    }
    
    .dashboard-card .card-body {
        padding: 20px;
    }
    
    .dashboard-card .card-body .menu-item {
        padding: 8px 12px;
        margin: 3px 0;
        border-radius: 6px;
        transition: all 0.2s ease;
        cursor: pointer;
        color: #333;
        font-size: 14px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        text-decoration: none;
    }
    
    .dashboard-card .card-body .menu-item:hover {
        background: #f0f4ff;
        color: #004085;
        text-decoration: none;
    }
    
    .dashboard-card .card-body .menu-item .badge-count {
        background: #004085;
        color: #fff;
        padding: 2px 10px;
        border-radius: 20px;
        font-size: 12px;
        min-width: 30px;
        text-align: center;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s ease, background-color 0.2s ease;
    }

    .dashboard-card .card-body .menu-item:hover .badge-count:not(.hyper) {
        transform: scale(1.05);
        background: #002752;
    }
    
    .dashboard-card .card-body .menu-item .badge-count.hyper {
        background: #28a745;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .dashboard-card .card-body .menu-item .badge-count.hyper:hover,
    .dashboard-card .card-body .menu-item:hover .badge-count.hyper {
        background: #218838;
        transform: scale(1.05);
    }
    
    .dashboard-card .card-body .sub-menu {
        padding-left: 20px;
        border-left: 2px solid #e8edf3;
        margin-left: 10px;
    }
    
    .dashboard-card .card-body .sub-menu .menu-item {
        font-size: 13px;
        padding: 5px 10px;
    }
    
    .dashboard-card .card-body .menu-item i {
        margin-right: 10px;
        width: 20px;
        color: #6c757d;
    }
    
    .dashboard-card .card-body .menu-item:hover i {
        color: #004085;
    }
    
    .dashboard-card .card-body .view-all {
        text-align: center;
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid #e8edf3;
    }
    
    .dashboard-card .card-body .view-all a {
        color: #004085;
        text-decoration: none;
        font-weight: 500;
        font-size: 13px;
    }
    
    .dashboard-card .card-body .view-all a:hover {
        text-decoration: underline;
    }
    
    .scroll-container {
        max-height: 300px;
        overflow-y: auto;
        padding-right: 5px;
    }
    
    .scroll-container::-webkit-scrollbar {
        width: 6px;
    }
    
    .scroll-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .scroll-container::-webkit-scrollbar-thumb {
        background: #c1c7cd;
        border-radius: 10px;
    }
    
    .scroll-container::-webkit-scrollbar-thumb:hover {
        background: #a8aeb4;
    }
    
    .grid-layout {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }
    
    .card-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 18px;
        margin-right: 10px;
        flex-shrink: 0;
    }
    
    .card-icon.blue { background: #004085; }
    .card-icon.green { background: #28a745; }
    .card-icon.orange { background: #fd7e14; }
    .card-icon.purple { background: #6f42c1; }
    .card-icon.red { background: #dc3545; }
    .card-icon.teal { background: #20c997; }
    .card-icon.indigo { background: #6610f2; }
    .card-icon.pink { background: #e83e8c; }
    .card-icon.cyan { background: #17a2b8; }
    .card-icon.amber { background: #ffc107; }
    .card-icon.brown { background: #795548; }
    .card-icon.dark { background: #343a40; }
    
    .header-with-icon {
        display: flex;
        align-items: center;
        width: 100%;
    }
    
    .menu-item-text {
        display: flex;
        align-items: center;
        flex: 1;
    }
    
    .menu-item-text i {
        margin-right: 10px;
        width: 20px;
        text-align: center;
    }
    
    .badge-count i {
        margin-right: 0 !important;
        width: auto !important;
        font-size: 10px !important;
        color: #fff !important;
    }
    
    .checkbox-group {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 5px 15px;
        padding: 5px 0;
    }
    
    .checkbox-group .menu-item {
        font-size: 12px;
        padding: 4px 8px;
    }
    
    .checkbox-group .menu-item i {
        font-size: 12px;
        width: 16px;
    }
    
    .required-mark {
        color: #dc3545;
        font-weight: bold;
        margin-left: 2px;
    }
    
    .btn-group-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px solid #e8edf3;
    }
    
    .btn-group-actions .btn-action {
        flex: 1;
        min-width: 100px;
        padding: 6px 12px;
        border-radius: 6px;
        border: 1px solid #004085;
        background: #fff;
        color: #004085;
        font-size: 12px;
        font-weight: 500;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    
    .btn-group-actions .btn-action:hover {
        background: #004085;
        color: #fff;
        text-decoration: none;
    }
    
    .btn-group-actions .btn-action i {
        margin-right: 5px;
    }
    
    .note-section {
        background: #f8f9fa;
        padding: 10px 12px;
        border-radius: 6px;
        font-size: 12px;
        color: #6c757d;
        margin-top: 10px;
        border-left: 3px solid #004085;
    }
    
    @media (max-width: 768px) {
        .grid-layout {
            grid-template-columns: 1fr;
        }
        .checkbox-group {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="content-wrapper">
    
    <!-- ░░ TOP BANNER ░░ -->
	@if($companySetting && $companySetting->image)
    	<img src="{{ asset('uploads/company-settings/' . $companySetting->image) }}" alt="We Are Hiring Banner" class="dashboard-banner">
	@endif

    <!-- Main Menu Grid -->
    <div class="grid-layout">
        <!-- 1. RESUME MANAGEMENT -->
        <div class="dashboard-card accent-teal">
            <div class="card-header">
                <div class="header-with-icon">
                    <span class="card-icon teal"><i class="fas fa-file-alt"></i></span>
                    RESUME MANAGEMENT
                </div>
            </div>
            <div class="card-body">
                <a href="{{ route('candidates.index', ['source' => 'database']) }}" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-database"></i> Data Base CVs
                    </span>
                    <span class="badge-count">{{ $databaseCVs ?? 0 }}</span>
                </a>
                <a href="{{ route('candidates.index', ['source' => 'website']) }}" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-globe"></i> Website CVs
                    </span>
                    <span class="badge-count">{{ $websiteCVs ?? 0 }}</span>
                </a>
                <a href="{{ route('candidates.index', ['source' => 'other']) }}" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-upload"></i> Other CVs
                    </span>
                    <span class="badge-count">{{ $otherCVs ?? 0 }}</span>
                </a>
                <a href="{{ route('candidates.index') }}" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-chart-bar"></i> Total CV volumes
                    </span>
                    <span class="badge-count hyper">
                        <i class="fas fa-file"></i> {{ $totalCVs ?? 0 }}
                    </span>
                </a>
                <a href="{{ route('candidates.index', ['status' => 'shortlisted']) }}" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-check-circle"></i> CVs Shortlisted
                    </span>
                    <span class="badge-count">{{ $shortlistedCVs ?? 0 }}</span>
                </a>
                <a href="{{ route('candidates.index', ['status' => 'submitted']) }}" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-paper-plane"></i> CVs Submitted / Re-Awage
                    </span>
                    <span class="badge-count">{{ $submittedCVs ?? 0 }}</span>
                </a>
                <a href="{{ route('add-candidates') }}" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-fast-forward"></i> Quick Uploads
                    </span>
                </a>
                <div class="view-all">
                    <a href="{{ route('candidates.index') }}">View All CVs <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
        <!-- 2. DAILY MARKETING -->
        <div class="dashboard-card accent-orange">
            <div class="card-header">
                <div class="header-with-icon">
                    <span class="card-icon orange"><i class="fas fa-bullhorn"></i></span>
                    DAILY MARKETING
                </div>
            </div>
            <div class="card-body">
                <a href="{{ route('clients.index', ['type' => 'domestic']) }}" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-globe-asia"></i> List of Pan India Clients
                    </span>
                    <span class="badge-count">{{ $panIndiaClientsCount ?? 0 }}</span>
                </a>
                <a href="{{ route('clients.index', ['type' => 'international']) }}" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-globe-americas"></i> List of International Clients
                    </span>
                    <span class="badge-count">{{ $internationalClientsCount ?? 0 }}</span>
                </a>
                <a href="{{ route('sendtoothers.index', ['type' => 'domestic']) }}" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-chart-line"></i> Pan India Marketing
                    </span>
                    <span class="badge-count hyper">
                        <i class="fas fa-users"></i> {{ $panIndiaMarketingCount ?? 0 }}
                    </span>
                </a>
                <a href="{{ route('sendtoothers.index', ['type' => 'international']) }}" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-chart-line"></i> International Marketing
                    </span>
                    <span class="badge-count hyper">
                        <i class="fas fa-users"></i> {{ $internationalMarketingCount ?? 0 }}
                    </span>
                </a>
                
                <div class="view-all">
                    <a href="{{ route('sendtoothers.index') }}">View All <i class="fas fa-arrow-right"></i></a>
                </div>
                
                <!-- Scrollable Marketing Details -->
                <div class="mt-3">
                    <div class="scroll-container">
                        <h6 class="text-muted mb-2"><i class="fas fa-flag"></i> Pan India Marketing</h6>
                        @if(isset($panIndiaMarketing) && $panIndiaMarketing->count() > 0)
                            @foreach($panIndiaMarketing as $item)
                            <a href="{{ route('clients.show', $item->id) }}" class="menu-item" style="font-size: 12px; padding: 4px 8px; text-decoration: none;">
                                <span class="menu-item-text">
                                    <i class="fas fa-circle" style="font-size: 6px; color: #28a745;"></i>
                                    {{ $item->company_name ?? $item->client_name ?? 'Client Name' }}
                                </span>
                                <span class="badge-count" style="font-size: 10px; padding: 1px 8px;"><i class="fas fa-eye"></i></span>
                            </a>
                            @endforeach
                        @else
                            <div class="menu-item" style="font-size: 12px; padding: 4px 8px; cursor: default;">
                                <span class="menu-item-text">
                                    <i class="fas fa-circle" style="font-size: 6px; color: #6c757d;"></i>
                                    No data available
                                </span>
                            </div>
                        @endif
                        
                        <h6 class="text-muted mt-3 mb-2"><i class="fas fa-globe"></i> International Marketing</h6>
                        @if(isset($internationalMarketing) && $internationalMarketing->count() > 0)
                            @foreach($internationalMarketing as $item)
                            <a href="{{ route('clients.show', $item->id) }}" class="menu-item" style="font-size: 12px; padding: 4px 8px; text-decoration: none;">
                                <span class="menu-item-text">
                                    <i class="fas fa-circle" style="font-size: 6px; color: #17a2b8;"></i>
                                    {{ $item->company_name ?? $item->client_name ?? 'Client Name' }}
                                </span>
                                <span class="badge-count" style="font-size: 10px; padding: 1px 8px;"><i class="fas fa-eye"></i></span>
                            </a>
                            @endforeach
                        @else
                            <div class="menu-item" style="font-size: 12px; padding: 4px 8px; cursor: default;">
                                <span class="menu-item-text">
                                    <i class="fas fa-circle" style="font-size: 6px; color: #6c757d;"></i>
                                    No data available
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
 		<!-- 3. NEW ENROLMENT -->
        <div class="dashboard-card accent-green">
            <div class="card-header">
                <div class="header-with-icon">
                    <span class="card-icon green"><i class="fas fa-user-plus"></i></span>
                    NEW ENROLMENT
                </div>
            </div>
            <div class="card-body">
                <a href="{{ route('recruiters') }}" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-user-tie"></i> Employees / HR Recruiters
                    </span>
                    <span class="badge-count">{{ $employeesCount ?? 0 }}</span>
                </a>
                <a href="{{ route('clients.index') }}" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-handshake"></i> Client
                    </span>
                    <span class="badge-count">{{ $clientsCount ?? 0 }}</span>
                </a>
                <a href="{{ route('agents.index') }}" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-briefcase"></i> Business Agent
                    </span>
                    <span class="badge-count">{{ $businessAgentsCount ?? 0 }}</span>
                </a>
                <a href="{{ route('agents.index') }}" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-users-cog"></i> Sub Agent
                    </span>
                    <span class="badge-count">{{ $subAgentsCount ?? 0 }}</span>
                </a>
                <a href="{{ route('service-provider-details.index') }}" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-concierge-bell"></i> Service Providers
                    </span>
                    <span class="badge-count">{{ $serviceProvidersCount ?? 0 }}</span>
                </a>
            </div>
        </div>
        <!-- 4. CANDIDATE RECRUITMENT PROGRESS -->
        <div class="dashboard-card accent-indigo">
            <div class="card-header">
                <div class="header-with-icon">
                    <span class="card-icon indigo"><i class="fas fa-user-graduate"></i></span>
                    CANDIDATE RECRUITMENT PROGRESS
                </div>
            </div>
            <div class="card-body">
                <a href="#" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-upload"></i> Selected Candidates data upload
                    </span>
                </a>
                <a href="#" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-flag"></i> Pan India
                    </span>
                </a>
                <a href="#" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-clock"></i> International
                    </span>
                </a>
                <div class="view-all">
                    <a href="#">View all <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
        <!-- 5. DEMAND REGISTER -->
        <div class="dashboard-card accent-red">
            <div class="card-header">
                <div class="header-with-icon">
                    <span class="card-icon red"><i class="fas fa-clipboard-list"></i></span>
                    DEMAND REGISTER
                </div>
            </div>
            <div class="card-body">
                <a href="{{ route('demands.index', ['type' => 'domestic']) }}" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-flag"></i> Pan India Dominated
                    </span>
                    <span class="badge-count">{{ $domesticDemands ?? 0 }}</span>
                </a>
                <a href="{{ route('demands.index', ['type' => 'international']) }}" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-globe"></i> International Marketing Dominated
                    </span>
                    <span class="badge-count">{{ $internationalDemands ?? 0 }}</span>
                </a>
                <div class="view-all">
                    <a href="{{ route('demands.index') }}">View All <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
        <!-- 6. DISPATCHES -->
 		<div class="dashboard-card accent-cyan">
            <div class="card-header">
                <div class="header-with-icon">
                    <span class="card-icon cyan"><i class="fas fa-file-contract"></i></span>
                    DISPATCHES
                </div>
            </div>
            <div class="card-body">
                <a href="#" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-flag"></i> Candidate Document Upload
                    </span>
                    <span class="badge-count">{{ $preOfferPanIndia ?? 0 }}</span>
                </a>
                <a href="#" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-check-circle"></i> Travel Schedule
                    </span>
                    <span class="badge-count">{{ $mainOfferPanIndia ?? 0 }}</span>
                </a>
                
 				<div class="view-all">
                    <a href="#">View all <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
        <!-- 7. INVOICE & QUOTATIONS -->
        <div class="dashboard-card accent-brown">
            <div class="card-header">
                <div class="header-with-icon">
                    <span class="card-icon brown"><i class="fas fa-chart-bar"></i></span>
                    INVOICE & QUOTATIONS
                </div>
            </div>
            <div class="card-body">
                <a href="{{ route('domestic-invoice-reports.index') }}" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-file-alt"></i> Proforma Invoice
                    </span>
                    <span class="badge-count">{{ $proformaInvoiceCount ?? 0 }}</span>
                </a>

                <a href="{{ route('tax-invoice-reports.index') }}" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-calendar-alt"></i> Tax Invoice
                    </span>
                    <span class="badge-count">{{ $taxInvoiceCount ?? 0 }}</span>
                </a>

                <a href="{{ route('invoice-reports.index') }}" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-chart-line"></i> Commercial Invoice
                    </span>
                    <span class="badge-count hyper">
                        <i class="fas fa-file-invoice-dollar"></i> {{ $commercialInvoiceCount ?? 0 }}
                    </span>
                </a>

				 <a href="{{ route('tax-international-invoice-reports.index') }}" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-chart-line"></i> Sale Invoice
                    </span>
                    <span class="badge-count hyper">
                        <i class="fas fa-file-invoice-dollar"></i> {{ $saleInvoiceCount ?? 0 }}
                    </span>
                </a> 
				<a href="{{ route('domestic-invoice-reports.index') }}" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-chart-line"></i> QUOTATIONS
                    </span>
                    <span class="badge-count hyper">
                        <i class="fas fa-file-invoice"></i> {{ $quotationsCount ?? 0 }}
                    </span>
                </a>
 				<a href="{{ route('receipts.index') }}" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-chart-line"></i> E-Receipts
                    </span>
                    <span class="badge-count hyper">
                        <i class="fas fa-receipt"></i> {{ $receiptsCount ?? 0 }}
                    </span>
                </a>
				<div class="note-section" style="border-left-color: #28a745;">
                    <i class="fas fa-info-circle"></i> 
                    <strong>Note:</strong> Pan India, International Scroll Button to Appear.
                </div>
                <div class="view-all">
                    <a href="{{ route('domestic-invoice-reports.index') }}">View All <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
        <!-- 8. MAILS FOLLOW UP -->
        <div class="dashboard-card accent-pink">
            <div class="card-header">
                <div class="header-with-icon">
                    <span class="card-icon pink"><i class="fas fa-envelope"></i></span>
                    MAILS FOLLOW UP
                </div>
            </div>
            <div class="card-body">
                <a href="{{ route('sendtoothers.index') }}" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-building"></i> Mails to Client
                    </span>
                    <span class="badge-count">{{ $clientMails ?? 0 }}</span>
                </a>
                <a href="{{ route('sendtocandidates.index') }}" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-user"></i> Mails to Candidate
                    </span>
                    <span class="badge-count">{{ $candidateMails ?? 0 }}</span>
                </a>
                <a href="{{ route('offer.index') }}" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-file-signature"></i> Offer Request / Visa Request
                    </span>
                    <span class="badge-count">{{ $offerRequests ?? 0 }}</span>
                </a>
 				<div class="view-all">
                    <a href="{{ route('sendtoothers.index') }}">View all <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
        
        <!-- 9. OFFERS TO CANDIDATE -->
        <div class="dashboard-card accent-cyan">
            <div class="card-header">
                <div class="header-with-icon">
                    <span class="card-icon cyan"><i class="fas fa-file-contract"></i></span>
                    OFFERS TO CANDIDATE
                </div>
            </div>
            <div class="card-body">
                <a href="{{ route('offer.index', ['type' => 'domestic', 'category' => 'pre_offer']) }}" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-flag"></i> Pre Offer Pan India
                    </span>
                    <span class="badge-count">{{ $preOfferPanIndia ?? 0 }}</span>
                </a>
                <a href="{{ route('offer.index', ['type' => 'domestic', 'category' => 'main_offer']) }}" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-check-circle"></i> Main Offer Pan India
                    </span>
                    <span class="badge-count">{{ $mainOfferPanIndia ?? 0 }}</span>
                </a>
                <a href="{{ route('sendtocandidates.index') }}" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-envelope"></i> General Email Offer
                    </span>
                    <span class="badge-count">{{ $generalEmailOffers ?? 0 }}</span>
                </a>
                <a href="{{ route('offer.index', ['type' => 'international', 'category' => 'pre_offer']) }}" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-globe"></i> Pre Offer International
                    </span>
                    <span class="badge-count">{{ $preOfferInternational ?? 0 }}</span>
                </a>
                <a href="{{ route('offer.index', ['type' => 'international', 'category' => 'main_offer']) }}" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-globe-americas"></i> Main Offer International
                    </span>
                    <span class="badge-count">{{ $mainOfferInternational ?? 0 }}</span>
                </a>
 				<div class="view-all">
                    <a href="{{ route('offer.index') }}">View all <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
        <!-- 10. FORMATS & TEMPLATES -->
        <div class="dashboard-card accent-amber">
            <div class="card-header">
                <div class="header-with-icon">
                    <span class="card-icon amber"><i class="fas fa-file-format"></i></span>
                    FORMATS & TEMPLATES
                </div>
            </div>
            <div class="card-body">
				<a href="#" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-upload"></i> HTML Templates
                    </span>
                    <span class="badge-count" style="background: #ffc107; color: #000;">25+</span>
                </a>
                <h6 class="text-muted mb-2"><i class="fas fa-flag"></i> Pan India</h6>
                <a href="#" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-upload"></i> Upload Formats Pan-India
                    </span>
                    <span class="badge-count" style="background: #ffc107; color: #000;">25+</span>
                </a>
                <a href="#" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-eye"></i> View Formats Pan-India
                    </span>
                    <span class="badge-count" style="background: #ffc107; color: #000;">25+</span>
                </a>
                <a href="#" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-envelope"></i> General Message Pan-India
                    </span>
                </a>

                <h6 class="text-muted mt-3 mb-2"><i class="fas fa-globe"></i> International</h6>
                <a href="#" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-upload"></i> Upload Formats International
                    </span>
                    <span class="badge-count" style="background: #ffc107; color: #000;">25+</span>
                </a>
                <a href="#" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-eye"></i> View Formats International
                    </span>
                    <span class="badge-count" style="background: #ffc107; color: #000;">25+</span>
                </a>
                <a href="#" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-envelope"></i> General Message International
                    </span>
                </a>

                <div class="note-section">
                    <i class="fas fa-info-circle"></i> 
                    <strong>View:</strong> Will have a consolidated formats available. Each button to have minimum 25 uploads.
                </div>

                <div class="view-all">
                    <a href="#">View All Formats <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
        <!-- 11. TRAVEL SCHEDULE, TRAVEL DOCUMENTS & DEPATCHES -->
        <div class="dashboard-card accent-dark">
            <div class="card-header">
                <div class="header-with-icon">
                    <span class="card-icon dark"><i class="fas fa-plane"></i></span>
                    TRAVEL SCHEDULE, DOCUMENTS & DEPATCHES
                </div>
            </div>
            <div class="card-body">
                <a href="#" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-calendar-check"></i> Travel Schedule
                    </span>
                    <span class="badge-count">{{ $travelScheduleCount ?? 0 }}</span>
                </a>
                <div class="note-section" style="border-left-color: #343a40; margin-bottom: 10px;">
                    <i class="fas fa-info-circle"></i> 
                    Travel schedule is an email content where the flight details and candidate details are given for client reference.
                </div>

                <h6 class="text-muted mb-2"><i class="fas fa-file-alt"></i> Travel Documents</h6>
                <div class="checkbox-group">
                    <div class="menu-item" style="cursor: default;">
                        <span class="menu-item-text">
                            <i class="fas fa-ticket-alt"></i> Onward Airticket <span class="required-mark">*</span>
                        </span>
                    </div>
                    <div class="menu-item" style="cursor: default;">
                        <span class="menu-item-text">
                            <i class="fas fa-ticket-alt"></i> Return Airticket
                        </span>
                    </div>
                    <div class="menu-item" style="cursor: default;">
                        <span class="menu-item-text">
                            <i class="fas fa-shield-alt"></i> Travel Insurance
                        </span>
                    </div>
                    <div class="menu-item" style="cursor: default;">
                        <span class="menu-item-text">
                            <i class="fas fa-passport"></i> Visa Paper <span class="required-mark">*</span>
                        </span>
                    </div>
                    <div class="menu-item" style="cursor: default;">
                        <span class="menu-item-text">
                            <i class="fas fa-id-card"></i> Passport Pages <span class="required-mark">*</span>
                        </span>
                    </div>
                    <div class="menu-item" style="cursor: default;">
                        <span class="menu-item-text">
                            <i class="fas fa-check-circle"></i> ECR Clearance
                        </span>
                    </div>
                    <div class="menu-item" style="cursor: default;">
                        <span class="menu-item-text">
                            <i class="fas fa-phone"></i> Interview call letter
                        </span>
                    </div>
                    <div class="menu-item" style="cursor: default;">
                        <span class="menu-item-text">
                            <i class="fas fa-hotel"></i> Hotel Accommodation Booking
                        </span>
                    </div>
                    <div class="menu-item" style="cursor: default;">
                        <span class="menu-item-text">
                            <i class="fas fa-file-signature"></i> Candidate's signed offer letter
                        </span>
                    </div>
                    <div class="menu-item" style="cursor: default;">
                        <span class="menu-item-text">
                            <i class="fas fa-camera"></i> Candidate photo
                        </span>
                    </div>
                    <div class="menu-item" style="cursor: default;">
                        <span class="menu-item-text">
                            <i class="fas fa-map-marker-alt"></i> Employer Address
                        </span>
                    </div>
                    <div class="menu-item" style="cursor: default;">
                        <span class="menu-item-text">
                            <i class="fas fa-id-card"></i> Employer Civil ID card
                        </span>
                    </div>
                    <div class="menu-item" style="cursor: default;">
                        <span class="menu-item-text">
                            <i class="fas fa-handshake"></i> Candidate MoU
                        </span>
                    </div>
                    <div class="menu-item" style="cursor: default;">
                        <span class="menu-item-text">
                            <i class="fas fa-clipboard-check"></i> Despatch Check List
                        </span>
                    </div>
                </div>

                <div class="btn-group-actions">
                    <a href="#" class="btn-action">
                        <i class="fas fa-user"></i> Send to Recruiter ID
                    </a>
                    <a href="#" class="btn-action">
                        <i class="fas fa-building"></i> Submit to Client
                    </a>
                    <a href="#" class="btn-action">
                        <i class="fas fa-user-check"></i> Submit to Candidate
                    </a>
                </div>

                <div class="note-section" style="border-left-color: #28a745;">
                    <i class="fas fa-info-circle"></i> 
                    <strong>Note:</strong> Recruiter will tick mark the required heading and upload relevant document either to client or candidate by email.
                </div>

                <div class="view-all">
                    <a href="#">View All Travel Documents <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
        <!-- 12. PROGRESS REPORTS -->
        <div class="dashboard-card accent-brown">
            <div class="card-header">
                <div class="header-with-icon">
                    <span class="card-icon brown"><i class="fas fa-chart-bar"></i></span>
                    PROGRESS REPORTS
                </div>
            </div>
            <div class="card-body">
                <a href="#" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-file-alt"></i> DPR (Daily Progress Report)
                    </span>
                    <span class="badge-count">{{ $dprCount ?? 0 }}</span>
                </a>

                <a href="#" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-calendar-alt"></i> Monthly Progress
                    </span>
                    <span class="badge-count">{{ $monthlyProgressCount ?? 0 }}</span>
                </a>

                <a href="#" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-chart-line"></i> PMS Report
                    </span>
                    <span class="badge-count hyper">
                        <i class="fas fa-chart-pie"></i> {{ $pmsReportCount ?? 0 }}
                    </span>
                </a>

                <div class="note-section" style="border-left-color: #795548;">
                    <i class="fas fa-info-circle"></i> 
                    <strong>PMS Report:</strong> Monthwise Performance graph with Staff-wise monthly report.
                </div>

                <a href="#" class="menu-item">
                    <span class="menu-item-text">
                        <i class="fas fa-chart-pie"></i> Progress Chart Report
                    </span>
                    <span class="badge-count">{{ $progressChartCount ?? 0 }}</span>
                </a>

                <div class="note-section" style="border-left-color: #dc3545; margin-top: 5px;">
                    <i class="fas fa-exclamation-triangle" style="color: #dc3545;"></i> 
                    <strong>Note:</strong> Pan India & International reports are available separately.
                </div>

                <div class="view-all">
                    <a href="#">View All Reports <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div> 
</div>

@endsection

@section('js')
<script>
    function showMarketingDetails(type) {
        if (type === 'pan-india') {
            window.location.href = "{{ route('sendtoothers.index', ['type' => 'domestic']) }}";
        } else if (type === 'international') {
            window.location.href = "{{ route('sendtoothers.index', ['type' => 'international']) }}";
        }
    }
    
    $(document).ready(function() {
        $('.menu-item:not([style*="cursor: default"])').on('mouseenter', function() {
            $(this).css('background-color', '#f0f4ff');
        }).on('mouseleave', function() {
            $(this).css('background-color', 'transparent');
        });
    });
</script>
@endsection