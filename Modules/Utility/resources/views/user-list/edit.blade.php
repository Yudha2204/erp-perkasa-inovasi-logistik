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
            <h3>Edit User List</h3>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            @if(Session::has('error'))
                            <div class="alert alert-danger">
                            {{ Session::get('error')}}
                            </div>
                            @endif
                            <form action="{{ route('utility.user-list.update', $user->id) }}" method="post">
                                @csrf
                                @method("PUT")
                                <div class="row">
                                    <div class="col-4 form-group">
                                        <label class="form-label">Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ $user->name }}">
                                        @if ($errors->has('name'))
                                            <span class="text-danger">{{ $errors->first('name') }}</span>
                                        @endif
                                    </div>
                                    <div class="col-4 form-group">
                                        <label class="form-label">Username</label>
                                        <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ $user->username }}">
                                        @if ($errors->has('username'))
                                            <span class="text-danger">{{ $errors->first('username') }}</span>
                                        @endif
                                    </div>
                                    <div class="col-4 form-group">
                                        <label class="form-label">Department</label>
                                        <input type="text" class="form-control @error('department') is-invalid @enderror" id="department" name="department" value="{{ $user->department }}">
                                        @if ($errors->has('department'))
                                            <span class="text-danger">{{ $errors->first('department') }}</span>
                                        @endif
                                    </div>
                                    <div class="col-4 form-group">
                                        <label class="form-label">Password</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                                        @if ($errors->has('password'))
                                            <span class="text-danger">{{ $errors->first('password') }}</span>
                                        @endif
                                    </div>
                                    <div class="col-4 form-group">
                                        <label class="form-label">Level</label>
                                        <select class="form-select @error('roles') is-invalid @enderror" aria-label="Roles" id="roles" name="roles">
                                            @forelse ($roles as $role)
            
                                                @if ($role!='Super Admin')
                                                <option value="{{ $role }}" {{ in_array($role, $userRoles ?? []) ? 'selected' : '' }}>
                                                    {{ $role }}
                                                </option>
                                                @else
                                                    @if (Auth::user()->hasRole('Super Admin'))   
                                                    <option value="{{ $role }}" {{ in_array($role, $userRoles ?? []) ? 'selected' : '' }}>
                                                        {{ $role }}
                                                    </option>
                                                    @endif
                                                @endif
            
                                            @empty
            
                                            @endforelse
                                        </select>
                                        @if ($errors->has('roles'))
                                            <span class="text-danger">{{ $errors->first('roles') }}</span>
                                        @endif
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