<?

load::app('modules/ppo/controller');
class ppo_talk_message_action extends ppo_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		if ( !$this->topic = ppo_topics_peer::instance()->get_item(request::get_int('id')) )
		{
			exit;
		}

		$this->group = ppo_peer::instance()->get_item($this->topic['group_id']);
		if ( ( $this->group['privacy'] == ppo_peer::PRIVACY_PRIVATE ) && !ppo_members_peer::instance()->is_member($this->group['id'], session::get_user_id()) )
		{
			$this->redirect('/group' . $this->group['id']);
		}

		if ( $text = trim(request::get('text')) )
		{
			ppo_topics_messages_peer::instance()->insert(array(
				'topic_id' => $this->topic['id'],
				'user_id' => session::get_user_id(),
				'created_ts' => time(),
				'text' => $text
			));

			ppo_topics_peer::instance()->update(array(
				'id' => $this->topic['id'],
				'messages_count' => $this->topic['messages_count'] + 1,
				'last_user_id' => session::get_user_id(),
				'updated_ts' => time()
			));
		}

		$this->set_renderer('ajax');
		$this->json = array('id' => $this->topic['id']);
	}
}