<?

load::app('modules/groups/controller');
class groups_delete_news_action extends groups_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		if ( $news = groups_news_peer::instance()->get_item(request::get_int('id')) )
		{
			if ( groups_peer::instance()->is_moderator($news['group_id'], session::get_user_id()) )
			{
				groups_news_peer::instance()->delete_item(request::get_int('id'));
			}
		}

		$this->set_renderer('ajax');
		$this->json = array();
	}
}