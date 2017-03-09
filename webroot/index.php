<?php

//константа для разделителя директорий, так он отличается в разных системах
define('DS', DIRECTORY_SEPARATOR);
//константа для корня приложения
define('ROOT', dirname(dirname(__FILE__)));
//константа для папки с шаблонами
define('VIEWS_PATH', ROOT.DS.'views');

//подключаем файл инициализации init.php с автозагрузчиком
require_once(ROOT.DS.'lib'.DS.'init.php');

$session_month = 2592000; //время жизни сессии на месяц
session_set_cookie_params($session_month);
//стартуем сессию, чтобы она была доступна везде
session_start();

try {
    App::run($_SERVER['REQUEST_URI']);
} catch (Exception $e) {
    var_dump($e->getCode());
    var_dump($e->getMessage());
}
