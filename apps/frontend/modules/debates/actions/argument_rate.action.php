<?

load::app('modules/debates/controller');
class debates_argument_rate_action extends debates_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->set_renderer('ajax');
		$this->json = array();

		load::model('user/questions');

		if ( !debates_arguments_peer::instance()->has_rated(request::get_int('id'), session::get_user_id()) )
		{
			if ( $argument = debates_arguments_peer::instance()->get_item(request::get_int('id')) )
			{
				debates_arguments_peer::instance()->update(array(
					'id' => request::get_int('id'),
					'rate' => $argument['rate'] + (request::get_bool('positive') ? 1 : -1),
					'total' => $argument['total'] + 1
				));

				user_data_peer::instance()->update_rate($argument['user_id'], request::get_bool('positive') ? 0.2 : -0.2, session::get_user_id());

				debates_arguments_peer::instance()->rate($argument['id'], session::get_user_id());
			}
		}
	}
}