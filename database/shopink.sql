-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 25, 2025 at 08:42 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shopink`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('moderator','full_admin') DEFAULT 'moderator',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'adminuser', 'admin@example.com', '$2y$10$tOf6gYIHWnZ8IFtPVuTLpe4PRL5M9BsZ8Fn1oshiuDDm/ZoPn/.7K', 'full_admin', '2025-06-06 11:29:45'),
(2, 'moderator_user', 'moderator@example.com', '$2y$10$JTpt1W/RdUp5so9icrTz6OyfRLjOSooFf2l6jef1kGa8uaesyh/5O', 'moderator', '2025-06-06 12:14:26');

-- --------------------------------------------------------

--
-- Table structure for table `Categories`
--

CREATE TABLE `Categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Categories`
--

INSERT INTO `Categories` (`category_id`, `name`, `description`) VALUES
(1, 'Electronics', 'Devices such as laptops, phones, and gadgets.'),
(2, 'Audio', 'Headphones, speakers, and sound systems.'),
(3, 'Fashion', 'Clothing, shoes, and accessories.'),
(4, 'Jewelry', 'Rings, bracelets, necklaces and fine accessories.'),
(5, 'Men\'s Clothing', 'Shirts, jackets, and everyday wear for men.'),
(6, 'Women\'s Clothing', 'Tops, jackets, and fashionwear for women.'),
(7, 'Fitness', 'Gear and equipment for training and activewear.'),
(8, 'Beauty', 'Skincare, makeup, and grooming essentials.'),
(9, 'Snacks', 'Sweet and savory snack products.'),
(10, 'Home & Kitchen', 'Furniture, appliances, and kitchen essentials.'),
(11, 'Toys & Games', 'Toys, puzzles, board games, and kids entertainment.'),
(12, 'Books', 'Fiction, non-fiction, educational and more.'),
(13, 'Health & Wellness', 'Supplements, personal care, and hygiene.'),
(14, 'Sports & Outdoors', 'Outdoor gear, activewear, and fitness accessories.'),
(15, 'Automotive', 'Car accessories, tools, and vehicle electronics.'),
(16, 'Baby Products', 'Products for newborns, toddlers, and parenting.'),
(17, 'Office Supplies', 'Stationery, printers, chairs, and office furniture.'),
(18, 'Pet Supplies', 'Food, toys, and accessories for pets.'),
(19, 'Groceries', 'Pantry items, beverages, and household food supplies.'),
(20, 'Tools & Hardware', 'DIY tools, construction materials, and hardware.'),
(21, 'Jewelery', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `financials`
--

CREATE TABLE `financials` (
  `transaction_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_status` enum('pending','completed','failed') DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `transaction_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `financials`
--

INSERT INTO `financials` (`transaction_id`, `user_id`, `product_id`, `quantity`, `total_amount`, `payment_status`, `payment_method`, `transaction_date`) VALUES
(2, 6, 8, 2, 23999.98, 'completed', 'card', '2025-06-01 08:15:00'),
(3, 9, 26, 3, 32.97, 'completed', 'bank_transfer', '2025-06-02 09:30:00'),
(4, 9, 22, 2, 31.98, 'completed', 'bank_transfer', '2025-06-02 09:30:00'),
(5, 9, 21, 4, 223.96, 'completed', 'bank_transfer', '2025-06-02 09:30:00'),
(6, 21, 8, 4, 47999.96, 'pending', 'card', '2025-06-03 11:45:00'),
(7, 21, 27, 1, 64.00, 'pending', 'card', '2025-06-03 11:45:00'),
(8, 21, 20, 3, 66.90, 'pending', 'card', '2025-06-03 11:45:00'),
(9, 24, 5, 3, 34499.97, 'failed', 'card', '2025-06-04 12:20:00'),
(10, 24, 6, 1, 1299.99, 'failed', 'card', '2025-06-04 12:20:00'),
(11, 25, 24, 1, 168.00, 'completed', 'cash_on_delivery', '2025-06-05 13:10:00'),
(12, 26, 23, 2, 1390.00, 'completed', 'card', '2025-06-06 14:25:00'),
(13, 26, 25, 4, 39.96, 'completed', 'card', '2025-06-06 14:25:00'),
(14, 30, 19, 2, 219.90, 'completed', 'card', '2025-06-07 15:35:00'),
(15, 6, 22, 1, 15.99, 'pending', 'bank_transfer', '2025-06-08 16:45:00'),
(16, 9, 28, 2, 218.00, 'completed', 'card', '2025-06-09 17:55:00'),
(17, 21, 7, 3, 5339.97, 'completed', 'cash_on_delivery', '2025-06-10 18:05:00'),
(18, 21, 20, 2, 44.60, 'completed', 'cash_on_delivery', '2025-06-10 18:05:00'),
(1000, 5, 6, 2, 2599.98, 'pending', 'cod', '2023-03-23 22:00:00'),
(1001, 9, 7, 2, 3559.98, 'failed', 'cod', '2023-02-03 22:00:00'),
(1002, 22, 6, 5, 6499.95, 'pending', 'credit_card', '2023-12-20 22:00:00'),
(1005, 9, 5, 5, 57499.95, 'pending', 'eft', '2024-05-31 22:00:00'),
(1006, 6, 19, 4, 439.80, 'pending', 'cod', '2023-08-08 22:00:00'),
(1007, 33, 21, 3, 167.97, 'pending', 'credit_card', '2023-07-04 22:00:00'),
(1011, 6, 21, 2, 111.98, 'pending', 'paypal', '2023-10-28 22:00:00'),
(1012, 33, 6, 1, 1299.99, 'pending', 'credit_card', '2024-05-28 22:00:00'),
(1013, 21, 20, 5, 111.50, 'failed', 'credit_card', '2023-03-15 22:00:00'),
(1015, 21, 7, 1, 1779.99, 'failed', 'eft', '2023-07-07 22:00:00'),
(1016, 6, 19, 4, 439.80, 'pending', 'cod', '2023-12-16 22:00:00'),
(5000, 23, 6, 2, 2299.98, 'completed', 'credit_card', '2023-02-14 08:36:00'),
(5001, 23, 5, 1, 11499.99, 'completed', 'credit_card', '2023-03-08 12:15:00'),
(5002, 23, 6, 1, 1299.99, 'failed', 'paypal', '2023-05-21 14:46:00'),
(5003, 23, 7, 2, 3549.98, 'pending', 'bank_transfer', '2023-06-10 07:21:00'),
(5008, 23, 7, 1, 1779.99, 'failed', 'paypal', '2023-11-14 06:11:00'),
(5010, 33, 208, 2, 240.00, 'completed', 'credit_card', '2025-06-25 05:15:29'),
(5011, 33, 209, 1, 250.00, 'completed', 'paypal', '2025-06-25 05:15:29'),
(5012, 33, 210, 1, 75.50, 'completed', 'mobile_money', '2025-06-25 05:15:29');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `order_status` enum('pending','shipped','delivered','cancelled') DEFAULT 'pending',
  `total_cost` decimal(10,2) NOT NULL,
  `shipping_address` text DEFAULT NULL,
  `seller_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `order_date`, `order_status`, `total_cost`, `shipping_address`, `seller_id`) VALUES
(1, 6, '2025-06-01 08:15:00', 'pending', 549.99, '12 Long St, Cape Town', 35),
(2, 9, '2025-06-02 12:25:00', 'shipped', 899.50, '34 Greenmarket Sq, Cape Town', 5),
(3, 21, '2025-06-03 07:05:00', 'delivered', 1299.00, '78 Loop St, Cape Town', 35),
(4, 24, '2025-06-03 13:30:00', 'cancelled', 399.00, '101 Kloof St, Cape Town', 23),
(5, 25, '2025-06-04 09:45:00', 'delivered', 259.99, '55 Main Rd, Claremont', 22),
(6, 26, '2025-06-04 14:20:00', 'shipped', 999.00, '2 Beach Rd, Sea Point', 22),
(7, 30, '2025-06-05 11:40:00', 'pending', 649.75, '99 Roeland St, Cape Town', 29),
(8, 6, '2025-06-05 06:55:00', 'delivered', 349.50, '45 Church St, Woodstock', 22),
(9, 9, '2025-06-06 08:10:00', 'pending', 749.99, '32 Victoria Rd, Camps Bay', 27),
(10, 21, '2025-06-06 10:30:00', 'shipped', 1250.00, '10 Bree St, Cape Town', 23),
(11, 24, '2025-06-06 13:15:00', 'delivered', 440.00, '89 Albert Rd, Salt River', 29),
(12, 25, '2025-06-07 07:50:00', 'cancelled', 199.00, '22 Harrington St, Cape Town', 23),
(13, 26, '2025-06-07 09:05:00', 'delivered', 875.25, '77 Strand St, Cape Town', 5),
(14, 30, '2025-06-08 12:00:00', 'shipped', 600.00, '16 Hope St, Cape Town', 35),
(15, 6, '2025-06-08 14:45:00', 'pending', 1050.99, '4 Mill St, Gardens', 27),
(100, 32, '2025-05-22 08:25:57', 'pending', 2076.30, '90 Example St, Cape Town', 29),
(101, 25, '2025-06-04 08:25:57', 'pending', 7676.31, '78 Example St, Cape Town', 29),
(102, 21, '2025-05-10 08:25:57', 'shipped', 473.78, '90 Example St, Cape Town', 29),
(104, 9, '2025-05-09 08:25:57', 'pending', 12396.65, '39 Example St, Cape Town', 29),
(105, 31, '2025-06-05 08:25:57', 'delivered', 3405.12, '57 Example St, Cape Town', 35),
(106, 21, '2025-06-03 08:25:57', 'pending', 5976.94, '86 Example St, Cape Town', 35),
(107, 25, '2025-05-17 08:25:57', 'pending', 405.54, '29 Example St, Cape Town', 35),
(108, 31, '2025-06-20 08:25:57', 'pending', 1799.88, '91 Example St, Cape Town', 35),
(109, 24, '2025-05-09 08:25:57', 'delivered', 3379.86, '88 Example St, Cape Town', 35),
(110, 24, '2025-05-24 08:25:57', 'pending', 1926.52, '99 Example St, Cape Town', 35),
(111, 30, '2025-06-23 08:25:57', 'delivered', 1880.45, '19 Example St, Cape Town', NULL),
(112, 21, '2025-05-03 08:25:57', 'delivered', 410.32, '36 Example St, Cape Town', NULL),
(113, 24, '2025-06-04 08:25:57', 'shipped', 1839.57, '43 Example St, Cape Town', NULL),
(114, 26, '2025-05-10 08:25:57', 'pending', 4726.84, '45 Example St, Cape Town', NULL),
(115, 32, '2025-05-16 08:25:57', 'delivered', 7091.35, '50 Example St, Cape Town', NULL),
(116, 25, '2025-06-18 08:25:57', 'delivered', 4656.36, '24 Example St, Cape Town', NULL),
(117, 26, '2025-06-02 08:25:57', 'shipped', 276.77, '30 Example St, Cape Town', NULL),
(118, 6, '2025-06-12 08:25:57', 'pending', 6473.66, '66 Example St, Cape Town', NULL),
(119, 21, '2025-04-29 08:25:57', 'shipped', 5743.30, '44 Example St, Cape Town', NULL),
(400, 22, '2025-06-15 08:00:00', 'delivered', 1299.99, '14 Long St, Cape Town', 5),
(401, 23, '2025-06-16 10:30:00', 'pending', 2599.99, '55 Main Rd, Cape Town', 29),
(402, 27, '2025-06-17 13:00:00', 'shipped', 1998.50, '88 Kloof St, Cape Town', 29),
(403, 28, '2025-06-18 15:45:00', 'delivered', 899.50, '23 Loop St, Cape Town', 31),
(404, 5, '2023-03-24 12:30:00', 'shipped', 2299.99, '123 Main Road, Cape Town', NULL),
(405, 23, '2023-04-10 07:00:00', 'delivered', 4550.50, '45 Sandton Blvd, Johannesburg', NULL),
(407, 32, '2023-08-05 09:15:00', 'cancelled', 1520.75, '2 Victoria Rd, Durban', NULL),
(408, 31, '2023-11-01 08:05:00', 'delivered', 379.99, '7 Long Street, Stellenbosch', NULL),
(2000, 21, '2023-02-14 08:35:00', 'delivered', 2299.98, '123 Flower St, Lusaka', 23),
(2001, 22, '2023-03-08 12:12:00', 'shipped', 11499.99, '456 Tech Ave, Ndola', 23),
(2002, 23, '2023-05-21 14:45:00', 'cancelled', 1299.99, '78 Urban Rd, Kitwe', 23),
(2003, 5, '2023-06-10 07:20:00', 'pending', 3549.98, '99 Market St, Livingstone', 23),
(2004, 21, '2023-07-18 15:05:00', 'delivered', 22.30, '123 Flower St, Lusaka', 29),
(2005, 22, '2023-08-04 09:30:00', 'delivered', 55.99, '456 Tech Ave, Ndola', 29),
(2006, 23, '2023-09-02 17:45:00', 'shipped', 219.90, '78 Urban Rd, Kitwe', 29),
(2007, 6, '2023-10-25 11:00:00', 'delivered', 11999.99, '1 Sunset Blvd, Lusaka', 35),
(2008, 9, '2023-11-14 06:10:00', 'cancelled', 1779.99, '12 Harmony Way, Kitwe', 23),
(2009, 33, '2023-12-05 18:55:00', 'delivered', 109.95, '789 Chill Ln, Lusaka', 29),
(2010, 33, '2025-06-18 03:10:46', 'delivered', 240.00, '1 Sample Lane, Lusaka', 29),
(2011, 33, '2025-06-25 05:15:29', 'delivered', 240.00, '1 Sample Lane, Lusaka', 29),
(2012, 33, '2025-06-25 05:15:29', 'delivered', 250.00, '2 Sample Lane, Lusaka', 29),
(2013, 33, '2025-06-25 05:15:29', 'delivered', 75.50, '3 Sample Lane, Lusaka', 29);

-- --------------------------------------------------------

--
-- Table structure for table `Order_Items`
--

CREATE TABLE `Order_Items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Order_Items`
--

INSERT INTO `Order_Items` (`order_item_id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(19, 1, 8, 2, 11999.99),
(20, 2, 26, 3, 10.99),
(21, 2, 22, 2, 15.99),
(22, 2, 21, 4, 55.99),
(23, 3, 8, 4, 11999.99),
(24, 3, 27, 1, 64.00),
(25, 3, 20, 3, 22.30),
(26, 4, 5, 3, 11499.99),
(27, 4, 6, 1, 1299.99),
(28, 5, 24, 1, 168.00),
(29, 6, 23, 2, 695.00),
(30, 6, 25, 4, 9.99),
(31, 7, 19, 2, 109.95),
(32, 8, 22, 1, 15.99),
(33, 9, 28, 2, 109.00),
(34, 10, 7, 3, 1779.99),
(35, 10, 20, 2, 22.30),
(36, 11, 21, 1, 55.99),
(37, 12, 6, 1, 1299.99),
(38, 13, 26, 3, 10.99),
(39, 13, 25, 2, 9.99),
(40, 14, 8, 1, 11999.99),
(41, 15, 27, 2, 64.00),
(200, 100, 105, 1, 760.04),
(201, 100, 104, 1, 1316.26),
(202, 101, 105, 2, 1990.75),
(203, 101, 116, 1, 1281.85),
(204, 101, 107, 2, 1206.48),
(205, 102, 116, 1, 473.78),
(209, 104, 107, 4, 1455.22),
(210, 104, 106, 4, 1410.72),
(500, 400, 200, 1, 1299.99),
(501, 401, 201, 1, 2599.99),
(502, 402, 202, 1, 1099.99),
(503, 402, 204, 1, 899.00),
(504, 403, 203, 1, 899.50),
(1000, 2000, 6, 2, 1149.99),
(1001, 2001, 5, 1, 11499.99),
(1002, 2002, 6, 1, 1299.99),
(1003, 2003, 7, 2, 1774.99),
(1004, 2004, 20, 1, 22.30),
(1005, 2005, 21, 1, 55.99),
(1006, 2006, 20, 3, 73.30),
(1007, 2007, 8, 1, 11999.99),
(1008, 2008, 7, 1, 1779.99),
(1009, 2009, 19, 1, 109.95),
(1010, 2010, 205, 2, 120.00),
(1011, 2011, 208, 2, 120.00),
(1012, 2012, 209, 1, 250.00),
(1013, 2013, 210, 1, 75.50);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_status` enum('pending','completed','failed','refunded') DEFAULT 'pending',
  `payment_method` enum('card','bank_transfer','cash_on_delivery') DEFAULT 'card',
  `amount_paid` decimal(10,2) NOT NULL DEFAULT 0.00,
  `transaction_id` varchar(100) DEFAULT NULL,
  `payment_reference` varchar(100) DEFAULT NULL,
  `payment_channel` varchar(50) DEFAULT 'web'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `order_id`, `payment_date`, `payment_status`, `payment_method`, `amount_paid`, `transaction_id`, `payment_reference`, `payment_channel`) VALUES
(1, 1, '2025-06-01 08:15:00', 'completed', 'card', 2199.99, 'TXN001', 'REF001', 'web'),
(2, 2, '2025-06-02 09:30:00', 'completed', 'bank_transfer', 109.95, 'TXN002', 'REF002', 'mobile'),
(3, 3, '2025-06-03 11:45:00', 'pending', 'card', 55.99, 'TXN003', 'REF003', 'web'),
(4, 4, '2025-06-04 12:20:00', 'failed', 'card', 1299.99, 'TXN004', 'REF004', 'web'),
(5, 5, '2025-06-05 13:10:00', 'completed', 'cash_on_delivery', 64.00, 'TXN005', 'REF005', 'manual'),
(6, 6, '2025-06-06 14:25:00', 'completed', 'card', 168.00, 'TXN006', 'REF006', 'mobile'),
(7, 7, '2025-06-07 15:35:00', 'completed', 'card', 11999.99, 'TXN007', 'REF007', 'web'),
(8, 8, '2025-06-08 16:45:00', 'pending', 'bank_transfer', 9.99, 'TXN008', 'REF008', 'web'),
(9, 9, '2025-06-09 17:55:00', 'completed', 'card', 1779.99, 'TXN009', 'REF009', 'mobile'),
(10, 10, '2025-06-10 18:05:00', 'completed', 'cash_on_delivery', 22.30, 'TXN010', 'REF010', 'manual');

-- --------------------------------------------------------

--
-- Table structure for table `platform_fees`
--

CREATE TABLE `platform_fees` (
  `fee_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `commission_percentage` decimal(5,2) NOT NULL DEFAULT 10.00,
  `calculated_fee` decimal(10,2) NOT NULL,
  `fee_status` enum('charged','refunded') DEFAULT 'charged',
  `fee_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `platform_fees`
--

INSERT INTO `platform_fees` (`fee_id`, `order_id`, `seller_id`, `commission_percentage`, `calculated_fee`, `fee_status`, `fee_date`) VALUES
(10, 1, 22, 10.00, 54.99, 'charged', '2025-06-01 08:15:00'),
(11, 2, 23, 10.00, 89.95, 'charged', '2025-06-02 12:25:00'),
(12, 3, 29, 10.00, 129.90, 'charged', '2025-06-03 07:05:00'),
(13, 5, 27, 10.00, 25.99, 'charged', '2025-06-04 09:45:00'),
(14, 6, 28, 10.00, 99.90, 'charged', '2025-06-04 14:20:00'),
(15, 7, 29, 10.00, 64.98, 'charged', '2025-06-05 11:40:00'),
(16, 8, 22, 10.00, 34.95, 'charged', '2025-06-05 06:55:00'),
(17, 9, 23, 10.00, 75.00, 'charged', '2025-06-06 08:10:00'),
(18, 10, 23, 10.00, 125.00, 'charged', '2025-06-06 10:30:00'),
(19, 11, 27, 10.00, 44.00, 'charged', '2025-06-06 13:15:00'),
(20, 13, 28, 10.00, 87.53, 'charged', '2025-06-07 09:05:00'),
(21, 14, 29, 10.00, 60.00, 'charged', '2025-06-08 12:00:00'),
(22, 15, 22, 10.00, 105.10, 'charged', '2025-06-08 14:45:00');

-- --------------------------------------------------------

--
-- Table structure for table `Products`
--

CREATE TABLE `Products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `category_id` int(11) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `seller_id` int(11) DEFAULT NULL,
  `discount_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Products`
--

INSERT INTO `Products` (`product_id`, `name`, `description`, `price`, `stock_quantity`, `category_id`, `image_url`, `created_at`, `seller_id`, `discount_price`) VALUES
(5, 'Gaming Laptop', 'High-performance laptop for gaming and work.', 11499.99, 5, 1, 'laptop.jpg', '2025-03-24 11:00:50', 23, NULL),
(6, 'Wireless Headphones', 'Noise-canceling Bluetooth headphones.', 1299.99, 10, 2, 'headphones.jpeg', '2025-03-24 11:00:50', 23, NULL),
(7, 'Running Shoes', 'Comfortable and stylish running shoes.', 1779.99, 20, 3, 'shoes.jpeg', '2025-03-24 11:00:50', 23, NULL),
(8, 'Smartphone', 'Latest model with powerful features.', 11999.99, 8, 1, 'smartphone.jpeg', '2025-03-24 11:00:50', 35, NULL),
(19, 'Fjallraven - Foldsack No. 1 Backpack', 'Your perfect pack for everyday use and walks in the forest. Stash your laptop (up to 15 inches) in the padded sleeve, your everyday', 109.95, 20, 3, 'fjaellraeven-foldsack-no1-daypack.jpg', '2025-06-05 12:52:56', 29, NULL),
(20, 'Mens Casual Premium Slim Fit T-Shirts', 'Slim-fitting style, contrast raglan long sleeve, three-button henley placket, light weight & soft fabric for breathable and comfortable wearing.', 22.30, 35, 3, 'tshirt.jpeg', '2025-06-05 12:52:56', 29, NULL),
(21, 'Mens Cotton Jacket', 'Great outerwear jacket for Spring/Autumn/Winter, suitable for working, hiking, camping, and more.', 55.99, 50, 3, 'cotton_jacket.jpeg', '2025-06-05 12:52:56', 29, NULL),
(22, 'Mens Casual Slim Fit', 'Please note that body builds vary. Size chart available below.', 15.99, 45, 3, 'https://fakestoreapi.com/img/71YXzeOuslL._AC_UY879_.jpg', '2025-06-05 12:52:56', 22, NULL),
(23, 'John Hardy Women\'s Naga Bracelet', 'Inspired by the mythical dragon that protects the ocean\'s pearl. Wear inward for love, outward for protection.', 695.00, 10, 4, 'john_hardy_naga_bracelet.jpg', '2025-06-05 12:52:56', 22, NULL),
(24, 'Solid Gold Petite Micropave', 'Return or exchange within 30 days. Designed and sold by Hafeez Center.', 168.00, 15, 4, 'https://fakestoreapi.com/img/61sbMiUnoGL._AC_UL640_QL65_ML3_.jpg', '2025-06-05 12:52:56', 22, NULL),
(25, 'White Gold Plated Princess Ring', 'Classic wedding or engagement ring. Ideal for anniversary or Valentine\'s Day.', 9.99, 40, 4, 'https://fakestoreapi.com/img/71YAIFU48IL._AC_UL640_QL65_ML3_.jpg', '2025-06-05 12:52:56', 5, NULL),
(26, 'Pierced Owl Rose Gold Plated Tunnel Earrings', 'Rose Gold Plated Double Flared Tunnel Plug Earrings. Made of 316L Stainless Steel.', 10.99, 25, 4, 'https://fakestoreapi.com/img/51UDEzMJVpL._AC_UL640_QL65_ML3_.jpg', '2025-06-05 12:52:56', 5, NULL),
(27, 'WD 2TB Elements Portable External Hard Drive', 'USB 3.0 and USB 2.0 compatible. Fast transfers and improved PC performance.', 64.00, 30, 1, 'https://fakestoreapi.com/img/61IBBVJvSDL._AC_SY879_.jpg', '2025-06-05 12:52:56', 27, NULL),
(28, 'SanDisk SSD PLUS 1TB Internal SSD', 'Faster boot up, application load and response. Read/write speeds up to 535MB/s.', 109.00, 40, 1, 'https://fakestoreapi.com/img/61U7T1koQqL._AC_SX679_.jpg', '2025-06-05 12:52:56', 27, NULL),
(29, 'Fjallraven - Foldsack No. 1 Backpack, Fits 15 Laptops', 'Your perfect pack for everyday use and walks in the forest. Stash your laptop (up to 15 inches) in the padded sleeve, your everyday', 109.95, 28, 5, 'https://fakestoreapi.com/img/81fPKd-2AYL._AC_SL1500_.jpg', '2025-06-23 20:52:22', 29, NULL),
(30, 'Mens Casual Premium Slim Fit T-Shirts ', 'Slim-fitting style, contrast raglan long sleeve, three-button henley placket, light weight & soft fabric for breathable and comfortable wearing. And Solid stitched shirts with round neck made for durability and a great fit for casual fashion wear and diehard baseball fans. The Henley style round neckline includes a three-button placket.', 22.30, 14, 5, 'https://fakestoreapi.com/img/71-3HjGNDUL._AC_SY879._SX._UX._SY._UY_.jpg', '2025-06-23 20:52:22', 29, NULL),
(31, 'Mens Cotton Jacket', 'great outerwear jackets for Spring/Autumn/Winter, suitable for many occasions, such as working, hiking, camping, mountain/rock climbing, cycling, traveling or other outdoors. Good gift choice for you or your family member. A warm hearted love to Father, husband or son in this thanksgiving or Christmas Day.', 55.99, 11, 5, 'https://fakestoreapi.com/img/71li-ujtlUL._AC_UX679_.jpg', '2025-06-23 20:52:22', 29, NULL),
(32, 'Mens Casual Slim Fit', 'The color could be slightly different between on the screen and in practice. / Please note that body builds vary by person, therefore, detailed size information should be reviewed below on the product description.', 15.99, 20, 5, 'https://fakestoreapi.com/img/71YXzeOuslL._AC_UY879_.jpg', '2025-06-23 20:52:22', 29, NULL),
(33, 'John Hardy Women\'s Legends Naga Gold & Silver Dragon Station Chain Bracelet', 'From our Legends Collection, the Naga was inspired by the mythical water dragon that protects the ocean\'s pearl. Wear facing inward to be bestowed with love and abundance, or outward for protection.', 695.00, 16, 21, 'https://fakestoreapi.com/img/71pWzhdJNwL._AC_UL640_QL65_ML3_.jpg', '2025-06-23 20:52:22', 29, NULL),
(34, 'Solid Gold Petite Micropave ', 'Satisfaction Guaranteed. Return or exchange any order within 30 days.Designed and sold by Hafeez Center in the United States. Satisfaction Guaranteed. Return or exchange any order within 30 days.', 168.00, 29, 21, 'https://fakestoreapi.com/img/61sbMiUnoGL._AC_UL640_QL65_ML3_.jpg', '2025-06-23 20:52:22', 29, NULL),
(35, 'White Gold Plated Princess', 'Classic Created Wedding Engagement Solitaire Diamond Promise Ring for Her. Gifts to spoil your love more for Engagement, Wedding, Anniversary, Valentine\'s Day...', 9.99, 30, 21, 'https://fakestoreapi.com/img/71YAIFU48IL._AC_UL640_QL65_ML3_.jpg', '2025-06-23 20:52:22', 29, NULL),
(36, 'Pierced Owl Rose Gold Plated Stainless Steel Double', 'Rose Gold Plated Double Flared Tunnel Plug Earrings. Made of 316L Stainless Steel', 10.99, 11, 21, 'https://fakestoreapi.com/img/51UDEzMJVpL._AC_UL640_QL65_ML3_.jpg', '2025-06-23 20:52:22', 29, NULL),
(37, 'WD 2TB Elements Portable External Hard Drive - USB 3.0 ', 'USB 3.0 and USB 2.0 Compatibility Fast data transfers Improve PC Performance High Capacity; Compatibility Formatted NTFS for Windows 10, Windows 8.1, Windows 7; Reformatting may be required for other operating systems; Compatibility may vary depending on user’s hardware configuration and operating system', 64.00, 6, 1, 'https://fakestoreapi.com/img/61IBBVJvSDL._AC_SY879_.jpg', '2025-06-23 20:52:22', 29, NULL),
(38, 'SanDisk SSD PLUS 1TB Internal SSD - SATA III 6 Gb/s', 'Easy upgrade for faster boot up, shutdown, application load and response (As compared to 5400 RPM SATA 2.5” hard drive; Based on published specifications and internal benchmarking tests using PCMark vantage scores) Boosts burst write performance, making it ideal for typical PC workloads The perfect balance of performance and reliability Read/write speeds of up to 535MB/s/450MB/s (Based on internal testing; Performance may vary depending upon drive capacity, host device, OS and application.)', 109.00, 29, 1, 'https://fakestoreapi.com/img/61U7T1koQqL._AC_SX679_.jpg', '2025-06-23 20:52:22', 29, NULL),
(39, 'Silicon Power 256GB SSD 3D NAND A55 SLC Cache Performance Boost SATA III 2.5', '3D NAND flash are applied to deliver high transfer speeds Remarkable transfer speeds that enable faster bootup and improved overall system performance. The advanced SLC Cache Technology allows performance boost and longer lifespan 7mm slim design suitable for Ultrabooks and Ultra-slim notebooks. Supports TRIM command, Garbage Collection technology, RAID, and ECC (Error Checking & Correction) to provide the optimized performance and enhanced reliability.', 109.00, 15, 1, 'https://fakestoreapi.com/img/71kWymZ+c+L._AC_SX679_.jpg', '2025-06-23 20:52:22', 29, NULL),
(40, 'WD 4TB Gaming Drive Works with Playstation 4 Portable External Hard Drive', 'Expand your PS4 gaming experience, Play anywhere Fast and easy, setup Sleek design with high capacity, 3-year manufacturer\'s limited warranty', 114.00, 10, 1, 'https://fakestoreapi.com/img/61mtL65D4cL._AC_SX679_.jpg', '2025-06-23 20:52:22', 29, NULL),
(41, 'Acer SB220Q bi 21.5 inches Full HD (1920 x 1080) IPS Ultra-Thin', '21. 5 inches Full HD (1920 x 1080) widescreen IPS display And Radeon free Sync technology. No compatibility for VESA Mount Refresh Rate: 75Hz - Using HDMI port Zero-frame design | ultra-thin | 4ms response time | IPS panel Aspect ratio - 16: 9. Color Supported - 16. 7 million colors. Brightness - 250 nit Tilt angle -5 degree to 15 degree. Horizontal viewing angle-178 degree. Vertical viewing angle-178 degree 75 hertz', 599.00, 22, 1, 'https://fakestoreapi.com/img/81QpkIctqPL._AC_SX679_.jpg', '2025-06-23 20:52:22', 29, NULL),
(42, 'Samsung 49-Inch CHG90 144Hz Curved Gaming Monitor (LC49HG90DMNXZA) – Super Ultrawide Screen QLED ', '49 INCH SUPER ULTRAWIDE 32:9 CURVED GAMING MONITOR with dual 27 inch screen side by side QUANTUM DOT (QLED) TECHNOLOGY, HDR support and factory calibration provides stunningly realistic and accurate color and contrast 144HZ HIGH REFRESH RATE and 1ms ultra fast response time work to eliminate motion blur, ghosting, and reduce input lag', 999.99, 26, 1, 'https://fakestoreapi.com/img/81Zt42ioCgL._AC_SX679_.jpg', '2025-06-23 20:52:22', 29, NULL),
(43, 'BIYLACLESEN Women\'s 3-in-1 Snowboard Jacket Winter Coats', 'Note:The Jackets is US standard size, Please choose size as your usual wear Material: 100% Polyester; Detachable Liner Fabric: Warm Fleece. Detachable Functional Liner: Skin Friendly, Lightweigt and Warm.Stand Collar Liner jacket, keep you warm in cold weather. Zippered Pockets: 2 Zippered Hand Pockets, 2 Zippered Pockets on Chest (enough to keep cards or keys)and 1 Hidden Pocket Inside.Zippered Hand Pockets and Hidden Pocket keep your things secure. Humanized Design: Adjustable and Detachable Hood and Adjustable cuff to prevent the wind and water,for a comfortable fit. 3 in 1 Detachable Design provide more convenience, you can separate the coat and inner as needed, or wear it together. It is suitable for different season and help you adapt to different climates', 56.99, 25, 6, 'https://fakestoreapi.com/img/51Y5NI-I5jL._AC_UX679_.jpg', '2025-06-23 20:52:22', 29, NULL),
(44, 'Lock and Love Women\'s Removable Hooded Faux Leather Moto Biker Jacket', '100% POLYURETHANE(shell) 100% POLYESTER(lining) 75% POLYESTER 25% COTTON (SWEATER), Faux leather material for style and comfort / 2 pockets of front, 2-For-One Hooded denim style faux leather jacket, Button detail on waist / Detail stitching at sides, HAND WASH ONLY / DO NOT BLEACH / LINE DRY / DO NOT IRON', 29.95, 16, 6, 'https://fakestoreapi.com/img/81XH0e8fefL._AC_UY879_.jpg', '2025-06-23 20:52:22', 29, NULL),
(45, 'Rain Jacket Women Windbreaker Striped Climbing Raincoats', 'Lightweight perfet for trip or casual wear---Long sleeve with hooded, adjustable drawstring waist design. Button and zipper front closure raincoat, fully stripes Lined and The Raincoat has 2 side pockets are a good size to hold all kinds of things, it covers the hips, and the hood is generous but doesn\'t overdo it.Attached Cotton Lined Hood with Adjustable Drawstrings give it a real styled look.', 39.99, 23, 6, 'https://fakestoreapi.com/img/71HblAHs5xL._AC_UY879_-2.jpg', '2025-06-23 20:52:22', 29, NULL),
(46, 'MBJ Women\'s Solid Short Sleeve Boat Neck V ', '95% RAYON 5% SPANDEX, Made in USA or Imported, Do Not Bleach, Lightweight fabric with great stretch for comfort, Ribbed on sleeves and neckline / Double stitching on bottom hem', 9.85, 28, 6, 'https://fakestoreapi.com/img/71z3kpMAYsL._AC_UY879_.jpg', '2025-06-23 20:52:22', 29, NULL),
(47, 'Opna Women\'s Short Sleeve Moisture', '100% Polyester, Machine wash, 100% cationic polyester interlock, Machine Wash & Pre Shrunk for a Great Fit, Lightweight, roomy and highly breathable with moisture wicking fabric which helps to keep moisture away, Soft Lightweight Fabric with comfortable V-neck collar and a slimmer fit, delivers a sleek, more feminine silhouette and Added Comfort', 7.95, 15, 6, 'https://fakestoreapi.com/img/51eg55uWmdL._AC_UX679_.jpg', '2025-06-23 20:52:22', 29, NULL),
(48, 'DANVOUY Womens T Shirt Casual Cotton Short', '95%Cotton,5%Spandex, Features: Casual, Short Sleeve, Letter Print,V-Neck,Fashion Tees, The fabric is soft and has some stretch., Occasion: Casual/Office/Beach/School/Home/Street. Season: Spring,Summer,Autumn,Winter.', 12.99, 12, 6, 'https://fakestoreapi.com/img/61pHAEJ4NML._AC_UX679_.jpg', '2025-06-23 20:52:22', 29, NULL),
(99, 'AfriEssence Floral Dress', 'This is a high-quality sunglasses item, perfect for modern needs.', 538.88, 46, 5, 'https://dummyjson.com/image/product_1.jpg', '2025-06-23 21:14:57', 29, NULL),
(100, 'Lalela Floral Dress', 'This is a high-quality lighting item, perfect for modern needs.', 360.14, 38, 5, 'https://dummyjson.com/image/product_2.jpg', '2025-06-23 21:14:57', 29, NULL),
(101, 'Zenzi Windbreaker Jacket', 'This is a high-quality womens shoes item, perfect for modern needs.', 731.98, 74, 5, 'https://dummyjson.com/image/product_3.jpg', '2025-06-23 21:14:57', 29, NULL),
(102, 'Zenzi Floral Dress', 'This is a high-quality furniture item, perfect for modern needs.', 704.28, 48, 5, 'https://dummyjson.com/image/product_4.jpg', '2025-06-23 21:14:57', 29, NULL),
(103, 'Jozi Active Floral Dress', 'This is a high-quality mens watches item, perfect for modern needs.', 763.30, 77, 5, 'https://dummyjson.com/image/product_5.jpg', '2025-06-23 21:14:57', 29, NULL),
(104, 'Jozi Active Windbreaker Jacket', 'This is a high-quality furniture item, perfect for modern needs.', 782.40, 6, 5, 'https://dummyjson.com/image/product_6.jpg', '2025-06-23 21:14:57', 29, NULL),
(105, 'Lalela Floral Dress', 'This is a high-quality lighting item, perfect for modern needs.', 161.77, 66, 5, 'https://dummyjson.com/image/product_7.jpg', '2025-06-23 21:14:57', 29, NULL),
(106, 'Ubuntu Wear Windbreaker Jacket', 'This is a high-quality sunglasses item, perfect for modern needs.', 533.91, 35, 5, 'https://dummyjson.com/image/product_8.jpg', '2025-06-23 21:14:57', 29, NULL),
(107, 'Kaya Living Slim Fit Shirt', 'This is a high-quality mens watches item, perfect for modern needs.', 446.70, 62, 5, 'https://dummyjson.com/image/product_9.jpg', '2025-06-23 21:14:57', 29, NULL),
(108, 'AfriEssence Leather Sandals', 'This is a high-quality furniture item, perfect for modern needs.', 932.55, 35, 5, 'https://dummyjson.com/image/product_10.jpg', '2025-06-23 21:14:57', 29, NULL),
(109, 'SavannaTech Leather Sandals', 'This is a high-quality furniture item, perfect for modern needs.', 180.80, 46, 5, 'https://dummyjson.com/image/product_11.jpg', '2025-06-23 21:14:57', 29, NULL),
(110, 'Kaya Living Slim Fit Shirt', 'This is a high-quality womens dresses item, perfect for modern needs.', 245.44, 70, 5, 'https://dummyjson.com/image/product_12.jpg', '2025-06-23 21:14:57', 29, NULL),
(111, 'AfriEssence Slim Fit Shirt', 'This is a high-quality automotive item, perfect for modern needs.', 452.33, 67, 5, 'https://dummyjson.com/image/product_13.jpg', '2025-06-23 21:14:57', 29, NULL),
(112, 'Zenzi Floral Dress', 'This is a high-quality groceries item, perfect for modern needs.', 371.45, 44, 5, 'https://dummyjson.com/image/product_14.jpg', '2025-06-23 21:14:57', 29, NULL),
(113, 'Cape Luxe Desk Lamp', 'This is a high-quality home decoration item, perfect for modern needs.', 670.92, 78, 5, 'https://dummyjson.com/image/product_15.jpg', '2025-06-23 21:14:57', 29, NULL),
(114, 'Ubuntu Wear Slim Fit Shirt', 'This is a high-quality tops item, perfect for modern needs.', 256.08, 72, 5, 'https://dummyjson.com/image/product_16.jpg', '2025-06-23 21:14:57', 29, NULL),
(115, 'Zenzi Jogger Pants', 'This is a high-quality womens dresses item, perfect for modern needs.', 208.50, 51, 5, 'https://dummyjson.com/image/product_17.jpg', '2025-06-23 21:14:57', 29, NULL),
(116, 'Umoya Bluetooth Speaker', 'This is a high-quality laptops item, perfect for modern needs.', 9394.95, 62, 5, 'https://dummyjson.com/image/product_18.jpg', '2025-06-23 21:14:57', 29, NULL),
(117, 'Jozi Active Jogger Pants', 'This is a high-quality furniture item, perfect for modern needs.', 528.99, 22, 5, 'https://dummyjson.com/image/product_19.jpg', '2025-06-23 21:14:57', 29, NULL),
(118, 'Kaya Living Floral Dress', 'This is a high-quality womens watches item, perfect for modern needs.', 283.67, 94, 5, 'https://dummyjson.com/image/product_20.jpg', '2025-06-23 21:14:57', 29, NULL),
(119, 'Ubuntu Wear Floral Dress', 'This is a high-quality groceries item, perfect for modern needs.', 889.21, 5, 5, 'https://dummyjson.com/image/product_21.jpg', '2025-06-23 21:14:57', 29, NULL),
(120, 'Jozi Active Windbreaker Jacket', 'This is a high-quality mens shirts item, perfect for modern needs.', 194.56, 6, 5, 'https://dummyjson.com/image/product_22.jpg', '2025-06-23 21:14:57', 29, NULL),
(121, 'Kaya Living Face Cleanser', 'This is a high-quality skincare item, perfect for modern needs.', 152.30, 34, 5, 'https://dummyjson.com/image/product_23.jpg', '2025-06-23 21:14:57', 29, NULL),
(122, 'SavannaTech Bluetooth Speaker', 'This is a high-quality smartphones item, perfect for modern needs.', 8820.61, 97, 5, 'https://dummyjson.com/image/product_24.jpg', '2025-06-23 21:14:57', 29, NULL),
(123, 'AfriEssence Floral Dress', 'This is a high-quality motorcycle item, perfect for modern needs.', 915.59, 27, 5, 'https://dummyjson.com/image/product_25.jpg', '2025-06-23 21:14:57', 29, NULL),
(124, 'AfriEssence Floral Dress', 'This is a high-quality womens dresses item, perfect for modern needs.', 965.61, 42, 5, 'https://dummyjson.com/image/product_26.jpg', '2025-06-23 21:14:57', 29, NULL),
(125, 'Lalela Floral Dress', 'This is a high-quality furniture item, perfect for modern needs.', 257.98, 39, 5, 'https://dummyjson.com/image/product_27.jpg', '2025-06-23 21:14:57', 29, NULL),
(126, 'Mzansi Gear Jogger Pants', 'This is a high-quality womens dresses item, perfect for modern needs.', 707.53, 72, 5, 'https://dummyjson.com/image/product_28.jpg', '2025-06-23 21:14:57', 29, NULL),
(127, 'Lalela Throw Blanket', 'This is a high-quality home decoration item, perfect for modern needs.', 480.18, 27, 5, 'https://dummyjson.com/image/product_29.jpg', '2025-06-23 21:14:57', 29, NULL),
(128, 'Zenzi Floral Dress', 'This is a high-quality womens shoes item, perfect for modern needs.', 404.42, 28, 5, 'https://dummyjson.com/image/product_30.jpg', '2025-06-23 21:14:57', 29, NULL),
(129, 'Cape Luxe Slim Fit Shirt', 'This is a high-quality womens dresses item, perfect for modern needs.', 996.53, 70, 5, 'https://dummyjson.com/image/product_31.jpg', '2025-06-23 21:14:57', 29, NULL),
(130, 'Umoya Floral Dress', 'This is a high-quality fragrances item, perfect for modern needs.', 660.03, 15, 5, 'https://dummyjson.com/image/product_32.jpg', '2025-06-23 21:14:57', 29, NULL),
(131, 'Jozi Active Windbreaker Jacket', 'This is a high-quality mens shoes item, perfect for modern needs.', 539.49, 55, 5, 'https://dummyjson.com/image/product_33.jpg', '2025-06-23 21:14:57', 29, NULL),
(132, 'SavannaTech Jogger Pants', 'This is a high-quality motorcycle item, perfect for modern needs.', 482.26, 51, 5, 'https://dummyjson.com/image/product_34.jpg', '2025-06-23 21:14:57', 29, NULL),
(133, 'Cape Luxe Wireless Earbuds', 'This is a high-quality laptops item, perfect for modern needs.', 14662.39, 49, 5, 'https://dummyjson.com/image/product_35.jpg', '2025-06-23 21:14:57', 29, NULL),
(134, 'Kaya Living Slim Fit Shirt', 'This is a high-quality fragrances item, perfect for modern needs.', 593.64, 15, 5, 'https://dummyjson.com/image/product_36.jpg', '2025-06-23 21:14:57', 29, NULL),
(135, 'Cape Luxe Floral Dress', 'This is a high-quality automotive item, perfect for modern needs.', 961.15, 100, 5, 'https://dummyjson.com/image/product_37.jpg', '2025-06-23 21:14:57', 29, NULL),
(136, 'Mzansi Gear Windbreaker Jacket', 'This is a high-quality womens jewellery item, perfect for modern needs.', 859.96, 36, 5, 'https://dummyjson.com/image/product_38.jpg', '2025-06-23 21:14:57', 29, NULL),
(137, 'Cape Luxe Windbreaker Jacket', 'This is a high-quality furniture item, perfect for modern needs.', 249.02, 23, 5, 'https://dummyjson.com/image/product_39.jpg', '2025-06-23 21:14:57', 29, NULL),
(138, 'Jozi Active Floral Dress', 'This is a high-quality womens shoes item, perfect for modern needs.', 703.61, 38, 5, 'https://dummyjson.com/image/product_40.jpg', '2025-06-23 21:14:57', 29, NULL),
(139, 'Umoya Smartphone', 'This is a high-quality laptops item, perfect for modern needs.', 11331.46, 22, 5, 'https://dummyjson.com/image/product_41.jpg', '2025-06-23 21:14:57', 29, NULL),
(140, 'Umoya Slim Fit Shirt', 'This is a high-quality mens shirts item, perfect for modern needs.', 186.35, 71, 5, 'https://dummyjson.com/image/product_42.jpg', '2025-06-23 21:14:57', 29, NULL),
(141, 'Umoya Floral Dress', 'This is a high-quality womens dresses item, perfect for modern needs.', 333.88, 16, 5, 'https://dummyjson.com/image/product_43.jpg', '2025-06-23 21:14:57', 29, NULL),
(142, 'Kaya Living Slim Fit Shirt', 'This is a high-quality mens shirts item, perfect for modern needs.', 266.54, 38, 5, 'https://dummyjson.com/image/product_44.jpg', '2025-06-23 21:14:57', 29, NULL),
(143, 'Lalela Leather Sandals', 'This is a high-quality womens shoes item, perfect for modern needs.', 726.44, 55, 5, 'https://dummyjson.com/image/product_45.jpg', '2025-06-23 21:14:57', 29, NULL),
(144, 'Ubuntu Wear Laptop', 'This is a high-quality smartphones item, perfect for modern needs.', 13778.67, 73, 5, 'https://dummyjson.com/image/product_46.jpg', '2025-06-23 21:14:57', 29, NULL),
(145, 'Jozi Active Jogger Pants', 'This is a high-quality mens shirts item, perfect for modern needs.', 373.05, 88, 5, 'https://dummyjson.com/image/product_47.jpg', '2025-06-23 21:14:57', 29, NULL),
(146, 'Kaya Living Throw Blanket', 'This is a high-quality home decoration item, perfect for modern needs.', 161.38, 66, 5, 'https://dummyjson.com/image/product_48.jpg', '2025-06-23 21:14:57', 29, NULL),
(147, 'Umoya Slim Fit Shirt', 'This is a high-quality mens shirts item, perfect for modern needs.', 165.90, 11, 5, 'https://dummyjson.com/image/product_49.jpg', '2025-06-23 21:14:57', 29, NULL),
(148, 'Kaya Living Essential Oil Diffuser', 'This is a high-quality home decoration item, perfect for modern needs.', 218.84, 68, 5, 'https://dummyjson.com/image/product_50.jpg', '2025-06-23 21:14:57', 29, NULL),
(149, 'Chanel Classic Flap', 'Medium black lambskin with gold hardware.', 57999.00, 2, NULL, 'chanel_flap.jpeg', '2025-06-24 10:31:22', 29, NULL),
(150, 'YSL Lou Camera Bag', 'Beige leather with chevron quilting.', 17499.00, 5, NULL, 'ysl_camera.jpeg', '2025-06-24 10:31:22', 29, NULL),
(151, 'Rolex Oyster Perpetual', '36mm silver dial automatic watch.', 132000.00, 1, NULL, 'rolex.jpg', '2025-06-24 10:31:22', 29, NULL),
(152, 'Prada Re-Edition 2005', 'Re-nylon shoulder bag with mini pouch.', 15999.00, 4, NULL, 'prada_2005.jpeg', '2025-06-24 10:31:22', 29, NULL),
(153, 'Burberry Trench Coat', 'Beige Kensington trench, size M.', 11999.00, 3, NULL, 'burberry_coat.jpeg', '2025-06-24 10:31:22', 29, 9999.00),
(154, 'Valentino Rockstud Heels', '100mm pumps in black patent.', 8499.00, 2, NULL, 'valentino_heels.jpg', '2025-06-24 10:31:22', 29, 7999.00),
(155, 'Dior Book Tote', 'Medium size with embroidered oblique canvas.', 22499.00, 2, NULL, 'dior_tote.jpg', '2025-06-24 10:31:22', 29, NULL),
(156, 'Gucci Ophidia Crossbody', 'GG Supreme canvas with Web stripe.', 10999.00, 6, NULL, 'gucci_ophidia.jpg', '2025-06-24 10:31:22', 29, NULL),
(157, 'Balenciaga Triple S', 'Chunky sneaker in white/grey.', 10499.00, 4, NULL, 'balenciaga_sneaker.jpg', '2025-06-24 10:31:22', 29, NULL),
(158, 'Cartier Love Bracelet', '18K yellow gold, size 17.', 93500.00, 1, NULL, 'cartier_love.jpg', '2025-06-24 10:31:22', 29, NULL),
(159, 'Bottega Veneta Cassette', 'Green intrecciato leather, crossbody.', 27499.00, 3, NULL, 'bottega_cassette.jpg', '2025-06-24 10:31:22', 31, NULL),
(160, 'Celine Triomphe Bag', 'Smooth tan calfskin, medium.', 28999.00, 1, NULL, 'celine_triomphe.jpg', '2025-06-24 10:31:22', 31, NULL),
(161, 'Fendi Baguette', 'Multicolor embroidered limited edition.', 31999.00, 2, NULL, 'fendi_baguette.jpg', '2025-06-24 10:31:22', 31, NULL),
(162, 'Jacquemus Le Chiquito', 'Mini handbag in lavender.', 6999.00, 6, NULL, 'jacquemus.jpg', '2025-06-24 10:31:22', 31, NULL),
(163, 'Tom Ford Sunglasses', 'Oversized black acetate frame.', 4899.00, 10, NULL, 'tomford_sunglasses.jpg', '2025-06-24 10:31:22', 31, NULL),
(164, 'Balmain Blazer', 'Double-breasted navy wool, gold buttons.', 17999.00, 2, NULL, 'balmain_blazer.jpg', '2025-06-24 10:31:22', 31, NULL),
(165, 'Alexander McQueen Sneaker', 'Oversized white sneakers with red heel tab.', 9499.00, 5, NULL, 'mcqueen_sneaker.jpg', '2025-06-24 10:31:22', 31, NULL),
(166, 'Off-White Industrial Belt', 'Yellow logo belt, unisex.', 3499.00, 8, NULL, 'offwhite_belt.jpg', '2025-06-24 10:31:22', 31, NULL),
(167, 'Givenchy Antigona', 'Small leather satchel in black.', 21499.00, 2, NULL, 'givenchy_antigona.jpg', '2025-06-24 10:31:22', 31, NULL),
(168, 'Loewe Puzzle Bag', 'Classic tan leather version.', 27499.00, 3, NULL, 'loewe_puzzle.jpg', '2025-06-24 10:31:22', 31, NULL),
(200, 'Zanele Leather Bag', NULL, 1299.99, 10, NULL, 'bag1.jpeg', '2025-06-24 10:40:27', 5, NULL),
(201, 'Tebogo Smartwatch', NULL, 2599.99, 8, NULL, 'watch1.jpeg', '2025-06-24 10:40:27', 29, NULL),
(202, 'Tebogo Backpack', NULL, 1099.99, 4, NULL, 'bag2.jpeg', '2025-06-24 10:40:27', 29, NULL),
(203, 'Zanele Sunglasses', NULL, 899.50, 6, NULL, 'sunglasses.jpeg', '2025-06-24 10:40:27', 31, NULL),
(204, 'Luxury Perfume', NULL, 699.00, 5, NULL, 'perfume.jpeg', '2025-06-24 10:40:27', 32, NULL),
(205, 'Test Product A', NULL, 120.00, 30, NULL, 'default.png', '2025-06-25 05:12:53', 29, NULL),
(206, 'Test Product B', NULL, 250.00, 20, NULL, 'default.png', '2025-06-25 05:12:54', 29, NULL),
(207, 'Test Product C', NULL, 75.50, 15, NULL, 'default.png', '2025-06-25 05:12:54', 29, NULL),
(208, 'Test Product A', NULL, 120.00, 30, NULL, 'default.png', '2025-06-25 05:15:29', 29, NULL),
(209, 'Test Product B', NULL, 250.00, 20, NULL, 'default.png', '2025-06-25 05:15:29', 29, NULL),
(210, 'Test Product C', NULL, 75.50, 15, NULL, 'default.png', '2025-06-25 05:15:29', 29, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `Reviews`
--

CREATE TABLE `Reviews` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `review_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Reviews`
--

INSERT INTO `Reviews` (`review_id`, `user_id`, `product_id`, `rating`, `comment`, `review_date`) VALUES
(11, 6, 5, 4, 'Fantastic value for money.', '2025-06-05 22:00:00'),
(12, 9, 6, 3, 'Exceeded my expectations!', '2025-06-06 22:00:00'),
(13, 21, 7, 3, 'Great product, highly recommend!', '2025-06-07 22:00:00'),
(14, 24, 8, 5, 'Very useful and reliable.', '2025-06-08 22:00:00'),
(15, 25, 19, 4, 'Very useful and reliable.', '2025-06-09 22:00:00'),
(16, 26, 20, 5, 'Fantastic value for money.', '2025-06-10 22:00:00'),
(17, 30, 21, 3, 'Very useful and reliable.', '2025-06-11 22:00:00'),
(18, 6, 22, 5, 'Good quality, but delivery was slow.', '2025-06-12 22:00:00'),
(19, 9, 23, 3, 'Good quality, but delivery was slow.', '2025-06-13 22:00:00'),
(20, 21, 24, 3, 'Great product, highly recommend!', '2025-06-14 22:00:00'),
(21, 24, 25, 3, 'I would buy this again.', '2025-06-15 22:00:00'),
(22, 25, 26, 3, 'Not worth the price.', '2025-06-16 22:00:00'),
(23, 26, 27, 3, 'Exceeded my expectations!', '2025-06-17 22:00:00'),
(24, 30, 28, 5, 'Not worth the price.', '2025-06-18 22:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `seller_applications`
--

CREATE TABLE `seller_applications` (
  `application_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `seller_type` enum('individual','business') NOT NULL,
  `id_document` varchar(255) DEFAULT NULL,
  `proof_of_address` varchar(255) DEFAULT NULL,
  `bank_details` varchar(255) DEFAULT NULL,
  `selfie_with_id` varchar(255) DEFAULT NULL,
  `business_license` varchar(255) DEFAULT NULL,
  `tax_number` varchar(100) DEFAULT NULL,
  `product_description` text DEFAULT NULL,
  `application_status` enum('pending','approved','rejected') DEFAULT 'pending',
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seller_applications`
--

INSERT INTO `seller_applications` (`application_id`, `user_id`, `seller_type`, `id_document`, `proof_of_address`, `bank_details`, `selfie_with_id`, `business_license`, `tax_number`, `product_description`, `application_status`, `submitted_at`) VALUES
(1, 5, 'individual', 'docs/id_5.pdf', 'docs/address_5.pdf', 'docs/bank_5.pdf', 'docs/selfie_5.jpg', NULL, 'TAX12345', 'Selling eco-friendly tech accessories.', 'pending', '2025-06-05 08:00:00'),
(2, 23, 'business', 'docs/id_23.pdf', 'docs/address_23.pdf', 'docs/bank_23.pdf', 'docs/selfie_23.jpg', 'docs/license_23.pdf', 'TAX67890', 'Offering handmade artisan goods.', 'approved', '2025-06-03 10:45:00'),
(3, 28, 'individual', 'docs/id_28.pdf', 'docs/address_28.pdf', 'docs/bank_28.pdf', 'docs/selfie_28.jpg', NULL, 'TAX11111', 'Vintage collectibles and comics.', 'rejected', '2025-06-02 12:30:00'),
(5, 22, 'business', '22_id_document.png', '22_proof_of_address.jpg', '22_bank_details.jpg', '22_selfie_with_id.jpg', '', 'TAX55555', 'Fitness and lifestyle gear.', 'pending', '2025-06-04 07:15:00'),
(6, 27, 'individual', 'docs/id_27.pdf', 'docs/address_27.pdf', 'docs/bank_27.pdf', 'docs/selfie_27.jpg', NULL, 'TAX99999', 'Digital art and prints.', 'rejected', '2025-06-01 09:30:00'),
(7, 33, 'individual', '33_id.png', '33_proof.jpg', '33_bank.jpg', '33_selfie.jpg', NULL, '', 'General Seller', 'pending', '2025-06-25 03:35:45'),
(8, 33, 'individual', '33_id.png', '33_proof.jpg', '33_bank.jpg', '33_selfie.jpg', NULL, '', 'General Seller', 'approved', '2025-06-25 03:37:17'),
(9, 35, 'individual', '35_id.png', '35_proof.jpg', '35_bank.jpg', '35_selfie.jpg', NULL, '', 'general', 'approved', '2025-06-25 05:51:56');

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `user_id` int(11) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(225) NOT NULL,
  `last_name` varchar(225) NOT NULL,
  `role` enum('buyer','seller') DEFAULT 'buyer',
  `is_verified` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `seller_application_status` enum('none','pending','approved','rejected') DEFAULT 'none'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`user_id`, `email_address`, `password`, `first_name`, `last_name`, `role`, `is_verified`, `created_at`, `seller_application_status`) VALUES
(5, 'seller@test.com', '$2y$10$PQJNYVt5u3Yuh4eD8L6f2.0ydrN3INc0tPS.C8NP/YwJjEiHG4X7S', '', '', 'seller', 1, '2025-06-04 20:57:31', 'none'),
(6, 'test@buyer', '$2y$10$de/F14vxXu4URTO9aQ8jEO.VA/uB225QO6tBFksC/ADuwv2fr2i4G', 'test', 'buyer', 'buyer', 0, '2025-06-05 14:49:54', 'pending'),
(9, 'test2@buyer', '$2y$10$KFXLLbgterb4KQOT.kxVgefRxy3OuWXQeRVmiP4.OpgFl/44iNiqW', 'test2', 'buyer', 'buyer', 0, '2025-06-06 09:29:58', 'none'),
(21, 'user1@example.com', '$2y$10$examplehashfortestingusers1234567890', 'User1', 'Test', 'buyer', 1, '2025-06-06 10:27:58', 'approved'),
(22, 'user2@example.com', '$2y$10$examplehashfortestingusers1234567890', 'User2', 'Test', 'seller', 0, '2025-06-05 10:27:58', 'pending'),
(23, 'user3@example.com', '$2y$10$examplehashfortestingusers1234567890', 'User3', 'Test', 'seller', 0, '2025-06-04 10:27:58', 'approved'),
(24, 'user4@example.com', '$2y$10$examplehashfortestingusers1234567890', 'User4', 'Test', 'buyer', 0, '2025-06-03 10:27:58', 'rejected'),
(25, 'user5@example.com', '$2y$10$examplehashfortestingusers1234567890', 'User5', 'Test', 'buyer', 0, '2025-06-02 10:27:58', 'pending'),
(26, 'user6@example.com', '$2y$10$examplehashfortestingusers1234567890', 'User6', 'Test', 'buyer', 0, '2025-06-01 10:27:58', 'rejected'),
(27, 'user7@example.com', '$2y$10$examplehashfortestingusers1234567890', 'User7', 'Test', 'seller', 0, '2025-05-31 10:27:58', 'none'),
(28, 'user8@example.com', '$2y$10$examplehashfortestingusers1234567890', 'User8', 'Test', 'seller', 0, '2025-05-30 10:27:58', 'rejected'),
(29, 'user9@example.com', '$2y$10$examplehashfortestingusers1234567890', 'User9', 'Test', 'seller', 1, '2025-05-29 10:27:58', 'approved'),
(30, 'user10@example.com', '$2y$10$examplehashfortestingusers1234567890', 'User10', 'Test', 'buyer', 1, '2025-05-28 10:27:58', 'none'),
(31, 'zanele.seller@example.com', '$2y$10$K9Sau6LXj33F55lvAgppZ.GYNHaiDiSwEQKaYHjDfU7XYZa5uow3u', 'Zanele', 'Nkosi', 'seller', 0, '2025-06-24 10:27:21', 'none'),
(32, 'tebogo.seller@example.com', '$2y$10$K9Sau6LXj33F55lvAgppZ.GYNHaiDiSwEQKaYHjDfU7XYZa5uow3', 'Tebogo', 'Mokoena', 'seller', 0, '2025-06-24 10:27:21', 'none'),
(33, 'temp33@example.com', '$2y$10$.bx3ggVDQKRjKqe.Fj5Zlez0me/qiRdt0n2lTYbaq4ehC8nAqEkEK', 'Temp', 'Temp', 'seller', 0, '2025-06-24 23:52:10', 'none'),
(34, 'test2@test.com', '$2y$10$UjiVoMRMyBxkVY.IbTUOjelOBCO16pv.0J2Zb7B3x0I5PnsssAfHK', 'test2', 'test', 'buyer', 0, '2025-06-25 04:40:41', 'none'),
(35, 'test@test.com', '$2y$10$DLuz8c7Aob6Oy6It18qh4eJGkzl65vgkRoRoBQ1ckn5vvE8xT0EpC', 'test', 'test', 'seller', 0, '2025-06-25 05:51:06', 'none');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `Categories`
--
ALTER TABLE `Categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `financials`
--
ALTER TABLE `financials`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `financials_ibfk_1` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_order_seller` (`seller_id`);

--
-- Indexes for table `Order_Items`
--
ALTER TABLE `Order_Items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `platform_fees`
--
ALTER TABLE `platform_fees`
  ADD PRIMARY KEY (`fee_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `seller_id` (`seller_id`);

--
-- Indexes for table `Products`
--
ALTER TABLE `Products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `fk_seller` (`seller_id`);

--
-- Indexes for table `Reviews`
--
ALTER TABLE `Reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `seller_applications`
--
ALTER TABLE `seller_applications`
  ADD PRIMARY KEY (`application_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email_address` (`email_address`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `Categories`
--
ALTER TABLE `Categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `financials`
--
ALTER TABLE `financials`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5013;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2014;

--
-- AUTO_INCREMENT for table `Order_Items`
--
ALTER TABLE `Order_Items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1014;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `platform_fees`
--
ALTER TABLE `platform_fees`
  MODIFY `fee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `Products`
--
ALTER TABLE `Products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=211;

--
-- AUTO_INCREMENT for table `Reviews`
--
ALTER TABLE `Reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `seller_applications`
--
ALTER TABLE `seller_applications`
  MODIFY `application_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `financials`
--
ALTER TABLE `financials`
  ADD CONSTRAINT `financials_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `financials_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_order_seller` FOREIGN KEY (`seller_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `Order_Items`
--
ALTER TABLE `Order_Items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `Orders` (`order_id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `Products` (`product_id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `Orders` (`order_id`) ON DELETE CASCADE;

--
-- Constraints for table `platform_fees`
--
ALTER TABLE `platform_fees`
  ADD CONSTRAINT `platform_fees_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `Orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `platform_fees_ibfk_2` FOREIGN KEY (`seller_id`) REFERENCES `Users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `Products`
--
ALTER TABLE `Products`
  ADD CONSTRAINT `fk_seller` FOREIGN KEY (`seller_id`) REFERENCES `Users` (`user_id`),
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `Categories` (`category_id`) ON DELETE SET NULL;

--
-- Constraints for table `Reviews`
--
ALTER TABLE `Reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `Products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `seller_applications`
--
ALTER TABLE `seller_applications`
  ADD CONSTRAINT `seller_applications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
