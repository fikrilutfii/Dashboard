<aside class="w-64 bg-gray-900 text-white min-h-screen flex flex-col transition-all duration-300" :class="{ '-ml-64': !open }">
    <!-- Logo -->
    <div class="h-16 flex items-center justify-center border-b border-gray-800 bg-gray-950">
        <h1 class="text-xl font-bold tracking-wider uppercase">ABADI SENTOSA</h1>
    </div>

    <!-- Menu -->
    <nav class="flex-1 overflow-y-auto py-4">
        <ul class="space-y-1">
            <!-- Dashboard -->
            <li>
                <a href="{{ route('dashboard') }}" class="flex items-center px-6 py-3 transition-all duration-200 hover:bg-gray-800 hover:text-white {{ request()->routeIs('dashboard') ? 'bg-primary-600 text-white shadow-lg' : 'text-gray-400' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3 text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
                    </svg>
                    <span>Dashboard</span>
                </a>
            </li>

            @if(session('division') == 'percetakan')
                <!-- Divisi Percetakan -->
                <li class="px-6 py-2 mt-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Divisi Percetakan
                </li>
                <li>
                    <a href="{{ route('invoices.index', ['division' => 'percetakan']) }}" class="flex items-center px-6 py-2 transition-all duration-200 hover:bg-gray-800 hover:text-white {{ request()->fullUrlIs(route('invoices.index', ['division' => 'percetakan'])) ? 'text-white bg-gray-800 border-l-4 border-primary-500' : 'text-gray-400' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3 text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                        <span>Faktur Penjualan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('purchases.index', ['division' => 'percetakan']) }}" class="flex items-center px-6 py-2 transition-all duration-200 hover:bg-gray-800 hover:text-white {{ request()->fullUrlIs(route('purchases.index', ['division' => 'percetakan'])) ? 'text-white bg-gray-800 border-l-4 border-primary-500' : 'text-gray-400' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3 text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                    </svg>
                        <span>Pembelian Bahan</span>
                    </a>
                </li>

                <!-- SDM (Karyawan & Kasbon) -->
                <li class="px-6 py-2 mt-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    SDM & Gaji
                </li>
                <li>
                    <a href="{{ route('employees.index') }}" class="flex items-center px-6 py-2 transition-all duration-200 hover:bg-gray-800 hover:text-white {{ request()->routeIs('employees.*') ? 'text-white bg-gray-800 border-l-4 border-primary-500' : 'text-gray-400' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3 text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                        <span>Karyawan & Gaji</span>
                    </a>
                </li>
                
                <!-- Master Data (Percetakan needs access too) -->
                 <li class="px-6 py-2 mt-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Master Data
                </li>
                <li>
                    <a href="{{ route('customers.index') }}" class="flex items-center px-6 py-2 transition-all duration-200 hover:bg-gray-800 hover:text-white {{ request()->routeIs('customers.*') ? 'text-white bg-gray-800 border-l-4 border-primary-500' : 'text-gray-400' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3 text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                    </svg>
                        <span>Customers</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('products.index') }}" class="flex items-center px-6 py-2 transition-all duration-200 hover:bg-gray-800 hover:text-white {{ request()->routeIs('products.*') ? 'text-white bg-gray-800 border-l-4 border-primary-500' : 'text-gray-400' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3 text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                    </svg>
                        <span>Produk & Jasa</span>
                    </a>
                </li>
                 <li>
                    <a href="{{ route('suppliers.index') }}" class="flex items-center px-6 py-2 transition-all duration-200 hover:bg-gray-800 hover:text-white {{ request()->routeIs('suppliers.*') ? 'text-white bg-gray-800 border-l-4 border-primary-500' : 'text-gray-400' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3 text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.125-.504 1.125-1.125V14.25m-2.25 4.5v3.375c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V18.75m-7.5-6.75h3.86a2.25 2.25 0 012.012 1.244l1.5 3a2.25 2.25 0 01-.437 2.486l-1.664 1.71a.75.75 0 01-.197.12l-.968.322a3.003 3.003 0 01-3.69-3.69l.322-.968a.75.75 0 01.12-.197l1.71-1.664a2.25 2.25 0 012.486-.437l3-1.5a2.25 2.25 0 011.244-2.012l3.86-1.287A1.125 1.125 0 0121 4.875v7.875M3 14.25h12.75" />
                    </svg>
                        <span>Suppliers</span>
                    </a>
                </li>
            @endif

            @if(session('division') == 'konfeksi')
                <!-- Divisi Konfeksi -->
                <li class="px-6 py-2 mt-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Divisi Konfeksi
                </li>
                <li>
                    <!-- Assuming Konfeksi uses same Invoice/Purchase but simplified or separate? 
                         User said "konveksipun hanya untuk fitur konveksi".
                         For now, I'll allow Invoices/Purchases but strictly filtered. 
                    -->
                    <a href="{{ route('invoices.index', ['division' => 'konfeksi']) }}" class="flex items-center px-6 py-2 transition-all duration-200 hover:bg-gray-800 hover:text-white {{ request()->fullUrlIs(route('invoices.index', ['division' => 'konfeksi'])) ? 'text-white bg-gray-800 border-l-4 border-primary-500' : 'text-gray-400' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3 text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                        <span>Penjualan Barang</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('purchases.index', ['division' => 'konfeksi']) }}" class="flex items-center px-6 py-2 transition-all duration-200 hover:bg-gray-800 hover:text-white {{ request()->fullUrlIs(route('purchases.index', ['division' => 'konfeksi'])) ? 'text-white bg-gray-800 border-l-4 border-primary-500' : 'text-gray-400' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3 text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                    </svg>
                        <span>Pembelian Bahan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('employees.index') }}" class="flex items-center px-6 py-2 transition-all duration-200 hover:bg-gray-800 hover:text-white {{ request()->routeIs('employees.*') ? 'text-white bg-gray-800 border-l-4 border-primary-500' : 'text-gray-400' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3 text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                        <span>Karyawan & Gaji</span>
                    </a>
                </li>
                
                <!-- Master Data (Konfeksi needs access too) -->
                 <li class="px-6 py-2 mt-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Master Data
                </li>
                <li>
                    <a href="{{ route('customers.index') }}" class="flex items-center px-6 py-2 transition-all duration-200 hover:bg-gray-800 hover:text-white {{ request()->routeIs('customers.*') ? 'text-white bg-gray-800 border-l-4 border-primary-500' : 'text-gray-400' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3 text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                    </svg>
                        <span>Customers</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('products.index') }}" class="flex items-center px-6 py-2 transition-all duration-200 hover:bg-gray-800 hover:text-white {{ request()->routeIs('products.*') ? 'text-white bg-gray-800 border-l-4 border-primary-500' : 'text-gray-400' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3 text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                    </svg>
                        <span>Produk & Jasa</span>
                    </a>
                </li>
                 <li>
                    <a href="{{ route('suppliers.index') }}" class="flex items-center px-6 py-2 transition-all duration-200 hover:bg-gray-800 hover:text-white {{ request()->routeIs('suppliers.*') ? 'text-white bg-gray-800 border-l-4 border-primary-500' : 'text-gray-400' }}">
                         <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3 text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.125-.504 1.125-1.125V14.25m-2.25 4.5v3.375c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V18.75m-7.5-6.75h3.86a2.25 2.25 0 012.012 1.244l1.5 3a2.25 2.25 0 01-.437 2.486l-1.664 1.71a.75.75 0 01-.197.12l-.968.322a3.003 3.003 0 01-3.69-3.69l.322-.968a.75.75 0 01.12-.197l1.71-1.664a2.25 2.25 0 012.486-.437l3-1.5a2.25 2.25 0 011.244-2.012l3.86-1.287A1.125 1.125 0 0121 4.875v7.875M3 14.25h12.75" />
                    </svg>
                        <span>Suppliers</span>
                    </a>
                </li>
            @endif

            <!-- Keuangan Pusat (Shared?) -->
            <!-- Maybe make it available to both, but filtered by division inside controller as already implemented?
                 Index method in FinanceController handles filtering. 
                 Let's show it for both.
            -->
            <!-- Keuangan Pusat -->
            <!-- TRANSAKSI Section -->
            <li class="px-6 py-2 mt-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                Transaksi
            </li>
            <li>
                <a href="{{ route('finance.index') }}#filterSection" class="flex items-center px-6 py-2 transition-all duration-200 hover:bg-gray-800 hover:text-white {{ request()->routeIs('finance.*') ? 'text-white bg-gray-800 border-l-4 border-primary-500' : 'text-gray-400' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3 text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Pemasukan Manual</span>
                </a>
            </li>
            <li>
                <a href="{{ route('company-debts.index') }}" class="flex items-center px-6 py-2 transition-all duration-200 hover:bg-gray-800 hover:text-white {{ request()->routeIs('company-debts.*') ? 'text-white bg-gray-800 border-l-4 border-primary-500' : 'text-gray-400' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3 text-red-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.125-.504 1.125-1.125V14.25m-2.25 4.5v3.375c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V18.75m-7.5-6.75h3.86a2.25 2.25 0 012.012 1.244l1.5 3a2.25 2.25 0 01-.437 2.486l-1.664 1.71a.75.75 0 01-.197.12l-.968.322a3.003 3.003 0 01-3.69-3.69l.322-.968a.75.75 0 01.12-.197l1.71-1.664a2.25 2.25 0 012.486-.437l3-1.5a2.25 2.25 0 011.244-2.012l3.86-1.287A1.125 1.125 0 0121 4.875v7.875M3 14.25h12.75" />
                    </svg>
                    <span>Pembayaran Cicilan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('company-receivables.index') }}" class="flex items-center px-6 py-2 transition-all duration-200 hover:bg-gray-800 hover:text-white {{ request()->routeIs('company-receivables.*') ? 'text-white bg-gray-800 border-l-4 border-primary-500' : 'text-gray-400' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3 text-blue-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Tagihan Perusahaan</span>
                </a>
            </li>

            <!-- Laporan Section -->
            <li class="px-6 py-2 mt-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                Laporan & Keuangan
            </li>
            <li>
                <a href="{{ route('finance.index') }}" class="flex items-center px-6 py-2 transition-all duration-200 hover:bg-gray-800 hover:text-white {{ request()->routeIs('finance.index') && !request()->has('hash') ? 'text-white bg-gray-800 border-l-4 border-primary-500' : 'text-gray-400' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3 text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V19.875c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                    </svg>
                    <span>Ringkasan Keuangan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('kasbons.index') }}" class="flex items-center px-6 py-2 transition-all duration-200 hover:bg-gray-800 hover:text-white {{ request()->routeIs('kasbons.*') ? 'text-white bg-gray-800 border-l-4 border-primary-500' : 'text-gray-400' }}">
                    <svg xmlns="http://www.w3.org/2000/xl" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-3 text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 8.25H9m6 3H9m3 6l-3-3h1.5a3 3 0 116 0H15l-3 3z" />
                    </svg>
                    <span>Kasbon Karyawan</span>
                </a>
            </li>
        </ul>
        </ul>
    </nav>

    <!-- Switch Division Button -->
    <div class="px-6 py-2">
        <form method="POST" action="{{ route('division.switch') }}">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white bg-gray-700 rounded-lg hover:bg-gray-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                </svg>
                Ganti Divisi
            </button>
        </form>
    </div>

    <!-- User Profile (Bottom) -->
    <div class="p-4 border-t border-gray-800">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center text-sm font-bold">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div class="text-sm">
                <p class="font-semibold">{{ Auth::user()->name }}</p>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-xs text-red-400 hover:text-red-300">Log Out</button>
                </form>
            </div>
        </div>
    </div>
</aside>
