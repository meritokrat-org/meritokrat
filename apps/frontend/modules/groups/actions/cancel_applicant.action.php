<?

load::app('modules/groups/controller');
class groups_cancel_applicant_action extends groups_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->set_renderer('ajax');
		$this->json = array();

		$this->group = groups_peer::instance()->get_item(request::get_int('group_id'));
		if ( $this->group['user_id'] != session::get_user_id() and !groups_peer::instance()->is_moderator(request::get_int('group_id'), session::get_user_id()))
		{
			exit;
		}

		load::model('groups/applicants');
		groups_applicants_peer::instance()->remove($this->group['id'], request::get_int('id'));
	}
}