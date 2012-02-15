-- phpMyAdmin SQL Dump
-- version 3.4.5deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 15, 2012 at 04:18 PM
-- Server version: 5.1.58
-- PHP Version: 5.3.6-13ubuntu3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `logging`
--

-- --------------------------------------------------------

--
-- Table structure for table `DL_calendar`
--

CREATE TABLE IF NOT EXISTS `DL_calendar` (
  `VSCID` int(5) NOT NULL DEFAULT '0',
  `VSID` int(4) DEFAULT NULL,
  `VSCDATE` varchar(10) DEFAULT NULL,
  `VSCDAY` varchar(9) DEFAULT NULL,
  PRIMARY KEY (`VSCID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `DL_calendar`
--

INSERT INTO `DL_calendar` (`VSCID`, `VSID`, `VSCDATE`, `VSCDAY`) VALUES
(33451, 1944, '2012/01/07', 'Saturday'),
(33486, 1944, '2011/12/31', 'Saturday'),
(33495, 1945, '2012/01/07', 'Saturday'),
(33526, 1945, '2011/12/31', 'Saturday'),
(33530, 1947, '2012/01/01', 'Sunday'),
(33542, 1947, '2012/01/08', 'Sunday'),
(33571, 1947, '2011/12/25', 'Sunday'),
(33579, 1948, '2012/01/01', 'Sunday'),
(33590, 1948, '2012/01/08', 'Sunday'),
(33615, 1948, '2011/12/25', 'Sunday');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
