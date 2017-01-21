-- MySQL dump 10.13  Distrib 5.7.17, for Linux (x86_64)
--
-- Host: localhost    Database: packsan
-- ------------------------------------------------------
-- Server version	5.7.17-0ubuntu0.16.04.1

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
-- Table structure for table `actionlog`
--

DROP TABLE IF EXISTS `actionlog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `actionlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `log_text` varchar(255) DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `timestamp` int(11) DEFAULT NULL,
  `game_id` int(11) DEFAULT NULL,
  `log_level` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_actionlog_team1_idx` (`team_id`),
  KEY `fk_actionlog_game1_idx` (`game_id`),
  CONSTRAINT `fk_actionlog_game1` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_actionlog_team1` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `actionlog`
--

LOCK TABLES `actionlog` WRITE;
/*!40000 ALTER TABLE `actionlog` DISABLE KEYS */;
INSERT INTO `actionlog` VALUES (1,'Team logged in.',1,1485008899,NULL,1);
/*!40000 ALTER TABLE `actionlog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `game`
--

DROP TABLE IF EXISTS `game`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `game` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `description` text,
  `duration` int(11) DEFAULT NULL,
  `game_subject_id` int(11) NOT NULL,
  `level_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_game_game_subject1_idx` (`game_subject_id`),
  KEY `fk_game_level1_idx` (`level_id`),
  KEY `fk_game_location1_idx` (`location_id`),
  CONSTRAINT `fk_game_game_subject1` FOREIGN KEY (`game_subject_id`) REFERENCES `game_subject` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_game_level1` FOREIGN KEY (`level_id`) REFERENCES `level` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_game_location1` FOREIGN KEY (`location_id`) REFERENCES `location` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `game`
--

LOCK TABLES `game` WRITE;
/*!40000 ALTER TABLE `game` DISABLE KEYS */;
/*!40000 ALTER TABLE `game` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `game_subject`
--

DROP TABLE IF EXISTS `game_subject`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `game_subject` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `description` varchar(45) DEFAULT NULL,
  `done_color` varchar(45) DEFAULT NULL,
  `todo_color` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `game_subject`
--

LOCK TABLES `game_subject` WRITE;
/*!40000 ALTER TABLE `game_subject` DISABLE KEYS */;
INSERT INTO `game_subject` VALUES (1,'Die Elemente und wir',NULL,NULL,NULL),(2,'Wir in der Stadt',NULL,NULL,NULL),(3,'Damals war alles besser',NULL,NULL,NULL),(4,'Anders ist Einzig, nicht artig',NULL,NULL,NULL),(5,'Irgendwas mit Gott',NULL,NULL,NULL),(6,'Technik die begeistert',NULL,NULL,NULL),(7,'Wir in Aktion',NULL,NULL,NULL),(8,'Pack’s An',NULL,NULL,NULL),(9,'Wir haben Phantasie',NULL,NULL,NULL),(10,'Gefühlsecht',NULL,NULL,NULL),(11,'Wir miteinander',NULL,NULL,NULL),(12,'Baden-Powell 2.0',NULL,NULL,NULL),(13,'Feuer im Hintern!?',NULL,NULL,NULL),(14,'Bild dir deine Meinung!',NULL,NULL,NULL),(15,'Zukunftsmusik',NULL,NULL,NULL),(16,'Be A Star',NULL,NULL,NULL),(17,'Wasser macht nass',NULL,NULL,NULL),(18,'Mehr Meer, mehr Mär',NULL,NULL,NULL),(19,'Iss, Kind!',NULL,NULL,NULL),(20,'Ein bischen Frieden',NULL,NULL,NULL),(21,'Die Erde ist rund',NULL,NULL,NULL),(22,'Keine Luft zum Atmen',NULL,NULL,NULL),(23,'We are the world',NULL,NULL,NULL);
/*!40000 ALTER TABLE `game_subject` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `joker`
--

DROP TABLE IF EXISTS `joker`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `joker` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jokercode` varchar(32) DEFAULT NULL,
  `joker_used` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `joker`
--

LOCK TABLES `joker` WRITE;
/*!40000 ALTER TABLE `joker` DISABLE KEYS */;
INSERT INTO `joker` VALUES (1,'788ac26d2b0ad74d11789136ee2912e6',0),(2,'2a5af6f342196932440469aec3717bdb',0),(3,'2cae7a1c098662e8edc54d117a6fba11',0);
/*!40000 ALTER TABLE `joker` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `level`
--

DROP TABLE IF EXISTS `level`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `level` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `number` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `level`
--

LOCK TABLES `level` WRITE;
/*!40000 ALTER TABLE `level` DISABLE KEYS */;
INSERT INTO `level` VALUES (1,'Level 1',1),(2,'Level 2',2),(3,'Level 3',3),(4,'Level 4',4),(5,'Level 5',5),(6,'Level 6',6),(7,'Level 7',7);
/*!40000 ALTER TABLE `level` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `location`
--

DROP TABLE IF EXISTS `location`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `location` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `description` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `location`
--

LOCK TABLES `location` WRITE;
/*!40000 ALTER TABLE `location` DISABLE KEYS */;
/*!40000 ALTER TABLE `location` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `member`
--

DROP TABLE IF EXISTS `member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `grade` varchar(45) DEFAULT NULL,
  `passcode` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_member_team1_idx` (`team_id`),
  CONSTRAINT `fk_member_team1` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `member`
--

LOCK TABLES `member` WRITE;
/*!40000 ALTER TABLE `member` DISABLE KEYS */;
INSERT INTO `member` VALUES (1,'Peter Pan',NULL,'woe','68d5c9dfbb3d45ecd77d609b8eca1ebd'),(2,'Klaus',NULL,'woe','a3715699b7c3b00b81d64799245f0f47'),(3,'Claudia',NULL,'woe','5f8dd29a3fc26e057b5d3a179b501432');
/*!40000 ALTER TABLE `member` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `message`
--

DROP TABLE IF EXISTS `message`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `team_id` int(11) DEFAULT NULL,
  `game_id` int(11) DEFAULT NULL,
  `message_text` text,
  `send_time` datetime DEFAULT NULL,
  `read_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_message_team1_idx` (`team_id`),
  KEY `fk_message_game1_idx` (`game_id`),
  CONSTRAINT `fk_message_game1` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_message_team1` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `message`
--

LOCK TABLES `message` WRITE;
/*!40000 ALTER TABLE `message` DISABLE KEYS */;
/*!40000 ALTER TABLE `message` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `team`
--

DROP TABLE IF EXISTS `team`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `team` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `passcode` varchar(45) DEFAULT NULL,
  `member_of_team` varchar(45) DEFAULT NULL,
  `current_level` int(11) NOT NULL,
  `count_persons` varchar(45) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_team_level1_idx` (`current_level`),
  CONSTRAINT `fk_team_level1` FOREIGN KEY (`current_level`) REFERENCES `level` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team`
--

LOCK TABLES `team` WRITE;
/*!40000 ALTER TABLE `team` DISABLE KEYS */;
INSERT INTO `team` VALUES (1,'a95c530a7af5f492a74499e70578d150','a95c530a7af5f492a74499e70578d150',1,'3',1);
/*!40000 ALTER TABLE `team` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `team_level`
--

DROP TABLE IF EXISTS `team_level`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `team_level` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `start_time` int(11) DEFAULT NULL,
  `finish_time` int(11) DEFAULT NULL,
  `team_id` int(11) NOT NULL,
  `level_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_team_level_team_idx` (`team_id`),
  KEY `fk_team_level_level1_idx` (`level_id`),
  CONSTRAINT `fk_team_level_level1` FOREIGN KEY (`level_id`) REFERENCES `level` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_team_level_team` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team_level`
--

LOCK TABLES `team_level` WRITE;
/*!40000 ALTER TABLE `team_level` DISABLE KEYS */;
/*!40000 ALTER TABLE `team_level` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `team_level_game`
--

DROP TABLE IF EXISTS `team_level_game`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `team_level_game` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `team_level_id` int(11) NOT NULL,
  `assigned_game_subject` int(11) NOT NULL,
  `played_points` int(11) DEFAULT NULL,
  `start_time` int(11) DEFAULT NULL,
  `finish_time` int(11) DEFAULT NULL,
  `used_joker` int(11) NOT NULL,
  `assigned_game` int(11) NOT NULL,
  `solved_by_leveljump` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`,`used_joker`),
  KEY `fk_team_level_game_team_level1_idx` (`team_level_id`),
  KEY `fk_team_level_game_game_subject1_idx` (`assigned_game_subject`),
  KEY `fk_team_level_game_joker1_idx` (`used_joker`),
  KEY `fk_team_level_game_game1_idx` (`assigned_game`),
  CONSTRAINT `fk_team_level_game_game1` FOREIGN KEY (`assigned_game`) REFERENCES `game` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_team_level_game_game_subject1` FOREIGN KEY (`assigned_game_subject`) REFERENCES `game_subject` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_team_level_game_joker1` FOREIGN KEY (`used_joker`) REFERENCES `joker` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_team_level_game_team_level1` FOREIGN KEY (`team_level_id`) REFERENCES `team_level` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team_level_game`
--

LOCK TABLES `team_level_game` WRITE;
/*!40000 ALTER TABLE `team_level_game` DISABLE KEYS */;
/*!40000 ALTER TABLE `team_level_game` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `terminal`
--

DROP TABLE IF EXISTS `terminal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `terminal` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '			',
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `terminal`
--

LOCK TABLES `terminal` WRITE;
/*!40000 ALTER TABLE `terminal` DISABLE KEYS */;
/*!40000 ALTER TABLE `terminal` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-01-21 16:09:39
