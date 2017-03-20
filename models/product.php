<?php

class Product extends Model
{
    public function getList1()
    {

    }

    //метод для получения продукта по его id
    public function getById($id)
    {
        $id = (int)$id;

        $sql = "SELECT * FROM products WHERE id = {$id} LIMIT 1";
        $result = $this->db->query($sql);

        return isset($result[0]) ? $result[0] : null;
    }

    //метод для получение всех страниц
    public function getList($only_published = false)
    {
        $sql = 'SELECT * FROM products WHERE 1';
        if ($only_published) {
            $sql .= ' AND is_published = 1';
        }

        return $this->db->query($sql);
    }

    public function save($data, $id = null)
    {
        //var_dump($data);
        if (empty($data['name']) || empty($data['title']) || empty($data['description']) || empty($data['params'])) {
            return false;
        }


        $data = array_map('trim', $data);

        $id = (int)$id;
        $title = $this->db->escape($data['title']);
        $ua_title = $this->db->escape($data['ua_title']);
        $name = $this->db->escape($data['name']);
        $ua_name = $this->db->escape($data['ua_name']);
        $description = $this->db->escape($data['description']);
        $ua_description = $this->db->escape($data['ua_description']);
        $params = $this->db->escape($data['params']);
        $ua_params = $this->db->escape($data['ua_params']);
        $price = number_format($this->db->escape($data['price']), 2);
        $picture = $this->db->escape($data['picture']);
        $picture_small = $this->db->escape($data['picture_small']);
        $category_id = (int)$data['category_id'];
        $vendor = $this->db->escape($data['vendor']);
        $vendor_code = $this->db->escape($data['vendor_code']);
        $available_kiev = isset($data['available_kiev']) ? true : false;

        if (!$id) { //Add new record
            $sql = "INSERT INTO products SET title = '{$title}',
                                             ua_title = '{$ua_title}',
                                             name = '{$name}',
                                             ua_name = '{$ua_name}',
                                             description = '{$description}',
                                             ua_description = '{$ua_description}',
                                             params = '{$params}',
                                             ua_params = '{$ua_params}',
                                             price = '{$price}',
                                             picture = '{$picture}',
                                             picture_small = '{$picture_small}',
                                             category_id = '{$category_id}',
                                             vendor = '{$vendor}',
                                             vendor_code = '{$vendor_code}',
                                             available_kiev = '{$available_kiev}'";

        } else {
            $sql = "UPDATE products SET title = '{$title}',
                                             ua_title = '{$ua_title}',
                                             name = '{$name}',
                                             ua_name = '{$ua_name}',
                                             description = '{$description}',
                                             ua_description = '{$ua_description}',
                                             params = '{$params}',
                                             ua_params = '{$ua_params}',
                                             price = '{$price}',
                                             picture = '{$picture}',
                                             picture_small = '{$picture_small}',
                                             category_id = '{$category_id}',
                                             vendor = '{$vendor}',
                                             vendor_code = '{$vendor_code}',
                                             available_kiev = '{$available_kiev}'
                                          WHERE id = {$id}";

        }

        return $this->db->query($sql);
    }
}