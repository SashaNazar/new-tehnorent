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

    //метод для получение всех пользователей при поиске
    public function getListWithCondition($condition = array(), $sort = array(), $start = 1, $per_page = 10)
    {
        $sql = "SELECT orders.id,
                       orders.user_name,
                       orders.user_phone,
                       orders.product_id,
                       orders.status,
                       orders.comment,
                       orders.created,
                       orders.updated
                FROM {$this->table_name} WHERE 1";
        if ($condition) {
            foreach ($condition as $key => $value) {
                $sql .= " AND {$key} LIKE '%{$value}%'";
            }
        }
        if (!empty($sort)) {
            $sort_field = $sort['field'];
            $sort_by = $sort['by'];
            $sql .= " ORDER BY {$sort_field} {$sort_by}";
        }
        $sql .= " LIMIT {$start}, {$per_page}";

        return $this->db->query($sql);
    }

    //метод для получение всех заказов
    public function getList($start = 1, $per_page = 10, $sort = array(), $status = 1, $active = false)
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
        if (!empty($sort)) {
            $sort_field = $sort['field'];
            $sort_by = $sort['by'];
            $sql .= " ORDER BY {$sort_field} {$sort_by}";
        }
        $sql .= " LIMIT {$start}, {$per_page}";
        //$sql .= " LEFT JOIN category ON products.category_id = category.id LIMIT {$start}, {$per_page}";

        return $this->db->query($sql);
    }

    //метод для получение всех пользователей
    public function getList2($start = 1, $per_page = 10, $sort = array())
    {
        $sql = "SELECT id,
                       user_name,
                       user_phone
                FROM {$this->table_name}";
        if (!empty($sort)) {
            $sort_field = $sort['field'];
            $sort_by = $sort['by'];
            $sql .= " ORDER BY {$sort_field} {$sort_by}";
        }
        $sql .= " LIMIT {$start}, {$per_page}";

//        $sql = "SELECT id,
//                       user_name,
//                       user_phone
//                FROM {$this->table_name} ";

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