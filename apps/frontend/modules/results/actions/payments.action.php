<?

class results_payments_action extends frontend_controller
{
	protected $authorized_access = true;

	public function execute()
	{
            load::model('user/user_desktop');
            $user_desktop = user_desktop_peer::instance()->get_item(session::get_user_id());
            $user_functions = explode(',',str_replace(array('{','}'),array('',''),$user_desktop['functions']));
            if(!session::has_credential('admin') && !in_array(1,$user_functions))
            {
                throw new public_exception('У вас недостаточно прав');
            }

            load::model('geo');
            load::model('ppo/ppo');

            if(!request::get_int('allyears'))
            {
                $year = (request::get_int('year')) ? request::get_int('year') : date('Y');
                if($year<2011)$year = 2011;
                if($year>date('Y'))$year = date('Y');
                $this->year = $year;
                $period = ' AND (p.period >= '.mktime(0,0,0,1,1,$year).' AND p.period <= '.mktime(0,0,0,12,31,$year).')';
                $this->period = ' AND (date >= '.mktime(0,0,0,1,1,$year).' AND date <= '.mktime(0,0,0,12,31,$year).')';
                $this->prevperiod = ' AND (date >= '.mktime(0,0,0,1,1,($year-1)).' AND date <= '.mktime(0,0,0,12,31,($year-1)).')';
            }

            if(request::get_int('city') && request::get_int('ppo'))
            {
                $sql = "SELECT pm.group_id, p.type, SUM(p.summ), COUNT(p.summ) FROM ppo_members pm, user_payments p
                        WHERE p.approve = 2 AND p.user_id = pm.user_id AND p.del = 0 $period
                        AND pm.group_id IN (SELECT id FROM ppo WHERE active = 1 AND category = 1 AND city_id = ".request::get_int('city').")
                        GROUP BY pm.group_id, p.type 
                        ORDER BY pm.group_id";
                $this->list = $this->make_list(db::get_rows($sql),'group_id');
                $this->list = $this->make_array($this->list,ppo_peer::instance()->get_by_city(request::get_int('city')));
            }
            elseif(request::get_int('city'))
            {
                load::action_helper('pager');
                $sql = "SELECT u.user_id, p.type, SUM(p.summ), COUNT(p.summ) FROM user_data u, user_payments p
                        WHERE p.user_id = u.user_id AND p.approve = 2 AND p.del = 0 $period AND u.city_id = ".request::get_int('city')."
                        GROUP BY u.user_id, p.type, u.last_name 
                        ORDER BY u.last_name";
                $this->list = db::get_rows($sql);
                $this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 100);
                $this->list = $this->make_list($this->pager->get_list(),'user_id');
            }
            elseif(request::get_int('region') && request::get_int('people'))
            {
                load::action_helper('pager');
                $sql = "SELECT u.user_id, p.type, SUM(p.summ), COUNT(p.summ) FROM user_data u, user_payments p
                        WHERE p.user_id = u.user_id AND p.approve = 2 AND p.del = 0 $period AND u.region_id = ".request::get_int('region')."
                        GROUP BY u.user_id, p.type, u.last_name 
                        ORDER BY u.last_name";
                $this->list = db::get_rows($sql);
                $this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 100);
                $this->list = $this->make_list($this->pager->get_list(),'user_id');
            }
            elseif(request::get_int('region'))
            {
                $sql = "SELECT u.city_id, p.type, SUM(p.summ), COUNT(p.summ) FROM user_data u, user_payments p
                        WHERE p.user_id = u.user_id AND p.approve = 2 AND p.del = 0 $period AND u.region_id = ".request::get_int('region')."
                        GROUP BY u.city_id, p.type 
                        ORDER BY u.city_id";
                $this->list = $this->make_list(db::get_rows($sql),'city_id');
                $this->list = $this->make_array($this->list,geo_peer::instance()->get_cities(request::get_int('region')));
            }
            else
            {
                $sql = "SELECT u.region_id, p.type, SUM(p.summ), COUNT(p.summ) FROM user_data u, user_payments p
                        WHERE p.user_id = u.user_id AND p.approve = 2 AND p.del = 0 $period 
                        GROUP BY u.region_id, p.type
                        ORDER BY u.region_id";
                $this->list = $this->make_list(db::get_rows($sql),'region_id');
                $this->list = $this->make_array($this->list,geo_peer::instance()->get_regions(1));

                if($this->year && $this->year!=2011)
                {
                    $period = ' AND (p.period >= '.mktime(0,0,0,1,1,($this->year-1)).' AND p.period <= '.mktime(0,0,0,12,31,($this->year-1)).')';
                    $sql = "SELECT u.region_id, p.type, SUM(p.summ), COUNT(p.summ) FROM user_data u, user_payments p
                        WHERE p.user_id = u.user_id AND p.approve = 2 AND p.del = 0 $period
                        GROUP BY u.region_id, p.type
                        ORDER BY u.region_id";
                    $this->list2 = $this->make_list(db::get_rows($sql),'region_id');
                    $this->list2 = $this->make_array($this->list2,geo_peer::instance()->get_regions(1));
                }
            }
        }

        private function make_list($array,$column)
        {
            $result = array();
            foreach($array as $item)
            {
                $result[$item[$column]][$item['type']] = $item;
            }
            return $result;
        }

        private function make_array($array1=array(),$array2=array())
        {
            foreach($array2 as $k=>$v)
            {
                if($array1[$k])unset($array2[$k]);
            }
            if(count($array2)>0)
            {
                $array1 = $array1 + $array2;
                ksort($array1);
            }
            return $array1;
        }

}