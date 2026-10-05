<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\Transaction;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Cleanup any kasbon from general transactions table
        try {
            DB::table('transactions')
                ->where('category', 'like', '%kasbon%')
                ->orWhere('reference_type', 'like', '%Kasbon%')
                ->delete();
        } catch (\Exception $e) {}

        // 2. Ensure existing paid invoices have invoice_payment and transaction
        try {
            $paidInvoices = Invoice::where('status', 'lunas')->orWhere('paid_amount', '>', 0)->get();

            foreach ($paidInvoices as $invoice) {
                $paymentAmount = $invoice->paid_amount > 0 ? $invoice->paid_amount : $invoice->total_amount;
                if ($paymentAmount <= 0) continue;

                $existingPayment = InvoicePayment::where('invoice_id', $invoice->id)->first();
                if (!$existingPayment) {
                    $payDate = $invoice->invoice_date ? $invoice->invoice_date->format('Y-m-d') : date('Y-m-d');
                    $payment = InvoicePayment::create([
                        'invoice_id'       => $invoice->id,
                        'payment_date'     => $payDate,
                        'amount'           => $paymentAmount,
                        'payment_method'   => $invoice->payment_method === 'credit' ? 'Transfer Bank' : 'Tunai',
                        'reference_number' => null,
                        'notes'            => 'Sinkronisasi pelunasan faktur awal',
                    ]);

                    // Check if transaction already exists
                    $existingTrx = Transaction::where('reference_type', InvoicePayment::class)
                        ->where('reference_id', $payment->id)
                        ->first();

                    if (!$existingTrx) {
                        Transaction::create([
                            'type'           => 'credit',
                            'amount'         => $paymentAmount,
                            'category'       => 'pelunasan_faktur',
                            'reference_type' => InvoicePayment::class,
                            'reference_id'   => $payment->id,
                            'description'    => 'Pelunasan Faktur #' . ($invoice->faktur_number ?? $invoice->invoice_number) . ' - ' . ($invoice->customer->name ?? 'Customer'),
                            'date'           => $payDate,
                            'division'       => $invoice->division,
                            'entity'         => $invoice->entity ?? $invoice->division,
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {}
    }

    public function down(): void
    {
    }
};
