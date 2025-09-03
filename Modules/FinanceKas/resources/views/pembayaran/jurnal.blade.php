@extends('layouts.app')
@section('content')
    <div class="main-content app-content mt-0 pb-5">
        <div class="side-app">
            <!-- CONTAINER -->
            <div class="main-container container-fluid">
                <!-- PAGE-HEADER -->
                <div class="page-header">
                    <h1 style="color: #59758B; font-size: medium; font-size: 35px;">Journal Cash & Bank Out</h1>
                </div>
                <!-- PAGE-HEADER END -->
                <div class="" style="margin-bottom: 50px;">
                    <table style="width: fit-content; margin-bottom: 50px;">
                        <tr>
                            <td style="padding: 10px; font-size: 18px;">Nomor Transaksi</td>
                            <td style="padding: 10px; font-size: 18px;">:</td>
                            <td style="padding: 10px; font-size: 18px;">{{ $kas->transaction }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px; font-size: 18px;">Date</td>
                            <td style="padding: 10px; font-size: 18px;">:</td>
                            <td style="padding: 10px; font-size: 18px;">{{ \Carbon\Carbon::parse($kas->date_kas_out)->format('j F, Y') }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px; font-size: 18px;">Description</td>
                            <td style="padding: 10px; font-size: 18px;">:</td>
                            <td style="padding: 10px; font-size: 18px;">{{ $kas->description }}</td>
                        </tr>
                    </table>
    
                    <table class="table">
                        <thead style="border: 2px solid #015377; background-color: #015377;">
                            <tr>
                                <th scope="col"style="text-align: center; font-size: 18px; color: white;">Account Code</th>
                                <th scope="col"style="text-align: center; font-size: 18px; color: white;">Account Name</th>
                                <th scope="col"style="text-align: center; font-size: 18px; color: white;">Debit</th>
                                <th scope="col"style="text-align: center; font-size: 18px; color: white;">Kredit</th>
                            </tr>
                        </thead>
                        <tbody>
                        @php
                            $currency = "IDR";
                            if($kas->currency->initial) {
                                $currency = $kas->currency->initial;
                            }
                            $credit = 0;
                            $debit = 0;
                        @endphp
                          @foreach ($kas->jurnal as $j)
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
                            </tr>
                        </tbody>
                    </table>
                </div>

                <a href="{{ route('finance.kas.pembayaran.index') }}">
                    <button class="btn btn-primary">back</button>
                </a>
            </div>
            <!-- CONTAINER CLOSED -->
        </div>
    </div>
@endsection