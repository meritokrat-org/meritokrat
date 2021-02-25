<?

class sign_password_action extends frontend_controller
{
	public function execute()
	{
		if ( session::is_authenticated() )
		{
			$this->redirect('/profile');
		}

		if ( !$user = user_auth_peer::instance()->get_by_security_code( request::get('c') ) )
		{
			$this->redirect('/');
		}

		if ( request::get('password') )
		{
			user_auth_peer::instance()->update( array(
				'password' => md5(request::get('password')),
				'security_code' => user_auth_peer::instance()->generate_security_code(),
				'id' => $user['id']
			) );

			$this->set_renderer('ajax');
			$this->json = array();
		}
	}
}
