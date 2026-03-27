<x-app-layout>
    <x-slot name="header">Edit Data Penggajian</x-slot>

    <div class="max-w-2xl">
        <div class="bg-white rounded-2xl border border-zinc-100 shadow-sm p-8">
            <!-- Read-only Info -->
            <div class="p-4 bg-zinc-50 rounded-xl border border-zinc-100 mb-6">
                <p class="font-bold text-zinc-800">{{ $payroll->employee->name }}</p>
                <p class="text-sm text-zinc-500 mt-1">
                    Periode: {{ $payroll->period_start->format('d M Y') }} – {{ $payroll->period_end->format('d M Y') }}
                </p>
                <p class="text-sm text-zinc-500">Gaji/Hari: Rp {{ number_format($payroll->daily_rate ?: $payroll->employee->salary_base, 0, ',', '.') }}</p>
            </div>

            <form method="POST" action="{{ route('payrolls.update', $payroll) }}" class="space-y-5">
                @csrf @method('PUT')

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-zinc-700 mb-1">Hari Kerja</label>
                        <input type="number" name="working_days" id="working_days" value="{{ old('working_days', $payroll->working_days_count ?: $payroll->working_days) }}" min="0"
                            class="w-full border border-zinc-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-400" onchange="calcPreview()">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-zinc-700 mb-1">Status</label>
                        <select name="status" class="w-full border border-zinc-200 rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none">
                            <option value="belum_lunas" {{ old('status', $payroll->status) == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                            <option value="lunas" {{ old('status', $payroll->status) == 'lunas' ? 'selected' : '' }}>Lunas</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-zinc-700 mb-1">Bonus (Rp)</label>
                        <input type="number" name="bonus" id="bonus" value="{{ old('bonus', $payroll->bonus) }}" min="0"
                            class="w-full border border-zinc-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-400" onchange="calcPreview()">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-zinc-700 mb-1">Lembur (Jam)</label>
                        <input type="number" name="overtime_hours" id="overtime_hours" value="{{ old('overtime_hours', $payroll->overtime_hours) }}" min="0" step="0.5"
                            class="w-full border border-zinc-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-400" onchange="calcPreview()">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-zinc-700 mb-1">Potongan Kasbon (Rp)</label>
                        <input type="number" name="kasbon_deduction" id="deduction" value="{{ old('kasbon_deduction', $payroll->kasbon_deduction) }}" min="0"
                            class="w-full border border-zinc-200 rounded-xl px-4 py-2.5 text-sm text-red-600 focus:outline-none focus:border-red-400" onchange="calcPreview()">
                    </div>
                </div>

                <!-- Total Preview -->
                <div class="p-4 bg-emerald-50 rounded-xl border border-emerald-100">
                    <div class="flex justify-between text-sm">
                        <span class="text-zinc-500">Subtotal Gaji</span>
                        <span id="preview_sub" class="font-semibold text-zinc-700">—</span>
                    </div>
                    <div class="flex justify-between text-sm mt-1">
                        <span class="text-zinc-500">Total Diterima</span>
                        <span id="preview_total" class="font-bold text-emerald-700 text-base">—</span>
                    </div>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors">Perbarui</button>
                    <a href="{{ route('payrolls.index') }}" class="px-6 py-2.5 bg-zinc-100 text-zinc-600 text-sm font-semibold rounded-xl hover:bg-zinc-200 transition-colors">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <script>
    const dailyRate = {{ $payroll->daily_rate ?: $payroll->employee->salary_base }};
    const otRate    = {{ $payroll->overtime_rate ?: $payroll->employee->overtime_rate }};
    function calcPreview() {
        const days    = parseInt(document.getElementById('working_days').value)    || 0;
        const otHours = parseFloat(document.getElementById('overtime_hours').value) || 0;
        const bonus   = parseFloat(document.getElementById('bonus').value)         || 0;
        const ded     = parseFloat(document.getElementById('deduction').value)     || 0;
        
        const sub   = dailyRate * days;
        const otPay = otRate * otHours;
        const total = sub + otPay + bonus - ded;
        
        document.getElementById('preview_sub').textContent   = 'Rp ' + new Intl.NumberFormat('id-ID').format(sub);
        document.getElementById('preview_total').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
    }
    calcPreview();
    </script>
</x-app-layout>
