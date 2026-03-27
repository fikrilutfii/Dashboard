<aside 
    class="fixed inset-y-0 left-0 bg-zinc-900 text-zinc-300 transition-all duration-300 transform z-30 overflow-y-auto lg:static lg:inset-auto border-r border-zinc-800"
    :class="{
        '-translate-x-full': !sidebarOpen, 
        'translate-x-0': sidebarOpen, 
        'lg:translate-x-0': true,
        'w-72': sidebarExpanded, 
        'lg:w-20': !sidebarExpanded
    }"
>
    <!-- Logo -->
    <div class="h-20 flex items-center justify-center sticky top-0 z-10 bg-zinc-900/95 backdrop-blur-sm"
        :class="sidebarExpanded ? 'px-8' : 'px-4'">
        <div class="flex items-center gap-3 overflow-hidden">
            <!-- Icon Logo -->
            <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-indigo-500 to-violet-600 rounded-xl flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-indigo-500/20">
                A
            </div>
            <!-- Text Logo -->
            <div x-show="sidebarExpanded"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform -translate-x-2"
                x-transition:enter-end="opacity-100 transform translate-x-0"
                class="flex flex-col"
            >
                <h1 class="text-lg font-bold tracking-tight text-white leading-none">ABADI</h1>
                <span class="text-xs font-medium text-zinc-500 tracking-widest uppercase">Sentosa</span>
            </div>
        </div>
    </div>

    <!-- Menu -->
    <nav class="flex-1 py-6 px-3 space-y-1">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" 
           class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative
           {{ request()->routeIs('dashboard') ? 'bg-indigo-600/10 text-indigo-400' : 'text-zinc-400 hover:bg-zinc-800/50 hover:text-white' }}
           "
           :class="sidebarExpanded ? '' : 'justify-center'"
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 flex-shrink-0 transition-colors {{ request()->routeIs('dashboard') ? 'text-indigo-400' : 'text-zinc-500 group-hover:text-zinc-300' }}">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
            </svg>
            <span class="ml-3 font-medium whitespace-nowrap" x-show="sidebarExpanded">Dashboard</span>
             <div x-show="!sidebarExpanded" class="absolute left-full top-1/2 -translate-y-1/2 ml-4 px-2 py-1 bg-zinc-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 whitespace-nowrap shadow-xl border border-zinc-700">Dashboard</div>
        </a>

        @if(session('division') == 'percetakan')
            <div class="mt-8 mb-2 px-3" x-show="sidebarExpanded">
                <span class="text-xs font-bold text-zinc-500 uppercase tracking-widest">Transaksi Percetakan</span>
            </div>
            
            <a href="{{ route('invoices.index', ['division' => 'percetakan']) }}" 
               class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative
               {{ request()->fullUrlIs(route('invoices.index', ['division' => 'percetakan'])) ? 'bg-indigo-600/10 text-indigo-400' : 'text-zinc-400 hover:bg-zinc-800/50 hover:text-white' }}
               "
               :class="sidebarExpanded ? '' : 'justify-center'"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 flex-shrink-0 transition-colors {{ request()->fullUrlIs(route('invoices.index', ['division' => 'percetakan'])) ? 'text-indigo-400' : 'text-zinc-500 group-hover:text-zinc-300' }}">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
                <span class="ml-3 font-medium whitespace-nowrap" x-show="sidebarExpanded">Faktur Penjualan</span>
                 <div x-show="!sidebarExpanded" class="absolute left-full top-1/2 -translate-y-1/2 ml-4 px-2 py-1 bg-zinc-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 whitespace-nowrap shadow-xl border border-zinc-700">Faktur Penjualan</div>
            </a>

            <a href="{{ route('expenses.index') }}" 
               class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative
               {{ request()->routeIs('expenses.*') ? 'bg-indigo-600/10 text-indigo-400' : 'text-zinc-400 hover:bg-zinc-800/50 hover:text-white' }}
               "
               :class="sidebarExpanded ? '' : 'justify-center'"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 flex-shrink-0 transition-colors {{ request()->routeIs('expenses.*') ? 'text-indigo-400' : 'text-zinc-500 group-hover:text-zinc-300' }}">
                     <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg>
                <span class="ml-3 font-medium whitespace-nowrap" x-show="sidebarExpanded">Pengeluaran</span>
                <div x-show="!sidebarExpanded" class="absolute left-full top-1/2 -translate-y-1/2 ml-4 px-2 py-1 bg-zinc-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 whitespace-nowrap shadow-xl border border-zinc-700">Pengeluaran</div>
            </a>

             <a href="{{ route('payrolls.index') }}" 
               class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative
               {{ request()->routeIs('payrolls.*') ? 'bg-indigo-600/10 text-indigo-400' : 'text-zinc-400 hover:bg-zinc-800/50 hover:text-white' }}
               "
               :class="sidebarExpanded ? '' : 'justify-center'"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 flex-shrink-0 transition-colors {{ request()->routeIs('payrolls.*') ? 'text-indigo-400' : 'text-zinc-500 group-hover:text-zinc-300' }}">
                     <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                </svg>
                <span class="ml-3 font-medium whitespace-nowrap" x-show="sidebarExpanded">Penggajian</span>
                 <div x-show="!sidebarExpanded" class="absolute left-full top-1/2 -translate-y-1/2 ml-4 px-2 py-1 bg-zinc-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 whitespace-nowrap shadow-xl border border-zinc-700">Penggajian</div>
            </a>



        @elseif(session('division') == 'konfeksi')
             <div class="mt-8 mb-2 px-3" x-show="sidebarExpanded">
                <span class="text-xs font-bold text-zinc-500 uppercase tracking-widest">Transaksi Konfeksi</span>
            </div>
             <a href="{{ route('invoices.index', ['division' => 'konfeksi']) }}" 
               class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative
               {{ request()->fullUrlIs(route('invoices.index', ['division' => 'konfeksi'])) ? 'bg-indigo-600/10 text-indigo-400' : 'text-zinc-400 hover:bg-zinc-800/50 hover:text-white' }}
               "
               :class="sidebarExpanded ? '' : 'justify-center'"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 flex-shrink-0 transition-colors {{ request()->fullUrlIs(route('invoices.index', ['division' => 'konfeksi'])) ? 'text-indigo-400' : 'text-zinc-500 group-hover:text-zinc-300' }}">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
                <span class="ml-3 font-medium whitespace-nowrap" x-show="sidebarExpanded">Faktur Penjualan</span>
                 <div x-show="!sidebarExpanded" class="absolute left-full top-1/2 -translate-y-1/2 ml-4 px-2 py-1 bg-zinc-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 whitespace-nowrap shadow-xl border border-zinc-700">Faktur Penjualan</div>
            </a>
            
             <a href="{{ route('expenses.index') }}" 
               class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative
               {{ request()->routeIs('expenses.*') ? 'bg-indigo-600/10 text-indigo-400' : 'text-zinc-400 hover:bg-zinc-800/50 hover:text-white' }}
               "
               :class="sidebarExpanded ? '' : 'justify-center'"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 flex-shrink-0 transition-colors {{ request()->routeIs('expenses.*') ? 'text-indigo-400' : 'text-zinc-500 group-hover:text-zinc-300' }}">
                     <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg>
                <span class="ml-3 font-medium whitespace-nowrap" x-show="sidebarExpanded">Pengeluaran</span>
                <div x-show="!sidebarExpanded" class="absolute left-full top-1/2 -translate-y-1/2 ml-4 px-2 py-1 bg-zinc-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 whitespace-nowrap shadow-xl border border-zinc-700">Pengeluaran</div>
            </a>

            

             <a href="{{ route('payrolls.index') }}" 
               class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative
               {{ request()->routeIs('payrolls.*') ? 'bg-indigo-600/10 text-indigo-400' : 'text-zinc-400 hover:bg-zinc-800/50 hover:text-white' }}
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

        <!-- Finance -->
        <div class="mt-8 mb-2 px-3" x-show="sidebarExpanded">
            <span class="text-xs font-bold text-zinc-500 uppercase tracking-widest">Keuangan & Laporan</span>
        </div>

        <a href="{{ route('finance.index') }}" 
           class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group relative
           {{ request()->routeIs('finance.index') ? 'bg-indigo-600/10 text-indigo-400' : 'text-zinc-400 hover:bg-zinc-800/50 hover:text-white' }}
           "
           :class="sidebarExpanded ? '' : 'justify-center'"
        >
             <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 flex-shrink-0 transition-colors {{ request()->routeIs('finance.index') ? 'text-indigo-400' : 'text-zinc-500 group-hover:text-zinc-300' }}">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
            </svg>
            <span class="ml-3 font-medium whitespace-nowrap" x-show="sidebarExpanded">Laporan Keuangan</span>
             <div x-show="!sidebarExpanded" class="absolute left-full top-1/2 -translate-y-1/2 ml-4 px-2 py-1 bg-zinc-800 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-50 whitespace-nowrap shadow-xl border border-zinc-700">Laporan Keuangan</div>
        </a>

        <!-- Transaksi Collapsible -->
        <div x-data="{ transaksiOpen: {{ request()->routeIs(['finance.pemasukan', 'company-debts.*', 'company-receivables.*']) ? 'true' : 'false' }} }" class="mt-2 text-sm">
            <button @click="transaksiOpen = !transaksiOpen" 
                class="w-full flex items-center justify-between px-3 py-2.5 rounded-xl transition-all duration-200 group
                {{ request()->routeIs(['finance.pemasukan', 'company-debts.*', 'company-receivables.*']) ? 'bg-zinc-800/50 text-white' : 'text-zinc-400 hover:bg-zinc-800/50 hover:text-white' }}
                "
                :class="sidebarExpanded ? '' : 'justify-center'"
            >
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 flex-shrink-0 transition-colors {{ request()->routeIs(['finance.pemasukan', 'company-debts.*', 'company-receivables.*']) ? 'text-indigo-400' : 'text-zinc-500 group-hover:text-zinc-300' }}">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="ml-3 font-medium whitespace-nowrap" x-show="sidebarExpanded">Transaksi</span>
                </div>
                <svg x-show="sidebarExpanded" :class="transaksiOpen ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-200 text-zinc-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="transaksiOpen && sidebarExpanded" 
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 -translate-y-2"
                 x-transition:enter-end="transform opacity-100 translate-y-0"
                 class="mt-1 ml-9 border-l-2 border-zinc-800 space-y-1"
            >
                <a href="{{ route('finance.pemasukan') }}" 
                   class="block px-4 py-2 hover:text-white rounded-lg {{ request()->routeIs('finance.pemasukan') ? 'text-white font-semibold' : 'text-zinc-500' }}">
                    Pemasukan
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
                <a href="{{ route('employees.index') }}" 
                   class="block px-4 py-2 text-sm transition-colors rounded-lg {{ request()->routeIs('employees.*') ? 'text-white font-semibold' : 'text-zinc-500 hover:text-white hover:bg-zinc-800/30' }}">
                   Karyawan
                </a>
                <a href="{{ route('kasbons.index') }}" 
                   class="block px-4 py-2 text-sm transition-colors rounded-lg {{ request()->routeIs('kasbons.*') ? 'text-white font-semibold' : 'text-zinc-500 hover:text-white hover:bg-zinc-800/30' }}">
                   Kasbon Karyawan
                </a>
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

        </a>
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
