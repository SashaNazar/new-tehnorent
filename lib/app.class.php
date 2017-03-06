<?php

//отвечает за обработку запросов и вызывает методы контроллера
class App
{
    //для работы с объектом Роутера
    protected static $router;

    /**
     * @return mixed
     */
    public static function getRouter()
    {
        return self::$router;
    }

    //отвечает за обработку запросов к приложению, в качестве параметра получает $uri который
    //используется для создания объекта Роутера
    public static function run($uri)
    {
        self::$router = new Router($uri);

        //загружаем языковые настройки
        Lang::load(self::$router->getLanguage());

        $controller_class = ucfirst(self::$router->getController()).'Controller';
        $controller_method = strtolower(self::$router->getMethodPrefix().self::$router->getAction());

        //Создаем объект нужного контролера
        $controller_object = new $controller_class();
        if (method_exists($controller_object, $controller_method)) {
            //controllers action may return a view path
            $view_path = $controller_object->$controller_method();
            $view_object = new View($controller_object->getData(), $view_path);
            $content = $view_object->render();
        } else {
            throw new Exception('Method '.$controller_method.' of class '.$controller_class.' does not exist.');
        }

        //название роутера
        $layout = self::$router->getRoute();
        $layout_path = VIEWS_PATH.DS.$layout.'.html';
        $layout_view_object = new View(compact('content'), $layout_path);
        echo  $layout_view_object->render();
    }
}