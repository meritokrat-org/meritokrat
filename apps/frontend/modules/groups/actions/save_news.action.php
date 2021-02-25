<?

load::app('modules/groups/controller');
class groups_save_news_action extends groups_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		if ( $news = groups_news_peer::instance()->get_item(request::get_int('news_id')) )
		{
			if ( groups_peer::instance()->is_moderator($news['group_id'], session::get_user_id()) )
			{
				groups_news_peer::instance()->update(array(
					'id' => $news['id'],
					'text' => trim(request::get('text'))
				));
			}
		}

		$this->set_renderer('ajax');
		$this->json = array();
	}
}