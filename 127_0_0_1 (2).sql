-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 07, 2025 at 07:54 PM
-- Server version: 8.0.36-28
-- PHP Version: 8.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventory`
--
CREATE DATABASE IF NOT EXISTS `inventory` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `inventory`;

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` bigint UNSIGNED NOT NULL,
  `log_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint UNSIGNED DEFAULT NULL,
  `causer_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` bigint UNSIGNED DEFAULT NULL,
  `properties` json DEFAULT NULL,
  `batch_uuid` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `event`, `subject_id`, `causer_type`, `causer_id`, `properties`, `batch_uuid`, `created_at`, `updated_at`) VALUES
(1, 'default', 'updated', 'App\\Models\\User', 'updated', 3, 'App\\Models\\User', 3, '{\"old\": {\"name\": \"Inventory User\"}, \"attributes\": {\"name\": \"Inventory Use\"}}', NULL, '2025-06-03 11:44:51', '2025-06-03 11:44:51'),
(2, 'default', 'Purchase Order #PO-2025-0087 was deleted by Admin User', NULL, NULL, NULL, 'App\\Models\\User', 1, '{\"po_id\": 87}', NULL, '2025-07-10 12:31:41', '2025-07-10 12:31:41'),
(3, 'default', 'Purchase Order #PO-2025-0087 was deleted by Admin User', NULL, NULL, NULL, 'App\\Models\\User', 1, '{\"po_id\": 88}', NULL, '2025-07-10 12:35:40', '2025-07-10 12:35:40'),
(4, 'default', 'Purchase Order #PO-2025-0090 was deleted by Admin User', NULL, NULL, NULL, 'App\\Models\\User', 1, '{\"po_id\": 90}', NULL, '2025-07-11 08:59:10', '2025-07-11 08:59:10'),
(5, 'default', 'Purchase Order #PO-2025-0090 was deleted by Admin User', NULL, NULL, NULL, 'App\\Models\\User', 1, '{\"po_id\": 91}', NULL, '2025-07-11 09:09:33', '2025-07-11 09:09:33'),
(6, 'default', 'Purchase Order #PO-2025-0090 was deleted by Admin User', NULL, NULL, NULL, 'App\\Models\\User', 1, '{\"po_id\": 93}', NULL, '2025-07-11 09:13:08', '2025-07-11 09:13:08'),
(7, 'default', 'Purchase Order #PO-2025-0090 was deleted by Admin User', NULL, NULL, NULL, 'App\\Models\\User', 1, '{\"po_id\": 100}', NULL, '2025-07-11 09:23:48', '2025-07-11 09:23:48'),
(8, 'default', 'Purchase Order #PO-2025-0090 was deleted by Admin User', NULL, NULL, NULL, 'App\\Models\\User', 1, '{\"po_id\": 101}', NULL, '2025-07-12 12:37:24', '2025-07-12 12:37:24'),
(9, 'default', 'Purchase Order #PO-2025-0086 was deleted by Admin User', NULL, NULL, NULL, 'App\\Models\\User', 1, '{\"po_id\": 86}', NULL, '2025-07-12 13:22:51', '2025-07-12 13:22:51'),
(10, 'default', 'Purchase Order #PO-2025-0103 was deleted by Admin User', NULL, NULL, NULL, 'App\\Models\\User', 1, '{\"po_id\": 103}', NULL, '2025-07-13 19:12:23', '2025-07-13 19:12:23'),
(11, 'default', 'Purchase Order #PO-2025-0090 was deleted by Admin User', NULL, NULL, NULL, 'App\\Models\\User', 1, '{\"po_id\": 102}', NULL, '2025-07-13 19:38:02', '2025-07-13 19:38:02'),
(12, 'default', 'Purchase Order #PO-2025-0090 was deleted by Admin User', NULL, NULL, NULL, 'App\\Models\\User', 1, '{\"po_id\": 104}', NULL, '2025-07-13 19:39:36', '2025-07-13 19:39:36'),
(13, 'default', 'Purchase Order #PO-2025-0090 was deleted by Admin User', NULL, NULL, NULL, 'App\\Models\\User', 1, '{\"po_id\": 105}', NULL, '2025-07-13 19:42:16', '2025-07-13 19:42:16'),
(14, 'default', 'Purchase Order #PO-2025-0107 was deleted by Admin User', NULL, NULL, NULL, 'App\\Models\\User', 1, '{\"po_id\": 107}', NULL, '2025-07-13 19:46:28', '2025-07-13 19:46:28'),
(15, 'default', 'Purchase Order #PO-2025-0109 was deleted by Admin User', NULL, NULL, NULL, 'App\\Models\\User', 1, '{\"po_id\": 109}', NULL, '2025-07-14 07:25:26', '2025-07-14 07:25:26'),
(16, 'default', 'Purchase Order #PO-2025-0110 was deleted by Admin User', NULL, NULL, NULL, 'App\\Models\\User', 1, '{\"po_id\": 110}', NULL, '2025-07-14 07:25:57', '2025-07-14 07:25:57'),
(18, 'default', 'Purchase Order #PO-2025-0087 was deleted by Admin User', NULL, NULL, NULL, 'App\\Models\\User', 1, '{\"po_id\": 89}', NULL, '2025-07-14 07:31:40', '2025-07-14 07:31:40'),
(19, 'default', 'Purchase Order #PO-2025-0107 was deleted by Admin User', NULL, NULL, NULL, 'App\\Models\\User', 1, '{\"po_id\": 108}', NULL, '2025-07-14 07:41:58', '2025-07-14 07:41:58'),
(20, 'default', 'Purchase Order #PO-2025-0106 was deleted by Admin User', NULL, NULL, NULL, 'App\\Models\\User', 1, '{\"po_id\": 106}', NULL, '2025-07-14 08:24:53', '2025-07-14 08:24:53'),
(21, 'default', 'Purchase Order #PO-2025-0113 was deleted by Admin User', NULL, NULL, NULL, 'App\\Models\\User', 1, '{\"po_id\": 113}', NULL, '2025-07-14 12:11:22', '2025-07-14 12:11:22'),
(22, 'default', 'Purchase Order #PO-2025-0113 was deleted by Admin User', NULL, NULL, NULL, 'App\\Models\\User', 1, '{\"po_id\": 114}', NULL, '2025-07-14 13:16:37', '2025-07-14 13:16:37'),
(23, 'default', 'Purchase Order #PO-2025-0117 was deleted by Admin User', NULL, NULL, NULL, 'App\\Models\\User', 1, '{\"po_id\": 117}', NULL, '2025-07-14 13:19:21', '2025-07-14 13:19:21'),
(24, 'default', 'Purchase Order #PO-2025-0119 was deleted by Admin User', NULL, NULL, NULL, 'App\\Models\\User', 1, '{\"po_id\": 119}', NULL, '2025-07-14 13:21:29', '2025-07-14 13:21:29'),
(25, 'default', 'Purchase Order #PO-2025-0118 was deleted by Admin User', NULL, NULL, NULL, 'App\\Models\\User', 1, '{\"po_id\": 118}', NULL, '2025-07-14 13:25:44', '2025-07-14 13:25:44'),
(27, 'default', 'Purchase Order #PO-2025-0112 was deleted by Admin User', NULL, NULL, NULL, 'App\\Models\\User', 1, '{\"po_id\": 112}', NULL, '2025-07-14 13:29:41', '2025-07-14 13:29:41'),
(28, 'default', 'Purchase Order #PO-2025-0116 was deleted by Admin User', NULL, NULL, NULL, 'App\\Models\\User', 1, '{\"po_id\": 116}', NULL, '2025-07-14 13:30:22', '2025-07-14 13:30:22'),
(29, 'default', 'Purchase Order #PO-2025-0115 was deleted by Admin User', NULL, NULL, NULL, 'App\\Models\\User', 1, '{\"po_id\": 115}', NULL, '2025-07-14 13:33:59', '2025-07-14 13:33:59'),
(30, 'default', 'Purchase Order #PO-2025-0112 was deleted by Admin User', NULL, NULL, NULL, 'App\\Models\\User', 1, '{\"po_id\": 120}', NULL, '2025-07-14 13:35:48', '2025-07-14 13:35:48'),
(31, 'default', 'Purchase Order #PO-2025-0125 was deleted by Purchase User', NULL, NULL, NULL, 'App\\Models\\User', 2, '{\"po_id\": 125}', NULL, '2025-07-22 11:48:58', '2025-07-22 11:48:58');

-- --------------------------------------------------------

--
-- Table structure for table `barcodes`
--

CREATE TABLE `barcodes` (
  `id` int NOT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `generated_by` bigint UNSIGNED DEFAULT NULL,
  `barcode_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch_id` bigint UNSIGNED NOT NULL,
  `purchase_order_id` bigint UNSIGNED DEFAULT NULL,
  `material_id` bigint UNSIGNED DEFAULT NULL,
  `material_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `material_code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supplier_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `weight` decimal(10,2) DEFAULT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `barcode_type` enum('standard','qr','both') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('active','inactive','damaged','expired') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `qr_code_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `storage_location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quality_grade` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `last_scanned_at` timestamp NULL DEFAULT NULL,
  `print_count` int NOT NULL DEFAULT '0',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `scan_count` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barcodes`
--

INSERT INTO `barcodes` (`id`, `created_by`, `generated_by`, `barcode_number`, `batch_id`, `purchase_order_id`, `material_id`, `material_name`, `material_code`, `supplier_name`, `quantity`, `weight`, `unit_price`, `barcode_type`, `status`, `qr_code_data`, `storage_location`, `quality_grade`, `expiry_date`, `last_scanned_at`, `print_count`, `notes`, `created_at`, `updated_at`, `scan_count`) VALUES
(19, 1, NULL, 'BC250703173543NCKF', 68, 80, 5, 'sugar', 'SUG3149', 'Rahul Sharma', 5, NULL, 60.00, 'standard', 'active', '{\"barcode_number\":\"BC250703173543NCKF\",\"material_code\":\"SUG3149\",\"material_name\":\"sugar\",\"batch_number\":\"BATCH-250703-RGDN\",\"supplier_name\":\"Rahul Sharma\",\"expiry_date\":\"2025-07-21\",\"storage_location\":null,\"quality_grade\":\"A\",\"generated_at\":\"2025-07-03T12:05:43.674710Z\"}', NULL, 'A', '2025-07-21', '2025-07-10 09:44:56', 0, NULL, '2025-07-03 17:35:43', '2025-07-10 09:44:56', 1),
(20, 3, NULL, 'BC25071009460623HY', 69, 75, 10, 'Phone A', 'PHO7694', 'test', 10, NULL, 50.00, 'qr', 'active', '{\"barcode_number\":\"BC25071009460623HY\",\"material_code\":\"PHO7694\",\"material_name\":\"Phone A\",\"batch_number\":\"BATCH-250704-GDKK\",\"supplier_name\":\"test\",\"expiry_date\":\"2025-07-30\",\"storage_location\":null,\"quality_grade\":\"C\",\"generated_at\":\"2025-07-10T04:16:06.622348Z\"}', NULL, 'C', '2025-07-30', '2025-07-22 11:37:43', 0, NULL, '2025-07-10 09:46:06', '2025-07-22 11:37:43', 3),
(21, 3, NULL, 'BC250722113908UQQG', 74, 83, 5, 'sugar', 'SUG3149', 'Rahul Sharma', 5, NULL, 60.00, 'standard', 'active', '{\"barcode_number\":\"BC250722113908UQQG\",\"material_code\":\"SUG3149\",\"material_name\":\"sugar\",\"batch_number\":\"BATCH-250704-YVOC\",\"supplier_name\":\"Rahul Sharma\",\"expiry_date\":\"2025-07-31\",\"storage_location\":null,\"quality_grade\":\"A\",\"generated_at\":\"2025-07-22T06:09:08.528295Z\"}', NULL, 'A', '2025-07-31', NULL, 0, NULL, '2025-07-22 11:39:08', '2025-07-22 11:39:08', 0);

-- --------------------------------------------------------

--
-- Table structure for table `barcode_scans`
--

CREATE TABLE `barcode_scans` (
  `id` bigint UNSIGNED NOT NULL,
  `barcode_id` bigint UNSIGNED NOT NULL,
  `scanner_device` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scan_location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scan_type` enum('inventory','dispatch','quality_check','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'inventory',
  `scan_data` json DEFAULT NULL,
  `scanned_by` bigint UNSIGNED DEFAULT NULL,
  `scanned_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` bigint UNSIGNED NOT NULL,
  `state_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `state_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 21, 'Mumbai', NULL, NULL),
(2, 21, 'Pune', NULL, NULL),
(3, 21, 'Nagpur', NULL, NULL),
(4, 21, 'Nashik', NULL, NULL),
(5, 21, 'Aurangabad', NULL, NULL),
(6, 21, 'Solapur', NULL, NULL),
(7, 21, 'Amravati', NULL, NULL),
(8, 21, 'Kolhapur', NULL, NULL),
(9, 21, 'Nanded', NULL, NULL),
(10, 21, 'Sangli', NULL, NULL),
(11, 21, 'Jalgaon', NULL, NULL),
(12, 21, 'Latur', NULL, NULL),
(13, 21, 'Dhule', NULL, NULL),
(14, 21, 'Ahmednagar', NULL, NULL),
(15, 21, 'Chandrapur', NULL, NULL),
(16, 21, 'Parbhani', NULL, NULL),
(17, 21, 'Ichalkaranji', NULL, NULL),
(18, 21, 'Jalna', NULL, NULL),
(19, 21, 'Ambarnath', NULL, NULL),
(20, 21, 'Bhusawal', NULL, NULL),
(21, 21, 'Panvel', NULL, NULL),
(22, 21, 'Beed', NULL, NULL),
(23, 21, 'Satara', NULL, NULL),
(24, 21, 'Yavatmal', NULL, NULL),
(25, 21, 'Achalpur', NULL, NULL),
(26, 21, 'Osmanabad', NULL, NULL),
(27, 21, 'Nandurbar', NULL, NULL),
(28, 21, 'Wardha', NULL, NULL),
(29, 21, 'Gondia', NULL, NULL),
(30, 21, 'Barshi', NULL, NULL),
(31, 21, 'Udgir', NULL, NULL),
(32, 1, 'Port Blair', NULL, NULL),
(33, 1, 'Garacharma', NULL, NULL),
(34, 1, 'Bombooflat', NULL, NULL),
(35, 1, 'Prothrapur', NULL, NULL),
(36, 1, 'Hut Bay', NULL, NULL),
(37, 2, 'Visakhapatnam', NULL, NULL),
(38, 2, 'Vijayawada', NULL, NULL),
(39, 2, 'Guntur', NULL, NULL),
(40, 2, 'Nellore', NULL, NULL),
(41, 2, 'Kurnool', NULL, NULL),
(42, 2, 'Rajahmundry', NULL, NULL),
(43, 2, 'Tirupati', NULL, NULL),
(44, 2, 'Kadapa', NULL, NULL),
(45, 2, 'Anantapur', NULL, NULL),
(46, 2, 'Eluru', NULL, NULL),
(47, 2, 'Ongole', NULL, NULL),
(48, 2, 'Chittoor', NULL, NULL),
(49, 2, 'Machilipatnam', NULL, NULL),
(50, 2, 'Srikakulam', NULL, NULL),
(51, 2, 'Amaravati', NULL, NULL),
(52, 3, 'Itanagar', NULL, NULL),
(53, 3, 'Naharlagun', NULL, NULL),
(54, 3, 'Pasighat', NULL, NULL),
(55, 3, 'Roing', NULL, NULL),
(56, 3, 'Bomdila', NULL, NULL),
(57, 3, 'Tawang', NULL, NULL),
(58, 3, 'Ziro', NULL, NULL),
(59, 3, 'Along', NULL, NULL),
(60, 4, 'Guwahati', NULL, NULL),
(61, 4, 'Dibrugarh', NULL, NULL),
(62, 4, 'Jorhat', NULL, NULL),
(63, 4, 'Nagaon', NULL, NULL),
(64, 4, 'Tinsukia', NULL, NULL),
(65, 4, 'Tezpur', NULL, NULL),
(66, 4, 'Bongaigaon', NULL, NULL),
(67, 4, 'Karimganj', NULL, NULL),
(68, 4, 'Diphu', NULL, NULL),
(69, 4, 'Goalpara', NULL, NULL),
(70, 4, 'Barpeta', NULL, NULL),
(71, 4, 'Silchar', NULL, NULL),
(72, 5, 'Gaya', NULL, NULL),
(73, 5, 'Bhagalpur', NULL, NULL),
(74, 5, 'Muzaffarpur', NULL, NULL),
(75, 5, 'Darbhanga', NULL, NULL),
(76, 5, 'Purnia', NULL, NULL),
(77, 5, 'Arrah', NULL, NULL),
(78, 5, 'Begusarai', NULL, NULL),
(79, 5, 'Katihar', NULL, NULL),
(80, 5, 'Munger', NULL, NULL),
(81, 5, 'Chhapra', NULL, NULL),
(82, 5, 'Samastipur', NULL, NULL),
(83, 5, 'Patna', NULL, NULL),
(84, 6, 'Chandigarh', NULL, NULL),
(85, 7, 'Raipur', NULL, NULL),
(86, 7, 'Bhilai', NULL, NULL),
(87, 7, 'Bilaspur', NULL, NULL),
(88, 7, 'Korba', NULL, NULL),
(89, 7, 'Durg', NULL, NULL),
(90, 7, 'Rajnandgaon', NULL, NULL),
(91, 7, 'Jagdalpur', NULL, NULL),
(92, 7, 'Ambikapur', NULL, NULL),
(93, 7, 'Raigarh', NULL, NULL),
(94, 7, 'Chirmiri', NULL, NULL),
(95, 7, 'Dhamtari', NULL, NULL),
(96, 8, 'Daman', NULL, NULL),
(97, 8, 'Diu', NULL, NULL),
(98, 8, 'Silvassa', NULL, NULL),
(99, 9, 'New Delhi', NULL, NULL),
(100, 9, 'North Delhi', NULL, NULL),
(101, 9, 'South Delhi', NULL, NULL),
(102, 9, 'East Delhi', NULL, NULL),
(103, 9, 'West Delhi', NULL, NULL),
(104, 9, 'Central Delhi', NULL, NULL),
(105, 9, 'Shahdara', NULL, NULL),
(106, 9, 'Dwarka', NULL, NULL),
(107, 10, 'Panaji', NULL, NULL),
(108, 10, 'Margao', NULL, NULL),
(109, 10, 'Vasco da Gama', NULL, NULL),
(110, 10, 'Mapusa', NULL, NULL),
(111, 10, 'Ponda', NULL, NULL),
(112, 10, 'Curchorem', NULL, NULL),
(113, 10, 'Bicholim', NULL, NULL),
(114, 10, 'Canacona', NULL, NULL),
(115, 11, 'Ahmedabad', NULL, NULL),
(116, 11, 'Surat', NULL, NULL),
(117, 11, 'Vadodara', NULL, NULL),
(118, 11, 'Rajkot', NULL, NULL),
(119, 11, 'Bhavnagar', NULL, NULL),
(120, 11, 'Jamnagar', NULL, NULL),
(121, 11, 'Gandhinagar', NULL, NULL),
(122, 11, 'Junagadh', NULL, NULL),
(123, 11, 'Anand', NULL, NULL),
(124, 11, 'Navsari', NULL, NULL),
(125, 11, 'Bharuch', NULL, NULL),
(126, 11, 'Vapi', NULL, NULL),
(127, 12, 'Gurgaon', NULL, NULL),
(128, 12, 'Faridabad', NULL, NULL),
(129, 12, 'Panipat', NULL, NULL),
(130, 12, 'Ambala', NULL, NULL),
(131, 12, 'Yamunanagar', NULL, NULL),
(132, 12, 'Rohtak', NULL, NULL),
(133, 12, 'Hisar', NULL, NULL),
(134, 12, 'Karnal', NULL, NULL),
(135, 12, 'Sonipat', NULL, NULL),
(136, 12, 'Panchkula', NULL, NULL),
(137, 12, 'Sirsa', NULL, NULL),
(138, 12, 'Bhiwani', NULL, NULL),
(139, 13, 'Shimla', NULL, NULL),
(140, 13, 'Manali', NULL, NULL),
(141, 13, 'Dharamshala', NULL, NULL),
(142, 13, 'Solan', NULL, NULL),
(143, 13, 'Mandi', NULL, NULL),
(144, 13, 'Bilaspur', NULL, NULL),
(145, 13, 'Kullu', NULL, NULL),
(146, 13, 'Hamirpur', NULL, NULL),
(147, 13, 'Chamba', NULL, NULL),
(148, 13, 'Una', NULL, NULL),
(149, 13, 'Nahan', NULL, NULL),
(150, 13, 'Palampur', NULL, NULL),
(151, 14, 'Srinagar', NULL, NULL),
(152, 14, 'Jammu', NULL, NULL),
(153, 14, 'Anantnag', NULL, NULL),
(154, 14, 'Baramulla', NULL, NULL),
(155, 14, 'Udhampur', NULL, NULL),
(156, 14, 'Pulwama', NULL, NULL),
(157, 14, 'Kathua', NULL, NULL),
(158, 14, 'Poonch', NULL, NULL),
(159, 14, 'Kupwara', NULL, NULL),
(160, 14, 'Sopore', NULL, NULL),
(161, 14, 'Rajouri', NULL, NULL),
(162, 14, 'Kulgam', NULL, NULL),
(163, 15, 'Ranchi', NULL, NULL),
(164, 15, 'Jamshedpur', NULL, NULL),
(165, 15, 'Dhanbad', NULL, NULL),
(166, 15, 'Bokaro', NULL, NULL),
(167, 15, 'Deoghar', NULL, NULL),
(168, 15, 'Hazaribagh', NULL, NULL),
(169, 15, 'Giridih', NULL, NULL),
(170, 15, 'Ramgarh', NULL, NULL),
(171, 15, 'Chaibasa', NULL, NULL),
(172, 15, 'Dumka', NULL, NULL),
(173, 15, 'Godda', NULL, NULL),
(174, 15, 'Latehar', NULL, NULL),
(175, 16, 'Bengaluru', NULL, NULL),
(176, 16, 'Mysuru', NULL, NULL),
(177, 16, 'Mangalore', NULL, NULL),
(178, 16, 'Hubli', NULL, NULL),
(179, 16, 'Belgaum', NULL, NULL),
(180, 16, 'Davanagere', NULL, NULL),
(181, 16, 'Bellary', NULL, NULL),
(182, 16, 'Shimoga', NULL, NULL),
(183, 16, 'Tumkur', NULL, NULL),
(184, 16, 'Raichur', NULL, NULL),
(185, 16, 'Bidar', NULL, NULL),
(186, 16, 'Udupi', NULL, NULL),
(187, 17, 'Thiruvananthapuram', NULL, NULL),
(188, 17, 'Kochi', NULL, NULL),
(189, 17, 'Kozhikode', NULL, NULL),
(190, 17, 'Thrissur', NULL, NULL),
(191, 17, 'Kannur', NULL, NULL),
(192, 17, 'Kollam', NULL, NULL),
(193, 17, 'Alappuzha', NULL, NULL),
(194, 17, 'Palakkad', NULL, NULL),
(195, 17, 'Kottayam', NULL, NULL),
(196, 17, 'Malappuram', NULL, NULL),
(197, 17, 'Pathanamthitta', NULL, NULL),
(198, 17, 'Idukki', NULL, NULL),
(199, 18, 'Leh', NULL, NULL),
(200, 18, 'Kargil', NULL, NULL),
(201, 19, 'Kavaratti', NULL, NULL),
(202, 19, 'Agatti', NULL, NULL),
(203, 19, 'Amini', NULL, NULL),
(204, 19, 'Andrott', NULL, NULL),
(205, 19, 'Kalpeni', NULL, NULL),
(206, 19, 'Kadmat', NULL, NULL),
(207, 19, 'Minicoy', NULL, NULL),
(208, 19, 'Chetlat', NULL, NULL),
(209, 19, 'Bitra', NULL, NULL),
(210, 19, 'Kiltan', NULL, NULL),
(211, 20, 'Bhopal', NULL, NULL),
(212, 20, 'Indore', NULL, NULL),
(213, 20, 'Jabalpur', NULL, NULL),
(214, 20, 'Gwalior', NULL, NULL),
(215, 20, 'Ujjain', NULL, NULL),
(216, 20, 'Sagar', NULL, NULL),
(217, 20, 'Dewas', NULL, NULL),
(218, 20, 'Satna', NULL, NULL),
(219, 20, 'Ratlam', NULL, NULL),
(220, 20, 'Rewa', NULL, NULL),
(221, 20, 'Katni', NULL, NULL),
(222, 20, 'Singrauli', NULL, NULL),
(223, 20, 'Burhanpur', NULL, NULL),
(224, 20, 'Khandwa', NULL, NULL),
(225, 20, 'Bhind', NULL, NULL),
(226, 20, 'Chhindwara', NULL, NULL),
(227, 20, 'Morena', NULL, NULL),
(228, 20, 'Vidisha', NULL, NULL),
(229, 20, 'Shivpuri', NULL, NULL),
(230, 20, 'Damoh', NULL, NULL),
(231, 20, 'Mandsaur', NULL, NULL),
(232, 20, 'Neemuch', NULL, NULL),
(233, 20, 'Sehore', NULL, NULL),
(234, 20, 'Hoshangabad', NULL, NULL),
(235, 22, 'Imphal', NULL, NULL),
(236, 22, 'Thoubal', NULL, NULL),
(237, 22, 'Bishnupur', NULL, NULL),
(238, 22, 'Churachandpur', NULL, NULL),
(239, 22, 'Kakching', NULL, NULL),
(240, 22, 'Senapati', NULL, NULL),
(241, 22, 'Ukhrul', NULL, NULL),
(242, 22, 'Chandel', NULL, NULL),
(243, 22, 'Tamenglong', NULL, NULL),
(244, 22, 'Kangpokpi', NULL, NULL),
(245, 22, 'Jiribam', NULL, NULL),
(246, 22, 'Noney', NULL, NULL),
(247, 23, 'Shillong', NULL, NULL),
(248, 23, 'Tura', NULL, NULL),
(249, 23, 'Nongpoh', NULL, NULL),
(250, 23, 'Baghmara', NULL, NULL),
(251, 23, 'Williamnagar', NULL, NULL),
(252, 23, 'Jowai', NULL, NULL),
(253, 23, 'Mairang', NULL, NULL),
(254, 23, 'Resubelpara', NULL, NULL),
(255, 23, 'Ampati', NULL, NULL),
(256, 23, 'Mawkyrwat', NULL, NULL),
(257, 23, 'Khliehriat', NULL, NULL),
(258, 23, 'Amlarem', NULL, NULL),
(259, 24, 'Aizawl', NULL, NULL),
(260, 24, 'Lunglei', NULL, NULL),
(261, 24, 'Champhai', NULL, NULL),
(262, 24, 'Serchhip', NULL, NULL),
(263, 24, 'Kolasib', NULL, NULL),
(264, 24, 'Saiha', NULL, NULL),
(265, 24, 'Lawngtlai', NULL, NULL),
(266, 24, 'Mamit', NULL, NULL),
(267, 24, 'Saitual', NULL, NULL),
(268, 24, 'Hnahthial', NULL, NULL),
(269, 24, 'Khawzawl', NULL, NULL),
(270, 24, 'North Vanlaiphai', NULL, NULL),
(271, 25, 'Kohima', NULL, NULL),
(272, 25, 'Dimapur', NULL, NULL),
(273, 25, 'Mokokchung', NULL, NULL),
(274, 25, 'Tuensang', NULL, NULL),
(275, 25, 'Wokha', NULL, NULL),
(276, 25, 'Zunheboto', NULL, NULL),
(277, 25, 'Phek', NULL, NULL),
(278, 25, 'Mon', NULL, NULL),
(279, 25, 'Peren', NULL, NULL),
(280, 25, 'Kiphire', NULL, NULL),
(281, 25, 'Longleng', NULL, NULL),
(282, 25, 'Noklak', NULL, NULL),
(283, 26, 'Bhubaneswar', NULL, NULL),
(284, 26, 'Cuttack', NULL, NULL),
(285, 26, 'Rourkela', NULL, NULL),
(286, 26, 'Berhampur', NULL, NULL),
(287, 26, 'Sambalpur', NULL, NULL),
(288, 26, 'Balasore', NULL, NULL),
(289, 26, 'Puri', NULL, NULL),
(290, 26, 'Bhadrak', NULL, NULL),
(291, 26, 'Baripada', NULL, NULL),
(292, 26, 'Jharsuguda', NULL, NULL),
(293, 26, 'Angul', NULL, NULL),
(294, 26, 'Dhenkanal', NULL, NULL),
(295, 27, 'Puducherry', NULL, NULL),
(296, 27, 'Karaikal', NULL, NULL),
(297, 27, 'Mahe', NULL, NULL),
(298, 27, 'Yanam', NULL, NULL),
(299, 28, 'Amritsar', NULL, NULL),
(300, 28, 'Ludhiana', NULL, NULL),
(301, 28, 'Jalandhar', NULL, NULL),
(302, 28, 'Patiala', NULL, NULL),
(303, 28, 'Bathinda', NULL, NULL),
(304, 28, 'Hoshiarpur', NULL, NULL),
(305, 28, 'Mohali', NULL, NULL),
(306, 28, 'Pathankot', NULL, NULL),
(307, 28, 'Moga', NULL, NULL),
(308, 28, 'Phagwara', NULL, NULL),
(309, 28, 'Barnala', NULL, NULL),
(310, 28, 'Sangrur', NULL, NULL),
(311, 29, 'Jaipur', NULL, NULL),
(312, 29, 'Jodhpur', NULL, NULL),
(313, 29, 'Udaipur', NULL, NULL),
(314, 29, 'Kota', NULL, NULL),
(315, 29, 'Bikaner', NULL, NULL),
(316, 29, 'Ajmer', NULL, NULL),
(317, 29, 'Bhilwara', NULL, NULL),
(318, 29, 'Alwar', NULL, NULL),
(319, 29, 'Sikar', NULL, NULL),
(320, 29, 'Pali', NULL, NULL),
(321, 29, 'Tonk', NULL, NULL),
(322, 29, 'Chittorgarh', NULL, NULL),
(323, 30, 'Gangtok', NULL, NULL),
(324, 30, 'Gyalshing', NULL, NULL),
(325, 30, 'Namchi', NULL, NULL),
(326, 30, 'Mangan', NULL, NULL),
(327, 31, 'Chennai', NULL, NULL),
(328, 31, 'Coimbatore', NULL, NULL),
(329, 31, 'Madurai', NULL, NULL),
(330, 31, 'Tiruchirappalli', NULL, NULL),
(331, 31, 'Salem', NULL, NULL),
(332, 31, 'Tirunelveli', NULL, NULL),
(333, 31, 'Vellore', NULL, NULL),
(334, 31, 'Erode', NULL, NULL),
(335, 31, 'Thoothukudi', NULL, NULL),
(336, 31, 'Dindigul', NULL, NULL),
(337, 31, 'Thanjavur', NULL, NULL),
(338, 31, 'Kanchipuram', NULL, NULL),
(339, 32, 'Hyderabad', NULL, NULL),
(340, 32, 'Warangal', NULL, NULL),
(341, 32, 'Nizamabad', NULL, NULL),
(342, 32, 'Karimnagar', NULL, NULL),
(343, 32, 'Khammam', NULL, NULL),
(344, 32, 'Ramagundam', NULL, NULL),
(345, 32, 'Mahbubnagar', NULL, NULL),
(346, 32, 'Nalgonda', NULL, NULL),
(347, 32, 'Adilabad', NULL, NULL),
(348, 32, 'Siddipet', NULL, NULL),
(349, 32, 'Miryalaguda', NULL, NULL),
(350, 32, 'Jagtial', NULL, NULL),
(351, 33, 'Agartala', NULL, NULL),
(352, 33, 'Udaipur', NULL, NULL),
(353, 33, 'Dharmanagar', NULL, NULL),
(354, 33, 'Kailasahar', NULL, NULL),
(355, 33, 'Belonia', NULL, NULL),
(356, 33, 'Khowai', NULL, NULL),
(357, 33, 'Ambassa', NULL, NULL),
(358, 33, 'Sonamura', NULL, NULL),
(359, 33, 'Bishalgarh', NULL, NULL),
(360, 33, 'Santirbazar', NULL, NULL),
(361, 33, 'Amarpur', NULL, NULL),
(362, 33, 'Sabroom', NULL, NULL),
(363, 34, 'Lucknow', NULL, NULL),
(364, 34, 'Kanpur', NULL, NULL),
(365, 34, 'Ghaziabad', NULL, NULL),
(366, 34, 'Agra', NULL, NULL),
(367, 34, 'Varanasi', NULL, NULL),
(368, 34, 'Meerut', NULL, NULL),
(369, 34, 'Prayagraj (Allahabad)', NULL, NULL),
(370, 34, 'Bareilly', NULL, NULL),
(371, 34, 'Aligarh', NULL, NULL),
(372, 34, 'Moradabad', NULL, NULL),
(373, 34, 'Saharanpur', NULL, NULL),
(374, 34, 'Gorakhpur', NULL, NULL),
(375, 35, 'Dehradun', NULL, NULL),
(376, 35, 'Haridwar', NULL, NULL),
(377, 35, 'Roorkee', NULL, NULL),
(378, 35, 'Haldwani', NULL, NULL),
(379, 35, 'Rudrapur', NULL, NULL),
(380, 35, 'Nainital', NULL, NULL),
(381, 35, 'Pithoragarh', NULL, NULL),
(382, 35, 'Rishikesh', NULL, NULL),
(383, 35, 'Kotdwar', NULL, NULL),
(384, 35, 'Kashipur', NULL, NULL),
(385, 35, 'Tehri', NULL, NULL),
(386, 35, 'Almora', NULL, NULL),
(387, 36, 'Kolkata', NULL, NULL),
(388, 36, 'Howrah', NULL, NULL),
(389, 36, 'Durgapur', NULL, NULL),
(390, 36, 'Asansol', NULL, NULL),
(391, 36, 'Siliguri', NULL, NULL),
(392, 36, 'Maheshtala', NULL, NULL),
(393, 36, 'Rajpur Sonarpur', NULL, NULL),
(394, 36, 'South Dumdum', NULL, NULL),
(395, 36, 'Bidhannagar', NULL, NULL),
(396, 36, 'Kamarhati', NULL, NULL),
(397, 36, 'Bally', NULL, NULL),
(398, 36, 'Baranagar', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_batches`
--

CREATE TABLE `inventory_batches` (
  `id` bigint UNSIGNED NOT NULL,
  `batch_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier_batch_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `purchase_order_id` bigint UNSIGNED NOT NULL,
  `material_id` bigint UNSIGNED NOT NULL,
  `warehouse_id` bigint UNSIGNED DEFAULT NULL,
  `received_weight` decimal(10,2) DEFAULT NULL,
  `received_quantity` int NOT NULL,
  `current_weight` decimal(10,2) DEFAULT NULL,
  `current_quantity` int NOT NULL,
  `remaining_quantity` decimal(10,2) DEFAULT NULL,
  `unit_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_value` decimal(15,2) NOT NULL DEFAULT '0.00',
  `quality_grade` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `storage_location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `received_date` date NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `status` enum('active','expired','damaged','exhausted') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory_batches`
--

INSERT INTO `inventory_batches` (`id`, `batch_number`, `supplier_batch_number`, `purchase_order_id`, `material_id`, `warehouse_id`, `received_weight`, `received_quantity`, `current_weight`, `current_quantity`, `remaining_quantity`, `unit_price`, `total_value`, `quality_grade`, `storage_location`, `received_date`, `expiry_date`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(79, 'BATCH-250722-R4UC', NULL, 111, 12, 2, NULL, 30, NULL, 30, 30.00, 100.00, 0.00, 'Premium', NULL, '2025-07-22', '2025-08-08', 'active', NULL, '2025-07-22 16:57:41', '2025-07-22 16:57:41'),
(80, 'BATCH-250722-RIZG', NULL, 126, 9, 8, NULL, 10, NULL, 10, 10.00, 40.00, 0.00, 'A', NULL, '2025-07-22', '2025-07-31', 'active', NULL, '2025-07-22 17:03:26', '2025-07-22 17:03:26'),
(81, 'BATCH-250722-FRLC', NULL, 126, 7, 3, NULL, 10, NULL, 10, 10.00, 80.00, 0.00, 'A', NULL, '2025-07-22', '2025-08-01', 'active', NULL, '2025-07-22 17:04:37', '2025-07-22 17:04:37'),
(82, 'BATCH-250723-9VOV', NULL, 75, 10, 3, NULL, 50, NULL, 50, 50.00, 50.00, 0.00, 'A', NULL, '2025-07-23', '2025-07-31', 'active', NULL, '2025-07-23 15:07:27', '2025-07-23 15:07:27'),
(83, 'BATCH-250723-QOJV', NULL, 78, 5, 2, NULL, 10, NULL, 10, 10.00, 60.00, 0.00, 'A', NULL, '2025-07-23', '2025-08-05', 'active', NULL, '2025-07-23 15:08:11', '2025-07-23 15:08:11');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_items`
--

CREATE TABLE `inventory_items` (
  `id` bigint UNSIGNED NOT NULL,
  `material_id` bigint UNSIGNED NOT NULL,
  `batch_id` bigint UNSIGNED DEFAULT NULL,
  `quantity` decimal(10,3) NOT NULL DEFAULT '0.000',
  `weight` decimal(10,3) DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory_transactions`
--

CREATE TABLE `inventory_transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `transaction_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch_id` bigint UNSIGNED NOT NULL,
  `type` enum('intake','dispatch','return','damage','adjustment') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `weight` decimal(8,3) NOT NULL,
  `quantity` int NOT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `total_value` decimal(15,2) DEFAULT NULL,
  `reference_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `metadata` json DEFAULT NULL,
  `transaction_date` timestamp NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory_transactions`
--

INSERT INTO `inventory_transactions` (`id`, `transaction_id`, `batch_id`, `type`, `weight`, `quantity`, `unit_price`, `total_value`, `reference_number`, `reason`, `metadata`, `transaction_date`, `notes`, `created_at`, `updated_at`) VALUES
(57, 'TXN20250611000002', 52, 'intake', 20.000, 50, 520.00, 26000.00, 'BATCH-20250611-172702', NULL, NULL, '2025-06-11 00:00:00', NULL, '2025-06-11 17:28:41', '2025-06-11 17:28:41'),
(61, 'TXN-804225d4-d482-4ffd-9156-3191261d169a', 57, 'intake', 50.000, 10, 8.00, 80.00, 'BATCH-20250612-125430', NULL, NULL, '2025-06-12 00:00:00', NULL, '2025-06-12 12:55:21', '2025-06-12 12:55:21'),
(63, 'TXN-3137906f-00c1-409d-b86a-147cb2e4375d', 61, 'intake', 40.000, 40, 30.00, 1200.00, 'BATCH-250625-FEMA', NULL, NULL, '2025-06-25 00:00:00', 'Initial batch creation - ', '2025-06-25 15:44:48', '2025-06-25 15:44:48'),
(64, 'TXN-8708afe7-f139-4bd5-8336-29010389374a', 62, 'intake', 10.000, 10, 30.00, 300.00, 'BATCH-250625-UXR6', NULL, NULL, '2025-06-25 00:00:00', 'Initial batch creation - ', '2025-06-25 15:46:46', '2025-06-25 15:46:46'),
(65, 'TXN-6ecf21c8-83db-49ae-8199-f3ddc032f23a', 63, 'intake', 10.000, 10, 50.00, 500.00, 'BATCH-250626-DQ6I', NULL, NULL, '2025-06-26 00:00:00', 'Initial batch creation - two sent away due to fault', '2025-06-26 23:29:22', '2025-06-26 23:29:22'),
(66, 'TXN-07586898-6647-4e9b-94ec-d4524e7a7a9f', 64, 'intake', 40.000, 40, 50.00, 2000.00, 'BATCH-250703-7RSD', NULL, NULL, '2025-07-03 00:00:00', 'Initial batch creation - xcv', '2025-07-03 15:45:05', '2025-07-03 15:45:05'),
(67, 'TXN-2ee49164-b71f-439f-8cae-ce84b2b8aca5', 65, 'intake', 10.000, 10, 50.00, 500.00, 'BATCH-250703-OKZ0', NULL, NULL, '2025-07-03 00:00:00', 'Initial batch creation - ', '2025-07-03 16:14:25', '2025-07-03 16:14:25'),
(68, 'TXN-558972fd-5570-4468-9529-4abf19b5e206', 66, 'intake', 40.000, 40, 50.00, 2000.00, 'BATCH-250703-OFK3', NULL, NULL, '2025-07-03 00:00:00', 'Initial batch creation - ', '2025-07-03 16:18:38', '2025-07-03 16:18:38'),
(69, 'TXN-f0529beb-5ee3-4daa-8e2a-1c981a8cc928', 67, 'intake', 30.000, 30, 50.00, 1500.00, 'BATCH-250703-RFOT', NULL, NULL, '2025-07-03 00:00:00', 'Initial batch creation - ', '2025-07-03 16:24:18', '2025-07-03 16:24:18'),
(70, 'TXN-3df3e628-b2fd-4957-bc89-3261793e63e8', 68, 'intake', 5.000, 5, 60.00, 300.00, 'BATCH-250703-RGDN', NULL, NULL, '2025-07-03 00:00:00', 'Initial batch creation - ', '2025-07-03 16:29:48', '2025-07-03 16:29:48'),
(71, 'TXN-a742b89e-0d35-4e73-bcf6-79b2e31a03ec', 69, 'intake', 10.000, 10, 50.00, 500.00, 'BATCH-250704-GDKK', NULL, NULL, '2025-07-04 00:00:00', 'Initial batch creation - ', '2025-07-04 14:54:02', '2025-07-04 14:54:02'),
(72, 'TXN-a68701f4-4446-41f8-8c6f-33c8ea284f59', 74, 'intake', 5.000, 5, 60.00, 300.00, 'BATCH-250704-YVOC', NULL, NULL, '2025-07-04 00:00:00', 'Initial batch creation - ', '2025-07-04 16:09:33', '2025-07-04 16:09:33'),
(73, 'TXN-5993aa19-043f-48a8-9ca4-98a0d00e493e', 75, 'intake', 10.000, 10, 50.00, 500.00, 'BATCH-250705-2E8Q', NULL, NULL, '2025-07-05 00:00:00', 'Initial batch creation - ', '2025-07-05 17:03:03', '2025-07-05 17:03:03'),
(74, 'TXN-e3488c6e-e8e3-4c6b-aa12-51685d65f1b4', 77, 'intake', 5.000, 5, 60.00, 300.00, 'BATCH-250710-5DBG', NULL, NULL, '2025-07-10 00:00:00', 'Initial batch creation - ', '2025-07-10 10:53:42', '2025-07-10 10:53:42'),
(75, 'TXN-f60208d3-86a5-490c-8ed7-bde2f3c6a3b8', 79, 'intake', 30.000, 30, 100.00, 3000.00, 'BATCH-250722-R4UC', NULL, NULL, '2025-07-22 00:00:00', 'Initial batch creation - ', '2025-07-22 16:57:41', '2025-07-22 16:57:41'),
(76, 'TXN-b3a888c1-4835-4886-9eeb-85ec2463f581', 80, 'intake', 10.000, 10, 40.00, 400.00, 'BATCH-250722-RIZG', NULL, NULL, '2025-07-22 00:00:00', 'Initial batch creation - ', '2025-07-22 17:03:26', '2025-07-22 17:03:26'),
(77, 'TXN-755a2948-89a8-40a1-b1f9-49c08aa04bdf', 81, 'intake', 10.000, 10, 80.00, 800.00, 'BATCH-250722-FRLC', NULL, NULL, '2025-07-22 00:00:00', 'Initial batch creation - ', '2025-07-22 17:04:37', '2025-07-22 17:04:37'),
(78, 'TXN-8940ea51-0841-4571-928d-9dc761618856', 82, 'intake', 50.000, 50, 50.00, 2500.00, 'BATCH-250723-9VOV', NULL, NULL, '2025-07-23 00:00:00', 'Initial batch creation - ', '2025-07-23 15:07:27', '2025-07-23 15:07:27'),
(79, 'TXN-2c30cbfb-f7af-4e34-ae91-94103431a524', 83, 'intake', 10.000, 10, 60.00, 600.00, 'BATCH-250723-QOJV', NULL, NULL, '2025-07-23 00:00:00', 'Initial batch creation - ', '2025-07-23 15:08:11', '2025-07-23 15:08:11');

-- --------------------------------------------------------

--
-- Table structure for table `materials`
--

CREATE TABLE `materials` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `barcode` varchar(13) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `unit` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `length` decimal(10,2) DEFAULT NULL,
  `width` decimal(10,2) DEFAULT NULL,
  `height` decimal(10,2) DEFAULT NULL,
  `diameter` decimal(8,2) DEFAULT NULL,
  `weight` decimal(10,2) DEFAULT NULL,
  `volume` decimal(10,2) DEFAULT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `gst_rate` decimal(5,2) NOT NULL DEFAULT '18.00',
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `dimensions` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `materials`
--

INSERT INTO `materials` (`id`, `vendor_id`, `name`, `code`, `sku`, `barcode`, `description`, `unit`, `length`, `width`, `height`, `diameter`, `weight`, `volume`, `unit_price`, `gst_rate`, `category`, `is_available`, `created_at`, `updated_at`, `dimensions`) VALUES
(5, NULL, 'sugar', 'SUG3149', 'RAWSUG001', '2813387403726', 'daily needs', 'kg', NULL, NULL, NULL, NULL, NULL, NULL, 60.00, 5.00, 'raw material', 1, '2025-06-25 08:42:21', '2025-06-25 08:42:21', '{\"width\": 28, \"height\": 30, \"length\": 10}'),
(7, NULL, 'rava', 'RAV2844', 'GENRAV001', '2352378357261', NULL, 'pack', NULL, NULL, NULL, NULL, NULL, NULL, 30.00, 18.00, 'material', 1, '2025-06-25 12:12:50', '2025-06-26 12:29:52', '{\"width\": 28, \"height\": 50, \"length\": 10}'),
(8, NULL, 'test', 'TES8580', 'CRETES001', '2467778195914', 'This is test product , createied by PL for testing ,', 'bag', NULL, NULL, NULL, NULL, NULL, NULL, 1000.00, 25.00, 'Create a dynamic option for category .', 1, '2025-06-25 15:10:52', '2025-07-23 16:25:31', '{\"width\": 20, \"height\": 40, \"length\": 10}'),
(9, NULL, 'dal', 'D5295', 'RAWDAL001', '2276034415631', 'dail need', 'kg', NULL, NULL, NULL, NULL, NULL, NULL, 50.00, 18.00, 'raw material', 1, '2025-06-26 10:44:14', '2025-06-26 10:44:14', '{\"width\": 28, \"height\": 30, \"length\": 10}'),
(10, NULL, 'Phone A', 'PHO7694', 'ELEPHO001', '2744941941043', '1gb ram', 'piece', NULL, NULL, NULL, NULL, NULL, NULL, 10000.00, 18.00, 'Electrical', 1, '2025-06-26 22:55:07', '2025-07-23 16:25:17', '{\"width\": 1, \"height\": 2, \"length\": 1}'),
(11, NULL, 'Rice', 'RIC4888', 'FOORIC001', '2995123859179', 'Kashmiri', 'kg', NULL, NULL, NULL, NULL, NULL, NULL, 50.00, 5.00, 'Food', 1, '2025-06-27 17:47:01', '2025-06-27 17:47:01', '{\"width\": 28, \"height\": 30, \"length\": 10}'),
(12, NULL, 'Basmati rice', 'BAS1686', 'MATBAS001', '2541684264725', 'daily', 'kg', NULL, NULL, NULL, NULL, NULL, NULL, 99.00, 18.00, 'material', 1, '2025-07-10 10:15:47', '2025-07-23 16:25:04', '{\"width\": 10, \"height\": 20, \"length\": 10}'),
(13, NULL, 'Milk', 'MIL6948', 'FOOMIL001', '2195716711765', 'Pure A2k Cow Milk', 'liter', NULL, NULL, NULL, NULL, NULL, NULL, 58.00, 5.00, 'Food', 1, '2025-07-10 18:41:07', '2025-07-23 16:24:48', '{\"height\": 20, \"length\": 10}'),
(15, NULL, 'Tea powder', 'TEA3636', 'MATTEA001', '2940055519707', 'fgkjfjk', 'gram', 20.00, 30.00, 40.00, NULL, NULL, NULL, 40.00, 18.00, 'material', 1, '2025-07-23 11:43:43', '2025-07-23 16:24:34', '{\"width\": 20, \"height\": 30, \"length\": 10}'),
(20, NULL, 'icecream', 'ICE3763', 'RAWICE001', '2280067141276', 'jhdf', 'pack', NULL, NULL, NULL, NULL, NULL, NULL, 40.00, 18.00, 'raw material', 1, '2025-07-23 12:50:14', '2025-07-23 16:24:18', '{\"width\": 20, \"height\": 30, \"length\": 10}'),
(22, NULL, 'udid', 'U9845', 'RAWUDI001', '2236248044867', 'dgnk', 'kg', NULL, NULL, NULL, NULL, NULL, NULL, 50.00, 18.00, 'raw material', 1, '2025-07-23 13:48:39', '2025-07-23 16:23:34', '{\"width\": 40, \"height\": 30, \"length\": 50}'),
(23, NULL, 'Key Board', 'K1832', 'ELEKEY001', '2370196267760', 'Electronic Keyboard', 'piece', NULL, NULL, NULL, NULL, NULL, NULL, 265.00, 18.00, 'Electronics', 1, '2025-07-23 15:05:56', '2025-07-23 16:22:38', '{\"width\": 28, \"height\": 30, \"length\": 10}');

-- --------------------------------------------------------

--
-- Table structure for table `material_requests`
--

CREATE TABLE `material_requests` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `requested_by` bigint UNSIGNED NOT NULL,
  `resolved` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `material_requests`
--

INSERT INTO `material_requests` (`id`, `name`, `requested_by`, `resolved`, `created_at`, `updated_at`) VALUES
(1, 'ufvgb', 1, 1, '2025-06-25 11:52:49', '2025-06-25 11:58:46'),
(2, 'rava', 1, 1, '2025-06-25 12:08:28', '2025-06-25 12:12:50'),
(3, 'dal', 1, 1, '2025-06-25 12:19:27', '2025-06-26 10:44:14'),
(4, 'maggi', 1, 0, '2025-06-25 12:30:10', '2025-06-25 12:30:10'),
(5, 'papad', 1, 0, '2025-06-25 12:58:37', '2025-06-25 12:58:37'),
(6, 'sugar\\', 1, 0, '2025-06-25 13:10:44', '2025-06-25 13:10:44'),
(7, 'mug dal', 1, 0, '2025-06-25 13:46:46', '2025-06-25 13:46:46'),
(8, 'oil', 1, 0, '2025-06-25 13:48:03', '2025-06-25 13:48:03'),
(9, 'Test q Material 1', 1, 0, '2025-06-25 14:52:15', '2025-06-25 14:52:15'),
(10, 'sdfbgf', 1, 0, '2025-06-25 16:32:59', '2025-06-25 16:32:59'),
(11, 'suga', 1, 0, '2025-06-25 17:17:28', '2025-06-25 17:17:28'),
(12, 'sug', 1, 0, '2025-06-25 17:17:32', '2025-06-25 17:17:32'),
(13, 'mil', 1, 0, '2025-06-25 17:17:41', '2025-06-25 17:17:41'),
(14, 'mill', 1, 0, '2025-06-25 17:17:44', '2025-06-25 17:17:44'),
(15, 'sdfty', 1, 0, '2025-06-25 17:54:13', '2025-06-25 17:54:13'),
(16, 'su', 1, 0, '2025-06-25 17:54:18', '2025-06-25 17:54:18'),
(17, 'mug', 1, 0, '2025-06-26 11:39:16', '2025-06-26 11:39:16'),
(18, 'mi', 1, 1, '2025-06-26 11:44:57', '2025-07-10 16:03:13'),
(19, 'pe', 1, 0, '2025-06-26 11:51:40', '2025-06-26 11:51:40'),
(20, 'da', 1, 0, '2025-06-26 12:39:51', '2025-06-26 12:39:51'),
(21, 'sy', 1, 0, '2025-06-26 12:40:05', '2025-06-26 12:40:05'),
(22, 'dsgdth', 1, 1, '2025-06-26 16:28:29', '2025-07-10 11:01:18'),
(23, 'dsgdt', 1, 1, '2025-06-26 16:28:34', '2025-07-10 11:01:13'),
(24, 'ds', 1, 1, '2025-06-26 16:29:24', '2025-07-10 11:01:08'),
(25, 'milk', 1, 1, '2025-06-26 16:36:17', '2025-07-10 11:00:59'),
(26, 'Ph', 1, 1, '2025-06-26 22:57:10', '2025-07-10 15:50:49'),
(27, 'Pho', 1, 1, '2025-06-26 22:57:18', '2025-07-10 11:00:51'),
(28, 'mu', 1, 1, '2025-06-27 12:40:28', '2025-07-10 11:00:35'),
(29, 'bas', 1, 1, '2025-07-10 10:16:25', '2025-07-10 11:00:29'),
(30, 'basmati', 1, 0, '2025-07-10 10:16:29', '2025-07-10 10:16:29'),
(31, 'Milk', 1, 1, '2025-07-10 18:40:07', '2025-07-10 18:41:07'),
(32, 'Mi', 1, 0, '2025-07-10 18:40:17', '2025-07-10 18:40:17'),
(33, 'Pa', 1, 1, '2025-07-10 18:41:52', '2025-07-14 07:09:19'),
(34, 'Paneer', 1, 1, '2025-07-10 18:41:56', '2025-07-14 07:09:23');

-- --------------------------------------------------------

--
-- Table structure for table `material_vendor`
--

CREATE TABLE `material_vendor` (
  `id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `material_id` bigint UNSIGNED NOT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `quantity` int DEFAULT '0',
  `material_name` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `gst_rate` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `material_vendor`
--

INSERT INTO `material_vendor` (`id`, `vendor_id`, `material_id`, `unit_price`, `created_at`, `updated_at`, `quantity`, `material_name`, `gst_rate`) VALUES
(22, 24, 7, 100.00, '2025-06-26 15:39:38', '2025-07-23 17:57:05', 0, 'rava', 18.00),
(23, 25, 5, 100.00, '2025-06-26 15:45:29', '2025-07-09 17:26:19', 50, 'sugar', 5.00),
(40, 30, 10, 50.00, '2025-07-03 10:21:03', '2025-07-05 17:16:12', 60, NULL, NULL),
(42, 37, 12, 100.00, '2025-07-10 10:29:27', '2025-07-23 17:04:45', 50, NULL, NULL),
(44, 40, 7, 80.00, '2025-07-11 17:02:46', '2025-07-22 11:49:28', 0, 'rava', 18.00),
(45, 40, 9, 40.00, '2025-07-11 17:02:46', '2025-07-23 17:48:40', 0, 'dal', 18.00),
(49, 38, 8, 5000.00, '2025-07-23 15:29:10', '2025-08-03 03:04:32', 25, NULL, NULL),
(50, 38, 23, 5000.00, '2025-07-23 15:29:10', '2025-07-23 16:49:18', 30, NULL, NULL),
(52, 38, 20, 50.00, '2025-07-23 16:49:18', '2025-07-23 16:49:18', 10, NULL, NULL),
(53, 37, 22, 45.00, '2025-07-23 17:04:45', '2025-07-23 17:04:45', 15, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2024_01_01_000000_create_users_table', 1),
(6, '2024_01_01_000001_create_role_permissions_table', 1),
(7, '2024_01_01_000004_create_barcodes_table', 2),
(8, '2024_01_01_000005_create_barcode_scans_table', 3),
(9, '2024_01_01_000006_add_foreign_keys_to_barcodes_table', 3),
(10, '2024_01_02_000000_create_purchase_orders_table', 4),
(11, '2024_01_02_000000_create_vendors_table', 4),
(12, '2024_01_02_000001_create_purchase_order_items_table', 5),
(13, '2025_06_03_082958_create_activity_log_table', 5),
(14, '2025_06_03_082959_add_event_column_to_activity_log_table', 5),
(15, '2025_06_03_083000_add_batch_uuid_column_to_activity_log_table', 5),
(16, '2025_06_04_072604_add_user_profile_fields_to_users_table', 6),
(17, '2025_06_04_190846_add_vendor_id_to_purchase_orders_table', 6),
(18, '2025_06_04_191243_rename_date_column_in_purchase_orders_table', 6),
(19, '2025_06_04_191531_add_notes_to_purchase_orders_table', 6),
(20, '2025_06_04_200526_update_purchase_orders_add_items_column', 7),
(21, '2025_06_05_133155_update_purchase_order_items_for_gst_and_batch', 7),
(22, '2025_06_05_142339_add_unique_constraint_to_po_number', 8),
(23, '2025_06_05_161432_update_purchase_orders_table', 8),
(24, '2025_06_05_162044_add_fields_to_purchase_order_items_table', 8),
(25, '2025_06_05_162447_create_materials_table', 8),
(26, '2025_06_05_163141_create_inventory_batches_table', 8),
(27, '2025_06_05_163321_create_inventory_transactions_table', 8),
(28, '2025_06_05_200357_add_po_number_to_purchase_orders_table', 9),
(29, '2025_06_05_200737_add_shipping_cost_to_purchase_orders_table', 9),
(30, '2025_06_05_201757_make_purchase_id_nullable_on_purchase_orders_table', 9),
(31, '2025_06_05_202854_create_purchase_order_item_details_table', 9),
(32, '2025_06_05_223327_create_inventory_items_table', 9),
(33, '2025_06_06_094208_add_net_price_to_purchase_order_items_table', 9),
(34, '2025_06_06_180038_add_unit_price_to_inventory_batches_table', 9),
(35, '2025_06_09_094837_add_missing_columns_to_inventory_batches_table', 10),
(36, '2025_06_09_100305_add_supplier_batch_number_and_quality_grade_to_inventory_batches', 10),
(37, '2025_06_09_101907_add_supplier_batch_number_to_inventory_batches', 11),
(38, '2025_06_12_115618_create_report_logs_table', 11),
(39, '2025_06_12_145225_create_notifications_table', 11),
(40, '2025_06_12_153939_add_approval_fields_to_purchase_orders', 12),
(41, '2025_06_24_162632_create_warehouses_table', 12),
(42, '2025_06_24_162902_create_warehouse_users_table', 12),
(43, '2025_06_24_181449_create_warehouse_users_table', 12),
(44, '2025_06_24_181520_create_warehouses_table', 6),
(45, '2025_06_24_181520_create_warehouses_table', 12);

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `route` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `name`, `route`, `icon`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'User Management', 'dashboard.users.index', 'fas fa-users', 1, '2025-07-15 11:44:00', '2025-07-15 11:44:00'),
(2, 'Warehouse Management', 'dashboard.warehouses.index', 'fas fa-warehouse', 1, '2025-07-15 11:44:00', '2025-07-15 11:44:00'),
(3, 'View blocks', 'blocks.index', 'fas fa-cubes', 1, '2025-07-15 11:44:00', '2025-07-15 11:44:00'),
(4, 'Materials', 'materials.index', 'fas fa-boxes', 1, '2025-07-15 11:44:00', '2025-07-15 11:44:00'),
(5, 'Vendor Management', 'vendors.index', 'fas fa-truck', 1, '2025-07-15 11:44:00', '2025-07-15 11:44:00'),
(6, 'Quality Analysis', 'quality-analysis.index', 'fas fa-chart-line', 1, '2025-07-15 11:44:00', '2025-07-15 11:44:00'),
(7, 'Purchase Orders', 'purchase-orders.index', 'fas fa-shopping-cart', 1, '2025-07-15 11:44:00', '2025-07-15 11:44:00'),
(8, 'Inventory Controls', 'inventory.index', 'fas fa-clipboard-list', 1, '2025-07-15 11:44:00', '2025-07-15 11:44:00'),
(9, 'Barcode Management', 'barcode.index', 'fas fa-barcode', 1, '2025-07-15 11:44:00', '2025-07-15 11:44:00'),
(10, 'Report Analysis', 'reports.index', 'fas fa-chart-bar', 1, '2025-07-15 11:44:00', '2025-07-15 11:44:00');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` json NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`, `id`) VALUES
('App\\Notifications\\PurchaseOrderDeleted', 'App\\Models\\User', 1, '{\"type\": \"purchase_order_deleted\", \"title\": \"Purchase Order Deleted\", \"message\": \"PO #PO-2025-0106 was deleted by Admin User.\", \"deleted_by\": \"Admin User\", \"purchase_order_id\": 106, \"purchase_order_number\": \"PO-2025-0106\"}', NULL, '2025-07-14 08:24:53', '2025-07-14 08:24:53', '03b64426-8cc4-4fd4-9dc1-ce5b58c73e35'),
('App\\Notifications\\PurchaseOrderDeleted', 'App\\Models\\User', 1, '{\"type\": \"purchase_order_deleted\", \"title\": \"Purchase Order Deleted\", \"message\": \"PO #PO-2025-0107 was deleted by Admin User.\", \"deleted_by\": \"Admin User\", \"purchase_order_id\": 108, \"purchase_order_number\": \"PO-2025-0107\"}', NULL, '2025-07-14 07:41:58', '2025-07-14 07:41:58', '06a53d80-6ba8-42bc-a07f-78e391f8bc07'),
('dashboard', 'App\\Models\\User', 3, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/121\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"3540.00\", \"vendor\": \"vendor\", \"message\": \"PO #PO-2025-0112 from vendor requires your approval\", \"po_date\": \"14 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-14 13:36:44', '2025-07-14 13:36:44', '07693a47-0fe7-4a3e-9464-d25277557ffe'),
('dashboard', 'App\\Models\\User', 3, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/111\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"3540.00\", \"vendor\": \"vendor\", \"message\": \"PO #PO-202507-0107 from vendor requires your approval\", \"po_date\": \"14 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-14 08:16:19', '2025-07-14 08:16:19', '0a012de4-2023-4b17-b6ef-9865c5fd3037'),
('dashboard', 'App\\Models\\User', 1, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/122\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"2360.00\", \"vendor\": \"vendor\", \"message\": \"PO #PO-2025-0122 from vendor requires your approval\", \"po_date\": \"14 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-14 13:37:35', '2025-07-14 13:37:35', '0f1986ae-3548-493c-90b5-92fac0c8b322'),
('App\\Notifications\\PurchaseOrderCreated', 'App\\Models\\User', 1, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/126\", \"title\": \"New Purchase Order Created\", \"message\": \"PO #PO-2025-0125 was created by Purchase User.\", \"order_id\": 126, \"created_by\": \"Purchase User\"}', '2025-07-22 11:50:42', '2025-07-22 11:49:28', '2025-07-22 11:50:42', '19050adc-85b1-460e-b5c7-e1f3b7fda211'),
('App\\Notifications\\PurchaseOrderCreated', 'App\\Models\\User', 1, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/122\", \"title\": \"New Purchase Order Created\", \"message\": \"PO #PO-2025-0122 was created by Admin User.\", \"order_id\": 122, \"created_by\": \"Admin User\"}', NULL, '2025-07-14 13:37:35', '2025-07-14 13:37:35', '1df755ba-9a84-43a5-9690-c0b9f4ad5dc5'),
('dashboard', 'App\\Models\\User', 2, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/124\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"944.00\", \"vendor\": \"test\", \"message\": \"PO #PO-2025-0124 from test requires your approval\", \"po_date\": \"22 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-22 11:46:54', '2025-07-22 11:46:54', '2069c503-bdfa-45c0-87a1-dec5cb3519b5'),
('dashboard', 'App\\Models\\User', 1, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/111\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"3540.00\", \"vendor\": \"vendor\", \"message\": \"PO #PO-202507-0107 from vendor requires your approval\", \"po_date\": \"14 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-14 08:16:19', '2025-07-14 08:16:19', '225a6df8-e562-4916-9100-b5a1e99c1421'),
('dashboard', 'App\\Models\\User', 1, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/126\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"2832.00\", \"vendor\": \"test\", \"message\": \"PO #PO-2025-0125 from test requires your approval\", \"po_date\": \"22 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-22 11:49:28', '2025-07-22 11:49:28', '38e38009-1ffb-413f-92eb-706a39ea6eac'),
('App\\Notifications\\PurchaseOrderDeleted', 'App\\Models\\User', 1, '{\"type\": \"purchase_order_deleted\", \"title\": \"Purchase Order Deleted\", \"message\": \"PO #PO-2025-0125 was deleted by Purchase User.\", \"deleted_by\": \"Purchase User\", \"purchase_order_id\": 125, \"purchase_order_number\": \"PO-2025-0125\"}', NULL, '2025-07-22 11:48:58', '2025-07-22 11:48:58', '4af82a5f-5e7c-434b-a17c-8ad5e32be8b9'),
('App\\Notifications\\PurchaseOrderDeleted', 'App\\Models\\User', 1, '{\"type\": \"purchase_order_deleted\", \"deleted_by\": \"Admin User\", \"deleted_by_id\": 1, \"purchase_order_id\": 109, \"purchase_order_number\": \"N/A\"}', NULL, '2025-07-14 07:25:26', '2025-07-14 07:25:26', '4bd84367-35c0-49f7-b092-0790275cc6fe'),
('dashboard', 'App\\Models\\User', 9, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/124\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"944.00\", \"vendor\": \"test\", \"message\": \"PO #PO-2025-0124 from test requires your approval\", \"po_date\": \"22 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-22 11:46:54', '2025-07-22 11:46:54', '4c67bc5f-a9f2-4179-9245-35db98fb5d79'),
('App\\Notifications\\PurchaseOrderDeleted', 'App\\Models\\User', 1, '{\"type\": \"purchase_order_deleted\", \"title\": \"Purchase Order Deleted\", \"message\": \"PO #PO-2025-0115 was deleted by Admin User.\", \"deleted_by\": \"Admin User\", \"purchase_order_id\": 115, \"purchase_order_number\": \"PO-2025-0115\"}', NULL, '2025-07-14 13:33:59', '2025-07-14 13:33:59', '50f01d69-29ae-4385-b5ee-85b0b4bd3ac2'),
('App\\Notifications\\PurchaseOrderCreated', 'App\\Models\\User', 1, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/123\", \"title\": \"New Purchase Order Created\", \"message\": \"PO #PO-2025-0123 was created by Admin User.\", \"order_id\": 123, \"created_by\": \"Admin User\"}', NULL, '2025-07-15 16:35:53', '2025-07-15 16:35:53', '53f9cd11-6807-41c9-acd7-eb1b9692b29c'),
('dashboard', 'App\\Models\\User', 4, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/111\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"3540.00\", \"vendor\": \"vendor\", \"message\": \"PO #PO-202507-0107 from vendor requires your approval\", \"po_date\": \"14 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-14 08:16:19', '2025-07-14 08:16:19', '56b3e296-ed37-465a-9ad2-c1d93af5f132'),
('App\\Notifications\\PurchaseOrderDeleted', 'App\\Models\\User', 1, '{\"type\": \"purchase_order_deleted\", \"title\": \"Purchase Order Deleted\", \"message\": \"PO #PO-2025-0112 was deleted by Admin User.\", \"deleted_by\": \"Admin User\", \"purchase_order_id\": 120, \"purchase_order_number\": \"PO-2025-0112\"}', NULL, '2025-07-14 13:35:48', '2025-07-14 13:35:48', '6dd40291-47a5-404f-b808-b555281a74bb'),
('dashboard', 'App\\Models\\User', 1, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/128\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"9440.00\", \"vendor\": \"test\", \"message\": \"PO #PO-2025-0128 from test requires your approval\", \"po_date\": \"23 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-23 17:57:05', '2025-07-23 17:57:05', '75058f29-b0e0-4b94-ae95-d6bbf751444d'),
('dashboard', 'App\\Models\\User', 1, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/127\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"1888.00\", \"vendor\": \"test\", \"message\": \"PO #PO-2025-0127 from test requires your approval\", \"po_date\": \"23 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', '2025-07-23 17:54:24', '2025-07-23 17:48:40', '2025-07-23 17:54:24', '77ee0bda-06ee-4857-8d38-9b26da5f1055'),
('dashboard', 'App\\Models\\User', 1, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/124\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"944.00\", \"vendor\": \"test\", \"message\": \"PO #PO-2025-0124 from test requires your approval\", \"po_date\": \"22 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', '2025-07-23 17:44:46', '2025-07-22 11:46:54', '2025-07-23 17:44:46', '7b198f48-54f9-4fc2-bfd2-e031b5789314'),
('dashboard', 'App\\Models\\User', 1, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/121\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"3540.00\", \"vendor\": \"vendor\", \"message\": \"PO #PO-2025-0112 from vendor requires your approval\", \"po_date\": \"14 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-14 13:36:44', '2025-07-14 13:36:44', '7da6c818-c329-40ce-9fbd-6c74ceb86e72'),
('App\\Notifications\\PurchaseOrderDeleted', 'App\\Models\\User', 1, '{\"type\": \"purchase_order_deleted\", \"title\": \"Purchase Order Deleted\", \"message\": \"PO #PO-2025-0087 was deleted by Admin User.\", \"deleted_by\": \"Admin User\", \"purchase_order_id\": 89, \"purchase_order_number\": \"PO-2025-0087\"}', '2025-07-14 07:35:48', '2025-07-14 07:31:40', '2025-07-14 07:35:48', '80174e10-c7f2-468e-8d93-fef42b8df2b7'),
('App\\Notifications\\PurchaseOrderCreated', 'App\\Models\\User', 1, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/121\", \"title\": \"New Purchase Order Created\", \"message\": \"PO #PO-2025-0112 was created by Admin User.\", \"order_id\": 121, \"created_by\": \"Admin User\"}', NULL, '2025-07-14 13:36:44', '2025-07-14 13:36:44', '851d18b6-561a-4382-b5ec-5a67953b5118'),
('App\\Notifications\\PurchaseOrderCreated', 'App\\Models\\User', 1, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/124\", \"title\": \"New Purchase Order Created\", \"message\": \"PO #PO-2025-0124 was created by Admin User.\", \"order_id\": 124, \"created_by\": \"Admin User\"}', NULL, '2025-07-22 11:46:54', '2025-07-22 11:46:54', '8e48dd0d-3811-471d-b58e-fd935e36c2d2'),
('App\\Notifications\\PurchaseOrderCreated', 'App\\Models\\User', 1, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/128\", \"title\": \"PO #PO-2025-0128 Created\", \"message\": \"PO #PO-2025-0128 was created by Admin User. Status: Pending.\", \"order_id\": 128, \"approve_url\": \"https://portfolio3.lemmecode.in/purchase-orders/128/approve\"}', '2025-07-23 18:00:38', '2025-07-23 17:57:05', '2025-07-23 18:00:38', '90ce4c38-4c59-4fc1-9959-eaddcb462ac4'),
('dashboard', 'App\\Models\\User', 3, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/129\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"31250.00\", \"vendor\": \"Vendor Singh\", \"message\": \"PO #PO-2025-0129 from Vendor Singh requires your approval\", \"po_date\": \"03 Aug 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-08-03 03:04:32', '2025-08-03 03:04:32', '91c7b192-7e7b-412b-9a0b-f8647999a59b'),
('dashboard', 'App\\Models\\User', 2, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/121\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"3540.00\", \"vendor\": \"vendor\", \"message\": \"PO #PO-2025-0112 from vendor requires your approval\", \"po_date\": \"14 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-14 13:36:44', '2025-07-14 13:36:44', '93084a71-a1b0-4b2f-a24f-6a94c3f81807'),
('dashboard', 'App\\Models\\User', 3, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/127\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"1888.00\", \"vendor\": \"test\", \"message\": \"PO #PO-2025-0127 from test requires your approval\", \"po_date\": \"23 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-23 17:48:40', '2025-07-23 17:48:40', '936f5cf7-8b8f-44fa-96fc-ed3ae76cc102'),
('dashboard', 'App\\Models\\User', 4, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/121\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"3540.00\", \"vendor\": \"vendor\", \"message\": \"PO #PO-2025-0112 from vendor requires your approval\", \"po_date\": \"14 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-14 13:36:44', '2025-07-14 13:36:44', '95aeb02a-bbf1-483d-acb2-92c1a0312d94'),
('dashboard', 'App\\Models\\User', 2, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/129\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"31250.00\", \"vendor\": \"Vendor Singh\", \"message\": \"PO #PO-2025-0129 from Vendor Singh requires your approval\", \"po_date\": \"03 Aug 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-08-03 03:04:32', '2025-08-03 03:04:32', 'a4931804-75ba-419d-af8f-b63189afc0c5'),
('dashboard', 'App\\Models\\User', 9, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/126\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"2832.00\", \"vendor\": \"test\", \"message\": \"PO #PO-2025-0125 from test requires your approval\", \"po_date\": \"22 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-22 11:49:28', '2025-07-22 11:49:28', 'a8fd3205-f66d-4b58-9865-a2692ea5d88f'),
('App\\Notifications\\PurchaseOrderCreated', 'App\\Models\\User', 1, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/129\", \"title\": \"PO #PO-2025-0129 Created\", \"message\": \"PO #PO-2025-0129 was created by Admin User. Status: Pending.\", \"order_id\": 129, \"approve_url\": \"https://portfolio3.lemmecode.in/purchase-orders/129/approve\"}', NULL, '2025-08-03 03:04:32', '2025-08-03 03:04:32', 'adbdce10-2116-4087-83bb-c2b5055305a7'),
('App\\Notifications\\PurchaseOrderCreated', 'App\\Models\\User', 1, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/111\", \"title\": \"New Purchase Order Created\", \"message\": \"PO #PO-202507-0107 was created by Admin User.\", \"order_id\": 111, \"created_by\": \"Admin User\"}', NULL, '2025-07-14 08:16:19', '2025-07-14 08:16:19', 'b04c81cb-23ec-473f-abc4-949436f3291a'),
('dashboard', 'App\\Models\\User', 9, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/127\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"1888.00\", \"vendor\": \"test\", \"message\": \"PO #PO-2025-0127 from test requires your approval\", \"po_date\": \"23 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-23 17:48:40', '2025-07-23 17:48:40', 'b4004b04-790b-41f6-9e14-1844bf5ff64a'),
('dashboard', 'App\\Models\\User', 4, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/122\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"2360.00\", \"vendor\": \"vendor\", \"message\": \"PO #PO-2025-0122 from vendor requires your approval\", \"po_date\": \"14 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-14 13:37:35', '2025-07-14 13:37:35', 'b71b927d-3f53-465a-b503-ee71b85a6c8f'),
('dashboard', 'App\\Models\\User', 1, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/123\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"177000.00\", \"vendor\": \"Vendor Singh\", \"message\": \"PO #PO-2025-0123 from Vendor Singh requires your approval\", \"po_date\": \"15 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"high\"}', NULL, '2025-07-15 16:35:53', '2025-07-15 16:35:53', 'b817bcee-8666-41d0-9b9c-9bac31eacfc3'),
('dashboard', 'App\\Models\\User', 9, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/128\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"9440.00\", \"vendor\": \"test\", \"message\": \"PO #PO-2025-0128 from test requires your approval\", \"po_date\": \"23 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-23 17:57:05', '2025-07-23 17:57:05', 'b8a1896f-948e-46b3-8518-2c727e6189e4'),
('dashboard', 'App\\Models\\User', 1, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/3\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"472.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO2025000001 from Rahul Sharma requires your approval\", \"po_date\": \"24 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', '2025-07-14 07:12:20', '2025-06-24 18:38:29', '2025-07-14 07:12:20', 'c1531cbf-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 1, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/72\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"2520.00\", \"vendor\": \"Nikita Mohan Shinde\", \"message\": \"PO #PO-2025-0072 from Nikita Mohan Shinde requires your approval\", \"po_date\": \"02 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', '2025-07-14 07:12:20', '2025-07-02 09:45:55', '2025-07-14 07:12:20', 'c15325ce-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 2, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/72\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"2520.00\", \"vendor\": \"Nikita Mohan Shinde\", \"message\": \"PO #PO-2025-0072 from Nikita Mohan Shinde requires your approval\", \"po_date\": \"02 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-02 09:45:55', '2025-07-02 09:45:55', 'c153265c-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 3, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/72\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"2520.00\", \"vendor\": \"Nikita Mohan Shinde\", \"message\": \"PO #PO-2025-0072 from Nikita Mohan Shinde requires your approval\", \"po_date\": \"02 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-02 09:45:55', '2025-07-02 09:45:55', 'c153300a-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 1, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/73\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"4200.00\", \"vendor\": \"Nikita Mohan Shinde\", \"message\": \"PO #PO-2025-0054 from Nikita Mohan Shinde requires your approval\", \"po_date\": \"02 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', '2025-07-14 07:12:20', '2025-07-02 09:58:01', '2025-07-14 07:12:20', 'c15330ca-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 2, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/73\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"4200.00\", \"vendor\": \"Nikita Mohan Shinde\", \"message\": \"PO #PO-2025-0054 from Nikita Mohan Shinde requires your approval\", \"po_date\": \"02 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-02 09:58:01', '2025-07-02 09:58:01', 'c153317a-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 3, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/73\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"4200.00\", \"vendor\": \"Nikita Mohan Shinde\", \"message\": \"PO #PO-2025-0054 from Nikita Mohan Shinde requires your approval\", \"po_date\": \"02 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-02 09:58:01', '2025-07-02 09:58:01', 'c1533241-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 1, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/74\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"1260.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO-2025-0074 from Rahul Sharma requires your approval\", \"po_date\": \"03 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', '2025-07-14 07:12:20', '2025-07-03 10:21:28', '2025-07-14 07:12:20', 'c153328b-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 2, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/74\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"1260.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO-2025-0074 from Rahul Sharma requires your approval\", \"po_date\": \"03 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-03 10:21:28', '2025-07-03 10:21:28', 'c1533c97-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 3, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/74\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"1260.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO-2025-0074 from Rahul Sharma requires your approval\", \"po_date\": \"03 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-03 10:21:28', '2025-07-03 10:21:28', 'c1533dc0-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 1, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/75\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"2950.00\", \"vendor\": \"test\", \"message\": \"PO #PO-2025-0074 from test requires your approval\", \"po_date\": \"03 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', '2025-07-14 07:12:20', '2025-07-03 10:40:05', '2025-07-14 07:12:20', 'c153468b-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 2, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/3\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"472.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO2025000001 from Rahul Sharma requires your approval\", \"po_date\": \"24 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-06-24 18:38:30', '2025-06-24 18:38:30', 'c1534700-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 2, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/75\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"2950.00\", \"vendor\": \"test\", \"message\": \"PO #PO-2025-0074 from test requires your approval\", \"po_date\": \"03 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-03 10:40:05', '2025-07-03 10:40:05', 'c15347c2-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 3, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/75\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"2950.00\", \"vendor\": \"test\", \"message\": \"PO #PO-2025-0074 from test requires your approval\", \"po_date\": \"03 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-03 10:40:05', '2025-07-03 10:40:05', 'c1534867-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 1, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/76\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"590.00\", \"vendor\": \"test\", \"message\": \"PO #PO-2025-0076 from test requires your approval\", \"po_date\": \"03 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', '2025-07-14 07:12:20', '2025-07-03 10:41:11', '2025-07-14 07:12:20', 'c1534914-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 2, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/76\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"590.00\", \"vendor\": \"test\", \"message\": \"PO #PO-2025-0076 from test requires your approval\", \"po_date\": \"03 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-03 10:41:11', '2025-07-03 10:41:11', 'c1534949-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 3, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/76\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"590.00\", \"vendor\": \"test\", \"message\": \"PO #PO-2025-0076 from test requires your approval\", \"po_date\": \"03 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-03 10:41:11', '2025-07-03 10:41:11', 'c1534a22-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 2, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/77\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"5900.00\", \"vendor\": \"test\", \"message\": \"PO #PO-2025-0077 from test requires your approval\", \"po_date\": \"03 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-03 11:02:47', '2025-07-03 11:02:47', 'c1534ad6-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 3, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/77\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"5900.00\", \"vendor\": \"test\", \"message\": \"PO #PO-2025-0077 from test requires your approval\", \"po_date\": \"03 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-03 11:02:47', '2025-07-03 11:02:47', 'c1534b08-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 2, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/78\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"630.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO-2025-0077 from Rahul Sharma requires your approval\", \"po_date\": \"03 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-03 11:10:30', '2025-07-03 11:10:30', 'c1534bf5-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 3, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/3\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"472.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO2025000001 from Rahul Sharma requires your approval\", \"po_date\": \"24 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-06-24 18:38:30', '2025-06-24 18:38:30', 'c1535235-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 3, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/78\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"630.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO-2025-0077 from Rahul Sharma requires your approval\", \"po_date\": \"03 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-03 11:10:30', '2025-07-03 11:10:30', 'c1535368-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 2, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/79\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"630.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO-2025-0079 from Rahul Sharma requires your approval\", \"po_date\": \"03 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-03 11:11:37', '2025-07-03 11:11:37', 'c1535717-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 3, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/79\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"630.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO-2025-0079 from Rahul Sharma requires your approval\", \"po_date\": \"03 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-03 11:11:37', '2025-07-03 11:11:37', 'c1535829-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 2, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/80\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"315.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO-2025-0079 from Rahul Sharma requires your approval\", \"po_date\": \"03 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-03 11:13:33', '2025-07-03 11:13:33', 'c1535a84-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 3, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/80\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"315.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO-2025-0079 from Rahul Sharma requires your approval\", \"po_date\": \"03 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-03 11:13:33', '2025-07-03 11:13:33', 'c1536127-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 1, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/81\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"315.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO-2025-0081 from Rahul Sharma requires your approval\", \"po_date\": \"03 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', '2025-07-14 07:12:20', '2025-07-03 11:20:38', '2025-07-14 07:12:20', 'c15364fa-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 2, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/81\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"315.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO-2025-0081 from Rahul Sharma requires your approval\", \"po_date\": \"03 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-03 11:20:38', '2025-07-03 11:20:38', 'c15366b1-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 3, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/81\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"315.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO-2025-0081 from Rahul Sharma requires your approval\", \"po_date\": \"03 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-03 11:20:38', '2025-07-03 11:20:38', 'c15366f1-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 1, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/4\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"1500.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO2025000002 from Rahul Sharma requires your approval\", \"po_date\": \"25 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', '2025-07-14 07:12:20', '2025-06-25 15:43:24', '2025-07-14 07:12:20', 'c1536724-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 1, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/82\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"315.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO-2025-0081 from Rahul Sharma requires your approval\", \"po_date\": \"03 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', '2025-07-10 15:53:36', '2025-07-03 11:30:08', '2025-07-10 15:53:36', 'c1536756-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 2, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/82\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"315.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO-2025-0081 from Rahul Sharma requires your approval\", \"po_date\": \"03 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-03 11:30:08', '2025-07-03 11:30:08', 'c1536c17-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 1, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/84\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"9440.00\", \"vendor\": \"test\", \"message\": \"PO #PO-2025-0084 from test requires your approval\", \"po_date\": \"05 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', '2025-07-10 15:53:27', '2025-07-05 17:19:34', '2025-07-10 15:53:27', 'c1536c73-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 2, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/84\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"9440.00\", \"vendor\": \"test\", \"message\": \"PO #PO-2025-0084 from test requires your approval\", \"po_date\": \"05 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-05 17:19:34', '2025-07-05 17:19:34', 'c1536cbb-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 3, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/84\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"9440.00\", \"vendor\": \"test\", \"message\": \"PO #PO-2025-0084 from test requires your approval\", \"po_date\": \"05 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-05 17:19:34', '2025-07-05 17:19:34', 'c1536cfa-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 2, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/4\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"1500.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO2025000002 from Rahul Sharma requires your approval\", \"po_date\": \"25 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-06-25 15:43:24', '2025-06-25 15:43:24', 'c1536d2f-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 3, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/4\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"1500.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO2025000002 from Rahul Sharma requires your approval\", \"po_date\": \"25 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-06-25 15:43:24', '2025-06-25 15:43:24', 'c153775b-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 1, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/4\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"1770.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO2025000002 from Rahul Sharma requires your approval\", \"po_date\": \"25 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', '2025-07-14 07:12:20', '2025-06-25 15:43:24', '2025-07-14 07:12:20', 'c15379fd-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 2, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/4\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"1770.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO2025000002 from Rahul Sharma requires your approval\", \"po_date\": \"25 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-06-25 15:43:24', '2025-06-25 15:43:24', 'c1537a30-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 3, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/4\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"1770.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO2025000002 from Rahul Sharma requires your approval\", \"po_date\": \"25 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-06-25 15:43:24', '2025-06-25 15:43:24', 'c1537aa0-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 1, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/5\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"500.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO2025000003 from Rahul Sharma requires your approval\", \"po_date\": \"26 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', '2025-07-14 07:12:20', '2025-06-26 23:08:17', '2025-07-14 07:12:20', 'c1537ad0-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 2, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/5\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"500.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO2025000003 from Rahul Sharma requires your approval\", \"po_date\": \"26 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-06-26 23:08:17', '2025-06-26 23:08:17', 'c1537cc6-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 3, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/5\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"500.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO2025000003 from Rahul Sharma requires your approval\", \"po_date\": \"26 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-06-26 23:08:17', '2025-06-26 23:08:17', 'c1537cfd-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 1, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/5\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"590.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO2025000003 from Rahul Sharma requires your approval\", \"po_date\": \"26 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', '2025-07-14 07:12:20', '2025-06-26 23:08:17', '2025-07-14 07:12:20', 'c1537d70-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 2, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/5\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"590.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO2025000003 from Rahul Sharma requires your approval\", \"po_date\": \"26 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-06-26 23:08:17', '2025-06-26 23:08:17', 'c1537df4-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 3, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/5\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"590.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO2025000003 from Rahul Sharma requires your approval\", \"po_date\": \"26 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-06-26 23:08:17', '2025-06-26 23:08:17', 'c1537e67-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 1, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/4\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"1770.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO2025000002 from Rahul Sharma requires your approval\", \"po_date\": \"25 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"high\"}', '2025-07-14 07:12:20', '2025-06-30 07:43:28', '2025-07-14 07:12:20', 'c1537e98-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 2, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/4\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"1770.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO2025000002 from Rahul Sharma requires your approval\", \"po_date\": \"25 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"high\"}', NULL, '2025-06-30 07:43:28', '2025-06-30 07:43:28', 'c1537f09-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 3, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/4\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"1770.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO2025000002 from Rahul Sharma requires your approval\", \"po_date\": \"25 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"high\"}', NULL, '2025-06-30 07:43:28', '2025-06-30 07:43:28', 'c1537fb0-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 1, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/48\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"2520.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO-2025-0001 from Rahul Sharma requires your approval\", \"po_date\": \"30 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', '2025-07-14 07:12:20', '2025-06-30 17:16:49', '2025-07-14 07:12:20', 'c1538022-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 2, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/48\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"2520.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO-2025-0001 from Rahul Sharma requires your approval\", \"po_date\": \"30 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-06-30 17:16:49', '2025-06-30 17:16:49', 'c15385e0-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 3, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/48\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"2520.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO-2025-0001 from Rahul Sharma requires your approval\", \"po_date\": \"30 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-06-30 17:16:49', '2025-06-30 17:16:49', 'c15386ff-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 1, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/50\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"630.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO-2025-0049 from Rahul Sharma requires your approval\", \"po_date\": \"30 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', '2025-07-14 07:12:20', '2025-06-30 22:11:14', '2025-07-14 07:12:20', 'c1538a39-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 2, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/50\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"630.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO-2025-0049 from Rahul Sharma requires your approval\", \"po_date\": \"30 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-06-30 22:11:14', '2025-06-30 22:11:14', 'c1538b7c-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 3, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/50\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"630.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO-2025-0049 from Rahul Sharma requires your approval\", \"po_date\": \"30 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-06-30 22:11:14', '2025-06-30 22:11:14', 'c1538c9c-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 1, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/51\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"630.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO-2025-0051 from Rahul Sharma requires your approval\", \"po_date\": \"30 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', '2025-07-14 07:12:20', '2025-06-30 22:11:38', '2025-07-14 07:12:20', 'c1538d18-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 2, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/51\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"630.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO-2025-0051 from Rahul Sharma requires your approval\", \"po_date\": \"30 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-06-30 22:11:38', '2025-06-30 22:11:38', 'c153c4d9-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 3, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/51\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"630.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO-2025-0051 from Rahul Sharma requires your approval\", \"po_date\": \"30 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-06-30 22:11:38', '2025-06-30 22:11:38', 'c153c5d2-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 1, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/3\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"400.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO2025000001 from Rahul Sharma requires your approval\", \"po_date\": \"24 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', '2025-07-14 07:12:20', '2025-06-24 18:38:15', '2025-07-14 07:12:20', 'c153ce38-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 1, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/52\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"630.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO-2025-0051 from Rahul Sharma requires your approval\", \"po_date\": \"30 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', '2025-07-14 07:12:20', '2025-06-30 22:18:32', '2025-07-14 07:12:20', 'c153cea3-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 2, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/52\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"630.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO-2025-0051 from Rahul Sharma requires your approval\", \"po_date\": \"30 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-06-30 22:18:32', '2025-06-30 22:18:32', 'c153cede-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 3, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/52\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"630.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO-2025-0051 from Rahul Sharma requires your approval\", \"po_date\": \"30 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-06-30 22:18:32', '2025-06-30 22:18:32', 'c153cf13-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 1, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/53\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"630.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO-2025-0053 from Rahul Sharma requires your approval\", \"po_date\": \"30 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', '2025-07-14 07:12:20', '2025-06-30 22:19:35', '2025-07-14 07:12:20', 'c153cf7a-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 2, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/53\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"630.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO-2025-0053 from Rahul Sharma requires your approval\", \"po_date\": \"30 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-06-30 22:19:35', '2025-06-30 22:19:35', 'c153cfbb-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 3, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/53\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"630.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO-2025-0053 from Rahul Sharma requires your approval\", \"po_date\": \"30 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-06-30 22:19:35', '2025-06-30 22:19:35', 'c153cff3-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 2, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/3\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"400.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO2025000001 from Rahul Sharma requires your approval\", \"po_date\": \"24 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-06-24 18:38:15', '2025-06-24 18:38:15', 'c153d048-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 1, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/57\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"3540.00\", \"vendor\": \"test\", \"message\": \"PO #PO-2025-0054 from test requires your approval\", \"po_date\": \"30 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', '2025-07-14 07:12:20', '2025-06-30 22:34:00', '2025-07-14 07:12:20', 'c153d0f5-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 2, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/57\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"3540.00\", \"vendor\": \"test\", \"message\": \"PO #PO-2025-0054 from test requires your approval\", \"po_date\": \"30 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-06-30 22:34:00', '2025-06-30 22:34:00', 'c153d154-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 3, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/57\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"3540.00\", \"vendor\": \"test\", \"message\": \"PO #PO-2025-0054 from test requires your approval\", \"po_date\": \"30 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-06-30 22:34:00', '2025-06-30 22:34:00', 'c153d1a3-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 3, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/3\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"400.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO2025000001 from Rahul Sharma requires your approval\", \"po_date\": \"24 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-06-24 18:38:15', '2025-06-24 18:38:15', 'c153d30f-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 1, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/3\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"472.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO2025000001 from Rahul Sharma requires your approval\", \"po_date\": \"24 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', '2025-07-14 07:12:20', '2025-06-24 18:38:15', '2025-07-14 07:12:20', 'c153d361-5e09-11f0-987c-bc2411f3afe0');
INSERT INTO `notifications` (`type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`, `id`) VALUES
('dashboard', 'App\\Models\\User', 2, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/3\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"472.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO2025000001 from Rahul Sharma requires your approval\", \"po_date\": \"24 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-06-24 18:38:15', '2025-06-24 18:38:15', 'c153d3a8-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 1, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/66\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"1180.00\", \"vendor\": \"test\", \"message\": \"PO #PO-2025-0058 from test requires your approval\", \"po_date\": \"30 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', '2025-07-14 07:12:20', '2025-06-30 22:44:00', '2025-07-14 07:12:20', 'c153d3f4-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 2, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/66\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"1180.00\", \"vendor\": \"test\", \"message\": \"PO #PO-2025-0058 from test requires your approval\", \"po_date\": \"30 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-06-30 22:44:00', '2025-06-30 22:44:00', 'c153d443-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 3, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/66\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"1180.00\", \"vendor\": \"test\", \"message\": \"PO #PO-2025-0058 from test requires your approval\", \"po_date\": \"30 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-06-30 22:44:00', '2025-06-30 22:44:00', 'c153d48e-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 1, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/67\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"4130.00\", \"vendor\": \"test\", \"message\": \"PO #PO-2025-0067 from test requires your approval\", \"po_date\": \"30 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', '2025-07-14 07:12:20', '2025-06-30 22:45:22', '2025-07-14 07:12:20', 'c153dc11-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 2, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/67\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"4130.00\", \"vendor\": \"test\", \"message\": \"PO #PO-2025-0067 from test requires your approval\", \"po_date\": \"30 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-06-30 22:45:22', '2025-06-30 22:45:22', 'c153dc84-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 3, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/67\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"4130.00\", \"vendor\": \"test\", \"message\": \"PO #PO-2025-0067 from test requires your approval\", \"po_date\": \"30 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-06-30 22:45:22', '2025-06-30 22:45:22', 'c153dcb8-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 1, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/68\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"531.00\", \"vendor\": \"test\", \"message\": \"PO #PO-2025-0068 from test requires your approval\", \"po_date\": \"30 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', '2025-07-14 06:58:19', '2025-06-30 22:45:50', '2025-07-14 06:58:19', 'c153dcea-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 2, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/68\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"531.00\", \"vendor\": \"test\", \"message\": \"PO #PO-2025-0068 from test requires your approval\", \"po_date\": \"30 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-06-30 22:45:50', '2025-06-30 22:45:50', 'c153dd1a-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 3, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/3\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"472.00\", \"vendor\": \"Rahul Sharma\", \"message\": \"PO #PO2025000001 from Rahul Sharma requires your approval\", \"po_date\": \"24 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-06-24 18:38:15', '2025-06-24 18:38:15', 'c153dd4c-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 3, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/68\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"531.00\", \"vendor\": \"test\", \"message\": \"PO #PO-2025-0068 from test requires your approval\", \"po_date\": \"30 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-06-30 22:45:50', '2025-06-30 22:45:50', 'c153dd7d-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 1, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/69\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"59.00\", \"vendor\": \"test\", \"message\": \"PO #PO-2025-0069 from test requires your approval\", \"po_date\": \"30 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', '2025-07-14 06:57:24', '2025-06-30 22:46:24', '2025-07-14 06:57:24', 'c153dde1-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 2, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/69\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"59.00\", \"vendor\": \"test\", \"message\": \"PO #PO-2025-0069 from test requires your approval\", \"po_date\": \"30 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-06-30 22:46:24', '2025-06-30 22:46:24', 'c153de17-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 3, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/69\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"59.00\", \"vendor\": \"test\", \"message\": \"PO #PO-2025-0069 from test requires your approval\", \"po_date\": \"30 Jun 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-06-30 22:46:24', '2025-06-30 22:46:24', 'c153de49-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 1, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/70\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"5440.00\", \"vendor\": \"Nikita Mohan Shinde\", \"message\": \"PO #PO-2025-0070 from Nikita Mohan Shinde requires your approval\", \"po_date\": \"01 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', '2025-07-14 06:58:14', '2025-07-01 09:51:18', '2025-07-14 06:58:14', 'c153de7a-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 2, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/70\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"5440.00\", \"vendor\": \"Nikita Mohan Shinde\", \"message\": \"PO #PO-2025-0070 from Nikita Mohan Shinde requires your approval\", \"po_date\": \"01 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-01 09:51:18', '2025-07-01 09:51:18', 'c153deec-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 3, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/70\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"5440.00\", \"vendor\": \"Nikita Mohan Shinde\", \"message\": \"PO #PO-2025-0070 from Nikita Mohan Shinde requires your approval\", \"po_date\": \"01 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-01 09:51:18', '2025-07-01 09:51:18', 'c153df1e-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 1, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/71\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"1680.00\", \"vendor\": \"Nikita Mohan Shinde\", \"message\": \"PO #PO-2025-0068 from Nikita Mohan Shinde requires your approval\", \"po_date\": \"02 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', '2025-07-14 07:12:20', '2025-07-02 09:38:00', '2025-07-14 07:12:20', 'c153df50-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 2, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/71\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"1680.00\", \"vendor\": \"Nikita Mohan Shinde\", \"message\": \"PO #PO-2025-0068 from Nikita Mohan Shinde requires your approval\", \"po_date\": \"02 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-02 09:38:00', '2025-07-02 09:38:00', 'c153dfae-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 3, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/71\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"1680.00\", \"vendor\": \"Nikita Mohan Shinde\", \"message\": \"PO #PO-2025-0068 from Nikita Mohan Shinde requires your approval\", \"po_date\": \"02 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-02 09:38:00', '2025-07-02 09:38:00', 'c153dfde-5e09-11f0-987c-bc2411f3afe0'),
('dashboard', 'App\\Models\\User', 3, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/128\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"9440.00\", \"vendor\": \"test\", \"message\": \"PO #PO-2025-0128 from test requires your approval\", \"po_date\": \"23 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-23 17:57:05', '2025-07-23 17:57:05', 'c3913813-cfdd-4f9b-96c5-050d2f3af482'),
('dashboard', 'App\\Models\\User', 3, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/126\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"2832.00\", \"vendor\": \"test\", \"message\": \"PO #PO-2025-0125 from test requires your approval\", \"po_date\": \"22 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-22 11:49:28', '2025-07-22 11:49:28', 'c473646c-a47d-4bab-bfda-ea08d5275c16'),
('dashboard', 'App\\Models\\User', 2, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/122\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"2360.00\", \"vendor\": \"vendor\", \"message\": \"PO #PO-2025-0122 from vendor requires your approval\", \"po_date\": \"14 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-14 13:37:35', '2025-07-14 13:37:35', 'ca281423-73b6-417d-9e24-4758a3874da2'),
('dashboard', 'App\\Models\\User', 2, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/127\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"1888.00\", \"vendor\": \"test\", \"message\": \"PO #PO-2025-0127 from test requires your approval\", \"po_date\": \"23 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-23 17:48:40', '2025-07-23 17:48:40', 'd23ea48d-5160-45b3-bd73-3acf6238d07f'),
('dashboard', 'App\\Models\\User', 2, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/128\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"9440.00\", \"vendor\": \"test\", \"message\": \"PO #PO-2025-0128 from test requires your approval\", \"po_date\": \"23 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-23 17:57:05', '2025-07-23 17:57:05', 'd38c66e1-b77a-432d-a597-e48422a8ebeb'),
('dashboard', 'App\\Models\\User', 3, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/122\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"2360.00\", \"vendor\": \"vendor\", \"message\": \"PO #PO-2025-0122 from vendor requires your approval\", \"po_date\": \"14 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-14 13:37:35', '2025-07-14 13:37:35', 'd7fc6847-bcee-4bc7-98c9-fceede996dff'),
('dashboard', 'App\\Models\\User', 3, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/124\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"944.00\", \"vendor\": \"test\", \"message\": \"PO #PO-2025-0124 from test requires your approval\", \"po_date\": \"22 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-22 11:46:54', '2025-07-22 11:46:54', 'e4462b9d-ab44-4740-a323-10d569fba87e'),
('App\\Notifications\\PurchaseOrderCreated', 'App\\Models\\User', 1, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/127\", \"title\": \"PO #PO-2025-0127 Created\", \"message\": \"PO #PO-2025-0127 was created by Admin User. Status: Pending.\", \"order_id\": 127, \"approve_url\": \"https://portfolio3.lemmecode.in/purchase-orders/127/approve\"}', '2025-07-23 17:50:49', '2025-07-23 17:48:40', '2025-07-23 17:50:49', 'e60438e8-3555-43b3-9634-8a8ff2e6b91d'),
('dashboard', 'App\\Models\\User', 1, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/129\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"31250.00\", \"vendor\": \"Vendor Singh\", \"message\": \"PO #PO-2025-0129 from Vendor Singh requires your approval\", \"po_date\": \"03 Aug 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-08-03 03:04:32', '2025-08-03 03:04:32', 'ef31f13d-43ea-4475-9433-f09c4ad93bf1'),
('dashboard', 'App\\Models\\User', 2, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/126\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"2832.00\", \"vendor\": \"test\", \"message\": \"PO #PO-2025-0125 from test requires your approval\", \"po_date\": \"22 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-22 11:49:28', '2025-07-22 11:49:28', 'f45268dd-8ff2-482f-91c6-8bd36d551fee'),
('dashboard', 'App\\Models\\User', 2, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/111\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"3540.00\", \"vendor\": \"vendor\", \"message\": \"PO #PO-202507-0107 from vendor requires your approval\", \"po_date\": \"14 Jul 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-07-14 08:16:19', '2025-07-14 08:16:19', 'f716aead-9f12-45ea-9fdb-25acc34459de'),
('App\\Notifications\\PurchaseOrderUpdated', 'App\\Models\\User', 1, '{\"type\": \"purchase_order_updated\", \"title\": \"Purchase Order Updated\", \"changes\": {\"po_date\": {\"new\": \"2025-07-13\", \"old\": \"2025-07-12T18:30:00.000000Z\"}, \"order_date\": {\"new\": \"2025-07-13\", \"old\": \"2025-07-12T18:30:00.000000Z\"}, \"expected_delivery\": {\"new\": \"2025-08-26\", \"old\": \"2025-08-06T18:30:00.000000Z\"}}, \"message\": \"PO #PO-2025-0107 was updated by Admin User.\", \"updated_by\": \"Admin User\", \"purchase_order_id\": 108, \"purchase_order_number\": \"PO-2025-0107\"}', NULL, '2025-07-14 07:40:29', '2025-07-14 07:40:29', 'f8b4664c-a85a-457e-8ff3-d84ec45106db'),
('dashboard', 'App\\Models\\User', 9, '{\"url\": \"https://portfolio3.lemmecode.in/purchase-orders/129\", \"title\": \"Purchase Order Approval Required\", \"amount\": \"31250.00\", \"vendor\": \"Vendor Singh\", \"message\": \"PO #PO-2025-0129 from Vendor Singh requires your approval\", \"po_date\": \"03 Aug 2025\", \"category\": \"approval_required\", \"priority\": \"normal\"}', NULL, '2025-08-03 03:04:32', '2025-08-03 03:04:32', 'f9dbef49-1ac6-412f-90d8-927b97397132');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `module_id` bigint UNSIGNED NOT NULL,
  `can_view` tinyint(1) DEFAULT '0',
  `can_edit` tinyint(1) DEFAULT '0',
  `can_create` tinyint(1) DEFAULT '0',
  `can_delete` tinyint(1) DEFAULT '0',
  `can_assign` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `user_id`, `module_id`, `can_view`, `can_edit`, `can_create`, `can_delete`, `can_assign`, `created_at`, `updated_at`) VALUES
(1, 6, 2, 1, 1, 1, 0, 0, '2025-07-16 10:44:29', '2025-07-16 10:44:29'),
(7, 6, 4, 1, 1, 0, 0, 0, '2025-07-17 12:29:05', '2025-07-17 12:29:05'),
(8, 6, 3, 1, 0, 0, 0, 0, '2025-07-17 12:29:05', '2025-07-17 12:29:05'),
(9, 6, 5, 1, 0, 1, 1, 0, '2025-07-17 12:29:05', '2025-07-17 12:29:05'),
(11, 6, 6, 1, 0, 1, 1, 0, '2025-07-17 12:29:05', '2025-07-17 12:29:05'),
(12, 6, 7, 0, 0, 0, 0, 0, '2025-07-17 12:29:05', '2025-07-17 12:29:05'),
(13, 8, 10, 1, 1, 0, 0, 0, '2025-07-17 12:29:05', '2025-07-17 12:29:05'),
(14, 9, 6, 1, 0, 0, 0, 0, '2025-07-17 13:22:40', '2025-07-17 13:22:40'),
(15, 9, 7, 1, 0, 0, 0, 0, '2025-07-17 13:22:40', '2025-07-17 13:22:40'),
(16, 9, 8, 0, 1, 0, 0, 0, '2025-07-17 13:22:40', '2025-07-17 13:22:40'),
(17, 9, 9, 1, 0, 0, 0, 0, '2025-07-17 13:22:40', '2025-07-17 13:22:40');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` bigint UNSIGNED NOT NULL,
  `po_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purchase_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `supplier_contact` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `po_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('pending','approved','received','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_by` bigint UNSIGNED NOT NULL,
  `total_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `gst_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `final_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `shipping_address` text COLLATE utf8mb4_unicode_ci,
  `payment_mode` enum('cash','bank_transfer','cheque','credit') COLLATE utf8mb4_unicode_ci NOT NULL,
  `credit_days` int DEFAULT NULL,
  `shipping_cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `order_date` date DEFAULT NULL,
  `expected_delivery` date DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `approval_notes` text COLLATE utf8mb4_unicode_ci,
  `approval_threshold` decimal(10,2) NOT NULL DEFAULT '10000.00',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_orders`
--

INSERT INTO `purchase_orders` (`id`, `po_number`, `purchase_id`, `vendor_id`, `supplier_contact`, `po_date`, `status`, `created_by`, `total_amount`, `gst_amount`, `final_amount`, `shipping_address`, `payment_mode`, `credit_days`, `shipping_cost`, `order_date`, `expected_delivery`, `notes`, `created_at`, `updated_at`, `approved_by`, `approved_at`, `approval_notes`, `approval_threshold`, `deleted_at`) VALUES
(75, 'PO-2025-0074', NULL, 30, NULL, '2025-07-03 00:00:00', 'pending', 1, 2500.00, 450.00, 2950.00, 'pune', 'cash', NULL, 0.00, '2025-07-03', '2025-07-29', NULL, '2025-07-03 10:40:05', '2025-07-03 10:40:05', NULL, NULL, NULL, 10000.00, NULL),
(76, 'PO-2025-0076', NULL, 30, NULL, '2025-07-03 00:00:00', 'pending', 1, 500.00, 90.00, 590.00, 'pune', 'cash', NULL, 0.00, '2025-07-03', '2025-07-31', NULL, '2025-07-03 10:41:11', '2025-07-03 10:59:59', NULL, NULL, NULL, 10000.00, NULL),
(78, 'PO-2025-0077', NULL, 25, NULL, '2025-07-03 00:00:00', 'pending', 1, 600.00, 30.00, 630.00, 'pune', 'cash', NULL, 0.00, '2025-07-03', '2025-07-30', NULL, '2025-07-03 11:10:30', '2025-07-03 11:10:30', NULL, NULL, NULL, 10000.00, NULL),
(80, 'PO-2025-0079', NULL, 25, NULL, '2025-07-03 00:00:00', 'pending', 1, 300.00, 15.00, 315.00, 'pune', 'cash', NULL, 0.00, '2025-07-03', '2025-08-08', NULL, '2025-07-03 11:13:33', '2025-07-03 11:13:33', NULL, NULL, NULL, 10000.00, NULL),
(83, 'PO-2025-0081', NULL, 25, NULL, '2025-07-03 00:00:00', 'pending', 1, 300.00, 15.00, 315.00, 'fsdgtfhy', 'cash', NULL, 0.00, '2025-07-03', '2025-07-29', NULL, '2025-07-03 12:46:11', '2025-07-03 12:46:11', NULL, NULL, NULL, 10000.00, NULL),
(84, 'PO-2025-0084', NULL, 24, NULL, '2025-07-05 00:00:00', 'pending', 1, 5000.00, 900.00, 5900.00, 'pune', 'cash', NULL, 0.00, '2025-07-05', '2025-08-06', NULL, '2025-07-05 17:19:34', '2025-07-05 17:19:59', NULL, NULL, NULL, 10000.00, NULL),
(111, 'PO-202507-0107', NULL, 37, NULL, '2025-07-14 00:00:00', 'pending', 1, 3000.00, 540.00, 3540.00, 'ddfgfh', 'cash', NULL, 0.00, '2025-07-14', '2025-08-06', NULL, '2025-07-14 08:16:19', '2025-07-14 08:16:19', NULL, NULL, NULL, 10000.00, NULL),
(121, 'PO-2025-0112', NULL, 37, NULL, '2025-07-14 00:00:00', 'pending', 1, 3000.00, 540.00, 3540.00, 'pune', 'cash', NULL, 0.00, '2025-07-14', '2025-08-07', NULL, '2025-07-14 13:36:44', '2025-07-14 13:36:44', NULL, NULL, NULL, 10000.00, NULL),
(122, 'PO-2025-0122', NULL, 37, NULL, '2025-07-14 00:00:00', 'pending', 1, 2000.00, 360.00, 2360.00, 'pune', 'cash', NULL, 0.00, '2025-07-14', '2025-08-06', NULL, '2025-07-14 13:37:35', '2025-07-14 13:37:35', NULL, NULL, NULL, 10000.00, NULL),
(123, 'PO-2025-0123', NULL, 38, NULL, '2025-07-15 00:00:00', 'pending', 1, 150000.00, 27000.00, 177000.00, 'pune', 'cash', NULL, 0.00, '2025-07-15', '2025-08-02', NULL, '2025-07-15 16:35:53', '2025-07-15 16:35:53', NULL, NULL, NULL, 10000.00, NULL),
(124, 'PO-2025-0124', NULL, 40, NULL, '2025-07-22 00:00:00', 'pending', 1, 800.00, 144.00, 944.00, 'Pune', 'cash', NULL, 0.00, '2025-07-22', '2025-07-31', NULL, '2025-07-22 11:46:54', '2025-07-22 11:46:54', NULL, NULL, NULL, 10000.00, NULL),
(126, 'PO-2025-0125', NULL, 40, NULL, '2025-07-22 00:00:00', 'pending', 2, 2400.00, 432.00, 2832.00, 'pune', 'cash', NULL, 0.00, '2025-07-22', '2025-08-06', NULL, '2025-07-22 11:49:28', '2025-07-22 11:49:28', NULL, NULL, NULL, 10000.00, NULL),
(127, 'PO-2025-0127', NULL, 40, NULL, '2025-07-23 00:00:00', 'pending', 1, 1600.00, 288.00, 1888.00, 'pune', 'cash', NULL, 0.00, '2025-07-23', '2025-08-06', NULL, '2025-07-23 17:48:40', '2025-07-23 17:48:40', NULL, NULL, NULL, 10000.00, NULL),
(128, 'PO-2025-0128', NULL, 24, NULL, '2025-07-23 00:00:00', 'approved', 1, 8000.00, 1440.00, 9440.00, 'pune', 'cash', NULL, 0.00, '2025-07-23', '2025-07-25', 'hfdvkj', '2025-07-23 17:57:05', '2025-07-23 18:00:38', NULL, NULL, NULL, 10000.00, NULL),
(129, 'PO-2025-0129', NULL, 38, NULL, '2025-08-03 00:00:00', 'pending', 1, 25000.00, 6250.00, 31250.00, 'tf7inju64', 'cash', NULL, 0.00, '2025-08-03', '2025-08-14', NULL, '2025-08-03 03:04:31', '2025-08-03 03:04:31', NULL, NULL, NULL, 10000.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_items`
--

CREATE TABLE `purchase_order_items` (
  `id` bigint UNSIGNED NOT NULL,
  `purchase_order_id` bigint UNSIGNED NOT NULL,
  `item_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `quantity` int NOT NULL,
  `net_price` decimal(10,2) DEFAULT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `gst_rate` decimal(5,2) NOT NULL DEFAULT '18.00',
  `gst_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `final_amount` decimal(15,2) DEFAULT NULL,
  `batch_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `material_id` bigint UNSIGNED DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `available_qty` int NOT NULL DEFAULT '0',
  `remaining_qty` int NOT NULL DEFAULT '0',
  `dimensions` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `weight` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `batch_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_order_items`
--

INSERT INTO `purchase_order_items` (`id`, `purchase_order_id`, `item_name`, `description`, `quantity`, `net_price`, `unit_price`, `total_price`, `created_at`, `updated_at`, `gst_rate`, `gst_amount`, `final_amount`, `batch_number`, `expiry_date`, `material_id`, `total`, `available_qty`, `remaining_qty`, `dimensions`, `weight`, `batch_no`) VALUES
(121, 75, 'Phone A', NULL, 50, NULL, 50.00, 2950.00, '2025-07-03 10:40:05', '2025-07-03 10:40:05', 18.00, 0.00, NULL, NULL, NULL, 10, NULL, 60, 10, NULL, NULL, NULL),
(125, 76, 'Phone A', NULL, 10, NULL, 50.00, 590.00, '2025-07-03 10:59:59', '2025-07-03 10:59:59', 18.00, 0.00, NULL, NULL, NULL, 10, NULL, 60, 0, NULL, NULL, NULL),
(127, 78, 'sugar', NULL, 10, NULL, 60.00, 630.00, '2025-07-03 11:10:30', '2025-07-03 11:10:30', 5.00, 0.00, NULL, NULL, NULL, 5, NULL, 20, 10, NULL, NULL, NULL),
(129, 80, 'sugar', NULL, 5, NULL, 60.00, 315.00, '2025-07-03 11:13:33', '2025-07-03 11:13:33', 5.00, 0.00, NULL, NULL, NULL, 5, NULL, 20, 5, NULL, NULL, NULL),
(132, 83, 'sugar', NULL, 5, NULL, 60.00, 315.00, '2025-07-03 12:46:11', '2025-07-03 12:46:11', 5.00, 0.00, NULL, NULL, NULL, 5, NULL, 20, 0, NULL, NULL, NULL),
(134, 84, 'rava', NULL, 50, NULL, 100.00, 5900.00, '2025-07-05 17:19:59', '2025-07-05 17:19:59', 18.00, 0.00, NULL, NULL, NULL, 7, NULL, 80, 30, NULL, NULL, NULL),
(181, 111, 'Basmati rice', NULL, 30, NULL, 100.00, 3000.00, '2025-07-14 08:16:19', '2025-07-14 08:24:53', 18.00, 0.00, NULL, NULL, NULL, 12, NULL, 40, 10, NULL, NULL, NULL),
(191, 121, 'Basmati rice', NULL, 30, NULL, 100.00, 3540.00, '2025-07-14 13:36:44', '2025-07-14 13:36:44', 18.00, 0.00, NULL, NULL, NULL, 12, NULL, 50, 20, NULL, NULL, NULL),
(192, 122, 'Basmati rice', NULL, 20, NULL, 100.00, 2360.00, '2025-07-14 13:37:35', '2025-07-14 13:37:35', 18.00, 0.00, NULL, NULL, NULL, 12, NULL, 20, 0, NULL, NULL, NULL),
(193, 123, 'Phone A', NULL, 30, NULL, 5000.00, 177000.00, '2025-07-15 16:35:53', '2025-07-15 16:35:53', 18.00, 0.00, NULL, NULL, NULL, 10, NULL, 30, 0, NULL, NULL, NULL),
(194, 124, 'rava', NULL, 10, NULL, 80.00, 944.00, '2025-07-22 11:46:54', '2025-07-22 11:46:54', 18.00, 0.00, NULL, NULL, NULL, 7, NULL, 20, 10, NULL, NULL, NULL),
(197, 126, 'rava', NULL, 10, NULL, 80.00, 944.00, '2025-07-22 11:49:28', '2025-07-22 11:49:28', 18.00, 0.00, NULL, NULL, NULL, 7, NULL, 10, 0, NULL, NULL, NULL),
(198, 126, 'dal', NULL, 40, NULL, 40.00, 1888.00, '2025-07-22 11:49:28', '2025-07-22 11:49:28', 18.00, 0.00, NULL, NULL, NULL, 9, NULL, 80, 40, NULL, NULL, NULL),
(199, 127, 'dal', NULL, 40, NULL, 40.00, 1888.00, '2025-07-23 17:48:40', '2025-07-23 17:48:40', 18.00, 0.00, NULL, NULL, NULL, 9, NULL, 40, 0, NULL, NULL, NULL),
(200, 128, 'rava', NULL, 80, NULL, 100.00, 9440.00, '2025-07-23 17:57:05', '2025-07-23 17:57:05', 18.00, 0.00, NULL, NULL, NULL, 7, NULL, 80, 0, NULL, NULL, NULL),
(201, 129, 'test', NULL, 5, NULL, 5000.00, 31250.00, '2025-08-03 03:04:32', '2025-08-03 03:04:32', 25.00, 0.00, NULL, NULL, NULL, 8, NULL, 30, 25, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_item_details`
--

CREATE TABLE `purchase_order_item_details` (
  `id` bigint UNSIGNED NOT NULL,
  `purchase_order_item_id` bigint UNSIGNED NOT NULL,
  `gst_rate` decimal(5,2) NOT NULL DEFAULT '18.00',
  `gst_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `net_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `batch_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quality_analyses`
--

CREATE TABLE `quality_analyses` (
  `id` bigint UNSIGNED NOT NULL,
  `purchase_order_id` bigint UNSIGNED NOT NULL,
  `purchase_order_item_id` bigint UNSIGNED NOT NULL,
  `vendor_id` bigint UNSIGNED NOT NULL,
  `product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expected_volumetric_data` decimal(10,2) NOT NULL,
  `expected_weight` decimal(10,2) NOT NULL,
  `other_analysis_parameters` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `actual_volumetric_data` decimal(10,2) DEFAULT NULL,
  `actual_weight` decimal(10,2) DEFAULT NULL,
  `quantity_received` decimal(10,2) NOT NULL,
  `quality_status` enum('pending','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `remarks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `rejected_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `approved_by` bigint UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `barcode` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sku_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `batch_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `manufacturing_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quality_analyses`
--

INSERT INTO `quality_analyses` (`id`, `purchase_order_id`, `purchase_order_item_id`, `vendor_id`, `product_name`, `product_category`, `expected_volumetric_data`, `expected_weight`, `other_analysis_parameters`, `actual_volumetric_data`, `actual_weight`, `quantity_received`, `quality_status`, `remarks`, `rejected_reason`, `approved_by`, `approved_at`, `barcode`, `sku_id`, `batch_number`, `manufacturing_date`, `expiry_date`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, 83, 132, 25, 'sugar', 'raw material', 50.00, 20.00, NULL, 50.00, 50.00, 5.00, 'approved', NULL, NULL, 1, '2025-07-08 10:00:24', '2025070800015459', 'SUGRARAH0001', 'BATCH-250704-YVOC', '2025-07-10', '2025-07-29', 1, NULL, '2025-07-08 10:00:16', '2025-07-08 10:00:24'),
(2, 75, 121, 30, 'Phone A', 'Electrical', 50.00, 60.00, NULL, 50.00, 40.00, 50.00, 'approved', NULL, NULL, 1, '2025-07-08 10:46:11', '2025070800029527', 'PHOELTES0002', 'BATCH-250708-104550', '2025-07-24', '2025-08-06', 1, NULL, '2025-07-08 10:45:50', '2025-07-08 10:46:11'),
(3, 84, 134, 24, 'rava', 'material', 60.00, 60.00, NULL, 40.00, 50.00, 50.00, 'rejected', NULL, 'zdsfb', 1, '2025-07-09 13:33:50', '2025070900038067', 'RAVMATES0003', 'BATCH-250709-124524', '2025-08-06', '2025-08-07', 1, NULL, '2025-07-09 12:45:24', '2025-07-09 13:33:50'),
(4, 80, 129, 25, 'sugar', 'raw material', 5.00, 5.00, NULL, 4.00, 4.00, 5.00, 'approved', NULL, NULL, 1, '2025-07-09 13:33:26', '2025070900045295', 'SUGRARAH0004', 'BATCH-250709-133308', '2025-07-13', '2025-08-06', 1, NULL, '2025-07-09 13:33:08', '2025-07-09 13:33:26'),
(6, 78, 127, 25, 'sugar', 'raw material', 40.00, 20.00, NULL, 50.00, 40.00, 10.00, 'approved', NULL, NULL, 1, '2025-07-09 16:58:14', '2025070900069993', 'SUGRARAH0006', 'BATCH-250709-165801', '2025-07-22', '2025-08-08', 1, NULL, '2025-07-09 16:58:01', '2025-07-09 16:58:14'),
(7, 76, 125, 30, 'Phone A', 'Electrical', 50.00, 19.98, NULL, 40.00, 20.00, 10.00, 'approved', NULL, NULL, 1, '2025-07-10 11:37:52', '2025071000075657', 'PHOELTES0007', 'BATCH-250710-113722', '2025-07-11', '2025-08-07', 1, 1, '2025-07-10 11:37:22', '2025-07-10 11:37:52'),
(10, 121, 191, 37, 'Basmati rice', 'material', 50.00, 40.00, NULL, 50.00, 50.00, 30.00, 'approved', NULL, NULL, 1, '2025-07-22 12:03:01', '2025072100101653', 'BASMAVEN0010', 'BATCH-250721-174357', '2025-07-31', '2025-08-06', 6, NULL, '2025-07-21 17:43:57', '2025-07-22 12:03:01'),
(11, 123, 193, 38, 'Phone A', 'Electrical', 10.00, 20.00, NULL, 50.00, 50.00, 30.00, 'rejected', NULL, 'abcd', 1, '2025-07-22 12:02:46', '2025072200118695', 'PHOELVEN0011', 'BATCH-250722-115857', '2025-07-24', '2025-08-08', 1, NULL, '2025-07-22 11:58:57', '2025-07-22 12:02:46'),
(12, 111, 181, 37, 'Basmati rice', 'material', 20.00, 20.00, NULL, 10.00, 20.00, 30.00, 'pending', NULL, NULL, NULL, NULL, '2025072200122971', 'BASMAVEN0012', 'BATCH-250722-152855', '2025-07-30', '2025-08-09', 1, NULL, '2025-07-22 15:28:55', '2025-07-22 15:28:55'),
(13, 122, 192, 37, 'Basmati rice', 'material', 2000.00, 2000.00, NULL, 2000.00, 2000.00, 20.00, 'approved', NULL, NULL, 1, '2025-07-24 12:29:20', '2025072400138725', 'BASMAVEN0013', 'BATCH-250724-113139', '2025-08-06', '2025-08-07', 1, NULL, '2025-07-24 11:31:39', '2025-07-24 12:29:20'),
(14, 128, 200, 24, 'rava', 'material', 14000.00, 14000.00, NULL, 500.00, 7600.00, 80.00, 'rejected', NULL, 'fdg', 1, '2025-07-24 11:34:29', '2025072400149964', 'RAVMATES0014', 'BATCH-250724-113338', '2025-08-07', '2025-08-07', 1, NULL, '2025-07-24 11:33:38', '2025-07-24 11:34:29'),
(15, 127, 199, 40, 'dal', 'raw material', 8400.00, 8400.00, NULL, 8400.00, 8400.00, 40.00, 'pending', NULL, NULL, NULL, NULL, '2025072400152417', 'DALRATES0015', 'BATCH-250724-144716', '2025-08-06', '2025-08-09', 1, NULL, '2025-07-24 14:47:16', '2025-07-24 14:47:16');

-- --------------------------------------------------------

--
-- Table structure for table `report_logs`
--

CREATE TABLE `report_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `report_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `report_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `filters_applied` json DEFAULT NULL,
  `date_from` date DEFAULT NULL,
  `date_to` date DEFAULT NULL,
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `generated_by` bigint UNSIGNED DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permission` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Andaman and Nicobar Islands', NULL, NULL),
(2, 'Andhra Pradesh', NULL, NULL),
(3, 'Arunachal Pradesh', NULL, NULL),
(4, 'Assam', NULL, NULL),
(5, 'Bihar', NULL, NULL),
(6, 'Chandigarh', NULL, NULL),
(7, 'Chhattisgarh', NULL, NULL),
(8, 'Dadra and Nagar Haveli and Daman and Diu', NULL, NULL),
(9, 'Delhi', NULL, NULL),
(10, 'Goa', NULL, NULL),
(11, 'Gujarat', NULL, NULL),
(12, 'Haryana', NULL, NULL),
(13, 'Himachal Pradesh', NULL, NULL),
(14, 'Jammu and Kashmir', NULL, NULL),
(15, 'Jharkhand', NULL, NULL),
(16, 'Karnataka', NULL, NULL),
(17, 'Kerala', NULL, NULL),
(18, 'Ladakh', NULL, NULL),
(19, 'Lakshadweep', NULL, NULL),
(20, 'Madhya Pradesh', NULL, NULL),
(21, 'Maharashtra', NULL, NULL),
(22, 'Manipur', NULL, NULL),
(23, 'Meghalaya', NULL, NULL),
(24, 'Mizoram', NULL, NULL),
(25, 'Nagaland', NULL, NULL),
(26, 'Odisha', NULL, NULL),
(27, 'Puducherry', NULL, NULL),
(28, 'Punjab', NULL, NULL),
(29, 'Rajasthan', NULL, NULL),
(30, 'Sikkim', NULL, NULL),
(31, 'Tamil Nadu', NULL, NULL),
(32, 'Telangana', NULL, NULL),
(33, 'Tripura', NULL, NULL),
(34, 'Uttar Pradesh', NULL, NULL),
(35, 'Uttarakhand', NULL, NULL),
(36, 'West Bengal', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','purchase_team','inventory_manager','user') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `email_verified_at`, `last_login_at`, `password`, `is_active`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin User', 'admin@inventory.com', 'admin', '2025-06-24 09:53:45', NULL, '$2y$12$doOErg7dX7WgUYSREsBCu.LrLPwkagHrL/OqdDQAfMSchCSHLzmCa', 1, NULL, '2025-06-24 09:53:45', '2025-06-24 16:09:42'),
(2, 'Purchase User', 'purchase@inventory.com', 'purchase_team', '2025-06-24 09:53:45', NULL, '$2y$12$.BYueiG4fVQuEFuSnroEKejOvsUe1GPPwqp47XB10LhTefXotjDl2', 1, NULL, '2025-06-24 09:53:45', '2025-06-24 16:10:22'),
(3, 'Inventory User', 'inventory@inventory.com', 'inventory_manager', '2025-06-24 09:53:45', NULL, '$2y$12$RWvsYLb0hYqgwC1NHpCST.yD3mE2m7ub.OpsEttjT2PSKCLpxxy9G', 1, NULL, '2025-06-24 09:53:45', '2025-06-24 16:10:53'),
(6, 'Nikita', 'nikitashinde01598@gmail.com', 'user', NULL, NULL, '$2y$12$r8IuswJDJGtUga2ppLOlgODYDcKw2xYhhm6AewWw0y7mAZVFeFskm', 1, NULL, '2025-07-16 10:44:29', '2025-07-16 10:44:29'),
(8, 'Priya', 'p@gmail.com', 'user', NULL, NULL, '$2y$12$k3zSpFWLCAkJFiGU5QPwgu2S0uKz7R/qVDcGfiQfnfAlWlqdtM.4y', 1, NULL, '2025-07-17 12:29:05', '2025-07-17 12:29:05'),
(9, 'ram', 'ram@gmail.com', 'purchase_team', NULL, NULL, '$2y$12$OP6dVcgtmlXXHJgIuxSBZez38NDJhO68Glt.sA3ZJhyvKbyOvdbZS', 1, NULL, '2025-07-17 13:22:40', '2025-07-21 15:59:14');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `business_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_address` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_pincode` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_country` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `warehouse_address` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `warehouse_state` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `warehouse_city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `warehouse_pincode` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `warehouse_country` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_holder_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `branch_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ifsc_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `name`, `business_name`, `email`, `phone`, `company_address`, `company_state`, `company_city`, `company_pincode`, `company_country`, `warehouse_address`, `warehouse_state`, `warehouse_city`, `warehouse_pincode`, `warehouse_country`, `bank_holder_name`, `branch_name`, `bank_name`, `account_number`, `ifsc_code`, `address`, `created_at`, `updated_at`) VALUES
(24, 'test', 'test', 'test@gmail.com', '9856879656', 'Ahmednagar', NULL, NULL, NULL, NULL, 'katraj ,pune', NULL, NULL, NULL, NULL, 'Rahul Sharma', 'PARK STREET', 'HDFC Bank', '50100234567890', 'HDFC0001234', NULL, '2025-06-26 15:38:18', '2025-06-26 15:38:18'),
(25, 'Rahul Sharma', 'Sharma Industrial Supplies', 'rahul.sharma@gmail.com', '9785693656', 'Plot 21, Phase 2, Udyog Vihar, Gurugram, Haryana – 122016', NULL, NULL, NULL, NULL, 'Block C, Sector 63, Noida, Uttar Pradesh – 201301', NULL, NULL, NULL, NULL, 'Rahul Sharma', 'HAJIGANJ', 'State Bank of India', '50100234567890', 'SBIN0001234', NULL, '2025-06-26 15:45:29', '2025-06-26 15:45:29'),
(30, 'test', 'sp supplier', 'test@gmail.com', '9856879656', 'Ahmednagar', NULL, NULL, NULL, NULL, '123,nashik', NULL, NULL, NULL, NULL, 'Rahul Sharma', 'DELHI - SHAHDARA', 'HDFC Bank', '896532567864532632', 'HDFC0000585', NULL, '2025-06-27 15:27:03', '2025-06-27 15:27:03'),
(37, 'vendor', 'test', 'test@gmail.com', '9563256987', 'testtest,saergh', '15', '371', '414106', 'India', 'dfgfhgjfxfgzvsf', NULL, NULL, NULL, 'India', 'Mayuri Shaikh', 'Pune branch', 'State Bank of India', '896532567864532632', 'SBIN0001234', NULL, '2025-07-10 10:27:45', '2025-07-23 17:04:45'),
(38, 'Vendor Singh', 'Supply Provider', 'vendor@gmail.com', '6934567891', 'Leaf Soc. Tilekar Nagar, Pune', '21', '2', '411043', 'India', 'Mahesh Soc. Chintamani nagar 3', '21', '2', '411043', 'India', 'Vendor Singh', 'PARK STREET', 'HDFC Bank', '123456789', 'HDFC0001234', NULL, '2025-07-10 18:44:45', '2025-07-23 15:07:04'),
(40, 'test', 'test', 'test@gmail.com', '9856478956', 'Ahmednagar', '21', '14', '414106', 'India', 'Ahmednagar\r\n-', '21', '14', '414106', 'India', 'Mayuri Shaikh', 'KIDWAI NAGAR', 'HDFC Bank', '346578746599456', 'HDFC0001238', NULL, '2025-07-11 17:02:46', '2025-07-11 17:28:07');

-- --------------------------------------------------------

--
-- Table structure for table `warehouses`
--

CREATE TABLE `warehouses` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('main','cold_storage','transit','distribution','storage','temporary') COLLATE utf8mb4_unicode_ci NOT NULL,
  `capacity` decimal(10,2) DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `current_load` decimal(10,2) NOT NULL DEFAULT '0.00',
  `available_space` decimal(10,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `warehouses`
--

INSERT INTO `warehouses` (`id`, `name`, `address`, `city`, `state`, `contact_phone`, `contact_email`, `type`, `capacity`, `is_default`, `is_active`, `created_at`, `updated_at`, `deleted_at`, `current_load`, `available_space`) VALUES
(2, 'Mumbai Central Warehouse', 'Plot 21, MIDC Industrial Area, Andheri East', 'Mumbai', 'Maharashtra', '9876543210', 'mumbai@warehouse.in', 'distribution', 299.00, 0, 1, '2025-06-24 18:25:06', '2025-07-23 15:08:11', NULL, 65.00, 234.00),
(3, 'Delhi Storage Facility', 'Warehouse No. 12, Okhla Phase II', 'New Delhi', 'Delhi', '9123456789', 'delhi@warehouse.in', 'cold_storage', 200.00, 0, 1, '2025-06-24 18:25:06', '2025-07-23 15:07:27', NULL, 10.00, 190.00),
(4, 'Bangalore Temp Hub', '#78, Electronic City Phase I', 'Kasaragod', 'Kerala', '9988776655', 'bangalore@warehouse.in', 'temporary', 100.00, 0, 0, '2025-06-24 18:25:06', '2025-07-05 17:12:41', NULL, 0.00, 0.00),
(5, 'test', 'testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest', 'Bishnupur', 'Manipur', '9876543245', 'test@asd.test', 'temporary', 100.00, 0, 0, '2025-06-25 15:24:59', '2025-06-26 11:16:52', '2025-06-26 11:16:52', 0.00, 0.00),
(6, 'zcdsf', '5263zcdgfgfnhmn', 'Dombivli', 'Maharashtra', '8645643655', 'nikita@gmail.com', 'main', 52.00, 0, 1, '2025-07-05 17:03:57', '2025-07-05 17:04:02', '2025-07-05 17:04:02', 0.00, 0.00),
(7, 'daergthryj', 'At- Dagadwadi, Post- Karanji, Tal- Pathardi, Dist- Ahmednagar\r\n-', 'Ahmednagar', 'Maharashtra', '8459329698', 'nikita@gmail.com', 'main', 688.00, 0, 1, '2025-07-10 10:14:31', '2025-07-10 10:14:45', '2025-07-10 10:14:45', 0.00, 0.00),
(8, 'Rajdhani Complex  Shankar Maharaj Math', 'Shop-3. Rajdhani Complex near Shankar Maharaj Math\r\nPune Satara Road, Dhanakawadi', 'Pune', 'Maharashtra', '6447261275', 'abc@gmail.com', 'main', 10.00, 1, 1, '2025-07-10 18:31:08', '2025-07-22 17:03:26', NULL, 0.00, 10.00),
(9, 'cdsvfdb', 'xczdsvfbdgndgyr', 'Palakkad', 'Kerala', '8459329698', 'nikita@gmail.com', 'temporary', 415.00, 0, 1, '2025-07-21 11:34:16', '2025-07-21 11:41:20', '2025-07-21 11:41:20', 0.00, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `warehouse_blocks`
--

CREATE TABLE `warehouse_blocks` (
  `id` bigint UNSIGNED NOT NULL,
  `warehouse_id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rows` int NOT NULL,
  `columns` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `warehouse_blocks`
--

INSERT INTO `warehouse_blocks` (`id`, `warehouse_id`, `name`, `rows`, `columns`, `created_at`, `updated_at`) VALUES
(2, 2, 'Block A1', 5, 5, '2025-07-04 12:47:55', '2025-07-21 11:48:44'),
(3, 2, 'BlockA2', 2, 3, '2025-07-04 13:05:29', '2025-07-04 13:05:29'),
(5, 3, 'Bolck B', 3, 4, '2025-07-04 17:59:58', '2025-07-04 17:59:58'),
(7, 2, 'Block B', 1, 3, '2025-07-05 11:49:22', '2025-07-05 11:49:22'),
(8, 2, 'Rack a', 4, 4, '2025-07-10 15:23:30', '2025-07-10 15:23:30'),
(9, 8, 'Block c', 5, 5, '2025-07-12 10:57:35', '2025-07-12 10:57:35'),
(11, 4, 'Block A1', 2, 3, '2025-07-22 17:00:54', '2025-07-22 17:00:54');

-- --------------------------------------------------------

--
-- Table structure for table `warehouse_slots`
--

CREATE TABLE `warehouse_slots` (
  `id` bigint UNSIGNED NOT NULL,
  `block_id` bigint UNSIGNED NOT NULL,
  `row` int NOT NULL,
  `column` int NOT NULL,
  `status` enum('empty','partial','full') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'empty',
  `batch_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `warehouse_slots`
--

INSERT INTO `warehouse_slots` (`id`, `block_id`, `row`, `column`, `status`, `batch_id`, `created_at`, `updated_at`) VALUES
(50, 3, 1, 1, 'empty', NULL, '2025-07-04 13:05:29', '2025-07-04 13:05:29'),
(51, 3, 1, 2, 'empty', NULL, '2025-07-04 13:05:29', '2025-07-04 13:05:29'),
(52, 3, 1, 3, 'empty', NULL, '2025-07-04 13:05:29', '2025-07-04 13:05:29'),
(53, 3, 2, 1, 'empty', NULL, '2025-07-04 13:05:29', '2025-07-04 13:05:29'),
(54, 3, 2, 2, 'empty', NULL, '2025-07-04 13:05:29', '2025-07-04 13:05:29'),
(55, 3, 2, 3, 'empty', NULL, '2025-07-04 13:05:29', '2025-07-04 13:05:29'),
(66, 5, 1, 1, 'full', NULL, '2025-07-04 17:59:58', '2025-07-05 17:03:03'),
(67, 5, 1, 2, 'full', 81, '2025-07-04 17:59:58', '2025-07-22 17:04:37'),
(68, 5, 1, 3, 'full', 82, '2025-07-04 17:59:58', '2025-07-23 15:07:27'),
(69, 5, 1, 4, 'empty', NULL, '2025-07-04 17:59:58', '2025-07-04 17:59:58'),
(70, 5, 2, 1, 'empty', NULL, '2025-07-04 17:59:58', '2025-07-04 17:59:58'),
(71, 5, 2, 2, 'empty', NULL, '2025-07-04 17:59:58', '2025-07-04 17:59:58'),
(72, 5, 2, 3, 'empty', NULL, '2025-07-04 17:59:58', '2025-07-04 17:59:58'),
(73, 5, 2, 4, 'empty', NULL, '2025-07-04 17:59:58', '2025-07-04 17:59:58'),
(74, 5, 3, 1, 'empty', NULL, '2025-07-04 17:59:58', '2025-07-04 17:59:58'),
(75, 5, 3, 2, 'empty', NULL, '2025-07-04 17:59:58', '2025-07-04 17:59:58'),
(76, 5, 3, 3, 'empty', NULL, '2025-07-04 17:59:58', '2025-07-04 17:59:58'),
(77, 5, 3, 4, 'empty', NULL, '2025-07-04 17:59:58', '2025-07-04 17:59:58'),
(153, 7, 1, 1, 'empty', NULL, '2025-07-05 11:49:22', '2025-07-05 11:49:22'),
(154, 7, 1, 2, 'empty', NULL, '2025-07-05 11:49:22', '2025-07-05 11:49:22'),
(155, 7, 1, 3, 'empty', NULL, '2025-07-05 11:49:22', '2025-07-05 11:49:22'),
(156, 8, 1, 1, 'empty', NULL, '2025-07-10 15:23:30', '2025-07-10 15:23:30'),
(157, 8, 1, 2, 'empty', NULL, '2025-07-10 15:23:30', '2025-07-10 15:23:30'),
(158, 8, 1, 3, 'empty', NULL, '2025-07-10 15:23:30', '2025-07-10 15:23:30'),
(159, 8, 1, 4, 'empty', NULL, '2025-07-10 15:23:30', '2025-07-10 15:23:30'),
(160, 8, 2, 1, 'empty', NULL, '2025-07-10 15:23:30', '2025-07-10 15:23:30'),
(161, 8, 2, 2, 'empty', NULL, '2025-07-10 15:23:30', '2025-07-10 15:23:30'),
(162, 8, 2, 3, 'empty', NULL, '2025-07-10 15:23:30', '2025-07-10 15:23:30'),
(163, 8, 2, 4, 'empty', NULL, '2025-07-10 15:23:30', '2025-07-10 15:23:30'),
(164, 8, 3, 1, 'empty', NULL, '2025-07-10 15:23:30', '2025-07-10 15:23:30'),
(165, 8, 3, 2, 'empty', NULL, '2025-07-10 15:23:30', '2025-07-10 15:23:30'),
(166, 8, 3, 3, 'empty', NULL, '2025-07-10 15:23:30', '2025-07-10 15:23:30'),
(167, 8, 3, 4, 'empty', NULL, '2025-07-10 15:23:30', '2025-07-10 15:23:30'),
(168, 8, 4, 1, 'empty', NULL, '2025-07-10 15:23:30', '2025-07-10 15:23:30'),
(169, 8, 4, 2, 'empty', NULL, '2025-07-10 15:23:30', '2025-07-10 15:23:30'),
(170, 8, 4, 3, 'empty', NULL, '2025-07-10 15:23:30', '2025-07-10 15:23:30'),
(171, 8, 4, 4, 'empty', NULL, '2025-07-10 15:23:30', '2025-07-10 15:23:30'),
(172, 9, 1, 1, 'full', 80, '2025-07-12 10:57:35', '2025-07-22 17:03:26'),
(173, 9, 1, 2, 'empty', NULL, '2025-07-12 10:57:35', '2025-07-12 10:57:35'),
(174, 9, 1, 3, 'empty', NULL, '2025-07-12 10:57:35', '2025-07-12 10:57:35'),
(175, 9, 1, 4, 'empty', NULL, '2025-07-12 10:57:35', '2025-07-12 10:57:35'),
(176, 9, 1, 5, 'empty', NULL, '2025-07-12 10:57:35', '2025-07-12 10:57:35'),
(177, 9, 2, 1, 'empty', NULL, '2025-07-12 10:57:35', '2025-07-12 10:57:35'),
(178, 9, 2, 2, 'empty', NULL, '2025-07-12 10:57:35', '2025-07-12 10:57:35'),
(179, 9, 2, 3, 'empty', NULL, '2025-07-12 10:57:35', '2025-07-12 10:57:35'),
(180, 9, 2, 4, 'empty', NULL, '2025-07-12 10:57:35', '2025-07-12 10:57:35'),
(181, 9, 2, 5, 'empty', NULL, '2025-07-12 10:57:35', '2025-07-12 10:57:35'),
(182, 9, 3, 1, 'empty', NULL, '2025-07-12 10:57:35', '2025-07-12 10:57:35'),
(183, 9, 3, 2, 'empty', NULL, '2025-07-12 10:57:35', '2025-07-12 10:57:35'),
(184, 9, 3, 3, 'empty', NULL, '2025-07-12 10:57:35', '2025-07-12 10:57:35'),
(185, 9, 3, 4, 'empty', NULL, '2025-07-12 10:57:35', '2025-07-12 10:57:35'),
(186, 9, 3, 5, 'empty', NULL, '2025-07-12 10:57:35', '2025-07-12 10:57:35'),
(187, 9, 4, 1, 'empty', NULL, '2025-07-12 10:57:35', '2025-07-12 10:57:35'),
(188, 9, 4, 2, 'empty', NULL, '2025-07-12 10:57:35', '2025-07-12 10:57:35'),
(189, 9, 4, 3, 'empty', NULL, '2025-07-12 10:57:35', '2025-07-12 10:57:35'),
(190, 9, 4, 4, 'empty', NULL, '2025-07-12 10:57:35', '2025-07-12 10:57:35'),
(191, 9, 4, 5, 'empty', NULL, '2025-07-12 10:57:35', '2025-07-12 10:57:35'),
(192, 9, 5, 1, 'empty', NULL, '2025-07-12 10:57:35', '2025-07-12 10:57:35'),
(193, 9, 5, 2, 'empty', NULL, '2025-07-12 10:57:35', '2025-07-12 10:57:35'),
(194, 9, 5, 3, 'empty', NULL, '2025-07-12 10:57:35', '2025-07-12 10:57:35'),
(195, 9, 5, 4, 'empty', NULL, '2025-07-12 10:57:35', '2025-07-12 10:57:35'),
(196, 9, 5, 5, 'empty', NULL, '2025-07-12 10:57:35', '2025-07-12 10:57:35'),
(197, 2, 1, 1, 'full', 79, '2025-07-21 11:48:44', '2025-07-22 16:57:41'),
(198, 2, 1, 2, 'full', 83, '2025-07-21 11:48:44', '2025-07-23 15:08:11'),
(199, 2, 1, 3, 'empty', NULL, '2025-07-21 11:48:44', '2025-07-21 11:48:44'),
(200, 2, 1, 4, 'empty', NULL, '2025-07-21 11:48:44', '2025-07-21 11:48:44'),
(201, 2, 1, 5, 'empty', NULL, '2025-07-21 11:48:44', '2025-07-21 11:48:44'),
(202, 2, 2, 1, 'empty', NULL, '2025-07-21 11:48:44', '2025-07-21 11:48:44'),
(203, 2, 2, 2, 'empty', NULL, '2025-07-21 11:48:44', '2025-07-21 11:48:44'),
(204, 2, 2, 3, 'empty', NULL, '2025-07-21 11:48:44', '2025-07-21 11:48:44'),
(205, 2, 2, 4, 'empty', NULL, '2025-07-21 11:48:44', '2025-07-21 11:48:44'),
(206, 2, 2, 5, 'empty', NULL, '2025-07-21 11:48:44', '2025-07-21 11:48:44'),
(207, 2, 3, 1, 'empty', NULL, '2025-07-21 11:48:44', '2025-07-21 11:48:44'),
(208, 2, 3, 2, 'empty', NULL, '2025-07-21 11:48:44', '2025-07-21 11:48:44'),
(209, 2, 3, 3, 'empty', NULL, '2025-07-21 11:48:44', '2025-07-21 11:48:44'),
(210, 2, 3, 4, 'empty', NULL, '2025-07-21 11:48:44', '2025-07-21 11:48:44'),
(211, 2, 3, 5, 'empty', NULL, '2025-07-21 11:48:44', '2025-07-21 11:48:44'),
(212, 2, 4, 1, 'empty', NULL, '2025-07-21 11:48:44', '2025-07-21 11:48:44'),
(213, 2, 4, 2, 'empty', NULL, '2025-07-21 11:48:44', '2025-07-21 11:48:44'),
(214, 2, 4, 3, 'empty', NULL, '2025-07-21 11:48:44', '2025-07-21 11:48:44'),
(215, 2, 4, 4, 'empty', NULL, '2025-07-21 11:48:44', '2025-07-21 11:48:44'),
(216, 2, 4, 5, 'empty', NULL, '2025-07-21 11:48:44', '2025-07-21 11:48:44'),
(217, 2, 5, 1, 'empty', NULL, '2025-07-21 11:48:44', '2025-07-21 11:48:44'),
(218, 2, 5, 2, 'empty', NULL, '2025-07-21 11:48:44', '2025-07-21 11:48:44'),
(219, 2, 5, 3, 'empty', NULL, '2025-07-21 11:48:44', '2025-07-21 11:48:44'),
(220, 2, 5, 4, 'empty', NULL, '2025-07-21 11:48:44', '2025-07-21 11:48:44'),
(221, 2, 5, 5, 'empty', NULL, '2025-07-21 11:48:44', '2025-07-21 11:48:44'),
(242, 11, 1, 1, 'empty', NULL, '2025-07-22 17:00:54', '2025-07-22 17:00:54'),
(243, 11, 1, 2, 'empty', NULL, '2025-07-22 17:00:54', '2025-07-22 17:00:54'),
(244, 11, 1, 3, 'empty', NULL, '2025-07-22 17:00:54', '2025-07-22 17:00:54'),
(245, 11, 2, 1, 'empty', NULL, '2025-07-22 17:00:54', '2025-07-22 17:00:54'),
(246, 11, 2, 2, 'empty', NULL, '2025-07-22 17:00:54', '2025-07-22 17:00:54'),
(247, 11, 2, 3, 'empty', NULL, '2025-07-22 17:00:54', '2025-07-22 17:00:54');

-- --------------------------------------------------------

--
-- Table structure for table `warehouse_users`
--

CREATE TABLE `warehouse_users` (
  `id` bigint UNSIGNED NOT NULL,
  `warehouse_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `role` enum('view_only','editor','manager','admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'view_only',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `warehouse_users`
--

INSERT INTO `warehouse_users` (`id`, `warehouse_id`, `user_id`, `role`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'admin', '2025-06-24 18:25:54', '2025-06-24 18:25:54'),
(2, 8, 6, 'view_only', '2025-07-21 13:24:39', '2025-07-21 13:24:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject` (`subject_type`,`subject_id`),
  ADD KEY `causer` (`causer_type`,`causer_id`),
  ADD KEY `activity_log_log_name_index` (`log_name`);

--
-- Indexes for table `barcodes`
--
ALTER TABLE `barcodes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cities_state_id_foreign` (`state_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `inventory_batches`
--
ALTER TABLE `inventory_batches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `inventory_batches_batch_number_unique` (`batch_number`),
  ADD KEY `inventory_batches_purchase_order_id_foreign` (`purchase_order_id`),
  ADD KEY `inventory_batches_material_id_foreign` (`material_id`);

--
-- Indexes for table `inventory_items`
--
ALTER TABLE `inventory_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_items_material_id_foreign` (`material_id`),
  ADD KEY `inventory_items_batch_id_foreign` (`batch_id`);

--
-- Indexes for table `inventory_transactions`
--
ALTER TABLE `inventory_transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `inventory_transactions_transaction_id_unique` (`transaction_id`),
  ADD KEY `inventory_transactions_batch_id_foreign` (`batch_id`);

--
-- Indexes for table `materials`
--
ALTER TABLE `materials`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `materials_code_unique` (`code`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD UNIQUE KEY `barcode` (`barcode`),
  ADD KEY `materials_sku_index` (`sku`),
  ADD KEY `materials_barcode_index` (`barcode`),
  ADD KEY `materials_vendor_id_foreign` (`vendor_id`);

--
-- Indexes for table `material_requests`
--
ALTER TABLE `material_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `material_requests_requested_by_foreign` (`requested_by`);

--
-- Indexes for table `material_vendor`
--
ALTER TABLE `material_vendor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vendor_id` (`vendor_id`),
  ADD KEY `material_id` (`material_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_module` (`user_id`,`module_id`),
  ADD KEY `fk_permissions_module` (`module_id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `purchase_orders_po_number_unique` (`po_number`),
  ADD KEY `purchase_orders_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `batch_no` (`batch_no`);

--
-- Indexes for table `quality_analyses`
--
ALTER TABLE `quality_analyses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `barcode` (`barcode`),
  ADD UNIQUE KEY `sku_id` (`sku_id`),
  ADD KEY `fk_qa_purchase_order_item_id` (`purchase_order_item_id`),
  ADD KEY `fk_qa_approved_by` (`approved_by`),
  ADD KEY `fk_qa_created_by` (`created_by`),
  ADD KEY `fk_qa_updated_by` (`updated_by`),
  ADD KEY `idx_qa_quality_status_created_at` (`quality_status`,`created_at`),
  ADD KEY `idx_qa_vendor_id_quality_status` (`vendor_id`,`quality_status`),
  ADD KEY `idx_qa_po_id_quality_status` (`purchase_order_id`,`quality_status`),
  ADD KEY `idx_qa_barcode` (`barcode`),
  ADD KEY `idx_qa_sku_id` (`sku_id`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_permissions_role_permission_unique` (`role`,`permission`),
  ADD KEY `role_permissions_role_index` (`role`),
  ADD KEY `role_permissions_permission_index` (`permission`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `warehouses_is_active_is_default_index` (`is_active`,`is_default`),
  ADD KEY `warehouses_city_state_index` (`city`,`state`);

--
-- Indexes for table `warehouse_blocks`
--
ALTER TABLE `warehouse_blocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `warehouse_id` (`warehouse_id`);

--
-- Indexes for table `warehouse_slots`
--
ALTER TABLE `warehouse_slots`
  ADD PRIMARY KEY (`id`),
  ADD KEY `block_id` (`block_id`),
  ADD KEY `batch_id` (`batch_id`);

--
-- Indexes for table `warehouse_users`
--
ALTER TABLE `warehouse_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `warehouse_users_warehouse_id_user_id_unique` (`warehouse_id`,`user_id`),
  ADD KEY `warehouse_users_user_id_foreign` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `barcodes`
--
ALTER TABLE `barcodes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=399;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_batches`
--
ALTER TABLE `inventory_batches`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=84;

--
-- AUTO_INCREMENT for table `inventory_transactions`
--
ALTER TABLE `inventory_transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `materials`
--
ALTER TABLE `materials`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `material_requests`
--
ALTER TABLE `material_requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `material_vendor`
--
ALTER TABLE `material_vendor`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=130;

--
-- AUTO_INCREMENT for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=202;

--
-- AUTO_INCREMENT for table `quality_analyses`
--
ALTER TABLE `quality_analyses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `warehouse_blocks`
--
ALTER TABLE `warehouse_blocks`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `warehouse_slots`
--
ALTER TABLE `warehouse_slots`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=248;

--
-- AUTO_INCREMENT for table `warehouse_users`
--
ALTER TABLE `warehouse_users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cities`
--
ALTER TABLE `cities`
  ADD CONSTRAINT `cities_state_id_foreign` FOREIGN KEY (`state_id`) REFERENCES `states` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `materials`
--
ALTER TABLE `materials`
  ADD CONSTRAINT `materials_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `material_requests`
--
ALTER TABLE `material_requests`
  ADD CONSTRAINT `material_requests_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `material_vendor`
--
ALTER TABLE `material_vendor`
  ADD CONSTRAINT `material_vendor_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `material_vendor_ibfk_2` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `permissions`
--
ALTER TABLE `permissions`
  ADD CONSTRAINT `fk_permissions_module` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_permissions_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD CONSTRAINT `purchase_orders_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `quality_analyses`
--
ALTER TABLE `quality_analyses`
  ADD CONSTRAINT `fk_qa_approved_by` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_qa_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_qa_purchase_order_id` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_qa_purchase_order_item_id` FOREIGN KEY (`purchase_order_item_id`) REFERENCES `purchase_order_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_qa_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_qa_vendor_id` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `warehouse_blocks`
--
ALTER TABLE `warehouse_blocks`
  ADD CONSTRAINT `warehouse_blocks_ibfk_1` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `warehouse_slots`
--
ALTER TABLE `warehouse_slots`
  ADD CONSTRAINT `warehouse_slots_ibfk_1` FOREIGN KEY (`block_id`) REFERENCES `warehouse_blocks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `warehouse_slots_ibfk_2` FOREIGN KEY (`batch_id`) REFERENCES `inventory_batches` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `warehouse_users`
--
ALTER TABLE `warehouse_users`
  ADD CONSTRAINT `warehouse_users_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `warehouse_users_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
