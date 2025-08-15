@extends('layouts.app')
@section('content')

<div class="main-content app-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <!-- PAGE-HEADER -->
            <div class="page-header">
                <h1>Marketing Windows</h1>
            </div>
            <!-- PAGE-HEADER END -->

            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="row">
                            <div class="col-3">
                                <div class="card-img-absolute circle-icon bg-primary text-center align-self-center box-primary-shadow bradius">
                                    <i class="lnr lnr-enter fs-30  text-white mt-4"></i>
                                </div>
                            </div>
                            <div class="col-9">
                                <div class="card-body p-4">
                                    <h2 class="mb-2 fw-normal mt-2">{{ $count }}</h2>
                                    <h5 class="fw-normal">Marketing Export</h5>
                                    <a href="{{ route('marketing.overview.index') }}" class="btn btn-primary-light btn-sm mb-0">View Less</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="d-flex d-inline">
                        <div class="col-auto">
                            <form action="{{ route('marketing.export.index') }}">
                                <div class="input-group">
                                    <div class="input-group-text"><i class="fe fe-search"></i></div>
                                    <input type="text" name="search" value="{{ $search }}" class="form-control" id="autoSizingInputGroup" placeholder="Searching.....">
                                </div>
                            </form>
                        </div>

                        <a href="javascript:void(0)" id="btn-filter" class="btn btn-light"><i class="fe fe-arrow-right me-2"></i>Filters</a>&nbsp;&nbsp;
                        <div class="dropdown">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                                <i class="fe fe-plus me-2"></i>Add
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ route('marketing.export.create') }}"><i class="fe fe-plus me-2"></i>New Marketing</a>
                                <a class="dropdown-item" href="{{ route('marketing.export.create-quotation') }}"><i class="fe fe-plus me-2"></i>New Quotation</a>
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
                                        <tr style="text-align: center;">
                                            <th>No</th>
                                            <th>Job Order ID</th>
                                            <th>Client Name</th>
                                            <th>No. PO</th>
                                            <th>No. CIPL</th>
                                            <th>Description</th>
                                            <th>Origin</th>
                                            <th>Destination</th>
                                            <th>Pick Up Address</th>
                                            <th>Delivery Address</th>
                                            <th>Quot No.</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($marketingExports as $data)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $data->job_order_id }}</td>
                                            <td>{{ $data->contact?->customer_name ?? 'undefined' }}</td>
                                            <td>{{ $data->no_po }}</td>
                                            <td>{{ $data->no_cipl }}</td>
                                            <td>{{ $data->description }}</td>
                                            <td>{{ $data->origin }}</td>
                                            <td>{{ $data->destination }}</td>
                                            <td>{{ $data->pickup_address }}</td>
                                            <td>{{ $data->delivery_address }}</td>
                                            <td>
                                                @if ($data->quotation)
                                                {{ $data->quotation->quotation_no }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($data->status == 1)
                                                    Hold
                                                @elseif($data->status == 2)
                                                    Selling
                                                @else
                                                    
                                                @endif
                                            </td>
                                            <td>
                                                <div class="g-2">
                                                    <a href="{{ route('marketing.export.show', $data->id) }}" class="btn text-purple btn-sm"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-original-title="Show"><span
                                                        class="fe fe-eye fs-14"></span></a>
                                                    <a href="{{ route('marketing.export.edit', $data->id) }}" class="btn text-warning btn-sm"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-original-title="Edit"><span
                                                                class="fe fe-edit fs-14"></span></a>
                                                    @if ($data->status == 1)
                                                        <a href="#" class="btn text-danger btn-sm"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-original-title="Delete" onclick="if (confirm('Are you sure want to delete this item?')) {
                                                                    event.preventDefault();
                                                                    document.getElementById('delete-{{$data->id}}').submit();
                                                                }else{
                                                                    event.preventDefault();
                                                                }">
                                                            <span class="fe fe-trash-2 fs-14"></span>
                                                        </a>

                                                        <form id="delete-{{$data->id}}" action="{{route('marketing.export.destroy',$data->id)}}" style="display: none;" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>      
                                                    @endif


                                                </div>
                                            </td>
                                        </tr>

                                        @empty
                                        <td colspan="13" align="center">
                                            <span class="text-danger">
                                                <strong>Data is Empty</strong>
                                            </span>
                                        </td>
                                        @endforelse
    
            
                                    </tbody>
                                </table>
                            </div>
                            {{  $marketingExports->appends(request()->input())->links()}}
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
                <form action="{{ route('marketing.export.index') }}">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <select class="form-control select2 form-select"
                                    data-placeholder="Choose one" name="filter_status">
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
                                    data-placeholder="Choose one" name="filter_origin">
                                    @php
                                        // Array untuk menyimpan data yang sudah ditampilkan
                                        $displayedFromItems = [];
                                    @endphp
                                    @foreach ($filterData as $from)
                                        @if (!in_array($from->origin, $displayedFromItems)) 
                                            <option label="Choose one" selected disabled></option>
                                            <option value="{{ $from->origin }}" {{ $filterOrigin == $from->origin ? "selected" : "" }}>{{ $from->origin }}</option>
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
                                    data-placeholder="Choose one" name="filter_destination">
                                    @php
                                        // Array untuk menyimpan data yang sudah ditampilkan
                                        $displayedToItems = [];
                                    @endphp
                                    @foreach ($filterData as $to)
                                        @if (!in_array($to->destination, $displayedToItems)) 
                                            <option label="Choose one" selected disabled></option>
                                            <option value="{{ $to->destination }}" {{ $filterDestination == $to->destination ? "selected" : "" }}>{{ $to->destination }}</option>
                                            @php
                                                $displayedToItems[] = $to->destination;
                                            @endphp
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mt-3" style="text-align: right">
                            <a href="{{ route('marketing.export.index') }}" class="btn btn-white color-grey">Reset</a>
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