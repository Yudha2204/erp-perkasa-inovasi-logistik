@extends('layouts.app')

@section('content')

<div class="main-content app-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">

            <!-- PAGE-HEADER -->
            <div class="page-header">
                <h1>General Ledger</h1>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <a href="{{ route('generalledger.general-journal.index') }}">
                                        <div class="card text-white card-transparent" style="background-color: #59B1C3;">
                                            <div class="card-body" style="text-align: center">
                                                <img src="{{ url('assets/images/icon/penerimaan-kas.png') }}" alt="">
                                                <h4 class="card-title mt-2">General Journal</h4>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
