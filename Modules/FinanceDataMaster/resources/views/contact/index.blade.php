@extends('layouts.app')
@section('content')

    <div class="main-content app-content mt-0">
        <div class="side-app">

            <!-- CONTAINER -->
            <div class="main-container container-fluid">

                <!-- PAGE-HEADER -->
                <div class="page-header">
                    <h1>Contact Data</h1>
                </div>
                <!-- PAGE-HEADER END -->

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="d-flex d-inline">
                            <form action="{{ route('finance.master-data.contact.index') }}">
                                <input type="text" name="search" id="search" value="{{ Request::get('search') }}" class="form-control" placeholder="Searching.....">
                            </form>
                            &nbsp;&nbsp;
                            <a class="btn btn-primary" id="modal-create-btn" href="javascript:void(0)"><i
                                    class="fe fe-plus me-2"></i>Add New</a>&nbsp;&nbsp;
                            <button type="button" class="btn btn-light"><img
                                    src="{{ url('assets/images/icon/filter.png') }}" alt=""></button>
                        </div>
                    </div>
                </div>

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
                                    <table class="table text-nowrap text-md-nowrap mb-0">
                                        <thead>
                                            <tr style="text-align: ">
                                                <th>No</th>
                                                <th>Name</th>
                                                <th>Customer ID</th>
                                                <th>Email</th>
                                                <th>Type</th>
                                                <th>Telepon</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($contact as $c)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $c->customer_name }}</td>
                                                    <td>{{ $c->customer_id }}</td>
                                                    <td>{{ $c->email }}</td>
                                                    <td>
                                                        @foreach (json_decode($c->type) as $t)
                                                            @if ($t == 1)
                                                                Customer
                                                            @elseif ($t == 2)
                                                                Vendor
                                                            @elseif ($t == 3)
                                                                Karyawan
                                                            @elseif ($t == 4)
                                                                Supplier
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>{{ $c->phone_number }}</td>
                                                    <td>
                                                        <div class="g-2">
                                                            <a href="javascript:void(0)" id="btn-show" data-id="{{ $c->id }}" class="btn text-primary btn-sm"
                                                                data-bs-toggle="tooltip"
                                                                data-bs-original-title="Detail"><span
                                                                    class="fe fe-eye fs-14"></span></a>
                                                            <a href="javascript:void(0)" id="btn-edit"
                                                                data-id="{{ $c->id }}"
                                                                class="btn text-primary btn-sm" data-bs-toggle="tooltip"
                                                                data-bs-original-title="Edit"><span
                                                                    class="fe fe-edit fs-14"></span></a>
                                                            <a href="#" class="btn text-danger btn-sm"
                                                                data-bs-toggle="tooltip" data-bs-original-title="Delete"
                                                                onclick="if (confirm('Are you sure want to delete this item?')) {
                                                                            event.preventDefault();
                                                                            document.getElementById('delete-{{ $c->id }}').submit();
                                                                        }else{
                                                                            event.preventDefault();
                                                                        }">
                                                                <span class="fe fe-trash-2 fs-14"></span>
                                                            </a>
                                                            <form id="delete-{{ $c->id }}"
                                                                action="{{ route('finance.master-data.contact.destroy', $c->id) }}"
                                                                style="display: none;" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <td colspan="7" style="text-align: center">
                                                    <span class="text-danger">
                                                        <strong>Data is Empty</strong>
                                                    </span>
                                                </td>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- CONTAINER CLOSED -->
        </div>
    </div>

    {{-- modal create --}}
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
                                <form action="{{ route('finance.master-data.contact.store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="panel-body tabs-menu-body">
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="tab1">
                                                <h4><u>Customer</u></h4>
                                                <div class="form-group">
                                                    <label>Customer ID</label>
                                                    <input type="text" name="customer_id" id="customer_id"
                                                        value="{{ old('customer_id') }}" class="form-control" disabled>
                                                </div>
                                                <div class="form-group">
                                                    <label>Customer Name</label>
                                                    <input type="text" name="customer_name" id="customer_name"
                                                        value="{{ old('customer_name') }}"class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Title</label>
                                                    <input type="text" name="title" value="{{ old('title') }}"
                                                        class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Mobile Phone Number</label>
                                                    <input type="text" name="phone_number"
                                                        value="{{ old('phone_humber') }}" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input type="text" name="email" value="{{ old('email') }}"
                                                        class="form-control">
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-6">
                                                        <label>NPWP/KTP</label>
                                                        <input type="text" name="npwp_ktp"
                                                            value="{{ old('npwp_ktp') }}" class="form-control">
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
                                                                <input type="checkbox" class="custom-control-input"
                                                                    id="contact_type1" name="contact_type[]"
                                                                    value="1"
                                                                    @if (is_array(old('contact_type')) && in_array(1, old('contact_type'))) checked @endif>
                                                                <span class="custom-control-label">Customer</span>
                                                            </label>
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <label class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    id="contact_type2" name="contact_type[]"
                                                                    value="2"
                                                                    @if (is_array(old('contact_type')) && in_array(2, old('contact_type'))) checked @endif>
                                                                <span class="custom-control-label">Vendor</span>
                                                            </label>
                                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                                            <label class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    id="contact_type3" name="contact_type[]"
                                                                    value="3"
                                                                    @if (is_array(old('contact_type')) && in_array(3, old('contact_type'))) checked @endif>
                                                                <span class="custom-control-label">Karyawan</span>
                                                            </label>
                                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                                            <label class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    id="contact_type4" name="contact_type[]"
                                                                    value="4"
                                                                    @if (is_array(old('contact_type')) && in_array(4, old('contact_type'))) checked @endif>
                                                                <span class="custom-control-label">Supplier</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row input_fields_wrap_new mt-2">
                                                    <div class="col-10">
                                                        <div class="form-group"
                                                            style="margin-bottom: 0px; margin-top: 0px">
                                                            <label>Term Of Payment</label>
                                                            <select class="form-control select2 form-select"
                                                                data-placeholder="Choose one" name="term_payment_id[]">
                                                                @foreach ($terms as $term)
                                                                    <option
                                                                        {{ old('term_payment_id[]') == $term->id ? 'selected' : '' }}
                                                                        value="{{ $term->id }}">{{ $term->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-3">
                                                        <button type="button" id="tambahKolomNew"
                                                            class="btn btn-primary btn-sm add_field_button_new"><i
                                                                class="fe fe-plus me-2"></i>Add New Term</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab2">
                                                <h4><u>Company</u></h4>
                                                <div class="form-group">
                                                    <label>Company Name</label>
                                                    <input type="text" name="company_name"
                                                        value="{{ old('company_name') }}" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Type Of Company</label>
                                                    <div class="d-flex d-inline">
                                                        <label class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                name="type_of_company" value="1"
                                                                @if (is_array(old('type_of_company')) && in_array(1, old('type_of_company'))) checked @endif>
                                                            <span class="custom-control-label">PT / Ltd</span>
                                                        </label>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                                        <label class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                name="type_of_company" value="2"
                                                                @if (is_array(old('type_of_company')) && in_array(2, old('type_of_company'))) checked @endif>
                                                            <span class="custom-control-label">CV</span>
                                                        </label>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                                        <label class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                name="type_of_company" value="3"
                                                                @if (is_array(old('type_of_company')) && in_array(3, old('type_of_company'))) checked @endif>
                                                            <span class="custom-control-label">UD</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Company Tax Status</label>
                                                    <div class="d-flex d-inline">
                                                        <label class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                name="company_tax_status" value="1"
                                                                @if (is_array(old('company_tax_status')) && in_array(1, old('company_tax_status'))) checked @endif>
                                                            <span class="custom-control-label">Taxable</span>
                                                        </label>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                                        <label class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                name="company_tax_status" value="2"
                                                                @if (is_array(old('company_tax_status')) && in_array(2, old('company_tax_status'))) checked @endif>
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
                                                    <input type="text" name="address" value="{{ old('address') }}"
                                                        class="form-control">
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label>City</label>
                                                        <input type="text" name="city" value="{{ old('city') }}"
                                                            class="form-control">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label>Postal Code</label>
                                                        <input type="text" name="postal_code"
                                                            value="{{ old('postal_code') }}" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Country</label>
                                                    <input type="text" name="country" value="{{ old('country') }}"
                                                        class="form-control">
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab4">
                                                <h4><u>Others</u></h4>
                                                <div class="form-group">
                                                    <label>PIC for Urgent Status</label>
                                                    <input type="text" name="pic_for_urgent_status"
                                                        value="{{ old('pic_for_urgent_status') }}" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label>Mobile Number</label>
                                                    <input type="text" name="mobile_number"
                                                        value="{{ old('mobile_number') }}" class="form-control">
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

    {{-- modal edit --}}
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

    {{-- modal show --}}
    <div class="modal fade" id="modal-show" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">+ Detail Contact</h5>
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
                                            <li><a href="#tab1show" class="active" data-bs-toggle="tab">Customer</a></li>
                                            <li><a href="#tab2show" data-bs-toggle="tab">Company</a></li>
                                            <li><a href="#tab3show" data-bs-toggle="tab">Address</a></li>
                                            <li><a href="#tab4show" data-bs-toggle="tab">Others</a></li>
                                        </ul>
                                    </div>
                                </div>
                                    <div class="panel-body tabs-menu-body">
                                        <div class="tab-content">
                                            <div class="tab-pane active" id="tab1show">
                                                <h4><u>Customer</u></h4>
                                                <div class="form-group">
                                                    <label>Customer ID</label>
                                                    <input type="text" name="customer_id" id="customer_id_show"
                                                        class="form-control" disabled>
                                                </div>
                                                <div class="form-group">
                                                    <label>Customer Name</label>
                                                    <input type="text" name="customer_name" id="customer_name_show"
                                                        class="form-control" disabled>
                                                </div>
                                                <div class="form-group">
                                                    <label>Title</label>
                                                    <input type="text" name="title" id="title_show"
                                                        class="form-control" disabled>
                                                </div>
                                                <div class="form-group">
                                                    <label>Mobile Phone Number</label>
                                                    <input type="text" name="phone_number" id="phone_number_show"
                                                        class="form-control" disabled>
                                                </div>
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input type="text" name="email" id="email_show"
                                                        class="form-control" disabled>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-6">
                                                        <label>NPWP/KTP</label>
                                                        <input type="text" name="npwp_ktp" id="npwp_ktp_show"
                                                            class="form-control" disabled>
                                                    </div>
                                                    <div class="form-group col-6">
                                                        <label>Upload Document</label>
                                                        <input type="file" name="document" class="form-control" disabled>
                                                        <div id="file_show"></div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label>Category</label>
                                                        <div class="custom-controls-stacked d-flex d-inline">
                                                            <label class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    id="contact_type1" name="contact_type[]"
                                                                    value="1" disabled>
                                                                <span class="custom-control-label">Customer</span>
                                                            </label>
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <label class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    id="contact_type2_show" name="contact_type[]"
                                                                    value="2" disabled>
                                                                <span class="custom-control-label">Vendor</span>
                                                            </label>
                                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                                            <label class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    id="contact_type3" name="contact_type[]"
                                                                    value="3" disabled>
                                                                <span class="custom-control-label">Karyawan</span>
                                                            </label>
                                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                                            <label class="custom-control custom-checkbox">
                                                                <input type="checkbox" class="custom-control-input"
                                                                    id="contact_type4" name="contact_type[]"
                                                                    value="4" disabled>
                                                                <span class="custom-control-label">Supplier</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row input_fields_wrap_new show mt-2">
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab2show">
                                                <h4><u>Company</u></h4>
                                                <div class="form-group">
                                                    <label>Company Name</label>
                                                    <input type="text" name="company_name" id="company_name_show"
                                                        class="form-control" disabled>
                                                </div>
                                                <div class="form-group">
                                                    <label>Type Of Company</label>
                                                    <div class="d-flex d-inline">
                                                        <label class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                name="type_of_company" value="1" disabled>
                                                            <span class="custom-control-label">PT / Ltd</span>
                                                        </label>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                                        <label class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                name="type_of_company" value="2" disabled>
                                                            <span class="custom-control-label">CV</span>
                                                        </label>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                                        <label class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                name="type_of_company" value="3" disabled>
                                                            <span class="custom-control-label">UD</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Company Tax Status</label>
                                                    <div class="d-flex d-inline">
                                                        <label class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                name="company_tax_status" value="1" disabled>
                                                            <span class="custom-control-label">Taxable</span>
                                                        </label>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                                        <label class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                name="company_tax_status" value="2" disabled>
                                                            <span class="custom-control-label">Non Taxable</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div id="input_vendor_show"></div>
                                            </div>
                                            <div class="tab-pane" id="tab3show">
                                                <h4><u>Address</u></h4>
                                                <div class="form-group">
                                                    <label>Address</label>
                                                    <input type="text" name="address" id="address_show"
                                                        class="form-control" disabled>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label>City</label>
                                                        <input type="text" name="city" id="city_show"
                                                            class="form-control" disabled>
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label>Postal Code</label>
                                                        <input type="text" name="postal_code" id="postal_code_show"
                                                            class="form-control" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Country</label>
                                                    <input type="text" name="country" id="country_show"
                                                        class="form-control" disabled>
                                                </div>
                                            </div>
                                            <div class="tab-pane" id="tab4show">
                                                <h4><u>Others</u></h4>
                                                <div class="form-group">
                                                    <label>PIC for Urgent Status</label>
                                                    <input type="text" name="pic_for_urgent_status"
                                                        id="pic_for_urgent_status_show" class="form-control" disabled>
                                                </div>
                                                <div class="form-group">
                                                    <label>Mobile Number</label>
                                                    <input type="text" name="mobile_number" id="mobile_number_show"
                                                        class="form-control" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-3" style="text-align: right">
                                            <a class="btn btn-white color-grey" data-bs-dismiss="modal">Close</a>
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
    <script>
        $(document).ready(function() {
            $('#modal-create-btn').click(function() {
                // reinitialize input fields
                $('input:checkbox[name^="contact_type"]').each(function() {
                    $(this).prop('checked', false);
                });

                $('input:radio[name="type_of_company"]').each(function() {
                    $(this).prop('checked', false);
                });

                $('input:radio[name="company_tax_status"]').each(function() {
                    $(this).prop('checked', false);
                });

                $('#modal-create').modal('show');
            });

            // show beneficiary - siwft code if select checkbox vendor value modal create
            $("input:checkbox[name^='contact_type']").on('change', function() {
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
            var max_fields_new = 50; //maximum input boxes allowed
            var wrapper_new = $(".input_fields_wrap_new"); //Fields wrapper
            var wrapper_new_edit = $(".input_fields_wrap_new.edit"); //Fields wrapper
            var wrapper_new_show = $(".input_fields_wrap_new.show"); //Fields wrapper
            var add_button_new = $(".add_field_button_new"); //Add button ID

            var x = 1; //initlal text box count
            $(add_button_new).click(function(e) { //on add input button click
                e.preventDefault();
                if (x < max_fields_new) { //max input box allowed
                    x++; //text box increment
                    $(wrapper_new).append(`
                    <div class="row input_fields_wrap_new">
                        <div class="col-10">
                            <div class="form-group" style="margin-bottom: 0px; margin-top: 0px">
                                <label>Term Of Payment</label>
                                <select class="form-control select2 form-select"
                                    data-placeholder="Choose one" name="term_payment_id[]">
                                    @foreach ($terms as $term)
                                        <option {{ old('term_payment_id[]') == $term->id ? 'selected' : '' }} value="{{ $term->id }}">{{ $term->name }}</option>
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

            $(wrapper_new).on("click", ".remove_field_new", function(e) { //user click on remove text
                e.preventDefault();
                $(this).parent().parent().remove();
                x--;
            })


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


            //show data
            $('body').on('click', '#btn-show', function () {
            // reinitialize input fields
            $('input:checkbox[name^="contact_type"]').each(function() {
                $(this).prop('checked', false);
            });

            $('input:radio[name="type_of_company"]').each(function() {
                $(this).prop('checked', false);
            });

            $('input:radio[name="company_tax_status"]').each(function() {
                $(this).prop('checked', false);
            });

            let id = $(this).data('id');
            var url = "{{ route('finance.master-data.contact.show', ":id") }}";
            url = url.replace(':id', id);

                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: 'GET',
                    dataType: 'json',
                    url: url,
                    success:function(response){
                            // //fill data to form
                            $('#customer_id_show').val(response.data.customer_id);
                            $('#customer_name_show').val(response.data.customer_name);
                            $('#title_show').val(response.data.title);
                            $('#phone_number_show').val(response.data.phone_number);
                            $('#email_show').val(response.data.email);
                            $('#npwp_ktp_show').val(response.data.npwp_ktp);
                            $('#company_name_show').val(response.data.company_name);
                        
                            $('#address_show').val(response.data.address);
                            $('#city_show').val(response.data.city);
                            $('#postal_code_show').val(response.data.postal_code);
                            $('#country_show').val(response.data.country);
                            $('#pic_for_urgent_status_show').val(response.data.pic_for_urgent_status);
                            $('#mobile_number_show').val(response.data.mobile_number);


                            $('input:checkbox[name^="contact_type"]').each(function() {
                                let type = JSON.parse(response.data.type); // response : ['1', '3']
                                if (type.includes($(this).val())) {
                                    $(this).prop('checked', true);
                                }

                                // show beneficiary - siwft code if select checkbox vendor value modal show
                                if ($('#contact_type2_show').prop('checked')) {
                                    $("#input_vendor_show").html("");
                                    var radioBtnShow = $(`<div class="form-group">
                                                        <label>Beneficiary Bank/Branch</label>
                                                        <input type="text" name="bank_branch" class="form-control" id="bank_branch_show" disabled>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Beneficiary Acc Name</label>
                                                        <input type="text" name="acc_name" class="form-control" id="acc_name_show" disabled>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Beneficiary Acc No</label>
                                                        <input type="text" name="acc_no" class="form-control" id="acc_no_show" disabled>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Swift Code</label>
                                                        <input type="text" name="swift_code" class="form-control" id="swift_code_show" disabled>
                                                    </div>`);
                                    radioBtnShow.appendTo('#input_vendor_show');

                                    $('#bank_branch_show').val(response.data.bank_branch);
                                    $('#acc_name_show').val(response.data.acc_name);
                                    $('#acc_no_show').val(response.data.acc_no);
                                    $('#swift_code_show').val(response.data.swift_code);
                                } else {
                                    $("#input_vendor_show").html("");
                                }
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

                                results +=`
                                    <div class="row input_fields_wrap_new">
                                        <div class="col-10">
                                            <div class="form-group mt-2">
                                                <label>Term Of Payment</label>
                                                <select class="form-control select2 form-select"
                                                    data-placeholder="Choose one" name="term_payment_id[]" disabled>
                                                    ${option}
                                                </select>
                                            </div>
                                        </div>
                                    </div>`; //add input box
                            });

                            $(wrapper_new_show).html(results);

                            //show document if exist
                            if (response.data.document) {
                                $("#file_show").html("");
                                var fileShow = $(`<a href="/storage/${response.data.document}" target="_blank" class="btn btn-info shadow-sm btn-sm">View File</a>`);
                                fileShow.appendTo('#file_show');
                            } else {
                                $("#file_show").html("");
                                var fileShow = $(`<i>there is no document</i>`);
                                fileShow.appendTo('#file_show');
                            }

                            $('#modal-show').modal('show');
                        }
                });
            });


        });
    </script>
@endpush
