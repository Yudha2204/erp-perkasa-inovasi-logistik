@extends('layouts.app')
@section('content')
  <div class="main-content app-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <!-- PAGE-HEADER -->
            <div class="page-header">
                <h1>Penerimaan</h1>
            </div>
            <!-- PAGE-HEADER END -->

            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="d-flex d-inline">
                        <input type="text" class="form-control col-5" placeholder="Searching.....">&nbsp;&nbsp;
                        <a class="btn btn-primary" href="{{ route('finance.kas.penerimaan.create') }}"><i
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
                            <div class="table-responsive">
                                <table class="table text-nowrap text-md-nowrap mb-0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Date</th>
                                            <th>No Referensi</th>
                                            <th>Nama</th>
                                            <th>Description</th>
                                            <th>Value</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($head as $data)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d', $data->date_kas_in)->format('d/m/Y') }}</td>
                                            <td>{{ $data->transaction }}</td>
                                            <td>
                                                @if(isset($data->contact))
                                                {{ $data->contact->customer_name }}
                                                @endif
                                            </td>
                                            <td>{{ $data->description }}</td>
                                            <td>
                                                {{ isset($data->currency) ? $data->currency->initial : "" }} {{ number_format($data->total, 2, '.', ','); }}
                                            </td>
                                            <td>
                                                <div class="dropdown" style="position: absolute; display: inline-block;">
                                                    <a href="javascript:void(0)" data-bs-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fe fe-more-vertical"></i></a>
                                                    <div class="dropdown-menu" style="min-width: 7rem; z-index: 999999999;">
                                                        <a href="{{ route('finance.kas.penerimaan.show', $data->id) }}" class="btn text-purple btn-sm dropdown-item"><span class="fe fe-eye fs-14"></span> Detail</a>
                                                        <a href="{{ route('finance.kas.penerimaan.edit', $data->id) }}" class="btn text-warning btn-sm dropdown-item"><span class="fe fe-edit fs-14"></span> Edit</a>
                                                        <a href="{{ route('finance.kas.penerimaan.jurnal', $data->id) }}" class="btn text-success btn-sm dropdown-item">
                                                            <span class="fe fe-edit fs-14"></span> Journal
                                                        </a>

                                                        <!-- delete -->
                                                        <form action="{{ route('finance.kas.penerimaan.destroy', $data->id) }}" method="POST">
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
        if (confirm('Are you sure want to delete this item?')) {
            return true;
        } else {
            return false;
        }
    }
</script>
@endpush