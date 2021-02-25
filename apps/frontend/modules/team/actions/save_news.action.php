<?

load::app('modules/ppo/controller');
class ppo_save_news_action extends ppo_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		if ( $news = ppo_news_peer::instance()->get_item(request::get_int('news_id')) )
		{
			if ( ppo_peer::instance()->is_moderator($news['group_id'], session::get_user_id()) )
			{
				ppo_news_peer::instance()->update(array(
					'id' => $news['id'],
					'text' => trim(request::get('text'))
				));
			}
		}

		$this->set_renderer('ajax');
		$this->json = array();
	}
}