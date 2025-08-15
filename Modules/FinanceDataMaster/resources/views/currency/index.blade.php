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
                <h1>Currency Data</h1>
            </div>
            <!-- PAGE-HEADER END -->

            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="d-flex d-inline">
                        <form action="{{ route('finance.master-data.currency.index') }}">
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
                                            <th>Initial</th>
                                            <th>Currency Name</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($currencies as $key => $c)
                                            <tr>
                                                <td>{{ $currencies->firstItem() + $key }}</td>
                                                <td>{{ $c->initial }}</td>
                                                <td>{{ $c->currency_name }}</td>
                                                <td>
                                                    <div class="g-2">
                                                        <a href="javascript:void(0)" id="btn-edit" data-id="{{ $c->id }}" class="btn text-primary btn-sm"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-original-title="Edit"><span
                                                                class="fe fe-edit fs-14"></span></a>
                                                        @if ($c->can_delete == 1 )
                                                            <a href="#" class="btn text-danger btn-sm"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-original-title="Delete" onclick="if (confirm('Are you sure want to delete this item?')) {
                                                                                event.preventDefault();
                                                                                document.getElementById('delete-{{$c->id}}').submit();
                                                                            }else{
                                                                                event.preventDefault();
                                                                            }">
                                                                        <span class="fe fe-trash-2 fs-14"></span>
                                                                    </a>
                                                            <form id="delete-{{$c->id}}" action="{{route('finance.master-data.currency.destroy',$c->id)}}" style="display: none;" method="POST">
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
                            {{  $currencies->appends(request()->input())->links()}}
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
                <h5 class="modal-title">+ Add Currency</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ route('finance.master-data.currency.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label>Initial</label>
                                <input type="text" name="initial" value="{{ old('initial') }}" id="initial" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Currency Name</label>
                                <input type="text" name="currency_name" value="{{ old('currency_name') }}" id="currency_name" class="form-control">
                            </div>
                            <div class="form-wrapper-bank" id="form-wrapper-bank-create">
                                <div class="form-container">
                                    <div class="form-group">
                                        <label>Fund Transfer - Account No</label>
                                        <input type="text" name="account_fund[]" value="{{ old('account_fund.0') }}" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Bank Name</label>
                                        <input type="text" name="bank_name[]" value="{{ old('bank_name.0') }}" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Address</label>
                                        <input type="text" name="address[]" value="{{ old('address.0') }}" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Swift Code</label>
                                        <input type="text" name="swift_code[]" value="{{ old('swift_code.0') }}" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <a href="javascript:void(0)" class="btn btn-default add-form" style="width: 100%; display: none">
                                    <span><i class="fa fa-plus"></i></span> Add New Bank
                                </a>
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
                        <form action="{{ route('finance.master-data.currency.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" id="id_edit">
                            <div class="form-group">
                                <label>Initial</label>
                                <input type="text" name="initial" id="initial_edit" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Currency Name</label>
                                <input type="text" name="currency_name" id="currency_name_edit" class="form-control">
                            </div>
                            <div class="form-wrapper-bank" id="form-wrapper-bank-edit">
                            </div>
                            <div class="form-group">
                                <a href="javascript:void(0)" class="btn btn-default add-form" style="width: 100%; display:none">
                                    <span><i class="fa fa-plus"></i></span> Add New Bank
                                </a>
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
                <h5 class="modal-title"> Detail Currency</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Initial</label>
                            <input type="text" name="initial" id="initial_show" class="form-control" disabled>
                        </div>
                        <div class="form-group">
                            <label>Currency Name</label>
                            <input type="text" name="currency_name" id="currency_name_show" class="form-control" disabled>
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
        $('#form-wrapper-bank-edit').html('')
        let id = $(this).data('id');
        var url = "{{ route('finance.master-data.currency.edit', ":id") }}";
        url = url.replace(':id', id);

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'GET',
            dataType: 'json',
            url: url,
            success:function(response){
                    // //fill data to form
                    $('#id_edit').val(response.data.id);
                    $('#initial_edit').val(response.data.initial);
                    $('#currency_name_edit').val(response.data.currency_name);

                    for(let data of response.data.banks) {
                        var newForm = `
                        <div class="form-container">
                            <div class="form-group">
                                <label>Fund Transfer - Account No</label>
                                <input type="text" name="account_fund[]" value="${data.account_no}" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Bank Name</label>
                                <input type="text" name="bank_name[]" value="${data.bank_name}" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <input type="text" name="address[]" value="${data.address}" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Swift Code</label>
                                <input type="text" name="swift_code[]" value="${data.swift_code}" class="form-control">
                            </div>
                            <input type="text" hidden name="bank_id[]" value="${data.id}">
                        </div>
                        `
                        $('#form-wrapper-bank-edit').append(newForm);
                    }
if(!response.data.banks || response.data.banks.length === 0) {
var newForm = `
                        <div class="form-container">
                            <div class="form-group">
                                <label>Fund Transfer - Account No</label>
                                <input type="text" name="account_fund[]" value="" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Bank Name</label>
                                <input type="text" name="bank_name[]" value="" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <input type="text" name="address[]" value="" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Swift Code</label>
                                <input type="text" name="swift_code[]" value="" class="form-control">
                            </div>
                        </div>
                        `
                        $('#form-wrapper-bank-edit').append(newForm);
}
                    $('#modal-edit').modal('show');
                }
        });
    });

    //show data
    $('body').on('click', '#btn-show', function () {

    let id = $(this).data('id');
    var url = "{{ route('finance.master-data.currency.show', ":id") }}";
    url = url.replace(':id', id);

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'GET',
            dataType: 'json',
            url: url,
            success:function(response){
                    // //fill data to form
                    $('#initial_show').val(response.data.initial);
                    $('#currency_name_show').val(response.data.currency_name);

                    $('#modal-show').modal('show');
                }
        });
    });

    $('.add-form').on('click', function () {
        const formWrapperId = $(this).closest('.modal-body').find('.form-wrapper-bank').attr('id');
        const formIndex = $('#' + formWrapperId + ' .form-container').length;
        oldAccountFund = @json(old('account_fund', []));
        oldBankName = @json(old('bank_name', []));
        oldAddress = @json(old('address', []));
        oldSwiftCode = @json(old('swift_code', []));

        var newForm = `
            <div class="form-container">
                <div class="form-group">
                    <label>Fund Transfer - Account No</label>
                    <input type="text" name="account_fund[]" value="${oldAccountFund[formIndex] || ''}" class="form-control">
                </div>
                <div class="form-group">
                    <label>Bank Name</label>
                    <input type="text" name="bank_name[]" value="${oldBankName[formIndex] || ''}" class="form-control">
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <input type="text" name="address[]" value="${oldAddress[formIndex] || ''}" class="form-control">
                </div>
                <div class="form-group">
                    <label>Swift Code</label>
                    <input type="text" name="swift_code[]" value="${oldSwiftCode[formIndex] || ''}" class="form-control">
                </div>
            </div>
        `;

        $('#' + formWrapperId).append(newForm);
    })
    </script>
@endpush