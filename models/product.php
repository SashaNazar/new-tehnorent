<?php

class Product extends Model
{
    protected $table_name = 'products';

    public function getTotalCountWithCategory($category_id = false, $active = false)
    {
        $sql = "SELECT count(*) AS total FROM products WHERE 1";
        if ($active) {
            $sql .= " AND active = 'yes'";
        }
        if ($category_id && is_integer($category_id)) {
            $sql .= " AND category_id = {$category_id}";
        }
        $result = $this->db->query($sql);

        return isset($result[0]) ? $result[0]['total'] : null;
    }


    //метод для получения продукта по его id
    public function getById($id)
    {
        $id = (int)$id;

        $sql = "SELECT * FROM products WHERE id = {$id} LIMIT 1";
        $result = $this->db->query($sql);

        return isset($result[0]) ? $result[0] : null;
    }

    public function getByIdLang($id, $lang = 'ru')
    {
        $id = (int)$id;

        $suffix = '';
        if ($lang == 'ua') {
            $suffix = $lang.'_';
        }

        $sql = "SELECT id,
                       category_id,
                       active,
                       {$suffix}title as title,
                       {$suffix}name as name,
                       {$suffix}description as description,
                       {$suffix}params as params,
                       price,
                       deposit,
                       picture,
                       picture_small,
                       vendor,
                       vendor_code
                  FROM products WHERE id = {$id} LIMIT 1";
        $result = $this->db->query($sql);

        return isset($result[0]) ? $result[0] : null;
    }

    public function getProductsByCategory($start, $per_page = 10, $category_id, $active = false, $lang = 'ru')
    {
        $category_id = (int)$category_id;
        $sql = "SELECT * FROM products WHERE category_id={$category_id}";
        if ($active) {
            $sql .= " AND active = 'yes'";
        }
        $sql .= " LIMIT {$start}, {$per_page}";
        return $this->db->query($sql);
    }

    public function popularProducts()
    {
        $sql = "SELECT products.id,
                       products.active,
                       products.title,
                       products.name,
                       products.description,
                       products.params,
                       products.ua_title,
                       products.ua_name,
                       products.ua_description,
                       products.ua_params,
                       products.price,
                       products.picture,
                       products.picture_small,
                       products.vendor,
                       products.vendor_code,
                       products.deposit
                FROM products WHERE 1 LIMIT 8";
        return $this->db->query($sql);
    }

    //метод для получение всех продуктов
    public function getList($start = 1, $per_page = 10, $active = false)
    {
        $sql = "SELECT products.id,
                       products.active,
                       products.title,
                       products.name,
                       products.description,
                       products.params,
                       products.ua_title,
                       products.ua_name,
                       products.ua_description,
                       products.ua_params,
                       products.price,
                       products.picture,
                       products.picture_small,
                       products.vendor,
                       products.vendor_code,
                       products.deposit,
                       category.name as category_name
                FROM products";
        if ($active) {
            $sql .= ' AND active = 1';
        }
        $sql .= " LEFT JOIN category ON products.category_id = category.id LIMIT {$start}, {$per_page}";

        //var_dump($sql);die;
        return $this->db->query($sql);
    }

    public function save($data, $id = null)
    {
        //var_dump($data);
        if (empty($data['name']) || empty($data['title']) || empty($data['description']) || empty($data['params'])) {
            return false;
        }


        $data = array_map('trim', $data);

        $id = (int)$id;
        $title = $this->db->escape($data['title']);
        $ua_title = $this->db->escape($data['ua_title']);
        $name = $this->db->escape($data['name']);
        $ua_name = $this->db->escape($data['ua_name']);
        $description = $this->db->escape($data['description']);
        $ua_description = $this->db->escape($data['ua_description']);
        $params = $this->db->escape($data['params']);
        $ua_params = $this->db->escape($data['ua_params']);
        $price = number_format(floatval($data['price']), 2, '.', '');
        $deposit = number_format(floatval($data['deposit']), 2, '.', '');
        $category_id = (int)$data['category_id'];
        $vendor = $this->db->escape($data['vendor']);
        $vendor_code = $this->db->escape($data['vendor_code']);
        $active = isset($data['active']) ? 'yes' : 'no';

        if (!$id) { //Add new record
            $sql = "INSERT INTO products SET title = '{$title}',
                                             ua_title = '{$ua_title}',
                                             name = '{$name}',
                                             ua_name = '{$ua_name}',
                                             description = '{$description}',
                                             ua_description = '{$ua_description}',
                                             params = '{$params}',
                                             ua_params = '{$ua_params}',
                                             price = '{$price}',
                                             deposit = '{$deposit}',
                                             category_id = '{$category_id}',
                                             vendor = '{$vendor}',
                                             vendor_code = '{$vendor_code}',
                                             active = '{$active}'";

        } else {
            $sql = "UPDATE products SET title = '{$title}',
                                             ua_title = '{$ua_title}',
                                             name = '{$name}',
                                             ua_name = '{$ua_name}',
                                             description = '{$description}',
                                             ua_description = '{$ua_description}',
                                             params = '{$params}',
                                             ua_params = '{$ua_params}',
                                             price = '{$price}',
                                             deposit = '{$deposit}',
                                             category_id = '{$category_id}',
                                             vendor = '{$vendor}',
                                             vendor_code = '{$vendor_code}',
                                             active = '{$active}'
                                          WHERE id = {$id}";

        }

        $this->db->query($sql);
        if (!$id) {
            $id = $this->db->getLastInsertId();
        }

        return $id;

        //return $this->db->query($sql);
    }

    public function saveImageForProduct($id_product, $big_image, $small_image)
    {
        $id = (int)$id_product;
        $big_image = $this->db->escape($big_image);
        $small_image = $this->db->escape($small_image);

        $sql = "UPDATE products SET picture = '{$big_image}',
                                    picture_small = '{$small_image}'
                                WHERE id = {$id}";
        return $this->db->query($sql);
    }

    public function deleteImageForProduct($id)
    {
        $id = (int)$id;
        $sql = "SELECT picture, picture_small FROM products WHERE id = {$id} LIMIT 1";
        $images = $this->db->query($sql);

        if ($images && $images[0]) {
            foreach ($images[0] as $image) {
                if (!empty($image) && file_exists(ROOT . $image)) {
                    unlink(ROOT . $image);
                }
            }
        }

        $this->saveImageForProduct($id, null, null);
        return $this->db->query($sql);
    }

    public function setReserved($id)
    {
        $id = (int)$id;
        $sql = "UPDATE {$this->table_name} SET active = 'no' WHERE id = {$id}";
        return $this->db->query($sql);
    }

    //функции с техноскарба
    public function findForSearchAjax($data)
    {
        if (empty($data['query'])) {
            return false;
        }

        $query = $this->db->escape($data['query']);
        $sql = "SELECT id, name FROM {$this->table_name} WHERE name LIKE '%{$query}%'";
        return $this->db->query($sql);
    }

    public function findForSearchFull($data)
    {
        if (empty($data['query'])) {
            return false;
        }
        $query = $this->db->escape($data['query']);

        $sql = "SELECT products.id,
                       products.active,
                       products.title,
                       products.name,
                       products.description,
                       products.params,
                       products.ua_title,
                       products.ua_name,
                       products.ua_description,
                       products.ua_params,
                       products.price,
                       products.picture,
                       products.picture_small,
                       products.vendor,
                       products.vendor_code,
                       products.deposit
                FROM {$this->table_name} WHERE name LIKE '%{$query}%'";
        return $this->db->query($sql);
    }













    //функция с техноскарба
    public function seatchModelStock ($sstr, $region ='', $group = '') {
        $add_where = $this->seatchSqlCitiGroup($sstr, $region, $group);
        $cnt = $this->cnt;
        $sql = "SELECT DISTINCT models_id, "._suffix()."models_full_name as models_full_name, brands_name, groups_name, models_group, "._suffix()."types_name as types_name
		FROM ".DI_DBTABLES_MODELS."
				INNER JOIN ".DI_DBTABLES_ITEMS." ON items_model = models_id
				INNER JOIN ".DI_DBTABLES_BRANDS." ON brands_id = models_brand
				INNER JOIN ".DI_DBTABLES_GROUPS." ON groups_id = models_group
				INNER JOIN ".DI_DBTABLES_TYPES." ON types_id = models_type
		WHERE models_items > 0 " . $add_where . " and items_active = 'yes'
		ORDER BY models_items desc
		LIMIT 0," . $cnt ;
        $r = DB::query($sql);
        $html = $this->getHtmlSeatch($r);
        return $html;
    }

    /*
    *  Строка поиска по городам и регионам
    */
    private function seatchSqlCitiGroup ($sstr, $region ='', $group = '') {
        $sstr 	= self::clean_words(stripslashes($sstr ));
        $sstr1 = self::encodestring($sstr);
        $clean_keywords	= explode(' ', $sstr);

        for($j = 0; $j < count($clean_keywords); $j++) {
            $add_where .= " and ("._suffix()
                ."models_full_name like '%"
                . trim($clean_keywords[$j])
                ."%'  or "._suffix()
                ."models_full_name like '%" . trim(self::encodestring($clean_keywords[$j])) ."%' )";
        }
        if ((int)$region) {
            $add_where .= " and items_city = '".$region."'";
        }
        if ((int)$group) {
            $groups = di_get_groups ((int)$group);
            $add_where .= " and items_group in (".implode(",", $groups).")";
        }
        return $add_where;
    }

    /*
     *  Замена спец. символов
     */
    static public function clean_words ($str) {
        return trim(preg_replace('/[^a-zA-ZА-Яа-я0-9\s]/', ' ', $str));
    }

    /*
    *  Перекодировка строки для поиска
    */
    static public function encodestring($st)
    {
        $st = strtr($st,
            array(
                "Панасоник"=>"Panasonic", "панасоник"=>"panasonic",
                "алкател"=>"Alcatel", "Алкател"=>"Alcatel",
                "Асер"=>"Acer", "асер"=>"acer",
                "Эппл"=>"Apple", "эппл"=>"apple",
                "Атлон"=>"Athlon", "атлон"=>"athlon",
                "Бенкью"=>"BenQ", "бенкью"=>"BenQ",
                "Блекбери"=>"BlackBerry", "блекбери"=>"BlackBerry",
                "Бош"=>"BOSCH", "бош"=>"BOSCH",
                "Кенди"=>"Candy", "кенди"=>"Candy",
                "Селерон"=>"Celeron", "селерон"=>"Celeron",
                "Компак"=>"Compaq", "компак"=>"Compaq",
                "Коре"=>"Core", "коре"=>"Core",
                "Дэу"=>"DAEWOO", "дэу"=>"DAEWOO",
                "Делонги"=>"Delonghi", "делонги"=>"Delonghi",
                "Флай"=>"Fly", "флай"=>"Fly",
                "Айфон"=>"iPhone", "айфон"=>"iPhone",
                "Айпод"=>"iPod", "айпод"=>"iPod",
                "Жабра"=>"Jabra", "жабра"=>"Jabra",
                "Джабра"=>"Jabra", "джабра"=>"Jabra",
                "Кенвуд"=>"KENWOOD", "кенвуд"=>"KENWOOD",
                "Мулинекс"=>"Moulinex", "мулинекс"=>"Moulinex",
                "Филипс"=>"Philips", "филипс"=>"Philips",
                "Томас"=>"THOMAS", "томас"=>"THOMAS",
                "Томсон"=>"THOMSON", "томсон"=>"THOMSON",
                "Вирпл"=>"Whirlpool", "вирпл"=>"Whirlpool",
                "Ямаха"=>"Yamaha", "ямаха"=>"Yamaha",
                "сони"=>"sony",  "Сони"=>"Sony",
                "эриксон"=>"ericsson",  "Эриксон"=>"Ericsson",
                "икс"=>"x", "Икс"=>"X", "ИКС"=>"X", "экс"=>"x", "Экс"=>"X", "ЭКС"=>"X"
            )
        );

        // Сначала заменяем "односимвольные" фонемы.
        $st=strtr($st,"абвгдеёзийклмнопрстуфхъыэ_",
            "abvgdeeziyklmnoprstufh'iei");
        $st=strtr($st,"АБВГДЕЁЗИЙКЛМНОПРСТУФХЪЫЭ_",
            "ABVGDEEZIYKLMNOPRSTUFH'IEI");

        // Затем - "многосимвольные".
        $st = strtr($st,
            array(
                "ж"=>"zh", "ц"=>"ts", "ч"=>"ch", "ш"=>"sh",
                "щ"=>"shch","ь"=>"", "ю"=>"yu", "я"=>"ya",
                "Ж"=>"ZH", "Ц"=>"TS", "Ч"=>"CH", "Ш"=>"SH",
                "Щ"=>"SHCH","Ь"=>"", "Ю"=>"YU", "Я"=>"YA",
                "ї"=>"i", "Ї"=>"Yi", "є"=>"ie", "Є"=>"Ye"
            )
        );
        // Возвращаем результат.
        return $st;
    }
}