<?php

class profile_get_countries_action extends frontend_controller
{
    public function execute()
    {
        load::model('geo');
        $this->set_renderer('ajax');
        $list = geo_peer::instance()->get_countries();

        unset($list[0]);
        $this->json = $list;
    }
}


