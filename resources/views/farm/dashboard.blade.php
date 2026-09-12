<x-farm.layout title="Dashboard" subtitle="Ringkasan Kinerja Divisi Pemotongan Ayam (RPA)">

    <!-- Top 4 Financial Metric Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; margin-bottom: 24px;">
        <!-- 1. Tagihan (Piutang Klien) -->
        <div class="stat-card" style="border-left: 4px solid #d97706;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <div style="font-size: 11.5px; font-weight: 700; color: #71717a; text-transform: uppercase; letter-spacing: 0.05em;">Tagihan (Piutang)</div>
                    <div style="font-size: 22px; font-weight: 800; color: #09090b; margin-top: 6px;">Rp {{ number_format($totalReceivables, 0, ',', '.') }}</div>
                    <div style="font-size: 11.5px; color: #d97706; margin-top: 4px; font-weight: 600;">Belum lunas dari pembeli</div>
                </div>
                <div style="width: 36px; height: 36px; border-radius: 10px; background: #fef3c7; display: flex; align-items: center; justify-content: center; color: #d97706;">
                    <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>

        <!-- 2. Pembayaran (Hutang Supplier) -->
        <div class="stat-card" style="border-left: 4px solid #dc2626;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <div style="font-size: 11.5px; font-weight: 700; color: #71717a; text-transform: uppercase; letter-spacing: 0.05em;">Pembayaran (Hutang)</div>
                    <div style="font-size: 22px; font-weight: 800; color: #09090b; margin-top: 6px;">Rp {{ number_format($totalPayables, 0, ',', '.') }}</div>
                    <div style="font-size: 11.5px; color: #dc2626; margin-top: 4px; font-weight: 600;">Hutang beli ayam hidup</div>
                </div>
                <div style="width: 36px; height: 36px; border-radius: 10px; background: #fee2e2; display: flex; align-items: center; justify-content: center; color: #dc2626;">
                    <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>
        </div>

        <!-- 3. Pemasukan (Kas Masuk) -->
        <div class="stat-card" style="border-left: 4px solid #10b981;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <div style="font-size: 11.5px; font-weight: 700; color: #71717a; text-transform: uppercase; letter-spacing: 0.05em;">Pemasukan (Bulan Ini)</div>
                    <div style="font-size: 22px; font-weight: 800; color: #09090b; margin-top: 6px;">Rp {{ number_format($totalIncomeMonth, 0, ',', '.') }}</div>
                    <div style="font-size: 11.5px; color: #10b981; margin-top: 4px; font-weight: 600;">Hari ini: Rp {{ number_format($totalIncomeToday, 0, ',', '.') }}</div>
                </div>
                <div style="width: 36px; height: 36px; border-radius: 10px; background: #d1fae5; display: flex; align-items: center; justify-content: center; color: #10b981;">
                    <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
                </div>
            </div>
        </div>

        <!-- 4. Pengeluaran RPA -->
        <div class="stat-card" style="border-left: 4px solid #6366f1;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <div style="font-size: 11.5px; font-weight: 700; color: #71717a; text-transform: uppercase; letter-spacing: 0.05em;">Pengeluaran RPA (Bulan Ini)</div>
                    <div style="font-size: 22px; font-weight: 800; color: #09090b; margin-top: 6px;">Rp {{ number_format($totalExpenseMonth, 0, ',', '.') }}</div>
                    <div style="font-size: 11.5px; color: #6366f1; margin-top: 4px; font-weight: 600;">Hari ini: Rp {{ number_format($totalExpenseToday, 0, ',', '.') }}</div>
                </div>
                <div style="width: 36px; height: 36px; border-radius: 10px; background: #e0e7ff; display: flex; align-items: center; justify-content: center; color: #6366f1;">
                    <svg style="width: 20px; height: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- 2 Specialized KPI Cards for Poultry Processing -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 16px; margin-bottom: 24px;">
        <!-- A. Sisa Stok Karkas / Parting Hari Ini -->
        <div class="ios-card" style="padding: 22px 24px; background: linear-gradient(135deg, #ffffff, #fdfaf3);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                <div style="font-size: 12px; font-weight: 700; color: #a37f38; text-transform: uppercase; letter-spacing: 0.05em;">Sisa Stok Karkas / Parting Hari Ini</div>
                <span class="badge badge-amber"><span class="badge-dot"></span> Perishable Watch</span>
            </div>
            <div style="display: flex; align-items: baseline; gap: 10px;">
                <div style="font-size: 28px; font-weight: 800; color: #09090b;">{{ number_format($totalStockKg, 1, ',', '.') }} <span style="font-size: 16px; font-weight: 600; color: #71717a;">Kg</span></div>
                @if($totalStockEkor > 0)
                <div style="font-size: 15px; font-weight: 600; color: #71717a;">({{ number_format($totalStockEkor, 0, ',', '.') }} Ekor)</div>
                @endif
            </div>
            <div style="font-size: 12px; color: #71717a; margin-top: 8px;">Total produk segar karkas utuh & potongan parting yang siap dijual hari ini.</div>
        </div>

        <!-- B. Indikator Margin / Spread Harian -->
        <div class="ios-card" style="padding: 22px 24px; background: linear-gradient(135deg, #ffffff, #f0fdf4);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                <div style="font-size: 12px; font-weight: 700; color: #166534; text-transform: uppercase; letter-spacing: 0.05em;">Margin / Spread Harian (Beli vs Jual)</div>
                <span class="badge badge-green"><span class="badge-dot"></span> Indikator Sehat</span>
            </div>
            <div style="display: flex; align-items: baseline; gap: 8px;">
                <div style="font-size: 28px; font-weight: 800; color: #15803d;">Rp {{ number_format($marginPerKg, 0, ',', '.') }} <span style="font-size: 15px; font-weight: 600; color: #71717a;">/ Kg</span></div>
            </div>
            <div style="display: flex; gap: 16px; font-size: 12px; color: #71717a; margin-top: 8px;">
                <div>Beli Hidup: <strong style="color:#09090b;">Rp {{ number_format($avgBuyPricePerKg, 0, ',', '.') }}</strong>/Kg</div>
                <div>Jual Karkas: <strong style="color:#09090b;">Rp {{ number_format($avgSellPricePerKg, 0, ',', '.') }}</strong>/Kg</div>
            </div>
        </div>
    </div>

    <!-- 2 Column Section: Stock Overview & Pending Invoices -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 24px; margin-bottom: 24px;">
        <!-- Realtime Stock Table -->
        <div class="ios-card" style="padding: 20px 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <div>
                    <h3 style="font-size: 15px; font-weight: 800; color: #09090b; margin: 0;">Stok Karkas & Parting</h3>
                    <p style="font-size: 12px; color: #71717a; margin: 2px 0 0;">Posisi stok realtime per kategori produk</p>
                </div>
                <a href="{{ route('farm.master_data.index', ['tab' => 'products']) }}" class="ios-btn ios-btn-secondary" style="font-size: 12px; padding: 6px 14px;">Kelola Stok</a>
            </div>

            <div style="overflow-x: auto;">
                <table class="ios-table">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Produk</th>
                            <th style="text-align: right;">Stok (Kg)</th>
                            <th style="text-align: right;">Harga Acuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productsStock as $prod)
                        <tr>
                            <td style="font-weight: 600; color: #71717a; font-size: 12px;">{{ $prod->code }}</td>
                            <td style="font-weight: 700; color: #09090b;">{{ $prod->name }}</td>
                            <td style="text-align: right; font-weight: 700; color: {{ $prod->current_stock_kg > 0 ? '#09090b' : '#dc2626' }};">
                                {{ number_format($prod->current_stock_kg, 1, ',', '.') }} {{ $prod->unit }}
                            </td>
                            <td style="text-align: right; color: #71717a; font-size: 12.5px;">
                                Rp {{ number_format($prod->standard_price, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: #a1a1aa; padding: 24px;">Belum ada master produk karkas</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pending Invoices -->
        <div class="ios-card" style="padding: 20px 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <div>
                    <h3 style="font-size: 15px; font-weight: 800; color: #09090b; margin: 0;">Faktur Menunggu Pembayaran</h3>
                    <p style="font-size: 12px; color: #71717a; margin: 2px 0 0;">Faktur penjualan dengan saldo piutang</p>
                </div>
                <a href="{{ route('farm.billing.index') }}" class="ios-btn ios-btn-secondary" style="font-size: 12px; padding: 6px 14px;">Lihat Tagihan</a>
            </div>

            <div style="overflow-x: auto;">
                <table class="ios-table">
                    <thead>
                        <tr>
                            <th>No Faktur</th>
                            <th>Customer</th>
                            <th>Jatuh Tempo</th>
                            <th style="text-align: right;">Sisa Tagihan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingInvoices as $inv)
                        <tr>
                            <td>
                                <a href="{{ route('farm.invoices.show', $inv->id) }}" style="font-weight: 700; color: #09090b; text-decoration: none;">
                                    {{ $inv->invoice_number }}
                                </a>
                            </td>
                            <td style="font-weight: 600;">{{ $inv->customer->name ?? 'N/A' }}</td>
                            <td style="font-size: 12px; color: #71717a;">
                                {{ $inv->due_date ? $inv->due_date->format('d/m/Y') : '-' }}
                            </td>
                            <td style="text-align: right; font-weight: 800; color: #d97706;">
                                Rp {{ number_format($inv->remaining_amount, 0, ',', '.') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: #a1a1aa; padding: 24px;">Semua faktur penjualan telah lunas</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Production Batches -->
    <div class="ios-card" style="padding: 20px 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
            <div>
                <h3 style="font-size: 15px; font-weight: 800; color: #09090b; margin: 0;">Aktivitas Produksi & Penerimaan Ayam Hidup Terakhir</h3>
                <p style="font-size: 12px; color: #71717a; margin: 2px 0 0;">Log batch potong, HPP ayam hidup, dan susut rendemen</p>
            </div>
            <a href="{{ route('farm.production.create') }}" class="ios-btn ios-btn-primary" style="font-size: 12px; padding: 8px 16px;">+ Input Batch Baru</a>
        </div>

        <div style="overflow-x: auto;">
            <table class="ios-table">
                <thead>
                    <tr>
                        <th>No Batch</th>
                        <th>Tanggal</th>
                        <th>Supplier</th>
                        <th style="text-align: right;">Ayam Hidup</th>
                        <th style="text-align: right;">Hasil Karkas/Parting</th>
                        <th style="text-align: right;">Susut (%)</th>
                        <th style="text-align: right;">Total HPP Beli</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentBatches as $b)
                    <tr>
                        <td>
                            <a href="{{ route('farm.production.show', $b->id) }}" style="font-weight: 700; color: #09090b; text-decoration: none;">
                                {{ $b->batch_number }}
                            </a>
                        </td>
                        <td style="font-size: 12.5px; color: #71717a;">{{ $b->production_date->format('d/m/Y') }}</td>
                        <td style="font-weight: 600;">{{ $b->supplier->name ?? 'N/A' }}</td>
                        <td style="text-align: right;">
                            <span style="font-weight: 700;">{{ number_format($b->live_birds_weight_kg, 1, ',', '.') }} Kg</span>
                            <div style="font-size: 11px; color: #71717a;">({{ number_format($b->live_birds_count) }} Ekor)</div>
                        </td>
                        <td style="text-align: right; font-weight: 700; color: #10b981;">
                            {{ number_format($b->total_yield_weight_kg, 1, ',', '.') }} Kg
                        </td>
                        <td style="text-align: right;">
                            <span class="badge {{ $b->shrinkage_percentage > 25 ? 'badge-amber' : 'badge-zinc' }}">
                                {{ number_format($b->shrinkage_percentage, 1, ',', '.') }}%
                            </span>
                        </td>
                        <td style="text-align: right; font-weight: 700;">
                            Rp {{ number_format($b->total_buy_price, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: #a1a1aa; padding: 24px;">Belum ada catatan batch produksi potong</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-farm.layout>
