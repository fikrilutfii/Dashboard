<x-farm-layout title="Tambah Data Penggajian" subtitle="Input Rekapitulasi Gaji Pegawai Peternakan">

    <div class="max-w-3xl mx-auto">
        <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] border border-white/60 shadow-premium p-8">
            <form action="{{ route('farm.payroll.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-[11px] font-black text-zinc-400 uppercase tracking-widest mb-2">Nama Pegawai <span class="text-red-500">*</span></label>
                        <input type="text" name="employee_name" value="{{ old('employee_name') }}" required placeholder="Nama lengkap pegawai"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-500 bg-white/80 @error('employee_name') border-red-400 @enderror">
                        @error('employee_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-[11px] font-black text-zinc-400 uppercase tracking-widest mb-2">Jabatan / Role</label>
                        <input type="text" name="role" value="{{ old('role', 'Staf Lapangan') }}" placeholder="Contoh: Staf Lapangan, Kepala Unit, Teknisi"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-500 bg-white/80">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-[11px] font-black text-zinc-400 uppercase tracking-widest mb-2">Periode Mulai <span class="text-red-500">*</span></label>
                        <input type="date" name="period_start" value="{{ old('period_start', date('Y-m-01')) }}" required
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-500 bg-white/80">
                    </div>
                    <div>
                        <label class="block text-[11px] font-black text-zinc-400 uppercase tracking-widest mb-2">Periode Selesai <span class="text-red-500">*</span></label>
                        <input type="date" name="period_end" value="{{ old('period_end', date('Y-m-t')) }}" required
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-500 bg-white/80">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <label class="block text-[11px] font-black text-zinc-400 uppercase tracking-widest mb-2">Gaji Pokok (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" id="basic_salary" name="basic_salary" value="{{ old('basic_salary', 0) }}" required min="0" oninput="calcNet()"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-500 bg-white/80 @error('basic_salary') border-red-400 @enderror">
                    </div>
                    <div>
                        <label class="block text-[11px] font-black text-zinc-400 uppercase tracking-widest mb-2">Tunjangan / Bonus (Rp)</label>
                        <input type="number" id="allowances" name="allowances" value="{{ old('allowances', 0) }}" min="0" oninput="calcNet()"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-500 bg-white/80">
                    </div>
                    <div>
                        <label class="block text-[11px] font-black text-zinc-400 uppercase tracking-widest mb-2">Potongan / Kasbon (Rp)</label>
                        <input type="number" id="deductions" name="deductions" value="{{ old('deductions', 0) }}" min="0" oninput="calcNet()"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-500 bg-white/80">
                    </div>
                </div>

                {{-- Net Salary Preview --}}
                <div class="flex items-center justify-between bg-amber-50 border border-amber-100 rounded-2xl px-6 py-5 mb-6">
                    <div>
                        <div class="text-[10px] font-black text-amber-700 uppercase tracking-widest">Estimasi Gaji Bersih (Net)</div>
                        <div class="text-xs text-amber-600 mt-0.5">Gaji Pokok + Tunjangan − Potongan</div>
                    </div>
                    <div id="netSalaryDisplay" class="text-2xl font-black text-amber-700">Rp 0</div>
                </div>

                <div class="mb-8">
                    <label class="block text-[11px] font-black text-zinc-400 uppercase tracking-widest mb-2">Catatan Tambahan</label>
                    <textarea name="notes" rows="3" placeholder="Opsional: keterangan lembur, bonus panen, dsb..."
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500/30 focus:border-primary-500 bg-white/80 resize-none">{{ old('notes') }}</textarea>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('farm.payroll.index') }}" class="border border-gray-200 text-zinc-600 font-bold px-6 py-3 rounded-xl hover:bg-gray-50 transition-all text-sm">Batal</a>
                    <button type="submit" class="bg-primary-600 text-white font-black px-8 py-3 rounded-xl hover:bg-primary-700 transition-all shadow-lg shadow-primary-500/20 text-sm">Simpan Data Gaji</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function calcNet() {
            const basic = parseFloat(document.getElementById('basic_salary').value) || 0;
            const allow = parseFloat(document.getElementById('allowances').value) || 0;
            const ded   = parseFloat(document.getElementById('deductions').value) || 0;
            const net   = basic + allow - ded;
            document.getElementById('netSalaryDisplay').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(net);
        }
        calcNet();
    </script>
</x-farm-layout>
