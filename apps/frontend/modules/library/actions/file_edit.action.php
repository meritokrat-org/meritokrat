<?

class library_file_edit_action extends frontend_controller
{
        protected $authorized_access = true;
        protected $credentials = array('admin');
        
	public function execute()
	{

		load::model('library/files_dirs');
		$dirs = library_files_dirs_peer::instance()->get_list();
                if (request::get('dir_id')) $this->dirs = array( 0 => t('Немає') );
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
                                        'dir_id' => $dir_id,
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
                                $dir_id = request::get_int('dir_id');
                                $links = array();
                                if ( strlen($_POST['url'])>3 )
                                    {
                                        if (mb_substr($_POST['url'],0,7)!='http://') $_POST['url']='http://'.$_POST['url'];
                                            trim($_POST['title']) ? $title = trim($_POST['title']) : $title=$_POST['url'];
                                           library_files_peer::instance()->update(array(
                                                    'id' => $id,
                                                    'dir_id' => $dir_id,
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

			$this->redirect('/library/index?dir_id='.$dir_id);
		}
	}
}
