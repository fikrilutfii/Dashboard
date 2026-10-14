<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    public ?array $activityLogOriginalData = null;

    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            $model->recordActivityLog('TAMBAH');
        });

        static::updating(function ($model) {
            $model->activityLogOriginalData = $model->getOriginal();
        });

        static::updated(function ($model) {
            $model->recordActivityLog('UBAH');
        });

        static::deleting(function ($model) {
            $model->activityLogOriginalData = $model->getAttributes();
        });

        static::deleted(function ($model) {
            $model->recordActivityLog('HAPUS');
        });
    }

    public function recordActivityLog(string $action): void
    {
        $userId = Auth::id() ?? 1;
        $className = class_basename(static::class);
        $displayName = method_exists($this, 'getLogDisplayName') ? $this->getLogDisplayName() : $this->resolveLogIdentifier();

        $ignoredFields = ['updated_at', 'created_at', 'deleted_at', 'remember_token', 'password'];

        $properties = [
            'action' => $action,
            'model'  => $className,
            'target' => $displayName,
        ];

        $description = '';

        if ($action === 'TAMBAH') {
            $rawAttrs = collect($this->getAttributes())->except($ignoredFields)->toArray();
            $formattedAttrs = [];
            foreach ($rawAttrs as $key => $val) {
                if ($val !== null && $val !== '') {
                    $formattedAttrs[] = [
                        'field' => $key,
                        'label' => $this->formatLogFieldLabel($key),
                        'value' => $this->formatLogFieldValue($key, $val),
                    ];
                }
            }

            $description = method_exists($this, 'getLogCreatedDescription')
                ? $this->getLogCreatedDescription()
                : "Menambahkan data {$displayName}";

            $properties['summary'] = $description;
            $properties['details'] = $formattedAttrs;

        } elseif ($action === 'UBAH') {
            $changes = collect($this->getChanges())->except($ignoredFields)->toArray();

            if (empty($changes)) {
                return;
            }

            $original = $this->activityLogOriginalData ?? [];
            $diffList = [];
            $diffSummaryParts = [];

            foreach ($changes as $key => $newVal) {
                $oldVal = $original[$key] ?? null;

                // Skip if value is effectively identical
                if ($oldVal === $newVal) {
                    continue;
                }

                $label = $this->formatLogFieldLabel($key);
                $oldDisplay = $this->formatLogFieldValue($key, $oldVal);
                $newDisplay = $this->formatLogFieldValue($key, $newVal);

                $diffList[] = [
                    'field' => $key,
                    'label' => $label,
                    'old'   => $oldDisplay,
                    'new'   => $newDisplay,
                ];

                $diffSummaryParts[] = "{$label} ({$oldDisplay} → {$newDisplay})";
            }

            if (empty($diffList)) {
                return;
            }

            $changesText = implode(', ', array_slice($diffSummaryParts, 0, 3));
            if (count($diffSummaryParts) > 3) {
                $changesText .= ', +' . (count($diffSummaryParts) - 3) . ' perubahan lainnya';
            }

            $description = method_exists($this, 'getLogUpdatedDescription')
                ? $this->getLogUpdatedDescription($diffList)
                : "Mengubah {$displayName}: {$changesText}";

            $properties['summary'] = $description;
            $properties['changes'] = $diffList;

        } elseif ($action === 'HAPUS') {
            $rawAttrs = collect($this->activityLogOriginalData ?? $this->getAttributes())->except($ignoredFields)->toArray();
            $formattedAttrs = [];
            foreach ($rawAttrs as $key => $val) {
                if ($val !== null && $val !== '') {
                    $formattedAttrs[] = [
                        'field' => $key,
                        'label' => $this->formatLogFieldLabel($key),
                        'value' => $this->formatLogFieldValue($key, $val),
                    ];
                }
            }

            $description = method_exists($this, 'getLogDeletedDescription')
                ? $this->getLogDeletedDescription()
                : "Menghapus data {$displayName}";

            $properties['summary'] = $description;
            $properties['details'] = $formattedAttrs;
        }

        try {
            ActivityLog::create([
                'user_id'      => $userId,
                'activity'     => $action,
                'description'  => $description,
                'subject_type' => static::class,
                'subject_id'   => $this->getKey(),
                'properties'   => $properties,
                'ip_address'   => request()?->ip() ?? '127.0.0.1',
                'user_agent'   => request()?->userAgent() ?? 'System',
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning("Failed to record activity log: " . $e->getMessage());
        }
    }

    protected function resolveLogIdentifier(): string
    {
        $className = class_basename(static::class);
        $labels = [
            'Invoice'           => 'Faktur',
            'InvoicePayment'    => 'Pembayaran Faktur',
            'Purchase'          => 'Pembelian',
            'Product'           => 'Produk',
            'Customer'          => 'Pelanggan',
            'Supplier'          => 'Pemasok',
            'Kasbon'            => 'Kasbon',
            'KasbonRepayment'   => 'Cicilan Kasbon',
            'Payroll'           => 'Gaji',
            'Employee'          => 'Karyawan',
            'Expense'           => 'Pengeluaran',
            'CompanyDebt'       => 'Hutang',
            'CompanyReceivable' => 'Piutang',
            'User'              => 'Pengguna',
        ];
        $label = $labels[$className] ?? $className;

        $candidateFields = ['faktur_number', 'invoice_number', 'po_number', 'purchase_number', 'name', 'code', 'username'];
        foreach ($candidateFields as $f) {
            if (!empty($this->{$f})) {
                return "{$label} #{$this->{$f}}";
            }
        }

        return "{$label} #{$this->getKey()}";
    }

    protected function formatLogFieldLabel(string $field): string
    {
        $labels = [
            'total_amount'       => 'Total Biaya',
            'paid_amount'        => 'Jumlah Dibayar',
            'amount'             => 'Nominal',
            'status'             => 'Status',
            'due_date'           => 'Jatuh Tempo',
            'invoice_date'       => 'Tanggal Faktur',
            'payment_date'       => 'Tanggal Bayar',
            'payment_method'     => 'Metode Pembayaran',
            'price'              => 'Harga Jual',
            'unit_price'         => 'Harga Satuan',
            'stock'              => 'Jumlah Stok',
            'quantity'           => 'Kuantitas',
            'role'               => 'Role Hak Akses',
            'allowed_division'   => 'Hak Akses Divisi',
            'customer_id'        => 'ID Pelanggan',
            'supplier_id'        => 'ID Pemasok',
            'employee_id'        => 'ID Karyawan',
            'name'               => 'Nama',
            'phone'              => 'Telepon',
            'email'              => 'Email',
            'address'            => 'Alamat',
            'category'           => 'Kategori',
            'description'        => 'Keterangan',
            'reason'             => 'Alasan',
            'installment_amount' => 'Cicilan per Bulan',
            'remaining_amount'   => 'Sisa Tagihan',
            'division'           => 'Divisi',
        ];

        return $labels[$field] ?? ucwords(str_replace('_', ' ', $field));
    }

    protected function formatLogFieldValue(string $field, $value): string
    {
        if ($value === null || $value === '') {
            return '-';
        }

        $moneyFields = ['total_amount', 'paid_amount', 'amount', 'price', 'unit_price', 'salary', 'daily_rate', 'overtime_rate', 'remaining_amount', 'installment_amount'];
        if (in_array($field, $moneyFields) && is_numeric($value)) {
            return 'Rp ' . number_format((float)$value, 0, ',', '.');
        }

        if ($field === 'status') {
            $statusLabels = [
                'lunas'       => 'Lunas',
                'belum_lunas' => 'Belum Lunas',
                'pending'     => 'Pending',
                'open'        => 'Aktif (Open)',
                'paid'        => 'Lunas (Paid)',
                'deducted'    => 'Dipotong Gaji',
                'active'      => 'Aktif',
                'inactive'    => 'Non-Aktif',
            ];
            return $statusLabels[strtolower($value)] ?? (string)$value;
        }

        if ($value instanceof \Carbon\Carbon || $value instanceof \DateTimeInterface) {
            return $value->format('d/m/Y');
        }

        if (is_bool($value)) {
            return $value ? 'Ya' : 'Tidak';
        }

        return (string)$value;
    }
}
