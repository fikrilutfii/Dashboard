<x-app-layout>
    <x-slot name="header">Tambah Pembayaran Perusahaan</x-slot>

    <div class="max-w-2xl">
        <div class="bg-white rounded-2xl border border-zinc-100 shadow-sm p-8">
            <form method="POST" action="{{ route('company-debts.store') }}" class="space-y-6">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-zinc-700 mb-1">Nama Kreditur <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full border border-zinc-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-100" placeholder="Nama pihak / perusahaan pemberi pinjaman">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-zinc-700 mb-1">Jumlah Pembayaran (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="amount" value="{{ old('amount') }}" min="0" class="w-full border border-zinc-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-100" placeholder="0">
                        @error('amount')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-zinc-700 mb-1">Angsuran Per Bulan (Rp)</label>
                        <input type="number" name="monthly_amount" value="{{ old('monthly_amount') }}" min="0" class="w-full border border-zinc-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-100" placeholder="Opsional">
                        @error('monthly_amount')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-zinc-700 mb-1">Jenis Pembayaran <span class="text-red-500">*</span></label>
                        <select name="type" class="w-full border border-zinc-200 rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-indigo-400">
                            <option value="cash" {{ old('type') == 'cash' ? 'selected' : '' }}>Tunai</option>
                            <option value="credit" {{ old('type') == 'credit' ? 'selected' : '' }}>Cicilan</option>
                        </select>
                        @error('type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-zinc-700 mb-1">Jatuh Tempo</label>
                        <input type="date" name="due_date" value="{{ old('due_date') }}" class="w-full border border-zinc-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-400">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-zinc-700 mb-1">Keterangan</label>
                    <textarea name="description" rows="3" class="w-full border border-zinc-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-400 focus:ring-1 focus:ring-indigo-100" placeholder="Keterangan tambahan (opsional)">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-zinc-700 mb-1">Entitas Keuangan</label>
                    <select name="entity" class="w-full border border-zinc-200 rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-indigo-400">
                        <option value="percetakan" {{ (old('entity') ?? session('division')) == 'percetakan' ? 'selected' : '' }}>Percetakan</option>
                        <option value="konfeksi" {{ (old('entity') ?? session('division')) == 'konfeksi' ? 'selected' : '' }}>Konveksi</option>
                        <option value="pribadi" {{ old('entity') == 'pribadi' ? 'selected' : '' }}>Pribadi</option>
                    </select>
                    <p class="text-[10px] text-zinc-400 mt-1">*Pilih Pribadi jika ini adalah dana pribadi owner.</p>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors">Simpan</button>
                    <a href="{{ route('company-debts.index') }}" class="px-6 py-2.5 bg-zinc-100 text-zinc-600 text-sm font-semibold rounded-xl hover:bg-zinc-200 transition-colors">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
