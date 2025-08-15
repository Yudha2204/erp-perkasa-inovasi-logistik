@extends('layouts.app')
@section('content')

<div class="main-content app-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <!-- PAGE-HEADER -->
            <div class="page-header mb-0">
                <h1>Operation</h1>
            </div>
            <!-- PAGE-HEADER END -->
            <h3>Report</h3>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="d-flex d-inline">
                        <form action="{{ route('operation.report.index') }}">
                            <div class="input-group">
                                <div class="input-group-text"><i class="fe fe-search"></i></div>
                                <input type="text" name="search" value="{{ $search }}" class="form-control" id="autoSizingInputGroup" placeholder="Searching.....">
                            </div>
                        </form>&nbsp;&nbsp;
                        <div class="dropdown">
                            <button type="button" class="btn" data-bs-toggle="dropdown" style="background-color:#fcebda; color: #f4a98e"><i class="fe fe-plus me-2"></i>Export</button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:void(0)" id="export-internal">Export Internal</a>
                                <a class="dropdown-item" href="javascript:void(0)" id="export-external">Export External</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive mb-3">
                                <table class="table text-nowrap text-md-nowrap mb-0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>Kategori</th>
                                            <th>Domestik /<br>International</th>
                                            <th>Customer</th>
                                            <th>Asal</th>
                                            <th>Tujuan</th>
                                            <th>Dimensi</th>
                                            <th>VOL/CBM</th>
                                            <th>Berat Kg</th>
                                            <th>Plt/Cly/Pkgs</th>
                                            <th>Vendor</th>
                                            <th>Keterangan</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $i = 1;
                                        @endphp
                                        @forelse ($paginator as $item)
                                        <tr>
                                            <td>{{ ($paginator->currentPage() - 1)  * $paginator->links()->paginator->perPage() + $loop->iteration }}</td>
                                            <td>{{ $item->pickup_date }}</td>
                                            <td>{{ $item->marketing->source }}</td>
                                            <td>
                                                @if ($item->marketing->expedition == 1)
                                                    Domestik
                                                @else
                                                    International
                                                @endif
                                                <br>
                                                @if ($item->transportation == 1)
                                                    Air Freight
                                                @elseif ($item->transportation == 2)
                                                    Sea Freight
                                                @else 
                                                    Land Trucking
                                                @endif
                                                -
                                                {{ $item->transportation_desc }}
                                            </td>
                                            <td>{{ $item->marketing->contact->customer_name }}</td>
                                            <td>{{ $item->origin }}</td>
                                            <td>{{ $item->destination }}</td>
                                            <td>
                                                @if ($item->marketing->dimensions)
                                                    @foreach ($item->marketing->dimensions as $dimension)
                                                    {{ $dimension->length }} {{ $dimension->width }} {{ $dimension->height }} <br>
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td>@if ($item->marketing->total_volume)
                                                    {{ $item->marketing->total_volume }} {{ $item->marketing->freetext_volume }} 
                                                @endif 
                                            </td>
                                            <td>{{ $item->marketing->total_weight }}</td>
                                            <td>
                                                @if ($item->marketing->dimensions)
                                                    @foreach ($item->marketing->dimensions as $dimension)
                                                    {{ $dimension->packages }} <br>
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $vendorNames = [];
                                                    foreach($item->vendors as $vendor) {
                                                        $vendorFinance = $vendor->getVendorFinance();
                                                        if ($vendorFinance && isset($vendorFinance->vendor)) {
                                                            $vendorNames[] = $vendorFinance->vendor->customer_name;
                                                        }
                                                    }
                                                    $uniqueVendorNames = array_unique($vendorNames);
                                                @endphp
                                                {{ implode(', ', $uniqueVendorNames) }}
                                            </td>
                                            <td>{{ $item->marketing->description }}</td>
                                            <td> @if ($item->marketing->source == "export")
                                                    <div class="g-2">
                                                        <a href="{{ route('operation.export.show', $item->id) }}" class="btn text-purple btn-sm"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-original-title="Show"><span
                                                            class="fe fe-eye fs-14"></span></a>
                                                    </div>
                                                @else
                                                    <div class="g-2">
                                                        <a href="{{ route('operation.import.show', $item->id) }}" class="btn text-purple btn-sm"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-original-title="Show"><span
                                                            class="fe fe-eye fs-14"></span></a>
                                                    </div>
                                                @endif
                                            </td>
                                            @empty
                                            <td colspan="14" align="center">
                                                <span class="text-danger">
                                                    <strong>Data is Empty</strong>
                                                </span>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            {{ $paginator->links() }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- CONTAINER CLOSED -->
    </div>
</div>

{{-- modal export internal --}}
<div class="modal fade" id="modal-export-internal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Internal PDF</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('operation.report.export-pdf') }}">
                    <input type="hidden" name="type" value="internal">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="form-label">Tanggal</label>
                                <input type="date" class="form-control" name="date_from" value="{{ old('date_from') }}">
                            </div>
                        </div>
                        <div class="col-md-1" style="margin-top: 40px">
                            <i>s/d</i>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="form-label">Tanggal</label>
                                <input type="date" class="form-control" name="date_to" value="{{ old('date_to') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Domestik/International</label>
                                <select class="form-control select2 form-select"
                                    data-placeholder="All" name="expedition">
                                    <option value="0" selected>All</option>
                                    <option value="1">Domestik</option>
                                    <option value="2">International</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Transportation</label>
                                <select class="form-control select2 form-select"
                                    data-placeholder="All" name="transportation">
                                    <option value="0" selected>All</option>
                                    <option value="1">Air Freight</option>
                                    <option value="2">Sea Freight</option>
                                    <option value="3">Land Trucking</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3" style="text-align: right">
                        <a class="btn btn-white color-grey" data-bs-dismiss="modal">Close</a>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- modal export external --}}
<div class="modal fade" id="modal-export-external" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export External PDF</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('operation.report.export-pdf') }}">
                    <input type="hidden" name="type" value="external">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Customer</label>
                                <select class="form-control select2 form-select"
                                    data-placeholder="Choose one" name="customer">
                                    @php
                                    // Array untuk menyimpan data yang sudah ditampilkan
                                    $displayedFromItems = [];
                                    @endphp
                                    @foreach ($mergedData as $customer)
                                        @if (!in_array($customer->marketing->contact->customer_name, $displayedFromItems)) 
                                            <option value="{{ $customer->marketing->contact->customer_name }}">{{ $customer->marketing->contact->customer_name }}</option>
                                            @php
                                                $displayedFromItems[] = $customer->marketing->contact->customer_name;
                                            @endphp
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="form-label">Tanggal</label>
                                <input type="date" class="form-control" name="date_from" value="{{ old('date_from') }}">
                            </div>
                        </div>
                        <div class="col-md-1" style="margin-top: 40px">
                            <i>s/d</i>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label class="form-label">Tanggal</label>
                                <input type="date" class="form-control" name="date_to" value="{{ old('date_to') }}">
                            </div>
                        </div>
                    </div>
                    <div class="mt-3" style="text-align: right">
                        <a class="btn btn-white color-grey" data-bs-dismiss="modal">Close</a>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
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
    
        $('body').on('click', '#export-internal', function() {
            $('#modal-export-internal').modal('show');
        });

        $('body').on('click', '#export-external', function() {
            $('#modal-export-external').modal('show');
        });

    })  
</script>  

@endpush