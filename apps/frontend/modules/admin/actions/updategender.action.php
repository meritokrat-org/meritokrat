<?
load::app('modules/admin/controller');

class admin_updategender_action extends admin_controller
{
	public function execute()
	{
		if (request::get('send')) {
			load::model('user/user_data');
			foreach (request::get('users') as $k => $u) {
				user_data_peer::instance()->update(array("user_id" => $k, "gender" => $u));
			}
		} else {
			load::view_helper('user');
			load::model('user/user_desktop');
			$this->users = db::get_rows("SELECT user_id,first_name,last_name
           FROM user_data JOIN user_auth ON user_data.user_id=user_auth.id 
           WHERE user_auth.active=true AND (user_data.gender!='m' AND user_data.gender !='f') LIMIT 50",
				array(), $this->connection_name);
		}
	}
}
