-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 09, 2024 at 06:10 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `valeo-app`
--

-- --------------------------------------------------------

--
-- Table structure for table `production_plan_detail`
--

CREATE TABLE `production_plan_detail` (
  `id` int(11) NOT NULL,
  `Production_plan` varchar(30) NOT NULL,
  `Id_material` varchar(30) NOT NULL,
  `Material_desc` varchar(128) NOT NULL,
  `Material_need` int(11) NOT NULL,
  `Uom` varchar(5) NOT NULL,
  `status` int(11) NOT NULL,
  `Crtdt` varchar(128) NOT NULL,
  `Crtby` varchar(128) NOT NULL,
  `Upddt` varchar(128) NOT NULL,
  `Updby` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
