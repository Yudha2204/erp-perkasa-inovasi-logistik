@extends('layouts.app')
@section('content')

<div class="main-content app-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">
            <div class="page-header">
                <h1 style="color: #015377"><b>Exchange Rate</b></h1>
                <form action="{{ route('finance.exchange-rate.index') }}">
                    <input type="text" id="date_exchange" class="form-control" name="date" value="{{ Request::get('date') ?? \Carbon\Carbon::now()->format('Y-m-d') }}" style="border-color: #E8F4FE; border-width: 2px">
                </form>
            </div>
            <!-- PAGE-HEADER END -->
            
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
    </script>
@endpush