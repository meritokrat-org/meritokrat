<?

load::app('modules/events/controller');
load::model('groups/groups');
class events_edit_action extends events_controller
{
	protected $authorized_access = true;

	public function execute()
	{

            if ( request::get_int('status') )
            {
                if(!events_members_peer::instance()->get_item(request::get_int('id'),session::get_user_id()))
                        events_members_peer::instance()->add(request::get_int('id'), session::get_user_id());
                        db::exec("UPDATE events2users SET leads=:leads, status=:status
                                WHERE event_id=:event_id AND user_id=:user_id",
                                array(
                                    'event_id' => request::get_int('id'),
                                    'status' => request::get_int('status'),
                                    'leads' => request::get_int('leads'),
                                    'user_id' => session::get_user_id()
                                    ));
                        db::exec("UPDATE invites SET status=:status
                                WHERE obj_id=:event_id AND to_id=:user_id AND type=:type",
                                array(
                                    'event_id' => request::get_int('id'),
                                    'status'=> request::get_int('status'),
                                    'type' => 1,
                                    'user_id' => session::get_user_id()
                                    ));
                $this->set_renderer('ajax');
                $this->json=array();
                return;
            }
            if ( request::get_file('file') )
            {
                $photo = request::get_file('file');
                load::system('storage/storage_simple');
                $storage = new storage_simple();
                $salt = substr(md5($photo['name']),0,10);
                $key = 'events/' . $salt . '.jpg';
                $storage->save_uploaded($key, request::get_file('file'));
                events_peer::instance()->update(array('id' => request::get_int('id'),'photo' =>  $photo['name']));
            }
            $this->event = events_peer::instance()->get_item(request::get_int('id'));
						$this->event["format"] = unserialize($this->event["format"]);

            if ( request::get_file('file') ) return;
            if(!$this->event['id'])die();

            load::model('user/user_desktop');
            if(user_desktop_peer::instance()->is_regional_coordinator(session::get_user_id()) OR user_desktop_peer::instance()->is_raion_coordinator(session::get_user_id()))
                $coordinator = 1;

            $allow = 0;
            /*switch ($this->event['type'])
            {
                case 1:
                    
                case 3:
                 //if (session::has_credential('admin') 
                      //      OR groups_peer::instance()->is_moderator($this->event['content_id'], session::get_user_id())
                     //       OR session::has_credential('designer')
                      //      OR $coordinator
                       //     )
                    if (session::get_user_id()==31)
                        $allow = 1;
                    break;

                case 2;
                   # TODO когда появится лидеская группа сделать проверку 
                    break;
                case 0:
                    if (session::has_credential('admin') 
                            OR $this->event['user_id'] == session::get_user_id()
                            OR session::has_credential('designer')
                            OR $coordinator
                            )
                        $allow = 1;
            }*/
        if ($this->event['type'] == 1 && (session::has_credential('admin') || groups_peer::instance()->is_moderator($this->event['content_id'], session::get_user_id())))
            $allow = 1;
        
        if ($this->event['type'] == 3 && session::get_user_id() == 31)
             $allow = 1;
          
        if (session::has_credential('admin') || $this->event['user_id'] == session::get_user_id())
             $allow = 1;
        
        if ( $allow ==0 )
        {
                    $this->redirect('/');
        }
        
            if ( request::get('submit') )
            {

                    $this->set_renderer('ajax');
                    $this->json = array('id'=>$this->event['id']);
                    $name = trim(strip_tags(request::get('name')));
                    $description = trim(request::get('description'));
                    $notes = trim(strip_tags(request::get('notes')));
                    $adress = trim(strip_tags(request::get('adress')));
                    $start=strtotime(str_replace('/', '-', request::get('start')));
                    $end=strtotime(str_replace('/', '-', request::get('end')));
                    $cat=request::get_int('cat');
                    $hidden=request::get_int('hidden');
                    if($cat!=2)$catname="";
                    else $catname=request::get_string('catname');
                    if ($name && $description && $start && $end)
                    {
                        /*$data = events_peer::instance()->get_item(request::get_int('id'));
                        if($data['start']!=$start && $data['end']==$end)
                        {
                            $range = $data['end'] - $data['start'];
                            $end = $start + $range;
                        }*/
												$format = array(
																"campaign" => request::get_bool("campaign"),
																"propaganda" => request::get_bool("propaganda"),
																"other" => request::get_bool("other"),
																"other_text" => request::get("other_text"),
														);
                        events_peer::instance()->update(array('id' => request::get_int('id'),
                            'name' => $name,
                            "cat"=>$cat,
                            "section"=>request::get_int('section'),
                            "confirm"=>request::get_int('confirm'),
                            "region_id"=>request::get_int('regionc'),
                            "city_id"=>request::get_int('city'),
                            "adress"=>$adress,
                            "level"=>request::get_int('level'),
                            "catname"=>trim(strip_tags(request::get('catname'))),
                            "site"=>trim(strip_tags(request::get('site'))),
                            "notes"=>$notes,
                            "price"=>request::get_int('price'),
                            "description"=>$description,
                            "start"=>  $start,
                            "end"=> $end,
                            "hidden"=>$hidden,
														"format"=> serialize($format)));
                    }
            }


	}
}