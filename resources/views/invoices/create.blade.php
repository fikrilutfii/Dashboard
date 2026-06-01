<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ session('division') == 'konfeksi' ? 'Input Penjualan Barang' : 'Buat Invoice Baru' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('invoices.store') }}" method="POST" id="invoiceForm">
                        @csrf
                        
                        <!-- Top Section: Customer & Dates -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">No. {{ session('division') == 'konfeksi' ? 'Transaksi' : 'Faktur' }} (Manual)</label>
                                    <input type="text" name="invoice_number" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required placeholder="Contoh: INV/2026/001">
                                </div>
                                
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Divisi</label>
                                    <input type="text" value="{{ ucfirst(session('division', 'Percetakan')) }}" class="shadow border rounded w-full py-2 px-3 text-gray-500 bg-gray-100 leading-tight focus:outline-none" readonly>
                                    <input type="hidden" name="division" value="{{ session('division', 'percetakan') }}">
                                </div>

                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Customer</label>
                                    <div class="flex gap-2">
                                        <select name="customer_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                            <option value="">-- Pilih Customer --</option>
                                            @foreach($customers as $customer)
                                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                            @endforeach
                                        </select>
                                        <a href="{{ route('customers.create') }}" class="bg-green-500 text-white px-3 py-2 rounded font-bold hover:bg-green-600">+</a>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal {{ session('division') == 'konfeksi' ? 'Transaksi' : 'Invoice' }}</label>
                                    <input type="date" name="invoice_date" value="{{ date('Y-m-d') }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                </div>
                                @if(session('division') != 'konfeksi')
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Jatuh Tempo (Opsional)</label>
                                    <input type="date" name="due_date" value="{{ date('Y-m-d') }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                                @endif
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
                                    <!-- Row Template will be appended via JS -->
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

                        <!-- Payment Method Section -->
                        <div x-data="{ method: 'cash' }" class="mb-8">
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 shadow-sm">
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-4 text-center sm:text-left">Metode Pembayaran</label>
                                <div class="grid grid-cols-2 gap-4 max-w-md mx-auto sm:mx-0">
                                    <label class="cursor-pointer group">
                                        <input type="radio" name="payment_method" value="cash" x-model="method" class="hidden">
                                        <div class="flex flex-col items-center justify-center gap-2 p-4 rounded-xl border-2 transition-all"
                                             :class="method === 'cash' ? 'border-primary-500 bg-primary-50 text-primary-700' : 'border-gray-100 bg-white text-gray-400 group-hover:border-gray-200'">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                                            </svg>
                                            <span class="font-bold text-sm">Tunai (Cash)</span>
                                        </div>
                                    </label>
                                    <label class="cursor-pointer group">
                                        <input type="radio" name="payment_method" value="credit" x-model="method" class="hidden">
                                        <div class="flex flex-col items-center justify-center gap-2 p-4 rounded-xl border-2 transition-all"
                                             :class="method === 'credit' ? 'border-primary-500 bg-primary-50 text-primary-700' : 'border-gray-100 bg-white text-gray-400 group-hover:border-gray-200'">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span class="font-bold text-sm">Tagihan (Credit)</span>
                                        </div>
                                    </label>
                                </div>

                                <!-- Installment Options -->
                                <div x-show="method === 'credit'" x-transition class="mt-6 pt-6 border-t border-gray-100 grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-2 uppercase tracking-tighter">Tenor / Lama Cicilan (Bulan)</label>
                                        <input type="number" name="tenure" value="12" min="1" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-white" placeholder="Contoh: 12">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 text-sm font-bold mb-2 uppercase tracking-tighter">Jatuh Tempo Pertama</label>
                                        <input type="date" name="due_date" value="{{ date('Y-m-d', strtotime('+1 month')) }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-white">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('invoices.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline">
                                Batal
                            </a>
                            <button class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-8 rounded shadow-lg shadow-primary-200 transition-all focus:outline-none focus:shadow-outline" type="submit">
                                Simpan Invoice
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Script for Input Logic -->
    <script>
        let rowCount = 0;

        function addItem() {
            const table = document.querySelector('#itemsTable tbody');
            const row = document.createElement('tr');
            row.id = `row-${rowCount}`;
            row.innerHTML = `
                <td class="p-1 border">
                    <input type="text" name="items[${rowCount}][product_code]" class="w-full border rounded px-2 py-1 uppercase" placeholder="Kode" onblur="fetchProduct(this, ${rowCount})" required>
                    <div id="loading-${rowCount}" class="text-xs text-blue-500 hidden">Loading...</div>
                </td>
                <td class="p-1 border">
                    <input type="text" name="items[${rowCount}][product_name]" id="name-${rowCount}" class="w-full border rounded px-2 py-1" required>
                </td>
                <td class="p-1 border">
                    <input type="number" name="items[${rowCount}][quantity]" id="qty-${rowCount}" class="w-full border rounded px-2 py-1 text-center" value="1" min="1" onchange="calculateRow(${rowCount})" required>
                </td>
                <td class="p-1 border">
                    <input type="number" name="items[${rowCount}][unit_price]" id="price-${rowCount}" class="w-full border rounded px-2 py-1 text-right" onchange="calculateRow(${rowCount})" required>
                </td>
                <td class="p-1 border text-right font-mono" id="subtotal-${rowCount}">0</td>
                <td class="p-1 border text-center">
                    <button type="button" onclick="removeRow(${rowCount})" class="text-red-500 font-bold">x</button>
                </td>
            `;
            table.appendChild(row);
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
                        // Manual Price: Do not auto-fill
                        calculateRow(id);
                    }
                } else {
                    alert('Barang tidak ditemukan!');
                    input.value = '';
                    document.getElementById(`name-${id}`).value = '';
                    // document.getElementById(`price-${id}`).value = '';
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

        // Add first row on load
        window.onload = () => addItem();
    </script>
</x-app-layout>
