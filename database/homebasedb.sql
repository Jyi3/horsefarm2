-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jan 31, 2022 at 08:37 PM
-- Server version: 5.7.34
-- PHP Version: 7.4.21

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


/*homebasedb.sql: SQL file for importing into phpMyAdmin.*/
/*Contains all of the tables and foreign key restraints necessary for CVHR training.*/
/*Name can be changed once website is fully functional.*/

/*Drop CVHR tables*/
DROP TABLE IF EXISTS notesDB;
DROP TABLE IF EXISTS personToHorseDB;
DROP TABLE IF EXISTS horseToBehaviorDB;
DROP TABLE IF EXISTS behaviorDB;
DROP TABLE IF EXISTS personDB;
DROP TABLE IF EXISTS horseDB; 


/*Create CVHR tables*/

CREATE TABLE horseDB (
  horseID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  horseName text NOT NULL,
  color text,
  breed text,
  pastureNum int,
  colorRank text,
  archive boolean,
  archiveDate text
);

CREATE TABLE behaviorDB (
  title varchar(100) primary key NOT NULL,
  behaviorLevel text
);

CREATE TABLE personDB (
  firstName text NOT NULL,
  lastName text NOT NULL, 
  fullName varchar(50) NOT NULL,
  phone text NOT NULL,
  email text,
  username varchar(100) NOT NULL,
  pass text,
  userType text NOT NULL,
  archive boolean,
  archiveDate text,
  PRIMARY KEY (username)
);


CREATE TABLE personToHorseDB (
  horseID INT NOT NULL,
  username varchar(100) NOT NULL,
  FOREIGN KEY (username) REFERENCES personDB(username),
  FOREIGN KEY (horseID) REFERENCES horseDB(horseID),
  PRIMARY KEY (username, horseID)
);

CREATE TABLE horseToBehaviorDB (
  horseID INT NOT NULL,
  title varchar(100) NOT NULL,
  FOREIGN KEY (horseID) REFERENCES horseDB(horseID),
  FOREIGN KEY (title) REFERENCES behaviorDB(title),
  PRIMARY KEY (horseID, title)
);


CREATE TABLE notesDB (
  horseID INT NOT NULL,
  noteID INT NOT NULL,
  noteDate date,
  noteTimestamp timestamp,
  note text,
  username varchar(100) NOT NULL,
  primary key (horseID, noteDate, noteTimestamp, note(255), username),
  FOREIGN KEY (horseID) REFERENCES horseDB(horseID),
  FOREIGN KEY (username) REFERENCES personDB(username)
);



