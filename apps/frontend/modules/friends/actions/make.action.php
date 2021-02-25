<?

class friends_make_action extends frontend_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->disable_layout();
		$this->user = user_auth_peer::instance()->get_item(request::get_int('id'));

		load::model('friends/pending');
		if ( !friends_pending_peer::instance()->is_pending($this->user['id'], session::get_user_id()) )
		{
			if(friends_pending_peer::instance()->check_limit(session::get_user_id()))
                        {
                            friends_pending_peer::instance()->add($this->user['id'], session::get_user_id());

                            $user = user_data_peer::instance()->get_item( session::get_user_id() );

                            load::action_helper('user_email', false);
                            $options = array(
                                    '%link%' => 'http://' . context::get('host') . '/friends?pending=1',
                                    '%settings%'=>'http://'. context::get('host') . '/profile/edit?id='.$this->user['id'].'&tab=settings'
                                );
                            user_email_helper::send_sys('friends_make',$this->user['id'],session::get_user_id(),$options);
                        }
                        else
                        {
                            $this->error = 1;
                        }
		}
	}
}