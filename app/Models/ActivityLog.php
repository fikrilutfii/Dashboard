<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'activity',
        'description',
        'subject_type',
        'subject_id',
        'properties',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getModuleLabelAttribute(): string
    {
        if (!$this->subject_type) {
            $act = strtolower($this->activity);
            if (str_contains($act, 'login') || str_contains($act, 'logout')) {
                return 'Autentikasi';
            }
            return 'Sistem';
        }

        $base = class_basename($this->subject_type);
        $labels = [
            'Invoice'           => 'Faktur Penjualan',
            'InvoicePayment'    => 'Pembayaran Faktur',
            'InvoiceItem'       => 'Item Faktur',
            'Purchase'          => 'Pembelian Bahan',
            'PurchaseItem'      => 'Item Pembelian',
            'Product'           => 'Produk & Stok',
            'Customer'          => 'Pelanggan',
            'Supplier'          => 'Pemasok',
            'Kasbon'            => 'Kasbon Karyawan',
            'KasbonRepayment'   => 'Cicilan Kasbon',
            'Payroll'           => 'Penggajian',
            'Employee'          => 'Karyawan',
            'Expense'           => 'Pengeluaran',
            'CompanyDebt'       => 'Hutang Usaha',
            'CompanyReceivable' => 'Piutang Usaha',
            'Transaction'       => 'Transaksi Kas',
            'User'              => 'Akses Pengguna',
        ];

        return $labels[$base] ?? $base;
    }

    public function getActionTypeAttribute(): string
    {
        $act = strtolower($this->activity);
        if (str_contains($act, 'create') || str_contains($act, 'tambah')) return 'create';
        if (str_contains($act, 'update') || str_contains($act, 'ubah') || str_contains($act, 'edit')) return 'update';
        if (str_contains($act, 'delete') || str_contains($act, 'hapus')) return 'delete';
        if (str_contains($act, 'login')) return 'login';
        if (str_contains($act, 'logout')) return 'logout';
        if (str_contains($act, 'pay') || str_contains($act, 'bayar')) return 'payment';
        return 'other';
    }

    public function getFormattedSummaryAttribute(): string
    {
        $props = $this->properties;
        if (is_array($props) && !empty($props['summary'])) {
            return $props['summary'];
        }
        return $this->description ?: 'Aktivitas pada sistem';
    }
}
