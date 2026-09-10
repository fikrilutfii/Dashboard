<x-farm.layout title="Edit Pengeluaran Kas" subtitle="Perbarui data kas keluar operasional RPA">

    <div style="max-width: 700px; margin: 0 auto;">
        <form method="POST" action="{{ route('farm.expenses.update', $expense->id) }}">
            @csrf
            @method('PUT')

            <div class="ios-card" style="padding: 24px; margin-bottom: 24px;">
                <h3 style="font-size: 16px; font-weight: 800; color: #09090b; margin: 0 0 16px;">Informasi Pengeluaran</h3>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Tanggal Pengeluaran <span style="color: #dc2626;">*</span></label>
                        <input type="date" name="expense_date" value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}" class="ios-input" required>
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Kategori Pengeluaran <span style="color: #dc2626;">*</span></label>
                        <select name="category" class="ios-input" required>
                            <option value="operasional_rpa" {{ $expense->category === 'operasional_rpa' ? 'selected' : '' }}>Operasional Produksi RPA</option>
                            <option value="administrasi" {{ $expense->category === 'administrasi' ? 'selected' : '' }}>Administrasi & Umum</option>
                            <option value="armada_umum" {{ $expense->category === 'armada_umum' ? 'selected' : '' }}>Armada (Servis/Pajak Berkala)</option>
                            <option value="lain" {{ $expense->category === 'lain' ? 'selected' : '' }}>Lain-lain</option>
                        </select>
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Jenis / Subkategori</label>
                        <input type="text" name="subcategory" value="{{ old('subcategory', $expense->subcategory) }}" class="ios-input">
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Nominal (Rp) <span style="color: #dc2626;">*</span></label>
                        <input type="number" name="amount" value="{{ old('amount', $expense->amount) }}" step="any" min="1" class="ios-input" required>
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Metode Pembayaran <span style="color: #dc2626;">*</span></label>
                        <select name="payment_method" class="ios-input" required>
                            <option value="Tunai" {{ $expense->payment_method === 'Tunai' ? 'selected' : '' }}>Tunai / Cash</option>
                            <option value="Transfer Bank" {{ $expense->payment_method === 'Transfer Bank' ? 'selected' : '' }}>Transfer Bank</option>
                        </select>
                    </div>

                    <div style="grid-column: 1 / -1;">
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Deskripsi / Keterangan <span style="color: #dc2626;">*</span></label>
                        <input type="text" name="description" value="{{ old('description', $expense->description) }}" class="ios-input" required>
                    </div>

                    <div style="grid-column: 1 / -1;">
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Catatan Tambahan (Opsional)</label>
                        <textarea name="notes" rows="2" class="ios-input">{{ old('notes', $expense->notes) }}</textarea>
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px;">
                <a href="{{ route('farm.expenses.index') }}" class="ios-btn ios-btn-secondary">Batal</a>
                <button type="submit" class="ios-btn ios-btn-primary" style="padding: 12px 28px;">Perbarui Pengeluaran</button>
            </div>
        </form>
    </div>

</x-farm.layout>
