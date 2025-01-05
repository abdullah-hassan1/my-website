-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 05, 2025 at 06:38 PM
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
-- Database: `gpa`
--

-- --------------------------------------------------------

--
-- Table structure for table `scheme`
--

CREATE TABLE `scheme` (
  `schemeId` int(11) NOT NULL,
  `grade` varchar(45) NOT NULL,
  `point` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `scheme`
--

INSERT INTO `scheme` (`schemeId`, `grade`, `point`) VALUES
(1, 'A', 4),
(2, 'A-', 3.67),
(3, 'B+', 3.33),
(4, 'B', 3),
(5, 'B-', 2.67),
(6, 'C+', 2.33),
(7, 'C', 2),
(8, 'C-', 1.87),
(9, 'D+', 1.33),
(10, 'D', 1),
(11, 'F', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `scheme`
--
ALTER TABLE `scheme`
  ADD PRIMARY KEY (`schemeId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `scheme`
--
ALTER TABLE `scheme`
  MODIFY `schemeId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
