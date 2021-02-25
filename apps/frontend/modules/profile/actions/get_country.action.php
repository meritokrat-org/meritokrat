<?php

class profile_get_country_action extends frontend_controller
{
	public function execute()
	{
		load::model('geo');
		$list = array();
		if ($countries = geo_peer::instance()->get_country_by_key(request::get_string('key'))) {
			foreach ($countries as $country_id) {
				$list[] = geo_peer::instance()->get_country($country_id);
			}
		}

		$this->set_renderer('ajax');
		$this->json = $list;
	}
}

?>
