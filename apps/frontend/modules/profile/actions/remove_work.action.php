<?php

class profile_remove_work_action extends frontend_controller
{
	public function execute()
	{
		load::model('user/user_works');
		$this->set_renderer('ajax');

		user_works_peer::instance()->delete_item(request::get_int("id"));
	}
}

?>
