<?

class profile_login_action extends frontend_controller
{
    protected $authorized_access = true;

    public function execute()
    {

        if ($user = user_auth_peer::instance()->get_item(request::get_int('id'))) {
            if (session::has_credential('superadmin') || session::has_credential('programmer') || ($user['offline'] == session::get_user_id() && session::get_user_id() > 0)) {

                {
                    session::set('admin_id', (session::get('admin_id') ? session::get('admin_id') : session::get_user_id()));
                    $sql = 'INSERT INTO admin_login_log ("id", "admin_id", "user_id", "ts", "ip") VALUES (nextval(\'admin_login_log_id_seq\'::regclass), :admin_id, :user_id, ' . time() . ', :ip)';
                    session::set_user_id($user['id'], explode(',', $user['credentials']));
                    db::exec($sql, array(
                        'admin_id' => session::get('admin_id'),
                        'user_id' => $user['id'],
                        'ip' => $_SERVER['REMOTE_ADDR']
                    ));

                    $this->redirect('/profile-' . $user['id']);
                }
            } else $this->redirect('/profile-' . request::get_int('id'));
        } else throw new public_exception (t('Недостаточно прав'));

    }
}

?>
