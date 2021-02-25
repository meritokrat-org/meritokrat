<?
load::app('modules/reform/controller');

class reform_get_list_action extends reform_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->set_layout(null);
		$list = db::get_cols('SELECT id FROM user_auth WHERE active = true');

		$this->projectId = request::get_int("project_id");

		foreach ($list as $item)
			$this->list[] = user_auth_peer::instance()->get_item($item["id"]);

		$this->pager = pager_helper::get_pager($this->list, request::get_int('page', 1), 12);
	}
}