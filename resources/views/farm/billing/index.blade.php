<x-farm-layout title="Tagihan Klien & Piutang" subtitle="Rekapitulasi Piutang Penjualan Panen Ayam per Pembeli">

    {{-- Total Outstanding Card --}}
    <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm max-w-sm mb-6">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Piutang Belum Lunas</p>
        <h3 class="text-2xl font-bold text-red-600 mt-1">Rp {{ number_format($totalOutstanding, 0, ',', '.') }}</h3>
    </div>

    {{-- Search --}}
    <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm mb-6">
        <form method="GET" action="{{ route('farm.billing.index') }}" class="flex flex-wrap gap-3 items-center">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pembeli / klien..."
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:border-primary-500 flex-1 min-w-[200px]">
            <button type="submit" class="bg-primary-600 text-white font-bold px-4 py-2 rounded-lg hover:bg-primary-700 text-sm">Cari</button>
            @if(request('search'))
                <a href="{{ route('farm.billing.index') }}" class="text-gray-500 hover:underline text-sm">Reset</a>
            @endif
        </form>
    </div>

    {{-- Customer Billing Blocks --}}
    <div class="space-y-6" x-data="{ openPaymentModal: false, activeInvoiceId: null, activeInvoiceNum: '', activeRemaining: 0 }">
        @forelse($customers as $customer)
        @php $custOutstanding = $customer->invoices->sum(fn($inv) => $inv->total_amount - $inv->paid_amount); @endphp
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between p-4 bg-gray-50 border-b border-gray-200">
                <div>
                    <h3 class="font-bold text-gray-900 text-base">{{ $customer->name }}</h3>
                    <p class="text-xs text-gray-500">
                        @if($customer->phone)Telp: {{ $customer->phone }}@endif
                        @if($customer->city) · {{ $customer->city }}@endif
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-xs font-semibold text-gray-500 uppercase">Total Piutang</p>
                    <p class="text-lg font-bold text-red-600 font-mono">Rp {{ number_format($custOutstanding, 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                    <thead>
                        <tr class="bg-gray-100 border-b border-gray-200 text-xs text-gray-600 uppercase font-semibold">
                            <th class="p-3 text-left">No. Faktur</th>
                            <th class="p-3 text-left">Tanggal</th>
                            <th class="p-3 text-left">Kandang</th>
                            <th class="p-3 text-right">Total Tagihan</th>
                            <th class="p-3 text-right">Terbayar</th>
                            <th class="p-3 text-right">Sisa Piutang</th>
                            <th class="p-3 text-center">Status</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($customer->invoices as $inv)
                        @php $rem = $inv->total_amount - $inv->paid_amount; @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 font-semibold font-mono text-primary-600">
                                <a href="{{ route('farm.invoices.show', $inv) }}">{{ $inv->invoice_number }}</a>
                            </td>
                            <td class="p-3 text-xs text-gray-600">{{ \Carbon\Carbon::parse($inv->invoice_date)->format('d/m/Y') }}</td>
                            <td class="p-3 text-xs">{{ $inv->coop->name ?? '-' }}</td>
                            <td class="p-3 text-right font-mono font-bold">Rp {{ number_format($inv->total_amount, 0, ',', '.') }}</td>
                            <td class="p-3 text-right font-mono text-green-600">Rp {{ number_format($inv->paid_amount, 0, ',', '.') }}</td>
                            <td class="p-3 text-right font-mono font-bold text-red-600">Rp {{ number_format($rem, 0, ',', '.') }}</td>
                            <td class="p-3 text-center">
                                @if($inv->status === 'sebagian')
                                    <span class="bg-amber-100 text-amber-800 text-xs px-2 py-0.5 rounded-full font-bold uppercase">Sebagian</span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs px-2 py-0.5 rounded-full font-bold uppercase">Belum Lunas</span>
                                @endif
                            </td>
                            <td class="p-3 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button type="button" @click="openPaymentModal = true; activeInvoiceId = {{ $inv->id }}; activeInvoiceNum = '{{ $inv->invoice_number }}'; activeRemaining = {{ $rem }};"
                                        class="bg-green-600 text-white text-xs px-2.5 py-1 rounded font-bold hover:bg-green-700">+ Bayar</button>
                                    <a href="{{ route('farm.invoices.show', $inv) }}" class="text-blue-600 hover:underline text-xs border border-blue-200 px-2 py-1 rounded bg-blue-50">Detail</a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl border border-gray-200 p-8 text-center text-gray-500 text-sm">
            Tidak ada tagihan atau piutang yang belum lunas.
        </div>
        @endforelse

        {{-- Payment Modal --}}
        <div x-show="openPaymentModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" x-cloak>
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 mx-4">
                <h3 class="text-lg font-bold text-gray-900 mb-1">Input Pembayaran Tagihan</h3>
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
