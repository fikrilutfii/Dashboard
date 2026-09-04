<?php
$now = \Carbon\Carbon::now(); 
$tagihan = \App\Models\Invoice::where('division', 'percetakan')->whereMonth('invoice_date', $now->month)->whereYear('invoice_date', $now->year)->sum('total_amount'); 
$pembayaran = \App\Models\Transaction::where('type', 'credit')->where('division', 'percetakan')->whereMonth('date', $now->month)->whereYear('date', $now->year)->sum('amount'); 
echo json_encode(['now' => $now->toDateTimeString(), 'tagihan' => $tagihan, 'pembayaran' => $pembayaran]);
