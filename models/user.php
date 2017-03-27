<?php

class User extends Model
{
    //имя таблицы модели
    protected $table_name = 'users';

    //метод для получение всех пользователей при поиске
    public function getListWithCondition($condition = array(), $start = 1, $per_page = 10)
    {
        $sql = "SELECT id,
                       user_name,
                       user_phone
                FROM {$this->table_name} WHERE 1";
        if ($condition) {
            foreach ($condition as $key => $value) {
                $sql .= " AND {$key} LIKE '%{$value}%'";
            }
        }
        $sql .= " LIMIT {$start}, {$per_page}";

//        $sql = "SELECT id,
//                       user_name,
//                       user_phone
//                FROM {$this->table_name} LIMIT {$start}, {$per_page}";

        return $this->db->query($sql);
    }

    //метод для получение всех пользователей
    public function getList($start = 1, $per_page = 10, $sort = array())
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

    public function setUser($name, $phone)
    {
        if (empty($name) || empty($phone)) {
            return false;
        }

        $user_name = $this->db->escape($name);
        $user_phone = $this->db->escape($phone);

        $user = $this->findUserByPhoneAndName($user_name, $user_phone);

        if ($user) {
            return $user;
        }

        $sql = "INSERT INTO {$this->table_name} SET user_name = '{$user_name}',
                                                    user_phone = '{$user_phone}'";

        $this->db->query($sql);
        $id = $this->db->getLastInsertId();

        return $id;
    }

    protected function findUserByPhoneAndName($name, $phone)
    {
        $sql = "SELECT id FROM {$this->table_name} WHERE user_name = '{$name}' AND user_phone = '{$phone}' LIMIT 1";
        $result = $this->db->query($sql);
        return isset($result[0]) ? $result[0] : null;
    }

}