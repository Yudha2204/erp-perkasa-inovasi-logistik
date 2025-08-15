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
                <h1>Laporan Rekening</h1>
            </div>
            <!-- PAGE-HEADER END -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="d-flex d-inline">
                        <form action="{{ route('finance.report-finance.laporan-rekening') }}" method="GET">
                            <input type="text" name="search" value="{{ request()->search }}" class="form-control" placeholder="Searching.....">
                            <input type="hidden" name="source" value="{{ request()->source }}">
                            <input type="hidden" name="start_date_laporan_rekening" value="{{ request()->start_date_laporan_rekening }}">
                            <input type="hidden" name="end_date_laporan_rekening" value="{{ request()->end_date_laporan_rekening }}">
                        </form>
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
                                            <th>Name</th>
                                            <th>Date</th>
                                            <th>Account</th>
                                            <th>Total </th>
                                            <th>Already paid</th>
                                            <th>Remaining</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sao as $index => $item)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $item->contact->customer_name }}</td>
                                                <td>{{ $item->date }}</td>
                                                <td>{{ $item->account }}</td>
                                                <td>{{ $item->currency->initial }} {{ number_format($item->total, 2) }}</td>
                                                <td>{{ $item->currency->initial }} {{ number_format($item->already_paid, 2) }}</td>
                                                <td>{{ $item->currency->initial }} {{ number_format($item->remaining, 2) }}</td>
                                                <td>
                                                    <a class="btn text-success btn-sm" href="{{ route('finance.report-finance.laporan-rekening-pdf', $item->id) }}">
                                                        <span class="fe fe-check fs-14"></span> Print
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- Pagination Links -->
                            <div class="pagination-wrapper">
                                {{ $sao->links() }} <!-- Laravel pagination links -->
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
@endpush