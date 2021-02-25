<?

load::app('modules/groups/controller');
class groups_new_action extends groups_controller
{
	public function execute()
	{
		$this->hot = groups_peer::instance()->get_new();

		load::action_helper('pager');
		$this->pager = pager_helper::get_pager($this->hot, request::get_int('page'), 10);
		$this->hot = $this->pager->get_list();
	}
}