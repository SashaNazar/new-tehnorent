<?php

class Session
{
    //flash сообщение для выводы пользователям сайта
    protected static $flash_message;

    //установка flash сообщения
    public static function setFlash($message)
    {
        self::$flash_message = $message;
    }

    //проверка, есть ли сообщение
    public static function hasFlash()
    {
        return !is_null(self::$flash_message);
    }

    //вывод flash сообщения
    public static function flash()
    {
        //echo self::$flash_message;
        return self::$flash_message;
        self::$flash_message = null;
    }

    //метод для записи данных в сессию по ключу
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    //метод для получения данных из сессии по ключу
    public static function get($key)
    {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
        return null;
    }

    //метод для удаления записи из сессии по ключу
    public static function delete($key)
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    //метод для удаления сессии, вызывается при віходе пользователем из системы
    public static function destroy()
    {
        session_destroy();
    }


}