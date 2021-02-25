<?
load::app('modules/ppo/controller');
class ppo_down_file_action extends ppo_controller
{
	public function execute()
	{

		load::model('ppo/files');
		if ( request::get('id') )
                {
			$id = request::get_int('id');
                        $file_data = ppo_files_peer::instance()->get_item($id);
                        if (!session::has_credential('admin') && !ppo_peer::instance()->is_moderator($file_data['group_id'],session::get_user_id()))
                        {
                                $this->redirect('/ppo');
                        }
                        $dir_id = $file_data['dir_id'];
                        $files = ppo_files_peer::instance()->get_list(array('dir_id'=>$dir_id), array(), array('position DESC'));
                        //получаем айдишник файла от которого бдует взята позиция и пойдет сдвиг вниз
                        $flip_files=array_flip($files);
                        $replace_file_key=$flip_files[$id]-1;
                        $file2_id=$files[$replace_file_key];
                        //информация о файле выше
                        $sql = 'SELECT * FROM ppo_files WHERE id = :id LIMIT 1';
			$bind = array('id' => $file2_id);
			$file = db::get_row( $sql, $bind, null );
                        $position=$file['position'];
                        if ($position>0)
                        {
                        db::exec("UPDATE ppo_files SET position=position-1 WHERE position=:position AND dir_id=:dir_id", array('position'=>$position,'dir_id'=>$dir_id));
                        ppo_files_peer::instance()->update(array(
                                                    'id' => $id,
                                                    'position' => $file['position']
                                            ));
                        }
                        $this->redirect('/ppo/file?id='.$file_data['group_id'].'&dir_id='.$dir_id);
		}
	}
}
