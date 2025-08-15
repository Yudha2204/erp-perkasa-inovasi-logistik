@extends('layouts.app')
@section('content')
<div class="main-content app-content mt-0">
    <div class="side-app">
        <!-- CONTAINER -->
        <div class="main-container container-fluid">
            <!-- PAGE-HEADER -->
            <div class="page-header mb-0">
                <h1>Laporan Rekening</h1>
            </div>
            <h4 style="color: #015377">Print</h4>
            <!-- PAGE-HEADER END -->
            <div class="row">
                <div class="col-xl-12" style="background-color: white; padding: 100px; overflow:hidden;" id="onPrint">
                    <h4 style="font-weight: bold;">PT. Perkasa Inovasi Logistik</h4>
                    <h4 style="font-weight: bold;">
                        {{$relatedSaos[0]->account == "piutang" ? "Customer Outstanding Invoice Report By Currency (Order By Invoice Date" : "Supplier Outstanding Invoice Report By Currency (Order By Invoice Date)" }}
                    </h4>
                    <h6 style="margin-top: 6vh; font-weight: bold;">Criteria</h3>
                    <table style="justify-content: space-between; align-items: center; display: flex; margin-left: 2vh; font-weight: bold;">
                        <tr>
                            <td style="padding-right: 4vh;">Date</td>
                            <td>:</td>
                            <td style="padding-left: 1vh;">{{ date('d-M-Y') }}</td>
                        </tr>
                        <tr>
                            <td style="padding-right: 4vh;">Customer</td>
                            <td>:</td>
                            <td style="padding-left: 1vh;">{{$relatedSaos[0]->contact->customer_name}}</td>
                        </tr>
                    </table>
                    <table class="w-100" style="margin-top: 1vh; font-weight: bold;">
                        <tr style="border-top: 2px solid black; border-bottom: 2px solid black;">
                            <th>Customer</th>
                            <th>Doc Date</th>
                            <th>Doc No</th>
                            <th>{{$relatedSaos[0]->account == "piutang" ? "Term" : "PO No" }}</th>
                            <th>{{$relatedSaos[0]->account == "piutang" ? "Due Date" : "Term" }}</th>
                            <th style="text-align: right;">Transaction Amount</th>
                            <th style="text-align: right;">Payment Amoun</th>
                            <th style="text-align: right;">Outstanding Amoun</th>
                        </tr>
                        <tr>
                            <td style="padding-top: 2vh;">Indonesia Rupiah</td>
                        </tr>
                        @php
                            $totalFinal = 0;
                            $alreadyPaidFinal = 0;
                            $remainingFinal = 0;
                        @endphp
                        @foreach ($relatedSaos as $sao)
                            <tr>
                                <td>{{ $sao->contact->customer_name }}</td>
                                <td>{{ $sao->date }}</td>
                                <td>
                                    @if ($sao->account == "piutang" && isset($sao->invoice))
                                        {{ $sao->invoice->transaction }}
                                    @elseif ($sao->account != "piutang" && isset($sao->order))
                                        {{ $sao->order->transaction }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if ($sao->account == "piutang" && isset($sao->invoice->term))
                                        {{ $sao->invoice->term->name }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if ($sao->account == "piutang" && isset($sao->invoice->term))
                                        {{ $sao->invoice->term->pay_days }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td style="text-align: right; border-bottom: 2px solid black; padding-bottom: 2vh;">
                                    <span style="float: left;">{{ $sao->currency->initial }}</span>
                                    <span style="float: right;">
                                        @php
                                            $totalFinal += $sao->total
                                        @endphp
                                        {{ number_format($sao->total, 2, '.', ',') }}
                                    </span>
                                </td>
                                <td style="text-align: right; border-bottom: 2px solid black; padding-bottom: 2vh;">
                                    @php
                                        $alreadyPaidFinal += $sao->already_paid;
                                    @endphp
                                    {{ number_format($sao->already_paid, 2, '.', ',') }}
                                </td>
                                <td style="text-align: right; border-bottom: 2px solid black; padding-bottom: 2vh;">
                                    @php
                                        $remainingFinal += $sao->remaining;
                                    @endphp
                                    {{ number_format($sao->remaining, 2, '.', ',') }}
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="text-align: right; border-bottom: 2px solid black; padding-top: 2vh; padding-bottom: 2vh;">
                                <span style="float: left;">{{$sao->currency->initial}}</span>
                                <span style="float: right;">{{ number_format($totalFinal, 2, '.', ',') }}</span>
                            </td>
                            <td style="text-align: right; border-bottom: 2px solid black; padding-top: 2vh; padding-bottom: 2vh;">{{ number_format($alreadyPaidFinal, 2, '.', ',')  }}</td>
                            <td style="text-align: right; border-bottom: 2px solid black; padding-top: 2vh; padding-bottom: 2vh;">{{ number_format($remainingFinal, 2, '.', ',')  }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="padding-top: 1vh; padding-bottom: 1vh;">
                                <span style="float: left; margin-right: -5vh">Total {{$sao->currency->currency_name}}</span>
                                <span style="float: right; margin-right: 5vh">:</span> 
                            </td>
                            <td style="text-align: right; border-bottom: 2px solid black; padding-top: 2vh; padding-bottom: 2vh;">
                                <span style="float: left;">{{$sao->currency->initial}}</span>
                                <span style="float: right;">{{ number_format($totalFinal, 2, '.', ',') }}</span>
                            </td>
                            <td style="text-align: right; border-bottom: 2px solid black; padding-top: 2vh; padding-bottom: 2vh;">{{ number_format($alreadyPaidFinal, 2, '.', ',')  }}</td>
                            <td style="text-align: right; border-bottom: 2px solid black; padding-top: 2vh; padding-bottom: 2vh;">{{ number_format($remainingFinal, 2, '.', ',')  }}</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="border-top: 2px solid black;">
                                <span style="float: left; margin-right: -5vh">Total {{$sao->currency->initial}}</span>
                                <span style="float: right; margin-right: 5vh">:</span> 
                            </td>
                            <td style="text-align: right;">
                                <span style="float: left;">{{$sao->currency->initial}}</span>
                                <span style="float: right;">{{ number_format($totalFinal, 2, '.', ',') }}</span>    
                            </td>
                            <td style="text-align: right;">{{ number_format($alreadyPaidFinal, 2, '.', ',')  }}</td>
                            <td style="text-align: right;">{{ number_format($remainingFinal, 2, '.', ',')  }}</td>
                        </tr>
                    </table>
                </div>
                <div class="d-flex my-5 gap-4">
                    <a href="{{ route('finance.report-finance.laporan-rekening') }}">
                        <button class="btn btn-primary">back</button>
                    </a>
                    <button id="print" class="btn" style="background-color: white; border: 1px solid black;">print</button>
                </div>
            </div>
        </div>
        <!-- CONTAINER CLOSED -->
    </div>
</div>
@endsection
@push('styles')
    <style>
    @media print {
        body * {
            visibility: hidden;
            font-size: 12px;
        }
        #onPrint, #onPrint * {
            visibility: visible;
        }
        #onPrint {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
    }
    </style>
@endpush
@push('scripts')
    <script>
        var ones = ['', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];
        var tens = ['', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];
        var teens = ['ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'];
        function convert_millions(num) {
            if (num >= 1000000) {
                return convert_millions(Math.floor(num / 1000000)) + " million " + convert_thousands(num % 1000000);
            } else {
                return convert_thousands(num);
            }
        }
        function convert_thousands(num) {
            if (num >= 1000) {
                return convert_hundreds(Math.floor(num / 1000)) + " thousand " + convert_hundreds(num % 1000);
            } else {
                return convert_hundreds(num);
            }
        }
        function convert_hundreds(num) {
            if (num > 99) {
                return ones[Math.floor(num / 100)] + " hundred " + convert_tens(num % 100);
            } else {
                return convert_tens(num);
            }
        }
        function convert_tens(num) {
            if (num < 10) return ones[num];
            else if (num >= 10 && num < 20) return teens[num - 10];
            else {
                return tens[Math.floor(num / 10)] + " " + ones[num % 10];
            }
        }
        function convert(num) {
            if (num == 0) return "zero";
            else return convert_millions(num);
        }
        function convertDecimalPart(decimalPart) {
            if (!decimalPart) return "";
            let decimalWords = "";
            for (let i = 0; i < decimalPart.length; i++) {
                decimalWords += ones[parseInt(decimalPart[i])] + " ";
            }
            return decimalWords.trim();
        }
        function main() {
            var number = document.getElementById('number').innerText;
            var parts = number.match(/([A-Z]+)\s+([\d,\.]+)/);
            var currency = parts[1];
            var numberString = parts[2].replace(/,/g, ''); // Remove commas
            var [integerPart, decimalPart] = numberString.split('.');
            
            var integerWords = convert(parseInt(integerPart));
            var decimalWords = decimalPart ? convertDecimalPart(decimalPart) : "";
            
            var result = `In words: ${integerWords}`;
            if (decimalWords) {
                result += ` point ${decimalWords}`;
            }
            
            document.getElementById('word').innerText = result;
        }
        main();
    </script>
    <script>
    document.getElementById("print").addEventListener("click", function() {
        var printContent = document.getElementById("onPrint").innerHTML;
        var iframe = document.createElement('iframe');
        iframe.style.position = 'absolute';
        iframe.style.width = '0px';
        iframe.style.height = '0px';
        iframe.style.border = 'none';
        document.body.appendChild(iframe);
        var doc = iframe.contentWindow.document;
        doc.open();
        doc.write('<html><head><title>Print</title>');
        // Sertakan semua file CSS yang diperlukan
        var styles = document.querySelectorAll('link[rel="stylesheet"], style');
        styles.forEach(function(style) {
            doc.write(style.outerHTML);
        });
        doc.write('<style>@media print { body * { visibility: hidden; } #printSection, #printSection * { visibility: visible; } #printSection { position: absolute; left: 0; top: 0; width: 100%; } }</style>');
        doc.write('</head><body>');
        doc.write('<div id="printSection">');
        doc.write(printContent);
        doc.write('</div>');
        doc.write('</body></html>');
        doc.close();
        iframe.contentWindow.focus();
        iframe.contentWindow.print();
        setTimeout(function() {
            document.body.removeChild(iframe);
        }, 100);
    });
    </script>
@endpush