@extends('layouts.app')
@section('content')

    <div class="main-content app-content mt-0 pb-5">
        <div class="side-app">

            <!-- CONTAINER -->
            <div class="main-container container-fluid" id="onPrint">

                <!-- PAGE-HEADER -->
                <div class="page-header">
                    <h1 style="font-size: 36px; font-weight: bold; color: #015377;" id="titleTop" >Arus Kas</h1>
                </div>
                <!-- PAGE-HEADER END -->

                <div class="row" style="background-color: white; padding-top: 50px; padding-bottom: 50px;" id="headerPrint">
                    <div class="col-xl-12">
                        <!-- if pay -->
                        <div class="w-full">
                            <div class="w-full d-flex justify-content-center align-items-center flex-column mb-5">
                                    <p style="font-size: 18px; font-weight: 500;">PT Perkasa Inovasi Logistik</p>
                                    <p style="font-size: 18px; margin-top: -10px; font-weight: 500; color: #467FF7;">Arus Kas</p>
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
                            <div style="font-size: 16px; font-weight: 400;" class="prints">Semua Departemen</div>
                            <div style="width: 100%; margin-bottom: 10px; margin-top: 10px; height: 1px; background-color: black;" class="prints"></div>
                            <div style="font-size: 16px; font-weight: 400; width: 100%; text-align: end; margin-bottom: 40px; color: #B14F4B;" class="prints">Saldo</div>
                        </div>
                        
                        @if(isset($account["Operation"]))
                        <div class="w-full d-flex flex-column justify-content-between px-5" id="bottomPrint">
                            <p style="color: #59758B; font-size: 16px;">Operation</p>
                            @php
                                $total_operation = 0;
                            @endphp
                            @foreach($account["Operation"] as $operation)
                                @php
                                    $total_op = 0;
                                    $all_zero = true;
                                    $master_accounts_output = [];
                                @endphp
                                @foreach($operation->master_accounts as $a)
                                    @php
                                        $total_aba = 0;
                                        foreach ($a->balance_accounts as $aba) {
                                            $total_aba += $aba->credit - $aba->debit;
                                        }
                                        if ($total_aba != 0) {
                                            $all_zero = false;
                                        }
                                        $total_op += $total_aba;
                                        $master_accounts_output[] = [
                                            'code' => $a->code,
                                            'account_name' => $a->account_name,
                                            'total_aba' => $total_aba
                                        ];
                                    @endphp
                                @endforeach

                                @if(!$all_zero)
                                <p style="margin-left: 30px; color: #367EA3; font-size: 16px;">{{ $operation->name }}</p>
                                @foreach($master_accounts_output as $a)
                                @if($a["total_aba"] != 0)
                                <div class="w-full d-flex justify-content-between align-items-center" style="margin-left: 50px;">
                                    <p>{{ $a["code"] }}</p>
                                    <p>{{ $a["account_name"] }}</p>
                                        @if ($a["total_aba"] < 0)
                                            <p style="border-bottom: 1px solid black;">(
                                            {{ $currency->initial }} 
                                            {{ number_format($a["total_aba"]*-1, 2) }}
                                            )
                                            </p>
                                        @else
                                            <p style="border-bottom: 1px solid black;">
                                            {{ $currency->initial }} 
                                            {{ number_format($a["total_aba"], 2) }}
                                            </p>
                                        @endif
                                </div>
                                @endif
                                @endforeach
                                <div class="w-full d-flex justify-content-between align-items-center">
                                    <p style="color: #00CA4C; font-size: 16px;">Total {{ $operation->name }}</p>
                                    @if ($total_op < 0)
                                        <p style="color: #00CA4C; font-size: 16px; border-bottom: 1px solid #00CA4C;">
                                            ({{ $currency->initial }} {{ number_format($total_op*-1, 2) }})
                                        </p>
                                    @else
                                        <p style="color: #00CA4C; font-size: 16px; border-bottom: 1px solid #00CA4C;">
                                            {{ $currency->initial }} {{ number_format($total_op, 2) }}
                                        </p>
                                    @endif
                                </div>
                                @endif
                                @php
                                    $total_operation += $total_op;
                                @endphp
                            @endforeach
                            <div class="w-full d-flex justify-content-between align-items-center">
                                <p style="color: #00CA4C; font-size: 16px;">Operation</p>
                                @if ($total_operation < 0)
                                    <p style="color: #00CA4C; font-size: 16px; border-bottom: 1px solid #00CA4C;">
                                        ({{ $currency->initial }} {{ number_format($total_operation*-1, 2) }})
                                    </p>
                                @else
                                    <p style="color: #00CA4C; font-size: 16px; border-bottom: 1px solid #00CA4C;">
                                        {{ $currency->initial }} {{ number_format($total_operation, 2) }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        @endif

                        @if(isset($account["Investing"]))
                        <div class="w-full d-flex flex-column justify-content-between px-5">
                            <p style="color: #59758B; font-size: 16px;">Investing</p>
                            @php
                                $total_investing = 0;
                            @endphp
                            @foreach($account["Investing"] as $investing)
                                @php
                                    $total_inv = 0;
                                    $all_zero = true;
                                    $master_accounts_output = [];
                                @endphp
                                @foreach($investing->master_accounts as $a)
                                    @php
                                        $total_aba = 0;
                                        foreach ($a->balance_accounts as $aba) {
                                            $total_aba += $aba->credit - $aba->debit;
                                        }
                                        if ($total_aba != 0) {
                                            $all_zero = false;
                                        }
                                        $total_inv += $total_aba;
                                        $master_accounts_output[] = [
                                            'code' => $a->code,
                                            'account_name' => $a->account_name,
                                            'total_aba' => $total_aba
                                        ];
                                    @endphp
                                @endforeach
                                
                                @if(!$all_zero)
                                <p style="margin-left: 30px; color: #367EA3; font-size: 16px;">{{ $investing->name }}</p>
                                @foreach($master_accounts_output as $a)
                                @if($a["total_aba"] != 0)
                                <div class="w-full d-flex justify-content-between align-items-center" style="margin-left: 50px;">
                                    <p>{{ $a["code"] }}</p>
                                    <p>{{ $a["account_name"] }}</p>
                                        @if ($a["total_aba"] < 0)
                                            <p style="border-bottom: 1px solid black;">(
                                            {{ $currency->initial }} 
                                            {{ number_format($a["total_aba"]*-1, 2) }}
                                            )
                                            </p>
                                        @else
                                            <p style="border-bottom: 1px solid black;">
                                            {{ $currency->initial }} 
                                            {{ number_format($a["total_aba"], 2) }}
                                            </p>
                                        @endif
                                </div>
                                @endif
                                @endforeach
                                <div class="w-full d-flex justify-content-between align-items-center">
                                    <p style="color: #00CA4C; font-size: 16px;">Total {{ $investing->name }}</p>
                                    @if ($total_inv < 0)
                                        <p style="color: #00CA4C; font-size: 16px; border-bottom: 1px solid #00CA4C;">
                                            ({{ $currency->initial }} {{ number_format($total_inv*-1, 2) }})
                                        </p>
                                    @else
                                        <p style="color: #00CA4C; font-size: 16px; border-bottom: 1px solid #00CA4C;">
                                            {{ $currency->initial }} {{ number_format($total_inv, 2) }}
                                        </p>
                                    @endif
                                </div>
                                @endif
                                @php
                                   $total_investing += $total_inv; 
                                @endphp
                            @endforeach
                            <div class="w-full d-flex justify-content-between align-items-center">
                                <p style="color: #00CA4C; font-size: 16px;">Investing</p>
                                @if ($total_investing < 0)
                                    <p style="color: #00CA4C; font-size: 16px; border-bottom: 1px solid #00CA4C;">
                                        ({{ $currency->initial }} {{ number_format($total_investing*-1, 2) }})
                                    </p>
                                @else
                                    <p style="color: #00CA4C; font-size: 16px; border-bottom: 1px solid #00CA4C;">
                                        {{ $currency->initial }} {{ number_format($total_investing, 2) }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        @endif

                        @if(isset($account["Financing"]))
                        <div class="w-full d-flex flex-column justify-content-between px-5">
                            <p style="color: #59758B; font-size: 16px;">Financing</p>
                            @php
                                $total_financing = 0;
                            @endphp
                            @foreach($account["Financing"] as $financing)
                                @php
                                    $total_fin = 0;
                                    $all_zero = true;
                                    $master_accounts_output = [];
                                @endphp
                                @foreach($financing->master_accounts as $a)
                                    @php
                                        $total_aba = 0;
                                        foreach ($a->balance_accounts as $aba) {
                                            $total_aba += $aba->credit - $aba->debit;
                                        }
                                        if ($total_aba != 0) {
                                            $all_zero = false;
                                        }
                                        $total_fin += $total_aba;
                                        $master_accounts_output[] = [
                                            'code' => $a->code,
                                            'account_name' => $a->account_name,
                                            'total_aba' => $total_aba
                                        ];
                                    @endphp
                                @endforeach

                                @if(!$all_zero)
                                <p style="margin-left: 30px; color: #367EA3; font-size: 16px;">{{ $financing->name }}</p>
                                @foreach($master_accounts_output as $a)
                                @if($a["total_aba"] != 0)
                                <div class="w-full d-flex justify-content-between align-items-center" style="margin-left: 50px;">
                                    <p>{{ $a["code"] }}</p>
                                    <p>{{ $a["account_name"] }}</p>
                                        @if ($a["total_aba"] < 0)
                                            <p style="border-bottom: 1px solid black;">(
                                            {{ $currency->initial }} 
                                            {{ number_format($a["total_aba"]*-1, 2) }}
                                            )
                                            </p>
                                        @else
                                            <p style="border-bottom: 1px solid black;">
                                            {{ $currency->initial }} 
                                            {{ number_format($a["total_aba"], 2) }}
                                            </p>
                                        @endif
                                </div>
                                @endif
                                @endforeach
                                <div class="w-full d-flex justify-content-between align-items-center">
                                    <p style="color: #00CA4C; font-size: 16px;">Total {{ $financing->name }}</p>
                                    @if ($total_fin < 0)
                                        <p style="color: #00CA4C; font-size: 16px; border-bottom: 1px solid #00CA4C;">
                                            ({{ $currency->initial }} {{ number_format($total_fin*-1, 2) }})
                                        </p>
                                    @else
                                        <p style="color: #00CA4C; font-size: 16px; border-bottom: 1px solid #00CA4C;">
                                            {{ $currency->initial }} {{ number_format($total_fin, 2) }}
                                        </p>
                                    @endif
                                </div>
                                @endif
                                @php
                                    $total_financing += $total_fin;
                                @endphp
                            @endforeach
                            <div class="w-full d-flex justify-content-between align-items-center">
                                <p style="color: #00CA4C; font-size: 16px;">Financing</p>
                                @if ($total_financing < 0)
                                    <p style="color: #00CA4C; font-size: 16px; border-bottom: 1px solid #00CA4C;">
                                        ({{ $currency->initial }} {{ number_format($total_financing*-1, 2) }})
                                    </p>
                                @else
                                    <p style="color: #00CA4C; font-size: 16px; border-bottom: 1px solid #00CA4C;">
                                        {{ $currency->initial }} {{ number_format($total_financing, 2) }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        @endif
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
            #bottomPrint {
                margin-top: -50px;
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
                var printClass = document.querySelectorAll('.prints');
    
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

                printClass.forEach(element => {
                    if(element.style.display === "none") {
                        element.style.display = "block"
                    } else {
                        element.style.display = "none"
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
                exportButton.style.display = 'block';
                addEventListeners()
            });
        }

        document.addEventListener("DOMContentLoaded", function() {
            addEventListeners();
        });
    </script>
@endpush