<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Invoice') }} : {{ $invoice->invoice_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('invoices.update', $invoice->id) }}" method="POST" id="invoiceForm">
                        @csrf
                        @method('PUT')
                        
                        <!-- Top Section: Customer & Dates -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Customer</label>
                                <select name="customer_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                    <option value="">-- Pilih Customer --</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ $invoice->customer_id == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Invoice</label>
                                    <input type="date" name="invoice_date" value="{{ $invoice->invoice_date->format('Y-m-d') }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Jatuh Tempo (Opsional)</label>
                                    <input type="date" name="due_date" value="{{ $invoice->due_date ? $invoice->due_date->format('Y-m-d') : '' }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                            </div>
                        </div>

                        <!-- Items Section -->
                        <h3 class="text-lg font-bold mb-2 border-b pb-1">Item Invoice</h3>
                        <div class="overflow-x-auto mb-4">
                            <table class="w-full border-collapse" id="itemsTable">
                                <thead>
                                    <tr class="bg-gray-100 text-left">
                                        <th class="p-2 border" style="width: 20%;">Kode Barang</th>
                                        <th class="p-2 border" style="width: 30%;">Nama Barang</th>
                                        <th class="p-2 border" style="width: 10%;">Qty</th>
                                        <th class="p-2 border text-right" style="width: 20%;">Harga (@)</th>
                                        <th class="p-2 border text-right" style="width: 15%;">Subtotal</th>
                                        <th class="p-2 border text-center" style="width: 5%;">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Rows populated via JS -->
                                </tbody>
                                <tfoot>
                                    <tr class="bg-gray-50 font-bold">
                                        <td colspan="4" class="p-2 text-right border">TOTAL</td>
                                        <td class="p-2 text-right border" id="grandTotal">0</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <button type="button" onclick="addItem()" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-1 px-3 rounded mb-6 text-sm">
                            + Tambah Item
                        </button>

                        <div class="flex items-center justify-end">
                            <button class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline" type="submit">
                                Update Invoice
                            </button>
                            <a href="{{ route('invoices.index') }}" class="ml-4 text-gray-600">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Script to Load Existing Items and Handle Logic -->
    <script>
        let rowCount = 0;
        const existingItems = @json($invoice->items);

        function addItem(data = null) {
            const table = document.querySelector('#itemsTable tbody');
            const row = document.createElement('tr');
            row.id = `row-${rowCount}`;

            const productCode = data ? data.product_code : '';
            const qty = data ? data.quantity : 1;
            const price = data ? data.unit_price : '';
            const name = data ? data.item_name : '';
            
            row.innerHTML = `
                <td class="p-1 border">
                    <input type="text" name="items[${rowCount}][product_code]" class="w-full border rounded px-2 py-1 uppercase" value="${productCode}" placeholder="Kode" onblur="fetchProduct(this, ${rowCount})" required>
                    <div id="loading-${rowCount}" class="text-xs text-blue-500 hidden">Loading...</div>
                </td>
                <td class="p-1 border">
                    <input type="text" id="name-${rowCount}" class="w-full bg-gray-100 border rounded px-2 py-1" value="${name}" readonly tabindex="-1">
                </td>
                <td class="p-1 border">
                    <input type="number" name="items[${rowCount}][quantity]" id="qty-${rowCount}" class="w-full border rounded px-2 py-1 text-center" value="${qty}" min="1" onchange="calculateRow(${rowCount})" required>
                </td>
                <td class="p-1 border">
                    <input type="number" name="items[${rowCount}][unit_price]" id="price-${rowCount}" class="w-full border rounded px-2 py-1 text-right" value="${price}" onchange="calculateRow(${rowCount})" required>
                </td>
                <td class="p-1 border text-right font-mono" id="subtotal-${rowCount}">0</td>
                <td class="p-1 border text-center">
                    <button type="button" onclick="removeRow(${rowCount})" class="text-red-500 font-bold">x</button>
                </td>
            `;
            table.appendChild(row);

            // Calculate initial subtotal for this row if data exists
            if(data) {
                calculateRow(rowCount, false); // false = don't double count grand total yet
            }
            rowCount++;
        }

        async function fetchProduct(input, id) {
            const code = input.value.trim();
            if (!code) return;

            document.getElementById(`loading-${id}`).classList.remove('hidden');
            
            try {
                const response = await fetch(`/api/products/${code}`);
                if (response.ok) {
                    const data = await response.json();
                    if (data.success) {
                        const product = data.data;
                        document.getElementById(`name-${id}`).value = product.name;
                        // Manual Price entry
                        calculateRow(id);
                    }
                } else {
                    alert('Barang tidak ditemukan!');
                    input.value = '';
                }
            } catch (error) {
                console.error('Error fetching product:', error);
            } finally {
                document.getElementById(`loading-${id}`).classList.add('hidden');
            }
        }

        function calculateRow(id) {
            const qty = parseFloat(document.getElementById(`qty-${id}`).value) || 0;
            const price = parseFloat(document.getElementById(`price-${id}`).value) || 0;
            const subtotal = qty * price;
            
            document.getElementById(`subtotal-${id}`).innerText = new Intl.NumberFormat('id-ID').format(subtotal);
            calculateGrandTotal();
        }

        function calculateGrandTotal() {
            let total = 0;
            const subs = document.querySelectorAll('[id^="subtotal-"]');
            subs.forEach(el => {
                total += parseFloat(el.innerText.replace(/\./g, '').replace(/,/g, '.')) || 0;
            });
            document.getElementById('grandTotal').innerText = new Intl.NumberFormat('id-ID').format(total);
        }

        function removeRow(id) {
            document.getElementById(`row-${id}`).remove();
            calculateGrandTotal();
        }

        // Initialize Rows
        window.onload = () => {
            if (existingItems && existingItems.length > 0) {
                existingItems.forEach(item => addItem(item));
            } else {
                addItem();
            }
            calculateGrandTotal();
        };
    </script>
</x-app-layout>
