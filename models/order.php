<?php

class Order extends Model
{
    protected $table_name = 'orders';

    //метод для получения заказа по его id
    public function getById($id)
    {
        $id = (int)$id;
        $sql = "SELECT * FROM {$this->table_name} WHERE id = {$id} LIMIT 1";
        $result = $this->db->query($sql);
        return isset($result[0]) ? $result[0] : null;
    }

    //метод для получение всех заказов
    public function getList($start = 1, $per_page = 10, $status = 1, $active = false)
    {
        $status = (int)$status;
        $sql = "SELECT orders.id,
                       orders.user_name,
                       orders.user_phone,
                       orders.product_id,
                       orders.status,
                       orders.comment,
                       orders.created,
                       orders.updated
                FROM {$this->table_name} WHERE 1";
        if ($status) {
            $sql .= " AND status = {$status}";
        }
        $sql .= " LIMIT {$start}, {$per_page}";
        //$sql .= " LEFT JOIN category ON products.category_id = category.id LIMIT {$start}, {$per_page}";

        //var_dump($sql);die;
        return $this->db->query($sql);
    }

    public function save($data, $id = null)
    {
        $data = array_map('trim', $data);
        if (empty($data['user_name']) || empty($data['user_phone']) || empty($data['product_id'])) {
            return false;
        }

        $id = (int)$id;
        $user_name = $this->db->escape($data['user_name']);
        $user_phone = $this->db->escape($data['user_phone']);
        $product_id = (int)$data['product_id'];
        $created = date("Y-m-d H:i:s");

        if (!$id) {
            $sql = "INSERT INTO {$this->table_name} SET user_name = '{$user_name}',
                                                        user_phone = '{$user_phone}',
                                                        product_id = '{$product_id}',
                                                        created = '{$created}'";
        }

        $this->db->query($sql);
        if (!$id) {
            $id = $this->db->getLastInsertId();
        }

        return $id;
    }
}