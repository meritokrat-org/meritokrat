<?

abstract class photo_controller extends frontend_controller
{
	#protected $authorized_access = true;

	public function init()
	{
		parent::init();
		load::model('photo/photo');
		load::model('photo/photo_albums');
		load::view_helper('photo');
		load::action_helper('pager');

		/**
		 * types
		 * 1-user
		 * 2-ppo
		 * 3-group
		 * 4-team
		 */
		if ($this->album_id = request::get_int('album_id'))
			$this->album = photo_albums_peer::instance()->get_item($this->album_id);

		if (!$this->type = request::get_int('type')) {
			if (!$this->album_id)
				$this->type = 1;
			else
				$this->type = $this->album['type'];
		}
		if (!$this->oid = request::get_int('oid')) {
			if (!$this->album_id)
				$this->oid = session::get_user_id();
			else
				$this->oid = $this->album['obj_id'];
		}

		if ($this->type == 1) {
			$this->item = user_auth_peer::instance()->get_item($this->oid);
			if (!$this->item['id']) {
				throw new public_exception(t('Пользователь не найден'));
			}
		} elseif ($this->type == 2) {
			load::model('ppo/ppo');
			$this->item = ppo_peer::instance()->get_item($this->oid);
			$company_title = (mb_strlen($this->item['title']) > 50) ? mb_substr(htmlspecialchars(stripslashes($this->item['title'])), 0, 50) . '...' : htmlspecialchars(stripslashes($this->item['title']));
			if (!$this->item['id']) {
				throw new public_exception(t('Партийная организация не найдена'));
			}
		} elseif ($this->type == 3) {
			load::model('groups/groups');
			load::model('groups/members');
			$this->item = groups_peer::instance()->get_item($this->oid);
			$group_title = (mb_strlen($this->item['title']) > 50) ? mb_substr(htmlspecialchars(stripslashes($this->item['title'])), 0, 50) . '...' : htmlspecialchars(stripslashes($this->item['title']));
			if (!$this->item['id']) {
				throw new public_exception(t('Группа не найдена'));
			} elseif ($this->item['privacy'] == groups_peer::PRIVACY_PRIVATE AND !groups_members_peer::instance()->is_member($this->item['id'], session::get_user_id())) {
				$this->redirect('/group' . $this->oid);
			}
		} elseif ($this->type == 4) {
			load::model('team/team');
			$this->item = team_peer::instance()->get_item($this->oid);
			$company_title = (mb_strlen($this->item['title']) > 50) ? mb_substr(htmlspecialchars(stripslashes($this->item['title'])), 0, 50) . '...' : htmlspecialchars(stripslashes($this->item['title']));
			if (!$this->item['id']) {
				throw new public_exception(t('Штаб не найден'));
			}
		}

		$this->names = array(
			1 => t('Фотоальбомы участника'),
			2 => t('Фотоальбомы') . ' ' . t('партийных организаций'),
			3 => t('Фотоальбомы группы'),
			3 => t('Фотоальбомы штаба')
		);

		$this->links = array(
			1 => array('profile?id=' . $this->oid, user_helper::full_name($this->oid, false)),
			2 => array('ppo' . $this->oid . '/' . $this->item['number'], $company_title),
			3 => array('group' . $this->oid, $group_title),
			4 => array('team' . $this->oid . '/' . $this->item['number'], $company_title),
		);
		$this->access = $this->get_access();

		client_helper::set_title(t('Фотоальбомы') . ' :: ' . conf::get('project_name'));
	}

	protected function get_access()
	{
		if (session::has_credential('admin'))
			return true;

		if ($this->album_id)
			$album = photo_albums_peer::instance()->get_item($this->album_id);

		if ($album['id']) {
			if ($album['type'] == 1) {
				if ($album['user_id'] == session::get_user_id())
					return true;
			} elseif ($album['type'] == 2) {
				if (ppo_peer::instance()->is_moderator($this->item['id'], session::get_user_id()) || session::has_credential('admin'))
					return true;
			} elseif ($album['type'] == 3) {
				load::model('groups/members');
				if (groups_members_peer::instance()->is_member($this->item['id'], session::get_user_id()) OR $album['user_id'] == session::get_user_id())
					return true;
			} elseif ($album['type'] == 4) {
				if (team_peer::instance()->is_member($this->item['id'], session::get_user_id()) OR $album['user_id'] == session::get_user_id())
					return true;
			}
			return false;
		} else {
			if ($this->type == 1) {
				if ($this->oid == session::get_user_id())
					return true;
			} elseif ($this->type == 2) {
				if (ppo_peer::instance()->is_moderator($this->item['id'], session::get_user_id()) || session::has_credential('admin'))
					return true;
			} elseif ($this->type == 3 && $this->item['id']) {
				load::model('groups/members');
				if (groups_members_peer::instance()->is_member($this->item['id'], session::get_user_id()))
					return true;
			} elseif ($this->type == 4) {
				if (team_peer::instance()->is_moderator($this->item['id'], session::get_user_id()) || session::has_credential('admin'))
					return true;
			}
			return false;
		}
	}
}