-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 28, 2024 at 05:45 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `harken`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `booking_id` int(11) NOT NULL,
  `users_id` int(11) DEFAULT NULL,
  `service_id` int(11) DEFAULT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `contact` varchar(50) DEFAULT NULL,
  `date_of_appointment` date DEFAULT NULL,
  `time_of_appointment` time DEFAULT NULL,
  `status` enum('Pending','Approved','Cancelled') NOT NULL DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`booking_id`, `users_id`, `service_id`, `full_name`, `address`, `date_of_birth`, `age`, `contact`, `date_of_appointment`, `time_of_appointment`, `status`) VALUES
(1, 1, 3, 'Paula Arcoirez De Chavez', 'San Agustin, Ibaan, Batangas', '2004-10-01', 20, 'paula@gmail.com', '2024-11-05', '10:00:00', 'Cancelled'),
(2, 2, 4, 'Althea Arcoirez Plata', 'Batangas City', '2004-07-13', 20, 'thea@gmail.com', '2024-11-06', '11:00:00', 'Approved'),
(3, 3, 5, 'Dave John Lavarias', 'dada', '2019-01-29', 5, 'davejohnlavarias@gmail.com', '2024-11-07', '09:00:00', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `service_id` int(11) NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `service_desc` text DEFAULT NULL,
  `service_price` decimal(10,2) NOT NULL,
  `service_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service`
--

INSERT INTO `service` (`service_id`, `service_name`, `service_desc`, `service_price`, `service_image`) VALUES
(3, 'try1', '111', 123.00, NULL),
(4, 'try', 'try', 123.00, NULL),
(5, 'try', 'try', 123.00, NULL),
(6, 'try', 'try', 4111555.00, NULL),
(7, 'try', 'try', 11.00, NULL),
(8, 'try', 'try', 11.00, NULL),
(9, 'try', 'try', 11.00, NULL),
(10, 'pau', 'pau', 123.00, 'uploads/jabe.jpg'),
(11, 'marcus', 'marcus', 123.00, 'uploads/treasure-doyoung.jpg'),
(12, 'maan', 'maan', 123.00, 'uploads/jabe.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `users_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `age` int(3) NOT NULL,
  `birthday` date NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `account_type` int(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`users_id`, `first_name`, `middle_name`, `last_name`, `address`, `age`, `birthday`, `gender`, `email`, `password`, `account_type`, `created_at`) VALUES
(1, 'Paula', 'Arcoirez', 'De Chavez', 'San Agustin, Ibaan, Batangas', 20, '2024-10-01', 'Female', 'paula@gmail.com', '$2y$10$RKHnzZU/HjQM01JZVEAcUOU/mMgNLLCohbpzXGklnIzo97TwcMbR6', 2, '2024-10-19 17:00:08'),
(2, 'Althea', 'Arcoirez', 'Plata', 'Batangas City', 20, '2004-07-13', 'Female', 'thea@gmail.com', '$2y$10$ONpR8ysj4FQHIVJLXh8aJev5RGJYn.rrdCea55N0XLB1WXctWwTjK', 1, '2024-10-20 05:36:26'),
(3, 'dave', 'john', 'Lavarias', 'dada', 5, '2019-01-29', 'Male', 'davejohnlavarias@gmail.com', '$2y$10$c9wqUZCP0Y5eUyZKG8yuL.26oatZ77R1nTIV4UQ.4uxDXFhL9eNEW', 1, '2024-10-21 21:37:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `users_id` (`users_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`service_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`users_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `users_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`users_id`),
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `service` (`service_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
