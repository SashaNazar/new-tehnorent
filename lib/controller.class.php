<?php

//Базовый класс для контролеров
class Controller
{
    //Содержит информацию, которая передается из контроллера представлеию
    protected $data;

    //Содержит модель(для доступа к объекту модели)
    protected $model;

    //Параметры из строки запроса
    protected $params;

    //Объект шаблонизатора
    protected $template;

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }

    public function __construct($data = array())
    {
        $this->data = $data;
        $this->params = App::getRouter()->getParams();
        $this->template = App::$template;
    }
}