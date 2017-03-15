<?php

class Newses extends Model
{
    //метод для получение всех yjdjcntq
    public function getList($active = false)
    {
        $sql = 'SELECT * FROM news WHERE 1';
        if ($active) {
            $sql .= ' AND active = 1';
        }

        return $this->db->query($sql);
    }

    public function save($data, $id = null)
    {
        if (empty($data['title']) || empty($data['description']) || empty($data['content'])) {
            return false;
        }

        $data = array_map('trim', $data);

       // var_dump($data);die;

        $id = (int)$id;
        $title = $this->db->escape($data['title']);
        $ua_title = $this->db->escape($data['ua_title']);
        $description = $this->db->escape($data['description']);
        $ua_description = $this->db->escape($data['ua_description']);
        $content = $this->db->escape($data['content']);
        $ua_content = $this->db->escape($data['ua_content']);
        $active = isset($data['active']) ? 'yes' : 'no';
        $alias = $this->slugify($title);


        var_dump($title);
        var_dump($alias);die;
        if (!$id) { //Add new record
            $sql = "INSERT INTO pages SET title = '{$title}',
                                          ua_title = '{$ua_title}',
                                          alias = '{$alias}',
                                          description = '{$description}',
                                          ua_description = '{$ua_description}',
                                          content = '{$content}',
                                          ua_content = '{$ua_content}',
                                          content = '{$content}',
                                          active = '{$active}'";
        } else {
            $sql = "UPDATE pages SET title = '{$title}',
                                     ua_title = '{$ua_title}',
                                     alias = '{$alias}',
                                     description = '{$description}',
                                     ua_description = '{$ua_description}',
                                     content = '{$content}',
                                     ua_content = '{$ua_content}',
                                     content = '{$content}',
                                     active = '{$active}'
                                  WHERE id = {$id}";
        }

        return $this->db->query($sql);
    }

    //Метод для создания alias для новостей
    protected function slugify($text)
    {
        var_dump($text);
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        var_dump($text);die;

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
}