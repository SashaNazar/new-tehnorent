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
        $page = 1;
        $per_page = $page_offset = 5;
        $page_start = 0;
        $total_records = $this->model->getTotalCount();
        // determine page (based on <_GET>)
        if (isset($_GET) && isset($_GET['page'])) {
            $page = (int)$_GET['page'];
            if ($page > 1) {
                $page_start = ($page - 1) * $page_offset;
            }
        }

        // instantiate; set current page; set number of records
        $pagination = new Pagination();
        $pagination->setCurrent($page);
        $pagination->setRPP($per_page);
        $pagination->setTotal($total_records);
        // grab rendered/parsed pagination markup
        $markup = $pagination->parse();

        $result = $this->model->getList($page_start, $page_offset);
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

        $this->template->addVar('PAGINATION', $markup);
        $this->template->addVar('OUTPUTMAIN', $this->template->parseFile('news/new_admin_index.html', false) );
        //$this->data['pages'] = $this->model->getList();
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
                'NEWS_ID'	 =>   $result['id'] ,
                'NEWS_TITLE_RU'			=>   $result['title'],
                'NEWS_TITLE_UA'			=>   $result['ua_title'],
                'NEWS_ALIAS'			=>   $result['alias'],
                'NEWS_DESCRIPTION_RU' =>   $result['description'],
                'NEWS_DESCRIPTION_UA' =>   $result['ua_description'],
                'NEWS_TEXT_RU'	=>   nl2br($result['text']),
                'NEWS_TEXT_UA'	=>   nl2br($result['ua_text']),
                'NEWS_ACTIVE'	=>   $result['active'],
                //'NEWS_PICTURE'	=>   DS.'webroot'.DS.'image'.DS.'logo1.png',
            ));

            $this->template->addVar('OUTPUTMAIN', $this->template->parseFile('news/new_admin_edit.html', false) );

        } else {
            Session::setFlash("Неправилный Id страницы!");
            Router::redirect('/admin/admins/');
        }
    }

    public function admin_add()
    {
        if ($_POST) {
            if(isset($_FILES['picture']) && !empty($_FILES['picture']['name'])){

                $resultFile = $this->upload_file($_FILES['picture']);
                $img = 'null'; // В таблице поле должно иметь значение по умолчанию null

                if(isset($resultFile['error'])){
                    $error = $resultFile['error'];
                }else{
                    $img = '"'.$resultFile['filename'].'"';
                }
            }

            if(!isset($error)){
                $_POST['picture'] = $img;

                $result = $this->model->save($_POST);
                if ($result) {
                    Session::setFlash('Администратор был успешно создан.');
                } else {
                    Session::setFlash('Ошибка!');
                }
                Router::redirect('/admin/news/');
            }else{
                Session::setFlash($error);
                $this->template->addVar('OUTPUTMAIN', $this->template->parseFile('news/new_admin_add.html', false) );

            }

//            $result = $this->model->save($_POST);
//            if ($result) {
//                Session::setFlash('Администратор был успешно создан.');
//            } else {
//                Session::setFlash('Ошибка!');
//            }
//            Router::redirect('/admin/news/');
        }
        $this->template->addVar('OUTPUTMAIN', $this->template->parseFile('news/new_admin_add.html', false) );
    }

    protected function upload_file($file, $upload_dir= 'image', $allowed_types= array('image/png','image/x-png','image/jpeg','image/webp','image/gif')){

        $filename = $file['name']; // В переменную $filename заносим точное имя файла.
        $blacklist = array(".php", ".phtml", ".php3", ".php4");
        $ext = substr($filename, strrpos($filename,'.'), strlen($filename)-1); // В переменную $ext заносим расширение загруженного файла.
        if(in_array($ext,$blacklist )){
            return array('error' => 'Запрещено загружать исполняемые файлы');}

        $upload_dir = ROOT.DS.'webroot'.DS.$upload_dir.DS; // Место, куда будут загружаться файлы.
        $max_filesize = 8388608; // Максимальный размер загружаемого файла в байтах (в данном случае он равен 8 Мб).
        $prefix = date('Ymd-is_');


        if(!is_writable($upload_dir))  // Проверяем, доступна ли на запись папка, определенная нами под загрузку файлов.
            return array('error' => 'Невозможно загрузить файл в папку "'.$upload_dir.'". Установите права доступа - 777.');

        if(!in_array($file['type'], $allowed_types))
            return array('error' => 'Данный тип файла не поддерживается.');

        if(filesize($file['tmp_name']) > $max_filesize)
            return array('error' => 'файл слишком большой. максимальный размер '.intval($max_filesize/(1024*1024)).'мб');

        if(!move_uploaded_file($file['tmp_name'],$upload_dir.$prefix.$filename)) // Загружаем файл в указанную папку.
            return array('error' => 'При загрузке возникли ошибки. Попробуйте ещё раз.');

        return Array('filename' => $prefix.$filename);
    }
}