<?

load::app('modules/team/controller');

class team_showusers_action extends team_controller
{
	public function execute()
	{
		load::action_helper('page', false);
		$this->disable_layout();
		$this->users = array();
	}
}