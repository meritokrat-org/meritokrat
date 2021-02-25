<?
load::app('modules/team/controller');

class team_search_action extends team_controller
{
	public function execute()
	{
		$this->set_template('index');
		if (trim(request::get_string('req')) == '') {
			$this->redirect('/team');
		}

		$all_regions = geo_peer::get_regions(1);


		foreach ($all_regions as $region_id => $title) {
			$count_users = db::get_scalar('SELECT count(*)
                FROM team
                WHERE region_id=:region_id ' . $sqladd,
				array('region_id' => $region_id));
			$this->az_regions[] = array('id' => $region_id, 'title' => $title, 'count' => $count_users);
			$this->rate_regions[$count_users . ($region_id)] = array('id' => $region_id, 'title' => $title, 'count' => $count_users);
			ksort($this->rate_regions);
		}
		$this->hot = team_peer::instance()->search_by_title(request::get_string('req'));

		load::action_helper('pager');
		$this->pager = pager_helper::get_pager($this->hot, request::get_int('page'), 15);
		$this->hot = $this->pager->get_list();
	}
}