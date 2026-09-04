<x-farm-layout title="Detail Faktur Penjualan" subtitle="{{ $invoice->invoice_number }}">
    <x-slot name="headerActions">
        <a href="{{ route('farm.invoices.print', $invoice) }}" target="_blank" class="ios-btn ios-btn-secondary">
            <svg style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Cetak Faktur
        </a>
        <a href="{{ route('farm.invoices.edit', $invoice) }}" class="ios-btn ios-btn-secondary">
            <svg style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit
        </a>
        <a href="{{ route('farm.invoices.index') }}" class="ios-btn ios-btn-secondary">
            Kembali
        </a>
    </x-slot>

    @php $remaining = $invoice->total_amount - $invoice->paid_amount; @endphp

    <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;" x-data="{ openPaymentModal: false }">
        <!-- Main Invoice Content -->
        <div>
            <div class="ios-card" style="padding:28px;">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:24px;border-bottom:1px solid rgba(0,0,0,0.06);padding-bottom:20px;">
                    <div>
                        <span class="badge badge-amber" style="font-size:12px;padding:4px 12px;margin-bottom:8px;"><span class="badge-dot"></span> PETERNAKAN AYAM</span>
                        <h2 style="font-size:24px;font-weight:800;color:#09090b;margin:4px 0 0;">{{ $invoice->invoice_number }}</h2>
                        <div style="font-size:13px;color:#71717a;margin-top:2px;">Tanggal: {{ \Carbon\Carbon::parse($invoice->invoice_date)->translatedFormat('d F Y') }}</div>
                    </div>
                    <div style="text-align:right;">
                        @if($invoice->status === 'lunas')
                            <span class="badge badge-green" style="font-size:13px;padding:6px 16px;"><span class="badge-dot"></span> LUNAS</span>
                        @elseif($invoice->status === 'sebagian')
                            <span class="badge badge-amber" style="font-size:13px;padding:6px 16px;"><span class="badge-dot"></span> BAYAR SEBAGIAN</span>
                        @else
                            <span class="badge badge-red" style="font-size:13px;padding:6px 16px;"><span class="badge-dot"></span> BELUM LUNAS</span>
                        @endif
                        @if($invoice->due_date)
                            <div style="font-size:12px;color:#71717a;margin-top:6px;">Jatuh Tempo: {{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}</div>
                        @endif
                    </div>
                </div>

                <!-- Customer & Coop Info -->
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:28px;background:rgba(0,0,0,0.02);padding:18px;border-radius:18px;border:1px solid rgba(0,0,0,0.04);">
                    <div>
                        <div style="font-size:11px;font-weight:700;color:#71717a;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:4px;">Customer / Pembeli</div>
                        <div style="font-size:16px;font-weight:700;color:#09090b;">{{ $invoice->customer->name ?? '-' }}</div>
                        @if($invoice->customer->phone)<div style="font-size:13px;color:#52525b;">Telp: {{ $invoice->customer->phone }}</div>@endif
                        @if($invoice->customer->city)<div style="font-size:13px;color:#52525b;">Kota: {{ $invoice->customer->city }}</div>@endif
                    </div>
                    <div>
                        <div style="font-size:11px;font-weight:700;color:#71717a;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:4px;">Asal Panen Kandang</div>
                        <div style="font-size:16px;font-weight:700;color:#c5a059;">{{ $invoice->coop->name ?? 'Kandang Utama' }}</div>
                        <div style="font-size:13px;color:#52525b;margin-top:4px;">Metode Pembayaran: <strong>{{ strtoupper($invoice->payment_method ?? 'TRANSFER') }}</strong></div>
                    </div>
                </div>

                <!-- Items Breakdown -->
                <h3 style="font-size:15px;font-weight:700;color:#09090b;margin-bottom:12px;">Rincian Barang & Panen</h3>
                <table class="ios-table" style="margin-bottom:24px;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Deskripsi / Item Panen</th>
                            <th style="text-align:center;">Qty</th>
                            <th>Satuan</th>
                            <th style="text-align:right;">Harga Satuan</th>
                            <th style="text-align:right;">Total Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->items as $idx => $item)
                        <tr>
                            <td style="width:40px;color:#71717a;">{{ $idx + 1 }}</td>
                            <td style="font-weight:600;color:#09090b;">{{ $item->description }}</td>
                            <td style="text-align:center;font-weight:700;">{{ number_format($item->qty, 2) }}</td>
                            <td><span class="badge badge-zinc">{{ $item->unit }}</span></td>
                            <td style="text-align:right;">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                            <td style="text-align:right;font-weight:700;color:#09090b;">Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                @if($invoice->notes)
                <div style="background:#fffbeb;border:1px solid #fef3c7;border-radius:14px;padding:14px 18px;font-size:13px;color:#92400e;">
                    <strong>Catatan:</strong> {{ $invoice->notes }}
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar Summary Card -->
        <div>
            <div class="ios-card" style="padding:24px;position:sticky;top:90px;">
                <h3 style="font-size:16px;font-weight:800;color:#09090b;margin-top:0;margin-bottom:16px;">Ringkasan Pembayaran</h3>

                <div style="display:flex;justify-content:space-between;font-size:14px;margin-bottom:10px;color:#52525b;">
                    <span>Total Tagihan</span>
                    <strong style="color:#09090b;">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</strong>
                </div>

                <div style="display:flex;justify-content:space-between;font-size:14px;margin-bottom:10px;color:#52525b;">
                    <span>Telah Dibayar</span>
                    <strong style="color:#059669;">Rp {{ number_format($invoice->paid_amount, 0, ',', '.') }}</strong>
                </div>

                <hr style="border:none;border-top:1px solid rgba(0,0,0,0.06);margin:14px 0;">

                <div style="display:flex;justify-content:space-between;font-size:16px;margin-bottom:20px;">
                    <span style="font-weight:700;color:#09090b;">Sisa Piutang</span>
                    <strong style="font-weight:800;color:{{ $remaining > 0 ? '#e11d48' : '#059669' }};">
                        Rp {{ number_format($remaining, 0, ',', '.') }}
                    </strong>
                </div>

                @if($remaining > 0)
                <button type="button" @click="openPaymentModal = true" class="ios-btn ios-btn-primary" style="width:100%;background:#059669;margin-bottom:12px;">
                    + Catat Pembayaran
                </button>
                @endif

                <form action="{{ route('farm.invoices.destroy', $invoice) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus faktur ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="ios-btn ios-btn-danger" style="width:100%;">
                        Hapus Faktur
                    </button>
                </form>
            </div>
        </div>

        <!-- Payment Modal -->
        <div x-show="openPaymentModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm" x-cloak>
            <div class="ios-card" style="width:100%;max-width:440px;padding:28px;margin:16px;">
                <h3 style="font-size:18px;font-weight:800;color:#09090b;margin-top:0;margin-bottom:6px;">Input Pembayaran Faktur</h3>
                <p style="font-size:13px;color:#71717a;margin-bottom:20px;">No: <strong style="color:#c5a059;">{{ $invoice->invoice_number }}</strong></p>

                <form action="{{ route('farm.invoices.payment', $invoice) }}" method="POST">
                    @csrf
                    <div style="margin-bottom:16px;">
                        <label style="display:block;font-size:13px;font-weight:600;color:#09090b;margin-bottom:6px;">Sisa Piutang Saat Ini</label>
                        <div style="font-size:20px;font-weight:800;color:#e11d48;">Rp {{ number_format($remaining, 0, ',', '.') }}</div>
                    </div>

                    <div style="margin-bottom:20px;">
                        <label style="display:block;font-size:13px;font-weight:600;color:#09090b;margin-bottom:6px;">Jumlah Pembayaran (Rp) <span style="color:#f43f5e;">*</span></label>
                        <input type="number" name="amount" required max="{{ $remaining }}" value="{{ $remaining }}" class="ios-input" placeholder="Nominal pembayaran">
                    </div>

                    <div style="display:flex;justify-content:flex-end;gap:10px;">
                        <button type="button" @click="openPaymentModal = false" class="ios-btn ios-btn-secondary">Batal</button>
                        <button type="submit" class="ios-btn ios-btn-primary" style="background:#059669;">Simpan Pembayaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-farm-layout>
