<?php

class ppo_members_peer extends db_peer_postgre
{
    const FUNCTION_COMMISSION_MEMBER = 3;
    const FUNCTION_COUNCIL_MEMBER    = 4;
    const ACTING_HEAD                = 5;
    const ACTING_MANAGER             = 6;
    const FUNCTIONS                  = [
        1                                => 'Голова',
        2                                => 'Відповідальний секретар',
        self::FUNCTION_COMMISSION_MEMBER => 'Член КРК',
        self::FUNCTION_COUNCIL_MEMBER    => 'Член Совета',
        self::ACTING_HEAD                => 'и.о.Главы',
        self::ACTING_MANAGER             => 'и.о.Руководителя Секретариата',
    ];

    protected $table_name = 'ppo_members';
    protected $primary_key = null;

    /**
     * @return ppo_members_peer
     */
    public static function instance()
    {
        return parent::instance('ppo_members_peer');
    }

    public function add($group_id, $user_id, $type = false)
    {
        $insert = ['group_id' => $group_id, 'user_id' => $user_id];

        if (!$type) {
            if (db::get_scalar(
                'SELECT id FROM invites WHERE type = 2 AND obj_id = '.$group_id.' AND to_id = '.$user_id
            )) {
                $insert['type'] = 1;
            }
        } else {
            $insert['type'] = $type;
        }

        $this->insert($insert, true);
    }

    public function get_type($group_id, $user_id)
    {
        $index = db::get_scalar(
            'SELECT type FROM '.$this->table_name.' WHERE group_id = '.$group_id.' AND user_id = '.$user_id
        );
        $array = [
            t('Присоединился'),
            t('Приглашен'),
            t('Присоединен'),
        ];

        return $array[$index];
    }

    public function remove($group_id, $user_id)
    {
        $sql = 'DELETE FROM '.$this->table_name.' WHERE group_id = :group_id AND user_id = :user_id';

        return db::exec($sql, ['group_id' => $group_id, 'user_id' => $user_id], $this->connection_name);
    }

    public function is_member($group_id, $user_id)
    {
        return in_array($user_id, $this->get_members($group_id));
    }

    public function get_members($group_id = 0, $function = false, $ppo = [], $del = true)
    {
        $delsql = '';

        $bind = ['group_id' => $group_id];
        if (false !== $function) {
            $sqladd           = ' AND "function" = :function';
            $bind['function'] = $function;
        }
        if ($del) {
            $delsql = ' AND user_id not IN(SELECT id FROM user_auth WHERE del>0)';
        }

        if (2 === $ppo['category']) {
            $sqladd = 'OR group_id IN(SELECT id FROM ppo WHERE city_id='.(int) $ppo['city_id'].' AND category=1)';
        } elseif (3 === $ppo['category']) {
            $sqladd = 'OR group_id IN(SELECT id FROM ppo WHERE region_id='.(int) $ppo['region_id'].' AND category=1)';
        }

        $sql = 'SELECT user_id FROM '.$this->table_name.' WHERE (group_id = :group_id '.$sqladd.') '
            .$delsql.
            'GROUP BY user_id';

        return db::get_cols($sql, $bind, $this->connection_name);
    }

    public function get_ppo($user_id, $order = false)
    {
        $bind           = ['user_id' => $user_id];
        $where          = ' AND group_id in (SELECT id FROM ppo WHERE active=:active';
        $bind['active'] = 1;
        if (!session::has_credential('admin') and session::get_user_id() != $user_id) {
            $where          .= ' AND hidden=:hidden';
            $bind['hidden'] = 0;
        }
        $sql         = 'SELECT group_id FROM '.$this->table_name.' WHERE user_id = :user_id'.$where.')';
        $return_data = db::get_cols($sql, $bind, $this->connection_name);
        if ($return_data && 'count_users' == $order) {
            $return_data = db::get_cols(
                'SELECT group_id FROM ppo_members WHERE group_id in ('.implode(
                    ',',
                    $return_data
                ).') GROUP BY group_id ORDER BY count(group_id) DESC'
            );
        }

        return $return_data;
    }

    public function get_user_by_function($function, $group_id, $ppo = [])
    {
        return db::get_scalar(
            'SELECT user_id FROM '.$this->table_name.' 
                WHERE function=:function AND group_id=:group_id',
            ['function' => $function, 'group_id' => $group_id]
        );
    }

    public function allow_edit($user_id, $ppo)
    {
        switch ($ppo['category']) {
            case 2:
                $sqladd = 'AND region_id='.$ppo['region_id'].' AND category=3';
                break;
            case 1:
                $sqladd = 'AND ((region_id='.$ppo['region_id'].' AND category=3)
                    OR (region_id='.$ppo['region_id'].' AND city_id='.$ppo['city_id'].' AND category=2))';
                break;
            default:
        }
        if ($ppo['category'] < 3) {
            $sql = 'OR
                group_id IN(SELECT id FROM ppo WHERE active=1 '.$sqladd.')';
        }
        $is = db::get_scalar(
            'SELECT count(*) FROM '.$this->table_name.' 
                WHERE (user_id=:user_id AND function IN(1,2,4)) AND ((group_id=:group_id) '.$sql.')',
            ['user_id' => $user_id, 'group_id' => $ppo['id']]
        );

        return $is;
    }

    public function is_ppoleader($user_id, $ppo_id = false)
    {
        if (!$ppo_id) {
            return db::get_scalar(
                'SELECT group_id FROM '.$this->table_name.'
                WHERE (function=1 OR function=2) AND user_id=:user_id',
                ['user_id' => $user_id]
            );
        } else {
            return db::get_scalar(
                'SELECT group_id FROM '.$this->table_name.'
                WHERE (function=1 OR function=2) AND user_id=:user_id AND group_id=:ppo_id',
                ['user_id' => $user_id, 'ppo_id' => $ppo_id]
            );
        }
    }

    public function is_leader($user_id, $ppo_id = false)
    {
        if (!$ppo_id) {
            return db::get_scalar(
                'SELECT group_id FROM ppo_members pm,ppo p
                WHERE pm.function>0 AND pm.user_id=:user_id AND pm.group_id=p.id AND p.active=1 ORDER BY p.category DESC',
                ['user_id' => $user_id]
            );
        }

        return db::get_scalar(
            'SELECT group_id FROM '.$this->table_name.'
            WHERE function>0 AND user_id=:user_id AND group_id=:ppo_id',
            ['user_id' => $user_id, 'ppo_id' => $ppo_id]
        );
    }

    public function get_users_by_function($function, $group_id)
    {
        return db::get_cols(
            'SELECT user_id FROM '.$this->table_name.' 
                WHERE function=:function AND group_id=:group_id',
            ['function' => $function, 'group_id' => $group_id]
        );
    }

    public function set_function($group_id, $user_id, $function_id)
    {
        $sql = 'SELECT count(*) FROM '.$this->table_name.' WHERE group_id = :group_id AND user_id = :user_id';

        if (0 === db::get_scalar($sql, ['group_id' => $group_id, 'user_id' => $user_id])) {
            $this->insert(
                [
                    'group_id' => $group_id,
                    'user_id'  => $user_id,
                    'function' => $function_id,
                ]
            );
        } else {
            $sql = "update {$this->table_name} set \"function\" = :function where group_id = :group_id and user_id = :user_id";
            db::exec($sql, ['group_id' => $group_id, 'user_id' => $user_id, 'function' => $function_id]);
        }
    }

    public function upsertMember($ppo, $user, $function)
    {
        $sql     = "select count(*) from {$this->table_name} where group_id = :group_id and user_id = :user_id";
        $context = ['group_id' => $ppo, 'user_id' => $user];

        if (0 === db::get_scalar($sql, $context)) {
            $sql = "insert into {$this->table_name} (group_id, user_id, \"function\") values (:group_id, :user_id, :function)";
        } else {
            $sql = "update {$this->table_name} set \"function\" = :function where group_id = :group_id and user_id = :user_id";
        }

        $context['function'] = $function;

        db::exec($sql, $context);
    }

    public function ppo_info($user_id)
    {
        if (!$user_id) {
            $user_id = 0;
        }
        $query = db::get_row(
            'SELECT p.region_id, p.city_id, p.category FROM ppo_members m, ppo p
                WHERE (m.function = 1 OR m.function = 2) AND m.user_id = '.$user_id.' AND p.id = m.group_id AND p.category > 0'
        );
        if (3 == $query['category']) {
            return [0 => $query['region_id']];
        } else {
            if (1 == $query['category'] || 2 == $query['category']) {
                return [1 => $query['city_id']];
            }
        }
    }

    public function ppo_by_leader($user_id)
    {
        return db::get_cols(
            'SELECT p.id FROM ppo_members m, ppo p
                WHERE (m.function = 1 OR m.function = 2) AND m.user_id = '.$user_id.' AND p.id = m.group_id AND p.category > 0'
        );
    }
}
