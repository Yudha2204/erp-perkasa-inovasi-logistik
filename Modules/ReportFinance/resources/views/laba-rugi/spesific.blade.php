@extends('layouts.app')
@section('content')

    <div class="main-content app-content mt-0 pb-5">
        <div class="side-app">

            <!-- CONTAINER -->
            <div class="main-container container-fluid" id="onPrint">

                <!-- PAGE-HEADER -->
                <div class="page-header">
                    <h1 style="font-size: 36px; font-weight: bold; color: #015377;" id="titleTop" >Laba Rugi</h1>
                </div>
                <!-- PAGE-HEADER END -->

                <div class="row" style="background-color: white; padding-top: 50px; padding-bottom: 50px;" id="headerPrint">
                    <div class="col-xl-12">
                        <!-- if pay -->
                        <div class="w-full" style="margin-bottom: 50px;">
                            <div class="w-full d-flex justify-content-center align-items-center flex-column mb-5">
                                    <p style="font-size: 18px; font-weight: 500;">PT Perkasa Inovasi Logistik</p>
                                    <p style="font-size: 18px; margin-top: -10px; font-weight: 500; color: #467FF7;">Laba Rugi</p>
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
                        
                        <!-- Pendapatan -->
                        <div class="w-full d-flex flex-column justify-content-between px-5" id="bottomPrint">
                            <div class="w-full d-flex flex-column justify-content-between">
                                <p style="color: #59758B; font-size: 16px;">Pendapatan</p>
                                @php
                                    $total_pendapatan = 0;
                                @endphp
                                @foreach ($pendapatan as $p)
                                    @php
                                        $total = 0;
                                        $all_zero = true;
                                        $master_accounts_output = [];
                                    @endphp
                                    @foreach ($p["master_accounts"] as $ma)
                                        @php
                                            $total_maba = 0;
                                            foreach ($ma["balance_accounts"] as $maba) {
                                                $total_maba += $maba->credit - $maba->debit;
                                            }
                                            if ($total_maba != 0) {
                                                $all_zero = false;
                                            }
                                            $total += $total_maba;
                                            $master_accounts_output[] = [
                                                'code' => $ma->code,
                                                'account_name' => $ma->account_name,
                                                'total_maba' => $total_maba
                                            ];
                                        @endphp
                                    @endforeach

                                    @if(!$all_zero)
                                        <p style="margin-left: 30px; color: #367EA3; font-size: 16px;">{{ $p->name }}</p>
                                    @foreach($master_accounts_output as $ma)
                                    @if($ma["total_maba"] != 0)
                                        <div class="w-full d-flex justify-content-between align-items-center" style="margin-left: 50px;">
                                            <p>{{ $ma["code"] }}</p>
                                            <p>{{ $ma["account_name"] }}</p>
                                                @if ($ma["total_maba"] < 0)
                                                    <p style="border-bottom: 1px solid black;">(
                                                    {{ $currency->initial }} 
                                                    {{ number_format($ma["total_maba"]*-1, 2) }}
                                                    )
                                                    </p>
                                                @else
                                                    <p style="border-bottom: 1px solid black;">
                                                    {{ $currency->initial }} 
                                                    {{ number_format($ma["total_maba"], 2) }}
                                                    </p>
                                                @endif
                                        </div>
                                    @endif
                                    @endforeach
                                    <div class="w-full d-flex justify-content-between align-items-center">
                                        <p style="color: #00CA4C; font-size: 16px;">Total {{ $p->name }}</p>
                                        @if ($total < 0)
                                            <p style="color: #00CA4C; font-size: 16px; border-bottom: 1px solid #00CA4C;">
                                                ({{ $currency->initial }} {{ number_format($total*-1, 2) }})
                                            </p>
                                        @else
                                            <p style="color: #00CA4C; font-size: 16px; border-bottom: 1px solid #00CA4C;">
                                                {{ $currency->initial }} {{ number_format($total, 2) }}
                                            </p>
                                        @endif
                                    </div>
                                    @endif
                                    @php
                                        $total_pendapatan += $total;
                                    @endphp
                                @endforeach
                                <div class="w-full d-flex justify-content-between align-items-center">
                                    <p style="color: #59758B; font-size: 16px; font-weight: bold">Total Pendapatan</p>
                                    @if ($total_pendapatan < 0)
                                    <p style="color: #59758B; font-size: 16px; font-weight: bold; border-bottom: 1px solid #59758B;"">
                                        ({{ $currency->initial }} {{ number_format($total_pendapatan*-1, 2) }})
                                    </p>
                                    @else
                                    <p style="color: #59758B; font-size: 16px; font-weight: bold; border-bottom: 1px solid #59758B;"">
                                        {{ $currency->initial }} {{ number_format($total_pendapatan, 2) }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Bebas Atas Pendapatan -->
                        <div class="w-full d-flex flex-column justify-content-between px-5">
                            <div class="w-full d-flex flex-column justify-content-between">
                                <p style="color: #59758B; font-size: 16px;">Beban Atas Pendapatan</p>
                                @php
                                    $total_beban = 0;
                                @endphp
                                @foreach ($beban as $b)
                                    @php
                                        $total = 0;
                                        $all_zero = true;
                                        $master_accounts_output = [];
                                    @endphp
                                    @foreach ($b["master_accounts"] as $ma)
                                        @php
                                            $total_maba = 0;
                                            foreach ($ma["balance_accounts"] as $maba) {
                                                $total_maba += $maba->credit - $maba->debit;
                                            }
                                            if ($total_maba != 0) {
                                                $all_zero = false;
                                            }
                                            $total += $total_maba;
                                            $master_accounts_output[] = [
                                                'code' => $ma->code,
                                                'account_name' => $ma->account_name,
                                                'total_maba' => $total_maba
                                            ];
                                            $total += $total_maba;
                                        @endphp
                                    @endforeach

                                    @if(!$all_zero)
                                        <p style="margin-left: 30px; color: #367EA3; font-size: 16px;">{{ $b->name === 'Biaya Produksi' ? 'Biaya Vendor' : $b->name }}</p>
                                    @foreach($master_accounts_output as $ma)
                                    @if($ma["total_maba"] != 0)
                                        <div class="w-full d-flex justify-content-between align-items-center" style="margin-left: 50px;">
                                            <p>{{ $ma["code"] }}</p>
                                            <p>{{ $ma["account_name"] === 'Biaya Produksi' ? 'Biaya Vendor' : $ma["account_name"] }}</p>
                                                @if ($ma["total_maba"] < 0)
                                                    <p style="border-bottom: 1px solid black;">(
                                                    {{ $currency->initial }} 
                                                    {{ number_format($ma["total_maba"]*-1, 2) }}
                                                    )
                                                    </p>
                                                @else
                                                    <p style="border-bottom: 1px solid black;">
                                                    {{ $currency->initial }} 
                                                    {{ number_format($ma["total_maba"], 2) }}
                                                    </p>
                                                @endif
                                        </div>
                                    @endif
                                    @endforeach
                                    <div class="w-full d-flex justify-content-between align-items-center">
                                        <p style="color: #00CA4C; font-size: 16px;">
                                            Total {{ $b->name === 'Biaya Produksi' ? 'Biaya Vendor' : $b->name }}
                                        </p>
                                        @if ($total < 0)
                                            <p style="color: #00CA4C; font-size: 16px; border-bottom: 1px solid #00CA4C;">
                                                ({{ $currency->initial }} {{ number_format($total*-1, 2) }})
                                            </p>
                                        @else
                                            <p style="color: #00CA4C; font-size: 16px; border-bottom: 1px solid #00CA4C;">
                                                {{ $currency->initial }} {{ number_format($total, 2) }}
                                            </p>
                                        @endif
                                    </div>
                                    @endif
                                    @php
                                        $total_beban += $total;
                                    @endphp
                                @endforeach
                                <div class="w-full d-flex justify-content-between align-items-center">
                                    <p style="color: #59758B; font-size: 16px; font-weight: bold">Total Beban Atas Pendapatan</p>
                                    @if ($total_beban < 0)
                                    <p style="color: #59758B; font-size: 16px; font-weight: bold; border-bottom: 1px solid #59758B;">
                                        ({{ $currency->initial }} {{ number_format($total_beban*-1, 2) }})
                                    </p>
                                    @else
                                    <p style="color: #59758B; font-size: 16px; font-weight: bold; border-bottom: 1px solid #59758B;">{{ $currency->initial }} {{ number_format($total_beban, 2) }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="w-full d-flex flex-column justify-content-between px-5">
                            <div class="w-full d-flex justify-content-between align-items-center">
                                <p style="color: #59758B; font-size: 16px;">Total Laba Kotor</p>
                                @if ($total_pendapatan - $total_beban < 0)
                                <p style="color: #59758B; font-size: 16px; border-bottom: 1px solid #59758B;">
                                    ({{ $currency->initial }} {{ number_format(($total_pendapatan - $total_beban)*-1, 2) }})
                                </p>
                                @else
                                <p style="color: #59758B; font-size: 16px; border-bottom: 1px solid #59758B;">{{ $currency->initial }} {{ number_format(($total_pendapatan - $total_beban), 2) }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Bebas Operasional -->
                        <div class="w-full d-flex flex-column justify-content-between px-5">
                            <div class="w-full d-flex flex-column justify-content-between">
                                <p style="color: #59758B; font-size: 16px;">Beban Operasional</p>
                                @php
                                    $total_beban_operasional = 0;
                                @endphp
                                @foreach ($beban_operasional as $bo)
                                    @php
                                        $total = 0;
                                        $all_zero = true;
                                        $master_accounts_output = [];
                                    @endphp
                                    @foreach ($bo["master_accounts"] as $ma)
                                        @php
                                            $total_maba = 0;
                                            foreach ($ma["balance_accounts"] as $maba) {
                                                $total_maba += $maba->credit - $maba->debit;
                                            }
                                            if ($total_maba != 0) {
                                                $all_zero = false;
                                            }
                                            $total += $total_maba;
                                            $master_accounts_output[] = [
                                                'code' => $ma->code,
                                                'account_name' => $ma->account_name,
                                                'total_maba' => $total_maba
                                            ];
                                            $total += $total_maba;
                                        @endphp
                                    @endforeach

                                    @if(!$all_zero)
                                        <p style="margin-left: 30px; color: #367EA3; font-size: 16px;">{{ $bo->name }}</p>
                                    @foreach($master_accounts_output as $ma)
                                    @if($ma["total_maba"] != 0)
                                        <div class="w-full d-flex justify-content-between align-items-center" style="margin-left: 50px;">
                                            <p>{{ $ma["code"] }}</p>
                                            <p>{{ $ma["account_name"] }}</p>
                                                @if ($ma["total_maba"] < 0)
                                                    <p style="border-bottom: 1px solid black;">(
                                                    {{ $currency->initial }} 
                                                    {{ number_format($ma["total_maba"]*-1, 2) }}
                                                    )
                                                    </p>
                                                @else
                                                    <p style="border-bottom: 1px solid black;">
                                                    {{ $currency->initial }} 
                                                    {{ number_format($ma["total_maba"], 2) }}
                                                    </p>
                                                @endif
                                        </div>
                                    @endif
                                    @endforeach
                                    <div class="w-full d-flex justify-content-between align-items-center">
                                        <p style="color: #00CA4C; font-size: 16px;">Total {{ $bo->name }}</p>
                                        @if ($total < 0)
                                        <p style="color: #00CA4C; font-size: 16px; border-bottom: 1px solid #00CA4C;">({{ $currency->initial }} {{ number_format($total*-1, 2) }})</p>
                                        @else
                                        <p style="color: #00CA4C; font-size: 16px; border-bottom: 1px solid #00CA4C;">{{ $currency->initial }} {{ number_format($total, 2) }}</p>
                                        @endif
                                    </div>
                                    @endif
                                    @php
                                        $total_beban_operasional += $total;
                                    @endphp
                                @endforeach
                                <div class="w-full d-flex justify-content-between align-items-center">
                                    <p style="color: #59758B; font-size: 16px; font-weight: bold">Total Beban Operasional</p>
                                    @if ($total_beban_operasional < 0)
                                    <p style="color: #59758B; font-size: 16px; font-weight: bold; border-bottom: 1px solid #59758B;"">
                                        ({{ $currency->initial }} {{ number_format($total_beban_operasional*-1, 2) }})</p>
                                    @else
                                    <p style="color: #59758B; font-size: 16px; font-weight: bold; border-bottom: 1px solid #59758B;"">{{ $currency->initial }} {{ number_format($total_beban_operasional, 2) }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="w-full d-flex flex-column justify-content-between px-5 mb-5">
                            <div class="w-full d-flex justify-content-between align-items-center">
                                <p style="color: #59758B; font-size: 16px;">Laba Bersih</p>
                                @if ($total_pendapatan - $total_beban - $total_beban_operasional < 0)
                                <p style="color: #59758B; font-size: 16px; border-bottom: 1px solid #59758B;">({{ $currency->initial }} {{ number_format(($total_pendapatan - $total_beban - $total_beban_operasional)*-1, 2) }})</p>
                                @else
                                <p style="color: #59758B; font-size: 16px; border-bottom: 1px solid #59758B;">{{ $currency->initial }} {{ number_format(($total_pendapatan - $total_beban - $total_beban_operasional), 2) }}</p>
                                @endif
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
            #bottomPrint {
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
