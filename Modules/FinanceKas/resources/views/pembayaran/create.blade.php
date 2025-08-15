@extends('layouts.app')
@section('content')
@push('styles')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endpush

<div class="main-content app-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">
        <form action="{{ route('finance.kas.pembayaran.store') }}" method="POST" enctype="multipart/form-data" name="dynamic-form">
            @csrf
            <!-- PAGE-HEADER -->
            <div class="page-header mb-0">
                <h1>Pembayaran</h1>
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
                                <!-- <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Customer</label>
                                        <div class="d-flex d-inline">
                                            <select class="form-control select2 form-select"
                                                data-placeholder="Choose One" name="customer_id" id="customer_id">
                                                <option label="Choose One" selected disabled></option>
                                                @foreach ($contact as $c)
                                                    <option value="{{ $c->id }}">{{ $c->customer_name }}</option>   
                                                @endforeach
                                            </select>
                                            <div id="btn_edit_contact"></div>
                                        </div>
                                        <a data-bs-effect="effect-scale" data-bs-toggle="modal" href="#modal-create"><i class="fe fe-plus me-1"></i>Add new Customer</a>
                                    </div>
                                </div> -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="currency_id" class="form-label">Currency</label>
                                        <select class="form-control select2 form-select"
                                            data-placeholder="Choose Currency" name="currency_id" id="currency_id">
                                            <option label="Choose Currency"></option>
                                            @foreach($currencies as $c)
                                                <option value="{{ $c->id }}">{{ $c->initial }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="account_head_id" class="form-label">Dari Akun</label>
                                        <select class="form-control select2 form-select"
                                            data-placeholder="Choose One" name="account_head_id" id="account_head_id" >
                                            <option label="Choose One"></option>
                                        </select>
                                        <a data-bs-effect="effect-scale" data-bs-toggle="modal" href="#modaldemo8"><i class="fe fe-plus me-1"></i>Add new Account</a>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="date" class="form-label">Date</label>
                                        <input type="date" class="form-control" name="date" id="date">
                                    </div>
                                </div>
                          </div>
                          <div class="row">
                              <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="no_transactions" class="form-label">Nomor Transaksi <a data-bs-effect="effect-scale" data-bs-toggle="modal" href="#modal-transaction-format"><i class="fa fa-cog"></i></a></label>
                                        <input type="text" name="no_transactions" id="no_transactions" class="form-control" readonly placeholder="Choose Transaction" />
                                    </div>
                              </div>
                              <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="description" class="form-label">Description</label>
                                        <input type="text" class="form-control" name="description" id="description" placeholder="Desc"/>
                                    </div>
                              </div>
                          </div>
                          <!-- <div class="row">
                              <div class="col-md-6">
                                  <label class="custom-control custom-radio">
                                      <input type="checkbox" class="custom-control-input" id="choose_job_order" name="choose_job_order" value="0">
                                      <span class="custom-control-label"><b>Choose Job Order</b></span>
                                  </label>
                              </div>
                          </div>
                          <div id="job_order_display" style="display: none">
                              <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="job_order" class="form-label">Job Order</label>
                                        <select class="form-control select2 form-select"
                                            data-placeholder="(Job Order No) - Export/Import" name="job_order" id="job_order">
                                            <option label="(Job Order No) - Export/Import"></option>
                                        </select>
                                    </div>
                                </div>
                                  <div class="col-md-4">
                                      <div class="form-group">
                                          <label for="" class="form-label">Consignee</label>
                                          <input type="text" class="form-control" name="consignee" id="consignee" placeholder="Link" readonly>
                                      </div>
                                  </div>
                                  <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="shipper" class="form-label">Shipper</label>
                                        <input type="text" class="form-control" name="shipper" id="shipper" placeholder="Link" readonly>
                                    </div>
                                </div> 
                              </div>
                              <div class="row">
                                  <div class="col-md-4">
                                      <div class="form-group">
                                          <label for="transportation" class="form-label">Transportation</label>
                                          <input type="text" class="form-control" name="transportation" id="transportation" placeholder="Link" readonly>
                                      </div>
                                      <label class="custom-control custom-radio" id="transportation_desc" style="display:none">
                                        <input type="radio" class="custom-control-input" name="transportation_desc" checked>
                                        <span class="custom-control-label"></span>
                                    </label>
                                  </div>
                                  <div class="col-md-4">
                                      <div class="form-group">
                                          <label for="commodity" class="form-label">Commodity</label>
                                          <input type="text" class="form-control" name="commodity" id="commodity" placeholder="Link" readonly>
                                      </div>
                                  </div>
                              </div>
                          </div> -->
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
                                          <th style="min-width:15rem;">Account</th>
                                          <th style="min-width:15rem;">Total</th>
                                          <th>#</th>
                                      </tr>
                                  </thead>
                                  <tbody id="form-container">
                                  </tbody>
                              </table>
                            </div>
                            <div class="row mt-2">
                                <a href="javascript:void(0)" class="btn btn-default"
                                    id="add-form">
                                    <span><i class="fa fa-plus"></i></span> Add New Column
                                </a>
                            </div>
                            <div style="display: flex; justify-content: flex-end">
                              <div class="row" style="margin-top: 1rem;">
                                <div style="height: 0.05rem; background-color: #000"></div>
                                <div style="display: flex; justify-content: space-between; margin-top: 1rem;">
                                  <input placeholder="Total" disabled style="width: 30px; background-color: transparent; outline: none; border: none;">
                                  <input id="total_prices" disabled placeholder="0" style="width: 100%; background-color: transparent; outline: none; border: none; direction: rtl;"/>
                                </div>
                              </div>
                            </div>
                            <div class="mt-3" style="text-align: right; margin-bottom: 1.5rem;">
                              <a href='javascript: history.go(-1)' class="btn btn-white color-grey">Close</a>
                              <button type="submit" id="submit-all-form" class="btn btn-primary">Save</button>
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

{{-- modal transaction format --}}
<div class="modal fade" id="modal-transaction-format" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pengaturan Nomor Transaksi</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-primary">
                            <div class="tab-menu-heading tab-menu-heading-boxed">
                                <div class="tabs-menu tabs-menu-border">
                                    <!-- Tabs -->
                                    <ul class="nav panel-tabs">
                                        <li><a href="#tab29" class="active" data-bs-toggle="tab">Custom Format</a></li>
                                        <li><a href="#tab30" data-bs-toggle="tab">Tambah Baru</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="panel-body tabs-menu-body mt-3">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab29">
                                        <div class="d-flex d-inline">
                                            <select class="form-control select2 form-select"
                                                data-placeholder="Select..." id="select-no-transactions" required>
                                                <option label="Select..." selected disabled></option>
                                                @foreach ($no_transactions as $t)
                                                    <option value="{{ $t->id }}">{{ $t->template }}{{ $t->number }}</option>   
                                                @endforeach
                                            </select>
                                            <button class="btn text-danger btn-sm" id="delete_option">
                                                <span class="fe fe-trash-2 fs-14"></span>
                                            </button>
                                        </div>
                                        <br><br>
                                        <div class="mt-3" style="text-align: right">
                                            <a class="btn btn-white color-grey" data-bs-dismiss="modal">Close</a>
                                            <button type="submit" id="change-transaction-num" class="btn btn-primary" class="btn-close" data-bs-dismiss="modal">Save</button>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="tab30">
                                        <form action="{{ route('finance.kas.transaction-kas-out.store') }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-4 mb-2">
                                                    <input type="text" class="form-control" name="head-code" placeholder="Example: INV"  />
                                                </div>
                                                <div class="col-md-4 mb-2">
                                                    <input type="text" class="form-control" disabled placeholder="{{ date('Y') }}" />
                                                </div>
                                                <div class="col-md-4 mb-2">
                                                    <input type="text" class="form-control" name="tail-code" placeholder="Example: XV" />
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="start-code" class="form-label">Mulai Dari Nomor</label>
                                                <input type="text" class="form-control" name="start-code" id="start-code" >
                                            </div>
                                            <br><br>
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
        let opsi = []

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

        $('#currency_id').on('change', function() {
            getAccountDetail()
        })

        $('#change-transaction-num').on('click', function(event){
            event.preventDefault();

            var selectedOption = $('#select-no-transactions option:selected').text();
            $('#no_transactions').val(selectedOption);
        });

        document.addEventListener('DOMContentLoaded', function() {
            var deleteButton = document.getElementById('delete_option');
            deleteButton.addEventListener('click', function(event) {
                var selectElement = document.getElementById('select-no-transactions');
                var selectedOption = selectElement.options[selectElement.selectedIndex];
                if (!selectedOption.value) {
                    alert('No option selected');
                    return;
                }
                var confirmDelete = confirm('Are you sure want to delete this item?');
                
                if (confirmDelete) {
                    var id = selectedOption.value;
                    var url = "{{ route('finance.kas.transaction-kas-out.destroy', ':id') }}".replace(':id', id);
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'DELETE',
                        dataType: 'json',
                        url: url,
                        success: function(response) {
                            if(response.message === "Success") {
                                alert('Item deleted successfully');
                                selectedOption.remove();
                                $('#no_transactions').val('');
                            } else {
                                alert('Error')
                            }
                        }
                    })
                }
            });
        });

        $(document).ready(function () {
            $("input:checkbox[name^='choose_job_order']").on('change', function () {
                const job_order_display =  $("#job_order_display");
                if ($('#choose_job_order').prop('checked')) {
                    job_order_display.show();
                    $('#choose_job_order').val("1")
                } else {
                    job_order_display.hide();
                    $('#choose_job_order').val("0")
                }
            });
        })

        function getAccountDetail() {
            var currency_id = $('#currency_id').val()
            const selectElementHead = document.querySelector('select[name="account_head_id"]')
            const details = document.querySelectorAll('.form-wrapper')

            let defaultOption = document.createElement("option");
            defaultOption.label = "Choose One";
            selectElementHead.innerHTML = ""
            selectElementHead.add(defaultOption);

            opsi = []

            details.forEach(el => {
                const selectElement = el.querySelector('select[name="account_detail_id"]')
                defaultOption = document.createElement("option");
                defaultOption.label = "Choose One";
                selectElement.innerHTML = ""
                selectElement.add(defaultOption)
            })

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
                        response.data.forEach(d => {
                            let newOption = new Option(d.account_name, d.id);
                            opsi.push(newOption.outerHTML)
                            selectElementHead.add(newOption)
                            details.forEach(el => {
                                const selectElement = el.querySelector('select[name="account_detail_id"]')
                                newOption = new Option(d.account_name, d.id)
                                selectElement.add(newOption)
                            })
                        })
                    }
                }
            })
        }

        $('#customer_id').on('change', function () {
            getJobOrder()
        })

        function getJobOrder() {
            var contact = $('#customer_id').val();
            const selectElement = document.getElementById("job_order");
            selectElement.innerHTML = ""
            const defaultOption = document.createElement("option");
            defaultOption.label = "(Job Order No) - Export/Import";
            selectElement.add(defaultOption);

            $('#consignee').val('')
            $('#shipper').val('')
            $('#transportation').val('')
            $('#transportation_desc').hide()
            $('#commodity').val('')
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'GET',
                dataType: 'json',
                url: '{{ route('finance.kas.job-order') }}',
                data: { 'contact': contact },
                success: function(response) {
                    if(response?.data) {
                        response.data.forEach(function(item) {
                            const option = document.createElement('option')
                            option.value = item.id + ":" + item.source
                            option.text = item.job_order_id + " - " + item.source
                            selectElement.add(option)
                        })
                    }
                }
            })
        }

        $('#job_order').on('change', function() {
            $('#consignee').val('')
            $('#shipper').val('')
            $('#transportation').val('')
            $('#transportation_desc').hide()
            $('#commodity').val('')
            var job_order = $(this).val()
            var split = job_order.split(':')
            var job_order_id = split[0]
            var job_order_source = split[1]
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'GET',
                dataType: 'json',
                url: '{{ route('finance.kas.job-order.details') }}',
                data: { 'job_order_id': job_order_id,
                        'job_order_source' : job_order_source
                },
                success: function(response) {
                    $('#consignee').val(response.consignee)
                    $('#shipper').val(response.shipper)
                    let transportation = ''
                    if(response.transportation === 1) {
                        transportation = 'Air Freight'
                    } else if(response.transportation === 2) {
                        transportation = 'Sea Freight'
                    } else if(response.transportation === 3) {
                        transportation = 'Land Trucking'
                    }
                    $('#transportation').val(transportation)
                    if(response.transportation_desc) {
                        $('#transportation_desc').show()
                        $('#transportation_desc span').text(response.transportation_desc)
                    }
                    $('#commodity').val(response.description)
                }
            })
        })

        document.getElementById('add-form').addEventListener('click', function() {
            var formContainer = document.getElementById('form-container');
            var newFormWrapper = document.createElement('tr');
            newFormWrapper.classList.add('form-wrapper');
            newFormWrapper.classList.add('group-form');
        
            var formTemplate = `
            <td><a href="javascript:void(0)"
                class="arrow-display" onclick="toggleList(this)">
                <span style="color: #000; margin-top: 1rem">&#8743;</span>
                </a>
            </td>
            <td>
                <input type="text" class="form-control description-input" name="desc_detail" placeholder="Desc..." />
                <label class="form-label remark-input">Remark</label>
                <input type="text" class="form-control remark-input mt-2" name="remark_detail" placeholder="Text..." />
            </td>
            <td>
                <div class="form-group">
                    <select class="form-control select2 form-select"
                        data-placeholder="Choose One" name="account_detail_id">
                        <option label="Choose One"></option>
                        ${opsi.join('')}
                    </select>
                </div>
            </td>
            <td>
                <input type="text" class="form-control" placeholder="Nominal" name="price_detail" onchange="calculate()"/>
            </td>
            <td>
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn delete-row" onclick="deleteList(this)"><i class="fa fa-trash text-danger delete-form"></i></button>
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

        function toggleList(arrow) {
            var toggleElement = arrow.parentNode;
            var remarkInput = toggleElement.nextElementSibling.querySelectorAll('.remark-input');
            var toggle = arrow.querySelector("span")

            remarkInput.forEach(element => {
            if(element.style['display'] === 'none') {
                toggle.innerHTML = "&#8743;"
                element.style['display'] = 'block';
            } else {
                toggle.innerHTML = "&#8744;"
                element.style['display'] = 'none';
            }
            });
        }

        function deleteList(event) {
            event.closest('tr').remove();
        }

        function calculate() {
            const tr = document.querySelectorAll('.form-wrapper')
            let grand_total = 0;
            tr.forEach(el => {
                const input = el.querySelector('input[name="price_detail"]')
                let total = input.value
                if(!total) total = "0"
                total = parseFloat(total.replace(/,/g, ''))

                if(total > 0) {
                    input.value = total.toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
                }
                grand_total += total
            })
            $('#total_prices').val(grand_total.toLocaleString('en', { minimumFractionDigits: 2, maximumFractionDigits: 2 }))
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