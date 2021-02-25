<?

class profile_leader_action extends frontend_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->disable_layout();
		$this->user = user_auth_peer::instance()->get_item(request::get_int('id'));

		user_data_peer::instance()->update(
			array('owner_id' => $this->user['id']),
			array('user_id' => session::get_user_id())
		);
	}
}