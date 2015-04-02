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
-- Dumping data for table `AdoptionStatus`
--

LOCK TABLES `AdoptionStatus` WRITE;
/*!40000 ALTER TABLE `AdoptionStatus` DISABLE KEYS */;
INSERT INTO `AdoptionStatus` VALUES ('A','Available'),('M','Meet'),('N','Not ready'),('P','Pending Eval');
/*!40000 ALTER TABLE `AdoptionStatus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `DogSize`
--

LOCK TABLES `DogSize` WRITE;
/*!40000 ALTER TABLE `DogSize` DISABLE KEYS */;
INSERT INTO `DogSize` VALUES ('Toy',0,10),('Small',10,25),('Medium',25,60),('Large',60,150),('ExtraLarge',150,400);
/*!40000 ALTER TABLE `DogSize` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `Medication`
--

LOCK TABLES `Medication` WRITE;
/*!40000 ALTER TABLE `Medication` DISABLE KEYS */;
INSERT INTO `Medication` VALUES (1,'DHPP','Distemper, hepatitis, parvovirus and parainfluenza','1',''),(2,'Bordatella','','1',''),(3,'Rabies','','1',''),(4,'Flea','','1',''),(5,'Pyrantel','dewormer','1',''),(16,'FVRCP','','1','C'),(17,'Leptospirosis','','1','D'),(18,'Heartworm','','1','D');
/*!40000 ALTER TABLE `Medication` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `MicrochipType`
--

LOCK TABLES `MicrochipType` WRITE;
/*!40000 ALTER TABLE `MicrochipType` DISABLE KEYS */;
INSERT INTO `MicrochipType` VALUES (1,'24PetWatch'),(2,'AKC'),(3,'Avid'),(4,'Avid Euro'),(5,'Banfield'),(6,'Bayer ResQ'),(7,'HomeAgain'),(8,'Found Animals'),(9,'Datamars'),(10,'Other/Unknown');
/*!40000 ALTER TABLE `MicrochipType` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `Personality`
--

LOCK TABLES `Personality` WRITE;
/*!40000 ALTER TABLE `Personality` DISABLE KEYS */;
INSERT INTO `Personality` VALUES ('A','Affectionate'),('C','Calm'),('E','Energetic'),('I','Independent'),('P','Playful');
/*!40000 ALTER TABLE `Personality` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `PositionType`
--

LOCK TABLES `PositionType` WRITE;
/*!40000 ALTER TABLE `PositionType` DISABLE KEYS */;
INSERT INTO `PositionType` VALUES (1,'Employee'),(2,'Volunteer'),(3,'Foster parent'),(4,'Supporter'),(5,'Vet'),(6,'Technitian'),(7,'Other');
/*!40000 ALTER TABLE `PositionType` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `SurgeryType`
--

LOCK TABLES `SurgeryType` WRITE;
/*!40000 ALTER TABLE `SurgeryType` DISABLE KEYS */;
INSERT INTO `SurgeryType` VALUES (1,'Spay/Neuter'),(2,'Dental');
/*!40000 ALTER TABLE `SurgeryType` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `TestType`
--

LOCK TABLES `TestType` WRITE;
/*!40000 ALTER TABLE `TestType` DISABLE KEYS */;
INSERT INTO `TestType` VALUES (1,'Heartworm','','negative'),(2,'FELV','C','negative'),(3,'FIV','C','negative'),(4,'Fecal','','negative');
/*!40000 ALTER TABLE `TestType` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `TransferType`
--

LOCK TABLES `TransferType` WRITE;
/*!40000 ALTER TABLE `TransferType` DISABLE KEYS */;
INSERT INTO `TransferType` VALUES (1,'Pixie','Y','Y','P'),(2,'Trial','N','Y','N'),(3,'Adopted','N',NULL,'N'),(4,'Other Shelter','N','N','Y'),(5,'Foster','Y','Y',''),(6,'Previous Owner','',NULL,'N'),(7,'Euthanasia','N','N','Y'),(13,'Other','',NULL,'');
/*!40000 ALTER TABLE `TransferType` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `VitalSignType`
--

LOCK TABLES `VitalSignType` WRITE;
/*!40000 ALTER TABLE `VitalSignType` DISABLE KEYS */;
INSERT INTO `VitalSignType` VALUES (1,'Heart Rate','HR','D',70.0,160.0,'bpm'),(2,'Heart Rate','HR','C',150.0,240.0,'bpm'),(3,'Respiratory Rate','RR','D',10.0,30.0,'breaths per minute'),(4,'Respiratory Rate','RR','C',20.0,30.0,'breaths per minute'),(5,'Temperature','Temp','',101.0,102.5,'F'),(7,'Weight','Weight','',0.0,0.0,'lbs');
/*!40000 ALTER TABLE `VitalSignType` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-03-22 16:04:23
