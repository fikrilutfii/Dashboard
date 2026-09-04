<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Rincian Faktur - {{ $customer->name }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            color: #000;
            background-color: #fff;
            margin: 20px;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px double #000;
            padding-bottom: 10px;
        }
        .company-name {
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .company-details {
            font-size: 10px;
            margin-top: 5px;
        }
        .title {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            text-decoration: underline;
            margin-bottom: 20px;
            text-transform: uppercase;
        }
        .meta-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .meta-group {
            width: 48%;
        }
        .meta-label {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            border-bottom: 1px solid #000;
            margin-bottom: 5px;
            padding-bottom: 2px;
        }
        .meta-value {
            margin-bottom: 3px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-transform: uppercase;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-row {
            font-weight: bold;
            background-color: #f2f2f2;
        }
        
        /* Print directives */
        @media print {
            body {
                margin: 0;
            }
            .no-print {
                display: none;
            }
        }
        
        .no-print-bar {
            background-color: #f4f4f5;
            padding: 10px 20px;
            margin: -20px -20px 20px -20px;
            border-bottom: 1px solid #e4e4e7;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn-print {
            background-color: #18181b;
            color: white;
            border: none;
            padding: 8px 16px;
            cursor: pointer;
            font-family: sans-serif;
            font-weight: bold;
            font-size: 12px;
            border-radius: 4px;
        }
        .btn-print:hover {
            background-color: #27272a;
        }
    </style>
</head>
@php
    $logoPath = public_path('images/logo.jpg');
    $logoData = file_exists($logoPath) ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($logoPath)) : null;
@endphp
<body>

    <div class="no-print-bar no-print">
        <div>
            <span style="font-family: sans-serif; font-weight: bold;">Pratinjau Cetak Laporan</span>
        </div>
        <div>
            <button onclick="window.print()" class="btn-print">Cetak Laporan</button>
            <button onclick="window.close()" class="btn-print" style="background-color: #e4e4e7; color: #27272a; margin-left: 5px;">Tutup</button>
        </div>
    </div>

    <table style="width: 100%; border-collapse: collapse; border-bottom: 3px double #dc2626; padding-bottom: 8px; margin-bottom: 15px;">
        <tr>
            <td style="width: 90px; vertical-align: middle; border: none; padding: 0;">
                @if($logoData)
                    <img src="{{ $logoData }}" style="height: 60px; width: auto;" alt="Logo AS">
                @else
                    <img src="{{ asset('images/logo.jpg') }}" style="height: 60px; width: auto;" alt="Logo AS">
                @endif
            </td>
            <td style="vertical-align: middle; text-align: center; border: none; padding: 0 10px 0 0;">
                <h1 style="color: #dc2626; font-family: Arial, sans-serif; font-size: 26px; font-weight: 900; margin: 0; letter-spacing: 2px; text-transform: uppercase;">
                    ABADI SENTOSA
                </h1>
                <div style="font-family: Arial, sans-serif; font-size: 9.5px; font-weight: bold; color: #1e3a8a; margin: 3px 0; letter-spacing: 0.5px;">
                    KONTRAKTOR <span style="color: #dc2626;">&bull;</span> PERDAGANGAN UMUM <span style="color: #dc2626;">&bull;</span> PERCETAKAN <span style="color: #dc2626;">&bull;</span> JASA
                </div>
                <div style="font-family: Arial, sans-serif; font-size: 9px; color: #374151; line-height: 1.3;">
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
            <td class="meta-td text-right">
                <div class="meta-label text-right">Rangkuman Dokumen:</div>
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
                            <br><span style="font-size: 10px; color: #666;">PO: {{ $invoice->invoice_number }}</span>
                        @else
                            {{ $invoice->invoice_number }}
                        @endif
                    </td>
                    <td class="text-center">{{ $invoice->invoice_date ? $invoice->invoice_date->format('d-m-Y') : '-' }}</td>
                    <td>{{ $item->item_name }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
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
                    <td class="text-right">Rp {{ number_format($totalSemua, 0, ',', '.') }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <script>
        // Auto trigger print dialog when page is loaded
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>
