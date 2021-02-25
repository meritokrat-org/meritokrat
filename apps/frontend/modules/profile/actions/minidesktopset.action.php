<?php

class profile_minidesktopset_action extends frontend_controller {
	
	public function execute() {
		$this->set_renderer('ajax');
		$this->json = array();
		if(session::get_user_id())
			db_key::i()->set('minidesktopset_'.session::get_user_id(), request::get_int('show'));
	}
	
}

?>
