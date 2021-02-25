<?
load::app('modules/ppo/controller');
class ppo_down_dir_action  extends ppo_controller
{
	public function execute()
	{

		load::model('ppo/files_dirs');
		if ( request::get('dir_id') )
                {

			$dir_id = request::get_int('dir_id');
                        $dir_data = ppo_files_dirs_peer::instance()->get_item($dir_id);
                        
                        if (!ppo_peer::instance()->is_moderator($dir_data['group_id'], session::get_user_id()))
                            {
                                    $this->redirect('/group'.$dir_data['group_id']);
                            }
                        $dirs = ppo_files_dirs_peer::instance()->get_list(array('group_id'=>$dir_data['group_id']), array(), array('position DESC'));
//получаем айдишник папки от которой бдует взята позиция и пойдет сдвиг вниз
                        $flip_dirs=array_flip($dirs);
                        $replace_dir_key=$flip_dirs[$dir_id]-1;
                        $dir2_id=$dirs[$replace_dir_key];
//информация о папке выше
                        $sql = 'SELECT * FROM ppo_files_dirs WHERE id = :id LIMIT 1';
			$bind = array('id' => $dir2_id);
			$dir = db::get_row( $sql, $bind, null );
                        $position=$dir['position'];
                        if ($position>0)
                        {
                        db::exec("UPDATE ppo_files_dirs SET position=position-1 WHERE position=:position AND group_id=:group_id", array('position'=>$position,'group_id'=>$dir_data['group_id']));
                        ppo_files_dirs_peer::instance()->update(array(
                                                    'id' => $dir_id,
                                                    'position' => $dir['position']
                                            ));
                        }
                        $this->redirect('/ppo/file?id='.$dir_data['group_id'].'&dir_id='.$dir_id);
		}
	}
}
