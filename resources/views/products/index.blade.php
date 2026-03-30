<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Master Barang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if(session('success'))
                        <div class="mb-4 p-3 bg-green-50 text-green-800 rounded border border-green-200 text-sm">✅ {{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="mb-4 p-3 bg-red-50 text-red-800 rounded border border-red-200 text-sm">❌ {{ session('error') }}</div>
                    @endif

                    <div class="flex justify-between mb-4 flex-wrap gap-2">
                        <form action="{{ route('products.index') }}" method="GET" class="flex gap-2">
                            <input type="text" name="search" placeholder="Cari Kode / Nama..." value="{{ request('search') }}" class="border rounded px-2 py-1">
                            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded shadow hover:shadow-md transition-all">Cari</button>
                        </form>
                        <div class="flex gap-2 flex-wrap">
                            <a href="{{ route('products.create') }}" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 text-sm font-semibold">+ Tambah Barang</a>
                            <form action="{{ route('products.import-csv') }}" method="POST" onsubmit="return confirm('Import data dari file \'data master.csv\' (di root project)? Data lama akan diupdate jika kode sama.')">
                                @csrf
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm font-semibold">📥 Import Data Master CSV</button>
                            </form>
                        </div>
                    </div>

                    <table class="w-full border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border border-gray-300 p-2">Kode</th>
                                <th class="border border-gray-300 p-2">Nama Barang</th>
                                <th class="border border-gray-300 p-2">Satuan</th>
                                <th class="border border-gray-300 p-2 text-right">Harga</th>
                                <th class="border border-gray-300 p-2 text-center">Stok</th>
                                <th class="border border-gray-300 p-2 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                <tr>
                                    <td class="border border-gray-300 p-2">{{ $product->code }}</td>
                                    <td class="border border-gray-300 p-2">{{ $product->name }}</td>
                                    <td class="border border-gray-300 p-2">{{ $product->unit }}</td>
                                    <td class="border border-gray-300 p-2 text-right">{{ number_format($product->price, 0, ',', '.') }}</td>
                                    <td class="border border-gray-300 p-2 text-center">
                                        <span class="font-bold {{ $product->stock <= 5 ? 'text-red-500' : 'text-green-600' }}">{{ number_format($product->stock, 0, ',', '.') }}</span>
                                        @if($product->shared_stock_code)
                                            <br><span class="text-xs text-blue-500 bg-blue-50 px-1 rounded border border-blue-200" title="Shared Stock Code">🔗 {{ $product->shared_stock_code }}</span>
                                        @endif
                                    </td>
                                    <td class="border border-gray-300 p-2 text-center">
                                        <a href="{{ route('products.edit', $product) }}" class="text-primary-600 hover:text-primary-900">Edit</a> |
                                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Hapus barang ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:underline">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="border border-gray-300 p-2 text-center">Belum ada data barang.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    <div class="mt-4">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
