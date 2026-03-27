<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Pembelian: {{ $purchase->purchase_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600">Supplier</p>
                            <p class="font-bold text-lg">{{ $purchase->supplier->name ?? 'Umum (Cash)' }}</p>
                            <p class="text-sm text-gray-600 mt-2">Divisi</p>
                            <p class="font-bold uppercase">{{ $purchase->division }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Tanggal</p>
                            <p class="font-bold">{{ $purchase->date->format('d F Y') }}</p>
                            <p class="text-sm text-gray-600 mt-2">Status Pembayaran</p>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $purchase->status == 'lunas' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $purchase->status == 'lunas' ? 'Lunas' : 'Belum Lunas' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="font-bold text-lg mb-4">Item Pembelian</h3>
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr>
                                <th class="px-5 py-3 border-b-2 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">Item</th>
                                <th class="px-5 py-3 border-b-2 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase">Quantity</th>
                                <th class="px-5 py-3 border-b-2 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase">Harga Satuan</th>
                                <th class="px-5 py-3 border-b-2 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchase->items as $item)
                                <tr>
                                    <td class="px-5 py-3 border-b">{{ $item->item_name }}</td>
                                    <td class="px-5 py-3 border-b text-right">{{ $item->quantity }}</td>
                                    <td class="px-5 py-3 border-b text-right">{{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                    <td class="px-5 py-3 border-b text-right font-bold">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="3" class="px-5 py-3 text-right font-bold">TOTAL</td>
                                <td class="px-5 py-3 text-right font-bold text-lg border-t-2 border-black">Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-end gap-3">
                <a href="{{ route('purchases.index', ['division' => $purchase->division]) }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Kembali</a>
                
                @if($purchase->status == 'belum_lunas')
                <form action="{{ route('purchases.update-status', $purchase) }}" method="POST" onsubmit="return confirm('Konfirmasi pelunasan pembayaran ini?')">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Lunasi Pembayaran Sekarang</button>
                </form>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
