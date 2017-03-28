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
        $result = $this->model->getList();
        foreach ($result as $item) {
            $this->template->addBlock('ADMINS', array(
                'admins_id'			=>   $item['id'] ,
                'admins_name'			=>   $item['admins_name'],
                'admins_active'			=>   $item['admins_active'] == 'yes' ? 'yes' : ''
            ));
        }
        $this->template->addVar('OUTPUTMAIN', $this->template->parseFile('admins/admin_index.html', false) );

        //$this->data['admins'] = $this->model->getList();
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
        $this->template->addVar('OUTPUTMAIN', $this->template->parseFile('admins/admin_add.html', false) );
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
            //$this->data['admin'] = $this->model->getById($this->params[0]);

            $result = $this->model->getById($this->params[0]);
            $this->template->addVars(array(
                'VAL_ADMINS_ID'			    =>   $result['id'] ,
                'VAL_ADMINS_LOGIN'			=>   $result['admins_login'],
                'VAL_ADMINS_NAME'			=>   $result['admins_name'],
                'VAL_ADMINS_MOBILE_PHONE'	=>   $result['admins_mobile_phone'],
                'VAL_ADMINS_SHORT_PHONE'	=>   $result['admins_short_phone'],
                'VAL_ADMINS_PASS'			=>   $result['admins_pass'],
                'VAL_ADMINS_ACCESS'			=>   $result['admins_accessgroup'],
                'CHK_ADMINS_ACTIVE'			=>   $result['admins_active'] == 'yes'? 'checked="checked"':'',
                'CHK_ADMINS_MANAGER'		=>   $result['admins_manager'] == 'yes'? 'checked="checked"':'',
            ));

            $this->template->addVar('OUTPUTMAIN', $this->template->parseFile('admins/admin_edit.html', false) );

        } else {
            Session::setFlash("Неправилный Id администратора!");
            Router::redirect('/admin/admins/');
        }
    }

    public function admin_delete()
    {
        if (isset($this->params[0])) {
            $result = $this->model->delete($this->params[0]);
            if ($result) {
                Session::setFlash('Page was deleted!');
            } else {
                Session::setFlash('Error!');
            }
            Router::redirect('/admin/admins/');
        }
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

        $this->template->addVar('OUTPUTMAIN', $this->template->parseFile('admins/admin_login.html', false) );
    }

    public function admin_logout()
    {
        Session::destroy();
        Router::redirect('/admin/');
    }
}