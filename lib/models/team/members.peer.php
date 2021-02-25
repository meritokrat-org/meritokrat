<?

class team_members_peer extends db_peer_postgre
{
    protected $table_name = 'team_members';
    protected $primary_key = null;

    /**
     * @return team_members_peer
     */
    public static function instance()
    {
        return parent::instance('team_members_peer');
    }

    public function add($group_id, $user_id, $type = false)
    {
        $insert = array('group_id' => $group_id, 'user_id' => $user_id);
        if (!$type) {
            if (db::get_scalar('SELECT id FROM invites WHERE type = 2 AND obj_id = ' . $group_id . ' AND to_id = ' . $user_id)) {
                $insert['type'] = 1;
            }
        } else {
            $insert['type'] = $type;
        }
        $this->insert($insert);
    }

    public function get_type($group_id, $user_id)
    {
        $index = db::get_scalar('SELECT type FROM ' . $this->table_name . ' WHERE group_id = ' . $group_id . ' AND user_id = ' . $user_id);
        $array = array(
            t('Присоединился'),
            t('Приглашен'),
            t('Присоединен')
        );
        return $array[$index];
    }

    public function remove($group_id, $user_id)
    {
        $sql = 'DELETE FROM ' . $this->table_name . ' WHERE group_id = :group_id AND user_id = :user_id';
        return db::exec($sql, array('group_id' => $group_id, 'user_id' => $user_id), $this->connection_name);
    }

    public function get_members($group_id = 0, $function = false, $team = array(), $del = true)
    {
        $bind = array('group_id' => $group_id);
        if ($function !== false) {
            $sqladd = "AND function=:function";
            $bind['function'] = $function;
        }
        if ($del) $delsql = ' AND user_id not IN(SELECT id FROM user_auth WHERE del>0)';
        else $delsql = '';

        if ($team['category'] == 3)
            $sqladd = "OR group_id IN(SELECT id FROM team WHERE city_id=" . (int)$team['city_id'] . " AND category=1)";
        elseif ($team['category'] == 4)
            $sqladd = "OR group_id IN(SELECT id FROM team WHERE region_id=" . (int)$team['region_id'] . " AND category=1)";
        elseif ($team['category'] == 5)
            $sqladd = "OR group_id IN(SELECT id FROM team WHERE category IN (1, 2, 3))";

        $sql = 'SELECT user_id FROM ' . $this->table_name . '
                    WHERE (group_id = :group_id ' . $sqladd . ') '
            . $delsql .
            'GROUP BY user_id';
        return db::get_cols($sql, $bind, $this->connection_name);
    }

    public function get_children_teams_members($team_id)
    {
        $sql = 'SELECT user_id FROM ' . $this->table_name . ' WHERE group_id = :team_id';
        $bind = ['team_id' => $team_id];

        return db::get_cols($sql, $bind, $this->connection_name);
    }

    public function is_member($group_id, $user_id)
    {
        return in_array($user_id, $this->get_members($group_id));
    }

    public function get_team($user_id, $order = false)
    {
        $bind = array('user_id' => $user_id);
        $where = " AND group_id in (SELECT id FROM team WHERE active=:active";
        $bind['active'] = 1;
        if (!session::has_credential('admin') and session::get_user_id() != $user_id) {
            $where .= " AND hidden=:hidden";
            $bind['hidden'] = 0;
        }
        $sql = 'SELECT group_id FROM ' . $this->table_name . ' WHERE user_id = :user_id' . $where . ')';
        $return_data = db::get_cols($sql, $bind, $this->connection_name);
        if ($return_data && $order == 'count_users') {
            $return_data = db::get_cols("SELECT group_id FROM team_members WHERE group_id in (" . implode(',', $return_data) . ") GROUP BY group_id ORDER BY count(group_id) DESC");
        }
        return $return_data;
    }

    public function get_user_by_function($function, $group_id, $team = array())
    {
        return db::get_scalar('SELECT user_id FROM ' . $this->table_name . '
                WHERE function=:function AND group_id=:group_id', array('function' => $function, 'group_id' => $group_id));
    }

    public function allow_edit($user_id, $team)
    {
        switch ($team['category']) {
            case 2:
                $sqladd = "AND region_id=" . $team['region_id'] . " AND category=3";
                break;
            case 1:
                $sqladd = "AND ((region_id=" . $team['region_id'] . " AND category=3)
                    OR (region_id=" . $team['region_id'] . " AND city_id=" . $team['city_id'] . " AND category=2))";
                break;
            default:
        }
        if ($team['category'] < 3) $sql = 'OR
                group_id IN(SELECT id FROM team WHERE active=1 ' . $sqladd . ')';
        $is = db::get_scalar('SELECT count(*) FROM ' . $this->table_name . '
                WHERE (user_id=:user_id AND function IN(1,2,4)) AND ((group_id=:group_id) ' . $sql . ')',
            array('user_id' => $user_id, 'group_id' => $team['id']));
        return $is;
    }

    public function is_teamleader($user_id, $team_id = false)
    {
        if (!$team_id)
            return db::get_scalar('SELECT group_id FROM ' . $this->table_name . '
                WHERE (function=1 OR function=2) AND user_id=:user_id', array('user_id' => $user_id));
        else
            return db::get_scalar('SELECT group_id FROM ' . $this->table_name . '
                WHERE (function=1 OR function=2) AND user_id=:user_id AND group_id=:team_id', array('user_id' => $user_id, 'team_id' => $team_id));
    }

    public function is_leader($user_id, $team_id = false)
    {
        if (!$team_id)
            return db::get_scalar('SELECT group_id FROM team_members pm,team p
                WHERE pm.function>0 AND pm.user_id=:user_id AND pm.group_id=p.id AND p.active=1 ORDER BY p.category DESC', array('user_id' => $user_id));
        else
            return db::get_scalar('SELECT group_id FROM ' . $this->table_name . '
                WHERE function>0 AND user_id=:user_id AND group_id=:team_id', array('user_id' => $user_id, 'team_id' => $team_id));
    }

    public function get_users_by_function($function, $group_id)
    {
        return db::get_cols('SELECT user_id FROM ' . $this->table_name . '
                WHERE function=:function AND group_id=:group_id', array('function' => $function, 'group_id' => $group_id));
    }

    public function set_function($group_id, $user_id, $function_id)
    {
        if (db::get_scalar('SELECT count(*) FROM ' . $this->table_name . '
                WHERE group_id=:group_id AND user_id=:user_id', array('group_id' => $group_id, 'user_id' => $user_id)) == 0
        )
            $this->insert(array('group_id' => $group_id, 'user_id' => $user_id, 'function' => $function_id));
        else
            db::exec("UPDATE " . $this->table_name . " SET group_id=:group_id, user_id=:user_id, function=:function
                                WHERE group_id=:group_id AND user_id=:user_id", array(
                'group_id' => $group_id,
                'user_id' => $user_id,
                'function' => $function_id));
        /*
                //берем инфу о ппо
                $team_info=$this->get_item($group_id);

                //добавляем соответсвующюю функцию
                $id_function='1'.$function_id.$team_info['category'];//хитросплетенно
                $insert_data=array(
                    'user_id' => $user_id,
                    'region_id' => $team_info['region_id'],
                    'city_id' => $team_info['city_id'],
                    'function_id' => user_auth_peer::instance()->get_function($id_function)
                );

                load::model('user/user_desktop_funct');
                user_desktop_funct_peer::instance()->insert($data);


                load::model('user/user_desktop');
                if ($user_data=user_desktop_peer::instance()->get_item($user_id))
                {
                    $user_data['functions']=  str_replace('}', ','.$id_function.'}', $user_data['functions']);
                    user_desktop_peer::instance()->update($user_data);
                }
                else
                {
                    user_desktop_peer::instance()->insert(array(
                        'user_id' => $user_id,
                        'functions' => '{'.$id_function.'}'
                    ));
                }
         */

    }

    public function team_info($user_id)
    {

        if (!$user_id) $user_id = 0;
        $query = db::get_row('SELECT p.region_id, p.city_id, p.category FROM team_members m, team p
                WHERE (m.function = 1 OR m.function = 2) AND m.user_id = ' . $user_id . ' AND p.id = m.group_id AND p.category > 0');
        if ($query['category'] == 3) {
            return array(0 => $query['region_id']);
        } elseif ($query['category'] == 1 || $query['category'] == 2) {
            return array(1 => $query['city_id']);
        }
    }

    public function team_by_leader($user_id)
    {
        return db::get_cols('SELECT p.id FROM team_members m, team p
                WHERE (m.function = 1 OR m.function = 2) AND m.user_id = ' . $user_id . ' AND p.id = m.group_id AND p.category > 0');
    }
}
