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
            <h4>Add New Operation Export</h4>
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
        <form action="{{ route('operation.export.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
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
                                                <select name="job_order_id" class="form-control form-select" data-bs-placeholder="Select" disabled>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Negara Asal / Origin</label>
                                                <input type="text" class="form-control" name="origin" id="origin" value="{{ old('origin') }}" placeholder="fill the text.."  >
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Pickup Address</label>
                                                <input type="text" class="form-control mb-2" name="pickup_address" id="pickup_address" value="{{ old('pickup_address') }}" placeholder="fill the text.."  >
                                                <input type="text" class="form-control mb-2" name="pickup_address_desc" id="pickup_address_desc" value="{{ old('pickup_address_desc') }}" placeholder="fill the text.."  >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Tanggal Pickup</label>
                                                <input type="date" class="form-control" name="pickup_date" id="pickup_date" value="{{ old('pickup_date') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Transportasi</label>
                                                <select class="form-control select2 form-select"
                                                    data-placeholder="Choose one" name="transportation">
                                                    <option label="Choose one" selected disabled></option>
                                                    <option value="1" {{ old('transportation') == 1 ? "selected" : "" }}>Air Freight</option>
                                                    <option value="2" {{ old('transportation') == 2 ? "selected" : "" }}>Sea Freight</option>
                                                    <option value="3" {{ old('transportation') == 3 ? "selected" : "" }}>Land Trucking</option>
                                                </select>
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
                                                <input type="date" class="form-control" name="departure_date" id="departure_date" value="{{ old('departure_date') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Jam</label>
                                                <input type="time" class="form-control" name="departure_time" id="departure_time" value="{{ old('departure_time') }}">
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
                                        <input type="date" class="form-control" name="batam_entry_date" id="batam_entry_date" value="{{ old('batam_entry_date') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Tanggal Keluar Batam</label>
                                        <input type="date" class="form-control" name="batam_exit_date" id="batam_exit_date" value="{{ old('batam_exit_date') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="volume" class="form-label">Tanggal Masuk Tujuan</label>
                                        <input type="date" class="form-control" name="destination_entry_date" id="destination_entry_date" value="{{ old('destination_entry_date') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="volume" class="form-label">Tanggal Masuk Gudang</label>
                                        <input type="date" class="form-control" name="warehouse_entry_date" id="warehouse_entry_date" value="{{ old('warehouse_entry_date') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="volume" class="form-label">Tanggal Keluar Gudang</label>
                                        <input type="date" class="form-control" name="warehouse_exit_date" id="warehouse_exit_date" value="{{ old('warehouse_exit_date') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="volume" class="form-label">Tanggal Diterima Client</label>
                                        <input type="date" class="form-control" name="client_received_date" id="client_received_date">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="volume" class="form-label">Tanggal Masuk SIN</label>
                                        <input type="date" class="form-control" name="sin_entry_date" id="sin_entry_date">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="volume" class="form-label">Tanggal Keluar SIN</label>
                                        <input type="date" class="form-control" name="sin_exit_date" id="sin_exit_date">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="volume" class="form-label">Tanggal POD Balik</label>
                                        <input type="date" class="form-control" name="return_pod_date" id="return_pod_date">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="volume" class="form-label">Document</label>
                                        <input type="file" class="form-control" name="document_activities[]" accept=".pdf" multiple>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
            <h2>Vendor</h2>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-column gap-2" id="vendor">
                                <div class="row" data-id="0">
                                    <div class="col-lg-5">
                                        <div class="form-group">
                                            <label class="form-label">Vendor</label>
                                                <input type="text" name="vendor" class="form-control mb-4" placeholder="Link to finance" disabled>
                                        </div>
                                    </div>
                                    <div class="col-lg-5">
                                        <div class="form-group">
                                            <label class="form-label">Transit Via</label>
                                                <input type="text" name="transit[0]" class="form-control mb-4" placeholder="Fill the text">
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label class="form-label" style="opacity: 0">Delete</label>
                                            <button type="button" class="btn delete-row" onclick="deleteList(this)"><i class="fa fa-trash text-danger delete-form"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-lg-12">
                                    <a href="javascript:void(0)" class="btn btn-default w-100"
                                        id="add-transit">
                                        <span><i class="fa fa-plus"></i></span> Add New Transit
                                    </a>
                                </div>
                            </div>
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
                                                <input type="text" class="form-control" name="destination" id="destination" value="{{ old('destination') }}" placeholder="fill the text..">
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label class="form-label">Tanggal Kedatangan</label>
                                                <input type="date" class="form-control" name="arrival_date" id="arrival_date" value="{{ old('arrival_date') }}" placeholder="fill the text.."  >
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Jam</label>
                                                <input type="time" class="form-control" name="arrival_time" id="arrival_time" value="{{ old('arrival_time') }}" placeholder="fill the text.."  >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Delivery Address</label>
                                                <input type="text" class="form-control mb-2" name="delivery_address" id="delivery_address" value="{{ old('delivery_address') }}" placeholder="fill the text.."  >
                                                <input type="text" class="form-control mb-2" name="delivery_address_desc" id="delivery_address_desc" value="{{ old('delivery_address_desc') }}" placeholder="fill the text.."  >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Nama Penerima</label>
                                                <input type="text" class="form-control" name="recepient_name" id="recepient_name" value="{{ old('recepient_name') }}" placeholder="fill the text.."  >
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Document</label>
                                                <input type="file" class="form-control" name="documents[]" accept=".pdf" multiple>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Keterangan Kedatangan</label>
                                        <textarea class="form-control mb-4" name="arrival_desc" placeholder="Fill the text"
                                        rows="4">{{ old('arrival_desc') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Remark</label>
                                        <textarea class="form-control mb-4" name="remark" placeholder="Fill the text"
                                            rows="4">{{ old('remark') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Status</label>
                                        <div class="custom-controls-stacked">
                                            <label class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" name="status"
                                                    value="1" {{ old('status') == 1 ? "checked" : "" }}>
                                                <span class="custom-control-label">On - Progress</span>
                                            </label>
                                            <label class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" name="status"
                                                    value="2" {{ old('status') == 2 ? "checked" : "" }}>
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
                            <a href="{{ route('operation.export.index') }}" class="btn btn-default">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

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

    <script>
        $(document).ready(function () {
            $('select[name="transportation"]').change(function () {
                if (this.value == '1') {
                    $("#radio_buttons").html("");
                    var radioBtn = $(`<label class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input"
                                                name="transportation_desc" value="Hand Carry" {{ old('transportation_desc') == "Hand Carry" ? "checked" : "" }}>
                                            <span class="custom-control-label">Hand Carry</span>
                                        </label>
                                        <label class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input"
                                                name="transportation_desc" value="Express" {{ old('transportation_desc') == "Express" ? "checked" : "" }}>
                                            <span class="custom-control-label">Express</span>
                                        </label>
                                        <label class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input"
                                                name="transportation_desc" value="Regular" {{ old('transportation_desc') == "Regular" ? "checked" : "" }}>
                                            <span class="custom-control-label">Regular</span>
                                        </label>`);
                    radioBtn.appendTo('#radio_buttons');
                }
                else if (this.value == '2') {
                    $("#radio_buttons").html("");
                    var radioBtn = $(`<label class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input"
                                                name="transportation_desc" value="FCL" {{ old('transportation_desc') == "FCL" ? "checked" : "" }}>
                                            <span class="custom-control-label">FCL</span>
                                        </label>
                                        <label class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input"
                                                name="transportation_desc" value="LCL" {{ old('transportation_desc') == "LCL" ? "checked" : "" }}>
                                            <span class="custom-control-label">LCL</span>`);
                    radioBtn.appendTo('#radio_buttons');
                }
                else if (this.value == '3') {
                    $("#radio_buttons").html("");
                }
            });

            $('#add-transit').click(function() {
                var $vendorContainer = $('#vendor');
                var lastId = $vendorContainer.find('.row:last-child').data('id') || 0;
                var newId = lastId + 1;

                var $newRow = $(`
                    <div class="row" data-id="${newId}">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label">Vendor</label>
                                <input type="text" name="vendor" class="form-control mb-4" placeholder="Link to finance" disabled>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="form-group">
                                <label class="form-label">Transit Via</label>
                                <input type="text" name="transit[${newId}]" class="form-control mb-4" placeholder="Fill the text">
                            </div>
                        </div>
                        <div class="col-lg-2">
                            <div class="form-group">
                                <label class="form-label" style="opacity: 0">Delete</label>
                                <button type="button" class="btn delete-row" onclick="deleteList(this)"><i class="fa fa-trash text-danger delete-form"></i></button>
                            </div>
                        </div>
                    </div>
                `);

                $vendorContainer.append($newRow);
            });
        });

        function deleteList(element) {
            element.closest('.row').remove();
        }


    </script>

@endpush