<x-app-layout>
    <x-slot name="header">
        Edit {{ $expense->type === 'bahan' ? 'Belanja Bahan' : 'Pengeluaran Manual' }}
    </x-slot>

    <div class="max-w-3xl mx-auto space-y-6 flex flex-col items-center">
        @if ($errors->any())
            <div class="bg-red-50 text-red-600 p-4 rounded-xl border border-red-100 w-full">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-zinc-100 shadow-sm overflow-hidden w-full">
            <div class="p-6">
                <form action="{{ route('expenses.update', $expense) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Common Fields -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-zinc-700 mb-1">Tanggal <span class="text-red-500">*</span></label>
                            <input type="date" name="date" value="{{ old('date', $expense->date->format('Y-m-d')) }}" class="w-full border border-zinc-200 rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400 transition-colors" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-zinc-700 mb-1">Entitas / Divisi</label>
                            <select name="entity" class="w-full border border-zinc-200 rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-indigo-400">
                                <option value="" {{ old('entity', $expense->entity) == '' ? 'selected' : '' }}>-- Tidak Spesifik --</option>
                                <option value="Percetakan" {{ old('entity', $expense->entity) == 'Percetakan' ? 'selected' : '' }}>Percetakan</option>
                                <option value="Konveksi" {{ old('entity', $expense->entity) == 'Konveksi' ? 'selected' : '' }}>Konveksi</option>
                            </select>
                            <p class="text-xs text-zinc-500 mt-1.5">Kosongkan jika bukan beban untuk satu divisi spesifik</p>
                        </div>
                    </div>

                    <hr class="border-zinc-100">

                    @if($expense->type === 'manual')
                        <!-- Fields for Manual Expense -->
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-semibold text-zinc-700 mb-1">Kategori Pengeluaran <span class="text-red-500">*</span></label>
                                <input type="text" name="category" value="{{ old('category', $expense->category) }}" placeholder="Contoh: Transportasi, Listrik, Konsumsi" class="w-full border border-zinc-200 rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400 transition-colors" required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-zinc-700 mb-1">Total Nominal <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <span class="text-zinc-500 font-medium">Rp</span>
                                    </div>
                                    <input type="number" name="amount" value="{{ old('amount', $expense->amount) }}" min="0" class="w-full pl-11 border border-zinc-200 rounded-xl px-4 py-2.5 bg-white font-medium focus:outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400 transition-colors" placeholder="0" required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-zinc-700 mb-1">Deskripsi & Keterangan Tambahan <span class="text-red-500">*</span></label>
                                <textarea name="description" rows="3" class="w-full border border-zinc-200 rounded-xl px-4 py-3 text-sm bg-white focus:outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400 transition-colors" placeholder="Jelaskan detail pengeluaran ini..." required>{{ old('description', $expense->description) }}</textarea>
                            </div>
                        </div>
                    @else
                        <!-- Fields for Belanja Bahan -->
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-zinc-700 mb-1">Nama Suplier / Toko <span class="text-red-500">*</span></label>
                                    <input type="text" name="supplier_name" value="{{ old('supplier_name', $expense->supplier_name) }}" placeholder="Contoh: Toko Kertas Abadi" class="w-full border border-zinc-200 rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-indigo-400 transition-colors" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-zinc-700 mb-1">Barang / Bahan <span class="text-red-500">*</span></label>
                                    <input type="text" name="item_name" value="{{ old('item_name', $expense->item_name) }}" placeholder="Contoh: Kertas Artpaper 120gr" class="w-full border border-zinc-200 rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-indigo-400 transition-colors" required>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4" x-data="{ qty: {{ old('quantity', $expense->quantity ?? 1) }}, price: {{ old('unit_price', rtrim(rtrim($expense->unit_price, '0'), '.')) }} }">
                                <div>
                                    <label class="block text-sm font-semibold text-zinc-700 mb-1">Kuantitas</label>
                                    <input type="number" name="quantity" x-model="qty" min="1" class="w-full border border-zinc-200 rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-indigo-400">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-zinc-700 mb-1">Harga Satuan</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-zinc-500 font-medium text-sm">Rp</span>
                                        </div>
                                        <input type="number" name="unit_price" x-model="price" min="0" class="w-full pl-10 border border-zinc-200 rounded-xl px-4 py-2.5 bg-white font-medium focus:outline-none focus:border-indigo-400" placeholder="0">
                                    </div>
                                </div>
                                
                                <div class="col-span-2 pt-2">
                                    <label class="block text-sm font-semibold text-zinc-700 mb-1">Total Belanja <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <span class="text-zinc-500 font-medium">Rp</span>
                                        </div>
                                        <!-- Keep user's explicitly typed total amount if they prefer, else calculate -->
                                        <input type="number" name="amount" min="0" value="{{ old('amount', rtrim(rtrim($expense->amount, '0'), '.')) }}" x-bind:value="(qty * price) > 0 ? (qty * price) : '{{ old('amount', rtrim(rtrim($expense->amount, '0'), '.')) }}'" class="w-full pl-11 border border-zinc-200 rounded-xl px-4 py-3 bg-zinc-50 text-indigo-700 font-bold text-lg focus:outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400 transition-colors" placeholder="0" required>
                                    </div>
                                    <p class="text-xs text-zinc-500 mt-1 text-right">Anda dapat mengisi total belanja secara manual langsung di sini jika tidak spesifik.</p>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-zinc-700 mb-1">Catatan Tambahan (Boleh Kosong)</label>
                                <textarea name="description" rows="2" class="w-full border border-zinc-200 rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400 transition-colors" placeholder="Catatan opsional...">{{ old('description', $expense->description) }}</textarea>
                            </div>
                        </div>
                    @endif

                    <!-- Submit & Cancel Actions -->
                    <div class="pt-6 border-t border-zinc-100 flex items-center gap-3 justify-end">
                        <a href="{{ route('expenses.index') }}" class="px-5 py-2.5 text-sm font-semibold text-zinc-600 bg-white border border-zinc-200 rounded-xl hover:bg-zinc-50 transition-colors">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 shadow-sm shadow-indigo-200 transition-all flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>
                            Perbarui Pengeluaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
