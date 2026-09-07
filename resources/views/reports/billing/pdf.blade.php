<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Rincian Faktur - {{ $customer->name }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            font-size: 11px;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .company-name {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .company-details {
            font-size: 9px;
            color: #555;
            margin-top: 5px;
        }
        .title {
            font-size: 13px;
            font-weight: bold;
            text-align: center;
            text-decoration: underline;
            margin-bottom: 20px;
            text-transform: uppercase;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 20px;
            border: none;
        }
        .meta-td {
            width: 50%;
            vertical-align: top;
            border: none;
            padding: 0;
        }
        .meta-label {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
            border-bottom: 1px solid #ddd;
            margin-bottom: 5px;
            padding-bottom: 2px;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #ddd;
            padding: 6px 8px;
            text-align: left;
        }
        table.data-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-row {
            font-weight: bold;
            background-color: #f8f9fa;
        }
    </style>
</head>
@php
    $logoPath = public_path('images/logo.jpg');
    $logoData = file_exists($logoPath) ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($logoPath)) : null;
@endphp
<body>

    <table style="width: 100%; border-collapse: collapse; border-bottom: 3px double #dc2626; padding-bottom: 8px; margin-bottom: 15px;">
        <tr>
            <td style="width: 80px; vertical-align: middle; border: none; padding: 0;">
                @if($logoData)
                    <img src="{{ $logoData }}" style="height: 55px; width: auto;" alt="Logo AS">
                @else
                    <img src="{{ asset('images/logo.jpg') }}" style="height: 55px; width: auto;" alt="Logo AS">
                @endif
            </td>
            <td style="vertical-align: middle; text-align: center; border: none; padding: 0 10px 0 0;">
                <h1 style="color: #dc2626; font-family: Arial, sans-serif; font-size: 24px; font-weight: 900; margin: 0; letter-spacing: 2px; text-transform: uppercase;">
                    ABADI SENTOSA
                </h1>
                <div style="font-family: Arial, sans-serif; font-size: 8.5px; font-weight: bold; color: #1e3a8a; margin: 3px 0; letter-spacing: 0.5px;">
                    KONTRAKTOR <span style="color: #dc2626;">&bull;</span> PERDAGANGAN UMUM <span style="color: #dc2626;">&bull;</span> PERCETAKAN <span style="color: #dc2626;">&bull;</span> JASA
                </div>
                <div style="font-family: Arial, sans-serif; font-size: 8px; color: #374151; line-height: 1.3;">
                    Jl. H. Sapari silih asih No. 5 Bandung 085860180550<br>
                    e-mail : ferry4850@gmail.com
                </div>
            </td>
        </tr>
    </table>

    <div class="title">Laporan Rincian Faktur</div>

    <table class="meta-table">
        <tr>
            <td class="meta-td">
                <div class="meta-label">Pelanggan:</div>
                <div style="margin-bottom: 3px;"><strong>{{ $customer->name }}</strong></div>
            </td>
            <td class="meta-td" style="text-align: right;">
                <div class="meta-label" style="text-align: right;">Rangkuman Dokumen:</div>
                <div style="margin-bottom: 3px;">Tanggal Cetak: {{ \Carbon\Carbon::now('Asia/Jakarta')->format('d-m-Y H:i') }} WIB</div>
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 20%;">No. Faktur</th>
                <th class="text-center" style="width: 15%;">Tanggal</th>
                <th style="width: 25%;">Nama Barang</th>
                <th class="text-center" style="width: 10%;">Qty</th>
                <th class="text-right" style="width: 10%;">Harga</th>
                <th class="text-right" style="width: 15%;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; $totalSemua = 0; @endphp
            @foreach($invoices as $invoice)
                @foreach($invoice->items as $item)
                @php $totalSemua += $item->subtotal; @endphp
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>
                        @if($invoice->faktur_number)
                            {{ $invoice->faktur_number }}
                            <br><span style="font-size: 8px; color: #666;">PO: {{ $invoice->invoice_number }}</span>
                        @else
                            {{ $invoice->invoice_number }}
                        @endif
                    </td>
                    <td class="text-center">{{ $invoice->invoice_date ? $invoice->invoice_date->format('d-m-Y') : '-' }}</td>
                    <td>{{ $item->item_name }}</td>
                    <td class="text-center">{{ $item->formatted_quantity }}</td>
                    <td class="text-right">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            @endforeach
            @if($no == 1)
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data rincian faktur</td>
                </tr>
            @else
                <tr class="total-row">
                    <td colspan="6" class="text-center">TOTAL KESELURUHAN</td>
                    <td class="text-right" style="color: #dc2626;">Rp {{ number_format($totalSemua, 0, ',', '.') }}</td>
                </tr>
            @endif
        </tbody>
    </table>

</body>
</html>
