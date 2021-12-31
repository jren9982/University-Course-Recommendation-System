-- MySQL dump 10.16  Distrib 10.1.26-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: db
-- ------------------------------------------------------
-- Server version	10.1.26-MariaDB-0+deb9u1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

DROP DATABASE IF EXISTS ICT2103UniDB;
CREATE DATABASE ICT2103UniDB;
USE ICT2103UniDB;

--
-- Table structure for table `User`
--

DROP TABLE IF EXISTS `User`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `User` (
  `email` varchar(255) NOT NULL PRIMARY KEY,
  `name` varchar(255) NOT NULL ,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `User`
--

LOCK TABLES `User` WRITE;
/*!40000 ALTER TABLE `User` DISABLE KEYS */;
INSERT INTO `User` VALUES ('user@email.com','user','u$er');
/*!40000 ALTER TABLE `User` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Uni_CCA`
--

DROP TABLE IF EXISTS `Uni_CCA`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Uni_CCA` (
  `cca_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT NOT NULL ,
  `uid` INT NOT NULL,
  `cca_name` varchar(50) DEFAULT NULL,
  CONSTRAINT fk_category_id_cca
		FOREIGN KEY (category_id) REFERENCES Uni_CCA_categories (category_id)
        ON DELETE CASCADE
        ON UPDATE RESTRICT,
  CONSTRAINT fk_uid_cca
		FOREIGN KEY (uid) REFERENCES Uni_List (uid)
        ON DELETE CASCADE
		ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Uni_CCA`
--

LOCK TABLES `Uni_CCA` WRITE;
/*!40000 ALTER TABLE `Uni_CCA` DISABLE KEYS */;
INSERT INTO `Uni_CCA` VALUES (1,1,1,'Chinese Drama'),(2,1,1,'Chinese Society'),(3,1,1,'Chinese Orchestra'),(4,1,1,'Guitar Orchestra'),(5,1,1,'Harmonica Orchestra'),(6,1,1,'Symphony Orchestra'),(7,1,1,'English Drama'),(8,1,1,'Choir Ensemble'),(9,1,1,'DJ Collective'),(10,1,1,'Dance Ensemble'),(11,1,1,'Indian Dance'),(12,2,1,'Archery'),(13,2,1,'Badminton'),(14,2,1,'Basketball'),(15,2,1,'Bowling'),(16,2,1,'Cross-Country'),(17,2,1,'Dragonboat'),(18,2,1,'Sailing'),(19,2,1,'Shooting'),(20,2,1,'Swimming'),(21,2,1,'Volleyball'),(22,3,1,'Buddhist Society'),(23,3,1,'Catholic Society'),(24,3,1,'Christian Society'),(25,3,1,'Hindu Society'),(26,3,1,'Muslim Society'),(27,3,1,'Sikh Society'),(28,4,1,'Drone Club'),(29,4,1,'Game Development'),(30,4,1,'Greyhats Club'),(31,4,1,'Hackers Club'),(32,4,1,'Space Club'),(33,4,1,'Climate Change Club'),(34,4,1,'Lionhearter Club'),(35,4,1,'Red Cross Youth'),(36,4,1,'Rotaract Club'),(37,1,2,'Chinese Society'),(38,1,2,'French Society'),(39,1,2,'German Society'),(40,1,2,'Japanese Appreciation Club'),(41,1,2,'Drama Club'),(42,1,2,'Visual Arts Society'),(43,1,2,'Chinese Dance'),(44,1,2,'Chinese Drum'),(45,1,2,'Chinese Orchestra'),(46,1,2,'Dancesport'),(47,1,2,'Lion Dance'),(48,1,2,'Symphony Orchestra'),(49,1,2,'Wushu'),(50,2,2,'Bowling'),(51,2,2,'Archery'),(52,2,2,'Chess'),(53,2,2,'Fencing'),(54,2,2,'Floorball'),(55,2,2,'Golf'),(56,2,2,'Inline Skating'),(57,2,2,'Judo'),(58,2,2,'Tennis'),(59,2,2,'Yachting'),(60,3,2,'Buddhist Society'),(61,3,2,'Christian Society'),(62,3,2,'Muslim Society'),(63,4,2,'Animal Lovers\' Society'),(64,4,2,'Aerospace Society'),(65,4,2,'Anglers Club'),(66,4,2,'Astronomical Society'),(67,4,2,'Board Games Society'),(68,4,2,'Cats Management Network'),(69,4,2,'Debate Society'),(70,4,2,'Film Society'),(71,1,3,'Visual Arts Society'),(72,1,3,'Dancesport'),(73,1,3,'Chinese Orchestra'),(74,1,3,'Guitar Orchestra'),(75,1,3,'Literature Club'),(76,1,3,'Malay Cultural Club'),(77,1,3,'Symphony Orchestra'),(78,1,3,'Choir Ensemble'),(79,2,3,'Aikido'),(80,2,3,'Archery'),(81,2,3,'Badminton'),(82,2,3,'Basketball'),(83,2,3,'Bowling'),(84,2,3,'Climbing'),(85,2,3,'Dragonboat'),(86,2,3,'Fencing'),(87,2,3,'Floorball'),(88,2,3,'Golf'),(89,2,3,'Handball'),(90,2,3,'Kendo'),(91,2,3,'Rugby'),(92,2,3,'Sailing'),(93,2,3,'Squash'),(94,2,3,'Ultimate Frisbee'),(95,3,3,'Buddhist Society'),(96,3,3,'Christian Society'),(97,3,3,'Muslim Society'),(98,3,3,'Sikh Society'),(99,4,3,'Red Cross Youth'),(100,4,3,'Rotaract Club'),(101,4,3,'Debate Society'),(102,4,3,'Animal Lovers\' Society'),(103,4,3,'Wine Appreciation Club'),(104,4,3,'Women\'s Connection'),  (105, 5, 3, 'Chao Vietnam'), (106, 5, 3, 'Connect China'), (107, 5, 3, 'SMU Francophiles');
/*!40000 ALTER TABLE `Uni_CCA` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Uni_CCA_categories`
--

DROP TABLE IF EXISTS `Uni_CCA_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Uni_CCA_categories` (
  `category_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `category_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Uni_CCA_categories`
--

LOCK TABLES `Uni_CCA_categories` WRITE;
/*!40000 ALTER TABLE `Uni_CCA_categories` DISABLE KEYS */;
INSERT INTO `Uni_CCA_categories` VALUES (1,'Arts & Culture'),(2,'Sports'),(3,'Religion'),(4,'Interest Clubs'), (5, 'International Clubs');
/*!40000 ALTER TABLE `Uni_CCA_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Uni_Courses`
--

DROP TABLE IF EXISTS `Uni_Courses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Uni_Courses` (
  `course_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `uid` INT NOT NULL,
  `course_name` varchar(100) DEFAULT NULL,
  `gpa10pct` varchar(10) DEFAULT NULL,
  `gpa90pct` varchar(10) DEFAULT NULL,
  CONSTRAINT fk_uid_courses
		FOREIGN KEY (uid) REFERENCES Uni_List (uid)
        ON DELETE CASCADE
		ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Uni_Courses`
--

LOCK TABLES `Uni_Courses` WRITE;
/*!40000 ALTER TABLE `Uni_Courses` DISABLE KEYS */;
INSERT INTO `Uni_Courses` VALUES (1,1,'Law','3.86','3.93'),(2,1,'Medicine','3.94','4'),(3,1,'Nursing','3.33','3.86'),(4,1,'Architecture','3.59','3.92'),(5,1,'Industrial Design','3.88','3.97'),(6,1,'Project & Facilities Management','3.59','3.84'),(7,1,'Real Estate','3.58','3.83'),(8,1,'Biomedical Engineering','3.71','3.92'),(9,1,'Chemical Engineering','3.65','3.95'),(10,1,'Civil Engineering','3.71','3.95'),(11,1,'Electrical Engineering','3.68','3.94'),(12,1,'Engineering Science','3.83','3.99'),(13,1,'Environmental Engineering','3.57','3.94'),(14,1,'Materials Science & Engineering','3.76','3.93'),(15,1,'Mechanical Engineering','3.65','3.94'),(16,1,'Computing(Business Analytics)','3.81','3.95'),(17,1,'Computing(Computer Science)','3.86','3.98'),(18,1,'Computing(Information Security)','3.84','3.97'),(19,1,'Computing(Informations Systems)','3.82','3.99'),(20,1,'Computer Engineering','3.87','3.97'),(21,1,'Science(Chemistry)','3.85','3.96'),(22,1,'Science(Life Sciences)','3.83','3.97'),(23,1,'Business Admin','3.73','3.95'),(24,1,'Businesss Admin(Accountancy)','3.76','3.97'),(25,1,'Arts & Social Sciences','3.67','3.92'),(26,2,'Aerospace Engineering','3.49','3.93'),(27,2,'Bioengineering','3.69','3.96'),(28,2,'Chemical & Biomolecular Engineering','3.67','3.98'),(29,2,'Civil Engineering','3.44','3.97'),(30,2,'Computer Engineering','3.58','3.95'),(31,2,'Computer Science','3.56','3.95'),(32,2,'Electrical & Electronic Engineering','3.43','3.94'),(33,2,'Environmental Engineering','3.46','3.81'),(34,2,'Information Engineering & Media','3.49','3.98'),(35,2,'Maritime Studies','3.54','3.92'),(36,2,'Materials Engineering','3.38','3.88'),(37,2,'Mechanical Engineering','3.4','3.91'),(38,2,'Double Major Programmes for Science','3.84','3.99'),(39,2,'Biological Sciences','3.71','3.96'),(40,2,'Chemistry & Biological Chemistry','3.49','3.88'),(41,2,'Physics/Applied Physics','3.52','3.97'),(42,2,'Accountancy','3.62','3.95'),(43,2,'Business','3.62','3.94'),(44,2,'Art, Design & Media','3.3','3.83'),(45,2,'Chinese','3.41','3.81'),(46,2,'Communication Studies','3.68','3.93'),(47,2,'Economics','3.51','3.84'),(48,2,'English','3.39','3.71'),(49,2,'Linguistics & Multilingual Studies','3.38','3.73'),(50,2,'Philosophy','3.31','3.6'),(51,2,'Psychology','3.67','3.92'),(52,2,'Public Policy & Global Affairs','3.56','3.95'),(53,2,'Sociology','3.47','3.77'),(54,2,'Sports Science & Management','3.54','3.93'),(55,3,'Bachelor of Accountancy','3.68','3.95'),(56,3,'Bachelor of Business Management','3.68','3.94'),(57,3,'Bachelor of Science (Economics)','3.69','3.92'),(58,3,'Bachelor of Science (Information Systems)','3.57','3.92'),(59,3,'Bachelor of Science (Computer Science)','3.75','3.96'),(60,3,'Bachelor of Social Science','3.66','3.91'),(61,1,'Dentistry','NUL','NUL'),(62,1,'Engineering','NUL','NUL'),(63,1,'Industrial & Systems Engineering','NUL','NUL'),(64,1,'Pharmaceutical Science','NUL','NUL'),(65,1,'Pharmacy','NUL','NUL'),(66,1,'Environmental Studies','NUL','NUL'),(67,2,'Medicine','NUL','NUL'),(68,2,'Renaissance Engineering','NUL','NUL'),(69,2,'Data Science & Artificial Intelligence','NUL','NUL'),(70,2,'Engineering','NUL','NUL'),(71,2,'Environmental Earth Systems Science','NUL','NUL'),(72,2,'Mathematical Sciences','NUL','NUL'),(73,2,'Double Major Programmes for Humanities, Arts & Social Sciences','NUL','NUL'),(74,2,'History','NUL','NUL'),(75,2,'Arts (Education(NIE))','NUL','NUL'),(76,2,'Science (Education (NIE))','NUL','NUL'),(77,3,'Bachelor of Laws','NUL','NUL');
/*!40000 ALTER TABLE `Uni_Courses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Uni_Location`
--

DROP TABLE IF EXISTS `Uni_Location`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Uni_Location` (
  `lid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
--   `uid` INT DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL
--   CONSTRAINT fk_uid_location
-- 		FOREIGN KEY (uid) REFERENCES Uni_List (uid)
--      ON DELETE CASCADE
-- 		ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Uni_Location`
--

LOCK TABLES `Uni_Location` WRITE;
/*!40000 ALTER TABLE `Uni_Location` DISABLE KEYS */;
-- INSERT INTO `Uni_Location` VALUES (1,1,'Southwest'),(2,2,'West'),(3,3,'Central');
INSERT INTO `Uni_Location` VALUES (1,'Southwest'),(2,'West'),(3,'Central');
/*!40000 ALTER TABLE `Uni_Location` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Uni_List`
--

DROP TABLE IF EXISTS `Uni_List`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Uni_List` (
  `uid` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `lid` INT NOT NULL,
  `uni_name` varchar(50) DEFAULT NULL,
  CONSTRAINT fk_lid_list
	FOREIGN KEY (lid) REFERENCES Uni_Location (lid)
	ON DELETE CASCADE
	ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Uni_List`
--

LOCK TABLES `Uni_List` WRITE;
/*!40000 ALTER TABLE `Uni_List` DISABLE KEYS */;
INSERT INTO `Uni_List` VALUES (1,1,'NUS'),(2,2,'NTU'),(3,3,'SMU');
/*!40000 ALTER TABLE `Uni_List` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Uni_Vacancies`
--

DROP TABLE IF EXISTS `Uni_Vacancies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Uni_Vacancies` (
  `course_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `vacancies` INT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Uni_Vacancies`
--

LOCK TABLES `Uni_Vacancies` WRITE;
/*!40000 ALTER TABLE `Uni_Vacancies` DISABLE KEYS */;
INSERT INTO `Uni_Vacancies` VALUES (1,223),(2,280),(3,311),(4,160),(5,48),(6,167),(7,165),(8,161),(9,253),(10,133),(11,207),(12,35),(13,47),(14,79),(15,346),(16,241),(17,596),(18,53),(19,124),(20,181),(21,571),(22,571),(23,765),(24,220),(25,1510),(26,124),(27,102),(28,174),(29,120),(30,99),(31,424),(32,608),(33,35),(34,97),(35,86),(36,211),(37,540),(38,63),(39,263),(40,231),(41,80),(42,507),(43,837),(44,145),(45,73),(46,168),(47,163),(48,90),(49,82),(50,59),(51,134),(52,65),(53,139),(54,74),(55,349),(56,840),(57,264),(58,448),(59,49),(60,244),(61,60),(62,56),(63,65),(64,48),(65,156),(66,47),(67,149),(68,55),(69,52),(70,33),(71,34),(72,152),(73,57),(74,49),(75,28),(76,29),(77,174);
/*!40000 ALTER TABLE `Uni_Vacancies` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-08-22 15:20:25
