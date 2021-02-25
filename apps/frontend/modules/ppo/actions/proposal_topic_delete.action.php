<?

load::app('modules/ppo/controller');
class ppo_proposal_topic_delete_action extends ppo_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		if ( !$this->topic = ppo_proposal_peer::instance()->get_item(request::get_int('id')) )
		{
			exit;
		}

		if ( ( $this->topic['user_id'] != session::get_user_id() ) && !ppo_peer::instance()->is_moderator($this->topic['group_id'], session::get_user_id()) )
		{
			exit;
		}

		ppo_proposal_peer::instance()->delete_item($this->topic['id']);

		$this->redirect('/ppo/proposal?id=' . $this->topic['group_id']);
	}
}