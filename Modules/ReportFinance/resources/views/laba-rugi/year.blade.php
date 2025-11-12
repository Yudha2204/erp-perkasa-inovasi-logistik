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
                       <div class="w-full d-flex justify-content-center align-items-center flex-column">
                            <p style="font-size: 18px; font-weight: 500;">PT Perkasa Inovasi Logistik</p>
                            <p style="font-size: 18px; margin-top: -10px; font-weight: 500; color: #467FF7;">Laba Rugi</p>
                            <p style="font-size: 18px; margin-top: -10px; font-weight: 500; color: #B14F4B;">Year: {{ $year }}</p>
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
                          
                          <div class="d-flex gap-4 flex-column px-5" id="bottomPrint">
                            @foreach($yearProfitLoss["data"] as $idx => $data)
                            @php
                                $labaRugi = $data;
                            @endphp
                            @if(count($data["income"]) > 0 || count($data["sales_discount"]) > 0 || count($data["cost_of_sale"]) > 0 || count($data["expense"]) > 0 || count($data["other_income"]) > 0 || count($data["other_expense"]) > 0 || count($data["rounding_difference"]) > 0 || count($data["exchange_profit_loss"]) > 0)
                            <div class="w-full d-flex flex-column justify-content-between mb-4">
                              <h4 class="mb-3 fw-bold" style="font-size: 18px; color: #59758B;">{{ $yearProfitLoss["month_name"][$idx] }}</h4>
                            @php
                                // Helper to calculate category total
                                $calcTotal = function($category) {
                                    $total = 0;
                                    foreach ($category as $accountType) {
                                        foreach ($accountType->master_accounts as $ma) {
                                            foreach ($ma->balance_accounts as $maba) {
                                                $total += $maba->credit - $maba->debit;
                                            }
                                        }
                                    }
                                    return $total;
                                };

                                // Calculate totals for each category
                                $total_income = $calcTotal($labaRugi["income"]);
                                $total_sales_discount = $calcTotal($labaRugi["sales_discount"]);
                                $total_cost_of_sale = $calcTotal($labaRugi["cost_of_sale"]);
                                $total_purchase_discount = $calcTotal($labaRugi["purchase_discount"]);
                                $total_expense = $calcTotal($labaRugi["expense"]);
                                $total_other_income = $calcTotal($labaRugi["other_income"]);
                                $total_other_expense = $calcTotal($labaRugi["other_expense"]);
                                $total_rounding_difference = $calcTotal($labaRugi["rounding_difference"]);
                                $total_exchange_profit_loss = $calcTotal($labaRugi["exchange_profit_loss"]);

                                // Calculate net profit/loss
                                $net_profit_loss = $total_income 
                                    + $total_sales_discount 
                                    + $total_cost_of_sale 
                                    + $total_purchase_discount 
                                    + $total_expense 
                                    + $total_other_income 
                                    + $total_other_expense 
                                    + $total_rounding_difference 
                                    + $total_exchange_profit_loss;

                                // Helper to render category
                                $renderCategory = function($category, $categoryName, $isNegative = false) {
                                    $total = 0;
                                    $all_zero = true;
                                    $master_accounts_output = [];
                                    
                                    foreach ($category as $accountType) {
                                        foreach ($accountType->master_accounts as $ma) {
                                            $total_maba = 0;
                                            foreach ($ma->balance_accounts as $maba) {
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
                                        }
                                    }
                                    
                                    return [
                                        'name' => $categoryName,
                                        'total' => $total,
                                        'all_zero' => $all_zero,
                                        'accounts' => $master_accounts_output
                                    ];
                                };
                            @endphp
                              <!-- Income -->
                              @php $income_data = $renderCategory($labaRugi["income"], "Income"); @endphp
                              @if(!$income_data["all_zero"])
                              <div class="w-full d-flex flex-column justify-content-between">
                                  <p style="color: #59758B; font-size: 16px;">Income</p>
                                  @foreach($income_data["accounts"] as $ma)
                                  @if($ma["total_maba"] != 0)
                                  <div class="w-full d-flex justify-content-between align-items-center" style="margin-left: 30px;">
                                      <div class="d-flex gap-3">
                                          <p>{{ $ma["code"] }}</p>
                                          <p>{{ $ma["account_name"] }}</p>
                                      </div>
                                      <p style="border-bottom: 1px solid black;">
                                          {{ $currency->initial }} {{ number_format(abs($ma["total_maba"]), 2) }}
                                      </p>
                                  </div>
                                  @endif
                                  @endforeach
                                  <div class="w-full d-flex justify-content-between align-items-center">
                                      <p style="color: #00CA4C; font-size: 16px;">Total Income</p>
                                      <p style="color: #00CA4C; font-size: 16px; border-bottom: 1px solid #00CA4C;">
                                          {{ $currency->initial }} {{ number_format(abs($income_data["total"]), 2) }}
                                      </p>
                                  </div>
                              </div>
                              @endif

                              <!-- Sales Discount (-) -->
                              @php $sales_discount_data = $renderCategory($labaRugi["sales_discount"], "Sales Discount", true); @endphp
                              @if(!$sales_discount_data["all_zero"])
                              <div class="w-full d-flex flex-column justify-content-between">
                                  <p style="color: #59758B; font-size: 16px;">Sales Discount (-)</p>
                                  @foreach($sales_discount_data["accounts"] as $ma)
                                  @if($ma["total_maba"] != 0)
                                  <div class="w-full d-flex justify-content-between align-items-center" style="margin-left: 30px;">
                                      <div class="d-flex gap-3">
                                          <p>{{ $ma["code"] }}</p>
                                          <p>{{ $ma["account_name"] }}</p>
                                      </div>
                                      <p style="border-bottom: 1px solid black;">
                                          ({{ $currency->initial }} {{ number_format(abs($ma["total_maba"]), 2) }})
                                      </p>
                                  </div>
                                  @endif
                                  @endforeach
                                  <div class="w-full d-flex justify-content-between align-items-center">
                                      <p style="color: #00CA4C; font-size: 16px;">Total Sales Discount</p>
                                      <p style="color: #00CA4C; font-size: 16px; border-bottom: 1px solid #00CA4C;">
                                          ({{ $currency->initial }} {{ number_format(abs($sales_discount_data["total"]), 2) }})
                                      </p>
                                  </div>
                              </div>
                              @endif

                              <!-- Cost of Sale (-) -->
                              @php $cost_of_sale_data = $renderCategory($labaRugi["cost_of_sale"], "Cost of Sale", true); @endphp
                              @if(!$cost_of_sale_data["all_zero"])
                              <div class="w-full d-flex flex-column justify-content-between">
                                  <p style="color: #59758B; font-size: 16px;">Cost of Sale (-)</p>
                                  @foreach($cost_of_sale_data["accounts"] as $ma)
                                  @if($ma["total_maba"] != 0)
                                  <div class="w-full d-flex justify-content-between align-items-center" style="margin-left: 30px;">
                                      <div class="d-flex gap-3">
                                          <p>{{ $ma["code"] }}</p>
                                          <p>{{ $ma["account_name"] }}</p>
                                      </div>
                                      <p style="border-bottom: 1px solid black;">
                                          ({{ $currency->initial }} {{ number_format(abs($ma["total_maba"]), 2) }})
                                      </p>
                                  </div>
                                  @endif
                                  @endforeach
                                  <div class="w-full d-flex justify-content-between align-items-center">
                                      <p style="color: #00CA4C; font-size: 16px;">Total Cost of Sale</p>
                                      <p style="color: #00CA4C; font-size: 16px; border-bottom: 1px solid #00CA4C;">
                                          ({{ $currency->initial }} {{ number_format(abs($cost_of_sale_data["total"]), 2) }})
                                      </p>
                                  </div>
                              </div>
                              @endif

                              <!-- Purchase Discount (+) -->
                              @php $purchase_discount_data = $renderCategory($labaRugi["purchase_discount"], "Purchase Discount"); @endphp
                              @if(!$purchase_discount_data["all_zero"])
                              <div class="w-full d-flex flex-column justify-content-between">
                                  <p style="color: #59758B; font-size: 16px;">Purchase Discount (+)</p>
                              </div>
                              @endif
                              @foreach($purchase_discount_data["accounts"] as $ma)
                              @if($ma["total_maba"] != 0)
                              <div class="w-full d-flex justify-content-between align-items-center" style="margin-left: 30px;">
                                  <div class="d-flex gap-3">
                                      <p>{{ $ma["code"] }}</p>
                                      <p>{{ $ma["account_name"] }}</p>
                                  </div>
                              </div>
                              @endif
                              @endforeach
                              <div class="w-full d-flex justify-content-between align-items-center">
                                  <p style="color: #00CA4C; font-size: 16px;">Total Purchase Discount</p>
                                  <p style="color: #00CA4C; font-size: 16px; border-bottom: 1px solid #00CA4C;">
                                      {{ $currency->initial }} {{ number_format(abs($purchase_discount_data["total"]), 2) }}
                                  </p>
                              </div>
                              </div>

                              <!-- Expense (-) -->
                              @php $expense_data = $renderCategory($labaRugi["expense"], "Expense", true); @endphp
                              @if(!$expense_data["all_zero"])
                              <div class="w-full d-flex flex-column justify-content-between">
                                  <p style="color: #59758B; font-size: 16px;">Expense (-)</p>
                                  @foreach($expense_data["accounts"] as $ma)
                                  @if($ma["total_maba"] != 0)
                                  <div class="w-full d-flex justify-content-between align-items-center" style="margin-left: 30px;">
                                      <div class="d-flex gap-3">
                                          <p>{{ $ma["code"] }}</p>
                                          <p>{{ $ma["account_name"] }}</p>
                                      </div>
                                      <p style="border-bottom: 1px solid black;">
                                          ({{ $currency->initial }} {{ number_format(abs($ma["total_maba"]), 2) }})
                                      </p>
                                  </div>
                                  @endif
                                  @endforeach
                                  <div class="w-full d-flex justify-content-between align-items-center">
                                      <p style="color: #00CA4C; font-size: 16px;">Total Expense</p>
                                      <p style="color: #00CA4C; font-size: 16px; border-bottom: 1px solid #00CA4C;">
                                          ({{ $currency->initial }} {{ number_format(abs($expense_data["total"]), 2) }})
                                      </p>
                                  </div>
                              </div>
                              @endif

                              <!-- Other Income (+) -->
                              @php $other_income_data = $renderCategory($labaRugi["other_income"], "Other Income"); @endphp
                              @if(!$other_income_data["all_zero"])
                              <div class="w-full d-flex flex-column justify-content-between">
                                  <p style="color: #59758B; font-size: 16px;">Other Income (+)</p>
                                  @foreach($other_income_data["accounts"] as $ma)
                                  @if($ma["total_maba"] != 0)
                                  <div class="w-full d-flex justify-content-between align-items-center" style="margin-left: 30px;">
                                      <div class="d-flex gap-3">
                                          <p>{{ $ma["code"] }}</p>
                                          <p>{{ $ma["account_name"] }}</p>
                                      </div>
                                      <p style="border-bottom: 1px solid black;">
                                          {{ $currency->initial }} {{ number_format(abs($ma["total_maba"]), 2) }}
                                      </p>
                                  </div>
                                  @endif
                                  @endforeach
                                  <div class="w-full d-flex justify-content-between align-items-center">
                                      <p style="color: #00CA4C; font-size: 16px;">Total Other Income</p>
                                      <p style="color: #00CA4C; font-size: 16px; border-bottom: 1px solid #00CA4C;">
                                          {{ $currency->initial }} {{ number_format(abs($other_income_data["total"]), 2) }}
                                      </p>
                                  </div>
                              </div>
                              @endif

                              <!-- Other Expense (-) -->
                              @php $other_expense_data = $renderCategory($labaRugi["other_expense"], "Other Expense", true); @endphp
                              @if(!$other_expense_data["all_zero"])
                              <div class="w-full d-flex flex-column justify-content-between">
                                  <p style="color: #59758B; font-size: 16px;">Other Expense (-)</p>
                                  @foreach($other_expense_data["accounts"] as $ma)
                                  @if($ma["total_maba"] != 0)
                                  <div class="w-full d-flex justify-content-between align-items-center" style="margin-left: 30px;">
                                      <div class="d-flex gap-3">
                                          <p>{{ $ma["code"] }}</p>
                                          <p>{{ $ma["account_name"] }}</p>
                                      </div>
                                      <p style="border-bottom: 1px solid black;">
                                          ({{ $currency->initial }} {{ number_format(abs($ma["total_maba"]), 2) }})
                                      </p>
                                  </div>
                                  @endif
                                  @endforeach
                                  <div class="w-full d-flex justify-content-between align-items-center">
                                      <p style="color: #00CA4C; font-size: 16px;">Total Other Expense</p>
                                      <p style="color: #00CA4C; font-size: 16px; border-bottom: 1px solid #00CA4C;">
                                          ({{ $currency->initial }} {{ number_format(abs($other_expense_data["total"]), 2) }})
                                      </p>
                                  </div>
                              </div>
                              @endif

                              <!-- Rounding Difference (+) -->
                              @php $rounding_difference_data = $renderCategory($labaRugi["rounding_difference"], "Rounding Difference"); @endphp
                              @if(!$rounding_difference_data["all_zero"])
                              <div class="w-full d-flex flex-column justify-content-between">
                                  <p style="color: #59758B; font-size: 16px;">Rounding Difference (+)</p>
                                  @foreach($rounding_difference_data["accounts"] as $ma)
                                  @if($ma["total_maba"] != 0)
                                  <div class="w-full d-flex justify-content-between align-items-center" style="margin-left: 30px;">
                                      <div class="d-flex gap-3">
                                          <p>{{ $ma["code"] }}</p>
                                          <p>{{ $ma["account_name"] }}</p>
                                      </div>
                                      <p style="border-bottom: 1px solid black;">
                                          {{ $currency->initial }} {{ number_format(abs($ma["total_maba"]), 2) }}
                                      </p>
                                  </div>
                                  @endif
                                  @endforeach
                                  <div class="w-full d-flex justify-content-between align-items-center">
                                      <p style="color: #00CA4C; font-size: 16px;">Total Rounding Difference</p>
                                      <p style="color: #00CA4C; font-size: 16px; border-bottom: 1px solid #00CA4C;">
                                          {{ $currency->initial }} {{ number_format(abs($rounding_difference_data["total"]), 2) }}
                                      </p>
                                  </div>
                              </div>
                              @endif

                              <!-- Exchange Profit/Loss (+) -->
                              @php $exchange_profit_loss_data = $renderCategory($labaRugi["exchange_profit_loss"], "Exchange Profit/Loss"); @endphp
                              @if(!$exchange_profit_loss_data["all_zero"])
                              <div class="w-full d-flex flex-column justify-content-between">
                                  <p style="color: #59758B; font-size: 16px;">Exchange Profit/Loss (+)</p>
                                  @foreach($exchange_profit_loss_data["accounts"] as $ma)
                                  @if($ma["total_maba"] != 0)
                                  <div class="w-full d-flex justify-content-between align-items-center" style="margin-left: 30px;">
                                      <div class="d-flex gap-3">
                                          <p>{{ $ma["code"] }}</p>
                                          <p>{{ $ma["account_name"] }}</p>
                                      </div>
                                      <p style="border-bottom: 1px solid black;">
                                          {{ $currency->initial }} {{ number_format(abs($ma["total_maba"]), 2) }}
                                      </p>
                                  </div>
                                  @endif
                                  @endforeach
                                  <div class="w-full d-flex justify-content-between align-items-center">
                                      <p style="color: #00CA4C; font-size: 16px;">Total Exchange Profit/Loss</p>
                                      <p style="color: #00CA4C; font-size: 16px; border-bottom: 1px solid #00CA4C;">
                                          {{ $currency->initial }} {{ number_format(abs($exchange_profit_loss_data["total"]), 2) }}
                                      </p>
                                  </div>
                              </div>
                              @endif

                              <!-- Net Profit/Loss -->
                              <div class="w-full d-flex flex-column justify-content-between mb-5">
                                  <div class="w-full d-flex justify-content-between align-items-center">
                                      <p style="color: #59758B; font-size: 16px; font-weight: bold">Net Profit/Loss</p>
                                      @if ($net_profit_loss < 0)
                                      <p style="color: #59758B; font-size: 16px; font-weight: bold; border-bottom: 1px solid #59758B;">
                                          ({{ $currency->initial }} {{ number_format(abs($net_profit_loss), 2) }})
                                      </p>
                                      @else
                                      <p style="color: #59758B; font-size: 16px; font-weight: bold; border-bottom: 1px solid #59758B;">
                                          {{ $currency->initial }} {{ number_format($net_profit_loss, 2) }}
                                      </p>
                                      @endif
                                  </div>
                              </div>
                            </div>
                          @endif
                          @endforeach
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
