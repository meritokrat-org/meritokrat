<?

load::app('modules/ppo/controller');
class ppo_delete_photo_comment_action extends ppo_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		load::model('ppo/photo_comments');

		$this->set_renderer('ajax');
		$this->json = array();

		if ( $comment_id = request::get_int('id') )
		{
			if ( !session::has_credential('moderator') )
			{
				load::model('ppo/photos');

				$comment = ppo_photo_comments_peer::instance()->get_item($comment_id);
				$photo = ppo_photos_peer::instance()->get_item($comment['photo_id']);

				if ( !ppo_peer::instance()->is_moderator($photo['group_id'], session::get_user_id()) )
				{
					if ( $comment['user_id'] != session::get_user_id() )
					{
						return;
					}
				}
			}

			ppo_photo_comments_peer::instance()->delete_item( request::get_int('id') );
		}
	}
}