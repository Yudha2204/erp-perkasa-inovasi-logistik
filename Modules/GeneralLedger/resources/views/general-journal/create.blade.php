@extends('layouts.app')
@push('styles')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endpush
@section('content')

<div class="main-content app-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">
            <!-- PAGE-HEADER -->
            <div class="page-header mb-0">
                <h1>General Journal</h1>
            </div>
            <h4 style="color: #015377">Add New</h4>
            <!-- PAGE-HEADER END -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <form action="{{ route('generalledger.general-journal.store') }}" method="POST">
                            @csrf
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
                                <div class="row">
                                    <div class="col-md-6">
                                <div class="form-group">
                                    <label for="journal_number">Journal Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('journal_number') is-invalid @enderror" 
                                           id="journal_number" name="journal_number" value="{{ old('journal_number', sprintf('GJ.%s-%02d-%04d', \Carbon\Carbon::now()->year, \Carbon\Carbon::now()->month, $latest_number)) }}" readonly required>
                                    @error('journal_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="date">Date <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control @error('date') is-invalid @enderror" 
                                                id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                                            @error('date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="currency_id">Currency <span class="text-danger">*</span></label>
                                            <select class="form-control @error('currency_id') is-invalid @enderror" 
                                                    id="currency_id" name="currency_id" required>
                                                <option value="">Select Currency</option>
                                                @foreach($currencies as $currency)
                                                    <option value="{{ $currency->id }}" {{ old('currency_id') == $currency->id ? 'selected' : '' }}>
                                                        {{ $currency->initial }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('currency_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                                    id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <h5>Journal Entries</h5>
                                        <div class="table-responsive">
                                            <table class="table" id="journal-entries">
                                                <thead>
                                                    <tr>
                                                        <th>Account</th>
                                                        <th>Description</th>
                                                        <th>Debit</th>
                                                        <th>Credit</th>
                                                        <th>Remark</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <select class="form-control select2 account-select" name="entries[0][account_id]" required style="width: 100%;">
                                                                <option value="">Select Account</option>
                                                                @foreach($accounts as $account)
                                                                    <option value="{{ $account->id }}" data-currency="{{ $account->master_currency_id }}">
                                                                        {{ $account->code }} - {{ $account->account_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="entries[0][description]">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control debit-input" name="entries[0][debit]" value="0">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control credit-input" name="entries[0][credit]" value="0">
                                                        </td>
                                                        <td>
                                                            <input type="text" class="form-control" name="entries[0][remark]">
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn remove-row text-danger">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="2"><strong>Total</strong></td>
                                                        <td><strong id="total-debit">0.00</strong></td>
                                                        <td><strong id="total-credit">0.00</strong></td>
                                                        <td colspan="2">
                                                            <button type="button" class="btn btn-success btn-sm" id="add-row">
                                                                <i class="fe fe-plus"></i> Add Row
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                        <input type="hidden" name="form_data" id="form_data">
                                    </div>
                                </div>

                                @if($errors->has('form_errors'))
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach($errors->get('form_errors') as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if($errors->has('balance'))
                                    <div class="alert alert-danger">
                                        {{ $errors->first('balance') }}
                                    </div>
                                @endif
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Save Journal</button>
                                <a href="{{ route('generalledger.general-journal.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ url('assets/plugins/select2/select2.full.min.js') }}"></script>
<script src="{{ url('assets/js/select2.js') }}"></script>
<script>
$(document).ready(function() {
    let rowIndex = 1;

    // Auto-generate journal number when date changes
    $('#date').on('change', function() {
        const date = $(this).val();
        generateJournalNumber(date);
    });

    function generateJournalNumber(date) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'GET',
            dataType: 'json',
            data: { 'date': date },
            url: '{{ route('generalledger.general-journal.transaction-number') }}',
            success: function(response) {
                if (response.message === "Success") {
                    const year = new Date(date).getFullYear();
                    const month = (new Date(date).getMonth() + 1).toString().padStart(2, '0');
                    const number = response.data.toString().padStart(4, '0');

                    $('#journal_number').val(`GJ.${year}-${month}-${number}`);
                }
            }
        });
    }

    // Add row
    $('#add-row').click(function() {
        const newRow = `
            <tr>
                <td>
                    <select class="form-control select2 account-select" name="entries[${rowIndex}][account_id]" required style="width: 100%;">
                        <option value="">Select Account</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}" data-currency="{{ $account->master_currency_id }}">
                                {{ $account->account_code }} - {{ $account->account_name }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="text" class="form-control" name="entries[${rowIndex}][description]">
                </td>
                <td>
                    <input type="text" class="form-control debit-input" name="entries[${rowIndex}][debit]" value="0">
                </td>
                <td>
                    <input type="text" class="form-control credit-input" name="entries[${rowIndex}][credit]" value="0">
                </td>
                <td>
                    <input type="text" class="form-control" name="entries[${rowIndex}][remark]">
                </td>
                <td>
                    <button type="button" class="btn remove-row text-danger">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        $('#journal-entries tbody').append(newRow);
        
        // Initialize Select2 on the newly added select element
        $('#journal-entries tbody tr:last .select2').select2({
            placeholder: 'Select Account',
            allowClear: true,
            width: '100%'
        });
        
        rowIndex++;
    });

    // Remove row
    $(document).on('click', '.remove-row', function() {
        $(this).closest('tr').remove();
        calculateTotals();
    });

    // Filter accounts by currency
    $('#currency_id').change(function() {
        const selectedCurrency = $(this).val();
        $('.account-select option').show();
        if (selectedCurrency) {
            $('.account-select option').each(function() {
                const currency = $(this).data('currency');
                if (currency && currency != selectedCurrency) {
                    $(this).hide();
                }
            });
        }
    });

    // Calculate totals
    function calculateTotals() {
        let totalDebit = 0;
        let totalCredit = 0;

        $('.debit-input').each(function() {
            const value = parseFloat($(this).val().replace(/,/g, '')) || 0;
            totalDebit += value;
        });

        $('.credit-input').each(function() {
            const value = parseFloat($(this).val().replace(/,/g, '')) || 0;
            totalCredit += value;
        });

        $('#total-debit').text(formatNumber(totalDebit));
        $('#total-credit').text(formatNumber(totalCredit));
    }

    // Format number with commas
    function formatNumber(num) {
        return num.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }

    // Update totals on input change
    $(document).on('input', '.debit-input, .credit-input', function() {
        calculateTotals();
    });

    // Form submission
    $('form').submit(function(e) {
        const formData = [];
        $('#journal-entries tbody tr').each(function() {
            const accountId = $(this).find('.account-select').val();
            const description = $(this).find('input[name*="[description]"]').val();
            const debit = $(this).find('.debit-input').val();
            const credit = $(this).find('.credit-input').val();
            const remark = $(this).find('input[name*="[remark]"]').val();

            if (accountId && (parseFloat(debit.replace(/,/g, '')) > 0 || parseFloat(credit.replace(/,/g, '')) > 0)) {
                formData.push({
                    account_id: accountId,
                    description: description,
                    debit: debit,
                    credit: credit,
                    remark: remark
                });
            }
        });

        $('#form_data').val(JSON.stringify(formData));
    });
});
</script>
@endpush
