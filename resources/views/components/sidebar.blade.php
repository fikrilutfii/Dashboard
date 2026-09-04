<aside 
    class="fixed inset-y-0 left-0 bg-zinc-950 text-zinc-400 transition-all duration-500 transform z-30 overflow-y-auto lg:static lg:inset-auto border-r border-white/5 scrollbar-hide shadow-2xl shadow-black/50"
    :class="{
        '-translate-x-full': !sidebarOpen, 
        'translate-x-0': sidebarOpen, 
        'lg:translate-x-0': true,
        'w-72': sidebarExpanded, 
        'lg:w-20': !sidebarExpanded
    }"
>
    <!-- Logo -->
    <div class="h-24 flex items-center justify-center sticky top-0 z-10 bg-zinc-950/80 backdrop-blur-md border-b border-white/5"
        :class="sidebarExpanded ? 'px-8' : 'px-4'">
        <div class="flex items-center gap-3 overflow-hidden">
            <!-- Icon Logo -->
            <div class="flex-shrink-0 w-12 h-12 premium-gradient rounded-2xl flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-primary-500/30 transform transition-transform duration-300 hover:rotate-6">
                A
            </div>
            <!-- Text Logo -->
            <div x-show="sidebarExpanded"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform -translate-x-4"
                x-transition:enter-end="opacity-100 transform translate-x-0"
                class="flex flex-col"
            >
                <h1 class="text-xl font-black tracking-tighter text-white leading-none">ABADI</h1>
                <span class="text-[10px] font-bold text-primary-400 tracking-[0.3em] uppercase mt-1">Sentosa</span>
            </div>
        </div>
    </div>

    <!-- Menu Navigation -->
    <nav class="flex-1 py-6 px-3 space-y-1">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" 
           class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative
           {{ request()->routeIs(['dashboard', 'farm.dashboard']) ? 'bg-indigo-600/10 text-indigo-400 font-semibold' : 'text-zinc-400 hover:bg-zinc-800/50 hover:text-white' }}
           "
           :class="sidebarExpanded ? '' : 'justify-center'"
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 flex-shrink-0 transition-colors {{ request()->routeIs(['dashboard', 'farm.dashboard']) ? 'text-indigo-400' : 'text-zinc-500 group-hover:text-zinc-300' }}">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
            </svg>
            <span class="ml-3 font-medium whitespace-nowrap" x-show="sidebarExpanded">Dashboard</span>
            <div x-show="!sidebarExpanded" class="absolute left-full top-1/2 -translate-y-1/2 ml-4 px-2 py-1 bg-zinc-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 whitespace-nowrap shadow-xl border border-zinc-700">Dashboard</div>
        </a>

        @if(session('division') == 'peternakan')
            <!-- TRANSAKSI PETERNAKAN -->
            <div class="mt-8 mb-2 px-3" x-show="sidebarExpanded">
                <span class="text-xs font-bold text-amber-500 uppercase tracking-widest">
                    TRANSAKSI PETERNAKAN
                </span>
            </div>
            
            <!-- Faktur Penjualan Collapsible -->
            <div x-data="{ fakturOpen: {{ request()->routeIs(['farm.invoices.*', 'farm.billing.*']) ? 'true' : 'false' }} }" class="mt-2 text-sm">
                <button @click="fakturOpen = !fakturOpen" 
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl transition-all duration-200 group
                    {{ request()->routeIs(['farm.invoices.*', 'farm.billing.*']) ? 'bg-zinc-800/50 text-white' : 'text-zinc-400 hover:bg-zinc-800/50 hover:text-white' }}
                    "
                    :class="sidebarExpanded ? '' : 'justify-center'"
                >
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 flex-shrink-0 transition-colors {{ request()->routeIs(['farm.invoices.*', 'farm.billing.*']) ? 'text-amber-400' : 'text-zinc-500 group-hover:text-zinc-300' }}">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                        <span class="ml-3 font-medium whitespace-nowrap" x-show="sidebarExpanded">Faktur Penjualan</span>
                    </div>
                    <svg x-show="sidebarExpanded" :class="fakturOpen ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-200 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                    <div x-show="!sidebarExpanded" class="absolute left-full top-1/2 -translate-y-1/2 ml-4 px-2 py-1 bg-zinc-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 whitespace-nowrap shadow-xl border border-zinc-700">Faktur Penjualan</div>
                </button>

                <div x-show="fakturOpen && sidebarExpanded" 
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 -translate-y-2"
                     x-transition:enter-end="transform opacity-100 translate-y-0"
                     class="mt-1 ml-9 border-l-2 border-zinc-800 space-y-1"
                >
                    <a href="{{ route('farm.invoices.index') }}" 
                       class="block px-4 py-2 text-sm transition-colors rounded-lg {{ request()->routeIs('farm.invoices.*') ? 'text-white font-semibold' : 'text-zinc-500 hover:text-white hover:bg-zinc-800/30' }}">
                        Daftar Faktur
                    </a>
                    <a href="{{ route('farm.billing.index') }}" 
                       class="block px-4 py-2 text-sm transition-colors rounded-lg {{ request()->routeIs('farm.billing.*') ? 'text-white font-semibold' : 'text-zinc-500 hover:text-white hover:bg-zinc-800/30' }}">
                        Tagihan Klien
                    </a>
                </div>
            </div>

            <!-- Penggajian Pegawai -->
            <a href="{{ route('farm.payroll.index') }}" 
               class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative mt-1
               {{ request()->routeIs('farm.payroll.*') ? 'bg-amber-600/10 text-amber-400 font-semibold' : 'text-zinc-400 hover:bg-zinc-800/50 hover:text-white' }}
               "
               :class="sidebarExpanded ? '' : 'justify-center'"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 flex-shrink-0 transition-colors {{ request()->routeIs('farm.payroll.*') ? 'text-amber-400' : 'text-zinc-500 group-hover:text-zinc-300' }}">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="ml-3 font-medium whitespace-nowrap" x-show="sidebarExpanded">Penggajian Pegawai</span>
                <div x-show="!sidebarExpanded" class="absolute left-full top-1/2 -translate-y-1/2 ml-4 px-2 py-1 bg-zinc-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 whitespace-nowrap shadow-xl border border-zinc-700">Penggajian Pegawai</div>
            </a>

            <!-- OPERASIONAL PETERNAKAN -->
            <div class="mt-8 mb-2 px-3" x-show="sidebarExpanded">
                <span class="text-xs font-bold text-zinc-500 uppercase tracking-widest">OPERASIONAL PETERNAKAN</span>
            </div>

            <!-- Log Operasional -->
            <a href="{{ route('farm.operational.index') }}" 
               class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative
               {{ request()->routeIs('farm.operational.*') ? 'bg-amber-600/10 text-amber-400 font-semibold' : 'text-zinc-400 hover:bg-zinc-800/50 hover:text-white' }}
               "
               :class="sidebarExpanded ? '' : 'justify-center'"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 flex-shrink-0 transition-colors {{ request()->routeIs('farm.operational.*') ? 'text-amber-400' : 'text-zinc-500 group-hover:text-zinc-300' }}">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <span class="ml-3 font-medium whitespace-nowrap" x-show="sidebarExpanded">Log Operasional</span>
                <div x-show="!sidebarExpanded" class="absolute left-full top-1/2 -translate-y-1/2 ml-4 px-2 py-1 bg-zinc-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 whitespace-nowrap shadow-xl border border-zinc-700">Log Operasional</div>
            </a>

            <!-- Transportasi -->
            <a href="{{ route('farm.transportation.index') }}" 
               class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative mt-1
               {{ request()->routeIs('farm.transportation.*') ? 'bg-amber-600/10 text-amber-400 font-semibold' : 'text-zinc-400 hover:bg-zinc-800/50 hover:text-white' }}
               "
               :class="sidebarExpanded ? '' : 'justify-center'"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 flex-shrink-0 transition-colors {{ request()->routeIs('farm.transportation.*') ? 'text-amber-400' : 'text-zinc-500 group-hover:text-zinc-300' }}">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
                <span class="ml-3 font-medium whitespace-nowrap" x-show="sidebarExpanded">Transportasi</span>
                <div x-show="!sidebarExpanded" class="absolute left-full top-1/2 -translate-y-1/2 ml-4 px-2 py-1 bg-zinc-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 whitespace-nowrap shadow-xl border border-zinc-700">Transportasi</div>
            </a>

            <!-- Catatan Pengeluaran -->
            <a href="{{ route('farm.expenses.index') }}" 
               class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative mt-1
               {{ request()->routeIs('farm.expenses.*') ? 'bg-amber-600/10 text-amber-400 font-semibold' : 'text-zinc-400 hover:bg-zinc-800/50 hover:text-white' }}
               "
               :class="sidebarExpanded ? '' : 'justify-center'"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 flex-shrink-0 transition-colors {{ request()->routeIs('farm.expenses.*') ? 'text-amber-400' : 'text-zinc-500 group-hover:text-zinc-300' }}">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span class="ml-3 font-medium whitespace-nowrap" x-show="sidebarExpanded">Catatan Pengeluaran</span>
                <div x-show="!sidebarExpanded" class="absolute left-full top-1/2 -translate-y-1/2 ml-4 px-2 py-1 bg-zinc-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 whitespace-nowrap shadow-xl border border-zinc-700">Catatan Pengeluaran</div>
            </a>

            <!-- MASTER DATA PETERNAKAN -->
            <div x-data="{ masterDataOpen: {{ request()->routeIs(['farm.master.*']) ? 'true' : 'false' }} }" class="mt-4">
                <button @click="masterDataOpen = !masterDataOpen" 
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl transition-all duration-200 group
                    {{ request()->routeIs(['farm.master.*']) ? 'bg-zinc-800/50 text-white' : 'text-zinc-400 hover:bg-zinc-800/50 hover:text-white' }}
                    "
                    :class="sidebarExpanded ? '' : 'justify-center'"
                >
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 flex-shrink-0 transition-colors {{ request()->routeIs(['farm.master.*']) ? 'text-amber-400' : 'text-zinc-500 group-hover:text-zinc-300' }}">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                        </svg>
                        <span class="ml-3 font-medium whitespace-nowrap" x-show="sidebarExpanded">Master Data</span>
                    </div>
                    <svg x-show="sidebarExpanded" :class="masterDataOpen ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-200 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                    <div x-show="!sidebarExpanded" class="absolute left-full top-1/2 -translate-y-1/2 ml-4 px-2 py-1 bg-zinc-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 whitespace-nowrap shadow-xl border border-zinc-700">Master Data</div>
                </button>

                <div x-show="masterDataOpen && sidebarExpanded" 
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 -translate-y-2"
                     x-transition:enter-end="transform opacity-100 translate-y-0"
                     class="mt-1 ml-9 border-l-2 border-zinc-800 space-y-1"
                >
                    <a href="{{ route('farm.master.coops.index') }}" 
                       class="block px-4 py-2 text-sm transition-colors rounded-lg {{ request()->routeIs('farm.master.coops.*') ? 'text-white font-semibold' : 'text-zinc-500 hover:text-white hover:bg-zinc-800/30' }}">
                        Data Kandang
                    </a>
                    <a href="{{ route('farm.master.customers.index') }}" 
                       class="block px-4 py-2 text-sm transition-colors rounded-lg {{ request()->routeIs('farm.master.customers.*') ? 'text-white font-semibold' : 'text-zinc-500 hover:text-white hover:bg-zinc-800/30' }}">
                        Pembeli Panen
                    </a>
                    <a href="{{ route('farm.master.suppliers.index') }}" 
                       class="block px-4 py-2 text-sm transition-colors rounded-lg {{ request()->routeIs('farm.master.suppliers.*') ? 'text-white font-semibold' : 'text-zinc-500 hover:text-white hover:bg-zinc-800/30' }}">
                        Supplier DOC & Pakan
                    </a>
                </div>
            </div>

        @else
            <!-- TRANSAKSI PERCETAKAN / KONFEKSI -->
            <div class="mt-8 mb-2 px-3" x-show="sidebarExpanded">
                <span class="text-xs font-bold text-zinc-500 uppercase tracking-widest">
                    TRANSAKSI {{ strtoupper(session('division', 'PERCETAKAN')) }}
                </span>
            </div>
            
            <!-- Faktur Penjualan Collapsible -->
            <div x-data="{ fakturOpen: {{ request()->routeIs(['invoices.*', 'reports.billing*']) ? 'true' : 'false' }} }" class="mt-2 text-sm">
                <button @click="fakturOpen = !fakturOpen" 
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl transition-all duration-200 group
                    {{ request()->routeIs(['invoices.*', 'reports.billing*']) ? 'bg-zinc-800/50 text-white' : 'text-zinc-400 hover:bg-zinc-800/50 hover:text-white' }}
                    "
                    :class="sidebarExpanded ? '' : 'justify-center'"
                >
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 flex-shrink-0 transition-colors {{ request()->routeIs(['invoices.*', 'reports.billing*']) ? 'text-indigo-400' : 'text-zinc-500 group-hover:text-zinc-300' }}">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                        <span class="ml-3 font-medium whitespace-nowrap" x-show="sidebarExpanded">Faktur Penjualan</span>
                    </div>
                    <svg x-show="sidebarExpanded" :class="fakturOpen ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-200 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                    <div x-show="!sidebarExpanded" class="absolute left-full top-1/2 -translate-y-1/2 ml-4 px-2 py-1 bg-zinc-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 whitespace-nowrap shadow-xl border border-zinc-700">Faktur Penjualan</div>
                </button>

                <div x-show="fakturOpen && sidebarExpanded" 
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 -translate-y-2"
                     x-transition:enter-end="transform opacity-100 translate-y-0"
                     class="mt-1 ml-9 border-l-2 border-zinc-800 space-y-1"
                >
                    <a href="{{ route('invoices.index', ['division' => session('division', 'percetakan')]) }}" 
                       class="block px-4 py-2 text-sm transition-colors rounded-lg {{ request()->routeIs('invoices.*') ? 'text-white font-semibold' : 'text-zinc-500 hover:text-white hover:bg-zinc-800/30' }}">
                        Daftar Faktur
                    </a>
                    <a href="{{ route('reports.billing') }}" 
                       class="block px-4 py-2 text-sm transition-colors rounded-lg {{ request()->routeIs('reports.billing*') ? 'text-white font-semibold' : 'text-zinc-500 hover:text-white hover:bg-zinc-800/30' }}">
                        Laporan Tagihan Klien
                    </a>
                </div>
            </div>

            @if(!in_array(Auth::user()->role, ['admin3', 'limited_invoice']))
            <!-- Penggajian -->
            <a href="{{ route('payrolls.index') }}" 
               class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative mt-1
               {{ request()->routeIs('payrolls.*') ? 'bg-indigo-600/10 text-indigo-400 font-semibold' : 'text-zinc-400 hover:bg-zinc-800/50 hover:text-white' }}
               "
               :class="sidebarExpanded ? '' : 'justify-center'"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 flex-shrink-0 transition-colors {{ request()->routeIs('payrolls.*') ? 'text-indigo-400' : 'text-zinc-500 group-hover:text-zinc-300' }}">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                </svg>
                <span class="ml-3 font-medium whitespace-nowrap" x-show="sidebarExpanded">Penggajian</span>
                <div x-show="!sidebarExpanded" class="absolute left-full top-1/2 -translate-y-1/2 ml-4 px-2 py-1 bg-zinc-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 whitespace-nowrap shadow-xl border border-zinc-700">Penggajian</div>
            </a>
            @endif

            <!-- KEUANGAN & LAPORAN -->
            <div class="mt-8 mb-2 px-3" x-show="sidebarExpanded">
                <span class="text-xs font-bold text-zinc-500 uppercase tracking-widest">KEUANGAN & LAPORAN</span>
            </div>

            @if(!in_array(Auth::user()->role, ['admin3', 'limited_invoice']))
            <!-- Laporan Keuangan -->
            <a href="{{ route('finance.index') }}" 
               class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative
               {{ request()->routeIs('finance.index') ? 'bg-indigo-600/10 text-indigo-400 font-semibold' : 'text-zinc-400 hover:bg-zinc-800/50 hover:text-white' }}
               "
               :class="sidebarExpanded ? '' : 'justify-center'"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 flex-shrink-0 transition-colors {{ request()->routeIs('finance.index') ? 'text-indigo-400' : 'text-zinc-500 group-hover:text-zinc-300' }}">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                </svg>
                <span class="ml-3 font-medium whitespace-nowrap" x-show="sidebarExpanded">Laporan Keuangan</span>
                <div x-show="!sidebarExpanded" class="absolute left-full top-1/2 -translate-y-1/2 ml-4 px-2 py-1 bg-zinc-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 whitespace-nowrap shadow-xl border border-zinc-700">Laporan Keuangan</div>
            </a>
            @endif

            <!-- Dashboard Stok -->
            <a href="{{ route('reports.stock') }}" 
               class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative mt-1
               {{ request()->routeIs('reports.stock') ? 'bg-indigo-600/10 text-indigo-400 font-semibold' : 'text-zinc-400 hover:bg-zinc-800/50 hover:text-white' }}
               "
               :class="sidebarExpanded ? '' : 'justify-center'"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 flex-shrink-0 transition-colors {{ request()->routeIs('reports.stock') ? 'text-indigo-400' : 'text-zinc-500 group-hover:text-zinc-300' }}">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                </svg>
                <span class="ml-3 font-medium whitespace-nowrap" x-show="sidebarExpanded">Dashboard Stok</span>
                <div x-show="!sidebarExpanded" class="absolute left-full top-1/2 -translate-y-1/2 ml-4 px-2 py-1 bg-zinc-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 whitespace-nowrap shadow-xl border border-zinc-700">Dashboard Stok</div>
            </a>

            @if(Auth::user()->isAdmin())
            <!-- Log Aktivitas -->
            <a href="{{ route('activity-logs.index') }}" 
               class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative mt-1
               {{ request()->routeIs('activity-logs.*') ? 'bg-indigo-600/10 text-indigo-400 font-semibold' : 'text-zinc-400 hover:bg-zinc-800/50 hover:text-white' }}
               "
               :class="sidebarExpanded ? '' : 'justify-center'"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 flex-shrink-0 transition-colors {{ request()->routeIs('activity-logs.*') ? 'text-indigo-400' : 'text-zinc-500 group-hover:text-zinc-300' }}">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
                <span class="ml-3 font-medium whitespace-nowrap" x-show="sidebarExpanded">Log Aktivitas</span>
                <div x-show="!sidebarExpanded" class="absolute left-full top-1/2 -translate-y-1/2 ml-4 px-2 py-1 bg-zinc-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 whitespace-nowrap shadow-xl border border-zinc-700">Log Aktivitas</div>
            </a>

            <!-- Manajemen Akses -->
            <a href="{{ route('user-access.index') }}" 
               class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative mt-1
               {{ request()->routeIs('user-access.*') ? 'bg-indigo-600/10 text-indigo-400 font-semibold' : 'text-zinc-400 hover:bg-zinc-800/50 hover:text-white' }}
               "
               :class="sidebarExpanded ? '' : 'justify-center'"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 flex-shrink-0 transition-colors {{ request()->routeIs('user-access.*') ? 'text-indigo-400' : 'text-zinc-500 group-hover:text-zinc-300' }}">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
                <span class="ml-3 font-medium whitespace-nowrap" x-show="sidebarExpanded">Manajemen Akses</span>
                <div x-show="!sidebarExpanded" class="absolute left-full top-1/2 -translate-y-1/2 ml-4 px-2 py-1 bg-zinc-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 whitespace-nowrap shadow-xl border border-zinc-700">Manajemen Akses</div>
            </a>
            @endif

            @if(!in_array(Auth::user()->role, ['admin3', 'limited_invoice']))
            <!-- Transaksi Collapsible -->
            <div x-data="{ transaksiOpen: {{ request()->routeIs(['finance.transactions', 'company-debts.*', 'company-receivables.*']) ? 'true' : 'false' }} }" class="mt-2 text-sm">
                <button @click="transaksiOpen = !transaksiOpen" 
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl transition-all duration-200 group
                    {{ request()->routeIs(['finance.transactions', 'company-debts.*', 'company-receivables.*']) ? 'bg-zinc-800/50 text-white' : 'text-zinc-400 hover:bg-zinc-800/50 hover:text-white' }}
                    "
                    :class="sidebarExpanded ? '' : 'justify-center'"
                >
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 flex-shrink-0 transition-colors {{ request()->routeIs(['finance.transactions', 'company-debts.*', 'company-receivables.*']) ? 'text-indigo-400' : 'text-zinc-500 group-hover:text-zinc-300' }}">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="ml-3 font-medium whitespace-nowrap" x-show="sidebarExpanded">Transaksi</span>
                    </div>
                    <svg x-show="sidebarExpanded" :class="transaksiOpen ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-200 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                    <div x-show="!sidebarExpanded" class="absolute left-full top-1/2 -translate-y-1/2 ml-4 px-2 py-1 bg-zinc-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 whitespace-nowrap shadow-xl border border-zinc-700">Transaksi</div>
                </button>

                <div x-show="transaksiOpen && sidebarExpanded" 
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 -translate-y-2"
                     x-transition:enter-end="transform opacity-100 translate-y-0"
                     class="mt-1 ml-9 border-l-2 border-zinc-800 space-y-1"
                >
                    <a href="{{ route('finance.transactions') }}" 
                       class="block px-4 py-2 hover:text-white rounded-lg {{ request()->routeIs('finance.transactions') ? 'text-white font-semibold' : 'text-zinc-500' }}">
                        Transaksi
                    </a>
                    <a href="{{ route('company-debts.index') }}" 
                       class="block px-4 py-2 hover:text-white rounded-lg {{ request()->routeIs('company-debts.*') ? 'text-white font-semibold' : 'text-zinc-500' }}">
                        Pembayaran
                    </a>
                    <a href="{{ route('company-receivables.index') }}" 
                       class="block px-4 py-2 hover:text-white rounded-lg {{ request()->routeIs('company-receivables.*') ? 'text-white font-semibold' : 'text-zinc-500' }}">
                        Tagihan
                    </a>
                </div>
            </div>
            @endif

            <!-- Master Data Collapsible -->
            <div x-data="{ masterDataOpen: {{ request()->routeIs(['products.*', 'customers.*', 'suppliers.*', 'employees.*', 'kasbons.*']) ? 'true' : 'false' }} }" class="mt-4">
                <button @click="masterDataOpen = !masterDataOpen" 
                    class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl transition-all duration-200 group
                    {{ request()->routeIs(['products.*', 'customers.*', 'suppliers.*', 'employees.*', 'kasbons.*']) ? 'bg-zinc-800/50 text-white' : 'text-zinc-400 hover:bg-zinc-800/50 hover:text-white' }}
                    "
                    :class="sidebarExpanded ? '' : 'justify-center'"
                >
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 flex-shrink-0 transition-colors {{ request()->routeIs(['products.*', 'customers.*', 'suppliers.*', 'employees.*', 'kasbons.*']) ? 'text-indigo-400' : 'text-zinc-500 group-hover:text-zinc-300' }}">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                        </svg>
                        <span class="ml-3 font-medium whitespace-nowrap" x-show="sidebarExpanded">Master Data</span>
                    </div>
                    <svg x-show="sidebarExpanded" :class="masterDataOpen ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-200 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                    <div x-show="!sidebarExpanded" class="absolute left-full top-1/2 -translate-y-1/2 ml-4 px-2 py-1 bg-zinc-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 whitespace-nowrap shadow-xl border border-zinc-700">Master Data</div>
                </button>

                <!-- Submenu Items -->
                <div x-show="masterDataOpen && sidebarExpanded" 
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 -translate-y-2"
                     x-transition:enter-end="transform opacity-100 translate-y-0"
                     class="mt-1 ml-9 border-l-2 border-zinc-800 space-y-1"
                >
                    @if(!in_array(Auth::user()->role, ['admin3', 'limited_invoice']))
                    <a href="{{ route('employees.index') }}" 
                       class="block px-4 py-2 text-sm transition-colors rounded-lg {{ request()->routeIs('employees.*') ? 'text-white font-semibold' : 'text-zinc-500 hover:text-white hover:bg-zinc-800/30' }}">
                       Karyawan
                    </a>
                    <a href="{{ route('kasbons.index') }}" 
                       class="block px-4 py-2 text-sm transition-colors rounded-lg {{ request()->routeIs('kasbons.*') ? 'text-white font-semibold' : 'text-zinc-500 hover:text-white hover:bg-zinc-800/30' }}">
                       Kasbon Karyawan
                    </a>
                    @endif
                    <a href="{{ route('suppliers.index') }}" 
                       class="block px-4 py-2 text-sm transition-colors rounded-lg {{ request()->routeIs('suppliers.*') ? 'text-white font-semibold' : 'text-zinc-500 hover:text-white hover:bg-zinc-800/30' }}">
                       Supplier
                    </a>
                    <a href="{{ route('products.index') }}" 
                       class="block px-4 py-2 text-sm transition-colors rounded-lg {{ request()->routeIs('products.*') ? 'text-white font-semibold' : 'text-zinc-500 hover:text-white hover:bg-zinc-800/30' }}">
                       Produk
                    </a>
                    <a href="{{ route('customers.index') }}" 
                       class="block px-4 py-2 text-sm transition-colors rounded-lg {{ request()->routeIs('customers.*') ? 'text-white font-semibold' : 'text-zinc-500 hover:text-white hover:bg-zinc-800/30' }}">
                       Customer
                    </a>
                </div>
            </div>
        @endif
    </nav>

    <!-- Footer / Collapse Toggle -->
    <div class="p-4 border-t border-zinc-800/50" :class="sidebarExpanded ? '' : 'flex justify-center'">
        <!-- Switch Division -->
        <form method="POST" action="{{ route('division.switch') }}">
            @csrf
            <button type="submit" 
                class="flex items-center justify-center gap-3 w-full px-4 py-3 text-sm font-medium text-zinc-400 bg-zinc-800/50 rounded-xl hover:bg-zinc-800 hover:text-white transition-all duration-200 border border-zinc-700/50 hover:border-zinc-600"
                :class="sidebarExpanded ? '' : 'p-3 aspect-square'"
                title="Ganti Divisi"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                </svg>
                <span x-show="sidebarExpanded">Ganti Divisi</span>
            </button>
        </form>
    </div>
</aside>
