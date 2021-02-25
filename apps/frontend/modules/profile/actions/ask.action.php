<?

class profile_ask_action extends frontend_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->set_renderer('ajax');
		$this->json = array();

		if ( trim(request::get_string('text')) && request::get_int('profile_id') )
		{
			load::model('user/questions');
			$this->id = user_questions_peer::instance()->insert(array(
				'user_id' => session::get_user_id(),
				'profile_id' => request::get_int('profile_id'),
				'text' => trim(request::get_string('text'))
			));

			user_questions_peer::instance()->rate($this->id, session::get_user_id());

			load::action_helper('user_email', false);
			/*user_email_helper::send(
				request::get_int('profile_id'),
				session::get_user_id(),
				array(
					'subject' => t('%sender% написав на Вашій стіні'),
					'body' =>
						'%receiver%,' . " " .
						'%sender% ' . t(' написав на Вашій стіні') . ":\n" .
						trim(request::get_string('text')) . "\n\n" .
					    t('Щоб переглянути напис, перейдіть за посиланням') . ":\n" .
						'http://' . context::get('host') . '/profile-' . request::get_int('profile_id')
				)
			);*/
                        $options = array(
                                    '%text%' => trim(request::get_string('text')),
                                    '%link%' =>  'http://' . context::get('host') . '/profile-' . request::get_int('profile_id'),
                                    '%settings%'=>'http://'. context::get('host') . '/profile/edit?id='.request::get_int('profile_id').'&tab=settings'
                                    );
                        user_email_helper::send_sys('messages_wall',request::get_int('profile_id'),session::get_user_id(),$options);
		}
	}
}