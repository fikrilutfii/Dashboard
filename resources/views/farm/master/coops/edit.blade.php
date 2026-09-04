<x-farm-layout title="Edit Kandang" subtitle="Perbarui Data Kandang">
    <div style="max-width:680px;margin:0 auto;">
        <div class="ios-card" style="padding:28px;">
            <form action="{{ route('farm.master.coops.update', $coop) }}" method="POST">
                @csrf
                @method('PUT')

                <div style="display:grid;grid-template-columns:2fr 1fr;gap:16px;margin-bottom:20px;">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Nama Kandang <span style="color:#ff3b30;">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $coop->name) }}" required class="ios-input">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Kapasitas (Ekor) <span style="color:#ff3b30;">*</span></label>
                        <input type="number" name="capacity" value="{{ old('capacity', $coop->capacity) }}" min="1" required class="ios-input">
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Lokasi / Blok</label>
                        <input type="text" name="location" value="{{ old('location', $coop->location) }}" class="ios-input">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Status Kandang</label>
                        <select name="status" class="ios-input">
                            <option value="aktif" @selected(old('status', $coop->status) == 'aktif')>Aktif Berjalan</option>
                            <option value="non_aktif" @selected(old('status', $coop->status) == 'non_aktif')>Non-Aktif (Kosong)</option>
                            <option value="pemeliharaan" @selected(old('status', $coop->status) == 'pemeliharaan')>Pemeliharaan / Clean-up</option>
                        </select>
                    </div>
                </div>

                <div style="margin-bottom:24px;">
                    <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Catatan / Spesifikasi Kandang</label>
                    <textarea name="notes" rows="3" class="ios-input" style="resize:vertical;">{{ old('notes', $coop->notes) }}</textarea>
                </div>

                <div style="display:flex;justify-content:flex-end;gap:12px;">
                    <a href="{{ route('farm.master.coops.index') }}" class="ios-btn ios-btn-secondary">Batal</a>
                    <button type="submit" class="ios-btn ios-btn-primary">Update Kandang</button>
                </div>
            </form>
        </div>
    </div>
</x-farm-layout>
