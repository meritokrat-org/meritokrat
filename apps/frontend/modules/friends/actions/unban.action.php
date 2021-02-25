<?

class friends_unban_action extends frontend_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->set_renderer('ajax');
		$this->json = array();
		
		$this->user = user_auth_peer::instance()->get_item(request::get_int('id'));

		load::model('user/blacklist');
		user_blacklist_peer::delete( session::get_user_id(), $this->user['id'] );
	}
}