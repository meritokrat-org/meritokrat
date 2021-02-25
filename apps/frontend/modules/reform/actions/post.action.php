<?

load::app('modules/blogs/controller');
class ppo_post_action extends blogs_controller
{
	public function execute()
	{
		$this->selected_menu = '/ppo';
                
		load::model('ppo/ppo');
		load::model('ppo/members');
                

		load::model('blogs/posts');
		if ( !$this->post_data = blogs_posts_peer::instance()->get_item( request::get_int('id') ) )
		{
			$this->redirect('/group'.request::get_int('group_id'));
		}
                
		$this->group = ppo_peer::instance()->get_item($this->post_data['ppo_id']);
		if ( ( $this->group['privacy'] == ppo_peer::PRIVACY_PRIVATE ) && !ppo_members_peer::instance()->is_member($this->group['id'], session::get_user_id()) && !session::has_credential('admin') )
		{
			if(session::get_user_id()>0)$this->redirect('/ppo'.$this->group['id'].'/'.$this->group['number'].'/');
		}
                //просмотров=кол-во юзеров открывших пост
                if (!db_key::i()->exists('post_viewed:'.$this->post_data['id'].':'.session::get_user_id()))
		{
			blogs_posts_peer::instance()->update(array('views' => $this->post_data['views'] + 1, 'id' => $this->post_data['id']));
                        db_key::i()->set('post_viewed:'.$this->post_data['id'].':'.session::get_user_id(), true);                           
		}

		client_helper::register_variable('postId', $this->post_data['id']);
		client_helper::set_title($this->post_data['title'] . ' | ' . conf::get('project_name'));

		load::model('blogs/comments');
		$this->comments = blogs_comments_peer::instance()->get_by_post( $this->post_data['id'] );

		load::model('user/user_data');
		load::view_helper('user');

		load::model('blogs/posts_tags');
		$this->similar = blogs_posts_peer::instance()->get_similar($this->post_data['id'], 5);

		load::model('user/blacklist');
		$this->is_blacklisted = user_blacklist_peer::is_banned( $this->post_data['user_id'], session::get_user_id() );
        
		client_helper::set_meta(array(
			'name' => 'description',
			'content' => $this->post_data['title']
		));
		client_helper::set_meta(array(
			'name' => 'keywords',
			'content' => str_replace(' ', ', ', $this->post_data['title'])
		));
	}
}