<?
class banners_up_action extends frontend_controller
{
	public function execute()
	{
		if (!session::has_credential('admin'))
		{
			$this->redirect('/banners');
		}
		load::model('banners/items');
		if ( request::get('id') )
        {
			$id = request::get_int('id');
            $banners = banners_items_peer::instance()->get_list(array(), array(), array('position ASC'));
//получаем айдишник папки от которой бдует взята позиция и пойдет сдвиг вниз
            $flip_banners=array_flip($banners);
            $replace_banner_key=$flip_banners[$id]-1;
            $banner2_id=$dirs[$replace_banner_key];
//информация о папке выше
            $sql = 'SELECT * FROM banners WHERE id = :id LIMIT 1';
			$bind = array('id' => $banner2_id);
			$ban = db::get_row( $sql, $bind, null );
            $position=$ban['position'];
            if ($position>0)
            {
            db::exec("UPDATE banners SET position=position+1 WHERE position=:position", array('position'=>$position));
            banners_items_peer::instance()->update(array(
                    'id' => $id,
                    'position' => $ban['position']
                ));
            }
            $this->redirect('/banners');
		}
	}
}
