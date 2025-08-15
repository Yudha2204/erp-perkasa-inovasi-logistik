@extends('layouts.app')
@push('styles')
<style>
    tbody tr.grouping {
        background-color: #cce5ff;
        /* Blue background color */
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
            <h1>Marketing Import</h1>
        </div>
        <h4>Detail Quotation</h4>
        <!-- PAGE-HEADER END -->
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
                                            <input class="form-control mb-4" name="customer" value="{{ $marketingImport->contact->customer_name }}"  readonly type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Phone Number</label>
                                            <input class="form-control mb-4" name="phone_number" value="{{ $marketingImport->contact->phone_number }}"  readonly type="number">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Company Address</label>
                                            <input class="form-control mb-4" name="company_address" value="{{ $marketingImport->contact->address }}"  readonly type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Company Name</label>
                                            <input class="form-control mb-4" name="company_name" value="{{ $marketingImport->contact->company_name }}"  readonly type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Payment Term</label>
                                            <div class="term-payment-class">
                                                @foreach ($marketingImport->contact->termPaymentContacts as $data)
                                                    <input class="form-control mb-4" name="payment_term"  value="{{ $data->term_payment->name }}" readonly type="text">
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Customer ID</label>
                                            <input class="form-control mb-4" name="payment_term"  value="{{ $marketingImport->contact->customer_id }}" readonly type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Origin</label>
                                            <input class="form-control mb-4" name="dept" value="{{ $marketingImport->origin }}"  readonly type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label">Destination</label>
                                            <input class="form-control mb-4" name="dest" value="{{ $marketingImport->destination }}"  readonly type="text">
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
                                            <input class="form-control mb-4" name="date" value="{{ $quotation->date }}"  readonly type="date">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Quote No.</label>
                                            <input class="form-control mb-4" name="quote_no" value="{{ $quotation->quotation_no }}"  readonly type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Valid Until</label>
                                            <input class="form-control mb-4" name="valid_until" value="{{ $quotation->valid_until }}"  readonly type="date">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Shipper</label>
                                            <input class="form-control mb-4" name="shipper" value="{{ $marketingImport->shipper }}"  readonly type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Consignee</label>
                                            <input class="form-control mb-4" name="shipper" value="{{ $marketingImport->consignee }}"  readonly type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Project Desc</label>
                                            <textarea class="form-control mb-4" name="remark"   readonly
                                                rows="4">{{ $quotation->project_desc }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="form-group">
                                            <label class="form-label" for="currency">Total Weight</label>
                                            <input type="text" class="form-control mb-4" id="currency" name="currency" readonly value="{{ $marketingImport->total_weight }}" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="form-group">
                                            <label class="form-label" for="currency">Total Volume</label>
                                            <input type="text" class="form-control mb-4" id="currency" name="currency" readonly value="{{ $marketingImport->total_volume }}" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="form-group">
                                            <label class="form-label" for="currency">Currency</label>
                                            <input type="text" class="form-control mb-4" id="currency" name="currency" readonly value="{{ $quotation->currency->initial }}" autocomplete="off">
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
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="container mt-5">
                                            <table class="table table-bordered table-hover"
                                                id="editableTable">
                                                <thead>
                                                    <tr>
                                                        <th>DESCRIPTION</th>
                                                        <th>TOTAL</th>
                                                        <th>REMARK</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    
                                                    @foreach ($group as $g)
                                                        @php
                                                            $totalGroup = null;
                                                            $total = null;
                                                        @endphp
                                                        @foreach ($g->items as $item)
                                                        <tr class="group-form">
                                                            <td width="50%">
                                                                <input type="text" class="form-control description-input" value="{{ $item->description }}" readonly  />
                                                                <input type="hidden" class="form-control description-input" readonly />
                                                            </td>
                                                            <td width="20%">
                                                                <div class="d-flex align-items-center justify-content-between">
                                                                    <div class="currency-text">{{ $quotation->currency->initial }}</div>
                                                                    <input type="text" style="text-align: right; margin-left: 5px" value="{{ $item->total }}" class="form-control border-left-0 pl-2 text-right total-input" readonly />
                                                                </div>
                                                            </td>
                                                            <td width="20%">
                                                                <input type="text" class="form-control remark-input" value="{{ $item->remark }}" readonly />
                                                            </td>
                                                        </tr>

                                                        @php
                                                            $total[] = $item->total;
                                                            $totalHitungTotal[] = $item->total;
                                                        @endphp

                                                        @endforeach
                                        
                                                        @php
                                                            $totalPriceRow = array_sum($totalHitungTotal);
                                                            $totalGroup = array_sum($total);
                                                        @endphp

                                                        <tr class="group-total" style="background-color: #C0E3FF;">
                                                            <td>
                                                                <div class="text-white bg-primary p-2 group-text" style="border-radius: 5px;">Total {{ $g->group }}</div>
                                                            </td>
                                                            <td>
                                                                <div class="text-white bg-primary p-2" style="border-radius: 5px;">
                                                                    <div class="d-flex align-items-center justify-content-between">
                                                                        <div class="currency-text">{{ $quotation->currency->initial }}</div>
                                                                        <div class="group-total-value">{{ $totalGroup }}</div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td></td>
                                                        </tr> 
                                                 
                                                    @endforeach

                                                </tbody>
                                            </table>
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
                                                                        <strong id="overall-total-currency">{{ $quotation->currency->initial }} </strong>
                                                                        <strong id="overall-total"> {{ $totalPriceRow }}</strong>
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
                        </div>
                    </div>
                </div>
                <!-- row 2 -->

    
        </div>
        <!-- CONTAINER END -->
    </div>
</div>

@endsection