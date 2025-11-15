@extends('layouts.app')
@section('content')
@push('styles')
    <style>
        .f1-steps { overflow: hidden; position: relative; margin-top: 20px; }

        .f1-progress { position: absolute; top: 24px; left: 0; width: 100%; height: 1px; background: #ddd; }
        .f1-progress-line { position: absolute; top: 0; left: 0; height: 1px; background: #338056; }

        .f1-step { position: relative; float: left; width: 25%; padding: 0 5px; }

        .f1-step-icon {
            display: inline-block; width: 40px; height: 40px; margin-top: 4px; background: #ddd;
            font-size: 16px; color: #fff; line-height: 40px;
            -moz-border-radius: 50%; -webkit-border-radius: 50%; border-radius: 50%;
        }
        .f1-step.activated .f1-step-icon {
            background: #fff; border: 1px solid #338056; color: #338056; line-height: 38px;
        }
        .f1-step.active .f1-step-icon {
            width: 48px; height: 48px; margin-top: 0; background: #338056; font-size: 22px; line-height: 48px;
        }

        .f1-step p { color: #ccc; }
        .f1-step.activated p { color: #338056; }
        .f1-step.active p { color: #338056; }

        .f1 fieldset { display: none; text-align: left; }

        .f1-buttons { text-align: right; }

        .f1 .input-error { border-color: #f35b3f; }
    </style>
@endpush

<div class="main-content app-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">
            <!-- PAGE-HEADER -->
            <div class="page-header mb-0">
                <h1>Operation</h1>
            </div>
            <h4>Detail Operation Import</h4>
            <!-- PAGE-HEADER END -->
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
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Job Order Master ID</label>
                                                <input type="text" class="form-control" name="" id="" value="{{ $operationImport->marketing->job_order_id }}" disabled>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">MBL</label>
                                                <input type="text" class="form-control" name="mbl" id="mbl" value="{{ $operationImport->mbl }}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">HBL</label>
                                                <input type="text" class="form-control" name="hbl" id="hbl" value="{{ $operationImport->hbl }}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Chargeable Weight</label>
                                                <input type="number" step="0.01" class="form-control" name="chargeable_weight" id="chargeable_weight" value="{{ $operationImport->chargeable_weight }}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Negara Asal / Origin</label>
                                                <input type="text" class="form-control" name="origin" id="origin" value="{{ $operationImport->origin }}" disabled >
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Pickup Address</label>
                                                <input type="text" class="form-control mb-2" name="pickup_address" id="pickup_address" value="{{ $operationImport->pickup_address }}"  disabled>
                                                <input type="text" class="form-control mb-2" name="pickup_address_desc" id="pickup_address_desc" value="{{ $operationImport->pickup_address_desc }}"  disabled >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Tanggal Pickup</label>
                                                <input type="date" class="form-control" name="pickup_date" id="pickup_date" value="{{ $operationImport->pickup_date }}" disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Transportasi</label>
                                                <select class="form-control select2 form-select"
                                                    data-placeholder="Choose one" name="transportation" disabled>
                                                    <option label="Choose one" selected disabled></option>
                                                    <option value="1" {{ $operationImport->transportation == 1 ? 'selected' : '' }}>Air Freight</option>
                                                    <option value="2" {{ $operationImport->transportation == 2 ? 'selected' : '' }}>Sea Freight</option>
                                                    <option value="3" {{ $operationImport->transportation == 3 ? 'selected' : '' }}>Land Trucking</option>
                                                </select>
                                                <div id="transportation_desc_before" style="display: block">
                                                    @if ($operationImport->transportation_desc == "Hand Carry")
                                                        <label class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                value="Express" checked>
                                                            <span class="custom-control-label">Hand Carry</span>
                                                        </label>
                                                    @elseif ($operationImport->transportation_desc == "Express")
                                                        <label class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                value="Express" checked>
                                                            <span class="custom-control-label">Express</span>
                                                        </label>
                                                    @elseif ($operationImport->transportation_desc == "Regular")
                                                        <label class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                value="Express" checked>
                                                            <span class="custom-control-label">Regular</span>
                                                        </label>
                                                    @elseif ($operationImport->transportation_desc == "FCL")
                                                        <label class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                value="Express" checked>
                                                            <span class="custom-control-label">FCL</span>
                                                        </label>
                                                    @elseif ($operationImport->transportation_desc == "LCL")
                                                        <label class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                value="Express" checked>
                                                            <span class="custom-control-label">LCL</span>
                                                        </label>
                                                    @endif
                                                </div>
                                                <div id="radio_buttons"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Tanggal Berangkat</label>
                                                <input type="date" class="form-control" name="departure_date" id="departure_date" value="{{ $operationImport->departure_date}}" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Jam</label>
                                                <input type="time" class="form-control" name="departure_time" id="departure_time" value="{{ $operationImport->departure_time }}" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <h2>Activity</h2>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Tanggal Masuk Batam</label>
                                        <input type="date" class="form-control" name="batam_entry_date" id="batam_entry_date" value="{{ $operationImport->activity->batam_entry_date ?? '' }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Tanggal Keluar Batam</label>
                                        <input type="date" class="form-control" name="batam_exit_date" id="batam_exit_date" value="{{ $operationImport->activity->batam_exit_date ?? '' }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="volume" class="form-label">Tanggal Masuk Tujuan</label>
                                        <input type="date" class="form-control" name="destination_entry_date" id="destination_entry_date" value="{{ $operationImport->activity->destination_entry_date ?? '' }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="volume" class="form-label">Tanggal Masuk Gudang</label>
                                        <input type="date" class="form-control" name="warehouse_entry_date" id="warehouse_entry_date" value="{{ $operationImport->activity->warehouse_entry_date ?? '' }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="volume" class="form-label">Tanggal Keluar Gudang</label>
                                        <input type="date" class="form-control" name="warehouse_exit_date" id="warehouse_exit_date" value="{{ $operationImport->activity->warehouse_exit_date ?? '' }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="volume" class="form-label">Tanggal Diterima Client</label>
                                        <input type="date" class="form-control" name="client_received_date" value="{{ $operationImport->activity->client_received_date ?? '' }}"  id="client_received_date" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="volume" class="form-label">Tanggal Masuk SIN</label>
                                        <input type="date" class="form-control" name="sin_entry_date" value="{{ $operationImport->activity->sin_entry_date ?? '' }}" id="sin_entry_date" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="volume" class="form-label">Tanggal Keluar SIN</label>
                                        <input type="date" class="form-control" name="sin_exit_date" value="{{ $operationImport->activity->sin_exit_date ?? '' }}" id="sin_exit_date" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="volume" class="form-label">Tanggal POD Balik</label>
                                        <input type="date" class="form-control" name="return_pod_date"  value="{{ $operationImport->activity->return_pod_date ?? '' }}" id="return_pod_date" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="volume" class="form-label">Document</label>
                                        <input type="file" class="form-control" name="document_activities[]" accept=".pdf" multiple disabled>
                                        @if ($documentActivity)
                                            @foreach ($documentActivity as $file)
                                            <a href="{{  Storage::url($file->document)  }}" target="_blank" class="btn btn-info shadow-sm btn-sm">View File</a>
                                            @endforeach
                                        @else
                                            <p>there are no documents here</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
            <h2>Shipment Progress</h2>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            @if ($progress)
                            @php
                                $i = 1;
                            @endphp
                                @foreach ($progress as $p)
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Tanggal</label>
                                                        <input type="date" class="form-control" name="date_progress" id="date_progress" value="{{ $p->date_progress }}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-label">Jam</label>
                                                        <input type="time" class="form-control" name="time_progress" id="time_progress" value="{{ $p->time_progress }}" disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">Lokasi Progress {{ $i++ }}</label>
                                                        <input type="text" class="form-control mb-2" name="location" id="location" value="{{ $p->location }}" placeholder="Nama tempat" disabled >
                                                        <input type="text" class="form-control mb-2" name="location_desc" id="location_desc" value="{{ $p->location_desc}}" placeholder="alamat lengkap tempat" disabled >
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">Transportasi</label>
                                                        <select class="form-control select2 form-select"
                                                            data-placeholder="Choose one" name="transportation" disabled>
                                                            <option label="Choose one" selected disabled></option>
                                                            <option value="1" {{ $p->transportation == 1 ? "selected" : "" }}>Air Freight</option>
                                                            <option value="2" {{ $p->transportation == 2 ? "selected" : "" }}>Sea Freight</option>
                                                            <option value="3" {{ $p->transportation== 3 ? "selected" : "" }}>Land Trucking</option>
                                                        </select>
                                                        <label class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                value="{{ $p->transportation_desc }}" checked>
                                                            <span class="custom-control-label">{{ $p->transportation_desc }}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">Carrier / Pengangkut</label>
                                                        <input type="text" class="form-control" name="carrier" id="carrier" value="{{ $p->carrier }}" placeholder="fill the text.." disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                    
                                        <div class="col-md-4">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">Keterangan Progress {{ $i++ }}</label>
                                                        <input type="text" class="form-control" name="description" id="description" value="{{ $p->description }}" placeholder="fill the text.." disabled>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label class="form-label">Document</label>
                                                        <input type="file" class="form-control" name="documents[]" accept=".pdf" multiple disabled>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>               
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <h2>Vendor</h2>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            @foreach($vendor as $idx => $v)
                            <div class="row" data-id="{{ $idx }}">
                                <input hidden name="vendor_id[{{ $idx }}]" class="form-control mb-4" disabled value="{{ $v->id }}">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-label">Vendor</label>
                                            <input type="text" name="vendor" class="form-control mb-4" disabled placeholder="Link to finance" disabled value="{{ $v->getVendorFinance() !== null ? $v->getVendorFinance()->vendor->customer_name : "" }}">
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="form-label">Transit Via</label>
                                            <input type="text" name="transit[{{ $idx }}]" class="form-control mb-4" disabled placeholder="Fill the text" value="{{ $v->transit }}">
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <h2>Arrival</h2>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Negara Tujuan / Destination</label>
                                                <input type="text" class="form-control" name="destination" id="destination" value="{{ $operationImport->destination }}"  disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label class="form-label">Tanggal Kedatangan</label>
                                                <input type="date" class="form-control" name="arrival_date" id="arrival_date" value="{{ $operationImport->arrival_date }}"  disabled >
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Jam</label>
                                                <input type="time" class="form-control" name="arrival_time" id="arrival_time" value="{{ $operationImport->arrival_time }}"  disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Delivery Address</label>
                                                <input type="text" class="form-control mb-2" name="delivery_address" id="delivery_address" value="{{ $operationImport->delivery_address }}"  disabled >
                                                <input type="text" class="form-control mb-2" name="delivery_address_desc" id="delivery_address_desc" value="{{ $operationImport->delivery_address_desc }}"  disabled  >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Nama Penerima</label>
                                                <input type="text" class="form-control" name="recepient_name" id="recepient_name" value="{{ $operationImport->recepient_name }}"  disabled>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Document</label>
                                                <input type="file" class="form-control" name="documents[]" accept=".pdf" multiple disabled>
                                                @if ($documentArrival)
                                                    @foreach ($documentArrival as $fileArrival)
                                                    <a href="{{  Storage::url($fileArrival->document)  }}" target="_blank" class="btn btn-info shadow-sm btn-sm">View File</a>
                                                    @endforeach
                                                @else
                                                    <p>there are no documents here</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Keterangan Kedatangan</label>
                                        <textarea class="form-control mb-4" name="arrival_desc"  disabled
                                        rows="4">{{ $operationImport->arrival_desc }}</textarea>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Remark</label>
                                        <textarea class="form-control mb-4" name="remark"  disabled
                                            rows="4">{{ $operationImport->remark }}</textarea>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Status</label>
                                        <div class="custom-controls-stacked">
                                            <label class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" name="status"
                                                    value="1" {{ $operationImport->status == '1' ? 'checked' : '' }} disabled >
                                                <span class="custom-control-label">On - Progress</span>
                                            </label>
                                            <label class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" name="status"
                                                    value="2" {{ $operationImport->status == '2' ? 'checked' : '' }}  disabled>
                                                <span class="custom-control-label">End - Progress</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
             
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <div class="btn-list text-end">
                            <a href="{{ route('operation.import.index') }}" class="btn btn-default">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <br><br><br>
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