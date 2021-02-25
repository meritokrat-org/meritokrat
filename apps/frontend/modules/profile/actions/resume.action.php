<?

load::app('modules/admin/controller');
class profile_resume_action extends frontend_controller
{
	public function execute()
	{
		if(!session::has_credential("admin")){
			$this->redirect("/");
		}
            if ( $id=request::get_int('id') )
		{
			$password = substr(md5(microtime(true)), 0, 8);
			$user = user_auth_peer::instance()->get_item($id);
                        $email=str_replace(",", "", $user['email']);
                        $email=str_replace(" ", "", $email);
			user_auth_peer::instance()->update(array(
					'id' => $id,
					'password' => md5($password),
                                        'email' => $email,
                                        'active' => 1,
                                        'del' => 0
				));

                        load::model('user/user_data');
                        $user_data = user_data_peer::instance()->get_item($id);
                        load::action_helper('user_email',false);
                        $options = array(
                            "%fullname%" => $user_data['first_name']." ".$user_data['last_name'],
                            "%email%" => $user['email'],
                            "%password%" => $password
                            );
                        user_email_helper::send_sys('user_resend',$user['id'],null,$options);
		}
                        $this->redirect('/profile-'.$id);
        }
}