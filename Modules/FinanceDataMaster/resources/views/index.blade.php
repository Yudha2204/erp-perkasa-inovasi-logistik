@extends('layouts.app')
@section('content')

<div class="main-content app-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <!-- PAGE-HEADER -->
            <div class="page-header">
                <h1>Data Master</h1>
            </div>
            <!-- PAGE-HEADER END -->

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <a href="{{ route('finance.master-data.contact.index') }}">
                                        <div class="card text-white card-transparent" style="background-color: #2EB2EC;">
                                            <div class="card-body" style="text-align: center">
                                                <img src="{{ url('assets/images/icon/contact.png') }}" alt="">
                                                <h4 class="card-title mt-2">Contact Data</h4>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="{{ route('finance.master-data.account.index') }}">
                                        <div class="card text-white card-transparent" style="background-color: #F56A3B;">
                                            <div class="card-body" style="text-align: center">
                                                <img src="{{ url('assets/images/icon/akun.png') }}" alt="">
                                                <h4 class="card-title mt-2">Account Data</h4>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="{{ route('finance.master-data.currency.index') }}">
                                        <div class="card text-white card-transparent" style="background-color: #5581AD;">
                                            <div class="card-body" style="text-align: center">
                                                <img src="{{ url('assets/images/icon/currency.png') }}" alt="">
                                                <h4 class="card-title mt-2">Currency Data</h4>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="{{ route('finance.master-data.tax.index') }}">
                                        <div class="card text-white card-transparent" style="background-color: #4AB4C5;">
                                            <div class="card-body" style="text-align: center">
                                                <img src="{{ url('assets/images/icon/tax.png') }}" alt="">
                                                <h4 class="card-title mt-2">Tax Data</h4>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <a href="{{ route('finance.master-data.term-of-payment.index') }}">
                                        <div class="card text-white card-transparent" style="background-color: #54B856;">
                                            <div class="card-body" style="text-align: center">
                                                <img src="{{ url('assets/images/icon/term_payment.png') }}" alt="">
                                                <h4 class="card-title mt-2">Term Of Payment</h4>
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