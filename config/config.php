<?php

//основной кофигурационный файл нашего приложения

//название сайта
Config::set('site_name', 'Теххнорент');

//список языков проекта
Config::set('languages', array('en', 'ru', 'ua'));

//добавляем роуты
// Routes. Route name => method_prefix
Config::set('routes', array(
    'default' => '',
    'admin'   => 'admin_'
));

//дефолтный роут
Config::set('default_route', 'default');
//дефолтный язык
Config::set('default_language', 'ru');
//дефолтный контроллер
Config::set('default_controller', 'pages');
//дефолтный action
Config::set('default_action', 'index');



