<?php

load::app('modules/zayava/controller');

class zayava_rec_action extends zayava_controller {
	public function execute(){
		$this->set_renderer('ajax');
    $this->json = array();
		
		if(request::get_int("id")){
			user_zayava_peer::instance()->recover_item(request::get_int("id"), session::get_user_id());
		}
		
	}
}

?>
