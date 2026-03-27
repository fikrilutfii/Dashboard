<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji - {{ $payroll->employee->name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; font-size: 11px; color: #1a1a1a; padding: 20px; background: #f4f4f5; }
        .slip-card { background: white; width: 100%; max-width: 400px; margin: 0 auto; padding: 25px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px dashed #e2e8f0; padding-bottom: 15px; }
        .header h1 { font-size: 16px; font-weight: 800; color: #1e3a5f; letter-spacing: 1px; }
        .header p { color: #64748b; font-size: 10px; margin-top: 2px; }
        
        .info-grid { display: grid; grid-template-cols: 1fr 1fr; gap: 10px; margin-bottom: 20px; }
        .info-label { color: #64748b; font-size: 9px; text-transform: uppercase; font-weight: 600; }
        .info-value { color: #1e293b; font-weight: 700; font-size: 11px; margin-top: 2px; }
        
        .section-title { font-size: 10px; font-weight: 800; color: #1e3a5f; margin-bottom: 10px; border-left: 3px solid #6366f1; padding-left: 8px; text-transform: uppercase; }
        .item-row { display: flex; justify-content: space-between; margin-bottom: 6px; }
        .item-label { color: #475569; }
        .item-value { color: #1e293b; font-weight: 600; }
        
        .divider { height: 1px; background: #e2e8f0; margin: 15px 0; }
        
        .total-section { background: #f8fafc; padding: 12px; border-radius: 8px; margin-top: 15px; border: 1px solid #e2e8f0; }
        .total-row { display: flex; justify-content: space-between; align-items: center; }
        .total-label { font-size: 12px; font-weight: 700; color: #1e293b; }
        .total-amount { font-size: 16px; font-weight: 800; color: #6366f1; }
        
        .footer { text-align: center; margin-top: 20px; color: #94a3b8; font-size: 9px; font-style: italic; }
        
        .btn-print { display: block; width: 100%; max-width: 400px; margin: 0 auto 15px auto; padding: 10px; background: #1e293b; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; text-align: center; text-decoration: none; }
        
        @media print {
            body { background: white; padding: 0; }
            .slip-card { box-shadow: none; border: 1px solid #e2e8f0; max-width: 100%; border-radius: 0; }
            .btn-print { display: none; }
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
    <button onclick="window.print()" class="btn-print">🖨️ Cetak Slip Gaji</button>
    <a href="{{ route('payrolls.index') }}" class="btn-print" style="background: #e2e8f0; color: #475569; margin-top: -10px;">← Kembali</a>

    <div class="slip-card">
        <div class="header">
            <h1>SLIP GAJI KARYAWAN</h1>
            <p>CV. ABADI SENTOSA - {{ ucfirst($payroll->employee->division) }}</p>
        </div>

        <div class="info-grid">
            <div>
                <p class="info-label">Nama Karyawan</p>
                <p class="info-value">{{ $payroll->employee->name }}</p>
            </div>
            <div style="text-align: right;">
                <p class="info-label">Periode</p>
                <p class="info-value">{{ \Carbon\Carbon::parse($payroll->period_end)->format('d F Y') }}</p>
            </div>
        </div>

        <div class="section-title">Penghasilan</div>
        <div class="item-row">
            <span class="item-label">Gaji Pokok ({{ $payroll->working_days_count }} hari)</span>
            <span class="item-value">Rp {{ number_format($payroll->basic_salary, 0, ',', '.') }}</span>
        </div>
        @if($payroll->overtime_pay > 0)
        <div class="item-row">
            <span class="item-label">Lembur ({{ number_format($payroll->overtime_hours, 1) }} jam)</span>
            <span class="item-value">Rp {{ number_format($payroll->overtime_pay, 0, ',', '.') }}</span>
        </div>
        @endif
        @if($payroll->bonus > 0)
        <div class="item-row">
            <span class="item-label">Bonus / Insentif</span>
            <span class="item-value">Rp {{ number_format($payroll->bonus, 0, ',', '.') }}</span>
        </div>
        @endif

        <div class="divider"></div>

        <div class="section-title">Potongan</div>
        @if($payroll->kasbon_deduction > 0)
        <div class="item-row">
            <span class="item-label">Potongan Kasbon</span>
            <span class="item-value" style="color: #dc2626;">- Rp {{ number_format($payroll->kasbon_deduction, 0, ',', '.') }}</span>
        </div>
        @else
        <p style="color: #94a3b8; font-size: 9px; text-align: center;">Tidak ada potongan</p>
        @endif

        <div class="total-section">
            <div class="total-row">
                <span class="total-label">Total Diterima</span>
                <span class="total-amount">Rp {{ number_format($payroll->total_salary, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="footer">
            <p>Terima kasih atas dedikasi dan kerja keras Anda.</p>
            <p style="margin-top: 5px; font-weight: 600;">CV. Abadi Sentosa</p>
        </div>
    </div>
</body>
</html>
