<?

class eventreport_peer extends db_peer_postgre
{

	protected $table_name = 'eventreport';

	/**
	 * @return events_peer
	 */
	public static function instance()
	{ 
		return parent::instance( 'eventreport_peer' );
	}

        public function get_new_events($section=9)
        {
            return db::get_cols('SELECT id FROM events WHERE section = '.intval($section).' AND "end" < '.time().' AND id NOT IN (SELECT event_id FROM eventreport)');
        }

        public function get_by_ppo($po_id=0,$status=3)
        {
            return db::get_cols('SELECT id FROM eventreport WHERE po_id = '.intval($po_id).' AND status = '.intval($status).'  AND del=0 ORDER BY start');
        }

    public function get_by_team($team_id=0,$status=3)
    {
        return db::get_cols('SELECT id FROM eventreport WHERE team_id = '.intval($team_id).' AND status = '.intval($status).'  AND del=0 ORDER BY start');
    }

        public function get_all_by_ppo($po_id=0)
        {
            return db::get_cols('SELECT id FROM eventreport WHERE po_id = '.intval($po_id).' AND del=0 ORDER BY start');
        }

    public function get_all_by_team($team_id=0)
    {
        return db::get_cols('SELECT id FROM eventreport WHERE team_id = '.intval($team_id).' AND del=0 ORDER BY start');
    }

        public function get_reports($ppo=array(),$status=3)
        {
            if($status==3 || session::has_credential('admin'))
            {
                return db::get_cols('SELECT id FROM eventreport WHERE status = '.intval($status).' AND del=0 ORDER BY id DESC');
            }
            elseif(is_array($ppo) && count($ppo)>0 && !session::has_credential('admin'))
            {
                return db::get_cols('SELECT id FROM eventreport WHERE status = '.intval($status).' AND del=0 AND po_id IN ('.implode(',',$ppo).') ORDER BY id DESC');
            }
            else
            {
                return array();
            }
        }

        public function search()
        {
            $where = '';
            $bind = array();
            $status = request::get_int('status');
            if(request::get_string('name'))
            {
                $where .= ' AND name ILIKE :name ';
		$bind['name'] = request::get_string('name').'%';
            }
            if(request::get_int('ppo'))
            {
                $where .= ' AND po_id IN (SELECT id FROM ppo WHERE category = :category) ';
                $bind['category'] = request::get_int('ppo');
            }
            if(request::get('start_day') && request::get('start_month') && request::get('start_year'))
            {
                if(request::get('end_day') && request::get('end_month') && request::get('end_year'))
                {
                    $where .= ' AND start >= :start AND "end" <= :end ';
                    $bind['start'] = user_helper::dateval('start');
                    $bind['end'] = user_helper::dateval('end');
                }
                else
                {
                    $where .= ' AND start >= :start ';
                    $bind['start'] = user_helper::dateval('start');
                }
            }
            if($status)
            {
                if($status==99)$status=0;
                $where .= ' AND status = :status ';
		$bind['status'] = $status;
            }
            return db::get_cols('SELECT id FROM eventreport WHERE del=0 '.$where.' ORDER BY start',$bind);
        }

        public function statistics()
        {
            $bind = array();
            if(request::get('start_day') && request::get('start_month') && request::get('start_year'))
            {
                if(request::get('end_day') && request::get('end_month') && request::get('end_year'))
                {
                    $where .= ' start >= :start AND "end" <= :end ';
                    $bind['start'] = user_helper::dateval('start');
                    $bind['end'] = user_helper::dateval('end');
                }
                else
                {
                    $where .= ' start >= :start ';
                    $bind['start'] = user_helper::dateval('start');
                }
            }
            $query = db::get_rows('SELECT id,informed,agitation FROM eventreport WHERE del=0'.$where,$bind);
            $data['total'] = $data['informed'] = 0;
            $data['agitation'] = array();
            foreach($query as $q)
            {
                $data['total'] += 1;
                $data['informed'] += intval($q['informed']);
                $agitarray = unserialize($q['agitation']);
                if(is_array($agitarray))
                {
                    foreach($agitarray as $k=>$v)
                    {
                        $data['agitation'][$k] += intval($v);
                    }
                }
            }
            return $data;
        }
        
}
