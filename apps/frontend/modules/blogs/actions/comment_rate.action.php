<?

class blogs_comment_rate_action extends frontend_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		load::model('blogs/comments');
		if ( $comment = blogs_comments_peer::instance()->get_item( request::get_int('id') ) )
		{
			if ( !blogs_comments_peer::instance()->has_rated($comment['id'], session::get_user_id()) )
			{
				blogs_comments_peer::instance()->update( array(
					'id' => $comment['id'],
					'rate' => $comment['rate'] + ( request::get_int('positive') ? 1 : -1 )
				) );

				user_data_peer::instance()->update_rate($comment['user_id'], request::get_int('positive') ? 0.1 : -0.1, session::get_user_id());

				blogs_comments_peer::instance()->rate($comment['id'], session::get_user_id());
			}
		}

		$this->set_renderer('ajax');
		$this->json = array();
	}
}