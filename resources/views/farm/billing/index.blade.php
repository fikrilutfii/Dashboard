<x-farm.layout title="Laporan Tagihan & Aging Report" subtitle="Monitoring piutang penjualan karkas dan umur jatuh tempo">

    <!-- Aging Report Metric Summary -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 14px; margin-bottom: 24px;">
        <!-- Total Piutang -->
        <div class="stat-card" style="border-left: 4px solid #09090b;">
            <div style="font-size: 11px; font-weight: 700; color: #71717a; text-transform: uppercase;">Total Piutang Belum Lunas</div>
            <div style="font-size: 20px; font-weight: 800; color: #09090b; margin-top: 4px;">Rp {{ number_format($totalReceivables, 0, ',', '.') }}</div>
            <div style="font-size: 11px; color: #71717a; margin-top: 4px;">Semua kategori umur piutang</div>
        </div>

        <!-- 0-7 Hari (Current) -->
        <div class="stat-card" style="border-left: 4px solid #10b981;">
            <div style="font-size: 11px; font-weight: 700; color: #166534; text-transform: uppercase;">Lancar (0 - 7 Hari)</div>
            <div style="font-size: 20px; font-weight: 800; color: #15803d; margin-top: 4px;">Rp {{ number_format($aging0to7, 0, ',', '.') }}</div>
            <div style="font-size: 11px; color: #166534; margin-top: 4px;">Periode tempo baru</div>
        </div>

        <!-- 8-14 Hari (Warning) -->
        <div class="stat-card" style="border-left: 4px solid #3b82f6;">
            <div style="font-size: 11px; font-weight: 700; color: #1e40af; text-transform: uppercase;">Perhatian (8 - 14 Hari)</div>
            <div style="font-size: 20px; font-weight: 800; color: #1d4ed8; margin-top: 4px;">Rp {{ number_format($aging8to14, 0, ',', '.') }}</div>
            <div style="font-size: 11px; color: #1e40af; margin-top: 4px;">Jatuh tempo 1-2 minggu</div>
        </div>

        <!-- 15-30 Hari (Overdue) -->
        <div class="stat-card" style="border-left: 4px solid #f59e0b;">
            <div style="font-size: 11px; font-weight: 700; color: #92400e; text-transform: uppercase;">Menunggak (15 - 30 Hari)</div>
            <div style="font-size: 20px; font-weight: 800; color: #b45309; margin-top: 4px;">Rp {{ number_format($aging15to30, 0, ',', '.') }}</div>
            <div style="font-size: 11px; color: #92400e; margin-top: 4px;">Perlu penagihan aktif</div>
        </div>

        <!-- >30 Hari (Chronic) -->
        <div class="stat-card" style="border-left: 4px solid #ef4444;">
            <div style="font-size: 11px; font-weight: 700; color: #991b1b; text-transform: uppercase;">Kronis (> 30 Hari)</div>
            <div style="font-size: 20px; font-weight: 800; color: #b91c1c; margin-top: 4px;">Rp {{ number_format($agingOver30, 0, ',', '.') }}</div>
            <div style="font-size: 11px; color: #991b1b; margin-top: 4px;">Piutang macet / kritis</div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="ios-card" style="padding: 16px 20px; margin-bottom: 20px;">
        <form method="GET" action="{{ route('farm.billing.index') }}" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center;">
            <div style="flex: 1; min-width: 220px;">
                <select name="customer_id" class="ios-input">
                    <option value="">Semua Pelanggan / Customer</option>
                    @foreach($customers as $c)
                    <option value="{{ $c->id }}" {{ request('customer_id') == $c->id ? 'selected' : '' }}>{{ $c->name }} ({{ $c->city ?? 'Umum' }})</option>
                    @endforeach
                </select>
            </div>

            <div style="width: 200px;">
                <select name="aging_bracket" class="ios-input">
                    <option value="">Semua Umur Piutang</option>
                    <option value="0_7" {{ request('aging_bracket') === '0_7' ? 'selected' : '' }}>0 - 7 Hari (Lancar)</option>
                    <option value="8_14" {{ request('aging_bracket') === '8_14' ? 'selected' : '' }}>8 - 14 Hari (Perhatian)</option>
                    <option value="15_30" {{ request('aging_bracket') === '15_30' ? 'selected' : '' }}>15 - 30 Hari (Menunggak)</option>
                    <option value="over_30" {{ request('aging_bracket') === 'over_30' ? 'selected' : '' }}>> 30 Hari (Kronis)</option>
                </select>
            </div>

            <button type="submit" class="ios-btn ios-btn-secondary">Filter</button>
            @if(request()->hasAny(['customer_id', 'aging_bracket']))
            <a href="{{ route('farm.billing.index') }}" class="ios-btn ios-btn-secondary" style="color: #71717a;">Reset</a>
            @endif
        </form>
    </div>

    <!-- Tagihan Table -->
    <div class="ios-card" style="padding: 20px 24px;">
        <div style="overflow-x: auto;">
            <table class="ios-table">
                <thead>
                    <tr>
                        <th>No. Faktur</th>
                        <th>Tanggal</th>
                        <th>Customer</th>
                        <th>Umur Piutang</th>
                        <th style="text-align: right;">Total Faktur</th>
                        <th style="text-align: right;">Terbayar</th>
                        <th style="text-align: right;">Sisa Tagihan</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $today = \Carbon\Carbon::today(); @endphp
                    @forelse($invoices as $inv)
                    @php
                        $days = $today->diffInDays($inv->invoice_date);
                    @endphp
                    <tr>
                        <td>
                            <a href="{{ route('farm.invoices.show', $inv->id) }}" style="font-weight: 700; color: #09090b; text-decoration: none;">
                                {{ $inv->invoice_number }}
                            </a>
                        </td>
                        <td style="font-size: 12.5px; color: #71717a;">{{ $inv->invoice_date->format('d/m/Y') }}</td>
                        <td style="font-weight: 700; color: #09090b;">{{ $inv->customer->name ?? 'N/A' }}</td>
                        <td>
                            @if($days <= 7)
                            <span class="badge badge-green"><span class="badge-dot"></span> {{ $days }} Hari</span>
                            @elseif($days <= 14)
                            <span class="badge badge-blue"><span class="badge-dot"></span> {{ $days }} Hari</span>
                            @elseif($days <= 30)
                            <span class="badge badge-amber"><span class="badge-dot"></span> {{ $days }} Hari</span>
                            @else
                            <span class="badge badge-red"><span class="badge-dot"></span> {{ $days }} Hari (Kronis)</span>
                            @endif
                        </td>
                        <td style="text-align: right; font-weight: 600;">Rp {{ number_format($inv->total_amount, 0, ',', '.') }}</td>
                        <td style="text-align: right; font-weight: 600; color: #10b981;">Rp {{ number_format($inv->paid_amount, 0, ',', '.') }}</td>
                        <td style="text-align: right; font-weight: 800; color: #d97706;">Rp {{ number_format($inv->remaining_amount, 0, ',', '.') }}</td>
                        <td style="text-align: right;">
                            <div style="display: flex; gap: 6px; justify-content: flex-end;" x-data="{ openPayModal: false }">
                                <button @click="openPayModal = true" class="ios-btn ios-btn-gold" style="padding: 6px 12px; font-size: 11.5px;">
                                    Catat Cicilan
                                </button>
                                <a href="{{ route('farm.invoices.show', $inv->id) }}" class="ios-btn ios-btn-secondary" style="padding: 6px 10px; font-size: 11.5px;">
                                    Detail
                                </a>

                                <!-- Modal Catat Cicilan -->
                                <div x-show="openPayModal" class="modal-backdrop" x-cloak>
                                    <div class="modal-content" @click.away="openPayModal = false">
                                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                                            <div>
                                                <h3 style="font-size: 17px; font-weight: 800; color: #09090b; margin: 0;">Catat Pembayaran Cicilan</h3>
                                                <p style="font-size: 12.5px; color: #71717a; margin: 2px 0 0;">Faktur: {{ $inv->invoice_number }} - {{ $inv->customer->name ?? '' }}</p>
                                            </div>
                                            <button @click="openPayModal = false" style="background: none; border: none; font-size: 18px; cursor: pointer; color: #71717a;">&times;</button>
                                        </div>

                                        <div style="background: #f4f4f6; border-radius: 14px; padding: 14px 16px; margin-bottom: 18px; display: flex; justify-content: space-between;">
                                            <div>
                                                <div style="font-size: 11px; color: #71717a; font-weight: 600; text-transform: uppercase;">Total Tagihan</div>
                                                <div style="font-size: 15px; font-weight: 800;">Rp {{ number_format($inv->total_amount, 0, ',', '.') }}</div>
                                            </div>
                                            <div style="text-align: right;">
                                                <div style="font-size: 11px; color: #71717a; font-weight: 600; text-transform: uppercase;">Sisa Tagihan</div>
                                                <div style="font-size: 15px; font-weight: 800; color: #d97706;">Rp {{ number_format($inv->remaining_amount, 0, ',', '.') }}</div>
                                            </div>
                                        </div>

                                        <form method="POST" action="{{ route('farm.invoices.payment.store', $inv->id) }}">
                                            @csrf
                                            <div style="margin-bottom: 14px;">
                                                <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Tanggal Bayar</label>
                                                <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" class="ios-input" required>
                                            </div>

                                            <div style="margin-bottom: 14px;">
                                                <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Nominal Pembayaran (Rp)</label>
                                                <input type="number" name="amount" value="{{ $inv->remaining_amount }}" max="{{ $inv->remaining_amount }}" min="1" step="any" class="ios-input" required>
                                            </div>

                                            <div style="margin-bottom: 14px;">
                                                <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Metode Pembayaran</label>
                                                <select name="payment_method" class="ios-input" required>
                                                    <option value="Transfer Bank">Transfer Bank</option>
                                                    <option value="Tunai">Tunai / Cash</option>
                                                    <option value="Giro / Cek">Giro / Cek</option>
                                                </select>
                                            </div>

                                            <div style="margin-bottom: 20px;">
                                                <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Catatan / No. Referensi</label>
                                                <input type="text" name="notes" placeholder="Contoh: Cicilan BCA ref #1234" class="ios-input">
                                            </div>

                                            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                                                <button type="button" @click="openPayModal = false" class="ios-btn ios-btn-secondary">Batal</button>
                                                <button type="submit" class="ios-btn ios-btn-primary">Simpan Pembayaran</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align: center; color: #a1a1aa; padding: 32px;">Tidak ada tagihan tertunggak sesuai filter</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
            {{ $invoices->links() }}
        </div>
    </div>

</x-farm.layout>
