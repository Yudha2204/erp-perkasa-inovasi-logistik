@extends('layouts.app')
@section('content')
@push('styles')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endpush

<div class="main-content app-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">
        <form action="{{ route('finance.piutang.invoice.store') }}" method="POST" enctype="multipart/form-data" name="dynamic-form">
            @csrf
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
                                                data-placeholder="Choose One" name="customer_id" id="customer_id" disabled>
                                                <option value="{{ $invoiceHead->contact->id }}">{{ $invoiceHead->contact->customer_name }}</option> 
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" class="form-label">Sales No</label>
                                        <select class="form-control select2 form-select"
                                            data-placeholder="Choose One" name="sales_no" id="sales_no" disabled>
                                            <option value="{{ $invoiceHead->sales_id }}">{{ $invoiceHead->sales->transaction }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" class="form-label">Term Of Payment</label>
                                        <select class="form-control select2 form-select"
                                            data-placeholder="Choose One" name="term_payment" id="term_payment" disabled>
                                            <option value="{{ $invoiceHead->term->id }}:{{ $invoiceHead->term->pay_days }}">{{ $invoiceHead->term->name }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        @php
                                        $job_order = 'Empty';
                                        $commodity = 'Empty';
                                        $consignee = 'Empty';
                                        $shipper = 'Empty';
                                        $transportation = 'Empty';
                                        $transportation_desc = 'Empty';
                                          if($invoiceHead->sales->marketing) {
                                            $job_order = $invoiceHead->sales->marketing->job_order_id;
                                            $commodity = $invoiceHead->sales->marketing->description;
                                            $consignee = $invoiceHead->sales->marketing->consignee;
                                            $shipper = $invoiceHead->sales->marketing->shipper;
                                            $transportation = $invoiceHead->sales->marketing->transportation;
                                            if($transportation === 1) {
                                                $transportation = 'Air Freight';
                                            } else if($transportation === 2) {
                                                $transportation = 'Sea Freight';
                                            } else {
                                                $transportation = 'Land Trucking';
                                            }
                                            $transportation_desc = $invoiceHead->sales->marketing->transportation_desc;
                                          }  
                                        @endphp
                                        <label for="job_order" class="form-label">Job Order</label>
                                        <input type="text" class="form-control" name="job_order" id="job_order" value="{{ $job_order }}" placeholder="Link" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="no_transactions" class="form-label">Nomor Transaksi <a data-bs-effect="effect-scale" data-bs-toggle="modal" style="display: none;" href="#modal-transaction-format"><i class="fa fa-cog"></i></a></label>
                                        <input type="text" name="no_transactions" id="no-transactions" class="form-control" readonly placeholder="Choose Transaction" value="{{ $invoiceHead->transaction }}" />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="date_invoice" class="form-label">Date</label>
                                        <input type="date" class="form-control" name="date_invoice" id="date_invoice" readonly value="{{ $invoiceHead->date_invoice }}">
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
                                        <input type="date" class="form-control" name="date_expired" id="date_expired" readonly value="{{ $invoiceHead->due_date }}">
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
                                        <input type="text" class="form-control" name="sell_des" id="sell_des" placeholder="Desc" readonly value="{{ $invoiceHead->description }}">
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
                                        @foreach($invoiceHead->details as $data)
                                        <tr class="form-wrapper">
                                            <td></td>
                                            <td>
                                                <input type="text" class="form-control description-input" name="des_detail" placeholder="Desc" readonly value="{{ $data->description }}" />
                                                <label class="form-label">Remark</label>
                                                <input type="text" class="form-control remark-input" name="remark_detail" placeholder="Remark" readonly value="{{ $data->remark }}"/>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control qty-input" name="qty_detail" readonly value="{{ $data->quantity }}"/>
                                                <label class="form-label">Disc</label>
                                                <input type="text" class="form-control discount-input" name="disc_detail" readonly value="{{ $data->discount_nominal }}"/>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control uom-input" name="uom_detail" readonly value="{{ $data->uom }}"/>
                                                <label class="form-label" style="visibility: hidden;">Disc</label>
                                                <select class="form-control select2 form-select discount-type" data-placeholder="Choose One" name="disc_type_detail" disabled>
                                                    @php
                                                        $discount_type_detail = $data->discount_type === "persen" ? "%" : "0";   
                                                        $discount_total += $data->discount;
                                                    @endphp
                                                    <option value="{{ $data->discount_type }}" selected>{{ $discount_type_detail }}</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control price-input" name="price_detail" readonly value="{{ number_format($data->price, 2, '.', ',') }}" />
                                                <label for="" class="form-label">Pajak</label>
                                                <select class="form-control select2 form-select" data-placeholder="Tax" name="pajak_detail" id="pajak_detail" disabled>
                                                    @php
                                                      $tax_text = null;
                                                      $tax_value = null;
                                                      if($data->tax_detail) {
                                                        $tax_id = $data->tax_detail->id;
                                                        $tax_rate = $data->tax_detail->tax_rate;
                                                        $tax_value = "$tax_id:$tax_rate";
                                                        $tax_text = "$tax_rate%";
                                                      }

                                                      $tax_total += $data->tax;
                                                    @endphp
                                                    @if(isset($tax_text))
                                                    <option value="{{ $tax_value }}">{{ $tax_text }}</option>
                                                    @else
                                                    <option label="Tax"></option>
                                                    @endif
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control total-input" name="total_detail" readonly value="{{ number_format($data->total,2,'.',',') }}"/>
                                                @if(isset($data->dp_nominal))
                                                <label class="custom-control custom-radio" style="margin-bottom: 0.375rem;">
                                                    <input type="radio" class="custom-control-input" value="1" checked>
                                                    <span class="custom-control-label form-label">Bayar DP</span>
                                                </label>
                                                <div class="d-flex gap-2 flex-column">
                                                    <input type="text" class="form-control" name="dp_detail" readonly value="{{ $data->dp_nominal }}" />
                                                    <select class="form-control select2 form-select" data-placeholder="Choose One" name="dp_type_detail" disabled >
                                                        <option value="persen" {{ $data->dp_type === "persen" ? "selected" : "" }}>%</option>
                                                        <option value="nominal" {{ $data->dp_type === "nominal" ? "selected" : "" }}>0</option>
                                                    </select>
                                                </div>
                                                @php
                                                    $dp += $data->dp;
                                                @endphp
                                                @endif
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
                                        <tr>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    Biaya Lain
                                                    <input type="text" style="width: 50%" class="form-control" id="additional_cost" name="additional_cost" readonly value="{{ number_format($invoiceHead->additional_cost, 2, '.',',') }}" />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    Diskon (-)
                                                    <div style="width: 10%">
                                                        <select class="form-control select2 form-select" id="discount_type" name="discount_type" disabled>
                                                            @php
                                                             $discount_type_head = $invoiceHead->discount_type === "persen" ? "%" : "0";   
                                                            @endphp
                                                            <option value="{{ $invoiceHead->discount_type }}">{{ $discount_type_head }}</option>
                                                        </select>
                                                    </div>
                                                    <input type="text" style="width: 10%" class="form-control" id="discount" name="discount" value="{{ $invoiceHead->discount_nominal }}" readonly />
                                                    <input type="text" style="width: 50%" class="form-control" id="discount_display" name="discount_display" value="{{ number_format(($invoiceHead->discount + $discount_total),2,'.',',') }}" readonly />
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
                                                    <input type="text" style="width: 50%" class="form-control" id="total_display" name="total_display" readonly value="{{ number_format($invoiceHead->total, 2, '.',',') }}" />
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
                                        <tr style="{{ $invoiceHead->dp_receive > 0 ? "" : "display: none" }}" id="dp">
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    DP From Receive Payments
                                                    <input type="text" style="width: 50%" class="form-control" id="display_dp" name="display_dp" readonly placeholder="0" value="{{ number_format($invoiceHead->dp_receive, 2, '.',',') }}" />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr style="{{ $dp > 0 ? "block" : "display: none" }}" id="sisa">
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    Sisa
                                                    <input type="text" style="width: 50%" class="form-control" id="display_sisa" name="display_sisa" readonly placeholder="0"  value="{{ number_format($invoiceHead->total - $dp - $invoiceHead->dp_receive, 2, '.',',')  }}"/>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="btn-list text-end">
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

@endsection
@push('scripts')
    <!-- SELECT2 JS -->
    <script src="{{ url('assets/plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ url('assets/js/select2.js') }}"></script>

    <!-- MULTI SELECT JS-->
    <script src="{{ url('assets/plugins/multipleselect/multiple-select.js') }}"></script>
    <script src="{{ url('assets/plugins/multipleselect/multi-select.js') }}"></script>
@endpush