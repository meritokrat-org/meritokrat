<?php

class profile_katottg_action extends frontend_controller
{
    public function execute()
    {
        load::model('katottg');
        $this->set_renderer('ajax');
        header('Content-type: application/json');

        $this->json = katottg_peer::instance()->collect(request::get_int('id', null));
    }
}



