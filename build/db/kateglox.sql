-- phpMyAdmin SQL Dump
-- version 3.1.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 14, 2009 at 07:06 AM
-- Server version: 5.0.67
-- PHP Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `kateglox`
--

-- --------------------------------------------------------

--
-- Table structure for table `definition`
--

CREATE TABLE IF NOT EXISTS `definition` (
  `definition_id` int(11) NOT NULL auto_increment,
  `definition_phrase_id` int(11) NOT NULL,
  `definition_text` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`definition_id`),
  UNIQUE KEY `definition_phrase_id` (`definition_phrase_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=69014 ;

-- --------------------------------------------------------

--
-- Table structure for table `discipline`
--

CREATE TABLE IF NOT EXISTS `discipline` (
  `discipline_id` int(11) NOT NULL auto_increment,
  `discipline_name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `discipline_abbreviation` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`discipline_id`),
  UNIQUE KEY `discipline_name` (`discipline_name`),
  UNIQUE KEY `discipline_abbreviation` (`discipline_abbreviation`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=56 ;

-- --------------------------------------------------------

--
-- Table structure for table `lexical`
--

CREATE TABLE IF NOT EXISTS `lexical` (
  `lexical_id` int(11) NOT NULL auto_increment,
  `lexical_name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `lexical_abbreviation` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`lexical_id`),
  UNIQUE KEY `lexical_name` (`lexical_name`),
  UNIQUE KEY `lexical_abbreviation` (`lexical_abbreviation`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `phrase`
--

CREATE TABLE IF NOT EXISTS `phrase` (
  `phrase_id` int(11) NOT NULL auto_increment,
  `phrase_type_id` int(11) NOT NULL,
  `phrase_lexical_id` int(11) NOT NULL,
  `phrase_name` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`phrase_id`),
  UNIQUE KEY `phrase_name` (`phrase_name`),
  KEY `phrase_lexical_id` (`phrase_lexical_id`),
  KEY `phrase_type_id` (`phrase_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=69134 ;

-- --------------------------------------------------------

--
-- Table structure for table `phrase_type`
--

CREATE TABLE IF NOT EXISTS `phrase_type` (
  `phrase_type_id` int(11) NOT NULL auto_increment,
  `phrase_type_name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `phrase_type_abbreviation` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`phrase_type_id`),
  UNIQUE KEY `phrase_type_name` (`phrase_type_name`),
  UNIQUE KEY `phrase_type_abbreviation` (`phrase_type_abbreviation`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `proverb`
--

CREATE TABLE IF NOT EXISTS `proverb` (
  `proverb_id` int(11) NOT NULL auto_increment,
  `proverb_phrase_id` int(11) NOT NULL,
  `proverb_text` text collate utf8_unicode_ci NOT NULL,
  `proverb_meaning` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`proverb_id`),
  KEY `proverb_phrase_id` (`proverb_phrase_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2370 ;

-- --------------------------------------------------------

--
-- Table structure for table `relation`
--

CREATE TABLE IF NOT EXISTS `relation` (
  `relation_id` int(11) NOT NULL auto_increment,
  `relation_type_id` int(11) NOT NULL,
  `relation_phrase_id` int(11) NOT NULL,
  `relation_phrase_relation` int(11) NOT NULL,
  PRIMARY KEY  (`relation_id`),
  UNIQUE KEY `relation_unique` (`relation_phrase_id`,`relation_phrase_relation`),
  KEY `relation_type_id` (`relation_type_id`),
  KEY `relation_phrase_id` (`relation_phrase_id`),
  KEY `relation_phrase_relation` (`relation_phrase_relation`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=71936 ;

-- --------------------------------------------------------

--
-- Table structure for table `relation_type`
--

CREATE TABLE IF NOT EXISTS `relation_type` (
  `relation_type_id` int(11) NOT NULL auto_increment,
  `relation_type_name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `relation_type_abbreviation` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`relation_type_id`),
  UNIQUE KEY `relation_type_name` (`relation_type_name`),
  UNIQUE KEY `relation_type_abbreviation` (`relation_type_abbreviation`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `source`
--

CREATE TABLE IF NOT EXISTS `source` (
  `source_id` int(11) NOT NULL auto_increment,
  `source_phrase_id` int(11) NOT NULL,
  `source_type_id` int(11) NOT NULL,
  `source_url` varchar(1020) collate utf8_unicode_ci NOT NULL,
  `source_label` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`source_id`),
  KEY `source_phrase_id` (`source_phrase_id`),
  KEY `source_type_id` (`source_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `source_type`
--

CREATE TABLE IF NOT EXISTS `source_type` (
  `source_type_id` int(11) NOT NULL auto_increment,
  `source_type_name` varchar(255) collate utf8_unicode_ci NOT NULL,
  `source_type_abbreviation` varchar(255) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`source_type_id`),
  UNIQUE KEY `source_type_name` (`source_type_name`),
  UNIQUE KEY `source_type_abbreviation` (`source_type_abbreviation`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL auto_increment,
  `user_username` varchar(255) collate utf8_unicode_ci default NULL,
  `user_password` varchar(255) collate utf8_unicode_ci default NULL,
  `user_last_login` timestamp NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`user_id`),
  UNIQUE KEY `user_username` (`user_username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `definition`
--
ALTER TABLE `definition`
  ADD CONSTRAINT `definition_ibfk_1` FOREIGN KEY (`definition_phrase_id`) REFERENCES `phrase` (`phrase_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `phrase`
--
ALTER TABLE `phrase`
  ADD CONSTRAINT `phrase_ibfk_2` FOREIGN KEY (`phrase_lexical_id`) REFERENCES `lexical` (`lexical_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `phrase_ibfk_1` FOREIGN KEY (`phrase_type_id`) REFERENCES `phrase_type` (`phrase_type_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `proverb`
--
ALTER TABLE `proverb`
  ADD CONSTRAINT `proverb_ibfk_1` FOREIGN KEY (`proverb_phrase_id`) REFERENCES `phrase` (`phrase_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `relation`
--
ALTER TABLE `relation`
  ADD CONSTRAINT `relation_ibfk_3` FOREIGN KEY (`relation_phrase_relation`) REFERENCES `phrase` (`phrase_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `relation_ibfk_1` FOREIGN KEY (`relation_type_id`) REFERENCES `relation_type` (`relation_type_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `relation_ibfk_2` FOREIGN KEY (`relation_phrase_id`) REFERENCES `phrase` (`phrase_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `source`
--
ALTER TABLE `source`
  ADD CONSTRAINT `source_ibfk_2` FOREIGN KEY (`source_type_id`) REFERENCES `source_type` (`source_type_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `source_ibfk_1` FOREIGN KEY (`source_phrase_id`) REFERENCES `phrase` (`phrase_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
