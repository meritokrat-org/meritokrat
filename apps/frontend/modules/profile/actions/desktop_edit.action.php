<?

class profile_desktop_edit_action extends frontend_controller
{
	protected $authorized_access = true;

	public function execute()
	{
                load::model('ppo/ppo');
                load::model('ppo/members');
                load::action_helper('membership',false);
                
                load::action_helper('membership',false);
                if ( session::has_credential('admin') )
		{
			if ( !$user_id = request::get_int('id') )
			{
				$user_id = session::get_user_id();
			}
		}
		else
		{
			$user_id = session::get_user_id();
		}

		$this->user = user_auth_peer::instance()->get_item($user_id);
                $this->user_data = user_data_peer::instance()->get_item($user_id);

                if(!user_auth_peer::instance()->get_rights($this->user['id'], 10) && !$this->user['desktop']==1) throw new public_exception( t('Трапилася помилка') );
                load::model('user/user_desktop_help');
                load::model('user/user_desktop_received_help');
                load::model('user/user_desktop_active_help');
                load::model('user/user_desktop_help_types');
                load::model('user/user_desktop_funct');
                load::model('user/user_agitmaterials');
                
                load::model('user/user_payments');
                load::model('user/user_payments_log');
                $this->payments = user_payments_peer::instance()->get_by_user($user_id);

                load::model('user/membership');
                $this->membership = user_membership_peer::instance()->get_user($user_id);

                load::model('user/zayava');
                $zid = user_zayava_peer::instance()->get_user($user_id);
                if($zid[0])
                    $this->zayava = user_zayava_peer::instance()->get_item($zid[0]);

                $this->active_help = user_desktop_active_help_peer::instance()->get_list(array('user_id'=>$user_id));
                $this->help_types = user_desktop_help_peer::getHelpTypes();

		load::model('user/user_desktop');
		$this->user_desktop = db::get_row("SELECT * FROM user_desktop WHERE user_id=:user_id", array('user_id'=>$user_id));
                $this->user_functions=explode(',',str_replace(array('{','}'),array('',''),$this->user_desktop['functions'])); 
                $this->tent_data = unserialize($this->user_desktop['information_tent']);
                $this->inet_data = unserialize($this->user_desktop['information_inet']);
                
                load::model('user/user_desktop_meeting');

                $this->meetings_list = user_desktop_meeting_peer::instance()->get_list(array('user_id'=>$user_id));
                foreach ($this->meetings_list as $meeting) {
                  //  echo $meeting;
                    $this->user_desktop_meeting[]=user_desktop_meeting_peer::instance()->get_item($meeting);
                
                }
                load::model('user/user_desktop_event');

                $this->events_list = user_desktop_event_peer::instance()->get_list(array('user_id'=>$user_id));

                foreach ($this->events_list as $event) {
                    $this->user_desktop_event[]=user_desktop_event_peer::instance()->get_item($event);
                }


                load::model('user/user_desktop_education');
                
                $this->educations_list = user_desktop_education_peer::instance()->get_list(array('user_id'=>$user_id));
                foreach ($this->educations_list as $education) {
                    $this->user_desktop_education[]=user_desktop_education_peer::instance()->get_item($education);
                }
                
                load::model('user/user_desktop_signature');
                
                $this->signature_list = user_desktop_signature_peer::instance()->get_list(array('user_id'=>$user_id));
                foreach ($this->signature_list as $education) {
                    $this->user_desktop_signature[]=user_desktop_signature_peer::instance()->get_item($education);
                }

		load::view_helper('user');


		session::has_credential('admin') ? client_helper::register_variable('defaultTab', request::get_string('tab', 'function')) : client_helper::register_variable('defaultTab', request::get_string('tab', 'information'));

                $this->user_desktop_funct=user_desktop_funct_peer::instance()->get_user($user_id,user_auth_peer::get_functions());
                
		if ( request::get('submit') )
		{
			$this->set_renderer('ajax');
			$this->json = array();
                      
			if ( request::get('type') == 'function'  and session::has_credential('admin') )
			{
				$user_functions = explode(',', str_replace(array('{', '}'), array('', ''), $this->user_desktop['functions']));
				$tmp_functions = array();
				foreach($user_functions as $func){
					if($func > 110)
						$tmp_functions[] = $func;
				}
				$tmp_functions2 = array();
				foreach(user_auth_peer::get_functions() as $function_id=>$function_title){
					if (request::get_int('function_'.$function_id)==1)
						$tmp_functions2[] = $function_id;
				}
				$tmp_functions = array_merge($tmp_functions, $tmp_functions2);
				$function_update = '{'.implode(',', $tmp_functions).'}';
        /*                      $function_update='{';
                              foreach (user_auth_peer::get_functions() as $function_id=>$function_title) if (request::get_int('function_'.$function_id)==1) $function_update.= $function_id.',';
                              $function_update.='}';*/

        $regions = serialize(array('region'=>request::get('region'),'city'=>request::get('city')));

        user_desktop_peer::instance()->update(array(
					'user_id' => $user_id,
					'functions' => $function_update,
                                        //'regions' => $regions
				));

                              // отправка письма при назначении координатором
                             foreach (array(5,6,18) as $function_id) { 
                                  if (request::get_int('function_'.$function_id) && !in_array($function_id,$this->user_functions))
                                  {
                                          load::action_helper('user_email', false);

                                                    @$region=request::get('region-'.$function_id);
                                                    $functions=array(
                                                        5 => t('Координатора развития региона'),
                                                        6 => t('Координатора развития района'),
                                                        18 => t('Логистического координатора')
                                                        
                                                    );
                                                    
                                                    $options = array(
                                                                '%receiver%' => user_helper::full_name($user_id, false),
                                                                '%function%' => $functions[$function_id],
                                                                '%url%' => ($function_id==18 ? 'https://meritokrat.org/groups/talk_topic?id=42' : 'https://meritokrat.org/blogpost2027'),
                                                                '%search_url%' => ($function_id==18 ? 'https://meritokrat.org/blogpost76' : 'https://meritokrat.org/search?country=1&submit=1&region='.$region[0]),
                                                                );

                                          user_email_helper::send_sys('add_coordinator_function',$user_id,31,$options);
                                  }
                              }
                              
                              user_desktop_funct_peer::instance()->del_user($user_id);
                              foreach (user_auth_peer::get_functions() as $function_id=>$function_title)
                              {  
                                $regn=request::get('region-'.$function_id);
                                $cit=request::get('city-'.$function_id);  
                        
                                if($regn[0]>0){ 
                                    foreach ($regn as $k=>$r){
                                if (request::get_int('function_'.$function_id)==1){
                                user_desktop_funct_peer::instance()->insert(array(
                                                'user_id' => $user_id,
                                                'region_id' => intval($r),
                                                'city_id' => intval($cit[$k]),
                                                'function_id' => $function_id));
                                    }}}
                              }

			}

			elseif ( request::get('type') == 'information' )
			{
                            $banners=array();
                            $publications=array();

                            $ptitle = request::get('p_title');
                            $pdescription = request::get('p_description');
                            $pmedianame = request::get('p_media_name');
                            $ptype = request::get('p_type');
                            $patype = request::get('another_p_type');
                            $ppubl = request::get('publ');
                            $ppurl = request::get('purl');
                            $pdate = user_helper::dateval('p_date',true);
                            $purl = request::get('p_url');

                            foreach($ptitle as $k=>$v)
                            {
                                if(trim($v))
                                {
                                    $publications[]=array(
                                        'title' => $ptitle[$k],
                                        'description' => $pdescription[$k],
                                        'media_name' => $pmedianame[$k],
                                        'type' => intval($ptype[$k]),
                                        'another_type' => $patype[$k],
                                        'publ' => intval($ppubl[$k]),
                                        'purl' => intval($ppurl[$k]),
                                        'date' => date('Y/m/d',$pdate[$k]),
                                        'url' => (strlen($purl[$k])>3 AND mb_substr($purl[$k],0,7)!=='http://') ? 'http://'.$purl[$k] : $purl[$k]
                                    );
                                }
                            }

                                $i=0;
                              foreach(request::get('b_title') as $title)
                                {
                                  if (trim($title))
                                    {
                                    $banners[]=array(
                                                'title' => $title,
                                                'description' => request::get('b_description_'.$i),
                                                'url' => (strlen(request::get('b_url_'.$i))>3 AND mb_substr(request::get('b_url_'.$i),0,7)!=='http://') ? 'http://'.request::get('b_url_'.$i) : request::get('b_url_'.$i));
                                    }
                                    $i++;
                                }
                                

                            if($tent = request::get('tent_details')) {
                                $tent_details = request::get('tent_details');
                                $tent_date = user_helper::dateval('tent_date',true);
                                $tent_desc = request::get('tent_details');
                                $tent_hours = request::get('tent_hours');
                                foreach ($tent as $k => $v) {
                                    if(trim($tent_desc[$k]))
                                        $tent_data[] = array(
                                                                'hours'=>(intval($tent_hours[$k])+1),
                                                                'date'=>$tent_date[$k],
                                                                'description'=>trim($tent_desc[$k])
                                                            );
                                }
                                user_desktop_peer::instance()->update(array(
                                                                            'user_id' => $user_id,
                                                                            'information_tent'=>serialize($tent_data)
                                                                            ));
                                rating_helper::updateRating($user_id, 'tent');
                            }
                            if($inet = request::get('inet_details')) {
                                $inet_details = request::get('inet_details');
                                $inet_date = user_helper::dateval('inet_date',true);
                                $inet_desc = request::get('inet_details');
                                $inet_hours = request::get('inet_hours');
                                foreach ($inet as $k => $v) {
                                    if(trim($inet_desc[$k]))
                                        $inet_data[] = array(
                                                                'hours'=>(intval($inet_hours[$k])+1),
                                                                'date'=>$inet_date[$k],
                                                                'description'=>trim($inet_desc[$k])
                                                            );
                                }
                                user_desktop_peer::instance()->update(array(
                                                                            'user_id' => $user_id,
                                                                            'information_inet'=>serialize($inet_data)
                                                                            ));
                                rating_helper::updateRating($user_id, 'inet');
                            }

                                /**user_agitmaterials_peer::instance()->delete($user_id);

                                $agitmaterials_recive = request::get('agitmaterial_receive');
                                $agitmaterials_given = request::get('agitmaterial_given');
                                $agitmaterials_presented = request::get('agitmaterial_presented');
                                foreach(user_helper::get_agimaterials() as $k=>$v)
                                {
                                    user_agitmaterials_peer::instance()->insert(array(
                                        'user_id' => $user_id,
                                        'type' => $k,
                                        'receive' => intval($agitmaterials_recive[$k]),
                                        'given' => intval($agitmaterials_given[$k]),
                                        'presented' => intval($agitmaterials_presented[$k])
                                    ));
                                }*/

                                user_desktop_peer::instance()->update(array(
					'user_id' => $user_id,
                                        'information_banners' => serialize($banners),
                                        'information_publications' => serialize($publications),
                                      //  'information_avtonumbers' => request::get('information_avtonumbers',0),
					'information_people_private_count' => request::get_int('information_people_private_count',0),
					'information_people_phone_count' => request::get_int('information_people_phone_count',0),
					'information_people_email_count' => request::get_int('information_people_email_count',0),
					'information_people_social_count' => request::get_int('information_people_social_count',0)
				));
                                 if(!empty($publications)) rating_helper::updateRating($user_id, 'publications');
                                 if(!empty($banners)) rating_helper::updateRating($user_id, 'banners');
			}

			elseif ( request::get('type') == 'tasks' )
			{
                            $i=0;
                            foreach ($this->signature_list as $signature)  user_desktop_signature_peer::instance()->delete_item( $signature );
                            foreach (request::get('signatures') as $signature)
                                {
                                if (request::get_int('city_'.$signature) && request::get_int($signature))
                                    {
                                            user_desktop_signature_peer::instance()->insert(array(
                                                'user_id' => $user_id,
                                                'region_id' => request::get_int($signature,0),
                                                'city_id' => request::get_int('city_'.$signature,0),
                                                'plan' => request::get_int('plan_'.$signature,0),
                                        ));
                                    }
                                     $i++;
                                }
			}
                        elseif ( request::get('type') == 'active_help' )
			{
//                            print_r(request::get_all());
//                            exit;

                            $active = user_desktop_active_help_peer::instance()->get_list(array('user_id'=>$user_id));
//                            print_r($active);
//                            exit;
                            if(!empty($active))
                                user_desktop_active_help_peer::instance()->update(array('user_id'=>$user_id,'active'=>  request::get_int('act')));
                            else
                                user_desktop_active_help_peer::instance ()->insert (array(
                                                                        'user_id'=>$user_id,
                                                                        'active'=> request::get_int('act')
                                                                        ));
			}
                        elseif ( request::get('type') == 'help' && session::has_credential('admin'))
			{
                            if(request::get_int('help_type')==-1) {
                               $data =  user_desktop_help_types_peer::instance()->get_list(array('name'=>request::get('new_type')));
                               if(empty($data)) {
                                    user_desktop_help_types_peer::instance()->insert(array(
                                                                                'name'=>  request::get('new_type')
                                                                                ));
                               }
                                $help_data = user_desktop_help_types_peer::instance()->get_list(array('name'=>request::get('new_type')));
                                $help_type = $help_data[0];
                            }

                            if(request::get('need')==0) {
                                if(request::get_int('help_type')!=-1)
                                    $help_type = request::get_int('help_type');
                                user_desktop_help_peer::instance()->insert(array(
                                       'user_id' => $user_id,
                                       'need' => request::get_int('need'),
                                       'type' => $help_type,
                                       'describe' => request::get('help_describe'),
                                       'hours_per_week' => request::get_int('hours_per_week'),
                                ));
                            }
                            elseif(request::get('need')==1) {
                                if(request::get_int('help_type')!=-1)
                                    $help_type = request::get_int('help_type');
                                user_desktop_help_peer::instance()->insert(array(
                                       'user_id' => $user_id,
                                       'need' => request::get_int('need'),
                                       'type' => $help_type,
                                       'describe' => request::get('help_review'),
                                ));
                            }
			}

			elseif ( request::get('type') == 'change_help' && session::has_credential('admin') )
			{
//                            print_r(request::get_all());
//                            exit;
                            
                            if(request::get('update')==0)
                                user_desktop_help_peer::instance()->delete_item (request::get_int('key'));
                            elseif(request::get('need')==0)
                                    user_desktop_help_peer::instance()->update(array(
                                       'id'=>  request::get_int ('key'),
                                       'user_id' => $user_id,
                                       'need' => request::get_int('need'),
                                       'type' => request::get_int('help_type'),
                                       'describe' => request::get('help_describe'),
                                       'hours_per_week' => request::get_int('hours_per_week'),
                                    ));
                                else
                                    user_desktop_help_peer::instance()->update(array(
                                           'id'=>  request::get_int ('key'),
                                           'user_id' => $user_id,
                                           'need' => request::get_int('need'),
                                           'type' => request::get_int('help_type'),
                                           'describe' => request::get('help_describe'),
                                    ));
			}

			elseif ( request::get('type') == 'receive'  && session::has_credential('admin'))
			{
//                            print_r(request::get_all());
                            
                           // if(isset(request::get_int('mounth_from'))&&isset(request::get_int('year_from'))&&isset(request::get_int('mounth_to'))&&isset(request::get_int('year_to')))
                               $date = request::get_int('mounth_from').'/'.request::get_int('year_from').'-'.request::get_int('mounth_to').'/'.request::get_int('year_to');
//                            print_r($date);
//                            exit;
                                user_desktop_received_help_peer::instance()->insert(array(
                                       'userfrom' => $user_id,
                                       'userto' => request::get_int('user_to'),
                                       'type' => request::get_int('help_type'),
                                       'describe' => request::get('help_describe'),
                                       'receive_date' => $date,
                                ));
                        }
                        elseif ( request::get('type') == 'people' )
			{
                            if (session::has_credential('admin')) {
                            user_desktop_peer::instance()->update(array(
					'user_id' => $user_id,
                                        'people_recommended' => request::get_int('people_recommended'),
                                        'people_attracted' => request::get_int('people_attracted')
                                ));
                            }
                           /* user_desktop_peer::instance()->update(array(
					'user_id' => $user_id,
					'information_people_count' => request::get_int('information_people_count',0)
				)); */
			}
                        
			elseif ( request::get('type') == 'other' )
			{
                            user_desktop_peer::instance()->update(array(
					'user_id' => $user_id,
                                        'other' => request::get_string('other')
                                ));
			}

			elseif ( request::get('type') == 'meetings' )
			{
                            $etitle = request::get('title');
                            $edescription = request::get('description');
                            $edate = user_helper::dateval('date',true);
                            $epart = request::get('status');
                            foreach ($this->meetings_list as $meeting)  user_desktop_meeting_peer::instance()->delete_item( $meeting );
                            foreach($etitle as $k=>$v)
                            {
                                if (strlen(trim($v))>3)
                                {
                                    user_desktop_meeting_peer::instance()->insert(array(
                                        'user_id' => $user_id,
                                        'title' => $etitle[$k],
                                        'description' => $edescription[$k],
                                        'meeting_date' => date('Y/m/d',$edate[$k]),
                                        'part' => intval($epart[$k])
                                    ));
                                }
                            }
			}
			elseif ( request::get('type') == 'events' )
			{
                            $etitle = request::get('event_title');
                            $edescription = request::get('event_description');
                            $edate = user_helper::dateval('event_date',true);
                            $epart = request::get('event_status');
                            $members = request::get('member_count');
                            
                            foreach ($this->events_list as $event)  user_desktop_event_peer::instance()->delete_item( $event );
                            foreach($etitle as $k=>$v)
                            {
                                if (strlen(trim($v))>3)
                                    {
                                        $insert_data = array(
                                            'user_id' => $user_id,
                                            'title' => $etitle[$k],
                                            'description' => $edescription[$k],
                                            'event_date' => date('Y/m/d',$edate[$k]),
                                            'part' => intval($epart[$k])
                                        );
                                        if(intval($epart[$k])!=1) {
                                            $insert_data = array_merge ($insert_data,array('member_count'=>intval($members[$k])));
                                            $updateRating=1;
                                        }
                                        user_desktop_event_peer::instance()->insert($insert_data);
                                    }
                            }
                            rating_helper::updateRating($user_id, 'speach');
			}
			elseif ( request::get('type') == 'educations' )
			{
                            $etitle = request::get('education_title');
                            $edescription = request::get('education_description');
                            $edate = user_helper::dateval('education_date',true);
                            $epart = request::get('education_status');
                            foreach ($this->educations_list as $event)  user_desktop_education_peer::instance()->delete_item( $event );
                            foreach($etitle as $k=>$v)
                            {
                                if (strlen(trim($v))>3)
                                {
                                    user_desktop_education_peer::instance()->insert(array(
                                        'user_id' => $user_id,
                                        'title' => $etitle[$k],
                                        'description' => $edescription[$k],
                                        'education_date' => date('Y/m/d',$edate[$k]),
                                        'part' => intval($epart[$k])
                                    ));
                                }
                            }
			}
			elseif ( request::get('type') == 'photo' )
			{

				load::system('storage/storage_simple');

				load::form('profile/profile_picture');
				$form = new profile_picture_form();
				$form->load_from_request();
				if ( $form->is_valid() )
				{
					$storage = new storage_simple();

					$salt = substr(md5(microtime()),0,8);

                                        $avtonumbers=unserialize($this->user_desktop['information_avtonumbers_photos']);
                                        $avtonumbers[]= array(
                                                        'name' => request::get_string('name'),
                                                        'description' => request::get_string('description'),
                                                        'type' => request::get_int('phototype'),
                                                        'salt' => $salt
                                            );
					$key = 'avtonumber/' . $user_id . $salt . '.jpg';
					$storage->save_uploaded($key, request::get_file('file'));
                                        //echo(context::get('image_server') . user_helper::avtophoto_path($user_id,$photo_id));
					//$this->json = array();

                                        user_desktop_peer::instance()->update(array(
                                                'user_id' => $user_id,
                                                'information_avtonumbers_photos' => serialize($avtonumbers)
                                        ));
                                        rating_helper::updateRating($user_id, 'autonumber');
                                        rating_helper::updateRating($user_id, 'magnet');
                                        
				}
				else
				{
					//$this->json = array('errors' => $form->get_errors());
//print_r($form->get_errors());
				}

                                $this->redirect('/profile/desktop_edit?tab=photo&id='.$user_id);
			}
                        else if ( request::get('type') == 'avatarm' )
			{
                                user_desktop_peer::instance()->update(array(
                                                'user_id' => $user_id,
                                                'avatarm' => serialize(request::get('contacts'))
                                        ));
                                rating_helper::updateRating($user_id, 'avatarm');
			}
                        else if ( request::get('type') == 'payments' )
			{
                                user_desktop_peer::instance()->update(array(
                                        'user_id' => $user_id,
                                        'confidence' => request::get_int('confidence')
                                ));

                                /*$date = request::get('date');
                                $typ = request::get('typ');
                                $summ = request::get('summ');
                                $method = request::get('method');
                                $way = request::get('way');
                                $month = request::get('month');
                                $year = request::get('year');
                                $previd = request::get('previd');

                                foreach($previd as $pid)
                                {
                                    if($pid)$unset[] = $pid; #удаляем старые идишники которые не были переданы
                                }
                                user_payments_peer::instance()->del_user($user_id,$unset);
                                if(is_array($date))
                                {
                                    foreach($date as $k=>$v)
                                    {
                                        if(!$v || !$summ[$k]){continue;}
                                        if(!$previd[$k])
                                        {
                                            user_payments_peer::instance()->insert(array(
                                                'user_id' => $user_id,
                                                'type' => intval($typ[$k]),
                                                'summ' => intval($summ[$k]),
                                                'method' => intval($method[$k]),
                                                'way' => intval($way[$k]),
                                                'period' => $this->get_timestamp($month[$k],$year[$k]),
                                                'date' => $this->get_timestamp($v)
                                            ));
                                        }
                                        else
                                        {
                                            user_payments_peer::instance()->update(array(
                                                'id' => $previd[$k],
                                                'summ' => intval($summ[$k]),
                                                'method' => intval($method[$k]),
                                                'way' => intval($way[$k]),
                                                'period' => $this->get_timestamp($month[$k],$year[$k]),
                                                'date' => $this->get_timestamp($v)
                                            ));
                                        }
                                    }
                                }*/
			}
                        else if ( request::get('type') == 'membership' )
			{
                            if(request::get_int('kvnumber') && user_membership_peer::instance()->check_number($user_id,request::get_int('kvnumber')))
                            {
                                $this->json = array('errors' => array('kvnumber' => array(t('Партийный билет с таким номером уже существует'))));
                                return;
                            }

                            if(request::get_int('mid'))
                            {
                                user_membership_peer::instance()->update(array(
                                    'id' => request::get_int('mid'),
                                    'user_id' => $user_id,
                                    'invdate' => user_helper::dateval('invdate'),
                                    'invnumber' => trim(request::get_string('invnumber')),
                                    'kvnumber' => request::get_int('kvnumber'),
                                    'removedate' => user_helper::dateval('removedate'),
                                    'removenumber' => request::get('removenumber', ''),
                                    'removewhy' => request::get_int('removewhy'),
                                    'kvmake' => request::get_int('kvmake'),
                                    'kvgive' => request::get_int('kvgive'),
                                    'kvcomment' => mb_substr(trim(request::get_string('kvcomment')),0,250,'UTF-8'),
                                    'remove_type'=>  request::get('off_type'),
                                    'remove_from'=>session::get_user_id()
                                ));
																
																if(request::get_int("off_type")){
																	$ustatus = request::get_int('ustatus');
																	
																	if(request::get_int("off_type") == 1 && request::get_bool("statement-confirmation"))
																	{
																		load::model("user/zayava_termination");
																		$statement_data = user_zayava_termination_peer::instance()->get_statement_by_user_id($user_id);
																		$time = user_helper::dateval('removedate');
																		user_zayava_termination_peer::instance()->confirm_statement($statement_data["id"], $time);
																	}

																	membership_helper::change_status($user_id, $ustatus);
																}
                            }
                            else
                            {
                                user_membership_peer::instance()->insert(array(
                                    'user_id' => $user_id,
                                    'invdate' => user_helper::dateval('invdate'),
                                    'invnumber' => request::get_string('invnumber'),
                                    'kvnumber' => request::get_int('kvnumber'),
                                    'removedate' => user_helper::dateval('statement-date-confirmation'),
                                    'removenumber' => request::get('removenumber'),
                                    'removewhy' => request::get_int('removewhy'),
                                    'kvmake' => request::get_int('kvmake'),
                                    'kvgive' => request::get_int('kvgive'),
                                    'kvcomment' => mb_substr(trim(request::get_string('kvcomment')),0,250,'UTF-8'),
                                ));
                            }

                        }
		}
	}

        private function get_timestamp($data,$year=false)
        {
            if($year===false)
            {
                $segments = explode('-',$data);
                if(!$segments[1] || !$segments[0] || !$segments[2])
                    return 0;
                return mktime(0, 0, 0, $segments[1], $segments[0], $segments[2]);
            }
            else
            {
                return mktime(0, 0, 0, $data, 1, $year);
            }
        }
}
