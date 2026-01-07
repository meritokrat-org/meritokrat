<?

load::app('modules/ppo/controller');
class ppo_new_action extends ppo_controller
{
	public function execute()
	{ load::model('groups/groups');
             $all_regions=geo_peer::instance()->get_regions(1);
                    foreach ($all_regions  as $region_id => $title ) {   
            $count_users=db::get_scalar('SELECT count(*) 
                FROM ppo 
                WHERE region_id=:region_id '.$sqladd,
                    array('region_id'=>$region_id));
            $this->az_regions[]=array('id'=>$region_id, 'title'=>$title,'count'=>$count_users);
            $this->rate_regions[$count_users.($region_id)]=array('id'=>$region_id, 'title'=>$title, 'count'=>$count_users);
            ksort($this->rate_regions);
            }
		$this->hot = ppo_peer::instance()->get_new();

		load::action_helper('pager');
		$this->pager = pager_helper::get_pager($this->hot, request::get_int('page'), 10);
		$this->hot = $this->pager->get_list();
	}
}