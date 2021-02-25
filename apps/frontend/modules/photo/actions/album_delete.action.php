<?
load::app('modules/photo/controller');
class photo_album_delete_action extends photo_controller
{
	protected $authorized_access = true;
	public function execute()
	{
		if ( !$this->photoalbum = photo_albums_peer::instance()->get_item( request::get_int('id') ) )
		{
                        throw new public_exception('Фотоальбом не найден');
		}

		if ( $this->access )
		{
                        $photos = photo_peer::instance()->get_album(request::get_int('id'));
                        foreach($photos as $value)photo_peer::instance()->delete_item($value);
                        photo_albums_peer::instance()->delete_item($this->photoalbum['id']);
		}

		$this->redirect('/photo?type='.$this->photoalbum['type'].'&oid='.$this->photoalbum['obj_id']);
	}
}
