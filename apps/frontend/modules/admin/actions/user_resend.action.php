<?

load::app('modules/admin/controller');

class admin_user_resend_action extends admin_controller
{
    public function execute()
    {
        $this->set_layout('');

        if ($id = request::get_int('id')) {
            $user = user_auth_peer::instance()->get_item($id);
            if (strpos($user['email'], '@')) {
                $password = substr(md5(microtime(true)), 0, 8);
                user_auth_peer::instance()->update(array(
                    'id' => $id,
                    'password' => md5($password),
                    'last_invite' => time()
                ));

                load::model('user/user_data');
                $user_data = user_data_peer::instance()->get_item($id);

                load::action_helper('user_email', false);
                $options = array(
                    "%fullname%" => $user_data['first_name'] . " " . $user_data['last_name'],
                    "%email%" => $user['email'],
                    "%password%" => $password
                );
                user_email_helper::send_sys('user_resend', $user['id'], null, $options);
                die('ok');
            } else {
                $this->error = 'email';
            }
        } else {
            $this->error = 'error';
        }
        $this->user = request::get_int('id');
    }
}
