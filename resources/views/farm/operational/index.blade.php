<x-farm-layout title="Operasional Peternakan" subtitle="Manajemen Batch Populasi, Pakan (FCR), Kesehatan, Produksi & Panen">

    {{-- Vaccine Reminder Notification Alert --}}
    @if(count($upcomingVaccines) > 0)
    <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded-xl shadow-sm mb-6">
        <div class="flex items-start gap-3">
            <svg class="w-6 h-6 text-amber-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
            </svg>
            <div class="flex-1">
                <h4 class="font-bold text-amber-900 text-sm">Reminder Jadwal Vaksinasi / Obat (Mendekati Due Date / Terlewat)</h4>
                <div class="mt-2 text-xs text-amber-800 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                    @foreach($upcomingVaccines as $v)
                    <div class="bg-white p-2.5 rounded-lg border border-amber-200 flex justify-between items-center">
                        <div>
                            <span class="font-bold text-gray-900">{{ $v->vaccine_name }}</span>
                            <div class="text-gray-500">{{ $v->coop->name ?? '-' }} ({{ $v->batch->batch_code ?? '-' }})</div>
                            <div class="font-semibold text-amber-700 mt-0.5">Tgl: {{ \Carbon\Carbon::parse($v->scheduled_date)->format('d/m/Y') }}</div>
                        </div>
                        <form action="{{ route('farm.operational.vaccine.complete', $v) }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-green-600 text-white text-[10px] px-2 py-1 rounded font-bold hover:bg-green-700">✓ Selesai</button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
            <p class="text-xs font-semibold text-gray-500 uppercase">Populasi Aktif Real-time</p>
            <h3 class="text-2xl font-bold text-amber-600 mt-1 font-mono">{{ number_format($totalActivePop) }} <span class="text-xs font-normal text-gray-500">ekor</span></h3>
        </div>
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
            <p class="text-xs font-semibold text-gray-500 uppercase">Mortalitas Bulan Ini</p>
            <h3 class="text-2xl font-bold text-red-600 mt-1 font-mono">{{ number_format($mortalityMonth) }} <span class="text-xs font-normal text-gray-500">ekor</span></h3>
        </div>
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
            <p class="text-xs font-semibold text-gray-500 uppercase">FCR Rata-rata (Broiler)</p>
            <h3 class="text-2xl font-bold text-blue-600 mt-1 font-mono">{{ $avgFcr > 0 ? $avgFcr : '-' }}</h3>
        </div>
        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
            <p class="text-xs font-semibold text-gray-500 uppercase">Produksi Telur Hari Ini</p>
            <h3 class="text-2xl font-bold text-emerald-600 mt-1 font-mono">{{ number_format($todayEggs) }} <span class="text-xs font-normal text-gray-500">butir</span></h3>
        </div>
    </div>

    {{-- Navigation Tabs --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-6" x-data="{ 
        activeTab: '{{ request('tab', 'batches') }}',
        modalBatch: false,
        modalFeed: false,
        modalHealth: false,
        modalVaccine: false,
        modalProd: false,
        modalHarvest: false
    }">
        {{-- Filter & Tab Buttons Header --}}
        <div class="p-4 bg-gray-50 border-b border-gray-200 flex flex-wrap gap-3 items-center justify-between">
            {{-- Tabs Nav --}}
            <div class="flex flex-wrap gap-1 bg-gray-200 p-1 rounded-lg">
                <button @click="activeTab = 'batches'" :class="activeTab === 'batches' ? 'bg-white text-gray-900 shadow font-bold' : 'text-gray-600 hover:text-gray-900'" class="px-3 py-1.5 rounded-md text-xs transition">
                    1. Populasi Batch
                </button>
                <button @click="activeTab = 'feed'" :class="activeTab === 'feed' ? 'bg-white text-gray-900 shadow font-bold' : 'text-gray-600 hover:text-gray-900'" class="px-3 py-1.5 rounded-md text-xs transition">
                    2. Pemberian Pakan (FCR)
                </button>
                <button @click="activeTab = 'health'" :class="activeTab === 'health' ? 'bg-white text-gray-900 shadow font-bold' : 'text-gray-600 hover:text-gray-900'" class="px-3 py-1.5 rounded-md text-xs transition">
                    3. Kesehatan & Vaksin
                </button>
                <button @click="activeTab = 'production'" :class="activeTab === 'production' ? 'bg-white text-gray-900 shadow font-bold' : 'text-gray-600 hover:text-gray-900'" class="px-3 py-1.5 rounded-md text-xs transition">
                    4. Produksi Telur / Weight
                </button>
                <button @click="activeTab = 'harvest'" :class="activeTab === 'harvest' ? 'bg-white text-gray-900 shadow font-bold' : 'text-gray-600 hover:text-gray-900'" class="px-3 py-1.5 rounded-md text-xs transition">
                    5. Stok Panen & Afkir
                </button>
            </div>

            {{-- Action Buttons based on active tab --}}
            <div class="flex gap-2">
                <template x-if="activeTab === 'batches'">
                    <button @click="modalBatch = true" class="bg-green-600 text-white font-bold px-3 py-1.5 rounded-lg hover:bg-green-700 text-xs shadow">+ Input Batch Populasi</button>
                </template>
                <template x-if="activeTab === 'feed'">
                    <button @click="modalFeed = true" class="bg-blue-600 text-white font-bold px-3 py-1.5 rounded-lg hover:bg-blue-700 text-xs shadow">+ Input Pakan Harian</button>
                </template>
                <template x-if="activeTab === 'health'">
                    <div class="flex gap-2">
                        <button @click="modalHealth = true" class="bg-red-600 text-white font-bold px-3 py-1.5 rounded-lg hover:bg-red-700 text-xs shadow">+ Catat Mortalitas/Kesehatan</button>
                        <button @click="modalVaccine = true" class="bg-purple-600 text-white font-bold px-3 py-1.5 rounded-lg hover:bg-purple-700 text-xs shadow">+ Jadwal Vaksin</button>
                    </div>
                </template>
                <template x-if="activeTab === 'production'">
                    <button @click="modalProd = true" class="bg-emerald-600 text-white font-bold px-3 py-1.5 rounded-lg hover:bg-emerald-700 text-xs shadow">+ Catat Produksi Harian</button>
                </template>
                <template x-if="activeTab === 'harvest'">
                    <button @click="modalHarvest = true" class="bg-amber-600 text-white font-bold px-3 py-1.5 rounded-lg hover:bg-amber-700 text-xs shadow">+ Catat Panen / Afkir</button>
                </template>
            </div>
        </div>

        {{-- Filter Kandang --}}
        <div class="p-3 bg-white border-b border-gray-200">
            <form method="GET" action="{{ route('farm.operational.index') }}" class="flex flex-wrap gap-3 items-center">
                <input type="hidden" name="tab" :value="activeTab">
                <select name="coop_id" class="border border-gray-300 rounded-lg px-3 py-1.5 text-xs bg-white">
                    <option value="">-- Semua Kandang --</option>
                    @foreach($coops as $c)
                        <option value="{{ $c->id }}" @selected(request('coop_id') == $c->id)>{{ $c->name }}</option>
                    @endforeach
                </select>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="border border-gray-300 rounded-lg px-3 py-1.5 text-xs">
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="border border-gray-300 rounded-lg px-3 py-1.5 text-xs">
                <button type="submit" class="bg-gray-800 text-white px-3 py-1.5 rounded-lg text-xs font-semibold">Filter</button>
                @if(request('coop_id') || request('date_from') || request('date_to'))
                    <a href="{{ route('farm.operational.index') }}" class="text-gray-500 hover:underline text-xs">Reset</a>
                @endif
            </form>
        </div>

        {{-- TAB 1: BATCH POPULASI KANDANG --}}
        <div x-show="activeTab === 'batches'">
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                    <thead>
                        <tr class="bg-gray-100 border-b border-gray-200 text-xs text-gray-600 uppercase font-semibold">
                            <th class="p-3 text-left">Kode Batch</th>
                            <th class="p-3 text-left">Kandang</th>
                            <th class="p-3 text-center">Tipe Ayam</th>
                            <th class="p-3 text-left">Tgl Masuk</th>
                            <th class="p-3 text-right">Populasi Awal</th>
                            <th class="p-3 text-right">Sisa Pop. Real-time</th>
                            <th class="p-3 text-center">Umur Ayam</th>
                            <th class="p-3 text-center">FCR Siklus</th>
                            <th class="p-3 text-center">Status</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($batches as $b)
                        @php $fcr = $b->calculateFcr(); @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 font-mono font-bold text-amber-600">{{ $b->batch_code }}</td>
                            <td class="p-3 font-semibold">{{ $b->coop->name ?? '-' }}</td>
                            <td class="p-3 text-center">
                                @if($b->type === 'broiler')
                                    <span class="bg-amber-100 text-amber-800 text-xs px-2 py-0.5 rounded font-bold uppercase">Broiler</span>
                                @else
                                    <span class="bg-purple-100 text-purple-800 text-xs px-2 py-0.5 rounded font-bold uppercase">Layer</span>
                                @endif
                            </td>
                            <td class="p-3 text-xs">{{ $b->entry_date->format('d/m/Y') }}</td>
                            <td class="p-3 text-right font-mono">{{ number_format($b->initial_population) }} ekor</td>
                            <td class="p-3 text-right font-mono font-bold text-emerald-600">{{ number_format($b->current_population) }} ekor</td>
                            <td class="p-3 text-center text-xs">Hari ke-{{ $b->age_days }}</td>
                            <td class="p-3 text-center font-mono font-bold text-blue-600">{{ $fcr > 0 ? $fcr : '-' }}</td>
                            <td class="p-3 text-center">
                                @if($b->status === 'aktif')
                                    <span class="bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded-full font-bold uppercase">Aktif</span>
                                @else
                                    <span class="bg-gray-100 text-gray-700 text-xs px-2 py-0.5 rounded-full font-bold uppercase">Selesai</span>
                                @endif
                            </td>
                            <td class="p-3 text-center">
                                @if($b->status === 'aktif')
                                <form action="{{ route('farm.operational.batch.close', $b) }}" method="POST" class="inline" onsubmit="return confirm('Tutup siklus batch populasi ini?');">
                                    @csrf
                                    <button type="submit" class="text-xs bg-gray-200 text-gray-800 px-2 py-1 rounded font-semibold hover:bg-gray-300">Tutup Siklus</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="10" class="p-6 text-center text-gray-500">Belum ada batch populasi kandang. Klik "+ Input Batch Populasi" di atas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TAB 2: PEMBERIAN PAKAN --}}
        <div x-show="activeTab === 'feed'">
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                    <thead>
                        <tr class="bg-gray-100 border-b border-gray-200 text-xs text-gray-600 uppercase font-semibold">
                            <th class="p-3 text-left">Tanggal</th>
                            <th class="p-3 text-left">Batch / Kandang</th>
                            <th class="p-3 text-left">Jenis Pakan</th>
                            <th class="p-3 text-right">Jumlah Pakan (Kg)</th>
                            <th class="p-3 text-left">Catatan</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($feedLogs as $fl)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 text-xs text-gray-600 font-semibold">{{ $fl->log_date->format('d/m/Y') }}</td>
                            <td class="p-3 font-semibold">{{ $fl->coop->name ?? '-' }} <span class="text-xs text-gray-500 font-normal">({{ $fl->batch->batch_code ?? '-' }})</span></td>
                            <td class="p-3 font-medium">{{ $fl->feed_type }}</td>
                            <td class="p-3 text-right font-mono font-bold text-blue-600">{{ number_format($fl->quantity_kg, 2) }} kg</td>
                            <td class="p-3 text-xs text-gray-500">{{ $fl->notes ?? '-' }}</td>
                            <td class="p-3 text-center">
                                <form action="{{ route('farm.operational.feed.destroy', $fl) }}" method="POST" class="inline delete-confirm">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 border border-red-200 text-xs px-2 py-1 rounded hover:bg-red-50">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="p-6 text-center text-gray-500">Belum ada riwayat konsumsi pakan. Klik "+ Input Pakan Harian" di atas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($feedLogs->hasPages())<div class="p-4 border-t border-gray-200">{{ $feedLogs->links() }}</div>@endif
        </div>

        {{-- TAB 3: KESEHATAN & VAKSINASI --}}
        <div x-show="activeTab === 'health'">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 p-4">
                {{-- Left: Log Mortalitas --}}
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <div class="p-3 bg-gray-100 font-bold text-xs text-gray-800 uppercase flex justify-between items-center">
                        <span>Catatan Mortalitas & Kesehatan Harian</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs">
                            <thead class="bg-gray-50 border-b border-gray-200 text-gray-600 font-semibold">
                                <tr>
                                    <th class="p-2 text-left">Tgl</th>
                                    <th class="p-2 text-left">Kandang</th>
                                    <th class="p-2 text-center">Mortalitas</th>
                                    <th class="p-2 text-center">Afkir</th>
                                    <th class="p-2 text-left">Penyebab / Pengobatan</th>
                                    <th class="p-2 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($healthLogs as $hl)
                                <tr>
                                    <td class="p-2">{{ $hl->log_date->format('d/m/Y') }}</td>
                                    <td class="p-2 font-semibold">{{ $hl->coop->name ?? '-' }}</td>
                                    <td class="p-2 text-center font-bold text-red-600">-{{ $hl->mortality_count }} ekor</td>
                                    <td class="p-2 text-center font-bold text-amber-600">{{ $hl->cull_count ? '-'.$hl->cull_count.' ekor' : '-' }}</td>
                                    <td class="p-2">
                                        @if($hl->cause)<div class="font-semibold text-gray-800">{{ $hl->cause }}</div>@endif
                                        <div class="text-gray-500">{{ $hl->treatment_notes ?? '' }}</div>
                                    </td>
                                    <td class="p-2 text-center">
                                        <form action="{{ route('farm.operational.health.destroy', $hl) }}" method="POST" class="inline delete-confirm">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="p-4 text-center text-gray-500">Belum ada catatan kesehatan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Right: Jadwal Vaksin --}}
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <div class="p-3 bg-gray-100 font-bold text-xs text-gray-800 uppercase flex justify-between items-center">
                        <span>Jadwal Vaksinasi & Obat</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs">
                            <thead class="bg-gray-50 border-b border-gray-200 text-gray-600 font-semibold">
                                <tr>
                                    <th class="p-2 text-left">Nama Vaksin/Obat</th>
                                    <th class="p-2 text-left">Kandang</th>
                                    <th class="p-2 text-left">Tgl Jadwal</th>
                                    <th class="p-2 text-center">Status</th>
                                    <th class="p-2 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($vaccineSchedules as $vs)
                                <tr>
                                    <td class="p-2 font-semibold text-gray-900">{{ $vs->vaccine_name }}</td>
                                    <td class="p-2">{{ $vs->coop->name ?? '-' }}</td>
                                    <td class="p-2 font-mono">{{ $vs->scheduled_date->format('d/m/Y') }}</td>
                                    <td class="p-2 text-center">
                                        @if($vs->status === 'selesai')
                                            <span class="bg-green-100 text-green-800 text-[10px] px-2 py-0.5 rounded font-bold">Selesai</span>
                                        @else
                                            <span class="bg-amber-100 text-amber-800 text-[10px] px-2 py-0.5 rounded font-bold">Pending</span>
                                        @endif
                                    </td>
                                    <td class="p-2 text-center">
                                        <div class="flex justify-center gap-1">
                                            @if($vs->status !== 'selesai')
                                            <form action="{{ route('farm.operational.vaccine.complete', $vs) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:underline">✓ Selesai</button>
                                            </form>
                                            @endif
                                            <form action="{{ route('farm.operational.vaccine.destroy', $vs) }}" method="POST" class="inline delete-confirm">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="p-4 text-center text-gray-500">Belum ada jadwal vaksinasi.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- TAB 4: PRODUKSI TELUR / WEIGHT SAMPLING --}}
        <div x-show="activeTab === 'production'">
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                    <thead>
                        <tr class="bg-gray-100 border-b border-gray-200 text-xs text-gray-600 uppercase font-semibold">
                            <th class="p-3 text-left">Tanggal</th>
                            <th class="p-3 text-left">Batch / Kandang</th>
                            <th class="p-3 text-center">Tipe</th>
                            <th class="p-3 text-right">Sampling Berat (Broiler)</th>
                            <th class="p-3 text-center">Rincian Telur (A / B / C)</th>
                            <th class="p-3 text-right">Total Telur (Butir / Kg)</th>
                            <th class="p-3 text-center">Egg Production Rate (%)</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($productionLogs as $pl)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 text-xs text-gray-600 font-semibold">{{ $pl->log_date->format('d/m/Y') }}</td>
                            <td class="p-3 font-semibold">{{ $pl->coop->name ?? '-' }} <span class="text-xs text-gray-500 font-normal">({{ $pl->batch->batch_code ?? '-' }})</span></td>
                            <td class="p-3 text-center">
                                @if($pl->batch && $pl->batch->type === 'broiler')
                                    <span class="bg-amber-100 text-amber-800 text-xs px-2 py-0.5 rounded font-bold uppercase">Broiler</span>
                                @else
                                    <span class="bg-purple-100 text-purple-800 text-xs px-2 py-0.5 rounded font-bold uppercase">Layer</span>
                                @endif
                            </td>
                            <td class="p-3 text-right font-mono font-bold text-gray-900">
                                {{ $pl->avg_weight_kg ? number_format($pl->avg_weight_kg, 2) . ' kg' : '-' }}
                            </td>
                            <td class="p-3 text-center text-xs">
                                @if($pl->total_egg_count > 0)
                                    Grade A: <strong>{{ $pl->egg_count_a }}</strong> | B: <strong>{{ $pl->egg_count_b }}</strong> | C: <strong>{{ $pl->egg_count_c }}</strong>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="p-3 text-right font-mono font-bold text-emerald-600">
                                @if($pl->total_egg_count > 0)
                                    {{ number_format($pl->total_egg_count) }} butir <span class="text-xs text-gray-500">({{ number_format($pl->total_egg_weight_kg, 1) }} kg)</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="p-3 text-center font-mono font-bold text-purple-600">
                                {{ $pl->egg_production_rate > 0 ? $pl->egg_production_rate . '%' : '-' }}
                            </td>
                            <td class="p-3 text-center">
                                <form action="{{ route('farm.operational.production.destroy', $pl) }}" method="POST" class="inline delete-confirm">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 border border-red-200 text-xs px-2 py-1 rounded hover:bg-red-50">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="p-6 text-center text-gray-500">Belum ada data produksi. Klik "+ Catat Produksi Harian" di atas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($productionLogs->hasPages())<div class="p-4 border-t border-gray-200">{{ $productionLogs->links() }}</div>@endif
        </div>

        {{-- TAB 5: STOK PANEN & AFKIR --}}
        <div x-show="activeTab === 'harvest'">
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse">
                    <thead>
                        <tr class="bg-gray-100 border-b border-gray-200 text-xs text-gray-600 uppercase font-semibold">
                            <th class="p-3 text-left">Tanggal Panen</th>
                            <th class="p-3 text-left">Batch / Kandang</th>
                            <th class="p-3 text-center">Tipe Panen</th>
                            <th class="p-3 text-right">Jumlah Ekor</th>
                            <th class="p-3 text-right">Total Berat (Kg)</th>
                            <th class="p-3 text-right">Rata-rata Berat</th>
                            <th class="p-3 text-right">Harga Acuan/Kg</th>
                            <th class="p-3 text-center">Status Penjualan</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($harvestLogs as $hl)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 text-xs text-gray-600 font-semibold">{{ $hl->harvest_date->format('d/m/Y') }}</td>
                            <td class="p-3 font-semibold">{{ $hl->coop->name ?? '-' }} <span class="text-xs text-gray-500 font-normal">({{ $hl->batch->batch_code ?? '-' }})</span></td>
                            <td class="p-3 text-center">
                                @if($hl->type === 'panen_broiler')
                                    <span class="bg-amber-100 text-amber-800 text-xs px-2 py-0.5 rounded font-bold uppercase">Panen Broiler</span>
                                @else
                                    <span class="bg-rose-100 text-rose-800 text-xs px-2 py-0.5 rounded font-bold uppercase">Afkir Layer</span>
                                @endif
                            </td>
                            <td class="p-3 text-right font-mono font-bold text-gray-900">{{ number_format($hl->chicken_count) }} ekor</td>
                            <td class="p-3 text-right font-mono font-bold text-emerald-600">{{ number_format($hl->total_weight_kg, 2) }} kg</td>
                            <td class="p-3 text-right font-mono text-xs">{{ number_format($hl->avg_weight_kg, 2) }} kg/ekor</td>
                            <td class="p-3 text-right font-mono text-xs">Rp {{ number_format($hl->reference_price_per_kg, 0, ',', '.') }}</td>
                            <td class="p-3 text-center">
                                @if($hl->status_penjualan === 'tersedia')
                                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded-full font-bold uppercase">Tersedia Stok</span>
                                @elseif($hl->status_penjualan === 'terjual_sebagian')
                                    <span class="bg-amber-100 text-amber-800 text-xs px-2 py-0.5 rounded-full font-bold uppercase">Terjual Sebagian</span>
                                @else
                                    <span class="bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded-full font-bold uppercase">Terjual (Faktur)</span>
                                @endif
                            </td>
                            <td class="p-3 text-center">
                                <form action="{{ route('farm.operational.harvest.destroy', $hl) }}" method="POST" class="inline delete-confirm">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 border border-red-200 text-xs px-2 py-1 rounded hover:bg-red-50">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="9" class="p-6 text-center text-gray-500">Belum ada riwayat panen/afkir. Klik "+ Catat Panen / Afkir" di atas.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($harvestLogs->hasPages())<div class="p-4 border-t border-gray-200">{{ $harvestLogs->links() }}</div>@endif
        </div>

        {{-- MODALS SECTION --}}

        {{-- Modal 1: Input Batch Populasi --}}
        <div x-show="modalBatch" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" x-cloak>
            <div class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6 mx-4">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Input Batch Populasi Masuk</h3>
                <form action="{{ route('farm.operational.batch.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Pilih Kandang <span class="text-red-500">*</span></label>
                            <select name="farm_coop_id" required class="w-full border border-gray-300 rounded-lg p-2 text-sm bg-white">
                                <option value="">-- Pilih --</option>
                                @foreach($coops as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }} (Kapasitas {{ number_format($c->capacity) }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Kode Batch / Siklus <span class="text-red-500">*</span></label>
                            <input type="text" name="batch_code" required value="BATCH-{{ now()->format('Ym') }}-{{ rand(100,999) }}" class="w-full border border-gray-300 rounded-lg p-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Tipe Ayam <span class="text-red-500">*</span></label>
                            <select name="type" required class="w-full border border-gray-300 rounded-lg p-2 text-sm bg-white">
                                <option value="broiler">Broiler (Ayam Pedaging)</option>
                                <option value="layer">Layer (Ayam Petelur)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Tanggal Masuk (DOC) <span class="text-red-500">*</span></label>
                            <input type="date" name="entry_date" required value="{{ date('Y-m-d') }}" class="w-full border border-gray-300 rounded-lg p-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Populasi Awal (Ekor) <span class="text-red-500">*</span></label>
                            <input type="number" min="1" name="initial_population" required placeholder="Contoh: 2000" class="w-full border border-gray-300 rounded-lg p-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Target Panen (Broiler)</label>
                            <input type="date" name="target_harvest_date" class="w-full border border-gray-300 rounded-lg p-2 text-sm">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Catatan</label>
                        <textarea name="notes" rows="2" class="w-full border border-gray-300 rounded-lg p-2 text-sm" placeholder="Catatan bibit DOC/supplier..."></textarea>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="modalBatch = false" class="border border-gray-300 px-4 py-2 rounded-lg text-sm">Batal</button>
                        <button type="submit" class="bg-green-600 text-white font-bold px-4 py-2 rounded-lg text-sm hover:bg-green-700">Simpan Batch</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal 2: Input Pakan Harian --}}
        <div x-show="modalFeed" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" x-cloak>
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 mx-4">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Input Pemberian Pakan Harian</h3>
                <form action="{{ route('farm.operational.feed.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Pilih Batch Kandang <span class="text-red-500">*</span></label>
                        <select name="farm_batch_id" required class="w-full border border-gray-300 rounded-lg p-2 text-sm bg-white">
                            <option value="">-- Pilih Batch --</option>
                            @foreach($activeBatches as $ab)
                                <option value="{{ $ab->id }}">{{ $ab->coop->name }} - {{ $ab->batch_code }} ({{ number_format($ab->current_population) }} ekor)</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Tanggal <span class="text-red-500">*</span></label>
                            <input type="date" name="log_date" required value="{{ date('Y-m-d') }}" class="w-full border border-gray-300 rounded-lg p-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Jumlah Pakan (Kg) <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" min="0.01" name="quantity_kg" required placeholder="100.5" class="w-full border border-gray-300 rounded-lg p-2 text-sm">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Jenis Pakan <span class="text-red-500">*</span></label>
                        <input type="text" name="feed_type" required placeholder="Contoh: Starter Broiler / Finisher / PAR-S" class="w-full border border-gray-300 rounded-lg p-2 text-sm">
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="modalFeed = false" class="border border-gray-300 px-4 py-2 rounded-lg text-sm">Batal</button>
                        <button type="submit" class="bg-blue-600 text-white font-bold px-4 py-2 rounded-lg text-sm hover:bg-blue-700">Simpan Pakan</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal 3a: Input Mortalitas / Kesehatan --}}
        <div x-show="modalHealth" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" x-cloak>
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 mx-4">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Input Catatan Mortalitas / Kesehatan</h3>
                <form action="{{ route('farm.operational.health.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Pilih Batch Kandang <span class="text-red-500">*</span></label>
                        <select name="farm_batch_id" required class="w-full border border-gray-300 rounded-lg p-2 text-sm bg-white">
                            <option value="">-- Pilih Batch --</option>
                            @foreach($activeBatches as $ab)
                                <option value="{{ $ab->id }}">{{ $ab->coop->name }} - {{ $ab->batch_code }} (Populasi {{ number_format($ab->current_population) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Tanggal <span class="text-red-500">*</span></label>
                            <input type="date" name="log_date" required value="{{ date('Y-m-d') }}" class="w-full border border-gray-300 rounded-lg p-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Mortalitas (Mati) <span class="text-red-500">*</span></label>
                            <input type="number" min="0" name="mortality_count" required value="0" class="w-full border border-gray-300 rounded-lg p-2 text-sm">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Afkir Harian (Ekor)</label>
                        <input type="number" min="0" name="cull_count" value="0" class="w-full border border-gray-300 rounded-lg p-2 text-sm">
                    </div>
                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Penyebab (Opsional)</label>
                        <input type="text" name="cause" placeholder="Contoh: Panas, Stress, Cacat" class="w-full border border-gray-300 rounded-lg p-2 text-sm">
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="modalHealth = false" class="border border-gray-300 px-4 py-2 rounded-lg text-sm">Batal</button>
                        <button type="submit" class="bg-red-600 text-white font-bold px-4 py-2 rounded-lg text-sm hover:bg-red-700">Simpan Catatan</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal 3b: Jadwal Vaksin --}}
        <div x-show="modalVaccine" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" x-cloak>
            <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6 mx-4">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Buat Jadwal Vaksinasi / Obat</h3>
                <form action="{{ route('farm.operational.vaccine.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Pilih Batch Kandang <span class="text-red-500">*</span></label>
                        <select name="farm_batch_id" required class="w-full border border-gray-300 rounded-lg p-2 text-sm bg-white">
                            <option value="">-- Pilih Batch --</option>
                            @foreach($activeBatches as $ab)
                                <option value="{{ $ab->id }}">{{ $ab->coop->name }} - {{ $ab->batch_code }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Nama Vaksin / Obat <span class="text-red-500">*</span></label>
                        <input type="text" name="vaccine_name" required placeholder="Contoh: ND-IB / Gumboro / Vitamin A" class="w-full border border-gray-300 rounded-lg p-2 text-sm">
                    </div>
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Tanggal Jadwal <span class="text-red-500">*</span></label>
                        <input type="date" name="scheduled_date" required value="{{ date('Y-m-d') }}" class="w-full border border-gray-300 rounded-lg p-2 text-sm">
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="modalVaccine = false" class="border border-gray-300 px-4 py-2 rounded-lg text-sm">Batal</button>
                        <button type="submit" class="bg-purple-600 text-white font-bold px-4 py-2 rounded-lg text-sm hover:bg-purple-700">Simpan Jadwal</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal 4: Input Produksi --}}
        <div x-show="modalProd" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" x-cloak>
            <div class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6 mx-4">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Input Produksi Harian / Sampling Berat</h3>
                <form action="{{ route('farm.operational.production.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Pilih Batch Kandang <span class="text-red-500">*</span></label>
                        <select name="farm_batch_id" required class="w-full border border-gray-300 rounded-lg p-2 text-sm bg-white">
                            <option value="">-- Pilih Batch --</option>
                            @foreach($activeBatches as $ab)
                                <option value="{{ $ab->id }}">{{ $ab->coop->name }} - {{ $ab->batch_code }} ({{ strtoupper($ab->type) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Tanggal Log <span class="text-red-500">*</span></label>
                        <input type="date" name="log_date" required value="{{ date('Y-m-d') }}" class="w-full border border-gray-300 rounded-lg p-2 text-sm">
                    </div>

                    <div class="border-t border-b border-gray-200 py-3 my-3">
                        <p class="text-xs font-bold text-gray-700 mb-2 uppercase">Untuk Layer (Produksi Telur):</p>
                        <div class="grid grid-cols-3 gap-2 mb-2">
                            <div>
                                <label class="block text-[10px] text-gray-600">Grade A (Butir)</label>
                                <input type="number" min="0" name="egg_count_a" value="0" class="w-full border border-gray-300 rounded p-1 text-sm">
                            </div>
                            <div>
                                <label class="block text-[10px] text-gray-600">Grade B (Butir)</label>
                                <input type="number" min="0" name="egg_count_b" value="0" class="w-full border border-gray-300 rounded p-1 text-sm">
                            </div>
                            <div>
                                <label class="block text-[10px] text-gray-600">Grade C (Butir)</label>
                                <input type="number" min="0" name="egg_count_c" value="0" class="w-full border border-gray-300 rounded p-1 text-sm">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] text-gray-600">Total Berat Telur (Kg)</label>
                            <input type="number" step="0.1" min="0" name="total_egg_weight_kg" value="0" class="w-full border border-gray-300 rounded p-1 text-sm">
                        </div>
                    </div>

                    <div class="mb-4">
                        <p class="text-xs font-bold text-gray-700 mb-1 uppercase">Untuk Broiler (Sampling Rata-rata Berat):</p>
                        <input type="number" step="0.01" min="0" name="avg_weight_kg" placeholder="Contoh: 1.65 (Kg)" class="w-full border border-gray-300 rounded p-2 text-sm">
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" @click="modalProd = false" class="border border-gray-300 px-4 py-2 rounded-lg text-sm">Batal</button>
                        <button type="submit" class="bg-emerald-600 text-white font-bold px-4 py-2 rounded-lg text-sm hover:bg-emerald-700">Simpan Produksi</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal 5: Input Panen / Afkir --}}
        <div x-show="modalHarvest" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" x-cloak>
            <div class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6 mx-4">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Input Catatan Panen Broiler / Afkir Layer</h3>
                <form action="{{ route('farm.operational.harvest.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Pilih Batch Kandang <span class="text-red-500">*</span></label>
                        <select name="farm_batch_id" required class="w-full border border-gray-300 rounded-lg p-2 text-sm bg-white">
                            <option value="">-- Pilih Batch --</option>
                            @foreach($activeBatches as $ab)
                                <option value="{{ $ab->id }}">{{ $ab->coop->name }} - {{ $ab->batch_code }} (Populasi {{ number_format($ab->current_population) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Tanggal Panen <span class="text-red-500">*</span></label>
                            <input type="date" name="harvest_date" required value="{{ date('Y-m-d') }}" class="w-full border border-gray-300 rounded-lg p-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Tipe Panen <span class="text-red-500">*</span></label>
                            <select name="type" required class="w-full border border-gray-300 rounded-lg p-2 text-sm bg-white">
                                <option value="panen_broiler">Panen Broiler</option>
                                <option value="afkir_layer">Afkir Layer (Ayam Tua)</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Jumlah Ayam Dipanen (Ekor) <span class="text-red-500">*</span></label>
                            <input type="number" min="1" name="chicken_count" required placeholder="500" class="w-full border border-gray-300 rounded-lg p-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Total Berat Timbangan (Kg) <span class="text-red-500">*</span></label>
                            <input type="number" step="0.1" min="0.1" name="total_weight_kg" required placeholder="850.5" class="w-full border border-gray-300 rounded-lg p-2 text-sm">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Harga Acuan per Kg (Rp) (Opsional)</label>
                        <input type="number" name="reference_price_per_kg" placeholder="22000" class="w-full border border-gray-300 rounded-lg p-2 text-sm">
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="modalHarvest = false" class="border border-gray-300 px-4 py-2 rounded-lg text-sm">Batal</button>
                        <button type="submit" class="bg-amber-600 text-white font-bold px-4 py-2 rounded-lg text-sm hover:bg-amber-700">Simpan Panen</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

</x-farm-layout>
