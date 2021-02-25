<?php

load::model('ppo/members');
load::model('ppo/ppo');
load::action_helper('pager');

class admin_rating_action extends frontend_controller
{
    protected static $sort_field = 'full_rating';
    protected static $sort_direction = 1;

    public static function compare($a, $b)
    {
        if ($a[self::$sort_field] === $b[self::$sort_field]) {
            return $a[self::$sort_field] > $b[self::$sort_field] ? self::$sort_direction * -1 : 0;
        }

        return $a[self::$sort_field] > $b[self::$sort_field] ? self::$sort_direction * -1 : self::$sort_direction;
    }

    public function execute()
    {
        $type        = request::get('type', 'criteria_adv');
        $this->field = request::get('field', 'invites');
        $direct      = request::get('direct', 'DESC');
        $this->status      = request::get_int('status', null);

        $costs         = rating_helper::get_costs();
        $this->members = db::get_scalar('SELECT COUNT(id) FROM user_rating');

        if ($costs) {
            foreach ($costs as $k => $v) {
                $this->alias2names[$v['alias']] = $v['name_'.getenv('LANGUAGE')];
            }
        }

        $this->alias2names = array_merge(
            $this->alias2names,
            [
                'full_rating'    => t('Общий рейтинг'),
                'regional_ratio' => t('Региональный коефициент'),
                'added_points'   => t('Дополнительные баллы'),
            ]
        );

        switch ($type) {
            case 'criteria':
                $types      = rating_helper::get_costs();
                $this->summ = 0;
                if ($types) {
                    foreach ($types as $k => $v) {
                        $all_count                   = db::get_scalar('SELECT SUM('.$v['alias'].') FROM user_rating');
                        $this->by_types[$v['alias']] = floatval($all_count) * floatval($v['cost']);
                        $this->summary               += $this->by_types[$v['alias']];
                    }
                }
                switch ($direct) {
                    case 'ASC':
                        asort($this->by_types);
                        break;
                    case 'DESC':
                        arsort($this->by_types);
                        break;
                    default :
                        break;
                }
                $this->title = t('Общий').' рейтинг';
                break;

            case 'people':
                //$list = db::get_cols("SELECT id FROM user_rating ORDER BY rating ".$direct);

                $this->by_users       = rating_helper::calculate_by_all_users();
                self::$sort_direction = 'ASC' === $direct ? -1 : 1;


                uasort($this->by_users, 'admin_rating_action::compare');
                $list        = array_keys($this->by_users);
                $this->pager = pager_helper::get_pager($list, request::get_int('page'), 50);
                $this->list  = $this->pager->get_list();
                $this->title = t('Члены партии');

                break;

            case 'criteria_adv':
                if ('invites' === $this->field) {
                    $extraSql   = '';
                    $parameters = [];

                    if (null !== $this->status) {
                        $extraSql             .= ' and ui.status = :status';
                        $parameters['status'] = $this->status;
                    }

                    $sql = <<<SQL
select ua.id, count(ui.id) as invited, ud.first_name, ud.last_name
from user_auth as ua
         right join user_data as ud on ud.user_id = ua.id
         inner join user_auth as ui on ui.invited_by = ua.id
where ui.status not in (-1, 1, 3){$extraSql}
group by ua.id, ud.first_name, ud.last_name
order by invited desc;
SQL;


                    $list = db::get_rows($sql, $parameters);

                    $this->pager = pager_helper::get_pager($list, request::get_int('page'), 50);
                    $this->list  = $this->pager->get_list();
                } else {
                    $this->by_users = rating_helper::calculate_by_all_users();
                    $exFields       = db::get_cols('SELECT alias FROM user_rating_cost');

                    $costs = rating_helper::get_costs();
                    if ($costs) {
                        foreach ($costs as $k => $v) {
                            if ($this->field == $v['alias']) {
                                $this->title = $v['name_'.getenv('LANGUAGE')];
                            }
                        }
                    }

                    self::$sort_direction = 'ASC' === $direct ? -1 : 1;

                    if (in_array(
                        $this->field,
                        array_merge($exFields, ['full_rating', 'added_points', 'regional_ratio']),
                        true
                    )) {
                        self::$sort_field = $this->field;
                    } else {
                        $this->field = 'full_rating';
                    }

                    uasort($this->by_users, 'admin_rating_action::compare');

                    $list        = array_keys($this->by_users);
                    $this->pager = pager_helper::get_pager($list, request::get_int('page'), 50);
                    $this->list  = $this->pager->get_list();
                }

                break;
        }

        $this->type = $type;
    }
}

