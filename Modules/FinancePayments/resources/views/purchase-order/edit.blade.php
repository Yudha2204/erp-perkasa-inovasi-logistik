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
            <h4 style="color: #015377">Add New</h4>
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
                                        <div class="d-flex d-inline">
                                            <select class="form-control select2 form-select"
                                                data-placeholder="Choose One" name="vendor_id" id="vendor_id">
                                                <option label="Choose One" selected disabled></option>
                                                @foreach ($vendor as $v)
                                                    <option value="{{ $v->id }}" {{ $v->id === $order->vendor->id ? "selected" : "" }}>{{ $v->customer_name }}</option>   
                                                @endforeach
                                            </select>
                                            <div id="btn_edit_contact"></div>
                                        </div>
                                        <a data-bs-effect="effect-scale" data-bs-toggle="modal" href="#modal-create"><i class="fe fe-plus me-1"></i>Create New Contact</a>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Customer</label>
                                        <select class="form-control select2 form-select"
                                            data-placeholder="Choose One" name="customer_id" id="customer_id">
                                            <option value="null">No Customer</option>
                                            @foreach ($customer as $c)
                                                <option value="{{ $c->id }}" {{ isset($order->customer) && $c->id === $order->customer->id ? "selected" : "" }}>{{ $c->customer_name }}</option>   
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
                                        <select class="form-control select2 form-select"
                                            data-placeholder="Choose One" name="currency_id" id="currency_id">
                                            <option label="Choose One" selected disabled></option>
                                            @foreach ($currencies as $c)
                                                <option value="{{ $c->id }}" {{ $c->id === $order->currency->id ? "selected" : "" }}>{{ $c->initial }}</option>   
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
                                <div class="col-md-6">
                                    <label class="custom-control custom-radio">
                                        <input type="checkbox" id="choose_job_order" name="choose_job_order" class="custom-control-input" value="{{ isset($order->job_order) ? 1 : 0 }}" {{ isset($order->job_order) ? "checked" : "" }}>
                                        <span class="custom-control-label"><b>Choose Job Order</b></span>
                                    </label>
                                </div>
                            </div>
                            <div style="{{ isset($order->job_order) ? "" : "display:none;" }}" id="job_order_display">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="job_order_id" class="form-label">Job Order</label>
                                            <select class="form-control select2 form-select"
                                                data-placeholder="Choose One" name="job_order_id" id="job_order_id">
                                                @if(!isset($order->job_order))
                                                <option label="Choose One"></option>  
                                                @endif

                                                @foreach ($job_order as $jo)
                                                    <option value="{{ $jo->id }}:{{ $jo->marketing->source }}"
                                                        @if(isset($order->job_order))
                                                        @if($jo->id === $order->job_order->id && $jo->marketing->source === $order->job_order->marketing->source)
                                                            selected
                                                        @endif
                                                        @endif>{{ $jo->job_order_id }} - {{ $jo->marketing->source }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="transit_via" class="form-label">Transit Via</label>
                                            <select name="transit_via" id="transit_via"
                                              data-placeholder="Dropdown Operation" class="form-control select2 form-select">
                                              <option label="Dropdown Operation"></option>
                                              @if(isset($order->job_order) && isset($order->job_order->vendors))
                                              @foreach($order->job_order->vendors as $vendor)
                                                @if($vendor->id === $order->transit_via)
                                                <option value="{{ $vendor->id }}" selected>{{ $vendor->transit }}</option>
                                                @endif
                                                @if(!isset($vendor->vendor))
                                                <option value="{{ $vendor->id }}">{{ $vendor->transit }}</option>
                                                @endif
                                              @endforeach
                                              @endif
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="consignee" class="form-label">Consignee</label>
                                            <input type="text" class="form-control" name="consignee" id="consignee" readonly placeholder="Link" value="{{ isset($order->job_order->marketing->consignee) ? $order->job_order->marketing->consignee : "" }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            @php
                                                $transportation = '';
                                                if($order->job_order && $order->job_order->marketing) {
                                                    if($order->job_order->marketing->transportation === 1) {
                                                        $transportation = 'Air Freight';
                                                    } else if($order->job_order->marketing->transportation === 2) {
                                                        $transportation = 'Sea Freight';
                                                    } else if($order->job_order->marketing->transportation === 3) {
                                                        $transportation = 'Land Trucking';
                                                    }
                                                }
                                            @endphp
                                            <label for="transportation" class="form-label">Transportation</label>
                                            <input type="text" class="form-control" name="transportation" id="transportation" readonly placeholder="Link" value="{{ $transportation }}">
                                        </div>
                                        <label class="custom-control custom-radio" id="transportation_desc" style="{{ isset($order->job_order->marketing->transportation_desc) ? "" : "display:none;" }}">
                                            <input type="radio" class="custom-control-input"
                                                name="transportation_desc" checked>
                                            <span class="custom-control-label">{{ isset($order->job_order->marketing->transportation_desc) ? $order->job_order->marketing->transportation_desc : "" }}</span>
                                        </label>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="shipper" class="form-label">Shipper</label>
                                            <input type="text" class="form-control" name="shipper" id="shipper" readonly placeholder="Link" value="{{ isset($order->job_order->marketing->shipper) ? $order->job_order->marketing->shipper : "" }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="commodity" class="form-label">Commodity</label>
                                            <input type="text" class="form-control" name="commodity" id="commodity" readonly placeholder="Link" value="{{ isset($order->job_order->marketing->description) ? $order->job_order->marketing->description : "" }}">
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
                                    @php
                                        $discount_total = 0;
                                        $tax_total = 0;
                                        $dp = 0;
                                    @endphp
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
                                                <label class="custom-control custom-radio" style="margin-bottom: 0.375rem;">
                                                    <input type="checkbox" class="custom-control-input" name="dp_desc" value="{{ isset($data->dp_nominal) ? "1" : "0" }}" {{ isset($data->dp_nominal) ? "checked" : "" }} onchange="changeDp(this); calculate(); toUpdate(this)">
                                                    <span class="custom-control-label form-label">Bayar DP</span>
                                                </label>
                                                <div class="d-flex gap-2 flex-column" style="display: {{ isset($data->dp_nominal) ? "flex" : "none" }} !important">
                                                    <input type="text" class="form-control" name="dp_detail" value="{{ isset($data->dp_nominal) ? $data->dp_nominal : "" }}" onchange="calculate(); toUpdate(this)"/>
                                                    <select class="form-control select2 form-select" data-placeholder="Choose One" name="dp_type_detail" onchange="calculate(); toUpdate(this)" >
                                                        <option value="persen" {{ isset($data->dp_type) && $data->dp_type === "persen" ? "selected" : "" }}>%</option>
                                                        <option value="nominal" {{ isset($data->dp_type) && $data->dp_type === "nominal" ? "selected" : "" }}>0</option>
                                                    </select>
                                                </div>
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
                                        <tr>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    Biaya Lain
                                                    <input type="text" style="width: 50%" class="form-control" name="additional_cost" id="additional_cost" value="{{ number_format($order->additional_cost, 2, '.',',') }}" />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    Diskon (-)
                                                    <div style="width: 10%">
                                                        <select class="form-control select2 form-select" name="discount_type" id="discount_type" onchange="hideButton()">
                                                            <option value="persen" {{ $order->discount_type === "persen" ? "selected" : "" }}>%</option>
                                                            <option value="nominal" {{ $order->discount_type === "nominal" ? "selected" : "" }}>0</option>
                                                        </select>
                                                    </div>
                                                    <input type="text" style="width: 10%" class="form-control" name="discount" id="discount" value="0" onchange="hideButton()" value="{{ $order->discount_nominal }}" />
                                                    <input type="text" style="width: 50%" class="form-control" id="discount_display" name="discount_display" readonly value="{{ number_format(($order->discount + $discount_total),2,'.',',') }}" />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    Total Pajak
                                                    <input type="text" style="width: 50%" class="form-control" id="display_pajak" name="display_pajak" readonly value="{{ number_format($tax_total, 2, '.',',')}}" />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    Total
                                                    <input type="text" style="width: 50%" class="form-control" id="total_display" name="total_display" readonly placeholder="0" value="{{ number_format($order->total, 2, '.',',') }}"/>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr style="{{ $dp > 0 ? "block" : "display: none" }}" id="dp">
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    DP
                                                    <input type="text" style="width: 50%" class="form-control" id="display_dp" name="display_dp" readonly placeholder="0" value="{{ number_format($dp, 2, '.',',') }}"/>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr style="{{ $dp > 0 ? "block" : "display: none" }}" id="sisa">
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    Sisa
                                                    <input type="text" style="width: 50%" class="form-control" id="display_sisa" name="display_sisa" readonly placeholder="0" value="{{ number_format($order->total - $dp, 2, '.',',')  }}" />
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
                                        <button id="submit-all-form" type="submit" class="btn btn-primary"  style="display: none;">Save</button>
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
        //modal
        $('select[name="vendor_id"]').change(function () {
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
        //modal

        $('#choose_job_order').on('change', function () {
            const job_order_display =  $("#job_order_display");
            if ($('#choose_job_order').prop('checked')) {
                job_order_display.show();
                $('#choose_job_order').val("1")
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
                        response.data.forEach(function(item) {
                            const option = document.createElement("option");
                            option.value = item.id + ":" + item.marketing.source; 
                            option.text = item.job_order_id + " - " + item.marketing.source;
                            selectElement.add(option);
                        });
                    }
                }
            });
        }

        $('#customer_id').on('change', function() {
            getJobOrder()
        });

        $('#additional_cost').on('change', function() {
            hideButton()
            var additional = $(this).val();
            $(this).val(Number(additional.replace(/,/g, '')).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 }))
        })

        function hideButton() {
            $('#submit-all-form').hide()
        }

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
                        const current_id = '{{ $order->id }}'
                        response.data.vendors.forEach(item => {
                            if(!item.vendor || item.vendor == current_id) {
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

        function toUpdate(element) {
            const querySelector = element.closest('tr').querySelector('input[name="operator"]')
            const id = querySelector.value.split(':')[0]
            querySelector.value = `${id}:update`
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
                total -= pajak

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
                <label for="" class="form-label">Pajak</label>
                <select class="form-control select2 form-select" data-placeholder="Tax" name="pajak_detail" id="pajak_detail" onchange="calculate()">
                    <option label="Tax"></option>
                    @foreach ($taxs as $tax)
                        <option value="{{ $tax->id }}:{{ $tax->tax_rate }}">{{ $tax->tax_rate }}%</option>
                    @endforeach
                </select>
            </td>
            <td>
                <input type="text" class="form-control total-input" name="total_detail" readonly value="0"/>
                <label class="custom-control custom-radio" style="margin-bottom: 0.375rem;">
                    <input type="checkbox" class="custom-control-input" name="dp_desc" value="0" onchange="changeDp(this); calculate()">
                    <span class="custom-control-label form-label">Bayar DP</span>
                </label>
                <div class="d-flex gap-2 flex-column" style="display: none !important;">
                    <input type="text" class="form-control" name="dp_detail" onchange="calculate()" />
                    <select class="form-control select2 form-select" data-placeholder="Choose One" name="dp_type_detail" onchange="calculate()" >
                        <option value="persen" selected>%</option>
                        <option value="nominal">0</option>
                    </select>
                </div>
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
        });

        function total() {
            var total = 0;
            var disc = document.querySelector('input[name="discount"]').value;
            if(!disc) disc = "0";
            disc = parseFloat(disc.replace(/,/g, ''))

            var additional = document.querySelector('input[name="additional_cost"]').value;
            if(!additional) additional = "0";
            additional = parseFloat(additional.replace(/,/g, ''))
            
            var totalDetailInputs = document.querySelectorAll('input[name="total_detail"]');
            totalDetailInputs.forEach(function(input) {
                totalDetail = input.value;
                if(!totalDetail) totalDetail = "0";
                total += parseFloat(totalDetail.replace(/,/g, '')) || 0;
            });

            total += additional

            var discount_type = document.querySelector('select[name="discount_type"]').value;
            if(discount_type === "persen") {
                disc = (disc/100)*(total)
            }
            total -= disc
            
            var { grand_disc } = calculate()
            disc +=  grand_disc

            var dp = document.querySelector('input[name="display_dp"]').value;
            if(!dp) dp = "0";
            dp = parseFloat(dp.replace(/,/g, ''))

            $('#discount_display').val(disc.toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 }))
            $('#total_display').val(total.toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 }))
            $('#display_sisa').val((total-dp).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 }))
            $('#submit-all-form').show()
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

        function deleteList(element) {
            hideButton()
            element.closest('tr').remove();
            const id = element.closest('tr').querySelector('input[name="operator"]').value.split(":")[0]
            element.closest('tr').querySelector('input[name="operator"]').value = `${id}:delete`
            element.closest('tr').querySelector('input[name="qty_detail"]').value = 0
            element.closest('tr').querySelector('input[name="disc_detail"]').value = 0
            element.closest('tr').querySelector('input[name="price_detail"]').value = 0
            element.closest('tr').querySelector('input[name="total_detail"]').value = 0
        }
    </script>
@endpush