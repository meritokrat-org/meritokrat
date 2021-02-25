<?
load::app('modules/ppo/controller');
class ppo_file_edit_action  extends ppo_controller
{
	public function execute()
	{

		load::model('ppo/files_dirs');
		load::model('ppo/files');
                
                $edit_type = request::get_string('type');
                
                $dir_id = request::get_int('dir_id');
                $dir_data = ppo_files_dirs_peer::instance()->get_item($dir_id);
                
                $file_id = request::get_int('id');
                $file_data = ppo_files_peer::instance()->get_item($file_id);
                
                if (!session::has_credential('admin') && !ppo_peer::instance()->is_moderator($dir_data['group_id'], session::get_user_id()) && !ppo_peer::instance()->is_moderator($file_data['group_id'], session::get_user_id()) && !$file_data['group_id']==session::get_user_id() && !$dir_data['group_id']==session::get_user_id())
		{
                        $this->group = ppo_peer::instance()->get_item($dir_data['group_id']);
			$this->redirect('/ppo'.$this->group['id'].'/'.$this->group['number'].'/');
		}

		$file_id>0 ? $dirs = ppo_files_dirs_peer::instance()->get_list(array('group_id'=>$file_data['group_id'])) : $dirs = ppo_files_dirs_peer::instance()->get_list(array('group_id'=>$dir_data['group_id']));
		$this->dirs = array( 0 => t('Немає') );
		foreach ( $dirs as $id )
		{
			$dir = ppo_files_dirs_peer::instance()->get_item($id);
			$this->dirs[$id] = $dir['title'];
		}
		if ( request::get('submit') )
		{
			load::model('ppo/files');
			$id = request::get_int('id');
                        $group_id=$file_data['group_id'];
                        if ($edit_type=='file')
                            {
                                ppo_files_peer::instance()->update(array(
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
                                           ppo_files_peer::instance()->update(array(
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

			$this->redirect('/ppo/file?id='.$group_id.'&dir_id='.$dir_id);
		}
	}
}
