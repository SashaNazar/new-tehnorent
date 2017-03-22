<?php

class PagesController extends Controller
{
    public function __construct(array $data = array())
    {
        parent::__construct($data);
        $this->model = new Page();
    }

    public function product()
    {
        if (isset($this->params[0])) {

            $productModel = new Product();
            $product = $productModel->getByIdLang($this->params[0], $this->language);

            $categoryModel = new Categories();
            $categoriesAll = $categoryModel->getCategoryForMenu($this->language);

            $breadcrumbs_array = $this->breadcrumbs($categoriesAll, $product['category_id']);
            $breadcrumbsTitleDescription = $this->view_breadcrumbs($breadcrumbs_array, false);

            $array_category = $this->getCategoriesForMenu();
            $categories_menu = $this->view_cat($array_category);

            $pages = $this->model->getListActive($this->language);
            $pages_menu = $this->view_pages($pages);


//            echo "<pre>";
//            var_dump($breadcrumbsTitleDescription);die;
            $breadcrumbsTitleDescription['breadcrumbs'] .= " {$product['title']}";
            //var_dump($breadcrumbsTitleDescription['breadcrumbs']);die;
//            $breadcrumbs = "<a href='" . $this->languageForUrl . "'>{$breadcrumbs_title}</a> | ". $page['title'];
//            $title_text = $page['title'];
//            $infopage_title = $page['title'];
//            $infopage_text = nl2br($page['text']);

            $this->template->addVars(array(
                'PRODUCT_NAME' => $product['name'],
                'PRODUCT_ID' => $product['id'],
                'PRODUCT_PRICE' => $product['price'],
                'PRODUCT_DEPOSIT' => $product['deposit'],
                'PRODUCT_VENDOR' => $product['vendor'],
                'PRODUCT_VENDOR_CODE' => $product['vendor_code'],
                'PRODUCT_PARAMS' => htmlspecialchars_decode($product['params']),
                'PRODUCT_PICTURE_SMALL' => $product['picture_small'],
                'PRODUCT_PICTURE_BIG' => $product['picture'],
                'PRODUCT_DESCRIPTION' => htmlspecialchars_decode($product['description']),


                'TITLE_TEXT' => $breadcrumbsTitleDescription['title_text'],
                'DESCRIPTION_TEXT' => $breadcrumbsTitleDescription['description'],
                'BREADCRUMBS' => $breadcrumbsTitleDescription['breadcrumbs'],
                'CATEGORIES_MENU' => $categories_menu,
                'PAGES_MENU' => $pages_menu
            ));

            $this->template->addVar('OUTPUTMAIN', $this->template->parseFile('pages/new_product.html', false) );
        }
    }

    public function category()
    {
        if (isset($this->params[0])) {

            $category_id = (int)$this->params[0];
            $categoryModel = new Categories();
            $categoriesAll = $categoryModel->getCategoryForMenu($this->language);

            $breadcrumbs_array = $this->breadcrumbs($categoriesAll, $category_id);
            $breadcrumbsTitleDescription = $this->view_breadcrumbs($breadcrumbs_array);

            $array_category = $this->getCategoriesForMenu();
            $categories_menu = $this->view_cat($array_category, 1, $category_id);

            $pages = $this->model->getListActive($this->language);
            $pages_menu = $this->view_pages($pages);

            $products = $categoryModel->getProductsByCategory($category_id, $this->language);

            $lang = (App::getRouter()->getLanguage() == 'ua') ? '' : DS.App::getRouter()->getLanguage();

            foreach ($products as $item) {
                $this->template->addBlock('PRODUCTS', array(
                    'LANG' => $lang,
                    'items_id'			=>   $item['id'] ,
                    'items_name'			=>   $item['name'],
                    'items_params'			=>   htmlspecialchars_decode($item['params']),
                    'items_picture'			=>   $item['picture'],
                    'items_typePrefix'			=>   $item['title'],
                    'items_picture_sm'			=>   $item['picture_small'],
                    'items_vendor'			=>   $item['vendor'],
                    'items_vendor_code'			=>   $item['vendor_code'],
                    'items_price'			=>   $item['price'],
                    'items_deposit'			=>   $item['deposit'],
                ));
            }
            //$this->template->addVar('OUTPUTMAIN', $this->template->parseFile('admins/new_admin_index.html', false) );


            $this->template->addVars(array(
                'TITLE_TEXT' => $breadcrumbsTitleDescription['title_text'],
                'DESCRIPTION_TEXT' => $breadcrumbsTitleDescription['description'],
                'BREADCRUMBS' => $breadcrumbsTitleDescription['breadcrumbs'],
                'CATEGORIES_MENU' => $categories_menu,
                'PAGES_MENU' => $pages_menu
            ));

            $this->template->addVar('OUTPUTMAIN', $this->template->parseFile('pages/new_categories.html', false) );

        } else {
            Router::redirect($this->languageForUrl.'/pages/');
        }

    }

    protected function view_breadcrumbs($breadcrumbs_array, $deleteLastLink = true)
    {
        $breadcrumbs_title = "Главная";
        $array_response = array();
        if ($this->language == 'ua') {
            $breadcrumbs_title = "Головна";
        }
        $langUrl = $this->languageForUrl == '' ? '/' : $this->languageForUrl;
        if($breadcrumbs_array){
            $breadcrumbs = "<a href='" . $langUrl . "'>{$breadcrumbs_title}</a> | ";
            foreach($breadcrumbs_array as $id => $title){
                $breadcrumbs .= "<a href='" . $this->languageForUrl . "/pages/category/{$id}'>{$title}</a> | ";
                $title_text = $title.". ";
                $description = $title.": ";
            }
            if ($deleteLastLink) {
                $breadcrumbs = rtrim($breadcrumbs, " | ");
                $breadcrumbs = preg_replace("#(.+)?<a.+>(.+)</a>$#", "$1$2", $breadcrumbs);
            }
        }else{
            $breadcrumbs = "<a href='" . $this->languageForUrl . "/pages/'>{$breadcrumbs_title}</a> | Каталог";
        }

        $array_response['breadcrumbs'] = $breadcrumbs;
        $array_response['title_text'] = $title_text;
        $array_response['description'] = $description;

        return $array_response;
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

    public function infopage()
    {
        if (isset($this->params[0])) {
            $breadcrumbs_title = "Главная";
            if ($this->language == 'ua') {
                $breadcrumbs_title = "Головна";
            }

            $pagesModel = new Page();
            $page = $pagesModel->getById($this->params[0]);

            $array_category = $this->getCategoriesForMenu();
            $categories_menu = $this->view_cat($array_category);

            $breadcrumbs = "<a href='" . $this->languageForUrl . "'>{$breadcrumbs_title}</a> | ". $page['title'];

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
        $title_text = "Прокат електроінструментів і обладнання у Києві і області.";
        if ($this->language == 'ru') {
            $title_text = "Прокат электроинструментов и оборудования в Киеве и области.";
        }

        $array_category = $this->getCategoriesForMenu();
        $categories_menu = $this->view_cat($array_category);

        $pages = $this->model->getListActive($this->language);
        $pages_menu = $this->view_pages($pages);

        $this->template->addVars(array(
            'TITLE_TEXT' => $title_text,
            'CATEGORIES_MENU' => $categories_menu,
            'PAGES_MENU' => $pages_menu
        ));

        if ($this->params[0] && is_string($this->params[0])) {

            $breadcrumbs_title = "Главная";
            if ($this->language == 'ua') {
                $breadcrumbs_title = "Головна";
            }

            $current_page = $this->model->getByAlias($this->params[0], $this->language);

            if (!$current_page) {
                Router::redirect('/'.$this->language);
            }

            $langUrl = $this->languageForUrl == '' ? '/' : $this->languageForUrl;

            $breadcrumbs = "<a href='" . $langUrl . "' >{$breadcrumbs_title}</a> | ". $current_page['title'];

            $this->template->addVars(array(
                'INFOPAGE_TITLE' => $current_page['title'],
                'INFOPAGE_TEXT' => nl2br($current_page['text']),
                'BREADCRUMBS' => $breadcrumbs
            ));
            $this->template->addVar('OUTPUTMAIN', $this->template->parseFile('pages/new_infopage.html', false) );

        } else {
            $this->template->addVar('OUTPUTMAIN', $this->template->parseFile('pages/new_index.html', false) );
        }

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

    protected function view_pages($array)
    {
        $begin = '<p>';
        $array_size = count($array);

        foreach ($array as $key => $item) {
            if ($key === $array_size-1) {
                $begin .= "<a href='" . $this->languageForUrl ."/pages/index/" . $item['alias'] . "'>" . $item['title'] . "</a>";
            } else {
                $begin .= "<a href='" . $this->languageForUrl ."/pages/index/" . $item['alias'] . "'>" . $item['title'] . "</a> | ";
            }
        }
        $begin .= '</p>';
        return $begin;

    }

    protected function view_cat($arr,$parent_id = 1, $current_category = false) {
        //Условия выхода из рекурсии
        if(empty($arr[$parent_id])) {
            return;
        }
        $title_menu = '<strong>Электроинструменты<br>напрокат</strong>';
        if ($this->language == 'ua') {
            $title_menu = '<strong>Електроінструменти<br>напрокат</strong>';
        }

        if ($parent_id == 1) {
            $str = "<ul class='category'><div class='tmen'>{$title_menu}</div>";
        } else {
            $str =  '<ul style="display: none;">';
        }
        //перебираем в цикле массив и выводим на экран
        for($i = 0; $i < count($arr[$parent_id]);$i++) {
            //$str .= '<li><a href="/' . $this->language . '/pages/category/'.$arr[$parent_id][$i]['id'].'">'.$arr[$parent_id][$i]['name'].'</a>';

            if ($current_category && $current_category == $arr[$parent_id][$i]['id']) {
                $str .= '<li><a href="' . $this->languageForUrl . '/pages/category/'.$arr[$parent_id][$i]['id'].'"><strong>'.$arr[$parent_id][$i]['name'].'</strong></a>';
            } else {
                $str .= '<li><a href="' . $this->languageForUrl . '/pages/category/' . $arr[$parent_id][$i]['id'] . '">' . $arr[$parent_id][$i]['name'] . '</a>';
            }

            //рекурсия - проверяем нет ли дочерних категорий
            $str .= $this->view_cat($arr,$arr[$parent_id][$i]['id'], $current_category);
            $str .= '</li>';
        }

        $str .= '</ul>';

        return $str;
    }

    public function admin_index()
    {
        $result = $this->model->getList();
        foreach ($result as $item) {
            $this->template->addBlock('PAGES', array(
                'page_id'	=>   $item['id'] ,
                'title'		 =>   $item['title'],
                'alias'		 =>   $item['alias'],
                'description' =>   $item['description'],
                'keywords' =>   $item['keywords'],
                'text' => nl2br($item['text']),
                'position' => $item['position']
            ));
        }

        $this->template->addVar('OUTPUTMAIN', $this->template->parseFile('pages/new_admin_index.html', false) );
    }

    public function admin_add()
    {
        if ($_POST) {
            $result = $this->model->save($_POST);
            if ($result) {
                Session::setFlash('Страница была успешно создана.');
            } else {
                Session::setFlash('Ошибка!');
            }
            Router::redirect('/admin/pages/');
        }
        $this->template->addVar('OUTPUTMAIN', $this->template->parseFile('pages/new_admin_add.html', false) );
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
            Router::redirect('/admin/pages/');
        }

        //  нужно доделать, чтобы при несуществующем айдишнике тоже перебрасывало на главную
        if (isset($this->params[0])) {
            //$this->data['admin'] = $this->model->getById($this->params[0]);

            $result = $this->model->getById($this->params[0]);
            $this->template->addVars(array(
                'PAGE_ID'	 =>   $result['id'] ,
                'PAGE_TITLE_RU'			=>   $result['title'],
                'PAGE_TITLE_UA'			=>   $result['ua_title'],
                'PAGE_ALIAS'			=>   $result['alias'],
                'PAGE_DESCRIPTION_RU' =>   $result['description'],
                'PAGE_DESCRIPTION_UA' =>   $result['ua_description'],
                'PAGE_KEYWORDS_RU'			=>   $result['keywords'],
                'PAGE_KEYWORDS_UA'			=>   $result['ua_keywords'],
                'PAGE_TEXT_RU'	=>   nl2br($result['text']),
                'PAGE_TEXT_UA'	=>   nl2br($result['ua_text']),
                'PAGE_POSITION'	=>   $result['position']
            ));

            $this->template->addVar('OUTPUTMAIN', $this->template->parseFile('pages/new_admin_edit.html', false) );

        } else {
            Session::setFlash("Неправилный Id страницы!");
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
            Router::redirect('/admin/pages/');
        }
    }
}