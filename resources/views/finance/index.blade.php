<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <span class="text-[11px] font-black text-teal-800/60 uppercase tracking-[0.2em] block mb-1">Manajemen Finansial</span>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">
                    Laporan Keuangan & Arus Kas
                </h2>
            </div>
            
            <div class="flex items-center gap-2">
                <button onclick="document.getElementById('filterSection')?.scrollIntoView({behavior: 'smooth'})" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white/70 border border-slate-200/80 text-slate-700 text-xs font-bold hover:bg-white shadow-sm active:scale-95 transition-all">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    Saring
                </button>
                <a href="{{ route('finance.export.pdf', request()->query()) }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-white/70 border border-slate-200/80 text-slate-700 text-xs font-bold hover:bg-white shadow-sm active:scale-95 transition-all">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Cetak
                </a>
                <a href="{{ route('finance.export.excel', request()->query()) }}" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-teal-700 text-white text-xs font-bold hover:bg-teal-800 shadow-sm shadow-teal-900/10 active:scale-95 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Excel
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 space-y-6 max-w-7xl mx-auto page-enter">

        @if(session('success'))
            <div class="glass-card rounded-[20px] p-4 border border-emerald-200/80 bg-emerald-50/70 text-emerald-800 text-xs font-bold flex items-center gap-3">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="glass-card rounded-[20px] p-4 border border-rose-200/80 bg-rose-50/70 text-rose-800 text-xs font-bold flex items-center gap-3">
                <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- TOP NAVIGATION SEGMENTS -->
        <div class="glass-card p-1.5 rounded-[22px] border border-white/70 shadow-sm flex flex-wrap gap-1.5">
            <button onclick="switchMainTab('ringkasan')" id="main-tab-btn-ringkasan" class="main-tab-btn px-4 py-2.5 rounded-xl text-xs font-black transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Arus Kas & Ringkasan
            </button>
            <button onclick="switchMainTab('mingguan')" id="main-tab-btn-mingguan" class="main-tab-btn px-4 py-2.5 rounded-xl text-xs font-black transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Rincian Mingguan
            </button>
            <button onclick="switchMainTab('bulanan')" id="main-tab-btn-bulanan" class="main-tab-btn px-4 py-2.5 rounded-xl text-xs font-black transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Rekapitulasi 12 Bulan
            </button>
            <button onclick="switchMainTab('terserap')" id="main-tab-btn-terserap" class="main-tab-btn px-4 py-2.5 rounded-xl text-xs font-black transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                Keuangan Terserap
                @if($terserapSummary['sisa_terserap'] > 0)
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black bg-rose-500 text-white font-mono leading-none">
                        Rp {{ number_format($terserapSummary['sisa_terserap'] / 1000, 0) }}k
                    </span>
                @endif
            </button>
        </div>

        <!-- ========================================== -->
        <!-- TAB 1: ARUS KAS & RINGKASAN                -->
        <!-- ========================================== -->
        <div id="main-content-ringkasan" class="main-tab-content space-y-6">
            <!-- SECTION A: Ringkasan Saldo -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="glass-card glass-card-hover p-6 rounded-[24px]">
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Total Pemasukan</p>
                    <h3 class="text-3xl font-black text-emerald-600 tracking-tight leading-none font-mono">
                        Rp {{ number_format($ringkasan['total_pemasukan'], 0, ',', '.') }}
                    </h3>
                    <div class="mt-4 flex items-center gap-2 border-t pt-3 border-slate-100/80">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Total Kas Masuk Terkini</span>
                    </div>
                </div>

                <div class="glass-card glass-card-hover p-6 rounded-[24px]">
                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">Total Pengeluaran</p>
                    <h3 class="text-3xl font-black text-rose-600 tracking-tight leading-none font-mono">
                        Rp {{ number_format($ringkasan['total_pembayaran'], 0, ',', '.') }}
                    </h3>
                    <div class="mt-4 flex items-center gap-2 border-t pt-3 border-slate-100/80">
                        <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Total Kas Keluar Terkini</span>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-teal-800 via-teal-900 to-emerald-950 p-6 rounded-[24px] shadow-xl shadow-teal-950/20 text-white relative overflow-hidden flex flex-col justify-between">
                    <div class="absolute -right-6 -bottom-6 w-36 h-36 bg-teal-400/20 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="relative z-10">
                        <p class="text-[11px] font-black text-teal-200 uppercase tracking-[0.2em] mb-2">Arus Kas Bersih</p>
                        <h3 class="text-3xl font-black tracking-tight leading-none font-mono">
                            Rp {{ number_format($ringkasan['arus_kas_bersih'], 0, ',', '.') }}
                        </h3>
                    </div>
                    <div class="mt-4 flex items-center gap-2 border-t pt-3 border-white/10 relative z-10">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span class="text-[10px] text-teal-100 font-bold uppercase tracking-wider">
                            Saldo Global: Rp {{ number_format($saldoGlobal, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- SECTION B: Charts & Ringkasan Cepat -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
                <div class="lg:col-span-2 space-y-5">
                    <div class="glass-card p-6 rounded-[24px]">
                        <div class="flex items-center justify-between mb-5">
                            <h4 class="text-xs font-black text-slate-700 uppercase tracking-wider">Tren Arus Kas (30 Hari)</h4>
                            <div class="flex gap-4 text-[10px] font-bold uppercase tracking-wider">
                                <div class="flex items-center gap-1.5 text-emerald-700">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Pemasukan
                                </div>
                                <div class="flex items-center gap-1.5 text-rose-600">
                                    <span class="w-2 h-2 rounded-full bg-rose-500"></span> Pengeluaran
                                </div>
                            </div>
                        </div>
                        <div class="h-[260px]">
                            <canvas id="trenChart"></canvas>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="glass-card p-5 rounded-[24px]">
                            <h4 class="text-xs font-black text-slate-700 mb-4 uppercase tracking-wider">Perbandingan Bulanan</h4>
                            <canvas id="perbandinganChart" height="230"></canvas>
                        </div>
                        <div class="glass-card p-5 rounded-[24px]">
                            <h4 class="text-xs font-black text-slate-700 mb-4 uppercase tracking-wider">Distribusi Kategori</h4>
                            <div class="max-w-[200px] mx-auto">
                                <canvas id="pieChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ringkasan Periode Cepat -->
                <div class="glass-card rounded-[24px] flex flex-col overflow-hidden">
                    <div class="bg-white/40 px-5 py-3.5 border-b border-white/60">
                        <h4 class="text-xs font-black text-slate-700 uppercase tracking-wider">Ringkasan Periodik</h4>
                    </div>
                    <div class="p-5 flex-1">
                        <div class="flex gap-3 mb-4">
                            <button onclick="switchQuickTab('mingguan')" id="quick-tab-mingguan" class="text-xs font-black pb-1.5 border-b-2 border-teal-700 text-teal-800 transition-all">Minggu Ini</button>
                            <button onclick="switchQuickTab('bulanan')" id="quick-tab-bulanan" class="text-xs font-black pb-1.5 border-b-2 border-transparent text-slate-400 hover:text-slate-600 transition-all">Bulan Ini</button>
                            <button onclick="switchQuickTab('tahunan')" id="quick-tab-tahunan" class="text-xs font-black pb-1.5 border-b-2 border-transparent text-slate-400 hover:text-slate-600 transition-all">Tahun Ini</button>
                        </div>

                        <div id="quick-content-mingguan" class="quick-tab-content">
                            @include('finance.partials.periodic_table', ['data' => $periodik['mingguan'], 'label' => 'Minggu Ini'])
                        </div>
                        <div id="quick-content-bulanan" class="quick-tab-content hidden">
                            @include('finance.partials.periodic_table', ['data' => $periodik['bulanan'], 'label' => 'Bulan Ini'])
                        </div>
                        <div id="quick-content-tahunan" class="quick-tab-content hidden">
                            @include('finance.partials.periodic_table', ['data' => $periodik['tahunan'], 'label' => 'Tahun Ini'])
                        </div>

                        <!-- Kewajiban Perusahaan -->
                        <div class="mt-6 pt-5 border-t border-slate-100 space-y-3">
                            <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block">Kewajiban Perusahaan</span>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-slate-500 font-semibold">Total Hutang Tertunggak</span>
                                <span class="font-mono font-bold text-rose-600">Rp {{ number_format($ringkasan['total_hutang'], 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-slate-500 font-semibold">Total Tagihan Belum Terbayar</span>
                                <span class="font-mono font-bold text-emerald-600">Rp {{ number_format($ringkasan['total_tagihan'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION C: Saring (Filter Section) -->
            <div id="filterSection" class="glass-card p-5 rounded-[24px] scroll-mt-24 border border-white/70 shadow-sm">
                <form method="GET" action="{{ route('finance.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
                    <input type="hidden" name="tab" value="ringkasan">
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black text-slate-400 mb-1 uppercase tracking-wider">Divisi</label>
                        <select name="division" class="w-full bg-white/70 border border-slate-200/80 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-teal-500">
                            <option value="">Semua Divisi</option>
                            <option value="percetakan" {{ request('division') == 'percetakan' ? 'selected' : '' }}>Percetakan</option>
                            <option value="konfeksi" disabled>Konveksi (Belum Tersedia)</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black text-slate-400 mb-1 uppercase tracking-wider">Entitas</label>
                        <select name="entity" class="w-full bg-white/70 border border-slate-200/80 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-teal-500">
                            <option value="">Semua Entitas</option>
                            <option value="percetakan" {{ request('entity') == 'percetakan' ? 'selected' : '' }}>Percetakan</option>
                            <option value="konfeksi" disabled>Konveksi (Belum Tersedia)</option>
                            <option value="pribadi" {{ request('entity') == 'pribadi' ? 'selected' : '' }}>Pribadi</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black text-slate-400 mb-1 uppercase tracking-wider">Mulai</label>
                        <input type="date" name="start_date" value="{{ $filters['start_date'] }}" class="w-full bg-white/70 border border-slate-200/80 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-teal-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black text-slate-400 mb-1 uppercase tracking-wider">Selesai</label>
                        <input type="date" name="end_date" value="{{ $filters['end_date'] }}" class="w-full bg-white/70 border border-slate-200/80 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-teal-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-black text-slate-400 mb-1 uppercase tracking-wider">Jenis</label>
                        <select name="type" class="w-full bg-white/70 border border-slate-200/80 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-teal-500">
                            <option value="">Semua</option>
                            <option value="credit" {{ request('type') == 'credit' ? 'selected' : '' }}>Masuk</option>
                            <option value="debit" {{ request('type') == 'debit' ? 'selected' : '' }}>Keluar</option>
                        </select>
                    </div>
                    <div class="md:col-span-2 flex items-center gap-2">
                        <button type="submit" class="flex-1 px-4 py-2 bg-teal-700 hover:bg-teal-800 text-white rounded-xl text-xs font-bold shadow-sm shadow-teal-900/10 active:scale-95 transition-all">
                            Saring
                        </button>
                        <a href="{{ route('finance.index') }}" class="px-3 py-2 text-xs font-semibold text-slate-500 hover:text-slate-800">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <!-- SECTION D: Tabel Transaksi -->
            <div class="glass-card rounded-[26px] overflow-hidden border border-white/70 shadow-sm">
                <div class="bg-white/40 px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                    <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider">Rincian Buku Kas Transaksi</h4>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left">
                        <thead class="bg-slate-50/70 border-b border-slate-200/60 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                            <tr>
                                <th class="px-6 py-3.5">Tanggal</th>
                                <th class="px-6 py-3.5">Keterangan</th>
                                <th class="px-6 py-3.5">Kategori</th>
                                <th class="px-6 py-3.5">Entitas</th>
                                <th class="px-6 py-3.5 text-right text-emerald-600">Masuk (+)</th>
                                <th class="px-6 py-3.5 text-right text-rose-600">Keluar (-)</th>
                                <th class="px-6 py-3.5 text-right text-slate-700">Saldo Berjalan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                            @forelse($transaksi as $t)
                                <tr class="hover:bg-teal-50/20 transition-colors">
                                    <td class="px-6 py-3 text-slate-500 font-mono whitespace-nowrap">{{ $t->date->format('d/m/Y') }}</td>
                                    <td class="px-6 py-3">
                                        <div class="font-bold text-slate-800">{{ $t->description }}</div>
                                        <div class="text-[10px] text-teal-700 font-black uppercase tracking-wider mt-0.5">{{ $t->division }}</div>
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        @if($t->category == 'keuangan_terserap')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black bg-rose-50 text-rose-700 border border-rose-200/60 uppercase">Terserap (Divisi Lain)</span>
                                        @elseif($t->category == 'pengembalian_keuangan_terserap')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200/60 uppercase">Pengembalian Terserap</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 uppercase">
                                                {{ str_replace('_', ' ', $t->category) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black uppercase {{ $t->entity == 'pribadi' ? 'bg-amber-50 text-amber-700 border border-amber-200/60' : 'bg-teal-50 text-teal-700 border border-teal-200/60' }}">
                                            {{ $t->entity ?? $t->division }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3 text-right font-mono font-bold text-emerald-600 whitespace-nowrap">
                                        @if($t->type == 'credit') Rp {{ number_format($t->amount, 0, ',', '.') }} @endif
                                    </td>
                                    <td class="px-6 py-3 text-right font-mono font-bold text-rose-600 whitespace-nowrap">
                                        @if($t->type == 'debit') Rp {{ number_format($t->amount, 0, ',', '.') }} @endif
                                    </td>
                                    <td class="px-6 py-3 text-right font-mono font-bold text-slate-800 whitespace-nowrap">
                                        Rp {{ number_format($t->running_balance ?? 0, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-slate-400 font-medium">Belum ada data transaksi untuk periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 2: RICIAN MINGGUAN                     -->
        <!-- ========================================== -->
        <div id="main-content-mingguan" class="main-tab-content hidden space-y-6">
            <!-- Filter Bulan & Tahun -->
            <div class="glass-card p-5 rounded-[24px] border border-white/70 shadow-sm">
                <form method="GET" action="{{ route('finance.index') }}" class="flex flex-wrap items-end gap-3">
                    <input type="hidden" name="tab" value="mingguan">
                    <div class="w-48">
                        <label class="block text-[10px] font-black text-slate-400 mb-1 uppercase tracking-wider">Pilih Bulan</label>
                        <select name="month" class="w-full bg-white/70 border border-slate-200/80 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-teal-500">
                            @foreach([1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'] as $mNum => $mName)
                                <option value="{{ $mNum }}" {{ $selectedMonth == $mNum ? 'selected' : '' }}>{{ $mName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-36">
                        <label class="block text-[10px] font-black text-slate-400 mb-1 uppercase tracking-wider">Pilih Tahun</label>
                        <select name="year" class="w-full bg-white/70 border border-slate-200/80 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-teal-500">
                            @for($y = now()->year + 1; $y >= now()->year - 4; $y--)
                                <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <button type="submit" class="px-5 py-2 bg-teal-700 hover:bg-teal-800 text-white rounded-xl text-xs font-bold shadow-sm active:scale-95 transition-all">
                        Tampilkan Mingguan
                    </button>
                </form>
            </div>

            <!-- Summary Cards Bulan Terpilih -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="glass-card p-5 rounded-[22px]">
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block mb-1">Total Pemasukan Bulan {{ $weeklyBreakdown['month_name'] }}</span>
                    <h4 class="text-2xl font-black text-emerald-600 font-mono">Rp {{ number_format($weeklyBreakdown['total_pemasukan'], 0, ',', '.') }}</h4>
                </div>
                <div class="glass-card p-5 rounded-[22px]">
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block mb-1">Total Pengeluaran Bulan {{ $weeklyBreakdown['month_name'] }}</span>
                    <h4 class="text-2xl font-black text-rose-600 font-mono">Rp {{ number_format($weeklyBreakdown['total_pengeluaran'], 0, ',', '.') }}</h4>
                </div>
                <div class="glass-card p-5 rounded-[22px]">
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block mb-1">Arus Kas Bersih Bulan Ini</span>
                    <h4 class="text-2xl font-black {{ $weeklyBreakdown['total_bersih'] >= 0 ? 'text-teal-700' : 'text-rose-600' }} font-mono">
                        Rp {{ number_format($weeklyBreakdown['total_bersih'], 0, ',', '.') }}
                    </h4>
                </div>
            </div>

            <!-- Tabel 5 Minggu -->
            <div class="glass-card rounded-[26px] overflow-hidden border border-white/70 shadow-sm">
                <div class="bg-white/40 px-6 py-4 border-b border-slate-100">
                    <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider">
                        Rincian Mingguan: {{ $weeklyBreakdown['month_name'] }} {{ $weeklyBreakdown['year'] }}
                    </h4>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left">
                        <thead class="bg-slate-50/70 border-b border-slate-200/60 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                            <tr>
                                <th class="px-6 py-3.5">Periode Minggu</th>
                                <th class="px-6 py-3.5">Rentang Tanggal</th>
                                <th class="px-6 py-3.5 text-right text-emerald-600">Pemasukan Operasional</th>
                                <th class="px-6 py-3.5 text-right text-rose-600">Pengeluaran Operasional</th>
                                <th class="px-6 py-3.5 text-right text-amber-600">Dana Terserap Keluar</th>
                                <th class="px-6 py-3.5 text-right text-blue-600">Pengembalian Masuk</th>
                                <th class="px-6 py-3.5 text-right text-slate-800">Arus Bersih</th>
                                <th class="px-6 py-3.5 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                            @foreach($weeklyBreakdown['weeks'] as $week)
                                <tr class="hover:bg-teal-50/20 transition-colors">
                                    <td class="px-6 py-4 font-black text-slate-800 whitespace-nowrap">{{ $week['label'] }}</td>
                                    <td class="px-6 py-4 font-medium text-slate-500 whitespace-nowrap">{{ $week['date_range'] }}</td>
                                    <td class="px-6 py-4 text-right font-mono font-bold text-emerald-600 whitespace-nowrap">
                                        Rp {{ number_format($week['pemasukan'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-mono font-bold text-rose-600 whitespace-nowrap">
                                        Rp {{ number_format($week['pengeluaran'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-mono font-semibold text-amber-600 whitespace-nowrap">
                                        {{ $week['terserap'] > 0 ? 'Rp ' . number_format($week['terserap'], 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-mono font-semibold text-blue-600 whitespace-nowrap">
                                        {{ $week['kembali'] > 0 ? 'Rp ' . number_format($week['kembali'], 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-mono font-black {{ $week['bersih'] >= 0 ? 'text-teal-800' : 'text-rose-600' }} whitespace-nowrap">
                                        Rp {{ number_format($week['bersih'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        @if($week['bersih'] >= 0)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200/60 uppercase">Surplus</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-rose-50 text-rose-700 border border-rose-200/60 uppercase">Defisit</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-slate-50/80 border-t-2 border-slate-200/80 font-mono font-black text-xs">
                            <tr>
                                <td colspan="2" class="px-6 py-4 text-slate-800 uppercase tracking-wider font-sans font-bold">Total Bulan Ini</td>
                                <td class="px-6 py-4 text-right text-emerald-700">Rp {{ number_format($weeklyBreakdown['total_pemasukan'], 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-right text-rose-700">Rp {{ number_format($weeklyBreakdown['total_pengeluaran'], 0, ',', '.') }}</td>
                                <td colspan="2"></td>
                                <td class="px-6 py-4 text-right {{ $weeklyBreakdown['total_bersih'] >= 0 ? 'text-teal-800' : 'text-rose-700' }}">
                                    Rp {{ number_format($weeklyBreakdown['total_bersih'], 0, ',', '.') }}
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 3: REKAPITULASI 12 BULAN (TAHUNAN)    -->
        <!-- ========================================== -->
        <div id="main-content-bulanan" class="main-tab-content hidden space-y-6">
            <!-- Filter Tahun -->
            <div class="glass-card p-5 rounded-[24px] border border-white/70 shadow-sm">
                <form method="GET" action="{{ route('finance.index') }}" class="flex flex-wrap items-end gap-3">
                    <input type="hidden" name="tab" value="bulanan">
                    <div class="w-48">
                        <label class="block text-[10px] font-black text-slate-400 mb-1 uppercase tracking-wider">Pilih Tahun Laporan</label>
                        <select name="year" class="w-full bg-white/70 border border-slate-200/80 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-teal-500">
                            @for($y = now()->year + 1; $y >= now()->year - 4; $y--)
                                <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>Tahun {{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <button type="submit" class="px-5 py-2 bg-teal-700 hover:bg-teal-800 text-white rounded-xl text-xs font-bold shadow-sm active:scale-95 transition-all">
                        Tampilkan 12 Bulan
                    </button>
                </form>
            </div>

            <!-- Summary Cards Tahun Terpilih -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="glass-card p-5 rounded-[22px]">
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block mb-1">Total Pemasukan Tahun {{ $monthlyBreakdown['year'] }}</span>
                    <h4 class="text-2xl font-black text-emerald-600 font-mono">Rp {{ number_format($monthlyBreakdown['total_pemasukan'], 0, ',', '.') }}</h4>
                </div>
                <div class="glass-card p-5 rounded-[22px]">
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block mb-1">Total Pengeluaran Tahun {{ $monthlyBreakdown['year'] }}</span>
                    <h4 class="text-2xl font-black text-rose-600 font-mono">Rp {{ number_format($monthlyBreakdown['total_pengeluaran'], 0, ',', '.') }}</h4>
                </div>
                <div class="glass-card p-5 rounded-[22px]">
                    <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block mb-1">Arus Kas Bersih 1 Tahun</span>
                    <h4 class="text-2xl font-black {{ $monthlyBreakdown['total_bersih'] >= 0 ? 'text-teal-700' : 'text-rose-600' }} font-mono">
                        Rp {{ number_format($monthlyBreakdown['total_bersih'], 0, ',', '.') }}
                    </h4>
                </div>
            </div>

            <!-- Tabel 12 Bulan -->
            <div class="glass-card rounded-[26px] overflow-hidden border border-white/70 shadow-sm">
                <div class="bg-white/40 px-6 py-4 border-b border-slate-100">
                    <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider">
                        Rincian 12 Bulan Tahun {{ $monthlyBreakdown['year'] }}
                    </h4>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left">
                        <thead class="bg-slate-50/70 border-b border-slate-200/60 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                            <tr>
                                <th class="px-6 py-3.5">Bulan</th>
                                <th class="px-6 py-3.5 text-right text-emerald-600">Pemasukan Operasional</th>
                                <th class="px-6 py-3.5 text-right text-rose-600">Pengeluaran Operasional</th>
                                <th class="px-6 py-3.5 text-right text-amber-600">Dana Terserap</th>
                                <th class="px-6 py-3.5 text-right text-blue-600">Pengembalian</th>
                                <th class="px-6 py-3.5 text-right text-slate-800">Laba / Bersih</th>
                                <th class="px-6 py-3.5 text-right text-indigo-700">Saldo Akumulatif</th>
                                <th class="px-6 py-3.5 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                            @foreach($monthlyBreakdown['months'] as $m)
                                <tr class="hover:bg-teal-50/20 transition-colors">
                                    <td class="px-6 py-3.5 font-bold text-slate-800 whitespace-nowrap">{{ $m['month_name'] }}</td>
                                    <td class="px-6 py-3.5 text-right font-mono font-bold text-emerald-600 whitespace-nowrap">
                                        Rp {{ number_format($m['pemasukan'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-3.5 text-right font-mono font-bold text-rose-600 whitespace-nowrap">
                                        Rp {{ number_format($m['pengeluaran'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-3.5 text-right font-mono font-semibold text-amber-600 whitespace-nowrap">
                                        {{ $m['terserap'] > 0 ? 'Rp ' . number_format($m['terserap'], 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="px-6 py-3.5 text-right font-mono font-semibold text-blue-600 whitespace-nowrap">
                                        {{ $m['kembali'] > 0 ? 'Rp ' . number_format($m['kembali'], 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="px-6 py-3.5 text-right font-mono font-black {{ $m['bersih'] >= 0 ? 'text-teal-800' : 'text-rose-600' }} whitespace-nowrap">
                                        Rp {{ number_format($m['bersih'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-3.5 text-right font-mono font-black text-indigo-700 whitespace-nowrap">
                                        Rp {{ number_format($m['saldo_kumulatif'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-3.5 text-center whitespace-nowrap">
                                        @if($m['bersih'] >= 0)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200/60 uppercase">Surplus</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black bg-rose-50 text-rose-700 border border-rose-200/60 uppercase">Defisit</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-slate-50/80 border-t-2 border-slate-200/80 font-mono font-black text-xs">
                            <tr>
                                <td class="px-6 py-4 text-slate-800 uppercase tracking-wider font-sans font-bold">Total 1 Tahun</td>
                                <td class="px-6 py-4 text-right text-emerald-700">Rp {{ number_format($monthlyBreakdown['total_pemasukan'], 0, ',', '.') }}</td>
                                <td class="px-6 py-4 text-right text-rose-700">Rp {{ number_format($monthlyBreakdown['total_pengeluaran'], 0, ',', '.') }}</td>
                                <td colspan="2"></td>
                                <td class="px-6 py-4 text-right {{ $monthlyBreakdown['total_bersih'] >= 0 ? 'text-teal-800' : 'text-rose-700' }}">
                                    Rp {{ number_format($monthlyBreakdown['total_bersih'], 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-right text-indigo-800">
                                    Rp {{ number_format($monthlyBreakdown['total_bersih'], 0, ',', '.') }}
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 4: KEUANGAN TERSERAP (PERCETAKAN)      -->
        <!-- ========================================== -->
        <div id="main-content-terserap" class="main-tab-content hidden space-y-6">
            <!-- Notice Banner -->
            <div class="glass-card rounded-[22px] p-5 border border-amber-200/80 bg-amber-50/40 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center shrink-0 mt-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider">Fitur Keuangan Terserap (Khusus Percetakan)</h4>
                        <p class="text-xs text-slate-600 mt-0.5">
                            Mencatat arus keluar kas Percetakan yang diserap/dipinjam divisi lain untuk keperluan talangan/hutang. Dana ini <strong>tidak dicatat sebagai beban rugi operasional percetakan</strong>, dan saat dikembalikan <strong>tidak dihitung sebagai omzet penjualan</strong>.
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <button type="button" onclick="document.getElementById('terserapModal').classList.remove('hidden')" class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-sm shadow-rose-600/20 active:scale-95 transition-all">
                        + Catat Keuangan Terserap
                    </button>
                    <button type="button" onclick="document.getElementById('repayTerserapModal').classList.remove('hidden')" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-sm shadow-emerald-600/20 active:scale-95 transition-all">
                        + Catat Pengembalian
                    </button>
                </div>
            </div>

            <!-- Summary Cards Terserap -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="glass-card glass-card-hover p-6 rounded-[24px]">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[11px] font-black uppercase tracking-wider text-slate-400">Total Keuangan Terserap</span>
                        <div class="w-8 h-8 rounded-xl bg-rose-500/10 text-rose-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </div>
                    </div>
                    <h3 class="text-3xl font-black text-rose-600 font-mono tracking-tight">
                        Rp {{ number_format($terserapSummary['total_terserap'], 0, ',', '.') }}
                    </h3>
                    <p class="text-[11px] text-slate-400 mt-2 font-medium">Total dana kas yang diserap divisi lain</p>
                </div>

                <div class="glass-card glass-card-hover p-6 rounded-[24px]">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[11px] font-black uppercase tracking-wider text-slate-400">Total Sudah Dikembalikan</span>
                        <div class="w-8 h-8 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                        </div>
                    </div>
                    <h3 class="text-3xl font-black text-emerald-600 font-mono tracking-tight">
                        Rp {{ number_format($terserapSummary['total_dikembalikan'], 0, ',', '.') }}
                    </h3>
                    <p class="text-[11px] text-slate-400 mt-2 font-medium">Dana yang telah masuk kembali ke kas</p>
                </div>

                <div class="glass-card glass-card-hover p-6 rounded-[24px] border-2 {{ $terserapSummary['sisa_terserap'] > 0 ? 'border-amber-300/80 bg-amber-50/20' : 'border-emerald-200/60' }}">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[11px] font-black uppercase tracking-wider text-slate-400">Sisa Belum Kembali</span>
                        <div class="w-8 h-8 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <h3 class="text-3xl font-black {{ $terserapSummary['sisa_terserap'] > 0 ? 'text-amber-700' : 'text-emerald-700' }} font-mono tracking-tight">
                        Rp {{ number_format($terserapSummary['sisa_terserap'], 0, ',', '.') }}
                    </h3>
                    <p class="text-[11px] text-slate-400 mt-2 font-medium">Saldo aktif dana percetakan yang masih tertahan</p>
                </div>
            </div>

            <!-- Tabel Riwayat Keuangan Terserap -->
            <div class="glass-card rounded-[26px] overflow-hidden border border-white/70 shadow-sm">
                <div class="bg-white/40 px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                    <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider">Riwayat Mutasi Keuangan Terserap</h4>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left">
                        <thead class="bg-slate-50/70 border-b border-slate-200/60 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                            <tr>
                                <th class="px-6 py-3.5">Tanggal</th>
                                <th class="px-6 py-3.5">Jenis Mutasi</th>
                                <th class="px-6 py-3.5">Keterangan / Divisi</th>
                                <th class="px-6 py-3.5 text-right">Nominal (Rp)</th>
                                <th class="px-6 py-3.5 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                            @forelse($terserapSummary['riwayat'] as $item)
                                <tr class="hover:bg-teal-50/20 transition-colors">
                                    <td class="px-6 py-3.5 font-mono text-slate-500 whitespace-nowrap">{{ $item->date->format('d/m/Y') }}</td>
                                    <td class="px-6 py-3.5 whitespace-nowrap">
                                        @if($item->category == 'keuangan_terserap')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-rose-50 text-rose-700 border border-rose-200/60 uppercase">
                                                Diserap Keluar (-)
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200/60 uppercase">
                                                Dikembalikan (+)
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-3.5 font-semibold text-slate-800">
                                        {{ $item->description }}
                                    </td>
                                    <td class="px-6 py-3.5 text-right font-mono font-black whitespace-nowrap {{ $item->category == 'keuangan_terserap' ? 'text-rose-600' : 'text-emerald-600' }}">
                                        {{ $item->category == 'keuangan_terserap' ? '-' : '+' }} Rp {{ number_format($item->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-3.5 text-center whitespace-nowrap">
                                        <form action="{{ route('finance.terserap.destroy', $item) }}" method="POST" class="action-confirm inline-block"
                                            data-title="Hapus Catatan Ini?"
                                            data-text="Apakah Anda yakin ingin menghapus catatan mutasi keuangan terserap ini?"
                                            data-icon="warning"
                                            data-confirm-text="Ya, Hapus"
                                            data-confirm-color="#ef4444">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-rose-600 hover:text-rose-800 text-xs font-semibold hover:underline">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-400 font-medium">
                                        Belum ada catatan keuangan terserap untuk divisi percetakan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    @include('finance.partials.modals')

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const chartFont = { size: 10, weight: 'bold', family: 'Inter, sans-serif' };

        // Tren Chart (Tren Arus Kas)
        const ctxTren = document.getElementById('trenChart');
        if (ctxTren) {
            new Chart(ctxTren, {
                type: 'line',
                data: {
                    labels: @json($chartData['tren']['labels']),
                    datasets: [
                        {
                            label: 'Pemasukan',
                            data: @json($chartData['tren']['pemasukan']),
                            borderColor: '#10b981',
                            backgroundColor: '#10b98105',
                            fill: true,
                            tension: 0.35,
                            borderWidth: 2,
                            pointRadius: 0,
                            pointHoverRadius: 4
                        },
                        {
                            label: 'Pembayaran',
                            data: @json($chartData['tren']['pembayaran']),
                            borderColor: '#ef4444',
                            backgroundColor: '#ef444405',
                            fill: true,
                            tension: 0.35,
                            borderWidth: 2,
                            pointRadius: 0,
                            pointHoverRadius: 4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { 
                        y: { 
                            beginAtZero: true, 
                            grid: { color: '#f3f4f6', drawBorder: false },
                            ticks: { font: chartFont, color: '#9ca3af', callback: v => v/1000 + 'k' }
                        },
                        x: { 
                            grid: { display: false },
                            ticks: { font: chartFont, color: '#9ca3af' }
                        }
                    }
                }
            });
        }

        // Perbandingan Chart (Bar)
        const ctxPerbandingan = document.getElementById('perbandinganChart');
        if (ctxPerbandingan) {
            new Chart(ctxPerbandingan, {
                type: 'bar',
                data: {
                    labels: @json($chartData['perbandingan']['labels']),
                    datasets: [
                        {
                            label: 'Masuk',
                            data: @json($chartData['perbandingan']['pemasukan']),
                            backgroundColor: '#0d9488',
                            borderRadius: 6,
                            barThickness: 20
                        },
                        {
                            label: 'Keluar',
                            data: @json($chartData['perbandingan']['pembayaran']),
                            backgroundColor: '#e2e8f0',
                            borderRadius: 6,
                            barThickness: 20
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: { 
                        y: { beginAtZero: true, grid: { color: '#f3f4f6', drawBorder: false }, ticks: { font: chartFont, color: '#9ca3af' } },
                        x: { grid: { display: false }, ticks: { font: chartFont, color: '#9ca3af' } }
                    }
                }
            });
        }

        // Pie Chart (Doughnut)
        const ctxPie = document.getElementById('pieChart');
        if (ctxPie) {
            new Chart(ctxPie, {
                type: 'doughnut',
                data: {
                    labels: @json($chartData['pie']['labels']),
                    datasets: [{
                        data: @json($chartData['pie']['totals']),
                        backgroundColor: ['#0d9488','#10b981','#f59e0b','#ef4444','#8b5cf6','#fb7185','#34d399','#818cf8'],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    cutout: '75%',
                    plugins: { legend: { display: false } }
                }
            });
        }

        function switchMainTab(tab) {
            document.querySelectorAll('.main-tab-content').forEach(c => c.classList.add('hidden'));
            const targetContent = document.getElementById('main-content-' + tab);
            if (targetContent) {
                targetContent.classList.remove('hidden');
            }

            document.querySelectorAll('.main-tab-btn').forEach(btn => {
                btn.classList.remove('coach-active', 'bg-teal-800', 'text-white', 'shadow-md');
                btn.classList.add('text-slate-600', 'hover:text-slate-900', 'hover:bg-white/60');
            });

            const activeBtn = document.getElementById('main-tab-btn-' + tab);
            if (activeBtn) {
                activeBtn.classList.add('coach-active', 'bg-teal-800', 'text-white', 'shadow-md');
                activeBtn.classList.remove('text-slate-600', 'hover:text-slate-900', 'hover:bg-white/60');
            }

            const url = new URL(window.location);
            url.searchParams.set('tab', tab);
            window.history.replaceState({}, '', url);
        }

        function switchQuickTab(tab) {
            document.querySelectorAll('.quick-tab-content').forEach(c => c.classList.add('hidden'));
            document.getElementById('quick-content-' + tab).classList.remove('hidden');
            
            document.querySelectorAll('[id^="quick-tab-"]').forEach(t => {
                t.classList.remove('border-teal-700', 'text-teal-800');
                t.classList.add('border-transparent', 'text-slate-400');
            });
            document.getElementById('quick-tab-' + tab).classList.add('border-teal-700', 'text-teal-800');
            document.getElementById('quick-tab-' + tab).classList.remove('border-transparent', 'text-slate-400');
        }

        // Initialize active main tab on load
        document.addEventListener('DOMContentLoaded', function () {
            const initialTab = "{{ $activeTab ?? 'ringkasan' }}";
            switchMainTab(initialTab);
        });
    </script>
    @endpush
</x-app-layout>
