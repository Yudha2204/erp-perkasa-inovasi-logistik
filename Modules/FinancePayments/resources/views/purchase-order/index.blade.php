@extends('layouts.app')
@section('content')

    <div class="main-content app-content mt-0">
        <div class="side-app">

            <!-- CONTAINER -->
            <div class="main-container container-fluid">

                <!-- PAGE-HEADER -->
                <div class="page-header">
                    <h1>Account Payable</h1>
                </div>
                <!-- PAGE-HEADER END -->

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="d-flex d-inline">
                            <form action="">
                                <input type="text" name="search" value="{{ Request::get('search') }}"  class="form-control" placeholder="Searching.....">
                            </form>
                            &nbsp;&nbsp;<a class="btn btn-primary" href="{{ route('finance.payments.account-payable.create') }}"><i
                                    class="fe fe-plus me-2"></i>Add New</a>&nbsp;&nbsp;
                            <!-- <button type="button" class="btn btn-light"><img
                                    src="{{ url('assets/images/icon/filter.png') }}" alt=""></button> -->
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
                                                <th>Invoice No.</th>
                                                <th>Vendor Name</th>
                                                <th>Description</th>
                                                <th>Value</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($head as $data)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $data->date_order)->format('d/m/Y') }}</td>
                                                <td>{{ $data->transaction }}</td>
                                                <td>{{ isset($data->vendor) ? $data->vendor->customer_name : "" }}</td>
                                                <td>{{ $data->description }}</td>
                                                <td>{{ isset($data->currency) ? $data->currency->initial : "" }} 
                                                    @if($data->dp > 0 && $data->status !== 'paid')
                                                    {{ number_format($data->total-$data->dp, 2, '.', ',')  }}
                                                    @else
                                                    {{ number_format($data->total, 2, '.', ',')  }}
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge
                                                        @if ($data->status === 'open') bg-success
                                                        @elseif ($data->status === 'due date') bg-warning
                                                        @elseif ($data->status === 'over due') bg-danger
                                                        @elseif ($data->status === 'paid') bg-primary
                                                        @endif"
                                                    >
                                                        {{ ucfirst($data->status) }}
                                                    </span>
                                                    @if($data->dp > 0 && $data->status !== 'paid')
                                                    <span class="badge bg-info">
                                                        DP
                                                    </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="dropdown" style="position: absolute; display: inline-block;">
                                                        <a href="javascript:void(0)" data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fe fe-more-vertical"></i></a>
                                                        <div class="dropdown-menu" style="min-width: 7rem; z-index: 999999999;">
                                                            <a href={{ route('finance.payments.account-payable.show', $data->id) }} class="btn text-purple btn-sm dropdown-item"><span class="fe fe-eye fs-14"></span> Detail</a>
                                                            <a href={{ route('finance.payments.account-payable.edit', $data->id) }} class="btn text-warning btn-sm dropdown-item"><span class="fe fe-edit fs-14"></span> Edit</a>
                                                            <a href={{ route('finance.payments.account-payable.jurnal', $data->id) }} class="btn text-success btn-sm dropdown-item">
                                                                <span class="fe fe-edit fs-14"></span> Journal
                                                            </a>
    
                                                            <!-- delete -->
                                                            <form action="{{ route('finance.payments.account-payable.destroy', $data->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn text-danger btn-sm dropdown-item" onclick="return confirmDelete()"><span class="fe fe-trash fs-14"></span> Delete</button>
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
        if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
            return true;
        } else {
            return false;
        }
    }
</script>
@endpush
