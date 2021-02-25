<?php

class inquirer_peer extends db_peer_postgre {
	
	protected $table_name = 'inquirers';
	
	public static function instance(){
		return parent::instance("inquirer_peer");
	}
	
	public function save($data){
		
		if( ! $data["id"])
			unset($data["id"]);
		
		if(isset($data["questions"]) && is_array($data["questions"]))
			$data["questions"] = serialize($data["questions"]);
		
		if(isset($data["answers"]) && is_array($data["answers"]))
			$data["answers"] = serialize($data["answers"]);
			
		
		if( ! $data["id"])
			$data["created"] = date("Y/m/d h:i:s");
		
		$data["modified"] = date("Y/m/d h:i:s");
		
		if(isset($data["assigned_for"]) && is_array($data["assigned_for"]))
			$data["assigned_for"] = serialize($data["assigned_for"]);
		
		if($data["id"]){
			$id = $data["id"];
			$keys = array("id" => $data["id"]);
			parent::update($data, $keys);
		} else {
			$id = parent::insert($data);
		}
		
		return $id;
		
	}
	
	public function get($id){
		$data = db::get_row("SELECT * FROM ".$this->table_name." WHERE id = :id LIMIT 1", array('id' => $id));
		if($data){
			$data["name"] = trim($data["name"]);
			
			$data["questions"] = unserialize($data["questions"]);

			if($data["answers"])
				$data["answers"] = unserialize($data["answers"]);
			else
				$data["answers"] = array();
			
			if($data["assigned_for"])
				$data["assigned_for"] = unserialize($data["assigned_for"]);
			else
				$data["assigned_for"] = array();
			
			$data["created"] = preg_split("/[-\s:]/s", $data["created"]);
			$data["modified"] = preg_split("/[-\s:]/s", $data["modified"]);
		} else {
			$data = null;
		}
		
		return $data;
		
	}
		
	public function get_inquirers($switch = false){
		$sql = "SELECT id FROM {$this->table_name} WHERE";
		
		$flag = $switch ? true : false;
		
		switch($switch){
			case "assigned_for":
				$sql .= " assigned_for IS NOT NULL";
				break;
			
			case "published":
				$sql .= " assigned_for IS NOT NULL AND published > 0";
				break;
			
		}
		
		if($flag)
			$sql .= " AND";
		
		$sql .= " (deleted IS NULL OR deleted = 0)";
		
		$sql .= " ORDER BY id DESC";
		
		$list = db::get_cols($sql);
		return $list;
	}
	
	public function init_popup(){
		
		//session::set("inquirer_popup", false);
		if( ! session::get("inquirer_popup")){
			session::set("inquirer_popup", true);
		} else {
			return false;
		}
		
		$user_id = session::get_user_id();
		
		$list = $this->get_inquirers("published");
		foreach($list as $id){
			$item = $this->get($id);
			if(is_array($item["assigned_for"])){
				if(in_array($user_id, $item["assigned_for"])){
					return $id;
				}
			}
		}
		
		return false;
	}
	
	public function reply($data){
		
		$user_id = session::get_user_id();
		$inquirer_id = $data["id"];
		
		$item = $this->get($inquirer_id);
		
		for($i = 0; $i < count($item["assigned_for"]); $i++){
			if($item["assigned_for"][$i] == $user_id){
				unset($item["assigned_for"][$i]);
				break;
			}
		}
		
		$answers = array($user_id => array());
		foreach($data["answers"] as $key => $answer){
			foreach($answer as $tok){
				if(isset($tok["value"]))
					$value = $tok["value"];
				else {
					$tok["name"] = explode("-", $tok["name"]);
					$value = $item["questions"][$key]["variants"][$tok["name"][1]];
				}
				if(isset($answers[$user_id][$item["questions"][$key]["question"]])){
					if( ! is_array($answers[$user_id][$item["questions"][$key]["question"]])){
						$answers[$user_id][$item["questions"][$key]["question"]] = array(
							$answers[$user_id][$item["questions"][$key]["question"]],
							$value
						);
					} else {
						array_push($answers[$user_id][$item["questions"][$key]["question"]], $value);
					}
				} else {
					$answers[$user_id][$item["questions"][$key]["question"]] = $value;
				}
			}
		}
		
		$item["answers"][$user_id] = $answers[$user_id];
		
		$rt_arr = array(
			"id" => $inquirer_id,
			"answers" => $item["answers"],
			"assigned_for" => $item["assigned_for"]
		);
		
		return $this->save($rt_arr);
		
	}
	
	public function delete($inquirer_id){
		return parent::update(array("deleted" => "1"), array("id" => $inquirer_id));
	}
	
	public function assign($inquirer_id, $user_id_list){
		$keys = array("id" => $inquirer_id);
		
		$data["assigned_for"] = serialize($user_id_list);
		
		parent::update($data, $keys);
	}
	
	public function recover($inquirer_id){
		
		$keys = array("id" => $inquirer_id);
		
		$data["deleted"] = '0';
		
		parent::update($data, $keys);
		
		return $inquirer_id;
		
	}
	
	public function start($inquirer_id){
		$keys = array("id" => $inquirer_id);
		
		$data["published"] = '1';
		
		parent::update($data, $keys);
		
		return $inquirer_id;
	}
	
	public function stop($inquirer_id){
		$keys = array("id" => $inquirer_id);
		
		$data["published"] = '0';
		
		parent::update($data, $keys);
		
		return $inquirer_id;
	}
	
}

?>
