<?

load::app('modules/ppo/controller');
class ppo_position_topic_delete_action extends ppo_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		if ( !$this->topic = ppo_positions_peer::instance()->get_item(request::get_int('id')) )
		{
			exit;
		}

		if ( ( $this->topic['user_id'] != session::get_user_id() ) && !ppo_peer::instance()->is_moderator($this->topic['group_id'], session::get_user_id()) )
		{
			exit;
		}

		ppo_positions_peer::instance()->delete_item($this->topic['id']);

		$this->redirect('/ppo/talk?id=' . $this->topic['group_id']);
	}
}