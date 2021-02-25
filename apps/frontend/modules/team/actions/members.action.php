<?

load::app('modules/team/controller');

class team_members_action extends team_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		if ($this->group = team_peer::instance()->get_item(request::get_int('id'))) {
			if (($this->group['privacy'] == team_peer::PRIVACY_PRIVATE) && !team_members_peer::instance()->is_member($this->group['id'], session::get_user_id()) && !session::has_credential('admin')) {
				$this->redirect('/team' . $this->group['id'] . '/' . $this->group['number']);
			}

			$this->list = team_members_peer::instance()->get_members($this->group['id'], false, $this->group);
			$this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 16);
			$this->list = $this->pager->get_list();
		}
	}
}