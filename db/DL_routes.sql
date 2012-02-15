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
-- Table structure for table `DL_routes`
--

CREATE TABLE IF NOT EXISTS `DL_routes` (
  `ROUTEID` int(4) NOT NULL DEFAULT '0',
  `ROUTEIDENTIFIER` int(4) DEFAULT NULL,
  `ROUTEDESCRPTION` varchar(50) DEFAULT NULL,
  `ROUTEPUBLICIDENTIFIER` varchar(4) DEFAULT NULL,
  `ROUTEVERSION` varchar(9) DEFAULT NULL,
  `ROUTESERVICETYPE` int(1) DEFAULT NULL,
  `ROUTESERVICEMODE` int(1) DEFAULT NULL,
  PRIMARY KEY (`ROUTEID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `DL_routes`
--

INSERT INTO `DL_routes` (`ROUTEID`, `ROUTEIDENTIFIER`, `ROUTEDESCRPTION`, `ROUTEPUBLICIDENTIFIER`, `ROUTEVERSION`, `ROUTESERVICETYPE`, `ROUTESERVICEMODE`) VALUES
(2, 4001, 'Hasselt - Genk - Zwartberg', '1', '10-01', 0, 0),
(3, 4001, 'Hasselt - Genk - Zwartberg', '1', '10-08', 0, 0),
(4, 4002, 'Hasselt - Beringen (Mijnen)', '2', '10-01', 0, 0),
(5, 4002, 'Hasselt - Beringen (Mijnen)', '2', '10-08', 0, 0),
(6, 4003, 'Hasselt - Wellen - Borgloon - Heers', '3', '10-01', 0, 0),
(7, 4003, 'Hasselt - Wellen - Borgloon - Heers', '3', '10-08', 0, 0),
(8, 4004, 'Hasselt - Kortessem - Tongeren', '4', '10-01', 0, 0),
(9, 4004, 'Hasselt - Kortessem - Tongeren', '4', '10-08', 0, 0),
(10, 4005, 'Hasselt - Nieuwerkerken - Sint-Truiden', '5', '10-01', 0, 0),
(11, 4005, 'Hasselt - Nieuwerkerken - Sint-Truiden', '5', '10-08', 0, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
