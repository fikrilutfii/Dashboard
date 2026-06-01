<x-app-layout>
    <x-slot name="header">
        Catat {{ $type === 'bahan' ? 'Belanja Bahan' : 'Pengeluaran Manual' }}
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
                <form action="{{ route('expenses.store') }}" method="POST" class="space-y-6" x-data="{ method: '{{ old('payment_method', 'cash') }}' }">
                    @csrf
                    <input type="hidden" name="type" value="{{ $type }}">

                    <!-- Common Fields -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-zinc-700 mb-1">Tanggal <span class="text-red-500">*</span></label>
                            <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" class="w-full border border-zinc-200 rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400 transition-colors" required>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-zinc-700 mb-1">Opsi Bayar <span class="text-red-500">*</span></label>
                            <div class="flex p-1 bg-zinc-100 rounded-xl">
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="payment_method" value="cash" x-model="method" class="hidden">
                                    <div class="py-1.5 text-center rounded-lg text-xs font-bold transition-all"
                                         :class="method === 'cash' ? 'bg-white text-primary-600 shadow-sm' : 'text-zinc-400'">
                                        CASH
                                    </div>
                                </label>
                                <label class="flex-1 cursor-pointer">
                                    <input type="radio" name="payment_method" value="credit" x-model="method" class="hidden">
                                    <div class="py-1.5 text-center rounded-lg text-xs font-bold transition-all"
                                         :class="method === 'credit' ? 'bg-white text-primary-600 shadow-sm' : 'text-zinc-400'">
                                        CREDIT
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <hr class="border-zinc-100">

                    @if($type === 'manual')
                        <!-- Fields for Manual Expense -->
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-semibold text-zinc-700 mb-1">Kategori Pengeluaran <span class="text-red-500">*</span></label>
                                <input type="text" name="category" value="{{ old('category') }}" placeholder="Contoh: Transportasi, Listrik, Konsumsi" class="w-full border border-zinc-200 rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400 transition-colors" required>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-zinc-700 mb-1">Total Nominal <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <span class="text-zinc-500 font-medium">Rp</span>
                                    </div>
                                    <input type="number" name="amount" value="{{ old('amount') }}" min="0" class="w-full pl-11 border border-zinc-200 rounded-xl px-4 py-2.5 bg-white font-medium focus:outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400 transition-colors" placeholder="0" required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-zinc-700 mb-1">Deskripsi & Keterangan Tambahan <span class="text-red-500">*</span></label>
                                <textarea name="description" rows="3" class="w-full border border-zinc-200 rounded-xl px-4 py-3 text-sm bg-white focus:outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400 transition-colors" placeholder="Jelaskan detail pengeluaran ini..." required>{{ old('description') }}</textarea>
                            </div>
                        </div>
                    @else
                        <!-- Fields for Belanja Bahan -->
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-zinc-700 mb-1">Nama Suplier / Toko <span class="text-red-500">*</span></label>
                                    <input type="text" name="supplier_name" value="{{ old('supplier_name') }}" placeholder="Contoh: Toko Kertas Abadi" class="w-full border border-zinc-200 rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-indigo-400 transition-colors" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-zinc-700 mb-1">Barang / Bahan <span class="text-red-500">*</span></label>
                                    <input type="text" name="item_name" value="{{ old('item_name') }}" placeholder="Contoh: Kertas Artpaper 120gr" class="w-full border border-zinc-200 rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-indigo-400 transition-colors" required>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4" x-data="{ qty: {{ old('quantity', 1) }}, price: {{ old('unit_price', 0) }} }">
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
                                        <input type="number" name="amount" min="0" :value="(qty * price) > 0 ? (qty * price) : '{{ old('amount') }}'" class="w-full pl-11 border border-zinc-200 rounded-xl px-4 py-3 bg-zinc-50 text-indigo-700 font-bold text-lg focus:outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400 transition-colors" placeholder="0" required>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-zinc-700 mb-1">Catatan Tambahan (Boleh Kosong)</label>
                                <textarea name="description" rows="2" class="w-full border border-zinc-200 rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-400 transition-colors" placeholder="Catatan opsional...">{{ old('description') }}</textarea>
                            </div>
                        </div>
                    @endif

                    <!-- Installment Settings -->
                    <div x-show="method === 'credit'" x-transition class="bg-primary-50/50 p-6 rounded-[1.5rem] border border-primary-100 space-y-4">
                        <div class="flex items-center gap-2 mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-primary-600">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-sm font-black text-primary-900 uppercase tracking-widest">Pengaturan Cicilan</span>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-zinc-700 mb-1">Jatuh Tempo Pertama</label>
                                <input type="date" name="due_date" value="{{ old('due_date', date('Y-m-d', strtotime('+1 month'))) }}" class="w-full border border-zinc-200 rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-primary-400">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-zinc-700 mb-1">Lama Cicilan (Bulan)</label>
                                <input type="number" name="tenure" value="{{ old('tenure', 12) }}" min="1" class="w-full border border-zinc-200 rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-primary-400" placeholder="Contoh: 12">
                            </div>
                        </div>
                    </div>

                    <hr class="border-zinc-100">

                    <!-- Submit & Cancel Actions -->
                    <div class="pt-6 border-t border-zinc-100 flex items-center gap-3 justify-end">
                        <a href="{{ route('expenses.index') }}" class="px-5 py-2.5 text-sm font-semibold text-zinc-600 bg-white border border-zinc-200 rounded-xl hover:bg-zinc-50 transition-colors">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2.5 text-sm font-semibold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 shadow-sm shadow-indigo-200 transition-all flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0z" /></svg>
                            Simpan Pengeluaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
