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
}