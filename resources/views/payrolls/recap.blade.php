<x-app-layout>
    <x-slot name="header">Rekap Gaji Mingguan</x-slot>

    <div class="space-y-6">
        <!-- Period Selector -->
        <div class="bg-white rounded-2xl border border-zinc-100 shadow-sm p-6">
            <form method="GET" action="{{ route('payrolls.recap') }}" class="flex flex-col sm:flex-row gap-4 items-end">
                <div>
                    <label class="block text-xs font-bold text-zinc-500 uppercase tracking-wider mb-1">Periode Mulai</label>
                    <input type="date" name="period_start" value="{{ $periodStart }}" class="border border-zinc-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-400">
                </div>
                <div>
                    <label class="block text-xs font-bold text-zinc-500 uppercase tracking-wider mb-1">Periode Selesai</label>
                    <input type="date" name="period_end" value="{{ $periodEnd }}" class="border border-zinc-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-400">
                </div>
                <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors">
                    Hitung Otomatis
            </form>

        </div>

        <!-- Payroll Table -->
        <div class="bg-white rounded-2xl border border-zinc-100 shadow-sm overflow-hidden">
            <form action="{{ route('payrolls.storeRecap') }}" method="POST">
                @csrf
                <input type="hidden" name="period_start" value="{{ $periodStart }}">
                <input type="hidden" name="period_end"   value="{{ $periodEnd }}">

                <div class="p-5 border-b border-zinc-100 flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-zinc-800">Rekap Gaji Karyawan</h3>
                        <p class="text-xs text-zinc-400 mt-0.5">{{ \Carbon\Carbon::parse($periodStart)->format('d M Y') }} – {{ \Carbon\Carbon::parse($periodEnd)->format('d M Y') }}</p>
                    </div>
                    <button type="submit" onclick="return confirm('Proses pembayaran mingguan ini? Status akan menjadi Belum Lunas.')"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 text-white text-sm font-bold rounded-xl hover:bg-emerald-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" /></svg>
                        Proses Pembayaran Mingguan
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-zinc-50 border-b border-zinc-100">
                            <tr>
                                <th class="text-left px-5 py-3 text-xs font-semibold text-zinc-500 uppercase">Karyawan</th>
                                <th class="text-right px-5 py-3 text-xs font-semibold text-zinc-500 uppercase">Gaji/Hari</th>
                                <th class="text-center px-5 py-3 text-xs font-semibold text-zinc-500 uppercase">Hari Masuk</th>
                                <th class="text-right px-5 py-3 text-xs font-semibold text-zinc-500 uppercase">Subtotal</th>
                                <th class="text-center px-5 py-3 text-xs font-semibold text-zinc-500 uppercase">Lembur (Jam)</th>
                                <th class="text-right px-5 py-3 text-xs font-semibold text-zinc-500 uppercase">Bonus</th>
                                <th class="text-right px-5 py-3 text-xs font-semibold text-zinc-500 uppercase">Pot. Kasbon</th>
                                <th class="text-right px-5 py-3 text-xs font-semibold text-zinc-500 uppercase">Total Diterima</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100" id="recap_body">
                            @foreach($employees as $index => $emp)
                            <tr class="hover:bg-zinc-50">
                                <input type="hidden" name="payrolls[{{ $index }}][employee_id]" value="{{ $emp->id }}">
                                <td class="px-5 py-3">
                                    <p class="font-bold text-zinc-800">{{ $emp->name }}</p>
                                    <p class="text-xs text-zinc-400">{{ ucfirst($emp->division) }}</p>
                                </td>
                                <td class="px-5 py-3 text-right text-zinc-600">
                                    Rp {{ number_format($emp->daily_rate, 0, ',', '.') }}
                                    <input type="hidden" id="daily_{{ $index }}" value="{{ $emp->daily_rate }}">
                                </td>
                                <td class="px-5 py-3 text-center">
                                    @if($emp->working_days_count > 0)
                                        <span class="inline-block px-2 py-1 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-lg mr-1">{{ $emp->working_days_count }} hari</span>
                                    @else
                                        <span class="inline-block px-2 py-1 bg-zinc-50 text-zinc-400 text-xs rounded-lg mr-1">0 hari</span>
                                    @endif
                                    <input type="number" name="payrolls[{{ $index }}][working_days]" id="days_{{ $index }}" class="w-14 border border-zinc-200 rounded-lg text-center text-sm py-1" value="{{ $emp->working_days_count }}" min="0" onchange="calcRow({{ $index }})">
                                </td>
                                <td class="px-5 py-3 text-right text-zinc-600 font-mono">
                                    <span id="subtotal_{{ $index }}">{{ number_format($emp->subtotal_salary, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-5 py-3 text-center">
                                    <input type="number" name="payrolls[{{ $index }}][overtime_hours]" id="ot_hours_{{ $index }}" class="w-16 border border-zinc-200 rounded-lg text-center text-sm py-1 focus:border-indigo-400" value="0" min="0" step="0.5" onchange="calcRow({{ $index }})">
                                    <input type="hidden" id="ot_rate_{{ $index }}" value="{{ $emp->overtime_rate }}">
                                    <div class="text-[10px] text-zinc-400 mt-1">@ Rp {{ number_format($emp->overtime_rate, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <input type="number" name="payrolls[{{ $index }}][bonus]" id="bonus_{{ $index }}" class="w-24 border border-zinc-200 rounded-lg text-right text-sm py-1 focus:border-indigo-400" value="0" min="0" onchange="calcRow({{ $index }})">
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <input type="number" name="payrolls[{{ $index }}][deduction]" id="ded_{{ $index }}" class="w-24 border border-zinc-200 rounded-lg text-right text-sm py-1 text-red-600 focus:border-red-400" value="{{ $emp->recommended_kasbon_deduction }}" min="0" onchange="calcRow({{ $index }})">
                                    @if($emp->current_kasbon > 0)
                                        <div class="text-xs text-red-400 mt-0.5">Kasbon: Rp {{ number_format($emp->current_kasbon, 0, ',', '.') }}</div>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-right font-bold text-zinc-800">
                                    <span id="total_{{ $index }}">{{ number_format($emp->subtotal_salary + 0 - $emp->recommended_kasbon_deduction, 0, ',', '.') }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-zinc-50 border-t border-zinc-200">
                            <tr>
                                <td colspan="6" class="px-5 py-3 text-right font-bold text-zinc-700">Grand Total:</td>
                                <td class="px-5 py-3 text-right font-bold text-indigo-700 text-base" id="grand_total">
                                    Rp {{ number_format($employees->sum('subtotal_salary'), 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </form>
        </div>
    </div>

    <script>
    @foreach($employees as $index => $emp)
        calcRow({{ $index }});
    @endforeach

    function calcRow(i) {
        const daily   = parseFloat(document.getElementById(`daily_${i}`).value) || 0;
        const days    = parseInt(document.getElementById(`days_${i}`).value)    || 0;
        const otHours = parseFloat(document.getElementById(`ot_hours_${i}`).value) || 0;
        const otRate  = parseFloat(document.getElementById(`ot_rate_${i}`).value)  || 0;
        const bonus   = parseFloat(document.getElementById(`bonus_${i}`).value)  || 0;
        const ded     = parseFloat(document.getElementById(`ded_${i}`).value)    || 0;
        
        const sub     = daily * days;
        const otPay   = otHours * otRate;
        const total   = sub + otPay + bonus - ded;
        
        document.getElementById(`subtotal_${i}`).textContent = formatRp(sub);
        document.getElementById(`total_${i}`).textContent    = formatRp(total);
        calcGrand();
    }

    function calcGrand() {
        let grand = 0;
        document.querySelectorAll('[id^=total_]').forEach(el => {
            grand += parseFloat(el.textContent.replace(/\./g,'').replace(',','.')) || 0;
        });
        document.getElementById('grand_total').textContent = 'Rp ' + formatRp(grand);
    }

    function formatRp(n) {
        return new Intl.NumberFormat('id-ID').format(Math.round(n));
    }
    </script>
</x-app-layout>
