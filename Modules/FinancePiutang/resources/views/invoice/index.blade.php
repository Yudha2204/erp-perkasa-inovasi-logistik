@extends('layouts.app')
@section('content')

    <div class="main-content app-content mt-0">
        <div class="side-app">

            <!-- CONTAINER -->
            <div class="main-container container-fluid">

                <!-- PAGE-HEADER -->
                <div class="page-header">
                    <h1>Invoice</h1>
                </div>
                <!-- PAGE-HEADER END -->

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="d-flex d-inline">
                            <form action="">
                                <input type="text" name="search" value="{{ Request::get('search') }}"  class="form-control" placeholder="Searching.....">
                            </form>
                            &nbsp;&nbsp;<a class="btn btn-primary" href="{{ route('finance.piutang.invoice.create') }}"><i class="fe fe-plus me-2"></i>Add New</a>&nbsp;&nbsp;
                            <!-- <button type="button" class="btn btn-light"><img src="{{ url('assets/images/icon/filter.png') }}" alt=""></button> -->
                        </div>
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
                                <div class="table-responsive mb-2">
                                    <table class="table text-nowrap text-md-nowrap mb-0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Date</th>
                                                <th>Invoice No</th>
                                                <th>Customer Name</th>
                                                <th>Description</th>
                                                <th>Value</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($invoice as $key => $inv)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $inv->date_invoice)->format('d/m/Y') }}</td>
                                                <td>{{ $inv->transaction }}</td>
                                                <td>
                                                    @if(isset($inv->contact))
                                                    {{ $inv->contact->customer_name }}
                                                    @endif
                                                </td>
                                                <td>{{ $inv->description }}</td>
                                                <td>
                                                    {{ $inv->currency->initial }}
                                                    @if($inv->dp > 0 && $inv->status !== 'paid')
                                                    {{ number_format($inv->total-$inv->dp, 2, '.', ',')  }}
                                                    @else
                                                    {{ number_format($inv->total, 2, '.', ',')  }}
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge
                                                        @if ($inv->status === 'open') bg-success
                                                        @elseif ($inv->status === 'due date') bg-warning
                                                        @elseif ($inv->status === 'over due') bg-danger
                                                        @elseif ($inv->status === 'paid') bg-primary
                                                        @endif"
                                                    >
                                                        {{ ucfirst($inv->status) }}
                                                    </span>
                                                    @if($inv->dp > 0 && $inv->status !== 'paid')
                                                    <span class="badge bg-info">
                                                        DP
                                                    </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="dropdown" style="position: absolute; display: inline-block;">
                                                        <a href="javascript:void(0)" data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fe fe-more-vertical"></i></a>
                                                        <div class="dropdown-menu" style="min-width: 7rem; z-index: 999999999;">
                                                            <a href="{{ route('finance.piutang.invoice.show', $inv->id) }}" class="btn text-purple btn-sm dropdown-item"><span class="fe fe-eye fs-14"></span> Detail</a>
                                                            <a href="{{ route('finance.piutang.invoice.edit', $inv->id) }}" class="btn text-warning btn-sm dropdown-item"><span class="fe fe-edit fs-14"></span> Edit</a>
                                                            <a href="{{ route('finance.piutang.invoice.jurnal', $inv->id) }}" class="btn text-success btn-sm dropdown-item">
                                                            <i class="fe fe-edit fs-14"></i> Journal
                                                            </a>

                                                            <!-- delete -->
                                                            <form action="{{ route('finance.piutang.invoice.destroy', $inv->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn text-danger btn-sm dropdown-item" onclick="return confirmDelete()"><span class="fe fe-trash fs-14"></span> Delete</button>
                                                            </form>       

                                                            <a class="btn text-success btn-sm dropdown-item" data-bs-toggle="modal" href="#modal-pdf-{{ $inv->id }}" modal-data-id="{{ $inv->id }}" data-id="{{ $inv->sales->marketing->job_order_id ?? "" }}">
                                                            <span class="fe fe-check fs-14"></span> Print
                                                            </a>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
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

    @foreach($invoice as $key => $inv)
    {{-- modal --}}
    <div class="modal fade" id="modal-pdf-{{ $inv->id }}" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Format Print Invoice</h5>
                    <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('finance.piutang.invoice.pdf', $inv->id) }}" method="POST" enctype="multipart/form-data" name="dynamic-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group d-flex flex-column gap-2">
                                    <input hidden type="text" class="form-control" name="invoice_id" id="invoice_id" value="{{ $inv->id }}">
                                    <div class="">
                                        <label for="shipper">Shipper</label>
                                        <input type="text" class="form-control" name="shipper" id="shipper" value="{{ $inv->sales->marketing->shipper ?? "" }}">
                                    </div>
                                    <div class="">
                                        <label for="consignee">Consignee</label>
                                        <input type="text" class="form-control" name="consignee" id="consignee" value="{{ $inv->sales->marketing->consignee ?? "" }}">
                                    </div>
                                    <div class="">
                                        <label for="comodity">Comodity</label>
                                        <input type="text" class="form-control" name="comodity" id="comodity" value="{{ $inv->sales->marketing->description ?? "" }}">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="mbl">MBL</label>
                                            <input type="text" class="form-control" name="mbl" id="mbl" placeholder="0" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="hbl">HBL</label>
                                            <input type="text" class="form-control" name="hbl" id="hbl" placeholder="0" required>
                                        </div>
                                    </div>
                                    <div class="">
                                        @php
                                            $voyage = '';
                                            $transportation = '';
    
                                            if ($inv->sales && $inv->sales->marketing) {
                                                $transportation = 'Land Trucking'; 
                                                if ($inv->sales->marketing->transportation === 1) {
                                                    $transportation = 'Air Freight';
                                                } else if ($inv->sales->marketing->transportation === 2) {
                                                    $transportation = 'Sea Freight';
                                                }
                                                $voyage = $transportation . " - " . $inv->sales->marketing->transportation_desc;
                                            }
                                        @endphp
                                        <label for="voyage">Voyage</label>
                                        <input type="text" class="form-control" name="voyage" id="voyage" value="{{ $voyage }}">
                                    </div>
                                    <div class="">
                                        <label for="chargetableWeight">Chargetable Weight</label>
                                        <input type="text" class="form-control" name="chargetableWeight" placeholder="0" id="chargetableWeight" required>
                                    </div>
                                    <div class="">
                                        <label for="invoice_date">Invoice Date</label>
                                        <input type="date" class="form-control" name="invoice_date" id="invoice_date" value="{{ $inv->date_invoice }}">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="depart_date">Depart Date</label>
                                            <input type="date" class="form-control" name="depart_date" id="depart_date">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="origin">Origin / Destination</label>
                                            <input type="text" class="form-control" name="origin" id="origin" value="{{ $inv->sales->marketing->origin ?? "Origin" }} / {{ $inv->sales->marketing->destination ?? "Destination" }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="weight">Weight</label>
                                            <input type="text" class="form-control" name="weight" id="weight" value="{{ $inv->sales->marketing->total_weight ?? 0 }}">
                                        </div>
                                        <div class="col-md-6 d-flex gap-2">                                           
                                            <div class="">
                                                @php
                                                    $volumetrik = "-";
                                                    $m3 = "-";
                                                    if($inv->sales) {
                                                        if($inv->sales->marketing && $inv->sales->marketing->freetext_volume === "M3") {
                                                            $m3 = $inv->sales->marketing->total_volume;
                                                        } else if($inv->sales->marketing && $inv->sales->marketing->freetext_volume !== "M3") {
                                                            $volumetrik = $inv->sales->marketing->total_volume;
                                                        }
                                                    }
                                                @endphp
                                                <label for="volumetrik">Volumetrik</label>
                                                <input type="text" class="form-control" name="volumetrik" id="volumetrik" value="{{ $volumetrik }}">
                                            </div>
                                            <h4 class="mt-6">/</h4>
                                            <div class="">
                                                <label for="m3">M3</label>
                                                <input type="text" class="form-control" name="m3" id="m3" value="{{ $m3 }}">
                                            </div>
                                        </div>
                                    </div>

                                    <h5 class="modal-title mt-5">Foot Note</h5>
                                    <label for="shipper">Currency</label>
                                    <div class="w-full flex items-center" style="display: flex; gap: 10px;">
                                        @foreach($bank as $b)
                                        <div class="d-flex items-center">
                                            <input type="checkbox" name="currencies[]" value="{{ $b->id }}">{{ $b->currency->initial }}
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                <br>
                                <div class="mt-3" style="text-align: right">
                                    <a class="btn btn-white color-grey" data-bs-dismiss="modal">Close</a>
                                    <button type="submit" class="btn btn-primary ms-4">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach

@endsection
@push('scripts')
<script>
    function confirmDelete() {
        if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
            return true;
        } else {
            return false;
        }
    }
</script>
@endpush
