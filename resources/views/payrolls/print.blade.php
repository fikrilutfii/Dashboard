<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penggajian Mingguan</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; font-size: 12px; color: #1a1a1a; padding: 30px; }
        .header { text-align: center; margin-bottom: 24px; border-bottom: 2px solid #1e3a5f; padding-bottom: 16px; }
        .header h1 { font-size: 18px; font-weight: 700; color: #1e3a5f; }
        .header p  { font-size: 12px; color: #555; margin-top: 4px; }
        .meta { display: flex; justify-content: space-between; margin-bottom: 16px; font-size: 11px; color: #444; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        thead th { background: #1e3a5f; color: white; padding: 8px 10px; text-align: left; font-size: 11px; }
        thead th.right { text-align: right; }
        thead th.center { text-align: center; }
        tbody tr:nth-child(even) { background: #f4f7fb; }
        tbody td { padding: 7px 10px; border-bottom: 1px solid #e2e8f0; vertical-align: top; }
        tbody td.right { text-align: right; font-variant-numeric: tabular-nums; }
        tbody td.center { text-align: center; }
        tfoot td { padding: 9px 10px; font-weight: 700; border-top: 2px solid #1e3a5f; background: #eef2f7; }
        tfoot td.right { text-align: right; }
        .badge-lunas { display: inline-block; padding: 2px 8px; border-radius: 20px; background: #d1fae5; color: #065f46; font-weight: 600; font-size: 10px; }
        .badge-belum { display: inline-block; padding: 2px 8px; border-radius: 20px; background: #fee2e2; color: #991b1b; font-weight: 600; font-size: 10px; }
        @media print {
            body { padding: 15px; }
            .no-print { display: none !important; }
            .page-break { page-break-before: always; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom:16px; display:flex; gap:10px;">
        <button onclick="window.print()" style="padding:8px 20px; background:#1e3a5f; color:white; border:none; border-radius:8px; cursor:pointer; font-size:13px;">🖨️ Cetak / Simpan PDF</button>
        <a href="{{ route('payrolls.index') }}" style="padding:8px 20px; background:#eee; color:#333; border-radius:8px; text-decoration:none; font-size:13px;">← Kembali</a>
    </div>

    <div class="header">
        <h1>LAPORAN PENGGAJIAN MINGGUAN</h1>
        <p>
            @if($periodStart && $periodEnd)
                Periode: {{ \Carbon\Carbon::parse($periodStart)->format('d M Y') }} – {{ \Carbon\Carbon::parse($periodEnd)->format('d M Y') }}
            @else
                Semua Periode
            @endif
        </p>
        <p>Dicetak: {{ now()->format('d M Y H:i') }}</p>
    </div>

    <div class="meta">
        <span>Divisi: {{ ucfirst(session('division') ?: 'Semua') }}</span>
        <span>Total Karyawan: {{ $payrolls->pluck('employee_id')->unique()->count() }}</span>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Karyawan</th>
                <th>Periode</th>
                <th class="center">Hari Kerja</th>
                <th class="right">Gaji/Hari</th>
                <th class="right">Subtotal</th>
                <th class="right">Bonus</th>
                <th class="right">Pot. Kasbon</th>
                <th class="right">Total Diterima</th>
                <th class="center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payrolls as $i => $payroll)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>
                    <strong>{{ $payroll->employee->name }}</strong><br>
                    <span style="color:#888; font-size:10px;">{{ ucfirst($payroll->employee->division) }}</span>
                </td>
                <td style="white-space:nowrap; font-size:10px;">
                    {{ $payroll->period_start->format('d/m/Y') }}<br>– {{ $payroll->period_end->format('d/m/Y') }}
                </td>
                <td class="center">{{ $payroll->working_days_count ?? $payroll->working_days ?? 0 }} hari</td>
                <td class="right">{{ number_format($payroll->daily_rate ?: $payroll->employee->salary_base, 0, ',', '.') }}</td>
                <td class="right">{{ number_format($payroll->basic_salary, 0, ',', '.') }}</td>
                <td class="right" style="color:#059669;">{{ number_format($payroll->bonus, 0, ',', '.') }}</td>
                <td class="right" style="color:#dc2626;">{{ number_format($payroll->kasbon_deduction, 0, ',', '.') }}</td>
                <td class="right"><strong>{{ number_format($payroll->total_salary, 0, ',', '.') }}</strong></td>
                <td class="center">
                    @if($payroll->status === 'lunas')
                        <span class="badge-lunas">Lunas</span>
                    @else
                        <span class="badge-belum">Belum Lunas</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" style="text-align:center; padding:20px; color:#888;">Tidak ada data penggajian.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5">TOTAL</td>
                <td class="right">{{ number_format($payrolls->sum('basic_salary'), 0, ',', '.') }}</td>
                <td class="right">{{ number_format($payrolls->sum('bonus'), 0, ',', '.') }}</td>
                <td class="right">{{ number_format($payrolls->sum('kasbon_deduction'), 0, ',', '.') }}</td>
                <td class="right">{{ number_format($payrolls->sum('total_salary'), 0, ',', '.') }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div style="margin-top: 20px; font-size: 11px; color: #555; text-align: right;">
        Dicetak pada: {{ now()->format('d M Y H:i') }}
    </div>
</body>
</html>
