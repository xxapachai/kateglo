-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 21. November 2010 um 21:31
-- Server Version: 5.1.41
-- PHP-Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `kateglox2`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `antonym`
--

CREATE TABLE IF NOT EXISTS `antonym` (
  `antonym_id` int(11) NOT NULL AUTO_INCREMENT,
  `antonym_meaning_id` int(11) NOT NULL,
  `antonym_antonym_id` int(11) NOT NULL,
  PRIMARY KEY (`antonym_id`),
  UNIQUE KEY `antonym_unique` (`antonym_meaning_id`,`antonym_antonym_id`),
  KEY `antonym_antonym_id` (`antonym_antonym_id`),
  KEY `antonym_meaning_id` (`antonym_meaning_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `class`
--

CREATE TABLE IF NOT EXISTS `class` (
  `class_id` int(11) NOT NULL AUTO_INCREMENT,
  `class_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`class_id`),
  UNIQUE KEY `class_name` (`class_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `class_category`
--

CREATE TABLE IF NOT EXISTS `class_category` (
  `class_category_id` int(11) NOT NULL AUTO_INCREMENT,
  `class_category_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`class_category_id`),
  UNIQUE KEY `class_category_name` (`class_category_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `definition`
--

CREATE TABLE IF NOT EXISTS `definition` (
  `definition_id` int(11) NOT NULL AUTO_INCREMENT,
  `definition_meaning_id` int(11) NOT NULL,
  `definition_text` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`definition_id`),
  KEY `definition_meaning_id` (`definition_meaning_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `discipline`
--

CREATE TABLE IF NOT EXISTS `discipline` (
  `discipline_id` int(11) NOT NULL AUTO_INCREMENT,
  `discipline_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`discipline_id`),
  UNIQUE KEY `discipline_name` (`discipline_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `entry`
--

CREATE TABLE IF NOT EXISTS `entry` (
  `entry_id` int(11) NOT NULL AUTO_INCREMENT,
  `entry_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`entry_id`),
  UNIQUE KEY `entry_name` (`entry_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `meaning`
--

CREATE TABLE IF NOT EXISTS `meaning` (
  `meaning_id` int(11) NOT NULL AUTO_INCREMENT,
  `meaning_entry_id` int(11) NOT NULL,
  PRIMARY KEY (`meaning_id`),
  KEY `meaning_entry_id` (`meaning_entry_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `misspelled`
--

CREATE TABLE IF NOT EXISTS `misspelled` (
  `misspelled_id` int(11) NOT NULL AUTO_INCREMENT,
  `misspelled_meaning_id` int(11) NOT NULL,
  `misspelled_misspelled_id` int(11) NOT NULL,
  PRIMARY KEY (`misspelled_id`),
  UNIQUE KEY `misspelled_meaning_id` (`misspelled_meaning_id`,`misspelled_misspelled_id`),
  UNIQUE KEY `misspelled_misspelled_id` (`misspelled_misspelled_id`),
  KEY `misspelled_meaning_id_2` (`misspelled_meaning_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `pronounciation`
--

CREATE TABLE IF NOT EXISTS `pronounciation` (
  `pronounciation_id` int(11) NOT NULL AUTO_INCREMENT,
  `pronounciation_syllabel_id` int(11) NOT NULL,
  `pronounciation_text` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `pronounciation_ipa` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`pronounciation_id`),
  KEY `pronounciation_syllabel_id` (`pronounciation_syllabel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `relation`
--

CREATE TABLE IF NOT EXISTS `relation` (
  `relation_id` int(11) NOT NULL AUTO_INCREMENT,
  `relation_meaning_id` int(11) NOT NULL,
  `relation_relation_id` int(11) NOT NULL,
  PRIMARY KEY (`relation_id`),
  UNIQUE KEY `relation_unique` (`relation_meaning_id`,`relation_relation_id`),
  KEY `relation_meaning_id` (`relation_meaning_id`),
  KEY `relation_relation_id` (`relation_relation_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rel_antonym_definition`
--

CREATE TABLE IF NOT EXISTS `rel_antonym_definition` (
  `rel_antonym_id` int(11) NOT NULL,
  `rel_definition_id` int(11) NOT NULL,
  PRIMARY KEY (`rel_antonym_id`,`rel_definition_id`),
  KEY `rel_antonym_id` (`rel_antonym_id`),
  KEY `rel_definition_id` (`rel_definition_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rel_class_category`
--

CREATE TABLE IF NOT EXISTS `rel_class_category` (
  `rel_class_id` int(11) NOT NULL,
  `rel_class_category_id` int(11) NOT NULL,
  PRIMARY KEY (`rel_class_id`),
  KEY `rel_class_category_id` (`rel_class_category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rel_definition_class`
--

CREATE TABLE IF NOT EXISTS `rel_definition_class` (
  `rel_definition_id` int(11) NOT NULL,
  `rel_class_id` int(11) NOT NULL,
  PRIMARY KEY (`rel_definition_id`),
  KEY `rel_class_id` (`rel_class_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rel_definition_discipline`
--

CREATE TABLE IF NOT EXISTS `rel_definition_discipline` (
  `rel_definition_id` int(11) NOT NULL,
  `rel_discipline_id` int(11) NOT NULL,
  PRIMARY KEY (`rel_definition_id`,`rel_discipline_id`),
  KEY `rel_definition_id` (`rel_definition_id`),
  KEY `rel_discipline_id` (`rel_discipline_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rel_meaning_type`
--

CREATE TABLE IF NOT EXISTS `rel_meaning_type` (
  `rel_meaning_id` int(11) NOT NULL,
  `rel_type_id` int(11) NOT NULL,
  PRIMARY KEY (`rel_meaning_id`),
  KEY `rel_type_id` (`rel_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rel_misspelled_definition`
--

CREATE TABLE IF NOT EXISTS `rel_misspelled_definition` (
  `rel_misspelled_id` int(11) NOT NULL,
  `rel_definition_id` int(11) NOT NULL,
  PRIMARY KEY (`rel_misspelled_id`,`rel_definition_id`),
  KEY `rel_misspelled_id` (`rel_misspelled_id`),
  KEY `rel_definition_id` (`rel_definition_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rel_relation_definition`
--

CREATE TABLE IF NOT EXISTS `rel_relation_definition` (
  `rel_relation_id` int(11) NOT NULL,
  `rel_definition_id` int(11) NOT NULL,
  PRIMARY KEY (`rel_relation_id`,`rel_definition_id`),
  UNIQUE KEY `rel_definition_id` (`rel_definition_id`),
  KEY `rel_relation_id` (`rel_relation_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rel_synonym_definition`
--

CREATE TABLE IF NOT EXISTS `rel_synonym_definition` (
  `rel_synonym_id` int(11) NOT NULL,
  `rel_definition_id` int(11) NOT NULL,
  PRIMARY KEY (`rel_synonym_id`,`rel_definition_id`),
  KEY `rel_synonym_id` (`rel_synonym_id`),
  KEY `rel_definition_id` (`rel_definition_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rel_type_category`
--

CREATE TABLE IF NOT EXISTS `rel_type_category` (
  `rel_type_id` int(11) NOT NULL,
  `rel_type_category_id` int(11) NOT NULL,
  PRIMARY KEY (`rel_type_id`),
  KEY `rel_type_category_id` (`rel_type_category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `sample`
--

CREATE TABLE IF NOT EXISTS `sample` (
  `sample_id` int(11) NOT NULL AUTO_INCREMENT,
  `sample_definition_id` int(11) NOT NULL,
  `sample_text` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`sample_id`),
  KEY `sample_definition_id` (`sample_definition_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `source`
--

CREATE TABLE IF NOT EXISTS `source` (
  `source_id` int(11) NOT NULL AUTO_INCREMENT,
  `source_entry_id` int(11) NOT NULL,
  `source_category_id` int(11) NOT NULL,
  `source_url` varchar(2100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `source_text` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`source_id`),
  KEY `source_entry_id` (`source_entry_id`),
  KEY `source_category_id` (`source_category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `source_category`
--

CREATE TABLE IF NOT EXISTS `source_category` (
  `source_category_id` int(11) NOT NULL AUTO_INCREMENT,
  `source_category_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`source_category_id`),
  UNIQUE KEY `source_category_name` (`source_category_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `syllabel`
--

CREATE TABLE IF NOT EXISTS `syllabel` (
  `syllabel_id` int(11) NOT NULL AUTO_INCREMENT,
  `syllabel_meaning_id` int(11) NOT NULL,
  `syllabel_text` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`syllabel_id`),
  KEY `syllabel_meaning_id` (`syllabel_meaning_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `synonym`
--

CREATE TABLE IF NOT EXISTS `synonym` (
  `synonym_id` int(11) NOT NULL AUTO_INCREMENT,
  `synonym_meaning_id` int(11) NOT NULL,
  `synonym_synonym_id` int(11) NOT NULL,
  PRIMARY KEY (`synonym_id`),
  UNIQUE KEY `synonym_unique` (`synonym_meaning_id`,`synonym_synonym_id`),
  KEY `synonym_meaning_id` (`synonym_meaning_id`),
  KEY `synonym_synonym_id` (`synonym_synonym_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `type`
--

CREATE TABLE IF NOT EXISTS `type` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`type_id`),
  UNIQUE KEY `type_name` (`type_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `type_category`
--

CREATE TABLE IF NOT EXISTS `type_category` (
  `type_category_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_category_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`type_category_id`),
  UNIQUE KEY `type_category_name` (`type_category_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `antonym`
--
ALTER TABLE `antonym`
  ADD CONSTRAINT `antonym_ibfk_1` FOREIGN KEY (`antonym_meaning_id`) REFERENCES `meaning` (`meaning_id`),
  ADD CONSTRAINT `antonym_ibfk_2` FOREIGN KEY (`antonym_antonym_id`) REFERENCES `meaning` (`meaning_id`);

--
-- Constraints der Tabelle `definition`
--
ALTER TABLE `definition`
  ADD CONSTRAINT `definition_ibfk_1` FOREIGN KEY (`definition_meaning_id`) REFERENCES `meaning` (`meaning_id`);

--
-- Constraints der Tabelle `meaning`
--
ALTER TABLE `meaning`
  ADD CONSTRAINT `meaning_ibfk_1` FOREIGN KEY (`meaning_entry_id`) REFERENCES `entry` (`entry_id`);

--
-- Constraints der Tabelle `misspelled`
--
ALTER TABLE `misspelled`
  ADD CONSTRAINT `misspelled_ibfk_1` FOREIGN KEY (`misspelled_meaning_id`) REFERENCES `meaning` (`meaning_id`),
  ADD CONSTRAINT `misspelled_ibfk_2` FOREIGN KEY (`misspelled_misspelled_id`) REFERENCES `meaning` (`meaning_id`);

--
-- Constraints der Tabelle `pronounciation`
--
ALTER TABLE `pronounciation`
  ADD CONSTRAINT `pronunciation_ibfk_1` FOREIGN KEY (`pronounciation_syllabel_id`) REFERENCES `syllabel` (`syllabel_id`);

--
-- Constraints der Tabelle `relation`
--
ALTER TABLE `relation`
  ADD CONSTRAINT `relation_ibfk_1` FOREIGN KEY (`relation_meaning_id`) REFERENCES `meaning` (`meaning_id`),
  ADD CONSTRAINT `relation_ibfk_2` FOREIGN KEY (`relation_relation_id`) REFERENCES `meaning` (`meaning_id`);

--
-- Constraints der Tabelle `rel_antonym_definition`
--
ALTER TABLE `rel_antonym_definition`
  ADD CONSTRAINT `rel_antonym_definition_ibfk_1` FOREIGN KEY (`rel_antonym_id`) REFERENCES `antonym` (`antonym_id`),
  ADD CONSTRAINT `rel_antonym_definition_ibfk_2` FOREIGN KEY (`rel_definition_id`) REFERENCES `definition` (`definition_id`);

--
-- Constraints der Tabelle `rel_class_category`
--
ALTER TABLE `rel_class_category`
  ADD CONSTRAINT `rel_class_category_ibfk_1` FOREIGN KEY (`rel_class_id`) REFERENCES `class` (`class_id`),
  ADD CONSTRAINT `rel_class_category_ibfk_2` FOREIGN KEY (`rel_class_category_id`) REFERENCES `class_category` (`class_category_id`);

--
-- Constraints der Tabelle `rel_definition_class`
--
ALTER TABLE `rel_definition_class`
  ADD CONSTRAINT `rel_definition_class_ibfk_1` FOREIGN KEY (`rel_definition_id`) REFERENCES `definition` (`definition_id`),
  ADD CONSTRAINT `rel_definition_class_ibfk_2` FOREIGN KEY (`rel_class_id`) REFERENCES `class` (`class_id`);

--
-- Constraints der Tabelle `rel_definition_discipline`
--
ALTER TABLE `rel_definition_discipline`
  ADD CONSTRAINT `rel_definition_discipline_ibfk_1` FOREIGN KEY (`rel_definition_id`) REFERENCES `definition` (`definition_id`),
  ADD CONSTRAINT `rel_definition_discipline_ibfk_2` FOREIGN KEY (`rel_discipline_id`) REFERENCES `discipline` (`discipline_id`);

--
-- Constraints der Tabelle `rel_meaning_type`
--
ALTER TABLE `rel_meaning_type`
  ADD CONSTRAINT `rel_meaning_type_ibfk_1` FOREIGN KEY (`rel_meaning_id`) REFERENCES `meaning` (`meaning_id`),
  ADD CONSTRAINT `rel_meaning_type_ibfk_2` FOREIGN KEY (`rel_type_id`) REFERENCES `type` (`type_id`);

--
-- Constraints der Tabelle `rel_misspelled_definition`
--
ALTER TABLE `rel_misspelled_definition`
  ADD CONSTRAINT `rel_misspelled_definition_ibfk_1` FOREIGN KEY (`rel_misspelled_id`) REFERENCES `misspelled` (`misspelled_id`),
  ADD CONSTRAINT `rel_misspelled_definition_ibfk_2` FOREIGN KEY (`rel_definition_id`) REFERENCES `definition` (`definition_id`);

--
-- Constraints der Tabelle `rel_relation_definition`
--
ALTER TABLE `rel_relation_definition`
  ADD CONSTRAINT `rel_relation_definition_ibfk_1` FOREIGN KEY (`rel_relation_id`) REFERENCES `relation` (`relation_id`),
  ADD CONSTRAINT `rel_relation_definition_ibfk_2` FOREIGN KEY (`rel_definition_id`) REFERENCES `definition` (`definition_id`);

--
-- Constraints der Tabelle `rel_synonym_definition`
--
ALTER TABLE `rel_synonym_definition`
  ADD CONSTRAINT `rel_synonym_definition_ibfk_1` FOREIGN KEY (`rel_synonym_id`) REFERENCES `synonym` (`synonym_id`),
  ADD CONSTRAINT `rel_synonym_definition_ibfk_2` FOREIGN KEY (`rel_definition_id`) REFERENCES `definition` (`definition_id`);

--
-- Constraints der Tabelle `rel_type_category`
--
ALTER TABLE `rel_type_category`
  ADD CONSTRAINT `rel_type_category_ibfk_1` FOREIGN KEY (`rel_type_id`) REFERENCES `type` (`type_id`),
  ADD CONSTRAINT `rel_type_category_ibfk_2` FOREIGN KEY (`rel_type_category_id`) REFERENCES `type_category` (`type_category_id`);

--
-- Constraints der Tabelle `sample`
--
ALTER TABLE `sample`
  ADD CONSTRAINT `sample_ibfk_1` FOREIGN KEY (`sample_definition_id`) REFERENCES `definition` (`definition_id`);

--
-- Constraints der Tabelle `source`
--
ALTER TABLE `source`
  ADD CONSTRAINT `source_ibfk_1` FOREIGN KEY (`source_entry_id`) REFERENCES `entry` (`entry_id`),
  ADD CONSTRAINT `source_ibfk_2` FOREIGN KEY (`source_category_id`) REFERENCES `source_category` (`source_category_id`);

--
-- Constraints der Tabelle `syllabel`
--
ALTER TABLE `syllabel`
  ADD CONSTRAINT `syllabel_ibfk_1` FOREIGN KEY (`syllabel_meaning_id`) REFERENCES `meaning` (`meaning_id`);

--
-- Constraints der Tabelle `synonym`
--
ALTER TABLE `synonym`
  ADD CONSTRAINT `synonym_ibfk_1` FOREIGN KEY (`synonym_meaning_id`) REFERENCES `meaning` (`meaning_id`),
  ADD CONSTRAINT `synonym_ibfk_2` FOREIGN KEY (`synonym_synonym_id`) REFERENCES `meaning` (`meaning_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
