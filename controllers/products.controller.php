<?php

class ProductsController extends Controller
{
    public function __construct(array $data = array())
    {
        parent::__construct($data);
        $this->model = new Product();
    }


    public function admin_index()
    {
        $dataForSearch = array();
        $status = 'all';
        $products_statuses = array('yes', 'no');
        $search = '?' . $_SERVER['QUERY_STRING'];

        $dataForSort = array(
            'field' => 'id',
            'by' => 'ASC'
        );

        //какие продукты показывать
        if (isset($this->params[0])) {
            if ($this->params[0] !== 'all') {
                $status_products = (int)$this->params[0];
                $key = array_search($status_products, $products_statuses);
                $status = ($key === false) ? 'all' : $products_statuses[$key];
            }
        }

        /// Условия сортировки
        if (isset($this->params[1])) {
            $fieldSort = array('id', 'title', 'ua_title', 'price', 'deposit', 'category_id', 'active');
            $bySort = array('ASC', 'DESC');

            $sortParams = explode('&', $this->params[1]);

            if (in_array($sortParams[0], $fieldSort)) {
                $dataForSort['field'] = $sortParams[0];
            }
            if (in_array($sortParams[1], $bySort)) {
                $dataForSort['by'] = $sortParams[1];
            }
        }

        if (!empty($_GET)) {
            $condition = array(
                'id' => '',
                'title' => '',
                'ua_title' => '',
                'price' => '',
                'deposit' => '',
                'category_id' => '',
                'active' => ''
            );

            $filter = array_intersect_key($_GET, $condition);
            $filter = array_filter($filter);
            $filter = array_map('trim', $filter);
            $dataForSearch = array_merge($dataForSearch, $filter);

            $total_records = $this->model->getTotalCountWithCondition($dataForSearch);
            $data_for_pagination = $this->getDataForPagination($total_records);
            $result = $this->model->getListWithCondition($data_for_pagination['page_start'], $data_for_pagination['page_offset'], $dataForSort, $status, $dataForSearch);

        } else {
            $total_records = $this->model->getTotalCountWithCondition($dataForSearch);
            $data_for_pagination = $this->getDataForPagination($total_records);
            $result = $this->model->getListWithCondition($data_for_pagination['page_start'], $data_for_pagination['page_offset'], $dataForSort, $status, $dataForSearch);
        }

//        $data_for_pagination = $this->getDataForPagination();
//
//        $result = $this->model->getList($data_for_pagination['page_start'], $data_for_pagination['page_offset'], true);

        foreach ($result as $item) {
            $this->template->addBlock('PRODUCTS', array(
                'id'	=>   $item['id'] ,
                'title'		 =>   $item['title'],
                'ua_title'		 =>   $item['ua_title'],
                'picture_small'		 =>   $item['picture_small'],
                'price' =>   $item['price'],
                'deposit' =>   $item['deposit'],
                'category_name' =>   $item['category_name'],
                'active' => $item['active'],
            ));
        }

        $this->template->addVars(array(
            'PAGINATION' => $data_for_pagination['markup'] ,
            'STATUS' => $status,
            'SEARCH' => $search,
            'TOTAL' => $total_records,
            'QUERY' => json_encode($_GET)
        ));

        $this->template->addVar('OUTPUTMAIN', $this->template->parseFile('products/admin_index.html', false) );
    }

    public function admin_add()
    {
        if ($_POST) {

            $result = $this->model->save($_POST);

            if ($_FILES && $_FILES['picture']['size'] > 0 && $result) {

                $file_name = (int)$result;
                $image = new Image_upload();
                $image->load($_FILES['picture']['tmp_name']);
                $image->resize(100, 100);
                $small_image = $image->save(DS."webroot".DS."image".DS."products".DS."{$file_name}_small.png");
                $image->load($_FILES['picture']['tmp_name']);
                $image->resize(500, 500);
                $big_image = $image->save(DS."webroot".DS."image".DS."products".DS."{$file_name}_big.png");

                $this->model->saveImageForProduct($file_name, $big_image, $small_image);
            }

            if ($result) {
                Session::setFlash('Продукт был успешно создан.');
            } else {
                Session::setFlash('Ошибка!');
            }
            Router::redirect('/admin/products/');
        }

        $categoryModel = new Categories();
        $categories = $categoryModel->getList();
        foreach ($categories as $category) {
            $this->template->addBlock('ALL_CATEGORIES', array(
                'id' => $category['id'] ,
                'name' => $category['name'],
            ));
        }

        $this->template->addVar('OUTPUTMAIN', $this->template->parseFile('products/admin_add.html', false) );
    }

    public function admin_edit()
    {
        if ($_POST) {
            $id = isset($_POST['id']) ? (int)$_POST['id'] : null;
            $result = $this->model->save($_POST, $id);

            if ($_FILES && $_FILES['picture']['size'] > 0 && $result) {
                $file_name = (int)$result;
                $image = new Image_upload();
                $image->load($_FILES['picture']['tmp_name']);
                $image->resize(100, 100);
                $small_image = $image->save(DS."webroot".DS."image".DS."products".DS."{$file_name}_small.png");
                $image->load($_FILES['picture']['tmp_name']);
                $image->resize(500, 500);
                $big_image = $image->save(DS."webroot".DS."image".DS."products".DS."{$file_name}_big.png");

                $this->model->saveImageForProduct($file_name, $big_image, $small_image);
            }

            if ($result) {
                Session::setFlash('Данные успешно обновлены.');
            } else {
                Session::setFlash("Ошибка!");
            }
            Router::redirect('/admin/products/');
        }

        //  нужно доделать, чтобы при несуществующем айдишнике тоже перебрасывало на главную
        if (isset($this->params[0])) {
            //$this->data['admin'] = $this->model->getById($this->params[0]);

            $result = $this->model->getById($this->params[0]);
            $this->template->addVars(array(
                'PRODUCT_ID'	 =>   $result['id'] ,
                'PRODUCT_TITLE_RU'			=>   $result['title'],
                'PRODUCT_TITLE_UA'			=>   $result['ua_title'],
                'PRODUCT_NAME_RU'			=>   $result['name'],
                'PRODUCT_NAME_UA'			=>   $result['ua_name'],
                'PRODUCT_DESCRIPTION_RU' =>   $result['description'],
                'PRODUCT_DESCRIPTION_UA' =>   $result['ua_description'],
                'PRODUCT_PARAMS_RU' =>   $result['params'],
                'PRODUCT_PARAMS_UA' =>   $result['ua_params'],
                'PRODUCT_PRICE' =>   $result['price'],
                'PRODUCT_DEPOSIT' =>   $result['deposit'],
                'PRODUCT_VENDOR' =>   $result['vendor'],
                'PRODUCT_VENDOR_CODE' =>   $result['vendor_code'],
                'PRODUCT_PICTURE'	=>   $result['picture_small'],
                'PRODUCT_ACTIVE'	=>   $result['active'] == 'yes' ? "checked=checked" : '',
            ));

            $categoryModel = new Categories();
            $categories = $categoryModel->getList();
            foreach ($categories as $category) {
                $selected = $result['category_id'] == $category['id'] ? "selected='selected'" : "";
                $this->template->addBlock('ALL_CATEGORIES', array(
                    'id' => $category['id'] ,
                    'name' => $category['name'],
                    'selected' => $selected,
                ));
            }

            $this->template->addVar('OUTPUTMAIN', $this->template->parseFile('products/admin_edit.html', false) );

        } else {
            Session::setFlash("Неправилный Id страницы!");
            Router::redirect('/admin/products/');
        }
    }

    public function admin_delete()
    {
        if (isset($this->params[0])) {
            $this->model->deleteImageForProduct($this->params[0]);
            $result = $this->model->delete($this->params[0]);
            if ($result) {
                Session::setFlash('Page was deleted!');
            } else {
                Session::setFlash('Error!');
            }
            Router::redirect('/admin/products/');
        }
    }

    public function admin_deleteImage()
    {
        if (isset($this->params[0])) {
            $result = $this->model->deleteImageForProduct($this->params[0]);
            if ($result) {
                Session::setFlash('Картинка удалена');
            } else {
                Session::setFlash("Ошибка!");
            }
            Router::redirect('/admin/products/edit/'.$this->params[0]);
        } else {
            Router::redirect('/admin/products/');
        }
    }
}