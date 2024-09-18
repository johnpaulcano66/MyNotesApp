-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 03, 2021 at 03:56 AM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.0.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `note_taking`
--

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `note_id` int(11) NOT NULL,
  `user_ID` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `note` varchar(1000) NOT NULL,
  `time_in` varchar(50) NOT NULL,
  `last_updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`note_id`, `user_ID`, `title`, `note`, `time_in`, `last_updated_at`) VALUES
(6, 1, 'Momentum', 'Momentum is a commonly used term in sports. A team that has the momentum is on the move and is going to take some effort to stop. A team that has a lot of momentum is really on the move and is going to be hard to stop. Momentum is a physics term; it refers to the quantity of motion that an object has. A sports team that is on the move has the momentum. If an object is in motion (on the move) then it has momentum.\r\n\r\nMomentum can be defined as \"mass in motion.\" All objects have mass; so if an object is moving, then it has momentum - it has its mass in motion. The amount of momentum that an object has is dependent upon two variables: how much stuff is moving and how fast the stuff is moving. Momentum depends upon the variables mass and velocity. In terms of an equation, the momentum of an object is equal to the mass of the object times the velocity of the object.', '10:15:08pm', '2021-09-02 01:02:43'),
(7, 1, 'Cohesive Force', 'Excel is a spreadsheet program that allows you to store, organize, and analyze information. While you may think Excel is only used by certain people to process complicated data, anyone can learn how to take advantage of the program\'s powerful features. Whether you\'re keeping a budget, organizing a training log, or creating an invoice, Excel makes it easy to work with different types of data.', '12:11:04am', '2021-09-02 00:54:34'),
(8, 2, 'Photosynthesis', 'Photosynthesis is a process used by plants and other organisms to convert light energy into chemical energy that, through cellular respiration, can later be released to fuel the organism\'s activities.', '12:26:33am', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `register`
--

CREATE TABLE `register` (
  `user_ID` int(11) NOT NULL,
  `fullName` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `register`
--

INSERT INTO `register` (`user_ID`, `fullName`, `email`, `password`) VALUES
(1, 'Nathaniel Nkrumah', 'code@gmail.com', 'e9b59046bfad66983177acea12045cb9'),
(2, 'William Abormega', 'nnn@gmail.com', 'd933df149c62be04ea54d3a9bfb0372c');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`note_id`);

--
-- Indexes for table `register`
--
ALTER TABLE `register`
  ADD PRIMARY KEY (`user_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `note_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `register`
--
ALTER TABLE `register`
  MODIFY `user_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
