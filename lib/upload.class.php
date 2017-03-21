<?php

class Upload
{
    private $upload_dir = ROOT.DS.'webroot'.DS.'image'.DS;

    private $allowed_types = array('image/png','image/x-png','image/jpeg','image/webp','image/gif');

    private $blacklist = array(".php", ".phtml", ".php3", ".php4");

    private $img_name;

    public function __construct($upload_dir, $img_new_name)
    {
        $this->upload_dir = $upload_dir;

    }

    function load1($filename) {
        $image_info = getimagesize($filename);
        $this->image_type = $image_info[2];
        if( $this->image_type == IMAGETYPE_JPEG ) {
            $this->image = imagecreatefromjpeg($filename);
        } elseif( $this->image_type == IMAGETYPE_GIF ) {
            $this->image = imagecreatefromgif($filename);
        } elseif( $this->image_type == IMAGETYPE_PNG ) {
            $this->image = imagecreatefrompng($filename);
        }
    }

    public function load($file)
    {
        $filename = $file['name']; // В переменную $filename заносим точное имя файла.

        $ext = substr($filename, strrpos($filename,'.'), strlen($filename)-1); // В переменную $ext заносим расширение загруженного файла.

        $max_filesize = 8388608; // Максимальный размер загружаемого файла в байтах (в данном случае он равен 8 Мб).
    }

    public function resize()
    {

    }

    public function upload_file($file, $upload_dir= 'image', $allowed_types= array('image/png','image/x-png','image/jpeg','image/webp','image/gif')){

        $filename = $file['name']; // В переменную $filename заносим точное имя файла.

        $blacklist = array(".php", ".phtml", ".php3", ".php4");

        $ext = substr($filename, strrpos($filename,'.'), strlen($filename)-1); // В переменную $ext заносим расширение загруженного файла.

        if(in_array($ext,$blacklist )){
            return array('error' => 'Запрещено загружать исполняемые файлы');}

        //$upload_dir = ROOT.DS.'webroot'.DS.$upload_dir.DS; // Место, куда будут загружаться файлы.
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