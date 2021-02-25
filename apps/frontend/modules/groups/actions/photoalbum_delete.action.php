<?

load::app('modules/groups/controller');
class groups_photoalbum_delete_action extends groups_controller
{
	public function execute()
	{
		load::model('groups/photos_albums');
		load::model('groups/photos');
		if ( !$this->photoalbum = groups_photos_albums_peer::instance()->get_item( request::get_int('id') ) )
		{
			throw new public_exception('Фотоальбом не найден');
		}

		$this->group = groups_peer::instance()->get_item($this->photoalbum['group_id']);

		if ( ($this->photoalbum['user_id'] == session::get_user_id()) || groups_peer::instance()->is_moderator($this->photoalbum['group_id'], session::get_user_id())  )
		{

			$photos = groups_photos_albums_peer::instance()->get_by_group($this->group['id'], request::get_int('id'));
                       foreach($photos as $value) groups_photos_peer::instance()->delete_item($value);
                        groups_photos_albums_peer::instance()->delete_item($this->photoalbum['id']);
		}

		$this->redirect('/groups/photo?id=' . $this->group['id']);
	}
}
