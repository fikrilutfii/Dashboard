<x-app-layout>
    <x-slot name="header">Pengeluaran & Belanja</x-slot>

    <div class="space-y-6">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl p-6 border border-zinc-100 shadow-sm">
                <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wider">Total Pengeluaran (Bulan Ini)</p>
                <h3 class="text-2xl font-bold text-red-500 mt-2">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</h3>
            </div>
            <!-- Empty card for spacing / future metrics -->
            <div class="bg-white rounded-2xl p-6 border border-zinc-100 shadow-sm flex items-center justify-center">
                <p class="text-sm text-zinc-400 font-medium italic">Gunakan filter untuk melihat periode spesifik.</p>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-2xl border border-zinc-100 shadow-sm overflow-hidden">
            <div class="p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-zinc-100">
                <h3 class="text-lg font-bold text-zinc-800">Daftar Pengeluaran</h3>
                <div class="flex gap-2">
                    <a href="{{ route('expenses.create', ['type' => 'manual']) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-zinc-800 text-white text-sm font-semibold rounded-xl hover:bg-zinc-700 transition-colors shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        Pengeluaran Manual
                    </a>
                    <a href="{{ route('expenses.create', ['type' => 'bahan']) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                        Belanja Bahan
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="mx-6 mt-4 p-3 bg-emerald-50 text-emerald-700 rounded-xl text-sm border border-emerald-100">{{ session('success') }}</div>
            @endif

            <!-- Filter -->
            <div class="p-4 border-b border-zinc-100 bg-zinc-50">
                <form method="GET" class="flex flex-col sm:flex-row gap-3">
                    <select name="type" class="text-sm border border-zinc-200 rounded-xl px-3 py-2 bg-white">
                        <option value="">Semua Jenis</option>
                        <option value="manual" {{ request('type') == 'manual' ? 'selected' : '' }}>Operasional (Manual)</option>
                        <option value="bahan" {{ request('type') == 'bahan' ? 'selected' : '' }}>Belanja Bahan</option>
                    </select>
                    <input type="text" name="category" value="{{ request('category') }}" placeholder="Cari Ket / Kategori" class="text-sm border border-zinc-200 rounded-xl px-3 py-2 bg-white flex-1">
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="text-sm border border-zinc-200 rounded-xl px-3 py-2 bg-white">
                    <span class="text-zinc-400 self-center">-</span>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" class="text-sm border border-zinc-200 rounded-xl px-3 py-2 bg-white">
                    <button type="submit" class="px-4 py-2 bg-zinc-800 text-white text-sm rounded-xl hover:bg-zinc-700 font-semibold shadow-sm">Terapkan</button>
                    <a href="{{ route('expenses.index') }}" class="px-4 py-2 text-zinc-500 hover:bg-zinc-200 rounded-xl text-sm font-semibold transition-colors">Reset</a>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-zinc-50 border-b border-zinc-100">
                        <tr>
                            <th class="text-left px-6 py-4 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Tanggal</th>
                            <th class="text-left px-6 py-4 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Jenis & Detail</th>
                            <th class="text-left px-6 py-4 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Kategori/Entitas</th>
                            <th class="text-right px-6 py-4 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Nominal</th>
                            <th class="text-center px-6 py-4 text-xs font-semibold text-zinc-500 uppercase tracking-wider w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @forelse($expenses as $expense)
                        <tr class="hover:bg-zinc-50 transition-colors">
                            <td class="px-6 py-4 text-zinc-600 font-medium whitespace-nowrap">
                                {{ $expense->date->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($expense->type === 'bahan')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-bold bg-indigo-50 text-indigo-700 uppercase tracking-widest mb-1.5 border border-indigo-100/50">Belanja Bahan</span>
                                    <p class="font-bold text-zinc-800">{{ $expense->item_name }}</p>
                                    <p class="text-xs text-zinc-500 mt-0.5">Suplier: <span class="text-zinc-700 font-medium">{{ $expense->supplier_name }}</span></p>
                                    @if($expense->description)
                                        <p class="text-xs text-zinc-400 mt-1 line-clamp-1" title="{{ $expense->description }}">{{ $expense->description }}</p>
                                    @endif
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10px] font-bold bg-zinc-100 text-zinc-600 uppercase tracking-widest mb-1.5 border border-zinc-200/50">Operasional</span>
                                    <p class="font-bold text-zinc-800">{{ $expense->category }}</p>
                                    <p class="text-xs text-zinc-500 mt-0.5 line-clamp-1" title="{{ $expense->description }}">{{ $expense->description }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-zinc-100 text-zinc-600 rounded text-xs font-medium">{{ $expense->entity ?? $expense->division }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <p class="font-bold text-red-600">Rp {{ number_format($expense->amount, 0, ',', '.') }}</p>
                                @if($expense->type === 'bahan' && $expense->quantity > 1)
                                    <p class="text-[10px] text-zinc-400 mt-0.5">{{ $expense->quantity }}x Rp {{ number_format($expense->unit_price, 0, ',', '.') }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('expenses.edit', $expense) }}" class="p-1.5 text-zinc-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z" /><path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z" /></svg>
                                    </a>
                                    <form action="{{ route('expenses.destroy', $expense) }}" method="POST" onsubmit="return confirm('Hapus data pengeluaran ini secara permanen? Data transaksi & keuangan akan ikut terhapus.')" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1.5 text-zinc-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z" clip-rule="evenodd" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-zinc-400">
                                <div class="flex flex-col items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-zinc-300 mb-3"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9zM15 12c-.836 0-1.61.34-2.185.9M15 12a3 3 0 11-4.37 2.685M15 12h.01m-4.38-2.685v.01M10.884 14.685A3 3 0 1015.255 12" /></svg>
                                    <p class="font-medium text-zinc-500">Tidak ada data pengeluaran ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($expenses->hasPages())
                <div class="p-4 border-t border-zinc-100 bg-zinc-50">{{ $expenses->links() }}</div>
            @endif
        </div>
    </div>
</x-app-layout>
