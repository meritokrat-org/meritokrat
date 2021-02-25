<?

load::app('modules/admin/controller');

class admin_del_recomendation_action extends admin_controller
{
	public function execute()
	{
		if ($id = request::get('del')) {
			if (db::exec("DELETE FROM user_recomendations WHERE id=" . $id)) die('1');
		}
		die('error');
	}
}
