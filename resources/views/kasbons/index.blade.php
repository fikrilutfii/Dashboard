<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-[11px] font-black text-teal-800/60 uppercase tracking-[0.2em] block mb-1">Kepegawaian & Payroll</span>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">
                    Daftar Kasbon Karyawan
                </h2>
            </div>
            <div class="flex items-center gap-2.5">
                <a href="{{ route('kasbons.print_pdf', request()->all()) }}" target="_blank"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/70 border border-slate-200/80 text-slate-700 text-xs font-bold hover:bg-white shadow-sm active:scale-95 transition-all">
                    <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    Print PDF
                </a>
                <a href="{{ route('kasbons.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-teal-700 hover:bg-teal-800 text-white text-xs font-bold shadow-sm shadow-teal-900/15 active:scale-95 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    + Catat Kasbon Baru
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

        <!-- Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <!-- Kasbon Bulan Ini -->
            <div class="glass-card glass-card-hover rounded-[22px] p-5 relative overflow-hidden">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[11px] font-black uppercase tracking-wider text-slate-400">Kasbon Bulan Ini</span>
                    <div class="w-8 h-8 rounded-xl bg-blue-500/10 text-blue-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-slate-800 tracking-tight font-mono">
                    Rp {{ number_format($kasbonBulanIni, 0, ',', '.') }}
                </div>
                <div class="mt-2 text-xs font-semibold text-slate-400 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                    Pencairan kasbon periode bulan berjalan
                </div>
            </div>

            <!-- Total Sisa Kasbon -->
            <div class="glass-card glass-card-hover rounded-[22px] p-5 relative overflow-hidden">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[11px] font-black uppercase tracking-wider text-slate-400">Total Sisa Kasbon</span>
                    <div class="w-8 h-8 rounded-xl bg-amber-500/10 text-amber-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
                <div class="text-2xl font-black text-amber-700 tracking-tight font-mono">
                    Rp {{ number_format($totalOutstanding, 0, ',', '.') }}
                </div>
                <div class="mt-2 text-xs font-semibold text-slate-400 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                    Total outstanding pinjaman aktif seluruh staff
                </div>
            </div>
        </div>

        <!-- Filter & Search Card -->
        <div class="glass-card rounded-[24px] p-5 border border-white/70 shadow-sm">
            <form action="{{ route('kasbons.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
                <div class="flex-1 min-w-[260px]">
                    <div class="relative">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama karyawan..."
                            class="w-full pl-10 pr-4 py-2.5 text-xs text-slate-700 bg-white/70 border border-slate-200/80 rounded-xl focus:outline-none focus:border-teal-500 focus:bg-white focus:ring-2 focus:ring-teal-500/20 transition-all">
                    </div>
                </div>
                <button type="submit" class="px-5 py-2.5 bg-teal-700 hover:bg-teal-800 text-white rounded-xl text-xs font-bold shadow-sm shadow-teal-900/10 active:scale-95 transition-all">
                    Cari Kasbon
                </button>
                @if(request('search'))
                    <a href="{{ route('kasbons.index') }}" class="px-3.5 py-2.5 rounded-xl text-xs font-semibold text-slate-500 hover:text-slate-800 hover:bg-white/60 transition-all">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <!-- Table Card -->
        <div class="glass-card rounded-[26px] overflow-hidden border border-white/70 shadow-sm">
            <div class="bg-white/40 px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider">Daftar Pinjaman & Status Cicilan</h4>
                <span class="text-[11px] font-semibold text-slate-400">Total: {{ number_format($kasbons->total(), 0, ',', '.') }} pengajuan</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-200/60 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                            <th class="px-5 py-3.5">Tanggal</th>
                            <th class="px-5 py-3.5">Karyawan</th>
                            <th class="px-5 py-3.5">Tipe</th>
                            <th class="px-5 py-3.5 text-right">Total Pinjaman</th>
                            <th class="px-5 py-3.5 text-right">Sisa Pinjaman</th>
                            <th class="px-5 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                        @forelse($kasbons as $kasbon)
                            <tr class="hover:bg-teal-50/20 transition-colors">
                                <td class="px-5 py-3 font-mono text-slate-500 whitespace-nowrap">
                                    {{ $kasbon->date->format('d/m/Y') }}
                                </td>
                                <td class="px-5 py-3">
                                    <div class="font-bold text-slate-800">{{ $kasbon->employee->name ?? '-' }}</div>
                                    @if($kasbon->description)
                                        <div class="text-[10px] text-slate-400 mt-0.5">{{ Str::limit($kasbon->description, 35) }}</div>
                                    @endif
                                </td>
                                <td class="px-5 py-3 whitespace-nowrap">
                                    @if($kasbon->type == 'personal_credit')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black bg-blue-50 text-blue-700 border border-blue-200/60">Kredit Pribadi</span>
                                    @elseif($kasbon->type == 'personal_loan')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black bg-purple-50 text-purple-700 border border-purple-200/60">Pinjaman Cash</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black bg-slate-100 text-slate-700 border border-slate-200/60">Kasbon Staff</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-right font-mono font-bold text-slate-800 whitespace-nowrap">
                                    Rp {{ number_format($kasbon->amount, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-3 text-right whitespace-nowrap">
                                    <div class="flex flex-col items-end">
                                        <div class="font-mono font-bold text-xs {{ $kasbon->remaining_amount <= 0 ? 'text-emerald-700' : 'text-rose-600' }}">
                                            Rp {{ number_format($kasbon->remaining_amount, 0, ',', '.') }}
                                        </div>
                                        <!-- Progress Bar -->
                                        <div class="w-24 h-1.5 bg-slate-100 rounded-full mt-1.5 overflow-hidden">
                                            <div class="h-full bg-emerald-500 rounded-full" style="width: {{ $kasbon->payment_percentage }}%"></div>
                                        </div>
                                        <div class="text-[9px] text-slate-400 mt-1 font-semibold">
                                            Terbayar: {{ number_format($kasbon->payment_percentage, 0) }}%
                                        </div>

                                        @if($kasbon->installment_amount > 0)
                                            <div class="mt-1 inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                Cicilan: Rp {{ number_format($kasbon->installment_amount, 0, ',', '.') }}/bln
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1.5">
                                        @if($kasbon->remaining_amount > 0)
                                            <button onclick="openRepayModal('{{ $kasbon->id }}', '{{ $kasbon->employee->name }}', {{ $kasbon->remaining_amount }}, {{ $kasbon->installment_amount }})"
                                                class="px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-[10px] shadow-sm shadow-emerald-600/20 active:scale-95 transition-all">
                                                Bayar
                                            </button>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200/60 uppercase">
                                                Lunas
                                            </span>
                                        @endif

                                        @if(($kasbon->status === 'aktif' || $kasbon->status === 'lunas') && Auth::user()->isAdmin())
                                            <a href="{{ route('kasbons.edit', $kasbon) }}"
                                                class="px-2.5 py-1 rounded-lg bg-white/70 border border-slate-200 text-amber-700 hover:bg-white text-[10px] font-bold transition-all">
                                                Edit
                                            </a>
                                        @endif

                                        @if(Auth::user()->isAdmin())
                                            <form action="{{ route('kasbons.destroy', $kasbon) }}" method="POST" class="action-confirm inline-block"
                                                data-title="Hapus Kasbon Ini?"
                                                data-text="Apakah Anda yakin ingin menghapus catatan kasbon ini? Data yang dihapus tidak dapat dipulihkan."
                                                data-icon="warning"
                                                data-confirm-text="Ya, Hapus"
                                                data-confirm-color="#ef4444">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-1 rounded-lg text-rose-500 hover:bg-rose-50 transition-all" title="Hapus Kasbon">
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
                                        <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        Belum ada catatan pinjaman atau kasbon.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($kasbons->hasPages())
                <div class="px-6 py-4 bg-white/40 border-t border-slate-100">
                    {{ $kasbons->links() }}
                </div>
            @endif
        </div>

    </div>

    <!-- Repayment Modal -->
    <div id="repayModal" class="fixed inset-0 bg-slate-900/60 hidden overflow-y-auto h-full w-full z-50 backdrop-blur-md">
        <div class="relative top-20 mx-auto p-6 md:p-8 border border-white/60 w-[95%] max-w-[420px] shadow-2xl rounded-[28px] bg-white/95 backdrop-blur-xl">
            <div class="mb-4 flex justify-between items-center pb-3 border-b border-slate-100">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-teal-800/60 block">Manajemen Kasbon</span>
                    <h3 class="text-lg font-black text-slate-800">Pembayaran Cicilan</h3>
                </div>
                <button type="button" onclick="document.getElementById('repayModal').classList.add('hidden')" class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 hover:text-slate-600 flex items-center justify-center transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="bg-slate-50/70 rounded-xl p-3 mb-4 space-y-1 text-xs">
                <div class="flex justify-between">
                    <span class="text-slate-500">Karyawan:</span>
                    <span id="modalEmployeeName" class="font-bold text-slate-800"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Sisa Pinjaman:</span>
                    <span class="font-mono font-bold text-rose-600">Rp <span id="modalRemaining"></span></span>
                </div>
            </div>
            
            <form id="repayForm" method="POST" class="action-confirm space-y-3"
                data-title="Konfirmasi Pembayaran Cicilan"
                data-text="Catat pembayaran cicilan kasbon ini? Penerimaan kas akan tercatat di laporan keuangan."
                data-icon="question"
                data-confirm-text="Ya, Simpan Pembayaran"
                data-confirm-color="#0f766e">
                @csrf
                <div id="monthlyOption" class="hidden pb-2 border-b border-slate-100">
                    <label class="block text-slate-700 text-xs font-bold mb-1">Pilih Jumlah Bulan</label>
                    <div class="flex gap-2 items-center">
                        <input type="number" id="monthsToPay" value="1" min="1" class="w-20 border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-center font-bold">
                        <span class="text-xs text-slate-500">Bulan × Rp <span id="modalMonthlyText" class="font-mono font-bold text-slate-700"></span></span>
                    </div>
                </div>

                <div>
                    <label class="block text-slate-700 text-xs font-bold mb-1">Jumlah Bayar (Rp) <span class="text-rose-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-xs font-bold text-slate-400">Rp</span>
                        <input type="number" name="amount" id="repayAmount" class="w-full pl-10 pr-3 py-2 text-xs font-mono font-bold border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600" required>
                    </div>
                </div>
                
                <div>
                    <label class="block text-slate-700 text-xs font-bold mb-1">Tanggal Bayar <span class="text-rose-500">*</span></label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 text-xs border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600" required>
                </div>

                <div>
                    <label class="block text-slate-700 text-xs font-bold mb-1">Keterangan</label>
                    <input type="text" name="description" placeholder="Contoh: Cicilan ke-1" class="w-full px-3 py-2 text-xs border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600">
                </div>
                
                <div class="flex justify-end gap-2.5 pt-3">
                    <button type="button" onclick="document.getElementById('repayModal').classList.add('hidden')" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-200 transition-all">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-teal-700 hover:bg-teal-800 text-white rounded-xl text-xs font-bold shadow-sm shadow-teal-900/10 active:scale-95 transition-all">
                        Simpan Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function openRepayModal(id, name, remaining, installment) {
            document.getElementById('repayModal').classList.remove('hidden');
            document.getElementById('repayForm').action = `/kasbons/${id}/repay`;
            document.getElementById('modalEmployeeName').innerText = name;
            document.getElementById('modalRemaining').innerText = new Intl.NumberFormat('id-ID').format(remaining);
            
            const monthlyOption = document.getElementById('monthlyOption');
            const monthlyText = document.getElementById('modalMonthlyText');
            const monthsInput = document.getElementById('monthsToPay');
            const amountInput = document.getElementById('repayAmount');

            if (installment > 0) {
                monthlyOption.classList.remove('hidden');
                monthlyText.innerText = new Intl.NumberFormat('id-ID').format(installment);
                monthsInput.value = 1;
                amountInput.value = installment;
                
                monthsInput.oninput = function() {
                    amountInput.value = Math.min(this.value * installment, remaining);
                };
            } else {
                monthlyOption.classList.add('hidden');
                amountInput.value = remaining;
                monthsInput.oninput = null;
            }

            amountInput.max = remaining;
        }
    </script>
    @endpush
</x-app-layout>
