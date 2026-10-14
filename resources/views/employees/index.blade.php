<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-[11px] font-black text-teal-800/60 uppercase tracking-[0.2em] block mb-1">Kepegawaian & HR</span>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">
                    Manajemen Karyawan
                </h2>
            </div>
            <div class="flex items-center gap-2.5">
                <a href="{{ route('payrolls.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white/70 border border-slate-200/80 text-slate-700 text-xs font-bold hover:bg-white shadow-sm active:scale-95 transition-all">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Lihat Penggajian
                </a>
                <a href="{{ route('employees.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-teal-700 hover:bg-teal-800 text-white text-xs font-bold shadow-sm shadow-teal-900/15 active:scale-95 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    + Karyawan Baru
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 space-y-6 max-w-7xl mx-auto page-enter">

        @if(session('success'))
            <div class="glass-card rounded-[20px] p-4 border border-emerald-200/80 bg-emerald-50/70 text-emerald-800 text-xs font-bold flex items-center gap-3">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="glass-card rounded-[20px] p-4 border border-rose-200/80 bg-rose-50/70 text-rose-800 text-xs font-bold flex items-center gap-3">
                <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Table Card -->
        <div class="glass-card rounded-[26px] overflow-hidden border border-white/70 shadow-sm">
            <div class="bg-white/40 px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider">Daftar Pegawai & Staff</h4>
                <span class="text-[11px] font-semibold text-slate-400">Total: {{ number_format($employees->total(), 0, ',', '.') }} orang</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/70 border-b border-slate-200/60 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                            <th class="px-5 py-3.5">Nama Pegawai</th>
                            <th class="px-5 py-3.5">Divisi</th>
                            <th class="px-5 py-3.5">Jabatan</th>
                            <th class="px-5 py-3.5 text-right">Gaji Pokok</th>
                            <th class="px-5 py-3.5 text-right">Lembur/Jam</th>
                            <th class="px-5 py-3.5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-xs text-slate-700">
                        @forelse($employees as $employee)
                            <tr class="hover:bg-teal-50/20 transition-colors">
                                <td class="px-5 py-3.5 font-bold text-slate-800">
                                    {{ $employee->name }}
                                </td>
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black uppercase bg-teal-50 text-teal-700 border border-teal-200/60">
                                        {{ $employee->division }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-slate-600 font-medium">
                                    {{ $employee->role ?? '-' }}
                                </td>
                                <td class="px-5 py-3.5 text-right font-mono font-bold text-slate-800 whitespace-nowrap">
                                    Rp {{ number_format($employee->salary_base, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-3.5 text-right font-mono text-slate-500 whitespace-nowrap">
                                    Rp {{ number_format($employee->overtime_rate, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-3.5 text-center whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1.5">
                                        <a href="{{ route('kasbons.create', ['employee_id' => $employee->id]) }}"
                                            class="px-2.5 py-1 rounded-lg bg-amber-50 border border-amber-200/80 text-amber-700 hover:bg-amber-100 font-bold text-[10px] transition-all">
                                            Kasbon
                                        </a>
                                        <a href="{{ route('employees.edit', $employee) }}"
                                            class="px-2.5 py-1 rounded-lg bg-white/70 border border-slate-200 text-teal-700 hover:bg-white font-bold text-[10px] transition-all">
                                            Edit
                                        </a>
                                        @if(Auth::user()->isAdmin())
                                            <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="inline-block delete-confirm">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-1 rounded-lg text-rose-500 hover:bg-rose-50 transition-all" title="Hapus Pegawai">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400 font-medium">
                                    <div class="max-w-xs mx-auto">
                                        <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                        Belum ada data pegawai atau karyawan.
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($employees->hasPages())
                <div class="px-6 py-4 bg-white/40 border-t border-slate-100">
                    {{ $employees->links() }}
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
