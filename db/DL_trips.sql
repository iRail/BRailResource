-- phpMyAdmin SQL Dump
-- version 3.4.5deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 15, 2012 at 04:15 PM
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
-- Table structure for table `DL_trips`
--

CREATE TABLE IF NOT EXISTS `DL_trips` (
  `TRIPID` int(11) NOT NULL,
  `ROUTEID` int(11) NOT NULL,
  `VSID` int(11) NOT NULL,
  `TRIPNOTEIDENTIFIER` int(11) NOT NULL,
  `TRIPNOTETEXT` varchar(255) NOT NULL,
  `TRIPSTART` varchar(255) NOT NULL,
  `TRIPEND` varchar(255) NOT NULL,
  `TRIPSHIFTSTART` varchar(255) NOT NULL,
  `TRIPSHIFTEND` varchar(255) NOT NULL,
  `TRIPNOTEIDENTIFIER2` varchar(255) NOT NULL,
  `TRIPNOTE` varchar(255) NOT NULL,
  PRIMARY KEY (`TRIPID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `DL_trips`
--

INSERT INTO `DL_trips` (`TRIPID`, `ROUTEID`, `VSID`, `TRIPNOTEIDENTIFIER`, `TRIPNOTETEXT`, `TRIPSTART`, `TRIPEND`, `TRIPSHIFTSTART`, `TRIPSHIFTEND`, `TRIPNOTEIDENTIFIER2`, `TRIPNOTE`) VALUES
(1278042, 5464, 2573, 0, '', '10:22', '10:50', '0', '0', '', ''),
(1278043, 5464, 2573, 0, '', '05:53', '06:21', '0', '0', '', ''),
(1278044, 5464, 2573, 0, '', '10:52', '11:20', '0', '0', '', ''),
(1278045, 5464, 2573, 0, '', '11:22', '11:50', '0', '0', '', ''),
(1278046, 5464, 2573, 0, '', '12:10', '12:38', '0', '0', '', ''),
(1278047, 5464, 2573, 0, '', '12:40', '13:08', '0', '0', '', ''),
(1278048, 5464, 2573, 0, '', '13:10', '13:38', '0', '0', '', ''),
(1278049, 5464, 2573, 0, '', '13:40', '14:08', '0', '0', '', ''),
(1278050, 5464, 2573, 0, '', '14:10', '14:38', '0', '0', '', ''),
(1278051, 5464, 2573, 0, '', '14:40', '15:08', '0', '0', '', ''),
(1278052, 5464, 2573, 0, '', '15:10', '15:38', '0', '0', '', ''),
(1278053, 5464, 2573, 0, '', '06:24', '06:52', '0', '0', '', ''),
(1278054, 5464, 2573, 0, '', '15:40', '16:08', '0', '0', '', ''),
(1278055, 5464, 2573, 0, '', '16:10', '16:38', '0', '0', '', ''),
(1278056, 5464, 2573, 0, '', '16:40', '17:08', '0', '0', '', ''),
(1278057, 5464, 2573, 0, '', '17:10', '17:38', '0', '0', '', ''),
(1278058, 5464, 2573, 0, '', '17:40', '18:08', '0', '0', '', ''),
(1278059, 5464, 2573, 0, '', '18:10', '18:38', '0', '0', '', ''),
(1278060, 5464, 2573, 0, '', '18:40', '19:08', '0', '0', '', ''),
(1278061, 5464, 2573, 0, '', '19:10', '19:38', '0', '0', '', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
