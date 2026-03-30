<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight print:hidden">
            {{ __('Dashboard & Laporan Stok Barang') }}
        </h2>
    </x-slot>

    <div class="py-12 print:py-0 print:m-0">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 print:max-w-none print:px-0">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg print:shadow-none print:rounded-none">
                <div class="p-6 text-gray-900 print:p-0">

                    <!-- Header Cetak Khusus (Hanya muncul saat print) -->
                    <div class="hidden print:block text-center mb-8">
                        <h1 class="text-2xl font-bold uppercase">Laporan Stok Barang ({{ ucfirst(session('division', 'Semua Divisi')) }})</h1>
                        <p class="text-gray-500">Tanggal Cetak: {{ date('d M Y H:i') }}</p>
                        <hr class="mt-4 border-2 border-gray-800">
                    </div>

                    <!-- Metrics / Dashboard Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 print:hidden">
                        <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg shadow-sm">
                            <h3 class="text-blue-800 font-bold text-lg">Total Jenis Barang</h3>
                            <p class="text-3xl font-black text-blue-900 mt-2">{{ number_format($totalProducts) }}</p>
                        </div>
                        <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg shadow-sm">
                            <h3 class="text-yellow-800 font-bold text-lg">Stok Menipis (<= 5)</h3>
                            <p class="text-3xl font-black text-yellow-900 mt-2">{{ number_format($lowStockCount) }}</p>
                            @if($lowStockCount > 0)
                                <ul class="mt-2 text-sm text-yellow-700 list-disc list-inside">
                                    @foreach($lowStockItems as $item)
                                        <li>{{ $item->name }} <span class="font-bold">({{ $item->stock }})</span></li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                        <div class="bg-red-50 border border-red-200 p-4 rounded-lg shadow-sm">
                            <h3 class="text-red-800 font-bold text-lg">Stok Habis (= 0)</h3>
                            <p class="text-3xl font-black text-red-900 mt-2">{{ number_format($outOfStockCount) }}</p>
                            @if($outOfStockCount > 0)
                                <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                    @foreach($outOfStockItems as $item)
                                        <li>{{ $item->name }}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>

                    <!-- Filter & Print Button -->
                    <div class="flex justify-between items-center mb-4 print:hidden">
                        <form action="{{ route('reports.stock') }}" method="GET" class="flex gap-2">
                            <input type="text" name="search" placeholder="Cari Kode/Nama..." value="{{ request('search') }}" class="border rounded px-3 py-1 text-sm focus:ring-primary-500">
                            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-1 px-4 rounded shadow">Cari</button>
                            @if(request('search'))
                                <a href="{{ route('reports.stock') }}" class="py-1 px-4 text-gray-500 hover:text-gray-700">Reset</a>
                            @endif
                        </form>
                        <button onclick="window.print()" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded shadow flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" />
                            </svg>
                            Cetak Laporan
                        </button>
                    </div>

                    <!-- Full Table -->
                    <h3 class="font-bold text-lg mb-2 border-b pb-2 print:text-xl print:mb-4">Daftar Seluruh Stok Barang</h3>
                    <table class="w-full border-collapse border border-gray-300 text-sm print:text-xs">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-300 p-2 text-left">Kode</th>
                                <th class="border border-gray-300 p-2 text-left">Nama Barang</th>
                                <th class="border border-gray-300 p-2 text-center">Satuan</th>
                                <th class="border border-gray-300 p-2 text-center">Divisi</th>
                                <th class="border border-gray-300 p-2 text-center bg-gray-200">Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                <tr class="{{ $product->stock <= 0 ? 'bg-red-50 text-red-900' : ($product->stock <= 5 ? 'bg-yellow-50 text-yellow-900' : '') }}">
                                    <td class="border border-gray-300 p-2">
                                        <span class="font-semibold">{{ $product->code }}</span>
                                        @if($product->shared_stock_code)
                                            <div class="text-[10px] text-blue-600 mt-1 whitespace-nowrap">🔗 {{ $product->shared_stock_code }}</div>
                                        @endif
                                    </td>
                                    <td class="border border-gray-300 p-2 font-medium">{{ $product->name }}</td>
                                    <td class="border border-gray-300 p-2 text-center">{{ $product->unit }}</td>
                                    <td class="border border-gray-300 p-2 text-center capitalize">{{ $product->division }}</td>
                                    <td class="border border-gray-300 p-2 text-center font-bold text-base {{ $product->stock <= 0 ? 'text-red-700' : ($product->stock <= 5 ? 'text-yellow-700' : 'text-green-700') }}">
                                        {{ number_format($product->stock, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="border border-gray-300 p-4 text-center text-gray-500">Data stok barang tidak ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4 print:hidden">
                        {{ $products->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Print CSS -->
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .max-w-7xl, .max-w-7xl * {
                visibility: visible;
            }
            .max-w-7xl {
                position: absolute;
                left: 0;
                top: 0;
            }
            @page {
                size: portrait;
                margin: 1cm;
            }
        }
    </style>
</x-app-layout>
