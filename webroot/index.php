<?php

//константа для разделителя директорий, так он отличается в разных системах
define('DS', DIRECTORY_SEPARATOR);
//константа для корня приложения
define('ROOT', dirname(dirname(__FILE__)));

//получаем строку входящего запроса
$uri = $_SERVER['REQUEST_URI'];

//print_r($uri);