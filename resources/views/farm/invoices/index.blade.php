<x-farm.layout title="Faktur Penjualan" subtitle="Kelola faktur penjualan ayam potong & karkas">

    <x-slot:headerActions>
        <a href="{{ route('farm.invoices.create') }}" class="ios-btn ios-btn-primary">
            + Buat Faktur Baru
        </a>
    </x-slot:headerActions>

    <!-- Filter & Search Bar -->
    <div class="ios-card" style="padding: 16px 20px; margin-bottom: 20px;">
        <form method="GET" action="{{ route('farm.invoices.index') }}" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center;">
            <div style="flex: 1; min-width: 200px;">
                <input type="text" name="search" value="{{ request('search') }}" class="ios-input" placeholder="Cari No. Faktur atau Nama Customer...">
            </div>

            <div style="width: 170px;">
                <select name="status" class="ios-input">
                    <option value="">Semua Status</option>
                    <option value="belum_lunas" {{ request('status') === 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                    <option value="sebagian" {{ request('status') === 'sebagian' ? 'selected' : '' }}>Sebagian (Cicilan)</option>
                    <option value="lunas" {{ request('status') === 'lunas' ? 'selected' : '' }}>Lunas</option>
                </select>
            </div>

            <div style="width: 190px;">
                <select name="sender_id" class="ios-input">
                    <option value="">Semua Pengirim</option>
                    @foreach($senders as $s)
                    <option value="{{ $s->id }}" {{ request('sender_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="ios-btn ios-btn-secondary">Filter</button>
            @if(request()->hasAny(['search', 'status', 'sender_id', 'start_date', 'end_date']))
            <a href="{{ route('farm.invoices.index') }}" class="ios-btn ios-btn-secondary" style="color: #71717a;">Reset</a>
            @endif
        </form>
    </div>

    <!-- Invoice Table -->
    <div class="ios-card" style="padding: 20px 24px;">
        <div style="overflow-x: auto;">
            <table class="ios-table">
                <thead>
                    <tr>
                        <th>No. Faktur</th>
                        <th>Tanggal</th>
                        <th>Pengirim Faktur</th>
                        <th>Customer</th>
                        <th style="text-align: right;">Total Faktur</th>
                        <th style="text-align: right;">Terbayar</th>
                        <th style="text-align: right;">Sisa Tagihan</th>
                        <th style="text-align: center;">Status</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $inv)
                    <tr>
                        <td>
                            <a href="{{ route('farm.invoices.show', $inv->id) }}" style="font-weight: 700; color: #09090b; text-decoration: none;">
                                {{ $inv->invoice_number }}
                            </a>
                        </td>
                        <td style="font-size: 12.5px; color: #71717a;">{{ $inv->invoice_date->format('d/m/Y') }}</td>
                        <td style="font-weight: 600; color: #a37f38; font-size: 12.5px;">{{ $inv->sender->name ?? 'Default' }}</td>
                        <td style="font-weight: 600; color: #09090b;">{{ $inv->customer->name ?? 'N/A' }}</td>
                        <td style="text-align: right; font-weight: 700;">Rp {{ number_format($inv->total_amount, 0, ',', '.') }}</td>
                        <td style="text-align: right; font-weight: 600; color: #10b981;">Rp {{ number_format($inv->paid_amount, 0, ',', '.') }}</td>
                        <td style="text-align: right; font-weight: 800; color: {{ $inv->remaining_amount > 0 ? '#d97706' : '#10b981' }};">
                            Rp {{ number_format($inv->remaining_amount, 0, ',', '.') }}
                        </td>
                        <td style="text-align: center;">
                            @if($inv->status === 'lunas')
                            <span class="badge badge-green"><span class="badge-dot"></span> Lunas</span>
                            @elseif($inv->status === 'sebagian')
                            <span class="badge badge-amber"><span class="badge-dot"></span> Sebagian</span>
                            @else
                            <span class="badge badge-red"><span class="badge-dot"></span> Belum Lunas</span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <div style="display: flex; gap: 6px; justify-content: flex-end;" x-data="{ openPayModal: false }">
                                @if($inv->remaining_amount > 0)
                                <button @click="openPayModal = true" class="ios-btn ios-btn-gold" style="padding: 6px 12px; font-size: 11.5px;">
                                    Catat Bayar
                                </button>
                                @endif

                                <a href="{{ route('farm.invoices.export-excel', $inv->id) }}" class="ios-btn ios-btn-secondary" style="padding: 6px 10px; font-size: 11.5px;" title="Export Excel">
                                    Excel
                                </a>

                                <a href="{{ route('farm.invoices.show', $inv->id) }}" class="ios-btn ios-btn-secondary" style="padding: 6px 10px; font-size: 11.5px;">
                                    Detail
                                </a>

                                <a href="{{ route('farm.invoices.edit', $inv->id) }}" class="ios-btn ios-btn-secondary" style="padding: 6px 10px; font-size: 11.5px;">
                                    Edit
                                </a>

                                <!-- Modal Catat Bayar / Cicilan -->
                                <div x-show="openPayModal" class="modal-backdrop" x-cloak>
                                    <div class="modal-content" @click.away="openPayModal = false">
                                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                                            <div>
                                                <h3 style="font-size: 17px; font-weight: 800; color: #09090b; margin: 0;">Catat Pembayaran / Cicilan</h3>
                                                <p style="font-size: 12.5px; color: #71717a; margin: 2px 0 0;">Faktur: {{ $inv->invoice_number }} ({{ $inv->customer->name ?? '' }})</p>
                                            </div>
                                            <button @click="openPayModal = false" style="background: none; border: none; font-size: 18px; cursor: pointer; color: #71717a;">&times;</button>
                                        </div>

                                        <div style="background: #f4f4f6; border-radius: 14px; padding: 14px 16px; margin-bottom: 18px; display: flex; justify-content: space-between;">
                                            <div>
                                                <div style="font-size: 11px; color: #71717a; font-weight: 600; text-transform: uppercase;">Total Faktur</div>
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
                                                <div style="font-size: 11px; color: #71717a; margin-top: 4px;">Isi sesuai nominal cicilan atau biarkan penuh untuk pelunasan.</div>
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
                                                <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Catatan / No Ref (Opsional)</label>
                                                <input type="text" name="notes" placeholder="Contoh: Transfer BCA Ref #88921" class="ios-input">
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
                        <td colspan="9" style="text-align: center; color: #a1a1aa; padding: 32px;">Belum ada data faktur penjualan</td>
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
