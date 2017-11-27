-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 27, 2017 at 03:24 AM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mlm`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `Username` varchar(50) NOT NULL,
  `Password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`Username`, `Password`) VALUES
('Neo', 'Matrix'),
('xXxAgentKillerxXx', 'headshot');

-- --------------------------------------------------------

--
-- Table structure for table `agent`
--

CREATE TABLE `agent` (
  `Agent_ID` int(11) NOT NULL,
  `Name` varchar(150) NOT NULL,
  `ImmediateUpline_ID` int(11) DEFAULT NULL,
  `Status` tinyint(4) NOT NULL,
  `PhoneNumber` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `agent`
--

INSERT INTO `agent` (`Agent_ID`, `Name`, `ImmediateUpline_ID`, `Status`, `PhoneNumber`) VALUES
(0, 'COMPANY', NULL, 1, '0000000000'),
(1, 'John', NULL, 1, '0123456789'),
(2, 'Jane', 1, 1, '0025134'),
(3, 'Dude', NULL, 1, '01234'),
(4, 'Tir', 2, 1, '11223344'),
(6, 'Doe', 2, 0, '999'),
(7, 'AA', 2, 0, '123123'),
(8, 'Doe', 1, 1, '122'),
(9, 'Fayr', 2, 1, '222');

-- --------------------------------------------------------

--
-- Table structure for table `agent_branch_employment`
--

CREATE TABLE `agent_branch_employment` (
  `Agent_ID` int(11) NOT NULL,
  `Branch_ID` int(11) NOT NULL,
  `Started` date NOT NULL,
  `End` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `agent_branch_employment`
--

INSERT INTO `agent_branch_employment` (`Agent_ID`, `Branch_ID`, `Started`, `End`) VALUES
(0, 1, '2000-01-01', NULL),
(1, 1, '2000-01-01', NULL),
(2, 1, '2000-01-01', NULL),
(3, 1, '2000-01-01', NULL),
(4, 1, '2000-01-01', NULL),
(6, 2, '2017-11-20', '2017-11-28'),
(7, 1, '2017-11-27', '2017-11-28'),
(8, 1, '2017-11-27', NULL),
(9, 2, '2017-11-27', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `agent_has_downline`
--

CREATE TABLE `agent_has_downline` (
  `Agent_ID` int(11) NOT NULL,
  `Downline_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `agent_has_downline`
--

INSERT INTO `agent_has_downline` (`Agent_ID`, `Downline_ID`) VALUES
(1, 2),
(1, 4),
(1, 6),
(1, 7),
(1, 8),
(1, 9),
(2, 4),
(2, 6),
(2, 7),
(2, 9);

-- --------------------------------------------------------

--
-- Table structure for table `agent_involved_in_closing`
--

CREATE TABLE `agent_involved_in_closing` (
  `Agent_ID` int(11) NOT NULL,
  `Closing_ID` int(11) NOT NULL,
  `earning` double NOT NULL,
  `workedAs` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `agent_involved_in_closing`
--

INSERT INTO `agent_involved_in_closing` (`Agent_ID`, `Closing_ID`, `earning`, `workedAs`) VALUES
(0, 24, 6000, 2),
(0, 24, 4000, 3),
(0, 24, 7000, 4),
(0, 24, 2000, 5),
(0, 24, 1000, 6),
(0, 24, 6000, 8),
(0, 24, 4000, 9),
(0, 24, 7000, 10),
(0, 24, 2000, 11),
(0, 24, 1000, 12),
(0, 24, 6000, 14),
(0, 24, 4000, 15),
(0, 24, 7000, 16),
(0, 24, 2000, 17),
(0, 24, 1000, 18),
(0, 24, 6000, 20),
(0, 24, 4000, 21),
(0, 24, 7000, 22),
(0, 24, 2000, 23),
(0, 24, 1000, 24),
(1, 24, 100000, 1),
(2, 24, 100000, 13),
(8, 24, 100000, 7),
(9, 24, 100000, 19);

-- --------------------------------------------------------

--
-- Table structure for table `branch`
--

CREATE TABLE `branch` (
  `branch_id` int(11) NOT NULL,
  `President_ID` int(11) DEFAULT NULL,
  `VicePresident_ID` int(11) DEFAULT NULL,
  `status` tinyint(4) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `branch`
--

INSERT INTO `branch` (`branch_id`, `President_ID`, `VicePresident_ID`, `status`, `Name`, `address`) VALUES
(1, 1, NULL, 1, 'First', 'AAAAAAA Street'),
(2, 2, NULL, 1, 'Second', 'Dukuh Kupang'),
(4, 3, 6, 1, 'Denver', 'Citraland'),
(5, NULL, NULL, 0, 'Fair', 'Grounds'),
(6, NULL, NULL, 0, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `closing`
--

CREATE TABLE `closing` (
  `closing_ID` int(11) NOT NULL,
  `Date` date NOT NULL,
  `Price` double NOT NULL,
  `Address` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `closing`
--

INSERT INTO `closing` (`closing_ID`, `Date`, `Price`, `Address`) VALUES
(24, '2017-11-27', 400000, 'Quadruple');

-- --------------------------------------------------------

--
-- Table structure for table `paypercentages`
--

CREATE TABLE `paypercentages` (
  `PPID` int(11) NOT NULL,
  `JobName` varchar(70) NOT NULL,
  `Percentage` double NOT NULL,
  `ValidityStart` date NOT NULL,
  `ValidityEnd` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `paypercentages`
--

INSERT INTO `paypercentages` (`PPID`, `JobName`, `Percentage`, `ValidityStart`, `ValidityEnd`) VALUES
(1, 'President', 6, '2000-01-01', NULL),
(2, 'VicePresident', 4, '2000-01-01', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`Username`);

--
-- Indexes for table `agent`
--
ALTER TABLE `agent`
  ADD PRIMARY KEY (`Agent_ID`),
  ADD KEY `fk_Agent_ImmediateUpline_ID` (`ImmediateUpline_ID`);

--
-- Indexes for table `agent_branch_employment`
--
ALTER TABLE `agent_branch_employment`
  ADD KEY `Has_or_had_an_agent` (`Branch_ID`),
  ADD KEY `agent_branch_employment_ibfk_1` (`Agent_ID`);

--
-- Indexes for table `agent_has_downline`
--
ALTER TABLE `agent_has_downline`
  ADD PRIMARY KEY (`Agent_ID`,`Downline_ID`),
  ADD KEY `fk_Agent_Has_Downline_Downline_ID` (`Downline_ID`);

--
-- Indexes for table `agent_involved_in_closing`
--
ALTER TABLE `agent_involved_in_closing`
  ADD PRIMARY KEY (`Agent_ID`,`Closing_ID`,`workedAs`),
  ADD KEY `fk_Agent_Has_Closing_Closing_ID` (`Closing_ID`);

--
-- Indexes for table `branch`
--
ALTER TABLE `branch`
  ADD PRIMARY KEY (`branch_id`),
  ADD KEY `fk_Branch_President_ID` (`President_ID`),
  ADD KEY `fk_Branch_VicePresident_ID` (`VicePresident_ID`);

--
-- Indexes for table `closing`
--
ALTER TABLE `closing`
  ADD PRIMARY KEY (`closing_ID`);

--
-- Indexes for table `paypercentages`
--
ALTER TABLE `paypercentages`
  ADD PRIMARY KEY (`PPID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `agent`
--
ALTER TABLE `agent`
  MODIFY `Agent_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `branch`
--
ALTER TABLE `branch`
  MODIFY `branch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `closing`
--
ALTER TABLE `closing`
  MODIFY `closing_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `paypercentages`
--
ALTER TABLE `paypercentages`
  MODIFY `PPID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `agent`
--
ALTER TABLE `agent`
  ADD CONSTRAINT `fk_Agent_ImmediateUpline_ID` FOREIGN KEY (`ImmediateUpline_ID`) REFERENCES `agent` (`Agent_ID`);

--
-- Constraints for table `agent_branch_employment`
--
ALTER TABLE `agent_branch_employment`
  ADD CONSTRAINT `Has_or_had_an_agent` FOREIGN KEY (`Branch_ID`) REFERENCES `branch` (`branch_id`),
  ADD CONSTRAINT `agent_branch_employment_ibfk_1` FOREIGN KEY (`Agent_ID`) REFERENCES `agent` (`Agent_ID`);

--
-- Constraints for table `agent_has_downline`
--
ALTER TABLE `agent_has_downline`
  ADD CONSTRAINT `fk_Agent_Has_Downline_Agent_ID` FOREIGN KEY (`Agent_ID`) REFERENCES `agent` (`Agent_ID`),
  ADD CONSTRAINT `fk_Agent_Has_Downline_Downline_ID` FOREIGN KEY (`Downline_ID`) REFERENCES `agent` (`Agent_ID`);

--
-- Constraints for table `agent_involved_in_closing`
--
ALTER TABLE `agent_involved_in_closing`
  ADD CONSTRAINT `fk_Agent_Has_Closing_Agent_ID` FOREIGN KEY (`Agent_ID`) REFERENCES `agent` (`Agent_ID`),
  ADD CONSTRAINT `fk_Agent_Has_Closing_Closing_ID` FOREIGN KEY (`Closing_ID`) REFERENCES `closing` (`closing_ID`) ON DELETE CASCADE;

--
-- Constraints for table `branch`
--
ALTER TABLE `branch`
  ADD CONSTRAINT `fk_Branch_President_ID` FOREIGN KEY (`President_ID`) REFERENCES `agent` (`Agent_ID`),
  ADD CONSTRAINT `fk_Branch_VicePresident_ID` FOREIGN KEY (`VicePresident_ID`) REFERENCES `agent` (`Agent_ID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
