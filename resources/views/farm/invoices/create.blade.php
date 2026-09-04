<x-farm-layout title="Buat Faktur Penjualan" subtitle="Faktur Penjualan Panen Ayam / Hasil Peternakan">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <form action="{{ route('farm.invoices.store') }}" method="POST" id="invoiceForm">
                @csrf

                {{-- Available Harvest Quick Picker Alert (Optional) --}}
                @if(isset($availableHarvests) && count($availableHarvests) > 0)
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-amber-900 uppercase">Pilih dari Stok Panen Tersedia (Opsional)</span>
                        <span class="text-[10px] text-amber-700 font-semibold">{{ count($availableHarvests) }} Stok Tersedia</span>
                    </div>
                    <select id="harvestPicker" onchange="applyHarvestData(this)" class="w-full border border-amber-300 rounded-lg p-2 text-xs bg-white text-gray-900 font-medium">
                        <option value="">-- Pilih Stok Panen (Atau Isi Manual di Bawah) --</option>
                        @foreach($availableHarvests as $h)
                            <option value="{{ $h->id }}" 
                                    data-coop-id="{{ $h->farm_coop_id }}"
                                    data-desc="{{ $h->type === 'panen_broiler' ? 'Panen Ayam Broiler - ' . ($h->coop->name ?? '') : 'Afkir Ayam Layer - ' . ($h->coop->name ?? '') }} ({{ number_format($h->chicken_count) }} ekor / {{ number_format($h->total_weight_kg, 1) }} kg)"
                                    data-qty="{{ $h->total_weight_kg }}"
                                    data-price="{{ $h->reference_price_per_kg > 0 ? $h->reference_price_per_kg : 22000 }}">
                                Tgl {{ \Carbon\Carbon::parse($h->harvest_date)->format('d/m/Y') }} | {{ $h->coop->name ?? '-' }} | {{ $h->type === 'panen_broiler' ? 'Broiler' : 'Afkir' }} | {{ number_format($h->chicken_count) }} ekor ({{ number_format($h->total_weight_kg, 1) }} kg)
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="farm_harvest_log_id" id="farm_harvest_log_id" value="">
                </div>
                @endif

                {{-- Header Info --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">No. Faktur <span class="text-red-500">*</span></label>
                        <input type="text" name="invoice_number" value="{{ old('invoice_number', $invoiceNumber) }}" required class="w-full border border-gray-300 rounded-lg p-2 text-sm font-bold text-amber-600">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Tanggal Faktur <span class="text-red-500">*</span></label>
                        <input type="date" name="invoice_date" value="{{ old('invoice_date', date('Y-m-d')) }}" required class="w-full border border-gray-300 rounded-lg p-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Jatuh Tempo (Due Date)</label>
                        <input type="date" name="due_date" value="{{ old('due_date', date('Y-m-d', strtotime('+14 days'))) }}" class="w-full border border-gray-300 rounded-lg p-2 text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Pembeli / Customer <span class="text-red-500">*</span></label>
                        <select name="farm_customer_id" required class="w-full border border-gray-300 rounded-lg p-2 text-sm bg-white">
                            <option value="">-- Pilih Customer --</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}" @selected(old('farm_customer_id') == $c->id)>{{ $c->name }} {{ $c->city ? '('.$c->city.')' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Asal Kandang</label>
                        <select name="farm_coop_id" id="farm_coop_id" class="w-full border border-gray-300 rounded-lg p-2 text-sm bg-white">
                            <option value="">-- Pilih Kandang --</option>
                            @foreach($coops as $coop)
                                <option value="{{ $coop->id }}" @selected(old('farm_coop_id') == $coop->id)>{{ $coop->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Metode Pembayaran</label>
                        <select name="payment_method" class="w-full border border-gray-300 rounded-lg p-2 text-sm bg-white">
                            <option value="transfer" @selected(old('payment_method') == 'transfer')>Transfer Bank</option>
                            <option value="cash" @selected(old('payment_method') == 'cash')>Tunai / Cash</option>
                            <option value="tempo" @selected(old('payment_method') == 'tempo')>Kredit / Tempo</option>
                        </select>
                    </div>
                </div>

                {{-- Line Items Table --}}
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="font-bold text-sm text-gray-800 uppercase">Item Penjualan / Rincian Panen</h3>
                        <button type="button" onclick="addItemRow()" class="bg-gray-100 border border-gray-300 text-gray-800 px-3 py-1 rounded text-xs font-bold hover:bg-gray-200">+ Tambah Baris</button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm border-collapse border border-gray-200">
                            <thead>
                                <tr class="bg-gray-100 text-xs text-gray-600 uppercase font-semibold">
                                    <th class="p-2 border text-left">Deskripsi Barang / Panen</th>
                                    <th class="p-2 border text-right w-28">Qty</th>
                                    <th class="p-2 border text-left w-24">Satuan</th>
                                    <th class="p-2 border text-right w-36">Harga Satuan (Rp)</th>
                                    <th class="p-2 border text-right w-36">Total (Rp)</th>
                                    <th class="p-2 border w-12 text-center"></th>
                                </tr>
                            </thead>
                            <tbody id="itemsTbody">
                                {{-- Rows generated dynamically --}}
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Grand Total Display --}}
                <div class="flex justify-end mb-6">
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 min-w-[280px] text-right">
                        <div class="text-xs font-bold text-amber-800 uppercase">Total Faktur Penjualan</div>
                        <div id="grandTotalDisplay" class="text-2xl font-bold text-amber-600 mt-1 font-mono">Rp 0</div>
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Catatan Faktur</label>
                    <textarea name="notes" rows="2" placeholder="Catatan syarat pembayaran, rincian timbangan, dll..." class="w-full border border-gray-300 rounded-lg p-2 text-sm">{{ old('notes') }}</textarea>
                </div>

                <div class="flex justify-end gap-2">
                    <a href="{{ route('farm.invoices.index') }}" class="border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-gray-50">Batal</a>
                    <button type="submit" class="bg-amber-600 text-white font-bold px-5 py-2 rounded-lg text-sm hover:bg-amber-700 shadow">Simpan Faktur Penjualan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let itemIndex = 0;

        function addItemRow(desc = 'Ayam Broiler Panen', qty = 1000, unit = 'kg', price = 22000) {
            const tbody = document.getElementById('itemsTbody');
            const tr = document.createElement('tr');
            tr.className = 'border-b border-gray-200 item-row';
            tr.innerHTML = `
                <td class="p-2 border">
                    <input type="text" name="items[${itemIndex}][description]" value="${desc}" required class="w-full border border-gray-300 rounded p-1.5 text-xs">
                </td>
                <td class="p-2 border">
                    <input type="number" step="0.01" min="0" name="items[${itemIndex}][qty]" value="${qty}" oninput="calcRow(${itemIndex})" id="qty_${itemIndex}" required class="w-full border border-gray-300 rounded p-1.5 text-xs text-right font-mono">
                </td>
                <td class="p-2 border">
                    <input type="text" name="items[${itemIndex}][unit]" value="${unit}" class="w-full border border-gray-300 rounded p-1.5 text-xs">
                </td>
                <td class="p-2 border">
                    <input type="number" min="0" name="items[${itemIndex}][unit_price]" value="${price}" oninput="calcRow(${itemIndex})" id="price_${itemIndex}" required class="w-full border border-gray-300 rounded p-1.5 text-xs text-right font-mono">
                </td>
                <td class="p-2 border text-right font-mono font-bold text-gray-900" id="total_${itemIndex}">
                    Rp 0
                </td>
                <td class="p-2 border text-center">
                    <button type="button" onclick="this.closest('tr').remove(); calcGrandTotal();" class="text-red-600 hover:text-red-800 text-xs font-bold">X</button>
                </td>
            `;
            tbody.appendChild(tr);
            calcRow(itemIndex);
            itemIndex++;
        }

        function calcRow(idx) {
            const qty = parseFloat(document.getElementById(`qty_${idx}`)?.value || 0);
            const price = parseFloat(document.getElementById(`price_${idx}`)?.value || 0);
            const total = qty * price;
            const totalEl = document.getElementById(`total_${idx}`);
            if (totalEl) {
                totalEl.innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
            }
            calcGrandTotal();
        }

        function calcGrandTotal() {
            let grandTotal = 0;
            const rows = document.querySelectorAll('#itemsTbody tr');
            rows.forEach((row) => {
                const qtyInput = row.querySelector('input[name*="[qty]"]');
                const priceInput = row.querySelector('input[name*="[unit_price]"]');
                if (qtyInput && priceInput) {
                    grandTotal += (parseFloat(qtyInput.value || 0) * parseFloat(priceInput.value || 0));
                }
            });
            document.getElementById('grandTotalDisplay').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(grandTotal);
        }

        function applyHarvestData(selectEl) {
            const selectedOpt = selectEl.options[selectEl.selectedIndex];
            if (!selectedOpt.value) return;

            const harvestId = selectedOpt.value;
            const coopId = selectedOpt.getAttribute('data-coop-id');
            const desc = selectedOpt.getAttribute('data-desc');
            const qty = parseFloat(selectedOpt.getAttribute('data-qty') || 0);
            const price = parseFloat(selectedOpt.getAttribute('data-price') || 0);

            document.getElementById('farm_harvest_log_id').value = harvestId;
            if (coopId) {
                document.getElementById('farm_coop_id').value = coopId;
            }

            // Clear existing rows & set new harvest item row
            document.getElementById('itemsTbody').innerHTML = '';
            addItemRow(desc, qty, 'kg', price);
        }

        document.addEventListener('DOMContentLoaded', function() {
            addItemRow();
        });
    </script>
</x-farm-layout>
