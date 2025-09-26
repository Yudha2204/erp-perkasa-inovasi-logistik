@extends('layouts.app')
@section('content')
@push('styles')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endpush

<div class="main-content app-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">
        <form action="" method="POST" enctype="multipart/form-data" name="dynamic-form">
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
                                        <label for="coa_ar" class="form-label">Account Name</label>
                                        <input type="text" class="form-control" value="{{ $invoiceHead->account->account_name }}" disabled>
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
                                        <input type="text" class="form-control" name="job_order" id="job_order" value="{{ $job_order }}" placeholder="Link" readonly disabled>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="no_transactions" class="form-label">Nomor Transaksi</label>
                                        <input type="text" name="no_transactions" id="no-transactions" class="form-control" readonly placeholder="Choose Transaction" value="{{ $invoiceHead->transaction }}" disabled/>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="date_invoice" class="form-label">Date</label>
                                        <input type="date" class="form-control" name="date_invoice" id="date_invoice" readonly value="{{ $invoiceHead->date_invoice }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="commodity" class="form-label">Commodity</label>
                                        <input type="text" class="form-control" name="commodity" id="commodity" placeholder="Link" readonly value="{{ $commodity }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="consignee" class="form-label">Consignee</label>
                                        <input type="text" class="form-control" name="consignee" id="consignee" placeholder="Link" readonly value="{{ $consignee }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="date_expired" class="form-label">Tanggal Jatuh Tempo</label>
                                        <input type="date" class="form-control" name="date_expired" id="date_expired" readonly value="{{ $invoiceHead->due_date }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="shipper" class="form-label">Shipper</label>
                                        <input type="text" class="form-control" name="shipper" id="shipper" placeholder="Link" readonly value="{{ $shipper }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="transportation" class="form-label">Transportation</label>
                                        <input type="text" class="form-control" name="transportation" id="transportation" placeholder="Link" readonly value="{{ $transportation }}" disabled>
                                    </div>
                                    <label class="custom-control custom-radio" id="transportation_desc" style="{{ $transportation_desc === "Empty" ? "display: none" : "" }}">
                                        <input type="radio" class="custom-control-input" name="transportation_desc" checked disabled>
                                        <span class="custom-control-label">{{ $transportation_desc }}</span>
                                    </label>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="sell_des" class="form-label">Description</label>
                                        <input type="text" class="form-control" name="sell_des" id="sell_des" placeholder="Desc" readonly value="{{ $invoiceHead->description }}" disabled>
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
                                                <input type="text" class="form-control" value="{{ $data->description }}" disabled/>
                                                <label class="form-label">Remark</label>
                                                <input type="text" class="form-control" value="{{ $data->remark }}" disabled/>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" value="{{ $data->quantity }}" disabled/>
                                                <label class="form-label">Disc</label>
                                                <input type="text" class="form-control" value="{{ $data->discount_nominal }}" disabled/>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" value="{{ $data->uom }}" disabled/>
                                                <label class="form-label" style="visibility: hidden;">Disc</label>
                                                <select class="form-control select2 form-select" disabled>
                                                    <option value="persen" {{ $data->discount_type === "persen" ? "selected" : "" }}>%</option>
                                                    <option value="nominal" {{ $data->discount_type === "nominal" ? "selected" : "" }} >0</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" value="{{ number_format($data->price, 2, '.', ',') }}" disabled/>
                                                <label for="" class="form-label">Pajak</label>
                                                <select class="form-control select2 form-select" disabled>
                                                    @if(isset($data->tax_detail))
                                                    <option value="{{ $data->tax_detail->id }}">{{ $data->tax_detail->tax_rate }}%</option>
                                                    @else
                                                    <option label="Tax"></option>
                                                    @endif
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" readonly value="{{ number_format($data->total,2,'.',',') }}" disabled/>
                                                <label for="coa_sales" class="form-label">Account Name </label>
                                                <input type="text" class="form-control" value="{{ $data->account->account_name }}" disabled>
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
                                                    <input type="text" style="width: 50%" class="form-control" readonly value="{{ number_format($invoiceHead->additional_cost, 2, '.',',') }}" disabled/>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    Diskon (-)
                                                    <div style="width: 10%">
                                                        <select class="form-control select2 form-select" disabled>
                                                            <option value="persen" {{ $invoiceHead->discount_type === "persen" ? "selected" : "" }}>%</option>
                                                            <option value="nominal" {{ $invoiceHead->discount_type === "nominal" ? "selected" : "" }} >0</option>
                                                        </select>
                                                    </div>
                                                    <input type="text" style="width: 10%" class="form-control" value="{{ $invoiceHead->discount_nominal }}" disabled/>
                                                    <input type="text" style="width: 50%" class="form-control" readonly value="{{ number_format(($invoiceHead->discount),2,'.',',') }}" disabled/>
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
                                                        <input type="text" class="form-control" value="{{ $invoiceHead->ppnTax->tax_rate ?? 0 }}%" readonly disabled>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    Total Pajak
                                                    <input type="text" style="width: 50%" class="form-control" readonly value="{{ number_format($tax_total, 2, '.',',')}}" disabled/>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    Total
                                                    <input type="text" style="width: 50%" class="form-control" readonly value="{{ number_format($invoiceHead->total, 2, '.',',') }}" disabled/>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr style="{{ $dp > 0 ? "" : "display: none" }}" id="dp">
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    DP
                                                    <input type="text" style="width: 50%" class="form-control" readonly placeholder="0" value="{{ number_format($dp, 2, '.',',') }}" disabled/>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr style="{{ $invoiceHead->dp_receive > 0 ? "" : "display: none" }}" id="dp">
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    DP From Receive Payments
                                                    <input type="text" style="width: 50%" class="form-control" readonly placeholder="0" value="{{ number_format($invoiceHead->dp_receive, 2, '.',',') }}" disabled/>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr style="{{ $dp > 0 ? "block" : "display: none" }}" id="sisa">
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    Sisa
                                                    <input type="text" style="width: 50%" class="form-control" readonly placeholder="0"  value="{{ number_format($invoiceHead->total - $dp - $invoiceHead->dp_receive, 2, '.',',')  }}" disabled/>
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
