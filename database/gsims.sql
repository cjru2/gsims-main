-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 07, 2023 at 02:17 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gsims`
--

-- --------------------------------------------------------

--
-- Table structure for table `restaurant_category`
--

CREATE TABLE `restaurant_category` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `status` enum('Enable','Disable') NOT NULL DEFAULT 'Enable'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `restaurant_category`
--

INSERT INTO `restaurant_category` (`id`, `name`, `status`) VALUES
(1, 'Beverage1', 'Enable'),
(6, 'Beverage', 'Enable');

-- --------------------------------------------------------

--
-- Table structure for table `restaurant_items`
--

CREATE TABLE `restaurant_items` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `category_id` int(11) NOT NULL,
  `status` enum('Enable','Disable') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `restaurant_items`
--

INSERT INTO `restaurant_items` (`id`, `name`, `price`, `category_id`, `status`) VALUES
(1, 'Iced Tea', 90.00, 6, 'Enable'),
(6, 'Coke', 5.00, 6, 'Enable');

-- --------------------------------------------------------

--
-- Table structure for table `restaurant_order`
--

CREATE TABLE `restaurant_order` (
  `id` int(11) NOT NULL,
  `table_id` varchar(250) NOT NULL,
  `gross_amount` decimal(12,2) NOT NULL,
  `tax_amount` decimal(12,2) NOT NULL,
  `net_amount` decimal(12,2) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_by` enum('admin','waiter','cashier') NOT NULL,
  `status` enum('In Process','Completed') NOT NULL DEFAULT 'In Process'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `restaurant_order`
--

INSERT INTO `restaurant_order` (`id`, `table_id`, `gross_amount`, `tax_amount`, `net_amount`, `created`, `created_by`, `status`) VALUES
(1000, '1', 9.00, 0.00, 0.00, '2023-12-07 09:08:16', 'admin', 'Completed');

-- --------------------------------------------------------

--
-- Table structure for table `restaurant_order_item`
--

CREATE TABLE `restaurant_order_item` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(4) NOT NULL,
  `rate` decimal(12,2) NOT NULL,
  `amount` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `restaurant_order_item`
--

INSERT INTO `restaurant_order_item` (`id`, `order_id`, `category_id`, `item_id`, `quantity`, `rate`, `amount`) VALUES
(10, 1000, 6, 1, 1, 5.00, 5.00),
(11, 1000, 6, 6, 1, 4.00, 4.00);

-- --------------------------------------------------------

--
-- Table structure for table `restaurant_promo`
--

CREATE TABLE `restaurant_promo` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `promocode` varchar(250) NOT NULL,
  `productid` int(11) NOT NULL,
  `product_description` varchar(250) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `status` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `restaurant_promo`
--

INSERT INTO `restaurant_promo` (`id`, `name`, `promocode`, `productid`, `product_description`, `price`, `status`) VALUES
(1, 'Unli A', '72b40fe', 1, 'Unli drinks', 0.00, 'Enable'),
(2, 'SET A', 'bd929e1', 1, 'A', 0.00, 'Enable'),
(3, 'ef', 'c61f6bd', 1, 'sd', 0.00, 'Enable'),
(4, 'k', '368e111', 1, 'k', 0.00, 'Enable'),
(5, 'oqqq', '60c8174', 1, 'o', 0.00, 'Enable'),
(6, 'Happy Meal', '0b3fa75', 0, 'burger,fries,drinks', 0.00, 'Enable'),
(7, 'k', 'f70fecf', 0, 'k', 0.00, 'Enable'),
(8, 'qWo', 'e8d351a', 0, 'Enk', 8.00, 'Enable'),
(9, 'Happy Meal', 'b1623c3', 0, 'Burger, Fries, Drinks', 499.00, 'Enable');

-- --------------------------------------------------------

--
-- Table structure for table `restaurant_promo_products`
--

CREATE TABLE `restaurant_promo_products` (
  `id` int(11) NOT NULL,
  `promoid` int(11) NOT NULL,
  `categoryid` int(11) NOT NULL,
  `productid` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `restaurant_promo_products`
--

INSERT INTO `restaurant_promo_products` (`id`, `promoid`, `categoryid`, `productid`, `quantity`, `status`) VALUES
(0, 1, 0, 1, 2, 'Enable'),
(0, 2, 0, 1, 1, 'Enable'),
(0, 3, 0, 6, 2, 'Enable'),
(0, 4, 0, 1, 999, 'Enable'),
(0, 4, 0, 6, 999, 'Enable'),
(0, 0, 6, 6, 3, 'Enable'),
(0, 0, 6, 6, 1, 'Enable'),
(0, 5, 6, 6, 1, 'Enable'),
(0, 6, 6, 6, 1, 'Enable'),
(0, 7, 6, 6, 1, 'Enable'),
(0, 8, 6, 1, 7, 'Enable'),
(0, 9, 6, 1, 1, 'Enable'),
(0, 9, 6, 6, 2, 'Enable');

-- --------------------------------------------------------

--
-- Table structure for table `restaurant_table`
--

CREATE TABLE `restaurant_table` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `capacity` int(3) NOT NULL,
  `status` enum('Enable','Disable') NOT NULL DEFAULT 'Enable'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `restaurant_table`
--

INSERT INTO `restaurant_table` (`id`, `name`, `capacity`, `status`) VALUES
(1, 'Table 1', 4, 'Enable'),
(2, 'Table 2', 2, 'Enable'),
(3, 'Table 3', 4, 'Enable'),
(4, 'Table 4', 2, 'Enable'),
(5, 'Table 5', 4, 'Enable');

-- --------------------------------------------------------

--
-- Table structure for table `restaurant_tax`
--

CREATE TABLE `restaurant_tax` (
  `id` int(11) NOT NULL,
  `tax_name` varchar(250) NOT NULL,
  `percentage` decimal(4,2) NOT NULL,
  `status` enum('Enable','Disable') NOT NULL DEFAULT 'Enable'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `restaurant_tax`
--

INSERT INTO `restaurant_tax` (`id`, `tax_name`, `percentage`, `status`) VALUES
(1, 'SGST', 5.50, 'Enable'),
(2, 'CGST', 7.50, 'Enable');

-- --------------------------------------------------------

--
-- Table structure for table `restaurant_user`
--

CREATE TABLE `restaurant_user` (
  `id` int(11) UNSIGNED NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(64) NOT NULL,
  `mobile` varchar(12) NOT NULL,
  `address` text NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `role` enum('admin','waiter','cashier') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `restaurant_user`
--

INSERT INTO `restaurant_user` (`id`, `first_name`, `last_name`, `gender`, `email`, `password`, `mobile`, `address`, `created`, `role`) VALUES
(1, 'Gina', 'Ildefonso', 'Female', 'admin@email.com', '202cb962ac59075b964b07152d234b70', '1234567890', '', '2020-11-28 22:45:58', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `restaurant_category`
--
ALTER TABLE `restaurant_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `restaurant_items`
--
ALTER TABLE `restaurant_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `restaurant_order`
--
ALTER TABLE `restaurant_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `restaurant_order_item`
--
ALTER TABLE `restaurant_order_item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `restaurant_promo`
--
ALTER TABLE `restaurant_promo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `restaurant_table`
--
ALTER TABLE `restaurant_table`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `restaurant_tax`
--
ALTER TABLE `restaurant_tax`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `restaurant_user`
--
ALTER TABLE `restaurant_user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `restaurant_category`
--
ALTER TABLE `restaurant_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `restaurant_items`
--
ALTER TABLE `restaurant_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `restaurant_order`
--
ALTER TABLE `restaurant_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1001;

--
-- AUTO_INCREMENT for table `restaurant_order_item`
--
ALTER TABLE `restaurant_order_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `restaurant_promo`
--
ALTER TABLE `restaurant_promo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `restaurant_table`
--
ALTER TABLE `restaurant_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `restaurant_tax`
--
ALTER TABLE `restaurant_tax`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `restaurant_user`
--
ALTER TABLE `restaurant_user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
