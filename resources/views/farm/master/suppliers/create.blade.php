<x-farm-layout title="Tambah Supplier" subtitle="Tambah Data Supplier DOC/Pakan/Obat">
    <div style="max-width:680px;margin:0 auto;">
        <div class="ios-card" style="padding:28px;">
            <form action="{{ route('farm.master.suppliers.store') }}" method="POST">
                @csrf

                <div style="display:grid;grid-template-columns:2fr 1fr;gap:16px;margin-bottom:20px;">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Nama Supplier / PT <span style="color:#ff3b30;">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: PT Japfa / Pokphand / Japfa Comfeed" class="ios-input">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Kategori <span style="color:#ff3b30;">*</span></label>
                        <select name="type" required class="ios-input">
                            <option value="doc" @selected(old('type') == 'doc')>DOC (Bibit)</option>
                            <option value="pakan" @selected(old('type') == 'pakan')>Pakan Ayam</option>
                            <option value="obat" @selected(old('type') == 'obat')>Obat / Vaksin</option>
                            <option value="alat" @selected(old('type') == 'alat')>Peralatan</option>
                            <option value="lain" @selected(old('type') == 'lain')>Lainnya</option>
                        </select>
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">No. Telepon / WhatsApp</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="081234567890" class="ios-input">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Contact Person (CP)</label>
                        <input type="text" name="contact_person" value="{{ old('contact_person') }}" placeholder="Nama Sales / Manager" class="ios-input">
                    </div>
                </div>

                <div style="margin-bottom:20px;">
                    <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Alamat Lengkap</label>
                    <textarea name="address" rows="3" placeholder="Alamat kantor / gudang supplier..." class="ios-input" style="resize:vertical;">{{ old('address') }}</textarea>
                </div>

                <div style="margin-bottom:24px;">
                    <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Catatan Tambahan</label>
                    <input type="text" name="notes" value="{{ old('notes') }}" placeholder="Opsional" class="ios-input">
                </div>

                <div style="display:flex;justify-content:flex-end;gap:12px;">
                    <a href="{{ route('farm.master.suppliers.index') }}" class="ios-btn ios-btn-secondary">Batal</a>
                    <button type="submit" class="ios-btn ios-btn-primary">Simpan Supplier</button>
                </div>
            </form>
        </div>
    </div>
</x-farm-layout>
