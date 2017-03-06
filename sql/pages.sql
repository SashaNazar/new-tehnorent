-- phpMyAdmin SQL Dump
-- version 4.0.10.16
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Мар 06 2017 г., 17:34
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
-- Структура таблицы `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `page_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `position` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`page_id`),
  UNIQUE KEY `alias` (`alias`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `pages`
--

INSERT INTO `pages` (`page_id`, `title`, `alias`, `description`, `keywords`, `text`, `position`) VALUES
(1, 'Контактна інформація та гарантії', 'guarantee', 'Гарантійні терміни, контакти', 'Гарантійні терміни, контакти', 'Замовлення і консультація по телефонам\r\n067-921-83-17\r\n066-354-28-96\r\n093-872-56-25\r\ne-mail: ztool@ukr.net\r\n\r\nГарантійні терміни для:\r\n- електроінструменту, мотоінструменту, компресорів, очищувачів високого тиску, зарядних пристроїв – 12 місяців;\r\n- пневмоінструменту, ключів балонних роторних, лебідок, талів – 6 місяців;\r\n- гідравлики – 3 місяці.\r\n\r\nАдреса сервісного центру: м. Харьків, пр. Московський, 247\r\nДля споживачів усєї України ми сплачуємо доставку товару на гарантійне обслуговування та назад службою доставки «Нова Пошта» або іншими службами (за погодженням). Час ремонту (разом з доставкою) становитиме, орієнтовно, 4-7 днів.\r\nГрафік работи сервісного центру: Пн, Вт, Ср, Чт, Пт (9.00 – 17.30);\r\nТелефонии сервісного центр: +38 (057) 716-333-8, +38 (067) 576-14-10;\r\ne-mail:servis@intertool.kharkov.ua\r\n\r\nЗдаючи інструмент в ремонт по гарантії, Ви можете в цей час працювати аналогічним інструментом з нашого обмінного фонду.', 1),
(2, 'Доставка та оплата', 'delivery', 'Доставка та оплата', 'Доставка та оплата', 'Доставка Службами доставки: «Нова Пошта», «Міст Експрес», «Делівері»\r\n\r\nВартість доставки замовлень при розрахунку післяплатою оплачує покупець згідно з тарифами кур''єрської служби.\r\nТовар буде доставлений у відділення служби доставки.\r\n\r\nДОСТАВКА НА ВІДДІЛЕННЯ КУР''ЄРСКОЇ СЛУЖБИ:\r\n\r\n\r\nПри сумі замовлення 1000.00 грн., або більше, доставка здійснюється безкоштовно за умови передплати. При сумі замовлення до 1000.00 грн. – вартість замовлення складає 35.00 грн.\r\nУмови безкоштовної доставки не поширюються на наступні групи товаров:\r\n•       Гідравлічне обладнання для СТО;\r\n•       Компресори повітряні;\r\n•       Сходи та драбини;\r\n•       Тачки садово -будівельні;\r\n•       газонокосарки;\r\n•       Генератори;\r\n•       бетономішалки;\r\n•       Снігоприбирачі;\r\n•       Мотоблоки та культиватори;\r\n\r\n\r\nАДРЕСНА ДОСТАВКА:\r\n\r\nВартість доставки по Києву при сумі замовлення до 1000.00 грн. становить 35.00 грн. При сумі замовлення 1000.00 грн та більше – доставка здійснюється безкоштовно. В разі здійснення адресної доставки службою на будь-яку суму за місцем проживання клієнта – доставка здійснюється за рахунок клієнта.\r\n\r\nДОСТАВКА ПО КИЄВУ:\r\n\r\n При сумі замовлення до 1000.00 грн. становить 35.00 грн. При сумі замовлення 1000.00 грн та більше – доставка здійснюється безкоштовно.\r\n\r\nДоставка замовленого товару здійснюється до під''їзду, або до воріт будинку. Для цього покупець узгоджує адресу та час доставки з менеджером. Передача замовлення покупцеві відбувається за пред''явлення покупцем документу, який посвідчує особу.\r\n\r\nОПЛАТА ТОВАРУ:\r\n ГОТІВКОВА:\r\n— післяплатою під час отримання замовлення у представництві служби доставки «Нова пошта» та «Міст Експрес»;\r\n— оплата кур’єрові при доставці товару по Києву.\r\nБЕЗГОТІВКОВА:\r\n — для фізичних осіб через банк (при доставці по всій території України). Ви можете сплатити рахунок, який вам вислано, безготівковим переказом в будь-якому банку (при цьому може бути утримано банківську комісію). Як тільки оплата надходить на наш розрахунковий рахунок (як правило, гроші надходять на рахунок наступного дня) товар передається до служби доставки. Просимо після здійснення оплати інформувати менеджерів.\r\n\r\nТЕРМІНИ ФОРМУВАННЯ ЗАМОВЛЕННЯ ТА ПОСТАВКИ ТОВАРУ:\r\n\r\nТермін формування замовлення, зазвичай, 1-2 робочих дня. В цей період електронною поштою або факсом Вам буде відправлений рахунок до сплати з реквізитами. У випадку, якщо Ви відправили замовлення на інструмент у вихідні або святкові дні, його формування пересувається на найближчий робочий день. Доставка інструменту, що замовлений в інтернет-магазині, здійснюється протягом 2-4 днів після оформлення замовлення', 2);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
