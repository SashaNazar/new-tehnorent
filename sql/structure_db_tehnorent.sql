CREATE TABLE `admins` (
  `admins_id` smallint(4) NOT NULL AUTO_INCREMENT,
  `admins_name` varchar(24) NOT NULL,
  `admins_login` varchar(24) NOT NULL,
  `admins_pass` varchar(50) NOT NULL,
  `admins_accessgroup` tinyint(4) NOT NULL,
  `admins_active` enum('yes','no') NOT NULL DEFAULT 'yes',
  `admins_manager` enum('yes','no') NOT NULL DEFAULT 'no',
  `admins_manager_active` enum('yes','no') NOT NULL DEFAULT 'no',
  `admins_mobile_phone` varchar(20) DEFAULT NULL,
  `admins_short_phone` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`admins_id`),
  UNIQUE KEY `admins_name` (`admins_name`),
  KEY `admins_active` (`admins_active`),
  KEY `admins_manager` (`admins_manager`),
  KEY `admins_manager_2` (`admins_manager`,`admins_manager_active`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;


CREATE TABLE `categoty` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `p_id` int(12) NOT NULL,
  `name` varchar(100) NOT NULL,
  `seo` text NOT NULL,
  `ua_name` varchar(100) NOT NULL,
  `ua_seo` text NOT NULL,
  `active` enum('yes','no') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `p_id` (`p_id`),
  KEY `active` (`active`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;


CREATE TABLE `items` (
  `items_id` int(12) NOT NULL,
  `items_available_kiev` enum('true','false') NOT NULL DEFAULT 'false',
  `items_categoryId` int(12) NOT NULL,
  `items_picture` text NOT NULL,
  `items_picture_loc` text NOT NULL,
  `items_picture_sm` text NOT NULL,
  `items_vendor` varchar(50) NOT NULL,
  `items_vendorCode` varchar(50) NOT NULL,
  `items_typePrefix` text NOT NULL,
  `items_name` text NOT NULL,
  `items_description` text NOT NULL,
  `items_param` text NOT NULL,
  `ua_items_typePrefix` text NOT NULL,
  `ua_items_name` text NOT NULL,
  `ua_items_description` text NOT NULL,
  `ua_items_param` text NOT NULL,
  UNIQUE KEY `items_id` (`items_id`),
  KEY `items_available_kiev` (`items_available_kiev`),
  KEY `items_categoryId` (`items_categoryId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE `pages` (
  `page_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `position` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`page_id`),
  UNIQUE KEY `alias` (`alias`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;