<x-farm-layout title="Edit Log Operasional" subtitle="Perbarui Catatan Log Harian">
    <div style="max-width:720px;margin:0 auto;">
        <div class="ios-card" style="padding:28px;">
            <form action="{{ route('farm.operational.update', $log) }}" method="POST">
                @csrf
                @method('PUT')

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Pilih Kandang <span style="color:#ff3b30;">*</span></label>
                        <select name="farm_coop_id" required class="ios-input">
                            @foreach($coops as $c)
                                <option value="{{ $c->id }}" @selected(old('farm_coop_id', $log->farm_coop_id) == $c->id)>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Tanggal Log <span style="color:#ff3b30;">*</span></label>
                        <input type="date" name="log_date" value="{{ old('log_date', $log->log_date) }}" required class="ios-input">
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:20px;">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Populasi Ayam (Ekor) <span style="color:#ff3b30;">*</span></label>
                        <input type="number" name="population" value="{{ old('population', $log->population) }}" required min="0" class="ios-input">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Mortalitas / Kematian <span style="color:#ff3b30;">*</span></label>
                        <input type="number" name="mortality" value="{{ old('mortality', $log->mortality) }}" required min="0" class="ios-input">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Pakan Digunakan (Kg) <span style="color:#ff3b30;">*</span></label>
                        <input type="number" step="0.1" name="feed_kg" value="{{ old('feed_kg', $log->feed_kg) }}" required min="0" class="ios-input">
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Rata-rata Berat Ayam (Kg)</label>
                        <input type="number" step="0.01" name="avg_weight" value="{{ old('avg_weight', $log->avg_weight) }}" min="0" class="ios-input">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Umur Ayam (Hari)</label>
                        <input type="number" name="age_days" value="{{ old('age_days', $log->age_days) }}" min="0" class="ios-input">
                    </div>
                </div>

                <div style="margin-bottom:20px;">
                    <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Vaksin / Vitamin Diberikan</label>
                    <input type="text" name="vaccine_notes" value="{{ old('vaccine_notes', $log->vaccine_notes) }}" class="ios-input">
                </div>

                <div style="margin-bottom:24px;">
                    <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Catatan Tambahan</label>
                    <textarea name="notes" rows="3" class="ios-input" style="resize:vertical;">{{ old('notes', $log->notes) }}</textarea>
                </div>

                <div style="display:flex;justify-content:flex-end;gap:12px;">
                    <a href="{{ route('farm.operational.index') }}" class="ios-btn ios-btn-secondary">Batal</a>
                    <button type="submit" class="ios-btn ios-btn-primary">Update Log</button>
                </div>
            </form>
        </div>
    </div>
</x-farm-layout>
