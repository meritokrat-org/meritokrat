<?

load::app('modules/groups/controller');
class groups_leave_action extends groups_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->set_renderer('ajax');
		$this->json = array();

		$group = groups_peer::instance()->get_item(request::get_int('id'));
		groups_peer::instance()->update_rate( request::get_int('id'), -1, session::get_user_id() );

		if ( session::get_user_id() != $group['user_id'] )
		{
			groups_members_peer::instance()->remove($group['id'], session::get_user_id());
		}
	}
}