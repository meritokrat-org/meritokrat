<?
class profile_down_dir_action extends frontend_controller
{
	public function execute()
	{
		load::model('library/files_dirs');
                $this->user_id= session::get_user_id();
		if ( request::get('dir_id') )
                {

			$dir_id = request::get_int('dir_id');
                        $dirs = library_files_dirs_peer::instance()->get_list(
                                array("object_id"=>$this->user_id,"type"=>1), array(), array('position DESC'));
//получаем айдишник папки от которой бдует взята позиция и пойдет сдвиг вниз
                        $flip_dirs=array_flip($dirs);
                        $replace_dir_key=$flip_dirs[$dir_id]-1;
                        $dir2_id=$dirs[$replace_dir_key];
//информация о папке выше
                        $sql = 'SELECT * FROM files_dirs WHERE id = :id LIMIT 1';
			$bind = array('id' => $dir2_id);
			$dir = db::get_row( $sql, $bind, null );
                        $position=$dir['position'];
                        if ($position>0)
                        {
                        db::exec("UPDATE files_dirs SET position=position-1 
                            WHERE position=:position AND object_id=:object_id AND type=1", 
                                array('position'=>$position,"object_id"=>$this->user_id));
                        library_files_dirs_peer::instance()->update(array(
                                                    'id' => $dir_id,
                                                    'position' => $dir['position']
                                            ));
                        }
                        $this->redirect('/profile/file?dir_id='.$dir_id);
		}
	}
}
