@extends('layouts.app')
@section('content')
@push('styles')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endpush
<div class="main-content app-content mt-0 pb-5">
        <div class="side-app">
            <!-- CONTAINER -->
            <div class="main-container container-fluid">
                <!-- PAGE-HEADER -->
                <div class="page-header">
                    <h1 style="color: #59758B; font-size: medium; font-size: 35px;">General Journal Details</h1>
                </div>
                <!-- PAGE-HEADER END -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex">
                                <h3 class="card-title mb-0">General Journal Details</h3>
                                <div class="card-tools">
                                    @can('edit-general_journal@finance')
                                    <a href="{{ route('generalledger.general-journal.edit', $journal->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                    @endcan
                                    <a href="{{ route('generalledger.general-journal.index') }}" class="btn btn-secondary btn-sm">
                                        <i class="fa fa-arrow-left"></i> Back
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="30%">Journal Number:</th>
                                            <td>{{ $journal->journal_number }}</td>
                                        </tr>
                                        <tr>
                                            <th>Date:</th>
                                            <td>{{ $journal->date->format('d/m/Y') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Currency:</th>
                                            <td>{{ $journal->currency->initial }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="30%">Total Debit:</th>
                                            <td class="text-right"><strong>{{ number_format($journal->total_debit, 2) }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th>Total Credit:</th>
                                            <td class="text-right"><strong>{{ number_format($journal->total_credit, 2) }}</strong></td>
                                        </tr>
                                        <tr>
                                            <th>Balance:</th>
                                            <td class="text-right">
                                                <strong class="{{ abs($journal->total_debit - $journal->total_credit) < 0.01 ? 'text-success' : 'text-danger' }}">
                                                    {{ number_format($journal->total_debit - $journal->total_credit, 2) }}
                                                </strong>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            @if($journal->description)
                            <div class="row">
                                <div class="col-12">
                                    <h5>Description</h5>
                                    <p>{{ $journal->description }}</p>
                                </div>
                            </div>
                            @endif

                            <div class="row">
                                <div class="col-12">
                                    <h5>Journal Entries</h5>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Account Code</th>
                                                    <th>Account Name</th>
                                                    <th>Description</th>
                                                    <th class="text-right">Debit</th>
                                                    <th class="text-right">Credit</th>
                                                    <th>Remark</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($journal->details as $detail)
                                                <tr>
                                                    <td>{{ $detail->account->account_code }}</td>
                                                    <td>{{ $detail->account->account_name }}</td>
                                                    <td>{{ $detail->description }}</td>
                                                    <td class="text-right">
                                                        @if($detail->debit > 0)
                                                            {{ number_format($detail->debit, 2) }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td class="text-right">
                                                        @if($detail->credit > 0)
                                                            {{ number_format($detail->credit, 2) }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>{{ $detail->remark }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr class="table-info">
                                                    <th colspan="3">Total</th>
                                                    <th class="text-right">{{ number_format($journal->total_debit, 2) }}</th>
                                                    <th class="text-right">{{ number_format($journal->total_credit, 2) }}</th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
