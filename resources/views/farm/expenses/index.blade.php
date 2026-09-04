<x-farm-layout title="Pengeluaran Peternakan" subtitle="Catatan Pembelian Pakan, Bibit DOC, Obat & Biaya Operasional">
    <x-slot name="headerActions">
        <a href="{{ route('farm.expenses.create') }}" class="bg-green-600 text-white font-bold px-4 py-2 rounded-lg hover:bg-green-700 text-sm shadow">
            + Catat Pengeluaran
        </a>
    </x-slot>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
            <p class="text-xs font-semibold text-gray-500 uppercase">Total Bulan Ini</p>
            <h4 class="text-xl font-bold text-red-600 mt-1">Rp {{ number_format($totalBulanIni, 0, ',', '.') }}</h4>
        </div>
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
            <p class="text-xs font-semibold text-gray-500 uppercase">Pakan Ayam</p>
            <h4 class="text-xl font-bold text-amber-600 mt-1">Rp {{ number_format($byCategory['pakan'] ?? 0, 0, ',', '.') }}</h4>
        </div>
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
            <p class="text-xs font-semibold text-gray-500 uppercase">Bibit DOC</p>
            <h4 class="text-xl font-bold text-teal-600 mt-1">Rp {{ number_format($byCategory['doc'] ?? 0, 0, ',', '.') }}</h4>
        </div>
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
            <p class="text-xs font-semibold text-gray-500 uppercase">Obat & Vaksin</p>
            <h4 class="text-xl font-bold text-purple-600 mt-1">Rp {{ number_format($byCategory['obat'] ?? 0, 0, ',', '.') }}</h4>
        </div>
    </div>

    {{-- Card Main --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        {{-- Filter --}}
        <div class="p-4 bg-gray-50 border-b border-gray-200">
            <form method="GET" action="{{ route('farm.expenses.index') }}" class="flex flex-wrap gap-3 items-center">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari keterangan..."
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-primary-500 w-full sm:w-64">

                <select name="category" class="border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white">
                    <option value="">-- Semua Kategori --</option>
                    <option value="pakan" @selected(request('category')=='pakan')>Pakan Ayam</option>
                    <option value="doc" @selected(request('category')=='doc')>Bibit DOC</option>
                    <option value="obat" @selected(request('category')=='obat')>Obat / Vaksin</option>
                    <option value="operasional" @selected(request('category')=='operasional')>Biaya Operasional</option>
                    <option value="peralatan" @selected(request('category')=='peralatan')>Peralatan & Maintenance</option>
                    <option value="lainnya" @selected(request('category')=='lainnya')>Lain-lain</option>
                </select>

                <button type="submit" class="bg-primary-600 text-white font-bold px-4 py-2 rounded-lg hover:bg-primary-700 text-sm">Filter</button>
                @if(request('search') || request('category'))
                    <a href="{{ route('farm.expenses.index') }}" class="text-gray-500 hover:underline text-sm ml-1">Reset</a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead>
                    <tr class="bg-gray-100 border-b border-gray-200 text-xs text-gray-600 uppercase font-semibold">
                        <th class="p-3 text-left">Tanggal</th>
                        <th class="p-3 text-left">Kategori</th>
                        <th class="p-3 text-left">Keterangan</th>
                        <th class="p-3 text-left">Supplier</th>
                        <th class="p-3 text-left">Metode</th>
                        <th class="p-3 text-right">Nominal</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($expenses as $e)
                    <tr class="hover:bg-gray-50">
                        <td class="p-3 font-semibold text-gray-900">{{ \Carbon\Carbon::parse($e->expense_date)->format('d/m/Y') }}</td>
                        <td class="p-3 text-xs">
                            <span class="bg-gray-100 text-gray-800 px-2 py-0.5 rounded font-bold uppercase">
                                {{ strtoupper($e->category) }}
                            </span>
                        </td>
                        <td class="p-3 font-medium">
                            {{ $e->description }}
                            @if($e->notes)<div class="text-xs text-gray-500">{{ $e->notes }}</div>@endif
                        </td>
                        <td class="p-3 text-xs text-gray-700">{{ $e->supplier->name ?? '-' }}</td>
                        <td class="p-3 text-xs uppercase font-semibold">{{ $e->payment_method ?? 'CASH' }}</td>
                        <td class="p-3 text-right font-mono font-bold text-red-600">Rp {{ number_format($e->amount, 0, ',', '.') }}</td>
                        <td class="p-3 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('farm.expenses.edit', $e) }}" class="text-amber-600 border border-amber-300 hover:bg-amber-50 text-xs px-2 py-1 rounded">Edit</a>
                                <form action="{{ route('farm.expenses.destroy', $e) }}" method="POST" class="inline delete-confirm">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 border border-red-300 hover:bg-red-50 text-xs px-2 py-1 rounded">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="p-6 text-center text-gray-500">Belum ada catatan pengeluaran.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($expenses->hasPages())
        <div class="p-4 border-t border-gray-200">{{ $expenses->links() }}</div>
        @endif
    </div>
</x-farm-layout>
