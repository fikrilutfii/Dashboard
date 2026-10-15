<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-[11px] font-black text-teal-800/60 uppercase tracking-[0.2em] block mb-1">Manajemen Piutang</span>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">
                    Tagihan & Piutang Perusahaan
                </h2>
            </div>
            <div class="flex items-center gap-2.5">
                <a href="{{ route('reports.billing') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/70 border border-slate-200/80 text-slate-700 text-xs font-bold hover:bg-white shadow-sm active:scale-95 transition-all">
                    Laporan Tagihan Klien
                </a>
                <a href="{{ route('company-receivables.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-teal-700 hover:bg-teal-800 text-white text-xs font-bold shadow-sm shadow-teal-900/15 active:scale-95 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Tagihan
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

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="glass-card glass-card-hover rounded-[22px] p-5 relative overflow-hidden">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[11px] font-black uppercase tracking-wider text-slate-400">Sisa Tagihan (Piutang)</span>
                    <div class="w-8 h-8 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <h3 class="text-2xl font-black text-amber-700 font-mono tracking-tight">
                    Rp {{ number_format($totalBelumLunas, 0, ',', '.') }}
                </h3>
                <p class="text-xs text-slate-400 mt-2 font-medium">Total dana piutang yang belum diterima</p>
            </div>

            <div class="glass-card glass-card-hover rounded-[22px] p-5 relative overflow-hidden">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[11px] font-black uppercase tracking-wider text-slate-400">Tagihan Bulan Ini</span>
                    <div class="w-8 h-8 rounded-xl bg-blue-500/10 text-blue-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                </div>
                <h3 class="text-2xl font-black text-blue-700 font-mono tracking-tight">
                    Rp {{ number_format($totalAngsuranBulanIni, 0, ',', '.') }}
                </h3>
                <p class="text-xs text-slate-400 mt-2 font-medium">Target penerimaan piutang periode bulan ini</p>
            </div>

            <div class="glass-card glass-card-hover rounded-[22px] p-5 relative overflow-hidden">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[11px] font-black uppercase tracking-wider text-slate-400">Total Sudah Diterima</span>
                    <div class="w-8 h-8 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <h3 class="text-2xl font-black text-emerald-600 font-mono tracking-tight">
                    Rp {{ number_format($totalLunas, 0, ',', '.') }}
                </h3>
                <p class="text-xs text-slate-400 mt-2 font-medium">Akumulasi piutang yang telah terlunasi</p>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="glass-card rounded-[24px] p-5 border border-white/70 shadow-sm">
            <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
                <div class="lg:col-span-2">
                    <label class="block text-[10px] font-black text-slate-400 mb-1 uppercase tracking-wider">Cari Nama / Keterangan</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama debitur, keterangan..."
                        class="w-full bg-white/70 border border-slate-200/80 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-teal-500">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 mb-1 uppercase tracking-wider">Status</label>
                    <select name="status" class="w-full bg-white/70 border border-slate-200/80 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-teal-500">
                        <option value="">Semua Status</option>
                        <option value="belum_lunas" {{ request('status') == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                        <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 mb-1 uppercase tracking-wider">Jenis</label>
                    <select name="type" class="w-full bg-white/70 border border-slate-200/80 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-teal-500">
                        <option value="">Semua Jenis</option>
                        <option value="cash" {{ request('type') == 'cash' ? 'selected' : '' }}>Tunai</option>
                        <option value="installment" {{ request('type') == 'installment' ? 'selected' : '' }}>Cicilan</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-teal-700 hover:bg-teal-800 text-white rounded-xl text-xs font-bold shadow-sm shadow-teal-900/10 active:scale-95 transition-all">
                        Terapkan
                    </button>
                    <a href="{{ route('company-receivables.index') }}" class="px-3 py-2 text-xs font-semibold text-slate-500 hover:text-slate-800">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Table Card -->
        <div class="glass-card rounded-[26px] overflow-hidden border border-white/70 shadow-sm">
            <div class="bg-white/40 px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider">Daftar Tagihan & Piutang Berjalan</h4>
                <span class="text-[11px] font-semibold text-slate-400">Total: {{ number_format($receivables->total(), 0, ',', '.') }} catatan</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-200/60 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                            <th class="px-5 py-3.5">Debitur / Nama</th>
                            <th class="px-5 py-3.5">Jenis</th>
                            <th class="px-5 py-3.5 text-right">Total Tagihan</th>
                            <th class="px-5 py-3.5 text-right">Sisa Tagihan</th>
                            <th class="px-5 py-3.5 text-center">Status</th>
                            <th class="px-5 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                        @forelse($receivables as $receivable)
                            <tr class="hover:bg-teal-50/20 transition-colors">
                                <td class="px-5 py-3.5">
                                    <div class="font-bold text-slate-800">{{ $receivable->name }}</div>
                                    @if($receivable->description)
                                        <div class="text-[10px] text-slate-400 mt-0.5 line-clamp-1">{{ $receivable->description }}</div>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black uppercase {{ $receivable->type == 'cash' ? 'bg-blue-50 text-blue-700 border border-blue-200/60' : 'bg-amber-50 text-amber-700 border border-amber-200/60' }}">
                                        {{ $receivable->type_label }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-right font-mono font-bold text-slate-800 whitespace-nowrap">
                                    Rp {{ number_format($receivable->total_amount, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                    <div class="font-mono font-bold text-xs {{ $receivable->remaining_amount > 0 ? 'text-rose-600' : 'text-emerald-700' }}">
                                        Rp {{ number_format($receivable->remaining_amount, 0, ',', '.') }}
                                    </div>
                                    @if($receivable->type === 'installment' && $receivable->monthly_amount > 0 && $receivable->status !== 'lunas')
                                        <div class="mt-1">
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                Cicilan: Rp {{ number_format($receivable->monthly_amount, 0, ',', '.') }}/bln
                                            </span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-center whitespace-nowrap">
                                    @if($receivable->status == 'paid')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200/60 uppercase">
                                            Lunas
                                        </span>
                                    @elseif($receivable->status == 'sebagian')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-amber-50 text-amber-700 border border-amber-200/60 uppercase">
                                            Sebagian
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-rose-50 text-rose-700 border border-rose-200/60 uppercase">
                                            Belum Lunas
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1.5 flex-wrap justify-center">
                                        @if($receivable->status != 'lunas')
                                            <!-- Quick Payment Popover -->
                                            <div x-data="{ open: false }" class="relative">
                                                <button @click="open = !open"
                                                    class="px-2.5 py-1 rounded-lg bg-teal-700 hover:bg-teal-800 text-white font-bold text-[10px] shadow-sm active:scale-95 transition-all">
                                                    Bayar
                                                </button>
                                                <div x-show="open" @click.away="open = false" x-cloak
                                                    class="absolute right-0 top-8 z-50 glass-card bg-white/95 backdrop-blur-xl shadow-2xl border border-white/80 rounded-2xl p-4 w-60 text-left">
                                                    <form action="{{ route('company-receivables.record-payment', $receivable) }}" method="POST"
                                                        class="action-confirm space-y-2.5"
                                                        data-title="Catat Pembayaran Tagihan"
                                                        data-text="Catat pembayaran tagihan untuk {{ $receivable->name }}?"
                                                        data-icon="question"
                                                        data-confirm-text="Ya, Simpan"
                                                        data-confirm-color="#0f766e">
                                                        @csrf
                                                        <div class="text-[11px] font-bold text-slate-500">
                                                            Sisa: <span class="font-mono text-rose-600">Rp {{ number_format($receivable->remaining_amount, 0, ',', '.') }}</span>
                                                        </div>
                                                        <input type="number" name="payment_amount" max="{{ $receivable->remaining_amount }}" min="1"
                                                            class="w-full border border-slate-200 rounded-xl px-2.5 py-1.5 text-xs font-mono font-bold" placeholder="Nominal bayar" required>
                                                        <button type="submit" class="w-full py-1.5 bg-teal-700 hover:bg-teal-800 text-white text-xs font-bold rounded-xl transition-all">
                                                            Simpan
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>

                                            <form action="{{ route('company-receivables.mark-lunas', $receivable) }}" method="POST" class="action-confirm inline-block"
                                                data-title="Konfirmasi Pelunasan Tagihan"
                                                data-text="Tandai tagihan {{ $receivable->name }} sebesar Rp {{ number_format($receivable->remaining_amount, 0, ',', '.') }} sebagai LUNAS?"
                                                data-icon="question"
                                                data-confirm-text="Ya, Tandai Lunas"
                                                data-confirm-color="#0f766e">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 font-bold text-[10px] transition-all">
                                                    Tandai Lunas
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('company-receivables.revert-lunas', $receivable) }}" method="POST" class="action-confirm inline-block"
                                                data-title="Batalkan Status Lunas?"
                                                data-text="Kembalikan tagihan {{ $receivable->name }} ke Belum Lunas dan batalkan mutasi kas terkait?"
                                                data-icon="warning"
                                                data-confirm-text="Ya, Batalkan Lunas"
                                                data-confirm-color="#f59e0b">
                                                @csrf
                                                <button type="submit" class="px-2 py-1 rounded-lg bg-amber-50 text-amber-800 border border-amber-200 hover:bg-amber-100 font-bold text-[10px] transition-all">
                                                    Batal Lunas
                                                </button>
                                            </form>
                                        @endif

                                        <a href="{{ route('company-receivables.edit', $receivable) }}" class="px-2 py-1 bg-white/70 border border-slate-200 text-slate-700 hover:bg-white text-[10px] font-bold rounded-lg transition-all">
                                            Edit
                                        </a>

                                        @if(Auth::user()->isAdmin())
                                            <form action="{{ route('company-receivables.destroy', $receivable) }}" method="POST" class="action-confirm inline-block"
                                                data-title="Hapus Tagihan Ini?"
                                                data-text="Hapus data piutang ini secara permanen?"
                                                data-icon="warning"
                                                data-confirm-text="Ya, Hapus"
                                                data-confirm-color="#ef4444">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-1 rounded-lg text-rose-500 hover:bg-rose-50 transition-all" title="Hapus">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
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
                                        Belum ada data tagihan atau piutang.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($receivables->hasPages())
                <div class="px-6 py-4 bg-white/40 border-t border-slate-100">
                    {{ $receivables->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
