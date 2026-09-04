<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Barang') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('products.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Kode Barang</label>
                            <input type="text" name="code" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('code') border-red-500 @enderror" placeholder="Contoh: A001" value="{{ old('code') }}">
                            @error('code')
                                <p class="text-red-500 text-xs italic mt-1 feedback-error" id="error-code">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Barang</label>
                            <input type="text" name="name" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror" value="{{ old('name') }}">
                            @error('name')
                                <p class="text-red-500 text-xs italic mt-1 feedback-error" id="error-name">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex gap-4 mb-4">
                            <div class="w-1/2">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Satuan</label>
                                <input type="text" name="unit" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('unit') border-red-500 @enderror" placeholder="Pcs, Rim, Box" value="{{ old('unit') }}">
                                @error('unit')
                                    <p class="text-red-500 text-xs italic mt-1 feedback-error" id="error-unit">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="w-1/2">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Harga Default</label>
                                <input type="number" name="price" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('price') border-red-500 @enderror" min="0" value="{{ old('price') }}">
                                @error('price')
                                    <p class="text-red-500 text-xs italic mt-1 feedback-error" id="error-price">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex gap-4 mb-6">
                            <div class="w-1/2">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Stok Awal</label>
                                <input type="number" name="stock" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('stock') border-red-500 @enderror" min="0" value="{{ old('stock', 0) }}">
                                @error('stock')
                                    <p class="text-red-500 text-xs italic mt-1 feedback-error" id="error-stock">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="w-1/2">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Kode Stok Bersama (Opsional)</label>
                                <input type="text" name="shared_stock_code" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('shared_stock_code') border-red-500 @enderror" placeholder="Abaikan untuk stok terpisah" value="{{ old('shared_stock_code') }}">
                                @error('shared_stock_code')
                                    <p class="text-red-500 text-xs italic mt-1 feedback-error" id="error-shared_stock_code">{{ $message }}</p>
                                @enderror
                                <p class="text-xs text-gray-500 mt-1">Samakan kode ini antar barang jika fisiknya sama persis.</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                                Simpan Barang
                            </button>
                            <a href="{{ route('products.index') }}" class="text-gray-600 hover:text-gray-800">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
