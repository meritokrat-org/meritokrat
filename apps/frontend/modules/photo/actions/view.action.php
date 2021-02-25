<?
load::app('modules/photo/controller');
class photo_view_action extends photo_controller
{
	public function execute()
	{
		if ( !$this->photo = photo_peer::instance()->get_item( request::get_int('id') ) )
		{
			throw new public_exception('Фото не найдено');
		}

		load::model('photo/photo_comments');
		$this->comments = photo_comments_peer::instance()->get_by_photo($this->photo['id']);

		$title = $this->photo['title'];

		if ( $this->photo['album_id'] )
		{
			$this->album = photo_albums_peer::instance()->get_item( $this->photo['album_id'] );
			if ( !$title ) $title = $this->album['title'];
		}

		client_helper::set_title( $title );

		$photos = photo_peer::instance()->get_album($this->photo['album_id']);
		$this->ptotal = count($photos);

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
