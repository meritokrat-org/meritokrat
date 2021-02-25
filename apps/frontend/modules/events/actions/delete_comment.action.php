<?

load::app('modules/events/controller');
load::model('events/comments');
class events_delete_comment_action extends events_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->set_renderer('ajax');
		$this->json = array();

		if ( $comment_id = request::get_int('id') )
		{
			$comment = events_comments_peer::instance()->get_item($comment_id);

			if ( !session::has_credential('moderator') )
			{
				$event = events_posts_peer::instance()->get_item($comment['event_id']);

				if ( session::has_credential('selfmoderator') )
				{
						if ( $event['user_id'] != session::get_user_id() ) return;
				}
				else if ( $comment['user_id'] != session::get_user_id() )
				{
						return;
				}
			}

			events_comments_peer::instance()->delete_item( request::get_int('id') );

                        if ( session::has_credential('moderator') )
			{
				load::model('admin_feed');
				/*$text = htmlspecialchars($comment['text']) . '<br /><br /> Автор: ' .
						user_helper::full_name($comment['user_id']) . '<br /><br />' .
						'<a href="/event' . $comment['event_id'] . '">Перейти к событию' . '</a>';*/

				admin_feed_peer::instance()->add(session::get_user_id(), admin_feed_peer::TYPE_EVENT_COMMENT, $text, $comment, request::get_string('why'));
			}
		}
	}
}