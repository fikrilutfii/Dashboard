<x-farm-layout title="Log Transportasi Peternakan" subtitle="Pengiriman Hasil Panen & Penerimaan Bibit DOC / Pakan">
    <x-slot name="headerActions">
        <a href="{{ route('farm.transportation.create') }}" class="bg-green-600 text-white font-bold px-4 py-2 rounded-lg hover:bg-green-700 text-sm shadow">
            + Catat Transportasi
        </a>
    </x-slot>

    {{-- Stat Card --}}
    <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm max-w-sm mb-4">
        <p class="text-xs font-semibold text-gray-500 uppercase">Biaya Transportasi Bulan Ini</p>
        <h4 class="text-xl font-bold text-blue-600 mt-1">Rp {{ number_format($totalBulanIni, 0, ',', '.') }}</h4>
    </div>

    {{-- Card Main --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        {{-- Filter --}}
        <div class="p-4 bg-gray-50 border-b border-gray-200">
            <form method="GET" action="{{ route('farm.transportation.index') }}" class="flex flex-wrap gap-3 items-center">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari deskripsi, tujuan, pengemudi..."
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-primary-500 w-full sm:w-64">

                <select name="type" class="border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white">
                    <option value="">-- Semua Tipe --</option>
                    <option value="masuk" @selected(request('type')=='masuk')>Masuk (Bibit / Pakan)</option>
                    <option value="keluar" @selected(request('type')=='keluar')>Keluar (Panen)</option>
                </select>

                <button type="submit" class="bg-primary-600 text-white font-bold px-4 py-2 rounded-lg hover:bg-primary-700 text-sm">Filter</button>
                @if(request('search') || request('type'))
                    <a href="{{ route('farm.transportation.index') }}" class="text-gray-500 hover:underline text-sm ml-1">Reset</a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead>
                    <tr class="bg-gray-100 border-b border-gray-200 text-xs text-gray-600 uppercase font-semibold">
                        <th class="p-3 text-left">Tanggal</th>
                        <th class="p-3 text-left">Tipe</th>
                        <th class="p-3 text-left">Deskripsi</th>
                        <th class="p-3 text-left">Tujuan / Asal</th>
                        <th class="p-3 text-left">Pengemudi & No. Plat</th>
                        <th class="p-3 text-right">Biaya</th>
                        <th class="p-3 text-center">Status</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($transportations as $t)
                    <tr class="hover:bg-gray-50">
                        <td class="p-3 font-semibold text-gray-900">{{ \Carbon\Carbon::parse($t->transport_date)->format('d/m/Y') }}</td>
                        <td class="p-3">
                            @if($t->type === 'masuk')
                                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded font-bold uppercase">Masuk</span>
                            @else
                                <span class="bg-amber-100 text-amber-800 text-xs px-2 py-0.5 rounded font-bold uppercase">Keluar (Panen)</span>
                            @endif
                        </td>
                        <td class="p-3 font-medium">
                            {{ $t->description }}
                            @if($t->notes)<div class="text-xs text-gray-500">{{ $t->notes }}</div>@endif
                        </td>
                        <td class="p-3 text-xs text-gray-700">{{ $t->destination ?? '-' }}</td>
                        <td class="p-3 text-xs">
                            <div class="font-semibold">{{ $t->driver ?? '-' }}</div>
                            @if($t->vehicle_plate)<div class="text-gray-500">{{ $t->vehicle_plate }}</div>@endif
                        </td>
                        <td class="p-3 text-right font-mono font-bold text-blue-600">Rp {{ number_format($t->amount, 0, ',', '.') }}</td>
                        <td class="p-3 text-center">
                            @if($t->status === 'selesai')
                                <span class="bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded-full font-bold uppercase">Selesai</span>
                            @else
                                <span class="bg-amber-100 text-amber-800 text-xs px-2 py-0.5 rounded-full font-bold uppercase">Proses</span>
                            @endif
                        </td>
                        <td class="p-3 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <a href="{{ route('farm.transportation.edit', $t) }}" class="text-amber-600 border border-amber-300 hover:bg-amber-50 text-xs px-2 py-1 rounded">Edit</a>
                                <form action="{{ route('farm.transportation.destroy', $t) }}" method="POST" class="inline delete-confirm">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 border border-red-300 hover:bg-red-50 text-xs px-2 py-1 rounded">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="p-6 text-center text-gray-500">Belum ada log transportasi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transportations->hasPages())
        <div class="p-4 border-t border-gray-200">{{ $transportations->links() }}</div>
        @endif
    </div>
</x-farm-layout>
