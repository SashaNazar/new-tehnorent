-- phpMyAdmin SQL Dump
-- version 4.0.10.16
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Мар 06 2017 г., 17:33
-- Версия сервера: 5.5.49-0+deb7u1-log
-- Версия PHP: 5.2.17-0+deb7u2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `tehnoren`
--

-- --------------------------------------------------------

--
-- Структура таблицы `admins`
--

CREATE TABLE IF NOT EXISTS `admins` (
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `admins`
--

INSERT INTO `admins` (`admins_id`, `admins_name`, `admins_login`, `admins_pass`, `admins_accessgroup`, `admins_active`, `admins_manager`, `admins_manager_active`, `admins_mobile_phone`, `admins_short_phone`) VALUES
(1, 'Андрей Лебедев', 'tech28@skarb.com.ua', 'tech28', 1, 'yes', 'no', 'no', '', '30155'),
(2, 'Виктор Ануприенко', 'Admin', '25101972', 1, 'yes', 'no', 'no', '', '10288'),
(3, 'Sasha', 'sasha', '123456', 1, 'yes', 'no', 'no', '+380(96)915-15-49', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
