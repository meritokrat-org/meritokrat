<?

load::app('modules/blogs/controller');
class blogs_declarations_action extends blogs_controller
{
	public function execute()
	{
		client_helper::set_title( t('Объявления') . ' | ' . conf::get('project_name') );
                
		load::view_helper('tag', true);

		$this->list = blogs_posts_peer::instance()->get_declarations_posts();

                $this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 8);
		$this->list = $this->pager->get_list();
	}
}
