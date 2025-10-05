@extends('layouts.app')
@section('content')
@push('styles')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endpush

<div class="main-content app-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">
        <form>
            @csrf
            <!-- PAGE-HEADER -->
            <div class="page-header mb-0">
                <h1>Sales Order</h1>
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
                                                data-placeholder="Choose One" name="customer_id" id="customer_id" disabled>
                                                <option value="{{ $dataSalesOrder->contact->id }}" selected>{{ $dataSalesOrder->contact->customer_name }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" class="form-label">Nomor Transaksi <a data-bs-effect="effect-scale" data-bs-toggle="modal" style="display: none;" href="#modal-transaction-format"><i class="fa fa-cog"></i></a></label>
                                        <input type="text" name="no_transaction" id="no-transaction" class="form-control" readonly placeholder="Choose Transaction" value="{{ $dataSalesOrder->transaction }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" class="form-label">Date</label>
                                        <input type="date" class="form-control" name="date_sales" id="date_sales" value="{{ $dataSalesOrder->date }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" class="form-label">Currency</label>
                                        <select class="form-control select2 form-select"
                                            data-placeholder="Choose One" name="currency_id" id="currency_id" disabled>
                                            <option value="{{ $dataSalesOrder->currency->id }}" selected>{{ $dataSalesOrder->currency->initial }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="des_head_sales" class="form-label">Description</label>
                                        <input type="text" class="form-control" name="des_head_sales" id="des_head_sales" placeholder="Sales Order - No Transaksi" readonly value="{{ $dataSalesOrder->description }}" >
                                    </div>
                                </div>
                            </div>
                            @if(isset($dataSalesOrder->marketing))
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="custom-control custom-radio">
                                        <input type="radio" id="choose_job_order" name="choose_job_order" class="custom-control-input" checked>
                                        <span class="custom-control-label"><b>Choose Job Order</b></span>
                                    </label>
                                </div>
                            </div>
                            <div id="job_order_display">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="" class="form-label">No Referensi</label>
                                            <select class="form-control select2 form-select"
                                                data-placeholder="Choose One" name="no_referensi" id="no_referensi" disabled>
                                                <option value="{{ $dataSalesOrder->marketing->id }}:{{ $dataSalesOrder->marketing->source }}" selected>{{ $dataSalesOrder->marketing->quotation->quotation_no }} - {{ $dataSalesOrder->marketing->source }}</option>  
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="job_order_id" class="form-label">Job Order</label>
                                            <input type="text" class="form-control" name="job_order_id" id="job_order_id" readonly placeholder="Link" value="{{ $dataSalesOrder->marketing->job_order_id }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="consignee" class="form-label">Consignee</label>
                                            <input type="text" class="form-control" name="consignee" id="consignee" readonly placeholder="Link" value="{{ $dataSalesOrder->marketing->consignee }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="transportation" class="form-label">Transportation</label>
                                            @php
                                              $transportation_id = $dataSalesOrder->marketing->transportation;
                                              $transportation = 'Land Trucking';
                                              if($transportation_id === 1) {
                                                $transportation = 'Air Freight';
                                              } else if($transportation_id === 2) {
                                                $transportation = 'Sea Freight';
                                              }
                                            @endphp
                                            <input type="text" class="form-control" name="transportation" id="transportation" readonly placeholder="Link" value="{{ $transportation }}" >
                                        </div>
                                        @if(isset($dataSalesOrder->marketing->transportation_desc))
                                        <label class="custom-control custom-radio" id="transportation_desc">
                                            <input type="radio" class="custom-control-input"
                                                name="transportation_desc" checked>
                                            <span class="custom-control-label">{{ $dataSalesOrder->marketing->transportation_desc }}</span>
                                        </label>
                                        @endif
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="shipper" class="form-label">Shipper</label>
                                            <input type="text" class="form-control" name="shipper" id="shipper" readonly placeholder="Link" value="{{ $dataSalesOrder->marketing->shipper }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="commodity" class="form-label">Commodity</label>
                                            <input type="text" class="form-control" name="commodity" id="commodity" readonly placeholder="Link" value="{{ $dataSalesOrder->marketing->description }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
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
                                        @php
                                          $discount_total = 0;  
                                        @endphp
                                        @foreach ($dataSalesOrder->details as $data)
                                            <tr class="form-wrapper">
                                                <td></td>
                                                <td>
                                                    <input type="text" class="form-control description-input" name="des_detail" placeholder="Desc" value="{{ $data->description }}" readonly />
                                                    <label class="form-label">Remark</label>
                                                    <input type="text" class="form-control remark-input" name="remark_detail" placeholder="Remark" value="{{ $data->remark }}" readonly />
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="qty_detail" value="{{ $data->quantity }}" readonly />
                                                    <label class="form-label">Disc</label>
                                                    <input type="text" class="form-control" name="disc_detail" value="{{ $data->discount_nominal }}" readonly />
                                                    </td>
                                                <td>
                                                    <input type="text" class="form-control" name="uom_detail" value="{{ $data->uom }}" readonly />
                                                    <label class="form-label" style="visibility: hidden;">Disc</label>
                                                    <select class="form-control select2 form-select" data-placeholder="Choose One" name="disc_type_detail" disabled >
                                                        @php
                                                            $discount_type_detail = $data->discount_type === "persen" ? "%" : "0";   
                                                            $discount_total += $data->discount;
                                                        @endphp
                                                        <option value="{{ $data->discount_type }}" selected>{{ $discount_type_detail }}</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control price-input" name="price_detail" readonly value="{{ number_format($data->price, 2, '.', ',') }}" />
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control total-input" name="total_detail" readonly value="{{ number_format($data->total, 2, '.', ',') }}" />
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-between">
                                                        
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="row justify-content-end">
                                <div class="col-lg-6">
                                    <table class="table mt-5">
                                        {{-- <tr>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    Biaya Lain
                                                    <input type="text" style="width: 50%" class="form-control" name="additional_cost" id="additional_cost" readonly value="{{ number_format($dataSalesOrder->additional_cost, 2, '.', ',') }}" />
                                                </div>
                                            </td>
                                        </tr> --}}
                                        <tr>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    Diskon (-)
                                                    <div style="width: 10%">
                                                        <select class="form-control select2 form-select" name="discount_type" id="discount_type" disabled>
                                                            @php
                                                             $discount_type_head = $dataSalesOrder->discount_type === "persen" ? "%" : "0";   
                                                            @endphp
                                                            <option value="{{ $dataSalesOrder->discount_type }}">{{ $discount_type_head }}</option>
                                                        </select>
                                                    </div>
                                                    <input type="text" style="width: 10%" class="form-control" name="discount" id="discount" value="{{ $dataSalesOrder->discount_nominal }}" readonly/>
                                                    <input type="text" style="width: 50%" class="form-control" id="discount_display" name="discount_display" readonly value="{{ number_format($dataSalesOrder->discount + $discount_total, 2, '.', ',') }}" />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    Total
                                                    <input type="text" style="width: 50%" class="form-control" id="total_display" name="total_display" readonly value="{{ number_format($dataSalesOrder->total, 2, '.', ',') }}" />
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="btn-list text-end" >
                                    </div>
                                </div>
                                <br><br><br><br>
                                <div class="col-md-12">
                                    <div class="btn-list text-end">
                                        <a href="javascript: history.go(-1)" class="btn btn-default">Back</a>
                                        <button id="submit-all-form" type="submit" class="btn btn-primary" style="display: none;">Save</button>
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

    <!-- MULTI SELECT JS-->
    <script src="{{ url('assets/plugins/multipleselect/multiple-select.js') }}"></script>
    <script src="{{ url('assets/plugins/multipleselect/multi-select.js') }}"></script>
@endpush