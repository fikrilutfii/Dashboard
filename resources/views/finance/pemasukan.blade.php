<x-app-layout>
    <x-slot name="header">Pemasukan Manual</x-slot>

    <div class="space-y-6">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl p-6 border border-zinc-100 shadow-sm col-span-1">
                <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wider italic">Total Pemasukan (Periode)</p>
                <h3 class="text-2xl font-bold text-emerald-600 mt-2">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h3>
            </div>
            <div class="md:col-span-2"></div>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-2xl border border-zinc-100 shadow-sm overflow-hidden">
            <div class="p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-zinc-100">
                <h3 class="text-lg font-bold text-zinc-800">Riwayat Pemasukan Manual</h3>
                <button onclick="document.getElementById('incomeModal').classList.remove('hidden')" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-xl hover:bg-emerald-700 transition-colors shadow-md active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Catat Pemasukan Baru
                </button>
            </div>

            @if(session('success'))
                <div class="mx-6 mt-4 p-3 bg-emerald-50 text-emerald-700 rounded-xl text-sm border border-emerald-100 italic">{{ session('success') }}</div>
            @endif

            <!-- Filter -->
            <div class="p-4 border-b border-zinc-100 bg-zinc-50/50">
                <form method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                    <div>
                        <label class="block text-[10px] font-bold text-zinc-400 uppercase mb-1 italic">Divisi</label>
                        <select name="division" class="w-full text-sm border border-zinc-200 rounded-xl px-3 py-2 bg-white">
                            <option value="">Semua Divisi</option>
                            <option value="percetakan" {{ request('division') == 'percetakan' ? 'selected' : '' }}>Percetakan</option>
                            <option value="konfeksi" {{ request('division') == 'konfeksi' ? 'selected' : '' }}>Konveksi</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-zinc-400 uppercase mb-1 italic">Mulai</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full text-sm border border-zinc-200 rounded-xl px-3 py-2 bg-white">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-zinc-400 uppercase mb-1 italic">Selesai</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full text-sm border border-zinc-200 rounded-xl px-3 py-2 bg-white">
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 py-2 bg-zinc-800 text-white text-sm font-bold rounded-xl hover:bg-zinc-700 transition-all">Saring</button>
                        <a href="{{ route('finance.pemasukan') }}" class="px-4 py-2 text-zinc-500 text-sm font-bold rounded-xl hover:bg-zinc-100 transition-all">Reset</a>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-zinc-50 border-b border-zinc-100">
                        <tr>
                            <th class="text-left px-6 py-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Tanggal</th>
                            <th class="text-left px-6 py-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Keterangan</th>
                            <th class="text-left px-6 py-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Entitas</th>
                            <th class="text-right px-6 py-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @forelse($transaksi as $t)
                        <tr class="hover:bg-zinc-50 transition-colors">
                            <td class="px-6 py-4 text-zinc-500 font-medium">
                                {{ $t->date->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-bold text-zinc-800">{{ $t->description }}</p>
                                <p class="text-[10px] text-zinc-400 uppercase font-black tracking-tight mt-0.5">{{ $t->division }} - {{ $t->category }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase border
                                    {{ $t->entity == 'pribadi' ? 'bg-rose-50 text-rose-600 border-rose-100' : 'bg-indigo-50 text-indigo-600 border-indigo-100' }}">
                                    {{ $t->entity ?? $t->division }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-black text-emerald-600 text-base">
                                Rp {{ number_format($t->amount, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-zinc-400 italic">
                                Belum ada data pemasukan manual dalam periode ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-zinc-100">{{ $transaksi->links() }}</div>
        </div>
    </div>

    @include('finance.partials.modals')
</x-app-layout>
