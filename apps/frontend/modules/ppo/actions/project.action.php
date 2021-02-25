<?

load::app('modules/ppo/controller');
class ppo_project_action extends ppo_controller
{
	public function execute()
	{
		$this->hot = ppo_peer::instance()->get_project_new();

		load::action_helper('pager');
		$this->pager = pager_helper::get_pager($this->hot, request::get_int('page'), 10);
		$this->hot = $this->pager->get_list();
	}
}