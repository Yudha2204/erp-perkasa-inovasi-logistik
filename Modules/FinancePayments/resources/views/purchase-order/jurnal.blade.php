@extends('layouts.app')
@section('content')
    <div class="main-content app-content mt-0 pb-5">
        <div class="side-app">
            <!-- CONTAINER -->
            <div class="main-container container-fluid">
                <!-- PAGE-HEADER -->
                <div class="page-header">
                    <h1 style="color: #59758B; font-size: medium; font-size: 35px;">Journal Account Payable</h1>
                </div>
                <!-- PAGE-HEADER END -->
                <div class="" style="margin-bottom: 50px;">
                    <table style="width: fit-content; margin-bottom: 50px;">
                        <tr>
                            <td style="padding: 10px; font-size: 18px;">Nomor Transaksi</td>
                            <td style="padding: 10px; font-size: 18px;">:</td>
                            <td style="padding: 10px; font-size: 18px;">{{ $jurnal->transaction }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px; font-size: 18px;">Date</td>
                            <td style="padding: 10px; font-size: 18px;">:</td>
                            <td style="padding: 10px; font-size: 18px;">{{ \Carbon\Carbon::parse($jurnal->date_order)->format('j F, Y') }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px; font-size: 18px;">Description</td>
                            <td style="padding: 10px; font-size: 18px;">:</td>
                            <td style="padding: 10px; font-size: 18px;">{{ $jurnal->description }}</td>
                        </tr>
                    </table>

                    <table class="table">
                        <thead style="border: 2px solid #015377; background-color: #015377;">
                            <tr>
                                <th scope="col"style="text-align: center; font-size: 18px; color: white;">Account Code</th>
                                <th scope="col"style="text-align: center; font-size: 18px; color: white;">Account Name</th>
                                <th scope="col"style="text-align: center; font-size: 18px; color: white;">Debit</th>
                                <th scope="col"style="text-align: center; font-size: 18px; color: white;">Kredit</th>
                                @if ($jurnal->currency_id != 1)
                                <th scope="col"style="text-align: center; font-size: 18px; color: white;">Debit</th>
                                <th scope="col"style="text-align: center; font-size: 18px; color: white;">Kredit</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @php
                              $currency = "IDR";
                              if($jurnal->currency) {
                                  $currency = $jurnal->currency->initial;
                              }
                              $credit = 0;
                              $debit = 0;
                              $debitIDR = 0;
                              $creditIDR = 0;
                            @endphp
                          @foreach ($jurnal->jurnal as $j)
                          @php
                                  $currencyIDR = $jurnal->jurnalIDR[$loop->index]->currency->initial;
                          @endphp
                          @if($j->debit > 0 || $j->credit > 0)
                          <tr>
                              <td style="border: 2px solid #015377; font-size: 18px; color: #015377;">
                                  {{ $j->master_account->code }}
                              </td>
                              <td style="border: 2px solid #015377; font-size: 18px; color: #015377;">
                                  {{ $j->master_account->account_name }}
                              </td>
                              <td style="border: 2px solid #015377; font-size: 18px; color: #015377;">
                                  @php
                                   $debit += $j->debit;
                                  @endphp
                                  @if($j->debit > 0)
                                  {{ $currency }} {{ number_format($j->debit, 2, '.', ','); }}
                                  @else
                                  -
                                  @endif
                              </td>
                              <td style="border: 2px solid #015377; font-size: 18px; color: #015377;">
                                  @php
                                   $credit += $j->credit;
                                  @endphp
                                  @if($j->credit > 0)
                                  {{ $currency }} {{ number_format($j->credit, 2, '.', ','); }}
                                  @else
                                  -
                                  @endif
                              </td>
                              @if ($jurnal->currency_id != 1)
                              <td style="border: 2px solid #015377; font-size: 18px; color: #015377;">
                                @php
                                 $debitIDR += $jurnal->jurnalIDR[$loop->index]->debit;
                                @endphp
                                @if($jurnal->jurnalIDR[$loop->index]->debit > 0)
                                {{ $currencyIDR }} {{ number_format($jurnal->jurnalIDR[$loop->index]->debit, 2, '.', ','); }}
                                @else
                                -
                                @endif
                            </td>
                            <td style="border: 2px solid #015377; font-size: 18px; color: #015377;">
                                @php
                                 $creditIDR += $jurnal->jurnalIDR[$loop->index]->credit;
                                @endphp
                                @if($jurnal->jurnalIDR[$loop->index]->credit > 0)
                                {{ $currencyIDR }} {{ number_format($jurnal->jurnalIDR[$loop->index]->credit, 2, '.', ','); }}
                                @else
                                -
                                @endif
                            </td>
                                @endif

                          </tr>
                          @endif
                          @endforeach
                          <tr style="background-color: #015377; color: white">
                              <td style="border: 2px solid #015377; text-align: center; font-size: 18px; color: white;">Total</td>
                              <td style="border: 2px solid #015377; font-size: 18px; color: white;"></td>
                              <td style="border: 2px solid #015377; font-size: 18px; color: white;">
                                  {{ $currency }} {{ number_format($debit, 2, '.', ','); }}
                              </td>
                              <td style="border: 2px solid #015377; font-size: 18px; color: white;">
                                  {{ $currency }} {{ number_format($credit, 2, '.', ','); }}
                              </td>
                              @if ($jurnal->currency_id != 1)
                              <td style="border: 2px solid #015377; font-size: 18px; color: white;">
                                {{ $currencyIDR }} {{ number_format($debitIDR, 2, '.', ','); }}
                            </td>
                            <td style="border: 2px solid #015377; font-size: 18px; color: white;">
                                {{ $currencyIDR }} {{ number_format($creditIDR, 2, '.', ','); }}
                            </td>
                              @endif

                          </tr>
                        </tbody>
                    </table>
                </div>

                <a href="{{ route('finance.payments.account-payable.index') }}">
                    <button class="btn btn-primary">Back</button>
                </a>
            </div>
            <!-- CONTAINER CLOSED -->
        </div>
    </div>
@endsection

@push('scripts')
@endpush
