<x-farm.layout title="Detail Faktur Penjualan" subtitle="{{ $invoice->invoice_number }}">

    <x-slot:headerActions>
        <a href="{{ route('farm.invoices.export-excel', $invoice->id) }}" class="ios-btn ios-btn-gold">
            <svg style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Export Excel
        </a>
        <a href="{{ route('farm.invoices.edit', $invoice->id) }}" class="ios-btn ios-btn-secondary">Edit Faktur</a>
        <a href="{{ route('farm.invoices.index') }}" class="ios-btn ios-btn-secondary">Kembali</a>
    </x-slot:headerActions>

    <div style="max-width: 960px; margin: 0 auto;">
        <!-- Top Info Header Card -->
        <div class="ios-card" style="padding: 28px; margin-bottom: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 20px;">
                <!-- Sender & Invoice Ident -->
                <div>
                    <div style="font-size: 11px; font-weight: 700; color: #a37f38; text-transform: uppercase; letter-spacing: 0.08em;">Identitas Pengirim Faktur</div>
                    <div style="font-size: 20px; font-weight: 800; color: #09090b; margin-top: 2px;">{{ $invoice->sender->name ?? 'Asfour Broiler' }}</div>
                    @if($invoice->sender)
                    <div style="font-size: 12.5px; color: #71717a; margin-top: 4px;">{{ $invoice->sender->address }}</div>
                    <div style="font-size: 12.5px; color: #71717a;">Telp: {{ $invoice->sender->phone ?? '-' }}</div>
                    @if($invoice->sender->bank_name)
                    <div style="font-size: 12px; color: #09090b; font-weight: 600; margin-top: 4px;">Bank: {{ $invoice->sender->bank_name }} - {{ $invoice->sender->bank_account_number }} (a.n {{ $invoice->sender->bank_account_name }})</div>
                    @endif
                    @endif
                </div>

                <!-- Customer & Date Info -->
                <div style="text-align: right;">
                    <div style="font-size: 18px; font-weight: 800; color: #09090b;">{{ $invoice->invoice_number }}</div>
                    <div style="margin-top: 6px;">
                        @if($invoice->status === 'lunas')
                        <span class="badge badge-green"><span class="badge-dot"></span> Lunas</span>
                        @elseif($invoice->status === 'sebagian')
                        <span class="badge badge-amber"><span class="badge-dot"></span> Sebagian (Cicilan)</span>
                        @else
                        <span class="badge badge-red"><span class="badge-dot"></span> Belum Lunas</span>
                        @endif
                    </div>
                    <div style="font-size: 12.5px; color: #71717a; margin-top: 8px;">Tanggal Faktur: <strong style="color:#09090b;">{{ $invoice->invoice_date->format('d/m/Y') }}</strong></div>
                    @if($invoice->due_date)
                    <div style="font-size: 12.5px; color: #71717a;">Jatuh Tempo: <strong style="color:#09090b;">{{ $invoice->due_date->format('d/m/Y') }}</strong></div>
                    @endif
                    <div style="font-size: 13px; color: #09090b; margin-top: 8px; font-weight: 700;">Kepada: {{ $invoice->customer->name ?? 'N/A' }}</div>
                    <div style="font-size: 12px; color: #71717a;">{{ $invoice->customer->address ?? '' }}</div>
                </div>
            </div>
        </div>

        <!-- Line Items Card -->
        <div class="ios-card" style="padding: 24px; margin-bottom: 24px;">
            <h3 style="font-size: 15px; font-weight: 800; color: #09090b; margin: 0 0 16px;">Rincian Barang Karkas & Potongan</h3>

            <div style="overflow-x: auto;">
                <table class="ios-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama Produk</th>
                            <th style="text-align: right;">Berat (Kg)</th>
                            <th style="text-align: right;">Ekor</th>
                            <th style="text-align: right;">Harga Satuan</th>
                            <th style="text-align: right;">Total Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->items as $idx => $item)
                        <tr>
                            <td style="color: #71717a; font-size: 12px;">{{ $idx + 1 }}</td>
                            <td style="font-weight: 600; color: #71717a; font-size: 12px;">{{ $item->product_code ?? '-' }}</td>
                            <td style="font-weight: 700; color: #09090b;">{{ $item->item_name }}</td>
                            <td style="text-align: right; font-weight: 700;">{{ number_format($item->weight_kg, 1, ',', '.') }} Kg</td>
                            <td style="text-align: right; color: #71717a;">{{ $item->qty_ekor > 0 ? number_format($item->qty_ekor) : '-' }}</td>
                            <td style="text-align: right;">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                            <td style="text-align: right; font-weight: 800;">Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Financial Summary -->
            <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid rgba(0,0,0,0.06); display: flex; justify-content: flex-end;">
                <div style="width: 300px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 13.5px; color: #71717a;">
                        <span>Total Faktur:</span>
                        <strong style="color: #09090b;">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 13.5px; color: #10b981;">
                        <span>Total Terbayar:</span>
                        <strong>Rp {{ number_format($invoice->paid_amount, 0, ',', '.') }}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 16px; font-weight: 800; color: {{ $invoice->remaining_amount > 0 ? '#d97706' : '#10b981' }}; padding-top: 8px; border-top: 2px solid #09090b;">
                        <span>Sisa Tagihan:</span>
                        <span>Rp {{ number_format($invoice->remaining_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment History (Cicilan / Bertahap) -->
        <div class="ios-card" style="padding: 24px; margin-bottom: 24px;" x-data="{ openPayModal: false }">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <div>
                    <h3 style="font-size: 15px; font-weight: 800; color: #09090b; margin: 0;">Riwayat Pembayaran & Cicilan</h3>
                    <p style="font-size: 12px; color: #71717a; margin: 2px 0 0;">Catatan angsuran dan pembayaran bertahap dari pelanggan</p>
                </div>
                @if($invoice->remaining_amount > 0)
                <button type="button" @click="openPayModal = true" class="ios-btn ios-btn-gold" style="font-size: 12px; padding: 7px 16px;">
                    + Tambah Pembayaran
                </button>
                @endif
            </div>

            <div style="overflow-x: auto;">
                <table class="ios-table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th style="text-align: right;">Nominal Bayar</th>
                            <th>Metode</th>
                            <th>Catatan / No. Ref</th>
                            <th style="text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoice->payments as $p)
                        <tr>
                            <td style="font-size: 13px; font-weight: 600;">{{ $p->payment_date->format('d/m/Y') }}</td>
                            <td style="text-align: right; font-weight: 800; color: #10b981;">Rp {{ number_format($p->amount, 0, ',', '.') }}</td>
                            <td style="font-size: 12.5px;">{{ $p->payment_method }}</td>
                            <td style="font-size: 12.5px; color: #71717a;">{{ $p->notes ?? $p->reference_number ?? '-' }}</td>
                            <td style="text-align: right;">
                                <form method="POST" action="{{ route('farm.invoices.payment.destroy', ['invoice' => $invoice->id, 'payment' => $p->id]) }}" onsubmit="return confirm('Hapus riwayat pembayaran ini? Saldo tagihan akan dikembalikan.');" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: none; border: none; color: #f43f5e; font-size: 12px; font-weight: 600; cursor: pointer;">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: #a1a1aa; padding: 20px;">Belum ada riwayat pembayaran yang tercatat</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Modal Form Pembayaran Cicilan -->
            <div x-show="openPayModal" class="modal-backdrop" x-cloak>
                <div class="modal-content" @click.away="openPayModal = false">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h3 style="font-size: 17px; font-weight: 800; color: #09090b; margin: 0;">Catat Pembayaran Cicilan</h3>
                        <button @click="openPayModal = false" style="background: none; border: none; font-size: 18px; cursor: pointer; color: #71717a;">&times;</button>
                    </div>

                    <form method="POST" action="{{ route('farm.invoices.payment.store', $invoice->id) }}">
                        @csrf
                        <div style="margin-bottom: 14px;">
                            <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Tanggal Bayar</label>
                            <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" class="ios-input" required>
                        </div>

                        <div style="margin-bottom: 14px;">
                            <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Nominal Pembayaran (Rp)</label>
                            <input type="number" name="amount" value="{{ $invoice->remaining_amount }}" max="{{ $invoice->remaining_amount }}" min="1" step="any" class="ios-input" required>
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
                            <input type="text" name="notes" placeholder="Contoh: Cicilan ke-2 transfer BCA" class="ios-input">
                        </div>

                        <div style="display: flex; gap: 10px; justify-content: flex-end;">
                            <button type="button" @click="openPayModal = false" class="ios-btn ios-btn-secondary">Batal</button>
                            <button type="submit" class="ios-btn ios-btn-primary">Simpan Pembayaran</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Invoice Button -->
        <div style="display: flex; justify-content: flex-end;">
            <form method="POST" action="{{ route('farm.invoices.destroy', $invoice->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus faktur ini? Stok produk akan dikembalikan.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="ios-btn ios-btn-danger" style="font-size: 12.5px;">Hapus Faktur Penjualan</button>
            </form>
        </div>
    </div>

</x-farm.layout>
