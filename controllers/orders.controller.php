<?php

class OrdersController extends Controller
{
    public function __construct(array $data = array())
    {
        parent::__construct($data);
        $this->model = new Order();
    }

    public function zakaz()
    {
        $response = array();
        if ($this->is_ajax() && $_POST) {
            $result = $this->model->save($_POST);
            if ($result) {
                $productModel = new Product();
                $productModel->setReserved($_POST['product_id']);
                $response['status'] = 1;
                $response['message'] = "С вами скоро свяжуться!";
                //Session::setFlash('С вами скоро свяжуться!');
            } else {
                $response['status'] = 0;
                $response['message'] = "Ошибка!";
               // Session::setFlash('Ошибка!');
            }

        }

        echo json_encode($response);
        die;

    }

    public function admin_index()
    {
        $data_for_pagination = $this->getDataForPagination();
        $result = $this->model->getList($data_for_pagination['page_start'], $data_for_pagination['page_offset']);

        foreach ($result as $order) {
            $this->template->addBlock('ORDER', $order);
        }

        $this->template->addVar('PAGINATION', $data_for_pagination['markup']);
        $this->template->addVar('OUTPUTMAIN', $this->template->parseFile('orders/admin_index.html', false) );
    }

    public function admin_edit()
    {
        if ($_POST) {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
            $result = $this->model->save($_POST, $id);
        }
        if (isset($this->params[0])) {
            $result = $this->model->getById($this->params[0]);
            $this->template->addVars(array(
                'ORDER_ID'	 =>   $result['id'] ,
                'ORDER_USER_NAME'			=>   $result['user_name'],
                'ORDER_USER_PHONE'			=>   $result['user_phone'],
                'ORDER_PRODUCT_ID'			=>   $result['product_id'],
                'ORDER_STATUS' =>   $result['status'],
                'ORDER_COMMENT' =>   $result['comment']
            ));

            $this->template->addVar('OUTPUTMAIN', $this->template->parseFile('orders/admin_edit.html', false) );
        }
    }
    public function admin_edit1()
    {
        if ($_POST) {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
            $result = $this->model->save($_POST, $id);
            if ($result) {
                Session::setFlash('Данные успешно обновлены.');
            } else {
                Session::setFlash("Ошибка!");
            }
            Router::redirect('/admin/pages/');
        }

        //  нужно доделать, чтобы при несуществующем айдишнике тоже перебрасывало на главную
        if (isset($this->params[0])) {
            //$this->data['admin'] = $this->model->getById($this->params[0]);

            $result = $this->model->getById($this->params[0]);
            $this->template->addVars(array(
                'NEWS_ID'	 =>   $result['id'] ,
                'NEWS_TITLE_RU'			=>   $result['title'],
                'NEWS_TITLE_UA'			=>   $result['ua_title'],
                'NEWS_ALIAS'			=>   $result['alias'],
                'NEWS_DESCRIPTION_RU' =>   $result['description'],
                'NEWS_DESCRIPTION_UA' =>   $result['ua_description'],
                'NEWS_TEXT_RU'	=>   nl2br($result['text']),
                'NEWS_TEXT_UA'	=>   nl2br($result['ua_text']),
                'NEWS_ACTIVE'	=>   $result['active'],
                //'NEWS_PICTURE'	=>   DS.'webroot'.DS.'image'.DS.'logo1.png',
            ));

            $this->template->addVar('OUTPUTMAIN', $this->template->parseFile('news/new_admin_edit.html', false) );

        } else {
            Session::setFlash("Неправилный Id страницы!");
            Router::redirect('/admin/admins/');
        }
    }
}