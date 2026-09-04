<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CustomerBillingExport implements FromArray, WithTitle, ShouldAutoSize, WithStyles
{
    protected $customer;
    protected $invoices;

    public function __construct($customer, $invoices)
    {
        $this->customer = $customer;
        $this->invoices = $invoices;
    }

    public function title(): string
    {
        return 'Laporan Tagihan - ' . $this->customer->name;
    }

    public function array(): array
    {
        $data = [
            ['LAPORAN RINCIAN FAKTUR (EXCEL)'],
            ['Klien:', $this->customer->name],
            ['Telepon:', $this->customer->phone ?? '-'],
            ['Email:', $this->customer->email ?? '-'],
            ['Alamat:', $this->customer->address ?? '-'],
            ['Tanggal Cetak:', Carbon::now()->format('d-m-Y H:i')],
            [],
            [
                'No',
                'No. Faktur',
                'PO',
                'Tanggal',
                'Nama Barang',
                'Qty',
                'Harga (Rp)',
                'Subtotal (Rp)'
            ]
        ];

        $totalSemua = 0;
        $no = 1;

        foreach ($this->invoices as $invoice) {
            foreach ($invoice->items as $item) {
                $totalSemua += $item->subtotal;
                $data[] = [
                    $no++,
                    $invoice->faktur_number ?? '-',
                    $invoice->invoice_number ?? '-',
                    $invoice->invoice_date ? Carbon::parse($invoice->invoice_date)->format('d/m/Y') : '-',
                    $item->item_name,
                    $item->quantity,
                    (double) $item->unit_price,
                    (double) $item->subtotal
                ];
            }
        }

        $data[] = [];
        $data[] = [
            'TOTAL KESELURUHAN',
            '',
            '',
            '',
            '',
            '',
            '',
            (double) $totalSemua
        ];

        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        $totalRows = count($this->invoices) + 8;
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            8 => ['font' => ['bold' => true]],
            ($totalRows + 2) => ['font' => ['bold' => true]],
        ];
    }
}
