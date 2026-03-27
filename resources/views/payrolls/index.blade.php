<x-app-layout>
    <x-slot name="header">Penggajian Karyawan</x-slot>

    <div class="space-y-6">
        <!-- Header Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl p-5 border border-zinc-100 shadow-sm">
                <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wider">Total Gaji (Ditampilkan)</p>
                <h3 class="text-xl font-bold text-zinc-800 mt-1">Rp {{ number_format($payrolls->sum('total_salary'), 0, ',', '.') }}</h3>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-zinc-100 shadow-sm">
                <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wider">Sudah Lunas</p>
                <h3 class="text-xl font-bold text-emerald-600 mt-1">Rp {{ number_format($payrolls->filter(fn($p) => $p->status === 'lunas')->sum('total_salary'), 0, ',', '.') }}</h3>
            </div>
            <div class="bg-white rounded-2xl p-5 border border-zinc-100 shadow-sm">
                <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wider">Belum Lunas</p>
                <h3 class="text-xl font-bold text-red-500 mt-1">Rp {{ number_format($payrolls->where('status', 'belum_lunas')->sum('total_salary'), 0, ',', '.') }}</h3>
            </div>
        </div>


        <!-- Table Card -->
        <div class="bg-white rounded-2xl border border-zinc-100 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-zinc-100 flex flex-col sm:flex-row gap-3 justify-between items-start sm:items-center">
                <form method="GET" action="{{ route('payrolls.index') }}" class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari karyawan..." class="text-sm border border-zinc-200 rounded-xl px-4 py-2 focus:outline-none focus:border-indigo-400 w-full sm:w-44">
                    <input type="date" name="date_start" value="{{ request('date_start') }}" class="text-sm border border-zinc-200 rounded-xl px-3 py-2 focus:outline-none focus:border-indigo-400">
                    <input type="date" name="date_end"   value="{{ request('date_end') }}"   class="text-sm border border-zinc-200 rounded-xl px-3 py-2 focus:outline-none focus:border-indigo-400">
                    <button type="submit" class="px-4 py-2 bg-zinc-800 text-white text-sm font-semibold rounded-xl hover:bg-zinc-700">Filter</button>
                </form>
                <div class="flex gap-2 flex-shrink-0 flex-wrap">
                    <a href="{{ route('payrolls.print') . (request()->has('date_start') ? '?' . request()->getQueryString() : '') }}" target="_blank"
                        class="px-4 py-2 bg-zinc-100 text-zinc-700 text-sm font-semibold rounded-xl hover:bg-zinc-200 transition-colors whitespace-nowrap border border-zinc-200">
                        🖨️ Cetak Laporan
                    </a>
                    <a href="{{ route('payrolls.recap') }}" class="px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-xl hover:bg-emerald-700 transition-colors whitespace-nowrap">Rekap Mingguan</a>
                </div>
            </div>

            @if(session('success'))
                <div class="mx-5 mt-4 p-3 bg-emerald-50 text-emerald-700 rounded-xl text-sm border border-emerald-100">{{ session('success') }}</div>
            @endif

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-zinc-50 border-b border-zinc-100">
                        <tr>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-zinc-500 uppercase">Periode</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-zinc-500 uppercase">Karyawan</th>
                            <th class="text-center px-4 py-3 text-xs font-semibold text-zinc-500 uppercase">Hari</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold text-zinc-500 uppercase">Gaji/Hari</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold text-zinc-500 uppercase">Bonus</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold text-zinc-500 uppercase">Pot. Kasbon</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold text-zinc-500 uppercase">Total</th>
                            <th class="text-center px-4 py-3 text-xs font-semibold text-zinc-500 uppercase">Status</th>
                            <th class="text-center px-4 py-3 text-xs font-semibold text-zinc-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @forelse($payrolls as $payroll)
                            @php $isLunas = $payroll->status === 'lunas'; @endphp
                            <tr class="hover:bg-zinc-50 transition-colors {{ !$isLunas ? 'bg-red-50/20' : '' }}">
                                <td class="px-4 py-3 text-zinc-500 text-xs whitespace-nowrap">
                                    {{ $payroll->period_start->format('d/m') }} – {{ $payroll->period_end->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-3 font-semibold text-zinc-800">{{ $payroll->employee->name }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-0.5 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-lg">
                                        {{ $payroll->working_days_count ?? $payroll->working_days ?? 0 }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right text-zinc-500 text-xs">Rp {{ number_format($payroll->daily_rate ?: $payroll->employee->salary_base, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right text-emerald-600 text-xs">+{{ number_format($payroll->bonus, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right text-red-500 text-xs">-{{ number_format($payroll->kasbon_deduction, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right font-bold text-zinc-800">Rp {{ number_format($payroll->total_salary, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-center">
                                    @if($isLunas)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" /></svg>
                                            Lunas
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-600 border border-red-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" /></svg>
                                            Belum Lunas
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                        <a href="{{ route('payrolls.slip', $payroll) }}" target="_blank" class="px-2.5 py-1 bg-indigo-50 text-indigo-700 text-xs font-semibold rounded-lg hover:bg-indigo-100 border border-indigo-100 whitespace-nowrap">📄 Slip</a>
                                        @if(!$isLunas)
                                            <form action="{{ route('payrolls.mark-lunas', $payroll) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1 bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-lg hover:bg-emerald-100 border border-emerald-100 whitespace-nowrap">✓ Lunas</button>
                                            </form>
                                        @endif
                                        <a href="{{ route('payrolls.edit', $payroll) }}" class="px-2.5 py-1 bg-zinc-100 text-zinc-600 text-xs font-semibold rounded-lg hover:bg-zinc-200">Edit</a>
                                        <form action="{{ route('payrolls.destroy', $payroll) }}" method="POST" onsubmit="return confirm('Hapus data penggajian ini? Aksi tidak dapat dibatalkan.')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="px-2.5 py-1 bg-red-50 text-red-600 text-xs font-semibold rounded-lg hover:bg-red-100">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-12 text-center text-zinc-400">
                                    <p class="font-medium">Belum ada riwayat penggajian.</p>
                                    <a href="{{ route('payrolls.recap') }}" class="mt-2 inline-block text-indigo-600 hover:underline text-sm">→ Rekap Gaji Mingguan</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-zinc-100">{{ $payrolls->links() }}</div>
        </div>
    </div>
</x-app-layout>
