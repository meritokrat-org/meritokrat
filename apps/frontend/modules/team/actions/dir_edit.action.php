<?
load::app('modules/ppo/controller');
class ppo_dir_edit_action extends ppo_controller
{
	public function execute()
	{
		load::model('ppo/files_dirs');
		if ( request::get('submit') )
		{
			$dir_id = request::get_int('dir_id');
                        $dir_data = ppo_files_dirs_peer::instance()->get_item($dir_id);
                        if (!ppo_peer::instance()->is_moderator($dir_data['group_id'], session::get_user_id()))
                            {
                                    $this->redirect('/group'.$dir_data['group_id']);
                            }
                       if ( strlen($_POST['title'])>2 )
                                    {
                                    $position = request::get_int('position');
               //                   db::exec("UPDATE files_dirs SET position=position+1 WHERE position>=:position", array('position'=>$position));
                                    ppo_files_dirs_peer::instance()->update(array(
                                                    'id' => $dir_id,
                                                    'title' => trim(request::get_string('title')),
                                                    'parent_id' => request::get_int('parent_id'),
                                                    'position' => $position
                                            ));
                                    }
			$this->redirect('/ppo/file?id='.$dir_data['group_id'].'&dir_id='.$dir_id);
		}
	}
}
