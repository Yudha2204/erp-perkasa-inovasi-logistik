@extends('layouts.app')
@section('content')

    <div class="main-content app-content mt-0 pb-5">
        <div class="side-app">

            <!-- CONTAINER -->
            <div class="main-container container-fluid" id="onPrint">

                <!-- PAGE-HEADER -->
                <div class="page-header">
                    <h1 style="font-size: 36px; font-weight: bold; color: #015377;" id="titleTop">Neraca</h1>
                </div>
                <!-- PAGE-HEADER END -->

                <div class="row" style="background-color: white; padding-top: 50px; padding-bottom: 50px;" id="headerPrint">
                    <div class="col-xl-12">
                       <div class="w-full d-flex justify-content-center align-items-center flex-column">
                            <p style="font-size: 18px; font-weight: 500;">PT Perkasa Inovasi Logistik</p>
                            <p style="font-size: 18px; margin-top: -10px; font-weight: 500; color: #467FF7;">Neraca</p>
                            <p style="font-size: 18px; margin-top: -10px; font-weight: 500; color: #B14F4B;">{{ \Carbon\Carbon::parse($startDate)->format('j F, Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('j F, Y') }}</p>
                            <p style="font-size: 18px; margin-top: -10px; font-weight: 500; color: #B14F4B;">
                                Currency: <span style="color: #B14F4B;">{{ $idrCurrency->initial }}</span>
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
                        <div class="row">
                            <!-- Aktiva Column -->
                            <div class="col-md-6">
                                <table class="table">
                                    <thead>
                                        <tr style="background-color: #597FB3; text-align: center;">
                                            <th colspan="2" style="color: white;">AKTIVA</th>
                                        </tr>
                                        <tr style="background-color: #597FB3; border-top: 2px solid #597FB3;">
                                            <th style="color: white;">Kode Akun</th>
                                            <th style="color: white; text-align: right;">Saldo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($balanceSheet['aktiva']['data'] as $accountGroup)
                                            @if($accountGroup['header'])
                                                <tr>
                                                    <td style="padding-left: 20px; font-weight: bold;">
                                                        {{ $accountGroup['header']->code }} - {{ $accountGroup['header']->account_name }}
                                                    </td>
                                                    <td style="text-align: right; font-weight: bold;">
                                                        {{ number_format($accountGroup['total'], 2, ',', '.') }}
                                                    </td>
                                                </tr>
                                                @foreach($accountGroup['children'] as $child)
                                                    <tr>
                                                        <td style="padding-left: 40px;">
                                                            {{ $child['account']->code }} - {{ $child['account']->account_name }} {{ $idrCurrency->initial }}
                                                        </td>
                                                        <td style="text-align: right;">
                                                            {{ number_format($child['balance'], 2, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                    @if(isset($child['foreign_currency']) && $child['foreign_currency'])
                                                        <tr>
                                                            <td style="padding-left: 40px;">
                                                                {{ $child['account']->code }} - {{ $child['account']->account_name }} {{ $child['foreign_currency']['currency']->initial }}
                                                            </td>
                                                            <td style="text-align: right;">
                                                                {{ number_format($child['foreign_currency']['balance'], 2, ',', '.') }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @else
                                                @foreach($accountGroup['children'] as $child)
                                                    <tr>
                                                        <td style="padding-left: 20px;">
                                                            {{ $child['account']->code }} - {{ $child['account']->account_name }} {{ $idrCurrency->initial }}
                                                        </td>
                                                        <td style="text-align: right;">
                                                            {{ number_format($child['balance'], 2, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                    @if(isset($child['foreign_currency']) && $child['foreign_currency'])
                                                        <tr>
                                                            <td style="padding-left: 20px;">
                                                                {{ $child['account']->code }} - {{ $child['account']->account_name }} {{ $child['foreign_currency']['currency']->initial }}
                                                            </td>
                                                            <td style="text-align: right;">
                                                                {{ number_format($child['foreign_currency']['balance'], 2, ',', '.') }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach
                                        <tr id="footer" style="background-color: #597fb3">
                                            <th style="color: white; font-weight: bold;">TOTAL AKTIVA</th>
                                            <th style="color: white; text-align: right; font-weight: bold; text-decoration: underline; text-decoration-style: double;">
                                                {{ number_format($balanceSheet['aktiva']['total'], 2, ',', '.') }}
                                            </th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Passiva Column -->
                            <div class="col-md-6">
                                <table class="table">
                                    <thead>
                                        <tr style="background-color: #597FB3; text-align: center;">
                                            <th colspan="2" style="color: white;">PASSIVA</th>
                                        </tr>
                                        <tr style="background-color: #597FB3; border-top: 2px solid #597FB3;">
                                            <th style="color: white;">Kode Akun</th>
                                            <th style="color: white; text-align: right;">Saldo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($balanceSheet['passiva']['data'] as $accountGroup)
                                            @if($accountGroup['header'])
                                                <tr>
                                                    <td style="padding-left: 20px; font-weight: bold;">
                                                        {{ $accountGroup['header']->code }} - {{ $accountGroup['header']->account_name }}
                                                    </td>
                                                    <td style="text-align: right; font-weight: bold;">
                                                        {{ number_format($accountGroup['total'], 2, ',', '.') }}
                                                    </td>
                                                </tr>
                                                @foreach($accountGroup['children'] as $child)
                                                    <tr>
                                                        <td style="padding-left: 40px;">
                                                            {{ $child['account']->code }} - {{ $child['account']->account_name }} {{ $idrCurrency->initial }}
                                                        </td>
                                                        <td style="text-align: right;">
                                                            {{ number_format($child['balance'], 2, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                    @if(isset($child['foreign_currency']) && $child['foreign_currency'])
                                                        <tr>
                                                            <td style="padding-left: 40px;">
                                                                {{ $child['account']->code }} - {{ $child['account']->account_name }} {{ $child['foreign_currency']['currency']->initial }}
                                                            </td>
                                                            <td style="text-align: right;">
                                                                {{ number_format($child['foreign_currency']['balance'], 2, ',', '.') }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @else
                                                @foreach($accountGroup['children'] as $child)
                                                    <tr>
                                                        <td style="padding-left: 20px;">
                                                            {{ $child['account']->code }} - {{ $child['account']->account_name }} {{ $idrCurrency->initial }}
                                                        </td>
                                                        <td style="text-align: right;">
                                                            {{ number_format($child['balance'], 2, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                    @if(isset($child['foreign_currency']) && $child['foreign_currency'])
                                                        <tr>
                                                            <td style="padding-left: 20px;">
                                                                {{ $child['account']->code }} - {{ $child['account']->account_name }} {{ $child['foreign_currency']['currency']->initial }}
                                                            </td>
                                                            <td style="text-align: right;">
                                                                {{ number_format($child['foreign_currency']['balance'], 2, ',', '.') }}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach
                                        <tr id="footer" style="background-color: #597fb3">
                                            <th style="color: white; font-weight: bold;">TOTAL PASSIVA</th>
                                            <th style="color: white; text-align: right; font-weight: bold; text-decoration: underline; text-decoration-style: double;">
                                                {{ number_format($balanceSheet['passiva']['total'], 2, ',', '.') }}
                                            </th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
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

