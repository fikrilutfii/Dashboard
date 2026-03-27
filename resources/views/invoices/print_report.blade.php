<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Faktur Penjualan</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #111; padding: 20px; }
        h1  { font-size: 16px; font-weight: 700; color: #1e3a5f; }
        .subtitle { font-size: 11px; color: #555; margin-top: 3px; }
        .header { border-bottom: 2px solid #1e3a5f; padding-bottom: 12px; margin-bottom: 14px; }
        .meta { display: flex; justify-content: space-between; font-size: 10px; color: #666; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        thead th { background: #1e3a5f; color: #fff; padding: 7px 8px; font-size: 10px; text-align: left; }
        thead th.right { text-align: right; }
        tbody tr:nth-child(even) { background: #f5f8fc; }
        tbody td { padding: 6px 8px; border-bottom: 1px solid #e2e8f0; }
        tbody td.right { text-align: right; }
        tfoot td { padding: 7px 8px; font-weight: 700; border-top: 2px solid #1e3a5f; background: #eef2f7; }
        tfoot td.right { text-align: right; }
        .badge-paid   { display: inline-block; padding: 1px 7px; border-radius: 20px; background: #d1fae5; color: #065f46; font-size: 10px; font-weight: 600; }
        .badge-unpaid { display: inline-block; padding: 1px 7px; border-radius: 20px; background: #fee2e2; color: #991b1b; font-size: 10px; font-weight: 600; }
        .print-time { margin-top: 14px; font-size: 10px; color: #888; text-align: right; }
        @media print {
            body { padding: 10px; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="margin-bottom:14px; display:flex; gap:10px;">
        <button onclick="window.print()" style="padding:7px 18px; background:#1e3a5f; color:#fff; border:none; border-radius:7px; cursor:pointer; font-size:12px;">🖨️ Cetak / Simpan PDF</button>
        <a href="{{ route('invoices.index', $filters) }}" style="padding:7px 18px; background:#eee; color:#333; border-radius:7px; text-decoration:none; font-size:12px;">← Kembali</a>
    </div>

    <div class="header">
        <h1>LAPORAN FAKTUR PENJUALAN</h1>
        <div class="subtitle">
            @if(!empty($filters['status'])) Status: {{ strtoupper($filters['status']) }} &nbsp;|&nbsp; @endif
            @if(!empty($filters['date_filter'])) Tanggal: {{ \Carbon\Carbon::parse($filters['date_filter'])->format('d M Y') }} &nbsp;|&nbsp; @endif
            @if(!empty($filters['search'])) Pencarian: "{{ $filters['search'] }}" &nbsp;|&nbsp; @endif
            Total: {{ $invoices->count() }} faktur
        </div>
    </div>

    <div class="meta">
        <span>Divisi: {{ ucfirst(session('division') ?: 'Semua') }}</span>
        <span>Dicetak: {{ now()->format('d M Y H:i') }}</span>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No. Faktur</th>
                <th>Tanggal</th>
                <th>Customer</th>
                <th>Jumlah Item</th>
                <th class="right">Total (Rp)</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoices as $i => $invoice)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td style="font-family: monospace; font-weight: 600;">{{ $invoice->invoice_number }}</td>
                <td>{{ $invoice->invoice_date->format('d/m/Y') }}</td>
                <td>{{ $invoice->customer->name }}</td>
                <td>{{ $invoice->items->count() }} item</td>
                <td class="right">{{ number_format($invoice->total_amount, 0, ',', '.') }}</td>
                <td>
                    @if($invoice->status == 'lunas')
                        <span class="badge-paid">LUNAS</span>
                    @else
                        <span class="badge-unpaid">BELUM LUNAS</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center; padding:20px; color:#aaa;">Tidak ada data faktur.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5">TOTAL</td>
                <td class="right">{{ number_format($invoices->sum('total_amount'), 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="print-time">Laporan dibuat pada: {{ now()->format('d M Y H:i:s') }}</div>
</body>
</html>
