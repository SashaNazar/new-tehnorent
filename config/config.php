<?php

//основной кофигурационный файл нашего приложения

//название сайта
Config::set('site_name', 'Технорент');
//список языков проекта
Config::set('languages', array('ru', 'ua'));

//добавляем роуты
// Routes. Route name => method_prefix
Config::set('routes', array(
    'default' => '',
    'admin'   => 'admin_'
));

//дефолтный роут
Config::set('default_route', 'default');
//дефолтный язык
Config::set('default_language', 'ua');
//дефолтный контроллер
Config::set('default_controller', 'pages');
//дефолтный action
Config::set('default_action', 'index');

//"соль" (будет использоваться при генерации хеша паролей)
Config::set('salt', 'test1234567890test');


//Конфиги для подключения к базе данных локально
Config::set('db.host', 'localhost');
Config::set('db.user', 'admin');
Config::set('db.password', 'admin');
Config::set('db.db_name', 'new_tehnorent');

//Конфиги для подключения к БД на проекте
//Config::set("db.host", "localhost");
//Config::set("db.user", "u_tehnoren");
//Config::set("db.password", "FDJnCjTSNwrL");
//Config::set("db.db_name", "tehnoren");

