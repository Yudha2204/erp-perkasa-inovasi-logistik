@extends('layouts.app')
@section('content')
@push('styles')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endpush

<div class="main-content app-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">
        <form action="{{ route('finance.piutang.invoice.update',  $invoice->id) }}" method="POST" enctype="multipart/form-data" name="dynamic-form">
            @csrf
            @method('PUT')
            <!-- PAGE-HEADER -->
            <div class="page-header mb-0">
                <h1>Invoice</h1>
            </div>
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
                                        <label class="form-label">Customer</label>
                                        <div class="d-flex d-inline">
                                            <select class="form-control select2 form-select"
                                                data-placeholder="Choose One" name="customer_id" id="customer_id">
                                                <option label="Choose One" selected disabled></option>
                                                @foreach ($contact as $c)
                                                    <option value="{{ $c->id }}" {{ $c->id === $invoice->contact->id ? "selected" : "" }}>{{ $c->customer_name }}</option>
                                                @endforeach
                                            </select>
                                            <div id="btn_edit_contact"></div>
                                        </div>
                                        <a data-bs-effect="effect-scale" data-bs-toggle="modal" href="#modal-create"><i class="fe fe-plus me-1"></i>Create New Customer</a>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" class="form-label">Sales No</label>
                                        <select class="form-control select2 form-select"
                                            data-placeholder="Choose One" name="sales_no" id="sales_no">
                                            <option value="{{ $invoice->sales->id }}">{{ $invoice->sales->transaction }}</option>
                                            @foreach ($salesOrder as $so)
                                            @if($so->id !== $invoice->sales->id)
                                                <option value="{{ $so->id }}">{{ $so->transaction }}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        @php
                                        $job_order = 'Empty';
                                        $commodity = 'Empty';
                                        $consignee = 'Empty';
                                        $shipper = 'Empty';
                                        $transportation = 'Empty';
                                        $transportation_desc = 'Empty';
                                          if($invoice->sales->marketing) {
                                            $job_order = $invoice->sales->marketing->job_order_id;
                                            $commodity = $invoice->sales->marketing->description;
                                            $consignee = $invoice->sales->marketing->consignee;
                                            $shipper = $invoice->sales->marketing->shipper;
                                            $transportation = $invoice->sales->marketing->transportation;
                                            if($transportation === 1) {
                                                $transportation = 'Air Freight';
                                            } else if($transportation === 2) {
                                                $transportation = 'Sea Freight';
                                            } else {
                                                $transportation = 'Land Trucking';
                                            }
                                            $transportation_desc = $invoice->sales->marketing->transportation_desc;
                                          }
                                        @endphp
                                        <label for="" class="form-label">Term Of Payment</label>
                                        <select class="form-control select2 form-select"
                                            data-placeholder="Choose One" name="term_payment" id="term_payment" onchange="updateDueDate()">
                                            <option label="Choose One" selected disabled></option>
                                            @foreach ($terms as $term)
                                                <option value="{{ $term->id }}:{{ $term->pay_days }}" {{ $term->id === $invoice->term->id ? "selected" : "" }}>{{ $term->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="coa_ar" class="form-label">Account Name</label>
                                        <select class="form-control select2 form-select"
                                            data-placeholder="Choose One" name="coa_ar" id="coa_ar">
                                            @foreach ($coa_ar as $ca)
                                                    <option value="{{ $ca->id }}" {{ $invoice->account_id == $ca->id ? 'selected' : ""}}>{{ $ca->account_name }}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="job_order" class="form-label">Job Order</label>
                                        <input type="text" class="form-control" name="job_order" id="job_order" value="{{ $job_order }}" placeholder="Link" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="no_transactions" class="form-label">Nomor Transaksi</label>
                                        <input type="text" name="no_transactions" id="no-transactions" class="form-control" readonly placeholder="Choose Transaction" value="{{ $invoice->transaction }}"/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="date_invoice" class="form-label">Date</label>
                                        <input type="date" class="form-control" name="date_invoice" id="date_invoice" onchange="updateDueDate()" value="{{ $invoice->date_invoice }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="commodity" class="form-label">Commodity</label>
                                        <input type="text" class="form-control" name="commodity" id="commodity" placeholder="Link" readonly value="{{ $commodity }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="consignee" class="form-label">Consignee</label>
                                        <input type="text" class="form-control" name="consignee" id="consignee" placeholder="Link" readonly value="{{ $consignee }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="date_expired" class="form-label">Tanggal Jatuh Tempo</label>
                                        <input type="date" class="form-control" name="date_expired" id="date_expired" value="{{ $invoice->due_date }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="shipper" class="form-label">Shipper</label>
                                        <input type="text" class="form-control" name="shipper" id="shipper" placeholder="Link" readonly value="{{ $shipper }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="transportation" class="form-label">Transportation</label>
                                        <input type="text" class="form-control" name="transportation" id="transportation" placeholder="Link" readonly value="{{ $transportation }}">
                                    </div>
                                    <label class="custom-control custom-radio" id="transportation_desc" style="{{ $transportation_desc === "Empty" ? "display: none" : "" }}">
                                        <input type="radio" class="custom-control-input" name="transportation_desc" checked>
                                        <span class="custom-control-label">{{ $transportation_desc }}</span>
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="sell_des" class="form-label">Description</label>
                                        <input type="text" class="form-control" name="sell_des" id="sell_des" placeholder="Desc" value="{{ $invoice->description }}">
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
                                    @php
                                        $discount_total = 0;
                                        $tax_total = 0;
                                        $dp = 0;
                                    @endphp
                                    <tbody id="form-container">
                                        @foreach($invoice->details as $data)
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
                                                    @php
                                                        $discount_total += $data->discount;
                                                    @endphp
                                                    <option value="persen" {{ $data->discount_type === "persen" ? "selected" : "" }}>%</option>
                                                    <option value="nominal" {{ $data->discount_type === "nominal" ? "selected" : "" }} >0</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control price-input" name="price_detail" value="{{ number_format($data->price, 2, '.', ',') }}" onchange="calculate(); toUpdate(this)"/>
                                                <label for="" class="form-label">Pajak</label>
                                                <select class="form-control select2 form-select" data-placeholder="Tax" name="pajak_detail" id="pajak_detail" onchange="calculate(); toUpdate(this)">
                                                    @php
                                                        $tax_total += $data->tax;
                                                    @endphp
                                                    @foreach ($taxs as $tax)
                                                        <option value="{{ $tax->id }}:{{ $tax->tax_rate }}" {{ isset($data->tax_detail) && $tax->id === $data->tax_detail->id ? "selected" : ""}}>{{ $tax->tax_rate }}%</option>
                                                    @endforeach
                                                    <option label="Tax" {{ isset($data->tax_detail) ? "" : "selected" }}></option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control total-input" name="total_detail" readonly value="{{ number_format($data->total,2,'.',',') }}"/>
                                                <label for="coa_sales" class="form-label">Account Name </label>
                                                    <select class="form-control select2 form-select coa-sales-select"
                                                        data-placeholder="Choose One" name="coa_sales" id="coa_sales" >
                                                        @foreach ($coa_sales as $cs)
                                                    <option value="{{ $cs->id }}"  {{ $data->account_id == $cs->id ? 'selected' : ""}}>{{ $cs->account_name }}</option>
                                                @endforeach
                                                    </select>
                                                @php
                                                  if(isset($data->dp_nominal)) {
                                                    $dp += $data->dp;
                                                  }
                                                @endphp
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    <button type="button" class="btn delete-row" onclick="deleteList(this)"><i class="fa fa-trash text-danger delete-form"></i></button>
                                                </div>
                                                <input type="text" hidden value="{{ $data->id }}:nothing" name="operator">
                                            </td>
                                        </tr>
                                        <tr class="account-row">
                                            <td></td>
                                            <td>
                                                <div class="form-group">

                                                </div>
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
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
                            <div class="row justify-content-end">
                                <div class="col-lg-6">
                                    <table class="table mt-5">
                                        <tr>
                                            <td>
                                                {{-- <div class="d-flex justify-content-between">
                                                    Biaya Lain
                                                    <input type="text" style="width: 50%" class="form-control" id="additional_cost" name="additional_cost" value="{{ number_format($invoice->additional_cost, 2, '.',',') }}" />
                                                </div> --}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    Diskon (-)
                                                    <div style="width: 10%">
                                                        <select class="form-control select2 form-select" id="discount_type" name="discount_type" onchange="hideButton()">
                                                            <option value="persen" {{ $invoice->discount_type === "persen" ? "selected" : "" }}>%</option>
                                                            <option value="nominal" {{ $invoice->discount_type === "nominal" ? "selected" : "" }} >0</option>
                                                        </select>
                                                    </div>
                                                    <input type="text" style="width: 10%" class="form-control" id="discount" name="discount" onchange="hideButton()" value="{{ $invoice->discount_nominal }}" />
                                                    <input type="text" style="width: 50%" class="form-control" id="discount_display" name="discount_display" readonly value="{{ number_format(($invoice->discount + $discount_total),2,'.',',') }}" />
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
                                                                <option value="{{ $tax->id }}:{{ $tax->tax_rate }}" {{ $invoice->tax_id == $tax->id ? 'selected' : '' }}>{{ $tax->tax_rate }}%</option>
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
                                                    <input type="text" style="width: 50%" class="form-control" id="display_pajak" name="display_pajak" readonly value="{{ number_format($tax_total, 2, '.',',')}}" />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    Total
                                                    <input type="text" style="width: 50%" class="form-control" id="total_display" name="total_display" readonly placeholder="0" value="{{ number_format($invoice->total, 2, '.',',') }}"/>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr style="{{ $dp > 0 ? "" : "display: none" }}" id="dp">
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    DP
                                                    <input type="text" style="width: 50%" class="form-control" id="display_dp" name="display_dp" readonly placeholder="0" value="{{ number_format($dp, 2, '.',',') }}" />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr style="{{ $dp > 0 ? "block" : "display: none" }}" id="sisa">
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    Sisa
                                                    <input type="text" style="width: 50%" class="form-control" id="display_sisa" name="display_sisa" readonly placeholder="0"  value="{{ number_format($invoice->total - $dp, 2, '.',',')  }}"/>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="btn-list text-end">
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
                                        <button id="submit-all-form" style="display: none;" type="submit" class="btn btn-primary">Save</button>
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

{{-- modal input contact --}}
<div class="modal fade" id="modal-create" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">+ Add Contact</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class=" tab-menu-heading">
                                <div class="tabs-menu1">
                                    <!-- Tabs -->
                                    <ul class="nav panel-tabs">
                                        <li><a href="#tab1" class="active" data-bs-toggle="tab">Customer</a></li>
                                        <li><a href="#tab2" data-bs-toggle="tab">Company</a></li>
                                        <li><a href="#tab3" data-bs-toggle="tab">Address</a></li>
                                        <li><a href="#tab4" data-bs-toggle="tab">Others</a></li>
                                    </ul>
                                </div>
                            </div>
                            <form action="{{ route('finance.master-data.contact.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="panel-body tabs-menu-body">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab1">
                                            <h4><u>Customer</u></h4>
                                            <div class="form-group">
                                                <label>Customer ID</label>
                                                <input type="text" name="customer_id" id="customer_id" value="{{ old('customer_id') }}" class="form-control" disabled>
                                            </div>
                                            <div class="form-group">
                                                <label>Customer Name</label>
                                                <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name') }}"class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Title</label>
                                                <input type="text" name="title" value="{{ old('title') }}" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Mobile Phone Number</label>
                                                <input type="text" name="phone_number" value="{{ old('phone_humber') }}" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="text" name="email" value="{{ old('email') }}" class="form-control">
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-6">
                                                    <label>NPWP/KTP</label>
                                                    <input type="text" name="npwp_ktp" value="{{ old('npwp_ktp') }}" class="form-control">
                                                </div>
                                                <div class="form-group col-6">
                                                    <label>Upload Document</label>
                                                    <input type="file" name="document" class="form-control">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label>Category</label>
                                                    <div class="custom-controls-stacked d-flex d-inline">
                                                        <label class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input" id="contact_type1" name="contact_type[]" value="1" @if(is_array(old('contact_type')) && in_array(1,old('contact_type'))) checked @endif>
                                                                <span class="custom-control-label">Customer</span>
                                                            </label>
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <label class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input" id="contact_type2" name="contact_type[]" value="2" @if(is_array(old('contact_type')) && in_array(2,old('contact_type'))) checked @endif>
                                                                <span class="custom-control-label">Vendor</span>
                                                            </label>
                                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                                        <label class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input" id="contact_type3" name="contact_type[]" value="3" @if(is_array(old('contact_type')) && in_array(3,old('contact_type'))) checked @endif>
                                                                <span class="custom-control-label">Karyawan</span>
                                                            </label>
                                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                                        <label class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input" id="contact_type4" name="contact_type[]" value="4" @if(is_array(old('contact_type')) && in_array(4,old('contact_type'))) checked @endif>
                                                                <span class="custom-control-label">Supplier</span>
                                                            </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row input_fields_wrap_new mt-2">
                                                <div class="col-10">
                                                    <div class="form-group" style="margin-bottom: 0px; margin-top: 0px">
                                                        <label>Term Of Payment</label>
                                                        <select class="form-control select2 form-select"
                                                            data-placeholder="Choose One" name="term_payment_id[]">
                                                            <option label="Choose One" selected disabled></option>
                                                            @foreach ($terms as $term)
                                                                <option {{ old('term_payment_id[]') == $term->id ? "selected" : "" }} value="{{ $term->id }}">{{ $term->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-3">
                                                    <button type="button" id="tambahKolomNew" class="btn btn-primary btn-sm add_field_button_new"><i class="fe fe-plus me-2"></i>Add New Term</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="tab2">
                                            <h4><u>Company</u></h4>
                                            <div class="form-group">
                                                <label>Company Name</label>
                                                <input type="text" name="company_name" value="{{ old('company_name') }}" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Type Of Company</label>
                                                <div class="d-flex d-inline">
                                                    <label class="custom-control custom-radio">
                                                        <input type="radio" class="custom-control-input"
                                                        name="type_of_company" value="1"  @if(is_array(old('type_of_company')) && in_array(1,old('type_of_company'))) checked @endif>
                                                        <span class="custom-control-label">PT / Ltd</span>
                                                    </label>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                                    <label class="custom-control custom-radio">
                                                        <input type="radio" class="custom-control-input"
                                                        name="type_of_company" value="2" @if(is_array(old('type_of_company')) && in_array(2,old('type_of_company'))) checked @endif>
                                                        <span class="custom-control-label">CV</span>
                                                    </label>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                                    <label class="custom-control custom-radio">
                                                        <input type="radio" class="custom-control-input"
                                                        name="type_of_company" value="3" @if(is_array(old('type_of_company')) && in_array(3,old('type_of_company'))) checked @endif>
                                                        <span class="custom-control-label">UD</span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Company Tax Status</label>
                                                <div class="d-flex d-inline">
                                                    <label class="custom-control custom-radio">
                                                        <input type="radio" class="custom-control-input"
                                                        name="company_tax_status" value="1"  @if(is_array(old('company_tax_status')) && in_array(1,old('company_tax_status'))) checked @endif>
                                                        <span class="custom-control-label">Taxable</span>
                                                    </label>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                                    <label class="custom-control custom-radio">
                                                        <input type="radio" class="custom-control-input"
                                                        name="company_tax_status" value="2" @if(is_array(old('company_tax_status')) && in_array(2,old('company_tax_status'))) checked @endif>
                                                        <span class="custom-control-label">Non Taxable</span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div id="input_vendor"></div>
                                        </div>
                                        <div class="tab-pane" id="tab3">
                                            <h4><u>Address</u></h4>
                                            <div class="form-group">
                                                <label>Address</label>
                                                <input type="text" name="address" value="{{ old('address') }}" class="form-control">
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label>City</label>
                                                    <input type="text" name="city" value="{{ old('city') }}" class="form-control">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>Postal Code</label>
                                                    <input type="text" name="postal_code" value="{{ old('postal_code') }}" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Country</label>
                                                <input type="text" name="country" value="{{ old('country') }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="tab4">
                                            <h4><u>Others</u></h4>
                                            <div class="form-group">
                                                <label>PIC for Urgent Status</label>
                                                <input type="text" name="pic_for_urgent_status" value="{{ old('pic_for_urgent_status') }}" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Mobile Number</label>
                                                <input type="text" name="mobile_number" value="{{ old('mobile_number') }}" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3" style="text-align: right">
                                        <a class="btn btn-white color-grey" data-bs-dismiss="modal">Close</a>
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- modal edit contact --}}
<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">+ Edit Contact</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class=" tab-menu-heading">
                                <div class="tabs-menu1">
                                    <!-- Tabs -->
                                    <ul class="nav panel-tabs">
                                        <li><a href="#tab1edit" class="active" data-bs-toggle="tab">Customer</a></li>
                                        <li><a href="#tab2edit" data-bs-toggle="tab">Company</a></li>
                                        <li><a href="#tab3edit" data-bs-toggle="tab">Address</a></li>
                                        <li><a href="#tab4edit" data-bs-toggle="tab">Others</a></li>
                                    </ul>
                                </div>
                            </div>
                            <form action="{{ route('finance.master-data.contact.store') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id" id="id_edit">
                                <div class="panel-body tabs-menu-body">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab1edit">
                                            <h4><u>Customer</u></h4>
                                            <div class="form-group">
                                                <label>Customer ID</label>
                                                <input type="text" name="customer_id" id="customer_id_edit"
                                                    class="form-control" disabled>
                                            </div>
                                            <div class="form-group">
                                                <label>Customer Name</label>
                                                <input type="text" name="customer_name" id="customer_name_edit"
                                                    class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Title</label>
                                                <input type="text" name="title" id="title_edit"
                                                    class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Mobile Phone Number</label>
                                                <input type="text" name="phone_number" id="phone_number_edit"
                                                    class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="text" name="email" id="email_edit"
                                                    class="form-control">
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-6">
                                                    <label>NPWP/KTP</label>
                                                    <input type="text" name="npwp_ktp" id="npwp_ktp_edit"
                                                        class="form-control">
                                                </div>
                                                <div class="form-group col-6">
                                                    <label>Upload Document</label>
                                                    <input type="file" name="document" class="form-control">
                                                    <div id="file_edit"></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label>Category</label>
                                                    <div class="custom-controls-stacked d-flex d-inline">
                                                        <label class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="contact_type1" name="contact_type[]"
                                                                value="1">
                                                            <span class="custom-control-label">Customer</span>
                                                        </label>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <label class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="contact_type2_edit" name="contact_type[]"
                                                                value="2">
                                                            <span class="custom-control-label">Vendor</span>
                                                        </label>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                                        <label class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="contact_type3" name="contact_type[]"
                                                                value="3">
                                                            <span class="custom-control-label">Karyawan</span>
                                                        </label>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                                        <label class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="contact_type4" name="contact_type[]"
                                                                value="4">
                                                            <span class="custom-control-label">Supplier</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row input_fields_wrap_new edit mt-2">

                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-3">
                                                    <button type="button" id="tambahKolomNew"
                                                        class="btn btn-primary btn-sm add_field_button_new"><i
                                                            class="fe fe-plus me-2"></i>Add New Term</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="tab2edit">
                                            <h4><u>Company</u></h4>
                                            <div class="form-group">
                                                <label>Company Name</label>
                                                <input type="text" name="company_name" id="company_name_edit"
                                                    class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Type Of Company</label>
                                                <div class="d-flex d-inline">
                                                    <label class="custom-control custom-radio">
                                                        <input type="radio" class="custom-control-input"
                                                            name="type_of_company" value="1">
                                                        <span class="custom-control-label">PT / Ltd</span>
                                                    </label>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                                    <label class="custom-control custom-radio">
                                                        <input type="radio" class="custom-control-input"
                                                            name="type_of_company" value="2">
                                                        <span class="custom-control-label">CV</span>
                                                    </label>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                                    <label class="custom-control custom-radio">
                                                        <input type="radio" class="custom-control-input"
                                                            name="type_of_company" value="3">
                                                        <span class="custom-control-label">UD</span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Company Tax Status</label>
                                                <div class="d-flex d-inline">
                                                    <label class="custom-control custom-radio">
                                                        <input type="radio" class="custom-control-input"
                                                            name="company_tax_status" value="1">
                                                        <span class="custom-control-label">Taxable</span>
                                                    </label>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                                    <label class="custom-control custom-radio">
                                                        <input type="radio" class="custom-control-input"
                                                            name="company_tax_status" value="2">
                                                        <span class="custom-control-label">Non Taxable</span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div id="input_vendor_edit"></div>
                                        </div>
                                        <div class="tab-pane" id="tab3edit">
                                            <h4><u>Address</u></h4>
                                            <div class="form-group">
                                                <label>Address</label>
                                                <input type="text" name="address" id="address_edit"
                                                    class="form-control">
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label>City</label>
                                                    <input type="text" name="city" id="city_edit"
                                                        class="form-control">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label>Postal Code</label>
                                                    <input type="text" name="postal_code" id="postal_code_edit"
                                                        class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label>Country</label>
                                                <input type="text" name="country" id="country_edit"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="tab4edit">
                                            <h4><u>Others</u></h4>
                                            <div class="form-group">
                                                <label>PIC for Urgent Status</label>
                                                <input type="text" name="pic_for_urgent_status"
                                                    id="pic_for_urgent_status_edit" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label>Mobile Number</label>
                                                <input type="text" name="mobile_number" id="mobile_number_edit"
                                                    class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3" style="text-align: right">
                                        <a class="btn btn-white color-grey" data-bs-dismiss="modal">Close</a>
                                        <button type="submit" class="btn btn-primary">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')

    <!-- SELECT2 JS -->
    <script src="{{ url('assets/plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ url('assets/js/select2.js') }}"></script>

    <!-- MULTI SELECT JS-->
    <script src="{{ url('assets/plugins/multipleselect/multiple-select.js') }}"></script>
    <script src="{{ url('assets/plugins/multipleselect/multi-select.js') }}"></script>

    <script>
        let currentCurrencyId = '{{ $invoice->sales->currency_id }}';
        $('select[name="customer_id"]').change(function () {
            var id_contact = this.value;

            $("#btn_edit_contact").html("");
            var editContactBtn = $(`<a href="javascript:void(0)" id="btn-edit" data-id="${id_contact}" class="btn text-primary btn-sm mt-2" data-bs-toggle="tooltip" data-bs-original-title="Edit data customer"><span class="fe fe-edit fs-14"></span></a>`);
            editContactBtn.appendTo('#btn_edit_contact');
        });

        $('body').on('click', '#btn-edit', function() {
            // reinitialize input fields
            $('input:checkbox[name^="contact_type"]').each(function() {
                $(this).prop('checked', false);
            });

            let id = $(this).data('id');
            var url = "{{ route('finance.master-data.contact.edit', ':id') }}";
            url = url.replace(':id', id);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'GET',
                dataType: 'json',
                url: url,
                success: function(response) {
                    // //fill data to form
                    $('#id_edit').val(response.data.id);
                    $('#customer_id_edit').val(response.data.customer_id);
                    $('#customer_name_edit').val(response.data.customer_name);
                    $('#title_edit').val(response.data.title);
                    $('#phone_number_edit').val(response.data.phone_number);
                    $('#email_edit').val(response.data.email);
                    $('#npwp_ktp_edit').val(response.data.npwp_ktp);
                    $('#company_name_edit').val(response.data.company_name);

                    $('#address_edit').val(response.data.address);
                    $('#city_edit').val(response.data.city);
                    $('#postal_code_edit').val(response.data.postal_code);
                    $('#country_edit').val(response.data.country);
                    $('#pic_for_urgent_status_edit').val(response.data
                        .pic_for_urgent_status);
                    $('#mobile_number_edit').val(response.data.mobile_number);

                    $('input:checkbox[name^="contact_type"]').each(function() {
                        let type = JSON.parse(response.data.type); // response : ['1', '3']
                        if (type.includes($(this).val())) {
                            $(this).prop('checked', true);
                        }

                        // show beneficiary - siwft code if select checkbox vendor value modal edit
                        if ($('#contact_type2_edit').prop('checked')) {
                            $("#input_vendor_edit").html("");

                            var radioBtnEdit = $(`<div class="form-group">
                                                <label>Beneficiary Bank/Branch</label>
                                                <input type="text" name="bank_branch" class="form-control" id="bank_branch_edit">
                                            </div>
                                            <div class="form-group">
                                                <label>Beneficiary Acc Name</label>
                                                <input type="text" name="acc_name" class="form-control" id="acc_name_edit">
                                            </div>
                                            <div class="form-group">
                                                <label>Beneficiary Acc No</label>
                                                <input type="text" name="acc_no" class="form-control" id="acc_no_edit">
                                            </div>
                                            <div class="form-group">
                                                <label>Swift Code</label>
                                                <input type="text" name="swift_code" class="form-control" id="swift_code_edit">
                                            </div>`);
                            radioBtnEdit.appendTo('#input_vendor_edit');

                            $('#bank_branch_edit').val(response.data.bank_branch);
                            $('#acc_name_edit').val(response.data.acc_name);
                            $('#acc_no_edit').val(response.data.acc_no);
                            $('#swift_code_edit').val(response.data.swift_code);
                        } else {
                            $("#input_vendor_edit").html("");
                        }

                        // onChange show beneficiary - siwft code if select checkbox vendor value modal edit
                        $("input:checkbox[name^='contact_type']").on('change', function() {
                            if ($('#contact_type2_edit').prop('checked')) {
                                $("#input_vendor_edit").html("");
                                var radioBtnEdit = $(`<div class="form-group">
                                                <label>Beneficiary Bank/Branch</label>
                                                <input type="text" name="bank_branch" class="form-control" id="bank_branch_edit">
                                            </div>
                                            <div class="form-group">
                                                <label>Beneficiary Acc Name</label>
                                                <input type="text" name="acc_name" class="form-control" id="acc_name_edit">
                                            </div>
                                            <div class="form-group">
                                                <label>Beneficiary Acc No</label>
                                                <input type="text" name="acc_no" class="form-control" id="acc_no_edit">
                                            </div>
                                            <div class="form-group">
                                                <label>Swift Code</label>
                                                <input type="text" name="swift_code" class="form-control" id="swift_code_edit">
                                            </div>`);
                                radioBtnEdit.appendTo('#input_vendor_edit');

                                $('#bank_branch_edit').val(response.data.bank_branch);
                                $('#acc_name_edit').val(response.data.acc_name);
                                $('#acc_no_edit').val(response.data.acc_no);
                                $('#swift_code_edit').val(response.data.swift_code);
                            } else {
                                $("#input_vendor_edit").html("");
                            }
                        });


                    });

                    $('input:radio[name="type_of_company"]').each(function() {
                        if ($(this).val() == response.data.type_of_company) {
                            $(this).prop('checked', true);
                        }
                    });

                    $('input:radio[name="company_tax_status"]').each(function() {
                        if ($(this).val() == response.data.company_tax_status) {
                            $(this).prop('checked', true);
                        }
                    });

                    let results = ''
                    let master_term_payment = response.data.master_term_of_payment;
                    response.data.term_payment_contacts.forEach(function(item) {
                        let option = '';
                        master_term_payment.forEach(function(term) {
                            option +=
                                `<option value="${term.id}" ${term.id == item.term_payment_id ? 'selected' : ''}>${term.name}</option>`;
                        });

                        // first item no button remove
                        let removeButton = '';
                        if (item.id != response.data.term_payment_contacts[0].id) {
                            removeButton = `<div class="col-2">
                                <button type="button" class="btn text-danger btn-sm remove_field_new" style="margin-top: 30px;"><span class="fe fe-trash-2 fs-14"></span></button>
                            </div>`;
                        }
                        results +=`
                            <div class="row input_fields_wrap_new">
                                <div class="col-10">
                                    <div class="form-group mt-2">
                                        <label>Term Of Payment</label>
                                        <select class="form-control select2 form-select"
                                            data-placeholder="Choose one" name="term_payment_id[]">
                                            ${option}
                                        </select>
                                    </div>
                                </div>
                                ${removeButton}
                            </div>`; //add input box
                    });

                    var wrapper_new_edit = $(".input_fields_wrap_new.edit"); //Fields wrapper
                    $(wrapper_new_edit).html(results);

                    //show document if exist
                    if (response.data.document) {
                        $("#file_edit").html("");
                        var fileEdit = $(`<a href="/storage/${response.data.document}" target="_blank" class="btn btn-info shadow-sm btn-sm">View File</a>`);
                        fileEdit.appendTo('#file_edit');
                    } else {
                        $("#file_edit").html("");
                        var fileEdit = $(`<i>there is no document</i>`);
                        fileEdit.appendTo('#file_edit');
                    }

                    $('#modal-edit').modal('show');
                }
            });
        });

        $(document).ready(function () {
            $('#pph_tax_master').on('change', function() {
                var selectedTax = $(this).val();
                $('.form-wrapper').each(function() {
                    $(this).find('select[name="pajak_detail"]').val(selectedTax).trigger('change');
                });
            });
            $('#customer_id').select2('destroy').select2({
                placeholder: "Choose One"
            });

            // show beneficiary - siwft code if select checkbox vendor value
            $("input:checkbox[name^='contact_type']").on('change', function () {
                if ($('#contact_type2').prop('checked')) {
                    $("#input_vendor").html("");
                    var radioBtn = $(`<div class="form-group">
                                            <label>Beneficiary Bank/Branch</label>
                                            <input type="text" name="bank_branch" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Beneficiary Acc Name</label>
                                            <input type="text" name="acc_name" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Beneficiary Acc No</label>
                                            <input type="text" name="acc_no" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Swift Code</label>
                                            <input type="text" name="swift_code" class="form-control">
                                        </div>`);
                    radioBtn.appendTo('#input_vendor');
                } else {
                    $("#input_vendor").html("");
                }
            });

            //multiple input dropdown Term of Payment
            var max_fields_new      = 50; //maximum input boxes allowed
            var wrapper_new         = $(".input_fields_wrap_new"); //Fields wrapper
            var add_button_new      = $(".add_field_button_new"); //Add button ID

            var x = 1; //initlal text box count
            $(add_button_new).click(function(e){ //on add input button click
                e.preventDefault();
                if(x < max_fields_new){ //max input box allowed
                    x++; //text box increment
                    $(wrapper_new).append(`
                        <div class="row input_fields_wrap_new">
                            <div class="col-10">
                                <div class="form-group" style="margin-bottom: 0px; margin-top: 0px">
                                    <label>Term Of Payment</label>
                                    <select class="form-control select2 form-select"
                                        data-placeholder="Choose one" name="term_payment_id[]">
                                        @foreach ($terms as $term)
                                            <option {{ old('term_payment_id[]') == $term->id ? "selected" : "" }} value="{{ $term->id }}">{{ $term->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-2">
                                <button type="button" class="btn text-danger btn-sm remove_field_new" style="margin-top: 30px;"><span class="fe fe-trash-2 fs-14"></span></button>
                            </div>
                        </div>`); //add input box
                }
            });

            $(wrapper_new).on("click",".remove_field_new", function(e){ //user click on remove text
                e.preventDefault();
                $(this).parent().parent().remove(); x--;
            })
        });

        $('#customer_id').on('change', function() {
            var id = $(this).val();
            resetOption()
            const customerReal = @json($invoice->sales);
            const $select = $('#sales_no');
            $select.empty();

            const selectElement = document.getElementById("sales_no");
            selectElement.innerHTML = ""
            const defaultOption = document.createElement("option");
            defaultOption.label = "Choose One";
            selectElement.add(defaultOption);

            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'GET',
                dataType: 'json',
                data: {'id': id},
                url: '{{ route('finance.piutang.invoice.get-sales-order') }}',
                success:function(response)
                {
                    if (id == customerReal.contact_id) {
                        const realOption = new Option(customerReal.transaction, customerReal.id)
                        $select.append(realOption);
                    }

                    if (response?.data) {
                        response.data.forEach(function(item) {
                            if(item.id !== customerReal.id) {
                                const option = new Option(item.transaction, item.id)
                                $select.append(option);
                            }
                        });
                    }

                    $select.trigger('change.select2');
                }
            });
        });

        function resetOption() {
            $('#job_order').val(null);
            $('#commodity').val(null);
            $('#consignee').val(null);
            $('#shipper').val(null);
            $('#transportation').val(null);
            const transportation_desc = document.getElementById("transportation_desc")
            transportation_desc.style.display = 'none';
        }

        function toUpdate(element) {
            const querySelector = element.closest('tr').querySelector('input[name="operator"]')
            const id = querySelector.value.split(':')[0]
            querySelector.value = `${id}:update`
        }

        $('#sales_no').on('change', function() {
            var id = $(this).val();
            resetOption();
            $('#submit-all-form').hide()
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'GET',
                dataType: 'json',
                data: {'id': id},
                url: '{{ route('finance.piutang.get-sales-order-details') }}',
                success:function(response)
                {
                    if(response.data.marketing){
                        $('#job_order').val(response.data.marketing.job_order_id);
                        $('#commodity').val(response.data.marketing.description);
                        $('#consignee').val(response.data.marketing.consignee);
                        $('#shipper').val(response.data.marketing.shipper);
                        if(response.data.marketing.transportation_desc){
                            const transportation_desc = document.getElementById("transportation_desc")
                            transportation_desc.style.display = 'block';
                            const text_transportation_desc = transportation_desc.querySelector('.custom-control-label')
                            text_transportation_desc.innerText = response.data.marketing.transportation_desc
                        }

                        if (response.data.marketing.transportation == 1) {
                            $('#transportation').val("Air Freight");
                        } else if (response.data.marketing.transportation == 2) {
                            $('#transportation').val("Sea Freight");
                        } else {
                            $('#transportation').val("Land Trucking");
                        }
                    }

                    $('#no-transactions').val(response.data.sales.transaction.replace('SO', 'INV'))

                    $('#date_invoice').val(response.data.sales.date)
                    updateDueDate()
                }
            });
        });

        function updateDueDate() {
            const date = $('#date_invoice').val()
            const days = $('#term_payment').val()?.split(':')[1] ?? "0"
            if(date) {
                let due_date = new Date(date)
                due_date.setDate(due_date.getDate() + Number(days))
                $('#date_expired').val(formatDate(due_date))
            } else {
                let now = new Date()
                now.setDate(now.getDate() + Number(days))
                $('#date_invoice').val(formatDate(new Date()))
                $('#date_expired').val(formatDate(now))
            }
        }

        function formatDate(date) {
            let year = date.getFullYear();
            let month = (date.getMonth() + 1).toString().padStart(2, '0');
            let day = date.getDate().toString().padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        function calculate() {
            $('#submit-all-form').hide()
            var forms = document.querySelectorAll('.form-wrapper');
            var grand_disc = 0;
            var grand_pajak = 0;
            var grand_dp = 0;
            forms.forEach(function(form) {
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
                // total -= pajak

                if(price > 0) {
                    input[6].value = price.toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
                }
                input[8].value = total.toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })

                if(input[9].value == 1 && total > 0) {
                    var dp_type = input[11].value
                    var dp = input[10].value
                    if(!dp) dp = "0"
                    dp = parseFloat(dp.replace(/,/g, ''))
                    if(dp_type === "persen") {
                        dp = (dp/100)*total
                    }

                    grand_dp += dp
                }
            });
            $('#display_pajak').val(grand_pajak.toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 }))
            if(grand_dp > 0) {
                $('#dp').show()
                $('#sisa').show()
                $('#display_dp').val(grand_dp.toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 }))
            } else {
                $('#dp').hide()
                $('#sisa').hide()
                $('#display_dp').val(0)
            }

            return {
                grand_disc
            }
        }

        document.getElementById('add-form').addEventListener('click', function() {
            var formContainer = document.getElementById('form-container');
            var newFormWrapper = document.createElement('tr');
            newFormWrapper.classList.add('form-wrapper');

            var formTemplate = `
            <td></td>
            <td>
                <input type="text" class="form-control description-input" name="des_detail" placeholder="Desc" />
                <label class="form-label">Remark</label>
                <input type="text" class="form-control remark-input" name="remark_detail" placeholder="Remark" />
            </td>
            <td>
                <input type="text" class="form-control" name="qty_detail" onchange="calculate()" />
                <label class="form-label">Disc</label>
                <input type="text" class="form-control" name="disc_detail" onchange="calculate()" />
            </td>
            <td>
                <input type="text" class="form-control" name="uom_detail" />
                <label class="form-label" style="visibility: hidden;">Disc</label>
                <select class="form-control select2 form-select" data-placeholder="Choose One" name="disc_type_detail" onchange="calculate()" >
                    <option value="persen" selected>%</option>
                    <option value="nominal">0</option>
                </select>
            </td>
            <td>
                <input type="text" class="form-control price-input" name="price_detail" onchange="calculate()" />
                <label for="" class="form-label">PPh</label>
                <select class="form-control select2 form-select" data-placeholder="Tax" name="pajak_detail" id="pajak_detail" onchange="calculate()">
                    <option label="Tax"></option>
                    @foreach ($taxs as $tax)
                        <option value="{{ $tax->id }}:{{ $tax->tax_rate }}">{{ $tax->tax_rate }}%</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="text" class="form-control total-input" name="total_detail" readonly value="0"/>
                <label class="form-label">Account Name</label>
                <select class="form-control select2 form-select coa-sales-select" data-placeholder="Choose One" name="coa_sales_new[]">
                    <option label="Choose One" selected disabled></option>
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
    const newCoaSelect = $(newFormWrapper).find('.coa-sales-select').select2({ placeholder: "Choose One" });
    console.log(newCoaSelect);
    // AJAX Call untuk mengisi dropdown COA Sales di baris yang baru dibuat
    $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        type: 'GET',
        dataType: 'json',
        url: '{{ route('finance.master-data.account') }}',
        data: {
            //'currency_id': currentCurrencyId, // Gunakan variabel currency yang sudah disimpan
            'account_type_id': 15
        },
        success: function(response) {
            if(response.data) {
                response.data.forEach(element => {
                    const newOption = new Option(element.account_name, element.id);
                    newCoaSelect.append(newOption);
                });
                newCoaSelect.trigger('change');
            }
        }
    });
        });

        function hideButton() {
            $('#submit-all-form').hide()
        }

        // $('#additional_cost').on('change', function() {
        //     hideButton()
        //     var additional = $(this).val();
        //     $(this).val(Number(additional.replace(/,/g, '')).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 }))
        // })

        function total() {
            var { grand_disc } = calculate(); // This updates row totals and gets sum of row discounts

            var total = 0;
            var totalDetailInputs = document.querySelectorAll('input[name="total_detail"]');
            totalDetailInputs.forEach(function(input) {
                totalDetail = input.value;
                if(!totalDetail) totalDetail = "0";
                total += parseFloat(totalDetail.replace(/,/g, '')) || 0;
            });

            var disc = document.querySelector('input[name="discount"]').value;
            if(!disc) disc = "0";
            disc = parseFloat(disc.replace(/,/g, ''))

            var discount_type = document.querySelector('select[name="discount_type"]').value;
            if(discount_type === "persen") {
                disc = (disc/100)*(total)
            }
            total -= disc

            var total_discount_for_display = disc + grand_disc;

            var ppn_tax = $('#ppn_tax').val();
            if (ppn_tax) {
                ppn_tax = ppn_tax.split(':')[1];
                total = total + (total * (ppn_tax/100));
            }

            var dp = document.querySelector('input[name="display_dp"]').value;
            if(!dp) dp = "0";
            dp = parseFloat(dp.replace(/,/g, ''))

            $('#discount_display').val(total_discount_for_display.toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 }))
            $('#total_display').val(total.toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 }))
            $('#display_sisa').val((total-dp).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 }))
            $('#submit-all-form').show()
        }

        function deleteList(element) {
            hideButton()
            element.closest('tr').style["display"] = "none";
            const id = element.closest('tr').querySelector('input[name="operator"]').value.split(":")[0]
            element.closest('tr').querySelector('input[name="operator"]').value = `${id}:delete`
            element.closest('tr').querySelector('input[name="qty_detail"]').value = 0
            element.closest('tr').querySelector('input[name="disc_detail"]').value = 0
            element.closest('tr').querySelector('input[name="price_detail"]').value = 0
            element.closest('tr').querySelector('input[name="total_detail"]').value = 0
        }

        function changeDp(element) {
            const elementCurrent = element.parentNode.nextElementSibling.style["display"]
            if(elementCurrent === "none") {
                element.value = 1
                element.parentNode.nextElementSibling.style.setProperty("display", "flex", "important")
            } else {
                element.value = 0
                element.parentNode.nextElementSibling.style.setProperty("display", "none", "important")
            }
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

            // Menyimpan data dalam input tersembunyi untuk dikirimkan ke backend
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'form_data');
            hiddenInput.setAttribute('value', JSON.stringify(formData));
            document.querySelector('form[name="dynamic-form"]').appendChild(hiddenInput);

            // Mengirimkan formulir ke backend
            document.forms['dynamic-form'].submit();
        });
    </script>
@endpush
