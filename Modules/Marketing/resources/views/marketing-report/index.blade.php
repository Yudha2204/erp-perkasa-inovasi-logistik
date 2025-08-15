@extends('layouts.app')
@section('content')

<div class="main-content app-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <!-- PAGE-HEADER -->
            <div class="page-header mb-2">
                <h1>Report Marketing</h1>
            </div>
            <!-- PAGE-HEADER END -->

            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="d-flex d-inline">

                        <a href="javascript:void(0)" id="btn-filter" class="btn btn-light"><i class="fe fe-arrow-right me-2"></i>Filters</a>&nbsp;&nbsp;
                        <form action="{{ route('marketing.report.export-pdf') }}">
                            <input type="hidden" name="status" value="{{ $filterStatus }}">
                            <input type="hidden" name="from" value="{{ $filterFrom }}">
                            <input type="hidden" name="to" value="{{ $filterTo }}">
                            <button type="submit" class="btn" style="background-color:#fcebda; color: #f4a98e"><i class="fe fe-plus me-2"></i>Export</button>&nbsp;&nbsp;
                        </form>
                   
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
                                            <th>Kategori</th>
                                            <th>Customer Name</th>
                                            <th>No INV</th>
                                            <th>Commodity/Description</th>
                                            <th>Selling</th>
                                            <th>Cost</th>
                                            <th>Profit</th>
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
                                                <td>{{ $item->source }}</td>
                                                <td>{{ $item->contact->customer_name }}</td>
                                                <td>{{ $item->no_cipl }}</td>
                                                <td>{{ $item->description }}</td>
                                                <td>
                                                    @if ($item->quotation)
                                                    {{ $item->quotation->currency->initial }} 
                                                        @if($item->getDataCalculate()["isInvoice"] === false)
                                                        {{ number_format($item->quotation->sales_value, 2, '.',',') }}
                                                        @else
                                                        {{ number_format($item->getDataCalculate()["selling"], 2, '.',',') }}
                                                        @endif
                                                    @else
                                                    
                                                    @endif
                                                </td>
                                                <td>{{ number_format($item->getDataCalculate()["cost"],2,'.',',') }}</td>
                                                <td>{{ number_format($item->getDataCalculate()["profit"],2,'.',',') }}</td>
                                                <td>
                                                    @if ($item->source == "export")
                                                        <div class="g-2">
                                                            <a href="{{ route('marketing.export.show', $item->id) }}" class="btn text-purple btn-sm"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-original-title="Show"><span
                                                                class="fe fe-eye fs-14"></span></a>
                                                        </div>
                                                    @else
                                                        <div class="g-2">
                                                            <a href="{{ route('marketing.import.show', $item->id) }}" class="btn text-purple btn-sm"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-original-title="Show"><span
                                                                class="fe fe-eye fs-14"></span></a>
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                        <td colspan="9" align="center">
                                            <span class="text-danger">
                                                <strong>Data is Empty</strong>
                                            </span>
                                        </td>
                                        @endforelse
            
                                    </tbody>
                                </table>
                            </div>
                            {{-- {{  $mergedData->appends(request()->input())->links()}} --}}
                            {{ $paginator->links() }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- CONTAINER CLOSED -->
    </div>
</div>


{{-- modal filter --}}
<div class="modal fade" id="modal-filter" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filter</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('marketing.report.index') }}">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select class="form-control select2 form-select"
                                    data-placeholder="Choose one" name="status">
                                    <option label="Choose one" selected disabled></option>
                                    <option value="1" {{ $filterStatus == 1 ? "selected" : "" }}>Hold</option>
                                    <option value="2" {{ $filterStatus == 2 ? "selected" : "" }}>Selling</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">From</label>
                                <select class="form-control select2 form-select"
                                    data-placeholder="Choose one" name="from">
                                    <option label="Choose one" selected disabled></option>
                                    @php
                                        // Array untuk menyimpan data yang sudah ditampilkan
                                        $displayedFromItems = [];
                                    @endphp
                                    @foreach ($mergedDataFilter as $from)
                                        @if (!in_array($from->origin, $displayedFromItems)) 
                                            <option value="{{ $from->origin }}" {{ $filterFrom == $from->origin ? "selected" : "" }}>{{ $from->origin }}</option>
                                            @php
                                                $displayedFromItems[] = $from->origin;
                                            @endphp
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">To</label>
                                <select class="form-control select2 form-select"
                                    data-placeholder="Choose one" name="to">
                                    <option label="Choose one" selected disabled></option>
                                    @php
                                        // Array untuk menyimpan data yang sudah ditampilkan
                                        $displayedToItems = [];
                                    @endphp
                                    @foreach ($mergedDataFilter as $to)
                                        @if (!in_array($to->destination, $displayedToItems)) 
                                            <option value="{{ $to->destination }}" {{ $filterTo == $to->destination ? "selected" : "" }}>{{ $to->destination }}</option>
                                            @php
                                                $displayedToItems[] = $to->destination;
                                            @endphp
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mt-3" style="text-align: right">
                            <a href="{{ route('marketing.report.index') }}" class="btn btn-white color-grey">Reset</a>
                            <button type="submit" class="btn btn-primary">Search</button>
                        </div>
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
    
        $('body').on('click', '#btn-filter', function() {
            $('#modal-filter').modal('show');
        });



    })  
</script>  

@endpush