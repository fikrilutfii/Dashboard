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
            <div>
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
            <div class="bg-white p-6 rounded-3xl border border-zinc-100 shadow-premium group hover:shadow-premium-hover transition-all duration-300">
                <p class="text-[10px] font-black text-zinc-400 uppercase tracking-[0.2em] mb-2">Total Tagihan Berjalan</p>
                <h3 class="text-3xl font-black text-red-600 tracking-tight">
                    Rp {{ number_format($totalOutstanding, 0, ',', '.') }}
                </h3>
                <div class="mt-4 flex items-center gap-2 border-t pt-4 border-zinc-50">
                    <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                    <span class="text-[10px] text-zinc-400 font-bold uppercase tracking-wider">Total sisa tagihan semua klien</span>
                </div>
            </div>

            <!-- Card 2: Unpaid Invoices -->
            <div class="bg-white p-6 rounded-3xl border border-zinc-100 shadow-premium group hover:shadow-premium-hover transition-all duration-300">
                <p class="text-[10px] font-black text-zinc-400 uppercase tracking-[0.2em] mb-2">Faktur Belum Lunas</p>
                <h3 class="text-3xl font-black text-amber-600 tracking-tight">
                    {{ number_format($unpaidInvoicesCount) }}
                </h3>
                <div class="mt-4 flex items-center gap-2 border-t pt-4 border-zinc-50">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    <span class="text-[10px] text-zinc-400 font-bold uppercase tracking-wider">Invoice outstanding / sebagian</span>
                </div>
            </div>

            <!-- Card 3: Unpaid Customers -->
            <div class="bg-white p-6 rounded-3xl border border-zinc-100 shadow-premium group hover:shadow-premium-hover transition-all duration-300">
                <p class="text-[10px] font-black text-zinc-400 uppercase tracking-[0.2em] mb-2">Jumlah Klien Menunggak</p>
                <h3 class="text-3xl font-black text-indigo-600 tracking-tight">
                    {{ number_format($unpaidCustomersCount) }}
                </h3>
                <div class="mt-4 flex items-center gap-2 border-t pt-4 border-zinc-50">
                    <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                    <span class="text-[10px] text-zinc-400 font-bold uppercase tracking-wider">Klien dengan saldo tagihan</span>
                </div>
            </div>
        </div>

        <!-- Filter & Table Card -->
        <div class="bg-white rounded-3xl border border-zinc-100 shadow-premium overflow-hidden">
            <div class="p-6 border-b border-zinc-100 bg-zinc-50/50 flex flex-col gap-4">
                <div>
                    <h3 class="text-lg font-extrabold text-zinc-800">Daftar Tagihan per Klien</h3>
                    <p class="text-xs text-zinc-400 mt-1 font-medium">Gunakan filter di bawah untuk mencari tagihan berdasarkan nama klien, kode/nama barang, atau rentang tanggal faktur.</p>
                </div>
                
                <!-- Search Form -->
                <form method="GET" action="{{ route('reports.billing') }}" class="flex flex-wrap items-end gap-3 w-full">
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-1.5">Nama Klien</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama klien..." class="w-full text-sm bg-white border border-zinc-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-zinc-700">
                    </div>
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-1.5">Kode/Nama Barang</label>
                        <input type="text" name="item_search" value="{{ request('item_search') }}" placeholder="Cari barang..." class="w-full text-sm bg-white border border-zinc-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-zinc-700">
                    </div>
                    <div class="flex-1 min-w-[130px]">
                        <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-1.5">Dari Tgl</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full text-sm bg-white border border-zinc-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-zinc-700">
                    </div>
                    <div class="flex-1 min-w-[130px]">
                        <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-1.5">Sampai Tgl</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full text-sm bg-white border border-zinc-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-primary-500/20 focus:border-primary-500 transition-all text-zinc-700">
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="submit" class="h-[42px] px-5 bg-zinc-800 hover:bg-zinc-900 text-white text-sm font-bold rounded-xl transition-all shadow-md active:scale-95 flex items-center gap-2">
                            Filter
                        </button>
                        @if(request('search') || request('item_search') || request('start_date') || request('end_date'))
                            <a href="{{ route('reports.billing') }}" class="h-[42px] px-5 bg-zinc-100 hover:bg-zinc-200 text-zinc-700 text-sm font-bold rounded-xl transition-all active:scale-95 flex items-center">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-zinc-50 border-b border-zinc-100">
                        <tr>
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
                                <td class="px-6 py-4 font-bold text-zinc-800">
                                    <a href="{{ route('reports.billing.show', $customer) }}" class="hover:text-primary-600 transition-colors">
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
                                    <a href="{{ route('reports.billing.show', $customer) }}" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-zinc-100 text-zinc-700 text-xs font-bold rounded-xl hover:bg-primary-50 hover:text-primary-700 hover:border-primary-100 border border-transparent transition-all">
                                        Rincian Tagihan
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-zinc-400">
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
</x-app-layout>
