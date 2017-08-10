-- MySQL dump 10.13  Distrib 5.7.19, for Linux (x86_64)
--
-- Host: localhost    Database: payproapi
-- ------------------------------------------------------
-- Server version	5.7.19-0ubuntu0.16.04.1

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
-- Table structure for table `Accounts`
--

DROP TABLE IF EXISTS `Accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `agreement_id` int(11) NOT NULL,
  `last_synced_transaction_id` int(11) DEFAULT NULL,
  `country_id` int(11) NOT NULL,
  `forename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `birth_date` datetime NOT NULL,
  `document_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `document_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `card_holder_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `account_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sort_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `street` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `building_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `postcode` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_33BEFCFAABCB3E15` (`last_synced_transaction_id`),
  KEY `IDX_33BEFCFA24890B2B` (`agreement_id`),
  KEY `IDX_33BEFCFAF92F3E70` (`country_id`),
  CONSTRAINT `FK_33BEFCFA24890B2B` FOREIGN KEY (`agreement_id`) REFERENCES `Agreements` (`id`),
  CONSTRAINT `FK_33BEFCFAABCB3E15` FOREIGN KEY (`last_synced_transaction_id`) REFERENCES `Transactions` (`id`),
  CONSTRAINT `FK_33BEFCFAF92F3E70` FOREIGN KEY (`country_id`) REFERENCES `Countries` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Accounts`
--

LOCK TABLES `Accounts` WRITE;
/*!40000 ALTER TABLE `Accounts` DISABLE KEYS */;
INSERT INTO `Accounts` VALUES (1,1,207,209,'John','Doe','1992-06-27 10:16:08','DNI','43223432D','132050','04078266','623053','Guell','6','08293','Barcelona',NULL,'PENDING','2016-08-07 10:16:11','2017-08-10 15:35:58'),(2,1,NULL,209,'Second','Account','1992-06-27 10:31:50','DNI','12312312D','132092','04070139','623053','Guell','6','08293','Barcelona',NULL,'PENDING','2017-08-07 10:31:52','2017-08-07 10:31:52'),(3,1,NULL,209,'String','String','1992-06-08 12:27:06','DNI','12341234K','132099','04077193','623053','Ametller','6','08293','Barcelona',NULL,'PENDING','2017-08-08 12:27:09','2017-08-08 12:27:09'),(4,1,NULL,209,'String','String','1992-06-08 14:40:04','PASSPORT','5040143258USA8107271F23041042550932212903838','132105','04075585','623053','Ametller','6','08293','Barcelona',NULL,'PENDING','2017-08-09 14:40:05','2017-08-09 14:40:05');
/*!40000 ALTER TABLE `Accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Agreements`
--

DROP TABLE IF EXISTS `Agreements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Agreements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contis_agreement_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `currency_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `new_card_charge` bigint(20) NOT NULL,
  `card_reissue_charge` bigint(20) NOT NULL,
  `local_atmwithdraw_charge` bigint(20) NOT NULL,
  `abroad_atmwithdraw_charge` bigint(20) NOT NULL,
  `max_balance` bigint(20) NOT NULL,
  `card_limit` bigint(20) NOT NULL,
  `monthly_account_fee` bigint(20) NOT NULL,
  `daily_spend_limit` bigint(20) NOT NULL,
  `monthly_spend_limit` bigint(20) NOT NULL,
  `max_no_of_additional_cards` bigint(20) NOT NULL,
  `atmweekly_spend_limit` bigint(20) NOT NULL,
  `atmmonthly_spend_limit` bigint(20) NOT NULL,
  `cash_back_daily_limit` bigint(20) NOT NULL,
  `cash_back_weekly_limit` bigint(20) NOT NULL,
  `cash_back_monthly_limit` bigint(20) NOT NULL,
  `cash_back_yearly_limit` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Agreements`
--

LOCK TABLES `Agreements` WRITE;
/*!40000 ALTER TABLE `Agreements` DISABLE KEYS */;
INSERT INTO `Agreements` VALUES (1,'RJAMGSL7','GB',599,0,0,150,500000,1,0,12345,1246788,0,1342,123451,1234,123456,12345678,1234567890),(2,'8JZ8MYNM','GB',59900,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1);
/*!40000 ALTER TABLE `Agreements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Cards`
--

DROP TABLE IF EXISTS `Cards`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Cards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) DEFAULT NULL,
  `contis_card_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contis_card_activation_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL,
  `is_enabled` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_C50377F99B6B5FBA` (`account_id`),
  CONSTRAINT `FK_C50377F99B6B5FBA` FOREIGN KEY (`account_id`) REFERENCES `Accounts` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Cards`
--

LOCK TABLES `Cards` WRITE;
/*!40000 ALTER TABLE `Cards` DISABLE KEYS */;
/*!40000 ALTER TABLE `Cards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Countries`
--

DROP TABLE IF EXISTS `Countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iso2` varchar(2) COLLATE utf8_unicode_ci NOT NULL,
  `iso3` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `iso_numeric` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=250 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Countries`
--

LOCK TABLES `Countries` WRITE;
/*!40000 ALTER TABLE `Countries` DISABLE KEYS */;
INSERT INTO `Countries` VALUES (1,'AF','AFG','004','Afghanistan',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(2,'AX','ALA','248','Åland Islands',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(3,'AL','ALB','008','Albania',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(4,'DZ','DZA','012','Algeria',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(5,'AS','ASM','016','American Samoa',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(6,'AD','AND','020','Andorra',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(7,'AO','AGO','024','Angola',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(8,'AI','AIA','660','Anguilla',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(9,'AQ','ATA','010','Antarctica',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(10,'AG','ATG','028','Antigua and Barbuda',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(11,'AR','ARG','032','Argentina',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(12,'AM','ARM','051','Armenia',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(13,'AW','ABW','533','Aruba',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(14,'AU','AUS','036','Australia',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(15,'AT','AUT','040','Austria',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(16,'AZ','AZE','031','Azerbaijan',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(17,'BS','BHS','044','Bahamas',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(18,'BH','BHR','048','Bahrain',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(19,'BD','BGD','050','Bangladesh',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(20,'BB','BRB','052','Barbados',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(21,'BY','BLR','112','Belarus',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(22,'BE','BEL','056','Belgium',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(23,'BZ','BLZ','084','Belize',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(24,'BJ','BEN','204','Benin',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(25,'BM','BMU','060','Bermuda',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(26,'BT','BTN','064','Bhutan',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(27,'BO','BOL','068','Bolivia (Plurinational State of)',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(28,'BQ','BES','535','Bonaire, \'iso2\' => Sint Eustatius and Saba',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(29,'BA','BIH','070','Bosnia and Herzegovina',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(30,'BW','BWA','072','Botswana',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(31,'BV','BVT','074','Bouvet Island',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(32,'BR','BRA','076','Brazil',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(33,'IO','IOT','086','British Indian Ocean Territory',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(34,'BN','BRN','096','Brunei Darussalam',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(35,'BG','BGR','100','Bulgaria',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(36,'BF','BFA','854','Burkina Faso',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(37,'BI','BDI','108','Burundi',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(38,'CV','CPV','132','Cabo Verde',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(39,'KH','KHM','116','Cambodia',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(40,'CM','CMR','120','Cameroon',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(41,'CA','CAN','124','Canada',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(42,'KY','CYM','136','Cayman Islands',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(43,'CF','CAF','140','Central African Republic',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(44,'TD','TCD','148','Chad',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(45,'CL','CHL','152','Chile',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(46,'CN','CHN','156','China',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(47,'CX','CXR','162','Christmas Island',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(48,'CC','CCK','166','Cocos (Keeling) Islands',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(49,'CO','COL','170','Colombia',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(50,'KM','COM','174','Comoros',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(51,'CG','COG','178','Congo',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(52,'CD','COD','180','Congo (Democratic Republic of the)',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(53,'CK','COK','184','Cook Islands',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(54,'CR','CRI','188','Costa Rica',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(55,'CI','CIV','384','Côte d\'Ivoire',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(56,'HR','HRV','191','Croatia',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(57,'CU','CUB','192','Cuba',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(58,'CW','CUW','531','Curaçao',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(59,'CY','CYP','196','Cyprus',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(60,'CZ','CZE','203','Czechia',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(61,'DK','DNK','208','Denmark',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(62,'DJ','DJI','262','Djibouti',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(63,'DM','DMA','212','Dominica',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(64,'DO','DOM','214','Dominican Republic',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(65,'EC','ECU','218','Ecuador',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(66,'EG','EGY','818','Egypt',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(67,'SV','SLV','222','El Salvador',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(68,'GQ','GNQ','226','Equatorial Guinea',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(69,'ER','ERI','232','Eritrea',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(70,'EE','EST','233','Estonia',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(71,'ET','ETH','231','Ethiopia',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(72,'FK','FLK','238','Falkland Islands (Malvinas)',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(73,'FO','FRO','234','Faroe Islands',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(74,'FJ','FJI','242','Fiji',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(75,'FI','FIN','246','Finland',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(76,'FR','FRA','250','France',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(77,'GF','GUF','254','French Guiana',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(78,'PF','PYF','258','French Polynesia',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(79,'TF','ATF','260','French Southern Territories',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(80,'GA','GAB','266','Gabon',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(81,'GM','GMB','270','Gambia',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(82,'GE','GEO','268','Georgia',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(83,'DE','DEU','276','Germany',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(84,'GH','GHA','288','Ghana',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(85,'GI','GIB','292','Gibraltar',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(86,'GR','GRC','300','Greece',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(87,'GL','GRL','304','Greenland',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(88,'GD','GRD','308','Grenada',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(89,'GP','GLP','312','Guadeloupe',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(90,'GU','GUM','316','Guam',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(91,'GT','GTM','320','Guatemala',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(92,'GG','GGY','831','Guernsey',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(93,'GN','GIN','324','Guinea',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(94,'GW','GNB','624','Guinea-Bissau',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(95,'GY','GUY','328','Guyana',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(96,'HT','HTI','332','Haiti',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(97,'HM','HMD','334','Heard Island and McDonald Islands',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(98,'VA','VAT','336','Holy See',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(99,'HN','HND','340','Honduras',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(100,'HK','HKG','344','Hong Kong',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(101,'HU','HUN','348','Hungary',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(102,'IS','ISL','352','Iceland',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(103,'IN','IND','356','India',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(104,'ID','IDN','360','Indonesia',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(105,'IR','IRN','364','Iran (Islamic Republic of)',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(106,'IQ','IRQ','368','Iraq',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(107,'IE','IRL','372','Ireland',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(108,'IM','IMN','833','Isle of Man',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(109,'IL','ISR','376','Israel',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(110,'IT','ITA','380','Italy',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(111,'JM','JAM','388','Jamaica',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(112,'JP','JPN','392','Japan',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(113,'JE','JEY','832','Jersey',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(114,'JO','JOR','400','Jordan',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(115,'KZ','KAZ','398','Kazakhstan',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(116,'KE','KEN','404','Kenya',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(117,'KI','KIR','296','Kiribati',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(118,'KP','PRK','408','Korea (Democratic People\'s Republic of)',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(119,'KR','KOR','410','Korea (Republic of)',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(120,'KW','KWT','414','Kuwait',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(121,'KG','KGZ','417','Kyrgyzstan',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(122,'LA','LAO','418','Lao People\'s Democratic Republic',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(123,'LV','LVA','428','Latvia',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(124,'LB','LBN','422','Lebanon',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(125,'LS','LSO','426','Lesotho',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(126,'LR','LBR','430','Liberia',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(127,'LY','LBY','434','Libya',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(128,'LI','LIE','438','Liechtenstein',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(129,'LT','LTU','440','Lithuania',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(130,'LU','LUX','442','Luxembourg',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(131,'MO','MAC','446','Macao',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(132,'MK','MKD','807','Macedonia (the former Yugoslav Republic of)',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(133,'MG','MDG','450','Madagascar',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(134,'MW','MWI','454','Malawi',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(135,'MY','MYS','458','Malaysia',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(136,'MV','MDV','462','Maldives',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(137,'ML','MLI','466','Mali',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(138,'MT','MLT','470','Malta',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(139,'MH','MHL','584','Marshall Islands',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(140,'MQ','MTQ','474','Martinique',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(141,'MR','MRT','478','Mauritania',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(142,'MU','MUS','480','Mauritius',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(143,'YT','MYT','175','Mayotte',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(144,'MX','MEX','484','Mexico',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(145,'FM','FSM','583','Micronesia (Federated States of)',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(146,'MD','MDA','498','Moldova (Republic of)',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(147,'MC','MCO','492','Monaco',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(148,'MN','MNG','496','Mongolia',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(149,'ME','MNE','499','Montenegro',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(150,'MS','MSR','500','Montserrat',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(151,'MA','MAR','504','Morocco',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(152,'MZ','MOZ','508','Mozambique',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(153,'MM','MMR','104','Myanmar',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(154,'NA','NAM','516','Namibia',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(155,'NR','NRU','520','Nauru',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(156,'NP','NPL','524','Nepal',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(157,'NL','NLD','528','Netherlands',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(158,'NC','NCL','540','New Caledonia',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(159,'NZ','NZL','554','New Zealand',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(160,'NI','NIC','558','Nicaragua',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(161,'NE','NER','562','Niger',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(162,'NG','NGA','566','Nigeria',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(163,'NU','NIU','570','Niue',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(164,'NF','NFK','574','Norfolk Island',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(165,'MP','MNP','580','Northern Mariana Islands',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(166,'NO','NOR','578','Norway',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(167,'OM','OMN','512','Oman',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(168,'PK','PAK','586','Pakistan',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(169,'PW','PLW','585','Palau',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(170,'PS','PSE','275','Palestine, \'iso2\' => State of',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(171,'PA','PAN','591','Panama',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(172,'PG','PNG','598','Papua New Guinea',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(173,'PY','PRY','600','Paraguay',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(174,'PE','PER','604','Peru',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(175,'PH','PHL','608','Philippines',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(176,'PN','PCN','612','Pitcairn',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(177,'PL','POL','616','Poland',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(178,'PT','PRT','620','Portugal',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(179,'PR','PRI','630','Puerto Rico',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(180,'QA','QAT','634','Qatar',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(181,'RE','REU','638','Réunion',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(182,'RO','ROU','642','Romania',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(183,'RU','RUS','643','Russian Federation',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(184,'RW','RWA','646','Rwanda',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(185,'BL','BLM','652','Saint Barthélemy',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(186,'SH','SHN','654','Saint Helena, \'iso2\' => Ascension and Tristan da Cunha',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(187,'KN','KNA','659','Saint Kitts and Nevis',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(188,'LC','LCA','662','Saint Lucia',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(189,'MF','MAF','663','Saint Martin (French part)',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(190,'PM','SPM','666','Saint Pierre and Miquelon',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(191,'VC','VCT','670','Saint Vincent and the Grenadines',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(192,'WS','WSM','882','Samoa',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(193,'SM','SMR','674','San Marino',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(194,'ST','STP','678','Sao Tome and Principe',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(195,'SA','SAU','682','Saudi Arabia',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(196,'SN','SEN','686','Senegal',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(197,'RS','SRB','688','Serbia',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(198,'SC','SYC','690','Seychelles',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(199,'SL','SLE','694','Sierra Leone',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(200,'SG','SGP','702','Singapore',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(201,'SX','SXM','534','Sint Maarten (Dutch part)',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(202,'SK','SVK','703','Slovakia',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(203,'SI','SVN','705','Slovenia',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(204,'SB','SLB','090','Solomon Islands',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(205,'SO','SOM','706','Somalia',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(206,'ZA','ZAF','710','South Africa',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(207,'GS','SGS','239','South Georgia and the South Sandwich Islands',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(208,'SS','SSD','728','South Sudan',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(209,'ES','ESP','724','Spain',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(210,'LK','LKA','144','Sri Lanka',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(211,'SD','SDN','729','Sudan',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(212,'SR','SUR','740','Suriname',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(213,'SJ','SJM','744','Svalbard and Jan Mayen',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(214,'SZ','SWZ','748','Swaziland',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(215,'SE','SWE','752','Sweden',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(216,'CH','CHE','756','Switzerland',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(217,'SY','SYR','760','Syrian Arab Republic',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(218,'TW','TWN','158','Taiwan, \'iso2\' => Province of China[a]',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(219,'TJ','TJK','762','Tajikistan',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(220,'TZ','TZA','834','Tanzania, \'iso2\' => United Republic of',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(221,'TH','THA','764','Thailand',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(222,'TL','TLS','626','Timor-Leste',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(223,'TG','TGO','768','Togo',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(224,'TK','TKL','772','Tokelau',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(225,'TO','TON','776','Tonga',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(226,'TT','TTO','780','Trinidad and Tobago',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(227,'TN','TUN','788','Tunisia',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(228,'TR','TUR','792','Turkey',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(229,'TM','TKM','795','Turkmenistan',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(230,'TC','TCA','796','Turks and Caicos Islands',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(231,'TV','TUV','798','Tuvalu',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(232,'UG','UGA','800','Uganda',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(233,'UA','UKR','804','Ukraine',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(234,'AE','ARE','784','United Arab Emirates',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(235,'GB','GBR','826','United Kingdom of Great Britain and Northern Ireland',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(236,'US','USA','840','United States of America',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(237,'UM','UMI','581','United States Minor Outlying Islands',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(238,'UY','URY','858','Uruguay',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(239,'UZ','UZB','860','Uzbekistan',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(240,'VU','VUT','548','Vanuatu',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(241,'VE','VEN','862','Venezuela (Bolivarian Republic of)',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(242,'VN','VNM','704','Viet Nam',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(243,'VG','VGB','092','Virgin Islands (British)',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(244,'VI','VIR','850','Virgin Islands (U.S.)',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(245,'WF','WLF','876','Wallis and Futuna',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(246,'EH','ESH','732','Western Sahara',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(247,'YE','YEM','887','Yemen',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(248,'ZM','ZMB','894','Zambia',1,'2017-08-07 09:22:42','2017-08-07 09:22:42'),(249,'ZW','ZWE','716','Zimbabwe',1,'2017-08-07 09:22:42','2017-08-07 09:22:42');
/*!40000 ALTER TABLE `Countries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Invites`
--

DROP TABLE IF EXISTS `Invites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Invites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `inviter_id` int(11) NOT NULL,
  `invited_phone_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_CCC353F0B79F4F04` (`inviter_id`),
  CONSTRAINT `FK_CCC353F0B79F4F04` FOREIGN KEY (`inviter_id`) REFERENCES `Users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Invites`
--

LOCK TABLES `Invites` WRITE;
/*!40000 ALTER TABLE `Invites` DISABLE KEYS */;
/*!40000 ALTER TABLE `Invites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `MigrationVersions`
--

DROP TABLE IF EXISTS `MigrationVersions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `MigrationVersions` (
  `version` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `MigrationVersions`
--

LOCK TABLES `MigrationVersions` WRITE;
/*!40000 ALTER TABLE `MigrationVersions` DISABLE KEYS */;
INSERT INTO `MigrationVersions` VALUES ('20170523165703'),('20170613162920'),('20170704122249');
/*!40000 ALTER TABLE `MigrationVersions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `MobileVerificationCodes`
--

DROP TABLE IF EXISTS `MobileVerificationCodes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `MobileVerificationCodes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `MobileVerificationCodes`
--

LOCK TABLES `MobileVerificationCodes` WRITE;
/*!40000 ALTER TABLE `MobileVerificationCodes` DISABLE KEYS */;
INSERT INTO `MobileVerificationCodes` VALUES (1,'3457','+34650395348'),(2,'2811','+34690331976'),(3,'6794','+34666666666'),(4,'7972','+34623123123'),(5,'3191','+34654321123'),(6,'1513','+34630012345');
/*!40000 ALTER TABLE `MobileVerificationCodes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Notifications`
--

DROP TABLE IF EXISTS `Notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) DEFAULT NULL,
  `is_sent` tinyint(1) NOT NULL,
  `device_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_D37EFB269B6B5FBA` (`account_id`),
  CONSTRAINT `FK_D37EFB269B6B5FBA` FOREIGN KEY (`account_id`) REFERENCES `Accounts` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Notifications`
--

LOCK TABLES `Notifications` WRITE;
/*!40000 ALTER TABLE `Notifications` DISABLE KEYS */;
INSERT INTO `Notifications` VALUES (1,1,0,'myAwesomeDevice','2017-08-07 10:16:11','2017-08-07 10:16:11'),(2,2,0,'myAwesomeDevice','2017-08-07 10:31:52','2017-08-07 10:31:52'),(3,3,0,'deviceToken','2017-08-08 12:27:09','2017-08-08 12:27:09'),(4,4,0,'deviceToken','2017-08-09 14:40:05','2017-08-09 14:40:05');
/*!40000 ALTER TABLE `Notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Profiles`
--

DROP TABLE IF EXISTS `Profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Profiles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) DEFAULT NULL,
  `picture` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_7246E7669B6B5FBA` (`account_id`),
  CONSTRAINT `FK_7246E7669B6B5FBA` FOREIGN KEY (`account_id`) REFERENCES `Accounts` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Profiles`
--

LOCK TABLES `Profiles` WRITE;
/*!40000 ALTER TABLE `Profiles` DISABLE KEYS */;
/*!40000 ALTER TABLE `Profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `TransactionInvites`
--

DROP TABLE IF EXISTS `TransactionInvites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TransactionInvites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invite_id` int(11) DEFAULT NULL,
  `transaction_id` int(11) DEFAULT NULL,
  `requested_at` datetime NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_1ECB7E032FC0CB0F` (`transaction_id`),
  KEY `IDX_1ECB7E03EA417747` (`invite_id`),
  CONSTRAINT `FK_1ECB7E032FC0CB0F` FOREIGN KEY (`transaction_id`) REFERENCES `Transactions` (`id`),
  CONSTRAINT `FK_1ECB7E03EA417747` FOREIGN KEY (`invite_id`) REFERENCES `Invites` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `TransactionInvites`
--

LOCK TABLES `TransactionInvites` WRITE;
/*!40000 ALTER TABLE `TransactionInvites` DISABLE KEYS */;
/*!40000 ALTER TABLE `TransactionInvites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Transactions`
--

DROP TABLE IF EXISTS `Transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payer_id` int(11) DEFAULT NULL,
  `beneficiary_id` int(11) DEFAULT NULL,
  `contis_transaction_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `amount` bigint(20) NOT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_F299C1B4C17AD9A9` (`payer_id`),
  KEY `IDX_F299C1B4ECCAAFA0` (`beneficiary_id`),
  CONSTRAINT `FK_F299C1B4C17AD9A9` FOREIGN KEY (`payer_id`) REFERENCES `Accounts` (`id`),
  CONSTRAINT `FK_F299C1B4ECCAAFA0` FOREIGN KEY (`beneficiary_id`) REFERENCES `Accounts` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=208 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Transactions`
--

LOCK TABLES `Transactions` WRITE;
/*!40000 ALTER TABLE `Transactions` DISABLE KEYS */;
INSERT INTO `Transactions` VALUES (139,1,NULL,'989819',1,'1','2017-08-03 12:25:42','2017-08-03 12:25:42',NULL),(140,1,NULL,'989741',599,'New card request charge','2017-08-03 10:01:51','2017-08-03 10:01:51',NULL),(141,NULL,1,'989728',5000,' top-up ,Test Funds','2017-08-03 09:38:12','2017-08-03 09:38:12',NULL),(152,1,NULL,'990573',1,'50','2017-08-07 13:35:46','2017-08-07 13:35:46',NULL),(153,1,NULL,'990572',1,'49','2017-08-07 13:35:42','2017-08-07 13:35:42',NULL),(154,1,NULL,'990571',1,'48','2017-08-07 13:34:30','2017-08-07 13:34:30',NULL),(155,1,NULL,'990570',1,'47','2017-08-07 13:34:22','2017-08-07 13:34:22',NULL),(156,1,NULL,'990569',1,'46','2017-08-07 13:34:19','2017-08-07 13:34:19',NULL),(157,1,NULL,'990568',1,'45','2017-08-07 13:34:15','2017-08-07 13:34:15',NULL),(158,1,NULL,'990567',1,'44','2017-08-07 13:34:12','2017-08-07 13:34:12',NULL),(159,1,NULL,'990566',1,'43','2017-08-07 13:34:06','2017-08-07 13:34:06',NULL),(160,1,NULL,'990565',1,'42','2017-08-07 13:34:02','2017-08-07 13:34:02',NULL),(161,1,NULL,'990564',1,'41','2017-08-07 13:33:58','2017-08-07 13:33:58',NULL),(162,1,NULL,'990563',1,'40','2017-08-07 13:33:53','2017-08-07 13:33:53',NULL),(163,1,NULL,'990562',1,'39','2017-08-07 13:33:49','2017-08-07 13:33:49',NULL),(164,1,NULL,'990561',1,'38','2017-08-07 13:33:41','2017-08-07 13:33:41',NULL),(165,1,NULL,'990560',1,'37','2017-08-07 13:33:37','2017-08-07 13:33:37',NULL),(166,1,NULL,'990559',1,'36','2017-08-07 13:33:33','2017-08-07 13:33:33',NULL),(167,1,NULL,'990558',1,'35','2017-08-07 13:33:23','2017-08-07 13:33:23',NULL),(168,1,NULL,'990557',1,'34','2017-08-07 13:32:56','2017-08-07 13:32:56',NULL),(169,1,NULL,'990556',1,'33','2017-08-07 13:32:51','2017-08-07 13:32:51',NULL),(170,1,NULL,'990555',1,'32','2017-08-07 13:32:37','2017-08-07 13:32:37',NULL),(171,1,NULL,'990554',1,'31','2017-08-07 13:32:32','2017-08-07 13:32:32',NULL),(172,1,NULL,'990553',1,'30','2017-08-07 13:32:01','2017-08-07 13:32:01',NULL),(173,1,NULL,'990552',1,'29','2017-08-07 13:31:56','2017-08-07 13:31:56',NULL),(174,1,NULL,'990551',1,'28','2017-08-07 13:31:52','2017-08-07 13:31:52',NULL),(175,1,NULL,'990550',1,'27','2017-08-07 13:31:48','2017-08-07 13:31:48',NULL),(176,1,NULL,'990549',1,'26','2017-08-07 13:31:43','2017-08-07 13:31:43',NULL),(177,1,NULL,'990548',1,'25','2017-08-07 13:31:38','2017-08-07 13:31:38',NULL),(178,1,NULL,'990547',1,'24','2017-08-07 13:31:34','2017-08-07 13:31:34',NULL),(179,1,NULL,'990546',1,'23','2017-08-07 13:31:30','2017-08-07 13:31:30',NULL),(180,1,NULL,'990545',1,'22','2017-08-07 13:31:25','2017-08-07 13:31:25',NULL),(181,1,NULL,'990544',1,'21','2017-08-07 13:31:21','2017-08-07 13:31:21',NULL),(182,1,NULL,'990543',1,'20','2017-08-07 13:31:17','2017-08-07 13:31:17',NULL),(183,1,NULL,'990542',1,'19','2017-08-07 13:31:13','2017-08-07 13:31:13',NULL),(184,1,NULL,'990541',1,'18','2017-08-07 13:31:09','2017-08-07 13:31:09',NULL),(185,1,NULL,'990540',1,'17','2017-08-07 13:31:05','2017-08-07 13:31:05',NULL),(186,1,NULL,'990539',1,'16','2017-08-07 13:31:02','2017-08-07 13:31:02',NULL),(187,1,NULL,'990538',1,'15','2017-08-07 13:30:57','2017-08-07 13:30:57',NULL),(188,1,NULL,'990537',1,'14','2017-08-07 13:30:54','2017-08-07 13:30:54',NULL),(189,1,NULL,'990536',1,'13','2017-08-07 13:30:50','2017-08-07 13:30:50',NULL),(190,1,NULL,'990535',1,'12','2017-08-07 13:30:44','2017-08-07 13:30:44',NULL),(191,1,NULL,'990534',1,'11','2017-08-07 13:30:39','2017-08-07 13:30:39',NULL),(192,1,NULL,'990533',1,'10','2017-08-07 13:30:35','2017-08-07 13:30:35',NULL),(193,1,NULL,'990532',1,'9','2017-08-07 13:30:29','2017-08-07 13:30:29',NULL),(194,1,NULL,'990531',1,'8','2017-08-07 13:30:24','2017-08-07 13:30:24',NULL),(195,1,NULL,'990530',1,'7','2017-08-07 13:30:20','2017-08-07 13:30:20',NULL),(196,1,NULL,'990529',1,'6','2017-08-07 13:30:16','2017-08-07 13:30:16',NULL),(197,1,NULL,'990528',1,'5','2017-08-07 13:30:05','2017-08-07 13:30:05',NULL),(198,1,NULL,'990512',1,'4','2017-08-07 08:34:36','2017-08-07 08:34:36',NULL),(199,1,NULL,'990511',1,'3','2017-08-07 08:34:30','2017-08-07 08:34:30',NULL),(200,1,NULL,'990510',1,'2','2017-08-07 08:34:24','2017-08-07 08:34:24',NULL),(201,1,NULL,'990509',1,'1','2017-08-07 08:34:17','2017-08-07 08:34:17',NULL),(202,1,NULL,'989823',1,'barcos y cosas','2017-08-03 15:25:10','2017-08-03 15:25:10',NULL),(203,1,NULL,'989822',1,'4','2017-08-03 12:26:19','2017-08-03 12:26:19',NULL),(204,1,NULL,'989821',1,'3','2017-08-03 12:26:12','2017-08-03 12:26:12',NULL),(205,1,NULL,'989820',1,'2','2017-08-03 12:26:06','2017-08-03 12:26:06',NULL),(206,1,2,'992156',1,'barcos y cosas','2017-08-10 15:34:57','2017-08-10 15:34:57','This is a title'),(207,1,2,'992157',1,'barcos y cosas','2017-08-10 15:35:24','2017-08-10 15:35:24','This is a title');
/*!40000 ALTER TABLE `Transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Users`
--

DROP TABLE IF EXISTS `Users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) DEFAULT NULL,
  `username` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `confirmation_token` varchar(180) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `email_canonical` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_D5428AED92FC23A8` (`username_canonical`),
  UNIQUE KEY `UNIQ_D5428AEDC05FB297` (`confirmation_token`),
  KEY `IDX_D5428AED9B6B5FBA` (`account_id`),
  CONSTRAINT `FK_D5428AED9B6B5FBA` FOREIGN KEY (`account_id`) REFERENCES `Accounts` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Users`
--

LOCK TABLES `Users` WRITE;
/*!40000 ALTER TABLE `Users` DISABLE KEYS */;
INSERT INTO `Users` VALUES (1,1,'+34690331976','+34690331976',1,NULL,'$2y$13$PYGm.9F4627UGMTwgmF6LuCvLMLLcFG9Uoy5YB39KM668vpAkASny','2017-08-10 15:52:58',NULL,NULL,'a:0:{}',NULL,NULL),(2,2,'+34666666666','+34666666666',1,NULL,'$2y$13$ENTC3ceHkm/YME/sgZ5EkePiSHarOXvahRYooiTEeApaWyNC6M0gi','2017-08-07 10:31:50',NULL,NULL,'a:0:{}',NULL,NULL),(3,3,'+34654321123','+34654321123',1,NULL,'$2y$13$88O9kIQFMmtQ4BPZm/iT9eGpqsLviNmx/FiFGRQb1er9KJ.S6CCaK','2017-08-08 12:31:13',NULL,NULL,'a:0:{}',NULL,NULL),(4,4,'+34630012345','+34630012345',1,NULL,'$2y$13$4YicQinjCQZEyikFT7AKS.RsrGL9nNujUNiCos0lSN0un/m1zwtyq','2017-08-09 14:40:03',NULL,NULL,'a:0:{}',NULL,NULL);
/*!40000 ALTER TABLE `Users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-08-10 16:35:04
