<?

class events_peer extends db_peer_postgre
{

	protected $table_name = 'events';

	/**
	 * @return events_peer
	 */
	public static function instance()
	{ 
		return parent::instance( 'events_peer' );
	}

        public function get_by_content_id($content_id,$type=1)
        {
            $result = db::get_cols('SELECT * FROM events WHERE type = '.$type.' AND content_id = '.$content_id.' ORDER BY start DESC');
            $invites = db::get_cols("SELECT obj_id FROM invites WHERE type=1 AND to_id=".(session::get_user_id() ? session::get_user_id() : 0));
            if(!is_array($invites))$invites=array();
            load::model('groups/members');
            foreach($result as $k=>$v)
            {
                $item = db::get_row('SELECT * FROM events WHERE id = '.$v);
                if( $item['hidden'] && !in_array($item['id'], $invites) && $item['user_id']!=session::get_user_id() &&  !session::has_credential('admin') && !groups_members_peer::instance()->is_member($item['content_id'],session::get_user_id()) )
                {
                     unset($result[$k]);
                }
            }
            return $result;
        }

        public function get_list($page, $limit = 50, $arhive=false, $section=0, $type=0, $cat=0, $user_id=0, $future=true, $region=0, $level=0, $check_hidden=1 )
        {
            if($arhive==true)$sqladd="AND start<=".time();
            if($future==true && $arhive==false)$sqladd.="AND start>=".time();
            if($section>0)$sqladd.=" AND section=".$section;
            if($cat>0)$sqladd.=" AND cat=".$cat;
            if($type>0)$sqladd.=" AND type=".$type;
            if($region>0)$sqladd.=" AND region_id=".$region;
            if($level>0)$sqladd.=" AND level=".$level;
            if($user_id>0)$sqladd.=" AND id IN(SELECT event_id FROM events2users WHERE event_id=events.id AND user_id=$user_id)";
            $sql=' FROM events WHERE id>0 '.$sqladd.' ORDER BY start DESC';
            $offset=($page*$limit)-$limit;
            if($offset<0)$offset=0;
            $data['list'] = db::get_rows('SELECT *,(SELECT count(*)
                FROM events2users WHERE event_id=events.id AND status IN(1,3) AND user_id not IN(SELECT id FROM user_auth WHERE del>0)) as users_count,
                (SELECT SUM(leads) FROM events2users WHERE event_id=events.id AND status IN(1,3) AND user_id not IN(SELECT id FROM user_auth WHERE del>0))
                as users_leads_count,
                (SELECT status FROM events2users WHERE event_id=events.id AND user_id='.session::get_user_id().' LIMIT 1)
                as status'.$sql.' LIMIT '.$limit.' OFFSET '.$offset);
            $data['count'] = db::get_cols('SELECT * '.$sql);
            if($check_hidden>0)
            {
                $invites = db::get_cols("SELECT obj_id FROM invites WHERE type=1 AND to_id=".session::get_user_id());
                if(!is_array($invites))$invites=array();
                load::model('groups/members');
                if(is_array($data['list']))
                foreach($data['list'] as $k=>$v)
                {
                    if( $data['list'][$k]['hidden'] && !in_array($v['id'], $invites) && $v['user_id']!=session::get_user_id() &&  !session::has_credential('admin') && !($v['type']==1 && groups_members_peer::instance()->is_member($v['content_id'],session::get_user_id())) )
                    {
                         unset($data['list'][$k],$data['count'][$k]);
                    }
                }
            }
            return $data;
        }
        
        public function get_newest($limit=5)
        {   
            if (session::get_user_id()) $add_sql='or id in (SELECT obj_id FROM invites WHERE type=1 AND to_id='.session::get_user_id().')';
            $sql='SELECT id  FROM events WHERE "end">'.time().'AND (hidden=0 '.$add_sql.') ORDER BY id DESC LIMIT '.$limit;
            return db::get_cols($sql);
        }
        
        public function  get_item($id)
        {
            //7 раз вставить  AND user_id not IN(SELECT id FROM user_auth WHERE del>0 ?? =/
            
            if (session::get_user_id()) $add_sql=', (SELECT status FROM events2users WHERE event_id=events.id AND user_id='.session::get_user_id().' LIMIT 1) as status';
        return db::get_row('SELECT *,(SELECT count(*)
            FROM events2users WHERE event_id=events.id AND status = 1) as users1count,
            (SELECT count(*)
            FROM events2users WHERE event_id=events.id AND status = 2) as users2count,
            (SELECT count(*)
            FROM events2users WHERE event_id=events.id AND status = 3) as users3count,
            (SELECT SUM(leads) FROM events2users WHERE event_id=events.id AND status = 1) as users1sum,
            (SELECT SUM(leads) FROM events2users WHERE event_id=events.id AND status = 2) as users2sum,
            (SELECT SUM(leads) FROM events2users WHERE event_id=events.id AND status = 3) as users3sum'.
            $add_sql
            .' FROM events WHERE id='.$id);
        }

        public function get_small_item($id)
        {
            return parent::get_item($id);
        }

        public function search( $filters = array(), $limit = 20, $offset = 0 )
	{
		$where = array('1=1');
                $bind=array();
                if ( $filters ) foreach ( $filters as $name => $value )
		{
                    if($name=='name')
			{
                                if(mb_strlen($value)>0) {
                                    $where[] = "{$name} ILIKE :{$name}";
                                    $bind[$name] = '%'.$value.'%';
                                }
			}elseif($name=='status')
			{
                            $where[] = "id IN(SELECT event_id FROM events2users WHERE {$name} = :{$name} AND user_id=".session::get_user_id().")";
                            $bind[$name] = $value;
                        }elseif($name=='start')
                        {
                            $unix=strtotime($value);
                            $start=mktime(0,0,0,date('d',$unix),date('m',$unix),date('Y',$unix));
                            $end=mktime(23,59,59,date('d',$unix),date('m',$unix),date('Y',$unix));
                            $where[] = "({$name}>=:start AND {$name}<=:end)";
                            $bind['start'] = $start;
                            $bind['end'] = $end;
                        }elseif($name=='end')
                        {
                            $unix=strtotime($value);
                            $start=mktime(0,0,0,date('d',$unix),date('m',$unix),date('Y',$unix));
                            $end=mktime(23,59,59,date('d',$unix),date('m',$unix),date('Y',$unix));
                            $where[] = "(\"{$name}\">=:start2 AND \"{$name}\"<=:end2)";
                            $bind['start2'] = $start;
                            $bind['end2'] = $end;
                        }else
			{
				$where[] = "{$name} = :{$name}";
				$bind[$name] = $value;
			}
                }
            
		$add='SELECT *,(SELECT count(*)
            FROM events2users WHERE event_id=events.id AND status IN(1,3)) as users_count,
            (SELECT SUM(leads) FROM events2users WHERE event_id=events.id AND status IN(1,3) AND user_id not IN(SELECT id FROM user_auth WHERE del>0))
            as users_leads_count,
            (SELECT status FROM events2users WHERE event_id=events.id AND user_id='.session::get_user_id().')
            as status';

                $sql =  ' FROM ' . $this->table_name .'
				WHERE ' . implode(' AND ', $where); 
                $data['count'] = db::get_cols('SELECT * '.$sql, $bind, $this->connection_name);
                $bind=array_merge($bind,array('limit' => $limit , 'offset' => $offset)); 
		$data['list'] =  db::get_rows($add.$sql.' LIMIT :limit OFFSET :offset;', $bind, $this->connection_name);
                return $data;
        }

        public static function get_types()
	{
		/*return array(
            1 => t('Информационная'),
            2 => t('Организационная'),
            3 => t('Учебная'),
            4 => t('Общественная'),
            5 => t('Благотворительная'),
            6 => t('Культурная'),
            7 => t('Спортивная'),
            8 => t('Другая')
                );*/

                return array(
						'9' => t('Агитационная'),
            '1' => t('Информационная'),
            '2' => t('Организационная'),
            '3' => t('Учебная'),
            '8' => t('Другая')
                );
        }

        public static function get_cats()
	{
		return array(
            1 => t('Мероприятие МПУ'),
            2 => t('Мероприятие другой организации')
                );
        }

        public static function get_confirm()
	{
		return array(
            false => t('Необязательное'),
            true => t('Обязательное')
                );
        }

        public static function get_price_types()
        {
		return array(
            false => t('Бесплатное'),
            true => t('Платное')
                );
        }

        public static function get_levels()
        {
		return array(
            1 => t('Национальная'),
            2 => t('Местная')
                );
        }

        public function user_confirm($event_id,$user_id)
        {
            return db::get_scalar('SELECT confirm FROM events2users WHERE event_id = '.$event_id.' AND user_id = '.$user_id);
        }
				
				public static function get_formats(){
					return array(
							"campaign" => t('Агитационная палатка'),
							"propaganda" => t('Агитационный пробег'),
							"other" => t('Другое')
					);
					
				}
}
