<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <span class="text-[11px] font-black text-teal-800/60 uppercase tracking-[0.2em] block mb-1">Manajemen Penjualan</span>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">
                    {{ session('division') == 'konfeksi' ? 'Daftar Penjualan Barang' : 'Daftar Invoice' }}
                </h2>
            </div>
            <div class="flex items-center gap-2.5">
                <a href="{{ route('reports.billing') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 text-white text-xs font-bold shadow-sm shadow-indigo-600/20 hover:bg-indigo-700 active:scale-95 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Tagihan per Klien
                </a>
                <a href="{{ route('invoices.printReport', request()->only(['search','status','date_filter','division'])) }}" target="_blank"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-700 text-white text-xs font-bold shadow-sm hover:bg-slate-800 active:scale-95 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Cetak Laporan
                </a>
                <a href="{{ route('invoices.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-teal-600 text-white text-xs font-bold shadow-sm shadow-teal-600/20 hover:bg-teal-700 active:scale-95 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    + Buat Invoice
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

        <!-- Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Penjualan -->
            <div class="glass-card glass-card-hover rounded-[22px] p-5 relative overflow-hidden">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[11px] font-black uppercase tracking-wider text-slate-400">Total Penjualan</span>
                    <div class="w-8 h-8 rounded-xl bg-blue-500/10 text-blue-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-800 tracking-tight font-mono">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</div>
                <div class="mt-2.5 text-xs font-semibold text-slate-400 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                    {{ $countLunas + $countBelumLunas }} faktur tercatat
                </div>
            </div>

            <!-- Total Pelunasan -->
            <div class="glass-card glass-card-hover rounded-[22px] p-5 relative overflow-hidden">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[11px] font-black uppercase tracking-wider text-slate-400">Total Pelunasan</span>
                    <div class="w-8 h-8 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-emerald-700 tracking-tight font-mono">Rp {{ number_format($totalPelunasan, 0, ',', '.') }}</div>
                <div class="mt-2.5 text-xs font-semibold text-slate-400 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    {{ $countLunas }} faktur lunas
                </div>
            </div>

            <!-- Sisa Tagihan -->
            <div class="glass-card glass-card-hover rounded-[22px] p-5 relative overflow-hidden">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[11px] font-black uppercase tracking-wider text-slate-400">Sisa Tagihan</span>
                    <div class="w-8 h-8 rounded-xl bg-rose-500/10 text-rose-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-rose-600 tracking-tight font-mono">Rp {{ number_format($totalSisaTagihan, 0, ',', '.') }}</div>
                <div class="mt-2.5 text-xs font-semibold text-slate-400 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                    {{ $countBelumLunas }} belum lunas
                </div>
            </div>

            <!-- % Terkumpul -->
            <div class="glass-card glass-card-hover rounded-[22px] p-5 relative overflow-hidden">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[11px] font-black uppercase tracking-wider text-slate-400">% Terkumpul</span>
                    <div class="w-8 h-8 rounded-xl bg-purple-500/10 text-purple-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-purple-700 tracking-tight font-mono">
                    {{ $totalPenjualan > 0 ? number_format(($totalPelunasan / $totalPenjualan) * 100, 1) : 0 }}%
                </div>
                <div class="mt-2.5 text-xs font-semibold text-slate-400 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                    dari total tagihan penjualan
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="glass-card rounded-[24px] p-5 border border-white/70 shadow-sm">
            <form action="{{ route('invoices.index') }}" method="GET" class="flex flex-wrap gap-3 items-end">
                <!-- Search -->
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-[11px] font-black text-slate-500 uppercase tracking-wider mb-1.5">Pencarian</label>
                    <input type="text" name="search" placeholder="No Invoice / Customer / Nama Barang" value="{{ request('search') }}"
                        class="w-full bg-white/70 border border-slate-200/80 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-teal-500 focus:bg-white focus:ring-2 focus:ring-teal-500/20 transition-all">
                </div>

                <!-- Status -->
                <div class="w-36">
                    <label class="block text-[11px] font-black text-slate-500 uppercase tracking-wider mb-1.5">Status</label>
                    <select name="status" class="w-full bg-white/70 border border-slate-200/80 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-teal-500 focus:bg-white focus:ring-2 focus:ring-teal-500/20 transition-all">
                        <option value="">Semua Status</option>
                        <option value="belum_lunas" {{ request('status') == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                        <option value="sebagian" {{ request('status') == 'sebagian' ? 'selected' : '' }}>Sebagian</option>
                        <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                    </select>
                </div>

                <!-- Date Filter By -->
                <div class="w-36">
                    <label class="block text-[11px] font-black text-slate-500 uppercase tracking-wider mb-1.5">Berdasarkan</label>
                    <select name="filter_date_by" class="w-full bg-white/70 border border-slate-200/80 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-teal-500 focus:bg-white focus:ring-2 focus:ring-teal-500/20 transition-all">
                        <option value="invoice_date" {{ request('filter_date_by', 'invoice_date') == 'invoice_date' ? 'selected' : '' }}>Tgl Faktur</option>
                        <option value="payment_date" {{ request('filter_date_by') == 'payment_date' ? 'selected' : '' }}>Tgl Pelunasan</option>
                    </select>
                </div>

                <!-- Start Date -->
                <div class="w-36">
                    <label class="block text-[11px] font-black text-slate-500 uppercase tracking-wider mb-1.5">Dari</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                        class="w-full bg-white/70 border border-slate-200/80 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-teal-500 focus:bg-white focus:ring-2 focus:ring-teal-500/20 transition-all">
                </div>

                <!-- End Date -->
                <div class="w-36">
                    <label class="block text-[11px] font-black text-slate-500 uppercase tracking-wider mb-1.5">Sampai</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                        class="w-full bg-white/70 border border-slate-200/80 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-teal-500 focus:bg-white focus:ring-2 focus:ring-teal-500/20 transition-all">
                </div>

                <button type="submit" class="px-5 py-2 rounded-xl bg-teal-700 hover:bg-teal-800 text-white text-xs font-bold shadow-sm shadow-teal-900/10 active:scale-95 transition-all">
                    Saring
                </button>
                @if(request()->anyFilled(['search', 'status', 'start_date', 'end_date', 'filter_date_by', 'date_filter']))
                    <a href="{{ route('invoices.index') }}" class="px-3 py-2 rounded-xl text-slate-500 hover:text-slate-800 text-xs font-semibold hover:bg-slate-100/60 transition-all">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Table Card -->
        <div class="glass-card rounded-[26px] overflow-hidden border border-white/70 shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-200/60 text-[11px] font-black uppercase tracking-wider text-slate-500">
                            <th class="px-4 py-3.5">No Invoice</th>
                            <th class="px-4 py-3.5">Tanggal</th>
                            <th class="px-4 py-3.5">Customer</th>
                            <th class="px-4 py-3.5 text-center">Metode</th>
                            <th class="px-4 py-3.5 text-right">Total</th>
                            <th class="px-4 py-3.5 text-right">Dibayar</th>
                            <th class="px-4 py-3.5 text-right">Sisa</th>
                            <th class="px-4 py-3.5 text-center">Status</th>
                            <th class="px-4 py-3.5 text-center">Cetak</th>
                            <th class="px-4 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100/80 text-xs text-slate-700">
                        @forelse($invoices as $invoice)
                            <tr class="hover:bg-teal-50/20 transition-colors">
                                <td class="px-4 py-3 font-mono font-bold text-slate-800">
                                    {{ $invoice->faktur_number }}
                                    @if($invoice->invoice_number)
                                        <div class="text-[10px] font-normal text-slate-400 font-sans">OP: {{ $invoice->invoice_number }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-slate-600 font-medium">
                                    {{ $invoice->invoice_date->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-3 font-semibold text-slate-800">
                                    {{ $invoice->customer->name ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($invoice->payment_method === 'credit')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black bg-amber-50 text-amber-700 border border-amber-200/60 uppercase">Credit</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black bg-blue-50 text-blue-700 border border-blue-200/60 uppercase">Cash</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right font-mono font-bold text-slate-800 whitespace-nowrap">
                                    Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-right font-mono font-bold text-emerald-700 whitespace-nowrap">
                                    Rp {{ number_format($invoice->paid_amount ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-right font-mono font-bold text-rose-600 whitespace-nowrap">
                                    Rp {{ number_format(max(0, $invoice->total_amount - ($invoice->paid_amount ?? 0)), 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($invoice->status == 'lunas')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200/60 uppercase">Lunas</span>
                                    @elseif($invoice->status == 'sebagian')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-amber-50 text-amber-700 border border-amber-200/60 uppercase">Sebagian</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-rose-50 text-rose-700 border border-rose-200/60 uppercase">Belum</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($invoice->is_printed)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200/60">Sudah</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 border border-slate-200/60">Belum</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1.5 text-xs font-semibold">
                                        <a href="{{ route('invoices.show', $invoice) }}" class="text-teal-700 hover:text-teal-900 hover:underline">Lihat</a>
                                        <span class="text-slate-300">|</span>
                                        <a href="{{ route('invoices.edit', $invoice) }}" class="text-amber-700 hover:text-amber-900 hover:underline">Edit</a>
                                        <span class="text-slate-300">|</span>
                                        <a href="javascript:void(0)" onclick="confirmPrint('{{ route('invoices.print', $invoice) }}')" class="text-slate-600 hover:text-slate-900 hover:underline">Print SJ</a>
                                        <span class="text-slate-300">|</span>
                                        <a href="javascript:void(0)" onclick="confirmPrint('{{ route('invoices.print-combined', $invoice) }}')" class="text-blue-600 hover:text-blue-800 hover:underline">Gabung</a>
                                        <span class="text-slate-300">|</span>
                                        <a href="javascript:void(0)" onclick="confirmPrint('{{ route('invoices.export', $invoice) }}', true)" class="text-emerald-700 hover:text-emerald-900 hover:underline font-bold">Excel</a>
                                        <span class="text-slate-300">|</span>
                                        <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="action-confirm inline-block"
                                            data-title="Hapus Faktur Ini?"
                                            data-text="Apakah Anda yakin ingin menghapus faktur ini? Stok barang akan dikembalikan ke gudang."
                                            data-icon="warning"
                                            data-confirm-text="Ya, Hapus Faktur"
                                            data-confirm-color="#ef4444">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-rose-600 hover:text-rose-800 hover:underline">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-6 py-12 text-center text-slate-400 font-medium">
                                    <div class="max-w-xs mx-auto">
                                        <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        Tidak ada faktur invoice ditemukan.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($invoices->hasPages())
                <div class="px-6 py-4 bg-white/40 border-t border-slate-100">
                    {{ $invoices->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
