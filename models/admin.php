<?php

class Admin extends Model
{
    //функция для получения пользователя по его логину
    public function getByLogin($login)
    {
        $admin_login = $this->db->escape($login);
        $sql = "SELECT * FROM admins WHERE admins_login = '{$admin_login}' LIMIT 1";
        $result = $this->db->query($sql);

        return isset($result[0]) ? $result[0] : false;
    }

    public function getList()
    {
        $sql = "SELECT admins_id, admins_name, admins_active FROM admins WHERE 1";
        return $this->db->query($sql);
    }

    public function getById($id)
    {
        $id = (int)$id;
        $sql = "SELECT * FROM admins WHERE admins_id = {$id} LIMIT 1";
        $result = $this->db->query($sql);
        return isset($result[0]) ? $result[0] : null;
    }

    public function setUnactive($id)
    {
        $id = (int)$id;
        $sql = "UPDATE admins SET admins_active = 'no' WHERE admins_id = {$id}";
        return $this->db->query($sql);
    }

    public function setActive($id)
    {
        $id = (int)$id;
        $sql = "UPDATE admins SET admins_active = 'yes' WHERE admins_id = {$id}";
        return $this->db->query($sql);
    }

    public function save($data, $id = null)
    {
//        if (empty($data['admins_name']) || empty($data['title']) || empty($data['content'])) {
//            return false;
//        }

        //Удаляем пробелы в начале и конце строки в массиве входных данных
        $data = array_map('trim', $data);

        $admins_id = (int)$id;
        $admins_name = $this->db->escape($data['name']);
        $admins_login = $this->db->escape($data['login']);
        $admins_pass = $this->db->escape($data['pass']);
        $admins_mobile_phone = $this->db->escape($data['mobile_phone']);
        $admins_short_phone = $this->db->escape($data['short_phone']);
        $admins_accessgroup = isset($data['access']) ? (int)$data['access'] : 0;
        $admins_active = isset($data['active']) ? 'yes' : 'no';
        $admins_manager = isset($data['manager']) ? 'yes' : 'no';

        if (!$id) { //Add new record
            $sql = "INSERT INTO admins SET admins_name = '{$admins_name}',
                                           admins_login = '{$admins_login}',
                                           admins_pass = '{$admins_pass}',
                                           admins_mobile_phone = '{$admins_mobile_phone}',
                                           admins_short_phone = '{$admins_short_phone}',
                                           admins_accessgroup = '{$admins_accessgroup}',
                                           admins_active = '{$admins_active}',
                                           admins_manager = '{$admins_manager}'";
        } else {
            $sql = "UPDATE admins SET admins_name = '{$admins_name}',
                                      admins_login = '{$admins_login}',
                                      admins_pass = '{$admins_pass}',
                                      admins_mobile_phone = '{$admins_mobile_phone}',
                                      admins_short_phone = '{$admins_short_phone}',
                                      admins_accessgroup = '{$admins_accessgroup}',
                                      admins_active = '{$admins_active}',
                                      admins_manager = '{$admins_manager}'
                                  WHERE admins_id = {$admins_id}";

        }

        return $this->db->query($sql);
    }
}