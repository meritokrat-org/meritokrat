<?

load::app('modules/blogs/controller');
class groups_comments_action extends blogs_controller
{
	public function execute()
	{

		feed_peer::reset_user_flag(session::get_user_id());
                
		load::view_helper('tag', true);

		tag_helper::$rss = 'http://' . context::get('host') . '/blogs/rss?type=comments';
                
                $sql="SELECT id FROM blogs_comments WHERE post_id in (SELECT id FROM blogs_posts WHERE type=0) ORDER by id DESC";
		$this->list = db::get_cols($sql);

		$this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 10);
		$this->list = $this->pager->get_list();
	}
}
