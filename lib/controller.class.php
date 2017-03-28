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

    //Текущий язык
    protected $language;

    //Язык для вставки в урл
    protected $languageForUrl = '';

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
        $this->language = App::getRouter()->getLanguage();
        $this->template = App::$template;

        if ($this->language == 'ru') {
            $this->languageForUrl = DS.$this->language;
        }
    }

    public function getDataForPagination($total_records = false)
    {
        $result = array();
        //пагинация начало
        $page = 1;
        $per_page = $page_offset = 15;
        $page_start = 0;
        if ($total_records === false) {
            $total_records = $this->model->getTotalCount();
        }

        if (isset($_GET) && isset($_GET['page'])) {
            $page = (int)$_GET['page'];
            if ($page > 1) {
                $page_start = ($page - 1) * $page_offset;
            }
        }

        $pagination = new Pagination();
        $pagination->setCurrent($page);
        $pagination->setRPP($per_page);
        $pagination->setTotal($total_records);
        $markup = $pagination->parse();
        //пагинация конец

        $result['page_start'] = $page_start;
        $result['page_offset'] = $page_offset;
        $result['markup'] = $markup;

        return $result;

    }

    /**
     * Check if current request is AJAX.
     */
    function is_ajax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }


}