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
            <h3>Detail User List</h3>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            @if(Session::has('error'))
                            <div class="alert alert-danger">
                            {{ Session::get('error')}}
                            </div>
                            @endif
                                <div class="row">
                                    <div class="col-4 form-group">
                                        <label class="form-label">Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ $user->name }}" disabled>
                                        @if ($errors->has('name'))
                                            <span class="text-danger">{{ $errors->first('name') }}</span>
                                        @endif
                                    </div>
                                    <div class="col-4 form-group">
                                        <label class="form-label">Username</label>
                                        <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ $user->username }}" disabled>
                                        @if ($errors->has('username'))
                                            <span class="text-danger">{{ $errors->first('username') }}</span>
                                        @endif
                                    </div>
                                    <div class="col-4 form-group">
                                        <label class="form-label">Department</label>
                                        <input type="text" class="form-control @error('department') is-invalid @enderror" id="department" name="department" value="{{ $user->department }}" disabled>
                                        @if ($errors->has('department'))
                                            <span class="text-danger">{{ $errors->first('department') }}</span>
                                        @endif
                                    </div>
                                    <div class="col-4 form-group">
                                        <label class="form-label">Level</label>
                                        <select class="form-select @error('roles') is-invalid @enderror" aria-label="Roles" id="roles" name="roles" disabled>
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
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <div class="btn-list text-end">
                                                <a href="{{ route('utility.user-list.index') }}" class="btn btn-default">Cancel</a>
                                            </div>
                                        </div>
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

@endsection