<?

load::app('modules/ppo/controller');
class ppo_photo_action extends ppo_controller
{
	public function execute()
	{
		if ( !$this->group = ppo_peer::instance()->get_item(request::get_int('id')) )
		{
			$this->redirect('/ppo');
		}

                load::model('ppo/photos_albums');

                if ( request::get('submit') && ($album_name = trim(request::get('title') )))
		{
			load::model('ppo/photos');
			load::system('storage/storage_simple');
			$album_id = ppo_photos_albums_peer::instance()->insert(array(
					'title' => $album_name,
					'group_id' => $this->group['id']
				));

                        $this->redirect('/ppo/photo?id='.$this->group['id'].'&album_id='.$album_id);
		}

		if ( ( $this->group['privacy'] == ppo_peer::PRIVACY_PRIVATE ) && !ppo_members_peer::instance()->is_member($this->group['id'], session::get_user_id()) )
		{
			$this->redirect('/group' . $this->group['id']);
		}

		load::model('ppo/photos');
		load::model('ppo/photos_albums');

		if ( $this->album_id = request::get_int('album_id', null) )
		{
			$this->album = ppo_photos_albums_peer::instance()->get_item($this->album_id);
		}

		if ( $this->album_id !== null )
		{
			$this->photos = ppo_photos_peer::instance()->get_by_group($this->group['id'], $this->album_id);
			$this->pager = pager_helper::get_pager($this->photos, request::get_int('page'), 20);
			$this->photos = $this->pager->get_list();
		}
		else
		{
			$this->albums = ppo_photos_albums_peer::instance()->get_by_group($this->group['id']);
			array_unshift($this->albums, 0);
		}
	}
}