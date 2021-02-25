<?

class friends_blacklist_action extends frontend_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->disable_layout();
		$this->user = user_auth_peer::instance()->get_item(request::get_int('id'));
	}
}