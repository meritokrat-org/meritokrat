<?
load::app('modules/photo/controller');

class photo_add_action extends photo_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$albums = photo_albums_peer::instance()->get_by_obj($this->oid, $this->type);

		foreach ($albums as $id) {
			$album = photo_albums_peer::instance()->get_item($id);
			$this->albums[$id] = $album['title'];
		}

		if (request::get('submit')) {
			$this->album_id = request::get_int('album_id');
			$this->oid = request::get_int('oid');
			$this->type = request::get_int('type');

			if ($this->access = $this->get_access()) {
				if ($album_name = trim(request::get('album_name'))) {
					$album_id = photo_albums_peer::instance()->insert(array(
						'title' => $album_name,
						'obj_id' => $this->oid,
						'user_id' => session::get_user_id(),
						'type' => $this->type
					));
					$this->redirect('/photo/add?album_id=' . $album_id . '&type=' . $this->type . '&oid=' . $this->oid);
				} else {
					load::system('storage/storage_simple');
					$storage = new storage_simple();
					$photos = array();

					if (is_array($_FILES['file']['tmp_name'])) {
						foreach ($_FILES['file']['tmp_name'] as $i => $file) {
							if ($_FILES['file']['error'][$i]) continue;
							if (!getimagesize($file)) continue;

							$title = trim($_POST['title'][$i]);

							$salt = photo_peer::generate_photo_salt();
							$id = photo_peer::instance()->insert(array(
								'album_id' => $this->album_id,
								'user_id' => session::get_user_id(),
								'salt' => $salt,
								'title' => $title
							));

							$key = 'photoalbum/' . $id . '-' . $salt . '.jpg';
							$storage->save_uploaded($key, array('tmp_name' => $file));

							$photos[] = $id;
						}
					}
					$this->redirect('/photo?album_id=' . $this->album_id);
				}
			}
		}
	}
}
