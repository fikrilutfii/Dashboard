-- ==========================================================
-- SQL SCHEMA FOR ASFOUR BROILER / PEMOTONGAN AYAM DIVISION
-- ==========================================================

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

CREATE TABLE IF NOT EXISTS `farm_customers` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `farm_suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `farm_location` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE IF NOT EXISTS `farm_invoices` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(255) NOT NULL UNIQUE,
  `farm_sender_id` bigint(20) UNSIGNED DEFAULT NULL,
  `farm_customer_id` bigint(20) UNSIGNED NOT NULL,
  `invoice_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `total_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `remaining_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('belum_lunas','sebagian','lunas') NOT NULL DEFAULT 'belum_lunas',
  `payment_method` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `farm_invoice_items` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `farm_invoice_id` bigint(20) UNSIGNED NOT NULL,
  `farm_product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_code` varchar(255) DEFAULT NULL,
  `item_name` varchar(255) NOT NULL,
  `weight_kg` decimal(10,2) NOT NULL DEFAULT 0.00,
  `qty_ekor` int(11) NOT NULL DEFAULT 0,
  `unit` varchar(50) NOT NULL DEFAULT 'kg',
  `unit_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_price` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE IF NOT EXISTS `farm_transportations` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `transport_date` date NOT NULL,
  `farm_invoice_id` bigint(20) UNSIGNED DEFAULT NULL,
  `farm_vehicle_id` bigint(20) UNSIGNED DEFAULT NULL,
  `driver_name` varchar(255) DEFAULT NULL,
  `destination` varchar(255) DEFAULT NULL,
  `departure_time` time DEFAULT NULL,
  `arrival_time` time DEFAULT NULL,
  `delivery_status` enum('sedang_dikirim','baik','komplain_sebagian','komplain_penuh') NOT NULL DEFAULT 'baik',
  `bbm_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `toll_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `other_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `farm_expenses` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `expense_date` date NOT NULL,
  `category` enum('operasional_rpa','administrasi','armada_umum','lain') NOT NULL DEFAULT 'operasional_rpa',
  `subcategory` varchar(255) DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `payment_method` varchar(255) NOT NULL DEFAULT 'tunai',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `farm_payrolls` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `farm_employee_id` bigint(20) UNSIGNED DEFAULT NULL,
  `employee_name` varchar(255) NOT NULL,
  `role` varchar(255) DEFAULT NULL,
  `salary_type` enum('harian','borongan','bulanan') NOT NULL DEFAULT 'borongan',
  `period_start` date NOT NULL,
  `period_end` date NOT NULL,
  `work_days` int(11) NOT NULL DEFAULT 0,
  `total_kg_produced` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_ekor_produced` int(11) NOT NULL DEFAULT 0,
  `basic_salary` decimal(15,2) NOT NULL DEFAULT 0.00,
  `overtime_pay` decimal(15,2) NOT NULL DEFAULT 0.00,
  `allowances` decimal(15,2) NOT NULL DEFAULT 0.00,
  `deductions` decimal(15,2) NOT NULL DEFAULT 0.00,
  `net_salary` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','dibayar') NOT NULL DEFAULT 'pending',
  `paid_at` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `farm_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` enum('pemasukan','pengeluaran') NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `transaction_date` date NOT NULL,
  `reference_type` varchar(255) DEFAULT NULL,
  `reference_id` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
