<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #444; padding-bottom: 10px; }
        .summary-grid { width: 100%; margin-bottom: 30px; border-collapse: collapse; }
        .summary-box { border: 1px solid #eee; padding: 15px; text-align: center; }
        .summary-label { display: block; font-size: 10px; color: #666; text-transform: uppercase; margin-bottom: 5px; }
        .summary-value { display: block; font-size: 16px; font-weight: bold; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th { background: #f8f8f8; border-bottom: 1px solid #ddd; padding: 8px; text-align: left; font-size: 10px; }
        .table td { border-bottom: 1px solid #eee; padding: 8px; font-size: 10px; }
        .text-right { text-align: right; }
        .text-green { color: #10b981; }
        .text-red { color: #ef4444; }
        .font-bold { font-weight: bold; }
        .footer { margin-top: 50px; text-align: right; font-size: 10px; color: #888; }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin:0; font-size: 20px;">LAPORAN KEUANGAN & ARUS KAS</h1>
        <p style="margin:5px 0;">Periode: {{ $filters['start_date'] ?? '-' }} s/d {{ $filters['end_date'] ?? '-' }}</p>
    </div>

    <table class="summary-grid">
        <tr>
            <td class="summary-box">
                <span class="summary-label">Total Pemasukan</span>
                <span class="summary-value text-green">Rp {{ number_format($summary['total_pemasukan'], 0, ',', '.') }}</span>
            </td>
            <td class="summary-box">
                <span class="summary-label">Total Pengeluaran</span>
                <span class="summary-value text-red">Rp {{ number_format($summary['total_pembayaran'], 0, ',', '.') }}</span>
            </td>
            <td class="summary-box">
                <span class="summary-label">Pengeluaran Bersih</span>
                <span class="summary-value">Rp {{ number_format($summary['arus_kas_bersih'], 0, ',', '.') }}</span>
            </td>
        </tr>
    </table>

    <h3 style="font-size: 14px; border-bottom: 1px solid #eee; padding-bottom: 5px; text-transform: uppercase;">Rincian Transaksi</h3>
    <table class="table">
        <thead>
            <tr>
                <th>TANGGAL</th>
                <th>KETERANGAN</th>
                <th>KATEGORI</th>
                <th class="text-right">MASUK (+)</th>
                <th class="text-right">KELUAR (-)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $trx)
                <tr>
                    <td>{{ $trx->date->format('d/m/Y') }}</td>
                    <td>
                        <div class="font-bold">{{ $trx->description }}</div>
                        <div style="font-size: 8px; color: #999;">{{ strtoupper($trx->division) }}</div>
                    </td>
                    <td>{{ strtoupper(str_replace('_', ' ', $trx->category)) }}</td>
                    <td class="text-right text-green">@if($trx->type == 'credit') {{ number_format($trx->amount, 0, ',', '.') }} @endif</td>
                    <td class="text-right text-red">@if($trx->type == 'debit') {{ number_format($trx->amount, 0, ',', '.') }} @endif</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
