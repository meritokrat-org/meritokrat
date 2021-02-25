<?

load::app('modules/polls/controller');
class polls_delete_comment_action extends polls_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->set_renderer('ajax');
		$this->json = array();

		if ( $comment_id = request::get_int('id') )
		{
			load::model('polls/comments');
                        $comment = polls_comments_peer::instance()->get_item($comment_id);

                        if ( !session::has_credential('moderator') )
			{
				if ( $comment['user_id'] != session::get_user_id() )
				{
					return;
				}
			}

			polls_comments_peer::instance()->delete_item( request::get_int('id') );

                        if ( session::has_credential('moderator') )
			{
				load::model('admin_feed');
				/*$text = htmlspecialchars($comment['text']) . '<br /><br /> Автор: ' .
						user_helper::full_name($comment['user_id']) . '<br /><br />' .
						'<a href="/poll' . $comment['poll_id'] . '">Перейти к опросу' . '</a>';*/

				admin_feed_peer::instance()->add(session::get_user_id(), admin_feed_peer::TYPE_POLL_COMMENT, $text, $comment, request::get_string('why'));
			}
		}
	}
}