<?

load::app('modules/ppo/controller');
class ppo_photo_view_action extends ppo_controller
{
	public function execute()
	{
		load::model('ppo/photos');
		if ( !$this->photo = ppo_photos_peer::instance()->get_item( request::get_int('id') ) )
		{
			throw new public_exception('Фото не найдено');
		}

		load::model('ppo/photo_comments');
		$this->comments = ppo_photo_comments_peer::instance()->get_by_photo($this->photo['id']);

		$this->group = ppo_peer::instance()->get_item($this->photo['group_id']);

		if ( ( $this->group['privacy'] == ppo_peer::PRIVACY_PRIVATE ) && !ppo_members_peer::instance()->is_member($this->group['id'], session::get_user_id()) )
		{
			$this->redirect('/group' . $this->group['id']);
		}

		$title = $this->photo['title'];

		if ( $this->photo['album_id'] )
		{
			load::model('ppo/photos_albums');
			$this->album = ppo_photos_albums_peer::instance()->get_item( $this->photo['album_id'] );
			if ( !$title ) $title = $this->album['title'];
		}

		client_helper::set_title( $title . ' | ' . $this->group['title'] );

		$photos = ppo_photos_peer::instance()->get_by_group($this->group['id'], $this->photo['album_id']);
		$this->total = count($photos);

		foreach ( $photos as $i => $id )
		{
			if ( $id == $this->photo['id'] )
			{
				$this->current = $i + 1;

				$this->next_id = $photos[$i+1];
				$this->previous_id = $photos[$i-1];
			}
		}
	}
}
