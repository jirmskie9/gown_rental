-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 13, 2024 at 07:05 AM
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
-- Database: `gown_rental`
--

-- --------------------------------------------------------

--
-- Table structure for table `gowns`
--

CREATE TABLE `gowns` (
  `gown_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
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

INSERT INTO `gowns` (`gown_id`, `name`, `size`, `color`, `price`, `availability_status`, `main_image`, `img1`, `img2`, `img3`, `description`, `created_at`) VALUES
(2, 'Red Gown', 'Small', 'Red', '2100.00', 'available', '../uploads/s-l1200.jpg', '../uploads/253ee130c39774f564a3866ec38e0952.jfif', '../uploads/images.jfif', '../uploads/ph-11134207-7r98u-luh9st2keimy06.jfif', 'red is an expression of grief and loss. The work was presented outside the National Museum of the American Indian, where 35 red dresses hung from trees, served as representation of the thousands of indigenous women who have been murdered or are missing.', '2024-10-11 06:13:29'),
(4, 'Lava Gown', 'Medium', 'red', '2500.00', 'available', '../uploads/12fg1h32f1gf.jpg', '../uploads/catriona-grays-lava-inspired-gown-by-mak-tumang-at-the-miss-v0-ez98leusfd1d1.jpg', '../uploads/fhhghghg.jfif', '../uploads/hjhjhjjhj.jfif', 'The dress is also called Mayon Volcano gown as its design was inspired by Albay&#039;s Mayon Volcano, and later the dress became popularly known as the &quot;lava gown&quot; owing to its distinct fiery red to dark red color.', '2024-10-11 06:48:46'),
(5, 'Pink Gown', 'Medium', 'Pink', '1000.00', 'available', '../uploads/s-l1200 (1).jpg', '../uploads/asadad.jfif', '../uploads/fdfdfdfdfdf.jfif', '../uploads/images1232131.jfif', 'a bride&#039;s confidence, creativity, and willingness to embrace change. Pink dresses showcase a bride&#039;s vibrant personality and her desire to make a lasting impression on her wedding day.', '2024-10-11 06:54:39');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `date_time` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `content`, `date_time`) VALUES
(3, 5, 'Your <b>Lava</b> Gown confirmed by Admin', '2024-10-13 11:33:50');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `reservation_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `status` enum('pending','confirmed','completed','canceled') DEFAULT 'pending',
  `total_price` decimal(10,2) NOT NULL,
  `payment_status` enum('unpaid','paid','partial') DEFAULT 'unpaid',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`reservation_id`, `customer_id`, `gown_id`, `start_date`, `end_date`, `status`, `total_price`, `payment_status`, `created_at`) VALUES
(5, 5, 4, '2024-10-11', '2024-10-11', 'confirmed', '2575.00', 'unpaid', '2024-10-11 05:24:44');

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
(5, 'John Doe', 'johnd@gmail.com', '94a181ffe31cf8516e4fa4e3f5297bd7', '9514619679', 'Purok Uno', '2024-10-11 06:14:58', 'customer', '../pages/uploads/RobloxScreenShot20240513_072310004.png', '462348', 'Complete');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for table `gowns`
--
ALTER TABLE `gowns`
  MODIFY `gown_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

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
