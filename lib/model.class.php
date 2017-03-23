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

    public function getTotalCount($active = false)
    {
        $sql = "SELECT count(*) AS total FROM {$this->table_name} WHERE 1";
        if ($active) {
            $sql .= " AND active = 'yes'";
        }
        $result = $this->db->query($sql);

        return isset($result[0]) ? $result[0]['total'] : null;
    }
}