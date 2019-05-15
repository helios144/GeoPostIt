-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 15, 2019 at 07:15 PM
-- Server version: 10.1.26-MariaDB
-- PHP Version: 7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `geopostit`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category`, `icon`) VALUES
(1, 'all', 'glyphicon glyphicon-globe'),
(2, 'general', 'glyphicon glyphicon-asterisk'),
(3, 'events', 'glyphicon glyphicon-calendar'),
(4, 'fun', 'glyphicon glyphicon-glass'),
(5, 'interesting', 'glyphicon glyphicon-eye-open'),
(6, 'sightseeing', 'glyphicon glyphicon-tree-deciduous'),
(7, 'incident', 'glyphicon glyphicon-warning-sign');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `share_option` int(255) NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `title` varchar(500) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `comment` varchar(5000) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `image` varchar(3000) CHARACTER SET ascii COLLATE ascii_bin NOT NULL,
  `category` varchar(255) NOT NULL,
  `post_creation_date` datetime NOT NULL,
  `delete_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `user_id`, `share_option`, `latitude`, `longitude`, `title`, `comment`, `image`, `category`, `post_creation_date`, `delete_date`) VALUES
(1, 1, 0, 54.686519, 25.290327, 'Gedimino pilis', 'pilis', 'img1.jpg', 'general', '2019-05-01 00:00:00', '0000-00-00 00:00:00'),
(2, 1, 1, 54.685577, 25.286563, 'Katedra\r\n', 'KATEDRA', 'img1.jpg', 'general', '2019-04-09 00:00:00', '0000-00-00 00:00:00'),
(3, 1, 1, 54.726444, 25.329961, 'VGTU biblioteka', 'Atėjo daug žmonių pasimokyti', 'img2.jpg', 'general', '2019-05-01 00:00:00', '0000-00-00 00:00:00'),
(5, 4, 1, 54.733486, 25.226464, 'Pašilaičių miškelis', 'Pašilaičių miškas kur visi renkasi pasigrožėti gamta', 'img3.jpg', 'general', '2019-02-01 00:00:00', '2119-04-20 23:00:24');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(255) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `password_hash` varchar(2000) NOT NULL,
  `share_list` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `password_hash`, `share_list`) VALUES
(2, 'helios', '$2y$10$xGi/c/avvhCh.lfQRm6SFOtnVGn0gd0Mf9xCEXySmEU7Jq8X01U72', '1'),
(3, 'admin', '$2y$10$vY0GhYZCJ9JPVXAnDYYfLulTWXSEO5PPNsgt854tRGvQQuZ4Zrz/O', '1'),
(4, 'helios144', '$2y$10$qHTqXkWy9ZFZE9LlfLAuR.9F7PLQUgtL87awxdphQziNVP6je27Eq', '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
