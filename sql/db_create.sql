CREATE DATABASE  IF NOT EXISTS `pixie` /*!40100 DEFAULT CHARACTER SET big5 */;
USE `pixie`;
-- MySQL dump 10.13  Distrib 5.5.41, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: pixie
-- ------------------------------------------------------
-- Server version	5.5.41-0+wheezy1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `AdoptionStatus`
--

DROP TABLE IF EXISTS `AdoptionStatus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AdoptionStatus` (
  `adoptionStatusID` char(1) NOT NULL,
  `adoptionStatus` varchar(20) NOT NULL,
  PRIMARY KEY (`adoptionStatusID`)
) ENGINE=InnoDB DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Animal`
--

DROP TABLE IF EXISTS `Animal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Animal` (
  `animalID` int(11) NOT NULL AUTO_INCREMENT,
  `animalName` varchar(100) NOT NULL,
  `species` char(1) DEFAULT NULL,
  `breed` varchar(50) DEFAULT NULL,
  `markings` varchar(45) DEFAULT NULL,
  `gender` char(1) DEFAULT NULL,
  `estBirthdate` date DEFAULT NULL,
  `isFixed` bit(1) DEFAULT b'0',
  `activityLevel` int(11) DEFAULT NULL,
  `note` longtext,
  `microchipNumber` varchar(25) DEFAULT NULL,
  `microchipTypeID` int(11) DEFAULT NULL,
  `dateImplanted` date DEFAULT NULL,
  `url` varchar(45) DEFAULT NULL,
  `kids` char(1) DEFAULT 'U',
  `dogs` char(1) DEFAULT 'U',
  `cats` char(1) DEFAULT 'U',
  `adoptionStatusID` char(1) DEFAULT NULL,
  `personalityID` char(1) DEFAULT NULL,
  `isHypo` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`animalID`),
  KEY `Animal_ibfk_1` (`microchipTypeID`),
  CONSTRAINT `Animal_ibfk_1` FOREIGN KEY (`microchipTypeID`) REFERENCES `MicrochipType` (`microchipTypeID`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1669 DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary table structure for view `AnimalInfo`
--

DROP TABLE IF EXISTS `AnimalInfo`;
/*!50001 DROP VIEW IF EXISTS `AnimalInfo`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `AnimalInfo` (
  `animalID` tinyint NOT NULL,
  `animalName` tinyint NOT NULL,
  `breed` tinyint NOT NULL,
  `markings` tinyint NOT NULL,
  `activityLevel` tinyint NOT NULL,
  `species` tinyint NOT NULL,
  `gender` tinyint NOT NULL,
  `isFixed` tinyint NOT NULL,
  `isHypo` tinyint NOT NULL,
  `kids` tinyint NOT NULL,
  `cats` tinyint NOT NULL,
  `dogs` tinyint NOT NULL,
  `estBirthdate` tinyint NOT NULL,
  `age` tinyint NOT NULL,
  `note` tinyint NOT NULL,
  `microchipNumber` tinyint NOT NULL,
  `dateImplanted` tinyint NOT NULL,
  `microchipName` tinyint NOT NULL,
  `personality` tinyint NOT NULL,
  `adoptionStatus` tinyint NOT NULL,
  `url` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `AnimalSurgeries`
--

DROP TABLE IF EXISTS `AnimalSurgeries`;
/*!50001 DROP VIEW IF EXISTS `AnimalSurgeries`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `AnimalSurgeries` (
  `surgeryTypeID` tinyint NOT NULL,
  `animalID` tinyint NOT NULL,
  `surgeryDate` tinyint NOT NULL,
  `note` tinyint NOT NULL,
  `personID` tinyint NOT NULL,
  `surgeryType` tinyint NOT NULL,
  `animalName` tinyint NOT NULL,
  `lastName` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `AnimalSurgery`
--

DROP TABLE IF EXISTS `AnimalSurgery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `AnimalSurgery` (
  `surgeryTypeID` int(11) NOT NULL,
  `animalID` int(11) NOT NULL,
  `surgeryDate` date NOT NULL DEFAULT '0000-00-00',
  `note` varchar(150) DEFAULT NULL,
  `personID` int(11) DEFAULT NULL,
  PRIMARY KEY (`surgeryTypeID`,`animalID`,`surgeryDate`),
  KEY `surgeryTypeID` (`surgeryTypeID`),
  KEY `animalID` (`animalID`),
  KEY `personID` (`personID`),
  CONSTRAINT `AnimalSurgery_ibfk_2` FOREIGN KEY (`surgeryTypeID`) REFERENCES `SurgeryType` (`surgeryTypeID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `AnimalSurgery_ibfk_3` FOREIGN KEY (`animalID`) REFERENCES `Animal` (`animalID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `AnimalSurgery_ibfk_4` FOREIGN KEY (`personID`) REFERENCES `Person` (`personID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Application`
--

DROP TABLE IF EXISTS `Application`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Application` (
  `applicationID` int(11) NOT NULL AUTO_INCREMENT,
  `personID` int(11) NOT NULL,
  `applicationDate` date NOT NULL,
  `species` char(1) DEFAULT NULL,
  `gender` char(1) DEFAULT NULL,
  `minAge` int(11) DEFAULT '0',
  `maxAge` int(11) DEFAULT '99',
  `minWeight` int(11) DEFAULT '0',
  `maxWeight` int(11) DEFAULT '600',
  `breed` varchar(150) DEFAULT NULL,
  `minActivityLevel` int(11) DEFAULT '0',
  `maxActivityLevel` int(11) DEFAULT '99',
  `numKids` int(11) DEFAULT '0',
  `numDogs` int(11) DEFAULT '0',
  `numCats` int(11) DEFAULT '0',
  `personalityID` char(1) DEFAULT NULL,
  `note` longtext,
  `needHypo` tinyint(4) NOT NULL DEFAULT '0',
  `closed` tinyint(4) NOT NULL DEFAULT '0',
  `rank` int(11) DEFAULT '3',
  PRIMARY KEY (`applicationID`),
  KEY `personID` (`personID`),
  CONSTRAINT `Application_ibfk_1` FOREIGN KEY (`personID`) REFERENCES `Person` (`personID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=934 DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary table structure for view `CountMeds`
--

DROP TABLE IF EXISTS `CountMeds`;
/*!50001 DROP VIEW IF EXISTS `CountMeds`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `CountMeds` (
  `animalID` tinyint NOT NULL,
  `medicationID` tinyint NOT NULL,
  `medicationName` tinyint NOT NULL,
  `countMeds` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `CurrentPositions`
--

DROP TABLE IF EXISTS `CurrentPositions`;
/*!50001 DROP VIEW IF EXISTS `CurrentPositions`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `CurrentPositions` (
  `positionTypeID` tinyint NOT NULL,
  `personID` tinyint NOT NULL,
  `note` tinyint NOT NULL,
  `startDate` tinyint NOT NULL,
  `positionName` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `CurrentTransfer`
--

DROP TABLE IF EXISTS `CurrentTransfer`;
/*!50001 DROP VIEW IF EXISTS `CurrentTransfer`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `CurrentTransfer` (
  `animalID` tinyint NOT NULL,
  `personID` tinyint NOT NULL,
  `transferDate` tinyint NOT NULL,
  `transferTypeID` tinyint NOT NULL,
  `fee` tinyint NOT NULL,
  `note` tinyint NOT NULL,
  `animalName` tinyint NOT NULL,
  `microchipNumber` tinyint NOT NULL,
  `adoptionStatusID` tinyint NOT NULL,
  `CurrentPerson` tinyint NOT NULL,
  `transferName` tinyint NOT NULL,
  `pixieResponsible` tinyint NOT NULL,
  `speciesName` tinyint NOT NULL,
  `species` tinyint NOT NULL,
  `genderName` tinyint NOT NULL,
  `gender` tinyint NOT NULL,
  `Fixed` tinyint NOT NULL,
  `isFixed` tinyint NOT NULL,
  `estBirthdate` tinyint NOT NULL,
  `Adoptable` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `DogSize`
--

DROP TABLE IF EXISTS `DogSize`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `DogSize` (
  `dogSizeName` varchar(20) NOT NULL,
  `minSize` int(11) NOT NULL DEFAULT '0',
  `maxSize` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`minSize`,`maxSize`)
) ENGINE=InnoDB DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `File`
--

DROP TABLE IF EXISTS `File`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `File` (
  `fileID` int(11) NOT NULL AUTO_INCREMENT,
  `fileName` varchar(45) NOT NULL,
  `fileURL` varchar(45) NOT NULL,
  `dateUploaded` date DEFAULT NULL,
  `note` varchar(45) DEFAULT NULL,
  `animalID` int(11) DEFAULT NULL,
  `personID` int(11) DEFAULT NULL,
  PRIMARY KEY (`fileID`),
  KEY `File_ibfk_1` (`animalID`),
  KEY `File_ibfk_2` (`personID`),
  CONSTRAINT `File_ibfk_1` FOREIGN KEY (`animalID`) REFERENCES `Animal` (`animalID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `File_ibfk_2` FOREIGN KEY (`personID`) REFERENCES `Person` (`personID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Medication`
--

DROP TABLE IF EXISTS `Medication`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Medication` (
  `medicationID` int(11) NOT NULL AUTO_INCREMENT,
  `medicationName` varchar(20) DEFAULT NULL,
  `notes` varchar(50) DEFAULT NULL,
  `isVaccination` binary(1) DEFAULT '0',
  `species` char(1) DEFAULT NULL,
  PRIMARY KEY (`medicationID`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary table structure for view `Medications`
--

DROP TABLE IF EXISTS `Medications`;
/*!50001 DROP VIEW IF EXISTS `Medications`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `Medications` (
  `animalID` tinyint NOT NULL,
  `startdate` tinyint NOT NULL,
  `medicationname` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `MicrochipType`
--

DROP TABLE IF EXISTS `MicrochipType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `MicrochipType` (
  `microchipTypeID` int(11) NOT NULL AUTO_INCREMENT,
  `microchipName` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`microchipTypeID`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Person`
--

DROP TABLE IF EXISTS `Person`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Person` (
  `personID` int(11) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(100) NOT NULL,
  `lastName` varchar(100) NOT NULL,
  `secondary` varchar(45) DEFAULT NULL,
  `address1` varchar(50) DEFAULT NULL,
  `address2` varchar(20) DEFAULT NULL,
  `city` varchar(20) DEFAULT NULL,
  `state` varchar(20) DEFAULT NULL,
  `zip` varchar(20) DEFAULT NULL,
  `homePhone` varchar(15) DEFAULT NULL,
  `cellPhone` varchar(15) DEFAULT NULL,
  `workPhone` varchar(15) DEFAULT NULL,
  `faxPhone` varchar(15) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `note` varchar(150) DEFAULT NULL,
  `isOrg` bit(1) DEFAULT b'0',
  PRIMARY KEY (`personID`)
) ENGINE=InnoDB AUTO_INCREMENT=1685 DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `PersonPosition`
--

DROP TABLE IF EXISTS `PersonPosition`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PersonPosition` (
  `positionTypeID` int(11) NOT NULL DEFAULT '0',
  `personID` int(11) NOT NULL DEFAULT '0',
  `note` varchar(150) DEFAULT NULL,
  `startDate` date DEFAULT NULL,
  PRIMARY KEY (`positionTypeID`,`personID`),
  KEY `personID` (`personID`),
  CONSTRAINT `PersonPosition_ibfk_2` FOREIGN KEY (`positionTypeID`) REFERENCES `PositionType` (`positionTypeID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Personality`
--

DROP TABLE IF EXISTS `Personality`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Personality` (
  `personalityID` char(1) NOT NULL,
  `personality` varchar(20) NOT NULL,
  PRIMARY KEY (`personalityID`)
) ENGINE=InnoDB DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `PositionType`
--

DROP TABLE IF EXISTS `PositionType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `PositionType` (
  `positionTypeID` int(11) NOT NULL AUTO_INCREMENT,
  `positionName` varchar(20) NOT NULL,
  PRIMARY KEY (`positionTypeID`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Prescription`
--

DROP TABLE IF EXISTS `Prescription`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Prescription` (
  `medicationID` int(11) NOT NULL,
  `animalID` int(11) NOT NULL,
  `startDate` date NOT NULL,
  `lot` varchar(45) DEFAULT NULL,
  `expDate` varchar(45) DEFAULT NULL,
  `note` varchar(50) DEFAULT NULL,
  `nextDose` date DEFAULT NULL,
  PRIMARY KEY (`medicationID`,`animalID`,`startDate`),
  KEY `animalID` (`animalID`),
  CONSTRAINT `Prescription_ibfk_1` FOREIGN KEY (`animalID`) REFERENCES `Animal` (`animalID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `Prescription_ibfk_2` FOREIGN KEY (`medicationID`) REFERENCES `Medication` (`medicationID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `SurgeryType`
--

DROP TABLE IF EXISTS `SurgeryType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SurgeryType` (
  `surgeryTypeID` int(11) NOT NULL AUTO_INCREMENT,
  `surgeryType` varchar(20) NOT NULL,
  PRIMARY KEY (`surgeryTypeID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Test`
--

DROP TABLE IF EXISTS `Test`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Test` (
  `testDate` date NOT NULL,
  `testResult` varchar(50) DEFAULT NULL,
  `note` varchar(50) DEFAULT NULL,
  `testTypeID` int(11) NOT NULL,
  `animalID` int(11) NOT NULL,
  PRIMARY KEY (`testTypeID`,`animalID`,`testDate`),
  KEY `animalID` (`animalID`),
  CONSTRAINT `Test_ibfk_1` FOREIGN KEY (`testTypeID`) REFERENCES `TestType` (`testTypeID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `Test_ibfk_2` FOREIGN KEY (`animalID`) REFERENCES `Animal` (`animalID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `TestType`
--

DROP TABLE IF EXISTS `TestType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TestType` (
  `testTypeID` int(11) NOT NULL AUTO_INCREMENT,
  `testName` varchar(20) DEFAULT NULL,
  `species` char(1) DEFAULT NULL,
  `desiredResult` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`testTypeID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary table structure for view `TestView`
--

DROP TABLE IF EXISTS `TestView`;
/*!50001 DROP VIEW IF EXISTS `TestView`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `TestView` (
  `testDate` tinyint NOT NULL,
  `testResult` tinyint NOT NULL,
  `note` tinyint NOT NULL,
  `testTypeID` tinyint NOT NULL,
  `animalID` tinyint NOT NULL,
  `testName` tinyint NOT NULL,
  `desiredResult` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `Transfer`
--

DROP TABLE IF EXISTS `Transfer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Transfer` (
  `animalID` int(11) NOT NULL,
  `personID` int(11) NOT NULL,
  `transferDate` date NOT NULL,
  `transferTypeID` int(11) DEFAULT NULL,
  `fee` decimal(5,2) DEFAULT '0.00',
  `note` longtext,
  PRIMARY KEY (`animalID`,`personID`,`transferDate`),
  KEY `personID` (`personID`),
  KEY `Placement_ibfk_3` (`transferTypeID`)
) ENGINE=InnoDB DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary table structure for view `TransferHistory`
--

DROP TABLE IF EXISTS `TransferHistory`;
/*!50001 DROP VIEW IF EXISTS `TransferHistory`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `TransferHistory` (
  `animalName` tinyint NOT NULL,
  `transferName` tinyint NOT NULL,
  `animalID` tinyint NOT NULL,
  `personID` tinyint NOT NULL,
  `transferDate` tinyint NOT NULL,
  `transferTypeID` tinyint NOT NULL,
  `adoptable` tinyint NOT NULL,
  `fee` tinyint NOT NULL,
  `note` tinyint NOT NULL,
  `Name` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `TransferType`
--

DROP TABLE IF EXISTS `TransferType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TransferType` (
  `transferTypeID` int(11) NOT NULL AUTO_INCREMENT,
  `transferName` varchar(20) NOT NULL,
  `adoptable` char(1) DEFAULT NULL,
  `pixieResponsible` char(1) DEFAULT NULL,
  `isOrg` char(1) DEFAULT NULL,
  PRIMARY KEY (`transferTypeID`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `Users`
--

DROP TABLE IF EXISTS `Users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Users` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` char(128) NOT NULL,
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `VitalSign`
--

DROP TABLE IF EXISTS `VitalSign`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `VitalSign` (
  `vitalDateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `vitalValue` decimal(10,1) DEFAULT NULL,
  `note` varchar(50) DEFAULT NULL,
  `vitalSignTypeID` int(11) NOT NULL,
  `animalID` int(11) NOT NULL,
  PRIMARY KEY (`vitalSignTypeID`,`animalID`,`vitalDateTime`),
  KEY `animalID` (`animalID`),
  CONSTRAINT `VitalSign_ibfk_1` FOREIGN KEY (`vitalSignTypeID`) REFERENCES `VitalSignType` (`vitalSignTypeID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `VitalSign_ibfk_2` FOREIGN KEY (`animalID`) REFERENCES `Animal` (`animalID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `VitalSignType`
--

DROP TABLE IF EXISTS `VitalSignType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `VitalSignType` (
  `vitalSignTypeID` int(11) NOT NULL,
  `vitalSignTypeName` varchar(20) DEFAULT NULL,
  `vitalSignShortName` varchar(45) DEFAULT NULL,
  `species` char(1) DEFAULT NULL,
  `low` float(4,1) DEFAULT NULL,
  `hi` float(4,1) DEFAULT NULL,
  `units` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`vitalSignTypeID`)
) ENGINE=InnoDB DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary table structure for view `innerCurrentTransfer`
--

DROP TABLE IF EXISTS `innerCurrentTransfer`;
/*!50001 DROP VIEW IF EXISTS `innerCurrentTransfer`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `innerCurrentTransfer` (
  `animalID` tinyint NOT NULL,
  `transferDate` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Dumping routines for database 'pixie'
--
/*!50003 DROP PROCEDURE IF EXISTS `Adoptable` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `Adoptable`()
BEGIN
-- Get a list of animals up for adoption
SELECT 
  a.animalName, a.species, a.breed, a.gender, 
  concat(per.firstname, ' ', per.lastname) as CurrentOwner, per.personID
FROM 
  Animal a, 
  Placement pl, 
  Person per,
  ( 
	SELECT a.animalID, MAX(pl.AquiredDate) as AquiredDate
	FROM Animal a, Placement pl 
	WHERE pl.animalID = a.animalID 
	GROUP BY a.animalID
  ) maxDate
WHERE 
  pl.animalId = a.animalId AND
  per.personID = pl.personID AND 
  a.animalID = maxDate.animalID AND pl.AquiredDate = maxDate.AquiredDate
  AND pl.isPermenant = False;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `AnimalList` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `AnimalList`(
	IN inAnimalName varchar(20), 
	IN inSpecies char, 
	IN inGender char,
	IN status varchar(10)
)
BEGIN
-- Get a list of animals up for adoption
	SELECT 
		a.animalID, a.animalName,a.breed, 
	case (a.species) 
			WHEN 'D' THEN "Dog" 
			WHEN 'C' THEN "Cat" 
		end as species,
	case (a.gender) 
			WHEN 'F' THEN "Female" 
			WHEN 'M' THEN "Male" 
		end as gender,

 if(`per`.`firstName` is NULL,
            `per`.`lastName`,
            concat(`per`.`firstName`, ' ', `per`.`lastName`)
		) as CurrentPerson, 
		per.personID,
		DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(estBirthdate)), '%Y')+0 AS age,
		case (pl.isPermenant) 
			WHEN 1 THEN "No" 
			WHEN 0 THEN "Yes" 
		end as Adoptable,
		a.estBirthdate
	FROM 
		Animal a
		INNER JOIN Placement pl on pl.animalID = a.animalID
		INNER JOIN Person per on per.personID = pl.personID
		INNER JOIN 
		( 
			SELECT a.animalID, MAX(pl.AquiredDate) as AquiredDate
			FROM Animal a, Placement pl 
			WHERE pl.animalID = a.animalID 
			GROUP BY a.animalID
		) maxDate on a.animalID = maxDate.animalID AND pl.AquiredDate = maxDate.AquiredDate		
WHERE 
	(a.animalName like concat('%', inAnimalName, '%') OR (inAnimalName like '')) AND
	(a.species = inSpecies OR inSpecies like '') AND 
	(a.gender = inGender  OR inGender like '') 
-- 	AND pl.isPermenant = False;
;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `AnimalMeds` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `AnimalMeds`(IN inAnimalID int)
BEGIN

SELECT 
  a.animalname, 
  a.species, 
  a.estbirthdate, 
  p.startdate, 
  m.medicationname
FROM 
  Animal a
  INNER JOIN Prescription p on p.animalID = a.animalID
  INNER JOIN Medication m on p.medicationID = m.medicationID
WHERE a.animalID = inAnimalID
ORDER by p.startdate;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `CurrentVaccinations` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `CurrentVaccinations`(IN inAnimalID int)
BEGIN
	SELECT a.animalID, a.animalName,
		Bordatella.lastDate as Bordatella, 
		Rabies.lastDate as Rabies, 
		Flea.lastDate as Flea, Pyrantel.
		lastDate as Pyrantel
	FROM Animal a
	LEFT JOIN 
	(
		SELECT a.animalID, max(p.startdate) as lastDate
		FROM 
		  Animal a
		  INNER JOIN Prescription p on p.animalID = a.animalID
		  INNER JOIN Medication m on p.medicationID = m.medicationID and m.medicationName = 'DHPP'
		GROUP BY a.animalID
		) DHPP on DHPP.animalID = a.animalID
	LEFT JOIN 
	(
		SELECT a.animalID, max(p.startdate) as lastDate
		FROM 
		  Animal a
		  INNER JOIN Prescription p on p.animalID = a.animalID
		  INNER JOIN Medication m on p.medicationID = m.medicationID and m.medicationName = 'Bordatella'
		GROUP BY a.animalID
	) Bordatella on a.animalID = DHPP.animalID
	LEFT JOIN (
		SELECT a.animalID, max(p.startdate) as lastDate
		FROM 
		  Animal a
		  INNER JOIN Prescription p on p.animalID = a.animalID
		  INNER JOIN Medication m on p.medicationID = m.medicationID and m.medicationName = 'Rabies'
		GROUP BY a.animalID
	) Rabies on a.animalID = Rabies.animalID
	LEFT JOIN (
		SELECT a.animalID, max(p.startdate) as lastDate
		FROM 
		  Animal a
		  INNER JOIN Prescription p on p.animalID = a.animalID
		  INNER JOIN Medication m on p.medicationID = m.medicationID and m.medicationName = 'Flea'
		GROUP BY a.animalID
	) Flea on a.animalID = Flea.animalID
	LEFT JOIN (
		SELECT a.animalID, max(p.startdate) as lastDate
		FROM 
		  Animal a
		  INNER JOIN Prescription p on p.animalID = a.animalID
		  INNER JOIN Medication m on p.medicationID = m.medicationID and m.medicationName = 'Pyrantel'
		GROUP BY a.animalID
	) Pyrantel on a.animalID = Flea.animalID
	WHERE a.animalID = inAnimalID;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `FindAnimal` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `FindAnimal`(
	IN inAnimalName varchar(20), 
	IN inMicrochipNumber varchar(20), 
	IN inSpecies char, 
	IN inGender char,
	in inTransferTypeID int,
	in inAdoptionStatusID char,
	IN onlyAdoptable bool,
	IN notFixed bool,
	IN startDate date,
	IN endDate date
)
BEGIN
-- Get a list of animals up for adoption
	SELECT 
		a.animalID, a.animalName,a.breed, a.microchipNumber,
	case (a.species) 
			WHEN 'D' THEN "Dog" 
			WHEN 'C' THEN "Cat" 
		end as speciesName,a.species,
	case (a.gender) 
			WHEN 'F' THEN "Female" 
			WHEN 'M' THEN "Male" 
		end as genderName,a.gender,
		case (a.isFixed) 
			WHEN 1 THEN "Yes" 
			WHEN 0 THEN "No" 
		end as Fixed,	 a.isFixed	,
		a.estBirthdate,
 	 if((`per`.`firstName` is NULL or per.isOrg),
             `per`.`lastName`,
           concat(`per`.`firstName`, ' ', `per`.`lastName`)
		) as CurrentPerson, 
		per.personID,
		case (tt.adoptable) 
			WHEN 'Y' THEN 'Yes'
			ELSE 'No' 
		end as Adoptable,
		tt.transferName, tt.transferTypeID, 
		maxDate.transferDate, t.personID
	FROM 
		Animal a
		INNER JOIN 
		( 
			SELECT t.animalID, MAX(t.transferDate) as transferDate
			FROM Transfer t 
			WHERE ((t.transferDate BETWEEN startDate AND endDate) or (!startDate)) AND
				((t.transferTypeID = inTransferTypeID) OR inTransferTypeID = 0) 
			GROUP BY t.animalID
		) maxDate on a.animalID = maxDate.animalID 	
		INNER JOIN Transfer t on t.animalID = a.animalID and t.transferDate = maxDate.transferDate
		INNER JOIN TransferType tt on t.transferTypeID = tt.transferTypeID
		INNER JOIN Person per on per.personID = t.personID
WHERE 
	(a.animalName like concat('%', inAnimalName, '%') OR (inAnimalName like '')) AND
	(a.microchipNumber like concat('%', inMicrochipNumber, '%') OR (inMicrochipNumber like '')) AND
	(a.species = inSpecies OR inSpecies like '') AND 
	(a.gender = inGender  OR inGender like '') AND
	(t.transferTypeID = inTransferTypeID OR inTransferTypeID = 0) AND
	((a.adoptionStatusID = inAdoptionStatusID) OR inAdoptionStatusID = '') AND
 	(((tt.adoptable = 'Y') AND onlyAdoptable) OR !onlyAdoptable) AND
 	((!a.isFixed AND notFixed) OR !notFixed) 
;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `FindApplication` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `FindApplication`(
	IN inName varchar(100),
	IN inBreed varchar(200),
	IN inSpecies char,
	IN showClosed bit
)
BEGIN
	SELECT a.*, p.personID, p.firstName, p.lastName from Application a
    INNER JOIN Person p on a.personID = p.personID
	WHERE
	(
		(p.firstName like concat('%', inName, '%')) OR 
		(p.lastName  like concat('%', inName, '%')) OR 
		(concat('%', p.firstName, '%', p.lastName, '%') like concat('%', inName, '%')) OR 
		(inName like '')
	) AND (
        (a.breed like concat('%', inBreed, '%')) OR (inBreed like '')
    ) AND (
        (a.species like inSpecies) OR (inSpecies like '')
	) AND  (
        showClosed=1 OR (a.closed = 0)
	)
    ORDER BY a.applicationDate
        ;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `FindPerson` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `FindPerson`(	
	IN inName varchar(100),
	IN inEmail varchar(20),
	IN inTelephone varchar(20),
	IN inPositionTypeID int,
	IN inIsOrg bit
)
BEGIN
	SELECT p.* from Person p
	WHERE
	(
		(p.firstName like concat('%', inName, '%')) OR 
		(p.lastName  like concat('%', inName, '%')) OR 
		(concat('%', p.firstName, '%', p.lastName, '%') like concat('%', inName, '%')) OR 
		(p.secondary  like concat('%', inName, '%')) OR 
		(inName like '')
	) AND (
        p.email like concat('%', inEmail, '%') OR (inEmail like '')
    ) AND (
		(p.cellPhone like concat('%', inTelephone, '%')) OR
		(p.homePhone like concat('%', inTelephone, '%')) OR
		(p.workPhone like concat('%', inTelephone, '%')) OR
		(inTelephone like '')
	) AND  (
        inIsOrg=p.isOrg OR (inIsOrg=0)
    ) AND (
		(inPositionTypeID in (select positionTypeID from PersonPosition where personID = p.personID and positionTypeID = inPositionTypeID)) 
		OR (inPositionTypeID=0)
	) and p.personID > 0;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `HomeAnimals` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `HomeAnimals`(IN inPersonID int)
BEGIN
	SELECT 
		a.animalID, a.animalName, per.personID, t.transferDate,
	case (a.species) 
			WHEN 'D' THEN "Dog" 
			WHEN 'C' THEN "Cat" 
			WHEN 'O' THEN "Other" 
		end as species,
		case (t.isPermenant) 
			WHEN 1 THEN "No" 
			WHEN 0 THEN "Yes" 
		end as Adoptable,
		case (a.isFixed) 
			WHEN 1 THEN "Yes" 
			WHEN 0 THEN "No" 
		end as isFixed,		
		tt.transferName
	FROM 
		Animal a
		INNER JOIN Transfer t on t.animalID = a.animalID and t.personID = inPersonID
		INNER JOIN TransferType tt on t.transferTypeID = tt.transferTypeID
		INNER JOIN Person per on per.personID = t.personID
		INNER JOIN 
		( 
			SELECT t.animalID, t.personID, MAX(t.transferDate) as transferDate
			FROM Transfer t 
			GROUP BY t.animalID
		) maxDate on a.animalID = maxDate.animalID AND t.transferDate = maxDate.transferDate	;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `matchAnimals` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `matchAnimals`(IN  inApplicationID int, IN  inAnimalID int)
BEGIN
select  * 
FROM 
(
	select a.animalID, a.animalName, a.breed, a.species, a.gender, a.activityLevel, a.personalityID, a.note, a.kids, a.dogs, a.cats, a.isHypo,
		vs.vitalValue as weight, t.transferTypeID, tt.transferName,
		(date_format(from_days((to_days(now()) - to_days(`a`.`estBirthdate`))), '%Y') + 0) AS `age`
	From Animal a
	LEFT JOIN (
		select vs.animalID, vs.vitalValue,  max(vs.vitalDateTime) as vitalDateTime
		FROM VitalSign vs
		WHERE vs.vitalSignTypeID = 7
		GROUP BY vs.animalID
	) w on w.animalID=a.animalID
	LEFT JOIN VitalSign vs ON vs.animalID = w.animalID and vs.vitalDateTime=w.vitalDateTime and vs.vitalSignTypeID = 7
	INNER JOIN (
		select t.animalID, max(t.transferDate) as transferDate
		FROM Transfer t
		group by t.animalID
	) cp on cp.animalID = a.animalID  
	INNER JOIN Transfer t on t.animalID = cp.animalID and t.transferDate=cp.transferDate 
	INNER JOIN TransferType tt on tt.transferTypeID = t.transferTypeID
) al
INNER JOIN Application w ON (
	w.closed = 0 AND 
	(al.species = w.species) AND
	((al.cats = 'Y' and w.numCats > 0) or (w.numCats = 0)) AND 
	((al.dogs = 'Y' and w.numDogs > 0) or (w.numDogs = 0)) AND 
	((al.kids = 'Y' and w.numKids > 0) or (w.numKids = 0)) AND 
	((al.isHypo = 1) or (w.needHypo <> 1)) AND 
	((al.gender = w.gender) or (w.gender = '')) AND 
	((al.personalityID = w.personalityID) or (w.personalityID = '') or (w.personalityID = 0)) AND 
	((al.age >= w.minAge) and (al.age <= w.maxAge)) AND
	((al.weight >= w.minWeight) and (al.weight <= w.maxWeight)) AND
	((al.activityLevel >= w.minActivityLevel) and (al.activityLevel <= w.maxActivityLevel)) AND
	(al.transferTypeID in (1, 5))
)
INNER JOIN Person p on w.personID = p.personID
WHERE ((w.applicationID = inApplicationID) OR (al.animalID = inAnimalID))
;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `medList` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `medList`(IN inAnimalID int)
BEGIN
	SELECT a.animalID, a.animalName,
		Bordatella.lastDate as Bordatella, 
		Rabies.lastDate as Rabies, 
		Flea.lastDate as Flea, Pyrantel.
		lastDate as Pyrantel
	FROM Animal a
	LEFT JOIN 
	(
		SELECT a.animalID, max(p.startdate) as lastDate
		FROM 
		  Animal a
		  INNER JOIN Prescription p on p.animalID = a.animalID
		  INNER JOIN Medication m on p.medicationID = m.medicationID and m.medicationName = 'DHPP'
		GROUP BY a.animalID
		) DHPP on DHPP.animalID = a.animalID
	LEFT JOIN 
	(
		SELECT a.animalID, max(p.startdate) as lastDate
		FROM 
		  Animal a
		  INNER JOIN Prescription p on p.animalID = a.animalID
		  INNER JOIN Medication m on p.medicationID = m.medicationID and m.medicationName = 'Bordatella'
		GROUP BY a.animalID
	) Bordatella on a.animalID = DHPP.animalID
	LEFT JOIN (
		SELECT a.animalID, max(p.startdate) as lastDate
		FROM 
		  Animal a
		  INNER JOIN Prescription p on p.animalID = a.animalID
		  INNER JOIN Medication m on p.medicationID = m.medicationID and m.medicationName = 'Rabies'
		GROUP BY a.animalID
	) Rabies on a.animalID = Rabies.animalID
	LEFT JOIN (
		SELECT a.animalID, max(p.startdate) as lastDate
		FROM 
		  Animal a
		  INNER JOIN Prescription p on p.animalID = a.animalID
		  INNER JOIN Medication m on p.medicationID = m.medicationID and m.medicationName = 'Flea'
		GROUP BY a.animalID
	) Flea on a.animalID = Flea.animalID
	LEFT JOIN (
		SELECT a.animalID, max(p.startdate) as lastDate
		FROM 
		  Animal a
		  INNER JOIN Prescription p on p.animalID = a.animalID
		  INNER JOIN Medication m on p.medicationID = m.medicationID and m.medicationName = 'Pyrantel'
		GROUP BY a.animalID
	) Pyrantel on a.animalID = Flea.animalID
	WHERE a.animalID = inAnimalID;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `pixieVaccinations` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `pixieVaccinations`(inDate date)
BEGIN
SELECT m.*, p.*, a.animalID, a.animalName 
FROM (
	SELECT medicationID, animalID, max(startDate) startDate 
	FROM Prescription 
	GROUP BY medicationID, animalID
) maxDate
INNER JOIN Medication m on m.medicationID = maxDate.medicationID
INNER JOIN Prescription p on p.startDate = maxDate.startDate and maxDate.animalID = p.animalID and p.medicationID = maxDate.medicationID
INNER JOIN Animal a on maxDate.animalID = a.animalID
INNER JOIN CurrentTransfer ct on ct.animalID = a.animalID and ct.pixieResponsible in ('Y', '')
WHERE p.nextDose is not null  and p.nextDose <= inDate
order by p.nextDose;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `ShowVaccinationDates` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `ShowVaccinationDates`(IN inAnimalID int, IN inMedicationID int)
BEGIN

SELECT 
  a.animalID, 
  p.startdate, 
  m.medicationName, cm.countMeds
FROM 
  Animal a
  INNER JOIN Prescription p on p.animalID = a.animalID and p.medicationID = inMedicationID
  INNER JOIN Medication m on m.medicationID = p.medicationID and m.isVaccination
  INNER JOIN CountMeds cm on cm.animalID = a.animalID and cm.medicationID = m.medicationID
WHERE a.animalID = inAnimalID
ORDER by p.startdate;

END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 DROP PROCEDURE IF EXISTS `VitalSign` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `VitalSign`(IN inAnimalID int)
BEGIN
-- Get a list of vital signs and ranges, ordered by date
SELECT 
  a.animalname, 
  a.species, 
  a.estbirthdate, 
  vst.vitalsigntypename, 
  concat (vs.vitalValue, ' ', vst.units) as VitalValue,
  concat ('(', vst.low, ' ', vst.units, ' - ', vst.hi, ' ', vst.units,')') as VitalsRange,
  vs.vitalDateTime
FROM 
  Animal a  
  INNER JOIN VitalSign vs on a.animalid = vs.animalid
  INNER JOIN VitalSignType vst on vs.vitalsigntypeid = vst.vitalsigntypeid
WHERE a.animalID = inAnimalID
ORDER BY vs.vitalDateTime;
END ;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Final view structure for view `AnimalInfo`
--

/*!50001 DROP TABLE IF EXISTS `AnimalInfo`*/;
/*!50001 DROP VIEW IF EXISTS `AnimalInfo`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `AnimalInfo` AS select `a`.`animalID` AS `animalID`,`a`.`animalName` AS `animalName`,`a`.`breed` AS `breed`,`a`.`markings` AS `markings`,`a`.`activityLevel` AS `activityLevel`,(case `a`.`species` when 'D' then 'Dog' when 'C' then 'Cat' when 'O' then 'Other' end) AS `species`,(case `a`.`gender` when 'F' then 'Female' when 'M' then 'Male' when 'O' then 'Other/Unknown' end) AS `gender`,(case `a`.`isFixed` when '1' then 'Yes' when '0' then 'No' end) AS `isFixed`,(case `a`.`isHypo` when '1' then 'Yes' when '0' then 'No' end) AS `isHypo`,(case `a`.`kids` when 'Y' then 'Yes' when 'N' then 'No' end) AS `kids`,(case `a`.`cats` when 'Y' then 'Yes' when 'N' then 'No' end) AS `cats`,(case `a`.`dogs` when 'Y' then 'Yes' when 'N' then 'No' end) AS `dogs`,`a`.`estBirthdate` AS `estBirthdate`,(date_format(from_days((to_days(now()) - to_days(`a`.`estBirthdate`))),'%Y') + 0) AS `age`,`a`.`note` AS `note`,`a`.`microchipNumber` AS `microchipNumber`,`a`.`dateImplanted` AS `dateImplanted`,`mt`.`microchipName` AS `microchipName`,`py`.`personality` AS `personality`,`as`.`adoptionStatus` AS `adoptionStatus`,`a`.`url` AS `url` from (((`Animal` `a` left join `MicrochipType` `mt` on((`mt`.`microchipTypeID` = `a`.`microchipTypeID`))) left join `Personality` `py` on((`py`.`personalityID` = `a`.`personalityID`))) left join `AdoptionStatus` `as` on((`as`.`adoptionStatusID` = `a`.`adoptionStatusID`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `AnimalSurgeries`
--

/*!50001 DROP TABLE IF EXISTS `AnimalSurgeries`*/;
/*!50001 DROP VIEW IF EXISTS `AnimalSurgeries`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `AnimalSurgeries` AS select `asg`.`surgeryTypeID` AS `surgeryTypeID`,`asg`.`animalID` AS `animalID`,`asg`.`surgeryDate` AS `surgeryDate`,`asg`.`note` AS `note`,`asg`.`personID` AS `personID`,`st`.`surgeryType` AS `surgeryType`,`a`.`animalName` AS `animalName`,`p`.`lastName` AS `lastName` from (((`AnimalSurgery` `asg` left join `Animal` `a` on((`asg`.`animalID` = `a`.`animalID`))) join `SurgeryType` `st` on((`st`.`surgeryTypeID` = `asg`.`surgeryTypeID`))) left join `Person` `p` on((`asg`.`personID` = `p`.`personID`))) order by `asg`.`surgeryDate` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `CountMeds`
--

/*!50001 DROP TABLE IF EXISTS `CountMeds`*/;
/*!50001 DROP VIEW IF EXISTS `CountMeds`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `CountMeds` AS select `a`.`animalID` AS `animalID`,`m`.`medicationID` AS `medicationID`,`m`.`medicationName` AS `medicationName`,count(`p`.`startDate`) AS `countMeds` from ((`Animal` `a` join `Prescription` `p` on((`p`.`animalID` = `a`.`animalID`))) join `Medication` `m` on((`p`.`medicationID` = `m`.`medicationID`))) group by `m`.`medicationName` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `CurrentPositions`
--

/*!50001 DROP TABLE IF EXISTS `CurrentPositions`*/;
/*!50001 DROP VIEW IF EXISTS `CurrentPositions`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `CurrentPositions` AS select `pp`.`positionTypeID` AS `positionTypeID`,`pp`.`personID` AS `personID`,`pp`.`note` AS `note`,`pp`.`startDate` AS `startDate`,`pt`.`positionName` AS `positionName` from (`PersonPosition` `pp` join `PositionType` `pt` on((`pt`.`positionTypeID` = `pp`.`positionTypeID`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `CurrentTransfer`
--

/*!50001 DROP TABLE IF EXISTS `CurrentTransfer`*/;
/*!50001 DROP VIEW IF EXISTS `CurrentTransfer`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `CurrentTransfer` AS select `t`.`animalID` AS `animalID`,`t`.`personID` AS `personID`,`t`.`transferDate` AS `transferDate`,`t`.`transferTypeID` AS `transferTypeID`,`t`.`fee` AS `fee`,`t`.`note` AS `note`,`a`.`animalName` AS `animalName`,`a`.`microchipNumber` AS `microchipNumber`,`a`.`adoptionStatusID` AS `adoptionStatusID`,if(`p`.`isOrg`,`p`.`lastName`,concat(`p`.`firstName`,' ',`p`.`lastName`)) AS `CurrentPerson`,`tt`.`transferName` AS `transferName`,`tt`.`pixieResponsible` AS `pixieResponsible`,(case `a`.`species` when 'D' then 'Dog' when 'C' then 'Cat' end) AS `speciesName`,`a`.`species` AS `species`,(case `a`.`gender` when 'F' then 'Female' when 'M' then 'Male' end) AS `genderName`,`a`.`gender` AS `gender`,(case `a`.`isFixed` when 1 then 'Yes' when 0 then 'No' end) AS `Fixed`,`a`.`isFixed` AS `isFixed`,`a`.`estBirthdate` AS `estBirthdate`,(case `tt`.`adoptable` when 'Y' then 'Yes' else 'No' end) AS `Adoptable` from ((((`innerCurrentTransfer` `cp` join `Transfer` `t` on(((`t`.`animalID` = `cp`.`animalID`) and (`cp`.`transferDate` = `t`.`transferDate`)))) join `Animal` `a` on((`a`.`animalID` = `t`.`animalID`))) join `Person` `p` on((`t`.`personID` = `p`.`personID`))) join `TransferType` `tt` on((`t`.`transferTypeID` = `tt`.`transferTypeID`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `Medications`
--

/*!50001 DROP TABLE IF EXISTS `Medications`*/;
/*!50001 DROP VIEW IF EXISTS `Medications`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `Medications` AS select `a`.`animalID` AS `animalID`,`p`.`startDate` AS `startdate`,`m`.`medicationName` AS `medicationname` from ((`Animal` `a` join `Prescription` `p` on((`p`.`animalID` = `a`.`animalID`))) join `Medication` `m` on((`p`.`medicationID` = `m`.`medicationID`))) where (`m`.`isVaccination` = 0) order by `p`.`startDate` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `TestView`
--

/*!50001 DROP TABLE IF EXISTS `TestView`*/;
/*!50001 DROP VIEW IF EXISTS `TestView`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `TestView` AS select `t`.`testDate` AS `testDate`,`t`.`testResult` AS `testResult`,`t`.`note` AS `note`,`t`.`testTypeID` AS `testTypeID`,`t`.`animalID` AS `animalID`,`tt`.`testName` AS `testName`,`tt`.`desiredResult` AS `desiredResult` from (`Test` `t` left join `TestType` `tt` on((`t`.`testTypeID` = `tt`.`testTypeID`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `TransferHistory`
--

/*!50001 DROP TABLE IF EXISTS `TransferHistory`*/;
/*!50001 DROP VIEW IF EXISTS `TransferHistory`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `TransferHistory` AS select `a`.`animalName` AS `animalName`,`tt`.`transferName` AS `transferName`,`t`.`animalID` AS `animalID`,`t`.`personID` AS `personID`,`t`.`transferDate` AS `transferDate`,`t`.`transferTypeID` AS `transferTypeID`,`tt`.`adoptable` AS `adoptable`,`t`.`fee` AS `fee`,`t`.`note` AS `note`,if((isnull(`p`.`firstName`) or `p`.`isOrg`),`p`.`lastName`,concat(`p`.`firstName`,' ',`p`.`lastName`)) AS `Name` from (((`Animal` `a` join `Transfer` `t` on((`a`.`animalID` = `t`.`animalID`))) join `TransferType` `tt` on((`t`.`transferTypeID` = `tt`.`transferTypeID`))) join `Person` `p` on((`t`.`personID` = `p`.`personID`))) order by `t`.`transferDate` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `innerCurrentTransfer`
--

/*!50001 DROP TABLE IF EXISTS `innerCurrentTransfer`*/;
/*!50001 DROP VIEW IF EXISTS `innerCurrentTransfer`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `innerCurrentTransfer` AS select `Transfer`.`animalID` AS `animalID`,max(`Transfer`.`transferDate`) AS `transferDate` from `Transfer` group by `Transfer`.`animalID` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-03-22 15:59:35
