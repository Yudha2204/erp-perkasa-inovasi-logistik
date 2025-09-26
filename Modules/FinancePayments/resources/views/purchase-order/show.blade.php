@extends('layouts.app')
@section('content')

<div class="main-content app-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">
        <form>
            <!-- PAGE-HEADER -->
            <div class="page-header mb-0">
                <h1>Account Payable</h1>
            </div>
            <h4 style="color: #015377">Show</h4>
            <!-- PAGE-HEADER END -->

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Vendor</label>
                                        <select class="form-control select2 form-select" disabled>
                                            <option>{{ $order->vendor->customer_name }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Customer</label>
                                        <select class="form-control select2 form-select" disabled>
                                            <option>{{ $order->customer->customer_name ?? 'No Customer' }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="no-transaction" class="form-label">Nomor Transaksi</label>
                                        <input type="text" id="no-transaction" class="form-control" readonly value="{{ $order->transaction }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" class="form-label">Date</label>
                                        <input type="date" class="form-control" readonly value="{{ $order->date_order }}" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" class="form-label">Currency</label>
                                        <select class="form-control select2 form-select" disabled>
                                            <option selected>{{ $order->currency->initial }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="coa_ap" class="form-label">Account Name</label>
                                        <select class="form-control select2 form-select" disabled>
                                            <option>{{ $order->account->account_name }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                             <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="des_head_order" class="form-label">Description</label>
                                        <input type="text" class="form-control" readonly placeholder="Desc" value="{{ $order->description }}" >
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
                                        </tr>
                                    </thead>
                                    <tbody id="form-container">
                                        @foreach($order->details as $data)
                                        <tr class="form-wrapper">
                                            <td></td>
                                            <td>
                                                <input type="text" class="form-control" readonly value="{{ $data->description }}"/>
                                                <label class="form-label">Remark</label>
                                                <input type="text" class="form-control" readonly value="{{ $data->remark }}"/>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" readonly value="{{ $data->quantity }}"/>
                                                <label class="form-label">Disc</label>
                                                <input type="text" class="form-control" readonly value="{{ $data->discount_nominal }}"/>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" readonly value="{{ $data->uom }}"/>
                                                <label class="form-label" style="visibility: hidden;">Disc</label>
                                                <select class="form-control select2 form-select" disabled>
                                                    <option selected>{{ $data->discount_type === "persen" ? "%" : "0" }}</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" readonly value="{{ number_format($data->price, 2, '.', ',') }}"/>
                                                <label for="" class="form-label">PPh</label>
                                                <select class="form-control select2 form-select" disabled>
                                                    <option>{{ $data->tax->tax_rate ?? 0 }}%</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" readonly value="{{ number_format($data->total,2,'.',',') }}"/>
                                                <label for="coa_expense_detail" class="form-label">Account Name</label>
                                                <select class="form-control select2 form-select" disabled>
                                                    <option>{{ $data->account->account_name }}</option>
                                                </select>
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
                                                    Diskon (-)
                                                    <input type="text" style="width: 50%" class="form-control" readonly value="{{ number_format($order->discount, 2, '.', ',') }}" />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                @php
                                                    $ppn_rate = $order->ppnTax->tax_rate ?? 0;
                                                    $ppn_amount = 0;
                                                    if ($ppn_rate > 0) {
                                                        $total_before_ppn = $order->total / (1 + ($ppn_rate / 100));
                                                        $ppn_amount = $order->total - $total_before_ppn;
                                                    }
                                                @endphp
                                                <div class="d-flex justify-content-between">
                                                    PPN ({{ $ppn_rate }}%)
                                                    <input type="text" style="width: 50%" class="form-control" readonly value="{{ number_format($ppn_amount, 2, '.', ',') }}" />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    Total Pajak
                                                    <input type="text" style="width: 50%" class="form-control" readonly value="{{ number_format($order->details->sum('tax'), 2, '.', ',')}}" />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    Total
                                                    <input type="text" style="width: 50%" class="form-control" readonly value="{{ number_format($order->total, 2, '.', ',') }}"/>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="row">
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
