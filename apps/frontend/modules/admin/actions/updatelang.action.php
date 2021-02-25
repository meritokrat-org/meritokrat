<?

class admin_updatelang_action extends basic_controller
{
	public function execute()
	{
		load::view_helper('user');
		load::model('user/user_data');
//        $cols=user_data_peer::instance()->
//        db::exec("UPDATE user_data SET");
	}
}
