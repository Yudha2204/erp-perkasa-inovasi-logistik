@extends('layouts.app')
@section('content')
<div class="main-content app-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <!-- PAGE-HEADER -->
            <div class="page-header">
                <h1>Laporan</h1>
            </div>
            <!-- PAGE-HEADER END -->

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                @canany(['view-buku_besar@finance'])
                                <div class="col-md-4">
                                    <a data-bs-effect="effect-scale" data-bs-toggle="modal" href="#modal-buku-besar-format">
                                        <div class="card text-white card-transparent" style="background-color: #596AC3;">
                                            <div class="card-body" style="text-align: center">
                                                <img src="{{ url('assets/images/icon/buku%20besar.png') }}" alt="Buku Besar">
                                                <h4 class="card-title mt-2">Buku Besar</h4>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endcanany
                                @canany(['view-jurnal_umum@finance'])
                                <div class="col-md-4">
                                    <a data-bs-effect="effect-scale" data-bs-toggle="modal" href="#modal-jurnal-umum-format">
                                        <div class="card text-white card-transparent" style="background-color: #59B1C3;">
                                            <div class="card-body" style="text-align: center">
                                                <img src="{{ url('assets/images/icon/buku%20besar.png') }}" alt="Buku Besar">
                                                <h4 class="card-title mt-2">Jurnal Umum</h4>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endcanany
                                @canany(['view-neraca_saldo@finance'])
                                <div class="col-md-4">
                                    <a data-bs-effect="effect-scale" data-bs-toggle="modal" href="#modal-neraca-format">
                                        <div class="card text-white card-transparent" style="background-color: #D95B4C;">
                                            <div class="card-body" style="text-align: center">
                                                <img src="{{ url('assets/images/icon/receive-payment.png') }}" alt="">
                                                <h4 class="card-title mt-2">Neraca Saldo</h4>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endcanany
                                @canany(['view-neraca@finance'])
                                <div class="col-md-4">
                                    <a data-bs-effect="effect-scale" data-bs-toggle="modal" href="#modal-neraca-balance-format">
                                        <div class="card text-white card-transparent" style="background-color: #8B5CF6;">
                                            <div class="card-body" style="text-align: center">
                                                <img src="{{ url('assets/images/icon/receive-payment.png') }}" alt="">
                                                <h4 class="card-title mt-2">Neraca</h4>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endcanany
                                @if(false)
                                @canany(['view-arus_kas@finance'])
                                <div class="col-md-4">
                                    <a data-bs-effect="effect-scale" data-bs-toggle="modal" href="#modal-arus-kas-format">
                                        <div class="card text-white card-transparent" style="background-color: #5581AD;">
                                            <div class="card-body" style="text-align: center">
                                                <img src="{{ url('assets/images/icon/buku%20besar.png') }}" alt="Arus Kas">
                                                <h4 class="card-title mt-2">Arus Kas</h4>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endcanany
                                @endif
                                @if(['view-laba_rugi@finance'])
                                @canany(['view-laba_rugi@finance'])
                                <div class="col-md-4">
                                    <a data-bs-effect="effect-scale" data-bs-toggle="modal" href="#modal-profit-loss-format">
                                        <div class="card text-white card-transparent" style="background-color: #EAAD52;">
                                            <div class="card-body" style="text-align: center">
                                                <img src="{{ url('assets/images/icon/laba%20rugi.png') }}" alt="Laba Rugi">
                                                <h4 class="card-title mt-2">Laba Rugi</h4>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endcanany
                                @endif
                                @if(false)
                                @canany(['view-laporan_rekening@finance'])
                                <div class="col-md-4">
                                    <a data-bs-effect="effect-scale" data-bs-toggle="modal" href="#modal-laporan-rekening-format">
                                        <div class="card text-white card-transparent" style="background-color: #9a6cf0;">
                                            <div class="card-body" style="text-align: center">
                                                <img src="{{ url('assets/images/icon/laba%20rugi.png') }}" alt="Laba Rugi">
                                                <h4 class="card-title mt-2">Laporan Rekening</h4>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endcanany
                                @endif
                                @if(['view-outstanding_arap@finance'])
                                @canany(['view-outstanding_arap@finance'])
                                <div class="col-md-4">
                                    <a data-bs-effect="effect-scale" data-bs-toggle="modal" href="#modal-outstanding-arap-format">
                                        <div class="card text-white card-transparent" style="background-color: #28a745;">
                                            <div class="card-body" style="text-align: center">
                                                <img src="{{ url('assets/images/icon/laba%20rugi.png') }}" alt="Laba Rugi">
                                            <h4 class="card-title mt-2">Outstanding AR/AP</h4>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endcanany
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- CONTAINER CLOSED -->
    </div>
</div>

{{-- modal jurnal umum format --}}
<div class="modal fade" id="modal-jurnal-umum-format" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pengaturan filter jurnal umum</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-center align-items-center gap-4">
                            <button class="btn standardBtn" style="padding: 0px 10px; border-bottom: 1px solid #467FF7;" onclick="toggleForm('standard', this)">Standard</button>
                            <button class="btn yearBtn" style="padding: 0px 10px;" onclick="toggleForm('year', this)">Year</button>
                        </div>
                        <form class="standardForm" action="{{ route('finance.report-finance.general-ledger') }}" method="GET" enctype="multipart/form-data">

                            <div class="row d-flex justify-content-center align-items-center flex-column">
                                {{-- <label for="">Currency:</label>
                                <select name="currency" id="currency" style="height: 30px; margin-bottom: 30px; width: 450px;" required>
                                    <option label="Choose one" selected disabled></option>
                                    @foreach($currency as $data)
                                        <option value="{{$data->id}}">{{$data->initial}}</option>
                                    @endforeach
                                </select> --}}
                                <label for="">Pick date:</label>
                                <div class="d-flex justify-content-around align-items-center">
                                    <input type="text" id="start_datepicker_jurnal" name="start_date_jurnal" style="width: 200px; text-align: center; border: 1px solid #D8D8DC;">
                                    <div style="margin-top: 2px;">-</div>
                                    <input type="text" id="end_datepicker_jurnal" name="end_date_jurnal" style="width: 200px; text-align: center; border: 1px solid #D8D8DC;">
                                </div>
                            </div>
                            <br><br>
                            <div class="mt-3" style="text-align: right">
                                <a class="btn btn-white color-grey" data-bs-dismiss="modal">Close</a>
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </form>
                        <form class="yearForm" style="display: none;" action="{{ route('finance.report-finance.year-general-ledger') }}" method="GET" enctype="multipart/form-data">

                            <div class="row d-flex justify-content-center align-items-center flex-column">
                                {{-- <label for="">Currency:</label>
                                <select name="currency" id="currency" style="height: 30px; margin-bottom: 30px; width: 450px;" required>
                                    <option label="Choose one" selected disabled></option>
                                    @foreach($currency as $data)
                                        <option value="{{$data->id}}">{{$data->initial}}</option>
                                    @endforeach
                                </select> --}}
                                <label>Year</label>
                                <div class="d-flex justify-content-around align-items-center">
                                    <div class="form-group">
                                        <input type="number" value="{{ \Carbon\Carbon::now()->year }}" style="width: 450px; text-align: center; border: 1px solid #D8D8DC;" name="year"></input>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3" style="text-align: right">
                                <a class="btn btn-white color-grey" data-bs-dismiss="modal">Close</a>
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- modal buku besar format --}}
<div class="modal fade" id="modal-buku-besar-format" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pengaturan filter buku besar</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-center align-items-center gap-4">
                            <button class="btn standardBtn" style="padding: 0px 10px; border-bottom: 1px solid #467FF7;" onclick="toggleForm('standard', this)">Standard</button>
                            <button class="btn yearBtn" style="padding: 0px 10px;" onclick="toggleForm('year', this)">Year</button>
                        </div>
                        <form class="standardForm" action="{{ route('finance.report-finance.data-ledger') }}" method="GET" enctype="multipart/form-data">

                            <div class="row d-flex justify-content-center align-items-center flex-column">
                                <label for="">Currency:</label>
                                <select name="currency" id="currency" style="height: 30px; margin-bottom: 30px; width: 450px;" required>
                                    <option label="Choose one" selected disabled></option>
                                    @foreach($currency as $data)
                                        <option value="{{$data->id}}">{{$data->initial}}</option>
                                    @endforeach
                                </select>
                                <label for="">Pick date:</label>
                                <div class="d-flex justify-content-around align-items-center">
                                    <input type="text" id="start_datepicker_buku_besar" name="start_date_buku_besar" style="width: 200px; text-align: center; border: 1px solid #D8D8DC;">
                                    <div style="margin-top: 2px;">-</div>
                                    <input type="text" id="end_datepicker_buku_besar" name="end_date_buku_besar" style="width: 200px; text-align: center; border: 1px solid #D8D8DC;">
                                </div>
                            </div>
                            <br><br>
                            <div class="mt-3" style="text-align: right">
                                <a class="btn btn-white color-grey" data-bs-dismiss="modal">Close</a>
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </form>
                        <form class="yearForm" style="display: none;" action="{{ route('finance.report-finance.year-data-ledger') }}" method="GET" enctype="multipart/form-data">

                            <div class="row d-flex justify-content-center align-items-center flex-column">
                                <label for="">Currency:</label>
                                <select name="currency" id="currency" style="height: 30px; margin-bottom: 30px; width: 450px;" required>
                                    <option label="Choose one" selected disabled></option>
                                    @foreach($currency as $data)
                                        <option value="{{$data->id}}">{{$data->initial}}</option>
                                    @endforeach
                                </select>
                                <label>Year</label>
                                <div class="d-flex justify-content-around align-items-center">
                                    <div class="form-group">
                                        <input type="number" value="{{ \Carbon\Carbon::now()->year }}" style="width: 450px; text-align: center; border: 1px solid #D8D8DC;" name="year"></input>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3" style="text-align: right">
                                <a class="btn btn-white color-grey" data-bs-dismiss="modal">Close</a>
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- modal arus kas format --}}
<div class="modal fade" id="modal-arus-kas-format" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pengaturan filter arus kas</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-center align-items-center gap-4">
                            <button class="btn standardBtn" style="padding: 0px 10px; border-bottom: 1px solid #467FF7;" onclick="toggleForm('standard', this)">Standard</button>
                            <button class="btn yearBtn" style="padding: 0px 10px;" onclick="toggleForm('year', this)">Year</button>
                        </div>
                        <form class="standardForm" action="{{ route('finance.report-finance.cash-flow') }}" method="GET" enctype="multipart/form-data">

                            <div class="row d-flex justify-content-center align-items-center flex-column">
                                <label for="">Currency:</label>
                                <select name="currency" id="currency" style="height: 30px; margin-bottom: 30px; width: 450px;" required>
                                    <option label="Choose one" selected disabled></option>
                                    @foreach($currency as $data)
                                        <option value="{{$data->id}}">{{$data->initial}}</option>
                                    @endforeach
                                </select>
                                <label for="">Pick date:</label>
                                <div class="d-flex justify-content-around align-items-center">
                                    <input type="text" id="start_datepicker_arus_kas" name="start_date_arus_kas" style="width: 200px; text-align: center; border: 1px solid #D8D8DC;">
                                    <div style="margin-top: 2px;">-</div>
                                    <input type="text" id="end_datepicker_arus_kas" name="end_date_arus_kas" style="width: 200px; text-align: center; border: 1px solid #D8D8DC;">
                                </div>
                            </div>
                            <br><br>
                            <div class="mt-3" style="text-align: right">
                                <a class="btn btn-white color-grey" data-bs-dismiss="modal">Close</a>
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </form>
                        <form class="yearForm" style="display: none;" action="{{ route('finance.report-finance.year-cash-flow') }}" method="GET" enctype="multipart/form-data">

                            <div class="row d-flex justify-content-center align-items-center flex-column">
                                <label for="">Currency:</label>
                                <select name="currency" id="currency" style="height: 30px; margin-bottom: 30px; width: 450px;" required>
                                    <option label="Choose one" selected disabled></option>
                                    @foreach($currency as $data)
                                        <option value="{{$data->id}}">{{$data->initial}}</option>
                                    @endforeach
                                </select>
                                <label>Year</label>
                                <div class="d-flex justify-content-around align-items-center">
                                    <div class="form-group">
                                        <input type="number" value="{{ \Carbon\Carbon::now()->year }}" style="width: 450px; text-align: center; border: 1px solid #D8D8DC;" name="year"></input>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3" style="text-align: right">
                                <a class="btn btn-white color-grey" data-bs-dismiss="modal">Close</a>
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- modal neraca format --}}
<div class="modal fade" id="modal-neraca-format" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pengaturan filter neraca</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-center align-items-center gap-4">
                            <button class="btn standardBtn" style="padding: 0px 10px; border-bottom: 1px solid #467FF7;" onclick="toggleForm('standard', this)">Standard</button>
                            <button class="btn yearBtn" style="padding: 0px 10px;" onclick="toggleForm('year', this)">Year</button>
                        </div>
                        <!-- stadard -->
                        <form class="standardForm" action="{{ route('finance.report-finance.trial-balance') }}" method="GET" enctype="multipart/form-data">

                            <div class="row d-flex justify-content-center align-items-center flex-column">
                                <label for="">Pick date:</label>
                                <div class="d-flex justify-content-around align-items-center">
                                    <input type="text" id="start_datepicker_neraca" name="start_date_neraca" style="width: 200px; text-align: center; border: 1px solid #D8D8DC;">
                                    <div style="margin-top: 2px;">-</div>
                                    <input type="text" id="end_datepicker_neraca" name="end_date_neraca" style="width: 200px; text-align: center; border: 1px solid #D8D8DC;">
                                </div>
                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" name="foreign_currency" id="foreign_currency" value="1">
                                    <label class="form-check-label" for="foreign_currency">
                                        Foreign Currency
                                    </label>
                                </div>
                            </div>
                            <br><br>
                            <div class="mt-3" style="text-align: right">
                                <a class="btn btn-white color-grey" data-bs-dismiss="modal">Close</a>
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </form>
                        <!-- year -->
                        <form class="yearForm" style="display: none;" action="{{ route('finance.report-finance.year-trial-balancer') }}" method="GET" enctype="multipart/form-data">

                            <div class="row d-flex justify-content-center align-items-center flex-column">
                                <label>Year</label>
                                <div class="d-flex justify-content-around align-items-center">
                                    <div class="form-group">
                                        <input type="number" value="{{ \Carbon\Carbon::now()->year }}" style="width: 450px; text-align: center; border: 1px solid #D8D8DC;" name="year"></input>
                                    </div>
                                </div>
                                <div class="form-check mt-3">
                                    <input class="form-check-input" type="checkbox" name="foreign_currency" id="foreign_currency_year" value="1">
                                    <label class="form-check-label" for="foreign_currency_year">
                                        Foreign Currency
                                    </label>
                                </div>
                            </div>
                            <div class="mt-3" style="text-align: right">
                                <a class="btn btn-white color-grey" data-bs-dismiss="modal">Close</a>
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- modal neraca balance format --}}
<div class="modal fade" id="modal-neraca-balance-format" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pengaturan filter neraca</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-center align-items-center gap-4">
                            <button class="btn standardBtn" style="padding: 0px 10px; border-bottom: 1px solid #467FF7;" onclick="toggleForm('standard', this)">Standard</button>
                            <button class="btn yearBtn" style="padding: 0px 10px;" onclick="toggleForm('year', this)">Year</button>
                        </div>
                        <!-- stadard -->
                        <form class="standardForm" action="{{ route('finance.report-finance.neraca') }}" method="GET" enctype="multipart/form-data">

                            <div class="row d-flex justify-content-center align-items-center flex-column">
                                <label for="">Fiscal Period:</label>
                                <select name="fiscal_period_neraca" id="fiscal_period_neraca" style="height: 30px; margin-bottom: 30px; width: 450px;" required>
                                    <option label="Choose one" value="" selected disabled></option>
                                    @foreach($fiscalPeriods as $period)
                                        <option value="{{$period->id}}">{{$period->period}} ({{ \Carbon\Carbon::parse($period->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($period->end_date)->format('d M Y') }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <br><br>
                            <div class="mt-3" style="text-align: right">
                                <a class="btn btn-white color-grey" data-bs-dismiss="modal">Close</a>
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </form>
                        <!-- year -->
                        <form class="yearForm" style="display: none;" action="{{ route('finance.report-finance.year-neraca') }}" method="GET" enctype="multipart/form-data">

                            <div class="row d-flex justify-content-center align-items-center flex-column">
                                <label>Year</label>
                                <div class="d-flex justify-content-around align-items-center">
                                    <div class="form-group">
                                        <input type="number" value="{{ \Carbon\Carbon::now()->year }}" style="width: 450px; text-align: center; border: 1px solid #D8D8DC;" name="year"></input>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3" style="text-align: right">
                                <a class="btn btn-white color-grey" data-bs-dismiss="modal">Close</a>
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- modal laba rugi format --}}
<div class="modal fade" id="modal-profit-loss-format" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pengaturan filter laba rugi</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-center align-items-center gap-4">
                            <button class="btn standardBtn" style="padding: 0px 10px; border-bottom: 1px solid #467FF7;" onclick="toggleForm('standard', this)">Standard</button>
                            <button class="btn yearBtn" style="padding: 0px 10px;" onclick="toggleForm('year', this)">Year</button>
                        </div>
                        <form class="standardForm" action="{{ route('finance.report-finance.profit-loss') }}" method="GET" enctype="multipart/form-data">

                            <div class="row d-flex justify-content-center align-items-center flex-column">
                                <label for="">Currency:</label>
                                <select name="currency" id="currency" style="height: 30px; margin-bottom: 30px; width: 450px;" required>
                                    <option label="Choose one" selected disabled></option>
                                    @foreach($currency as $data)
                                        <option value="{{$data->id}}">{{$data->initial}}</option>
                                    @endforeach
                                </select>
                                <label for="">Fiscal Period:</label>
                                <select name="fiscal_period_laba_rugi" id="fiscal_period_laba_rugi" style="height: 30px; margin-bottom: 30px; width: 450px;" required>
                                    <option label="Choose one" value="" selected disabled></option>
                                    @foreach($fiscalPeriods as $period)
                                        <option value="{{$period->id}}">{{$period->period}} ({{ \Carbon\Carbon::parse($period->start_date)->format('d M Y') }} - {{ \Carbon\Carbon::parse($period->end_date)->format('d M Y') }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <br><br>
                            <div class="mt-3" style="text-align: right">
                                <a class="btn btn-white color-grey" data-bs-dismiss="modal">Close</a>
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </form>
                        <form class="yearForm" style="display: none;" action="{{ route('finance.report-finance.year-profit-loss') }}" method="GET" enctype="multipart/form-data">

                            <div class="row d-flex justify-content-center align-items-center flex-column">
                                <label for="">Currency:</label>
                                <select name="currency" id="currency" style="height: 30px; margin-bottom: 30px; width: 450px;" required>
                                    <option label="Choose one" selected disabled></option>
                                    @foreach($currency as $data)
                                        <option value="{{$data->id}}">{{$data->initial}}</option>
                                    @endforeach
                                </select>
                                <label>Year</label>
                                <div class="d-flex justify-content-around align-items-center">
                                    <div class="form-group">
                                        <input type="number" value="{{ \Carbon\Carbon::now()->year }}" style="width: 450px; text-align: center; border: 1px solid #D8D8DC;" name="year"></input>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3" style="text-align: right">
                                <a class="btn btn-white color-grey" data-bs-dismiss="modal">Close</a>
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- modal laporan rekening format --}}
<div class="modal fade" id="modal-laporan-rekening-format" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pengaturan filter laporan rekening</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form class="standardForm" action="{{ route('finance.report-finance.laporan-rekening') }}" method="GET" enctype="multipart/form-data">
                            <div class="row d-flex justify-content-center align-items-center flex-column">
                                <label for="">Source :</label>
                                <select name="source" id="source" style="height: 30px; margin-bottom: 30px; width: 450px;" required>
                                    <option label="Choose one" selected disabled></option>
                                    <option value="customer">Customer</option>
                                    <option value="vendor">Vendor</option>
                                </select>
                                <label for="">Pick date:</label>
                                <div class="d-flex justify-content-around align-items-center">
                                    <input type="text" id="start_datepicker_laporan_rekening" name="start_date_laporan_rekening" style="width: 200px; text-align: center; border: 1px solid #D8D8DC;">
                                    <div style="margin-top: 2px;">-</div>
                                    <input type="text" id="end_datepicker_laporan_rekening" name="end_date_laporan_rekening" style="width: 200px; text-align: center; border: 1px solid #D8D8DC;">
                                </div>
                            </div>
                            <br><br>
                            <div class="mt-3" style="text-align: right">
                                <a class="btn btn-white color-grey" data-bs-dismiss="modal">Close</a>
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- modal outstanding ar/ap format --}}
<div class="modal fade" id="modal-outstanding-arap-format" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pengaturan filter Outstanding AR/AP</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form class="standardForm" action="{{ route('finance.report-finance.outstanding-arap') }}" method="GET" enctype="multipart/form-data">
                            <div class="row d-flex justify-content-center align-items-center flex-column">
                                <label for="">Source :</label>
                                <select name="source" id="source_outstanding" style="height: 30px; margin-bottom: 30px; width: 450px;" required>
                                    <option label="Choose one" selected disabled></option>
                                    <option value="invoice">AR (Invoice)</option>
                                    <option value="order">AP</option>
                                </select>
                                <label for="">As of Date:</label>
                                <div class="d-flex justify-content-center align-items-center">
                                    <input type="text" id="as_of_datepicker" name="as_of_date" style="width: 200px; text-align: center; border: 1px solid #D8D8DC;" required>
                                </div>
                            </div>
                            <br><br>
                            <div class="mt-3" style="text-align: right">
                                <a class="btn btn-white color-grey" data-bs-dismiss="modal">Close</a>
                                <button type="submit" class="btn btn-primary">Filter</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="../assets/plugins/chart/Chart.bundle.js"></script>
<script src="../assets/js/chart.js"></script>
<!-- Masukkan link CSS jQuery UI di header template Anda -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!-- Masukkan jQuery dan jQuery UI di footer template Anda -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

<script>
    $(function() {
        $("#start_datepicker_jurnal").datepicker();
        $("#end_datepicker_jurnal").datepicker();

        $("#start_datepicker_buku_besar").datepicker();
        $("#end_datepicker_buku_besar").datepicker();

        $("#start_datepicker_arus_kas").datepicker();
        $("#end_datepicker_arus_kas").datepicker();

        $("#start_datepicker_neraca").datepicker();
        $("#end_datepicker_neraca").datepicker();



        $("#start_datepicker_laporan_rekening").datepicker();
        $("#end_datepicker_laporan_rekening").datepicker();
        $("#as_of_datepicker").datepicker();
    })

    function toggleForm(type, element) {
        const parent = element.closest('.modal-body');
        const standardForm = parent.querySelector('.standardForm');
        const yearForm = parent.querySelector('.yearForm');
        const standardBtn = parent.querySelector('.standardBtn');
        const yearBtn = parent.querySelector('.yearBtn');

        if (type === 'standard') {
            standardForm.style.display = 'block';
            yearForm.style.display = 'none';
            standardBtn.style.borderBottom = '1px solid #467FF7';
            yearBtn.style.borderBottom = 'none';
        } else if (type === 'year') {
            standardForm.style.display = 'none';
            yearForm.style.display = 'block';
            yearBtn.style.borderBottom = '1px solid #467FF7';
            standardBtn.style.borderBottom = 'none';
        }
    }

</script>
@endpush
