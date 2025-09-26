@extends('layouts.app')
@section('content')

<div class="main-content app-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <!-- PAGE-HEADER -->
            <div class="page-header mb-0">
                <h1>Process</h1>
            </div>
            <!-- PAGE-HEADER END -->

            <!-- Process Menu (Cards) -->
            <div class="row">
                <div class="col-md-4">
                    <a href="{{ route('process.exchange-revaluation.index') }}">
                        <div class="card text-white card-transparent" style="background-color: #6FB662;">
                            <div class="card-body" style="text-align: center">
                                <img width="135" height="157" src="{{ url('assets/images/icon/dollar.png') }}" alt="">
                                <h4 class="card-title mt-5">Exchange Revaluation</h4>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('process.pl-closing.index') }}">
                        <div class="card text-white card-transparent" style="background-color: #8E79D6;">
                            <div class="card-body" style="text-align: center">
                                <img width="157" height="157" src="{{ url('assets/images/icon/profits.png') }}" alt="">
                                <h4 class="card-title mt-5">Profit & Loss Closing</h4>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4">
                    <a href="{{ route('process.annual-pl-closing.index') }}">
                        <div class="card text-white card-transparent" style="background-color: #8E79D6;">
                            <div class="card-body" style="text-align: center">
                                <img width="157" height="157" src="{{ url('assets/images/icon/profits.png') }}" alt="">
                                <h4 class="card-title mt-5">Annual Profit & Loss Closing</h4>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

        </div>
        <!-- CONTAINER CLOSED -->
    </div>
</div>

@endsection
