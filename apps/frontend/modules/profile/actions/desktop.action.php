<?

load::model("user/user_voter");

class profile_desktop_action  extends frontend_controller
{
	protected $authorized_access = true;
	public function execute()
	{

        if ( !$user_id = request::get_int('id') )
        {
			$this->redirect("/profile/desktop?id=".session::get_user_id());
			exit();
        }

        load::model('user/user_desktop_help');
        load::model('user/user_desktop_received_help');
		load::model('user/user_desktop_active_help');

		$this->sortable_list = user_auth_peer::instance()->get_sortable_list();

				$cond = array("user_id" => $user_id);
				$list = user_voter_peer::instance()->get_list($cond);
				$this->user_voter = array();
				if(count($list) > 0)
					$this->user_voter = user_voter_peer::instance()->get_item($list[0]);
				
                
                $this->user_auth = user_auth_peer::instance()->get_item($user_id);

                $this->active_help = user_desktop_active_help_peer::instance()->get_list(array('user_id'=>$user_id));

                load::model('user/membership');
                $this->membership = user_membership_peer::instance()->get_user($user_id);
                
                $user_send_help_list = user_desktop_received_help_peer::instance()->get_list(array('userfrom'=>$user_id,'approved'=>'1'));
                if(!empty($user_send_help_list))
                    $this->send_help_list = $user_send_help_list;

                $user_rec_help_list = user_desktop_received_help_peer::instance()->get_list(array('userto'=>$user_id,'approved'=>'0'));
                if(!empty($user_rec_help_list))
                    $this->rec_help_list = $user_rec_help_list;


                $need_help_list = user_desktop_help_peer::instance()->get_list(array('need'=>'1','user_id'=>$user_id));
                $need_help_type_ids = array();
                $user_help_types = user_desktop_help_peer::getHelpTypes ();
                $this->user_help_types = $user_help_types;


                load::model('user/zayava');
                $this->zayava = user_zayava_peer::instance()->check_user($user_id);
                

                if(!empty($need_help_list)) {
                    foreach($need_help_list as $need_id=>$need_item) {
                        $tmp = user_desktop_help_peer::instance()->get_item($need_item);
                        $need_help_type_ids[] = $tmp['type'];
                    }

                    $need_help_type_ids = array_unique($need_help_type_ids);
                    $this->user_need_help = array();

                    foreach ($need_help_type_ids as $need_help_id=>$need_help_item)
                        $this->user_need_help[$need_help_item] = $user_help_types[$need_help_item];
                }
                
                
                $provide_help_list = user_desktop_help_peer::instance()->get_list(array('need'=>'0','user_id'=>$user_id));
                $privide_help_type_ids = array();

                if(!empty($provide_help_list)) {
                    foreach($provide_help_list as $provide_id=>$provide_item) {
                        $tmp = user_desktop_help_peer::instance()->get_item($provide_item);
                        $privide_help_type_ids[] = $tmp['type'];
                    }

                    $privide_help_type_ids = array_unique($privide_help_type_ids);
                    $this->user_provide_help = array();
                    
                    foreach ($privide_help_type_ids as $prov_help_id=>$prov_help_item)
                        $this->user_provide_help[$prov_help_item] = $user_help_types[$prov_help_item];

                }

//                echo "<pre>";
//                print_r($this->user_provide_help);
//                print_r($this->user_need_help);
//                exit;

                

		$this->user = user_auth_peer::instance()->get_item($user_id);
		if ( !$this->user || (!$this->user['active'] and !session::has_credential('admin')))
		{
			throw new public_exception( t('Пользователь не найден') );
		}
		load::model('user/user_data');
		$this->user_data = user_data_peer::instance()->get_item( $this->user['id'] );

                
                if(user_auth_peer::instance()->get_rights($this->user['id'], 1)) // user_auth_peer::instance()->get_rights($this->user['id'], 10) || $this->user['desktop']==1
                {
                    load::model('user/user_desktop');
                    $this->user_desktop = db::get_row("SELECT * FROM user_desktop WHERE user_id=:user_id", array('user_id'=>$user_id));
                    
                    $this->publications = unserialize($this->user_desktop['information_publications']);
//                    echo "<pre>";
//                    print_r($this->publications);
//                    exit;
                    
                    load::model('user/user_desktop_meeting');
                    $this->meetings_list = user_desktop_meeting_peer::instance()->get_list(array('user_id'=>$user_id));
                    foreach ($this->meetings_list as $meeting) {
                        $meeting_data = user_desktop_meeting_peer::instance()->get_item($meeting);
                        $this->user_desktop_meeting[]=$meeting_data;
                        if($meeting_data['confirm']==1 && $meeting_data['part']==1)
                        {
                            $this->user_desktop_meeting_confirm[] = $meeting_data;
                        }
                        elseif($meeting_data['confirm']==2 && $meeting_data['part']==1)
                        {
                            $this->user_desktop_meeting_decline[] = $meeting_data;
                        }
                    }

                    if(count(user_desktop_peer::instance()->is_regional_coordinator($this->user['id'],true))>0
                            OR
                            count(user_desktop_peer::instance()->is_raion_coordinator($this->user['id'],true))>0
                            OR 
                            session::has_credential('admin'))
                    {
                         $this->user_contacts = user_desktop_peer::instance()->get_contacts($this->user['id']);
                         $this->coordinator = true;
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
                    load::view_helper('user');       

                    load::model('user/user_desktop_signature');

                    $this->signature_list = user_desktop_signature_peer::instance()->get_list(array('user_id'=>$user_id));
                    foreach ($this->signature_list as $signature) {
                        $this->user_desktop_signature[]=user_desktop_signature_peer::instance()->get_item($signature);
                    }
                    
                    load::model('user/user_desktop_signature_fact');

                    $this->signature_fact_list = user_desktop_signature_fact_peer::instance()->get_list(array('user_id'=>$user_id));
                    foreach ($this->signature_fact_list as $signature) {
                        $this->user_desktop_signature_fact[]=user_desktop_signature_fact_peer::instance()->get_item($signature);
                    }

                    load::model('user/user_agitmaterials');
                    $this->agitation = user_agitmaterials_peer::instance()->get_user_stat($user_id);

                    if(session::get_user_id()==$user_id || user_auth_peer::instance()->get_rights(session::get_user_id(), $this->user_desktop['confidence']))
                    {
                        $this->has_confidence = 1;

                        load::model('user/user_payments');
                        load::model('user/user_payments_log');
                        load::model('ppo/members');

                        $region_coordinator = user_desktop_peer::instance()->is_regional_coordinator(session::get_user_id(),true);
                        $raion_coordinator = user_desktop_peer::instance()->is_raion_coordinator(session::get_user_id(),true);

                        if(
                            (count($region_coordinator)>0 && in_array($this->user_data['region_id'],$region_coordinator))
                            OR
                            (count($raion_coordinator)>0 && in_array($this->user_data['city_id'],$raion_coordinator))
                            OR
                            (ppo_members_peer::instance()->is_ppoleader(session::get_user_id()))
                        )
                        {
                            $this->has_access = 1;
                        }

                        if($this->has_access OR session::get_user_id()==$user_id OR session::has_credential('admin'))
                            $this->payments = user_payments_peer::instance()->get_user( $user_id );
                        else
                            $this->payments = user_payments_peer::instance()->get_user( $user_id,false,2 );  
                    }
                    if(session::has_credential('admin')){
                        load::model('ban/ban');
                        $this->bans=ban_peer::instance()->get_ban_user($this->user['id']);
                    }

                    if(session::has_credential('admin'))
                    {
                        load::model('ppo/ppo');
                        load::model('ppo/members');
                        load::model('ppo/members_history');

                        $this->ppomember = ppo_members_peer::instance()->get_ppo($user_id);
                        $this->ppohistory = ppo_members_history_peer::instance()->get_member_history($user_id);

                        load::model('user/user_status_log');
                        $this->statuslog = user_status_log_peer::instance()->get_last($user_id,20);
                    }
                }
                else    throw new public_exception( t('Рабочий стол недоступен для пользователей ниже Меритократа') );

	}
}
