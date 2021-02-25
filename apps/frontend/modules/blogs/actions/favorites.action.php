<?

load::app('modules/blogs/controller');
class blogs_favorites_action extends blogs_controller
{
	public function execute()
	{
		load::view_helper('tag', true);

		$this->list = blogs_posts_peer::instance()->get_favorites( session::get_user_id() );
		tag_helper::$rss = 'http://' . context::get('host') . '/blogs/rss?type=favorites';

		$this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 8);
		$this->list = $this->pager->get_list();
	}
}
