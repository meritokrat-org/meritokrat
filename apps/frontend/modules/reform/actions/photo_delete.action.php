<?

load::app('modules/ppo/controller');
class ppo_photo_delete_action extends ppo_controller
{
	public function execute()
	{
		load::model('ppo/photos');
		if ( !$this->photo = ppo_photos_peer::instance()->get_item( request::get_int('id') ) )
		{
			throw new public_exception('Фото не найдено');
		}

		$this->group = ppo_peer::instance()->get_item($this->photo['group_id']);

		if ( ($this->photo['user_id'] == session::get_user_id()) || ppo_peer::instance()->is_moderator($this->photo['group_id'], session::get_user_id())  )
		{
			ppo_photos_peer::instance()->delete_item($this->photo['id']);
		}

		$this->redirect('/ppo/photo?id=' . $this->group['id'] . '&album_id=' . $this->photo['album_id']);
	}
}
