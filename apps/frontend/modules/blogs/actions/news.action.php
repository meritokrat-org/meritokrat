<?

load::app('modules/blogs/controller');
class blogs_news_action extends blogs_controller
{
	public function execute()
	{
		load::view_helper('tag', true);
                load::view_helper('image');
		$this->list = blogs_posts_peer::instance()->get_news_posts();
		tag_helper::$rss = 'http://' . context::get('host') . '/blogs/rss?type=news';

		$this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 8);
		$this->list = $this->pager->get_list();
	}
}
