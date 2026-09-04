<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Pembelian') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('purchases.update', $purchase) }}" method="POST" onsubmit="return validateForm()">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Left Column -->
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">No. Purchase / Nota <span class="text-gray-500 font-normal">(Opsional)</span></label>
                                    <input type="text" name="purchase_number" value="{{ $purchase->purchase_number }}" class="w-full border rounded px-3 py-2" placeholder="Kosongkan untuk otomatis">
                                </div>
                                
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Divisi</label>
                                    <input type="text" value="{{ ucfirst($purchase->division) }}" class="w-full border rounded px-3 py-2 bg-gray-100 text-gray-500" readonly>
                                </div>

                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Supplier</label>
                                    <select name="supplier_id" class="w-full border rounded px-3 py-2">
                                        <option value="">-- Tanpa Supplier (Umum) --</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ $purchase->supplier_id == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika beli eceran/umum.</p>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal</label>
                                    <input type="date" name="date" value="{{ $purchase->date->format('Y-m-d') }}" class="w-full border rounded px-3 py-2" required>
                                </div>
                                
                                <div class="p-4 bg-gray-50 border rounded-lg">
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Status Pembayaran</label>
                                    <p class="text-xs text-gray-500 mb-2">Status pembayaran tidak dapat diubah di sini. Silakan ubah dari menu Hutang Perusahaan jika perlu.</p>
                                    
                                    <div id="due_date_box" class="{{ $purchase->status == 'belum_lunas' ? '' : 'hidden' }}">
                                        <label class="block text-gray-700 text-xs font-bold mb-1">Jatuh Tempo Pembayaran</label>
                                        <input type="date" name="due_date" value="{{ $purchase->due_date ? $purchase->due_date->format('Y-m-d') : '' }}" class="w-full border rounded px-3 py-2 bg-white">
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
                                    <!-- Existing Rows -->
                                </tbody>
                                <tfoot>
                                    <tr class="font-bold bg-gray-50">
                                        <td colspan="3" class="p-2 text-right">TOTAL</td>
                                        <td class="p-2 text-right" id="grandTotal">{{ number_format($purchase->total_amount, 0, ',', '.') }}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                            <button type="button" onclick="addItem()" class="mt-2 bg-blue-500 text-white px-3 py-1 rounded text-sm">+ Tambah Item</button>
                        </div>

                        <div class="flex justify-end gap-2 border-t pt-4">
                            <a href="{{ route('purchases.index', ['division' => $purchase->division]) }}" class="bg-gray-500 text-white font-bold py-2 px-6 rounded hover:bg-gray-600">Batal</a>
                            <button type="submit" class="bg-yellow-600 text-white font-bold py-2 px-6 rounded hover:bg-yellow-700">Update Pembelian</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let rowCount = 0;
        const existingItems = @json($purchase->items);

        function addItem(item = null) {
            const table = document.querySelector('#itemsTable tbody');
            const row = document.createElement('tr');
            row.id = `row-${rowCount}`;
            
            const pCode = item ? (item.product_code || '') : '';
            const pName = item ? item.item_name : '';
            const pQty = item ? item.quantity : 1;
            const pPrice = item ? item.unit_price : '';
            const pSub = item ? (item.quantity * item.unit_price) : 0;

            row.innerHTML = `
                <td class="p-1 border">
                    <input type="text" name="items[${rowCount}][product_code]" value="${pCode}" class="w-full border rounded px-2 py-1 mb-1 text-sm uppercase" placeholder="Kode Produk (Opsional untuk tmbh stok)">
                    <input type="text" name="items[${rowCount}][item_name]" value="${pName}" class="w-full border rounded px-2 py-1" placeholder="Nama barang..." required>
                </td>
                <td class="p-1 border">
                    <input type="text" inputmode="decimal" name="items[${rowCount}][quantity]" id="qty-${rowCount}" value="${pQty}" pattern="[0-9]+([.,][0-9]{1,3})?" title="Gunakan angka positif hingga 3 desimal, misalnya 1,5 atau 1.5" class="w-full border rounded px-2 py-1 text-center" oninput="calcRow(${rowCount})" required>
                </td>
                <td class="p-1 border">
                    <input type="text" inputmode="decimal" name="items[${rowCount}][unit_price]" id="price-${rowCount}" value="${pPrice}" pattern="[0-9]+([.,][0-9]{1,2})?" title="Gunakan harga hingga 2 desimal, misalnya 507,20 atau 507.20" class="w-full border rounded px-2 py-1 text-right" placeholder="0" oninput="calcRow(${rowCount})" required>
                </td>
                <td class="p-1 border text-right font-mono" id="sub-${rowCount}">${new Intl.NumberFormat('id-ID').format(pSub)}</td>
                <td class="p-1 border text-center">
                    <button type="button" onclick="removeRow(${rowCount})" class="text-red-500 font-bold">&times;</button>
                </td>
            `;
            table.appendChild(row);
            rowCount++;
        }

        function calcRow(id) {
            const qty = parseFloat(document.getElementById(`qty-${id}`).value.replace(',', '.')) || 0;
            const price = parseFloat(document.getElementById(`price-${id}`).value.replace(',', '.')) || 0;
            const sub = qty * price;
            document.getElementById(`sub-${id}`).innerText = new Intl.NumberFormat('id-ID').format(sub);
            calcTotal();
        }

        function calcTotal() {
            let total = 0;
            document.querySelectorAll('[id^="qty-"]').forEach(input => {
                const id = input.id.replace('qty-', '');
                const qty = parseFloat(input.value.replace(',', '.')) || 0;
                const price = parseFloat(document.getElementById(`price-${id}`).value.replace(',', '.')) || 0;
                total += qty * price;
            });
            document.getElementById('grandTotal').innerText = new Intl.NumberFormat('id-ID').format(total);
        }

        function removeRow(id) {
            document.getElementById(`row-${id}`).remove();
            calcTotal();
        }

        function validateForm() {
            if (document.querySelectorAll('#itemsTable tbody tr').length === 0) {
                alert('Masukkan minimal satu item pembelian.');
                return false;
            }
            return true;
        }

        // Init
        window.onload = () => {
            if(existingItems && existingItems.length > 0) {
                existingItems.forEach(item => addItem(item));
            } else {
                addItem();
            }
        };
    </script>
</x-app-layout>
