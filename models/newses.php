<?php

class Newses extends Model
{
    public function getTotalCount($active = false)
    {
        $sql = "SELECT count(*) AS total FROM news WHERE 1";
        if ($active) {
            $sql .= " AND active = 'yes'";
        }
        $result = $this->db->query($sql);

        return isset($result[0]) ? $result[0]['total'] : null;
    }
    //метод для получение всех yjdjcntq
    public function getList($start = 1, $per_page = 10, $active = false)
    {
        $sql = 'SELECT * FROM news WHERE 1';
        if ($active) {
            $sql .= ' AND active = 1';
        }
        //if ((int)$start && (int)$per_page) {
            $sql .= " LIMIT {$start}, {$per_page}";
       // }

        return $this->db->query($sql);
    }

    //метод для получения новости по ее id
    public function getById($id)
    {
        $id = (int)$id;

        $sql = "SELECT * FROM news WHERE id = {$id} LIMIT 1";
        $result = $this->db->query($sql);

        return isset($result[0]) ? $result[0] : null;
    }

    public function save($data, $id = null)
    {
        if (empty($data['title']) || empty($data['description']) || empty($data['text'])) {
            return false;
        }

        $data = array_map('trim', $data);

       // var_dump($data);die;

        $id = (int)$id;
        $title = $this->db->escape($data['title']);
        $ua_title = $this->db->escape($data['ua_title']);
        $description = $this->db->escape($data['description']);
        $ua_description = $this->db->escape($data['ua_description']);
        $text = $this->db->escape($data['text']);
        $picture = $this->db->escape($data['picture']);
        $ua_text = $this->db->escape($data['ua_text']);
        $active = isset($data['active']) ? 'yes' : 'no';
        $alias = $this->translit($title);

        if (!$id) { //Add new record
            $sql = "INSERT INTO news SET title = '{$title}',
                                          ua_title = '{$ua_title}',
                                          alias = '{$alias}',
                                          description = '{$description}',
                                          ua_description = '{$ua_description}',
                                          text = '{$text}',
                                          ua_text = '{$ua_text}',
                                          picture = '{$picture}',
                                          active = '{$active}'";
        } else {
            $sql = "UPDATE news SET title = '{$title}',
                                     ua_title = '{$ua_title}',
                                     alias = '{$alias}',
                                     description = '{$description}',
                                     ua_description = '{$ua_description}',
                                     text = '{$text}',
                                     ua_text = '{$ua_text}',
                                     active = '{$active}'
                                  WHERE id = {$id}";
        }

        //var_dump($sql);die;
        return $this->db->query($sql);
    }

    protected function translit($s) {
        $s = (string) $s; // преобразуем в строковое значение
        $s = strip_tags($s); // убираем HTML-теги
        $s = str_replace(array("\n", "\r"), " ", $s); // убираем перевод каретки
        $s = preg_replace("/\s+/", ' ', $s); // удаляем повторяющие пробелы
        $s = trim($s); // убираем пробелы в начале и конце строки
        $s = function_exists('mb_strtolower') ? mb_strtolower($s, 'UTF-8') : strtolower($s); // переводим строку в нижний регистр (иногда надо задать локаль)
        $s = strtr($s, array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>''));
        $s = preg_replace("/[^0-9a-z-_ ]/i", "", $s); // очищаем строку от недопустимых символов
        $s = str_replace(" ", "-", $s); // заменяем пробелы знаком минус
        return $s; // возвращаем результат
    }
}