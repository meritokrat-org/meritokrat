<?

class friends_delete_action extends frontend_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		load::model('friends/friends');
		friends_peer::instance()->delete(session::get_user_id(), request::get_int('id'));

		$this->set_renderer('ajax');
		$this->json = array();
	}
}