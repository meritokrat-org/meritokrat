<?

load::app('modules/blogs/controller');
class groups_post_action extends blogs_controller
{
	public function execute()
	{
		$this->selected_menu = '/groups';
                
		load::model('groups/groups');
		load::model('groups/members');
                

		load::model('blogs/posts');
		if ( !$this->post_data = blogs_posts_peer::instance()->get_item( request::get_int('id') ) )
		{
			$this->redirect('/group'.request::get_int('group_id'));
		}
                
		$this->group = groups_peer::instance()->get_item($this->post_data['group_id']);
		if ( ( $this->group['privacy'] == groups_peer::PRIVACY_PRIVATE ) && !groups_members_peer::instance()->is_member($this->group['id'], session::get_user_id()) && !session::has_credential('admin') )
		{
			if(session::get_user_id()>0)$this->redirect('/group' . $this->group['id']);
		}
                //просмотров=кол-во юзеров открывших пост
                if (!db_key::i()->exists('post_viewed:'.$this->post_data['id'].':'.session::get_user_id()))
		{
			blogs_posts_peer::instance()->update(array('views' => $this->post_data['views'] + 1, 'id' => $this->post_data['id']));
                        db_key::i()->set('post_viewed:'.$this->post_data['id'].':'.session::get_user_id(), true);                           
		}
                if (!session::is_authenticated() && !db_key::i()->exists('post_viewed:'.$this->post_data['id'].':'.ip2long(str_replace('::ffff:', '', $_SERVER['REMOTE_ADDR']))))
                {
                    blogs_posts_peer::instance()->update(array('views' => $this->post_data['views'] + 1, 'id' => $this->post_data['id']));
                    db_key::i()->set('post_viewed:'.$this->post_data['id'].':'.ip2long(str_replace('::ffff:', '', $_SERVER['REMOTE_ADDR'])), true);
                }
/*
                //уникальный просмотр на каждую новую сессию                
		if ( !session::get('post_viewed_' . $this->post_data['id']) )
		{
			blogs_posts_peer::instance()->update(array('views' => $this->post_data['views'] + 1, 'id' => $this->post_data['id']));
			session::set('post_viewed_' . $this->post_data['id'], true);
		}
*/
		client_helper::register_variable('postId', $this->post_data['id']);
		client_helper::set_title(htmlspecialchars(stripslashes($this->post_data['title'])) );

		load::model('blogs/comments');
		$this->comments = blogs_comments_peer::instance()->get_by_post( $this->post_data['id'] );

		load::model('user/user_data');
		load::view_helper('user');

		load::model('blogs/posts_tags');
		$this->similar = blogs_posts_peer::instance()->get_similar($this->post_data['id'], 5);

		load::model('user/blacklist');
		$this->is_blacklisted = user_blacklist_peer::is_banned( $this->post_data['user_id'], session::get_user_id() );
        
                //load::model('bookmarks/bookmarks');
                //$this->post_data['favorite'] = bookmarks_peer::is_bookmarked(session::get_user_id(), 1, request::get_int('id'));

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