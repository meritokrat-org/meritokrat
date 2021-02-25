<?php

load::model("inquirer/inquirer");

class inquirer_edit_action extends frontend_controller {
	
	protected $status = 0;
	
	public function execute(){
		
		//echo serialize(array(11752));
		
		switch(request::get("act")){
			case "stop":
				$id = request::get("id");
				$id = inquirer_peer::instance()->stop($id);
				echo json_encode(array(
					"status" => $this->status,
					"id" => $id
				));
				exit(0);
				break;
			
			case "start":
				$id = request::get("id");
				$id = inquirer_peer::instance()->start($id);
				echo json_encode(array(
					"status" => $this->status,
					"id" => $id
				));
				exit(0);
				break;
			
			case "recover":
				
				$id = request::get("id");
				
				$id = inquirer_peer::instance()->recover($id);
				
				echo json_encode(array(
					"status" => $this->status,
					"id" => $id
				));
				exit(0);
				break;
			
			case "save":
				$this->disable_layout();
				
				$rq = request::get_all();
				
				$data = array();
				
				if(isset($rq["id"]))
					$data["id"] = $rq["id"];
				
				if(isset($rq["name"]))
					$data["name"] = $rq["name"];
				
				if(isset($rq["questions"]) && is_array($rq["questions"]))
					$data["questions"] = $rq["questions"];
				
				if(isset($rq["answers"]) && is_array($rq["answers"]))
					$data["answers"] = $rq["answers"];
				
				if(!$rq["id"])
					$data["creator"] = session::get_user_id();
				
				$id = inquirer_peer::instance()->save($data);
				
				echo json_encode(array(
					"status" => $this->status,
					"id" => $id
				));
				exit(0);
				
				break;
			
			case "get":
				$this->disable_layout();
				
				$id = request::get_int("id");
				$data = inquirer_peer::instance()->get($id);
				if( ! $data)
					$this->status = 1;
				
				echo json_encode(array(
					"status" => $this->status,
					"name" => $data["name"],
					"questions" => $data["questions"]
				));
				exit(0);
				
				break;
				
			case "reply":
				
				$rq = request::get_all();
				
				$data = array();
				$data["id"] = $rq["id"];
				$data["answers"] = $rq["answers"];
				
				if( ! inquirer_peer::instance()->reply($data)){
					$this->status = 1;
				}
				
				echo json_encode(array(
					"status" => $this->status,
				));
				exit(0);
				
				break;
			
			case "delete":
				
				inquirer_peer::instance()->delete(request::get_int("id"));
				
				echo json_encode(array(
					"status" => $this->status,
				));
				exit(0);
				
				break;
			
			case "assign":
				
				$rq = request::get_all();
				
				$rlt = array();
				foreach($rq["criterion"] as $filter => $keys){
					$filter_values = array();
					foreach($keys as $key => $val)
						$filter_values[] = $key;
					switch ($filter) {
						case 'common':
							$list = user_auth_peer::instance()->get_list(array('del' => 0), array(), array('id ASC'));
							break;

						case 'group':
							load::model('groups/members');
							$list = db::get_cols('SELECT user_id FROM ' . groups_members_peer::instance()->get_table_name() . ' WHERE group_id IN (' . implode(',', $filter_values) . ')');
							break;

						case 'ppo':
							load::model('ppo/ppo');
							load::model('ppo/members');
							$ppo = ppo_peer::instance()->get_item($filter_values[0]);
							$list = ppo_members_peer::instance()->get_members($ppo['id'], false, $ppo);
							break;

						case 'status':
							$list = db::get_cols('SELECT id as user_id FROM ' . user_auth_peer::instance()->get_table_name() . ' WHERE status IN (' . implode(',', $filter_values) . ')');
							break;

						case 'func':
							load::model('user/user_desktop');
							foreach ($filter_values as $id => $a) {
								$where[] = "functions && '{" . $a . "}'";
							}
							$list = db::get_cols('SELECT user_id FROM ' . user_desktop_peer::instance()->get_table_name() . ' WHERE ' . implode(' OR ', $where));
							break;

						case 'region':
							$list = db::get_cols('SELECT user_id FROM ' . user_data_peer::instance()->get_table_name() . ' WHERE region_id IN (' . implode(',', $filter_values) . ')');
							break;

						case 'lists':
							load::model('lists/lists_users');
							$list = db::get_cols('SELECT user_id FROM ' . lists_users_peer::instance()->get_table_name() . ' WHERE list_id IN (' . implode(',', $filter_values) . ') AND type = 0');
							break;

						case 'sferas':
							$list = db::get_cols('SELECT user_id FROM ' . user_data_peer::instance()->get_table_name() . ' WHERE segment IN (' . implode(',', $filter_values) . ')');
							break;

						case 'political_views':
							load::model('political_views');
							$list = db::get_cols('SELECT user_id FROM ' . user_data_peer::instance()->get_table_name() . ' WHERE political_views = ' . $filter_values);
							break;

						case 'targets':
							$i = 0;
							foreach ($filter_values as $fv) {
								$sqladd.='admin_target && \'{' . $fv . '}\' ';
								if ($i < count($filter_values) - 1)
									$sqladd.=' OR ';
								$i++;
							}
							$list = db::get_cols('SELECT user_id
                                FROM user_data WHERE ' . $sqladd);
							break;
							
					}
					$rlt = array_merge($rlt, $list);
				}
				
				inquirer_peer::instance()->assign($rq["id"], $rlt);
				
				echo json_encode(array(
					"status" => $this->status,
					"id" => $rq["id"],
					"count" => count($rlt)
				));
				exit(0);
				
				break;
				
			default:
				$this->inquirer_id = request::get_int("id");
				break;
		}
	}
	
}

?>
