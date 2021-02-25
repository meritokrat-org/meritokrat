<?

load::app('modules/groups/controller');
class groups_photo_delete_action extends groups_controller
{
	public function execute()
	{
		load::model('groups/photos');
		if ( !$this->photo = groups_photos_peer::instance()->get_item( request::get_int('id') ) )
		{
			throw new public_exception('Фото не найдено');
		}

		$this->group = groups_peer::instance()->get_item($this->photo['group_id']);

		if ( ($this->photo['user_id'] == session::get_user_id()) || groups_peer::instance()->is_moderator($this->photo['group_id'], session::get_user_id())  )
		{
			groups_photos_peer::instance()->delete_item($this->photo['id']);
		}

		$this->redirect('/groups/photo?id=' . $this->group['id'] . '&album_id=' . $this->photo['album_id']);
	}
}
