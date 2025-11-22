<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/logo-chart-dark.svg') }}" />
    <style>
        body {
            padding: 0 .5em;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.9em
        }

        table {            
            caption-side: bottom;
            border-collapse: collapse;
        }

        .table-bordered {
            width: 100%;
            margin-top: 1.5em;
            border: 1px solid black;
        }

        .table-bordered th,
        .table-bordered td, {
            padding: .5em .7em ;
            border: 1px solid black;
        }
        
        .table-bordered tfoot {
            font-weight: bold;
        }

        .remarks {
            width: 100% - .7em;
            border: 1px solid black;
            margin-top: 1em;
            padding: 0 .7em;
            box-sizing: border-box;
        }

        .remarks h5 {
            margin: 0.5em 0;
        }
    </style>
</head>
<body>
    
    {{-- <h1 style="position: absolute; color: rgba(255, 0, 0, 0.5); font-size: 20em; transform: rotate(-45); top: 10%; display: {{ ($reservation->status == 'inactive') ? 'block' : 'none' }};">VOID</h1> --}}

    <div class="header">
        <table style="width: 100%;">
            <tr>
                <td style="">
                    <table>
                        <tr>
                            <td><h1 style="margin: 0;">ALJU SHOES CLEAN</h1></td>
                        </tr>
                        <tr>
                            <td class="m-0 p-0">Jl. Johar Baru Utara 4 No.20 RT.03/RW.09.</td>
                        </tr>
                        <tr>
                            <td class="m-0 p-0">Jakarta Pusat, Jakarta, Indonesia.</td>
                        </tr>
                    </table>
                </td>
                <td style="">
                    <h3>INVOICE</h3>
                </td>
            </tr>
        </table>
    </div>
    <hr>
    <table style="margin-top: 1.5em">
        <tr>
            <td style="padding-bottom: .2em; width: {{ $transaction->transaction_type == 'dropzone' ? '130px' : '165px' }}">No. Invoice</td>
            <td style="padding-bottom: .2em; font-weight: bold;">{{ $transaction->invoice_no }}</td>
        </tr>
        <tr>
            <td style="padding-bottom: .2em;">Tanggal Invoice</td>
            <td style="padding-bottom: .2em; font-weight: bold;">{{ date('d M Y', strtotime($transaction->created_at)) }}</td>
        </tr>
        <tr>
            <td style="padding-bottom: .85em;">Pemesan</td>
            <td style="padding-bottom: .85em; font-weight: bold;">{{ $transaction->customer->name }}</td>
        </tr>
        <tr>
            <td style="padding-bottom: .2em;">Tanggal {{ $transaction->transaction_type == 'dropzone' ? 'Mulai' : 'Penjemputan' }}</td>
            <td style="padding-bottom: .2em; font-weight: bold;">{{ date('d M Y, H:i:s', strtotime($transaction->transaction_start)) }}</td>
        </tr>
        <tr>
            <td style="padding-bottom: .2em;">Tanggal {{ $transaction->transaction_type == 'dropzone' ? 'Selesai' : 'Pengantaran' }}</td>
            <td style="padding-bottom: .2em; font-weight: bold;">{{ date('d M Y, H:i:s', strtotime($transaction->transaction_end)) }}</td>
        </tr>
    </table>
    <table border="1" cellspacing="0" class="table-bordered" style="font-size: .9em">
        <thead>
            <tr>
                <th>No</th>
                <th>Item</th>
                <th>Merk</th>
                <th>Deskripsi</th>
                <th>Treatment</th>
                <th>Harga</th>
            </tr>
        </thead>
        <tbody>
            @php
                $i = 1;
            @endphp
            @foreach ($transaction->transaction_details as $item)
                <tr>
                    <td style="padding-bottom: 10px; padding-top: 10px;" align="center">{{ $i }}</td>
                    <td style="padding-bottom: 10px; padding-top: 10px;">{{ ucfirst($item['type']) }}</td>
                    <td style="padding-bottom: 10px; padding-top: 10px;">{{ $item['merk'] }}<span class="text-secondary fw-semibold">{{ $item['size'] ? ', Size ' : '' }}</span>{{ $item['size'] ?? '' }}</td>
                    <td style="padding-bottom: 10px; padding-top: 10px;">{{ $item->description }}</td>
                    <td style="padding-bottom: 10px; padding-top: 10px;">{{ $item->treatment_name }}</td>
                    <td style="padding-bottom: 10px; padding-top: 10px;" align="center">IDR {{ number_format($item->cost, 0, '.', ',') }}</td>
                </tr>
                @php
                    $i++;
                @endphp
            @endforeach
            @if ($transaction->shipping_cost)
                <tr>
                    <td style="padding-bottom: 10px; padding-top: 10px;" align="center">{{ $i }}</td>
                    <td style="padding-bottom: 10px; padding-top: 10px;" colspan="4">Biaya Pickup & Delivery</td>
                    <td style="padding-bottom: 10px; padding-top: 10px;" align="center">IDR {{ number_format($transaction->shipping_cost, 0, ',', '.') }}</td>
                </tr>
            @endif
            @if ($transaction->treatment_discount)
                <tr style="color: red;">
                    <td style="padding-bottom: 10px; padding-top: 10px;" align="center">#</td>
                    <td style="padding-bottom: 10px; padding-top: 10px;" colspan="4">Diskon Treatment</td>
                    <td style="padding-bottom: 10px; padding-top: 10px;" align="center">IDR {{ number_format($transaction->treatment_discount, 0, ',', '.') }}</td>
                </tr>
            @endif
            @if ($transaction->delivery_discount)
                <tr style="color: red;">
                    <td style="padding-bottom: 10px; padding-top: 10px;" align="center">#</td>
                    <td style="padding-bottom: 10px; padding-top: 10px;" colspan="4">Diskon Delivery</td>
                    <td style="padding-bottom: 10px; padding-top: 10px;" align="center">IDR {{ number_format($transaction->delivery_discount, 0, ',', '.') }}</td>
                </tr>
            @endif
        </tbody>
        <tfoot>
            <tr>
                <td style="padding-bottom: 10px; padding-top: 10px;" colspan="5" id="total-colspan">Total</td>
                <td align="center" style="white-space: nowrap; padding-bottom: 10px; padding-top: 10px;">IDR {{ number_format($transaction->total_cost, 0, '.', ',') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="remarks">
        <h5 style="font-size: 1em; margin-bottom: 0">PERHATIAN</h5>
        <ol style="margin-top: 8px; padding-left: 25px;">
            <li style="margin-bottom: 5px;">Pengambilan barang oleh konsumen harap disertai nota.</li>
            <li style="margin-bottom: 5px;">Pengambilan barang baik di counter maupun diantarkan (delivery) wajib diperiksa oleh konsumen. Kehilangan/kerusakan setelah barang diterima konsumen diluar tanggung jawab Alju Shoes Clean.</li>
            <li style="margin-bottom: 5px;">Barang yang tidak diambli selama 1 bulan terhitung sejak tanggal masuk barang, maka kehilangan dan kerusakan diluar tanggung jawab Alju Shoes Clean.</li>
            <li style="margin-bottom: 5px;">Setiap konsumen dianggap setuju dengan peraturan diatas.</li>
        </ol>
    </div>

    <table style="margin-top: 2em; width: 100%; text-align:right;">
        <tr>
            <td>Jakarta Pusat, {{ date('d M Y') }}</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>Alju Shoes Clean</td>
        </tr>
    </table>

</body>
</html>