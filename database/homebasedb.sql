DROP TABLE IF EXISTS `horseToBehaviordb`;
DROP TABLE IF EXISTS `persontohorsedb`;
DROP TABLE IF EXISTS `trainerToHorsedb`;
DROP TABLE IF EXISTS `behaviordb`;
DROP TABLE IF EXISTS `horsedb`;
DROP TABLE IF EXISTS `notesdb`;
DROP TABLE IF EXISTS `persondb`;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 18, 2023 at 04:45 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `homebasedb`
--

-- --------------------------------------------------------

--
-- Table structure for table `behaviordb`
--

CREATE TABLE `behaviordb` (
  `title` varchar(100),
  `behaviorLevel` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO `behaviordb` (`title`, `behaviorLevel`) VALUES
('Walking', 'Green'),
('Saddling', 'Green'),
('Haltering','Green'),
('Tying','Green'),
('Walking with Lead', 'Green'),
('Recognize Name', 'Green'),
('Steering', 'Green'),
('Interact with Horses', 'Green'),
('Trot', 'Yellow'),
('Gallop', 'Yellow'),
('Basic Jumping', 'Yellow'),
('Trailering', 'Yellow'),
('Trail Riding', 'Yellow'),
('Side Stepping', 'Yellow'),
('Intense Steering/Handling', 'Red'),
('Show Jumping', 'Red'),
('Spanish Walk', 'Red'),
('Rear-up', 'Red'),
('Bowing','Red'),
('Pulling/Driving', 'Red');
-- --------------------------------------------------------

--
-- Table structure for table `horsedb`
--

CREATE TABLE `horsedb` (
  `horseID` int(11) NOT NULL,
  `horseName` text NOT NULL,
  `diet` text DEFAULT NULL,
  `color` text DEFAULT NULL,
  `breed` text DEFAULT NULL,
  `pastureNum` int(11) DEFAULT NULL,
  `colorRank` text DEFAULT NULL,
  `archive` tinyint(1) DEFAULT NULL,
  `archiveDate` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Table structure for table `horsetobehaviordb`
--

CREATE TABLE `horsetobehaviordb` (
  `horseID` int(11) NOT NULL,
  `title` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notesdb`
--

CREATE TABLE `notesdb` (
  `horseID` int(11) NOT NULL,
  `noteID` int(11) NOT NULL,
  `noteDate` date NOT NULL,
  `noteTimestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `note` text NOT NULL,
  `username` varchar(100) NOT NULL,
  `archive` tinyint(1) DEFAULT NULL,
  `archiveDate` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------

--
-- Table structure for table `persondb`
--

CREATE TABLE `persondb` (
  `firstName` text NOT NULL,
  `lastName` text NOT NULL,
  `fullName` varchar(50) NOT NULL,
  `phone` text NOT NULL,
  `email` text DEFAULT NULL,
  `username` varchar(100) NOT NULL,
  `pass` text DEFAULT NULL,
  `userType` text NOT NULL,
  `archive` tinyint(1) DEFAULT NULL,
  `archiveDate` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `persondb`
--

INSERT INTO `persondb` (`firstName`, `lastName`, `fullName`, `phone`, `email`, `username`, `pass`, `userType`, `archive`, `archiveDate`) VALUES
('Admin', 'Admin', 'Admin Admin', '111-111-1111', '', 'Admin', '$2y$10$RgviL.Wom0Cj8mZJUYm9ZuE29aFWZWmT9hwNAsEPOyOOMcqWuKc0K', 'Admin', 0, NULL),
('Viewer', 'Viewer', 'Viewer Viewer', '111-111-1111', '', 'Viewer', '$2y$10$6stDMx35Sl78wwgTibdhmurfPsCySM5tvFJRXm3hXOM3WflmKwCKS', 'Viewer', 0, NULL),
('Kim', 'Reidmr', 'Kim reidmr', '111-111-1234', '', 'Reidmr', '$2y$10$hB9kyYi0VEBvb8UrG5aGoeWI6XpRZs4sVdy4HTF4QO0U/Jw45rADC', 'Head Trainer', 0, '2023-04-17');

-- --------------------------------------------------------

--
-- Table structure for table `persontohorsedb`
--

CREATE TABLE `persontohorsedb` (
  `horseID` int(11) NOT NULL,
  `username` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


--
-- Indexes for dumped tables
--

--
-- Indexes for table `behaviordb`
--
ALTER TABLE `behaviordb` 
  ADD PRIMARY KEY (`title`, `behaviorLevel`);


--
-- Indexes for table `horsedb`
--
ALTER TABLE `horsedb`
  ADD PRIMARY KEY (`horseID`);

--
-- Indexes for table `horsetobehaviordb`
--
ALTER TABLE `horsetobehaviordb`
  ADD PRIMARY KEY (`horseID`,`title`),
  ADD KEY `title` (`title`);

--
-- Indexes for table `notesdb`
--
ALTER TABLE `notesdb`
  ADD PRIMARY KEY (`horseID`, `noteID`, `username`);


--
-- Indexes for table `persondb`
--
ALTER TABLE `persondb`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `persontohorsedb`
--
ALTER TABLE `persontohorsedb`
  ADD PRIMARY KEY (`username`,`horseID`),
  ADD KEY `horseID` (`horseID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `horsedb`
--
ALTER TABLE `horsedb`
  MODIFY `horseID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `horsetobehaviordb`
--
ALTER TABLE `horsetobehaviordb`
  ADD CONSTRAINT `horsetobehaviordb_ibfk_1` FOREIGN KEY (`horseID`) REFERENCES `horsedb` (`horseID`),
  ADD CONSTRAINT `horsetobehaviordb_ibfk_2` FOREIGN KEY (`title`) REFERENCES `behaviordb` (`title`);

--
-- Constraints for table `notesdb`
--
ALTER TABLE `notesdb`
  ADD CONSTRAINT `notesdb_ibfk_1` FOREIGN KEY (`horseID`) REFERENCES `horsedb` (`horseID`),
  ADD CONSTRAINT `notesdb_ibfk_2` FOREIGN KEY (`username`) REFERENCES `persondb` (`username`);

--
-- Constraints for table `persontohorsedb`
--
ALTER TABLE `persontohorsedb`
  ADD CONSTRAINT `persontohorsedb_ibfk_1` FOREIGN KEY (`username`) REFERENCES `persondb` (`username`),
  ADD CONSTRAINT `persontohorsedb_ibfk_2` FOREIGN KEY (`horseID`) REFERENCES `horsedb` (`horseID`);
COMMIT;

