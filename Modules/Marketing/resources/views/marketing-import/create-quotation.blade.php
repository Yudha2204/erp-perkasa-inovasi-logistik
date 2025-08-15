@extends('layouts.app')
@push('styles')
<style>
    .form-control:focus {
        box-shadow: none;
        border-color: #d1d3e2;
    }
    .btn:focus {
        box-shadow: none !important;
        background-color: unset;
    }
</style>
@endpush
@section('content')

<div class="main-content app-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

         <!-- PAGE-HEADER -->
         <div class="page-header mb-0">
            <h1>Marketing</h1>
        </div>
        <h4>Add New Quotation</h4>
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
        <!-- PAGE-HEADER END -->
        <form action="{{ route('marketing.import.store-quotation') }}" method="post">
            @csrf
            @if ($dataMarketingImport)
                <input type="hidden" name="marketing_import_id" value="{{ $dataMarketingImport->id }}">
            @endif
                <!-- ROW-1 -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Bill To</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Customer</label>
                                            <div class="d-flex d-inline">
                                                @if ($dataMarketingImport)
                                                    <select class="form-control select2 form-select"
                                                        data-placeholder="Choose one" name="customer" id="customer" disabled>
                                                        <option label="Choose one" selected disabled></option>
                                                        @foreach ($contact as $c)
                                                        @if (in_array(1, json_decode($c->type)))
                                                            <option value="{{ $c->id }}" {{ $c->id == $dataMarketingImport->contact_id ? 'selected' : '' }}>{{ $c->customer_name }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                @else
                                                    <select class="form-control select2 form-select"
                                                        data-placeholder="Choose one" name="customer" id="customer" >
                                                        <option label="Choose one" selected disabled></option>
                                                        @foreach ($contact as $c)
                                                            @if (in_array(1, json_decode($c->type)))
                                                            <option value="{{ $c->id }}">{{ $c->customer_name }}</option>   
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                @endif
                                                <div id="btn_edit_contact"></div>
                                            </div>
                                            <a data-bs-effect="effect-scale" data-bs-toggle="modal" href="#modal-create"><i class="fe fe-plus me-1"></i>Create New Customer</a>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Phone Number</label>
                                            <input class="form-control mb-4" id="phone_number" type="number" @if ($dataMarketingImport)
                                                value="{{ $dataMarketingImport->contact->phone_number }}"
                                            @endif disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Company Address</label>
                                            <input class="form-control mb-4" id="company_address" type="text" @if ($dataMarketingImport)
                                            value="{{ $dataMarketingImport->contact->address }}"
                                        @endif disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Company Name</label>
                                            <input class="form-control mb-4" id="company_name" type="text" @if ($dataMarketingImport)
                                            value="{{ $dataMarketingImport->contact->company_name }}"
                                        @endif disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Payment Term</label>
                                            <div class="term-payment-class">
                                                <input class="form-control mb-4" id="payment_term[0]" type="text" disabled>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Customer ID</label>
                                            <input class="form-control mb-4" id="get_customer_id" type="text" @if ($dataMarketingImport)
                                            value="{{ $dataMarketingImport->contact->customer_id }}"
                                        @endif disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Origin</label>
                                            <input class="form-control mb-4" id="origin" type="text" @if ($dataMarketingImport)
                                            value="{{ $dataMarketingImport->origin }}"
                                        @endif disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Destination</label>
                                            <input class="form-control mb-4" id="destination" type="text" @if ($dataMarketingImport)
                                            value="{{ $dataMarketingImport->destination }}"
                                        @endif disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Quotation</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Date</label>
                                            <input class="form-control mb-4" name="date" placeholder="Fill the text" type="date">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Quotation No</label>
                                            <input class="form-control mb-4" name="quotation_no" placeholder="Fill the text" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Valid Until</label>
                                            <input class="form-control mb-4" name="valid_until" placeholder="Fill the text" type="date">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Shipper</label>
                                            <input class="form-control mb-4" id="shipper" type="text" @if ($dataMarketingImport)
                                            value="{{ $dataMarketingImport->shipper }}"
                                        @endif readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Consignee</label>
                                            <input class="form-control mb-4" id="consignee" type="text" @if ($dataMarketingImport)
                                            value="{{ $dataMarketingImport->consignee }}" 
                                        @endif readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Project Desc</label>
                                            <textarea class="form-control mb-4" name="project_desc" placeholder="Fill the text"
                                                rows="4"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">Total Weight</label>
                                            <input class="form-control mb-4" name="total_weight" type="text" @if ($dataMarketingImport)
                                            value="{{ $dataMarketingImport->total_weight }}" 
                                        @endif readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">Total Volume</label>
                                            <input class="form-control mb-4" name="total_volume" type="text" @if ($dataMarketingImport)
                                            value="{{ $dataMarketingImport->total_volume }}" 
                                        @endif readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label" for="currency">Currency</label>
                                            <select class="form-control select2 form-select"
                                                data-placeholder="Choose one" name="currency" id="currency" >
                                                <option label="Choose one" selected disabled></option>
                                                @foreach ($currencies as $c)
                                                    <option value="{{ $c->initial }}">{{ $c->initial }}</option>   
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- ROW-1 END -->
                <!-- row 2 -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>DESCRIPTION</th>
                                            <th>TOTAL</th>
                                            <th>REMARK</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="table-body">
                                        <!-- Group forms will be added dynamically here -->
                                    </tbody>
                                </table>

                                <a href="javascript:void(0)" class="btn border btn-block" style="background-color: #DADADA" id="add-group">
                                    <i class="fa fa-plus"></i> Add new group
                                </a>

                                <div class="row justify-content-end">
                                    <div class="col-lg-6">
                                        <table width="50%" class="table table-bordered mt-5">
                                            <tr>
                                                <td colspan="4">
                                                    <div class="d-flex justify-content-between">
                                                        <div>
                                                            <strong>TOTAL</strong>
                                                        </div>
                                                        <div>
                                                            <strong id="overall-total-currency">IDR</strong>
                                                            <input type="text" id="overall-total" name="total_all" readonly style="border: 0px; text-align: right" >
                                                            {{-- <strong id="overall-total"> 0</strong> --}}
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--row 4-->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <div class="btn-list text-end">
                                <a href="javascript: history.go(-1)" class="btn btn-default">Cancel</a>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
                <br><br><br><br>
                <!--row 4-->
        </form>
        </div>
        <!-- CONTAINER END -->
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
$(document).ready(function () {
    $('#customer').select2('destroy').select2({
        placeholder: "Choose One"
    });

    //show edit btn customer when customer id was selected
    $('select[name="customer"]').change(function () {
        var id_contact = this.value;

        $("#btn_edit_contact").html("");
        var editContactBtn = $(`<a href="javascript:void(0)" id="btn-edit" data-id="${id_contact}" class="btn text-primary btn-sm mt-2" data-bs-toggle="tooltip" data-bs-original-title="Edit data customer"><span class="fe fe-edit fs-14"></span></a>`);
        editContactBtn.appendTo('#btn_edit_contact');
    });

     //show edit data
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

    //fetch data customer
    $('#customer').on('change', function() {
        var id = $(this).val();
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'GET',
                dataType: 'json',
                data: {'id': id},
                url: '{{ route('marketing.import.getDataCustomer') }}',
                success:function(response)
                {
                    $('#phone_number').val(response.data.phone_number);
                    $('#company_address').val(response.data.address);
                    $('#company_name').val(response.data.company_name);

                    $('.term-payment-class .another-term-payment').remove()
                    response.data?.term_payment_contacts.forEach((el, idx) => {
                        const element = `#payment_term\\[${idx}\\]`
                        if(idx === 0) {
                            $(element).val(el.term_payment.name);
                        } else {
                            const newInput = document.createElement('input');
                            newInput.className = 'form-control mb-4 another-term-payment';
                            newInput.id = `payment_term[${idx}]`;
                            newInput.type = 'text';
                            newInput.disabled = true;
                            newInput.value = el.term_payment.name;

                            document.querySelector('.term-payment-class').append(newInput)
                        }
                    })
                    $('#get_customer_id').val(response.data.customer_id);       
                }
            });
    });


    //Customisasi group here
    let groupCount = 0;
    // Function to add a new group
    function addGroup() {
        if (groupCount < 26) { // Check if groupCount is less than 26 (A to Z)
            let groupChar = String.fromCharCode(65 + groupCount); // ASCII code for A is 65
            let newRow = `
            <tr class="group-form" data-group="${groupChar}">
                <input type="hidden" name="alphabet" value="${groupChar}">
                <td width="50%">
                    <input type="text" class="form-control description-input" name="description_${groupChar}[]" />
                    <input type="hidden" class="form-control description-input" name="group_count" value="${groupCount}" />
                </td>
                <td width="20%">
                    <div class="d-flex align-items-center justify-content-between"">
                        <div class="currency-text">IDR</div>
                        <input type="text" style="text-align: right; margin-left: 5px" class="form-control border-left-0 pl-2 text-right total-input" name="total_${groupChar}[]" data-group="${groupChar}" autocomplete="off" />
                    </div>
                </td>
                <td width="20%">
                    <input type="text" class="form-control remark-input" name="remark_${groupChar}[]" />
                </td>
                <td width="10%">
                    <div class="d-flex justify-content-between">
                        <button type="button" class="btn delete-row"><i class="fa fa-trash text-danger"></i></button>
                        <button type="button" class="btn add-row"><i class="fa fa-plus text-primary"></i></button>
                    </div>
                </td>
            </tr>
            <tr class="group-total" data-group="${groupChar}" style="background-color: #C0E3FF;">
                <td>
                    <div class="text-white bg-primary p-2 group-text" style="border-radius: 5px;">Total ${groupChar}</div>
                </td>
                <td>
                    <div class="text-white bg-primary p-2" style="border-radius: 5px;">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="currency-text">IDR</div>
                            <div class="group-total-value">0</div>
                        </div>
                    </div>
                </td>
                <td></td>
                <td>
                    <button type="button" class="btn remove-group"><i class="fa fa-trash text-danger"></i></button>
                </td>
            </tr>
            `;

            $('#table-body').append(newRow);
            groupCount++;
        } else {
            alert("You have reached the maximum limit of groups (A to Z).");
        }
    }

    // Function to update the total for a group
    function updateGroupTotal(groupChar) {
        let total = 0;
        $(`tr[data-group=${groupChar}]`).each(function () {
            let rowTotal = $(this).find('.total-input').val();

            // Check if rowTotal is not empty, contains only digits and commas, and is not zero
            if (/^\d+([\,]\d+)*$/.test(rowTotal) && parseFloat(rowTotal.replace(/,/g, '')) !== 0) {
                let numericValue = rowTotal.replace(/,/g, ''); // Remove commas for calculation
                total += parseInt(numericValue) || 0; // Use parseInt to treat the value as an integer
            }
        });
        let formattedTotal = total > 0 ? total.toLocaleString() : '0'; // Format the total with commas or display "0"
        $(`tr.group-total[data-group=${groupChar}]`).find('.group-total-value').text(formattedTotal);
        updateOverallTotal();
    }

    // Function to update the overall total
    function updateOverallTotal() {
        let overallTotal = 0;
        $('.group-total-value').each(function () {
            let groupTotal = $(this).text();

            // Check if groupTotal is not empty, contains only digits and commas, and is not zero
            if (/^\d+([\,]\d+)*$/.test(groupTotal) && parseFloat(groupTotal.replace(/,/g, '')) !== 0) {
                let numericValue = groupTotal.replace(/,/g, ''); // Remove commas for calculation
                overallTotal += parseInt(numericValue) || 0; // Use parseInt to treat the value as an integer
            }
        });
        let formattedOverallTotal = overallTotal > 0 ? overallTotal.toLocaleString() : '0'; // Format the overall total with commas or display "0"
        // $('#overall-total').text(` ${formattedOverallTotal}`);
        $('#overall-total').val(` ${formattedOverallTotal}`);
    }

    // Event handler to add a new group
    $('#add-group').click(function () {
        addGroup();
    });

    // Event handler to add a new entry row
    $('#table-body').on('click', '.add-row', function () {
        let groupChar = $(this).closest('tr.group-form').data('group');
        let newRow = addGroupRow(groupChar); // Store the new row in a variable
        $(this).closest('tr.group-form').after(newRow); // Add the new row after the current group
    });

    // Function to add a new row within a group
    function addGroupRow(groupChar) {
        let newRow = `
        <tr class="group-form" data-group="${groupChar}">
            <td width="50%">
                <input type="text" class="form-control description-input" name="description_${groupChar}[]" />
                <input type="hidden" class="form-control description-input" name="group_count" value="${groupCount}" />
            </td>
            <td width="20%">
                <div class="d-flex align-items-center justify-content-between"">
                    <div class="currency-text">IDR</div>
                    <input type="text" style="text-align: right; margin-left: 5px" class="form-control border-left-0 pl-2 text-right total-input" name="total_${groupChar}[]" data-group="${groupChar}" autocomplete="off" />
                </div>
            </td>
            <td width="20%">
                <input type="text" class="form-control remark-input" name="remark_${groupChar}[]" />
            </td>
            <td width="10%">
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn delete-row"><i class="fa fa-trash text-danger"></i></button>
                    <button type="button" class="btn add-row"><i class="fa fa-plus text-primary"></i></button>
                </div>
            </td>
        </tr>
        `;

        return newRow;
    }

    // Event handler to remove a row
    $('#table-body').on('click', '.delete-row', function () {
        let groupChar = $(this).closest('tr.group-form').data('group');

        // Check if there is more than one row in the group
        if ($(`tr.group-form[data-group=${groupChar}]`).length > 1) {
            $(this).closest('tr.group-form').remove();
            updateGroupTotal(groupChar);
        } else {
            alert("Cannot delete the last row in a group.");
        }
    });

    // Event handler to remove a group
    $('#table-body').on('click', '.remove-group', function () {
        if (confirm('Are you sure you want to remove this group?')) {
            let groupChar = $(this).closest('tr.group-total').data('group');

            $(`tr[data-group=${groupChar}]`).remove();
            groupCount--;

            reassignGroupNames();
            updateOverallTotal();
        }
    });

    // Function to reassign group names
    function reassignGroupNames() {
        let groupChar = 'A';
        $('#table-body').find('.group-total').each(function () {
            let currentGroupChar = $(this).data('group');
            $(this).data('group', groupChar);
            $(this).find('.group-text').text(`Total ${groupChar}`);
            $(`tr[data-group=${currentGroupChar}]`).attr('data-group', groupChar).find('input').each(function () {
                let name = $(this).attr('name');
                $(this).attr('name', name.replace(currentGroupChar, groupChar));
                $(this).data('group', groupChar);
            });
            groupChar = String.fromCharCode(groupChar.charCodeAt(0) + 1);
        });
    }

    // Event handler to update total on total input change
    $('#table-body').on('input', '.total-input', function () {
        let groupChar = $(this).data('group');
        console.log(groupChar)
        let inputValue = $(this).val();

        // Check if inputValue is not empty and contains only digits and commas
        if (/^\d+([\,]\d+)*$/.test(inputValue)) {
            let numericValue = inputValue.replace(/,/g, ''); // Remove existing commas
            let formattedValue = numericValue.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            $(this).val(formattedValue);
            updateGroupTotal(groupChar);
        } else {
            // Handle non-numeric input here (optional)
            updateGroupTotal(groupChar); // Recalculate even if the input is empty or non-numeric
        }
    });

    // Event handler to update currency
    $('#currency').on('change', function () {
        if ($(this).val() == "") {
            $('.currency-text').text('IDR')
            $('#overall-total-currency').text('IDR')
        } else {
            $('.currency-text').text($(this).val())
            $('#overall-total-currency').text($(this).val())
        }
    })

    // Initial group
    addGroup();

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

</script>

@endpush