<?php

class PagesController extends Controller
{
    public function __construct(array $data = array())
    {
        parent::__construct($data);
        $this->model = new Page();
    }

    public function index()
    {
        $this->data['pages'] = $this->model->getList();
      //  echo "Here will be pages list";
    }

    public function view()
    {
        //$params = App::getRouter()->getParams();
        if (isset($this->params[0])) {
            $alias = strtolower($this->params[0]);
            $this->data['page'] = $this->model->getByAlias($alias);
        }
    }
}