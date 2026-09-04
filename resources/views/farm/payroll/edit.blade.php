<x-farm-layout title="Edit Data Gaji" subtitle="Perbarui Data Penggajian Pegawai">
    <div style="max-width:680px;margin:0 auto;">
        <div class="ios-card" style="padding:28px;">
            <form action="{{ route('farm.payroll.update', $payroll) }}" method="POST">
                @csrf
                @method('PUT')

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Nama Pegawai <span style="color:#ff3b30;">*</span></label>
                        <input type="text" name="employee_name" value="{{ old('employee_name', $payroll->employee_name) }}" required class="ios-input">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Jabatan / Role</label>
                        <input type="text" name="role" value="{{ old('role', $payroll->role) }}" class="ios-input">
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Periode Mulai <span style="color:#ff3b30;">*</span></label>
                        <input type="date" name="period_start" value="{{ old('period_start', $payroll->period_start) }}" required class="ios-input">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Periode Selesai <span style="color:#ff3b30;">*</span></label>
                        <input type="date" name="period_end" value="{{ old('period_end', $payroll->period_end) }}" required class="ios-input">
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:20px;">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Gaji Pokok (Rp) <span style="color:#ff3b30;">*</span></label>
                        <input type="number" id="basic_salary" name="basic_salary" value="{{ old('basic_salary', $payroll->basic_salary) }}" required min="0" oninput="calcNet()" class="ios-input">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Tunjangan / Bonus (Rp)</label>
                        <input type="number" id="allowances" name="allowances" value="{{ old('allowances', $payroll->allowances) }}" min="0" oninput="calcNet()" class="ios-input">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Potongan / Kasbon (Rp)</label>
                        <input type="number" id="deductions" name="deductions" value="{{ old('deductions', $payroll->deductions) }}" min="0" oninput="calcNet()" class="ios-input">
                    </div>
                </div>

                <!-- Net Salary Preview Card -->
                <div style="background:#fff7ed;border:1px solid #ffedd5;border-radius:16px;padding:16px 20px;margin-bottom:24px;display:flex;align-items:center;justify-content:space-between;">
                    <div>
                        <div style="font-size:12px;font-weight:700;color:#c2410c;text-transform:uppercase;">Estimasi Gaji Bersih (Net)</div>
                        <div style="font-size:13px;color:#9a3412;">Gaji Pokok + Tunjangan - Potongan</div>
                    </div>
                    <div id="netSalaryDisplay" style="font-size:24px;font-weight:800;color:#c2410c;">Rp 0</div>
                </div>

                <div style="margin-bottom:24px;">
                    <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Catatan / Keterangan</label>
                    <textarea name="notes" rows="3" class="ios-input" style="resize:vertical;">{{ old('notes', $payroll->notes) }}</textarea>
                </div>

                <div style="display:flex;justify-content:flex-end;gap:12px;">
                    <a href="{{ route('farm.payroll.index') }}" class="ios-btn ios-btn-secondary">Batal</a>
                    <button type="submit" class="ios-btn ios-btn-primary">Update Data Gaji</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function calcNet() {
            const basic = parseFloat(document.getElementById('basic_salary').value) || 0;
            const allow = parseFloat(document.getElementById('allowances').value) || 0;
            const ded   = parseFloat(document.getElementById('deductions').value) || 0;
            const net   = basic + allow - ded;
            document.getElementById('netSalaryDisplay').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(net);
        }
        calcNet();
    </script>
</x-farm-layout>
