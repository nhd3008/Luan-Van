-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 23, 2025 at 03:40 PM
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

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `created_at`) VALUES
(98, 471586, 969729, 1, '2025-04-23 13:38:06');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `product_id` int(6) NOT NULL,
  `quantity` int(11) NOT NULL,
  `purchase_price` decimal(10,2) DEFAULT NULL,
  `unit_type` varchar(10) DEFAULT NULL,
  `supplier` varchar(255) DEFAULT NULL,
  `invoice_code` varchar(100) DEFAULT NULL,
  `import_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` datetime DEFAULT current_timestamp(),
  `imported_by` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`id`, `product_id`, `quantity`, `purchase_price`, `unit_type`, `supplier`, `invoice_code`, `import_date`, `created_at`, `imported_by`) VALUES
(39, 392786, 50, 20000.00, 'kg', 'Trái Cây Miền Tây', '123456789', '2025-04-20 17:00:00', '2025-04-22 09:20:36', 'admin@gmail.com'),
(40, 392786, 50, 20000.00, NULL, 'Trái Cây Miền Tây', '123456788', '2025-04-21 17:00:00', '2025-04-22 09:21:44', 'admin@gmail.com'),
(41, 634926, 50, 8000.00, 'trái', 'Trái Cây Miền Tây', '123456787', '2025-04-20 17:00:00', '2025-04-22 09:31:41', 'admin@gmail.com'),
(42, 484682, 50, 15000.00, 'kg', 'Trái Cây Miền Tây', '123456786', '2025-04-20 17:00:00', '2025-04-22 09:42:22', 'admin@gmail.com'),
(43, 225093, 50, 30000.00, 'kg', 'Trái Cây Nhập Khẩu', '123456785', '2025-04-20 17:00:00', '2025-04-22 09:43:11', 'admin@gmail.com'),
(44, 969729, 50, 35000.00, 'kg', 'Trái Cây Nhập Khẩu', '123456784', '2025-04-20 17:00:00', '2025-04-22 09:43:54', 'admin@gmail.com'),
(45, 379703, 50, 15000.00, 'trái', 'Trái Cây Miền Tây', '12456783', '2025-04-20 17:00:00', '2025-04-22 09:44:44', 'admin@gmail.com'),
(46, 158398, 50, 50000.00, 'kg', 'Trái Cây Nhập Khẩu', '123456782', '2025-04-20 17:00:00', '2025-04-22 09:45:18', 'admin@gmail.com'),
(47, 710262, 50, 25000.00, 'trái', 'Trái Cây Miền Tây', '123456781', '2025-04-20 17:00:00', '2025-04-22 09:47:09', 'admin@gmail.com'),
(48, 303122, 50, 30000.00, 'kg', 'Trái Cây Đà Lạt', '123456780', '2025-04-20 17:00:00', '2025-04-22 09:47:39', 'admin@gmail.com'),
(49, 765563, 50, 15000.00, 'trái', 'Trái Cây Miền Tây', '123456791', '2025-04-20 17:00:00', '2025-04-22 09:48:25', 'admin@gmail.com'),
(50, 907484, 50, 60000.00, 'kg', 'Trái Cây Nhập Khẩu', '123456792', '2025-04-20 17:00:00', '2025-04-22 09:49:15', 'admin@gmail.com'),
(51, 771187, 50, 70000.00, 'kg', 'Trái Cây Nhập Khẩu', '123456793', '2025-04-20 17:00:00', '2025-04-22 09:49:52', 'admin@gmail.com'),
(52, 277958, 50, 50000.00, 'kg', 'Trái Cây Miền Tây', '123456794', '2025-04-19 17:00:00', '2025-04-22 09:50:46', 'admin@gmail.com'),
(53, 259184, 50, 45000.00, 'kg', 'Trái Cây Nhập Khẩu', '123456795', '2025-04-20 17:00:00', '2025-04-22 09:51:30', 'admin@gmail.com'),
(54, 143176, 50, 15000.00, 'kg', 'Trái Cây Miền Tây', '123456796', '2025-04-20 17:00:00', '2025-04-22 09:54:07', 'admin@gmail.com'),
(55, 607327, 50, 70000.00, 'trái', 'Trái Cây Miền Tây', '123456797', '2025-04-20 17:00:00', '2025-04-22 09:56:07', 'admin@gmail.com'),
(56, 334743, 50, 350000.00, 'kg', 'Trái Cây Nhập Khẩu', '123456798', '2025-04-20 17:00:00', '2025-04-22 09:56:34', 'admin@gmail.com'),
(57, 802546, 50, 30000.00, 'kg', 'Trái Cây Nhập Khẩu', '123456799', '2025-04-20 17:00:00', '2025-04-22 10:27:02', 'admin@gmail.com'),
(58, 757785, 50, 150000.00, 'trái', 'Trái Cây Miền Tây', '987654321', '2025-04-15 17:00:00', '2025-04-23 20:14:58', 'admin@gmail.com'),
(59, 693114, 50, 35000.00, 'kg', 'Trái Cây Miền Tây', '987654322', '2025-04-17 17:00:00', '2025-04-23 20:15:30', 'admin@gmail.com'),
(60, 963392, 50, 100000.00, 'kg', 'Trái Cây Nhập Khẩu', '987654323', '2025-04-17 17:00:00', '2025-04-23 20:18:10', 'admin@gmail.com'),
(61, 311389, 50, 40000.00, 'trái', 'Trái Cây Miền Tây', '987654324', '2025-04-18 17:00:00', '2025-04-23 20:19:06', 'admin@gmail.com'),
(62, 557045, 50, 35000.00, 'kg', 'Trái Cây Miền Tây', '987654325', '2025-04-15 17:00:00', '2025-04-23 20:19:57', 'admin@gmail.com'),
(63, 285342, 50, 50000.00, 'kg', 'Trái Cây Nhập Khẩu', '987654326', '2025-04-14 17:00:00', '2025-04-23 20:21:09', 'admin@gmail.com'),
(64, 379577, 50, 150000.00, 'kg', 'Trái Cây Đà Lạt', '987654327', '2025-04-22 17:00:00', '2025-04-23 20:21:56', 'admin@gmail.com'),
(65, 823172, 50, 15000.00, 'kg', 'Trái Cây Đà Lạt', '987654328', '2025-04-23 17:00:00', '2025-04-23 20:23:04', 'admin@gmail.com');

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
  `payment_method` varchar(50) NOT NULL DEFAULT 'cod',
  `approved_by_email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `total_price`, `order_status`, `created_at`, `payment_method`, `approved_by_email`) VALUES
(65, 471586, 12500.00, 'shipped', '2025-04-22 02:35:49', 'cod', 'admin@gmail.com'),
(66, 471586, 37500.00, 'pending', '2025-04-22 02:37:48', 'cod', NULL),
(67, 667258, 45000.00, 'pending', '2025-04-22 17:04:40', 'cod', NULL),
(68, 471586, 80000.00, 'pending', '2025-04-23 12:45:20', 'vnpay', NULL),
(69, 471586, 35000.00, 'pending', '2025-04-23 12:51:51', 'vnpay', NULL),
(70, 471586, 10000.00, 'pending', '2025-04-23 12:57:40', 'vnpay', NULL);

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
(68, 65, 392786, 1, 25000.00),
(69, 66, 392786, 3, 25000.00),
(70, 67, 143176, 1, 20000.00),
(71, 67, 158398, 1, 70000.00),
(72, 68, 607327, 1, 80000.00),
(73, 69, 158398, 1, 70000.00),
(74, 70, 143176, 1, 20000.00);

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
(54, 66, 'nguyễn Hoàng Đông', '0377296369', 'a', '2025-04-22 09:37:48'),
(55, 67, 'Nguyễn Hoàng Đông', '0377296369', 'Phong Điền Cần Thơ', '2025-04-23 00:04:40'),
(56, 68, 'Nguyễn Hoàng Đông', '0377296369', 'Phong Điền Cần Thơ', '2025-04-23 19:45:20'),
(57, 69, 'Nguyễn Hoàng Đông', '0377296369', 'Quận 2 TP HCM', '2025-04-23 19:51:51'),
(58, 70, 'Nguyễn Hoàng Đông', '0377296369', 'Phường 3 Đà Lạt', '2025-04-23 19:57:40');

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
  `selling_price` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(100) NOT NULL DEFAULT 'Chung',
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `visibility` enum('public','private') DEFAULT 'public',
  `stock_quantity` float DEFAULT 0,
  `unit` varchar(10) NOT NULL DEFAULT 'kg',
  `status` enum('selling','not_selling') DEFAULT 'not_selling'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `selling_price`, `description`, `category`, `image_url`, `created_at`, `visibility`, `stock_quantity`, `unit`, `status`) VALUES
(143176, 'Ổi', 20000, 'Ổi có vỏ xanh hoặc vàng, thịt trắng hoặc đỏ, hạt nhiều. Đây là loại trái cây ngọt có thể ăn trực tiếp hoặc làm sinh tố.\r\nLợi ích sức khỏe: Ổi giàu vitamin C, giúp tăng cường miễn dịch và hỗ trợ tiêu hóa.', 'Tốt cho tiêu hóa', 'uploads/1745292382_ổi.jpg', '2025-04-22 02:54:07', 'public', 49, 'kg', 'selling'),
(158398, 'Nho', 70000, 'Nho là trái cây nhỏ, mọng nước với màu sắc đa dạng từ xanh, đỏ đến đen. Nho thường được ăn trực tiếp hoặc dùng để làm rượu vang.\r\nLợi ích sức khỏe: Nho chứa chất chống oxy hóa mạnh, giúp bảo vệ tim mạch và ngăn ngừa lão hóa.', 'Làm đẹp da', 'uploads/1745291804_nho.jpg', '2025-04-22 02:45:18', 'public', 49, 'kg', 'selling'),
(225093, 'Táo', 40000, 'Táo có vị ngọt hoặc chua, với vỏ đỏ, vàng hoặc xanh. Táo có độ giòn cao và là một món ăn nhẹ tuyệt vời, có thể ăn trực tiếp hoặc làm nước ép.\r\nLợi ích sức khỏe: Táo chứa nhiều chất xơ, hỗ trợ tiêu hóa và giúp làm sạch ruột.', 'Tốt cho tiêu hóa', 'uploads/1745291108_táo.jpg', '2025-04-22 02:43:11', 'public', 50, 'kg', 'selling'),
(259184, 'Lê', 55000, 'Lê có hình dáng tròn hoặc dài, vỏ mỏng và có vị ngọt, giòn. Lê thường được ăn tươi hoặc làm nước ép.\r\nLợi ích sức khỏe: Lê giúp cung cấp chất xơ và làm mát cơ thể, hỗ trợ tiêu hóa.', 'Tốt cho tiêu hóa', 'uploads/1745292499_lê.jpg', '2025-04-22 02:51:30', 'public', 50, 'kg', 'selling'),
(277958, 'vải', NULL, NULL, 'Chung', NULL, '2025-04-22 02:50:46', 'public', 50, 'kg', 'not_selling'),
(285342, 'Đào', 70000, 'Đào có vỏ mỏng màu vàng hoặc đỏ, thịt quả ngọt, mềm và có hạt. Đào thường được ăn trực tiếp hoặc làm món tráng miệng.\r\nLợi ích sức khỏe: Đào chứa nhiều vitamin A và C, giúp cải thiện sức khỏe da và mắt.', 'Làm đẹp da', 'uploads/1745415118_đào.jpg', '2025-04-23 13:21:09', 'public', 50, 'kg', 'selling'),
(303122, 'Bơ', NULL, NULL, 'Chung', NULL, '2025-04-22 02:47:39', 'public', 50, 'kg', 'not_selling'),
(311389, 'Bưởi', 50000, 'Bưởi có vỏ dày màu vàng hoặc hồng, thịt quả mọng nước, ngọt thanh và có vị hơi chua. Bưởi thường được ăn trực tiếp hoặc ép thành nước.\r\nLợi ích sức khỏe: Bưởi chứa nhiều vitamin C, giúp tăng cường hệ miễn dịch và hỗ trợ giảm cân.', 'Tăng cường miễn dịch', 'uploads/1745415210_bưởi.jpg', '2025-04-23 13:19:06', 'public', 50, 'trái', 'selling'),
(334743, 'Cherry', 450000, 'Cherry có màu đỏ tươi, vị ngọt và chua, thường được ăn tươi hoặc làm nước ép.\r\nLợi ích sức khỏe: Cherry giúp giảm viêm, hỗ trợ giấc ngủ và cung cấp nhiều chất chống oxy hóa.', 'Làm đẹp da', 'uploads/1745292256_cherry.jpg', '2025-04-22 02:56:34', 'public', 50, 'kg', 'selling'),
(379577, 'Dâu tây', 200000, 'Dâu tây là trái cây nhỏ, có hình dáng hình chóp, với màu đỏ tươi và bề mặt phủ lớp hạt nhỏ li ti. Dâu tây thường được ăn trực tiếp hoặc dùng để làm sinh tố, mứt, và bánh ngọt.\r\nLợi ích sức khỏe: Dâu tây chứa nhiều vitamin C và chất chống oxy hóa, giúp tăng cường hệ miễn dịch, bảo vệ da khỏi lão hóa và giảm nguy cơ mắc bệnh tim mạch. Dâu tây cũng hỗ trợ quá trình tiêu hóa và giúp làm đẹp da.', 'Làm đẹp da', 'uploads/1745415040_dâu tây.jpg', '2025-04-23 13:21:56', 'public', 50, 'kg', 'selling'),
(379703, 'Khóm ( Dứa )', NULL, NULL, 'Chung', NULL, '2025-04-22 02:44:44', 'public', 50, 'trái', 'not_selling'),
(392786, 'Xoài', 25000, 'Xoài là một trong những loại trái cây nhiệt đới phổ biến, với vị ngọt đậm đà và mùi thơm đặc trưng. Thịt xoài mềm mịn, màu vàng sáng, thường được dùng để làm sinh tố, tráng miệng hoặc ăn tươi.\r\nLợi ích sức khỏe: Xoài chứa nhiều vitamin C và A, giúp tăng cường miễn dịch, làm sáng da và tốt cho mắt.', 'Tăng cường miễn dịch', 'uploads/1745288622_xoài.jpg', '2025-04-22 02:20:36', 'public', 98.5, 'kg', 'selling'),
(484682, 'Chuối', 30000, 'Chuối là loại trái cây mềm, có vỏ vàng khi chín, rất dễ ăn và cung cấp năng lượng nhanh chóng. Chuối thường được ăn trực tiếp hoặc chế biến thành sinh tố.\r\nLợi ích sức khỏe: Chuối giàu kali, giúp duy trì huyết áp ổn định và cải thiện chức năng tim mạch.', 'Tăng cường miễn dịch', 'uploads/1745290714_1742531672_banana.png', '2025-04-22 02:42:22', 'public', 50, 'kg', 'selling'),
(557045, 'Nhãn', 45000, 'Nhãn có vỏ mỏng, màu nâu, thịt quả ngọt, mềm và chứa nhiều nước. Nhãn thường được ăn trực tiếp hoặc làm mứt.\r\nLợi ích sức khỏe: Nhãn cung cấp năng lượng, vitamin C và chất xơ, giúp tăng cường sức khỏe và tiêu hóa.', 'Tốt cho tiêu hóa', 'uploads/1745415171_nhãn.jpg', '2025-04-23 13:19:57', 'public', 50, 'kg', 'selling'),
(607327, 'Mít', 80000, 'Mít có vỏ gai, thịt có màu vàng tươi, ngọt và có mùi thơm đặc trưng. Mít được sử dụng trong nhiều món ăn và tráng miệng.\r\nLợi ích sức khỏe: Mít cung cấp vitamin C, kali và chất xơ, giúp hỗ trợ tiêu hóa và cải thiện sức khỏe tim mạch.', 'Tốt cho tiêu hóa', 'uploads/1745292309_mít.jpg', '2025-04-22 02:56:07', 'public', 49, 'trái', 'selling'),
(634926, 'Dưa Hấu (1-1,5kg)', 15000, 'Dưa hấu có vỏ xanh và thịt đỏ mọng nước. Đây là loại trái cây giải khát rất phổ biến trong mùa hè.\r\nLợi ích sức khỏe: Dưa hấu giúp cấp nước cho cơ thể, chứa lycopene có tác dụng chống oxy hóa và hỗ trợ tim mạch.', 'Làm đẹp da', 'uploads/1745289226_dưa hấu.jpg', '2025-04-22 02:31:41', 'public', 50, 'trái', 'selling'),
(693114, 'Chôm chôm', 45000, 'Chôm chôm có vỏ gai đỏ, thịt quả trắng ngọt. Chôm chôm thường được ăn trực tiếp hoặc dùng trong các món tráng miệng.\r\nLợi ích sức khỏe: Chôm chôm cung cấp nhiều vitamin C, giúp duy trì sức khỏe hệ miễn dịch và làm đẹp da.', 'Tăng cường miễn dịch', 'uploads/1745415280_chôm chôm.jpg', '2025-04-23 13:15:30', 'public', 50, 'kg', 'selling'),
(710262, 'Đu đủ', 40000, 'Đu đủ có màu cam sáng, thịt mềm và ngọt. Nó là một loại trái cây nhiệt đới phổ biến, có thể ăn tươi hoặc chế biến thành sinh tố.\r\nLợi ích sức khỏe: Đu đủ giúp tiêu hóa tốt nhờ enzyme papain và chứa nhiều vitamin C.', 'Tốt cho tiêu hóa', 'uploads/1745291602_đu đủ.jpg', '2025-04-22 02:47:09', 'public', 50, 'trái', 'selling'),
(757785, 'Sầu riêng', 180000, 'Sầu riêng có vỏ gai, thịt quả mềm, ngọt và có mùi đặc trưng. Sầu riêng thường được ăn trực tiếp hoặc dùng để làm món tráng miệng.\r\nLợi ích sức khỏe: Sầu riêng cung cấp năng lượng cao và chứa vitamin C, giúp cải thiện sức khỏe tim mạch và bảo vệ da.', 'Tăng cường miễn dịch', 'uploads/1745415421_sầu riêng.jpg', '2025-04-23 13:14:58', 'public', 50, 'trái', 'selling'),
(765563, 'Thanh Long', 20000, 'Thanh long có vỏ ngoài màu hồng hoặc vàng, thịt trắng hoặc đỏ, với những hạt đen nhỏ li ti. Thanh long có vị ngọt nhẹ và giòn.\r\nLợi ích sức khỏe: Thanh long giúp tăng cường hệ miễn dịch, tốt cho tiêu hóa và chứa nhiều chất xơ.', 'Tăng cường miễn dịch', 'uploads/1745291705_thanh long.jpg', '2025-04-22 02:48:25', 'public', 50, 'trái', 'selling'),
(771187, 'Kiwi', 90000, 'Kiwi có vỏ nâu, khi lột ra, thịt kiwi có màu xanh sáng với các hạt đen. Kiwi có vị chua ngọt và rất bổ dưỡng.\r\nLợi ích sức khỏe: Kiwi giúp tăng cường miễn dịch, chứa nhiều vitamin C và chất xơ.', 'Tăng cường miễn dịch', 'uploads/1745292542_kiwi.jpg', '2025-04-22 02:49:52', 'public', 50, 'kg', 'selling'),
(802546, 'Lựu', 45000, 'Lựu có vỏ đỏ, khi bóc ra chứa những hạt mọng nước, có vị chua ngọt. Lựu là một món ăn nhẹ tuyệt vời hoặc làm nước ép.\r\nLợi ích sức khỏe: Lựu chứa nhiều chất chống oxy hóa, tốt cho tim mạch và giúp làm đẹp da.', 'Làm đẹp da', 'uploads/1745292454_lựu đỏ.jpg', '2025-04-22 03:27:02', 'public', 50, 'kg', 'selling'),
(823172, 'Cà Chua', 20000, 'Cà chua có vỏ mỏng, màu đỏ hoặc vàng, thịt quả mềm và mọng nước. Cà chua thường được ăn sống hoặc dùng trong các món xào, salad.\r\nLợi ích sức khỏe: Cà chua giàu vitamin C và lycopene, giúp bảo vệ da khỏi tác hại của ánh nắng mặt trời và ngăn ngừa bệnh tim mạch.', 'Làm đẹp da', 'uploads/1745414935_cà chua.jpg', '2025-04-23 13:23:04', 'public', 50, 'kg', 'selling'),
(907484, 'Măng Cụt', 75000, 'Măng cụt có vỏ màu tím đậm và thịt bên trong màu trắng, ngọt và thơm. Đây là một trong những loại trái cây quý, được yêu thích trong các món tráng miệng\r\nMăng cụt chứa nhiều vitamin C và chất chống oxy hóa, hỗ trợ hệ miễn dịch và làm đẹp da.', 'Làm đẹp da', 'uploads/1745292589_măng cụt.jpg', '2025-04-22 02:49:15', 'public', 50, 'kg', 'selling'),
(963392, 'Mâm xôi', 130000, 'Mâm xôi có quả nhỏ, mọng nước với màu đỏ hoặc đen. Mâm xôi thường được ăn trực tiếp hoặc làm mứt.\r\nLợi ích sức khỏe: Mâm xôi là nguồn cung cấp chất chống oxy hóa và vitamin C, giúp tăng cường hệ miễn dịch và chống lão hóa.', 'Tăng cường miễn dịch', 'uploads/1745415362_mâm xôi.jpg', '2025-04-23 13:18:10', 'public', 50, 'kg', 'selling'),
(969729, 'Cam', 45000, 'Cam là trái cây có vị chua ngọt, thường có vỏ cam sáng và chứa nhiều múi nhỏ. Cam là nguồn cung cấp vitamin C dồi dào.\r\nLợi ích sức khỏe: Vitamin C trong cam giúp tăng cường miễn dịch và cải thiện sức khỏe da.', 'Tăng cường miễn dịch', 'uploads/1745291861_cam.jpg', '2025-04-22 02:43:54', 'public', 50, 'kg', 'selling');

-- --------------------------------------------------------

--
-- Table structure for table `revenue`
--

CREATE TABLE `revenue` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `customer_address` text DEFAULT NULL,
  `product_names` text DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `revenue`
--

INSERT INTO `revenue` (`id`, `order_id`, `total_amount`, `created_at`, `customer_name`, `customer_phone`, `customer_address`, `product_names`, `payment_method`) VALUES
(13, 65, 12500.00, '2025-04-22 10:40:39', 'Nguyễn Hoàng Đông', '0377296369', 'Cần Thơ', 'Xoài', 'cod');

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

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `supplier_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`supplier_id`, `name`, `phone`, `address`, `created_at`, `status`) VALUES
(5, 'Trái Cây Miền Tây', '0377296369', 'Phong Điền Cần Thơ', '2024-04-20 17:00:00', 'active'),
(6, 'Trái Cây Đà Lạt', '0377296368', 'Phường 3 Đà Lạt', '2023-03-24 17:00:00', 'active'),
(7, 'Trái Cây Nhập Khẩu', '0377296367', 'Quận 2 TP HCM', '2025-02-28 17:00:00', 'active');

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `full_name` varchar(255) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `role`, `age`, `created_at`, `full_name`, `phone_number`, `address`) VALUES
(1, 'admin', 'admin@gmail.com', '$2a$10$fY2OCfcCmTcSIY8l83IGIuoz8JaAAUtz8.xnrJLyRtl09TJZh1Nb.', 'admin', NULL, '2025-03-19 09:12:21', 'admin', NULL, NULL),
(471586, 'đông', 'ai@gmail.com', '$2y$10$NQ57yzjLY.y.lT/jbfK3r..Ax1oAZ2QVt0T2cVI6PbwqEz4ZDwYBG', 'user', 18, '2025-03-21 04:59:45', 'Nguyễn Hoàng Đông', '0377296369', 'Cần Thơ'),
(667258, 'Khang', 'khang@gmail.com', '$2y$10$bT/w52HeDQLPlvzupeN.feF9KUz1EU7AJRnwoDMOQFaSOXxVpDjj.', 'user', NULL, '2025-04-22 06:42:59', 'Huỳnh Hữu Khang', NULL, NULL),
(760047, 'đông1', 'dong1@gmail.com', '$2y$10$rGQxHuYrXxLEYAXVetpVKejmPn4SvCA/XD.nTDgshCV4B3ehutv72', 'manager', 23, '2025-04-20 14:40:34', NULL, NULL, NULL);

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
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `order_shipping`
--
ALTER TABLE `order_shipping`
  MODIFY `shipping_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `revenue`
--
ALTER TABLE `revenue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=335;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
