<?php

class profile_crop_photo_action extends frontend_controller
{
    protected $authorized_access = true;

    public function execute()
    {
        $this->disable_layout();
        $this->set_renderer(null);

        load::model('user/user_data');
        load::system('storage/storage_simple');
        $storage = new storage_simple();
        $user_data = user_data_peer::instance()->get_item(request::get_int('id'));
        user_helper::crop_photo($storage, $user_data, 'new_');

    }
}

