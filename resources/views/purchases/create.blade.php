<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Input Pembelian Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('purchases.store') }}" method="POST" onsubmit="return validateForm()">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Left Column -->
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">No. Purchase / Nota</label>
                                    <input type="text" name="purchase_number" class="w-full border rounded px-3 py-2" placeholder="Contoh: PO-001" required>
                                </div>
                                
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Divisi</label>
                                    <input type="text" value="{{ ucfirst(session('division', 'Percetakan')) }}" class="w-full border rounded px-3 py-2 bg-gray-100 text-gray-500" readonly>
                                    <input type="hidden" name="division" value="{{ session('division', 'percetakan') }}">
                                </div>

                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Supplier</label>
                                    <select name="supplier_id" class="w-full border rounded px-3 py-2">
                                        <option value="">-- Tanpa Supplier (Umum) --</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika beli eceran/umum.</p>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal</label>
                                    <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full border rounded px-3 py-2" required>
                                </div>
                                
                                <div class="p-4 bg-gray-50 border rounded-lg">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Status Pembayaran</label>
                                    <div class="flex gap-4">
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" name="payment_status" value="cash" checked class="mr-2 w-5 h-5 text-green-600 border-gray-300 focus:ring-green-500" onclick="toggleDue(false)">
                                            <span class="font-medium text-gray-900">Sudah Dibayar (Lunas)</span>
                                        </label>
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" name="payment_status" value="credit" class="mr-2 w-5 h-5 text-red-600 border-gray-300 focus:ring-red-500" onclick="toggleDue(true)">
                                            <span class="font-medium text-gray-900">Belum Dibayar (Masuk Pembayaran Perusahaan)</span>
                                        </label>
                                    </div>
                                    
                                    <div id="due_date_box" class="mt-3 hidden">
                                        <label class="block text-gray-700 text-xs font-bold mb-1">Jatuh Tempo Pembayaran</label>
                                        <input type="date" name="due_date" class="w-full border rounded px-3 py-2 bg-white">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Items -->
                        <div class="mb-6">
                            <h3 class="font-bold text-lg mb-2">Item Pembelian</h3>
                            <table class="w-full border" id="itemsTable">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="p-2 border text-left">Nama Barang / Deskripsi</th>
                                        <th class="p-2 border w-24">Qty</th>
                                        <th class="p-2 border w-40">Harga Satuan</th>
                                        <th class="p-2 border w-40 text-right">Subtotal</th>
                                        <th class="p-2 border w-10"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Rows -->
                                </tbody>
                                <tfoot>
                                    <tr class="font-bold bg-gray-50">
                                        <td colspan="3" class="p-2 text-right">TOTAL</td>
                                        <td class="p-2 text-right" id="grandTotal">0</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                            <button type="button" onclick="addItem()" class="mt-2 bg-blue-500 text-white px-3 py-1 rounded text-sm">+ Tambah Item</button>
                        </div>

                        <div class="flex justify-end border-t pt-4">
                            <button type="submit" class="bg-green-600 text-white font-bold py-2 px-6 rounded hover:bg-green-700">Simpan Pembelian</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let rowCount = 0;

        function toggleDue(show) {
            const box = document.getElementById('due_date_box');
            if (show) box.classList.remove('hidden');
            else box.classList.add('hidden');
        }

        function addItem() {
            const table = document.querySelector('#itemsTable tbody');
            const row = document.createElement('tr');
            row.id = `row-${rowCount}`;
            row.innerHTML = `
                <td class="p-1 border">
                    <input type="text" name="items[${rowCount}][item_name]" class="w-full border rounded px-2 py-1" placeholder="Nama barang..." required>
                </td>
                <td class="p-1 border">
                    <input type="number" name="items[${rowCount}][quantity]" id="qty-${rowCount}" value="1" min="1" class="w-full border rounded px-2 py-1 text-center" onchange="calcRow(${rowCount})" required>
                </td>
                <td class="p-1 border">
                    <input type="number" name="items[${rowCount}][unit_price]" id="price-${rowCount}" class="w-full border rounded px-2 py-1 text-right" placeholder="0" onchange="calcRow(${rowCount})" required>
                </td>
                <td class="p-1 border text-right font-mono" id="sub-${rowCount}">0</td>
                <td class="p-1 border text-center">
                    <button type="button" onclick="removeRow(${rowCount})" class="text-red-500 font-bold">&times;</button>
                </td>
            `;
            table.appendChild(row);
            rowCount++;
        }

        function calcRow(id) {
            const qty = parseFloat(document.getElementById(`qty-${id}`).value) || 0;
            const price = parseFloat(document.getElementById(`price-${id}`).value) || 0;
            const sub = qty * price;
            document.getElementById(`sub-${id}`).innerText = new Intl.NumberFormat('id-ID').format(sub);
            calcTotal();
        }

        function calcTotal() {
            let total = 0;
            document.querySelectorAll('[id^="sub-"]').forEach(el => {
                total += parseFloat(el.innerText.replace(/\./g, '')) || 0;
            });
            document.getElementById('grandTotal').innerText = new Intl.NumberFormat('id-ID').format(total);
        }

        function removeRow(id) {
            document.getElementById(`row-${id}`).remove();
            calcTotal();
        }

        function validateForm() {
            // Check if at least one item
            if (document.querySelectorAll('#itemsTable tbody tr').length === 0) {
                alert('Masukkan minimal satu item pembelian.');
                return false;
            }
            return true;
        }

        // Init
        window.onload = () => addItem();
    </script>
</x-app-layout>
