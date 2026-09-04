<x-farm-layout title="Edit Transportasi" subtitle="Perbarui Data Transportasi">
    <div style="max-width:680px;margin:0 auto;">
        <div class="ios-card" style="padding:28px;">
            <form action="{{ route('farm.transportation.update', $transportation) }}" method="POST">
                @csrf
                @method('PUT')

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Tanggal Transportasi <span style="color:#ff3b30;">*</span></label>
                        <input type="date" name="transport_date" value="{{ old('transport_date', $transportation->transport_date) }}" required class="ios-input">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Tipe Transportasi <span style="color:#ff3b30;">*</span></label>
                        <select name="type" required class="ios-input">
                            <option value="keluar" @selected(old('type', $transportation->type) == 'keluar')>Keluar (Pengiriman Panen Ayam)</option>
                            <option value="masuk" @selected(old('type', $transportation->type) == 'masuk')>Masuk (Pengiriman Bibit DOC / Pakan)</option>
                        </select>
                    </div>
                </div>

                <div style="margin-bottom:20px;">
                    <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Deskripsi / Muatan <span style="color:#ff3b30;">*</span></label>
                    <input type="text" name="description" value="{{ old('description', $transportation->description) }}" required class="ios-input">
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Tujuan / Asal Pengiriman</label>
                        <input type="text" name="destination" value="{{ old('destination', $transportation->destination) }}" class="ios-input">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Biaya Transportasi (Rp) <span style="color:#ff3b30;">*</span></label>
                        <input type="number" name="amount" value="{{ old('amount', $transportation->amount) }}" required min="0" class="ios-input">
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:20px;">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Nama Sopir / Driver</label>
                        <input type="text" name="driver" value="{{ old('driver', $transportation->driver) }}" class="ios-input">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Plat Nomor Kendaraan</label>
                        <input type="text" name="vehicle_plate" value="{{ old('vehicle_plate', $transportation->vehicle_plate) }}" class="ios-input">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Status</label>
                        <select name="status" class="ios-input">
                            <option value="selesai" @selected(old('status', $transportation->status) == 'selesai')>Selesai</option>
                            <option value="proses" @selected(old('status', $transportation->status) == 'proses')>Dalam Proses</option>
                        </select>
                    </div>
                </div>

                <div style="margin-bottom:24px;">
                    <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Catatan</label>
                    <textarea name="notes" rows="3" class="ios-input" style="resize:vertical;">{{ old('notes', $transportation->notes) }}</textarea>
                </div>

                <div style="display:flex;justify-content:flex-end;gap:12px;">
                    <a href="{{ route('farm.transportation.index') }}" class="ios-btn ios-btn-secondary">Batal</a>
                    <button type="submit" class="ios-btn ios-btn-primary">Update Transportasi</button>
                </div>
            </form>
        </div>
    </div>
</x-farm-layout>
