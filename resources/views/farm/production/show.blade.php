<x-farm.layout title="Detail Batch Produksi" subtitle="{{ $batch->batch_number }}">

    <x-slot:headerActions>
        <a href="{{ route('farm.production.index') }}" class="ios-btn ios-btn-secondary">Kembali</a>
    </x-slot:headerActions>

    <div style="max-width: 900px; margin: 0 auto;">
        <!-- Header Info Card -->
        <div class="ios-card" style="padding: 28px; margin-bottom: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px;">
                <div>
                    <div style="font-size: 11px; font-weight: 700; color: #a37f38; text-transform: uppercase; letter-spacing: 0.08em;">Informasi Batch Produksi</div>
                    <div style="font-size: 22px; font-weight: 800; color: #09090b; margin-top: 2px;">{{ $batch->batch_number }}</div>
                    <div style="font-size: 13px; color: #71717a; margin-top: 4px;">Tanggal Produksi: <strong>{{ $batch->production_date->format('d/m/Y') }}</strong></div>
                    <div style="font-size: 13px; color: #09090b; margin-top: 4px;">Supplier: <strong>{{ $batch->supplier->name ?? 'N/A' }}</strong></div>
                </div>

                <div style="text-align: right;">
                    <div style="font-size: 11px; font-weight: 700; color: #71717a; text-transform: uppercase;">Status Bayar Supplier</div>
                    <div style="margin-top: 4px;">
                        @if($batch->supplier_payment_status === 'lunas')
                        <span class="badge badge-green"><span class="badge-dot"></span> Lunas</span>
                        @else
                        <span class="badge badge-red"><span class="badge-dot"></span> Belum Lunas</span>
                        @endif
                    </div>
                    <div style="font-size: 13px; color: #71717a; margin-top: 8px;">Total HPP Pembelian: <strong style="color: #09090b;">Rp {{ number_format($batch->total_buy_price, 0, ',', '.') }}</strong></div>
                    <div style="font-size: 12px; color: #71717a;">(Rp {{ number_format($batch->buy_price_per_kg, 0, ',', '.') }} / Kg)</div>
                </div>
            </div>
        </div>

        <!-- Metrics Overview Card -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
            <div class="stat-card" style="border-left: 4px solid #09090b;">
                <div style="font-size: 11px; font-weight: 700; color: #71717a; text-transform: uppercase;">Timbang Hidup (Input)</div>
                <div style="font-size: 20px; font-weight: 800; color: #09090b; margin-top: 4px;">{{ number_format($batch->live_birds_weight_kg, 1, ',', '.') }} Kg</div>
                <div style="font-size: 11px; color: #71717a; margin-top: 2px;">{{ number_format($batch->live_birds_count) }} Ekor</div>
            </div>

            <div class="stat-card" style="border-left: 4px solid #10b981;">
                <div style="font-size: 11px; font-weight: 700; color: #166534; text-transform: uppercase;">Hasil Potong (Yield)</div>
                <div style="font-size: 20px; font-weight: 800; color: #15803d; margin-top: 4px;">{{ number_format($batch->total_yield_weight_kg, 1, ',', '.') }} Kg</div>
                <div style="font-size: 11px; color: #166534; margin-top: 2px;">Karkas & Parting</div>
            </div>

            <div class="stat-card" style="border-left: 4px solid #f59e0b;">
                <div style="font-size: 11px; font-weight: 700; color: #92400e; text-transform: uppercase;">Susut Rendemen</div>
                <div style="font-size: 20px; font-weight: 800; color: #b45309; margin-top: 4px;">{{ number_format($batch->shrinkage_percentage, 1, ',', '.') }}%</div>
                <div style="font-size: 11px; color: #92400e; margin-top: 2px;">Selisih: {{ number_format($batch->shrinkage_weight_kg, 1, ',', '.') }} Kg</div>
            </div>

            <div class="stat-card" style="border-left: 4px solid #ef4444;">
                <div style="font-size: 11px; font-weight: 700; color: #991b1b; text-transform: uppercase;">Mati / DOA</div>
                <div style="font-size: 20px; font-weight: 800; color: #b91c1c; margin-top: 4px;">{{ number_format($batch->doa_count) }} Ekor</div>
                <div style="font-size: 11px; color: #991b1b; margin-top: 2px;">Berat: {{ number_format($batch->doa_weight_kg, 1, ',', '.') }} Kg</div>
            </div>
        </div>

        <!-- Breakdown Hasil Parting Table -->
        <div class="ios-card" style="padding: 24px; margin-bottom: 24px;">
            <h3 style="font-size: 15px; font-weight: 800; color: #09090b; margin: 0 0 16px;">Rincian Hasil Potong & Parting yang Ditambahkan ke Stok</h3>

            <div style="overflow-x: auto;">
                <table class="ios-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th style="text-align: right;">Jumlah (Ekor/Bungkus)</th>
                            <th style="text-align: right;">Berat Hasil (Kg)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($batch->items as $idx => $item)
                        <tr>
                            <td style="color: #71717a; font-size: 12px;">{{ $idx + 1 }}</td>
                            <td style="font-weight: 600; color: #71717a; font-size: 12px;">{{ $item->product->code ?? '-' }}</td>
                            <td style="font-weight: 700; color: #09090b;">{{ $item->product->name ?? 'N/A' }}</td>
                            <td style="font-size: 12px; color: #71717a; text-transform: uppercase;">{{ str_replace('_', ' ', $item->product->category ?? '') }}</td>
                            <td style="text-align: right; color: #71717a;">{{ $item->qty_ekor > 0 ? number_format($item->qty_ekor) : '-' }}</td>
                            <td style="text-align: right; font-weight: 800; color: #10b981;">{{ number_format($item->weight_kg, 1, ',', '.') }} Kg</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Delete Batch Option -->
        <div style="display: flex; justify-content: flex-end;">
            <form method="POST" action="{{ route('farm.production.destroy', $batch->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus batch produksi ini? Stok yang telah ditambahkan akan ditarik kembali.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="ios-btn ios-btn-danger" style="font-size: 12.5px;">Hapus Batch Produksi</button>
            </form>
        </div>
    </div>

</x-farm.layout>
