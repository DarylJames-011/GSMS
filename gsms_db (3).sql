-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 08, 2026 at 06:08 PM
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
(1, 'Diesel', 52, 19917, '2026-02-26 13:22:33'),
(2, 'Unleaded', 55, 19002, '2026-02-27 06:11:17'),
(3, 'Premium', 58, 18785, '2026-02-27 06:11:32');

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
(1, '212', 82, 0, '12', 'Unavailable', NULL, 'prod_69a3318f8469c.jpg', '0000-00-00 00:00:00'),
(2, '121', 21, 0, 'a horse', 'Unavailable', NULL, 'prod_69a33259817df.jpg', '2026-03-01 02:22:17'),
(3, '121a', 53, 0, '21v', 'Unavailable', NULL, 'prod_69a3326e37a8e.jpg', '2026-03-01 02:22:38'),
(4, 'dia', 54, 0, 'dada', 'Unavailable', NULL, 'prod_69a332b52f4e4.jpg', '2026-03-01 02:23:49'),
(5, 'Brake Fluid', 56, 10, 'This so expensive ngl', 'Low', NULL, 'prod_69a3330e1cf9a.png', '2026-03-01 02:25:18'),
(6, 'Another this', 641, 0, '21csa', 'Unavailable', NULL, 'prod_69a3366688be8.png', '2026-03-01 02:39:34'),
(7, 'yum yum', 1531, 15, '131c', 'Available', NULL, 'prod_69a40c926b031.png', '2026-03-01 17:53:22'),
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
(60, 4, '2026-02-20 20:17:50', '2026-02-24 22:09:00', 'Ended');

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

-- --------------------------------------------------------

--
-- Table structure for table `transaction_tbl`
--

CREATE TABLE `transaction_tbl` (
  `transaction_id` int(11) NOT NULL,
  `transaction_no` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date_created` datetime NOT NULL,
  `total_amt` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(5, 'John', 'password', 'Administrator', '2026-02-26 14:48:10');

--
-- Indexes for dumped tables
--

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
  ADD PRIMARY KEY (`transaction_id2`);

--
-- Indexes for table `user_table`
--
ALTER TABLE `user_table`
  ADD UNIQUE KEY `user_ID` (`user_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

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
  MODIFY `shift_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `transaction_items`
--
ALTER TABLE `transaction_items`
  MODIFY `transaction_id2` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_table`
--
ALTER TABLE `user_table`
  MODIFY `user_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `received_order_fuel`
--
ALTER TABLE `received_order_fuel`
  ADD CONSTRAINT `fk_fuel` FOREIGN KEY (`fuel_id`) REFERENCES `fuel_tbl` (`fuel_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_invoice` FOREIGN KEY (`invoice_number`) REFERENCES `received_order` (`invoice_number`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
