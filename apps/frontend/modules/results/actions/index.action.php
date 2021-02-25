<?

load::model('user/user_voter');

class results_index_action  extends frontend_controller
{
	protected $authorized_access = false;
	public function execute()
	{

                if ( !$user_id = request::get_int('id') )
                {
                        $user_id = session::get_user_id();
                }
                
                load::model('user/user_payments');
                load::model('user/user_desktop_help');
                load::model('user/user_desktop_received_help');
                load::model('user/user_desktop_active_help');
                load::model('ppo/ppo');
                
                $this->ppo = db::get_rows("SELECT * FROM ppo WHERE category=1 AND active=1");
                $this->mpo = db::get_rows("SELECT * FROM ppo WHERE category=2 AND active=1");
                $this->rpo = db::get_rows("SELECT * FROM ppo WHERE category=3 AND active=1");
                
//                print_r($this->rpo);
//                exit;
                
                $this->ppo_cnt = db::get_scalar("SELECT COUNT(*) FROM ppo",array());
                $this->ppo_members = db::get_scalar("SELECT COUNT(*) FROM ppo_members",array());
                $this->mpu_members = db::get_scalar("SELECT COUNT(*) FROM user_auth WHERE status=20",array());
                $this->act = db::get_scalar("SELECT count(*) FROM user_data JOIN user_auth ON user_data.user_id=user_auth.id AND user_auth.active=true WHERE locationlng>0",array());
                $this->meritokrat_members = db::get_scalar("SELECT COUNT(*) FROM user_auth WHERE active=true",array());
                $this->informed_people = db::get_scalar("SELECT SUM(information_people_social_count)+SUM(information_people_email_count)+SUM(information_people_phone_count)+SUM(information_people_private_count) FROM user_desktop",array());
/*
                $this->active_help = user_desktop_active_help_peer::instance()->get_list(array('user_id'=>$user_id));
                
                
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


 
*/               

          
		load::model('user/user_data');
		$this->user_data = user_data_peer::instance()->get_item( $this->user['id'] );

                load::model('user/user_desktop');
                $this->user_desktop = db::get_row("SELECT * FROM user_desktop WHERE user_id=:user_id", array('user_id'=>$user_id));
                load::model('user/user_desktop_meeting');
                $this->meetings_list = user_desktop_meeting_peer::instance()->get_list(array('user_id'=>$user_id));
                foreach ($this->meetings_list as $meeting)
                {
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
                load::model('user/user_agitmaterials_log');

                $this->agitation = user_agitmaterials_peer::instance()->get_total();
                
                                $data=db::get_rows("SELECT user_id,
                  information_avtonumbers_photos
                  FROM user_desktop
                  WHERE
                  information_avtonumbers_photos!='a:0:{}'");   
           
           foreach($data as $user){
               $naglyadka=unserialize($user['information_avtonumbers_photos']);   
               foreach($naglyadka as $ng){
									switch($ng['type']){
										case 1:
											$this->magnits++;
											break;
										
										case 0:
											$this->autcnt++;
											break;
										
										case 2:
											$this->naglothercnt++;
											break;
									}
               }}
               
               $data=db::get_rows("SELECT user_id,
                  avatarm
                  FROM user_desktop
                  WHERE
                  avatarm!='a:0:{}'");   
           
           foreach($data as $user){
               $avatarm=unserialize($user['avatarm']);  
               foreach($avatarm as $ng){
                   if($ng!='')$this->avatarmcnt++;
               }}
               #print_R($avatarmcnt);
			   
			   
			   $this->covers_count = 0;
			   $this->willVote = 0;
			   $list = user_voter_peer::instance()->get_list();
			   foreach($list as $id)
			   {
				   $item = user_voter_peer::instance()->get_item($id);
				   
				   if($item['admin_data']['willVote'] > 0)
				   {
					   $this->willVote++;
				   }
				   
				   foreach($item['informator'] as $informator)
				   {
					   if($informator['contacts'][count($informator['contacts'])-1]['result'] == 1)
					   {
						   $this->willVote++;
					   }
				   }
					   
				   if($item['tasks_data']['coverInFacebook'] > 0 && $item['tasks_data']['linkCoverInFacebook'] != '')
				   {
					   $this->covers_count++;
				   }
			   }

	}
}
