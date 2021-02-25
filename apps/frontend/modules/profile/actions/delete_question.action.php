<?

class profile_delete_question_action extends frontend_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->set_renderer('ajax');
		$this->json = array();

		load::model('user/questions');
		if ( $question = user_questions_peer::instance()->get_item(request::get_int('id')) )
		{
			if ( session::has_credential('moderator') ||
				( ( $question['user_id'] == session::get_user_id() ) ) ||
				( session::has_credential('selfmoderator') && $question['profile_id'] == session::get_user_id() ) )
			{
				user_questions_peer::instance()->delete_item( $question['id'] );
			}
		}
	}
}