<?

class blogs_vote_action extends frontend_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		load::model('blogs/posts');
		if ( $post_data = blogs_posts_peer::instance()->get_item( request::get_int('id') ) )
		{
			if ( !blogs_posts_peer::instance()->has_rated($post_data['id'], session::get_user_id()) )
			{
				blogs_posts_peer::instance()->update( array(
					'id' => $post_data['id'],
					'for' => $post_data['for'] + ( request::get_int('positive') ? 1 : 0 ),
					'against' => $post_data['against'] + ( request::get_int('positive') ? 0 : 1 )
				) );

				user_data_peer::instance()->update_rate($post_data['user_id'], request::get_int('positive') ? 1 : -1, session::get_user_id());
				blogs_posts_peer::instance()->rate($post_data['id'], session::get_user_id());

				load::model('rate_history');
				rate_history_peer::instance()->insert(array(
					'type' => rate_history_peer::TYPE_BLOG_POST,
					'object_id' => $post_data['id'],
					'user_id' => session::get_user_id(),
					'rate' => request::get_int('positive') ? '+1' : '-1'
				));
			}
		}

		$this->set_renderer('ajax');
		$this->json = array();
	}
}