<x-farm-layout title="Edit Faktur Penjualan" subtitle="{{ $invoice->invoice_number }}">
    <div style="max-width:960px;margin:0 auto;">
        <div class="ios-card" style="padding:28px;">
            <form action="{{ route('farm.invoices.update', $invoice) }}" method="POST" id="invoiceForm">
                @csrf
                @method('PUT')

                <!-- Header Info -->
                <div style="display:grid;grid-template-columns:1.5fr 1fr 1fr;gap:16px;margin-bottom:20px;">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">No. Faktur <span style="color:#ff3b30;">*</span></label>
                        <input type="text" name="invoice_number" value="{{ old('invoice_number', $invoice->invoice_number) }}" required class="ios-input" style="font-weight:700;color:#d97706;">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Tanggal Faktur <span style="color:#ff3b30;">*</span></label>
                        <input type="date" name="invoice_date" value="{{ old('invoice_date', $invoice->invoice_date) }}" required class="ios-input">
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Jatuh Tempo (Due Date)</label>
                        <input type="date" name="due_date" value="{{ old('due_date', $invoice->due_date) }}" class="ios-input">
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1.5fr 1fr 1fr;gap:16px;margin-bottom:28px;">
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Customer / Pembeli Panen <span style="color:#ff3b30;">*</span></label>
                        <select name="farm_customer_id" required class="ios-input">
                            <option value="">-- Pilih Customer --</option>
                            @foreach($customers as $c)
                                <option value="{{ $c->id }}" @selected(old('farm_customer_id', $invoice->farm_customer_id) == $c->id)>{{ $c->name }} {{ $c->city ? '('.$c->city.')' : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Asal Kandang (Panen)</label>
                        <select name="farm_coop_id" class="ios-input">
                            <option value="">-- Pilih Kandang --</option>
                            @foreach($coops as $coop)
                                <option value="{{ $coop->id }}" @selected(old('farm_coop_id', $invoice->farm_coop_id) == $coop->id)>{{ $coop->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Metode Pembayaran</label>
                        <select name="payment_method" class="ios-input">
                            <option value="transfer" @selected(old('payment_method', $invoice->payment_method) == 'transfer')>Transfer Bank</option>
                            <option value="cash" @selected(old('payment_method', $invoice->payment_method) == 'cash')>Tunai / Cash</option>
                            <option value="tempo" @selected(old('payment_method', $invoice->payment_method) == 'tempo')>Kredit / Tempo</option>
                        </select>
                    </div>
                </div>

                <!-- Line Items Table -->
                <div style="margin-bottom:24px;">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                        <h3 style="font-size:16px;font-weight:700;color:#1c1c1e;margin:0;">Item Penjualan / Rincian Panen</h3>
                        <button type="button" onclick="addItemRow()" class="ios-btn ios-btn-secondary" style="padding:6px 14px;font-size:13px;">+ Tambah Baris Item</button>
                    </div>

                    <table class="ios-table" id="itemsTable">
                        <thead>
                            <tr>
                                <th style="width:40%;">Deskripsi Panen / Barang</th>
                                <th style="width:15%;">Jumlah / Qty</th>
                                <th style="width:15%;">Satuan</th>
                                <th style="width:20%;">Harga Satuan (Rp)</th>
                                <th style="width:10%;text-align:right;">Total (Rp)</th>
                                <th style="width:50px;"></th>
                            </tr>
                        </thead>
                        <tbody id="itemsTbody">
                            <!-- Rows loaded dynamically -->
                        </tbody>
                    </table>
                </div>

                <!-- Grand Total Card -->
                <div style="display:flex;justify-content:flex-end;margin-bottom:24px;">
                    <div style="background:#fef3c7;border:1px solid #fde68a;border-radius:16px;padding:20px;min-width:320px;text-align:right;">
                        <div style="font-size:12px;font-weight:700;color:#92400e;text-transform:uppercase;letter-spacing:0.05em;">Total Faktur Penjualan</div>
                        <div id="grandTotalDisplay" style="font-size:30px;font-weight:800;color:#d97706;margin-top:4px;">Rp 0</div>
                    </div>
                </div>

                <div style="margin-bottom:24px;">
                    <label style="display:block;font-size:13px;font-weight:600;color:#1c1c1e;margin-bottom:8px;">Catatan Faktur</label>
                    <textarea name="notes" rows="2" class="ios-input" style="resize:vertical;">{{ old('notes', $invoice->notes) }}</textarea>
                </div>

                <div style="display:flex;justify-content:flex-end;gap:12px;">
                    <a href="{{ route('farm.invoices.show', $invoice) }}" class="ios-btn ios-btn-secondary">Batal</a>
                    <button type="submit" class="ios-btn ios-btn-primary">Update Faktur Penjualan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let itemIndex = 0;

        function addItemRow(desc = '', qty = 0, unit = 'kg', price = 0) {
            const tbody = document.getElementById('itemsTbody');
            const tr = document.createElement('tr');
            tr.id = `item-row-${itemIndex}`;
            tr.innerHTML = `
                <td>
                    <input type="text" name="items[${itemIndex}][description]" value="${desc}" required class="ios-input" style="padding:8px 12px;font-size:14px;">
                </td>
                <td>
                    <input type="number" step="0.01" name="items[${itemIndex}][qty]" value="${qty}" required min="0" oninput="calculateRow(${itemIndex})" class="ios-input item-qty" style="padding:8px 12px;font-size:14px;">
                </td>
                <td>
                    <select name="items[${itemIndex}][unit]" class="ios-input" style="padding:8px 12px;font-size:14px;">
                        <option value="kg" ${unit === 'kg' ? 'selected' : ''}>Kg (Kilogram)</option>
                        <option value="ekor" ${unit === 'ekor' ? 'selected' : ''}>Ekor</option>
                        <option value="box" ${unit === 'box' ? 'selected' : ''}>Box / Keranjang</option>
                        <option value="pcs" ${unit === 'pcs' ? 'selected' : ''}>Pcs</option>
                    </select>
                </td>
                <td>
                    <input type="number" name="items[${itemIndex}][unit_price]" value="${price}" required min="0" oninput="calculateRow(${itemIndex})" class="ios-input item-price" style="padding:8px 12px;font-size:14px;">
                </td>
                <td style="text-align:right;font-weight:700;color:#1c1c1e;">
                    <span id="row-total-${itemIndex}">Rp 0</span>
                </td>
                <td style="text-align:center;">
                    <button type="button" onclick="removeRow(${itemIndex})" class="ios-btn ios-btn-danger" style="padding:4px 8px;font-size:12px;">✕</button>
                </td>
            `;
            tbody.appendChild(tr);
            calculateRow(itemIndex);
            itemIndex++;
        }

        function removeRow(idx) {
            const tr = document.getElementById(`item-row-${idx}`);
            if (tr) tr.remove();
            calculateGrandTotal();
        }

        function calculateRow(idx) {
            const tr = document.getElementById(`item-row-${idx}`);
            if (!tr) return;
            const qty = parseFloat(tr.querySelector('.item-qty').value) || 0;
            const price = parseFloat(tr.querySelector('.item-price').value) || 0;
            const total = qty * price;
            document.getElementById(`row-total-${idx}`).innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
            calculateGrandTotal();
        }

        function calculateGrandTotal() {
            let total = 0;
            const rows = document.querySelectorAll('#itemsTbody tr');
            rows.forEach(tr => {
                const qty = parseFloat(tr.querySelector('.item-qty')?.value) || 0;
                const price = parseFloat(tr.querySelector('.item-price')?.value) || 0;
                total += (qty * price);
            });
            document.getElementById('grandTotalDisplay').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
        }

        // Initialize with existing items
        document.addEventListener('DOMContentLoaded', () => {
            @if($invoice->items && $invoice->items->count() > 0)
                @foreach($invoice->items as $item)
                    addItemRow("{{ addslashes($item->description) }}", {{ $item->qty }}, "{{ $item->unit }}", {{ $item->unit_price }});
                @endforeach
            @else
                addItemRow();
            @endif
        });
    </script>
</x-farm-layout>
