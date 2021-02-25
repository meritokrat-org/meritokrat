<?

load::app('modules/groups/controller');
class groups_delete_moderator_action extends groups_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->disable_layout();

		$this->group = groups_peer::instance()->get_item(request::get_int('group_id'));
		if ( ( $this->group['user_id'] != session::get_user_id() ) && !session::has_credential('admin') )
		{
			exit;
		}

		$this->moderator_id = request::get_int('id');
		groups_peer::instance()->delete_moderator($this->group['id'], $this->moderator_id);

		$this->set_renderer('ajax');
		$this->json = array();
	}
}
