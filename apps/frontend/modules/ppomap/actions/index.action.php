<?
load::app('modules/ppo/controller');
class ppomap_index_action extends ppo_controller
{
	public function execute()
	{
            $this->groups=ppo_peer::instance()->get_list(array("active"=>1));
	}
}