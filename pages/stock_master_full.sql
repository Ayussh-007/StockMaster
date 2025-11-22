-- phpMyAdmin SQL Dump
-- Updated for StockMaster Inventory System

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Database: `stock_master`

-- --------------------------------------------------------

-- Table: `login_credentials` (Existing)
CREATE TABLE `login_credentials` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login_id` varchar(12) NOT NULL,
  `email_id` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `timestamp` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `login_id` (`login_id`),
  UNIQUE KEY `email_id` (`email_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for `login_credentials`
INSERT INTO `login_credentials` (`id`, `login_id`, `email_id`, `password`, `timestamp`) VALUES
(4, 'Manthan2007', 'railkarmanthan@gmail.com', '$2y$10$kxQYe5gIMZYC8fFh0/ClWOL4ZO5EbUuJTz0dBooOeXukl3Zx9TkBa', '2025-11-22 11:35:57'),
(5, 'AyushOP', 'mhatreayush@icloud.com', '$2y$10$IZokq.nBtU7lYY562.bzd.33uywoS1po51kEOgay1bJoeyNrwvZ4u', '2025-11-22 13:43:47');

-- --------------------------------------------------------

-- Table: `password_reset_otp` (Existing)
CREATE TABLE `password_reset_otp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email_id` varchar(255) NOT NULL,
  `otp` varchar(6) NOT NULL,
  `expires_at` datetime NOT NULL,
  `is_used` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `password_reset_otp` (`id`, `email_id`, `otp`, `expires_at`, `is_used`, `created_at`) VALUES
(5, 'railkarmanthan@gmail.com', '263060', '2025-11-22 09:24:48', 0, '2025-11-22 08:14:48');

-- --------------------------------------------------------

-- Table: `products` (New)
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `internal_ref` varchar(50) DEFAULT NULL,
  `category` varchar(100) DEFAULT 'All',
  `price` decimal(10,2) DEFAULT 0.00,
  `cost` decimal(10,2) DEFAULT 0.00,
  `stock_quantity` int(11) DEFAULT 0,
  `image_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `products` (`name`, `internal_ref`, `category`, `price`, `cost`, `stock_quantity`) VALUES
('Acoustic Bloc Screen', 'FURN-001', 'Furniture', 295.00, 120.00, 50),
('Corner Desk Black', 'FURN-002', 'Furniture', 85.00, 45.00, 2),
('USB-C Cable (2m)', 'ELEC-005', 'Electronics', 12.99, 4.50, 120);

-- --------------------------------------------------------

-- Table: `operations` (New - For Receipts & Deliveries)
CREATE TABLE `operations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reference` varchar(50) NOT NULL,
  `type` enum('Receipt','Delivery','Internal','Adjustment') NOT NULL,
  `partner_name` varchar(255) DEFAULT NULL,
  `scheduled_date` date DEFAULT NULL,
  `source_document` varchar(50) DEFAULT NULL,
  `status` enum('Draft','Waiting','Ready','Done','Late','Canceled') DEFAULT 'Draft',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `operations` (`reference`, `type`, `partner_name`, `scheduled_date`, `source_document`, `status`) VALUES
('WH/IN/00014', 'Receipt', 'Azure Interior', DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'PO00012', 'Ready'),
('WH/IN/00015', 'Receipt', 'Deco Addict', CURDATE(), 'PO00014', 'Ready'),
('WH/OUT/00054', 'Delivery', 'Gemini Furniture', DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'SO00012', 'Ready'),
('WH/OUT/00055', 'Delivery', 'Deco Addict', CURDATE(), 'SO00014', 'Waiting');

-- --------------------------------------------------------

-- Table: `move_history` (New)
CREATE TABLE `move_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT current_timestamp(),
  `reference` varchar(50) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `location_from` varchar(100) DEFAULT NULL,
  `location_to` varchar(100) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `status` varchar(50) DEFAULT 'Done',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `move_history` (`reference`, `product_name`, `location_from`, `location_to`, `quantity`, `status`) VALUES
('WH/IN/00014', 'Office Chair', 'Vendors', 'WH/Stock', 15, 'Done'),
('WH/OUT/00054', 'USB-C Cable', 'WH/Stock', 'Customers', 5, 'Done');

COMMIT;