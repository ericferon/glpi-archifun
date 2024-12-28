
-- -----------------------------------------------------
-- Table `glpi_plugin_archifun_funcareas`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `glpi_plugin_archifun_funcareas`;
CREATE TABLE `glpi_plugin_archifun_funcareas` (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entities_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `is_recursive` tinyint(1) NOT NULL default '0',
  `plugin_archifun_funcareas_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `completename` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `level` int(11) NOT NULL DEFAULT '0',
  `sons_cache` longtext COLLATE utf8mb4_unicode_ci,
  `ancestors_cache` longtext COLLATE utf8mb4_unicode_ci,
  `users_id` int(11) UNSIGNED NOT NULL default '0' COMMENT 'RELATION to glpi_users (id)',
  `groups_id` int(11) UNSIGNED NOT NULL default '0' COMMENT 'RELATION to glpi_groups (id)',
  `date_mod` timestamp default NULL,
  `is_helpdesk_visible` int(11) NOT NULL default '1',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unicity` (`plugin_archifun_funcareas_id`,`name`),
  KEY `users_id` (`users_id`),
  KEY `groups_id` (`groups_id`),
  KEY date_mod (date_mod),
  KEY is_helpdesk_visible (is_helpdesk_visible),
  KEY `is_deleted` (`is_deleted`),
  KEY `plugin_archifun_funcareas_id` (`plugin_archifun_funcareas_id`)
) AUTO_INCREMENT=756 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ;

-- ----------------------------------------------------------------
-- Table `glpi_plugin_archifun_funcareas_items`
-- ----------------------------------------------------------------
DROP TABLE IF EXISTS `glpi_plugin_archifun_funcareas_items`;
CREATE TABLE `glpi_plugin_archifun_funcareas_items` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`plugin_archifun_funcareas_id` int(11) UNSIGNED NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_archifun_funcareas (id)',
	`items_id` int(11) UNSIGNED NOT NULL default '0' COMMENT 'RELATION to various tables, according to itemtype (id)',
    `itemtype` varchar(100) collate utf8mb4_unicode_ci NOT NULL COMMENT 'see .class.php file',
	`plugin_archifun_funcareas_itemroles_id` INT(11) UNSIGNED NOT NULL default '0' COMMENT 'role of the relation (f.i Dev, QA, Prod, ...)',
	`comment` text COMMENT 'comment about the relation',
	PRIMARY KEY  (`id`),
	UNIQUE KEY `unicity` (`plugin_archifun_funcareas_id`,`items_id`,`itemtype`,`plugin_archifun_funcareas_itemroles_id`),
  KEY `FK_device` (`items_id`,`itemtype`),
  KEY `item` (`itemtype`,`items_id`)
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------
-- Table `glpi_plugin_archifun_funcareas_itemroles`
-- ----------------------------------------------------------------
DROP TABLE IF EXISTS `glpi_plugin_archifun_funcareas_itemroles`;
CREATE TABLE `glpi_plugin_archifun_funcareas_itemroles` (
	`id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`itemtype` varchar(100) collate utf8mb4_unicode_ci NOT NULL COMMENT 'see .class.php file',
	`name` VARCHAR(45) NOT NULL ,
	`comment` VARCHAR(45) NULL ,
	PRIMARY KEY  (`id`),
	UNIQUE INDEX `unicity` (`itemtype`,`name`)
)  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `glpi_plugin_archifun_profiles`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `glpi_plugin_archifun_profiles`;
CREATE TABLE `glpi_plugin_archifun_profiles` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`profiles_id` int(11) UNSIGNED NOT NULL default '0' COMMENT 'RELATION to glpi_profiles (id)',
	`archifun` char(1) collate utf8mb4_unicode_ci default NULL,
	`open_ticket` char(1) collate utf8mb4_unicode_ci default NULL,
	PRIMARY KEY  (`id`),
	KEY `profiles_id` (`profiles_id`)
) DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginArchifunFuncarea','2','2','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginArchifunFuncarea','6','3','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginArchifunFuncarea','7','4','0');
	
