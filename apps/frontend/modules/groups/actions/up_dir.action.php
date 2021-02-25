<?
load::app('modules/groups/controller');
class groups_up_dir_action  extends groups_controller
{
	protected $authorized_access = true;
	public function execute()
	{

		load::model('groups/files_dirs');
		if ( request::get('dir_id') )
                {

			$dir_id = request::get_int('dir_id');
                        $dir_data = groups_files_dirs_peer::instance()->get_item($dir_id);
                        
                        if (!groups_peer::instance()->is_moderator($dir_data['group_id'], session::get_user_id()))
                        {
                                $this->redirect('/groups'.$dir_data['group_id']);
                        }
                        $dirs = groups_files_dirs_peer::instance()->get_list(array('group_id'=>$dir_data['group_id']), array(), array('position ASC'));
//получаем айдишник папки от которой бдует взята позиция и пойдет сдвиг вниз
                        $flip_dirs=array_flip($dirs);
                        $replace_dir_key=$flip_dirs[$dir_id]-1;
                        $dir2_id=$dirs[$replace_dir_key];
//информация о папке выше
                        $sql = 'SELECT * FROM groups_files_dirs WHERE id = :id LIMIT 1';
			$bind = array('id' => $dir2_id);
			$dir = db::get_row( $sql, $bind, null );
                        $position=$dir['position'];
                        if ($position>0)
                        {
                        db::exec("UPDATE groups_files_dirs SET position=position+1 WHERE position=:position", array('position'=>$position));
                        groups_files_dirs_peer::instance()->update(array(
                                                    'id' => $dir_id,
                                                    'position' => $dir['position']
                                            ));
                        }
                        $this->redirect('/groups/file?id='.$dir_data['group_id'].'&dir_id='.$dir_id);
		}
	}
}
