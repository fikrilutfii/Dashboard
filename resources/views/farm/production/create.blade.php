<x-farm.layout title="Input Batch Produksi RPA" subtitle="Catat penerimaan ayam hidup, HPP supplier, hasil potong/parting & susut rendemen">

    <div style="max-width: 960px; margin: 0 auto;">
        <form method="POST" action="{{ route('farm.production.store') }}" x-data="productionForm()">
            @csrf

            <!-- 1. Intake Ayam Hidup Card -->
            <div class="ios-card" style="padding: 24px; margin-bottom: 20px;">
                <h3 style="font-size: 16px; font-weight: 800; color: #09090b; margin: 0 0 16px;">1. Penerimaan Ayam Hidup & HPP Bahan Baku</h3>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Nomor Batch <span style="color: #dc2626;">*</span></label>
                        <input type="text" name="batch_number" value="{{ old('batch_number', $generatedBatchNumber) }}" class="ios-input" required>
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Tanggal Produksi <span style="color: #dc2626;">*</span></label>
                        <input type="date" name="production_date" value="{{ old('production_date', date('Y-m-d')) }}" class="ios-input" required>
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Supplier Ayam Hidup / Peternak</label>
                        <select name="farm_supplier_id" class="ios-input">
                            <option value="">-- Pilih Supplier --</option>
                            @foreach($suppliers as $s)
                            <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->farm_location ?? 'Kandang' }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Jumlah Ayam Hidup (Ekor) <span style="color: #dc2626;">*</span></label>
                        <input type="number" name="live_birds_count" x-model.number="liveCount" min="1" class="ios-input" placeholder="Contoh: 1000" required>
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Total Timbang Hidup (Kg) <span style="color: #dc2626;">*</span></label>
                        <input type="number" name="live_birds_weight_kg" x-model.number="liveWeight" step="any" min="0.1" class="ios-input" placeholder="Contoh: 1800.5" required>
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Harga Beli / Kg (HPP Rp) <span style="color: #dc2626;">*</span></label>
                        <input type="number" name="buy_price_per_kg" x-model.number="buyPrice" step="any" min="0" class="ios-input" placeholder="Contoh: 21500" required>
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Status Bayar ke Supplier</label>
                        <select name="supplier_payment_status" class="ios-input">
                            <option value="belum_lunas">Belum Lunas (Tempo)</option>
                            <option value="lunas">Lunas (Tunai/Transfer)</option>
                        </select>
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Total Nilai HPP Bahan Baku</label>
                        <div style="padding: 11px 16px; background: rgba(0,0,0,0.03); border-radius: 14px; font-weight: 800; font-size: 15px; color: #09090b;">
                            <span x-text="formatRupiah(liveWeight * buyPrice)"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. DOA / Ayam Mati Saat Penerimaan -->
            <div class="ios-card" style="padding: 24px; margin-bottom: 20px;">
                <h3 style="font-size: 16px; font-weight: 800; color: #09090b; margin: 0 0 16px;">2. Ayam Mati / Reject Saat Kedatangan (DOA - Death On Arrival)</h3>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Jumlah Mati (Ekor)</label>
                        <input type="number" name="doa_count" value="0" min="0" class="ios-input" placeholder="0 jika tidak ada">
                        <div style="font-size: 11px; color: #71717a; margin-top: 4px;">Digunakan untuk dasar klaim susut mati ke supplier.</div>
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Berat Mati / Reject (Kg)</label>
                        <input type="number" name="doa_weight_kg" value="0" step="any" min="0" class="ios-input" placeholder="0.0">
                    </div>
                </div>
            </div>

            <!-- 3. Hasil Pemotongan & Parting Card -->
            <div class="ios-card" style="padding: 24px; margin-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <div>
                        <h3 style="font-size: 16px; font-weight: 800; color: #09090b; margin: 0;">3. Hasil Pemotongan & Parting (Penambah Stok)</h3>
                        <p style="font-size: 12px; color: #71717a; margin: 2px 0 0;">Input berat hasil karkas dan potongan parting (stok otomatis bertambah)</p>
                    </div>
                    <button type="button" @click="addItem()" class="ios-btn ios-btn-secondary" style="font-size: 12.5px; padding: 7px 14px;">
                        + Tambah Baris Hasil
                    </button>
                </div>

                <div style="overflow-x: auto;">
                    <table class="ios-table">
                        <thead>
                            <tr>
                                <th style="width: 40%;">Jenis Produk Karkas / Parting</th>
                                <th style="width: 25%; text-align: right;">Berat Hasil (Kg)</th>
                                <th style="width: 20%; text-align: right;">Jumlah (Ekor/Bungkus)</th>
                                <th style="width: 15%; text-align: center;">Hapus</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(item, index) in items" :key="index">
                                <tr>
                                    <td>
                                        <select :name="`items[${index}][farm_product_id]`" class="ios-input" style="padding: 8px 12px; font-size: 12.5px;" x-model="item.farm_product_id" required>
                                            <option value="">-- Pilih Produk --</option>
                                            @foreach($products as $p)
                                            <option value="{{ $p->id }}">
                                                {{ $p->name }} ({{ strtoupper($p->category) }})
                                            </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" :name="`items[${index}][weight_kg]`" x-model.number="item.weight_kg" step="any" min="0" class="ios-input" style="padding: 8px 12px; text-align: right; font-size: 12.5px;" placeholder="0.0" required>
                                    </td>
                                    <td>
                                        <input type="number" :name="`items[${index}][qty_ekor]`" x-model.number="item.qty_ekor" min="0" class="ios-input" style="padding: 8px 12px; text-align: right; font-size: 12.5px;" placeholder="0">
                                    </td>
                                    <td style="text-align: center;">
                                        <button type="button" @click="removeItem(index)" style="background: none; border: none; color: #f43f5e; cursor: pointer; font-size: 16px; font-weight: 800;" x-show="items.length > 1">&times;</button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- Yield & Shrinkage Calculation Card -->
                <div style="margin-top: 24px; padding: 18px 20px; background: #f8fafc; border-radius: 16px; border: 1px solid rgba(0,0,0,0.06); display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px;">
                    <div>
                        <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Total Hasil (Yield)</div>
                        <div style="font-size: 20px; font-weight: 800; color: #10b981; margin-top: 4px;">
                            <span x-text="totalYieldKg.toFixed(1)"></span> Kg
                        </div>
                    </div>

                    <div>
                        <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Susut Bobot (Kg)</div>
                        <div style="font-size: 20px; font-weight: 800; color: #d97706; margin-top: 4px;">
                            <span x-text="shrinkageKg.toFixed(1)"></span> Kg
                        </div>
                    </div>

                    <div>
                        <div style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase;">Persentase Susut (%)</div>
                        <div style="font-size: 20px; font-weight: 800; color: #09090b; margin-top: 4px;">
                            <span x-text="shrinkagePct.toFixed(1) + '%'"></span>
                        </div>
                        <div style="font-size: 11px; color: #64748b; margin-top: 2px;">Normal RPA: 20% - 25%</div>
                    </div>
                </div>
            </div>

            <!-- Notes Card -->
            <div class="ios-card" style="padding: 24px; margin-bottom: 24px;">
                <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Catatan Produksi (Opsional)</label>
                <textarea name="notes" rows="2" class="ios-input" placeholder="Catatan kondisi ayam, shift potong, dll..."></textarea>
            </div>

            <!-- Action Buttons -->
            <div style="display: flex; justify-content: flex-end; gap: 12px;">
                <a href="{{ route('farm.production.index') }}" class="ios-btn ios-btn-secondary">Batal</a>
                <button type="submit" class="ios-btn ios-btn-primary" style="padding: 12px 28px;">Simpan Batch & Tambah Stok</button>
            </div>
        </form>
    </div>

    <script>
        function productionForm() {
            return {
                liveCount: 0,
                liveWeight: 0,
                buyPrice: 0,
                items: [
                    @foreach($products as $p)
                    { farm_product_id: '{{ $p->id }}', weight_kg: 0, qty_ekor: 0 },
                    @endforeach
                ],
                addItem() {
                    this.items.push({ farm_product_id: '', weight_kg: 0, qty_ekor: 0 });
                },
                removeItem(index) {
                    if (this.items.length > 1) {
                        this.items.splice(index, 1);
                    }
                },
                get totalYieldKg() {
                    return this.items.reduce((acc, item) => acc + (parseFloat(item.weight_kg) || 0), 0);
                },
                get shrinkageKg() {
                    return Math.max(0, (parseFloat(this.liveWeight) || 0) - this.totalYieldKg);
                },
                get shrinkagePct() {
                    const lw = parseFloat(this.liveWeight) || 0;
                    return lw > 0 ? (this.shrinkageKg / lw) * 100 : 0;
                },
                formatRupiah(num) {
                    return 'Rp ' + (num || 0).toLocaleString('id-ID');
                }
            }
        }
    </script>

</x-farm.layout>
