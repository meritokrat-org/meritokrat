<?

load::app('modules/groups/controller');
class groups_talk_topic_delete_action extends groups_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		if ( !$this->topic = groups_topics_peer::instance()->get_item(request::get_int('id')) )
		{
			exit;
		}

		if ( ( $this->topic['user_id'] != session::get_user_id() ) && !groups_peer::instance()->is_moderator($this->topic['group_id'], session::get_user_id()) )
		{
			exit;
		}

		groups_topics_peer::instance()->delete_item($this->topic['id']);

		$this->redirect('/groups/talk?id=' . $this->topic['group_id']);
	}
}