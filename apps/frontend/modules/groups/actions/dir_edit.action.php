<?
load::app('modules/groups/controller');
class groups_dir_edit_action extends groups_controller
{
	protected $authorized_access = true;
	public function execute()
	{
		load::model('groups/files_dirs');
		if ( request::get('submit') )
		{
			$dir_id = request::get_int('dir_id');
                        $dir_data = groups_files_dirs_peer::instance()->get_item($dir_id);
                        if (!groups_peer::instance()->is_moderator($dir_data['group_id'], session::get_user_id()))
                            {
                                    $this->redirect('/group'.$dir_data['group_id']);
                            }
                       if ( strlen($_POST['title'])>2 )
                                    {
                                    $position = request::get_int('position');
               //                   db::exec("UPDATE files_dirs SET position=position+1 WHERE position>=:position", array('position'=>$position));
                                    groups_files_dirs_peer::instance()->update(array(
                                                    'id' => $dir_id,
                                                    'title' => trim(request::get_string('title')),
                                                    'parent_id' => request::get_int('parent_id'),
                                                    'position' => $position
                                            ));
                                    }
			$this->redirect('/groups/file?id='.$dir_data['group_id'].'&dir_id='.$dir_id);
		}
	}
}
