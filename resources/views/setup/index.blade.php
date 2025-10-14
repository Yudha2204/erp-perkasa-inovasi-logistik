@extends('layouts.app')
@section('title', 'Setup - Company Configuration')

@section('content')
<div class="main-content app-content mt-0">
    <div class="side-app">
        <!-- CONTAINER -->
        <div class="main-container container-fluid">
            <div class="pt-5">
                <div class="">
                    <h1 style="font-size: 36px; font-weight: bold; color: #015377;">Company Setup</h1>
                    <p class="text-muted">Configure your company information and system settings</p>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($setup)
                        <!-- Display existing setup -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Current Setup</h3>
                                <div class="card-options">
                                    <a href="{{ route('setup.edit', $setup->id) }}" class="btn btn-primary btn-sm">
                                        <i class="fe fe-edit"></i> Edit Setup
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Company Name:</label>
                                            <p class="form-control-plaintext">{{ $setup->company_name }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Company Address:</label>
                                            <p class="form-control-plaintext">{{ $setup->company_address }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Company Phone:</label>
                                            <p class="form-control-plaintext">{{ $setup->company_phone }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Company Email:</label>
                                            <p class="form-control-plaintext">{{ $setup->company_email }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Start Entry Period:</label>
                                            <p class="form-control-plaintext">{{ $setup->start_entry_period->format('F d, Y') }}</p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">Company Logo:</label>
                                            @if($setup->company_logo)
                                                <div class="mt-2">
                                                    <img src="{{ asset('storage/' . $setup->company_logo) }}" 
                                                         alt="Company Logo" 
                                                         class="img-thumbnail" 
                                                         style="max-width: 200px; max-height: 200px;">
                                                </div>
                                            @else
                                                <p class="text-muted">No logo uploaded</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- No setup found, show create button -->
                        <div class="card">
                            <div class="card-body text-center">
                                <div class="mb-4">
                                    <i class="fe fe-settings" style="font-size: 64px; color: #6c757d;"></i>
                                </div>
                                <h4>No Setup Configuration Found</h4>
                                <p class="text-muted">You need to configure your company setup before using the system.</p>
                                <a href="{{ route('setup.create') }}" class="btn btn-primary btn-lg">
                                    <i class="fe fe-plus"></i> Create Setup
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- CONTAINER CLOSED -->
    </div>
</div>
@endsection
