<?php

/**
 * Class profile_approve_photos_action
 *
 * @property array list
 * @property pager pager
 */
class profile_approve_photos_action extends frontend_controller
{
    protected $authorized_access = true;

    public function execute()
    {
        if (!session::has_credentials(array('admin', 'moderator'))) {
            throw new public_exception('Недостаточно прав');
        }

        load::action_helper('pager');
        $this->list  = db::get_cols('SELECT user_id FROM user_data WHERE new_photo_salt != \'\'');
        $this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 10);
        $this->list  = $this->pager->get_list();
    }
}

