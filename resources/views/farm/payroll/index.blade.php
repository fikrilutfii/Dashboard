<x-farm-layout title="Penggajian Pegawai Peternakan" subtitle="Pencatatan Gaji, Tunjangan, Potongan & Status Pembayaran Pegawai">
    <x-slot name="headerActions">
        <a href="{{ route('farm.payroll.create') }}" class="bg-green-600 text-white font-bold px-4 py-2 rounded-lg hover:bg-green-700 text-sm shadow">
            + Tambah Gaji Pegawai
        </a>
    </x-slot>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Gaji Terbayar (Bulan Ini)</p>
            <h3 class="text-2xl font-bold text-emerald-600 mt-1">Rp {{ number_format($totalBulanIni, 0, ',', '.') }}</h3>
        </div>
        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Gaji Pending</p>
            <h3 class="text-2xl font-bold text-amber-600 mt-1">Rp {{ number_format($totalPending, 0, ',', '.') }}</h3>
        </div>
    </div>

    {{-- Card Main --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        {{-- Filter Section --}}
        <div class="p-4 bg-gray-50 border-b border-gray-200">
            <form method="GET" action="{{ route('farm.payroll.index') }}" class="flex flex-wrap gap-3 items-center">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pegawai / jabatan..."
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-primary-500 w-full sm:w-64">

                <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white">
                    <option value="">-- Semua Status --</option>
                    <option value="pending" @selected(request('status') == 'pending')>Belum Dibayar (Pending)</option>
                    <option value="dibayar" @selected(request('status') == 'dibayar')>Sudah Dibayar</option>
                </select>

                <button type="submit" class="bg-primary-600 text-white font-bold px-4 py-2 rounded-lg hover:bg-primary-700 text-sm">Filter</button>
                @if(request('search') || request('status'))
                    <a href="{{ route('farm.payroll.index') }}" class="text-gray-500 hover:underline text-sm ml-1">Reset</a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead>
                    <tr class="bg-gray-100 border-b border-gray-200 text-xs text-gray-600 uppercase font-semibold">
                        <th class="p-3 text-left">Nama Pegawai</th>
                        <th class="p-3 text-left">Jabatan</th>
                        <th class="p-3 text-left">Periode Gaji</th>
                        <th class="p-3 text-right">Gaji Pokok</th>
                        <th class="p-3 text-right">Tunjangan</th>
                        <th class="p-3 text-right">Potongan</th>
                        <th class="p-3 text-right">Gaji Bersih</th>
                        <th class="p-3 text-center">Status</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($payrolls as $p)
                    <tr class="hover:bg-gray-50">
                        <td class="p-3">
                            <div class="font-bold text-gray-900">{{ $p->employee_name }}</div>
                            @if($p->notes)
                                <div class="text-xs text-gray-500 mt-0.5">{{ $p->notes }}</div>
                            @endif
                        </td>
                        <td class="p-3 text-xs">
                            <span class="bg-gray-100 text-gray-700 px-2 py-0.5 rounded border border-gray-200 uppercase font-semibold">
                                {{ $p->role ?? 'Staf Lapangan' }}
                            </span>
                        </td>
                        <td class="p-3 text-xs text-gray-600">
                            {{ \Carbon\Carbon::parse($p->period_start)->format('d/m/Y') }} – {{ \Carbon\Carbon::parse($p->period_end)->format('d/m/Y') }}
                        </td>
                        <td class="p-3 text-right font-mono">Rp {{ number_format($p->basic_salary, 0, ',', '.') }}</td>
                        <td class="p-3 text-right font-mono text-emerald-600">+{{ number_format($p->allowances, 0, ',', '.') }}</td>
                        <td class="p-3 text-right font-mono text-red-600">−{{ number_format($p->deductions, 0, ',', '.') }}</td>
                        <td class="p-3 text-right font-mono font-bold text-gray-900">Rp {{ number_format($p->net_salary, 0, ',', '.') }}</td>
                        <td class="p-3 text-center">
                            @if($p->status === 'dibayar')
                                <span class="bg-green-100 text-green-800 text-xs px-2.5 py-1 rounded-full font-bold uppercase">
                                    Dibayar ({{ \Carbon\Carbon::parse($p->paid_at)->format('d/m') }})
                                </span>
                            @else
                                <span class="bg-amber-100 text-amber-800 text-xs px-2.5 py-1 rounded-full font-bold uppercase">
                                    Pending
                                </span>
                            @endif
                        </td>
                        <td class="p-3 text-center">
                            <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                @if($p->status !== 'dibayar')
                                <form action="{{ route('farm.payroll.mark-paid', $p) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="bg-green-600 text-white text-xs px-2.5 py-1 rounded font-semibold hover:bg-green-700">✓ Lunasi</button>
                                </form>
                                @endif
                                <a href="{{ route('farm.payroll.edit', $p) }}" class="text-amber-600 border border-amber-300 hover:bg-amber-50 text-xs px-2 py-1 rounded font-medium">Edit</a>
                                <form action="{{ route('farm.payroll.destroy', $p) }}" method="POST" class="inline-block delete-confirm">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 border border-red-300 hover:bg-red-50 text-xs px-2 py-1 rounded font-medium">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="p-6 text-center text-gray-500">Belum ada data penggajian pegawai peternakan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($payrolls->hasPages())
        <div class="p-4 border-t border-gray-200">{{ $payrolls->links() }}</div>
        @endif
    </div>
</x-farm-layout>
