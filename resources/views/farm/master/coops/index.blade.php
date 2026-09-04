<x-farm-layout title="Data Kandang Peternakan" subtitle="Daftar Unit Kandang & Status Operasional">
    <x-slot name="headerActions">
        <a href="{{ route('farm.master.coops.create') }}" class="bg-green-600 text-white font-bold px-4 py-2 rounded-lg hover:bg-green-700 text-sm shadow">
            + Tambah Kandang
        </a>
    </x-slot>

    {{-- Card Main --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        {{-- Filter --}}
        <div class="p-4 bg-gray-50 border-b border-gray-200">
            <form method="GET" action="{{ route('farm.master.coops.index') }}" class="flex flex-wrap gap-3 items-center">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama kandang / lokasi..."
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-primary-500 w-full sm:w-64">

                <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white">
                    <option value="">-- Semua Status --</option>
                    <option value="aktif" @selected(request('status')=='aktif')>Aktif</option>
                    <option value="pemeliharaan" @selected(request('status')=='pemeliharaan')>Dalam Pemeliharaan</option>
                    <option value="non_aktif" @selected(request('status')=='non_aktif')>Tidak Aktif</option>
                </select>

                <button type="submit" class="bg-primary-600 text-white font-bold px-4 py-2 rounded-lg hover:bg-primary-700 text-sm">Filter</button>
                @if(request('search') || request('status'))
                    <a href="{{ route('farm.master.coops.index') }}" class="text-gray-500 hover:underline text-sm ml-1">Reset</a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead>
                    <tr class="bg-gray-100 border-b border-gray-200 text-xs text-gray-600 uppercase font-semibold">
                        <th class="p-3 text-left">Nama Kandang</th>
                        <th class="p-3 text-center">Kapasitas</th>
                        <th class="p-3 text-left">Lokasi</th>
                        <th class="p-3 text-center">Status</th>
                        <th class="p-3 text-center">Total Log Harian</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($coops as $coop)
                    <tr class="hover:bg-gray-50">
                        <td class="p-3 font-semibold text-gray-900">
                            {{ $coop->name }}
                            @if($coop->notes)<div class="text-xs text-gray-500">{{ $coop->notes }}</div>@endif
                        </td>
                        <td class="p-3 text-center font-bold text-amber-600 font-mono">{{ number_format($coop->capacity) }} Ekor</td>
                        <td class="p-3 text-xs text-gray-700">{{ $coop->location ?? '-' }}</td>
                        <td class="p-3 text-center">
                            @switch($coop->status)
                                @case('aktif')
                                    <span class="bg-green-100 text-green-800 text-xs px-2.5 py-1 rounded-full font-bold uppercase">Aktif</span>
                                    @break
                                @case('pemeliharaan')
                                    <span class="bg-amber-100 text-amber-800 text-xs px-2.5 py-1 rounded-full font-bold uppercase">Pemeliharaan</span>
                                    @break
                                @default
                                    <span class="bg-gray-100 text-gray-700 text-xs px-2.5 py-1 rounded-full font-bold uppercase">Tidak Aktif</span>
                            @endswitch
                        </td>
                        <td class="p-3 text-center text-xs font-semibold">{{ $coop->operational_logs_count ?? 0 }} Log</td>
                        <td class="p-3 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('farm.master.coops.edit', $coop) }}" class="text-amber-600 border border-amber-300 hover:bg-amber-50 text-xs px-2 py-1 rounded">Edit</a>
                                <form action="{{ route('farm.master.coops.destroy', $coop) }}" method="POST" class="inline delete-confirm">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 border border-red-300 hover:bg-red-50 text-xs px-2 py-1 rounded">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="p-6 text-center text-gray-500">Belum ada data kandang.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($coops->hasPages())
        <div class="p-4 border-t border-gray-200">{{ $coops->links() }}</div>
        @endif
    </div>
</x-farm-layout>
