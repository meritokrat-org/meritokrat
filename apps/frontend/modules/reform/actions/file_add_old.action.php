<?

load::app('modules/ppo/controller');
class ppo_file_add_action extends ppo_controller
{
	protected $authorized_access = true;

	public function execute()
	{
        if ( !$this->group = ppo_peer::instance()->get_item(request::get_int('id')) )
		{
			$this->redirect('/ppo');
		}

		if ( (!ppo_members_peer::instance()->is_member($this->group['id'], session::get_user_id())) and (!session::has_credential('admin')) )
		{
			$this->redirect('/group' . $this->group['id']);
		}

		load::model('ppo/files_dirs');
		$dirs = ppo_files_dirs_peer::instance()->get_by_group($this->group['id']);
		$this->dirs = array( 0 => t('Основная папка') );
		foreach ( $dirs as $id )
		{
			$dir = ppo_files_dirs_peer::instance()->get_item($id);
			$this->dirs[$id] = $dir['title'];
		}
		
		//$this->dirs[-1] = t('Новый альбом') . '...';

		if ( request::get('submit') )
		{
			load::model('ppo/files');

			$dir_id = request::get_int('dir_id');
			$files = array();

			foreach ( $_FILES['file']['tmp_name'] as $i => $file )
			{
				if ( $_FILES['file']['error'][$i] ) continue;
				if (!ppo_files_peer::instance()->is_allowed_filetype($_FILES['file']['name'][$i],  $_FILES['file']['type'][$i])) die('error in filetype');

				trim($_POST['title'][$i]) ? $title = trim($_POST['title'][$i]) : $title=$_FILES['file']['name'][$i];

				$salt = ppo_files_peer::generate_file_salt();
				$id = ppo_files_peer::instance()->insert(array(
					'dir_id' => $dir_id,
					'group_id' => $this->group['id'],
					'user_id' => session::get_user_id(),
					'salt' => $salt,
					'size' => ppo_files_peer::formatBytes($_FILES['file']['size'][$i]),
					'title' => $title,
					'ext' => end(explode('.', $_FILES['file']['name'][$i]))
				));
				$path = './files/ppo/' . $this->group['id'] . '/'.$id . '-' . $salt . '.' . end(explode('.', $_FILES['file']['name'][$i]));

                                $dir = dirname($path);
                                $create = array();
                                while ( !is_dir($dir) )
                                {
                                        $create[] = $dir;
                                        $dir = dirname($dir);
                                }
                                $create = array_reverse($create);
                                foreach ( $create as $dir )
                                {
                                        mkdir($dir);
                                }
                                
                                move_uploaded_file($file,$path);
                                $files[] = $id;
			}

			load::model('feed/feed');
			load::view_helper('tag', true);
			load::view_helper('group');

			$group = $this->group;
			/*ob_start();
			include dirname(__FILE__) . '/../../feed/views/partials/items/group_file.php';
			$feed_html = ob_get_clean();

			load::model('ppo/members');
			$readers = ppo_members_peer::instance()->get_members($this->group['id']);
			$readers = array_diff($readers, array(session::get_user_id()));
			/*feed_peer::instance()->add(session::get_user_id(), $readers, array(
				'actor' => session::get_user_id(),
				'text' => $feed_html,
				'action' => feed_peer::ACTION_GROUP_file_ADD,
				'section' => feed_peer::SECTION_PERSONAL
			));
                        */
			$this->redirect('/ppo/file?id=' . $this->group['id']);
		}
	}
}
