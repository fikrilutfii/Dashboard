<x-farm.layout title="Pengeluaran RPA" subtitle="Catat kas keluar operasional RPA (Es balok, plastik, gas scalder, dll)">

    <x-slot:headerActions>
        <a href="{{ route('farm.expenses.create') }}" class="ios-btn ios-btn-primary">
            + Catat Pengeluaran Baru
        </a>
    </x-slot:headerActions>

    <!-- Filter Bar & Summary -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; margin-bottom: 20px;">
        <div class="stat-card" style="border-left: 4px solid #6366f1;">
            <div style="font-size: 11px; font-weight: 700; color: #71717a; text-transform: uppercase;">Total Pengeluaran Kas (Sesuai Filter)</div>
            <div style="font-size: 22px; font-weight: 800; color: #09090b; margin-top: 4px;">Rp {{ number_format($totalExpense, 0, ',', '.') }}</div>
        </div>

        <div class="ios-card" style="padding: 16px 20px; display: flex; align-items: center;">
            <form method="GET" action="{{ route('farm.expenses.index') }}" style="display: flex; gap: 10px; flex-wrap: wrap; width: 100%; align-items: center;">
                <div style="flex: 1; min-width: 160px;">
                    <select name="category" class="ios-input">
                        <option value="">Semua Kategori</option>
                        <option value="operasional_rpa" {{ request('category') === 'operasional_rpa' ? 'selected' : '' }}>Operasional RPA (Es, Plastik, Gas, Kuli)</option>
                        <option value="administrasi" {{ request('category') === 'administrasi' ? 'selected' : '' }}>Administrasi & Umum</option>
                        <option value="armada_umum" {{ request('category') === 'armada_umum' ? 'selected' : '' }}>Armada (Servis/Pajak)</option>
                        <option value="lain" {{ request('category') === 'lain' ? 'selected' : '' }}>Lain-lain</option>
                    </select>
                </div>
                <div style="width: 140px;">
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="ios-input">
                </div>
                <div style="width: 140px;">
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="ios-input">
                </div>
                <button type="submit" class="ios-btn ios-btn-secondary">Filter</button>
            </form>
        </div>
    </div>

    <!-- Expenses Table -->
    <div class="ios-card" style="padding: 20px 24px;">
        <div style="overflow-x: auto;">
            <table class="ios-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kategori</th>
                        <th>Subkategori / Jenis</th>
                        <th>Keterangan</th>
                        <th style="text-align: right;">Nominal</th>
                        <th>Metode Bayar</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $e)
                    <tr>
                        <td style="font-size: 13px; font-weight: 600;">{{ $e->expense_date->format('d/m/Y') }}</td>
                        <td>
                            @if($e->category === 'operasional_rpa')
                            <span class="badge badge-amber"><span class="badge-dot"></span> Operasional RPA</span>
                            @elseif($e->category === 'administrasi')
                            <span class="badge badge-blue"><span class="badge-dot"></span> Administrasi</span>
                            @elseif($e->category === 'armada_umum')
                            <span class="badge badge-zinc"><span class="badge-dot"></span> Armada</span>
                            @else
                            <span class="badge badge-zinc"><span class="badge-dot"></span> Lain-lain</span>
                            @endif
                        </td>
                        <td style="font-weight: 600; color: #09090b;">{{ $e->subcategory ?? '-' }}</td>
                        <td style="color: #52525b; font-size: 13px;">{{ $e->description }}</td>
                        <td style="text-align: right; font-weight: 800; color: #09090b;">Rp {{ number_format($e->amount, 0, ',', '.') }}</td>
                        <td style="font-size: 12.5px; color: #71717a;">{{ $e->payment_method }}</td>
                        <td style="text-align: right;">
                            <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                <a href="{{ route('farm.expenses.edit', $e->id) }}" class="ios-btn ios-btn-secondary" style="padding: 6px 10px; font-size: 11.5px;">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('farm.expenses.destroy', $e->id) }}" onsubmit="return confirm('Hapus catatan pengeluaran ini?');" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="ios-btn ios-btn-danger" style="padding: 6px 10px; font-size: 11.5px;">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: #a1a1aa; padding: 32px;">Belum ada catatan pengeluaran kas</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
            {{ $expenses->links() }}
        </div>
    </div>

</x-farm.layout>
