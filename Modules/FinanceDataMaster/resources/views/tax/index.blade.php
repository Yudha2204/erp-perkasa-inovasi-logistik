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
                <h1>Tax Data</h1>
            </div>
            <!-- PAGE-HEADER END -->

            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="d-flex d-inline">
                        <form action="{{ route('finance.master-data.tax.index') }}">
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
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>Account</th>
                                            <th>Tax Rate</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($taxes as $key => $t)
                                            <tr>
                                                <td>{{ $taxes->firstItem() + $key }}</td>
                                                <td>{{ $t->code }}</td>
                                                <td>{{ $t->name }}</td>
                                                <td>
                                                    @if ($t->type == 'PPN')
                                                        <span class="badge bg-info badge-sm me-1 mb-1 mt-1">PPN</span>
                                                    @else
                                                        <span class="badge bg-warning badge-sm me-1 mb-1 mt-1">PPH</span>
                                                    @endif
                                                </td>
                                                <td>{{ $t->account ? $t->account->account_name : '-' }}</td>
                                                <td>{{ $t->tax_rate }}</td>
                                                <td>
                                                    @if ($t->status == 1)
                                                    <span class="badge bg-success badge-sm  me-1 mb-1 mt-1">Active</span>
                                                    @else
                                                    <span class="badge bg-danger badge-sm  me-1 mb-1 mt-1">Non Active</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="g-2">
                                                        <a href="javascript:void(0)" id="btn-show" data-id="{{ $t->id }}" class="btn text-primary btn-sm"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-original-title="Detail"><span
                                                            class="fe fe-eye fs-14"></span></a>
                                                        <a href="javascript:void(0)" id="btn-edit" data-id="{{ $t->id }}" class="btn text-primary btn-sm"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-original-title="Edit"><span
                                                                class="fe fe-edit fs-14"></span></a>
                                                        <a href="#" class="btn text-danger btn-sm"
                                                                data-bs-toggle="tooltip"
                                                                data-bs-original-title="Delete" onclick="if (confirm('Are you sure want to delete this item?')) {
                                                                            event.preventDefault();
                                                                            document.getElementById('delete-{{$t->id}}').submit();
                                                                        }else{
                                                                            event.preventDefault();
                                                                        }">
                                                                    <span class="fe fe-trash-2 fs-14"></span>
                                                                </a>
                                                        <form id="delete-{{$t->id}}" action="{{route('finance.master-data.tax.destroy',$t->id)}}" style="display: none;" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                        <td colspan="8">
                                            <span class="text-danger">
                                                <strong>Data is Empty</strong>
                                            </span>
                                        </td>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            {{  $taxes->appends(request()->input())->links()}}
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
                <h5 class="modal-title">+ Add Tax</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ route('finance.master-data.tax.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" value="{{ old('name') }}" id="name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Code</label>
                                <input type="text" name="code" value="{{ old('code') }}" id="code" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Type</label>
                                <select class="form-control select2 form-select" name="type" data-placeholder="Choose Type">
                                    <option value="PPH" {{ old('type') == 'PPH' ? "selected" : "" }}>PPH</option>
                                    <option value="PPN" {{ old('type') == 'PPN' ? "selected" : "" }}>PPN</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Tax Rate</label>
                                <input type="text" name="tax_rate" value="{{ old('tax_rate') }}" id="tax_rate" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control select2 form-select"
                                    data-placeholder="Choose one" name="status">
                                    <option {{ old('status') == 1 ? "selected" : "" }} value="1">Active</option>
                                    <option {{ old('status') == 2 ? "selected" : "" }} value="2">Non Active</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Account</label>
                                <select class="form-control select2 form-select" name="account_id" data-placeholder="Choose Account">
                                    <option value="">None</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}" {{ old('account_id') == $account->id ? "selected" : "" }}>{{ $account->account_name }}</option>
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
                <h5 class="modal-title">+ Edit Tax</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ route('finance.master-data.tax.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" id="id_edit">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" id="name_edit" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Code</label>
                                <input type="text" name="code" id="code_edit" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Type</label>
                                <select class="form-control select2 form-select" name="type" id="type_edit" data-placeholder="Choose Type">
                                    <option value="PPH">PPH</option>
                                    <option value="PPN">PPN</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Tax Rate</label>
                                <input type="text" name="tax_rate" id="tax_rate_edit" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control select2 form-select"
                                    data-placeholder="Choose one" name="status" id="status_edit">
                                    <option value="1">Active</option>
                                    <option value="2">Non Active</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Account</label>
                                <select class="form-control select2 form-select" name="account_id" id="account_id_edit" data-placeholder="Choose Account">
                                    <option value="">None</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{ $account->id }}" {{ old('account_id') == $account->id ? "selected" : "" }}>{{ $account->account_name }}</option>
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
                <h5 class="modal-title"> Detail Tax</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" id="name_show" class="form-control" disabled>
                        </div>
                        <div class="form-group">
                            <label>Code</label>
                            <input type="text" name="code" id="code_show" class="form-control" disabled>
                        </div>
                        <div class="form-group">
                            <label>Type</label>
                            <input type="text" name="type" id="type_show" class="form-control" disabled>
                        </div>
                        <div class="form-group">
                            <label>Tax Rate</label>
                            <input type="text" name="tax_rate" id="tax_rate_show" class="form-control" disabled>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select class="form-control select2 form-select"
                                data-placeholder="Choose one" name="status" id="status_show" disabled>
                                <option value="1">Active</option>
                                <option value="2">Non Active</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Account</label>
                            <input type="text" name="account" id="account_show" class="form-control" disabled>
                        </div>
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
    //edit data
    $('body').on('click', '#btn-edit', function () {

        let id = $(this).data('id');
        var url = "{{ route('finance.master-data.tax.edit', ':id') }}";
        url = url.replace(':id', id);

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'GET',
            dataType: 'json',
            url: url,
            success:function(response){
                    // //fill data to form
                    $('#id_edit').val(response.data.id);
                    $('#code_edit').val(response.data.code);
                    $('#name_edit').val(response.data.name);
                    $('#type_edit').val(response.data.type).change();
                    $('#tax_rate_edit').val(response.data.tax_rate);
                    $("#status_edit").val(response.data.status).change();
                    $('#account_id_edit').val(response.data.account_id || '').change();

                    $('#modal-edit').modal('show');
                }
        });
    });

    //show data
    $('body').on('click', '#btn-show', function () {

    let id = $(this).data('id');
    var url = "{{ route('finance.master-data.tax.show', ':id') }}";
    url = url.replace(':id', id);

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'GET',
            dataType: 'json',
            url: url,
            success:function(response){
                    // //fill data to form
                    $('#code_show').val(response.data.code);
                    $('#name_show').val(response.data.name);
                    $('#type_show').val(response.data.type);
                    $('#tax_rate_show').val(response.data.tax_rate);
                    $("#status_show").val(response.data.status).change();
                    $('#account_show').val(response.data.account ? response.data.account.account_name : 'None');

                    $('#modal-show').modal('show');
                }
        });
    });
    </script>
@endpush