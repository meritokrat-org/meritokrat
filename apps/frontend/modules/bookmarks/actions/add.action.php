<?php

load::app('modules/bookmarks/controller');

class bookmarks_add_action extends bookmarks_controller
{
    public function execute()
    {
        $this->set_renderer('ajax');
	//$this->json = array();

        if ( ( $type = request::get_int('type') ) && ( $id = request::get_int('id') ) )
        {
                bookmarks_peer::instance()->add(session::get_user_id(), $type, $id);
        }
    }
}