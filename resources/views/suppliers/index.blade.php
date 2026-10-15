<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-[11px] font-black text-teal-800/60 uppercase tracking-[0.2em] block mb-1">Master Data</span>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">
                    Daftar Supplier
                </h2>
            </div>
            <div>
                <a href="{{ route('suppliers.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-teal-700 hover:bg-teal-800 text-white text-xs font-bold shadow-sm shadow-teal-900/15 active:scale-95 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Supplier
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 space-y-6 max-w-7xl mx-auto page-enter">

        @if(session('success'))
            <div class="glass-card rounded-[20px] p-4 border border-emerald-200/80 bg-emerald-50/70 text-emerald-800 text-xs font-bold flex items-center gap-3">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="glass-card rounded-[20px] p-4 border border-rose-200/80 bg-rose-50/70 text-rose-800 text-xs font-bold flex items-center gap-3">
                <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Table Card -->
        <div class="glass-card rounded-[26px] overflow-hidden border border-white/70 shadow-sm">
            <div class="bg-white/40 px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider">Data Mitra Supplier</h4>
                <span class="text-[11px] font-semibold text-slate-400">Total: {{ number_format($suppliers->total(), 0, ',', '.') }} supplier</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-200/60 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                            <th class="px-5 py-3.5">Nama Supplier</th>
                            <th class="px-5 py-3.5">No. Telepon</th>
                            <th class="px-5 py-3.5">Alamat</th>
                            <th class="px-5 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                        @forelse($suppliers as $supplier)
                            <tr class="hover:bg-teal-50/20 transition-colors">
                                <td class="px-5 py-3.5 font-bold text-slate-800">
                                    {{ $supplier->name }}
                                </td>
                                <td class="px-5 py-3.5 font-mono text-slate-600">
                                    {{ $supplier->phone ?? '-' }}
                                </td>
                                <td class="px-5 py-3.5 text-slate-600">
                                    {{ $supplier->address ?? '-' }}
                                </td>
                                <td class="px-5 py-3.5 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('suppliers.edit', $supplier) }}" class="text-teal-700 hover:text-teal-900 font-semibold hover:underline">
                                            Edit
                                        </a>
                                        @if(Auth::user()->isAdmin())
                                            <span class="text-slate-300">|</span>
                                            <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="inline-block delete-confirm">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-rose-600 hover:text-rose-800 font-semibold hover:underline">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-400 font-medium">
                                    <div class="max-w-xs mx-auto">
                                        <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        Belum ada data supplier.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($suppliers->hasPages())
                <div class="px-6 py-4 bg-white/40 border-t border-slate-100">
                    {{ $suppliers->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
