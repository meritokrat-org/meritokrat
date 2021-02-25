<?
load::app('modules/reform/controller');

class reform_delete_user_action extends reform_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->group_id = request::get_int('group_id');
		if ( ! reform_peer::instance()->is_moderator($this->group_id, session::get_user_id())) {
			$this->redirect('/');
		}
		reform_members_peer::instance()->remove($this->group_id, request::get_int('id'));
		reform_members_history_peer::instance()->set_member_history($this->group_id, request::get_int('id'), false, time());
		$this->redirect('/projects/members?id=' . request::get_int('group_id'));
	}
}