<?
load::app('modules/blogs/controller');
class blogs_not_viewed_action extends blogs_controller
{
        protected $authorized_access = true;
	protected $credentials = array('redcollegiant');
	public function execute()
	{
		load::view_helper('tag', true);

		$this->list = blogs_posts_peer::instance()->get_not_viewed();

		$this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 12);
		$this->list = $this->pager->get_list();
	}
}
