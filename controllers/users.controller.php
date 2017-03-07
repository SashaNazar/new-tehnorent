<?php

//Класс-контроллер для работы с пользователями
class UsersController extends Controller
{
    public function __construct(array $data = array())
    {
        parent::__construct($data);
        $this->model = new User();
    }

    public function admin_login()
    {
        if ($_POST && isset($_POST['login']) && isset($_POST['password'])) {
            $user = $this->model->getByLogin($_POST['login']);
            $hash = md5(Config::get('hash').$user['password']);

            if ($user && $user['is_active'] && $user['password'] === $hash) {
                Session::set('login', $user['login']);
                Session::set('role', $user['role']);
            }

        }
    }

    public function admin_logout()
    {
        Session::destroy();
        Router::redirect('/admin/');
    }
}