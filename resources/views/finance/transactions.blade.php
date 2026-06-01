<x-app-layout>
    <x-slot name="header">Kelola Transaksi</x-slot>

    <div class="space-y-6">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl p-6 border border-zinc-100 shadow-sm">
                <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wider">Total Pemasukan (Cash)</p>
                <h3 class="text-2xl font-bold text-emerald-600 mt-2">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h3>
            </div>
            <div class="bg-white rounded-2xl p-6 border border-zinc-100 shadow-sm">
                <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wider">Total Pengeluaran (Cash)</p>
                <h3 class="text-2xl font-bold text-rose-600 mt-2">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
            </div>
            <div class="bg-white rounded-2xl p-6 border border-zinc-100 shadow-sm bg-zinc-50 border-zinc-200">
                <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wider">Saldo Bersih (Cash)</p>
                <h3 class="text-2xl font-bold text-zinc-800 mt-2">Rp {{ number_format($totalPemasukan - $totalPengeluaran, 0, ',', '.') }}</h3>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-2xl border border-zinc-100 shadow-sm overflow-hidden">
            <div class="p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-zinc-100">
                <h3 class="text-lg font-bold text-zinc-800">Riwayat Transaksi</h3>
                <button onclick="document.getElementById('transactionModal').classList.remove('hidden')" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100 active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Catat Transaksi Baru
                </button>
            </div>

            @if(session('success'))
                <div class="mx-6 mt-4 p-3 bg-emerald-50 text-emerald-700 rounded-xl text-sm border border-emerald-100 font-medium">{{ session('success') }}</div>
            @endif

            <!-- Filter -->
            <div class="p-4 border-b border-zinc-100 bg-zinc-50/50">
                <form method="GET" class="grid grid-cols-1 sm:grid-cols-5 gap-3">
                    <div>
                        <label class="block text-[10px] font-bold text-zinc-400 uppercase mb-1">Jenis</label>
                        <select name="type" class="w-full text-sm border border-zinc-200 rounded-xl px-3 py-2 bg-white">
                            <option value="">Semua</option>
                            <option value="credit" {{ request('type') == 'credit' ? 'selected' : '' }}>Pemasukan</option>
                            <option value="debit" {{ request('type') == 'debit' ? 'selected' : '' }}>Pengeluaran</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-zinc-400 uppercase mb-1">Mulai</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full text-sm border border-zinc-200 rounded-xl px-3 py-2 bg-white">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-zinc-400 uppercase mb-1">Selesai</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full text-sm border border-zinc-200 rounded-xl px-3 py-2 bg-white">
                    </div>
                    <div class="sm:col-span-2 flex items-end gap-2">
                        <button type="submit" class="flex-1 py-2 bg-zinc-800 text-white text-sm font-bold rounded-xl hover:bg-zinc-700 transition-all">Terapkan Filter</button>
                        <a href="{{ route('finance.transactions') }}" class="px-4 py-2 text-zinc-500 text-sm font-bold rounded-xl hover:bg-zinc-100 transition-all text-center">Reset</a>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-zinc-50 border-b border-zinc-100">
                        <tr>
                            <th class="text-left px-6 py-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Tanggal</th>
                            <th class="text-left px-6 py-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Keterangan</th>
                            <th class="text-left px-6 py-4 text-[10px] font-black text-zinc-400 uppercase tracking-widest">Jenis</th>
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
                                @if($t->type == 'credit')
                                    <span class="px-2 py-1 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded text-[10px] font-black uppercase">Pemasukan</span>
                                @else
                                    <span class="px-2 py-1 bg-rose-50 text-rose-600 border border-rose-100 rounded text-[10px] font-black uppercase">Pengeluaran</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right font-black text-base {{ $t->type == 'credit' ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ $t->type == 'credit' ? '+' : '-' }} Rp {{ number_format($t->amount, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-zinc-400 italic">
                                Belum ada data transaksi dalam periode ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-zinc-100">{{ $transaksi->links() }}</div>
        </div>
    </div>

    <!-- Unified Transaction Modal -->
    <div id="transactionModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                 x-data="{ type: 'credit', method: 'cash', isLoan: false }">
                <form action="{{ route('finance.storeTransaction') }}" method="POST">
                    @csrf
                    <input type="hidden" name="division" value="{{ session('division', 'percetakan') }}">
                    
                    <div class="bg-white px-6 pt-6 pb-4">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-bold text-zinc-800">Catat Transaksi</h3>
                            <button type="button" onclick="document.getElementById('transactionModal').classList.add('hidden')" class="text-zinc-400 hover:text-zinc-600">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <div class="space-y-5">
                            <!-- Type Selection -->
                            <div>
                                <label class="block text-xs font-black text-zinc-400 uppercase tracking-widest mb-2">Jenis Transaksi</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="type" value="credit" x-model="type" class="hidden">
                                        <div class="py-3 text-center rounded-xl border-2 font-bold text-sm transition-all"
                                             :class="type === 'credit' ? 'border-emerald-500 bg-emerald-50 text-emerald-700' : 'border-zinc-100 text-zinc-400'">
                                            PEMASUKAN
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="type" value="debit" x-model="type" class="hidden">
                                        <div class="py-3 text-center rounded-xl border-2 font-bold text-sm transition-all"
                                             :class="type === 'debit' ? 'border-rose-500 bg-rose-50 text-rose-700' : 'border-zinc-100 text-zinc-400'">
                                            PENGELUARAN
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Payment Method -->
                            <div>
                                <label class="block text-xs font-black text-zinc-400 uppercase tracking-widest mb-2">Metode Pembayaran</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="payment_method" value="cash" x-model="method" class="hidden">
                                        <div class="py-3 text-center rounded-xl border-2 font-bold text-sm transition-all"
                                             :class="method === 'cash' ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-zinc-100 text-zinc-400'">
                                            TUNAI (CASH)
                                        </div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="payment_method" value="credit" x-model="method" class="hidden">
                                        <div class="py-3 text-center rounded-xl border-2 font-bold text-sm transition-all"
                                             :class="method === 'credit' ? 'border-amber-500 bg-amber-50 text-amber-700' : 'border-zinc-100 text-zinc-400'">
                                            TEMPO / BERJANGKA
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-black text-zinc-400 uppercase tracking-widest mb-2">Tanggal</label>
                                    <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full border-zinc-200 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-zinc-400 uppercase tracking-widest mb-2">Jumlah (Rp)</label>
                                    <input type="number" name="amount" class="w-full border-zinc-200 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="0" required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-black text-zinc-400 uppercase tracking-widest mb-2">Keterangan / Deskripsi</label>
                                <input type="text" name="description" class="w-full border-zinc-200 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Contoh: Pinjaman modal atau Biaya sewa" required>
                            </div>

                            <div>
                                <label class="block text-xs font-black text-zinc-400 uppercase tracking-widest mb-2">Kategori (Opsional)</label>
                                <input type="text" name="category" class="w-full border-zinc-200 rounded-xl shadow-sm focus:ring-indigo-500 focus:border-indigo-500" placeholder="Contoh: Modal / Operasional">
                            </div>

                            <!-- Special Toggle for Cash Loans -->
                            <div x-show="method === 'cash'" class="p-4 bg-indigo-50 rounded-2xl border border-indigo-100">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_loan" value="1" x-model="isLoan" class="rounded border-indigo-300 text-indigo-600 focus:ring-indigo-500">
                                    <span class="ml-3 text-sm font-bold text-indigo-700">
                                        <span x-show="type === 'credit'">Otomatis Catat ke Daftar Pembayaran</span>
                                        <span x-show="type === 'debit'">Otomatis Catat ke Daftar Tagihan</span>
                                    </span>
                                </label>
                                <p class="text-[10px] text-indigo-500 mt-1 ml-7 italic">
                                    *Ceklis ini jika transaksi tunai ini ingin dipantau pembayarannya/penagihannya di masa depan.
                                </p>
                            </div>

                            <!-- Installment Fields -->
                            <div x-show="method === 'credit' || (method === 'cash' && isLoan)" x-transition class="p-4 bg-amber-50 rounded-2xl border border-amber-100 space-y-4">
                                <p class="text-xs font-bold text-amber-700 uppercase">Pengaturan Kredit/Cicilan</p>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-black text-amber-600/60 uppercase mb-1">Tenor (Bulan)</label>
                                        <input type="number" name="tenure" value="12" min="1" class="w-full border-amber-200 rounded-xl bg-white text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-black text-amber-600/60 uppercase mb-1">Jatuh Tempo Pertama</label>
                                        <input type="date" name="due_date" value="{{ date('Y-m-d', strtotime('+1 month')) }}" class="w-full border-amber-200 rounded-xl bg-white text-sm">
                                    </div>
                                </div>
                                <p class="text-[10px] text-amber-600 italic">* Transaksi ini akan otomatis masuk ke daftar <span x-text="type === 'credit' ? 'Pembayaran' : 'Tagihan'"></span> perusahaan.</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-zinc-50 px-6 py-4 flex flex-row-reverse gap-3">
                        <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100">
                            Simpan Transaksi
                        </button>
                        <button type="button" onclick="document.getElementById('transactionModal').classList.add('hidden')" class="px-6 py-2.5 bg-white text-zinc-500 text-sm font-bold rounded-xl border border-zinc-200 hover:bg-zinc-50 transition-all">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
