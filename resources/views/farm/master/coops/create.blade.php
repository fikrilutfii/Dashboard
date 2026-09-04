<x-farm-layout title="Tambah Kandang" subtitle="Tambah Data Unit Kandang Ayam">
    <div style="max-width:680px;margin:0 auto;">
        <div class="ios-card" style="padding:28px;">
            <form action="{{ route('farm.master.coops.store') }}" method="POST">
                @csrf

                <div style="display:grid;grid-template-columns:2fr 1fr;gap:16px;margin-bottom:20px;">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Nama Kandang <span style="color:#ff3b30;">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Kandang Alpha 01" class="ios-input">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Kapasitas (Ekor) <span style="color:#ff3b30;">*</span></label>
                        <input type="number" name="capacity" value="{{ old('capacity', 5000) }}" min="1" required class="ios-input">
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Lokasi / Blok</label>
                        <input type="text" name="location" value="{{ old('location') }}" placeholder="Contoh: Blok Timur Kav 3" class="ios-input">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Status Kandang</label>
                        <select name="status" class="ios-input">
                            <option value="aktif" @selected(old('status') == 'aktif')>Aktif Berjalan</option>
                            <option value="non_aktif" @selected(old('status') == 'non_aktif')>Non-Aktif (Kosong)</option>
                            <option value="pemeliharaan" @selected(old('status') == 'pemeliharaan')>Pemeliharaan / Clean-up</option>
                        </select>
                    </div>
                </div>

                <div style="margin-bottom:24px;">
                    <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Catatan / Spesifikasi Kandang</label>
                    <textarea name="notes" rows="3" placeholder="Opsional: Tipe kandang (closed house / open house), fasilitas, dll..." class="ios-input" style="resize:vertical;">{{ old('notes') }}</textarea>
                </div>

                <div style="display:flex;justify-content:flex-end;gap:12px;">
                    <a href="{{ route('farm.master.coops.index') }}" class="ios-btn ios-btn-secondary">Batal</a>
                    <button type="submit" class="ios-btn ios-btn-primary">Simpan Kandang</button>
                </div>
            </form>
        </div>
    </div>
</x-farm-layout>
