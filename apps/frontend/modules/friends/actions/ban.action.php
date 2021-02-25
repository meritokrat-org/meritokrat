<?

class friends_ban_action extends frontend_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->disable_layout();
		$this->user = user_auth_peer::instance()->get_item(request::get_int('id'));

		load::model('user/blacklist');
		user_blacklist_peer::insert( session::get_user_id(), $this->user['id'] );
	}
}