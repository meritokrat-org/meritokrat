<?

load::model('user/user_visits_log');

class sign_in_action extends frontend_controller
{
	public function execute()
	{
		$this->set_renderer('ajax');
		$this->json = array();

		if ( session::is_authenticated() )
		{
			return;
		}

		$error = 'Неверный email либо пароль';

		if ( !trim(strtolower(request::get('email'))) || !request::get('password') )
		{
			$this->json = array('errors' => array('email' => array($error)));
		}

		if ( !$user = user_auth_peer::instance()->get_by_email( trim(strtolower(request::get('email'))) ) )
		{
			$this->json = array('errors' => array('email' => array($error)));
		}
		else if ( $user['password'] != md5(request::get('password')) )
		{
			$this->json = array('errors' => array('email' => array($error)));
		}
		else
		{
			$this->json = array('referer' => session::get('referer'));
			session::set('referer', null);
                        if ( !$user['active'] )     {
                            
                            
                            user_auth_peer::instance()->activate( $user['id'] );
                            $inviter = ($user['recomended_by']) ? $user['recomended_by'] : (($user['invited_by']) ? $user['invited_by'] : 0);
                            if($inviter) rating_helper::updateRating($inviter, 'status');    
                            
                            
                        }
			session::set_user_id( $user['id'], explode(',', $user['credentials']) );
			cookie::set('auth', "{$user['email']}|{$user['password']}", time() + 60*60*24*31, '/', '.' . context::get('server'));

			$user_data = user_data_peer::instance()->get_item( $user['id'] );
			session::set( 'language', $user_data['language'] ? $user_data['language'] : 'ua' );

			error_log('SIGNIN: ' . $user['id'] . ' from ip ' . $_SERVER['REMOTE_ADDR']);
			
			user_visits_log_peer::instance()->insert(array(
				"user_id" => session::get_user_id(),
				"time_out" => date("Y-m-d H:i:s")
			));
			
			if ( !$user['ip'] )
			{
				user_auth_peer::instance()->update(array('ip' => $_SERVER['REMOTE_ADDR'], 'id' => $user['id']));
			}
		}
	}
}
