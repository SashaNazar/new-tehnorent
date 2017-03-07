<?php

class User extends Model
{
    //функция для получения пользователя по его логину
    public function getByLogin($login)
    {
        $login = $this->db->escape($login);
        $sql = "SELECT * FROM users WHERE login = '{$login}' LIMIT 1";
        $result = $this->db->query($sql);

        return isset($result[0]) ? $result[0] : false;
    }
}