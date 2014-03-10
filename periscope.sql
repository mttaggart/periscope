-- phpMyAdmin SQL Dump
-- version 4.0.6deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 10, 2014 at 02:06 PM
-- Server version: 5.5.35-0ubuntu0.13.10.2
-- PHP Version: 5.5.3-1ubuntu2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `periscope`
--

-- --------------------------------------------------------

--
-- Table structure for table `Activities`
--

CREATE TABLE IF NOT EXISTS `Activities` (
  `ACT_ID` int(11) NOT NULL AUTO_INCREMENT,
  `U_ID` int(11) NOT NULL,
  `Text` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ACT_ID`),
  KEY `U_ID` (`U_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `Admins`
--

CREATE TABLE IF NOT EXISTS `Admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `password` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `Assessments`
--

CREATE TABLE IF NOT EXISTS `Assessments` (
  `ASS_ID` int(11) NOT NULL AUTO_INCREMENT,
  `U_ID` int(11) NOT NULL,
  `Text` varchar(255) DEFAULT NULL,
  `AT_ID` int(11) NOT NULL,
  PRIMARY KEY (`ASS_ID`),
  KEY `U_ID` (`U_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

-- --------------------------------------------------------

--
-- Table structure for table `AssessmentTypes`
--

CREATE TABLE IF NOT EXISTS `AssessmentTypes` (
  `AT_ID` int(11) NOT NULL AUTO_INCREMENT,
  `AT_Text` varchar(30) NOT NULL,
  PRIMARY KEY (`AT_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `Content`
--

CREATE TABLE IF NOT EXISTS `Content` (
  `CON_ID` int(11) NOT NULL AUTO_INCREMENT,
  `U_ID` int(11) NOT NULL,
  `Text` varchar(255) NOT NULL,
  PRIMARY KEY (`CON_ID`),
  KEY `U_ID` (`U_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table `EssentialQuestions`
--

CREATE TABLE IF NOT EXISTS `EssentialQuestions` (
  `EQ_ID` int(11) NOT NULL AUTO_INCREMENT,
  `U_ID` int(11) NOT NULL,
  `Text` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`EQ_ID`),
  KEY `U_ID` (`U_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

-- --------------------------------------------------------

--
-- Table structure for table `GradeLevels`
--

CREATE TABLE IF NOT EXISTS `GradeLevels` (
  `GL_ID` int(11) NOT NULL AUTO_INCREMENT,
  `level` varchar(2) CHARACTER SET utf8 NOT NULL,
  `longname` varchar(30) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`GL_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table `Resources`
--

CREATE TABLE IF NOT EXISTS `Resources` (
  `RSC_ID` int(11) NOT NULL AUTO_INCREMENT,
  `U_ID` int(11) NOT NULL,
  `Text` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`RSC_ID`),
  KEY `U_ID` (`U_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `Skills`
--

CREATE TABLE IF NOT EXISTS `Skills` (
  `SKL_ID` int(11) NOT NULL AUTO_INCREMENT,
  `U_ID` int(11) NOT NULL,
  `Text` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`SKL_ID`),
  KEY `U_ID` (`U_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=49 ;

-- --------------------------------------------------------

--
-- Table structure for table `Subjects`
--

CREATE TABLE IF NOT EXISTS `Subjects` (
  `S_ID` int(11) NOT NULL AUTO_INCREMENT,
  `shortname` varchar(30) CHARACTER SET latin1 NOT NULL,
  `longname` varchar(255) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`S_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `Units`
--

CREATE TABLE IF NOT EXISTS `Units` (
  `U_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) DEFAULT NULL,
  `GradeLevel_id` int(11) NOT NULL,
  `Subject_id` int(11) NOT NULL,
  `StartDate` date DEFAULT NULL,
  `EndDate` date DEFAULT NULL,
  `enabled` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`U_ID`),
  KEY `GradeLevel_id` (`GradeLevel_id`),
  KEY `Subject_id` (`Subject_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Activities`
--
ALTER TABLE `Activities`
  ADD CONSTRAINT `Activities_ibfk_1` FOREIGN KEY (`U_ID`) REFERENCES `Units` (`U_ID`);

--
-- Constraints for table `Assessments`
--
ALTER TABLE `Assessments`
  ADD CONSTRAINT `Assessments_ibfk_1` FOREIGN KEY (`U_ID`) REFERENCES `Units` (`U_ID`);

--
-- Constraints for table `Content`
--
ALTER TABLE `Content`
  ADD CONSTRAINT `Content_ibfk_1` FOREIGN KEY (`U_ID`) REFERENCES `Units` (`U_ID`);

--
-- Constraints for table `EssentialQuestions`
--
ALTER TABLE `EssentialQuestions`
  ADD CONSTRAINT `EssentialQuestions_ibfk_1` FOREIGN KEY (`U_ID`) REFERENCES `Units` (`U_ID`);

--
-- Constraints for table `Resources`
--
ALTER TABLE `Resources`
  ADD CONSTRAINT `Resources_ibfk_1` FOREIGN KEY (`U_ID`) REFERENCES `Units` (`U_ID`);

--
-- Constraints for table `Skills`
--
ALTER TABLE `Skills`
  ADD CONSTRAINT `Skills_ibfk_1` FOREIGN KEY (`U_ID`) REFERENCES `Units` (`U_ID`);

--
-- Constraints for table `Units`
--
ALTER TABLE `Units`
  ADD CONSTRAINT `Units_ibfk_1` FOREIGN KEY (`GradeLevel_id`) REFERENCES `GradeLevels` (`GL_ID`),
  ADD CONSTRAINT `Units_ibfk_2` FOREIGN KEY (`GradeLevel_id`) REFERENCES `GradeLevels` (`GL_ID`),
  ADD CONSTRAINT `Units_ibfk_3` FOREIGN KEY (`Subject_id`) REFERENCES `Subjects` (`S_ID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
