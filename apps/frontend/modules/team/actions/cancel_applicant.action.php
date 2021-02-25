<?

load::app('modules/ppo/controller');
class ppo_cancel_applicant_action extends ppo_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->set_renderer('ajax');
		$this->json = array();

		$this->group = ppo_peer::instance()->get_item(request::get_int('group_id'));
		if ( $this->group['user_id'] != session::get_user_id() and !ppo_peer::instance()->is_moderator(request::get_int('group_id'), session::get_user_id()))
		{
			exit;
		}

		load::model('ppo/applicants');
		ppo_applicants_peer::instance()->remove($this->group['id'], request::get_int('id'));
	}
}