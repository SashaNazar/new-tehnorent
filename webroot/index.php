<?php

//константа для разделителя директорий, так он отличается в разных системах
define('DS', DIRECTORY_SEPARATOR);
//константа для корня приложения
define('ROOT', dirname(dirname(__FILE__)));
//константа для папки с шаблонами
define('VIEWS_PATH', ROOT.DS.'views');

//подключаем файл инициализации init.php с автозагрузчиком
require_once(ROOT.DS.'lib'.DS.'init.php');

try {
    App::run($_SERVER['REQUEST_URI']);
} catch (Exception $e) {
    var_dump($e->getCode());
    var_dump($e->getMessage());
}

//$router = new Router($_SERVER['REQUEST_URI']);

//echo "<pre>";
//print_r('Route: ' . $router->getRoute() . PHP_EOL);
//print_r('Language: ' . $router->getLanguage() . PHP_EOL);
//print_r('Controller: ' . $router->getController() . PHP_EOL);
//print_r('Action to be called: ' . $router->getMethodPrefix().$router->getAction() . PHP_EOL);
//echo "Params";
//print_r($router->getParams());