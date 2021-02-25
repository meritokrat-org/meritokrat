<?

load::app('modules/groups/controller');
class groups_mine_action extends groups_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->list = groups_members_peer::instance()->get_groups(session::get_user_id(),'count_users');
	}
}