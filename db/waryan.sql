-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 08, 2024 at 10:30 AM
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
-- Database: `db_testing_waryan`
--

DELIMITER $$
--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `uuid_v4_baru` () RETURNS CHAR(36) CHARSET utf8mb4 COLLATE utf8mb4_general_ci  BEGIN
    -- Buat 16 byte acak
    SET @hex = CONCAT(
        HEX(FLOOR(RAND() * 256)),
        HEX(FLOOR(RAND() * 256)),
        HEX(FLOOR(RAND() * 256)),
        HEX(FLOOR(RAND() * 256)),
        HEX(FLOOR(RAND() * 256)),
        HEX(FLOOR(RAND() * 256)),
        HEX(FLOOR(RAND() * 256)),
        HEX(FLOOR(RAND() * 256)),
        HEX(FLOOR(RAND() * 256)),
        HEX(FLOOR(RAND() * 256)),
        HEX(FLOOR(RAND() * 256)),
        HEX(FLOOR(RAND() * 256)),
        HEX(FLOOR(RAND() * 256)),
        HEX(FLOOR(RAND() * 256)),
        HEX(FLOOR(RAND() * 256)),
        HEX(FLOOR(RAND() * 256))
    );
    
    -- Setel versi ke 4 (acak)
    SET @hex = CONCAT(
        SUBSTR(@hex, 1, 8),
        '-',
        SUBSTR(@hex, 9, 4),
        '-4',
        SUBSTR(@hex, 14, 3),
        '-',
        HEX(FLOOR(ASCII(SUBSTR(@hex, 17, 1)) / 64) + 8),
        SUBSTR(@hex, 18, 3),
        '-',
        SUBSTR(@hex, 21)
    );
    
    RETURN LOWER(@hex);
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` char(36) NOT NULL,
  `user_id` char(36) DEFAULT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','processing','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_price`, `status`, `created_at`, `updated_at`) VALUES
('o1', 'u1', 999.99, 'completed', '2024-09-07 00:06:57', '2024-09-07 00:06:57'),
('o2', 'u2', 1649.98, 'completed', '2024-09-07 00:06:57', '2024-09-07 21:51:02'),
('o3', 'u1', 149.99, 'pending', '2024-09-07 00:06:57', '2024-09-07 00:06:57'),
('order-1', 'u1', 2400.00, 'completed', '2024-09-04 05:00:00', '2024-09-04 05:00:00'),
('order-2', 'u2', 1600.00, 'pending', '2024-09-05 05:00:00', '2024-09-05 05:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` char(36) NOT NULL,
  `order_id` char(36) DEFAULT NULL,
  `product_id` char(36) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
('item-1', 'order-1', 'prod-1', 2, 1200.00),
('item-2', 'order-2', 'prod-2', 2, 800.00),
('oi2', 'o2', 'p2', 1, 1499.99),
('oi3', 'o2', 'p3', 1, 149.99),
('oi4', 'o3', 'p3', 1, 149.99);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` char(36) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `sold_count` int(11) DEFAULT 0,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `slug`, `description`, `price`, `stock`, `sold_count`, `image_url`, `created_at`) VALUES
('64440758-950f-4dc6-9876-25584955e17', 'Sony WH-1000XM5 Wireless Industry Leading Active Noise Cancelling Headphones', 'sony-wh-1000xm5-wireless-industry-leading-active-noise-cancelling-headphones', 'Industry Leading noise cancellation-two processors control 8 microphones for unprecedented noise cancellation. With Auto NC Optimizer, noise cancelling is automatically optimized based on your wearing conditions and environment.', 800000.00, 0, 0, 'https://storage.googleapis.com/fir-auth-1c3bc.appspot.com/1692941008275-headphone3.jpg', '2024-09-07 11:14:15'),
('8f5e26a5-c8fc-41e3-9d7e-f9417b74f66', 'Microsoft Xbox X/S Wireless Controller Robot', 'microsoft-xbox-x-s-wireless-controller-robot', 'Experience the modernized design of the Xbox wireless controller in robot white, featuring sculpted surfaces and refined Geometry for enhanced comfort and effortless control during gameplay', 900000.00, 4, 0, 'https://storage.googleapis.com/fir-auth-1c3bc.appspot.com/1692255251854-xbox.jpg', '2024-09-07 10:51:44'),
('fe86a4a4-477a-4e57-8239-9951fdbec9', 'Logitech G733 Wireless Gaming Headset', 'logitech-g733-wireless-gaming-headset', 'Total freedom with up to 20 m wireless range and LIGHTSPEED wireless audio transmission. Keep playing for up to 29 hours of battery life. 1 Play in stereo on PlayStation', 1400000.00, 9, 0, 'https://storage.googleapis.com/fir-auth-1c3bc.appspot.com/1692257709689-logitech heaphone.jpg', '2024-09-07 10:58:43'),
('p2', 'Acer 50 inches V Series 4K Ultra HD', 'acer-50-inches-v-series-4k-ultra-hd', 'Resolution: QLED, 4K Ultra HD (3840x2160) | Refresh Rate: 60 Hertz | 178 Degree wide viewing angle', 1599.99, 30, 5, 'https://storage.googleapis.com/fir-auth-1c3bc.appspot.com/1694155440745-619--Jabh2L._SL1048_.jpg', '2024-09-07 00:06:57'),
('p3', 'Wireless Earbuds', 'wireless-earbuds', 'Premium sound quality wireless earbuds', 1999.99, 100, 20, 'https://storage.googleapis.com/fir-auth-1c3bc.appspot.com/1692947383286-714WUJlhbLS._SL1500_.jpg', '2024-09-07 00:06:57'),
('prod-1', 'Sleek Wireless Headphone & Inked Earbud Set', 'sleek-wireless-headphone-and-inked-earbud-set', 'Experience the fusion of style and sound with this sophisticated audio set featuring a pair of sleek, white wireless headphones offering crystal-clear sound quality and over-ear comfort. The set also includes a set of durable earbuds, perfect for an on-the-go lifestyle. Elevate your music enjoyment with this versatile duo, designed to cater to all your listening needs.', 300000.00, 10, 3, 'https://i.imgur.com/yVeIeDa.jpeg', '2024-09-01 05:00:00'),
('prod-2', 'Sleek White & Orange Wireless Gaming Controller', 'sleek-white-and-orange-wireless-gaming-controller', 'Elevate your gaming experience with this state-of-the-art wireless controller, featuring a crisp white base with vibrant orange accents. Designed for precision play, the ergonomic shape and responsive buttons provide maximum comfort and control for endless hours of gameplay. Compatible with multiple gaming platforms, this controller is a must-have for any serious gamer looking to enhance their setup.', 800.00, 20, 5, 'https://i.imgur.com/ZANVnHE.jpeg', '2024-09-02 05:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` char(36) NOT NULL,
  `user_id` char(36) DEFAULT NULL,
  `product_id` char(36) DEFAULT NULL,
  `order_id` char(36) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `product_id`, `order_id`, `rating`, `comment`, `created_at`) VALUES
('r2', 'u2', 'p2', 'o2', 4, 'Good laptop, but battery life could be better', '2024-09-07 00:06:57'),
('r3', 'u1', 'p3', 'o3', 5, 'Amazing sound quality!', '2024-09-07 00:06:57'),
('review-1', 'u1', 'prod-1', 'order-1', 5, 'Great Headphone!', '2024-09-05 05:00:00'),
('review-2', 'u2', 'prod-2', 'order-2', 4, 'Good controller', '2024-09-06 05:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `sales_statistics`
--

CREATE TABLE `sales_statistics` (
  `id` char(36) NOT NULL,
  `total_sales` decimal(15,2) DEFAULT 0.00,
  `total_products_sold` int(11) DEFAULT 0,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales_statistics`
--

INSERT INTO `sales_statistics` (`id`, `total_sales`, `total_products_sold`, `last_updated`) VALUES
('ss1', 2799.96, 35, '2024-09-07 00:06:57'),
('stat-1', 4000.00, 5, '2024-09-06 05:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` char(36) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `is_admin` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `is_admin`, `created_at`) VALUES
('51ce3de6-6db6-11ef-ae9e-d45d6469f111', 'masayama', '$2y$10$B3jrgiFIshIv71s4qb8wPul6oc77SuFVilubaMcY7cZZRGfmHa9Jy', 'masayama@gmail.com', 1, '2024-09-08 07:45:31'),
('98cbceb9-6cf1-11ef-ae9e-d45d6469f111', 'yanyan', '$2y$10$cUX7uCZwUECEUfHP7jFSNuV.dvOqI.cQo8FWhAblModyQZGdwpCfG', 'yanyan@yahoo.com', 1, '2024-09-07 08:17:17'),
('bc8fdd72-6db5-11ef-ae9e-d45d6469f111', 'adminyan', '$2y$10$jzmqCoFUrGs791SvSnP/2e7o.gYc1nt/U18fFD.hYqwbwodUjKPmy', 'adminyan@gmail.com', 1, '2024-09-08 07:41:20'),
('fab713d6-6cf7-11ef-ae9e-d45d6469f111', 'putri', '$2y$10$XE5o9UA1Tqh2RTgK0QATreACjSvx65jHlKNDzU5AjW.AtpGWt.9OG', 'putri@yahoo.com', 0, '2024-09-07 09:02:59'),
('u1', 'john_doe', 'hashed_password_1', 'john@example.com', 0, '2024-09-07 00:06:57'),
('u2', 'jane_smith', 'hashed_password_2', 'jane@example.com', 0, '2024-09-07 00:06:57'),
('u3', 'admin_user', 'hashed_password_3', 'admin@example.com', 1, '2024-09-07 00:06:57');

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `id` char(36) NOT NULL,
  `user_id` char(36) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `birth_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_profiles`
--

INSERT INTO `user_profiles` (`id`, `user_id`, `full_name`, `address`, `phone_number`, `birth_date`) VALUES
('up1', 'u1', 'John Doe', '123 Main St, Anytown, USA', '123-456-7890', '1990-01-15'),
('up2', 'u2', 'Jane Smith', '456 Elm St, Othertown, USA', '987-654-3210', '1988-07-22'),
('up3', 'u3', 'Admin User', '789 Oak St, Adminville, USA', '555-555-5555', '1985-03-30');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` char(36) NOT NULL,
  `user_id` char(36) DEFAULT NULL,
  `product_id` char(36) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `product_id`, `created_at`) VALUES
('w1', 'u1', 'p2', '2024-09-07 00:06:57'),
('w3', 'u1', 'p3', '2024-09-07 00:06:57');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `sales_statistics`
--
ALTER TABLE `sales_statistics`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_reviews_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD CONSTRAINT `user_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `fk_wishlist_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
