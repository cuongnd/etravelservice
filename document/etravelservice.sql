/*
SQLyog Ultimate v11.11 (32 bit)
MySQL - 5.6.21 : Database - etravelservice
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `etravelservice_cityarea` */

DROP TABLE IF EXISTS `etravelservice_cityarea`;

CREATE TABLE `etravelservice_cityarea` (
  `virtuemart_cityarea_id` int(11) NOT NULL DEFAULT '0',
  `sdsds` int(11) NOT NULL,
  `city_area_name` int(11) DEFAULT NULL,
  `airport_code` int(11) DEFAULT NULL,
  `virtuemart_state_id` int(11) DEFAULT NULL,
  `dfd` int(11) NOT NULL,
  `ordering` int(11) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `shared` int(11) DEFAULT NULL,
  `phone_code` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` datetime DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  `s232dsds` int(11) NOT NULL,
  PRIMARY KEY (`virtuemart_cityarea_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `etravelservice_countries` */

DROP TABLE IF EXISTS `etravelservice_countries`;

CREATE TABLE `etravelservice_countries` (
  `virtuemart_country_id` int(11) NOT NULL DEFAULT '0',
  `code` int(11) DEFAULT NULL,
  `phone_code` int(11) DEFAULT NULL,
  `state_number` int(11) DEFAULT NULL,
  `virtuemart_worldzone_id` int(11) DEFAULT NULL,
  `country_name` int(11) DEFAULT NULL,
  `iso2` int(11) DEFAULT NULL,
  `iso3` int(11) DEFAULT NULL,
  `flag` int(11) DEFAULT NULL,
  `country_3_code` int(11) DEFAULT NULL,
  `image` int(11) DEFAULT NULL,
  `country_2_code` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` datetime DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`virtuemart_country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `etravelservice_currencies` */

DROP TABLE IF EXISTS `etravelservice_currencies`;

CREATE TABLE `etravelservice_currencies` (
  `virtuemart_currency_id` int(11) NOT NULL DEFAULT '0',
  `virtuemart_vendor_id` int(11) DEFAULT NULL,
  `currency_name` int(11) DEFAULT NULL,
  `currency_code_2` int(11) DEFAULT NULL,
  `currency_code` int(11) DEFAULT NULL,
  `iso2` int(11) DEFAULT NULL,
  `iso3` int(11) DEFAULT NULL,
  `sign` int(11) DEFAULT NULL,
  `virtuemart_country_id` int(11) DEFAULT NULL,
  `currency_numeric_code` int(11) DEFAULT NULL,
  `currency_exchange_rate` int(11) DEFAULT NULL,
  `currency_symbol` int(11) DEFAULT NULL,
  `currency_decimal_place` int(11) DEFAULT NULL,
  `currency_decimal_symbol` int(11) DEFAULT NULL,
  `currency_thousands` int(11) DEFAULT NULL,
  `currency_positive_style` int(11) DEFAULT NULL,
  `currency_negative_style` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `shared` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` datetime DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`virtuemart_currency_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `etravelservice_group_size` */

DROP TABLE IF EXISTS `etravelservice_group_size`;

CREATE TABLE `etravelservice_group_size` (
  `virtuemart_group_size_id` int(11) NOT NULL DEFAULT '0',
  `group_name` int(11) DEFAULT NULL,
  `from` int(11) DEFAULT NULL,
  `to` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `shared` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` datetime DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`virtuemart_group_size_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `etravelservice_modules` */

DROP TABLE IF EXISTS `etravelservice_modules`;

CREATE TABLE `etravelservice_modules` (
  `module_id` int(11) NOT NULL DEFAULT '0',
  `module_name` int(11) DEFAULT NULL,
  `module_description` int(11) DEFAULT NULL,
  `module_perms` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `is_admin` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  PRIMARY KEY (`module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `etravelservice_physicalgrade` */

DROP TABLE IF EXISTS `etravelservice_physicalgrade`;

CREATE TABLE `etravelservice_physicalgrade` (
  `virtuemart_physicalgrade_id` int(11) NOT NULL DEFAULT '0',
  `title` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `shared` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` datetime DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`virtuemart_physicalgrade_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `etravelservice_products` */

DROP TABLE IF EXISTS `etravelservice_products`;

CREATE TABLE `etravelservice_products` (
  `virtuemart_product_id` int(11) NOT NULL DEFAULT '0',
  `virtuemart_vendor_id` int(11) DEFAULT NULL,
  `product_parent_id` int(11) DEFAULT NULL,
  `virtuemart_tour_type_id` int(11) DEFAULT NULL,
  `virtuemart_tour_style_id` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `virtuemart_physicalgrade_id` int(11) DEFAULT NULL,
  `virtuemart_tour_section_id` int(11) DEFAULT NULL,
  `product_sku` int(11) DEFAULT NULL,
  `tour_methor` int(11) DEFAULT NULL,
  `tour_length` int(11) DEFAULT NULL,
  `min_person` int(11) DEFAULT NULL,
  `max_person` int(11) DEFAULT NULL,
  `min_age` int(11) DEFAULT NULL,
  `max_age` int(11) DEFAULT NULL,
  `start_city` int(11) DEFAULT NULL,
  `end_city` int(11) DEFAULT NULL,
  `highlights` mediumtext,
  `inclusions` mediumtext,
  `exclusions` mediumtext,
  `meta_name` mediumtext,
  `product_special` int(11) DEFAULT NULL,
  `product_params` int(11) DEFAULT NULL,
  `hits` int(11) DEFAULT NULL,
  `intnotes` int(11) DEFAULT NULL,
  `metaauthor` int(11) DEFAULT NULL,
  `layout` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `pordering` int(11) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` datetime DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`virtuemart_product_id`),
  KEY `virtuemart_tour_type_id` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_style_id` (`virtuemart_tour_style_id`),
  KEY `virtuemart_physicalgrade_id` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_tour_section_id` (`virtuemart_tour_section_id`),
  KEY `start_city` (`start_city`),
  KEY `end_city` (`end_city`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `etravelservice_room` */

DROP TABLE IF EXISTS `etravelservice_room`;

CREATE TABLE `etravelservice_room` (
  `virtuemart_room_id` int(11) NOT NULL DEFAULT '0',
  `room_name` int(11) DEFAULT NULL,
  `icon` int(11) DEFAULT NULL,
  `image1` int(11) DEFAULT NULL,
  `image2` int(11) DEFAULT NULL,
  `virtuemart_hotel_id` int(11) DEFAULT NULL,
  `facilities` text,
  `key_word` int(11) DEFAULT NULL,
  `description` text,
  `ordering` int(11) DEFAULT NULL,
  `shared` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` datetime DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`virtuemart_room_id`),
  KEY `virtuemart_hotel_id` (`virtuemart_hotel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `etravelservice_tour_id_country_id` */

DROP TABLE IF EXISTS `etravelservice_tour_id_country_id`;

CREATE TABLE `etravelservice_tour_id_country_id` (
  `virtuemart_country_id` int(11) NOT NULL DEFAULT '0',
  `virtuemart_product_id` int(11) NOT NULL DEFAULT '0',
  `ordering` int(11) DEFAULT NULL,
  PRIMARY KEY (`virtuemart_country_id`,`virtuemart_product_id`),
  KEY `virtuemart_product_id` (`virtuemart_product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `etravelservice_tour_id_group_size_id` */

DROP TABLE IF EXISTS `etravelservice_tour_id_group_size_id`;

CREATE TABLE `etravelservice_tour_id_group_size_id` (
  `virtuemart_group_size_id` int(11) NOT NULL DEFAULT '0',
  `virtuemart_product_id` int(11) NOT NULL DEFAULT '0',
  `ordering` int(11) DEFAULT NULL,
  PRIMARY KEY (`virtuemart_group_size_id`,`virtuemart_product_id`),
  KEY `virtuemart_product_id` (`virtuemart_product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `etravelservice_tour_id_service_class_id` */

DROP TABLE IF EXISTS `etravelservice_tour_id_service_class_id`;

CREATE TABLE `etravelservice_tour_id_service_class_id` (
  `virtuemart_product_id` int(11) NOT NULL DEFAULT '0',
  `virtuemart_service_class_id` int(11) NOT NULL DEFAULT '0',
  `ordering` int(11) DEFAULT NULL,
  PRIMARY KEY (`virtuemart_product_id`,`virtuemart_service_class_id`),
  KEY `virtuemart_service_class_id` (`virtuemart_service_class_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `etravelservice_tour_section` */

DROP TABLE IF EXISTS `etravelservice_tour_section`;

CREATE TABLE `etravelservice_tour_section` (
  `virtuemart_tour_section_id` int(11) NOT NULL DEFAULT '0',
  `title` int(11) DEFAULT NULL,
  `icon` int(11) DEFAULT NULL,
  `meta_title` int(11) DEFAULT NULL,
  `key_word` int(11) DEFAULT NULL,
  `description` text,
  `ordering` int(11) DEFAULT NULL,
  `shared` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` datetime DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`virtuemart_tour_section_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `etravelservice_tour_service_class` */

DROP TABLE IF EXISTS `etravelservice_tour_service_class`;

CREATE TABLE `etravelservice_tour_service_class` (
  `virtuemart_service_class_id` int(11) NOT NULL DEFAULT '0',
  `service_class_name` int(11) DEFAULT NULL,
  `icon` int(11) DEFAULT NULL,
  `meta_title` int(11) DEFAULT NULL,
  `key_word` int(11) DEFAULT NULL,
  `description` text,
  `ordering` int(11) DEFAULT NULL,
  `shared` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` datetime DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`virtuemart_service_class_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `etravelservice_tour_style` */

DROP TABLE IF EXISTS `etravelservice_tour_style`;

CREATE TABLE `etravelservice_tour_style` (
  `virtuemart_tour_style_id` int(11) NOT NULL DEFAULT '0',
  `title` int(11) DEFAULT NULL,
  `icon` int(11) DEFAULT NULL,
  `meta_title` int(11) DEFAULT NULL,
  `key_word` int(11) DEFAULT NULL,
  `description` text,
  `ordering` int(11) DEFAULT NULL,
  `shared` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` datetime DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`virtuemart_tour_style_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `etravelservice_tour_type` */

DROP TABLE IF EXISTS `etravelservice_tour_type`;

CREATE TABLE `etravelservice_tour_type` (
  `virtuemart_tour_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` int(11) DEFAULT NULL,
  `icon` int(11) DEFAULT NULL,
  `meta_title` int(11) DEFAULT NULL,
  `key_word` int(11) DEFAULT NULL,
  `description` text,
  `ordering` int(11) DEFAULT NULL,
  `shared` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` datetime DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` datetime DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`virtuemart_tour_type_id`),
  UNIQUE KEY `virtuemart_tour_type_id_2` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_3` (`virtuemart_tour_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `me1u8_ak_profiles` */

DROP TABLE IF EXISTS `me1u8_ak_profiles`;

CREATE TABLE `me1u8_ak_profiles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(255) NOT NULL,
  `configuration` longtext,
  `filters` longtext,
  `quickicon` tinyint(3) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_ak_stats` */

DROP TABLE IF EXISTS `me1u8_ak_stats`;

CREATE TABLE `me1u8_ak_stats` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `description` varchar(255) NOT NULL,
  `comment` longtext,
  `backupstart` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `backupend` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` enum('run','fail','complete') NOT NULL DEFAULT 'run',
  `origin` varchar(30) NOT NULL DEFAULT 'backend',
  `type` varchar(30) NOT NULL DEFAULT 'full',
  `profile_id` bigint(20) NOT NULL DEFAULT '1',
  `archivename` longtext,
  `absolute_path` longtext,
  `multipart` int(11) NOT NULL DEFAULT '0',
  `tag` varchar(255) DEFAULT NULL,
  `backupid` varchar(255) DEFAULT NULL,
  `filesexist` tinyint(3) NOT NULL DEFAULT '1',
  `remote_filename` varchar(1000) DEFAULT NULL,
  `total_size` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_fullstatus` (`filesexist`,`status`),
  KEY `idx_stale` (`status`,`origin`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_ak_storage` */

DROP TABLE IF EXISTS `me1u8_ak_storage`;

CREATE TABLE `me1u8_ak_storage` (
  `tag` varchar(255) NOT NULL,
  `lastupdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `data` longtext,
  PRIMARY KEY (`tag`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_akeeba_common` */

DROP TABLE IF EXISTS `me1u8_akeeba_common`;

CREATE TABLE `me1u8_akeeba_common` (
  `key` varchar(255) NOT NULL,
  `value` longtext NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_assets` */

DROP TABLE IF EXISTS `me1u8_assets`;

CREATE TABLE `me1u8_assets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Nested set parent.',
  `lft` int(11) NOT NULL DEFAULT '0' COMMENT 'Nested set lft.',
  `rgt` int(11) NOT NULL DEFAULT '0' COMMENT 'Nested set rgt.',
  `level` int(10) unsigned NOT NULL COMMENT 'The cached level in the nested tree.',
  `name` varchar(50) NOT NULL COMMENT 'The unique name for the asset.\n',
  `title` varchar(100) NOT NULL COMMENT 'The descriptive title for the asset.',
  `rules` varchar(5120) NOT NULL COMMENT 'JSON encoded access control.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_asset_name` (`name`),
  KEY `idx_lft_rgt` (`lft`,`rgt`),
  KEY `idx_parent_id` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=99 DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_associations` */

DROP TABLE IF EXISTS `me1u8_associations`;

CREATE TABLE `me1u8_associations` (
  `id` int(11) NOT NULL COMMENT 'A reference to the associated item.',
  `context` varchar(50) NOT NULL COMMENT 'The context of the associated item.',
  `key` char(32) NOT NULL COMMENT 'The key for the association computed from an md5 on associated ids.',
  PRIMARY KEY (`context`,`id`),
  KEY `idx_key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_banner_clients` */

DROP TABLE IF EXISTS `me1u8_banner_clients`;

CREATE TABLE `me1u8_banner_clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `contact` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `extrainfo` text NOT NULL,
  `state` tinyint(3) NOT NULL DEFAULT '0',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `metakey` text NOT NULL,
  `own_prefix` tinyint(4) NOT NULL DEFAULT '0',
  `metakey_prefix` varchar(255) NOT NULL DEFAULT '',
  `purchase_type` tinyint(4) NOT NULL DEFAULT '-1',
  `track_clicks` tinyint(4) NOT NULL DEFAULT '-1',
  `track_impressions` tinyint(4) NOT NULL DEFAULT '-1',
  PRIMARY KEY (`id`),
  KEY `idx_own_prefix` (`own_prefix`),
  KEY `idx_metakey_prefix` (`metakey_prefix`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_banner_tracks` */

DROP TABLE IF EXISTS `me1u8_banner_tracks`;

CREATE TABLE `me1u8_banner_tracks` (
  `track_date` datetime NOT NULL,
  `track_type` int(10) unsigned NOT NULL,
  `banner_id` int(10) unsigned NOT NULL,
  `count` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`track_date`,`track_type`,`banner_id`),
  KEY `idx_track_date` (`track_date`),
  KEY `idx_track_type` (`track_type`),
  KEY `idx_banner_id` (`banner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_banners` */

DROP TABLE IF EXISTS `me1u8_banners`;

CREATE TABLE `me1u8_banners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `imptotal` int(11) NOT NULL DEFAULT '0',
  `impmade` int(11) NOT NULL DEFAULT '0',
  `clicks` int(11) NOT NULL DEFAULT '0',
  `clickurl` varchar(200) NOT NULL DEFAULT '',
  `state` tinyint(3) NOT NULL DEFAULT '0',
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  `custombannercode` varchar(2048) NOT NULL,
  `sticky` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `metakey` text NOT NULL,
  `params` text NOT NULL,
  `own_prefix` tinyint(1) NOT NULL DEFAULT '0',
  `metakey_prefix` varchar(255) NOT NULL DEFAULT '',
  `purchase_type` tinyint(4) NOT NULL DEFAULT '-1',
  `track_clicks` tinyint(4) NOT NULL DEFAULT '-1',
  `track_impressions` tinyint(4) NOT NULL DEFAULT '-1',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `reset` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `language` char(7) NOT NULL DEFAULT '',
  `created_by` int(10) unsigned NOT NULL DEFAULT '0',
  `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_state` (`state`),
  KEY `idx_own_prefix` (`own_prefix`),
  KEY `idx_metakey_prefix` (`metakey_prefix`),
  KEY `idx_banner_catid` (`catid`),
  KEY `idx_language` (`language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_categories` */

DROP TABLE IF EXISTS `me1u8_categories`;

CREATE TABLE `me1u8_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `lft` int(11) NOT NULL DEFAULT '0',
  `rgt` int(11) NOT NULL DEFAULT '0',
  `level` int(10) unsigned NOT NULL DEFAULT '0',
  `path` varchar(255) NOT NULL DEFAULT '',
  `extension` varchar(50) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `note` varchar(255) NOT NULL DEFAULT '',
  `description` mediumtext NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `access` int(10) unsigned NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  `metadesc` varchar(1024) NOT NULL COMMENT 'The meta description for the page.',
  `metakey` varchar(1024) NOT NULL COMMENT 'The meta keywords for the page.',
  `metadata` varchar(2048) NOT NULL COMMENT 'JSON encoded metadata properties.',
  `created_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `created_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `modified_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `language` char(7) NOT NULL,
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `cat_idx` (`extension`,`published`,`access`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_path` (`path`),
  KEY `idx_left_right` (`lft`,`rgt`),
  KEY `idx_alias` (`alias`),
  KEY `idx_language` (`language`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_contact_details` */

DROP TABLE IF EXISTS `me1u8_contact_details`;

CREATE TABLE `me1u8_contact_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `con_position` varchar(255) DEFAULT NULL,
  `address` text,
  `suburb` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `postcode` varchar(100) DEFAULT NULL,
  `telephone` varchar(255) DEFAULT NULL,
  `fax` varchar(255) DEFAULT NULL,
  `misc` mediumtext,
  `image` varchar(255) DEFAULT NULL,
  `email_to` varchar(255) DEFAULT NULL,
  `default_con` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `catid` int(11) NOT NULL DEFAULT '0',
  `access` int(10) unsigned NOT NULL DEFAULT '0',
  `mobile` varchar(255) NOT NULL DEFAULT '',
  `webpage` varchar(255) NOT NULL DEFAULT '',
  `sortname1` varchar(255) NOT NULL,
  `sortname2` varchar(255) NOT NULL,
  `sortname3` varchar(255) NOT NULL,
  `language` char(7) NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) unsigned NOT NULL DEFAULT '0',
  `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
  `metakey` text NOT NULL,
  `metadesc` text NOT NULL,
  `metadata` text NOT NULL,
  `featured` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Set if article is featured.',
  `xreference` varchar(50) NOT NULL COMMENT 'A reference to enable linkages to external data sets.',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_state` (`published`),
  KEY `idx_catid` (`catid`),
  KEY `idx_createdby` (`created_by`),
  KEY `idx_featured_catid` (`featured`,`catid`),
  KEY `idx_language` (`language`),
  KEY `idx_xreference` (`xreference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_content` */

DROP TABLE IF EXISTS `me1u8_content`;

CREATE TABLE `me1u8_content` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `asset_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',
  `title` varchar(255) NOT NULL DEFAULT '',
  `alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `introtext` mediumtext NOT NULL,
  `fulltext` mediumtext NOT NULL,
  `state` tinyint(3) NOT NULL DEFAULT '0',
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) unsigned NOT NULL DEFAULT '0',
  `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `images` text NOT NULL,
  `urls` text NOT NULL,
  `attribs` varchar(5120) NOT NULL,
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `metakey` text NOT NULL,
  `metadesc` text NOT NULL,
  `access` int(10) unsigned NOT NULL DEFAULT '0',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `metadata` text NOT NULL,
  `featured` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Set if article is featured.',
  `language` char(7) NOT NULL COMMENT 'The language code for the article.',
  `xreference` varchar(50) NOT NULL COMMENT 'A reference to enable linkages to external data sets.',
  PRIMARY KEY (`id`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_state` (`state`),
  KEY `idx_catid` (`catid`),
  KEY `idx_createdby` (`created_by`),
  KEY `idx_featured_catid` (`featured`,`catid`),
  KEY `idx_language` (`language`),
  KEY `idx_xreference` (`xreference`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_content_frontpage` */

DROP TABLE IF EXISTS `me1u8_content_frontpage`;

CREATE TABLE `me1u8_content_frontpage` (
  `content_id` int(11) NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`content_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_content_rating` */

DROP TABLE IF EXISTS `me1u8_content_rating`;

CREATE TABLE `me1u8_content_rating` (
  `content_id` int(11) NOT NULL DEFAULT '0',
  `rating_sum` int(10) unsigned NOT NULL DEFAULT '0',
  `rating_count` int(10) unsigned NOT NULL DEFAULT '0',
  `lastip` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`content_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_content_types` */

DROP TABLE IF EXISTS `me1u8_content_types`;

CREATE TABLE `me1u8_content_types` (
  `type_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type_title` varchar(255) NOT NULL DEFAULT '',
  `type_alias` varchar(255) NOT NULL DEFAULT '',
  `table` varchar(255) NOT NULL DEFAULT '',
  `rules` text NOT NULL,
  `field_mappings` text NOT NULL,
  `router` varchar(255) NOT NULL DEFAULT '',
  `content_history_options` varchar(5120) DEFAULT NULL COMMENT 'JSON string for com_contenthistory options',
  PRIMARY KEY (`type_id`),
  KEY `idx_alias` (`type_alias`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_contentitem_tag_map` */

DROP TABLE IF EXISTS `me1u8_contentitem_tag_map`;

CREATE TABLE `me1u8_contentitem_tag_map` (
  `type_alias` varchar(255) NOT NULL DEFAULT '',
  `core_content_id` int(10) unsigned NOT NULL COMMENT 'PK from the core content table',
  `content_item_id` int(11) NOT NULL COMMENT 'PK from the content type table',
  `tag_id` int(10) unsigned NOT NULL COMMENT 'PK from the tag table',
  `tag_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Date of most recent save for this tag-item',
  `type_id` mediumint(8) NOT NULL COMMENT 'PK from the content_type table',
  UNIQUE KEY `uc_ItemnameTagid` (`type_id`,`content_item_id`,`tag_id`),
  KEY `idx_tag_type` (`tag_id`,`type_id`),
  KEY `idx_date_id` (`tag_date`,`tag_id`),
  KEY `idx_tag` (`tag_id`),
  KEY `idx_type` (`type_id`),
  KEY `idx_core_content_id` (`core_content_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Maps items from content tables to tags';

/*Table structure for table `me1u8_core_log_searches` */

DROP TABLE IF EXISTS `me1u8_core_log_searches`;

CREATE TABLE `me1u8_core_log_searches` (
  `search_term` varchar(128) NOT NULL DEFAULT '',
  `hits` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_extensions` */

DROP TABLE IF EXISTS `me1u8_extensions`;

CREATE TABLE `me1u8_extensions` (
  `extension_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `type` varchar(20) NOT NULL,
  `element` varchar(100) NOT NULL,
  `folder` varchar(100) NOT NULL,
  `client_id` tinyint(3) NOT NULL,
  `enabled` tinyint(3) NOT NULL DEFAULT '1',
  `access` int(10) unsigned NOT NULL DEFAULT '1',
  `protected` tinyint(3) NOT NULL DEFAULT '0',
  `manifest_cache` text NOT NULL,
  `params` text NOT NULL,
  `custom_data` text NOT NULL,
  `system_data` text NOT NULL,
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) DEFAULT '0',
  `state` int(11) DEFAULT '0',
  PRIMARY KEY (`extension_id`),
  KEY `element_clientid` (`element`,`client_id`),
  KEY `element_folder_clientid` (`element`,`folder`,`client_id`),
  KEY `extension` (`type`,`element`,`folder`,`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10055 DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_finder_filters` */

DROP TABLE IF EXISTS `me1u8_finder_filters`;

CREATE TABLE `me1u8_finder_filters` (
  `filter_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) unsigned NOT NULL,
  `created_by_alias` varchar(255) NOT NULL,
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `map_count` int(10) unsigned NOT NULL DEFAULT '0',
  `data` text NOT NULL,
  `params` mediumtext,
  PRIMARY KEY (`filter_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_finder_links` */

DROP TABLE IF EXISTS `me1u8_finder_links`;

CREATE TABLE `me1u8_finder_links` (
  `link_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) NOT NULL,
  `route` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `indexdate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `md5sum` varchar(32) DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '1',
  `state` int(5) DEFAULT '1',
  `access` int(5) DEFAULT '0',
  `language` varchar(8) NOT NULL,
  `publish_start_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_end_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `start_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `list_price` double unsigned NOT NULL DEFAULT '0',
  `sale_price` double unsigned NOT NULL DEFAULT '0',
  `type_id` int(11) NOT NULL,
  `object` mediumblob NOT NULL,
  PRIMARY KEY (`link_id`),
  KEY `idx_type` (`type_id`),
  KEY `idx_title` (`title`),
  KEY `idx_md5` (`md5sum`),
  KEY `idx_url` (`url`(75)),
  KEY `idx_published_list` (`published`,`state`,`access`,`publish_start_date`,`publish_end_date`,`list_price`),
  KEY `idx_published_sale` (`published`,`state`,`access`,`publish_start_date`,`publish_end_date`,`sale_price`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_finder_links_terms0` */

DROP TABLE IF EXISTS `me1u8_finder_links_terms0`;

CREATE TABLE `me1u8_finder_links_terms0` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_finder_links_terms1` */

DROP TABLE IF EXISTS `me1u8_finder_links_terms1`;

CREATE TABLE `me1u8_finder_links_terms1` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_finder_links_terms2` */

DROP TABLE IF EXISTS `me1u8_finder_links_terms2`;

CREATE TABLE `me1u8_finder_links_terms2` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_finder_links_terms3` */

DROP TABLE IF EXISTS `me1u8_finder_links_terms3`;

CREATE TABLE `me1u8_finder_links_terms3` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_finder_links_terms4` */

DROP TABLE IF EXISTS `me1u8_finder_links_terms4`;

CREATE TABLE `me1u8_finder_links_terms4` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_finder_links_terms5` */

DROP TABLE IF EXISTS `me1u8_finder_links_terms5`;

CREATE TABLE `me1u8_finder_links_terms5` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_finder_links_terms6` */

DROP TABLE IF EXISTS `me1u8_finder_links_terms6`;

CREATE TABLE `me1u8_finder_links_terms6` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_finder_links_terms7` */

DROP TABLE IF EXISTS `me1u8_finder_links_terms7`;

CREATE TABLE `me1u8_finder_links_terms7` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_finder_links_terms8` */

DROP TABLE IF EXISTS `me1u8_finder_links_terms8`;

CREATE TABLE `me1u8_finder_links_terms8` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_finder_links_terms9` */

DROP TABLE IF EXISTS `me1u8_finder_links_terms9`;

CREATE TABLE `me1u8_finder_links_terms9` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_finder_links_termsa` */

DROP TABLE IF EXISTS `me1u8_finder_links_termsa`;

CREATE TABLE `me1u8_finder_links_termsa` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_finder_links_termsb` */

DROP TABLE IF EXISTS `me1u8_finder_links_termsb`;

CREATE TABLE `me1u8_finder_links_termsb` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_finder_links_termsc` */

DROP TABLE IF EXISTS `me1u8_finder_links_termsc`;

CREATE TABLE `me1u8_finder_links_termsc` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_finder_links_termsd` */

DROP TABLE IF EXISTS `me1u8_finder_links_termsd`;

CREATE TABLE `me1u8_finder_links_termsd` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_finder_links_termse` */

DROP TABLE IF EXISTS `me1u8_finder_links_termse`;

CREATE TABLE `me1u8_finder_links_termse` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_finder_links_termsf` */

DROP TABLE IF EXISTS `me1u8_finder_links_termsf`;

CREATE TABLE `me1u8_finder_links_termsf` (
  `link_id` int(10) unsigned NOT NULL,
  `term_id` int(10) unsigned NOT NULL,
  `weight` float unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`term_id`),
  KEY `idx_term_weight` (`term_id`,`weight`),
  KEY `idx_link_term_weight` (`link_id`,`term_id`,`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_finder_taxonomy` */

DROP TABLE IF EXISTS `me1u8_finder_taxonomy`;

CREATE TABLE `me1u8_finder_taxonomy` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `state` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `access` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `ordering` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `state` (`state`),
  KEY `ordering` (`ordering`),
  KEY `access` (`access`),
  KEY `idx_parent_published` (`parent_id`,`state`,`access`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_finder_taxonomy_map` */

DROP TABLE IF EXISTS `me1u8_finder_taxonomy_map`;

CREATE TABLE `me1u8_finder_taxonomy_map` (
  `link_id` int(10) unsigned NOT NULL,
  `node_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`node_id`),
  KEY `link_id` (`link_id`),
  KEY `node_id` (`node_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_finder_terms` */

DROP TABLE IF EXISTS `me1u8_finder_terms`;

CREATE TABLE `me1u8_finder_terms` (
  `term_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `term` varchar(75) NOT NULL,
  `stem` varchar(75) NOT NULL,
  `common` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `phrase` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `weight` float unsigned NOT NULL DEFAULT '0',
  `soundex` varchar(75) NOT NULL,
  `links` int(10) NOT NULL DEFAULT '0',
  `language` char(3) NOT NULL DEFAULT '',
  PRIMARY KEY (`term_id`),
  UNIQUE KEY `idx_term` (`term`),
  KEY `idx_term_phrase` (`term`,`phrase`),
  KEY `idx_stem_phrase` (`stem`,`phrase`),
  KEY `idx_soundex_phrase` (`soundex`,`phrase`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_finder_terms_common` */

DROP TABLE IF EXISTS `me1u8_finder_terms_common`;

CREATE TABLE `me1u8_finder_terms_common` (
  `term` varchar(75) NOT NULL,
  `language` varchar(3) NOT NULL,
  KEY `idx_word_lang` (`term`,`language`),
  KEY `idx_lang` (`language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_finder_tokens` */

DROP TABLE IF EXISTS `me1u8_finder_tokens`;

CREATE TABLE `me1u8_finder_tokens` (
  `term` varchar(75) NOT NULL,
  `stem` varchar(75) NOT NULL,
  `common` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `phrase` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `weight` float unsigned NOT NULL DEFAULT '1',
  `context` tinyint(1) unsigned NOT NULL DEFAULT '2',
  `language` char(3) NOT NULL DEFAULT '',
  KEY `idx_word` (`term`),
  KEY `idx_context` (`context`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_finder_tokens_aggregate` */

DROP TABLE IF EXISTS `me1u8_finder_tokens_aggregate`;

CREATE TABLE `me1u8_finder_tokens_aggregate` (
  `term_id` int(10) unsigned NOT NULL,
  `map_suffix` char(1) NOT NULL,
  `term` varchar(75) NOT NULL,
  `stem` varchar(75) NOT NULL,
  `common` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `phrase` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `term_weight` float unsigned NOT NULL,
  `context` tinyint(1) unsigned NOT NULL DEFAULT '2',
  `context_weight` float unsigned NOT NULL,
  `total_weight` float unsigned NOT NULL,
  `language` char(3) NOT NULL DEFAULT '',
  KEY `token` (`term`),
  KEY `keyword_id` (`term_id`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_finder_types` */

DROP TABLE IF EXISTS `me1u8_finder_types`;

CREATE TABLE `me1u8_finder_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `mime` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_languages` */

DROP TABLE IF EXISTS `me1u8_languages`;

CREATE TABLE `me1u8_languages` (
  `lang_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `lang_code` char(7) NOT NULL,
  `title` varchar(50) NOT NULL,
  `title_native` varchar(50) NOT NULL,
  `sef` varchar(50) NOT NULL,
  `image` varchar(50) NOT NULL,
  `description` varchar(512) NOT NULL,
  `metakey` text NOT NULL,
  `metadesc` text NOT NULL,
  `sitename` varchar(1024) NOT NULL DEFAULT '',
  `published` int(11) NOT NULL DEFAULT '0',
  `access` int(10) unsigned NOT NULL DEFAULT '0',
  `ordering` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`lang_id`),
  UNIQUE KEY `idx_sef` (`sef`),
  UNIQUE KEY `idx_image` (`image`),
  UNIQUE KEY `idx_langcode` (`lang_code`),
  KEY `idx_access` (`access`),
  KEY `idx_ordering` (`ordering`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_menu` */

DROP TABLE IF EXISTS `me1u8_menu`;

CREATE TABLE `me1u8_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menutype` varchar(24) NOT NULL COMMENT 'The type of menu this item belongs to. FK to #__menu_types.menutype',
  `title` varchar(255) NOT NULL COMMENT 'The display title of the menu item.',
  `alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'The SEF alias of the menu item.',
  `note` varchar(255) NOT NULL DEFAULT '',
  `path` varchar(1024) NOT NULL COMMENT 'The computed path of the menu item based on the alias field.',
  `link` varchar(1024) NOT NULL COMMENT 'The actually link the menu item refers to.',
  `type` varchar(16) NOT NULL COMMENT 'The type of link: Component, URL, Alias, Separator',
  `published` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'The published state of the menu link.',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '1' COMMENT 'The parent menu item in the menu tree.',
  `level` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'The relative level in the tree.',
  `component_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to #__extensions.id',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to #__users.id',
  `checked_out_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'The time the menu item was checked out.',
  `browserNav` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'The click behaviour of the link.',
  `access` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'The access level required to view the menu item.',
  `img` varchar(255) NOT NULL COMMENT 'The image of the menu item.',
  `template_style_id` int(10) unsigned NOT NULL DEFAULT '0',
  `params` text NOT NULL COMMENT 'JSON encoded data for the menu item.',
  `lft` int(11) NOT NULL DEFAULT '0' COMMENT 'Nested set lft.',
  `rgt` int(11) NOT NULL DEFAULT '0' COMMENT 'Nested set rgt.',
  `home` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT 'Indicates if this menu item is the home or default page.',
  `language` char(7) NOT NULL DEFAULT '',
  `client_id` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_client_id_parent_id_alias_language` (`client_id`,`parent_id`,`alias`,`language`),
  KEY `idx_componentid` (`component_id`,`menutype`,`published`,`access`),
  KEY `idx_menutype` (`menutype`),
  KEY `idx_left_right` (`lft`,`rgt`),
  KEY `idx_alias` (`alias`),
  KEY `idx_path` (`path`(255)),
  KEY `idx_language` (`language`)
) ENGINE=InnoDB AUTO_INCREMENT=138 DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_menu_types` */

DROP TABLE IF EXISTS `me1u8_menu_types`;

CREATE TABLE `me1u8_menu_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `menutype` varchar(24) NOT NULL,
  `title` varchar(48) NOT NULL,
  `description` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_menutype` (`menutype`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_messages` */

DROP TABLE IF EXISTS `me1u8_messages`;

CREATE TABLE `me1u8_messages` (
  `message_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id_from` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id_to` int(10) unsigned NOT NULL DEFAULT '0',
  `folder_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `date_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `state` tinyint(1) NOT NULL DEFAULT '0',
  `priority` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `subject` varchar(255) NOT NULL DEFAULT '',
  `message` text NOT NULL,
  PRIMARY KEY (`message_id`),
  KEY `useridto_state` (`user_id_to`,`state`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_messages_cfg` */

DROP TABLE IF EXISTS `me1u8_messages_cfg`;

CREATE TABLE `me1u8_messages_cfg` (
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cfg_name` varchar(100) NOT NULL DEFAULT '',
  `cfg_value` varchar(255) NOT NULL DEFAULT '',
  UNIQUE KEY `idx_user_var_name` (`user_id`,`cfg_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_modules` */

DROP TABLE IF EXISTS `me1u8_modules`;

CREATE TABLE `me1u8_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `asset_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.',
  `title` varchar(100) NOT NULL DEFAULT '',
  `note` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  `position` varchar(50) NOT NULL DEFAULT '',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `module` varchar(50) DEFAULT NULL,
  `access` int(10) unsigned NOT NULL DEFAULT '0',
  `showtitle` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `params` text NOT NULL,
  `client_id` tinyint(4) NOT NULL DEFAULT '0',
  `language` char(7) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `published` (`published`,`access`),
  KEY `newsfeeds` (`module`,`published`),
  KEY `idx_language` (`language`)
) ENGINE=InnoDB AUTO_INCREMENT=123 DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_modules_menu` */

DROP TABLE IF EXISTS `me1u8_modules_menu`;

CREATE TABLE `me1u8_modules_menu` (
  `moduleid` int(11) NOT NULL DEFAULT '0',
  `menuid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`moduleid`,`menuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_newsfeeds` */

DROP TABLE IF EXISTS `me1u8_newsfeeds`;

CREATE TABLE `me1u8_newsfeeds` (
  `catid` int(11) NOT NULL DEFAULT '0',
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `link` varchar(200) NOT NULL DEFAULT '',
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `numarticles` int(10) unsigned NOT NULL DEFAULT '1',
  `cache_time` int(10) unsigned NOT NULL DEFAULT '3600',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `rtl` tinyint(4) NOT NULL DEFAULT '0',
  `access` int(10) unsigned NOT NULL DEFAULT '0',
  `language` char(7) NOT NULL DEFAULT '',
  `params` text NOT NULL,
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) unsigned NOT NULL DEFAULT '0',
  `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` int(10) unsigned NOT NULL DEFAULT '0',
  `metakey` text NOT NULL,
  `metadesc` text NOT NULL,
  `metadata` text NOT NULL,
  `xreference` varchar(50) NOT NULL COMMENT 'A reference to enable linkages to external data sets.',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `description` text NOT NULL,
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `images` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_state` (`published`),
  KEY `idx_catid` (`catid`),
  KEY `idx_createdby` (`created_by`),
  KEY `idx_language` (`language`),
  KEY `idx_xreference` (`xreference`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_overrider` */

DROP TABLE IF EXISTS `me1u8_overrider`;

CREATE TABLE `me1u8_overrider` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `constant` varchar(255) NOT NULL,
  `string` text NOT NULL,
  `file` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_postinstall_messages` */

DROP TABLE IF EXISTS `me1u8_postinstall_messages`;

CREATE TABLE `me1u8_postinstall_messages` (
  `postinstall_message_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `extension_id` bigint(20) NOT NULL DEFAULT '700' COMMENT 'FK to #__extensions',
  `title_key` varchar(255) NOT NULL DEFAULT '' COMMENT 'Lang key for the title',
  `description_key` varchar(255) NOT NULL DEFAULT '' COMMENT 'Lang key for description',
  `action_key` varchar(255) NOT NULL DEFAULT '',
  `language_extension` varchar(255) NOT NULL DEFAULT 'com_postinstall' COMMENT 'Extension holding lang keys',
  `language_client_id` tinyint(3) NOT NULL DEFAULT '1',
  `type` varchar(10) NOT NULL DEFAULT 'link' COMMENT 'Message type - message, link, action',
  `action_file` varchar(255) DEFAULT '' COMMENT 'RAD URI to the PHP file containing action method',
  `action` varchar(255) DEFAULT '' COMMENT 'Action method name or URL',
  `condition_file` varchar(255) DEFAULT NULL COMMENT 'RAD URI to file holding display condition method',
  `condition_method` varchar(255) DEFAULT NULL COMMENT 'Display condition method, must return boolean',
  `version_introduced` varchar(50) NOT NULL DEFAULT '3.2.0' COMMENT 'Version when this message was introduced',
  `enabled` tinyint(3) NOT NULL DEFAULT '1',
  PRIMARY KEY (`postinstall_message_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_redirect_links` */

DROP TABLE IF EXISTS `me1u8_redirect_links`;

CREATE TABLE `me1u8_redirect_links` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `old_url` varchar(255) NOT NULL,
  `new_url` varchar(255) DEFAULT NULL,
  `referer` varchar(150) NOT NULL,
  `comment` varchar(255) NOT NULL,
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `published` tinyint(4) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `header` smallint(3) NOT NULL DEFAULT '301',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_link_old` (`old_url`),
  KEY `idx_link_modifed` (`modified_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_schemas` */

DROP TABLE IF EXISTS `me1u8_schemas`;

CREATE TABLE `me1u8_schemas` (
  `extension_id` int(11) NOT NULL,
  `version_id` varchar(20) NOT NULL,
  PRIMARY KEY (`extension_id`,`version_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_session` */

DROP TABLE IF EXISTS `me1u8_session`;

CREATE TABLE `me1u8_session` (
  `session_id` varchar(200) NOT NULL DEFAULT '',
  `client_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `guest` tinyint(4) unsigned DEFAULT '1',
  `time` varchar(14) DEFAULT '',
  `data` mediumtext,
  `userid` int(11) DEFAULT '0',
  `username` varchar(150) DEFAULT '',
  PRIMARY KEY (`session_id`),
  KEY `userid` (`userid`),
  KEY `time` (`time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_tags` */

DROP TABLE IF EXISTS `me1u8_tags`;

CREATE TABLE `me1u8_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `lft` int(11) NOT NULL DEFAULT '0',
  `rgt` int(11) NOT NULL DEFAULT '0',
  `level` int(10) unsigned NOT NULL DEFAULT '0',
  `path` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `note` varchar(255) NOT NULL DEFAULT '',
  `description` mediumtext NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `checked_out` int(11) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `access` int(10) unsigned NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  `metadesc` varchar(1024) NOT NULL COMMENT 'The meta description for the page.',
  `metakey` varchar(1024) NOT NULL COMMENT 'The meta keywords for the page.',
  `metadata` varchar(2048) NOT NULL COMMENT 'JSON encoded metadata properties.',
  `created_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `created_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by_alias` varchar(255) NOT NULL DEFAULT '',
  `modified_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `modified_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `images` text NOT NULL,
  `urls` text NOT NULL,
  `hits` int(10) unsigned NOT NULL DEFAULT '0',
  `language` char(7) NOT NULL,
  `version` int(10) unsigned NOT NULL DEFAULT '1',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `tag_idx` (`published`,`access`),
  KEY `idx_access` (`access`),
  KEY `idx_checkout` (`checked_out`),
  KEY `idx_path` (`path`),
  KEY `idx_left_right` (`lft`,`rgt`),
  KEY `idx_alias` (`alias`),
  KEY `idx_language` (`language`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_template_styles` */

DROP TABLE IF EXISTS `me1u8_template_styles`;

CREATE TABLE `me1u8_template_styles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `template` varchar(50) NOT NULL DEFAULT '',
  `client_id` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `home` char(7) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `params` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_template` (`template`),
  KEY `idx_home` (`home`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_tour` */

DROP TABLE IF EXISTS `me1u8_tour`;

CREATE TABLE `me1u8_tour` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ordering` int(11) NOT NULL,
  `state` tinyint(1) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_ucm_base` */

DROP TABLE IF EXISTS `me1u8_ucm_base`;

CREATE TABLE `me1u8_ucm_base` (
  `ucm_id` int(10) unsigned NOT NULL,
  `ucm_item_id` int(10) NOT NULL,
  `ucm_type_id` int(11) NOT NULL,
  `ucm_language_id` int(11) NOT NULL,
  PRIMARY KEY (`ucm_id`),
  KEY `idx_ucm_item_id` (`ucm_item_id`),
  KEY `idx_ucm_type_id` (`ucm_type_id`),
  KEY `idx_ucm_language_id` (`ucm_language_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_ucm_content` */

DROP TABLE IF EXISTS `me1u8_ucm_content`;

CREATE TABLE `me1u8_ucm_content` (
  `core_content_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `core_type_alias` varchar(255) NOT NULL DEFAULT '' COMMENT 'FK to the content types table',
  `core_title` varchar(255) NOT NULL,
  `core_alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `core_body` mediumtext NOT NULL,
  `core_state` tinyint(1) NOT NULL DEFAULT '0',
  `core_checked_out_time` varchar(255) NOT NULL DEFAULT '',
  `core_checked_out_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `core_access` int(10) unsigned NOT NULL DEFAULT '0',
  `core_params` text NOT NULL,
  `core_featured` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `core_metadata` varchar(2048) NOT NULL COMMENT 'JSON encoded metadata properties.',
  `core_created_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `core_created_by_alias` varchar(255) NOT NULL DEFAULT '',
  `core_created_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `core_modified_user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Most recent user that modified',
  `core_modified_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `core_language` char(7) NOT NULL,
  `core_publish_up` datetime NOT NULL,
  `core_publish_down` datetime NOT NULL,
  `core_content_item_id` int(10) unsigned DEFAULT NULL COMMENT 'ID from the individual type table',
  `asset_id` int(10) unsigned DEFAULT NULL COMMENT 'FK to the #__assets table.',
  `core_images` text NOT NULL,
  `core_urls` text NOT NULL,
  `core_hits` int(10) unsigned NOT NULL DEFAULT '0',
  `core_version` int(10) unsigned NOT NULL DEFAULT '1',
  `core_ordering` int(11) NOT NULL DEFAULT '0',
  `core_metakey` text NOT NULL,
  `core_metadesc` text NOT NULL,
  `core_catid` int(10) unsigned NOT NULL DEFAULT '0',
  `core_xreference` varchar(50) NOT NULL COMMENT 'A reference to enable linkages to external data sets.',
  `core_type_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`core_content_id`),
  KEY `tag_idx` (`core_state`,`core_access`),
  KEY `idx_access` (`core_access`),
  KEY `idx_alias` (`core_alias`),
  KEY `idx_language` (`core_language`),
  KEY `idx_title` (`core_title`),
  KEY `idx_modified_time` (`core_modified_time`),
  KEY `idx_created_time` (`core_created_time`),
  KEY `idx_content_type` (`core_type_alias`),
  KEY `idx_core_modified_user_id` (`core_modified_user_id`),
  KEY `idx_core_checked_out_user_id` (`core_checked_out_user_id`),
  KEY `idx_core_created_user_id` (`core_created_user_id`),
  KEY `idx_core_type_id` (`core_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='Contains core content data in name spaced fields';

/*Table structure for table `me1u8_ucm_history` */

DROP TABLE IF EXISTS `me1u8_ucm_history`;

CREATE TABLE `me1u8_ucm_history` (
  `version_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ucm_item_id` int(10) unsigned NOT NULL,
  `ucm_type_id` int(10) unsigned NOT NULL,
  `version_note` varchar(255) NOT NULL DEFAULT '' COMMENT 'Optional version name',
  `save_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `editor_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `character_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Number of characters in this version.',
  `sha1_hash` varchar(50) NOT NULL DEFAULT '' COMMENT 'SHA1 hash of the version_data column.',
  `version_data` mediumtext NOT NULL COMMENT 'json-encoded string of version data',
  `keep_forever` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=auto delete; 1=keep',
  PRIMARY KEY (`version_id`),
  KEY `idx_ucm_item_id` (`ucm_type_id`,`ucm_item_id`),
  KEY `idx_save_date` (`save_date`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_update_sites` */

DROP TABLE IF EXISTS `me1u8_update_sites`;

CREATE TABLE `me1u8_update_sites` (
  `update_site_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT '',
  `type` varchar(20) DEFAULT '',
  `location` text NOT NULL,
  `enabled` int(11) DEFAULT '0',
  `last_check_timestamp` bigint(20) DEFAULT '0',
  `extra_query` varchar(1000) DEFAULT '',
  PRIMARY KEY (`update_site_id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8 COMMENT='Update Sites';

/*Table structure for table `me1u8_update_sites_extensions` */

DROP TABLE IF EXISTS `me1u8_update_sites_extensions`;

CREATE TABLE `me1u8_update_sites_extensions` (
  `update_site_id` int(11) NOT NULL DEFAULT '0',
  `extension_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`update_site_id`,`extension_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Links extensions to update sites';

/*Table structure for table `me1u8_updates` */

DROP TABLE IF EXISTS `me1u8_updates`;

CREATE TABLE `me1u8_updates` (
  `update_id` int(11) NOT NULL AUTO_INCREMENT,
  `update_site_id` int(11) DEFAULT '0',
  `extension_id` int(11) DEFAULT '0',
  `name` varchar(100) DEFAULT '',
  `description` text NOT NULL,
  `element` varchar(100) DEFAULT '',
  `type` varchar(20) DEFAULT '',
  `folder` varchar(20) DEFAULT '',
  `client_id` tinyint(3) DEFAULT '0',
  `version` varchar(32) DEFAULT '',
  `data` text NOT NULL,
  `detailsurl` text NOT NULL,
  `infourl` text NOT NULL,
  `extra_query` varchar(1000) DEFAULT '',
  PRIMARY KEY (`update_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='Available Updates';

/*Table structure for table `me1u8_user_keys` */

DROP TABLE IF EXISTS `me1u8_user_keys`;

CREATE TABLE `me1u8_user_keys` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `series` varchar(255) NOT NULL,
  `invalid` tinyint(4) NOT NULL,
  `time` varchar(200) NOT NULL,
  `uastring` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `series` (`series`),
  UNIQUE KEY `series_2` (`series`),
  UNIQUE KEY `series_3` (`series`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_user_notes` */

DROP TABLE IF EXISTS `me1u8_user_notes`;

CREATE TABLE `me1u8_user_notes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `catid` int(10) unsigned NOT NULL DEFAULT '0',
  `subject` varchar(100) NOT NULL DEFAULT '',
  `body` text NOT NULL,
  `state` tinyint(3) NOT NULL DEFAULT '0',
  `checked_out` int(10) unsigned NOT NULL DEFAULT '0',
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `created_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_user_id` int(10) unsigned NOT NULL,
  `modified_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `review_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_up` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_category_id` (`catid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_user_profiles` */

DROP TABLE IF EXISTS `me1u8_user_profiles`;

CREATE TABLE `me1u8_user_profiles` (
  `user_id` int(11) NOT NULL,
  `profile_key` varchar(100) NOT NULL,
  `profile_value` text NOT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  UNIQUE KEY `idx_user_id_profile_key` (`user_id`,`profile_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Simple user profile storage table';

/*Table structure for table `me1u8_user_usergroup_map` */

DROP TABLE IF EXISTS `me1u8_user_usergroup_map`;

CREATE TABLE `me1u8_user_usergroup_map` (
  `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Foreign Key to #__users.id',
  `group_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Foreign Key to #__usergroups.id',
  PRIMARY KEY (`user_id`,`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_usergroups` */

DROP TABLE IF EXISTS `me1u8_usergroups`;

CREATE TABLE `me1u8_usergroups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Adjacency List Reference Id',
  `lft` int(11) NOT NULL DEFAULT '0' COMMENT 'Nested set lft.',
  `rgt` int(11) NOT NULL DEFAULT '0' COMMENT 'Nested set rgt.',
  `title` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_usergroup_parent_title_lookup` (`parent_id`,`title`),
  KEY `idx_usergroup_title_lookup` (`title`),
  KEY `idx_usergroup_adjacency_lookup` (`parent_id`),
  KEY `idx_usergroup_nested_set_lookup` (`lft`,`rgt`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_users` */

DROP TABLE IF EXISTS `me1u8_users`;

CREATE TABLE `me1u8_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `username` varchar(150) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `password` varchar(100) NOT NULL DEFAULT '',
  `block` tinyint(4) NOT NULL DEFAULT '0',
  `sendEmail` tinyint(4) DEFAULT '0',
  `registerDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lastvisitDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `activation` varchar(100) NOT NULL DEFAULT '',
  `params` text NOT NULL,
  `lastResetTime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Date of last password reset',
  `resetCount` int(11) NOT NULL DEFAULT '0' COMMENT 'Count of password resets since lastResetTime',
  `otpKey` varchar(1000) NOT NULL DEFAULT '' COMMENT 'Two factor authentication encrypted keys',
  `otep` varchar(1000) NOT NULL DEFAULT '' COMMENT 'One time emergency passwords',
  `requireReset` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Require user to reset password on next login',
  PRIMARY KEY (`id`),
  KEY `idx_name` (`name`),
  KEY `idx_block` (`block`),
  KEY `username` (`username`),
  KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=504 DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_viewlevels` */

DROP TABLE IF EXISTS `me1u8_viewlevels`;

CREATE TABLE `me1u8_viewlevels` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `title` varchar(100) NOT NULL DEFAULT '',
  `ordering` int(11) NOT NULL DEFAULT '0',
  `rules` varchar(5120) NOT NULL COMMENT 'JSON encoded access control.',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_assetgroup_title_lookup` (`title`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_virtuemart_accommodation` */

DROP TABLE IF EXISTS `me1u8_virtuemart_accommodation`;

CREATE TABLE `me1u8_virtuemart_accommodation` (
  `virtuemart_accommodation_id` int(11) DEFAULT NULL,
  `title` int(11) DEFAULT NULL,
  `icon` int(11) DEFAULT NULL,
  `meta_title` int(11) DEFAULT NULL,
  `key_word` int(11) DEFAULT NULL,
  `description` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `shared` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  KEY `ordering` (`ordering`),
  KEY `published` (`published`),
  KEY `shared` (`shared`),
  KEY `virtuemart_accommodation_id` (`virtuemart_accommodation_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='Used to store currencies';

/*Table structure for table `me1u8_virtuemart_activity` */

DROP TABLE IF EXISTS `me1u8_virtuemart_activity`;

CREATE TABLE `me1u8_virtuemart_activity` (
  `virtuemart_activity_id` int(11) DEFAULT NULL,
  `title` int(11) DEFAULT NULL,
  `icon` int(11) DEFAULT NULL,
  `meta_title` int(11) DEFAULT NULL,
  `key_word` int(11) DEFAULT NULL,
  `description` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `shared` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  KEY `ordering` (`ordering`),
  KEY `published` (`published`),
  KEY `shared` (`shared`),
  KEY `virtuemart_activity_id` (`virtuemart_activity_id`)
) ENGINE=MyISAM AUTO_INCREMENT=204 DEFAULT CHARSET=utf8 COMMENT='Used to store currencies';

/*Table structure for table `me1u8_virtuemart_adminmenuentries` */

DROP TABLE IF EXISTS `me1u8_virtuemart_adminmenuentries`;

CREATE TABLE `me1u8_virtuemart_adminmenuentries` (
  `id` int(11) DEFAULT NULL,
  `module_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `name` int(11) DEFAULT NULL,
  `link` int(11) DEFAULT NULL,
  `depends` int(11) DEFAULT NULL,
  `icon_class` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `tooltip` int(11) DEFAULT NULL,
  `view` int(11) DEFAULT NULL,
  `task` int(11) DEFAULT NULL,
  KEY `module_id` (`module_id`),
  KEY `published` (`published`),
  KEY `ordering` (`ordering`),
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COMMENT='Administration Menu Items';

/*Table structure for table `me1u8_virtuemart_airport` */

DROP TABLE IF EXISTS `me1u8_virtuemart_airport`;

CREATE TABLE `me1u8_virtuemart_airport` (
  `virtuemart_airport_id` int(11) DEFAULT NULL,
  `virtuemart_vendor_id` int(11) DEFAULT NULL,
  `virtuemart_cityarea_id` int(11) DEFAULT NULL,
  `virtuemart_worldzone_id` int(11) DEFAULT NULL,
  `airport_name` int(11) DEFAULT NULL,
  `ata_code` char(20) DEFAULT NULL,
  `icao_code` char(20) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `shared` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  UNIQUE KEY `state_3_code` (`virtuemart_vendor_id`,`virtuemart_cityarea_id`,`ata_code`),
  UNIQUE KEY `state_2_code` (`virtuemart_vendor_id`,`virtuemart_cityarea_id`,`icao_code`),
  KEY `virtuemart_vendor_id` (`virtuemart_vendor_id`),
  KEY `virtuemart_country_id` (`virtuemart_cityarea_id`),
  KEY `ordering` (`ordering`),
  KEY `shared` (`shared`),
  KEY `published` (`published`),
  KEY `virtuemart_airport_id` (`virtuemart_airport_id`)
) ENGINE=MyISAM AUTO_INCREMENT=833 DEFAULT CHARSET=utf8 COMMENT='States that are assigned to a country';

/*Table structure for table `me1u8_virtuemart_amout_percent` */

DROP TABLE IF EXISTS `me1u8_virtuemart_amout_percent`;

CREATE TABLE `me1u8_virtuemart_amout_percent` (
  `virtuemart_amout_percent_id` int(11) DEFAULT NULL,
  `title` int(11) DEFAULT NULL,
  `icon` int(11) DEFAULT NULL,
  `meta_title` int(11) DEFAULT NULL,
  `key_word` int(11) DEFAULT NULL,
  `description` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `shared` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  KEY `ordering` (`ordering`),
  KEY `published` (`published`),
  KEY `shared` (`shared`),
  KEY `virtuemart_amout_percent_id` (`virtuemart_amout_percent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=210 DEFAULT CHARSET=utf8 COMMENT='Used to store currencies';

/*Table structure for table `me1u8_virtuemart_calc_categories` */

DROP TABLE IF EXISTS `me1u8_virtuemart_calc_categories`;

CREATE TABLE `me1u8_virtuemart_calc_categories` (
  `id` int(11) DEFAULT NULL,
  `virtuemart_calc_id` int(11) DEFAULT NULL,
  `virtuemart_category_id` int(11) DEFAULT NULL,
  UNIQUE KEY `virtuemart_calc_id` (`virtuemart_calc_id`,`virtuemart_category_id`),
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_virtuemart_calc_countries` */

DROP TABLE IF EXISTS `me1u8_virtuemart_calc_countries`;

CREATE TABLE `me1u8_virtuemart_calc_countries` (
  `id` int(11) DEFAULT NULL,
  `virtuemart_calc_id` int(11) DEFAULT NULL,
  `virtuemart_country_id` int(11) DEFAULT NULL,
  UNIQUE KEY `virtuemart_calc_id` (`virtuemart_calc_id`,`virtuemart_country_id`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_virtuemart_calc_manufacturers` */

DROP TABLE IF EXISTS `me1u8_virtuemart_calc_manufacturers`;

CREATE TABLE `me1u8_virtuemart_calc_manufacturers` (
  `id` int(11) DEFAULT NULL,
  `virtuemart_calc_id` int(11) DEFAULT NULL,
  `virtuemart_manufacturer_id` int(11) DEFAULT NULL,
  UNIQUE KEY `virtuemart_calc_id` (`virtuemart_calc_id`,`virtuemart_manufacturer_id`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_virtuemart_calc_shoppergroups` */

DROP TABLE IF EXISTS `me1u8_virtuemart_calc_shoppergroups`;

CREATE TABLE `me1u8_virtuemart_calc_shoppergroups` (
  `id` int(11) DEFAULT NULL,
  `virtuemart_calc_id` int(11) DEFAULT NULL,
  `virtuemart_shoppergroup_id` int(11) DEFAULT NULL,
  UNIQUE KEY `virtuemart_calc_id` (`virtuemart_calc_id`,`virtuemart_shoppergroup_id`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_virtuemart_calc_states` */

DROP TABLE IF EXISTS `me1u8_virtuemart_calc_states`;

CREATE TABLE `me1u8_virtuemart_calc_states` (
  `id` int(11) DEFAULT NULL,
  `virtuemart_calc_id` int(11) DEFAULT NULL,
  `virtuemart_state_id` int(11) DEFAULT NULL,
  UNIQUE KEY `virtuemart_calc_id` (`virtuemart_calc_id`,`virtuemart_state_id`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_virtuemart_calcs` */

DROP TABLE IF EXISTS `me1u8_virtuemart_calcs`;

CREATE TABLE `me1u8_virtuemart_calcs` (
  `virtuemart_calc_id` int(11) DEFAULT NULL,
  `virtuemart_vendor_id` int(11) DEFAULT NULL,
  `calc_jplugin_id` int(11) DEFAULT NULL,
  `calc_name` int(11) DEFAULT NULL,
  `calc_descr` int(11) DEFAULT NULL,
  `calc_kind` int(11) DEFAULT NULL,
  `calc_value_mathop` int(11) DEFAULT NULL,
  `calc_value` int(11) DEFAULT NULL,
  `calc_currency` int(11) DEFAULT NULL,
  `calc_shopper_published` int(11) DEFAULT NULL,
  `calc_vendor_published` int(11) DEFAULT NULL,
  `publish_up` int(11) DEFAULT NULL,
  `publish_down` int(11) DEFAULT NULL,
  `for_override` int(11) DEFAULT NULL,
  `calc_params` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `shared` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  KEY `virtuemart_vendor_id` (`virtuemart_vendor_id`),
  KEY `published` (`published`),
  KEY `calc_kind` (`calc_kind`),
  KEY `shared` (`shared`),
  KEY `publish_up` (`publish_up`),
  KEY `publish_down` (`publish_down`),
  KEY `virtuemart_calc_id` (`virtuemart_calc_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_virtuemart_carts` */

DROP TABLE IF EXISTS `me1u8_virtuemart_carts`;

CREATE TABLE `me1u8_virtuemart_carts` (
  `virtuemart_cart_id` int(11) DEFAULT NULL,
  `virtuemart_user_id` int(11) DEFAULT NULL,
  `virtuemart_vendor_id` int(11) DEFAULT NULL,
  `cartdata` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  KEY `virtuemart_vendor_id` (`virtuemart_vendor_id`),
  KEY `virtuemart_user_id` (`virtuemart_user_id`),
  KEY `virtuemart_cart_id` (`virtuemart_cart_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Used to store the cart';

/*Table structure for table `me1u8_virtuemart_categories` */

DROP TABLE IF EXISTS `me1u8_virtuemart_categories`;

CREATE TABLE `me1u8_virtuemart_categories` (
  `virtuemart_category_id` int(11) DEFAULT NULL,
  `virtuemart_vendor_id` int(11) DEFAULT NULL,
  `category_template` int(11) DEFAULT NULL,
  `category_layout` int(11) DEFAULT NULL,
  `category_product_layout` int(11) DEFAULT NULL,
  `products_per_row` int(11) DEFAULT NULL,
  `limit_list_step` int(11) DEFAULT NULL,
  `limit_list_initial` int(11) DEFAULT NULL,
  `hits` int(11) DEFAULT NULL,
  `metarobot` int(11) DEFAULT NULL,
  `metaauthor` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `shared` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  KEY `virtuemart_vendor_id` (`virtuemart_vendor_id`),
  KEY `published` (`published`),
  KEY `shared` (`shared`),
  KEY `ordering` (`ordering`),
  KEY `virtuemart_category_id` (`virtuemart_category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='Product Categories are stored here';

/*Table structure for table `me1u8_virtuemart_categories_en_gb` */

DROP TABLE IF EXISTS `me1u8_virtuemart_categories_en_gb`;

CREATE TABLE `me1u8_virtuemart_categories_en_gb` (
  `virtuemart_category_id` int(11) DEFAULT NULL,
  `category_name` int(11) DEFAULT NULL,
  `category_description` int(11) DEFAULT NULL,
  `metadesc` int(11) DEFAULT NULL,
  `metakey` int(11) DEFAULT NULL,
  `customtitle` int(11) DEFAULT NULL,
  `slug` char(192) NOT NULL DEFAULT '',
  UNIQUE KEY `slug` (`slug`),
  KEY `virtuemart_category_id` (`virtuemart_category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_virtuemart_category_categories` */

DROP TABLE IF EXISTS `me1u8_virtuemart_category_categories`;

CREATE TABLE `me1u8_virtuemart_category_categories` (
  `id` int(11) DEFAULT NULL,
  `category_parent_id` int(11) DEFAULT NULL,
  `category_child_id` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  UNIQUE KEY `category_parent_id` (`category_parent_id`,`category_child_id`),
  KEY `category_child_id` (`category_child_id`),
  KEY `ordering` (`ordering`),
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='Category child-parent relation list';

/*Table structure for table `me1u8_virtuemart_category_medias` */

DROP TABLE IF EXISTS `me1u8_virtuemart_category_medias`;

CREATE TABLE `me1u8_virtuemart_category_medias` (
  `id` int(11) DEFAULT NULL,
  `virtuemart_category_id` int(11) DEFAULT NULL,
  `virtuemart_media_id` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  UNIQUE KEY `virtuemart_category_id` (`virtuemart_category_id`,`virtuemart_media_id`),
  KEY `ordering` (`ordering`),
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_virtuemart_cityarea` */

DROP TABLE IF EXISTS `me1u8_virtuemart_cityarea`;

CREATE TABLE `me1u8_virtuemart_cityarea` (
  `virtuemart_cityarea_id` int(11) NOT NULL AUTO_INCREMENT,
  `city_area_name` varchar(50) DEFAULT NULL,
  `virtuemart_state_id` int(11) DEFAULT NULL,
  `airport_code` int(11) DEFAULT NULL,
  `phone_code` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `shared` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`virtuemart_cityarea_id`),
  KEY `published` (`published`),
  KEY `shared` (`shared`),
  KEY `virtuemart_cityarea_id_4` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_5` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_6` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_7` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_8` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_9` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_10` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_11` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_12` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_13` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_14` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_15` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_16` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_17` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_18` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_19` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_20` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_21` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_22` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_24` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_25` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_26` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_27` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_28` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_29` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_30` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_31` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_32` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_33` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_34` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_35` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_36` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_38` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_39` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_40` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_41` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_42` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_43` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_44` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_45` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_46` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_47` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_48` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_49` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_50` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_51` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_52` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_53` (`virtuemart_cityarea_id`),
  KEY `virtuemart_cityarea_id_54` (`virtuemart_cityarea_id`),
  KEY `virtuemart_state_id` (`virtuemart_state_id`),
  CONSTRAINT `me1u8_virtuemart_cityarea_ibfk_1` FOREIGN KEY (`virtuemart_state_id`) REFERENCES `me1u8_virtuemart_states` (`virtuemart_state_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='Used to store currencies';

/*Table structure for table `me1u8_virtuemart_configs` */

DROP TABLE IF EXISTS `me1u8_virtuemart_configs`;

CREATE TABLE `me1u8_virtuemart_configs` (
  `virtuemart_config_id` int(11) DEFAULT NULL,
  `config` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  KEY `virtuemart_config_id` (`virtuemart_config_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='Holds configuration settings';

/*Table structure for table `me1u8_virtuemart_countries` */

DROP TABLE IF EXISTS `me1u8_virtuemart_countries`;

CREATE TABLE `me1u8_virtuemart_countries` (
  `virtuemart_country_id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) DEFAULT NULL,
  `phone_code` int(11) DEFAULT NULL,
  `state_number` int(11) DEFAULT NULL,
  `virtuemart_worldzone_id` int(11) DEFAULT NULL,
  `country_name` varchar(50) DEFAULT NULL,
  `iso2` int(11) DEFAULT NULL,
  `iso3` int(11) DEFAULT NULL,
  `flag` int(11) DEFAULT NULL,
  `country_3_code` int(11) DEFAULT NULL,
  `image` int(11) DEFAULT NULL,
  `country_2_code` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`virtuemart_country_id`),
  KEY `country_3_code` (`country_3_code`),
  KEY `country_2_code` (`country_2_code`),
  KEY `country_name` (`country_name`),
  KEY `ordering` (`ordering`),
  KEY `published` (`published`),
  KEY `virtuemart_country_id` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_2` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_3` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_4` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_5` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_6` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_7` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_8` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_9` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_10` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_11` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_12` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_13` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_14` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_15` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_16` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_17` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_18` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_19` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_20` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_21` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_22` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_23` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_24` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_25` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_26` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_27` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_28` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_29` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_30` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_31` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_32` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_33` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_34` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_35` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_36` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_37` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_38` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_39` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_40` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_41` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_42` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_43` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_44` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_45` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_46` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_47` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_48` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_49` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_50` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_51` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_52` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_53` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_54` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_55` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_56` (`virtuemart_country_id`),
  KEY `virtuemart_country_id_57` (`virtuemart_country_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='Country records';

/*Table structure for table `me1u8_virtuemart_coupons` */

DROP TABLE IF EXISTS `me1u8_virtuemart_coupons`;

CREATE TABLE `me1u8_virtuemart_coupons` (
  `virtuemart_coupon_id` int(11) DEFAULT NULL,
  `virtuemart_vendor_id` int(11) DEFAULT NULL,
  `coupon_code` int(11) DEFAULT NULL,
  `percent_or_total` int(11) DEFAULT NULL,
  `coupon_type` int(11) DEFAULT NULL,
  `coupon_value` int(11) DEFAULT NULL,
  `coupon_start_date` int(11) DEFAULT NULL,
  `coupon_expiry_date` int(11) DEFAULT NULL,
  `coupon_value_valid` int(11) DEFAULT NULL,
  `coupon_used` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  KEY `virtuemart_vendor_id` (`virtuemart_vendor_id`),
  KEY `coupon_code` (`coupon_code`),
  KEY `coupon_type` (`coupon_type`),
  KEY `published` (`published`),
  KEY `virtuemart_coupon_id` (`virtuemart_coupon_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='Used to store coupon codes';

/*Table structure for table `me1u8_virtuemart_currencies` */

DROP TABLE IF EXISTS `me1u8_virtuemart_currencies`;

CREATE TABLE `me1u8_virtuemart_currencies` (
  `virtuemart_currency_id` int(11) NOT NULL DEFAULT '0',
  `virtuemart_vendor_id` int(11) DEFAULT NULL,
  `currency_name` int(11) DEFAULT NULL,
  `currency_code_2` int(11) DEFAULT NULL,
  `currency_code` int(11) DEFAULT NULL,
  `currency_code_3` int(11) DEFAULT NULL,
  `iso2` int(11) DEFAULT NULL,
  `iso3` char(3) DEFAULT NULL,
  `sign` int(11) DEFAULT NULL,
  `virtuemart_country_id` int(11) DEFAULT NULL,
  `currency_numeric_code` int(11) DEFAULT NULL,
  `currency_exchange_rate` int(11) DEFAULT NULL,
  `currency_symbol` int(11) DEFAULT NULL,
  `currency_decimal_place` int(11) DEFAULT NULL,
  `currency_decimal_symbol` int(11) DEFAULT NULL,
  `currency_thousands` int(11) DEFAULT NULL,
  `currency_positive_style` int(11) DEFAULT NULL,
  `currency_negative_style` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `shared` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`virtuemart_currency_id`),
  UNIQUE KEY `currency_code_3` (`iso3`),
  KEY `ordering` (`ordering`),
  KEY `currency_name` (`currency_name`),
  KEY `published` (`published`),
  KEY `shared` (`shared`),
  KEY `virtuemart_vendor_id` (`virtuemart_vendor_id`),
  KEY `currency_numeric_code` (`currency_numeric_code`),
  KEY `virtuemart_currency_id` (`virtuemart_currency_id`),
  KEY `virtuemart_currency_id_2` (`virtuemart_currency_id`),
  KEY `virtuemart_currency_id_3` (`virtuemart_currency_id`),
  KEY `virtuemart_currency_id_4` (`virtuemart_currency_id`),
  KEY `virtuemart_currency_id_5` (`virtuemart_currency_id`),
  KEY `virtuemart_currency_id_6` (`virtuemart_currency_id`),
  KEY `virtuemart_currency_id_7` (`virtuemart_currency_id`),
  KEY `virtuemart_currency_id_8` (`virtuemart_currency_id`),
  KEY `virtuemart_currency_id_9` (`virtuemart_currency_id`),
  KEY `virtuemart_currency_id_10` (`virtuemart_currency_id`),
  KEY `virtuemart_currency_id_11` (`virtuemart_currency_id`),
  KEY `virtuemart_currency_id_12` (`virtuemart_currency_id`),
  KEY `virtuemart_currency_id_13` (`virtuemart_currency_id`),
  KEY `virtuemart_currency_id_14` (`virtuemart_currency_id`),
  KEY `virtuemart_currency_id_15` (`virtuemart_currency_id`),
  KEY `virtuemart_currency_id_16` (`virtuemart_currency_id`),
  KEY `virtuemart_currency_id_17` (`virtuemart_currency_id`),
  KEY `virtuemart_currency_id_18` (`virtuemart_currency_id`),
  KEY `virtuemart_currency_id_19` (`virtuemart_currency_id`),
  KEY `virtuemart_currency_id_20` (`virtuemart_currency_id`),
  KEY `virtuemart_currency_id_21` (`virtuemart_currency_id`),
  KEY `virtuemart_currency_id_22` (`virtuemart_currency_id`),
  KEY `virtuemart_currency_id_23` (`virtuemart_currency_id`),
  KEY `virtuemart_currency_id_24` (`virtuemart_currency_id`),
  KEY `virtuemart_currency_id_25` (`virtuemart_currency_id`),
  KEY `virtuemart_currency_id_26` (`virtuemart_currency_id`),
  KEY `virtuemart_currency_id_27` (`virtuemart_currency_id`),
  KEY `virtuemart_currency_id_28` (`virtuemart_currency_id`),
  KEY `virtuemart_currency_id_29` (`virtuemart_currency_id`),
  KEY `virtuemart_currency_id_30` (`virtuemart_currency_id`),
  KEY `virtuemart_currency_id_31` (`virtuemart_currency_id`),
  KEY `virtuemart_currency_id_32` (`virtuemart_currency_id`),
  KEY `virtuemart_currency_id_33` (`virtuemart_currency_id`),
  KEY `virtuemart_currency_id_34` (`virtuemart_currency_id`),
  KEY `virtuemart_currency_id_35` (`virtuemart_currency_id`),
  KEY `virtuemart_currency_id_36` (`virtuemart_currency_id`),
  KEY `virtuemart_currency_id_37` (`virtuemart_currency_id`),
  KEY `virtuemart_currency_id_38` (`virtuemart_currency_id`),
  KEY `virtuemart_currency_id_39` (`virtuemart_currency_id`),
  KEY `virtuemart_currency_id_40` (`virtuemart_currency_id`),
  KEY `virtuemart_currency_id_41` (`virtuemart_currency_id`),
  KEY `virtuemart_currency_id_42` (`virtuemart_currency_id`),
  KEY `virtuemart_currency_id_43` (`virtuemart_currency_id`),
  KEY `virtuemart_currency_id_44` (`virtuemart_currency_id`),
  KEY `virtuemart_currency_id_45` (`virtuemart_currency_id`),
  KEY `virtuemart_currency_id_46` (`virtuemart_currency_id`),
  KEY `virtuemart_currency_id_47` (`virtuemart_currency_id`)
) ENGINE=MyISAM AUTO_INCREMENT=207 DEFAULT CHARSET=utf8 COMMENT='Used to store currencies';

/*Table structure for table `me1u8_virtuemart_customs` */

DROP TABLE IF EXISTS `me1u8_virtuemart_customs`;

CREATE TABLE `me1u8_virtuemart_customs` (
  `virtuemart_custom_id` int(11) DEFAULT NULL,
  `custom_parent_id` int(11) DEFAULT NULL,
  `virtuemart_vendor_id` int(11) DEFAULT NULL,
  `custom_jplugin_id` int(11) DEFAULT NULL,
  `custom_element` int(11) DEFAULT NULL,
  `admin_only` int(11) DEFAULT NULL,
  `custom_title` int(11) DEFAULT NULL,
  `show_title` int(11) DEFAULT NULL,
  `custom_tip` int(11) DEFAULT NULL,
  `custom_value` int(11) DEFAULT NULL,
  `custom_desc` int(11) DEFAULT NULL,
  `field_type` int(11) DEFAULT NULL,
  `is_list` int(11) DEFAULT NULL,
  `is_hidden` int(11) DEFAULT NULL,
  `is_cart_attribute` int(11) DEFAULT NULL,
  `is_input` int(11) DEFAULT NULL,
  `layout_pos` int(11) DEFAULT NULL,
  `custom_params` int(11) DEFAULT NULL,
  `shared` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  KEY `custom_parent_id` (`custom_parent_id`),
  KEY `virtuemart_vendor_id` (`virtuemart_vendor_id`),
  KEY `custom_element` (`custom_element`),
  KEY `field_type` (`field_type`),
  KEY `is_cart_attribute` (`is_cart_attribute`),
  KEY `is_input` (`is_input`),
  KEY `shared` (`shared`),
  KEY `published` (`published`),
  KEY `ordering` (`ordering`),
  KEY `virtuemart_custom_id` (`virtuemart_custom_id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COMMENT='custom fields definition';

/*Table structure for table `me1u8_virtuemart_document` */

DROP TABLE IF EXISTS `me1u8_virtuemart_document`;

CREATE TABLE `me1u8_virtuemart_document` (
  `virtuemart_document_id` int(11) DEFAULT NULL,
  `title` int(11) DEFAULT NULL,
  `icon` int(11) DEFAULT NULL,
  `meta_title` int(11) DEFAULT NULL,
  `key_word` int(11) DEFAULT NULL,
  `description` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `shared` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  KEY `ordering` (`ordering`),
  KEY `published` (`published`),
  KEY `shared` (`shared`),
  KEY `virtuemart_document_id` (`virtuemart_document_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='Used to store currencies';

/*Table structure for table `me1u8_virtuemart_excursion_addon` */

DROP TABLE IF EXISTS `me1u8_virtuemart_excursion_addon`;

CREATE TABLE `me1u8_virtuemart_excursion_addon` (
  `virtuemart_excursion_addon_id` int(11) NOT NULL AUTO_INCREMENT,
  `excursion_addon_name` varchar(50) NOT NULL,
  `description` text,
  `excursion_payment_type` varchar(20) DEFAULT NULL,
  `vail_from` date DEFAULT NULL,
  `vail_to` date DEFAULT NULL,
  `conditions` text,
  `itinerary` text,
  `inclusion` text,
  `data_price` longtext NOT NULL,
  `passenger_age_from` int(11) DEFAULT NULL,
  `passenger_age_to` int(11) DEFAULT NULL,
  `virtuemart_cityarea_id` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `shared` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`virtuemart_excursion_addon_id`),
  KEY `virtuemart_cityarea_id` (`virtuemart_cityarea_id`),
  CONSTRAINT `me1u8_virtuemart_excursion_addon_ibfk_1` FOREIGN KEY (`virtuemart_cityarea_id`) REFERENCES `me1u8_virtuemart_cityarea` (`virtuemart_cityarea_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_virtuemart_faq` */

DROP TABLE IF EXISTS `me1u8_virtuemart_faq`;

CREATE TABLE `me1u8_virtuemart_faq` (
  `virtuemart_faq_id` int(11) DEFAULT NULL,
  `title` int(11) DEFAULT NULL,
  `icon` int(11) DEFAULT NULL,
  `meta_title` int(11) DEFAULT NULL,
  `key_word` int(11) DEFAULT NULL,
  `description` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `shared` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  KEY `ordering` (`ordering`),
  KEY `published` (`published`),
  KEY `shared` (`shared`),
  KEY `virtuemart_faq_id` (`virtuemart_faq_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='Used to store currencies';

/*Table structure for table `me1u8_virtuemart_group_size` */

DROP TABLE IF EXISTS `me1u8_virtuemart_group_size`;

CREATE TABLE `me1u8_virtuemart_group_size` (
  `virtuemart_group_size_id` int(11) NOT NULL DEFAULT '0',
  `group_name` int(11) DEFAULT NULL,
  `from` int(11) DEFAULT NULL,
  `to` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `shared` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`virtuemart_group_size_id`),
  KEY `ordering` (`ordering`),
  KEY `published` (`published`),
  KEY `shared` (`shared`),
  KEY `virtuemart_group_size_id` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_2` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_3` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_4` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_5` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_6` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_7` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_8` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_9` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_10` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_11` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_12` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_13` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_14` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_15` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_16` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_17` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_18` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_19` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_20` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_21` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_22` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_23` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_24` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_25` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_26` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_27` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_28` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_29` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_30` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_31` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_32` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_33` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_34` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_35` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_36` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_37` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_38` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_39` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_40` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_41` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_42` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_43` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_44` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_45` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_46` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_47` (`virtuemart_group_size_id`),
  KEY `virtuemart_group_size_id_48` (`virtuemart_group_size_id`)
) ENGINE=MyISAM AUTO_INCREMENT=204 DEFAULT CHARSET=utf8 COMMENT='Used to store currencies';

/*Table structure for table `me1u8_virtuemart_hotel` */

DROP TABLE IF EXISTS `me1u8_virtuemart_hotel`;

CREATE TABLE `me1u8_virtuemart_hotel` (
  `virtuemart_hotel_id` int(11) NOT NULL DEFAULT '0',
  `title` int(11) DEFAULT NULL,
  `address` int(11) DEFAULT NULL,
  `virtuemart_cityarea_id` int(11) DEFAULT NULL,
  `star_rating` int(11) DEFAULT NULL,
  `review` int(11) DEFAULT NULL,
  `photo` int(11) DEFAULT NULL,
  `phone` int(11) DEFAULT NULL,
  `description` int(11) DEFAULT NULL,
  `website` int(11) DEFAULT NULL,
  `google_map` int(11) DEFAULT NULL,
  `overview` int(11) DEFAULT NULL,
  `room_info` int(11) DEFAULT NULL,
  `facility_info` int(11) DEFAULT NULL,
  `hotel_photo1` int(11) DEFAULT NULL,
  `hotel_photo2` int(11) DEFAULT NULL,
  `facility_photo1` int(11) DEFAULT NULL,
  `facility_photo2` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `shared` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` datetime DEFAULT NULL,
  PRIMARY KEY (`virtuemart_hotel_id`),
  KEY `ordering` (`ordering`),
  KEY `published` (`published`),
  KEY `shared` (`shared`),
  KEY `virtuemart_hotel_id` (`virtuemart_hotel_id`),
  KEY `virtuemart_hotel_id_2` (`virtuemart_hotel_id`),
  KEY `virtuemart_hotel_id_3` (`virtuemart_hotel_id`),
  KEY `virtuemart_hotel_id_4` (`virtuemart_hotel_id`),
  KEY `virtuemart_hotel_id_5` (`virtuemart_hotel_id`),
  KEY `virtuemart_hotel_id_6` (`virtuemart_hotel_id`),
  KEY `virtuemart_hotel_id_7` (`virtuemart_hotel_id`),
  KEY `virtuemart_hotel_id_8` (`virtuemart_hotel_id`),
  KEY `virtuemart_hotel_id_9` (`virtuemart_hotel_id`),
  KEY `virtuemart_hotel_id_10` (`virtuemart_hotel_id`),
  KEY `virtuemart_hotel_id_11` (`virtuemart_hotel_id`),
  KEY `virtuemart_hotel_id_12` (`virtuemart_hotel_id`),
  KEY `virtuemart_hotel_id_13` (`virtuemart_hotel_id`),
  KEY `virtuemart_hotel_id_14` (`virtuemart_hotel_id`),
  KEY `virtuemart_hotel_id_15` (`virtuemart_hotel_id`),
  KEY `virtuemart_hotel_id_16` (`virtuemart_hotel_id`),
  KEY `virtuemart_hotel_id_17` (`virtuemart_hotel_id`),
  KEY `virtuemart_hotel_id_18` (`virtuemart_hotel_id`),
  KEY `virtuemart_hotel_id_19` (`virtuemart_hotel_id`),
  KEY `virtuemart_hotel_id_20` (`virtuemart_hotel_id`),
  KEY `virtuemart_hotel_id_21` (`virtuemart_hotel_id`),
  KEY `virtuemart_hotel_id_22` (`virtuemart_hotel_id`),
  KEY `virtuemart_hotel_id_23` (`virtuemart_hotel_id`),
  KEY `virtuemart_hotel_id_24` (`virtuemart_hotel_id`),
  KEY `virtuemart_hotel_id_25` (`virtuemart_hotel_id`),
  KEY `virtuemart_hotel_id_26` (`virtuemart_hotel_id`),
  KEY `virtuemart_hotel_id_27` (`virtuemart_hotel_id`),
  KEY `virtuemart_hotel_id_28` (`virtuemart_hotel_id`),
  KEY `virtuemart_hotel_id_29` (`virtuemart_hotel_id`),
  KEY `virtuemart_hotel_id_30` (`virtuemart_hotel_id`)
) ENGINE=MyISAM AUTO_INCREMENT=203 DEFAULT CHARSET=utf8 COMMENT='Used to store currencies';

/*Table structure for table `me1u8_virtuemart_hotel_addon` */

DROP TABLE IF EXISTS `me1u8_virtuemart_hotel_addon`;

CREATE TABLE `me1u8_virtuemart_hotel_addon` (
  `virtuemart_hotel_addon_id` int(11) NOT NULL AUTO_INCREMENT,
  `hotel_addon_type` varchar(20) DEFAULT NULL,
  `hotel_addon_name` varchar(50) DEFAULT NULL,
  `description` text,
  `hotel_payment_type` varchar(20) DEFAULT NULL,
  `hotel_addon_service_class` varchar(200) NOT NULL,
  `vail_from` date DEFAULT NULL,
  `vail_to` date DEFAULT NULL,
  `conditions` text,
  `itinerary` text,
  `inclusion` text,
  `data_price` longtext NOT NULL,
  `passenger_age_from` int(11) DEFAULT NULL,
  `passenger_age_to` int(11) DEFAULT NULL,
  `virtuemart_cityarea_id` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `shared` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`virtuemart_hotel_addon_id`),
  KEY `virtuemart_cityarea_id` (`virtuemart_cityarea_id`),
  CONSTRAINT `me1u8_virtuemart_hotel_addon_ibfk_1` FOREIGN KEY (`virtuemart_cityarea_id`) REFERENCES `me1u8_virtuemart_cityarea` (`virtuemart_cityarea_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_virtuemart_hotel_id_itinerary_id` */

DROP TABLE IF EXISTS `me1u8_virtuemart_hotel_id_itinerary_id`;

CREATE TABLE `me1u8_virtuemart_hotel_id_itinerary_id` (
  `id` int(11) NOT NULL DEFAULT '0',
  `virtuemart_itinerary_id` int(11) DEFAULT NULL,
  `virtuemart_hotel_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `virtuemart_itinerary_id` (`virtuemart_itinerary_id`),
  KEY `virtuemart_hotel_id` (`virtuemart_hotel_id`),
  KEY `id` (`id`),
  KEY `id_2` (`id`),
  KEY `id_3` (`id`),
  KEY `id_4` (`id`),
  KEY `id_5` (`id`),
  KEY `id_6` (`id`),
  KEY `id_7` (`id`),
  KEY `id_8` (`id`),
  KEY `id_9` (`id`),
  KEY `id_10` (`id`),
  KEY `id_11` (`id`),
  KEY `id_12` (`id`),
  KEY `id_13` (`id`),
  KEY `id_14` (`id`),
  KEY `id_15` (`id`),
  KEY `id_16` (`id`),
  KEY `id_17` (`id`),
  KEY `id_18` (`id`),
  KEY `id_19` (`id`),
  KEY `id_20` (`id`),
  KEY `id_21` (`id`),
  KEY `id_22` (`id`),
  KEY `id_23` (`id`),
  KEY `id_24` (`id`),
  KEY `id_25` (`id`),
  KEY `id_26` (`id`),
  KEY `id_27` (`id`),
  KEY `id_28` (`id`),
  KEY `id_29` (`id`),
  KEY `id_30` (`id`),
  CONSTRAINT `me1u8_virtuemart_hotel_id_itinerary_id_ibfk_1` FOREIGN KEY (`virtuemart_itinerary_id`) REFERENCES `me1u8_virtuemart_itinerary` (`virtuemart_itinerary_id`),
  CONSTRAINT `me1u8_virtuemart_hotel_id_itinerary_id_ibfk_2` FOREIGN KEY (`virtuemart_hotel_id`) REFERENCES `me1u8_virtuemart_hotel` (`virtuemart_hotel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `me1u8_virtuemart_invoices` */

DROP TABLE IF EXISTS `me1u8_virtuemart_invoices`;

CREATE TABLE `me1u8_virtuemart_invoices` (
  `virtuemart_invoice_id` int(11) DEFAULT NULL,
  `virtuemart_vendor_id` int(11) DEFAULT NULL,
  `virtuemart_order_id` int(11) DEFAULT NULL,
  `invoice_number` int(11) DEFAULT NULL,
  `order_status` int(11) DEFAULT NULL,
  `xhtml` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  UNIQUE KEY `invoice_number` (`invoice_number`,`virtuemart_vendor_id`),
  KEY `virtuemart_order_id` (`virtuemart_order_id`),
  KEY `virtuemart_vendor_id` (`virtuemart_vendor_id`),
  KEY `virtuemart_invoice_id` (`virtuemart_invoice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='custom fields definition';

/*Table structure for table `me1u8_virtuemart_itinerary` */

DROP TABLE IF EXISTS `me1u8_virtuemart_itinerary`;

CREATE TABLE `me1u8_virtuemart_itinerary` (
  `virtuemart_itinerary_id` int(11) NOT NULL DEFAULT '0',
  `title` int(11) DEFAULT NULL,
  `brief_itinerary` int(11) DEFAULT NULL,
  `full_itinerary` int(11) DEFAULT NULL,
  `overnight` int(11) DEFAULT NULL,
  `trip_note1` int(11) DEFAULT NULL,
  `trip_note2` int(11) DEFAULT NULL,
  `photo1` int(11) DEFAULT NULL,
  `photo2` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `shared` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`virtuemart_itinerary_id`),
  KEY `ordering` (`ordering`),
  KEY `published` (`published`),
  KEY `shared` (`shared`),
  KEY `virtuemart_itinerary_id` (`virtuemart_itinerary_id`),
  KEY `virtuemart_itinerary_id_2` (`virtuemart_itinerary_id`),
  KEY `virtuemart_itinerary_id_3` (`virtuemart_itinerary_id`),
  KEY `virtuemart_itinerary_id_4` (`virtuemart_itinerary_id`),
  KEY `virtuemart_itinerary_id_5` (`virtuemart_itinerary_id`),
  KEY `virtuemart_itinerary_id_6` (`virtuemart_itinerary_id`),
  KEY `virtuemart_itinerary_id_7` (`virtuemart_itinerary_id`),
  KEY `virtuemart_itinerary_id_8` (`virtuemart_itinerary_id`),
  KEY `virtuemart_itinerary_id_9` (`virtuemart_itinerary_id`),
  KEY `virtuemart_itinerary_id_10` (`virtuemart_itinerary_id`),
  KEY `virtuemart_itinerary_id_11` (`virtuemart_itinerary_id`),
  KEY `virtuemart_itinerary_id_12` (`virtuemart_itinerary_id`),
  KEY `virtuemart_itinerary_id_13` (`virtuemart_itinerary_id`),
  KEY `virtuemart_itinerary_id_14` (`virtuemart_itinerary_id`),
  KEY `virtuemart_itinerary_id_15` (`virtuemart_itinerary_id`),
  KEY `virtuemart_itinerary_id_16` (`virtuemart_itinerary_id`),
  KEY `virtuemart_itinerary_id_17` (`virtuemart_itinerary_id`),
  KEY `virtuemart_itinerary_id_18` (`virtuemart_itinerary_id`),
  KEY `virtuemart_itinerary_id_19` (`virtuemart_itinerary_id`),
  KEY `virtuemart_itinerary_id_20` (`virtuemart_itinerary_id`),
  KEY `virtuemart_itinerary_id_21` (`virtuemart_itinerary_id`),
  KEY `virtuemart_itinerary_id_22` (`virtuemart_itinerary_id`),
  KEY `virtuemart_itinerary_id_23` (`virtuemart_itinerary_id`),
  KEY `virtuemart_itinerary_id_24` (`virtuemart_itinerary_id`),
  KEY `virtuemart_itinerary_id_25` (`virtuemart_itinerary_id`),
  KEY `virtuemart_itinerary_id_26` (`virtuemart_itinerary_id`),
  KEY `virtuemart_itinerary_id_27` (`virtuemart_itinerary_id`),
  KEY `virtuemart_itinerary_id_28` (`virtuemart_itinerary_id`),
  KEY `virtuemart_itinerary_id_29` (`virtuemart_itinerary_id`),
  KEY `virtuemart_itinerary_id_30` (`virtuemart_itinerary_id`)
) ENGINE=MyISAM AUTO_INCREMENT=204 DEFAULT CHARSET=utf8 COMMENT='Used to store currencies';

/*Table structure for table `me1u8_virtuemart_itinerary_id_meal_id` */

DROP TABLE IF EXISTS `me1u8_virtuemart_itinerary_id_meal_id`;

CREATE TABLE `me1u8_virtuemart_itinerary_id_meal_id` (
  `id` int(11) NOT NULL DEFAULT '0',
  `virtuemart_itinerary_id` int(11) DEFAULT NULL,
  `virtuemart_meal_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `virtuemart_product_id` (`virtuemart_itinerary_id`,`virtuemart_meal_id`),
  KEY `id` (`id`),
  KEY `id_2` (`id`),
  KEY `id_3` (`id`),
  KEY `id_4` (`id`),
  KEY `id_5` (`id`),
  KEY `id_6` (`id`),
  KEY `id_7` (`id`),
  KEY `id_8` (`id`),
  KEY `id_9` (`id`),
  KEY `id_10` (`id`),
  KEY `id_11` (`id`),
  KEY `id_12` (`id`),
  KEY `id_13` (`id`),
  KEY `id_14` (`id`),
  KEY `id_15` (`id`),
  KEY `id_16` (`id`),
  KEY `id_17` (`id`),
  KEY `id_18` (`id`),
  KEY `id_19` (`id`),
  KEY `id_20` (`id`),
  KEY `id_21` (`id`),
  KEY `id_22` (`id`),
  KEY `id_23` (`id`),
  KEY `id_24` (`id`),
  KEY `id_25` (`id`),
  KEY `id_26` (`id`),
  KEY `id_27` (`id`),
  KEY `id_28` (`id`),
  KEY `id_29` (`id`),
  KEY `id_30` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=201 DEFAULT CHARSET=utf8 COMMENT='Maps Products to Categories';

/*Table structure for table `me1u8_virtuemart_language` */

DROP TABLE IF EXISTS `me1u8_virtuemart_language`;

CREATE TABLE `me1u8_virtuemart_language` (
  `virtuemart_language_id` int(11) NOT NULL DEFAULT '0',
  `virtuemart_vendor_id` int(11) DEFAULT NULL,
  `language_name` int(11) DEFAULT NULL,
  `iso2` int(11) DEFAULT NULL,
  `iso3` char(3) DEFAULT NULL,
  `sign` int(11) DEFAULT NULL,
  `virtuemart_country_id` int(11) DEFAULT NULL,
  `currency_numeric_code` int(11) DEFAULT NULL,
  `currency_exchange_rate` int(11) DEFAULT NULL,
  `currency_symbol` int(11) DEFAULT NULL,
  `currency_decimal_place` int(11) DEFAULT NULL,
  `currency_decimal_symbol` int(11) DEFAULT NULL,
  `currency_thousands` int(11) DEFAULT NULL,
  `currency_positive_style` int(11) DEFAULT NULL,
  `currency_negative_style` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `shared` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`virtuemart_language_id`),
  UNIQUE KEY `currency_code_3` (`iso3`),
  KEY `ordering` (`ordering`),
  KEY `currency_name` (`language_name`),
  KEY `published` (`published`),
  KEY `shared` (`shared`),
  KEY `virtuemart_vendor_id` (`virtuemart_vendor_id`),
  KEY `currency_numeric_code` (`currency_numeric_code`),
  KEY `virtuemart_language_id` (`virtuemart_language_id`),
  KEY `virtuemart_language_id_2` (`virtuemart_language_id`),
  KEY `virtuemart_language_id_3` (`virtuemart_language_id`),
  KEY `virtuemart_language_id_4` (`virtuemart_language_id`),
  KEY `virtuemart_language_id_5` (`virtuemart_language_id`),
  KEY `virtuemart_language_id_6` (`virtuemart_language_id`),
  KEY `virtuemart_language_id_7` (`virtuemart_language_id`),
  KEY `virtuemart_language_id_8` (`virtuemart_language_id`),
  KEY `virtuemart_language_id_9` (`virtuemart_language_id`),
  KEY `virtuemart_language_id_10` (`virtuemart_language_id`),
  KEY `virtuemart_language_id_11` (`virtuemart_language_id`),
  KEY `virtuemart_language_id_12` (`virtuemart_language_id`),
  KEY `virtuemart_language_id_13` (`virtuemart_language_id`),
  KEY `virtuemart_language_id_14` (`virtuemart_language_id`),
  KEY `virtuemart_language_id_15` (`virtuemart_language_id`),
  KEY `virtuemart_language_id_16` (`virtuemart_language_id`),
  KEY `virtuemart_language_id_17` (`virtuemart_language_id`),
  KEY `virtuemart_language_id_18` (`virtuemart_language_id`),
  KEY `virtuemart_language_id_19` (`virtuemart_language_id`),
  KEY `virtuemart_language_id_20` (`virtuemart_language_id`),
  KEY `virtuemart_language_id_21` (`virtuemart_language_id`),
  KEY `virtuemart_language_id_22` (`virtuemart_language_id`),
  KEY `virtuemart_language_id_23` (`virtuemart_language_id`),
  KEY `virtuemart_language_id_24` (`virtuemart_language_id`),
  KEY `virtuemart_language_id_25` (`virtuemart_language_id`),
  KEY `virtuemart_language_id_26` (`virtuemart_language_id`),
  KEY `virtuemart_language_id_27` (`virtuemart_language_id`),
  KEY `virtuemart_language_id_28` (`virtuemart_language_id`),
  KEY `virtuemart_language_id_29` (`virtuemart_language_id`),
  KEY `virtuemart_language_id_30` (`virtuemart_language_id`)
) ENGINE=MyISAM AUTO_INCREMENT=206 DEFAULT CHARSET=utf8 COMMENT='Used to store currencies';

/*Table structure for table `me1u8_virtuemart_manufacturercategories_en_gb` */

DROP TABLE IF EXISTS `me1u8_virtuemart_manufacturercategories_en_gb`;

CREATE TABLE `me1u8_virtuemart_manufacturercategories_en_gb` (
  `virtuemart_manufacturercategories_id` int(11) NOT NULL DEFAULT '0',
  `mf_category_name` int(11) DEFAULT NULL,
  `mf_category_desc` int(11) DEFAULT NULL,
  `slug` int(11) DEFAULT NULL,
  PRIMARY KEY (`virtuemart_manufacturercategories_id`),
  KEY `virtuemart_manufacturercategories_id` (`virtuemart_manufacturercategories_id`),
  KEY `virtuemart_manufacturercategor_2` (`virtuemart_manufacturercategories_id`),
  KEY `virtuemart_manufacturercategor_3` (`virtuemart_manufacturercategories_id`),
  KEY `virtuemart_manufacturercategor_4` (`virtuemart_manufacturercategories_id`),
  KEY `virtuemart_manufacturercategor_5` (`virtuemart_manufacturercategories_id`),
  KEY `virtuemart_manufacturercategor_6` (`virtuemart_manufacturercategories_id`),
  KEY `virtuemart_manufacturercategor_7` (`virtuemart_manufacturercategories_id`),
  KEY `virtuemart_manufacturercategor_8` (`virtuemart_manufacturercategories_id`),
  KEY `virtuemart_manufacturercategor_9` (`virtuemart_manufacturercategories_id`),
  KEY `virtuemart_manufacturercategor_10` (`virtuemart_manufacturercategories_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `me1u8_virtuemart_manufacturers` */

DROP TABLE IF EXISTS `me1u8_virtuemart_manufacturers`;

CREATE TABLE `me1u8_virtuemart_manufacturers` (
  `virtuemart_manufacturer_id` int(11) NOT NULL DEFAULT '0',
  `virtuemart_manufacturercategories_id` int(11) DEFAULT NULL,
  `hits` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`virtuemart_manufacturer_id`),
  KEY `virtuemart_manufacturer_id` (`virtuemart_manufacturer_id`),
  KEY `virtuemart_manufacturer_id_2` (`virtuemart_manufacturer_id`),
  KEY `virtuemart_manufacturer_id_3` (`virtuemart_manufacturer_id`),
  KEY `virtuemart_manufacturer_id_4` (`virtuemart_manufacturer_id`),
  KEY `virtuemart_manufacturer_id_5` (`virtuemart_manufacturer_id`),
  KEY `virtuemart_manufacturer_id_6` (`virtuemart_manufacturer_id`),
  KEY `virtuemart_manufacturer_id_7` (`virtuemart_manufacturer_id`),
  KEY `virtuemart_manufacturer_id_8` (`virtuemart_manufacturer_id`),
  KEY `virtuemart_manufacturer_id_9` (`virtuemart_manufacturer_id`),
  KEY `virtuemart_manufacturer_id_10` (`virtuemart_manufacturer_id`),
  KEY `virtuemart_manufacturer_id_11` (`virtuemart_manufacturer_id`),
  KEY `virtuemart_manufacturer_id_12` (`virtuemart_manufacturer_id`),
  KEY `virtuemart_manufacturer_id_13` (`virtuemart_manufacturer_id`),
  KEY `virtuemart_manufacturer_id_14` (`virtuemart_manufacturer_id`),
  KEY `virtuemart_manufacturer_id_15` (`virtuemart_manufacturer_id`),
  KEY `virtuemart_manufacturer_id_16` (`virtuemart_manufacturer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `me1u8_virtuemart_manufacturers_en_gb` */

DROP TABLE IF EXISTS `me1u8_virtuemart_manufacturers_en_gb`;

CREATE TABLE `me1u8_virtuemart_manufacturers_en_gb` (
  `virtuemart_manufacturer_id` int(11) DEFAULT NULL,
  `mf_name` int(11) DEFAULT NULL,
  `mf_email` int(11) DEFAULT NULL,
  `mf_desc` int(11) DEFAULT NULL,
  `mf_url` int(11) DEFAULT NULL,
  `slug` int(11) DEFAULT NULL,
  KEY `virtuemart_manufacturer_id` (`virtuemart_manufacturer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `me1u8_virtuemart_meal` */

DROP TABLE IF EXISTS `me1u8_virtuemart_meal`;

CREATE TABLE `me1u8_virtuemart_meal` (
  `virtuemart_meal_id` int(11) NOT NULL DEFAULT '0',
  `title` int(11) DEFAULT NULL,
  `icon` int(11) DEFAULT NULL,
  `meta_title` int(11) DEFAULT NULL,
  `key_word` int(11) DEFAULT NULL,
  `description` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `shared` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`virtuemart_meal_id`),
  KEY `ordering` (`ordering`),
  KEY `published` (`published`),
  KEY `shared` (`shared`),
  KEY `virtuemart_meal_id` (`virtuemart_meal_id`),
  KEY `virtuemart_meal_id_2` (`virtuemart_meal_id`),
  KEY `virtuemart_meal_id_3` (`virtuemart_meal_id`),
  KEY `virtuemart_meal_id_4` (`virtuemart_meal_id`),
  KEY `virtuemart_meal_id_5` (`virtuemart_meal_id`),
  KEY `virtuemart_meal_id_6` (`virtuemart_meal_id`),
  KEY `virtuemart_meal_id_7` (`virtuemart_meal_id`),
  KEY `virtuemart_meal_id_8` (`virtuemart_meal_id`),
  KEY `virtuemart_meal_id_9` (`virtuemart_meal_id`),
  KEY `virtuemart_meal_id_10` (`virtuemart_meal_id`),
  KEY `virtuemart_meal_id_11` (`virtuemart_meal_id`),
  KEY `virtuemart_meal_id_12` (`virtuemart_meal_id`),
  KEY `virtuemart_meal_id_13` (`virtuemart_meal_id`),
  KEY `virtuemart_meal_id_14` (`virtuemart_meal_id`),
  KEY `virtuemart_meal_id_15` (`virtuemart_meal_id`),
  KEY `virtuemart_meal_id_16` (`virtuemart_meal_id`),
  KEY `virtuemart_meal_id_17` (`virtuemart_meal_id`),
  KEY `virtuemart_meal_id_18` (`virtuemart_meal_id`),
  KEY `virtuemart_meal_id_19` (`virtuemart_meal_id`),
  KEY `virtuemart_meal_id_20` (`virtuemart_meal_id`),
  KEY `virtuemart_meal_id_21` (`virtuemart_meal_id`),
  KEY `virtuemart_meal_id_22` (`virtuemart_meal_id`),
  KEY `virtuemart_meal_id_23` (`virtuemart_meal_id`),
  KEY `virtuemart_meal_id_24` (`virtuemart_meal_id`),
  KEY `virtuemart_meal_id_25` (`virtuemart_meal_id`),
  KEY `virtuemart_meal_id_26` (`virtuemart_meal_id`),
  KEY `virtuemart_meal_id_27` (`virtuemart_meal_id`),
  KEY `virtuemart_meal_id_28` (`virtuemart_meal_id`),
  KEY `virtuemart_meal_id_29` (`virtuemart_meal_id`),
  KEY `virtuemart_meal_id_30` (`virtuemart_meal_id`)
) ENGINE=MyISAM AUTO_INCREMENT=209 DEFAULT CHARSET=utf8 COMMENT='Used to store currencies';

/*Table structure for table `me1u8_virtuemart_medias` */

DROP TABLE IF EXISTS `me1u8_virtuemart_medias`;

CREATE TABLE `me1u8_virtuemart_medias` (
  `virtuemart_media_id` int(11) NOT NULL DEFAULT '0',
  `virtuemart_vendor_id` int(11) DEFAULT NULL,
  `file_title` int(11) DEFAULT NULL,
  `file_description` int(11) DEFAULT NULL,
  `file_meta` int(11) DEFAULT NULL,
  `file_class` int(11) DEFAULT NULL,
  `file_mimetype` int(11) DEFAULT NULL,
  `file_type` int(11) DEFAULT NULL,
  `file_url` int(11) DEFAULT NULL,
  `file_url_thumb` int(11) DEFAULT NULL,
  `file_is_product_image` int(11) DEFAULT NULL,
  `file_is_downloadable` int(11) DEFAULT NULL,
  `file_is_forsale` int(11) DEFAULT NULL,
  `file_params` int(11) DEFAULT NULL,
  `file_lang` int(11) DEFAULT NULL,
  `shared` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`virtuemart_media_id`),
  KEY `virtuemart_vendor_id` (`virtuemart_vendor_id`),
  KEY `published` (`published`),
  KEY `file_type` (`file_type`),
  KEY `shared` (`shared`),
  KEY `virtuemart_media_id` (`virtuemart_media_id`),
  KEY `virtuemart_media_id_2` (`virtuemart_media_id`),
  KEY `virtuemart_media_id_3` (`virtuemart_media_id`),
  KEY `virtuemart_media_id_4` (`virtuemart_media_id`),
  KEY `virtuemart_media_id_5` (`virtuemart_media_id`),
  KEY `virtuemart_media_id_6` (`virtuemart_media_id`),
  KEY `virtuemart_media_id_7` (`virtuemart_media_id`),
  KEY `virtuemart_media_id_8` (`virtuemart_media_id`),
  KEY `virtuemart_media_id_9` (`virtuemart_media_id`),
  KEY `virtuemart_media_id_10` (`virtuemart_media_id`),
  KEY `virtuemart_media_id_11` (`virtuemart_media_id`),
  KEY `virtuemart_media_id_12` (`virtuemart_media_id`),
  KEY `virtuemart_media_id_13` (`virtuemart_media_id`),
  KEY `virtuemart_media_id_14` (`virtuemart_media_id`),
  KEY `virtuemart_media_id_15` (`virtuemart_media_id`),
  KEY `virtuemart_media_id_16` (`virtuemart_media_id`),
  KEY `virtuemart_media_id_17` (`virtuemart_media_id`),
  KEY `virtuemart_media_id_18` (`virtuemart_media_id`),
  KEY `virtuemart_media_id_19` (`virtuemart_media_id`),
  KEY `virtuemart_media_id_20` (`virtuemart_media_id`),
  KEY `virtuemart_media_id_21` (`virtuemart_media_id`),
  KEY `virtuemart_media_id_22` (`virtuemart_media_id`),
  KEY `virtuemart_media_id_23` (`virtuemart_media_id`),
  KEY `virtuemart_media_id_24` (`virtuemart_media_id`),
  KEY `virtuemart_media_id_25` (`virtuemart_media_id`),
  KEY `virtuemart_media_id_26` (`virtuemart_media_id`),
  KEY `virtuemart_media_id_27` (`virtuemart_media_id`),
  KEY `virtuemart_media_id_28` (`virtuemart_media_id`),
  KEY `virtuemart_media_id_29` (`virtuemart_media_id`)
) ENGINE=MyISAM AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COMMENT='Additional Images and Files which are assigned to products';

/*Table structure for table `me1u8_virtuemart_modules` */

DROP TABLE IF EXISTS `me1u8_virtuemart_modules`;

CREATE TABLE `me1u8_virtuemart_modules` (
  `module_id` int(11) DEFAULT NULL,
  `module_name` int(11) DEFAULT NULL,
  `module_description` int(11) DEFAULT NULL,
  `module_perms` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `is_admin` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  KEY `module_name` (`module_name`),
  KEY `ordering` (`ordering`),
  KEY `published` (`published`),
  KEY `module_id` (`module_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='VirtueMart Core Modules, not: Joomla modules';

/*Table structure for table `me1u8_virtuemart_order_calc_rules` */

DROP TABLE IF EXISTS `me1u8_virtuemart_order_calc_rules`;

CREATE TABLE `me1u8_virtuemart_order_calc_rules` (
  `virtuemart_order_calc_rule_id` int(11) DEFAULT NULL,
  `virtuemart_calc_id` int(11) DEFAULT NULL,
  `virtuemart_order_id` int(11) DEFAULT NULL,
  `virtuemart_vendor_id` int(11) DEFAULT NULL,
  `virtuemart_order_item_id` int(11) DEFAULT NULL,
  `calc_rule_name` int(11) DEFAULT NULL,
  `calc_kind` int(11) DEFAULT NULL,
  `calc_mathop` int(11) DEFAULT NULL,
  `calc_amount` int(11) DEFAULT NULL,
  `calc_result` int(11) DEFAULT NULL,
  `calc_value` int(11) DEFAULT NULL,
  `calc_currency` int(11) DEFAULT NULL,
  `calc_params` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  KEY `virtuemart_calc_id` (`virtuemart_calc_id`),
  KEY `virtuemart_order_id` (`virtuemart_order_id`),
  KEY `virtuemart_vendor_id` (`virtuemart_vendor_id`),
  KEY `virtuemart_order_calc_rule_id` (`virtuemart_order_calc_rule_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='Stores all calculation rules which are part of an order';

/*Table structure for table `me1u8_virtuemart_order_histories` */

DROP TABLE IF EXISTS `me1u8_virtuemart_order_histories`;

CREATE TABLE `me1u8_virtuemart_order_histories` (
  `virtuemart_order_history_id` int(11) DEFAULT NULL,
  `virtuemart_order_id` int(11) DEFAULT NULL,
  `order_status_code` int(11) DEFAULT NULL,
  `customer_notified` int(11) DEFAULT NULL,
  `comments` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  KEY `virtuemart_order_history_id` (`virtuemart_order_history_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores all actions and changes that occur to an order';

/*Table structure for table `me1u8_virtuemart_order_items` */

DROP TABLE IF EXISTS `me1u8_virtuemart_order_items`;

CREATE TABLE `me1u8_virtuemart_order_items` (
  `virtuemart_order_item_id` int(11) NOT NULL DEFAULT '0',
  `virtuemart_order_id` int(11) DEFAULT NULL,
  `virtuemart_vendor_id` int(11) DEFAULT NULL,
  `virtuemart_product_id` int(11) DEFAULT NULL,
  `order_item_sku` int(11) DEFAULT NULL,
  `order_item_name` int(11) DEFAULT NULL,
  `product_quantity` int(11) DEFAULT NULL,
  `product_item_price` int(11) DEFAULT NULL,
  `product_pricewithouttax` int(11) DEFAULT NULL,
  `product_tax` int(11) DEFAULT NULL,
  `product_basepricewithtax` int(11) DEFAULT NULL,
  `product_discountedpricewithouttax` int(11) DEFAULT NULL,
  `product_final_price` int(11) DEFAULT NULL,
  `product_subtotal_discount` int(11) DEFAULT NULL,
  `product_subtotal_with_tax` int(11) DEFAULT NULL,
  `order_item_currency` int(11) DEFAULT NULL,
  `order_status` int(11) DEFAULT NULL,
  `product_attribute` int(11) DEFAULT NULL,
  `delivery_date` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`virtuemart_order_item_id`),
  KEY `virtuemart_product_id` (`virtuemart_product_id`),
  KEY `virtuemart_order_id` (`virtuemart_order_id`),
  KEY `virtuemart_vendor_id` (`virtuemart_vendor_id`),
  KEY `order_status` (`order_status`),
  KEY `virtuemart_order_item_id` (`virtuemart_order_item_id`),
  KEY `virtuemart_order_item_id_2` (`virtuemart_order_item_id`),
  KEY `virtuemart_order_item_id_3` (`virtuemart_order_item_id`),
  KEY `virtuemart_order_item_id_4` (`virtuemart_order_item_id`),
  KEY `virtuemart_order_item_id_5` (`virtuemart_order_item_id`),
  KEY `virtuemart_order_item_id_6` (`virtuemart_order_item_id`),
  KEY `virtuemart_order_item_id_7` (`virtuemart_order_item_id`),
  KEY `virtuemart_order_item_id_8` (`virtuemart_order_item_id`),
  KEY `virtuemart_order_item_id_9` (`virtuemart_order_item_id`),
  KEY `virtuemart_order_item_id_10` (`virtuemart_order_item_id`),
  KEY `virtuemart_order_item_id_11` (`virtuemart_order_item_id`),
  KEY `virtuemart_order_item_id_12` (`virtuemart_order_item_id`),
  KEY `virtuemart_order_item_id_13` (`virtuemart_order_item_id`),
  KEY `virtuemart_order_item_id_14` (`virtuemart_order_item_id`),
  KEY `virtuemart_order_item_id_15` (`virtuemart_order_item_id`),
  KEY `virtuemart_order_item_id_16` (`virtuemart_order_item_id`),
  KEY `virtuemart_order_item_id_17` (`virtuemart_order_item_id`),
  KEY `virtuemart_order_item_id_18` (`virtuemart_order_item_id`),
  KEY `virtuemart_order_item_id_19` (`virtuemart_order_item_id`),
  KEY `virtuemart_order_item_id_20` (`virtuemart_order_item_id`),
  KEY `virtuemart_order_item_id_21` (`virtuemart_order_item_id`),
  KEY `virtuemart_order_item_id_22` (`virtuemart_order_item_id`),
  KEY `virtuemart_order_item_id_23` (`virtuemart_order_item_id`),
  KEY `virtuemart_order_item_id_24` (`virtuemart_order_item_id`),
  KEY `virtuemart_order_item_id_25` (`virtuemart_order_item_id`),
  KEY `virtuemart_order_item_id_26` (`virtuemart_order_item_id`),
  KEY `virtuemart_order_item_id_27` (`virtuemart_order_item_id`),
  KEY `virtuemart_order_item_id_28` (`virtuemart_order_item_id`),
  KEY `virtuemart_order_item_id_29` (`virtuemart_order_item_id`),
  KEY `virtuemart_order_item_id_30` (`virtuemart_order_item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores all items (products) which are part of an order';

/*Table structure for table `me1u8_virtuemart_order_userinfos` */

DROP TABLE IF EXISTS `me1u8_virtuemart_order_userinfos`;

CREATE TABLE `me1u8_virtuemart_order_userinfos` (
  `virtuemart_order_userinfo_id` int(11) DEFAULT NULL,
  `virtuemart_order_id` int(11) DEFAULT NULL,
  `virtuemart_user_id` int(11) DEFAULT NULL,
  `address_type` int(11) DEFAULT NULL,
  `address_type_name` int(11) DEFAULT NULL,
  `company` int(11) DEFAULT NULL,
  `title` int(11) DEFAULT NULL,
  `last_name` int(11) DEFAULT NULL,
  `first_name` int(11) DEFAULT NULL,
  `middle_name` int(11) DEFAULT NULL,
  `phone_1` int(11) DEFAULT NULL,
  `phone_2` int(11) DEFAULT NULL,
  `fax` int(11) DEFAULT NULL,
  `address_1` int(11) DEFAULT NULL,
  `address_2` int(11) DEFAULT NULL,
  `city` int(11) DEFAULT NULL,
  `virtuemart_state_id` int(11) DEFAULT NULL,
  `virtuemart_country_id` int(11) DEFAULT NULL,
  `zip` int(11) DEFAULT NULL,
  `email` int(11) DEFAULT NULL,
  `agreed` int(11) DEFAULT NULL,
  `tos` int(11) DEFAULT NULL,
  `customer_note` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  KEY `virtuemart_order_id` (`virtuemart_order_id`),
  KEY `virtuemart_user_id` (`virtuemart_user_id`,`address_type`),
  KEY `address_type` (`address_type`),
  KEY `virtuemart_order_userinfo_id` (`virtuemart_order_userinfo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Stores the BillTo and ShipTo Information at order time';

/*Table structure for table `me1u8_virtuemart_orders` */

DROP TABLE IF EXISTS `me1u8_virtuemart_orders`;

CREATE TABLE `me1u8_virtuemart_orders` (
  `virtuemart_order_id` int(11) DEFAULT NULL,
  `virtuemart_user_id` int(11) DEFAULT NULL,
  `virtuemart_vendor_id` int(11) DEFAULT NULL,
  `order_number` int(11) DEFAULT NULL,
  `customer_number` int(11) DEFAULT NULL,
  `order_pass` int(11) DEFAULT NULL,
  `order_create_invoice_pass` int(11) DEFAULT NULL,
  `order_total` int(11) DEFAULT NULL,
  `order_salesprice` int(11) DEFAULT NULL,
  `order_billtaxamount` int(11) DEFAULT NULL,
  `order_billtax` int(11) DEFAULT NULL,
  `order_billdiscountamount` int(11) DEFAULT NULL,
  `order_discountamount` int(11) DEFAULT NULL,
  `order_subtotal` int(11) DEFAULT NULL,
  `order_tax` int(11) DEFAULT NULL,
  `order_shipment` int(11) DEFAULT NULL,
  `order_shipment_tax` int(11) DEFAULT NULL,
  `order_payment` int(11) DEFAULT NULL,
  `order_payment_tax` int(11) DEFAULT NULL,
  `coupon_discount` int(11) DEFAULT NULL,
  `coupon_code` int(11) DEFAULT NULL,
  `order_discount` int(11) DEFAULT NULL,
  `order_currency` int(11) DEFAULT NULL,
  `order_status` int(11) DEFAULT NULL,
  `user_currency_id` int(11) DEFAULT NULL,
  `user_currency_rate` int(11) DEFAULT NULL,
  `virtuemart_paymentmethod_id` int(11) DEFAULT NULL,
  `virtuemart_shipmentmethod_id` int(11) DEFAULT NULL,
  `delivery_date` int(11) DEFAULT NULL,
  `order_language` int(11) DEFAULT NULL,
  `ip_address` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  KEY `virtuemart_user_id` (`virtuemart_user_id`),
  KEY `virtuemart_vendor_id` (`virtuemart_vendor_id`),
  KEY `order_number` (`order_number`),
  KEY `virtuemart_paymentmethod_id` (`virtuemart_paymentmethod_id`),
  KEY `virtuemart_shipmentmethod_id` (`virtuemart_shipmentmethod_id`),
  KEY `virtuemart_order_id` (`virtuemart_order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Used to store all orders';

/*Table structure for table `me1u8_virtuemart_orderstates` */

DROP TABLE IF EXISTS `me1u8_virtuemart_orderstates`;

CREATE TABLE `me1u8_virtuemart_orderstates` (
  `virtuemart_orderstate_id` int(11) DEFAULT NULL,
  `virtuemart_vendor_id` int(11) DEFAULT NULL,
  `order_status_code` int(11) DEFAULT NULL,
  `order_status_name` int(11) DEFAULT NULL,
  `order_status_description` int(11) DEFAULT NULL,
  `order_stock_handle` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  KEY `ordering` (`ordering`),
  KEY `virtuemart_vendor_id` (`virtuemart_vendor_id`),
  KEY `published` (`published`),
  KEY `virtuemart_orderstate_id` (`virtuemart_orderstate_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='All available order statuses';

/*Table structure for table `me1u8_virtuemart_payment` */

DROP TABLE IF EXISTS `me1u8_virtuemart_payment`;

CREATE TABLE `me1u8_virtuemart_payment` (
  `virtuemart_payment_id` int(11) DEFAULT NULL,
  `title` int(11) DEFAULT NULL,
  `virtuemart_currency_id` int(11) DEFAULT NULL,
  `vat` int(11) DEFAULT NULL,
  `cancel_fee` int(11) DEFAULT NULL,
  `credit_card_fee` int(11) DEFAULT NULL,
  `deposit_amount_type` int(11) DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `dep_term` int(11) DEFAULT NULL,
  `bal_term` int(11) DEFAULT NULL,
  `mode` int(11) DEFAULT NULL,
  `deposit_of_day` int(11) DEFAULT NULL,
  `balance_of_day` int(11) DEFAULT NULL,
  `cancellation_of_day` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `shared` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  KEY `ordering` (`ordering`),
  KEY `published` (`published`),
  KEY `shared` (`shared`),
  KEY `virtuemart_payment_id` (`virtuemart_payment_id`)
) ENGINE=MyISAM AUTO_INCREMENT=204 DEFAULT CHARSET=utf8 COMMENT='Used to store currencies';

/*Table structure for table `me1u8_virtuemart_paymentmethods` */

DROP TABLE IF EXISTS `me1u8_virtuemart_paymentmethods`;

CREATE TABLE `me1u8_virtuemart_paymentmethods` (
  `virtuemart_paymentmethod_id` int(11) NOT NULL DEFAULT '0',
  `virtuemart_vendor_id` int(11) DEFAULT NULL,
  `payment_jplugin_id` int(11) DEFAULT NULL,
  `payment_element` int(11) DEFAULT NULL,
  `payment_params` int(11) DEFAULT NULL,
  `shared` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`virtuemart_paymentmethod_id`),
  KEY `payment_jplugin_id` (`payment_jplugin_id`),
  KEY `virtuemart_vendor_id` (`virtuemart_vendor_id`),
  KEY `payment_element` (`payment_element`,`virtuemart_vendor_id`),
  KEY `ordering` (`ordering`),
  KEY `virtuemart_paymentmethod_id` (`virtuemart_paymentmethod_id`),
  KEY `virtuemart_paymentmethod_id_2` (`virtuemart_paymentmethod_id`),
  KEY `virtuemart_paymentmethod_id_3` (`virtuemart_paymentmethod_id`),
  KEY `virtuemart_paymentmethod_id_4` (`virtuemart_paymentmethod_id`),
  KEY `virtuemart_paymentmethod_id_5` (`virtuemart_paymentmethod_id`),
  KEY `virtuemart_paymentmethod_id_6` (`virtuemart_paymentmethod_id`),
  KEY `virtuemart_paymentmethod_id_7` (`virtuemart_paymentmethod_id`),
  KEY `virtuemart_paymentmethod_id_8` (`virtuemart_paymentmethod_id`),
  KEY `virtuemart_paymentmethod_id_9` (`virtuemart_paymentmethod_id`),
  KEY `virtuemart_paymentmethod_id_10` (`virtuemart_paymentmethod_id`),
  KEY `virtuemart_paymentmethod_id_11` (`virtuemart_paymentmethod_id`),
  KEY `virtuemart_paymentmethod_id_12` (`virtuemart_paymentmethod_id`),
  KEY `virtuemart_paymentmethod_id_13` (`virtuemart_paymentmethod_id`),
  KEY `virtuemart_paymentmethod_id_14` (`virtuemart_paymentmethod_id`),
  KEY `virtuemart_paymentmethod_id_15` (`virtuemart_paymentmethod_id`),
  KEY `virtuemart_paymentmethod_id_16` (`virtuemart_paymentmethod_id`),
  KEY `virtuemart_paymentmethod_id_17` (`virtuemart_paymentmethod_id`),
  KEY `virtuemart_paymentmethod_id_18` (`virtuemart_paymentmethod_id`),
  KEY `virtuemart_paymentmethod_id_19` (`virtuemart_paymentmethod_id`),
  KEY `virtuemart_paymentmethod_id_20` (`virtuemart_paymentmethod_id`),
  KEY `virtuemart_paymentmethod_id_21` (`virtuemart_paymentmethod_id`),
  KEY `virtuemart_paymentmethod_id_22` (`virtuemart_paymentmethod_id`),
  KEY `virtuemart_paymentmethod_id_23` (`virtuemart_paymentmethod_id`),
  KEY `virtuemart_paymentmethod_id_24` (`virtuemart_paymentmethod_id`),
  KEY `virtuemart_paymentmethod_id_25` (`virtuemart_paymentmethod_id`),
  KEY `virtuemart_paymentmethod_id_26` (`virtuemart_paymentmethod_id`),
  KEY `virtuemart_paymentmethod_id_27` (`virtuemart_paymentmethod_id`),
  KEY `virtuemart_paymentmethod_id_28` (`virtuemart_paymentmethod_id`),
  KEY `virtuemart_paymentmethod_id_29` (`virtuemart_paymentmethod_id`),
  KEY `virtuemart_paymentmethod_id_30` (`virtuemart_paymentmethod_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='The payment methods of your store';

/*Table structure for table `me1u8_virtuemart_paymentsetting` */

DROP TABLE IF EXISTS `me1u8_virtuemart_paymentsetting`;

CREATE TABLE `me1u8_virtuemart_paymentsetting` (
  `virtuemart_paymentsetting_id` int(11) NOT NULL DEFAULT '0',
  `config_mode` int(11) DEFAULT NULL,
  `deposit_term` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `virtuemart_currency_id` int(11) DEFAULT NULL,
  `credit_card_fee` int(11) DEFAULT NULL,
  `deposit_type` int(11) DEFAULT NULL,
  `deposit_amount` int(11) DEFAULT NULL,
  `balance_day_1` int(11) DEFAULT NULL,
  `balance_day_2` int(11) DEFAULT NULL,
  `balance_day_3` int(11) DEFAULT NULL,
  `balance_percent_1` int(11) DEFAULT NULL,
  `balance_percent_2` int(11) DEFAULT NULL,
  `balance_percent_3` int(11) DEFAULT NULL,
  `hold_seat` int(11) DEFAULT NULL,
  `rule_note` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `shared` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`virtuemart_paymentsetting_id`),
  KEY `ordering` (`ordering`),
  KEY `published` (`published`),
  KEY `shared` (`shared`),
  KEY `virtuemart_paymentsetting_id` (`virtuemart_paymentsetting_id`),
  KEY `virtuemart_paymentsetting_id_2` (`virtuemart_paymentsetting_id`),
  KEY `virtuemart_paymentsetting_id_3` (`virtuemart_paymentsetting_id`),
  KEY `virtuemart_paymentsetting_id_4` (`virtuemart_paymentsetting_id`),
  KEY `virtuemart_paymentsetting_id_5` (`virtuemart_paymentsetting_id`),
  KEY `virtuemart_paymentsetting_id_6` (`virtuemart_paymentsetting_id`),
  KEY `virtuemart_paymentsetting_id_7` (`virtuemart_paymentsetting_id`),
  KEY `virtuemart_paymentsetting_id_8` (`virtuemart_paymentsetting_id`),
  KEY `virtuemart_paymentsetting_id_9` (`virtuemart_paymentsetting_id`),
  KEY `virtuemart_paymentsetting_id_10` (`virtuemart_paymentsetting_id`),
  KEY `virtuemart_paymentsetting_id_11` (`virtuemart_paymentsetting_id`),
  KEY `virtuemart_paymentsetting_id_12` (`virtuemart_paymentsetting_id`),
  KEY `virtuemart_paymentsetting_id_13` (`virtuemart_paymentsetting_id`),
  KEY `virtuemart_paymentsetting_id_14` (`virtuemart_paymentsetting_id`),
  KEY `virtuemart_paymentsetting_id_15` (`virtuemart_paymentsetting_id`),
  KEY `virtuemart_paymentsetting_id_16` (`virtuemart_paymentsetting_id`),
  KEY `virtuemart_paymentsetting_id_17` (`virtuemart_paymentsetting_id`),
  KEY `virtuemart_paymentsetting_id_18` (`virtuemart_paymentsetting_id`),
  KEY `virtuemart_paymentsetting_id_19` (`virtuemart_paymentsetting_id`),
  KEY `virtuemart_paymentsetting_id_20` (`virtuemart_paymentsetting_id`),
  KEY `virtuemart_paymentsetting_id_21` (`virtuemart_paymentsetting_id`),
  KEY `virtuemart_paymentsetting_id_22` (`virtuemart_paymentsetting_id`),
  KEY `virtuemart_paymentsetting_id_23` (`virtuemart_paymentsetting_id`),
  KEY `virtuemart_paymentsetting_id_24` (`virtuemart_paymentsetting_id`),
  KEY `virtuemart_paymentsetting_id_25` (`virtuemart_paymentsetting_id`),
  KEY `virtuemart_paymentsetting_id_26` (`virtuemart_paymentsetting_id`),
  KEY `virtuemart_paymentsetting_id_27` (`virtuemart_paymentsetting_id`),
  KEY `virtuemart_paymentsetting_id_28` (`virtuemart_paymentsetting_id`),
  KEY `virtuemart_paymentsetting_id_29` (`virtuemart_paymentsetting_id`),
  KEY `virtuemart_paymentsetting_id_30` (`virtuemart_paymentsetting_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='Used to store currencies';

/*Table structure for table `me1u8_virtuemart_paymentsetting_id_paymentmethord_id` */

DROP TABLE IF EXISTS `me1u8_virtuemart_paymentsetting_id_paymentmethord_id`;

CREATE TABLE `me1u8_virtuemart_paymentsetting_id_paymentmethord_id` (
  `id` int(11) DEFAULT NULL,
  `virtuemart_payment_id` int(11) DEFAULT NULL,
  `virtuemart_product_id` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  UNIQUE KEY `virtuemart_product_id` (`virtuemart_product_id`,`virtuemart_payment_id`),
  KEY `ordering` (`ordering`),
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=233 DEFAULT CHARSET=utf8 COMMENT='Maps Products to Categories';

/*Table structure for table `me1u8_virtuemart_physicalgrade` */

DROP TABLE IF EXISTS `me1u8_virtuemart_physicalgrade`;

CREATE TABLE `me1u8_virtuemart_physicalgrade` (
  `virtuemart_physicalgrade_id` int(11) NOT NULL DEFAULT '0',
  `title` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `shared` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`virtuemart_physicalgrade_id`),
  KEY `ordering` (`ordering`),
  KEY `published` (`published`),
  KEY `shared` (`shared`),
  KEY `virtuemart_physicalgrade_id` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_2` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_3` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_4` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_5` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_6` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_7` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_8` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_9` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_10` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_11` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_12` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_13` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_14` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_15` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_16` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_17` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_18` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_19` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_20` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_21` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_22` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_23` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_24` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_25` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_26` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_27` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_28` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_29` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_30` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_31` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_32` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_33` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_34` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_35` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_36` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_37` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_38` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_39` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_40` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_41` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_42` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_43` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_44` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_45` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_46` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_47` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_48` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_49` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_50` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_51` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_52` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_53` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_54` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_55` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_56` (`virtuemart_physicalgrade_id`),
  KEY `virtuemart_physicalgrade_id_57` (`virtuemart_physicalgrade_id`)
) ENGINE=MyISAM AUTO_INCREMENT=203 DEFAULT CHARSET=utf8 COMMENT='Used to store currencies';

/*Table structure for table `me1u8_virtuemart_product_customfields` */

DROP TABLE IF EXISTS `me1u8_virtuemart_product_customfields`;

CREATE TABLE `me1u8_virtuemart_product_customfields` (
  `virtuemart_customfield_id` int(11) NOT NULL DEFAULT '0',
  `virtuemart_product_id` int(11) DEFAULT NULL,
  `virtuemart_custom_id` int(11) DEFAULT NULL,
  `customfield_value` int(11) DEFAULT NULL,
  `customfield_price` int(11) DEFAULT NULL,
  `disabler` int(11) DEFAULT NULL,
  `override` int(11) DEFAULT NULL,
  `customfield_params` int(11) DEFAULT NULL,
  `product_sku` int(11) DEFAULT NULL,
  `product_gtin` int(11) DEFAULT NULL,
  `product_mpn` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  PRIMARY KEY (`virtuemart_customfield_id`),
  KEY `virtuemart_customfield_id` (`virtuemart_customfield_id`),
  KEY `virtuemart_customfield_id_2` (`virtuemart_customfield_id`),
  KEY `virtuemart_customfield_id_3` (`virtuemart_customfield_id`),
  KEY `virtuemart_customfield_id_4` (`virtuemart_customfield_id`),
  KEY `virtuemart_customfield_id_5` (`virtuemart_customfield_id`),
  KEY `virtuemart_customfield_id_6` (`virtuemart_customfield_id`),
  KEY `virtuemart_customfield_id_7` (`virtuemart_customfield_id`),
  KEY `virtuemart_customfield_id_8` (`virtuemart_customfield_id`),
  KEY `virtuemart_customfield_id_9` (`virtuemart_customfield_id`),
  KEY `virtuemart_customfield_id_10` (`virtuemart_customfield_id`),
  KEY `virtuemart_customfield_id_11` (`virtuemart_customfield_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `me1u8_virtuemart_product_medias` */

DROP TABLE IF EXISTS `me1u8_virtuemart_product_medias`;

CREATE TABLE `me1u8_virtuemart_product_medias` (
  `id` int(11) DEFAULT NULL,
  `virtuemart_product_id` int(11) DEFAULT NULL,
  `virtuemart_media_id` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  UNIQUE KEY `virtuemart_product_id` (`virtuemart_product_id`,`virtuemart_media_id`),
  KEY `ordering` (`ordering`),
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_virtuemart_products` */

DROP TABLE IF EXISTS `me1u8_virtuemart_products`;

CREATE TABLE `me1u8_virtuemart_products` (
  `virtuemart_product_id` int(11) NOT NULL AUTO_INCREMENT,
  `virtuemart_vendor_id` int(11) DEFAULT NULL,
  `product_parent_id` int(11) DEFAULT NULL,
  `virtuemart_tour_type_id` int(11) DEFAULT NULL,
  `virtuemart_tour_style_id` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `virtuemart_physicalgrade_id` int(11) DEFAULT NULL,
  `virtuemart_tour_section_id` int(11) DEFAULT NULL,
  `product_sku` int(11) DEFAULT NULL,
  `tour_methor` int(11) DEFAULT NULL,
  `tour_length` int(11) DEFAULT NULL,
  `min_person` int(11) DEFAULT NULL,
  `max_person` int(11) DEFAULT NULL,
  `min_age` int(11) DEFAULT NULL,
  `max_age` int(11) DEFAULT NULL,
  `start_city` int(11) DEFAULT NULL,
  `end_city` int(11) DEFAULT NULL,
  `highlights` int(11) DEFAULT NULL,
  `inclusions` int(11) DEFAULT NULL,
  `exclusions` int(11) DEFAULT NULL,
  `meta_name` int(11) DEFAULT NULL,
  `product_special` int(11) DEFAULT NULL,
  `product_params` int(11) DEFAULT NULL,
  `hits` int(11) DEFAULT NULL,
  `intnotes` int(11) DEFAULT NULL,
  `metaauthor` int(11) DEFAULT NULL,
  `layout` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `pordering` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`virtuemart_product_id`),
  KEY `virtuemart_vendor_id` (`virtuemart_vendor_id`),
  KEY `product_parent_id` (`product_parent_id`),
  KEY `product_special` (`product_special`),
  KEY `published` (`published`),
  KEY `pordering` (`pordering`),
  KEY `created_on` (`created_on`),
  KEY `modified_on` (`modified_on`),
  KEY `virtuemart_product_id_2` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_3` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_4` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_5` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_6` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_7` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_8` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_9` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_10` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_11` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_12` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_13` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_14` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_15` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_16` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_17` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_18` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_19` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_20` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_21` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_22` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_23` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_24` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_25` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_26` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_27` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_28` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_29` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_30` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_32` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_33` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_34` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_35` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_36` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_37` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_38` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_39` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_40` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_41` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_42` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_43` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_44` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_45` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_46` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_47` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_48` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_49` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_50` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_51` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_52` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_53` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_54` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_55` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_56` (`virtuemart_product_id`),
  KEY `virtuemart_product_id` (`virtuemart_product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1222 DEFAULT CHARSET=utf8 COMMENT='All products are stored here.';

/*Table structure for table `me1u8_virtuemart_products_en_gb` */

DROP TABLE IF EXISTS `me1u8_virtuemart_products_en_gb`;

CREATE TABLE `me1u8_virtuemart_products_en_gb` (
  `virtuemart_product_id` int(11) NOT NULL,
  `product_name` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `product_s_desc` tinytext,
  `product_desc` text,
  `metadesc` tinytext,
  `metakey` tinytext,
  `customtitle` tinytext,
  `slug` tinytext,
  `version` tinytext,
  `compatibility` tinytext,
  `params` text,
  PRIMARY KEY (`virtuemart_product_id`),
  KEY `virtuemart_product_id` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_2` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_3` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_4` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_5` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_6` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_7` (`virtuemart_product_id`),
  KEY `virtuemart_product_id_8` (`virtuemart_product_id`),
  CONSTRAINT `me1u8_virtuemart_products_en_gb_ibfk_1` FOREIGN KEY (`virtuemart_product_id`) REFERENCES `me1u8_virtuemart_products` (`virtuemart_product_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `me1u8_virtuemart_rating_reviews` */

DROP TABLE IF EXISTS `me1u8_virtuemart_rating_reviews`;

CREATE TABLE `me1u8_virtuemart_rating_reviews` (
  `virtuemart_rating_review_id` int(11) NOT NULL DEFAULT '0',
  `virtuemart_product_id` int(11) DEFAULT NULL,
  `comment` int(11) DEFAULT NULL,
  `review_ok` int(11) DEFAULT NULL,
  `review_rates` int(11) DEFAULT NULL,
  `review_ratingcount` int(11) DEFAULT NULL,
  `review_rating` int(11) DEFAULT NULL,
  `review_editable` int(11) DEFAULT NULL,
  `lastip` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`virtuemart_rating_review_id`),
  KEY `virtuemart_rating_review_id` (`virtuemart_rating_review_id`),
  KEY `virtuemart_rating_review_id_2` (`virtuemart_rating_review_id`),
  KEY `virtuemart_rating_review_id_3` (`virtuemart_rating_review_id`),
  KEY `virtuemart_rating_review_id_4` (`virtuemart_rating_review_id`),
  KEY `virtuemart_rating_review_id_5` (`virtuemart_rating_review_id`),
  KEY `virtuemart_rating_review_id_6` (`virtuemart_rating_review_id`),
  KEY `virtuemart_rating_review_id_7` (`virtuemart_rating_review_id`),
  KEY `virtuemart_rating_review_id_8` (`virtuemart_rating_review_id`),
  KEY `virtuemart_rating_review_id_9` (`virtuemart_rating_review_id`),
  KEY `virtuemart_rating_review_id_10` (`virtuemart_rating_review_id`),
  KEY `virtuemart_rating_review_id_11` (`virtuemart_rating_review_id`),
  KEY `virtuemart_rating_review_id_12` (`virtuemart_rating_review_id`),
  KEY `virtuemart_rating_review_id_13` (`virtuemart_rating_review_id`),
  KEY `virtuemart_rating_review_id_14` (`virtuemart_rating_review_id`),
  KEY `virtuemart_rating_review_id_15` (`virtuemart_rating_review_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `me1u8_virtuemart_ratings` */

DROP TABLE IF EXISTS `me1u8_virtuemart_ratings`;

CREATE TABLE `me1u8_virtuemart_ratings` (
  `virtuemart_rating_id` int(11) NOT NULL DEFAULT '0',
  `virtuemart_product_id` int(11) DEFAULT NULL,
  `rates` int(11) DEFAULT NULL,
  `ratingcount` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`virtuemart_rating_id`),
  KEY `virtuemart_rating_id` (`virtuemart_rating_id`),
  KEY `virtuemart_rating_id_2` (`virtuemart_rating_id`),
  KEY `virtuemart_rating_id_3` (`virtuemart_rating_id`),
  KEY `virtuemart_rating_id_4` (`virtuemart_rating_id`),
  KEY `virtuemart_rating_id_5` (`virtuemart_rating_id`),
  KEY `virtuemart_rating_id_6` (`virtuemart_rating_id`),
  KEY `virtuemart_rating_id_7` (`virtuemart_rating_id`),
  KEY `virtuemart_rating_id_8` (`virtuemart_rating_id`),
  KEY `virtuemart_rating_id_9` (`virtuemart_rating_id`),
  KEY `virtuemart_rating_id_10` (`virtuemart_rating_id`),
  KEY `virtuemart_rating_id_11` (`virtuemart_rating_id`),
  KEY `virtuemart_rating_id_12` (`virtuemart_rating_id`),
  KEY `virtuemart_rating_id_13` (`virtuemart_rating_id`),
  KEY `virtuemart_rating_id_14` (`virtuemart_rating_id`),
  KEY `virtuemart_rating_id_15` (`virtuemart_rating_id`),
  KEY `virtuemart_rating_id_16` (`virtuemart_rating_id`),
  KEY `virtuemart_rating_id_17` (`virtuemart_rating_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `me1u8_virtuemart_room` */

DROP TABLE IF EXISTS `me1u8_virtuemart_room`;

CREATE TABLE `me1u8_virtuemart_room` (
  `virtuemart_room_id` int(11) NOT NULL DEFAULT '0',
  `room_name` int(11) DEFAULT NULL,
  `icon` int(11) DEFAULT NULL,
  `image1` int(11) DEFAULT NULL,
  `image2` int(11) DEFAULT NULL,
  `virtuemart_hotel_id` int(11) DEFAULT NULL,
  `facilities` int(11) DEFAULT NULL,
  `key_word` int(11) DEFAULT NULL,
  `description` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `shared` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`virtuemart_room_id`),
  KEY `ordering` (`ordering`),
  KEY `published` (`published`),
  KEY `shared` (`shared`),
  KEY `virtuemart_room_id` (`virtuemart_room_id`),
  KEY `virtuemart_room_id_2` (`virtuemart_room_id`),
  KEY `virtuemart_room_id_3` (`virtuemart_room_id`),
  KEY `virtuemart_room_id_4` (`virtuemart_room_id`),
  KEY `virtuemart_room_id_5` (`virtuemart_room_id`),
  KEY `virtuemart_room_id_6` (`virtuemart_room_id`),
  KEY `virtuemart_room_id_7` (`virtuemart_room_id`),
  KEY `virtuemart_room_id_8` (`virtuemart_room_id`),
  KEY `virtuemart_room_id_9` (`virtuemart_room_id`),
  KEY `virtuemart_room_id_10` (`virtuemart_room_id`),
  KEY `virtuemart_room_id_11` (`virtuemart_room_id`),
  KEY `virtuemart_room_id_12` (`virtuemart_room_id`),
  KEY `virtuemart_room_id_13` (`virtuemart_room_id`),
  KEY `virtuemart_room_id_14` (`virtuemart_room_id`),
  KEY `virtuemart_room_id_15` (`virtuemart_room_id`),
  KEY `virtuemart_room_id_16` (`virtuemart_room_id`),
  KEY `virtuemart_room_id_17` (`virtuemart_room_id`),
  KEY `virtuemart_room_id_18` (`virtuemart_room_id`),
  KEY `virtuemart_room_id_19` (`virtuemart_room_id`),
  KEY `virtuemart_room_id_20` (`virtuemart_room_id`),
  KEY `virtuemart_room_id_21` (`virtuemart_room_id`),
  KEY `virtuemart_room_id_22` (`virtuemart_room_id`),
  KEY `virtuemart_room_id_23` (`virtuemart_room_id`),
  KEY `virtuemart_room_id_24` (`virtuemart_room_id`),
  KEY `virtuemart_room_id_25` (`virtuemart_room_id`),
  KEY `virtuemart_room_id_26` (`virtuemart_room_id`),
  KEY `virtuemart_room_id_27` (`virtuemart_room_id`),
  KEY `virtuemart_room_id_28` (`virtuemart_room_id`),
  KEY `virtuemart_room_id_29` (`virtuemart_room_id`),
  KEY `virtuemart_room_id_30` (`virtuemart_room_id`)
) ENGINE=MyISAM AUTO_INCREMENT=204 DEFAULT CHARSET=utf8 COMMENT='Used to store currencies';

/*Table structure for table `me1u8_virtuemart_service_class` */

DROP TABLE IF EXISTS `me1u8_virtuemart_service_class`;

CREATE TABLE `me1u8_virtuemart_service_class` (
  `virtuemart_service_class_id` int(11) NOT NULL DEFAULT '0',
  `service_class_name` int(11) DEFAULT NULL,
  `icon` int(11) DEFAULT NULL,
  `meta_title` int(11) DEFAULT NULL,
  `key_word` int(11) DEFAULT NULL,
  `description` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `shared` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`virtuemart_service_class_id`),
  KEY `virtuemart_service_class_id` (`virtuemart_service_class_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_virtuemart_service_type` */

DROP TABLE IF EXISTS `me1u8_virtuemart_service_type`;

CREATE TABLE `me1u8_virtuemart_service_type` (
  `virtuemart_service_type_id` int(11) NOT NULL DEFAULT '0',
  `title` int(11) DEFAULT NULL,
  `icon` int(11) DEFAULT NULL,
  `meta_title` int(11) DEFAULT NULL,
  `key_word` int(11) DEFAULT NULL,
  `description` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `shared` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`virtuemart_service_type_id`),
  KEY `virtuemart_service_type_id` (`virtuemart_service_type_id`),
  KEY `virtuemart_service_type_id_2` (`virtuemart_service_type_id`),
  KEY `virtuemart_service_type_id_3` (`virtuemart_service_type_id`),
  KEY `virtuemart_service_type_id_4` (`virtuemart_service_type_id`),
  KEY `virtuemart_service_type_id_5` (`virtuemart_service_type_id`),
  KEY `virtuemart_service_type_id_6` (`virtuemart_service_type_id`),
  KEY `virtuemart_service_type_id_7` (`virtuemart_service_type_id`),
  KEY `virtuemart_service_type_id_8` (`virtuemart_service_type_id`),
  KEY `virtuemart_service_type_id_9` (`virtuemart_service_type_id`),
  KEY `virtuemart_service_type_id_10` (`virtuemart_service_type_id`),
  KEY `virtuemart_service_type_id_11` (`virtuemart_service_type_id`),
  KEY `virtuemart_service_type_id_12` (`virtuemart_service_type_id`),
  KEY `virtuemart_service_type_id_13` (`virtuemart_service_type_id`),
  KEY `virtuemart_service_type_id_14` (`virtuemart_service_type_id`),
  KEY `virtuemart_service_type_id_15` (`virtuemart_service_type_id`),
  KEY `virtuemart_service_type_id_16` (`virtuemart_service_type_id`),
  KEY `virtuemart_service_type_id_17` (`virtuemart_service_type_id`),
  KEY `virtuemart_service_type_id_18` (`virtuemart_service_type_id`),
  KEY `virtuemart_service_type_id_19` (`virtuemart_service_type_id`),
  KEY `virtuemart_service_type_id_20` (`virtuemart_service_type_id`),
  KEY `virtuemart_service_type_id_21` (`virtuemart_service_type_id`),
  KEY `virtuemart_service_type_id_22` (`virtuemart_service_type_id`),
  KEY `virtuemart_service_type_id_23` (`virtuemart_service_type_id`),
  KEY `virtuemart_service_type_id_24` (`virtuemart_service_type_id`),
  KEY `virtuemart_service_type_id_25` (`virtuemart_service_type_id`),
  KEY `virtuemart_service_type_id_26` (`virtuemart_service_type_id`),
  KEY `virtuemart_service_type_id_27` (`virtuemart_service_type_id`),
  KEY `virtuemart_service_type_id_28` (`virtuemart_service_type_id`),
  KEY `virtuemart_service_type_id_29` (`virtuemart_service_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `me1u8_virtuemart_shoppergroups` */

DROP TABLE IF EXISTS `me1u8_virtuemart_shoppergroups`;

CREATE TABLE `me1u8_virtuemart_shoppergroups` (
  `virtuemart_shoppergroup_id` int(11) DEFAULT NULL,
  `virtuemart_vendor_id` int(11) DEFAULT NULL,
  `shopper_group_name` int(11) DEFAULT NULL,
  `shopper_group_desc` int(11) DEFAULT NULL,
  `custom_price_display` int(11) DEFAULT NULL,
  `price_display` int(11) DEFAULT NULL,
  `default` int(11) DEFAULT NULL,
  `sgrp_additional` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `shared` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  KEY `virtuemart_shoppergroup_id` (`virtuemart_shoppergroup_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `me1u8_virtuemart_states` */

DROP TABLE IF EXISTS `me1u8_virtuemart_states`;

CREATE TABLE `me1u8_virtuemart_states` (
  `virtuemart_state_id` int(11) NOT NULL AUTO_INCREMENT,
  `virtuemart_country_id` int(11) DEFAULT NULL,
  `state_name` int(11) DEFAULT NULL,
  `state_3_code` char(3) DEFAULT NULL,
  `state_2_code` char(2) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `shared` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`virtuemart_state_id`),
  UNIQUE KEY `state_3_code` (`virtuemart_country_id`,`state_3_code`),
  UNIQUE KEY `state_2_code` (`virtuemart_country_id`,`state_2_code`),
  KEY `virtuemart_country_id` (`virtuemart_country_id`),
  KEY `ordering` (`ordering`),
  KEY `shared` (`shared`),
  KEY `published` (`published`),
  KEY `virtuemart_state_id` (`virtuemart_state_id`),
  KEY `virtuemart_state_id_2` (`virtuemart_state_id`),
  KEY `virtuemart_state_id_3` (`virtuemart_state_id`),
  KEY `virtuemart_state_id_4` (`virtuemart_state_id`),
  KEY `virtuemart_state_id_5` (`virtuemart_state_id`),
  KEY `virtuemart_state_id_6` (`virtuemart_state_id`),
  KEY `virtuemart_state_id_7` (`virtuemart_state_id`),
  KEY `virtuemart_state_id_8` (`virtuemart_state_id`),
  KEY `virtuemart_state_id_9` (`virtuemart_state_id`),
  KEY `virtuemart_state_id_10` (`virtuemart_state_id`),
  KEY `virtuemart_state_id_11` (`virtuemart_state_id`),
  KEY `virtuemart_state_id_12` (`virtuemart_state_id`),
  KEY `virtuemart_state_id_13` (`virtuemart_state_id`),
  KEY `virtuemart_state_id_14` (`virtuemart_state_id`),
  KEY `virtuemart_state_id_15` (`virtuemart_state_id`),
  KEY `virtuemart_state_id_16` (`virtuemart_state_id`),
  KEY `virtuemart_state_id_17` (`virtuemart_state_id`),
  KEY `virtuemart_state_id_18` (`virtuemart_state_id`),
  KEY `virtuemart_state_id_19` (`virtuemart_state_id`),
  KEY `virtuemart_state_id_20` (`virtuemart_state_id`),
  KEY `virtuemart_state_id_21` (`virtuemart_state_id`),
  KEY `virtuemart_state_id_22` (`virtuemart_state_id`),
  KEY `virtuemart_state_id_23` (`virtuemart_state_id`),
  KEY `virtuemart_state_id_24` (`virtuemart_state_id`),
  KEY `virtuemart_state_id_25` (`virtuemart_state_id`),
  KEY `virtuemart_state_id_26` (`virtuemart_state_id`),
  KEY `virtuemart_state_id_27` (`virtuemart_state_id`),
  KEY `virtuemart_state_id_28` (`virtuemart_state_id`),
  KEY `virtuemart_state_id_29` (`virtuemart_state_id`),
  KEY `virtuemart_state_id_30` (`virtuemart_state_id`),
  CONSTRAINT `me1u8_virtuemart_states_ibfk_1` FOREIGN KEY (`virtuemart_country_id`) REFERENCES `etravelservice_countries` (`virtuemart_country_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='States that are assigned to a country';

/*Table structure for table `me1u8_virtuemart_supplier` */

DROP TABLE IF EXISTS `me1u8_virtuemart_supplier`;

CREATE TABLE `me1u8_virtuemart_supplier` (
  `virtuemart_supplier_id` int(11) NOT NULL DEFAULT '0',
  `virtuemart_cityarea_id` int(11) DEFAULT NULL,
  `supplier_name` int(11) DEFAULT NULL,
  `vat_detail` int(11) DEFAULT NULL,
  `language` int(11) DEFAULT NULL,
  `currency_id` int(11) DEFAULT NULL,
  `supplier_type` int(11) DEFAULT NULL,
  `virtuemart_service_type_id` int(11) DEFAULT NULL,
  `bank_name` int(11) DEFAULT NULL,
  `bank_account` int(11) DEFAULT NULL,
  `swift_code` int(11) DEFAULT NULL,
  `supplier_logo` int(11) DEFAULT NULL,
  `address` int(11) DEFAULT NULL,
  `website` int(11) DEFAULT NULL,
  `telephone` int(11) DEFAULT NULL,
  `mobile_phone` int(11) DEFAULT NULL,
  `fax_number` int(11) DEFAULT NULL,
  `email_address` int(11) DEFAULT NULL,
  `contact_person` int(11) DEFAULT NULL,
  `contact_mobile` int(11) DEFAULT NULL,
  `additional_info` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `shared` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`virtuemart_supplier_id`),
  KEY `ordering` (`ordering`),
  KEY `published` (`published`),
  KEY `shared` (`shared`),
  KEY `virtuemart_supplier_id` (`virtuemart_supplier_id`),
  KEY `virtuemart_supplier_id_2` (`virtuemart_supplier_id`),
  KEY `virtuemart_supplier_id_3` (`virtuemart_supplier_id`),
  KEY `virtuemart_supplier_id_4` (`virtuemart_supplier_id`),
  KEY `virtuemart_supplier_id_5` (`virtuemart_supplier_id`),
  KEY `virtuemart_supplier_id_6` (`virtuemart_supplier_id`),
  KEY `virtuemart_supplier_id_7` (`virtuemart_supplier_id`),
  KEY `virtuemart_supplier_id_8` (`virtuemart_supplier_id`),
  KEY `virtuemart_supplier_id_9` (`virtuemart_supplier_id`),
  KEY `virtuemart_supplier_id_10` (`virtuemart_supplier_id`),
  KEY `virtuemart_supplier_id_11` (`virtuemart_supplier_id`),
  KEY `virtuemart_supplier_id_12` (`virtuemart_supplier_id`),
  KEY `virtuemart_supplier_id_13` (`virtuemart_supplier_id`),
  KEY `virtuemart_supplier_id_14` (`virtuemart_supplier_id`),
  KEY `virtuemart_supplier_id_15` (`virtuemart_supplier_id`),
  KEY `virtuemart_supplier_id_16` (`virtuemart_supplier_id`),
  KEY `virtuemart_supplier_id_17` (`virtuemart_supplier_id`),
  KEY `virtuemart_supplier_id_18` (`virtuemart_supplier_id`),
  KEY `virtuemart_supplier_id_19` (`virtuemart_supplier_id`),
  KEY `virtuemart_supplier_id_20` (`virtuemart_supplier_id`),
  KEY `virtuemart_supplier_id_21` (`virtuemart_supplier_id`),
  KEY `virtuemart_supplier_id_22` (`virtuemart_supplier_id`),
  KEY `virtuemart_supplier_id_23` (`virtuemart_supplier_id`),
  KEY `virtuemart_supplier_id_24` (`virtuemart_supplier_id`),
  KEY `virtuemart_supplier_id_25` (`virtuemart_supplier_id`),
  KEY `virtuemart_supplier_id_26` (`virtuemart_supplier_id`),
  KEY `virtuemart_supplier_id_27` (`virtuemart_supplier_id`),
  KEY `virtuemart_supplier_id_28` (`virtuemart_supplier_id`),
  KEY `virtuemart_supplier_id_29` (`virtuemart_supplier_id`),
  KEY `virtuemart_supplier_id_30` (`virtuemart_supplier_id`)
) ENGINE=MyISAM AUTO_INCREMENT=205 DEFAULT CHARSET=utf8 COMMENT='Used to store currencies';

/*Table structure for table `me1u8_virtuemart_tour_id_activity_id` */

DROP TABLE IF EXISTS `me1u8_virtuemart_tour_id_activity_id`;

CREATE TABLE `me1u8_virtuemart_tour_id_activity_id` (
  `virtuemart_product_id` int(11) NOT NULL,
  `virtuemart_activity_id` int(11) DEFAULT NULL,
  UNIQUE KEY `virtuemart_product_id` (`virtuemart_product_id`,`virtuemart_activity_id`),
  CONSTRAINT `me1u8_virtuemart_tour_id_activity_id_ibfk_1` FOREIGN KEY (`virtuemart_product_id`) REFERENCES `me1u8_virtuemart_products` (`virtuemart_product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Maps Products to Categories';

/*Table structure for table `me1u8_virtuemart_tour_id_country_id` */

DROP TABLE IF EXISTS `me1u8_virtuemart_tour_id_country_id`;

CREATE TABLE `me1u8_virtuemart_tour_id_country_id` (
  `id` int(11) NOT NULL DEFAULT '0',
  `virtuemart_country_id` int(12) DEFAULT NULL,
  `virtuemart_product_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `virtuemart_product_id` (`virtuemart_product_id`,`virtuemart_country_id`),
  KEY `id` (`id`),
  KEY `id_2` (`id`),
  KEY `id_3` (`id`),
  KEY `id_4` (`id`),
  KEY `id_5` (`id`),
  KEY `id_6` (`id`),
  KEY `id_7` (`id`),
  KEY `id_8` (`id`),
  KEY `id_9` (`id`),
  KEY `id_10` (`id`),
  KEY `id_11` (`id`),
  KEY `id_12` (`id`),
  KEY `id_13` (`id`),
  KEY `id_14` (`id`),
  KEY `id_15` (`id`),
  KEY `id_16` (`id`),
  KEY `id_17` (`id`),
  KEY `id_18` (`id`),
  KEY `id_19` (`id`),
  KEY `id_20` (`id`),
  KEY `id_21` (`id`),
  KEY `id_22` (`id`),
  KEY `id_23` (`id`),
  KEY `id_24` (`id`),
  KEY `id_25` (`id`),
  KEY `id_26` (`id`),
  KEY `id_27` (`id`),
  KEY `id_28` (`id`),
  KEY `id_29` (`id`),
  KEY `id_30` (`id`),
  KEY `id_31` (`id`),
  KEY `id_32` (`id`),
  KEY `id_33` (`id`),
  KEY `id_34` (`id`),
  KEY `id_35` (`id`),
  KEY `id_36` (`id`),
  KEY `id_37` (`id`),
  KEY `id_38` (`id`),
  KEY `id_39` (`id`),
  KEY `id_40` (`id`),
  KEY `id_41` (`id`),
  KEY `id_42` (`id`),
  KEY `id_43` (`id`),
  KEY `id_44` (`id`),
  KEY `id_45` (`id`),
  KEY `id_46` (`id`),
  KEY `id_47` (`id`),
  KEY `id_48` (`id`),
  KEY `id_49` (`id`),
  KEY `id_50` (`id`),
  KEY `id_51` (`id`),
  KEY `id_52` (`id`),
  KEY `id_53` (`id`),
  KEY `id_54` (`id`),
  KEY `id_55` (`id`),
  KEY `id_56` (`id`),
  KEY `id_57` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=223 DEFAULT CHARSET=utf8 COMMENT='Maps Products to Categories';

/*Table structure for table `me1u8_virtuemart_tour_id_excursion_addon_id` */

DROP TABLE IF EXISTS `me1u8_virtuemart_tour_id_excursion_addon_id`;

CREATE TABLE `me1u8_virtuemart_tour_id_excursion_addon_id` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `virtuemart_excursion_addon_id` int(11) NOT NULL,
  `virtuemart_product_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `virtuemart_product_id` (`virtuemart_product_id`,`virtuemart_excursion_addon_id`),
  KEY `virtuemart_excusion_addon_id` (`virtuemart_excursion_addon_id`),
  CONSTRAINT `me1u8_virtuemart_tour_id_excursion_addon_id_ibfk_1` FOREIGN KEY (`virtuemart_product_id`) REFERENCES `me1u8_virtuemart_products` (`virtuemart_product_id`),
  CONSTRAINT `me1u8_virtuemart_tour_id_excursion_addon_id_ibfk_2` FOREIGN KEY (`virtuemart_excursion_addon_id`) REFERENCES `me1u8_virtuemart_excursion_addon` (`virtuemart_excursion_addon_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='Maps Products to Categories';

/*Table structure for table `me1u8_virtuemart_tour_id_group_size_id` */

DROP TABLE IF EXISTS `me1u8_virtuemart_tour_id_group_size_id`;

CREATE TABLE `me1u8_virtuemart_tour_id_group_size_id` (
  `id` int(11) NOT NULL DEFAULT '0',
  `virtuemart_group_size_id` int(11) DEFAULT NULL,
  `virtuemart_product_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `virtuemart_product_id` (`virtuemart_product_id`,`virtuemart_group_size_id`),
  KEY `id` (`id`),
  KEY `id_2` (`id`),
  KEY `id_3` (`id`),
  KEY `id_4` (`id`),
  KEY `id_5` (`id`),
  KEY `id_6` (`id`),
  KEY `id_7` (`id`),
  KEY `id_8` (`id`),
  KEY `id_9` (`id`),
  KEY `id_10` (`id`),
  KEY `id_11` (`id`),
  KEY `id_12` (`id`),
  KEY `id_13` (`id`),
  KEY `id_14` (`id`),
  KEY `id_15` (`id`),
  KEY `id_16` (`id`),
  KEY `id_17` (`id`),
  KEY `id_18` (`id`),
  KEY `id_19` (`id`),
  KEY `id_20` (`id`),
  KEY `id_21` (`id`),
  KEY `id_22` (`id`),
  KEY `id_23` (`id`),
  KEY `id_24` (`id`),
  KEY `id_25` (`id`),
  KEY `id_26` (`id`),
  KEY `id_27` (`id`),
  KEY `id_28` (`id`),
  KEY `id_29` (`id`),
  KEY `id_30` (`id`),
  KEY `id_31` (`id`),
  KEY `id_32` (`id`),
  KEY `id_33` (`id`),
  KEY `id_34` (`id`),
  KEY `id_35` (`id`),
  KEY `id_36` (`id`),
  KEY `id_37` (`id`),
  KEY `id_38` (`id`),
  KEY `id_39` (`id`),
  KEY `id_40` (`id`),
  KEY `id_41` (`id`),
  KEY `id_42` (`id`),
  KEY `id_43` (`id`),
  KEY `id_44` (`id`),
  KEY `id_45` (`id`),
  KEY `id_46` (`id`),
  KEY `id_47` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8 COMMENT='Maps Products to Categories';

/*Table structure for table `me1u8_virtuemart_tour_id_hotel_addon_id` */

DROP TABLE IF EXISTS `me1u8_virtuemart_tour_id_hotel_addon_id`;

CREATE TABLE `me1u8_virtuemart_tour_id_hotel_addon_id` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `virtuemart_hotel_addon_id` int(11) DEFAULT NULL,
  `virtuemart_product_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `id_2` (`id`),
  KEY `virtuemart_transfer_addon_id` (`virtuemart_hotel_addon_id`),
  KEY `virtuemart_product_id` (`virtuemart_product_id`),
  CONSTRAINT `me1u8_virtuemart_tour_id_hotel_addon_id_ibfk_1` FOREIGN KEY (`virtuemart_product_id`) REFERENCES `me1u8_virtuemart_products` (`virtuemart_product_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `me1u8_virtuemart_tour_id_hotel_addon_id_ibfk_2` FOREIGN KEY (`virtuemart_hotel_addon_id`) REFERENCES `me1u8_virtuemart_hotel_addon` (`virtuemart_hotel_addon_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

/*Table structure for table `me1u8_virtuemart_tour_id_payment_id` */

DROP TABLE IF EXISTS `me1u8_virtuemart_tour_id_payment_id`;

CREATE TABLE `me1u8_virtuemart_tour_id_payment_id` (
  `id` int(11) NOT NULL DEFAULT '0',
  `virtuemart_product_id` int(11) DEFAULT NULL,
  `virtuemart_payment_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `virtuemart_product_id` (`virtuemart_product_id`),
  KEY `id` (`id`),
  KEY `id_2` (`id`),
  KEY `id_3` (`id`),
  KEY `id_4` (`id`),
  KEY `id_5` (`id`),
  KEY `id_6` (`id`),
  KEY `id_7` (`id`),
  KEY `id_8` (`id`),
  KEY `id_9` (`id`),
  KEY `id_10` (`id`),
  KEY `id_11` (`id`),
  KEY `id_12` (`id`),
  KEY `id_13` (`id`),
  KEY `id_14` (`id`),
  KEY `id_15` (`id`),
  KEY `id_16` (`id`),
  KEY `id_17` (`id`),
  KEY `id_18` (`id`),
  KEY `id_19` (`id`),
  KEY `id_20` (`id`),
  KEY `id_21` (`id`),
  KEY `id_22` (`id`),
  KEY `id_23` (`id`),
  KEY `id_24` (`id`),
  KEY `id_25` (`id`),
  KEY `id_26` (`id`),
  KEY `id_27` (`id`),
  KEY `id_28` (`id`),
  KEY `id_29` (`id`),
  KEY `id_30` (`id`),
  KEY `id_31` (`id`),
  KEY `id_32` (`id`),
  KEY `id_33` (`id`),
  KEY `id_34` (`id`),
  KEY `id_35` (`id`),
  KEY `id_36` (`id`),
  KEY `id_37` (`id`),
  KEY `id_38` (`id`),
  KEY `id_39` (`id`),
  KEY `id_40` (`id`),
  KEY `id_41` (`id`),
  KEY `id_42` (`id`),
  KEY `id_43` (`id`),
  KEY `id_44` (`id`),
  KEY `id_45` (`id`),
  KEY `id_46` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Maps Products to Categories';

/*Table structure for table `me1u8_virtuemart_tour_id_service_class_id` */

DROP TABLE IF EXISTS `me1u8_virtuemart_tour_id_service_class_id`;

CREATE TABLE `me1u8_virtuemart_tour_id_service_class_id` (
  `id` int(11) DEFAULT NULL,
  `virtuemart_product_id` int(11) DEFAULT NULL,
  `virtuemart_service_class_id` int(11) DEFAULT NULL,
  UNIQUE KEY `virtuemart_product_id` (`virtuemart_product_id`,`virtuemart_service_class_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Maps Products to Categories';

/*Table structure for table `me1u8_virtuemart_tour_id_transfer_addon_id` */

DROP TABLE IF EXISTS `me1u8_virtuemart_tour_id_transfer_addon_id`;

CREATE TABLE `me1u8_virtuemart_tour_id_transfer_addon_id` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `virtuemart_transfer_addon_id` int(11) DEFAULT NULL,
  `virtuemart_product_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `id_2` (`id`),
  KEY `virtuemart_transfer_addon_id` (`virtuemart_transfer_addon_id`),
  KEY `virtuemart_product_id` (`virtuemart_product_id`),
  CONSTRAINT `me1u8_virtuemart_tour_id_transfer_addon_id_ibfk_1` FOREIGN KEY (`virtuemart_transfer_addon_id`) REFERENCES `me1u8_virtuemart_transfer_addon` (`virtuemart_transfer_addon_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `me1u8_virtuemart_tour_id_transfer_addon_id_ibfk_2` FOREIGN KEY (`virtuemart_product_id`) REFERENCES `me1u8_virtuemart_products` (`virtuemart_product_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

/*Table structure for table `me1u8_virtuemart_tour_price` */

DROP TABLE IF EXISTS `me1u8_virtuemart_tour_price`;

CREATE TABLE `me1u8_virtuemart_tour_price` (
  `virtuemart_price_id` int(11) NOT NULL DEFAULT '0',
  `sale_period_from` date DEFAULT NULL,
  `sale_period_to` date DEFAULT NULL,
  `virtuemart_product_id` int(11) DEFAULT NULL,
  `virtuemart_service_class_id` int(11) DEFAULT NULL,
  `virtuemart_group_size_id` int(11) DEFAULT NULL,
  `senior_price` float DEFAULT NULL,
  `senior_mark_up_amout` float DEFAULT NULL,
  `senior_mark_up_percent` float DEFAULT NULL,
  `adult_price` float DEFAULT NULL,
  `adult_mark_up_amout` float DEFAULT NULL,
  `adult_mark_up_percent` float DEFAULT NULL,
  `teen_price` float DEFAULT NULL,
  `teen_mark_up_amout` float DEFAULT NULL,
  `teen_mark_up_percent` float DEFAULT NULL,
  `children1_price` float DEFAULT NULL,
  `childrent1_mark_up_amout` float DEFAULT NULL,
  `childrent1_mark_up_percent` float DEFAULT NULL,
  `children2_price` float DEFAULT NULL,
  `childrent2_mark_up_amout` float DEFAULT NULL,
  `childrent2_mark_up_percent` float DEFAULT NULL,
  `infant_price` float DEFAULT NULL,
  `infant_mark_up_amout` float DEFAULT NULL,
  `infant_mark_up_percent` float DEFAULT NULL,
  `private_room_price` float DEFAULT NULL,
  `private_room_mark_up_amout` float DEFAULT NULL,
  `private_room_mark_up_percent` float DEFAULT NULL,
  `tax` float DEFAULT NULL,
  `price_note` text CHARACTER SET latin1,
  `shared` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  `modified_on` datetime DEFAULT NULL,
  `locked_on` datetime DEFAULT NULL,
  PRIMARY KEY (`virtuemart_price_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_virtuemart_tour_price_id_group_size_id` */

DROP TABLE IF EXISTS `me1u8_virtuemart_tour_price_id_group_size_id`;

CREATE TABLE `me1u8_virtuemart_tour_price_id_group_size_id` (
  `id` int(11) NOT NULL DEFAULT '0',
  `virtuemart_price_id` int(11) DEFAULT NULL,
  `virtuemart_group_size_id` int(11) DEFAULT NULL,
  `senior_price` int(11) DEFAULT NULL,
  `senior_mark_up_amout` int(11) DEFAULT NULL,
  `senior_mark_up_percent` int(11) DEFAULT NULL,
  `adult_price` int(11) DEFAULT NULL,
  `adult_mark_up_amout` int(11) DEFAULT NULL,
  `adult_mark_up_percent` int(11) DEFAULT NULL,
  `teen_price` int(11) DEFAULT NULL,
  `teen_mark_up_amout` int(11) DEFAULT NULL,
  `teen_mark_up_percent` int(11) DEFAULT NULL,
  `children1_price` int(11) DEFAULT NULL,
  `childrent1_mark_up_amout` int(11) DEFAULT NULL,
  `childrent1_mark_up_percent` int(11) DEFAULT NULL,
  `children2_price` int(11) DEFAULT NULL,
  `childrent2_mark_up_amout` int(11) DEFAULT NULL,
  `childrent2_mark_up_percent` int(11) DEFAULT NULL,
  `infant_price` int(11) DEFAULT NULL,
  `infant_mark_up_amout` int(11) DEFAULT NULL,
  `infant_mark_up_percent` int(11) DEFAULT NULL,
  `private_room_price` int(11) DEFAULT NULL,
  `private_room_mark_up_amout` int(11) DEFAULT NULL,
  `private_room_mark_up_percent` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_virtuemart_tour_section` */

DROP TABLE IF EXISTS `me1u8_virtuemart_tour_section`;

CREATE TABLE `me1u8_virtuemart_tour_section` (
  `virtuemart_tour_section_id` int(11) NOT NULL DEFAULT '0',
  `title` int(11) DEFAULT NULL,
  `icon` int(11) DEFAULT NULL,
  `meta_title` int(11) DEFAULT NULL,
  `key_word` int(11) DEFAULT NULL,
  `description` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `shared` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`virtuemart_tour_section_id`),
  KEY `ordering` (`ordering`),
  KEY `published` (`published`),
  KEY `shared` (`shared`),
  KEY `virtuemart_tour_section_id` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_2` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_3` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_4` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_5` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_6` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_7` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_8` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_9` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_10` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_11` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_12` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_13` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_14` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_15` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_16` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_17` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_18` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_19` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_20` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_21` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_22` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_23` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_24` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_25` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_26` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_27` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_28` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_29` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_30` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_31` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_32` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_33` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_34` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_35` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_36` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_37` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_38` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_39` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_40` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_41` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_42` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_43` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_44` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_45` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_46` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_47` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_48` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_49` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_50` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_51` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_52` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_53` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_54` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_55` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_56` (`virtuemart_tour_section_id`),
  KEY `virtuemart_tour_section_id_57` (`virtuemart_tour_section_id`)
) ENGINE=MyISAM AUTO_INCREMENT=213 DEFAULT CHARSET=utf8 COMMENT='Used to store tour section';

/*Table structure for table `me1u8_virtuemart_tour_style` */

DROP TABLE IF EXISTS `me1u8_virtuemart_tour_style`;

CREATE TABLE `me1u8_virtuemart_tour_style` (
  `virtuemart_tour_style_id` int(11) NOT NULL DEFAULT '0',
  `title` int(11) DEFAULT NULL,
  `icon` int(11) DEFAULT NULL,
  `meta_title` int(11) DEFAULT NULL,
  `key_word` int(11) DEFAULT NULL,
  `description` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `shared` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`virtuemart_tour_style_id`),
  KEY `ordering` (`ordering`),
  KEY `published` (`published`),
  KEY `shared` (`shared`),
  KEY `virtuemart_tour_style_id` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_2` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_3` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_4` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_5` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_6` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_7` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_8` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_9` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_10` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_11` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_12` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_13` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_14` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_15` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_16` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_17` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_18` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_19` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_20` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_21` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_22` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_23` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_24` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_25` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_26` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_27` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_28` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_29` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_30` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_31` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_32` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_33` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_34` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_35` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_36` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_37` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_38` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_39` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_40` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_41` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_42` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_43` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_44` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_45` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_46` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_47` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_48` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_49` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_50` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_51` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_52` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_53` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_54` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_55` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_56` (`virtuemart_tour_style_id`),
  KEY `virtuemart_tour_style_id_57` (`virtuemart_tour_style_id`)
) ENGINE=MyISAM AUTO_INCREMENT=212 DEFAULT CHARSET=utf8 COMMENT='Used to store currencies';

/*Table structure for table `me1u8_virtuemart_tour_type` */

DROP TABLE IF EXISTS `me1u8_virtuemart_tour_type`;

CREATE TABLE `me1u8_virtuemart_tour_type` (
  `virtuemart_tour_type_id` int(11) NOT NULL DEFAULT '0',
  `title` int(11) DEFAULT NULL,
  `icon` int(11) DEFAULT NULL,
  `meta_title` int(11) DEFAULT NULL,
  `key_word` int(11) DEFAULT NULL,
  `description` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `shared` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`virtuemart_tour_type_id`),
  KEY `ordering` (`ordering`),
  KEY `published` (`published`),
  KEY `shared` (`shared`),
  KEY `virtuemart_tour_type_id` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_2` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_3` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_4` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_5` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_6` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_7` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_8` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_9` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_10` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_11` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_12` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_13` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_14` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_15` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_16` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_17` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_18` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_19` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_20` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_21` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_22` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_23` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_24` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_25` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_26` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_27` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_28` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_29` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_30` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_31` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_32` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_33` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_34` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_35` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_36` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_37` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_38` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_39` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_40` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_41` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_42` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_43` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_44` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_45` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_46` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_47` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_48` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_49` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_50` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_51` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_52` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_53` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_54` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_55` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_56` (`virtuemart_tour_type_id`),
  KEY `virtuemart_tour_type_id_57` (`virtuemart_tour_type_id`)
) ENGINE=MyISAM AUTO_INCREMENT=209 DEFAULT CHARSET=utf8 COMMENT='Used to store currencies';

/*Table structure for table `me1u8_virtuemart_transfer_addon` */

DROP TABLE IF EXISTS `me1u8_virtuemart_transfer_addon`;

CREATE TABLE `me1u8_virtuemart_transfer_addon` (
  `virtuemart_transfer_addon_id` int(11) NOT NULL AUTO_INCREMENT,
  `transfer_type` varchar(20) DEFAULT NULL,
  `transfer_addon_name` varchar(50) DEFAULT NULL,
  `description` text,
  `transfer_payment_type` varchar(20) DEFAULT NULL,
  `vail_from` date DEFAULT NULL,
  `vail_to` date DEFAULT NULL,
  `conditions` text,
  `itinerary` text,
  `inclusion` text,
  `data_price` longtext NOT NULL,
  `passenger_age_from` int(11) DEFAULT NULL,
  `passenger_age_to` int(11) DEFAULT NULL,
  `virtuemart_cityarea_id` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `shared` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`virtuemart_transfer_addon_id`),
  KEY `virtuemart_cityarea_id` (`virtuemart_cityarea_id`),
  CONSTRAINT `me1u8_virtuemart_transfer_addon_ibfk_1` FOREIGN KEY (`virtuemart_cityarea_id`) REFERENCES `me1u8_virtuemart_cityarea` (`virtuemart_cityarea_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

/*Table structure for table `me1u8_virtuemart_userinfos` */

DROP TABLE IF EXISTS `me1u8_virtuemart_userinfos`;

CREATE TABLE `me1u8_virtuemart_userinfos` (
  `virtuemart_userinfo_id` int(11) DEFAULT NULL,
  `virtuemart_user_id` int(11) DEFAULT NULL,
  `address_type` int(11) DEFAULT NULL,
  `address_type_name` int(11) DEFAULT NULL,
  `company` int(11) DEFAULT NULL,
  `title` int(11) DEFAULT NULL,
  `last_name` int(11) DEFAULT NULL,
  `first_name` int(11) DEFAULT NULL,
  `middle_name` int(11) DEFAULT NULL,
  `phone_1` int(11) DEFAULT NULL,
  `phone_2` int(11) DEFAULT NULL,
  `fax` int(11) DEFAULT NULL,
  `address_1` int(11) DEFAULT NULL,
  `address_2` int(11) DEFAULT NULL,
  `city` int(11) DEFAULT NULL,
  `virtuemart_state_id` int(11) DEFAULT NULL,
  `virtuemart_country_id` int(11) DEFAULT NULL,
  `zip` int(11) DEFAULT NULL,
  `agreed` int(11) DEFAULT NULL,
  `tos` int(11) DEFAULT NULL,
  `customer_note` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  KEY `i_virtuemart_user_id` (`virtuemart_userinfo_id`,`virtuemart_user_id`),
  KEY `virtuemart_user_id` (`virtuemart_user_id`,`address_type`),
  KEY `address_type` (`address_type`),
  KEY `address_type_name` (`address_type_name`),
  KEY `virtuemart_userinfo_id` (`virtuemart_userinfo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Customer Information, BT = BillTo and ST = ShipTo';

/*Table structure for table `me1u8_virtuemart_vendor_medias` */

DROP TABLE IF EXISTS `me1u8_virtuemart_vendor_medias`;

CREATE TABLE `me1u8_virtuemart_vendor_medias` (
  `id` int(11) DEFAULT NULL,
  `virtuemart_vendor_id` int(11) DEFAULT NULL,
  `virtuemart_media_id` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `me1u8_virtuemart_vendors` */

DROP TABLE IF EXISTS `me1u8_virtuemart_vendors`;

CREATE TABLE `me1u8_virtuemart_vendors` (
  `virtuemart_vendor_id` int(11) NOT NULL DEFAULT '0',
  `vendor_name` int(11) DEFAULT NULL,
  `vendor_currency` int(11) DEFAULT NULL,
  `vendor_accepted_currencies` int(11) DEFAULT NULL,
  `vendor_params` int(11) DEFAULT NULL,
  `metarobot` int(11) DEFAULT NULL,
  `metaauthor` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`virtuemart_vendor_id`),
  KEY `virtuemart_vendor_id` (`virtuemart_vendor_id`),
  KEY `virtuemart_vendor_id_2` (`virtuemart_vendor_id`),
  KEY `virtuemart_vendor_id_3` (`virtuemart_vendor_id`),
  KEY `virtuemart_vendor_id_4` (`virtuemart_vendor_id`),
  KEY `virtuemart_vendor_id_5` (`virtuemart_vendor_id`),
  KEY `virtuemart_vendor_id_6` (`virtuemart_vendor_id`),
  KEY `virtuemart_vendor_id_7` (`virtuemart_vendor_id`),
  KEY `virtuemart_vendor_id_8` (`virtuemart_vendor_id`),
  KEY `virtuemart_vendor_id_9` (`virtuemart_vendor_id`),
  KEY `virtuemart_vendor_id_10` (`virtuemart_vendor_id`),
  KEY `virtuemart_vendor_id_11` (`virtuemart_vendor_id`),
  KEY `virtuemart_vendor_id_12` (`virtuemart_vendor_id`),
  KEY `virtuemart_vendor_id_13` (`virtuemart_vendor_id`),
  KEY `virtuemart_vendor_id_14` (`virtuemart_vendor_id`),
  KEY `virtuemart_vendor_id_15` (`virtuemart_vendor_id`),
  KEY `virtuemart_vendor_id_16` (`virtuemart_vendor_id`),
  KEY `virtuemart_vendor_id_17` (`virtuemart_vendor_id`),
  KEY `virtuemart_vendor_id_18` (`virtuemart_vendor_id`),
  KEY `virtuemart_vendor_id_19` (`virtuemart_vendor_id`),
  KEY `virtuemart_vendor_id_20` (`virtuemart_vendor_id`),
  KEY `virtuemart_vendor_id_21` (`virtuemart_vendor_id`),
  KEY `virtuemart_vendor_id_22` (`virtuemart_vendor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `me1u8_virtuemart_vendors_en_gb` */

DROP TABLE IF EXISTS `me1u8_virtuemart_vendors_en_gb`;

CREATE TABLE `me1u8_virtuemart_vendors_en_gb` (
  `virtuemart_vendor_id` int(11) NOT NULL DEFAULT '0',
  `vendor_store_desc` int(11) DEFAULT NULL,
  `vendor_terms_of_service` int(11) DEFAULT NULL,
  `vendor_legal_info` int(11) DEFAULT NULL,
  `vendor_letter_css` int(11) DEFAULT NULL,
  `vendor_letter_header_html` int(11) DEFAULT NULL,
  `vendor_letter_footer_html` int(11) DEFAULT NULL,
  `vendor_store_name` int(11) DEFAULT NULL,
  `vendor_phone` int(11) DEFAULT NULL,
  `vendor_url` int(11) DEFAULT NULL,
  `metadesc` int(11) DEFAULT NULL,
  `metakey` int(11) DEFAULT NULL,
  `customtitle` int(11) DEFAULT NULL,
  `vendor_invoice_free1` int(11) DEFAULT NULL,
  `vendor_invoice_free2` int(11) DEFAULT NULL,
  `vendor_mail_free1` int(11) DEFAULT NULL,
  `vendor_mail_free2` int(11) DEFAULT NULL,
  `vendor_mail_css` int(11) DEFAULT NULL,
  `slug` int(11) DEFAULT NULL,
  PRIMARY KEY (`virtuemart_vendor_id`),
  KEY `virtuemart_vendor_id` (`virtuemart_vendor_id`),
  KEY `virtuemart_vendor_id_2` (`virtuemart_vendor_id`),
  KEY `virtuemart_vendor_id_3` (`virtuemart_vendor_id`),
  KEY `virtuemart_vendor_id_4` (`virtuemart_vendor_id`),
  KEY `virtuemart_vendor_id_5` (`virtuemart_vendor_id`),
  KEY `virtuemart_vendor_id_6` (`virtuemart_vendor_id`),
  KEY `virtuemart_vendor_id_7` (`virtuemart_vendor_id`),
  KEY `virtuemart_vendor_id_8` (`virtuemart_vendor_id`),
  KEY `virtuemart_vendor_id_9` (`virtuemart_vendor_id`),
  KEY `virtuemart_vendor_id_10` (`virtuemart_vendor_id`),
  KEY `virtuemart_vendor_id_11` (`virtuemart_vendor_id`),
  KEY `virtuemart_vendor_id_12` (`virtuemart_vendor_id`),
  KEY `virtuemart_vendor_id_13` (`virtuemart_vendor_id`),
  KEY `virtuemart_vendor_id_14` (`virtuemart_vendor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `me1u8_virtuemart_vmuser_shoppergroups` */

DROP TABLE IF EXISTS `me1u8_virtuemart_vmuser_shoppergroups`;

CREATE TABLE `me1u8_virtuemart_vmuser_shoppergroups` (
  `id` int(11) DEFAULT NULL,
  `virtuemart_user_id` int(11) DEFAULT NULL,
  `virtuemart_shoppergroup_id` int(11) DEFAULT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `me1u8_virtuemart_vmusers` */

DROP TABLE IF EXISTS `me1u8_virtuemart_vmusers`;

CREATE TABLE `me1u8_virtuemart_vmusers` (
  `virtuemart_user_id` int(11) DEFAULT NULL,
  `virtuemart_vendor_id` int(11) DEFAULT NULL,
  `user_is_vendor` int(11) DEFAULT NULL,
  `customer_number` int(11) DEFAULT NULL,
  `virtuemart_paymentmethod_id` int(11) DEFAULT NULL,
  `virtuemart_shipmentmethod_id` int(11) DEFAULT NULL,
  `agreed` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  KEY `virtuemart_user_id` (`virtuemart_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `me1u8_virtuemart_waitingusers` */

DROP TABLE IF EXISTS `me1u8_virtuemart_waitingusers`;

CREATE TABLE `me1u8_virtuemart_waitingusers` (
  `virtuemart_waitinguser_id` int(11) DEFAULT NULL,
  `virtuemart_product_id` int(11) DEFAULT NULL,
  `virtuemart_user_id` int(11) DEFAULT NULL,
  `notify_email` int(11) DEFAULT NULL,
  `notified` int(11) DEFAULT NULL,
  `notify_date` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  KEY `virtuemart_waitinguser_id` (`virtuemart_waitinguser_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Table structure for table `me1u8_virtuemart_worldzones` */

DROP TABLE IF EXISTS `me1u8_virtuemart_worldzones`;

CREATE TABLE `me1u8_virtuemart_worldzones` (
  `virtuemart_worldzone_id` int(11) NOT NULL DEFAULT '0',
  `virtuemart_vendor_id` int(11) DEFAULT NULL,
  `zone_name` int(11) DEFAULT NULL,
  `zone_cost` int(11) DEFAULT NULL,
  `zone_limit` int(11) DEFAULT NULL,
  `zone_description` int(11) DEFAULT NULL,
  `zone_tax_rate` int(11) DEFAULT NULL,
  `ordering` int(11) DEFAULT NULL,
  `shared` int(11) DEFAULT NULL,
  `published` int(11) DEFAULT NULL,
  `created_on` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `modified_on` int(11) DEFAULT NULL,
  `modified_by` int(11) DEFAULT NULL,
  `locked_on` int(11) DEFAULT NULL,
  `locked_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`virtuemart_worldzone_id`),
  KEY `virtuemart_worldzone_id` (`virtuemart_worldzone_id`),
  KEY `virtuemart_worldzone_id_2` (`virtuemart_worldzone_id`),
  KEY `virtuemart_worldzone_id_3` (`virtuemart_worldzone_id`),
  KEY `virtuemart_worldzone_id_4` (`virtuemart_worldzone_id`),
  KEY `virtuemart_worldzone_id_5` (`virtuemart_worldzone_id`),
  KEY `virtuemart_worldzone_id_6` (`virtuemart_worldzone_id`),
  KEY `virtuemart_worldzone_id_7` (`virtuemart_worldzone_id`),
  KEY `virtuemart_worldzone_id_8` (`virtuemart_worldzone_id`),
  KEY `virtuemart_worldzone_id_9` (`virtuemart_worldzone_id`),
  KEY `virtuemart_worldzone_id_10` (`virtuemart_worldzone_id`),
  KEY `virtuemart_worldzone_id_11` (`virtuemart_worldzone_id`),
  KEY `virtuemart_worldzone_id_12` (`virtuemart_worldzone_id`),
  KEY `virtuemart_worldzone_id_13` (`virtuemart_worldzone_id`),
  KEY `virtuemart_worldzone_id_14` (`virtuemart_worldzone_id`),
  KEY `virtuemart_worldzone_id_15` (`virtuemart_worldzone_id`),
  KEY `virtuemart_worldzone_id_16` (`virtuemart_worldzone_id`),
  KEY `virtuemart_worldzone_id_17` (`virtuemart_worldzone_id`),
  KEY `virtuemart_worldzone_id_18` (`virtuemart_worldzone_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
