@extends('layouts.app')
@section('content')

    <div class="main-content app-content mt-0 pb-5">
        <div class="side-app">

            <!-- CONTAINER -->
            <div class="main-container container-fluid" id="onPrint">

                <!-- PAGE-HEADER -->
                <div class="page-header">
                    <h1 style="font-size: 36px; font-weight: bold; color: #015377;" id="titleTop">Buku Besar</h1>
                </div>
                <!-- PAGE-HEADER END -->

                <div class="row" style="background-color: white; padding-top: 50px; padding-bottom: 50px;" id="headerPrint">
                    <div class="col-xl-12">
                       <div class="w-full d-flex justify-content-center align-items-center flex-column">
                            <p style="font-size: 18px; font-weight: 500;">PT Perkasa Inovasi Logistik</p>
                            <p style="font-size: 18px; margin-top: -10px; font-weight: 500; color: #467FF7;">Buku Besar</p>
                            <p style="font-size: 18px; margin-top: -10px; font-weight: 500; color: #B14F4B;">{{ \Carbon\Carbon::parse($startDate)->format('j F, Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('j F, Y') }}</p>
                            <p style="font-size: 18px; margin-top: -10px; font-weight: 500; color: #B14F4B;">
                                Currency: 
                                {{ $currency->initial }}
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
                                <tr style="background-color: #597FB3;">
                                    <th style="width: 0px; color: white;" id="tableTh">#</th>
                                    <th style="color: white;">NAMA AKUN / TANGGAL </th>
                                    <th style="color: white;">TRANSAKSI</th>
                                    <th style="color: white;">NO</th>
                                    <th style="color: white;">DESKRIPSI</th>
                                    <th style="color: white;">DEBIT</th>
                                    <th style="color: white;">KREDIT</th>
                                    <th style="color: white;">SALDO</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                // Grand total (semua akun)
                                $grandDebit = 0;
                                $grandKredit = 0;
                            @endphp
                            
                            @foreach ($groupedData as $type)
                                @php
                                    $filteredData = collect($type['data'])->filter(fn($d) => ($d['total_debit'] ?? 0) != 0 || ($d['total_kredit'] ?? 0) != 0);
                                @endphp
                            
                                @if($filteredData->isNotEmpty())
                                    <tr style="background-color:#F0F6F9;">
                                        <td style="cursor: pointer;" onclick="arrow(this)" class="tableTd">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M4.21967 8.21967C4.48594 7.9534 4.9026 7.9292 5.19621 8.14705L5.28033 8.21967L11.75 14.689L18.2197 8.21967C18.4859 7.9534 18.9026 7.9292 19.1962 8.14705L19.2803 8.21967C19.5466 8.48594 19.5708 8.9026 19.3529 9.19621L19.2803 9.28033L12.2803 16.2803C12.0141 16.5466 11.5974 16.5708 11.3038 16.3529L11.2197 16.2803L4.21967 9.28033C3.92678 8.98744 3.92678 8.51256 4.21967 8.21967Z" fill="#37474F"/>
                                            </svg>
                                        </td>
                                        <td style="font-size:16px;font-weight:400;">
                                            ({{ $type['master_account']->code }}) - {{ $type['master_account']->account_name }}
                                        </td>
                                        <td></td><td></td><td></td><td></td><td></td><td></td>
                                    </tr>
                            
                                    @php
                                        // RESET saldo per akun
                                        $runningSaldoAkun = 0;
                            
                                        // tentukan normal side akun
                                        $normalDebit = in_array(strtolower($type['master_account']->account_type->normal_side ?? 'debit'), ['debit']);
                            
                                        // pastikan data diurutkan juga di view (jaga-jaga)
                                        $rows = $filteredData->sortBy('created_at');
                            
                                        // total per akun
                                        $debitAkun = 0;
                                        $kreditAkun = 0;
                                    @endphp
                            
                                    @foreach ($rows as $data)
                                        @php
                                            $debit  = (float)($data['total_debit'] ?? 0);
                                            $kredit = (float)($data['total_kredit'] ?? 0);
                            
                                            // akumulasi per akun & grand total
                                            $debitAkun  += $debit;  $grandDebit  += $debit;
                                            $kreditAkun += $kredit; $grandKredit += $kredit;
                            
                                            // DELTA saldo sesuai normal side
                                            $delta = $normalDebit ? ($debit - $kredit) : ($kredit - $debit);
                                            $runningSaldoAkun += $delta;
                                        @endphp
                                        <tr style="background-color:#FBFEFF;" class="detail">
                                            <td></td>
                                            <td>{{ \Carbon\Carbon::parse($data['created_at'])->format('j F, Y') }}</td>
                                            <td>
                                                {{-- link sama seperti punyamu --}}
                                                @php
                                                    $link = '#';
                                                    $typeName = $data['transaksi_id']->transaction_type ?? '';
                                                    if($typeName === 'Saldo Awal') $link = route('finance.master-data.account');
                                                    elseif($typeName === 'Sales Order') $link = route('finance.piutang.sales-order.show', $data['transaksi']->id ?? null);
                                                    elseif($typeName === 'Invoice') $link = route('finance.piutang.invoice.show', $data['transaksi']->id ?? null);
                                                    elseif($typeName === 'Receive Payment') $link = route('finance.piutang.receive-payment.show', $data['transaksi']->id ?? null);
                                                    elseif($typeName === 'Kas Keluar') $link = route('finance.kas.pembayaran.show', $data['transaksi']->id ?? null);
                                                    elseif($typeName === 'Kas Masuk') $link = route('finance.kas.penerimaan.show', $data['transaksi']->id ?? null);
                                                    elseif($typeName === 'Account Payable') $link = route('finance.payments.account-payable.show', $data['transaksi']->id ?? null);
                                                    elseif($typeName === 'Payment') $link = route('finance.payments.purchase-payment.show', $data['transaksi']->id ?? null);
                                                @endphp
                                                <a href="{{ $link }}">{{ $typeName }}</a>
                                            </td>
                                            <td>{{ $data['transaksi']->transaction ?? '' }}</td>
                                            <td>{{ $data['transaksi']->description ?? '' }}</td>
                            
                                            {{-- ✅ debit & kredit selalu POSITIF di tampilan --}}
                                            <td>{{ number_format($debit, 2, '.', ',') }}</td>
                                            <td>{{ number_format($kredit, 2, '.', ',') }}</td>
                            
                                            {{-- saldo berjalan per akun, tanpa manipulasi tanda di kredit --}}
                                            <td>
                                                {{ round($runningSaldoAkun,2) == 0 ? '0.00'
                                                    : ($runningSaldoAkun < 0
                                                        ? '('.number_format(abs($runningSaldoAkun),2,'.',',').')'
                                                        : number_format($runningSaldoAkun,2,'.',',')
                                                      )
                                                }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                            
                            {{-- GRAND TOTAL (semua akun) --}}
                            <tr style="background-color:#597FB3;">
                                <th style="color:white;"></th>
                                <th style="color:white;"></th>
                                <th style="color:white;"></th>
                                <th style="color:white;"></th>
                                <th style="color:white;"></th>
                                <th style="color:white;">{{ number_format($grandDebit, 2, '.', ',') }}</th>
                                <th style="color:white;">{{ number_format($grandKredit, 2, '.', ',') }}</th>
                                <th style="color:white;"></th>
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
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

    <script>
        function addEventListeners() {
            document.getElementById("showExport").addEventListener("click", function() {
                var printButton = document.getElementById("print");
                var printTitle = document.getElementById("printTitle");
                var printIcon = document.getElementById("printIcon");
                var titleTop = document.getElementById("titleTop");
                var tableTh = document.getElementById("tableTh");
                var tableTd = document.querySelectorAll(".tableTd");

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
                if (tableTh.style.opacity === "0") {
                    tableTh.style.opacity = "1";
                    tableTh.style.visibility = "visible";
                } else {
                    tableTh.style.opacity = "0";
                    tableTh.style.visibility = "hidden";
                }

                tableTd.forEach(function(td) {
                    if (td.style.opacity === "0") {
                        td.style.opacity = "1";
                        td.style.visibility = "visible";
                    } else {
                        td.style.opacity = "0";
                        td.style.visibility = "hidden";
                    }
                });
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

        function arrow(element) {
            var rotation = $(element).data('rotation') || 0;
            rotation += 180;
            $(element).css('transform', 'rotate(' + rotation + 'deg)');
            $(element).data('rotation', rotation);
            var $row = $(element).closest('tr');
            var $details = $row.nextUntil(':not(.detail)');
            $details.toggle();
        }
    </script>
@endpush
