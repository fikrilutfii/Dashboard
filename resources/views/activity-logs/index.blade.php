<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Audit & Log Aktivitas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Riwayat Sistem (Audit Trail)</h3>
                            <p class="text-sm text-gray-600">Melacak seluruh perubahan data dan aktivitas masuk/keluar.</p>
                        </div>
                        
                        <!-- Form Pencarian & Filter -->
                        <form method="GET" action="{{ route('activity-logs.index') }}" class="w-full md:w-auto grid grid-cols-1 md:flex gap-2">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari aktivitas..." class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 text-sm"/>
                            
                            <select name="user_id" class="rounded-md border-gray-300 shadow-sm text-sm">
                                <option value="">Semua User</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                @endforeach
                            </select>

                            <select name="module" class="rounded-md border-gray-300 shadow-sm text-sm">
                                <option value="">Semua Modul</option>
                                <option value="Invoice" {{ request('module') == 'Invoice' ? 'selected' : '' }}>Faktur</option>
                                <option value="Product" {{ request('module') == 'Product' ? 'selected' : '' }}>Produk</option>
                                <option value="Customer" {{ request('module') == 'Customer' ? 'selected' : '' }}>Pelanggan</option>
                                <option value="Supplier" {{ request('module') == 'Supplier' ? 'selected' : '' }}>Pemasok</option>
                            </select>

                            <input type="date" name="date" value="{{ request('date') }}" class="rounded-md border-gray-300 shadow-sm text-sm" />

                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow text-sm">Filter</button>
                            
                            @if(request('search') || request('user_id') || request('date') || request('module'))
                                <a href="{{ route('activity-logs.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-2 px-4 rounded border text-sm text-center">Reset</a>
                            @endif
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full leading-normal">
                            <thead>
                                <tr>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Waktu</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Pengguna</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Modul</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Aktivitas</th>
                                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-50 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Detail Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($logs as $log)
                                    @php
                                        $badgeClass = 'bg-gray-100 text-gray-800 border-gray-200';
                                        
                                        $act = strtolower($log->activity);
                                        if (str_contains($act, 'create') || str_contains($act, 'login')) {
                                            $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                        } elseif (str_contains($act, 'update') || str_contains($act, 'edit') || str_contains($act, 'change')) {
                                            $badgeClass = 'bg-amber-50 text-amber-700 border-amber-200';
                                        } elseif (str_contains($act, 'delete') || str_contains($act, 'logout')) {
                                            $badgeClass = 'bg-rose-50 text-rose-700 border-rose-200';
                                        }

                                        $properties = json_decode($log->properties, true);
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-5 py-4 border-b border-gray-200 text-sm whitespace-nowrap">
                                            <div class="font-bold text-gray-900">{{ $log->created_at->setTimezone('Asia/Jakarta')->format('H:i') }}</div>
                                            <div class="text-xs text-gray-500">{{ $log->created_at->setTimezone('Asia/Jakarta')->format('d M Y') }}</div>
                                        </td>
                                        <td class="px-5 py-4 border-b border-gray-200 text-sm">
                                            @if($log->user)
                                                <div class="font-bold text-indigo-700">{{ $log->user->name }}</div>
                                                <div class="text-xs text-gray-500">{{ $log->ip_address ?? '-' }}</div>
                                            @else
                                                <span class="text-gray-400 italic">Sistem / Dihapus</span>
                                            @endif
                                        </td>
                                        <td class="px-5 py-4 border-b border-gray-200 text-sm font-semibold text-gray-700">
                                            {{ $log->subject_type ? class_basename($log->subject_type) : 'Auth/Sistem' }}
                                        </td>
                                        <td class="px-5 py-4 border-b border-gray-200 text-sm whitespace-nowrap">
                                            <span class="px-3 py-1 inline-flex text-xs font-black rounded-full border uppercase tracking-wider {{ $badgeClass }}">
                                                {{ $log->activity }}
                                            </span>
                                            <div class="mt-1 text-xs text-gray-600 truncate max-w-[200px]" title="{{ $log->description }}">
                                                {{ $log->description }}
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 border-b border-gray-200 text-sm">
                                            @if($properties && (isset($properties['old']) || isset($properties['new'])))
                                                <button onclick="toggleDetails('details-{{ $log->id }}')" class="text-indigo-600 hover:text-indigo-900 text-xs font-bold underline">
                                                    Lihat Perubahan Data
                                                </button>
                                                <div id="details-{{ $log->id }}" class="hidden mt-2 p-3 bg-gray-50 rounded-lg border border-gray-200 overflow-x-auto max-w-sm text-xs space-y-2">
                                                    @if(!empty($properties['old']))
                                                        <div>
                                                            <span class="font-bold text-rose-600">Sebelumnya:</span>
                                                            <pre class="mt-1 p-2 bg-white rounded border whitespace-pre-wrap">{{ json_encode($properties['old'], JSON_PRETTY_PRINT) }}</pre>
                                                        </div>
                                                    @endif
                                                    @if(!empty($properties['new']))
                                                        <div>
                                                            <span class="font-bold text-emerald-600">Sesudahnya:</span>
                                                            <pre class="mt-1 p-2 bg-white rounded border whitespace-pre-wrap">{{ json_encode($properties['new'], JSON_PRETTY_PRINT) }}</pre>
                                                        </div>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-gray-400 text-xs italic">Tidak ada detail data</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-5 py-8 border-b border-gray-200 bg-white text-sm text-center text-gray-500">
                                            Tidak ada riwayat log aktivitas.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $logs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleDetails(id) {
            const el = document.getElementById(id);
            if (el.classList.contains('hidden')) {
                el.classList.remove('hidden');
            } else {
                el.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>
