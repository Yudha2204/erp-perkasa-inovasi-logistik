-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 18, 2025 at 01:44 PM
-- Server version: 10.11.10-MariaDB
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u386969158_aplpil_bth`
--

-- --------------------------------------------------------

--
-- Table structure for table `account_payable_detail`
--

CREATE TABLE `account_payable_detail` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `head_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` text DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `uom` varchar(255) DEFAULT NULL,
  `price` decimal(30,2) DEFAULT NULL,
  `tax_id` bigint(20) UNSIGNED DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `discount_type` varchar(255) DEFAULT NULL,
  `discount_nominal` decimal(30,2) DEFAULT NULL,
  `dp_type` varchar(255) DEFAULT NULL,
  `dp_nominal` decimal(30,2) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `account_payable_head`
--

CREATE TABLE `account_payable_head` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vendor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `currency_id` bigint(20) UNSIGNED DEFAULT NULL,
  `operation_id` int(11) DEFAULT NULL,
  `source` varchar(255) DEFAULT NULL,
  `transit_via` int(11) DEFAULT NULL,
  `transaction` varchar(255) DEFAULT NULL,
  `date_order` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  `additional_cost` decimal(30,2) DEFAULT NULL,
  `discount_type` varchar(255) DEFAULT NULL,
  `discount_nominal` decimal(30,2) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `account_type`
--

CREATE TABLE `account_type` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `classification_id` bigint(20) UNSIGNED DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `cash_flow` tinyint(4) DEFAULT NULL,
  `can_delete` tinyint(4) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `account_type`
--

INSERT INTO `account_type` (`id`, `classification_id`, `code`, `name`, `cash_flow`, `can_delete`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, '1-0001', 'Kas', 0, 0, NULL, '2024-08-20 09:33:36', NULL),
(2, 1, '1-0002', 'Dompet Digital', 0, 0, NULL, '2024-08-20 09:33:36', NULL),
(3, 1, '1-0010', 'Piutang usaha', 0, 0, NULL, '2024-08-20 09:33:36', NULL),
(4, 1, '1-0011', 'Piutang lain', 1, 0, NULL, '2024-08-20 09:33:36', NULL),
(5, 1, '1-0020', 'Persediaan', 1, 0, NULL, '2024-08-20 09:33:36', NULL),
(6, 1, '1-0030', 'Biaya Bayar Di Muka', 1, 0, NULL, '2024-08-20 09:33:36', NULL),
(7, 1, '1-0050', 'Beban Pajak', 1, 0, NULL, '2024-08-20 09:33:36', NULL),
(8, 1, '1-0070', 'Harta Lancar Lain', 2, 0, NULL, '2024-08-20 09:33:36', NULL),
(9, 1, '2-0001', 'Utang Usaha', 1, 0, NULL, '2024-08-20 09:33:36', NULL),
(10, 1, '2-0020', 'Utang Lain', 1, 0, NULL, '2024-08-20 09:33:36', NULL),
(11, 1, '2-0023', 'Pendapatan Diterima Dimuka', 1, 0, NULL, '2024-08-20 09:33:36', NULL),
(12, 1, '2-0050', 'Beban Pajak Keluaran', 1, 0, NULL, '2024-08-20 09:33:36', NULL),
(13, 1, '3-0000', 'Equitas', 0, 0, NULL, '2024-08-20 09:33:36', NULL),
(14, 1, '4-0000', 'Pendapatan Jasa', 1, 0, NULL, '2024-08-20 09:33:36', NULL),
(15, 1, '4-0010', 'Diskon Penjualan', 1, 0, NULL, '2024-08-20 09:33:36', NULL),
(16, 1, '4-0020', 'Retur Penjualan', 1, 0, NULL, '2024-08-20 09:33:36', NULL),
(17, 1, '5-0001', 'Beban Pendapatan', 1, 0, NULL, '2024-08-20 09:33:36', NULL),
(18, 1, '5-0010', 'Diskon Pembelian', 1, 0, NULL, '2024-08-20 09:33:36', NULL),
(19, 1, '5-0050', 'Biaya Produksi', 1, 0, NULL, '2024-08-20 09:33:36', NULL),
(20, 1, '6-0021', 'Pengeluaran Barang Rusak', 2, 0, NULL, '2024-08-20 09:33:36', NULL),
(21, 1, '7-7099', 'Pendapatan Luar Usaha', 1, 0, NULL, '2024-08-20 09:33:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `activity_operation_export`
--

CREATE TABLE `activity_operation_export` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `operation_export_id` bigint(20) UNSIGNED DEFAULT NULL,
  `batam_entry_date` date DEFAULT NULL,
  `batam_exit_date` date DEFAULT NULL,
  `destination_entry_date` date DEFAULT NULL,
  `warehouse_entry_date` date DEFAULT NULL,
  `warehouse_exit_date` date DEFAULT NULL,
  `client_received_date` date DEFAULT NULL,
  `sin_entry_date` date DEFAULT NULL,
  `sin_exit_date` date DEFAULT NULL,
  `return_pod_date` date DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `activity_operation_import`
--

CREATE TABLE `activity_operation_import` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `operation_import_id` bigint(20) UNSIGNED DEFAULT NULL,
  `batam_entry_date` date DEFAULT NULL,
  `batam_exit_date` date DEFAULT NULL,
  `destination_entry_date` date DEFAULT NULL,
  `warehouse_entry_date` date DEFAULT NULL,
  `warehouse_exit_date` date DEFAULT NULL,
  `client_received_date` date DEFAULT NULL,
  `sin_entry_date` date DEFAULT NULL,
  `sin_exit_date` date DEFAULT NULL,
  `return_pod_date` date DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_operation_import`
--

INSERT INTO `activity_operation_import` (`id`, `operation_import_id`, `batam_entry_date`, `batam_exit_date`, `destination_entry_date`, `warehouse_entry_date`, `warehouse_exit_date`, `client_received_date`, `sin_entry_date`, `sin_exit_date`, `return_pod_date`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-09-04 15:08:21', '2024-09-04 15:08:21');

-- --------------------------------------------------------

--
-- Table structure for table `balance_account_data`
--

CREATE TABLE `balance_account_data` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `master_account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `transaction_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `transaction_id` int(11) DEFAULT NULL,
  `debit` decimal(30,2) DEFAULT NULL,
  `credit` decimal(30,2) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classification_account_type`
--

CREATE TABLE `classification_account_type` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `classification` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `classification_account_type`
--

INSERT INTO `classification_account_type` (`id`, `code`, `classification`, `created_at`, `updated_at`) VALUES
(1, '1-0000', 'Assets', '2024-08-20 09:33:36', NULL),
(2, '2-0000', 'Liabilities', '2024-08-20 09:33:36', NULL),
(3, '3-0000', 'Equity', '2024-08-20 09:33:36', NULL),
(4, '4-0000', 'Income', '2024-08-20 09:33:36', NULL),
(5, '5-0000', 'Cost Of Sales', '2024-08-20 09:33:36', NULL),
(6, '6-0000', 'Expenses', '2024-08-20 09:33:36', NULL),
(7, '7-0000', 'Other Cost', '2024-08-20 09:33:36', NULL),
(8, '8-0000', 'Other Expenses', '2024-08-20 09:33:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dimension_marketing_export`
--

CREATE TABLE `dimension_marketing_export` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `marketing_export_id` bigint(20) UNSIGNED DEFAULT NULL,
  `packages` varchar(255) DEFAULT NULL,
  `length` varchar(255) DEFAULT NULL,
  `width` varchar(255) DEFAULT NULL,
  `height` varchar(255) DEFAULT NULL,
  `input_measure` varchar(255) DEFAULT NULL,
  `qty` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dimension_marketing_export`
--

INSERT INTO `dimension_marketing_export` (`id`, `marketing_export_id`, `packages`, `length`, `width`, `height`, `input_measure`, `qty`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, '1', '12', '13', '14', '2', '10', NULL, '2024-08-20 09:33:36', NULL),
(2, 1, '2', '7', '8', '9', '3', '11', NULL, '2024-08-20 09:33:36', NULL),
(3, 2, '1', '12', '13', '14', '2', '10', NULL, '2024-08-20 09:33:36', NULL),
(4, 2, '2', '7', '8', '9', '3', '11', NULL, '2024-08-20 09:33:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `dimension_marketing_import`
--

CREATE TABLE `dimension_marketing_import` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `marketing_import_id` bigint(20) UNSIGNED DEFAULT NULL,
  `packages` varchar(255) DEFAULT NULL,
  `length` varchar(255) DEFAULT NULL,
  `width` varchar(255) DEFAULT NULL,
  `height` varchar(255) DEFAULT NULL,
  `input_measure` varchar(255) DEFAULT NULL,
  `qty` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dimension_marketing_import`
--

INSERT INTO `dimension_marketing_import` (`id`, `marketing_import_id`, `packages`, `length`, `width`, `height`, `input_measure`, `qty`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, '1', '12', '13', '14', '2', '10', NULL, '2024-08-20 09:33:37', NULL),
(2, 1, '2', '7', '8', '9', '3', '11', NULL, '2024-08-20 09:33:37', NULL),
(3, 2, '1', '12', '13', '14', '2', '10', NULL, '2024-08-20 09:33:37', NULL),
(4, 2, '2', '7', '8', '9', '3', '11', NULL, '2024-08-20 09:33:37', NULL),
(8, 3, '12', '55', '43', '85', NULL, NULL, NULL, '2024-09-04 15:05:32', '2024-09-04 15:05:32');

-- --------------------------------------------------------

--
-- Table structure for table `document_activity_op_ex`
--

CREATE TABLE `document_activity_op_ex` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `activity_operation_export_id` bigint(20) UNSIGNED DEFAULT NULL,
  `document` text DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `document_activity_op_im`
--

CREATE TABLE `document_activity_op_im` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `activity_operation_import_id` bigint(20) UNSIGNED DEFAULT NULL,
  `document` text DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `document_activity_op_im`
--

INSERT INTO `document_activity_op_im` (`id`, `activity_operation_import_id`, `document`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'operation-import/activity/documents/AWB 54202200251_22032024_123211.pdf', NULL, '2024-09-04 15:08:21', '2024-09-04 15:08:21');

-- --------------------------------------------------------

--
-- Table structure for table `document_arrival_op_ex`
--

CREATE TABLE `document_arrival_op_ex` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `operation_export_id` bigint(20) UNSIGNED DEFAULT NULL,
  `document` text DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `document_arrival_op_im`
--

CREATE TABLE `document_arrival_op_im` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `operation_import_id` bigint(20) UNSIGNED DEFAULT NULL,
  `document` text DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `document_marketing_export`
--

CREATE TABLE `document_marketing_export` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `marketing_export_id` bigint(20) UNSIGNED DEFAULT NULL,
  `document` text DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `document_marketing_import`
--

CREATE TABLE `document_marketing_import` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `marketing_import_id` bigint(20) UNSIGNED DEFAULT NULL,
  `document` text DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `document_marketing_import`
--

INSERT INTO `document_marketing_import` (`id`, `marketing_import_id`, `document`, `deleted_at`, `created_at`, `updated_at`) VALUES
(2, 3, 'marketing-import/documents/AWB 54202200251_22032024_123211.pdf', NULL, '2024-09-04 15:00:51', '2024-09-04 15:00:51');

-- --------------------------------------------------------

--
-- Table structure for table `document_progress_op_ex`
--

CREATE TABLE `document_progress_op_ex` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `progress_operation_export_id` bigint(20) UNSIGNED DEFAULT NULL,
  `document` text DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `document_progress_op_im`
--

CREATE TABLE `document_progress_op_im` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `progress_operation_import_id` bigint(20) UNSIGNED DEFAULT NULL,
  `document` text DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `group_quotation_m_ex`
--

CREATE TABLE `group_quotation_m_ex` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quotation_m_ex_id` bigint(20) UNSIGNED DEFAULT NULL,
  `group` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `group_quotation_m_ex`
--

INSERT INTO `group_quotation_m_ex` (`id`, `quotation_m_ex_id`, `group`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'A', NULL, '2024-08-20 09:33:36', NULL),
(2, 2, 'A', NULL, '2024-08-20 09:33:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `group_quotation_m_im`
--

CREATE TABLE `group_quotation_m_im` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quotation_m_im_id` bigint(20) UNSIGNED DEFAULT NULL,
  `group` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `group_quotation_m_im`
--

INSERT INTO `group_quotation_m_im` (`id`, `quotation_m_im_id`, `group`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'A', NULL, '2024-08-20 09:33:37', NULL),
(2, 2, 'A', NULL, '2024-08-20 09:33:37', NULL),
(3, 3, 'A', NULL, '2024-09-06 15:45:27', '2024-09-06 15:45:27'),
(4, 4, 'A', NULL, '2024-09-06 16:05:13', '2024-09-06 16:05:13'),
(5, 4, 'B', NULL, '2024-09-06 16:05:13', '2024-09-06 16:05:13'),
(6, 4, 'C', NULL, '2024-09-06 16:05:13', '2024-09-06 16:05:13');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_detail`
--

CREATE TABLE `invoice_detail` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `head_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` text DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `uom` varchar(255) DEFAULT NULL,
  `price` decimal(30,2) DEFAULT NULL,
  `tax_id` bigint(20) UNSIGNED DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `discount_type` varchar(255) DEFAULT NULL,
  `discount_nominal` decimal(30,2) DEFAULT NULL,
  `dp_type` varchar(255) DEFAULT NULL,
  `dp_nominal` decimal(30,2) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_head`
--

CREATE TABLE `invoice_head` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `contact_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sales_id` bigint(20) UNSIGNED DEFAULT NULL,
  `term_payment` bigint(20) UNSIGNED DEFAULT NULL,
  `currency_id` bigint(20) UNSIGNED DEFAULT NULL,
  `number` int(11) DEFAULT NULL,
  `date_invoice` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  `additional_cost` decimal(30,2) DEFAULT NULL,
  `discount_type` varchar(255) DEFAULT NULL,
  `discount_nominal` decimal(30,2) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_group_quotation_m_ex`
--

CREATE TABLE `item_group_quotation_m_ex` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `group_quotation_m_ex_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` text DEFAULT NULL,
  `total` varchar(255) DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `item_group_quotation_m_ex`
--

INSERT INTO `item_group_quotation_m_ex` (`id`, `group_quotation_m_ex_id`, `description`, `total`, `remark`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'Barang 1', '1000000', '-', NULL, '2024-08-20 09:33:36', NULL),
(2, 1, 'Barang 2', '2000000', '-', NULL, '2024-08-20 09:33:36', NULL),
(3, 2, 'Barang 1', '1000000', '-', NULL, '2024-08-20 09:33:37', NULL),
(4, 2, 'Barang 2', '2000000', '-', NULL, '2024-08-20 09:33:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `item_group_quotation_m_im`
--

CREATE TABLE `item_group_quotation_m_im` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `group_quotation_m_im_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` text DEFAULT NULL,
  `total` varchar(255) DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `item_group_quotation_m_im`
--

INSERT INTO `item_group_quotation_m_im` (`id`, `group_quotation_m_im_id`, `description`, `total`, `remark`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'Barang 1', '1000000', '-', NULL, '2024-08-20 09:33:37', NULL),
(2, 1, 'Barang 2', '2000000', '-', NULL, '2024-08-20 09:33:37', NULL),
(3, 2, 'Barang 1', '1000000', '-', NULL, '2024-08-20 09:33:37', NULL),
(4, 2, 'Barang 2', '2000000', '-', NULL, '2024-08-20 09:33:37', NULL),
(5, 3, 'Aircraft Parts', '17658000', NULL, NULL, '2024-09-06 15:45:27', '2024-09-06 15:45:27'),
(6, 4, 'Ocean Freight, pick up and local charge Shanghai - JKT. USD 4282/40\' x container x Rp 16.500', '211959000', NULL, NULL, '2024-09-06 16:05:13', '2024-09-06 16:05:13'),
(7, 4, 'Export customs, BL and Telex @USD 260 x Rp. 16.500', '4290000', NULL, NULL, '2024-09-06 16:05:13', '2024-09-06 16:05:13'),
(8, 4, 'PPN 1.1%', '2378739', NULL, NULL, '2024-09-06 16:05:13', '2024-09-06 16:05:13'),
(9, 5, 'Red clearance @Rp. 2,100,000 x 3 containers', '6300000', NULL, NULL, '2024-09-06 16:05:13', '2024-09-06 16:05:13'),
(10, 5, 'EDI', '125000', NULL, NULL, '2024-09-06 16:05:13', '2024-09-06 16:05:13'),
(11, 5, 'Administrasi', '250000', NULL, NULL, '2024-09-06 16:05:13', '2024-09-06 16:05:13'),
(12, 5, 'Trucking @Rp. 2,500,000 x 3 container', '7500000', NULL, NULL, '2024-09-06 16:05:13', '2024-09-06 16:05:13'),
(13, 5, 'PPN 1.1%', '155925', NULL, NULL, '2024-09-06 16:05:13', '2024-09-06 16:05:13'),
(14, 6, 'D/O @ Rp. 3,800,000 x 3 container', '11400000', NULL, NULL, '2024-09-06 16:05:13', '2024-09-06 16:05:13'),
(15, 6, 'LOLO @ Rp. 2,000,000 x container', '6000000', NULL, NULL, '2024-09-06 16:05:13', '2024-09-06 16:05:13'),
(16, 6, 'Storage jalur merah @ Rp. 9,800,000 x 3 container', '29400000', NULL, NULL, '2024-09-06 16:05:13', '2024-09-06 16:05:13'),
(17, 6, 'Asuransi 0.20% x USD 341,220 x Rp. 16.600', '11260260', NULL, NULL, '2024-09-06 16:05:13', '2024-09-06 16:05:13');

-- --------------------------------------------------------

--
-- Table structure for table `kas_in_detail`
--

CREATE TABLE `kas_in_detail` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `head_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` text DEFAULT NULL,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `total` decimal(30,2) DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kas_in_head`
--

CREATE TABLE `kas_in_head` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `contact_id` bigint(20) UNSIGNED DEFAULT NULL,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `currency_id` bigint(20) UNSIGNED DEFAULT NULL,
  `job_order_id` int(11) DEFAULT NULL,
  `source` varchar(255) DEFAULT NULL,
  `date_kas_in` date DEFAULT NULL,
  `transaction_id` bigint(20) UNSIGNED DEFAULT NULL,
  `number` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kas_out_detail`
--

CREATE TABLE `kas_out_detail` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `head_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` text DEFAULT NULL,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `total` decimal(30,2) DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kas_out_head`
--

CREATE TABLE `kas_out_head` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `contact_id` bigint(20) UNSIGNED DEFAULT NULL,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `currency_id` bigint(20) UNSIGNED DEFAULT NULL,
  `job_order_id` int(11) DEFAULT NULL,
  `source` varchar(255) DEFAULT NULL,
  `date_kas_out` date DEFAULT NULL,
  `transaction_id` bigint(20) UNSIGNED DEFAULT NULL,
  `number` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logo_address`
--

CREATE TABLE `logo_address` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `address` text NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `marketing_export`
--

CREATE TABLE `marketing_export` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `contact_id` bigint(20) UNSIGNED DEFAULT NULL,
  `job_order_id` varchar(255) DEFAULT NULL,
  `expedition` int(11) DEFAULT NULL,
  `transportation` int(11) DEFAULT NULL,
  `transportation_desc` varchar(255) DEFAULT NULL,
  `no_po` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `no_cipl` varchar(255) DEFAULT NULL,
  `total_weight` varchar(255) DEFAULT NULL,
  `total_volume` varchar(255) DEFAULT NULL,
  `freetext_volume` varchar(255) DEFAULT NULL,
  `origin` varchar(255) DEFAULT NULL,
  `shipper` varchar(255) DEFAULT NULL,
  `pickup_address` varchar(255) DEFAULT NULL,
  `destination` varchar(255) DEFAULT NULL,
  `consignee` varchar(255) DEFAULT NULL,
  `delivery_address` varchar(255) DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `source` varchar(255) NOT NULL DEFAULT 'export',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `marketing_export`
--

INSERT INTO `marketing_export` (`id`, `contact_id`, `job_order_id`, `expedition`, `transportation`, `transportation_desc`, `no_po`, `description`, `no_cipl`, `total_weight`, `total_volume`, `freetext_volume`, `origin`, `shipper`, `pickup_address`, `destination`, `consignee`, `delivery_address`, `remark`, `status`, `source`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'JOPILJKT-00001', 1, 2, 'FCL', '0001', 'Export gula tebu', '9999', '200', '12768', 'M3', 'Batam', 'Ex Shanghai', 'Jl. Tambunan', 'Jakarta', 'Express', 'Jl. Mataram', '-', 1, 'export', NULL, '2024-08-20 09:33:36', NULL),
(2, 2, 'JOPILJKT-00002', 1, 2, 'LCL', '0002', 'Export Minyak Sawit', '9990', '300', '12768', 'M3', 'Riau', 'Ex Shanghai', 'Jl. Srikaya', 'Papua', 'Express JS', 'Jl. Apel', '-', 2, 'export', NULL, '2024-08-20 09:33:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `marketing_import`
--

CREATE TABLE `marketing_import` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `contact_id` bigint(20) UNSIGNED DEFAULT NULL,
  `job_order_id` varchar(255) DEFAULT NULL,
  `expedition` int(11) DEFAULT NULL,
  `transportation` int(11) DEFAULT NULL,
  `transportation_desc` varchar(255) DEFAULT NULL,
  `no_po` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `no_cipl` varchar(255) DEFAULT NULL,
  `total_weight` varchar(255) DEFAULT NULL,
  `total_volume` varchar(255) DEFAULT NULL,
  `freetext_volume` varchar(255) DEFAULT NULL,
  `origin` varchar(255) DEFAULT NULL,
  `shipper` varchar(255) DEFAULT NULL,
  `pickup_address` varchar(255) DEFAULT NULL,
  `destination` varchar(255) DEFAULT NULL,
  `consignee` varchar(255) DEFAULT NULL,
  `delivery_address` varchar(255) DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `source` varchar(255) NOT NULL DEFAULT 'import',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `marketing_import`
--

INSERT INTO `marketing_import` (`id`, `contact_id`, `job_order_id`, `expedition`, `transportation`, `transportation_desc`, `no_po`, `description`, `no_cipl`, `total_weight`, `total_volume`, `freetext_volume`, `origin`, `shipper`, `pickup_address`, `destination`, `consignee`, `delivery_address`, `remark`, `status`, `source`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 1, 2, 'FCL', '0001', 'Import gula tebu', '9999', '200', '12768', 'M3', 'Batam', 'Ex Shanghai', 'Jl. Tambunan', 'Jakarta', 'Express', 'Jl. Mataram', '-', 1, 'import', '2024-08-26 15:15:45', '2024-08-20 09:33:37', '2024-08-26 15:15:45'),
(2, 2, 'JOPILJKT-00002', 1, 2, 'LCL', '0002', 'Import Minyak Sawit', '9990', '300', '12768', 'M3', 'Riau', 'Ex Shanghai', 'Jl. Srikaya', 'Papua', 'Express JS', 'Jl. Apel', '-', 2, 'import', NULL, '2024-08-20 09:33:37', NULL),
(3, 14, 'JOPILJKT-00003', 2, 1, 'Regular', NULL, 'Aircraft Parts', NULL, '403', '0', NULL, 'Italy', 'Geven SPA Unimpersonale', NULL, 'Kuala Lumpur', 'Lion Mentari C/O Pos Logistics Berhad', 'Mezzanine Floor CTB-B-05, Pos Aviation Cargo Complex', NULL, 2, 'import', NULL, '2024-08-30 11:17:34', '2024-09-04 14:56:04'),
(4, 11, 'JOPILJKT-00004', 2, 1, NULL, NULL, 'Telecommunication Equipments', NULL, '7278', NULL, NULL, 'Maryland, USA', NULL, NULL, 'Jakarta', 'PT. Abhimata Citra Abadi', 'Jl. Gunung Sahari No. 60-63', NULL, 2, 'import', NULL, '2024-08-30 11:31:09', '2024-08-30 15:35:55'),
(5, 15, 'JOPILJKT-00005', 2, 1, NULL, NULL, 'MRO SPART', NULL, '82', NULL, NULL, 'Thailand', NULL, NULL, 'Singapore', NULL, NULL, NULL, 2, 'import', NULL, '2024-08-30 13:33:31', '2024-08-30 13:33:31'),
(6, 14, 'JOPILJKT-00006', 2, 1, NULL, NULL, 'Fixture', NULL, '110', NULL, NULL, 'France', 'TMH TOOLS', NULL, 'Kuala Lumpur', 'Lion Mentari C/O Pos Logistics Berhad', 'Mezzanine Floor CTB-B-05, Pos Aviation Cargo Complex', NULL, 2, 'import', NULL, '2024-08-30 13:34:58', '2024-08-30 15:40:51'),
(7, 16, 'JOPILJKT-00007', 2, 2, NULL, NULL, 'LARS', NULL, '39000', NULL, NULL, 'Estonia', NULL, NULL, 'Singapore', NULL, NULL, NULL, 2, 'import', NULL, '2024-08-30 14:01:07', '2024-08-30 15:53:05'),
(8, 14, 'JOPILJKT-00008', 2, 1, NULL, NULL, 'Engine Stand', NULL, '2381', NULL, NULL, 'Texas, USA', 'HAECO Global Engine Support', NULL, 'Kuala Lumpur', 'Malindo Airways SDN BHD C/O Pos Logistics Berhad', 'Mezzanine Floor CTB-B-05, Pos Aviation Cargo Complex', NULL, 2, 'import', NULL, '2024-08-30 14:02:10', '2024-08-30 15:47:26'),
(9, 11, 'JOPILJKT-00009', 2, 2, 'FCL', NULL, 'Water Cooled Air Conditioning', NULL, NULL, NULL, NULL, 'Shanghai', NULL, NULL, 'Jakarta', NULL, NULL, NULL, 2, 'import', NULL, '2024-08-30 14:03:24', '2024-08-30 15:44:00');

-- --------------------------------------------------------

--
-- Table structure for table `master_account`
--

CREATE TABLE `master_account` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `account_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `account_name` varchar(255) DEFAULT NULL,
  `master_currency_id` bigint(20) UNSIGNED DEFAULT NULL,
  `can_delete` tinyint(4) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `master_account`
--

INSERT INTO `master_account` (`id`, `account_type_id`, `code`, `account_name`, `master_currency_id`, `can_delete`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, '110001', 'Kas', 1, 0, NULL, '2024-08-20 09:33:36', NULL),
(2, 2, '110009', 'Prive', 1, 0, NULL, '2024-08-20 09:33:36', NULL),
(3, 2, '110002', 'Rekening Bank', 1, 0, NULL, '2024-08-20 09:33:36', NULL),
(4, 2, '110003', 'Giro', 1, 0, NULL, '2024-08-20 09:33:36', NULL),
(5, 3, '110100', 'Piutang Usaha', 1, 0, NULL, '2024-08-20 09:33:36', NULL),
(6, 4, '110101', 'Piutang Usaha Belum Ditagih', 1, 0, NULL, '2024-08-20 09:33:36', NULL),
(7, 5, '110200', 'Persediaan Barang', 1, 0, NULL, '2024-08-20 09:33:36', NULL),
(8, 6, '110402', 'Biaya Bayar Di Muka', 1, 0, NULL, '2024-08-20 09:33:36', NULL),
(9, 7, '110500', 'PPN Masukan', 1, 0, NULL, '2024-08-20 09:33:36', NULL),
(10, 8, '110305', 'Aset Tetap Perlengkapan Kantor', 1, 0, NULL, '2024-08-20 09:33:36', NULL),
(11, 9, '220100', 'Hutang Usaha', 1, 0, NULL, '2024-08-20 09:33:36', NULL),
(12, 10, '220101', 'Hutang Belum Ditagih', 1, 0, NULL, '2024-08-20 09:33:36', NULL),
(13, 11, '220203', 'Pendapatan Diterima Di Muka', 1, 0, NULL, '2024-08-20 09:33:36', NULL),
(14, 12, '220500', 'PPN Keluaran', 1, 0, NULL, '2024-08-20 09:33:36', NULL),
(15, 13, '300001', 'Ekuitas Saldo Awal', 1, 0, NULL, '2024-08-20 09:33:36', NULL),
(16, 14, '440000', 'Pendapatan Jasa', 1, 0, NULL, '2024-08-20 09:33:36', NULL),
(17, 15, '440100', 'Diskon Penjualan', 1, 0, NULL, '2024-08-20 09:33:36', NULL),
(18, 16, '440200', 'Retur Penjualan', 1, 0, NULL, '2024-08-20 09:33:36', NULL),
(19, 14, '440010', 'Pendapatan Lain', 1, 0, NULL, '2024-08-20 09:33:36', NULL),
(20, 17, '550000', 'Beban Pokok Pendapatan', 1, 0, NULL, '2024-08-20 09:33:36', NULL),
(21, 18, '550100', 'Diskon Pembelian', 1, 0, NULL, '2024-08-20 09:33:36', NULL),
(22, 19, '550500', 'Biaya Produksi', 1, 0, NULL, '2024-08-20 09:33:36', NULL),
(23, 19, '550501', 'Uang Muka kepada Vendor', 1, 0, NULL, '2024-08-20 09:33:36', NULL),
(24, 20, '660216', 'Pengeluaran Barang Rusak', 1, 0, NULL, '2024-08-20 09:33:36', NULL),
(25, 21, '770099', 'Pengeluaran Lain', 1, 0, NULL, '2024-08-20 09:33:36', NULL),
(26, 1, '110001', 'Kas', 2, 0, NULL, '2024-08-20 09:33:36', NULL),
(27, 2, '110009', 'Prive', 2, 0, NULL, '2024-08-20 09:33:36', NULL),
(28, 2, '110002', 'Rekening Bank', 2, 0, NULL, '2024-08-20 09:33:36', NULL),
(29, 2, '110003', 'Giro', 2, 0, NULL, '2024-08-20 09:33:36', NULL),
(30, 3, '110100', 'Piutang Usaha', 2, 0, NULL, '2024-08-20 09:33:36', NULL),
(31, 4, '110101', 'Piutang Usaha Belum Ditagih', 2, 0, NULL, '2024-08-20 09:33:36', NULL),
(32, 5, '110200', 'Persediaan Barang', 2, 0, NULL, '2024-08-20 09:33:36', NULL),
(33, 6, '110402', 'Biaya Bayar Di Muka', 2, 0, NULL, '2024-08-20 09:33:36', NULL),
(34, 7, '110500', 'PPN Masukan', 2, 0, NULL, '2024-08-20 09:33:36', NULL),
(35, 8, '110305', 'Aset Tetap Perlengkapan Kantor', 2, 0, NULL, '2024-08-20 09:33:36', NULL),
(36, 9, '220100', 'Hutang Usaha', 2, 0, NULL, '2024-08-20 09:33:36', NULL),
(37, 10, '220101', 'Hutang Belum Ditagih', 2, 0, NULL, '2024-08-20 09:33:36', NULL),
(38, 11, '220203', 'Pendapatan Diterima Di Muka', 2, 0, NULL, '2024-08-20 09:33:36', NULL),
(39, 12, '220500', 'PPN Keluaran', 2, 0, NULL, '2024-08-20 09:33:36', NULL),
(40, 13, '300001', 'Ekuitas Saldo Awal', 2, 0, NULL, '2024-08-20 09:33:36', NULL),
(41, 14, '440000', 'Pendapatan Jasa', 2, 0, NULL, '2024-08-20 09:33:36', NULL),
(42, 15, '440100', 'Diskon Penjualan', 2, 0, NULL, '2024-08-20 09:33:36', NULL),
(43, 16, '440200', 'Retur Penjualan', 2, 0, NULL, '2024-08-20 09:33:36', NULL),
(44, 14, '440010', 'Pendapatan Lain', 2, 0, NULL, '2024-08-20 09:33:36', NULL),
(45, 17, '550000', 'Beban Pokok Pendapatan', 2, 0, NULL, '2024-08-20 09:33:36', NULL),
(46, 18, '550100', 'Diskon Pembelian', 2, 0, NULL, '2024-08-20 09:33:36', NULL),
(47, 19, '550500', 'Biaya Produksi', 2, 0, NULL, '2024-08-20 09:33:36', NULL),
(48, 19, '550501', 'Uang Muka kepada Vendor', 2, 0, NULL, '2024-08-20 09:33:36', NULL),
(49, 20, '660216', 'Pengeluaran Barang Rusak', 2, 0, NULL, '2024-08-20 09:33:36', NULL),
(50, 21, '770099', 'Pengeluaran Lain', 2, 0, NULL, '2024-08-20 09:33:36', NULL),
(51, 1, '110001', 'Kas', 3, 0, NULL, '2024-08-20 09:33:36', NULL),
(52, 2, '110009', 'Prive', 3, 0, NULL, '2024-08-20 09:33:36', NULL),
(53, 2, '110002', 'Rekening Bank', 3, 0, NULL, '2024-08-20 09:33:36', NULL),
(54, 2, '110003', 'Giro', 3, 0, NULL, '2024-08-20 09:33:36', NULL),
(55, 3, '110100', 'Piutang Usaha', 3, 0, NULL, '2024-08-20 09:33:36', NULL),
(56, 4, '110101', 'Piutang Usaha Belum Ditagih', 3, 0, NULL, '2024-08-20 09:33:36', NULL),
(57, 5, '110200', 'Persediaan Barang', 3, 0, NULL, '2024-08-20 09:33:36', NULL),
(58, 6, '110402', 'Biaya Bayar Di Muka', 3, 0, NULL, '2024-08-20 09:33:36', NULL),
(59, 7, '110500', 'PPN Masukan', 3, 0, NULL, '2024-08-20 09:33:36', NULL),
(60, 8, '110305', 'Aset Tetap Perlengkapan Kantor', 3, 0, NULL, '2024-08-20 09:33:36', NULL),
(61, 9, '220100', 'Hutang Usaha', 3, 0, NULL, '2024-08-20 09:33:36', NULL),
(62, 10, '220101', 'Hutang Belum Ditagih', 3, 0, NULL, '2024-08-20 09:33:36', NULL),
(63, 11, '220203', 'Pendapatan Diterima Di Muka', 3, 0, NULL, '2024-08-20 09:33:36', NULL),
(64, 12, '220500', 'PPN Keluaran', 3, 0, NULL, '2024-08-20 09:33:36', NULL),
(65, 13, '300001', 'Ekuitas Saldo Awal', 3, 0, NULL, '2024-08-20 09:33:36', NULL),
(66, 14, '440000', 'Pendapatan Jasa', 3, 0, NULL, '2024-08-20 09:33:36', NULL),
(67, 15, '440100', 'Diskon Penjualan', 3, 0, NULL, '2024-08-20 09:33:36', NULL),
(68, 16, '440200', 'Retur Penjualan', 3, 0, NULL, '2024-08-20 09:33:36', NULL),
(69, 14, '440010', 'Pendapatan Lain', 3, 0, NULL, '2024-08-20 09:33:36', NULL),
(70, 17, '550000', 'Beban Pokok Pendapatan', 3, 0, NULL, '2024-08-20 09:33:36', NULL),
(71, 18, '550100', 'Diskon Pembelian', 3, 0, NULL, '2024-08-20 09:33:36', NULL),
(72, 19, '550500', 'Biaya Produksi', 3, 0, NULL, '2024-08-20 09:33:36', NULL),
(73, 19, '550501', 'Uang Muka kepada Vendor', 3, 0, NULL, '2024-08-20 09:33:36', NULL),
(74, 20, '660216', 'Pengeluaran Barang Rusak', 3, 0, NULL, '2024-08-20 09:33:36', NULL),
(75, 21, '770099', 'Pengeluaran Lain', 3, 0, NULL, '2024-08-20 09:33:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `master_bank_currency`
--

CREATE TABLE `master_bank_currency` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `currency_id` bigint(20) UNSIGNED DEFAULT NULL,
  `account_no` varchar(255) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `swift_code` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `master_contact`
--

CREATE TABLE `master_contact` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` varchar(255) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `npwp_ktp` varchar(255) DEFAULT NULL,
  `document` text DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `type_of_company` int(11) DEFAULT NULL,
  `company_tax_status` int(11) DEFAULT NULL,
  `bank_branch` varchar(255) DEFAULT NULL,
  `acc_name` varchar(255) DEFAULT NULL,
  `acc_no` varchar(255) DEFAULT NULL,
  `swift_code` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `postal_code` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `pic_for_urgent_status` varchar(255) DEFAULT NULL,
  `mobile_number` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `master_contact`
--

INSERT INTO `master_contact` (`id`, `customer_id`, `customer_name`, `title`, `phone_number`, `email`, `npwp_ktp`, `document`, `type`, `company_name`, `type_of_company`, `company_tax_status`, `bank_branch`, `acc_name`, `acc_no`, `swift_code`, `address`, `city`, `postal_code`, `country`, `pic_for_urgent_status`, `mobile_number`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'AH-001', 'Ahmad Habibi', 'Manager', '081232123212', 'ahmad@gmail.com', '-', NULL, '[\"2\"]', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Jl. Ketapang', 'Batam', '98221', 'Indonesia', NULL, '08129322123', NULL, '2024-08-20 09:33:36', NULL),
(2, 'H-001', 'Herman', 'Manager', '083923912322', 'herman@gmail.com', '-', NULL, '[\"1\"]', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Jl. Wonosari', 'Pekanbaru', '09332', 'Indonesia', NULL, '093829312345', NULL, '2024-08-20 09:33:36', NULL),
(3, 'H-002', 'Hasibuan', 'Customer Service', '083927483931', 'hasibuan@gmail.com', '-', NULL, '[\"1\"]', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Jl. Wonokdadi', 'Pekanbaru', '09332', 'Indonesia', NULL, '093829302912', NULL, '2024-08-20 09:33:36', NULL),
(4, 'A7STLI-004', 'AIR 7 SEAS Transport Logistics Inc', 'Air7Seas', '+1 551-320-0023', 'Dev@air7seas.us', NULL, NULL, '[\"2\"]', 'AIR 7 SEAS Transport Logistics Inc USA', 1, NULL, NULL, NULL, NULL, NULL, '1815 Houret Ct, Milpitas, CA 95035, Amerika Serikat', 'California', NULL, 'United State of America', NULL, NULL, NULL, '2024-08-26 15:36:26', '2024-08-26 15:36:26'),
(5, 'EL-005', 'ECBEC Limited', 'Ecbec', '+86-185-7154-1728', 'Junior-ECBEC <junior@ecbecs.com>', NULL, NULL, '[\"2\"]', 'ECBEC Limited', 1, NULL, NULL, NULL, NULL, NULL, 'Unit 905, B Block, Datang Shidai Building, 2203 Meilong Road, LongHua District, ShenZhen, China', NULL, NULL, NULL, 'Junior', '+86-185-7154-1728', NULL, '2024-08-26 15:50:19', '2024-08-26 15:50:19'),
(6, 'AFSPL-006', 'ACS Freight Services Pte Ltd', 'ACS', '(65) 92377858', 'Benjamin YEO <benjamin@acsfrt.com.sg>', NULL, NULL, '[\"2\"]', 'ACS Freight Services Pte Ltd', 1, NULL, NULL, NULL, NULL, NULL, '119 Airport Cargo Road, #01-03/04 Changi Cargo Megaplex 1 Singapore 819454', 'Singapore', '819454', 'Singapore', 'Benyamin', '(65) 92377858', NULL, '2024-08-26 15:55:58', '2024-08-26 15:55:58'),
(7, 'IS-007', 'INTERTRANSPORT SRL', 'Intertransport', '+39.010.5355549', 'Matteo Sissa <matteo.sissa@intertransport.it>', NULL, NULL, '[\"2\"]', 'INTERTRANSPORT SRL', 1, NULL, NULL, NULL, NULL, NULL, 'Via Milano 162 F/R, 16126, Genova – Italy', 'Genova', '16126', 'Italy', 'Matteo Sissa', '+39.010.5355549', NULL, '2024-08-26 16:01:51', '2024-08-26 16:01:51'),
(8, 'ILPL-008', 'ID Logistics PTE LTD', 'ID Logistics', '+65 93365891', 'rashid@id-logistics.net', NULL, NULL, '[\"2\"]', 'ID Logistics PTE LTD', 1, NULL, NULL, NULL, NULL, NULL, 'Office: Cargo Agent Building C. #06-12. Warehouse: Cargo Agent Building C. #01-36, Singapore 819466.', 'Singapore', '819466', 'Singapore', NULL, NULL, NULL, '2024-08-26 16:06:54', '2024-08-26 16:06:54'),
(9, 'PPP-009', 'PT. PARAMAS PERMATA', 'PT. Paramas', '0813 8305 6685; 0878 8202 7505', 'Sudardjo DJ <sudardjo@gmail.com>', NULL, NULL, '[\"1\",\"2\"]', 'PT. PARAMAS PERMATA', 1, NULL, NULL, NULL, NULL, NULL, 'Komplek Ruko Puri Mutiara, Blok C No.15, Jl. Griya Utama, Sunter Agung, Jakarta Utara 14350', 'Jakarta', '14350', 'Indonesia', 'Pak Sudarjo', '0813 8305 6685; 0878 8202 7505', NULL, '2024-08-26 16:11:42', '2024-08-26 16:11:42'),
(10, 'PSN-010', 'Pasifik Satelit Nusantara', 'PSN', NULL, NULL, NULL, NULL, '[\"1\"]', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-26 16:17:35', '2024-08-26 16:17:35'),
(11, 'PACA-011', 'PT. ABHIMATA CITRA ABADI', 'PT. Abhimata', NULL, 'Tjatur Widjanarko <tjatur.widjanarko@abhimata.co.id>', NULL, NULL, '[\"1\"]', 'PT. ABHIMATA CITRA ABADI', 1, 1, NULL, NULL, NULL, NULL, 'MENARA BATAVIA 24th Floor, Jl. K.H. Mas Mansyur Kav.126, Jakarta 10220', 'Jakarta', '10220', 'Indonesia', 'Tjatur', NULL, NULL, '2024-08-26 16:25:53', '2024-08-26 16:25:53'),
(12, 'PANI-012', 'PT. AERO NUSANTARA INDONESIA', 'PT. Ani', NULL, 'ary@ani.co.id', NULL, NULL, '[\"1\"]', 'PT. AERO NUSANTARA INDONESIA', 1, 1, NULL, NULL, NULL, NULL, NULL, 'Jakarta', NULL, 'Indonesia', NULL, NULL, NULL, '2024-08-26 16:45:12', '2024-08-26 16:45:12'),
(13, 'PET-013', 'PT. Elnusa Tbk', 'PT. Elnusa', NULL, NULL, NULL, NULL, '[\"1\"]', 'PT. Elnusa Tbk', 1, 1, NULL, NULL, NULL, NULL, 'Jakarta', 'Jakarta', NULL, 'Indonesia', NULL, NULL, NULL, '2024-08-26 16:53:58', '2024-08-26 16:53:58'),
(14, 'PLA-014', 'PT. Lion Air', 'PT. Lion Air', NULL, 'muchlisin@lionairgroup.com', NULL, NULL, '[\"1\"]', 'PT. Lion Air', 1, 1, NULL, NULL, NULL, NULL, 'Jakarta', 'Jakarta', NULL, 'Indonesia', 'Pak Muchlisin; Pak Dimas', NULL, NULL, '2024-08-26 17:02:42', '2024-08-26 17:02:42'),
(15, 'SA-015', 'Skylight Aviation', 'Skylight', NULL, NULL, NULL, NULL, '[\"1\"]', 'Skylight Aviation', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-30 13:27:10', '2024-08-30 13:27:10'),
(16, 'PSLT-016', 'PT. SOECHI LINES Tbk', 'PT. Soechi', NULL, 'handy@soechi.id', NULL, NULL, '[\"1\"]', 'PT. SOECHI LINES Tbk', 1, NULL, NULL, NULL, NULL, NULL, 'Sahid Sudirman Center 51st Floor, Jl. Jend Sudirman, Kav 86 Jakarta Pusat', 'Jakarta', '10220', 'Indonesia', NULL, NULL, NULL, '2024-08-30 13:32:31', '2024-08-30 13:32:31'),
(17, 'ELOE-017', 'ENKEL LOGISTICS OU ESTONIA', 'Enkel Logistics', '+372 602 84 10', 'a.rutkovskii@enkel.ee', NULL, NULL, '[\"2\"]', 'ENKEL LOGISTICS OU ESTONIA', 1, NULL, NULL, NULL, NULL, NULL, 'Uuslinna tn 9/4-48, 11415 Tallinn, Estonia', 'Tallinn', '11415', 'Estonia', 'Aleksandr Rutkovskii (Project Manager)', NULL, NULL, '2024-08-30 13:40:26', '2024-08-30 13:40:26'),
(18, 'IS-018', 'INTERTRANSPORT SRL', 'Intertransport', NULL, 'marco.bertuzzi@intertransport.it', NULL, NULL, '[\"2\"]', 'INTERTRANSPORT SRL', 1, NULL, NULL, NULL, NULL, NULL, 'Office : Via Milano 162 F/R, 16126, Genova – Italy', 'Genova', '16126', 'Italy', NULL, NULL, NULL, '2024-08-30 13:53:38', '2024-08-30 13:53:38'),
(19, 'TBI-019', 'Trans Business International', 'TBI France', NULL, 'clara@tbifrance.com', NULL, NULL, '[\"2\"]', 'TBI France', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-30 14:12:48', '2024-08-30 14:12:48');

-- --------------------------------------------------------

--
-- Table structure for table `master_currency`
--

CREATE TABLE `master_currency` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `initial` varchar(255) DEFAULT NULL,
  `currency_name` varchar(255) DEFAULT NULL,
  `can_delete` tinyint(4) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `master_currency`
--

INSERT INTO `master_currency` (`id`, `initial`, `currency_name`, `can_delete`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'IDR', 'Rupiah', 0, NULL, '2024-08-20 09:33:36', NULL),
(2, 'SGD', 'Singapore Dollar', 0, NULL, '2024-08-20 09:33:36', NULL),
(3, 'USD', 'US Dollar', 0, NULL, '2024-08-20 09:33:36', NULL),
(4, 'EUR', 'Euro', 0, NULL, '2024-08-20 09:33:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `master_exchange`
--

CREATE TABLE `master_exchange` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date DEFAULT NULL,
  `from_currency_id` bigint(20) UNSIGNED DEFAULT NULL,
  `from_nominal` decimal(30,2) DEFAULT NULL,
  `to_currency_id` bigint(20) UNSIGNED DEFAULT NULL,
  `to_nominal` decimal(30,2) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `master_tax`
--

CREATE TABLE `master_tax` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `tax_rate` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `master_tax`
--

INSERT INTO `master_tax` (`id`, `code`, `name`, `tax_rate`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Non-Pajak', 'Non-Pajak', '0', 1, NULL, '2024-08-20 09:33:36', NULL),
(2, 'PPh 23-4', 'PPh Pasal 23 Non NPWP', '10', 1, NULL, '2024-08-20 09:33:36', NULL),
(3, 'PPh 24-5', 'PPh Pasal 23 NPWP', '5', 1, NULL, '2024-08-20 09:33:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `master_term_of_payment`
--

CREATE TABLE `master_term_of_payment` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `pay_days` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `master_term_of_payment`
--

INSERT INTO `master_term_of_payment` (`id`, `name`, `code`, `description`, `pay_days`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Cash On Delivery', 'COD', '', '0', NULL, '2024-08-20 09:33:36', NULL),
(2, '14 Days', '14Days', '', '14', NULL, '2024-08-20 09:33:36', NULL),
(3, '30 Days', '30Days', 'term 30 Hari', '30', NULL, '2024-08-26 15:51:07', '2024-08-26 15:51:07');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2014_10_12_100000_create_password_resets_table', 1),
(4, '2019_08_19_000000_create_failed_jobs_table', 1),
(5, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(6, '2024_01_23_012731_create_permission_tables', 1),
(7, '2024_01_23_014007_add_group_to_permissions_table', 1),
(8, '2024_01_24_092100_create_master_term_of_payment', 1),
(9, '2024_01_25_070555_create_master_contact', 1),
(10, '2024_01_25_093735_create_master_currency', 1),
(11, '2024_01_25_094037_create_master_tax', 1),
(12, '2024_01_25_094327_create_term_payment_master_contact', 1),
(13, '2024_01_26_022917_create_classification_account_type', 1),
(14, '2024_01_26_022937_create_account_type', 1),
(15, '2024_01_26_023028_create_master_account', 1),
(16, '2024_01_26_073012_create_marketing_export', 1),
(17, '2024_01_26_091440_create_transaction_type', 1),
(18, '2024_01_26_091441_create_balance_account_data', 1),
(19, '2024_01_27_034645_create_quotation_marketing_export', 1),
(20, '2024_01_27_035537_create_group_quotation_m_ex', 1),
(21, '2024_01_27_035557_create_item_group_quotation_m_ex', 1),
(22, '2024_01_27_070730_create_dimension_marketing_export', 1),
(23, '2024_01_28_034647_create_document_marketing_export', 1),
(24, '2024_01_28_222828_create_marketing_import_table', 1),
(25, '2024_01_28_222909_create_document_marketing_import', 1),
(26, '2024_02_01_135846_create_quotation_marketing_import', 1),
(27, '2024_02_01_140010_create_group_quotation_m_im', 1),
(28, '2024_02_01_140042_create_item_group_quotation_m_im', 1),
(29, '2024_02_01_140129_create_dimension_marketing_import', 1),
(30, '2024_02_22_005846_create_operation_import_table', 1),
(31, '2024_02_22_005915_create_activity_operation_import_table', 1),
(32, '2024_02_22_005940_create_vendor_operation_import_table', 1),
(33, '2024_02_22_010213_create_document_activity_op_im_table', 1),
(34, '2024_02_22_010240_create_document_arrival_op_im_table', 1),
(35, '2024_03_13_031834_create_operation_export_table', 1),
(36, '2024_03_13_031900_create_activity_operation_export_table', 1),
(37, '2024_03_13_031929_create_vendor_operation_export_table', 1),
(38, '2024_03_13_032038_create_document_activity_op_ex_table', 1),
(39, '2024_03_13_032106_create_document_arrival_op_ex_table', 1),
(40, '2024_03_14_233419_create_progress_operation_export', 1),
(41, '2024_03_14_234536_create_document_progress_op_ex', 1),
(42, '2024_03_15_030947_create_progress_operation_import', 1),
(43, '2024_03_15_031008_create_document_progress_op_im', 1),
(44, '2024_04_22_065847_create_sales_order_head', 1),
(45, '2024_04_22_065902_create_sales_order_detail', 1),
(46, '2024_05_10_065847_create_invoice_head', 1),
(47, '2024_05_10_065902_create_invoice_detail', 1),
(48, '2024_05_10_201203_create_master_exchange', 1),
(49, '2024_05_11_065847_create_receive_payment_head', 1),
(50, '2024_05_11_065848_create_recieve_payment_detail', 1),
(51, '2024_05_14_222758_create_no_transaction_table_kas_out', 1),
(52, '2024_05_17_125239_create_kas_out_head', 1),
(53, '2024_05_17_205655_create_kas_out_detail', 1),
(54, '2024_05_20_123038_create_no_transaction_table_kas_in', 1),
(55, '2024_05_21_122916_create_kas_in_head', 1),
(56, '2024_05_21_123022_create_kas_in_detail', 1),
(57, '2024_05_26_000419_create_account_payable_head', 1),
(58, '2024_05_26_000422_create_account_payable_detail', 1),
(59, '2024_05_26_000437_create_payment_head', 1),
(60, '2024_05_26_000440_create_payment_detail', 1),
(61, '2024_07_10_050944_create_bank_currency', 1),
(62, '2024_07_12_042805_create_notification_to_role', 1),
(63, '2024_08_13_065343_create_logo_address_table', 1),
(64, '2024_09_26_181443_create_sao_table', 2),
(65, '2024_10_10_001221_add_type_column_to_sao_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 3),
(4, 'App\\Models\\User', 2);

-- --------------------------------------------------------

--
-- Table structure for table `notification_to_role`
--

CREATE TABLE `notification_to_role` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `group_name` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notification_to_role`
--

INSERT INTO `notification_to_role` (`id`, `group_name`, `date`, `type`, `remark`, `content`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'finance', '2024-09-04', 'info-document', NULL, 'Dokumen POD balik AWB 54202200251_22032024_123211.pdf sudah diterima', NULL, '2024-09-04 15:08:21', '2024-09-04 15:08:21');

-- --------------------------------------------------------

--
-- Table structure for table `no_transaction_kas_in`
--

CREATE TABLE `no_transaction_kas_in` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `start` int(11) NOT NULL,
  `template` varchar(255) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `no_transaction_kas_out`
--

CREATE TABLE `no_transaction_kas_out` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `start` int(11) NOT NULL,
  `template` varchar(255) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `operation_export`
--

CREATE TABLE `operation_export` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `marketing_export_id` bigint(20) UNSIGNED DEFAULT NULL,
  `job_order_id` varchar(255) DEFAULT NULL,
  `origin` varchar(255) DEFAULT NULL,
  `pickup_address` varchar(255) DEFAULT NULL,
  `pickup_address_desc` varchar(255) DEFAULT NULL,
  `pickup_date` date DEFAULT NULL,
  `transportation` int(11) DEFAULT NULL,
  `transportation_desc` varchar(255) DEFAULT NULL,
  `departure_date` date DEFAULT NULL,
  `departure_time` time DEFAULT NULL,
  `destination` varchar(255) DEFAULT NULL,
  `arrival_date` date DEFAULT NULL,
  `arrival_time` time DEFAULT NULL,
  `delivery_address` varchar(255) DEFAULT NULL,
  `delivery_address_desc` varchar(255) DEFAULT NULL,
  `recepient_name` varchar(255) DEFAULT NULL,
  `arrival_desc` varchar(255) DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `operation_export`
--

INSERT INTO `operation_export` (`id`, `marketing_export_id`, `job_order_id`, `origin`, `pickup_address`, `pickup_address_desc`, `pickup_date`, `transportation`, `transportation_desc`, `departure_date`, `departure_time`, `destination`, `arrival_date`, `arrival_time`, `delivery_address`, `delivery_address_desc`, `recepient_name`, `arrival_desc`, `remark`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 2, 'JOPILJKT-00002', 'Riau', 'Jl. Srikaya', NULL, NULL, 2, 'LCL', NULL, NULL, 'Papua', NULL, NULL, 'Jl. Apel', NULL, 'Express JS', NULL, NULL, 1, NULL, '2024-08-20 09:33:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `operation_import`
--

CREATE TABLE `operation_import` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `marketing_import_id` bigint(20) UNSIGNED DEFAULT NULL,
  `job_order_id` varchar(255) DEFAULT NULL,
  `origin` varchar(255) DEFAULT NULL,
  `pickup_address` varchar(255) DEFAULT NULL,
  `pickup_address_desc` varchar(255) DEFAULT NULL,
  `pickup_date` date DEFAULT NULL,
  `transportation` int(11) DEFAULT NULL,
  `transportation_desc` varchar(255) DEFAULT NULL,
  `departure_date` date DEFAULT NULL,
  `departure_time` time DEFAULT NULL,
  `destination` varchar(255) DEFAULT NULL,
  `arrival_date` date DEFAULT NULL,
  `arrival_time` time DEFAULT NULL,
  `delivery_address` varchar(255) DEFAULT NULL,
  `delivery_address_desc` varchar(255) DEFAULT NULL,
  `recepient_name` varchar(255) DEFAULT NULL,
  `arrival_desc` varchar(255) DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `operation_import`
--

INSERT INTO `operation_import` (`id`, `marketing_import_id`, `job_order_id`, `origin`, `pickup_address`, `pickup_address_desc`, `pickup_date`, `transportation`, `transportation_desc`, `departure_date`, `departure_time`, `destination`, `arrival_date`, `arrival_time`, `delivery_address`, `delivery_address_desc`, `recepient_name`, `arrival_desc`, `remark`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 2, 'JOPILJKT-00002', 'Riau', 'Jl. Srikaya', NULL, NULL, 2, 'LCL', NULL, NULL, 'Papua', NULL, NULL, 'Jl. Apel', NULL, 'Express JS', NULL, NULL, 1, NULL, '2024-08-20 09:33:37', NULL),
(2, 3, 'JOPILJKT-00003', 'Italy', 'Nola - Napoly, Italy', NULL, '2024-03-18', 1, 'Regular', '2024-03-18', '15:06:00', 'Kuala Lumpur', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2024-08-30 11:25:26', '2024-09-04 15:08:21'),
(3, 4, 'JOPILJKT-00004', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2024-08-30 11:31:09', '2024-08-30 11:31:09'),
(4, 5, 'JOPILJKT-00005', 'Thailand', NULL, NULL, NULL, 1, NULL, NULL, NULL, 'Singapore', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2024-08-30 13:33:31', '2024-08-30 13:33:31'),
(5, 6, 'JOPILJKT-00006', 'Prancis', NULL, NULL, NULL, 1, NULL, NULL, NULL, 'Jakarta', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2024-08-30 13:34:58', '2024-08-30 13:34:58'),
(6, 7, 'JOPILJKT-00007', NULL, NULL, NULL, NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2024-08-30 14:01:07', '2024-08-30 14:01:07'),
(7, 8, 'JOPILJKT-00008', NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2024-08-30 14:02:10', '2024-08-30 14:02:10'),
(8, 9, 'JOPILJKT-00009', NULL, NULL, NULL, NULL, 2, 'FCL', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2024-08-30 14:03:24', '2024-08-30 14:03:24');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_detail`
--

CREATE TABLE `payment_detail` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `head_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payable_id` bigint(20) UNSIGNED DEFAULT NULL,
  `discount_type` varchar(255) DEFAULT NULL,
  `discount_nominal` decimal(30,2) DEFAULT NULL,
  `dp_type` varchar(255) DEFAULT NULL,
  `dp_nominal` decimal(30,2) DEFAULT NULL,
  `currency_via_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount_via` decimal(30,2) DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_head`
--

CREATE TABLE `payment_head` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vendor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `currency_id` bigint(20) UNSIGNED DEFAULT NULL,
  `date_payment` date DEFAULT NULL,
  `number` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `job_order_id` int(11) DEFAULT NULL,
  `source` varchar(255) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `additional_cost` decimal(30,2) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `group` varchar(255) DEFAULT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `group`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'create-role@role', 'role', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(2, 'edit-role@role', 'role', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(3, 'delete-role@role', 'role', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(4, 'create-user@user', 'user', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(5, 'edit-user@user', 'user', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(6, 'delete-user@user', 'user', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(7, 'view-export@operation', 'operation', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(8, 'edit-export@operation', 'operation', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(9, 'view-import@operation', 'operation', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(10, 'edit-import@operation', 'operation', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(11, 'view-export@marketing', 'marketing', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(12, 'create-export@marketing', 'marketing', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(13, 'delete-export@marketing', 'marketing', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(14, 'edit-export@marketing', 'marketing', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(15, 'view-import@marketing', 'marketing', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(16, 'create-import@marketing', 'marketing', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(17, 'delete-import@marketing', 'marketing', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(18, 'edit-import@marketing', 'marketing', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(19, 'view-contact@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(20, 'create-contact@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(21, 'delete-contact@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(22, 'edit-contact@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(23, 'view-account@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(24, 'create-account@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(25, 'delete-account@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(26, 'edit-account@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(27, 'view-currency@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(28, 'create-currency@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(29, 'delete-currency@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(30, 'edit-currency@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(31, 'view-tax@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(32, 'create-tax@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(33, 'delete-tax@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(34, 'edit-tax@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(35, 'view-term@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(36, 'create-term@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(37, 'delete-term@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(38, 'edit-term@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(39, 'view-sales_order@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(40, 'create-sales_order@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(41, 'delete-sales_order@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(42, 'edit-sales_order@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(43, 'view-invoice@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(44, 'create-invoice@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(45, 'delete-invoice@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(46, 'edit-invoice@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(47, 'view-receive_payment@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(48, 'create-receive_payment@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(49, 'delete-receive_payment@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(50, 'edit-receive_payment@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(51, 'view-kas_in@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(52, 'create-kas_in@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(53, 'delete-kas_in@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(54, 'edit-kas_in@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(55, 'view-kas_out@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(56, 'create-kas_out@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(57, 'delete-kas_out@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(58, 'edit-kas_out@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(59, 'view-account_payable@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(60, 'create-account_payable@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(61, 'delete-account_payable@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(62, 'edit-account_payable@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(63, 'view-payment@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(64, 'create-payment@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(65, 'delete-payment@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(66, 'edit-payment@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(67, 'view-exchange_rate@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(68, 'create-exchange_rate@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(69, 'delete-exchange_rate@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(70, 'edit-exchange_rate@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(71, 'view-buku_besar@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(72, 'view-jurnal_umum@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(73, 'view-neraca_saldo@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(74, 'view-arus_kas@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(75, 'view-laba_rugi@finance', 'finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `progress_operation_export`
--

CREATE TABLE `progress_operation_export` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `operation_export_id` bigint(20) UNSIGNED DEFAULT NULL,
  `date_progress` date DEFAULT NULL,
  `time_progress` time DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `location_desc` varchar(255) DEFAULT NULL,
  `transportation` int(11) DEFAULT NULL,
  `transportation_desc` varchar(255) DEFAULT NULL,
  `carrier` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `progress_operation_import`
--

CREATE TABLE `progress_operation_import` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `operation_import_id` bigint(20) UNSIGNED DEFAULT NULL,
  `date_progress` date DEFAULT NULL,
  `time_progress` time DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `location_desc` varchar(255) DEFAULT NULL,
  `transportation` int(11) DEFAULT NULL,
  `transportation_desc` varchar(255) DEFAULT NULL,
  `carrier` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quotation_marketing_export`
--

CREATE TABLE `quotation_marketing_export` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `marketing_export_id` bigint(20) UNSIGNED DEFAULT NULL,
  `date` date DEFAULT NULL,
  `quotation_no` varchar(255) DEFAULT NULL,
  `valid_until` date DEFAULT NULL,
  `project_desc` text DEFAULT NULL,
  `currency_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sales_value` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quotation_marketing_export`
--

INSERT INTO `quotation_marketing_export` (`id`, `marketing_export_id`, `date`, `quotation_no`, `valid_until`, `project_desc`, `currency_id`, `sales_value`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, '2024-08-20', '99898', '2024-08-20', '-', 1, '3000000', NULL, '2024-08-20 09:33:36', NULL),
(2, 2, '2024-08-20', '90000', '2024-08-20', '-', 1, '3000000', NULL, '2024-08-20 09:33:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `quotation_marketing_import`
--

CREATE TABLE `quotation_marketing_import` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `marketing_import_id` bigint(20) UNSIGNED DEFAULT NULL,
  `date` date DEFAULT NULL,
  `quotation_no` varchar(255) DEFAULT NULL,
  `valid_until` date DEFAULT NULL,
  `project_desc` text DEFAULT NULL,
  `currency_id` bigint(20) UNSIGNED DEFAULT NULL,
  `sales_value` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quotation_marketing_import`
--

INSERT INTO `quotation_marketing_import` (`id`, `marketing_import_id`, `date`, `quotation_no`, `valid_until`, `project_desc`, `currency_id`, `sales_value`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, '2024-08-20', '99898', '2024-08-20', '-', 1, '3000000', NULL, '2024-08-20 09:33:37', NULL),
(2, 2, '2024-08-20', '90000', '2024-08-20', '-', 1, '3000000', NULL, '2024-08-20 09:33:37', NULL),
(3, 3, '2024-03-05', '008', '2024-03-31', 'Pengiriman Aircraft Parts', 1, '17658000', NULL, '2024-09-06 15:45:27', '2024-09-06 15:45:27'),
(4, 9, '2024-07-03', '021', '2024-09-10', NULL, 1, '291018924', NULL, '2024-09-06 16:05:13', '2024-09-06 16:05:13');

-- --------------------------------------------------------

--
-- Table structure for table `receive_payment_detail`
--

CREATE TABLE `receive_payment_detail` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `head_id` bigint(20) UNSIGNED DEFAULT NULL,
  `invoice_id` bigint(20) UNSIGNED DEFAULT NULL,
  `discount_type` varchar(255) DEFAULT NULL,
  `discount_nominal` decimal(30,2) DEFAULT NULL,
  `dp_type` varchar(255) DEFAULT NULL,
  `dp_nominal` decimal(30,2) DEFAULT NULL,
  `currency_via_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount_via` decimal(30,2) DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `receive_payment_head`
--

CREATE TABLE `receive_payment_head` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `contact_id` bigint(20) UNSIGNED DEFAULT NULL,
  `account_id` bigint(20) UNSIGNED DEFAULT NULL,
  `currency_id` bigint(20) UNSIGNED DEFAULT NULL,
  `date_recieve` date DEFAULT NULL,
  `number` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `job_order_id` int(11) DEFAULT NULL,
  `source` varchar(255) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `additional_cost` decimal(30,2) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(2, 'Marketing', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(3, 'Operation', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35'),
(4, 'Finance', 'web', '2024-08-20 09:33:35', '2024-08-20 09:33:35');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 2),
(1, 4),
(2, 2),
(2, 4),
(7, 3),
(7, 4),
(8, 3),
(8, 4),
(9, 3),
(9, 4),
(10, 3),
(10, 4),
(11, 2),
(11, 4),
(12, 2),
(12, 4),
(13, 2),
(13, 4),
(14, 2),
(14, 4),
(15, 2),
(15, 4),
(16, 2),
(16, 4),
(17, 2),
(17, 4),
(18, 2),
(18, 4),
(19, 4),
(20, 4),
(21, 4),
(22, 4),
(23, 4),
(24, 4),
(25, 4),
(26, 4),
(27, 4),
(28, 4),
(29, 4),
(30, 4),
(31, 4),
(32, 4),
(33, 4),
(34, 4),
(35, 4),
(36, 4),
(37, 4),
(38, 4),
(39, 4),
(40, 4),
(41, 4),
(42, 4),
(43, 4),
(44, 4),
(45, 4),
(46, 4),
(47, 4),
(48, 4),
(49, 4),
(50, 4),
(51, 4),
(52, 4),
(53, 4),
(54, 4),
(55, 4),
(56, 4),
(57, 4),
(58, 4),
(59, 4),
(60, 4),
(61, 4),
(62, 4),
(63, 4),
(64, 4),
(65, 4),
(66, 4),
(67, 4),
(68, 4),
(69, 4),
(70, 4),
(71, 4),
(72, 4),
(73, 4),
(74, 4),
(75, 4);

-- --------------------------------------------------------

--
-- Table structure for table `sales_order_detail`
--

CREATE TABLE `sales_order_detail` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `head_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` text DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `uom` varchar(255) DEFAULT NULL,
  `price` decimal(30,2) DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `discount_type` varchar(255) DEFAULT NULL,
  `discount_nominal` decimal(30,2) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_order_head`
--

CREATE TABLE `sales_order_head` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `contact_id` bigint(20) UNSIGNED DEFAULT NULL,
  `number` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `currency_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` text DEFAULT NULL,
  `marketing_id` int(11) DEFAULT NULL,
  `source` varchar(255) DEFAULT NULL,
  `additional_cost` decimal(30,2) DEFAULT NULL,
  `discount_type` varchar(255) DEFAULT NULL,
  `discount_nominal` decimal(30,2) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sao`
--

CREATE TABLE `sao` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `contact_id` bigint(20) UNSIGNED DEFAULT NULL,
  `currency_id` bigint(20) UNSIGNED DEFAULT NULL,
  `invoice_id` bigint(20) UNSIGNED DEFAULT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `vendor_id` bigint(20) UNSIGNED DEFAULT NULL,
  `date` date NOT NULL,
  `account` varchar(255) NOT NULL,
  `total` decimal(15,2) NOT NULL,
  `already_paid` decimal(15,2) NOT NULL,
  `remaining` decimal(15,2) NOT NULL,
  `isPaid` tinyint(1) NOT NULL DEFAULT 0,
  `type` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `term_payment_master_contact`
--

CREATE TABLE `term_payment_master_contact` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `term_payment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `contact_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `term_payment_master_contact`
--

INSERT INTO `term_payment_master_contact` (`id`, `term_payment_id`, `contact_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2024-08-20 09:33:36', NULL),
(2, 1, 2, '2024-08-20 09:33:36', NULL),
(3, 2, 3, '2024-08-20 09:33:36', NULL),
(6, 3, 4, '2024-08-26 15:51:25', '2024-08-26 15:51:25'),
(7, 3, 5, '2024-08-26 15:51:35', '2024-08-26 15:51:35'),
(8, 3, 6, '2024-08-26 15:55:58', '2024-08-26 15:55:58'),
(9, 3, 7, '2024-08-26 16:01:51', '2024-08-26 16:01:51'),
(10, 3, 8, '2024-08-26 16:06:54', '2024-08-26 16:06:54'),
(11, 3, 9, '2024-08-26 16:11:42', '2024-08-26 16:11:42'),
(12, 3, 10, '2024-08-26 16:17:35', '2024-08-26 16:17:35'),
(13, 2, 11, '2024-08-26 16:25:53', '2024-08-26 16:25:53'),
(14, 3, 12, '2024-08-26 16:45:12', '2024-08-26 16:45:12'),
(15, 1, 13, '2024-08-26 16:53:58', '2024-08-26 16:53:58'),
(16, 3, 14, '2024-08-26 17:02:42', '2024-08-26 17:02:42'),
(17, 2, 15, '2024-08-30 13:27:10', '2024-08-30 13:27:10'),
(18, 2, 16, '2024-08-30 13:32:31', '2024-08-30 13:32:31'),
(19, 2, 17, '2024-08-30 13:40:26', '2024-08-30 13:40:26'),
(20, 3, 18, '2024-08-30 13:53:38', '2024-08-30 13:53:38'),
(21, 3, 19, '2024-08-30 14:12:48', '2024-08-30 14:12:48');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_type`
--

CREATE TABLE `transaction_type` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_type` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaction_type`
--

INSERT INTO `transaction_type` (`id`, `transaction_type`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Saldo Awal', NULL, '2024-08-20 09:33:36', NULL),
(2, 'Sales Order', NULL, '2024-08-20 09:33:36', NULL),
(3, 'Invoice', NULL, '2024-08-20 09:33:36', NULL),
(4, 'Receive Payment', NULL, '2024-08-20 09:33:36', NULL),
(5, 'Cash & Bank Out', NULL, '2024-08-20 09:33:36', NULL),
(6, 'Cash & Bank In', NULL, '2024-08-20 09:33:36', NULL),
(7, 'Account Payable', NULL, '2024-08-20 09:33:36', NULL),
(8, 'Payment', NULL, '2024-08-20 09:33:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `department`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'SuperAdmin', 'superadmin', 'SuperAdmin', 'superadmin@pil.com', NULL, '$2y$12$6kpuxABjkSrqqfDuT39gzO6WNdVgIlvp6UcL6MPBFCUnBATv.g6D.', NULL, '2024-08-20 09:33:36', '2024-08-20 09:33:36'),
(2, 'Athifah1', 'athifah1', 'finance', NULL, NULL, '$2y$10$Nd4WPXaihS8RHUUUBoF2EO/p6Nsy3OWrqyRLHUiV9aCOfD0TPl98e', NULL, '2024-08-30 10:53:24', '2024-08-30 10:53:24'),
(3, 'Putri1', 'putri1', 'marketing', NULL, NULL, '$2y$10$Nd4WPXaihS8RHUUUBoF2EO/p6Nsy3OWrqyRLHUiV9aCOfD0TPl98e', NULL, '2024-08-30 10:53:58', '2024-08-30 10:53:58');

-- --------------------------------------------------------

--
-- Table structure for table `vendor_operation_export`
--

CREATE TABLE `vendor_operation_export` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `operation_export_id` bigint(20) UNSIGNED DEFAULT NULL,
  `vendor` int(11) DEFAULT NULL,
  `total_charge` varchar(255) DEFAULT NULL,
  `transit` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendor_operation_import`
--

CREATE TABLE `vendor_operation_import` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `operation_import_id` bigint(20) UNSIGNED DEFAULT NULL,
  `vendor` int(11) DEFAULT NULL,
  `total_charge` varchar(255) DEFAULT NULL,
  `transit` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendor_operation_import`
--

INSERT INTO `vendor_operation_import` (`id`, `operation_import_id`, `vendor`, `total_charge`, `transit`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 2, NULL, NULL, 'Italy', NULL, '2024-09-04 15:08:21', '2024-09-04 15:08:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_payable_detail`
--
ALTER TABLE `account_payable_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_payable_detail_head_id_foreign` (`head_id`),
  ADD KEY `account_payable_detail_tax_id_foreign` (`tax_id`);

--
-- Indexes for table `account_payable_head`
--
ALTER TABLE `account_payable_head`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `account_payable_head_transaction_unique` (`transaction`),
  ADD KEY `account_payable_head_vendor_id_foreign` (`vendor_id`),
  ADD KEY `account_payable_head_customer_id_foreign` (`customer_id`),
  ADD KEY `account_payable_head_currency_id_foreign` (`currency_id`);

--
-- Indexes for table `account_type`
--
ALTER TABLE `account_type`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_type_classification_id_foreign` (`classification_id`);

--
-- Indexes for table `activity_operation_export`
--
ALTER TABLE `activity_operation_export`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_operation_export_operation_export_id_foreign` (`operation_export_id`);

--
-- Indexes for table `activity_operation_import`
--
ALTER TABLE `activity_operation_import`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_operation_import_operation_import_id_foreign` (`operation_import_id`);

--
-- Indexes for table `balance_account_data`
--
ALTER TABLE `balance_account_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `balance_account_data_master_account_id_foreign` (`master_account_id`),
  ADD KEY `balance_account_data_transaction_type_id_foreign` (`transaction_type_id`);

--
-- Indexes for table `classification_account_type`
--
ALTER TABLE `classification_account_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dimension_marketing_export`
--
ALTER TABLE `dimension_marketing_export`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dimension_marketing_export_marketing_export_id_foreign` (`marketing_export_id`);

--
-- Indexes for table `dimension_marketing_import`
--
ALTER TABLE `dimension_marketing_import`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dimension_marketing_import_marketing_import_id_foreign` (`marketing_import_id`);

--
-- Indexes for table `document_activity_op_ex`
--
ALTER TABLE `document_activity_op_ex`
  ADD PRIMARY KEY (`id`),
  ADD KEY `document_activity_op_ex_activity_operation_export_id_foreign` (`activity_operation_export_id`);

--
-- Indexes for table `document_activity_op_im`
--
ALTER TABLE `document_activity_op_im`
  ADD PRIMARY KEY (`id`),
  ADD KEY `document_activity_op_im_activity_operation_import_id_foreign` (`activity_operation_import_id`);

--
-- Indexes for table `document_arrival_op_ex`
--
ALTER TABLE `document_arrival_op_ex`
  ADD PRIMARY KEY (`id`),
  ADD KEY `document_arrival_op_ex_operation_export_id_foreign` (`operation_export_id`);

--
-- Indexes for table `document_arrival_op_im`
--
ALTER TABLE `document_arrival_op_im`
  ADD PRIMARY KEY (`id`),
  ADD KEY `document_arrival_op_im_operation_import_id_foreign` (`operation_import_id`);

--
-- Indexes for table `document_marketing_export`
--
ALTER TABLE `document_marketing_export`
  ADD PRIMARY KEY (`id`),
  ADD KEY `document_marketing_export_marketing_export_id_foreign` (`marketing_export_id`);

--
-- Indexes for table `document_marketing_import`
--
ALTER TABLE `document_marketing_import`
  ADD PRIMARY KEY (`id`),
  ADD KEY `document_marketing_import_marketing_import_id_foreign` (`marketing_import_id`);

--
-- Indexes for table `document_progress_op_ex`
--
ALTER TABLE `document_progress_op_ex`
  ADD PRIMARY KEY (`id`),
  ADD KEY `document_progress_op_ex_progress_operation_export_id_foreign` (`progress_operation_export_id`);

--
-- Indexes for table `document_progress_op_im`
--
ALTER TABLE `document_progress_op_im`
  ADD PRIMARY KEY (`id`),
  ADD KEY `document_progress_op_im_progress_operation_import_id_foreign` (`progress_operation_import_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `group_quotation_m_ex`
--
ALTER TABLE `group_quotation_m_ex`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_quotation_m_ex_quotation_m_ex_id_foreign` (`quotation_m_ex_id`);

--
-- Indexes for table `group_quotation_m_im`
--
ALTER TABLE `group_quotation_m_im`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_quotation_m_im_quotation_m_im_id_foreign` (`quotation_m_im_id`);

--
-- Indexes for table `invoice_detail`
--
ALTER TABLE `invoice_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_detail_head_id_foreign` (`head_id`),
  ADD KEY `invoice_detail_tax_id_foreign` (`tax_id`);

--
-- Indexes for table `invoice_head`
--
ALTER TABLE `invoice_head`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_head_contact_id_foreign` (`contact_id`),
  ADD KEY `invoice_head_sales_id_foreign` (`sales_id`),
  ADD KEY `invoice_head_term_payment_foreign` (`term_payment`),
  ADD KEY `invoice_head_currency_id_foreign` (`currency_id`);

--
-- Indexes for table `item_group_quotation_m_ex`
--
ALTER TABLE `item_group_quotation_m_ex`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_group_quotation_m_ex_group_quotation_m_ex_id_foreign` (`group_quotation_m_ex_id`);

--
-- Indexes for table `item_group_quotation_m_im`
--
ALTER TABLE `item_group_quotation_m_im`
  ADD PRIMARY KEY (`id`),
  ADD KEY `item_group_quotation_m_im_group_quotation_m_im_id_foreign` (`group_quotation_m_im_id`);

--
-- Indexes for table `kas_in_detail`
--
ALTER TABLE `kas_in_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kas_in_detail_head_id_foreign` (`head_id`),
  ADD KEY `kas_in_detail_account_id_foreign` (`account_id`);

--
-- Indexes for table `kas_in_head`
--
ALTER TABLE `kas_in_head`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kas_in_head_contact_id_foreign` (`contact_id`),
  ADD KEY `kas_in_head_account_id_foreign` (`account_id`),
  ADD KEY `kas_in_head_currency_id_foreign` (`currency_id`),
  ADD KEY `kas_in_head_transaction_id_foreign` (`transaction_id`);

--
-- Indexes for table `kas_out_detail`
--
ALTER TABLE `kas_out_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kas_out_detail_head_id_foreign` (`head_id`),
  ADD KEY `kas_out_detail_account_id_foreign` (`account_id`);

--
-- Indexes for table `kas_out_head`
--
ALTER TABLE `kas_out_head`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kas_out_head_contact_id_foreign` (`contact_id`),
  ADD KEY `kas_out_head_account_id_foreign` (`account_id`),
  ADD KEY `kas_out_head_currency_id_foreign` (`currency_id`),
  ADD KEY `kas_out_head_transaction_id_foreign` (`transaction_id`);

--
-- Indexes for table `logo_address`
--
ALTER TABLE `logo_address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `marketing_export`
--
ALTER TABLE `marketing_export`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `marketing_export_job_order_id_unique` (`job_order_id`),
  ADD KEY `marketing_export_contact_id_foreign` (`contact_id`);

--
-- Indexes for table `marketing_import`
--
ALTER TABLE `marketing_import`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `marketing_import_job_order_id_unique` (`job_order_id`),
  ADD KEY `marketing_import_contact_id_foreign` (`contact_id`);

--
-- Indexes for table `master_account`
--
ALTER TABLE `master_account`
  ADD PRIMARY KEY (`id`),
  ADD KEY `master_account_account_type_id_foreign` (`account_type_id`),
  ADD KEY `master_account_master_currency_id_foreign` (`master_currency_id`);

--
-- Indexes for table `master_bank_currency`
--
ALTER TABLE `master_bank_currency`
  ADD PRIMARY KEY (`id`),
  ADD KEY `master_bank_currency_currency_id_foreign` (`currency_id`);

--
-- Indexes for table `master_contact`
--
ALTER TABLE `master_contact`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_currency`
--
ALTER TABLE `master_currency`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_exchange`
--
ALTER TABLE `master_exchange`
  ADD PRIMARY KEY (`id`),
  ADD KEY `master_exchange_from_currency_id_foreign` (`from_currency_id`),
  ADD KEY `master_exchange_to_currency_id_foreign` (`to_currency_id`);

--
-- Indexes for table `master_tax`
--
ALTER TABLE `master_tax`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `master_term_of_payment`
--
ALTER TABLE `master_term_of_payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `notification_to_role`
--
ALTER TABLE `notification_to_role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `no_transaction_kas_in`
--
ALTER TABLE `no_transaction_kas_in`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `no_transaction_kas_out`
--
ALTER TABLE `no_transaction_kas_out`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `operation_export`
--
ALTER TABLE `operation_export`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `operation_export_job_order_id_unique` (`job_order_id`),
  ADD KEY `operation_export_marketing_export_id_foreign` (`marketing_export_id`);

--
-- Indexes for table `operation_import`
--
ALTER TABLE `operation_import`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `operation_import_job_order_id_unique` (`job_order_id`),
  ADD KEY `operation_import_marketing_import_id_foreign` (`marketing_import_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payment_detail`
--
ALTER TABLE `payment_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_detail_head_id_foreign` (`head_id`),
  ADD KEY `payment_detail_payable_id_foreign` (`payable_id`),
  ADD KEY `payment_detail_currency_via_id_foreign` (`currency_via_id`);

--
-- Indexes for table `payment_head`
--
ALTER TABLE `payment_head`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_head_vendor_id_foreign` (`vendor_id`),
  ADD KEY `payment_head_customer_id_foreign` (`customer_id`),
  ADD KEY `payment_head_account_id_foreign` (`account_id`),
  ADD KEY `payment_head_currency_id_foreign` (`currency_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `progress_operation_export`
--
ALTER TABLE `progress_operation_export`
  ADD PRIMARY KEY (`id`),
  ADD KEY `progress_operation_export_operation_export_id_foreign` (`operation_export_id`);

--
-- Indexes for table `progress_operation_import`
--
ALTER TABLE `progress_operation_import`
  ADD PRIMARY KEY (`id`),
  ADD KEY `progress_operation_import_operation_import_id_foreign` (`operation_import_id`);

--
-- Indexes for table `quotation_marketing_export`
--
ALTER TABLE `quotation_marketing_export`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quotation_marketing_export_marketing_export_id_foreign` (`marketing_export_id`),
  ADD KEY `quotation_marketing_export_currency_id_foreign` (`currency_id`);

--
-- Indexes for table `quotation_marketing_import`
--
ALTER TABLE `quotation_marketing_import`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quotation_marketing_import_marketing_import_id_foreign` (`marketing_import_id`),
  ADD KEY `quotation_marketing_import_currency_id_foreign` (`currency_id`);

--
-- Indexes for table `receive_payment_detail`
--
ALTER TABLE `receive_payment_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `receive_payment_detail_head_id_foreign` (`head_id`),
  ADD KEY `receive_payment_detail_invoice_id_foreign` (`invoice_id`),
  ADD KEY `receive_payment_detail_currency_via_id_foreign` (`currency_via_id`);

--
-- Indexes for table `receive_payment_head`
--
ALTER TABLE `receive_payment_head`
  ADD PRIMARY KEY (`id`),
  ADD KEY `receive_payment_head_contact_id_foreign` (`contact_id`),
  ADD KEY `receive_payment_head_account_id_foreign` (`account_id`),
  ADD KEY `receive_payment_head_currency_id_foreign` (`currency_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sales_order_detail`
--
ALTER TABLE `sales_order_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_order_detail_head_id_foreign` (`head_id`);

--
-- Indexes for table `sales_order_head`
--
ALTER TABLE `sales_order_head`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_order_head_contact_id_foreign` (`contact_id`),
  ADD KEY `sales_order_head_currency_id_foreign` (`currency_id`);

--
-- Indexes for table `sao`
--
ALTER TABLE `sao`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sao_contact_id_foreign` (`contact_id`),
  ADD KEY `sao_currency_id_foreign` (`currency_id`),
  ADD KEY `sao_invoice_id_foreign` (`invoice_id`),
  ADD KEY `sao_order_id_foreign` (`order_id`),
  ADD KEY `sao_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `term_payment_master_contact`
--
ALTER TABLE `term_payment_master_contact`
  ADD PRIMARY KEY (`id`),
  ADD KEY `term_payment_master_contact_term_payment_id_foreign` (`term_payment_id`),
  ADD KEY `term_payment_master_contact_contact_id_foreign` (`contact_id`);

--
-- Indexes for table `transaction_type`
--
ALTER TABLE `transaction_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `vendor_operation_export`
--
ALTER TABLE `vendor_operation_export`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_operation_export_operation_export_id_foreign` (`operation_export_id`);

--
-- Indexes for table `vendor_operation_import`
--
ALTER TABLE `vendor_operation_import`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_operation_import_operation_import_id_foreign` (`operation_import_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account_payable_detail`
--
ALTER TABLE `account_payable_detail`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `account_payable_head`
--
ALTER TABLE `account_payable_head`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `account_type`
--
ALTER TABLE `account_type`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `activity_operation_export`
--
ALTER TABLE `activity_operation_export`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `activity_operation_import`
--
ALTER TABLE `activity_operation_import`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `balance_account_data`
--
ALTER TABLE `balance_account_data`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `classification_account_type`
--
ALTER TABLE `classification_account_type`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `dimension_marketing_export`
--
ALTER TABLE `dimension_marketing_export`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `dimension_marketing_import`
--
ALTER TABLE `dimension_marketing_import`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `document_activity_op_ex`
--
ALTER TABLE `document_activity_op_ex`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `document_activity_op_im`
--
ALTER TABLE `document_activity_op_im`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `document_arrival_op_ex`
--
ALTER TABLE `document_arrival_op_ex`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `document_arrival_op_im`
--
ALTER TABLE `document_arrival_op_im`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `document_marketing_export`
--
ALTER TABLE `document_marketing_export`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `document_marketing_import`
--
ALTER TABLE `document_marketing_import`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `document_progress_op_ex`
--
ALTER TABLE `document_progress_op_ex`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `document_progress_op_im`
--
ALTER TABLE `document_progress_op_im`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `group_quotation_m_ex`
--
ALTER TABLE `group_quotation_m_ex`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `group_quotation_m_im`
--
ALTER TABLE `group_quotation_m_im`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `invoice_detail`
--
ALTER TABLE `invoice_detail`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_head`
--
ALTER TABLE `invoice_head`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_group_quotation_m_ex`
--
ALTER TABLE `item_group_quotation_m_ex`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `item_group_quotation_m_im`
--
ALTER TABLE `item_group_quotation_m_im`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `kas_in_detail`
--
ALTER TABLE `kas_in_detail`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kas_in_head`
--
ALTER TABLE `kas_in_head`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kas_out_detail`
--
ALTER TABLE `kas_out_detail`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kas_out_head`
--
ALTER TABLE `kas_out_head`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `logo_address`
--
ALTER TABLE `logo_address`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `marketing_export`
--
ALTER TABLE `marketing_export`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `marketing_import`
--
ALTER TABLE `marketing_import`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `master_account`
--
ALTER TABLE `master_account`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `master_bank_currency`
--
ALTER TABLE `master_bank_currency`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_contact`
--
ALTER TABLE `master_contact`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `master_currency`
--
ALTER TABLE `master_currency`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `master_exchange`
--
ALTER TABLE `master_exchange`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `master_tax`
--
ALTER TABLE `master_tax`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `master_term_of_payment`
--
ALTER TABLE `master_term_of_payment`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `notification_to_role`
--
ALTER TABLE `notification_to_role`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `no_transaction_kas_in`
--
ALTER TABLE `no_transaction_kas_in`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `no_transaction_kas_out`
--
ALTER TABLE `no_transaction_kas_out`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `operation_export`
--
ALTER TABLE `operation_export`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `operation_import`
--
ALTER TABLE `operation_import`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `payment_detail`
--
ALTER TABLE `payment_detail`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_head`
--
ALTER TABLE `payment_head`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `progress_operation_export`
--
ALTER TABLE `progress_operation_export`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `progress_operation_import`
--
ALTER TABLE `progress_operation_import`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quotation_marketing_export`
--
ALTER TABLE `quotation_marketing_export`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `quotation_marketing_import`
--
ALTER TABLE `quotation_marketing_import`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `receive_payment_detail`
--
ALTER TABLE `receive_payment_detail`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `receive_payment_head`
--
ALTER TABLE `receive_payment_head`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sales_order_detail`
--
ALTER TABLE `sales_order_detail`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_order_head`
--
ALTER TABLE `sales_order_head`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sao`
--
ALTER TABLE `sao`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `term_payment_master_contact`
--
ALTER TABLE `term_payment_master_contact`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `transaction_type`
--
ALTER TABLE `transaction_type`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `vendor_operation_export`
--
ALTER TABLE `vendor_operation_export`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vendor_operation_import`
--
ALTER TABLE `vendor_operation_import`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `account_payable_detail`
--
ALTER TABLE `account_payable_detail`
  ADD CONSTRAINT `account_payable_detail_head_id_foreign` FOREIGN KEY (`head_id`) REFERENCES `account_payable_head` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `account_payable_detail_tax_id_foreign` FOREIGN KEY (`tax_id`) REFERENCES `master_tax` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `account_payable_head`
--
ALTER TABLE `account_payable_head`
  ADD CONSTRAINT `account_payable_head_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `master_currency` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `account_payable_head_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `master_contact` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `account_payable_head_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `master_contact` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `account_type`
--
ALTER TABLE `account_type`
  ADD CONSTRAINT `account_type_classification_id_foreign` FOREIGN KEY (`classification_id`) REFERENCES `classification_account_type` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `activity_operation_export`
--
ALTER TABLE `activity_operation_export`
  ADD CONSTRAINT `activity_operation_export_operation_export_id_foreign` FOREIGN KEY (`operation_export_id`) REFERENCES `operation_export` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `activity_operation_import`
--
ALTER TABLE `activity_operation_import`
  ADD CONSTRAINT `activity_operation_import_operation_import_id_foreign` FOREIGN KEY (`operation_import_id`) REFERENCES `operation_import` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `balance_account_data`
--
ALTER TABLE `balance_account_data`
  ADD CONSTRAINT `balance_account_data_master_account_id_foreign` FOREIGN KEY (`master_account_id`) REFERENCES `master_account` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `balance_account_data_transaction_type_id_foreign` FOREIGN KEY (`transaction_type_id`) REFERENCES `transaction_type` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `dimension_marketing_export`
--
ALTER TABLE `dimension_marketing_export`
  ADD CONSTRAINT `dimension_marketing_export_marketing_export_id_foreign` FOREIGN KEY (`marketing_export_id`) REFERENCES `marketing_export` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `dimension_marketing_import`
--
ALTER TABLE `dimension_marketing_import`
  ADD CONSTRAINT `dimension_marketing_import_marketing_import_id_foreign` FOREIGN KEY (`marketing_import_id`) REFERENCES `marketing_import` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `document_activity_op_ex`
--
ALTER TABLE `document_activity_op_ex`
  ADD CONSTRAINT `document_activity_op_ex_activity_operation_export_id_foreign` FOREIGN KEY (`activity_operation_export_id`) REFERENCES `activity_operation_export` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `document_activity_op_im`
--
ALTER TABLE `document_activity_op_im`
  ADD CONSTRAINT `document_activity_op_im_activity_operation_import_id_foreign` FOREIGN KEY (`activity_operation_import_id`) REFERENCES `activity_operation_import` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `document_arrival_op_ex`
--
ALTER TABLE `document_arrival_op_ex`
  ADD CONSTRAINT `document_arrival_op_ex_operation_export_id_foreign` FOREIGN KEY (`operation_export_id`) REFERENCES `operation_export` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `document_arrival_op_im`
--
ALTER TABLE `document_arrival_op_im`
  ADD CONSTRAINT `document_arrival_op_im_operation_import_id_foreign` FOREIGN KEY (`operation_import_id`) REFERENCES `operation_import` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `document_marketing_export`
--
ALTER TABLE `document_marketing_export`
  ADD CONSTRAINT `document_marketing_export_marketing_export_id_foreign` FOREIGN KEY (`marketing_export_id`) REFERENCES `marketing_export` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `document_marketing_import`
--
ALTER TABLE `document_marketing_import`
  ADD CONSTRAINT `document_marketing_import_marketing_import_id_foreign` FOREIGN KEY (`marketing_import_id`) REFERENCES `marketing_import` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `document_progress_op_ex`
--
ALTER TABLE `document_progress_op_ex`
  ADD CONSTRAINT `document_progress_op_ex_progress_operation_export_id_foreign` FOREIGN KEY (`progress_operation_export_id`) REFERENCES `progress_operation_export` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `document_progress_op_im`
--
ALTER TABLE `document_progress_op_im`
  ADD CONSTRAINT `document_progress_op_im_progress_operation_import_id_foreign` FOREIGN KEY (`progress_operation_import_id`) REFERENCES `progress_operation_import` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `group_quotation_m_ex`
--
ALTER TABLE `group_quotation_m_ex`
  ADD CONSTRAINT `group_quotation_m_ex_quotation_m_ex_id_foreign` FOREIGN KEY (`quotation_m_ex_id`) REFERENCES `quotation_marketing_export` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `group_quotation_m_im`
--
ALTER TABLE `group_quotation_m_im`
  ADD CONSTRAINT `group_quotation_m_im_quotation_m_im_id_foreign` FOREIGN KEY (`quotation_m_im_id`) REFERENCES `quotation_marketing_import` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoice_detail`
--
ALTER TABLE `invoice_detail`
  ADD CONSTRAINT `invoice_detail_head_id_foreign` FOREIGN KEY (`head_id`) REFERENCES `invoice_head` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `invoice_detail_tax_id_foreign` FOREIGN KEY (`tax_id`) REFERENCES `master_tax` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `invoice_head`
--
ALTER TABLE `invoice_head`
  ADD CONSTRAINT `invoice_head_contact_id_foreign` FOREIGN KEY (`contact_id`) REFERENCES `master_contact` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `invoice_head_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `master_currency` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `invoice_head_sales_id_foreign` FOREIGN KEY (`sales_id`) REFERENCES `sales_order_head` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `invoice_head_term_payment_foreign` FOREIGN KEY (`term_payment`) REFERENCES `master_term_of_payment` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `item_group_quotation_m_ex`
--
ALTER TABLE `item_group_quotation_m_ex`
  ADD CONSTRAINT `item_group_quotation_m_ex_group_quotation_m_ex_id_foreign` FOREIGN KEY (`group_quotation_m_ex_id`) REFERENCES `group_quotation_m_ex` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `item_group_quotation_m_im`
--
ALTER TABLE `item_group_quotation_m_im`
  ADD CONSTRAINT `item_group_quotation_m_im_group_quotation_m_im_id_foreign` FOREIGN KEY (`group_quotation_m_im_id`) REFERENCES `group_quotation_m_im` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kas_in_detail`
--
ALTER TABLE `kas_in_detail`
  ADD CONSTRAINT `kas_in_detail_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `master_account` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `kas_in_detail_head_id_foreign` FOREIGN KEY (`head_id`) REFERENCES `kas_in_head` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `kas_in_head`
--
ALTER TABLE `kas_in_head`
  ADD CONSTRAINT `kas_in_head_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `master_account` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `kas_in_head_contact_id_foreign` FOREIGN KEY (`contact_id`) REFERENCES `master_contact` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `kas_in_head_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `master_currency` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `kas_in_head_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `no_transaction_kas_in` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `kas_out_detail`
--
ALTER TABLE `kas_out_detail`
  ADD CONSTRAINT `kas_out_detail_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `master_account` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `kas_out_detail_head_id_foreign` FOREIGN KEY (`head_id`) REFERENCES `kas_out_head` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `kas_out_head`
--
ALTER TABLE `kas_out_head`
  ADD CONSTRAINT `kas_out_head_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `master_account` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `kas_out_head_contact_id_foreign` FOREIGN KEY (`contact_id`) REFERENCES `master_contact` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `kas_out_head_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `master_currency` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `kas_out_head_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `no_transaction_kas_out` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `marketing_export`
--
ALTER TABLE `marketing_export`
  ADD CONSTRAINT `marketing_export_contact_id_foreign` FOREIGN KEY (`contact_id`) REFERENCES `master_contact` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `marketing_import`
--
ALTER TABLE `marketing_import`
  ADD CONSTRAINT `marketing_import_contact_id_foreign` FOREIGN KEY (`contact_id`) REFERENCES `master_contact` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `master_account`
--
ALTER TABLE `master_account`
  ADD CONSTRAINT `master_account_account_type_id_foreign` FOREIGN KEY (`account_type_id`) REFERENCES `account_type` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `master_account_master_currency_id_foreign` FOREIGN KEY (`master_currency_id`) REFERENCES `master_currency` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `master_bank_currency`
--
ALTER TABLE `master_bank_currency`
  ADD CONSTRAINT `master_bank_currency_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `master_currency` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `master_exchange`
--
ALTER TABLE `master_exchange`
  ADD CONSTRAINT `master_exchange_from_currency_id_foreign` FOREIGN KEY (`from_currency_id`) REFERENCES `master_currency` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `master_exchange_to_currency_id_foreign` FOREIGN KEY (`to_currency_id`) REFERENCES `master_currency` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `operation_export`
--
ALTER TABLE `operation_export`
  ADD CONSTRAINT `operation_export_marketing_export_id_foreign` FOREIGN KEY (`marketing_export_id`) REFERENCES `marketing_export` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `operation_import`
--
ALTER TABLE `operation_import`
  ADD CONSTRAINT `operation_import_marketing_import_id_foreign` FOREIGN KEY (`marketing_import_id`) REFERENCES `marketing_import` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `payment_detail`
--
ALTER TABLE `payment_detail`
  ADD CONSTRAINT `payment_detail_currency_via_id_foreign` FOREIGN KEY (`currency_via_id`) REFERENCES `master_exchange` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `payment_detail_head_id_foreign` FOREIGN KEY (`head_id`) REFERENCES `payment_head` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `payment_detail_payable_id_foreign` FOREIGN KEY (`payable_id`) REFERENCES `account_payable_head` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `payment_head`
--
ALTER TABLE `payment_head`
  ADD CONSTRAINT `payment_head_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `master_account` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `payment_head_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `master_currency` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `payment_head_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `master_contact` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `payment_head_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `master_contact` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `progress_operation_export`
--
ALTER TABLE `progress_operation_export`
  ADD CONSTRAINT `progress_operation_export_operation_export_id_foreign` FOREIGN KEY (`operation_export_id`) REFERENCES `operation_export` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `progress_operation_import`
--
ALTER TABLE `progress_operation_import`
  ADD CONSTRAINT `progress_operation_import_operation_import_id_foreign` FOREIGN KEY (`operation_import_id`) REFERENCES `operation_import` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `quotation_marketing_export`
--
ALTER TABLE `quotation_marketing_export`
  ADD CONSTRAINT `quotation_marketing_export_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `master_currency` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `quotation_marketing_export_marketing_export_id_foreign` FOREIGN KEY (`marketing_export_id`) REFERENCES `marketing_export` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `quotation_marketing_import`
--
ALTER TABLE `quotation_marketing_import`
  ADD CONSTRAINT `quotation_marketing_import_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `master_currency` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `quotation_marketing_import_marketing_import_id_foreign` FOREIGN KEY (`marketing_import_id`) REFERENCES `marketing_import` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `receive_payment_detail`
--
ALTER TABLE `receive_payment_detail`
  ADD CONSTRAINT `receive_payment_detail_currency_via_id_foreign` FOREIGN KEY (`currency_via_id`) REFERENCES `master_exchange` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `receive_payment_detail_head_id_foreign` FOREIGN KEY (`head_id`) REFERENCES `receive_payment_head` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `receive_payment_detail_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoice_head` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `receive_payment_head`
--
ALTER TABLE `receive_payment_head`
  ADD CONSTRAINT `receive_payment_head_account_id_foreign` FOREIGN KEY (`account_id`) REFERENCES `master_account` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `receive_payment_head_contact_id_foreign` FOREIGN KEY (`contact_id`) REFERENCES `master_contact` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `receive_payment_head_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `master_currency` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales_order_detail`
--
ALTER TABLE `sales_order_detail`
  ADD CONSTRAINT `sales_order_detail_head_id_foreign` FOREIGN KEY (`head_id`) REFERENCES `sales_order_head` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `sales_order_head`
--
ALTER TABLE `sales_order_head`
  ADD CONSTRAINT `sales_order_head_contact_id_foreign` FOREIGN KEY (`contact_id`) REFERENCES `master_contact` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `sales_order_head_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `master_currency` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `sao`
--
ALTER TABLE `sao`
  ADD CONSTRAINT `sao_contact_id_foreign` FOREIGN KEY (`contact_id`) REFERENCES `master_contact` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sao_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `master_currency` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sao_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoice_head` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sao_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `account_payable_head` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sao_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `master_contact` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `term_payment_master_contact`
--
ALTER TABLE `term_payment_master_contact`
  ADD CONSTRAINT `term_payment_master_contact_contact_id_foreign` FOREIGN KEY (`contact_id`) REFERENCES `master_contact` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `term_payment_master_contact_term_payment_id_foreign` FOREIGN KEY (`term_payment_id`) REFERENCES `master_term_of_payment` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `vendor_operation_export`
--
ALTER TABLE `vendor_operation_export`
  ADD CONSTRAINT `vendor_operation_export_operation_export_id_foreign` FOREIGN KEY (`operation_export_id`) REFERENCES `operation_export` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `vendor_operation_import`
--
ALTER TABLE `vendor_operation_import`
  ADD CONSTRAINT `vendor_operation_import_operation_import_id_foreign` FOREIGN KEY (`operation_import_id`) REFERENCES `operation_import` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
