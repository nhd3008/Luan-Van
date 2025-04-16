-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 16, 2025 at 09:09 AM
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
-- Database: `fruit_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `product_id` int(6) NOT NULL,
  `quantity` int(11) NOT NULL,
  `purchase_price` decimal(10,2) DEFAULT NULL,
  `selling_price` double DEFAULT NULL,
  `unit_type` varchar(10) DEFAULT NULL,
  `supplier` varchar(255) DEFAULT NULL,
  `import_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `product_id`, `quantity`, `purchase_price`, `selling_price`, `unit_type`, `supplier`, `import_date`, `created_at`) VALUES
(1, 473170, 11, 11000.00, NULL, NULL, 'Khang', '2025-04-08 15:54:24', '2025-04-09 11:09:00'),
(2, 312786, 50, 50.00, NULL, NULL, 'Kang', '2025-04-08 16:02:59', '2025-04-09 11:09:00'),
(3, 833347, 50, 275000.00, NULL, NULL, 'Kang', '2025-04-08 17:14:48', '2025-04-09 11:09:00'),
(4, 453384, 50, 250000.00, NULL, NULL, 'Kang', '2025-04-08 17:15:39', '2025-04-09 11:09:00'),
(5, 473170, 50, 75000.00, NULL, NULL, 'Kang', '2025-04-08 17:23:27', '2025-04-09 11:09:00'),
(6, 889487, 20, 55000.00, NULL, NULL, 'Kang', '2025-04-09 03:46:48', '2025-04-09 11:09:00'),
(7, 812213, 50, 500000.00, NULL, NULL, 'Kang', '2025-04-09 03:50:02', '2025-04-09 11:09:00'),
(8, 812213, 35, 50000.00, NULL, NULL, 'Kang', '2025-04-09 03:54:58', '2025-04-09 11:09:00'),
(9, 812213, 9, 75000.00, NULL, NULL, 'Kang', '2025-04-09 06:58:07', '2025-04-09 13:58:07'),
(15, 632569, 12, 30000.00, NULL, NULL, 'đông', '2025-04-09 18:31:11', '2025-04-10 01:31:11'),
(20, 691083, 20, 50000.00, NULL, NULL, 'đông', '2025-04-10 14:27:07', '2025-04-10 21:27:07'),
(21, 841025, 100, 25000.00, NULL, NULL, 'Kang', '2025-04-10 16:41:09', '2025-04-10 23:41:09'),
(22, 841025, 100, 50000.00, NULL, NULL, 'Kang', '2025-04-10 16:41:25', '2025-04-10 23:41:25'),
(23, 443778, 100, 200.00, NULL, NULL, 'Kang', '2025-04-10 16:41:49', '2025-04-10 23:41:49');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_transactions`
--

CREATE TABLE `inventory_transactions` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `type` enum('import','export') NOT NULL,
  `quantity` int(11) NOT NULL,
  `import_price` decimal(10,2) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `order_status` enum('pending','shipped','canceled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_method` varchar(50) NOT NULL DEFAULT 'cod'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `total_price`, `order_status`, `created_at`, `payment_method`) VALUES
(11, 471586, 40000.00, 'canceled', '2025-04-10 06:24:24', 'cod'),
(12, 471586, 150000.00, 'shipped', '2025-04-10 14:29:57', 'momo'),
(13, 471586, 25000.00, 'shipped', '2025-04-10 15:11:01', 'momo'),
(14, 471586, 1567500.00, 'shipped', '2025-04-10 16:32:17', 'cod'),
(15, 471586, 12500.00, 'canceled', '2025-04-10 16:49:37', 'cod'),
(16, 471586, 12500.00, 'shipped', '2025-04-13 08:49:18', 'bank');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(10, 11, 889487, 1, 30000.00),
(11, 12, 691083, 5, 60000.00),
(13, 14, 453384, 9, 150000.00),
(14, 14, 691083, 1, 60000.00),
(16, 14, 312786, 1, 25000.00),
(17, 15, 312786, 1, 25000.00),
(18, 16, 632569, 1, 25000.00);

-- --------------------------------------------------------

--
-- Table structure for table `order_shipping`
--

CREATE TABLE `order_shipping` (
  `shipping_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_shipping`
--

INSERT INTO `order_shipping` (`shipping_id`, `order_id`, `full_name`, `phone`, `address`, `created_at`) VALUES
(1, 13, 'nguyễn Hoàng Đông', '0377296369', 'ádfggc', '2025-04-10 22:11:01'),
(2, 14, 'Khang', '0922', 'Cần Thơ', '2025-04-10 23:32:17'),
(3, 15, 'khang', '0377296369', 'ádfggc', '2025-04-10 23:49:37'),
(4, 16, 'Nguyen Dong', '0377296369', 'haugiang', '2025-04-13 15:49:18');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `external_link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `content`, `image`, `created_at`, `external_link`) VALUES
(1, 'Tăng cường miễn dịch tự nhiên nhờ trái cây tươi', 'Các loại quả mọng như việt quất, dâu tây, và nho giàu polyphenol giúp làm chậm quá trình lão hóa da. Bơ và xoài cung cấp vitamin E và chất béo lành mạnh giúp da căng bóng và mềm mịn. Bổ sung chúng vào chế độ ăn mỗi ngày để có làn da rạng rỡ tự nhiên.', 'uploads/1744348781_blog_skin_fruit.jpg', '2025-04-11 12:19:41', 'https://nhathuoclongchau.com.vn/bai-viet/7-loai-trai-cay-va-rau-tang-cuong-he-mien-dich-hieu-qua.html'),
(2, 'Trái cây nào tốt cho da?', 'Các loại quả mọng như việt quất, dâu tây, và nho giàu polyphenol giúp làm chậm quá trình lão hóa da. Bơ và xoài cung cấp vitamin E và chất béo lành mạnh giúp da căng bóng và mềm mịn. Bổ sung chúng vào chế độ ăn mỗi ngày để có làn da rạng rỡ tự nhiên.', 'uploads/1744348800_làm_đẹp_da.jpg', '2025-04-11 12:20:00', 'https://seoulspa.vn/cac-loai-trai-cay-tot-cho-da');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(6) NOT NULL,
  `name` varchar(255) NOT NULL,
  `selling_price` decimal(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(100) NOT NULL DEFAULT 'Chung',
  `discount` decimal(5,2) NOT NULL DEFAULT 0.00,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `visibility` enum('public','private') DEFAULT 'public',
  `stock_quantity` int(11) DEFAULT 0,
  `unit` varchar(10) NOT NULL DEFAULT 'kg',
  `status` enum('selling','not_selling') DEFAULT 'not_selling'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `selling_price`, `description`, `category`, `discount`, `image_url`, `created_at`, `visibility`, `stock_quantity`, `unit`, `status`) VALUES
(125147, 'Chuối Nam Mỹ', 15900.00, 'ngon', 'Tăng cường miễn dịch', 0.00, 'uploads/1742533468_1742531672_banana.png', '2025-03-21 04:42:09', 'public', 0, 'kg', 'selling'),
(312786, 'Thanh Long', 25000.00, 'Ruột đỏ, Ruột trắng tuỳ loại', 'Làm đẹp da', 0.00, 'uploads/1742633082_thanh long.jpg', '2025-03-22 08:44:42', 'public', 48, 'kg', 'not_selling'),
(443778, 'nhãn', NULL, NULL, 'Chung', 0.00, NULL, '2025-04-10 16:41:49', 'public', 100, 'kg', 'not_selling'),
(453384, 'sầu riêng', 150000.00, 'Đặc sản', 'Tốt cho tiêu hóa', 0.00, 'uploads/1742533396_sầu riêng.jpg', '2025-03-21 05:03:16', 'public', 41, 'trái', 'selling'),
(463458, 'cam chua', 25000.00, 'ngon lắm', 'Hỗ trợ giảm cân', 0.00, 'uploads/1742533525_cam.jpg', '2025-03-21 04:40:53', 'public', 0, 'kg', 'not_selling'),
(473170, 'Xoài cát', 15000.00, 'ngọt nhẹ', 'Hỗ trợ giảm cân', 0.00, 'uploads/1742633110_xoài.jpg', '2025-03-22 08:45:10', 'public', 61, 'kg', 'not_selling'),
(632569, 'đào', 25000.00, 'chua', 'Tăng cường miễn dịch', 0.00, NULL, '2025-04-09 18:31:11', 'public', 4, 'kg', 'selling'),
(691083, 'dâu tây', 60000.00, 'chua lè chua lét', 'Hỗ trợ giảm cân', 0.00, 'uploads/1744295280_z5218368096450_3fbb08fbadf1a79acd0663018f7699d8.jpg', '2025-04-10 14:27:07', 'public', 14, 'kg', 'selling'),
(812213, 'Mít', 50000.00, 'e', 'Tăng cường miễn dịch', 0.00, 'uploads/1744170580_sầu riêng.jpg', '2025-04-09 03:49:40', 'public', 94, 'kg', 'not_selling'),
(833347, 'Lựu đỏ', 50000.00, 'mọng nước', 'Tăng cường miễn dịch', 0.00, 'uploads/1742633005_lựu đỏ.jpg', '2025-03-22 08:43:25', 'public', 50, 'kg', 'not_selling'),
(836179, 'nho', 35000.00, 'nho ngọt', 'Tăng cường miễn dịch', 0.00, 'uploads/1742625624_nho.jpg', '2025-03-22 06:40:24', 'public', 0, 'kg', 'selling'),
(841025, 'nhãn', NULL, NULL, 'Chung', 0.00, NULL, '2025-04-10 16:41:09', 'public', 200, '0', 'not_selling'),
(889487, 'Hồng giòn', 30000.00, 'xuất xứ Việt Nam', 'Làm đẹp da', 0.00, 'uploads/1742632938_hồng giòn.jpg', '2025-03-22 08:42:18', 'public', 6, 'kg', 'selling'),
(989849, 'táo', 60000.00, 'hơi ngon', 'Hỗ trợ giảm cân', 0.00, 'uploads/1742533534_táo.jpg', '2025-03-21 04:38:54', 'public', 0, 'kg', 'selling');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_results`
--

CREATE TABLE `quiz_results` (
  `quiz_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `recommended_products` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `revenue`
--

CREATE TABLE `revenue` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `revenue`
--

INSERT INTO `revenue` (`id`, `order_id`, `total_amount`, `created_at`) VALUES
(4, 12, 150000.00, '2025-04-10 21:39:40'),
(5, 14, 1567500.00, '2025-04-10 23:42:30'),
(6, 13, 25000.00, '2025-04-10 23:43:05'),
(7, 16, 12500.00, '2025-04-13 15:49:44');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `user_id`, `product_id`, `rating`, `comment`, `created_at`) VALUES
(333, 471586, 125147, 5, 'ngon', '2025-03-23 05:45:53'),
(334, 471586, 453384, 5, 'ok đấy', '2025-04-10 16:32:40');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `store_name` varchar(255) NOT NULL DEFAULT 'Fruit For Health'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `store_name`) VALUES
(1, 'Fruit For Health');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(6) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) DEFAULT 'user',
  `age` int(11) DEFAULT NULL,
  `health_goal` varchar(50) DEFAULT NULL,
  `flavor_preference` varchar(50) DEFAULT NULL,
  `lifestyle` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `role`, `age`, `health_goal`, `flavor_preference`, `lifestyle`, `created_at`) VALUES
(1, 'admin', 'admin@gmail.com', '$2a$10$fY2OCfcCmTcSIY8l83IGIuoz8JaAAUtz8.xnrJLyRtl09TJZh1Nb.', 'admin', NULL, NULL, NULL, NULL, '2025-03-19 09:12:21'),
(471586, 'đông', 'ai@gmail.com', '$2y$10$NQ57yzjLY.y.lT/jbfK3r..Ax1oAZ2QVt0T2cVI6PbwqEz4ZDwYBG', 'user', NULL, NULL, NULL, NULL, '2025-03-21 04:59:45');

-- --------------------------------------------------------

--
-- Table structure for table `warehouse_stock`
--

CREATE TABLE `warehouse_stock` (
  `stock_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit` enum('kg','trái') NOT NULL,
  `purchase_price` decimal(10,2) NOT NULL,
  `supplier` varchar(255) NOT NULL,
  `imported_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `inventory_transactions`
--
ALTER TABLE `inventory_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `order_shipping`
--
ALTER TABLE `order_shipping`
  ADD PRIMARY KEY (`shipping_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD UNIQUE KEY `product_id` (`product_id`);

--
-- Indexes for table `quiz_results`
--
ALTER TABLE `quiz_results`
  ADD PRIMARY KEY (`quiz_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `revenue`
--
ALTER TABLE `revenue`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `warehouse_stock`
--
ALTER TABLE `warehouse_stock`
  ADD PRIMARY KEY (`stock_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `inventory_transactions`
--
ALTER TABLE `inventory_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `order_shipping`
--
ALTER TABLE `order_shipping`
  MODIFY `shipping_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `quiz_results`
--
ALTER TABLE `quiz_results`
  MODIFY `quiz_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `revenue`
--
ALTER TABLE `revenue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=335;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `warehouse_stock`
--
ALTER TABLE `warehouse_stock`
  MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `inventory_transactions`
--
ALTER TABLE `inventory_transactions`
  ADD CONSTRAINT `inventory_transactions_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `order_shipping`
--
ALTER TABLE `order_shipping`
  ADD CONSTRAINT `order_shipping_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Constraints for table `quiz_results`
--
ALTER TABLE `quiz_results`
  ADD CONSTRAINT `quiz_results_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `revenue`
--
ALTER TABLE `revenue`
  ADD CONSTRAINT `revenue_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `warehouse_stock`
--
ALTER TABLE `warehouse_stock`
  ADD CONSTRAINT `warehouse_stock_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
