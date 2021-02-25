<?php

class user_sessions_peer extends db_peer_postgre
{
    protected $table_name = 'user_sessions';
    protected $primary_key = 'user_id';
    protected $session_time = 600;

    /**
     * @param string $peer
     * @return db_peer|user_sessions_peer
     */
    public static function instance($peer = 'user_sessions_peer')
    {
        return parent::instance($peer);
    }

    public function last_visit($user_id)
    {
        $sql = 'SELECT visit_ts FROM '.$this->table_name.' WHERE '.$this->primary_key.' = :user_id LIMIT 1';
        $bind = array($this->primary_key => $user_id);
        if (!$result = db::get_row($sql, $bind, $this->connection_name)) {
            return null;
        }
        $months = array(
            'ru' => array(
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
            ),
            'uk' => array(
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
            ),
        );
        $hours = array();

        $visit_ts_array = getdate($result['visit_ts']);
        $date_time_array = getdate(time());

        if ($visit_ts_array['year'] == $date_time_array['year']) {
            if ($result['visit_ts'] + $this->session_time >= time()) {
                return '<img style="margin-top: 2px;" alt="online" src="/static/images/common/user_online.png">';
            } elseif ($visit_ts_array['yday'] == $date_time_array['yday']) {
                $hours_left = $date_time_array['hours'] - $visit_ts_array['hours'];
                $minutes_left = $date_time_array['minutes'] - $visit_ts_array['minutes'];

                if ($minutes_left < 0) {
                    $minutes_left += 60;
                    $hours_left -= 1;
                }

                /*
                 * склонения
                  if ($minutes_left>4 and $minutes_left<21) $minutes_text=t('минут');
                  elseif (substr($minutes_left,-1)==1) $minutes_text=t('минуту');
                  elseif (in_array(substr($minutes_left,-1),array(2,3,4))) $minutes_text=t('минуты');
                  else $minutes_text=t('минут');

                  if (($hours_left>2 && $hours_left<5) or ($hours_left==22 or $hours_left==23)) $hours_text=t('часа');
                  elseif ($hours_left==1 or $hours_left==21) $hours_text=t('час');
                  else $hours_text=t('часов');
                  */

                $minutes_left > 0 ? $minutes_left .= " ".t('мин.') : $minutes_left = '';//.$minutes_text;
                $hours_left > 0 ? $hours_left .= " ".t('ч.') : $hours_left = '';//.$hours_text;

                return /*t('Последнее посещение') . ': ' . */ t(
                        'сегодня'
                    ).' <b>'.$hours_left.' '.$minutes_left.'</b>'.t(' назад');
            } elseif ($date_time_array['yday'] - $visit_ts_array['yday'] < 2) {
                return t('вчера').' <b>'.$visit_ts_array['hours'].':'.$visit_ts_array['minutes'].'</b>';
            }
        }
        session::get('language') != 'ru' ? $lang = 'uk' : $lang = 'ru';

        return strftime("%d", $result['visit_ts']).' '.$months[$lang][date("n", $result['visit_ts'])].' '.strftime(
                "%Y",
                $result['visit_ts']
            );
    }

    public function is_online($user_id)
    {
        return in_array($user_id, $this->who_online());
        /* $sql = 'SELECT * FROM ' . $this->table_name . ' WHERE ' . $this->primary_key . ' = :user_id AND visit_ts>=:visit_ts LIMIT 1';
 $bind=array(
             $this->primary_key => $user_id,
             'visit_ts'=>time()-$this->session_time
              );
 return db::get_cols($sql, $bind);
         */
    }

    public function who_online()
    {
        $sql = 'SELECT * FROM '.$this->table_name.' WHERE visit_ts>=:visit_ts';
        $bind = array('visit_ts' => time() - $this->session_time);

        return db::get_cols($sql, $bind);
    }

    public function set_offline($user_id)
    {
        db::exec(
            'UPDATE '.$this->table_name.' 
               SET visit_ts=visit_ts-601 
               WHERE '.$this->primary_key.' = :user_id',
            array($this->primary_key => $user_id)
        );
    }

    public function friends_online($user_id)
    {
        $sql = 'SELECT * FROM '.$this->table_name.' WHERE '.$this->primary_key.'
                        in (SELECT friend_id FROM '.friends_peer::instance()->get_table_name(
            ).' WHERE '.$this->primary_key.' = :user_id)
                        AND visit_ts>=:visit_ts';
        $bind = array($this->primary_key => $user_id, 'visit_ts' => time() - $this->session_time);

        return db::get_cols($sql, $bind);
    }


    public function update($user_id)
    {
        if (session::get('admin_id') > 0 && $user_id != session::get('admin_id')) {
            return false;
        }
        //db::exec('INSERT INTO "user_access" ("user_id", "ts", "ip", "ua", "referer", "module", "action", "id") '.                    "VALUES('".session::get_user_id()."', '".time()."', '".addslashes($_SERVER['REMOTE_ADDR'])."', '".addslashes($_SERVER['HTTP_USER_AGENT'])."', '".addslashes($_SERVER['HTTP_REFERER'])."', '".addslashes(request::get_string('module'))."', '".addslashes(request::get_string('action'))."', '".request::get_int('id',0)."')");
        $session_data = self::get_item($user_id);


        if (!db_key::i()->get('all_visits_'.$user_id)) {
            db_key::i()->set('all_visits_'.$user_id, 0);
        }
        if (!db_key::i()->get('start_visits_'.$user_id)) {
            db_key::i()->set('start_visits_'.$user_id, time());
        }

        if (!$session_data['first_visit']) {
            $data['first_visit'] = db_key::i()->get('start_visits_'.$user_id);
        }

        if ($session_data['visit_ts'] < (time() - $this->session_time)) {
            $data['start'] = time();
            $last_time = $session_data['visit_ts'] - $session_data['start'];

            //по старому в редисе
            $all_time = $last_time + db_key::i()->get('all_time_'.$user_id);
            db_key::i()->set('all_time_'.$user_id, $all_time);
            $all_visits = @intval(db_key::i()->get('all_visits_'.$user_id)) + 1;
            db_key::i()->set('all_visits_'.$user_id, $all_visits);


            $data['all_time'] = $all_time;
            $data['all_visits'] = $all_visits;
        }
        $keys = array('user_id' => $user_id);
        $data['visit_ts'] = time();
        $session_data ? parent::update($data, $keys) : parent::insert(
            array_merge($data, $keys)
        ); // parent::insert(array_merge($data, $keys));
    }
}