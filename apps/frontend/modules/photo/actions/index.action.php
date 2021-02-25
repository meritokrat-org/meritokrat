<?
load::app('modules/photo/controller');

class photo_index_action extends photo_controller
{
	public function execute()
	{
		if (request::get('submit') && ($album_name = trim(request::get('title')))) {
			$this->set_renderer('ajax');
			$this->json = array();
			$album_id = photo_albums_peer::instance()->insert(array(
				'obj_id' => request::get_int('oid'),
				'user_id' => session::get_user_id(),
				'title' => $album_name,
				'type' => request::get_int('type'),
			));
		}

		if ($this->album_id) {
			$this->photos = photo_peer::instance()->get_album($this->album_id);
			$this->pager = pager_helper::get_pager($this->photos, request::get_int('page'), 20);
			$this->photos = $this->pager->get_list();
		} else {
			$this->albums = photo_albums_peer::instance()->get_by_obj($this->oid, $this->type);
			//array_unshift($this->albums, 0);
		}
	}
}