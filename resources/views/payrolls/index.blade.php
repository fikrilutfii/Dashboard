<x-app-layout>
    <x-slot name="header">Penggajian Karyawan</x-slot>

    <div class="space-y-6 pb-12">
        <!-- Page Header & Action Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-50 border border-teal-100 text-teal-800 text-xs font-semibold mb-2">
                    <span class="w-2 h-2 rounded-full bg-teal-500"></span>
                    Manajemen SDM & Upah Kerja
                </div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Penggajian Karyawan</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Kelola slip gaji periode mingguan/bulanan, potongan kasbon, dan pencatatan pembayaran.</p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('payrolls.print') . (request()->has('date_start') ? '?' . request()->getQueryString() : '') }}" target="_blank"
                   class="glass-pill px-4 py-2.5 text-xs font-bold text-slate-700 hover:text-slate-950 rounded-xl transition-all flex items-center gap-2 shadow-2xs">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-teal-700">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" />
                    </svg>
                    Cetak Laporan
                </a>
                <a href="{{ route('payrolls.recap') }}" 
                   class="bg-gradient-to-r from-teal-700 to-emerald-800 hover:from-teal-800 hover:to-emerald-900 text-white font-extrabold text-xs px-4 py-2.5 rounded-xl shadow-sm transition-all flex items-center gap-1.5 active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5m.75-9l3-3 2.25 2.25L15 7.5" />
                    </svg>
                    Rekap Mingguan
                </a>
            </div>
        </div>

        <!-- Metric Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <!-- Card 1: Total Gaji Bulan Ini -->
            <div class="glass-card rounded-[22px] p-5 flex items-center gap-4 group hover:border-teal-400/50 transition-all">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-teal-600 to-emerald-800 flex items-center justify-center text-white shadow-md shadow-teal-700/20 group-hover:scale-105 transition-transform flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6H2.25m0 0H3m0 0h1.5m0 0h15m0 0h1.5m0 0H21m-1.5 0v.75A.75.75 0 0118.75 6H18m0 0v12.75M3.75 6v12.75m16.5 0a2.25 2.25 0 01-2.25 2.25H4.5A2.25 2.25 0 012.25 18.75" />
                    </svg>
                </div>
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Total Gaji Bulan Ini</span>
                    <span class="text-xl sm:text-2xl font-black text-slate-800 leading-tight">Rp {{ number_format($totalGajiBulanIni, 0, ',', '.') }}</span>
                    <span class="text-[10px] text-teal-700 font-semibold block mt-0.5">Akumulasi seluruh staf</span>
                </div>
            </div>

            <!-- Card 2: Sudah Lunas Bulan Ini -->
            <div class="glass-card rounded-[22px] p-5 flex items-center gap-4 group hover:border-emerald-400/50 transition-all">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-700 flex items-center justify-center text-white shadow-md shadow-emerald-700/20 group-hover:scale-105 transition-transform flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Sudah Lunas Bulan Ini</span>
                    <span class="text-xl sm:text-2xl font-black text-emerald-700 leading-tight">Rp {{ number_format($sudahLunasBulanIni, 0, ',', '.') }}</span>
                    <span class="text-[10px] text-emerald-600 font-semibold block mt-0.5">Sudah dibayarkan</span>
                </div>
            </div>

            <!-- Card 3: Belum Lunas (Semua) -->
            <div class="glass-card rounded-[22px] p-5 flex items-center gap-4 group hover:border-rose-400/50 transition-all">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-rose-500 to-red-700 flex items-center justify-center text-white shadow-md shadow-rose-700/20 group-hover:scale-105 transition-transform flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                </div>
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Belum Lunas (Semua)</span>
                    <span class="text-xl sm:text-2xl font-black text-rose-700 leading-tight">Rp {{ number_format($belumLunasAll, 0, ',', '.') }}</span>
                    <span class="text-[10px] text-rose-600 font-semibold block mt-0.5">Kewajiban berjalan</span>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50/90 border border-emerald-200/90 text-emerald-800 text-xs font-bold flex items-center gap-2.5 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Main Table Card -->
        <div class="glass-card rounded-[26px] overflow-hidden shadow-sm">
            <!-- Filter Bar -->
            <div class="p-5 sm:p-6 border-b border-slate-100/90">
                <form method="GET" action="{{ route('payrolls.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                    <div class="relative lg:col-span-2">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama karyawan..." 
                               class="w-full text-xs bg-white/95 border border-slate-200/90 rounded-xl pl-9 pr-3 py-2.5 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all shadow-2xs">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-slate-400 absolute left-3 top-3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>

                    <div>
                        <input type="date" name="date_start" value="{{ request('date_start') }}" 
                               class="w-full text-xs bg-white/95 border border-slate-200/90 rounded-xl px-3 py-2.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all shadow-2xs">
                    </div>

                    <div>
                        <input type="date" name="date_end" value="{{ request('date_end') }}" 
                               class="w-full text-xs bg-white/95 border border-slate-200/90 rounded-xl px-3 py-2.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all shadow-2xs">
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 bg-gradient-to-r from-teal-700 to-emerald-800 hover:from-teal-800 hover:to-emerald-900 text-white font-bold text-xs py-2.5 px-4 rounded-xl shadow-sm transition-all flex items-center justify-center gap-1.5 active:scale-95">
                            Filter
                        </button>
                        @if(request('search') || request('date_start') || request('date_end'))
                            <a href="{{ route('payrolls.index') }}" class="glass-pill text-xs font-bold text-slate-600 hover:text-slate-900 py-2.5 px-3 rounded-xl transition-all shadow-2xs">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-200/70 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            <th class="px-5 py-4">Periode</th>
                            <th class="px-5 py-4">Karyawan</th>
                            <th class="px-5 py-4 text-center">Hari</th>
                            <th class="px-5 py-4 text-right">Gaji/Hari</th>
                            <th class="px-5 py-4 text-right">Bonus</th>
                            <th class="px-5 py-4 text-right">Pot. Kasbon</th>
                            <th class="px-5 py-4 text-right">Total Gaji</th>
                            <th class="px-5 py-4 text-center">Status</th>
                            <th class="px-5 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100/90 text-xs">
                        @forelse($payrolls as $payroll)
                            @php $isLunas = $payroll->status === 'lunas'; @endphp
                            <tr class="hover:bg-teal-50/20 transition-colors {{ !$isLunas ? 'bg-rose-50/15' : '' }}">
                                <!-- Periode -->
                                <td class="px-5 py-4 text-slate-500 whitespace-nowrap font-medium">
                                    {{ $payroll->period_start->format('d/m') }} – {{ $payroll->period_end->format('d/m/Y') }}
                                </td>

                                <!-- Karyawan -->
                                <td class="px-5 py-4 font-bold text-slate-800 whitespace-nowrap">
                                    {{ $payroll->employee->name }}
                                </td>

                                <!-- Hari Kerja -->
                                <td class="px-5 py-4 text-center whitespace-nowrap">
                                    <span class="px-2.5 py-1 bg-teal-50 border border-teal-200/60 text-teal-800 text-xs font-black rounded-lg">
                                        {{ $payroll->working_days_count ?? $payroll->working_days ?? 0 }} hr
                                    </span>
                                </td>

                                <!-- Gaji/Hari -->
                                <td class="px-5 py-4 text-right text-slate-600 font-semibold whitespace-nowrap">
                                    Rp {{ number_format($payroll->daily_rate ?: $payroll->employee->salary_base, 0, ',', '.') }}
                                </td>

                                <!-- Bonus -->
                                <td class="px-5 py-4 text-right text-emerald-700 font-extrabold whitespace-nowrap">
                                    +Rp {{ number_format($payroll->bonus, 0, ',', '.') }}
                                </td>

                                <!-- Pot. Kasbon -->
                                <td class="px-5 py-4 text-right text-rose-600 font-extrabold whitespace-nowrap">
                                    -Rp {{ number_format($payroll->kasbon_deduction, 0, ',', '.') }}
                                </td>

                                <!-- Total Gaji -->
                                <td class="px-5 py-4 text-right font-black text-slate-800 text-sm whitespace-nowrap">
                                    Rp {{ number_format($payroll->total_salary, 0, ',', '.') }}
                                </td>

                                <!-- Status -->
                                <td class="px-5 py-4 text-center whitespace-nowrap">
                                    @if($isLunas)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-extrabold bg-emerald-50 text-emerald-800 border border-emerald-200/80">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" /></svg>
                                            Lunas
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-extrabold bg-rose-50 text-rose-700 border border-rose-200/80">
                                            Belum Lunas
                                        </span>
                                    @endif
                                </td>

                                <!-- Aksi -->
                                <td class="px-5 py-4 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                        <a href="{{ route('payrolls.slip', $payroll) }}" target="_blank" 
                                           class="px-2.5 py-1 bg-teal-50 text-teal-800 hover:bg-teal-100/80 text-xs font-bold rounded-lg border border-teal-200/60 transition-colors">
                                            Slip
                                        </a>

                                        @if(!$isLunas)
                                            <form action="{{ route('payrolls.mark-lunas', $payroll) }}" method="POST" class="action-confirm inline"
                                                data-title="Konfirmasi Pelunasan Gaji"
                                                data-text="Tandai pembayaran gaji {{ $payroll->employee->name ?? 'karyawan' }} sebesar Rp {{ number_format($payroll->net_salary, 0, ',', '.') }} sebagai LUNAS?"
                                                data-icon="question"
                                                data-confirm-text="Ya, Lunasi"
                                                data-confirm-color="#0d5c58">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1 bg-emerald-50 text-emerald-800 hover:bg-emerald-100 text-xs font-bold rounded-lg border border-emerald-200/70 transition-colors">
                                                    Lunaskan
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('payrolls.revert-lunas', $payroll) }}" method="POST" class="action-confirm inline"
                                                data-title="Batalkan Status Lunas Gaji?"
                                                data-text="Kembalikan status gaji {{ $payroll->employee->name ?? 'karyawan' }} ke Belum Lunas dan batalkan pencatatan pengeluaran kas?"
                                                data-icon="warning"
                                                data-confirm-text="Ya, Batalkan Lunas"
                                                data-confirm-color="#f59e0b">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1 bg-amber-50 text-amber-800 hover:bg-amber-100 text-xs font-bold rounded-lg border border-amber-200/70 transition-colors">
                                                    Batal Lunas
                                                </button>
                                            </form>
                                        @endif

                                        <a href="{{ route('payrolls.edit', $payroll) }}" class="px-2 py-1 glass-pill text-slate-700 hover:text-slate-900 text-xs font-semibold rounded-lg shadow-2xs">
                                            Edit
                                        </a>

                                        @if(Auth::user()->isAdmin())
                                            <form action="{{ route('payrolls.destroy', $payroll) }}" method="POST" class="action-confirm inline-block" data-title="Apakah Anda yakin?" data-text="Hapus data penggajian ini? Aksi tidak dapat dibatalkan." data-icon="warning" data-confirm-text="Ya, Hapus!" data-confirm-color="#ef4444">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="px-2 py-1 bg-rose-50 text-rose-700 hover:bg-rose-100 text-xs font-bold rounded-lg border border-rose-200/60">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center text-slate-400">
                                    <div class="w-12 h-12 mx-auto rounded-2xl bg-teal-50 flex items-center justify-center text-teal-700 mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <p class="font-bold text-slate-700">Belum ada riwayat penggajian.</p>
                                    <a href="{{ route('payrolls.recap') }}" class="mt-2 inline-block text-teal-700 hover:underline text-xs font-bold">→ Rekap Gaji Mingguan</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Bar -->
            <div class="p-4 sm:p-5 border-t border-slate-100/90 bg-white/40">
                {{ $payrolls->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
