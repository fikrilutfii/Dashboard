<x-app-layout>
    <x-slot name="header">
        Dashboard
    </x-slot>

    <div class="space-y-8 page-enter" x-data="{ showPaymentModal: false }">
        <!-- Welcome / Status Banner (Glassmorphic) -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 glass-card p-7 rounded-[26px] relative overflow-hidden">
            <!-- Decorative Subtle Glow -->
            <div class="absolute right-0 top-0 w-44 h-44 bg-teal-400/10 rounded-full -mr-16 -mt-16 blur-3xl pointer-events-none"></div>
            
            <div class="relative z-10">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-xs font-bold text-teal-800/80 uppercase tracking-wider">Sistem ERP Aktif</span>
                </div>
                <h3 class="text-2xl font-black text-slate-800 tracking-tight mt-1">Selamat Datang, {{ Auth::user()->name }}</h3>
                <p class="text-slate-500 text-xs sm:text-sm mt-1.5 flex items-center">
                    Divisi aktif: 
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold bg-teal-50 text-teal-800 border border-teal-200/80 uppercase tracking-wider ml-2 shadow-2xs">
                        {{ $division }}
                    </span>
                </p>
            </div>
            <div class="text-left sm:text-right relative z-10">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em]">Tanggal Hari Ini</p>
                <p class="text-xl font-black text-teal-900 mt-0.5 font-mono">{{ now()->format('d F Y') }}</p>
            </div>
        </div>

        @if(!in_array(Auth::user()->role, ['limited_invoice', 'admin3']))
        <!-- Statistics & Featured Grid (CoachPro Layout Style) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <x-summary-card 
                title="PEMBAYARAN PERCETAKAN BULAN INI" 
                value="Rp {{ number_format($pembayaranPercetakan, 0, ',', '.') }}" 
                color="sky"
                actionClick="showPaymentModal = true"
                actionLabel="Lihat Jadwal & Data ({{ $pembayaranPercetakanItems->count() }})"
                icon='<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
            />

            <x-summary-card 
                title="TAGIHAN PERCETAKAN BULAN INI" 
                value="Rp {{ number_format($totalTagihan, 0, ',', '.') }}" 
                color="rose"
                href="{{ route('company-receivables.index') }}"
                icon='<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>'
            />

            <x-summary-card 
                title="TOTAL TAGIHAN PERCETAKAN" 
                value="Rp {{ number_format($tagihanPercetakan, 0, ',', '.') }}" 
                color="amber"
                href="{{ route('company-receivables.index') }}"
                icon='<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>'
            />

            <x-summary-card 
                title="TOTAL PEMBAYARAN PERCETAKAN" 
                value="Rp {{ number_format($totalPembayaran, 0, ',', '.') }}" 
                color="indigo"
                href="{{ route('company-debts.index') }}"
                icon='<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
            />

            <x-summary-card 
                title="KEUNTUNGAN PERCETAKAN (BULAN INI)" 
                value="Rp {{ number_format($keuntunganPercetakan, 0, ',', '.') }}" 
                color="emerald"
                href="{{ route('finance.index') }}"
                icon='<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" /></svg>'
            />

            <x-summary-card 
                title="KEUNTUNGAN KONVEKSI (BULAN INI)" 
                value="Rp {{ number_format($keuntunganKonveksiBulanIni, 0, ',', '.') }}" 
                color="teal"
                href="{{ route('finance.index') }}"
                icon='<svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" /></svg>'
            />

            <!-- Featured Dark Teal Glass Banner (Inspired by CoachPro Card) -->
            <div class="col-span-1 md:col-span-2 bg-gradient-to-br from-teal-800 via-teal-900 to-emerald-950 text-white rounded-[26px] p-7 shadow-xl shadow-teal-950/20 relative overflow-hidden flex flex-col justify-between">
                <div class="absolute -right-8 -bottom-8 w-44 h-44 bg-teal-400/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-[10px] font-black uppercase tracking-[0.2em] text-teal-200">
                        PENGINGAT OPERASIONAL
                    </div>
                    <h4 class="text-xl font-extrabold text-white tracking-tight mt-3 leading-snug">
                        Laporan Tagihan & Pelunasan Cepat Klien
                    </h4>
                    <p class="text-xs text-teal-100/80 mt-1.5 max-w-md leading-relaxed">
                        Pantau seluruh invoice yang belum lunas per periode dan gunakan fitur pelunasan langsung tanpa repot klik satu per satu.
                    </p>
                </div>
                <div class="relative z-10 mt-6 flex flex-wrap items-center gap-3">
                    <a href="{{ route('reports.billing') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white hover:bg-teal-50 text-teal-950 font-bold rounded-2xl text-xs transition-all shadow-md active:scale-95">
                        Buka Laporan Tagihan
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                    </a>
                    <a href="{{ route('finance.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white font-bold rounded-2xl text-xs backdrop-blur-md border border-white/20 transition-all active:scale-95">
                        Laporan Keuangan
                    </a>
                </div>
            </div>
        </div>
        @elseif(Auth::user()->role === 'admin3')
        <div class="glass-card p-8 rounded-[26px] relative overflow-hidden">
            <div class="grid md:grid-cols-[1.2fr_0.8fr] gap-6 items-center">
                <div>
                    <h3 class="text-2xl font-black text-slate-800">Akses Terbatas untuk Admin 3</h3>
                    <p class="text-slate-600 mt-3 leading-relaxed text-sm">
                        Anda dapat mengakses faktur penjualan, laporan tagihan klien, dashboard stok, dan data master produk, supplier, serta customer.
                        Data ringkasan tagihan dan pembayaran perusahaan sengaja disembunyikan demi menjaga informasi keuangan yang sensitif.
                    </p>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center justify-center px-4 py-2 rounded-full bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold uppercase tracking-[0.18em]">
                        Akses Admin 3
                    </span>
                </div>
            </div>
        </div>
        @else
        <!-- Mode Akses Terbatas (Faktur) -->
        <div class="glass-card p-8 rounded-[26px] relative overflow-hidden flex flex-col md:flex-row items-center gap-6">
            <div class="relative w-20 h-20 flex items-center justify-center bg-teal-50 rounded-2xl shrink-0">
                <svg class="w-10 h-10 text-teal-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                </svg>
            </div>
            <div class="relative z-10">
                <h4 class="text-xl font-bold text-slate-800">Akses Terbatas (Faktur)</h4>
                <p class="text-slate-600 text-sm mt-2 leading-relaxed">
                    Anda masuk dengan peran <strong>Akses Faktur</strong>. Halaman ini dibatasi untuk tidak menampilkan laporan ringkasan keuangan demi keamanan data finansial perusahaan. Namun, Anda tetap dapat mengelola data transaksi, katalog produk, dan data pelanggan menggunakan menu navigasi di samping atau tombol <strong>Aksi Cepat</strong> di bawah ini.
                </p>
            </div>
        </div>
        @endif

        <!-- Action Section (Glass Card) -->
        <div class="glass-card rounded-[28px] p-7 shadow-xs">
            <h3 class="text-lg font-black text-slate-800 mb-6 flex items-center gap-3">
                <span class="w-9 h-9 bg-teal-100/90 text-teal-800 rounded-2xl flex items-center justify-center shadow-2xs">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
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
                        color="teal"
                        icon='<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>'
                    />
                    
                    <x-quick-action 
                        title="Belanja Bahan" 
                        desc="Kelola pembelian bahan"
                        url="{{ route('purchases.index', ['division' => 'percetakan']) }}" 
                        color="violet"
                        icon='<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" /></svg>'
                    />

                    <x-quick-action 
                        title="Produk Baru" 
                        desc="Tambah katalog produk"
                        url="{{ route('products.create') }}" 
                        color="amber"
                        icon='<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" /></svg>'
                    />

                    <x-quick-action 
                        title="Data Customer" 
                        desc="Kelola pelanggan"
                        url="{{ route('customers.index') }}" 
                        color="emerald"
                        icon='<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>'
                    />
                </div>
            @elseif($division == 'konfeksi')
                 <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    <x-quick-action 
                        title="Jual Barang" 
                        desc="Data penjualan"
                        url="{{ route('invoices.index', ['division' => 'konfeksi']) }}" 
                        color="teal"
                        icon='<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>'
                    />

                    <x-quick-action 
                        title="Bayar Gaji" 
                        desc="Payroll karyawan"
                        url="{{ route('payrolls.index') }}" 
                        color="emerald"
                        icon='<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg>'
                    />

                    <x-quick-action 
                        title="Data Kasbon" 
                        desc="Kelola kasbon karyawan"
                        url="{{ route('kasbons.index') }}" 
                        color="amber"
                        icon='<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
                    />

                    <x-quick-action 
                        title="Belanja Bahan" 
                        desc="Data belanja kain/benang"
                        url="{{ route('purchases.index', ['division' => 'konfeksi']) }}" 
                        color="indigo"
                        icon='<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>'
                    />
                </div>
            @endif
        </div>

        <!-- Modal Jadwal & Urutan Pembayaran Bulan Ini (Frosted Glassmorphic) -->
        <div x-show="showPaymentModal" 
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto" 
             aria-labelledby="modal-title" 
             role="dialog" 
             aria-modal="true">
            <div class="flex items-center justify-center min-h-screen p-4 text-center sm:p-6">
                <!-- Background overlay with soft backdrop blur -->
                <div x-show="showPaymentModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" 
                     @click="showPaymentModal = false"></div>

                <!-- Modal Card Container -->
                <div x-show="showPaymentModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative glass-card rounded-[28px] text-left overflow-hidden shadow-2xl max-w-4xl w-full border border-white/90 z-10 flex flex-col max-h-[90vh]">
                    
                    <!-- Modal Header -->
                    <div class="p-6 bg-white/70 border-b border-slate-100/80 flex items-center justify-between flex-shrink-0">
                        <div class="flex items-center gap-3.5">
                            <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-teal-600 to-emerald-800 text-white flex items-center justify-center shadow-md shadow-teal-700/20">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                            </div>
                            <div>
                                <div class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-teal-50 border border-teal-200/70 text-teal-800 text-[10px] font-extrabold uppercase tracking-wider mb-1">
                                    Jadwal Pembayaran Percetakan
                                </div>
                                <h3 class="text-lg font-black text-slate-800 tracking-tight leading-none">
                                    Rincian Pembayaran Bulan Ini ({{ Carbon\Carbon::now()->isoFormat('MMMM Y') }})
                                </h3>
                                <p class="text-xs text-slate-400 mt-1">Urutan kronologis tanggal dan daftar kewajiban yang harus dibayarkan.</p>
                            </div>
                        </div>
                        <button @click="showPaymentModal = false" class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100/70 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Total Banner -->
                    <div class="px-6 py-4 bg-gradient-to-r from-teal-50/80 to-emerald-50/80 border-b border-teal-100/70 flex flex-col sm:flex-row sm:items-center justify-between gap-3 flex-shrink-0">
                        <div>
                            <span class="text-[10px] font-bold text-teal-800/80 uppercase tracking-widest block">Total Kewajiban Pembayaran Bulan Ini</span>
                            <span class="text-2xl font-black text-teal-900 tracking-tight">Rp {{ number_format($pembayaranPercetakan, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1.5 rounded-xl bg-white/95 border border-teal-200/70 text-xs font-black text-teal-800 shadow-2xs">
                                {{ $pembayaranPercetakanItems->count() }} Data Kewajiban
                            </span>
                        </div>
                    </div>

                    <!-- Sequence List / Table -->
                    <div class="p-6 overflow-y-auto flex-1">
                        @if($pembayaranPercetakanItems->count() > 0)
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-slate-50/70 border-b border-slate-200/70 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                                            <th class="px-4 py-3 text-center">Urutan Tanggal</th>
                                            <th class="px-4 py-3">Nama Kewajiban & Keperluan</th>
                                            <th class="px-4 py-3 text-center">Tipe Bayar</th>
                                            <th class="px-4 py-3 text-right">Harus Dibayar (Bulan Ini)</th>
                                            <th class="px-4 py-3 text-right">Sisa Total Hutang</th>
                                            <th class="px-4 py-3 text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100/90 text-xs">
                                        @foreach($pembayaranPercetakanItems as $item)
                                            <tr class="hover:bg-teal-50/20 transition-colors {{ $item['is_overdue'] ? 'bg-rose-50/20' : ($item['is_today'] ? 'bg-amber-50/20' : '') }}">
                                                <!-- Urutan Tanggal -->
                                                <td class="px-4 py-3.5 whitespace-nowrap text-center">
                                                    <div class="inline-flex flex-col items-center justify-center p-2 rounded-xl min-w-[56px]
                                                        @if($item['is_overdue']) bg-rose-50 text-rose-700 border border-rose-200/80
                                                        @elseif($item['is_today']) bg-amber-50 text-amber-800 border border-amber-200/80
                                                        @elseif($item['days_diff'] <= 7) bg-amber-50/60 text-amber-700 border border-amber-100
                                                        @else bg-teal-50 text-teal-800 border border-teal-200/60
                                                        @endif">
                                                        <span class="text-base font-black leading-none">{{ $item['day_formatted'] }}</span>
                                                        <span class="text-[9px] font-bold uppercase tracking-wider mt-0.5">{{ $item['month_formatted'] }}</span>
                                                    </div>
                                                    <div class="mt-1">
                                                        @if($item['is_overdue'])
                                                            <span class="text-[9px] font-extrabold text-rose-600 block">Lewat Tempo</span>
                                                        @elseif($item['is_today'])
                                                            <span class="text-[9px] font-extrabold text-amber-600 block">Hari Ini</span>
                                                        @elseif($item['days_diff'] <= 7)
                                                            <span class="text-[9px] font-bold text-amber-600 block">H-{{ $item['days_diff'] }} hr</span>
                                                        @endif
                                                    </div>
                                                </td>

                                                <!-- Nama & Keterangan -->
                                                <td class="px-4 py-3.5">
                                                    <div class="font-extrabold text-slate-800 text-xs leading-tight">{{ $item['name'] }}</div>
                                                    <div class="text-[11px] text-slate-400 mt-0.5">{{ $item['description'] }}</div>
                                                    @if($item['entity'])
                                                        <span class="inline-block mt-1 text-[9px] font-bold px-2 py-0.5 bg-slate-100 text-slate-600 rounded">
                                                            {{ $item['entity'] }}
                                                        </span>
                                                    @endif
                                                </td>

                                                <!-- Tipe Bayar -->
                                                <td class="px-4 py-3.5 whitespace-nowrap text-center">
                                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-black border
                                                        {{ str_contains($item['type_label'], 'Angsuran') ? 'bg-blue-50 text-blue-700 border-blue-200/70' : 'bg-purple-50 text-purple-700 border-purple-200/70' }}">
                                                        {{ $item['type_label'] }}
                                                    </span>
                                                </td>

                                                <!-- Harus Dibayar Bulan Ini -->
                                                <td class="px-4 py-3.5 whitespace-nowrap text-right">
                                                    <span class="font-black text-slate-900 text-sm leading-none block">
                                                        Rp {{ number_format($item['due_amount'], 0, ',', '.') }}
                                                    </span>
                                                    <span class="text-[10px] font-semibold text-teal-700">Kewajiban bulan ini</span>
                                                </td>

                                                <!-- Sisa Total Hutang -->
                                                <td class="px-4 py-3.5 whitespace-nowrap text-right font-bold text-slate-500">
                                                    Rp {{ number_format($item['remaining_amount'], 0, ',', '.') }}
                                                </td>

                                                <!-- Aksi Cepat -->
                                                <td class="px-4 py-3.5 whitespace-nowrap text-center">
                                                    <a href="{{ route('company-debts.index') }}" 
                                                       class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-teal-50 hover:bg-teal-100/80 text-teal-800 border border-teal-200/70 text-xs font-extrabold transition-all shadow-2xs active:scale-95">
                                                        <span>Bayar</span>
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                                        </svg>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="py-12 text-center text-slate-400">
                                <div class="w-12 h-12 mx-auto rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center mb-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h4 class="text-sm font-bold text-slate-700">Tidak Ada Tagihan Jatuh Tempo Bulan Ini</h4>
                                <p class="text-xs text-slate-400 mt-1">Seluruh kewajiban pembayaran percetakan telah lunas atau belum jatuh tempo.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Modal Footer -->
                    <div class="p-4 bg-white/70 border-t border-slate-100/80 flex flex-col sm:flex-row items-center justify-between gap-3 flex-shrink-0">
                        <a href="{{ route('company-debts.index') }}" class="text-xs font-bold text-teal-800 hover:text-teal-950 flex items-center gap-1.5 hover:underline">
                            <span>Buka Halaman Seluruh Pembayaran Perusahaan</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                        <button @click="showPaymentModal = false" class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-extrabold transition-all shadow-2xs">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
