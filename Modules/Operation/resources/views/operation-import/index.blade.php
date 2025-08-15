@extends('layouts.app')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <div class="main-content app-content mt-0">
        <div class="side-app">

            <!-- CONTAINER -->
            <div class="main-container container-fluid">

                <!-- PAGE-HEADER -->
                <div class="page-header mb-0">
                    <h1>Operation</h1>
                </div>
                <!-- PAGE-HEADER END -->
                <h3>Import</h3>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <form action="{{ route('operation.import.index') }}">
                            <div class="d-flex d-inline">
                                <input type="text" name="search" id="search" value="{{ $search }}"
                                    class="form-control" placeholder="Searching.....">
                                &nbsp;&nbsp;
                                <button type="submit" class="btn btn-primary"><i
                                        class="fe fe-search me-1"></i>Search&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-12">
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
                                <div class="table-responsive">
                                    <table class="table text-nowrap text-md-nowrap mb-0">
                                        <thead>
                                            <tr style="text-align: center;">
                                                <th>No</th>
                                                <th>Job Order ID</th>
                                                <th>Departure Date</th>
                                                <th>Arrival Date</th>
                                                <th>Origin</th>
                                                <th>Destination</th>
                                                <th>Nama Penerima</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($operationImports as $key => $data)
                                                <tr>
                                                    <td>{{ $operationImports->firstItem() + $key }}</td>
                                                    <td>
                                                        {{ $data->marketing->job_order_id }}
                                                    </td>
                                                    <td>{{ $data->departure_date }}</td>
                                                    <td>{{ $data->arrival_date }}</td>
                                                    <td>{{ $data->origin }}</td>
                                                    <td>{{ $data->destination }}</td>
                                                    <td>{{ $data->recepient_name }}</td>
                                                    <td>
                                                        @if ($data->status == 1)
                                                            On - Progress
                                                        @elseif($data->status == 2)
                                                            End - Progress
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="g-2">
                                                            <a href="{{ route('operation.import.show', $data->id) }}"
                                                                class="btn text-purple btn-sm" data-bs-toggle="tooltip"
                                                                data-bs-original-title="Show"><span
                                                                    class="fe fe-eye fs-14"></span></a>
                                                            <a href="javascript:void(0)" id="btn-progress"
                                                                data-id="{{ $data->id }}" class="btn text-green btn-sm"
                                                                data-bs-toggle="tooltip"
                                                                data-bs-original-title="Update Progress"><span
                                                                    class="fe fe-file-text fs-14"></span></a>
                                                            <a href="{{ route('operation.import.edit', $data->id) }}"
                                                                class="btn text-warning btn-sm" data-bs-toggle="tooltip"
                                                                data-bs-original-title="Edit"><span
                                                                    class="fe fe-edit fs-14"></span></a>

                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <td colspan="12" align="center">
                                                    <span class="text-danger">
                                                        <strong>Data is Empty</strong>
                                                    </span>
                                                </td>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!-- CONTAINER CLOSED -->
        </div>
    </div>


    {{-- modal progress --}}
    <div class="modal fade" id="modal-progress" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Progress</h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('operation.import.update-progress') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div id="progress_before"></div>
                    </form>
                    <a href="javascript:void(0)" class="btn btn-default btn-block" id="addProgress">
                        <span><i class="fa fa-plus"></i></span> Add Progress
                    </a>
                    <form id="formCreateProgress" style="display: none"
                        action="{{ route('operation.import.store-progress') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="operation_import_id" id="operation_import_id">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label class="form-label">Tanggal</label>
                                            <input type="date" class="form-control" name="date_progress"
                                                id="date_progress" value="{{ old('date_progress') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Jam</label>
                                            <input type="time" class="form-control" name="time_progress"
                                                id="time_progress" value="{{ old('time_progress') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Lokasi Progress Selanjutnya</label>
                                            <input type="text" class="form-control mb-2" name="location"
                                                id="location" value="{{ old('location') }}" placeholder="Nama tempat">
                                            <input type="text" class="form-control mb-2" name="location_desc"
                                                id="location_desc" value="{{ old('location_desc') }}"
                                                placeholder="alamat lengkap tempat">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Transportasi</label>
                                            <select class="form-control select2 form-select" data-placeholder="Choose one"
                                                name="transportation">
                                                <option label="Choose one" selected></option>
                                                <option value="1" {{ old('transportation') == 1 ? 'selected' : '' }}>
                                                    Air Freight</option>
                                                <option value="2" {{ old('transportation') == 2 ? 'selected' : '' }}>
                                                    Sea Freight</option>
                                                <option value="3" {{ old('transportation') == 3 ? 'selected' : '' }}>
                                                    Land Trucking</option>
                                            </select>
                                            <div id="radio_buttons"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Carrier / Pengangkut</label>
                                            <input type="text" class="form-control" name="carrier" id="carrier"
                                                value="{{ old('carrier') }}" placeholder="fill the text..">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Keterangan Progress Selanjutnya</label>
                                            <input type="text" class="form-control" name="description"
                                                id="description" value="{{ old('description') }}"
                                                placeholder="fill the text..">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Document</label>
                                            <input type="file" class="form-control" name="documents[]" accept=".pdf"
                                                multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3" style="text-align: center">
                                <a class="btn btn-white color-grey" data-bs-dismiss="modal">Close</a>
                                <button type="submit" class="btn btn-primary">Save</button>
                                <button type="submit" class="btn btn-dark">Save & Notification</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-edit-progress" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Edit Progress</h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="progress_edit"></div>
                </div>
            </div>
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
        $(document).ready(function() {
            //show edit data
            $('body').on('click', '#btn-progress', function() {
                let id = $(this).data('id');

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        'id': id
                    },
                    url: '{{ route('operation.import.create-progress') }}',
                    success: function(response) {

                        $("#progress_before").html("");

                        $.each(response.data, function(key, item) {

                            var idProgress = item.id;
                            var itemTransportation = item.transportation;
                            var itemTransportationDesc = item.transportation_desc;
                            
                            var documentsContent = '';
                            $.each(item.documents, function(docKey, docItem) {
                                documentsContent += `
                                    <div class="d-flex gap-1" style="background-color: #E9E9F1; padding: 0.2rem; border-radius: 3px;">
                                    <a href="/storage/${docItem.document}" target="_blank" class="btn btn-info shadow-sm btn-sm">View</a>
                                    <button type="button" class="btn btn-danger shadow-sm btn-sm" onclick="deleteDocument(${docItem.id})" >Delete</button>
                                    </div>
                                `
                            })

                            let content = `<div class="row">
                                <input type="hidden" name="id_progress[]" value="${idProgress}">
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label class="form-label">Tanggal</label>
                                                <input type="date" class="form-control" name="e_date_progress[${idProgress}]" value="${item.date_progress}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-label">Jam</label>
                                                <input type="time" class="form-control" name="e_time_progress[${idProgress}]" value="${item.time_progress}">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Lokasi Progress ${key+2} </label>
                                                <input type="text" class="form-control mb-2" name="e_location[${idProgress}]" id="location" value="${item.location}" placeholder="Nama tempat">
                                                <input type="text" class="form-control mb-2" name="e_location_desc[${idProgress}]" value="${item.location_desc}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Transportasi</label>
                                                <select class="form-control select2 form-select transportation_change" name="e_transportation[${idProgress}]" data-itemTransportation="${itemTransportation}" data-itemTransportationDesc="${itemTransportationDesc}" data-placeholder="Choose one">
                                                    <option value="1" ${itemTransportation == 1 ? 'selected' : ''}>Air Freight</option>
                                                    <option value="2" ${itemTransportation == 2 ? 'selected' : ''}>Sea Freight</option>
                                                    <option value="3" ${itemTransportation == 3 ? 'selected' : ''}>Land Trucking</option>
                                                </select>
                                                
                                                <div class="transportationDesc1 ${itemTransportation == 1 ? '' : 'd-none'}">
                                                    <label class="custom-control custom-radio">
                                                    <input type="radio" class="custom-control-input"
                                                        name="e_transportation_desc[${idProgress}]" value="Hand Carry" ${itemTransportationDesc == 'Hand Carry' ? 'checked' : '' }>
                                                    <span class="custom-control-label">Hand Carry</span>
                                                    </label>
                                                    <label class="custom-control custom-radio">
                                                        <input type="radio" class="custom-control-input"
                                                            name="e_transportation_desc[${idProgress}]" value="Express" ${itemTransportationDesc == 'Express' ? 'checked' : '' }>
                                                        <span class="custom-control-label">Express</span>
                                                    </label>
                                                    <label class="custom-control custom-radio">
                                                        <input type="radio" class="custom-control-input"
                                                            name="e_transportation_desc[${idProgress}]" value="Regular" ${itemTransportationDesc == 'Regular' ? 'checked' : '' }>
                                                        <span class="custom-control-label">Regular</span>
                                                    </label>    
                                                </div>
                                        
                                                <div class="transportationDesc2 ${itemTransportation == 2 ? '' : 'd-none'}">
                                                    <label class="custom-control custom-radio">
                                                        <input type="radio" class="custom-control-input"
                                                            name="e_transportation_desc[${idProgress}]" value="FCL" ${itemTransportationDesc == 'FCL' ? 'checked' : '' }>
                                                        <span class="custom-control-label">FCL</span>
                                                    </label>
                                                    <label class="custom-control custom-radio">
                                                        <input type="radio" class="custom-control-input"
                                                            name="e_transportation_desc[${idProgress}]" value="LCL" ${itemTransportationDesc == 'LCL' ? 'checked' : '' }>
                                                        <span class="custom-control-label">LCL</span>
                                                    </label>    
                                                </div>

                                    </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Carrier / Pengangkut</label>
                                                <input type="text" class="form-control" name="e_carrier[${idProgress}]" value="${item.carrier}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
            
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Keterangan Progress ${key+2} </label>
                                                <input type="text" class="form-control" name="e_description[${idProgress}]" value="${item.description}">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Document</label>
                                                <input type="file" class="form-control" name="e_documents[${idProgress}][]" accept=".pdf" multiple>
                                                <div class="d-flex flex-wrap mt-3 gap-1 align-items-center">
                                                    ${documentsContent}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <div class="g-2">
                                            <form action="{{ route('operation.import.delete-progress', ':id_progress') }}" method="POST" class="d-inline" onclick="return confirm('Are you sure wants to delete this item');">
                                                @csrf
                                                @method('delete')
                                                <button class="btn text-danger">
                                                    <i class="fe fe-trash-2 fs-14"></i>
                                                </button>
                                            </form> 
                                            <a href="javascript:void(0)" class="badge bg-dark">Send Notification</a>
                                        </div>
                                    </div>
                                </div>
            
                            </div>`;

                            // Ganti placeholder ':id_progress' dengan nilai yang sebenarnya
                            content = content.replace(':id_progress', idProgress);
                            $('#progress_before').append(content);
                        });

                        if (response.data.length > 0) {
                            $('#progress_before').append(`
                            <div class="text-end">
                                <button class="btn btn-info mb-3">Update</button>
                            </div>
                            <div class="d-flex">
                                <div class="flex-fill border-top border-dark" style="border: 30px"></div>
                            </div>
                            <br>
                        `);
                        }


                        $('#operation_import_id').val(id);

                        $('#modal-progress').modal('show');
                    }
                });
            });

            //display form create progress when click button add progress
            $('#addProgress').on('click', function() {
                var addProgress = document.getElementById("addProgress");
                var formCreateProgress = document.getElementById("formCreateProgress");

                addProgress.style['display'] = 'none';
                formCreateProgress.style['display'] = 'block';

            });

            //redio button transportation
            $('select[name="transportation"]').change(function() {
                if (this.value == '1') {
                    $("#radio_buttons").html("");
                    var radioBtn = $(`<label class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input"
                                                name="transportation_desc" value="Hand Carry" {{ old('transportation_desc') == 'Hand Carry' ? 'checked' : '' }}>
                                            <span class="custom-control-label">Hand Carry</span>
                                        </label>
                                        <label class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input"
                                                name="transportation_desc" value="Express" {{ old('transportation_desc') == 'Express' ? 'checked' : '' }}>
                                            <span class="custom-control-label">Express</span>
                                        </label>
                                        <label class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input"
                                                name="transportation_desc" value="Regular" {{ old('transportation_desc') == 'Regular' ? 'checked' : '' }}>
                                            <span class="custom-control-label">Regular</span>
                                        </label>`);
                    radioBtn.appendTo('#radio_buttons');
                } else if (this.value == '2') {
                    $("#radio_buttons").html("");
                    var radioBtn = $(`<label class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input"
                                                name="transportation_desc" value="FCL" {{ old('transportation_desc') == 'FCL' ? 'checked' : '' }}>
                                            <span class="custom-control-label">FCL</span>
                                        </label>
                                        <label class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input"
                                                name="transportation_desc" value="LCL" {{ old('transportation_desc') == 'LCL' ? 'checked' : '' }}>
                                            <span class="custom-control-label">LCL</span>`);
                    radioBtn.appendTo('#radio_buttons');
                } else if (this.value == '3') {
                    $("#radio_buttons").html("");
                }
            });

            $('body').on('change', '.transportation_change', function() {
                let val = $(this).val()

                console.log($(this).next());

                if (val == '1') {
                    $(this).next().removeClass('d-none')
                    $(this).next().next().addClass('d-none')
                } else if (val == '2') {
                    $(this).next().next().removeClass('d-none')
                    $(this).next().addClass('d-none')
                } else {
                    $(this).next().addClass('d-none')
                    $(this).next().next().addClass('d-none')
                }
            });

        })

        function deleteDocument(id) {
            if(confirm('Are you sure want to delete this item?')) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'DELETE',
                    dataType: 'json',
                    url: "{{ route('operation.import.delete-progress-document', ':id') }}".replace(':id', id),
                    success: function () {
                        location.reload()
                    }
                })
            }
        }
    </script>
@endpush
