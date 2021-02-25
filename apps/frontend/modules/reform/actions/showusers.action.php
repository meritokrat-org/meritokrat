<?
load::app('modules/reform/controller');

class reform_showusers_action extends reform_controller
{
	public function execute()
	{
		load::action_helper('page', false);
		$this->disable_layout();
		$this->users = array();
	}
}