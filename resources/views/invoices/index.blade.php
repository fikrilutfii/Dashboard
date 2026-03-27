<x-app-layout>
    <x-slot name="header">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('division') == 'konfeksi' ? 'Daftar Penjualan Barang' : 'Daftar Invoice' }}
        </h2>
    </x-slot>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Simplified Filter -->
                    <div class="flex flex-wrap gap-4 mb-6 justify-between items-center bg-gray-50 p-4 rounded">
                        <form action="{{ route('invoices.index') }}" method="GET" class="flex flex-wrap gap-2 w-full md:w-auto">
                            <input type="text" name="search" placeholder="Cari No Invoice / Customer" value="{{ request('search') }}" class="border rounded px-2 py-1 text-sm w-full md:w-48">
                            
                            <select name="status" class="border rounded px-2 py-1 text-sm bg-white">
                                <option value="">Semua Status</option>
                                <option value="belum_lunas" {{ request('status') == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                                <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                            </select>
                            
                            <input type="date" name="date_filter" value="{{ request('date_filter') }}" class="border rounded px-2 py-1 text-sm bg-white">
                            
                            <button type="submit" class="bg-primary-500 text-white px-3 py-1 rounded text-sm hover:bg-primary-600">Filter</button>
                            @if(request()->anyFilled(['search', 'status', 'date_filter']))
                                <a href="{{ route('invoices.index') }}" class="text-gray-500 text-sm py-1 hover:underline">Reset</a>
                            @endif
                        </form>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('invoices.printReport', request()->only(['search','status','date_filter','division'])) }}" target="_blank" class="bg-gray-600 text-white px-4 py-2 rounded text-sm font-bold hover:bg-gray-700 flex items-center gap-1">
                                🖨️ Cetak Laporan
                            </a>
                            <a href="{{ route('invoices.create') }}" class="bg-green-600 text-white px-4 py-2 rounded text-sm font-bold hover:bg-green-700">
                                + Buat Invoice
                            </a>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse border border-gray-200">
                            <!-- ... table content ... -->
                            <thead>
                                <tr class="bg-gray-100 text-sm">
                                    <th class="border p-2 text-left">No Invoice</th>
                                    <th class="border p-2 text-left">Tanggal</th>
                                    <th class="border p-2 text-left">Customer</th>
                                    <th class="border p-2 text-right">Total</th>
                                    <th class="border p-2 text-center">Status</th>
                                    <th class="border p-2 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($invoices as $invoice)
                                    <tr class="hover:bg-gray-50 text-sm">
                                        <td class="border p-2 font-mono font-bold">{{ $invoice->invoice_number }}</td>
                                        <td class="border p-2">{{ $invoice->invoice_date->format('d/m/Y') }}</td>
                                        <td class="border p-2">{{ $invoice->customer->name }}</td>
                                        <td class="border p-2 text-right font-mono">{{ number_format($invoice->total_amount, 0, ',', '.') }}</td>
                                        <td class="border p-2 text-center">
                                            @if($invoice->status == 'lunas')
                                                <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full border border-green-200 uppercase font-bold">Lunas</span>
                                            @else
                                                <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full border border-red-200 uppercase font-bold">Belum Lunas</span>
                                            @endif
                                        </td>
                                        <td class="border p-2 text-center space-x-2">
                                            <a href="{{ route('invoices.show', $invoice) }}" class="text-primary-600 hover:underline">Lihat</a>
                                            @if($invoice->status != 'lunas')
                                                | <a href="{{ route('invoices.edit', $invoice) }}" class="text-yellow-600 hover:underline">Edit</a>
                                                | <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus invoice ini? Data tidak dapat dikembalikan.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:underline bg-transparent border-0 cursor-pointer p-0">Hapus</button>
                                                </form>
                                            @endif
                                            | <a href="{{ route('invoices.print', $invoice) }}" target="_blank" class="text-gray-600 hover:text-gray-900 hover:underline">Print</a>
                                            | <a href="{{ route('invoices.export', $invoice) }}" class="text-green-600 hover:text-green-900 hover:underline font-semibold">Excel</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-4 text-center text-gray-500">Tidak ada invoice ditemukan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $invoices->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
