<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Kasbon') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">
                    <!-- Action Buttons -->
                    <div class="flex justify-end mb-4">
                        <a href="{{ route('kasbons.create') }}" class="bg-primary-600 text-white px-4 py-2 rounded shadow hover:bg-primary-700 transition">
                            + Catat Kasbon Baru
                        </a>
                    </div>

                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr>
                                <th class="px-5 py-3 border-b-2 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">Tanggal</th>
                                <th class="px-5 py-3 border-b-2 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">Karyawan</th>
                                <th class="px-5 py-3 border-b-2 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase">Tipe</th>
                                <th class="px-5 py-3 border-b-2 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase">Total Pinjaman</th>
                                <th class="px-5 py-3 border-b-2 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase">Sisa Pinjaman</th>
                                <th class="px-5 py-3 border-b-2 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kasbons as $kasbon)
                                <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                                    <td class="px-5 py-3 border-b text-sm">
                                        {{ $kasbon->date->format('d/m/Y') }}
                                    </td>
                                    <td class="px-5 py-3 border-b font-bold text-sm">
                                        {{ $kasbon->employee->name }}
                                        <div class="text-xs text-gray-500">{{ Str::limit($kasbon->description, 20) }}</div>
                                    </td>
                                    <td class="px-5 py-3 border-b text-sm">
                                        @if($kasbon->type == 'personal_credit')
                                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">Kredit Pribadi</span>
                                        @elseif($kasbon->type == 'personal_loan')
                                            <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-xs">Pinjaman Cash</span>
                                        @else
                                            <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs">Kasbon Staff</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 border-b text-right text-sm">
                                        <div class="font-mono text-gray-600">Rp {{ number_format($kasbon->amount, 0, ',', '.') }}</div>
                                        <div class="text-[10px] text-gray-400 mt-1 uppercase italic">Total Pinjaman</div>
                                    </td>
                                    <td class="px-5 py-3 border-b">
                                        <div class="flex flex-col items-end">
                                            <div class="text-sm font-bold font-mono text-red-600">
                                                Rp {{ number_format($kasbon->remaining_amount, 0, ',', '.') }}
                                            </div>
                                            
                                            <!-- Progress Bar -->
                                            <div class="w-full max-w-[100px] h-1.5 bg-gray-100 rounded-full mt-1.5 overflow-hidden">
                                                <div class="h-full bg-green-500 rounded-full" style="width: {{ $kasbon->payment_percentage }}%"></div>
                                            </div>
                                            <div class="text-[9px] text-gray-400 mt-1 uppercase">Terbayar: {{ number_format($kasbon->payment_percentage, 0) }}%</div>

                                            @if($kasbon->installment_amount > 0)
                                                <div class="mt-2 inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100 uppercase tracking-tighter">
                                                    Cicilan: Rp {{ number_format($kasbon->installment_amount, 0, ',', '.') }} / Bulan
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-5 py-3 border-b text-center text-sm">
                                        @if($kasbon->remaining_amount > 0)
                                            <button onclick="openRepayModal('{{ $kasbon->id }}', '{{ $kasbon->employee->name }}', {{ $kasbon->remaining_amount }}, {{ $kasbon->installment_amount }})" class="bg-green-500 text-white px-3 py-1 rounded text-xs hover:bg-green-600 transition">
                                                Bayar
                                            </button>
                                        @else
                                            <span class="text-green-600 font-bold text-xs">LUNAS</span>
                                        @endif
                                        
                                        <form action="{{ route('kasbons.destroy', $kasbon) }}" method="POST" class="inline ml-2" onsubmit="return confirm('Hapus data ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-600 text-xs">&times;</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-3 border-b text-center text-gray-500">Belum ada data pinjaman.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Repayment Modal -->
                    <div id="repayModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
                        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                            <div class="mt-3 text-center">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Pembayaran Cicilan</h3>
                                <div class="mt-2 text-left">
                                    <p class="text-sm text-gray-500 mb-4">Karyawan: <span id="modalEmployeeName" class="font-bold"></span></p>
                                    <p class="text-sm text-gray-500 mb-4">Sisa Pinjaman: <span id="modalRemaining" class="font-bold"></span></p>
                                    
                                    <form id="repayForm" method="POST">
                                        @csrf
                                        <div id="monthlyOption" class="hidden mb-4 border-b pb-4">
                                            <label class="block text-gray-700 text-sm font-bold mb-2">Pilih Jumlah Bulan</label>
                                            <div class="flex gap-2 items-center">
                                                <input type="number" id="monthsToPay" value="1" min="1" class="w-20 border rounded px-3 py-2">
                                                <span class="text-sm text-gray-500">Bulan × <span id="modalMonthlyText"></span></span>
                                            </div>
                                        </div>

                                        <label class="block text-gray-700 text-sm font-bold mb-2">Jumlah Bayar (Rp)</label>
                                        <input type="number" name="amount" id="repayAmount" class="w-full border rounded px-3 py-2 mb-3" required>
                                        
                                        <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal</label>
                                        <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full border rounded px-3 py-2 mb-3" required>
                                        
                                        <div class="flex justify-end gap-2 mt-4">
                                            <button type="button" onclick="document.getElementById('repayModal').classList.add('hidden')" class="bg-gray-300 text-gray-800 px-4 py-2 rounded">Batal</button>
                                            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

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
                    <div class="mt-4">
                        {{ $kasbons->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
