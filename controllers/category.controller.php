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

//    public function admin_edit()
//    {
//        if ($_POST) {
//            $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
//            $result = $this->model->save($_POST, $id);
//            if ($result) {
//                Session::setFlash('Page was saved');
//            } else {
//                Session::setFlash("Page does not saved");
//            }
//            Router::redirect('/admin/category/');
//        }
//
//        if (isset($this->params[0])) {
//            $this->data['page'] = $this->model->getById($this->params[0]);
//            $this->data['categories'] = $this->model->getAllCategories();
//        } else {
//            Session::setFlash("Wrong page id!");
//            Router::redirect('/admin/category/');
//        }
//    }

    public function admin_index()
    {
        if ($_POST) {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
            $result = $this->model->save($_POST, $id);
            if ($result) {
                Session::setFlash('Page was saved');
            } else {
                Session::setFlash("Page does not saved");
            }
            Router::redirect('/admin/category/index/'.$_POST['id']);
        }

        $this->data['pages'] = $this->model->getList();

        $array_subcategory = array();
        foreach ($this->data['pages'] as $page) {
            if ($page['p_id'] == 1) {
                $array_subcategory[$page['id']] = $page;
            }
        }
        $this->data['subcategories'] = $array_subcategory;
        $this->data['categories'] = $this->model->getAllCategories();
        if (!empty($this->params[0])) {
            $currentCategory = $this->model->getById($this->params[0]);
            $this->data['currentCategory'] = $currentCategory;
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
        Router::redirect('/admin/category/index/'.$this->params[0]);
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
        Router::redirect('/admin/category/index/'.$this->params[0]);
    }
}
