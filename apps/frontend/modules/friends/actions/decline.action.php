<?

class friends_decline_action extends frontend_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->set_renderer('ajax');
		$this->json = array();

		load::model('friends/pending');
		if ( friends_pending_peer::instance()->is_pending(session::get_user_id(), request::get_int('user_id')) )
		{
			friends_pending_peer::instance()->delete(session::get_user_id(), request::get_int('user_id'));
		}
        $total = friends_pending_peer::instance()->get_by_user(session::get_user_id());
        if(count($total)==0)
        {
            die('1');
        }

	}
}