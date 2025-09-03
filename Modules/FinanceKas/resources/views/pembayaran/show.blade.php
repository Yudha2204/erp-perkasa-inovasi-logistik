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
                <h1>Cash & Bank Out</h1>
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
                                        <label for="currency_id" class="form-label">Currency</label>
                                        <select class="form-control select2 form-select"
                                            data-placeholder="Choose Currency" name="currency_id" id="currency_id" disabled>
                                            <option value="{{ $head->currency_id }}">{{ $head->currency->initial }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="account_id" class="form-label">Dari Akun</label>
                                        <select class="form-control select2 form-select"
                                            data-placeholder="Choose One" name="account_head_id" id="account_head_id" disabled>
                                            <option value="{{ $head->account->account_name }}">{{ $head->account->account_name }}</option>
                                        </select>
                                    </div>
                                </div>
                                
                              <div class="col-md-4">
                                <div class="form-group">
                                    <label for="date" class="form-label">Date</label>
                                    <input type="date" class="form-control" name="date" id="date" value="{{ $head->date_kas_out }}" readonly>
                                </div>
                            </div>
                          </div>
                          <div class="row">
                              <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="no_transactions" class="form-label">Nomor Transaksi <a data-bs-effect="effect-scale" data-bs-toggle="modal" style="display: none;" href="#modal-transaction-format"><i class="fa fa-cog"></i></a></label>
                                        <input type="text" name="no_transactions" id="no_transactions" class="form-control" readonly placeholder="Choose Transaction" value="{{ $head->transaction }}" />
                                    </div>
                              </div>
                              <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="description" class="form-label">Description</label>
                                        <input type="text" class="form-control" name="description" id="description" placeholder="Desc" readonly value="{{ $head->description }}"/>
                                    </div>
                              </div>
                          </div>
                          <!-- <div class="row">
                              <div class="col-md-6">
                                  <label class="custom-control custom-radio">
                                      <input type="radio" class="custom-control-input" id="choose_job_order" name="choose_job_order" value="{{ isset($head->job_order) ? 1 : 0 }}" {{ isset($head->job_order) ? "checked" : "" }}>
                                      <span class="custom-control-label"><b>Choose Job Order</b></span>
                                  </label>
                              </div>
                          </div>
                          <div id="job_order_display" style="{{ isset($head->job_order) ? "" : "display:none;" }}">
                              <div class="row">
                                <div class="col-md-4">
                                    @php
                                        $commodity = 'Empty';
                                        $consignee = 'Empty';
                                        $shipper = 'Empty';
                                        $transportation = 'Empty';
                                        $transportation_desc = 'Empty';
                                        if($head->job_order) {
                                            $commodity = $head->job_order->description;
                                            $consignee = $head->job_order->consignee;
                                            $shipper = $head->job_order->shipper;
                                            $transportation = $head->job_order->transportation;
                                            if($transportation === 1) {
                                                $transportation = 'Air Freight';
                                            } else if($transportation === 2) {
                                                $transportation = 'Sea Freight';
                                            } else {
                                                $transportation = 'Land Trucking';
                                            }
                                            $transportation_desc = $head->job_order->transportation_desc;
                                        }  
                                    @endphp
                                    <div class="form-group">
                                        <label for="job_order" class="form-label">Job Order</label>
                                        <select class="form-control select2 form-select"
                                            data-placeholder="(Job Order No) - Export/Import" name="job_order" id="job_order" disabled>
                                            <option label="(Job Order No) - Export/Import"></option>
                                            @if($head->job_order)
                                                <option value="{{ $head->job_order->id }}:{{ $head->job_order->source }}" selected>{{ $head->job_order->job_order_id }} - {{ $head->job_order->source }}</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                  <div class="col-md-4">
                                      <div class="form-group">
                                          <label for="" class="form-label">Consignee</label>
                                          <input type="text" class="form-control" name="consignee" id="consignee" placeholder="Link" readonly value="{{ $consignee }}">
                                      </div>
                                  </div>
                                  <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="shipper" class="form-label">Shipper</label>
                                        <input type="text" class="form-control" name="shipper" id="shipper" placeholder="Link" readonly value="{{ $shipper }}">
                                    </div>
                                </div> 
                              </div>
                              <div class="row">
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
                                          <label for="commodity" class="form-label">Commodity</label>
                                          <input type="text" class="form-control" name="commodity" id="commodity" placeholder="Link" readonly value="{{ $commodity }}">
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
                                    @foreach($head->details as $data)
                                    <tr class="form-wrapper group-form">
                                        <td><a href="javascript:void(0)"
                                            class="arrow-display" onclick="toggleList(this)">
                                            <span style="color: #000; margin-top: 1rem">&#8743;</span>
                                            </a>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control description-input" name="desc_detail" placeholder="Desc..." value="{{ $data->description }}" readonly/>
                                            <label class="form-label remark-input">Remark</label>
                                            <input type="text" class="form-control remark-input mt-2" name="remark_detail" placeholder="Text..." value="{{ $data->remark }}" readonly/>
                                        </td>
                                        <td>
                                            <div class="form-group">
                                                <select class="form-control select2 form-select"
                                                    data-placeholder="Choose One" name="account_detail_id" disabled>
                                                    <option label="Choose One"></option>
                                                    <option value="{{ $data->account->account_name }}" selected>{{ $data->account->account_name }}</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" placeholder="Nominal" name="price_detail" readonly value="{{ number_format($data->total, 2, '.',',') }}"/>
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
                            <div style="display: flex; justify-content: flex-end">
                              <div class="row" style="margin-top: 1rem;">
                                <div style="height: 0.05rem; background-color: #000"></div>
                                <div style="display: flex; justify-content: space-between; margin-top: 1rem;">
                                  <input placeholder="Total" disabled style="width: 30px; background-color: transparent; outline: none; border: none;">
                                  <input id="total_prices" disabled placeholder="0" style="width: 100%; background-color: transparent; outline: none; border: none; direction: rtl;" value="{{ number_format($head->total, 2, '.',',') }}"/>
                                </div>
                              </div>
                            </div>
                            <div class="mt-3" style="text-align: right; margin-bottom: 1.5rem;">
                              <a href='javascript: history.go(-1)' class="btn btn-white color-grey">Close</a>
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

    <script>
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
    </script>
@endpush