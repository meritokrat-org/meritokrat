<?

class profile_admin_file_edit_action extends frontend_controller
{
	public function execute()
	{

		load::model('library/files_dirs');
                if(!session::has_credential('admin'))$this->redirect ("/");
		$dirs = library_files_dirs_peer::instance()->get_list(array("object_id"=>session::get_user_id(),"type"=>1));
                if(count($dirs)==0)$dirs=array(0 => 0);
                $this->user_id=request::get_int('user_id');
                
                $this->dirs = array( 0 => t('Немає') );
		foreach ( $dirs as $id )
		{
			$dir = library_files_dirs_peer::instance()->get_item($id);
                        $this->dirs[$id] = $dir['title'];
		}

		if ( request::get('submit') )
		{
			load::model('library/files');

			$dir_id = request::get_int('dir_id');
			$id = request::get_int('id');
                        if ($_POST['type']=='file')
                            {
                                library_files_peer::instance()->update(array(
                                        'id' => $id,
                                        'type' => 9,
                                        'dir_id' => $dir_id,
                                        'title' => trim($_POST['title']),
                                        'author' => trim($_POST['author']),
                                        'lang' => trim($_POST['lang']),
                                        'describe' => request::get('describe',''),
                                        'position' => request::get_int('position')
                                ));
                            }
                        else
                            {
                                $dir_id = request::get_int('dir_id');
                                $links = array();
                                if ( strlen($_POST['url'])>3 )
                                    {
                                        if (mb_substr($_POST['url'],0,7)!='http://') $_POST['url']='http://'.$_POST['url'];
                                            trim($_POST['title']) ? $title = trim($_POST['title']) : $title=$_POST['url'];
                                           library_files_peer::instance()->update(array(
                                                    'id' => $id,
                                                    'type' => 9,
                                                    'dir_id' => $dir_id,
                                                    'title' => $title,
                                                    'url' => trim($_POST['url']),
                                                    'author' => trim($_POST['author']),
                                                    'lang' => trim($_POST['lang']),
                                                    'describe' => request::get('describe'),
                                                    'position' => request::get_int('position')
                                            ));
                                    }
                            }
                        if($dir_id>0)
			$this->redirect('/profile/admin_file?dir_id='.$dir_id);
                        else $this->redirect('/profile/admin_file?id='.$this->user_id);
		}
	}
}
