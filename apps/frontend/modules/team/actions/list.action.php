<?

load::app('modules/team/controller');
class team_list_action extends team_controller
{
	public function execute()
	{
		$this->set_layout(null);

		$this->teams = db::get_rows("SELECT team_members.group_id FROM team_members LEFT JOIN lists2users ON team_members.user_id = lists2users.user_id WHERE lists2users.list_id = :list_id GROUP BY team_members.group_id;", ["list_id" => request::get_int("id")]);
	}
}