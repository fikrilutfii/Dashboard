<x-app-layout>
    <x-slot name="header">Audit & Log Aktivitas</x-slot>

    <div class="space-y-6 pb-12">
        <!-- Page Header & Action Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-50 border border-teal-100 text-teal-800 text-xs font-semibold mb-2">
                    <span class="w-2 h-2 rounded-full bg-teal-500 animate-pulse"></span>
                    Audit Trail & Keamanan Sistem
                </div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Riwayat Sistem & Log Aktivitas</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Memantau seluruh perubahan data transaksi, modifikasi faktur, dan catatan aktivitas akun.</p>
            </div>
            <div class="flex items-center gap-2.5">
                <a href="{{ route('activity-logs.index') }}" class="glass-pill px-4 py-2 text-xs font-bold text-slate-600 hover:text-slate-900 rounded-xl transition-all flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    Segarkan Data
                </a>
            </div>
        </div>

        <!-- Metric Summary Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card 1: Total Aktivitas -->
            <div class="glass-card rounded-[22px] p-4 sm:p-5 flex items-center gap-3.5 group hover:border-teal-400/50 transition-all">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-teal-500 to-emerald-700 flex items-center justify-center text-white shadow-md shadow-teal-700/20 group-hover:scale-105 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                </div>
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Total Log</span>
                    <span class="text-xl sm:text-2xl font-black text-slate-800">{{ number_format($totalLogsCount) }}</span>
                </div>
            </div>

            <!-- Card 2: Hari Ini -->
            <div class="glass-card rounded-[22px] p-4 sm:p-5 flex items-center gap-3.5 group hover:border-blue-400/50 transition-all">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-700 flex items-center justify-center text-white shadow-md shadow-blue-700/20 group-hover:scale-105 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Hari Ini</span>
                    <span class="text-xl sm:text-2xl font-black text-slate-800">{{ number_format($todayLogsCount) }}</span>
                </div>
            </div>

            <!-- Card 3: Perubahan Data (CRUD) -->
            <div class="glass-card rounded-[22px] p-4 sm:p-5 flex items-center gap-3.5 group hover:border-amber-400/50 transition-all">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center text-white shadow-md shadow-amber-700/20 group-hover:scale-105 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                    </svg>
                </div>
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Mutasi & CRUD</span>
                    <span class="text-xl sm:text-2xl font-black text-amber-700">{{ number_format($crudCount) }}</span>
                </div>
            </div>

            <!-- Card 4: Sesi Login / Keluar -->
            <div class="glass-card rounded-[22px] p-4 sm:p-5 flex items-center gap-3.5 group hover:border-purple-400/50 transition-all">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-700 flex items-center justify-center text-white shadow-md shadow-purple-700/20 group-hover:scale-105 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                    </svg>
                </div>
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Sesi Auth</span>
                    <span class="text-xl sm:text-2xl font-black text-indigo-700">{{ number_format($authCount) }}</span>
                </div>
            </div>
        </div>

        <!-- Filter & Pencarian Bar -->
        <div class="glass-card rounded-[24px] p-5 shadow-sm">
            <form method="GET" action="{{ route('activity-logs.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
                <!-- Search Keyword -->
                <div class="lg:col-span-2 relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari aktivitas, nomor faktur, atau user..." 
                           class="w-full text-xs bg-white/90 border border-slate-200/90 rounded-xl pl-9 pr-3 py-2.5 text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all shadow-2xs">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-slate-400 absolute left-3 top-3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>

                <!-- User Filter -->
                <div>
                    <select name="user_id" class="w-full text-xs bg-white/90 border border-slate-200/90 rounded-xl px-3 py-2.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all shadow-2xs">
                        <option value="">Semua User</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Module Filter -->
                <div>
                    <select name="module" class="w-full text-xs bg-white/90 border border-slate-200/90 rounded-xl px-3 py-2.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all shadow-2xs">
                        <option value="">Semua Modul</option>
                        <option value="Invoice" {{ request('module') == 'Invoice' ? 'selected' : '' }}>Faktur Penjualan</option>
                        <option value="InvoicePayment" {{ request('module') == 'InvoicePayment' ? 'selected' : '' }}>Pembayaran Faktur</option>
                        <option value="Purchase" {{ request('module') == 'Purchase' ? 'selected' : '' }}>Pembelian Bahan</option>
                        <option value="Product" {{ request('module') == 'Product' ? 'selected' : '' }}>Produk & Stok</option>
                        <option value="Customer" {{ request('module') == 'Customer' ? 'selected' : '' }}>Pelanggan</option>
                        <option value="Supplier" {{ request('module') == 'Supplier' ? 'selected' : '' }}>Pemasok</option>
                        <option value="Kasbon" {{ request('module') == 'Kasbon' ? 'selected' : '' }}>Kasbon Karyawan</option>
                        <option value="Payroll" {{ request('module') == 'Payroll' ? 'selected' : '' }}>Penggajian</option>
                        <option value="Expense" {{ request('module') == 'Expense' ? 'selected' : '' }}>Pengeluaran</option>
                        <option value="CompanyDebt" {{ request('module') == 'CompanyDebt' ? 'selected' : '' }}>Hutang Perusahaan</option>
                        <option value="CompanyReceivable" {{ request('module') == 'CompanyReceivable' ? 'selected' : '' }}>Piutang Perusahaan</option>
                        <option value="User" {{ request('module') == 'User' ? 'selected' : '' }}>Akses Pengguna</option>
                        <option value="Auth" {{ request('module') == 'Auth' ? 'selected' : '' }}>Login / Logout</option>
                    </select>
                </div>

                <!-- Date Filter -->
                <div>
                    <input type="date" name="date" value="{{ request('date') }}" 
                           class="w-full text-xs bg-white/90 border border-slate-200/90 rounded-xl px-3 py-2.5 text-slate-700 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all shadow-2xs">
                </div>

                <!-- Actions -->
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-teal-700 to-emerald-800 hover:from-teal-800 hover:to-emerald-900 text-white font-bold text-xs py-2.5 px-4 rounded-xl shadow-sm transition-all flex items-center justify-center gap-1.5 active:scale-95">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
                        </svg>
                        Filter
                    </button>
                    @if(request('search') || request('user_id') || request('date') || request('module'))
                        <a href="{{ route('activity-logs.index') }}" class="glass-pill text-xs font-bold text-slate-600 hover:text-slate-900 py-2.5 px-3 rounded-xl transition-all flex items-center justify-center shadow-2xs">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Table Card -->
        <div class="glass-card rounded-[26px] overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-200/70 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            <th class="px-5 py-4">Waktu & Tanggal</th>
                            <th class="px-5 py-4">Pengguna</th>
                            <th class="px-5 py-4">Modul</th>
                            <th class="px-5 py-4">Aktivitas</th>
                            <th class="px-5 py-4">Catatan & Rincian Data</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100/90 text-xs">
                        @forelse ($logs as $log)
                            @php
                                $act = strtolower($log->activity);
                                $badgeStyle = 'bg-slate-100 text-slate-700 border-slate-200';
                                
                                if (str_contains($act, 'create') || str_contains($act, 'tambah')) {
                                    $badgeStyle = 'bg-emerald-50 text-emerald-800 border-emerald-200/80';
                                } elseif (str_contains($act, 'update') || str_contains($act, 'ubah') || str_contains($act, 'edit')) {
                                    $badgeStyle = 'bg-amber-50 text-amber-800 border-amber-200/80';
                                } elseif (str_contains($act, 'delete') || str_contains($act, 'hapus')) {
                                    $badgeStyle = 'bg-rose-50 text-rose-800 border-rose-200/80';
                                } elseif (str_contains($act, 'login')) {
                                    $badgeStyle = 'bg-blue-50 text-blue-800 border-blue-200/80';
                                } elseif (str_contains($act, 'logout')) {
                                    $badgeStyle = 'bg-purple-50 text-purple-800 border-purple-200/80';
                                }

                                $props = is_array($log->properties) ? $log->properties : json_decode($log->properties, true);
                                $hasChanges = !empty($props['changes']);
                                $hasDetails = !empty($props['details']);
                                $hasAttributes = !empty($props['attributes']);
                                $hasAnyDetail = $hasChanges || $hasDetails || $hasAttributes;
                            @endphp
                            <tr class="hover:bg-teal-50/20 transition-colors">
                                <!-- Waktu & Tanggal -->
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="p-1.5 rounded-lg bg-slate-100/80 text-slate-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-extrabold text-slate-800 text-xs">
                                                {{ $log->created_at->setTimezone('Asia/Jakarta')->format('H:i') }} WIB
                                            </div>
                                            <div class="text-[11px] font-medium text-slate-400">
                                                {{ $log->created_at->setTimezone('Asia/Jakarta')->format('d M Y') }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Pengguna & IP -->
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-teal-700 to-emerald-800 text-white font-extrabold text-xs flex items-center justify-center shadow-xs">
                                            {{ $log->user ? substr($log->user->name, 0, 1) : 'S' }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-800">
                                                {{ $log->user ? $log->user->name : 'Sistem / Otomatis' }}
                                            </div>
                                            <div class="text-[10px] text-slate-400 flex items-center gap-1 mt-0.5">
                                                <span class="font-mono bg-slate-100/90 px-1.5 py-0.5 rounded text-slate-600">{{ $log->ip_address ?? '127.0.0.1' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Modul -->
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-xl bg-white/80 border border-slate-200/70 text-slate-700 text-xs font-bold shadow-2xs">
                                        <span class="w-1.5 h-1.5 rounded-full bg-teal-600"></span>
                                        {{ $log->module_label ?? 'Sistem' }}
                                    </span>
                                </td>

                                <!-- Aktivitas -->
                                <td class="px-5 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1 inline-flex text-[11px] font-black rounded-lg border uppercase tracking-wider {{ $badgeStyle }}">
                                        {{ $log->activity }}
                                    </span>
                                </td>

                                <!-- Catatan & Detail Data -->
                                <td class="px-5 py-4">
                                    <div class="max-w-md">
                                        <!-- Primary readable summary note -->
                                        <p class="font-semibold text-slate-800 text-xs leading-relaxed">
                                            {{ $log->formatted_summary }}
                                        </p>

                                        <!-- If details or changes are available, show expandable pill -->
                                        @if($hasAnyDetail)
                                            <div class="mt-2" x-data="{ expanded: false }">
                                                <button @click="expanded = !expanded" type="button" 
                                                        class="inline-flex items-center gap-1.5 text-[11px] font-bold text-teal-700 hover:text-teal-900 bg-teal-50/70 hover:bg-teal-100/80 px-2.5 py-1 rounded-lg border border-teal-200/60 transition-colors">
                                                    <span x-text="expanded ? 'Tutup Rincian' : 'Lihat Detail Perubahan'"></span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3 h-3 transition-transform" :class="expanded ? 'rotate-180' : ''">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                                    </svg>
                                                </button>

                                                <!-- Expanded detail card -->
                                                <div x-show="expanded" x-collapse x-cloak class="mt-2.5 p-3 rounded-xl bg-white/95 border border-slate-200/90 shadow-sm text-xs space-y-2">
                                                    @if($hasChanges)
                                                        <div class="font-bold text-slate-700 border-b pb-1 mb-1.5 text-[11px] flex items-center justify-between">
                                                            <span>Rincian Kolom yang Diubah:</span>
                                                            <span class="text-teal-700 font-extrabold">{{ count($props['changes']) }} atribut</span>
                                                        </div>
                                                        <div class="space-y-1.5">
                                                            @foreach($props['changes'] as $change)
                                                                <div class="flex flex-col sm:flex-row sm:items-center justify-between text-xs py-1 border-b border-dashed border-slate-100 last:border-0 gap-1">
                                                                    <span class="font-bold text-slate-700 sm:w-1/3">{{ $change['label'] ?? $change['field'] }}:</span>
                                                                    <div class="flex items-center gap-2 sm:w-2/3">
                                                                        <span class="px-2 py-0.5 rounded bg-rose-50 text-rose-700 font-medium line-through text-[11px]">{{ $change['old'] ?? '-' }}</span>
                                                                        <span class="text-slate-400">→</span>
                                                                        <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-800 font-bold text-[11px]">{{ $change['new'] ?? '-' }}</span>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @elseif($hasDetails)
                                                        <div class="font-bold text-slate-700 border-b pb-1 mb-1.5 text-[11px]">Rincian Data:</div>
                                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                                            @foreach($props['details'] as $item)
                                                                <div class="bg-slate-50/70 p-2 rounded-lg border border-slate-100">
                                                                    <div class="text-[10px] font-bold text-slate-400 uppercase">{{ $item['label'] ?? $item['field'] }}</div>
                                                                    <div class="font-bold text-slate-800 text-xs mt-0.5">{{ $item['value'] ?? '-' }}</div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @elseif($hasAttributes)
                                                        <div class="font-bold text-slate-700 border-b pb-1 mb-1.5 text-[11px]">Snapshot Atribut:</div>
                                                        <pre class="p-2 bg-slate-50 rounded-lg text-[10px] font-mono text-slate-600 max-h-36 overflow-y-auto whitespace-pre-wrap">{{ json_encode($props['attributes'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <!-- Fallback info for auth or legacy entries -->
                                            <div class="mt-1 flex items-center gap-2 text-[11px] text-slate-400 font-medium">
                                                <span>{{ $log->user_agent ? Str::limit($log->user_agent, 45) : 'Aktivitas terekam' }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                    <div class="w-12 h-12 mx-auto rounded-2xl bg-teal-50 flex items-center justify-center text-teal-700 mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                    </div>
                                    <div class="font-bold text-slate-700">Belum Ada Riwayat Aktivitas</div>
                                    <p class="text-xs text-slate-400 mt-1">Belum ada aktivitas yang sesuai dengan kriteria filter pencarian.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Bar -->
            <div class="p-4 sm:p-5 border-t border-slate-100/90 bg-white/40">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
