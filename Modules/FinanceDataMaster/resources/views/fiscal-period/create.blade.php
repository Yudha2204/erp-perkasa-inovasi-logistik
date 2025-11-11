@extends('layouts.app')
@section('content')

<div class="main-content app-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <!-- PAGE-HEADER -->
            <div class="page-header">
                <h1>Create Fiscal Period</h1>
                <a href="{{ route('finance.master-data.fiscal-period.index') }}" class="btn btn-secondary">
                    <i class="fe fe-arrow-left me-2"></i>Back to List
                </a>
            </div>
            <!-- PAGE-HEADER END -->

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Fiscal Period Information</h3>
                        </div>
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger" role="alert">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button>
                                    <strong>Whoops!</strong>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('finance.master-data.fiscal-period.store') }}" method="POST">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="period" class="form-label">Period <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('period') is-invalid @enderror" 
                                                id="period" name="period" 
                                                value="{{ old('period') }}" 
                                                placeholder="YYYY-MM (e.g., 2025-01)" 
                                                required>
                                            <small class="form-text text-muted">Format: YYYY-MM (e.g., 2025-01)</small>
                                            @error('period')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                            <select class="form-control @error('status') is-invalid @enderror" 
                                                id="status" name="status" required>
                                                <option value="open" {{ old('status') == 'open' ? 'selected' : '' }}>Open</option>
                                                <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                                                id="start_date" name="start_date" 
                                                value="{{ old('start_date') }}" 
                                                required>
                                            @error('start_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                                                id="end_date" name="end_date" 
                                                value="{{ old('end_date') }}" 
                                                required>
                                            @error('end_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="notes" class="form-label">Notes</label>
                                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                                id="notes" name="notes" 
                                                rows="3" 
                                                placeholder="Optional notes about this fiscal period">{{ old('notes') }}</textarea>
                                            @error('notes')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <div class="btn-list text-end">
                                            <a href="{{ route('finance.master-data.fiscal-period.index') }}" class="btn btn-secondary">Cancel</a>
                                            <button type="submit" class="btn btn-primary">Create Fiscal Period</button>
                                        </div>
                                    </div>
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
$(document).ready(function() {
    // Auto-fill dates when period is entered
    $('#period').on('blur', function() {
        const period = $(this).val();
        if (period && period.match(/^\d{4}-\d{2}$/)) {
            const [year, month] = period.split('-');
            const startDate = new Date(year, month - 1, 1);
            const endDate = new Date(year, month, 0);
            
            $('#start_date').val(startDate.toISOString().split('T')[0]);
            $('#end_date').val(endDate.toISOString().split('T')[0]);
        }
    });
});
</script>
@endpush

