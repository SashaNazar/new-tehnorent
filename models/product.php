<?php

class Product extends Model
{
    protected $table_name = 'products';

//    public function getTotalCount($active = false)
//    {
//        $sql = "SELECT count(*) AS total FROM products WHERE 1";
//        if ($active) {
//            $sql .= " AND active = 'yes'";
//        }
//        $result = $this->db->query($sql);
//
//        return isset($result[0]) ? $result[0]['total'] : null;
//    }

    public function getTotalCountWithCategory($category_id = false, $active = false)
    {
        $sql = "SELECT count(*) AS total FROM products WHERE 1";
        if ($active) {
            $sql .= " AND active = 'yes'";
        }
        if ($category_id && is_integer($category_id)) {
            $sql .= " AND category_id = {$category_id}";
        }
        $result = $this->db->query($sql);

        return isset($result[0]) ? $result[0]['total'] : null;
    }


    //метод для получения продукта по его id
    public function getById($id)
    {
        $id = (int)$id;

        $sql = "SELECT * FROM products WHERE id = {$id} LIMIT 1";
        $result = $this->db->query($sql);

        return isset($result[0]) ? $result[0] : null;
    }

    public function getByIdLang($id, $lang = 'ru')
    {
        $id = (int)$id;

        $suffix = '';
        if ($lang == 'ua') {
            $suffix = $lang.'_';
        }

        $sql = "SELECT id,
                       category_id,
                       available_kiev,
                       {$suffix}title as title,
                       {$suffix}name as name,
                       {$suffix}description as description,
                       {$suffix}params as params,
                       price,
                       deposit,
                       picture,
                       picture_small,
                       vendor,
                       vendor_code
                  FROM products WHERE id = {$id} LIMIT 1";
        $result = $this->db->query($sql);

        return isset($result[0]) ? $result[0] : null;
    }

    public function getProductsByCategory($start, $per_page = 10, $category_id, $active = false, $lang = 'ru')
    {
        $category_id = (int)$category_id;
        $sql = "SELECT * FROM products WHERE category_id={$category_id}";
        if ($active) {
            $sql .= " AND active = 'yes'";
        }
        $sql .= " LIMIT {$start}, {$per_page}";
        return $this->db->query($sql);
    }

    //метод для получение всех продуктов
    public function getList($start = 1, $per_page = 10, $active = false)
    {
        $sql = "SELECT products.id,
                       products.available_kiev,
                       products.title,
                       products.name,
                       products.description,
                       products.params,
                       products.ua_title,
                       products.ua_name,
                       products.ua_description,
                       products.ua_params,
                       products.price,
                       products.picture,
                       products.picture_small,
                       products.vendor,
                       products.vendor_code,
                       products.deposit,
                       category.name as category_name
                FROM products";
        if ($active) {
            $sql .= ' AND active = 1';
        }
        $sql .= " LEFT JOIN category ON products.category_id = category.id LIMIT {$start}, {$per_page}";

        //var_dump($sql);die;
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
        $price = number_format($this->db->escape($data['price']), 2, '.', '');
        $deposit = number_format($this->db->escape($data['deposit']), 2, '.', '');
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
                                             deposit = '{$deposit}',
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
                                             deposit = '{$deposit}',
                                             category_id = '{$category_id}',
                                             vendor = '{$vendor}',
                                             vendor_code = '{$vendor_code}',
                                             available_kiev = '{$available_kiev}'
                                          WHERE id = {$id}";

        }

        $this->db->query($sql);
        if (!$id) {
            $id = $this->db->getLastInsertId();
        }

        return $id;

        //return $this->db->query($sql);
    }

    public function saveImageForProduct($id_product, $big_image, $small_image)
    {
        $id = (int)$id_product;
        $big_image = $this->db->escape($big_image);
        $small_image = $this->db->escape($small_image);

        $sql = "UPDATE products SET picture = '{$big_image}',
                                    picture_small = '{$small_image}'
                                WHERE id = {$id}";
        return $this->db->query($sql);
    }

    public function delete($id)
    {
        $id = (int)$id;
        $this->deleteImageForProduct($id);
        $sql = "DELETE FROM products WHERE id = {$id}";
        return $this->db->query($sql);
    }

    public function deleteImageForProduct($id)
    {
        $id = (int)$id;
        $sql = "SELECT picture, picture_small FROM products WHERE id = {$id} LIMIT 1";
        $images = $this->db->query($sql);

        if ($images && $images[0]) {
            foreach ($images[0] as $image) {
                if (!empty($image) && file_exists(ROOT . $image)) {
                    unlink(ROOT . $image);
                }
            }
        }

        $this->saveImageForProduct($id, null, null);
        return $this->db->query($sql);
    }
}