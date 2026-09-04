<!DOCTYPE html>
<html>
<head>
    <title>Laporan Kasbon Karyawan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #f4f4f4; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .badge { display: inline-block; padding: 2px 5px; border-radius: 3px; font-size: 10px; }
        .badge-blue { background-color: #e0f2fe; color: #0369a1; }
        .badge-purple { background-color: #f3e8ff; color: #7e22ce; }
        .badge-gray { background-color: #f3f4f6; color: #374151; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN KASBON KARYAWAN</h2>
        <p>Divisi: {{ $division ? ucfirst($division) : 'Semua Divisi' }} | Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
    </div>

    <div style="margin-bottom: 10px;">
        <strong>Total Kasbon (Outstanding): </strong> Rp {{ number_format($totalOutstanding, 0, ',', '.') }}
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Karyawan</th>
                <th>Tipe</th>
                <th class="text-right">Total Pinjaman</th>
                <th class="text-right">Sisa Pinjaman</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kasbons as $index => $kasbon)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $kasbon->date->format('d/m/Y') }}</td>
                <td>
                    <div class="font-bold">{{ $kasbon->employee->name }}</div>
                    <div style="font-size: 10px; color: #666;">{{ \Illuminate\Support\Str::limit($kasbon->description, 30) }}</div>
                </td>
                <td>
                    @if($kasbon->type == 'personal_credit')
                        <span class="badge badge-blue">Kredit Pribadi</span>
                    @elseif($kasbon->type == 'personal_loan')
                        <span class="badge badge-purple">Pinjaman Cash</span>
                    @else
                        <span class="badge badge-gray">Kasbon Staff</span>
                    @endif
                </td>
                <td class="text-right">Rp {{ number_format($kasbon->amount, 0, ',', '.') }}</td>
                <td class="text-right">
                    <span class="font-bold" style="{{ $kasbon->remaining_amount > 0 ? 'color: red;' : 'color: green;' }}">
                        Rp {{ number_format($kasbon->remaining_amount, 0, ',', '.') }}
                    </span>
                    @if($kasbon->installment_amount > 0)
                        <br><span style="font-size: 9px; color: #666;">Cicilan: Rp {{ number_format($kasbon->installment_amount, 0, ',', '.') }}/bln</span>
                    @endif
                </td>
                <td class="text-center">
                    @if($kasbon->remaining_amount > 0)
                        <span style="color: red; font-weight: bold; font-size: 10px;">BELUM LUNAS</span>
                    @else
                        <span style="color: green; font-weight: bold; font-size: 10px;">LUNAS</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada data kasbon.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
