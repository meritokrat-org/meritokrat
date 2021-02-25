<?

load::app('modules/reform/controller');
class reform_delete_news_action extends reform_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		if ( $news = reform_news_peer::instance()->get_item(request::get_int('id')) )
			if ( reform_peer::instance()->is_moderator($news['group_id'], session::get_user_id()) )
				reform_news_peer::instance()->delete_item(request::get_int('id'));

		$this->set_renderer('ajax');
		$this->json = array();
	}
}