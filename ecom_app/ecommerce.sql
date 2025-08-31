-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 31, 2025 at 10:51 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

DROP TABLE IF EXISTS `cart`;
CREATE TABLE IF NOT EXISTS `cart` (
  `id` int NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(100) NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `order_id` int NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(100) DEFAULT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `order_date` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `customer_name`, `total_amount`, `order_date`) VALUES
(1, 'Static Customer', 45.98, '2025-08-27 11:24:53'),
(2, 'Static Customer', 15.99, '2025-08-27 11:28:56'),
(3, 'Static Customer', 111.96, '2025-08-27 11:33:42'),
(4, 'Static Customer', 45.98, '2025-08-27 11:38:28'),
(5, 'Static Customer', 95.97, '2025-08-27 11:39:10'),
(6, 'Static Customer', 15.99, '2025-08-27 11:42:11'),
(7, 'Static Customer', 29.99, '2025-08-27 11:42:56'),
(8, 'Static Customer', 99.98, '2025-08-27 11:47:05'),
(9, 'Static Customer', 29.99, '2025-08-27 11:48:18'),
(10, 'Static Customer', 15.99, '2025-08-27 11:48:58'),
(11, 'Static Customer', 49.99, '2025-08-27 11:51:31'),
(12, 'Static Customer', 49.99, '2025-08-27 11:57:43'),
(13, 'Static Customer', 49.99, '2025-08-27 11:59:46'),
(14, 'Static Customer', 15.99, '2025-08-27 12:00:21'),
(15, 'Static Customer', 49.99, '2025-08-27 12:00:49'),
(16, 'Static Customer', 31.98, '2025-08-27 12:04:42'),
(17, 'Static Customer', 15.99, '2025-08-27 12:13:29'),
(18, 'Static Customer', 89.97, '2025-08-27 12:14:32'),
(19, 'Static Customer', 29.99, '2025-08-31 15:21:45'),
(20, 'Static Customer', 29.99, '2025-08-31 15:22:41'),
(21, 'Static Customer', 29.99, '2025-08-31 15:28:29');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int DEFAULT NULL,
  `product_id` int DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 1, 1, 15.99),
(2, 1, 3, 1, 29.99),
(3, 2, 1, 1, 15.99),
(4, 3, 2, 1, 49.99),
(5, 3, 1, 2, 15.99),
(6, 3, 3, 1, 29.99),
(7, 4, 1, 1, 15.99),
(8, 4, 3, 1, 29.99),
(9, 5, 3, 1, 29.99),
(10, 5, 1, 1, 15.99),
(11, 5, 2, 1, 49.99),
(12, 6, 1, 1, 15.99),
(13, 7, 3, 1, 29.99),
(14, 8, 2, 2, 49.99),
(15, 9, 3, 1, 29.99),
(16, 10, 1, 1, 15.99),
(17, 11, 2, 1, 49.99),
(18, 12, 2, 1, 49.99),
(19, 13, 2, 1, 49.99),
(20, 14, 1, 1, 15.99),
(21, 15, 2, 1, 49.99),
(22, 16, 1, 2, 15.99),
(23, 17, 1, 1, 15.99),
(24, 18, 3, 3, 29.99),
(25, 19, 3, 1, 29.99),
(26, 20, 3, 1, 29.99),
(27, 21, 3, 1, 29.99);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `description` text,
  `price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`) VALUES
(1, 'T-Shirt', 'Cool cotton t-shirt', 15.99, 'tshirt.jpg'),
(2, 'Sneakers', 'Stylish sneakers', 49.99, 'sneakers.jpg'),
(3, 'Backpack', 'Durable backpack', 29.99, 'backpack.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(4, 'new', 'new@gmail.com', '$2y$10$bOljlGOiFAyUbD52pxupA.K.epTHLXQl5ELmPxDWCB.LJCnT/XKLS', '2025-08-31 10:00:52'),
(3, 'Elakiya', 'elakiya@gmail.com', '$2y$10$eZ2xfMf0csPU4NZkdbT.L.l3CHj8/L5e.TMo.882fgv.pfiJXIaRi', '2025-08-31 09:51:25');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
