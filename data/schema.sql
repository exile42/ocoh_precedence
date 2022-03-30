-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: sika.swcp.com
-- Generation Time: Mar 29, 2022 at 07:24 PM
-- Server version: 10.3.27-MariaDB-0+deb10u1-log
-- PHP Version: 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ocoh`
--
CREATE DATABASE IF NOT EXISTS `ocoh` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `ocoh`;

-- --------------------------------------------------------

--
-- Table structure for table `ActivityLog`
--

CREATE TABLE `ActivityLog` (
  `ID` int(11) NOT NULL,
  `WhenDone` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `UserID` int(11) NOT NULL DEFAULT 0,
  `Activity` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `IPAddress` varchar(25) COLLATE latin1_general_ci NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Groups`
--

CREATE TABLE `Groups` (
  `GroupID` int(11) NOT NULL,
  `KingdomID` smallint(6) NOT NULL DEFAULT 17,
  `ParentGroupID` int(11) DEFAULT NULL,
  `Name` varchar(60) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `Designator` varchar(20) COLLATE latin1_general_ci NOT NULL,
  `BaronyOrder` tinyint(4) DEFAULT NULL,
  `Active` tinyint(4) NOT NULL DEFAULT 1,
  `Incipient` tinyint(4) NOT NULL DEFAULT 0,
  `Notes` varchar(150) COLLATE latin1_general_ci DEFAULT NULL,
  `RegName` tinyint(4) NOT NULL DEFAULT 0,
  `RegDevice` tinyint(4) NOT NULL DEFAULT 0,
  `Blazon` varchar(200) COLLATE latin1_general_ci DEFAULT NULL,
  `Filename` varchar(30) COLLATE latin1_general_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `Kingdoms`
--

CREATE TABLE `Kingdoms` (
  `KingdomID` smallint(6) NOT NULL,
  `Name` varchar(60) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `CreationOrder` smallint(6) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `UserAccess`
--

CREATE TABLE `UserAccess` (
  `UserAccessID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL DEFAULT 0,
  `PrincipalHerald` tinyint(4) NOT NULL DEFAULT 0,
  `ExternalSubs` tinyint(4) NOT NULL DEFAULT 0,
  `InternalSubs` tinyint(4) NOT NULL DEFAULT 0,
  `SubmissionPrep` tinyint(4) NOT NULL DEFAULT 0,
  `Wimble` tinyint(4) NOT NULL DEFAULT 0,
  `OPMinion` tinyint(4) NOT NULL DEFAULT 0,
  `OPView` tinyint(4) NOT NULL DEFAULT 0,
  `Wharrow` tinyint(4) NOT NULL DEFAULT 0,
  `RollofArmsMinion` tinyint(4) NOT NULL DEFAULT 0,
  `Ceremonies` tinyint(4) NOT NULL DEFAULT 0,
  `Education` tinyint(4) NOT NULL DEFAULT 0,
  `Field` tinyint(4) NOT NULL DEFAULT 0,
  `Display` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci COMMENT='Defining what Users have access to';

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `UserID` int(10) UNSIGNED NOT NULL,
  `Username` varchar(15) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `Password` varchar(20) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `Email` varchar(40) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `LastLogin` datetime DEFAULT NULL,
  `LastLoginIP` varchar(25) COLLATE latin1_general_ci DEFAULT NULL,
  `CreatedOn` date NOT NULL DEFAULT '0000-00-00',
  `CreatedBy` int(11) NOT NULL DEFAULT 0,
  `UserTypeID` int(11) NOT NULL DEFAULT 3
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci COMMENT='Website logins & email aliases';

-- --------------------------------------------------------

--
-- Table structure for table `UserType`
--

CREATE TABLE `UserType` (
  `ID` int(11) NOT NULL,
  `UserType` varchar(50) COLLATE latin1_general_ci NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wAliases`
--

CREATE TABLE `wAliases` (
  `AliasID` smallint(6) NOT NULL,
  `PersonID` int(11) NOT NULL DEFAULT 0,
  `Alias` varchar(80) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `SearchAlias` varchar(80) COLLATE latin1_general_ci NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wAwards`
--

CREATE TABLE `wAwards` (
  `AwardID` int(11) NOT NULL,
  `Name` varchar(80) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `RankID` smallint(6) NOT NULL DEFAULT 34,
  `Rank` int(11) NOT NULL DEFAULT 999999999,
  `Initials` varchar(10) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `Stylization` varchar(80) COLLATE latin1_general_ci DEFAULT NULL,
  `Closed` tinyint(4) NOT NULL DEFAULT 0,
  `GivenFor` varchar(150) COLLATE latin1_general_ci DEFAULT NULL,
  `ShortFor` varchar(40) COLLATE latin1_general_ci DEFAULT NULL COMMENT 'Short form of GivenFor',
  `GiverType` enum('B','R','C') COLLATE latin1_general_ci NOT NULL DEFAULT 'B',
  `GroupID` int(11) DEFAULT NULL,
  `KingdomID` smallint(6) NOT NULL DEFAULT 0,
  `Blazon` varchar(200) COLLATE latin1_general_ci DEFAULT NULL,
  `Charter` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `Notes` varchar(150) COLLATE latin1_general_ci DEFAULT NULL,
  `Filename` varchar(60) COLLATE latin1_general_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wAwardsGiven`
--

CREATE TABLE `wAwardsGiven` (
  `AwardsGivenID` int(11) NOT NULL,
  `PersonID` int(11) NOT NULL DEFAULT 0,
  `AwardID` int(11) NOT NULL DEFAULT 0,
  `ReignID` smallint(6) DEFAULT 0,
  `Date` datetime NOT NULL DEFAULT '9999-12-31 00:00:00',
  `SortRank` int(11) DEFAULT NULL,
  `Resigned` tinyint(4) NOT NULL DEFAULT 0,
  `Premier` tinyint(4) NOT NULL DEFAULT 0,
  `AwardedBy` varchar(50) COLLATE latin1_general_ci DEFAULT NULL,
  `Verified` varchar(20) COLLATE latin1_general_ci DEFAULT NULL,
  `Visible` tinyint(4) NOT NULL DEFAULT 1,
  `ScrollGiven` tinyint(4) NOT NULL DEFAULT 0,
  `Scribe` varchar(60) COLLATE latin1_general_ci DEFAULT NULL,
  `Reason` varchar(30) COLLATE latin1_general_ci DEFAULT NULL,
  `DateAdded` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `Notes` text COLLATE latin1_general_ci DEFAULT NULL,
  `LastUpdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `LastUpdateID` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wAwardsResigned`
--

CREATE TABLE `wAwardsResigned` (
  `PersonID` int(11) NOT NULL DEFAULT 0,
  `AwardID` int(11) NOT NULL DEFAULT 0,
  `Date` date NOT NULL DEFAULT '0000-00-00',
  `Notes` varchar(100) COLLATE latin1_general_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wHeraldry`
--

CREATE TABLE `wHeraldry` (
  `HeraldryID` int(11) NOT NULL,
  `PersonID` int(11) NOT NULL DEFAULT 0,
  `Type` enum('Device','Badge') COLLATE latin1_general_ci NOT NULL DEFAULT 'Device',
  `Blazon` text COLLATE latin1_general_ci DEFAULT NULL,
  `JointOwnerID` int(11) DEFAULT NULL COMMENT 'PersonID of secondary owner',
  `Association` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `RegDeviceDate` varchar(7) COLLATE latin1_general_ci DEFAULT NULL,
  `Filename` varchar(200) COLLATE latin1_general_ci DEFAULT NULL,
  `FilenameLine` varchar(200) COLLATE latin1_general_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wPeople`
--

CREATE TABLE `wPeople` (
  `PersonID` int(11) NOT NULL,
  `SCAName` varchar(80) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `SearchName` varchar(80) COLLATE latin1_general_ci DEFAULT NULL,
  `RegName` tinyint(4) NOT NULL DEFAULT 0,
  `RegNameDate` varchar(7) COLLATE latin1_general_ci DEFAULT NULL,
  `RegDevice` tinyint(4) NOT NULL DEFAULT 0,
  `GroupID` int(11) DEFAULT NULL,
  `KingdomID` smallint(6) NOT NULL DEFAULT 17,
  `Active` tinyint(4) NOT NULL DEFAULT 1,
  `Deceased` tinyint(4) NOT NULL DEFAULT 0,
  `Pronouns` varchar(10) COLLATE latin1_general_ci DEFAULT NULL COMMENT 'free-text field for pronoun display in Wimble/Wharrow',
  `ModernName` varchar(150) COLLATE latin1_general_ci DEFAULT NULL,
  `MemberNumber` int(11) DEFAULT NULL,
  `DateAdded` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `LastUpdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `LastUpdateID` int(11) NOT NULL DEFAULT 0,
  `PersonalRank` int(10) UNSIGNED NOT NULL DEFAULT 4294967295,
  `SortRank` int(10) UNSIGNED NOT NULL DEFAULT 4294967295
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Table structure for table `wRanks`
--

CREATE TABLE `wRanks` (
  `RankID` int(11) NOT NULL,
  `Rank` smallint(6) NOT NULL DEFAULT 0,
  `Description` varchar(150) COLLATE latin1_general_ci NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wReigns`
--

CREATE TABLE `wReigns` (
  `ReignID` smallint(6) NOT NULL,
  `GroupID` int(11) NOT NULL DEFAULT 96,
  `ReignOrder` smallint(6) DEFAULT NULL,
  `Ruler` varchar(80) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `Consort` varchar(80) COLLATE latin1_general_ci DEFAULT NULL,
  `RPersonID` int(11) DEFAULT NULL,
  `CPersonID` int(11) DEFAULT NULL,
  `RUseName` varchar(60) COLLATE latin1_general_ci DEFAULT NULL,
  `CUseName` varchar(60) COLLATE latin1_general_ci DEFAULT NULL,
  `RUseTitle` varchar(20) COLLATE latin1_general_ci NOT NULL DEFAULT 'King',
  `CUseTitle` varchar(20) COLLATE latin1_general_ci DEFAULT 'Queen',
  `Start` date NOT NULL DEFAULT '9999-12-31',
  `End` date DEFAULT '9999-12-31',
  `Notes` text COLLATE latin1_general_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ActivityLog`
--
ALTER TABLE `ActivityLog`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `Groups`
--
ALTER TABLE `Groups`
  ADD PRIMARY KEY (`GroupID`),
  ADD KEY `ParentGroupID` (`ParentGroupID`);

--
-- Indexes for table `Kingdoms`
--
ALTER TABLE `Kingdoms`
  ADD PRIMARY KEY (`KingdomID`);

--
-- Indexes for table `UserAccess`
--
ALTER TABLE `UserAccess`
  ADD PRIMARY KEY (`UserAccessID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`UserID`,`Username`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- Indexes for table `UserType`
--
ALTER TABLE `UserType`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `wAliases`
--
ALTER TABLE `wAliases`
  ADD PRIMARY KEY (`AliasID`),
  ADD KEY `FK_Alias_Person` (`PersonID`);

--
-- Indexes for table `wAwards`
--
ALTER TABLE `wAwards`
  ADD PRIMARY KEY (`AwardID`),
  ADD KEY `FK_A_Rank` (`RankID`),
  ADD KEY `FK_A_Locale` (`GroupID`,`KingdomID`) USING BTREE;

--
-- Indexes for table `wAwardsGiven`
--
ALTER TABLE `wAwardsGiven`
  ADD PRIMARY KEY (`AwardsGivenID`),
  ADD KEY `FK_AG_Person` (`PersonID`),
  ADD KEY `FK_AG_Award` (`AwardID`),
  ADD KEY `FK_AG_Reign` (`ReignID`,`AwardedBy`);

--
-- Indexes for table `wAwardsResigned`
--
ALTER TABLE `wAwardsResigned`
  ADD KEY `PersonID` (`PersonID`,`AwardID`,`Date`),
  ADD KEY `FK_Resign_Award` (`AwardID`);

--
-- Indexes for table `wHeraldry`
--
ALTER TABLE `wHeraldry`
  ADD PRIMARY KEY (`HeraldryID`),
  ADD KEY `FK_H_Person` (`PersonID`);

--
-- Indexes for table `wPeople`
--
ALTER TABLE `wPeople`
  ADD PRIMARY KEY (`PersonID`),
  ADD KEY `FK_P_Locale` (`KingdomID`,`GroupID`),
  ADD KEY `IDX_Names` (`SCAName`,`ModernName`);

--
-- Indexes for table `wRanks`
--
ALTER TABLE `wRanks`
  ADD PRIMARY KEY (`RankID`);

--
-- Indexes for table `wReigns`
--
ALTER TABLE `wReigns`
  ADD PRIMARY KEY (`ReignID`),
  ADD KEY `FK_Reign_People` (`RPersonID`,`CPersonID`),
  ADD KEY `FK_Reign_Group` (`GroupID`),
  ADD KEY `IDX_UseName` (`RUseName`,`CUseName`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ActivityLog`
--
ALTER TABLE `ActivityLog`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Groups`
--
ALTER TABLE `Groups`
  MODIFY `GroupID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Kingdoms`
--
ALTER TABLE `Kingdoms`
  MODIFY `KingdomID` smallint(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `UserAccess`
--
ALTER TABLE `UserAccess`
  MODIFY `UserAccessID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `UserID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `UserType`
--
ALTER TABLE `UserType`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wAliases`
--
ALTER TABLE `wAliases`
  MODIFY `AliasID` smallint(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wAwards`
--
ALTER TABLE `wAwards`
  MODIFY `AwardID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wAwardsGiven`
--
ALTER TABLE `wAwardsGiven`
  MODIFY `AwardsGivenID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wHeraldry`
--
ALTER TABLE `wHeraldry`
  MODIFY `HeraldryID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wPeople`
--
ALTER TABLE `wPeople`
  MODIFY `PersonID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wRanks`
--
ALTER TABLE `wRanks`
  MODIFY `RankID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wReigns`
--
ALTER TABLE `wReigns`
  MODIFY `ReignID` smallint(6) NOT NULL AUTO_INCREMENT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
