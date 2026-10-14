<x-app-layout>
    <x-slot name="header">Manajemen Akses</x-slot>

    <div class="space-y-6 pb-12">
        <!-- Top Action / Title Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-teal-50 border border-teal-100 text-teal-800 text-xs font-semibold mb-2">
                    <span class="w-2 h-2 rounded-full bg-teal-500"></span>
                    Pengaturan Keamanan & Peran Pengguna
                </div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Manajemen Akses Pengguna</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Ubah role dan batasan divisi akun. Perubahan berlaku langsung saat pengguna login kembali.</p>
            </div>
        </div>

        <!-- Metric Summary Cards -->
        @php
            $adminCount = $users->where('role', 'admin')->count();
            $opCount = $users->whereIn('role', ['limited_invoice', 'admin3'])->count();
            $viewerCount = $users->where('role', 'viewer')->count();
        @endphp
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="glass-card rounded-[22px] p-4 sm:p-5 flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-teal-600 to-emerald-800 flex items-center justify-center text-white shadow-md shadow-teal-700/20">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                </div>
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Total Pengguna</span>
                    <span class="text-xl sm:text-2xl font-black text-slate-800">{{ $users->count() }}</span>
                </div>
            </div>

            <div class="glass-card rounded-[22px] p-4 sm:p-5 flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-700 flex items-center justify-center text-white shadow-md shadow-emerald-700/20">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                    </svg>
                </div>
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Admin Penuh</span>
                    <span class="text-xl sm:text-2xl font-black text-emerald-700">{{ $adminCount }}</span>
                </div>
            </div>

            <div class="glass-card rounded-[22px] p-4 sm:p-5 flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-700 flex items-center justify-center text-white shadow-md shadow-blue-700/20">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6H2.25m0 0H3m0 0h1.5m0 0h15m0 0h1.5m0 0H21m-1.5 0v.75A.75.75 0 0118.75 6H18m0 0v12.75M3.75 6v12.75m16.5 0a2.25 2.25 0 01-2.25 2.25H4.5A2.25 2.25 0 012.25 18.75" />
                    </svg>
                </div>
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Kasir / Operasional</span>
                    <span class="text-xl sm:text-2xl font-black text-indigo-700">{{ $opCount }}</span>
                </div>
            </div>

            <div class="glass-card rounded-[22px] p-4 sm:p-5 flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center text-white shadow-md shadow-amber-700/20">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Viewer (Lihat Saja)</span>
                    <span class="text-xl sm:text-2xl font-black text-amber-700">{{ $viewerCount }}</span>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50/90 border border-emerald-200/90 text-emerald-800 text-xs font-bold flex items-center gap-2.5 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Main User Table Card -->
        <div class="glass-card rounded-[26px] overflow-hidden shadow-sm">
            <div class="p-5 sm:p-6 border-b border-slate-100/90 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h2 class="text-base font-extrabold text-slate-800">Daftar Pengguna & Hak Akses</h2>
                    <p class="text-xs text-slate-400 mt-0.5">Konfigurasikan peran fungsional dan akses divisi untuk masing-masing user.</p>
                </div>
                <div class="text-xs text-slate-500 font-medium bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-200/60">
                    Akun Login: <span class="font-extrabold text-teal-800">{{ auth()->user()->username }}</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-200/70 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            <th class="px-6 py-4">Nama Pengguna</th>
                            <th class="px-6 py-4">Username</th>
                            <th class="px-6 py-4">Role Akses</th>
                            <th class="px-6 py-4">Divisi Terbuka</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100/90 text-xs">
                        @foreach($users as $user)
                            <tr class="hover:bg-teal-50/20 transition-colors">
                                <!-- Nama Pengguna & Avatar -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-teal-700 to-emerald-800 text-white font-extrabold text-xs flex items-center justify-center shadow-xs">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-800 text-xs">{{ $user->name }}</div>
                                            <div class="text-[11px] text-slate-400">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Username -->
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-mono text-xs font-semibold px-2.5 py-1 rounded-lg bg-slate-100/80 text-slate-700 border border-slate-200/60">
                                        {{ $user->username ?: '-' }}
                                    </span>
                                </td>

                                @if($user->username === 'tracker')
                                    <td class="px-6 py-4" colspan="3">
                                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-teal-50 border border-teal-200/70 text-teal-800 text-xs font-extrabold">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-teal-600">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                            </svg>
                                            Super Administrator / Tracker (Hak akses tetap)
                                        </div>
                                    </td>
                                @else
                                    <!-- Form Edit Access -->
                                    <form action="{{ route('user-access.update', $user) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <!-- Role Akses -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <select name="role" class="text-xs bg-white/95 border border-slate-200 rounded-xl px-3 py-2 text-slate-700 font-semibold focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all shadow-2xs">
                                                @foreach($roles as $value => $label)
                                                    <option value="{{ $value }}" @selected($user->role === $value)>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </td>

                                        <!-- Divisi Terbuka -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <select name="allowed_division" class="text-xs bg-white/95 border border-slate-200 rounded-xl px-3 py-2 text-slate-700 font-semibold focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600 transition-all shadow-2xs">
                                                <option value="all" @selected($user->allowed_division === 'all')>Semua divisi</option>
                                                <option value="percetakan" @selected($user->allowed_division === 'percetakan')>Percetakan</option>
                                                <option value="konfeksi" @selected($user->allowed_division === 'konfeksi')>Konfeksi</option>
                                                <option value="peternakan" @selected($user->allowed_division === 'peternakan')>Peternakan Ayam</option>
                                            </select>
                                        </td>

                                        <!-- Aksi Simpan -->
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <button type="submit" class="bg-gradient-to-r from-teal-700 to-emerald-800 hover:from-teal-800 hover:to-emerald-900 text-white font-extrabold text-xs px-4 py-2 rounded-xl shadow-sm transition-all active:scale-95 inline-flex items-center gap-1.5">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                </svg>
                                                Simpan
                                            </button>
                                        </td>
                                    </form>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
