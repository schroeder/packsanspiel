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
  `log_text` varchar(1024) DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `timestamp` int(11) DEFAULT NULL,
  `game_id` int(11) DEFAULT NULL,
  `log_level` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_744C5DC0E48FD905` (`game_id`),
  KEY `fk_actionlog_team1_idx` (`team_id`),
  KEY `fk_actionlog_game1_idx` (`game_id`),
  CONSTRAINT `fk_actionlog_game1` FOREIGN KEY (`game_id`) REFERENCES `game` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_actionlog_team1` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=240 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `actionlog`
--

LOCK TABLES `actionlog` WRITE;
/*!40000 ALTER TABLE `actionlog` DISABLE KEYS */;
INSERT INTO `actionlog` VALUES (1,'Team logged in.',1,1485008899,NULL,1),(2,'Failed admin login try with qr code dde3259db83f1c5a86720338341e8608!',NULL,1485026985,NULL,3),(3,'Failed admin login try with qr code dde3259db83f1c5a86720338341e8608!',NULL,1485026986,NULL,3),(4,'Failed admin login try with qr code dde3259db83f1c5a86720338341e8608!',NULL,1485027146,NULL,3),(5,'Failed team login try with qr code 1a1ce120f74c03216b2bcb575cc7b592!',NULL,1485027172,NULL,2),(6,'Failed team login try with qr code 1a1ce120f74c03216b2bcb575cc7b592!',NULL,1485027173,NULL,2),(7,'Failed team login try with qr code 1a1ce120f74c03216b2bcb575cc7b592!',NULL,1485027173,NULL,2),(8,'Failed team login try with qr code 1a1ce120f74c03216b2bcb575cc7b592!',NULL,1485027180,NULL,2),(9,'Failed team login try with qr code 1a1ce120f74c03216b2bcb575cc7b592!',NULL,1485027233,NULL,2),(10,'Failed team login try with qr code 1a1ce120f74c03216b2bcb575cc7b592!',NULL,1485027240,NULL,2),(11,'Failed team login try with qr code 1a1ce120f74c03216b2bcb575cc7b592!',NULL,1485027240,NULL,2),(12,'Failed team login try with qr code 1a1ce120f74c03216b2bcb575cc7b592!',NULL,1485027240,NULL,2),(13,'Failed team login try with qr code 1a1ce120f74c03216b2bcb575cc7b592!',NULL,1485027265,NULL,2),(14,'Failed team login try with qr code 1a1ce120f74c03216b2bcb575cc7b592!',NULL,1485027275,NULL,2),(15,'Failed team login try with qr code 1a1ce120f74c03216b2bcb575cc7b592!',NULL,1485027276,NULL,2),(16,'Failed team login try with qr code 1a1ce120f74c03216b2bcb575cc7b592!',NULL,1485027365,NULL,2),(17,'Failed team login try with qr code 1a1ce120f74c03216b2bcb575cc7b592!',NULL,1485027416,NULL,2),(18,'Failed team login try with qr code 1a1ce120f74c03216b2bcb575cc7b592!',NULL,1485027424,NULL,2),(19,'Failed team login try with qr code 07f942ee7d5e4f8a341236a199781eec!',NULL,1485027435,NULL,2),(20,'Failed team login try with qr code 07f942ee7d5e4f8a341236a199781eec!',NULL,1485027436,NULL,2),(21,'Failed team login try with qr code 07f942ee7d5e4f8a341236a199781eec!',NULL,1485029074,NULL,2),(22,'Registration: Cannot find member to add member:5f8dd29a3fc26e057b5d3a179b501432.',NULL,1485035612,NULL,3),(23,'Registration: Cannot find team to add 1a1ce120f74c03216b2bcb575cc7b592.',NULL,1485037959,NULL,3),(24,'Registration: Cannot find team to add 1a1ce120f74c03216b2bcb575cc7b592.',NULL,1485038562,NULL,3),(25,'Registration: Team initializedCannot find team to add 1a1ce120f74c03216b2bcb575cc7b592.',2,1485038693,NULL,3),(26,'Unable to log in team . ',2,1485039801,NULL,3),(27,'Failed team login try with qr code 1a1ce120f74c03216b2bcb575cc7b592!',NULL,1485040099,NULL,2),(28,'Unable to log in team . ',2,1485041137,NULL,3),(29,'Unable to log in team . ',2,1485041188,NULL,3),(30,'Team logged in . ',2,1485041202,NULL,1),(31,'Team logged in . ',2,1485082317,NULL,1),(32,'Team logged in . ',2,1485082318,NULL,1),(33,'Team logged in . ',2,1485082318,NULL,1),(34,'Registration: Cannot find member with passcode eb9c618431ffd099703ad9a4600130c9.',NULL,1485258936,NULL,3),(35,'Registration: Cannot find member with passcode eb9c618431ffd099703ad9a4600130c9.',NULL,1485258960,NULL,3),(36,'Registration: Cannot find member with passcode eB9c618431ffd099703ad9a4600130c9.',NULL,1485258960,NULL,3),(37,'Registration: Cannot find member with passcode eB9c618431ffd099703ad9a4600130c9.',NULL,1485258983,NULL,3),(38,'Registration: Cannot find member with passcode eB9c618431ffd099703ad9a4600130c9.',NULL,1485258983,NULL,3),(39,'Registration: Cannot find member with passcode eB9c618431ffd099703ad9a4600130c9.',NULL,1485259039,NULL,3),(41,'Registration: Cannot find member with passcode eB9c618431ffd099703ad9a4600130c9.',NULL,1485259040,NULL,3),(42,'Registration: Cannot find member with passcode eB9c618431ffd099703ad9a4600130c9.',NULL,1485259059,NULL,3),(44,'Registration: Cannot find member with passcode eB9c618431ffd099703ad9a4600130c9.',NULL,1485259059,NULL,3),(46,'Registration: Cannot find member with passcode eB9c618431ffd099703ad9a4600130c9.',NULL,1485259060,NULL,3),(47,'Registration: Cannot find member with passcode eB9c618431ffd099703ad9a4600130c9.',NULL,1485259069,NULL,3),(48,'Registration: Team initializedCannot find team to add 9080c8e0106d3cf3c5f9c0337a6fcb2d.',6,1485259069,NULL,3),(49,'Registration: Cannot find member with passcode eB9c618431ffd099703ad9a4600130c9.',NULL,1485259069,NULL,3),(50,'Registration: Cannot find member with passcode eB9c618431ffd099703ad9a4600130c9.',NULL,1485259097,NULL,3),(51,'Registration: Cannot find member with passcode eB9c618431ffd099703ad9a4600130c9.',NULL,1485259097,NULL,3),(52,'Registration: Cannot find member with passcode eB9c618431ffd099703ad9a4600130c9.',NULL,1485259110,NULL,3),(53,'Registration: Cannot find member with passcode eB9c618431ffd099703ad9a4600130c9.',NULL,1485259111,NULL,3),(54,'Registration: Cannot find member with passcode eB9c618431ffd099703ad9a4600130c9.',NULL,1485259111,NULL,3),(55,'Registration: member already registered',NULL,1485259111,NULL,2),(56,'Registration: Cannot find member with passcode eB9c618431ffd099703ad9a4600130c9.',NULL,1485259111,NULL,3),(57,'Registration: Cannot find member with passcode eB9c618431ffd099703ad9a4600130c9.',NULL,1485259138,NULL,3),(58,'Registration: member already registered',NULL,1485259138,NULL,2),(59,'Registration: Cannot find member with passcode eB9c618431ffd099703ad9a4600130c9.',NULL,1485259138,NULL,3),(60,'Registration: member already registered',NULL,1485259138,NULL,2),(61,'Registration: Cannot find member with passcode eB9c618431ffd099703ad9a4600130c9.',NULL,1485259139,NULL,3),(62,'Registration: Cannot find member with passcode eB9c618431ffd099703ad9a4600130c9.',NULL,1485259147,NULL,3),(63,'Registration: Cannot find member with passcode eB9c618431ffd099703ad9a4600130c9.',NULL,1485259147,NULL,3),(64,'Registration: member already registered',NULL,1485259147,NULL,2),(65,'Registration: Cannot find member with passcode eB9c618431ffd099703ad9a4600130c9.',NULL,1485259147,NULL,3),(66,'Registration: Cannot find member with passcode eB9c618431ffd099703ad9a4600130c9.',NULL,1485259163,NULL,3),(67,'Registration: Cannot find member with passcode eB9c618431ffd099703ad9a4600130c9.',NULL,1485261636,NULL,3),(68,'Registration: Cannot find member with passcode eB9c618431ffd099703ad9a4600130c9.',NULL,1485261640,NULL,3),(69,'Registration: Cannot find member with passcode eB9c618431ffd099703ad9a4600130c9.',NULL,1485261766,NULL,3),(70,'Registration: Cannot finish registration!',NULL,1485261766,NULL,3),(71,'Registration: Cannot find member with passcode eB9c618431ffd099703ad9a4600130c9.',NULL,1485261887,NULL,3),(73,'Registration: Cannot find member with passcode eB9c618431ffd099703ad9a4600130c9.',NULL,1485261919,NULL,3),(74,'Registration: Cannot find member with passcode eB9c618431ffd099703ad9a4600130c9.',NULL,1485261933,NULL,3),(76,'Registration: Cannot find member with passcode eB9c618431ffd099703ad9a4600130c9.',NULL,1485261968,NULL,3),(78,'Registration: Cannot find member with passcode eB9c618431ffd099703ad9a4600130c9.',NULL,1485261979,NULL,3),(79,'Registration: Cannot find member with passcode eB9c618431ffd099703ad9a4600130c9.',NULL,1485262217,NULL,3),(80,'Registration: Cannot find member with passcode eB9c618431ffd099703ad9a4600130c9.',NULL,1485262227,NULL,3),(81,'Registration: Cannot find member with passcode eB9c618431ffd099703ad9a4600130c9.',NULL,1485262246,NULL,3),(83,'Registration: Cannot find member with passcode eB9c618431ffd099703ad9a4600130c9.',NULL,1485262249,NULL,3),(85,'Registration: Cannot find member with passcode eB9c618431ffd099703ad9a4600130c9.',NULL,1485262251,NULL,3),(86,'Registration: Cannot find member with passcode eB9c618431ffd099703ad9a4600130c9.',NULL,1485262383,NULL,3),(88,'Registration: Cannot find member with passcode eB9c618431ffd099703ad9a4600130c9.',NULL,1485262383,NULL,3),(89,'Registration: Cannot find member with passcode eB9c618431ffd099703ad9a4600130c9.',NULL,1485262554,NULL,3),(90,'Registration: Cannot find member with passcode eB9c618431ffd099703ad9a4600130c9.',NULL,1485262575,NULL,3),(91,'Registration: Cannot find member with passcode eB9c618431ffd099703ad9a4600130c9.',NULL,1485262583,NULL,3),(92,'Registration: Cannot find member with passcode eB9c618431ffd099703ad9a4600130c9.',NULL,1485262588,NULL,3),(93,'Registration: Cannot finish registration!',NULL,1485262588,NULL,3),(94,'Registration: Cannot find member with passcode eB9c618431ffd099703ad9a4600130c9.',NULL,1485262646,NULL,3),(95,'Registration: Cannot finish registration!',NULL,1485262646,NULL,3),(96,'Registration: Cannot find member with passcode eB9c618431ffd099703ad9a4600130c9.',NULL,1485262666,NULL,3),(97,'Registration: Cannot find member with passcode eB9c618431ffd099703ad9a4600130c9.',NULL,1485262736,NULL,3),(98,'Failed team login try with qr code 07f942ee7d5e4f8aU;@c@6VV0ÁÁÁÁÁÁÁÁ!',NULL,1485262758,NULL,2),(99,'Team logged in . ',13,1485262964,NULL,1),(100,'Team logged in . ',13,1485262965,NULL,1),(101,'Team logged in . ',13,1485263059,NULL,1),(102,'Team logged in . ',13,1485263060,NULL,1),(103,'Registration: Cannot find member with passcode eb9c618431ffd099703ad9a4600130c9.',NULL,1485279722,NULL,3),(104,'Registration: Cannot find member with passcode eb9c618431ffd099703ad9a4600130c9.',NULL,1485279801,NULL,3),(105,'Registration: Cannot find member with passcode eb9c618431ffd099703ad9a4600130c9.',NULL,1485279855,NULL,3),(106,'Registration: Cannot find member with passcode eb9c618431ffd099703ad9a4600130c9.',NULL,1485279878,NULL,3),(107,'Registration: Cannot find member with passcode eb9c618431ffd099703ad9a4600130c9.',NULL,1485279886,NULL,3),(108,'Registration: Cannot find member with passcode eb9c618431ffd099703ad9a4600130c9.',2,1485279887,NULL,3),(109,'Registration: Cannot find member with passcode eb9c618431ffd099703ad9a4600130c9.',2,1485280020,NULL,3),(110,'Registration: Cannot find member with passcode eb9c618431ffd099703ad9a4600130c9.',NULL,1485280041,NULL,3),(111,'Registration: Cannot find member with passcode eb9c618431ffd099703ad9a4600130c9.',NULL,1485280043,NULL,3),(112,'Registration: Cannot find member with passcode eb9c618431ffd099703ad9a4600130c9.',NULL,1485280092,NULL,3),(113,'Registration: Cannot find member with passcode eb9c618431ffd099703ad9a4600130c9.',NULL,1485280104,NULL,3),(114,'Team logged in . ',13,1485280172,NULL,1),(115,'Team logged in . ',13,1485280172,NULL,1),(116,'Registration: Cannot find member to add 68d5c9dfbb3d45ecd77d609b8eca1Ubd.',NULL,1485280303,NULL,3),(117,'Registration: member already registered',NULL,1485280308,NULL,2),(118,'Registration: Cannot find member to add 65c44b28a3643be71b7bc51d22f31211.',NULL,1485280324,NULL,3),(119,'Registration: Cannot find member to add 65c44b28a3643be71b7bc51d22f31211.',NULL,1485280325,NULL,3),(120,'Team logged in . ',13,1485280536,NULL,1),(121,'Registration: Cannot find member to add eb9c618431ffd099703ad9a4600130c9.',2,1485280773,NULL,3),(122,'Registration: Cannot find member to add eb9c618431ffd099703ad9a4600130c9.',2,1485280773,NULL,3),(123,'Team logged in . ',13,1485280794,NULL,1),(124,'Team logged in . ',13,1485280854,NULL,1),(125,'Team logged in . ',13,1485280854,NULL,1),(126,'Team logged in . ',13,1485282637,NULL,1),(127,'Team logged in . ',13,1485282637,NULL,1),(128,'Registration: member already registered',2,1485282840,NULL,2),(129,'Registration: member already registered',2,1485282848,NULL,2),(130,'Registration: member already registered',2,1485282853,NULL,2),(131,'Failed team login try with qr code 1a1ce120f74c03216b2bcb575cc7b592!',NULL,1485282866,NULL,2),(132,'Team logged in . ',13,1485282916,NULL,1),(133,'Team logged in . ',13,1485282916,NULL,1),(134,'Failed login try with qr code , error: \"Invalid code\"',NULL,1485292389,NULL,2),(135,'Team logged in . ',13,1485292436,NULL,1),(136,'Failed team login try with qr code a95c530a7af5f492a74499e70578d150!',NULL,1485292997,NULL,2),(137,'Failed team login try with qr code a95c530a7af5f492a74499e70578d150!',NULL,1485293053,NULL,2),(138,'Failed team login try with qr code a95c530a7af5f492a74499e70578d150!',NULL,1485293133,NULL,2),(139,'Failed team login try with qr code a95c530a7af5f492a74499e70578d150!',NULL,1485293142,NULL,2),(140,'Failed team login try with qr code a95c530a7af5f492a74499e70578d150!',NULL,1485293388,NULL,2),(141,'Team logged in . ',1,1485293457,NULL,1),(142,'Team logged in . ',1,1485299874,NULL,1),(143,'Team logged in . ',1,1485300880,NULL,1),(144,'Team logged in . ',1,1485300990,NULL,1),(145,'Team logged in . ',1,1485301076,NULL,1),(146,'Team logged in . ',1,1485301224,NULL,1),(147,'Team logged in . ',1,1485301540,NULL,1),(148,'Team logged in . ',1,1485301591,NULL,1),(149,'Team logged in . ',1,1485301732,NULL,1),(150,'Team logged in . ',1,1485301805,NULL,1),(151,'Moved Team into first level. ',1,1485301877,NULL,1),(152,'Team logged in . ',1,1485303530,NULL,1),(153,'Failed login try with qr code , error: \"Invalid code\"',NULL,1485330196,NULL,2),(154,'Team logged in . ',1,1485330210,NULL,1),(155,'Team logged in . ',1,1485474427,NULL,1),(156,'Team logged in . ',1,1485905837,NULL,1),(157,'Team logged in . ',1,1485905852,NULL,1),(158,'Team logged in . ',1,1485907706,NULL,1),(159,'Failed admin login try with qr code dde3259db83f1c5a86720338341e8608!',NULL,1486153476,NULL,3),(160,'Failed admin login try with qr code dde3259db83f1c5a86720338341e8608!',NULL,1486153489,NULL,3),(161,'Failed admin login try with qr code dde3259db83f1c5a86720338341e8608!',NULL,1486153523,NULL,3),(162,'Failed admin login try with qr code dde3259db83f1c5a86720338341e8608!',NULL,1486153626,NULL,3),(163,'Failed admin login try with qr code dde3259db83f1c5a86720338341e8608!',NULL,1486153799,NULL,3),(164,'Failed admin login try with qr code dde3259db83f1c5a86720338341e8608!',NULL,1486153852,NULL,3),(165,'Admin logged in . ',15,1486154686,NULL,1),(166,'Team logged in . ',16,1486165962,NULL,1),(167,'Moved Team into first level. ',16,1486165962,NULL,1),(168,'Game logged in . ',16,1486166556,NULL,1),(169,'Game logged in . ',16,1486169873,NULL,1),(170,'Failed team login try with qr code 1a1ce120f74c03216b2bcb575cc7b592!',NULL,1486170374,NULL,2),(171,'Game logged in . ',16,1486210112,NULL,1),(172,'Failed team login try with qr code 1a1ce120f74c03216b2bcb575cc7b592!',NULL,1486319539,NULL,2),(173,'Registration: member already registered',13,1486321054,NULL,2),(174,'Registration: member already registered',13,1486321054,NULL,2),(175,'Registration: member already registered',13,1486322149,NULL,2),(176,'Registration: member already registered',13,1486322252,NULL,2),(177,'Registration: member already registered',13,1486322281,NULL,2),(178,'Failed team login try with qr code 1a1ce120f74c03216b2bcb575cc7b592!',NULL,1486412942,NULL,2),(179,'Team logged in . ',1,1486412956,NULL,1),(180,'Team logged in . ',1,1486412969,NULL,1),(181,'Team logged in . ',1,1486412969,NULL,1),(182,'Failed admin login try with qr code dde3259db83f1c5a86720338341e8608!',NULL,1486413492,NULL,3),(183,'Failed admin login try with qr code 0c4d4b28cb7600094541349d88ade877!',NULL,1486413533,NULL,3),(184,'Failed admin login try with qr code 0c4d4b28cb7600094541349d88ade877!',NULL,1486413533,NULL,3),(185,'Failed admin login try with qr code 0c4d4b28cb7600094541349d88ade877!',NULL,1486413729,NULL,3),(186,'Failed admin login try with qr code 0c4d4b28cb7600094541349d88ade877!',NULL,1486413733,NULL,3),(187,'Failed admin login try with qr code 0c4d4b28cb7600094541349d88ade877!',NULL,1486413742,NULL,3),(188,'Failed admin login try with qr code dde3259db83f1c5a86720338341e8608!',NULL,1486413764,NULL,3),(189,'Failed admin login try with qr code dde3259db83f1c5a86720338341e8608!',NULL,1486413764,NULL,3),(190,'Failed admin login try with qr code dde3259db83f1c5a86720338341e8608!',NULL,1486413787,NULL,3),(191,'Failed admin login try with qr code dde3259db83f1c5a86720338341e8608!',NULL,1486413787,NULL,3),(192,'Failed admin login try with qr code dde3259db83f1c5a86720338341e8608!',NULL,1486413828,NULL,3),(193,'Failed admin login try with qr code dde3259db83f1c5a86720338341e8608!',NULL,1486413828,NULL,3),(194,'Failed admin login try with qr code dde3259db83f1c5a86720338341e8608!',NULL,1486413990,NULL,3),(195,'Failed admin login try with qr code 0c4d4b28cb7600094541349d88ade877!',NULL,1486413999,NULL,3),(196,'Admin logged in . ',15,1486414017,NULL,1),(197,'Failed admin login try with qr code 0c4d4b28cb7600094541349d88ade877!',NULL,1486414020,NULL,3),(198,'Failed admin login try with qr code dde3259db83f1c5a86720338341e8608!',NULL,1486414037,NULL,3),(199,'Admin logged in . ',15,1486414039,NULL,1),(200,'Failed team login try with qr code 1a1ce120f74c03216b2bcb575cc7b592!',NULL,1486470001,NULL,2),(201,'Failed team login try with qr code 1a1ce120f74c03216b2bcb575cc7b592!',NULL,1486499154,NULL,2),(202,'Failed team login try with qr code 1a1ce120f74c03216b2bcb575cc7b592!',NULL,1486499468,NULL,2),(203,'Failed team login try with qr code team!',NULL,1486499513,NULL,2),(204,'Failed team login try with qr code team!',NULL,1486499524,NULL,2),(205,'Team logged in . ',1,1486499529,NULL,1),(206,'Failed admin login try with qr code 0c4d4b28cb7600094541349d88ade877!',NULL,1486499823,NULL,3),(207,'Admin logged in . ',15,1486499845,NULL,1),(208,'Failed team login try with qr code 1a1ce120f74c03216b2bcb575cc7b592!',NULL,1486844870,NULL,2),(209,'Team logged in . ',1,1486845032,NULL,1),(210,'Team logged in . ',1,1486845070,NULL,1),(211,'Registration: member already registered',NULL,1486857375,NULL,2),(212,'Registration: member already registered',NULL,1486858330,NULL,2),(213,'Registration: member already registered',NULL,1486858337,NULL,2),(214,'Registration: Cancel registration for group 1.',NULL,1486858946,NULL,3),(215,'Registration: Cancel registration for member eb9c618431ffd099703ad9a4600130c9.',NULL,1486858946,NULL,3),(216,'Registration: Cancel registration for member 61ee05df02ec3c4910bbd21c0ad92f28.',NULL,1486858946,NULL,3),(217,'Registration: Cancel registration for member 65c44b28a3643be71b7bc51d22f31211.',NULL,1486858946,NULL,3),(218,'Failed team login try with qr code 1a1ce120f74c03216b2bcb575cc7b592!',NULL,1486858957,NULL,2),(219,'Registration: member already registered',NULL,1486859080,NULL,2),(220,'Team logged in . ',17,1487624772,NULL,1),(221,'Moved Team into first level. ',17,1487624772,NULL,1),(222,'Registration: member already registered',13,1487624908,NULL,2),(223,'Registration: member already registered',13,1487624914,NULL,2),(224,'Registration: member already registered',13,1487624973,NULL,2),(225,'Registration: member already registered',13,1487624973,NULL,2),(226,'Registration: member already registered',13,1487625109,NULL,2),(227,'Registration: member already registered',13,1487625352,NULL,2),(228,'Registration: member already registered',13,1487625357,NULL,2),(229,'Registration: member already registered',13,1487625388,NULL,2),(230,'Team logged in . ',13,1487625466,NULL,1),(231,'Moved Team into first level. ',13,1487625466,NULL,1),(232,'Team logged in . ',13,1487663292,NULL,1),(233,'Team logged in . ',13,1487969081,NULL,1),(234,'Failed admin login try with qr code 0c4d4b28cb7600094541349d88ade877!',NULL,1487970761,NULL,3),(235,'Admin logged in . ',15,1487971432,NULL,1),(236,'Failed admin login try with qr code 0c4d4b28cb7600094541349d88ade877!',NULL,1488663348,NULL,3),(237,'Admin logged in . ',15,1488663356,NULL,1),(238,'Admin logged in . ',15,1489100114,NULL,1),(239,'Admin logged in . ',15,1489438730,NULL,1);
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
  `passcode` varchar(45) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `description` text,
  `duration` int(11) DEFAULT NULL,
  `game_subject_id` int(11) DEFAULT NULL,
  `level_id` int(11) DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `game_description` text,
  `group_text_game_start` text,
  `group_text_game_question` text,
  `group_text_game_correct_answer` tinytext,
  `group_text_game_wrong_answer_1` tinytext,
  `group_text_game_wrong_answer_2` tinytext,
  `group_text_game_wrong_answer_3` tinytext,
  `group_text_game_end` text,
  `parent_game` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_232B318C1EC80738` (`parent_game`),
  KEY `fk_game_game_subject1_idx` (`game_subject_id`),
  KEY `fk_game_level1_idx` (`level_id`),
  KEY `fk_game_location1_idx` (`location_id`),
  CONSTRAINT `FK_232B318C1EC80738` FOREIGN KEY (`parent_game`) REFERENCES `game` (`id`),
  CONSTRAINT `fk_game_game_subject1` FOREIGN KEY (`game_subject_id`) REFERENCES `game_subject` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_game_level1` FOREIGN KEY (`level_id`) REFERENCES `level` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_game_location1` FOREIGN KEY (`location_id`) REFERENCES `location` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `game`
--

LOCK TABLES `game` WRITE;
/*!40000 ALTER TABLE `game` DISABLE KEYS */;
INSERT INTO `game` VALUES (1,'b530aff9d55a7ba4d089d356507ee19f','Testspiel 1','Lorem ipsum',5,1,1,1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(2,'asdfasdf','Testspiel','Lorem ipsum',5,3,1,1,0,'<p>Lorem ipsum dolor sit <strong>amet</strong>, consetetur <strong>sadipscing</strong> elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>\r\n<ul>\r\n<li>Lorem ipsum</li>\r\n<li>dolor sit amet</li>\r\n<li>consetetur sadipscing</li>\r\n<li>elitr</li>\r\n<li>sed</li>\r\n</ul>\r\n<p>diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p>',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
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
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `game_subject`
--

LOCK TABLES `game_subject` WRITE;
/*!40000 ALTER TABLE `game_subject` DISABLE KEYS */;
INSERT INTO `game_subject` VALUES (1,'Die Elemente und wir',NULL,'#73d216','#fce94f'),(2,'Wir in der Stadt',NULL,'#73d216','#eeeeec'),(3,'Damals war alles besser',NULL,'#73d216','#f57900'),(4,'Anders ist Einzig, nicht artig',NULL,'#73d216','#e9b96e'),(5,'Irgendwas mit Gott',NULL,'#73d216','#729fcf'),(6,'Technik die begeistert',NULL,'#73d216','#ad7fa8'),(7,'Wir in Aktion',NULL,'#73d216','#888a85'),(8,'Pack’s An',NULL,'#73d216','#ef2929'),(9,'Wir haben Phantasie',NULL,'#73d216','#fce94f'),(10,'Gefühlsecht',NULL,'#73d216','#eeeeec'),(11,'Wir miteinander',NULL,'#73d216','#f57900'),(12,'Baden-Powell 2.0',NULL,'#73d216','#e9b96e'),(13,'Feuer im Hintern!?',NULL,'#73d216','#729fcf'),(14,'Bild dir deine Meinung!',NULL,'#73d216','#ad7fa8'),(15,'Zukunftsmusik',NULL,'#73d216','#888a85'),(16,'Be A Star',NULL,'#73d216','#ef2929'),(17,'Wasser macht nass',NULL,'#73d216','#fce94f'),(18,'Mehr Meer, mehr Mär',NULL,'#73d216','#eeeeec'),(19,'Iss, Kind!',NULL,'#73d216','#f57900'),(20,'Ein bischen Frieden',NULL,'#73d216','#e9b96e'),(21,'Die Erde ist rund',NULL,'#73d216','#ad7fa8'),(22,'Keine Luft zum Atmen',NULL,'#73d216','#ef2929'),(23,'We are the world',NULL,'#73d216','#e9b96e'),(24,NULL,NULL,NULL,NULL);
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
  `created` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `joker`
--

LOCK TABLES `joker` WRITE;
/*!40000 ALTER TABLE `joker` DISABLE KEYS */;
INSERT INTO `joker` VALUES (1,'788ac26d2b0ad74d11789136ee2912e6',0,NULL),(2,'2a5af6f342196932440469aec3717bdb',0,NULL),(3,'2cae7a1c098662e8edc54d117a6fba11',0,NULL),(4,'70be68914b033787c335778168671eb7',0,NULL),(5,'760b643ca78c73eb69da2a98f3d6f833',0,NULL),(6,'f577576e451dcb547a4af91152fedc9e',0,1486503342),(7,'0401b6dcbf215bbdd68aa80b7be78b57',0,1486503381),(8,'64aa4e0daefbd3e309ac7f48bf558b0c',0,1486503412),(9,'7b05f67f3aa363975d009c2c4b0dc629',0,1486544063);
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `location`
--

LOCK TABLES `location` WRITE;
/*!40000 ALTER TABLE `location` DISABLE KEYS */;
INSERT INTO `location` VALUES (1,'Ort','Hier');
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
  `first_name` varchar(45) DEFAULT NULL,
  `team_id` int(11) DEFAULT NULL,
  `grade` varchar(45) DEFAULT NULL,
  `passcode` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_member_team1_idx` (`team_id`),
  CONSTRAINT `fk_member_team1` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `member`
--

LOCK TABLES `member` WRITE;
/*!40000 ALTER TABLE `member` DISABLE KEYS */;
INSERT INTO `member` VALUES (1,'Member 1','Peter',1,'woe','68d5c9dfbb3d45ecd77d609b8eca1ebd'),(2,'Member 2','Klaus',1,'woe','a3715699b7c3b00b81d64799245f0f47'),(3,'Member 3','Ulrike',1,'woe','5f8dd29a3fc26e057b5d3a179b501432'),(4,'Admin','Admin 1',15,'admin','715a4b2d636c89bdb19984a522d9e050'),(5,'Member 4','Hans',13,'juffi','61ee05df02ec3c4910bbd21c0ad92f28'),(6,'Member 5','Maria',13,'juffi','eb9c618431ffd099703ad9a4600130c9'),(7,'Member 6','Maja',13,'juffi','65c44b28a3643be71b7bc51d22f31211'),(8,'Member 7','Dieter',NULL,'pfadi','892ca14a1e05bdd554bf43c1bf99ca50'),(9,'Member 8','Karl',NULL,'pfadi','0ec32cfec8c84003f416c7be3e3659ae'),(10,'Member 9','Gustav',NULL,'pfadi','990e665ac7f078125b6ad82becccce11'),(11,'Member 10','Franz',NULL,'rover','122ca28f6a242d3613917c563801f885'),(12,'Member 11','Fred',NULL,'rover','50bb72bd1da1c71b7a223ad513742acb'),(13,'Member 12','Albert',NULL,'rover','473082d890a974c5293acc6dd69d9791');
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
  `parent_team` varchar(45) DEFAULT NULL,
  `current_level` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_team_level1_idx` (`current_level`),
  CONSTRAINT `fk_team_level1` FOREIGN KEY (`current_level`) REFERENCES `level` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team`
--

LOCK TABLES `team` WRITE;
/*!40000 ALTER TABLE `team` DISABLE KEYS */;
INSERT INTO `team` VALUES (1,'a95c530a7af5f492a74499e70578d150','a95c530a7af5f492a74499e70578d150',1,2),(2,'1a1ce120f74c03216b2bcb575cc7b592','1a1ce120f74c03216b2bcb575cc7b592',NULL,1),(6,'9080c8e0106d3cf3c5f9c0337a6fcb2d','9080c8e0106d3cf3c5f9c0337a6fcb2d',NULL,1),(13,'07f942ee7d5e4f8a341236a199781eec','07f942ee7d5e4f8a341236a199781eec',1,2),(14,'2','2',NULL,1),(15,'1756ad92205d30550ace821ac1e90d9e','1756ad92205d30550ace821ac1e90d9e',NULL,4),(16,'848ab7852d793ae2c009304f58c46885','848ab7852d793ae2c009304f58c46885',1,5),(17,'1a1ce120f74c03216b2bcb575cc7b592','1a1ce120f74c03216b2bcb575cc7b592',1,2);
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
  `team_id` int(11) DEFAULT NULL,
  `level_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_team_level_team_idx` (`team_id`),
  KEY `fk_team_level_level1_idx` (`level_id`),
  CONSTRAINT `fk_team_level_level1` FOREIGN KEY (`level_id`) REFERENCES `level` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_team_level_team` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team_level`
--

LOCK TABLES `team_level` WRITE;
/*!40000 ALTER TABLE `team_level` DISABLE KEYS */;
INSERT INTO `team_level` VALUES (3,1485301805,NULL,1,1),(4,1486165962,NULL,16,1),(5,1487624772,NULL,17,1),(6,1487625466,NULL,13,1);
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
  `team_level_id` int(11) DEFAULT NULL,
  `assigned_game_subject` int(11) DEFAULT NULL,
  `played_points` varchar(45) DEFAULT NULL,
  `start_time` int(11) DEFAULT NULL,
  `finish_time` int(11) DEFAULT NULL,
  `used_joker` int(11) DEFAULT NULL,
  `assigned_game` int(11) DEFAULT NULL,
  `solved_by_leveljump` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_74D7B858722A863B` (`used_joker`),
  KEY `fk_team_level_game_team_level1_idx` (`team_level_id`),
  KEY `fk_team_level_game_game_subject1_idx` (`assigned_game_subject`),
  KEY `fk_team_level_game_joker1_idx` (`used_joker`),
  KEY `fk_team_level_game_game1_idx` (`assigned_game`),
  CONSTRAINT `fk_team_level_game_game1` FOREIGN KEY (`assigned_game`) REFERENCES `game` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_team_level_game_game_subject1` FOREIGN KEY (`assigned_game_subject`) REFERENCES `game_subject` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_team_level_game_joker1` FOREIGN KEY (`used_joker`) REFERENCES `joker` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_team_level_game_team_level1` FOREIGN KEY (`team_level_id`) REFERENCES `team_level` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team_level_game`
--

LOCK TABLES `team_level_game` WRITE;
/*!40000 ALTER TABLE `team_level_game` DISABLE KEYS */;
INSERT INTO `team_level_game` VALUES (1,3,17,NULL,1485301808,NULL,NULL,NULL,NULL),(2,3,16,NULL,1485301809,NULL,NULL,NULL,NULL),(3,3,13,NULL,1485301810,NULL,NULL,NULL,NULL),(4,3,6,NULL,1485301812,NULL,NULL,NULL,NULL),(5,4,16,NULL,1486165962,NULL,NULL,NULL,NULL),(6,4,18,NULL,1486165962,NULL,NULL,NULL,NULL),(7,4,9,NULL,1486165962,NULL,NULL,NULL,NULL),(8,4,7,NULL,1486165962,NULL,NULL,NULL,NULL),(9,5,14,NULL,1487624772,NULL,NULL,NULL,NULL),(10,5,1,NULL,1487624772,NULL,NULL,NULL,NULL),(11,5,18,NULL,1487624772,NULL,NULL,NULL,NULL),(12,5,6,NULL,1487624772,NULL,NULL,NULL,NULL),(13,6,15,NULL,1487625466,NULL,NULL,NULL,NULL),(14,6,4,NULL,1487625466,NULL,NULL,NULL,NULL),(15,6,2,NULL,1487625466,NULL,NULL,NULL,NULL),(16,6,1,NULL,1487625466,NULL,NULL,NULL,NULL);
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

-- Dump completed on 2017-03-13 22:09:20
