-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 27, 2025 at 06:17 AM
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
-- Database: `smartgarden`
--

-- --------------------------------------------------------

--
-- Table structure for table `sensor_data`
--

CREATE TABLE `sensor_data` (
  `id` int(11) NOT NULL,
  `timestamp` datetime DEFAULT current_timestamp(),
  `temperature` float DEFAULT NULL,
  `humidity` float DEFAULT NULL,
  `soil` float DEFAULT NULL,
  `airquality` float DEFAULT NULL,
  `plantheight` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sensor_data`
--

INSERT INTO `sensor_data` (`id`, `timestamp`, `temperature`, `humidity`, `soil`, `airquality`, `plantheight`) VALUES
(1, '2025-04-27 10:00:10', 25.4, 60.2, 40.5, 120, 15.3),
(2, '2025-04-27 10:00:10', 26.1, 58.8, 42, 130, 16),
(3, '2025-04-27 10:00:10', 24.9, 61.5, 39.8, 110, 14.5),
(4, '2025-04-27 10:00:10', 27, 57.2, 43.5, 140, 16.8),
(5, '2025-04-27 10:00:10', 25.8, 59, 41, 125, 15.6),
(6, '2025-04-27 10:00:10', 26.5, 58.3, 40.7, 135, 16.2),
(7, '2025-04-27 10:00:10', 24.5, 62, 38.9, 115, 14.2),
(8, '2025-04-27 10:00:10', 27.2, 56.5, 44.1, 145, 17),
(9, '2025-04-27 10:00:10', 25.1, 60, 40, 122, 15.1),
(10, '2025-04-27 10:00:10', 26, 59.5, 41.2, 132, 16);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sensor_data`
--
ALTER TABLE `sensor_data`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sensor_data`
--
ALTER TABLE `sensor_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
