-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 04, 2025 at 04:12 AM
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
-- Database: `billing_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `bills`
--

CREATE TABLE `bills` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `bill_date` date NOT NULL DEFAULT current_timestamp(),
  `total_amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bills`
--

INSERT INTO `bills` (`id`, `customer_name`, `bill_date`, `total_amount`, `created_at`) VALUES
(5, 'Rahul Ssingh', '2025-08-03', 18801.24, '2025-08-03 04:48:24'),
(6, 'Rahul', '2025-08-03', 18801.24, '2025-08-03 04:48:24'),
(7, 'Garima', '2025-08-03', 295.00, '2025-08-03 04:48:24'),
(8, 'Ajay Singh', '2025-08-03', 28465.14, '2025-08-03 04:48:24'),
(9, 'Amita', '2025-08-03', 116253.60, '2025-08-03 05:37:26');

-- --------------------------------------------------------

--
-- Table structure for table `bill_items`
--

CREATE TABLE `bill_items` (
  `id` int(11) NOT NULL,
  `bill_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `tax_amount` decimal(10,2) NOT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `postingDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bill_items`
--

INSERT INTO `bill_items` (`id`, `bill_id`, `product_id`, `quantity`, `price`, `tax_amount`, `total_amount`, `postingDate`) VALUES
(3, 6, 2, 1, 14458.00, 4048.24, 18506.24, '2025-08-03 04:24:07'),
(4, 6, 3, 1, 250.00, 45.00, 295.00, '2025-08-03 04:24:07'),
(5, 7, 3, 1, 250.00, 45.00, 295.00, '2025-08-03 04:24:07'),
(6, 8, 5, 1, 24123.00, 4342.14, 28465.14, '2025-08-03 04:24:07'),
(7, 9, 6, 1, 98520.00, 17733.60, 116253.60, '2025-08-03 05:37:26');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(100) DEFAULT NULL,
  `tax_rate` decimal(5,2) DEFAULT NULL,
  `added_by` int(11) NOT NULL,
  `creation_date` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`, `tax_rate`, `added_by`, `creation_date`) VALUES
(1, 'Mobiles', 28.00, 2, '2025-08-02 18:30:57'),
(2, 'Cloths', 5.00, 2, '2025-08-02 18:31:11'),
(3, 'Food', 5.00, 2, '2025-08-02 18:31:18'),
(6, 'Electronic Items', 18.00, 2, '2025-08-03 03:31:51'),
(7, 'Home Appliances', 28.00, 2, '2025-08-03 04:11:10'),
(8, 'Refrigerator ', 18.00, 2, '2025-08-03 04:11:40'),
(9, 'Two Wheelers', 18.00, 2, '2025-08-03 05:36:19');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `added_by` int(11) DEFAULT NULL,
  `creation_date` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `category_id`, `added_by`, `creation_date`) VALUES
(2, 'Motorola ZX12', 14458.00, 1, 2, '2025-08-03 02:59:28'),
(3, 'Mobile Charger', 250.00, 6, 2, '2025-08-03 03:32:07'),
(4, 'AC', 34589.00, 7, 2, '2025-08-03 04:11:25'),
(5, 'Whirlpool D12', 24123.00, 8, 2, '2025-08-03 04:12:02'),
(6, 'Activa 110 CC Classic', 98520.00, 9, 2, '2025-08-03 05:36:56');

-- --------------------------------------------------------

--
-- Table structure for table `tbladmin`
--

CREATE TABLE `tbladmin` (
  `ID` int(11) NOT NULL,
  `AdminName` varchar(120) DEFAULT NULL,
  `AdminuserName` varchar(20) DEFAULT NULL,
  `MobileNumber` bigint(10) DEFAULT NULL,
  `Email` varchar(120) DEFAULT NULL,
  `Password` varchar(120) DEFAULT NULL,
  `AdminRegdate` timestamp NULL DEFAULT current_timestamp(),
  `UserType` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbladmin`
--

INSERT INTO `tbladmin` (`ID`, `AdminName`, `AdminuserName`, `MobileNumber`, `Email`, `Password`, `AdminRegdate`, `UserType`) VALUES
(2, 'Admin', 'admin', 1234567890, 'admin@gmail.com', 'f925916e2754e5e03f75dd58a5733251', '2025-04-30 18:30:00', 1),
(9, 'Admin 2', 'test12', 1231231234, 'admin@t.com', 'f925916e2754e5e03f75dd58a5733251', '2025-06-05 14:41:12', 0),
(10, 'Test Subadmin', 'testadmin', 1231231230, 'testsubadmin@t.com', 'f925916e2754e5e03f75dd58a5733251', '2025-06-10 14:14:02', 0),
(12, 'John Doe', 'john12', 1425362514, 'johndeo12@gmail.com', 'f925916e2754e5e03f75dd58a5733251', '2025-08-03 05:35:40', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bills`
--
ALTER TABLE `bills`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bill_items`
--
ALTER TABLE `bill_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bill_id` (`bill_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `tbladmin`
--
ALTER TABLE `tbladmin`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bills`
--
ALTER TABLE `bills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `bill_items`
--
ALTER TABLE `bill_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tbladmin`
--
ALTER TABLE `tbladmin`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bill_items`
--
ALTER TABLE `bill_items`
  ADD CONSTRAINT `bill_items_ibfk_1` FOREIGN KEY (`bill_id`) REFERENCES `bills` (`id`),
  ADD CONSTRAINT `bill_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
