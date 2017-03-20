CREATE TABLE admins
(
    admins_id SMALLINT(4) unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT,
    admins_name VARCHAR(24) NOT NULL,
    admins_login VARCHAR(24) NOT NULL,
    admins_pass VARCHAR(50) NOT NULL,
    admins_accessgroup TINYINT(4) NOT NULL,
    admins_active ENUM('yes', 'no') DEFAULT 'yes' NOT NULL,
    admins_manager ENUM('yes', 'no') DEFAULT 'no' NOT NULL,
    admins_manager_active ENUM('yes', 'no') DEFAULT 'no' NOT NULL,
    admins_mobile_phone VARCHAR(20),
    admins_short_phone VARCHAR(5)
);
CREATE INDEX admins_active ON admins (admins_active);
CREATE INDEX admins_manager ON admins (admins_manager);
CREATE INDEX admins_manager_2 ON admins (admins_manager, admins_manager_active);
CREATE UNIQUE INDEX admins_name ON admins (admins_name);
CREATE TABLE category
(
    id INT(12) PRIMARY KEY NOT NULL AUTO_INCREMENT,
    p_id INT(12) NOT NULL,
    name VARCHAR(100) NOT NULL,
    seo TEXT NOT NULL,
    ua_name VARCHAR(100) NOT NULL,
    ua_seo TEXT NOT NULL,
    active ENUM('yes', 'no') NOT NULL
);
CREATE INDEX active ON category (active);
CREATE INDEX p_id ON category (p_id);
CREATE TABLE items
(
    items_id INT(12) NOT NULL,
    items_available_kiev ENUM('true', 'false') DEFAULT 'false' NOT NULL,
    items_categoryId INT(12) NOT NULL,
    items_picture TEXT NOT NULL,
    items_picture_loc TEXT NOT NULL,
    items_picture_sm TEXT NOT NULL,
    items_vendor VARCHAR(50) NOT NULL,
    items_vendorCode VARCHAR(50) NOT NULL,
    items_typePrefix TEXT NOT NULL,
    items_name TEXT NOT NULL,
    items_description TEXT NOT NULL,
    items_param TEXT NOT NULL,
    ua_items_typePrefix TEXT NOT NULL,
    ua_items_name TEXT NOT NULL,
    ua_items_description TEXT NOT NULL,
    ua_items_param TEXT NOT NULL
);
CREATE INDEX items_available_kiev ON items (items_available_kiev);
CREATE INDEX items_categoryId ON items (items_categoryId);
CREATE UNIQUE INDEX items_id ON items (items_id);
CREATE TABLE messages
(
    id TINYINT(3) unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    messages TEXT
);
CREATE TABLE news
(
    id INT(12) unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT,
    title VARCHAR(100) NOT NULL,
    ua_title VARCHAR(100),
    alias VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    ua_description TEXT,
    picture VARCHAR(100),
    picture_small VARCHAR(100),
    active ENUM('yes', 'no') DEFAULT 'yes' NOT NULL,
    text TEXT NOT NULL,
    ua_text TEXT
);
CREATE TABLE old_pages
(
    page_id TINYINT(3) unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    alias VARCHAR(255) NOT NULL,
    description VARCHAR(255) NOT NULL,
    keywords VARCHAR(255) NOT NULL,
    text TEXT NOT NULL,
    position TINYINT(3) unsigned DEFAULT '0' NOT NULL
);
CREATE UNIQUE INDEX alias ON old_pages (alias);
CREATE TABLE pages
(
    id TINYINT(3) unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    ua_title VARCHAR(255),
    alias VARCHAR(255) NOT NULL,
    description VARCHAR(255) NOT NULL,
    ua_description VARCHAR(255),
    keywords VARCHAR(255) NOT NULL,
    ua_keywords VARCHAR(255),
    text TEXT NOT NULL,
    ua_text TEXT,
    position TINYINT(3) unsigned DEFAULT '0' NOT NULL
);
CREATE UNIQUE INDEX alias ON pages (alias);
CREATE TABLE users
(
    id SMALLINT(5) unsigned PRIMARY KEY NOT NULL AUTO_INCREMENT,
    login VARCHAR(45) NOT NULL,
    email VARCHAR(100) NOT NULL,
    role VARCHAR(45) DEFAULT 'admin' NOT NULL,
    password CHAR(32) NOT NULL,
    is_active TINYINT(1) unsigned DEFAULT '1'
);



CREATE TABLE `products` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `available_kiev` enum('true','false') NOT NULL DEFAULT 'false',
  `title` varchar(100) NOT NULL,
  `name` text NOT NULL,
  `description` text NOT NULL,
  `params` text NOT NULL,
  `ua_title` varchar(100) NOT NULL,
  `ua_name` text NOT NULL,
  `ua_description` text NOT NULL,
  `ua_params` text NOT NULL,
  `price` decimal(8,2) NOT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `picture_small` varchar(255) DEFAULT NULL,
  `vendor` varchar(50) NOT NULL,
  `vendor_code` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8