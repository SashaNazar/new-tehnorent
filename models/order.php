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
    public function getListWithCondition($start = 1, $per_page = 10, $sort = array(), $status = 'all', $condition = array())
    {
        $sql = "SELECT orders.id,
                       orders.user_name,
                       orders.user_phone,
                       orders.product_id,
                       orders.status,
                       orders.comment,
                       orders.created,
                       orders.updated,
                       orders.start_rent,
                       orders.end_rent
                FROM {$this->table_name} WHERE 1";
        if ($condition) {
            foreach ($condition as $key => $value) {
                $sql .= " AND {$key} LIKE '%{$value}%'";
            }
        }
        if ($status !== 'all') {
            $status = (int)$status;
            $sql .= " AND status = {$status}";
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
    public function getList($start = 1, $per_page = 10, $sort = array(), $status = 'all', $active = false)
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
        if ($status !== 'all') {
            $status = (int)$status;
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
        } else {
            $status = (int)$data['status'];
            $start_rent = !empty($data['start_rent']) ? $data['start_rent'] : '0000-00-00 00:00:00';
            $end_rent = !empty($data['end_rent']) ? $data['end_rent'] : '0000-00-00 00:00:00';
            $sql = "UPDATE {$this->table_name} SET user_name = '{$user_name}',
                                                   user_phone = '{$user_phone}',
                                                   product_id = '{$product_id}',
                                                   status = '{$status}',
                                                   start_rent = '{$start_rent}',
                                                   end_rent = '{$end_rent}'
                                               WHERE id = {$id}";
        }

        //var_dump($sql);die;
        $this->db->query($sql);
        if (!$id) {
            $id = $this->db->getLastInsertId();
        }

        return $id;
    }
}