-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 27, 2025 at 05:00 AM
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
-- Database: `uniteasedb`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_accounts`
--

CREATE TABLE `admin_accounts` (
  `admin_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(100) NOT NULL,
  `reg_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_accounts`
--

INSERT INTO `admin_accounts` (`admin_id`, `full_name`, `username`, `password`, `email`, `phone_number`, `reg_date`) VALUES
(2, 'John Wilvin Mislang', 'wilvin1', '$2y$10$OwEaRCLb5w1sZ5OM6yz46.s9kyKk3ylYfplzWOP8GZQ7yWBcCmWsS', 'johnwilvin22@gmail.com', '09085013142', '2025-10-26 19:35:36');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `announcement_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `unit_id` int(11) NOT NULL,
  `time_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `request_id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `unit_id` int(11) NOT NULL,
  `time_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tenant_accounts`
--

CREATE TABLE `tenant_accounts` (
  `tenant_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(100) NOT NULL,
  `reg_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tenant_accounts`
--

INSERT INTO `tenant_accounts` (`tenant_id`, `full_name`, `username`, `password`, `email`, `phone_number`, `reg_date`) VALUES
(1, 'John Wilvin Mislang', 'wilvin1', '$2y$10$reIk4svbtO9xC2kbY7.fROC31HCCQ/QNsGJtb5Aoe0Imc3MrLNuJS', 'johnwilvin22@gmail.com', '09085013142', '2025-10-20 02:43:50'),
(2, 'John Doe', 'nigger1', '$2y$10$h8jzTxEfZI43dPETnqf6SuhrFVhJEpTcnssnP3G8Z1RZXnCpn7y7e', 'johnwilvin22@gmail.com', '09085013142', '2025-10-20 14:25:01'),
(3, 'Mark Cruz', 'nigger2', '$2y$10$wl5c.TtXY6DsipZNkeq2pO2QysckyftCLp13IkPS.P.oDkr7lNIlq', 'johnwilvin22@gmail.com', '09085013142', '2025-10-20 14:25:16'),
(4, 'Juan Dela Cruz', 'nigger3', '$2y$10$Ywf4ArbaRlSAqO0iyvz.Ku89XOXZyHdkKs0/4YcPwswzTB.Ve5vb.', 'johnwilvin22@gmail.com', '09085013142', '2025-10-20 14:25:33'),
(5, 'Chloe Santos', 'nigger4', '$2y$10$BzzhFAatTaATMYRMO09KTe/WA.0hrahSOMCGaaf3.BjrGsPqTSJCW', 'johnwilvin22@gmail.com', '09085013142', '2025-10-20 14:25:56'),
(6, 'John Batumbakal', 'nigger 5', '$2y$10$nwMYstIVXTbOmEtVTeK2Uu1SX9dBP2Xq21Y6JaGm3j1WCvHwRNkMy', 'johnwilvin22@gmail.com', '09085013142', '2025-10-20 14:41:56');

-- --------------------------------------------------------

--
-- Table structure for table `tenant_units`
--

CREATE TABLE `tenant_units` (
  `id` int(11) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `date_assigned` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tenant_units`
--

INSERT INTO `tenant_units` (`id`, `unit_id`, `tenant_id`, `date_assigned`) VALUES
(62, 115, 1, '2025-10-27 01:54:27');

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `unit_id` int(11) NOT NULL,
  `unit_name` varchar(100) NOT NULL,
  `status` enum('Available','Full') NOT NULL,
  `capacity` int(11) NOT NULL,
  `unit_floor` varchar(100) NOT NULL,
  `admin_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`unit_id`, `unit_name`, `status`, `capacity`, `unit_floor`, `admin_id`) VALUES
(115, '1', 'Full', 1, '1', 2),
(116, '2', 'Available', 2, '2', 2),
(117, '3', 'Available', 3, '3', 2),
(118, '4', 'Available', 4, '4', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_accounts`
--
ALTER TABLE `admin_accounts`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`announcement_id`),
  ADD KEY `admin_id` (`admin_id`),
  ADD KEY `unit_id` (`unit_id`);

--
-- Indexes for table `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`request_id`);

--
-- Indexes for table `tenant_accounts`
--
ALTER TABLE `tenant_accounts`
  ADD PRIMARY KEY (`tenant_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `tenant_units`
--
ALTER TABLE `tenant_units`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tenantunits_unit` (`unit_id`),
  ADD KEY `fk_tenantunits_tenant` (`tenant_id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`unit_id`),
  ADD KEY `fk_units_admin` (`admin_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_accounts`
--
ALTER TABLE `admin_accounts`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `announcement_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `requests`
--
ALTER TABLE `requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tenant_accounts`
--
ALTER TABLE `tenant_accounts`
  MODIFY `tenant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tenant_units`
--
ALTER TABLE `tenant_units`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `unit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `units` (`admin_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `announcements_ibfk_2` FOREIGN KEY (`unit_id`) REFERENCES `units` (`unit_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tenant_units`
--
ALTER TABLE `tenant_units`
  ADD CONSTRAINT `fk_tenantunits_tenant` FOREIGN KEY (`tenant_id`) REFERENCES `tenant_accounts` (`tenant_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_tenantunits_unit` FOREIGN KEY (`unit_id`) REFERENCES `units` (`unit_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `units`
--
ALTER TABLE `units`
  ADD CONSTRAINT `fk_admin_units` FOREIGN KEY (`admin_id`) REFERENCES `admin_accounts` (`admin_id`),
  ADD CONSTRAINT `fk_units_admin` FOREIGN KEY (`admin_id`) REFERENCES `admin_accounts` (`admin_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
