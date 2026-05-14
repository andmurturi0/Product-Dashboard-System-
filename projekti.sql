-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 22, 2026 at 07:32 AM
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
-- Database: `projekti`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `monthly_price` decimal(10,2) NOT NULL,
  `registration_date` date NOT NULL,
  `product_features` text DEFAULT NULL,
  `is_available` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `category`, `monthly_price`, `registration_date`, `product_features`, `is_available`, `created_at`) VALUES
(2, 'Plani Standart', 'biznes_vogel', 49.00, '2026-03-19', 'Monitorim i faqes 24/7\r\nCertifikatë SSL dhe mbrojtje HTTPS\r\nSkanim javor për malware\r\nFirewall bazë për rrjetin', 1, '2026-03-19 04:15:35'),
(3, 'Plani Profesional', 'biznes_mesem', 149.00, '2026-03-20', 'Testim penetrimi çdo muaj\r\nMbrojtje e avancuar nga sulmet DDoS\r\nSiguria e email-eve dhe Phishing\r\nSuport teknik i dedikuar 24/7', 1, '2026-03-20 00:27:04'),
(4, 'Plani Enterprise', 'biznes_madh', 499.00, '2026-03-20', 'Monitorim i vazhdueshëm SOC\r\nAnalizë risku dhe pajtueshmëri ligjore\r\nTrajnim i stafit për sigurinë\r\nReagim i menjëhershëm ndaj incidenteve', 1, '2026-03-20 00:28:01');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`) VALUES
(2, 'And Murturi', 'andmurturi0@gmail.com', '$2y$10$5QXoed6yZQcXOEfUrOlyReakxxcyZPIPPznDrDlsVNFa3ao5Kwtvq'),
(3, 'Bardha', 'bardha@gmail.com', '$2y$10$EGu6B1cul/Raa5gmAyUpXOvfvP1UMPu5z7dEpwHuxQOjxAZcRECIG'),
(5, 'Aniku', 'aniku@gmail.com', '$2y$10$vqSfjgtLac2QBdOveW3.l.XpuL/GaII7EZY.UiGQb2pXszDkp4sdK'),
(7, 'Anuar Murturi', 'anuar@gmail.com', '$2y$10$wbT5RiuLwaIdZtSPTms.wehPH/B7j8P9iFYZc7JG0AjKxTlpM2nJy');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
