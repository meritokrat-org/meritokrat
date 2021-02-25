<?php

class ppo_peer extends db_peer_postgre
{
    const PRIVACY_PUBLIC  = 1;
    const PRIVACY_PRIVATE = 2;
    const PRIVACY_HIDDEN  = 3;

    protected $table_name = 'ppo';

    /**
     * @param string $peer
     * @return db_peer|ppo_peer
     */
    public static function instance($peer = 'ppo_peer')
    {
        load::model('ppo/members');

        return parent::instance($peer);
    }

    public static function get_type($id)
    {
        $types = self::get_types();

        return $types[$id];
    }

    public static function get_category($id)
    {
        $types = self::get_levels();

        return $types[$id];
    }

    public static function get_levels($withZeroDash = true)
    {
        $levels = [
            3 => t('Региональная'),
            2 => t('Местная'),
            1 => t('Первичная'),
        ];

        if (true === $withZeroDash) {
            $levels = [0 => '&mdash;'] + $levels;
        }

        return $levels;
    }

    public static function get_statuses()
    {
        return [
            2 => t('Схвалена'),
            1 => t('Не схвалена'),
            3 => t('Не легалiзована'),
            4 => t('Легалiзована'),
        ];
    }

    public static function get_ptypes()
    {
        return [
            1 => t('По месту жительства'),
            2 => t('По месту работы'),
            3 => t('По месту учебы'),
            4 => t('По профессиональными интересами'),
            5 => t('По другим интересам'),
        ];
    }

    public static function get_level($id)
    {
        $levels = self::get_levels();

        return $levels[$id];
    }

    public static function get_teritory($id)
    {
        $teritories = self::get_teritories();

        return $teritories[$id];
    }

    public static function get_user_ppo($user_id, $category = 1)
    {
        return db::get_row(
            'SELECT * FROM ppo WHERE active=:active AND category=:category
            AND id in(SELECT group_id FROM ppo_members WHERE user_id = :user_id)',
            ['user_id' => $user_id, 'active' => 1, 'category' => $category]
        );
    }

    public function findByLevelAndRegion($level = 3, $regionId = null)
    {
        $sql  = [
            'select id, title, category, region_id, city_id',
            'from ppo',
            'where category = :level',
        ];
        $bind = ['level' => $level];

        if (null !== $regionId) {
            $sql[]            = 'and region_id = :regionId';
            $bind['regionId'] = $regionId;
        }

        $sql[] = 'order by title';

        return db::get_rows(implode(' ', $sql), $bind);
    }

    /* метод используется также для того чтобы получить список групп
    контент которых можно показывать текущему пользователю */

    public function regenerate_photo_salt($id)
    {
        $salt = substr(md5(microtime(true)), 0, 8);

        $this->update(['photo_salt' => $salt, 'id' => $id]);

        return $salt;
    }

    public function update($data, $keys = null)
    {
        parent::update($data, $keys);
        $this->reindex($data[$this->primary_key]);
    }

    public function reindex($id)
    {
        $index_columns = ['title'];
        $index_expr    = 'coalesce('.implode(',\'\') ||\' \'|| coalesce(', $index_columns).',\'\')';

        db::exec(
            'UPDATE '.$this->table_name.' SET fti = to_tsvector(\'russian\', '.$index_expr.') WHERE id = :id',
            ['id' => $id]
        );
    }

    public function get_new($limit = 500)
    {
        //@todo: приделать кеширование

        //$where['active'] = 1;
        $where = [];
        if (!session::has_credential('admin')) {
            $where['active']  = 1;
            $where['type']    = 2;
            $where['hidden']  = 0;
            $where['user_id'] = session::get_user_id();
            $where['to_id']   = session::get_user_id();
            $sql              .= 'AND active=:active AND (hidden=:hidden OR ppo.id in (SELECT group_id from ppo_members WHERE user_id=:user_id) OR ppo.id in (SELECT obj_id from invites WHERE to_id=:to_id AND type=:type))';
        }
        $seven_day = mktime(0, 0, 0, date('m', time()), date('d', time()), date('Y', time())) - 60 * 60 * 24 * 7;

        return db::get_cols("SELECT id FROM ppo WHERE created_ts>$seven_day ".$sql.' ORDER BY id DESC;', $where);
    }

    public function get_project_new($limit = 500)
    {
        $where['active'] = 1;
        $where['hidden'] = 0;

        return $this->get_list($where, [], ['id DESC'], $limit);
    }

    public function get_hot($category, $ptype = null, $status = null, $region = null, $citi = null)
    {
        if ($category) {
            $where['category'] = $category;
        }

        if ($ptype) {
            $where['ptype'] = $ptype;
        }

        if ($region) {
            $where['region_id'] = $region;
        }

        if ($citi) {
            $where['city_id'] = $citi;
        }

        if ($status) {
            switch ($status) {
                case 1:
                case 2:
                    $where['active'] = $status == 2 ? 1 : 0;
                    break;
                case 3:
                    $sql .= " (dovidnum='' OR doviddate=0) AND ";
                    break;
                case 4:
                    $sql .= " dovidnum!='' AND doviddate!=0 AND ";
                    break;
            }
        }

        $where_clause = [];
        foreach ($where as $key => $value) {
            $where_clause[] = "{$key} = :{$key}";
            $bind[$key]     = $value;
        }

        $sql .= implode(' AND ', $where_clause);
        #echo $sql;
        if ($type) {
            $sql .= ' AND ppo.type = '.$type;
        }


        if (!session::has_credential('admin')) {
            // $where['active']       = 1;
            $where['invites_type'] = 2;
            $where['hidden']       = 0;
            $where['user_id']      = session::get_user_id();
            $where['to_id']        = session::get_user_id();
            $sql                   .= ' AND (hidden=:hidden OR ppo.id in (SELECT group_id from ppo_members WHERE user_id=:user_id) OR ppo.id in (SELECT obj_id from invites WHERE to_id=:to_id AND invites.type=:invites_type))';
        }
        $this->hot_sql   = $sql;
        $this->hot_where = $where;

        $sql = <<<SQL
SELECT ppo.id
FROM ppo
         LEFT JOIN ppo_members ON (ppo.id = ppo_members.group_id)
WHERE 1 = 1
  AND {$sql}
GROUP BY ppo.id
ORDER BY count(ppo.id) DESC;
SQL;

        return db::get_cols($sql, $where);
    }

    public function get_by_members_colls()
    {
        if (!session::has_credential('admin')) {
            $where['active']  = 1;
            $where['type']    = 2;
            $where['hidden']  = 0;
            $where['user_id'] = session::get_user_id();
            $where['to_id']   = session::get_user_id();
            $sql              = 'WHERE active=:active AND hidden=:hidden OR ppo.id in (SELECT group_id from ppo_members WHERE user_id=:user_id) OR ppo.id in (SELECT obj_id from invites WHERE to_id=:to_id AND type=:type)';
        } else {
            if (!session::has_credential('superadmin')) {
                $where            = ['hidden' => 0];
                $where['user_id'] = session::get_user_id();
                $sql              = 'WHERE hidden=:hidden OR ppo.id in (SELECT group_id from ppo_members WHERE user_id=:user_id)';
            } else {
                $where = [];
            }
        }
        if (session::get_user_id() == 996) {
            //var_dump($where);
            //die("SELECT ppo.id FROM ppo LEFT JOIN ppo_members ON (ppo.id = ppo_members.group_id) ".$sql." GROUP BY ppo.id ORDER BY count(ppo.id) DESC;");
        }

        return db::get_cols(
            'SELECT ppo.id FROM ppo LEFT JOIN ppo_members ON (ppo.id = ppo_members.group_id) '.$sql.' GROUP BY ppo.id ORDER BY count(ppo.id) DESC;',
            $where
        );
    }

    public function update_rate($id, $value, $user_id = null)
    {
        if (!$data = $this->get_item($id)) {
            return;
        }

        if ($user_id) {
            $value = $value * user_data_peer::instance()->get_rate_multiplier($user_id);
        }

        $this->update(
            [
                'id'   => $id,
                'rate' => $data['rate'] + $value,
            ]
        );
    }

    public function search($keyword, $limit = 5)
    {
        $keyword = str_replace(' ', ' | ', $keyword);
        $sql     = 'SELECT id FROM '.$this->table_name.' WHERE fti @@ to_tsquery(\'russian\', :keyword) LIMIT :limit;';

        return db::get_cols($sql, ['keyword' => $keyword, 'limit' => $limit], $this->connection_name);
    }

    public function search_by_title($request)
    {
        return db::get_cols(
            'SELECT id FROM '.$this->table_name." WHERE title ILIKE '%".trim(strip_tags($request))."%'"
        );
    }

    public function insert($data, $ignore_duplicate = false)
    {
        $id = parent::insert($data, $ignore_duplicate);
        $this->reindex($id);

        return $id;
    }

    public function is_moderator($id, $user_id, $show_for_admin = true)
    {
        if (!session::has_credential('admin')) {
            return ppo_members_peer::instance()->allow_edit($user_id, $this->get_item($id));
        } else {
            return true;
        }
    }

    public function add_moderator($id, $user_id)
    {
        $moderators   = $this->get_moderators($id);
        $moderators[] = $user_id;
        $moderators   = array_unique($moderators);

        if (!ppo_members_peer::instance()->is_member($id, $user_id)) {
            ppo_members_peer::instance()->add($id, $user_id);
        }

        db_key::i()->set('ppo_moderators:'.$id, serialize($moderators));
    }

    public function get_moderators($id, $ppo = [])
    {
        return [
            ppo_members_peer::instance()->get_user_by_function(1, $id, $ppo),
            ppo_members_peer::instance()->get_user_by_function(2, $id, $ppo),
        ];
    }

    public function delete_moderator($id, $user_id)
    {
        $moderators = $this->get_moderators($id);
        $moderators = array_diff($moderators, [$user_id]);

        db_key::i()->set('ppo_moderators:'.$id, serialize($moderators));
    }

    public function get_select_list()
    {
        $list   = $this->get_list();
        $select = [];
        foreach ($list as $id) {
            $data        = $this->get_item($id);
            $select[$id] = stripcslashes($data['title']);
        }

        return $select;
    }

    public function get_by_region($region_id = 0, $select = true, $category = false, $order = null)
    {
        if ($category) {
            $sqladd = ' AND category = '.$category;
        }

        $query = db::get_rows(
            'SELECT id,title,number FROM '.$this->table_name.' WHERE active = 1 AND region_id = '.$region_id.$sqladd.$order
        );
        if ($select) {
            $array = [];
            foreach ($query as $q) {
                $array[$q['id']] = $q['title'];
            }

            return $array;
        } else {
            return $query;
        }
    }

    public function get_by_city($city_id = 0, $category = 1)
    {
        $query = db::get_rows(
            'SELECT id,title FROM '.$this->table_name.' WHERE active = 1 AND category = '.$category.' AND city_id = '.$city_id
        );

        $array = [];
        foreach ($query as $q) {
            $array[$q['id']] = $q['title'];
        }

        return $array;
    }

    public function get_children($ppo, $category = 2, $get_category = 1, $sqladd = '')
    {
        $sql  = ['SELECT id, title, number FROM %s WHERE active = :active AND category = :level'];
        $bind = [
            'active' => 1,
            'level'  => $get_category,
        ];

        if (2 === $category) {
            $sql[]          = 'AND city_id = :cityId'; //$ppo['city_id'], $get_category);
            $bind['cityId'] = $ppo['city_id'];
        } else {
            $sql[]            = 'AND region_id = :regionId';//, $ppo['region_id'], $get_category);
            $bind['regionId'] = $ppo['region_id'];
        }

        $sql[] = 'ORDER BY city_id, title';
        $sql   = sprintf(implode(' ', $sql), $this->table_name);

        return db::get_rows($sql, $bind);
    }

    public function getPpoMembersRecursive($ppo)
    {
        $sql  = <<< SQL
select p.region_id as region,
       array_to_json(array_agg(pm.user_id)) as ids
from ppo p
         join ppo_members pm on pm.group_id = p.id
where p.category <= :category
  and p.region_id = :region_id
SQL;
        $bind = [
            'category'  => $ppo['category'],
            'region_id' => $ppo['region_id'],
        ];

        if ($bind['category'] < 3) {
            $sql             .= ' and p.city_id = :city_id';
            $bind['city_id'] = $ppo['city_id'];
        }

        $sql .= ' group by p.region_id';

        $row = db::get_row($sql, $bind);

        if (!array_key_exists('ids', $row)) {
            return [];
        }

        $ids = json_decode($row['ids'], true);
        $sql = 'select id from user_auth where id in (%s)';

        if (!session::has_credential('admin')) {
            $sql .= ' and status not in (-1, 1, 3)';
        }

        return db::get_cols(sprintf($sql, implode(', ', $ids)));
    }

    public function get_all_children($po_id = 0)
    {
        $item         = parent::get_item($po_id);
        $children_ppo = $children_mpo = [];
        if ($item['category'] > 1) {
            $children_ppo = $this->get_children_array($item, $item['category']);
            if ($item['category'] > 2) {
                $children_mpo = $this->get_children_array($item, 3, 2);
            }
        }

        return array_merge([$po_id], $children_ppo, $children_mpo);
    }

    private function get_children_array($ppo, $category = 2, $get_category = 1)
    {
        if ($category == 2) {
            $sqladd .= 'AND city_id='.$ppo['city_id']." and category = $get_category";
        } else {
            $sqladd .= 'AND region_id='.$ppo['region_id']." and category = $get_category";
        }

        return db::get_cols('SELECT id FROM '.$this->table_name.' WHERE active = 1 '.$sqladd.'');
    }

    public function get_ppo_regions($category = 0)
    {
        if ($category > 0) {
            $sqladd = "WHERE category = $category";
        }
        $regions = db::get_rows("SELECT region_id FROM ppo $sqladd GROUP by region_id ORDER by region_id");

        return $regions;
        foreach ($regions as $r) {
            $name_arr[$r['title']] = $r['region_id'];
        }
        ksort($name_arr);
        foreach ($name_arr as $nr) {
            $regs[] = ['region_id' => $nr];
        }

        return $regs;
        print_r($regs);
        die();
    }

    public function leaders_by_user_data($user_data = [])
    {
        if (!$user_data['user_id']) {
            return false;
        }
        $cache_key = 'ppo_leaders_'.$user_data['user_id'];
        if (mem_cache::i()->exists($cache_key)) {
            return mem_cache::i()->get($cache_key);
        }
        $leaders = [];
        for ($i = 1; $i < 4; $i++) {
            $ppo = $this->get_by_user_data($user_data, $i);
            if ($ppo) {
                $usr = ppo_members_peer::instance()->get_users_by_function(1, $ppo);
                if ($usr[0]) {
                    $leaders[] = $usr[0];
                }
            }
        }
        mem_cache::i()->set($cache_key, $leaders);

        return $leaders;
    }

    public function get_by_user_data($user_data = [], $category = 1)
    {
        switch ($category) {
            case 1:
                if ($user_data['user_id']) {
                    return db::get_scalar(
                        'SELECT id FROM '.$this->table_name.' WHERE active=:active
                        AND category=:category
                        AND id in(SELECT group_id FROM ppo_members WHERE user_id = :user_id)',
                        ['user_id' => $user_data['user_id'], 'active' => 1, 'category' => 1]
                    );
                }
                break;
            case 2:
                if ($user_data['city_id']) {
                    return db::get_scalar(
                        'SELECT id FROM '.$this->table_name.' WHERE active=:active
                        AND category=:category
                        AND city_id=:city_id',
                        ['city_id' => $user_data['city_id'], 'active' => 1, 'category' => 2]
                    );
                }
                break;
            case 3:
                if ($user_data['region_id']) {
                    return db::get_scalar(
                        'SELECT id FROM '.$this->table_name.' WHERE active=:active
                        AND category=:category
                        AND region_id=:region_id',
                        ['region_id' => $user_data['region_id'], 'active' => 1, 'category' => 3]
                    );
                }
                break;
        }

        return false;
    }
}
