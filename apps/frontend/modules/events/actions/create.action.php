<?

load::app('modules/events/controller');
class events_create_action extends events_controller
{
	protected $authorized_access = true;
        
	public function execute()
	{
	        $this->user_data = user_data_peer::instance()->get_item(session::get_user_id());
		$this->user_auth = user_auth_peer::instance()->get_item(session::get_user_id());

		if ( request::get('submit') )
		{ 
                        $name = trim(strip_tags(request::get('name')));
                        $description = trim(request::get('description'));
                        $adress = trim(strip_tags(request::get('adress')));
                        $notes = trim(strip_tags(request::get('notes')));
                        $start=strtotime(str_replace('/', '-', request::get('start')));
                        $end=strtotime(str_replace('/', '-', request::get('end')));
                        $content_id=intval(request::get_int('content_id'));
                        $hidden=intval(request::get_int('hidden'));
                        load::model('groups/groups');
                        load::model('user/user_desktop');
                       /* switch (request::get_int('type'))
                        {
                            case 1:
                                if (session::has_credential('admin') || groups_peer::instance()->is_moderator($content_id, session::get_user_id()))
                                    $type = 1;
                                break;

                            case 2;
                                # TODO когда появится лидеская группа сделать проверку 
                                break;

                            case 3:
                                if (session::has_credential('admin')  
                                        OR groups_peer::instance()->is_moderator($this->event['content_id'], session::get_user_id())
                                        OR (user_desktop_peer::instance()->is_regional_coordinator(session::get_user_id()) 
                                                OR user_desktop_peer::instance()->is_raion_coordinator(session::get_user_id()))
                                        )
                                    $type = 3;
                                break;
                            default:
                                $type = 0;
                        }*/
                        load::model('ppo/ppo');
                        load::model('ppo/members');
                        if(request::get_int('type')==1 && (session::has_credential('admin') 
                                || groups_peer::instance()->is_moderator($content_id, session::get_user_id())))
			$type = 1;
                        elseif(request::get_int('type')==4 && (session::has_credential('admin') 
                                || ppo_peer::instance()->is_moderator($content_id, session::get_user_id())))
			$type = 4;
                        else{
                            if (session::get_user_id()==31)$type = 3;
                            else $type = 0;
                        }
                        
                        if ($name && $description && $start && $end && $adress)
			{
														$format = array(
																"campaign" => request::get_bool("campaign"),
																"propaganda" => request::get_bool("propaganda"),
																"other" => request::get_bool("other"),
																"other_text" => request::get("other_text"),
														);
                            $id = events_peer::instance()->insert(array('name' => $name,
                                "cat"=>request::get_int('cat'),
                                "section"=>request::get_int('section'),
                                "confirm"=>request::get_int('confirm'),
                                "region_id"=>request::get_int('regionc'),
                                "city_id"=>request::get_int('city'),
                                "adress"=>$adress,
                                "level"=>request::get_int('level'),
                                "catname"=>trim(strip_tags(request::get('catname'))),
                                "site"=>trim(strip_tags(request::get('site'))),
                                "price"=>request::get_int('price'),
                                "notes"=>$notes,                              
                                "description"=>$description,
                                'user_id' => session::get_user_id(),
                                "start"=>  $start,
                                "end"=> $end,
                                "type"=> $type,
                                "content_id"=> $content_id,
                                "hidden"=>$hidden,
																"format"=> serialize($format)));
                            #events_members_peer::instance()->add($id, session::get_user_id());
                            #events_members_peer::instance()->set_status( $id, session::get_user_id(), 1 );
                            load::action_helper('user_email', false);

                            if(request::get_int('section')==9)
                            {
                                $ppo_info = ppo_peer::instance()->get_item($content_id);
                                $options = array(
                                    '%ppo%' => '<a href="http://' . context::get('host') . '/ppo' . $content_id . '/">'.$ppo_info['title'].'</a>',
                                    '%event%' => '<a href="http://' . context::get('host') . '/event' . $id . '">'.$name.'</a>',
                                    '%profile%' => user_helper::full_name(session::get_user_id(), true, array(), false)
                                );
                                foreach ( user_auth_peer::get_admins() as $admin )
                                {
                                    $options['%settings%'] = 'http://'. context::get('host') . '/profile/edit?id='.$admin.'&tab=settings';
                                    user_email_helper::send_sys('event_agitation',$admin,false,$options);
                                }
                                unset($options);
                            }

                            if($type==1)
                            {
                                $options = array(
                                    '%title%' => $name,
                                    '%event%' => '<a href="http://' . context::get('host') . '/event' . $id . '">'.$name.'</a>',
                                    '%username%' => user_helper::full_name(session::get_user_id(), true, array(), false)
                                );
                                load::model('groups/members');
                                $members = groups_members_peer::instance()->get_members($content_id);
                                if(is_array($members) && count($members)>0)
                                {
                                    foreach($members as $member)
                                    {
                                        user_email_helper::send_sys('grop_event_invite',$member,session::get_user_id(),$options);
                                    }
                                }
                            }
			}
                        $this->set_renderer('ajax');
			$this->json = array('id'=>$id);
		}
	}
}
