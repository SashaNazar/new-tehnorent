<?php

class PagesController extends Controller
{
    public function index()
    {
        $this->data['test_content'] = "Hello world";
      //  echo "Here will be pages list";
    }

    public function view()
    {
        //$params = App::getRouter()->getParams();
        if (isset($this->params[0])) {
            $alias = strtolower($this->params[0]);
            $this->data['content'] = "Here will be page ".$alias;
        }
    }
}