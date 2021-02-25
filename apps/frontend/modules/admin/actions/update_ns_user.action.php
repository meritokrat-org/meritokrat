<?

load::app('modules/admin/controller');
class admin_update_ns_user_action extends admin_controller
{
	public function execute()
	{
		
                load::model('user/user_novasys_data');
		if ($id=request::get_int('id'))
                {
                    if (db::exec("UPDATE user_novasys_data SET user_id=".request::get_int('name')." WHERE novasys_id=".$id)) die('1');
                }
		elseif ($id=request::get('del'))
                {
                    if (db::exec("DELETE FROM user_novasys_data WHERE novasys_id=".str_replace('r_','',$id))) die('1');
                }
                die('error');
	}
}
