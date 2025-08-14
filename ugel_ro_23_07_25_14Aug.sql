-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 14, 2025 at 02:52 PM
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
  `grid_power_export_opening_kwh` varchar(50) DEFAULT NULL,
  `grid_power_export_meter_closing_kwh` varchar(50) DEFAULT NULL,
  `grid_power_export_meter_difference_kwh` varchar(20) DEFAULT NULL,
  `grid_power_export_meter_file` varchar(100) DEFAULT NULL,
  `lng_storage_tank_file` varchar(100) DEFAULT NULL,
  `lng_dispenser_file` varchar(100) DEFAULT NULL,
  `geg_file` varchar(100) DEFAULT NULL,
  `unloading_mfm_file` varchar(100) DEFAULT NULL,
  `grid_power_meter_file` varchar(100) DEFAULT NULL,
  `solar_power_meter_file` varchar(100) DEFAULT NULL,
  `operter_id` varchar(50) DEFAULT NULL,
  `ro_id` int(11) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `is_submitted` tinyint(1) NOT NULL DEFAULT 0,
  `step` int(11) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lng_storage_mis`
--

INSERT INTO `lng_storage_mis` (`id`, `mis_date`, `lng_opening_level_l`, `lng_opening_level_kg`, `lng_closing_level_l`, `lng_closing_level_kg`, `lng_difference_kg`, `lng_totalizer_opening_kg`, `lng_totalizer_closing_kg`, `sold_qty_dispenser_kg`, `geg_totalizer_opening_kg`, `geg_totalizer_closing_kg`, `geg_consumption_kg`, `geg_running_hours`, `unloading_mfm_opening_reading_kg`, `unloading_mfm_closing_reading_kg`, `unloading_mfm_difference_kg`, `grid_power_opening_kwh`, `grid_power_meter_closing_kwh`, `grid_power_meter_difference_kwh`, `solar_power_opening_kwh`, `solar_power_meter_closing_kwh`, `solar_power_meter_difference_kwh`, `grid_power_export_opening_kwh`, `grid_power_export_meter_closing_kwh`, `grid_power_export_meter_difference_kwh`, `grid_power_export_meter_file`, `lng_storage_tank_file`, `lng_dispenser_file`, `geg_file`, `unloading_mfm_file`, `grid_power_meter_file`, `solar_power_meter_file`, `operter_id`, `ro_id`, `remarks`, `is_submitted`, `step`, `is_active`, `is_deleted`, `created`) VALUES
(1, '2025-08-13', '43467', '17386.8', '45000', '18000', '613.2000000000007', '56432', '60000', '3568', '50000', '60000', '10000', '2', '12000', '15000', '3000', '9000', '10000', '1000', '5000', '7000', '2000', '6000', '7000', '1000', '1755168536-1.jpg', '1755168270-0.jpg', '1755168333-0.jpg', '1755168420-0.jpg', '1755168466-0.jpg', '1755168536-0.jpg', '1755168536-2.jpg', '2', 8, 'test remark', 1, 0, 1, 0, '2025-08-14 16:12:02'),
(2, '2025-08-14', '45000', '18000', '50000', '20000', '2000', '60000', '50000', '-10000', '60000', '70000', '10000', '6', '15000', '20000', '5000', '10000', '14000', '4000', '7000', '9000', '2000', '7000', '10000', '3000', '1755170792-1.jpg', '1755170557-0.jpg', '1755170579-0.jpg', '1755170600-0.jpg', '1755170633-0.jpg', '1755170792-0.jpg', '1755170792-2.jpg', '2', 8, 'test huikm', 1, 0, 1, 0, '2025-08-14 16:52:37');

-- --------------------------------------------------------

--
-- Table structure for table `lng_tanker_unloading_records`
--

CREATE TABLE `lng_tanker_unloading_records` (
  `id` int(11) NOT NULL,
  `unloading_date` date DEFAULT current_timestamp(),
  `vehicle_number` varchar(20) DEFAULT NULL,
  `invoice_qty_kgs` varchar(20) DEFAULT NULL,
  `invoice_number` varchar(50) DEFAULT NULL,
  `totalizer_value_kgs_opening` varchar(30) DEFAULT NULL,
  `totalizer_value_kgs_closing` varchar(30) DEFAULT NULL,
  `total_unload_qty_kgs` varchar(30) DEFAULT NULL,
  `diff_qty_kgs` varchar(30) DEFAULT NULL,
  `unload_start_time_hrs` datetime DEFAULT NULL,
  `unload_end_time_hrs` datetime DEFAULT NULL,
  `total_time_taken` varchar(20) DEFAULT NULL,
  `operator_name` varchar(50) DEFAULT NULL,
  `invoice_date` date DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `file_list` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_deleted` tinyint(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `operter_id` varchar(50) DEFAULT NULL,
  `ro_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lng_tanker_unloading_records`
--

INSERT INTO `lng_tanker_unloading_records` (`id`, `unloading_date`, `vehicle_number`, `invoice_qty_kgs`, `invoice_number`, `totalizer_value_kgs_opening`, `totalizer_value_kgs_closing`, `total_unload_qty_kgs`, `diff_qty_kgs`, `unload_start_time_hrs`, `unload_end_time_hrs`, `total_time_taken`, `operator_name`, `invoice_date`, `remarks`, `file_list`, `is_active`, `is_deleted`, `created`, `operter_id`, `ro_id`) VALUES
(1, '2025-08-13', 'MH01AB1234', '39000', 'Invoice123', '45675', '14000', '-31675', '-70675', '2025-08-09 13:31:00', '2025-08-13 13:32:00', '96:01:00', 'JAykumar', '2025-08-11', 'test\r\n', '[\"1755072279-0.jpg\"]', 1, 0, '2025-08-13 13:34:39', '2', 8),
(2, '2025-08-14', 'MH01AB1234', '3000', 'INS354', '12000', '7000', '-5000', '-8000', '2025-08-13 13:21:00', '2025-08-13 16:21:00', '03:00:00', 'Kiran', '2025-08-14', 'test\r\n', '[\"1755168768-0.jpg\"]', 1, 0, '2025-08-14 16:22:48', '2', 8),
(3, '2025-08-14', 'MH01AB1234', '3000', 'Invoice123', '12000', '14000', '2000', '-1000', '2025-08-14 14:55:00', '2025-08-14 17:56:00', '03:01:00', 'JAykumar', '2025-08-13', 'test\r\n', '[\"1755174416-0.jpg\"]', 1, 0, '2025-08-14 17:56:56', '2', 8);

-- --------------------------------------------------------

--
-- Table structure for table `setings`
--

CREATE TABLE `setings` (
  `id` int(11) NOT NULL,
  `ltr_to_kg` varchar(10) NOT NULL,
  `created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `setings`
--

INSERT INTO `setings` (`id`, `ltr_to_kg`, `created`) VALUES
(1, '0.4', '2025-08-05 15:55:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `lng_storage_mis`
--
ALTER TABLE `lng_storage_mis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lng_tanker_unloading_records`
--
ALTER TABLE `lng_tanker_unloading_records`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `setings`
--
ALTER TABLE `setings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lng_storage_mis`
--
ALTER TABLE `lng_storage_mis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `lng_tanker_unloading_records`
--
ALTER TABLE `lng_tanker_unloading_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `setings`
--
ALTER TABLE `setings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
