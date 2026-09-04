<x-farm-layout title="Faktur Penjualan Peternakan" subtitle="Daftar & Pengelolaan Faktur Penjualan Panen Ayam">
    <x-slot name="headerActions">
        <a href="{{ route('farm.invoices.create') }}" class="bg-green-600 text-white font-bold px-4 py-2 rounded-lg hover:bg-green-700 text-sm shadow">
            + Buat Faktur Baru
        </a>
    </x-slot>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
            <p class="text-xs font-semibold text-gray-500 uppercase">Total Faktur</p>
            <h4 class="text-xl font-bold text-gray-800 mt-1">{{ number_format($stats['total']) }}</h4>
        </div>
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
            <p class="text-xs font-semibold text-gray-500 uppercase">Belum Lunas</p>
            <h4 class="text-xl font-bold text-amber-600 mt-1">{{ number_format($stats['belum_lunas']) }}</h4>
        </div>
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
            <p class="text-xs font-semibold text-gray-500 uppercase">Faktur Lunas</p>
            <h4 class="text-xl font-bold text-emerald-600 mt-1">{{ number_format($stats['lunas']) }}</h4>
        </div>
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
            <p class="text-xs font-semibold text-gray-500 uppercase">Total Sisa Piutang</p>
            <h4 class="text-xl font-bold text-red-600 mt-1">Rp {{ number_format($stats['outstanding'], 0, ',', '.') }}</h4>
        </div>
    </div>

    {{-- Card Main --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden" x-data="{ openPaymentModal: false, activeInvoiceId: null, activeInvoiceNum: '', activeRemaining: 0 }">
        {{-- Filter Section --}}
        <div class="p-4 bg-gray-50 border-b border-gray-200">
            <form method="GET" action="{{ route('farm.invoices.index') }}" class="flex flex-wrap gap-3 items-center">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No. Faktur / Pembeli..."
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-primary-500 w-full sm:w-64">

                <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white">
                    <option value="">-- Semua Status --</option>
                    <option value="belum_lunas" @selected(request('status')=='belum_lunas')>Belum Lunas</option>
                    <option value="sebagian" @selected(request('status')=='sebagian')>Bayar Sebagian</option>
                    <option value="lunas" @selected(request('status')=='lunas')>Lunas</option>
                </select>

                <input type="date" name="date_from" value="{{ request('date_from') }}" class="border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white">
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white">

                <button type="submit" class="bg-primary-600 text-white font-bold px-4 py-2 rounded-lg hover:bg-primary-700 text-sm">Filter</button>
                @if(request('search') || request('status') || request('date_from') || request('date_to'))
                    <a href="{{ route('farm.invoices.index') }}" class="text-gray-500 hover:underline text-sm ml-1">Reset</a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead>
                    <tr class="bg-gray-100 border-b border-gray-200 text-xs text-gray-600 uppercase font-semibold">
                        <th class="p-3 text-left">No. Faktur</th>
                        <th class="p-3 text-left">Tanggal</th>
                        <th class="p-3 text-left">Pembeli</th>
                        <th class="p-3 text-left">Kandang</th>
                        <th class="p-3 text-right">Total Tagihan</th>
                        <th class="p-3 text-right">Terbayar</th>
                        <th class="p-3 text-right">Sisa Piutang</th>
                        <th class="p-3 text-center">Status</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($invoices as $inv)
                    @php $remaining = $inv->total_amount - $inv->paid_amount; @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="p-3 font-semibold font-mono text-primary-600">
                            <a href="{{ route('farm.invoices.show', $inv) }}">{{ $inv->invoice_number }}</a>
                        </td>
                        <td class="p-3 text-gray-600 text-xs">{{ \Carbon\Carbon::parse($inv->invoice_date)->format('d/m/Y') }}</td>
                        <td class="p-3 font-medium">{{ $inv->customer->name ?? '-' }}</td>
                        <td class="p-3 text-xs">{{ $inv->coop->name ?? '-' }}</td>
                        <td class="p-3 text-right font-mono font-bold">Rp {{ number_format($inv->total_amount, 0, ',', '.') }}</td>
                        <td class="p-3 text-right font-mono text-green-600">Rp {{ number_format($inv->paid_amount, 0, ',', '.') }}</td>
                        <td class="p-3 text-right font-mono font-bold {{ $remaining > 0 ? 'text-red-600' : 'text-green-600' }}">Rp {{ number_format($remaining, 0, ',', '.') }}</td>
                        <td class="p-3 text-center">
                            @if($inv->status === 'lunas')
                                <span class="bg-green-100 text-green-800 text-xs px-2.5 py-1 rounded-full font-bold uppercase">Lunas</span>
                            @elseif($inv->status === 'sebagian')
                                <span class="bg-amber-100 text-amber-800 text-xs px-2.5 py-1 rounded-full font-bold uppercase">Sebagian</span>
                            @else
                                <span class="bg-red-100 text-red-800 text-xs px-2.5 py-1 rounded-full font-bold uppercase">Belum Lunas</span>
                            @endif
                        </td>
                        <td class="p-3 text-center">
                            <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                <a href="{{ route('farm.invoices.show', $inv) }}" class="text-blue-600 hover:underline text-xs border border-blue-200 px-2 py-1 rounded bg-blue-50">Lihat</a>
                                @if($inv->status !== 'lunas')
                                <button type="button" @click="openPaymentModal = true; activeInvoiceId = {{ $inv->id }}; activeInvoiceNum = '{{ $inv->invoice_number }}'; activeRemaining = {{ $remaining }};"
                                    class="text-green-600 hover:underline text-xs border border-green-200 px-2 py-1 rounded bg-green-50">+ Bayar</button>
                                @endif
                                <a href="{{ route('farm.invoices.print', $inv) }}" target="_blank" class="text-gray-700 hover:underline text-xs border border-gray-300 px-2 py-1 rounded bg-gray-50">Cetak</a>
                                <a href="{{ route('farm.invoices.edit', $inv) }}" class="text-amber-600 hover:underline text-xs border border-amber-200 px-2 py-1 rounded bg-amber-50">Edit</a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9" class="p-6 text-center text-gray-500">Belum ada faktur penjualan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($invoices->hasPages())
        <div class="p-4 border-t border-gray-200">{{ $invoices->links() }}</div>
        @endif

        {{-- Payment Modal --}}
        <div x-show="openPaymentModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" x-cloak>
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 mx-4">
                <h3 class="text-lg font-bold text-gray-900 mb-1">Input Pembayaran Faktur</h3>
                <p class="text-xs text-gray-500 mb-4">No. Faktur: <strong x-text="activeInvoiceNum" class="text-primary-600"></strong></p>
                <form :action="'{{ url('/farm/invoices') }}/' + activeInvoiceId + '/payment'" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Sisa Piutang</label>
                        <div class="text-xl font-bold text-red-600 font-mono" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(activeRemaining)"></div>
                    </div>
                    <div class="mb-5">
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Nominal Pembayaran (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="amount" required :max="activeRemaining" :value="activeRemaining"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-green-500">
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="openPaymentModal = false" class="border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-100">Batal</button>
                        <button type="submit" class="bg-green-600 text-white font-bold px-4 py-2 rounded-lg hover:bg-green-700 text-sm">Simpan Pembayaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-farm-layout>
