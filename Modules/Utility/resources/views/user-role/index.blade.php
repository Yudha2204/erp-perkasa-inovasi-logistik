@extends('layouts.app')
@section('content')

<div class="main-content app-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <!-- PAGE-HEADER -->
            <div class="page-header mb-0">
                <h1>Utility</h1>
            </div>
            <!-- PAGE-HEADER END -->
            <h3>User Role</h3>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="d-flex d-inline">
                        <form action="{{ route('utility.user-role.index') }}">
                            <input type="text" name="search" id="search" value="{{ Request::get('search') }}" class="form-control" placeholder="Searching.....">
                        </form>
                        &nbsp;&nbsp;
                        <a class="modal-effect btn btn-primary" data-bs-effect="effect-scale" data-bs-toggle="modal" href="#modaldemo8"><i class="fe fe-plus me-2"></i>Add New</a>
                    
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table text-nowrap text-md-nowrap mb-0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Level</th>
                                            <th style="width: 5%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($roles as $role)
                                            <tr>
                                                <th scope="row">{{ $loop->iteration }}</th>
                                                <td>{{ $role->name }}</td>
                                                <td>
                                                    <form action="{{ route('utility.user-role.destroy', $role->id) }}" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                
                                                    <a class="btn text-primary btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-effect="effect-scale"
                                                    data-id="{{ $role->id }}"
                                                    href="#modal-permission"><span
                                                        class="fe fe-shield fs-14"></span></a>

                                                    @if ($role->name!='Super Admin')
                                                        @can('edit-role@role')
                                                        <a href="javascript:void(0)" id="btn-edit" data-id="{{ $role->id }}" class="btn text-primary btn-sm"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-original-title="Edit"><span
                                                                class="fe fe-edit fs-14"></span></a>
                                                        @endcan
                        
                                                        @can('delete-role@role')
                                                            @if ($role->name != Auth::user()->hasRole($role->name))
                                                                 <button type="submit" class="btn text-danger btn-sm" onclick="return confirm('Do you want to delete this Level?');"><i class="fe fe-trash-2 fs-14"></i></button>
                                                            @endif
                                                        @endcan
                                                    @endif
                                                </form>
                                                </td>
                                            </tr>
                                            
                                        @empty
                                        <td colspan="2" style="text-align: center">
                                            <span class="text-danger">
                                                <strong>Data is Empty</strong>
                                            </span>
                                        </td>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- CONTAINER CLOSED -->
    </div>
</div>


{{-- modal --}}

<div class="modal fade" id="modaldemo8">
    <div class="modal-dialog modal-dialog-centered text-center" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">Add New User Role</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            </div>
            <form action="{{ route('utility.user-role.store') }}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label" style="text-align: left">Level</label>
                        <input type="text" class="form-control" name="name" id="name" placeholder="fill the text" autofocus required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">Save</button> 
                    <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- modal edit --}}
<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit User Role</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ route('utility.user-role.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" id="id_edit">
                            <div class="form-group">
                                <label>Level</label>
                                <input type="text" name="name" id="name_edit" class="form-control">
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

{{-- modal permission --}}
<div class="modal fade" id="modal-permission" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Set Permissions</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{ route('utility.user-utility.store') }}" method="POST">
            @csrf
            <input hidden name="role_id"></input>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Tab buttons based on permission groups -->
                        <div class="d-flex justify-content-center align-items-center gap-4" id="tabButtons">
                            @foreach($permissions->groupBy('group') as $group => $groupPermissions)
                                <button type="button" class="btn btn-outline-primary tab-button" data-group="{{ $group }}" style="padding: 5px 10px;" onclick="toggleGroup('{{ $group }}')">{{ ucwords(str_replace('_', ' ', $group)) }}</button>
                            @endforeach
                        </div>

                        <!-- Tab content based on permission groups -->
                        <div id="tabContent" class="mt-3">
                            @foreach($permissions->groupBy('group') as $group => $groupPermissions)
                                <div id="{{ $group }}Content" class="group-content tab-pane fade">
                                    @php
                                        $groupedPermissions = $groupPermissions->groupBy(function ($permission) {
                                            return explode('@', explode('-', $permission->name)[1])[0];
                                        });
                                    @endphp
                                    @foreach($groupedPermissions as $name => $permissions)
                                        <div class="row mb-2">
                                            <div class="col-3">
                                                <p>{{ ucwords(str_replace('_', ' ', $name)) }}</p>
                                            </div>
                                            <div class="col-9">
                                                @foreach(['view', 'edit', 'create', 'delete'] as $action)
                                                    @php
                                                        $permission = $permissions->firstWhere('name', $action . '-' . $name . '@' . $group);
                                                    @endphp
                                                    @if($permission)
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="checkbox" id="{{ $permission->name }}" name="permissions[]" value="{{ $permission->name }}">
                                                            <label class="form-check-label" for="{{ $permission->name }}">
                                                                {{ ucfirst($action) }}
                                                            </label>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
            </form>
        </div>
    </div>
</div>

@endsection
@push('scripts')
    <script>
    //edit data
    $('body').on('click', '#btn-edit', function () {

        let id = $(this).data('id');
        var url = "{{ route('utility.user-role.edit', ":id") }}";
        url = url.replace(':id', id);

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'GET',
            dataType: 'json',
            url: url,
            success:function(response){
                    // //fill data to form
                    $('#id_edit').val(response.data.id);
                    $('#name_edit').val(response.data.name);

                    $('#modal-edit').modal('show');
                }
        });
    });

    function toggleGroup(group) {
        document.querySelectorAll('.group-content').forEach(function(element) {
            element.style.display = 'none';
            element.classList.remove('show', 'active');
        });
        document.querySelectorAll('.tab-button').forEach(function(button) {
            button.classList.remove('active');
        });

        const selectedContent = document.getElementById(group + 'Content');
        selectedContent.classList.add('show', 'active');
        document.querySelector(`.tab-button[data-group="${group}"]`).classList.add('active');
        document.getElementById(group + 'Content').style.display = 'block';
    }

    document.addEventListener('DOMContentLoaded', function() {
        const firstGroupButton = document.querySelector('#tabButtons button');
        if (firstGroupButton) {
            toggleGroup(firstGroupButton.getAttribute('onclick').split("'")[1]);
        }
    });

    $('a[href="#modal-permission"]').click(function() {
        var roleId = $(this).data('id');
        $('input[name="role_id"]').val(roleId);

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'GET',
            dataType: 'json',
            url: "{{ route('utility.user-permissions') }}",
            data: { "role_id": roleId },
            success:function(response){
                    if(response.message === "Success") {
                        let modal = $('#modal-permission');
                        if(modal.length > 0) {
                            modal.find('input[type="checkbox"]').each(function() {
                                let checkbox = $(this);
                                let permissionName = checkbox.val();
                                if(response.data.some(permission => permission.name === permissionName)) {
                                    checkbox.prop('checked', true);
                                } else {
                                    checkbox.prop('checked', false);
                                }
                            })
                        }
                    }
                }
        });
    });
    </script>
@endpush