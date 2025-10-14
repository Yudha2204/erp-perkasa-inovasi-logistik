@extends('layouts.app')
@section('title', 'Create Setup - Company Configuration')

@section('content')
<div class="main-content app-content mt-0">
    <div class="side-app">
        <!-- CONTAINER -->
        <div class="main-container container-fluid">
            <div class="pt-5">
                <div class="">
                    <h1 style="font-size: 36px; font-weight: bold; color: #015377;">Create Company Setup</h1>
                    <p class="text-muted">Configure your company information and system settings</p>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Setup Information</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('setup.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="company_name" class="form-label">Company Name <span class="text-danger">*</span></label>
                                            <input type="text" 
                                                   class="form-control @error('company_name') is-invalid @enderror" 
                                                   id="company_name" 
                                                   name="company_name" 
                                                   value="{{ old('company_name') }}" 
                                                   required>
                                            @error('company_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="company_phone" class="form-label">Company Phone <span class="text-danger">*</span></label>
                                            <input type="text" 
                                                   class="form-control @error('company_phone') is-invalid @enderror" 
                                                   id="company_phone" 
                                                   name="company_phone" 
                                                   value="{{ old('company_phone') }}" 
                                                   required>
                                            @error('company_phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="start_entry_period" class="form-label">Start Entry Period <span class="text-danger">*</span></label>
                                            <input type="date" 
                                                   class="form-control @error('start_entry_period') is-invalid @enderror" 
                                                   id="start_entry_period" 
                                                   name="start_entry_period" 
                                                   value="{{ old('start_entry_period') }}" 
                                                   required>
                                            @error('start_entry_period')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="company_email" class="form-label">Company Email <span class="text-danger">*</span></label>
                                            <input type="email" 
                                                   class="form-control @error('company_email') is-invalid @enderror" 
                                                   id="company_email" 
                                                   name="company_email" 
                                                   value="{{ old('company_email') }}" 
                                                   required>
                                            @error('company_email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="company_logo" class="form-label">Company Logo <span class="text-danger">*</span></label>
                                            <input type="file" 
                                                   class="form-control @error('company_logo') is-invalid @enderror" 
                                                   id="company_logo" 
                                                   name="company_logo" 
                                                   accept="image/*" 
                                                   onchange="previewImage(event)" 
                                                   required>
                                            @error('company_logo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="mt-2">
                                                <img id="logo_preview" style="max-width: 200px; max-height: 200px; display: none;" class="img-thumbnail">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="company_address" class="form-label">Company Address <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('company_address') is-invalid @enderror" 
                                              id="company_address" 
                                              name="company_address" 
                                              rows="3" 
                                              required>{{ old('company_address') }}</textarea>
                                    @error('company_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('setup.index') }}" class="btn btn-secondary">
                                        <i class="fe fe-arrow-left"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fe fe-save"></i> Create Setup
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- CONTAINER CLOSED -->
    </div>
</div>
@endsection

@push('scripts')
<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('logo_preview');
            output.src = reader.result;
            output.style.display = 'block';
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endpush
