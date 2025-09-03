@extends('layouts.app')
@section('content')

<div class="main-content app-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <!-- PAGE-HEADER -->
            <div class="page-header">
                <h1>Kas In/Out</h1>
            </div>
            <!-- PAGE-HEADER END -->

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <a href="{{ route('finance.kas.penerimaan.index') }}">
                                        <div class="card text-white card-transparent" style="background-color: #59B1C3;">
                                            <div class="card-body" style="text-align: center">
                                                <img src="{{ url('assets/images/icon/penerimaan-kas.png') }}" alt="">
                                                <h4 class="card-title mt-2">Cash & Bank In</h4>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="{{ route('finance.kas.pembayaran.index') }}">
                                        <div class="card text-white card-transparent" style="background-color: #EAAD52;">
                                            <div class="card-body" style="text-align: center">
                                                <img src="{{ url('assets/images/icon/pengeluaran-kas.png') }}" alt="">
                                                <h4 class="card-title mt-2">Cash & Bank Out</h4>
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
        <!-- CONTAINER CLOSED -->
    </div>
</div>

@endsection