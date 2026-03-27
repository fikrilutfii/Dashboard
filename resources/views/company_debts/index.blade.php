<x-app-layout>
    <x-slot name="header">Pembayaran</x-slot>

    <div class="space-y-6">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl p-6 border border-zinc-100 shadow-sm">
                <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wider">Sisa Pembayaran (Belum Lunas)</p>
                <h3 class="text-2xl font-bold text-red-500 mt-2">Rp {{ number_format($totalBelumLunas, 0, ',', '.') }}</h3>
            </div>
            <div class="bg-white rounded-2xl p-6 border border-zinc-100 shadow-sm">
                <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wider">Total Sudah Dibayar</p>
                <h3 class="text-2xl font-bold text-emerald-600 mt-2">Rp {{ number_format($totalLunas, 0, ',', '.') }}</h3>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-2xl border border-zinc-100 shadow-sm overflow-hidden">
            <div class="p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-zinc-100">
                <h3 class="text-lg font-bold text-zinc-800">Daftar Pembayaran</h3>
                <a href="{{ route('company-debts.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Tambah Pembayaran
                </a>
            </div>

            @if(session('success'))
                <div class="mx-6 mt-4 p-3 bg-emerald-50 text-emerald-700 rounded-xl text-sm border border-emerald-100">{{ session('success') }}</div>
            @endif

            <!-- Filter -->
            <div class="p-4 border-b border-zinc-100 bg-zinc-50">
                <form method="GET" class="flex flex-col sm:flex-row gap-3">
                    <select name="status" class="border rounded-md px-3 py-2 mr-2">
                        <option value="">Semua Status</option>
                        <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Belum Lunas</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                    </select>
                    <select name="type" class="text-sm border border-zinc-200 rounded-xl px-3 py-2 bg-white">
                        <option value="">Semua Jenis</option>
                        <option value="cash" {{ request('type') == 'cash' ? 'selected' : '' }}>Tunai</option>
                        <option value="credit" {{ request('type') == 'credit' ? 'selected' : '' }}>Kredit</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-zinc-800 text-white text-sm rounded-xl hover:bg-zinc-700">Terapkan</button>
                    <a href="{{ route('company-debts.index') }}" class="px-4 py-2 text-zinc-500 text-sm rounded-xl hover:bg-zinc-100">Reset</a>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-zinc-50 border-b border-zinc-100">
                        <tr>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Nama / Kreditur</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Jenis</th>
                            <th class="text-right px-6 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Jumlah</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Jatuh Tempo</th>
                            <th class="text-center px-6 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Status</th>
                            <th class="text-center px-6 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @forelse($debts as $debt)
                        <tr class="hover:bg-zinc-50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-zinc-800">{{ $debt->name }}</p>
                                @if($debt->description)
                                    <p class="text-xs text-zinc-400 mt-0.5">{{ Str::limit($debt->description, 60) }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-lg text-xs font-medium {{ $debt->type == 'cash' ? 'bg-blue-50 text-blue-700' : 'bg-purple-50 text-purple-700' }}">
                                    {{ $debt->type_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <p class="font-bold text-zinc-800">Rp {{ number_format($debt->remaining_amount, 0, ',', '.') }}</p>
                                <p class="text-[10px] text-zinc-400 mt-1">Sisa dari Rp {{ number_format($debt->amount, 0, ',', '.') }}</p>
                                <div class="w-24 bg-gray-100 rounded-full h-1 mt-2 ml-auto overflow-hidden">
                                    <div class="bg-indigo-500 h-1 rounded-full transition-all duration-500" style="width: {{ $debt->payment_percentage }}%"></div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-zinc-500 text-sm">
                                {{ $debt->due_date ? $debt->due_date->format('d M Y') : '—' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($debt->status == 'lunas')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        Lunas
                                    </span>
                                @elseif($debt->remaining_amount < $debt->amount)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                                        Sebagian
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-100">
                                        Belum Lunas
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    @if($debt->status != 'lunas')
                                        <button 
                                            onclick="openPaymentModal({{ $debt->id }}, '{{ $debt->name }}', {{ $debt->remaining_amount }}, {{ $debt->monthly_amount ?? 0 }})"
                                            class="px-3 py-1 bg-indigo-50 text-indigo-700 text-xs font-semibold rounded-lg hover:bg-indigo-100 border border-indigo-100">
                                            Bayar
                                        </button>
                                        <form action="{{ route('company-debts.mark-lunas', $debt) }}" method="POST" onsubmit="return confirm('Lunasi pembayaran ini?')">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-lg hover:bg-emerald-100 border border-emerald-100">Lunasi</button>
                                        </form>
                                    @endif
                                    <a href="{{ route('company-debts.edit', $debt) }}" class="px-3 py-1 bg-zinc-100 text-zinc-600 text-xs font-semibold rounded-lg hover:bg-zinc-200">Edit</a>
                                    <form action="{{ route('company-debts.destroy', $debt) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1 text-red-400 hover:text-red-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-zinc-400">
                                <p class="font-medium">Belum ada data pembayaran perusahaan.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-zinc-100">{{ $debts->links() }}</div>
        </div>
    </div>

    <!-- Modal Pembayaran -->
    <div id="paymentModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-2xl border border-zinc-100">
            <h3 class="text-xl font-bold text-zinc-800 mb-1">Catat Pembayaran</h3>
            <p id="modalDebtName" class="text-sm text-zinc-500 mb-6"></p>

            <form id="paymentForm" method="POST">
                @csrf
                <div class="space-y-5">
                    <div>
                        <label class="block text-xs font-bold text-zinc-400 uppercase mb-2 text-center tracking-widest">Jumlah Pembayaran (Rp)</label>
                        <input type="number" name="payment_amount" id="payment_amount" step="0.01" 
                            class="w-full text-center text-3xl font-black text-indigo-600 border-none bg-indigo-50/30 rounded-2xl py-6 focus:ring-2 focus:ring-indigo-500 transition-all" 
                            placeholder="0" required autofocus>
                        <p class="text-center text-[11px] text-zinc-400 mt-3 italic font-medium">Sisa tagihan: <span id="modalRemaining" class="text-zinc-600 font-bold"></span></p>
                    </div>

                    <div id="monthlyOption" class="hidden border-t border-zinc-100 pt-5">
                        <div class="bg-zinc-50 rounded-xl p-4">
                            <label class="block text-[10px] font-black text-zinc-400 uppercase mb-3 tracking-tighter">Opsi Angsuran (Tersedia)</label>
                            <div class="flex gap-3 items-center">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <input type="number" id="monthsToPay" value="1" min="1" class="w-16 h-10 text-center border-zinc-200 rounded-lg text-sm font-bold focus:ring-indigo-500">
                                        <span class="text-xs font-semibold text-zinc-500">Bulan</span>
                                    </div>
                                    <p class="text-[9px] text-zinc-400 mt-1">@ Rp <span id="modalMonthlyText"></span> / bln</p>
                                </div>
                                <button type="button" id="applyMonthsBtn" class="px-4 py-2 bg-white border border-zinc-200 text-indigo-600 text-xs font-bold rounded-lg hover:bg-indigo-50 transition-all shadow-sm">
                                    Gunakan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 mt-8">
                    <button type="button" onclick="closePaymentModal()" class="flex-1 px-4 py-3 border border-zinc-200 text-zinc-500 font-bold rounded-xl hover:bg-zinc-50 transition-all text-xs tracking-wider uppercase">Batal</button>
                    <button type="submit" class="flex-1 px-4 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all text-xs tracking-wider uppercase shadow-lg shadow-indigo-200">Simpan</button>
                </div>
            </form>
        </div>
    </div>

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

            amountInput.value = ''; // Let the user type
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
</x-app-layout>
