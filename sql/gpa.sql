-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 04, 2017 at 08:39 PM
-- Server version: 5.6.24
-- PHP Version: 5.6.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `gpa`
--

-- --------------------------------------------------------

--
-- Table structure for table `scheme`
--

CREATE TABLE IF NOT EXISTS `scheme` (
  `schemeId` int(11) NOT NULL,
  `grade` varchar(45) NOT NULL,
  `point` double NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

--
-- Table structure for table `semester`
--

CREATE TABLE IF NOT EXISTS `semester` (
  `semesterId` int(10) unsigned NOT NULL,
  `semesterName` varchar(45) DEFAULT NULL,
  `totCredits` double DEFAULT NULL,
  `SemesterGPA` double DEFAULT NULL,
  `users_userId` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Table structure for table `subject`
--

CREATE TABLE IF NOT EXISTS `subject` (
  `subjectId` int(10) unsigned NOT NULL,
  `subjectCode` varchar(45) DEFAULT NULL,
  `subjectName` varchar(45) NOT NULL,
  `subjectCredits` varchar(45) NOT NULL,
  `isGpa` tinyint(1) NOT NULL,
  `Semester_semesterId` int(10) unsigned NOT NULL,
  `Grade` varchar(45) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `userId` int(11) NOT NULL,
  `userName` varchar(30) NOT NULL,
  `userEmail` varchar(60) NOT NULL,
  `userPass` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `scheme`
--
ALTER TABLE `scheme`
  ADD PRIMARY KEY (`schemeId`);

--
-- Indexes for table `semester`
--
ALTER TABLE `semester`
  ADD PRIMARY KEY (`semesterId`), ADD KEY `fk_Semester_users_idx` (`users_userId`);

--
-- Indexes for table `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`subjectId`,`Semester_semesterId`), ADD KEY `fk_Subject_Semester1_idx` (`Semester_semesterId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`), ADD UNIQUE KEY `userEmail` (`userEmail`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `scheme`
--
ALTER TABLE `scheme`
  MODIFY `schemeId` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `semester`
--
ALTER TABLE `semester`
  MODIFY `semesterId` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `subject`
--
ALTER TABLE `subject`
  MODIFY `subjectId` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `semester`
--
ALTER TABLE `semester`
ADD CONSTRAINT `fk_Semester_users` FOREIGN KEY (`users_userId`) REFERENCES `users` (`userId`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `subject`
--
ALTER TABLE `subject`
ADD CONSTRAINT `fk_Subject_Semester1` FOREIGN KEY (`Semester_semesterId`) REFERENCES `semester` (`semesterId`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
