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
                </div>
            </div>
        </div>

        <!-- Toolbar & Export Actions -->
        <div class="flex flex-wrap items-center justify-between gap-4 bg-zinc-50 border border-zinc-100 p-4 rounded-2xl shadow-sm">
            <div class="text-zinc-600 text-xs font-bold uppercase tracking-wider">
                Opsi Ekspor Laporan Tagihan:
            </div>
            <div class="flex flex-wrap items-center gap-2">
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
                                        {{ $item->quantity }}
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
    </div>
</x-app-layout>
