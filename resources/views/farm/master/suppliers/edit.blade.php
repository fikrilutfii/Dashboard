<x-farm-layout title="Edit Supplier" subtitle="Perbarui Data Supplier">
    <div style="max-width:680px;margin:0 auto;">
        <div class="ios-card" style="padding:28px;">
            <form action="{{ route('farm.master.suppliers.update', $supplier) }}" method="POST">
                @csrf
                @method('PUT')

                <div style="display:grid;grid-template-columns:2fr 1fr;gap:16px;margin-bottom:20px;">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Nama Supplier / PT <span style="color:#ff3b30;">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $supplier->name) }}" required class="ios-input">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Kategori <span style="color:#ff3b30;">*</span></label>
                        <select name="type" required class="ios-input">
                            <option value="doc" @selected(old('type', $supplier->type) == 'doc')>DOC (Bibit)</option>
                            <option value="pakan" @selected(old('type', $supplier->type) == 'pakan')>Pakan Ayam</option>
                            <option value="obat" @selected(old('type', $supplier->type) == 'obat')>Obat / Vaksin</option>
                            <option value="alat" @selected(old('type', $supplier->type) == 'alat')>Peralatan</option>
                            <option value="lain" @selected(old('type', $supplier->type) == 'lain')>Lainnya</option>
                        </select>
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">No. Telepon / WhatsApp</label>
                        <input type="text" name="phone" value="{{ old('phone', $supplier->phone) }}" class="ios-input">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Contact Person (CP)</label>
                        <input type="text" name="contact_person" value="{{ old('contact_person', $supplier->contact_person) }}" class="ios-input">
                    </div>
                </div>

                <div style="margin-bottom:20px;">
                    <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Alamat Lengkap</label>
                    <textarea name="address" rows="3" class="ios-input" style="resize:vertical;">{{ old('address', $supplier->address) }}</textarea>
                </div>

                <div style="margin-bottom:24px;">
                    <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Catatan Tambahan</label>
                    <input type="text" name="notes" value="{{ old('notes', $supplier->notes) }}" class="ios-input">
                </div>

                <div style="display:flex;justify-content:flex-end;gap:12px;">
                    <a href="{{ route('farm.master.suppliers.index') }}" class="ios-btn ios-btn-secondary">Batal</a>
                    <button type="submit" class="ios-btn ios-btn-primary">Update Supplier</button>
                </div>
            </form>
        </div>
    </div>
</x-farm-layout>
