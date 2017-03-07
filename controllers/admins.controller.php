<?php

//Контроллер для работы с администраторами

class AdminsController extends Controller
{
    //массив с уровнями доступа администраторов
    protected $admins_accessgroup = array(1);

    public function __construct(array $data = array())
    {
        parent::__construct($data);
        $this->model = new Admin();
    }

    public function admin_login()
    {
        if ($_POST && isset($_POST['login']) && isset($_POST['password'])) {
            $user = $this->model->getByLogin($_POST['login']);
            //$hash = md5(Config::get('hash').$_POST['password']);

            if ($user && in_array($user['admins_accessgroup'], $this->admins_accessgroup) && $user['admins_pass'] == $_POST['password']) {
                var_dump('fsdfsd');
                Session::set('login', $user['admins_login']);
                Session::set('role', 'admin');
                Session::set('access_group', $user['admins_accessgroup']);
            }

        }
    }

    public function admin_logout()
    {
        Session::destroy();
        Router::redirect('/admin/');
    }
}