<?php

class PagesController extends Controller
{
    public function __construct(array $data = array())
    {
        parent::__construct($data);
        $this->model = new Page();
    }

    public function category()
    {
        if (isset($this->params[0])) {
            $category_id = (int)$this->params[0];
            $categoryModel = new Categories();
            $categoriesAll = $categoryModel->getCategoryForMenu($this->language);

            $breadcrumbs_array = $this->breadcrumbs($categoriesAll, $category_id);

            if($breadcrumbs_array){
                $breadcrumbs = "<a href='/'>Главная</a> | ";
                foreach($breadcrumbs_array as $id => $title){
                    $breadcrumbs .= "<a href='/" . $this->language . "/pages/category/{$id}'>{$title}</a> | ";
                    $title_text = $title.". ";
                    $description = $title.": ";
                }
                $breadcrumbs = rtrim($breadcrumbs, " | ");
                $breadcrumbs = preg_replace("#(.+)?<a.+>(.+)</a>$#", "$1$2", $breadcrumbs);
            }else{
                $breadcrumbs = "<a href='/" . $this->language . "/pages/'>Главная</a> | Каталог";
            }

            $array_category = $this->getCategoriesForMenu();
            $categories_menu = $this->view_cat($array_category);


            $this->template->addVars(array(
                'TITLE_TEXT' => $title_text,
                'DESCRIPTION_TEXT' => $description,
                'BREADCRUMBS' => $breadcrumbs,
                'CATEGORIES_MENU' => $categories_menu
            ));

            $this->template->addVar('OUTPUTMAIN', $this->template->parseFile('pages/new_categories.html', false) );

        }

    }

    /**
     * Хлебные крошки
     **/
    protected function breadcrumbs($array, $id){
        if(!$id) return false;

        $count = count($array);
        $breadcrumbs_array = array();
        for($i = 0; $i < $count; $i++){
            if($array[$id]){
                $breadcrumbs_array[$array[$id]['id']] = htmlspecialchars_decode($array[$id]['name']);
                $id = $array[$id]['p_id'];
            }else break;
        }

        return array_reverse($breadcrumbs_array, true);
    }

    /**
     * Получение ID дочерних категорий
     **/
    protected function cats_id($array, $id){
        if(!$id) return false;

        foreach($array as $item){
            if($item['p_id'] == $id){
                $data .= $item['id'] . ",";
                $data .= $this->cats_id($array, $item['id']);
            }
        }
        return $data;
    }

    public function oldCategory()
    {
        if(isset($_GET['category'])){
            $id = (int)$_GET['category'];
            // хлебные крошки
            // return true (array not empty) || return false
            $breadcrumbs_array = breadcrumbs($categories, $id);

            if($breadcrumbs_array){
                $breadcrumbs = "<a href='/'>Главная</a> | ";
                foreach($breadcrumbs_array as $id => $title){
                    $breadcrumbs .= "<a href='?category={$id}'>{$title}</a> | ";
                    $title_text = $title.". ";
                    $description = $title.": ";
                }
                $breadcrumbs = rtrim($breadcrumbs, " | ");
                $breadcrumbs = preg_replace("#(.+)?<a.+>(.+)</a>$#", "$1$2", $breadcrumbs);
            }else{
                $breadcrumbs = "<a href='/" . $this->language ."/catalog/'>Главная</a> | Каталог";
            }
            // ID дочерних категорий
            $ids = cats_id($categories, $id);
            $ids = !$ids ? $id : rtrim($ids, ",");

            if($ids){
                $products = get_products($ids);
                $pagination = get_products_pagination($ids);
            } else {
                $products = null;
                $pagination = null;
            }
        }else{
            $products = get_products();
            $pagination = get_products_pagination($ids);
        }
        foreach($products as $product){
            $description.= $product['items_vendor']. " ". $product['items_vendorCode'].". ";
        }
    }

    public function infopage()
    {
        if (isset($this->params[0])) {
            $pagesModel = new Page();
            $page = $pagesModel->getById($this->params[0]);

            $array_category = $this->getCategoriesForMenu();
            $categories_menu = $this->view_cat($array_category);

            $breadcrumbs = "<a href='/'" . $this->language. " >Главная</a> | ". $page['title'];

            $title_text = $page['title'];
            $infopage_title = $page['title'];
            $infopage_text = nl2br($page['text']);

            $this->template->addVars(array(
                'TITLE_TEXT' => $title_text,
                'BREADCRUMBS' => $breadcrumbs,
                'CATEGORIES_MENU' => $categories_menu,
                'INFOPAGE_TITLE' => $infopage_title,
                'INFOPAGE_TEXT' => $infopage_text,
            ));

            $this->template->addVar('OUTPUTMAIN', $this->template->parseFile('pages/new_infopage.html', false) );
        }
    }

    public function index()
    {
        $title_text = "Прокат электроинструментов и оборудования в Киеве и области.";

        $array_category = $this->getCategoriesForMenu();
        $categories_menu = $this->view_cat($array_category);

        $this->template->addVars(array(
            'TITLE_TEXT' => $title_text,
            'CATEGORIES_MENU' => $categories_menu
        ));
        $this->template->addVar('OUTPUTMAIN', $this->template->parseFile('pages/new_index.html', false) );

        //$this->data['pages'] = $this->model->getList();
      //  echo "Here will be pages list";
    }

    protected function getCategoriesForMenu()
    {
        $modelCategory = new Categories();
        $categories = $modelCategory->getListActive($this->language);
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
        return $array_category;
    }

    protected function view_cat($arr,$parent_id = 1) {
        //Условия выхода из рекурсии
        //var_dump($arr);die;
        if(empty($arr[$parent_id])) {
            return;
        }
        if ($parent_id == 1) {
            $str = '';
        } else {
            $str =  '<ul style="display: none;">';
        }
        //перебираем в цикле массив и выводим на экран
        for($i = 0; $i < count($arr[$parent_id]);$i++) {
            $str .= '<li><a href="/' . $this->language . '/pages/category/'.$arr[$parent_id][$i]['id'].'">'.$arr[$parent_id][$i]['name'].'</a>';

            //рекурсия - проверяем нет ли дочерних категорий
            $str .= $this->view_cat($arr,$arr[$parent_id][$i]['id']);
            $str .= '</li>';
        }
        if ($parent_id != 1) {
            $str .= '</ul>';
        }

        return $str;
    }

    public function admin_index()
    {
        $result = $this->model->getList();
        foreach ($result as $item) {
            $this->template->addBlock('PAGES', array(
                'page_id'			 =>   $item['page_id'] ,
                'title'		 =>   $item['title'],
                'alias'		 =>   $item['alias'],
                'description' =>   $item['description'],
                'keywords' =>   $item['keywords'],
                'text' => $item['text'],
                'position' => $item['position']
            ));
        }

        $this->template->addVar('OUTPUTMAIN', $this->template->parseFile('pages/new_admin_index.html', false) );


        $this->data['pages'] = $this->model->getList();
    }

    public function admin_add()
    {
        if ($_POST) {
           $result = $this->model->save($_POST);
           if ($result) {
               Session::setFlash('Page was saved!');
           } else {
               Session::setFlash('Error!');
           }
           Router::redirect('/admin/pages/');
        }
    }

    public function admin_edit()
    {
        if ($_POST) {
            $id = isset($_POST['id']) ? $_POST['id'] : null;
            $result = $this->model->save($_POST, $id);
            if ($result) {
                Session::setFlash('Page was saved!');
            } else {
                Session::setFlash('Error!');
            }
            Router::redirect('/admin/pages/');
        }

        //нужно доделать, чтобы при несуществующем айдишнике тоже перебрасывало на главную
        if (isset($this->params[0])) {
            $this->data['page'] = $this->model->getById($this->params[0]);

            if (!$this->data['page']) {
                Session::setFlash('Wrong page id.');
                Router::redirect('/admin/pages/');
            }

        } else {
            Session::setFlash('Wrong page id.');
            Router::redirect('/admin/pages/');
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
            Router::redirect('/admin/pages/');
        }
    }
}