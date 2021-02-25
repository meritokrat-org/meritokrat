<?

load::app('modules/ppo/controller');
class ppo_photoalbum_delete_action extends ppo_controller
{
	public function execute()
	{
		load::model('ppo/photos_albums');
		load::model('ppo/photos');
		if ( !$this->photoalbum = ppo_photos_albums_peer::instance()->get_item( request::get_int('id') ) )
		{
			throw new public_exception('Фотоальбом не найден');
		}

		$this->group = ppo_peer::instance()->get_item($this->photoalbum['group_id']);

		if ( ($this->photoalbum['user_id'] == session::get_user_id()) || ppo_peer::instance()->is_moderator($this->photoalbum['group_id'], session::get_user_id())  )
		{

			$photos = ppo_photos_albums_peer::instance()->get_by_group($this->group['id'], request::get_int('id'));
                       foreach($photos as $value) ppo_photos_peer::instance()->delete_item($value);
                        ppo_photos_albums_peer::instance()->delete_item($this->photoalbum['id']);
		}

		$this->redirect('/ppo/photo?id=' . $this->group['id']);
	}
}
