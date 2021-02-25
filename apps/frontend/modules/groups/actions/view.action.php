<?

load::app('modules/groups/controller');

class groups_view_action extends groups_controller
{
	public function execute()
	{
		$this->group = groups_peer::instance()->get_item(request::get_int('id'));
		if (!$this->group and !$this->success or (!session::has_credential('admin') and $this->group['active'] != 1)) $this->redirect('/groups');
		client_helper::set_title(stripslashes(htmlspecialchars($this->group['title'])) . ' | ' . conf::get('project_name'));

		$this->moderators = groups_peer::instance()->get_moderators($this->group['id']);

		$this->is_member = groups_members_peer::instance()->is_member($this->group['id'], session::get_user_id());
		if (!$this->is_member) {
			load::model('invites/invites');
			$num = invites_peer::instance()->get_by_user(session::get_user_id(), 2, request::get_int('id'));
			if (count($num) > 0) {
				$this->has_invite = 1;
			}
		}
		//if (session::get_user_id()==996) die('hidden? - '.$this->group['hidden'].'is_member? - '.$this->is_member.'has_invite? - '.$this->has_invite);
		if (($this->group['hidden'] == 1) && !$this->is_member && !$this->has_invite && !session::has_credential('admin')) {
			throw new public_exception(t('У вас недостаточно прав'));
			return;
		}
		if (($this->group['privacy'] == groups_peer::PRIVACY_PRIVATE) && !$this->is_member && !session::has_credential('admin')) {
			load::model('groups/applicants');
			$this->privacy_closed = true;
			return;
		}

		$this->members = groups_members_peer::instance()->get_members($this->group['id']);
		shuffle($this->members);
		// 5 random users from group
		$this->users = array_slice(array_values($this->members), 0, 5);
		$this->news = groups_news_peer::instance()->get_by_group($this->group['id']);
		/*if ( $news = groups_news_peer::instance()->get_by_group($this->group['id']) )
		{
			$this->news = groups_news_peer::instance()->get_item(array_shift($news));
		}*/

		if ($talks = groups_topics_peer::instance()->get_by_group($this->group['id'])) {
			$this->talks = array_slice($talks, 0, 20);
		}

		load::model('blogs/posts');
		load::model('blogs/comments');

		if ($posts = blogs_posts_peer::instance()->get_by_group($this->group['id'])) {
			$this->posts_count = count($posts);
			if ($this->group['category'] == 2)
				$this->posts = array_slice($posts, 0, 10);
			else
				$this->posts = array_slice($posts, 0, 20);
		}

		if ($this->group['category'] == 2 && $ideals = blogs_posts_peer::instance()->get_by_group($this->group['id'], 1)) {
			$this->ideals_count = count($ideals);
			$this->ideals = array_slice($ideals, 0, 3);
		}
		if ($this->group['category'] == 2 && $positions = blogs_posts_peer::instance()->get_by_group($this->group['id'], 2)) {
			$this->positions_count = count($positions);
			$this->positions = array_slice($positions, 0, 3);
		}

		if ($proposals = groups_proposal_peer::instance()->get_by_group($this->group['id'])) {
			$this->proposals = array_slice($proposals, 0, 10);
		}

		if ($positions = groups_positions_peer::instance()->get_by_group($this->group['id'])) {
			$this->positions = array_slice($positions, 0, 10);
		}

		//load::model('groups/photos');
		//$this->photos = groups_photos_peer::instance()->get_by_group($this->group['id']);
		//$this->photos = array_slice($this->photos, 0, 10);

		load::view_helper('photo');
		load::model('photo/photo');
		$this->photos = photo_peer::instance()->get_by_obj($this->group['id'], 3);
		$this->photos = array_slice($this->photos, 0, 3);

		load::model('groups/files');
		$this->files = groups_files_peer::instance()->get_by_group($this->group['id']);
		$this->files = array_slice($this->files, 0, 10);

		load::model('events/events');
		$this->events = events_peer::instance()->get_by_content_id($this->group['id']);
		$this->events = array_slice($this->events, 0, 10);

		load::model('groups/files');
		load::model('groups/files_dirs');


		$this->dirs_tree = $this->get_child_dirs(0);


		$this->dirs = groups_files_dirs_peer::instance()->get_list(
			array("group_id" => $this->group['id']), array(), array('position ASC'));

		$this->dirs_lists = array(0 => t('немає'));
		foreach ($this->dirs as $id) {
			$dir = groups_files_dirs_peer::instance()->get_item($id);
			$this->dirs_lists[$id] = stripslashes($dir['title']);
			$this->files[$id] = groups_files_peer::instance()->get_list(
				array('dir_id' => $id, "group_id" => $this->group['id']), array(),
				array('position ASC'));
		}
		$this->files[0] = groups_files_peer::instance()->get_list(
			array('dir_id' => 0, "group_id" => $this->group['id']), array(), array('position ASC'));
		if (count($this->files[0]) > 0) {
			$this->dirs_tree[0] = "";
			$this->dirs[] = 0;
		}
		$this->last_parent_dir = db::get_scalar("SELECT id FROM groups_files_dirs  WHERE parent_id=0
                    AND group_id=" . $this->group['id'] . " ORDER by position DESC LIMIT 1");
		$this->nosteep = 1;
		/*
client_helper::set_meta(array(
	'name' => 'description',
	'content' => t('Группа') . ' "' . $this->group['title'] . '"'
));
client_helper::set_meta(array(
	'name' => 'keywords',
	'content' => t('Группа') . ', ' . $this->group['title']
));
		*/
	}

	private function get_child_dirs($dir_id, $recursion = true)
	{
		$dirs = groups_files_dirs_peer::instance()->get_list(array('parent_id' => $dir_id, "group_id" => $this->group['id']), array(), array('position ASC'));
		if (!$recursion) return $dirs;
		if (!is_array($dirs)) return false;
		else {
			foreach ($dirs as $dir) {
				$all_dirs[$dir] = $this->get_child_dirs($dir);
			}
		}
		return $all_dirs;
	}
}