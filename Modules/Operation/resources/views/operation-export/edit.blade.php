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
            <h4>Edit Operation Export</h4>
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
        <form action="{{ route('operation.export.update', $operationExport->id) }}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @csrf

            <input type="hidden" name="activity_id" value="{{ $activity->id ?? '' }}">


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
                                                <input type="text" class="form-control" name="" id="" value="{{ $operationExport->marketing->job_order_id }}" disabled>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Negara Asal / Origin</label>
                                                <input type="text" class="form-control" name="origin" id="origin" value="{{ $operationExport->origin }}" placeholder="fill the text.."  >
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Pickup Address</label>
                                                <input type="text" class="form-control mb-2" name="pickup_address" id="pickup_address" value="{{ $operationExport->pickup_address }}" placeholder="fill the text.."  >
                                                <input type="text" class="form-control mb-2" name="pickup_address_desc" id="pickup_address_desc" value="{{ $operationExport->pickup_address_desc }}" placeholder="fill the text.."  >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Tanggal Pickup</label>
                                                <input type="date" class="form-control" name="pickup_date" id="pickup_date" value="{{ $operationExport->pickup_date }}">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Transportasi</label>
                                                <select class="form-control select2 form-select"
                                                    data-placeholder="Choose one" name="transportation">
                                                    <option label="Choose one" selected disabled></option>
                                                    <option value="1" {{ $operationExport->transportation == 1 ? 'selected' : '' }}>Air Freight</option>
                                                    <option value="2" {{ $operationExport->transportation == 2 ? 'selected' : '' }}>Sea Freight</option>
                                                    <option value="3" {{ $operationExport->transportation == 3 ? 'selected' : '' }}>Land Trucking</option>
                                                </select>
                                                <div id="transportation_desc_before" style="display: block">
                                                    @if ($operationExport->transportation == 1)
                                                        <label class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                name="transportation_desc" value="Hand Carry" {{ $operationExport->transportation_desc == 'Hand Carry' ? 'checked' : '' }}>
                                                            <span class="custom-control-label">Hand Carry</span>
                                                        </label>
                                                        <label class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                name="transportation_desc" value="Express" {{ $operationExport->transportation_desc == 'Express' ? 'checked' : '' }}>
                                                            <span class="custom-control-label">Express</span>
                                                        </label>
                                                        <label class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                name="transportation_desc" value="Regular" {{ $operationExport->transportation_desc == 'Regular' ? 'checked' : '' }}>
                                                            <span class="custom-control-label">Regular</span>
                                                        </label>
                                                    @elseif ($operationExport->transportation == 2)
                                                        <label class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                name="transportation_desc" value="FCL" {{ $operationExport->transportation_desc == 'FCL' ? 'checked' : '' }}>
                                                            <span class="custom-control-label">FCL</span>
                                                        </label>
                                                        <label class="custom-control custom-radio">
                                                            <input type="radio" class="custom-control-input"
                                                                name="transportation_desc" value="LCL" {{ $operationExport->transportation_desc == 'LCL' ? 'checked' : '' }}>
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
                                                <input type="date" class="form-control" name="departure_date" id="departure_date" value="{{ $operationExport->departure_date}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Jam</label>
                                                <input type="time" class="form-control" name="departure_time" id="departure_time" value="{{ $operationExport->departure_time }}">
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
                                        <input type="date" class="form-control" name="batam_entry_date" id="batam_entry_date" value="{{ $operationExport->activity->batam_entry_date ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Tanggal Keluar Batam</label>
                                        <input type="date" class="form-control" name="batam_exit_date" id="batam_exit_date" value="{{ $operationExport->activity->batam_exit_date ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="volume" class="form-label">Tanggal Masuk Tujuan</label>
                                        <input type="date" class="form-control" name="destination_entry_date" id="destination_entry_date" value="{{ $operationExport->activity->destination_entry_date ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="volume" class="form-label">Tanggal Masuk Gudang</label>
                                        <input type="date" class="form-control" name="warehouse_entry_date" id="warehouse_entry_date" value="{{ $operationExport->activity->warehouse_entry_date ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="volume" class="form-label">Tanggal Keluar Gudang</label>
                                        <input type="date" class="form-control" name="warehouse_exit_date" id="warehouse_exit_date" value="{{ $operationExport->activity->warehouse_exit_date ?? '' }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="volume" class="form-label">Tanggal Diterima Client</label>
                                        <input type="date" class="form-control" name="client_received_date" value="{{ $operationExport->activity->client_received_date ?? '' }}"  id="client_received_date">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="volume" class="form-label">Tanggal Masuk SIN</label>
                                        <input type="date" class="form-control" name="sin_entry_date" value="{{ $operationExport->activity->sin_entry_date ?? '' }}" id="sin_entry_date">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="volume" class="form-label">Tanggal Keluar SIN</label>
                                        <input type="date" class="form-control" name="sin_exit_date" value="{{ $operationExport->activity->sin_exit_date ?? '' }}" id="sin_exit_date">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="volume" class="form-label">Tanggal POD Balik</label>
                                        <input type="date" class="form-control" name="return_pod_date"  value="{{ $operationExport->activity->return_pod_date ?? '' }}" id="return_pod_date">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="volume" class="form-label">Document</label>
                                        <input type="file" class="form-control" name="document_activities[]" accept=".pdf" multiple>
                                        @if ($documentActivity)
                                            @foreach ($documentActivity as $file)
                                            <a href="{{  Storage::url($file->document)  }}" target="_blank" class="btn btn-info shadow-sm btn-sm">View File</a>
                                            <a href="#" class="btn text-danger btn-sm"
                                            data-bs-toggle="tooltip"
                                            data-bs-original-title="Delete Document" id="delete-document-activity-id" data-id="{{ $file->id }}" >
                                                <span class="fe fe-trash-2 fs-14"></span>
                                            </a>
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
            <h2>Vendor</h2>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-column gap-2" id="vendor">
                                @foreach($vendor as $idx => $v)
                                <div class="row" data-id="{{ $idx }}">
                                    <input hidden name="vendor_id[{{ $idx }}]" class="form-control mb-4" value="{{ $v->id }}">
                                    <div class="col-lg-5">
                                        <div class="form-group">
                                            <label class="form-label">Vendor</label>
                                                <input type="text" name="vendor" class="form-control mb-4" placeholder="Link to finance" disabled value="{{ $v->getVendorFinance() !== null ? $v->getVendorFinance()->vendor->customer_name : "" }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-5">
                                        <div class="form-group">
                                            <label class="form-label">Transit Via</label>
                                                <input type="text" name="transit[{{ $idx }}]" class="form-control mb-4" placeholder="Fill the text" value="{{ $v->transit }}" onchange="updateList(this)">
                                        </div>
                                    </div>
                                    <div class="col-lg-2">
                                        <div class="form-group">
                                            <label class="form-label" style="opacity: 0">Delete</label>
                                            <button type="button" class="btn delete-row" onclick="deleteList(this)"><i class="fa fa-trash text-danger delete-form"></i></button>
                                        </div>
                                    </div>
                                    <input hidden name="operator[{{ $idx }}]" class="form-control mb-4 operator" value="{{ $v->id }}:nothing">
                                </div>
                                @endforeach
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
                                                <input type="text" class="form-control" name="destination" id="destination" value="{{ $operationExport->destination }}" placeholder="fill the text..">
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label class="form-label">Tanggal Kedatangan</label>
                                                <input type="date" class="form-control" name="arrival_date" id="arrival_date" value="{{ $operationExport->arrival_date }}" placeholder="fill the text.."  >
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Jam</label>
                                                <input type="time" class="form-control" name="arrival_time" id="arrival_time" value="{{ $operationExport->arrival_time }}" placeholder="fill the text.."  >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Delivery Address</label>
                                                <input type="text" class="form-control mb-2" name="delivery_address" id="delivery_address" value="{{ $operationExport->delivery_address }}" placeholder="fill the text.."  >
                                                <input type="text" class="form-control mb-2" name="delivery_address_desc" id="delivery_address_desc" value="{{ $operationExport->delivery_address_desc }}" placeholder="fill the text.."  >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Nama Penerima</label>
                                                <input type="text" class="form-control" name="recepient_name" id="recepient_name" value="{{ $operationExport->recepient_name }}" placeholder="fill the text.."  >
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Document</label>
                                                <input type="file" class="form-control" name="documents[]" accept=".pdf" multiple>
                                                @if ($documentArrival)
                                                    @foreach ($documentArrival as $fileArrival)
                                                    <a href="{{  Storage::url($fileArrival->document)  }}" target="_blank" class="btn btn-info shadow-sm btn-sm">View File</a>
                                                    <a href="#" class="btn text-danger btn-sm"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-original-title="Delete Document" id="delete-document-arrival-id" data-id="{{ $fileArrival->id }}" >
                                                        <span class="fe fe-trash-2 fs-14"></span>
                                                    </a>
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
                                        <textarea class="form-control mb-4" name="arrival_desc" placeholder="Fill the text"
                                        rows="4">{{ $operationExport->arrival_desc }}</textarea>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Remark</label>
                                        <textarea class="form-control mb-4" name="remark" placeholder="Fill the text"
                                            rows="4">{{ $operationExport->remark }}</textarea>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Status</label>
                                        <div class="custom-controls-stacked">
                                            <label class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" name="status"
                                                    value="1" {{ $operationExport->status == '1' ? 'checked' : '' }} >
                                                <span class="custom-control-label">On - Progress</span>
                                            </label>
                                            <label class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" name="status"
                                                    value="2" {{ $operationExport->status == '2' ? 'checked' : '' }} >
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

{{-- delete document --}}
@if (isset($documentActivity) && $documentActivity->count() > 0)
<form id="delete-activity-document" action="{{route('operation.export.delete-activity-document')}}" style="display: none;" method="POST">
    @csrf
    @method('POST')
    <input type="hidden" name="delete_document_activity_id">
</form>
@endif

{{-- delete document --}}
@if (isset($documentArrival) && $documentArrival->count() > 0)
<form id="delete-arrival-document" action="{{route('operation.export.delete-arrival-document')}}" style="display: none;" method="POST">
    @csrf
    @method('POST')
    <input type="hidden" name="delete_document_arrival_id">
</form>
@endif

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
            $('body').on('click', '#delete-document-activity-id', function () {
                let document_id = $(this).data('id');

                if (confirm("Are you sure want to delete this document?") == true) {
                        event.preventDefault();
                        $('input[name="delete_document_activity_id"]').val(document_id);
                        document.getElementById('delete-activity-document').submit();
                }
            });

            $('body').on('click', '#delete-document-arrival-id', function () {
                let document_id = $(this).data('id');

                if (confirm("Are you sure want to delete this document?") == true) {
                        event.preventDefault();
                        $('input[name="delete_document_arrival_id"]').val(document_id);
                        document.getElementById('delete-arrival-document').submit();
                }
            });

            $('select[name="transportation"]').change(function () {
                var transportation_desc_before =  document.getElementById("transportation_desc_before");
                transportation_desc_before.style['display'] = 'none';
                if (this.value == '1') {
                    $("#radio_buttons").html("");
                    var radioBtn = $(`<label class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input"
                                                name="transportation_desc" value="Hand Carry" {{ $operationExport->transportation_desc == 'Hand Carry' ? 'checked' : '' }}>
                                            <span class="custom-control-label">Hand Carry</span>
                                        </label>
                                        <label class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input"
                                                name="transportation_desc" value="Express" {{ $operationExport->transportation_desc == 'Express' ? 'checked' : '' }}>
                                            <span class="custom-control-label">Express</span>
                                        </label>
                                        <label class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input"
                                                name="transportation_desc" value="Regular" {{ $operationExport->transportation_desc == 'Regular' ? 'checked' : '' }}>
                                            <span class="custom-control-label">Regular</span>
                                        </label>`);
                    radioBtn.appendTo('#radio_buttons');
                }
                else if (this.value == '2') {
                    $("#radio_buttons").html("");
                    var radioBtn = $(`<label class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input"
                                                name="transportation_desc" value="FCL" {{ $operationExport->transportation_desc == 'FCL' ? 'checked' : '' }}>
                                            <span class="custom-control-label">FCL</span>
                                        </label>
                                        <label class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input"
                                                name="transportation_desc" value="LCL" {{ $operationExport->transportation_desc == 'LCL' ? 'checked' : '' }}>
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
                        <input hidden name="operator[${newId}]" class="form-control mb-4 operator" value="0:create">
                    </div>
                `);

                $vendorContainer.append($newRow);
            });
        });

        function deleteList(element) {
            let id = element.closest('.row').querySelector('.operator').value
            const operation = id.split(":")[1]
            id = id[0]
            if(operation !== "create") {
                element.closest('.row').querySelector('.operator').value = `${id}:delete`
                element.closest('.row').style["display"] = "none"
            } else {
                element.closest('.row').remove()
            }
        }

        function updateList(element) {
            let id = element.closest('.row').querySelector('.operator').value
            const operation = id.split(":")[1]
            id = id[0] 
            if(operation === "nothing") {
                element.closest('.row').querySelector('.operator').value = `${id}:update`
            }
        }



    </script>

@endpush