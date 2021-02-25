<?
load::app('modules/groups/controller');

class groups_showusers_action extends groups_controller
{
	public function execute()
	{
		load::action_helper('page', false);
		$this->disable_layout();
		$this->users = array();
	}
}