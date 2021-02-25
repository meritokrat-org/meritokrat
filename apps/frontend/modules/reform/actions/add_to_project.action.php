<?
load::app('modules/reform/controller');

class reform_add_to_project_action extends reform_controller
{
	protected $authorized_access = true;

	public function execute()
	{
		$this->set_renderer(null);
		$projectId = request::get_int("project_id");

		foreach(request::get("fs") as $uid)
			reform_members_peer::instance()->insert(["group_id" => $projectId, "user_id" => $uid]);
	}
}