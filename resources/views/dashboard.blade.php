<x-app-layout>
    <x-slot name="header">
        Dashboard
    </x-slot>

    <div class="space-y-8 page-enter">
        <!-- Welcome / Status -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 bg-white/80 backdrop-blur-xl p-8 rounded-[2rem] border border-white/60 shadow-premium relative overflow-hidden">
            <!-- Decorative Accent -->
            <div class="absolute right-0 top-0 w-32 h-32 bg-primary-500/5 rounded-full -mr-16 -mt-16 blur-3xl"></div>
            
            <div class="relative z-10">
                <h3 class="text-2xl font-black text-zinc-900 tracking-tight">Selamat Datang, {{ Auth::user()->name }} 👋</h3>
                <p class="text-zinc-500 text-sm mt-2 flex items-center">
                    Anda sedang mengelola divisi: 
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black bg-primary-50 text-primary-600 border border-primary-100 uppercase tracking-widest ml-2">
                        {{ $division }}
                    </span>
                </p>
            </div>
            <div class="text-right hidden sm:block relative z-10">
                <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-[0.2em]">Tanggal Hari Ini</p>
                <p class="text-xl font-black text-primary-600 mt-1">{{ now()->format('d F Y') }}</p>
            </div>
        </div>

        @if(!in_array(Auth::user()->role, ['limited_invoice', 'admin3']))
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <x-summary-card 
                title="PEMBAYARAN PERCETAKAN BULAN INI" 
                value="Rp {{ number_format($pembayaranPercetakan, 0, ',', '.') }}" 
                color="sky"
                href="{{ route('company-receivables.index') }}"
                icon='<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
            />

            <x-summary-card 
                title="TAGIHAN PERCETAKAN BULAN INI" 
                value="Rp {{ number_format($totalTagihan, 0, ',', '.') }}" 
                color="rose"
                href="{{ route('invoices.index', ['division' => 'percetakan']) }}"
                icon='<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>'
            />

            <x-summary-card 
                title="TOTAL TAGIHAN PERCETAKAN" 
                value="Rp {{ number_format($tagihanPercetakan, 0, ',', '.') }}" 
                color="amber"
                href="{{ route('reports.billing') }}"
                icon='<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>'
            />

            <x-summary-card 
                title="TOTAL PEMBAYARAN PERCETAKAN" 
                value="Rp {{ number_format($totalPembayaran, 0, ',', '.') }}" 
                color="indigo"
                href="{{ route('finance.index') }}"
                icon='<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
            />

            <x-summary-card 
                title="KEUNTUNGAN PERCETAKAN (BULAN INI)" 
                value="Rp {{ number_format($keuntunganPercetakan, 0, ',', '.') }}" 
                color="emerald"
                href="{{ route('finance.index') }}"
                icon='<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" /></svg>'
            />

            <x-summary-card 
                title="KEUNTUNGAN KONVEKSI (MINGGU INI)" 
                value="Rp {{ number_format($keuntunganKonveksiMingguIni, 0, ',', '.') }}" 
                color="teal"
                href="{{ route('finance.index') }}"
                icon='<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" /></svg>'
            />

            <x-summary-card 
                title="KEUNTUNGAN KONVEKSI (BULAN INI)" 
                value="Rp {{ number_format($keuntunganKonveksiBulanIni, 0, ',', '.') }}" 
                color="emerald"
                href="{{ route('finance.index') }}"
                icon='<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" /></svg>'
            />
        </div>
        @elseif(Auth::user()->role === 'admin3')
        <div class="bg-white/80 backdrop-blur-xl p-8 rounded-[2rem] border border-white/60 shadow-premium relative overflow-hidden">
            <div class="grid md:grid-cols-[1.2fr_0.8fr] gap-6 items-center">
                <div>
                    <h3 class="text-2xl font-black text-zinc-900">Akses Terbatas untuk Admin 3</h3>
                    <p class="text-zinc-600 mt-3 leading-relaxed">
                        Anda dapat mengakses faktur penjualan, laporan tagihan klien, dashboard stok, dan data master produk, supplier, serta customer.
                        Data ringkasan tagihan dan pembayaran perusahaan sengaja disembunyikan demi menjaga informasi keuangan yang sensitif.
                    </p>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center justify-center px-4 py-2 rounded-full bg-amber-50 text-amber-700 border border-amber-100 text-sm font-bold uppercase tracking-[0.18em]">
                        Akses Admin 3
                    </span>
                </div>
            </div>
        </div>
        @else
        <!-- Mode Akses Terbatas (Faktur) -->
        <div class="bg-white/80 backdrop-blur-xl p-8 rounded-[2rem] border border-white/60 shadow-premium relative overflow-hidden flex flex-col md:flex-row items-center gap-6">
            <!-- Decorative Accent -->
            <div class="absolute right-0 top-0 w-32 h-32 bg-primary-500/5 rounded-full -mr-16 -mt-16 blur-3xl"></div>
            
            <div class="relative w-20 h-20 flex items-center justify-center bg-primary-50 rounded-2xl shrink-0">
                <svg class="w-10 h-10 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                </svg>
                <div class="absolute inset-0 bg-primary-400/20 blur-xl rounded-full scale-75 -z-10"></div>
            </div>
            <div class="relative z-10">
                <h4 class="text-xl font-bold text-zinc-800">Akses Terbatas (Faktur)</h4>
                <p class="text-zinc-600 text-sm mt-2 leading-relaxed">
                    Anda masuk dengan peran <strong>Akses Faktur</strong>. Halaman ini dibatasi untuk tidak menampilkan laporan ringkasan keuangan demi keamanan data finansial perusahaan. Namun, Anda tetap dapat mengelola data transaksi, katalog produk, dan data pelanggan menggunakan menu navigasi di samping atau tombol <strong>Aksi Cepat</strong> di bawah ini.
                </p>
            </div>
        </div>
        @endif

        <!-- Action Section -->
        <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] shadow-premium border border-white/60 p-8">
            <h3 class="text-xl font-black text-zinc-900 mb-8 flex items-center gap-3">
                <span class="p-2.5 premium-gradient text-white rounded-2xl shadow-lg shadow-primary-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                    </svg>
                </span>
                Aksi Cepat
            </h3>

            @if($division == 'percetakan')
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    <x-quick-action 
                        title="Faktur Penjualan" 
                        desc="Kelola data faktur"
                        url="{{ route('invoices.index', ['division' => 'percetakan']) }}" 
                        color="indigo"
                        icon='<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>'
                    />
                    
                    <x-quick-action 
                        title="Belanja Bahan" 
                        desc="Kelola pembelian bahan"
                        url="{{ route('purchases.index', ['division' => 'percetakan']) }}" 
                        color="violet"
                        icon='<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" /></svg>'
                    />

                    <x-quick-action 
                        title="Produk Baru" 
                        desc="Tambah katalog produk"
                        url="{{ route('products.create') }}" 
                        color="amber"
                        icon='<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg>'
                    />

                    <x-quick-action 
                        title="Data Customer" 
                        desc="Kelola pelanggan"
                        url="{{ route('customers.index') }}" 
                        color="emerald"
                        icon='<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>'
                    />
                </div>
            @elseif($division == 'konfeksi')
                 <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    <x-quick-action 
                        title="Jual Barang" 
                        desc="Data penjualan"
                        url="{{ route('invoices.index', ['division' => 'konfeksi']) }}" 
                        color="indigo"
                        icon='<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>'
                    />

                    <x-quick-action 
                        title="Bayar Gaji" 
                        desc="Payroll karyawan"
                        url="{{ route('payrolls.index') }}" 
                        color="emerald"
                        icon='<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg>'
                    />

                    <x-quick-action 
                        title="Data Kasbon" 
                        desc="Kelola kasbon karyawan"
                        url="{{ route('kasbons.index') }}" 
                        color="orange"
                        icon='<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
                    />

                    <x-quick-action 
                        title="Belanja Bahan" 
                        desc="Data belanja kain/benang"
                        url="{{ route('purchases.index', ['division' => 'konfeksi']) }}" 
                        color="zinc"
                        icon='<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>'
                    />
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
