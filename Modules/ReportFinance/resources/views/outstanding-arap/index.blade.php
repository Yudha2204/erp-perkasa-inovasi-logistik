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
                <h1>Laporan Outstanding {{ $source == 'customer' || $source == 'invoice' ? 'AR (Invoice)' : 'AP' }}</h1>
                <p class="text-muted">As of Date: {{ \Carbon\Carbon::parse($asOfDate)->format('d F Y') }}</p>
                @if($contact_id || $vendor_id)
                    <p class="text-muted">
                        Filter: 
                        @if($source == 'invoice' && $contact_id && $contacts)
                            @php
                                $selectedContact = $contacts->firstWhere('id', $contact_id);
                            @endphp
                            Customer: <strong>{{ $selectedContact->customer_name ?? 'N/A' }}</strong>
                        @elseif($source == 'order' && $vendor_id && $contacts)
                            @php
                                $selectedVendor = $contacts->firstWhere('id', $vendor_id);
                            @endphp
                            Vendor: <strong>{{ $selectedVendor->customer_name ?? 'N/A' }}</strong>
                        @endif
                    </p>
                @endif
            </div>
            <!-- PAGE-HEADER END -->
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="d-flex d-inline">
                        <form action="{{ route('finance.report-finance.outstanding-arap') }}" method="GET" class="d-flex">
                            <input type="text" name="search" value="{{ request()->search }}" class="form-control" placeholder="Searching....." style="margin-right: 10px;">
                            <input type="hidden" name="source" value="{{ request()->source }}">
                            <input type="hidden" name="as_of_date" value="{{ request()->as_of_date }}">
                            @if($contact_id)
                                <input type="hidden" name="contact_id" value="{{ $contact_id }}">
                            @endif
                            @if($vendor_id)
                                <input type="hidden" name="vendor_id" value="{{ $vendor_id }}">
                            @endif
                            <button type="submit" class="btn btn-primary">Search</button>
                        </form>
                    </div>
                </div>
                <div class="col-md-6 mb-3 text-end">
                    <a href="{{ route('finance.report-finance.outstanding-arap', array_merge(request()->only(['source', 'as_of_date', 'contact_id', 'vendor_id']), ['search' => ''])) }}" class="btn btn-secondary">Clear Search</a>
                    <a href="{{ route('finance.report-finance.index') }}" class="btn btn-info">Back to Reports</a>
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
                                            <th>Invoice/Order No</th>
                                            <th>Date</th>
                                            <th>Account</th>
                                            <th>Total</th>
                                            <th>Already Paid</th>
                                            <th>Remaining</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($paginator as $index => $item)
                                            <tr>
                                                <td>{{ ($paginator->currentPage() - 1) * $paginator->perPage() + $index + 1 }}</td>
                                                <td>{{ $item->contact->customer_name ?? '-' }}</td>
                                                <td>
                                                    @if($item->invoice)
                                                        {{ $item->invoice->transaction ?? '-' }}
                                                    @elseif($item->order)
                                                        {{ $item->order->transaction ?? '-' }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($item->date)->format('d F Y') }}</td>
                                                <td>{{ ucfirst($item->account) }}</td>
                                                <td>{{ $item->currency->initial ?? '' }} {{ number_format($item->total, 2) }}</td>
                                                <td>{{ $item->currency->initial ?? '' }} {{ number_format($item->already_paid, 2) }}</td>
                                                <td><strong>{{ $item->currency->initial ?? '' }} {{ number_format($item->remaining, 2) }}</strong></td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">No outstanding records found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        @foreach($totalsByCurrency as $total)
                                            <tr>
                                                <th colspan="5" class="text-end">Total Outstanding ({{ $total['currency']->initial ?? 'N/A' }}):</th>
                                                <th colspan="3">
                                                    <strong>{{ $total['currency']->initial ?? '' }} {{ number_format($total['total'], 2) }}</strong>
                                                </th>
                                            </tr>
                                        @endforeach
                                    </tfoot>
                                </table>
                            </div>
                            <!-- Pagination Links -->
                            <div class="pagination-wrapper">
                                {{ $paginator->links() }}
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

