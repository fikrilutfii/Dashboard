<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-[11px] font-black text-teal-800/60 uppercase tracking-[0.2em] block mb-1">Manajemen Biaya</span>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">
                    Pengeluaran & Belanja Operasional
                </h2>
            </div>
            <div class="flex items-center gap-2.5">
                <a href="{{ route('expenses.create', ['type' => 'manual']) }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/70 border border-slate-200/80 text-slate-700 text-xs font-bold hover:bg-white shadow-sm active:scale-95 transition-all">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    + Biaya Operasional
                </a>
                <a href="{{ route('expenses.create', ['type' => 'bahan']) }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-teal-700 hover:bg-teal-800 text-white text-xs font-bold shadow-sm shadow-teal-900/15 active:scale-95 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    + Belanja Bahan
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

        <!-- Metric Card -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="glass-card glass-card-hover rounded-[22px] p-5 relative overflow-hidden">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[11px] font-black uppercase tracking-wider text-slate-400">Total Pengeluaran (Bulan Ini)</span>
                    <div class="w-8 h-8 rounded-xl bg-rose-500/10 text-rose-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-rose-600 tracking-tight font-mono">
                    Rp {{ number_format($totalExpenses, 0, ',', '.') }}
                </div>
                <div class="mt-2 text-xs font-semibold text-slate-400 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                    Akumulasi biaya dan belanja operasional bulan berjalan
                </div>
            </div>

            <div class="glass-card glass-card-hover rounded-[22px] p-5 relative overflow-hidden flex flex-col justify-between">
                <div>
                    <span class="text-[11px] font-black uppercase tracking-wider text-slate-400 block mb-1">Informasi Filter</span>
                    <p class="text-xs text-slate-600">
                        Gunakan filter tanggal dan jenis belanja di bawah untuk menyaring pengeluaran per periode atau kategori spesifik.
                    </p>
                </div>
                <div class="text-xs font-semibold text-teal-700 flex items-center gap-1.5 mt-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-teal-500"></span>
                    Sistem Mini ERP Abadi Sentosa
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="glass-card rounded-[24px] p-5 border border-white/70 shadow-sm">
            <form method="GET" action="{{ route('expenses.index') }}" class="flex flex-wrap items-end gap-3">
                <div class="w-44">
                    <label class="block text-[10px] font-black text-slate-400 mb-1 uppercase tracking-wider">Jenis Pengeluaran</label>
                    <select name="type" class="w-full bg-white/70 border border-slate-200/80 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-teal-500">
                        <option value="">Semua Jenis</option>
                        <option value="manual" {{ request('type') == 'manual' ? 'selected' : '' }}>Operasional (Manual)</option>
                        <option value="bahan" {{ request('type') == 'bahan' ? 'selected' : '' }}>Belanja Bahan</option>
                    </select>
                </div>

                <div class="flex-1 min-w-[200px]">
                    <label class="block text-[10px] font-black text-slate-400 mb-1 uppercase tracking-wider">Kategori / Keterangan</label>
                    <input type="text" name="category" value="{{ request('category') }}" placeholder="Cari ket / kategori..."
                        class="w-full bg-white/70 border border-slate-200/80 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-teal-500">
                </div>

                <div class="w-36">
                    <label class="block text-[10px] font-black text-slate-400 mb-1 uppercase tracking-wider">Dari Tanggal</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                        class="w-full bg-white/70 border border-slate-200/80 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-teal-500">
                </div>

                <div class="w-36">
                    <label class="block text-[10px] font-black text-slate-400 mb-1 uppercase tracking-wider">Sampai Tanggal</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                        class="w-full bg-white/70 border border-slate-200/80 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-teal-500">
                </div>

                <button type="submit" class="px-5 py-2 bg-teal-700 hover:bg-teal-800 text-white rounded-xl text-xs font-bold shadow-sm shadow-teal-900/10 active:scale-95 transition-all">
                    Terapkan
                </button>
                @if(request()->anyFilled(['type', 'category', 'date_from', 'date_to']))
                    <a href="{{ route('expenses.index') }}" class="px-3 py-2 text-xs font-semibold text-slate-500 hover:text-slate-800">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Table Card -->
        <div class="glass-card rounded-[26px] overflow-hidden border border-white/70 shadow-sm">
            <div class="bg-white/40 px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider">Daftar Biaya & Pembelian Bahan</h4>
                <span class="text-[11px] font-semibold text-slate-400">Total: {{ number_format($expenses->total(), 0, ',', '.') }} pengeluaran</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-200/60 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                            <th class="px-5 py-3.5">Tanggal</th>
                            <th class="px-5 py-3.5">Jenis & Detail</th>
                            <th class="px-5 py-3.5">Kategori / Entitas</th>
                            <th class="px-5 py-3.5 text-center">Metode</th>
                            <th class="px-5 py-3.5 text-right">Nominal</th>
                            <th class="px-5 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                        @forelse($expenses as $expense)
                            <tr class="hover:bg-teal-50/20 transition-colors">
                                <td class="px-5 py-3.5 font-mono text-slate-500 whitespace-nowrap">
                                    {{ $expense->date->format('d/m/Y') }}
                                </td>
                                <td class="px-5 py-3.5">
                                    @if($expense->type === 'bahan')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-black bg-indigo-50 text-indigo-700 border border-indigo-200/60 uppercase mb-1">
                                            Belanja Bahan
                                        </span>
                                        <div class="font-bold text-slate-800">{{ $expense->item_name }}</div>
                                        <div class="text-[10px] text-slate-400">Supplier: <span class="text-slate-700 font-semibold">{{ $expense->supplier_name }}</span></div>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-black bg-slate-100 text-slate-600 border border-slate-200/60 uppercase mb-1">
                                            Operasional
                                        </span>
                                        <div class="font-bold text-slate-800">{{ $expense->category }}</div>
                                        @if($expense->description)
                                            <div class="text-[10px] text-slate-400 line-clamp-1">{{ $expense->description }}</div>
                                        @endif
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black uppercase bg-teal-50 text-teal-700 border border-teal-200/60">
                                        {{ $expense->entity ?? $expense->division }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-center whitespace-nowrap">
                                    @if($expense->payment_method === 'credit')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black bg-amber-50 text-amber-700 border border-amber-200/60 uppercase">CREDIT</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200/60 uppercase">CASH</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-right font-mono font-bold text-rose-600 whitespace-nowrap">
                                    Rp {{ number_format($expense->amount, 0, ',', '.') }}
                                    @if($expense->type === 'bahan' && $expense->quantity > 1)
                                        <div class="text-[9px] text-slate-400 font-normal">{{ $expense->quantity }} × Rp {{ number_format($expense->unit_price, 0, ',', '.') }}</div>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('expenses.edit', $expense) }}"
                                            class="p-1 rounded-lg text-teal-700 hover:bg-teal-50 transition-all" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        @if(Auth::user()->isAdmin())
                                            <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="action-confirm inline-block"
                                                data-title="Hapus Pengeluaran Ini?"
                                                data-text="Hapus data pengeluaran ini secara permanen? Data kas yang terkait akan ikut disesuaikan."
                                                data-icon="warning"
                                                data-confirm-text="Ya, Hapus"
                                                data-confirm-color="#ef4444">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-1 rounded-lg text-rose-500 hover:bg-rose-50 transition-all" title="Hapus">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400 font-medium">
                                    <div class="max-w-xs mx-auto">
                                        <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        Tidak ada data pengeluaran ditemukan.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($expenses->hasPages())
                <div class="px-6 py-4 bg-white/40 border-t border-slate-100">
                    {{ $expenses->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
