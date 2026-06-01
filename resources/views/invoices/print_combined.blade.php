<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Jalan & Faktur {{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: "Courier New", Courier, monospace; font-size: 10pt; margin: 0; padding: 0; color: #000; }
        
        @page {
            size: A4 landscape; /* Landscape format */
            margin: 0.5cm; 
        }

        .container { 
            display: flex; 
            width: 100%;
            justify-content: space-between;
        }

        .page-left {
            width: 49%;
            position: relative;
        }

        .page-right {
            width: 49%;
            position: relative;
        }

        /* Top Header */
        .top-header { 
            display: flex; 
            justify-content: space-between; 
            margin-bottom: 20px; 
            margin-top: 10px;
        }

        /* Table Structure */
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 15px; 
        }
        
        th, td { 
            padding: 4px; 
            vertical-align: top; 
        }
        
        /* Column Widths */
        .col-no { width: 30px; text-align: center; }
        .col-code { width: 80px; }
        .col-item { } 
        .col-qty { width: 60px; text-align: center; }
        .col-unit { width: 40px; text-align: center; }
        .col-price { width: 90px; text-align: right; }
        .col-total { width: 100px; text-align: right; }

        tr.item-row { height: 18px; } 

        .total-row td {
            font-weight: bold;
            padding-top: 10px;
        }

        @media print {
            body { -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="container">
        
        <!-- Sisi Kiri: Faktur -->
        <div class="page-left">
            <div class="top-header">
                <div>
                    <div>Kepada Yth.</div>
                    <div><strong>{{ $invoice->customer->name ?? 'N/A' }}</strong></div>
                    <div>{{ $invoice->customer->address ?? '' }}</div>
                </div>
                <div style="text-align: right;">
                    <div>Bandung, {{ $invoice->invoice_date->format('d F Y') }}</div>
                </div>
            </div>

            <div style="display: flex; justify-content: space-between;">
                <div>FAKTUR NO: <strong>{{ $invoice->invoice_number }}</strong></div>
                <div>OP No. ________</div>
            </div>

            <div style="min-height: 8cm; margin-top: 10px;">
                <table>
                    <thead>
                        <tr>
                            <th class="col-no">No</th>
                            <th class="col-code">Kode</th>
                            <th class="col-item">Nama Barang</th>
                            <th class="col-qty">Banyaknya</th>
                            <th class="col-unit">Satuan</th>
                            <th class="col-price">Harga (Rp)</th>
                            <th class="col-total">Jumlah (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $subtotal = 0; @endphp
                        @foreach ($invoice->items as $index => $item)
                        @php $subtotal += ($item->quantity * $item->unit_price); @endphp
                            <tr class="item-row">
                                <td class="col-no">{{ $index + 1 }}</td>
                                <td class="col-code">{{ $item->product_code }}</td>
                                <td class="col-item">{{ $item->item_name }}</td>
                                <td class="col-qty">{{ $item->quantity }}</td>
                                <td class="col-unit">PCS</td>
                                <td class="col-price">{{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                <td class="col-total">{{ number_format($item->quantity * $item->unit_price, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="total-row">
                            <td colspan="6" style="text-align: right;">Total</td>
                            <td class="col-total">{{ number_format($subtotal, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Sisi Kanan: Surat Jalan -->
        <div class="page-right">
            <div class="top-header">
                <div>
                    <div>Kepada Yth.</div>
                    <div><strong>{{ $invoice->customer->name ?? 'N/A' }}</strong></div>
                    <div>{{ $invoice->customer->address ?? '' }}</div>
                </div>
                <div style="text-align: right;">
                    <div>Bandung, {{ $invoice->invoice_date->format('d F Y') }}</div>
                </div>
            </div>

            <div style="display: flex; justify-content: space-between;">
                <div>SURAT JALAN NO: <strong>{{ $invoice->invoice_number }}</strong></div>
                <div>OP No. ________</div>
            </div>

            <div style="min-height: 8cm; margin-top: 10px;">
                <table>
                    <thead>
                        <tr>
                            <th class="col-no">No</th>
                            <th class="col-code">Kode</th>
                            <th class="col-item">Nama Barang</th>
                            <th class="col-qty">Banyaknya</th>
                            <th class="col-unit">Satuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoice->items as $index => $item)
                            <tr class="item-row">
                                <td class="col-no">{{ $index + 1 }}</td>
                                <td class="col-code">{{ $item->product_code }}</td>
                                <td class="col-item">{{ $item->item_name }}</td>
                                <td class="col-qty">{{ $item->quantity }}</td>
                                <td class="col-unit">PCS</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</body>
</html>
