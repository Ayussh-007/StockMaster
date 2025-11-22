-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 22, 2025 at 11:47 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `stock_master`
--

-- --------------------------------------------------------

--
-- Table structure for table `delivery`
--

CREATE TABLE `delivery` (
  `id` int(11) NOT NULL,
  `customer` varchar(100) NOT NULL,
  `delivery_date` date NOT NULL,
  `priority` varchar(20) NOT NULL DEFAULT 'Normal',
  `status` varchar(20) NOT NULL DEFAULT 'Ready'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventary`
--

CREATE TABLE `inventary` (
  `id` int(11) NOT NULL,
  `reference` varchar(100) NOT NULL,
  `location` varchar(100) NOT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'In Progress',
  `count_date` date NOT NULL,
  `product_scope` varchar(50) NOT NULL DEFAULT 'All Products'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login_credentials`
--

CREATE TABLE `login_credentials` (
  `id` int(11) NOT NULL,
  `login_id` varchar(12) NOT NULL,
  `email_id` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login_credentials`
--

INSERT INTO `login_credentials` (`id`, `login_id`, `email_id`, `password`, `timestamp`) VALUES
(4, 'Manthan2007', 'railkarmanthan@gmail.com', '$2y$10$kxQYe5gIMZYC8fFh0/ClWOL4ZO5EbUuJTz0dBooOeXukl3Zx9TkBa', '2025-11-22 11:35:57'),
(5, 'AyushOP', 'mhatreayush@icloud.com', '$2y$10$IZokq.nBtU7lYY562.bzd.33uywoS1po51kEOgay1bJoeyNrwvZ4u', '2025-11-22 13:43:47');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_otp`
--

CREATE TABLE `password_reset_otp` (
  `id` int(11) NOT NULL,
  `email_id` varchar(255) NOT NULL,
  `otp` varchar(6) NOT NULL,
  `expires_at` datetime NOT NULL,
  `is_used` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_reset_otp`
--

INSERT INTO `password_reset_otp` (`id`, `email_id`, `otp`, `expires_at`, `is_used`, `created_at`) VALUES
(5, 'railkarmanthan@gmail.com', '263060', '2025-11-22 09:24:48', 0, '2025-11-22 08:14:48');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `product_type` varchar(50) DEFAULT 'Storable Product',
  `internal_ref` varchar(100) DEFAULT NULL,
  `sales_price` decimal(10,2) DEFAULT 0.00,
  `cost` decimal(10,2) DEFAULT 0.00,
  `category` varchar(100) DEFAULT NULL,
  `on_hand` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `receipts`
--

CREATE TABLE `receipts` (
  `id` int(11) NOT NULL,
  `reference_code` varchar(50) NOT NULL,
  `vendor` varchar(100) NOT NULL,
  `scheduled_date` date NOT NULL,
  `source_document` varchar(50) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Ready'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `delivery`
--
ALTER TABLE `delivery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventary`
--
ALTER TABLE `inventary`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login_credentials`
--
ALTER TABLE `login_credentials`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login_id` (`login_id`),
  ADD UNIQUE KEY `email_id` (`email_id`);

--
-- Indexes for table `password_reset_otp`
--
ALTER TABLE `password_reset_otp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `receipts`
--
ALTER TABLE `receipts`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `delivery`
--
ALTER TABLE `delivery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventary`
--
ALTER TABLE `inventary`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login_credentials`
--
ALTER TABLE `login_credentials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `password_reset_otp`
--
ALTER TABLE `password_reset_otp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `receipts`
--
ALTER TABLE `receipts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
