<?php
/**
 * Created by PhpStorm.
 * User: L K
 * Date: 15.03.2017
 * Time: 10:32
 */

class NewsController extends Controller
{
    public function __construct(array $data = array())
    {
        parent::__construct($data);
        $this->model = new Newses();
    }

    public function admin_index()
    {
        $result = $this->model->getList();
        foreach ($result as $item) {
            $this->template->addBlock('NEWS', array(
                'id'			 =>   $item['id'] ,
                'title'		 =>   $item['title'],
                'ua_title'		 =>   $item['ua_title'],
                'alias'		 =>   $item['alias'],
                'description' =>   $item['description'],
                'ua_description' =>   $item['ua_description'],
                'picture' => $item['picture'],
                'active'         => $item['active'] == 'yes' ? 'yes' : ''
            ));
        }

        $this->template->addVar('OUTPUTMAIN', $this->template->parseFile('news/new_admin_index.html', false) );
        //$this->data['pages'] = $this->model->getList();
    }



    public function admin_add()
    {
        if ($_POST) {
            $result = $this->model->save($_POST);
            if ($result) {
                Session::setFlash('Администратор был успешно создан.');
            } else {
                Session::setFlash('Ошибка!');
            }
            Router::redirect('/admin/admins/');
        }
        $this->template->addVar('OUTPUTMAIN', $this->template->parseFile('news/new_admin_add.html', false) );
    }
}