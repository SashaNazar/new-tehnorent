<?php

class CategoryController extends Controller
{
    public function __construct($data = array())
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

    public function admin_add()
    {
        if ($_POST) {
            $result = $this->model->save($_POST);
            if ($result) {
                Session::setFlash('Категория была успешно создана.');
            } else {
                Session::setFlash('Ошибка!');
            }
            Router::redirect('/admin/category/');
        }
        $categories = $this->model->getList();
        foreach ($categories as $category) {
            $this->template->addBlock('ALL_CATEGORIES', array(
                'id' => $category['id'] ,
                'name' => $category['name'],
            ));
        }
        $this->template->addVar('OUTPUTMAIN', $this->template->parseFile('category/admin_add.html', false) );
    }

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

        $categories = $this->model->getList();
        if (!$categories) {
            return null;
        }
        $array_category = array();
        foreach ($categories as $category) {
            //Формируем массив, где ключами являются адишники на родительские категории
            if(empty($array_category[$category['p_id']])) {
                $array_category[$category['p_id']] = array();
            }
            $array_category[$category['p_id']][] = $category;
        }

        $categories_menu = $this->view_cat($array_category);



        if (!empty($this->params[0])) {
            $currentCategory = $this->model->getById($this->params[0]);

            $this->template->addVars(array(
                'CURRENT_CATEGORY_ID' => $currentCategory['id'],
                'CURRENT_CATEGORY_P_ID' => $currentCategory['p_id'],
                'CURRENT_CATEGORY_NAME' => $currentCategory['name'],
                'CURRENT_CATEGORY_UA_NAME' => $currentCategory['ua_name'],
                'CURRENT_CATEGORY_SEO' => $currentCategory['seo'],
                'CURRENT_CATEGORY_UA_SEO' => $currentCategory['ua_seo'],
                'CURRENT_CATEGORY_ACTIVE' => $currentCategory['active'] == 'yes' ? "checked='checked'" : "",
            ));
        }

        foreach ($categories as $category) {
            $selected = $currentCategory['p_id'] == $category['id'] ? "selected='selected'" : "";
            $this->template->addBlock('ALL_CATEGORIES', array(
                'id' => $category['id'] ,
                'name' => $category['name'],
                'selected' => $selected,
            ));
        }



        $this->template->addVar('CATEGORIES_MENU', $categories_menu);
        $this->template->addVar('OUTPUTMAIN', $this->template->parseFile('category/admin_index.html', false) );

    }

    //вывод каталога с помощью рекурсии
    protected function view_cat($arr,$parent_id = 1) {
        //Условия выхода из рекурсии
        //var_dump($arr);die;
        if(empty($arr[$parent_id])) {
            return;
        }
        $str =  '<ul>';
        //перебираем в цикле массив и выводим на экран
        for($i = 0; $i < count($arr[$parent_id]);$i++) {
            $str .= '<li><a href="/admin/category/index/'.$arr[$parent_id][$i]['id'].'">'.$arr[$parent_id][$i]['name'].'</a>';

            if ($arr[$parent_id][$i]['active'] == 'yes') {
                $str .= ' | <a class="text-danger" href="/admin/category/unactive/' . $arr[$parent_id][$i]['id'] . '">Деактивировать</a>';
            } else {
                $str .= ' | <a class="text-success" href="/admin/category/active/' . $arr[$parent_id][$i]['id'] . '">Активировать</a>';
            }

            //рекурсия - проверяем нет ли дочерних категорий
            $str .= $this->view_cat($arr,$arr[$parent_id][$i]['id']);
            $str .= '</li>';
        }
        $str .= '</ul>';
        return $str;
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
