<?php

class Status extends Model
{
    protected $table_name = 'statuses';

    public function getAllStatus()
    {
        $sql = "SELECT * FROM {$this->table_name}";
        return $this->db->query($sql);

    }
}