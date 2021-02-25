<?php

class profile_get_works_action extends frontend_controller
{
	public function execute()
	{
		load::model('geo');
		load::model('user/user_works');
		$this->set_renderer('ajax');

		$this->json = user_works_peer::instance()->get_item(request::get_int("id"));
	}
}

?>
