<x-farm.layout title="Buat Slip Gaji Karyawan" subtitle="Pilih karyawan dan sesuaikan tarif borongan, harian, atau bulanan">

    <div style="max-width: 800px; margin: 0 auto;">
        <form method="POST" action="{{ route('farm.payroll.store') }}" x-data="payrollForm()">
            @csrf

            <div class="ios-card" style="padding: 24px; margin-bottom: 20px;">
                <h3 style="font-size: 16px; font-weight: 800; color: #09090b; margin: 0 0 16px;">Karyawan & Periode Gaji</h3>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Pilih Karyawan <span style="color: #dc2626;">*</span></label>
                        <select name="farm_employee_id" class="ios-input" @change="onEmployeeSelect($event.target.value)" required>
                            <option value="">-- Pilih Karyawan --</option>
                            @foreach($employees as $e)
                            <option value="{{ $e->id }}" data-role="{{ $e->role }}" data-type="{{ $e->salary_type }}" data-daily="{{ $e->daily_rate }}" data-pkg="{{ $e->piece_rate_per_kg }}" data-pekor="{{ $e->piece_rate_per_ekor }}" data-monthly="{{ $e->monthly_salary }}">
                                {{ $e->name }} ({{ $e->role ?? 'Staf' }} - {{ ucfirst($e->salary_type) }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Skema Penggajian <span style="color: #dc2626;">*</span></label>
                        <select name="salary_type" class="ios-input" x-model="salaryType" @change="calculateBasicSalary()" required>
                            <option value="borongan">Borongan (per Kg / Ekor Potong)</option>
                            <option value="harian">Harian (Tarif per Hari)</option>
                            <option value="bulanan">Bulanan Tetap</option>
                        </select>
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Awal Periode <span style="color: #dc2626;">*</span></label>
                        <input type="date" name="period_start" value="{{ date('Y-m-01') }}" class="ios-input" required>
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Akhir Periode <span style="color: #dc2626;">*</span></label>
                        <input type="date" name="period_end" value="{{ date('Y-m-t') }}" class="ios-input" required>
                    </div>
                </div>
            </div>

            <!-- Kalkulasi Berdasarkan Skema -->
            <div class="ios-card" style="padding: 24px; margin-bottom: 20px;">
                <h3 style="font-size: 16px; font-weight: 800; color: #09090b; margin: 0 0 16px;">Kalkulasi & Komponen Gaji</h3>

                <!-- Tampilan jika Borongan -->
                <div x-show="salaryType === 'borongan'" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Total Kg Potong / Parting</label>
                        <input type="number" name="total_kg_produced" x-model.number="totalKg" step="any" min="0" class="ios-input" placeholder="0.0" @input="calculateBasicSalary()">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Tarif per Kg (Rp)</label>
                        <input type="number" x-model.number="ratePerKg" step="any" min="0" class="ios-input" placeholder="0" @input="calculateBasicSalary()">
                    </div>
                </div>

                <!-- Tampilan jika Harian -->
                <div x-show="salaryType === 'harian'" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Jumlah Hari Kerja (Hari)</label>
                        <input type="number" name="work_days" x-model.number="workDays" min="0" class="ios-input" placeholder="0" @input="calculateBasicSalary()">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Tarif per Hari (Rp)</label>
                        <input type="number" x-model.number="dailyRate" step="any" min="0" class="ios-input" placeholder="0" @input="calculateBasicSalary()">
                    </div>
                </div>

                <!-- Komponen Finansial -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Gaji Pokok / Hasil Borongan (Rp) <span style="color: #dc2626;">*</span></label>
                        <input type="number" name="basic_salary" x-model.number="basicSalary" step="any" min="0" class="ios-input" required>
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Uang Lembur (Rp)</label>
                        <input type="number" name="overtime_pay" x-model.number="overtimePay" step="any" min="0" class="ios-input">
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Tunjangan / Bonus (Rp)</label>
                        <input type="number" name="allowances" x-model.number="allowances" step="any" min="0" class="ios-input">
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #dc2626; margin-bottom: 6px;">Potongan / Kasbon (Rp)</label>
                        <input type="number" name="deductions" x-model.number="deductions" step="any" min="0" class="ios-input">
                    </div>
                </div>

                <!-- Total Take Home Pay -->
                <div style="margin-top: 20px; padding: 18px 20px; background: #f4f4f6; border-radius: 16px; display: flex; justify-content: space-between; align-items: center;">
                    <div style="font-size: 13px; font-weight: 700; color: #71717a;">TOTAL GAJI BERSIH (TAKE HOME PAY):</div>
                    <div style="font-size: 22px; font-weight: 800; color: #09090b;">
                        <span x-text="formatRupiah(netSalary)"></span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div style="display: flex; justify-content: flex-end; gap: 12px;">
                <a href="{{ route('farm.payroll.index') }}" class="ios-btn ios-btn-secondary">Batal</a>
                <button type="submit" class="ios-btn ios-btn-primary" style="padding: 12px 28px;">Simpan Slip Gaji</button>
            </div>
        </form>
    </div>

    <script>
        function payrollForm() {
            return {
                salaryType: 'borongan',
                totalKg: 0,
                ratePerKg: 0,
                workDays: 0,
                dailyRate: 0,
                monthlySalary: 0,
                basicSalary: 0,
                overtimePay: 0,
                allowances: 0,
                deductions: 0,

                onEmployeeSelect(employeeId) {
                    const selectEl = event.target;
                    const opt = selectEl.options[selectEl.selectedIndex];
                    if (opt) {
                        this.salaryType = opt.dataset.type || 'borongan';
                        this.dailyRate = parseFloat(opt.dataset.daily) || 0;
                        this.ratePerKg = parseFloat(opt.dataset.pkg) || 0;
                        this.monthlySalary = parseFloat(opt.dataset.monthly) || 0;
                        this.calculateBasicSalary();
                    }
                },

                calculateBasicSalary() {
                    if (this.salaryType === 'borongan') {
                        this.basicSalary = (parseFloat(this.totalKg) || 0) * (parseFloat(this.ratePerKg) || 0);
                    } else if (this.salaryType === 'harian') {
                        this.basicSalary = (parseInt(this.workDays) || 0) * (parseFloat(this.dailyRate) || 0);
                    } else {
                        this.basicSalary = parseFloat(this.monthlySalary) || 0;
                    }
                },

                get netSalary() {
                    const totalGross = (parseFloat(this.basicSalary) || 0) + (parseFloat(this.overtimePay) || 0) + (parseFloat(this.allowances) || 0);
                    return Math.max(0, totalGross - (parseFloat(this.deductions) || 0));
                },

                formatRupiah(num) {
                    return 'Rp ' + (num || 0).toLocaleString('id-ID');
                }
            }
        }
    </script>

</x-farm.layout>
