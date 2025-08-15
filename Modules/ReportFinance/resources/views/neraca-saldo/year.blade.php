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
                            <p id="date-range" style="font-size: 18px; margin-top: -10px; font-weight: 500; color: #B14F4B;">{{ $year }}</p>
                            <p style="font-size: 18px; margin-top: -10px; font-weight: 500; color: #B14F4B;">
                                Currency: 
                                    <span style="color: #B14F4B;">{{ $currency->initial }}</span>
                            </p>
                       </div>
                       <div class="w-full d-flex justify-content-end align-items-center">
                            <div id="showExport" style="background-color: #FCEBDA; height: 55px; width: 150px; border-radius: 10px; display: flex; justify-content: center; align-items: center; gap: 10px; margin-bottom: 20px; cursor: pointer; position: relative;">
                                <div style="height: 24px; width: 24px; display: flex; justify-content: center; align-items: center;"  id="printIcon">
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
                                    <th style="color: white;">Month</th>
                                    <th style="color: white;">Kode Akun</th>
                                    <th style="color: white;">Account Name</th>
                                    <th colspan="2" style="color: white;">Saldo Awal</th>
                                    <th colspan="2" style="color: white;">Mutasi</th>
                                    <th style="color: white;">Net Mutation</th>
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
                                    <th></th>
                                    <th style="color: white;">Debit</th>
                                    <th style="color: white;">Kredit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $footerSaldoAwalDebit=0;
                                    $footerSaldoAwalKredit=0;
                                    $footerDebit=0;
                                    $footerKredit=0;
                                    $footerNet=0;
                                    $footerSaldoAkhirDebit=0;
                                    $footerSaldoAkhirKredit=0;
                                @endphp
                                @if(isset($yearTrialBalance))
                                    @foreach($yearTrialBalance['month_name'] as $index => $month)
                                        @if((!($yearTrialBalance['data'][$index]->isEmpty())))
                                            @foreach($yearTrialBalance['data'][$index] as $ma)
                                            @php
                                                $startDate = \Carbon\Carbon::createFromFormat('F Y', $month . ' ' . $year)->startOfMonth();    
                                                $endDate = \Carbon\Carbon::createFromFormat('F Y', $month . ' ' . $year)->endOfMonth();    
                                                
                                                $data = $ma->getDebitKreditAll($startDate, $endDate);
                                                if($data["debit"] == 0 && $data["kredit"] == 0) {
                                                    continue;
                                                }
                                                $debitData = '(' . $data["debit"] . ')';
                                                $kreditData = $data["kredit"];
                                            @endphp
                                            <tr>
                                                <td>{{ $month }}</td>
                                                <td>{{ $ma->code }}</td>
                                                <td>{{ $ma->account_name }}</td>

                                                {{-- Saldo Awal --}}
                                                @php
                                                    $saldoAwal = $ma->getDebitKreditSaldoAwal();
                                                    $debitSaldoAwal = '(' . $saldoAwal["debit"] . ')';
                                                    $kreditSaldoAwal = $saldoAwal["kredit"];
                                                @endphp
                                                <td>{{ $debitSaldoAwal }}</td>
                                                <td>{{ $kreditSaldoAwal }}</td>
                                                
                                                {{-- Data Debit dan Kredit --}}
                                                <td>{{ $debitData }}</td>
                                                <td>{{ $kreditData }}</td>
                                                
                                                {{-- Net Mutation --}}
                                                @php
                                                    $netMutation = $ma->getNetMutation($startDate, $endDate);
                                                    if ($netMutation < 0) {
                                                        $hasil = '(' . abs($netMutation) . ')';
                                                    } else {
                                                        $hasil = $netMutation;
                                                    }
                                                @endphp
                                                <td>{{ $hasil }}</td>
                                                
                                                {{-- Saldo Akhir --}}
                                                @php
                                                    $debitSaldoAkhir = "(0)";
                                                    $kreditSaldoAkhir = "0";
                                                    
                                                    if ($netMutation < 0) {
                                                        $debitSaldoAkhir = '(' . abs($netMutation) . ')';
                                                    } else {
                                                        $kreditSaldoAkhir = $netMutation;
                                                    }
                                                @endphp
                                                <td>{{ $debitSaldoAkhir }}</td>
                                                <td>{{ $kreditSaldoAkhir }}</td>
                                            </tr>
                                            @endforeach

                                        @php
                                            $footerSaldoAwalDebit += $yearTrialBalance['total'][$index]['saldoAwalDebit'];
                                            $footerSaldoAwalKredit += $yearTrialBalance['total'][$index]['saldoAwalKredit']; 
                                            $footerDebit += $yearTrialBalance['total'][$index]['mutasDebit'];
                                            $footerKredit += $yearTrialBalance['total'][$index]['mutasKredit']; 
                                            $footerNet += $yearTrialBalance['total'][$index]['netMutasi'];
                                            $footerSaldoAkhirDebit += $yearTrialBalance['total'][$index]['saldoAkhirDebit'];
                                            $footerSaldoAkhirKredit += $yearTrialBalance['total'][$index]['saldoAkhirKredit'];
                                        @endphp
                                        <tr id="footer" style="background-color: #597FB3;">
                                            <th style="color: white;">Total Month</th>
                                            <th style="color: white;"></th>
                                            <th style="color: white;"></th>
                                            <th style="color: white;">({{ $yearTrialBalance['total'][$index]['saldoAwalDebit'] }})</th>
                                            <th style="color: white;">{{ $yearTrialBalance['total'][$index]['saldoAwalKredit'] }}</th>
                                            <th style="color: white;">({{ $yearTrialBalance['total'][$index]['mutasDebit'] }})</th>
                                            <th style="color: white;">{{ $yearTrialBalance['total'][$index]['mutasKredit'] }}</th>
                                            <th style="color: white;">
                                                {{ $yearTrialBalance['total'][$index]['netMutasi'] }}
                                            </th>
                                            <th style="color: white;">
                                                ({{ abs($yearTrialBalance['total'][$index]['saldoAkhirDebit']) }})
                                            </th>
                                            <th style="color: white;">
                                                {{ $yearTrialBalance['total'][$index]['saldoAkhirKredit'] }}
                                            </th>
                                        </tr>
                                        @endif
                                    @endforeach
                                @endif
                                
                                <tr id="footer" style="background-color: #597FB3;">
                                    <th style="color: white;">Total</th>
                                    <th style="color: white;"></th>
                                    <th style="color: white;"></th>
                                    <th style="color: white;">({{$footerSaldoAwalDebit}})</th>
                                    <th style="color: white;">{{$footerSaldoAwalKredit}}</th>
                                    <th style="color: white;">({{$footerDebit}})</th>
                                    <th style="color: white;">{{$footerKredit}}</th>
                                    <th style="color: white;">
                                        @php
                                         if($footerNet < 0) {
                                            $footerNet = abs($footerNet);
                                            $footerNet = "({$footerNet})";
                                         }   
                                        @endphp
                                        {{$footerNet}}
                                    </th>
                                    <th style="color: white;">
                                        @php
                                         if($footerSaldoAkhirDebit < 0) {
                                            $footerSaldoAkhirDebit = abs($footerSaldoAkhirDebit);
                                            $footerSaldoAkhirDebit = "({$footerSaldoAkhirDebit})";
                                         }   
                                        @endphp
                                        {{$footerSaldoAkhirDebit}}
                                    </th>
                                    <th style="color: white;">
                                        @php
                                         if($footerSaldoAkhirKredit < 0) {
                                            $footerSaldoAkhirKredit = abs($footerSaldoAkhirKredit);
                                            $footerSaldoAkhirKredit = "({$footerSaldoAkhirKredit})";
                                         }   
                                        @endphp
                                        {{$footerSaldoAkhirKredit}}
                                    </th>
                                </tr>
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
                addEventListeners();
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
