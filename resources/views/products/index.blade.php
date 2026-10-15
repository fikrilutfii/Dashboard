<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-[11px] font-black text-teal-800/60 uppercase tracking-[0.2em] block mb-1">Master Data</span>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">
                    Daftar Barang & Produk
                </h2>
            </div>
            <div class="flex items-center gap-2.5">
                <form action="{{ route('products.import-csv') }}" method="POST" class="action-confirm inline-block"
                    data-title="Import CSV Master Data"
                    data-text="Import data dari file 'data master.csv' di root project? Data lama dengan kode sama akan diperbarui."
                    data-icon="question"
                    data-confirm-text="Ya, Import"
                    data-confirm-color="#0f766e">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/70 border border-slate-200/80 text-slate-700 text-xs font-bold hover:bg-white shadow-sm active:scale-95 transition-all">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Import Master CSV
                    </button>
                </form>
                <a href="{{ route('products.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-teal-700 hover:bg-teal-800 text-white text-xs font-bold shadow-sm shadow-teal-900/15 active:scale-95 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Barang
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

        <!-- Filter & Search Card -->
        <div class="glass-card rounded-[24px] p-5 border border-white/70 shadow-sm">
            <form action="{{ route('products.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                <div class="flex-1 min-w-[260px]">
                    <div class="relative">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" name="search" placeholder="Cari Kode atau Nama Barang..." value="{{ request('search') }}"
                            class="w-full pl-10 pr-4 py-2.5 text-xs text-slate-700 bg-white/70 border border-slate-200/80 rounded-xl focus:outline-none focus:border-teal-500 focus:bg-white focus:ring-2 focus:ring-teal-500/20 transition-all">
                    </div>
                </div>
                <button type="submit" class="px-5 py-2.5 bg-teal-700 hover:bg-teal-800 text-white rounded-xl text-xs font-bold shadow-sm shadow-teal-900/10 active:scale-95 transition-all">
                    Cari Barang
                </button>
                @if(request('search'))
                    <a href="{{ route('products.index') }}" class="px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-500 hover:text-slate-800 hover:bg-white/60 transition-all">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Table Card -->
        <div class="glass-card rounded-[26px] overflow-hidden border border-white/70 shadow-sm">
            <div class="bg-white/40 px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider">Katalog Produk & Stok</h4>
                <span class="text-[11px] font-semibold text-slate-400">Total: {{ number_format($products->total(), 0, ',', '.') }} item</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-200/60 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                            <th class="px-5 py-3.5">Kode</th>
                            <th class="px-5 py-3.5">Nama Barang</th>
                            <th class="px-5 py-3.5 text-center">Satuan</th>
                            <th class="px-5 py-3.5 text-right">Harga</th>
                            <th class="px-5 py-3.5 text-center">Stok</th>
                            <th class="px-5 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                        @forelse($products as $product)
                            <tr class="hover:bg-teal-50/20 transition-colors">
                                <td class="px-5 py-3 font-mono font-bold text-slate-800 whitespace-nowrap">
                                    {{ $product->code }}
                                </td>
                                <td class="px-5 py-3 font-semibold text-slate-800">
                                    {{ $product->name }}
                                </td>
                                <td class="px-5 py-3 text-center text-slate-500 font-medium">
                                    {{ $product->unit ?? 'pcs' }}
                                </td>
                                <td class="px-5 py-3 text-right font-mono font-bold text-slate-800 whitespace-nowrap">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-3 text-center whitespace-nowrap">
                                    @if($product->stock <= 5)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-rose-50 text-rose-700 border border-rose-200/60 font-mono">
                                            {{ number_format($product->stock, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200/60 font-mono">
                                            {{ number_format($product->stock, 0, ',', '.') }}
                                        </span>
                                    @endif

                                    @if($product->shared_stock_code)
                                        <div class="mt-1">
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[9px] font-bold bg-blue-50 text-blue-700 border border-blue-200/60" title="Shared Stock Code">
                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                                {{ $product->shared_stock_code }}
                                            </span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center gap-2 text-xs font-semibold">
                                        <a href="{{ route('products.edit', $product) }}" class="text-teal-700 hover:text-teal-900 hover:underline">
                                            Edit
                                        </a>
                                        @if(Auth::user()->isAdmin())
                                            <span class="text-slate-300">|</span>
                                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline delete-confirm">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-rose-600 hover:text-rose-800 hover:underline">
                                                    Hapus
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
                                        <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                        Belum ada data barang atau produk ditemukan.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($products->hasPages())
                <div class="px-6 py-4 bg-white/40 border-t border-slate-100">
                    {{ $products->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
