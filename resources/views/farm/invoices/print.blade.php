<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faktur {{ $invoice->invoice_number }} — Peternakan Ayam Abadi Sentosa</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 13px; color: #1c1c1e; line-height: 1.5; margin: 0; padding: 20px; background: #fff; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, 0.05); border-radius: 8px; }
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .header-table td { vertical-align: top; }
        .brand { font-size: 22px; font-weight: 800; color: #d97706; }
        .sub-brand { font-size: 12px; color: #666; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; }
        .inv-title { font-size: 20px; font-weight: 800; text-align: right; color: #1c1c1e; }
        .info-box { background: #fafafa; border: 1px solid #f0f0f0; border-radius: 6px; padding: 12px 16px; margin-bottom: 20px; display: flex; justify-content: space-between; }
        .info-col { font-size: 12px; }
        .info-col strong { color: #1c1c1e; font-size: 13px; display: block; margin-bottom: 2px; }
        .item-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .item-table th { background: #f59e0b; color: white; padding: 10px 12px; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; text-align: left; }
        .item-table td { padding: 10px 12px; border-bottom: 1px solid #eee; }
        .item-table tr:nth-child(even) td { background: #fafafa; }
        .totals-table { width: 320px; margin-left: auto; border-collapse: collapse; margin-bottom: 30px; }
        .totals-table td { padding: 6px 12px; font-size: 13px; }
        .totals-table tr.grand-total td { font-weight: 800; font-size: 16px; color: #d97706; border-top: 2px solid #d97706; padding-top: 10px; }
        .signatures { display: flex; justify-content: space-between; margin-top: 40px; text-align: center; }
        .sig-box { width: 200px; }
        .sig-line { margin-top: 60px; border-top: 1px dashed #aaa; font-weight: 600; padding-top: 4px; font-size: 12px; }
        .no-print { margin-bottom: 20px; text-align: right; }
        .btn-print { background: #f59e0b; color: white; border: none; padding: 10px 20px; font-weight: 700; border-radius: 6px; cursor: pointer; }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
            .invoice-box { border: none; box-shadow: none; padding: 0; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" class="btn-print">🖨️ Cetak / Download PDF</button>
    </div>

    <div class="invoice-box">
        <!-- Header -->
        <table class="header-table">
            <tr>
                <td>
                    <div class="brand">PETERNAKAN AYAM</div>
                    <div class="sub-brand">Abadi Sentosa — Division Farm</div>
                    <div style="font-size:12px;color:#555;margin-top:4px;">
                        Jl. Raya Peternakan No. 88, Bogor<br>
                        Telp/WA: 0812-3456-7890
                    </div>
                </td>
                <td style="text-align:right;">
                    <div class="inv-title">FAKTUR PENJUALAN</div>
                    <div style="font-weight:700;color:#d97706;font-size:14px;margin-top:4px;">{{ $invoice->invoice_number }}</div>
                    <div style="font-size:12px;color:#666;">Tanggal: {{ \Carbon\Carbon::parse($invoice->invoice_date)->translatedFormat('d F Y') }}</div>
                    @if($invoice->due_date)
                    <div style="font-size:12px;color:#666;">Jatuh Tempo: {{ \Carbon\Carbon::parse($invoice->due_date)->translatedFormat('d F Y') }}</div>
                    @endif
                </td>
            </tr>
        </table>

        <!-- Customer & Coop Info -->
        <div class="info-box">
            <div class="info-col">
                <span style="color:#888;font-size:10px;text-transform:uppercase;">DITUJUKAN KEPADA:</span>
                <strong>{{ $invoice->customer->name ?? 'Pelanggan' }}</strong>
                @if($invoice->customer->phone)Telp: {{ $invoice->customer->phone }}<br>@endif
                @if($invoice->customer->address){{ $invoice->customer->address }}@endif
            </div>
            <div class="info-col" style="text-align:right;">
                <span style="color:#888;font-size:10px;text-transform:uppercase;">ASAL KANDANG:</span>
                <strong>{{ $invoice->coop->name ?? 'Kandang Utama' }}</strong>
                Metode Pembayaran: {{ strtoupper($invoice->payment_method ?? 'TRANSFER') }}
            </div>
        </div>

        <!-- Item Table -->
        <table class="item-table">
            <thead>
                <tr>
                    <th style="width:40px;">No</th>
                    <th>Deskripsi Barang / Panen</th>
                    <th style="text-align:center;">Qty</th>
                    <th>Satuan</th>
                    <th style="text-align:right;">Harga Satuan</th>
                    <th style="text-align:right;">Total (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $idx => $item)
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td style="font-weight:600;">{{ $item->description }}</td>
                    <td style="text-align:center;">{{ number_format($item->qty, 2) }}</td>
                    <td>{{ $item->unit }}</td>
                    <td style="text-align:right;">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                    <td style="text-align:right;font-weight:600;">Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <table class="totals-table">
            <tr>
                <td>Subtotal</td>
                <td style="text-align:right;font-weight:600;">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Telah Dibayar</td>
                <td style="text-align:right;color:#16a34a;font-weight:600;">Rp {{ number_format($invoice->paid_amount, 0, ',', '.') }}</td>
            </tr>
            <tr class="grand-total">
                <td>Sisa Piutang</td>
                <td style="text-align:right;">Rp {{ number_format($invoice->total_amount - $invoice->paid_amount, 0, ',', '.') }}</td>
            </tr>
        </table>

        @if($invoice->notes)
        <div style="font-size:11px;color:#666;border-top:1px solid #eee;padding-top:8px;">
            <strong>Catatan:</strong> {{ $invoice->notes }}
        </div>
        @endif

        <!-- Signatures -->
        <div class="signatures">
            <div class="sig-box">
                <div>Penerima / Pembeli</div>
                <div class="sig-line">( {{ $invoice->customer->name ?? '........................' }} )</div>
            </div>
            <div class="sig-box">
                <div>Hormat Kami,</div>
                <div class="sig-line">( Peternakan Ayam )</div>
            </div>
        </div>
    </div>

</body>
</html>
