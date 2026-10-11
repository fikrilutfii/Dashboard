<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs text-zinc-400 font-bold uppercase tracking-wider">
                    <a href="{{ route('reports.billing') }}" class="hover:text-zinc-700 transition-colors">Laporan Tagihan</a>
                    <span>/</span>
                    <span class="text-zinc-600">Rincian Klien</span>
                </div>
                <h2 class="font-extrabold text-2xl text-zinc-800 tracking-tight leading-none mt-1.5">
                    {{ $customer->name }}
                </h2>
            </div>
            
            <div class="flex items-center gap-2">
                <a href="{{ route('reports.billing') }}" class="px-4 py-2 border border-zinc-200 text-zinc-600 text-sm font-semibold rounded-xl hover:bg-zinc-50 hover:text-zinc-800 transition-all shadow-sm">
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 space-y-6 max-w-7xl mx-auto page-enter">
        <!-- Client Detail & Summary Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Client Info -->
            <div class="lg:col-span-2 bg-white p-6 rounded-3xl border border-zinc-100 shadow-premium flex flex-col justify-between">
                <div>
                    <h3 class="text-xs font-black text-zinc-400 uppercase tracking-[0.2em] mb-4">Informasi Klien</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <div>
                                <p class="text-[10px] text-zinc-400 uppercase tracking-widest font-bold">Nama Lengkap</p>
                                <p class="text-sm font-extrabold text-zinc-800 mt-0.5">{{ $customer->name }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-zinc-400 uppercase tracking-widest font-bold">Nomor Telepon</p>
                                <p class="text-sm font-bold text-zinc-800 mt-0.5">{{ $customer->phone ?? 'Tidak ada data' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-zinc-400 uppercase tracking-widest font-bold">Alamat Email</p>
                                <p class="text-sm font-bold text-zinc-800 mt-0.5">{{ $customer->email ?? 'Tidak ada data' }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-[10px] text-zinc-400 uppercase tracking-widest font-bold">Alamat Surat Menyurat</p>
                            <p class="text-sm font-bold text-zinc-700 mt-0.5 whitespace-pre-line leading-relaxed">{{ $customer->address ?? 'Tidak ada data' }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 border-t border-zinc-100 pt-4 flex flex-wrap gap-2 justify-end">
                    <a href="{{ route('customers.show', $customer) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs text-primary-700 bg-primary-50 rounded-xl hover:bg-primary-100 font-bold transition-all border border-primary-100">
                        Buka Profil Klien
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" /></svg>
                    </a>
                </div>
            </div>

            <!-- Total Outstanding Card -->
            <div class="premium-gradient p-8 rounded-3xl shadow-lg shadow-primary-500/30 text-white flex flex-col justify-between">
                <div>
                    <p class="text-[10px] font-black text-primary-200 uppercase tracking-[0.2em] mb-2 italic">Total yang Harus Dibayarkan</p>
                    <h3 class="text-3.5xl font-black tracking-tight leading-none">
                        Rp {{ number_format($totalOutstanding, 0, ',', '.') }}
                    </h3>
                </div>
                <div class="mt-6 border-t border-white/10 pt-4">
                    <div class="flex items-center justify-between text-xs text-primary-100 font-bold">
                        <span>Jumlah Faktur Outstanding:</span>
                        <span>{{ count($invoices) }} Faktur</span>
                    </div>
                    <p class="text-[10px] text-primary-200 mt-2 font-medium">Laporan ini hanya mencakup tagihan yang belum dibayar penuh pada Divisi {{ ucfirst($division ?? 'Semua') }}.</p>
                    @if($totalOutstanding > 0)
                    <div class="mt-4">
                        <button type="button" onclick="openBatchSettleModal()" class="w-full py-2.5 bg-white hover:bg-zinc-100 text-primary-700 font-extrabold text-xs rounded-xl transition-all shadow-md active:scale-95 flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6H2.25m0 0v10.5m0-10.5h19.5m0 0v10.5m0-10.5c0-.414-.336-.75-.75-.75h-.75M21.75 6v10.5m0 0a3.75 3.75 0 01-3.75 3.75H6a3.75 3.75 0 01-3.75-3.75" /></svg>
                            Pelunasan Tagihan Periode Ini
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Toolbar & Export Actions -->
        <div class="flex flex-wrap items-center justify-between gap-4 bg-zinc-50 border border-zinc-100 p-4 rounded-2xl shadow-sm">
            <div class="text-zinc-600 text-xs font-bold uppercase tracking-wider">
                Opsi Tagihan & Ekspor:
            </div>
            <div class="flex flex-wrap items-center gap-2">
                @if($totalOutstanding > 0)
                <button type="button" onclick="openBatchSettleModal()" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-all shadow-sm active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6H2.25m0 0v10.5m0-10.5h19.5m0 0v10.5m0-10.5c0-.414-.336-.75-.75-.75h-.75M21.75 6v10.5m0 0a3.75 3.75 0 01-3.75 3.75H6a3.75 3.75 0 01-3.75-3.75" /></svg>
                    Pelunasan Tagihan Periode Ini
                </button>
                @endif
                <a href="{{ route('reports.billing.print', array_merge(['customer' => $customer->id], request()->query())) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-zinc-200 text-zinc-700 text-xs font-bold rounded-xl hover:bg-zinc-50 transition-all shadow-sm active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-zinc-500"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.82l-.24-2.48a3 3 0 115.96 0l-.24 2.48m-5.48 0H18m-12.72 0L5.25 15.65A1.5 1.5 0 006.74 17.5H17.26a1.5 1.5 0 001.49-1.85L17.28 13.82m0 0H6.72M16.5 10.5h.008v.008h-.008V10.5z" /></svg>
                    Cetak Surat (Browser)
                </a>
                <a href="{{ route('reports.billing.pdf', array_merge(['customer' => $customer->id], request()->query())) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 border border-red-200 text-red-700 text-xs font-bold rounded-xl hover:bg-red-100/50 transition-all shadow-sm active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-red-600"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                    Unduh PDF
                </a>
                <a href="{{ route('reports.billing.excel', array_merge(['customer' => $customer->id], request()->query())) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold rounded-xl hover:bg-emerald-100/50 transition-all shadow-sm active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-emerald-600"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>
                    Unduh Excel
                </a>
            </div>
        </div>

        <!-- Search & Filter Card -->
        <div class="bg-white p-4 rounded-2xl border border-zinc-100 shadow-sm mb-6">
            <form action="{{ route('reports.billing.show', $customer) }}" method="GET" class="flex flex-wrap items-end gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-1.5">Cari Kode/Nama Barang</label>
                    <input type="text" name="item_search" value="{{ request('item_search') }}" placeholder="Ketik kode atau nama..." class="w-full text-sm bg-zinc-50 border border-zinc-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-zinc-700">
                </div>
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-1.5">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full text-sm bg-zinc-50 border border-zinc-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-zinc-700">
                </div>
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-1.5">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full text-sm bg-zinc-50 border border-zinc-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-zinc-700">
                </div>
                <div>
                    <button type="submit" class="h-[42px] px-5 bg-zinc-800 hover:bg-zinc-900 text-white text-sm font-bold rounded-xl transition-all shadow-md active:scale-95 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                        Filter Data
                    </button>
                </div>
                @if(request('item_search') || request('start_date') || request('end_date'))
                <div>
                    <a href="{{ route('reports.billing.show', $customer) }}" class="h-[42px] px-5 bg-zinc-100 hover:bg-zinc-200 text-zinc-700 text-sm font-bold rounded-xl transition-all active:scale-95 flex items-center gap-2">
                        Reset
                    </a>
                </div>
                @endif
            </form>
        </div>

        <!-- Invoice Table Card -->
        <div class="bg-white rounded-3xl border border-zinc-100 shadow-premium overflow-hidden">
            <div class="p-6 border-b border-zinc-100 bg-zinc-50/50">
                <h3 class="text-base font-extrabold text-zinc-800">Daftar Rincian Faktur</h3>
                <p class="text-xs text-zinc-400 mt-1 font-medium">Berikut adalah rincian barang dari faktur yang tercatat.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-zinc-50 border-b border-zinc-100">
                        <tr>
                            <th class="text-center px-6 py-4 text-xs font-semibold text-zinc-500 uppercase tracking-wider">No</th>
                            <th class="text-left px-6 py-4 text-xs font-semibold text-zinc-500 uppercase tracking-wider">No. Faktur</th>
                            <th class="text-center px-6 py-4 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Tanggal</th>
                            <th class="text-left px-6 py-4 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Nama Barang</th>
                            <th class="text-center px-6 py-4 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Qty</th>
                            <th class="text-right px-6 py-4 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Harga</th>
                            <th class="text-right px-6 py-4 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @php $no = 1; $totalSemua = 0; @endphp
                        @forelse($invoices as $invoice)
                            @foreach($invoice->items as $item)
                                @php $totalSemua += $item->subtotal; @endphp
                                <tr class="hover:bg-zinc-50/50 transition-colors">
                                    <td class="px-6 py-4 text-center font-medium text-zinc-600">{{ $no++ }}</td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('invoices.show', $invoice) }}" class="font-bold text-zinc-800 hover:text-primary-600 transition-colors">
                                            #{{ $invoice->invoice_number }}
                                        </a>
                                        @if($invoice->faktur_number)
                                            <p class="text-xs text-zinc-400 font-normal mt-0.5">No. Faktur: {{ $invoice->faktur_number }}</p>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center font-medium text-zinc-600">
                                        {{ $invoice->invoice_date ? $invoice->invoice_date->format('d M Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 font-bold text-zinc-700">
                                        {{ $item->item_name }}
                                    </td>
                                    <td class="px-6 py-4 text-center font-medium text-zinc-600">
                                        {{ $item->formatted_quantity }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-medium text-zinc-500">
                                        Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-zinc-700">
                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-zinc-400">
                                    <p class="font-bold">Tidak ada data rincian faktur</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-zinc-50/50 border-t border-zinc-100 font-black">
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-zinc-700 uppercase tracking-wider text-xs text-right">Total Keseluruhan</td>
                            <td class="px-6 py-4 text-right text-red-600 text-lg">Rp {{ number_format($totalSemua ?? 0, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Riwayat Pelunasan Klien Terakhir -->
        @if(isset($recentPayments) && $recentPayments->count() > 0)
        <div class="bg-white rounded-3xl border border-zinc-100 shadow-premium overflow-hidden">
            <div class="p-6 border-b border-zinc-100 bg-zinc-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                    <h3 class="text-base font-extrabold text-zinc-800">Riwayat Pelunasan Klien Terakhir</h3>
                    <p class="text-xs text-zinc-400 mt-1 font-medium">Pembayaran yang tercatat untuk klien ini. Jika ada kesalahan klik pelunasan, Anda dapat membatalkan atau mengubahnya.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-zinc-50 border-b border-zinc-100">
                        <tr>
                            <th class="text-center px-6 py-3.5 text-xs font-semibold text-zinc-500 uppercase tracking-wider">No</th>
                            <th class="text-center px-6 py-3.5 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Tanggal</th>
                            <th class="text-left px-6 py-3.5 text-xs font-semibold text-zinc-500 uppercase tracking-wider">No. Faktur</th>
                            <th class="text-left px-6 py-3.5 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Metode</th>
                            <th class="text-left px-6 py-3.5 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Referensi</th>
                            <th class="text-left px-6 py-3.5 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Catatan</th>
                            <th class="text-right px-6 py-3.5 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Jumlah</th>
                            <th class="text-center px-6 py-3.5 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @foreach($recentPayments as $idx => $payment)
                        <tr class="hover:bg-zinc-50/50 transition-colors">
                            <td class="px-6 py-3.5 text-center font-medium text-zinc-500">{{ $idx + 1 }}</td>
                            <td class="px-6 py-3.5 text-center font-medium text-zinc-600">
                                {{ $payment->payment_date ? $payment->payment_date->format('d/m/Y') : '-' }}
                            </td>
                            <td class="px-6 py-3.5 font-bold text-zinc-800">
                                @if($payment->invoice)
                                    <a href="{{ route('invoices.show', $payment->invoice) }}" class="text-primary-600 hover:text-primary-800 hover:underline">
                                        #{{ $payment->invoice->faktur_number ?? $payment->invoice->invoice_number }}
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-6 py-3.5 font-semibold text-zinc-700">{{ $payment->payment_method }}</td>
                            <td class="px-6 py-3.5 font-mono text-xs text-zinc-500">{{ $payment->reference_number ?? '-' }}</td>
                            <td class="px-6 py-3.5 text-xs text-zinc-600">{{ $payment->notes ?? '-' }}</td>
                            <td class="px-6 py-3.5 text-right font-extrabold text-emerald-700 font-mono">
                                Rp {{ number_format($payment->amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-3.5 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2">
                                    @if($payment->invoice)
                                    <a href="{{ route('invoices.show', $payment->invoice) }}" class="px-2.5 py-1 text-xs bg-zinc-100 text-zinc-700 hover:bg-zinc-200 font-bold rounded-lg transition-colors">
                                        Lihat Faktur
                                    </a>
                                    <form action="{{ route('invoices.payment.destroy', [$payment->invoice, $payment]) }}" method="POST"
                                        class="action-confirm inline-block"
                                        data-title="Batalkan & Hapus Pembayaran?"
                                        data-text="Hapus pelunasan sebesar Rp {{ number_format($payment->amount, 0, ',', '.') }} untuk Faktur #{{ $payment->invoice->faktur_number ?? $payment->invoice->invoice_number }}? Status faktur akan disesuaikan kembali."
                                        data-icon="warning"
                                        data-confirm-text="Ya, Batalkan"
                                        data-confirm-color="#ef4444">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1 text-xs bg-red-50 text-red-600 hover:bg-red-100 font-bold rounded-lg transition-colors border border-red-100">
                                            Batalkan
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>

    <!-- Modal Pelunasan Tagihan Klien Periode -->
    <div id="batchSettleModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm hidden p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg p-6 sm:p-8 relative max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-4 border-b border-zinc-100 mb-6">
                <div>
                    <h3 class="text-lg font-black text-zinc-800">Pelunasan Tagihan Klien Per Periode</h3>
                    <p class="text-xs text-zinc-400 mt-0.5">Alokasikan pembayaran ke faktur outstanding klien secara berurutan.</p>
                </div>
                <button type="button" onclick="closeBatchSettleModal()" class="text-zinc-400 hover:text-zinc-600 p-1 rounded-xl hover:bg-zinc-100 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <form action="{{ route('reports.billing.settle', $customer) }}" method="POST"
                class="action-confirm space-y-4"
                data-title="Konfirmasi Pelunasan Tagihan Periode"
                data-text="Proses pelunasan tagihan untuk klien {{ $customer->name }}? Pembayaran akan dialokasikan ke faktur-faktur yang belum lunas secara berurutan dan otomatis tercatat di kas keuangan."
                data-icon="question"
                data-confirm-text="Ya, Proses Pelunasan"
                data-confirm-color="#059669">
                @csrf

                <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                <input type="hidden" name="item_search" value="{{ request('item_search') }}">

                <!-- Ringkasan Info -->
                <div class="bg-emerald-50/60 border border-emerald-100 rounded-2xl p-4 text-xs space-y-2">
                    <div class="flex justify-between items-center text-zinc-600">
                        <span>Nama Klien:</span>
                        <span class="font-extrabold text-zinc-800">{{ $customer->name }}</span>
                    </div>
                    <div class="flex justify-between items-center text-zinc-600">
                        <span>Periode Tagihan:</span>
                        <span class="font-bold text-zinc-700">
                            {{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') : 'Awal' }} 
                            s/d 
                            {{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') : 'Sekarang' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center text-zinc-600">
                        <span>Jumlah Faktur Belum Lunas:</span>
                        <span class="font-bold text-zinc-700">{{ count($invoices) }} Faktur</span>
                    </div>
                    <div class="flex justify-between items-center pt-2 border-t border-emerald-200/60">
                        <span class="font-black text-emerald-900 uppercase tracking-wider">Total Tagihan Periode:</span>
                        <span class="font-black text-base text-emerald-800 font-mono">Rp {{ number_format($totalOutstanding, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Input Tanggal & Metode -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-zinc-700 mb-1.5">Tanggal Pembayaran <span class="text-red-500">*</span></label>
                        <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" class="w-full text-sm border-zinc-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-zinc-700" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-zinc-700 mb-1.5">Metode Pembayaran <span class="text-red-500">*</span></label>
                        <select name="payment_method" class="w-full text-sm border-zinc-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-zinc-700" required>
                            <option value="Transfer BCA">Transfer BCA</option>
                            <option value="Transfer Mandiri">Transfer Mandiri</option>
                            <option value="Transfer BRI">Transfer BRI</option>
                            <option value="Transfer BNI">Transfer BNI</option>
                            <option value="Cash">Cash (Tunai)</option>
                            <option value="Giro">Giro / Cek</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                </div>

                <!-- Input Jumlah Bayar -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="text-xs font-bold text-zinc-700">Jumlah Pelunasan (Rp) <span class="text-red-500">*</span></label>
                        <button type="button" onclick="setFullAmount({{ (float)$totalOutstanding }})" class="text-[11px] text-emerald-700 font-extrabold hover:underline">
                            Bayar Penuh (100%)
                        </button>
                    </div>
                    <input type="number" step="any" min="1" max="{{ (float)$totalOutstanding }}" name="amount" id="batch_settle_amount" value="{{ (float)$totalOutstanding }}" class="w-full text-sm border-zinc-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 font-mono text-zinc-800" required>
                    <p class="text-[11px] text-zinc-400 mt-1 font-medium">Bisa diisi sebagian atau penuh. Pembayaran akan dialokasikan ke faktur terlama terlebih dahulu.</p>
                </div>

                <!-- Input Referensi -->
                <div>
                    <label class="block text-xs font-bold text-zinc-700 mb-1.5">Nomor Referensi / Bukti Transfer</label>
                    <input type="text" name="reference_number" placeholder="Contoh: BUKTI-TRF-0912" class="w-full text-sm border-zinc-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-zinc-700">
                </div>

                <!-- Input Catatan -->
                <div>
                    <label class="block text-xs font-bold text-zinc-700 mb-1.5">Catatan Pembayaran</label>
                    <textarea name="notes" rows="2" placeholder="Keterangan tambahan pelunasan..." class="w-full text-sm border-zinc-200 rounded-xl px-3.5 py-2 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-zinc-700">{{ 'Pelunasan tagihan periode ' . (request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') : 'awal') . ' s/d ' . (request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') : 'sekarang') }}</textarea>
                </div>

                <div class="pt-4 border-t border-zinc-100 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeBatchSettleModal()" class="px-5 py-2.5 border border-zinc-200 text-zinc-600 hover:bg-zinc-50 rounded-xl text-xs font-bold transition-all">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all shadow-md active:scale-95 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                        Simpan & Proses Pelunasan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openBatchSettleModal() {
            const modal = document.getElementById('batchSettleModal');
            if (modal) {
                modal.classList.remove('hidden');
            }
        }

        function closeBatchSettleModal() {
            const modal = document.getElementById('batchSettleModal');
            if (modal) {
                modal.classList.add('hidden');
            }
        }

        function setFullAmount(amount) {
            const input = document.getElementById('batch_settle_amount');
            if (input) {
                input.value = amount;
            }
        }
    </script>
</x-app-layout>
