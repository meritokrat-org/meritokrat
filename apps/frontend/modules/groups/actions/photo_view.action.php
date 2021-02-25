<?

load::app('modules/groups/controller');
class groups_photo_view_action extends groups_controller
{
	public function execute()
	{
		load::model('groups/photos');
		if ( !$this->photo = groups_photos_peer::instance()->get_item( request::get_int('id') ) )
		{
			throw new public_exception('Фото не найдено');
		}

		load::model('groups/photo_comments');
		$this->comments = groups_photo_comments_peer::instance()->get_by_photo($this->photo['id']);

		$this->group = groups_peer::instance()->get_item($this->photo['group_id']);

		if ( ( $this->group['privacy'] == groups_peer::PRIVACY_PRIVATE ) && !groups_members_peer::instance()->is_member($this->group['id'], session::get_user_id()) )
		{
			$this->redirect('/group' . $this->group['id']);
		}

		$title = $this->photo['title'];

		if ( $this->photo['album_id'] )
		{
			load::model('groups/photos_albums');
			$this->album = groups_photos_albums_peer::instance()->get_item( $this->photo['album_id'] );
			if ( !$title ) $title = $this->album['title'];
		}

		client_helper::set_title( $title . ' | ' . $this->group['title'] );

		$photos = groups_photos_peer::instance()->get_by_group($this->group['id'], $this->photo['album_id']);
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
