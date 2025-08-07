-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 07, 2025 at 01:41 PM
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
-- Database: `ugel_ro_23_07_25`
--

-- --------------------------------------------------------

--
-- Table structure for table `lng_storage_mis`
--

CREATE TABLE `lng_storage_mis` (
  `id` int(11) NOT NULL,
  `mis_date` date NOT NULL DEFAULT current_timestamp(),
  `lng_opening_level_l` varchar(30) DEFAULT NULL,
  `lng_opening_level_kg` varchar(30) DEFAULT NULL,
  `lng_closing_level_l` varchar(30) DEFAULT NULL,
  `lng_closing_level_kg` varchar(30) DEFAULT NULL,
  `lng_difference_kg` varchar(30) DEFAULT NULL,
  `lng_totalizer_opening_kg` varchar(30) DEFAULT NULL,
  `lng_totalizer_closing_kg` varchar(30) DEFAULT NULL,
  `sold_qty_dispenser_kg` varchar(30) DEFAULT NULL,
  `geg_totalizer_opening_kg` varchar(30) DEFAULT NULL,
  `geg_totalizer_closing_kg` varchar(30) DEFAULT NULL,
  `geg_consumption_kg` varchar(30) DEFAULT NULL,
  `geg_running_hours` varchar(20) DEFAULT NULL,
  `unloading_mfm_opening_reading_kg` varchar(30) DEFAULT NULL,
  `unloading_mfm_closing_reading_kg` varchar(30) DEFAULT NULL,
  `unloading_mfm_difference_kg` varchar(30) DEFAULT NULL,
  `grid_power_opening_kwh` varchar(30) DEFAULT NULL,
  `grid_power_meter_closing_kwh` varchar(30) DEFAULT NULL,
  `grid_power_meter_difference_kwh` varchar(30) DEFAULT NULL,
  `solar_power_opening_kwh` varchar(30) DEFAULT NULL,
  `solar_power_meter_closing_kwh` varchar(30) DEFAULT NULL,
  `solar_power_meter_difference_kwh` varchar(30) DEFAULT NULL,
  `lng_storage_tank_file` varchar(100) DEFAULT NULL,
  `lng_dispenser_file` varchar(100) DEFAULT NULL,
  `geg_file` varchar(100) DEFAULT NULL,
  `unloading_mfm_file` varchar(100) NOT NULL,
  `grid_power_meter_file` varchar(100) DEFAULT NULL,
  `solar_power_meter_file` varchar(100) NOT NULL,
  `operter_id` varchar(50) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `is_submitted` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `lng_storage_mis`
--
ALTER TABLE `lng_storage_mis`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lng_storage_mis`
--
ALTER TABLE `lng_storage_mis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
