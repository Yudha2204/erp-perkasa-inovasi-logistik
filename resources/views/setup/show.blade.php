@extends('layouts.app')
@section('title', 'View Setup - Company Configuration')

@section('content')
<div class="main-content app-content mt-0">
    <div class="side-app">
        <!-- CONTAINER -->
        <div class="main-container container-fluid">
            <div class="pt-5">
                <div class="">
                    <h1 style="font-size: 36px; font-weight: bold; color: #015377;">Company Setup Details</h1>
                    <p class="text-muted">View your company configuration details</p>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Setup Information</h3>
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
                                        <label class="form-label fw-bold">Company Phone:</label>
                                        <p class="form-control-plaintext">{{ $setup->company_phone }}</p>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Start Entry Period:</label>
                                        <p class="form-control-plaintext">{{ $setup->start_entry_period->format('F d, Y') }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Company Email:</label>
                                        <p class="form-control-plaintext">{{ $setup->company_email }}</p>
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
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Company Address:</label>
                                <p class="form-control-plaintext">{{ $setup->company_address }}</p>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('setup.index') }}" class="btn btn-secondary">
                                    <i class="fe fe-arrow-left"></i> Back to Setup
                                </a>
                                <a href="{{ route('setup.edit', $setup->id) }}" class="btn btn-primary">
                                    <i class="fe fe-edit"></i> Edit Setup
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- CONTAINER CLOSED -->
    </div>
</div>
@endsection
