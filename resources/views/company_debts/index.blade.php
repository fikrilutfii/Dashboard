<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-[11px] font-black text-teal-800/60 uppercase tracking-[0.2em] block mb-1">Kewajiban & Hutang</span>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">
                    Daftar Pembayaran Hutang
                </h2>
            </div>
            <div>
                <a href="{{ route('company-debts.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-teal-700 hover:bg-teal-800 text-white text-xs font-bold shadow-sm shadow-teal-900/15 active:scale-95 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    + Tambah Pembayaran
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

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="glass-card glass-card-hover rounded-[22px] p-5 relative overflow-hidden">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[11px] font-black uppercase tracking-wider text-slate-400">Sisa Pembayaran</span>
                    <div class="w-8 h-8 rounded-xl bg-rose-500/10 text-rose-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <h3 class="text-2xl font-black text-rose-600 font-mono tracking-tight">
                    Rp {{ number_format($totalBelumLunas, 0, ',', '.') }}
                </h3>
                <p class="text-xs text-slate-400 mt-2 font-medium">Total kewajiban yang belum terlunasi</p>
            </div>

            <div class="glass-card glass-card-hover rounded-[22px] p-5 relative overflow-hidden">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[11px] font-black uppercase tracking-wider text-slate-400">Jatuh Tempo Bulan Ini</span>
                    <div class="w-8 h-8 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                </div>
                <h3 class="text-2xl font-black text-amber-700 font-mono tracking-tight">
                    Rp {{ number_format($totalAngsuranBulanIni, 0, ',', '.') }}
                </h3>
                <p class="text-xs text-slate-400 mt-2 font-medium">Tagihan jatuh tempo dalam bulan berjalan</p>
            </div>

            <div class="glass-card glass-card-hover rounded-[22px] p-5 relative overflow-hidden">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[11px] font-black uppercase tracking-wider text-slate-400">Total Terbayar</span>
                    <div class="w-8 h-8 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <h3 class="text-2xl font-black text-emerald-600 font-mono tracking-tight">
                    Rp {{ number_format($totalLunas, 0, ',', '.') }}
                </h3>
                <p class="text-xs text-slate-400 mt-2 font-medium">Akumulasi hutang yang sudah dilunasi</p>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="glass-card rounded-[24px] p-5 border border-white/70 shadow-sm">
            <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
                <div class="lg:col-span-2">
                    <label class="block text-[10px] font-black text-slate-400 mb-1 uppercase tracking-wider">Cari Nama / Keterangan</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama kreditur, keterangan..."
                        class="w-full bg-white/70 border border-slate-200/80 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-teal-500">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 mb-1 uppercase tracking-wider">Status</label>
                    <select name="status" class="w-full bg-white/70 border border-slate-200/80 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-teal-500">
                        <option value="">Semua Status</option>
                        <option value="belum_lunas" {{ request('status') == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                        <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-400 mb-1 uppercase tracking-wider">Jenis</label>
                    <select name="type" class="w-full bg-white/70 border border-slate-200/80 rounded-xl px-3 py-2 text-xs text-slate-700 focus:outline-none focus:border-teal-500">
                        <option value="">Semua Jenis</option>
                        <option value="cash" {{ request('type') == 'cash' ? 'selected' : '' }}>Tunai</option>
                        <option value="credit" {{ request('type') == 'credit' ? 'selected' : '' }}>Kredit</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-teal-700 hover:bg-teal-800 text-white rounded-xl text-xs font-bold shadow-sm shadow-teal-900/10 active:scale-95 transition-all">
                        Terapkan
                    </button>
                    <a href="{{ route('company-debts.index') }}" class="px-3 py-2 text-xs font-semibold text-slate-500 hover:text-slate-800">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Table Card -->
        <div class="glass-card rounded-[26px] overflow-hidden border border-white/70 shadow-sm">
            <div class="bg-white/40 px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider">Daftar Kewajiban & Pembayaran Hutang</h4>
                <span class="text-[11px] font-semibold text-slate-400">Total: {{ number_format($debts->total(), 0, ',', '.') }} catatan</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-200/60 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                            <th class="px-5 py-3.5">Kreditur / Nama</th>
                            <th class="px-5 py-3.5">Jenis</th>
                            <th class="px-5 py-3.5 text-right">Sisa / Total</th>
                            <th class="px-5 py-3.5 text-center">Jatuh Tempo</th>
                            <th class="px-5 py-3.5 text-center">Status</th>
                            <th class="px-5 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                        @forelse($debts as $debt)
                            <tr class="hover:bg-teal-50/20 transition-colors">
                                <td class="px-5 py-3.5">
                                    <div class="font-bold text-slate-800">{{ $debt->name }}</div>
                                    @if($debt->description)
                                        <div class="text-[10px] text-slate-400 mt-0.5 line-clamp-1">{{ $debt->description }}</div>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black uppercase {{ $debt->type == 'cash' ? 'bg-blue-50 text-blue-700 border border-blue-200/60' : 'bg-purple-50 text-purple-700 border border-purple-200/60' }}">
                                        {{ $debt->type_label }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-right whitespace-nowrap">
                                    <div class="font-mono font-bold text-slate-800">Rp {{ number_format($debt->remaining_amount, 0, ',', '.') }}</div>
                                    <div class="text-[10px] text-slate-400">dari Rp {{ number_format($debt->amount, 0, ',', '.') }}</div>
                                    @if($debt->monthly_amount > 0 && $debt->status != 'lunas')
                                        <div class="mt-1">
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                ± {{ ceil($debt->remaining_amount / $debt->monthly_amount) }} bln lagi
                                            </span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-center font-mono text-slate-500 whitespace-nowrap">
                                    {{ $debt->due_date ? $debt->due_date->format('d/m/Y') : '-' }}
                                </td>
                                <td class="px-5 py-3.5 text-center whitespace-nowrap">
                                    @if($debt->status === 'lunas')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200/60 uppercase">
                                            Lunas
                                        </span>
                                    @elseif($debt->remaining_amount < $debt->amount)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-amber-50 text-amber-700 border border-amber-200/60 uppercase">
                                            Sebagian
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-rose-50 text-rose-700 border border-rose-200/60 uppercase">
                                            Belum Lunas
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1.5">
                                        @if($debt->status != 'lunas')
                                            <button onclick="openPaymentModal({{ $debt->id }}, '{{ $debt->name }}', {{ $debt->remaining_amount }}, {{ $debt->monthly_amount ?? 0 }})"
                                                class="px-2.5 py-1 rounded-lg bg-teal-700 hover:bg-teal-800 text-white font-bold text-[10px] shadow-sm active:scale-95 transition-all">
                                                Bayar
                                            </button>
                                            <form action="{{ route('company-debts.mark-lunas', $debt) }}" method="POST" class="action-confirm inline-block"
                                                data-title="Konfirmasi Pelunasan"
                                                data-text="Lunasi pembayaran hutang ini? Saldo kas akan terpotong otomatis."
                                                data-icon="question"
                                                data-confirm-text="Ya, Lunasi"
                                                data-confirm-color="#0f766e">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100 font-bold text-[10px] transition-all">
                                                    Lunasi
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('company-debts.revert-lunas', $debt) }}" method="POST" class="action-confirm inline-block"
                                                data-title="Batalkan Status Lunas?"
                                                data-text="Kembalikan status hutang {{ $debt->name }} ke Belum Lunas dan batalkan mutasi kas terkait?"
                                                data-icon="warning"
                                                data-confirm-text="Ya, Batalkan Lunas"
                                                data-confirm-color="#f59e0b">
                                                @csrf
                                                <button type="submit" class="px-2.5 py-1 rounded-lg bg-amber-50 text-amber-800 border border-amber-200 hover:bg-amber-100 font-bold text-[10px] transition-all">
                                                    Batal Lunas
                                                </button>
                                            </form>
                                        @endif

                                        @if($debt->purchase_id)
                                            <a href="{{ route('purchases.edit', $debt->purchase_id) }}" class="px-2 py-1 bg-white/70 border border-slate-200 text-slate-700 hover:bg-white text-[10px] font-bold rounded-lg transition-all">
                                                Rincian
                                            </a>
                                        @else
                                            <a href="{{ route('company-debts.edit', $debt) }}" class="px-2 py-1 bg-white/70 border border-slate-200 text-slate-700 hover:bg-white text-[10px] font-bold rounded-lg transition-all">
                                                Edit
                                            </a>
                                        @endif

                                        @if(Auth::user()->isAdmin())
                                            <form action="{{ route('company-debts.destroy', $debt) }}" method="POST" class="action-confirm inline-block"
                                                data-title="Hapus Hutang Ini?"
                                                data-text="Hapus data kewajiban ini secara permanen?"
                                                data-icon="warning"
                                                data-confirm-text="Ya, Hapus"
                                                data-confirm-color="#ef4444">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-1 rounded-lg text-rose-500 hover:bg-rose-50 transition-all" title="Hapus">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400 font-medium">
                                    <div class="max-w-xs mx-auto">
                                        <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        Belum ada data pembayaran atau hutang perusahaan.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($debts->hasPages())
                <div class="px-6 py-4 bg-white/40 border-t border-slate-100">
                    {{ $debts->links() }}
                </div>
            @endif
        </div>

    </div>

    <!-- Modal Pembayaran -->
    <div id="paymentModal" class="fixed inset-0 bg-slate-900/60 hidden items-center justify-center z-50 backdrop-blur-md">
        <div class="bg-white/95 backdrop-blur-xl rounded-[28px] p-6 md:p-8 w-full max-w-md shadow-2xl border border-white/60 mx-4">
            <div class="mb-4 flex justify-between items-center pb-3 border-b border-slate-100">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-teal-800/60 block">Pelunasan Hutang</span>
                    <h3 class="text-lg font-black text-slate-800">Catat Pembayaran</h3>
                </div>
                <button type="button" onclick="closePaymentModal()" class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 hover:text-slate-600 flex items-center justify-center transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <p id="modalDebtName" class="text-xs font-semibold text-slate-500 mb-4"></p>

            <form id="paymentForm" method="POST" class="action-confirm space-y-4"
                data-title="Konfirmasi Pembayaran Hutang"
                data-text="Simpan pencatatan pembayaran hutang ini? Saldo kas akan terpotong."
                data-icon="question"
                data-confirm-text="Ya, Simpan Pembayaran"
                data-confirm-color="#0f766e">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1 text-center">Jumlah Pembayaran (Rp)</label>
                    <input type="number" name="payment_amount" id="payment_amount" step="0.01" 
                        class="w-full text-center text-2xl font-mono font-black text-teal-800 border border-slate-200 bg-white rounded-xl py-3 focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all" 
                        placeholder="0" required autofocus>
                    <p class="text-center text-[11px] text-slate-400 mt-1.5">Sisa tagihan: <span id="modalRemaining" class="text-slate-700 font-bold font-mono"></span></p>
                </div>

                <div id="monthlyOption" class="hidden border-t border-slate-100 pt-3">
                    <div class="bg-slate-50/80 rounded-xl p-3 text-xs">
                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Opsi Angsuran Bulanan</label>
                        <div class="flex gap-2 items-center">
                            <input type="number" id="monthsToPay" value="1" min="1" class="w-16 text-center border border-slate-200 rounded-lg text-xs font-bold py-1.5">
                            <span class="text-xs text-slate-500">Bulan × Rp <span id="modalMonthlyText" class="font-mono font-bold text-slate-700"></span></span>
                            <button type="button" id="applyMonthsBtn" class="ml-auto px-3 py-1.5 bg-white border border-slate-200 text-teal-700 text-xs font-bold rounded-lg hover:bg-slate-50 shadow-sm">
                                Gunakan
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex gap-2.5 pt-3">
                    <button type="button" onclick="closePaymentModal()" class="flex-1 py-2.5 bg-slate-100 text-slate-600 font-bold rounded-xl text-xs hover:bg-slate-200 transition-all">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 py-2.5 bg-teal-700 hover:bg-teal-800 text-white font-bold rounded-xl text-xs shadow-sm shadow-teal-900/10 active:scale-95 transition-all">
                        Simpan Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        let currentMonthly = 0;
        let currentRemaining = 0;

        function openPaymentModal(id, name, remaining, monthly) {
            currentMonthly = monthly;
            currentRemaining = remaining;

            document.getElementById('modalDebtName').innerText = name;
            document.getElementById('modalRemaining').innerText = formatIDR(remaining);
            
            const monthlyOption = document.getElementById('monthlyOption');
            const monthlyText = document.getElementById('modalMonthlyText');
            const monthsInput = document.getElementById('monthsToPay');
            const amountInput = document.getElementById('payment_amount');
            const applyBtn = document.getElementById('applyMonthsBtn');

            amountInput.value = '';
            amountInput.max = remaining;

            if (monthly > 0) {
                monthlyOption.classList.remove('hidden');
                monthlyText.innerText = new Intl.NumberFormat('id-ID').format(monthly);
                monthsInput.value = 1;

                applyBtn.onclick = function() {
                    let total = monthsInput.value * monthly;
                    amountInput.value = Math.min(total, remaining).toFixed(2);
                };
            } else {
                monthlyOption.classList.add('hidden');
            }

            document.getElementById('paymentForm').action = "/company-debts/" + id + "/payment";
            document.getElementById('paymentModal').classList.remove('hidden');
            document.getElementById('paymentModal').classList.add('flex');
            
            setTimeout(() => amountInput.focus(), 100);
        }

        function formatIDR(amount) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
        }

        function closePaymentModal() {
            document.getElementById('paymentModal').classList.add('hidden');
            document.getElementById('paymentModal').classList.remove('flex');
        }
    </script>
    @endpush
</x-app-layout>
