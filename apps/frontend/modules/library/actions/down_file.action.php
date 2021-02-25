<?
class library_down_file_action extends frontend_controller
{
	protected $authorized_access = true;
	protected $credentials = array('admin');
        
	public function execute()
	{
            
		load::model('library/files');
		if ( request::get('id') )
                {
			$id = request::get_int('id');
                        $dir_id = library_files_peer::instance()->get_item($id);
                        $dir_id = $dir_id['dir_id'];
                        $files = library_files_peer::instance()->get_list(array(), array(), array('position DESC'));
                        //получаем айдишник файла от которого бдует взята позиция и пойдет сдвиг вниз
                        $flip_files=array_flip($files);
                        $replace_file_key=$flip_files[$id]-1;
                        $file2_id=$files[$replace_file_key];
                        //информация о файле выше
                        $sql = 'SELECT * FROM files WHERE id = :id LIMIT 1';
			$bind = array('id' => $file2_id);
			$file = db::get_row( $sql, $bind, null );
                        $position=$file['position'];
                        if ($position>0)
                        {
                        db::exec("UPDATE files SET position=position-1 WHERE position=:position AND dir_id=:dir_id", array('position'=>$position,'dir_id'=>$dir_id));
                        library_files_peer::instance()->update(array(
                                                    'id' => $id,
                                                    'position' => $file['position']
                                            ));
                        }
                        $this->redirect('/library/index?dir_id='.$dir_id);
		}
	}
}
