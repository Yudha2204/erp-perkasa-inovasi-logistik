@extends('layouts.app')
@section('content')
@push('styles')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endpush

<div class="main-content app-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">
        <form action="{{ route('finance.payments.purchase-payment.update', $head->id) }}" method="POST" enctype="multipart/form-data" name="dynamic-form">
            @csrf
            @method('PUT')
            <!-- PAGE-HEADER -->
            <div class="page-header mb-0">
                <h1>Payment</h1>
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
                                        <label class="form-label">Vendor</label>
                                        <div class="d-flex d-inline">
                                            <select class="form-control select2 form-select"
                                                data-placeholder="Choose One" name="vendor_id" id="vendor_id">
                                                <option label="Choose One" selected disabled></option>
                                                @foreach ($vendors as $v)
                                                    <option value="{{ $v->id }}" {{ $v->id === $head->vendor->id ? "selected" : "" }}>{{ $v->customer_name }}</option>
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
                                        <div class="d-flex d-inline">
                                            <select class="form-control select2 form-select"
                                                data-placeholder="Choose One" name="customer_id" id="customer_id">
                                                <option value="null">No Customer</option>
                                                @foreach ($customers as $c)
                                                    <option value="{{ $c->id }}" {{ isset($head->customer) && $c->id === $head->customer->id ? "selected" : "" }}>{{ $c->customer_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" class="form-label">Nomor Transaksi</label>
                                        <input type="text" name="no_transactions" id="no_transactions" class="form-control" readonly placeholder="Choose Transaction" value="{{ $head->transaction }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" class="form-label">Date</label>
                                        <input type="date" class="form-control" name="date_payment" id="date_payment" value="{{ $head->date_payment }}"  >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="currency_head_id" class="form-label">Currency</label>
                                        <select class="form-control select2 form-select"
                                            data-placeholder="Choose Currency" name="currency_head_id" id="currency_head_id">
                                            <option label="Choose Currency"></option>
                                            @foreach ($currencies as $c)
                                                <option value="{{ $c->id }}" {{ $c->id === $head->currency_id ? "selected" : "" }}>{{ $c->initial }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" class="form-label">Description</label>
                                        <input type="text" class="form-control" name="description" id="description" value="{{ $head->description }}" placeholder="Desc"  >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="account_id" class="form-label">Account Name</label>
                                        <select class="form-control select2 form-select"
                                            data-placeholder="Choose One" name="head_account_id" id="account_id">
                                            <option label="Choose One"></option>
                                            @foreach ($accounts as $account)
                                                <option value="{{ $account->id }}" {{ $account->id === $head->account->id ? "selected" : "" }}>{{ $account->account_name }}</option>
                                            @endforeach
                                        </select>
                                        <a data-bs-effect="effect-scale" data-bs-toggle="modal" href="#modaldemo8"><i class="fe fe-plus me-1"></i>Create New Account</a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="custom-control custom-radio">
                                        <input type="checkbox" class="custom-control-input" id="choose_job_order" name="choose_job_order" value="{{ isset($head->job_order) ? 1 : 0 }}" {{ isset($head->job_order) ? "checked" : "" }}>
                                        <span class="custom-control-label"><b>Choose Job Order</b></span>
                                    </label>
                                </div>
                            </div>
                            <div style="{{ isset($head->job_order) ? "" : "display:none;" }}" id="job_order_display">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="job_order_id" class="form-label">Job Order</label>
                                            <select class="form-control select2 form-select"
                                                data-placeholder="Choose One" name="job_order_id" id="job_order_id">
                                                <option label="Choose One" selected disabled></option>
                                                @foreach ($operation as $jo)
                                                    <option value="{{ $jo->id }}:{{ $jo->marketing->source }}" {{ $jo->id === $head->job_order_id && $jo->marketing->source === $head->source ? "selected" : "" }}>{{ $jo->job_order_id }} - {{ $jo->marketing->source }}</option>
                                                @endforeach
                                            </select>
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
                                            <th style="min-width:15rem;">Charge Type / Payable</th>
                                            <th style="min-width:15rem;">Tanggal</th>
                                            <th style="min-width:10rem;">Jumlah</th>
                                            <th style="min-width:10rem;">Diskon</th>
                                            <th style="min-width:10rem;">Total</th>
                                            <th style="min-width:10rem;">Account Name</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody id="form-container">
                                        @foreach($head->details as $data)
                                        <tr class="form-wrapper">
                                            <td></td>
                                            <td>
                                                <select class="form-control select2 form-select charge-type-select" name="charge_type" data-placeholder="Select Charge Type" onchange="toggleChargeType(this)">
                                                    <option value="payable" {{ $data->charge_type === 'payable' ? 'selected' : '' }}>Payable</option>
                                                    <option value="account" {{ $data->charge_type === 'account' ? 'selected' : '' }}>Account</option>
                                                </select>
                                                <div class="payable-section" style="{{ $data->charge_type === 'account' ? 'display: none;' : '' }}">
                                                    <select class="form-control select2 form-select" data-order-id="{{ $data->payable_id }}" name="detail_order" data-placeholder="Choose One" onchange="getData(this)">
                                                        <option value="{{ $data->payable_id }}">{{ $data->payable->transaction }}</option>
                                                        @foreach($purchaseOrder as $i)
                                                            @if($data->payable_id !== $i->id)
                                                            <option value="{{ $i->id }}" {{ $i->id === $data->payable_id ? "selected" : "" }}>{{ $i->transaction }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="account-section" style="{{ $data->charge_type === 'payable' ? 'display: none;' : '' }}">
                                                    <input type="text" class="form-control account-description" name="description" placeholder="Enter description" value="{{ $data->description }}"/>
                                                </div>
                                            </td>
                                            <td>
                                                @if($data->charge_type === 'payable' && $data->payable)
                                                <input type="date" class="form-control" readonly name="detail_date" value="{{ $data->payable->date_order }}"/>
                                                @else
                                                <input type="date" class="form-control" name="detail_date" value="{{ $data->created_at ? $data->created_at->format('Y-m-d') : '' }}"/>
                                                @endif
                                            </td>
                                            <td>
                                                @if($data->charge_type === 'payable' && $data->payable)
                                                <input type="text" class="form-control" readonly name="detail_jumlah" value="{{ number_format($data->payable->total-$data->payable->dp-$data->getDpPaymentBefore($data->head_id),2,'.',',') }}"/>
                                                @else
                                                <input type="text" class="form-control amount-input" name="detail_jumlah" placeholder="Enter amount" value="{{ number_format($data->amount, 2, '.', ',') }}" onchange="getTotal(this)"/>
                                                @endif
                                                <label class="custom-control custom-radio" style="margin-bottom: 0.375rem;">
                                                    <input type="checkbox" class="custom-control-input" name="other_currency" value="{{ isset($data->currency_via_id) ? 1 : 0 }}" onchange="changeOpsi(this); getTotal(this)" {{ isset($data->currency_via_id) ? "checked" : "" }}>
                                                    <span class="custom-control-label form-label">Mata Uang Lain</span>
                                                </label>
                                                <div class="d-flex justify-content-between gap-2" style="{{ isset($data->currency_via_id) ? "display: flex !important;" : "display: none !important;" }}">
                                                    <input type="text" class="form-control" name="other_currency_nominal" onchange="getTotal(this)" value="{{ number_format($data->amount_via,2) }}"/>
                                                    <select class="form-control select2 form-select" data-placeholder="X" name="other_currency_type" onchange="getTotal(this)">
                                                        @foreach($exchange as $e)
                                                            @php
                                                                $name = '';
                                                                $dataExchange = 1;
                                                                if($e->from_currency_id === $head->currency_id) {
                                                                    $dataExchange = $e->from_nominal/$e->to_nominal;
                                                                    $name = $e->to_currency->initial;
                                                                } else {
                                                                    $dataExchange = $e->to_nominal/$e->from_nominal;
                                                                    $name = $e->from_currency->initial;
                                                                }
                                                            @endphp
                                                            <option value="{{ $e->id }}" data-exchange='{{ $dataExchange }}' {{ $e->id === $data->currency_via_id ? "selected" : "" }}>{{ $name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-between gap-2">
                                                    <input type="text" class="form-control" name="detail_discount_nominal" onchange="getTotal(this)" value="{{ number_format($data->discount_nominal,2,'.',',')}}" {{ isset($data->dp_nominal) ? "disabled" : "" }}/>
                                                    <select class="form-control select2 form-select" data-placeholder="Choose one" name="detail_discount_type" onchange="getTotal(this)" {{ isset($data->dp_nominal) ? "disabled" : "" }}>
                                                        <option value="persen" {{ $data->discount_type === "persen" ? "selected" : "" }}>%</option>
                                                        <option value="nominal" {{ $data->discount_type === "nominal" ? "selected" : "" }}>0</option>
                                                    </select>
                                                </div>
                                                <label class="custom-control custom-radio" style="margin-bottom: 0.375rem;">
                                                    <input type="checkbox" class="custom-control-input" name="dp_desc" value="{{ isset($data->dp_nominal) ? 1 : 0 }}" {{ isset($data->dp_nominal) ? "checked" : "" }} onchange="changeDp(this); getTotal(this)">
                                                    <span class="custom-control-label form-label">Bayar DP</span>
                                                </label>
                                                <div class="d-flex justify-content-between gap-2" style="{{ isset($data->dp_nominal) ? "" : "display: none !important" }}">
                                                    <input type="text" class="form-control" name="detail_dp_nominal" value="{{ $data->dp_nominal }}" onchange="getTotal(this)"/>
                                                    <select class="form-control select2 form-select" data-placeholder="Choose one" name="detail_dp_type" onchange="getTotal(this)">
                                                        <option value="persen" {{ $data->dp_type === "persen" ? "selected" : "" }}>%</option>
                                                        <option value="nominal" {{ $data->dp_type === "nominal" ? "selected" : "" }}>0</option>
                                                    </select>
                                                    <input type="text" hidden class="form-control" name="detail_dp_order_nominal" value="{{ $data->dp + $data->dp_payment }}"/>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control total_input" readonly name="detail_total" value="{{ number_format($data->total,2,'.',',') }}"/>
                                            </td>
                                            <td>
                                                <select class="form-control select2 form-select coa-ap-select" data-placeholder="Choose One" name="account_id" data-selected="{{ $data->account_id }}">
                                                    <option label="Choose One" selected disabled></option>
                                                </select>
                                                <label class="form-label">Remark</label>
                                                <input type="text" class="form-control remark-input" placeholder="Remark" name="detail_remark" value="{{ $data->remark }}" />
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    <button type="button" class="btn delete-row" onclick="deleteList(this)"><i class="fa fa-trash text-danger delete-form"></i></button>
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
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="note_payment" class="form-label">Catatan Transaksi</label>
                                        <textarea name="note_payment" id="note_payment" cols="30" rows="5" placeholder="Tulis catatan transaksi di sini ( maks 1000 karakter )" class="form-control">{{ $head->note }}</textarea>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <table class="table mt-5">

                                        <tr>
                                            <td>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    Diskon
                                                    <input type="text" style="width: 50%" class="form-control" id="discount_display" name="discount_display" readonly placeholder="0" value="{{ number_format($head->discount,2,'.',',')}}" />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    Total
                                                    <input type="text" style="width: 50%" class="form-control" id="total_display" name="total_display" readonly placeholder="0" value="{{ number_format($head->total,2,'.',',')}}" />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr style="{{ $head->dp > 0 ? "" : "display: none" }}" id="dp">
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    DP
                                                    <input type="text" style="width: 50%" class="form-control" id="display_dp" name="display_dp" readonly placeholder="0" value="{{ number_format($head->dp, 2, '.',',') }}" />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr style="display: none" id="dp_order">
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    DP From Account Payable
                                                    <input type="text" style="width: 50%" class="form-control" id="display_dp_order" name="display_dp_order" readonly placeholder="0" />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr style="{{ $head->dp > 0 ? "block" : "display: none" }}" id="sisa">
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    Sisa
                                                    <input type="text" style="width: 50%" class="form-control" id="display_sisa" name="display_sisa" readonly placeholder="0"  value="{{ number_format($head->total - $head->dp, 2, '.',',')  }}"/>
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
                                            id="calculate" onclick="calculate()">
                                            <span><i class="fa fa-plus"></i></span> Calculate
                                        </a>
                                    </div>
                                </div>
                                <br><br><br><br>
                                <div class="col-md-12">
                                    <div class="btn-list text-end">
                                        <a href="javascript: history.go(-1)" class="btn btn-default">Cancel</a>
                                        <button id="submit-all-form" style="display: none;" type="button" class="btn btn-primary">Save</button>
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
                                                            data-placeholder="Choose one" name="term_payment_id[]">
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

{{-- modal create --}}
<div class="modal fade" id="modaldemo8" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">+ Add Account</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ route('finance.master-data.account.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Account Type</label>
                                <select class="form-control select2 form-select"
                                    data-placeholder="Choose one" name="account_type_id">
                                    @foreach ($accountTypes as $accountType)
                                        <option {{ old('account_type_id') == $accountType->id ? "selected" : "" }} value="{{ $accountType->id }}">{{ $accountType->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Code</label>
                                <input type="text" name="code" value="{{ old('code') }}" id="code" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Account Name</label>
                                <input type="text" name="account_name" value="{{ old('account_name') }}" id="account_name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Currency</label>
                                <select class="form-control select2 form-select"
                                    data-placeholder="Choose one" name="master_currency_id">
                                    @foreach ($currencies as $currency)
                                        <option {{ old('master_currency_id') == $currency->id ? "selected" : "" }} value="{{ $currency->id }}">{{ $currency->initial }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-3" style="text-align: right">
                                <a class="btn btn-white color-grey" data-bs-dismiss="modal">Close</a>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
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
        let opsi = '';
        let opsiCurrency = '';
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

        //display
        $(document).ready(function () {
            $("input:checkbox[name^='choose_job_order']").on('change', function () {
                const job_order_display =  $("#job_order_display");
                if ($('#choose_job_order').prop('checked')) {
                    job_order_display.show();
                    $('#choose_job_order').val("1")
                    getOrder()
                } else {
                    job_order_display.hide();
                    $('#choose_job_order').val("0")
                    getOrder()
                }
            });
        })
        //display

        $('#customer_id').on('change', function () {
            getJobOrder()
            getOrder()
        })

        $('#vendor_id').on('change', function () {
            getOrder()
        })

        $(document).ready(function () {
            getOrder(true)
            resetCurrency(true)

            // Fetch AP Accounts and populate dropdowns
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'GET',
                dataType: 'json',
                url: '{{ route('finance.master-data.account') }}',
                data: { 'account_type_id' :8 },
                success: function(response) {
                    if(response.data) {
                        $('.coa-ap-select').each(function() {
                            var selectedValue = $(this).data('selected');
                            $(this).empty().append('<option label="Choose One" selected disabled></option>');
                            response.data.forEach(element => {
                                const newOption = new Option(element.account_name, element.id);
                                $(this).append(newOption);
                            });
                            if (selectedValue) {
                                $(this).val(selectedValue).trigger('change');
                            }
                        });
                    }
                }
            });
        })

        $('#currency_head_id').on('change', function () {
            getOrder()
            resetCurrency()
            const currency_id = $(this).val()
            $('#account_id').text('')

            const selectElement = document.getElementById("account_id");
            selectElement.innerHTML = ""
            const defaultOption = document.createElement("option");
            defaultOption.label = "Choose One";
            selectElement.add(defaultOption);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'GET',
                dataType: 'json',
                url: '{{ route('finance.master-data.account') }}',
                data: { 'currency_id': currency_id },
                success: function(response) {
                    if(response.data) {
                        response.data.forEach(element => {
                            const newOption = new Option(element.account_name, element.id)
                            $('#account_id').append(newOption)
                        });
                    }
                }
            })
        })

        $('#job_order_id').on('change', function () {
            getOrder()
        })

        function changeFormat(element) {
            let value = element.value
            value = parseFloat(value.replace(/,/g, '')) || 0
            element.value = value.toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
            hideButton()
        }

        function getJobOrder() {
            var name = $('#customer_id').val();
            const selectElement = document.getElementById("job_order_id");
            selectElement.innerHTML = ""
            const defaultOption = document.createElement("option");
            defaultOption.label = "Choose One";
            selectElement.add(defaultOption);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'GET',
                dataType: 'json',
                url: '{{ route('finance.payments.job-order') }}',
                data: { 'customer_id': name },
                success: function(response) {
                    if(response?.data) {
                        response.data.forEach(function(item) {
                            const option = document.createElement('option')
                            option.value = item.id + ":" + item.marketing.source
                            option.text = item.job_order_id + " - " + item.marketing.source
                            selectElement.add(option)
                        })
                    }
                }
            })
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

            // Menyimpan data dalam input tersembunyi untuk dikirimkan ke backend
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
                    window.location.href = "{{ route('finance.payments.purchase-payment.index') }}";
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
                            console.log(messages)
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

        document.getElementById('add-form').addEventListener('click', function() {
            var formContainer = document.getElementById('form-container');
            var newFormWrapper = document.createElement('tr');
            newFormWrapper.classList.add('form-wrapper');

            var formTemplate = `
            <td></td>
            <td>
                <select class="form-control select2 form-select charge-type-select" name="charge_type" data-placeholder="Select Charge Type" onchange="toggleChargeType(this)">
                    <option value="payable">Payable</option>
                    <option value="account">Account</option>
                </select>
                <div class="payable-section">
                    <select class="form-control select2 form-select" name="detail_order" data-placeholder="Choose One" onchange="getData(this)">
                        <option label="Choose One" selected disabled></option>
                        ${opsi}
                    </select>
                </div>
                <div class="account-section" style="display: none;">
                    <input type="text" class="form-control account-description" name="description" placeholder="Enter description"/>
                </div>
            </td>
            <td>
                <input type="date" class="form-control" readonly name="detail_date" />
            </td>
            <td>
                <input type="text" class="form-control amount-input" name="detail_jumlah" placeholder="Enter amount" onchange="getTotal(this)"/>
                <label class="custom-control custom-radio" style="margin-bottom: 0.375rem;">
                    <input type="checkbox" class="custom-control-input" name="other_currency" value="0" onchange="changeOpsi(this); getTotal(this)">
                    <span class="custom-control-label form-label">Mata Uang Lain</span>
                </label>
                <div class="d-flex justify-content-between gap-2" style="display: none !important;">
                    <input type="text" class="form-control" name="other_currency_nominal" onchange="getTotal(this)"/>
                    <select class="form-control select2 form-select" data-placeholder="X" name="other_currency_type" onchange="getTotal(this)">
                    </select>
                </div>
            </td>
            <td>
                <div class="d-flex justify-content-between gap-2">
                    <input type="text" class="form-control" name="detail_discount_nominal" onchange="getTotal(this)"/>
                    <select class="form-control select2 form-select" data-placeholder="Choose one" name="detail_discount_type" onchange="getTotal(this)">
                        <option value="persen">%</option>
                        <option value="nominal">0</option>
                    </select>
                </div>
                <label class="custom-control custom-radio" style="margin-bottom: 0.375rem;">
                    <input type="checkbox" class="custom-control-input" name="dp_desc" value="0" onchange="changeDp(this); getTotal(this)">
                    <span class="custom-control-label form-label">Bayar DP</span>
                </label>
                <div class="d-flex justify-content-between gap-2" style="display: none !important;">
                    <input type="text" class="form-control" name="detail_dp_nominal" onchange="getTotal(this)"/>
                    <select class="form-control select2 form-select" data-placeholder="Choose one" name="detail_dp_type" onchange="getTotal(this)">
                        <option value="persen">%</option>
                        <option value="nominal">0</option>
                    </select>
                    <input type="text" hidden class="form-control" name="detail_dp_order_nominal"/>
                </div>
            </td>
            <td>
                <input type="text" class="form-control total_input" readonly name="detail_total"/>
            </td>
            <td>
                <select class="form-control select2 form-select coa-ap-select" data-placeholder="Choose One" name="account_id">
                    <option label="Choose One" selected disabled></option>
                </select>
                <label class="form-label">Remark</label>
                <input type="text" class="form-control remark-input" placeholder="Remark" name="detail_remark" />
            </td>
            <td>
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn delete-row" onclick="deleteList(this)"><i class="fa fa-trash text-danger delete-form"></i></button>
                </div>
            </td>
            `;

            getCurrencyVia()
            newFormWrapper.innerHTML = formTemplate;
            formContainer.appendChild(newFormWrapper);

            const coaApSelect = newFormWrapper.querySelector('.coa-ap-select');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'GET',
                dataType: 'json',
                url: '{{ route('finance.master-data.account') }}',
                data: { 'account_type_id' :8 },
                success: function(response) {
                    if(response.data) {
                        response.data.forEach(element => {
                            const newOption = new Option(element.account_name, element.id)
                            $(coaApSelect).append(newOption);
                        });
                    }
                }
            })

            $('.select2').select2({
                minimumResultsForSearch: Infinity
            });
            var select2Elements = document.querySelectorAll('.select2');
            select2Elements.forEach(function(element) {
                element.style.width = '100%';
            });
        });

        function deleteList(element) {
            hideButton()
            element.closest('tr').remove();
        }

        function getTotal(element) {
            const row = element.closest('tr')
            let jumlah = row.querySelector('input[name="detail_jumlah"]').value
            jumlah = parseFloat(jumlah.replace(/,/g, '')) || 0
            const dp = row.querySelector('input[name="dp_desc"]').value
            let diskon = row.querySelector('input[name="detail_discount_nominal"]').value
            diskon = parseFloat(diskon.replace(/,/g, '')) || 0
            if(dp === "1") {
                row.querySelector('input[name="detail_discount_nominal"]').value = 0;
                diskon = 0;
            }

            const other_currency = row.querySelector('input[name="other_currency"]').value
            const other_currency_type = row.querySelector('select[name="other_currency_type"]').value
            let other_currency_nominal = 0
            if(other_currency == 1 && other_currency_type) {
                other_currency_nominal = row.querySelector('input[name="other_currency_nominal"]').value
                other_currency_nominal = parseFloat(other_currency_nominal.replace(/,/g, '')) || 0
                const exchange_rate = $('select[name="other_currency_type"] option:selected').data('exchange');
                jumlah = other_currency_nominal*exchange_rate
            }

            const diskon_type = row.querySelector('select[name="detail_discount_type"]').value
            let total = 0;
            if(diskon_type === "persen") {
                total = jumlah - ((diskon/100)*jumlah)
            } else {
                total = jumlah - diskon
            }

            row.querySelector('input[name="other_currency_nominal"]').value = other_currency_nominal.toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
            row.querySelector('input[name="detail_total"]').value = total.toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
            hideButton()
        }

        function getOrder(justOpsi) {
            var customer = $('#customer_id').val()
            if(customer === "null") {
                customer = null
            }
            var vendor = $('#vendor_id').val()
            var currency = $('#currency_head_id').val()
            var choose = $('#choose_job_order').val()
            var job_order = "null"
            if(choose === "1") {
                if($('#job_order_id').val()) {
                    job_order = $('#job_order_id').val()
                }
            }

            var customer_current = '{{ $head->customer_id }}'
            if(!customer_current) {
                customer_current = null
            }
            const vendor_current = '{{ $head->vendor_id }}'
            const currency_current = '{{ $head->currency_id }}'
            const job_order_current = '{{ $head->job_order_id }}:{{ $head->source }}'

            if(!justOpsi) {
                var formContainer = document.getElementById('form-container');
                formContainer.innerHTML = '';
            }
            $('#add-form').hide()

            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'GET',
                dataType: 'json',
                data: {'customer': customer, 'currency': currency, 'job_order': job_order, 'vendor': vendor },
                url: '{{ route('finance.payments.order') }}',
                success:function(response)
                {
                    if (response.data && (response.data.length > 0 || (customer == customer_current && currency == currency_current && job_order == job_order_current && vendor == vendor_current))) {
                        $('#add-form').show()
                        if(!justOpsi) {
                            var newFormWrapper = document.createElement('tr');
                            newFormWrapper.classList.add('form-wrapper');
                        }

                        opsi = ''
                        const details = @json($head->details);
                        if(customer == customer_current && currency == currency_current && job_order == job_order_current && vendor == vendor_current) {
                            for(let data of details) {
                                opsi += `<option value="${data.payable_id}">${data.payable.transaction}</option>`
                            }
                        }

                        response.data.forEach(el => {
                            const isDuplicate = details.some(detail => detail.payable_id === el.payable_id)
                            if(!isDuplicate) {
                                const newOption = `<option value=${el.id}>${el.transaction}</option>`
                                opsi += newOption
                            }
                        })

                        if(!justOpsi) {
                            var formTemplate = `
                            <td></td>
                            <td>
                                <select class="form-control select2 form-select charge-type-select" name="charge_type" data-placeholder="Select Charge Type" onchange="toggleChargeType(this)">
                                    <option value="payable">Payable</option>
                                    <option value="account">Account</option>
                                </select>
                                <div class="payable-section">
                                    <select class="form-control select2 form-select" name="detail_order" data-placeholder="Choose One" onchange="getData(this)">
                                        <option label="Choose One" selected disabled></option>
                                        ${opsi}
                                    </select>
                                </div>
                                <div class="account-section" style="display: none;">
                                    <input type="text" class="form-control account-description" name="description" placeholder="Enter description"/>
                                </div>
                            </td>
                            <td>
                                <input type="date" class="form-control" readonly name="detail_date" />
                            </td>
                            <td>
                                <input type="text" class="form-control amount-input" name="detail_jumlah" placeholder="Enter amount" onchange="getTotal(this)"/>
                                <label class="custom-control custom-radio" style="margin-bottom: 0.375rem;">
                                    <input type="checkbox" class="custom-control-input" name="other_currency" value="0" onchange="changeOpsi(this); getTotal(this)">
                                    <span class="custom-control-label form-label">Mata Uang Lain</span>
                                </label>
                                <div class="d-flex justify-content-between gap-2" style="display: none !important;">
                                    <input type="text" class="form-control" name="other_currency_nominal" onchange="getTotal(this)"/>
                                    <select class="form-control select2 form-select" data-placeholder="X" name="other_currency_type" onchange="getTotal(this)">
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-between gap-2">
                                    <input type="text" class="form-control" name="detail_discount_nominal" onchange="getTotal(this)"/>
                                    <select class="form-control select2 form-select" data-placeholder="Choose one" name="detail_discount_type" onchange="getTotal(this)">
                                        <option value="persen">%</option>
                                        <option value="nominal">0</option>
                                    </select>
                                </div>
                                <label class="custom-control custom-radio" style="margin-bottom: 0.375rem;">
                                    <input type="checkbox" class="custom-control-input" name="dp_desc" value="0" onchange="changeDp(this); getTotal(this)">
                                    <span class="custom-control-label form-label">Bayar DP</span>
                                </label>
                                <div class="d-flex justify-content-between gap-2" style="display: none !important;">
                                    <input type="text" class="form-control" name="detail_dp_nominal" onchange="getTotal(this)"/>
                                    <select class="form-control select2 form-select" data-placeholder="Choose one" name="detail_dp_type" onchange="getTotal(this)">
                                        <option value="persen">%</option>
                                        <option value="nominal">0</option>
                                    </select>
                                    <input type="text" hidden class="form-control" name="detail_dp_order_nominal"/>
                                </div>
                            </td>
                            <td>
                                <input type="text" class="form-control total_input" readonly name="detail_total"/>
                            </td>
                            <td>
                                <select class="form-control select2 form-select coa-ap-select" data-placeholder="Choose One" name="account_id">
                                    <option label="Choose One" selected disabled></option>
                                </select>
                                <label class="form-label">Remark</label>
                                <input type="text" class="form-control remark-input" placeholder="Remark" name="detail_remark" />
                            </td>
                            <td>
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn delete-row" onclick="deleteList(this)"><i class="fa fa-trash text-danger delete-form"></i></button>
                                </div>
                            </td>
                            `;

                            newFormWrapper.innerHTML = formTemplate;
                            formContainer.appendChild(newFormWrapper);
                            getCurrencyVia()
                        } else {
                            $('select[name="detail_order"]').each(function() {
                                let current_value = $(this).data('order-id');
                                $(this).html(opsi);
                                $(this).val(current_value).trigger('change');
                            });
                        }

                        $('.select2').select2({
                            minimumResultsForSearch: Infinity
                        });
                        var select2Elements = document.querySelectorAll('.select2');
                        select2Elements.forEach(function(element) {
                            element.style.width = '100%';
                        });
                    }
                }
            })
        }

        function hideButton() {
            $('#submit-all-form').hide()
        }

        function getData(element) {
            const order = $(element).val()

            const row = element.closest('tr')

            $(row.querySelector('.coa-ap-select')).prop('disabled', false);

            row.querySelector('input[name="detail_date"]').value = ''
            row.querySelector('input[name="detail_jumlah"]').value = ''
            row.querySelector('input[name="detail_discount_nominal"]').value = 0
            row.querySelector('input[name="detail_total"]').value = ''
            hideButton()

            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'GET',
                dataType: 'json',
                data: {'order': order },
                url: '{{ route('finance.payments.order.details') }}',
                success:function(response)
                {
                    if(response.data) {
                        row.querySelector('input[name="detail_date"]').value = response.data.date_order
                        let total = Number(response.data.total)
                        if(response.data.dp) {
                            total -= Number(response.data.dp)
                        }
                        if(response.data.dp_payment) {
                            total -= Number(response.data.dp_payment)
                        }
                        row.querySelector('input[name="detail_jumlah"]').value = total.toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
                        row.querySelector('input[name="detail_total"]').value = total.toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
                        row.querySelector('input[name="detail_dp_order_nominal"]').value = (Number(response.data.dp) + Number(response.data.dp_payment)).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
                        if (response.data.account_id) {
                            const coaSelect = $(row.querySelector('.coa-ap-select'));
                            coaSelect.val(response.data.account_id).trigger('change');
                            coaSelect.prop('disabled', true);
                        }
                    }
                }
            })
        }

        function calculate() {
            const tr = document.querySelectorAll('.form-wrapper')
            let grand_total = 0;
            let grand_diskon = 0;
            let grand_dp = 0;
            let grand_dp_order = 0;
            tr.forEach(el => {
                let total_tr = 0;
                let diskon_tr = 0;
                let jumlah = el.querySelector('input[name="detail_jumlah"]').value
                let diskon = el.querySelector('input[name="detail_discount_nominal"]').value
                jumlah = parseFloat(jumlah.replace(/,/g, '')) || 0

                const other_currency = el.querySelector('input[name="other_currency"]').value
                const other_currency_type = el.querySelector('select[name="other_currency_type"]').value
                let other_currency_nominal = 0
                if(other_currency == 1 && other_currency_type) {
                    other_currency_nominal = el.querySelector('input[name="other_currency_nominal"]').value
                    other_currency_nominal = parseFloat(other_currency_nominal.replace(/,/g, '')) || 0
                    const exchange_rate = $('select[name="other_currency_type"] option:selected').data('exchange');
                    jumlah = other_currency_nominal*exchange_rate
                }

                diskon = parseFloat(diskon.replace(/,/g, '')) || 0
                const diskon_type = el.querySelector('select[name="detail_discount_type"]').value
                if(diskon_type === "persen") {
                    diskon_tr = (diskon/100)*jumlah
                } else {
                    diskon_tr = diskon
                }

                const isDp = el.querySelector('input[name="dp_desc"]').value
                if(isDp === "1") {
                    let dp = el.querySelector('input[name="detail_dp_nominal"]').value
                    dp = parseFloat(dp.replace(/,/g, '')) || 0
                    const dp_type = el.querySelector('select[name="detail_dp_type"]').value
                    if(dp_type === "persen") {
                        grand_dp += (dp/100)*jumlah
                    } else {
                        grand_dp += dp
                    }
                } else {
                    let dp_order = el.querySelector('input[name="detail_dp_order_nominal"]').value
                    dp_order = parseFloat(dp_order.replace(/,/g,'')) || 0
                    grand_dp_order += dp_order
                }

                total_tr = jumlah - diskon_tr

                grand_total += total_tr
                grand_diskon += diskon_tr
            })


            $('#discount_display').val(grand_diskon.toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 }))
            $('#total_display').val(grand_total.toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 }))
            $('#display_sisa').val((grand_total-grand_dp).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 }))
            $('#display_dp_order').val((grand_dp_order).toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 }))
            if(grand_dp > 0) {
                $('#dp').show()
                $('#sisa').show()
                $('#display_dp').val(grand_dp.toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 }))
            } else {
                $('#dp').hide()
                $('#sisa').hide()
                $('#display_dp').val(0)
            }
            $('#submit-all-form').show()
        }

        function changeDp(element) {
            const discount = element.parentNode.previousElementSibling
            const elementCurrent = element.parentNode.nextElementSibling.style["display"]
            if(elementCurrent === "none") {
                discount.querySelector('select').disabled = true;
                discount.querySelector('input').disabled = true;
                element.value = 1
                element.parentNode.nextElementSibling.style.setProperty("display", "flex", "important")
            } else {
                element.value = 0
                element.parentNode.nextElementSibling.style.setProperty("display", "none", "important")
                discount.querySelector('select').disabled = false;
                discount.querySelector('input').disabled = false;
            }
        }

        function changeOpsi(element) {
            const elementCurrent = element.parentNode.nextElementSibling.style["display"]
            if(elementCurrent === "none") {
                element.value = 1
                element.parentNode.nextElementSibling.style.setProperty("display", "flex", "important")
            } else {
                element.value = 0
                element.parentNode.nextElementSibling.style.setProperty("display", "none", "important")
            }
        }

        $('#date_payment').on('change', function() {
            const date = $(this).val()
            resetCurrency()
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'GET',
                dataType: 'json',
                data: { 'date' : date },
                url: '{{ route('finance.payments.transaction-purchase-payment') }}',
                success:function(response)
                {
                    if(response.message === "Success") {
                        const current_year = '{{ \Carbon\Carbon::parse($head->date_payment)->year }}'
                        let number = '{{ $head->number }}'
                        const year = new Date(date).getFullYear();
                        const month = (new Date(date).getMonth() + 1).toString().padStart(2, '0');
                        if(year != current_year) {
                            number = response.data.toString()
                        }
                        number = number.padStart(4, '0');

                        $('#no_transactions').val(`PVP.${year}-${month}-${number}`)
                    }
                }
            })
        })

        function getCurrencyVia() {
            const form = $('.form-wrapper')
            form.each(function () {
                const currencySelect = $(this).find('select[name="other_currency_type"]')
                currencySelect.append(opsiCurrency)
                getTotal(currencySelect[0])
            })
        }

        function resetCurrency(justOpsi) {
            const date = $('#date_payment').val()
            const currency = $('#currency_head_id').val()
            if(!justOpsi) {
                const form = $('.form-wrapper')
                form.each(function () {
                    const currencySelect = $(this).find('select[name="other_currency_type"]')
                    currencySelect.html('')
                })
                opsiCurrency = ''
            }

            if(date && currency) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'GET',
                    dataType: 'json',
                    url: '{{ route('finance.exchange-by-date') }}',
                    data: { 'date': date, 'currency_from': currency },
                    success:function(response) {
                        if(response.message === "Success") {
                            response.data.forEach(el => {
                                let dataExchange = 1;
                                let name = '';
                                if(el.from_currency_id == currency) {
                                    dataExchange = el.from_nominal/el.to_nominal
                                    name = el.to_currency.initial
                                } else {
                                    dataExchange = el.to_nominal/el.from_nominal
                                    name = el.from_currency.initial
                                }
                                const newOption = `<option value=${el.id} data-exchange='${dataExchange}'>${name}</option>`
                                opsiCurrency += newOption
                            })
                            if(!justOpsi) {
                                getCurrencyVia()
                            }
                        }
                    }
                })
            }
        }

        function toggleChargeType(element) {
            const row = element.closest('tr');
            const chargeType = element.value;
            const coaApSelect = $(row.querySelector('.coa-ap-select'));
            const payableSection = row.querySelector('.payable-section');
            const accountSection = row.querySelector('.account-section');
            const amountInput = row.querySelector('input[name="detail_jumlah"]');
            const dateInput = row.querySelector('input[name="detail_date"]');
            
            if (chargeType === 'account') {
                payableSection.style.display = 'none';
                accountSection.style.display = 'block';
                coaApSelect.prop('disabled', false);
                coaApSelect.val('').trigger('change');
                amountInput.removeAttribute('readonly');
                dateInput.value = '';
                dateInput.removeAttribute('readonly');
            } else {
                payableSection.style.display = 'block';
                accountSection.style.display = 'none';
                amountInput.setAttribute('readonly', 'readonly');
                dateInput.setAttribute('readonly', 'readonly');
            }
        }
    </script>
@endpush
