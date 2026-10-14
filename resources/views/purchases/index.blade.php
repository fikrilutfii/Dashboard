<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-[11px] font-black text-teal-800/60 uppercase tracking-[0.2em] block mb-1">Pengadaan & Bahan</span>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">
                    Pembelian Bahan
                    @if(request('division'))
                        <span class="text-teal-700 font-sans font-bold text-lg">({{ ucfirst(request('division')) }})</span>
                    @endif
                </h2>
            </div>
            <div>
                <a href="{{ route('purchases.create', ['division' => request('division')]) }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-teal-700 hover:bg-teal-800 text-white text-xs font-bold shadow-sm shadow-teal-900/15 active:scale-95 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    + Beli Bahan
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

        <!-- Filter Card -->
        <div class="glass-card rounded-[24px] p-5 border border-white/70 shadow-sm">
            <form method="GET" action="{{ route('purchases.index') }}" class="flex flex-wrap items-end gap-3">
                <input type="hidden" name="division" value="{{ request('division') }}">
                <div class="flex-1 min-w-[220px]">
                    <label class="block text-[10px] font-black text-slate-400 mb-1 uppercase tracking-wider">Pencarian</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No. Purchase / Supplier..."
                        class="w-full bg-white/70 border border-slate-200/80 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-teal-500 focus:bg-white focus:ring-2 focus:ring-teal-500/20 transition-all">
                </div>
                <div class="w-36">
                    <label class="block text-[10px] font-black text-slate-400 mb-1 uppercase tracking-wider">Dari Tanggal</label>
                    <input type="date" name="date_start" value="{{ request('date_start') }}"
                        class="w-full bg-white/70 border border-slate-200/80 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-teal-500">
                </div>
                <div class="w-36">
                    <label class="block text-[10px] font-black text-slate-400 mb-1 uppercase tracking-wider">Sampai Tanggal</label>
                    <input type="date" name="date_end" value="{{ request('date_end') }}"
                        class="w-full bg-white/70 border border-slate-200/80 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-teal-500">
                </div>
                <button type="submit" class="px-5 py-2 bg-teal-700 hover:bg-teal-800 text-white rounded-xl text-xs font-bold shadow-sm shadow-teal-900/10 active:scale-95 transition-all">
                    Saring
                </button>
                @if(request()->anyFilled(['search', 'date_start', 'date_end']))
                    <a href="{{ route('purchases.index', ['division' => request('division')]) }}" class="px-3 py-2 text-xs font-semibold text-slate-500 hover:text-slate-800">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Table Card -->
        <div class="glass-card rounded-[26px] overflow-hidden border border-white/70 shadow-sm">
            <div class="bg-white/40 px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider">Riwayat Pengadaan Bahan</h4>
                <span class="text-[11px] font-semibold text-slate-400">Total: {{ number_format($purchases->total(), 0, ',', '.') }} faktur</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-200/60 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                            <th class="px-5 py-3.5">No. Purchase</th>
                            <th class="px-5 py-3.5">Supplier</th>
                            <th class="px-5 py-3.5">Tanggal</th>
                            <th class="px-5 py-3.5 text-center">Status</th>
                            <th class="px-5 py-3.5 text-right">Total</th>
                            <th class="px-5 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                        @forelse($purchases as $purchase)
                            <tr class="hover:bg-teal-50/20 transition-colors">
                                <td class="px-5 py-3.5 font-mono font-bold text-slate-800 whitespace-nowrap">
                                    {{ $purchase->purchase_number }}
                                    <div class="text-[10px] font-semibold text-teal-700 uppercase tracking-wider font-sans mt-0.5">{{ $purchase->division }}</div>
                                </td>
                                <td class="px-5 py-3.5 font-semibold text-slate-800">
                                    {{ $purchase->supplier->name ?? 'Non-Supplier (Cash)' }}
                                </td>
                                <td class="px-5 py-3.5 text-slate-500 whitespace-nowrap">
                                    {{ $purchase->date->format('d/m/Y') }}
                                </td>
                                <td class="px-5 py-3.5 text-center whitespace-nowrap">
                                    @if($purchase->status == 'lunas')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200/60 uppercase">
                                            Lunas
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-rose-50 text-rose-700 border border-rose-200/60 uppercase">
                                            Belum Lunas
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-right font-mono font-bold text-slate-800 whitespace-nowrap">
                                    Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-3.5 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1.5">
                                        <a href="{{ route('purchases.show', $purchase) }}"
                                            class="px-2.5 py-1 rounded-lg bg-white/70 border border-slate-200 text-teal-700 hover:bg-white font-bold text-[10px] transition-all">
                                            Detail
                                        </a>
                                        <a href="{{ route('purchases.edit', $purchase) }}"
                                            class="px-2.5 py-1 rounded-lg bg-white/70 border border-slate-200 text-amber-700 hover:bg-white font-bold text-[10px] transition-all">
                                            Edit
                                        </a>
                                        @if($purchase->status == 'belum_lunas')
                                            <a href="{{ route('purchases.show', $purchase) }}"
                                                class="px-2.5 py-1 rounded-lg bg-teal-700 hover:bg-teal-800 text-white font-bold text-[10px] shadow-sm active:scale-95 transition-all">
                                                Bayar
                                            </a>
                                        @else
                                            <form action="{{ route('purchases.revert-status', $purchase) }}" method="POST" class="action-confirm inline-block"
                                                data-title="Batalkan Status Lunas?"
                                                data-text="Kembalikan status pembelian ini ke Belum Lunas dan batalkan mutasi kas terkait?"
                                                data-icon="warning"
                                                data-confirm-text="Ya, Batalkan Lunas"
                                                data-confirm-color="#f59e0b">
                                                @csrf
                                                <button type="submit" class="px-2 py-1 rounded-lg bg-amber-50 text-amber-800 border border-amber-200 text-[10px] font-bold hover:bg-amber-100 transition-all">
                                                    Batal Lunas
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
                                        <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                        Belum ada data pembelian bahan.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($purchases->hasPages())
                <div class="px-6 py-4 bg-white/40 border-t border-slate-100">
                    {{ $purchases->appends(request()->query())->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
