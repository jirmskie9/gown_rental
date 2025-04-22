-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 22, 2025 at 03:01 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gings`
--

-- --------------------------------------------------------

--
-- Table structure for table `agreements`
--

CREATE TABLE `agreements` (
  `id` int(11) NOT NULL,
  `reservation_id` int(11) NOT NULL,
  `signature` varchar(255) NOT NULL,
  `date_signed` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `agreements`
--

INSERT INTO `agreements` (`id`, `reservation_id`, `signature`, `date_signed`) VALUES
(1, 1, 'signature_67f48d3c25035.png', '2025-04-08 04:43:08'),
(2, 2, 'signature_67fe41ca70b83.png', '2025-04-15 13:23:54'),
(3, 4, 'signature_68079244c3fa5.png', '2025-04-22 14:57:40');

-- --------------------------------------------------------

--
-- Table structure for table `bookmarks`
--

CREATE TABLE `bookmarks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `date_time` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookmarks`
--

INSERT INTO `bookmarks` (`id`, `user_id`, `content`, `date_time`) VALUES
(5, 5, 'Pink Gown', '2024-10-21 19:21:07'),
(6, 7, 'Red Gown', '2024-10-28 12:52:26'),
(7, 7, 'Lava Gown', '2024-10-29 05:23:42'),
(8, 5, 'Red Gown', '2024-10-29 15:16:04'),
(9, 7, 'Pink Gown', '2024-11-10 01:51:42'),
(10, 5, 'Pink Gown', '2024-11-17 07:43:53'),
(11, 7, 'Lava Gown', '2024-11-30 11:57:40'),
(12, 7, 'Pink Gown', '2024-11-30 12:16:23'),
(13, 7, 'Red Gown', '2025-03-23 16:34:47');

-- --------------------------------------------------------

--
-- Table structure for table `gowns`
--

CREATE TABLE `gowns` (
  `gown_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` varchar(60) NOT NULL DEFAULT 'wedding',
  `size` varchar(10) NOT NULL,
  `color` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `availability_status` enum('available','rented','maintenance') DEFAULT 'available',
  `main_image` varchar(255) DEFAULT NULL,
  `img1` varchar(255) NOT NULL,
  `img2` varchar(255) NOT NULL,
  `img3` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gowns`
--

INSERT INTO `gowns` (`gown_id`, `name`, `category`, `size`, `color`, `price`, `availability_status`, `main_image`, `img1`, `img2`, `img3`, `description`, `created_at`) VALUES
(2, 'Red Gown', 'wedding', 'Medium', 'Red', '2100.00', 'available', '../uploads/s-l1200.jpg', '../uploads/253ee130c39774f564a3866ec38e0952.jfif', '../uploads/images.jfif', '../uploads/ph-11134207-7r98u-luh9st2keimy06.jfif', 'red is an expression of grief and loss. The work was presented outside the National Museum of the American Indian, where 35 red dresses hung from trees, served as representation of the thousands of indigenous women who have been murdered or are missing.', '2024-10-11 06:13:29'),
(4, 'Lava Gown', 'wedding', 'Medium', 'red', '2500.00', 'available', '../uploads/12fg1h32f1gf.jpg', '../uploads/catriona-grays-lava-inspired-gown-by-mak-tumang-at-the-miss-v0-ez98leusfd1d1.jpg', '../uploads/fhhghghg.jfif', '../uploads/hjhjhjjhj.jfif', 'The dress is also called Mayon Volcano gown as its design was inspired by Albay&#039;s Mayon Volcano, and later the dress became popularly known as the &quot;lava gown&quot; owing to its distinct fiery red to dark red color.', '2024-10-11 06:48:46'),
(5, 'Pink Gown', 'wedding', 'Medium', 'Pink', '1000.00', 'available', '../uploads/s-l1200 (1).jpg', '../uploads/asadad.jfif', '../uploads/fdfdfdfdfdf.jfif', '../uploads/images1232131.jfif', 'a bride&#039;s confidence, creativity, and willingness to embrace change. Pink dresses showcase a bride&#039;s vibrant personality and her desire to make a lasting impression on her wedding day.', '2024-10-11 06:54:39'),
(6, 'Bridal Gown', 'wedding', 'Medium', 'White', '12500.00', 'available', '../uploads/download.jfif', '../uploads/download.jfif', '../uploads/download.jfif', '../uploads/download.jfif', 'Wedding dress New slim simple princess starry sky Hepburn Style Wedding Gown ', '2024-11-18 01:52:08'),
(7, 'Bridesmaid Dresses Package Deal', 'wedding', 'Medium', 'sky blue', '10400.00', 'available', '../uploads/bridemaid.jfif', '../uploads/Off-the-Shoulder-Mismatched-Bridesmaid-Dresses-Blue-Fitted-Bridesmaid-Dress-PB10049_8369f9ca-8eab-4652-a849-77e4a2dc4277_1024x1024.webp', '../uploads/Off-the-Shoulder-Mismatched-Bridesmaid-Dresses-Blue-Fitted-Bridesmaid-Dress-PB10049_8369f9ca-8eab-4652-a849-77e4a2dc4277_1024x1024.webp', '../uploads/bridemaid.jfif', 'Women Elegant Bridesmaid Dress Gown Wedding PartyDinner Evening Maxi Dresses【135cm】', '2024-11-18 01:57:38'),
(8, 'Barong', 'wedding', 'Medium', 'White', '5650.00', 'available', '../uploads/Formal-barong-460x410.jpg', '../uploads/Formal-barong-460x410.jpg', '../uploads/Formal-barong-460x410.jpg', '../uploads/Formal-barong-460x410.jpg', 'Onésimus Suits &amp; Barongs', '2024-11-18 01:59:56'),
(9, 'Black Gown', 'wedding', 'Small', 'Black', '1500.00', 'available', '../uploads/black.jpg', '../uploads/black1.jpg', '../uploads/black 3.jpg', '../uploads/black 2.jpg', 'Black prom formal dress', '2024-12-01 11:11:13'),
(10, 'Fairy Gown', 'wedding', 'Small', 'Sky Blue', '8600.00', 'available', '../uploads/fairy.jpg', '../uploads/fairy.jpg', '../uploads/fairy.jpg', '../uploads/fairy.jpg', 'Fairy Ball Gown Off The Shoulder Flare Sleeve Lavender Lace Corset Prom ', '2024-12-01 11:15:14'),
(11, 'Royal Blue Satin lace Beaded Women Prom Evening Dress Engagement Formal', 'wedding', 'Large', 'Royal blue', '13000.00', 'available', '../uploads/Royal Blue Satin lace Beaded Women Prom Evening Dress Engagement Formal.jpg', '../uploads/blue.jpg', '../uploads/blue.jpg', '../uploads/blue.jpg', 'Royal Blue Satin lace Beaded Women Prom Evening Dress Engagement Formal', '2024-12-01 11:19:59'),
(12, 'Royal Blue', 'prom', '', 'Blue', '1000.00', 'available', '../uploads/D5823_ROY_003-scaled.webp', '../uploads/AlbinaDylaRoyalJewelledhirefront.webp', '../uploads/RoyalBlueProductComp1000x1440_1200x.webp', '../uploads/AlbinaDylaRoyalJewelledhirefront.webp', 'Test', '2025-04-04 10:30:44');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `date_time` varchar(40) NOT NULL,
  `type` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `content`, `date_time`, `type`) VALUES
(1, 7, 'Jirmy Nacario wants to rent Red Gown', '2025-04-08 10:41:36', 'admin'),
(2, 7, 'Your <b>Red Gown</b> confirmed by Admin', '2025-04-08 10:41:55', 'customer'),
(3, 7, 'Your <b>Red Gown</b> confirmed by Admin', '2025-04-08 10:42:50', 'customer'),
(4, 7, 'Your payment for the gown <b>Red Gown</b> has been confirmed.', '2025-04-08 10:43:25', 'customer'),
(5, 7, 'The gown has been successfully returned.', '2025-04-15 19:20:36', '0'),
(6, 7, 'Jirmy Nacario wants to rent Red Gown', '2025-04-15 19:22:03', 'admin'),
(7, 7, 'Your <b>Red Gown</b> confirmed by Admin', '2025-04-15 19:23:25', 'customer'),
(8, 7, 'Your payment for the gown <b>Red Gown</b> has been confirmed.', '2025-04-15 19:25:13', 'customer'),
(9, 7, 'The gown has been successfully returned.', '2025-04-15 19:32:05', '0'),
(10, 7, 'Jirmy Nacario wants to rent Red Gown', '2025-04-15 19:32:16', 'admin'),
(11, 7, 'Jirmy Nacario wants to rent Pink Gown', '2025-04-22 20:56:57', 'admin'),
(12, 7, 'Your <b>Pink Gown</b> confirmed by Admin', '2025-04-22 20:57:24', 'customer'),
(13, 7, 'Your payment for the gown <b>Pink Gown</b> has been confirmed.', '2025-04-22 20:57:56', 'customer'),
(14, 7, 'The gown has been successfully returned.', '2025-04-22 20:58:12', '0');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `reservation_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `name` varchar(70) NOT NULL,
  `number` varchar(70) NOT NULL,
  `transaction_id` varchar(100) NOT NULL,
  `proof` varchar(200) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `reservation_id`, `amount`, `payment_method`, `name`, `number`, `transaction_id`, `proof`, `payment_date`) VALUES
(1, 1, '2563.00', 'Cash on Pick Up', '', '', '', '', '2025-04-08 02:43:15'),
(2, 2, '2563.00', 'Cash on Pick Up', '', '', '', '', '2025-04-15 11:24:01'),
(3, 4, '1430.00', 'Cash on Pick Up', '', '', '', '', '2025-04-22 12:57:45');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `gown_id` int(11) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('pending','confirmed','completed','canceled','tosig') DEFAULT 'pending',
  `total_price` decimal(10,2) NOT NULL,
  `payment_status` enum('unpaid','paid','partial') DEFAULT 'unpaid',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`reservation_id`, `customer_id`, `gown_id`, `start_date`, `end_date`, `status`, `total_price`, `payment_status`, `created_at`) VALUES
(1, 7, 2, '2025-04-08', '2025-04-09', 'completed', '2163.00', 'paid', '2025-04-07 20:41:36'),
(2, 7, 2, '2025-04-15', '2025-04-17', 'completed', '2163.00', 'paid', '2025-04-15 05:22:03'),
(3, 7, 2, '2025-04-15', '2025-04-17', 'pending', '2163.00', 'unpaid', '2025-04-15 05:32:16'),
(4, 7, 5, '2025-04-22', '2025-04-23', 'completed', '1030.00', 'paid', '2025-04-22 06:56:57');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_type` varchar(60) DEFAULT NULL,
  `profile` varchar(200) NOT NULL,
  `otp` varchar(100) NOT NULL,
  `status` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `password`, `phone_number`, `address`, `created_at`, `user_type`, `profile`, `otp`, `status`) VALUES
(4, 'Kk Abol', 'admin@gmail.com', '94a181ffe31cf8516e4fa4e3f5297bd7', '09123213', 'San Miguel', '2024-10-11 04:10:58', 'admin', '../pages/uploads/imagesgown.jfif', '828327', 'Complete'),
(5, 'John Doe', 'johnd@gmail.com', '94a181ffe31cf8516e4fa4e3f5297bd7', '9514619679', 'Purok Uno', '2024-10-11 06:14:58', 'customer', '../pages/uploads/RobloxScreenShot20240513_072310004.png', '462348', 'Complete'),
(6, 'Cristy Mae Balaba', 'cristymaebalaba@gmail.com', '94a181ffe31cf8516e4fa4e3f5297bd7', '0941234224', 'San Vicente Banga South Cotabato', '2024-10-18 12:53:16', 'customer', '../pages/uploads/RobloxScreenShot20240418_113447697.png', '668688', 'Complete'),
(7, 'Jirmy Nacario', 'jirmskie9@gmail.com', '94a181ffe31cf8516e4fa4e3f5297bd7', '09514619679', 'Prk Rañada Polomolok South Cotabato', '2024-10-28 12:49:56', 'customer', '../pages/uploads/1727790302941.jpg', '259295', 'Complete'),
(8, 'Kaye Calata Ambrosio', 'kayeambrosio967@gmail.com', '2d8ee3a2e5146f2071d443cfeec70621', '09669472212', 'Esperanza, Sultan Kudarat', '2024-10-29 05:27:14', 'customer', '../pages/uploads/inbound7234978930513312430.jpg', '228703', 'Pending'),
(9, 'Mac Vincent', 'jirmy09@gmail.com', '$2y$10$DdH4nrCUbFRzBOc7eUqYkesNmLp5zMImkccDO8I7fGvZAeBzGj8O6', '09123111132321', 'Sean\'s SUPERMIX, Cannery Road, Citizen Village, Polomolok, South Cotabato, Soccsksargen, 9500, Philippines', '2024-11-10 02:03:37', 'customer', '../pages/uploads/RobloxScreenShot20240430_152724017.png', '799111', 'Complete'),
(10, 'Ryan Bayore', 'bayoreryan@gmail.com', '$2y$10$S0wfaybQ8X0dHLcuwIuHBuGKznsx1Hct2BKH3yorDh36aSqieDli6', '09382341891', 'Poblacion, Tampakan, South Cotabato', '2024-11-18 01:52:03', 'customer', '../pages/uploads/me.png', '290885', 'Complete'),
(11, 'Pauline ', 'paulinelasprilla98@gmail.com', '$2y$10$4W4d6.qTUIMhDJATQARK.OxcAPGID55n4mC13dXwGg4jrDVmUX/di', '09658144294', 'Norala South Cotabato ', '2024-12-01 09:05:50', 'customer', '../pages/uploads/IMG_20241125_225256.jpg', '274129', 'Complete'),
(12, 'John Noble', 'jundellnoble2@gmail.com', '$2y$10$yQbevrpwG6PHF2xqcaIPqOb6YX0nX4fQf7bH4Ggw96gUadFN8b3Gi', '0912314222', 'Prk Dignadice, Polomolok South Cotabato', '2024-12-02 04:49:44', 'customer', '../pages/uploads/POLWD Logo.png', '322414', 'Complete'),
(13, 'Jhonrid Morata', 'agustine@gmail.com', '$2y$10$Koic/ZsCpSS1.xy2aDDgdO2qxsqgYFRIEZ.2U20sTzALm37XQ0f4K', '09103386986', 'Tupi', '2025-03-14 23:37:21', 'customer', '../pages/uploads/inbound9101296103722362546.jpg', '605692', 'Pending'),
(14, 'Oliver Tree', 'nacariozeyn@gmail.com', '$2y$10$desxOpebL246PISmvX9LoOUSaES2P79/M4eh4k0g6gap2a6bhn.dq', '094618751044', 'Citizen Village, Polomolok, South Cotabato, Soccsksargen, 9504, Philippines', '2025-03-21 13:20:37', 'customer', '../pages/uploads/21b0634d6db89320693f785600a7f5f6_0.jpeg', '464811', 'Complete'),
(15, 'Gwyneth Nicole Bangkas ', 'gwynezia23@gmail.com', '$2y$10$clyCsmRcjSA1.GcXWKZ7feHW83ah7QG9cIEGj/kwsgiuXwv9ziLoy', '09458220397', 'Cebuano, Tupi, South Cotabato ', '2025-03-21 13:47:48', 'customer', '../pages/uploads/inbound8337130594965850185.jpg', '554458', 'Pending');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `agreements`
--
ALTER TABLE `agreements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reservation_id` (`reservation_id`);

--
-- Indexes for table `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gowns`
--
ALTER TABLE `gowns`
  ADD PRIMARY KEY (`gown_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `reservation_id` (`reservation_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservation_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `gown_id` (`gown_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `agreements`
--
ALTER TABLE `agreements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `bookmarks`
--
ALTER TABLE `bookmarks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `gowns`
--
ALTER TABLE `gowns`
  MODIFY `gown_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `agreements`
--
ALTER TABLE `agreements`
  ADD CONSTRAINT `agreements_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`reservation_id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`reservation_id`) REFERENCES `reservations` (`reservation_id`);

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`gown_id`) REFERENCES `gowns` (`gown_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
