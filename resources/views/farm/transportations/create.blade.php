<x-farm.layout title="Input Log Pengiriman" subtitle="Catat waktu keberangkatan, armada, dan kondisi sampai barang">

    <div style="max-width: 800px; margin: 0 auto;">
        <form method="POST" action="{{ route('farm.transportations.store') }}">
            @csrf

            <div class="ios-card" style="padding: 24px; margin-bottom: 20px;">
                <h3 style="font-size: 16px; font-weight: 800; color: #09090b; margin: 0 0 16px;">Informasi Pengiriman & Waktu (SLA)</h3>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Tanggal Pengiriman <span style="color: #dc2626;">*</span></label>
                        <input type="date" name="transport_date" value="{{ date('Y-m-d') }}" class="ios-input" required>
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Faktur Penjualan (Terkait)</label>
                        <select name="farm_invoice_id" class="ios-input">
                            <option value="">-- Tidak Terhubung / Pengiriman Khusus --</option>
                            @foreach($invoices as $inv)
                            <option value="{{ $inv->id }}">{{ $inv->invoice_number }} - {{ $inv->customer->name ?? '' }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Kendaraan Armada</label>
                        <select name="farm_vehicle_id" class="ios-input" id="vehicleSelect" onchange="autoFillDriver(this)">
                            <option value="">-- Pilih Kendaraan --</option>
                            @foreach($vehicles as $v)
                            <option value="{{ $v->id }}" data-driver="{{ $v->default_driver }}">{{ $v->plate_number }} ({{ $v->vehicle_type ?? 'Mobil' }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Nama Supir <span style="color: #dc2626;">*</span></label>
                        <input type="text" name="driver_name" id="driverInput" placeholder="Nama supir pengantar" class="ios-input" required>
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Tujuan / Alamat Pengantaran</label>
                        <input type="text" name="destination" placeholder="Pasar / Restoran / Agen tujuan" class="ios-input">
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Waktu Berangkat (Jam)</label>
                        <input type="time" name="departure_time" class="ios-input">
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Waktu Tiba (Jam)</label>
                        <input type="time" name="arrival_time" class="ios-input">
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Kondisi Barang Saat Sampai <span style="color: #dc2626;">*</span></label>
                        <select name="delivery_status" class="ios-input" required>
                            <option value="baik">Baik (Diterima Penuh)</option>
                            <option value="sedang_dikirim">Sedang Dalam Perjalanan</option>
                            <option value="komplain_sebagian">Sebagian Komplain</option>
                            <option value="komplain_penuh">Komplain Penuh / Retur</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Biaya Operasional Kendaraan Card -->
            <div class="ios-card" style="padding: 24px; margin-bottom: 24px;">
                <h3 style="font-size: 16px; font-weight: 800; color: #09090b; margin: 0 0 16px;">Biaya Operasional Pengiriman</h3>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Biaya BBM (Rp)</label>
                        <input type="number" name="bbm_cost" value="0" step="any" min="0" class="ios-input">
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Biaya Tol / Parkir (Rp)</label>
                        <input type="number" name="toll_cost" value="0" step="any" min="0" class="ios-input">
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Biaya Lain-lain (Rp)</label>
                        <input type="number" name="other_cost" value="0" step="any" min="0" class="ios-input">
                    </div>

                    <div style="grid-column: 1 / -1;">
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Catatan Pengiriman</label>
                        <textarea name="notes" rows="2" class="ios-input" placeholder="Keterangan rute atau catatan serah terima..."></textarea>
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px;">
                <a href="{{ route('farm.transportations.index') }}" class="ios-btn ios-btn-secondary">Batal</a>
                <button type="submit" class="ios-btn ios-btn-primary" style="padding: 12px 28px;">Simpan Log Transportasi</button>
            </div>
        </form>
    </div>

    <script>
        function autoFillDriver(selectEl) {
            const opt = selectEl.options[selectEl.selectedIndex];
            const driver = opt.dataset.driver;
            if (driver) {
                document.getElementById('driverInput').value = driver;
            }
        }
    </script>

</x-farm.layout>
