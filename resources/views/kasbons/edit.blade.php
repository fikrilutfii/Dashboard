<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Kasbon Karyawan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($errors->any())
                        <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4">
                            <ul class="list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('kasbons.update', $kasbon) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Karyawan</label>
                                <select name="employee_id" class="w-full border rounded px-3 py-2" required>
                                    @foreach($employees as $emp)
                                        <option value="{{ $emp->id }}" {{ $kasbon->employee_id == $emp->id ? 'selected' : '' }}>
                                            {{ $emp->name }} ({{ ucfirst($emp->division) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Tipe Pinjaman</label>
                                <select name="type" class="w-full border rounded px-3 py-2 bg-gray-50" onchange="toggleInstallment(this.value)" required>
                                    <option value="staff_kasbon" {{ $kasbon->type == 'staff_kasbon' ? 'selected' : '' }}>Kasbon Staff (Potong Gaji)</option>
                                    @if(session('division') == 'percetakan')
                                        <option value="personal_credit" {{ $kasbon->type == 'personal_credit' ? 'selected' : '' }}>Kredit Pribadi (Dicicil)</option>
                                        <option value="personal_loan" {{ $kasbon->type == 'personal_loan' ? 'selected' : '' }}>Pinjaman Pribadi (Cash)</option>
                                    @endif
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Pilih jenis pinjaman sesuai kebijakan.</p>
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal</label>
                                <input type="date" name="date" value="{{ $kasbon->date->format('Y-m-d') }}" class="w-full border rounded px-3 py-2" required>
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Nominal Pinjaman (Rp)</label>
                                <input type="number" name="amount" value="{{ $kasbon->amount }}" class="w-full border rounded px-3 py-2" placeholder="Contoh: 1000000" required>
                                @php $paid_amount = $kasbon->amount - $kasbon->remaining_amount; @endphp
                                @if($paid_amount > 0)
                                    <p class="text-xs text-red-500 mt-1">Nominal tidak boleh kurang dari Rp {{ number_format($paid_amount, 0, ',', '.') }} (Sudah dibayar).</p>
                                @endif
                            </div>

                            <div id="installment_box" class="{{ in_array($kasbon->type, ['personal_credit', 'staff_kasbon']) ? '' : 'hidden' }}">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Rencana Cicilan per Bulan (Rp)</label>
                                <input type="number" name="installment_amount" value="{{ $kasbon->installment_amount }}" class="w-full border rounded px-3 py-2" placeholder="Contoh: 100000">
                                <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ada rencana cicilan tetap.</p>
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Keterangan / Keperluan</label>
                                <input type="text" name="description" value="{{ $kasbon->description }}" class="w-full border rounded px-3 py-2">
                            </div>
                        </div>

                        <script>
                            function toggleInstallment(type) {
                                const box = document.getElementById('installment_box');
                                if (type === 'personal_credit' || type === 'staff_kasbon') {
                                    box.classList.remove('hidden');
                                } else {
                                    box.classList.add('hidden');
                                }
                            }
                        </script>
                        <div class="mt-6 flex justify-end gap-2">
                            <a href="{{ route('kasbons.index') }}" class="bg-gray-200 text-gray-700 font-bold py-2 px-6 rounded hover:bg-gray-300">Batal</a>
                            <button type="submit" class="bg-orange-600 text-white font-bold py-2 px-6 rounded hover:bg-orange-700">Update Kasbon</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
