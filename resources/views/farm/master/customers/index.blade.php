<x-farm-layout title="Master Pembeli Panen" subtitle="Daftar Pembeli / Klien Panen Peternakan Ayam">
    <x-slot name="headerActions">
        <a href="{{ route('farm.master.customers.create') }}" class="bg-green-600 text-white font-bold px-4 py-2 rounded-lg hover:bg-green-700 text-sm shadow">
            + Tambah Pembeli
        </a>
    </x-slot>

    {{-- Card Main --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        {{-- Filter --}}
        <div class="p-4 bg-gray-50 border-b border-gray-200">
            <form method="GET" action="{{ route('farm.master.customers.index') }}" class="flex flex-wrap gap-3 items-center">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pembeli, telepon, kota..."
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-primary-500 w-full sm:w-64">

                <button type="submit" class="bg-primary-600 text-white font-bold px-4 py-2 rounded-lg hover:bg-primary-700 text-sm">Filter</button>
                @if(request('search'))
                    <a href="{{ route('farm.master.customers.index') }}" class="text-gray-500 hover:underline text-sm ml-1">Reset</a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead>
                    <tr class="bg-gray-100 border-b border-gray-200 text-xs text-gray-600 uppercase font-semibold">
                        <th class="p-3 text-left">Nama Pembeli</th>
                        <th class="p-3 text-left">Telepon / Kontak</th>
                        <th class="p-3 text-left">Kota</th>
                        <th class="p-3 text-left">Alamat</th>
                        <th class="p-3 text-center">Total Faktur</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($customers as $customer)
                    <tr class="hover:bg-gray-50">
                        <td class="p-3 font-semibold text-gray-900">
                            {{ $customer->name }}
                            @if($customer->contact_person)
                                <div class="text-xs text-gray-500 font-normal">CP: {{ $customer->contact_person }}</div>
                            @endif
                        </td>
                        <td class="p-3 text-xs text-gray-700">{{ $customer->phone ?? '-' }}</td>
                        <td class="p-3 text-xs">{{ $customer->city ?? '-' }}</td>
                        <td class="p-3 text-xs text-gray-600 max-w-[200px] truncate">{{ $customer->address ?? '-' }}</td>
                        <td class="p-3 text-center text-xs font-semibold">{{ $customer->invoices_count ?? 0 }} Faktur</td>
                        <td class="p-3 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('farm.master.customers.edit', $customer) }}" class="text-amber-600 border border-amber-300 hover:bg-amber-50 text-xs px-2 py-1 rounded">Edit</a>
                                <form action="{{ route('farm.master.customers.destroy', $customer) }}" method="POST" class="inline delete-confirm">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 border border-red-300 hover:bg-red-50 text-xs px-2 py-1 rounded">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="p-6 text-center text-gray-500">Belum ada data pembeli.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($customers->hasPages())
        <div class="p-4 border-t border-gray-200">{{ $customers->links() }}</div>
        @endif
    </div>
</x-farm-layout>
