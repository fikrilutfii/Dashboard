<x-farm.layout title="Master Data RPA" subtitle="Kelola identitas pengirim faktur, customer, supplier, produk & stok, karyawan, dan armada">

    <!-- Tab Navigation -->
    <div style="display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 24px;">
        <a href="{{ route('farm.master_data.index', ['tab' => 'senders']) }}" class="ios-btn {{ $activeTab === 'senders' ? 'ios-btn-primary' : 'ios-btn-secondary' }}">
            Identitas Pengirim Faktur
        </a>
        <a href="{{ route('farm.master_data.index', ['tab' => 'customers']) }}" class="ios-btn {{ $activeTab === 'customers' ? 'ios-btn-primary' : 'ios-btn-secondary' }}">
            Customer & Harga Khusus
        </a>
        <a href="{{ route('farm.master_data.index', ['tab' => 'suppliers']) }}" class="ios-btn {{ $activeTab === 'suppliers' ? 'ios-btn-primary' : 'ios-btn-secondary' }}">
            Supplier Ayam Hidup
        </a>
        <a href="{{ route('farm.master_data.index', ['tab' => 'products']) }}" class="ios-btn {{ $activeTab === 'products' ? 'ios-btn-primary' : 'ios-btn-secondary' }}">
            Produk Karkas & Stok
        </a>
        <a href="{{ route('farm.master_data.index', ['tab' => 'employees']) }}" class="ios-btn {{ $activeTab === 'employees' ? 'ios-btn-primary' : 'ios-btn-secondary' }}">
            Karyawan RPA
        </a>
        <a href="{{ route('farm.master_data.index', ['tab' => 'vehicles']) }}" class="ios-btn {{ $activeTab === 'vehicles' ? 'ios-btn-primary' : 'ios-btn-secondary' }}">
            Armada & Supir
        </a>
    </div>

    <!-- ========================================== -->
    <!-- TAB 1: IDENTITAS PENGIRIM FAKTUR (SENDERS) -->
    <!-- ========================================== -->
    @if($activeTab === 'senders')
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 24px;">
        <!-- Form Tambah Pengirim -->
        <div class="ios-card" style="padding: 24px; height: fit-content;">
            <h3 style="font-size: 16px; font-weight: 800; color: #09090b; margin: 0 0 16px;">+ Tambah Identitas Pengirim</h3>
            <p style="font-size: 12px; color: #71717a; margin: -10px 0 16px;">Nama pengirim ini akan muncul di dropdown saat membuat faktur penjualan</p>

            <form method="POST" action="{{ route('farm.master_data.senders.store') }}">
                @csrf
                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 4px;">Nama Usaha / Brand Pengirim <span style="color: #dc2626;">*</span></label>
                    <input type="text" name="name" placeholder="Contoh: ASFOUR BROILER" class="ios-input" required>
                </div>

                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 4px;">No. Telepon / WA</label>
                    <input type="text" name="phone" placeholder="Contoh: 081234567890" class="ios-input">
                </div>

                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 4px;">Alamat Usaha</label>
                    <textarea name="address" rows="2" placeholder="Alamat lengkap usaha / RPA..." class="ios-input"></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 12px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 4px;">Nama Bank</label>
                        <input type="text" name="bank_name" placeholder="Contoh: BCA" class="ios-input">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 4px;">No. Rekening</label>
                        <input type="text" name="bank_account_number" placeholder="Contoh: 1234567890" class="ios-input">
                    </div>
                </div>

                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 4px;">Atas Nama Rekening</label>
                    <input type="text" name="bank_account_name" placeholder="Contoh: Fikri Lutfi" class="ios-input">
                </div>

                <div style="margin-bottom: 16px;">
                    <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 600; cursor: pointer;">
                        <input type="checkbox" name="is_default" value="1">
                        Jadikan Pengirim Default
                    </label>
                </div>

                <button type="submit" class="ios-btn ios-btn-primary" style="width: 100%;">Simpan Identitas Pengirim</button>
            </form>
        </div>

        <!-- Daftar Pengirim -->
        <div class="ios-card" style="padding: 24px;">
            <h3 style="font-size: 16px; font-weight: 800; color: #09090b; margin: 0 0 16px;">Daftar Identitas Pengirim Faktur</h3>

            <div style="display: flex; flex-direction: column; gap: 14px;">
                @forelse($senders as $s)
                <div style="background: #f8fafc; border-radius: 16px; padding: 16px 18px; border: 1px solid rgba(0,0,0,0.06);">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div>
                            <div style="font-size: 16px; font-weight: 800; color: #09090b;">
                                {{ $s->name }}
                                @if($s->is_default)
                                <span class="badge badge-amber" style="font-size: 10.5px; padding: 2px 8px;">Default</span>
                                @endif
                            </div>
                            <div style="font-size: 12.5px; color: #71717a; margin-top: 4px;">{{ $s->address ?? 'Alamat belum diatur' }}</div>
                            <div style="font-size: 12px; color: #71717a;">Telp: {{ $s->phone ?? '-' }}</div>
                            @if($s->bank_name)
                            <div style="font-size: 12px; color: #09090b; font-weight: 600; margin-top: 4px;">Bank: {{ $s->bank_name }} - {{ $s->bank_account_number }} (a.n {{ $s->bank_account_name }})</div>
                            @endif
                        </div>

                        <div>
                            <form method="POST" action="{{ route('farm.master_data.senders.destroy', $s->id) }}" onsubmit="return confirm('Hapus identitas pengirim ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ios-btn ios-btn-danger" style="padding: 4px 8px; font-size: 11px;">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
                @empty
                <div style="text-align: center; color: #a1a1aa; padding: 32px;">Belum ada data identitas pengirim</div>
                @endforelse
            </div>
        </div>
    </div>
    @endif

    <!-- ========================================== -->
    <!-- TAB 2: CUSTOMERS & CUSTOM PRICES           -->
    <!-- ========================================== -->
    @if($activeTab === 'customers')
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 24px;">
        <!-- Form Tambah Customer -->
        <div class="ios-card" style="padding: 24px; height: fit-content;">
            <h3 style="font-size: 16px; font-weight: 800; color: #09090b; margin: 0 0 16px;">+ Tambah Customer / Pembeli</h3>

            <form method="POST" action="{{ route('farm.master_data.customers.store') }}">
                @csrf
                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 4px;">Nama Pelanggan / Resto / Agen <span style="color: #dc2626;">*</span></label>
                    <input type="text" name="name" placeholder="Contoh: Rumah Makan Padang Berkah" class="ios-input" required>
                </div>

                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 4px;">No. Telepon / WA</label>
                    <input type="text" name="phone" placeholder="0812..." class="ios-input">
                </div>

                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 4px;">Kota / Wilayah</label>
                    <input type="text" name="city" placeholder="Contoh: Jakarta Barat, Pasar Induk" class="ios-input">
                </div>

                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 4px;">Alamat Lengkap</label>
                    <textarea name="address" rows="2" class="ios-input"></textarea>
                </div>

                <button type="submit" class="ios-btn ios-btn-primary" style="width: 100%;">Simpan Customer</button>
            </form>
        </div>

        <!-- Daftar Customer -->
        <div class="ios-card" style="padding: 20px 24px;">
            <h3 style="font-size: 16px; font-weight: 800; color: #09090b; margin: 0 0 16px;">Daftar Customer</h3>

            <div style="overflow-x: auto;">
                <table class="ios-table">
                    <thead>
                        <tr>
                            <th>Nama Customer</th>
                            <th>Kota</th>
                            <th>Telepon</th>
                            <th style="text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $c)
                        <tr>
                            <td style="font-weight: 700; color: #09090b;">{{ $c->name }}</td>
                            <td style="font-size: 12.5px; color: #71717a;">{{ $c->city ?? '-' }}</td>
                            <td style="font-size: 12.5px;">{{ $c->phone ?? '-' }}</td>
                            <td style="text-align: right;">
                                <form method="POST" action="{{ route('farm.master_data.customers.destroy', $c->id) }}" onsubmit="return confirm('Hapus customer ini?');" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="ios-btn ios-btn-danger" style="padding: 4px 8px; font-size: 11px;">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: #a1a1aa; padding: 24px;">Belum ada customer</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- ========================================== -->
    <!-- TAB 3: SUPPLIERS AYAM HIDUP                -->
    <!-- ========================================== -->
    @if($activeTab === 'suppliers')
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 24px;">
        <!-- Form Tambah Supplier -->
        <div class="ios-card" style="padding: 24px; height: fit-content;">
            <h3 style="font-size: 16px; font-weight: 800; color: #09090b; margin: 0 0 16px;">+ Tambah Supplier Ayam Hidup</h3>

            <form method="POST" action="{{ route('farm.master_data.suppliers.store') }}">
                @csrf
                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 4px;">Nama Supplier / Peternak <span style="color: #dc2626;">*</span></label>
                    <input type="text" name="name" placeholder="Contoh: PT Japfa / Peternak Pak Budi" class="ios-input" required>
                </div>

                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 4px;">Lokasi Kandang / Peternakan</label>
                    <input type="text" name="farm_location" placeholder="Contoh: Kandang Parung, Bogor" class="ios-input">
                </div>

                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 4px;">No. Telepon / WA</label>
                    <input type="text" name="phone" placeholder="0812..." class="ios-input">
                </div>

                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 4px;">Alamat Kantor / Supplier</label>
                    <textarea name="address" rows="2" class="ios-input"></textarea>
                </div>

                <button type="submit" class="ios-btn ios-btn-primary" style="width: 100%;">Simpan Supplier</button>
            </form>
        </div>

        <!-- Daftar Supplier -->
        <div class="ios-card" style="padding: 20px 24px;">
            <h3 style="font-size: 16px; font-weight: 800; color: #09090b; margin: 0 0 16px;">Daftar Supplier Ayam Hidup</h3>

            <div style="overflow-x: auto;">
                <table class="ios-table">
                    <thead>
                        <tr>
                            <th>Nama Supplier</th>
                            <th>Lokasi Kandang</th>
                            <th>Telepon</th>
                            <th style="text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suppliers as $s)
                        <tr>
                            <td style="font-weight: 700; color: #09090b;">{{ $s->name }}</td>
                            <td style="font-size: 12.5px; color: #71717a;">{{ $s->farm_location ?? '-' }}</td>
                            <td style="font-size: 12.5px;">{{ $s->phone ?? '-' }}</td>
                            <td style="text-align: right;">
                                <form method="POST" action="{{ route('farm.master_data.suppliers.destroy', $s->id) }}" onsubmit="return confirm('Hapus supplier ini?');" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="ios-btn ios-btn-danger" style="padding: 4px 8px; font-size: 11px;">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: #a1a1aa; padding: 24px;">Belum ada supplier ayam hidup</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- ========================================== -->
    <!-- TAB 4: PRODUK KARKAS & STOK                -->
    <!-- ========================================== -->
    @if($activeTab === 'products')
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 24px;">
        <!-- Form Tambah Produk -->
        <div class="ios-card" style="padding: 24px; height: fit-content;">
            <h3 style="font-size: 16px; font-weight: 800; color: #09090b; margin: 0 0 16px;">+ Tambah Produk Karkas / Parting</h3>

            <form method="POST" action="{{ route('farm.master_data.products.store') }}">
                @csrf
                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 4px;">Kode Produk <span style="color: #dc2626;">*</span></label>
                    <input type="text" name="code" placeholder="Contoh: AYM-UTUH, FLT-DADA, PHA-BWH" class="ios-input" required>
                </div>

                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 4px;">Nama Produk <span style="color: #dc2626;">*</span></label>
                    <input type="text" name="name" placeholder="Contoh: Ayam Karkas Utuh, Fillet Dada, Sayap" class="ios-input" required>
                </div>

                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 4px;">Kategori <span style="color: #dc2626;">*</span></label>
                    <select name="category" class="ios-input" required>
                        <option value="karkas_utuh">Ayam Karkas Utuh</option>
                        <option value="parting">Parting / Potongan (Dada, Paha, Sayap)</option>
                        <option value="sampingan">Sampingan (Ceker, Kepala, Hati Ampela)</option>
                        <option value="lain">Lain-lain</option>
                    </select>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 12px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 4px;">Satuan Dasar</label>
                        <select name="unit" class="ios-input">
                            <option value="kg">Kg</option>
                            <option value="ekor">Ekor</option>
                            <option value="pack">Pack</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 4px;">Harga Acuan / Satuan</label>
                        <input type="number" name="standard_price" step="any" min="0" placeholder="0" class="ios-input" required>
                    </div>
                </div>

                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 4px;">Stok Awal (Kg)</label>
                    <input type="number" name="current_stock_kg" step="any" value="0" class="ios-input">
                </div>

                <button type="submit" class="ios-btn ios-btn-primary" style="width: 100%;">Simpan Master Produk</button>
            </form>
        </div>

        <!-- Daftar Produk -->
        <div class="ios-card" style="padding: 20px 24px;">
            <h3 style="font-size: 16px; font-weight: 800; color: #09090b; margin: 0 0 16px;">Daftar Produk & Stok Realtime</h3>

            <div style="overflow-x: auto;">
                <table class="ios-table">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th style="text-align: right;">Stok (Kg)</th>
                            <th style="text-align: right;">Harga Acuan</th>
                            <th style="text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $p)
                        <tr>
                            <td style="font-weight: 700; font-size: 12px; color: #71717a;">{{ $p->code }}</td>
                            <td style="font-weight: 700; color: #09090b;">{{ $p->name }}</td>
                            <td style="font-size: 11.5px; color: #71717a; text-transform: uppercase;">{{ str_replace('_', ' ', $p->category) }}</td>
                            <td style="text-align: right; font-weight: 800; color: {{ $p->current_stock_kg > 0 ? '#10b981' : '#dc2626' }};">
                                {{ number_format($p->current_stock_kg, 1, ',', '.') }} {{ $p->unit }}
                            </td>
                            <td style="text-align: right; font-size: 12.5px;">Rp {{ number_format($p->standard_price, 0, ',', '.') }}</td>
                            <td style="text-align: right;">
                                <form method="POST" action="{{ route('farm.master_data.products.destroy', $p->id) }}" onsubmit="return confirm('Hapus produk ini?');" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="ios-btn ios-btn-danger" style="padding: 4px 8px; font-size: 11px;">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: #a1a1aa; padding: 24px;">Belum ada master produk</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- ========================================== -->
    <!-- TAB 5: EMPLOYEES / KARYAWAN                -->
    <!-- ========================================== -->
    @if($activeTab === 'employees')
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 24px;">
        <!-- Form Tambah Karyawan -->
        <div class="ios-card" style="padding: 24px; height: fit-content;">
            <h3 style="font-size: 16px; font-weight: 800; color: #09090b; margin: 0 0 16px;">+ Tambah Karyawan RPA</h3>

            <form method="POST" action="{{ route('farm.master_data.employees.store') }}" x-data="{ stype: 'borongan' }">
                @csrf
                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 4px;">Nama Karyawan <span style="color: #dc2626;">*</span></label>
                    <input type="text" name="name" placeholder="Nama lengkap karyawan..." class="ios-input" required>
                </div>

                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 4px;">Jabatan / Bagian</label>
                    <input type="text" name="role" placeholder="Contoh: Pemotong, Parting, Cabut Bulu, Supir" class="ios-input">
                </div>

                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 4px;">Skema Gaji Utama <span style="color: #dc2626;">*</span></label>
                    <select name="salary_type" class="ios-input" x-model="stype" required>
                        <option value="borongan">Borongan (per Kg potong)</option>
                        <option value="harian">Harian</option>
                        <option value="bulanan">Bulanan Tetap</option>
                    </select>
                </div>

                <div x-show="stype === 'borongan'" style="margin-bottom: 12px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 4px;">Tarif Borongan per Kg (Rp)</label>
                    <input type="number" name="piece_rate_per_kg" value="0" step="any" min="0" class="ios-input">
                </div>

                <div x-show="stype === 'harian'" style="margin-bottom: 12px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 4px;">Tarif Harian (Rp)</label>
                    <input type="number" name="daily_rate" value="0" step="any" min="0" class="ios-input">
                </div>

                <div x-show="stype === 'bulanan'" style="margin-bottom: 12px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 4px;">Gaji Pokok Bulanan (Rp)</label>
                    <input type="number" name="monthly_salary" value="0" step="any" min="0" class="ios-input">
                </div>

                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 4px;">No. Telepon</label>
                    <input type="text" name="phone" class="ios-input">
                </div>

                <button type="submit" class="ios-btn ios-btn-primary" style="width: 100%;">Simpan Karyawan</button>
            </form>
        </div>

        <!-- Daftar Karyawan -->
        <div class="ios-card" style="padding: 20px 24px;">
            <h3 style="font-size: 16px; font-weight: 800; color: #09090b; margin: 0 0 16px;">Daftar Karyawan RPA</h3>

            <div style="overflow-x: auto;">
                <table class="ios-table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Bagian</th>
                            <th>Skema Gaji</th>
                            <th>Tarif Dasar</th>
                            <th style="text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $emp)
                        <tr>
                            <td style="font-weight: 700; color: #09090b;">{{ $emp->name }}</td>
                            <td style="font-size: 12.5px; color: #71717a;">{{ $emp->role ?? '-' }}</td>
                            <td>
                                <span class="badge {{ $emp->salary_type === 'borongan' ? 'badge-amber' : ($emp->salary_type === 'harian' ? 'badge-blue' : 'badge-zinc') }}">
                                    {{ ucfirst($emp->salary_type) }}
                                </span>
                            </td>
                            <td style="font-size: 12.5px; font-weight: 600;">
                                @if($emp->salary_type === 'borongan')
                                Rp {{ number_format($emp->piece_rate_per_kg, 0, ',', '.') }}/kg
                                @elseif($emp->salary_type === 'harian')
                                Rp {{ number_format($emp->daily_rate, 0, ',', '.') }}/hari
                                @else
                                Rp {{ number_format($emp->monthly_salary, 0, ',', '.') }}/bln
                                @endif
                            </td>
                            <td style="text-align: right;">
                                <form method="POST" action="{{ route('farm.master_data.employees.destroy', $emp->id) }}" onsubmit="return confirm('Hapus data karyawan ini?');" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="ios-btn ios-btn-danger" style="padding: 4px 8px; font-size: 11px;">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: #a1a1aa; padding: 24px;">Belum ada master karyawan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- ========================================== -->
    <!-- TAB 6: VEHICLES / ARMADA & SUPIR           -->
    <!-- ========================================== -->
    @if($activeTab === 'vehicles')
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 24px;">
        <!-- Form Tambah Armada -->
        <div class="ios-card" style="padding: 24px; height: fit-content;">
            <h3 style="font-size: 16px; font-weight: 800; color: #09090b; margin: 0 0 16px;">+ Tambah Kendaraan Armada</h3>

            <form method="POST" action="{{ route('farm.master_data.vehicles.store') }}">
                @csrf
                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 4px;">Nomor Polisi (Plat) <span style="color: #dc2626;">*</span></label>
                    <input type="text" name="plate_number" placeholder="Contoh: B 1234 CD" class="ios-input" required>
                </div>

                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 4px;">Jenis Kendaraan</label>
                    <input type="text" name="vehicle_type" placeholder="Contoh: Gran Max Box Pendingin, Pick Up, Engkel" class="ios-input">
                </div>

                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 4px;">Supir Tetap / Default</label>
                    <input type="text" name="default_driver" placeholder="Nama supir yang biasa bawa" class="ios-input">
                </div>

                <div style="margin-bottom: 12px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 4px;">Catatan Tambahan</label>
                    <textarea name="notes" rows="2" class="ios-input"></textarea>
                </div>

                <button type="submit" class="ios-btn ios-btn-primary" style="width: 100%;">Simpan Kendaraan</button>
            </form>
        </div>

        <!-- Daftar Armada -->
        <div class="ios-card" style="padding: 20px 24px;">
            <h3 style="font-size: 16px; font-weight: 800; color: #09090b; margin: 0 0 16px;">Daftar Kendaraan Armada</h3>

            <div style="overflow-x: auto;">
                <table class="ios-table">
                    <thead>
                        <tr>
                            <th>No. Polisi</th>
                            <th>Jenis Kendaraan</th>
                            <th>Supir Default</th>
                            <th style="text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vehicles as $v)
                        <tr>
                            <td style="font-weight: 800; color: #09090b;">{{ $v->plate_number }}</td>
                            <td style="font-size: 12.5px; color: #71717a;">{{ $v->vehicle_type ?? '-' }}</td>
                            <td style="font-size: 12.5px; font-weight: 600;">{{ $v->default_driver ?? '-' }}</td>
                            <td style="text-align: right;">
                                <form method="POST" action="{{ route('farm.master_data.vehicles.destroy', $v->id) }}" onsubmit="return confirm('Hapus kendaraan armada ini?');" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="ios-btn ios-btn-danger" style="padding: 4px 8px; font-size: 11px;">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: #a1a1aa; padding: 24px;">Belum ada armada kendaraan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

</x-farm.layout>
