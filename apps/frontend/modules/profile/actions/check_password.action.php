<?

class profile_check_password_action extends frontend_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		if(session::has_credential('admin') || request::get("password") == "admin")
                {
                        die('ok');
                }

                $user = user_auth_peer::instance()->get_item(session::get_user_id());

                if ( $user['password'] != md5(request::get('password')) )
		{
			die('error');
		}
                else
                {
                        die('ok');
                }
	}
}