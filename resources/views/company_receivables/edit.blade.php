<x-app-layout>
    <x-slot name="header">Edit Tagihan Perusahaan</x-slot>

    <div class="max-w-2xl">
        <div class="bg-white rounded-2xl border border-zinc-100 shadow-sm p-8">
            <form method="POST" action="{{ route('company-receivables.update', $companyReceivable) }}" class="space-y-6">
                @csrf @method('PUT')
                <div>
                    <label class="block text-sm font-semibold text-zinc-700 mb-1">Nama Debitur <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $companyReceivable->name) }}" class="w-full border border-zinc-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-400">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-zinc-700 mb-1">Total Tagihan (Rp)</label>
                        <input type="number" name="total_amount" value="{{ old('total_amount', $companyReceivable->total_amount) }}" min="0" class="w-full border border-zinc-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-400">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-zinc-700 mb-1">Sisa Tagihan (Rp)</label>
                        <input type="number" name="remaining_amount" value="{{ old('remaining_amount', $companyReceivable->remaining_amount) }}" min="0" class="w-full border border-zinc-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-400">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-zinc-700 mb-1">Jenis <span class="text-red-500">*</span></label>
                        <select name="type" class="w-full border border-zinc-200 rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none">
                            <option value="cash" {{ old('type', $companyReceivable->type) == 'cash' ? 'selected' : '' }}>Tunai</option>
                            <option value="installment" {{ old('type', $companyReceivable->type) == 'installment' ? 'selected' : '' }}>Cicilan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-zinc-700 mb-1">Status</label>
                        <select name="status" id="status" class="w-full border rounded px-3 py-2" required>
                            <option value="unpaid" {{ old('status', $companyReceivable->status) == 'unpaid' ? 'selected' : '' }}>Belum Lunas</option>
                            <option value="sebagian" {{ old('status', $companyReceivable->status) == 'sebagian' ? 'selected' : '' }}>Sebagian</option>
                            <option value="paid" {{ old('status', $companyReceivable->status) == 'paid' ? 'selected' : '' }}>Lunas</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-zinc-700 mb-1">Keterangan</label>
                    <textarea name="description" rows="3" class="w-full border border-zinc-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-400">{{ old('description', $companyReceivable->description) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-zinc-700 mb-1">Entitas Keuangan</label>
                    <select name="entity" class="w-full border border-zinc-200 rounded-xl px-4 py-2.5 text-sm bg-white focus:outline-none focus:border-indigo-400">
                        <option value="percetakan" {{ old('entity', $companyReceivable->entity) == 'percetakan' ? 'selected' : '' }}>Percetakan</option>
                        <option value="konfeksi" {{ old('entity', $companyReceivable->entity) == 'konfeksi' ? 'selected' : '' }}>Konveksi</option>
                        <option value="pribadi" {{ old('entity', $companyReceivable->entity) == 'pribadi' ? 'selected' : '' }}>Pribadi</option>
                    </select>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors">Perbarui</button>
                    <a href="{{ route('company-receivables.index') }}" class="px-6 py-2.5 bg-zinc-100 text-zinc-600 text-sm font-semibold rounded-xl hover:bg-zinc-200 transition-colors">Batal</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
