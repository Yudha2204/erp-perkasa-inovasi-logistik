@extends('layouts.app')
@section('content')
@push('styles')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endpush

<div class="main-content app-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <!-- PAGE-HEADER -->
            <div class="page-header">
                <h1>Sales Order</h1>
            </div>
            <!-- PAGE-HEADER END -->

            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="d-flex d-inline">
                        <form action="">
                            <input type="text" name="search" value="{{ Request::get('search') }}" class="form-control" placeholder="Searching.....">
                        </form>
                        &nbsp;&nbsp;<a class="btn btn-primary" href="{{ route('finance.piutang.sales-order.create') }}"><i class="fe fe-plus me-2"></i>Add New</a>&nbsp;&nbsp;
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
                                            <th>No Transaksi</th>
                                            <th>Customer Name</th>
                                            <th>Description</th>
                                            <th>Value</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($sales_order as $key => $so)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $so->date)->format('d/m/Y') }}</td>
                                                <td>{{ $so->transaction }}</td>
                                                <td>
                                                    @if(isset($so->contact))
                                                    {{ $so->contact->customer_name }}
                                                    @endif
                                                </td>
                                                <td>{{ $so->description }}</td>
                                                <td>
                                                    @if(isset($so->currency))
                                                    {{ $so->currency->initial }}    
                                                    @endif
                                                    {{ number_format($so->total, 2, '.', ',') }}
                                                </td>
                                                <td>
                                                    <div class="dropdown" style="position: absolute;">
                                                        <a href="javascript:void(0)" data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fe fe-more-vertical"></i></a>
                                                        <div class="dropdown-menu" style="z-index: 99;">
                                                            <a href="{{ route('finance.piutang.sales-order.show', $so->id) }}" class="btn text-purple btn-sm dropdown-item"><i class="fe fe-eye fs-14"></i> Detail</a>
                                                            <a href="{{ route('finance.piutang.sales-order.edit', $so->id) }}" class="btn text-warning btn-sm dropdown-item"><i class="fe fe-edit fs-14"></i> Edit</a>
                                                            <form action="{{ route('finance.piutang.sales-order.destroy', $so->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn text-danger btn-sm dropdown-item" onclick="return confirmDelete()"><i class="fe fe-trash fs-14"></i> Delete</button>
                                                            </form>  
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
@endsection
@push('scripts')
<script>
    function confirmDelete() {
        if (confirm('Are you sure want to delete this item?')) {
            return true;
        } else {
            return false;
        }
    }
</script>
@endpush
