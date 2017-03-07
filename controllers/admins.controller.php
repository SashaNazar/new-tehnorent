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

    public function admin_index()
    {
        $this->data['admins'] = $this->model->getList();
    }

    public function admin_add()
    {
        if ($_POST) {
            $result = $this->model->save($_POST);
            if ($result) {
                Session::setFlash('Администратор был успешно создан.');
            } else {
                Session::setFlash('Ошибка!');
            }
            Router::redirect('/admin/admins/');
        }
    }

    public function admin_edit()
    {
        if ($_POST) {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
            $result = $this->model->save($_POST, $id);
            if ($result) {
                Session::setFlash('Данные успешно обновлены.');
            } else {
                Session::setFlash("Ошибка!");
            }
            Router::redirect('/admin/admins/');
        }


        //  нужно доделать, чтобы при несуществующем айдишнике тоже перебрасывало на главную
        if (isset($this->params[0])) {
            $this->data['admin'] = $this->model->getById($this->params[0]);

            if (!$this->data['admin']) {
                Session::setFlash("Неправилный Id администратора!");
                Router::redirect('/admin/admins/');
            }

        } else {
            Session::setFlash("Неправилный Id администратора!");
            Router::redirect('/admin/admins/');
        }
    }

    public function admin_profile()
    {

    }

    public function admin_unactive()
    {
        if (isset($this->params[0])) {
            $result = $this->model->setUnactive($this->params[0]);
            if ($result) {
                Session::setFlash('Status was successful changed');
            } else {
                Session::setFlash("Error!");
            }
        }
        Router::redirect('/admin/admins/');
    }

    public function admin_active()
    {
        if (isset($this->params[0])) {
            $result = $this->model->setActive($this->params[0]);
            if ($result) {
                Session::setFlash('Status was successful changed');
            } else {
                Session::setFlash("Error!");
            }
        }
        Router::redirect('/admin/admins/');
    }

    public function admin_login()
    {
        if ($_POST && isset($_POST['login']) && isset($_POST['password'])) {
            $user = $this->model->getByLogin($_POST['login']);
            //$hash = md5(Config::get('hash').$_POST['password']);

            if ($user && in_array($user['admins_accessgroup'], $this->admins_accessgroup) && $user['admins_pass'] == $_POST['password']) {
                Session::set('login', $user['admins_login']);
                Session::set('role', 'admin');
                Session::set('access_group', $user['admins_accessgroup']);

                Router::redirect('/admin/admins/');
            } else {
                Session::setFlash('Логин или пароль неверный!');
            }

        }
    }

    public function admin_logout()
    {
        Session::destroy();
        Router::redirect('/admin/');
    }
}