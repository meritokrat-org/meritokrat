<?
load::app('modules/groups/controller');
class groups_file_edit_action  extends groups_controller
{
	protected $authorized_access = true;
	public function execute()
	{

		load::model('groups/files_dirs');
		load::model('groups/files');
                
                $edit_type = request::get_string('type');
                
                $dir_id = request::get_int('dir_id');
                $dir_data = groups_files_dirs_peer::instance()->get_item($dir_id);
                
                $file_id = request::get_int('id');
                $file_data = groups_files_peer::instance()->get_item($file_id);
                
                if (!session::has_credential('admin') && !groups_peer::instance()->is_moderator($dir_data['group_id'], session::get_user_id()) && !groups_peer::instance()->is_moderator($file_data['group_id'], session::get_user_id()) && !$file_data['group_id']==session::get_user_id() && !$dir_data['group_id']==session::get_user_id())
		{
			$this->redirect('/group'.$file_data);
		}

		$file_id>0 ? $dirs = groups_files_dirs_peer::instance()->get_list(array('group_id'=>$file_data['group_id'])) : $dirs = groups_files_dirs_peer::instance()->get_list(array('group_id'=>$dir_data['group_id']));
		$this->dirs = array( 0 => t('Немає') );
		foreach ( $dirs as $id )
		{
			$dir = groups_files_dirs_peer::instance()->get_item($id);
			$this->dirs[$id] = $dir['title'];
		}
		if ( request::get('submit') )
		{
			load::model('groups/files');
			$id = request::get_int('id');
                        $group_id=$file_data['group_id'];
                        if ($edit_type=='file')
                            {
                                groups_files_peer::instance()->update(array(
                                        'id' => $id,
                                        'dir_id' => $dir_id,
                                        'group_id' => $group_id,
                                        'user_id' => session::get_user_id(),
                                        'title' => trim($_POST['title']),
                                        'author' => trim($_POST['author']),
                                        'lang' => trim($_POST['lang']),
                                        'describe' => request::get('describe',''),
                                        'position' => request::get_int('position')
                                ));
                            }
                        else
                            {
                                $links = array();
                                if ( strlen($_POST['url'])>3 )
                                    {
                                        if (mb_substr($_POST['url'],0,7)!='http://') $_POST['url']='http://'.$_POST['url'];
                                            trim($_POST['title']) ? $title = trim($_POST['title']) : $title=$_POST['url'];
                                           groups_files_peer::instance()->update(array(
                                                    'id' => $id,
                                                    'dir_id' => $dir_id,
                                                    'group_id' => $group_id,
                                                    'user_id' => session::get_user_id(),
                                                    'title' => $title,
                                                    'url' => trim($_POST['url']),
                                                    'author' => trim($_POST['author']),
                                                    'lang' => trim($_POST['lang']),
                                                    'describe' => request::get('describe'),
                                                    'position' => request::get_int('position')
                                            ));
                                    }
                            }

			$this->redirect('/groups/file?id='.$group_id.'&dir_id='.$dir_id);
		}
	}
}
