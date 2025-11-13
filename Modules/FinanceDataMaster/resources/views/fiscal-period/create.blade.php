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

                            @if (session('error'))
                                <div class="alert alert-danger" role="alert">
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button>
                                    <strong>Error!</strong> {{ session('error') }}
                                </div>
                            @endif

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">Creation Mode <span class="text-danger">*</span></label>
                                        <div class="btn-group w-100" role="group">
                                            <input type="radio" class="btn-check" name="creation_mode" id="mode_single" value="single" checked>
                                            <label class="btn btn-outline-primary" for="mode_single">Single Period</label>
                                            
                                            <input type="radio" class="btn-check" name="creation_mode" id="mode_year" value="year">
                                            <label class="btn btn-outline-primary" for="mode_year">Whole Year</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Single Period Form -->
                            <form id="singlePeriodForm" action="{{ route('finance.master-data.fiscal-period.store') }}" method="POST" onsubmit="return validatePeriod()">
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
                                            <small class="form-text text-muted">
                                                Format: YYYY-MM (e.g., 2025-01)
                                                @if($startEntryPeriod)
                                                    <br><span class="text-warning"><i class="fe fe-alert-circle"></i> Period must be >= {{ $startEntryPeriod->format('F Y') }}</span>
                                                @endif
                                            </small>
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

                            <!-- Whole Year Form -->
                            <form id="yearForm" action="{{ route('finance.master-data.fiscal-period.store-year') }}" method="POST" style="display: none;">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="year" class="form-label">Year <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control @error('year') is-invalid @enderror" 
                                                id="year" name="year" 
                                                value="{{ old('year', date('Y')) }}" 
                                                min="2000" 
                                                max="2100" 
                                                required>
                                            <small class="form-text text-muted">
                                                @if($startEntryPeriod)
                                                    This will create periods for months >= {{ $startEntryPeriod->format('F Y') }} in the selected year
                                                @else
                                                    This will create all 12 months for the selected year
                                                @endif
                                            </small>
                                            @error('year')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="year_status" class="form-label">Status <span class="text-danger">*</span></label>
                                            <select class="form-control @error('status') is-invalid @enderror" 
                                                id="year_status" name="status" required>
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
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="year_notes" class="form-label">Notes</label>
                                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                                id="year_notes" name="notes" 
                                                rows="3" 
                                                placeholder="Optional notes (will be applied to all periods)">{{ old('notes') }}</textarea>
                                            @error('notes')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-info">
                                    <i class="fe fe-info me-2"></i>
                                    <strong>Note:</strong> This will create fiscal periods for the selected year, but only for months that are greater than or equal to the start entry period.
                                    @if($startEntryPeriod)
                                        <br><strong>Start Entry Period:</strong> {{ $startEntryPeriod->format('F d, Y') }}
                                        <br>Only periods from {{ $startEntryPeriod->format('F Y') }} onwards will be created.
                                    @else
                                        <br><strong>Note:</strong> No start entry period is configured. All 12 months will be created.
                                    @endif
                                    <br>If a period already exists, it will be skipped.
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <div class="btn-list text-end">
                                            <a href="{{ route('finance.master-data.fiscal-period.index') }}" class="btn btn-secondary">Cancel</a>
                                            <button type="submit" class="btn btn-primary">Create All Periods for Year</button>
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
@if($startEntryPeriod)
const startEntryPeriodDate = new Date('{{ $startEntryPeriod->format('Y-m-d') }}');
const startEntryPeriodFormatted = '{{ $startEntryPeriod->format('F Y') }}';
@endif

function validatePeriod() {
    @if($startEntryPeriod)
    const period = $('#period').val();
    if (period && period.match(/^\d{4}-\d{2}$/)) {
        const [year, month] = period.split('-');
        const startDate = new Date(year, month - 1, 1);
        
        if (startDate < startEntryPeriodDate) {
            alert('Cannot create fiscal period before the start entry period (' + startEntryPeriodFormatted + ').');
            $('#period').focus();
            return false;
        }
    }
    @endif
    return true;
}

$(document).ready(function() {
    // Toggle between single period and whole year forms
    $('input[name="creation_mode"]').on('change', function() {
        const mode = $(this).val();
        if (mode === 'single') {
            $('#singlePeriodForm').show();
            $('#yearForm').hide();
        } else {
            $('#singlePeriodForm').hide();
            $('#yearForm').show();
        }
    });

    // Auto-fill dates when period is entered
    $('#period').on('blur', function() {
        const period = $(this).val();
        if (period && period.match(/^\d{4}-\d{2}$/)) {
            const [year, month] = period.split('-');
            const startDate = new Date(year, month - 1, 1);
            const endDate = new Date(year, month, 0);
            
            $('#start_date').val(startDate.toISOString().split('T')[0]);
            $('#end_date').val(endDate.toISOString().split('T')[0]);
            
            // Validate against start entry period if exists
            @if($startEntryPeriod)
            if (startDate < startEntryPeriodDate) {
                $(this).addClass('is-invalid');
                const errorMsg = 'Period must be >= ' + startEntryPeriodFormatted;
                if ($(this).next('.invalid-feedback').length === 0) {
                    $(this).after('<div class="invalid-feedback">' + errorMsg + '</div>');
                } else {
                    $(this).next('.invalid-feedback').text(errorMsg);
                }
            } else {
                $(this).removeClass('is-invalid');
                $(this).next('.invalid-feedback').remove();
            }
            @endif
        }
    });
});
</script>
@endpush

