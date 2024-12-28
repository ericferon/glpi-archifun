ALTER TABLE `glpi_plugin_archifun_funcareas_items` 
   ADD COLUMN `plugin_archifun_funcareas_itemroles_id` INT(11) UNSIGNED NOT NULL default '0' COMMENT 'role of the relation (f.i Dev, QA, Prod, ...)' AFTER `itemtype`,
   ADD COLUMN `comment` text COMMENT 'comment about the relation' AFTER `plugin_archifun_funcareas_itemroles_id`;
 
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
