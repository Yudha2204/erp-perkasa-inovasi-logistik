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
                <h1>Fiscal Period Management</h1>
            </div>
            <!-- PAGE-HEADER END -->

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="d-flex d-inline">
                        <form action="{{ route('finance.master-data.fiscal-period.index') }}" method="GET" class="d-flex">
                            <input type="text" name="search" id="search" value="{{ Request::get('search') }}" class="form-control" placeholder="Search by period...">
                            <select name="year" class="form-control ms-2" style="width: 150px;">
                                <option value="">All Years</option>
                                @for($i = date('Y') - 5; $i <= date('Y') + 1; $i++)
                                    <option value="{{ $i }}" {{ Request::get('year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                            <button type="submit" class="btn btn-light ms-2"><i class="fe fe-search"></i></button>
                        </form>
                        &nbsp;&nbsp;<a class="btn btn-primary" href="{{ route('finance.master-data.fiscal-period.create') }}"><i class="fe fe-plus me-2"></i>Add New</a>
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
                                            <th>Period</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Status</th>
                                            <th>Closed At</th>
                                            <th>Closed By</th>
                                            <th>Notes</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($fiscalPeriods as $key => $period)
                                            <tr>
                                                <td>{{ $fiscalPeriods->firstItem() + $key }}</td>
                                                <td><strong>{{ $period->period }}</strong></td>
                                                <td>{{ $period->start_date->format('d/m/Y') }}</td>
                                                <td>{{ $period->end_date->format('d/m/Y') }}</td>
                                                <td>
                                                    @if($period->status === 'open')
                                                        <span class="badge bg-success">Open</span>
                                                    @else
                                                        <span class="badge bg-danger">Closed</span>
                                                    @endif
                                                </td>
                                                <td>{{ $period->closed_at ? $period->closed_at->format('d/m/Y H:i') : '-' }}</td>
                                                <td>{{ $period->closedByUser ? $period->closedByUser->name : '-' }}</td>
                                                <td>{{ Str::limit($period->notes, 50) ?: '-' }}</td>
                                                <td>
                                                    <div class="g-2">
                                                        <a href="{{ route('finance.master-data.fiscal-period.show', $period->id) }}" class="btn text-info btn-sm"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-original-title="View">
                                                            <span class="fe fe-eye fs-14"></span>
                                                        </a>
                                                        <a href="{{ route('finance.master-data.fiscal-period.edit', $period->id) }}" class="btn text-primary btn-sm"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-original-title="Edit">
                                                            <span class="fe fe-edit fs-14"></span>
                                                        </a>
                                                        <a href="#" class="btn text-danger btn-sm"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-original-title="Delete" 
                                                            onclick="if (confirm('Are you sure you want to delete this fiscal period?')) {
                                                                event.preventDefault();
                                                                document.getElementById('delete-{{$period->id}}').submit();
                                                            } else {
                                                                event.preventDefault();
                                                            }">
                                                            <span class="fe fe-trash-2 fs-14"></span>
                                                        </a>
                                                        <form id="delete-{{$period->id}}" action="{{ route('finance.master-data.fiscal-period.destroy', $period->id) }}" style="display: none;" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                        <tr>
                                            <td colspan="9" class="text-center">
                                                <span class="text-danger">
                                                    <strong>No fiscal periods found</strong>
                                                </span>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            {{ $fiscalPeriods->appends(request()->input())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- CONTAINER CLOSED -->
    </div>
</div>

@endsection

