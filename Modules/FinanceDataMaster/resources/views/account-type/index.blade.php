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
                <h1>Account Type</h1>
            </div>
            <!-- PAGE-HEADER END -->

            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="d-flex d-inline">
                        <form action="{{ route('finance.master-data.account-type.index') }}">
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
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($accountTypes as $key => $a)
                                            <tr>
                                                <td>{{ $accountTypes->firstItem() + $key }}</td>
                                                <td>{{ $a->code }}</td>
                                                <td>{{ $a->name }}</td>
                                                <td>
                                                    <div class="g-2">
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
                                                            <form id="delete-{{$a->id}}" action="{{route('finance.master-data.account-type.destroy',$a->id)}}" style="display: none;" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                            </form>    
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                        <td colspan="7">
                                            <span class="text-danger">
                                                <strong>Data is Empty</strong>
                                            </span>
                                        </td>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            {{  $accountTypes->appends(request()->input())->links()}}
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
                <h5 class="modal-title">+ Add Account Type</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ route('finance.master-data.account-type.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Classification</label>
                                <select class="form-control select2 form-select"
                                    data-placeholder="Choose one" name="classification_id">
                                    @foreach ($classifications as $classification)
                                        <option {{ old('classification_id') == $classification->id ? "selected" : "" }} value="{{ $classification->id }}">{{ $classification->code }} - {{ $classification->classification }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Code</label>
                                <input type="text" name="code" value="{{ old('code') }}" id="code" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" value="{{ old('name') }}" id="name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Cash Flow</label>
                                <select class="form-control select2 form-select"
                                    data-placeholder="Choose one" name="cash_flow">
                                    <option {{ old('cash_flow') == 0 ? "selected" : "" }} value="0">Undefined</option>
                                    <option {{ old('cash_flow') == 1 ? "selected" : "" }} value="1">Operation Activities</option>
                                    <option {{ old('cash_flow') == 2 ? "selected" : "" }} value="2">Investing Activities</option>
                                    <option {{ old('cash_flow') == 3 ? "selected" : "" }} value="3">Financing Activities</option>
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
                <h5 class="modal-title">+ Edit Account Type</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ route('finance.master-data.account-type.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" id="id_edit">
                            <div class="form-group">
                                <label>Classification</label>
                                <select class="form-control select2 form-select"
                                    data-placeholder="Choose one" name="classification_id" id="classification_id_edit">
                                    @foreach ($classifications as $classification)
                                        <option value="{{ $classification->id }}">{{ $classification->code }} - {{ $classification->classification }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Code</label>
                                <input type="text" name="code" id="code_edit" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" id="name_edit" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Cash Flow</label>
                                <select class="form-control select2 form-select"
                                    data-placeholder="Choose one" name="cash_flow" id="cash_flow_edit">
                                    <option value="0">Undefined</option>
                                    <option value="1">Operation Activities</option>
                                    <option value="2">Investing Activities</option>
                                    <option value="3">Financing Activities</option>
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
                            <label>Classification</label>
                            <select class="form-control select2 form-select"
                                data-placeholder="Choose one" name="classification_id" id="classification_id_show" disabled>
                                @foreach ($classifications as $classification)
                                    <option value="{{ $classification->id }}">{{ $classification->code }} - {{ $classification->classification }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Code</label>
                            <input type="text" name="code" id="code_show" class="form-control" disabled>
                        </div>
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" id="name_show" class="form-control" disabled>
                        </div>
                        <div class="form-group">
                            <label>Cash Flow</label>
                            <select class="form-control select2 form-select"
                                data-placeholder="Choose one" name="cash_flow" id="cash_flow_show" disabled>
                                <option value="0">Undefined</option>
                                <option value="1">Operation Activities</option>
                                <option value="2">Investing Activities</option>
                                <option value="3">Financing Activities</option>
                            </select>
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
        var url = "{{ route('finance.master-data.account-type.edit', ":id") }}";
        url = url.replace(':id', id);

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'GET',
            dataType: 'json',
            url: url,
            success:function(response){
                    // //fill data to form
                    $('#id_edit').val(response.data.id);
                    $("#classification_id_edit").val(response.data.classification_id).change();
                    $('#code_edit').val(response.data.code);
                    $('#name_edit').val(response.data.name);
                    $("#cash_flow_edit").val(response.data.cash_flow).change();

                    $('#modal-edit').modal('show');
                }
        });
    });

    //show data
    $('body').on('click', '#btn-show', function () {

    let id = $(this).data('id');
    var url = "{{ route('finance.master-data.account-type.show', ":id") }}";
    url = url.replace(':id', id);

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'GET',
            dataType: 'json',
            url: url,
            success:function(response){
                    // //fill data to form
                    $("#classification_id_show").val(response.data.classification_id).change();
                    $('#code_show').val(response.data.code);
                    $('#name_show').val(response.data.name);
                    $("#cash_flow_show").val(response.data.cash_flow).change();

                    $('#modal-show').modal('show');
                }
        });
    });
    </script>
@endpush