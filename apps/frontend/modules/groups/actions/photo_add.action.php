<?

load::app('modules/groups/controller');
class groups_photo_add_action extends groups_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		if ( !$this->group = groups_peer::instance()->get_item(request::get_int('id')) )
		{
			$this->redirect('/groups');
		}

		if ( (!groups_members_peer::instance()->is_member($this->group['id'], session::get_user_id())) and (!session::has_credential('admin')) )
		{
			$this->redirect('/group' . $this->group['id']);
		}

		load::model('groups/photos_albums');
		$albums = groups_photos_albums_peer::instance()->get_by_group($this->group['id']);
		$this->albums = array( 0 => t('Основной альбом') );
		foreach ( $albums as $id )
		{
			$album = groups_photos_albums_peer::instance()->get_item($id);
			$this->albums[$id] = $album['title'];
		}
		
		//$this->albums[-1] = t('Новый альбом') . '...';

		if ( request::get('submit') )
		{
			load::model('groups/photos');
			load::system('storage/storage_simple');

			$album_id = request::get_int('album_id');

			if ( $album_name = trim(request::get('album_name')) )
			{
				$album_id = groups_photos_albums_peer::instance()->insert(array(
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

				$salt = groups_photos_peer::generate_photo_salt();
				$id = groups_photos_peer::instance()->insert(array(
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

			load::model('groups/members');
			$readers = groups_members_peer::instance()->get_members($this->group['id']);
			$readers = array_diff($readers, array(session::get_user_id()));
			feed_peer::instance()->add(session::get_user_id(), $readers, array(
				'actor' => session::get_user_id(),
				'text' => $feed_html,
				'action' => feed_peer::ACTION_GROUP_PHOTO_ADD,
				'section' => feed_peer::SECTION_PERSONAL
			));

			$this->redirect('/groups/photo?id=' . $this->group['id']);
		}
	}
}
