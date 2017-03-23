<?php

class Page extends Model
{
    //имя таблицы модели
    protected $table_name = "pages";

    //метод для получение всех страниц
    public function getList($only_published = false)
    {
        $sql = 'SELECT * FROM pages WHERE 1';
        if ($only_published) {
            $sql .= ' AND is_published = 1';
        }

        return $this->db->query($sql);
    }

    public function getListActive($lang = 'ru')
    {
        $suffix = '';
        if ($lang == 'ua') {
            $suffix = $lang.'_';
        }
        $sql = "SELECT id,
                       {$suffix}title as title,
                       alias,
                       {$suffix}description as description,
                       {$suffix}keywords as keywords,
                       {$suffix}text as text
                  FROM pages";
        return $this->db->query($sql);
    }

    //метод для получения страницы по ее алиасу
    public function getByAlias($alias, $lang = 'ru')
    {
        $alias = $this->db->escape($alias);

        $suffix = '';
        if ($lang == 'ua') {
            $suffix = $lang.'_';
        }

        $sql = "SELECT id,
                       {$suffix}title as title,
                       alias,
                       {$suffix}description as description,
                       {$suffix}keywords as keywords,
                       {$suffix}text as text
                  FROM pages WHERE alias = '{$alias}' LIMIT 1";
        $result = $this->db->query($sql);

        return isset($result[0]) ? $result[0] : null;
    }

    //метод для получения страницы по ее id
    public function getById($id)
    {
        $id = (int)$id;

        $sql = "SELECT * FROM pages WHERE id = {$id} LIMIT 1";
        $result = $this->db->query($sql);

        return isset($result[0]) ? $result[0] : null;
    }

    //СТАРЫЙ МЕТОД, ТАБЛИЦА ИЗМЕНИЛАСЬ
    public function save($data, $id = null)
    {
        if (empty($data['alias']) || empty($data['title']) || empty($data['content'])) {
            return false;
        }

        $id = (int)$id;
        $alias = $this->db->escape($data['alias']);
        $title = $this->db->escape($data['title']);
        $content = $this->db->escape($data['content']);
        $is_published = isset($data['is_published']) ? 1 : 0;

        if (!$id) { //Add new record
            $sql = "INSERT INTO pages SET alias = '{$alias}',
                                          title = '{$title}',
                                          content = '{$content}',
                                          is_published = '{$is_published}'";
        } else {
            $sql = "UPDATE pages SET alias = '{$alias}',
                                     title = '{$title}',
                                     content = '{$content}',
                                     is_published = '{$is_published}'
                                 WHERE id = {$id}";
        }

        return $this->db->query($sql);
    }

    public function delete($id)
    {
        $id = (int)$id;
        $sql = "DELETE FROM pages WHERE page_id = {$id}";
        return $this->db->query($sql);
    }

    protected function translit($s) {
        $s = (string) $s; // преобразуем в строковое значение
        $s = strip_tags($s); // убираем HTML-теги
        $s = str_replace(array("\n", "\r"), " ", $s); // убираем перевод каретки
        $s = preg_replace("/\s+/", ' ', $s); // удаляем повторяющие пробелы
        $s = trim($s); // убираем пробелы в начале и конце строки
        $s = function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s); // переводим строку в нижний регистр (иногда надо задать локаль)
        $s = strtr($s, array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>''));
        $s = preg_replace("/[^0-9a-z-_ ]/i", "", $s); // очищаем строку от недопустимых символов
        $s = str_replace(" ", "-", $s); // заменяем пробелы знаком минус
        return $s; // возвращаем результат
    }
}