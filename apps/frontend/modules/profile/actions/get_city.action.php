<?

class profile_get_city_action extends frontend_controller
{
	public function execute()
	{
		load::model('geo');
		$list = array();
		if ( $cities = geo_peer::instance()->get_by_key(request::get_string('key')) )
		{
			foreach ( $cities as $city_id )
			{
				$list[] = geo_peer::instance()->get_city($city_id);
			}
		}


		$this->set_renderer('ajax');
		$this->json = $list;
	}
}