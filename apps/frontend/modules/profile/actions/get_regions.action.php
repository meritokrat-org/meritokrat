<?php

class profile_get_regions_action extends frontend_controller
{
	public function execute()
	{
		load::model('geo');
		$this->set_renderer('ajax');
		$list = geo_peer::instance()->get_regions(request::get_int("country"));
		unset($list[0]);
		$this->json = $list;
	}
}

?>
