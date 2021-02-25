<?

load::app('modules/ppo/controller');
class ppo_change_owner_action extends ppo_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->set_renderer('ajax');
		$this->json = array();

		$this->group = ppo_peer::instance()->get_item(request::get_int('group_id'));
		if ( ( $this->group['user_id'] != session::get_user_id() ) && !session::has_credential('admin') )
		{
			exit;
		}

		if ( $this->moderator_id = request::get_int('id') )
		{
			ppo_peer::instance()->update(array(
				'id' => $this->group['id'],
				'user_id' => $this->moderator_id
			));
                        ppo_peer::instance()->delete_moderator($this->group['id'], $this->moderator_id);
		}
	}
}
