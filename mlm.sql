-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 25, 2017 at 02:49 PM
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
  `Branch_ID` int(11) NOT NULL,
  `Name` varchar(150) NOT NULL,
  `ImmediateUpline_ID` int(11) DEFAULT NULL,
  `Status` tinyint(4) NOT NULL,
  `PhoneNumber` varchar(30) NOT NULL,
  `Password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `agent`
--

INSERT INTO `agent` (`Agent_ID`, `Branch_ID`, `Name`, `ImmediateUpline_ID`, `Status`, `PhoneNumber`, `Password`) VALUES
(1, 1, 'John', NULL, 1, '0123456789', 'cheese'),
(2, 1, 'Jane', 1, 1, '0025134', 'meat'),
(3, 1, 'Dude', NULL, 1, '01234', 'FFF');

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
(1, 2);

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
(1, NULL, NULL, 1, 'First', ''),
(2, NULL, NULL, 1, 'Second', ''),
(4, NULL, NULL, 1, 'Denver', '');

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
(1, '2015-07-04', 1000000, 'Burma'),
(2, '2017-10-21', 999999999, 'Dubai');

-- --------------------------------------------------------

--
-- Table structure for table `paypercentages`
--

CREATE TABLE `paypercentages` (
  `JobName` varchar(70) NOT NULL,
  `Percentage` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `paypercentages`
--

INSERT INTO `paypercentages` (`JobName`, `Percentage`) VALUES
('President', 6),
('Vice President', 4);

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
  ADD KEY `fk_Agent_ImmediateUpline_ID` (`ImmediateUpline_ID`),
  ADD KEY `fk_Agent_Branch_ID` (`Branch_ID`);

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
  ADD PRIMARY KEY (`Agent_ID`,`Closing_ID`),
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
  ADD PRIMARY KEY (`JobName`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `agent`
--
ALTER TABLE `agent`
  MODIFY `Agent_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `branch`
--
ALTER TABLE `branch`
  MODIFY `branch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `closing`
--
ALTER TABLE `closing`
  MODIFY `closing_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `agent`
--
ALTER TABLE `agent`
  ADD CONSTRAINT `fk_Agent_Branch_ID` FOREIGN KEY (`Branch_ID`) REFERENCES `branch` (`branch_id`),
  ADD CONSTRAINT `fk_Agent_ImmediateUpline_ID` FOREIGN KEY (`ImmediateUpline_ID`) REFERENCES `agent` (`Agent_ID`);

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
  ADD CONSTRAINT `fk_Agent_Has_Closing_Closing_ID` FOREIGN KEY (`Closing_ID`) REFERENCES `closing` (`Closing_ID`);

--
-- Constraints for table `branch`
--
ALTER TABLE `branch`
  ADD CONSTRAINT `fk_Branch_President_ID` FOREIGN KEY (`President_ID`) REFERENCES `agent` (`Agent_ID`),
  ADD CONSTRAINT `fk_Branch_VicePresident_ID` FOREIGN KEY (`VicePresident_ID`) REFERENCES `agent` (`Agent_ID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
