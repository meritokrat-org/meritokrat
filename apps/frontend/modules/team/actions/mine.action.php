<?

load::app('modules/ppo/controller');
class ppo_mine_action extends ppo_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->list = ppo_members_peer::instance()->get_ppo(session::get_user_id(),'count_users');
	}
}