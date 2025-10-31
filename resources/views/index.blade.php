@extends('layouts.app')
@section('title', 'Software Management Dashboard')
@section('content')
<div class="wrapper">
    <nav class="main-header navbar navbar-expand navbar-white navbar-light fixed-top border-bottom bg-white">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="/software/manage" class="nav-link fw-medium">Home</a>
            </li>
        </ul>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                    <div class="d-flex align-items-center">
                        <div class="avatar-circle bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                            <span class="fw-bold">A</span>
                        </div>
                        <span class="d-none d-md-inline">Admin</span>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                    <li class="dropdown-header p-3 bg-light border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="avatar-circle bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                <span class="fw-bold">A</span>
                            </div>
                            <div>
                                <p class="mb-0 fw-semibold">Admin</p>
                                <small class="text-muted">Software Manager</small>
                            </div>
                        </div>
                    </li>
                    <li class="dropdown-footer p-2">
                        <a href="{{ route('logout') }}" class="btn btn-outline-danger btn-sm w-100">
                            <i class="fas fa-sign-out-alt me-1"></i> Sign out
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
    @include('partials.sidebar')
    <div class="content-wrapper mt-5 pt-3 bg-light">
        <div class="content-header bg-white py-3 border-bottom">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-sm-7">
                        <h1 class="m-0 fw-bold text-dark fs-3">Software Management Portal</h1>
                        <p class="text-muted mb-0">Manage your software products efficiently in one place</p>
                    </div>
                    <div class="col-sm-5 text-sm-end d-flex">
                        <div class="d-inline-flex align-items-center bg-primary text-white py-1 px-3 rounded">
                            <i class="fas fa-cube me-2"></i>
                            <span id="current-software" class="fw-medium">Select Software</span>
                        </div>
                        <div class="d-inline-flex align-items-center bg-success text-white py-1 px-3 rounded ms-2" id="software-type-badge" style="display: none;">
                            <i class="fas fa-tag me-2"></i>
                            <span id="current-software-type">Unknown</span>
                        </div>
                    </div>
                    <div id="current-time" class="text-muted small mt-1"></div>
                </div>
            </div>
        </div>
        <section class="content pb-4 p-0 pt-4">
            <div class="container-fluid p-0">
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body py-3">
                                <div class="row">
                                    <div class="col-lg-2 col-md-4 col-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-info bg-opacity-10 p-3 rounded me-3">
                                                <i class="fas fa-cube text-primary fs-5"></i>
                                            </div>
                                            <div>
                                                <h5 class="mb-0 fw-bold" id="softwares-count">{{ $softwares->count() ?? 0 }}</h5>
                                                <small class="text-muted">Software Products</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-4 col-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-success bg-opacity-10 p-3 rounded me-3">
                                                <i class="fas fa-star text-success fs-5"></i>
                                            </div>
                                            <div>
                                                <h5 class="mb-0 fw-bold" id="features-count">0</h5>
                                                <small class="text-muted">Features</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-4 col-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-warning bg-opacity-10 p-3 rounded me-3">
                                                <i class="fas fa-clock text-warning fs-5"></i>
                                            </div>
                                            <div>
                                                <h5 class="mb-0 fw-bold" id="trials-count">0</h5>
                                                <small class="text-muted">Active Trials</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-4 col-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-info bg-opacity-10 p-3 rounded me-3">
                                                <i class="fas fa-question-circle text-info fs-5"></i>
                                            </div>
                                            <div>
                                                <h5 class="mb-0 fw-bold" id="faqs-count">0</h5>
                                                <small class="text-muted">FAQs</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-4 col-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-danger bg-opacity-10 p-3 rounded me-3">
                                                <i class="fas fa-envelope text-danger fs-5"></i>
                                            </div>
                                            <div>
                                                <h5 class="mb-0 fw-bold" id="requests-count">0</h5>
                                                <small class="text-muted">Requests</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-4 col-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-warning bg-opacity-10 p-3 rounded me-3">
                                                <i class="fas fa-ticket-alt text-warning fs-5"></i>
                                            </div>
                                            <div>
                                                <h5 class="mb-0 fw-bold" id="tickets-count">0</h5>
                                                <small class="text-muted">Support Tickets</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-bold">
                                    <i class="fas fa-cube me-2 text-primary"></i>Software Management
                                </h5>
                                <div>
                                    <button class="btn btn-outline-primary btn-sm me-2" id="changeSoftwareTypeBtn" style="display: none;">
                                        <i class="fas fa-exchange-alt me-1"></i> Change Type
                                    </button>
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addSoftwareModal">
                                        <i class="fas fa-plus me-1"></i> Add Software
                                    </button>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="p-3 border-bottom bg-light">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Select Software</label>
                                            <select class="form-control select2" id="softwareSelect">
                                                <option value="">Choose software...</option>
                                                @foreach($softwares as $software)
                                                    <option value="{{ $software->name }}" data-url="{{ $software->url }}" data-id="{{ $software->id }}" {{ ($selectedSoftware ?? '') == $software->name ? 'selected' : '' }}>
                                                        {{ $software->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-hover mb-0" id="softwareTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="fw-semibold py-3">Name</th>
                                                <th class="fw-semibold py-3">URL</th>
                                                <th class="fw-semibold py-3">Status</th>
                                                <th class="fw-semibold py-3">Created</th>
                                                <th class="fw-semibold py-3 text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="software-body">
                                            @foreach($softwares as $software)
                                                <tr>
                                                    <td class="fw-semibold align-middle">
                                                        <div class="d-flex align-items-center">
                                                            <div class="bg-info bg-opacity-10 p-2 rounded me-3">
                                                                <i class="fas fa-cube text-primary"></i>
                                                            </div>
                                                            <span>{{ $software->name }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="align-middle">
                                                        <a href="{{ $software->url }}" target="_blank" class="text-decoration-none text-primary">
                                                            <i class="fas fa-external-link-alt me-1"></i>
                                                            {{ Str::limit($software->url, 25) }}
                                                        </a>
                                                    </td>
                                                    <td class="align-middle">
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check-circle me-1"></i> Active
                                                        </span>
                                                    </td>
                                                    <td class="align-middle text-muted">{{ $software->created_at}}</td>
                                                    <td class="align-middle text-end">
                                                        <div class="btn-group">
                                                            <button class="btn btn-sm btn-outline-primary edit-software" 
                                                                    data-id="{{ $software->id }}" 
                                                                    data-name="{{ $software->name }}" 
                                                                    data-url="{{ $software->url }}" 
                                                                    title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-outline-danger delete-software" 
                                                                    data-id="{{ $software->id }}" 
                                                                    data-name="{{ $software->name }}"
                                                                    title="Delete">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
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

                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-0 pt-3">
                                <ul class="nav nav-tabs card-header-tabs border-bottom-0" id="adminTabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active fw-semibold py-3 px-4" data-bs-toggle="tab" data-bs-target="#features" type="button" role="tab">
                                            <i class="fas fa-star me-2"></i> Features
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link fw-semibold py-3 px-4" data-bs-toggle="tab" data-bs-target="#trials" type="button" role="tab">
                                            <i class="fas fa-clock me-2"></i> Trials
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link fw-semibold py-3 px-4" data-bs-toggle="tab" data-bs-target="#software-requests" type="button" role="tab">
                                            <i class="fas fa-envelope me-2"></i> Requests
                                        </button>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body p-0">
                                <div class="tab-content p-4">
                                    <div class="tab-pane fade show active" id="features" role="tabpanel">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h5 class="mb-0 fw-bold">Features</h5>
                                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addFeatureModal">
                                                <i class="fas fa-plus me-1"></i> Add Feature
                                            </button>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-hover" id="features-table">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th class="fw-semibold">Name</th>
                                                        <th class="fw-semibold">Description</th>
                                                        <th class="fw-semibold">Price</th>
                                                        <th class="fw-semibold">Video</th>
                                                        <th class="fw-semibold">Status</th>
                                                        <th class="fw-semibold text-end">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="features-body">
                                                    <tr>
                                                        <td colspan="6" class="text-center py-5 text-muted">
                                                            <i class="fas fa-star fa-2x mb-3 opacity-25"></i>
                                                            <p class="fw-medium">Select a software to view features</p>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="trials" role="tabpanel">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h5 class="mb-0 fw-bold">Trials</h5>
                                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addTrialModal">
                                                <i class="fas fa-plus me-1"></i> Add Trial
                                            </button>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-hover" id="trials-table">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th class="fw-semibold">Client</th>
                                                        <th class="fw-semibold">Feature</th>
                                                        <th class="fw-semibold">Dates</th>
                                                        <th class="fw-semibold">Status</th>
                                                        <th class="fw-semibold text-end">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="trials-body">
                                                    <tr>
                                                        <td colspan="5" class="text-center py-5 text-muted">
                                                            <i class="fas fa-clock fa-2x mb-3 opacity-25"></i>
                                                            <p class="fw-medium">Select a software to view trials</p>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="software-requests" role="tabpanel">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h5 class="mb-0 fw-bold">Software Requests</h5>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-hover" id="software-requests-table">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th class="fw-semibold">Client Details</th>
                                                        <th class="fw-semibold">Requested Date</th>
                                                        <th class="fw-semibold">Message</th>
                                                        <th class="fw-semibold">Status</th>
                                                        <th class="fw-semibold text-end">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="software-requests-body">
                                                    <tr>
                                                        <td colspan="6" class="text-center py-5 text-muted">
                                                            <i class="fas fa-desktop fa-2x mb-3 opacity-25"></i>
                                                            <p class="fw-medium">Select a software to view software requests</p>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-bold">
                                    <i class="fas fa-ticket-alt me-2 text-purple"></i>Support Tickets
                                </h5>
                                <div>
                                    <button class="btn btn-outline-primary btn-sm me-2" id="export-tickets" title="Export Tickets">
                                        <i class="fas fa-file-excel me-1"></i> Export
                                    </button>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0" id="tickets-table">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="fw-semibold py-3">ID</th>
                                                <th class="fw-semibold py-3">Ticket ID</th>
                                                <th class="fw-semibold py-3">Subject</th>
                                                <th class="fw-semibold py-3">Description</th>
                                                <th class="fw-semibold py-3">Priority</th>
                                                <th class="fw-semibold py-3">Status</th>
                                                <th class="fw-semibold py-3">Created</th>
                                                <th class="fw-semibold py-3 text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tickets-body">
                                            <tr>
                                                <td colspan="8" class="text-center py-5 text-muted">
                                                    <i class="fas fa-ticket-alt fa-2x mb-3 opacity-25"></i>
                                                    <p class="fw-medium">No support tickets available</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-bold">
                                    <i class="fas fa-question-circle me-2 text-info"></i>Frequently Asked Questions
                                </h5>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addFaqModal">
                                    <i class="fas fa-plus me-1"></i> Add FAQ
                                </button>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0" id="faqs-table">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="fw-semibold py-3">Question</th>
                                                <th class="fw-semibold py-3">Answer</th>
                                                <th class="fw-semibold py-3 text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="faqs-body">
                                            <tr>
                                                <td colspan="5" class="text-center py-5 text-muted">
                                                    <i class="fas fa-question-circle fa-2x mb-3 opacity-25"></i>
                                                    <p class="fw-medium">No FAQs available</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-bold">
                                    <i class="fas fa-ad me-2 text-success"></i>Advertisements
                                </h5>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAdvertisementModal">
                                    <i class="fas fa-plus me-1"></i> Add Advertisement
                                </button>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0" id="advertisements-table">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="fw-semibold py-3">Title</th>
                                                <th class="fw-semibold py-3">Badge</th>
                                                <th class="fw-semibold py-3">Status</th>
                                                <th class="fw-semibold py-3">Dates</th>
                                                <th class="fw-semibold py-3 text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="advertisements-body">
                                            <tr>
                                                <td colspan="6" class="text-center py-5 text-muted">
                                                    <i class="fas fa-ad fa-2x mb-3 opacity-25"></i>
                                                    <p class="fw-medium">No advertisements available</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-bold">
                                    <i class="fas fa-mobile-alt me-2 text-success"></i>APK Management
                                </h5>
                                <button class="btn btn-primary btn-sm" onclick="uploadApkModal()">
                                    <i class="fas fa-plus me-1"></i> Upload APK
                                </button>
                            </div>
                            <div class="card-body">
                                <div id="apkInfoSection" class="d-none">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <h6 class="text-primary mb-3">APK Information</h6>
                                                    <div id="apkDetails" class="mt-2">
                                                    </div>
                                                </div>
                                                <div class="col-md-4 text-end">
                                                    <button class="btn btn-danger btn-sm" onclick="deleteApk()" id="deleteApkBtn">
                                                        <i class="fas fa-trash me-1"></i>Delete APK
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="noApkAvailable" class="text-center py-4">
                                    <i class="fas fa-mobile-alt fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No APK file uploaded for this software</p>
                                    <small class="text-muted">Click "Upload APK" to add an APK file</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </section>
    </div>
    <footer class="main-footer bg-white py-3 text-center border-top">
        <strong class="text-muted">&copy; 2025 <a href="#" class="text-decoration-none">Clikzop</a>. All rights reserved.</strong>
    </footer>
</div>

<div class="modal fade" id="changeSoftwareTypeModal" tabindex="-1" aria-labelledby="changeSoftwareTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning">
                <h5 class="modal-title fw-semibold text-dark" id="changeSoftwareTypeModalLabel">
                    <i class="fas fa-exchange-alt me-2"></i>Change Software Type
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label fw-medium">Current Software</label>
                    <input type="text" class="form-control" id="currentSoftwareName" readonly>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-medium">Current Type</label>
                    <input type="text" class="form-control" id="currentSoftwareType" readonly>
                </div>
                
                <div class="mb-4">
                    <label class="form-label fw-medium">New Software Type <span class="text-danger">*</span></label>
                    <select class="form-select" id="newSoftwareType" required>
                        <option value="">Select new type...</option>
                        <option value="real_state">Real Estate Management</option>
                        <option value="lead_management">Lead Management</option>
                        <option value="task_management">Task Management</option>
                        <option value="mis_management">MIS Management</option>
                    </select>
                </div>
                
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> This change will affect the entire software system. Please confirm you want to proceed.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" id="confirmTypeChange">
                    <i class="fas fa-sync-alt me-1"></i> Change Type
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addSoftwareModal" tabindex="-1" aria-labelledby="addSoftwareModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary">
                <h5 class="modal-title fw-semibold text-light" id="addSoftwareModalLabel">
                    <i class="fas fa-plus-circle me-2"></i>Add New Software
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('software.store') }}" method="POST" id="add-software-form">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-medium">Software Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" placeholder="Enter software name" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-medium">Software Type <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="type" name="type">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-medium">Software URL</label>
                        <input type="url" class="form-control" name="url" placeholder="https://example.com">
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> Add Software
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@include('modals.add-feature')
@include('modals.edit-feature')
@include('modals.add-trial')
@include('modals.edit-trial')
@include('modals.add-faq')
@include('modals.edit-faq')
@include('modals.edit-software')
@include('modals.edit-software-request')
@include('modals.add-advertisement')
@include('modals.edit-advertisement')
@include('modals.ticket-message')
@include('modals.upload-apk')
@endsection