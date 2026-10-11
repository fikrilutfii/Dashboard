<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Invoice') }} : {{ $invoice->faktur_number }} <span class="text-sm font-normal text-gray-500">(OP: {{ $invoice->invoice_number ?? '-' }})</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">{{ session('error') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Top Actions -->
                    <div class="flex justify-between items-center mb-6">
                        <a href="{{ route('invoices.index') }}" class="text-blue-500 hover:underline">&laquo; Kembali</a>
                        <div class="flex gap-2 flex-wrap">
                            <a href="{{ route('invoices.print', $invoice) }}" target="_blank" class="bg-gray-800 text-white px-4 py-2 rounded text-sm">Print SJ</a>
                            <a href="{{ route('invoices.print-combined', $invoice) }}" target="_blank" class="bg-blue-600 text-white px-4 py-2 rounded text-sm">Print Gabungan</a>
                            <a href="{{ route('invoices.print-excel', $invoice) }}" target="_blank" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm">Print Faktur</a>
                            <a href="{{ route('invoices.edit', $invoice) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded text-sm font-semibold">Edit Invoice</a>
                        </div>
                    </div>

                    <!-- Invoice Header Info -->
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="font-bold text-lg mb-2">Customer</h3>
                            <div class="bg-gray-50 p-4 rounded border">
                                <p class="font-bold">{{ $invoice->customer->name }}</p>
                                <p>{{ $invoice->customer->address }}</p>
                                <p>{{ $invoice->customer->phone }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <h3 class="font-bold text-lg mb-2">Info Invoice</h3>
                            <p>No. Faktur: <strong>{{ $invoice->faktur_number }}</strong></p>
                            <p>OP No: <strong>{{ $invoice->invoice_number ?? '-' }}</strong></p>
                            <p>Tanggal: <strong>{{ $invoice->invoice_date->format('d F Y') }}</strong></p>
                            <p>Status:
                                <span class="px-2 py-1 rounded text-sm font-bold
                                    {{ $invoice->status == 'lunas' ? 'bg-green-200 text-green-800' : ($invoice->status == 'sebagian' ? 'bg-yellow-200 text-yellow-800' : 'bg-red-200 text-red-800') }}">
                                    {{ str_replace('_', ' ', strtoupper($invoice->status)) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <!-- Payment Summary Cards -->
                    <div class="grid grid-cols-3 gap-4 mb-6">
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                            <p class="text-xs text-blue-500 font-bold uppercase">Total Tagihan</p>
                            <p class="text-xl font-bold text-blue-700 font-mono">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                            <p class="text-xs text-green-500 font-bold uppercase">Sudah Dibayar</p>
                            <p class="text-xl font-bold text-green-700 font-mono">Rp {{ number_format($invoice->paid_amount ?? 0, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                            <p class="text-xs text-red-500 font-bold uppercase">Sisa Tagihan</p>
                            <p class="text-xl font-bold text-red-700 font-mono">Rp {{ number_format(max(0, $invoice->total_amount - ($invoice->paid_amount ?? 0)), 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <!-- Status Toggle & Add Payment -->
                    <div class="mb-6 border rounded-lg p-4 bg-gray-50 flex flex-wrap justify-between items-center gap-3">
                        <div class="font-bold text-gray-700">Manajemen Pembayaran:</div>
                        <div class="flex gap-2 flex-wrap">
                            @if($invoice->status != 'lunas')
                                <!-- Add Payment Button -->
                                <button onclick="document.getElementById('paymentModal').classList.remove('hidden')"
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-5 rounded shadow text-sm">
                                    + Catat Pelunasan
                                </button>
                            @endif

                            @if($invoice->status != 'lunas')
                                <form action="{{ route('invoices.update-status', $invoice) }}" method="POST"
                                    class="action-confirm inline-block"
                                    data-title="Konfirmasi Pelunasan Faktur" 
                                    data-text="Apakah Anda yakin ingin menandai Faktur #{{ $invoice->faktur_number }} ini sebagai LUNAS? Saldo dan transaksi pelunasan akan dicatat otomatis." 
                                    data-icon="question" 
                                    data-confirm-text="Ya, Tandai Lunas" 
                                    data-confirm-color="#16a34a">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="status" value="lunas">
                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-5 rounded shadow text-sm">
                                        Tandai LUNAS
                                    </button>
                                </form>
                            @endif

                            @if($invoice->status != 'belum_lunas')
                                <form action="{{ route('invoices.update-status', $invoice) }}" method="POST"
                                    class="action-confirm inline-block"
                                    data-title="Batalkan Status Lunas?" 
                                    data-text="Kembalikan status faktur ini ke BELUM LUNAS? Riwayat pembayaran terkait akan dihapus/direset sehingga faktur dapat diperbaiki." 
                                    data-icon="warning" 
                                    data-confirm-text="Ya, Batal Lunas" 
                                    data-confirm-color="#ef4444">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="status" value="belum_lunas">
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-5 rounded shadow text-sm">
                                        Reset ke Belum Lunas
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- Payment History Table -->
                    @php $payments = $invoice->payments; @endphp
                    @if($payments->count() > 0)
                    <div class="mb-8">
                        <h3 class="font-bold text-lg mb-2 border-b pb-1">Riwayat Pelunasan</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full border-collapse border border-gray-200 text-sm">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="border p-2 text-left">Tanggal</th>
                                        <th class="border p-2 text-left">Metode</th>
                                        <th class="border p-2 text-left">Referensi</th>
                                        <th class="border p-2 text-left">Catatan</th>
                                        <th class="border p-2 text-right">Jumlah</th>
                                        <th class="border p-2 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $payment)
                                    <tr class="hover:bg-gray-50">
                                        <td class="border p-2">{{ $payment->payment_date->format('d/m/Y') }}</td>
                                        <td class="border p-2">{{ $payment->payment_method }}</td>
                                        <td class="border p-2 font-mono text-xs">{{ $payment->reference_number ?? '-' }}</td>
                                        <td class="border p-2 text-gray-600 text-xs">{{ $payment->notes ?? '-' }}</td>
                                        <td class="border p-2 text-right font-bold font-mono text-green-700">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                        <td class="border p-2 text-center whitespace-nowrap">
                                            <div class="flex items-center justify-center gap-1.5">
                                                <button type="button"
                                                    onclick="openEditPaymentModal({{ $payment->id }}, '{{ $payment->payment_date->format('Y-m-d') }}', '{{ $payment->payment_method }}', {{ (float)$payment->amount }}, '{{ addslashes($payment->reference_number ?? '') }}', '{{ addslashes($payment->notes ?? '') }}', {{ max(0, (float)$invoice->total_amount - (float)($invoice->payments->where('id', '!=', $payment->id)->sum('amount'))) }})"
                                                    class="px-2 py-1 bg-amber-50 text-amber-700 hover:bg-amber-100 rounded text-xs font-bold border border-amber-200">
                                                    Edit
                                                </button>
                                                <form action="{{ route('invoices.payment.destroy', [$invoice, $payment]) }}" method="POST"
                                                    class="action-confirm inline-block"
                                                    data-title="Batalkan & Hapus Pembayaran?" data-text="Hapus pelunasan sebesar Rp {{ number_format($payment->amount, 0, ',', '.') }}? Status faktur akan disesuaikan kembali." data-icon="warning" data-confirm-text="Ya, Hapus" data-confirm-color="#ef4444">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="px-2 py-1 bg-red-50 text-red-600 hover:bg-red-100 rounded text-xs font-bold border border-red-200">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50 font-bold">
                                    <tr>
                                        <td colspan="4" class="border p-2 text-right">Total Dibayar:</td>
                                        <td class="border p-2 text-right font-mono text-green-700">Rp {{ number_format($payments->sum('amount'), 0, ',', '.') }}</td>
                                        <td class="border p-2"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    @endif

                    <!-- Items Table -->
                    <h3 class="font-bold text-lg mb-2">Item Invoice</h3>
                    <table class="w-full border-collapse border border-gray-300 mb-8">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="border p-2 text-left">Kode Barang</th>
                                <th class="border p-2 text-left">Nama Barang</th>
                                <th class="border p-2 text-center">Qty</th>
                                <th class="border p-2 text-right">Harga Satuan</th>
                                <th class="border p-2 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($invoice->items as $item)
                                <tr>
                                    <td class="border p-2 font-mono text-sm">{{ $item->product_code ?? '-' }}</td>
                                    <td class="border p-2">{{ $item->item_name }}</td>
                                    <td class="border p-2 text-center">{{ $item->formatted_quantity }}</td>
                                    <td class="border p-2 text-right">{{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                    <td class="border p-2 text-right font-bold">{{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-200">
                                <td colspan="4" class="border p-2 text-right font-bold">TOTAL TAGIHAN</td>
                                <td class="border p-2 text-right font-bold text-lg">{{ number_format($invoice->total_amount, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>

                    <!-- Activity Log -->
                    @if($invoice->logs->count() > 0)
                        <div class="mt-4">
                            <h4 class="font-bold text-gray-600 border-b mb-2">Riwayat Aktivitas</h4>
                            <ul class="text-sm text-gray-500 max-h-40 overflow-y-auto">
                                @foreach($invoice->logs as $log)
                                    <li class="mb-1">
                                        <span class="font-mono text-xs">[{{ $log->created_at->format('d/m/Y H:i') }}]</span>
                                        <strong>{{ $log->action }}:</strong> {{ $log->description }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div id="paymentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-bold mb-4 text-gray-800">Catat Pelunasan Faktur</h3>
            <p class="text-sm text-gray-500 mb-4">
                Faktur: <strong>{{ $invoice->faktur_number }}</strong> &mdash; Sisa:
                <strong class="text-red-600">Rp {{ number_format(max(0, $invoice->total_amount - ($invoice->paid_amount ?? 0)), 0, ',', '.') }}</strong>
            </p>
            <form action="{{ route('invoices.payment.store', $invoice) }}" method="POST" class="action-confirm" data-title="Konfirmasi Pembayaran" data-text="Simpan catatan pelunasan faktur ini? Status faktur dan pencatatan kas akan diperbarui." data-icon="question" data-confirm-text="Ya, Simpan" data-confirm-color="#2563eb">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal Pembayaran <span class="text-red-500">*</span></label>
                        <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required
                            class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Jumlah Pembayaran (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="amount" min="1"
                            max="{{ max(0, $invoice->total_amount - ($invoice->paid_amount ?? 0)) > 0 ? max(0, $invoice->total_amount - ($invoice->paid_amount ?? 0)) : $invoice->total_amount }}"
                            value="{{ max(0, $invoice->total_amount - ($invoice->paid_amount ?? 0)) }}" required
                            class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Metode Pembayaran <span class="text-red-500">*</span></label>
                        <select name="payment_method" required class="w-full border rounded px-3 py-2 bg-white focus:ring-2 focus:ring-blue-500">
                            <option value="Transfer Bank">Transfer Bank</option>
                            <option value="Tunai">Tunai</option>
                            <option value="Cek/Giro">Cek/Giro</option>
                            <option value="QRIS">QRIS</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">No. Referensi / Bukti <span class="text-gray-400 font-normal">(Opsional)</span></label>
                        <input type="text" name="reference_number" placeholder="Contoh: TRF-20241001"
                            class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Catatan <span class="text-gray-400 font-normal">(Opsional)</span></label>
                        <textarea name="notes" rows="2" placeholder="Catatan tambahan..."
                            class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="button" onclick="document.getElementById('paymentModal').classList.add('hidden')"
                            class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
                            Simpan Pembayaran
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Payment Modal -->
    <div id="editPaymentModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm hidden flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl relative">
            <div class="flex justify-between items-center mb-4 pb-2 border-b">
                <div>
                    <h3 class="font-bold text-lg text-gray-800">Edit Riwayat Pembayaran</h3>
                    <p class="text-xs text-gray-400">Ubah data pelunasan faktur</p>
                </div>
                <button type="button" onclick="closeEditPaymentModal()" class="text-gray-400 hover:text-gray-600 text-lg font-bold">&times;</button>
            </div>
            <form id="editPaymentForm" method="POST" class="action-confirm"
                data-title="Konfirmasi Perubahan Pembayaran"
                data-text="Simpan perubahan pembayaran ini? Status faktur dan buku kas keuangan akan disinkronkan."
                data-icon="question"
                data-confirm-text="Ya, Simpan Perubahan"
                data-confirm-color="#2563eb">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal Pembayaran <span class="text-red-500">*</span></label>
                        <input type="date" name="payment_date" id="edit_payment_date" required
                            class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">
                            Jumlah Pembayaran (Rp) <span class="text-red-500">*</span>
                            <span class="text-xs font-normal text-gray-400">(Maks: Rp <span id="edit_max_amount_display">0</span>)</span>
                        </label>
                        <input type="number" step="any" name="amount" id="edit_payment_amount" min="1" required
                            class="w-full border rounded px-3 py-2 font-mono focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Metode Pembayaran <span class="text-red-500">*</span></label>
                        <select name="payment_method" id="edit_payment_method" required class="w-full border rounded px-3 py-2 bg-white focus:ring-2 focus:ring-blue-500">
                            <option value="Transfer Bank">Transfer Bank</option>
                            <option value="Transfer BCA">Transfer BCA</option>
                            <option value="Transfer Mandiri">Transfer Mandiri</option>
                            <option value="Transfer BRI">Transfer BRI</option>
                            <option value="Transfer BNI">Transfer BNI</option>
                            <option value="Tunai">Tunai / Cash</option>
                            <option value="Cek/Giro">Cek/Giro</option>
                            <option value="QRIS">QRIS</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">No. Referensi / Bukti <span class="text-gray-400 font-normal">(Opsional)</span></label>
                        <input type="text" name="reference_number" id="edit_reference_number" placeholder="Contoh: TRF-20241001"
                            class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Catatan <span class="text-gray-400 font-normal">(Opsional)</span></label>
                        <textarea name="notes" id="edit_payment_notes" rows="2" placeholder="Catatan tambahan..."
                            class="w-full border rounded px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="button" onclick="closeEditPaymentModal()"
                            class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditPaymentModal(id, date, method, amount, ref, notes, maxAmount) {
            const form = document.getElementById('editPaymentForm');
            form.action = "{{ url('invoices/' . $invoice->id . '/payment') }}/" + id;
            document.getElementById('edit_payment_date').value = date;
            document.getElementById('edit_payment_method').value = method;
            document.getElementById('edit_payment_amount').value = amount;
            document.getElementById('edit_payment_amount').max = maxAmount;
            document.getElementById('edit_max_amount_display').innerText = new Intl.NumberFormat('id-ID').format(maxAmount);
            document.getElementById('edit_reference_number').value = ref;
            document.getElementById('edit_payment_notes').value = notes;
            
            document.getElementById('editPaymentModal').classList.remove('hidden');
        }

        function closeEditPaymentModal() {
            document.getElementById('editPaymentModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
