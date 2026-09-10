<x-farm.layout title="Transportasi & Logistik" subtitle="Kontrol SLA waktu tempuh, kondisi barang tiba, dan biaya armada">

    <x-slot:headerActions>
        <a href="{{ route('farm.transportations.create') }}" class="ios-btn ios-btn-primary">
            + Input Log Pengiriman
        </a>
    </x-slot:headerActions>

    <!-- Filter Bar -->
    <div class="ios-card" style="padding: 16px 20px; margin-bottom: 20px;">
        <form method="GET" action="{{ route('farm.transportations.index') }}" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center;">
            <div style="width: 170px;">
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="ios-input">
            </div>
            <div style="width: 170px;">
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="ios-input">
            </div>

            <div style="width: 190px;">
                <select name="status" class="ios-input">
                    <option value="">Semua Kondisi Barang</option>
                    <option value="baik" {{ request('status') === 'baik' ? 'selected' : '' }}>Baik (Selesai)</option>
                    <option value="sedang_dikirim" {{ request('status') === 'sedang_dikirim' ? 'selected' : '' }}>Sedang Dikirim</option>
                    <option value="komplain_sebagian" {{ request('status') === 'komplain_sebagian' ? 'selected' : '' }}>Komplain Sebagian</option>
                    <option value="komplain_penuh" {{ request('status') === 'komplain_penuh' ? 'selected' : '' }}>Komplain Penuh / Retur</option>
                </select>
            </div>

            <button type="submit" class="ios-btn ios-btn-secondary">Filter</button>
            @if(request()->hasAny(['start_date', 'end_date', 'status']))
            <a href="{{ route('farm.transportations.index') }}" class="ios-btn ios-btn-secondary" style="color: #71717a;">Reset</a>
            @endif
        </form>
    </div>

    <!-- Transportation Table -->
    <div class="ios-card" style="padding: 20px 24px;">
        <div style="overflow-x: auto;">
            <table class="ios-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kendaraan / No. Polisi</th>
                        <th>Supir</th>
                        <th>Faktur / Tujuan</th>
                        <th>Waktu SLA (Berangkat - Tiba)</th>
                        <th style="text-align: center;">Kondisi Barang</th>
                        <th style="text-align: right;">BBM + Tol</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transportations as $t)
                    <tr>
                        <td style="font-size: 13px; font-weight: 600;">{{ $t->transport_date->format('d/m/Y') }}</td>
                        <td style="font-weight: 700; color: #09090b;">
                            {{ $t->vehicle->plate_number ?? '-' }}
                            <div style="font-size: 11.5px; color: #71717a; font-weight: 500;">{{ $t->vehicle->vehicle_type ?? '' }}</div>
                        </td>
                        <td style="font-weight: 600;">{{ $t->driver_name }}</td>
                        <td>
                            @if($t->invoice)
                            <a href="{{ route('farm.invoices.show', $t->invoice->id) }}" style="font-weight: 700; color: #09090b; text-decoration: none;">
                                {{ $t->invoice->invoice_number }}
                            </a>
                            <div style="font-size: 11.5px; color: #71717a;">{{ $t->invoice->customer->name ?? '' }}</div>
                            @else
                            <span>{{ $t->destination ?? '-' }}</span>
                            @endif
                        </td>
                        <td style="font-size: 12.5px;">
                            @if($t->departure_time || $t->arrival_time)
                            <span style="font-weight: 700;">{{ $t->departure_time ? substr($t->departure_time, 0, 5) : '--:--' }}</span> s/d <span style="font-weight: 700;">{{ $t->arrival_time ? substr($t->arrival_time, 0, 5) : '--:--' }}</span>
                            @else
                            <span style="color: #a1a1aa;">-</span>
                            @endif
                        </td>
                        <td style="text-align: center;">
                            @if($t->delivery_status === 'baik')
                            <span class="badge badge-green"><span class="badge-dot"></span> Baik</span>
                            @elseif($t->delivery_status === 'sedang_dikirim')
                            <span class="badge badge-blue"><span class="badge-dot"></span> Dikirim</span>
                            @elseif($t->delivery_status === 'komplain_sebagian')
                            <span class="badge badge-amber"><span class="badge-dot"></span> Komplain Sebagian</span>
                            @else
                            <span class="badge badge-red"><span class="badge-dot"></span> Retur / Komplain Penuh</span>
                            @endif
                        </td>
                        <td style="text-align: right; font-weight: 700;">
                            Rp {{ number_format($t->total_cost, 0, ',', '.') }}
                        </td>
                        <td style="text-align: right;">
                            <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                <a href="{{ route('farm.transportations.edit', $t->id) }}" class="ios-btn ios-btn-secondary" style="padding: 6px 10px; font-size: 11.5px;">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('farm.transportations.destroy', $t->id) }}" onsubmit="return confirm('Hapus catatan log pengiriman ini?');" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="ios-btn ios-btn-danger" style="padding: 6px 10px; font-size: 11.5px;">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align: center; color: #a1a1aa; padding: 32px;">Belum ada catatan log transportasi pengiriman</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
            {{ $transportations->links() }}
        </div>
    </div>

</x-farm.layout>
