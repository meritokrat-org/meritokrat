<?

load::app('modules/ppo/controller');
class ppo_link_action extends ppo_controller
{
	public function execute()
	{
		if ( !$this->group = ppo_peer::instance()->get_item(request::get_int('id')) )
		{
			$this->redirect('/ppo');
		}

                load::model('ppo/links_dirs');

                if ( request::get('submit') && ($dir_name = trim(request::get('title') )))
		{
			load::model('ppo/links');
			$dir_id = ppo_links_dirs_peer::instance()->insert(array(
					'title' => $dir_name,
					'group_id' => $this->group['id']
				));

                        $this->redirect('/ppo/link?id='.$this->group['id']);
		}

		if ( ( $this->group['privacy'] == ppo_peer::PRIVACY_PRIVATE ) && !ppo_members_peer::instance()->is_member($this->group['id'], session::get_user_id()) )
		{
			$this->redirect('/group' . $this->group['id']);
		}

		load::model('ppo/links');
		load::model('ppo/links_dirs');

		$this->dirs = ppo_links_dirs_peer::instance()->get_by_group($this->group['id']);
		$this->dirs_lists = array( 0 => t('Разное') );
		foreach ( $this->dirs as $id )
		{
			$dir = ppo_links_dirs_peer::instance()->get_item($id);
			$this->dirs_lists[$id] = $dir['title'];
                        $this->links[$id] = ppo_links_peer::instance()->get_by_group($this->group['id'],$id);
		}
                        $this->links[0] = ppo_links_peer::instance()->get_by_group($this->group['id'],0);

                array_unshift($this->dirs, 0);
	}
}