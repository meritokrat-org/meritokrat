<?
class profile_up_file_action extends frontend_controller
{
	public function execute()
	{
		load::model('library/files');
                $this->user_id= session::get_user_id();
		if ( request::get('id') )
                {
			$id = request::get_int('id');
                        $dir_id = library_files_peer::instance()->get_item($id);
                        $dir_id = $dir_id['dir_id'];
                        $files = library_files_peer::instance()->get_list(
                                array("object_id"=>$this->user_id,"type"=>1), 
                                array(), array('position ASC'));
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
                        db::exec("UPDATE files SET position=position+1 
                            WHERE position=:position AND dir_id=:dir_id
                            AND object_id=:object_id AND type=1", 
                                array('position'=>$position,'dir_id'=>$dir_id,"object_id"=>$this->user_id));
                        library_files_peer::instance()->update(array(
                                                    'id' => $id,
                                                    'position' => $file['position']
                                            ));
                        }
                        $this->redirect('/profile/file?dir_id='.$dir_id);
                        die();
		}
	}
}
