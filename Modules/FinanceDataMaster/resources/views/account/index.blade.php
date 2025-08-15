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
                        <form action="{{ route('finance.master-data.account.index') }}">
                            <input type="text" name="search" id="search" value="{{ Request::get('search') }}" class="form-control" placeholder="Searching.....">
                        </form>
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
                                                <td>{{ $a->account_name }}</td>
                                                <td>{{ $a->account_type->name }}</td>
                                                <td>{{ $a->currency->initial }}</td>
                                                <td>
                                                    @php
                                                        $grand_total = 0;
                                                        foreach ($a->balance_accounts as $data) {
                                                            $total = $data->credit - $data->debit;
                                                            $grand_total += $total;
                                                        }
                                                        
                                                        if($grand_total < 0) {
                                                            $grand_total = '(' . number_format($grand_total*-1, 2, '.', ',') . ')';
                                                        } else {
                                                            $grand_total = number_format($grand_total, 2, '.', ',');
                                                        }
                                                    @endphp
                                                    {{ $grand_total }}
                                                </td>
                                                <td>
                                                    <div class="g-2">
                                                        <a href="javascript:void(0)" id="btn-create-beginning-balance" data-id="{{ $a->id }}" data-code="{{ $a->code }}" data-name="{{ $a->account_name }}" class="btn text-primary btn-sm"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-original-title="Create Saldo Awal"><span
                                                                class="fe fe-plus fs-14"></span></a>
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
                            {{  $accounts->appends(request()->input())->links()}}
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
                                <label>Classification</label>
                                <select class="form-control select2 form-select"
                                    data-placeholder="Choose one" name="account_type_id">
                                    @foreach ($accountTypes as $accountType)
                                        <option {{ old('account_type_id') == $accountType->id ? "selected" : "" }} value="{{ $accountType->id }}">{{ $accountType->name }}</option>
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
                                <label>Type</label>
                                <select class="form-control select2 form-select"
                                    data-placeholder="Choose one" name="type">
                                    <option value="header">Header</option>
                                    <option value="detail">Detail</option>
                                </select>
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

{{-- modal create beginning balance --}}
<div class="modal fade" id="createBeginningBalance" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
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
                            <div class="form-group">
                                <label>Code</label>
                                <input type="text" name="code" value="{{ old('code') }}" id="code_beginning_balance" class="form-control" disabled>
                            </div>
                            <div class="form-group">
                                <label>Account Name</label>
                                <input type="text" name="account_name" value="{{ old('account_name') }}" id="account_name_beginning_balance" class="form-control" disabled>
                            </div>
                            <div class="form-group">
                                <label>Debit</label>
                                <input type="text" name="debit" value="{{ old('debit') }}" id="debit" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Kredit</label>
                                <input type="text" name="credit" value="{{ old('credit') }}" id="credit" class="form-control">
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
                <h5 class="modal-title">+ Edit Currency</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ route('finance.master-data.account.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" id="id_edit">
                            <div class="form-group">
                                <label>Account Type</label>
                                <select class="form-control select2 form-select"
                                    data-placeholder="Choose one" name="account_type_id" id="account_type_id_edit">
                                    @foreach ($accountTypes as $accountType)
                                        <option value="{{ $accountType->id }}">{{ $accountType->name }}</option>
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
                                <label>Type</label>
                                <select class="form-control select2 form-select"
                                    data-placeholder="Choose one" name="type_edit" disabled>
                                    <option value="header">Header</option>
                                    <option value="detail">Detail</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Parent</label>
                                <select class="form-control select2 form-select"
                                    data-placeholder="Choose one" name="parent_edit" disabled>
                                    @foreach ($headerAccounts as $account)
                                        <option {{ old('parent') == $account->id ? "selected" : "" }} value="{{ $account->id }}">{{ $account->code }} - {{ $account->account_name }}</option>
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
                            <label>Account Type</label>
                            <select class="form-control select2 form-select"
                                data-placeholder="Choose one" name="account_type_id" id="account_type_id_show" disabled>
                                @foreach ($accountTypes as $accountType)
                                    <option value="{{ $accountType->id }}">{{ $accountType->name }}</option>
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
                            <label>Type</label>
                            <select class="form-control select2 form-select"
                                data-placeholder="Choose one" name="type_show" disabled>
                                <option value="header">Header</option>
                                <option value="detail">Detail</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Parent</label>
                            <select class="form-control select2 form-select"
                                data-placeholder="Choose one" name="parent_show" disabled>
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
    $('select[name="parent"]').closest('.form-group').hide();
    $('select[name="parent"]').val("");

    // Show/hide Account Header field based on Account Type selection
    $('select[name="type"]').on('change', function() {
        if ($(this).val() === 'detail') {
            $('select[name="parent"]').closest('.form-group').show();
        } else {
            $('select[name="parent"]').val("");
            $('select[name="parent"]').closest('.form-group').hide();
        }
    });

    $('select[name="type_edit"]').on('change', function() {
        if ($(this).val() === 'detail') {
            $('select[name="parent_edit"]').closest('.form-group').show();
        } else {
            $('select[name="parent_edit"]').val("");
            $('select[name="parent_edit"]').closest('.form-group').hide();
        }
    });

    // On page load, trigger change to set initial state
    $(document).ready(function() {
        $('#account_type').trigger('change');
    });
    
    //create beginning balance

    $('body').on('click', '#btn-create-beginning-balance', function () {

        let id = $(this).data('id');
        var url = "{{ route('finance.master-data.account.show', ":id") }}";
        url = url.replace(':id', id);

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'GET',
            dataType: 'json',
            url: url,
            success:function(response){
                    // //fill data to form
                    $('#id_account').val(response.data.id);
                    $('#code_beginning_balance').val(response.data.code);
                    $('#account_name_beginning_balance').val(response.data.account_name);

                    $('#id_balance_account').val(response.data.id_balance_account);
                    $('#credit').val(response.data.credit);
                    $('#debit').val(response.data.debit);

                    $('#createBeginningBalance').modal('show');
                }
        });

    });

    //edit data
    $('body').on('click', '#btn-edit', function () {

        let id = $(this).data('id');
        var url = "{{ route('finance.master-data.account.edit', ":id") }}";
        url = url.replace(':id', id);

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'GET',
            dataType: 'json',
            url: url,
            success:function(response){
                    // //fill data to form
                    $('#id_edit').val(response.data.id);
                    $("#account_type_id_edit").val(response.data.account_type_id).change();
                    $('#code_edit').val(response.data.code);
                    $('#account_name_edit').val(response.data.account_name);
                    $("#type_show").val(response.data.type).change();

                    if (response.data.type == 'detail') {
                        $("#parent_show").show();
                        $("#parent_show").val(response.data.parent).change();
                    } else {
                        $("#parent_show").hide();
                    }

                    $("#master_currency_id_edit").val(response.data.master_currency_id).change();

                    $('#modal-edit').modal('show');
                }
        });
    });

    //show data
    $('body').on('click', '#btn-show', function () {

    let id = $(this).data('id');
    var url = "{{ route('finance.master-data.account.show', ":id") }}";
    url = url.replace(':id', id);

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
                    if (response.data.type == 'detail') {
                        $("#parent_show").show();
                        $("#parent_show").val(response.data.parent).change();
                    } else {
                        $("#parent_show").hide();
                    }

                    $('#credit_show').val(response.data.credit);
                    $('#debit_show').val(response.data.debit);

                    $('#modal-show').modal('show');
                }
        });
    });
    </script>
@endpush