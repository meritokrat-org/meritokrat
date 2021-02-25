<?

load::app('modules/ppo/controller');
class ppo_position_message_delete_action extends ppo_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		if ( !$this->message = ppo_positions_messages_peer::instance()->get_item(request::get_int('id')) )
		{
			exit;
		}

		if ( $this->message['user_id'] != session::get_user_id() )
		{
			if ( !$this->topic = ppo_positions_peer::instance()->get_item($this->message['topic_id']) )
			{
				exit;
			}
			
			if ( !ppo_peer::instance()->is_moderator($this->topic['group_id'], session::get_user_id()) )
			{
				exit;
			}
		}

		ppo_positions_messages_peer::instance()->delete_item($this->message['id']);

		$this->set_renderer('ajax');
		$this->json = array();
	}
}