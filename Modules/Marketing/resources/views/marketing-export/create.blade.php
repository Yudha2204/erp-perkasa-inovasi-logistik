@extends('layouts.app')
@section('content')

<div class="main-content app-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">
        <form action="{{ route('marketing.export.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if ($marketingExportId)
                <input type="hidden" name="marketing_export_id" value="{{ $marketingExportId }}">
            @endif
            <!-- PAGE-HEADER -->
            <div class="page-header mb-0">
                <h1>Marketing</h1>
            </div>
            <h4>Add New Marketing Export</h4>
            <!-- PAGE-HEADER END -->

            <div class="row">
                <div class="col-md-4">
                    <div class="card">
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
                            <div class="col-10 mx-auto form-group">
                                <label class="form-label">Domestik / International</label>
                                <select name="expedition"   class="form-control form-select" data-bs-placeholder="Select expedition">
                                    <option value="1">Domestik</option>
                                    <option value="2">International</option>
                                </select>
                            </div>
                            <div class="col-10 mx-auto form-group">
                                <label class="form-label">Transportation</label>
                                <select class="form-control select2 form-select"
                                    data-placeholder="Choose one" name="transportation">
                                    <option label="Choose one" selected disabled></option>
                                    <option value="1">Air Freight</option>
                                    <option value="2">Sea Freight</option>
                                    <option value="3">Land Trucking</option>
                                </select>
                            </div>
                            <div class="col-10 mx-auto form-group">
                                <div class="custom-controls-stacked">
                                    <div id="radio_buttons"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="row mb-5">
                            <div class="col-6 mx-3">
                                <div class="form-group">
                                    <label class="form-label">Customer</label>
                                    <div class="d-flex d-inline">
                                        <select class="form-control select2 form-select"
                                            data-placeholder="Choose one" name="customer_id" id="customer_id" required>
                                            <option label="Choose one" selected disabled></option>
                                            @foreach ($contact as $c)
                                                @if (in_array(1, json_decode($c->type)))
                                                <option value="{{ $c->id }}">{{ $c->customer_name }}</option>   
                                                @endif
                                            @endforeach
                                        </select>
                                        <div id="btn_edit_contact"></div>
                                    </div>
                                    <a data-bs-effect="effect-scale" data-bs-toggle="modal" href="#modal-create"><i class="fe fe-plus me-1"></i>Create New Customer</a>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">No. PO</label>
                                    <input type="text" class="form-control" name="no_po" id="no_po" value="{{ old('no_po') }}"  >
                                </div>
                            </div>
                            <div class="col-5">
                                <div class="form-group">
                                    <label for="" class="form-label">Commodity/Description</label>
                                    <textarea class="form-control mb-4" placeholder="Description" rows="5" name="description"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">No. CIPL</label>
                                        <input type="text" class="form-control" name="no_cipl" id="no_cipl" value="{{ old('no_cipl') }}" placeholder="fill the text.."  >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Total Weight</label>
                                        <input type="number" class="form-control" name="total_weight" id="total_weight" value="{{ old('weight') }}" placeholder="fill the text.."  >
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="volume" class="form-label">Total Volume</label>
                                        <input type="number" class="form-control" name="total_volume" id="total_volume" readonly>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="" class="form-label" style="color: white">.</label>
                                        <input type="text" class="form-control" name="freetext_volume" placeholder="free text" id="freetext_volume">
                                    </div>
                                </div>
                            </div>
                                
                            
                            <div class="row my-5">
                                
                                <h3 class="text-center">Dimension (Cm)</h3>
                                <a href="javascript:void(0)" class="btn btn-default"
                                    id="addDimension">
                                    <span><i class="fa fa-plus"></i></span> Add Column
                                </a>
                                <div
                                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-1 border-bottom">
                                </div>
                          
                            </div>


                            <div class="row">        
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Origin</label>
                                        <input type="text" class="form-control" name="origin" id="origin" value="{{ old('origin') }}" placeholder="fill the text.."  >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Destination</label>
                                        <input type="text" class="form-control" name="destination" id="destination" value="{{ old('destination') }}"  placeholder="fill the text.."  >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Shipper</label>
                                        <input type="text" class="form-control" name="shipper" id="shipper" value="{{ old('shipper') }}" placeholder="fill the text.."  >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Consignee</label>
                                        <input type="text" class="form-control" name="consignee" id="consignee" value="{{ old('consignee') }}"  placeholder="fill the text.."  >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Pickup Address</label>
                                        <input type="text" class="form-control" name="pickup_address" id="pickup_address" value="{{ old('pickup_address') }}"  placeholder="fill the text.."  >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Delivery Address</label>
                                        <input type="text" class="form-control" name="delivery_address" id="delivery_address" value="{{ old('delivery_address') }}"  placeholder="fill the text.."  >
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Document</label>
                                        <div class="form-group mb-0">
                                            <input id="demo" type="file" name="documents[]"
                                                accept=".pdf" multiple>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Remark</label>
                                        <textarea class="form-control mb-4" name="remark" placeholder="Fill the text"
                                            rows="4"></textarea>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Status</label>
                                        <div class="custom-controls-stacked">
                                            <label class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" name="status"
                                                    value="1" checked>
                                                <span class="custom-control-label">Hold</span>
                                            </label>
                                            <label class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" name="status"
                                                    value="2">
                                                <span class="custom-control-label">Selling</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

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

        {{-- <!-- FILE UPLOADES JS -->
    <script src="{{ url('assets/plugins/fileuploads/js/fileupload.js') }}"></script>
    <script src="{{ url('assets/plugins/fileuploads/js/file-upload.js') }}"></script>

    <!-- INTERNAL File-Uploads Js-->
    <script src="{{ url('assets/plugins/fancyuploder/jquery.ui.widget.js') }}"></script>
    <script src="{{ url('assets/plugins/fancyuploder/jquery.fileupload.js') }}"></script>
    <script src="{{ url('assets/plugins/fancyuploder/jquery.iframe-transport.js') }}"></script>
    <script src="{{ url('assets/plugins/fancyuploder/jquery.fancy-fileupload.js') }}"></script>
    <script src="{{ url('assets/plugins/fancyuploder/fancy-uploader.js') }}"></script> --}}

    <script>
        $(document).ready(function () {
            $('#customer_id').select2('destroy').select2({
                placeholder: "Choose One"
            });
            
            $('select[name="transportation"]').change(function () {
                if (this.value == '1') {
                    $("#radio_buttons").html("");
                    var radioBtn = $(`<label class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input"
                                                name="transportation_desc" value="Hand Carry">
                                            <span class="custom-control-label">Hand Carry</span>
                                        </label>
                                        <label class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input"
                                                name="transportation_desc" value="Express">
                                            <span class="custom-control-label">Express</span>
                                        </label>
                                        <label class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input"
                                                name="transportation_desc" value="Regular">
                                            <span class="custom-control-label">Regular</span>
                                        </label>`);
                    radioBtn.appendTo('#radio_buttons');
                }
                else if (this.value == '2') {
                    $("#radio_buttons").html("");
                    var radioBtn = $(`<label class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input"
                                                name="transportation_desc" value="FCL">
                                            <span class="custom-control-label">FCL</span>
                                        </label>
                                        <label class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input"
                                                name="transportation_desc" value="LCL">
                                            <span class="custom-control-label">LCL</span>`);
                    radioBtn.appendTo('#radio_buttons');
                }
                else if (this.value == '3') {
                    $("#radio_buttons").html("");
                }
            });

            //show edit btn customer when customer id was selected
            $('select[name="customer_id"]').change(function () {
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


            // Add Group Button Click
            $('#addDimension').on('click', function () {

                var currentDimension = $('tbody tr[class*="theDimensions"]').length;

                increaseNumDimensions = currentDimension + 1;

                // console.log(increaseNumDimensions)

                if (increaseNumDimensions == 1) {

                    var dimensions = `
                    <div class="table-responsive">
                        <table class="table table-bordered text-nowrap w-full datetimetable" id="dimensionTable"
                            border="0" cellspacing="5" cellpadding="5">
                            <thead>
                                <tr class="form-control-sm">
                                    <td class="text-center">Packages</td>
                                    <td class="text-center">Panjang</td>
                                    <td class="text-center">Lebar</td>
                                    <td class="text-center">Tinggi</td>
                                    <td class="text-center"></td>
                                    <td class="text-center">Quantity</td>
                                    <td class="text-center">Total</td>
                                    <td class=></td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="form-control-sm theDimensions`+ increaseNumDimensions + `">
                                    <td style="text-align: center;"><input type="text" name="packages[]" id="dimension[`+ increaseNumDimensions + `][packages]" data-row="` + increaseNumDimensions + `"
                                            placeholder="">
                                    </td>
                                    <td style="text-align: center;"><input type="number" name="panjang[]" min="0" id="dimension[`+ increaseNumDimensions + `][panjang]" class="inputan" data-row="` + increaseNumDimensions + `" onkeyup="calculateTotal(event, ` + increaseNumDimensions + `)"
                                            placeholder="" style="width:60px;">
                                    </td>
                                    <td style="text-align: center;"><input type="number" name="lebar[]" min="0" id="dimension[`+ increaseNumDimensions + `][lebar]" class="inputan" data-row="` + increaseNumDimensions + `" onkeyup="calculateTotal(event, ` + increaseNumDimensions + `)"
                                            placeholder="" style="width:60px;">
                                    </td>
                                    <td style="text-align: center;"><input type="number" name="tinggi[]" min="0" id="dimension[`+ increaseNumDimensions + `][tinggi]" class="inputan" data-row="` + increaseNumDimensions + `" onkeyup="calculateTotal(event, ` + increaseNumDimensions + `)"
                                            placeholder="" style="width:60px;">
                                    </td>
                                    <td style="text-align: center;"><input type="number" name="user_input[]" min="0" id="dimension[`+ increaseNumDimensions + `][user_input]" class="inputan" data-row="` + increaseNumDimensions + `" onkeyup="calculateTotal(event, ` + increaseNumDimensions + `)"
                                            placeholder="" style="width:80px;">
                                    </td>
                                    <td style="text-align: center;"><input type="number" name="quantity[]" min="0" id="dimension[`+ increaseNumDimensions + `][quantity]" class="inputan" data-row="` + increaseNumDimensions + `" onkeyup="calculateTotal(event, ` + increaseNumDimensions + `)"
                                            placeholder="" style="width:60px;">
                                    </td>
                                    <td style="text-align: center;"><input type="text" name="total[]" id="dimension[`+ increaseNumDimensions + `][total]" data-row="` + increaseNumDimensions + `"
                                            placeholder="" style="width:80px;" readonly>
                                    </td>
                                    <td style="text-align: center;">
                                        <a href="javascript:void(0)" class="btn text-danger remove-dimension"><span class="fe fe-trash-2 fs-14"></span></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>`;

                    $('#addDimension').before(dimensions);
                }

                if (increaseNumDimensions > 1) {

                    var newDimension = `
                        <tr class="form-control-sm theDimensions`+ increaseNumDimensions + `">
                            <td style="text-align: center;"><input type="text" name="packages[]" id="dimension[`+ increaseNumDimensions + `][packages]" data-row="` + increaseNumDimensions + `"
                                    placeholder="">
                            </td>
                            <td style="text-align: center;"><input type="number" name="panjang[]" min="0" id="dimension[`+ increaseNumDimensions + `][panjang]" class="inputan" data-row="` + increaseNumDimensions + `" onkeyup="calculateTotal(event, ` + increaseNumDimensions + `)"
                                    placeholder="" style="width:60px;">
                            </td>
                            <td style="text-align: center;"><input type="number" name="lebar[]" min="0" id="dimension[`+ increaseNumDimensions + `][lebar]" class="inputan" data-row="` + increaseNumDimensions + `" onkeyup="calculateTotal(event, ` + increaseNumDimensions + `)"
                                    placeholder="" style="width:60px;">
                            </td>
                            <td style="text-align: center;"><input type="number" name="tinggi[]" min="0" id="dimension[`+ increaseNumDimensions + `][tinggi]" class="inputan" data-row="` + increaseNumDimensions + `" onkeyup="calculateTotal(event, ` + increaseNumDimensions + `)"
                                    placeholder="" style="width:60px;">
                            </td>
                            <td style="text-align: center;"><input type="number" name="user_input[]" min="0" id="dimension[`+ increaseNumDimensions + `][user_input]" class="inputan" data-row="` + increaseNumDimensions + `" onkeyup="calculateTotal(event, ` + increaseNumDimensions + `)"
                                    placeholder="" style="width:80px;">
                            </td>
                            <td style="text-align: center;"><input type="number" name="quantity[]" min="0" id="dimension[`+ increaseNumDimensions + `][quantity]" class="inputan" data-row="` + increaseNumDimensions + `" onkeyup="calculateTotal(event, ` + increaseNumDimensions + `)"
                                    placeholder="" style="width:60px;">
                            </td>
                            <td style="text-align: center;"><input type="text" name="total[]" id="dimension[`+ increaseNumDimensions + `][total]" data-row="` + increaseNumDimensions + `"
                                    placeholder="" style="width:80px;" readonly>
                            </td>
                            <td style="text-align: center;">
                                <a href="javascript:void(0)" class="btn text-danger remove-dimension"><span class="fe fe-trash-2 fs-14"></span></a>
                            </td>
                        </tr>
                    `;

                    $('.theDimensions' + currentDimension).after(newDimension);
                }

            })

            var rowSelector = '.theDimensions1';

            // Add a common class to all input fields within the dynamic rows to simplify the selector.
            var inputSelector = rowSelector + ' input';

            // Attach the keyup event listener to all input fields within the dynamic rows.
            $(document).on('keyup', inputSelector, function () {
                // Find the parent row of the current input field.
                var currentRow = $(this).closest(rowSelector);

                // Retrieve values from input fields within the current row.
                var panjang = parseFloat(currentRow.find('input[id="panjang"]').val()) || 0;
                var lebar = parseFloat(currentRow.find('input[id="lebar"]').val()) || 0;
                var tinggi = parseFloat(currentRow.find('input[id="tinggi"]').val()) || 0;
                var user_input = parseFloat(currentRow.find('input[id="user_input"]').val()) || 1;
                var quantity = parseFloat(currentRow.find('input[id="quantity"]').val()) || 1;

                // Calculate the total for the current row.
                var result = panjang * lebar * tinggi / user_input * quantity;

                // Update the total input element within the current row with the result.
                currentRow.find('input[id="total"]').val(isNaN(result) ? "" : result.toFixed(2));
            });


            $(document).on('click', '.remove-dimension', function () {

                var deletedDimensionRow = $(this).closest('tr');

                deletedDimensionRow.remove();

                var remainingDimensionRows = $('tbody tr[class*="theDimensions"]');

                remainingDimensionRows.each(function (index) {

                    var numDimension = index + 1;

                    removeDimensionClass = $(this).removeClass();
                    addDimensionClass = $(this).addClass("form-control-sm theDimensions" + numDimension);

                    $(this).find('[id^="dimension["]').each(function () {

                        var oldName = $(this).attr('id');
                        var newName = oldName.replace(/\[\d+\]/, '[' + numDimension + ']');
                        $(this).attr('id', newName);
                    });
                });

                var getRemainingDimensionRowsAgain = $('tbody tr[class*="theDimensions"]');

                if (getRemainingDimensionRowsAgain.length == 0) {

                    var getDimensionLabel = $('h3[class$="dimension-label"]');

                    getDimensionLabel.remove();

                    var getTableResponsive = $('div[class="table-responsive"]');

                    getTableResponsive.remove();
                }

                if (getRemainingDimensionRowsAgain.length > 0) {

                    // calculateTotal();
                }
                calculateGrandTotal();
            });

            $('#new_marketing').submit(function () {

                var $inputs = $('#new_marketing :input');

                var values = {};
                $inputs.each(function () {
                    values[this.name] = $(this).val();
                });

                console.log(values);
                return false
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

        function calculateGrandTotal() {
            var grandTotal = 0;

            var elementsWithTheDimensionsClass = $('[class*="theDimensions"]');
            console.log(elementsWithTheDimensionsClass)

            $('[class*="theDimensions"]').each(function () {
                var row = $(this);

                grandTotal += parseFloat(row.find('input[id$="[total]"]').val()) || 0;
            });

            var resultString = grandTotal.toString();
    
            if (resultString.includes('.')) {
                // Jika hasilnya berupa angka desimal
                var decimalPart = resultString.split('.')[1];
                if (decimalPart.length > 3) {
                    // Jika angka desimal memiliki lebih dari 3 angka dibelakang koma
                    resultString = resultString.split('.')[0] + '.' + decimalPart.substring(0, 3);
                }
            }

            $('input[id="total_volume"]').val(parseFloat(resultString));
        }

        function calculateTotal(event, row) {

            var rowNumber = row;

            var grandTotal = 0;

            var panjangValue = $('input[id="dimension[' + rowNumber + '][panjang]"]').val();
            var lebarValue = $('input[id="dimension[' + rowNumber + '][lebar]"]').val();
            var tinggiValue = $('input[id="dimension[' + rowNumber + '][tinggi]"]').val();
            var user_inputValue = $('input[id="dimension[' + rowNumber + '][user_input]"]').val();
            var quantityValue = $('input[id="dimension[' + rowNumber + '][quantity]"]').val();

            var totalValue = panjangValue * lebarValue * (tinggiValue / user_inputValue) * quantityValue;

            if (isNaN(totalValue)) {
                totalValue = 0;
            }

            var resultString = totalValue.toString();
    
            if (resultString.includes('.')) {
                // Jika hasilnya berupa angka desimal
                var decimalPart = resultString.split('.')[1];
                if (decimalPart.length > 3) {
                    // Jika angka desimal memiliki lebih dari 3 angka dibelakang koma
                    resultString = resultString.split('.')[0] + '.' + decimalPart.substring(0, 3);
                }
            }

            var inputName = 'dimension[' + rowNumber + '][total]';

            $('input[id="' + inputName + '"]').val(resultString);

            calculateGrandTotal()
        }
    </script>

@endpush