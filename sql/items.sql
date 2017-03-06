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
-- Структура таблицы `items`
--

CREATE TABLE IF NOT EXISTS `items` (
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

--
-- Дамп данных таблицы `items`
--

INSERT INTO `items` (`items_id`, `items_available_kiev`, `items_categoryId`, `items_picture`, `items_picture_loc`, `items_picture_sm`, `items_vendor`, `items_vendorCode`, `items_typePrefix`, `items_name`, `items_description`, `items_param`, `ua_items_typePrefix`, `ua_items_name`, `ua_items_description`, `ua_items_param`) VALUES
(122193, 'true', 2, 'http://intertool.ua/catalog/avtoinstrument/ustroystva-zaryadnie-i-pusko-zaryadnie-dlya-akb/ustroystva-zaryadnie-dlya-akb/intertool-at-3014-source.jpg', '/items_img/122193/INTERTOOL_AT-3014500.jpg', 'http://toolz.com.ua/items_img/128675/INTERTOOL_AC-0001100.jpg', 'INTERTOOL', 'AT-3014', 'Автомобильное зарядное устройство для АКБ', 'Автомобильное зарядное устройство для АКБ INTERTOOL AT-3014', '&lt;p&gt;Компактное зарядное устройство AT-3014 INTERTOOL предназначено для зарядки аккумуляторных батарей 6 В и 12 В, емкостью до 60 Ач. Данная модель зарядного устройства подходит для зарядки аккумуляторных батарей мотоциклов, автомобилей и других механических транспортных средств. Питается зарядное устройство от бытовой сети с напряжением 230 В.&lt;/p&gt;\n\n&lt;h2&gt;Особенности&lt;/h2&gt;\n\n&lt;blockquote&gt;\n&lt;p&gt;Подходит для аккумуляторов практически любых типов транспортных средств: мотоциклов, автомобилей;&lt;/p&gt;\n\n&lt;p&gt;Два режима зарядки &amp;mdash; на 6 В или 12 В;&lt;/p&gt;\n\n&lt;p&gt;Очень скромные габариты;&lt;/p&gt;\n\n&lt;p&gt;Питается от бытовой сети 230 В.&lt;/p&gt;\n&lt;/blockquote&gt;\n\n&lt;div class=&quot;show_hide&quot; hide=&quot;Скрыть полное описание&quot; show=&quot;Показать полное описание&quot;&gt;\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\n\n&lt;p&gt;С помощью разъемов-крокодилов устройство подсоединяется к контактам аккумуляторной батареи с соблюдением полярности. Ток заряда составляет 6 А. На передней стороне зарядного устройства имеется удобный тумблер-переключатель для выбора режима зарядки на 6 В или 12 В, а также стрелочный индикатор тока зарядки. Автомобильное зарядное устройство имеет небольшие габаритные размеры и малый вес.&lt;/p&gt;\n\n&lt;p&gt;Оно не займет много свободного пространства в вашем гараже. Удобная ручка в верхней части позволяет без проблем перемещать зарядное устройство. Хранить AT-3014 необходимо в закрытых помещениях при минимальной влажности и окружающей температуре воздуха от -10&amp;deg;C до +30&amp;deg;C. При длительном хранении зарядного устройства с температурой окружающего воздуха менее чем -10&amp;deg;C, перед включением устройства дайте ему прогреться в теплом помещении, как минимум 1 час.&lt;/p&gt;\n\n&lt;p&gt;&amp;nbsp;&lt;/p&gt;\n\n&lt;p&gt;&lt;b&gt;Настоятельно рекомендуем вам, прежде чем приступать к работе, ознакомьтесь с инструкцией по эксплуатации!&lt;/b&gt;&lt;/p&gt;\n&lt;/div&gt;', 'Потребляемая мощность: 70 Вт&lt;br /&gt;\r\nНапряжение питания: 230 В&lt;br /&gt;\r\nВыходное напряжение постоянного тока: 6; 12 В&lt;br /&gt;\r\nПостоянный выходной ток: 6 А&lt;br /&gt;\r\nЕмкость аккумулятора: 60 А*ч&lt;br /&gt;\r\nГарантия: 12 месяцев&lt;br /&gt;\r\nВес: 1,550 кг&lt;br /&gt;', '', '', '', ''),
(111395, 'true', 2, 'http://intertool.ua/catalog/yashchiki-sumki-poyasa-dlya-instrumentov/yashchiki-dlya-instrumentov/intertool-bx-0016-source.jpg', '/items_img/111395/INTERTOOL_BX-0016500.jpg', 'http://toolz.com.ua/items_img/128675/INTERTOOL_AC-0001100.jpg', 'INTERTOOL', 'BX-0016', 'Ящик для инструментов', 'Ящик для инструментов, 16&quot; 396x216x164 мм INTERTOOL BX-0016', '&lt;p&gt;Ящик для инструментов ТМ INTERTOOL BX-0016 небольшого размера (396*216*164мм, 16&amp;quot;), предназначен для бережного хранения инструментов. Изготовлен из прочного пластика. Оснащен съемным лотком для часто используемых инструментов и деталей, а также двумя объемными органайзерами с прозрачной крышкой для мелких расходных материалов. Запирается на надежный пластиковый замок. Легкий, компактный, с удобной ручкой-держателем что имеет большое значение при транспортировке.&lt;/p&gt;', 'Тип: ящик для инструментов &lt;br /&gt;\r\nГабаритные размеры: 396x216x164 мм&lt;br /&gt;\r\nМатериал изготовления: пластик &lt;br /&gt;\r\nВес: 1,000 кг&lt;br /&gt;', '', '', '', ''),
(111756, 'true', 3, 'http://intertool.ua/catalog/yashchiki-sumki-poyasa-dlya-instrumentov/yashchiki-dlya-instrumentov/intertool-bx-0125-source.jpg', '/items_img/111756/INTERTOOL_BX-0125500.jpg', 'http://toolz.com.ua/items_img/128675/INTERTOOL_AC-0001100.jpg', 'INTERTOOL', 'BX-0125', 'Ящик для инструментов', 'Ящик для инструментов, 13&quot; 318x175x131 мм INTERTOOL BX-0125', '&lt;p&gt;Ящик INTERTOOL BX-0125 &amp;ndash; сравнительно небольшой и имеет габаритные размеры 318*175*131 мм 13&amp;quot;. Легкий, многофункциональный ящик предназначен для хранения и транспортировки мелких деталей и инструментов. Изготовлен из ударопрочного пластика, надежно закрывается на пластиковый замок, оснащен съемным лотком для часто используемого мелкого инструмента. В верхней части крышки ящика расположен объемный органайзер с прозрачной крышкой для хранения различных мелочей.&lt;/p&gt;', 'Тип: ящик для инструментов &lt;br /&gt;\r\nГабаритные размеры: 318x175x131 мм&lt;br /&gt;\r\nМатериал изготовления: пластик &lt;br /&gt;\r\nВес: 0,563 кг&lt;br /&gt;', '', '', '', ''),
(123663, 'true', 3, 'http://intertool.ua/catalog/yashchiki-sumki-poyasa-dlya-instrumentov/sumki-dlya-instrumentov/intertool-bx-9007-source.jpg', '/items_img/123663/INTERTOOL_BX-9007500.jpg', 'http://toolz.com.ua/items_img/128675/INTERTOOL_AC-0001100.jpg', 'INTERTOOL', 'BX-9007', 'Сумка-тележка для инструментов', 'Сумка-тележка для инструментов с телескопической ручкой 510 мм x 290 мм x 360 мм INTERTOOL BX-9007', '&lt;div&gt;Сумка-тележка с телескопической ручкой BX-9007. Одна из самых вместительных сумок из представленных &lt;a href=&quot;/blog/novinki/sumki-dlya-instrumentov-tm-intertool.html&quot;&gt;новинок.&lt;/a&gt; Хранение и транспортировка инструментов стала еще удобнее. Сумка оснащена телескопической ручкой. Прочные амортизирующие колеса на сумке выдерживают большую нагрузку и максимально мягко проходят небольшие неровности на дороге. Преимущество сумки &amp;ndash; прочный материал 600 d полиэстер. Максимально удобное размещение карманов на сумке позволяет разместить как большой инструмент, так и небольшой ручной инструмент, а также расходные материалы.&lt;/div&gt;', 'Тип: сумка-тележка &lt;br /&gt;\r\nГабаритные размеры: 510x290x360 мм&lt;br /&gt;\r\nВес: 2,750 кг&lt;br /&gt;', '', '', '', ''),
(122939, 'true', 3, 'http://intertool.ua/catalog/elektroinstrument-i-oborudovanie/shurupoverti/shurupoverti-setevie/intertool-dt-0103-source.jpg', '/items_img/122939/INTERTOOL_DT-0103500.jpg', 'http://toolz.com.ua/items_img/128675/INTERTOOL_AC-0001100.jpg', 'INTERTOOL', 'DT-0103', 'Шуруповерт сетевой', 'Шуруповерт 280Вт, 230В, 0-750об/мин, 1.0-10мм, реверс, момент фиксации 23+1 INTERTOOL DT-0103', '&lt;p&gt;Электрический шуруповерт (дрель-шуруповерт) &amp;ndash; ручной электрический инструмент предназначен для сверления отверстий, заворачивания и отворачивания шурупов и винтов. Электрический шуруповерт DT-0103 оснащен двигателем мощностью 280 Вт. Максимальная скорость вращения электрического шуруповерта составляет 0-750 об/мин. За счет электронной регулировки числа оборотов достигается бесступенчатая подгонка чисел оборотов соответствующему рабочему процессу. А возможность выбора крутящего момента позволяет использовать шуруповерт для различных целей. Диаметр сверления электрического шуруповерта составляет: для стали 5 мм, для дерева 10 мм. К сверильному патрону электрического шуруповерта DT-0103 также подойдут промышленные сверла и биты диаметрами до 10 мм.&lt;/p&gt;', 'Гарантия: 12 месяцев&lt;br /&gt;\r\nНапряжение питания: 230 В&lt;br /&gt;\r\nПотребляемая мощность: 280 Вт&lt;br /&gt;\r\nСкорость вращения: 0-750 об/мин&lt;br /&gt;\r\nПатрон: 1,0-10 мм&lt;br /&gt;\r\nДиаметр сверления: дерево: 10 мм; сталь: 5 мм &lt;br /&gt;\r\nНаличие реверса: да &lt;br /&gt;\r\nНаличие уровня: нет &lt;br /&gt;\r\nВес: 1,700 кг&lt;br /&gt;', '', '', '', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
