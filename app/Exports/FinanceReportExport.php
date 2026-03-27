<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FinanceReportExport implements FromCollection, WithHeadings, WithMapping, WithTitle, ShouldAutoSize, WithStyles
{
    protected $transactions;
    protected $filters;

    public function __construct($transactions, $filters)
    {
        $this->transactions = $transactions;
        $this->filters = $filters;
    }

    public function collection()
    {
        return $this->transactions;
    }

    public function title(): string
    {
        return 'Laporan Keuangan';
    }

    public function headings(): array
    {
        return [
            ['LAPORAN KEUANGAN & ARUS KAS'],
            ['Periode:', $this->filters['start_date'] . ' s/d ' . $this->filters['end_date']],
            ['Divisi:', ucfirst($this->filters['division'] ?? 'Semua')],
            [],
            [
                'Tanggal',
                'Keterangan',
                'Kategori',
                'Divisi',
                'Masuk (+)',
                'Keluar (-)'
            ]
        ];
    }

    public function map($trx): array
    {
        return [
            Carbon::parse($trx->date)->format('d/m/Y'),
            $trx->description,
            ucwords(str_replace('_', ' ', $trx->category)),
            ucfirst($trx->division),
            $trx->type === 'credit' ? $trx->amount : 0,
            $trx->type === 'debit' ? $trx->amount : 0,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            5 => ['font' => ['bold' => true]],
        ];
    }
}
