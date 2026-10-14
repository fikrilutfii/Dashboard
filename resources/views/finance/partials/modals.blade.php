<!-- Pemasukan Modal -->
<div id="incomeModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 backdrop-blur-sm">
    <div class="relative top-20 mx-auto p-8 border w-[450px] shadow-2xl rounded-2xl bg-white">
        <div class="mb-6 flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-900">Input Pemasukan Manual</h3>
            <button onclick="document.getElementById('incomeModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="{{ route('finance.storeTransaction') }}" method="POST">
            @csrf
            <input type="hidden" name="type" value="credit">
            <input type="hidden" name="payment_method" value="cash">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Jumlah (Rp)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-400">Rp</span>
                        <input type="number" name="amount" class="w-full pl-10 pr-4 py-2 border-gray-200 rounded-lg focus:ring-green-500 focus:border-green-500" required placeholder="0">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal</label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full border-gray-200 rounded-lg focus:ring-green-500 focus:border-green-500" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Divisi (Data Source)</label>
                    <select name="division" class="w-full border-gray-200 rounded-lg focus:ring-green-500 focus:border-green-500">
                        <option value="percetakan" {{ session('division') == 'percetakan' ? 'selected' : '' }}>Percetakan</option>
                        <option value="konfeksi" disabled>Konveksi (Belum Tersedia)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Entitas Keuangan</label>
                    <select name="entity" class="w-full border-gray-200 rounded-lg focus:ring-green-500 focus:border-green-500">
                        <option value="percetakan" {{ session('division') == 'percetakan' ? 'selected' : '' }}>Percetakan</option>
                        <option value="konfeksi" disabled>Konveksi (Belum Tersedia)</option>
                        <option value="pribadi">Pribadi</option>
                    </select>
                    <p class="text-[10px] text-gray-400 mt-1">*Pilih Pribadi jika transaksi ini adalah dana pribadi owner.</p>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Keterangan</label>
                    <textarea name="description" rows="3" class="w-full border-gray-200 rounded-lg focus:ring-green-500 focus:border-green-500" required placeholder="Contoh: Pembayaran sisa cetak..."></textarea>
                </div>
            </div>
            <div class="mt-8 flex gap-3">
                <button type="button" onclick="document.getElementById('incomeModal').classList.add('hidden')" class="flex-1 bg-gray-100 text-gray-600 px-4 py-2.5 rounded-lg font-bold hover:bg-gray-200 transition-all">Batal</button>
                <button type="submit" class="flex-1 bg-green-600 text-white px-4 py-2.5 rounded-lg font-bold hover:bg-green-700 shadow-md transition-all">Simpan Pemasukan</button>
            </div>
        </form>
    </div>
</div>

<!-- Loan Modal -->
<div id="loanModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 backdrop-blur-sm">
    <div class="relative top-20 mx-auto p-8 border w-[450px] shadow-2xl rounded-2xl bg-white">
        <div class="mb-6 flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-900">Catat Pinjaman Perusahaan</h3>
            <button onclick="document.getElementById('loanModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="{{ route('finance.storeLoan') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Pemberi Pinjaman</label>
                    <input type="text" name="creditor_name" class="w-full border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="Nama Bank / Leasing / Persona" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Jumlah Pinjaman (Rp)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-400">Rp</span>
                        <input type="number" name="amount" class="w-full pl-10 pr-4 py-2 border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required placeholder="0">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Tipe Pinjaman</label>
                    <select name="type" class="w-full border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="cash">Tunai (Masuk ke Kas)</option>
                        <option value="credit">Non-Tunai (Pembayaran Aset / Cicilan)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Entitas Penanggung Jawab</label>
                    <select name="entity" class="w-full border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="percetakan" {{ session('division') == 'percetakan' ? 'selected' : '' }}>Percetakan</option>
                        <option value="konfeksi" disabled>Konveksi (Belum Tersedia)</option>
                        <option value="pribadi">Pribadi</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal</label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Keterangan</label>
                    <textarea name="description" rows="2" class="w-full border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" placeholder="Keterangan tambahan..."></textarea>
                </div>
            </div>
            <div class="mt-8 flex gap-3">
                <button type="button" onclick="document.getElementById('loanModal').classList.add('hidden')" class="flex-1 bg-gray-100 text-gray-600 px-4 py-2.5 rounded-lg font-bold hover:bg-gray-200 transition-all">Batal</button>
                <button type="submit" class="flex-1 bg-indigo-600 text-white px-4 py-2.5 rounded-lg font-bold hover:bg-indigo-700 shadow-md transition-all">Simpan Pinjaman</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Catat Keuangan Terserap -->
<div id="terserapModal" class="fixed inset-0 bg-slate-900/60 overflow-y-auto h-full w-full hidden z-50 backdrop-blur-md">
    <div class="relative top-20 mx-auto p-6 md:p-8 border border-white/60 w-[95%] max-w-[480px] shadow-2xl rounded-[28px] bg-white/95 backdrop-blur-xl">
        <div class="mb-5 flex justify-between items-center pb-3 border-b border-slate-100">
            <div>
                <span class="text-[10px] font-black uppercase tracking-widest text-teal-800/60 block">Divisi Percetakan</span>
                <h3 class="text-lg font-black text-slate-800">Catat Keuangan Terserap</h3>
            </div>
            <button type="button" onclick="document.getElementById('terserapModal').classList.add('hidden')" class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 hover:text-slate-600 flex items-center justify-center transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="{{ route('finance.terserap.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Jumlah Dana yang Diserap (Rp) <span class="text-rose-500">*</span></label>
                <div class="relative">
                    <span class="absolute left-3 top-2.5 text-xs font-bold text-slate-400">Rp</span>
                    <input type="number" name="amount" min="1000" class="w-full pl-10 pr-3 py-2 text-sm font-mono font-bold border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600" required placeholder="0">
                </div>
                <p class="text-[10px] text-slate-400 mt-1">Uang fisik keluar dari kas percetakan, namun TIDAK dihitung sebagai beban rugi operasional percetakan.</p>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Divisi Peminjam / Keperluan <span class="text-rose-500">*</span></label>
                <input type="text" name="target" class="w-full px-3 py-2 text-xs border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600" placeholder="Contoh: Divisi Konfeksi (Bayar Hutang Kain)" required>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Penyerapan <span class="text-rose-500">*</span></label>
                <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 text-xs border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600" required>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Keterangan Tambahan</label>
                <textarea name="description" rows="2" class="w-full px-3 py-2 text-xs border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600" placeholder="Keterangan atau catatan pengembalian..."></textarea>
            </div>
            <div class="mt-6 flex gap-3 pt-2">
                <button type="button" onclick="document.getElementById('terserapModal').classList.add('hidden')" class="flex-1 bg-slate-100 text-slate-600 px-4 py-2.5 rounded-xl font-bold text-xs hover:bg-slate-200 transition-all">Batal</button>
                <button type="submit" class="flex-1 bg-rose-600 text-white px-4 py-2.5 rounded-xl font-bold text-xs hover:bg-rose-700 shadow-sm shadow-rose-600/30 transition-all">Simpan Dana Terserap</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Pengembalian Keuangan Terserap -->
<div id="repayTerserapModal" class="fixed inset-0 bg-slate-900/60 overflow-y-auto h-full w-full hidden z-50 backdrop-blur-md">
    <div class="relative top-20 mx-auto p-6 md:p-8 border border-white/60 w-[95%] max-w-[480px] shadow-2xl rounded-[28px] bg-white/95 backdrop-blur-xl">
        <div class="mb-5 flex justify-between items-center pb-3 border-b border-slate-100">
            <div>
                <span class="text-[10px] font-black uppercase tracking-widest text-emerald-800/60 block">Kas Percetakan</span>
                <h3 class="text-lg font-black text-slate-800">Catat Pengembalian Dana</h3>
            </div>
            <button type="button" onclick="document.getElementById('repayTerserapModal').classList.add('hidden')" class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 hover:text-slate-600 flex items-center justify-center transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="{{ route('finance.terserap.repay') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nominal yang Dikembalikan (Rp) <span class="text-rose-500">*</span></label>
                <div class="relative">
                    <span class="absolute left-3 top-2.5 text-xs font-bold text-slate-400">Rp</span>
                    <input type="number" name="amount" min="1000" class="w-full pl-10 pr-3 py-2 text-sm font-mono font-bold border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600" required placeholder="0">
                </div>
                <p class="text-[10px] text-slate-400 mt-1">Uang kembali masuk ke kas percetakan, dan TIDAK dihitung sebagai omzet penjualan fiktif.</p>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Sumber Pengembalian / Divisi</label>
                <input type="text" name="source" class="w-full px-3 py-2 text-xs border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600" placeholder="Contoh: Pengembalian Divisi Konfeksi">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Tanggal Pengembalian <span class="text-rose-500">*</span></label>
                <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 text-xs border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600" required>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Keterangan Tambahan</label>
                <textarea name="description" rows="2" class="w-full px-3 py-2 text-xs border border-slate-200 rounded-xl focus:ring-2 focus:ring-teal-500/20 focus:border-teal-600" placeholder="Contoh: Pelunasan dana talangan tahap 1..."></textarea>
            </div>
            <div class="mt-6 flex gap-3 pt-2">
                <button type="button" onclick="document.getElementById('repayTerserapModal').classList.add('hidden')" class="flex-1 bg-slate-100 text-slate-600 px-4 py-2.5 rounded-xl font-bold text-xs hover:bg-slate-200 transition-all">Batal</button>
                <button type="submit" class="flex-1 bg-emerald-600 text-white px-4 py-2.5 rounded-xl font-bold text-xs hover:bg-emerald-700 shadow-sm shadow-emerald-600/30 transition-all">Simpan Pengembalian</button>
            </div>
        </form>
    </div>
</div>
