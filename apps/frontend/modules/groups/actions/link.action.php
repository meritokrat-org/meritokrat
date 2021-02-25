<?

load::app('modules/groups/controller');
class groups_link_action extends groups_controller
{
	public function execute()
	{
		if ( !$this->group = groups_peer::instance()->get_item(request::get_int('id')) )
		{
			$this->redirect('/groups');
		}

                load::model('groups/links_dirs');

                if ( request::get('submit') && ($dir_name = trim(request::get('title') )))
		{
			load::model('groups/links');
			$dir_id = groups_links_dirs_peer::instance()->insert(array(
					'title' => $dir_name,
					'group_id' => $this->group['id']
				));

                        $this->redirect('/groups/link?id='.$this->group['id']);
		}

		if ( ( $this->group['privacy'] == groups_peer::PRIVACY_PRIVATE ) && !groups_members_peer::instance()->is_member($this->group['id'], session::get_user_id()) )
		{
			$this->redirect('/group' . $this->group['id']);
		}

		load::model('groups/links');
		load::model('groups/links_dirs');

		$this->dirs = groups_links_dirs_peer::instance()->get_by_group($this->group['id']);
		$this->dirs_lists = array( 0 => t('Разное') );
		foreach ( $this->dirs as $id )
		{
			$dir = groups_links_dirs_peer::instance()->get_item($id);
			$this->dirs_lists[$id] = $dir['title'];
                        $this->links[$id] = groups_links_peer::instance()->get_by_group($this->group['id'],$id);
		}
                        $this->links[0] = groups_links_peer::instance()->get_by_group($this->group['id'],0);

                array_unshift($this->dirs, 0);
	}
}