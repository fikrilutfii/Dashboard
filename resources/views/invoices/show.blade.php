<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Invoice') }} : {{ $invoice->invoice_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Top Actions -->
                    <div class="flex justify-between items-center mb-6">
                        <a href="{{ route('invoices.index') }}" class="text-blue-500 hover:underline">&laquo; Kembali</a>
                        <div class="flex gap-2">
                             <a href="{{ route('invoices.print', $invoice) }}" target="_blank" class="bg-gray-800 text-white px-4 py-2 rounded">
                                Print Invoice
                            </a>
                            @if($invoice->status != 'lunas')
                                <a href="{{ route('invoices.edit', $invoice) }}" class="bg-yellow-500 text-white px-4 py-2 rounded">
                                    Edit Invoice
                                </a>
                            @endif
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
                            <p>Tanggal: <strong>{{ $invoice->invoice_date->format('d F Y') }}</strong></p>
                            <p>Status: 
                                <span class="px-2 py-1 rounded text-sm {{ $invoice->status == 'lunas' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                    {{ str_replace('_', ' ', strtoupper($invoice->status)) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <!-- Simplified Status Toggle -->
                    <div class="mb-8 border-t border-b py-4 bg-gray-50 flex justify-between items-center px-4">
                        <div class="font-bold text-gray-700">Ubah Status Pembayaran:</div>
                        <div>
                            @if($invoice->status == 'belum_lunas')
                                <form action="{{ route('invoices.update-status', $invoice) }}" method="POST" onsubmit="return confirm('Tandai invoice ini sebagai LUNAS?');">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="lunas">
                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded shadow">
                                        Tandai LUNAS
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('invoices.update-status', $invoice) }}" method="POST" onsubmit="return confirm('Kembalikan status ke BELUM LUNAS?');">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="belum_lunas">
                                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-6 rounded shadow">
                                        Tandai BELUM LUNAS
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

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
                                    <td class="border p-2 text-center">{{ $item->quantity }}</td>
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

                    <!-- Validation Log -->
                    @if($invoice->logs->count() > 0)
                        <div class="mt-8">
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
</x-app-layout>
