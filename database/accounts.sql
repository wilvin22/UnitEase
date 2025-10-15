-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 16, 2025 at 01:31 AM
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
-- Database: `unitease`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `account_id` int(11) NOT NULL,
  `account_type` varchar(20) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` char(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(11) NOT NULL,
  `reg_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`account_id`, `account_type`, `username`, `password`, `email`, `phone_number`, `reg_date`) VALUES
(1, '', 'Wilvin1', '$2y$10$WUYynUWPdmesDTChMlEIOeZcp18A35evG68TqjcNEXSFTntG0yGCy', 'johnwilvin22@gmail.com', '09085013142', '2025-10-08 02:28:20'),
(9, '', 'wilvinffs', '$2y$10$/tJbP3UHqCPH7vjHf/F0K.KC32dXjC6hMrZOgR.GQEk2ERDJ2uL.6', 'johnwilvin22@gmail.com', '09085013142', '2025-10-09 02:37:15'),
(10, '', 'john_ka_magaling22', '$2y$10$y0TgvChx5y0z.q6KflNGz.GXYtVlsgxWME/5.hRv3LVZRQziKwdCy', 'johnwilvin22@gmail.com', '09085013142', '2025-10-09 02:42:32'),
(11, '', 'ganda', '$2y$10$jjPPyv1f3XOHUPAr1/3iqubf/kqEVmK57dx1hxBcukwfqeuO0WWjm', 'hiterozamaryjoy@gmail.com', '09454061801', '2025-10-09 08:18:46');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`account_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
