-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 18, 2026 at 04:35 AM
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
-- Database: `gsms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcement_tbl`
--

CREATE TABLE `announcement_tbl` (
  `anc_id` int(11) NOT NULL,
  `anc_type` enum('General','Update','Alert','') NOT NULL,
  `title` varchar(50) NOT NULL,
  `message` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcement_tbl`
--

INSERT INTO `announcement_tbl` (`anc_id`, `anc_type`, `title`, `message`, `date_created`) VALUES
(0, 'Alert', 'Price Increase', 'Increase', '2026-03-18 00:11:22');

-- --------------------------------------------------------

--
-- Table structure for table `discount_tbl`
--

CREATE TABLE `discount_tbl` (
  `discount_id` int(11) NOT NULL,
  `dc_name` varchar(255) NOT NULL,
  `dc_amt` int(11) NOT NULL,
  `dc_desc` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fuel_tbl`
--

CREATE TABLE `fuel_tbl` (
  `fuel_id` int(11) NOT NULL,
  `fuel_type` varchar(255) NOT NULL,
  `price_per_ltr` decimal(10,0) NOT NULL,
  `stock_ltrs` decimal(10,0) NOT NULL,
  `date_created` datetime NOT NULL
) ;

--
-- Dumping data for table `fuel_tbl`
--

INSERT INTO `fuel_tbl` (`fuel_id`, `fuel_type`, `price_per_ltr`, `stock_ltrs`, `date_created`) VALUES
(1, 'Diesel', 70, 13809, '2026-02-26 13:22:33'),
(2, 'Unleaded', 70, 13162, '2026-02-27 06:11:17'),
(3, 'Premium', 50, 18473, '2026-02-27 06:11:32');

-- --------------------------------------------------------

--
-- Table structure for table `product_tbl`
--

CREATE TABLE `product_tbl` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `description` varchar(255) NOT NULL,
  `status` enum('Available','Low','Unavailable','') NOT NULL DEFAULT 'Unavailable',
  `restock_date` varchar(255) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_tbl`
--

INSERT INTO `product_tbl` (`product_id`, `product_name`, `price`, `stock`, `description`, `status`, `restock_date`, `image`, `date_created`) VALUES
(4, 'dia', 54, 0, 'dada', 'Unavailable', NULL, 'prod_69a332b52f4e4.jpg', '2026-03-01 02:23:49'),
(5, 'Brake Fluid', 56, 0, 'This so expensive ngl', 'Unavailable', NULL, 'prod_69a3330e1cf9a.png', '2026-03-01 02:25:18'),
(6, 'Another this', 641, 0, '21csa', 'Unavailable', NULL, 'prod_69a3366688be8.png', '2026-03-01 02:39:34'),
(7, 'yum yum', 1531, 0, '131c', 'Unavailable', NULL, 'prod_69a40c926b031.png', '2026-03-01 17:53:22'),
(8, 'birb', 2313, 0, 'this is a cute looking birb, not gonna lie\r\n\r\n', 'Unavailable', NULL, 'prod_69a4143ddc9aa.jpg', '2026-03-01 18:26:05');

--
-- Triggers `product_tbl`
--
DELIMITER $$
CREATE TRIGGER `set_product_status_insert` BEFORE INSERT ON `product_tbl` FOR EACH ROW SET NEW.status =
CASE
    WHEN NEW.stock = 0 THEN 'unavailable'
    WHEN NEW.stock <= 12 THEN 'low'
    ELSE 'available'
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `set_product_status_update` BEFORE UPDATE ON `product_tbl` FOR EACH ROW SET NEW.status =
CASE
    WHEN NEW.stock = 0 THEN 'unavailable'
    WHEN NEW.stock <= 12 THEN 'low'
    ELSE 'available'
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `received_order`
--

CREATE TABLE `received_order` (
  `order_id` int(11) NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `supplier_name` varchar(255) NOT NULL,
  `date_ordered` date NOT NULL,
  `date_received` date NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `notes` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `received_order`
--

INSERT INTO `received_order` (`order_id`, `invoice_number`, `supplier_name`, `date_ordered`, `date_received`, `date_created`, `notes`) VALUES
(20, '1cscac', 'Petrol de Oro', '2026-02-05', '2026-02-27', '2026-02-28 00:00:45', 'sa'),
(21, 'Silver', 'Petrol de Oro', '2026-02-24', '2026-02-27', '2026-02-28 00:01:26', 'ooiiasac'),
(22, 'Diesel12', 'Petrol de Oro', '2026-02-26', '2026-02-27', '2026-02-28 00:14:57', 'Diesel'),
(23, 'Diesel123', 'Petrol de Oro', '2026-02-24', '2026-02-27', '2026-02-28 00:16:52', 'cas'),
(24, 'SVA-12V-SVG1A', 'Petrol de Oro', '2026-02-25', '2026-02-27', '2026-02-28 00:18:21', '12311c'),
(25, 'casc', 'Petrol de Oro', '2026-02-08', '2026-02-27', '2026-02-28 00:18:48', ''),
(26, 'KSAKK', 'Petrol de Oro', '2026-02-23', '2026-02-27', '2026-02-28 00:23:19', '2121'),
(27, 'csac', 'Petrol de Oro', '2026-02-25', '2026-02-27', '2026-02-28 00:24:02', ''),
(30, 'csa', 'Petrol de Oro', '2026-02-25', '2026-02-27', '2026-02-28 00:37:54', 'ca'),
(31, 'casca', 'Petrol de Oro', '2026-02-24', '2026-02-27', '2026-02-28 00:38:09', 'a'),
(32, 'vav', 'Petrol de Oro', '2026-02-23', '2026-02-27', '2026-02-28 00:38:29', 'v'),
(33, 'YES', 'Petrol de Oro', '2026-02-22', '2026-02-27', '2026-02-28 01:26:26', 'Diesel'),
(35, 'KSAKSKA', 'sasasa', '2026-02-23', '0000-00-00', '2026-02-28 01:27:50', 'premium'),
(36, 'sasa21', 'Petrol de Oro', '2026-02-24', '2026-02-27', '2026-02-28 01:29:53', ''),
(37, 'Diesel', 'Petrol de Oro', '2026-02-23', '2026-02-27', '2026-02-28 02:41:25', '1212'),
(38, 'Diesela', 'Petrol de Oro', '2026-02-25', '0000-00-00', '2026-02-28 02:42:12', '212'),
(39, 'Diesel1', 'Petrol de Oro', '2026-02-24', '2026-02-27', '2026-02-28 02:44:51', '12121'),
(40, 'IDSHA-1DDS-1213A', 'Petrol de Oro', '2026-02-26', '2026-02-28', '2026-02-28 17:13:58', 'I like turtles'),
(41, '121', 'Petrol de Oro', '2026-02-25', '0000-00-00', '2026-02-28 17:14:20', '12'),
(42, '2121', 'Petrol de Oro', '2026-02-11', '2026-02-28', '2026-02-28 18:34:49', '13'),
(43, '121as', 'Petrol de Oro', '2026-02-25', '2026-02-28', '2026-02-28 18:36:22', '121'),
(44, 'fd', 'Petrol de Oro', '2026-02-26', '2026-02-28', '2026-02-28 20:40:26', '1212'),
(45, 'cdvasv', 'Petrol de Oro', '2026-02-03', '2026-02-28', '2026-02-28 21:00:33', 'svav'),
(46, 'ISAOXM-12901-11012', 'Petrol de Oro', '2026-02-25', '2026-02-28', '2026-02-28 22:41:34', 'The reason why i made this note is because to test the functionality of this div.'),
(47, 'aasasa', 'Petrol de Oro', '2026-02-24', '2026-02-28', '2026-03-01 01:10:43', '1sca'),
(48, 'ISAJ-1NSV-VLO3', 'Petrol de Oro', '2026-02-26', '2026-02-28', '2026-03-01 03:28:03', 'i like u'),
(49, 'DVM-1221V-31ZA', 'Petrol de Oro', '2026-02-27', '2026-03-01', '2026-03-01 17:52:23', '121');

-- --------------------------------------------------------

--
-- Table structure for table `received_order_fuel`
--

CREATE TABLE `received_order_fuel` (
  `id` int(11) NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `fuel_id` int(11) NOT NULL,
  `liters` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `received_order_fuel`
--

INSERT INTO `received_order_fuel` (`id`, `invoice_number`, `fuel_id`, `liters`) VALUES
(25, '1cscac', 1, 1331),
(26, 'Silver', 2, 2000),
(27, 'Diesel12', 1, 120),
(28, 'Diesel123', 1, 2000),
(29, 'SVA-12V-SVG1A', 1, 15000),
(30, 'casc', 2, 15000),
(31, 'KSAKK', 1, 10),
(32, 'csac', 3, 60),
(35, 'csa', 1, 1),
(36, 'casca', 3, 2000),
(37, 'vav', 3, 8000),
(38, 'YES', 1, 1200),
(40, 'KSAKSKA', 3, 2000),
(41, 'sasa21', 3, 12),
(42, 'Diesel', 1, 12),
(43, 'Diesela', 1, 121),
(44, 'Diesel1', 1, 122),
(45, 'IDSHA-1DDS-1213A', 2, 2002),
(46, '121', 1, 0),
(47, '2121', 3, 1000),
(48, '121as', 3, 1212),
(49, 'fd', 3, 1000),
(50, 'cdvasv', 3, 1000),
(51, 'ISAOXM-12901-11012', 3, 1000),
(52, 'aasasa', 3, 501),
(53, 'ISAJ-1NSV-VLO3', 3, 500),
(54, 'DVM-1221V-31ZA', 3, 500);

--
-- Triggers `received_order_fuel`
--
DELIMITER $$
CREATE TRIGGER `update_stock_after_insert` AFTER INSERT ON `received_order_fuel` FOR EACH ROW BEGIN
    UPDATE fuel_tbl f
    JOIN (
        SELECT fuel_id, SUM(liters) AS total_liters
        FROM received_order_fuel
        WHERE fuel_id = NEW.fuel_id
        GROUP BY fuel_id
    ) rof ON f.fuel_id = rof.fuel_id
    SET f.stock_ltrs = rof.total_liters;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `received_order_prods`
--

CREATE TABLE `received_order_prods` (
  `id` int(11) NOT NULL,
  `invoice_number` varchar(255) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` decimal(10,0) NOT NULL,
  `units` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shifts`
--

CREATE TABLE `shifts` (
  `shift_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `shift_start` datetime NOT NULL,
  `shift_end` datetime DEFAULT NULL,
  `status` enum('Active','Ended') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shifts`
--

INSERT INTO `shifts` (`shift_id`, `user_id`, `shift_start`, `shift_end`, `status`) VALUES
(60, 4, '2026-02-20 20:17:50', '2026-02-24 22:09:00', 'Ended'),
(61, 4, '2026-03-13 19:49:23', '2026-03-13 19:50:18', 'Ended'),
(62, 4, '2026-03-15 04:23:44', '2026-03-15 18:06:29', 'Ended'),
(63, 4, '2026-03-16 02:56:57', '2026-03-17 01:32:59', 'Ended'),
(64, 7, '2026-03-16 14:59:26', '2026-03-16 15:11:13', 'Ended'),
(65, 7, '2026-03-16 15:11:42', '2026-03-16 15:11:56', 'Ended'),
(66, 7, '2026-03-16 15:12:39', '2026-03-16 15:12:44', 'Ended'),
(67, 7, '2026-03-16 15:14:22', '2026-03-16 15:14:31', 'Ended'),
(68, 7, '2026-03-16 15:16:35', '2026-03-16 15:17:38', 'Ended'),
(69, 7, '2026-03-16 15:17:42', '2026-03-16 15:54:31', 'Ended'),
(70, 7, '2026-03-16 16:29:00', '2026-03-16 17:21:21', 'Ended'),
(71, 7, '2026-03-16 17:38:09', '2026-03-16 17:38:15', 'Ended'),
(72, 7, '2026-03-16 18:12:23', '2026-03-16 18:13:21', 'Ended'),
(73, 7, '2026-03-16 18:13:41', '2026-03-16 18:14:56', 'Ended'),
(74, 7, '2026-03-16 18:16:15', '2026-03-16 18:16:52', 'Ended'),
(75, 7, '2026-03-16 19:19:46', '2026-03-16 20:27:20', 'Ended'),
(76, 7, '2026-03-16 20:27:29', '2026-03-16 20:29:43', 'Ended'),
(77, 7, '2026-03-16 20:46:27', '2026-03-16 21:14:31', 'Ended'),
(78, 7, '2026-03-16 21:16:14', '2026-03-16 21:16:23', 'Ended'),
(79, 7, '2026-03-16 22:47:06', '2026-03-16 22:47:41', 'Ended'),
(80, 7, '2026-03-16 23:22:56', '2026-03-16 23:23:19', 'Ended'),
(81, 7, '2026-03-16 23:43:01', '2026-03-16 23:43:38', 'Ended'),
(82, 4, '2026-03-17 01:33:15', '2026-03-17 01:33:18', 'Ended'),
(83, 7, '2026-03-17 02:08:00', '2026-03-17 02:08:46', 'Ended'),
(84, 4, '2026-03-17 14:57:39', '2026-03-17 15:02:34', 'Ended'),
(85, 4, '2026-03-17 16:27:32', '2026-03-17 16:30:33', 'Ended'),
(86, 4, '2026-03-17 16:32:08', '2026-03-17 16:32:18', 'Ended'),
(87, 4, '2026-03-17 16:34:13', '2026-03-17 16:35:19', 'Ended'),
(88, 4, '2026-03-17 16:36:54', '2026-03-17 16:37:03', 'Ended'),
(89, 4, '2026-03-17 22:35:34', '2026-03-17 22:36:48', 'Ended'),
(90, 4, '2026-03-18 01:19:09', '2026-03-18 01:21:40', 'Ended'),
(91, 4, '2026-03-18 01:22:01', '2026-03-18 01:23:45', 'Ended'),
(92, 4, '2026-03-18 01:24:30', '2026-03-18 01:24:34', 'Ended'),
(93, 4, '2026-03-18 01:24:37', '2026-03-18 01:26:00', 'Ended'),
(94, 4, '2026-03-18 01:26:23', '2026-03-18 01:26:47', 'Ended'),
(95, 4, '2026-03-18 01:57:28', '2026-03-18 02:00:02', 'Ended'),
(96, 4, '2026-03-18 02:23:03', '2026-03-18 02:23:05', 'Ended'),
(97, 4, '2026-03-18 02:57:39', '2026-03-18 02:59:07', 'Ended');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_items`
--

CREATE TABLE `transaction_items` (
  `transaction_id2` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `product_type` enum('product','fuel','','') NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` decimal(10,0) NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `subtotal` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction_items`
--

INSERT INTO `transaction_items` (`transaction_id2`, `transaction_id`, `product_type`, `product_id`, `quantity`, `price`, `subtotal`) VALUES
(3, 6, 'product', 7, 2, 1531, 3062),
(4, 7, 'product', 7, 5, 1531, 7655),
(5, 8, 'product', 5, 2, 56, 112),
(6, 8, 'product', 7, 2, 1531, 3062),
(7, 9, 'fuel', 1, 19, 52, 1000),
(8, 9, 'product', 5, 1, 56, 56),
(9, 10, 'fuel', 1, 25, 52, 1300),
(10, 11, 'product', 7, 2, 1531, 3062),
(11, 12, 'product', 5, 1, 56, 56),
(12, 13, 'fuel', 1, 25, 52, 1300),
(13, 14, 'fuel', 2, 68, 55, 3750),
(14, 15, 'fuel', 3, 122, 58, 7076),
(15, 16, 'fuel', 1, 12, 52, 600),
(16, 17, 'fuel', 1, 25, 52, 1300),
(17, 18, 'product', 7, 2, 1531, 3062),
(18, 19, 'fuel', 2, 20, 55, 1100),
(19, 19, 'product', 5, 2, 56, 112),
(20, 19, 'product', 7, 1, 1531, 1531),
(21, 20, 'fuel', 1, 19, 52, 1000),
(22, 21, 'fuel', 1, 19, 52, 1000),
(23, 22, 'fuel', 2, 25, 55, 1375),
(24, 23, 'fuel', 1, 19, 52, 1000),
(25, 24, 'fuel', 1, 19, 52, 1000),
(26, 24, 'product', 5, 1, 56, 56),
(27, 25, 'product', 7, 11, 1531, 16841),
(28, 26, 'product', 7, 3, 1531, 4593),
(29, 27, 'product', 7, 1, 1531, 1531),
(30, 28, 'product', 5, 1, 56, 56),
(31, 29, 'product', 7, 1, 1531, 1531),
(32, 30, 'product', 7, 1, 1531, 1531),
(33, 31, 'product', 7, 1, 1531, 1531),
(34, 32, 'product', 7, 1, 1531, 1531),
(35, 33, 'product', 7, 1, 1531, 1531),
(36, 34, 'fuel', 1, 19, 52, 1000),
(37, 34, 'product', 7, 1, 1531, 1531),
(38, 35, 'product', 7, 1, 1531, 1531),
(39, 36, 'product', 7, 1, 1531, 1531),
(40, 37, 'product', 7, 1, 1531, 1531),
(41, 38, 'product', 5, 2, 56, 112),
(42, 38, 'product', 7, 1, 1531, 1531),
(43, 39, 'product', 5, 4, 56, 224),
(44, 40, 'product', 7, 1, 1531, 1531),
(45, 41, 'product', 5, 1, 56, 56),
(46, 41, 'product', 7, 1, 1531, 1531),
(47, 42, 'product', 7, 1, 1531, 1531),
(48, 43, 'fuel', 1, 38, 52, 2000),
(49, 44, 'fuel', 1, 19, 52, 1000),
(50, 45, 'fuel', 2, 25, 55, 1375),
(51, 46, 'fuel', 1, 19, 52, 1000),
(52, 47, 'fuel', 1, 19, 52, 1000),
(53, 48, 'product', 5, 1, 56, 56),
(54, 49, 'fuel', 1, 19, 52, 1000),
(55, 50, 'fuel', 1, 6, 52, 300),
(56, 51, 'fuel', 1, 19, 52, 1000),
(57, 51, 'product', 5, 1, 56, 56),
(58, 52, 'fuel', 1, 19, 52, 1000),
(59, 53, 'fuel', 1, 19, 52, 1000),
(60, 54, 'fuel', 1, 19, 52, 1000),
(61, 55, 'fuel', 1, 38, 52, 2000),
(62, 56, 'fuel', 1, 38, 52, 2000),
(63, 57, 'fuel', 2, 36, 55, 2000),
(64, 58, 'fuel', 2, 25, 55, 1375),
(65, 59, 'fuel', 2, 25, 55, 1375),
(66, 60, 'fuel', 3, 25, 58, 1450),
(67, 61, 'fuel', 3, 125, 58, 7250),
(68, 62, 'fuel', 1, 6, 52, 300),
(69, 63, 'fuel', 1, 19, 52, 1000),
(70, 64, 'fuel', 1, 19, 52, 1000),
(71, 65, 'fuel', 1, 19, 52, 1000),
(72, 66, 'fuel', 1, 5, 52, 260),
(73, 67, 'fuel', 2, 25, 55, 1375),
(74, 68, 'fuel', 2, 25, 55, 1375),
(75, 69, 'fuel', 2, 25, 55, 1375),
(76, 70, 'fuel', 1, 19, 52, 1000),
(77, 71, 'fuel', 2, 18, 55, 1000),
(78, 72, 'fuel', 2, 25, 55, 1375),
(79, 73, 'fuel', 1, 38, 52, 2000),
(80, 74, 'fuel', 2, 18, 55, 1000),
(81, 75, 'fuel', 1, 19, 52, 1000),
(82, 76, 'fuel', 2, 25, 55, 1375),
(83, 77, 'fuel', 1, 19, 52, 1000),
(84, 78, 'fuel', 2, 25, 55, 1375),
(85, 79, 'fuel', 1, 25, 52, 1300),
(86, 80, 'fuel', 2, 25, 55, 1375),
(87, 81, 'fuel', 1, 288, 52, 15000),
(88, 82, 'fuel', 1, 58, 52, 3000),
(89, 83, 'fuel', 2, 25, 55, 1375),
(90, 84, 'fuel', 2, 125, 55, 6875),
(91, 85, 'fuel', 2, 125, 55, 6875),
(92, 86, 'fuel', 2, 25, 55, 1375),
(93, 87, 'fuel', 1, 115, 52, 6000),
(94, 88, 'fuel', 2, 75, 55, 4125),
(95, 89, 'fuel', 1, 5000, 86, 430000),
(96, 90, 'fuel', 2, 5000, 80, 400000),
(97, 91, 'fuel', 2, 25, 70, 1750),
(98, 92, 'fuel', 2, 25, 70, 1750),
(99, 93, 'fuel', 1, 43, 70, 3000),
(100, 93, 'fuel', 2, 25, 70, 1750),
(101, 93, 'fuel', 3, 40, 50, 2000),
(102, 94, 'fuel', 1, 43, 70, 3000);

--
-- Triggers `transaction_items`
--
DELIMITER $$
CREATE TRIGGER `reduce_stock_after_transaction` AFTER INSERT ON `transaction_items` FOR EACH ROW BEGIN
    -- Reduce product stock
    IF NEW.product_type = 'product' THEN
        UPDATE product_tbl
        SET stock = GREATEST(stock - NEW.quantity, 0)
        WHERE product_id = NEW.product_id;
    END IF;

    -- Reduce fuel stock (liters)
    IF NEW.product_type = 'fuel' THEN
        UPDATE fuel_tbl
        SET stock_ltrs = GREATEST(stock_ltrs - NEW.quantity, 0)
        WHERE fuel_id = NEW.product_id;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `transaction_tbl`
--

CREATE TABLE `transaction_tbl` (
  `transaction_id` int(11) NOT NULL,
  `transaction_no` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `payment_method` enum('Cash','Credit','Online','','') NOT NULL,
  `reference_num` varchar(255) DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `total_amt` decimal(10,0) NOT NULL,
  `amt_received` decimal(10,0) NOT NULL,
  `status` enum('Confirmed','Void','','') NOT NULL DEFAULT 'Confirmed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction_tbl`
--

INSERT INTO `transaction_tbl` (`transaction_id`, `transaction_no`, `user_id`, `payment_method`, `reference_num`, `date_created`, `total_amt`, `amt_received`, `status`) VALUES
(6, 'TRNS-006', 4, 'Cash', NULL, '2026-03-11 01:23:42', 3062, 0, 'Void'),
(7, 'TRNS-007', 4, 'Cash', NULL, '2026-03-11 01:26:22', 7655, 0, 'Void'),
(8, 'TRNS-008', 4, 'Cash', NULL, '2026-03-11 01:27:39', 3174, 0, 'Void'),
(9, 'TRNS-009', 4, 'Cash', NULL, '2026-03-11 01:29:26', 1056, 0, 'Void'),
(10, 'TRNS-010', 4, 'Cash', NULL, '2026-03-11 01:30:44', 1300, 0, 'Confirmed'),
(11, 'TRNS-011', 4, '', NULL, '2026-03-12 01:28:46', 3062, 0, 'Confirmed'),
(12, 'TRNS-012', 4, '', NULL, '2026-03-12 01:31:42', 56, 0, 'Confirmed'),
(13, 'TRNS-013', 4, '', NULL, '2026-03-12 19:15:24', 1300, 0, 'Confirmed'),
(14, 'TRNS-014', 4, 'Cash', NULL, '2026-03-12 19:24:33', 3750, 0, 'Confirmed'),
(15, 'TRNS-015', 4, 'Cash', '', '2026-03-12 19:29:56', 7076, 8000, 'Confirmed'),
(16, 'TRNS-016', 4, 'Cash', '', '2026-03-12 20:20:17', 600, 600, 'Confirmed'),
(17, 'TRNS-017', 4, 'Cash', '', '2026-03-12 20:21:45', 1300, 1500, 'Confirmed'),
(18, 'TRNS-018', 4, 'Cash', '', '2026-03-12 20:23:05', 3062, 5000, 'Confirmed'),
(19, 'TRNS-019', 4, 'Cash', '', '2026-03-12 22:16:22', 2743, 3000, 'Void'),
(20, 'TRNS-020', 4, 'Cash', '', '2026-03-13 15:01:02', 1000, 1000, 'Confirmed'),
(21, 'TRNS-021', 4, 'Cash', '', '2026-03-13 15:01:02', 1000, 1000, 'Confirmed'),
(22, 'TRNS-022', 4, 'Cash', '', '2026-03-13 21:09:21', 1375, 1375, 'Confirmed'),
(23, 'TRNS-023', 4, 'Credit', 'DARYL-JAMES-BACOL', '2026-03-13 21:30:57', 1000, 3100, 'Confirmed'),
(24, 'TRNS-024', 4, 'Cash', '', '2026-03-14 20:14:36', 1056, 1100, 'Void'),
(25, 'TRNS-025', 4, 'Credit', 'DARYL-JAMES-BACOL', '2026-03-14 23:35:05', 16841, 17000, 'Void'),
(26, 'TRNS-026', 4, 'Cash', '', '2026-03-15 04:16:01', 4593, 5000, 'Confirmed'),
(27, 'TRNS-027', 4, 'Cash', '', '2026-03-15 04:27:32', 1531, 1531, 'Confirmed'),
(28, 'TRNS-028', 4, 'Cash', '', '2026-03-15 04:29:53', 56, 60, 'Confirmed'),
(29, 'TRNS-029', 4, 'Cash', '', '2026-03-15 04:31:14', 1531, 1600, 'Confirmed'),
(30, 'TRNS-030', 4, 'Cash', '', '2026-03-15 04:32:04', 1531, 1600, 'Confirmed'),
(31, 'TRNS-031', 4, 'Cash', '', '2026-03-15 04:33:21', 1531, 1600, 'Confirmed'),
(32, 'TRNS-032', 4, 'Cash', '', '2026-03-15 04:34:47', 1531, 1600, 'Confirmed'),
(33, 'TRNS-033', 4, 'Cash', '', '2026-03-15 04:37:34', 1531, 1600, 'Confirmed'),
(34, 'TRNS-034', 4, 'Cash', '', '2026-03-15 04:38:08', 2531, 3000, 'Void'),
(35, 'TRNS-035', 4, 'Cash', '', '2026-03-15 04:39:59', 1531, 1600, 'Confirmed'),
(36, 'TRNS-036', 4, 'Cash', '', '2026-03-15 17:16:54', 1531, 1600, 'Confirmed'),
(37, 'TRNS-037', 4, 'Cash', '', '2026-03-15 17:17:53', 1531, 1600, 'Void'),
(38, 'TRNS-038', 4, 'Cash', '', '2026-03-15 17:19:20', 1643, 1700, 'Confirmed'),
(39, 'TRNS-039', 4, 'Cash', '', '2026-03-15 17:20:14', 224, 225, 'Confirmed'),
(40, 'TRNS-040', 4, 'Cash', '', '2026-03-15 17:20:51', 1531, 1600, 'Void'),
(41, 'TRNS-041', 4, 'Cash', '', '2026-03-15 17:21:56', 1587, 1600, 'Void'),
(42, 'TRNS-042', 4, 'Cash', '', '2026-03-15 17:25:58', 1531, 1600, 'Confirmed'),
(43, 'TRNS-043', 4, 'Cash', '', '2026-03-15 17:26:35', 2000, 2000, 'Confirmed'),
(44, 'TRNS-044', 4, 'Cash', '', '2026-03-15 17:27:21', 1000, 1000, 'Confirmed'),
(45, 'TRNS-045', 4, 'Cash', '', '2026-03-15 17:27:46', 1375, 1400, 'Confirmed'),
(46, 'TRNS-046', 4, 'Credit', 'SA-VSA-121VFS-SA', '2026-03-15 17:29:13', 1000, 1000, 'Confirmed'),
(47, 'TRNS-047', 4, 'Cash', '', '2026-03-15 17:36:14', 1000, 1000, 'Confirmed'),
(48, 'TRNS-048', 4, 'Cash', '', '2026-03-15 17:39:56', 56, 60, 'Confirmed'),
(49, 'TRNS-049', 4, 'Cash', '', '2026-03-15 17:41:45', 1000, 2000, 'Confirmed'),
(50, 'TRNS-050', 4, 'Cash', '', '2026-03-15 17:45:01', 300, 300, 'Confirmed'),
(51, 'TRNS-051', 4, 'Cash', '', '2026-03-15 17:45:26', 1056, 1060, 'Confirmed'),
(52, 'TRNS-052', 4, 'Cash', '', '2026-03-15 17:45:59', 1000, 1000, 'Confirmed'),
(53, 'TRNS-053', 4, 'Cash', '', '2026-03-15 17:46:43', 1000, 1000, 'Confirmed'),
(54, 'TRNS-054', 4, 'Cash', '', '2026-03-15 17:47:58', 1000, 1000, 'Confirmed'),
(55, 'TRNS-055', 4, 'Cash', '', '2026-03-15 17:48:45', 2000, 2000, 'Confirmed'),
(56, 'TRNS-056', 4, 'Cash', '', '2026-03-15 17:50:18', 2000, 2000, 'Confirmed'),
(57, 'TRNS-057', 4, 'Cash', '', '2026-03-15 17:58:51', 2000, 2000, 'Confirmed'),
(58, 'TRNS-058', 4, 'Cash', '', '2026-03-15 18:00:21', 1375, 1400, 'Confirmed'),
(59, 'TRNS-059', 4, 'Cash', '', '2026-03-15 18:01:53', 1375, 1400, 'Confirmed'),
(60, 'TRNS-060', 4, 'Cash', '', '2026-03-15 18:04:20', 1450, 1500, 'Confirmed'),
(61, 'TRNS-061', 4, 'Cash', '', '2026-03-15 18:04:59', 7250, 8500, 'Confirmed'),
(62, 'TRNS-062', 4, 'Cash', '', '2026-03-16 02:25:34', 300, 300, 'Confirmed'),
(63, 'TRNS-063', 4, 'Credit', '1dC-12CAS', '2026-03-16 02:26:09', 1000, 1000, 'Confirmed'),
(64, 'TRNS-064', 4, 'Credit', 'vqV1-21VSA', '2026-03-16 02:27:12', 1000, 1000, 'Confirmed'),
(65, 'TRNS-065', 4, 'Cash', '', '2026-03-16 02:50:17', 1000, 1000, 'Confirmed'),
(66, 'TRNS-066', 4, 'Cash', '', '2026-03-16 02:51:00', 260, 260, 'Confirmed'),
(67, 'TRNS-067', 4, 'Cash', '', '2026-03-16 02:53:06', 1375, 1375, 'Confirmed'),
(68, 'TRNS-068', 4, 'Cash', '', '2026-03-16 02:53:58', 1375, 1375, 'Confirmed'),
(69, 'TRNS-069', 4, 'Cash', '', '2026-03-16 02:54:51', 1375, 1375, 'Confirmed'),
(70, 'TRNS-070', 7, 'Cash', '', '2026-03-16 14:00:40', 1000, 1000, 'Confirmed'),
(71, 'TRNS-071', 7, 'Cash', '', '2026-03-16 15:21:46', 1000, 1000, 'Confirmed'),
(72, 'TRNS-072', 7, 'Cash', '', '2026-03-16 15:22:23', 1375, 1500, 'Void'),
(73, 'TRNS-073', 7, 'Cash', '', '2026-03-16 16:56:59', 2000, 2000, 'Confirmed'),
(74, 'TRNS-074', 7, 'Cash', '', '2026-03-16 17:16:37', 1000, 1000, 'Confirmed'),
(75, 'TRNS-075', 7, 'Cash', '', '2026-03-16 17:17:04', 1000, 1000, 'Confirmed'),
(76, 'TRNS-076', 7, 'Cash', '', '2026-03-16 17:19:27', 1375, 1375, 'Confirmed'),
(77, 'TRNS-077', 7, 'Cash', '', '2026-03-16 18:16:30', 1000, 1000, 'Confirmed'),
(78, 'TRNS-078', 7, 'Cash', '', '2026-03-16 18:16:42', 1375, 1375, 'Confirmed'),
(79, 'TRNS-079', 7, 'Cash', '', '2026-03-16 19:21:06', 1300, 1300, 'Confirmed'),
(80, 'TRNS-080', 7, 'Cash', '', '2026-03-16 20:28:33', 1375, 1400, 'Confirmed'),
(81, 'TRNS-081', 7, 'Cash', '', '2026-03-16 20:29:02', 15000, 15000, 'Confirmed'),
(82, 'TRNS-082', 7, 'Cash', '', '2026-03-16 22:47:17', 3000, 3000, 'Confirmed'),
(83, 'TRNS-083', 7, 'Cash', '', '2026-03-16 22:47:30', 1375, 1375, 'Confirmed'),
(84, 'TRNS-084', 7, 'Cash', '', '2026-03-16 23:23:08', 6875, 6900, 'Confirmed'),
(85, 'TRNS-085', 7, 'Cash', '', '2026-03-16 23:23:08', 6875, 6900, 'Confirmed'),
(86, 'TRNS-086', 7, 'Credit', 'BSDA-CAS1-JKJA1', '2026-03-16 23:43:25', 1375, 1400, 'Confirmed'),
(87, 'TRNS-087', 7, 'Cash', '', '2026-03-17 02:08:16', 6000, 6000, 'Confirmed'),
(88, 'TRNS-088', 7, 'Cash', '', '2026-03-17 02:08:32', 4125, 4150, 'Confirmed'),
(89, 'TRNS-089', 4, 'Cash', '', '2026-03-17 22:35:52', 430000, 500000, 'Confirmed'),
(90, 'TRNS-090', 4, 'Credit', 'aJHV-1V1AV1-041', '2026-03-17 22:36:27', 400000, 400000, 'Confirmed'),
(91, 'TRNS-091', 4, 'Cash', '', '2026-03-18 01:23:29', 1750, 1750, 'Confirmed'),
(92, 'TRNS-092', 4, 'Cash', '', '2026-03-18 01:23:41', 1750, 2000, 'Confirmed'),
(93, 'TRNS-093', 4, 'Cash', '', '2026-03-18 01:26:42', 6750, 7000, 'Confirmed'),
(94, 'TRNS-094', 4, 'Cash', '', '2026-03-18 02:57:56', 3000, 3000, 'Void');

--
-- Triggers `transaction_tbl`
--
DELIMITER $$
CREATE TRIGGER `restore_inventory_after_void` AFTER UPDATE ON `transaction_tbl` FOR EACH ROW BEGIN

    -- Only run when status changes Confirmed -> Void
    IF OLD.status = 'Confirmed' AND NEW.status = 'Void' THEN

        -- Restore product stock
        UPDATE product_tbl p
        JOIN transaction_items ti
        ON ti.product_id = p.product_id
        SET p.stock = p.stock + ti.quantity
        WHERE ti.transaction_id = NEW.transaction_id
        AND ti.product_type = 'product';

        -- Restore fuel stock
        UPDATE fuel_tbl f
        JOIN transaction_items ti
        ON ti.product_id = f.fuel_id
        SET f.stock_ltrs = f.stock_ltrs + ti.quantity
        WHERE ti.transaction_id = NEW.transaction_id
        AND ti.product_type = 'fuel';

    END IF;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `user_table`
--

CREATE TABLE `user_table` (
  `user_ID` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Administrator','Cashier','','') NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_table`
--

INSERT INTO `user_table` (`user_ID`, `username`, `password`, `role`, `date_created`) VALUES
(4, 'Jane', 'password', 'Cashier', '2026-02-17 14:19:05'),
(5, 'John', 'password', 'Administrator', '2026-02-26 14:48:10'),
(7, 'Janine', 'password', 'Cashier', '2026-03-16 13:59:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `discount_tbl`
--
ALTER TABLE `discount_tbl`
  ADD PRIMARY KEY (`discount_id`);

--
-- Indexes for table `fuel_tbl`
--
ALTER TABLE `fuel_tbl`
  ADD PRIMARY KEY (`fuel_id`);

--
-- Indexes for table `product_tbl`
--
ALTER TABLE `product_tbl`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `received_order`
--
ALTER TABLE `received_order`
  ADD PRIMARY KEY (`order_id`),
  ADD UNIQUE KEY `invoice_number` (`invoice_number`);

--
-- Indexes for table `received_order_fuel`
--
ALTER TABLE `received_order_fuel`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_invoice_fuel` (`invoice_number`,`fuel_id`),
  ADD KEY `fk_fuel` (`fuel_id`);

--
-- Indexes for table `received_order_prods`
--
ALTER TABLE `received_order_prods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shifts`
--
ALTER TABLE `shifts`
  ADD PRIMARY KEY (`shift_id`);

--
-- Indexes for table `transaction_items`
--
ALTER TABLE `transaction_items`
  ADD PRIMARY KEY (`transaction_id2`),
  ADD KEY `fk_transaction` (`transaction_id`);

--
-- Indexes for table `transaction_tbl`
--
ALTER TABLE `transaction_tbl`
  ADD PRIMARY KEY (`transaction_id`);

--
-- Indexes for table `user_table`
--
ALTER TABLE `user_table`
  ADD UNIQUE KEY `user_ID` (`user_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `discount_tbl`
--
ALTER TABLE `discount_tbl`
  MODIFY `discount_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fuel_tbl`
--
ALTER TABLE `fuel_tbl`
  MODIFY `fuel_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_tbl`
--
ALTER TABLE `product_tbl`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `received_order`
--
ALTER TABLE `received_order`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `received_order_fuel`
--
ALTER TABLE `received_order_fuel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `received_order_prods`
--
ALTER TABLE `received_order_prods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shifts`
--
ALTER TABLE `shifts`
  MODIFY `shift_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `transaction_items`
--
ALTER TABLE `transaction_items`
  MODIFY `transaction_id2` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `transaction_tbl`
--
ALTER TABLE `transaction_tbl`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `user_table`
--
ALTER TABLE `user_table`
  MODIFY `user_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `received_order_fuel`
--
ALTER TABLE `received_order_fuel`
  ADD CONSTRAINT `fk_fuel` FOREIGN KEY (`fuel_id`) REFERENCES `fuel_tbl` (`fuel_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_invoice` FOREIGN KEY (`invoice_number`) REFERENCES `received_order` (`invoice_number`);

--
-- Constraints for table `transaction_items`
--
ALTER TABLE `transaction_items`
  ADD CONSTRAINT `fk_transaction` FOREIGN KEY (`transaction_id`) REFERENCES `transaction_tbl` (`transaction_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
