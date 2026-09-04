<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs text-zinc-400 font-bold uppercase tracking-wider">
                    <a href="{{ route('customers.index') }}" class="hover:text-zinc-700 transition-colors">Master Customer</a>
                    <span>/</span>
                    <span class="text-zinc-600">Profil Pelanggan</span>
                </div>
                <h2 class="font-extrabold text-2xl text-zinc-800 tracking-tight leading-none mt-1.5">
                    {{ $customer->name }}
                </h2>
            </div>
            
            <div class="flex items-center gap-2">
                <a href="{{ route('customers.index') }}" class="px-4 py-2 border border-zinc-200 text-zinc-600 text-sm font-semibold rounded-xl hover:bg-zinc-50 hover:text-zinc-800 transition-all shadow-sm">
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto page-enter">
        <div class="bg-white rounded-3xl border border-zinc-100 shadow-premium overflow-hidden">
            <!-- Header Banner -->
            <div class="premium-gradient h-32 px-8 flex items-end pb-4 text-white">
                <div>
                    <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-white/20 text-white border border-white/10 uppercase tracking-wider">
                        Customer Profil
                    </span>
                </div>
            </div>

            <!-- Profile Info -->
            <div class="p-8 space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <div>
                            <p class="text-[10px] text-zinc-400 uppercase tracking-widest font-bold">Nama Lengkap</p>
                            <p class="text-base font-extrabold text-zinc-800 mt-0.5">{{ $customer->name }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-zinc-400 uppercase tracking-widest font-bold">Nomor Telepon</p>
                            <p class="text-base font-bold text-zinc-800 mt-0.5">{{ $customer->phone ?? 'Tidak ada data' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-zinc-400 uppercase tracking-widest font-bold">Alamat Email</p>
                            <p class="text-base font-bold text-zinc-800 mt-0.5">{{ $customer->email ?? 'Tidak ada data' }}</p>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <p class="text-[10px] text-zinc-400 uppercase tracking-widest font-bold">Divisi Terdaftar</p>
                            <span class="inline-block mt-1 px-2.5 py-1 text-xs font-semibold rounded-lg {{ $customer->division === 'percetakan' ? 'bg-blue-50 text-blue-700 border border-blue-100' : 'bg-purple-50 text-purple-700 border border-purple-100' }}">
                                {{ ucfirst($customer->division ?? 'percetakan') }}
                            </span>
                        </div>
                        <div>
                            <p class="text-[10px] text-zinc-400 uppercase tracking-widest font-bold">Alamat Rumah / Kantor</p>
                            <p class="text-sm font-bold text-zinc-700 mt-1 whitespace-pre-line leading-relaxed">{{ $customer->address ?? 'Tidak ada data' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Action section linking to Billing Report -->
                <div class="border-t border-zinc-100 pt-8 flex flex-col sm:flex-row gap-4 items-center justify-between">
                    <div>
                        <h4 class="text-sm font-extrabold text-zinc-800">Laporan Keuangan & Piutang Klien</h4>
                        <p class="text-xs text-zinc-400 mt-0.5">Periksa seluruh tagihan yang belum dibayar oleh klien ini.</p>
                    </div>
                    
                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <a href="{{ route('reports.billing.show', $customer) }}" class="inline-flex items-center justify-center gap-2 w-full sm:w-auto px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-xl transition-all shadow-md active:scale-95 shadow-red-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9zM9 13h6M9 17h3" /></svg>
                            Lihat Laporan Tagihan Klien
                        </a>
                        <a href="{{ route('customers.edit', $customer) }}" class="inline-flex items-center justify-center gap-2 w-full sm:w-auto px-5 py-2.5 bg-zinc-100 hover:bg-zinc-200 text-zinc-700 text-sm font-bold rounded-xl transition-all active:scale-95">
                            Edit Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
