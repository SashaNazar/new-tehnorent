<?php

class Message extends Model
{
    //метод для записи или обновления данных
    public function save($data, $id = null)
    {
        if (!isset($data['name']) || !isset($data['email']) || !isset($data['message'])) {
            return false;
        }

        $id = (int)$id;
        $name = $this->db->escape($data['name']);
        $email = $this->db->escape($data['email']);
        $messages = $this->db->escape($data['message']);

        $sql = "messages SET name = '{$name}',
                             email = '{$email}',
                             messages = '{$messages}'";
        if (!$id) { //Add new record
            $sql = "INSERT INTO " . $sql;
        } else {
            $sql = "UPDATE " . $sql ."  WHERE id = {$id}";
        }

        return $this->db->query($sql);
    }
}