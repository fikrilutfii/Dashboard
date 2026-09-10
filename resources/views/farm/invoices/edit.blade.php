<x-farm.layout title="Edit Faktur Penjualan" subtitle="{{ $invoice->invoice_number }}">

    <div style="max-width: 960px; margin: 0 auto;">
        <form method="POST" action="{{ route('farm.invoices.update', $invoice->id) }}" x-data="invoiceEditForm()">
            @csrf
            @method('PUT')

            <!-- Header Info Card -->
            <div class="ios-card" style="padding: 24px; margin-bottom: 20px;">
                <h3 style="font-size: 16px; font-weight: 800; color: #09090b; margin: 0 0 16px;">Informasi Faktur & Pengirim</h3>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">
                            Nama / Identitas Pengirim Faktur <span style="color: #dc2626;">*</span>
                        </label>
                        <select name="farm_sender_id" class="ios-input" required>
                            @foreach($senders as $s)
                            <option value="{{ $s->id }}" {{ $invoice->farm_sender_id == $s->id ? 'selected' : '' }}>
                                {{ $s->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">
                            Pelanggan / Customer <span style="color: #dc2626;">*</span>
                        </label>
                        <select name="farm_customer_id" class="ios-input" required>
                            @foreach($customers as $c)
                            <option value="{{ $c->id }}" {{ $invoice->farm_customer_id == $c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">
                            Tanggal Faktur <span style="color: #dc2626;">*</span>
                        </label>
                        <input type="date" name="invoice_date" value="{{ old('invoice_date', $invoice->invoice_date->format('Y-m-d')) }}" class="ios-input" required>
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">
                            Tanggal Jatuh Tempo
                        </label>
                        <input type="date" name="due_date" value="{{ old('due_date', $invoice->due_date ? $invoice->due_date->format('Y-m-d') : '') }}" class="ios-input">
                    </div>
                </div>
            </div>

            <!-- Line Items Card -->
            <div class="ios-card" style="padding: 24px; margin-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                    <div>
                        <h3 style="font-size: 16px; font-weight: 800; color: #09090b; margin: 0;">Rincian Barang Karkas / Parting</h3>
                        <p style="font-size: 12px; color: #71717a; margin: 2px 0 0;">Stok produk akan disesuaikan otomatis</p>
                    </div>
                    <button type="button" @click="addItem()" class="ios-btn ios-btn-secondary" style="font-size: 12.5px; padding: 7px 14px;">
                        + Tambah Baris
                    </button>
                </div>

                <div style="overflow-x: auto;">
                    <table class="ios-table">
                        <thead>
                            <tr>
                                <th style="width: 28%;">Pilih Produk</th>
                                <th style="width: 22%;">Nama Produk</th>
                                <th style="width: 14%; text-align: right;">Berat (Kg)</th>
                                <th style="width: 10%; text-align: right;">Ekor</th>
                                <th style="width: 14%; text-align: right;">Harga / Satuan</th>
                                <th style="width: 16%; text-align: right;">Subtotal (Rp)</th>
                                <th style="width: 6%; text-align: center;">Hapus</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template x-for="(item, index) in items" :key="index">
                                <tr>
                                    <td>
                                        <select :name="`items[${index}][farm_product_id]`" class="ios-input" style="padding: 8px 12px; font-size: 12.5px;" x-model="item.farm_product_id" @change="onProductSelect(index, $event.target.value)">
                                            <option value="">-- Manual / Custom --</option>
                                            @foreach($products as $p)
                                            <option value="{{ $p->id }}" data-code="{{ $p->code }}" data-name="{{ $p->name }}" data-price="{{ $p->standard_price }}" data-unit="{{ $p->unit }}">
                                                {{ $p->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" :name="`items[${index}][product_code]`" x-model="item.product_code">
                                        <input type="hidden" :name="`items[${index}][unit]`" x-model="item.unit">
                                    </td>
                                    <td>
                                        <input type="text" :name="`items[${index}][item_name]`" x-model="item.item_name" class="ios-input" style="padding: 8px 12px; font-size: 12.5px;" required>
                                    </td>
                                    <td>
                                        <input type="number" :name="`items[${index}][weight_kg]`" x-model.number="item.weight_kg" step="any" min="0" class="ios-input" style="padding: 8px 12px; text-align: right; font-size: 12.5px;" @input="calcSubtotal(index)">
                                    </td>
                                    <td>
                                        <input type="number" :name="`items[${index}][qty_ekor]`" x-model.number="item.qty_ekor" min="0" class="ios-input" style="padding: 8px 12px; text-align: right; font-size: 12.5px;">
                                    </td>
                                    <td>
                                        <input type="number" :name="`items[${index}][unit_price]`" x-model.number="item.unit_price" step="any" min="0" class="ios-input" style="padding: 8px 12px; text-align: right; font-size: 12.5px;" @input="calcSubtotal(index)" required>
                                    </td>
                                    <td style="text-align: right; font-weight: 700; font-size: 13.5px;">
                                        <span x-text="formatRupiah(item.subtotal)"></span>
                                    </td>
                                    <td style="text-align: center;">
                                        <button type="button" @click="removeItem(index)" style="background: none; border: none; color: #f43f5e; cursor: pointer; font-size: 16px; font-weight: 800;" x-show="items.length > 1">&times;</button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- Grand Total Summary -->
                <div style="margin-top: 20px; padding-top: 16px; border-top: 1px solid rgba(0,0,0,0.06); display: flex; justify-content: flex-end;">
                    <div style="width: 320px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 13px; color: #71717a;">
                            <span>Total Berat:</span>
                            <strong style="color: #09090b;" x-text="totalWeightKg.toFixed(1) + ' Kg'"></strong>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 18px; font-weight: 800; color: #09090b; padding-top: 8px; border-top: 2px solid #09090b;">
                            <span>Grand Total:</span>
                            <span x-text="formatRupiah(grandTotal)"></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes Card -->
            <div class="ios-card" style="padding: 24px; margin-bottom: 24px;">
                <label style="display: block; font-size: 12px; font-weight: 700; color: #09090b; margin-bottom: 6px;">Catatan Faktur (Opsional)</label>
                <textarea name="notes" rows="2" class="ios-input">{{ old('notes', $invoice->notes) }}</textarea>
            </div>

            <!-- Action Buttons -->
            <div style="display: flex; justify-content: flex-end; gap: 12px;">
                <a href="{{ route('farm.invoices.show', $invoice->id) }}" class="ios-btn ios-btn-secondary">Batal</a>
                <button type="submit" class="ios-btn ios-btn-primary" style="padding: 12px 28px;">Perbarui Faktur Penjualan</button>
            </div>
        </form>
    </div>

    <script>
        function invoiceEditForm() {
            return {
                items: [
                    @foreach($invoice->items as $it)
                    {
                        farm_product_id: '{{ $it->farm_product_id ?? '' }}',
                        product_code: '{{ $it->product_code ?? '' }}',
                        item_name: '{{ $it->item_name }}',
                        weight_kg: {{ $it->weight_kg }},
                        qty_ekor: {{ $it->qty_ekor }},
                        unit: '{{ $it->unit }}',
                        unit_price: {{ $it->unit_price }},
                        subtotal: {{ $it->total_price }}
                    },
                    @endforeach
                ],
                addItem() {
                    this.items.push({ farm_product_id: '', product_code: '', item_name: '', weight_kg: 0, qty_ekor: 0, unit: 'kg', unit_price: 0, subtotal: 0 });
                },
                removeItem(index) {
                    if (this.items.length > 1) {
                        this.items.splice(index, 1);
                    }
                },
                onProductSelect(index, productId) {
                    const selectEl = event.target;
                    const opt = selectEl.options[selectEl.selectedIndex];
                    if (productId && opt) {
                        this.items[index].product_code = opt.dataset.code || '';
                        this.items[index].item_name = opt.dataset.name || '';
                        this.items[index].unit_price = parseFloat(opt.dataset.price) || 0;
                        this.items[index].unit = opt.dataset.unit || 'kg';
                        this.calcSubtotal(index);
                    }
                },
                calcSubtotal(index) {
                    const item = this.items[index];
                    const qty = item.weight_kg > 0 ? item.weight_kg : (item.qty_ekor > 0 ? item.qty_ekor : 1);
                    item.subtotal = (qty || 0) * (item.unit_price || 0);
                },
                get grandTotal() {
                    return this.items.reduce((acc, item) => acc + (item.subtotal || 0), 0);
                },
                get totalWeightKg() {
                    return this.items.reduce((acc, item) => acc + (parseFloat(item.weight_kg) || 0), 0);
                },
                formatRupiah(num) {
                    return 'Rp ' + (num || 0).toLocaleString('id-ID');
                }
            }
        }
    </script>

</x-farm.layout>
