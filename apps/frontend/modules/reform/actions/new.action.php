<?

load::app('modules/reform/controller');

class reform_new_action extends reform_controller
{
	public function execute()
	{
		load::model('groups/groups');
		$all_regions = geo_peer::get_regions(1);
		foreach ($all_regions as $region_id => $title) {
			$count_users = db::get_scalar('SELECT count(*)
                FROM reform
                WHERE region_id=:region_id ' . $sqladd,
				array('region_id' => $region_id));
			$this->az_regions[] = array('id' => $region_id, 'title' => $title, 'count' => $count_users);
			$this->rate_regions[$count_users . ($region_id)] = array('id' => $region_id, 'title' => $title, 'count' => $count_users);
			ksort($this->rate_regions);
		}
		$this->hot = reform_peer::instance()->get_new();

		load::action_helper('pager');
		$this->pager = pager_helper::get_pager($this->hot, request::get_int('page'), 10);
		$this->hot = $this->pager->get_list();
	}
}