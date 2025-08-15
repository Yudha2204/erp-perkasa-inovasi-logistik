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
            <h3>Add New User List</h3>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            @if(Session::has('error'))
                            <div class="alert alert-danger">
                            {{ Session::get('error')}}
                            </div>
                            @endif
                            <form action="{{ route('utility.user-list.store') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-4 form-group">
                                        <label class="form-label">Name</label>
                                        <input type="text" class="form-control" name="name" id="name" autofocus required>
                                    </div>
                                    <div class="col-4 form-group">
                                        <label class="form-label">Username</label>
                                        <input type="text" class="form-control" name="username" id="name" required>
                                    </div>
                                    <div class="col-4 form-group">
                                        <label class="form-label">Department</label>
                                        <input type="text" class="form-control" name="department" id="department" required>
                                    </div>
                                    <div class="col-4 form-group">
                                        <label class="form-label">Password</label>
                                        <input type="password" class="form-control" name="password" id="password" required>
                                    </div>
                                    <div class="col-4 form-group">
                                        <label class="form-label">Level</label>
                                        <select name="roles" required class="form-control form-select" data-bs-placeholder="Select Country">
                                            @forelse ($roles as $role)
    
                                            @if ($role!='Super Admin')
                                                <option value="{{ $role }}" {{ in_array($role, old('roles') ?? []) ? 'selected' : '' }}>
                                                {{ $role }}
                                                </option>
                                            @else
                                                @if (Auth::user()->hasRole('Super Admin'))   
                                                    <option value="{{ $role }}" {{ in_array($role, old('roles') ?? []) ? 'selected' : '' }}>
                                                    {{ $role }}
                                                    </option>
                                                @endif
                                            @endif
        
                                        @empty
        
                                        @endforelse
    
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <div class="btn-list text-end">
                                                <a href="{{ route('utility.user-list.index') }}" class="btn btn-default">Cancel</a>
                                                <button type="submit" class="btn btn-primary">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- CONTAINER CLOSED -->
    </div>
</div>

@endsection