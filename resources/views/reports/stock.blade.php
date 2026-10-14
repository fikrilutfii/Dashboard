<x-app-layout>
    <x-slot name="header">Dashboard Stok</x-slot>

    <div class="space-y-6 pb-12 print:py-0 print:m-0">
        <!-- Print Header (Only visible when printing) -->
        <div class="hidden print:block text-center mb-8">
            <h1 class="text-2xl font-bold uppercase">Laporan Stok Barang ({{ ucfirst(session('division', 'Semua Divisi')) }})</h1>
            <p class="text-gray-500">Tanggal Cetak: {{ date('d M Y H:i') }}</p>
            <hr class="mt-4 border-2 border-gray-800">
        </div>

        <!-- Page Header & Action Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 print:hidden">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-50 border border-teal-100 text-teal-800 text-xs font-semibold mb-2">
                    <span class="w-2 h-2 rounded-full bg-teal-500 animate-pulse"></span>
                    Inventaris & Manajemen Pergudangan
                </div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Dashboard Stok Barang</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Pantau ketersediaan produk, aset barang, peringatan stok menipis, dan riwayat pergerakan.</p>
            </div>
            <div class="flex items-center gap-2.5">
                <button onclick="window.print()" class="glass-pill px-4 py-2 text-xs font-bold text-slate-700 hover:text-slate-950 rounded-xl transition-all flex items-center gap-2 shadow-2xs">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-teal-700">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" />
                    </svg>
                    Cetak Laporan
                </button>
            </div>
        </div>

        <!-- Success/Error Alerts -->
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" class="p-4 rounded-2xl bg-emerald-50/90 border border-emerald-200/90 text-emerald-800 text-xs font-bold flex justify-between items-center shadow-sm print:hidden">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-emerald-700 hover:text-emerald-900">&times;</button>
            </div>
        @endif
        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" class="p-4 rounded-2xl bg-rose-50/90 border border-rose-200/90 text-rose-800 text-xs font-bold flex justify-between items-center shadow-sm print:hidden">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-rose-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button @click="show = false" class="text-rose-700 hover:text-rose-900">&times;</button>
            </div>
        @endif

        <!-- Metric Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 print:hidden">
            <!-- Card 1: Total Nilai Aset -->
            <div class="glass-card rounded-[22px] p-5 flex items-center gap-4 group hover:border-teal-400/50 transition-all">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-teal-600 to-emerald-800 flex items-center justify-center text-white shadow-md shadow-teal-700/20 group-hover:scale-105 transition-transform flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Total Nilai Aset</span>
                    <span class="text-xl sm:text-2xl font-black text-slate-800 leading-tight">Rp {{ number_format($totalAssetValue, 0, ',', '.') }}</span>
                    <span class="text-[10px] text-teal-700/80 font-semibold block mt-0.5">Berdasarkan Harga Jual & Stok</span>
                </div>
            </div>

            <!-- Card 2: Total Produk -->
            <div class="glass-card rounded-[22px] p-5 flex items-center gap-4 group hover:border-blue-400/50 transition-all">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-700 flex items-center justify-center text-white shadow-md shadow-blue-700/20 group-hover:scale-105 transition-transform flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                    </svg>
                </div>
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Total Produk</span>
                    <span class="text-xl sm:text-2xl font-black text-slate-800 leading-tight">{{ number_format($totalProducts) }} <span class="text-xs font-semibold text-slate-400">item</span></span>
                    <span class="text-[10px] text-slate-400 font-semibold block mt-0.5">Katalog data produk</span>
                </div>
            </div>

            <!-- Card 3: Stok Menipis -->
            <div class="glass-card rounded-[22px] p-5 flex items-center gap-4 group hover:border-amber-400/50 transition-all">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center text-white shadow-md shadow-amber-700/20 group-hover:scale-105 transition-transform flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Stok Menipis (≤ 5)</span>
                    <span class="text-xl sm:text-2xl font-black text-amber-700 leading-tight">{{ number_format($lowStockCount) }} <span class="text-xs font-semibold text-slate-400">item</span></span>
                    <span class="text-[10px] text-amber-600 font-semibold block mt-0.5">Perlu pengadaan ulang</span>
                </div>
            </div>

            <!-- Card 4: Stok Habis -->
            <div class="glass-card rounded-[22px] p-5 flex items-center gap-4 group hover:border-rose-400/50 transition-all">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-rose-500 to-red-700 flex items-center justify-center text-white shadow-md shadow-rose-700/20 group-hover:scale-105 transition-transform flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Stok Habis (0)</span>
                    <span class="text-xl sm:text-2xl font-black text-rose-700 leading-tight">{{ number_format($outOfStockCount) }} <span class="text-xs font-semibold text-slate-400">item</span></span>
                    <span class="text-[10px] text-rose-600 font-semibold block mt-0.5">Segera stok bahan</span>
                </div>
            </div>
        </div>

        <!-- Two Column Layout: Main Table & Sidebar -->
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Main Content: Stock Table -->
            <div class="flex-1 glass-card rounded-[26px] overflow-hidden shadow-sm">
                <!-- Search & Filters -->
                <div class="p-5 sm:p-6 border-b border-slate-100/90 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h2 class="text-base font-extrabold text-slate-800">Daftar Stok Produk</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Kelola kuantitas barang dan lakukan penyesuaian opname stok.</p>
                    </div>

                    <form action="{{ route('reports.stock') }}" method="GET" class="flex items-center gap-2 w-full sm:w-auto">
                        <div class="relative w-full sm:w-64">
                            <input type="text" name="search" placeholder="Cari Kode atau Nama Produk..." value="{{ request('search') }}"
                                   class="w-full text-xs bg-white/95 border border-slate-200/90 rounded-xl pl-9 pr-3 py-2 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all shadow-2xs">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-slate-400 absolute left-3 top-2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                        </div>
                        <button type="submit" class="bg-gradient-to-r from-teal-700 to-emerald-800 hover:from-teal-800 hover:to-emerald-900 text-white font-bold text-xs py-2 px-3.5 rounded-xl shadow-sm transition-all active:scale-95">
                            Cari
                        </button>
                        @if(request('search'))
                            <a href="{{ route('reports.stock') }}" class="glass-pill text-xs font-bold text-slate-600 hover:text-slate-900 py-2 px-3 rounded-xl transition-all shadow-2xs">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/70 border-b border-slate-200/70 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                <th class="p-4 pl-6">Barang</th>
                                <th class="p-4 text-center">Satuan</th>
                                <th class="p-4 text-right">Harga Jual</th>
                                <th class="p-4 text-center">Stok Terkini</th>
                                <th class="p-4 pr-6 text-center print:hidden">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100/90 text-xs">
                            @forelse($products as $product)
                                <tr class="hover:bg-teal-50/20 transition-colors {{ $product->stock <= 0 ? 'bg-rose-50/20' : ($product->stock <= 5 ? 'bg-amber-50/20' : '') }}">
                                    <!-- Barang & Kode -->
                                    <td class="p-4 pl-6">
                                        <div>
                                            <div class="font-extrabold text-slate-800 text-xs">{{ $product->name }}</div>
                                            <div class="text-[11px] text-slate-500 flex items-center gap-2 mt-1">
                                                <span class="bg-slate-100/90 border border-slate-200/60 px-2 py-0.5 rounded-md font-mono text-slate-700 font-semibold">{{ $product->code }}</span>
                                                @if($product->shared_stock_code)
                                                    <span class="text-[10px] text-teal-700 bg-teal-50 border border-teal-200/60 px-2 py-0.5 rounded-full font-bold flex items-center gap-1" title="Shared Stock">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                                        {{ $product->shared_stock_code }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Satuan -->
                                    <td class="p-4 text-center text-slate-600 font-semibold">
                                        <span class="px-2 py-1 rounded-md bg-slate-100/80 text-[11px]">{{ $product->unit ?: 'pcs' }}</span>
                                    </td>

                                    <!-- Harga Jual -->
                                    <td class="p-4 text-right font-extrabold text-slate-800">
                                        Rp {{ number_format($product->price, 0, ',', '.') }}
                                    </td>

                                    <!-- Stok Terkini -->
                                    <td class="p-4 text-center whitespace-nowrap">
                                        @if($product->stock <= 0)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black bg-rose-50 text-rose-700 border border-rose-200/80">
                                                Habis (0)
                                            </span>
                                        @elseif($product->stock <= 5)
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black bg-amber-50 text-amber-800 border border-amber-200/80">
                                                Sisa {{ number_format($product->stock, 0, ',', '.') }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-black bg-emerald-50 text-emerald-800 border border-emerald-200/80">
                                                {{ number_format($product->stock, 0, ',', '.') }}
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Aksi -->
                                    <td class="p-4 pr-6 text-center print:hidden whitespace-nowrap">
                                        <button onclick="openAdjustModal({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->stock }})" 
                                                class="text-teal-800 hover:text-teal-950 bg-teal-50 hover:bg-teal-100/80 border border-teal-200/70 px-3 py-1.5 rounded-xl text-xs font-extrabold transition-all shadow-2xs active:scale-95 inline-flex items-center gap-1.5">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
                                            </svg>
                                            Sesuaikan Stok
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-12 text-center text-slate-400">
                                        <div class="w-12 h-12 mx-auto rounded-2xl bg-teal-50 flex items-center justify-center text-teal-700 mb-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                        </div>
                                        <div class="font-bold text-slate-700">Data Stok Tidak Ditemukan</div>
                                        <p class="text-xs text-slate-400 mt-1">Coba ubah kata kunci pencarian Anda.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="p-4 sm:p-5 border-t border-slate-100/90 bg-white/40 print:hidden">
                    {{ $products->links() }}
                </div>
            </div>

            <!-- Right Sidebar: Alerts & Recent Logs -->
            <div class="w-full lg:w-80 flex flex-col gap-6 print:hidden">
                <!-- Alert Widget -->
                @if($outOfStockCount > 0 || $lowStockCount > 0)
                    <div class="glass-card rounded-[24px] overflow-hidden shadow-sm border border-rose-200/60">
                        <div class="bg-rose-50/80 p-4 border-b border-rose-100/80 flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <h3 class="font-extrabold text-rose-900 text-xs uppercase tracking-wider">Perhatian Stok Menipis</h3>
                        </div>
                        <div class="p-4 max-h-72 overflow-y-auto text-xs space-y-2.5">
                            @foreach($outOfStockItems as $item)
                                <div class="flex justify-between items-center p-2 rounded-xl bg-white/80 border border-rose-100">
                                    <span class="text-slate-800 font-bold truncate pr-2">{{ $item->name }}</span>
                                    <span class="text-rose-700 font-black text-[10px] bg-rose-50 border border-rose-200 px-2 py-0.5 rounded-md flex-shrink-0">Habis</span>
                                </div>
                            @endforeach
                            @foreach($lowStockItems as $item)
                                <div class="flex justify-between items-center p-2 rounded-xl bg-white/80 border border-amber-100">
                                    <span class="text-slate-700 font-medium truncate pr-2">{{ $item->name }}</span>
                                    <span class="text-amber-800 font-bold text-[10px] bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-md flex-shrink-0">Sisa {{ $item->stock }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Recent Movements Widget -->
                <div class="glass-card rounded-[24px] overflow-hidden shadow-sm flex flex-col">
                    <div class="p-4 border-b border-slate-100/90 flex items-center gap-2.5 bg-slate-50/50">
                        <svg class="w-4 h-4 text-teal-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <h3 class="font-extrabold text-slate-800 text-xs uppercase tracking-wider">Riwayat Pergerakan Stok</h3>
                    </div>
                    <div class="p-4 overflow-y-auto max-h-80">
                        @if(isset($recentLogs) && count($recentLogs) > 0)
                            <div class="relative border-l-2 border-slate-200/80 ml-2.5 space-y-5">
                                @foreach($recentLogs as $log)
                                    <div class="relative pl-5">
                                        <!-- Timeline Dot -->
                                        <div class="absolute -left-1.5 top-1.5 w-3 h-3 rounded-full border-2 border-white {{ $log->type === 'in' ? 'bg-emerald-500' : ($log->type === 'out' ? 'bg-amber-500' : 'bg-teal-600') }}"></div>
                                        
                                        <div class="flex flex-col">
                                            <div class="flex justify-between items-start gap-1">
                                                <span class="text-xs font-bold text-slate-800 leading-tight">{{ $log->product->name }}</span>
                                                <span class="text-xs font-extrabold {{ $log->quantity > 0 ? 'text-emerald-700' : 'text-amber-700' }} whitespace-nowrap">
                                                    {{ $log->quantity > 0 ? '+' : '' }}{{ $log->quantity }}
                                                </span>
                                            </div>
                                            <p class="text-[11px] text-slate-500 leading-tight mt-0.5">{{ $log->description ?? 'Penyesuaian' }}</p>
                                            <div class="flex justify-between items-center text-[10px] text-slate-400 mt-1">
                                                <span>{{ $log->user->name ?? 'Sistem' }}</span>
                                                <span>{{ $log->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-slate-400 text-center py-4">Belum ada riwayat pergerakan stok.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Penyesuaian Stok (Frosted Glassmorphism) -->
    <div id="adjustModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4 text-center">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="closeAdjustModal()"></div>

            <!-- Modal panel -->
            <div class="relative glass-card rounded-[26px] p-6 sm:p-7 text-left overflow-hidden shadow-2xl max-w-md w-full border border-white/80 z-10">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold text-slate-800" id="modal-title">Penyesuaian Stok Produk</h3>
                            <p class="text-[11px] text-slate-500 font-semibold" id="modal-product-name"></p>
                        </div>
                    </div>
                    <button onclick="closeAdjustModal()" class="text-slate-400 hover:text-slate-700 text-lg font-bold">&times;</button>
                </div>

                <form id="adjustStockForm" method="POST" action="" class="mt-4 space-y-4">
                    @csrf
                    
                    <div class="p-3 rounded-xl bg-slate-50/80 border border-slate-200/60 flex items-center justify-between text-xs">
                        <span class="text-slate-500 font-semibold">Stok Saat Ini:</span>
                        <span id="modal-current-stock" class="font-extrabold text-slate-800 text-sm"></span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Tipe Penyesuaian</label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200/80 bg-white/90 cursor-pointer hover:border-teal-500 transition-all text-xs font-bold text-slate-700">
                                <input type="radio" name="adjustment_type" value="add" class="text-teal-600 focus:ring-teal-500" checked>
                                <span>Tambah (+)</span>
                            </label>
                            <label class="flex items-center gap-2 p-2.5 rounded-xl border border-slate-200/80 bg-white/90 cursor-pointer hover:border-rose-500 transition-all text-xs font-bold text-slate-700">
                                <input type="radio" name="adjustment_type" value="subtract" class="text-rose-600 focus:ring-rose-500">
                                <span>Kurangi (-)</span>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label for="quantity" class="block text-xs font-bold text-slate-700 mb-1">Jumlah</label>
                        <input type="number" name="quantity" id="quantity" min="1" required 
                               class="w-full text-xs bg-white/90 border border-slate-200/90 rounded-xl px-3 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all shadow-2xs">
                    </div>

                    <div>
                        <label for="reason" class="block text-xs font-bold text-slate-700 mb-1">Alasan / Keterangan</label>
                        <input type="text" name="reason" id="reason" placeholder="Contoh: Koreksi opname fisik, barang rusak, salah input" required 
                               class="w-full text-xs bg-white/90 border border-slate-200/90 rounded-xl px-3 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all shadow-2xs">
                    </div>

                    <div class="pt-3 flex gap-2">
                        <button type="submit" class="flex-1 bg-gradient-to-r from-teal-700 to-emerald-800 hover:from-teal-800 hover:to-emerald-900 text-white font-extrabold text-xs py-2.5 px-4 rounded-xl shadow-sm transition-all active:scale-95">
                            Simpan Penyesuaian
                        </button>
                        <button type="button" onclick="closeAdjustModal()" class="glass-pill px-4 py-2.5 text-xs font-bold text-slate-600 hover:text-slate-800 rounded-xl transition-all">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function openAdjustModal(productId, productName, currentStock) {
            document.getElementById('modal-product-name').innerText = productName;
            document.getElementById('modal-current-stock').innerText = currentStock + ' item';
            
            let form = document.getElementById('adjustStockForm');
            let baseUrl = "{{ route('products.adjust-stock', ['product' => 'PRODUCT_ID']) }}";
            form.action = baseUrl.replace('PRODUCT_ID', productId);
            
            document.getElementById('quantity').value = '';
            document.getElementById('reason').value = '';
            
            document.getElementById('adjustModal').classList.remove('hidden');
        }

        function closeAdjustModal() {
            document.getElementById('adjustModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
