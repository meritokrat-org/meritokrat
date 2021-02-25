<?

load::app('modules/groups/controller');
class groups_delete_photo_comment_action extends groups_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		load::model('groups/photo_comments');

		$this->set_renderer('ajax');
		$this->json = array();

		if ( $comment_id = request::get_int('id') )
		{
			if ( !session::has_credential('moderator') )
			{
				load::model('groups/photos');

				$comment = groups_photo_comments_peer::instance()->get_item($comment_id);
				$photo = groups_photos_peer::instance()->get_item($comment['photo_id']);

				if ( !groups_peer::instance()->is_moderator($photo['group_id'], session::get_user_id()) )
				{
					if ( $comment['user_id'] != session::get_user_id() )
					{
						return;
					}
				}
			}

			groups_photo_comments_peer::instance()->delete_item( request::get_int('id') );
		}
	}
}