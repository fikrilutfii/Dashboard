<table class="min-w-full">
    <thead class="bg-gray-50">
        <tr>
            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">{{ $label }}</th>
            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Jumlah (Rp)</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-100">
        <tr>
            <td class="px-6 py-4 text-sm text-gray-600">Total Pemasukan</td>
            <td class="px-6 py-4 text-sm text-right font-mono text-green-600 font-bold">Rp {{ number_format($data['pemasukan'], 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="px-6 py-4 text-sm text-gray-600">Total Pengeluaran</td>
            <td class="px-6 py-4 text-sm text-right font-mono text-red-600 font-bold">Rp {{ number_format($data['pembayaran'], 0, ',', '.') }}</td>
        </tr>
        <tr class="bg-gray-50">
            <td class="px-6 py-4 text-sm font-bold text-gray-900">Pengeluaran Bersih</td>
            <td class="px-6 py-4 text-sm text-right font-mono font-bold {{ $data['bersih'] >= 0 ? 'text-gray-900' : 'text-red-700' }}">
                Rp {{ number_format($data['bersih'], 0, ',', '.') }}
            </td>
        </tr>
    </tbody>
</table>
