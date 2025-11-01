@extends('layouts.app')
@section('content')

    <div class="main-content app-content mt-0 pb-5">
        <div class="side-app">

            <!-- CONTAINER -->
            <div class="main-container container-fluid" id="onPrint">

                <!-- PAGE-HEADER -->
                <div class="page-header">
                    <h1 style="font-size: 36px; font-weight: bold; color: #015377;" id="titleTop">Jurnal Umum</h1>
                </div>
                <!-- PAGE-HEADER END -->
                <div class="row" style="background-color: white; padding-top: 50px; padding-bottom: 50px;" id="headerPrint">
                    <div class="col-xl-12">
                       <div class="w-full d-flex justify-content-center align-items-center flex-column">
                            <p style="font-size: 18px; font-weight: 500;">PT Perkasa Inovasi Logistik</p>
                            <p style="font-size: 18px; margin-top: -10px; font-weight: 500; color: #467FF7;">Jurnal Umum</p>
                            <p style="font-size: 18px; margin-top: -10px; font-weight: 500; color: #B14F4B;">{{ \Carbon\Carbon::parse($startDate)->format('j F, Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('j F, Y') }}</p>
                            <p style="font-size: 18px; margin-top: -10px; font-weight: 500; color: #B14F4B;">

                            </p>
                       </div>
                       <div class="w-full d-flex justify-content-end align-items-center">
                            <div id="showExport"  style="background-color: #FCEBDA; height: 55px; width: 150px; border-radius: 10px; display: flex; justify-content: center; align-items: center; gap: 10px; margin-bottom: 20px; cursor: pointer; position: relative;">
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
                                    <th style="color: white;">Type</th>
                                    <th style="color: white;">REF/DATE</th>
                                    <th style="color: white;">DESKRIPSI</th>
                                    <th style="color: white;">DEBIT</th>
                                    <th style="color: white;">KREDIT</th>
                                    <th style="color: white;">DEBIT IDR</th>
                                    <th style="color: white;">KREDIT IDR</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($groupedData as $module_id => $data)
                                    @php
                                    $module_name = "Saldo Awal";
                                    if($module_id === 2) {
                                        $module_name = "Sales Order";
                                    } else if($module_id === 3) {
                                        $module_name = "Invoice";
                                    } else if($module_id === 4) {
                                        $module_name = "Receive Payment";
                                    } else if($module_id === 5) {
                                        $module_name = "Cash & Bank Out";
                                    } else if($module_id === 6) {
                                        $module_name = "Cash & Bank In";
                                    } else if($module_id === 7) {
                                        $module_name = "Account Payable";
                                    } else if($module_id === 8) {
                                        $module_name = "Payment";
                                    } else if($module_id === 9) {
                                        $module_name = "General Journal";
                                    }
                                    @endphp
                                    @foreach($data as $transactionData)
                                    @php
                                        $account = $transactionData['head'];
                                        $jurnal = $transactionData['jurnal'];
                                        $jurnalIDR = $transactionData['jurnalIDR'];
                                        $isForeignCurrency = $jurnalIDR->isNotEmpty() && $jurnal->isNotEmpty() && $jurnal->first()->currency_id != $jurnalIDR->first()->currency_id;
                                    @endphp
                                    {{-- @dd($data) --}}
                                        {{-- Transaction Header Row --}}
                                        <tr>
                                            <td><b>{{ $module_name }}</b></td>
                                            @php
                                                $date = "";
                                                if($module_id === 2) {
                                                    $date = $account->date;
                                                } else if($module_id === 3) {
                                                    $date = $account->date_invoice;
                                                } else if($module_id === 4) {
                                                    $date = $account->date_recieve;
                                                } else if($module_id === 5) {
                                                    $date = $account->date_kas_out;
                                                } else if($module_id === 6) {
                                                    $date = $account->date_kas_in;
                                                } else if($module_id === 7) {
                                                    $date = $account->date;
                                                } else if($module_id === 8) {
                                                    $date = $account->date_order;
                                                } else if($module_id === 9) {
                                                    $date = $account->date_journal;
                                                }
                                            @endphp
                                            <td><b>{{ \Carbon\Carbon::parse($date)->format('j F, Y') }}</b></td>
                                            <td><b>{{ $account->description }}</b></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>

                                        {{-- Journal Entries --}}
                                        @for ($i = 0; $i < $jurnal->count(); $i++)
                                            @php
                                                $jurnal_entry = $jurnal[$i];
                                            @endphp
                                            @if($jurnal_entry->debit > 0 || $jurnal_entry->credit > 0)
                                            <tr>
                                                <td></td>
                                                @php
                                                    $link = "#";
                                                    if($module_name === "Saldo Awal") {
                                                        $link = route('finance.master-data.account');
                                                    } else if($module_name === "Sales Order") {
                                                        $link = route('finance.piutang.sales-order.show', $account->id);
                                                    } else if($module_name === "Invoice") {
                                                        $link = route('finance.piutang.invoice.show', $account->id);
                                                    } else if($module_name === "Receive Payment") {
                                                        $link = route('finance.piutang.receive-payment.show', $account->id);
                                                    } else if($module_name === "Cash & Bank Out") {
                                                        $link = route('finance.kas.pembayaran.show', $account->id);
                                                    } else if($module_name === "Cash & Bank In") {
                                                        $link = route('finance.kas.penerimaan.show', $account->id);
                                                    } else if($module_name === "Penerimaan Quotation") {
                                                        $link = route('finance.payments.quotation-vendor.show', $account->id);
                                                    } else if($module_name === "Account Payable") {
                                                        $link = route('finance.payments.account-payable.show', $account->id);
                                                    } else if($module_name === "Payment") {
                                                        $link = route('finance.payments.purchase-payment.show', $account->id);
                                                    } else if($module_name === "General Journal") {
                                                        $link = route('finance.general-ledger.jurnal-umum.show', $account->id);
                                                    }
                                                @endphp
                                                <td><a href="{{ $link }}">{{ $account->transaction }}</a></td>
                                                <td>{{ $jurnal_entry->master_account->account_name }}</td>
                                                <td>
                                                    @if($jurnal_entry->debit > 0)
                                                    {{ $account->currency->initial }} {{ number_format($jurnal_entry->debit,2,'.',',') }}
                                                    @else
                                                    -
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($jurnal_entry->credit > 0)
                                                    {{ $account->currency->initial }} {{ number_format($jurnal_entry->credit,2,'.',',') }}
                                                    @else
                                                    -
                                                    @endif
                                                </td>
                                            {{-- </tr> --}}
                                            @endif

                                            {{-- IDR Journal Entry --}}
                                            {{-- @if($isForeignCurrency) --}}
                                                @php
                                                    $jurnal_idr_entry = $jurnalIDR->get($i);
                                                @endphp
                                                @if($jurnal_idr_entry && ($jurnal_idr_entry->debit > 0 || $jurnal_idr_entry->credit > 0))
                                                {{-- <tr style="font-style: italic; color: #555;"> --}}
                                                    <td>
                                                        @if($jurnal_idr_entry->debit > 0)
                                                        IDR {{ number_format($jurnal_idr_entry->debit,2,'.',',') }}
                                                        @else
                                                        -
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($jurnal_idr_entry->credit > 0)
                                                        IDR {{ number_format($jurnal_idr_entry->credit,2,'.',',') }}
                                                        @else
                                                        -
                                                        @endif
                                                    </td>
                                                    @endif
                                                </tr>
                                            {{-- @endif --}}
                                        @endfor
                                    @endforeach
                                @endforeach
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
                if (tableTh.style.display === "none") {
                    tableTh.style.display = "block";
                } else {
                    tableTh.style.display = "none";
                }
                tableTd.forEach(function(td) {
                    if (td.style.display === "none") {
                        td.style.display = "block";
                    } else {
                        td.style.display = "none";
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
    </script>
    <script>
        var rows = document.querySelectorAll('tbody tr');
        rows.forEach(function(row, index) {
            if (index % 2 === 0) {
                row.style.backgroundColor = '#F7FAFB';
            } else {
                row.style.backgroundColor = 'white';
            }
        });
    </script>
@endpush
