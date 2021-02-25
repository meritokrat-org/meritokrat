<?php
class profile_get_region_action extends frontend_controller
{
	protected $authorized_access = true;
	public function execute()
	{
		load::model('geo');
		$list = array();
		if ( $regions = geo_peer::instance()->get_region_by_key(request::get_string('key')) )
		{
			foreach ( $regions as $region_id )
			{
				$list[] = geo_peer::instance()->get_region($region_id);
			}
		}


		$this->set_renderer('ajax');
		$this->json = $list;
	}
}
?>
