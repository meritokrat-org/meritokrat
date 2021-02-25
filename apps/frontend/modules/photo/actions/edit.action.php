<?
load::app('modules/photo/controller');
class photo_edit_action extends photo_controller
{
	protected $authorized_access = true;
	public function execute()
	{
		if(!$this->album_id)
                {
                    $this->redirect('/photo?type='.$this->type.'&oid='.$this->oid);
                }
                if(!$this->access)
                {
                    $this->redirect('/photo?album_id='.$this->album_id);
                }

                $this->albums = photo_albums_peer::instance()->get_item($this->album_id);
                $this->photos = photo_peer::instance()->get_album($this->album_id);
                $this->pager = pager_helper::get_pager($this->photos, request::get_int('page'), 18);
                $this->photos = $this->pager->get_list();

		if ( request::get('submit') )
		{
                    $this->album_id = request::get_int('album_id');
                    $this->oid = request::get_int('oid');
                    $this->type = request::get_int('type');

                    if($this->access = $this->get_access())
                    {
			if ( $album_name = trim(request::get('album_name')) )
			{
				photo_albums_peer::instance()->update(array(
                                        'id' => $this->album_id,
					'title' => $album_name,
					'obj_id' => $this->oid,
                                        'user_id' => session::get_user_id(),
                                        'type' => $this->type
				));
                                $this->redirect('/photo?album_id='.$this->album_id);
			}
                    }
		}
	}
}
