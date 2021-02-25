<?

load::app('modules/ideas/controller');
class ideas_rate_action extends ideas_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		if ( $idea = ideas_peer::instance()->get_item(request::get_int('id')) )
		{

			if ( !ideas_peer::instance()->has_voted($idea['id'], session::get_user_id()) )
			{
				ideas_peer::instance()->update(array(
					'id' => $idea['id'],
					'rate' => $idea['rate'] + 1
				));

				user_data_peer::instance()->update_rate($idea['user_id'], 1, session::get_user_id());
				ideas_peer::instance()->vote($idea['id'], session::get_user_id());
			}
		}

		$this->set_renderer('ajax');
		$this->json = array();
	}
}
