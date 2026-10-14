<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-2xl text-zinc-800 tracking-tight leading-none">
                    Laporan Tagihan Klien
                </h2>
                <p class="text-xs text-zinc-400 mt-1.5 font-medium uppercase tracking-wider">
                    Divisi: {{ ucfirst($division ?? 'Semua') }}
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('company-receivables.index') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-zinc-200 text-zinc-600 text-sm font-semibold rounded-xl hover:bg-zinc-50 hover:text-zinc-800 transition-all active:scale-95 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                    Kelola Semua Tagihan
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 space-y-6 max-w-7xl mx-auto page-enter">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Card 1: Total Outstanding -->
            <div class="glass-card glass-card-hover p-6 rounded-[26px]">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Total Tagihan Berjalan</p>
                    <div class="w-10 h-10 rounded-2xl bg-rose-100/80 text-rose-700 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                </div>
                <h3 class="text-3xl font-black text-rose-600 tracking-tight leading-none">
                    Rp {{ number_format($totalOutstanding, 0, ',', '.') }}
                </h3>
                <div class="mt-4 flex items-center gap-2 border-t pt-3.5 border-slate-100/80">
                    <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Total sisa tagihan semua klien</span>
                </div>
            </div>

            <!-- Card 2: Unpaid Invoices -->
            <div class="glass-card glass-card-hover p-6 rounded-[26px]">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Faktur Belum Lunas</p>
                    <div class="w-10 h-10 rounded-2xl bg-amber-100/80 text-amber-700 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                    </div>
                </div>
                <h3 class="text-3xl font-black text-amber-700 tracking-tight leading-none">
                    {{ number_format($unpaidInvoicesCount) }}
                </h3>
                <div class="mt-4 flex items-center gap-2 border-t pt-3.5 border-slate-100/80">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Invoice outstanding / sebagian</span>
                </div>
            </div>

            <!-- Card 3: Unpaid Customers -->
            <div class="glass-card glass-card-hover p-6 rounded-[26px]">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Jumlah Klien Menunggak</p>
                    <div class="w-10 h-10 rounded-2xl bg-teal-100/80 text-teal-800 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                    </div>
                </div>
                <h3 class="text-3xl font-black text-teal-800 tracking-tight leading-none">
                    {{ number_format($unpaidCustomersCount) }}
                </h3>
                <div class="mt-4 flex items-center gap-2 border-t pt-3.5 border-slate-100/80">
                    <span class="w-2 h-2 rounded-full bg-teal-600"></span>
                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Klien dengan saldo tagihan</span>
                </div>
            </div>
        </div>

        <!-- Filter & Table Card -->
        <div class="glass-card rounded-[26px] overflow-hidden">
            <div class="p-6 border-b border-white/60 bg-white/40 flex flex-col gap-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-800 tracking-tight">Daftar Tagihan per Klien</h3>
                        <p class="text-xs text-slate-500 mt-1 font-medium">Gunakan filter untuk menyaring periode atau nama barang, lalu gunakan tombol pelunasan periode untuk melunasi tanpa klik faktur satu per satu.</p>
                    </div>
                    @if(request('start_date') || request('end_date'))
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 bg-teal-50 text-teal-900 border border-teal-200/80 rounded-full text-xs font-bold self-start shadow-2xs">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-teal-700"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5" /></svg>
                        Periode: {{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') : 'Awal' }} s/d {{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') : 'Sekarang' }}
                    </div>
                    @endif
                </div>
                
                <!-- Search Form -->
                <form method="GET" action="{{ route('reports.billing') }}" class="flex flex-wrap items-end gap-3 w-full">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Nama Klien</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama klien..." class="w-full text-sm bg-white/80 border border-white/80 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all text-slate-700">
                    </div>
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Kode/Nama Barang</label>
                        <input type="text" name="item_search" value="{{ request('item_search') }}" placeholder="Cari barang..." class="w-full text-sm bg-white/80 border border-white/80 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all text-slate-700">
                    </div>
                    <div class="flex-1 min-w-[130px]">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Dari Tgl Faktur</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full text-sm bg-white/80 border border-white/80 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all text-slate-700">
                    </div>
                    <div class="flex-1 min-w-[130px]">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Sampai Tgl Faktur</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full text-sm bg-white/80 border border-white/80 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all text-slate-700">
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="submit" class="h-[42px] px-5 bg-gradient-to-r from-teal-800 to-emerald-800 hover:from-teal-900 hover:to-emerald-900 text-white text-sm font-bold rounded-xl transition-all shadow-md active:scale-95 flex items-center gap-2">
                            Filter
                        </button>
                        @if(request('search') || request('item_search') || request('start_date') || request('end_date'))
                            <a href="{{ route('reports.billing') }}" class="h-[42px] px-5 bg-white/70 hover:bg-white text-slate-700 text-sm font-bold rounded-xl transition-all active:scale-95 flex items-center border border-white/80">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-white/50 border-b border-white/60">
                        <tr>
                            <th class="w-12 px-4 py-4 text-center">
                                <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll(this)" class="w-4 h-4 rounded border-zinc-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer" title="Pilih Semua Klien di Halaman Ini">
                            </th>
                            <th class="text-left px-6 py-4 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Nama Klien</th>
                            <th class="text-center px-6 py-4 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Divisi</th>
                            <th class="text-center px-6 py-4 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Jumlah Faktur</th>
                            <th class="text-right px-6 py-4 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Total Tagihan</th>
                            <th class="text-right px-6 py-4 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Total Terbayar</th>
                            <th class="text-right px-6 py-4 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Sisa Tagihan</th>
                            <th class="text-center px-6 py-4 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @forelse($customers as $customer)
                            @php
                                $unpaidInvoices = $customer->invoices;
                                $invoiceCount = $unpaidInvoices->count();
                                $totalInvoiced = $unpaidInvoices->sum('total_amount');
                                $totalPaid = $unpaidInvoices->sum('paid_amount');
                                $totalRemaining = $totalInvoiced - $totalPaid;
                            @endphp
                            <tr class="hover:bg-zinc-50/50 transition-colors">
                                <td class="px-4 py-4 text-center">
                                    @if($totalRemaining > 0)
                                        <input type="checkbox"
                                            class="client-checkbox w-4 h-4 rounded border-zinc-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer"
                                            value="{{ $customer->id }}"
                                            data-id="{{ $customer->id }}"
                                            data-name="{{ $customer->name }}"
                                            data-remaining="{{ (float)$totalRemaining }}"
                                            data-count="{{ $invoiceCount }}"
                                            onchange="updateSelectionState()">
                                    @else
                                        <span class="text-zinc-300 font-bold text-xs">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-bold text-zinc-800">
                                    <a href="{{ route('reports.billing.show', array_merge(['customer' => $customer->id], request()->query())) }}" class="hover:text-primary-600 transition-colors">
                                        {{ $customer->name }}
                                    </a>
                                    @if($customer->phone)
                                        <p class="text-xs text-zinc-400 font-normal mt-0.5">{{ $customer->phone }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-block px-2.5 py-1 text-xs font-semibold rounded-lg {{ $customer->division === 'percetakan' ? 'bg-blue-50 text-blue-700 border border-blue-100' : 'bg-purple-50 text-purple-700 border border-purple-100' }}">
                                        {{ ucfirst($customer->division) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center text-zinc-600 font-medium">
                                    {{ $invoiceCount }} Faktur
                                </td>
                                <td class="px-6 py-4 text-right font-medium text-zinc-500">
                                    Rp {{ number_format($totalInvoiced, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-right font-medium text-zinc-500">
                                    Rp {{ number_format($totalPaid, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-right font-black text-red-600">
                                    Rp {{ number_format($totalRemaining, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        @if($totalRemaining > 0)
                                            <button type="button"
                                                onclick="openSingleModal({{ $customer->id }}, '{{ addslashes($customer->name) }}', {{ (float)$totalRemaining }}, {{ $invoiceCount }})"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-bold rounded-xl border border-emerald-200 transition-all active:scale-95 shadow-sm"
                                                title="Lunasi langsung tagihan periode ini untuk klien {{ $customer->name }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6H2.25m0 0v10.5m0-10.5h19.5m0 0v10.5m0-10.5c0-.414-.336-.75-.75-.75h-.75M21.75 6v10.5m0 0a3.75 3.75 0 01-3.75 3.75H6a3.75 3.75 0 01-3.75-3.75" /></svg>
                                                Lunasi Periode
                                            </button>
                                        @endif
                                        <a href="{{ route('reports.billing.show', array_merge(['customer' => $customer->id], request()->query())) }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-zinc-100 text-zinc-700 text-xs font-bold rounded-xl hover:bg-primary-50 hover:text-primary-700 hover:border-primary-100 border border-transparent transition-all">
                                            Rincian Tagihan
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center text-zinc-400">
                                    <div class="flex flex-col items-center justify-center gap-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-zinc-300"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.03 0 1.9.693 2.166 1.638m-7.377 12.408l1.5-1.5m0 0l1.5-1.5m-1.5 1.5l1.5 1.5m-1.5-1.5l-1.5-1.5M3 16.06V18c0 1.242 1.008 2.25 2.25 2.25h13.5A2.25 2.25 0 0021 18v-1.94" /></svg>
                                        <p class="font-bold text-sm">Tidak ada tagihan tertunggak</p>
                                        <p class="text-xs text-zinc-400">Semua klien untuk divisi ini telah melunasi tagihan mereka.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($customers->hasPages())
                <div class="p-6 border-t border-zinc-100 bg-zinc-50/50">
                    {{ $customers->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Floating Bottom Bar for Bulk Settlement -->
    <div id="bulkActionBar" class="fixed bottom-6 inset-x-0 z-40 flex justify-center px-4 pointer-events-none transition-all duration-300 transform translate-y-24 opacity-0">
        <div class="pointer-events-auto bg-zinc-900/95 backdrop-blur-md text-white px-6 py-4 rounded-2xl shadow-2xl border border-zinc-800 flex flex-wrap items-center gap-4 max-w-4xl w-full justify-between">
            <div class="flex items-center gap-3">
                <span class="w-3 h-3 rounded-full bg-emerald-400 animate-pulse"></span>
                <div>
                    <p class="text-xs text-zinc-400 font-bold uppercase tracking-wider">Aksi Masal Klien Terpilih</p>
                    <p class="text-sm font-extrabold text-white">
                        <span id="bulkSelectedCount">0</span> Klien Terpilih 
                        <span class="text-zinc-500 mx-1.5">•</span> 
                        Total Sisa: <span id="bulkSelectedTotal" class="text-emerald-400 font-mono font-black">Rp 0</span>
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" onclick="deselectAll()" class="px-3.5 py-2 text-xs font-bold text-zinc-400 hover:text-white transition-colors">
                    Batal Pilih
                </button>
                <button type="button" onclick="openBulkModal()" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-extrabold shadow-lg shadow-emerald-900/40 transition-all active:scale-95 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6H2.25m0 0v10.5m0-10.5h19.5m0 0v10.5m0-10.5c0-.414-.336-.75-.75-.75h-.75M21.75 6v10.5m0 0a3.75 3.75 0 01-3.75 3.75H6a3.75 3.75 0 01-3.75-3.75" /></svg>
                    Lunasi Klien Terpilih Sekaligus
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Pelunasan Cepat Single Client -->
    <div id="singleClientModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm hidden">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-zinc-100 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-4 border-b border-zinc-100">
                <div>
                    <h3 class="text-base font-extrabold text-zinc-800">Pelunasan Tagihan Periode</h3>
                    <p class="text-xs text-zinc-400 font-medium mt-0.5">Lunasi faktur outstanding untuk klien ini.</p>
                </div>
                <button type="button" onclick="closeSingleModal()" class="p-2 text-zinc-400 hover:text-zinc-600 rounded-xl hover:bg-zinc-100 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <form id="singleSettleForm" action="" method="POST"
                class="action-confirm space-y-4 pt-4"
                data-title="Konfirmasi Pelunasan Tagihan Periode"
                data-text="Proses pelunasan tagihan periode untuk klien ini? Pembayaran akan dialokasikan ke faktur belum lunas secara otomatis dan dicatat ke kas keuangan."
                data-icon="question"
                data-confirm-text="Ya, Proses Pelunasan"
                data-confirm-color="#059669">
                @csrf

                <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                <input type="hidden" name="item_search" value="{{ request('item_search') }}">

                <!-- Ringkasan Klien -->
                <div class="bg-emerald-50/60 border border-emerald-100 rounded-2xl p-4 text-xs space-y-2">
                    <div class="flex justify-between items-center text-zinc-600">
                        <span>Nama Klien:</span>
                        <span id="singleModalClientName" class="font-extrabold text-zinc-800">-</span>
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
                        <span id="singleModalInvoiceCount" class="font-bold text-zinc-700">-</span>
                    </div>
                    <div class="flex justify-between items-center pt-2 border-t border-emerald-200/60">
                        <span class="font-black text-emerald-900 uppercase tracking-wider">Total Tagihan Periode:</span>
                        <span id="singleModalTotalRemaining" class="font-black text-base text-emerald-800 font-mono">Rp 0</span>
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
                        <button type="button" id="singleModalSetFullBtn" class="text-[11px] text-emerald-700 font-extrabold hover:underline">
                            Bayar Penuh (100%)
                        </button>
                    </div>
                    <input type="number" step="any" min="1" name="amount" id="singleModalAmount" value="" class="w-full text-sm border-zinc-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 font-mono text-zinc-800" required>
                    <p class="text-[11px] text-zinc-400 mt-1 font-medium">Bisa diisi sebagian atau penuh. Pembayaran dialokasikan ke faktur terlama terlebih dahulu.</p>
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
                    <button type="button" onclick="closeSingleModal()" class="px-5 py-2.5 border border-zinc-200 text-zinc-600 hover:bg-zinc-50 rounded-xl text-xs font-bold transition-all">
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

    <!-- Modal Pelunasan Masal (Banyak Klien Sekaligus) -->
    <div id="bulkClientModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm hidden">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-zinc-100 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-4 border-b border-zinc-100">
                <div>
                    <h3 class="text-base font-extrabold text-zinc-800">Pelunasan Masal Klien Terpilih</h3>
                    <p class="text-xs text-zinc-400 font-medium mt-0.5">Lunasi tagihan seluruh klien yang dipilih sekaligus.</p>
                </div>
                <button type="button" onclick="closeBulkModal()" class="p-2 text-zinc-400 hover:text-zinc-600 rounded-xl hover:bg-zinc-100 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <form action="{{ route('reports.billing.bulk-settle') }}" method="POST"
                class="action-confirm space-y-4 pt-4"
                data-title="Konfirmasi Pelunasan Masal Semua Klien Terpilih"
                data-text="Yakin ingin melunasi seluruh sisa tagihan untuk semua klien yang dipilih? Pembayaran akan dialokasikan penuh ke setiap faktur terkait dan dicatat ke kas keuangan."
                data-icon="question"
                data-confirm-text="Ya, Lunasi Semua Klien Terpilih"
                data-confirm-color="#059669">
                @csrf

                <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                <input type="hidden" name="item_search" value="{{ request('item_search') }}">

                <!-- Dynamic container for selected customer_ids -->
                <div id="bulkCustomerInputsContainer"></div>

                <!-- Ringkasan Info Pelunasan Masal -->
                <div class="bg-emerald-50/60 border border-emerald-100 rounded-2xl p-4 text-xs space-y-2.5">
                    <div class="flex justify-between items-center text-zinc-600">
                        <span>Jumlah Klien Terpilih:</span>
                        <span id="bulkModalClientCount" class="font-extrabold text-zinc-800">0 Klien</span>
                    </div>
                    <div class="flex justify-between items-center text-zinc-600">
                        <span>Total Faktur Terkait:</span>
                        <span id="bulkModalInvoiceCount" class="font-bold text-zinc-700">0 Faktur</span>
                    </div>
                    <div class="flex justify-between items-center text-zinc-600">
                        <span>Periode Tagihan:</span>
                        <span class="font-bold text-zinc-700">
                            {{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') : 'Awal' }} 
                            s/d 
                            {{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') : 'Sekarang' }}
                        </span>
                    </div>
                    
                    <!-- Client Names Badges -->
                    <div class="pt-2 border-t border-emerald-100">
                        <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-wider mb-1.5">Daftar Klien yang Akan Dilunasi:</p>
                        <div id="bulkModalClientNamesList" class="flex flex-wrap gap-1.5 max-h-24 overflow-y-auto pr-1"></div>
                    </div>

                    <div class="flex justify-between items-center pt-2.5 border-t border-emerald-200/60">
                        <span class="font-black text-emerald-900 uppercase tracking-wider">Total Pelunasan Masal:</span>
                        <span id="bulkModalTotalAmount" class="font-black text-base text-emerald-800 font-mono">Rp 0</span>
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

                <!-- Input Referensi -->
                <div>
                    <label class="block text-xs font-bold text-zinc-700 mb-1.5">Nomor Referensi / Bukti Transfer</label>
                    <input type="text" name="reference_number" placeholder="Contoh: BUKTI-MASAL-0912" class="w-full text-sm border-zinc-200 rounded-xl px-3.5 py-2.5 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-zinc-700">
                </div>

                <!-- Input Catatan -->
                <div>
                    <label class="block text-xs font-bold text-zinc-700 mb-1.5">Catatan Pelunasan Masal</label>
                    <textarea name="notes" rows="2" placeholder="Keterangan tambahan..." class="w-full text-sm border-zinc-200 rounded-xl px-3.5 py-2 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 text-zinc-700">{{ 'Pelunasan masal tagihan periode ' . (request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') : 'awal') . ' s/d ' . (request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') : 'sekarang') }}</textarea>
                </div>

                <div class="pt-4 border-t border-zinc-100 flex items-center justify-end gap-3">
                    <button type="button" onclick="closeBulkModal()" class="px-5 py-2.5 border border-zinc-200 text-zinc-600 hover:bg-zinc-50 rounded-xl text-xs font-bold transition-all">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition-all shadow-md active:scale-95 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                        Lunasi Klien Terpilih
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function formatRupiah(amount) {
            return 'Rp ' + Number(amount).toLocaleString('id-ID');
        }

        function getCheckedClients() {
            const checkboxes = document.querySelectorAll('.client-checkbox:checked');
            const clients = [];
            checkboxes.forEach(cb => {
                clients.push({
                    id: cb.value,
                    name: cb.dataset.name,
                    remaining: parseFloat(cb.dataset.remaining) || 0,
                    count: parseInt(cb.dataset.count) || 0
                });
            });
            return clients;
        }

        function updateSelectionState() {
            const clients = getCheckedClients();
            const allCheckboxes = document.querySelectorAll('.client-checkbox');
            const selectAll = document.getElementById('selectAllCheckbox');
            const bar = document.getElementById('bulkActionBar');

            if (selectAll) {
                selectAll.checked = allCheckboxes.length > 0 && clients.length === allCheckboxes.length;
                selectAll.indeterminate = clients.length > 0 && clients.length < allCheckboxes.length;
            }

            const countEl = document.getElementById('bulkSelectedCount');
            const totalEl = document.getElementById('bulkSelectedTotal');

            if (clients.length > 0) {
                const totalAmount = clients.reduce((sum, c) => sum + c.remaining, 0);
                if (countEl) countEl.innerText = clients.length;
                if (totalEl) totalEl.innerText = formatRupiah(totalAmount);

                if (bar) {
                    bar.classList.remove('translate-y-24', 'opacity-0');
                    bar.classList.add('translate-y-0', 'opacity-100');
                }
            } else {
                if (bar) {
                    bar.classList.add('translate-y-24', 'opacity-0');
                    bar.classList.remove('translate-y-0', 'opacity-100');
                }
            }
        }

        function toggleSelectAll(masterCb) {
            const checkboxes = document.querySelectorAll('.client-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = masterCb.checked;
            });
            updateSelectionState();
        }

        function deselectAll() {
            const checkboxes = document.querySelectorAll('.client-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = false;
            });
            const selectAll = document.getElementById('selectAllCheckbox');
            if (selectAll) {
                selectAll.checked = false;
                selectAll.indeterminate = false;
            }
            updateSelectionState();
        }

        // Single client modal handlers
        function openSingleModal(customerId, customerName, remaining, invoiceCount) {
            const modal = document.getElementById('singleClientModal');
            const form = document.getElementById('singleSettleForm');
            if (!modal || !form) return;

            form.action = "{{ url('reports/billing') }}/" + customerId + "/settle";
            document.getElementById('singleModalClientName').innerText = customerName;
            document.getElementById('singleModalInvoiceCount').innerText = invoiceCount + ' Faktur';
            document.getElementById('singleModalTotalRemaining').innerText = formatRupiah(remaining);

            const amountInput = document.getElementById('singleModalAmount');
            if (amountInput) {
                amountInput.value = remaining;
                amountInput.max = remaining;
            }

            const setFullBtn = document.getElementById('singleModalSetFullBtn');
            if (setFullBtn) {
                setFullBtn.onclick = function() {
                    if (amountInput) amountInput.value = remaining;
                };
            }

            modal.classList.remove('hidden');
        }

        function closeSingleModal() {
            const modal = document.getElementById('singleClientModal');
            if (modal) modal.classList.add('hidden');
        }

        // Bulk client modal handlers
        function openBulkModal() {
            const clients = getCheckedClients();
            if (clients.length === 0) {
                alert('Silakan pilih minimal satu klien terlebih dahulu.');
                return;
            }

            const modal = document.getElementById('bulkClientModal');
            const inputsContainer = document.getElementById('bulkCustomerInputsContainer');
            const countEl = document.getElementById('bulkModalClientCount');
            const invoiceCountEl = document.getElementById('bulkModalInvoiceCount');
            const totalEl = document.getElementById('bulkModalTotalAmount');
            const namesListEl = document.getElementById('bulkModalClientNamesList');

            if (!modal || !inputsContainer) return;

            inputsContainer.innerHTML = '';
            if (namesListEl) namesListEl.innerHTML = '';

            let totalRemaining = 0;
            let totalInvoices = 0;

            clients.forEach(c => {
                totalRemaining += c.remaining;
                totalInvoices += c.count;

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'customer_ids[]';
                input.value = c.id;
                inputsContainer.appendChild(input);

                if (namesListEl) {
                    const badge = document.createElement('span');
                    badge.className = 'inline-block px-2 py-0.5 bg-white border border-emerald-200 text-zinc-700 text-[11px] font-semibold rounded-md shadow-2xs';
                    badge.innerText = c.name;
                    namesListEl.appendChild(badge);
                }
            });

            if (countEl) countEl.innerText = clients.length + ' Klien';
            if (invoiceCountEl) invoiceCountEl.innerText = totalInvoices + ' Faktur';
            if (totalEl) totalEl.innerText = formatRupiah(totalRemaining);

            modal.classList.remove('hidden');
        }

        function closeBulkModal() {
            const modal = document.getElementById('bulkClientModal');
            if (modal) modal.classList.add('hidden');
        }
    </script>
</x-app-layout>
