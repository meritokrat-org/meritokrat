<?
load::app('modules/groups/controller');
class groups_addmembers_action extends groups_controller
{
	protected $authorized_access = true;
	public function execute()
	{
                $this->set_renderer('ajax');
                $this->friends = request::get('fr');
                $this->type = request::get_int('item_type');
                $this->from = session::get_user_id();
                $this->id = request::get_int('item_id');
                $this->message = request::get_string('message');
                if(count($this->friends)>0)
                {
                    foreach ( $this->friends as $friend_id )
                    {
                 
                        if (!groups_members_peer::instance()->is_member($this->id, $friend_id )) {
                                groups_members_peer::instance()->add($this->id, $friend_id, 2 );
                                groups_peer::instance()->update_rate($this->id, 1, $friend_id );
                            }
                            //$options['%link%'] = 'http://'.conf::get('server').'/invites/edit?commit=1&user='.$friend_id.'&id='.$id.'&status=1';
                            //user_email_helper::send_sys($templates[$this->type],$friend_id,session::get_user_id(),$options);

                    }
                }
                die();
	}
}
