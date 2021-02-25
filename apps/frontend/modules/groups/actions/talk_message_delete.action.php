<?

load::app('modules/groups/controller');
class groups_talk_message_delete_action extends groups_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		if ( !$this->message = groups_topics_messages_peer::instance()->get_item(request::get_int('id')) )
		{
			exit;
		}

		if ( $this->message['user_id'] != session::get_user_id() )
		{
			if ( !$this->topic = groups_topics_peer::instance()->get_item($this->message['topic_id']) )
			{
				exit;
			}
			
			if ( !groups_peer::instance()->is_moderator($this->topic['group_id'], session::get_user_id()) )
			{
				exit;
			}
		}

		groups_topics_messages_peer::instance()->delete_item($this->message['id']);

		$this->set_renderer('ajax');
		$this->json = array();
	}
}