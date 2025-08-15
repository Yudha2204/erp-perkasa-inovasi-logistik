<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Marketing Report</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <style>
        * {
            font-size: 14px;
            font-family: 'Times New Roman';
        }

        h1 {
            font-size: 18px;
        }

        table {
            border: 1px solid black;
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid black;
            padding: 10px;
        }

        tr th {
            font-weight: 500;
        }

        th {
            text-align: center;
            border-collapse: collapse;
            white-space: nowrap;
        }

        td {
            border-collapse: collapse;
            text-align: center;
        }

        @page {
            size: A4 landscape;
        }
    </style>
</head>

<body>
    <h1 style="text-align: center; font-size: 25px">REFENUE REPORT FOR SALES MARKETING</h1>
    <table id="printed">
        <thead>
            <tr>
                <th>No</th>
                <th style="max-width: 20%">CUSTOMER NAME</th>
                <th>NO. INV</th>
                <th style="max-width: 20%">Commodity/Description</th>
                <th>SELLING</th>
                <th>COST</th>
                <th>PROFIT</th>
            </tr>
        </thead>
        <tbody>
            @php
                $int = 1;
            @endphp
            @forelse ($data as $data)
                <tr>
                    <td>{{ $int }}</td>
                    <td>{{ $data->contact?->customer_name ?? 'undefined' }}</td>
                    <td>{{ $data->no_cipl }}</td>
                    <td>{{ $data->description }}</td>
                    <td>
                        @if ($data->quotation)
                        {{ $data->quotation->currency->initial }}
                            @if($data->getDataCalculate()["isInvoice"] === false)
                            {{ number_format($data->quotation->sales_value, 2, '.',',') }}
                            @else
                            {{ number_format($data->getDataCalculate()["selling"], 2, '.',',') }}
                            @endif
                        @else
                        
                        @endif
                    </td>
                    <td>{{ $data->getDataCalculate()["cost"] }}</td>
                    <td>{{ $data->getDataCalculate()["profit"] }}</td>
                </tr>
                @php
                    $int++;
                @endphp
            @empty
                <td colspan="7" align="center">
                    <span class="text-danger">
                        <strong>Data is Empty</strong>
                    </span>
                </td>
            @endforelse
        </tbody>
    </table>
</body>
