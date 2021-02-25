<?

class profile_leadership_action extends frontend_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->user = user_auth_peer::instance()->get_item(request::get_int('user_id'));
		$this->user_people = user_data_peer::instance()->get_people( $this->user['id'] );
	}
}