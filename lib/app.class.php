<?php

//отвечает за обработку запросов и вызывает методы контроллера
class App
{
    //для работы с объектом Роутера
    protected static $router;

    //объект для работы с БД
    public static $db;

    //объект для работы с шаблонизатором
    public static $template;

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

        //Создаем подключение с базой данных
        self::$db = new DB(Config::get('db.host'), Config::get('db.user'), Config::get('db.password'), Config::get('db.db_name'));

        //загружаем Шаблонизатор
        self::$template = new Template(VIEWS_PATH);

        //загружаем языковые настройки
        Lang::load(self::$router->getLanguage());

        $controller_class = ucfirst(self::$router->getController()).'Controller';
        $controller_method = strtolower(self::$router->getMethodPrefix().self::$router->getAction());

        //название роутера
        $layout = self::$router->getRoute();

        if ($layout == 'admin' && Session::get('role') != 'admin') {
            if ($controller_method != 'admin_login') {
                //Router::redirect('/admin/users/login');
                Router::redirect('/admin/admins/login');
            }
        }

        //Создаем объект нужного контролера
        $controller_object = new $controller_class();
        if (method_exists($controller_object, $controller_method)) {
            //controllers action may return a view path
            $view_path = $controller_object->$controller_method();
//            $view_object = new View($controller_object->getData(), $view_path);
//            $content = $view_object->render();
        } else {
            throw new Exception('Method '.$controller_method.' of class '.$controller_class.' does not exist.');
        }

//        $layout_path = VIEWS_PATH.DS.$layout.'.html';
//        $layout_view_object = new View(compact('content'), $layout_path);
//        echo  $layout_view_object->render();

        if (!empty(Session::get('role'))) {
            self::$template->addVar('admin_user', Session::get('role'));
        }
        if (Session::hasFlash()) {
            self::$template->addVar('message', Session::flash());
        }
        self::$template->addVar('site_name', Config::get('site_name'));
        self::$template->parseFile('new_admin.html');

    }
}