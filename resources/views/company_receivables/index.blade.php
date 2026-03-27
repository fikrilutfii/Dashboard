<x-app-layout>
    <x-slot name="header">Tagihan</x-slot>

    <div class="space-y-6">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl p-6 border border-zinc-100 shadow-sm">
                <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wider">Sisa Tagihan (Belum Diterima)</p>
                <h3 class="text-2xl font-bold text-amber-600 mt-2">Rp {{ number_format($totalBelumLunas, 0, ',', '.') }}</h3>
            </div>
            <div class="bg-white rounded-2xl p-6 border border-zinc-100 shadow-sm">
                <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wider">Total Sudah Diterima</p>
                <h3 class="text-2xl font-bold text-emerald-600 mt-2">Rp {{ number_format($totalLunas, 0, ',', '.') }}</h3>
            </div>
        </div>

        <!-- Table Card -->
        <div class="bg-white rounded-2xl border border-zinc-100 shadow-sm overflow-hidden">
            <div class="p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-zinc-100">
                <h3 class="text-lg font-bold text-zinc-800">Daftar Tagihan</h3>
                <a href="{{ route('company-receivables.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-semibold rounded-xl hover:bg-indigo-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Tambah Tagihan
                </a>
            </div>

            @if(session('success'))
                <div class="mx-6 mt-4 p-3 bg-emerald-50 text-emerald-700 rounded-xl text-sm border border-emerald-100">{{ session('success') }}</div>
            @endif

            <!-- Filter -->
            <div class="p-4 border-b border-zinc-100 bg-zinc-50">
                <form method="GET" class="flex flex-col sm:flex-row gap-3">
                    <select name="status" class="border rounded-md px-3 py-2 mr-2">
                        <option value="">Semua Status</option>
                        <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Belum Lunas</option>
                        <option value="sebagian" {{ request('status') == 'sebagian' ? 'selected' : '' }}>Sebagian</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                    </select>
                    <select name="type" class="text-sm border border-zinc-200 rounded-xl px-3 py-2 bg-white">
                        <option value="">Semua Jenis</option>
                        <option value="cash" {{ request('type') == 'cash' ? 'selected' : '' }}>Tunai</option>
                        <option value="installment" {{ request('type') == 'installment' ? 'selected' : '' }}>Cicilan</option>
                    </select>
                    <button type="submit" class="px-4 py-2 bg-zinc-800 text-white text-sm rounded-xl hover:bg-zinc-700">Terapkan</button>
                    <a href="{{ route('company-receivables.index') }}" class="px-4 py-2 text-zinc-500 text-sm rounded-xl hover:bg-zinc-100">Reset</a>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-zinc-50 border-b border-zinc-100">
                        <tr>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Debitur</th>
                            <th class="text-left px-6 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Jenis</th>
                            <th class="text-right px-6 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Total Tagihan</th>
                            <th class="text-right px-6 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Sisa Tagihan</th>
                            <th class="text-center px-6 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Status</th>
                            <th class="text-center px-6 py-3 text-xs font-semibold text-zinc-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100">
                        @forelse($receivables as $receivable)
                        <tr class="hover:bg-zinc-50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="font-semibold text-zinc-800">{{ $receivable->name }}</p>
                                @if($receivable->description)
                                    <p class="text-xs text-zinc-400 mt-0.5">{{ Str::limit($receivable->description, 60) }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-lg text-xs font-medium {{ $receivable->type == 'cash' ? 'bg-blue-50 text-blue-700' : 'bg-amber-50 text-amber-700' }}">
                                    {{ $receivable->type_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-semibold text-zinc-700">Rp {{ number_format($receivable->total_amount, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right font-bold {{ $receivable->remaining_amount > 0 ? 'text-red-600' : 'text-emerald-600' }}">
                                Rp {{ number_format($receivable->remaining_amount, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($receivable->status == 'paid')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" /></svg>
                                        Lunas
                                    </span>
                                @elseif($receivable->status == 'sebagian')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-100">
                                        Sebagian
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" /></svg>
                                        Belum Lunas
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2 flex-wrap">
                                    @if($receivable->status != 'paid')
                                        <!-- Quick Payment Form -->
                                        <div x-data="{ open: false }" class="relative">
                                            <button @click="open = !open" class="px-3 py-1 bg-amber-50 text-amber-700 text-xs font-semibold rounded-lg hover:bg-amber-100 transition-colors border border-amber-100">
                                                Catat Pembayaran
                                            </button>
                                            <div x-show="open" @click.away="open = false" class="absolute right-0 top-8 z-50 bg-white shadow-xl border border-zinc-100 rounded-xl p-4 w-56">
                                                <form action="{{ route('company-receivables.record-payment', $receivable) }}" method="POST" class="space-y-3">
                                                    @csrf
                                                    <p class="text-xs font-semibold text-zinc-600">Sisa: Rp {{ number_format($receivable->remaining_amount, 0, ',', '.') }}</p>
                                                    <input type="number" name="payment_amount" max="{{ $receivable->remaining_amount }}" min="1" class="w-full border border-zinc-200 rounded-lg px-3 py-1.5 text-sm" placeholder="Jumlah bayar">
                                                    <button type="submit" class="w-full py-1.5 bg-emerald-600 text-white text-xs font-semibold rounded-lg hover:bg-emerald-700">Simpan</button>
                                                </form>
                                            </div>
                                        </div>

                                        <form action="{{ route('company-receivables.mark-lunas', $receivable) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-lg hover:bg-emerald-100 transition-colors border border-emerald-100">
                                                Tandai Lunas
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('company-receivables.edit', $receivable) }}" class="px-3 py-1 bg-zinc-100 text-zinc-600 text-xs font-semibold rounded-lg hover:bg-zinc-200 transition-colors">Edit</a>
                                    <form action="{{ route('company-receivables.destroy', $receivable) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="px-3 py-1 bg-red-50 text-red-600 text-xs font-semibold rounded-lg hover:bg-red-100">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-zinc-400">
                                <p class="font-medium">Belum ada data tagihan.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-4 border-t border-zinc-100">
                {{ $receivables->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
