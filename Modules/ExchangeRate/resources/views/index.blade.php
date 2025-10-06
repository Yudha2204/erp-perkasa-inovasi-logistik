@extends('layouts.app')
@section('content')

@push('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

<div class="main-content app-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">
            <div class="page-header">
                <h1 style="color: #015377"><b>Exchange Rate</b></h1>
            </div>

            <!-- Bulk Exchange Rate Section -->
            <div class="row mb-4">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title" style="color: #015377"><b>Bulk Exchange Rate - Multi Currency</b></h3>
                            <div class="card-options">
                                <span class="badge bg-info">
                                    <i class="fa fa-shield"></i> Bidirectional Protection Active
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('finance.exchange-rate.bulk-store') }}" method="POST" id="bulk-exchange-form">
                                @csrf
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" id="csrf-token-input">

                                <!-- Information Section -->
                                <div class="alert alert-info">
                                    <h6><i class="fa fa-info-circle"></i> Bidirectional Protection</h6>
                                    <p class="mb-0">
                                        The system automatically prevents duplicate currency pairs in both directions.
                                        For example, if you have "USD → EUR", you cannot add "EUR → USD" for the same date.
                                        This ensures data consistency and prevents conflicting exchange rates.
                                    </p>
                                </div>

                                <!-- Date Range Section -->
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Start Date</label>
                                            <input type="text" class="form-control" id="bulk_start_date" name="bulk_start_date" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>End Date</label>
                                            <input type="text" class="form-control" id="bulk_end_date" name="bulk_end_date" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <div>
                                                <button type="button" class="btn btn-primary" id="add-currency-pair">
                                                    <i class="fa fa-plus"></i> Add Currency Pair
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Currency Pairs Section -->
                                <div id="currency-pairs-container">
                                    <!-- First currency pair -->
                                    <div class="currency-pair-row mb-3" data-pair-index="0">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>From Currency</label>
                                                    <select class="form-control select2 bulk-from-currency" name="currency_pairs[0][from_currency]" onchange="updateBulkToCurrency(this)" required>
                                                        <option value="">Choose One</option>
                                                        @foreach ($currencies as $c)
                                                        <option value="{{$c->id}}">{{$c->initial}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>From Nominal</label>
                                                    <input type="text" class="form-control" name="currency_pairs[0][from_nominal]" placeholder="Enter amount" required>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>To Currency</label>
                                                    <select class="form-control select2 bulk-to-currency" name="currency_pairs[0][to_currency]" required>
                                                        <option value="">Choose One</option>
                                                        @foreach ($currencies as $c)
                                                        <option value="{{$c->id}}">{{$c->initial}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>To Nominal</label>
                                                    <input type="text" class="form-control" name="currency_pairs[0][to_nominal]" placeholder="Enter amount" required>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>&nbsp;</label>
                                                    <div>
                                                        <button type="button" class="btn btn-danger btn-sm remove-currency-pair" onclick="removeCurrencyPair(this)">
                                                            <i class="fa fa-trash"></i> Remove
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="row">
                                    <div class="col-md-12 text-end">
                                        <button type="submit" class="btn btn-success btn-lg">
                                            <i class="fa fa-plus"></i> Bulk Insert All Currency Pairs
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- PAGE-HEADER END -->
            <form class="mb-4" action="{{ route('finance.exchange-rate.index') }}">
                <div class="d-flex justify-content-center align-items-center">
                    <label for="date_exchange" class="me-2 mb-0 fw-bold" style="font-size: 1.1rem;">Filter</label>
                    <input type="text" id="date_exchange" class="form-control" name="date" value="{{ Request::get('date') ?? \Carbon\Carbon::now()->format('Y-m-d') }}" style="border-color: #E8F4FE; border-width: 2px; max-width: 200px;">
                </div>
            </form>

            <form action="{{ route('finance.exchange-rate.store') }}" method="POST" enctype="multipart/form-data" name="dynamic-form">
            @csrf
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
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
                            <div class="table-responsive">
                                <table class="table text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th style="min-width:10rem; text-align:center">Mata Uang</th>
                                            <th style="min-width:15rem; text-align:center">Nominal</th>
                                            <th style="min-width:5rem; text-align:center"></th>
                                            <th style="min-width:10rem; text-align:center">Mata Uang</th>
                                            <th style="min-width:15rem; text-align:center">Nominal</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody id="form-container">
                                        @foreach ($exchangeRate as $e)
                                            <tr class="form-wrapper group-form">
                                                <td></td>
                                                <td>
                                                    <select class="form-control select2 form-select" data-placeholder="Choose One" name="from_currency" onchange="changeCurrency(this, 'from'); changeOperation(this)" disabled>
                                                        @foreach ($currencies as $c)
                                                        <option value="{{$c->id}}" {{ $c->id === $e->from_currency_id ? "selected" : "" }}>{{$c->initial}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control total-input text-center" name="from_nominal" value="{{ number_format($e->from_nominal,2,'.',',') }}" onchange="changeOperation(this); changeFormat(this)" />
                                                </td>
                                                <td>
                                                    <input disabled type="text" value="To" class="form-control" style="background: none; border: 0px; text-align: center">
                                                </td>
                                                <td>
                                                    <select class="form-control select2 form-select" data-placeholder="Choose One" name="to_currency" onchange="changeOperation(this)" disabled>
                                                        @foreach ($currencies as $c)
                                                        @if(!($c->id === $e->from_currency_id))
                                                        <option value="{{$c->id}}" {{ $c->id === $e->to_currency_id ? "selected" : "" }}>{{$c->initial}}</option>
                                                        @endif
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control total-input text-center" name="to_nominal" value="{{ number_format($e->to_nominal,2,'.',',') }}" onchange="changeOperation(this); changeFormat(this)"/>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-between">
                                                        <button type="button" class="btn" onclick="deleteList(this)">
                                                        <i class="fa fa-trash text-danger delete-form"></i></button>
                                                        <input name="operator" hidden value="nothing:{{$e->id}}">
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="row mt-2">
                                <a href="javascript:void(0)" class="btn btn-default"
                                    id="add-form">
                                    <span><i class="fa fa-plus"></i></span> Add New Column
                                </a>
                            </div>
                            <br><br><br>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <div class="btn-list text-end">
                                            <button id="submit-all-form" type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- PAGE-HEADER -->
            </form>
        </div>
        <!-- CONTAINER CLOSED -->
    </div>
</div>

@endsection
@push('styles')
<style>
/* Fix for datepicker shaking on hover */
.ui-datepicker .ui-datepicker-header .ui-datepicker-prev-hover,
.ui-datepicker .ui-datepicker-header .ui-datepicker-next-hover {
    top: 1px !important;
    left: 5px !important;
    right: 5px !important;
    position: relative !important;
}

.ui-datepicker .ui-datepicker-header .ui-datepicker-prev:hover,
.ui-datepicker .ui-datepicker-header .ui-datepicker-next:hover {
    top: 1px !important;
    left: 5px !important;
    right: 5px !important;
    position: relative !important;
}

/* Fix for datepicker date cells shaking */
.ui-datepicker .ui-datepicker-calendar td {
    position: relative !important;
    border: 1px solid #eceef9 !important;
    padding: 0 !important;
    background-color: #eceef9 !important;
    text-align: right !important;
}

.ui-datepicker .ui-datepicker-calendar td a {
    transition: background-color 0.2s ease, color 0.2s ease !important;
    transform: none !important;
    position: relative !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    bottom: 0 !important;
    margin: 0 !important;
    padding: 6px 10px !important;
    display: block !important;
    border: 0 !important;
    border-radius: 1px !important;
}

.ui-datepicker .ui-datepicker-calendar td a:hover,
.ui-datepicker .ui-datepicker-calendar td a.ui-state-hover {
    transform: none !important;
    position: relative !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    bottom: 0 !important;
    margin: 0 !important;
    transition: background-color 0.2s ease, color 0.2s ease !important;
    background-color: #f0f2f7 !important;
    color: #473b52 !important;
}

/* Fix for ui-state-hover class that gets added dynamically */
.ui-datepicker .ui-datepicker-calendar td a.ui-state-hover {
    transform: none !important;
    position: relative !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    bottom: 0 !important;
    margin: 0 !important;
    background-color: #f0f2f7 !important;
    color: #473b52 !important;
}

/* Ensure no position changes on any hover state */
.ui-datepicker .ui-datepicker-calendar td:hover a,
.ui-datepicker .ui-datepicker-calendar td a:focus,
.ui-datepicker .ui-datepicker-calendar td a:active {
    transform: none !important;
    position: relative !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    bottom: 0 !important;
    margin: 0 !important;
}

/* Selected date styling */
.ui-datepicker .ui-datepicker-calendar td.ui-datepicker-current-day a,
.ui-datepicker .ui-datepicker-calendar td.ui-datepicker-current-day a:hover,
.ui-datepicker .ui-datepicker-calendar td.ui-datepicker-current-day a.ui-state-hover {
    background-color: #007bff !important;
    color: #ffffff !important;
    font-weight: bold !important;
    transform: none !important;
    position: relative !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    bottom: 0 !important;
    margin: 0 !important;
}

/* Today's date styling */
.ui-datepicker .ui-datepicker-calendar td.ui-datepicker-today a {
    background-color: #f8f9fa !important;
    color: #473b52 !important;
    font-weight: 500 !important;
    transform: none !important;
    position: relative !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    bottom: 0 !important;
    margin: 0 !important;
}

.ui-datepicker .ui-datepicker-calendar td.ui-datepicker-today a:hover,
.ui-datepicker .ui-datepicker-calendar td.ui-datepicker-today a.ui-state-hover {
    background-color: #e9ecef !important;
    color: #473b52 !important;
    transform: none !important;
    position: relative !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    bottom: 0 !important;
    margin: 0 !important;
}
</style>
@endpush

@push('scripts')
    <!-- SELECT2 JS -->
    <script src="{{ url('assets/plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ url('assets/js/select2.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

    <script>
        $(function () {
            const defaultDate = "{{ Request::get('date') ?? \Carbon\Carbon::now()->format('Y-m-d') }}"
            $("#date_exchange").datepicker({
                dateFormat: "yy-mm-dd",
                defaultDate: defaultDate,
                onSelect: function() {
                    $(this).closest('form').submit()
                }
            });

            // Initialize bulk date pickers
            $("#bulk_start_date").datepicker({
                dateFormat: "yy-mm-dd",
                defaultDate: defaultDate
            });

            $("#bulk_end_date").datepicker({
                dateFormat: "yy-mm-dd",
                defaultDate: defaultDate
            });
        })

        function changeOperation(event) {
            var row = event.closest('tr');
            var operationInput = row.querySelector('input[name="operator"]');
            var splitOperation = operationInput.value.split(":")
            if(splitOperation[0] === 'nothing') {
                operationInput.value = 'update:' + splitOperation[1];
            }
        }

        function deleteList(event) {
            var row = event.closest('tr')
            var operationInput = row.querySelector('input[name="operator"]');
            var splitOperation = operationInput.value.split(":")
            if(splitOperation[0] !== 'create') {
                operationInput.value = 'delete:' + splitOperation[1];
                row.style["display"] = "none";
            } else {
                row.remove()
            }
        }

        function changeFormat(event) {
            let value = event.value
            value = parseFloat(value.replace(/,/g, '')) || 0
            event.value = Number(value).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
        }

        document.getElementById('submit-all-form').addEventListener('click', function() {
            var forms = document.querySelectorAll('.form-wrapper');
            var formData = [];

            forms.forEach(function(form) {
                var formDataObj = {};
                form.querySelectorAll('input, select').forEach(function(input) {
                    formDataObj[input.name] = input.value;
                });
                formData.push(formDataObj);
            });

            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'form_data');
            hiddenInput.setAttribute('value', JSON.stringify(formData));
            var dateInput = document.createElement('input');
            dateInput.setAttribute('type', 'hidden');
            dateInput.setAttribute('name', 'date');
            var date = document.querySelector('input[name="date"]').value;
            dateInput.setAttribute('value', date);
            document.querySelector('form[name="dynamic-form"]').appendChild(hiddenInput);
            document.querySelector('form[name="dynamic-form"]').appendChild(dateInput);

            document.forms['dynamic-form'].submit();
        });

        document.getElementById('add-form').addEventListener('click', function() {
            var formContainer = document.getElementById('form-container');
            var newFormWrapper = document.createElement('tr');
            newFormWrapper.classList.add('form-wrapper');
            newFormWrapper.classList.add('group-form');

            var formTemplate = `
            <td></td>
            <td>
                <select class="form-control select2 form-select" data-placeholder="Choose One" name="from_currency" onchange="changeCurrency(this, 'from')">
                    <option label="Choose One"></option>
                    @foreach ($currencies as $c)
                    <option value="{{$c->id}}">{{$c->initial}}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="text" class="form-control total-input text-center" name="from_nominal" onchange="changeFormat(this)" />
            </td>
            <td>
                <input disabled type="text" value="To" class="form-control" style="background: none; border: 0px; text-align: center">
            </td>
            <td>
                <select class="form-control select2 form-select" data-placeholder="Choose One" name="to_currency">
                    <option label="Choose One"></option>
                </select>
            </td>
            <td>
                <input type="text" class="form-control total-input text-center" name="to_nominal" onchange="changeFormat(this)" />
            </td>
            <td>
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn" onclick="deleteList(this)">
                    <i class="fa fa-trash text-danger delete-form"></i></button>
                    <input name="operator" hidden value="create:0">
                </div>
            </td>
            `;

            newFormWrapper.innerHTML = formTemplate;
            formContainer.appendChild(newFormWrapper);

            $('.select2').select2({
                minimumResultsForSearch: Infinity
            });
            var select2Elements = document.querySelectorAll('.select2');
            select2Elements.forEach(function(element) {
                element.style.width = '100%';
            });
        });

        function changeCurrency(element, type) {
            const currencyFromId = element.value
            const tr = element.closest('tr')
            let currencySelect = tr.querySelector('select[name="to_currency"]')
            currencySelect.innerHTML = ""

            const allCurrency = {!! json_encode($currencies) !!}
            allCurrency.forEach(currency => {
                if(currency.id != currencyFromId) {
                    const option = document.createElement('option');
                    option.value = currency.id;
                    option.textContent = currency.initial;
                    currencySelect.appendChild(option);
                }
            })
        }

        // Multi-currency pair management
        let currencyPairIndex = 0;

        // Add currency pair
        document.getElementById('add-currency-pair').addEventListener('click', function() {
            currencyPairIndex++;
            const container = document.getElementById('currency-pairs-container');
            const newPairRow = document.createElement('div');
            newPairRow.classList.add('currency-pair-row', 'mb-3');
            newPairRow.setAttribute('data-pair-index', currencyPairIndex);

            newPairRow.innerHTML = `
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>From Currency</label>
                            <select class="form-control select2 bulk-from-currency" name="currency_pairs[${currencyPairIndex}][from_currency]" onchange="updateBulkToCurrency(this)" required>
                                <option value="">Choose One</option>
                                @foreach ($currencies as $c)
                                <option value="{{$c->id}}">{{$c->initial}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>From Nominal</label>
                            <input type="text" class="form-control" name="currency_pairs[${currencyPairIndex}][from_nominal]" placeholder="Enter amount" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>To Currency</label>
                            <select class="form-control select2 bulk-to-currency" name="currency_pairs[${currencyPairIndex}][to_currency]" required>
                                <option value="">Choose One</option>
                                @foreach ($currencies as $c)
                                <option value="{{$c->id}}">{{$c->initial}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>To Nominal</label>
                            <input type="text" class="form-control" name="currency_pairs[${currencyPairIndex}][to_nominal]" placeholder="Enter amount" required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div>
                                <button type="button" class="btn btn-danger btn-sm remove-currency-pair" onclick="removeCurrencyPair(this)">
                                    <i class="fa fa-trash"></i> Remove
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            container.appendChild(newPairRow);

            // Initialize select2 for new elements
            $('.select2').select2({
                minimumResultsForSearch: Infinity
            });

            // Add event listener for currency changes in new pair
            const newFromCurrency = newPairRow.querySelector('.bulk-from-currency');
            const newToCurrency = newPairRow.querySelector('.bulk-to-currency');

            newFromCurrency.addEventListener('change', function() {
                updateBulkToCurrency(this);
            });

            newToCurrency.addEventListener('change', function() {
                // No automatic checking - only check on submit
            });
        });

        // Remove currency pair
        function removeCurrencyPair(button) {
            const pairRow = button.closest('.currency-pair-row');
            const container = document.getElementById('currency-pairs-container');

            // Don't allow removing the last pair
            if (container.children.length <= 1) {
                alert('At least one currency pair is required!');
                return;
            }

            pairRow.remove();

            // No automatic checking - only check on submit
        }

        // Function to update bulk "To Currency" dropdown
        function updateBulkToCurrency(selectElement) {
            const fromCurrencyId = selectElement.value;
            const pairRow = selectElement.closest('.currency-pair-row');
            const toCurrencySelect = pairRow.querySelector('.bulk-to-currency');

            // Clear existing options
            toCurrencySelect.innerHTML = '<option value="">Choose One</option>';

            const allCurrency = {!! json_encode($currencies) !!};
            allCurrency.forEach(currency => {
                if(currency.id != fromCurrencyId) {
                    const option = document.createElement('option');
                    option.value = currency.id;
                    option.textContent = currency.initial;
                    toCurrencySelect.appendChild(option);
                }
            });

            // Reinitialize select2
            $(toCurrencySelect).select2({
                minimumResultsForSearch: Infinity
            });

            // No automatic checking - only check on submit
        }

        // No real-time checking - only check on submit button press

        // No automatic checking - only check on submit

        // Add event listeners for initial currency pair
        document.addEventListener('DOMContentLoaded', function() {
            const initialFromCurrency = document.querySelector('.bulk-from-currency');
            const initialToCurrency = document.querySelector('.bulk-to-currency');

            if (initialFromCurrency) {
                initialFromCurrency.addEventListener('change', function() {
                    updateBulkToCurrency(this);
                });
            }

            if (initialToCurrency) {
                initialToCurrency.addEventListener('change', function() {
                    // No automatic checking - only check on submit
                });
            }
        });

        // Bulk form submission handler
        document.getElementById('bulk-exchange-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const startDate = formData.get('bulk_start_date');
            const endDate = formData.get('bulk_end_date');

            // Validation
            if (new Date(startDate) > new Date(endDate)) {
                alert('Start Date cannot be greater than End Date!');
                return;
            }

            // Get all currency pairs
            const currencyPairs = [];
            const pairRows = document.querySelectorAll('.currency-pair-row');

            for (let i = 0; i < pairRows.length; i++) {
                const row = pairRows[i];
                const fromCurrency = row.querySelector('.bulk-from-currency').value;
                const toCurrency = row.querySelector('.bulk-to-currency').value;
                const fromNominal = row.querySelector('input[name*="[from_nominal]"]').value;
                const toNominal = row.querySelector('input[name*="[to_nominal]"]').value;

                // Validate each pair
                if (!fromCurrency || !toCurrency || !fromNominal || !toNominal) {
                    alert(`Please fill all fields in currency pair ${i + 1}!`);
                    return;
                }

                if (fromCurrency === toCurrency) {
                    alert(`From Currency and To Currency cannot be the same in pair ${i + 1}!`);
                    return;
                }

                // Check for duplicate pairs (bidirectional)
                const pairKey = `${fromCurrency}-${toCurrency}`;
                const reversePairKey = `${toCurrency}-${fromCurrency}`;
                if (currencyPairs.includes(pairKey) || currencyPairs.includes(reversePairKey)) {
                    alert(`Duplicate currency pair detected!\n\nPair ${i + 1}: ${fromCurrency} → ${toCurrency}\nThis conflicts with an existing pair in the same direction or reverse direction.\n\nPlease remove duplicate pairs.`);
                    return;
                }

                currencyPairs.push(pairKey);
            }

            // Now check for existing rates before showing confirmation
            checkExistingRatesForSubmission(startDate, endDate, currencyPairs, pairRows, this);
        });

        // Function to check existing rates only when submit button is pressed
        function checkExistingRatesForSubmission(startDate, endDate, currencyPairs, pairRows, formElement) {
            // Show loading indicator
            const submitBtn = document.querySelector('#bulk-exchange-form button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Checking existing rates...';
            submitBtn.disabled = true;

            // Make API call to check existing rates with timeout
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 10000); // 10 second timeout

            // Get CSRF token with fallbacks
            const csrfTokenInput = document.getElementById('csrf-token-input');
            const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfTokenHidden = document.querySelector('input[name="_token"]');

            const csrfToken = csrfTokenInput?.value ||
                             csrfTokenMeta?.getAttribute('content') ||
                             csrfTokenHidden?.value ||
                             '{{ csrf_token() }}';

            console.log('CSRF Token sources:', {
                input: csrfTokenInput?.value,
                meta: csrfTokenMeta?.getAttribute('content'),
                hidden: csrfTokenHidden?.value,
                fallback: '{{ csrf_token() }}',
                final: csrfToken
            });

            fetch('{{ route("finance.exchange-rate.check-existing") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    start_date: startDate,
                    end_date: endDate,
                    currency_pairs: currencyPairs
                }),
                signal: controller.signal
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                // Clear timeout
                clearTimeout(timeoutId);

                // Reset button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;

                // Show confirmation with existing rates info
                const daysDiff = Math.ceil((new Date(endDate) - new Date(startDate)) / (1000 * 60 * 60 * 24)) + 1;
                const totalRecords = daysDiff * pairRows.length;

                let confirmMessage = `This will create exchange rate records from ${startDate} to ${endDate}.\n\n`;

                if (data.has_conflicts && data.existing_rates.length > 0) {
                    const newRecords = totalRecords - data.existing_rates.length;
                    confirmMessage += `• ${newRecords} new records will be created\n`;
                    confirmMessage += `• ${data.existing_rates.length} existing records will be skipped\n\n`;
                    confirmMessage += `Existing rates found:\n`;

                    // Show first few existing rates
                    data.existing_rates.slice(0, 3).forEach(rate => {
                        const directionText = rate.direction === 'same' ? 'same direction' : 'reverse direction';
                        confirmMessage += `• ${rate.date}: Currency pair already exists (${directionText})\n`;
                    });

                    if (data.existing_rates.length > 3) {
                        confirmMessage += `• ... and ${data.existing_rates.length - 3} more\n`;
                    }

                    confirmMessage += `\nDo you want to continue?`;
                } else {
                    confirmMessage += `• ${totalRecords} records will be created (${pairRows.length} currency pairs × ${daysDiff} days)\n\n`;
                    confirmMessage += `Do you want to continue?`;
                }

                if (confirm(confirmMessage)) {
                    formElement.submit();
                }
            })
            .catch(error => {
                // Clear timeout
                clearTimeout(timeoutId);

                console.error('Error checking existing rates:', error);

                // Reset button on error
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;

                // Show user-friendly error message and allow submission
                let errorMessage = 'Unable to check existing rates. ';
                if (error.name === 'AbortError') {
                    errorMessage += 'Request timed out. ';
                }
                errorMessage += 'Do you want to proceed with the bulk insert anyway?';

                if (confirm(errorMessage)) {
                    formElement.submit();
                }
            });
        }
    </script>
@endpush
