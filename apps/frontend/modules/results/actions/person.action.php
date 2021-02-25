<?php

load::model("user/user_auth");
load::model("user/user_data");
load::model("user/user_visits_log");
load::view_helper("date");

class results_person_action extends frontend_controller
{
	public function execute()
	{
		$this->user_id = request::get_int("id");
		
		if( ! user_auth_peer::instance()->get_item($this->user_id))
		{
			$this->redirect("/results/people?act=ntime");
			exit(0);
		}
		
		$this->visits_log = user_visits_log_peer::instance()->get_list(array("user_id" => $this->user_id), array(), array("time DESC"));
	}
}

?>
