<?

load::app('modules/groups/controller');
class profile_admin_file_add_action extends groups_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		load::model('library/files_dirs');
		$dirs = library_files_dirs_peer::instance()->get_list();
		$this->dirs = array( 0 => t('Основная папка') );
                $this->user_id=request::get_int('user_id');
		foreach ( $dirs as $id )
		{
			$dir = library_files_dirs_peer::instance()->get_item($id);
			$this->dirs[$id] = $dir['title'];
		}

		//$this->dirs[-1] = t('Новый альбом') . '...';
			load::model('library/files');

			$dir_id = request::get_int('dir_id');
                        $position = request::get_int('position');
                        db::exec("UPDATE files SET position=position+1 WHERE position>=:position", array('position'=>$position));
                        if (isset($_FILES['file']))
                            {

                                trim($_POST['title']) ? $title = trim($_POST['title']) : $title=$_FILES['file']['name'][0];
                                
                                $files = array();
                                load::system('storage/storage_simple');
                                foreach ( $_FILES['file']['tmp_name'] as $i => $file )
                                {
                                        if ( $_FILES['file']['error'][$i] ) continue;
                                        if (!library_files_peer::instance()->is_allowed_filetype($_FILES['file']['name'][$i],  $_FILES['file']['type'][$i])) throw new public_exception('error in file extension or mime-type: '.stripslashes(htmlspecialchars($_FILES['file']['type'][$i])));
//

                                        $storage = new storage_simple();
                                        $salt = library_files_peer::generate_file_salt();
                                        $storage->save_uploaded($salt, array('tmp_name'=>$file));
       
                                        $files[]=array("name"=>$_FILES['file']['name'][$i],
                                            "salt"=>$salt);  
                                }
                                
                                 
                                $id = library_files_peer::instance()->insert(array(
                                                'dir_id' => $dir_id,
                                                'object_id' => $this->user_id,
                                                'admin_id' => session::get_user_id(),
                                                'url' => $_FILES['file']['name'][0],
                                                'size' => library_files_peer::formatBytes($_FILES['file']['size'][0]),
                                                'title' => $title,
                                                'type' => 9,
                                                'describe' => $_POST['describe'],
                                                'author' => request::get_string('author'),
                                                'lang' => request::get_string('lang'),
                                                'files' => serialize($files),
                                    'date' => time()
                                        ));
                               $files[] = $id;
                            }
                        else
                            {
                                $dir_id = request::get_int('dir_id');
                                $links = array();
                                if ( strlen($_POST['url'])>3 )
                                    {
                                        if (mb_substr($_POST['url'],0,7)!='http://') $_POST['url']='http://'.$_POST['url'];
                                            library_files_peer::instance()->get_icon(end(explode('.', $_POST['url'])))!=='unknown.png' ? $ext=end(explode('.', $_POST['url'])) : $ext=NULL;
                                            trim($_POST['title']) ? $title = trim($_POST['title']) : $title=$_POST['url'];
                                            $id = library_files_peer::instance()->insert(array(
                                                    'dir_id' => $dir_id,
                                                    'object_id' => $this->user_id,
                                                    'admin_id' => session::get_user_id(),
                                                    'title' => $title,
                                                    'url' => trim($_POST['url']),
                                                    'exts' => $ext,
                                                    'type' => 9,
                                                    'author' => request::get_string('author'),
                                                    'lang' => request::get_string('lang'),
                                                    'describe' => request::get('describe'),
                                                'date' => time()
                                            ));
                                        $links[] = $id;
                                    }
                            }

			load::model('feed/feed');
			load::view_helper('tag', true);
			load::view_helper('group');

			$this->redirect('/profile/admin_file?id='.$this->user_id);
	}
}
