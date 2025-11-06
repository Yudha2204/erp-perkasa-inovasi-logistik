@extends('layouts.app')
@section('content')

    <div class="main-content app-content mt-0 pb-5">
        <div class="side-app">

            <!-- CONTAINER -->
            <div class="main-container container-fluid" id="onPrint">

                <!-- PAGE-HEADER -->
                <div class="page-header">
                    <h1 style="font-size: 36px; font-weight: bold; color: #015377;" id="titleTop">Neraca Saldo</h1>
                </div>
                <!-- PAGE-HEADER END -->

                <div class="row" style="background-color: white; padding-top: 50px; padding-bottom: 50px;" id="headerPrint">
                    <div class="col-xl-12">
                       <div class="w-full d-flex justify-content-center align-items-center flex-column">
                            <p style="font-size: 18px; font-weight: 500;">PT Perkasa Inovasi Logistik</p>
                            <p style="font-size: 18px; margin-top: -10px; font-weight: 500; color: #467FF7;">Neraca Saldo</p>
                            <p style="font-size: 18px; margin-top: -10px; font-weight: 500; color: #B14F4B;">{{ \Carbon\Carbon::parse($startDate)->format('j F, Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('j F, Y') }}</p>
                            <p style="font-size: 18px; margin-top: -10px; font-weight: 500; color: #B14F4B;">
                                Currency:
                                    <span style="color: #B14F4B;">{{ $idrCurrency->initial }}</span>
                                @if($foreign_currency)
                                    <span style="color: #B14F4B;"> (with Foreign Currency)</span>
                                @endif
                            </p>
                       </div>
                       <div class="w-full d-flex justify-content-end align-items-center">
                            <div id="showExport" style="background-color: #FCEBDA; height: 55px; width: 150px; border-radius: 10px; display: flex; justify-content: center; align-items: center; gap: 10px; margin-bottom: 20px; cursor: pointer; position: relative;">
                                <div style="height: 24px; width: 24px; display: flex; justify-content: center; align-items: center;" id="printIcon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 4V20M20 12H4" stroke="#F1977B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </div>
                                <h1 style="color: #F1977B; font-size: 18px; font-weight: 500; margin-top: 12px;" id="printTitle">Export</h1>

                                <button id="print" style="position: absolute; top: 100%; background-color: #367EA3;height: 55px; width: 150px; color: white; border: 1px solid white; display: none; font-size: 16px; font-weight: bold;">Export PDF</button>
                            </div>
                       </div>

                        <div style="font-size: 16px; font-weight: 400;">Semua Departemen</div>
                        <table class="table">
                            <thead>
                                <tr style="background-color: #597FB3; text-align: center;">
                                    <th style="color: white;">Kode Akun</th>
                                    <th style="color: white;">Account Name</th>
                                    <th style="color: white;">Currency</th>
                                    <th colspan="2" style="color: white;">Saldo Awal</th>
                                    <th colspan="2" style="color: white;">Mutasi</th>
                                    <th colspan="2" style="color: white;">Saldo Akhir</th>
                                </tr>
                                <tr style="background-color: #597FB3; border-top: 2px solid #597FB3;">
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th style="color: white;">Debit</th>
                                    <th style="color: white;">Kredit</th>
                                    <th style="color: white;">Debit</th>
                                    <th style="color: white;">Kredit</th>
                                    <th style="color: white;">Debit</th>
                                    <th style="color: white;">Kredit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($masterAccounts))
                                    @foreach($masterAccounts as $ma)
                                    @php
                                        $data = $ma->getDebitKreditAll($startDate, $endDate, $idrCurrency->id);
                                        if($data["debit"] == 0 && $data["kredit"] == 0) {
                                            continue;
                                        }
                                    @endphp
                                        {{-- IDR Row --}}
                                        <tr>
                                            <td>{{$ma->code}}</td>
                                            <td>{{$ma->account_name}}</td>
                                            <td>{{ $idrCurrency->initial }}</td>
                                            @php
                                                $saldoAwal = $ma->getDebitKreditSaldoAwal($idrCurrency->id);
                                                $netMutation = $ma->getNetMutation($startDate, $endDate, $idrCurrency->id);
                                            @endphp
                                            <td>{{ number_format($saldoAwal["debit"], 0, ',', '.') }}</td>
                                            <td>{{ number_format($saldoAwal["kredit"], 0, ',', '.') }}</td>

                                            <td>{{ number_format($data["debit"], 0, ',', '.') }}</td>
                                            <td>{{ number_format($data["kredit"], 0, ',', '.') }}</td>

                                            <td>{{  number_format(abs($netMutation), 0, ',', '.')  }}</td>
                                            <td>{{ $netMutation >= 0 ? number_format($netMutation, 0, ',', '.') : '0' }}</td>
                                        </tr>
                                        {{-- Foreign Currency Row (if checked and account is not IDR) --}}
                                        @if($foreign_currency && isset($ma->foreign_currency_data))
                                            @php
                                                $fcData = $ma->foreign_currency_data;
                                                $fcNetMutation = $fcData['netMutation'];
                                            @endphp
                                            <tr style="background-color: #f0f0f0;">
                                                <td></td>
                                                <td style="padding-left: 30px;">{{$ma->account_name}} (Original)</td>
                                                <td>{{ $fcData['currency']->initial }}</td>
                                                <td>{{ number_format($fcData['saldoAwal']["debit"], 0, ',', '.') }}</td>
                                                <td>{{ number_format($fcData['saldoAwal']["kredit"], 0, ',', '.') }}</td>
                                                <td>{{ number_format($fcData['data']["debit"], 0, ',', '.') }}</td>
                                                <td>{{ number_format($fcData['data']["kredit"], 0, ',', '.') }}</td>
                                                <td>{{ number_format(abs($fcNetMutation), 0, ',', '.') }}</td>
                                                <td>{{ $fcNetMutation >= 0 ? number_format($fcNetMutation, 0, ',', '.') : '0' }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                @endif
                                @if(isset($footer))
                                    <tr id="footer" style="background-color: #597fb3">
                                        <th style="color: white">Total</th>
                                        <th style="color: white"></th>
                                        <th style="color: white"></th>
                                        <th style="color: white">{{ number_format($footer["saldoAwalDebit"], 0, ',', '.') }}</th>
                                        <th style="color: white">{{ number_format($footer["saldoAwalKredit"], 0, ',', '.') }}</th>
                                        <th style="color: white">{{ number_format(abs($footer["mutasDebit"]), 0, ',', '.') }}</th>
                                        <th style="color: white">{{ number_format($footer["mutasKredit"], 0, ',', '.') }}</th>
                                        <th style="color: white">{{ number_format(abs($footer["saldoAkhirDebit"]), 0, ',', '.') }}</th>
                                        <th style="color: white">{{ number_format($footer["saldoAkhirKredit"], 0, ',', '.') }}</th>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <a id="back-btn" href="{{ route('finance.report-finance.index') }}">
                    <button class="btn btn-primary" style="margin-top: 50px;">Back</button>
                </a>
            </div>
            <!-- CONTAINER CLOSED -->
        </div>
    </div>
@endsection
@push('styles')
    <style>
         @media print {
            #headerPrint {
                margin-top: -100px;
            }
        }
    </style>
@endpush


@push('scripts')
    <script>
        function addEventListeners() {
            document.getElementById("showExport").addEventListener("click", function() {
                var printButton = document.getElementById("print");
                var printTitle = document.getElementById("printTitle");
                var printIcon = document.getElementById("printIcon");
                var titleTop = document.getElementById("titleTop");
                if (printButton.style.display === "none") {
                    printButton.style.display = "block";
                } else {
                    printButton.style.display = "none";
                }
                if (printTitle.style.display === "none") {
                    printTitle.style.display = "block";
                } else {
                    printTitle.style.display = "none";
                }
                if (printIcon.style.display === "none") {
                    printIcon.style.display = "block";
                } else {
                    printIcon.style.display = "none";
                }
                if (titleTop.style.display === "none") {
                    titleTop.style.display = "block";
                } else {
                    titleTop.style.display = "none";
                }
            });

            document.getElementById("print").addEventListener("click", function() {
                var printContent = document.getElementById("onPrint");
                var originalContents = document.body.innerHTML;
                var exportButton = document.getElementById("print");
                exportButton.style.display = 'none';
                var backBtn = document.getElementById("back-btn");
                backBtn.style.display = 'none';
                document.body.innerHTML = printContent.innerHTML;
                window.print();
                document.body.innerHTML = originalContents;
                addEventListeners(); // Pasang kembali event listeners
                exportButton.style.display = 'block';
            });
        }

        document.addEventListener("DOMContentLoaded", function() {
            addEventListeners();
        });
    </script>
    <script>
        var rows = document.querySelectorAll('tbody tr');
        rows.forEach(function(row, index) {
            if (!row.id || row.id !== 'footer') {
                if (index % 2 === 0) {
                    row.style.backgroundColor = '#F7FAFB';
                } else {
                    row.style.backgroundColor = 'white';
                }
            }
        });
    </script>
@endpush
