<?php

class Model
{
    //атрибут для доступа к базе данных
    protected $db;

    //имя таблицы модели
    protected $table_name;

    public function __construct()
    {
        $this->db = App::$db;
    }

    //Метод для получения количества записей в таблице
    public function getTotalCount($active = false)
    {
        $sql = "SELECT count(*) AS total FROM {$this->table_name} WHERE 1";
        if ($active) {
            $sql .= " AND active = 'yes'";
        }
        $result = $this->db->query($sql);

        return isset($result[0]) ? $result[0]['total'] : null;
    }

    public function getTotalCountWithCondition($condition = array())
    {
        $sql = "SELECT count(*) AS total FROM {$this->table_name} WHERE 1";
        if (!empty($condition)) {
            foreach ($condition as $key => $value) {
                $sql .= " AND {$key} LIKE '{$this->db->escape($value)}'";
            }
        }

        $result = $this->db->query($sql);

        return isset($result[0]) ? $result[0]['total'] : null;
    }

    //функция для удаления записей из таблицы по id
    public function delete($id)
    {
        $id = (int)$id;
        $sql = "DELETE FROM {$this->table_name} WHERE id = {$id}";
        return $this->db->query($sql);
    }
}