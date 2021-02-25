<?php

class sign_out_action extends frontend_controller
{
    public function execute()
    {
        if (session::is_authenticated()) {
            user_sessions_peer::instance()->set_offline(session::get_user_id());
            session::set('admin_id', null);
            session::unset_user();
            cookie::set('auth', null, time() + 60 * 60 * 24 * 31, '/', '.' . context::get('server'));
        }

        $this->redirect('/');
    }
}