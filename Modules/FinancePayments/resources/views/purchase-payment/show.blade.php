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
                                                data-placeholder="Choose One" name="vendor_id" id="vendor_id" disabled>
                                                <option value="{{ $data_payment->vendor->id }}">{{ $data_payment->vendor->customer_name }}</option>   
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Customer</label>
                                        <div class="d-flex d-inline">
                                            <select class="form-control select2 form-select"
                                                data-placeholder="Choose One" name="customer_id" id="customer_id" disabled>
                                                @if(isset($data_payment->customer))
                                                <option value="{{ $data_payment->customer->id }}">{{ $data_payment->customer->customer_name }}</option> 
                                                @else
                                                <option value="null">No Customer</option>
                                                @endif  
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" class="form-label">Nomor Transaksi</label>
                                        <input type="text" name="no_transactions" id="no_transactions" class="form-control" readonly placeholder="Choose Transaction" value="{{ $data_payment->transaction }}" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" class="form-label">Date</label>
                                        <input type="date" class="form-control" name="date_payment" id="date_payment" value="{{ $data_payment->date_payment }}" readonly >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="currency_head_id" class="form-label">Currency</label>
                                        <select class="form-control select2 form-select"
                                            data-placeholder="Choose Currency" name="currency_head_id" id="currency_head_id" disabled>
                                            <option value="{{ $data_payment->currency_id }}">{{ $data_payment->currency->initial }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" class="form-label">Description</label>
                                        <input type="text" class="form-control" name="description" id="description" value="{{ $data_payment->description }}" placeholder="Desc" readonly >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="account_id" class="form-label">Account Name</label>
                                        <select class="form-control select2 form-select"
                                            data-placeholder="Choose One" name="account_id" id="account_id" disabled>
                                            <option value="{{ $data_payment->account->id }}">{{ $data_payment->account->account_name }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            @if(isset($data_payment->job_order))
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" id="choose_job_order" name="choose_job_order" checked>
                                        <span class="custom-control-label"><b>Choose Job Order</b></span>
                                    </label>
                                </div>
                            </div>
                            <div id="job_order_display">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="job_order_id" class="form-label">Job Order</label>
                                            <select class="form-control select2 form-select"
                                                data-placeholder="Choose One" name="job_order_id" id="job_order_id" disabled>
                                                <option value="{{ $data_payment->job_order_id }}:{{ $data_payment->source }}">{{ $data_payment->job_order->job_order_id }} - {{ $data_payment->source }}</option>
                                            </select>
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
                                            <th style="min-width:15rem;">Charge Type / Payable</th>
                                            <th style="min-width:15rem;">Tanggal</th>
                                            <th style="min-width:10rem;">Jumlah</th>
                                            <th style="min-width:10rem;">Diskon</th>
                                            <th style="min-width:10rem;">Total</th>
                                            <th style="min-width:15rem;">Account Name</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody id="form-container">
                                        @foreach($data_payment->details as $data)
                                        <tr class="form-wrapper">
                                            <td></td>
                                            <td>
                                                <select class="form-control select2 form-select" disabled>
                                                    <option value="{{ $data->charge_type }}" selected>{{ ucfirst($data->charge_type) }}</option>
                                                </select>
                                                @if($data->charge_type === 'payable' && $data->payable)
                                                <select class="form-control select2 form-select" name="detail_order" data-placeholder="Choose One" disabled>
                                                    <option value="{{ $data->payable_id }}" selected>{{ $data->payable->transaction }}</option>
                                                </select>
                                                @elseif($data->charge_type === 'account')
                                                <input type="text" class="form-control" value="{{ $data->description }}" readonly/>
                                                @endif
                                            </td>
                                            <td>
                                                @if($data->charge_type === 'payable' && $data->payable)
                                                <input type="date" class="form-control" readonly name="detail_date" value="{{ $data->payable->date_order }}" readonly/>
                                                @else
                                                <input type="date" class="form-control" readonly name="detail_date" value="{{ $data->created_at ? $data->created_at->format('Y-m-d') : '' }}" readonly/>
                                                @endif
                                            </td>
                                            <td>
                                                @if($data->charge_type === 'payable' && $data->payable)
                                                <input type="text" class="form-control" readonly name="detail_jumlah" value="{{ number_format($data->payable->total-$data->payable->dp-$data->getDpPaymentBefore($data->head_id),2,'.',',') }}" readonly/>
                                                @else
                                                <input type="text" class="form-control" readonly name="detail_jumlah" value="{{ number_format($data->amount, 2, '.', ',') }}" readonly/>
                                                @endif
                                                @if(isset($data->currency_via_id))
                                                <label class="custom-control custom-radio" style="margin-bottom: 0.375rem;">
                                                    <input type="radio" class="custom-control-input" value="1" checked>
                                                    <span class="custom-control-label form-label">Mata Uang Lain</span>
                                                </label>
                                                <div class="d-flex justify-content-between gap-2">
                                                    <input type="text" class="form-control" readonly name="other_currency_nominal" value="{{ number_format($data->amount_via,2) }}"/>
                                                    <select class="form-control select2 form-select" data-placeholder="X" name="other_currency_type" disabled>
                                                        @php
                                                            $name = "";
                                                            if($data->currency_via->from_currency_id === $data_payment->currency_id) {
                                                                $name = $data->currency_via->to_currency->initial;
                                                            } else {
                                                                $name = $data->currency_via->from_currency->initial;
                                                            }
                                                        @endphp
                                                        <option value="{{ $data->currency_via_id }}">{{ $name }}</option>
                                                    </select>
                                                </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-between gap-2">
                                                    <input type="text" class="form-control" name="detail_discount_nominal" readonly value="{{ number_format($data->discount_nominal,2,'.',',')}}"/>
                                                    <select class="form-control select2 form-select" data-placeholder="Choose one" name="detail_discount_type" disabled>
                                                        <option value="persen" {{ $data->discount_type === "persen" ? "selected" : "" }}>%</option>
                                                        <option value="nominal" {{ $data->discount_type === "nominal" ? "selected" : "" }}>0</option>
                                                    </select>
                                                </div>
                                                @if(isset($data->dp_nominal))
                                                <label class="custom-control custom-radio" style="margin-bottom: 0.375rem;">
                                                    <input type="radio" class="custom-control-input" value="1" checked>
                                                    <span class="custom-control-label form-label">Bayar DP</span>
                                                </label>
                                                <div class="d-flex justify-content-between gap-2">
                                                    <input type="text" class="form-control" name="detail_dp_nominal" readonly value="{{ $data->dp_nominal }}"/>
                                                    <select class="form-control select2 form-select" data-placeholder="Choose one" name="detail_dp_type" disabled>
                                                        <option value="persen" {{ $data->dp_type === "persen" ? "selected" : "" }}>%</option>
                                                        <option value="nominal" {{ $data->dp_type === "nominal" ? "selected" : "" }}>0</option>
                                                    </select>
                                                </div>
                                                @endif
                                            </td>
                                            <td>
                                                <input type="text" class="form-control total_input" readonly name="detail_total" value="{{ number_format($data->total,2,'.',',') }}"/>
                                            </td>
                                            <td>
                                                <select class="form-control select2 form-select" disabled>
                                                    <option value="{{ $data->account->id }}">{{ $data->account->account_name }}</option>
                                                </select>
                                                <label class="form-label">Remark</label>
                                                <input type="text" class="form-control remark-input" placeholder="Remark" name="detail_remark" value="{{ $data->remark }}" readonly/>
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
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="note_recive" class="form-label">Catatan Transaksi</label>
                                        <textarea name="note_payment" id="note_payment" cols="30" rows="5" readonly placeholder="Tulis catatan transaksi di sini ( maks 1000 karakter )" class="form-control">{{ $data_payment->note }}</textarea>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <table class="table mt-5">
                                        <tr>
                                            <td>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    Biaya Lain
                                                    <input type="text" style="width: 50%" class="form-control" name="additional_cost" id="additional_cost" placeholder="0" value="{{ number_format($data_payment->additional_cost,2,'.',',') }}" readonly />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    Diskon
                                                    <input type="text" style="width: 50%" class="form-control" id="discount_display" name="discount_display" readonly placeholder="0" value="{{ number_format($data_payment->discount,2,'.',',')}}" />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    Total
                                                    <input type="text" style="width: 50%" class="form-control" id="total_display" name="total_display" readonly placeholder="0" value="{{ number_format($data_payment->total,2,'.',',')}}" />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr style="{{ $data_payment->dp > 0 ? "" : "display: none" }}" id="dp">
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    DP
                                                    <input type="text" style="width: 50%" class="form-control" id="display_dp" name="display_dp" readonly placeholder="0" value="{{ number_format($data_payment->dp, 2, '.',',') }}" />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr style="{{ $data_payment->dp > 0 ? "block" : "display: none" }}" id="sisa">
                                            <td>
                                                <div class="d-flex justify-content-between">
                                                    Sisa
                                                    <input type="text" style="width: 50%" class="form-control" id="display_sisa" name="display_sisa" readonly placeholder="0"  value="{{ number_format($data_payment->total - $data_payment->dp, 2, '.',',')  }}"/>
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