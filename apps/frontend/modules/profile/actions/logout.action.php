<?php

/**
 * @property array json
 */
class profile_logout_action extends frontend_controller
{
    protected $authorized_access = true;

    /**
     * @throws public_exception
     */
    public function execute()
    {
        if ($user = user_auth_peer::instance()->get_item(session::get('admin_id'))) {
            $user_id = session::get_user_id();
            session::set_user_id(session::get('admin_id'), explode(',', $user['credentials']));
            session::set('admin_id', null);
            $this->redirect('/profile-' . $user_id);
        } else {
            // else $this->redirect('/profile-'.request::get_int('id'));
            throw new public_exception ('Помилка');
        }

    }
}

