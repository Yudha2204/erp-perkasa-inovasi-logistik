@extends('layouts.app')
@section('content')

<div class="main-content app-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <!-- PAGE-HEADER -->
            <div class="page-header mb-0">
                <h1>Process Management</h1>
                <p class="text-muted">Choose your preferred interface</p>
            </div>
            <!-- PAGE-HEADER END -->

            <!-- Interface Selection -->
            <div class="row">
                <div class="col-md-6">
                    <a href="{{ route('process.combined') }}">
                        <div class="card text-white card-transparent" style="background-color: #007bff;">
                            <div class="card-body" style="text-align: center">
                                <i class="fe fe-layers" style="font-size: 4rem; margin-bottom: 1rem;"></i>
                                <h4 class="card-title mt-3">Combined Process Interface</h4>
                                <p class="card-text">Execute multiple processes at once with a unified interface</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body" style="text-align: center">
                            <h5 class="card-title">Individual Process Pages</h5>
                            <p class="card-text">Access individual process pages for specific operations</p>
                            <div class="row mt-3">
                                <div class="col-4">
                                    <a href="{{ route('process.exchange-revaluation.index') }}" class="btn btn-outline-success btn-sm">
                                        <i class="fe fe-dollar-sign"></i><br>Exchange<br>Revaluation
                                    </a>
                                </div>
                                <div class="col-4">
                                    <a href="{{ route('process.pl-closing.index') }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fe fe-trending-up"></i><br>P&L<br>Closing
                                    </a>
                                </div>
                                <div class="col-4">
                                    <a href="{{ route('process.annual-pl-closing.index') }}" class="btn btn-outline-warning btn-sm">
                                        <i class="fe fe-calendar"></i><br>Annual<br>P&L Closing
                                    </a>
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
