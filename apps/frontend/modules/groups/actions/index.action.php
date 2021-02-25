<?
load::app('modules/groups/controller');

class groups_index_action extends groups_controller
{
	public function execute()
	{
		if (request::get('bookmark')) {
			load::model('bookmarks/bookmarks');
		}
		$this->cur_type = request::get_int('type');
		if (request::get_int('user_id') > 0) {
			$this->hot = groups_members_peer::instance()->get_groups(request::get_int('user_id'));
		} elseif (request::get_int('app')) {
			if (session::has_credential('admin')) {
				$applicants = db::get_cols('SELECT DISTINCT(group_id) FROM groups_applicants');
			} else {
				load::model('groups/applicants');
				$applicants = array();
				$creator = db::get_cols('SELECT id FROM groups WHERE user_id=' . session::get_user_id(), array(), null, true);
				$groups_artem_negodyai = db::get_cols('SELECT DISTINCT(group_id) FROM groups_applicants');
				foreach ($groups_artem_negodyai as $id) {
					if (groups_peer::instance()->is_moderator($id, session::get_user_id()) || in_array($id, $creator))
						$applicants[] = $id;
				}
			}
			$this->hot = $applicants;
		} elseif (request::get('filter') && session::has_credential('admin')) {
			switch (request::get('filter')) {
				case 'approved':
					$this->hot = groups_peer::instance()->get_list(array('active' => '1'), array(), array('id'));
					break;

				case 'not_approved':
					$this->hot = groups_peer::instance()->get_list(array('active' => '0'), array(), array('id'));
					break;

				case 'open':
					$this->hot = groups_peer::instance()->get_list(array('privacy' => '1'), array(), array('id'));
					break;

				case 'glosed':
					$this->hot = groups_peer::instance()->get_list(array('privacy' => '2'), array(), array('id'));
					break;

				case 'hidden':
					$this->hot = groups_peer::instance()->get_list(array('hidden' => '1'), array(), array('id'));
					break;

				case 'public':
					$this->hot = groups_peer::instance()->get_list(array('hidden' => '0'), array(), array('id'));
					break;
			}
		} elseif (request::get('hot') || request::get('type') || request::get('teritory') || request::get('level') || request::get('category')) {
			$this->hot = groups_peer::instance()->get_hot(
				$this->cur_type,
				$this->cur_teritory = request::get_int('teritory'),
				$this->cur_level = request::get_int('level'),
				$this->cur_category = request::get_int('category')
			);
		} else
			$this->hot = groups_peer::instance()->get_by_members_colls();

		$this->hot = db::get_cols("SELECT id FROM groups WHERE id IN (" . implode(", ", $this->hot) . ") ORDER BY id DESC");

		load::action_helper('pager');
		$this->pager = pager_helper::get_pager($this->hot, request::get_int('page'), 15);
		$this->hot = $this->pager->get_list();
	}
}