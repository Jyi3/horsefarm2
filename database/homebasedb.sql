
DROP TABLE IF EXISTS dbapplicantscreenings;
DROP TABLE IF EXISTS dbbehavior;
DROP TABLE IF EXISTS dbdates;
DROP TABLE IF EXISTS dblog;
DROP TABLE IF EXISTS dbmasterschedule;
DROP TABLE IF EXISTS dbpersons;
DROP TABLE IF EXISTS dbscl;
DROP TABLE IF EXISTS dbshifts;
DROP TABLE IF EXISTS dbweeks;
DROP TABLE IF EXISTS notesDB;
DROP TABLE IF EXISTS archivePersonDB;
DROP TABLE IF EXISTS archiveHorseDB;
DROP TABLE IF EXISTS trainerToHorseDB;
DROP TABLE IF EXISTS horseToBehaviorDB;
DROP TABLE IF EXISTS persontohorsedb;
DROP TABLE IF EXISTS behaviorDB;
DROP TABLE IF EXISTS horseDB;
DROP TABLE IF EXISTS trainerDB;
DROP TABLE IF EXISTS personDB;

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


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

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

--
-- Dumping data for table `horsedb`
--

INSERT INTO `horsedb` (`horseID`, `horseName`, `color`, `breed`, `pastureNum`, `colorRank`, `archive`, `archiveDate`) VALUES
(1, 'Thunder', 'Brown', 'Quarter Horse', 1, 'Green', 1, '2023-04-18'),
(2, 'Midnight', 'Black', 'Arabian', 1, 'Yellow', 0, ''),
(3, 'Silver', 'Gray', 'Andalusian', 2, 'Green', 0, ''),
(4, 'Blaze', 'Chestnut', 'Thoroughbred', 2, 'Yellow', 0, ''),
(5, 'Snowflake', 'White', 'Friesian', 3, 'Green', 0, ''),
(6, 'Cocoa', 'Bay', 'Morgan', 3, 'Yellow', 0, ''),
(7, 'Sunny', 'Palomino', 'Paint', 4, 'Green', 1, '2023-04-18'),
(8, 'Stormy', 'Dapple Gray', 'Lipizzaner', 4, 'Red', 0, '');

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

--
-- Dumping data for table `notesdb`
--

INSERT INTO `notesdb` (`horseID`, `noteID`, `noteDate`, `noteTimestamp`, `note`, `username`, `archive`, `archiveDate`) VALUES
(1, 2, '2023-04-15', '2023-04-15 13:06:09', 'testasdf', 'testHT', NULL, NULL),
(1, 1, '2023-04-15', '2023-04-17 21:16:22', 'test 3 5 3 5', 'testHT', 0, '2023-04-17'),
(1, 3, '2023-04-17', '2023-04-17 06:55:24', 'test', 'testHT', NULL, NULL),
(1, 4, '2023-04-17', '2023-04-17 06:55:59', 'tester', 'testHT', NULL, NULL),
(4, 1, '2023-04-17', '2023-04-17 21:16:22', 'test 3 5 3 5', 'testHT', 0, '2023-04-17');

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
('testHT', 'testHT', 'testHT testHT', '111-111-1234', '', 'testHT', '$2y$10$hB9kyYi0VEBvb8UrG5aGoeWI6XpRZs4sVdy4HTF4QO0U/Jw45rADC', 'Head Trainer', 0, '2023-04-17'),
('testR', 'testR', 'testR testR', '111-111-1234', '', 'testR', '$2y$10$VCUfh1QSjSKvxnTr/sfzAuuDpJQctVyEV1Rtoz4W5f/Dpmka/35nW', 'Recruit', 1, '2023-04-18'),
('testT', 'testT', 'testT testT', '111-111-1234', '', 'testT', '$2y$10$6fCtYwAnnNh3iuG7wG7fEOkvdRZnOXm6KCQdFlrlX5qocFgTwt4Em', 'Trainer', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `persontohorsedb`
--

CREATE TABLE `persontohorsedb` (
  `horseID` int(11) NOT NULL,
  `username` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `persontohorsedb`
--

INSERT INTO `persontohorsedb` (`horseID`, `username`) VALUES
(4, 'testHT'),
(6, 'testHT'),
(8, 'testHT'),
(8, 'testR');

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
  ADD PRIMARY KEY (`horseID`,`noteDate`,`noteTimestamp`,`note`(255),`username`),
  ADD KEY `username` (`username`);

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

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


