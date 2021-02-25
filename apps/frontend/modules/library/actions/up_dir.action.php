<?
class library_up_dir_action extends frontend_controller
{

        protected $authorized_access = true;
        protected $credentials = array('admin');
    
	public function execute()
	{
            
		load::model('library/files_dirs');
		if ( request::get('dir_id') )
                {

			$dir_id = request::get_int('dir_id');
                        $dirs = library_files_dirs_peer::instance()->get_list(array(), array(), array('position ASC'));
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
                        db::exec("UPDATE files_dirs SET position=position+1 WHERE position=:position", array('position'=>$position));
                        library_files_dirs_peer::instance()->update(array(
                                                    'id' => $dir_id,
                                                    'position' => $dir['position']
                                            ));
                        }
                        $this->redirect('/library/index?dir_id='.$dir_id);
		}
	}
}
