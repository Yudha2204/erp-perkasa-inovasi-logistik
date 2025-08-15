@extends('layouts.app')
@section('content')

<div class="main-content app-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">
            <!-- PAGE-HEADER -->
            <div class="page-header mb-0">
                <h1>Marketing</h1>
            </div>
            <h4>Detail Marketing Import</h4>
            <!-- PAGE-HEADER END -->

            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                 
                        <div class="row">
                            <div class="col-10 mx-auto form-group">
                                <label class="form-label">Domestik / International</label>
                                <select name="expedition" required class="form-control select2 form-select" data-bs-placeholder="Select Country" disabled>
                                    <option value="domestik" {{ $data->expedition == 1 ? 'selected' : '' }}>Domestik</option>
                                    <option value="international" {{ $data->expedition == 2 ? 'selected' : '' }}>International</option>
                                </select>
                            </div>
                            <div class="col-10 mx-auto form-group">
                                <label class="form-label">Transportation</label>
                                <select class="form-control select2 form-select"
                                    data-placeholder="Choose one" name="transportation" disabled>
                                    <option value="Air Freight" {{ $data->transportation == 1 ? 'selected' : '' }}>Air Freight</option>
                                    <option value="Sea Freight" {{ $data->transportation == 2 ? 'selected' : '' }}>Sea Freight</option>
                                    <option value="Land Trucking" {{ $data->transportation == 3 ? 'selected' : '' }}>Land Trucking</option>
                                </select>
                            </div>
                            <div class="col-10 mx-auto form-group">
                                <div class="custom-controls-stacked">
                                    @if ($data->transportation_desc == "Hand Carry")
                                        <label class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input"
                                                name="transport_desc" value="Express" checked>
                                            <span class="custom-control-label">Hand Carry</span>
                                        </label>
                                    @elseif ($data->transportation_desc == "Express")
                                        <label class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input"
                                                name="transport_desc" value="Express" checked>
                                            <span class="custom-control-label">Express</span>
                                        </label>
                                    @elseif ($data->transportation_desc == "Regular")
                                        <label class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input"
                                                name="transport_desc" value="Express" checked>
                                            <span class="custom-control-label">Regular</span>
                                        </label>
                                    @elseif ($data->transportation_desc == "FCL")
                                        <label class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input"
                                                name="transport_desc" value="Express" checked>
                                            <span class="custom-control-label">FCL</span>
                                        </label>
                                    @elseif ($data->transportation_desc == "LCL")
                                        <label class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input"
                                                name="transport_desc" value="Express" checked>
                                            <span class="custom-control-label">LCL</span>
                                        </label>
                                    @endif
                                    <div id="radio_buttons"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="row mb-5">
                            <div class="col-6 mx-3">
                                <div class="form-group">
                                    <label class="form-label">Client Name</label>
                                    <input type="text" class="form-control" name="client_name" id="client_name" value="{{ $data->contact->customer_name }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">No. PO</label>
                                    <input type="text" class="form-control" name="no_po" id="no_po" value="{{ $data->no_po }}" readonly>
                                </div>
                            </div>
                            <div class="col-5">
                                <div class="form-group">
                                    <label for="" class="form-label">Commodity/Description</label>
                                    <textarea class="form-control mb-4" placeholder="Description" rows="5" name="description" readonly>{{ $data->no_po }}</textarea>
                                </div>
                            </div>
                            <div class="col-12 mx-3">
                                @if ($data->quotation)
                                    <a href="{{ route('marketing.import.show-quotation', $data->id) }}" target="_blank" class="btn btn-primary btn-sm"><i class="ti ti-eye"></i></a>&nbsp;&nbsp;Show Quotation
                                @else
                                    <p class="mb-0">Quotation is empty, do you want to create it?</p>
                                    <form action="{{ route('marketing.import.create-quotation') }}">
                                        <input type="hidden" name="marketing_import_id" value="{{ $data->id }}">
                                        <input type="submit" value="Create Quotation" class="btn btn-primary btn-sm">
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">No. CIPL</label>
                                        <input type="text" class="form-control" name="no_cipl" id="department"   value="{{ $data->no_cipl }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Weight (Kg)</label>
                                        <input type="text" class="form-control" name="weight" id="department"   value="{{ $data->total_weight }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        @if ($data->transportation == 1 || $data->transportation == 3)
                                        <label class="form-label">Volume M<sup>2</sup></label>
                                        @else
                                        <label class="form-label">Volume M<sup>3</sup></label>
                                        @endif
                                        <input type="text" class="form-control" name="volume" id="department" value="{{ $data->total_volume }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="" class="form-label" style="color: white">.</label>
                                        <input type="text" class="form-control" name="freetext_volume" value="{{ $data->freetext_volume }}" id="freetext_volume" readonly>
                                    </div>
                                </div>
                            </div>
                                
                            <div class="row my-5">

                                <h3 class="card-title text-center dimension-label">Dimension</h3>
                                <div class="table-responsive">
                                    <table class="table table-bordered text-nowrap w-full datetimetable" id="dimensionTable"
                                        border="0" cellspacing="5" cellpadding="5">
                                        <thead>
                                            <tr class="form-control-sm">
                                                <td class="text-center">Packages</td>
                                                <td class="text-center">Panjang</td>
                                                <td class="text-center">Lebar</td>
                                                <td class="text-center">Tinggi</td>
                                                <td class="text-center"></td>
                                                <td class="text-center">Quantity</td>
                                                <td class="text-center">Total</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data->dimensions as $dimension)
                                            <tr class="form-control-sm theDimensions`+ increaseNumDimensions + `">
                                                <td><input type="text" value="{{ $dimension->packages }}" style="width:60px; border: 0px" readonly>
                                                </td>
                                                <td><input type="text" value="{{ $dimension->length }}" style="width:60px; border: 0px"  readonly>
                                                </td>
                                                <td><input type="text" value="{{ $dimension->width }}"style="width:60px; border: 0px"  readonly>
                                                </td>
                                                <td><input type="text" value="{{ $dimension->height }}" style="width:60px; border: 0px" readonly>
                                                </td>
                                                <td><input type="text"value="{{ $dimension->input_measure }}" style="width:60px; border: 0px" readonly>
                                                </td>
                                                <td><input type="text" value="{{ $dimension->qty }}" style="width:60px; border: 0px" readonly>
                                                </td>
                                                @php
                                                $heightDivInput_measure = $dimension->input_measure == 0 ? 0 : $dimension->height / $dimension->input_measure;
                                                $total = $dimension->length * $dimension->width * $heightDivInput_measure * $dimension->qty;
                                        
                                                // Memeriksa apakah ada koma dalam hasil perhitungan
                                                if (strpos($total, '.') !== false) {
                                                    $totalFinal = sprintf("%.3f", $total); // Membatasi menjadi 3 angka dibelakang koma
                                                } else {
                                                    $totalFinal = $total; // Tidak ada koma, tidak perlu melakukan apapun
                                                }

                                                @endphp
                                                <td><input type="text" value="{{ $totalFinal }}" style="width:60px; border: 0px" readonly>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div
                                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-1 border-bottom">
                                </div>
                          
                            </div>
                            
                            <div class="row">        
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Origin</label>
                                        <input type="text" class="form-control" name="origin" id="origin" value="{{ $data->origin }}"   readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Destination</label>
                                        <input type="text" class="form-control" name="destination" id="destination" value="{{ $data->destination }}"    readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Shipper</label>
                                        <input type="text" class="form-control" name="shipper" id="shipper" value="{{ $data->shipper }}"   readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Consignee</label>
                                        <input type="text" class="form-control" name="consignee" id="consignee" value="{{ $data->consignee }}"    readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Pickup Address</label>
                                        <input type="text" class="form-control" name="pickup_address" id="pickup_address" value="{{ $data->pickup_address }}"    readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">Delivery Address</label>
                                        <input type="text" class="form-control" name="delivery_address" id="delivery_address" value="{{ $data->delivery_address }}"    readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom"></div>
                            <div class="row mt-3">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Document</label>
                                        @if ($data->documents->count() > 0)
                                            @foreach ($data->documents as $file)
                                            <a href="{{  Storage::url($file->document)  }}" target="_blank" class="btn btn-info shadow-sm btn-sm">View File</a>
                                            @endforeach
                                        @else
                                            <p>there are no documents here</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Remark</label>
                                        <textarea class="form-control mb-4" name="remark" placeholder="Textarea"
                                            rows="4" readonly>{{ $data->remark }}</textarea>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Status</label>
                                        <div class="custom-controls-stacked">
                                            <label class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" name="status"
                                                    value="hold" {{ $data->status == '1' ? 'checked' : '' }} disabled>
                                                <span class="custom-control-label">Hold</span>
                                            </label>
                                            <label class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" name="status"
                                                    value="selling" {{ $data->status == '2' ? 'checked' : '' }} disabled>
                                                <span class="custom-control-label">Selling</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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