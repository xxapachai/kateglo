--
-- Database: `kateglo`
--

-- --------------------------------------------------------

--
-- Table structure for table `definition`
--

DROP TABLE IF EXISTS `definition`;
CREATE TABLE IF NOT EXISTS `definition` (
  `def_uid` int(11) NOT NULL AUTO_INCREMENT,
  `phrase` varchar(255) NOT NULL,
  `def_num` tinyint(4) NOT NULL DEFAULT '1',
  `def_text` varchar(4000) NOT NULL,
  `discipline` varchar(16) DEFAULT NULL,
  `sample` varchar(4000) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updater` varchar(16) NOT NULL,
  PRIMARY KEY (`def_uid`)
);

-- --------------------------------------------------------

--
-- Table structure for table `derivation`
--

DROP TABLE IF EXISTS `derivation`;
CREATE TABLE IF NOT EXISTS `derivation` (
  `drv_uid` int(11) NOT NULL AUTO_INCREMENT,
  `root_phrase` varchar(255) NOT NULL,
  `derived_phrase` varchar(255) NOT NULL,
  `drv_type` varchar(16) NOT NULL,
  `updated` datetime DEFAULT NULL,
  `updater` varchar(16) NOT NULL,
  PRIMARY KEY (`drv_uid`)
);

-- --------------------------------------------------------

--
-- Table structure for table `derivation_type`
--

DROP TABLE IF EXISTS `derivation_type`;
CREATE TABLE IF NOT EXISTS `derivation_type` (
  `drv_type` varchar(16) NOT NULL COMMENT 'a=affix; c=compound',
  `drv_type_name` varchar(255) NOT NULL,
  `sort_order` tinyint(4) NOT NULL DEFAULT '1',
  `updated` datetime DEFAULT NULL,
  `updater` varchar(16) NOT NULL,
  PRIMARY KEY (`drv_type`)
);

-- --------------------------------------------------------

--
-- Table structure for table `discipline`
--

DROP TABLE IF EXISTS `discipline`;
CREATE TABLE IF NOT EXISTS `discipline` (
  `discipline` varchar(16) NOT NULL,
  `discipline_name` varchar(255) NOT NULL,
  `updated` datetime DEFAULT NULL,
  `updater` varchar(16) NOT NULL,
  PRIMARY KEY (`discipline`)
);

-- --------------------------------------------------------

--
-- Table structure for table `lexical_class`
--

DROP TABLE IF EXISTS `lexical_class`;
CREATE TABLE IF NOT EXISTS `lexical_class` (
  `lex_class` varchar(16) NOT NULL,
  `lex_class_name` varchar(255) NOT NULL,
  `sort_order` tinyint(4) NOT NULL DEFAULT '1',
  `updated` datetime DEFAULT NULL,
  `updater` varchar(16) NOT NULL,
  PRIMARY KEY (`lex_class`)
);

-- --------------------------------------------------------

--
-- Table structure for table `phrase`
--

DROP TABLE IF EXISTS `phrase`;
CREATE TABLE IF NOT EXISTS `phrase` (
  `phrase` varchar(255) NOT NULL,
  `lex_class` varchar(16) NOT NULL,
  `pronounciation` varchar(4000) DEFAULT NULL,
  `etymology` varchar(4000) DEFAULT NULL,
  `actual_phrase` varchar(255) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updater` varchar(16) NOT NULL,
  PRIMARY KEY (`phrase`)
);

-- --------------------------------------------------------

--
-- Table structure for table `relation`
--

DROP TABLE IF EXISTS `relation`;
CREATE TABLE IF NOT EXISTS `relation` (
  `rel_uid` int(11) NOT NULL AUTO_INCREMENT,
  `root_phrase` varchar(255) NOT NULL,
  `related_phrase` varchar(255) NOT NULL,
  `rel_type` varchar(16) NOT NULL,
  `updated` datetime DEFAULT NULL,
  `updater` varchar(16) NOT NULL,
  PRIMARY KEY (`rel_uid`)
);

-- --------------------------------------------------------

--
-- Table structure for table `relation_type`
--

DROP TABLE IF EXISTS `relation_type`;
CREATE TABLE IF NOT EXISTS `relation_type` (
  `rel_type` varchar(16) NOT NULL COMMENT 's=synonym, a=antonym, o=other',
  `rel_type_name` varchar(255) NOT NULL,
  `sort_order` tinyint(4) NOT NULL DEFAULT '1',
  `updated` datetime DEFAULT NULL,
  `updater` varchar(16) NOT NULL,
  PRIMARY KEY (`rel_type`)
);

-- --------------------------------------------------------

--
-- Table structure for table `sys_action`
--

DROP TABLE IF EXISTS `sys_action`;
CREATE TABLE IF NOT EXISTS `sys_action` (
  `ses_uid` int(11) NOT NULL,
  `action_time` datetime NOT NULL,
  `action_type` varchar(16) DEFAULT NULL,
  `module` varchar(16) DEFAULT NULL,
  `description` varchar(4000) DEFAULT NULL,
  PRIMARY KEY (`ses_uid`,`action_time`)
);

-- --------------------------------------------------------

--
-- Table structure for table `sys_session`
--

DROP TABLE IF EXISTS `sys_session`;
CREATE TABLE IF NOT EXISTS `sys_session` (
  `ses_uid` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(16) DEFAULT NULL,
  `user_id` varchar(16) DEFAULT NULL,
  `started` datetime DEFAULT NULL,
  `ended` datetime DEFAULT NULL,
  PRIMARY KEY (`ses_uid`)
) AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `sys_user`
--

DROP TABLE IF EXISTS `sys_user`;
CREATE TABLE IF NOT EXISTS `sys_user` (
  `user_id` varchar(16) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `pass_key` varchar(16) DEFAULT NULL,
  `last_access` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  `updater` varchar(16) NOT NULL,
  PRIMARY KEY (`user_id`)
);
