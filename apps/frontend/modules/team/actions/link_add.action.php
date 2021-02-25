<?

load::app('modules/ppo/controller');
class ppo_link_add_action extends ppo_controller
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

		load::model('ppo/links_dirs');
		$dirs = ppo_links_dirs_peer::instance()->get_by_group($this->group['id']);
		$this->dirs = array( 0 => t('Разное') );
		foreach ( $dirs as $id )
		{
			$dir = ppo_links_dirs_peer::instance()->get_item($id);
			$this->dirs[$id] = $dir['title'];
		}

		//$this->dirs[-1] = t('Новый альбом') . '...';

		if ( request::get('submit') )
		{
			load::model('ppo/links');

			$dir_id = request::get_int('dir_id');
			$links = array();
			if ( strlen($_POST['url'])>3 )
                            {
                            if (mb_substr($_POST['url'],0,7)!='http://') $_POST['url']='http://'.$_POST['url'];
				trim($_POST['title']) ? $title = trim($_POST['title']) : $title=$_POST['url'];
				$id = ppo_links_peer::instance()->insert(array(
					'dir_id' => $dir_id,
					'group_id' => $this->group['id'],
					'user_id' => session::get_user_id(),
					'title' => $title,
					'url' => trim($_POST['url'])
				));
                        $links[] = $id;
                            }

			load::model('feed/feed');
			load::view_helper('tag', true);
			load::view_helper('group');

			$group = $this->group;
			$this->redirect('/ppo/file?id=' . $this->group['id']);
		}
	}
}
