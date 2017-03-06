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

    public static function flash()
    {
        echo self::$flash_message;
        self::$flash_message = null;
    }

}