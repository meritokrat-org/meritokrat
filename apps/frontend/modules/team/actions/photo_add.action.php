<?

load::app('modules/ppo/controller');
class ppo_photo_add_action extends ppo_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		if ( !$this->group = ppo_peer::instance()->get_item(request::get_int('id')) )
		{
			$this->redirect('/ppo');
		}

		if ( (!ppo_members_peer::instance()->is_member($this->group['id'], session::get_user_id())) and (!session::has_credential('admin')) )
		{
			$this->redirect('/group' . $this->group['id']);
		}

		load::model('ppo/photos_albums');
		$albums = ppo_photos_albums_peer::instance()->get_by_group($this->group['id']);
		$this->albums = array( 0 => t('Основной альбом') );
		foreach ( $albums as $id )
		{
			$album = ppo_photos_albums_peer::instance()->get_item($id);
			$this->albums[$id] = $album['title'];
		}
		
		//$this->albums[-1] = t('Новый альбом') . '...';

		if ( request::get('submit') )
		{
			load::model('ppo/photos');
			load::system('storage/storage_simple');

			$album_id = request::get_int('album_id');

			if ( $album_name = trim(request::get('album_name')) )
			{
				$album_id = ppo_photos_albums_peer::instance()->insert(array(
					'title' => $album_name,
					'group_id' => $this->group['id']
				));
			}

			$storage = new storage_simple();
			$photos = array();

			foreach ( $_FILES['file']['tmp_name'] as $i => $file )
			{
				if ( $_FILES['file']['error'][$i] ) continue;
				if ( !getimagesize($file) ) continue;
				
				$title = trim($_POST['title'][$i]);

				$salt = ppo_photos_peer::generate_photo_salt();
				$id = ppo_photos_peer::instance()->insert(array(
					'album_id' => $album_id,
					'group_id' => $this->group['id'],
					'user_id' => session::get_user_id(),
					'salt' => $salt,
					'title' => $title
				));

				$key = 'group_photo/' . $id . '-' . $salt . '.jpg';
				$storage->save_uploaded($key, array('tmp_name' => $file));

				$photos[] = $id;
			}

			load::model('feed/feed');
			load::view_helper('tag', true);
			load::view_helper('group');

			$group = $this->group;
			ob_start();
			include dirname(__FILE__) . '/../../feed/views/partials/items/group_photo.php';
			$feed_html = ob_get_clean();

			load::model('ppo/members');
			$readers = ppo_members_peer::instance()->get_members($this->group['id'],false,$this->group);
			$readers = array_diff($readers, array(session::get_user_id()));
			feed_peer::instance()->add(session::get_user_id(), $readers, array(
				'actor' => session::get_user_id(),
				'text' => $feed_html,
				'action' => feed_peer::ACTION_GROUP_PHOTO_ADD,
				'section' => feed_peer::SECTION_PERSONAL
			));

			$this->redirect('/ppo/photo?id=' . $this->group['id']);
		}
	}
}
