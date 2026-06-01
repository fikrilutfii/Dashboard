<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center print:hidden">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Dashboard Stok Barang') }}
            </h2>
            <div class="flex gap-2">
                <button onclick="window.print()" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-semibold py-2 px-4 rounded-lg shadow-sm transition-all flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" />
                    </svg>
                    Cetak Laporan
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-8 print:py-0 print:m-0">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 print:max-w-none print:px-0">
            
            <!-- Success/Error Messages -->
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg p-4 flex justify-between items-center shadow-sm print:hidden">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-600 hover:text-emerald-800">&times;</button>
                </div>
            @endif
            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" class="mb-4 bg-red-50 border border-red-200 text-red-800 rounded-lg p-4 flex justify-between items-center shadow-sm print:hidden">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                        <span class="font-medium">{{ session('error') }}</span>
                    </div>
                    <button @click="show = false" class="text-red-600 hover:text-red-800">&times;</button>
                </div>
            @endif

            <!-- Header Cetak Khusus (Hanya muncul saat print) -->
            <div class="hidden print:block text-center mb-8">
                <h1 class="text-2xl font-bold uppercase">Laporan Stok Barang ({{ ucfirst(session('division', 'Semua Divisi')) }})</h1>
                <p class="text-gray-500">Tanggal Cetak: {{ date('d M Y H:i') }}</p>
                <hr class="mt-4 border-2 border-gray-800">
            </div>

            <!-- Premium Metrics Dashboard -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8 print:hidden">
                <!-- Card 1: Total Value -->
                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden group">
                    <div class="absolute right-0 top-0 opacity-10 transform translate-x-4 -translate-y-4 transition-transform group-hover:scale-110">
                        <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path></svg>
                    </div>
                    <h3 class="text-indigo-100 font-medium text-sm tracking-wider uppercase mb-1">Total Nilai Aset</h3>
                    <p class="text-3xl font-bold">Rp {{ number_format($totalAssetValue, 0, ',', '.') }}</p>
                    <p class="text-indigo-200 text-xs mt-2">Berdasarkan Harga Jual & Stok</p>
                </div>

                <!-- Card 2: Total Items -->
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex flex-col justify-center">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        </div>
                        <h3 class="text-gray-500 font-medium text-sm">Total Produk</h3>
                    </div>
                    <p class="text-3xl font-black text-gray-800">{{ number_format($totalProducts) }} <span class="text-base font-medium text-gray-400">item</span></p>
                </div>

                <!-- Card 3: Low Stock -->
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex flex-col justify-center relative overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-amber-400"></div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 bg-amber-50 text-amber-600 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <h3 class="text-gray-500 font-medium text-sm">Stok Menipis (<= 5)</h3>
                    </div>
                    <p class="text-3xl font-black text-gray-800">{{ number_format($lowStockCount) }} <span class="text-base font-medium text-gray-400">item</span></p>
                </div>

                <!-- Card 4: Out of Stock -->
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex flex-col justify-center relative overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-rose-500"></div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="p-2 bg-rose-50 text-rose-600 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-gray-500 font-medium text-sm">Stok Habis</h3>
                    </div>
                    <p class="text-3xl font-black text-gray-800">{{ number_format($outOfStockCount) }} <span class="text-base font-medium text-gray-400">item</span></p>
                </div>
            </div>

            <!-- Two Column Layout: Main Table & Recent Activity -->
            <div class="flex flex-col lg:flex-row gap-6">
                
                <!-- Main Content: Stock Table -->
                <div class="flex-1 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    
                    <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <h3 class="font-bold text-lg text-gray-800">Manajemen Stok</h3>
                        <form action="{{ route('reports.stock') }}" method="GET" class="flex w-full sm:w-auto relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" name="search" placeholder="Cari Kode / Nama..." value="{{ request('search') }}" class="w-full sm:w-64 pl-10 pr-4 py-2 border border-gray-200 rounded-l-lg text-sm focus:ring-primary-500 focus:border-primary-500">
                            <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white font-medium py-2 px-4 rounded-r-lg text-sm transition-colors">Cari</button>
                            @if(request('search'))
                                <a href="{{ route('reports.stock') }}" class="ml-2 py-2 px-3 text-sm text-gray-500 hover:text-gray-700 bg-gray-100 rounded-lg">Reset</a>
                            @endif
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                                    <th class="p-4 font-medium">Barang</th>
                                    <th class="p-4 font-medium text-center">Satuan</th>
                                    <th class="p-4 font-medium text-right">Harga Jual</th>
                                    <th class="p-4 font-medium text-center">Stok Terkini</th>
                                    <th class="p-4 font-medium text-center print:hidden">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">
                                @forelse($products as $product)
                                    <tr class="hover:bg-gray-50 transition-colors {{ $product->stock <= 0 ? 'bg-rose-50/30' : ($product->stock <= 5 ? 'bg-amber-50/30' : '') }}">
                                        <td class="p-4">
                                            <div class="flex items-center">
                                                <div>
                                                    <div class="font-bold text-gray-800">{{ $product->name }}</div>
                                                    <div class="text-xs text-gray-500 flex items-center gap-2 mt-1">
                                                        <span class="bg-gray-100 px-2 py-0.5 rounded font-mono">{{ $product->code }}</span>
                                                        @if($product->shared_stock_code)
                                                            <span class="text-[10px] text-blue-600 bg-blue-50 px-1.5 py-0.5 rounded-full flex items-center gap-1" title="Shared Stock">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                                                {{ $product->shared_stock_code }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="p-4 text-center text-gray-500">{{ $product->unit }}</td>
                                        <td class="p-4 text-right font-medium text-gray-700">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                        <td class="p-4 text-center">
                                            @if($product->stock <= 0)
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200">
                                                    Habis (0)
                                                </span>
                                            @elseif($product->stock <= 5)
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                                    Sisa {{ number_format($product->stock, 0, ',', '.') }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                    {{ number_format($product->stock, 0, ',', '.') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="p-4 text-center print:hidden">
                                            <button onclick="openAdjustModal({{ $product->id }}, '{{ $product->name }}', {{ $product->stock }})" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded text-xs font-semibold transition-colors">
                                                Sesuaikan Stok
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-8 text-center text-gray-500">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                                <p>Data stok barang tidak ditemukan.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="p-4 border-t border-gray-100 print:hidden">
                        {{ $products->links() }}
                    </div>
                </div>

                <!-- Right Sidebar: Recent Logs & Alerts -->
                <div class="w-full lg:w-80 flex flex-col gap-6 print:hidden">
                    
                    <!-- Alert Widget -->
                    @if($outOfStockCount > 0 || $lowStockCount > 0)
                    <div class="bg-white rounded-2xl shadow-sm border border-rose-100 overflow-hidden">
                        <div class="bg-rose-50 p-4 border-b border-rose-100 flex items-center gap-2">
                            <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <h3 class="font-bold text-rose-800">Perhatian Stok</h3>
                        </div>
                        <div class="p-4 max-h-64 overflow-y-auto text-sm">
                            <ul class="space-y-3">
                                @foreach($outOfStockItems as $item)
                                    <li class="flex justify-between items-start">
                                        <span class="text-gray-700 font-medium">{{ $item->name }}</span>
                                        <span class="text-rose-600 font-bold text-xs bg-rose-50 px-2 py-0.5 rounded">Habis</span>
                                    </li>
                                @endforeach
                                @foreach($lowStockItems as $item)
                                    <li class="flex justify-between items-start">
                                        <span class="text-gray-600">{{ $item->name }}</span>
                                        <span class="text-amber-600 font-bold text-xs bg-amber-50 px-2 py-0.5 rounded">Sisa {{ $item->stock }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @endif

                    <!-- Recent Activity Widget -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex-1 flex flex-col overflow-hidden">
                        <div class="p-4 border-b border-gray-100 flex items-center gap-2 bg-gray-50/50">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <h3 class="font-bold text-gray-800">Riwayat Pergerakan Stok</h3>
                        </div>
                        <div class="p-4 flex-1 overflow-y-auto">
                            @if(isset($recentLogs) && count($recentLogs) > 0)
                                <div class="relative border-l border-gray-200 ml-3 space-y-6">
                                    @foreach($recentLogs as $log)
                                        <div class="relative pl-6">
                                            <!-- Timeline Dot -->
                                            <div class="absolute -left-1.5 top-1 w-3 h-3 rounded-full border-2 border-white {{ $log->type === 'in' ? 'bg-emerald-500' : ($log->type === 'out' ? 'bg-amber-500' : 'bg-blue-500') }}"></div>
                                            
                                            <div class="flex flex-col">
                                                <div class="flex justify-between items-start mb-1">
                                                    <span class="text-sm font-bold text-gray-800">{{ $log->product->name }}</span>
                                                    <span class="text-xs font-bold {{ $log->quantity > 0 ? 'text-emerald-600' : 'text-amber-600' }}">
                                                        {{ $log->quantity > 0 ? '+' : '' }}{{ $log->quantity }}
                                                    </span>
                                                </div>
                                                <p class="text-xs text-gray-600 leading-tight mb-1">{{ $log->description ?? 'Penyesuaian' }}</p>
                                                <div class="flex justify-between items-center text-[10px] text-gray-400">
                                                    <span>Oleh: {{ $log->user->name ?? 'Sistem' }}</span>
                                                    <span>{{ $log->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-500 text-center py-4">Belum ada riwayat pergerakan stok.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Penyesuaian Stok -->
    <div id="adjustModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeAdjustModal()"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 border-b">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Penyesuaian Stok: <span id="modal-product-name" class="font-bold text-indigo-700"></span>
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 mb-4">Stok Saat Ini: <span id="modal-current-stock" class="font-bold text-gray-800"></span></p>
                                
                                <form id="adjustStockForm" method="POST" action="">
                                    @csrf
                                    
                                    <div class="mb-4">
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Tipe Penyesuaian</label>
                                        <div class="flex gap-4">
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="adjustment_type" value="add" class="text-indigo-600 form-radio focus:ring-indigo-500" checked>
                                                <span class="ml-2 text-sm text-gray-700">Tambah (+)</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="adjustment_type" value="subtract" class="text-rose-600 form-radio focus:ring-rose-500">
                                                <span class="ml-2 text-sm text-gray-700">Kurangi (-)</span>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label for="quantity" class="block text-gray-700 text-sm font-bold mb-2">Jumlah</label>
                                        <input type="number" name="quantity" id="quantity" min="1" required class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                    
                                    <div class="mb-2">
                                        <label for="reason" class="block text-gray-700 text-sm font-bold mb-2">Alasan / Keterangan</label>
                                        <input type="text" name="reason" id="reason" placeholder="Contoh: Barang rusak, Salah input, Koreksi opname" required class="shadow-sm appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" form="adjustStockForm" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        Simpan Penyesuaian
                    </button>
                    <button type="button" onclick="closeAdjustModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        function openAdjustModal(productId, productName, currentStock) {
            document.getElementById('modal-product-name').innerText = productName;
            document.getElementById('modal-current-stock').innerText = currentStock;
            
            // Set form action dynamic URL
            let form = document.getElementById('adjustStockForm');
            // We use a base URL and replace the id. Assuming route format: /products/{product}/adjust-stock
            let baseUrl = "{{ route('products.adjust-stock', ['product' => 'PRODUCT_ID']) }}";
            form.action = baseUrl.replace('PRODUCT_ID', productId);
            
            // Reset fields
            document.getElementById('quantity').value = '';
            document.getElementById('reason').value = '';
            
            document.getElementById('adjustModal').classList.remove('hidden');
        }

        function closeAdjustModal() {
            document.getElementById('adjustModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
