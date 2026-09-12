<x-farm.layout title="Slip Gaji Karyawan" subtitle="Rincian pembayaran gaji karyawan RPA">

    <x-slot:headerActions>
        <button onclick="window.print()" class="ios-btn ios-btn-primary">
            Cetak Slip Gaji
        </button>
        <a href="{{ route('farm.payroll.index') }}" class="ios-btn ios-btn-secondary">Kembali</a>
    </x-slot:headerActions>

    <div style="max-width: 680px; margin: 0 auto; background: white; border-radius: 20px; padding: 36px; border: 1px solid rgba(0,0,0,0.08); box-shadow: 0 10px 30px rgba(0,0,0,0.03);">
        <!-- Kop Slip -->
        <div style="border-bottom: 2px solid #09090b; padding-bottom: 16px; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <h2 style="font-size: 20px; font-weight: 800; color: #09090b; margin: 0;">ASFOUR BROILER</h2>
                <div style="font-size: 12px; color: #71717a; margin-top: 2px;">Divisi Pemotongan Ayam (RPA)</div>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 16px; font-weight: 800; color: #09090b;">SLIP GAJI</div>
                <div style="font-size: 12px; color: #71717a; margin-top: 2px;">Periode: {{ $payroll->period_start->format('d/m/Y') }} - {{ $payroll->period_end->format('d/m/Y') }}</div>
            </div>
        </div>

        <!-- Employee Info -->
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 24px; font-size: 13px;">
            <div>Nama Karyawan: <strong style="color:#09090b;">{{ $payroll->employee_name }}</strong></div>
            <div>Jabatan / Bagian: <strong style="color:#09090b;">{{ $payroll->role ?? '-' }}</strong></div>
            <div>Skema Gaji: <strong style="color:#09090b; text-transform: uppercase;">{{ $payroll->salary_type }}</strong></div>
            <div>Status Pembayaran: <strong style="color: {{ $payroll->status === 'dibayar' ? '#10b981' : '#dc2626' }}; text-transform: uppercase;">{{ $payroll->status }}</strong></div>
        </div>

        <!-- Earnings & Deductions Table -->
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 24px; font-size: 13.5px;">
            <thead>
                <tr style="border-bottom: 1px solid #e4e4e7; background: #f8fafc;">
                    <th style="text-align: left; padding: 10px 12px; font-weight: 700;">Keterangan Penghasilan</th>
                    <th style="text-align: right; padding: 10px 12px; font-weight: 700;">Jumlah (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <tr style="border-bottom: 1px solid #f4f4f5;">
                    <td style="padding: 10px 12px;">
                        Gaji Pokok / Hasil Kerja
                        @if($payroll->salary_type === 'borongan')
                            <span style="color:#71717a; font-size:12px;">({{ number_format($payroll->total_kg_produced, 1) }} Kg)</span>
                        @elseif($payroll->salary_type === 'harian')
                            <span style="color:#71717a; font-size:12px;">({{ $payroll->work_days }} Hari Kerja)</span>
                        @endif
                    </td>
                    <td style="text-align: right; padding: 10px 12px; font-weight: 600;">Rp {{ number_format($payroll->basic_salary, 0, ',', '.') }}</td>
                </tr>
                @if($payroll->overtime_pay > 0)
                <tr style="border-bottom: 1px solid #f4f4f5;">
                    <td style="padding: 10px 12px;">Uang Lembur</td>
                    <td style="text-align: right; padding: 10px 12px; font-weight: 600;">Rp {{ number_format($payroll->overtime_pay, 0, ',', '.') }}</td>
                </tr>
                @endif
                @if($payroll->allowances > 0)
                <tr style="border-bottom: 1px solid #f4f4f5;">
                    <td style="padding: 10px 12px;">Tunjangan & Bonus</td>
                    <td style="text-align: right; padding: 10px 12px; font-weight: 600;">Rp {{ number_format($payroll->allowances, 0, ',', '.') }}</td>
                </tr>
                @endif
                @if($payroll->deductions > 0)
                <tr style="border-bottom: 1px solid #f4f4f5; color: #dc2626;">
                    <td style="padding: 10px 12px;">Potongan / Kasbon</td>
                    <td style="text-align: right; padding: 10px 12px; font-weight: 600;">- Rp {{ number_format($payroll->deductions, 0, ',', '.') }}</td>
                </tr>
                @endif
            </tbody>
            <tfoot>
                <tr style="border-top: 2px solid #09090b; font-weight: 800; font-size: 15px;">
                    <td style="padding: 12px;">GAJI BERSIH DITERIMA:</td>
                    <td style="text-align: right; padding: 12px; color: #09090b;">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        <!-- Signature Section -->
        <div style="display: flex; justify-content: space-between; margin-top: 48px; text-align: center; font-size: 12.5px;">
            <div>
                <div>Penerima,</div>
                <div style="margin-top: 54px; font-weight: 700; border-top: 1px solid #71717a; padding-top: 4px; min-width: 140px;">
                    {{ $payroll->employee_name }}
                </div>
            </div>
            <div>
                <div>Pengelola RPA,</div>
                <div style="margin-top: 54px; font-weight: 700; border-top: 1px solid #71717a; padding-top: 4px; min-width: 140px;">
                    Pemotongan Ayam (RPA)
                </div>
            </div>
        </div>
    </div>

</x-farm.layout>
