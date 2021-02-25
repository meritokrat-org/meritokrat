<?

class sign_autologin_action extends frontend_controller
{
	public function execute()
	{
		if ( session::is_authenticated() )
		{
                    $this->redirect('/profile');
		}

                load::action_helper('user_email', false);
		$error = 'Неверный email либо пароль';

		if ( !trim(strtolower(request::get('email'))) || !request::get('password') )
		{
                    header("Content-Type: text/html; charset=utf-8"); die($error);
		}

		if ( !$user = user_auth_peer::instance()->get_by_email( trim(strtolower(request::get('email'))) ) )
		{
                    header("Content-Type: text/html; charset=utf-8"); die($error);
		}
		else if ( $user['password'] != md5(request::get('password')) )
		{
                    header("Content-Type: text/html; charset=utf-8"); die($error);
		}
		else
		{
                    session::set('referer', null);

                    session::set_user_id( $user['id'], explode(',', $user['credentials']) );
                    cookie::set('auth', "{$user['email']}|{$user['password']}", time() + 60*60*24*31, '/', '.' . context::get('host'));

                    $user_data = user_data_peer::instance()->get_item( $user['id'] );
                    session::set( 'language', $user_data['language'] ? $user_data['language'] : 'ua' );

                    error_log('SIGNIN: ' . $user['id'] . ' from ip ' . $_SERVER['REMOTE_ADDR']);

                    if ( !$user['ip'] )
                    {
                        user_auth_peer::instance()->update(array('ip' => $_SERVER['REMOTE_ADDR'], 'id' => $user['id']));
                    }
                    if ( !$user['active'] )
                    {
                        user_auth_peer::instance()->activate( $user['id'] );
                        $inviter = ($user['recomended_by']) ? $user['recomended_by'] : (($user['invited_by']) ? $user['invited_by'] : 0);
                        if($inviter) rating_helper::updateRating($inviter, 'status'); 
                    }
                    $this->redirect('/profile');
		}
	}
}
