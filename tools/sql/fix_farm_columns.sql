-- ================================================================
-- SQL FIX UNTUK MENAMBAH KOLOM YANG KURANG DI FARM_INVOICES & TABLES
-- ================================================================

-- 1. Buat Tabel farm_senders jika belum ada
CREATE TABLE IF NOT EXISTS `farm_senders` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `bank_account_number` varchar(255) DEFAULT NULL,
  `bank_account_name` varchar(255) DEFAULT NULL,
  `invoice_footer_notes` text DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Buat Tabel farm_products jika belum ada
CREATE TABLE IF NOT EXISTS `farm_products` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL UNIQUE,
  `name` varchar(255) NOT NULL,
  `category` enum('karkas_utuh','parting','sampingan','lain') NOT NULL DEFAULT 'karkas_utuh',
  `unit` varchar(50) NOT NULL DEFAULT 'kg',
  `standard_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `current_stock_kg` decimal(12,2) NOT NULL DEFAULT 0.00,
  `current_stock_ekor` int(11) NOT NULL DEFAULT 0,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Tambah Kolom ke farm_invoices (abaikan error jika kolom sudah ada)
ALTER TABLE `farm_invoices` ADD `farm_sender_id` bigint(20) UNSIGNED DEFAULT NULL;
ALTER TABLE `farm_invoices` ADD `remaining_amount` decimal(15,2) NOT NULL DEFAULT 0.00;

-- 4. Tambah Kolom ke farm_invoice_items
ALTER TABLE `farm_invoice_items` ADD `farm_product_id` bigint(20) UNSIGNED DEFAULT NULL;
ALTER TABLE `farm_invoice_items` ADD `product_code` varchar(255) DEFAULT NULL;
ALTER TABLE `farm_invoice_items` ADD `weight_kg` decimal(10,2) NOT NULL DEFAULT 0.00;
ALTER TABLE `farm_invoice_items` ADD `qty_ekor` int(11) NOT NULL DEFAULT 0;

-- 5. Buat Tabel farm_production_batches jika belum ada
CREATE TABLE IF NOT EXISTS `farm_production_batches` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `batch_number` varchar(255) NOT NULL UNIQUE,
  `production_date` date NOT NULL,
  `farm_supplier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `live_birds_count` int(11) NOT NULL DEFAULT 0,
  `live_birds_weight_kg` decimal(10,2) NOT NULL DEFAULT 0.00,
  `buy_price_per_kg` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_buy_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `supplier_payment_status` enum('belum_lunas','lunas') NOT NULL DEFAULT 'belum_lunas',
  `doa_count` int(11) NOT NULL DEFAULT 0,
  `doa_weight_kg` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_yield_weight_kg` decimal(10,2) NOT NULL DEFAULT 0.00,
  `shrinkage_weight_kg` decimal(10,2) NOT NULL DEFAULT 0.00,
  `shrinkage_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Buat Tabel farm_production_items jika belum ada
CREATE TABLE IF NOT EXISTS `farm_production_items` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `farm_production_batch_id` bigint(20) UNSIGNED NOT NULL,
  `farm_product_id` bigint(20) UNSIGNED NOT NULL,
  `qty_ekor` int(11) NOT NULL DEFAULT 0,
  `weight_kg` decimal(10,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Buat Tabel farm_invoice_payments jika belum ada
CREATE TABLE IF NOT EXISTS `farm_invoice_payments` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `farm_invoice_id` bigint(20) UNSIGNED NOT NULL,
  `payment_date` date NOT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `payment_method` varchar(255) NOT NULL DEFAULT 'transfer',
  `reference_number` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Buat Tabel farm_customer_prices jika belum ada
CREATE TABLE IF NOT EXISTS `farm_customer_prices` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `farm_customer_id` bigint(20) UNSIGNED NOT NULL,
  `farm_product_id` bigint(20) UNSIGNED NOT NULL,
  `custom_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `farm_cust_prod_unique` (`farm_customer_id`,`farm_product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Buat Tabel farm_employees jika belum ada
CREATE TABLE IF NOT EXISTS `farm_employees` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `role` varchar(255) DEFAULT NULL,
  `salary_type` enum('harian','borongan','bulanan') NOT NULL DEFAULT 'borongan',
  `daily_rate` decimal(15,2) NOT NULL DEFAULT 0.00,
  `piece_rate_per_kg` decimal(15,2) NOT NULL DEFAULT 0.00,
  `piece_rate_per_ekor` decimal(15,2) NOT NULL DEFAULT 0.00,
  `monthly_salary` decimal(15,2) NOT NULL DEFAULT 0.00,
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. Buat Tabel farm_vehicles jika belum ada
CREATE TABLE IF NOT EXISTS `farm_vehicles` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `plate_number` varchar(255) NOT NULL,
  `vehicle_type` varchar(255) DEFAULT NULL,
  `default_driver` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
