<x-farm-layout title="Master Supplier Peternakan" subtitle="Daftar Supplier DOC, Pakan, Obat, & Peralatan">
    <x-slot name="headerActions">
        <a href="{{ route('farm.master.suppliers.create') }}" class="bg-green-600 text-white font-bold px-4 py-2 rounded-lg hover:bg-green-700 text-sm shadow">
            + Tambah Supplier
        </a>
    </x-slot>

    {{-- Card Main --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        {{-- Filter --}}
        <div class="p-4 bg-gray-50 border-b border-gray-200">
            <form method="GET" action="{{ route('farm.master.suppliers.index') }}" class="flex flex-wrap gap-3 items-center">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama supplier..."
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-primary-500 w-full sm:w-64">

                <select name="type" class="border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white">
                    <option value="">-- Semua Kategori --</option>
                    <option value="doc" @selected(request('type') == 'doc')>DOC (Bibit Ayam)</option>
                    <option value="pakan" @selected(request('type') == 'pakan')>Pakan Ayam</option>
                    <option value="obat" @selected(request('type') == 'obat')>Obat / Vaksin</option>
                    <option value="alat" @selected(request('type') == 'alat')>Peralatan Kandang</option>
                    <option value="lain" @selected(request('type') == 'lain')>Lain-lain</option>
                </select>

                <button type="submit" class="bg-primary-600 text-white font-bold px-4 py-2 rounded-lg hover:bg-primary-700 text-sm">Filter</button>
                @if(request('search') || request('type'))
                    <a href="{{ route('farm.master.suppliers.index') }}" class="text-gray-500 hover:underline text-sm ml-1">Reset</a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead>
                    <tr class="bg-gray-100 border-b border-gray-200 text-xs text-gray-600 uppercase font-semibold">
                        <th class="p-3 text-left">Nama Supplier</th>
                        <th class="p-3 text-left">Kategori</th>
                        <th class="p-3 text-left">Telepon / WA</th>
                        <th class="p-3 text-left">Contact Person</th>
                        <th class="p-3 text-left">Alamat</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($suppliers as $supplier)
                    <tr class="hover:bg-gray-50">
                        <td class="p-3 font-semibold text-gray-900">
                            {{ $supplier->name }}
                            @if($supplier->notes)<div class="text-xs text-gray-500 font-normal">{{ $supplier->notes }}</div>@endif
                        </td>
                        <td class="p-3 text-xs">
                            <span class="bg-gray-100 text-gray-800 px-2 py-0.5 rounded font-bold uppercase">
                                {{ strtoupper($supplier->type) }}
                            </span>
                        </td>
                        <td class="p-3 text-xs text-gray-700">{{ $supplier->phone ?? '-' }}</td>
                        <td class="p-3 text-xs">{{ $supplier->contact_person ?? '-' }}</td>
                        <td class="p-3 text-xs text-gray-600 max-w-[200px] truncate">{{ $supplier->address ?? '-' }}</td>
                        <td class="p-3 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('farm.master.suppliers.edit', $supplier) }}" class="text-amber-600 border border-amber-300 hover:bg-amber-50 text-xs px-2 py-1 rounded">Edit</a>
                                <form action="{{ route('farm.master.suppliers.destroy', $supplier) }}" method="POST" class="inline delete-confirm">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 border border-red-300 hover:bg-red-50 text-xs px-2 py-1 rounded">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="p-6 text-center text-gray-500">Belum ada data supplier.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($suppliers->hasPages())
        <div class="p-4 border-t border-gray-200">{{ $suppliers->links() }}</div>
        @endif
    </div>
</x-farm-layout>
