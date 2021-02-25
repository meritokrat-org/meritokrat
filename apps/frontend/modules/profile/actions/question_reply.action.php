<?

class profile_question_reply_action extends frontend_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->disable_layout();

		load::model('user/questions');
		$question = user_questions_peer::instance()->get_item(request::get_int('id'));
		if ( $question['profile_id'] == session::get_user_id() )
		{
			user_questions_peer::instance()->update(array(
				'id' => request::get_int('id'),
				'reply' => trim(request::get('reply'))
			));
		}

		$this->question = user_questions_peer::instance()->get_item(request::get_int('id'));
	}
}