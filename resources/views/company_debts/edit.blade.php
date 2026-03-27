<x-app-layout>
    <x-slot name="header">Edit Pembayaran Perusahaan</x-slot>

    <div class="max-w-2xl">
        <div class="bg-white rounded-2xl border border-zinc-100 shadow-sm p-8">
            <form method="POST" action="{{ route('company-debts.update', $companyDebt) }}" class="space-y-6">
                @csrf @method('PUT')
                <div>
                    <label class="block text-sm font-semibold text-zinc-700 mb-1">Nama Kreditur <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $companyDebt->name) }}" class="w-full border border-zinc-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-400">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-zinc-700 mb-1">Jumlah Pembayaran (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="amount" value="{{ old('amount', $companyDebt->amount) }}" min="0" class="w-full border border-zinc-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-400">
                        @error('amount')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-zinc-700 mb-1">Angsuran Per Bulan (Rp)</label>
                        <input type="number" name="monthly_amount" value="{{ old('monthly_amount', $companyDebt->monthly_amount) }}" min="0" class="w-full border border-zinc-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-400" placeholder="Opsional">
                        @error('monthly_amount')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-zinc-700 mb-1">Jenis Pembayaran <span class="text-red-500">*</span></label>
                        <select name="type" class="w-full border border-zinc-200 rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-indigo-400">
                            <option value="cash" {{ old('type', $companyDebt->type) == 'cash' ? 'selected' : '' }}>Tunai (Cash)</option>
                            <option value="credit" {{ old('type', $companyDebt->type) == 'credit' ? 'selected' : '' }}>Kredit / Cicilan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-zinc-700 mb-1">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="status" class="w-full border-zinc-200 rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-indigo-400" required>
                            <option value="belum_lunas" {{ old('status', $companyDebt->status) == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                            <option value="lunas" {{ old('status', $companyDebt->status) == 'lunas' ? 'selected' : '' }}>Lunas</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-zinc-700 mb-1">Jatuh Tempo</label>
                    <input type="date" name="due_date" value="{{ old('due_date', $companyDebt->due_date?->format('Y-m-d')) }}" class="w-full border border-zinc-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-400">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-zinc-700 mb-1">Keterangan</label>
                    <textarea name="description" rows="3" class="w-full border border-zinc-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-400">{{ old('description', $companyDebt->description) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-zinc-700 mb-1">Entitas Keuangan</label>
                    <select name="entity" class="w-full border border-zinc-200 rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-indigo-400">
                        <option value="percetakan" {{ old('entity', $companyDebt->entity) == 'percetakan' ? 'selected' : '' }}>Percetakan</option>
                        <option value="konfeksi" {{ old('entity', $companyDebt->entity) == 'konfeksi' ? 'selected' : '' }}>Konveksi</option>
                        <option value="pribadi" {{ old('entity', $companyDebt->entity) == 'pribadi' ? 'selected' : '' }}>Pribadi</option>
                    </select>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors">Perbarui</button>
                    <a href="{{ route('company-debts.index') }}" class="px-6 py-2.5 bg-zinc-100 text-zinc-600 text-sm font-semibold rounded-xl hover:bg-zinc-200 transition-colors">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
