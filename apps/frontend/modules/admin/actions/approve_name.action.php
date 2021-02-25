<?
load::app('modules/admin/controller');

class admin_approve_name_action extends admin_controller
{
	public function execute()
	{

		if(request::get_int("id", 0) > 0)
		{
			$user = user_data_peer::instance()->get_item(request::get_int("id"));

			if( ! request::get_bool("approve"))
				$_data = [
					"user_id" => request::get_int("id"),
					"first_name" => $user["first_name"],
					"last_name" => $user["last_name"],
					"new_fname" => null,
					"new_lname" => null
				];
			else
			{
				$_data = [
					"user_id" => request::get_int("id"),
					"new_fname" => null,
					"new_lname" => null
				];
				$_data["first_name"] = $user["new_fname"] != "" ? $user["new_fname"] : $user["first_name"];
				$_data["last_name"] = $user["new_lname"] != "" ? $user["new_lname"] : $user["last_name"];
			}

			user_data_peer::instance()->update($_data);
		}
		else
		{
			load::model('user/user_data');
			$this->list = user_data_peer::instance()->get_new_names();
		}
	}
}