-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 17, 2021 at 08:20 PM
-- Server version: 5.7.31
-- PHP Version: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `chat`
--

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nick` varchar(50) COLLATE utf8mb4_turkish_ci NOT NULL,
  `content` text COLLATE utf8mb4_turkish_ci NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `nick`, `content`, `created`) VALUES
(35, 'user123', 'hi all', '2021-05-17 23:18:35'),
(36, 'excalibur98', 'hey', '2021-05-17 23:18:50'),
(37, 'excalibur98', 'we are only two right now ?!', '2021-05-17 23:19:31'),
(38, 'anonim34', 'no Im here also', '2021-05-17 23:20:19'),
(39, 'anonim34', 'hi everyone', '2021-05-17 23:20:29');

-- --------------------------------------------------------

--
-- Table structure for table `nickinuse`
--

DROP TABLE IF EXISTS `nickinuse`;
CREATE TABLE IF NOT EXISTS `nickinuse` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nick` varchar(35) COLLATE utf8mb4_turkish_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nick` (`nick`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Dumping data for table `nickinuse`
--

INSERT INTO `nickinuse` (`id`, `nick`) VALUES
(24, 'anonim34'),
(18, 'Semih'),
(20, 'Uğur');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
