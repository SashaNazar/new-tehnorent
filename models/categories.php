<?php

class Categories extends Model
{
    public function getList()
    {
        $sql = "SELECT * FROM category WHERE 1";
        return $this->db->query($sql);
    }

    public function getListActive($lang = 'ru')
    {
        $suffix = '';
        if ($lang == 'ua') {
            $suffix = $lang.'_';
        }
        $sql = "SELECT id, p_id, {$suffix}name as name FROM category WHERE active='yes'";
        return $this->db->query($sql);
    }

    public function getCategoryForMenu($lang = 'ru')
    {
        $suffix = '';
        if ($lang == 'ua') {
            $suffix = $lang.'_';
        }

        $sql = "SELECT id, p_id, {$suffix}name as name FROM category where active='yes'";
        $result = $this->db->query($sql);

        $arr_cat = array();
        foreach ($result as $item) {
            $arr_cat[$item['id']] = $item;
        }
        return $arr_cat;
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
        if (empty($data['name'])) {
            return false;
        }

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

    public function setUnactive($id)
    {
        $id = (int)$id;
        $sql = "UPDATE category SET active = 'no' WHERE id = {$id}";
        return $this->db->query($sql);
    }

    public function setActive($id)
    {
        $id = (int)$id;
        $sql = "UPDATE category SET active = 'yes' WHERE id = {$id}";
        return $this->db->query($sql);
    }
}