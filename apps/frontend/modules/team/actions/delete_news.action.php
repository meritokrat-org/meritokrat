<?

load::app('modules/ppo/controller');
class ppo_delete_news_action extends ppo_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		if ( $news = ppo_news_peer::instance()->get_item(request::get_int('id')) )
		{
			if ( ppo_peer::instance()->is_moderator($news['group_id'], session::get_user_id()) )
			{
				ppo_news_peer::instance()->delete_item(request::get_int('id'));
			}
		}

		$this->set_renderer('ajax');
		$this->json = array();
	}
}