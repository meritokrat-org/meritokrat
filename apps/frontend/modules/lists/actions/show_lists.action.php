<?

load::app('modules/lists/controller');

class lists_show_lists_action extends lists_controller
{
	public function execute()
	{
		$this->disable_layout();
		$this->id = request::get_int('id');

		$this->invited = lists_users_peer::instance()->get_lists_by_user($this->id);
		$this->lists = lists_peer::instance()->get_list();
		$this->lists = array_diff($this->lists, $this->invited);
	}
}
