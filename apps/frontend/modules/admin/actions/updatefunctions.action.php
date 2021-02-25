<?

class admin_updatefunctions_action extends basic_controller
{
	public function execute()
	{
		load::view_helper('user');
		load::model('user/user_desktop');
		$users = db::get_cols('SELECT user_id FROM user_desktop WHERE
           functions !=:ar', array('ar' => '{}'), $this->connection_name);
		foreach ($users as $id) {
			#echo user_helper::photo_path( $id);
			$data = user_desktop_peer::instance()->get_item($id);
			if ($data['functions']) {
				$user_functions = explode(',', str_replace(array('{', '}'), array('', ''), $data['functions']));
				$array = array_flip($user_functions); //Меняем местами ключи и значения
				unset ($array[9]); //Удаляем элемент массива
				unset ($array[10]); //Удаляем элемент массива
				$user_functions = array_flip($array); //Меняем местами ключи и значения
				$user_functions = "{" . implode(",", $user_functions) . "}";
				user_desktop_peer::instance()->update(array(
					'user_id' => $data['user_id'],
					'functions' => $user_functions,
				));
				echo $data['functions'];
//print_r($user_functions);
			}
		}
		die();
	}
}
