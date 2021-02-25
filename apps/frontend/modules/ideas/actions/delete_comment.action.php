<?

load::app('modules/ideas/controller');
class ideas_delete_comment_action extends ideas_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->set_renderer('ajax');
		$this->json = array();

		if ( $comment_id = request::get_int('id') )
		{
                        $comment = ideas_comments_peer::instance()->get_item($comment_id);

			if ( !session::has_credential('moderator') )
			{
				$post = ideas_peer::instance()->get_item($comment['idea_id']);

				if ( session::has_credential('selfmoderator') )
				{
                                        if ( $post['user_id'] != session::get_user_id() ) return;
				}
				else if ( $comment['user_id'] != session::get_user_id() )
				{
                                        return;
				}
			}

			ideas_comments_peer::instance()->delete_item( request::get_int('id') );

                        if ( session::has_credential('moderator') )
			{
				load::model('admin_feed');
				/*$text = htmlspecialchars($comment['text']) . '<br /><br /> Автор: ' .
						user_helper::full_name($comment['user_id']) . '<br /><br />' .
						'<a href="/idea' . $comment['idea_id'] . '">Перейти к идее' . '</a>';*/

				admin_feed_peer::instance()->add(session::get_user_id(), admin_feed_peer::TYPE_IDEA_COMMENT, $text, $comment, request::get_string('why'));
			}
		}
	}
}