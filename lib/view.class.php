<?php

//класс который отвечает за работу с представлениями
class View
{
    //для хранения данных которые передаются из контроллера в представление
    protected $data;

    //содержит путь к текущему файлу представления
    protected $path;

    public function __construct($data = array(), $path = null)
    {
        if (!$path) {
            $path = self::getDefaultViewPath();
        }
        if (!file_exists($path)) {
            throw new Exception('Template file is not found in path: ' . $path);
        }

        $this->path = $path;
        $this->data = $data;
    }

    //путь к шаблону
    protected static function getDefaultViewPath()
    {
        $router = App::getRouter();
        if (!$router) {
            return false;
        }

        $controller_dir = $router->getController();
        $template_name = $router->getMethodPrefix().$router->getAction().'.html';

        return VIEWS_PATH.DS.$controller_dir.DS.$template_name;
    }

    //отвечает за рендеринг шаблона и возвращает готовый html код
    public function render()
    {
        //переменная $data будет доступна в самом шаблоне
        $data = $this->data;
        //включаем буферизацию вывода
        ob_start();
        include($this->path);
        $content = ob_get_clean();

        return $content;
    }
}