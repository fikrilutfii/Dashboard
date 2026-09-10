<x-farm.layout title="Penggajian Karyawan RPA" subtitle="Skema fleksibel: Borongan per Kg/Ekor, Gaji Harian, dan Gaji Bulanan">

    <x-slot:headerActions>
        <a href="{{ route('farm.payroll.create') }}" class="ios-btn ios-btn-primary">
            + Buat Slip Gaji Baru
        </a>
    </x-slot:headerActions>

    <!-- Filter Bar -->
    <div class="ios-card" style="padding: 16px 20px; margin-bottom: 20px;">
        <form method="GET" action="{{ route('farm.payroll.index') }}" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center;">
            <div style="width: 180px;">
                <select name="salary_type" class="ios-input">
                    <option value="">Semua Skema Gaji</option>
                    <option value="borongan" {{ request('salary_type') === 'borongan' ? 'selected' : '' }}>Borongan (Kg / Ekor)</option>
                    <option value="harian" {{ request('salary_type') === 'harian' ? 'selected' : '' }}>Gaji Harian</option>
                    <option value="bulanan" {{ request('salary_type') === 'bulanan' ? 'selected' : '' }}>Gaji Bulanan</option>
                </select>
            </div>

            <div style="width: 140px;">
                <select name="month" class="ios-input">
                    <option value="">Bulan</option>
                    @for($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
                    @endfor
                </select>
            </div>

            <div style="width: 120px;">
                <select name="year" class="ios-input">
                    <option value="">Tahun</option>
                    @for($y = date('Y') - 1; $y <= date('Y') + 1; $y++)
                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <button type="submit" class="ios-btn ios-btn-secondary">Filter</button>
            @if(request()->hasAny(['salary_type', 'month', 'year']))
            <a href="{{ route('farm.payroll.index') }}" class="ios-btn ios-btn-secondary" style="color: #71717a;">Reset</a>
            @endif
        </form>
    </div>

    <!-- Payroll Table -->
    <div class="ios-card" style="padding: 20px 24px;">
        <div style="overflow-x: auto;">
            <table class="ios-table">
                <thead>
                    <tr>
                        <th>Nama Karyawan</th>
                        <th>Jabatan / Role</th>
                        <th>Skema Gaji</th>
                        <th>Periode Kerja</th>
                        <th style="text-align: right;">Produktivitas / Hari</th>
                        <th style="text-align: right;">Gaji Bersih</th>
                        <th style="text-align: center;">Status</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payrolls as $p)
                    <tr>
                        <td style="font-weight: 700; color: #09090b;">{{ $p->employee_name }}</td>
                        <td style="font-size: 12.5px; color: #71717a;">{{ $p->role ?? '-' }}</td>
                        <td>
                            @if($p->salary_type === 'borongan')
                            <span class="badge badge-amber"><span class="badge-dot"></span> Borongan</span>
                            @elseif($p->salary_type === 'harian')
                            <span class="badge badge-blue"><span class="badge-dot"></span> Harian</span>
                            @else
                            <span class="badge badge-zinc"><span class="badge-dot"></span> Bulanan</span>
                            @endif
                        </td>
                        <td style="font-size: 12.5px; color: #71717a;">
                            {{ $p->period_start->format('d/m/Y') }} - {{ $p->period_end->format('d/m/Y') }}
                        </td>
                        <td style="text-align: right; font-size: 12.5px;">
                            @if($p->salary_type === 'borongan')
                                @if($p->total_kg_produced > 0)
                                    <strong>{{ number_format($p->total_kg_produced, 1, ',', '.') }}</strong> Kg
                                @elseif($p->total_ekor_produced > 0)
                                    <strong>{{ number_format($p->total_ekor_produced) }}</strong> Ekor
                                @endif
                            @elseif($p->salary_type === 'harian')
                                <strong>{{ $p->work_days }}</strong> Hari
                            @else
                                1 Bulan
                            @endif
                        </td>
                        <td style="text-align: right; font-weight: 800; color: #09090b;">
                            Rp {{ number_format($p->net_salary, 0, ',', '.') }}
                        </td>
                        <td style="text-align: center;">
                            @if($p->status === 'dibayar')
                            <span class="badge badge-green"><span class="badge-dot"></span> Dibayar</span>
                            @else
                            <span class="badge badge-red"><span class="badge-dot"></span> Pending</span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                <a href="{{ route('farm.payroll.show', $p->id) }}" class="ios-btn ios-btn-secondary" style="padding: 6px 10px; font-size: 11.5px;">
                                    Slip Gaji
                                </a>

                                @if($p->status !== 'dibayar')
                                <form method="POST" action="{{ route('farm.payroll.mark-paid', $p->id) }}" onsubmit="return confirm('Tandai gaji ini sudah dibayar? Pengeluaran kas akan otomatis dicatat.');" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="ios-btn ios-btn-gold" style="padding: 6px 10px; font-size: 11.5px;">Bayar</button>
                                </form>
                                @endif

                                <form method="POST" action="{{ route('farm.payroll.destroy', $p->id) }}" onsubmit="return confirm('Hapus data slip gaji ini?');" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="ios-btn ios-btn-danger" style="padding: 6px 10px; font-size: 11.5px;">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align: center; color: #a1a1aa; padding: 32px;">Belum ada data slip penggajian</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px;">
            {{ $payrolls->links() }}
        </div>
    </div>

</x-farm.layout>
