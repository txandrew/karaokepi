-- MySQL dump 10.15  Distrib 10.0.38-MariaDB, for debian-linux-gnueabihf (armv8l)
--
-- Host: localhost    Database: karaoke
-- ------------------------------------------------------
-- Server version	10.0.38-MariaDB-0+deb8u1

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
-- Temporary table structure for view `qry_avg_ratings`
--

DROP TABLE IF EXISTS `qry_avg_ratings`;
/*!50001 DROP VIEW IF EXISTS `qry_avg_ratings`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `qry_avg_ratings` (
  `youtube_id` tinyint NOT NULL,
  `avg_ratings` tinyint NOT NULL,
  `reviews` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `qry_last_played`
--

DROP TABLE IF EXISTS `qry_last_played`;
/*!50001 DROP VIEW IF EXISTS `qry_last_played`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `qry_last_played` (
  `QUEUED_BY` tinyint NOT NULL,
  `last_played` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `qry_songs_w_ratings`
--

DROP TABLE IF EXISTS `qry_songs_w_ratings`;
/*!50001 DROP VIEW IF EXISTS `qry_songs_w_ratings`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `qry_songs_w_ratings` (
  `youtube_id` tinyint NOT NULL,
  `title` tinyint NOT NULL,
  `artist` tinyint NOT NULL,
  `genre` tinyint NOT NULL,
  `song_type` tinyint NOT NULL,
  `added_by` tinyint NOT NULL,
  `added_time` tinyint NOT NULL,
  `downloaded` tinyint NOT NULL,
  `user_id` tinyint NOT NULL,
  `rating` tinyint NOT NULL,
  `favorite` tinyint NOT NULL,
  `avg_ratings` tinyint NOT NULL,
  `reviews` tinyint NOT NULL
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `tbl_history`
--

DROP TABLE IF EXISTS `tbl_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_history` (
  `youtube_id` varchar(12) NOT NULL,
  `queued_by` varchar(16) NOT NULL,
  `played` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `user_id` (`queued_by`),
  KEY `youtube_id` (`youtube_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_messages`
--

DROP TABLE IF EXISTS `tbl_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_messages` (
  `LOG_TIME` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `EXEC_FILE` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `MSG_TYPE` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `MESSAGE` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_queue`
--

DROP TABLE IF EXISTS `tbl_queue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_queue` (
  `youtube_id` varchar(12) NOT NULL,
  `queue_val` int(11) NOT NULL,
  `queued_by` varchar(16) NOT NULL,
  KEY `youtube_id` (`youtube_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_ratings`
--

DROP TABLE IF EXISTS `tbl_ratings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_ratings` (
  `youtube_id` varchar(12) NOT NULL,
  `user_id` varchar(16) NOT NULL,
  `favorite` tinyint(1) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  KEY `youtube_id` (`youtube_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_songs`
--

DROP TABLE IF EXISTS `tbl_songs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_songs` (
  `youtube_id` varchar(12) NOT NULL,
  `title` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `artist` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `genre` varchar(32) NOT NULL,
  `song_type` varchar(16) NOT NULL,
  `downloaded` tinyint(1) NOT NULL,
  `added_by` varchar(16) NOT NULL,
  `added_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `format` int(11) DEFAULT NULL,
  PRIMARY KEY (`youtube_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_status`
--

DROP TABLE IF EXISTS `tbl_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_status` (
  `status` varchar(11) NOT NULL,
  `youtube_id` varchar(12) DEFAULT NULL,
  `queued_by` varchar(16) DEFAULT NULL,
  `last_sync` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `id` int(11) NOT NULL,
  `Testing` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `youtube_id` (`youtube_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tbl_users`
--

DROP TABLE IF EXISTS `tbl_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tbl_users` (
  `user_id` varchar(16) NOT NULL DEFAULT '',
  `email` varchar(128) NOT NULL,
  `color` varchar(6) DEFAULT NULL,
  `active` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Final view structure for view `qry_avg_ratings`
--

/*!50001 DROP TABLE IF EXISTS `qry_avg_ratings`*/;
/*!50001 DROP VIEW IF EXISTS `qry_avg_ratings`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `qry_avg_ratings` AS (select `tbl_ratings`.`youtube_id` AS `youtube_id`,avg(`tbl_ratings`.`rating`) AS `avg_ratings`,count(0) AS `reviews` from `tbl_ratings` where (`tbl_ratings`.`rating` is not null) group by `tbl_ratings`.`youtube_id`) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `qry_last_played`
--

/*!50001 DROP TABLE IF EXISTS `qry_last_played`*/;
/*!50001 DROP VIEW IF EXISTS `qry_last_played`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`kpi-server`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `qry_last_played` AS (select `tbl_history`.`queued_by` AS `QUEUED_BY`,max(if((`tbl_history`.`queued_by` = 'Admin'),'1970-01-01 00:01:01',`tbl_history`.`played`)) AS `last_played` from `tbl_history` group by `tbl_history`.`queued_by`) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `qry_songs_w_ratings`
--

/*!50001 DROP TABLE IF EXISTS `qry_songs_w_ratings`*/;
/*!50001 DROP VIEW IF EXISTS `qry_songs_w_ratings`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `qry_songs_w_ratings` AS (select `s`.`youtube_id` AS `youtube_id`,`s`.`title` AS `title`,`s`.`artist` AS `artist`,`s`.`genre` AS `genre`,`s`.`song_type` AS `song_type`,`s`.`added_by` AS `added_by`,`s`.`added_time` AS `added_time`,`s`.`downloaded` AS `downloaded`,`u`.`user_id` AS `user_id`,ifnull(`r`.`rating`,0) AS `rating`,ifnull(`r`.`favorite`,0) AS `favorite`,ifnull(`a`.`avg_ratings`,0) AS `avg_ratings`,ifnull(`a`.`reviews`,0) AS `reviews` from (((`tbl_users` `u` join `tbl_songs` `s`) left join `tbl_ratings` `r` on(((`r`.`youtube_id` = `s`.`youtube_id`) and (`u`.`user_id` = `r`.`user_id`)))) left join `qry_avg_ratings` `a` on((`a`.`youtube_id` = `s`.`youtube_id`)))) */;
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

-- Dump completed on 2019-12-30 18:50:24
