<x-farm-layout title="Edit Customer" subtitle="Perbarui Data Customer">
    <div style="max-width:680px;margin:0 auto;">
        <div class="ios-card" style="padding:28px;">
            <form action="{{ route('farm.master.customers.update', $customer) }}" method="POST">
                @csrf
                @method('PUT')

                <div style="margin-bottom:20px;">
                    <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Nama Customer / Perusahaan <span style="color:#ff3b30;">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $customer->name) }}" required class="ios-input">
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">No. Telepon / WhatsApp</label>
                        <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}" class="ios-input">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Contact Person (CP)</label>
                        <input type="text" name="contact_person" value="{{ old('contact_person', $customer->contact_person) }}" class="ios-input">
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Kota</label>
                        <input type="text" name="city" value="{{ old('city', $customer->city) }}" class="ios-input">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Catatan Tambahan</label>
                        <input type="text" name="notes" value="{{ old('notes', $customer->notes) }}" class="ios-input">
                    </div>
                </div>

                <div style="margin-bottom:24px;">
                    <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Alamat Lengkap</label>
                    <textarea name="address" rows="3" class="ios-input" style="resize:vertical;">{{ old('address', $customer->address) }}</textarea>
                </div>

                <div style="display:flex;justify-content:flex-end;gap:12px;">
                    <a href="{{ route('farm.master.customers.index') }}" class="ios-btn ios-btn-secondary">Batal</a>
                    <button type="submit" class="ios-btn ios-btn-primary">Update Customer</button>
                </div>
            </form>
        </div>
    </div>
</x-farm-layout>
