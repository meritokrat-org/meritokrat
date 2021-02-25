<?

load::app('modules/events/controller');
class events_confirm_action extends events_controller
{
	protected $authorized_access = true;

	public function execute()
	{
                $this->set_renderer('ajax');
                if(request::get_int('multiple')) $event = events_peer::instance()->get_item(request::get_int('item_id'));
                else $event = events_peer::instance()->get_item(request::get_int('event_id'));
                if(!request::get_int('confirm') || request::get_int('confirm')==1)
                    $confirm = 1;
                else
                    $confirm = 2;
                if($event['id'])
                {
                    if(!request::get_int('multiple'))
                    {
                        $this->saveuser(request::get_int('user_id'),$confirm,$event);
                    }
                    else
                    {
                        $users = request::get('fr');
                        if(is_array($users) && count($users)>0)
                        {
                            foreach($users as $user_id)
                            {
                                $this->saveuser($user_id,$confirm,$event);
                            }
                        }
                    }
                }

	}

        private function saveuser($user_id=0,$confirm=1,$event=array())
        {
                if(!events_members_peer::instance()->is_member($event['id'], $user_id))
                {
                    events_members_peer::instance()->add($event['id'], $user_id);
                }
                
                db::exec("UPDATE events2users SET confirm = :confirm WHERE event_id=:event_id AND user_id=:user_id",array(
                    'event_id' => $event['id'],
                    'confirm' => $confirm,
                    'user_id' => $user_id
                    ));
                load::model('user/user_desktop_meeting');

                $query = db::get_cols('SELECT id FROM user_desktop_meeting WHERE event_id=:event_id AND user_id=:user_id LIMIT 1',array(
                    'event_id' => $event['id'],
                    'user_id' => $user_id
                    ));

                if(!$query[0])
                {
                    user_desktop_meeting_peer::instance()->insert(array(
                        'user_id' => $user_id,
                        'title' => $event['name'],
                        'description' => $event['description'],
                        'meeting_date' => date("d/m/Y",$event['start']),
                        'part' => 1,
                        'confirm' => $confirm,
                        'event_id' => $event['id']
                        ));
                }
                else
                {
                    user_desktop_meeting_peer::instance()->update(array(
                        'id' => $query[0],
                        'confirm' => $confirm
                        ));
                }
        }
}