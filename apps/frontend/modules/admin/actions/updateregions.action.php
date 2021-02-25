<?

class admin_updateregions_action extends basic_controller
{
	public function execute()
	{
		load::view_helper('user');
		load::model('user/user_desktop');
		load::model('user/user_desktop_funct');
		#$users = db::get_rows("SELECT user_id,regions FROM user_desktop WHERE
		#    regions !=''", array(), $this->connection_name);
		$users = db::get_rows("SELECT user_id,regions,functions FROM user_desktop WHERE
          regions !=''", array(), $this->connection_name);
		#print_r($users);
		$i = 0;
		foreach ($users as $data) {
#$data = user_desktop_peer::instance()->get_item($id);
			if ($data['regions']) {
				$regions = unserialize($data['regions']);
				if ($data['functions']) {
					$user_functions = explode(',', str_replace(array('{', '}'), array('', ''), $data['functions']));
					foreach ($regions['region'] as $funct => $r) {
						if ($funct == 9 || $funct == 10 || !in_array($funct, $user_functions)) continue;
						$arr[$i]['user_id'] = $data['user_id'];
						$arr[$i]['region_id'] = $r;
						$arr[$i]['city_id'] = intval($regions['city'][$funct]);
						$arr[$i]['function_id'] = $funct;
						user_desktop_funct_peer::instance()->insert($arr[$i]);
						$i++;
					}
				}
			}
		}
		print_r($arr);
		die();
	}
}
