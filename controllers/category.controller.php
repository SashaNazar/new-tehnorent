<?php

class CategoryController extends Controller
{
    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->model = new Categories();
    }

    public function index()
    {
        if ($_POST) {
            if ($this->model->save($_POST)) {
                Session::setFlash("Thank you! Your message was sent successfully");
            }
        }
    }

    public function admin_index()
    {
        $this->data['pages'] = $this->model->getList();
//        var_dump('<pre>');
//        var_dump($this->data['pages']);die;
    }

    public function admin_edit()
    {
        if ($_POST) {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
            $result = $this->model->save($_POST, $id);
            if ($result) {
                Session::setFlash('Page was saved');
            } else {
                Session::setFlash("Page does not saved");
            }
            Router::redirect('/admin/category/');
        }

        if (isset($this->params[0])) {
            $this->data['page'] = $this->model->getById($this->params[0]);
            $this->data['categories'] = $this->model->getAllCategories();
//            var_dump('<pre>');
//            var_dump($this->data['categories']);die;
        } else {
            Session::setFlash("Wrong page id!");
            Router::redirect('/admin/category/');
        }
    }
}
