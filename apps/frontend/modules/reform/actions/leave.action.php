<?

load::app('modules/ppo/controller');
class ppo_leave_action extends ppo_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->set_renderer('ajax');
		$this->json = array();

		$group = ppo_peer::instance()->get_item(request::get_int('id'));
		ppo_peer::instance()->update_rate( request::get_int('id'), -1, session::get_user_id() );

		if ( session::get_user_id() != $group['user_id'] )
		{
			ppo_members_peer::instance()->remove($group['id'], session::get_user_id());
                        ppo_members_history_peer::instance()->set_member_history(request::get_int('id'),session::get_user_id(),false,time());
		}
	}
}