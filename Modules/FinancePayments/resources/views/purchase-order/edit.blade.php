@extends('layouts.app')
@section('content')
@push('styles')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endpush

<div class="main-content app-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">
        <form action="{{ route('finance.payments.account-payable.update',  $order->id) }}" method="POST" enctype="multipart/form-data" name="dynamic-form">
            @csrf
            @method('PUT')
            <!-- PAGE-HEADER -->
            <div class="page-header mb-0">
                <h1>Account Payable</h1>
            </div>
            <h4 style="color: #015377">Edit</h4>
            <!-- PAGE-HEADER END -->

            <div class="row">
                <div class="col-md-12">
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
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Vendor</label>
                                        <select class="form-control select2 form-select" data-placeholder="Choose One" name="vendor_id" id="vendor_id">
                                            @foreach ($vendor as $v)
                                                <option value="{{ $v->id }}" {{ $v->id === $order->vendor_id ? "selected" : "" }}>{{ $v->customer_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Customer</label>
                                        <select class="form-control select2 form-select" data-placeholder="Choose One" name="customer_id" id="customer_id">
                                            <option value="null">No Customer</option>
                                            @foreach ($customer as $c)
                                                <option value="{{ $c->id }}" {{ $order->customer_id === $c->id ? "selected" : "" }}>{{ $c->customer_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="no-transaction" class="form-label">Nomor Transaksi</label>
                                        <input type="text" name="no_transaction" id="no-transaction" class="form-control" placeholder="Input Transaction" value="{{ $order->transaction }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" class="form-label">Date</label>
                                        <input type="date" class="form-control" name="date_order" id="date_order" value="{{ $order->date_order }}" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" class="form-label">Currency</label>
                                        <select class="form-control select2 form-select" data-placeholder="Choose One" name="currency_id" id="currency_id">
                                            @foreach ($currencies as $c)
                                                <option value="{{ $c->id }}" {{ $c->id === $order->currency_id ? "selected" : "" }}>{{ $c->initial }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="des_head_order" class="form-label">Description</label>
                                        <input type="text" class="form-control" name="des_head_order" id="des_head_order" placeholder="Desc" value="{{ $order->description }}" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="coa_ap" class="form-label">Account Name</label>
                                        <select class="form-control select2 form-select" data-placeholder="Choose One" name="coa_ap" id="coa_ap">
                                            @foreach($coa_ap as $account)
                                                <option value="{{ $account->id }}" {{ $account->id == $order->account_id ? 'selected' : '' }}>{{ $account->account_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="custom-control custom-radio">
                                        <input type="checkbox" id="choose_job_order" name="choose_job_order" class="custom-control-input" value="{{ $order->job_order_id ? '1' : '0' }}" {{ $order->job_order_id ? 'checked' : '' }}>
                                        <span class="custom-control-label"><b>Choose Job Order</b></span>
                                    </label>
                                </div>
                            </div>
                            <div style="{{ $order->job_order_id ? '' : 'display: none' }}" id="job_order_display">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="job_order_id" class="form-label">Job Order</label>
                                            <select class="form-control select2 form-select"
                                                data-placeholder="Choose One" name="job_order_id" id="job_order_id">
                                                <option label="Choose One"></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="transit_via" class="form-label">Transit Via</label>
                                            <select name="transit_via" id="transit_via"
                                              data-placeholder="Dropdown Operation" class="form-control select2 form-select">
                                              <option label="Dropdown Operation" disabled></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="consignee" class="form-label">Consignee</label>
                                            <input type="text" class="form-control" name="consignee" id="consignee" readonly placeholder="Link">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="transportation" class="form-label">Transportation</label>
                                            <input type="text" class="form-control" name="transportation" id="transportation" readonly placeholder="Link" >
                                        </div>
                                        <label class="custom-control custom-radio" id="transportation_desc" style="display: none;">
                                            <input type="radio" class="custom-control-input"
                                                name="transportation_desc" checked>
                                            <span class="custom-control-label"></span>
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="shipper" class="form-label">Shipper</label>
                                            <input type="text" class="form-control" name="shipper" id="shipper" readonly placeholder="Link">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="commodity" class="form-label">Commodity</label>
                                            <input type="text" class="form-control" name="commodity" id="commodity" readonly placeholder="Link">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th style="min-width:15rem;">Description</th>
                                            <th style="min-width:5rem;">Quantity</th>
                                            <th style="min-width:5rem;">UoM</th>
                                            <th style="min-width:10rem;">Price</th>
                                            <th style="min-width:10rem;">Total</th>
                                            <th style="text-align: center;">#</th>
                                        </tr>
                                    </thead>
                                    <tbody id="form-container">
                                        @foreach($order->details as $data)
                                        <tr class="form-wrapper">
                                            <td></td>
                                            <td>
                                                <input type="text" class="form-control description-input" name="des_detail" placeholder="Desc" value="{{ $data->description }}" onchange="toUpdate(this)"/>
                                                <label class="form-label">Remark</label>
                                                <input type="text" class="form-control remark-input" name="remark_detail" placeholder="Remark" value="{{ $data->remark }}" onchange="toUpdate(this)"/>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control qty-input" name="qty_detail" value="{{ $data->quantity }}" onchange="calculate(); toUpdate(this)"/>
                                                <label class="form-label">Disc</label>
                                                <input type="text" class="form-control discount-input" name="disc_detail" value="{{ $data->discount_nominal }}" onchange="calculate(); toUpdate(this)"/>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control uom-input" name="uom_detail" value="{{ $data->uom }}" onchange="toUpdate(this)"/>
                                                <label class="form-label" style="visibility: hidden;">Disc</label>
                                                <select class="form-control select2 form-select discount-type" data-placeholder="Choose One" name="disc_type_detail" onchange="calculate(); toUpdate(this)">
                                                    <option value="persen" {{ $data->discount_type === "persen" ? "selected" : "" }}>%</option>
                                                    <option value="nominal" {{ $data->discount_type === "nominal" ? "selected" : "" }} >0</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control price-input" name="price_detail" value="{{ number_format($data->price, 2, '.', ',') }}" onchange="calculate(); toUpdate(this)"/>
                                                <label for="" class="form-label">PPh</label>
                                                <select class="form-control select2 form-select pajak-detail" data-placeholder="Tax" name="pajak_detail" onchange="calculate(); toUpdate(this)">
                                                    <option label="Tax"></option>
                                                    @foreach ($taxs as $tax)
                                                        <option value="{{ $tax->id }}:{{ $tax->tax_rate }}" {{ $data->tax_id === $tax->id ? "selected" : ""}}>{{ $tax->tax_rate }}%</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control total-input" name="total_detail" readonly value="{{ number_format($data->total,2,'.',',') }}"/>
                                                <label for="coa_expense_detail" class="form-label">Account Name</label>
                                                <select class="form-control select2 form-select coa-expense-select" data-placeholder="Choose One" name="coa_expense_detail" onchange="toUpdate(this)">
                                                    @foreach($coa_expense as $account)
                                                        <option value="{{ $account->id }}" {{ $account->id == $data->account_id ? 'selected' : '' }}>{{ $account->account_name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    <button type="button" class="btn delete-row" onclick="deleteList(this)"><i class="fa fa-trash text-danger delete-form"></i></button>
                                                </div>
                                                <input type="text" hidden value="{{ $data->id }}:nothing" name="operator">
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="row mt-2">
                                <a href="javascript:void(0)" class="btn btn-default" id="add-form">
                                    <span><i class="fa fa-plus"></i></span> Add New Column
                                </a>
                            </div>
                            <div class="row justify-content-end">
                                <div class="col-lg-6">
                                    <table class="table mt-5">
                                        {{-- <tr>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    Biaya Lain
                                                    <input type="text" style="width: 50%" class="form-control" name="additional_cost" id="additional_cost" value="0" />
                                                </div>
                                            </td>
                                        </tr> --}}
                                        <tr>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    Diskon (-)
                                                    <div style="width: 10%">
                                                        <select class="form-control select2 form-select" name="discount_type" id="discount_type" onchange="hideButton()">
                                                            <option value="persen" {{ $order->discount_type == 'persen' ? 'selected' : '' }}>%</option>
                                                            <option value="nominal" {{ $order->discount_type == 'nominal' ? 'selected' : '' }}>0</option>
                                                        </select>
                                                    </div>
                                                    <input type="text" style="width: 10%" class="form-control" name="discount" id="discount" value="{{ $order->discount_nominal }}" onchange="hideButton()" />
                                                    <input type="text" style="width: 50%" class="form-control" id="discount_display" name="discount_display" readonly value="{{ number_format($order->discount, 2, '.', ',') }}" />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <label for="" class="form-label">PPh</label>
                                                    </div>
                                                    <div style="width: 150px">
                                                        <select class="form-control select2 form-select" data-placeholder="Tax" id="pph_tax_master">
                                                            <option label="pph tax"></option>
                                                            @foreach ($taxs as $tax)
                                                                <option value="{{ $tax->id }}:{{ $tax->tax_rate }}">{{ $tax->tax_rate }}%</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <label for="" class="form-label">PPn</label>
                                                    </div>
                                                    <div style="width: 150px">
                                                        <select class="form-control select2 form-select" data-placeholder="Tax" name="ppn_tax" id="ppn_tax" onchange="hideButton()">
                                                            <option label="ppn tax"></option>
                                                            @foreach ($ppn_tax as $tax)
                                                                <option value="{{ $tax->id }}:{{ $tax->tax_rate }}" {{ $order->tax_id == $tax->id ? 'selected' : '' }}>{{ $tax->tax_rate }}%</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    Total PPh
                                                    <input type="text" style="width: 50%" class="form-control" id="display_pajak" name="display_pajak" readonly value="0" />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    Total
                                                    <input type="text" style="width: 50%" class="form-control" id="total_display" name="total_display" readonly placeholder="0" value="{{ number_format($order->total, 2, '.', ',') }}"/>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="btn-list text-end" >
                                        <a href="javascript:void(0)" class="btn btn-default"
                                            id="calculate" onclick="total()">
                                            <span><i class="fa fa-plus"></i></span> Calculate
                                        </a>
                                    </div>
                                </div>
                                <br><br><br><br>
                                <div class="col-md-12">
                                    <div class="btn-list text-end">
                                        <a href="javascript: history.go(-1)" class="btn btn-default">Cancel</a>
                                        <button id="submit-all-form" type="button" class="btn btn-primary"  style="display: none;">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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

    <script>
        $(document).ready(function() {
            if ($('#choose_job_order').prop('checked')) {
                getJobOrder();
            }

            calculate();
            total();

            $('#pph_tax_master').on('change', function() {
                var selectedTax = $(this).val();
                $('.form-wrapper').each(function() {
                    $(this).find('select[name="pajak_detail"]').val(selectedTax).trigger('change');
                });
                calculate();
            });
        });

        $('#choose_job_order').on('change', function () {
            const job_order_display =  $("#job_order_display");
            if ($('#choose_job_order').prop('checked')) {
                job_order_display.show();
                $('#choose_job_order').val("1")
                getJobOrder();
            } else {
                job_order_display.hide();
                $('#choose_job_order').val("0")
            }
        });

        function getJobOrder() {
            var contact = $('#customer_id').val();

            const selectElement = document.getElementById("job_order_id");
            selectElement.innerHTML = ""
            const transitVia = document.getElementById('transit_via');
            transitVia.innerHTML = ""
            const consignee = document.getElementById("consignee")
            consignee.value = "";
            const transportation = document.getElementById("transportation")
            transportation.value = "";
            const transportation_desc = document.getElementById("transportation_desc")
            transportation_desc.style.display = 'none';
            const shipper = document.getElementById("shipper")
            shipper.value = "";
            const commodity = document.getElementById("commodity")
            commodity.value = "";

            const defaultOption = document.createElement("option");
            defaultOption.label = "Choose One";
            selectElement.add(defaultOption);

            const defaultTransitOption = document.createElement("option")
            defaultTransitOption.label = "Dropdown Operation";
            transitVia.add(defaultTransitOption)

            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'GET',
                dataType: 'json',
                data: { 'customer_id': contact },
                url: '{{ route('finance.payments.job-order') }}',
                success:function(response)
                {
                    if (response?.data) {
                        let orderJobOrderId = "{{ $order->job_order_id }}";
                        let jobOrderValueToSelect = null;

                        response.data.forEach(function(item) {
                            const optionValue = item.id + ":" + item.marketing.source;
                            const option = document.createElement("option");
                            option.value = optionValue;
                            option.text = item.job_order_id + " - " + item.marketing.source;
                            selectElement.add(option);

                            if (item.id == orderJobOrderId) {
                                jobOrderValueToSelect = optionValue;
                            }
                        });

                        if (jobOrderValueToSelect) {
                            $('#job_order_id').val(jobOrderValueToSelect).trigger('change');
                        }
                    }
                }
            });
        }

        $('#customer_id').on('change', function() {
            if ($('#choose_job_order').prop('checked')) {
                getJobOrder()
            }
        });

        $('#job_order_id').on('change', function() {
            var id = $(this).val();
            const transitVia = document.getElementById('transit_via');
            transitVia.innerHTML = ""
            const defaultTransitOption = document.createElement("option")
            defaultTransitOption.label = "Dropdown Operation";
            transitVia.add(defaultTransitOption)
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'GET',
                dataType: 'json',
                data: { 'id' : id },
                url: '{{ route('finance.payments.job-order.details') }}',
                success:function(response)
                {
                    hideButton()
                    $('#consignee').val(response.data.marketing.consignee);

                    if (response.data.marketing.transportation == 1) {
                        $('#transportation').val("Air Freight");
                    } else if (response.data.marketing.transportation == 2) {
                        $('#transportation').val("Sea Freight");
                    } else if(response.data.marketing.transportation) {
                        $('#transportation').val("Land Trucking");
                    }

                    if(response.data.marketing.transportation_desc){
                        const transportation_desc = document.getElementById("transportation_desc")
                        transportation_desc.style.display = 'block';
                        const text_transportation_desc = transportation_desc.querySelector('.custom-control-label')
                        text_transportation_desc.innerText = response.data.marketing.transportation_desc
                    }

                    $('#shipper').val(response.data.marketing.shipper);
                    $('#commodity').val(response.data.marketing.description);
                    if(response.data.vendors) {
                        response.data.vendors.forEach(item => {
                            if(!item.vendor) {
                                const option = document.createElement("option");
                                option.value = item.id;
                                option.text = item.transit;
                                transitVia.add(option);
                            }
                        })
                    }
                }
            });
        });

        document.getElementById('add-form').addEventListener('click', function() {
            var formContainer = document.getElementById('form-container');
            var newFormWrapper = document.createElement('tr');
            newFormWrapper.classList.add('form-wrapper');

            var formTemplate = `
            <td></td>
            <td>
                <input type="text" class="form-control description-input" name="des_detail" placeholder="Desc" onchange="toUpdate(this)"/>
                <label class="form-label">Remark</label>
                <input type="text" class="form-control remark-input" name="remark_detail" placeholder="Remark" onchange="toUpdate(this)"/>
            </td>
            <td>
                <input type="text" class="form-control qty-input" name="qty_detail" onchange="calculate(); toUpdate(this)" value="0"/>
                <label class="form-label">Disc</label>
                <input type="text" class="form-control discount-input" name="disc_detail" onchange="calculate(); toUpdate(this)" value="0"/>
            </td>
            <td>
                <input type="text" class="form-control uom-input" name="uom_detail" onchange="toUpdate(this)"/>
                <label class="form-label" style="visibility: hidden;">Disc</label>
                <select class="form-control select2 form-select discount-type" data-placeholder="Choose One" name="disc_type_detail" onchange="calculate(); toUpdate(this)" >
                    <option value="persen">%</option>
                    <option value="nominal">0</option>
                </select>
            </td>
            <td>
                <input type="text" class="form-control price-input" name="price_detail" onchange="calculate(); toUpdate(this)" value="0"/>
                <label for="" class="form-label">PPh</label>
                <select class="form-control select2 form-select pajak-detail" data-placeholder="Tax" name="pajak_detail" onchange="calculate(); toUpdate(this)">
                    <option label="Tax"></option>
                    @foreach ($taxs as $tax)
                        <option value="{{ $tax->id }}:{{ $tax->tax_rate }}">{{ $tax->tax_rate }}%</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="text" class="form-control total-input" name="total_detail" readonly value="0"/>
                <label for="coa_expense_detail" class="form-label">Account Name</label>
                <select class="form-control select2 form-select coa-expense-select" data-placeholder="Choose One" name="coa_expense_detail" onchange="toUpdate(this)">
                    @foreach($coa_expense as $account)
                        <option value="{{ $account->id }}" {{ $account->account_type_id == 17 ? 'selected' : '' }}>{{ $account->account_name }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn delete-row" onclick="deleteList(this)"><i class="fa fa-trash text-danger delete-form"></i></button>
                </div>
                <input type="text" hidden name="operator" value="0:create">
            </td>
            `;

            newFormWrapper.innerHTML = formTemplate;
            formContainer.appendChild(newFormWrapper);
            $(newFormWrapper).find('.select2').select2({ placeholder: "Choose One" });
        });

        function toUpdate(element) {
            const querySelector = element.closest('tr').querySelector('input[name="operator"]')
            const id = querySelector.value.split(':')[0]
            if (id !== "0") {
                querySelector.value = `${id}:update`
            }
        }

        function calculate() {
            $('#submit-all-form').hide()
            var forms = document.querySelectorAll('.form-wrapper');
            var grand_disc = 0;
            var grand_pajak = 0;
            forms.forEach(function(form) {
                if (form.style.display === 'none') return;

                var input = form.querySelectorAll("input, select");
                var quantity = input[2].value
                var price = input[6].value
                if(!price) price = "0"
                price = parseFloat(price.replace(/,/g, ''))
                var discount_type = input[5].value
                var disc = input[3].value
                if(!disc) disc = "0"
                disc = parseFloat(disc.replace(/,/g, ''))
                var pajak = input[7].value
                if(!pajak) {
                    pajak = "0"
                } else {
                    pajak = pajak.split(":")[1]
                }
                pajak = parseFloat(pajak)

                let total = quantity*price
                if(discount_type === "persen") {
                    disc = (disc/100)*total
                }
                grand_disc +=  disc
                total -= disc

                pajak = (pajak/100)*total
                grand_pajak += pajak

                if(price > 0) {
                    input[6].value = price.toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
                }
                input[8].value = total.toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
            });
            $('#display_pajak').val(grand_pajak.toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 }))

            return {
                grand_disc
            }
        }

        function total() {
            var total = 0;
            var disc = document.querySelector('input[name="discount"]').value;
            if(!disc) disc = "0";
            disc = parseFloat(disc.replace(/,/g, ''))

            var totalDetailInputs = document.querySelectorAll('input[name="total_detail"]');
            totalDetailInputs.forEach(function(input) {
                if (input.closest('tr').style.display === 'none') return;
                totalDetail = input.value;
                if(!totalDetail) totalDetail = "0";
                total += parseFloat(totalDetail.replace(/,/g, '')) || 0;
            });

            var discount_type = document.querySelector('select[name="discount_type"]').value;
            if(discount_type === "persen") {
                disc = (disc/100)*(total)
            }
            total -= disc

            var { grand_disc } = calculate()
            disc +=  grand_disc

            var ppn_tax = $('#ppn_tax').val();
            if (ppn_tax) {
                ppn_tax = ppn_tax.split(':')[1];
                total = total + (total * (ppn_tax/100));
            }

            $('#discount_display').val(disc.toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 }))
            $('#total_display').val(total.toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 }))
            $('#submit-all-form').show()
        }

        function deleteList(element) {
            hideButton();
            const row = element.closest('tr');
            const operatorInput = row.querySelector('input[name="operator"]');
            const id = operatorInput.value.split(':')[0];

            if (id === '0') {
                row.remove();
            } else {
                row.style.display = 'none';
                operatorInput.value = `${id}:delete`;
            }
            calculate();
            total();
        }

        function hideButton() {
            $('#submit-all-form').hide()
        }

        document.getElementById('submit-all-form').addEventListener('click', function(event) {
            event.preventDefault();
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
            document.querySelector('form[name="dynamic-form"]').appendChild(hiddenInput);

            const form = $('form[name="dynamic-form"]');
            const formDataToSend = new FormData(form[0]);

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: formDataToSend,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(data) {
                    sessionStorage.setItem('successMessage', data.message);
                    window.location.href = "{{ route('finance.payments.account-payable.index') }}";
                },
                error: function(xhr, status, error) {
                    const data = xhr.responseJSON;
                    let errorCard = $('.card-body').first();
                    let errorAlert = errorCard.find('.alert-danger');

                    if (errorAlert.length === 0) {
                        errorAlert = $('<div class="alert alert-danger" role="alert" tabindex="-1"><button type="button" class="btn-close" data-bs-dismiss="alert" aria-hidden="true">×</button><strong>Whoops!</strong><ul></ul></div>');
                        errorCard.prepend(errorAlert);
                    }

                    let errorList = errorAlert.find('ul');
                    errorList.empty();

                    for (const key in data.errors) {
                        if (Object.hasOwnProperty.call(data.errors, key)) {
                            const messages = data.errors[key];
                            messages.forEach(message => {
                                errorList.append($('<li>').text(message));
                            });
                        }
                    }

                    errorAlert.show();
                    errorAlert.focus();
                }
            });
        });

    </script>
@endpush
