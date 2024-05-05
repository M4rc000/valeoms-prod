-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 05, 2024 at 09:39 PM
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
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL,
  `username` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `date_joined` varchar(128) NOT NULL,
  `is_active` int(11) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `username`, `password`, `gender`, `date_joined`, `is_active`, `role_id`) VALUES
(1, 'Marco Antonio', 'Prime', '$2y$10$DMF.T6mKOkmOUmpuH9mTseHBoOtZj4hX2TVkt4B6DMieiROqdwXQi', 'Male', '2024-05-04 01:00', 1, 1),
(2, 'Jack Bryan', 'Jack', '$2y$10$DMF.T6mKOkmOUmpuH9mTseHBoOtZj4hX2TVkt4B6DMieiROqdwXQi', 'Male', '2024-05-04 01:00', 1, 2),
(3, 'KejaHoran', 'Bee', '$2y$10$iDDWqB2VjA2g6.7KFJsta.ovaePZDn5ie/ow7xGKN4zPsBQYID/ty', 'female', '06-05-2024 00:31', 1, 3),
(5, 'Fiona', 'Scientist', '$2y$10$.LqCoCKdWZslANF6d5aRKexfbD9vzfd8O90qx0B5mhpfwMJ5SUUW.', 'female', '06-05-2024 01:54', 1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `user_access_menu`
--

CREATE TABLE `user_access_menu` (
  `id` int(2) NOT NULL,
  `role_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_access_menu`
--

INSERT INTO `user_access_menu` (`id`, `role_id`, `menu_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 5),
(5, 2, 2),
(6, 2, 5),
(7, 3, 3),
(8, 3, 5);

-- --------------------------------------------------------

--
-- Table structure for table `user_menu`
--

CREATE TABLE `user_menu` (
  `id` int(11) NOT NULL,
  `menu` varchar(20) NOT NULL,
  `crtdt` varchar(20) NOT NULL,
  `crtby` varchar(20) NOT NULL,
  `upddt` varchar(20) NOT NULL,
  `updby` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_menu`
--

INSERT INTO `user_menu` (`id`, `menu`, `crtdt`, `crtby`, `upddt`, `updby`) VALUES
(1, 'Admin', '2024-05-04 01:00', 'System', '2024-05-04 01:00', 'System'),
(2, 'Warehouse', '2024-05-04 01:00', 'System', '2024-05-04 01:00', 'System'),
(3, 'Production', '2024-05-04 01:00', 'System', '2024-05-04 01:00', 'System'),
(4, 'Finished Good', '2024-05-04 01:00', 'System', '2024-05-04 01:00', 'System'),
(5, 'User', '2024-05-04 01:00', 'System', '2024-05-04 01:00', 'System');

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `id` int(2) NOT NULL,
  `role` varchar(30) NOT NULL,
  `crtdt` varchar(50) NOT NULL,
  `crtby` varchar(50) NOT NULL,
  `upddt` varchar(128) NOT NULL,
  `updby` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`id`, `role`, `crtdt`, `crtby`, `upddt`, `updby`) VALUES
(1, 'Administrator', '2024-05-04 01:00', 'System', '2024-05-04 01:00', 'System'),
(2, 'Warehouse', '2024-05-04 01:00', 'System', '2024-05-04 01:00', 'System'),
(4, 'Production', '2024-05-04 01:00', 'System', '2024-05-04 01:00', 'System'),
(5, 'FG', '2024-05-04 01:00', 'System', '2024-05-04 01:00', 'System');

-- --------------------------------------------------------

--
-- Table structure for table `user_sub_menu`
--

CREATE TABLE `user_sub_menu` (
  `id` int(11) NOT NULL,
  `menu_id` int(2) NOT NULL,
  `title` varchar(20) NOT NULL,
  `url` varchar(50) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `is_active` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user_sub_menu`
--

INSERT INTO `user_sub_menu` (`id`, `menu_id`, `title`, `url`, `icon`, `is_active`) VALUES
(1, 1, 'Dashboard', 'admin/', 'bx bxs-dashboard', 1),
(2, 1, 'Manage User', 'admin/manage_user', 'bx bxs-group', 1),
(3, 1, 'Manage Role', 'admin/manage_role', 'bx bxs-purchase-tag', 1),
(4, 1, 'Manage Menu', 'admin/manage_menu', 'bx bx-menu', 1),
(5, 1, 'Manage Sub-menu', 'admin/manage_sub_menu', 'bx bx-menu-alt-right', 1),
(6, 1, 'Manage Books', 'admin/manage_books', 'mdi mdi-book-open-page-variant', 1),
(7, 2, 'Receiving Material', 'warehouse/receiving_material', 'bx bxl-dropbox', 1),
(8, 2, 'List Storage', 'warehouse/list_storage', 'bx bxs-cabinet', 1),
(9, 2, 'List Material Report', 'warehouse/list_material_report', 'bx bxs-report', 1),
(10, 2, 'Re-Grouping', 'warehouse/regrouping', 'bx  bxs-collection', 1),
(11, 2, 'Cycle Count', 'warehouse/cycle_count', 'bx bx-recycle', 1),
(12, 2, 'Production Request', 'warehouse/production_request', 'bx bxs-archive-in', 1),
(13, 3, 'Material Request', 'production/material_request', 'bx bxs-card', 1),
(14, 3, 'Kitting', 'production/kitting', 'bi bi-inbox', 1),
(15, 3, 'Kanban Box', 'production/kanban_box', 'bi bi-inbox-fill', 1),
(16, 3, 'Material Return', 'production/material_return', 'bi bi-arrow-repeat', 1),
(17, 1, 'Manage Storage', 'admin/manage_storage', 'bi bi-bounding-box', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_access_menu`
--
ALTER TABLE `user_access_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_menu`
--
ALTER TABLE `user_menu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_sub_menu`
--
ALTER TABLE `user_sub_menu`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_access_menu`
--
ALTER TABLE `user_access_menu`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user_menu`
--
ALTER TABLE `user_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_role`
--
ALTER TABLE `user_role`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_sub_menu`
--
ALTER TABLE `user_sub_menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
