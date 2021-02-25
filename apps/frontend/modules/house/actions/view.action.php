<?php

load::app('modules/house/controller');

class house_view_action extends house_controller
{
    public function execute(){
        $hid = request::get_int('hid');
        var_dump($hid);
    }
}