<?php

class profile_get_cities_action extends frontend_controller
{
	public function execute()
	{
		load::model('geo');
		$this->set_renderer('ajax');

		$region = request::get_int("region");

		if($region > 28)
			$list = geo_peer::instance()->get_cities($region, true);
		else
			$list = geo_peer::instance()->get_cities($region);

		unset($list[0]);
		$this->json = $list;
	}
}

?>
