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
}