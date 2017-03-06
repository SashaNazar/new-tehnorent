<?php

//отвечает за хранение настроек приложения(например параметры для подключения к базе данных)
class Config
{
    //ассоциативный массив для настроек приложения
    protected static $settings = array();

    //метод для получения значения хранимого в настройках
    public static function get($key)
    {
        return isset(self::$settings[$key]) ? self::$settings[$key] : null;
    }

    //метод для устаовки значения в массив $settings
    public static function set($key, $value)
    {
        self::$settings[$key] = $value;
    }
}