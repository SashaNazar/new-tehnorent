<?php

class Categories extends Model
{
    public function getList()
    {
        $sql = "SELECT * FROM category WHERE 1";
        return $this->db->query($sql);
    }

    public function getById($id)
    {
        $id = (int)$id;
        $sql = "SELECT * FROM category WHERE id = {$id} LIMIT 1";
        $result = $this->db->query($sql);
        return isset($result[0]) ? $result[0] : null;
    }

    public function getAllCategories()
    {
        $sql = "SELECT id, name FROM category WHERE 1";
        return $this->db->query($sql);
    }


    public function save($data, $id = null)
    {
        // var_dump($data);die;
//        if (empty($data['alias']) || empty($data['title']) || empty($data['content'])) {
//            return false;
//        }

        $id = (int)$id;
        $p_id = (int)$data['p_id'];
        $name = $this->db->escape($data['name']);
        $ua_name = $this->db->escape($data['ua_name']);
        $seo = $this->db->escape($data['seo']);
        $ua_seo = $this->db->escape($data['ua_seo']);
        $active = isset($data['active']) ? 'yes' : 'no';

        if (!$id) { //Add new record
            $sql = "INSERT INTO category
                            SET p_id = {$p_id},
                                name = '{$name}',
                                ua_name = '{$ua_name}',
                                seo = '{$seo}',
                                ua_seo = '{$ua_seo}',
                                active = '{$active}'";
        } else {
            $sql = "UPDATE category SET p_id = {$p_id},
                                        name = '{$name}',
                                        ua_name = '{$ua_name}',
                                        seo = '{$seo}',
                                        ua_seo = '{$ua_seo}',
                                        active = '{$active}'
                                WHERE id = {$id}";
        }

        return $this->db->query($sql);
    }

}