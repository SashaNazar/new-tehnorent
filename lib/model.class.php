<?php

class Model
{
    //атрибут для доступа к базе данных
    protected $db;

    public function __construct()
    {
        $this->db = App::$db;
    }
}