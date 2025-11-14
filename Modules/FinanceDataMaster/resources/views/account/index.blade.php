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
                <h1>Account Data</h1>
                <a class="btn btn-primary" href="{{ route('finance.master-data.account-type.index') }}" target="_blank"><i class="fe fe-list me-2"></i>View Account Type</a>
            </div>
            <!-- PAGE-HEADER END -->

            <div class="row">
                <div class="col-md-12 mb-3">
                    <div class="d-flex d-inline">
                        <form action="{{ route('finance.master-data.account.index') }}" method="GET" class="d-flex align-items-center">
                            <input type="text" name="search" id="search" value="{{ Request::get('search') }}" class="form-control" placeholder="Searching.....">
                            @if(Request::get('pageSize'))
                                <input type="hidden" name="pageSize" value="{{ Request::get('pageSize') }}">
                            @endif
                        </form>
                        &nbsp;&nbsp;
                        <select name="pageSize" id="pageSize" class="form-control" style="width: auto; max-width: 120px;">
                            <option value="10" {{ Request::get('pageSize', 10) == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ Request::get('pageSize') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ Request::get('pageSize') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ Request::get('pageSize') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                        &nbsp;&nbsp;<a class="btn btn-primary" data-bs-effect="effect-scale" data-bs-toggle="modal" href="#modaldemo8"><i class="fe fe-plus me-2"></i>Add New</a>&nbsp;&nbsp;
                        <button type="button" class="btn btn-light"><img src="{{ url('assets/images/icon/filter.png') }}" alt=""></button>
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
                                            <th>Code</th>
                                            <th>Type</th>
                                            <th>Account Name</th>
                                            <th>Account Type</th>
                                            <th>Currency</th>
                                            <th>Saldo</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($accounts as $key => $a)
                                            <tr>
                                                <td>{{ $accounts->firstItem() + $key }}</td>
                                                <td>{{ $a->code }}</td>
                                                <td>{{ \Illuminate\Support\Str::title($a->type) }}</td>
                                                <td>{{ $a->account_name }}</td>
                                                <td>{{ $a->account_type?->name ?? '-' }}</td>
                                                <td>{{ $a->currency?->initial ?? '-'}}</td>
                                                <td>
                                                    @php
                                                        // Get all balance accounts
                                                        $balance_accounts = $a->balance_accounts->where('currency_id', $a->master_currency_id);
                                                        
                                                        // Sum all debit and credit
                                                        $total_debit = 0;
                                                        $total_credit = 0;
                                                        foreach ($balance_accounts as $data) {
                                                            $total_debit += $data->debit ?? 0;
                                                            $total_credit += $data->credit ?? 0;
                                                        }
                                                        
                                                        // Get normal_side from account_type, default to 'debit'
                                                        $normalSide = $a->account_type?->normal_side ?? 'debit';
                                                        
                                                        // Calculate ending balance based on normal_side
                                                        if ($normalSide === 'debit') {
                                                            // Asset accounts: ending balance = debit - credit
                                                            $ending_balance = $total_debit - $total_credit;
                                                        } else {
                                                            // Liability/Equity accounts: ending balance = credit - debit
                                                            $ending_balance = $total_credit - $total_debit;
                                                        }
                                                        
                                                        // Format the ending balance
                                                        if($ending_balance < 0) {
                                                            $ending_balance = '(' . number_format($ending_balance*-1, 2, '.', ',') . ')';
                                                        } else {
                                                            $ending_balance = number_format($ending_balance, 2, '.', ',');
                                                        }
                                                    @endphp
                                                    {{ $ending_balance }}
                                                </td>
                                                <td>
                                                    <div class="g-2">
                                                        @if ($a->currency)
                                                            <a href="javascript:void(0)" id="btn-create-beginning-balance" data-id="{{ $a->id }}" data-code="{{ $a->code }}" data-name="{{ $a->account_name }}" class="btn text-primary btn-sm"
                                                                data-bs-toggle="tooltip"
                                                                data-bs-original-title="Create Saldo Awal"><span
                                                                    class="fe fe-plus fs-14"></span></a>
                                                        @endif
                                                        <a href="javascript:void(0)" id="btn-show" data-id="{{ $a->id }}" class="btn text-primary btn-sm"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-original-title="Detail"><span
                                                            class="fe fe-eye fs-14"></span></a>
                                                        <a href="javascript:void(0)" id="btn-edit" data-id="{{ $a->id }}" class="btn text-primary btn-sm"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-original-title="Edit"><span
                                                                class="fe fe-edit fs-14"></span></a>
                                                        @if ($a->can_delete == 1)
                                                            <a href="#" class="btn text-danger btn-sm"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-original-title="Delete" onclick="if (confirm('Are you sure want to delete this item?')) {
                                                                                event.preventDefault();
                                                                                document.getElementById('delete-{{$a->id}}').submit();
                                                                            }else{
                                                                                event.preventDefault();
                                                                            }">
                                                                        <span class="fe fe-trash-2 fs-14"></span>
                                                                    </a>
                                                            <form id="delete-{{$a->id}}" action="{{route('finance.master-data.account.destroy',$a->id)}}" style="display: none;" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                        <td colspan="7" style="text-align: center">
                                            <span class="text-danger">
                                                <strong>Data is Empty</strong>
                                            </span>
                                        </td>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    Showing {{ $accounts->firstItem() ?? 0 }} to {{ $accounts->lastItem() ?? 0 }} of {{ $accounts->total() }} entries
                                </div>
                                <div>
                                    {{ $accounts->appends(request()->input())->links() }}
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

{{-- modal create --}}
<div class="modal fade" id="modaldemo8" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">+ Add Account</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ route('finance.master-data.account.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Type</label>
                                <select class="form-control select2 form-select"
                                    data-placeholder="Choose one" name="type">
                                    <option value="header">Header</option>
                                    <option value="detail">Detail</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Classification</label>
                                <select class="form-control select2 form-select"
                                    data-placeholder="Choose one" name="account_type_id">
                                    @foreach ($accountTypes as $accountType)
                                        <option {{ old('account_type_id') == $accountType->id ? "selected" : "" }} value="{{ $accountType->id }}" data-report-type="{{ $accountType->report_type ?? 'NONE' }}">{{ $accountType->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Code</label>
                                <input type="text" name="code" value="{{ old('code') }}" id="code" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Account Name</label>
                                <input type="text" name="account_name" value="{{ old('account_name') }}" id="account_name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Parent</label>
                                <select class="form-control select2 form-select"
                                    data-placeholder="Choose one" name="parent">
                                    @foreach ($headerAccounts as $account)
                                        <option {{ old('parent') == $account->id ? "selected" : "" }} value="{{ $account->id }}">{{ $account->code }} - {{ $account->account_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Currency</label>
                                <select class="form-control select2 form-select"
                                    data-placeholder="Choose one" name="master_currency_id">
                                    @foreach ($currencies as $currency)
                                        <option {{ old('master_currency_id') == $currency->id ? "selected" : "" }} value="{{ $currency->id }}">{{ $currency->initial }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-3" style="text-align: right">
                                <a class="btn btn-white color-grey" data-bs-dismiss="modal">Close</a>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- modal edit --}}
<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Account</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form action="" method="POST" id="form-edit">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="id" id="id_edit">
                            <div class="form-group">
                                <label>Type</label>
                                <select class="form-control select2 form-select"
                                    data-placeholder="Choose one" name="type" id="type_edit">
                                    <option value="header">Header</option>
                                    <option value="detail">Detail</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Classification</label>
                                <select class="form-control select2 form-select"
                                    data-placeholder="Choose one" name="account_type_id" id="account_type_id_edit">
                                    @foreach ($accountTypes as $accountType)
                                        <option value="{{ $accountType->id }}" data-report-type="{{ $accountType->report_type ?? 'NONE' }}">{{ $accountType->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Code</label>
                                <input type="text" name="code" id="code_edit" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Account Name</label>
                                <input type="text" name="account_name" id="account_name_edit" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Parent</label>
                                <select class="form-control select2 form-select"
                                    data-placeholder="Choose one" name="parent" id="parent_edit">
                                    @foreach ($headerAccounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->code }} - {{ $account->account_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Currency</label>
                                <select class="form-control select2 form-select"
                                    data-placeholder="Choose one" name="master_currency_id" id="master_currency_id_edit">
                                    @foreach ($currencies as $currency)
                                        <option value="{{ $currency->id }}">{{ $currency->initial }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-3" style="text-align: right">
                                <a class="btn btn-white color-grey" data-bs-dismiss="modal">Close</a>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- modal create beginning balance --}}
<div class="modal fade" id="createBeginningBalance" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">+ Add Saldo Awal</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ route('finance.master-data.account.store-beginning-balance') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id_account" id="id_account">
                            <input type="hidden" name="id_balance_account" id="id_balance_account">
                            <input type="hidden" name="account_type_id" id="account_type_id_beginning_balance">
                            
                            <!-- Hidden fields to store multiple entries data -->
                            <input type="hidden" name="invoice_entries" id="invoice_entries" value="">
                            <input type="hidden" name="ap_entries" id="ap_entries" value="">
                            
                            <div class="form-group">
                                <label>Code</label>
                                <input type="text" name="code" value="{{ old('code') }}" id="code_beginning_balance" class="form-control" disabled>
                            </div>
                            <div class="form-group">
                                <label>Account Name</label>
                                <input type="text" name="account_name" value="{{ old('account_name') }}" id="account_name_beginning_balance" class="form-control" disabled>
                            </div>
                            
                            <!-- AR Account Fields (Account Receivable) -->
                            <div id="ar_fields" style="display: none;">
                                <h6 class="text-primary">Invoice Information (Account Receivable)</h6>
                                
                                <!-- Add New Invoice Form -->
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h6 class="mb-0">Add New Invoice</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Invoice Number <span class="text-danger">*</span></label>
                                                    <input type="number" min="1" step="1" id="new_invoice_number" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Invoice Date <span class="text-danger">*</span></label>
                                                    <input type="date" id="new_invoice_date" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Customer <span class="text-danger">*</span></label>
                                                    <select class="form-control select2 form-select" id="new_invoice_contact_id" data-placeholder="Choose One">
                                                        <option label="Choose One" selected disabled></option>
                                                        @foreach ($customers as $customer)
                                                            <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Beginning Balance Value <span class="text-danger">*</span></label>
                                                    <input type="number" step="0.01" id="new_invoice_value" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-6" id="invoice_idr_field" style="display: none;">
                                                <div class="form-group">
                                                    <label>IDR Value (Manual Exchange Rate) <span class="text-muted">(Optional)</span></label>
                                                    <input type="number" step="0.01" id="new_invoice_idr_value" class="form-control" placeholder="Leave empty to use default exchange rate">
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-primary btn-sm" onclick="addInvoiceEntry()">
                                            <i class="fe fe-plus"></i> Add Invoice
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Invoice Entries Table -->
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="invoice_table">
                                        <thead>
                                            <tr>
                                                <th>Invoice Number</th>
                                                <th>Invoice Date</th>
                                                <th>Customer</th>
                                                <th>Value</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="invoice_table_body">
                                            <!-- Invoice entries will be added here dynamically -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- AP Account Fields (Account Payable) -->
                            <div id="ap_fields" style="display: none;">
                                <h6 class="text-primary">Account Payable Information</h6>
                                
                                <!-- Add New AP Form -->
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h6 class="mb-0">Add New Account Payable</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>AP Number <span class="text-danger">*</span></label>
                                                    <input type="text" id="new_ap_number" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>AP Date <span class="text-danger">*</span></label>
                                                    <input type="date" id="new_ap_date" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Vendor <span class="text-danger">*</span></label>
                                                    <select class="form-control select2 form-select" id="new_ap_vendor_id" data-placeholder="Choose One">
                                                        <option label="Choose One" selected disabled></option>
                                                        @foreach ($vendors as $vendor)
                                                            <option value="{{ $vendor->id }}">{{ $vendor->customer_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Beginning Balance Value <span class="text-danger">*</span></label>
                                                    <input type="number" step="0.01" id="new_ap_value" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-6" id="ap_idr_field" style="display: none;">
                                                <div class="form-group">
                                                    <label>IDR Value (Manual Exchange Rate) <span class="text-muted">(Optional)</span></label>
                                                    <input type="number" step="0.01" id="new_ap_idr_value" class="form-control" placeholder="Leave empty to use default exchange rate">
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-primary btn-sm" onclick="addAPEntry()">
                                            <i class="fe fe-plus"></i> Add AP
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- AP Entries Table -->
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="ap_table">
                                        <thead>
                                            <tr>
                                                <th>AP Number</th>
                                                <th>AP Date</th>
                                                <th>Vendor</th>
                                                <th>Value</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="ap_table_body">
                                            <!-- AP entries will be added here dynamically -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Debit/Credit fields for non-AR/AP accounts -->
                            <div id="debit_credit_fields">
                                <div class="form-group">
                                    <label>Debit</label>
                                    <input type="text" name="debit" value="{{ old('debit') }}" id="debit" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Kredit</label>
                                    <input type="text" name="credit" value="{{ old('credit') }}" id="credit" class="form-control">
                                </div>
                            </div>

                            <div class="mt-3" style="text-align: right">
                                <a class="btn btn-white color-grey" data-bs-dismiss="modal">Close</a>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- modal edit --}}
<div class="modal fade z-index-1000" id="modal-edit-beginning-balance" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg bg-white" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">+ Update Saldo Awal</h5>
            </div>
            <form action="{{ route('finance.master-data.account.update-beginning-balance') }}" method="POST" id="form-edit-beginning-balance">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id" id="id_ar_ap">
                    <input type="hidden" name="type" id="edit_type">
                    <div class="form-group">
                        <label>Number</label>
                        <input type="text" name="number_edit_beginning_balance" id="number_edit_beginning_balance" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" name="date_edit_beginning_balance" id="date_edit_beginning_balance" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Value</label>
                        <input type="text" name="value_edit_beginning_balance" id="value_edit_beginning_balance" class="form-control">
                    </div>
                    <div class="form-group" id="idr_value_edit_field" style="display: none;">
                        <label>IDR Value (Manual Exchange Rate) <span class="text-muted">(Optional)</span></label>
                        <input type="number" step="0.01" name="idr_value_edit_beginning_balance" id="idr_value_edit_beginning_balance" class="form-control" placeholder="Leave empty to use default exchange rate">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- modal show --}}
<div class="modal fade" id="modal-show" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> Detail Account</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Type</label>
                            <select class="form-control select2 form-select"
                                data-placeholder="Choose one" name="type" id="type_show" disabled>
                                <option value="header">Header</option>
                                <option value="detail">Detail</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Account Type</label>
                            <select class="form-control select2 form-select"
                                data-placeholder="Choose one" name="account_type_id" id="account_type_id_show" disabled>
                                @foreach ($accountTypes as $accountType)
                                    <option value="{{ $accountType->id }}" data-report-type="{{ $accountType->report_type ?? 'NONE' }}">{{ $accountType->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Code</label>
                            <input type="text" name="code" id="code_show" class="form-control" disabled>
                        </div>
                        <div class="form-group">
                            <label>Account Name</label>
                            <input type="text" name="account_name" id="account_name_show" class="form-control" disabled>
                        </div>
                        <div class="form-group">
                            <label>Parent</label>
                            <select class="form-control select2 form-select"
                                data-placeholder="Choose one" name="parent" id="parent_show" disabled>
                                @foreach ($headerAccounts as $account)
                                    <option {{ old('parent') == $account->id ? "selected" : "" }} value="{{ $account->id }}">{{ $account->code }} - {{ $account->account_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Currency</label>
                            <select class="form-control select2 form-select"
                                data-placeholder="Choose one" name="master_currency_id" id="master_currency_id_show" disabled>
                                @foreach ($currencies as $currency)
                                    <option value="{{ $currency->id }}">{{ $currency->initial }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <h4>Saldo Awal Akun</h4>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Debit</label>
                            <input type="text" name="" id="debit_show" class="form-control" disabled>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Kredit</label>
                            <input type="text" name="" id="credit_show" class="form-control" disabled>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="mt-3" style="text-align: right">
                            <a class="btn btn-white color-grey" data-bs-dismiss="modal">Close</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')
    <script>

    // Show/hide Account Header field based on Account Type selection
    $('select[name="type"]').on('change', function() {
        if ($(this).val() === 'detail') {
            $('select[name="parent"]').closest('.form-group').show();
            $('select[name="account_type_id"]').closest('.form-group').show();
            $('select[name="master_currency_id"]').closest('.form-group').show();
        } else {
            $('select[name="parent"]').val("");
            $('select[name="account_type_id"]').val("");
            $('select[name="master_currency_id"]').val("");
            $('select[name="parent"]').closest('.form-group').hide();
            $('select[name="account_type_id"]').closest('.form-group').hide();
            $('select[name="master_currency_id"]').closest('.form-group').hide();
        }
    });

    // Function to check account type report type and hide/show currency field
    function toggleCurrencyField(accountTypeSelect, currencyField) {
        const selectedAccountTypeId = $(accountTypeSelect).val();
        if (selectedAccountTypeId) {
            // Get the account type data to check report_type
            const accountTypeOption = $(accountTypeSelect).find('option:selected');
            const reportType = accountTypeOption.data('report-type');
            
            if (reportType === 'PL' || reportType === 'NONE') {
                $(currencyField).closest('.form-group').hide();
            } else {
                $(currencyField).closest('.form-group').show();
            }
        }
    }

    // Show/hide currency field based on Account Type selection (create modal)
    $('select[name="account_type_id"]').on('change', function() {
        toggleCurrencyField(this, 'select[name="master_currency_id"]');
    });

    // Show/hide currency field based on Account Type selection (edit modal)
    $('#account_type_id_edit').on('change', function() {
        toggleCurrencyField(this, '#master_currency_id_edit');
    });

    // Show/hide Parent field based on Type selection (edit modal)
    $('#type_edit').on('change', function() {
        if ($(this).val() === 'detail') {
            $('#parent_edit').closest('.form-group').show();
            $('#account_type_id_edit').closest('.form-group').show();
            $('#master_currency_id_edit').closest('.form-group').show();
        } else {
            $('#parent_edit').val("").trigger('change');
            $('#account_type_id_edit').val("").trigger('change');
            $('#master_currency_id_edit').val("").trigger('change');
            $('#parent_edit').closest('.form-group').hide();
            $('#account_type_id_edit').closest('.form-group').hide();
            $('#master_currency_id_edit').closest('.form-group').hide();
        }
    });

    // On page load, trigger change to set initial state
    $(document).ready(function() {
        $('select[name="type"]').val("header").change();
        
        // Check currency field visibility on page load if account type is already selected
        if ($('select[name="account_type_id"]').val()) {
            toggleCurrencyField('select[name="account_type_id"]', 'select[name="master_currency_id"]');
        }
    });
    
    //create beginning balance

    $('body').on('click', '#btn-create-beginning-balance', function () {

        let accountId = $(this).data('id');
        var url = "{{ route('finance.master-data.account.show', ':id') }}";
        url = url.replace(':id', accountId);

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'GET',
            dataType: 'json',
            url: url,
            success:function(response){
                    // Fill basic data to form
                    $('#id_account').val(response.data.id);
                    $('#code_beginning_balance').val(response.data.code);
                    $('#account_name_beginning_balance').val(response.data.account_name);
                    $('#account_type_id_beginning_balance').val(response.data.account_type_id);
                    $('#account_type_id_beginning_balance').data('currency-id', response.data.master_currency_id);

                    $('#id_balance_account').val(response.data.id_balance_account);
                    $('#credit').val(response.data.credit);
                    $('#debit').val(response.data.debit);

                    // Show/hide AR and AP fields based on account type
                    const accountTypeId = response.data.account_type_id;
                    const currencyId = response.data.master_currency_id;
                    const idrCurrencyId = {{ $idrCurrencyId }};
                    const isForeignCurrency = currencyId && currencyId != idrCurrencyId;
                    
                    // Hide all special fields first
                    $('#ar_fields').hide();
                    $('#ap_fields').hide();
                    $('#debit_credit_fields').show(); // Show debit/credit by default
                    
                    // Clear existing entries
                    invoiceEntries = [];
                    apEntries = [];
                    $('#invoice_table_body').empty();
                    $('#ap_table_body').empty();
                    
                    // Show/hide IDR field based on currency
                    if (isForeignCurrency) {
                        $('#invoice_idr_field').show();
                        $('#ap_idr_field').show();
                    } else {
                        $('#invoice_idr_field').hide();
                        $('#ap_idr_field').hide();
                    }
                    
                    // Show AR fields for Account Receivable (ID 4)
                    if (accountTypeId == 4) {
                        $('#ar_fields').show();
                        $('#debit_credit_fields').hide(); // Hide debit/credit for AR
                        
                        // Load existing invoice entries if any
                        if (response.data.invoice_entries && response.data.invoice_entries.length > 0) {
                            response.data.invoice_entries.forEach(function(entry) {
                                // Add to the entries array
                                invoiceEntries.push(entry);
                                // Add to the table
                                addInvoiceEntryToTable(entry);
                            });
                            // Update the hidden field
                            updateInvoiceEntriesHiddenField();
                        }
                    }
                    // Show AP fields for Account Payable (ID 8)
                    else if (accountTypeId == 8) {
                        $('#ap_fields').show();
                        $('#debit_credit_fields').hide(); // Hide debit/credit for AP
                        
                        // Load existing AP entries if any
                        if (response.data.ap_entries && response.data.ap_entries.length > 0) {
                            response.data.ap_entries.forEach(function(entry) {
                                // Add to the entries array
                                apEntries.push(entry);
                                // Add to the table
                                addAPEntryToTable(entry);
                            });
                            // Update the hidden field
                            updateAPEntriesHiddenField();
                        }
                    }

                    $('#createBeginningBalance').modal('show');
                }
        });

    });

    //edit data
    $('body').on('click', '#btn-edit', function () {

        let editId = $(this).data('id');
        var editUrl = "{{ route('finance.master-data.account.edit', ':id') }}";
        editUrl = editUrl.replace(':id', editId);
        
        var updateUrl = "{{ route('finance.master-data.account.update', ':id') }}";
        updateUrl = updateUrl.replace(':id', editId);

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'GET',
            dataType: 'json',
            url: editUrl,
            success:function(response){
                    console.log('Edit response:', response); // Debug log
                    
                    if (!response || !response.data) {
                        console.error('Invalid response structure:', response);
                        alert('Invalid response from server. Please try again.');
                        return;
                    }
                    
                    // Set form action
                    $('#form-edit').attr('action', updateUrl);
                    
                    // Fill data to form
                    $('#id_edit').val(response.data.id || '');
                    $('#code_edit').val(response.data.code || '');
                    $('#account_name_edit').val(response.data.account_name || '');
                    
                    // Set type first to control field visibility
                    if (response.data.type) {
                        $("#type_edit").val(response.data.type).trigger('change');
                    }
                    
                    // Then set other fields
                    if (response.data.account_type_id) {
                        $("#account_type_id_edit").val(response.data.account_type_id).trigger('change');
                    }
                    if (response.data.parent) {
                        $("#parent_edit").val(response.data.parent).trigger('change');
                    }
                    if (response.data.master_currency_id) {
                        $("#master_currency_id_edit").val(response.data.master_currency_id).trigger('change');
                    }
                    
                    // Check currency field visibility after setting account type
                    setTimeout(function() {
                        toggleCurrencyField('#account_type_id_edit', '#master_currency_id_edit');
                        
                        // Show modal after a small delay to ensure DOM is ready
                        var modalElement = document.getElementById('modal-edit');
                        console.log('Modal element:', modalElement); // Debug log
                        
                        if (modalElement) {
                            // Try Bootstrap 5 native API first
                            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                                try {
                                    var modal = bootstrap.Modal.getOrCreateInstance(modalElement);
                                    modal.show();
                                    console.log('Modal shown using Bootstrap 5 API');
                                } catch(e) {
                                    console.error('Error showing modal with Bootstrap 5:', e);
                                    // Fallback to jQuery
                                    $('#modal-edit').modal('show');
                                }
                            } else {
                                // Fallback to jQuery
                                $('#modal-edit').modal('show');
                                console.log('Modal shown using jQuery');
                            }
                        } else {
                            console.error('Modal element #modal-edit not found in DOM');
                            alert('Modal element not found. Please refresh the page.');
                        }
                    }, 150);
                },
            error: function(xhr, status, error) {
                console.error('Error loading edit data:', error);
                console.error('Response:', xhr.responseText);
                alert('Error loading account data. Please try again.');
            }
        });
    });

    //show data
    $('body').on('click', '#btn-show', function () {

    let showId = $(this).data('id');
    var url = "{{ route('finance.master-data.account.show', ':id') }}";
    url = url.replace(':id', showId);

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'GET',
            dataType: 'json',
            url: url,
            success:function(response){
                    // //fill data to form
                    $("#account_type_id_show").val(response.data.account_type_id).change();
                    $('#code_show').val(response.data.code);
                    $('#account_name_show').val(response.data.account_name);
                    $("#master_currency_id_show").val(response.data.master_currency_id).change();
                    $("#type_show").val(response.data.type).change();
                    $("#parent_show").val(response.data.parent).change();
                    $('#credit_show').val(response.data.credit);
                    $('#debit_show').val(response.data.debit);
                    
                    // Check currency field visibility after setting account type
                    setTimeout(function() {
                        toggleCurrencyField('#account_type_id_show', '#master_currency_id_show');
                    }, 100);

                    $('#modal-show').modal('show');
                }
        });
    });

    // Global variables to store entries
    let invoiceEntries = [];
    let apEntries = [];

    // Invoice entry functions
    function addInvoiceEntry() {
        const number = $('#new_invoice_number').val();
        const date = $('#new_invoice_date').val();
        const value = $('#new_invoice_value').val();
        const contactId = $('#new_invoice_contact_id').val();
        const contactName = $('#new_invoice_contact_id option:selected').text();
        const idrValue = $('#new_invoice_idr_value').val();
        const idrCurrencyId = {{ $idrCurrencyId }};
        const accountCurrencyId = $('#account_type_id_beginning_balance').data('currency-id') || null;
        const isForeignCurrency = accountCurrencyId && accountCurrencyId != idrCurrencyId;

        if (!number || !date || !value || !contactId) {
            alert('Please fill all required fields including Customer');
            return;
        }

        // Validate transaction date must be before start period date
        @if($startEntryPeriodDate)
        const startPeriodDate = new Date('{{ $startEntryPeriodDate }}');
        const transactionDate = new Date(date);
        if (transactionDate >= startPeriodDate) {
            alert('Transaction date must be before start entry period ({{ \Carbon\Carbon::parse($startEntryPeriodDate)->format('d/m/Y') }})!');
            return;
        }
        @endif

        const entry = {
            id: Date.now(), // Temporary ID for new entries
            number: number,
            date: date,
            value: parseFloat(value),
            contact_id: contactId,
            contact_name: contactName,
            idr_value: idrValue ? parseFloat(idrValue) : null
        };

        invoiceEntries.push(entry);
        addInvoiceEntryToTable(entry);
        updateInvoiceEntriesHiddenField();
        
        // Clear form
        $('#new_invoice_number').val('');
        $('#new_invoice_date').val('');
        $('#new_invoice_value').val('');
        $('#new_invoice_contact_id').val('').trigger('change');
        $('#new_invoice_idr_value').val('');
    }

    function addInvoiceEntryToTable(entry) {
        const contactName = entry.contact_name || 'N/A';
        const row = `
            <tr data-entry-id="${entry.id}">
                <td>${entry.number}</td>
                <td>${entry.date}</td>
                <td>${contactName}</td>
                <td>${entry.value.toLocaleString()}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-warning" onclick="editInvoiceEntry('${entry.id}')">
                        <i class="fe fe-edit"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteInvoiceEntry('${entry.id}')">
                        <i class="fe fe-trash-2"></i>
                    </button>
                </td>
            </tr>
        `;
        $('#invoice_table_body').append(row);
    }

    function editInvoiceEntry(entryId) {
        const entry = invoiceEntries.find(e => e.id == entryId || e.id == parseInt(entryId));
        if (entry) {
            const idrCurrencyId = {{ $idrCurrencyId }};
            const accountCurrencyId = $('#account_type_id_beginning_balance').data('currency-id') || null;
            const isForeignCurrency = accountCurrencyId && accountCurrencyId != idrCurrencyId;
            
            $('#id_ar_ap').val(entry.id);
            $('#edit_type').val("invoice");
            $('#number_edit_beginning_balance').val(entry.number);
            $('#date_edit_beginning_balance').val(entry.date);
            $('#value_edit_beginning_balance').val(entry.value);
            
            // Show/hide IDR field based on currency
            if (isForeignCurrency) {
                $('#idr_value_edit_field').show();
                $('#idr_value_edit_beginning_balance').val(entry.idr_value || '');
            } else {
                $('#idr_value_edit_field').hide();
                $('#idr_value_edit_beginning_balance').val('');
            }
            
            $('#modal-edit-beginning-balance').modal('show');
        }
    }

    function deleteInvoiceEntry(entryId) {
        if (!confirm('Are you sure you want to delete this invoice entry?')) {
            return;
        }
        invoiceEntries = invoiceEntries.filter(e => e.id != entryId && e.id != parseInt(entryId));
        $(`tr[data-entry-id="${entryId}"]`).remove();
        updateInvoiceEntriesHiddenField();
    }

    function updateInvoiceEntriesHiddenField() {
        $('#invoice_entries').val(JSON.stringify(invoiceEntries));
    }

    // AP entry functions
    function addAPEntry() {
        const number = $('#new_ap_number').val();
        const date = $('#new_ap_date').val();
        const value = $('#new_ap_value').val();
        const vendorId = $('#new_ap_vendor_id').val();
        const vendorName = $('#new_ap_vendor_id option:selected').text();
        const idrValue = $('#new_ap_idr_value').val();
        const idrCurrencyId = {{ $idrCurrencyId }};
        const accountCurrencyId = $('#account_type_id_beginning_balance').data('currency-id') || null;
        const isForeignCurrency = accountCurrencyId && accountCurrencyId != idrCurrencyId;

        if (!number || !date || !value || !vendorId) {
            alert('Please fill all required fields including Vendor');
            return;
        }

        // Validate transaction date must be before start period date
        @if($startEntryPeriodDate)
        const startPeriodDate = new Date('{{ $startEntryPeriodDate }}');
        const transactionDate = new Date(date);
        if (transactionDate >= startPeriodDate) {
            alert('Transaction date must be before start entry period ({{ \Carbon\Carbon::parse($startEntryPeriodDate)->format('d/m/Y') }})!');
            return;
        }
        @endif

        const entry = {
            id: Date.now(), // Temporary ID for new entries
            number: number,
            date: date,
            value: parseFloat(value),
            vendor_id: vendorId,
            vendor_name: vendorName,
            idr_value: idrValue ? parseFloat(idrValue) : null
        };

        apEntries.push(entry);
        addAPEntryToTable(entry);
        updateAPEntriesHiddenField();
        
        // Clear form
        $('#new_ap_number').val('');
        $('#new_ap_date').val('');
        $('#new_ap_value').val('');
        $('#new_ap_vendor_id').val('').trigger('change');
        $('#new_ap_idr_value').val('');
    }

    function addAPEntryToTable(entry) {
        const vendorName = entry.vendor_name || 'N/A';
        const row = `
            <tr data-entry-id="${entry.id}">
                <td>${entry.number}</td>
                <td>${entry.date}</td>
                <td>${vendorName}</td>
                <td>${entry.value.toLocaleString()}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-warning" onclick="editAPEntry('${entry.id}')">
                        <i class="fe fe-edit"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-danger" onclick="deleteAPEntry('${entry.id}')">
                        <i class="fe fe-trash-2"></i>
                    </button>
                </td>
            </tr>
        `;
        $('#ap_table_body').append(row);
    }

    function editAPEntry(entryId) {
        const entry = apEntries.find(e => e.id == entryId || e.id == parseInt(entryId));
        if (entry) {
            const idrCurrencyId = {{ $idrCurrencyId }};
            const accountCurrencyId = $('#account_type_id_beginning_balance').data('currency-id') || null;
            const isForeignCurrency = accountCurrencyId && accountCurrencyId != idrCurrencyId;
            
            $('#id_ar_ap').val(entry.id);
            $('#edit_type').val("ap");
            $('#number_edit_beginning_balance').val(entry.number);
            $('#date_edit_beginning_balance').val(entry.date);
            $('#value_edit_beginning_balance').val(entry.value);
            
            // Show/hide IDR field based on currency
            if (isForeignCurrency) {
                $('#idr_value_edit_field').show();
                $('#idr_value_edit_beginning_balance').val(entry.idr_value || '');
            } else {
                $('#idr_value_edit_field').hide();
                $('#idr_value_edit_beginning_balance').val('');
            }
            
            $('#modal-edit-beginning-balance').modal('show');
        }
    }

    function deleteAPEntry(entryId) {
        if (!confirm('Are you sure you want to delete this AP entry?')) {
            return;
        }
        apEntries = apEntries.filter(e => e.id != entryId && e.id != parseInt(entryId));
        $(`tr[data-entry-id="${entryId}"]`).remove();
        updateAPEntriesHiddenField();
    }

    function updateAPEntriesHiddenField() {
        $('#ap_entries').val(JSON.stringify(apEntries));
    }

    // Handle page size change
    $('#pageSize').on('change', function() {
        const pageSize = $(this).val();
        const url = new URL(window.location.href);
        url.searchParams.set('pageSize', pageSize);
        url.searchParams.delete('page'); // Reset to first page when changing page size
        window.location.href = url.toString();
    });

    // Validate date before submit edit beginning balance form
    $('#form-edit-beginning-balance').on('submit', function(e) {
        const date = $('#date_edit_beginning_balance').val();
        @if($startEntryPeriodDate)
        if (date) {
            const startPeriodDate = new Date('{{ $startEntryPeriodDate }}');
            const transactionDate = new Date(date);
            if (transactionDate >= startPeriodDate) {
                e.preventDefault();
                alert('Transaction date must be before start entry period ({{ \Carbon\Carbon::parse($startEntryPeriodDate)->format('d/m/Y') }})!');
                return false;
            }
        }
        @endif
    });
    </script>
@endpush