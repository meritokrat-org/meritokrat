<?

class sign_activate_action extends frontend_controller
{
	public function execute()
	{
		$this->set_layout('public');

		if ( session::is_authenticated() )
		{
			$this->redirect('/');
		}

		if ( !$user = user_auth_peer::instance()->get_by_security_code( $_GET['c'] ) )
		{
			$this->redirect('/');
		}

		if ( !$user['active'] )
		{
			if ( in_array($user['type'], user_auth_peer::$activate_types) )
			{
				user_auth_peer::instance()->activate( $user['id'] );
				session::set_user_id( $user['id'] );
				$this->activated = true;

				$candidate = request::get('v');

				if ( is_numeric($candidate) )
				{
					load::model('candidates/votes');
					candidates_votes_peer::instance()->add(session::get_user_id(), $candidate);
					$this->redirect('http://2010.' . context::get('server') . '/');
				}
			}
		}
	}
}