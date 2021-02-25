<?

load::app('modules/team/controller');

class team_delete_user_action extends team_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->group_id = request::get_int('group_id');
		if (!team_peer::instance()->is_moderator($this->group_id, session::get_user_id())) {
			$this->redirect('/');
		}
		team_members_peer::instance()->remove($this->group_id, request::get_int('id'));
		team_members_history_peer::instance()->set_member_history($this->group_id, request::get_int('id'), false, time());
		$this->redirect('/team/members?id=' . request::get_int('group_id'));
	}
}