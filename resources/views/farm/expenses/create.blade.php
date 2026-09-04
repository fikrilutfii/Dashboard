<x-farm-layout title="Catat Pengeluaran" subtitle="Input Pengeluaran Operasional / Pembelian">
    <div style="max-width:680px;margin:0 auto;">
        <div class="ios-card" style="padding:28px;">
            <form action="{{ route('farm.expenses.store') }}" method="POST">
                @csrf

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Tanggal Pengeluaran <span style="color:#ff3b30;">*</span></label>
                        <input type="date" name="expense_date" value="{{ old('expense_date', date('Y-m-d')) }}" required class="ios-input">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Kategori Pengeluaran <span style="color:#ff3b30;">*</span></label>
                        <select name="category" required class="ios-input">
                            <option value="pakan" @selected(old('category') == 'pakan')>Pakan Ayam</option>
                            <option value="doc" @selected(old('category') == 'doc')>Bibit DOC</option>
                            <option value="obat" @selected(old('category') == 'obat')>Obat & Vaksin</option>
                            <option value="operasional" @selected(old('category') == 'operasional')>Operasional Kandang / Listrik / Sekam</option>
                            <option value="peralatan" @selected(old('category') == 'peralatan')>Peralatan & Maintenance</option>
                            <option value="lainnya" @selected(old('category') == 'lainnya')>Lain-lain</option>
                        </select>
                    </div>
                </div>

                <div style="margin-bottom:20px;">
                    <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Keterangan / Deskripsi <span style="color:#ff3b30;">*</span></label>
                    <input type="text" name="description" value="{{ old('description') }}" required placeholder="Contoh: Pembelian Pakan BR-1 50 Sak / Pembelian Sekam 100 Karung" class="ios-input">
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Jumlah Nominal (Rp) <span style="color:#ff3b30;">*</span></label>
                        <input type="number" name="amount" value="{{ old('amount') }}" required min="0" placeholder="0" class="ios-input">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Metode Pembayaran</label>
                        <select name="payment_method" class="ios-input">
                            <option value="transfer" @selected(old('payment_method') == 'transfer')>Transfer Bank</option>
                            <option value="cash" @selected(old('payment_method') == 'cash')>Tunai / Cash</option>
                        </select>
                    </div>
                </div>

                <div style="margin-bottom:20px;">
                    <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Supplier (Opsional)</label>
                    <select name="farm_supplier_id" class="ios-input">
                        <option value="">-- Tanpa Supplier / Pembelian Bebas --</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}" @selected(old('farm_supplier_id') == $s->id)>{{ $s->name }} ({{ ucfirst($s->type) }})</option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom:24px;">
                    <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Catatan Tambahan</label>
                    <textarea name="notes" rows="3" placeholder="Nomor nota, rincian item, atau catatan lainnya..." class="ios-input" style="resize:vertical;">{{ old('notes') }}</textarea>
                </div>

                <div style="display:flex;justify-content:flex-end;gap:12px;">
                    <a href="{{ route('farm.expenses.index') }}" class="ios-btn ios-btn-secondary">Batal</a>
                    <button type="submit" class="ios-btn ios-btn-primary">Simpan Pengeluaran</button>
                </div>
            </form>
        </div>
    </div>
</x-farm-layout>
