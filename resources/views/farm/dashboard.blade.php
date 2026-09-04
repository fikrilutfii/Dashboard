<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Peternakan Ayam
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Penjualan Bulan Ini</p>
                    <h3 class="text-2xl font-bold text-amber-600 mt-1">Rp {{ number_format($penjualanBulanIni, 0, ',', '.') }}</h3>
                    <a href="{{ route('farm.invoices.index') }}" class="text-xs text-amber-600 hover:underline mt-2 inline-block font-semibold">Lihat Faktur →</a>
                </div>

                <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Sisa Piutang Klien</p>
                    <h3 class="text-2xl font-bold text-red-600 mt-1">Rp {{ number_format($totalTagihanBelumLunas, 0, ',', '.') }}</h3>
                    <a href="{{ route('farm.billing.index') }}" class="text-xs text-red-600 hover:underline mt-2 inline-block font-semibold">Lihat Tagihan →</a>
                </div>

                <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pengeluaran Bulan Ini</p>
                    <h3 class="text-2xl font-bold text-purple-600 mt-1">Rp {{ number_format($pengeluaranBulanIni, 0, ',', '.') }}</h3>
                    <a href="{{ route('farm.expenses.index') }}" class="text-xs text-purple-600 hover:underline mt-2 inline-block font-semibold">Lihat Pengeluaran →</a>
                </div>

                <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Keuntungan Bersih Bulan Ini</p>
                    <h3 class="text-2xl font-bold {{ $keuntunganBulanIni >= 0 ? 'text-emerald-600' : 'text-red-600' }} mt-1">Rp {{ number_format(max(0, $keuntunganBulanIni), 0, ',', '.') }}</h3>
                    <span class="text-xs text-gray-400 mt-2 inline-block">(Penjualan - Pengeluaran)</span>
                </div>

                <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Biaya Transportasi Bulan Ini</p>
                    <h3 class="text-2xl font-bold text-blue-600 mt-1">Rp {{ number_format($transportasiBulanIni, 0, ',', '.') }}</h3>
                    <a href="{{ route('farm.transportation.index') }}" class="text-xs text-blue-600 hover:underline mt-2 inline-block font-semibold">Lihat Transportasi →</a>
                </div>

                <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Status Kandang Aktif</p>
                    <h3 class="text-2xl font-bold text-teal-600 mt-1">{{ $kandangAktif }} / {{ $totalKandang }} Unit</h3>
                    <a href="{{ route('farm.master.coops.index') }}" class="text-xs text-teal-600 hover:underline mt-2 inline-block font-semibold">Kelola Kandang →</a>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-4">Aksi Cepat Menu Utama</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
                    <a href="{{ route('farm.invoices.create') }}" class="p-3 text-center bg-amber-50 hover:bg-amber-100 border border-amber-200 rounded-lg text-amber-900 font-bold text-xs transition">
                        + Buat Faktur
                    </a>
                    <a href="{{ route('farm.operational.create') }}" class="p-3 text-center bg-teal-50 hover:bg-teal-100 border border-teal-200 rounded-lg text-teal-900 font-bold text-xs transition">
                        + Log Operasional
                    </a>
                    <a href="{{ route('farm.expenses.create') }}" class="p-3 text-center bg-purple-50 hover:bg-purple-100 border border-purple-200 rounded-lg text-purple-900 font-bold text-xs transition">
                        + Catat Pengeluaran
                    </a>
                    <a href="{{ route('farm.transportation.create') }}" class="p-3 text-center bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg text-blue-900 font-bold text-xs transition">
                        + Transportasi
                    </a>
                    <a href="{{ route('farm.payroll.create') }}" class="p-3 text-center bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 rounded-lg text-emerald-900 font-bold text-xs transition">
                        + Buat Gaji
                    </a>
                    <a href="{{ route('farm.master.coops.create') }}" class="p-3 text-center bg-gray-50 hover:bg-gray-100 border border-gray-200 rounded-lg text-gray-900 font-bold text-xs transition">
                        + Tambah Kandang
                    </a>
                </div>
            </div>

            {{-- Recent Data --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Faktur Terbaru --}}
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="p-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="font-bold text-gray-800 text-sm">Faktur Penjualan Terbaru</h3>
                        <a href="{{ route('farm.invoices.index') }}" class="text-xs text-primary-600 hover:underline font-semibold">Lihat Semua</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100 border-b border-gray-200 text-xs font-semibold text-gray-600 uppercase">
                                <tr>
                                    <th class="p-3 text-left">No. Faktur</th>
                                    <th class="p-3 text-left">Pembeli</th>
                                    <th class="p-3 text-right">Total</th>
                                    <th class="p-3 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($recentInvoices as $inv)
                                <tr class="hover:bg-gray-50">
                                    <td class="p-3 font-semibold text-primary-600">
                                        <a href="{{ route('farm.invoices.show', $inv) }}">{{ $inv->invoice_number }}</a>
                                    </td>
                                    <td class="p-3">{{ $inv->customer->name ?? '-' }}</td>
                                    <td class="p-3 text-right font-mono font-bold">Rp {{ number_format($inv->total_amount, 0, ',', '.') }}</td>
                                    <td class="p-3 text-center">
                                        @if($inv->status === 'lunas')
                                            <span class="bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded-full font-bold">Lunas</span>
                                        @elseif($inv->status === 'sebagian')
                                            <span class="bg-amber-100 text-amber-800 text-xs px-2 py-0.5 rounded-full font-bold">Sebagian</span>
                                        @else
                                            <span class="bg-red-100 text-red-800 text-xs px-2 py-0.5 rounded-full font-bold">Belum Lunas</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="p-4 text-center text-gray-500">Belum ada faktur penjualan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Pengeluaran Terbaru --}}
                <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="p-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="font-bold text-gray-800 text-sm">Pengeluaran Terbaru</h3>
                        <a href="{{ route('farm.expenses.index') }}" class="text-xs text-primary-600 hover:underline font-semibold">Lihat Semua</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100 border-b border-gray-200 text-xs font-semibold text-gray-600 uppercase">
                                <tr>
                                    <th class="p-3 text-left">Tanggal</th>
                                    <th class="p-3 text-left">Keterangan</th>
                                    <th class="p-3 text-left">Kategori</th>
                                    <th class="p-3 text-right">Nominal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($recentExpenses as $exp)
                                <tr class="hover:bg-gray-50">
                                    <td class="p-3 text-xs text-gray-500">{{ \Carbon\Carbon::parse($exp->expense_date)->format('d/m/Y') }}</td>
                                    <td class="p-3 font-semibold">{{ $exp->description }}</td>
                                    <td class="p-3 text-xs uppercase">{{ $exp->category }}</td>
                                    <td class="p-3 text-right font-mono font-bold text-red-600">Rp {{ number_format($exp->amount, 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="p-4 text-center text-gray-500">Belum ada pengeluaran.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
