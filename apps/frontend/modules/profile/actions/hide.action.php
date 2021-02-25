<?

class profile_hide_action extends frontend_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->disable_layout();
		$this->set_renderer("ajax");

		$__data = [
			"id" => request::get_int('id'),
			"hide_inviter" => true
		];

		user_auth_peer::instance()->update($__data);

		$this->json["status"] = "deleted";
	}
}