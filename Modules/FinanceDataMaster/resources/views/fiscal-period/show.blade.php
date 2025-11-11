@extends('layouts.app')
@section('content')

<div class="main-content app-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <!-- PAGE-HEADER -->
            <div class="page-header">
                <h1>Fiscal Period Details</h1>
                <a href="{{ route('finance.master-data.fiscal-period.index') }}" class="btn btn-secondary">
                    <i class="fe fe-arrow-left me-2"></i>Back to List
                </a>
            </div>
            <!-- PAGE-HEADER END -->

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Fiscal Period Information</h3>
                            <div class="card-options">
                                <a href="{{ route('finance.master-data.fiscal-period.edit', $fiscalPeriod->id) }}" class="btn btn-primary btn-sm">
                                    <i class="fe fe-edit me-2"></i>Edit
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <th width="40%">Period</th>
                                                <td><strong>{{ $fiscalPeriod->period }}</strong></td>
                                            </tr>
                                            <tr>
                                                <th>Start Date</th>
                                                <td>{{ $fiscalPeriod->start_date->format('d/m/Y') }}</td>
                                            </tr>
                                            <tr>
                                                <th>End Date</th>
                                                <td>{{ $fiscalPeriod->end_date->format('d/m/Y') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Status</th>
                                                <td>
                                                    @if($fiscalPeriod->status === 'open')
                                                        <span class="badge bg-success">Open</span>
                                                    @else
                                                        <span class="badge bg-danger">Closed</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @if($fiscalPeriod->status === 'closed')
                                            <tr>
                                                <th>Closed At</th>
                                                <td>{{ $fiscalPeriod->closed_at ? $fiscalPeriod->closed_at->format('d/m/Y H:i:s') : '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Closed By</th>
                                                <td>{{ $fiscalPeriod->closedByUser ? $fiscalPeriod->closedByUser->name : '-' }}</td>
                                            </tr>
                                            @endif
                                            <tr>
                                                <th>Notes</th>
                                                <td>{{ $fiscalPeriod->notes ?: '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Created At</th>
                                                <td>{{ $fiscalPeriod->created_at->format('d/m/Y H:i:s') }}</td>
                                            </tr>
                                            <tr>
                                                <th>Updated At</th>
                                                <td>{{ $fiscalPeriod->updated_at->format('d/m/Y H:i:s') }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="btn-list">
                                        <a href="{{ route('finance.master-data.fiscal-period.edit', $fiscalPeriod->id) }}" class="btn btn-primary">
                                            <i class="fe fe-edit me-2"></i>Edit
                                        </a>
                                        <a href="{{ route('finance.master-data.fiscal-period.index') }}" class="btn btn-secondary">
                                            <i class="fe fe-arrow-left me-2"></i>Back to List
                                        </a>
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

