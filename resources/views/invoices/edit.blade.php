<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Invoice') }} : {{ $invoice->faktur_number }}
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
                        <div class="space-y-4 mb-6 max-w-2xl">
                            <!-- Row 1: No. Faktur & OP No. -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">No. Faktur</label>
                                    <input type="text" 
                                           class="shadow border rounded w-full py-2 px-3 text-gray-500 bg-gray-100 leading-tight focus:outline-none" 
                                           value="{{ $invoice->faktur_number }}"
                                           readonly>
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">OP No. <span class="text-gray-500 font-normal">(Opsional)</span></label>
                                    <input type="text" 
                                           name="invoice_number"
                                           class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                           value="{{ old('invoice_number', $invoice->invoice_number) }}">
                                </div>
                            </div>

                            <!-- Row 2: Tanggal & Jatuh Tempo -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Invoice</label>
                                    <input type="date" name="invoice_date" value="{{ old('invoice_date', $invoice->invoice_date->format('Y-m-d')) }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Jatuh Tempo Pembayaran (Opsional)</label>
                                    <input type="date" name="due_date" value="{{ old('due_date', $invoice->due_date ? $invoice->due_date->format('Y-m-d') : date('Y-m-d', strtotime('+1 month'))) }}" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                </div>
                            </div>

                            <!-- Row 3: Customer -->
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Customer</label>
                                <select name="customer_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                    <option value="">-- Pilih Customer --</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ $invoice->customer_id == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                                    @endforeach
                                </select>
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

                        <!-- Payment Method Section -->
                        <div x-data="{ method: '{{ old('payment_method', $invoice->payment_method ?? 'cash') }}' }" class="mb-8">
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
                                        <input type="number" name="tenure" value="{{ $invoice->tenure ?? 12 }}" min="1" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-white" placeholder="Contoh: 12">
                                    </div>
                                    <p class="text-sm text-gray-500 self-end">Atur tanggal jatuh tempo pada kolom di atas.</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('invoices.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2 px-6 rounded focus:outline-none focus:shadow-outline">
                                Batal
                            </a>
                            <button class="bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-8 rounded shadow-lg shadow-primary-200 transition-all focus:outline-none focus:shadow-outline" type="submit">
                                Update Invoice
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Riwayat Harga & Faktur Barang -->
    <div id="productHistoryModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 hidden backdrop-blur-xs">
        <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] flex flex-col overflow-hidden border border-gray-100">
            <!-- Modal Header -->
            <div class="px-6 py-4 bg-gray-900 text-white flex justify-between items-center shrink-0">
                <div>
                    <h3 class="text-base font-bold flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5 text-blue-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                        Riwayat Penjualan & Harga Faktur Sebelumnya
                    </h3>
                    <p class="text-xs text-gray-400 mt-0.5">Daftar transaksi faktur sebelumnya untuk barang ini beserta harga dan jumlah yang dipesan</p>
                </div>
                <button type="button" onclick="closeHistoryModal()" class="text-gray-400 hover:text-white text-2xl font-bold leading-none p-1">
                    &times;
                </button>
            </div>

            <!-- Product Quick Info Bar -->
            <div class="bg-blue-50/70 border-b border-blue-100 px-6 py-3 flex flex-wrap items-center justify-between gap-4 text-xs shrink-0">
                <div class="flex items-center gap-2">
                    <span class="text-gray-500 font-semibold">Kode:</span>
                    <span id="histModalProductCode" class="font-bold text-blue-700 bg-white px-2 py-0.5 rounded border border-blue-200 uppercase font-mono">-</span>
                    <span class="text-gray-400">|</span>
                    <span class="text-gray-500 font-semibold">Nama:</span>
                    <span id="histModalProductName" class="font-bold text-gray-800">-</span>
                </div>
                <div class="flex items-center gap-4 text-gray-600">
                    <div>Harga Master: <b id="histModalMasterPrice" class="text-gray-800 font-mono">-</b></div>
                    <div>Stok Saat Ini: <b id="histModalStock" class="text-gray-800 font-mono">-</b></div>
                    <div>Total Transaksi: <b id="histModalCount" class="text-blue-700 font-mono">0</b></div>
                </div>
            </div>

            <!-- Modal Content (Table / Empty) -->
            <div class="p-6 overflow-y-auto flex-1">
                <div id="histModalLoading" class="text-center py-12 text-gray-500 hidden">
                    <svg class="animate-spin h-8 w-8 text-blue-600 mx-auto mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                    </svg>
                    Memuat riwayat faktur...
                </div>

                <div id="histModalEmpty" class="text-center py-12 text-gray-500 hidden">
                    <div class="w-12 h-12 mx-auto mb-2 text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0l-3-3m3 3l3-3M3.75 7.5h16.5m-16.5 0l2.25-4.5h12l2.25 4.5" />
                        </svg>
                    </div>
                    <h4 class="font-bold text-gray-700 mb-1">Belum Ada Riwayat Faktur</h4>
                    <p class="text-sm text-gray-500 max-w-md mx-auto">Barang ini belum pernah tercatat di transaksi faktur sebelumnya, atau belum ada penjualan yang tersimpan dengan kode ini.</p>
                    <div id="histModalMasterPriceOption" class="mt-4 hidden">
                        <button type="button" id="btnUseMasterPrice" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold py-1.5 px-4 rounded-lg shadow-sm transition">
                            Gunakan Harga Master Acuan
                        </button>
                    </div>
                </div>

                <div id="histModalTableContainer" class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700 text-xs uppercase font-bold tracking-wider">
                                <th class="p-2.5 border">No. Faktur</th>
                                <th class="p-2.5 border">Tanggal</th>
                                <th class="p-2.5 border">Customer / Pelanggan</th>
                                <th class="p-2.5 border text-center">Qty</th>
                                <th class="p-2.5 border text-right">Harga (@)</th>
                                <th class="p-2.5 border text-right">Subtotal</th>
                                <th class="p-2.5 border text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="histModalTableBody" class="divide-y divide-gray-200">
                            <!-- Injected via JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-3 bg-gray-50 border-t flex justify-end shrink-0">
                <button type="button" onclick="closeHistoryModal()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-1.5 px-5 rounded-lg text-sm transition">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Cari Barang Cepat -->
    <div id="productSearchModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4 hidden backdrop-blur-xs">
        <div class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full max-h-[85vh] flex flex-col overflow-hidden border border-gray-100">
            <div class="px-6 py-4 bg-gray-800 text-white flex justify-between items-center shrink-0">
                <h3 class="text-base font-bold flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-blue-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    Pencarian Master Barang & Riwayat Faktur
                </h3>
                <button type="button" onclick="closeSearchModal()" class="text-gray-400 hover:text-white text-2xl font-bold leading-none p-1">
                    &times;
                </button>
            </div>
            <div class="p-4 border-b bg-gray-50">
                <input type="text" id="quickSearchInput" class="w-full border rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Ketik kode atau nama barang..." oninput="handleQuickSearch(this.value)">
            </div>
            <div class="p-4 overflow-y-auto flex-1">
                <div id="quickSearchResults" class="space-y-2">
                    <p class="text-center text-gray-400 py-8 text-sm">Ketik nama atau kode barang untuk melihat hasil...</p>
                </div>
            </div>
            <div class="px-6 py-3 bg-gray-50 border-t flex justify-end shrink-0">
                <button type="button" onclick="closeSearchModal()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-1.5 px-5 rounded-lg text-sm">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- Script to Load Existing Items and Handle Logic -->
    <script>
        let rowCount = 0;
        let activeHistoryRowId = null;
        let activeSearchRowId = null;
        const rowProductsData = {};
        let searchTimeout = null;
        const existingItems = @json($invoice->items);

        function addItem(data = null) {
            const table = document.querySelector('#itemsTable tbody');
            const row = document.createElement('tr');
            const id = rowCount;
            row.id = `row-${id}`;

            const pCode = data ? (data.product_code || '') : '';
            const pName = data ? (data.item_name || data.product_name || '') : '';
            const pQty = data ? (parseFloat(data.quantity) || 1) : 1;
            const pPrice = data ? (data.unit_price || '') : '';

            row.innerHTML = `
                <td class="p-1.5 border align-top">
                    <div class="relative">
                        <div class="flex items-center gap-1">
                            <input type="text" 
                                   name="items[${id}][product_code]" 
                                   id="code-${id}" 
                                   class="w-full border rounded px-2 py-1 uppercase text-sm font-semibold focus:ring-1 focus:ring-primary-500" 
                                   placeholder="Kode" 
                                   value="${pCode}"
                                   autocomplete="off"
                                   oninput="handleCodeInput(this, ${id})" 
                                   onblur="handleCodeBlur(this, ${id})" 
                                   required>
                            <button type="button" 
                                    onclick="openSearchModal(${id})" 
                                    class="px-2 py-1 bg-gray-100 hover:bg-gray-200 border rounded text-xs text-gray-700 font-bold shrink-0 transition flex items-center justify-center" 
                                    title="Cari Barang & Riwayat Faktur">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 text-gray-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Autocomplete Dropdown List -->
                        <div id="autocomplete-${id}" class="absolute left-0 right-0 top-full mt-1 bg-white border border-gray-200 rounded-lg shadow-xl z-30 hidden max-h-72 overflow-y-auto divide-y divide-gray-100 text-xs min-w-[320px]"></div>
                    </div>

                    <!-- Loading Text -->
                    <div id="loading-${id}" class="text-[11px] text-blue-600 hidden mt-1 font-medium flex items-center gap-1">
                        <svg class="animate-spin h-3 w-3 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                        </svg>
                        Mencari barang & riwayat faktur...
                    </div>

                    <!-- History Badge Button -->
                    <div id="history-badge-container-${id}" class="mt-1.5 hidden">
                        <button type="button" 
                                onclick="openHistoryModal(${id})" 
                                class="inline-flex items-center gap-1 text-[11px] bg-blue-50 hover:bg-blue-100 text-blue-700 font-semibold px-2 py-0.5 rounded border border-blue-200 transition shadow-2xs">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-3 h-3 text-blue-600">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                            <span id="history-badge-text-${id}">0 Riwayat Faktur</span>
                        </button>
                    </div>
                </td>
                <td class="p-1.5 border align-top">
                    <input type="text" name="items[${id}][product_name]" id="name-${id}" value="${pName}" class="w-full border rounded px-2 py-1 text-sm" placeholder="Nama Barang" required>
                </td>
                <td class="p-1.5 border align-top">
                    <input type="text" inputmode="decimal" name="items[${id}][quantity]" id="qty-${id}" class="w-full border rounded px-2 py-1 text-center text-sm font-semibold" value="${pQty}" pattern="[0-9]+([.,][0-9]{1,3})?" title="Jumlah barang" oninput="calculateRow(${id})" required>
                </td>
                <td class="p-1.5 border align-top">
                    <input type="text" inputmode="decimal" name="items[${id}][unit_price]" id="price-${id}" value="${pPrice}" class="w-full border rounded px-2 py-1 text-right text-sm font-semibold" pattern="[0-9]+([.,][0-9]{1,2})?" title="Gunakan harga hingga 2 desimal" oninput="calculateRow(${id})" required>
                    
                    <!-- Quick Last Price Helper -->
                    <div id="price-helper-${id}" class="text-[11px] text-gray-500 mt-1 hidden leading-snug">
                        <div id="price-helper-content-${id}"></div>
                    </div>
                </td>
                <td class="p-1.5 border align-top text-right font-mono font-bold text-sm pt-2" id="subtotal-${id}">0</td>
                <td class="p-1.5 border align-top text-center pt-1.5">
                    <button type="button" onclick="removeRow(${id})" class="text-red-500 hover:text-red-700 font-bold px-2 py-1 rounded hover:bg-red-50 text-xs" title="Hapus baris">&times;</button>
                </td>
            `;
            table.appendChild(row);

            if (pCode) {
                fetchProductByCode(pCode, id, false);
            }

            calculateRow(id, false);
            rowCount++;
        }

        // Live typing in product code input
        function handleCodeInput(input, id) {
            const query = input.value.trim();
            const dropdown = document.getElementById(`autocomplete-${id}`);

            if (query.length < 1) {
                dropdown.classList.add('hidden');
                dropdown.innerHTML = '';
                return;
            }

            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(async () => {
                try {
                    const response = await fetch(`{{ url('/api/products/search') }}?q=${encodeURIComponent(query)}`);
                    if (response.ok) {
                        const res = await response.json();
                        if (res.success && res.data && res.data.length > 0) {
                            renderAutocomplete(res.data, id);
                        } else {
                            dropdown.classList.add('hidden');
                            dropdown.innerHTML = '';
                        }
                    }
                } catch (e) {
                    console.error('Search error:', e);
                }
            }, 250);
        }

        function renderAutocomplete(products, id) {
            const dropdown = document.getElementById(`autocomplete-${id}`);
            dropdown.innerHTML = '';

            products.forEach(p => {
                const item = document.createElement('div');
                item.className = 'p-2.5 hover:bg-blue-50/90 cursor-pointer border-b border-gray-100 last:border-b-0 transition flex flex-col gap-1';
                const formattedPrice = p.price_formatted || new Intl.NumberFormat('id-ID').format(p.price || 0);
                const hasLastQty = p.last_qty !== null && p.last_qty !== undefined;
                const qtyText = hasLastQty ? `${p.last_qty_formatted} ${escapeHtml(p.unit || 'Pcs')}` : null;

                item.innerHTML = `
                    <div class="flex items-center justify-between gap-2">
                        <div class="truncate">
                            <span class="font-bold text-blue-700 uppercase">${escapeHtml(p.code)}</span>
                            <span class="text-gray-900 font-medium ml-1.5">${escapeHtml(p.name)}</span>
                        </div>
                        <div class="text-right whitespace-nowrap">
                            <span class="text-[10px] text-gray-400">Harga:</span>
                            <span class="font-bold text-blue-800 font-mono ml-0.5">Rp ${formattedPrice}</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between text-[11px] text-gray-500 gap-2">
                        <div class="flex items-center gap-1.5 flex-wrap">
                            ${qtyText ? `
                                <span class="px-1.5 py-0.5 rounded bg-blue-100/70 text-blue-800 font-semibold text-[10px]">
                                    Qty Dipesan: ${qtyText}
                                </span>
                            ` : `<span class="text-gray-400 text-[10px]">Belum ada riwayat pesanan</span>`}
                            ${p.last_customer ? `<span class="text-gray-500 truncate max-w-[150px]">(${escapeHtml(p.last_customer)})</span>` : ''}
                        </div>
                        <div class="whitespace-nowrap text-[11px] text-gray-600">
                            Stok: <b class="text-gray-800">${p.stock || 0}</b>
                        </div>
                    </div>
                `;
                item.onmousedown = (e) => {
                    e.preventDefault();
                    selectProduct(p.code, id, p.price, p.last_qty);
                };
                dropdown.appendChild(item);
            });

            dropdown.classList.remove('hidden');
        }

        function handleCodeBlur(input, id) {
            setTimeout(() => {
                const dropdown = document.getElementById(`autocomplete-${id}`);
                if (dropdown) dropdown.classList.add('hidden');
            }, 250);

            const code = input.value.trim();
            if (code) {
                fetchProductByCode(code, id, true);
            }
        }

        function selectProduct(code, id, price = null, qty = null) {
            const input = document.getElementById(`code-${id}`);
            input.value = code;
            const dropdown = document.getElementById(`autocomplete-${id}`);
            if (dropdown) dropdown.classList.add('hidden');
            fetchProductByCode(code, id, true).then(() => {
                if (price !== null && price !== undefined && parseFloat(price) > 0) {
                    applyPrice(id, price);
                }
                if (qty !== null && qty !== undefined && parseFloat(qty) > 0) {
                    const qtyInput = document.getElementById(`qty-${id}`);
                    if (qtyInput && (!qtyInput.value || qtyInput.value === '1')) {
                        applyQty(id, qty);
                    }
                }
            });
        }

        async function fetchProductByCode(code, id, autoApplyPriceIfEmpty = true) {
            if (!code) return;
            const loading = document.getElementById(`loading-${id}`);
            if (loading) loading.classList.remove('hidden');

            try {
                const response = await fetch(`{{ url('/api/products') }}/${encodeURIComponent(code)}`);
                if (response.ok) {
                    const res = await response.json();
                    if (res.success) {
                        const product = res.data;
                        const history = res.history || [];
                        const lastInvoice = res.last_invoice || (history.length > 0 ? history[0] : null);

                        rowProductsData[id] = res;

                        // Set Product Name
                        const nameInput = document.getElementById(`name-${id}`);
                        if (nameInput) nameInput.value = product.name;

                        // Update History Badge
                        const badgeContainer = document.getElementById(`history-badge-container-${id}`);
                        const badgeText = document.getElementById(`history-badge-text-${id}`);
                        if (badgeContainer && badgeText) {
                            badgeContainer.classList.remove('hidden');
                            badgeText.innerText = `${history.length} Riwayat Faktur`;
                        }

                        // Update Price Helper
                        const priceHelper = document.getElementById(`price-helper-${id}`);
                        const priceHelperContent = document.getElementById(`price-helper-content-${id}`);
                        if (priceHelper && priceHelperContent) {
                            priceHelper.classList.remove('hidden');

                            if (lastInvoice) {
                                priceHelperContent.innerHTML = `
                                    <span class="text-gray-600 font-medium">Terakhir:</span>
                                    <b class="text-blue-700 font-mono">${lastInvoice.unit_price_display}</b>
                                    <span class="text-gray-500">(${escapeHtml(lastInvoice.display_number)}, Qty: ${lastInvoice.formatted_quantity})</span>
                                    <button type="button" onclick="applyPrice(${id}, ${lastInvoice.unit_price})" class="ml-1.5 text-xs text-blue-600 hover:text-blue-800 font-bold underline cursor-pointer">Pakai Harga</button>
                                    <button type="button" onclick="applyQty(${id}, ${lastInvoice.quantity})" class="ml-1 text-xs text-emerald-600 hover:text-emerald-800 font-bold underline cursor-pointer">Pakai Qty</button>
                                `;
                            } else if (product.price && product.price > 0) {
                                const masterFormatted = new Intl.NumberFormat('id-ID').format(product.price);
                                priceHelperContent.innerHTML = `
                                    <span class="text-gray-600 font-medium">Master:</span>
                                    <b class="text-gray-800 font-mono">Rp ${masterFormatted}</b>
                                    <button type="button" onclick="applyPrice(${id}, ${product.price})" class="ml-1.5 text-xs text-blue-600 hover:text-blue-800 font-bold underline cursor-pointer">Pakai Harga</button>
                                `;
                            } else {
                                priceHelperContent.innerHTML = `<span class="text-gray-400">Belum ada riwayat harga</span>`;
                            }
                        }

                        // Auto-fill price if field is empty or 0 and history exists
                        const priceInput = document.getElementById(`price-${id}`);
                        if (autoApplyPriceIfEmpty && priceInput && (!priceInput.value || parseFloat(priceInput.value) === 0)) {
                            if (lastInvoice && lastInvoice.unit_price) {
                                applyPrice(id, lastInvoice.unit_price);
                            } else if (product.price && product.price > 0) {
                                applyPrice(id, product.price);
                            }
                        }
                    }
                } else {
                    const badgeContainer = document.getElementById(`history-badge-container-${id}`);
                    if (badgeContainer) badgeContainer.classList.add('hidden');
                    const priceHelper = document.getElementById(`price-helper-${id}`);
                    if (priceHelper) priceHelper.classList.add('hidden');
                }
            } catch (err) {
                console.error('Error fetching product details:', err);
            } finally {
                if (loading) loading.classList.add('hidden');
            }
        }

        function applyPrice(id, price) {
            const priceInput = document.getElementById(`price-${id}`);
            if (priceInput) {
                priceInput.value = price;
                calculateRow(id);
                priceInput.classList.add('bg-green-50', 'border-green-400');
                setTimeout(() => {
                    priceInput.classList.remove('bg-green-50', 'border-green-400');
                }, 800);
            }
        }

        function applyQty(id, qty) {
            const qtyInput = document.getElementById(`qty-${id}`);
            if (qtyInput && qty) {
                qtyInput.value = qty;
                calculateRow(id);
                qtyInput.classList.add('bg-blue-50', 'border-blue-400');
                setTimeout(() => {
                    qtyInput.classList.remove('bg-blue-50', 'border-blue-400');
                }, 800);
            }
        }

        function applyPriceAndQty(id, price, qty) {
            applyPrice(id, price);
            const qtyInput = document.getElementById(`qty-${id}`);
            if (qtyInput && qty) {
                qtyInput.value = qty;
                calculateRow(id);
            }
            closeHistoryModal();
        }

        // History Modal Logic
        function openHistoryModal(id) {
            activeHistoryRowId = id;
            const modal = document.getElementById('productHistoryModal');
            const dataObj = rowProductsData[id];

            if (!dataObj || !dataObj.data) {
                const code = document.getElementById(`code-${id}`).value.trim();
                if (code) {
                    fetchProductByCode(code, id, false).then(() => {
                        openHistoryModal(id);
                    });
                }
                return;
            }

            const product = dataObj.data;
            const history = dataObj.history || [];

            document.getElementById('histModalProductCode').innerText = product.code || '-';
            document.getElementById('histModalProductName').innerText = product.name || '-';
            document.getElementById('histModalMasterPrice').innerText = product.price ? 'Rp ' + new Intl.NumberFormat('id-ID').format(product.price) : 'Rp 0';
            document.getElementById('histModalStock').innerText = (product.stock || 0) + ' ' + (product.unit || 'Pcs');
            document.getElementById('histModalCount').innerText = history.length;

            const tableContainer = document.getElementById('histModalTableContainer');
            const emptyContainer = document.getElementById('histModalEmpty');
            const tbody = document.getElementById('histModalTableBody');

            if (history.length === 0) {
                tableContainer.classList.add('hidden');
                emptyContainer.classList.remove('hidden');

                const masterOption = document.getElementById('histModalMasterPriceOption');
                const btnMaster = document.getElementById('btnUseMasterPrice');
                if (product.price && product.price > 0) {
                    masterOption.classList.remove('hidden');
                    btnMaster.onclick = () => {
                        applyPrice(activeHistoryRowId, product.price);
                        closeHistoryModal();
                    };
                } else {
                    masterOption.classList.add('hidden');
                }
            } else {
                emptyContainer.classList.add('hidden');
                tableContainer.classList.remove('hidden');
                tbody.innerHTML = '';

                history.forEach(item => {
                    const tr = document.createElement('tr');
                    tr.className = 'hover:bg-blue-50/60 transition';
                    const statusBadge = item.status === 'lunas' 
                        ? '<span class="px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-700">Lunas</span>'
                        : '<span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-700">Belum Lunas</span>';

                    tr.innerHTML = `
                        <td class="p-2.5 border font-semibold text-gray-900">
                            <div>${escapeHtml(item.faktur_number)}</div>
                            ${item.op_number && item.op_number !== item.faktur_number && !item.op_number.startsWith('NO-PO-') ? `<div class="text-[11px] text-gray-500 font-normal">OP: ${escapeHtml(item.op_number)}</div>` : ''}
                        </td>
                        <td class="p-2.5 border text-gray-600 whitespace-nowrap">${escapeHtml(item.invoice_date)}</td>
                        <td class="p-2.5 border">
                            <div class="font-medium text-gray-800">${escapeHtml(item.customer_name)}</div>
                            <div class="mt-0.5">${statusBadge}</div>
                        </td>
                        <td class="p-2.5 border text-center font-bold text-gray-800">${escapeHtml(String(item.formatted_quantity))}</td>
                        <td class="p-2.5 border text-right font-mono font-bold text-blue-700">${escapeHtml(item.unit_price_display)}</td>
                        <td class="p-2.5 border text-right font-mono text-gray-600">${escapeHtml(item.subtotal_formatted)}</td>
                        <td class="p-2.5 border text-center whitespace-nowrap">
                            <button type="button" 
                                    onclick="applyPriceAndQty(${activeHistoryRowId}, ${item.unit_price}, ${item.quantity})" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-3 py-1 rounded text-xs shadow-sm transition">
                                Gunakan Harga Ini
                            </button>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
            }

            modal.classList.remove('hidden');
        }

        function closeHistoryModal() {
            document.getElementById('productHistoryModal').classList.add('hidden');
        }

        // Quick Search Modal Logic
        function openSearchModal(id) {
            activeSearchRowId = id;
            const modal = document.getElementById('productSearchModal');
            modal.classList.remove('hidden');
            const input = document.getElementById('quickSearchInput');
            input.value = '';
            input.focus();
            handleQuickSearch('');
        }

        function closeSearchModal() {
            document.getElementById('productSearchModal').classList.add('hidden');
        }

        async function handleQuickSearch(query) {
            const container = document.getElementById('quickSearchResults');
            if (!query || query.trim().length === 0) {
                container.innerHTML = '<p class="text-center text-gray-400 py-8 text-sm">Ketik nama atau kode barang untuk melihat hasil...</p>';
                return;
            }

            try {
                const response = await fetch(`{{ url('/api/products/search') }}?q=${encodeURIComponent(query.trim())}`);
                if (response.ok) {
                    const res = await response.json();
                    if (res.success && res.data && res.data.length > 0) {
                        container.innerHTML = '';
                        res.data.forEach(p => {
                            const card = document.createElement('div');
                            card.className = 'p-3 border rounded-xl hover:border-blue-500 hover:bg-blue-50/40 cursor-pointer flex flex-col sm:flex-row sm:items-center justify-between gap-3 transition shadow-xs';
                            const pPrice = p.price_formatted || new Intl.NumberFormat('id-ID').format(p.price || 0);
                            const hasLastQty = p.last_qty !== null && p.last_qty !== undefined;
                            const qtyText = hasLastQty ? `${p.last_qty_formatted} ${escapeHtml(p.unit || 'Pcs')}` : null;

                            card.innerHTML = `
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 font-bold text-xs uppercase border border-blue-200">${escapeHtml(p.code)}</span>
                                        <span class="font-bold text-gray-800 text-sm">${escapeHtml(p.name)}</span>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-gray-500">
                                        <span>Stok: <b class="text-gray-700">${p.stock || 0} ${escapeHtml(p.unit || 'Pcs')}</b></span>
                                        ${qtyText ? `<span class="bg-blue-50 text-blue-700 px-1.5 py-0.5 rounded border border-blue-100 font-medium">Qty Dipesan: <b>${qtyText}</b></span>` : '<span class="text-gray-400">Belum ada riwayat pesanan</span>'}
                                        ${p.last_customer ? `<span>Pelanggan: <b class="text-gray-700">${escapeHtml(p.last_customer)}</b></span>` : ''}
                                        ${p.last_faktur ? `<span>Faktur: <b class="text-gray-700">${escapeHtml(p.last_faktur)}</b></span>` : ''}
                                    </div>
                                </div>
                                <div class="flex sm:flex-col items-center sm:items-end justify-between gap-1 border-t sm:border-t-0 pt-2 sm:pt-0 shrink-0">
                                    <div class="text-right">
                                        <div class="text-xs text-gray-500">Harga: <b class="text-blue-700 text-sm font-mono font-bold">Rp ${pPrice}</b></div>
                                    </div>
                                    <button type="button" class="mt-1 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs px-3.5 py-1.5 rounded-lg transition shadow-xs">
                                        Pilih Barang
                                    </button>
                                </div>
                            `;
                            card.onclick = () => {
                                selectProduct(p.code, activeSearchRowId, p.price, p.last_qty);
                                closeSearchModal();
                            };
                            container.appendChild(card);
                        });
                    } else {
                        container.innerHTML = '<p class="text-center text-gray-500 py-8 text-sm">Tidak ada barang yang cocok dengan kata kunci.</p>';
                    }
                }
            } catch (err) {
                console.error(err);
            }
        }

        function calculateRow(id, updateGrandTotal = true) {
            const qtyInput = document.getElementById(`qty-${id}`);
            const priceInput = document.getElementById(`price-${id}`);
            if (!qtyInput || !priceInput) return;

            const qty = parseFloat(qtyInput.value.replace(',', '.')) || 0;
            const price = parseFloat(priceInput.value.replace(',', '.')) || 0;
            const subtotal = qty * price;
            
            const subtotalEl = document.getElementById(`subtotal-${id}`);
            if (subtotalEl) {
                subtotalEl.innerText = new Intl.NumberFormat('id-ID').format(subtotal);
            }
            if (updateGrandTotal) {
                calculateGrandTotal();
            }
        }

        function calculateGrandTotal() {
            let total = 0;
            document.querySelectorAll('[id^="qty-"]').forEach(input => {
                const id = input.id.replace('qty-', '');
                const priceEl = document.getElementById(`price-${id}`);
                if (priceEl) {
                    const qty = parseFloat(input.value.replace(',', '.')) || 0;
                    const price = parseFloat(priceEl.value.replace(',', '.')) || 0;
                    total += qty * price;
                }
            });
            const grandTotalEl = document.getElementById('grandTotal');
            if (grandTotalEl) {
                grandTotalEl.innerText = new Intl.NumberFormat('id-ID').format(total);
            }
        }

        function removeRow(id) {
            const row = document.getElementById(`row-${id}`);
            if (row) row.remove();
            delete rowProductsData[id];
            calculateGrandTotal();
        }

        function escapeHtml(text) {
            if (!text) return '';
            return String(text)
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        // Close modals on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeHistoryModal();
                closeSearchModal();
            }
        });

        // Initialize Existing Rows
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
