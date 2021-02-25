<?

class search_index_action extends frontend_controller
{
	#protected $authorized_access = true;
	public function execute()
	{
        load::model('user/user_desktop');
        load::model('user/user_novasys_data');
        load::model('user/user_contact');
        load::model('geo');

		$this->sortable_list = user_auth_peer::instance()->get_sortable_list();

        $this->is_regional_coordinator = user_desktop_peer::instance()->is_regional_coordinator(session::get_user_id(),true);
        $this->is_raion_coordinator = user_desktop_peer::instance()->is_raion_coordinator(session::get_user_id(),true);
        $this->is_logistic_coordinator_region = user_desktop_peer::instance()->is_logistic_coordinator(session::get_user_id(),'region',true);
        $this->is_logistic_coordinator_raion = user_desktop_peer::instance()->is_logistic_coordinator(session::get_user_id(),'city',true);
                
        $this->region_selected = request::get('region', 0);
        $this->region_selected = request::get('region_adv', 0);

        if($this->region_selected == "")
	        $this->region_selected = 0;

        $this->city_selected = request::get('city', 0);
        $this->city_selected = request::get('city_adv_select', 0);

        if($this->city_selected == "")
	        $this->city_selected=0;
                
        if(request::get('regId'))
        {

            if ($data = geo_peer::instance()->get_cities(request::get('regId')))
                foreach ($data as $key => $city)
                    $cities[] = array('id' => $key, 'title' => $city);

            ksort($cities);
            $result = array('type'=>'success', 'cities'=>$cities);

            die(json_encode($result));
        }

		if ( request::get('submit') )
		{
			$this->keyword = str_replace(array('\\', '-', '<', '>', '"', '\'', ')', '('), '', $this->keyword);
			$this->keyword = preg_replace('/ +/', ' ', $this->keyword);

			$filters = array();
            $city_id = request::get('city',null);

			if ( $this->city_id = (int)$city_id )
				$filters['city_id'] = $this->city_id;

			if ( $this->gender = request::get('gender') )
				$filters['gender'] = $this->gender;

			if(request::get('age_end') or request::get('age_start'))
                if(request::get('age_end') != '...' or request::get('age_start')!='...')
                {
                    $this->age_start = date("Y-m-d",(time()-request::get_int('age_start',0)*12*30*24*60*60));
                    $this->age_end = date("Y-m-d",(time()-request::get_int('age_end',120)*12*30*24*60*60));
                }

            $filters['birthday'] = array($this->age_start, $this->age_end);
			if (request::get('first_name')) $filters['first_name'] = htmlspecialchars(request::get_string('first_name'));
			if (request::get('last_name')) $filters['last_name'] = htmlspecialchars(request::get_string('last_name'));
			if (request::get('country')) $filters['country_id'] = request::get_int('country');
			if (request::get('region')) $filters['region_id'] = request::get_int('region');
            if (request::get('region_adv')) $filters['region_id'] = request::get_int('region_adv');
            if (request::get('city')) $filters['city_id'] = request::get_int('city');
			if (request::get('city_txt')) $filters['city'] = request::get_string('city_txt');
			if (request::get('region_txt')) $filters['region'] = request::get_string('region_txt');
            if (request::get('city_adv_select')) $filters['city_id'] = request::get_int('city_adv_select');
			if (request::get('segment')) $filters['segment'] = request::get_int('segment');
			if (request::get('status')) $filters['status'] = request::get_int('status');
			if (request::get('function')) $filters['function'] = request::get_int('function');
			if (request::get('visit_ts')) $filters['visit_ts'] = request::get_int('visit_ts');
			if (request::get('type')) $filters['type'] = request::get_int('type');
			if ($_GET['active']==10) $filters['active']='FALSE';
			elseif ($_GET['active']==1) $filters['active'] = 1;
			if (request::get('chief_contact')) $filters['chief_contact'] = request::get_int('chief_contact');
			if (request::get('coordinator_contact')) $filters['coordinator_contact'] = request::get_int('coordinator_contact');
			if (request::get('interesting')) $filters['interesting'] = request::get_int('interesting');
			if (request::get('email')) $filters['email'] = request::get_string('email');
            if (request::get('phone')) $filters['phone'] = request::get_string('phone');
            if (request::get_int('has_phone')) $filters['has_phone'] = request::get_int('has_phone');
			if (request::get_int('start_begin_day') && request::get_int('start_begin_month') && request::get_int('start_begin_year')) $filters['start_begin'] = user_helper::dateval('start_begin');
			if (request::get_int('start_end_day') && request::get_int('start_end_month') && request::get_int('start_end_year')) $filters['start_end'] = user_helper::dateval('start_end')+24*3600;
			if (request::get_int('activation_begin_day') && request::get_int('activation_begin_month') && request::get_int('activation_begin_year')) $filters['activation_begin'] = user_helper::dateval('activation_begin');
			if (request::get_int('activation_end_day') && request::get_int('activation_end_month') && request::get_int('activation_end_year')) $filters['activation_end'] = user_helper::dateval('activation_end')+24*3600;
            if (request::get('expert')) $filters['expert'] = request::get_int('expert');
            if (request::get('contacted')) $filters['contacted'] = request::get_int('contacted');
            if (request::get('offline')) $filters['offline'] = request::get_int('offline');
            if (request::get('about')) $filters['about'] = request::get_int('about');
            if (request::get('contact_status') or request::get('contact_status')==='0') $filters['contact_status'] = request::get_int('contact_status');
            if (request::get('target')) $filters['target'] = '{'.request::get_int('target').'}';
            if (request::get('admintarget')) $filters['admin_target'] = '{'.request::get_int('admintarget').'}';
            if (request::get('work_jobsearch1')) $filters['work_jobsearch1'] = request::get_int('work_jobsearch1');
            if (request::get('work_jobsearch2')) $filters['work_jobsearch2'] = request::get_int('work_jobsearch2');
			//if (session::get_user_id()==29) var_dump($filters);

            if(request::get_int('contacts'))
            {
                if (request::get('contact_type')) $filters['contact_type'] = request::get_int('contact_type');
                if (request::get('contact_user_id')) $filters['contact_user_id'] = request::get_int('contact_user_id');
                if (request::get('description')) $filters['description'] = request::get('description');
                if (request::get('contact_who')) $filters['contact_who'] = request::get_int('contact_who');
                if (request::get_int('period_begin_day') && request::get_int('period_begin_month') && request::get_int('period_begin_year')) $filters['period_begin'] = user_helper::dateval('period_begin');
                if (request::get_int('period_end_day') && request::get_int('period_end_month') && request::get_int('period_end_year')) $filters['period_end'] = user_helper::dateval('period_end')+24*3600;
                if (request::get('region_contact_city_select')) $filters['city_id']=request::get_int('region_contact_city_select');
                if (request::get('contact_region')) $filters['region_id']=request::get_int('contact_region');

                if($filters['period_begin'])$this->begin = intval($filters['period_begin']);
                if($filters['period_end'])$this->ends = intval($filters['period_end']);

                if(session::has_credential('admin'))
                {
                    $this->users = user_data_peer::instance()->contact_search($filters, 500, 0 );
                }
                else
                {
                    $this->users = user_data_peer::instance()->contact_search($filters, 500, 0, $this->is_regional_coordinator, $this->is_raion_coordinator );
                }
            }
            elseif(request::get_int('map') && session::is_authenticated())
            {
               $user=user_data_peer::instance()->get_item(session::get_user_id());
               if(!$user['locationlat'])$this->redirect ("/profile/edit?id=".session::get_user_id()."&tab=map");
                load::action_helper('pager', true);
               $this->users = user_data_peer::instance()->map_search();
               #$this->map_pager = pager_helper::get_pager($this->users, request::get_int('page'), 21);
               #$this->users = $this->map_pager->get_list();
               $this->map_data=user_data_peer::instance()->map_data;
            }
            elseif(request::get_int('smap'))
            {
               $user=user_data_peer::instance()->get_item(session::get_user_id());
               if(!$user['locationlat'])$this->redirect ("/profile/edit?id=".session::get_user_id()."&tab=map");
            }
            else
            {
                request::get('sort')=='az' ? $order='last_name' : $order='user_id DESC';
                $this->users = user_data_peer::instance()->my_search($filters, 20000, 0, $order );
            }

            $this->count_found=count($this->users);
			if ( $this->found = (bool)$this->users )
			{
                if(count($this->users) > 0)
                {
                    if($filters['region_id'])
	                    $this->region_coordinators = user_desktop_peer::instance()->get_regional_coordinators($filters['region_id']);
                    else
                        $this->region_coordinators = db::get_cols("SELECT user_id FROM user_desktop WHERE functions && '{5}' AND user_id IN (".implode(',',$this->users).")");

                    if($filters['city_id'])
                        $this->raion_coordinators = user_desktop_peer::instance()->get_raion_coordinators($filters['city_id']);
                    else{
                        if($filters['region_id']){
                            $raions_ar=(array)geo_peer::instance()->get_cities($filters['region_id']);

	                        if(count($raions_ar) > 0)
	                        {
		                        foreach ($raions_ar as $rid => $k)
			                        $raions[] = $rid;

		                        $sqladd = " AND city_id IN (" . implode(',', $raions) . ")";
	                        }
	                        else
		                        $sqladd = "";
                        }

	                    $this->raion_coordinators = db::get_cols("SELECT user_id
                            FROM user_desktop_funct
                            WHERE function_id=6 AND user_id IN (".implode(',',$this->users).")".$sqladd);
                    }

                    if($filters['region_id'] || $filters['city_id'])
                        $this->logistic_coordinators=user_desktop_peer::instance()->get_logistic_coordinators($filters['region_id'],$filters['city_id']);
                    else
                        $this->logistic_coordinators = db::get_cols("SELECT user_id FROM user_desktop_funct WHERE function_id=18 AND user_id IN (".implode(',',$this->users).")");

                    $this->all_coordinators=array();

	                if(($filters['region_id'] && $filters['country_id'] && !$filters['birthday'][0] && !$filters['birthday'][1] && count($filters)==3)
                        or
                       ($filters['region_id'] && $filters['country_id'] && $filters['city_id'] && !$filters['birthday'][0] && !$filters['birthday'][1] && count($filters)==4))
                    {
                        $raion_c = array_diff($this->raion_coordinators,$this->region_coordinators);
                        $logis_c = array_diff($this->logistic_coordinators,$raion_c,$this->region_coordinators);
                        $this->all_coordinators = array_merge($this->region_coordinators,$raion_c,$logis_c);
                        $this->all_coordinators = array_unique($this->all_coordinators);

                    }
                    $this->users = array_diff($this->users, $this->all_coordinators);
                }
                                
                load::action_helper('pager', true);
				if (
						!(session::has_credential('admin')
						|| (in_array(request::get('region'),$this->is_regional_coordinator))
						|| (in_array(request::get('city'), $this->is_raion_coordinator)))
						|| (in_array(request::get('region'), $this->is_logistic_coordinator_region))
						|| (in_array(request::get('city'), $this->is_logistic_coordinator_raion))
						|| request::get_int('all_pages')!=1
				) {
                    request::get_int('map') ? $this->pager = pager_helper::get_pager($this->users, request::get_int('page'), 21) : $this->pager = pager_helper::get_pager($this->users, request::get_int('page'), 20);
                    $this->users = $this->pager->get_list();
				}
			}

			if (
					(session::has_credential('admin')
					|| (in_array(request::get('region'),$this->is_regional_coordinator))
					|| (in_array(request::get('city'), $this->is_raion_coordinator)))
					&& request::get_int('print')==1
			)
            {
                $array = array('ft','nm','ot','ct','rg','sg','br','em','ph','fn','st','hd','at','rt','rf','cn','pc','cd');
                foreach($array as $a)
                {
                    $this->ft[$a] = request::get_int($a);
                }
                $this->set_template('print');
                $this->set_layout('');
            }
		}
	}
}
