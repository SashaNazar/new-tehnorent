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
        $lang = 'ua';
        $messages_array = array(
            'ua' => array(
                'success' => "Дякуєм за замовлення. Вам зателефонують на вказаний Вами номер.",
                'error' => "Некоректно введені дані."
            ),
            'ru' => array(
                'success' => "Спасибо за заказ. Вам перезвонят на указанный Вами номер.",
                'error' => "Некорректно введенные данные."
            )
        );

        $response = array();
        if ($this->is_ajax() && $_POST) {
            if (!empty($_POST['lang']) && $_POST['lang'] === 'ru') {
                $lang = 'ru';
            }
            $result = $this->model->save($_POST);
            if ($result) {
                $productModel = new Product();
                $productModel->setReserved($_POST['product_id']);

                $userModel = new User();
                $userModel->setUser($_POST['user_name'], $_POST['user_phone']);

                $response['status'] = 1;
                $response['message'] = $messages_array[$lang]['success'];
            } else {
                $response['status'] = 0;
                $response['message'] = $messages_array[$lang]['error'];
            }

        }

        echo json_encode($response);
        die;

    }

    public function admin_index()
    {
        $dataForSearch = array();
        $status = 'all';
        $all_statuses_orders = array(1, 2, 3, 4, 5);
        $search = '?' . $_SERVER['QUERY_STRING'];

        $dataForSort = array(
            'field' => 'id',
            'by' => 'ASC'
        );
        //какие заказы показывать
        if (isset($this->params[0])) {
            if ($this->params[0] !== 'all') {
                $status_order = (int)$this->params[0];
                $key = array_search($status_order, $all_statuses_orders);
                $status = ($key === false) ? 'all' : $all_statuses_orders[$key];
            }
        }

        if (isset($this->params[1])) {
            $fieldSort = array('id', 'user_name', 'user_phone', 'product_id', 'status', 'comment', 'created', 'updated');
            $bySort = array('ASC', 'DESC');

            $sortParams = explode('&', $this->params[1]);

            if (in_array($sortParams[0], $fieldSort)) {
                $dataForSort['field'] = $sortParams[0];
            }
            if (in_array($sortParams[1], $bySort)) {
                $dataForSort['by'] = $sortParams[1];
            }
        }

        if ($status !== 'all' && is_integer($status)) {
            $dataForSearch['status'] = $status;
        }

        if (!empty($_GET)) {
            $condition = array(
                'id' => '',
                'user_name' => '',
                'user_phone' => '',
                'product_id' => '',
                'status' => '',
                'created' => '',
                'updated' => '',
                'start_rent' => '',
                'end_rent' => ''
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

        foreach ($result as $order) {
            $this->template->addBlock('ORDER', $order);
        }

        $this->template->addVars(array(
            'PAGINATION' => $data_for_pagination['markup'] ,
            'STATUS' => $status,
            'SEARCH' => $search,
            'TOTAL' => $total_records,
            'QUERY' => json_encode($_GET)
        ));
        $this->template->addVar('OUTPUTMAIN', $this->template->parseFile('orders/admin_index.html', false) );
    }

    public function admin_delete()
    {
        if (isset($this->params[0])) {
            $result = $this->model->delete($this->params[0]);
            if ($result) {
                Session::setFlash('Order was deleted!');
            } else {
                Session::setFlash('Error!');
            }
            Router::redirect('/admin/orders/');
        }
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

            $statusModel = new Status();
            $statuses = $statusModel->getAllStatus();
            foreach ($statuses as $status) {
                $status['selected'] = ($status['id'] === $result['status']) ? 'selected' : '';
                $this->template->addBlock('STATUS', $status);
            }

            $this->template->addVar('OUTPUTMAIN', $this->template->parseFile('orders/admin_edit.html', false) );
        }
    }
}