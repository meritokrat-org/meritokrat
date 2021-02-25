<?

load::app('modules/reform/controller');

class reform_view_action extends reform_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		if(request::get_int('id', 0) > 0)
			$this->group = reform_peer::instance()->get_item(request::get_int('id'));
		elseif(request::get_string("symlink", "") != "")
			$this->group = reform_peer::instance()->get_item_by_symlink(request::get_string('symlink'));

		load::model('user/party_inventory');
		$this->inv_owners = reform_members_peer::instance()->get_members($this->group['id'], false, $this->group);
		if ($this->inv_owners)
			$this->inventory_type = db::get_cols("SELECT inventory_type FROM party_inventory WHERE user_id IN (" . implode(",", $this->inv_owners) . ") GROUP BY inventory_type");


		$this->user = user_auth_peer::instance()->get_item(session::get_user_id());
		$this->user_data = user_data_peer::instance()->get_item(session::get_user_id());

		if( ! $this->group || ( ! session::has_credential('admin') and $this->group['active'] != 1)) $this->redirect('/projects');

		client_helper::set_title(stripslashes(htmlspecialchars($this->group['title'])) . ' | ' . conf::get('project_name'));

		$user_desktop = db::get_row("SELECT * FROM user_desktop WHERE user_id=:user_id", array('user_id' => $this->user['id']));
		$this->user_functions = (array)explode(',', str_replace(array('{', '}'), array('', ''), $user_desktop['functions']));

		$this->moderators = reform_peer::instance()->get_moderators($this->group['id'], $this->group);

		if ($this->group['category'] == 3) {
			$this->finances = reform_finance_peer::instance()->get_by_region($this->group['region_id']);
			$this->ftotal = db::get_scalar("SELECT SUM(p.summ) FROM user_data u, user_payments p WHERE p.user_id = u.user_id AND p.approve = 2 AND p.type = 2 AND p.del = 0 AND u.region_id = " . $this->group['region_id']);
			$this->finvtotal = db::get_scalar("SELECT SUM(p.summ) FROM user_data u, user_payments p WHERE p.user_id = u.user_id AND p.approve = 2 AND p.type = 1 AND p.del = 0 AND u.region_id = " . $this->group['region_id']);
			$this->ffondtotal = db::get_scalar("SELECT SUM(p.summ) FROM user_data u, user_payments p WHERE p.user_id = u.user_id AND p.approve = 2 AND p.type = 4 AND p.del = 0 AND u.region_id = " . $this->group['region_id']);
		}

		$this->is_member = reform_members_peer::instance()->is_member($this->group['id'], session::get_user_id());
		if (!$this->is_member) {
			load::model('invites/invites');
			$num = invites_peer::instance()->get_by_user(session::get_user_id(), 2, request::get_int('id'));
			if (count($num) > 0) {
				$this->has_invite = 1;
			}
		}
		if (($this->group['hidden'] == 1) && !$this->is_member && !$this->has_invite && !session::has_credential('admin')) {
			throw new public_exception(t('У вас недостаточно прав'));
			return;
		}
		if (($this->group['privacy'] == reform_peer::PRIVACY_PRIVATE) && !$this->is_member && !session::has_credential('admin')) {
			load::model('reform/applicants');
			$this->privacy_closed = true;
			return;
		}
		load::model('blogs/posts');
		load::model('blogs/comments');

		if ($posts = blogs_posts_peer::instance()->get_by_ppo($this->group['id'])) {
			$this->posts = array_slice($posts, 0, 10);
		}

		load::model('events/events');
		$this->events = events_peer::instance()->get_by_content_id($this->group['id'], 4);
		$this->events = array_slice($this->events, 0, 10);

		load::model('reform/files');
		$this->files = reform_files_peer::instance()->get_by_group($this->group['id']);
		$this->files = array_slice($this->files, 0, 10);

		load::view_helper('photo');
		load::model('photo/photo');
		$this->photos = photo_peer::instance()->get_by_obj($this->group['id'], 2);
		$this->photos = array_slice($this->photos, 0, 3);

		$this->news = reform_news_peer::instance()->get_by_group($this->group['id']);
		$this->members = reform_members_peer::instance()->get_members($this->group['id'], false, $this->group);
		$this->members_cnt = count($this->members);
		shuffle($this->members);
		$this->users = array_slice(array_values($this->members), 0, 8);

		if ($this->group['category'] > 1) {
			$this->children_ppo = reform_peer::instance()->get_children($this->group, $this->group['category']);
			if ($this->group['category'] > 2) $this->children_mpo = reform_peer::instance()->get_children($this->group, 3, 2);
		}

		load::model('eventreport/eventreport');
		$this->is_leader = reform_members_peer::instance()->is_leader(session::get_user_id());
		if ($this->is_leader || session::has_credential('admin')) {
			$this->reports = eventreport_peer::instance()->get_all_by_ppo($this->group['id']);
		} else {
			$this->reports = eventreport_peer::instance()->get_by_ppo($this->group['id']);
		}
	}

}