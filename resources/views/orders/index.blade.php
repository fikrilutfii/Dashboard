<x-app-layout>
    <x-slot name="header">Order Percetakan</x-slot>

    <div class="space-y-6 pb-12">
        <!-- Page Header & Action Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-50 border border-teal-100 text-teal-800 text-xs font-semibold mb-2">
                    <span class="w-2 h-2 rounded-full bg-teal-500"></span>
                    Antrian Pesanan Cetak
                </div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Order Cetak & Produksi</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Pantau status antrian order cetak dari pelanggan.</p>
            </div>
            <div class="flex items-center gap-2.5">
                <a href="{{ route('orders.create') }}" 
                   class="bg-gradient-to-r from-teal-700 to-emerald-800 hover:from-teal-800 hover:to-emerald-900 text-white font-extrabold text-xs px-4 py-2.5 rounded-xl shadow-sm transition-all flex items-center gap-1.5 active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Tambah Order Baru
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50/90 border border-emerald-200/90 text-emerald-800 text-xs font-bold flex items-center gap-2.5 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Table Card -->
        <div class="glass-card rounded-[26px] overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-200/70 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            <th class="px-6 py-4">ID Order</th>
                            <th class="px-6 py-4">Pelanggan</th>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">Keterangan</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100/90 text-xs">
                        @forelse ($orders as $order)
                            <tr class="hover:bg-teal-50/20 transition-colors">
                                <td class="px-6 py-4 font-mono font-bold text-slate-800 whitespace-nowrap">
                                    #{{ $order->id }}
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-800 whitespace-nowrap">
                                    {{ $order->customer->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-slate-500 whitespace-nowrap font-medium">
                                    {{ $order->order_date }}
                                </td>
                                <td class="px-6 py-4 text-slate-600">
                                    {{ Str::limit($order->description, 40) }}
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    @php
                                        $st = strtolower($order->status);
                                        $stClass = 'bg-amber-50 text-amber-800 border-amber-200/80';
                                        if ($st === 'completed' || $st === 'selesai') {
                                            $stClass = 'bg-emerald-50 text-emerald-800 border-emerald-200/80';
                                        } elseif ($st === 'cancelled' || $st === 'batal') {
                                            $stClass = 'bg-rose-50 text-rose-800 border-rose-200/80';
                                        }
                                    @endphp
                                    <span class="px-2.5 py-1 inline-flex text-xs font-black rounded-full border {{ $stClass }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('orders.edit', $order) }}" class="px-2.5 py-1 bg-teal-50 text-teal-800 hover:bg-teal-100/80 font-bold rounded-lg border border-teal-200/60 transition-colors">
                                            Edit
                                        </a>
                                        <form action="{{ route('orders.destroy', $order) }}" method="POST" class="action-confirm inline-block" data-title="Apakah Anda yakin?" data-text="Data order yang dihapus tidak dapat dikembalikan!" data-icon="warning" data-confirm-text="Ya, Hapus!" data-confirm-color="#ef4444">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2.5 py-1 bg-rose-50 text-rose-700 hover:bg-rose-100 font-bold rounded-lg border border-rose-200/60 transition-colors">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                    <div class="w-12 h-12 mx-auto rounded-2xl bg-teal-50 flex items-center justify-center text-teal-700 mb-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    </div>
                                    <p class="font-bold text-slate-700">Belum ada order cetak.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 sm:p-5 border-t border-slate-100/90 bg-white/40">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
