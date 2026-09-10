<x-farm.layout title="Produksi Pemotongan Ayam" subtitle="Pencatatan penerimaan ayam hidup, HPP supplier, hasil parting, dan susut bobot">

    <x-slot:headerActions>
        <a href="{{ route('farm.production.create') }}" class="ios-btn ios-btn-primary">
            + Input Batch Produksi Baru
        </a>
    </x-slot:headerActions>

    <!-- Filter Bar -->
    <div class="ios-card" style="padding: 16px 20px; margin-bottom: 20px;">
        <form method="GET" action="{{ route('farm.production.index') }}" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center;">
            <div style="width: 170px;">
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="ios-input">
            </div>
            <div style="width: 170px;">
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="ios-input">
            </div>

            <div style="width: 200px;">
                <select name="supplier_id" class="ios-input">
                    <option value="">Semua Supplier</option>
                    @foreach($suppliers as $s)
                    <option value="{{ $s->id }}" {{ request('supplier_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="ios-btn ios-btn-secondary">Filter</button>
            @if(request()->hasAny(['start_date', 'end_date', 'supplier_id']))
            <a href="{{ route('farm.production.index') }}" class="ios-btn ios-btn-secondary" style="color: #71717a;">Reset</a>
            @endif
        </form>
    </div>

    <!-- Production Table -->
    <div class="ios-card" style="padding: 20px 24px;">
        <div style="overflow-x: auto;">
            <table class="ios-table">
                <thead>
                    <tr>
                        <th>No. Batch</th>
                        <th>Tanggal</th>
                        <th>Supplier Asal</th>
                        <th style="text-align: right;">Ayam Hidup</th>
                        <th style="text-align: right;">HPP Beli (Rp/Kg)</th>
                        <th style="text-align: right;">Mati (DOA)</th>
                        <th style="text-align: right;">Hasil Karkas/Parting</th>
                        <th style="text-align: right;">Susut (%)</th>
                        <th style="text-align: center;">Bayar Supplier</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($batches as $b)
                    <tr>
                        <td>
                            <a href="{{ route('farm.production.show', $b->id) }}" style="font-weight: 700; color: #09090b; text-decoration: none;">
                                {{ $b->batch_number }}
                            </a>
                        </td>
                        <td style="font-size: 12.5px; color: #71717a;">{{ $b->production_date->format('d/m/Y') }}</td>
                        <td style="font-weight: 600; color: #09090b;">{{ $b->supplier->name ?? 'N/A' }}</td>
                        <td style="text-align: right;">
                            <span style="font-weight: 700;">{{ number_format($b->live_birds_weight_kg, 1, ',', '.') }} Kg</span>
                            <div style="font-size: 11px; color: #71717a;">({{ number_format($b->live_birds_count) }} Ekor)</div>
                        </td>
                        <td style="text-align: right;">
                            <span style="font-weight: 700;">Rp {{ number_format($b->buy_price_per_kg, 0, ',', '.') }}</span>
                            <div style="font-size: 11px; color: #71717a;">Total: Rp {{ number_format($b->total_buy_price, 0, ',', '.') }}</div>
                        </td>
                        <td style="text-align: right; color: {{ $b->doa_count > 0 ? '#dc2626' : '#71717a' }};">
                            {{ $b->doa_count > 0 ? number_format($b->doa_count) . ' Ekor (' . number_format($b->doa_weight_kg, 1) . ' Kg)' : '-' }}
                        </td>
                        <td style="text-align: right; font-weight: 700; color: #10b981;">
                            {{ number_format($b->total_yield_weight_kg, 1, ',', '.') }} Kg
                        </td>
                        <td style="text-align: right;">
                            <span class="badge {{ $b->shrinkage_percentage > 25 ? 'badge-amber' : 'badge-zinc' }}">
                                {{ number_format($b->shrinkage_percentage, 1, ',', '.') }}%
                            </span>
                        </td>
                        <td style="text-align: center;">
                            @if($b->supplier_payment_status === 'lunas')
                            <span class="badge badge-green"><span class="badge-dot"></span> Lunas</span>
                            @else
                            <span class="badge badge-red"><span class="badge-dot"></span> Belum Lunas</span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                <a href="{{ route('farm.production.show', $b->id) }}" class="ios-btn ios-btn-secondary" style="padding: 6px 10px; font-size: 11.5px;">
                                    Detail
                                </a>
                                <form method="POST" action="{{ route('farm.production.destroy', $b->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus batch produksi ini? Stok yang telah ditambahkan akan ditarik kembali.');" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="ios-btn ios-btn-danger" style="padding: 6px 10px; font-size: 11.5px;">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" style="text-align: center; color: #a1a1aa; padding: 32px;">Belum ada catatan batch produksi potong ayam</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
            {{ $batches->links() }}
        </div>
    </div>

</x-farm.layout>
