-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 29, 2025 at 09:24 AM
-- Server version: 10.6.20-MariaDB-cll-lve
-- PHP Version: 8.3.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wandrbae_test`
--
CREATE DATABASE IF NOT EXISTS `wandrbae_test` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `wandrbae_test`;

-- --------------------------------------------------------

--
-- Table structure for table `accessibility_feature`
--

CREATE TABLE `accessibility_feature` (
  `accessibility_feature_id` int(11) NOT NULL,
  `feature_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `accessibility_feature`
--

INSERT INTO `accessibility_feature` (`accessibility_feature_id`, `feature_name`) VALUES
(3, 'Minimal Elevation Changes'),
(4, 'Paved Access Points'),
(7, 'Paved Paths'),
(5, 'Restrooms'),
(6, 'Water Station'),
(1, 'Wheelchair Accessible'),
(2, 'Wide Paths');

-- --------------------------------------------------------

--
-- Table structure for table `difficulty`
--

CREATE TABLE `difficulty` (
  `difficulty_id` int(11) NOT NULL,
  `difficulty_type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `difficulty`
--

INSERT INTO `difficulty` (`difficulty_id`, `difficulty_type`) VALUES
(3, 'Difficult'),
(1, 'Easy'),
(2, 'Moderate'),
(4, 'Very Difficult');

-- --------------------------------------------------------

--
-- Table structure for table `ride`
--

CREATE TABLE `ride` (
  `ride_id` int(11) NOT NULL,
  `ride_name` varchar(100) NOT NULL,
  `created_by` int(11) NOT NULL,
  `route_id` int(10) UNSIGNED NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `location_name` varchar(100) NOT NULL,
  `street_address` varchar(100) NOT NULL,
  `city` varchar(50) NOT NULL,
  `state` char(2) NOT NULL,
  `zip_code` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ride`
--

INSERT INTO `ride` (`ride_id`, `ride_name`, `created_by`, `route_id`, `start_time`, `end_time`, `location_name`, `street_address`, `city`, `state`, `zip_code`) VALUES
(1, 'Morning Lake Louise Loop', 1, 1, '2025-03-01 09:00:00', '2025-03-01 09:30:00', 'Lake Louise Park', '55 Lake Louise Dr', 'Weaverville', 'NC', '28787'),
(2, 'Saturday Blue Ridge Parkway Ride', 2, 2, '2025-03-08 10:00:00', '2025-03-08 11:30:00', 'Craggy Gardens Visitor Center', 'Blue Ridge Parkway Milepost 364.5', 'Asheville', 'NC', '28805'),
(3, 'Weekday Arboretum Cruise', 3, 3, '2025-03-10 16:00:00', '2025-03-10 17:15:00', 'NC Arboretum', '100 Frederick Law Olmsted Way', 'Asheville', 'NC', '28806'),
(5, 'Blue Ridge Rumble', 3, 2, '2025-03-20 12:00:00', '2025-03-20 13:00:00', 'Craggy Gardens Outpost', '364 blue ridge parkway', 'Black Mountain', 'NC', '28711'),
(6, 'Blue Ridge Rumble 2', 3, 2, '2025-03-20 13:00:00', '2025-03-20 14:00:00', 'Craggy Gardens Visitor Center', '364 blue ridge parkway', 'Black Mountain', 'NC', '28711'),
(7, 'TEST', 3, 2, '2025-03-20 13:00:00', '2025-03-20 14:00:00', 'Craggy Gardens Visitor Center', '364 blue ridge parkway', 'Black Mountain', 'NC', '28711');

--
-- Triggers `ride`
--
DELIMITER $$
CREATE TRIGGER `ride_state_uppercase` BEFORE INSERT ON `ride` FOR EACH ROW BEGIN
  SET NEW.state = UPPER(NEW.state);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `ride_participant`
--

CREATE TABLE `ride_participant` (
  `ride_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ride_participant`
--

INSERT INTO `ride_participant` (`ride_id`, `user_id`, `joined_at`) VALUES
(2, 1, '2025-03-07 14:15:30'),
(2, 3, '2025-03-06 19:23:45'),
(3, 2, '2025-03-10 00:45:12');

-- --------------------------------------------------------

--
-- Table structure for table `route`
--

CREATE TABLE `route` (
  `route_id` int(10) UNSIGNED NOT NULL,
  `route_name` varchar(100) NOT NULL,
  `created_by` int(11) NOT NULL,
  `distance_km` decimal(5,2) NOT NULL,
  `terrain_type_id` int(11) NOT NULL,
  `difficulty_id` int(11) NOT NULL,
  `bike_lane` tinyint(1) NOT NULL DEFAULT 0,
  `accessibility_feature_id` int(11) DEFAULT NULL,
  `average_speed_kph` decimal(4,2) NOT NULL,
  `estimated_duration` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `landmarks` text NOT NULL,
  `energy_saved_kwh` decimal(10,2) NOT NULL,
  `kinetic_energy_joules` decimal(5,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `route`
--

INSERT INTO `route` (`route_id`, `route_name`, `created_by`, `distance_km`, `terrain_type_id`, `difficulty_id`, `bike_lane`, `accessibility_feature_id`, `average_speed_kph`, `estimated_duration`, `landmarks`, `energy_saved_kwh`, `kinetic_energy_joules`, `created_at`) VALUES
(1, 'Lake Louise Park Loop', 1, 1.25, 1, 1, 1, 1, 8.00, '2025-02-27 01:13:35', 'Lake Louise, playground, picnic area, gazebo, walking bridge, wooded area, ducks', 0.75, 45.20, '2025-02-27 01:13:35'),
(2, 'Blue Ridge Parkway Overlook Loop', 2, 8.50, 1, 2, 1, 3, 15.20, '2025-03-05 23:00:00', 'Craggy Gardens Visitor Center, Craggy Pinnacle Trail, Parkway overlooks, mountain views, rhododendron gardens', 5.10, 120.75, '2025-03-01 20:45:22'),
(3, 'Bent Creek Greenway', 3, 6.20, 4, 1, 0, 7, 12.40, '2025-03-05 21:30:00', 'NC Arboretum entrance, Bent Creek, French Broad River access, picnic area, hardwood forest, creek crossings', 3.72, 95.40, '2025-03-02 14:30:15');

-- --------------------------------------------------------

--
-- Table structure for table `route_message`
--

CREATE TABLE `route_message` (
  `message_id` int(11) NOT NULL,
  `route_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` varchar(140) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `route_message`
--

INSERT INTO `route_message` (`message_id`, `route_id`, `user_id`, `message`, `created_at`) VALUES
(1, 2, 2, 'Beautiful views at the overlook! Perfect for a clear day.', '2025-03-02 21:20:45'),
(2, 3, 3, 'Watch for muddy sections near the creek after rain.', '2025-03-03 15:15:30'),
(3, 2, 3, 'Looking forward to Saturday\'s ride! Anyone bringing extra water?', '2025-03-06 19:25:10');

-- --------------------------------------------------------

--
-- Table structure for table `route_recording`
--

CREATE TABLE `route_recording` (
  `recording_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `route_id` int(10) UNSIGNED NOT NULL,
  `ride_id` int(11) NOT NULL,
  `started_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `completed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `route_recording`
--

INSERT INTO `route_recording` (`recording_id`, `user_id`, `route_id`, `ride_id`, `started_at`, `completed_at`) VALUES
(1, 1, 1, 1, '2025-03-01 14:00:00', '2025-03-01 14:30:00'),
(2, 2, 2, 2, '2025-03-08 15:00:00', '2025-03-08 16:28:45'),
(3, 3, 3, 3, '2025-03-10 20:00:00', '2025-03-10 21:12:30');

-- --------------------------------------------------------

--
-- Table structure for table `terrain_type`
--

CREATE TABLE `terrain_type` (
  `terrain_type_id` int(11) NOT NULL,
  `terrain_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `terrain_type`
--

INSERT INTO `terrain_type` (`terrain_type_id`, `terrain_name`) VALUES
(3, 'Dirt'),
(2, 'Gravel'),
(4, 'Mixed'),
(1, 'Paved'),
(5, 'Rocky');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `user_role_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_member` tinyint(1) NOT NULL DEFAULT 1,
  `hashed_password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_role_id`, `username`, `first_name`, `last_name`, `email`, `password`, `is_member`, `hashed_password`) VALUES
(1, 1, 'wandrbaedemo', 'Demo', 'User', 'demo@wandrbae.com', '$2y$10$examplehashedpasswordfordemopurposesonly', 1, ''),
(2, 1, 'mountainbiker', 'Sarah', 'Johnson', 'sarah@example.com', '$2y$10$securehashedpasswordexample12345678', 1, ''),
(3, 1, 'ashevillecyclist', 'Mike', 'Thompson', 'mike@example.com', '$2y$10$anothersecurehashedpasswordexample', 1, ''),
(4, 1, 'Test', 'Test', 'Test', 'g@gmail.com', '', 1, ''),
(16, 1, 'OnTheFly', 'Trisha', 'Hollifield', 'trishahollifield@gmail.com', '', 1, '$2y$10$JwJb.JM9EvPCkfYGY6SJiufP3fOQm7bPpzl2cSPLLH1YCxEB3nLUe'),
(18, 1, 'BikerBaeKev', 'Kevin', 'Franklin', 'bikebae@baemail.com', '', 1, '$2y$10$cmQRy.TDIWGjrUvui7Ph/.j9wQu9pMAAM659kIdrtbeqtuEy/9QfK'),
(21, 1, 'Frank', 'Frank', 'James', 'fjames1977@gmail.com', '', 1, '$2y$10$SAMB74jHYRR039hzm4C6u.CMeljPmlukef5Shc17quHoU02JO9gu6'),
(22, 2, 'Hankhill', 'Hank', 'Hill', 'hank@gmail.com', '', 1, '$2y$10$WQPRojVgbH7/rPXbe4FcXuFX7jAn2b/KfKwfzpRqGRTtaBHf/x4iq'),
(23, 1, 'Bobbyhill', 'Bobby', 'Hill', 'Bobbyhill@gmail.com', '', 1, '$2y$10$GeC/HpdhDwHHBz7D613jCOLVBp/b/zm2A4uxQ3zWuHKQI07Dq.7Vu'),
(24, 1, 'Greenbean', 'Greenbean', 'Jones', 'Greenbean@yahoo.com', '', 1, '$2y$10$GruinrKrkZXjtlJsCNKtMOQKKfYaNiUOIahm6Zmj.MAiPwelbS/zi');

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `role_id` int(1) NOT NULL,
  `role_name` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`role_id`, `role_name`) VALUES
(1, 'Member'),
(2, 'Admin'),
(3, 'Superadmin');

-- --------------------------------------------------------

--
-- Table structure for table `waypoint`
--

CREATE TABLE `waypoint` (
  `waypoint_id` int(11) NOT NULL,
  `recording_id` int(11) NOT NULL,
  `latitude` decimal(9,6) NOT NULL,
  `longitude` decimal(9,6) NOT NULL,
  `location` point NOT NULL,
  `sequence_number` int(11) NOT NULL,
  `recorded_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `waypoint`
--

INSERT INTO `waypoint` (`waypoint_id`, `recording_id`, `latitude`, `longitude`, `location`, `sequence_number`, `recorded_at`) VALUES
(1, 2, 35.700500, -82.375800, 0x0000000001010000008b6ce7fba9d941408e75711b0d9854c0, 1, '2025-03-08 15:00:00'),
(2, 2, 35.701200, -82.376400, 0x00000000010100000044faedebc0d94140b98d06f0169854c0, 2, '2025-03-08 15:02:15'),
(3, 2, 35.702300, -82.377100, 0x0000000001010000008bfd65f7e4d9414095d40968229854c0, 3, '2025-03-08 15:04:30'),
(4, 2, 35.703400, -82.378200, 0x000000000101000000d200de0209da414039d6c56d349854c0, 4, '2025-03-08 15:06:45'),
(5, 2, 35.704100, -82.379600, 0x0000000001010000008a8ee4f21fda4140f163cc5d4b9854c0, 5, '2025-03-08 15:09:00'),
(6, 2, 35.705200, -82.380100, 0x000000000101000000d1915cfe43da41406a4df38e539854c0, 6, '2025-03-08 15:11:15'),
(7, 2, 35.706300, -82.379500, 0x0000000001010000001895d40968da41403f355eba499854c0, 7, '2025-03-08 15:13:30'),
(8, 2, 35.707100, -82.378300, 0x0000000001010000003480b74082da4140ea043411369854c0, 8, '2025-03-08 15:15:45'),
(9, 2, 35.707800, -82.377200, 0x000000000101000000ed0dbe3099da41404703780b249854c0, 9, '2025-03-08 15:18:00'),
(10, 2, 35.708200, -82.376100, 0x0000000001010000007b832f4ca6da4140a301bc05129854c0, 10, '2025-03-08 15:20:15'),
(11, 2, 35.707600, -82.375200, 0x000000000101000000265305a392da4140645ddc46039854c0, 11, '2025-03-08 15:22:30'),
(12, 2, 35.706700, -82.374500, 0x000000000101000000a60a462575da41408716d9cef79754c0, 12, '2025-03-08 15:24:45'),
(13, 2, 35.705600, -82.374100, 0x0000000001010000005f07ce1951da4140c05b2041f19754c0, 13, '2025-03-08 15:27:00'),
(14, 2, 35.704500, -82.373800, 0x0000000001010000001904560e2dda4140abcfd556ec9754c0, 14, '2025-03-08 15:29:15'),
(15, 2, 35.703300, -82.373900, 0x0000000001010000006ea301bc05da41405dfe43faed9754c0, 15, '2025-03-08 15:31:30'),
(16, 2, 35.702200, -82.374300, 0x00000000010100000027a089b0e1d9414024b9fc87f49754c0, 16, '2025-03-08 15:33:45'),
(17, 2, 35.701400, -82.374800, 0x0000000001010000000bb5a679c7d941409ca223b9fc9754c0, 17, '2025-03-08 15:36:00'),
(18, 2, 35.700500, -82.375800, 0x0000000001010000008b6ce7fba9d941408e75711b0d9854c0, 18, '2025-03-08 15:38:15'),
(19, 3, 35.470100, -82.599800, 0x000000000101000000c0ec9e3c2cbc414003098a1f63a654c0, 1, '2025-03-10 20:00:00'),
(20, 3, 35.470800, -82.599200, 0x000000000101000000787aa52c43bc4140d8f0f44a59a654c0, 2, '2025-03-10 20:02:30'),
(21, 3, 35.471600, -82.598500, 0x000000000101000000956588635dbc4140fca9f1d24da654c0, 3, '2025-03-10 20:05:00'),
(22, 3, 35.472300, -82.597700, 0x0000000001010000004df38e5374bc41406e3480b740a654c0, 4, '2025-03-10 20:07:30'),
(23, 3, 35.473100, -82.597100, 0x0000000001010000006ade718a8ebc4140431cebe236a654c0, 5, '2025-03-10 20:10:00'),
(24, 3, 35.474000, -82.596600, 0x000000000101000000e9263108acbc4140ca32c4b12ea654c0, 6, '2025-03-10 20:12:30'),
(25, 3, 35.474700, -82.595800, 0x000000000101000000a2b437f8c2bc41403cbd529621a654c0, 7, '2025-03-10 20:15:00'),
(26, 3, 35.475600, -82.595100, 0x00000000010100000022fdf675e0bc414060764f1e16a654c0, 8, '2025-03-10 20:17:30'),
(27, 3, 35.476400, -82.594300, 0x0000000001010000003ee8d9acfabc4140d200de0209a654c0, 9, '2025-03-10 20:20:00'),
(28, 3, 35.477200, -82.593500, 0x0000000001010000005bd3bce314bd4140448b6ce7fba554c0, 10, '2025-03-10 20:22:30'),
(29, 3, 35.478100, -82.593900, 0x000000000101000000da1b7c6132bd41400b46257502a654c0, 11, '2025-03-10 20:25:00'),
(30, 3, 35.478900, -82.594700, 0x000000000101000000f7065f984cbd414099bb96900fa654c0, 12, '2025-03-10 20:27:30'),
(31, 3, 35.479400, -82.595600, 0x000000000101000000e8d9acfa5cbd4140d95f764f1ea654c0, 13, '2025-03-10 20:30:00'),
(32, 3, 35.479000, -82.596500, 0x0000000001010000005a643bdf4fbd41401904560e2da654c0, 14, '2025-03-10 20:32:30'),
(33, 3, 35.478200, -82.597300, 0x0000000001010000003e7958a835bd4140a779c7293aa654c0, 15, '2025-03-10 20:35:00'),
(34, 3, 35.477300, -82.598100, 0x000000000101000000be30992a18bd414035ef384547a654c0, 16, '2025-03-10 20:37:30'),
(35, 3, 35.476500, -82.598900, 0x000000000101000000a245b6f3fdbc4140c364aa6054a654c0, 17, '2025-03-10 20:40:00'),
(36, 3, 35.475700, -82.599500, 0x000000000101000000865ad3bce3bc4140ee7c3f355ea654c0, 18, '2025-03-10 20:42:30'),
(37, 3, 35.474800, -82.600200, 0x0000000001010000000612143fc6bc4140cac342ad69a654c0, 19, '2025-03-10 20:45:00'),
(38, 3, 35.473900, -82.600800, 0x00000000010100000086c954c1a8bc4140f5dbd78173a654c0, 20, '2025-03-10 20:47:30'),
(39, 3, 35.473000, -82.601300, 0x000000000101000000068195438bbc41406dc5feb27ba654c0, 21, '2025-03-10 20:50:00'),
(40, 3, 35.472100, -82.601800, 0x0000000001010000008638d6c56dbc4140e6ae25e483a654c0, 22, '2025-03-10 20:52:30'),
(41, 3, 35.471200, -82.601200, 0x00000000010100000007f0164850bc4140bc96900f7aa654c0, 23, '2025-03-10 20:55:00'),
(42, 3, 35.470600, -82.600500, 0x000000000101000000b1bfec9e3cbc4140df4f8d976ea654c0, 24, '2025-03-10 20:57:30'),
(43, 3, 35.470100, -82.599800, 0x000000000101000000c0ec9e3c2cbc414003098a1f63a654c0, 25, '2025-03-10 21:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accessibility_feature`
--
ALTER TABLE `accessibility_feature`
  ADD PRIMARY KEY (`accessibility_feature_id`),
  ADD UNIQUE KEY `feature_name` (`feature_name`);

--
-- Indexes for table `difficulty`
--
ALTER TABLE `difficulty`
  ADD PRIMARY KEY (`difficulty_id`),
  ADD UNIQUE KEY `difficulty_type` (`difficulty_type`);

--
-- Indexes for table `ride`
--
ALTER TABLE `ride`
  ADD PRIMARY KEY (`ride_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `route_id` (`route_id`);

--
-- Indexes for table `ride_participant`
--
ALTER TABLE `ride_participant`
  ADD PRIMARY KEY (`ride_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `route`
--
ALTER TABLE `route`
  ADD PRIMARY KEY (`route_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `terrain_type_id` (`terrain_type_id`),
  ADD KEY `difficulty_id` (`difficulty_id`),
  ADD KEY `accessibility_feature_id` (`accessibility_feature_id`);

--
-- Indexes for table `route_message`
--
ALTER TABLE `route_message`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `route_id` (`route_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `route_recording`
--
ALTER TABLE `route_recording`
  ADD PRIMARY KEY (`recording_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `route_id` (`route_id`),
  ADD KEY `ride_id` (`ride_id`);

--
-- Indexes for table `terrain_type`
--
ALTER TABLE `terrain_type`
  ADD PRIMARY KEY (`terrain_type_id`),
  ADD UNIQUE KEY `terrain_name` (`terrain_name`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `user_role_id` (`user_role_id`);

--
-- Indexes for table `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `waypoint`
--
ALTER TABLE `waypoint`
  ADD PRIMARY KEY (`waypoint_id`),
  ADD UNIQUE KEY `recording_id` (`recording_id`,`sequence_number`),
  ADD SPATIAL KEY `location` (`location`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ride`
--
ALTER TABLE `ride`
  MODIFY `ride_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `route`
--
ALTER TABLE `route`
  MODIFY `route_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `route_message`
--
ALTER TABLE `route_message`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `route_recording`
--
ALTER TABLE `route_recording`
  MODIFY `recording_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `waypoint`
--
ALTER TABLE `waypoint`
  MODIFY `waypoint_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ride`
--
ALTER TABLE `ride`
  ADD CONSTRAINT `ride_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `ride_ibfk_2` FOREIGN KEY (`route_id`) REFERENCES `route` (`route_id`);

--
-- Constraints for table `ride_participant`
--
ALTER TABLE `ride_participant`
  ADD CONSTRAINT `ride_participant_ibfk_1` FOREIGN KEY (`ride_id`) REFERENCES `ride` (`ride_id`),
  ADD CONSTRAINT `ride_participant_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `route`
--
ALTER TABLE `route`
  ADD CONSTRAINT `route_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `route_ibfk_2` FOREIGN KEY (`terrain_type_id`) REFERENCES `terrain_type` (`terrain_type_id`),
  ADD CONSTRAINT `route_ibfk_3` FOREIGN KEY (`difficulty_id`) REFERENCES `difficulty` (`difficulty_id`),
  ADD CONSTRAINT `route_ibfk_4` FOREIGN KEY (`accessibility_feature_id`) REFERENCES `accessibility_feature` (`accessibility_feature_id`);

--
-- Constraints for table `route_message`
--
ALTER TABLE `route_message`
  ADD CONSTRAINT `route_message_ibfk_1` FOREIGN KEY (`route_id`) REFERENCES `route` (`route_id`),
  ADD CONSTRAINT `route_message_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `route_recording`
--
ALTER TABLE `route_recording`
  ADD CONSTRAINT `route_recording_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `route_recording_ibfk_2` FOREIGN KEY (`route_id`) REFERENCES `route` (`route_id`),
  ADD CONSTRAINT `route_recording_ibfk_3` FOREIGN KEY (`ride_id`) REFERENCES `ride` (`ride_id`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`user_role_id`) REFERENCES `user_role` (`role_id`);

--
-- Constraints for table `waypoint`
--
ALTER TABLE `waypoint`
  ADD CONSTRAINT `waypoint_ibfk_1` FOREIGN KEY (`recording_id`) REFERENCES `route_recording` (`recording_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
