<?

abstract  class blogs_controller extends frontend_controller
{
	#protected $authorized_access = true;
	public function init()
	{
		parent::init();

		load::model('blogs/posts');
		load::model('groups/groups');
		load::model('blogs/tags');
		load::model('blogs/posts_tags');
		load::model('blogs/comments');

		load::action_helper('pager', true);

		client_helper::set_title( t('Публикации') . ' | ' . conf::get('project_name') );
		
		$rand = db::get_cols("SELECT user_id FROM user_data WHERE photo_salt IS NOT NULL");
		$rand_count = 5;
		$offset = rand(0,count($rand)-$rand_count);

		$this->random_users = array_slice($rand, $offset, $rand_count);
	}

	public function post_action()
	{
		parent::post_action();

		$this->selected_menu = '/blogs';

		$newest = blogs_posts_peer::instance()->get_newest();
		$this->newestpager = pager_helper::get_pager($newest, request::get_int('page'), 9);
		$this->newest = $this->newestpager->get_list();
                
		if ( $this->top_tags = blogs_tags_peer::instance()->get_top() )
		{
			foreach ( $this->top_tags as $key=>$tag )
			{
                                $blog_items = blogs_posts_peer::instance()->get_by_tag($tag['id']);
                                if(!empty($blog_items)) $meta_keywords[] = blogs_tags_peer::instance()->get_name($tag['id']);
                                else unset($this->top_tags[$key]);
			}
		}
		load::model('user/user_data');
		load::view_helper('user');

		client_helper::set_meta(array(
			'name' => 'description',
			'content' => t('Публикации')
		));

		if ( $meta_keywords )
		{
			client_helper::set_meta(array(
				'name' => 'keywords',
				'content' => implode(', ', $meta_keywords)
			));
		}
	}
}
