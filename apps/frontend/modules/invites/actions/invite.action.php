<?

class invites_invite_action extends frontend_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->disable_layout();

		load::model('invites/invites');
		load::model('friends/friends');
		$this->friends = friends_peer::instance()->get_by_user(session::get_user_id());

		if ($group_id = str_replace(array("https://meritokrat.org/group"), array(""), $_SERVER['HTTP_REFERER'])) {
			load::model('groups/members');
			$this->groupmembers = groups_members_peer::instance()->get_members(intval($group_id));
			$this->friends = array_diff($this->friends, $this->groupmembers);
		}

		$this->types = array(
			'blog_post' => t('Запись в блоге'),
			'debate' => t('Дебаты'),
			'poll' => t('Опрос'),
			'idea' => t('Идея'),
			'group' => t('Группа'),
			'party' => t('Партия'),
			'event' => t('Событие'),
			'ppo' => t('Партийные организации'),
			'projects' => t('Проекты'),
			'team' => t('Команда'),
		);
		$this->headers = array(
			'group' => t('Пригласить в сообщество'),
			'event' => t('Пригласить на событие'),
			'poll' => t('Пригласить на опрос'),
			'ppo' => t('Пригласить в партийную организацию'),
			'projects' => t('Пригласить в проект'),
			'team' => t('Пригласить в команду'),
		);
		$this->messages = array(
			'group' => 'Доброго дня, запрошую Вас приєднатися до спільноти',
			'event' => 'Доброго дня, запрошую Вас вiдвiдати подiю',
			'poll' => 'Доброго дня, запрошую Вас прийняти участь у опитуваннi',
			'ppo' => 'Доброго дня, запрошую Вас у партiйную органiзацiю',
			'projects' => 'Доброго дня, запрошую Вас у проект',
			'team' => t('Доброго дня, запрошую Вас до команди'),
		);

		$this->type = request::get('type');

		if ( ! $this->types[$this->type]) {
			return;
		}

		switch ($this->type) {
			case 'blog_post':

				break;

			case 'poll':
				load::model('polls/polls');
				$this->data = polls_peer::instance()->get_item(request::get_int('id'));
				$this->name = $this->data['question'];
				$this->invited = db::get_cols('SELECT to_id FROM invites WHERE obj_id = ' . request::get_int('id') . ' AND type = 3 AND status = 0');
				$this->users = db::get_cols("SELECT id FROM user_auth WHERE active=:active AND id NOT IN (SELECT user_id FROM polls_votes WHERE poll_id=:poll_id)", array('active' => 1, 'poll_id' => request::get_int('id')));

				break;

			case 'idea':

				break;

			case 'group':
				load::view_helper('group');
				load::model('groups/groups');
				$this->data = groups_peer::instance()->get_item(request::get_int('id'));
				$this->name = $this->data['title'];
				$this->invited = db::get_cols('SELECT to_id FROM invites WHERE obj_id = ' . request::get_int('id') . ' AND type = 2 AND status = 0');
				$this->users = db::get_cols("SELECT id FROM user_auth WHERE active=:active AND id NOT IN (SELECT user_id FROM groups_members WHERE group_id=:group_id)", array('active' => 1, 'group_id' => request::get_int('id')));
				break;

			case
				'ppo':
				load::view_helper('group');
				load::model('ppo/ppo');
				$this->data = ppo_peer::instance()->get_item(request::get_int('id'));
				$this->name = $this->data['title'];
				$this->invited = db::get_cols('SELECT to_id FROM invites WHERE obj_id = ' . request::get_int('id') . ' AND type = 4 AND status = 0');
				$this->users = db::get_cols("SELECT id FROM user_auth
                                WHERE status=:status
                                AND id NOT IN (SELECT user_id
                                FROM ppo_members WHERE group_id IN(SELECT id FROM ppo WHERE category=1))", array('status' => 20));

				break;

			case 'projects':
				load::view_helper('group');
				load::model('reform/reform');
				$this->data = reform_peer::instance()->get_item(request::get_int('id'));
				$this->name = $this->data['title'];
				$this->invited = db::get_cols('SELECT to_id FROM invites WHERE obj_id = ' . request::get_int('id') . ' AND type = 4 AND status = 0');
				$this->users = db::get_cols("SELECT id FROM user_auth
                                WHERE status=:status
                                AND id NOT IN (SELECT user_id
                                FROM reform_members WHERE group_id IN(SELECT id FROM reform WHERE category=1))", array('status' => 20));

				break;

			case 'team':
				load::view_helper('group');
				load::model('team/team');
				$this->data = team_peer::instance()->get_item(request::get_int('id'));
				$this->name = $this->data['title'];
				$this->invited = db::get_cols('SELECT to_id FROM invites WHERE obj_id = ' . request::get_int('id') . ' AND type = 4 AND status = 0');
				$this->users = db::get_cols("SELECT id FROM user_auth
                                WHERE status=:status
                                AND id NOT IN (SELECT user_id
                                FROM team_members WHERE group_id IN(SELECT id FROM team WHERE category=1))", array('status' => 20));

				break;

			case 'event':
				load::model('events/events');
				$this->data = events_peer::instance()->get_item(request::get_int('id'));
				$this->name = $this->data['name'];
				$this->invited = db::get_cols('SELECT to_id FROM invites WHERE obj_id = ' . request::get_int('id') . ' AND type = 1 AND status = 0');
				$this->users = db::get_cols("SELECT id FROM user_auth WHERE active=:active AND id NOT IN (SELECT user_id FROM events2users WHERE event_id=:obj_id)", array('active' => 1, 'obj_id' => request::get_int('id')));

				break;
		}

		load::action_helper('page', false);
		$this->pager = pager_helper::get_pager($this->friends, request::get_int('page', 1), 12);
		$this->userpager = pager_helper::get_pager($this->users, request::get_int('page', 1), 12);
		$this->users = $this->userpager->get_list();
		$this->invited = array_unique($this->invited);
		$this->invpager = page_helper::get_pager($this->invited, request::get_int('page'), 10);
		$this->invited = $this->invpager->get_list();
		$this->header = $this->headers[$this->type];
		$this->message = $this->messages[$this->type];

		if (session::has_credential('admin')) //($this->type == 'event' || $this->type == 'poll') &&
		{
			load::model('geo');
			$this->regions = geo_peer::instance()->get_regions(1);

			load::view_helper('group');
			load::model('groups/groups');
			$this->groups = groups_peer::instance()->get_list();

			load::model('user/user_auth');
			$this->statuses = user_auth_peer::get_statuses();

			load::model('lists/lists');
			$this->lists = lists_peer::instance()->get_list();
		}

		load::view_helper('image');
		$this->item_id = request::get_int('id');
		$this->item_type = request::get_int('typ');
	}
}
