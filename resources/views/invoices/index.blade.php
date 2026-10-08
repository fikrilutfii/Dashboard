<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('division') == 'konfeksi' ? 'Daftar Penjualan Barang' : 'Daftar Invoice' }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-5">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">{{ session('error') }}</div>
            @endif

            <!-- Metric Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
                    <p class="text-xs text-gray-500 uppercase font-bold">Total Penjualan</p>
                    <p class="text-xl font-bold text-blue-700 font-mono">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $countLunas + $countBelumLunas }} faktur</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
                    <p class="text-xs text-gray-500 uppercase font-bold">Total Pelunasan</p>
                    <p class="text-xl font-bold text-green-700 font-mono">Rp {{ number_format($totalPelunasan, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $countLunas }} faktur lunas</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
                    <p class="text-xs text-gray-500 uppercase font-bold">Sisa Tagihan</p>
                    <p class="text-xl font-bold text-red-700 font-mono">Rp {{ number_format($totalSisaTagihan, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $countBelumLunas }} belum lunas</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4 border-l-4 border-purple-500">
                    <p class="text-xs text-gray-500 uppercase font-bold">% Terkumpul</p>
                    <p class="text-xl font-bold text-purple-700 font-mono">
                        {{ $totalPenjualan > 0 ? number_format(($totalPelunasan / $totalPenjualan) * 100, 1) : 0 }}%
                    </p>
                    <p class="text-xs text-gray-400 mt-1">dari total tagihan</p>
                </div>
            </div>

            <!-- Filters Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 border-b">
                    <form action="{{ route('invoices.index') }}" method="GET" class="flex flex-wrap gap-3 items-end">
                        <!-- Search -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1">Cari</label>
                            <input type="text" name="search" placeholder="No Invoice / Customer / Nama Barang" value="{{ request('search') }}"
                                class="border rounded px-2 py-1.5 text-sm w-56">
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1">Status</label>
                            <select name="status" class="border rounded px-2 py-1.5 text-sm bg-white">
                                <option value="">Semua Status</option>
                                <option value="belum_lunas" {{ request('status') == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                                <option value="sebagian" {{ request('status') == 'sebagian' ? 'selected' : '' }}>Sebagian</option>
                                <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                            </select>
                        </div>

                        <!-- Date Filter By -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1">Filter Tanggal</label>
                            <select name="filter_date_by" class="border rounded px-2 py-1.5 text-sm bg-white">
                                <option value="invoice_date" {{ request('filter_date_by', 'invoice_date') == 'invoice_date' ? 'selected' : '' }}>Tgl Faktur</option>
                                <option value="payment_date" {{ request('filter_date_by') == 'payment_date' ? 'selected' : '' }}>Tgl Pelunasan</option>
                            </select>
                        </div>

                        <!-- Start Date -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1">Dari</label>
                            <input type="date" name="start_date" value="{{ request('start_date') }}"
                                class="border rounded px-2 py-1.5 text-sm bg-white">
                        </div>

                        <!-- End Date -->
                        <div>
                            <label class="block text-xs font-bold text-gray-500 mb-1">Sampai</label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}"
                                class="border rounded px-2 py-1.5 text-sm bg-white">
                        </div>

                        <button type="submit" class="bg-blue-600 text-white px-4 py-1.5 rounded text-sm hover:bg-blue-700">Filter</button>
                        @if(request()->anyFilled(['search', 'status', 'start_date', 'end_date', 'filter_date_by', 'date_filter']))
                            <a href="{{ route('invoices.index') }}" class="text-gray-500 text-sm py-1.5 hover:underline">Reset</a>
                        @endif
                    </form>
                </div>

                <!-- Action Buttons -->
                <div class="px-4 py-3 flex justify-end gap-2 bg-gray-50">
                    <a href="{{ route('reports.billing') }}" class="bg-indigo-600 text-white px-4 py-2 rounded text-sm font-bold hover:bg-indigo-700">
                        Tagihan per Klien
                    </a>
                    <a href="{{ route('invoices.printReport', request()->only(['search','status','date_filter','division'])) }}" target="_blank"
                        class="bg-gray-600 text-white px-4 py-2 rounded text-sm font-bold hover:bg-gray-700">
                        Cetak Laporan
                    </a>
                    <a href="{{ route('invoices.create') }}" class="bg-green-600 text-white px-4 py-2 rounded text-sm font-bold hover:bg-green-700">
                        + Buat Invoice
                    </a>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-gray-200">
                        <thead>
                            <tr class="bg-gray-100 text-sm">
                                <th class="border p-2 text-left">No Invoice</th>
                                <th class="border p-2 text-left">Tanggal</th>
                                <th class="border p-2 text-left">Customer</th>
                                <th class="border p-2 text-center">Metode</th>
                                <th class="border p-2 text-right">Total</th>
                                <th class="border p-2 text-right">Dibayar</th>
                                <th class="border p-2 text-right">Sisa</th>
                                <th class="border p-2 text-center">Status</th>
                                <th class="border p-2 text-center">Cetak</th>
                                <th class="border p-2 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoices as $invoice)
                                <tr class="hover:bg-gray-50 text-sm">
                                    <td class="border p-2 font-mono">
                                        <strong>{{ $invoice->faktur_number }}</strong><br>
                                        <small class="text-gray-500">OP: {{ $invoice->invoice_number ?? '-' }}</small>
                                    </td>
                                    <td class="border p-2">{{ $invoice->invoice_date->format('d/m/Y') }}</td>
                                    <td class="border p-2">{{ $invoice->customer->name }}</td>
                                    <td class="border p-2 text-center">
                                        @if($invoice->payment_method === 'credit')
                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-black bg-amber-50 text-amber-600 border border-amber-100 uppercase">Credit</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-md text-[10px] font-black bg-blue-50 text-blue-600 border border-blue-100 uppercase">Cash</span>
                                        @endif
                                    </td>
                                    <td class="border p-2 text-right font-mono">{{ number_format($invoice->total_amount, 0, ',', '.') }}</td>
                                    <td class="border p-2 text-right font-mono text-green-700">{{ number_format($invoice->paid_amount ?? 0, 0, ',', '.') }}</td>
                                    <td class="border p-2 text-right font-mono text-red-600">{{ number_format(max(0, $invoice->total_amount - ($invoice->paid_amount ?? 0)), 0, ',', '.') }}</td>
                                    <td class="border p-2 text-center">
                                        @if($invoice->status == 'lunas')
                                            <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full border border-green-200 uppercase font-bold whitespace-nowrap">Lunas</span>
                                        @elseif($invoice->status == 'sebagian')
                                            <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full border border-yellow-200 uppercase font-bold whitespace-nowrap">Sebagian</span>
                                        @else
                                            <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full border border-red-200 uppercase font-bold whitespace-nowrap">Belum</span>
                                        @endif
                                    </td>
                                    <td class="border p-2 text-center">
                                        @if($invoice->is_printed)
                                            <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full border border-blue-200 uppercase font-bold">Sudah</span>
                                        @else
                                            <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded-full border border-gray-200 uppercase font-bold">Belum</span>
                                        @endif
                                    </td>
                                    <td class="border p-2 text-center whitespace-nowrap space-x-1 text-xs">
                                        <a href="{{ route('invoices.show', $invoice) }}" class="text-blue-600 hover:underline">Lihat</a>
                                        @if($invoice->status != 'lunas')
                                            | <a href="{{ route('invoices.edit', $invoice) }}" class="text-yellow-600 hover:underline">Edit</a>
                                        @endif
                                        | <a href="javascript:void(0)" onclick="confirmPrint('{{ route('invoices.print', $invoice) }}')" class="text-gray-600 hover:underline">Print SJ</a>
                                        | <a href="javascript:void(0)" onclick="confirmPrint('{{ route('invoices.print-combined', $invoice) }}')" class="text-blue-600 hover:underline">Gabung</a>
                                        | <a href="javascript:void(0)" onclick="confirmPrint('{{ route('invoices.export', $invoice) }}', true)" class="text-green-600 hover:underline font-semibold">Excel</a>
                                        | <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus faktur ini? Stok barang akan dikembalikan.');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="p-4 text-center text-gray-500">Tidak ada invoice ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4">
                    {{ $invoices->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
