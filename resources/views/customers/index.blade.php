<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-[11px] font-black text-teal-800/60 uppercase tracking-[0.2em] block mb-1">Master Data</span>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">
                    Daftar Pelanggan (Customer)
                </h2>
            </div>
            <div>
                <a href="{{ route('customers.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-teal-700 hover:bg-teal-800 text-white text-xs font-bold shadow-sm shadow-teal-900/15 active:scale-95 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    + Tambah Pelanggan
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
                <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider">Data Pelanggan Terdaftar</h4>
                <span class="text-[11px] font-semibold text-slate-400">Total: {{ number_format($customers->total(), 0, ',', '.') }} pelanggan</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-200/60 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                            <th class="px-5 py-3.5">Nama Customer</th>
                            <th class="px-5 py-3.5">Email</th>
                            <th class="px-5 py-3.5">No. Telepon</th>
                            <th class="px-5 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                        @forelse ($customers as $customer)
                            <tr class="hover:bg-teal-50/20 transition-colors">
                                <td class="px-5 py-3.5 font-bold text-slate-800">
                                    <a href="{{ route('customers.show', $customer) }}" class="text-teal-800 hover:text-teal-950 hover:underline">
                                        {{ $customer->name }}
                                    </a>
                                </td>
                                <td class="px-5 py-3.5 text-slate-600">
                                    {{ $customer->email ?? '-' }}
                                </td>
                                <td class="px-5 py-3.5 font-mono text-slate-600">
                                    {{ $customer->phone ?? '-' }}
                                </td>
                                <td class="px-5 py-3.5 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center gap-2">
                                        <a href="{{ route('customers.edit', $customer) }}" class="text-teal-700 hover:text-teal-900 font-semibold hover:underline">
                                            Edit
                                        </a>
                                        @if(Auth::user()->isAdmin())
                                            <span class="text-slate-300">|</span>
                                            <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline-block delete-confirm">
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
                                        <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        Belum ada data pelanggan.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($customers->hasPages())
                <div class="px-6 py-4 bg-white/40 border-t border-slate-100">
                    {{ $customers->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
