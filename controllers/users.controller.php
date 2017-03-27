<?php

//Класс-контроллер для работы с пользователями
class UsersController extends Controller
{
    public function __construct(array $data = array())
    {
        parent::__construct($data);
        $this->model = new User();
    }

    public function admin_index()
    {
        $dataForSort = array(
            'field' => 'id',
            'by' => 'ASC'
        );
        if (isset($this->params[0])) {
            $fieldSort = array('id', 'user_name', 'user_phone');
            $bySort = array('ASC', 'DESC');

            $sortParams = explode('&', $this->params[0]);

            if (in_array($sortParams[0], $fieldSort)) {
                $dataForSort['field'] = $sortParams[0];
            }
            if (in_array($sortParams[1], $bySort)) {
                $dataForSort['by'] =$sortParams[1];
            }
        }

        if (isset($_GET['user_name']) && isset($_GET['user_phone'])) {

            $condition = array();
            if ($_GET['user_name']) {
                $condition['user_name'] = $_GET['user_name'];
            }
            if ($_GET['user_phone']) {
                $condition['user_phone'] = $_GET['user_phone'];
            }
            $total_records = $this->model->getTotalCountWithCondition($condition);

            $data_for_pagination = $this->getDataForPagination($total_records);
            $result = $this->model->getListWithCondition($condition, $data_for_pagination['page_start'], $data_for_pagination['page_offset']);
        } else {
            $data_for_pagination = $this->getDataForPagination();
            $result = $this->model->getList($data_for_pagination['page_start'], $data_for_pagination['page_offset'], $dataForSort);
        }


        foreach ($result as $user) {
            $this->template->addBlock('USER', $user);
        }

        $this->template->addVar('PAGINATION', $data_for_pagination['markup']);
        $this->template->addVar('OUTPUTMAIN', $this->template->parseFile('users/admin_index.html', false) );
    }

    public function admin_delete()
    {
        if (isset($this->params[0])) {
            $result = $this->model->delete($this->params[0]);
            if ($result) {
                Session::setFlash('User was deleted!');
            } else {
                Session::setFlash('Error!');
            }
            Router::redirect('/admin/users/');
        }
    }
//    public function admin_login()
//    {
//        if ($_POST && isset($_POST['login']) && isset($_POST['password'])) {
//            $user = $this->model->getByLogin($_POST['login']);
//            $hash = md5(Config::get('hash').$user['password']);
//
//            if ($user && $user['is_active'] && $user['password'] === $hash) {
//                Session::set('login', $user['login']);
//                Session::set('role', $user['role']);
//            }
//
//        }
//    }
//
//    public function admin_logout()
//    {
//        Session::destroy();
//        Router::redirect('/admin/');
//    }
}