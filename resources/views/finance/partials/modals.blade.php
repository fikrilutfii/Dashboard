<!-- Pemasukan Modal -->
<div id="incomeModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 backdrop-blur-sm">
    <div class="relative top-20 mx-auto p-8 border w-[450px] shadow-2xl rounded-2xl bg-white">
        <div class="mb-6 flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-900">Input Pemasukan Manual</h3>
            <button onclick="document.getElementById('incomeModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="{{ route('finance.storePemasukan') }}" method="POST">
            @csrf
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
                        <option value="konfeksi" {{ session('division') == 'konfeksi' ? 'selected' : '' }}>Konveksi</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Entitas Keuangan</label>
                    <select name="entity" class="w-full border-gray-200 rounded-lg focus:ring-green-500 focus:border-green-500">
                        <option value="percetakan" {{ session('division') == 'percetakan' ? 'selected' : '' }}>Percetakan</option>
                        <option value="konfeksi" {{ session('division') == 'konfeksi' ? 'selected' : '' }}>Konveksi</option>
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
                        <option value="konfeksi" {{ session('division') == 'konfeksi' ? 'selected' : '' }}>Konveksi</option>
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
