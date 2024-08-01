-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 01, 2024 at 05:49 PM
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
-- Database: `employee_task_management_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance_info`
--

CREATE TABLE `attendance_info` (
  `attendance_id` int(20) NOT NULL,
  `atn_user_id` int(20) NOT NULL,
  `in_time` datetime DEFAULT NULL,
  `out_time` datetime DEFAULT NULL,
  `total_duration` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `attendance_info`
--

INSERT INTO `attendance_info` (`attendance_id`, `atn_user_id`, `in_time`, `out_time`, `total_duration`) VALUES
(1, 1, '2024-04-18 16:28:47', '2024-08-01 16:18:50', '05:59:59'),
(2, 28, '2024-04-18 16:33:00', '2024-04-18 16:33:54', '00:00:54'),
(3, 27, '2024-04-18 16:35:44', '2024-04-18 16:35:46', '00:00:02'),
(4, 27, '2024-04-27 14:12:32', '2024-04-27 14:12:36', '00:00:04'),
(5, 28, '2024-04-27 14:13:23', '2024-04-27 14:13:27', '00:00:04'),
(6, 27, '2024-04-27 17:05:35', '2024-04-27 17:05:42', '00:00:07'),
(7, 27, '2024-05-13 14:20:19', '2024-08-01 16:28:29', '05:59:59'),
(27, 0, '2024-08-01 16:28:18', '2024-08-01 16:30:08', '05:59:59');

-- --------------------------------------------------------

--
-- Table structure for table `task_info`
--

CREATE TABLE `task_info` (
  `task_id` int(50) NOT NULL,
  `task_title` varchar(120) NOT NULL,
  `task_description` text DEFAULT NULL,
  `task_start_time` datetime DEFAULT NULL,
  `task_end_time` datetime DEFAULT NULL,
  `t_user_id` int(20) NOT NULL,
  `task_status` int(11) DEFAULT NULL COMMENT '0 = incomplete, 1 = In progress, 2 = complete',
  `task_grade` varchar(500) NOT NULL COMMENT '0 = Good, 1 = Very Good, 2 = Exceptional, 3 = Bad',
  `task_assign_to` varchar(1000) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `task_info`
--

INSERT INTO `task_info` (`task_id`, `task_title`, `task_description`, `task_start_time`, `task_end_time`, `t_user_id`, `task_status`, `task_grade`, `task_assign_to`) VALUES
(1, 'Take out trash', 'Clear the working area around the finance office.', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 27, 0, '1', ''),
(2, 'Make Sure the Visitors Feel Welcomed', 'Today the DVCAA is expecting High-End visitors that require maximum protection.', '1970-01-01 01:00:00', '1970-01-01 01:00:00', 28, 0, '2', ''),
(6, 'Clean My Computer', 'Clear the Cache and Install Linux', NULL, NULL, 0, 0, '', NULL),
(7, 'RedRum', 'Fill the Bottles', NULL, NULL, 0, 0, '', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `user_id` int(20) NOT NULL,
  `fullname` varchar(120) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `temp_password` varchar(100) DEFAULT NULL,
  `user_role` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci COMMENT='2';

--
-- Dumping data for table `tbl_admin`
--

INSERT INTO `tbl_admin` (`user_id`, `fullname`, `username`, `email`, `password`, `temp_password`, `user_role`) VALUES
(1, 'Racheal Christine', 'admin', 'admin@gmail.com', '0192023a7bbd73250516f069df18b500', NULL, 1),
(27, 'John Smitharins', 'jsmith', 'jsmith@sample.com', '9ddc44f3f7f78da5781d6cab571b2fc5', '', 2),
(28, 'Richard Muchoki', 'rmrm', 'richie@gmail.com', 'ab2b0766cb8a1d46c72cabb618a963af', '', 2),
(29, 'Richard Omuzengar', 'Rick', 'richardmuchoki7@gmail.com', '2a92ea06bc9bbbece4088d10c6ed5fb2', '1369997', 2),
(30, 'Ruth Kimeu', 'Raki', 'raki@gmail.com', '5d8e6d158ef7682c980fb61e4faab449', '2110308', 2),
(31, 'Woza Woza', 'Wozling', 'wozling@gmail.com', 'b1ceb18a9dab1f8ecd3543c9f49e3cb3', '6955733', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance_info`
--
ALTER TABLE `attendance_info`
  ADD PRIMARY KEY (`attendance_id`);

--
-- Indexes for table `task_info`
--
ALTER TABLE `task_info`
  ADD PRIMARY KEY (`task_id`);

--
-- Indexes for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance_info`
--
ALTER TABLE `attendance_info`
  MODIFY `attendance_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `task_info`
--
ALTER TABLE `task_info`
  MODIFY `task_id` int(50) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `user_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
