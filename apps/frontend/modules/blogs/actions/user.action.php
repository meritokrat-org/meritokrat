<?

load::app('modules/blogs/controller');
class blogs_user_action extends blogs_controller
{
	public function execute()
	{
		$this->selected_menu = '/blogs';

		if ( !request::get_int('user_id') )
		{
			$this->redirect('/blogs');
		}

		load::model('blogs/posts');
		$this->list = blogs_posts_peer::instance()->get_by_users(array($this->user_id = request::get_int('user_id')));

		$this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 15);
		$this->list = $this->pager->get_list();

		$user_data = user_data_peer::instance()->get_item($this->user_id);
		client_helper::set_title( $user_data['first_name'] . ' ' . $user_data['last_name'] . ' | ' . t('Блог') . ' на ' . conf::get('project_name') );
	}
}
