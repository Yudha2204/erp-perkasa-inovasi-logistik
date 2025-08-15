<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Operation Report External</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <style>
        * {
            font-size: 11px;
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
    {{-- <h1 style="text-align: center; font-size: 25px">REPORT INBOUND </h1> --}}
    <table id="printed">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Kategori</th>
                <th>Domestik /<br>International</th>
                <th>Customer</th>
                <th>Tujuan</th>
                <th>Asal</th>
                <th>Dimensi</th>
                <th>VOL/CBM</th>
                <th>Berat Kg</th>
                <th>Plt/Cly/Pkgs</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $int = 1;
            @endphp
            @forelse ($mergedData as $key => $item)
            <tr>
                <td>{{ $int++ }}</td>
                <td>{{ $item->pickup_date }}</td>
                <td>{{ $item->marketing->source }}</td>
                <td>
                    @if ($item->marketing->expedition == 1)
                        Domestik
                    @else
                        International
                    @endif
                    <br>
                    @if ($item->transportation == 1)
                        Air Freight
                    @elseif ($item->transportation == 2)
                        Sea Freight
                    @else 
                        Land Trucking
                    @endif
                    -
                    {{ $item->transportation_desc }}
                </td>
                <td>{{ $item->marketing->contact->customer_name }}</td>
                <td>{{ $item->destination }}</td>
                <td>{{ $item->origin }}</td>
                <td>
                    @if ($item->marketing->dimensions)
                        @foreach ($item->marketing->dimensions as $dimension)
                        {{ $dimension->length }} | {{ $dimension->width }} | {{ $dimension->height }} <br>
                        @endforeach
                    @endif
                </td>
                <td>@if ($item->marketing->total_volume)
                        {{ $item->marketing->total_volume }} {{ $item->marketing->freetext_volume }} 
                    @endif 
                </td>
                <td>{{ $item->marketing->total_weight }}</td>
                <td>
                    @if ($item->marketing->dimensions)
                        @foreach ($item->marketing->dimensions as $dimension)
                        {{ $dimension->packages }} <br>
                        @endforeach
                    @endif
                </td>
                <td>{{ $item->marketing->description }}</td>
                @empty
                <td colspan="12">
                    <span class="text-danger">
                        <strong>Data is Empty</strong>
                    </span>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
