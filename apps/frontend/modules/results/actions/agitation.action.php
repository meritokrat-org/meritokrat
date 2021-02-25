<?

class results_agitation_action extends frontend_controller
{
	protected $authorized_access = false;

	public function execute()
	{
            if (!session::has_credential('admin'))
            {
                    throw new public_exception('Недостаточно прав.');
            }

            load::model('geo');
            load::model('user/user_agitmaterials');
            load::model('user/user_agitmaterials_log');
            load::action_helper('pager');

            if(request::get_int('print'))
            {
                $this->set_layout('');
                $this->set_template('print');
            }

            $table = 'user_agitmaterials';
            if(request::get_int('period') && (request::get('start') || request::get('end')))$table = 'user_agitmaterials_log';

            if(!request::get('view') OR request::get('view')=='peoples')
            {
                $sqladd = "WHERE (receive > 0 OR given > 0 OR presented > 0)";

                if(request::get_int('region'))
                    $sqladd.=" AND user_id IN(SELECT user_id FROM user_data WHERE region_id = " . request::get_int('region') . ")";

                if(request::get_int('type'))
                    $sqladd.=" AND type = " . request::get_int('type');

                if(request::get('start'))
                {
                    $sqladd .= " AND date >= ".$this->get_timestamp(request::get('start'))." ";
                    $this->sql .= " AND date >= ".$this->get_timestamp(request::get('start'))." ";
                }
                if(request::get('end'))
                {
                    $sqladd .= " AND date <= ".$this->get_timestamp(request::get('end'))." ";
                    $this->sql .= " AND date <= ".$this->get_timestamp(request::get('end'))." ";
                }
                $this->list = db::get_cols("SELECT
                      user_id, SUM(receive) AS receive 
                      FROM $table
                      $sqladd
                      GROUP BY user_id
                      ORDER BY receive DESC");
                //db::get_cols($sql, array(), null, array('posts_rated_'.$type, 60*5));
                if(!is_array($this->list))$this->list=array();
                if(!request::get_int('print') OR (request::get_int('print') && !request::get_int('all')))
                {
                    $this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 20);
                    $this->list = $this->pager->get_list();
                }
            }
            elseif(request::get('view')=='regions')
            {
                if(request::get('start'))
                    $sqladd = " AND date >= ".$this->get_timestamp(request::get('start'))." ";

                if(request::get('end'))
                    $sqladd .= " AND date <= ".$this->get_timestamp(request::get('end'))." ";

                $query = db::get_rows("SELECT
                      SUM(a.receive) as receive, SUM(a.given) AS given, SUM(a.presented) AS presented, a.type, u.region_id
                      FROM $table AS a, user_data AS u
                      WHERE a.user_id = u.user_id AND u.user_id != 2546
                      $sqladd
                      GROUP BY a.type, u.region_id ORDER BY u.region_id, a.type");
                foreach($query as $q)
                {
                    if(intval($q['receive']) OR intval($q['given']) OR intval($q['presented']))
                    {
                        $arr[$q['region_id']][$q['type']] = $q;
                    }
                }
                $this->list = $arr;
            }
            elseif(request::get('view')=='types')
            {
                //$this->list = user_helper::get_agimaterials();
                if(request::get('start'))
                    $sqladd = " AND date >= ".$this->get_timestamp(request::get('start'))." ";

                if(request::get('end'))
                    $sqladd .= " AND date <= ".$this->get_timestamp(request::get('end'))." ";

                $query = db::get_rows("SELECT
                        SUM(receive) AS receive, SUM(given) AS given, SUM(presented) AS presented, type 
                        FROM $table
                        WHERE user_id=2546 $sqladd GROUP BY type ORDER BY type");

                foreach($query as $q)
                {
                    if(intval($q['receive']) OR intval($q['given']) OR intval($q['presented']))
                    {
                        $arr[$q['type']] = $q;
                    }
                }
                $this->list = $arr;
            }
        }

        private function get_timestamp($str)
        {
            $segments = explode('-',$str);
            return mktime(0, 0, 0, intval($segments[1]), intval($segments[0]), intval($segments[2]));
        }
}