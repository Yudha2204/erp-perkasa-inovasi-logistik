@extends('layouts.app')
@section('content')

<div class="main-content app-content mt-0">
    <div class="side-app">

        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <!-- PAGE-HEADER -->
            <div class="page-header mb-0">
                <h1>Invoice</h1>
            </div>f
            <h4 style="color: #015377">Print</h4>
            <!-- PAGE-HEADER END -->

            <div class="row">
                <div class="col-xl-12" style="background-color: white; padding: 100px; overflow:hidden;" id="onPrint">
                    @if($photos)
                        <div style="width: 100%; display: flex; justify-content: center; align-items: center; flex-direction: column; margin-bottom: 30px;">
                            <img src="{{ asset('storage/' . $photos->image_path) }}" alt="{{ $photos->title }}" style="max-width: 100px">
                            <p style="width: 80%; text-align: center; margin-top: 20px;">{{ $photos->address }}</p>
                        </div>
                    @endif
                    <div class="d-flex justify-content-center align-items-center mb-5">
                        <h3 style="font-weight: bold;" id="title-pdf">INVOICE</h3>
                    </div>
                    <div id="desOnePrint" class="d-flex justify-content-between align-items-center" style="position: relative; margin-bottom: 100px;">
                       <div style="position: absolute; top: 0px; left: 0px;">
                        <p style="margin-right: 10px;">Bill To:</p>
                        <div id="dataBillTo" style="position: absolute; top: 0px; left: 100%; width: 400px;">
                        @foreach($dataBillTo as $idx => $bil)
                             <div style="white-space: pre-wrap; word-wrap: break-word;">{{$bil}}</div>
                        @endforeach
                        </div>
                       </div>
                       <table style="position: absolute; top: 0px; right: 0px;">
                            <tr>
                                <td>INVOICE NO</td>
                                <td>:</td>
                                <td style="width: fit-content; padding-left: 5px;">{{$invoiceData->transaction ? $invoiceData->transaction : "-"}}</td>
                            </tr>
                            <tr>
                                <td>JOB AWB NO</td>
                                <td>:</td>
                                <td style="width: fit-content; padding-left: 5px;">{{$invoiceData->sales->marketing->job_order_id ?? "-"}}</td>
                            </tr>
                            <tr>
                                <td>INVOICE DATE</td>
                                <td>:</td>
                                <td style="width: fit-content; padding-left: 5px;">{{$invoiceData->date_invoice ? $invoiceData->date_invoice : "-"}}</td>
                            </tr>
                            <tr>
                                <td>TERMS</td>
                                <td>:</td>
                                <td style="width: fit-content; padding-left: 5px;">{{$invoiceData->term->name ? $invoiceData->term->name : "-"}}</td>
                            </tr>
                            <tr>
                                <td>CURRENCY</td>
                                <td>:</td>
                                <td style="width: fit-content; padding-left: 5px;">{{$currency->initial ? $currency->initial : "-"}}</td>
                            </tr>
                       </table>
                    </div>
                    <div id="desTwoPrint" class="d-flex justify-content-between align-items-center mt-8">
                       <table>
                            <tr>
                                <td>SHIPPER</td>
                                <td>:</td>
                                <td style="width: fit-content; padding-left: 5px;">{{$shipper ? $shipper : "-"}}</td>
                            </tr>
                            <tr>
                                <td>CONSIGNEE</td>
                                <td>:</td>
                                <td style="width: fit-content; padding-left: 5px;">{{$consignee ? $consignee : "-"}}</td>
                            </tr>
                            <tr>
                                <td>COMMODITY</td>
                                <td>:</td>
                                <td style="width: fit-content; padding-left: 5px;">{{$comodity ? $comodity : "-"}}</td>
                            </tr>
                       </table>
                    </div>

                    <div class="w-full mt-5" style="border: 1px solid black; height: fit-content;">
                        <p style="width: 100%; text-align: center; font-size: 15px; font-weight: bold; margin-bottom: -1px; border-bottom: 1px solid black;" id="description-title">DESCRIPTION</p>
                        <div class="d-flex justify-content-between align-items-center px-5 mt-5 pb-5" style="border-bottom: 1px solid black;" id="table-first">
                            <table>
                                    <tr>
                                        <td>MBL/MAWB</td>
                                        <td>:</td>
                                        <td style="width: fit-content; padding-left: 5px;">{{$mbl ? $mbl : "-"}}</td>
                                    </tr>
                                    <tr>
                                        <td>HBL/HAWB</td>
                                        <td>:</td>
                                        <td style="width: fit-content; padding-left: 5px;">{{$hbl ? $hbl : "-"}}</td>
                                    </tr>
                                    <tr>
                                        <td>Voyage/Flight</td>
                                        <td>:</td>
                                        <td style="width: fit-content; padding-left: 5px;">{{$voyage ? $voyage : "-"}}</td>
                                    </tr>
                                    <tr>
                                        <td>Depart Date</td>
                                        <td>:</td>
                                        <td style="width: fit-content; padding-left: 5px;">{{$depart_date ? $depart_date : "-"}}</td>
                                    </tr>
                            </table>
                            <table>
                                    <tr>
                                        <td>Origin/Destination</td>
                                        <td>:</td>
                                        <td style="width: fit-content; padding-left: 5px;">{{$origin ? $origin : "-"}}</td>
                                    </tr>
                                    <tr>
                                        <td>Packages</td>
                                        <td>:</td>
                                        <td style="width: fit-content; padding-left: 5px;">{{$invoiceDetail ? count($invoiceDetail) : "-"}}</td>
                                    </tr>
                                    <tr>
                                        <td>Weight(Kg)</td>
                                        <td>:</td>
                                        <td style="width: fit-content; padding-left: 5px;">{{$weight ? $weight : "-"}}</td>
                                    </tr>
                                    <tr>
                                        <td>Volumetric/M3</td>
                                        <td>:</td>
                                        <td style="width: fit-content; padding-left: 5px;">{{$volumetrik ? $volumetrik : "..."}}/{{$m3 ? $m3 : "..."}}</td>
                                    </tr>
                                    <tr>
                                        <td>Chargetable Weight</td>
                                        <td>:</td>
                                        <td style="width: fit-content; padding-left: 5px;">{{$chargetableWeight ? $chargetableWeight : "-"}}</td>
                                    </tr>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between align-items-center" style="border-bottom: 1px solid black;" id="table-second">
                            <div style="width: 50%; height: {{ count($invoiceDetail) < 4 ? '250px' : '100%' }}; display:flex; align-items:center; flex-direction: column;" id="desPadding">
                                <div style="width: 100%; height: 50px;"></div>
                                <div class="px-5 d-flex flex-column justify-content-start align-items-start mt-5" id="description-table" style="width: 100%; height: 100%; word-wrap: break-word;">
                                    <!-- {{$invoiceData->description ? $invoiceData->description : "-"}} -->
                                    @foreach($invoiceDetail as $index => $data)
                                        <p style="">{{$data->description ? $data->description : "-"}}</p>
                                    @endforeach
                                </div>
                            </div>
                            <div style="width: 50%;">
                                <div class="d-flex justify-content-between align-items-center" style="height: 50px;">
                                    <div class="d-flex justify-content-center align-items-center" style="width: 100%; text-align: center; height: 100%; border-bottom: 1px solid black; border-right: 1px solid black; border-left: 1px solid black;">UOM</div>
                                    <div class="d-flex justify-content-center align-items-center" style="width: 100%; text-align: center; height: 100%; border-bottom: 1px solid black; border-right: 1px solid black;">Price/Exchange Rate</div>
                                    <div class="d-flex justify-content-center align-items-center" style="width: 100%; text-align: center; height: 100%; border-bottom: 1px solid black;">Amount</div>
                                </div>
                                <div class="d-flex" style="height: {{ count($invoiceDetail) < 4 ? '200px' : '100%' }};">
                                    <div id="uomPrint" class="d-flex flex-column py-3 justify-content-start align-items-center" style="width: 100%; height: 100%; border-right: 1px solid black; border-left: 1px solid black;">
                                    @foreach($invoiceDetail as $index => $data)
                                        <p style="">{{$data->uom ? $data->uom : "-"}}</p>
                                    @endforeach
                                    </div>
                                    <div id="exchangeRatePrint" class="d-flex flex-column py-3 justify-content-start align-items-center" style="width: 100%; text-align: center; height: 100%; border-right: 1px solid black;">
                                    @foreach($invoiceDetail as $index => $data)
                                        <p style="">{{$currency->initial}} {{$data->price ? number_format($data->price,2,'.',',') : "-"}}</p>
                                    @endforeach
                                    </div>
                                    <div id="amountPrint" class="d-flex flex-column py-3 justify-content-start align-items-center" style="width: 100%; text-align: center; height: 100%;">
                                    @foreach($invoiceDetail as $index => $data)
                                        <p style="">{{$currency->initial}} {{$data->total ? number_format($data->total,2,'.',',') : "-"}}</p>
                                    @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div style="width: 50%; border-right: 1px solid black;"></div>
                            <div style="width: 50%;">
                                <div class="d-flex justify-content-between align-items-center" style="height: 50px;">
                                    <div class="d-flex justify-content-center align-items-center" style="width: 100%; text-align: center; height: 100%;"></div>
                                    <div class="d-flex justify-content-center align-items-center" style="width: 100%; text-align: center; height: 100%;">Total</div>
                                    <div class="d-flex justify-content-center align-items-center" style="width: 100%; text-align: center; height: 100%; border-left: 1px solid black;">{{$currency->initial}} {{$invoiceData->total ? number_format($invoiceData->total,2,'.',',') : "-"}}</div>
                                    <div class="justify-content-center align-items-center" style="width: 100%; text-align: center; height: 100%; border-left: 1px solid black; display: none;" id="number">{{$currency->initial}} {{$invoiceData->total ? $invoiceData->total : "-"}}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <p class="mt-8 mb-9" id="word"></p>
                    {!! (count($invoiceDetail) % 11) == 0 ? '<div id="separatorOverTen"></div>' : '' !!}

                    <div id="signaturePrint" class="d-flex justify-content-end align-items-center">
                        <div style="width: 200px; border-top: 1px solid black; text-align: center;">Authorize Signature</div>
                    </div>

                    <p style="margin-top: 20px;">Cheque payment should be crossed and make payable to PT. PERKASA INOVASI LOGISTIK</p>
                    <div class="w-full page-break"  style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;">
                    @foreach($filterFormCurrency as $idx => $item)
                        <table class="d-flex flex-column p-4" style="border: 1px solid black;">
                            <tr>
                                <td>Fund Transfer - {{ $item["currency"] }} Account No</td>
                                <td style="padding-left: 20px; padding-right: 20px;">:</td>
                                <td>{{$item["fund"]}}</td>
                            </tr>
                            <tr>
                                <td>Bank Name</td>
                                <td style="padding-left: 20px; padding-right: 20px;">:</td>
                                <td>{{$item["bankName"]}}</td>
                            </tr>
                            <tr>
                                <td>Address</td>
                                <td style="padding-left: 20px; padding-right: 20px;">:</td>
                                <td>{{$item["address"]}}</td>
                            </tr>
                            <tr>
                                <td>SWIFT Code</td>
                                <td style="padding-left: 20px; padding-right: 20px;">:</td>
                                <td>{{$item["code"]}}</td>
                            </tr>
                        </table>
                    @endforeach
                    </div>

                </div>

                <div class="d-flex my-5 gap-4">
                    <a href="{{ route('finance.piutang.invoice.index') }}">
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
        /* Sertakan semua CSS yang digunakan untuk tampilan layar */
        @import url('path/to/your/stylesheet.css');

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
        /* Tambahkan aturan CSS lainnya untuk mode cetak */
        .d-flex {
            display: flex;
        }
        .justify-content-center {
            justify-content: center;
        }
        .align-items-center {
            align-items: center;
        }
        .justify-content-between {
            justify-content: space-between;
        }
        .mb-5 {
            margin-bottom: 3rem;
        }
        .mt-5 {
            margin-top: 1rem;
        }
        .pb-5 {
            padding-bottom: 3rem;
        }
        .px-5 {
            padding-left: 3rem;
            padding-right: 3rem;
        }
        .ms-5 {
            margin-left: 3rem;
        }
        #title-pdf {
            font-size: 23px;
            margin-bottom: 40px;
        }
        #dataBillTo{
            margin-top: 12px;
        }

        #desOnePrint{
            margin-top: -60px;
        }

        #table-first{
            padding-bottom: 20px;
            padding-top: -20px;
            padding-left: 20px;
            padding-right: 20px;
        }

        /* #table-second{
            margin-top: 200px;
        } */

        #desTwoPrint{
            margin-top: 20px;
        }

        #uomPrint {
            display: flex;
            flex-direction: column;
            justify-content: left;
            width: 100%;
            text-align: center;
            height: 100%;
            border-right: 1px solid black;
        }

        #exchangeRatePrint {
            display: flex;
            flex-direction: column;
            justify-content: left;
            width: 100%;
            text-align: center;
            height: 100%;
            border-right: 1px solid black;
        }

        #amountPrint {
            display: flex;
            flex-direction: column;
            justify-content: left;
            width: 100%;
            text-align: center;
            height: 100%;
        }

        #word{
            margin-top: 10px;
            margin-bottom: 150px;
        }

        #separatorOverTen{
            height: 100px;
        }

        #signaturePrint {
            margin-top: -40px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }
        #description-title{
            font-size: 20px;
            margin-top: 2px;
            padding-bottom: 2px;
        }

        #description-table{
            display: flex;
            flex-direction: column;
            padding-top: 20px;
        }

        #desPadding{
            padding-left: 20px;
            padding-right: 20px;
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