<?php

class user_data_peer extends db_peer_postgre
{

    protected $table_name = 'user_data';
    protected $primary_key = 'user_id';
    protected $lang_cols = [
        "first_name",
        "last_name",
        "father_name",
        "location",
        "street",
        "about",
        "interests",
        "books",
        "films",
        "sites",
        "music",
        "leisure",
        "house",
    ];

    public static function instance($peer = 'user_data_peer')
    {
        load::action_helper('lang', false);

        return parent::instance($peer);
    }

    public static function get_contact_type($id)
    {
        $list = self::get_contact_types();

        return $list[$id];
    }

    public static function get_contact_types()
    {
        return [
            3 => 'Facebook',
            7 => 'Instagram',
            4 => 'Телеграм',
            5 => 'WhatsApp',
            6 => 'Signal',
        ];
    }

    public static function get_can_do($id = false)
    {
        if ($id == 0) {
            return '';
        }
        $list = [
            1 => t('готов заниматься интернет агитацией'),
            2 => t('готов заниматься уличной агитацией'),
            3 => t('могу помогать финансово (каждая гривня имеет значение)'),
            4 => t('другое'),
        ];

        if (!$id) {
            return $list;
        }

        return $list[$id];
    }

    public function prepare_contact($contact, $type)
    {
        $contact = mb_strtolower($contact);

        if (mb_substr($contact, 0, 7) !== 'http://' and $type < 11) {
            $contact = 'http://' . $contact;
        }

        // может на регулярки переписать?
        switch ($type) {
            case 1:
                preg_match('#([0-9a-zA-Z_]+$)#', $contact, $ctc);
                if ($ctc[1]) {
                    $contact = 'http://vkontakte.ru/' . $ctc[1];
                }
                //if ($contact>1) $contact="http://vkontakte.ru/id".$contact;
                //elseif(mb_substr($contact,0,15)!='http://vkontakt' and mb_substr($contact,0,13)!='http://vk.com' and mb_substr($contact,0,17)!='http://www.vk.com' and mb_substr($contact,0,19)!='http://www.vkontakt') $contact='http://vkontakte.ru/'.str_replace("http://","",$contact);
                break;

            case 2:
                if (mb_substr($contact, 0, 11) != 'http://odno' and mb_substr($contact, 0, 15) != 'http://www.odno') {
                    $contact = 'http://odnoklassniki.ru/' . str_replace("http://", "", $contact);
                }
                break;

            case 3:
                if (mb_substr($contact, 0, 19) != 'http://facebook.com' and mb_substr(
                        $contact,
                        0,
                        23
                    ) != 'http://www.facebook.com') {
                    $contact = 'http://facebook.com/' . str_replace("http://", "", $contact);
                }
                break;

            case 4:
                if (mb_substr($contact, 0, 18) != 'http://twitter.com' and mb_substr(
                        $contact,
                        0,
                        22
                    ) != 'http://www.twitter.com') {
                    $contact = 'http://twitter.com/' . str_replace("http://", "", $contact);
                }
                break;

            case 5:
                if (mb_substr($contact, 0, 19) != 'http://linkedin.com' and mb_substr(
                        $contact,
                        0,
                        23
                    ) != 'http://www.linkedin.com') {
                    $contact = 'http://linkedin.com/' . str_replace("http://", "", $contact);
                }
                break;

            case 7:
                if (!preg_match('/politiko\./', $contact)) {
                    $contact = 'http://politiko.ua/' . str_replace("http://", "", $contact);
                }
                break;

            case 9:
                if (mb_substr($contact, 0, 16) != 'http://profeo.ua' and mb_substr(
                        $contact,
                        0,
                        20
                    ) != 'http://www.profeo.ua') {
                    $contact = 'http://profeo.ua/' . str_replace("http://", "", $contact);
                }
                break;

            case 10:
                if (mb_substr($contact, 0, 17) != 'http://connect.ua' and mb_substr(
                        $contact,
                        0,
                        21
                    ) != 'http://www.connect.ua') {
                    $contact = 'http://connect.ua/' . str_replace("http://", "", $contact);
                }
                break;

            case 11:
                if (mb_substr($contact, 0, 23) != 'https://plus.google.com') {
                    $contact = 'https://plus.google.com/' . str_replace("https://", "", $contact);
                }
                break;

            default :

                break;
        }

        return $contact;
    }

    public function get_new_names()
    {
        return db::get_rows(
            "SELECT user_id, first_name, last_name, new_fname, new_lname FROM user_data WHERE new_fname IS NOT NULL or new_lname IS NOT NULL"
        );
    }

    public function regenerate_photo_salt($id, $new = '')
    {
        $salt = substr(md5(microtime(true)), 0, 8);

        $this->update([$new . 'photo_salt' => $salt, 'user_id' => $id]);

        return $salt;
    }

    public function update($data, $keys = null)
    {
        parent::update(lang_helper::set_lang_cols($this->lang_cols, $data), $keys);
        $this->reindex($data[$this->primary_key]);
    }

    public function reindex($id)
    {
        $index_columns = ['first_name', 'last_name', 'interests', 'about', 'position'];
        $index_expr    = 'coalesce(' . implode(',\'\') ||\' \'|| coalesce(', $index_columns) . ',\'\')';

        db::exec(
            'UPDATE ' . $this->table_name . ' SET fti = to_tsvector(\'russian\', ' . $index_expr . ') WHERE ' . $this->primary_key . ' = :id',
            ['id' => $id]
        );
    }

    public function has_trusted($profile_id, $user_id)
    {
        return db_key::i()->exists('profile_trust:' . $profile_id . ':' . $user_id);
    }

    public function my_trust($profile_id, $user_id)
    {
        return db_key::i()->get('profile_trust:' . $profile_id . ':' . $user_id) == 1;
    }

    public function trust($profile_id, $user_id, $trust = true)
    {
        db_key::i()->set('profile_trust:' . $profile_id . ':' . $user_id, $trust);
    }

    public function get_views_cloud()
    {
        $sql  = 'SELECT political_views, count(user_id) as total FROM ' . $this->table_name . ' GROUP BY political_views LIMIT 10';
        $list = db::get_rows($sql, [], $this->connection_name);

        return tags_helper::normalize($list, 'total');
    }

    public function get_people($user_id)
    {
        return parent::get_list(['owner_id' => $user_id], []);
    }

    public function update_rate($owner_id, $value, $user_id = null)
    {
        if (!$data = $this->get_item($owner_id)) {
            return;
        }

        if ($user_id) {
            $value = $value * $this->get_rate_multiplier($user_id);
        }

        $this->update(
            [
                'user_id' => $owner_id,
                'rate'    => $data['rate'] + $value,
            ]
        );
    }

    public function get_item($id, $session = 'language')
    {
        $data = parent::get_item($id);

        return lang_helper::get_lang_cols($this->lang_cols, $data, $session);
    }

    public function get_rate_multiplier($user_id)
    {
        $actor_data = $this->get_item($user_id);

        if ($actor_data['rate'] < 10) {
            $multiplier = 0.01;
        } else {
            if ($actor_data['rate'] < 100) {
                $multiplier = 0.3;
            } else {
                if ($actor_data['rate'] < 300) {
                    $multiplier = 0.5;
                } else {
                    $multiplier = 1;
                }
            }
        }

        return $multiplier;
    }

    public function search($keyword, $filters = [], $limit = 20, $offset = 0)
    {
        $where = ['fti @@ to_tsquery(\'russian\', :keyword)'];
        $bind  = ['keyword' => $keyword, 'limit' => $limit, 'offset' => $offset];

        if ($filters) {
            foreach ($filters as $name => $value) {
                if (is_array($value)) {
                    if ($value[0]) {
                        $where[]               = "{$name} >= :{$name}_from";
                        $bind[$name . '_from'] = $value[0];
                    }

                    if ($value[1]) {
                        $where[]             = "{$name} <= :{$name}_to";
                        $bind[$name . '_to'] = $value[1];
                    }
                } else {
                    $where[]     = "{$name} = :{$name}";
                    $bind[$name] = $value;
                }
            }
        }

        $keyword = str_replace(' ', ' & ', $keyword);
        $sql     = 'SELECT ' . $this->primary_key . '
				FROM ' . $this->table_name . '
				WHERE ' . implode(' AND ', $where) . '
				LIMIT :limit OFFSET :offset;';

        return db::get_cols($sql, $bind, $this->connection_name);
    }

    public function my_search($filters = [], $limit = 20, $offset = 0, $order = 'user_id DESC')
    {
        $where = ['1=1'];
        $bind  = ['limit' => $limit, 'offset' => $offset];
        if (in_array($filters['function'], [5, 6, 18])) {
            if ($filters['region_id'] > 0) {
                $fsqladd = " AND region_id=" . $filters['region_id'];
            }
            if ($filters['city_id'] > 0 && $filters['function'] == 6) {
                $fsqladd .= " AND city_id=" . $filters['city_id'];
            }
            unset($filters['region_id']);
            unset($filters['city_id']);
            unset($filters['country_id']);

            $where[] = "user_id in (SELECT user_id FROM user_desktop_funct WHERE function_id=" . $filters['function'] . " $fsqladd)";
        }
        if ($filters) {
            foreach ($filters as $name => $value) {
                if (is_array($value)) {
                    if ($value[0]) {
                        $where[]               = "{$name} <= :{$name}_from";
                        $bind[$name . '_from'] = $value[0];
                    }

                    if ($value[1]) {
                        $where[]             = "{$name} >= :{$name}_to";
                        $bind[$name . '_to'] = $value[1];
                    }
                } else if ($name == 'segment') {
                    if ($value > 0) {
                        $where[]                    = "({$name} = :{$name} OR additional_segment = :additional_segment)";
                        $bind[$name]                = $value;
                        $bind['additional_segment'] = $value;
                    }
                } else if ($name == 'about') {
                    $value == 1 ? $where[] = "user_id  in (SELECT user_id FROM user_shevchenko_data WHERE about!='')" : $where[] = "user_id  in (SELECT user_id FROM user_shevchenko_data WHERE about='')";
                } else if ($name == 'visit_ts') {
                    $bind[$name] = time() - abs($value * 24 * 60 * 60);
                    if ($value > 0) {
                        $where[] = "user_id in (SELECT user_id FROM user_sessions WHERE {$name} > :{$name})";
                    } else if ($value < 0) {
                        $where[] = "user_id in (SELECT user_id FROM user_sessions WHERE {$name} < :{$name})";
                    }
                } else if ($name == 'last_name' or $name == 'first_name') {
                    if (strlen($value) > 0) {
                        $where[]     = "{$name} ILIKE :{$name}";
                        $bind[$name] = '%' . $value . '%';
                    }
                } else if ($name == 'function') {
                    if ($value > 0 && !in_array($value, [5, 6, 18])) {
                        $where[]     = "user_id in (SELECT user_id FROM user_desktop WHERE functions && :function)";
                        $bind[$name] = '{' . $value . '}';
                    }
                } else if ($name == 'contacted') {
                    if ($value == 100) {
                        $where[] = "(user_id in (SELECT user_id FROM user_novasys_data WHERE contacted=0) or (user_id not in (SELECT user_id FROM user_novasys_data)) AND user_id not in (SELECT id FROM user_auth WHERE active is false))";
                    } else if ($value > 0) {
                        $where[]     = "user_id in (SELECT user_id FROM user_novasys_data WHERE contacted=:contacted)";
                        $bind[$name] = $value;
                    }
                } //выборка по полям из user_auth
                else if (in_array(
                    $name,
                    ['email', 'type', 'active', 'chief_contact', 'coordinator_contact', 'interesting']
                )) {
                    if ($value) {
                        $where_auth[] = $name . "=:" . $name;
                        $bind[$name]  = $value;
                    }
                } else if ($name == 'start_begin') {
                    $where_auth[]       = "created_ts>:created_ts AND created_ts<:created_ts_end";
                    $bind['created_ts'] = $value;
                    $filters['start_end'] ? $bind['created_ts_end'] = $filters['start_end'] : $bind['created_ts_end'] = time();
                } else if ($name == 'activation_begin') {
                    $where_auth[]         = "activated_ts>:activated_ts AND activated_ts<:activated_ts_end";
                    $bind['activated_ts'] = $value;
                    $filters['activation_end'] ? $bind['activated_ts_end'] = $filters['activation_end'] : $bind['activated_ts_end'] = time();
                } else if ($name == 'activation_end') {
                    if (!$filters['activation_begin']) {
                        $where_auth[]             = "activated_ts>:activated_ts AND activated_ts<:activated_ts_end";
                        $bind['activated_ts']     = 1;
                        $bind['activated_ts_end'] = $value;
                    }
                } else if ($name == 'start_end') {
                    if (!$filters['start_begin']) {
                        $where_auth[]           = "created_ts>:created_ts AND created_ts<:created_ts_end";
                        $bind['created_ts']     = 1;
                        $bind['created_ts_end'] = $value;
                    }
                } else if ($name == 'expert') {
                    if ($value == 99) {
                        $where_auth[] = "expert != '0' AND expert != ''";
                    } else {
                        $where_auth[] = "expert = :expert";
                        $bind[$name]  = $value;
                    }
                } else if ($name == 'offline') {
                    if (session::has_credential('admin')) {
                        if ($value == 2) {
                            $where_auth['offline'] = "(offline = 0 AND del = 0)";
                        } else if ($value == 3) {
                            $where_auth['offline'] = "(offline > 0 OR del > 0)";
                        }
                    }
                } else if ($name == 'contact_status') {
                    $where[]                = "contact_status = :contact_status";
                    $bind['contact_status'] = $value;
                } else if ($name == 'phone') {
                    $where[] = "((mobile LIKE '%" . $value . "%' OR phone LIKE '%" . $value . "%' OR work_phone LIKE '%" . $value . "%' OR home_phone LIKE '%" . $value . "%')
                                            OR user_id in (SELECT user_id FROM user_novasys_data WHERE phone LIKE '%" . $value . "%' OR mphone1 LIKE '%" . $value . "%' OR mphone1a LIKE '%" . $value . "%' OR phone2 LIKE '%" . $value . "%' OR phone3 LIKE '%" . $value . "%' OR hphone3 LIKE '%" . $value . "%' OR mphone3 LIKE '%" . $value . "%')
                                            OR user_id in (SELECT user_id FROM user_shevchenko_data WHERE phone LIKE '%" . $value . "%'))";
                } else if ($name == 'has_phone' && !$filters['phone']) {
                    if ($value == 1) {
                        $where[] = "((mobile != '' AND mobile IS NOT NULL) OR (phone != '' AND phone IS NOT NULL) OR (work_phone != '' AND work_phone IS NOT NULL) OR (home_phone != '' AND home_phone IS NOT NULL))";
                    } else {
                        $where[] = "((mobile = '' OR mobile IS NULL) AND (phone = '' OR phone IS NULL) AND (work_phone = '' OR work_phone IS NULL) AND (home_phone = '' OR home_phone IS NULL))";
                    }
                } else if ($name == 'target') {
                    $add            = " AND target && :target";
                    $bind['target'] = $value;
                } else if ($name == 'admin_target') {
                    $add                  .= " AND admin_target && :admin_target";
                    $bind['admin_target'] = $value;
                } else if ($name == 'status') {
                    if ($value == 99) {
                        $value   = 10;
                        $where[] = "user_id in (SELECT id FROM user_auth WHERE status>=" . $value . ")";
                    } else {
                        if ($value == 10) {
                            $value = 10;
                        }
                        if ($value == 20) {
                            $where[] = "user_id in (SELECT id FROM user_auth WHERE (status=20 OR ban=20) AND del=0)";
                        } else {
                            $where[] = "user_id in (SELECT id FROM user_auth WHERE status=" . $value . " AND del=0)";
                        }
                    }
                } else if ($name == 'work_jobsearch1' || $name == 'work_jobsearch2') {
                    if ($filters['work_jobsearch1'] > 0) {
                        $in[] = 1;
                    }
                    if ($filters['work_jobsearch2'] > 0) {
                        $in[] = 2;
                    }
                    $where[] = "user_id in (SELECT user_id FROM user_work WHERE work_jobsearch IN(" . implode(
                            ",",
                            $in
                        ) . "))";
                } else {
                    $where[]     = "{$name} = :{$name}";
                    $bind[$name] = $value;
                }
            }
        }

        if (!session::has_credential('admin')) {
            $where_auth['offline'] = "(del = 0)"; // offline = 0 AND
            $where['suslik']       = "(user_id NOT IN(SELECT id FROM user_auth WHERE suslik = :suslik)
                    OR user_id IN (SELECT user_id FROM " . $this->table_name . " 
                        WHERE share_users && '{" . session::get_user_id() . "}'))";
            $bind['suslik']        = 1;
        }

        if (session::has_credential('admin') && request::get('suslik')) {
            $where['suslik'] = "user_id IN(SELECT id FROM user_auth WHERE suslik = :suslik)";
            $bind['suslik']  = 1;
        }
        if (session::has_credential('admin') && in_array(request::get('real_app'), [1, 2])) {
            $where['real_app'] = "user_id IN (SELECT user_id FROM user_zayava WHERE real_app = :real_app)";
            $bind['real_app']  = (request::get('real_app') - 1);
        }
        if (count($where_auth) > 0) {
            $where['auth'] = 'user_id in (SELECT id FROM user_auth WHERE ' . implode(' AND ', $where_auth) . ')';
        }
        load::model('user/user_desktop');
        $is_regional_coordinator = user_desktop_peer::instance()->is_regional_coordinator(session::get_user_id(), true);
        $is_raion_coordinator    = user_desktop_peer::instance()->is_raion_coordinator(session::get_user_id(), true);

        if (!session::has_credential('admin') &&
            !((in_array($filters['region_id'], $is_regional_coordinator)) && $filters['region_id']) &&
            !((in_array($filters['city_id'], $is_raion_coordinator)) && $filters['city_id'])
        ) {
//			$where['active_check'] = "user_id in (SELECT id FROM user_auth WHERE active=:active_check)";
//			$bind['active_check'] = 1;
        }
        $sql = 'SELECT ' . $this->primary_key . '
				FROM ' . $this->table_name . '
				WHERE ' . implode(' AND ', $where) . $add . '
				ORDER BY ' . $order . '  LIMIT :limit OFFSET :offset;';
        /* if (session::get_user_id()==1360) {
          print_r($bind);
          die ($sql);
          } */
        //if(session::get_user_id()==996)print_r($filters);

        $result = db::get_cols($sql, $bind, $this->connection_name);

        //if(session::get_user_id()==1360)print_r($sql);

        if (!session::has_credential('admin')
            && count($is_regional_coordinator > 0)
            && $filters['active'] == 'FALSE'
            && !$filters['region_id']) {
            unset($bind['limit'], $bind['offset'], $where['active_check'], $bind['active_check']);
            $query = db::get_cols(
                'SELECT ' . $this->primary_key . '
                                    FROM ' . $this->table_name . '
                                    WHERE region_id IN(' . implode(",", $is_regional_coordinator) . ') AND ' . implode(
                    ' AND ',
                    $where
                ),
                $bind
            );

            $result = array_diff($result, $query);
            $result = array_merge($result, $query);
        }

        if (!session::has_credential(
                'admin'
            ) && $is_regional_coordinator && ($filters['offline'] == 1 || $filters['offline'] == 3)) {
            unset($bind['limit'], $bind['offset'], $where['active_check'], $bind['active_check'], $where_auth['offline'], $where['auth']);
            if ($filters['offline'] == 3) {
                $where_auth['offline'] = "offline > 0";
                $result                = [];
            }
            if (count($where_auth) > 0) {
                $where['auth'] = 'user_id in (SELECT id FROM user_auth WHERE ' . implode(' AND ', $where_auth) . ')';
            }
            $query = db::get_cols(
                'SELECT ' . $this->primary_key . '
                                    FROM ' . $this->table_name . '
                                    WHERE region_id IN(' . implode(",", $is_regional_coordinator) . ') AND ' . implode(
                    ' AND ',
                    $where
                ),
                $bind
            );

            $result = array_diff($result, $query);
            $result = array_merge($result, $query);
        }

        if (!session::has_credential('admin')
            && count($is_raion_coordinator) > 0
            && $filters['active'] == 'FALSE'
            && !$filters['city_id']) {
            unset($bind['limit'], $bind['offset'], $where['active_check'], $bind['active_check']);
            $query = db::get_cols(
                'SELECT ' . $this->primary_key . '
                                    FROM ' . $this->table_name . '
                                    WHERE city_id IN(' . implode(",", $is_raion_coordinator) . ') AND ' . implode(
                    ' AND ',
                    $where
                ),
                $bind
            );

            $result = array_diff($result, $query);
            $result = array_merge($result, $query);
        }

        if (!session::has_credential('admin') && count(
                $is_raion_coordinator > 0
            ) && ($filters['offline'] == 1 || $filters['offline'] == 3)) {
            unset($bind['limit'], $bind['offset'], $where['active_check'], $bind['active_check'], $where_auth['offline'], $where['auth']);
            if ($filters['offline'] == 3) {
                $where_auth['offline'] = "offline > 0";
                $result                = [];
            }
            if (count($where_auth) > 0) {
                $where['auth'] = 'user_id in (SELECT id FROM user_auth WHERE ' . implode(' AND ', $where_auth) . ')';
            }
            if ($is_raion_coordinator[0] > 0) {
                $sqladd = 'city_id IN(' . implode(",", $is_raion_coordinator) . ') AND';
            }
            $query = db::get_cols(
                'SELECT ' . $this->primary_key . '
                                    FROM ' . $this->table_name . '
                                    WHERE ' . $sqladd . ' ' . implode(' AND ', $where),
                $bind
            );

            $result = array_diff($result, $query);
            $result = array_merge($result, $query);
        }

        /* if($filters['region_id'])
          {
          if(count($result)>0)
          {
          $region_c = db::get_cols("SELECT user_id FROM user_desktop WHERE functions && '{5}' AND user_id IN (".implode(',',$result).")");
          $raion_c = db::get_cols("SELECT user_id FROM user_desktop WHERE functions && '{6}' AND user_id IN (".implode(',',$result).")");

          $raion_c = array_diff($raion_c, $region_c);
          $result = array_diff($result, $region_c, $raion_c);
          //$result = $region_c + $raion_c + $result;
          $result = array_merge($region_c,$raion_c,$result);
          }
          } */

        return $result;
    }

    public function contact_search($filters = [], $limit = 20, $offset = 0, $regions = false, $cities = false)
    {
        $bind  = ['limit' => $limit, 'offset' => $offset];
        $order = 0;

        if ($filters) {
            foreach ($filters as $name => $value) {
                if ($name == 'last_name' or $name == 'first_name' or $name == 'description') {
                    if (strlen($value) > 0) {
                        $where[]     = "{$name} ILIKE :{$name}";
                        $bind[$name] = '%' . $value . '%';
                    }
                } else if ($name == 'contact_type') {
                    $where_cont[] = "type = :type";
                    $bind['type'] = $value;
                } else if ($name == 'contact_who') {
                    $where_cont[] = "who = :who";
                    $bind['who']  = $value;
                } else if ($name == 'contact_user_id') {
                    $where_cont[]    = "contacter_id = :user_id";
                    $bind['user_id'] = $value;
                } else if ($name == 'period_begin') {
                    $where_cont[]  = "date>:begin AND date<:end";
                    $bind['begin'] = $value;
                    $filters['period_end'] ? $bind['end'] = $filters['period_end'] : $bind['end'] = time();
                    $order = 1;
                } else if ($name == 'region_id') {
                    $where[]           = "region_id = :region_id";
                    $bind['region_id'] = $value;
                } else if ($name == 'city_id') {
                    $where[]         = "city_id = :city_id";
                    $bind['city_id'] = $value;
                }
            }
        }

        if (is_array($regions) && count($regions) > 0) {
            $where[] = "region_id in (" . implode(',', $regions) . ")";
        } else if (is_array($cities) && count($cities) > 0) {
            $where[] = "city_id in (" . implode(',', $cities) . ")";
        }

        if (!session::has_credential('admin')) {
            $where['suslik'] = "(user_id NOT IN(SELECT id FROM user_auth WHERE suslik = :suslik)
                    OR user_id IN (SELECT user_id FROM " . $this->table_name . " 
                        WHERE share_users && '{" . session::get_user_id() . "}'))";
            $bind['suslik']  = 1;
        }

        if (count($where) > 0) {
            $where_cont[] = 'user_id in (SELECT user_id FROM user_data WHERE ' . implode(' AND ', $where) . ')';
        }
        if (count($where_cont) > 0) {
            $where = 'WHERE ' . implode(' AND ', $where_cont);
        }
        if ($order) {
            $res = db::get_cols(
                'SELECT user_id FROM (SELECT MIN(date),user_id FROM user_contact ' . $where . ' GROUP BY user_id,date ORDER BY date ASC) foo LIMIT :limit OFFSET :offset;',
                $bind
            );

            return array_unique($res);
        } else {
            return db::get_cols(
                'SELECT DISTINCT ON(user_id) user_id FROM user_contact ' . $where . ' LIMIT :limit OFFSET :offset;',
                $bind
            );
        }
    }

    public function map_search()
    {
        #$bind = array('limit' => $limit , 'offset' => $offset);
        $order = 0;
        $user  = $this->get_item(session::get_user_id());
        if (!session::has_credential('admin')) {
            $sqladdd = "AND onmap=1";
        }
        $data = db::get_rows(
            'SELECT user_id,
(6371 * acos(cos(radians(' . $user['locationlat'] . ')) * cos(radians(locationlat)) 
* cos(radians(locationlng) - radians(' . $user['locationlng'] . ')) 
+ sin(radians(' . $user['locationlat'] . ')) * sin(radians(locationlat)))) 
AS distance FROM user_data JOIN user_auth ON user_data.user_id=user_auth.id 
AND user_auth.active=true WHERE locationlng>0 ' . $sqladdd . ' AND user_id!=' . $user['user_id'] . ' 
ORDER BY distance'
        );
        foreach ($data as $d) {
            if ($d['distance'] > request::get_int('distance')) {
                break;
            }
            $list[]                  = $d['user_id'];
            $map_data[$d['user_id']] = $d['distance'];
        }
        $this->map_data = $map_data;
        if (!is_array($list)) {
            $list = [];
        }

        return $list;
    }

    public function smap_search($locationlng, $locationlat, $user_id)
    {
        if (!session::has_credential('admin')) {
            $sqladdd = "AND onmap=1";
        }
        $data = db::get_rows(
            'SELECT user_id,locationlat,locationlng,
(6371 * acos(cos(radians(' . $locationlat . ')) * cos(radians(locationlat)) 
* cos(radians(locationlng) - radians(' . $locationlng . ')) 
+ sin(radians(' . $locationlat . ')) * sin(radians(locationlat)))) 
AS distance FROM user_data JOIN user_auth ON user_data.user_id=user_auth.id 
AND (user_auth.active=true or user_auth.offline > 0) WHERE locationlng>0 ' . $sqladdd . '
ORDER BY distance'
        );
        $i    = 1;
        foreach ($data as $d) {
            if ($d['user_id'] == $user_id) {
                $map_data[$i]['active'] = 1;
            }
            $map_data[$i]['locationlat'] = $d['locationlat'];
            $map_data[$i]['locationlng'] = $d['locationlng'];
            $user_data                   = $this->get_item($d['user_id']);
            $country                     = geo_peer::instance()->get_country($user_data['country_id']);
            if ($user_data['region_id']) {
                $region = geo_peer::instance()->get_region($user_data['region_id']);
            }
            if ($user_data['city_id']) {
                $city = geo_peer::instance()->get_city($user_data['city_id']);
            }
            $map_data[$i]['Info'] = user_helper::photo(
                    $d['user_id'],
                    's',
                    ['class' => 'border1 mb5 mr10', 'align' => 'left']
                ) . user_helper::full_name($d['user_id']) . '<p class="plm p5">' . $country['name_' . translate::get_lang()] . ', ';
            ($user_data['region_id']) ? $map_data[$i]['Info'] .= $region['name_' . translate::get_lang()] . ', ' : '';
            ($user_data['city_id']) ? $map_data[$i]['Info'] .= $city['name_' . translate::get_lang()] : '';
            $map_data[$i]['Info'] .= '</p><p class="plm"><b class="bord">' . user_helper::convert_distance(
                    $d['distance'],
                    true
                ) . '</b></p>';
            $i++;
        }

        return $map_data;
    }

    public function get_distance($locationlat, $locationlng, $to_user_id)
    {
        return db::get_scalar(
            'SELECT
(6371 * acos(cos(radians(' . $locationlat . ')) * cos(radians(LocationLat)) 
* cos(radians(LocationLng) - radians(' . $locationlng . ')) 
+ sin(radians(' . $locationlat . ')) * sin(radians(LocationLat)))) 
FROM user_data WHERE LocationLng>0 AND user_id=' . $to_user_id
        );
    }

    public function get_by_name($keyword, $limit = 10)
    {
        $keyword = mb_strtolower($keyword);

        $sql = 'SELECT ' . $this->primary_key . ' FROM ' . $this->table_name . '
				WHERE lower(first_name) LIKE :keyword OR lower(last_name) LIKE :keyword
				LIMIT :limit';

        return db::get_cols($sql, ['keyword' => $keyword . '%', 'limit' => $limit], $this->connection_name);
    }

    public function get_by_name2($keyword, $wheree)
    {
        $keyword = mb_strtolower($keyword);
        $sql     = 'SELECT ' . $this->primary_key . ' FROM ' . $this->table_name . ' WHERE (lower(last_name) LIKE :keyword OR lower(first_name) LIKE :keyword) AND  ' . $this->primary_key . ' in (SELECT id FROM user_auth WHERE 1=1 ' . $wheree . ')';
        //$sql = 'SELECT ' . $this->primary_key . ' FROM ' . $this->table_name . ' WHERE lower(last_name) LIKE :keyword AND  ' . $this->primary_key . ' in (SELECT id FROM user_auth WHERE active=:active '.$wheree.')';
        //return db::get_cols($sql, array('keyword' => $keyword . '%','active' => 1), $this->connection_name);
        return db::get_cols($sql, ['keyword' => $keyword . '%'], $this->connection_name);
    }

    public function get_by_name_ppo($keyword, $wheree)
    {
        $keyword = mb_strtolower($keyword);
        $sql     = sprintf(
            'SELECT %s FROM %s WHERE lower(last_name) LIKE :keyword %s',
            $this->primary_key,
            $this->table_name,
            $wheree
        );

        return db::get_cols($sql, ['keyword' => $keyword . '%'], $this->connection_name);
    }

    public function get_by_name_team($keyword, $wheree)
    {
        $keyword = mb_strtolower($keyword);
        $sql     = 'SELECT ' . $this->primary_key . ' FROM ' . $this->table_name . ' WHERE lower(last_name) LIKE :keyword ' . $wheree;

        return db::get_cols($sql, ['keyword' => $keyword . '%'], $this->connection_name);
    }

    public function insert($data, $ignore_duplicate = false)
    {
        $id = parent::insert($data, $ignore_duplicate);
        $this->reindex($id);

        return $id;
    }

    public function get_formated_data($data)
    {
        /* Set locale to Ukrainian */
        $months = [
            'ru' => [
                '',
                'января',
                'февраля',
                'марта',
                'апреля',
                'мая',
                'июня',
                'июля',
                'августа',
                'сентября',
                'октября',
                'ноября',
                'декабря',
            ],
            'uk' => [
                '',
                'січня',
                'лютого',
                'березня',
                'квітня',
                'травня',
                'червня',
                'липня',
                'серпня',
                'вересня',
                'жовтня',
                'листопада',
                'грудня',
            ],
        ];

        session::get('language') != 'ru' ? $lang = 'uk' : $lang = 'ru';

        return strftime("%d", strtotime($data)) . ' ' . $months[$lang][date("n", strtotime($data))] . ' ' . strftime(
                "%Y",
                strtotime($data)
            );
    }

    public function search_by_status($key, $statInterval)
    {
        $name  = mb_strtolower($key);
        $where = ($statInterval[1]) ? ' AND status>=:minStat AND status<:maxStat' : 'AND status>=:minStat AND status>=:maxStat';
        $sql   = 'SELECT ' . $this->primary_key . ' FROM ' . $this->table_name . ' WHERE lower(last_name) LIKE :keyword AND  ' . $this->primary_key . ' in (SELECT id FROM user_auth WHERE active=:active AND status>=10 ' . $where . ')';
        $bind  = [
            'keyword' => $name . '%',
            'minStat' => $statInterval[0],
            'maxStat' => $statInterval[1],
            'active'  => 1,
        ];

        $uIds = db::get_cols($sql, $bind);

        /////////// ALREADY ASKED RECOMMENDATION?
        foreach ($uIds as $id => $value) {
            if (db_key::i()->exists(session::get_user_id() . "_asked_" . $value) || $value == session::get_user_id()) {
                unset($uIds[$id]);
            }
        }

        return $uIds;
    }

    public function get_users_count()
    {
        $sql = "SELECT COUNT(" . $this->primary_key . ") FROM " . $this->table_name;

        return db::get_scalar($sql);
    }

}
