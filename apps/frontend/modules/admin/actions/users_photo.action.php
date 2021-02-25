<?

load::app('modules/admin/controller');

class admin_users_photo_action extends admin_controller
{
	public function execute()
	{
		if(request::get_int("user_id") > 0){
			$this->set_renderer("ajax");
			$this->set_layout(null);

			db::exec("UPDATE user_data SET photo_salt = NULL WHERE user_id = :user_id", ["user_id" => request::get_int("user_id")]);

			$this->json["success"] = false;
			if(count(db::get_rows("SELECT user_id FROM user_data WHERE photo_salt = NULL") == 0))
				$this->json["success"] = true;
		}else{
			load::action_helper('pager', true);
			$this->list = db::get_rows("SELECT user_id AS id, photo_salt AS salt, first_name AS fname, last_name AS lname FROM user_data WHERE photo_salt IS NOT NULL ORDER BY user_id");
			$this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 42);
			$this->list = $this->pager->get_list();
		}
	}
}