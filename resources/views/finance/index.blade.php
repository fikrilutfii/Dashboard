<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="flex-1 min-w-0">
                <!-- Header Title Removed for Cleanliness -->
            </div>
            
            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <!-- Tombol Tambah Transaksi Dihilangkan Agar Terpisah -->
            </div>

                <div class="h-8 border-l border-gray-200 mx-1 hidden lg:block"></div>

                <!-- Tombol Saring, Cetak, Unduh (Outline) -->
                <div class="flex items-center gap-2">
                    <button onclick="document.getElementById('filterSection').scrollIntoView({behavior: 'smooth'})" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-600 text-sm font-bold rounded-xl hover:bg-gray-50 hover:border-gray-400 hover:text-gray-800 transition-all active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Saring
                    </button>
                    <a href="{{ route('finance.export.pdf', request()->query()) }}" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 text-gray-600 text-sm font-bold rounded-xl hover:bg-gray-50 hover:border-gray-400 hover:text-gray-800 transition-all active:scale-95">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        Cetak
                    </a>
                    <a href="{{ route('finance.export.excel', request()->query()) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-600 text-sm font-bold rounded-xl hover:bg-gray-50 hover:border-gray-400 hover:text-gray-800 transition-all active:scale-95 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Excel
                    </a>
                </div>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-50/50 min-h-screen page-enter">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- SECTION A: Ringkasan Saldo -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div class="bg-white/80 backdrop-blur-xl p-8 rounded-[2rem] border border-white/60 shadow-premium group hover:shadow-premium-hover transition-all duration-500">
                    <p class="text-[11px] font-black text-zinc-400 uppercase tracking-[0.2em] mb-3 italic">Total Pemasukan</p>
                    <h3 class="text-3xl font-black text-emerald-600 tracking-tight">Rp {{ number_format($ringkasan['total_pemasukan'], 0, ',', '.') }}</h3>
                    <div class="mt-4 flex items-center gap-2 border-t pt-4 border-zinc-100">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-[10px] text-zinc-400 font-bold uppercase tracking-wider">Dana Masuk Terkini</span>
                    </div>
                </div>

                <div class="bg-white/80 backdrop-blur-xl p-8 rounded-[2rem] border border-white/60 shadow-premium group hover:shadow-premium-hover transition-all duration-500">
                    <p class="text-[11px] font-black text-zinc-400 uppercase tracking-[0.2em] mb-3 italic">Total Pengeluaran</p>
                    <h3 class="text-3xl font-black text-rose-600 tracking-tight">Rp {{ number_format($ringkasan['total_pembayaran'], 0, ',', '.') }}</h3>
                    <div class="mt-4 flex items-center gap-2 border-t pt-4 border-zinc-100">
                        <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                        <span class="text-[10px] text-zinc-400 font-bold uppercase tracking-wider">Dana Keluar Terkini</span>
                    </div>
                </div>

                <div class="premium-gradient p-8 rounded-[2rem] shadow-lg shadow-primary-500/30 group hover:shadow-primary-500/40 transition-all duration-500 text-white">
                    <p class="text-[11px] font-black text-primary-200 uppercase tracking-[0.2em] mb-3 italic">Arus Kas Bersih</p>
                    <h3 class="text-3xl font-black tracking-tight">
                        Rp {{ number_format($ringkasan['arus_kas_bersih'], 0, ',', '.') }}
                    </h3>
                    <div class="mt-4 flex items-center gap-2 border-t pt-4 border-white/10">
                        <span class="w-2 h-2 rounded-full bg-white animate-bounce"></span>
                        <span class="text-[10px] text-primary-100 font-bold uppercase tracking-wider">Sisa Operasional</span>
                    </div>
                </div>
            </div>


            <!-- SECTION B: Statistik & Periode -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Grafik Utama -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                        <div class="flex items-center justify-between mb-6">
                            <h4 class="text-sm font-bold text-gray-700 uppercase tracking-wider">Tren Arus Kas (30 Hari)</h4>
                            <div class="flex gap-4 text-[10px] font-bold uppercase tracking-tighter">
                                <div class="flex items-center gap-1 text-emerald-600">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Pemasukan
                                </div>
                                <div class="flex items-center gap-1 text-rose-600">
                                    <span class="w-2 h-2 rounded-full bg-rose-500"></span> Pengeluaran
                                </div>
                            </div>
                        </div>
                        <div class="h-[280px]">
                            <canvas id="trenChart"></canvas>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                            <h4 class="text-sm font-bold text-gray-700 mb-6 uppercase tracking-wider">Perbandingan Bulanan</h4>
                            <canvas id="perbandinganChart" height="250"></canvas>
                        </div>
                        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                            <h4 class="text-sm font-bold text-gray-700 mb-6 uppercase tracking-wider">Distribusi Kategori</h4>
                            <div class="max-w-[220px] mx-auto">
                                <canvas id="pieChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ringkasan Periodik -->
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm flex flex-col overflow-hidden">
                    <div class="bg-gray-50/50 px-6 py-4 border-b">
                        <h4 class="text-sm font-bold text-gray-700 uppercase tracking-wider">Ringkasan Periodik</h4>
                    </div>
                    <div class="p-6 flex-1">
                        <div class="flex gap-4 mb-8">
                            <button onclick="switchTab('mingguan')" id="tab-mingguan" class="text-xs font-bold pb-2 border-b-2 border-indigo-600 text-indigo-600 transition-all">Mingguan</button>
                            <button onclick="switchTab('bulanan')" id="tab-bulanan" class="text-xs font-bold pb-2 border-b-2 border-transparent text-gray-400 hover:text-gray-600 transition-all">Bulanan</button>
                            <button onclick="switchTab('tahunan')" id="tab-tahunan" class="text-xs font-bold pb-2 border-b-2 border-transparent text-gray-400 hover:text-gray-600 transition-all">Tahunan</button>
                        </div>

                        <div id="content-mingguan" class="tab-content">
                            @include('finance.partials.periodic_table', ['data' => $periodik['mingguan'], 'label' => 'Minggu Ini'])
                        </div>
                        <div id="content-bulanan" class="tab-content hidden">
                            @include('finance.partials.periodic_table', ['data' => $periodik['bulanan'], 'label' => 'Bulan Ini'])
                        </div>
                        <div id="content-tahunan" class="tab-content hidden">
                            @include('finance.partials.periodic_table', ['data' => $periodik['tahunan'], 'label' => 'Tahun Ini'])
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION C: Saring (Filter Section) -->
            <div id="filterSection" class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm mb-8 scroll-mt-24">
                <form method="GET" action="{{ route('finance.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-gray-400 mb-1 uppercase tracking-widest italic">Divisi</label>
                        <select name="division" class="w-full border-gray-200 rounded-lg text-sm bg-gray-50/50">
                            <option value="">Semua Divisi</option>
                            <option value="percetakan" {{ request('division') == 'percetakan' ? 'selected' : '' }}>Percetakan</option>
                            <option value="konfeksi" disabled>Konveksi (Belum Tersedia)</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-gray-400 mb-1 uppercase tracking-widest italic">Entitas</label>
                        <select name="entity" class="w-full border-gray-200 rounded-lg text-sm bg-gray-50/50">
                            <option value="">Semua Entitas</option>
                            <option value="percetakan" {{ request('entity') == 'percetakan' ? 'selected' : '' }}>Percetakan</option>
                            <option value="konfeksi" disabled>Konveksi (Belum Tersedia)</option>
                            <option value="pribadi" {{ request('entity') == 'pribadi' ? 'selected' : '' }}>Pribadi</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-gray-400 mb-1 uppercase tracking-widest italic">Mulai</label>
                        <input type="date" name="start_date" value="{{ $filters['start_date'] }}" class="w-full border-gray-200 rounded-lg text-sm bg-gray-50/50">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-gray-400 mb-1 uppercase tracking-widest italic">Selesai</label>
                        <input type="date" name="end_date" value="{{ $filters['end_date'] }}" class="w-full border-gray-200 rounded-lg text-sm bg-gray-50/50">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[10px] font-bold text-gray-400 mb-1 uppercase tracking-widest italic">Jenis</label>
                        <select name="type" class="w-full border-gray-200 rounded-lg text-sm bg-gray-50/50">
                            <option value="">Semua</option>
                            <option value="credit" {{ request('type') == 'credit' ? 'selected' : '' }}>Masuk</option>
                            <option value="debit" {{ request('type') == 'debit' ? 'selected' : '' }}>Keluar</option>
                        </select>
                    </div>
                    <div class="md:col-span-1">
                        <button type="submit" class="w-full h-10 flex items-center justify-center bg-gray-900 text-white rounded-lg hover:bg-black transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </button>
                    </div>
                    
                    <div class="md:col-span-12 flex items-center gap-3 mt-2">
                        <span class="text-[10px] font-bold text-gray-300 uppercase italic mr-2 tracking-widest leading-none">Preset:</span>
                        <button type="button" onclick="setPreset('today')" class="text-[9px] font-bold border border-gray-200 bg-white text-gray-500 px-3 py-1.5 rounded-full hover:bg-gray-50 transition-all uppercase">Hari Ini</button>
                        <button type="button" onclick="setPreset('month')" class="text-[9px] font-bold border border-gray-200 bg-white text-gray-500 px-3 py-1.5 rounded-full hover:bg-gray-50 transition-all uppercase">Bulan Ini</button>
                        <button type="button" onclick="setPreset('year')" class="text-[9px] font-bold border border-gray-200 bg-white text-gray-500 px-3 py-1.5 rounded-full hover:bg-gray-50 transition-all uppercase">Tahun Ini</button>
                        <a href="{{ route('finance.index') }}" class="text-[9px] font-bold text-rose-400 hover:text-rose-600 uppercase ml-auto tracking-widest">Atur Ulang</a>
                    </div>
                </form>
            </div>

            <!-- SECTION D: Tabel Transaksi -->
            <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] border border-white/60 shadow-premium overflow-hidden">
                <div class="bg-gray-50/50 px-8 py-6 border-b border-zinc-100 flex justify-between items-center">
                    <h4 class="text-sm font-black text-zinc-900 uppercase tracking-widest">Rincian Transaksi</h4>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50/50 text-[10px] font-black text-zinc-400 uppercase tracking-[0.3em]">
                            <tr>
                                <th class="px-8 py-5 text-left">Tanggal</th>
                                <th class="px-8 py-5 text-left">Keterangan</th>
                                <th class="px-8 py-5 text-left">Kategori</th>
                                <th class="px-8 py-5 text-left">Entitas</th>
                                <th class="px-8 py-5 text-right text-emerald-600">Masuk (+)</th>
                                <th class="px-8 py-5 text-right text-rose-600">Keluar (-)</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-zinc-100">
                            @forelse($transaksi as $t)
                                <tr class="hover:bg-primary-50/50 transition-all duration-300 group">
                                    <td class="px-8 py-5 text-[11px] font-bold text-zinc-400 whitespace-nowrap">{{ $t->date->format('d/m/Y') }}</td>
                                    <td class="px-8 py-5">
                                        <div class="text-sm font-bold text-zinc-800 group-hover:text-primary-600 transition-colors">{{ $t->description }}</div>
                                        <div class="text-[9px] text-primary-400 font-black uppercase tracking-widest mt-1">{{ $t->division }}</div>
                                    </td>
                                    <td class="px-8 py-5 whitespace-nowrap">
                                        <span class="text-[10px] font-black text-zinc-500 bg-zinc-100 border border-zinc-200 px-3 py-1 rounded-full uppercase tracking-widest">
                                            {{ str_replace('_', ' ', $t->category) }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 whitespace-nowrap">
                                        <span class="text-[10px] font-black {{ $t->entity == 'pribadi' ? 'text-rose-500 bg-rose-50 border-rose-100' : ($t->entity == 'konfeksi' ? 'text-emerald-500 bg-emerald-50 border-emerald-100' : 'text-primary-500 bg-primary-50 border-primary-100') }} border px-3 py-1 rounded-full uppercase tracking-widest">
                                            {{ $t->entity ?? $t->division }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 text-sm text-right font-black text-emerald-600">
                                        @if($t->type == 'credit') Rp {{ number_format($t->amount, 0, ',', '.') }} @endif
                                    </td>
                                    <td class="px-8 py-5 text-sm text-right font-black text-rose-600">
                                        @if($t->type == 'debit') Rp {{ number_format($t->amount, 0, ',', '.') }} @endif
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="6" class="px-8 py-20 text-center text-zinc-400 italic text-sm">Belum ada data transaksi untuk periode ini.</td>
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
        new Chart(document.getElementById('trenChart'), {
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

        // Perbandingan Chart (Bar)
        new Chart(document.getElementById('perbandinganChart'), {
            type: 'bar',
            data: {
                labels: @json($chartData['perbandingan']['labels']),
                datasets: [
                    {
                        label: 'Masuk',
                        data: @json($chartData['perbandingan']['pemasukan']),
                        backgroundColor: '#6366f1',
                        borderRadius: 4,
                        barThickness: 20
                    },
                    {
                        label: 'Keluar',
                        data: @json($chartData['perbandingan']['pembayaran']),
                        backgroundColor: '#e5e7eb',
                        borderRadius: 4,
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

        // Pie Chart (Doughnut)
        new Chart(document.getElementById('pieChart'), {
            type: 'doughnut',
            data: {
                labels: @json($chartData['pie']['labels']),
                datasets: [{
                    data: @json($chartData['pie']['totals']),
                    backgroundColor: ['#6366f1','#10b981','#f59e0b','#ef4444','#8b5cf6','#fb7185','#34d399','#818cf8'],
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

        function switchTab(tab) {
            document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
            document.getElementById('content-' + tab).classList.remove('hidden');
            
            document.querySelectorAll('[id^="tab-"]').forEach(t => {
                t.classList.remove('border-indigo-600', 'text-indigo-600');
                t.classList.add('border-transparent', 'text-gray-400');
            });
            document.getElementById('tab-' + tab).classList.add('border-indigo-600', 'text-indigo-600');
            document.getElementById('tab-' + tab).classList.remove('border-transparent', 'text-gray-400');
        }

        function setPreset(preset) {
            const start = document.getElementsByName('start_date')[0];
            const end = document.getElementsByName('end_date')[0];
            const now = new Date();
            
            if(preset === 'today') {
                const today = now.toISOString().split('T')[0];
                start.value = today; end.value = today;
            } else if(preset === 'month') {
                start.value = new Date(now.getFullYear(), now.getMonth(), 1).toISOString().split('T')[0];
                end.value = new Date(now.getFullYear(), now.getMonth() + 1, 0).toISOString().split('T')[0];
            } else if(preset === 'year') {
                start.value = new Date(now.getFullYear(), 0, 1).toISOString().split('T')[0];
                end.value = new Date(now.getFullYear(), 11, 31).toISOString().split('T')[0];
            }
            start.form.submit();
        }
    </script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @endpush
</x-app-layout>
