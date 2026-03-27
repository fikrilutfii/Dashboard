<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pembelian Bahan') }} - {{ ucfirst(request('division', 'All')) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="mb-4 flex flex-col md:flex-row gap-4 justify-between items-center">
                <form method="GET" action="{{ route('purchases.index') }}" class="flex flex-col md:flex-row gap-2 w-full md:w-auto flex-1">
                    <input type="hidden" name="division" value="{{ request('division') }}">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No. Purchase / Supplier..." class="border rounded px-4 py-2 w-full md:w-64">
                    <input type="date" name="date_start" value="{{ request('date_start') }}" class="border rounded px-3 py-2">
                    <input type="date" name="date_end" value="{{ request('date_end') }}" class="border rounded px-3 py-2">
                    <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded">Cari</button>
                </form>
                <a href="{{ route('purchases.create', ['division' => request('division')]) }}" class="bg-primary-600 px-4 py-2 text-white rounded hover:bg-primary-700 whitespace-nowrap shadow hover:shadow-md transition-all">
                    + Beli Bahan
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No. Purchase</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Supplier</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($purchases as $purchase)
                                <tr>
                                    <td class="px-5 py-3 border-b border-gray-200 bg-white text-sm">
                                        <div class="font-bold text-gray-900">{{ $purchase->purchase_number }}</div>
                                        <div class="text-xs text-gray-500">{{ $purchase->division }}</div>
                                    </td>
                                    <td class="px-5 py-3 border-b border-gray-200 bg-white text-sm">
                                        {{ $purchase->supplier->name ?? 'Non-Supplier (Cash)' }}
                                    </td>
                                    <td class="px-5 py-3 border-b border-gray-200 bg-white text-sm">
                                        {{ $purchase->date->format('d/m/Y') }}
                                    </td>
                                    <td class="px-5 py-3 border-b border-gray-200 bg-white text-sm">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $purchase->status == 'lunas' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $purchase->status == 'lunas' ? 'Lunas' : 'Belum Lunas' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 border-b border-gray-200 bg-white text-sm text-right font-mono">
                                        Rp {{ number_format($purchase->total_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-5 py-3 border-b border-gray-200 bg-white text-sm text-right">
                                        <a href="{{ route('purchases.show', $purchase) }}" class="text-blue-600 hover:text-blue-900 mr-2">Detail</a>
                                        @if($purchase->status == 'unpaid')
                                            <form action="{{ route('purchases.update', $purchase) }}" method="POST" class="inline" onsubmit="return confirm('Lunasi pembayaran ini sekarang? Saldo akan terpotong.')">
                                                @csrf
                                                @method('PUT') 
                                                <!-- Actually updateStatus is not standard resource method update, need custom route or use a dedicated method -->
                                                <!-- Controller has updateStatus but standard resource points to update. 
                                                     Let's check routes. Resource creates update->PUT. 
                                                     But updateStatus logic is for payment. 
                                                     I should probably separate 'update' (edit) and 'pay'. 
                                                     For now, I'll use a hack or just link to Detail page to pay. -->
                                            </form>
                                            <a href="{{ route('purchases.show', $purchase) }}" class="bg-green-500 text-white px-2 py-1 rounded text-xs">Bayar</a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-3 border-b border-gray-200 bg-white text-sm text-center text-gray-500">
                                        Belum ada data pembelian.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="px-5 py-5 bg-white border-t flex flex-col xs:flex-row items-center xs:justify-between">
                        {{ $purchases->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
