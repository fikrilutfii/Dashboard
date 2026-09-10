<x-farm.layout title="Catat Pengeluaran Kas" subtitle="Input kas keluar untuk kebutuhan operasional pemotongan ayam">

    <div style="max-width: 700px; margin: 0 auto;">
        <form method="POST" action="{{ route('farm.expenses.store') }}">
            @csrf

            <div class="ios-card" style="padding: 24px; margin-bottom: 24px;">
                <h3 style="font-size: 16px; font-weight: 800; color: #09090b; margin: 0 0 16px;">Informasi Pengeluaran</h3>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Tanggal Pengeluaran <span style="color: #dc2626;">*</span></label>
                        <input type="date" name="expense_date" value="{{ date('Y-m-d') }}" class="ios-input" required>
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Kategori Pengeluaran <span style="color: #dc2626;">*</span></label>
                        <select name="category" class="ios-input" required>
                            <option value="operasional_rpa">Operasional Produksi RPA</option>
                            <option value="administrasi">Administrasi & Umum</option>
                            <option value="armada_umum">Armada (Servis/Pajak Berkala)</option>
                            <option value="lain">Lain-lain</option>
                        </select>
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Jenis / Subkategori</label>
                        <input type="text" name="subcategory" placeholder="Contoh: Es Balok, Plastik, Gas Scalder, Kuli" class="ios-input">
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Nominal (Rp) <span style="color: #dc2626;">*</span></label>
                        <input type="number" name="amount" step="any" min="1" class="ios-input" placeholder="0" required>
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Metode Pembayaran <span style="color: #dc2626;">*</span></label>
                        <select name="payment_method" class="ios-input" required>
                            <option value="Tunai">Tunai / Cash</option>
                            <option value="Transfer Bank">Transfer Bank</option>
                        </select>
                    </div>

                    <div style="grid-column: 1 / -1;">
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Deskripsi / Keterangan <span style="color: #dc2626;">*</span></label>
                        <input type="text" name="description" placeholder="Keterangan pengeluaran detail..." class="ios-input" required>
                    </div>

                    <div style="grid-column: 1 / -1;">
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Catatan Tambahan (Opsional)</label>
                        <textarea name="notes" rows="2" class="ios-input" placeholder="Catatan no nota / struk..."></textarea>
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px;">
                <a href="{{ route('farm.expenses.index') }}" class="ios-btn ios-btn-secondary">Batal</a>
                <button type="submit" class="ios-btn ios-btn-primary" style="padding: 12px 28px;">Simpan Pengeluaran</button>
            </div>
        </form>
    </div>

</x-farm.layout>
