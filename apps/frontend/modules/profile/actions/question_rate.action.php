<?

class profile_question_rate_action extends frontend_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->set_renderer('ajax');
		$this->json = array();

		load::model('user/questions');

		if ( !user_questions_peer::instance()->has_rated(request::get_int('id'), session::get_user_id()) )
		{
			if ( $question = user_questions_peer::instance()->get_item(request::get_int('id')) )
			{
				user_questions_peer::instance()->update(array(
					'id' => request::get_int('id'),
					'rate' => $question['rate'] + (request::get_bool('positive') ? 1 : -1)
				));

				user_questions_peer::instance()->rate($question['id'], session::get_user_id());
			}
		}
	}
}