<?

class profile_delete_action extends frontend_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->hash = md5(microtime());
		session::set('delete_hash', $this->hash);
		session::set('delete_id', request::get_int('id'));
		$this->type = request::get_int('type');
		$this->disable_layout();
	}
}