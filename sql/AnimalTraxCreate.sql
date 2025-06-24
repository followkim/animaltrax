CREATE DATABASE  IF NOT EXISTS `pixieBK` /*!40100 DEFAULT CHARACTER SET big5 */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `pixieBK`;
-- MySQL dump 10.13  Distrib 8.0.42, for Linux (x86_64)
--
-- Host: 192.168.0.128    Database: pixie
-- ------------------------------------------------------
-- Server version	8.0.42-0ubuntu0.24.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `AdoptionStatus` (
  `adoptionStatusID` char(1) NOT NULL,
  `adoptionStatus` varchar(20) NOT NULL,
  `isAdoptable` varchar(45) CHARACTER SET big5 COLLATE big5_bin DEFAULT NULL,
  PRIMARY KEY (`adoptionStatusID`)
) ENGINE=InnoDB DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `AdoptionStatus`
--

LOCK TABLES `AdoptionStatus` WRITE;
/*!40000 ALTER TABLE `AdoptionStatus` DISABLE KEYS */;
INSERT INTO `AdoptionStatus` VALUES ('A','Available','1'),('M','Meet','0'),('N','Not ready','0'),('P','Pending Eval','0'),('U','Not Adoptable','0');
/*!40000 ALTER TABLE `AdoptionStatus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Animal`
--

DROP TABLE IF EXISTS `Animal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Animal` (
  `animalID` int NOT NULL AUTO_INCREMENT,
  `animalName` varchar(100) NOT NULL,
  `species` char(1) DEFAULT NULL,
  `breed` varchar(50) DEFAULT NULL,
  `markings` varchar(45) DEFAULT NULL,
  `gender` char(1) DEFAULT NULL,
  `estBirthdate` date DEFAULT NULL,
  `isFixed` bit(1) DEFAULT b'0',
  `activityLevel` int DEFAULT NULL,
  `note` longtext,
  `microchipNumber` varchar(25) DEFAULT NULL,
  `microchipTypeID` int DEFAULT NULL,
  `dateImplanted` date DEFAULT NULL,
  `url` varchar(256) DEFAULT NULL,
  `kids` char(1) DEFAULT 'U',
  `dogs` char(1) DEFAULT 'U',
  `cats` char(1) DEFAULT 'U',
  `adoptionStatusID` char(1) DEFAULT NULL,
  `personalityID` char(1) DEFAULT NULL,
  `isHypo` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`animalID`),
  KEY `Animal_ibfk_1` (`microchipTypeID`),
  CONSTRAINT `Animal_ibfk_1` FOREIGN KEY (`microchipTypeID`) REFERENCES `MicrochipType` (`microchipTypeID`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Animal`
--

LOCK TABLES `Animal` WRITE;
/*!40000 ALTER TABLE `Animal` DISABLE KEYS */;
INSERT INTO `Animal` VALUES (9,'Diego','D','Chi/Beagle Mix','Fawn/White','M','2013-01-07',_binary '',3,'very sweet boy','1359322',1,'2014-01-07','uploads/DigDates_Portland-90.jpg','Y','N','Y','A','C',0),(14,'Loki','C','Devon Rex','White and tan','F','2013-05-07',_binary '',3,'','',NULL,NULL,'uploads/IMG_0586.JPG','U','Y','N','A','A',1),(15,'Boo','O','rabbit','white','M','2015-05-04',_binary '\0',0,'','',NULL,NULL,'uploads/WSH.jpg','U','U','U','A','P',0),(17,'Jake','C','Tabby','','M','2024-11-05',_binary '\0',6,'bonded with <a href=\"viewAnimal.php?animalID=18\">Elwood</a>\r\n','',NULL,NULL,'','U','U','Y','A','P',0),(18,'Elwood','C','tabby','','M','2024-11-05',_binary '\0',5,'bonded with <a href=\"viewAnimal.php?animalID=17\">Jake</a>','',NULL,NULL,'','U','U','Y','A','P',0),(19,'Willow','D','Border collie','Black/white','F','2017-05-05',_binary '',7,'','',NULL,NULL,'','U','N','U','A','E',0),(20,'Griffin','D','Border Collie','Black/White','M','2017-05-05',_binary '',6,'','',NULL,NULL,'','Y','Y','U','A','E',0),(21,'Maeby','C','Devon Rex','tri','F','2009-05-05',_binary '',0,'','',NULL,NULL,'','U','Y','Y','U','I',1),(22,'Farfel','D','Papillion ','tri','F','2025-05-07',_binary '',2,'Barks a lot at strangers','',NULL,NULL,'','U','Y','N','P','I',0),(23,'Griffon','D','Border Collie','Black and White','M','2016-12-11',_binary '',8,'','A8456789865',7,'2017-03-11','','Y','Y','Y','A','A',0),(25,'Spark Plug','D','Mix','White and tan','F','2024-01-01',_binary '',3,'','',1,NULL,'','U','Y','U','A','C',0);
/*!40000 ALTER TABLE `Animal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `AnimalInfo`
--

DROP TABLE IF EXISTS `AnimalInfo`;
/*!50001 DROP VIEW IF EXISTS `AnimalInfo`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `AnimalInfo` AS SELECT 
 1 AS `animalID`,
 1 AS `animalName`,
 1 AS `breed`,
 1 AS `markings`,
 1 AS `activityLevel`,
 1 AS `species`,
 1 AS `gender`,
 1 AS `isFixed`,
 1 AS `isHypo`,
 1 AS `kids`,
 1 AS `cats`,
 1 AS `dogs`,
 1 AS `estBirthdate`,
 1 AS `age`,
 1 AS `weight`,
 1 AS `note`,
 1 AS `microchipNumber`,
 1 AS `dateImplanted`,
 1 AS `microchipName`,
 1 AS `personality`,
 1 AS `adoptionStatus`,
 1 AS `url`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `AnimalSurgeries`
--

DROP TABLE IF EXISTS `AnimalSurgeries`;
/*!50001 DROP VIEW IF EXISTS `AnimalSurgeries`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `AnimalSurgeries` AS SELECT 
 1 AS `surgeryTypeID`,
 1 AS `animalID`,
 1 AS `surgeryDate`,
 1 AS `note`,
 1 AS `personID`,
 1 AS `surgeryType`,
 1 AS `animalName`,
 1 AS `lastName`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `AnimalSurgery`
--

DROP TABLE IF EXISTS `AnimalSurgery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `AnimalSurgery` (
  `surgeryTypeID` int NOT NULL,
  `animalID` int NOT NULL,
  `surgeryDate` date NOT NULL DEFAULT '0000-00-00',
  `note` varchar(150) DEFAULT NULL,
  `personID` int DEFAULT NULL,
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
-- Dumping data for table `AnimalSurgery`
--

LOCK TABLES `AnimalSurgery` WRITE;
/*!40000 ALTER TABLE `AnimalSurgery` DISABLE KEYS */;
INSERT INTO `AnimalSurgery` VALUES (1,14,'2013-11-01','',9),(1,17,'2025-06-01','',NULL),(1,18,'2025-06-01','',NULL),(1,22,'2025-07-01','note: has heart condition',1),(2,9,'2015-02-20','',9),(2,9,'2016-02-22','',9),(2,9,'2023-08-17','',9),(2,14,'2020-05-22','',9);
/*!40000 ALTER TABLE `AnimalSurgery` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Application`
--

DROP TABLE IF EXISTS `Application`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Application` (
  `applicationID` int NOT NULL AUTO_INCREMENT,
  `personID` int NOT NULL,
  `applicationDate` date NOT NULL,
  `species` char(1) DEFAULT NULL,
  `gender` char(1) DEFAULT NULL,
  `minAge` int DEFAULT '0',
  `maxAge` int DEFAULT '99',
  `minWeight` int DEFAULT '0',
  `maxWeight` int DEFAULT '600',
  `breed` varchar(150) DEFAULT NULL,
  `minActivityLevel` int DEFAULT '0',
  `maxActivityLevel` int DEFAULT '99',
  `numKids` int DEFAULT '0',
  `numDogs` int DEFAULT '0',
  `numCats` int DEFAULT '0',
  `personalityID` char(1) DEFAULT NULL,
  `note` longtext,
  `needHypo` tinyint NOT NULL DEFAULT '0',
  `closed` tinyint NOT NULL DEFAULT '0',
  `rank` int DEFAULT '3',
  PRIMARY KEY (`applicationID`),
  KEY `personID` (`personID`),
  CONSTRAINT `Application_ibfk_1` FOREIGN KEY (`personID`) REFERENCES `Person` (`personID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Application`
--

LOCK TABLES `Application` WRITE;
/*!40000 ALTER TABLE `Application` DISABLE KEYS */;
INSERT INTO `Application` VALUES (1,5,'2025-05-04','C','',8,20,0,600,'older cat',0,10,0,0,0,'0','Must be litter trained and won\'t scratch furniture.',1,0,3),(2,6,'2025-05-05','D','',0,3,0,15,'',0,3,0,1,0,'0','need a gentle dog',0,0,3);
/*!40000 ALTER TABLE `Application` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `CountMeds`
--

DROP TABLE IF EXISTS `CountMeds`;
/*!50001 DROP VIEW IF EXISTS `CountMeds`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `CountMeds` AS SELECT 
 1 AS `animalID`,
 1 AS `medicationID`,
 1 AS `medicationName`,
 1 AS `countMeds`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `CurrentPositions`
--

DROP TABLE IF EXISTS `CurrentPositions`;
/*!50001 DROP VIEW IF EXISTS `CurrentPositions`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `CurrentPositions` AS SELECT 
 1 AS `positionTypeID`,
 1 AS `personID`,
 1 AS `note`,
 1 AS `startDate`,
 1 AS `positionName`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `CurrentTransfer`
--

DROP TABLE IF EXISTS `CurrentTransfer`;
/*!50001 DROP VIEW IF EXISTS `CurrentTransfer`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `CurrentTransfer` AS SELECT 
 1 AS `animalID`,
 1 AS `personID`,
 1 AS `transferDate`,
 1 AS `transferTypeID`,
 1 AS `fee`,
 1 AS `note`,
 1 AS `animalName`,
 1 AS `microchipNumber`,
 1 AS `adoptionStatusID`,
 1 AS `CurrentPerson`,
 1 AS `transferName`,
 1 AS `pixieResponsible`,
 1 AS `speciesName`,
 1 AS `species`,
 1 AS `genderName`,
 1 AS `gender`,
 1 AS `Fixed`,
 1 AS `isFixed`,
 1 AS `estBirthdate`,
 1 AS `Adoptable`,
 1 AS `Status`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `DogSize`
--

DROP TABLE IF EXISTS `DogSize`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `DogSize` (
  `dogSizeName` varchar(20) NOT NULL,
  `minSize` int NOT NULL DEFAULT '0',
  `maxSize` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`minSize`,`maxSize`)
) ENGINE=InnoDB DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `DogSize`
--

LOCK TABLES `DogSize` WRITE;
/*!40000 ALTER TABLE `DogSize` DISABLE KEYS */;
INSERT INTO `DogSize` VALUES ('Toy',0,10),('Small',10,25),('Medium',25,60),('Large',60,150),('ExtraLarge',150,400);
/*!40000 ALTER TABLE `DogSize` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `File`
--

DROP TABLE IF EXISTS `File`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `File` (
  `fileID` int NOT NULL AUTO_INCREMENT,
  `fileName` varchar(45) NOT NULL,
  `fileURL` varchar(256) NOT NULL,
  `dateUploaded` date DEFAULT NULL,
  `note` varchar(45) DEFAULT NULL,
  `animalID` int DEFAULT NULL,
  `personID` int DEFAULT NULL,
  PRIMARY KEY (`fileID`),
  KEY `File_ibfk_1` (`animalID`),
  KEY `File_ibfk_2` (`personID`),
  CONSTRAINT `File_ibfk_1` FOREIGN KEY (`animalID`) REFERENCES `Animal` (`animalID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `File_ibfk_2` FOREIGN KEY (`personID`) REFERENCES `Person` (`personID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `File`
--

LOCK TABLES `File` WRITE;
/*!40000 ALTER TABLE `File` DISABLE KEYS */;
INSERT INTO `File` VALUES (3,'DigDates_Portland-90.jpg','uploads/DigDates_Portland-90.jpg','2025-05-06',NULL,9,NULL),(11,'IMG_0586.JPG','uploads/IMG_0586.JPG','2025-06-22',NULL,14,NULL),(13,'WSH.jpg','uploads/WSH.jpg','2025-06-22',NULL,15,NULL);
/*!40000 ALTER TABLE `File` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Medication`
--

DROP TABLE IF EXISTS `Medication`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Medication` (
  `medicationID` int NOT NULL AUTO_INCREMENT,
  `medicationName` varchar(20) DEFAULT NULL,
  `notes` varchar(50) DEFAULT NULL,
  `isVaccination` tinyint NOT NULL DEFAULT '1',
  `species` char(1) DEFAULT NULL,
  `nextDoseDays` int NOT NULL DEFAULT '30',
  PRIMARY KEY (`medicationID`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Medication`
--

LOCK TABLES `Medication` WRITE;
/*!40000 ALTER TABLE `Medication` DISABLE KEYS */;
INSERT INTO `Medication` VALUES (1,'DHPP','Distemper, hepatitis, parvovirus and parainfluenza',1,'',365),(2,'Bordatella','',1,'',365),(3,'Rabies','',1,'',1095),(4,'Flea','',1,'',30),(5,'Pyrantel','dewormer',1,'',365),(6,'DAPP','',1,'D',365),(16,'FVRCP','',1,'C',365),(17,'Leptospirosis','',1,'D',365),(18,'Heartworm','',0,'D',182);
/*!40000 ALTER TABLE `Medication` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `Medications`
--

DROP TABLE IF EXISTS `Medications`;
/*!50001 DROP VIEW IF EXISTS `Medications`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `Medications` AS SELECT 
 1 AS `animalID`,
 1 AS `startdate`,
 1 AS `medicationname`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `MicrochipType`
--

DROP TABLE IF EXISTS `MicrochipType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `MicrochipType` (
  `microchipTypeID` int NOT NULL AUTO_INCREMENT,
  `microchipName` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`microchipTypeID`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `MicrochipType`
--

LOCK TABLES `MicrochipType` WRITE;
/*!40000 ALTER TABLE `MicrochipType` DISABLE KEYS */;
INSERT INTO `MicrochipType` VALUES (1,'24PetWatch'),(2,'AKC'),(3,'Avid'),(4,'Avid Euro'),(5,'Banfield'),(6,'Bayer ResQ'),(7,'HomeAgain'),(8,'Found Animals'),(9,'Datamars'),(10,'Other / Unknown');
/*!40000 ALTER TABLE `MicrochipType` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Person`
--

DROP TABLE IF EXISTS `Person`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Person` (
  `personID` int NOT NULL AUTO_INCREMENT,
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
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Person`
--

LOCK TABLES `Person` WRITE;
/*!40000 ALTER TABLE `Person` DISABLE KEYS */;
INSERT INTO `Person` VALUES (1,'','AnimalTrax Shelter','','','','','','','','','',NULL,'','',_binary ''),(2,'','Oregon Humane Society','','1067 NE Columbia Blvd','','Portland','OR','97211','','','',NULL,'','OHS',_binary ''),(4,'Kimberley','Gray','','','','Portland','OR','','','','',NULL,'followkim@gmail.com','system developer',_binary '\0'),(5,'Jennifer','Jill','','','','','','','','','',NULL,'','',_binary '\0'),(6,'Darby','Lewes','','','','','','','','','',NULL,'','',_binary '\0'),(7,'','Laps Of Love','','','','','','','','','',NULL,'','euthenasia',_binary ''),(8,'Guy','JensFriend','','','','','','','','','',NULL,'','',_binary '\0'),(9,'','Heartfelt Vet','','','','','','','','','',NULL,'','',_binary '');
/*!40000 ALTER TABLE `Person` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PersonPosition`
--

DROP TABLE IF EXISTS `PersonPosition`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `PersonPosition` (
  `positionTypeID` int NOT NULL DEFAULT '0',
  `personID` int NOT NULL DEFAULT '0',
  `note` varchar(150) DEFAULT NULL,
  `startDate` date DEFAULT NULL,
  PRIMARY KEY (`positionTypeID`,`personID`),
  KEY `personID` (`personID`),
  CONSTRAINT `PersonPosition_ibfk_2` FOREIGN KEY (`positionTypeID`) REFERENCES `PositionType` (`positionTypeID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PersonPosition`
--

LOCK TABLES `PersonPosition` WRITE;
/*!40000 ALTER TABLE `PersonPosition` DISABLE KEYS */;
INSERT INTO `PersonPosition` VALUES (1,1,'','2000-01-01'),(2,4,'database dev','2014-05-01'),(3,8,'','2025-05-01');
/*!40000 ALTER TABLE `PersonPosition` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Personality`
--

DROP TABLE IF EXISTS `Personality`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Personality` (
  `personalityID` char(1) NOT NULL,
  `personality` varchar(20) NOT NULL,
  PRIMARY KEY (`personalityID`)
) ENGINE=InnoDB DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Personality`
--

LOCK TABLES `Personality` WRITE;
/*!40000 ALTER TABLE `Personality` DISABLE KEYS */;
INSERT INTO `Personality` VALUES ('A','Affectionate'),('C','Calm'),('E','Energetic'),('I','Independent'),('P','Playful');
/*!40000 ALTER TABLE `Personality` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PositionType`
--

DROP TABLE IF EXISTS `PositionType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `PositionType` (
  `positionTypeID` int NOT NULL AUTO_INCREMENT,
  `positionName` varchar(20) NOT NULL,
  PRIMARY KEY (`positionTypeID`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PositionType`
--

LOCK TABLES `PositionType` WRITE;
/*!40000 ALTER TABLE `PositionType` DISABLE KEYS */;
INSERT INTO `PositionType` VALUES (1,'Employee'),(2,'Volunteer'),(3,'Foster parent'),(4,'Supporter'),(5,'Vet'),(6,'Technitian'),(7,'Other');
/*!40000 ALTER TABLE `PositionType` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Prescription`
--

DROP TABLE IF EXISTS `Prescription`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Prescription` (
  `medicationID` int NOT NULL,
  `animalID` int NOT NULL,
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
-- Dumping data for table `Prescription`
--

LOCK TABLES `Prescription` WRITE;
/*!40000 ALTER TABLE `Prescription` DISABLE KEYS */;
INSERT INTO `Prescription` VALUES (1,9,'2013-10-04','','','',NULL),(1,9,'2013-12-31','','','',NULL),(1,14,'2015-05-04',NULL,NULL,'Initial Intake','2016-05-03'),(1,14,'2025-05-06','','','','2025-06-05'),(1,14,'2025-05-07','456','','','2025-06-06'),(1,14,'2025-06-24','','','','2026-06-24'),(1,22,'2025-05-07','','','','2025-06-06'),(2,9,'2013-10-04','','','',NULL),(2,9,'2015-01-09','','','','2016-01-09'),(2,9,'2016-01-23','','','',NULL),(2,9,'2019-04-27','','','','2020-04-27'),(2,14,'2025-05-06','','','','2025-05-06'),(2,14,'2025-06-01','','','',NULL),(2,22,'2025-05-07','','','','2025-06-06'),(2,22,'2025-06-22','','','','2026-06-22'),(3,9,'2013-12-20','','','',NULL),(3,9,'2015-01-09','','','','2018-01-09'),(3,9,'2015-05-09','','','','2016-05-09'),(3,9,'2016-05-09','','','','2017-05-09'),(3,9,'2021-02-19','','9/23/2022','','2024-02-19'),(3,9,'2025-06-21','','','','2028-06-20'),(3,14,'2025-05-07','','','test4','2025-06-06'),(3,14,'2025-06-06','','','',NULL),(3,18,'2025-05-07','','','','2025-06-06'),(3,22,'2022-05-07','','','','2022-06-06'),(3,22,'2023-05-07','','','','2023-06-06'),(3,22,'2024-05-07','','','','2024-06-06'),(3,22,'2025-05-07','','','','2025-06-06'),(3,22,'2025-06-22','','','','2028-06-21'),(4,9,'2019-04-27','','','','2020-04-27'),(4,14,'2015-05-04',NULL,NULL,'Initial Intake','2015-06-03'),(4,14,'2025-05-06','','','','2025-06-05'),(4,14,'2025-06-22','','','',NULL),(4,22,'2025-05-07','','','','2025-06-06'),(4,22,'2025-06-22','','','','2025-07-22'),(5,14,'2015-05-04',NULL,NULL,'Initial Intake','2015-06-03'),(5,14,'2025-05-06','','','','2025-06-05'),(6,9,'2015-01-09','','','','2016-01-30'),(6,9,'2016-01-23','','','',NULL),(16,14,'2015-05-04',NULL,NULL,'Initial Intake','2015-05-18'),(16,14,'2025-06-01','','','',NULL),(17,9,'2015-01-09','','','','2016-01-30'),(17,9,'2016-01-23','','','',NULL);
/*!40000 ALTER TABLE `Prescription` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `SurgeryType`
--

DROP TABLE IF EXISTS `SurgeryType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `SurgeryType` (
  `surgeryTypeID` int NOT NULL AUTO_INCREMENT,
  `surgeryType` varchar(20) NOT NULL,
  PRIMARY KEY (`surgeryTypeID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SurgeryType`
--

LOCK TABLES `SurgeryType` WRITE;
/*!40000 ALTER TABLE `SurgeryType` DISABLE KEYS */;
INSERT INTO `SurgeryType` VALUES (1,'Spay/Neuter'),(2,'Dental');
/*!40000 ALTER TABLE `SurgeryType` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Test`
--

DROP TABLE IF EXISTS `Test`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Test` (
  `testDate` date NOT NULL,
  `testResult` varchar(50) DEFAULT NULL,
  `note` varchar(50) DEFAULT NULL,
  `testTypeID` int NOT NULL,
  `animalID` int NOT NULL,
  PRIMARY KEY (`testTypeID`,`animalID`,`testDate`),
  KEY `animalID` (`animalID`),
  CONSTRAINT `Test_ibfk_1` FOREIGN KEY (`testTypeID`) REFERENCES `TestType` (`testTypeID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `Test_ibfk_2` FOREIGN KEY (`animalID`) REFERENCES `Animal` (`animalID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Test`
--

LOCK TABLES `Test` WRITE;
/*!40000 ALTER TABLE `Test` DISABLE KEYS */;
INSERT INTO `Test` VALUES ('2013-12-20','negative','',1,9),('2025-06-23','neg','',1,22),('2025-05-06','neg','',3,14),('2025-05-06','neg','',4,14);
/*!40000 ALTER TABLE `Test` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `TestType`
--

DROP TABLE IF EXISTS `TestType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `TestType` (
  `testTypeID` int NOT NULL AUTO_INCREMENT,
  `testName` varchar(20) DEFAULT NULL,
  `species` char(1) DEFAULT NULL,
  `desiredResult` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`testTypeID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `TestType`
--

LOCK TABLES `TestType` WRITE;
/*!40000 ALTER TABLE `TestType` DISABLE KEYS */;
INSERT INTO `TestType` VALUES (1,'Heartworm','','negative'),(2,'FELV','C','negative'),(3,'FIV','C','negative'),(4,'Fecal','','negative');
/*!40000 ALTER TABLE `TestType` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `TestView`
--

DROP TABLE IF EXISTS `TestView`;
/*!50001 DROP VIEW IF EXISTS `TestView`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `TestView` AS SELECT 
 1 AS `testDate`,
 1 AS `testResult`,
 1 AS `note`,
 1 AS `testTypeID`,
 1 AS `animalID`,
 1 AS `testName`,
 1 AS `desiredResult`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `Transfer`
--

DROP TABLE IF EXISTS `Transfer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Transfer` (
  `animalID` int NOT NULL,
  `personID` int NOT NULL,
  `transferDate` datetime(6) NOT NULL,
  `transferTypeID` int DEFAULT NULL,
  `fee` decimal(5,2) DEFAULT '0.00',
  `note` longtext,
  PRIMARY KEY (`animalID`,`personID`,`transferDate`),
  KEY `personID` (`personID`),
  KEY `Placement_ibfk_3` (`transferTypeID`)
) ENGINE=InnoDB DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Transfer`
--

LOCK TABLES `Transfer` WRITE;
/*!40000 ALTER TABLE `Transfer` DISABLE KEYS */;
INSERT INTO `Transfer` VALUES (9,1,'2013-10-28 00:00:00.000000',1,0.00,'Came via 2nd chance program'),(9,1,'2014-02-01 00:00:00.000000',1,0.00,'returned a bad dog'),(9,4,'2013-11-01 00:00:00.000000',2,0.00,''),(9,4,'2014-01-07 12:00:00.000000',3,200.00,''),(9,4,'2014-02-02 00:00:00.000000',3,0.00,'Just kidding'),(9,4,'2014-05-02 00:00:00.000000',3,0.00,'Gave Digs back finally\r\n'),(9,5,'2014-03-02 00:00:00.000000',13,0.00,'took care of Digs and wouldn\'t give him back'),(14,1,'2025-05-01 00:00:00.000000',1,0.00,'test kitty'),(14,4,'2025-06-16 00:00:00.000000',5,0.00,''),(15,1,'2025-05-04 00:00:00.000000',1,0.00,''),(17,1,'2025-05-05 00:00:00.000000',1,350.00,''),(17,8,'2025-05-09 00:00:00.000000',5,0.00,''),(18,1,'2025-05-05 00:00:00.000000',1,350.00,''),(18,8,'2025-05-09 00:00:00.000000',5,0.00,''),(19,1,'2025-05-01 00:00:00.000000',1,700.00,''),(20,1,'2025-04-05 00:00:00.000000',1,0.00,''),(20,6,'2025-05-05 00:00:00.000000',3,700.00,''),(21,1,'2015-05-05 00:00:00.000000',1,0.00,''),(21,4,'2015-06-05 00:00:00.000000',3,0.00,''),(21,7,'2023-10-05 00:00:00.000000',7,700.00,''),(22,1,'2025-05-05 00:00:00.000000',1,0.00,''),(23,1,'2025-06-15 14:00:00.000000',1,0.00,''),(23,5,'2025-06-15 16:00:00.000000',2,0.00,''),(25,1,'2025-05-01 00:00:00.000000',1,0.00,''),(27,1,'2025-06-21 00:00:00.000000',1,0.00,''),(28,1,'2025-06-22 00:00:00.000000',1,0.00,'');
/*!40000 ALTER TABLE `Transfer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `TransferHistory`
--

DROP TABLE IF EXISTS `TransferHistory`;
/*!50001 DROP VIEW IF EXISTS `TransferHistory`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `TransferHistory` AS SELECT 
 1 AS `animalName`,
 1 AS `transferName`,
 1 AS `animalID`,
 1 AS `personID`,
 1 AS `transferDate`,
 1 AS `transferTypeID`,
 1 AS `adoptable`,
 1 AS `fee`,
 1 AS `note`,
 1 AS `Name`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `TransferType`
--

DROP TABLE IF EXISTS `TransferType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `TransferType` (
  `transferTypeID` int NOT NULL AUTO_INCREMENT,
  `transferName` varchar(20) NOT NULL,
  `adoptable` char(1) DEFAULT NULL,
  `pixieResponsible` char(1) DEFAULT NULL,
  `isOrg` char(1) DEFAULT NULL,
  PRIMARY KEY (`transferTypeID`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `TransferType`
--

LOCK TABLES `TransferType` WRITE;
/*!40000 ALTER TABLE `TransferType` DISABLE KEYS */;
INSERT INTO `TransferType` VALUES (1,'Shelter','Y','Y','P'),(2,'Trial','N','Y','N'),(3,'Adopted','N','N','N'),(4,'Other Shelter','N','N','Y'),(5,'Foster','Y','Y','N'),(6,'Previous Owner','N','N','N'),(7,'Euthanasia','N','N','Y'),(8,'Medical','Y','Y','Y'),(13,'Other','N','N','N');
/*!40000 ALTER TABLE `TransferType` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Users`
--

DROP TABLE IF EXISTS `Users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `Users` (
  `userID` int NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` char(128) NOT NULL,
  `isAdmin` bit(1) DEFAULT b'0',
  PRIMARY KEY (`userID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Users`
--

LOCK TABLES `Users` WRITE;
/*!40000 ALTER TABLE `Users` DISABLE KEYS */;
INSERT INTO `Users` VALUES (1,'kim','followkim@gmail.com','nightmare',_binary ''),(2,'guest','','reporting',_binary '\0'),(4,'jen','','cosmicgirl29',_binary '\0');
/*!40000 ALTER TABLE `Users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `VitalSign`
--

DROP TABLE IF EXISTS `VitalSign`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `VitalSign` (
  `vitalDateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `vitalValue` decimal(10,1) DEFAULT NULL,
  `note` varchar(50) DEFAULT NULL,
  `vitalSignTypeID` int NOT NULL,
  `animalID` int NOT NULL,
  PRIMARY KEY (`vitalSignTypeID`,`animalID`,`vitalDateTime`),
  KEY `animalID` (`animalID`),
  CONSTRAINT `VitalSign_ibfk_1` FOREIGN KEY (`vitalSignTypeID`) REFERENCES `VitalSignType` (`vitalSignTypeID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `VitalSign_ibfk_2` FOREIGN KEY (`animalID`) REFERENCES `Animal` (`animalID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `VitalSign`
--

LOCK TABLES `VitalSign` WRITE;
/*!40000 ALTER TABLE `VitalSign` DISABLE KEYS */;
INSERT INTO `VitalSign` VALUES ('2015-01-09 08:00:00',175.0,'',1,9),('2015-01-30 08:00:00',15.0,'',1,9),('2025-05-04 07:00:00',152.0,'',1,9),('2025-05-09 07:00:00',150.0,'',1,9),('2025-05-05 01:00:00',25.0,'',2,14),('2025-05-06 23:00:00',260.0,'',2,14),('2025-06-21 07:00:00',150.0,'',2,14),('2015-01-09 08:00:00',15.0,'',3,9),('2025-05-04 07:00:00',20.0,'',3,9),('2025-05-09 07:00:00',22.0,'',3,9),('2025-05-06 07:00:00',20.0,'',4,14),('2015-01-09 08:00:00',100.0,'',5,9),('2025-05-04 07:00:00',99.3,'',5,9),('2025-05-09 07:00:00',100.0,'',5,9),('2025-06-23 00:07:00',100.2,'',5,14),('2015-01-09 08:00:00',12.8,'',7,9),('2015-01-30 08:00:00',12.5,'',7,9),('2015-02-20 08:00:00',13.0,'',7,9),('2015-03-24 07:00:00',11.2,'',7,9),('2016-09-03 07:00:00',14.1,'',7,9),('2025-05-04 07:00:00',10.0,'initial intake',7,14),('2025-05-05 07:00:00',7.0,'',7,22),('2025-06-21 07:00:00',10.0,'Initial Intake',7,25);
/*!40000 ALTER TABLE `VitalSign` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `VitalSignType`
--

DROP TABLE IF EXISTS `VitalSignType`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `VitalSignType` (
  `vitalSignTypeID` int NOT NULL,
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
-- Dumping data for table `VitalSignType`
--

LOCK TABLES `VitalSignType` WRITE;
/*!40000 ALTER TABLE `VitalSignType` DISABLE KEYS */;
INSERT INTO `VitalSignType` VALUES (1,'Heart Rate','HR','D',70.0,160.0,'bpm'),(2,'Heart Rate','HR','C',150.0,240.0,'bpm'),(3,'Respiratory Rate','RR','D',10.0,30.0,'breaths per minute'),(4,'Respiratory Rate','RR','C',20.0,30.0,'breaths per minute'),(5,'Temperature','Temp','',101.0,102.5,'F'),(7,'Weight','Weight','',0.0,0.0,'lbs');
/*!40000 ALTER TABLE `VitalSignType` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `innerCurrentTransfer`
--

DROP TABLE IF EXISTS `innerCurrentTransfer`;
/*!50001 DROP VIEW IF EXISTS `innerCurrentTransfer`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `innerCurrentTransfer` AS SELECT 
 1 AS `animalID`,
 1 AS `transferDate`*/;
SET character_set_client = @saved_cs_client;

--
-- Dumping events for database 'pixie'
--

--
-- Dumping routines for database 'pixie'
--
/*!50003 DROP PROCEDURE IF EXISTS `Adoptable` */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb3 */ ;
/*!50003 SET character_set_results = utf8mb3 */ ;
/*!50003 SET collation_connection  = utf8mb3_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `Adoptable`()
BEGIN

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
/*!50003 SET character_set_client  = utf8mb3 */ ;
/*!50003 SET character_set_results = utf8mb3 */ ;
/*!50003 SET collation_connection  = utf8mb3_general_ci */ ;
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
/*!50003 SET character_set_client  = utf8mb3 */ ;
/*!50003 SET character_set_results = utf8mb3 */ ;
/*!50003 SET collation_connection  = utf8mb3_general_ci */ ;
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
/*!50003 SET character_set_client  = utf8mb3 */ ;
/*!50003 SET character_set_results = utf8mb3 */ ;
/*!50003 SET collation_connection  = utf8mb3_general_ci */ ;
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
/*!50003 SET character_set_client  = utf8mb3 */ ;
/*!50003 SET character_set_results = utf8mb3 */ ;
/*!50003 SET collation_connection  = utf8mb3_general_ci */ ;
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
/*!50003 SET character_set_client  = utf8mb3 */ ;
/*!50003 SET character_set_results = utf8mb3 */ ;
/*!50003 SET collation_connection  = utf8mb3_general_ci */ ;
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
/*!50003 SET character_set_client  = utf8mb3 */ ;
/*!50003 SET character_set_results = utf8mb3 */ ;
/*!50003 SET collation_connection  = utf8mb3_general_ci */ ;
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
/*!50003 SET character_set_client  = utf8mb3 */ ;
/*!50003 SET character_set_results = utf8mb3 */ ;
/*!50003 SET collation_connection  = utf8mb3_general_ci */ ;
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
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
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
		select vs.animalID, max(vs.vitalDateTime) as vitalDateTime
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
WHERE ((w.applicationID = inApplicationID) OR (al.animalID = inAnimalID) or (inApplicationID=0 and inAnimalID=0));
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
/*!50003 SET character_set_client  = utf8mb3 */ ;
/*!50003 SET character_set_results = utf8mb3 */ ;
/*!50003 SET collation_connection  = utf8mb3_general_ci */ ;
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
/*!50003 SET character_set_client  = utf8mb3 */ ;
/*!50003 SET character_set_results = utf8mb3 */ ;
/*!50003 SET collation_connection  = utf8mb3_general_ci */ ;
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
/*!50003 SET character_set_client  = utf8mb3 */ ;
/*!50003 SET character_set_results = utf8mb3 */ ;
/*!50003 SET collation_connection  = utf8mb3_general_ci */ ;
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
/*!50003 SET character_set_client  = utf8mb3 */ ;
/*!50003 SET character_set_results = utf8mb3 */ ;
/*!50003 SET collation_connection  = utf8mb3_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
CREATE DEFINER=`root`@`localhost` PROCEDURE `VitalSign`(IN inAnimalID int)
BEGIN

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

/*!50001 DROP VIEW IF EXISTS `AnimalInfo`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `AnimalInfo` AS select `a`.`animalID` AS `animalID`,`a`.`animalName` AS `animalName`,`a`.`breed` AS `breed`,`a`.`markings` AS `markings`,`a`.`activityLevel` AS `activityLevel`,(case `a`.`species` when 'D' then 'Dog' when 'C' then 'Cat' when 'O' then 'Other' end) AS `species`,(case `a`.`gender` when 'F' then 'Female' when 'M' then 'Male' when 'O' then 'Other/Unknown' end) AS `gender`,(case `a`.`isFixed` when '1' then 'Yes' when '0' then 'No' end) AS `isFixed`,(case `a`.`isHypo` when '1' then 'Yes' when '0' then 'No' end) AS `isHypo`,(case `a`.`kids` when 'Y' then 'Yes' when 'N' then 'No' end) AS `kids`,(case `a`.`cats` when 'Y' then 'Yes' when 'N' then 'No' end) AS `cats`,(case `a`.`dogs` when 'Y' then 'Yes' when 'N' then 'No' end) AS `dogs`,`a`.`estBirthdate` AS `estBirthdate`,(date_format(from_days((to_days(now()) - to_days(`a`.`estBirthdate`))),'%Y') + 0) AS `age`,`lastWeight`.`vitalValue` AS `weight`,`a`.`note` AS `note`,`a`.`microchipNumber` AS `microchipNumber`,`a`.`dateImplanted` AS `dateImplanted`,`mt`.`microchipName` AS `microchipName`,`py`.`personality` AS `personality`,`as`.`adoptionStatus` AS `adoptionStatus`,`a`.`url` AS `url` from ((((`Animal` `a` left join `MicrochipType` `mt` on((`mt`.`microchipTypeID` = `a`.`microchipTypeID`))) left join `Personality` `py` on((`py`.`personalityID` = `a`.`personalityID`))) left join `AdoptionStatus` `as` on((`as`.`adoptionStatusID` = `a`.`adoptionStatusID`))) left join (select `vs`.`animalID` AS `animalID`,`vs`.`vitalValue` AS `vitalValue` from ((`VitalSign` `vs` join (select `VitalSign`.`animalID` AS `animalID`,max(`VitalSign`.`vitalDateTime`) AS `dt` from (`VitalSign` join `VitalSignType` on(((`VitalSign`.`vitalSignTypeID` = `VitalSignType`.`vitalSignTypeID`) and (`VitalSignType`.`vitalSignTypeName` = 'Weight')))) group by `VitalSign`.`animalID`) `inner_q` on(((`inner_q`.`animalID` = `vs`.`animalID`) and (`vs`.`vitalDateTime` = `inner_q`.`dt`)))) join `VitalSignType` on(((`VitalSignType`.`vitalSignTypeID` = `vs`.`vitalSignTypeID`) and (`VitalSignType`.`vitalSignTypeName` = 'Weight'))))) `lastWeight` on((`lastWeight`.`animalID` = `a`.`animalID`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `AnimalSurgeries`
--

/*!50001 DROP VIEW IF EXISTS `AnimalSurgeries`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb3 */;
/*!50001 SET character_set_results     = utf8mb3 */;
/*!50001 SET collation_connection      = utf8mb3_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `AnimalSurgeries` AS select `asg`.`surgeryTypeID` AS `surgeryTypeID`,`asg`.`animalID` AS `animalID`,`asg`.`surgeryDate` AS `surgeryDate`,`asg`.`note` AS `note`,`asg`.`personID` AS `personID`,`st`.`surgeryType` AS `surgeryType`,`a`.`animalName` AS `animalName`,`p`.`lastName` AS `lastName` from (((`AnimalSurgery` `asg` left join `Animal` `a` on((`asg`.`animalID` = `a`.`animalID`))) join `SurgeryType` `st` on((`st`.`surgeryTypeID` = `asg`.`surgeryTypeID`))) left join `Person` `p` on((`asg`.`personID` = `p`.`personID`))) order by `asg`.`surgeryDate` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `CountMeds`
--

/*!50001 DROP VIEW IF EXISTS `CountMeds`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb3 */;
/*!50001 SET character_set_results     = utf8mb3 */;
/*!50001 SET collation_connection      = utf8mb3_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `CountMeds` AS select `a`.`animalID` AS `animalID`,`m`.`medicationID` AS `medicationID`,`m`.`medicationName` AS `medicationName`,count(`p`.`startDate`) AS `countMeds` from ((`Animal` `a` join `Prescription` `p` on((`p`.`animalID` = `a`.`animalID`))) join `Medication` `m` on((`p`.`medicationID` = `m`.`medicationID`))) group by `m`.`medicationName` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `CurrentPositions`
--

/*!50001 DROP VIEW IF EXISTS `CurrentPositions`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb3 */;
/*!50001 SET character_set_results     = utf8mb3 */;
/*!50001 SET collation_connection      = utf8mb3_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `CurrentPositions` AS select `pp`.`positionTypeID` AS `positionTypeID`,`pp`.`personID` AS `personID`,`pp`.`note` AS `note`,`pp`.`startDate` AS `startDate`,`pt`.`positionName` AS `positionName` from (`PersonPosition` `pp` join `PositionType` `pt` on((`pt`.`positionTypeID` = `pp`.`positionTypeID`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `CurrentTransfer`
--

/*!50001 DROP VIEW IF EXISTS `CurrentTransfer`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `CurrentTransfer` AS select `t`.`animalID` AS `animalID`,`t`.`personID` AS `personID`,`t`.`transferDate` AS `transferDate`,`t`.`transferTypeID` AS `transferTypeID`,`t`.`fee` AS `fee`,`t`.`note` AS `note`,`a`.`animalName` AS `animalName`,`a`.`microchipNumber` AS `microchipNumber`,`a`.`adoptionStatusID` AS `adoptionStatusID`,if(`p`.`isOrg`,`p`.`lastName`,concat(`p`.`firstName`,' ',`p`.`lastName`)) AS `CurrentPerson`,`tt`.`transferName` AS `transferName`,`tt`.`pixieResponsible` AS `pixieResponsible`,(case `a`.`species` when 'D' then 'Dog' when 'C' then 'Cat' end) AS `speciesName`,`a`.`species` AS `species`,(case `a`.`gender` when 'F' then 'Female' when 'M' then 'Male' else 'Unknown' end) AS `genderName`,`a`.`gender` AS `gender`,(case `a`.`isFixed` when 1 then 'Yes' when 0 then 'No' end) AS `Fixed`,`a`.`isFixed` AS `isFixed`,`a`.`estBirthdate` AS `estBirthdate`,(case `tt`.`adoptable` when 'Y' then 'Yes' else 'No' end) AS `Adoptable`,(case (`p`.`personID` = 1) when 1 then `st`.`adoptionStatus` else `tt`.`transferName` end) AS `Status` from (((((`innerCurrentTransfer` `cp` join `Transfer` `t` on(((`t`.`animalID` = `cp`.`animalID`) and (`cp`.`transferDate` = `t`.`transferDate`)))) join `Animal` `a` on((`a`.`animalID` = `t`.`animalID`))) join `Person` `p` on((`t`.`personID` = `p`.`personID`))) join `AdoptionStatus` `st` on((`a`.`adoptionStatusID` = `st`.`adoptionStatusID`))) join `TransferType` `tt` on((`t`.`transferTypeID` = `tt`.`transferTypeID`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `Medications`
--

/*!50001 DROP VIEW IF EXISTS `Medications`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb3 */;
/*!50001 SET character_set_results     = utf8mb3 */;
/*!50001 SET collation_connection      = utf8mb3_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `Medications` AS select `a`.`animalID` AS `animalID`,`p`.`startDate` AS `startdate`,`m`.`medicationName` AS `medicationname` from ((`Animal` `a` join `Prescription` `p` on((`p`.`animalID` = `a`.`animalID`))) join `Medication` `m` on((`p`.`medicationID` = `m`.`medicationID`))) where (`m`.`isVaccination` = 0) order by `p`.`startDate` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `TestView`
--

/*!50001 DROP VIEW IF EXISTS `TestView`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb3 */;
/*!50001 SET character_set_results     = utf8mb3 */;
/*!50001 SET collation_connection      = utf8mb3_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `TestView` AS select `t`.`testDate` AS `testDate`,`t`.`testResult` AS `testResult`,`t`.`note` AS `note`,`t`.`testTypeID` AS `testTypeID`,`t`.`animalID` AS `animalID`,`tt`.`testName` AS `testName`,`tt`.`desiredResult` AS `desiredResult` from (`Test` `t` left join `TestType` `tt` on((`t`.`testTypeID` = `tt`.`testTypeID`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `TransferHistory`
--

/*!50001 DROP VIEW IF EXISTS `TransferHistory`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb3 */;
/*!50001 SET character_set_results     = utf8mb3 */;
/*!50001 SET collation_connection      = utf8mb3_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `TransferHistory` AS select `a`.`animalName` AS `animalName`,`tt`.`transferName` AS `transferName`,`t`.`animalID` AS `animalID`,`t`.`personID` AS `personID`,`t`.`transferDate` AS `transferDate`,`t`.`transferTypeID` AS `transferTypeID`,`tt`.`adoptable` AS `adoptable`,`t`.`fee` AS `fee`,`t`.`note` AS `note`,if(((`p`.`firstName` is null) or (0 <> `p`.`isOrg`)),`p`.`lastName`,concat(`p`.`firstName`,' ',`p`.`lastName`)) AS `Name` from (((`Animal` `a` join `Transfer` `t` on((`a`.`animalID` = `t`.`animalID`))) join `TransferType` `tt` on((`t`.`transferTypeID` = `tt`.`transferTypeID`))) join `Person` `p` on((`t`.`personID` = `p`.`personID`))) order by `t`.`transferDate` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `innerCurrentTransfer`
--

/*!50001 DROP VIEW IF EXISTS `innerCurrentTransfer`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb3 */;
/*!50001 SET character_set_results     = utf8mb3 */;
/*!50001 SET collation_connection      = utf8mb3_general_ci */;
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

-- Dump completed on 2025-06-23 20:48:59
