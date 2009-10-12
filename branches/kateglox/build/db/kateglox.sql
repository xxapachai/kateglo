-- phpMyAdmin SQL Dump
-- version 3.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 12, 2009 at 05:58 PM
-- Server version: 5.1.31
-- PHP Version: 5.2.6-3ubuntu4.2

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
-- Table structure for table `discipline`
--

CREATE TABLE IF NOT EXISTS `discipline` (
  `discipline_id` int(11) NOT NULL AUTO_INCREMENT,
  `discipline_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `discipline_abbreviation` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`discipline_id`),
  UNIQUE KEY `discipline_name` (`discipline_name`),
  UNIQUE KEY `discipline_abbreviation` (`discipline_abbreviation`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=56 ;

--
-- Dumping data for table `discipline`
--

INSERT INTO `discipline` (`discipline_id`, `discipline_name`, `discipline_abbreviation`) VALUES
(1, 'Biologi', 'biologi'),
(2, 'Ekonomi', 'ekonomi'),
(3, 'Fisika', 'fisika'),
(4, 'Kimia', 'kimia'),
(5, 'Linguistik', 'linguistik'),
(6, 'Matematika', 'matematika'),
(7, 'Olahraga', 'olahraga'),
(8, 'Pariwisata', 'pariwisata'),
(9, 'Politik', 'politik'),
(10, 'Teknologi Informasi', 'teknologiinformasi'),
(11, 'Kedokteran', 'kedokteran'),
(12, 'Hukum', 'hukum'),
(13, 'Manajemen', 'manajemen'),
(14, 'Pertanian', 'pertanian'),
(15, 'Arsitektur', 'arsitektur'),
(16, 'Asuransi', 'asuransi'),
(17, 'Perbankan', 'perbankan'),
(18, 'Pendidikan', 'pendidikan'),
(19, 'Elektronika', 'elektronika'),
(20, 'Fotografi', 'fotografi'),
(21, 'Geologi', 'geologi'),
(22, 'Perikanan', 'perikanan'),
(23, 'Perkapalan', 'perkapalan'),
(24, 'Konstruksi', 'konstruksi'),
(25, 'Kristen', 'kristen'),
(26, 'Pelelangan', 'pelelangan'),
(27, 'Keuangan', 'keuangan'),
(28, 'Minyak & Gas', 'minyakgas'),
(29, 'Militer', 'militer'),
(30, 'Mesin', 'mesin'),
(31, 'Otomotif', 'otomotif'),
(32, 'Paten', 'paten'),
(33, 'Pajak', 'pajak'),
(34, 'Pelayaran', 'pelayaran'),
(35, 'Psikologi', 'psikologi'),
(36, 'Agama', 'agama'),
(37, 'Saham', 'saham'),
(38, 'Statistika', 'statistika'),
(39, 'Teknik', 'teknik'),
(40, 'Peternakan', 'peternakan'),
(41, 'Transportasi', 'transportasi'),
(42, 'Umum', 'umum'),
(43, 'Agama Islam', 'agamaislam'),
(44, 'Antropologi', 'antropologi'),
(45, 'Arkeologi', 'arkeologi'),
(46, 'Farmasi', 'farmasi'),
(47, 'Filsafat', 'filsafat'),
(48, 'Kedokteran Hewan', 'kedokteranhewan'),
(49, 'Komunikasi Massa', 'komunikasimassa'),
(50, 'Perhutanan', 'perhutanan'),
(51, 'Sastra', 'sastra'),
(52, 'Sosiologi', 'sosiologi'),
(53, 'Teknik Kimia', 'teknikkimia'),
(54, 'Penerbangan', 'penerbangan'),
(55, 'Pertambangan', 'pertambangan');

-- --------------------------------------------------------

--
-- Table structure for table `lexical`
--

CREATE TABLE IF NOT EXISTS `lexical` (
  `lexical_id` int(11) NOT NULL AUTO_INCREMENT,
  `lexical_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lexical_abbreviation` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`lexical_id`),
  UNIQUE KEY `lexical_name` (`lexical_name`),
  UNIQUE KEY `lexical_abbreviation` (`lexical_abbreviation`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `lexical`
--

INSERT INTO `lexical` (`lexical_id`, `lexical_name`, `lexical_abbreviation`) VALUES
(1, 'Nomina (kata benda)', 'n'),
(2, 'Verba (kata kerja)', 'v'),
(3, 'Adjektiva (kata sifat)', 'adj'),
(4, 'Adverbia (kata keterangan)', 'adv'),
(5, 'Pronomina (kata ganti)', 'pron'),
(6, 'Numeralia (kata bilangan)', 'num'),
(7, 'Lain-lain (preposisi, artikula, dll)', 'l');

-- --------------------------------------------------------

--
-- Table structure for table `phrase_type`
--

CREATE TABLE IF NOT EXISTS `phrase_type` (
  `phrase_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `phrase_type_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phrase_type_abbreviation` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`phrase_type_id`),
  UNIQUE KEY `phrase_type_name` (`phrase_type_name`),
  UNIQUE KEY `phrase_type_abbreviation` (`phrase_type_abbreviation`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `phrase_type`
--

INSERT INTO `phrase_type` (`phrase_type_id`, `phrase_type_name`, `phrase_type_abbreviation`) VALUES
(1, 'Kata dasar', 'r'),
(2, 'Imbuhan', 'a'),
(3, 'Kata turunan', 'd');

-- --------------------------------------------------------

--
-- Table structure for table `relation_type`
--

CREATE TABLE IF NOT EXISTS `relation_type` (
  `relation_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `relation_type_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `relation_type_abbreviation` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`relation_type_id`),
  UNIQUE KEY `relation_type_name` (`relation_type_name`),
  UNIQUE KEY `relation_type_abbreviation` (`relation_type_abbreviation`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `relation_type`
--

INSERT INTO `relation_type` (`relation_type_id`, `relation_type_name`, `relation_type_abbreviation`) VALUES
(1, 'Sinonim', 's'),
(2, 'Antonim', 'a'),
(3, 'Berkaitan', 'r'),
(4, 'Peribahasa', 'pb'),
(5, 'Turunan', 'd');

-- --------------------------------------------------------

--
-- Table structure for table `source_type`
--

CREATE TABLE IF NOT EXISTS `source_type` (
  `source_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `source_type_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `source_type_abbreviation` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`source_type_id`),
  UNIQUE KEY `source_type_name` (`source_type_name`),
  UNIQUE KEY `source_type_abbreviation` (`source_type_abbreviation`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `source_type`
--

INSERT INTO `source_type` (`source_type_id`, `source_type_name`, `source_type_abbreviation`) VALUES
(1, 'Pusat Bahasa', 'Pusba'),
(2, 'Sofia Mansoor', 'SM'),
(3, 'Bahtera', 'Bahtera'),
(4, 'Wikipedia', 'Wikipedia'),
(5, 'Daisy Subakti', 'DS');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_last_login` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_username` (`user_username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_username`, `user_password`, `user_last_login`) VALUES
(1, 'ivan@bahtera.org', '2c42e5cf1cdbafea04ed267018ef1511', '2009-10-06 12:57:20'),
(2, 'romihardiyanto@gmail.com', '910b6c78a8482033b971116f02441ce4', '2009-10-06 12:57:20'),
(3, 'arthur@purnama.de', '68830aef4dbfad181162f9251a1da51b', '2009-10-07 12:21:09');
