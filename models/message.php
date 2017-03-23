<?php

class Message extends Model
{
    //имя таблицы модели
    protected $table_name = "messages";

    //метод для получение всех сообщений
    public function getList()
    {
        $sql = 'SELECT * FROM messages WHERE 1';
               return $this->db->query($sql);
    }
    //метод для записи или обновления данных
    public function save($data, $id = null)
    {
        if (empty($data['name']) || empty($data['email']) || empty($data['message'])) {
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